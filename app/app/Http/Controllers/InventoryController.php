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
            $books = Book::all(); // 在庫登録モーダル用

            return view('inventories.InventoryIndex', compact('books'));
        }
    }

    /**
     * 在庫登録
     *
     * @param Request
     * @return void
     */
    public function store(Request $request) {
        // 配列で取得
        $store_id = $request->store_id;
        $book_ids = $request->book_id;

        // バルクインサートで登録(複数登録対応)
        $data = array_map(function ($book_id) use ($store_id) {
            return [
                'store_id' => $store_id,
                'book_id' => $book_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $book_ids);

        Inventory::insert($data);

        return redirect()->route('inventories.index')->with('flash_msg', '在庫を登録しました');
    }

}
