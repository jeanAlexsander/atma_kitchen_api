<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarikSaldoController extends Controller
{
    public function tarikSaldo(Request $request)
    {
        $data = DB::table('users')->where('user_id', $request->user_id)->first();
        return response()->json([
            'message' => 'success',
            'data' => $data
        ]);
    }

    public function getHistorySaldo(Request $request)
    {
        $data = DB::table('balance_withdrawals')->where('user_id', $request->user_id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $data
        ]);
    }

    public function getPesananDikirim(Request $request)
    {
        $data = DB::table('orders')->where('user_id', $request->user_id)->where('status_order', 'sedang dikirim')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $data
        ]);
    }

    public function getDetailPesananDikirim(Request $request)
    {
        $data = DB::table('order_not_confirm')
            ->where('user_id', $request->user_id)
            ->where('order_date', $request->order_date)
            ->join('products', 'order_not_confirm.product_id', '=', 'products.product_id')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $data
        ]);
    }

    public function updateSelesaiPesan(Request $request)
    {
        DB::table('orders')->where('order_id', $request->order_id)->update([
            'status_order' => 'selesai'
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'success'
        ]);
    }
}
