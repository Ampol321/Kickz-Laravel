<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ShipmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 5) as $index) {
            DB::table('shipments')->insert([
                'shipment_img' => '/storage/images/Unknown.png',
                'shipment_name' => $faker->word(),
                'shipment_cost' => $faker->randomFloat($nbMaxDecimals = 2, $min = 30.00, $max = 100.00),
            ]);
        }
    }
}
