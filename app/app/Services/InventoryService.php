<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Inventory;
use App\Models\Store;

class InventoryService
{
    /**
     * 在庫一覧画面表示用データを取得
     *
     * @param  $user
     * @return array
     */
    public function getInventoryIndexData($user)
    {
        $userStoreId = $user->store_id;
        $employeesNum = Store::find($userStoreId)->users()->count();
        $inventoriesNum = Inventory::where('store_id', $userStoreId)
            ->join('books', 'inventories.book_id', '=', 'books.id')
            ->where('books.status_flag', 2)
            ->count();
        $totalBooksWeight = Inventory::where('store_id', $userStoreId)
            ->join('books', 'inventories.book_id', '=', 'books.id')
            ->where('books.status_flag', 2)
            ->sum('books.weight');

        $inventories = Inventory::select('inventories.*')
            ->join('books', 'inventories.book_id', '=', 'books.id')
            ->where('inventories.store_id', $userStoreId)
            ->where('books.status_flag', 2)
            ->orderBy('inventories.created_at', 'desc')
            ->paginate(10);

        if ($user->role == 1) {
            $stores = Store::all(); // 従業員登録モーダル用
            return compact('stores', 'inventories', 'employeesNum', 'inventoriesNum', 'totalBooksWeight', 'userStoreId');
        } else {
            $books = Book::all();  // 在庫登録モーダル用
            $userStore = Store::find($userStoreId);
            return compact('inventories', 'books', 'employeesNum', 'inventoriesNum', 'totalBooksWeight', 'userStoreId', 'userStore');
        }
    }

    /**
     * 非同期で店舗情報取得用データを取得
     *
     * @param int $storeId
     * @return array
     */
    public function getStoreInfoData($storeId)
    {
        $employeesNum = Store::find($storeId)->users()->count();
        $inventoriesNum = Inventory::where('store_id', $storeId)
            ->join('books', 'inventories.book_id', '=', 'books.id')
            ->where('books.status_flag', 2)
            ->count();
        $totalBooksWeight = Inventory::where('store_id', $storeId)
            ->join('books', 'inventories.book_id', '=', 'books.id')
            ->where('books.status_flag', 2)
            ->sum('books.weight');
        $inventories = Inventory::select('inventories.*')
            ->join('books', 'inventories.book_id', '=', 'books.id')
            ->where('inventories.store_id', $storeId)
            ->where('books.status_flag', 2)
            ->with('book')
            ->get();

        return compact('employeesNum', 'inventoriesNum', 'totalBooksWeight', 'inventories');
    }

    /**
     * 無限スクロール用在庫一覧データを取得
     *
     * @param int $pageNum
     * @param int $storeId
     * @return array
     */
    public function loadInventoriesData($pageNum, $storeId)
    {
        $inventories = Inventory::select('inventories.*')
            ->join('books', 'inventories.book_id', '=', 'books.id')
            ->where('inventories.store_id', $storeId)
            ->where('books.status_flag', 2)
            ->with('book')
            ->orderBy('inventories.created_at', 'desc')
            ->paginate(10, ['*'], 'page', $pageNum);

        return [
            'inventories' => $inventories->items(),
            'hasMorePages' => $inventories->hasMorePages(),
        ];
    }

    /**
     * 在庫登録処理
     *
     * @param int   $storeId
     * @param array $bookIds
     * @return array
     */
    public function storeInventory($storeId, array $bookIds)
    {
        // 既に登録されているbook_idを取得
        $existingBookIds = Inventory::where('store_id', $storeId)->pluck('book_id')->toArray();
        $newStoreBookIds = array_diff($bookIds, $existingBookIds);

        if (!empty($newStoreBookIds)) {
            // バルクインサート用のデータ作成（新規登録分のみ）
            $data = array_map(function ($book_id) use ($storeId) {
                return [
                    'store_id' => $storeId,
                    'book_id' => $book_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $newStoreBookIds);

            Inventory::insert($data);
            // 登録済みではないbook_idのstatus_flagを更新
            Book::whereIn('id', $newStoreBookIds)->update(['status_flag' => 2]);

            return ['success' => true, 'message' => '在庫を登録しました'];
        }

        return ['success' => false, 'message' => '選択された在庫はすでに登録されています'];
    }
}
