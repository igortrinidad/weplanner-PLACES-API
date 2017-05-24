<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory(App\Models\User::class)->create([
            'name' => 'Matheus',
            'last_name' => 'Lima',
            'email' => 'me@matheuslima.com.br',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
        ]);

        factory(App\Models\User::class, 10)->create();

        $this->call(PlacesCategoriesTableSeeder::class);

        factory(App\Models\Place::class, 100)->create();

        $this->call(PlaceCallendarSettingsTableSeeder::class);

        $this->call(PlacePhotoTableSeeder::class);

        $this->call(PlaceAppointmentsTableSeeder::class);

        factory(App\Models\Client::class)->create([
            'name' => 'Matheus',
            'last_name' => 'Lima',
            'email' => 'contato@matheuslima.com.br',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
        ]);

        factory(App\Models\Client::class, 10)->create();
    }
}
