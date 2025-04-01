<?php

use App\Http\Controllers\DrinkController;
use App\Http\Controllers\MachineController;
use App\Models\Machine;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/select', [DrinkController::class, 'index']);
Route::post('/select/user', [MachineController::class,'User']);
Route::post('/receipt/{id}', [DrinkController::class,'receipt']);
Route::get('/lucky/{key}', [DrinkController::class,'lucky']);
Route::get('/admin', [DrinkController::class,'admin']);
Route::post('/admin/change', [DrinkController::class,'admin'])->name('change');
Route::post('/admin/system', [MachineController::class,'system']);
Route::get('/edit/{id}', [DrinkController::class,'edit'])->name('edit');
Route::post('/edit/{id}', [DrinkController::class,'update']);
Route::get('/delete/{id}', [DrinkController::class,'destroy']);
Route::get('/wallet', [MachineController::class,'wallet']);
Route::post('/wallet/{key}', [MachineController::class,'wallet_custom']);