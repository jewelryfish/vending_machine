<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DrinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('drinks')->insert(
            [
              [
                'name' => 'テスト1',
                'image'=> 'drink_bin_bottle_blue_test.png',
                'price'=> '500',
                'stock'=> '10',
                'temperature'=> '1',
                'created_at' => now(),
                'updated_at' => now(),
              ],
              [
                'name' => 'テスト2',
                'image'=> 'drink_bin_bottle_cola_test.png',
                'price'=> '500',
                'stock'=> '5',
                'temperature'=> '1',
                'created_at' => now(),
                'updated_at' => now(),
              ],
              [
                'name' => 'テスト3',
                'image'=> 'drink_bin_bottle_cola_test.png',
                'price'=> '500',
                'stock'=> '5',
                'temperature'=> '0',
                'created_at' => now(),
                'updated_at' => now(),
              ],
              [
                'name' => 'テスト4',
                'image'=> 'drink_bin_bottle_cola_test.png',
                'price'=> '500',
                'stock'=> '5',
                'temperature'=> '0',
                'created_at' => now(),
                'updated_at' => now(),
              ],
              [
                'name' => 'テスト5',
                'image'=> 'drink_bin_bottle_blue_test.png',
                'price'=> '500',
                'stock'=> '0',
                'temperature'=> '0',
                'created_at' => now(),
                'updated_at' => now(),
              ],
              [
                'name' => 'テスト6',
                'image'=> 'drink_bin_bottle_blue_test.png',
                'price'=> '500',
                'stock'=> '10',
                'temperature'=> '1',
                'created_at' => now(),
                'updated_at' => now(),
              ],
              [
                'name' => 'テスト7',
                'image'=> 'drink_bin_bottle_blue_test.png',
                'price'=> '500',
                'stock'=> '10',
                'temperature'=> '1',
                'created_at' => now(),
                'updated_at' => now(),
              ],
            ]
        );
    }
}
