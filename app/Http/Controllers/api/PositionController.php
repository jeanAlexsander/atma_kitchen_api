<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PositionController extends Controller
{
    public function read()
{
    $data = DB::table('users')
        ->select('users.user_id', 'users.first_name', 'users.last_name', 'users.email', 'position.position_id', 'position.position_name')
        ->join('position', 'users.position_id', '=', 'position.position_id')
        ->where('users.role_id', '=', 4)
        ->get();
    
    $data2 = DB::table('users')
        ->select('users.user_id', 'users.first_name', 'users.last_name', 'users.email')
        ->where('users.role_id', '=', 4)
        ->get();

    $mergedData = $data->concat($data2)->unique('user_id');

    return response()->json([
        'status' => 'success',
        'message' => 'Data retrieved successfully',
        'data' => $mergedData,
    ], 200);
}

    public function getPosition(){
        $data = DB::table('position')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function delete($id)
    {
        $data = DB::table('users')
            ->where('user_id', '=', $id)
            ->update(['position_id' => null]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data deleted successfully',
            'data' => $data
        ], 200);
    }

    public function create(Request $request, $id)
    {

        $data = DB::table('users')
            ->where('user_id', '=', $id)
            ->update(['position_id' => $request->position_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data inserted successfully',
            'data' => $data
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $data = DB::table('users')
            ->where('user_id', '=', $id)
            ->update(['position_id' => $request->position_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully',
            'data' => $data
        ], 200);
    }
}
