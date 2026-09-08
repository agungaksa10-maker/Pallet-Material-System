<?php

namespace App\Services;

use App\Models\MasterMaterial;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class MaterialImportService
{
    /**
     * Parse an uploaded spreadsheet file (.xlsx, .xls, .csv, .txt) into an array of rows.
     *
     * @return array<int, array<int, string>>
     */
    public function parseFile(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'xlsx' || $extension === 'xls') {
            return $this->parseXlsx($file->getRealPath());
        }

        return $this->parseCsv($file->getRealPath());
    }

    /**
     * Parse a CSV file with automatic delimiter detection and UTF-8 BOM removal.
     *
     * @return array<int, array<int, string>>
     */
    public function parseCsv(string $filePath): array
    {
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            return [];
        }

        // Detect delimiter from the first 4KB of the file
        $sample = fread($handle, 4096) ?: '';
        rewind($handle);

        $delimiter = ',';
        $semicolons = substr_count($sample, ';');
        $commas = substr_count($sample, ',');
        $tabs = substr_count($sample, "\t");

        if ($semicolons > $commas && $semicolons > $tabs) {
            $delimiter = ';';
        } elseif ($tabs > $commas && $tabs > $semicolons) {
            $delimiter = "\t";
        }

        $rows = [];
        $isFirstRow = true;

        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            if ($isFirstRow && isset($data[0])) {
                $data[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $data[0]);
                $isFirstRow = false;
            }

            $cleanRow = array_map(fn ($val) => trim((string) $val), $data);

            if (! empty(array_filter($cleanRow, fn ($v) => $v !== ''))) {
                $rows[] = $cleanRow;
            }
        }

        fclose($handle);

        return $rows;
    }

    /**
     * Parse an OpenXML Spreadsheet (.xlsx) using PHP's built-in ZipArchive and SimpleXML.
     * Inspects all sheets and selects the one with the most data.
     *
     * @return array<int, array<int, string>>
     */
    public function parseXlsx(string $filePath): array
    {
        $zip = new ZipArchive;
        if ($zip->open($filePath) !== true) {
            return $this->parseCsv($filePath);
        }

        // 1. Read Shared Strings (xl/sharedStrings.xml)
        $sharedStrings = [];
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');

        if ($sharedStringsXml !== false) {
            $xml = @simplexml_load_string($sharedStringsXml);
            if ($xml !== false) {
                foreach ($xml->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string) $si->t;
                    } elseif (isset($si->r)) {
                        $text = '';
                        foreach ($si->r as $r) {
                            $text .= (string) ($r->t ?? '');
                        }
                        $sharedStrings[] = $text;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        // 2. Find worksheet with the most data rows
        $candidateSheets = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (preg_match('#^xl/worksheets/.*\.xml$#', $filename)) {
                $candidateSheets[] = $filename;
            }
        }

        if (empty($candidateSheets)) {
            $zip->close();

            return [];
        }

        $bestSheetXml = '';
        $bestRowCount = -1;

        foreach ($candidateSheets as $sheetFile) {
            $content = $zip->getFromName($sheetFile);
            if ($content !== false) {
                $rowCount = substr_count($content, '<row');
                if ($rowCount > $bestRowCount) {
                    $bestRowCount = $rowCount;
                    $bestSheetXml = $content;
                }
            }
        }

        $zip->close();

        if (empty($bestSheetXml)) {
            return [];
        }

        $xml = @simplexml_load_string($bestSheetXml);
        if ($xml === false || ! isset($xml->sheetData)) {
            return [];
        }

        $rows = [];

        foreach ($xml->sheetData->row as $rowNode) {
            $rowArray = [];
            $maxCol = 0;

            foreach ($rowNode->c as $cell) {
                $cellRef = (string) ($cell['r'] ?? 'A1');
                $colIndex = $this->columnRefToIndex($cellRef);
                $type = (string) ($cell['t'] ?? '');

                $value = '';
                if ($type === 's') {
                    $idx = (int) ($cell->v ?? 0);
                    $value = $sharedStrings[$idx] ?? '';
                } elseif ($type === 'inlineStr' && isset($cell->is->t)) {
                    $value = (string) $cell->is->t;
                } elseif (isset($cell->v)) {
                    $value = (string) $cell->v;
                }

                $rowArray[$colIndex] = trim($value);
                if ($colIndex > $maxCol) {
                    $maxCol = $colIndex;
                }
            }

            $normalizedRow = [];
            for ($c = 0; $c <= $maxCol; $c++) {
                $normalizedRow[$c] = $rowArray[$c] ?? '';
            }

            if (! empty(array_filter($normalizedRow, fn ($v) => $v !== ''))) {
                $rows[] = $normalizedRow;
            }
        }

        return $rows;
    }

    /**
     * Convert Excel column letter reference (e.g., 'A1', 'BC14') to 0-indexed column integer.
     */
    protected function columnRefToIndex(string $cellRef): int
    {
        preg_match('/^([A-Z]+)/i', strtoupper($cellRef), $matches);
        $letters = $matches[1] ?? 'A';
        $number = 0;

        for ($i = 0; $i < strlen($letters); $i++) {
            $number = $number * 26 + (ord($letters[$i]) - ord('A') + 1);
        }

        return max(0, $number - 1);
    }

    /**
     * Import parsed rows into the master_materials database table.
     * Ultra-forgiving: automatically finds headers or detects column roles.
     *
     * @param  array<int, array<int, string>>  $rows
     * @return array{success: bool, total_rows: int, imported: int, updated: int, skipped: int, errors: array<int, string>}
     */
    public function import(array $rows, bool $updateExisting = true): array
    {
        if (empty($rows)) {
            return [
                'success' => false,
                'total_rows' => 0,
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => ['File spreadsheet kosong atau tidak berisi data yang dapat dibaca.'],
            ];
        }

        // 1. Locate header row or auto-detect columns without throwing errors
        [$columnMap, $dataRows] = $this->resolveColumnMapping($rows);

        if (empty($dataRows)) {
            return [
                'success' => true,
                'total_rows' => 0,
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => [],
            ];
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($dataRows as $row) {
                $nameIndex = $columnMap['name'] ?? 0;
                $name = trim($row[$nameIndex] ?? '');

                // Skip header-like echoes or empty rows
                if ($name === '' || $this->isHeaderEcho($name)) {
                    $skipped++;

                    continue;
                }

                $itemCode = isset($columnMap['item_code']) ? trim($row[$columnMap['item_code']] ?? '') : null;
                $itemCode = ($itemCode !== '' && ! $this->isHeaderEcho($itemCode)) ? $itemCode : null;

                $rawUnit = isset($columnMap['default_unit']) ? trim($row[$columnMap['default_unit']] ?? '') : '';
                $unit = $this->normalizeUnit($rawUnit);

                $specification = isset($columnMap['specification']) ? trim($row[$columnMap['specification']] ?? '') : null;
                $specification = ($specification !== '' && ! $this->isHeaderEcho($specification)) ? $specification : null;

                $rawActive = isset($columnMap['is_active']) ? trim($row[$columnMap['is_active']] ?? '') : '1';
                $isActive = $this->normalizeActiveStatus($rawActive);

                // Find existing record by item_code or name
                $existing = null;
                if ($itemCode) {
                    $existing = MasterMaterial::where('item_code', $itemCode)->first();
                }
                if (! $existing) {
                    $existing = MasterMaterial::where('name', $name)->first();
                }

                if ($existing) {
                    if ($updateExisting) {
                        $existing->update([
                            'item_code' => $itemCode ?: $existing->item_code,
                            'name' => $name,
                            'default_unit' => $unit,
                            'specification' => $specification ?: $existing->specification,
                            'is_active' => $isActive,
                        ]);
                        $updated++;
                    } else {
                        $skipped++;
                    }
                } else {
                    MasterMaterial::create([
                        'item_code' => $itemCode,
                        'name' => $name,
                        'default_unit' => $unit,
                        'specification' => $specification,
                        'is_active' => $isActive,
                    ]);
                    $imported++;
                }
            }

            DB::commit();

            return [
                'success' => true,
                'total_rows' => count($dataRows),
                'imported' => $imported,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors' => $errors,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'success' => false,
                'total_rows' => count($dataRows),
                'imported' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => ['Terjadi kesalahan saat menyimpan ke database: '.$e->getMessage()],
            ];
        }
    }

    /**
     * Resolve column mapping by scanning the first 15 rows for header keywords.
     * If no explicit header is found, intelligently auto-detect column roles by inspecting content.
     *
     * @param  array<int, array<int, string>>  $rows
     * @return array{0: array<string, int>, 1: array<int, array<int, string>>}
     */
    protected function resolveColumnMapping(array $rows): array
    {
        $maxScan = min(15, count($rows));

        // Attempt 1: Scan for an explicit header row containing name/item/material keywords
        for ($i = 0; $i < $maxScan; $i++) {
            $candidateRow = $rows[$i];

            // A genuine table header must have at least 2 non-empty columns
            $nonEmptyCount = count(array_filter($candidateRow, fn ($v) => trim((string) $v) !== ''));
            if ($nonEmptyCount < 2) {
                continue;
            }

            $map = $this->mapHeaders($candidateRow);

            if (isset($map['name'])) {
                // Header found at row $i; all rows after $i are data rows
                $dataRows = array_slice($rows, $i + 1);

                return [$map, $dataRows];
            }
        }

        // Attempt 2: Auto-detect columns based on content analysis of sample rows
        $sampleRows = array_slice($rows, 0, min(10, count($rows)));
        $autoMap = $this->autoDetectColumnRoles($sampleRows);

        // Treat all rows (or all rows except a text title row) as data
        $firstRowText = implode(' ', $rows[0] ?? []);
        if (preg_match('/(laporan|master|data|daftar|material|sparepart|tabel)/i', $firstRowText) && count(array_filter($rows[0])) <= 3) {
            $dataRows = array_slice($rows, 1);
        } else {
            $dataRows = $rows;
        }

        return [$autoMap, $dataRows];
    }

    /**
     * Map header labels to normalized attribute keys.
     * Supports comprehensive Indonesian and English terms.
     *
     * @param  array<int, string>  $headerRow
     * @return array<string, int>
     */
    protected function mapHeaders(array $headerRow): array
    {
        $map = [];

        $aliases = [
            'item_code' => [
                'item_code', 'itemcode', 'kode_part', 'kodepart', 'kode_item', 'kode', 'part_number',
                'part_no', 'partnumber', 'no_part', 'code', 'part_code', 'material_number', 'mat_number',
                'material_no', 'mat_no', 'kode_material', 'kode_barang', 'sap_code', 'sap_number',
                'nomor_part', 'nomor_material', 'nomor_barang', 'no_material', 'no_barang', 'id_material',
                'id_barang', 'id_part', 'article_no', 'article_number', 'sku', 'part_id', 'item_id',
            ],
            'name' => [
                'nama_material', 'namamaterial', 'nama_barang', 'namabarang', 'nama_item', 'namaitem',
                'nama_part', 'namapart', 'nama', 'material_name', 'name', 'item_name', 'part_name',
                'description', 'deskripsi', 'description_name', 'item_description', 'material_description',
                'mat_desc', 'desc', 'uraian', 'uraian_barang', 'nama_produk', 'produk', 'komponen',
                'nama_komponen', 'sparepart', 'spare_part', 'nama_sparepart', 'part', 'material',
                'barang', 'item', 'equipment', 'nama_alat', 'alat',
            ],
            'category' => [
                'kategori', 'category', 'cat', 'jenis', 'jenis_barang', 'tipe', 'group', 'kelompok',
                'klasifikasi', 'dept', 'divisi', 'klasifikasi_barang', 'tipe_barang',
            ],
            'default_unit' => [
                'satuan', 'unit', 'default_unit', 'uom', 'satuan_default', 'satuan_item', 'sat',
                'kemasan', 'packaging', 'unit_of_measure',
            ],
            'specification' => [
                'spesifikasi', 'spesifikasi_teknis', 'specification', 'spec', 'keterangan', 'ket',
                'catatan', 'notes', 'note', 'deskripsi_tambahan', 'remark', 'remarks', 'detail', 'lokasi',
                'rak', 'lokasi_rak', 'spesifikasi_barang',
            ],
            'is_active' => [
                'status', 'is_active', 'active', 'aktif', 'status_aktif', 'kondisi',
            ],
        ];

        foreach ($headerRow as $index => $header) {
            $clean = strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '_', $header)));
            $clean = trim($clean, '_');

            if ($clean === '') {
                continue;
            }

            foreach ($aliases as $key => $names) {
                if (! isset($map[$key]) && (in_array($clean, $names, true) || $this->isPartialMatch($clean, $names))) {
                    $map[$key] = $index;
                    break;
                }
            }
        }

        return $map;
    }

    /**
     * Check for partial match like "nama_material_lengkap" or "deskripsi_part".
     *
     * @param  array<int, string>  $names
     */
    protected function isPartialMatch(string $clean, array $names): bool
    {
        foreach ($names as $name) {
            if (strlen($name) >= 4 && str_contains($clean, $name)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Auto-detect column roles when no explicit header row is found.
     *
     * @param  array<int, array<int, string>>  $sampleRows
     * @return array<string, int>
     */
    protected function autoDetectColumnRoles(array $sampleRows): array
    {
        $map = [];
        $colStats = [];

        foreach ($sampleRows as $row) {
            foreach ($row as $colIdx => $val) {
                $val = trim((string) $val);
                if ($val === '') {
                    continue;
                }

                if (! isset($colStats[$colIdx])) {
                    $colStats[$colIdx] = [
                        'total_len' => 0,
                        'count' => 0,
                        'numeric_only' => true,
                        'sequential_index' => true,
                        'has_spaces' => false,
                        'values' => [],
                    ];
                }

                $colStats[$colIdx]['total_len'] += strlen($val);
                $colStats[$colIdx]['count']++;
                $colStats[$colIdx]['values'][] = $val;

                if (! is_numeric($val)) {
                    $colStats[$colIdx]['numeric_only'] = false;
                }
                if (str_contains($val, ' ')) {
                    $colStats[$colIdx]['has_spaces'] = true;
                }
            }
        }

        // 1. Identify "No" sequential index column (e.g. 1, 2, 3, 4) to exclude it
        $sequentialCols = [];
        foreach ($colStats as $colIdx => $stat) {
            if ($stat['numeric_only'] && $stat['count'] > 0) {
                $vals = array_map('intval', $stat['values']);
                if ($vals[0] <= 1 && end($vals) <= count($vals) + 2) {
                    $sequentialCols[] = $colIdx;
                }
            }
        }

        // 2. Identify candidate for "name" (the column with longest average text length and spaces)
        $bestNameCol = -1;
        $bestNameLen = -1;

        foreach ($colStats as $colIdx => $stat) {
            if (in_array($colIdx, $sequentialCols, true)) {
                continue;
            }

            $avgLen = $stat['count'] > 0 ? ($stat['total_len'] / $stat['count']) : 0;
            // Prefer columns with text and spaces (typical descriptions)
            $score = $avgLen + ($stat['has_spaces'] ? 10 : 0);

            if ($score > $bestNameLen) {
                $bestNameLen = $score;
                $bestNameCol = $colIdx;
            }
        }

        // Fallback: If no name column found, pick column 1 or 0
        if ($bestNameCol === -1) {
            $bestNameCol = isset($colStats[1]) ? 1 : 0;
        }
        $map['name'] = $bestNameCol;

        // 3. Identify item_code (short code column, alphanumeric, not sequential index)
        foreach ($colStats as $colIdx => $stat) {
            if ($colIdx === $bestNameCol || in_array($colIdx, $sequentialCols, true)) {
                continue;
            }

            $avgLen = $stat['count'] > 0 ? ($stat['total_len'] / $stat['count']) : 0;
            if ($avgLen >= 2 && $avgLen <= 25 && ! isset($map['item_code'])) {
                $map['item_code'] = $colIdx;
                break;
            }
        }

        // 4. Identify default_unit (e.g. PCS, UNIT, SET, ROLL, BOX)
        $knownUnits = ['PCS', 'UNIT', 'SET', 'ROLL', 'BOX', 'KG', 'M', 'LTR', 'PKT', 'BTG', 'LBR'];
        foreach ($colStats as $colIdx => $stat) {
            if ($colIdx === $bestNameCol || $colIdx === ($map['item_code'] ?? -1)) {
                continue;
            }

            foreach ($stat['values'] as $v) {
                if (in_array(strtoupper($v), $knownUnits, true)) {
                    $map['default_unit'] = $colIdx;
                    break 2;
                }
            }
        }

        // 5. Identify category (e.g. Dressing, Consumable, Sparepart)
        foreach ($colStats as $colIdx => $stat) {
            if ($colIdx === $bestNameCol || $colIdx === ($map['item_code'] ?? -1) || $colIdx === ($map['default_unit'] ?? -1)) {
                continue;
            }

            foreach ($stat['values'] as $v) {
                $lower = strtolower($v);
                if (str_contains($lower, 'dress') || str_contains($lower, 'consum') || str_contains($lower, 'spare')) {
                    $map['category'] = $colIdx;
                    break 2;
                }
            }
        }

        return $map;
    }

    /**
     * Check if a text value looks like a header label echo rather than genuine data.
     */
    protected function isHeaderEcho(?string $text): bool
    {
        if ($text === null) {
            return false;
        }

        $clean = strtolower(trim($text));
        if ($clean === '') {
            return false;
        }

        $headerWords = [
            'nama material', 'nama_material', 'namamaterial', 'material name', 'nama barang', 'nama_barang',
            'item name', 'part name', 'deskripsi', 'description', 'kode part', 'kode_part', 'item code',
            'part number', 'part no', 'kategori', 'category', 'satuan', 'unit', 'spesifikasi', 'status',
            'no', 'nomor', 'number',
        ];

        return in_array($clean, $headerWords, true);
    }

    /**
     * Normalize category value to 'Dressing' or 'Consumable'.
     */
    protected function normalizeCategory(string $value, string $nameContext = ''): string
    {
        $combined = strtolower(trim($value.' '.$nameContext));

        if (str_contains($combined, 'consum') || str_contains($combined, 'habis') || str_contains($combined, 'film') || str_contains($combined, 'strapp') || str_contains($combined, 'ribbon') || str_contains($combined, 'tape')) {
            return 'Consumable';
        }

        return 'Dressing';
    }

    /**
     * Normalize unit of measure string.
     */
    protected function normalizeUnit(string $value): string
    {
        $clean = strtoupper(trim($value));

        if ($clean === '' || strlen($clean) > 20 || $this->isHeaderEcho($clean)) {
            return 'UNIT';
        }

        return $clean;
    }

    /**
     * Normalize active status from various inputs (1, 0, true, false, aktif, ya, tidak).
     */
    protected function normalizeActiveStatus(string $value): bool
    {
        $clean = strtolower(trim($value));

        if (in_array($clean, ['0', 'false', 'tidak', 'no', 'nonaktif', 'inactive', 'off', 'n'], true)) {
            return false;
        }

        return true;
    }

    /**
     * Generate standard CSV template content with UTF-8 BOM and realistic ANDRITZ sample rows.
     */
    public function generateCsvTemplate(): string
    {
        $headers = ['kode_part', 'nama_material', 'satuan', 'spesifikasi', 'status'];

        $samples = [
            ['300944956', 'TURNKNIFE TK IV 330mm HHQ', 'PCS', 'Material pisau dressing standar slitter mill', '1'],
            ['300944957', 'TURNKNIFE TK IV 400mm HHQ', 'PCS', 'Pisau cadangan 400mm high precision', '1'],
            ['T0001', 'Roll Dressing Felt Standard', 'ROLL', 'Felt roll pelindung roll dressing', '1'],
            ['CS-001', 'Stretch Film 500mm x 300m', 'ROLL', 'Plastik wrapping pembungkus pallet mesin', '1'],
            ['CS-002', 'PET Strapping Band 19mm x 0.8mm', 'ROLL', 'Tali strapping pengikat muatan pallet', '1'],
            ['CS-003', 'Wax Thermal Transfer Ribbon 110mm x 300m', 'BOX', 'Ribbon cetak barcode dan stiker pallet', '1'],
        ];

        $csv = "\xEF\xBB\xBF";
        $csv .= implode(',', array_map(fn ($v) => '"'.str_replace('"', '""', $v).'"', $headers))."\r\n";

        foreach ($samples as $row) {
            $csv .= implode(',', array_map(fn ($v) => '"'.str_replace('"', '""', $v).'"', $row))."\r\n";
        }

        return $csv;
    }
}
