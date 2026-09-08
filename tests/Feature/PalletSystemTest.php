<?php

namespace Tests\Feature;

use App\Models\PalletSticker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PalletSystemTest extends TestCase
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

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Box Locator');
        $response->assertSee('User ID Terdaftar');
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'user_id' => 'operator01',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('user_id');
        $this->assertGuest();
    }

    public function test_user_can_login_with_registered_user_id(): void
    {
        $response = $this->post('/login', [
            'user_id' => 'operator01',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->user);
    }

    public function test_authenticated_user_can_view_pallet_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('OKI II');
        $response->assertSee('Dressing');
        $response->assertSee('Consumable');
        $response->assertSee('1 hingga 500');
    }

    public function test_pallet_number_must_be_between_1_and_500(): void
    {
        // Pallet number 0 is invalid
        $responseZero = $this->actingAs($this->user)->post('/pallet', [
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 0,
        ]);
        $responseZero->assertSessionHasErrors('pallet_number');

        // Pallet number 501 is invalid
        $responseHigh = $this->actingAs($this->user)->post('/pallet', [
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 501,
        ]);
        $responseHigh->assertSessionHasErrors('pallet_number');
    }

    public function test_site_must_be_one_of_the_specified_sites(): void
    {
        $response = $this->actingAs($this->user)->post('/pallet', [
            'site' => 'INVALID_SITE',
            'category' => 'Dressing',
            'pallet_number' => 10,
        ]);

        $response->assertSessionHasErrors('site');
    }

    public function test_category_must_be_dressing_or_consumable(): void
    {
        $response = $this->actingAs($this->user)->post('/pallet', [
            'site' => 'OKI II',
            'category' => 'INVALID_CAT',
            'pallet_number' => 10,
        ]);

        $response->assertSessionHasErrors('category');
    }

    public function test_user_can_create_and_save_pallet_sticker(): void
    {
        $response = $this->actingAs($this->user)->post('/pallet', [
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 42,
            'material_name' => 'Roll Dressing Unit 450mm',
            'batch_no' => 'BATCH-20260904-042',
            'quantity' => '24 PCS',
            'notes' => 'Pallet untuk line utama',
            'action' => 'save',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pallet_stickers', [
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 42,
            'pallet_code' => 'PLT-OKI2-DRS-042',
            'material_name' => 'Roll Dressing Unit 450mm',
            'user_id' => 'operator01',
        ]);
    }

    public function test_user_can_download_pdf_sticker(): void
    {
        $sticker = PalletSticker::create([
            'site' => 'OKI II',
            'category' => 'Consumable',
            'pallet_number' => 77,
            'pallet_code' => 'PLT-OKI2-CON-077',
            'material_name' => 'Stretch Film Roll',
            'batch_no' => 'BATCH-20260904-077',
            'quantity' => '50 ROLL',
            'user_id' => 'operator01',
            'printed_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->get("/pallet/pdf/{$sticker->id}?mode=stream");

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function test_user_can_stream_pdf_on_the_fly_with_custom_pallet_number(): void
    {
        $response = $this->actingAs($this->user)->get('/pallet/pdf?site=TELL&category=Dressing&pallet=250&mode=stream');

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function test_user_can_stream_batch_pdf_range(): void
    {
        $response = $this->actingAs($this->user)->get('/pallet/pdf?site=ISC&category=Consumable&start=1&end=5&mode=stream');

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($response->getContent());
    }

    public function test_user_can_view_create_pallet_form(): void
    {
        $response = $this->actingAs($this->user)->get('/pallet/create');

        $response->assertStatus(200);
        $response->assertSee('Buat Sticker Pallet Baru');
        $response->assertSee('OKI II');
        $response->assertSee('Dressing');
        $response->assertSee('Consumable');
        $response->assertSee('Nomor Pallet');
    }

    public function test_user_can_view_pallet_detail_page(): void
    {
        $sticker = PalletSticker::create([
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 10,
            'pallet_code' => 'PLT-OKI2-DRS-010',
            'material_name' => 'Roll Dressing Unit 450mm',
            'batch_no' => 'BATCH-20260904-010',
            'quantity' => '24 PCS',
            'user_id' => 'operator01',
            'printed_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->get("/pallet/{$sticker->id}");

        $response->assertStatus(200);
        $response->assertSee('PLT-OKI2-DRS-010');
        $response->assertSee('Roll Dressing Unit 450mm');
        $response->assertSee('BATCH-20260904-010');
        $response->assertSee('Cetak PDF');
        $response->assertSee('Edit Data');
    }

    public function test_user_can_view_edit_pallet_form(): void
    {
        $sticker = PalletSticker::create([
            'site' => 'OKI II',
            'category' => 'Consumable',
            'pallet_number' => 120,
            'pallet_code' => 'PLT-OKI2-CON-120',
            'material_name' => 'Thermal Transfer Ribbon',
            'batch_no' => 'BATCH-20260904-120',
            'quantity' => '30 ROLL',
            'user_id' => 'operator01',
            'printed_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->get("/pallet/{$sticker->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Edit Sticker Pallet');
        $response->assertSee('PLT-OKI2-CON-120');
        $response->assertSee('Thermal Transfer Ribbon');
        $response->assertSee('120');
    }

    public function test_user_can_update_pallet_sticker(): void
    {
        $sticker = PalletSticker::create([
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 15,
            'pallet_code' => 'PLT-OKI2-DRS-015',
            'material_name' => 'Diamond Tooling Old',
            'batch_no' => 'BATCH-OLD',
            'quantity' => '5 SET',
            'user_id' => 'operator01',
            'printed_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->put("/pallet/{$sticker->id}", [
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 16,
            'material_name' => 'Diamond Tooling Updated',
            'batch_no' => 'BATCH-NEW-016',
            'quantity' => '12 SET',
            'notes' => 'Updated notes from test',
        ]);

        $response->assertRedirect("/pallet/{$sticker->id}");
        $this->assertDatabaseHas('pallet_stickers', [
            'id' => $sticker->id,
            'site' => 'OKI II',
            'category' => 'Dressing',
            'pallet_number' => 16,
            'pallet_code' => 'PLT-OKI2-DRS-016',
            'material_name' => 'Diamond Tooling Updated',
            'quantity' => '12 SET',
            'batch_no' => 'BATCH-NEW-016',
        ]);
    }

    public function test_user_can_delete_pallet_sticker(): void
    {
        $sticker = PalletSticker::create([
            'site' => 'ISC',
            'category' => 'Consumable',
            'pallet_number' => 99,
            'pallet_code' => 'PLT-ISC-CON-099',
            'material_name' => 'To be deleted',
            'user_id' => 'operator01',
            'printed_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->delete("/pallet/{$sticker->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('pallet_stickers', [
            'id' => $sticker->id,
        ]);
    }
}
