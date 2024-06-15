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

    public function addDataRecipe(Request $request){
        $product_name = $request->product_name;
        $ingredients = $request->ingredients;
        DB::table('products')->insert([
            'name' => $product_name,
            'price'=> 0,
        ]);
        $product_id = DB::table('products')->where('name', $product_name)->first()->product_id;

        foreach ($ingredients as $ingredient) {
            DB::table('recipe_ingredients')->insert([
                'product_id' => $product_id,
                'ingredient_id' => $ingredient['ingredient_id'],
                'total_use' => $ingredient['total_use']
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Data has been added',
            'data' => $request->all()
        ]);
    }

    public function deleteDataRecipe($id){
        DB::table('products')->where('product_id', $id)->delete();
        DB::table('recipe_ingredients')->where('product_id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data has been deleted',
            'data' => $id
        ]);
    }

    function getDataRecipesForUpdate($id){
        $data = DB::table('products')->where('product_id', $id)->first();
        $ingredients = DB::table('recipe_ingredients')
            ->join('ingredients', 'recipe_ingredients.ingredient_id', '=', 'ingredients.ingredient_id')
            ->where('recipe_ingredients.product_id', $id)
            ->select('recipe_ingredients.ingredient_id', 'ingredients.name', 'recipe_ingredients.total_use')
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data has been retrieved',
            'data' => [
                'product_name' => $data->name,
                'ingredients' => $ingredients
            ]
        ]);
    }

    public function updateRecipesNew(Request $request){
        $product_name = $request->product_name;
        $ingredients = $request->ingredients;
        $product_id = DB::table('products')->where('name', $product_name)->first()->product_id;
        DB::table('products')->where('product_id', $product_id)->update([
            'name' => $product_name,
            'price'=> 0,
        ]);
        DB::table('recipe_ingredients')->where('product_id', $product_id)->delete();
        foreach ($ingredients as $ingredient) {
            DB::table('recipe_ingredients')->insert([
                'product_id' => $product_id,
                'ingredient_id' => $ingredient['ingredient_id'],
                'total_use' => $ingredient['total_use']
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Data has been updated',
            'data' => $request->all()
        ]);
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
