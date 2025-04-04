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
     * 入荷情報登録
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        // 共通の店舗id
        $storeId = $request->input('store_id');

        // 入力値を取得（配列かどうかをチェックして配列に変換）
        $bookIds = $request->input('book_id');
        $arrivalDates = $request->input('arrival_date');
        $arrivalFlags = $request->input('arrival_flag');

        // 1件の場合、文字列になっているので配列に変換
        if (!is_array($bookIds)) {
            $bookIds = [$bookIds];
        }
        if (!is_array($arrivalDates)) {
            $arrivalDates = [$arrivalDates];
        }
        if (!is_array($arrivalFlags)) {
            $arrivalFlags = [$arrivalFlags];
        }

        $arrivals = [];

        // 配列の件数分ループ
        for ($i = 0; $i < count($bookIds); $i++) {
            $arrivals[] = [
                'store_id'     => $storeId,
                'book_id'      => $bookIds[$i],
                'arrival_date' => $arrivalDates[$i],
                'arrival_flag' => $arrivalFlags[$i],
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        // バルクインサートで複数件を登録
        Arrival::insert($arrivals);

        return redirect('/arrivals')->with('flash_msg', '入荷情報を登録しました');
    }



    /**
     * 統合エンドポイント
     *
     * @param Request $request
     * @param [type] $storeId
     * @return void
     */
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

    public function updateSingleArrivalFlag(Request $request)
    {
        $ids = $request->input('ids');

        if ($ids && count($ids) > 0) {
            Arrival::whereIn('id', $ids)->update([
                'arrival_flag' => 1,
                'updated_at'   => now(),
            ]);

            $bookIds = Arrival::whereIn('id', $ids)
                ->pluck('book_id')
                ->unique();

            // Bookのstatus_flagを更新
            Book::whereIn('id', $bookIds)->update([
                'status_flag' => 2,
                'updated_at'  => now(),
            ]);

            $inventoryData = [];
            $arrivals = Arrival::whereIn('id', $ids)->get();

            foreach ($arrivals as $arrival) {
                $inventoryData[] = [
                    'store_id'   => $arrival->store_id,
                    'book_id'    => $arrival->book_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($inventoryData)) {
                Inventory::insert($inventoryData);
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => '確定する入荷予定を選択してください'], 400);
    }
}
