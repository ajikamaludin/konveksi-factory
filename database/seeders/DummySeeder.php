<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Buyer;
use App\Models\Color;
use App\Models\Material;
use App\Models\SettingPayroll;
use App\Models\Size;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->size();
        $this->color();
        $this->brand();
        $this->buyer();
        $this->material();
        $this->setting();
    }

    public function size()
    {
        $sizes = [
            ['id' => Str::uuid(), 'name' => 'XS'],
            ['id' => Str::uuid(), 'name' => 'S'],
            ['id' => Str::uuid(), 'name' => 'M'],
            ['id' => Str::uuid(), 'name' => 'L'],
            ['id' => Str::uuid(), 'name' => 'XL'],
            ['id' => Str::uuid(), 'name' => 'XXL'],
            ['id' => Str::uuid(), 'name' => 'XXXL'],
        ];

        Size::insert($sizes);
    }

    public function color()
    {
        foreach (['Merah', 'Kuning', 'Hijau'] as $c) {
            Color::create(['name' => $c]);
        }
    }

    public function brand()
    {
        foreach (['Brand A', 'Brand B', 'Brand C'] as $b) {
            Brand::create(['name' => $b]);
        }
    }

    public function buyer()
    {
        foreach (['Buyer A', 'Buyer B', 'Buyer C'] as $b) {
            Buyer::create(['name' => $b]);
        }
    }

    public function material()
    {
        foreach (['Material A', 'Material B', 'Material C'] as $b) {
            Material::create(['name' => $b]);
        }
    }

    public function supplier()
    {
        Supplier::create([
            'name' => 'PT Maju Lancar',
            'address' => 'Jl pandega sakti',
            'phonenumber' => '081231237821',
            'emails' => 'maju@mail.com',
        ]);
    }

    public function setting()
    {
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
