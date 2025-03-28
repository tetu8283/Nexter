<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * 商品登録
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $names = $request->input('name');
        $weights = $request->input('weight');
        $statusFlags = $request->input('status_flag');
        $images = $request->file('image');

        $books = [];

        for ($i = 0; $i < count($names); $i++) {
            // オリジナルファイル名を取得
            $file_name = $images[$i]->getClientOriginalName();

            // 画像を指定ディレクトリに保存
            $imagePath = $images[$i]->storeAs('public/books', $file_name);

            // DB へ保存するパスを生成
            $publicPath = 'storage/books/' . $file_name;

            $books[] = [
                'name' => $names[$i],
                'image' => $publicPath,
                'weight' => $weights[$i],
                'status_flag' => $statusFlags[$i],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // バルクインサート
        Book::insert($books);

        return redirect('/inventories')->with('flash_msg', '商品を登録しました');
    }
}
