<?php

use App\Models\Advertiser;
use App\Models\Decoration;
use App\Models\DecorationPhoto;
use App\Models\Place;
use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;

class DecorationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        $advertisers = Advertiser::all();

        $adTypes = ['city', 'home', 'place'];
        $adActions = ['call', 'email', 'whatsapp'];


        foreach ($advertisers as $advertiser){
            $action_data = ['email' => $advertiser->email, 'phone'=> $advertiser->phone, 'whatsapp' => $advertiser->whatsapp];


            $decoration = Decoration::create([
                'id' => Uuid::generate()->string,
                'advertiser_id' => $advertiser->id,
                'action' => $faker->randomElement($adActions),
                'place_id' => Place::where('confirmed', true)->orderByRaw('RAND()')->take(1)->first()->id,
                'action_data' => $action_data,
                'expire_at' => $faker->dateTimeBetween($startDate =  'now', $endDate =  '4 weeks')->format('Y-m-d'),
                'title' => $faker->sentence(2),
                'description' => $faker->sentence(6),
                'is_active' => true
            ]);

            DecorationPhoto::create([
                'decoration_id' => $decoration->id,
                'path' => 'service_ads/2e4884cd60c14beb751dca3407ac573b.png',
                'is_cover' => true
            ]);
        }
    }
}
