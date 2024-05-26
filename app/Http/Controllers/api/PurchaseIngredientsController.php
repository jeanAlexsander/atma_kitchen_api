<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class PurchaseIngredientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->all();
        DB::table('ingredients')->insert([
            'name' => $data['name'],
            'unit' => $data['unit'],
            'amount' => $data['amount'],
            'price_per_unit' => $data['price_per_unit'],
            'total' => $data['total'],
        ]);
        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been added',
                'data' => $data
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
        $data = DB::table('ingredients')->get();
        $dataDes = [];

        foreach ($data as $key => $value) {
            array_unshift($dataDes, [
                'ingredient_id' => $value->ingredient_id,
                'name' => $value->name,
                'unit' => $value->unit,
                'amount' => $value->amount,
                'price_per_unit' => $value->price_per_unit
            ]);
        }



        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been retrieved',
                'data' => $dataDes
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
        $data = DB::table('ingredients')->where('ingredient_id', $id)->get();
        $dataUpdate = DB::table('ingredients')->where('ingredient_id', $id)->update([
            'name' => $request->name,
            'unit' => $request->unit,
            'amount' => $request->amount,
            'price_per_unit' => $request->price_per_unit,
            'total' => $request->total,
        ]);
        $data = DB::table('ingredients')->where('ingredient_id', $id)->get();
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
        $data = DB::table('ingredients')->where('ingredient_id', $id)->get();
        if ($data) {
            DB::table('ingredients')->where('ingredient_id', $id)->delete();
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
        $data = DB::table('ingredients')->where('ingredient_id', $id)->get();
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

    public function addPurchaseIngrediets(Request $request)
    {
        $data = DB::table('purchase_ingredients')->insert([
            'ingredient_id' => $request->ingredient_id,
            'total_price' => $request->total_price,
            'total_buy' => $request->total_buy,
            'time_buy' => now()
        ]);

        return response()->json([
            'status' => "success",
            'messege' => "good",
            "data" => $data
        ], 200);
    }

    public function readPurchaseData()
    {
        $data = DB::table('purchase_ingredients')
            ->join('ingredients', 'purchase_ingredients.ingredient_id', '=', 'ingredients.ingredient_id')
            ->whereDate('time_buy', '=', date('Y-m-d'))
            ->get();

        $dataDes = [];

        foreach ($data as $key => $value) {
            array_unshift($dataDes, [
                'purchase_id' => $value->purchase_id,
                'ingredient_id' => $value->ingredient_id,
                'name' => $value->name,
                'unit' => $value->unit,
                'amount' => $value->amount,
                'price_per_unit' => $value->price_per_unit,
                'total_price' => $value->total_price,
                'time_buy' => $value->time_buy,
                'total_buy' => $value->total_buy
            ]);
        }

        return response()->json(
            [
                "status" => "ok",
                "message" => "greed",
                "data" => $dataDes
            ],
            200
        );
    }
    public function updatePurchaseData(Request $request, $id)
    {
        $data = DB::table('purchase_ingredients')->where('purchase_id', $id)->update([
            'total_buy' => $request->total_buy,
            'total_price' => $request->total_price,
            'time_buy' => now()
        ]);

        return response()->json(
            [
                'status' => 'ok',
                'message' => 'mantap',
                'data' => $data
            ],
            200
        );
    }

    public function deletePurchaseIngredient($id)
    {
        $data = DB::table('purchase_ingredients')->where('purchase_id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'ok',
            'data' => $data
        ], 200);
    }
}
