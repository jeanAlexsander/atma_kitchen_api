<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusOrderController extends Controller
{
    public function read()
    {
        $data = DB::table('orders')->where('status_order', 'pesanan diproses')->get();
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
        $data = DB::table('orders')
            ->where('order_id', '=', $id)
            ->update(['status_order' => $request->status_order]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully',
            'data' => $data
        ], 200);
    }
}


