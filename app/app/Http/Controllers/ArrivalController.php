<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arrival;
use App\Models\Store;
use App\Models\Book;

class ArrivalController extends Controller
{
    /**
     * 入荷一覧画面表示
     *
     * @return void
     */
    public function index() {
        if (auth()->user()->role == 1) {
            $stores = Store::all(); // 従業員登録モーダル用

            return view('arrivals.ArrivalIndex', compact('stores'));
        } else {
            $stores = Store::all(); // 従業員登録モーダル用
            $books = Book::all(); // 在庫登録モーダル用

            return view('arrivals.ArrivalIndex', compact('stores', 'books'));
        }

    }

    /**
     * 入荷登録画面表示
     *
     * @return void
     */
    public function create() {
        if (auth()->user()->role == 1) {
            $stores = Store::all(); // 従業員登録モーダル用

            return view('arrivals.ArrivalStore', compact('stores'));
        } else {
            $stores = Store::all(); // 従業員登録モーダル用
            $books = Book::all(); // 在庫登録モーダル用

            return view('arrivals.ArrivalStore', compact('stores', 'books'));
        }
    }
}
