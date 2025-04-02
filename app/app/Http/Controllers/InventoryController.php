<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Store;
use App\Services\InventoryService;
use App\Services;

class InventoryController extends Controller
{
    protected $inventoryService;

    /**
     * コンストラクタ
     *
     * @param InventoryService $inventoryService
     */
    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * 在庫一覧画面表示
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = $this->inventoryService->getInventoryIndexData(auth()->user());
        return view('inventories.InventoryIndex', $data);
    }

    /**
     * 統合エンドポイント
     * 検索でも使用
     *
     * @param Request $request
     * @param int $storeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInventoryData(Request $request, $storeId)
    {
        $page = $request->input('page', 1);
        // 検索フォームからの値を取得
        $searchName = $request->input('name');
        $startDate  = $request->input('start_date');
        $endDate    = $request->input('end_date');

        $employeesNum = Store::find($storeId)->users()->count();
        $inventoriesNum = Inventory::where('store_id', $storeId)
            ->join('books', 'inventories.book_id', '=', 'books.id')
            ->where('books.status_flag', 2)
            ->count();
        $totalBooksWeight = Inventory::where('store_id', $storeId)
            ->join('books', 'inventories.book_id', '=', 'books.id')
            ->where('books.status_flag', 2)
            ->sum('books.weight');

        $query = Inventory::with('book')
            ->where('store_id', $storeId)
            ->whereHas('book', function ($q) {
                $q->where('status_flag', 2);
            });

        if ($searchName) {
            $query->whereHas('book', function ($q) use ($searchName) {
                $q->where('name', 'LIKE', '%' . $searchName . '%');
            });
        }
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $inventories = $query->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);

        return response()->json([
            'inventories'    => $inventories->items(),
            'hasMorePages'   => $inventories->hasMorePages(),
            'employeesNum'   => $employeesNum,
            'inventoriesNum' => $inventoriesNum,
            'totalBooksWeight' => $totalBooksWeight,
        ]);
    }

    /**
     * 非同期で店舗情報取得
     *
     * @param int $storeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStoreInfo($storeId)
    {
        $data = $this->inventoryService->getStoreInfoData($storeId);
        return response()->json($data);
    }

    /**
     * 在庫一覧の無限スクロール用データ取得
     *
     * @param int $pageNum
     * @param int $storeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadInventories($pageNum, $storeId)
    {
        $data = $this->inventoryService->loadInventoriesData($pageNum, $storeId);
        return response()->json($data);
    }

    /**
     * 在庫登録
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $store_id = $request->store_id;
        $book_ids = $request->book_id;
        $result = $this->inventoryService->storeInventory($store_id, $book_ids);

        return redirect()->route('inventories.index')->with('flash_msg', $result['message']);
    }
}
