<?php

namespace Database\Seeders;

use App\Models\PalletSticker;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default users for authentication
        User::updateOrCreate(
            ['user_id' => 'admin_andritz'],
            [
                'name' => 'Admin ANDRITZ',
                'email' => 'admin@andritz.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['user_id' => 'admin'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@pallet-system.local',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['user_id' => 'operator01'],
            [
                'name' => 'Budi Santoso (Shift 1)',
                'email' => 'budi@pallet-system.local',
                'password' => Hash::make('password123'),
                'role' => 'operator',
            ]
        );

        User::updateOrCreate(
            ['user_id' => 'operator02'],
            [
                'name' => 'Agus Pratama (Shift 2)',
                'email' => 'agus@pallet-system.local',
                'password' => Hash::make('password123'),
                'role' => 'operator',
            ]
        );

        // Sample initial pallet sticker records across all sites with multi-components
        $samples = [
            [
                'site' => 'OKI II',
                'category' => 'Dressing',
                'pallet_number' => 1,
                'pallet_code' => 'PLT-OKI2-DRS-001',
                'material_name' => 'Roll Dressing Unit 450mm, Knife Run',
                'batch_no' => 'BATCH-20260904-001',
                'quantity' => '2 Komponen',
                'notes' => 'Pallet material Dressing OKI Mill II',
                'user_id' => 'operator01',
                'printed_at' => now()->subHours(5),
                'components' => [
                    ['component_name' => 'Roll Dressing Unit 450mm', 'quantity' => '24 PCS', 'batch_no' => 'BATCH-20260904-001A', 'notes' => 'Roller primary'],
                    ['component_name' => 'Knife Run', 'quantity' => '4 PCS', 'batch_no' => 'BATCH-20260904-001B', 'notes' => 'Spare knife unit'],
                ],
            ],
            [
                'site' => 'OKI II',
                'category' => 'Consumable',
                'pallet_number' => 15,
                'pallet_code' => 'PLT-OKI2-CON-015',
                'material_name' => 'Stretch Film Roll 500mm x 300m',
                'batch_no' => 'BATCH-20260904-015',
                'quantity' => '60 ROLL',
                'notes' => 'Consumable packing OKI II',
                'user_id' => 'operator01',
                'printed_at' => now()->subHours(4),
                'components' => [
                    ['component_name' => 'Stretch Film Roll 500mm x 300m', 'quantity' => '60 ROLL', 'batch_no' => 'BATCH-20260904-015', 'notes' => 'Pallet wrap stretch film'],
                ],
            ],
            [
                'site' => 'IKPD',
                'category' => 'Dressing',
                'pallet_number' => 8,
                'pallet_code' => 'PLT-IKPD-DRS-008',
                'material_name' => 'Diamond Dressing Tool #40',
                'batch_no' => 'BATCH-20260904-008',
                'quantity' => '10 SET',
                'notes' => 'Grinding tools IKPD Perawang',
                'user_id' => 'operator02',
                'printed_at' => now()->subHours(3),
                'components' => [
                    ['component_name' => 'Diamond Dressing Tool #40', 'quantity' => '10 SET', 'batch_no' => 'BATCH-20260904-008', 'notes' => 'High precision dresser'],
                ],
            ],
            [
                'site' => 'IKPD',
                'category' => 'Consumable',
                'pallet_number' => 2,
                'pallet_code' => 'PLT-IKPD-CON-002',
                'material_name' => 'Strapping Band Heavy Duty 15mm',
                'batch_no' => 'BATCH-20260904-002',
                'quantity' => '40 ROLL',
                'notes' => 'Packing finishing area IKPD',
                'user_id' => 'operator01',
                'printed_at' => now()->subHours(3),
                'components' => [
                    ['component_name' => 'Strapping Band Heavy Duty 15mm', 'quantity' => '40 ROLL', 'batch_no' => 'BATCH-20260904-002', 'notes' => 'PET Strap band'],
                ],
            ],
            [
                'site' => 'IKPP',
                'category' => 'Dressing',
                'pallet_number' => 2,
                'pallet_code' => 'PLT-IKPP-DRS-002',
                'material_name' => 'Knife Run, Guide Plate IKPP',
                'batch_no' => 'BATCH-20260904-002',
                'quantity' => '2 Komponen',
                'notes' => 'Pallet pisau slitter IKPP Serang Mill',
                'user_id' => 'operator01',
                'printed_at' => now()->subHours(2),
                'components' => [
                    ['component_name' => 'Knife Run', 'quantity' => '12 UNIT', 'batch_no' => 'BATCH-20260904-002-KR', 'notes' => 'Slitter upper knife run'],
                    ['component_name' => 'Guide Plate IKPP', 'quantity' => '6 PCS', 'batch_no' => 'BATCH-20260904-002-GP', 'notes' => 'Plate spacer'],
                ],
            ],
            [
                'site' => 'IKPP',
                'category' => 'Dressing',
                'pallet_number' => 12,
                'pallet_code' => 'PLT-IKPP-DRS-012',
                'material_name' => 'Dressing Blade Heavy Duty 300mm, Knife Run',
                'batch_no' => 'BATCH-20260904-012',
                'quantity' => '2 Komponen',
                'notes' => 'Cutting section Serang IKPP',
                'user_id' => 'operator02',
                'printed_at' => now()->subHours(2),
                'components' => [
                    ['component_name' => 'Dressing Blade Heavy Duty 300mm', 'quantity' => '30 PCS', 'batch_no' => 'BATCH-20260904-012', 'notes' => 'Standard knife dressing'],
                    ['component_name' => 'Knife Run', 'quantity' => '8 UNIT', 'batch_no' => 'BATCH-20260904-012-KR', 'notes' => 'Extra knife run set'],
                ],
            ],
            [
                'site' => 'IKPP',
                'category' => 'Consumable',
                'pallet_number' => 25,
                'pallet_code' => 'PLT-IKPP-CON-025',
                'material_name' => 'Corrugated Corner Protector',
                'batch_no' => 'BATCH-20260904-025',
                'quantity' => '100 PCS',
                'notes' => 'Pallet protective edge Serang',
                'user_id' => 'admin',
                'printed_at' => now()->subHours(2),
                'components' => [
                    ['component_name' => 'Corrugated Corner Protector', 'quantity' => '100 PCS', 'batch_no' => 'BATCH-20260904-025', 'notes' => 'Protector edge'],
                ],
            ],
            [
                'site' => 'TELL',
                'category' => 'Dressing',
                'pallet_number' => 5,
                'pallet_code' => 'PLT-TELL-DRS-005',
                'material_name' => 'Grinding Wheel Dressing Stone',
                'batch_no' => 'BATCH-20260904-005',
                'quantity' => '18 PCS',
                'notes' => 'Line Tjiwi Kimia TELL',
                'user_id' => 'operator01',
                'printed_at' => now()->subHour(),
                'components' => [
                    ['component_name' => 'Grinding Wheel Dressing Stone', 'quantity' => '18 PCS', 'batch_no' => 'BATCH-20260904-005', 'notes' => 'Abrasive dressing'],
                ],
            ],
            [
                'site' => 'TELL',
                'category' => 'Consumable',
                'pallet_number' => 30,
                'pallet_code' => 'PLT-TELL-CON-030',
                'material_name' => 'Thermal Transfer Ribbon 110mm x 300m',
                'batch_no' => 'BATCH-20260904-030',
                'quantity' => '50 ROLL',
                'notes' => 'Sticker printer consumable TELL',
                'user_id' => 'operator02',
                'printed_at' => now()->subHour(),
                'components' => [
                    ['component_name' => 'Thermal Transfer Ribbon 110mm x 300m', 'quantity' => '50 ROLL', 'batch_no' => 'BATCH-20260904-030', 'notes' => 'Wax resin ribbon'],
                ],
            ],
            [
                'site' => 'ISC',
                'category' => 'Dressing',
                'pallet_number' => 3,
                'pallet_code' => 'PLT-ISC-DRS-003',
                'material_name' => 'Superabrasive Dressing Roller',
                'batch_no' => 'BATCH-20260904-003',
                'quantity' => '8 SET',
                'notes' => 'Warehouse ISC Central',
                'user_id' => 'admin',
                'printed_at' => now()->subMinutes(30),
                'components' => [
                    ['component_name' => 'Superabrasive Dressing Roller', 'quantity' => '8 SET', 'batch_no' => 'BATCH-20260904-003', 'notes' => 'Precision roller'],
                ],
            ],
            [
                'site' => 'ISC',
                'category' => 'Consumable',
                'pallet_number' => 50,
                'pallet_code' => 'PLT-ISC-CON-050',
                'material_name' => 'Pallet Cover Plastic Bag Heavy Duty',
                'batch_no' => 'BATCH-20260904-050',
                'quantity' => '120 PCS',
                'notes' => 'Consumable export packaging ISC',
                'user_id' => 'operator01',
                'printed_at' => now()->subMinutes(10),
                'components' => [
                    ['component_name' => 'Pallet Cover Plastic Bag Heavy Duty', 'quantity' => '120 PCS', 'batch_no' => 'BATCH-20260904-050', 'notes' => 'Plastic hood'],
                ],
            ],
        ];

        foreach ($samples as $sample) {
            $components = $sample['components'] ?? [];
            unset($sample['components']);

            $pallet = PalletSticker::updateOrCreate(
                [
                    'site' => $sample['site'],
                    'category' => $sample['category'],
                    'pallet_number' => $sample['pallet_number'],
                ],
                $sample
            );

            // Re-sync components for this pallet
            $pallet->components()->delete();
            foreach ($components as $comp) {
                $pallet->components()->create($comp);
            }
        }
    }
}
