<?php

namespace Tests\Feature;

use App\Models\PalletComponent;
use App\Models\PalletSticker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PalletComponentFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'user_id' => 'operator01',
            'name' => 'Operator Test',
            'email' => 'operator@pallet.local',
            'password' => Hash::make('password123'),
            'role' => 'operator',
        ]);
    }

    public function test_can_create_pallet_with_multiple_components(): void
    {
        $response = $this->actingAs($this->user)->post('/pallet', [
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 1,
            'components' => [
                [
                    'component_name' => 'Knife Run',
                    'quantity' => '10 UNIT',
                    'batch_no' => 'BATCH-KR-001',
                    'notes' => 'Slitter knife',
                ],
                [
                    'component_name' => 'Guide Plate',
                    'quantity' => '4 PCS',
                    'batch_no' => 'BATCH-GP-001',
                    'notes' => 'Spacer plate',
                ],
            ],
            'action' => 'save',
        ]);

        $response->assertRedirect();

        $sticker = PalletSticker::where('site', 'OKI II')
            ->where('pallet_number', 1)
            ->first();

        $this->assertNotNull($sticker);
        $this->assertEquals('PLT-OKI2-DRS-001', $sticker->pallet_code);
        $this->assertStringContainsString('Knife Run', $sticker->material_name);
        $this->assertStringContainsString('Guide Plate', $sticker->material_name);

        $this->assertDatabaseHas('pallet_components', [
            'pallet_sticker_id' => $sticker->id,
            'component_name' => 'Knife Run',
            'quantity' => '10 UNIT',
            'batch_no' => 'BATCH-KR-001',
        ]);

        $this->assertDatabaseHas('pallet_components', [
            'pallet_sticker_id' => $sticker->id,
            'component_name' => 'Guide Plate',
            'quantity' => '4 PCS',
            'batch_no' => 'BATCH-GP-001',
        ]);

        $this->assertEquals(2, $sticker->components()->count());
    }

    public function test_pallet_number_cannot_be_reused_in_the_same_site(): void
    {
        // First pallet creation at OKI II (Pallet #1)
        PalletSticker::create([
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 1,
            'pallet_code' => 'PLT-OKI2-DRS-001',
            'material_name' => 'Knife Run',
            'user_id' => 'operator01',
        ]);

        // Attempt to create Pallet #1 again at OKI II must fail
        $response = $this->actingAs($this->user)->post('/pallet', [
            'site' => 'OKI II',
            'category' => 'Consumable',
            'pallet_number' => 1,
            'components' => [
                ['component_name' => 'Plastic Wrap', 'quantity' => '10 ROLL'],
            ],
            'action' => 'save',
        ]);

        $response->assertSessionHasErrors('pallet_number');

        // But Pallet #2 at OKI II should be allowed
        $responseOtherNumber = $this->actingAs($this->user)->post('/pallet', [
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 2,
            'components' => [
                ['component_name' => 'Roll Dressing Unit', 'quantity' => '5 UNIT'],
            ],
            'action' => 'save',
        ]);

        $responseOtherNumber->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('pallet_stickers', [
            'site' => 'OKI II',
            'pallet_number' => 2,
            'pallet_code' => 'PLT-OKI2-DRS-002',
        ]);
    }

    public function test_auto_sequencing_suggests_next_pallet_number(): void
    {
        // Pallet 1 is used at OKI II
        PalletSticker::create([
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 1,
            'pallet_code' => 'PLT-OKI2-DRS-001',
            'material_name' => 'Existing Pallet 1',
            'user_id' => 'operator01',
        ]);

        $nextNumber = PalletSticker::getNextAvailablePalletNumber('OKI II');
        $this->assertEquals(2, $nextNumber);

        // Accessing create page for OKI II should suggest Pallet 2
        $response = $this->actingAs($this->user)->get('/pallet/create?site=OKI II');
        $response->assertStatus(200);
        $response->assertViewHas('selectedPallet', 2);
    }

    public function test_component_search_dashboard_renders_successfully(): void
    {
        $response = $this->actingAs($this->user)->get('/components');

        $response->assertStatus(200);
        $response->assertSee('Cari Komponen di Pallet');
        $response->assertSee('Component Locator');
        $response->assertSee('OKI II');
    }

    public function test_search_knife_run_at_site_ikpp_shows_correct_pallets(): void
    {
        // Pallet 1 at OKI II has Knife Run
        $okiPallet1 = PalletSticker::create([
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 1,
            'pallet_code' => 'PLT-OKI2-DRS-001',
            'material_name' => 'Knife Run',
            'user_id' => 'operator01',
        ]);
        PalletComponent::create([
            'pallet_sticker_id' => $okiPallet1->id,
            'component_name' => 'Knife Run',
            'quantity' => '12 UNIT',
        ]);

        // Pallet 2 at OKI II has Knife Run and Guide Plate
        $okiPallet2 = PalletSticker::create([
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 2,
            'pallet_code' => 'PLT-OKI2-DRS-002',
            'material_name' => 'Knife Run, Guide Plate',
            'user_id' => 'operator01',
        ]);
        PalletComponent::create([
            'pallet_sticker_id' => $okiPallet2->id,
            'component_name' => 'Knife Run',
            'quantity' => '8 UNIT',
        ]);
        PalletComponent::create([
            'pallet_sticker_id' => $okiPallet2->id,
            'component_name' => 'Guide Plate',
            'quantity' => '4 PCS',
        ]);

        // Pallet 3 at OKI II does NOT have Knife Run
        $okiPallet3 = PalletSticker::create([
            'site' => 'OKI II',
            'category' => 'Consumable',
            'pallet_number' => 3,
            'pallet_code' => 'PLT-OKI2-CON-003',
            'material_name' => 'Stretch Film',
            'user_id' => 'operator01',
        ]);
        PalletComponent::create([
            'pallet_sticker_id' => $okiPallet3->id,
            'component_name' => 'Stretch Film',
            'quantity' => '50 ROLL',
        ]);

        // Search Knife Run
        $response = $this->actingAs($this->user)->get('/components?search=Knife+Run');

        $response->assertStatus(200);
        // Must show OKI II Pallets with Knife Run
        $response->assertSee('Pallet #1');
        $response->assertSee('Pallet #2');
        // Must NOT show non-matching Pallet 3
        $response->assertDontSee('Pallet #3');
    }

    public function test_user_can_edit_pallet_components(): void
    {
        $sticker = PalletSticker::create([
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 10,
            'pallet_code' => 'PLT-OKI2-DRS-010',
            'material_name' => 'Old Tool',
            'user_id' => 'operator01',
        ]);
        PalletComponent::create([
            'pallet_sticker_id' => $sticker->id,
            'component_name' => 'Old Tool',
            'quantity' => '1',
        ]);

        $response = $this->actingAs($this->user)->put("/pallet/{$sticker->id}", [
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 10,
            'components' => [
                [
                    'component_name' => 'Knife Run Updated',
                    'quantity' => '15 UNIT',
                    'batch_no' => 'BATCH-KR-NEW',
                ],
                [
                    'component_name' => 'Grinding Stone',
                    'quantity' => '5 PCS',
                    'batch_no' => 'BATCH-GS-NEW',
                ],
            ],
            'action' => 'save',
        ]);

        $response->assertRedirect("/pallet/{$sticker->id}");

        $this->assertEquals(2, $sticker->components()->count());
        $this->assertDatabaseHas('pallet_components', [
            'pallet_sticker_id' => $sticker->id,
            'component_name' => 'Knife Run Updated',
            'quantity' => '15 UNIT',
        ]);
        $this->assertDatabaseMissing('pallet_components', [
            'pallet_sticker_id' => $sticker->id,
            'component_name' => 'Old Tool',
        ]);
    }

    public function test_create_view_renders_clean_real_material_input_section_without_dummy_data(): void
    {
        $response = $this->actingAs($this->user)->get('/pallet/create');

        $response->assertStatus(200);
        $response->assertSee('DAFTAR MATERIAL / SPARE PART PALLET (INPUT DATA ASLI)');
        $response->assertSee('Tambah Material / Spare Part Asli');
        $response->assertSee('Belum ada material / spare part yang diinput');
        $response->assertDontSee('INV-T0001-ISC');
        $response->assertDontSee('SELECT SPARE PARTS (FROM SOURCE SITE)');
    }

    public function test_can_create_pallet_with_real_spare_parts_and_multiple_items(): void
    {
        $response = $this->actingAs($this->user)->post('/pallet', [
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 88,
            'components' => [
                [
                    'component_name' => '300944956 - TURNKNIFE TK IV 330mm HHQ',
                    'quantity' => '12 UNIT',
                    'batch_no' => 'BATCH-893',
                    'notes' => 'TK IV 330mm HHQ',
                ],
                [
                    'component_name' => '301843716 - DISC PLATE',
                    'quantity' => '2 PCS',
                    'batch_no' => 'BATCH-001',
                    'notes' => 'DISC PLATE',
                ],
            ],
            'action' => 'save',
        ]);

        $response->assertRedirect();

        $sticker = PalletSticker::where('site', 'OKI II')
            ->where('pallet_number', 88)
            ->first();

        $this->assertNotNull($sticker);
        $this->assertDatabaseHas('pallet_components', [
            'pallet_sticker_id' => $sticker->id,
            'component_name' => '300944956 - TURNKNIFE TK IV 330mm HHQ',
            'quantity' => '12 UNIT',
        ]);
        $this->assertDatabaseHas('pallet_components', [
            'pallet_sticker_id' => $sticker->id,
            'component_name' => '301843716 - DISC PLATE',
            'quantity' => '2 PCS',
        ]);
    }

    public function test_edit_view_renders_real_material_input_section_and_preloads_components(): void
    {
        $sticker = PalletSticker::create([
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 89,
            'pallet_code' => 'PLT-OKI2-DRS-089',
            'material_name' => '300944956 - TURNKNIFE TK IV 330mm HHQ',
            'user_id' => 'operator01',
        ]);
        PalletComponent::create([
            'pallet_sticker_id' => $sticker->id,
            'component_name' => '300944956 - TURNKNIFE TK IV 330mm HHQ',
            'quantity' => '5 UNIT',
            'batch_no' => 'BATCH-893',
            'notes' => 'TK IV 330mm HHQ',
        ]);

        $response = $this->actingAs($this->user)->get("/pallet/{$sticker->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('DAFTAR MATERIAL / SPARE PART PALLET (INPUT DATA ASLI)');
        $response->assertSee('300944956 - TURNKNIFE TK IV 330mm HHQ');
        $response->assertDontSee('INV-T0001-ISC');
        $response->assertDontSee('SELECT SPARE PARTS (FROM SOURCE SITE)');
    }

    public function test_can_create_and_update_pallet_with_kolom_and_tingkat(): void
    {
        $response = $this->actingAs($this->user)->post('/pallet', [
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 12,
            'kolom' => 'B',
            'tingkat' => '3',
            'components' => [
                [
                    'component_name' => 'TURNKNIFE TK IV',
                    'quantity' => '5',
                    'batch_no' => 'BATCH-001',
                    'kolom' => 'B',
                    'tingkat' => '3',
                ],
                [
                    'component_name' => 'ROLL DRESSING',
                    'quantity' => '2',
                    'batch_no' => 'BATCH-002',
                    'kolom' => 'C',
                    'tingkat' => '4',
                ],
            ],
            'action' => 'save',
        ]);

        $response->assertRedirect();

        $sticker = PalletSticker::where('site', 'OKI II')
            ->where('pallet_number', 12)
            ->first();

        $this->assertNotNull($sticker);
        $this->assertEquals('B', $sticker->kolom);
        $this->assertEquals('3', $sticker->tingkat);

        $this->assertDatabaseHas('pallet_components', [
            'pallet_sticker_id' => $sticker->id,
            'component_name' => 'TURNKNIFE TK IV',
            'kolom' => 'B',
            'tingkat' => '3',
        ]);

        $this->assertDatabaseHas('pallet_components', [
            'pallet_sticker_id' => $sticker->id,
            'component_name' => 'ROLL DRESSING',
            'kolom' => 'C',
            'tingkat' => '4',
        ]);

        // Test updating
        $updateResponse = $this->actingAs($this->user)->put("/pallet/{$sticker->id}", [
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 12,
            'kolom' => 'Z',
            'tingkat' => '5',
            'components' => [
                [
                    'component_name' => 'TURNKNIFE TK IV UPDATED',
                    'quantity' => '10',
                    'batch_no' => 'BATCH-001-UPD',
                    'kolom' => 'Z',
                    'tingkat' => '5',
                ],
            ],
            'action' => 'save',
        ]);

        $updateResponse->assertRedirect("/pallet/{$sticker->id}");

        $sticker->refresh();
        $this->assertEquals('Z', $sticker->kolom);
        $this->assertEquals('5', $sticker->tingkat);

        $this->assertDatabaseHas('pallet_components', [
            'pallet_sticker_id' => $sticker->id,
            'component_name' => 'TURNKNIFE TK IV UPDATED',
            'kolom' => 'Z',
            'tingkat' => '5',
        ]);

        // Test show page displays rack location
        $showResponse = $this->actingAs($this->user)->get("/pallet/{$sticker->id}");
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Kolom Z');
        $showResponse->assertSee('Tingkat 5');
    }

    public function test_create_view_renders_kolom_and_tingkat_dropdowns(): void
    {
        $response = $this->actingAs($this->user)->get('/pallet/create');
        $response->assertStatus(200);
        $response->assertSee('newPartKolom', false);
        $response->assertSee('newPartTingkat', false);
        $response->assertSee('<option value="A">A</option>', false);
        $response->assertSee('<option value="Z">Z</option>', false);
        $response->assertSee('<option value="1">1</option>', false);
        $response->assertSee('<option value="5">5</option>', false);
    }

    public function test_create_and_edit_views_only_show_oki_ii_site(): void
    {
        $response = $this->actingAs($this->user)->get('/pallet/create');
        $response->assertStatus(200);
        $response->assertSee('OKI II');
        $response->assertDontSee('5 Fasilitas Produksi');
        $response->assertDontSee('IKPD');
        $response->assertDontSee('IKPP');
        $response->assertDontSee('TELL');
        $response->assertDontSee('ISC');

        $sticker = PalletSticker::create([
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 20,
            'pallet_code' => 'PLT-OKI2-DRS-020',
            'material_name' => 'Sample Dressing',
            'user_id' => $this->user->id,
        ]);

        $editResponse = $this->actingAs($this->user)->get("/pallet/{$sticker->id}/edit");
        $editResponse->assertStatus(200);
        $editResponse->assertSee('OKI II');
        $editResponse->assertDontSee('5 Fasilitas Produksi');
        $editResponse->assertDontSee('IKPD');
        $editResponse->assertDontSee('IKPP');
        $editResponse->assertDontSee('TELL');
        $editResponse->assertDontSee('ISC');
    }
}
