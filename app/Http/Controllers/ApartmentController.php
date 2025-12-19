<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\ApartmentResource;
use App\Models\Apartment;
use App\Models\City;
use App\Models\Gov;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApartmentController extends Controller
{
    public function getAllApartments()
    {
        $apartments = Apartment::where('apartment_status', 'available')
            ->get([
                'id',
                'city_id',
                'description',
                'address',
                'size',
                'num_of_rooms',
                'price',
                'apartment_images',
                'apartment_status',
            ]);

        return response()->json([
            'success' => true,
            'data' => $apartments,
            'message' => '  all apartment fetched successfully  '
        ]);
    }

    public function getApartmentDetails(Apartment $apartment)
    {
        return ResponseHelper::jsonResponse(ApartmentResource::make($apartment), 'data fetched successfully');
    }

    public function addApartment(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => '  login first'
            ], 401);
        }

        // التحقق من أن المستخدم هو مالك
        if ($user->user_type != 'owner') {
            return response()->json([
                'success' => false,
                'message' => 'فقط المالك يمكنه إضافة شقق'
            ], 403);
        }

        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'description' => 'required|string',
            'address' => 'required|string',
            'size' => 'required|string',
            'num_of_rooms' => 'required|integer',
            'price' => 'required|integer',
            'apartment_images' => 'required|image|mimes:jpg,jpeg,png|max:4096',
        ]);


        $apartment = Apartment::create([
            'user_id' => auth::user()->id,
            'city_id' => $request->city_id,
            'description' => $request->description,
            'address' => $request->address,
            'size' => $request->size,
            'num_of_rooms' => $request->num_of_rooms,
            'price' => $request->price,
            'apartment_images' => $request->file('apartment_images')->store('apartment', 'public'),
            'apartment_status' => 'available',
        ]);

        return response()->json([
            'success' => true,
            'data' => $apartment,
            'message' => 'added suscessfully'
        ], 201);
    }

    public function filterApartments(Request $request)
    {
        $query = Apartment::where('apartment_status', 'available');

        // فلترة حسب المحافظة (من جدول المدن)
        if ($request->has('gov_id')) {
            $query->whereHas('city', function ($q) use ($request) {
                $q->where('gov_id', $request->gov_id);
            });
        }

        // فلترة حسب المدينة
        if ($request->has('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // فلترة حسب السعر
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // فلترة حسب الحجم (features)
        if ($request->has('size')) {
            foreach ($request->size as $size) {
                $query->whereJsonContains('size', $$size);
            }
        }

        $apartments = $query->get([
            'id',
            'city_id',
            'description',
            'address',
            'size',
            'num_of_rooms',
            'price',
            'apartment_images',
            'apartment_status',

        ]);

        return response()->json([
            'success' => true,
            'data' => $apartments,
            'message' => 'Filtered apartments fetched successfully'
        ]);
    }
}
