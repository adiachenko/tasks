<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Ramsey\Uuid\Uuid;

$factory->define(App\EmailConfirmation::class, function (Faker\Generator $faker) {
    return [
        'id' => Uuid::uuid4()->toString(),
        'email' => function () {
            return factory(App\User::class)->create()->email;
        },
    ];
});
