<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApartmentReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ممكن تضيف تحقق إضافي إذا بدك
    }

    public function rules(): array
    {
        return [
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|float|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }
}
