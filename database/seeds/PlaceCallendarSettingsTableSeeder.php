<?php

use App\Models\Place;
use Illuminate\Database\Seeder;

class PlaceCallendarSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $places = Place::get()->pluck('id')->all();

        foreach ($places as $place) {

            \App\Models\PlaceCalendarSettings::create([
                'place_id' => $place,
                'available_dates_range' => json_decode('[]'),
                'available_days_config' => json_decode('[{"name": "monday", "label": "Segunda-feira", "open_hours": {"to": null, "from": null}, "day_of_week": 1, "unavailable": false}, {"name": "tuesday", "label": "Terça-feira", "open_hours": {"to": null, "from": null}, "day_of_week": 2, "unavailable": false}, {"name": "wednesday", "label": "Quarta-feira", "open_hours": {"to": null, "from": null}, "day_of_week": 3, "unavailable": false}, {"name": "thursday", "label": "Quinta-feira", "open_hours": {"to": null, "from": null}, "day_of_week": 4, "unavailable": false}, {"name": "friday", "label": "Sexta-feira", "open_hours": {"to": null, "from": null}, "day_of_week": 5, "unavailable": false}, {"name": "saturday", "label": "Sábado", "open_hours": {"to": null, "from": null}, "day_of_week": 6, "unavailable": false}, {"name": "sunday", "label": "Domingo", "open_hours": {"to": null, "from": null}, "day_of_week": 7, "unavailable": false}]')
            ]);

        }
    }
}
