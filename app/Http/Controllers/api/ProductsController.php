<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{


    public function getImage($gambarName)
    {
        $directory = public_path('images');
        $filePath = $directory . '/' . $gambarName;
        if (file_exists($filePath)) {
            $imageUrl = asset('images/' . $gambarName);
            return $imageUrl;
        } else {
            return null;
        }
    }


    public function create(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
            $data['image'] = $request->file('image')->getClientOriginalName();
        }

        $data['image'] = $this->getImage($data['image']);

        DB::table('products')->insert([
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

    public function getCategory()
    {
        $data = DB::table('product_categories')->get();
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

        $temp = $request->all();


        if ($request->hasFile('image')) {
            $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
            $temp['image'] = $request->file('image')->getClientOriginalName();
        }

        $temp['image'] = $this->getImage($temp['image']);

        $dataUpdate = DB::table('products')->where('product_id', $id)->update([
            'name' => $temp['name'],
            'price' => $temp['price'],
            'quantity' => $temp['quantity'],
            'image' => $temp['image'],
            'category_id' => $temp['category_id'],
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
