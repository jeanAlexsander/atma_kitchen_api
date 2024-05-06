<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function read()
    {
        //ammbil data hari ini
        $data = DB::table('attendances')
            ->select('employees.employee_id', 'employees.first_name', 'employees.last_name', 'attendance_time', 'status', 'attendance_id')
            ->join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
            ->whereDate('attendance_time', '=', now())
            ->get();
        if ($data->isEmpty()) {
            $data = DB::table('employees')->select('first_name', 'last_name', 'employee_id')->get();
            for ($i = 0; $i < count($data); $i++) {
                DB::table('attendances')->insert([
                    'employee_id' => $data[$i]->employee_id,
                    'attendance_time' => null,
                    'attendance_time' => now(),
                    'status' => 0
                ]);
            }
            $data = DB::table('attendances')
                ->select('employees.employee_id', 'employees.first_name', 'employees.last_name', 'attendance_time', 'status', 'attendance_id')
                ->join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
                ->whereDate('attendance_time', '=', now())
                ->get();
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $data
        ], 200);
    }

    public function setAbsent($id)
    {
        DB::table('attendances')->where('attendance_id', $id)->update([
            'attendance_time' => now(),
            'status' => 1
        ]);

        $result = DB::table('attendances')
            ->select('employees.employee_id', 'employees.first_name', 'employees.last_name', 'attendance_time', 'status', 'attendance_id')
            ->join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
            ->whereDate('attendance_time', '=', now())
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully',
            'data' => $result
        ], 200);
    }
}
