<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeSalaryController extends Controller
{
    public function read(Request $request)
    {
        $cleanData = [];
        $monthCheck = DB::table('attendances')
            ->join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
            ->select(
                'employees.employee_id',
                DB::raw('CONCAT(employees.first_name, " ", employees.last_name) as employee_name')
            )
            ->whereMonth('attendances.attendance_time', $request->month)
            ->groupBy('employees.employee_id', 'employee_name')
            ->get();

        foreach ($monthCheck as $value) {
            $cleanData[$value->employee_id] = [
                'employee_id' => $value->employee_id,
                'employee_name' => $value->employee_name,
                'daily_bonus' => 0,
                'absent' => 0,
                'attend' => 0,
                'salary' => 0
            ];
        }

        foreach ($monthCheck as $value) {
            $attendCount = DB::table('attendances')
                ->where('employee_id', $value->employee_id)
                ->whereMonth('attendance_time', $request->month)
                ->where('status', 1)
                ->count();

            $absentCount = DB::table('attendances')
                ->where('employee_id', $value->employee_id)
                ->whereMonth('attendance_time', $request->month)
                ->where('status', 0)
                ->count();

            $cleanData[$value->employee_id]['attend'] = $attendCount;
            $cleanData[$value->employee_id]['absent'] = $absentCount;
        }

        foreach ($cleanData as $value) {
            $salary = DB::table('salary')
                ->where('employee_id', $value['employee_id'])
                ->first();
            $cleanData[$value['employee_id']]['salary'] = $salary->salary_amount;
            $cleanData[$value['employee_id']]['daily_bonus'] = $salary->bonus;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data has been retrieved',
            'data' => array_values($cleanData)
        ]);
    }
}
