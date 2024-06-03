<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BahanController extends Controller
{
    public function getBahan(Request $request)
    {
        $dataClean = [];
        $dataOrder = DB::table('orders')->where('order_id', $request->order_id)->first();

        $orderNotConfirm = DB::table('order_not_confirm')
            ->join('products', 'order_not_confirm.product_id', '=', 'products.product_id')
            ->where('user_id', $dataOrder->user_id)
            ->where('order_date', $dataOrder->order_date)
            ->get();

        foreach ($orderNotConfirm as $item) {
            $quantity = $item->amount / $item->price;
            if ($item->product_id === 2) {
                $item->product_id = 1;
            } else if ($item->product_id === 4) {
                $item->product_id = 3;
            } else if ($item->product_id === 6) {
                $item->product_id = 35;
            } else if ($item->product_id === 8) {
                $item->product_id = 36;
            } else if ($item->product_id === 10) {
                $item->product_id = 37;
            }
            $recipeIngredientDetail = DB::table('recipe_ingredients')
                ->join('ingredients', 'recipe_ingredients.ingredient_id', '=', 'ingredients.ingredient_id')
                ->where('product_id', $item->product_id)
                ->get();
            foreach ($recipeIngredientDetail as $itemDetail) {
                $kondisi = false;
                foreach ($dataClean as $key => $itemClean) {
                    if ($itemDetail->ingredient_id == $itemClean['ingredient_id']) {
                        $dataClean[$key]['quantity'] += $itemDetail->total_use * $quantity;
                        $kondisi = true;
                    }
                }
                if ($kondisi == false) {
                    $dataClean[] = [
                        'ingredient_id' => $itemDetail->ingredient_id,
                        'ingredient_name' => $itemDetail->name,
                        'quantity' => $itemDetail->total_use * $quantity
                    ];
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $dataClean
        ]);
    }

    public function getOrderTodays()
    {
        $date = Carbon::now()->format('Y-m-d');
        $data = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.user_id')
            ->whereDate('order_date', $date)
            ->where('status_order', 'pesanan diproses')
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'success',
            'data' => $data
        ]);
    }
}
