<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function store(Request $request) {
        $names = $request->input('name');
        $emails = $request->input('email');
        $passwords = $request->input('password');
        $store_ids = $request->input('store_id');
        $roles = $request->input('role');

        $users = [];
        $skipped = [];

        for ($i = 0; $i < count($names); $i++) {
            // 登録済みのメアドはスキップ
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

        $msg = count($users) > 0 ? 'ユーザーを登録しました。' : '';
        if (!empty($skipped)) {
            $msg .= ' 次メールアドレスは登録済みのためスキップされました: ' . implode(', ', $skipped);
        }

        return redirect()->route('inventories.index')->with('flash_msg', $msg);
    }
}
