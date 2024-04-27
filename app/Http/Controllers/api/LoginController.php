<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    

    public function login(Request $request)
    {
        $check = DB::raw("SELECT * FROM users WHERE email = '$request->email' AND password_hash = '$request->password_hash' LIMIT 1");
        if ($check) {
            $user = DB::table('users')->join('roles', 'users.role_id', '=', 'roles.role_id')->where('email', $request->email)->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Login success',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Login failed',
                'data' => $request
            ]);
        }
    }
}
