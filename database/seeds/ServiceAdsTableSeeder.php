<?php

use App\Models\Advertiser;
use App\Models\Place;
use App\Models\ServiceAd;
use App\Models\ServiceAdPhoto;
use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;

class ServiceAdsTableSeeder extends Seeder
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

            $adType = $faker->randomElement($adTypes);


            $serviceAd = ServiceAd::create([
                'id' => Uuid::generate()->string,
                'advertiser_id' => $advertiser->id,
                'type' => $adType,
                'action' => $faker->randomElement($adActions),
                'city' => $adType == 'city' ? 'Belo Horizonte' : null,
                'state' => $adType == 'city' ? 'MG' : null,
                'place_id' => $adType == 'place' ? Place::orderByRaw('RAND()')->take(1)->first()->id : null,
                'action_data' => $action_data,
                'expire_at' => $faker->dateTimeBetween($startDate =  'now', $endDate =  '4 weeks')->format('Y-m-d'),
                'title' => $faker->sentence(2),
                'description' => $faker->sentence(6),
                'is_active' => true
            ]);

            ServiceAdPhoto::create([
                'service_ad_id' => $serviceAd->id,
                'path' => 'service_ads/2e4884cd60c14beb751dca3407ac573b.png',
                'is_cover' => true
            ]);
        }

    }
}
