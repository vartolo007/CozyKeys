<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class AdminController extends BaseController
{
    /**
     * إنشاء Middleware للتحقق من الأدمن
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تسجيل الدخول أولاً'
                ], 401);
            }

            if ($user->user_type !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك. للأدمن فقط!'
                ], 403);
            }

            if ($user->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'حساب الأدمن غير مفعل'
                ], 403);
            }

            return $next($request);
        });
    }

    /**
     * عرض جميع طلبات التسجيل المعلقة
     */
    public function getPendingRegistrations()
    {
        $pendingUsers = User::where('status', 'pending')->get();

        return response()->json([
            'success' => true,
            'data' => $pendingUsers,
            'message' => 'تم جلب طلبات التسجيل المعلقة'
        ]);
    }

    /**
     * الموافقة على تسجيل مستخدم
     */
    public function approveUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود'
            ], 404);
        }

        $user->status = 'approved';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'تمت الموافقة على المستخدم: ' . $user->first_name . ' ' . $user->last_name,
            'user' => $user
        ]);
    }

    /**
     * رفض تسجيل مستخدم
     */
    public function rejectUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود'
            ], 404);
        }

        $user->status = 'rejected';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'تم رفض المستخدم: ' . $user->first_name . ' ' . $user->last_name,
            'user' => $user
        ]);
    }

    /**
     * عرض جميع المستخدمين
     */
    public function getAllUsers()
    {
        $users = User::all();

        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'تم جلب جميع المستخدمين'
        ]);
    }

    /**
     * حذف مستخدم
     */
    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود'
            ], 404);
        }

        // لا يمكن حذف الأدمن نفسه
        if ($user->user_type === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف حساب الأدمن'
            ], 403);
        }

        $userName = $user->first_name . ' ' . $user->last_name;
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المستخدم: ' . $userName
        ]);
    }

    /**
     * عرض إحصائيات النظام
     */
    public function getStatistics()
    {
        $totalUsers = User::count();
        $owners = User::where('user_type', 'owner')->count();
        $tenants = User::where('user_type', 'tenant')->count();
        $admins = User::where('user_type', 'admin')->count();

        $pendingUsers = User::where('status', 'pending')->count();
        $approvedUsers = User::where('status', 'approved')->count();
        $rejectedUsers = User::where('status', 'rejected')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => $totalUsers,
                'by_type' => [
                    'owners' => $owners,
                    'tenants' => $tenants,
                    'admins' => $admins
                ],
                'by_status' => [
                    'pending' => $pendingUsers,
                    'approved' => $approvedUsers,
                    'rejected' => $rejectedUsers
                ]
            ],
            'message' => 'إحصائيات النظام'
        ]);
    }
}
