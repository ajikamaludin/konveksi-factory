<?php

use App\Models\Compositions;
use App\Models\Permission;
use App\Models\SettingPayroll;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
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
            //Compositions
            ['id' => Str::uuid(), 'label' => 'Buat Komposisi', 'name' => 'create-composition'],
            ['id' => Str::uuid(), 'label' => 'Edit Komposisi', 'name' => 'update-composition'],
            ['id' => Str::uuid(), 'label' => 'Lihat Komposisi', 'name' => 'view-composition'],
            ['id' => Str::uuid(), 'label' => 'Hapus Komposisi', 'name' => 'delete-composition'],
        ];

        $permit = Permission::where('name', 'create-setting')->first();

        if ($permit == null) {
            foreach ($permissions as $permission) {
                Permission::insert($permission);
            }
        }

        $setting = SettingPayroll::first();
        if ($setting == null) {
            SettingPayroll::create([
                'payroll' => '100000',
                'workhours_sunday' => '8',
                'workhours_monday' => '9',
                'workhours_tuesday' => '9',
                'workhours_wednesday' => '9',
                'workhours_thusday' => '9',
                'workhours_friday' => '9',
                'workhours_saturday' => '8',
            ]);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
