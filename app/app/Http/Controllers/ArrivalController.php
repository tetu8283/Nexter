<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $stores = Store::all();  // 従業員登録モーダル用

            return view('arrivals.ArrivalIndex', compact('stores'));
        } else {
            $stores = Store::all();  // 従業員登録モーダル用
            $books = Book::all();    // 在庫登録モーダル用

            return view('arrivals.ArrivalIndex', compact('stores', 'books'));
        }

    }

    /**
     * 入荷登録画面表示
     *
     * @return void
     */
    public function create() {
        if(auth()->user()->role === 1) {
            $stores = Store::all();
            $userStoreId = Auth::user()->store_id;
            $books = Book::all();

            return view('arrivals.ArrivalStore', compact('stores', 'books', 'userStoreId'));
        } else {
            $userStoreId = Auth::user()->store_id;
            $books = Book::where('status_flag', 0)
                ->whereDoesntHave('inventories', function ($query) use ($userStoreId) {
                    $query->where('store_id', $userStoreId);
                })
                ->get();

            return view('arrivals.ArrivalStore', compact('books', 'userStoreId'));
        }
    }

    /**
     * 入荷予定登録
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request) {
        $arrival = new Arrival();

        $arrival->store_id = $request->store_id;
        $arrival->book_id = $request->book_id;
        $arrival->arrival_date = $request->arrival_date;
        $arrival->arrival_flag = $request->arrival_flag;
        $arrival->created_at = now();
        $arrival->updated_at = now();

        $arrival->save();

        return redirect()->route('arrivals.index');
    }
}
