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
            'site' => 'IKPP',
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

        $sticker = PalletSticker::where('site', 'IKPP')
            ->where('pallet_number', 1)
            ->first();

        $this->assertNotNull($sticker);
        $this->assertEquals('PLT-IKPP-DRS-001', $sticker->pallet_code);
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
        // First pallet creation at IKPP (Pallet #1)
        PalletSticker::create([
            'site' => 'IKPP',
            'category' => 'Dressing',
            'pallet_number' => 1,
            'pallet_code' => 'PLT-IKPP-DRS-001',
            'material_name' => 'Knife Run',
            'user_id' => 'operator01',
        ]);

        // Attempt to create Pallet #1 again at IKPP must fail
        $response = $this->actingAs($this->user)->post('/pallet', [
            'site' => 'IKPP',
            'category' => 'Consumable',
            'pallet_number' => 1,
            'components' => [
                ['component_name' => 'Plastic Wrap', 'quantity' => '10 ROLL'],
            ],
            'action' => 'save',
        ]);

        $response->assertSessionHasErrors('pallet_number');

        // But Pallet #1 at OKI II should be allowed (unique per site)
        $responseOtherSite = $this->actingAs($this->user)->post('/pallet', [
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 1,
            'components' => [
                ['component_name' => 'Roll Dressing Unit', 'quantity' => '5 UNIT'],
            ],
            'action' => 'save',
        ]);

        $responseOtherSite->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('pallet_stickers', [
            'site' => 'OKI II',
            'pallet_number' => 1,
            'pallet_code' => 'PLT-OKI2-DRS-001',
        ]);
    }

    public function test_auto_sequencing_suggests_next_pallet_number(): void
    {
        // Pallet 1 is used at IKPP
        PalletSticker::create([
            'site' => 'IKPP',
            'category' => 'Dressing',
            'pallet_number' => 1,
            'pallet_code' => 'PLT-IKPP-DRS-001',
            'material_name' => 'Existing Pallet 1',
            'user_id' => 'operator01',
        ]);

        $nextNumber = PalletSticker::getNextAvailablePalletNumber('IKPP');
        $this->assertEquals(2, $nextNumber);

        // Accessing create page for IKPP should suggest Pallet 2
        $response = $this->actingAs($this->user)->get('/pallet/create?site=IKPP');
        $response->assertStatus(200);
        $response->assertViewHas('selectedPallet', 2);
    }

    public function test_component_search_dashboard_renders_successfully(): void
    {
        $response = $this->actingAs($this->user)->get('/components');

        $response->assertStatus(200);
        $response->assertSee('Cari Komponen di Pallet');
        $response->assertSee('Component Locator');
        $response->assertSee('Semua Site');
        $response->assertSee('IKPP');
        $response->assertSee('OKI II');
    }

    public function test_search_knife_run_at_site_ikpp_shows_correct_pallets(): void
    {
        // Pallet 1 at IKPP has Knife Run
        $ikppPallet1 = PalletSticker::create([
            'site' => 'IKPP',
            'category' => 'Dressing',
            'pallet_number' => 1,
            'pallet_code' => 'PLT-IKPP-DRS-001',
            'material_name' => 'Knife Run',
            'user_id' => 'operator01',
        ]);
        PalletComponent::create([
            'pallet_sticker_id' => $ikppPallet1->id,
            'component_name' => 'Knife Run',
            'quantity' => '12 UNIT',
        ]);

        // Pallet 2 at IKPP has Knife Run and Guide Plate
        $ikppPallet2 = PalletSticker::create([
            'site' => 'IKPP',
            'category' => 'Dressing',
            'pallet_number' => 2,
            'pallet_code' => 'PLT-IKPP-DRS-002',
            'material_name' => 'Knife Run, Guide Plate',
            'user_id' => 'operator01',
        ]);
        PalletComponent::create([
            'pallet_sticker_id' => $ikppPallet2->id,
            'component_name' => 'Knife Run',
            'quantity' => '8 UNIT',
        ]);
        PalletComponent::create([
            'pallet_sticker_id' => $ikppPallet2->id,
            'component_name' => 'Guide Plate',
            'quantity' => '4 PCS',
        ]);

        // Pallet 3 at IKPP does NOT have Knife Run
        $ikppPallet3 = PalletSticker::create([
            'site' => 'IKPP',
            'category' => 'Consumable',
            'pallet_number' => 3,
            'pallet_code' => 'PLT-IKPP-CON-003',
            'material_name' => 'Stretch Film',
            'user_id' => 'operator01',
        ]);
        PalletComponent::create([
            'pallet_sticker_id' => $ikppPallet3->id,
            'component_name' => 'Stretch Film',
            'quantity' => '50 ROLL',
        ]);

        // Pallet 1 at OKI II also has Knife Run, but is at OKI II
        $okiPallet1 = PalletSticker::create([
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 1,
            'pallet_code' => 'PLT-OKI2-DRS-001',
            'material_name' => 'Knife Run OKI',
            'user_id' => 'operator01',
        ]);
        PalletComponent::create([
            'pallet_sticker_id' => $okiPallet1->id,
            'component_name' => 'Knife Run',
            'quantity' => '5 UNIT',
        ]);

        // Search Knife Run filtered by Site IKPP
        $response = $this->actingAs($this->user)->get('/components?search=Knife+Run&site=IKPP');

        $response->assertStatus(200);
        // Must show IKPP Pallets with Knife Run
        $response->assertSee('PLT-IKPP-DRS-001');
        $response->assertSee('PLT-IKPP-DRS-002');
        // Must NOT show non-matching Pallet 3
        $response->assertDontSee('PLT-IKPP-CON-003');
        // Must NOT show OKI II Pallet because site filter is IKPP
        $response->assertDontSee('PLT-OKI2-DRS-001');
    }

    public function test_user_can_edit_pallet_components(): void
    {
        $sticker = PalletSticker::create([
            'site' => 'TELL',
            'category' => 'Dressing',
            'pallet_number' => 10,
            'pallet_code' => 'PLT-TELL-DRS-010',
            'material_name' => 'Old Tool',
            'user_id' => 'operator01',
        ]);
        PalletComponent::create([
            'pallet_sticker_id' => $sticker->id,
            'component_name' => 'Old Tool',
            'quantity' => '1 UNIT',
        ]);

        $response = $this->actingAs($this->user)->put("/pallet/{$sticker->id}", [
            'site' => 'TELL',
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
}
