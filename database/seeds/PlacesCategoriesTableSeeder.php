<?php

use App\Models\PlaceCategory;
use Illuminate\Database\Seeder;

class PlacesCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PlaceCategory::create([
            'name' => 'Espaço de festas',
            'slug' => 'espaco-de-festas'
        ]);

        PlaceCategory::create([
            'name' => 'Cerimônia',
            'slug' => 'cerimonia'
        ]);
    }
}
