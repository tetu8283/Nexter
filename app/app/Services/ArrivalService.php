<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Inventory;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use App\Models\Arrival;
use App\Models\User;

class ArrivalService
{
    public function getArrivalIndexData($user)
    {
        $storeId = $user->store_id;
        $employeesNum = Store::find($storeId)->users()->count();
        $inventoriesNum = Inventory::where('store_id', $storeId)
            ->join('books', 'inventories.book_id', '=', 'books.id')
            ->where('books.status_flag', 2)
            ->count();

        // 入荷確定の書籍数を取得
        $arrivalBooksNum = Arrival::where('store_id', $storeId)
            ->where('arrival_flag', 1) // 入荷確定のもの
            ->count();

        $arrivals = Arrival::with('book')
            ->where('store_id', $storeId)
            ->where('arrival_flag', 0)
            ->orderBy('arrival_date', 'desc')
            ->paginate(10);

        $arrivalBooks = Book::where('status_flag', 0)
            // inventoriesに存在しないもの
            ->whereDoesntHave('inventories', function ($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })
            // かつ、arrivalsにも存在しないもの
            ->whereDoesntHave('arrivals', function ($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })
            ->get();

        if ($user->role == 1) {
            $stores = Store::all(); // 従業員登録モーダル用

            return compact('stores', 'arrivals', 'employeesNum', 'inventoriesNum', 'arrivalBooksNum', 'storeId', 'arrivalBooks');
        } else {
            $books = Book::all();  // 在庫登録モーダル用
            $userStore = Store::find($storeId);

            return compact('arrivals', 'books', 'employeesNum', 'inventoriesNum', 'arrivalBooksNum', 'storeId', 'userStore', 'arrivalBooks');
        }
    }

}
