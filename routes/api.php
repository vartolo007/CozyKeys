<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ApartmentReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// register routes
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [App\Http\Controllers\UserController::class, 'register']);
Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout'])->middleware('auth:sanctum');

// apartment + admin routes
Route::middleware('auth:sanctum')->group(function () {

    // 🏠 شقق
    Route::get('/apartments', [ApartmentController::class, 'getAllApartments']);
    Route::get('/apartments/{apartment}', [ApartmentController::class, 'getApartmentDetails']);
    Route::post('/apartments', [ApartmentController::class, 'addApartment']);
    Route::get('/apartments/filter', [ApartmentController::class, 'filterApartments']);

    // ⭐ تقييمات الشقق
    Route::post('/apartments/{apartment}/reviews', [ApartmentReviewController::class, 'Evaluation']);
    Route::get('/apartments/{apartment}/reviews', [ApartmentReviewController::class, 'EvaluationPresentation']);

    // 👨‍💼 مسارات الأدمن
    Route::get('/admin/pending-registrations', [AdminController::class, 'getPendingRegistrations']);
    Route::put('/admin/users/approve/{id}', [AdminController::class, 'approveUser']);
    Route::put('/admin/users/reject/{id}', [AdminController::class, 'rejectUser']);
    Route::get('/admin/users', [AdminController::class, 'getAllUsers']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
    Route::get('/admin/statistics', [AdminController::class, 'getStatistics']);
});

// 🗓️ حجوزات
Route::middleware('auth:sanctum')->group(function () {

    //حجز شقة
    Route::post('/bookings', [BookingController::class, 'store']);

    // عرض حجوزات شقق المالك
    Route::get('/owner/bookings', [BookingController::class, 'ownerBookings']);

    // موافقة المالك
    Route::put('/bookings/{id}/approve', [BookingController::class, 'approveBooking']);

    // رفض المالك
    Route::put('/bookings/{id}/reject', [BookingController::class, 'rejectBooking']);

    // تعديل الطلب للمستأجر
    Route::put('/bookings/{id}/request-edit', [BookingController::class, 'update']);

    //حذف الطلب للمستأجر
    Route::put('/bookings/{id}/request-cancel', [BookingController::class, 'destroy']);

    // عرض الطلبات للمالك
    Route::get('/owner/booking-requests', [BookingController::class, 'ownerRequests']);

    //الموافقة عل طلبات التعديل للمالك
    Route::put('/bookings/{id}/approve-edit', [BookingController::class, 'approveEdit']);

    //الموافقة عل طلبات الإلغاء للمالك
    Route::put('/bookings/{id}/approve-cancel', [BookingController::class, 'approveCancel']);

    //رفض الطلب للمالك(سواء للتعديل أو الإلغاء)
    Route::put('/bookings/{id}/reject-request', [BookingController::class, 'rejectRequest']);
});
