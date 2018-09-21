<?php

use Faker\Generator as Faker;

$factory->define(App\Models\UserAddress::class, function (Faker $faker) {
    $addresses = [
        ['江苏省','南京市','鼓楼区'],
        ['江苏省','南京市','建邺区'],
        ['江苏省','南京市','江宁区'],
        ['江苏省','南京市','秦淮区']
    ];

    $address = $faker->randomElement($addresses);
    return [
        'province' => $address[0],
        'city' => $address[1],
        'district' => $address[2],
        'address'       => sprintf('第%d街道第%d号', $faker->randomNumber(2), $faker->randomNumber(3)),
        'zip' => $faker->postcode,
        'contact_name' => $faker->name,
        'contact_phone' => $faker->phoneNumber
    ];
});
