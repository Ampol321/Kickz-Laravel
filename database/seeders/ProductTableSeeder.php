<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 5) as $index) {
            DB::table('products')->insert([
                'product_img' => '/storage/images/Unknown.png',
                'product_name' => $faker->name(),
                'colorway' => $faker->word(),
                'size' => $faker->randomElement([6,7,8,9,10,11,12,13]),
                'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 2000.00, $max = 30000.00),
                'brand_id' => $faker->randomElement(array (1,2,3)),
                'type_id' => $faker->randomElement(array (1,2,3,4)),
            ]);
        }
    }
}
