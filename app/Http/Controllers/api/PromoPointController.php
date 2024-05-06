<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoPointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $data = DB::table('users')->where('user_id', '=', $id)->first();

        $temp = $request->all()["total_point"];
        $temp2 = (int) $data->total_point;

        $dataTotalPoint = $temp2 + $temp;
        $dataUpdate = DB::table('users')->where('user_id', $id)->update(['total_point' => $dataTotalPoint]);
        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been added',
                'data' => $dataUpdate
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Data cannot be added',
                'data' => $data
            ]);
        }
    }

    public function read()
    {
        $data = DB::table('users')->where('role_id', '=', 5)->get();
        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been retrieved',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Data cannot be retrieved',
                'data' => $data
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $data = DB::table('users')->where('user_id', $id)->get();
        $dataUpdate = DB::table('users')->where('user_id', $id)->update([
            'total_point' => $request->total_point,
        ]);
        $data = DB::table('users')->where('user_id', $id)->get();
        if ($dataUpdate) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been updated',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Data cannot be updated',
                'data' => $data
            ]);
        }
    }

    public function delete($id)
    {
        $data = DB::table('users')->where('user_id', $id)->update(['total_point' => 0]);
        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been deleted',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Data cannot be deleted',
                'data' => $data
            ]);
        }
    }

    public function search($id)
    {
        $data = DB::table('users')->where('user_id', $id)->get();
        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been retrieved',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Data cannot be retrieved',
                'data' => $data
            ]);
        }
    }
}
