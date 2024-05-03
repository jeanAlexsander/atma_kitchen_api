<?php

use App\Http\Controllers\api\EmployeesController;
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
Route::get('/change-password', 'App\Http\Controllers\api\ChangePasswordController@changePassword');
//Product
Route::post('/add-product', 'App\Http\Controllers\api\ProductsController@create');
Route::get('/get-product', 'App\Http\Controllers\api\ProductsController@read');
Route::post('/update-product/{id}', 'App\Http\Controllers\api\ProductsController@update');
Route::delete('/delete-product/{id}', 'App\Http\Controllers\api\ProductsController@delete');
Route::post('/search-product/{id}', 'App\Http\Controllers\api\ProductsController@search');
//Recipes
Route::post('/add-recipes', 'App\Http\Controllers\api\RecipesController@create');
Route::get('/get-recipes', 'App\Http\Controllers\api\RecipesController@read');
Route::post('/update-recipes/{id}', 'App\Http\Controllers\api\RecipesController@update');
Route::delete('/delete-recipes/{id}', 'App\Http\Controllers\api\RecipesController@delete');
Route::post('/search-recipes/{id}', 'App\Http\Controllers\api\RecipesController@search');
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
//employees
Route::get('/get-employees', [EmployeesController::class, 'read']);
Route::delete('/delete-employee/{id}', [EmployeesController::class, 'delete']);
Route::get('/get-role', [EmployeesController::class, 'getRole']);
Route::post('/add-employee', [EmployeesController::class, 'create']);
Route::put('/update-employee/{id}', [EmployeesController::class, 'update']);
