<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkOrder;
use App\Models\Workstation;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Define permissions based on screenshot menus
        $permissions = [
            'view_dashboard',
            'manage_work_orders',
            'manage_produksi',
            'manage_qc',
            'manage_waste',
            'view_vsm',
            'view_laporan',
            'manage_users',
            'manage_settings'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Define roles and assign permissions
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        $operatorRole = Role::create(['name' => 'Operator Lini']);
        $operatorRole->givePermissionTo(['view_dashboard', 'manage_produksi', 'manage_waste']);

        $qcRole = Role::create(['name' => 'Analis QC']);
        $qcRole->givePermissionTo(['view_dashboard', 'manage_qc']);

        $spvRole = Role::create(['name' => 'Supervisor']);
        $spvRole->givePermissionTo(['view_dashboard', 'manage_work_orders', 'manage_produksi', 'manage_qc', 'manage_waste', 'view_vsm', 'view_laporan']);

        $managerRole = Role::create(['name' => 'Manajer']);
        $managerRole->givePermissionTo(['view_dashboard', 'view_vsm', 'view_laporan']);

        // Create users and assign roles
        $admin = User::factory()->create(['name' => 'Bagas (IT Admin)', 'email' => 'admin@sinaralfaomega.com']);
        $admin->assignRole($adminRole);

        $operator = User::factory()->create(['name' => 'Andi (Operator)', 'email' => 'operator.lini@sinaralfaomega.com']);
        $operator->assignRole($operatorRole);

        $qc = User::factory()->create(['name' => 'Siti (Analis Lab)', 'email' => 'analis.qc@sinaralfaomega.com']);
        $qc->assignRole($qcRole);

        $spv = User::factory()->create(['name' => 'Budi Santoso', 'email' => 'budi.spv@sinaralfaomega.com']);
        $spv->assignRole($spvRole);

        $manager = User::factory()->create(['name' => 'Handoko (Manajer)', 'email' => 'handoko.manajer@sinaralfaomega.com']);
        $manager->assignRole($managerRole);

        // Workstations
        $stations = ['Mixing', 'Filling', 'Packing'];
        foreach ($stations as $station) {
            Workstation::create(['name' => $station, 'type' => 'Production']);
        }

        // Work Orders based on screenshot
        WorkOrder::create(['order_number' => 'WO-0325', 'product_name' => 'Brightening Cream 50ml (Filling)', 'quantity' => 1000, 'unit' => 'pcs', 'status' => 'In Progress', 'created_by' => $spv->id, 'created_at' => '2025-05-26 08:12:00']);
        WorkOrder::create(['order_number' => 'WO-0324', 'product_name' => 'Facial Wash 100ml (Mixing)', 'quantity' => 200, 'unit' => 'kg', 'status' => 'In Progress', 'created_by' => $spv->id, 'created_at' => '2025-05-26 07:45:00']);
        WorkOrder::create(['order_number' => 'WO-0323', 'product_name' => 'Toner 100ml (Packing)', 'quantity' => 1500, 'unit' => 'pcs', 'status' => 'Completed', 'created_by' => $spv->id, 'created_at' => '2025-05-25 14:20:00']);
        WorkOrder::create(['order_number' => 'WO-0322', 'product_name' => 'Day Cream 20g (Mixing)', 'quantity' => 50, 'unit' => 'kg', 'status' => 'Completed', 'created_by' => $spv->id, 'created_at' => '2025-05-25 10:15:00']);
        WorkOrder::create(['order_number' => 'WO-0321', 'product_name' => 'Serum 20ml (Filling)', 'quantity' => 1200, 'unit' => 'pcs', 'status' => 'Completed', 'created_by' => $spv->id, 'created_at' => '2025-05-24 09:30:00']);
    }
}
