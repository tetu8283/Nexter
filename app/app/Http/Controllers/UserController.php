<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function store(Request $request) {
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->store_id = $request->store_id;
        $user->role = $request->role; // 一般従業員は固定

        $user->save();

        $flash_msg = 'ユーザーを登録しました';
        return redirect()->route('inventories.index')->with('flash_msg', $flash_msg);
    }
}
