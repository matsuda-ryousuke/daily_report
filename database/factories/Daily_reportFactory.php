<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Daily_report;
use Faker\Generator as Faker;

$factory->define(Daily_report::class, function (Faker $faker) {
    $unique_report_id = random_int(1, 2);
    $user_id = random_int(1, 2);
    $client_id = random_int(1, 2);
    $area_id = random_int(1, 2);
    $status = random_int(1, 2);

    return [
        'unique_report_id' => $unique_report_id,
        'user_id' => $user_id,
        'client_id' => $client_id,
        'area_id' => $area_id,
        'visit_contents' =>
        "unique_report_id:" . $unique_report_id . "\n" .
            'user_id:' . $user_id . "\n" .
            'client_id:' . $client_id . "\n" .
            'area_id:' . $area_id . "\n" .
            'status:' . $status . "\n",
        'client_name' => '（株）北海道',
        'client_dep' => '営業',
        'next_step' => $faker->realText(50),
        'status' => $status,
        "visit_date" => $faker->date('Y-m-d', 'now'),
    ];
});
