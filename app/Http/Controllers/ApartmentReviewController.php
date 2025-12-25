<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApartmentReviewRequest;
use App\Models\ApartmentReview;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;

class ApartmentReviewController extends Controller
{

    //دالة للتقييم للمستأجر
    public function Evaluation(StoreApartmentReviewRequest $request): JsonResponse
    {
        $data = $request->validated();
        $booking = Booking::findOrFail($data['booking_id']);


        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Not Allowed'], 403);
        }

        if ($booking->check_out_date > now() && $booking->booking_status !== 'cancelled') {
            return response()->json(['message' => 'You cannot leave a review before the booking ends or cancellation'], 400);
        }


        $review = ApartmentReview::create([
            'user_id' => $request->user()->id,
            'apartment_id' => $booking->apartment_id,
            'booking_id' => $booking->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return response()->json([
            'message' => 'The rating has been added successfully',
            'review' => $review
        ]);
    }

    // استعراض التقييمات لشقة حسب ال id
    public function EvaluationPresentation($apartmentId): JsonResponse
    {
        $reviews = ApartmentReview::where('apartment_id', $apartmentId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $average = $reviews->avg('rating');

        return response()->json([
            'average_rating' => $average,
            'reviews' => $reviews
        ]);
    }
}
