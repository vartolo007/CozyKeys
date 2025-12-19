<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Apartment;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    /**
     * إنشاء طلب حجز آمن مع منع التضارب (transaction + row lock).
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        $data = $request->validated();
        $apartmentId = (int) $data['apartment_id'];
        $checkIn = $data['check_in_date'];
        $checkOut = $data['check_out_date'];
        $userId = $request->user()->id;

        try {
            $booking = DB::transaction(function () use ($apartmentId, $checkIn, $checkOut, $userId) {
                $apt = Apartment::where('id', $apartmentId)->lockForUpdate()->firstOrFail();

                if (Booking::hasOverlap($apartmentId, $checkIn, $checkOut, ['approved'])) {
                    throw new \Exception('This period is already booked. Please choose other dates.');
                }
                return Booking::create([
                    'user_id' => $userId,
                    'apartment_id' => $apartmentId,
                    'check_in_date' => $checkIn,
                    'check_out_date' => $checkOut,
                    'booking_status' => 'pending',
                ]);
            }, 5);

            return response()->json(['success' => true, 'booking' => $booking], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
