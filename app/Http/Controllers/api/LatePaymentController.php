<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LatePaymentController extends Controller
{
    public function read()
    {
        $data = DB::table('orders')->where('status_order', 'konfirmasi pesanan oleh admin')->get();
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

    public function update($id)
    {
        $data = DB::table('orders')
            ->where('order_id', '=', $id)
            ->update(['status_order' => 'pesanan dibatalkan']);

        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully',
            'data' => $data
        ], 200);
    }
}
