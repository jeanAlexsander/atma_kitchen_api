<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;

class GeneralInformationController extends Controller
{
    public function readProduct()
    {
        $data = DB::table('products')->where("custodian_id", null)->get();
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function readCustodianProduct()
    {
        $data = DB::table('products')
            ->whereNotNull('products.custodian_id')
            ->join('custodians', 'products.custodian_id', '=', 'custodians.custodian_id')
            ->get();


        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function readTodayStock()
    {
        $data = DB::table('stock_today')
            ->whereDate('date', Carbon::today()->toDateString())
            ->get();

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function preOrder(Request $request)
    {
        $arrayData = $request->data;
        foreach ($arrayData as $item) {
            $data = DB::table('order_not_confirm')->insert([
                'user_id' => $item['user_id'],
                'product_id' => $item['product_id'],
                'amount' => $item['amount'],
                'status' => 0,
                'delivery_option' => $item['delivery_option'],
                'order_date' => Carbon::now()
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function readPreOrder()
    {
        $data = DB::table('order_not_confirm')
            ->join('products', 'order_not_confirm.product_id', '=', 'products.product_id')
            ->join('users', 'order_not_confirm.user_id', '=', 'users.user_id')
            ->where('order_not_confirm.status', 0)
            ->get();

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function confirmOrder(Request $request)
    {
        $arrayData = $request->data;
        $total_income = 0;
        $user_id = 0;
        $date = '';
        foreach ($arrayData as $item) {
            DB::table('order_not_confirm')->where('order_not_confirm_id', $item['order_not_confirm_id'])->update([
                'status' => 1
            ]);
            $total_income = $total_income + $item['amount'];
            $user_id = $item['user_id'];
            $date = $item['order_date'];
        }

        DB::table('orders')->insert([
            'user_id' => $user_id,
            'total' => $total_income,
            'order_date' => $date,
            'status_order' => "konfirmasi pesanan oleh admin",
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            "user_id" => $user_id,
            "total_income" => $total_income
        ], 200);
    }

    public function test(Request $request)
    {
        DB::table('orders')->insert([
            'user_id' => $request->user_id,
            'total' => $request->total_price,
            'order_date' => Carbon::now(),
            'status_order' => "konfirmasi pesanan oleh admin",
        ]);
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
        ], 200);
    }

    public function countPoint(Request $request)
    {
        $total_price = $request->total_price;
        $kondisi = 0;
        $total_point = 0;

        while ($kondisi == 0) {
            if ($total_price >= 1000000) {
                $total_point = $total_point + 200;
                $total_price = $total_price - 1000000;
            }
            if ($total_price >= 500000) {
                $total_point = $total_point + 75;
                $total_price = $total_price - 500000;
            }
            if ($total_price >= 100000) {
                $total_point = $total_point + 15;
                $total_price = $total_price - 100000;
            }
            if ($total_price >= 10000) {
                $total_point = $total_point + 1;
                $total_price = $total_price - 10000;
            }
            if ($total_price < 10000) {
                $kondisi = 1;
            }
        }
        return response()->json([
            'data' => $total_point
        ]);
    }

    public function addOnDelivery(Request $request)
    {

        $total_income = 0;

        if ($request->distance > 15) {
            $total_income = $total_income + 25000;
        } elseif ($request->distance > 10 && $request->distance <= 15) {
            $total_income = $total_income + 20000;
        } elseif ($request->distance > 5 && $request->distance <= 10) {
            $total_income = $total_income + 15000;
        } else {
            $total_income = $total_income + 10000;
        }



        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $total_income
        ], 200);
    }


    public function missingIngredients($id)
    {
        $data = DB::table('recipe_ingredients')->join('ingredients', 'recipe_ingredients.ingredient_id', '=', 'ingredients.ingredient_id')
            ->where('recipe_ingredients.product_id', $id)
            ->get();

        $missing = array();

        foreach ($data as $item) {
            if ($item->total_use > $item->amount) {
                $missing[] = $item;
            }
        }


        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $missing
        ], 200);
    }

    public function declineOrder(Request $request)
    {
        $data = DB::table('order_not_confirm')
            ->where('order_not_confirm.order_id', $request->order_id)
            ->update([
                'status' => 2
            ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $data
        ], 200);
    }


    public function notConfirmOrder()
    {
        $twoDaysLater = now()->addDays(2)->toDateString();

        $data = DB::table('order_not_confirm')
            ->join('products', 'order_not_confirm.product_id', '=', 'products.product_id')
            ->join('users', 'order_not_confirm.user_id', '=', 'users.user_id')
            ->where('order_not_confirm.status', 0)
            ->whereDate('order_not_confirm.order_date', '>=', now()->toDateString()) // Data dari hari ini
            ->whereDate('order_not_confirm.order_date', '<=', $twoDaysLater) // Hingga dua hari ke depan
            ->get();


        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $data
        ], 200);
    }


