<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\User;

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('password'),
        'remember_token' => str_random(10),
    ];
});

$faker = \Faker\Factory::create('pt_BR');

$factory->define(App\Models\Place::class, function () use ($faker) {

    $users = User::get()->pluck('id')->all();
    $categories = PlaceCategory::get()->pluck('id')->all();
    $name = $faker->company;

    return [

        'name' => $name,
        'user_id' => $faker->randomElement($users),
        'category_id' => $faker->randomElement($categories),
        'city' => $faker->city,
        'state' => $faker->stateAbbr,
        'description' => $faker->sentence(100),
        'min_guests' => rand(100, 200),
        'max_guests' => rand(300, 1000),
        'address' => json_decode('{"url": "https://maps.google.com/?q=Av.+Otac%C3%ADlio+Negr%C3%A3o+de+Lima+-+S%C3%A3o+Luiz,+Belo+Horizonte+-+MG,+31365-450,+Brasil&ftid=0xa69055ac376889:0xec4c68af4fa74f62", "name": "igreja da pampulha", "geolocation": {"lat": -19.8584157, "lng": -43.979020100000014}, "full_address": "Av. Otacílio Negrão de Lima - São Luiz, Belo Horizonte - MG, 31365-450, Brasil"}'),
        'informations' => json_decode('{"style": {"modern": false, "rustic": false, "classic": false, "authentic": false}, "guests": {"max": 0, "min": 0}, "covered": false, "parking": false, "services": {"others": false, "ceremony": false, "reception": false, "others_value": null}, "time_limit": false, "localization": {"city": false, "countryside": false, "city_surrounding": false}, "accessibility": false, "starter_price": 0, "payment_method": null, "multiple_events": false, "time_limit_value": "none", "music_exclusivity": false, "barman_exclusivity": false, "buffet_exclusivity": false, "decoration_exclusivity": false}'),
        'therms' => json_decode('{"accepted": true, "accpedted_at": "15/05/2017 11:03:58 AM"}'),
        'instructions' => json_decode('{"reservation": "<p><strong>Documentos necessários para </strong><strong style=\"color: rgb(102, 185, 102);\">reserva</strong></p><ul><li>Documento oficial <u>com foto </u>(carteira de identidade, CNH, carteira de trabalho, passaporte)&nbsp;</li><li>Documentos que possuem<u> data de validade</u> devem estar dentro do prazo.</li><li>Comprovante de endereço recente, emitido há no máximo <u>90 dias </u>(Contas de concessionárias públicas: Água, Luz, Gás, Telefonia Fixa ou Móvel)</li></ul>", "pre_reservation": "<p><strong>Documentos necessários para </strong><strong style=\"color: rgb(0, 102, 204);\">pré reserva</strong></p><ul><li>Documento oficial <u>com foto </u>(carteira de identidade, CNH, carteira de trabalho, passaporte)&nbsp;</li><li>Documentos que possuem<u> data de validade</u> devem estar dentro do prazo.</li><li>Comprovante de endereço recente, emitido há no máximo <u>90 dias </u>(Contas de concessionárias públicas: Água, Luz, Gás, Telefonia Fixa ou Móvel)</li></ul><p><br></p>"}'),
        'reservation_price' => rand(100, 200),
        'pre_reservation_price' => rand(50, 150),
        'slug' => str_slug($name, '-')

    ];
});

$factory->define(App\Models\Client::class, function () use($faker){
    static $password;

    return [
        'name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('password'),
        'phone' => $faker->cellphoneNumber,
        'remember_token' => str_random(10),
    ];
});

