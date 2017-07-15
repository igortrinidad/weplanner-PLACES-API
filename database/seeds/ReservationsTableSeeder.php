<?php

use App\Models\Client;
use App\Models\Place;
use App\Models\PlaceReservations;
use Illuminate\Database\Seeder;

class ReservationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        $places = Place::where('confirmed', true)->get()->random(20)->pluck('id')->toArray();
        $clients = Client::all()->pluck('id')->toArray();


        foreach ($places as $place){

            $range = range(1, $faker->randomNumber(1));

            foreach($range as $item){
                PlaceReservations::create([
                    'place_id' => $place,
                    'client_id' => $faker->randomElement($clients),
                    'date' => $faker->dateTimeBetween($startDate =  '-2 months', $endDate =  'now')->format('Y-m-d'),
                    'all_day' => true,
                    'is_pre_reservation' => $faker->randomElement([true, false])
                ]);
            }
        }
    }
}
