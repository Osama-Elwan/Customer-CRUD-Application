<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
//لو شوفت النوتس واستفدت ادعيلى الاقى شغل
//<3

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/customers/trash',[CustomerController::class,'trashIndex'])->name('customers.trash');//add new route in top of resourse route to avoid override
Route::get('/customers/restore/{customer}',[CustomerController::class,'restore'])->name('customers.restore');
Route::delete('/customers/trash/{customer}',[CustomerController::class,'forceDestroy'])->name('customers.force.destroy');
Route::resource('customers', CustomerController::class);
