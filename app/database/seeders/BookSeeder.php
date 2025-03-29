<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            'storage/books/レオちゃん.jpeg',
            'storage/books/ゴーファー.png',
        ];

        $books = [];
        for ($i = 1; $i <= 300; $i++) {
            $books[] = [
                'name' => 'Book ' . $i,
                'image' => $images[$i % 2], // 交互に画像を設定
                'weight' => mt_rand(0.3, 20) / 10, // ランダムな重さ (10.0 ~ 100.0)
                'status_flag' => 0, // 未登録
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('books')->insert($books);
    }
}
