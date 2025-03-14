<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ExpensesController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/Posts', function(){
    return 'API';
});


Route::post('register', [AuthController::class , 'register']);
Route::get('login', [AuthController::class , 'login']);

Route::middleware(['auth:sanctum','checkAdmin'])->apiResource('tags', TagsController::class);

Route::middleware('auth:sanctum' , 'checkAdmin')->apiResource('expenses', ExpensesController::class);

