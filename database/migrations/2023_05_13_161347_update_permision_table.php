<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\SettingPayroll;
use App\Models\Supplier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            //Compositions
            ['id' => Str::uuid(), 'label' => 'Buat Komposisi', 'name' => 'create-composition'],
            ['id' => Str::uuid(), 'label' => 'Edit Komposisi', 'name' => 'update-composition'],
            ['id' => Str::uuid(), 'label' => 'Lihat Komposisi', 'name' => 'view-composition'],
            ['id' => Str::uuid(), 'label' => 'Hapus Komposisi', 'name' => 'delete-composition'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], ['id' => $permission['id'], 'label' => $permission['label']]);
        }

        $role = Role::where(['name' => 'admin'])->first();
        $permissions = Permission::all();
        if ($role != null) {
            foreach ($permissions as $permission) {
                $role->rolePermissions()->create(['permission_id' => $permission->id]);
            }
        }
        $supplier = Supplier::first();
        if ($supplier==null){
            Supplier::create([
                'name' => 'PT Maju Lancar',
                'address' => 'Jl pandega sakti',
                'phonenumber' => '081231237821',
                'emails' => 'maju@mail.com',
            ]);
        }
       
        $settingPayroll = SettingPayroll::first();
        if ($settingPayroll == null) {
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