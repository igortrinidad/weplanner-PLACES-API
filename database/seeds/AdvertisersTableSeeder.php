<?php

use Illuminate\Database\Seeder;

class AdvertisersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Advertiser::create([
            'name' => 'Manolo Sauro Company',
            'email' => 'contato@matheuslima.com.br',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
            'phone' => '987654321',
            'whatsapp' => '+559876543210',
            'website' => 'https://matheuslima.com.br'
        ]);

        factory(App\Models\Advertiser::class, 10)->create();
    }
}
