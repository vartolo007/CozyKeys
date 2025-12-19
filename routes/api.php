<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//register routes

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [App\Http\Controllers\UserController::class, 'register']);
Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout'])->middleware('auth:sanctum');


//apartment routes


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/apartments', [ApartmentController::class, 'getAllApartments']);

    Route::get('/apartments/{apartment}', [ApartmentController::class, 'getApartmentDetails']);

    Route::post('/apartments', [ApartmentController::class, 'addApartment']);

    Route::get('/apartments/filter', [ApartmentController::class, 'filterApartments']);

    //admin routes

    Route::get('/admin/pending-registrations', [AdminController::class, 'getPendingRegistrations']);

    Route::put('/admin/users/approve/{id}', [AdminController::class, 'approveUser']);

    Route::put('/admin/users/reject/{id}', [AdminController::class, 'rejectUser']);

    Route::get('/admin/users', [AdminController::class, 'getAllUsers']);

    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);

    Route::get('/admin/statistics', [AdminController::class, 'getStatistics']);
});

// مسار إنشاء الحجز (يتطلب مصادقة)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bookings', [BookingController::class, 'store']);
});


