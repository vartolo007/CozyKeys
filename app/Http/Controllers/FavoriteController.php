<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Apartment;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    //تبديل حالة المفضلة (إضافة أو إزالة)

    public function toggleFavorite(Request $request, $apartmentId)
    {
        $user = $request->user();

        $apartment = Apartment::findOrFail($apartmentId);

        $favorite = Favorite::where('user_id', $user->id)
            ->where('apartment_id', $apartmentId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'success' => true,
                'message' => 'Apartment removed from favorites'
            ]);
        } else {
            $favorite = Favorite::create([
                'user_id' => $user->id,
                'apartment_id' => $apartmentId,
            ]);
            return response()->json([
                'success' => true,
                'data' => $favorite,
                'message' => 'Apartment added to favorites'
            ], 201);
        }
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
}
