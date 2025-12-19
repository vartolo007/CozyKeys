<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'apartment_id' => 'required|exists:apartments,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
        ];
    }

    public function messages()
    {
        return [
            'apartment_id.required' => 'The apartment could not be located.',
            'apartment_id.exists' => 'The apartment does not exist.',
            'check_in_date.required' => 'Set the arrival date.',
            'check_out_date.required' => 'Set the departure date.',
            'check_out_date.after' => 'The departure date must be after the arrival date.',
        ];
    }
}
