<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Apartment;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // إضافة شقة للمفضلة
    public function addToFavorites(Request $request, $apartmentId)
    {
        $user = $request->user();

        // تحقق إذا الشقة موجودة
        $apartment = Apartment::findOrFail($apartmentId);

        // تحقق إذا الشقة موجودة مسبقاً بالمفضلة
        $exists = Favorite::where('user_id', $user->id)
            ->where('apartment_id', $apartmentId)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Apartment already in favorites'
            ], 400);
        }

        $favorite = Favorite::create([
            'user_id' => $user->id,
            'apartment_id' => $apartmentId,
        ]);

        return response()->json([
            'success' => true,
            'data' => $favorite,
            'message' => 'Apartment added to favorites successfully'
        ], 201);
    }

    // عرض قائمة المفضلة للمستخدم
    public function getFavorites(Request $request)
    {
        $user = $request->user();

        $favorites = Favorite::where('user_id', $user->id)
            ->with('apartment') // جلب بيانات الشقة المرتبطة
            ->get();

        return response()->json([
            'success' => true,
            'data' => $favorites,
            'message' => 'Favorites fetched successfully'
        ]);
    }

    // إزالة شقة من المفضلة
    public function removeFromFavorites(Request $request, $apartmentId)
    {
        $user = $request->user();

        $favorite = Favorite::where('user_id', $user->id)
            ->where('apartment_id', $apartmentId)
            ->first();

        if (!$favorite) {
            return response()->json([
                'success' => false,
                'message' => 'Apartment not found in favorites'
            ], 404);
        }

        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Apartment removed from favorites successfully'
        ]);
    }
}
