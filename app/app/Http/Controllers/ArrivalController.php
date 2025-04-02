<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Arrival;
use App\Models\Store;
use App\Models\Book;
use App\Models\Inventory;
use App\Services\ArrivalService;

class ArrivalController extends Controller
{
    protected $arrivalService;

    /**
     * コンストラクタ
     *
     * @param ArrivalService $arrivalService
     */
    public function __construct(ArrivalService $arrivalService)
    {
        $this->arrivalService = $arrivalService;
    }

    /**
     * 入荷一覧画面表示
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = $this->arrivalService->getArrivalIndexData(auth()->user());
        return view('arrivals.ArrivalIndex', $data);
    }

    /**
     * 入荷登録
     *
     * @return void
     */
    public function create()
    {
        $userStoreId = Auth::user()->store_id;
        $arrivalBooks = Book::where('status_flag', 0)
                ->whereDoesntHave('inventories', function ($query) use ($userStoreId) {
                    $query->where('store_id', $userStoreId);
                })
                ->get();

        $data = $this->arrivalService->getArrivalIndexData(auth()->user());

        return view('arrivals.ArrivalStore', $data, compact('arrivalBooks'));
    }


    public function getArrivalData(Request $request, $storeId)
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

        $arrivalBooksNum = Arrival::where('store_id', $storeId)
            ->where('arrival_flag', 1) // 入荷が確定しているもの
            ->count();

        $query = Inventory::with('book')
            ->where('store_id', $storeId)
            ->whereHas('book', function ($q) {
                $q->where('status_flag', 2);
            });

        $arrivals = Arrival::with('book')
            ->where('store_id', $storeId)
            ->where('arrival_flag', 0);

        if ($searchName) {
            $query->whereHas('book', function ($q) use ($searchName) {
                $q->where('name', 'LIKE', '%' . $searchName . '%');
            });
        }
        if ($startDate && $endDate) {
            $query->whereBetween('arrival_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('arrival_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('arrival_date', '<=', $endDate);
        }

        $arrivals = $query->orderBy('arrival_date', 'desc')
            ->paginate(10, ['*'], 'page', $page);

        return response()->json([
            'arrivals'    => $arrivals->items(),
            'hasMorePages'   => $arrivals->hasMorePages(),
            'employeesNum'   => $employeesNum,
            'inventoriesNum' => $inventoriesNum,
            'arrivalBooksNum' => $arrivalBooksNum,
        ]);
    }

    public function loadArrivals(Request $request, $pageNum, $storeId)
    {
        // 検索条件の取得
        $searchName = $request->input('name');
        $startDate  = $request->input('start_date');
        $endDate    = $request->input('end_date');

        // 基本のクエリ作成（店舗・入荷未確定のみ）
        $query = Arrival::with('book')
            ->where('store_id', $storeId)
            ->where('arrival_flag', 0);

        // 商品名検索（関連するBookモデルのnameカラム）
        if ($searchName) {
            $query->whereHas('book', function ($q) use ($searchName) {
                $q->where('name', 'LIKE', '%' . $searchName . '%');
            });
        }

        // 日付検索
        if ($startDate && $endDate) {
            $query->whereBetween('arrival_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('arrival_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('arrival_date', '<=', $endDate);
        }

        // 入荷予定日で並び替え＆ページネーション
        $arrivals = $query->orderBy('arrival_date', 'desc')
            ->paginate(10, ['*'], 'page', $pageNum);

        return response()->json([
            'arrivals'     => $arrivals->items(),
            'hasMorePages' => $arrivals->hasMorePages(),
        ]);
    }
}
