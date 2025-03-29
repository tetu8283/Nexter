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
            $stores = Store::all();                                      // 従業員登録モーダル用
            $userStoreId = auth()->user()->store_id;                     // ログイン中のユーザーの所属店舗id
            $employeesNum = Store::find($userStoreId)->users()->count(); // 所属店舗の従業員数
            $inventoriesNum = Inventory::where('store_id', $userStoreId)
                ->join('books', 'inventories.book_id', '=', 'books.id')
                ->where('books.status_flag', 2)
                ->count();                                               // 所属店舗の在庫数

            // 在庫の総重量
            $totalBooksWeight = Inventory::where('store_id', $userStoreId)
                ->join('books', 'inventories.book_id', '=', 'books.id')
                ->where('books.status_flag', 2)
                ->sum('books.weight');

            // 所属店舗の在庫一覧を取得して、book情報をロード
            $inventories = Inventory::select('inventories.*')
                ->join('books', 'inventories.book_id', '=', 'books.id')
                ->where('inventories.store_id', $userStoreId)
                ->where('books.status_flag', 2)
                ->orderBy('inventories.created_at', 'desc')
                ->paginate(10); // 10件取得
                                                            // 店舗情報      全在庫         従業員数           在庫数             在庫総重量            所属店舗id
            return view('inventories.InventoryIndex', compact('stores', 'inventories', 'employeesNum', 'inventoriesNum', 'totalBooksWeight', 'userStoreId'));
        } else {
            $books = Book::all();                                        // 在庫登録モーダル用
            $userStoreId = auth()->user()->store_id;                     // ログイン中のユーザーの所属店舗id
            $userStore = Store::find($userStoreId);                      // 所属店舗
            $employeesNum = Store::find($userStoreId)->users()->count(); // 所属店舗の従業員数
            $inventoriesNum = Inventory::where('store_id', $userStoreId)
                ->join('books', 'inventories.book_id', '=', 'books.id')
                ->where('books.status_flag', 2)
                ->count();                                                // 所属店舗の在庫数

            // 在庫の総重量
            $totalBooksWeight = Inventory::where('store_id', $userStoreId)
                ->join('books', 'inventories.book_id', '=', 'books.id')
                ->where('books.status_flag', 2)
                ->sum('books.weight');

            // 所属店舗の在庫一覧を取得して、book情報をロード
            $inventories = Inventory::select('inventories.*')
                ->join('books', 'inventories.book_id', '=', 'books.id')
                ->where('inventories.store_id', $userStoreId)
                ->where('books.status_flag', 2)
                ->orderBy('inventories.created_at', 'desc')
                ->paginate(10); // 10件取得
                                                                // 全在庫      全書籍       従業員数           在庫数             在庫総重量         所属店舗id      所属店舗
            return view('inventories.InventoryIndex', compact('inventories', 'books', 'employeesNum', 'inventoriesNum', 'totalBooksWeight', 'userStoreId', 'userStore'));
        }
    }

    /**
     * 非同期で店舗情報取得
     *
     * @param int $storeId
     * @return void
     */
    public function getStoreInfo($storeId)
    {
        // 従業員数
        $employeesNum = Store::find($storeId)->users()->count();

        // 在庫数
        $inventoriesNum = Inventory::where('store_id', $storeId)
            ->join('books', 'inventories.book_id', '=', 'books.id')
            ->where('books.status_flag', 2)
            ->count();

        // 総重量
        $totalBooksWeight = Inventory::where('store_id', $storeId)
            ->join('books', 'inventories.book_id', '=', 'books.id')
            ->where('books.status_flag', 2)
            ->sum('books.weight');

        // 在庫一覧
        $inventories = Inventory::select('inventories.*')
            ->join('books', 'inventories.book_id', '=', 'books.id')
            ->where('inventories.store_id', $storeId)
            ->where('books.status_flag', 2)
            ->with('book')
            ->get();

        return response()->json([
            'employeesNum' => $employeesNum,
            'inventoriesNum' => $inventoriesNum,
            'totalBooksWeight' => $totalBooksWeight,
            'inventories' => $inventories,
        ]);
    }

    /**
     * 在庫一覧の無限スクロール用データ取得
     *
     * @param int
     * @return JsonResponse
     */
    public function loadInventories($pageNum, $storeId)
    {
        $inventories = Inventory::select('inventories.*')
        ->join('books', 'inventories.book_id', '=', 'books.id')
        ->where('inventories.store_id', $storeId)           // 店舗idの在庫取得
        ->where('books.status_flag', 2)
        ->with('book')
        ->orderBy('inventories.created_at', 'desc')
        ->paginate(10, ['*'], 'page', $pageNum);

        return response()->json([
            'inventories' => $inventories->items(),         // ページ内の在庫
            'hasMorePages' => $inventories->hasMorePages(), // 次ページが存在するか
        ]);
    }


    /**
     * 在庫登録
     *
     * @param Request
     * @return void
     */
    public function store(Request $request) {
        $store_id = $request->store_id;
        $book_ids = $request->book_id;
        // 登録済みのbook_idを取得
        $existingBookIds = Inventory::where('store_id', $store_id)->pluck('book_id')->toArray();

        $newStoreBookIds = array_diff($book_ids, $existingBookIds); // 新規登録されるbook_idのみを取得

        if(!empty($newStoreBookIds)) {
            // バルクインサート
            $data = array_map(function ($book_id) use ($store_id) {
                return [
                    'store_id' => $store_id,
                    'book_id' => $book_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $book_ids);

            Inventory::insert($data);
            // bookのstatus_flagを2にして、登録されている状態にする
            Book::whereIn('id', $book_ids)->update(['status_flag' => 2]);

            return redirect()->route('inventories.index')->with('flash_msg', '在庫を登録しました');
        }

        return redirect()->route('inventories.index')->with('flash_msg', '選択された在庫はすでに登録されています');
    }
}
