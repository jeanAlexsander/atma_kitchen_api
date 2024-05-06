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


    public function read() {
        $data = DB::table('recipes_temp')->get();
        if($data){
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been Retrieved',
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
        $data = DB::table('recipes_temp')
            ->where('recipe_id', $id)
            ->update(['deskripsi' => $request->deskripsi]);
        
        if($data){
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
