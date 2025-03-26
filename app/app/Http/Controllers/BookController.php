<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function store(Request $request)
    {
        $book = new Book();

        $book->name = $request->name;
        $book->image = $request->image;
        $book->weight = $request->weight;
        $book->status_flag = $request->status_flag;

        $flash_msg = '商品を登録しました';
        $book->save();
        return redirect('/inventories')->with('flash_msg', $flash_msg);
    }
}
