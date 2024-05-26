<?php

use App\Http\Controllers\api\EmployeesController;
use App\Http\Controllers\api\PresensiController;
use App\Http\Controllers\api\ChangePasswordController;
use App\Http\Controllers\api\HistoryController;


use App\Http\Controllers\api\PositionController;
use App\Http\Controllers\api\SalaryController;
use App\Http\Controllers\api\OtherNeedController;
use App\Http\Controllers\api\PromoPointController;
use App\Http\Controllers\api\PurchaseIngredientsController;
use App\Http\Controllers\api\PurchaseMaterialsController;
use App\Http\Controllers\api\UsersController;
use App\Http\Controllers\api\GeneralInformationController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('/login', 'App\Http\Controllers\api\LoginController@login');
Route::POST('/change-password', 'App\Http\Controllers\api\ChangePasswordController@index');
Route::post('/action-change-password', 'App\Http\Controllers\api\ChangePasswordController@changePassword');
Route::post('/register', 'App\Http\Controllers\api\LoginController@register');
//Promo Point
Route::post('/add-promoPoint/{id}', [PromoPointController::class, 'create']);
Route::get('/get-promoPoint', [PromoPointController::class, 'read']);
Route::put('/update-promoPoint/{id}', [PromoPointController::class, 'update']);
Route::delete('/delete-promoPoint/{id}', [PromoPointController::class, 'delete']);
//Product
Route::post('/add-product', 'App\Http\Controllers\api\ProductsController@create');
Route::get('/get-product', 'App\Http\Controllers\api\ProductsController@read');
Route::post('/update-product/{id}', 'App\Http\Controllers\api\ProductsController@update');
Route::delete('/delete-product/{id}', 'App\Http\Controllers\api\ProductsController@delete');
Route::post('/search-product/{id}', 'App\Http\Controllers\api\ProductsController@search');
Route::get('/get-categories', 'App\Http\Controllers\api\ProductsController@getCategory');
//Recipes
Route::post('/add-recipes_temp', 'App\Http\Controllers\api\RecipesTempController@create');
Route::get('/get-recipes_temp', 'App\Http\Controllers\api\RecipesTempController@read');
Route::put('/update-recipes_temp/{id}', 'App\Http\Controllers\api\RecipesTempController@update');
Route::delete('/delete-recipes_temp/{id}', 'App\Http\Controllers\api\RecipesTempController@delete');
Route::post('/search-recipes_temp/{id}', 'App\Http\Controllers\api\RecipesTempController@search');
//RecipeIngredients
Route::post('/add-recipeIngredients', 'App\Http\Controllers\api\RecipeIngredientsController@create');
Route::get('/get-recipeIngredients', 'App\Http\Controllers\api\RecipeIngredientsController@read');
Route::post('/update-recipeIngredients/{id}', 'App\Http\Controllers\api\RecipeIngredientsController@update');
Route::delete('/delete-recipeIngredients/{id}', 'App\Http\Controllers\api\RecipeIngredientsController@delete');
Route::post('/search-recipeIngredients/{id}', 'App\Http\Controllers\api\RecipeIngredientsController@search');
//Ingredients
Route::post('/add-ingredients', 'App\Http\Controllers\api\IngredientsController@create');
Route::get('/get-ingredients', 'App\Http\Controllers\api\IngredientsController@read');
Route::post('/update-ingredients/{id}', 'App\Http\Controllers\api\IngredientsController@update');
Route::delete('/delete-ingredients/{id}', 'App\Http\Controllers\api\IngredientsController@delete');
Route::post('/search-ingredients/{id}', 'App\Http\Controllers\api\IngredientsController@search');
//hampers
Route::post('/add-hampers', 'App\Http\Controllers\api\HampersController@create');
Route::get('/get-hampers', 'App\Http\Controllers\api\HampersController@read');
Route::post('/update-hampers/{id}', 'App\Http\Controllers\api\HampersController@update');
Route::delete('/delete-hampers/{id}', 'App\Http\Controllers\api\HampersController@delete');
Route::post('/search-hampers/{id}', 'App\Http\Controllers\api\HampersController@search');
Route::get('/get-image/{gambarName}', 'App\Http\Controllers\api\HampersController@getImage');
//Custodians
Route::post('/add-custodians', 'App\Http\Controllers\api\CustodiansController@create');
Route::get('/get-custodians', 'App\Http\Controllers\api\CustodiansController@read');
Route::post('/update-custodians/{id}', 'App\Http\Controllers\api\CustodiansController@update');
Route::delete('/delete-custodians/{id}', 'App\Http\Controllers\api\CustodiansController@delete');
Route::post('/search-custodians/{id}', 'App\Http\Controllers\api\CustodiansController@search');
//Users
Route::post('/add-users', 'App\Http\Controllers\api\UsersController@create');
Route::get('/get-users', 'App\Http\Controllers\api\UsersController@read');
Route::post('/update-users/{id}', 'App\Http\Controllers\api\UsersController@update');
Route::delete('/delete-users/{id}', 'App\Http\Controllers\api\UsersController@delete');
Route::post('/search-users/{id}', 'App\Http\Controllers\api\UsersController@search');
Route::post('/update-users/{id}', [UsersController::class, 'changeUser']);
//employees
Route::get('/get-employees', [EmployeesController::class, 'read']);
Route::delete('/delete-employee/{id}', [EmployeesController::class, 'delete']);
Route::get('/get-role', [EmployeesController::class, 'getRole']);
Route::post('/add-employee', [EmployeesController::class, 'create']);
Route::put('/update-employee/{id}', [EmployeesController::class, 'update']);
//Other Need
Route::post('/add-otherNeed', [OtherNeedController::class, 'create']);
Route::get('/get-otherNeed', [OtherNeedController::class, 'read']);
Route::delete('/delete-otherNeed/{id}', [OtherNeedController::class, 'delete']);
Route::put('/update-otherNeed/{id}', [OtherNeedController::class, 'update']);
//Purchase Material
Route::post('/add-purchaseIngredients', [PurchaseIngredientsController::class, 'create']);
Route::get('/get-purchaseIngredients', [PurchaseIngredientsController::class, 'read']);
Route::delete('/delete-purchaseIngredients/{id}', [PurchaseIngredientsController::class, 'delete']);
Route::put('/update-purchaseIngredients/{id}', [PurchaseIngredientsController::class, 'update']);
Route::get('/get-purchase-ingredient', [PurchaseIngredientsController::class, 'readPurchaseData']);
Route::post('/add-purchase-ingredient', [PurchaseIngredientsController::class, 'addPurchaseIngrediets']);
Route::put('/update-purchase-ingredient/{id}', [PurchaseIngredientsController::class, 'updatePurchaseData']);
Route::delete('/delete-purchase-ingredient/{id}', [PurchaseIngredientsController::class, 'deletePurchaseIngredient']);
//position
Route::get('/get-position', [PositionController::class, 'read']);
Route::delete('/delete-position/{id}', [PositionController::class, 'delete']);
Route::post('/add-position/{id}', [PositionController::class, 'create']);
Route::put('/update-position/{id}', [PositionController::class, 'update']);
Route::get('/fetch-position', [PositionController::class, 'getPosition']);
//salary
Route::get('/get-salary', [SalaryController::class, 'read']);
Route::put('/update-salary/{id}', [SalaryController::class, 'update']);
//temp recipe
Route::post('/add-recipes_temp', 'App\Http\Controllers\api\RecipesTempController@create');
Route::get('/get-recipes_temp', 'App\Http\Controllers\api\RecipesTempController@read');
Route::put('/update-recipes_temp/{id}', 'App\Http\Controllers\api\RecipesTempController@update');
Route::delete('/delete-recipes_temp/{id}', 'App\Http\Controllers\api\RecipesTempController@delete');
Route::post('/search-recipes_temp/{id}', 'App\Http\Controllers\api\RecipesTempController@search');
//history
Route::get('/get-history/{id}', [HistoryController::class, 'read']);
Route::get('/customer-admin', [HistoryController::class, 'getCustomer']);
//presensi
Route::get('/get-presensi', [PresensiController::class, 'read']);
Route::post('/set-presensi/{id}', [PresensiController::class, 'setAbsent']);
//general information
Route::get('/get-product-information', [GeneralInformationController::class, 'readProduct']);
Route::get('/get-cutodians-product-information', [GeneralInformationController::class, 'readCustodianProduct']);
Route::get('/get-ready-stock', [GeneralInformationController::class, 'readTodayStock']);
Route::post('/pre-order', [GeneralInformationController::class, 'preOrder']);
Route::get('/get-not-confirm-order', [GeneralInformationController::class, 'readPreOrder']);
Route::post('/confirm-order', [GeneralInformationController::class, 'confirmOrder']);
Route::post('/decline-order', [GeneralInformationController::class, 'declineOrder']);
Route::post('/missing-ingredients/{id}', [GeneralInformationController::class, 'missingIngredients']);
Route::post('/total-distance', [GeneralInformationController::class, 'addOnDelivery']);
Route::post('/get-point', [GeneralInformationController::class, 'countPoint']);
Route::get('/not-confirm-data', [GeneralInformationController::class, 'notConfirmOrder']);
Route::get('/confirm-distance', [GeneralInformationController::class, 'getConfirmOrder']);
Route::post('/confirm-distance-order', [GeneralInformationController::class, 'confirmOrderDistance']);
Route::post('/get-payment', [GeneralInformationController::class, 'getPaymentUser']);
Route::post('/get-payment-order', [GeneralInformationController::class, 'getDetailOrder']);
Route::post('/customer-payment', [GeneralInformationController::class, 'customerConfirmPayment']);
Route::get('/get-mo-confirm', [GeneralInformationController::class, 'orderMOConfirmation']);
Route::post('/get-missing-ingredient', [GeneralInformationController::class, 'countMissingIngredient']);
Route::post('/mo-confirm-order', [GeneralInformationController::class, 'moConfirmOrder']);
Route::post('/mo-decline-order', [GeneralInformationController::class, 'moRejectOrder']);
