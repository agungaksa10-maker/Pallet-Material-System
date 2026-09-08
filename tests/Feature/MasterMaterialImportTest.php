<?php

namespace Tests\Feature;

use App\Models\MasterMaterial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use ZipArchive;

class MasterMaterialImportTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'user_id' => 'admin_import',
            'name' => 'Import Admin',
            'email' => 'import@pallet.local',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }

    public function test_unauthenticated_user_cannot_import_materials(): void
    {
        $file = UploadedFile::fake()->create('materials.csv', 10, 'text/csv');

        $response = $this->post('/master-materials/import', [
            'file' => $file,
        ]);

        $response->assertRedirect('/login');
    }

    public function test_can_download_csv_template(): void
    {
        $response = $this->actingAs($this->user)->get('/master-materials/template');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('kode_part', $response->getContent());
        $this->assertStringContainsString('nama_material', $response->getContent());
        $this->assertStringContainsString('TURNKNIFE TK IV 330mm HHQ', $response->getContent());
    }

    public function test_can_import_materials_from_csv(): void
    {
        $csvContent = "kode_part,nama_material,kategori,satuan,spesifikasi,status\n"
            ."PART-001,Slitter Blade 330mm,Dressing,PCS,Tungsten carbide material,1\n"
            ."PART-002,Stretch Film Roll 500mm,Consumable,ROLL,Plastic wrap 300m,1\n"
            ."PART-003,Bearing Spacer 20mm,Dressing,SET,High precision,1\n";

        $file = UploadedFile::fake()->createWithContent('import_test.csv', $csvContent);

        $response = $this->actingAs($this->user)->post('/master-materials/import', [
            'file' => $file,
            'update_existing' => '1',
        ]);

        $response->assertRedirect('/master-materials');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('master_materials', [
            'item_code' => 'PART-001',
            'name' => 'Slitter Blade 330mm',
            'default_unit' => 'PCS',
        ]);

        $this->assertDatabaseHas('master_materials', [
            'item_code' => 'PART-002',
            'name' => 'Stretch Film Roll 500mm',
            'default_unit' => 'ROLL',
        ]);

        $this->assertDatabaseHas('master_materials', [
            'item_code' => 'PART-003',
            'name' => 'Bearing Spacer 20mm',
            'default_unit' => 'SET',
        ]);
    }

    public function test_can_import_csv_with_semicolon_delimiter(): void
    {
        $csvContent = "kode_part;nama_material;kategori;satuan;spesifikasi;status\r\n"
            ."SEMI-01;Roll Felt Standard;Dressing;ROLL;Felt pelindung;1\r\n"
            ."SEMI-02;Strapping Band PET;Consumable;ROLL;Tali strapping 19mm;1\r\n";

        $file = UploadedFile::fake()->createWithContent('import_semicolon.csv', $csvContent);

        $response = $this->actingAs($this->user)->post('/master-materials/import', [
            'file' => $file,
            'update_existing' => '1',
        ]);

        $response->assertRedirect('/master-materials');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('master_materials', [
            'item_code' => 'SEMI-01',
            'name' => 'Roll Felt Standard',
        ]);

        $this->assertDatabaseHas('master_materials', [
            'item_code' => 'SEMI-02',
            'name' => 'Strapping Band PET',
        ]);
    }

    public function test_can_update_existing_materials_on_reimport(): void
    {
        MasterMaterial::create([
            'item_code' => 'EXIST-01',
            'name' => 'Old Material Name',
            'category' => 'Dressing',
            'default_unit' => 'PCS',
            'is_active' => true,
        ]);

        $csvContent = "kode_part,nama_material,kategori,satuan,spesifikasi,status\n"
            ."EXIST-01,Updated Material Name,Consumable,BOX,New specification,1\n";

        $file = UploadedFile::fake()->createWithContent('reimport.csv', $csvContent);

        $response = $this->actingAs($this->user)->post('/master-materials/import', [
            'file' => $file,
            'update_existing' => '1',
        ]);

        $response->assertRedirect('/master-materials');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('master_materials', [
            'item_code' => 'EXIST-01',
            'name' => 'Updated Material Name',
            'default_unit' => 'BOX',
        ]);
    }

    public function test_rejects_empty_file(): void
    {
        $csvContent = "\n\n\n";
        $file = UploadedFile::fake()->createWithContent('empty.csv', $csvContent);

        $response = $this->actingAs($this->user)->post('/master-materials/import', [
            'file' => $file,
        ]);

        $response->assertRedirect('/master-materials');
        $response->assertSessionHas('error');
    }

    public function test_can_import_real_file_with_title_banners_and_custom_headers(): void
    {
        // Real spreadsheet with title banners on rows 1-2 and custom Indonesian headers on row 3
        $csvContent = "LAPORAN MASTER DATA SPAREPART PABRIK\n"
            ."SITE ANDRITZ OKI MILL - TANGGAL CETAK 2026-09-07\n"
            ."No,Kode Barang,Deskripsi Part Mesin,Jenis,Sat,Keterangan\n"
            ."1,300999111,Slitter Upper Blade Heavy Duty,Dressing,PCS,Pisau pemotong atas 350mm\n"
            ."2,CS-WRAP-01,Plastik Stretch Film 50cm,Consumable,ROLL,Wrapping pallet\n";

        $file = UploadedFile::fake()->createWithContent('real_custom.csv', $csvContent);

        $response = $this->actingAs($this->user)->post('/master-materials/import', [
            'file' => $file,
            'update_existing' => '1',
        ]);

        $response->assertRedirect('/master-materials');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('master_materials', [
            'item_code' => '300999111',
            'name' => 'Slitter Upper Blade Heavy Duty',
            'default_unit' => 'PCS',
        ]);

        $this->assertDatabaseHas('master_materials', [
            'item_code' => 'CS-WRAP-01',
            'name' => 'Plastik Stretch Film 50cm',
            'default_unit' => 'ROLL',
        ]);
    }

    public function test_can_import_raw_data_without_any_headers(): void
    {
        // No header row at all, just code and material name
        $csvContent = "300123456,Ceramic Roller Guide Bushing,Dressing,PCS\n"
            ."300123457,Tungsten Carbide Slitter Blade,Dressing,PCS\n";

        $file = UploadedFile::fake()->createWithContent('no_header.csv', $csvContent);

        $response = $this->actingAs($this->user)->post('/master-materials/import', [
            'file' => $file,
            'update_existing' => '1',
        ]);

        $response->assertRedirect('/master-materials');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('master_materials', [
            'item_code' => '300123456',
            'name' => 'Ceramic Roller Guide Bushing',
        ]);
    }

    public function test_can_import_xlsx_file(): void
    {
        // Generate a minimal valid XLSX archive
        $tempDir = sys_get_temp_dir();
        $xlsxPath = $tempDir.'/test_import_'.uniqid().'.xlsx';

        $zip = new ZipArchive;
        $this->assertTrue($zip->open($xlsxPath, ZipArchive::CREATE | ZipArchive::OVERWRITE));

        // Shared strings
        $sharedStringsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="10" uniqueCount="10">'
            .'<si><t>kode_part</t></si>'
            .'<si><t>nama_material</t></si>'
            .'<si><t>kategori</t></si>'
            .'<si><t>satuan</t></si>'
            .'<si><t>spesifikasi</t></si>'
            .'<si><t>status</t></si>'
            .'<si><t>XLSX-001</t></si>'
            .'<si><t>Excel Imported Ceramic Blade</t></si>'
            .'<si><t>Dressing</t></si>'
            .'<si><t>SET</t></si>'
            .'</sst>';

        // Sheet data
        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<sheetData>'
            .'<row r="1">'
            .'<c r="A1" t="s"><v>0</v></c>'
            .'<c r="B1" t="s"><v>1</v></c>'
            .'<c r="C1" t="s"><v>2</v></c>'
            .'<c r="D1" t="s"><v>3</v></c>'
            .'<c r="E1" t="s"><v>4</v></c>'
            .'<c r="F1" t="s"><v>5</v></c>'
            .'</row>'
            .'<row r="2">'
            .'<c r="A2" t="s"><v>6</v></c>'
            .'<c r="B2" t="s"><v>7</v></c>'
            .'<c r="C2" t="s"><v>8</v></c>'
            .'<c r="D2" t="s"><v>9</v></c>'
            .'<c r="E2" t="inlineStr"><is><t>High wear resistance</t></is></c>'
            .'<c r="F2"><v>1</v></c>'
            .'</row>'
            .'</sheetData>'
            .'</worksheet>';

        $zip->addFromString('xl/sharedStrings.xml', $sharedStringsXml);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->close();

        $uploadedFile = new UploadedFile(
            $xlsxPath,
            'test_materials.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->actingAs($this->user)->post('/master-materials/import', [
            'file' => $uploadedFile,
            'update_existing' => '1',
        ]);

        $response->assertRedirect('/master-materials');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('master_materials', [
            'item_code' => 'XLSX-001',
            'name' => 'Excel Imported Ceramic Blade',
            'default_unit' => 'SET',
        ]);

        if (file_exists($xlsxPath)) {
            @unlink($xlsxPath);
        }
    }
}
