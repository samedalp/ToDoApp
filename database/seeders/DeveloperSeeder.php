<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeveloperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('developer')->insert([
            [
                'name' => "Dev1",
                'level' => 1,
            ],
            [
                'name' => "Dev2",
                'level' => 2,
            ],
            [
                'name' => "Dev3",
                'level' => 3,
            ],
            [
                'name' => "Dev4",
                'level' => 4,
            ],
            [
                'name' => "Dev5",
                'level' => 5,
            ]
        ]);
    }
}
