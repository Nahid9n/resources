<?php

 // Payment EPS
Route::get('/eps/success',[App\Http\Controllers\EpsPaymentController::class,'epsSuccess'])->name('eps.success');
Route::get('/eps/fail',[App\Http\Controllers\EpsPaymentController::class,'epsFail'])->name('eps.fail');
Route::get('/eps/cancel',[App\Http\Controllers\EpsPaymentController::class,'epsCancel'])->name('eps.cancel');
Route::get('/retry-payment/{id}',[App\Http\Controllers\EpsPaymentController::class,'retryPayment'])->name('retry.payment');


