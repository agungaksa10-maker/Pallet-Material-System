<?php

namespace Tests\Feature;

use App\Models\MasterMaterial;
use App\Models\PalletSticker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MasterMaterialFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'user_id' => 'operator_mm',
            'name' => 'Master Material Tester',
            'email' => 'mmtester@pallet.local',
            'password' => Hash::make('secret123'),
            'role' => 'operator',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_master_materials(): void
    {
        $response = $this->get('/master-materials');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_master_materials_index(): void
    {
        MasterMaterial::create([
            'item_code' => 'TEST-001',
            'name' => 'TURNKNIFE TK IV 330mm',
            'default_unit' => 'PCS',
            'specification' => 'High quality blade',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->get('/master-materials');

        $response->assertStatus(200);
        $response->assertSee('TURNKNIFE TK IV 330mm');
        $response->assertSee('TEST-001');
    }

    public function test_can_filter_and_search_master_materials(): void
    {
        MasterMaterial::create([
            'item_code' => 'SL-01',
            'name' => 'Slitter Bottom Knife',
            'default_unit' => 'PCS',
            'is_active' => true,
        ]);

        MasterMaterial::create([
            'item_code' => 'DR-01',
            'name' => 'Roll Dressing Felt',
            'default_unit' => 'ROLL',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->get('/master-materials?search=Bottom');

        $response->assertStatus(200);
        $response->assertSee('Slitter Bottom Knife');
        $response->assertDontSee('Roll Dressing Felt');
    }

    public function test_can_create_new_master_material(): void
    {
        $response = $this->actingAs($this->user)->post('/master-materials', [
            'item_code' => 'PART-999',
            'name' => 'Ceramic Bushing Guide',
            'default_unit' => 'SET',
            'specification' => 'Diameter 40mm heat resistant',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/master-materials');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('master_materials', [
            'item_code' => 'PART-999',
            'name' => 'Ceramic Bushing Guide',
            'default_unit' => 'SET',
            'is_active' => true,
        ]);
    }

    public function test_can_update_master_material(): void
    {
        $material = MasterMaterial::create([
            'item_code' => 'OLD-CODE',
            'name' => 'Old Material Name',
            'default_unit' => 'UNIT',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->put("/master-materials/{$material->id}", [
            'item_code' => 'NEW-CODE',
            'name' => 'Updated Material Name',
            'default_unit' => 'BOX',
            'specification' => 'Updated spec',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/master-materials');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('master_materials', [
            'id' => $material->id,
            'item_code' => 'NEW-CODE',
            'name' => 'Updated Material Name',
            'default_unit' => 'BOX',
        ]);
    }

    public function test_can_delete_master_material(): void
    {
        $material = MasterMaterial::create([
            'item_code' => 'DEL-01',
            'name' => 'Material To Delete',
            'category' => 'General',
            'default_unit' => 'UNIT',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->delete("/master-materials/{$material->id}");

        $response->assertRedirect('/master-materials');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('master_materials', [
            'id' => $material->id,
        ]);
    }

    public function test_search_api_returns_json_results(): void
    {
        MasterMaterial::create([
            'item_code' => 'API-01',
            'name' => 'Tungsten Carbide Slitter Blade',
            'category' => 'Slitter',
            'default_unit' => 'PCS',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->getJson('/api/master-materials/search?q=Tungsten');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'item_code' => 'API-01',
            'name' => 'Tungsten Carbide Slitter Blade',
        ]);
    }

    public function test_pallet_create_and_edit_receive_master_materials(): void
    {
        MasterMaterial::create([
            'item_code' => 'PLT-ITEM-1',
            'name' => 'Guide Plate Wear Bar',
            'category' => 'General',
            'default_unit' => 'PCS',
            'is_active' => true,
        ]);

        $sticker = PalletSticker::create([
            'site' => 'IKPP',
            'category' => 'Dressing',
            'pallet_number' => 1,
            'material_name' => 'Existing Pallet Component',
            'quantity' => '5 PCS',
            'user_id' => $this->user->id,
        ]);

        $createResponse = $this->actingAs($this->user)->get('/pallet/create');
        $createResponse->assertStatus(200);
        $createResponse->assertViewHas('masterMaterials');

        $editResponse = $this->actingAs($this->user)->get("/pallet/{$sticker->id}/edit");
        $editResponse->assertStatus(200);
        $editResponse->assertViewHas('masterMaterials');
    }
}
