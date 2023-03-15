<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Buyer;
use App\Models\Color;
use App\Models\Material;
use App\Models\Size;
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
        foreach(['red', 'green', 'blue'] as $c) {
            Color::create(['name' => $c]);
        }
    }

    public function brand()
    {
        foreach(['Brand A', 'Brand B', 'Brand C'] as $b) {
            Brand::create(['name' => $b]);
        }
    }

    public function buyer()
    {
        foreach(['Buyer A', 'Buyer B', 'Buyer C'] as $b) {
            Buyer::create(['name' => $b]);
        }
    }

    public function material()
    {
        foreach(['Material A', 'Material B', 'Material C'] as $b) {
            Material::create(['name' => $b]);
        }
    }
}
