<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecipeIngredientsController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->all();
        DB::table('recipe_ingredients')->insert([
            'recipe_id' => $data['recipe_id'],
            'ingredient_id' => $data['ingredient_id'],
            'total_use' => $data['total_use'],
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
        $data = DB::table('recipe_ingredients')->get();
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
        $data = DB::table('recipe_ingredients')->where('recipe_id', $id)->get();
        $dataUpdate = DB::table('recipe_ingredients')->where('recipe_id', $id)->update([
            'amount' => $request->amount,
        ]);
        $data = DB::table('recipe_ingredients')->where('recipe_id', $id)->get();
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
        $data = DB::table('recipe_ingredients')->where('recipe_id', $id)->get();
        if ($data) {
            DB::table('recipe_ingredients')->where('recipe_id', $id)->delete();
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
        $data = DB::table('recipe_ingredients')->where('recipe_id', $id)->get();
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
