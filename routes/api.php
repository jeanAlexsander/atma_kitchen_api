<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'App\Http\Controllers\api\LoginController@login');

Route::get('/change-password', 'App\Http\Controllers\api\ChangePasswordController@changePassword');

//Promo Poin
Route::post('/add-promoPoint', 'App\Http\Controllers\api\PromoPointController@create');
Route::get('/get-promoPoint', 'App\Http\Controllers\api\PromoPointController@read');
Route::post('/update-promoPoint/{id}', 'App\Http\Controllers\api\PromoPointController@update');
Route::delete('/delete-promoPoint/{id}', 'App\Http\Controllers\api\PromoPointController@delete');
Route::post('/search-promoPoint/{id}', 'App\Http\Controllers\api\PromoPointController@search');

//Product
Route::post('/add-product', 'App\Http\Controllers\api\ProductsController@create');
Route::get('/get-product', 'App\Http\Controllers\api\ProductsController@read');
Route::post('/update-product/{id}', 'App\Http\Controllers\api\ProductsController@update');
Route::delete('/delete-product/{id}', 'App\Http\Controllers\api\ProductsController@delete');
Route::post('/search-product/{id}', 'App\Http\Controllers\api\ProductsController@search');

//Ingredients
Route::post('/add-ingredients', 'App\Http\Controllers\api\IngredientsController@create');
Route::get('/get-ingredients', 'App\Http\Controllers\api\IngredientsController@read');
Route::post('/update-ingredients/{id}', 'App\Http\Controllers\api\IngredientsController@update');
Route::delete('/delete-ingredients/{id}', 'App\Http\Controllers\api\IngredientsController@delete');
Route::post('/search-ingredients/{id}', 'App\Http\Controllers\api\IngredientsController@search');

//Custodians
Route::post('/add-custodians', 'App\Http\Controllers\api\CustodiansController@create');
Route::get('/get-custodians', 'App\Http\Controllers\api\CustodiansController@read');
Route::post('/update-custodians/{id}', 'App\Http\Controllers\api\CustodiansController@update');
Route::delete('/delete-custodians/{id}', 'App\Http\Controllers\api\CustodiansController@delete');
Route::post('/search-custodians/{id}', 'App\Http\Controllers\api\CustodiansController@search');

//RecipeIngredients
Route::post('/add-recipeIngredients', 'App\Http\Controllers\api\RecipeIngredientsController@create');
Route::get('/get-recipeIngredients', 'App\Http\Controllers\api\RecipeIngredientsController@read');
Route::post('/update-recipeIngredients/{id}', 'App\Http\Controllers\api\RecipeIngredientsController@update');
Route::delete('/delete-recipeIngredients/{id}', 'App\Http\Controllers\api\RecipeIngredientsController@delete');
Route::post('/search-recipeIngredients/{id}', 'App\Http\Controllers\api\RecipeIngredientsController@search');
