<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('machines')->insert(
            [
                [
                    'name' => 'drinker',
                    'pass'=> 'rext',
                    'id_User'=> '1',
                    'created_at' => now(),
                    'updated_at' => now()
                  ],
            ]
        );
    }
}