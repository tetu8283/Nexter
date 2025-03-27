<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function store(Request $request) {
        // データ取得
        $names = $request->input('name');
        $emails = $request->input('email');
        $passwords = $request->input('password');
        $store_ids = $request->input('store_id');
        $roles = $request->input('role');

        $users = []; // 登録用
        $skipped = []; // スキップ用

        // 人数分ループ
        for ($i = 0; $i < count($names); $i++) {
            // メアドの重複チェック
            if (DB::table('users')->where('email', $emails[$i])->exists()) {
                $skipped[] = $emails[$i];
                continue;
            }

            $users[] = [
                'name' => $names[$i],
                'email' => $emails[$i],
                'password' => Hash::make($passwords[$i]),
                'store_id' => $store_ids[$i],
                'role' => $roles[$i],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($users)) {
            User::insert($users);
        }

        $msg = count($users) > 0 ? 'ユーザーを登録しました' : '';
        if (!empty($skippedEmails)) {
            $msg = ' 次のメールアドレスは、既に登録済みのため登録されませんでした: ' . implode(', ', $skipped);
        }

        return redirect()->route('inventories.index')->with('flash_msg', $msg);
    }
}
