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
            $imagePath = $images[$i]->store('books', 'public');

            $books[] = [
                'name' => $names[$i],
                'image' => $imagePath,
                'weight' => $weights[$i],
                'status_flag' => $statusFlags[$i],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Book::insert($books);

        return redirect('/inventories')->with('flash_msg', '商品を登録しました');
    }
}
