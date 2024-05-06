<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{

    public function read() {
        $data = DB::table('salary')->join('employees', 'salary.employee_id' , '=', 'employees.employee_id')->get();
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
        $data = DB::table('salary')
            ->where('salary_id', '=', $id)
            ->update(['salary_amount' => $request->salary_amount, 'bonus' => $request->bonus, 'paid_time' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully',
            'data' => $data
        ], 200);
    }
}


