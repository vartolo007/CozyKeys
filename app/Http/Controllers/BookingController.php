<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Apartment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * إنشاء طلب حجز آمن مع منع التضارب (transaction + row lock).
     */
    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();
        $apartmentId = (int) $data['apartment_id'];
        $checkIn = $data['check_in_date'];
        $checkOut = $data['check_out_date'];
        $userId = $request->user()->id;

        try {
            $booking = DB::transaction(function () use ($apartmentId, $checkIn, $checkOut, $userId) {
                $apt = Apartment::where('id', $apartmentId)->lockForUpdate()->firstOrFail();

                // منع التداخل مع approved فقط
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


    /**
     * استعراض الحجوزات الخاصة بشقق المالك
     */
    public function ownerBookings()
    {
        $ownerId = Auth::id();

        $bookings = Booking::whereHas('apartment', function ($q) use ($ownerId) {
            $q->where('user_id', $ownerId);
        })->orderBy('check_in_date', 'asc')->get();

        return response()->json([
            'success' => true,
            'bookings' => $bookings
        ]);
    }

    /**
     * موافقة المالك على الحجز
     */
    public function approveBooking($id)
    {
        $booking = Booking::findOrFail($id);

        // فقط المالك يوافق
        if (Auth::id() !== $booking->apartment->user_id) {
            return response()->json(['message' => 'Not allowed. Only the apartment owner can approve.'], 403);
        }

        // منع الموافقة إذا يوجد تداخل مع approved
        if (Booking::hasOverlap(
            $booking->apartment_id,
            $booking->check_in_date,
            $booking->check_out_date,
            ['approved'],
            $booking->id
        )) {
            return response()->json(['message' => 'Cannot be approved due to another booking'], 400);
        }

        $booking->update(['booking_status' => 'approved']);
        // تحديث حالة الشقة إلى booking
        $booking->apartment->update(['apartment_status' => 'booking']);

        return response()->json(['message' => 'The booking has been approved', 'booking' => $booking]);
    }

    /**
     * رفض الحجز من قبل المالك
     */
    public function rejectBooking($id)
    {
        $booking = Booking::findOrFail($id);

        // فقط المالك يرفض
        if (Auth::id() !== $booking->apartment->user_id) {
            return response()->json(['message' => 'Not allowed. Only the apartment owner can refuse'], 403);
        }

        $booking->update(['booking_status' => 'rejected']);

        return response()->json(['message' => 'The booking was rejected', 'booking' => $booking]);
    }

    //دالة عرض طلبات التعديل والالغاء
    public function ownerRequests()
    {
        $ownerId = Auth::id();

        $requests = Booking::whereHas('apartment', function ($q) use ($ownerId) {
            $q->where('user_id', $ownerId);
        })
            ->where('request_type', '!=', 'none')
            ->get();

        return response()->json(['requests' => $requests]);
    }


    //دالة موافقة المالك على طلب التعديل
    public function approveEdit($id)
    {
        $booking = Booking::findOrFail($id);

        if (Auth::id() !== $booking->apartment->user_id) {
            return response()->json(['message' => 'Not allowed'], 403);
        }

        $data = json_decode($booking->edit_data, true);

        if (Booking::hasOverlap(
            $booking->apartment_id,
            $data['check_in_date'],
            $data['check_out_date'],
            ['approved'],
            $booking->id
        )) {
            return response()->json(['message' => 'Conflicted with another reservation'], 400);
        }

        $booking->update([
            'check_in_date' => $data['check_in_date'],
            'check_out_date' => $data['check_out_date'],
            'request_type' => 'none',
            'edit_data' => null
        ]);

        return response()->json(['message' => 'The amendment has been approved']);
    }


    //دالة موافقة المالك على طلب الإلغاء
    public function approveCancel($id)
    {
        $booking = Booking::findOrFail($id);

        if (Auth::id() !== $booking->apartment->user_id) {
            return response()->json(['message' => 'Not allowed'], 403);
        }

        $booking->update([
            'booking_status' => 'cancelled',
            'request_type' => 'none'
        ]);

        return response()->json(['message' => 'The cancellation has been approved']);
    }

    //دالة رفض المالك للطلب
    public function rejectRequest($id)
    {
        $booking = Booking::findOrFail($id);

        if (Auth::id() !== $booking->apartment->user_id) {
            return response()->json(['message' => 'Not allowed'], 403);
        }

        $booking->update([
            'request_type' => 'none',
            'edit_data' => null
        ]);

        return response()->json(['message' => 'The request was denied']);
    }

    /**
     * تعديل الحجز من قبل المستأجر
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // تأكد أن المستخدم هو صاحب الحجز
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['message' => 'Not allowed'], 403);
        }

        // تعديل الحقول المطلوبة
        $booking->request_type = 'edit';
        $booking->edit_data = json_encode([
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date
        ]);

        $booking->save(); // 🔥 هذا هو السطر اللي ينفّذ التعديل فعليًا

        return response()->json(['message' => 'The modification request has been sent to the owner.']);
    }

    /**
     * إلغاء الحجز من قبل المستأجر
     */
    public function destroy(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id !== Auth::id()) {
            return response()->json(['message' => 'Not allowed'], 403);
        }

        // إرسال طلب إلغاء
        $booking->update([
            'request_type' => 'cancel'
        ]);

        return response()->json(['message' => 'The cancellation request has been sent to the owner']);
    }
}
