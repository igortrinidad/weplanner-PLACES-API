<?php

use App\Models\Place;
use Illuminate\Database\Seeder;

class PromotionalDatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        $places = Place::where('confirmed', true)->get()->random(24)->pluck('id');

        foreach ($places as $place){
            \App\Models\PromotionalDate::create([
                'place_id' => $place,
                'date' => $faker->dateTimeBetween($startDate =  'now', $endDate =  '3 weeks')->format('Y-m-d'),
                'title' => $faker->sentence(6),
                'value' => $faker->numberBetween(1500, 5000),
                'discount' => $faker->numberBetween(10, 50),
                'rules' => $faker->sentence(50),
            ]);
        }
    }
}
