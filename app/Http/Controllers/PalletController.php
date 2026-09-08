<?php

namespace App\Http\Controllers;

use App\Models\MasterMaterial;
use App\Models\PalletComponent;
use App\Models\PalletSticker;
use App\Models\User;
use App\Services\BarcodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PalletController extends Controller
{
    /**
     * All available production sites (Accessible by Admin).
     */
    public const SITES = [
        'OKI II' => 'OKI Mill II (Ogan Komering Ilir)',
        'IKPD' => 'IKPD (Indah Kiat Pulp & Paper - Perawang)',
        'IKPP' => 'IKPP (Indah Kiat Pulp & Paper - Serang)',
        'TELL' => 'TELL (Tjiwi Kimia - Sidoarjo)',
        'ISC' => 'ISC (Integrated Supply Chain)',
    ];

    /**
     * Production sites allowed for Operator role.
     */
    public const OPERATOR_SITES = [
        'OKI II' => 'OKI Mill II (Ogan Komering Ilir)',
    ];

    /**
     * Get available sites for the specified or authenticated user.
     * Admin gets all sites, operator gets OKI II only.
     *
     * @return array<string, string>
     */
    public static function getSitesForUser(?User $user = null): array
    {
        $user = $user ?? Auth::user();
        if ($user && $user->role === 'admin') {
            return self::SITES;
        }

        return self::OPERATOR_SITES;
    }

    /**
     * Available categories: Dressing, Consumable
     */
    public const CATEGORIES = [
        'Dressing' => 'Dressing Material (Roll, Stone, Blade, Tooling)',
        'Consumable' => 'Consumable Material (Packaging, Film, Ribbon, Banding)',
    ];

    /**
     * Preset materials (empty by default, users input real material data).
     */
    public const MATERIAL_PRESETS = [
        'Dressing' => [],
        'Consumable' => [],
    ];

    /**
     * Spare parts catalog (empty by default, users input real spare part data).
     */
    public const SPAREPART_CATALOG = [];

    /**
     * Display main pallet sticker generator dashboard.
     */
    public function index(Request $request): View
    {
        $selectedSite = $request->query('site', 'OKI II');
        $selectedCategory = $request->query('category', 'Dressing');
        $selectedPallet = (int) $request->query('pallet', 1);

        if ($selectedPallet < 1 || $selectedPallet > 500) {
            $selectedPallet = 1;
        }

        // Filter recent stickers
        $query = PalletSticker::query();

        if ($request->filled('filter_site')) {
            $query->where('site', $request->filter_site);
        }
        if ($request->filled('filter_category')) {
            $query->where('category', $request->filter_category);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('pallet_code', 'like', "%{$search}%")
                    ->orWhere('material_name', 'like', "%{$search}%")
                    ->orWhere('batch_no', 'like', "%{$search}%")
                    ->orWhere('kolom', 'like', "%{$search}%")
                    ->orWhere('tingkat', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhere('pallet_number', (int) $search)
                    ->orWhereHas('components', function ($cq) use ($search) {
                        $cq->where('component_name', 'like', "%{$search}%")
                            ->orWhere('batch_no', 'like', "%{$search}%")
                            ->orWhere('kolom', 'like', "%{$search}%")
                            ->orWhere('tingkat', 'like', "%{$search}%")
                            ->orWhere('notes', 'like', "%{$search}%");
                    });
            });
        }

        $user = Auth::user();
        $sitesForUser = self::getSitesForUser($user);

        // Specific KPI Statistics requested: Total Pallet, Total Site, Dressing, Consumable
        $totalPallet = PalletSticker::count();
        $totalSite = count($sitesForUser);
        $totalDressing = PalletSticker::where('category', 'Dressing')->count();
        $totalConsumable = PalletSticker::where('category', 'Consumable')->count();

        $allStickers = PalletSticker::with('components')->latest('id')->get();

        // Used pallet numbers for selected site
        $usedPalletNumbers = PalletSticker::getUsedPalletNumbers($selectedSite);

        return view('pallet.index', [
            'sites' => $sitesForUser,
            'allSites' => self::SITES,
            'categories' => self::CATEGORIES,
            'materialPresets' => self::MATERIAL_PRESETS,
            'selectedSite' => $selectedSite,
            'selectedCategory' => $selectedCategory,
            'selectedPallet' => $selectedPallet,
            'stickers' => $allStickers,
            'totalPallet' => $totalPallet,
            'totalSite' => $totalSite,
            'totalDressing' => $totalDressing,
            'totalConsumable' => $totalConsumable,
            'usedPalletNumbers' => $usedPalletNumbers,
        ]);
    }

    /**
     * Show form to create a new pallet sticker.
     */
    public function create(Request $request): View
    {
        $user = Auth::user();
        $availableSites = self::getSitesForUser($user);

        $selectedSite = $request->query('site');
        if (! $selectedSite || ! array_key_exists($selectedSite, $availableSites)) {
            $selectedSite = array_key_first($availableSites);
        }

        $selectedCategory = $request->query('category', 'Dressing');

        // Automatically determine next sequential unused pallet for the selected site
        $selectedPallet = PalletSticker::getNextAvailablePalletNumber($selectedSite);

        // Precompute used pallets by site for dynamic JS switching
        $usedPalletsBySite = [];
        foreach (array_keys($availableSites) as $siteKey) {
            $usedPalletsBySite[$siteKey] = PalletSticker::getUsedPalletNumbers($siteKey);
        }

        return view('pallet.create', [
            'sites' => $availableSites,
            'allSites' => self::SITES,
            'categories' => self::CATEGORIES,
            'materialPresets' => self::MATERIAL_PRESETS,
            'catalog' => self::SPAREPART_CATALOG,
            'selectedSite' => $selectedSite,
            'selectedCategory' => $selectedCategory,
            'selectedPallet' => $selectedPallet,
            'usedPalletsBySite' => $usedPalletsBySite,
            'usedPalletNumbers' => $usedPalletsBySite[$selectedSite] ?? [],
            'masterMaterials' => MasterMaterial::active()->orderBy('name')->get(),
        ]);
    }

    /**
     * Store new pallet sticker record.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $allowedSites = array_keys(self::getSitesForUser($user));

        $validated = $request->validate([
            'site' => ['required', 'string', 'in:'.implode(',', $allowedSites)],
            'category' => ['required', 'string', 'in:'.implode(',', array_keys(self::CATEGORIES))],
            'pallet_number' => ['required', 'integer', 'min:1', 'max:500'],
            'material_name' => ['nullable', 'string', 'max:255'],
            'batch_no' => ['nullable', 'string', 'max:100'],
            'quantity' => ['nullable', 'string', 'max:100'],
            'kolom' => ['nullable', 'string', 'max:10'],
            'tingkat' => ['nullable', 'string', 'max:10'],
            'notes' => ['nullable', 'string', 'max:500'],
            'components' => ['nullable', 'array'],
            'components.*.component_name' => ['nullable', 'string', 'max:255'],
            'components.*.quantity' => ['nullable', 'string', 'max:100'],
            'components.*.batch_no' => ['nullable', 'string', 'max:100'],
            'components.*.kolom' => ['nullable', 'string', 'max:10'],
            'components.*.tingkat' => ['nullable', 'string', 'max:10'],
            'components.*.notes' => ['nullable', 'string', 'max:500'],
            'action' => ['nullable', 'string', 'in:save,print,range_print'],
            'range_start' => ['nullable', 'integer', 'min:1', 'max:500'],
            'range_end' => ['nullable', 'integer', 'min:1', 'max:500'],
            'from_create' => ['nullable', 'boolean'],
        ], [
            'pallet_number.min' => 'Nomor pallet minimal 1.',
            'pallet_number.max' => 'Nomor pallet maksimal 500.',
            'site.in' => ($user && $user->role === 'admin')
                ? 'Site harus salah satu dari: '.implode(', ', array_keys(self::SITES)).'.'
                : 'Operator hanya diizinkan untuk Site OKI II.',
            'category.in' => 'Kategori harus Dressing atau Consumable.',
        ]);

        // Pallet uniqueness check per site
        if (PalletSticker::isPalletUsed($validated['site'], (int) $validated['pallet_number'])) {
            return back()->withInput()->withErrors([
                'pallet_number' => "Pallet #{$validated['pallet_number']} pada Site {$validated['site']} sudah terdaftar dan tidak dapat digunakan lagi.",
            ]);
        }

        $userId = Auth::user()?->user_id ?? 'OPERATOR';
        $batchNo = ! empty($validated['batch_no'])
            ? $validated['batch_no']
            : 'BATCH-'.date('Ymd').'-'.str_pad((string) $validated['pallet_number'], 3, '0', STR_PAD_LEFT);

        $palletCode = PalletSticker::generateCode(
            $validated['site'],
            $validated['category'],
            (int) $validated['pallet_number']
        );

        // Process components array
        $componentsData = [];
        if (! empty($validated['components'])) {
            foreach ($validated['components'] as $comp) {
                if (! empty(trim($comp['component_name'] ?? ''))) {
                    $componentsData[] = [
                        'component_name' => trim($comp['component_name']),
                        'quantity' => ($comp['quantity'] ?? null) ?: '1',
                        'batch_no' => ($comp['batch_no'] ?? null) ?: $batchNo,
                        'kolom' => ! empty($comp['kolom']) ? strtoupper(trim($comp['kolom'])) : null,
                        'tingkat' => ! empty($comp['tingkat']) ? trim($comp['tingkat']) : null,
                        'notes' => $comp['notes'] ?? null,
                    ];
                }
            }
        }

        // Fallback if no components submitted
        if (empty($componentsData)) {
            $defaultMaterial = ($validated['material_name'] ?? null) ?: ($validated['category'] === 'Dressing' ? 'Standard Dressing Unit' : 'Standard Consumable Item');
            $componentsData[] = [
                'component_name' => $defaultMaterial,
                'quantity' => ($validated['quantity'] ?? null) ?: '1 PALLET',
                'batch_no' => $batchNo,
                'kolom' => ! empty($validated['kolom']) ? strtoupper(trim($validated['kolom'])) : null,
                'tingkat' => ! empty($validated['tingkat']) ? trim($validated['tingkat']) : null,
                'notes' => $validated['notes'] ?? null,
            ];
        }

        // Determine pallet-level kolom & tingkat: from request or first non-empty component
        $palletKolom = ! empty($validated['kolom']) ? strtoupper(trim($validated['kolom'])) : null;
        $palletTingkat = ! empty($validated['tingkat']) ? trim($validated['tingkat']) : null;
        if (! $palletKolom || ! $palletTingkat) {
            foreach ($componentsData as $cd) {
                if (! $palletKolom && ! empty($cd['kolom'])) {
                    $palletKolom = $cd['kolom'];
                }
                if (! $palletTingkat && ! empty($cd['tingkat'])) {
                    $palletTingkat = $cd['tingkat'];
                }
            }
        }

        $componentNames = array_column($componentsData, 'component_name');
        $summaryMaterial = count($componentNames) > 0
            ? implode(', ', $componentNames)
            : 'Standard Material';

        $sticker = DB::transaction(function () use ($validated, $palletCode, $summaryMaterial, $batchNo, $userId, $componentsData, $palletKolom, $palletTingkat) {
            $record = PalletSticker::create([
                'site' => $validated['site'],
                'category' => $validated['category'],
                'pallet_number' => (int) $validated['pallet_number'],
                'pallet_code' => $palletCode,
                'material_name' => $summaryMaterial,
                'batch_no' => $batchNo,
                'quantity' => ($validated['quantity'] ?? null) ?: (string) count($componentsData),
                'kolom' => $palletKolom,
                'tingkat' => $palletTingkat,
                'notes' => $validated['notes'] ?? null,
                'user_id' => $userId,
                'printed_at' => now(),
            ]);

            foreach ($componentsData as $c) {
                $record->components()->create($c);
            }

            return $record;
        });

        if ($request->input('action') === 'print') {
            return redirect()->route('pallet.pdf.download', [
                'id' => $sticker->id,
                'mode' => 'stream',
            ]);
        }

        if ($request->boolean('from_create')) {
            return redirect()->route('pallet.show', $sticker->id)
                ->with('success', "Sticker Pallet #{$sticker->pallet_number} ({$sticker->pallet_code}) berhasil dibuat dengan ".count($componentsData).' komponen!');
        }

        return redirect()->route('pallet.index', [
            'site' => $validated['site'],
            'category' => $validated['category'],
            'pallet' => min(500, (int) $validated['pallet_number'] + 1),
        ])->with('success', "Sticker Pallet #{$sticker->pallet_number} ({$sticker->pallet_code}) berhasil disimpan dan siap dicetak!");
    }

    /**
     * Download or stream single / batch PDF sticker.
     */
    public function downloadPdf(Request $request, ?int $id = null): Response
    {
        $stickersData = [];

        if ($id) {
            $sticker = PalletSticker::findOrFail($id);
            $stickersData[] = $this->formatStickerPayload($sticker);
        } else {
            // Check if batch range is requested
            $rangeStart = (int) $request->query('start', 0);
            $rangeEnd = (int) $request->query('end', 0);

            if ($rangeStart >= 1 && $rangeEnd >= $rangeStart && $rangeEnd <= 500) {
                $site = $request->query('site', 'OKI II');
                $category = $request->query('category', 'Dressing');
                $material = $request->query('material_name', $category === 'Dressing' ? 'Standard Dressing Unit' : 'Standard Consumable');
                $quantity = $request->query('quantity', '1 PALLET');
                $userId = Auth::user()?->user_id ?? 'OPERATOR';

                for ($num = $rangeStart; $num <= $rangeEnd; $num++) {
                    $code = PalletSticker::generateCode($site, $category, $num);
                    $batch = 'BATCH-'.date('Ymd').'-'.str_pad((string) $num, 3, '0', STR_PAD_LEFT);

                    $stickersData[] = [
                        'site' => $site,
                        'category' => $category,
                        'category_code' => 'I-COS',
                        'pallet_number' => $num,
                        'pallet_code' => $code,
                        'material_name' => $material,
                        'batch_no' => $batch,
                        'quantity' => $quantity,
                        'notes' => $request->query('notes', ''),
                        'user_id' => $userId,
                        'printed_at' => now(),
                        'barcode_html' => BarcodeService::generateBarcodeHtml($code, 46),
                        'qr_svg' => BarcodeService::generateQrSvg("{$code}|{$site}|{$category}|PLT:{$num}", 80),
                    ];
                }
            } else {
                // Single on-the-fly or query-based
                $site = $request->query('site', 'OKI II');
                $category = $request->query('category', 'Dressing');
                $palletNumber = max(1, min(500, (int) $request->query('pallet', 1)));
                $material = $request->query('material_name', $category === 'Dressing' ? 'Standard Dressing Material' : 'Standard Consumable Material');
                $quantity = $request->query('quantity', '1 PALLET');
                $notes = $request->query('notes', '');
                $userId = Auth::user()?->user_id ?? 'OPERATOR';
                $code = PalletSticker::generateCode($site, $category, $palletNumber);
                $batch = $request->query('batch_no') ?: 'BATCH-'.date('Ymd').'-'.str_pad((string) $palletNumber, 3, '0', STR_PAD_LEFT);

                $stickersData[] = [
                    'site' => $site,
                    'category' => $category,
                    'category_code' => 'I-COS',
                    'pallet_number' => $palletNumber,
                    'pallet_code' => $code,
                    'material_name' => $material,
                    'batch_no' => $batch,
                    'quantity' => $quantity,
                    'notes' => $notes,
                    'user_id' => $userId,
                    'printed_at' => now(),
                    'barcode_html' => BarcodeService::generateBarcodeHtml($code, 46),
                    'qr_svg' => BarcodeService::generateQrSvg("{$code}|{$site}|{$category}|PLT:{$palletNumber}", 80),
                ];
            }
        }

        if ($request->query('mode') === 'print' || $request->query('mode') === 'html') {
            return response()->view('pdf.sticker', [
                'stickers' => $stickersData,
                'autoPrint' => ($request->query('mode') === 'print'),
            ]);
        }

        $pdf = Pdf::loadView('pdf.sticker', ['stickers' => $stickersData]);

        // Landscape paper matching ANDRITZ pallet sticker ratio (~150mm x 115mm)
        // Set portrait with [0, 0, 430, 345] because Dompdf swaps indices when set to 'landscape'
        $pdf->setPaper([0, 0, 430.00, 345.00], 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
        ]);

        $firstSticker = $stickersData[0] ?? null;
        $filename = $firstSticker
            ? "Pallet_Sticker_{$firstSticker['pallet_code']}.pdf"
            : 'Pallet_Stickers.pdf';

        if ($request->query('mode') === 'stream' || $request->query('inline') === '1') {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    /**
     * Format a PalletSticker model into view payload with barcodes.
     */
    protected function formatStickerPayload(PalletSticker $sticker): array
    {
        $code = $sticker->pallet_code ?: PalletSticker::generateCode($sticker->site, $sticker->category, $sticker->pallet_number);
        $catCode = 'I-COS';

        return [
            'id' => $sticker->id,
            'site' => $sticker->site,
            'category' => $sticker->category,
            'category_code' => $catCode,
            'pallet_number' => $sticker->pallet_number,
            'pallet_code' => $code,
            'material_name' => $sticker->material_name ?: 'Standard Material',
            'batch_no' => $sticker->batch_no ?: 'BATCH-'.date('Ymd'),
            'quantity' => $sticker->quantity ?: '1 PALLET',
            'kolom' => $sticker->kolom,
            'tingkat' => $sticker->tingkat,
            'notes' => $sticker->notes,
            'user_id' => $sticker->user_id ?: 'OPERATOR',
            'printed_at' => $sticker->printed_at ?: now(),
            'barcode_html' => BarcodeService::generateBarcodeHtml($code, 46),
            'qr_svg' => BarcodeService::generateQrSvg("{$code}|{$sticker->site}|{$sticker->category}|PLT:{$sticker->pallet_number}", 80),
        ];
    }

    /**
     * Display the specified pallet sticker detail.
     */
    public function show(int $id): View
    {
        $sticker = PalletSticker::with('components')->findOrFail($id);
        $payload = $this->formatStickerPayload($sticker);

        return view('pallet.show', [
            'sticker' => $sticker,
            'payload' => $payload,
            'sites' => self::SITES,
            'categories' => self::CATEGORIES,
        ]);
    }

    /**
     * Show the form for editing the specified pallet sticker.
     */
    public function edit(int $id): View
    {
        $sticker = PalletSticker::with('components')->findOrFail($id);
        $user = Auth::user();
        $availableSites = self::getSitesForUser($user);

        if (! array_key_exists($sticker->site, $availableSites)) {
            $availableSites[$sticker->site] = self::SITES[$sticker->site] ?? $sticker->site;
        }

        $usedPalletsBySite = [];
        foreach (array_keys($availableSites) as $siteKey) {
            $usedPalletsBySite[$siteKey] = PalletSticker::getUsedPalletNumbers($siteKey, $sticker->id);
        }

        return view('pallet.edit', [
            'sticker' => $sticker,
            'sites' => $availableSites,
            'allSites' => self::SITES,
            'categories' => self::CATEGORIES,
            'materialPresets' => self::MATERIAL_PRESETS,
            'catalog' => self::SPAREPART_CATALOG,
            'usedPalletsBySite' => $usedPalletsBySite,
            'usedPalletNumbers' => $usedPalletsBySite[$sticker->site] ?? [],
            'masterMaterials' => MasterMaterial::active()->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified pallet sticker in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $sticker = PalletSticker::findOrFail($id);
        $user = Auth::user();
        $allowedSites = array_keys(self::getSitesForUser($user));
        if ($user?->role !== 'admin' && ! in_array($sticker->site, $allowedSites)) {
            $allowedSites[] = $sticker->site;
        }

        $validated = $request->validate([
            'site' => ['required', 'string', 'in:'.implode(',', $allowedSites)],
            'category' => ['required', 'string', 'in:'.implode(',', array_keys(self::CATEGORIES))],
            'pallet_number' => ['required', 'integer', 'min:1', 'max:500'],
            'material_name' => ['nullable', 'string', 'max:255'],
            'batch_no' => ['nullable', 'string', 'max:100'],
            'quantity' => ['nullable', 'string', 'max:100'],
            'kolom' => ['nullable', 'string', 'max:10'],
            'tingkat' => ['nullable', 'string', 'max:10'],
            'notes' => ['nullable', 'string', 'max:500'],
            'components' => ['nullable', 'array'],
            'components.*.component_name' => ['nullable', 'string', 'max:255'],
            'components.*.quantity' => ['nullable', 'string', 'max:100'],
            'components.*.batch_no' => ['nullable', 'string', 'max:100'],
            'components.*.kolom' => ['nullable', 'string', 'max:10'],
            'components.*.tingkat' => ['nullable', 'string', 'max:10'],
            'components.*.notes' => ['nullable', 'string', 'max:500'],
            'action' => ['nullable', 'string', 'in:save,print'],
        ], [
            'pallet_number.min' => 'Nomor pallet minimal 1.',
            'pallet_number.max' => 'Nomor pallet maksimal 500.',
            'site.in' => ($user && $user->role === 'admin')
                ? 'Site harus salah satu dari: '.implode(', ', array_keys(self::SITES)).'.'
                : 'Operator hanya diizinkan untuk Site OKI II.',
            'category.in' => 'Kategori harus Dressing atau Consumable.',
        ]);

        if (PalletSticker::isPalletUsed($validated['site'], (int) $validated['pallet_number'], $sticker->id)) {
            return back()->withInput()->withErrors([
                'pallet_number' => "Pallet #{$validated['pallet_number']} pada Site {$validated['site']} sudah digunakan oleh pallet lain.",
            ]);
        }

        $batchNo = ! empty($validated['batch_no'])
            ? $validated['batch_no']
            : 'BATCH-'.date('Ymd').'-'.str_pad((string) $validated['pallet_number'], 3, '0', STR_PAD_LEFT);

        $palletCode = PalletSticker::generateCode(
            $validated['site'],
            $validated['category'],
            (int) $validated['pallet_number']
        );

        $componentsData = [];
        if (! empty($validated['components'])) {
            foreach ($validated['components'] as $comp) {
                if (! empty(trim($comp['component_name'] ?? ''))) {
                    $componentsData[] = [
                        'component_name' => trim($comp['component_name']),
                        'quantity' => ($comp['quantity'] ?? null) ?: '1',
                        'batch_no' => ($comp['batch_no'] ?? null) ?: $batchNo,
                        'kolom' => ! empty($comp['kolom']) ? strtoupper(trim($comp['kolom'])) : null,
                        'tingkat' => ! empty($comp['tingkat']) ? trim($comp['tingkat']) : null,
                        'notes' => $comp['notes'] ?? null,
                    ];
                }
            }
        }

        if (empty($componentsData)) {
            $defaultMaterial = ($validated['material_name'] ?? null) ?: ($validated['category'] === 'Dressing' ? 'Standard Dressing Unit' : 'Standard Consumable Item');
            $componentsData[] = [
                'component_name' => $defaultMaterial,
                'quantity' => ($validated['quantity'] ?? null) ?: '1 PALLET',
                'batch_no' => $batchNo,
                'kolom' => ! empty($validated['kolom']) ? strtoupper(trim($validated['kolom'])) : null,
                'tingkat' => ! empty($validated['tingkat']) ? trim($validated['tingkat']) : null,
                'notes' => $validated['notes'] ?? null,
            ];
        }

        // Determine pallet-level kolom & tingkat: from request or first non-empty component
        $palletKolom = ! empty($validated['kolom']) ? strtoupper(trim($validated['kolom'])) : null;
        $palletTingkat = ! empty($validated['tingkat']) ? trim($validated['tingkat']) : null;
        if (! $palletKolom || ! $palletTingkat) {
            foreach ($componentsData as $cd) {
                if (! $palletKolom && ! empty($cd['kolom'])) {
                    $palletKolom = $cd['kolom'];
                }
                if (! $palletTingkat && ! empty($cd['tingkat'])) {
                    $palletTingkat = $cd['tingkat'];
                }
            }
        }

        $componentNames = array_column($componentsData, 'component_name');
        $summaryMaterial = count($componentNames) > 0
            ? implode(', ', $componentNames)
            : 'Standard Material';

        DB::transaction(function () use ($sticker, $validated, $palletCode, $summaryMaterial, $batchNo, $componentsData, $palletKolom, $palletTingkat) {
            $sticker->update([
                'site' => $validated['site'],
                'category' => $validated['category'],
                'pallet_number' => (int) $validated['pallet_number'],
                'pallet_code' => $palletCode,
                'material_name' => $summaryMaterial,
                'batch_no' => $batchNo,
                'quantity' => ($validated['quantity'] ?? null) ?: (string) count($componentsData),
                'kolom' => $palletKolom,
                'tingkat' => $palletTingkat,
                'notes' => $validated['notes'] ?? null,
            ]);

            $sticker->components()->delete();
            foreach ($componentsData as $c) {
                $sticker->components()->create($c);
            }
        });

        if ($request->input('action') === 'print') {
            return redirect()->route('pallet.pdf.download', [
                'id' => $sticker->id,
                'mode' => 'stream',
            ]);
        }

        return redirect()->route('pallet.show', $sticker->id)
            ->with('success', "Data sticker {$sticker->pallet_code} berhasil diperbarui dengan ".count($componentsData).' komponen!');
    }

    /**
     * Display Component Locator & Search Dashboard.
     */
    public function components(Request $request): View
    {
        $search = $request->query('search', '');
        $selectedSite = $request->query('site', '');
        $selectedCategory = $request->query('category', '');

        $query = PalletComponent::with(['palletSticker.components']);

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('component_name', 'like', "%{$search}%")
                    ->orWhere('batch_no', 'like', "%{$search}%")
                    ->orWhere('kolom', 'like', "%{$search}%")
                    ->orWhere('tingkat', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('palletSticker', function ($sub) use ($search) {
                        $sub->where('pallet_code', 'like', "%{$search}%")
                            ->orWhere('pallet_number', (int) $search)
                            ->orWhere('kolom', 'like', "%{$search}%")
                            ->orWhere('tingkat', 'like', "%{$search}%")
                            ->orWhere('notes', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($selectedSite) && $selectedSite !== 'all') {
            $query->whereHas('palletSticker', function ($q) use ($selectedSite) {
                $q->where('site', $selectedSite);
            });
        }

        if (! empty($selectedCategory) && $selectedCategory !== 'all') {
            $query->whereHas('palletSticker', function ($q) use ($selectedCategory) {
                $q->where('category', $selectedCategory);
            });
        }

        $components = $query->latest('id')->paginate(20)->withQueryString();

        $totalComponents = PalletComponent::count();
        $totalPalletsWithComponents = PalletComponent::distinct('pallet_sticker_id')->count('pallet_sticker_id');

        $siteCounts = [];
        foreach (array_keys(self::SITES) as $s) {
            $siteCounts[$s] = PalletComponent::whereHas('palletSticker', fn ($q) => $q->where('site', $s))->count();
        }

        $user = Auth::user();
        $availableSites = self::getSitesForUser($user);

        return view('pallet.components', [
            'components' => $components,
            'search' => $search,
            'selectedSite' => $selectedSite,
            'selectedCategory' => $selectedCategory,
            'sites' => $availableSites,
            'allSites' => self::SITES,
            'categories' => self::CATEGORIES,
            'totalComponents' => $totalComponents,
            'totalPalletsWithComponents' => $totalPalletsWithComponents,
            'siteCounts' => $siteCounts,
        ]);
    }

    /**
     * Delete a sticker record from history.
     */
    public function destroy(int $id): RedirectResponse
    {
        $sticker = PalletSticker::findOrFail($id);
        $code = $sticker->pallet_code;
        $sticker->delete();

        return back()->with('info', "Data sticker {$code} berhasil dihapus dari riwayat.");
    }
}
