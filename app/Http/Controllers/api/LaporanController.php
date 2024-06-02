<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function laporanBulanan()
    {

        $data = DB::table('orders')
            ->select(
                DB::raw('YEAR(order_date) as year'),
                DB::raw('MONTH(order_date) as month'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(order_id) as total_order')
            )
            ->groupBy(DB::raw('YEAR(order_date)'), DB::raw('MONTH(order_date)'))
            ->where('status_order', 'pesanan diproses')
            ->get();


        return response()->json([
            'data' => $data
        ]);
    }

    public function laporanProduct(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12'
        ]);

        $orderData = DB::table('orders')
            ->where('status_order', 'pesanan diproses')
            ->get();
        $cleanData = [];

        $products = DB::table('products')->get();

        $dates = $orderData->pluck('order_date')->toArray();
        $laporan = DB::table('order_not_confirm')
            ->select(
                DB::raw('YEAR(order_date) as year'),
                DB::raw('MONTH(order_date) as month'),
                'order_not_confirm.product_id',
                DB::raw('SUM(amount) as amount'),
                DB::raw('MAX(products.name) as product_name')
            )
            ->join('products', 'products.product_id', '=', 'order_not_confirm.product_id')
            ->whereIn(DB::raw('MONTH(order_date)'), [$request->month])
            ->whereIn('order_date', $dates)
            ->groupBy('order_not_confirm.product_id', 'year', 'month')
            ->get();

        foreach ($laporan as $item) {
            foreach ($products as $product) {
                if ($item->product_id == $product->product_id) {
                    $cleanData[] = [
                        'year' => $item->year,
                        'month' => $item->month,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'amount' => $item->amount,
                        'total_buy' => $item->amount / $product->price,
                        'price' => $product->price
                    ];
                }
            }
        }

        return response()->json([
            'data' => $cleanData
        ]);
    }


    public function laporanIngredient()
    {
        $data = DB::table('ingredients')->get();
        return response()->json([
            'data' => $data
        ]);
    }

    public function laporanIngredientPerPeriodeTertentu(Request $request)
    {
        $orderData = DB::table('orders')->where('status_order', 'pesanan diproses')->get();

        $dates = $orderData->pluck('order_date')->toArray();

        $sortedDate = [];

        foreach ($dates as $key => $date) {
            if ($request->month == Carbon::parse($date)->format('m') && $request->year == Carbon::parse($date)->format('Y')) {
                $sortedDate[] = $date;
            }
        }

        $dataClear = [];

        foreach ($sortedDate as $date) {
            $dataTemp = DB::table('order_not_confirm')
                ->join('products', 'products.product_id', '=', 'order_not_confirm.product_id')
                ->where('order_date', $date)
                ->get();

            foreach ($dataTemp as $data) {

                $quantity = $data->amount / $data->price;
                if ($data->product_id === 2) {
                    $data->product_id = 1;
                } else if ($data->product_id === 4) {
                    $data->product_id = 3;
                } else if ($data->product_id === 6) {
                    $data->product_id = 35;
                } else if ($data->product_id === 8) {
                    $data->product_id = 36;
                } else if ($data->product_id === 10) {
                    $data->product_id = 37;
                }
                $dataRecepies = DB::table('recipe_ingredients')
                    ->join('ingredients', 'ingredients.ingredient_id', '=', 'recipe_ingredients.ingredient_id')
                    ->where('product_id', $data->product_id)
                    ->get();

                foreach ($dataRecepies as $dataRecepie) {
                    $kondisi = false;
                    foreach ($dataClear as $key => $value) {
                        if ($value['ingredient_id'] === $dataRecepie->ingredient_id) {
                            $dataClear[$key]['quantity'] += $dataRecepie->total_use * $quantity;
                            $kondisi = true;
                        }
                    }
                    if ($kondisi === false) {
                        $dataClear[] = [
                            'ingredient_id' => $dataRecepie->ingredient_id,
                            'name' => $dataRecepie->name,
                            'quantity' => $dataRecepie->total_use * $quantity,
                            'unit' => $dataRecepie->unit
                        ];
                    }
                }
            }
        }
        return response()->json([
            'data' => $dataClear
        ]);
    }

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

        foreach ($dailyBonus as $item) {
            if ($request->month ==  $item->month && $request->year == $item->year) {
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

        foreach ($salary as $item) {
            if ($request->month == $item->month) {
                $salaryMonth[] = $item;
            }
        }

        foreach ($salaryMonth as $item) {
            $kondisi = false;
            foreach ($monthSort as $bonus) {
                if ($item->employee_id == $bonus->employee_id) {
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
            if ($kondisi == false) {
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

        foreach ($cleanData as $item) {
            if ($request->month == $item['month']) {
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

        foreach ($responseData as $item) {
            $kondisi = false;
            foreach ($absent as $abs) {
                if ($item['employee_id'] == $abs->employee_id) {
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
            if ($kondisi == false) {
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

        foreach ($responseDataAbsent as $item) {
            $kondisi = false;
            foreach ($attend as $att) {
                if ($item['employee_id'] == $att->employee_id) {
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
            if ($kondisi == false) {
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


    public function pemasukanDanPengeluaranBulanan(Request $request)
    {
        $monthOrder = [];
        $total = 0;
        $total_ingredient = 0;
        $total_gaji = 0;
        $tip = 0;
        $orders = DB::table('orders')
            ->where('status_order', 'pesanan diproses')
            ->get();

        foreach ($orders as $order) {
            if ($request->month == Carbon::parse($order->order_date)->format('m') && $request->year == Carbon::parse($order->order_date)->format('Y')) {
                $monthOrder[] = $order;
            }
        }


        foreach ($monthOrder as $order) {
            $temp = DB::table('payments')
                ->where('order_id', $order->order_id)
                ->first();
            $total += $order->total;
            $tip += $temp->amount - $order->total;
        }



        $data = $this->laporanIngredientPerPeriodeTertentu($request);

        $jsonData = $data->getData();

        $temp = $jsonData->data;

        $ingredient = DB::table('ingredients')->get();

        foreach ($temp as $data) {
            foreach ($ingredient as $ing) {
                if ($data->ingredient_id === $ing->ingredient_id) {
                    $total_ingredient += $data->quantity * $ing->price_per_unit;
                }
            }
        }

        $gaji = $this->read($request);

        $jsonGaji = $gaji->getData();

        $dataGaji  = $jsonGaji->data;

        foreach ($dataGaji as $gaji) {
            $total_gaji += $gaji->salary + $gaji->daily_bonus;
        }

        $other_need = DB::table('other_need')
            ->whereMonth('Date_of_expense', $request->month)
            ->get();

        return response()->json([
            "data" => [
                'total_pemasukan' => $total,
                'total_gaji' => $total_gaji,
                'total_pembelian_bahan' => $total_ingredient,
                'other_need' => $other_need,
                'tip' => $tip
            ]
        ]);
    }

    public function laporanRekapTransaksiPenitip()
    {
    }
}
