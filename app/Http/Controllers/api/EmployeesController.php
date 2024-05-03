<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class EmployeesController extends Controller
{
    public function read()
    {
        $data = DB::table('users')
            ->select('user_id', 'first_name', 'last_name', 'email', 'role_name', 'roles.role_id')
            ->join('roles', 'users.role_id', '=', 'roles.role_id')
            ->whereNotIn('roles.role_name', ['customer', 'owner'])
            ->get();


        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $data
        ], 200);
    }

    public function delete($id)
    {
        $data = DB::table('users')
            ->where('user_id', $id)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data deleted successfully',
            'data' => $data
        ], 200);
    }

    public function create(Request $request)
    {

        $data = DB::table('users')
            ->insert([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password_hash' => "12345678",
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data inserted successfully',
            'data' => $data
        ], 200);
    }

    public function getRole()
    {
        $data = DB::table('roles')
            ->select('role_id', 'role_name')
            ->whereNotIn('role_name', ['customer', 'owner'])
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $data
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $data = DB::table('users')
            ->where('user_id', $id)
            ->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'role_id' => $request->role_id,
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully',
            'data' => $data
        ], 200);
    }
}
