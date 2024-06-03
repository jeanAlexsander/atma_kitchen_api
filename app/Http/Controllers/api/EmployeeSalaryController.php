<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeSalaryController extends Controller
{
    public function read(Request $request)
{
    $cleanData = [];
    $monthSort = [];
    $responseData = [];
    $responseDataAbsent = [];
    $responseDataAttend = [];
    $salaryMonth = [];
    
    $dailyBonus = DB::table('attendances')
        ->join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
        ->select('attendances.employee_id', DB::raw('SUM(daily_bonus) as total'), DB::raw('CONCAT(employees.first_name, " ", employees.last_name) as employee_name'), DB::raw('MONTH(attendances.attendance_time) as month'), DB::raw('YEAR(attendances.attendance_time) as year'))
        ->where('status', 1)
        ->groupBy('attendances.employee_id', 'employees.first_name', 'employees.last_name', 'month', 'year')
        ->get();

    foreach ($dailyBonus as $item){
        if($request->month ==  $item->month && $request->year == $item->year){
            $monthSort[] = $item;
        }
    }
    

    $salary = DB::table('salary')
        ->join('employees', 'salary.employee_id', '=', 'employees.employee_id')
        ->select(
            'salary.employee_id',
            DB::raw('SUM(salary_amount) as total'),
            DB::raw('SUM(bonus) as bonus'),
            DB::raw('MONTH(paid_time) as month'),
            DB::raw('CONCAT(employees.first_name, " ", employees.last_name) as employee_name')
        )
        ->groupBy('salary.employee_id', 'month', 'employees.first_name', 'employees.last_name')
        ->get();

    foreach ($salary as $item){
        if($request->month == $item->month){
            $salaryMonth[] = $item;
        }
    }

    foreach ($salaryMonth as $item){
        $kondisi = false;
        foreach ($monthSort as $bonus){
            if ($item->employee_id == $bonus->employee_id ){
                $cleanData[] = [
                    'employee_id' => $item->employee_id,
                    'employee_name' => $item->employee_name,
                    'salary' => $item->total,
                    'bonus' => $item->bonus,
                    'daily_bonus' => $bonus->total,
                    'month' => $item->month
                ];
                $kondisi = true;
            }
        }
        if($kondisi == false){
            $cleanData[] = [
                'employee_id' => $item->employee_id,
                'employee_name' => $item->employee_name,
                'salary' => $item->total,
                'bonus' => $item->bonus,
                'daily_bonus' => 0,
                'month' => $item->month
            ];
        }
    }

    foreach ($cleanData as $item){
        if($request->month == $item['month']){
            $responseData[] = $item;
        }
    }

    $absent = DB::table('attendances')
        ->select('employee_id', DB::raw('COUNT(*) as total'), DB::raw('MONTH(attendance_time) as month'))
        ->where('status', 0)
        ->whereMonth('attendance_time', $request->month)
        ->groupBy('employee_id', 'month')
        ->get();
    
    $attend = DB::table('attendances')
        ->select('employee_id', DB::raw('COUNT(*) as total'), DB::raw('MONTH(attendance_time) as month'))
        ->where('status', 1)
        ->whereMonth('attendance_time', $request->month)
        ->groupBy('employee_id', 'month')
        ->get();
    
    foreach ($responseData as $item){
        $kondisi = false;
        foreach ($absent as $abs){
            if ($item['employee_id'] == $abs->employee_id){
                $responseDataAbsent[] = [
                    'employee_id' => $item['employee_id'],
                    'employee_name' => $item['employee_name'],
                    'salary' => $item['salary'],
                    'daily_bonus' => $item['daily_bonus'],
                    'month' => $item['month'],
                    'absent' => $abs->total
                ];
                $kondisi = true;
            }
        }
        if($kondisi == false){
            $responseDataAbsent[] = [
                'employee_id' => $item['employee_id'],
                'employee_name' => $item['employee_name'],
                'salary' => $item['salary'],
                'daily_bonus' => $item['daily_bonus'],
                'month' => $item['month'],
                'absent' => 0
            ];
        }
        
    }

    foreach ($responseDataAbsent as $item){
        $kondisi = false;
        foreach ($attend as $att){
            if ($item['employee_id'] == $att->employee_id){
                $responseDataAttend[] = [
                    'employee_id' => $item['employee_id'],
                    'employee_name' => $item['employee_name'],
                    'salary' => $item['salary'],
                    'daily_bonus' => $item['daily_bonus'],
                    'month' => $item['month'],
                    'absent' => $item['absent'],
                    'attend' => $att->total
                ];
                $kondisi = true;
            }
        }
        if($kondisi == false){
            $responseDataAttend[] = [
                'employee_id' => $item['employee_id'],
                'employee_name' => $item['employee_name'],
                'salary' => $item['salary'],
                'daily_bonus' => $item['daily_bonus'],
                'month' => $item['month'],
                'absent' => $item['absent'],
                'attend' => 0
            ];
        }
        
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Data has been retrieved',
        'data' => $responseDataAttend,
    ]);
    }
}
