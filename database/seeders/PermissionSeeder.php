<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['id' => Str::uuid(), 'label' => 'View Dashboard', 'name' => 'view-dashboard'],

            ['id' => Str::uuid(), 'label' => 'Buat User', 'name' => 'create-user'],
            ['id' => Str::uuid(), 'label' => 'Edit User', 'name' => 'update-user'],
            ['id' => Str::uuid(), 'label' => 'Lihat User', 'name' => 'view-user'],
            ['id' => Str::uuid(), 'label' => 'Hapus User', 'name' => 'delete-user'],

            ['id' => Str::uuid(), 'label' => 'Buat Role', 'name' => 'create-role'],
            ['id' => Str::uuid(), 'label' => 'Edit Role', 'name' => 'update-role'],
            ['id' => Str::uuid(), 'label' => 'Lihat Role', 'name' => 'view-role'],
            ['id' => Str::uuid(), 'label' => 'Hapus Role', 'name' => 'delete-role'],

            ['id' => Str::uuid(), 'label' => 'Buat Ukuran', 'name' => 'create-size'],
            ['id' => Str::uuid(), 'label' => 'Edit Ukuran', 'name' => 'update-size'],
            ['id' => Str::uuid(), 'label' => 'Lihat Ukuran', 'name' => 'view-size'],
            ['id' => Str::uuid(), 'label' => 'Hapus Ukuran', 'name' => 'delete-size'],

            ['id' => Str::uuid(), 'label' => 'Buat Warna', 'name' => 'create-color'],
            ['id' => Str::uuid(), 'label' => 'Edit Warna', 'name' => 'update-color'],
            ['id' => Str::uuid(), 'label' => 'Lihat Warna', 'name' => 'view-color'],
            ['id' => Str::uuid(), 'label' => 'Hapus Warna', 'name' => 'delete-color'],

            ['id' => Str::uuid(), 'label' => 'Buat Brand', 'name' => 'create-brand'],
            ['id' => Str::uuid(), 'label' => 'Edit Brand', 'name' => 'update-brand'],
            ['id' => Str::uuid(), 'label' => 'Lihat Brand', 'name' => 'view-brand'],
            ['id' => Str::uuid(), 'label' => 'Hapus Brand', 'name' => 'delete-brand'],

            ['id' => Str::uuid(), 'label' => 'Buat Bahan', 'name' => 'create-material'],
            ['id' => Str::uuid(), 'label' => 'Edit Bahan', 'name' => 'update-material'],
            ['id' => Str::uuid(), 'label' => 'Lihat Bahan', 'name' => 'view-material'],
            ['id' => Str::uuid(), 'label' => 'Hapus Bahan', 'name' => 'delete-material'],

            ['id' => Str::uuid(), 'label' => 'Buat Pembeli', 'name' => 'create-buyer'],
            ['id' => Str::uuid(), 'label' => 'Edit Pembeli', 'name' => 'update-buyer'],
            ['id' => Str::uuid(), 'label' => 'Lihat Pembeli', 'name' => 'view-buyer'],
            ['id' => Str::uuid(), 'label' => 'Hapus Pembeli', 'name' => 'delete-buyer'],

            ['id' => Str::uuid(), 'label' => 'Buat Artikel', 'name' => 'create-production'],
            ['id' => Str::uuid(), 'label' => 'Edit Artikel', 'name' => 'update-production'],
            ['id' => Str::uuid(), 'label' => 'Lihat Artikel', 'name' => 'view-production'],
            ['id' => Str::uuid(), 'label' => 'Hapus Artikel', 'name' => 'delete-production'],

            ['id' => Str::uuid(), 'label' => 'Buat Line Sewing', 'name' => 'create-production-result'],

            // setting Fabric
            ['id' => Str::uuid(), 'label' => 'Buat Kain', 'name' => 'create-fabric'],
            ['id' => Str::uuid(), 'label' => 'Edit Kain', 'name' => 'update-fabric'],
            ['id' => Str::uuid(), 'label' => 'Lihat Kain', 'name' => 'view-fabric'],
            ['id' => Str::uuid(), 'label' => 'Hapus Kain', 'name' => 'delete-fabric'],
            // setting Cutting
            ['id' => Str::uuid(), 'label' => 'Buat Cutting', 'name' => 'create-cutting'],
            ['id' => Str::uuid(), 'label' => 'Edit Cutting', 'name' => 'update-cutting'],
            ['id' => Str::uuid(), 'label' => 'Lihat Cutting', 'name' => 'view-cutting'],
            ['id' => Str::uuid(), 'label' => 'Hapus Cutting', 'name' => 'delete-cutting'],
            // setting User Cutting
            ['id' => Str::uuid(), 'label' => 'Buat User Cutting', 'name' => 'create-user-cutting'],
            // setting suplier
            ['id' => Str::uuid(), 'label' => 'Buat Supplier', 'name' => 'create-supplier'],
            ['id' => Str::uuid(), 'label' => 'Edit Supplier', 'name' => 'update-supplier'],
            ['id' => Str::uuid(), 'label' => 'Lihat Supplier', 'name' => 'view-supplier'],
            ['id' => Str::uuid(), 'label' => 'Hapus Supplier', 'name' => 'delete-supplier'],
            // setting rasio
            ['id' => Str::uuid(), 'label' => 'Buat Rasio', 'name' => 'create-ratio'],
            ['id' => Str::uuid(), 'label' => 'Edit Rasio', 'name' => 'update-ratio'],
            ['id' => Str::uuid(), 'label' => 'Lihat Rasio', 'name' => 'view-ratio'],
            ['id' => Str::uuid(), 'label' => 'Hapus Rasio', 'name' => 'delete-ratio'],
            // setting
            ['id' => Str::uuid(), 'label' => 'Setting', 'name' => 'create-setting'],
            // tv
            ['id' => Str::uuid(), 'label' => 'TV', 'name' => 'view-tv'],
            // Finishing
            ['id' => Str::uuid(), 'label' => 'Buat Finishing', 'name' => 'create-finishing'],
        ];

        foreach ($permissions as $permission) {
            Permission::insert($permission);
        }

        $role = Role::create(['name' => 'admin']);

        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $role->rolePermissions()->create(['permission_id' => $permission->id]);
        }

        User::create([
            'name' => 'Super Administrator',
            'email' => 'root@admin.com',
            'password' => bcrypt('password'),
        ]);

        $admin = User::create([
            'name' => 'Administator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
        ]);

        $setting = [];

        Setting::insert($setting);
    }
}
