<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{


    public function login(Request $request)
    {
        $check = DB::table('users')
            ->where('email', '=', $request->email)
            ->where('password_hash', '=', $request->password_hash)
            ->first();

        if (is_null($check)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Login failed',
                'data' => null
            ], 401);
        } else {
            $user = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.role_id')
                ->where('email', $request->email)
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Login success',
                'data' => $user
            ], 200);
        }
    }
}
