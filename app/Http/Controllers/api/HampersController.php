<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HampersController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->all();


        if ($request->hasFile('image')) {
            $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
            $data['image'] = $request->file('image')->getClientOriginalName();
        }

        $data['image'] = $this->getImage($data['image']);


        DB::table('hampers')->insert([
            'name' => $data['name'],
            'image' => $data['image'],
            'hampers_status' => $data['hampers_status'],
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

    public function getImage($gambarName)
    {
        // Ambil jalur direktori gambar
        $directory = public_path('images');

        // Cek apakah file yang diminta ada dalam direktori
        $filePath = $directory . '/' . $gambarName;
        if (file_exists($filePath)) {
            // Jika file ada, kembalikan URL-nya
            $imageUrl = asset('images/' . $gambarName);
            return $imageUrl;
        } else {
            // Jika file tidak ditemukan, kembalikan pesan error atau null
            return null; // atau throw new \Exception("File not found");
        }
    }




    public function read()
    {
        $data = DB::table('hampers')->get();
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
        $data = DB::table('hampers')->where('hampers_id', $id)->get();

        $temp = $request->all();


        if ($request->hasFile('image')) {
            $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
            $temp['image'] = $request->file('image')->getClientOriginalName();
        }

        $temp['image'] = $this->getImage($temp['image']);


        $dataUpdate = DB::table('hampers')->where('hampers_id', $id)->update([
            'name' => $temp['name'],
            'image' => $temp['image'],
            'hampers_status' => $temp['hampers_status'],
        ]);

        $data = DB::table('hampers')->where('hampers_id', $id)->get();
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
        $data = DB::table('hampers')->where('hampers_id', $id)->get();
        if ($data) {
            DB::table('hampers')->where('hampers_id', $id)->delete();
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
        $data = DB::table('hampers')->where('hampers_id', $id)->get();
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
