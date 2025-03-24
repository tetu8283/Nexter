<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stores')->insert([
            ['name' => '店舗1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '店舗2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '店舗3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '店舗4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '店舗5', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '店舗6', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '店舗7', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '店舗8', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '店舗9', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '店舗10', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
