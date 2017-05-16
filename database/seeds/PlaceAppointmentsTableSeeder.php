<?php

use App\Models\Place;
use App\Models\PlaceAppointment;
use Illuminate\Database\Seeder;

class PlaceAppointmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $places = Place::get()->pluck('id')->all();

        foreach ($places as $key => $place) {
            $key =  (integer) $key + 1;

            PlaceAppointment::create([
                'place_id' => $place,
                'title' => 'Evento ' . $key,
                'allDay' => true,
                'start' => \Carbon\Carbon::now()->toDateTimeString(),
                'end' => \Carbon\Carbon::now()->addHour(4)->toDateTimeString(),
            ]);

            $key = $key + 1;
            PlaceAppointment::create([
                'place_id' => $place,
                'title' => 'Evento ' . $key,
                'allDay' => true,
                'start' => \Carbon\Carbon::now()->addDay(1)->toDateTimeString(),
                'end' => \Carbon\Carbon::now()->addDay(1)->addHour(4)->toDateTimeString(),
            ]);

            $key = $key + 1;
            PlaceAppointment::create([
                'place_id' => $place,
                'title' => 'Evento ' . $key,
                'allDay' => true,
                'start' => \Carbon\Carbon::now()->addDay(3)->toDateTimeString(),
                'end' => \Carbon\Carbon::now()->addDay(3)->addHour(4)->toDateTimeString(),
            ]);
        }


    }
}