    public function getConfirmOrder()
    {
        $twoDaysLater = Carbon::now()->addDays(2)->toDateString();

        $data = DB::table('order_not_confirm')
            ->join('products', 'order_not_confirm.product_id', '=', 'products.product_id')
            ->join('users', 'order_not_confirm.user_id', '=', 'users.user_id')
            ->where('order_not_confirm.status', 1)
            ->where('order_not_confirm.delivery_option', 'atma bakery')
            ->whereDate('order_not_confirm.order_date', '>=', Carbon::now()->toDateString())
            ->whereDate('order_not_confirm.order_date', '<=', $twoDaysLater)
            ->get();

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function getOrdersDetail($date, $user_id)
    {
        $data = DB::table('orders')
            ->where('user_id', $user_id)
            ->where('order_date', $date)
            ->first();
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function confirmOrderDistance(Request $request)
    {

        $orderDate = Carbon::parse($request->data['data']['order_date'])->format('Y-m-d H:i:s');

        $detailOrder = DB::table('orders')
            ->where('user_id', $request->data['data']['user_id'])
            ->where('order_date', $orderDate)
            ->get();


        $feeAndOrderId = $request->data['delivery_fee'];
        $data = DB::table("delivery")->insert([
            "order_id" => $detailOrder[0]->order_id,
            "delivery_fee" => $feeAndOrderId,
            "delivery_status" => "konfirmasi oleh admin",
        ]);
        DB::table('orders')
            ->where('user_id', $request->data['data']['user_id'])
            ->where('order_date', $orderDate)
            ->update([
                'status_order' => "konfirmasi jarak"
            ]);

        DB::table('order_not_confirm')
            ->where('user_id', $request->data['data']['user_id'])
            ->where('order_date', $request->data['data']['order_date'])
            ->update([
                'status' => 2
            ]);

        if ($data) {
            return response()->json([
                'status' => 'ok',
                'message' => 'success',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'failed',
                'data' => $data
            ], 200);
        }
    }

    public function getPaymentUser(Request $request)
    {
        $twoDaysLater = Carbon::now()->addDays(2)->toDateString();
        $data = DB::table('orders')
            ->join('delivery', 'orders.order_id', '=', 'delivery.order_id')
            ->where('user_id', $request->user_id)
            ->where('status_order', 'konfirmasi jarak')
            ->whereDate('order_date', '>=', Carbon::now()->toDateString())
            ->whereDate('order_date', '<=', $twoDaysLater)
            ->get();

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function getDetailOrder(Request $request)
    {
        $orderDate = Carbon::parse($request->order_date)->format('Y-m-d H:i:s');
        $data = DB::table('order_not_confirm')
            ->join('products', 'order_not_confirm.product_id', '=', 'products.product_id')
            ->where('user_id', $request->user_id)
            ->where('order_date', $orderDate)
            ->get();

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function customerConfirmPayment(Request $request)
    {
        $data = $request->all();


        if ($request->hasFile('image')) {
            $request->file('image')->move('images/', $request->file('image')->getClientOriginalName());
            $data['image'] = $request->file('image')->getClientOriginalName();
        }

        $data['image'] =  $this->getImageUrl($data['image']);


        DB::table('payments')->insert([
            'order_id' => $data['order_id'],
            'transfer_proof' => $data['image'],
            'status' => 1,
            'amount' => $data['amount'],
            'payment_date' => Carbon::now()
        ]);

        DB::table('orders')
            ->where('order_id', $data['order_id'])
            ->update([
                'status_order' => "konfirmasi pembayaran oleh customer"
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
    public function getImageUrl($gambarName)
    {
        $directory = public_path('images');

        $filePath = $directory . '/' . $gambarName;
        if (file_exists($filePath)) {
            $imageUrl = asset('images/' . $gambarName);
            return $imageUrl;
        } else {
            return null;
        }
    }

    public function orderMOConfirmation()
    {
        $twoDaysLater = Carbon::now()->addDays(2)->toDateString();

        $data = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.user_id')
            // ->where('status_order', 'konfirmasi pembayaran oleh customer')
            ->whereDate('order_date', '>=', Carbon::now()->toDateString())
            ->whereDate('order_date', '<=', $twoDaysLater)
            ->get();

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function countMissingIngredient(Request $request)
    {
        $missingIngredient = [];
        $iterasi = 0;
        $ingredientId = 0;
        $missingingredientId = 0;

        foreach ($request->data as $item) {
            if ($item['product_id'] == 2) {
                $item['product_id'] = 1;
            } else if ($item['product_id'] == 4) {
                $item['product_id'] = 3;
            } else if ($item['product_id'] == 6) {
                $item['product_id'] = 35;
            } else if ($item['product_id'] == 8) {
                $item['product_id'] = 36;
            } else if ($item['product_id'] == 10) {
                $item['product_id'] = 37;
            }
            $products = DB::table('recipe_ingredients')->where('product_id', $item['product_id'])->get();

            foreach ($products as $product) {
                $iterasi++;

                $ingredient = DB::table('ingredients')->where('ingredient_id', $product->ingredient_id)->first();

                if ($product->total_use > $ingredient->amount) {
                    $missing = $product->total_use - $ingredient->amount;
                    $found = false;

                    foreach ($missingIngredient as $key => $missingItem) {
                        if ($missingItem['ingredient_id'] == $product->ingredient_id) {
                            $missingIngredient[$key]['quantity'] += $product->total_use;
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        $missingIngredient[] = [
                            'ingredient_id' => $product->ingredient_id,
                            'ingredient_name' => $ingredient->name,
                            'quantity' => $missing,
                            'unit' => $ingredient->unit
                        ];
                    }
                }
            }
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $missingIngredient,
            'iterasi' => $iterasi,
            'ingredient_id' => $ingredientId,
            'missingIngredientId' => $missingingredientId
        ], 200);
    }

    public function moConfirmOrder(Request $request)
    {
        DB::table('orders')->where('order_id', $request->order_id)->update([
            'status_order' => 'pesanan diproses'
        ]);
        $total_price = $request->total;
        $kondisi = 0;
        $total_point = 0;

        while ($kondisi == 0) {
            if ($total_price >= 1000000) {
                $total_point = $total_point + 200;
                $total_price = $total_price - 1000000;
            }
            if ($total_price >= 500000) {
                $total_point = $total_point + 75;
                $total_price = $total_price - 500000;
            }
            if ($total_price >= 100000) {
                $total_point = $total_point + 15;
                $total_price = $total_price - 100000;
            }
            if ($total_price >= 10000) {
                $total_point = $total_point + 1;
                $total_price = $total_price - 10000;
            }
            if ($total_price < 10000) {
                $kondisi = 1;
            }
        }
        $user = DB::table('users')->where('user_id', $request->user_id)->first();

        // Pastikan pengguna ditemukan sebelum melanjutkan
        if ($user) {
            $saldo = $user->total_point;

            // Tambahkan jumlah total dari permintaan ke saldo saat ini
            $saldo += $total_point;

            // Perbarui saldo pengguna di tabel 'users'
            DB::table('users')->where('user_id', $request->user_id)->update([
                'total_point' => $saldo
            ]);

            // Berikan respons sesuai keberhasilan
            return response()->json([
                'status' => 'ok',
                'message' => 'Success',
                'data' => $request->order_id
            ], 200);
        } else {
            // Jika pengguna tidak ditemukan, kirim respons yang sesuai
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
    }

    public function moRejectOrder(Request $request)
    {
        // Perbarui status pesanan menjadi 'pesanan ditolak' di tabel 'orders'
        DB::table('orders')->where('order_id', $request->order_id)->update([
            'status_order' => 'pesanan ditolak'
        ]);

        // Dapatkan saldo saat ini dari pengguna
        $user = DB::table('users')->where('user_id', $request->user_id)->first();

        // Pastikan pengguna ditemukan sebelum melanjutkan
        if ($user) {
            $saldo = $user->saldo;

            // Tambahkan jumlah total dari permintaan ke saldo saat ini
            $saldo += $request->total;

            // Perbarui saldo pengguna di tabel 'users'
            DB::table('users')->where('user_id', $request->user_id)->update([
                'saldo' => $saldo
            ]);

            // Berikan respons sesuai keberhasilan
            return response()->json([
                'status' => 'ok',
                'message' => 'Success',
                'data' => $request->order_id
            ], 200);
        } else {
            // Jika pengguna tidak ditemukan, kirim respons yang sesuai
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
    }
}
