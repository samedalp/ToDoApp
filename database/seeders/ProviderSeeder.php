<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('provider')->insert([
            [
                'name' => "Provider1",
                'endpoint' => "http://www.mocky.io/v2/5d47f24c330000623fa3ebfa",
                'parameters' => "zorluk,sure,id"
            ],
            [
                'name' => "Provider2",
                'endpoint' => "http://www.mocky.io/v2/5d47f235330000623fa3ebf7",
                'parameters' => "level,estimated_duration,{key}"
            ]
        ]);
    }
}
