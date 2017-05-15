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
            'name' => 'Espaço de eventos',
            'slug' => 'espaco-de-eventos'
        ]);

        PlaceCategory::create([
            'name' => 'Igrejas',
            'slug' => 'igrejas'
        ]);
    }
}
