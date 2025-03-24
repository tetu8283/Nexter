<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Store;

class InventoryController extends Controller
{
    public function index() {
        $stores = Store::all(); // 従業員登録モーダル用の店舗データ

        return view('inventories.InventoryIndex', compact('stores'));
    }
}
