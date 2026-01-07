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
                'message' => 'login first'
            ], 401);
        }

        // التحقق من أن المستخدم هو مالك
        if ($user->user_type != 'owner') {
            return response()->json([
                'success' => false,
                'message' => 'Only the owner can add apartments'
            ], 403);
        }

        // الفاليديشن لقبول مصفوفة صور
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'description' => 'required|string',
            'address' => 'required|string',
            'size' => 'required|string',
            'num_of_rooms' => 'required|integer',
            'price' => 'required|integer',
            'apartment_images' => 'required',
            'apartment_images.*' => 'image|mimes:jpg,jpeg,png|max:4096',
        ]);

        // تخزين الصور كلها
        $imageUrls = [];
        if ($request->hasFile('apartment_images')) {
            foreach ($request->file('apartment_images') as $image) {
                $path = $image->store('apartment', 'public');
                $imageUrls[] = asset('storage/' . $path);
            }
        }

        // إنشاء الشقة وتخزين روابط الصور كـ JSON
        $apartment = Apartment::create([
            'user_id' => $user->id,
            'city_id' => $request->city_id,
            'description' => $request->description,
            'address' => $request->address,
            'size' => $request->size,
            'num_of_rooms' => $request->num_of_rooms,
            'price' => $request->price,
            'apartment_images' => json_encode($imageUrls),
            'apartment_status' => 'available',
        ]);

        // فك التشفير واستبدال القيمة داخل الـ object
        $apartment->apartment_images = json_decode($apartment->apartment_images, true);

        return response()->json([
            'success' => true,
            'data' => $apartment,
            'message' => 'added successfully'
        ], 201);
    }


    public function filterApartments(Request $request)
    {
        $query = Apartment::query()
            ->where('apartment_status', 'available')
            ->with('city');

        // فلترة حسب المحافظة
        if ($request->filled('gov_id')) {
            $query->whereHas('city', function ($q) use ($request) {
                $q->where('gov_id', $request->gov_id);
            });
        }

        // فلترة حسب المدينة
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // فلترة حسب السعر
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [
                $request->min_price,
                $request->max_price
            ]);
        }

        // فلترة حسب الحجم
        if ($request->filled('size')) {
            $query->where('size', $request->input('size'));
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
