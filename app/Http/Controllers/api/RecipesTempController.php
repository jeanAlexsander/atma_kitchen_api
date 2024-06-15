<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecipesTempController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->all();
        $result = DB::table('recipes_temp')->insert(['product_name' => $data['product_name'], 'deskripsi' => $data['deskripsi']]);

        if ($result) {
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
        $cleanData = [];
        $data = DB::table('products')->select('product_id', 'name')->get();

        foreach ($data as $value) {
            if ($value->product_id != 2 && $value->product_id != 4 && $value->product_id != 6 && $value->product_id != 8 && $value->product_id != 10) {
                $cleanData[] = [
                    'product_id' => $value->product_id,
                    'name' => $value->name,
                    'deskripsi' => ''
                ];
            }
        }

        foreach ($cleanData as $key => $value) {
            $deskripsi = DB::table('recipe_ingredients')
                ->join('ingredients', 'recipe_ingredients.ingredient_id', '=', 'ingredients.ingredient_id')
                ->where('recipe_ingredients.product_id', $value['product_id']) // Add condition to match product_id
                ->select('ingredients.name', 'recipe_ingredients.total_use', 'ingredients.unit')
                ->get();

            $tempDeskripsi = '';
            foreach ($deskripsi as $desk) {
                $tempDeskripsi .= $desk->name . ' ' . $desk->total_use . ' ' . $desk->unit . ', ';
            }

            $tempDeskripsi = rtrim($tempDeskripsi, ', ');

            $cleanData[$key]['deskripsi'] = $tempDeskripsi;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data has been retrieved',
            'data' => $cleanData
        ]);
    }


    public function update(Request $request, $id)
    {
        $data = DB::table('recipes_temp')
            ->where('recipe_id', $id)
            ->update(['deskripsi' => $request->deskripsi]);

        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been updated',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update data'
            ]);
        }
    }


    public function delete($id)
    {
        $data = DB::table('recipes_temp')->where('recipe_id', $id)->get();
        if ($data) {
            DB::table('recipes_temp')->where('recipe_id', $id)->delete();
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
        $data = DB::table('recipes_temp')->where('recipe_id', $id)->get();
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
