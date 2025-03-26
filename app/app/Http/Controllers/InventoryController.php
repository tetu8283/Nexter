<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Store;

class InventoryController extends Controller
{
    /**
     * 在庫一覧画面表示
     *
     * @return void
     */
    public function index() {
        if (auth()->user()->role == 1) {
            $stores = Store::all(); // 従業員登録モーダル用

            return view('inventories.InventoryIndex', compact('stores'));
        } else {
            $stores = Store::all(); // 従業員登録モーダル用
            $books = Book::all(); // 在庫登録モーダル用

            return view('inventories.InventoryIndex', compact('stores', 'books'));
        }
    }
}
