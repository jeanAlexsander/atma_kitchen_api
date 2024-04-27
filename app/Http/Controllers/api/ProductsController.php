<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->all();
        DB::table('products')->insert([
            'product_id' => $data['product_id'],
            'custodian_id' => $data['custodian_id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'image' => $data['image'],
            'category_id' => $data['category_id'],
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

    public function createCustodians(Request $request)
    {
        $data = $request->all();
        DB::table('products')->insert([
            'product_id' => $data['product_id'],
            'custodian_id' => $data['custodian_id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'image' => $data['image'],
            'category_id' => $data['category_id'],
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
        $data = DB::table('products')->get();
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
        $data = DB::table('products')->where('product_id', $id)->get();
        $dataUpdate = DB::table('products')->where('product_id', $id)->update([
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $request->image,
            'category_id' => $request->category_id,
        ]);
        $data = DB::table('products')->where('product_id', $id)->get();
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
        $data = DB::table('products')->where('product_id', $id)->get();
        if ($data) {
            DB::table('products')->where('product_id', $id)->delete();
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
        $data = DB::table('products')->where('product_id', $id)->get();
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
