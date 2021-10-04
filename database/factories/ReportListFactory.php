<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Reports_list;
use Faker\Generator as Faker;

$factory->define(Reports_list::class, function (Faker $faker) {

    return [
        'log_report_id' => random_int(1, 2),
    ];
});
