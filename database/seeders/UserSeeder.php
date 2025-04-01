<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                [
                    'name' => 'ユーザー1',
                    'e_money'=> '10000',
                    'e_point'=> '500',
                    'buy1'=> 'コーラ',
                    'buy2'=> 'お茶',
                    'buy3'=> '水',
                    'created_at' => now(),
                    'updated_at' => now()
                  ],
            ]
        );
    }
}
