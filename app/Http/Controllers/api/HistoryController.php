<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function read($id)
    {
        $data = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.customer_id')
            ->where('orders.customer_id', $id)
            ->get();

        $orderid = [];
        foreach ($data as $key => $value) {
            $orderid[] = $value->order_id;
        }

        $dataOrder = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.product_id')
            ->whereIn('order_id', $orderid)
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data successfully retrieved',
            'data' => $dataOrder
        ], 200);
    }

    public function getCustomer()
    {
        $data = DB::table('users')->where('role_id', 5)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data successfully retrieved',
            'data' => $data
        ], 200);
    }
}
