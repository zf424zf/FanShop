<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Product::class, function (Faker $faker) {
    // 从数据库中随机取一个类目
    $category = \App\Models\Category::query()->where('is_directory', false)->inRandomOrder()->first();
    $image = $faker->randomElement([
        "https://ricefur.oss-cn-beijing.aliyuncs.com/ricefur/shop/product/7kG1HekGK6.jpg",
        "https://ricefur.oss-cn-beijing.aliyuncs.com/ricefur/shop/product/1B3n0ATKrn.jpg",
        "https://ricefur.oss-cn-beijing.aliyuncs.com/ricefur/shop/product/r3BNRe4zXG.jpg",
        "https://ricefur.oss-cn-beijing.aliyuncs.com/ricefur/shop/product/C0bVuKB2nt.jpg",
        "https://ricefur.oss-cn-beijing.aliyuncs.com/ricefur/shop/product/82Wf2sg8gM.jpg",
        "https://ricefur.oss-cn-beijing.aliyuncs.com/ricefur/shop/product/nIvBAQO5Pj.jpg",
        "https://ricefur.oss-cn-beijing.aliyuncs.com/ricefur/shop/product/XrtIwzrxj7.jpg",
        "https://ricefur.oss-cn-beijing.aliyuncs.com/ricefur/shop/product/uYEHCJ1oRp.jpg",
        "https://ricefur.oss-cn-beijing.aliyuncs.com/ricefur/shop/product/2JMRaFwRpo.jpg",
        "https://ricefur.oss-cn-beijing.aliyuncs.com/ricefur/shop/product/pa7DrV43Mw.jpg",
    ]);

    return [
        'title'        => $faker->word,
        'long_title'   => $faker->sentence,
        'description'  => $faker->sentence,
        'image'        => $image,
        'on_sale'      => true,
        'rating'       => $faker->numberBetween(0, 5),
        'sold_count'   => 0,
        'review_count' => 0,
        'price'        => 0,
        'category_id'  => $category ? $category->id : null,
    ];
});
