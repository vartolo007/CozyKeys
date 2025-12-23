<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class AdminController extends BaseController
{
    // للتحقق من الأدمن
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must log in first'
                ], 401);
            }

            if ($user->user_type !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'You aren`t authorized. Admins only!'
                ], 403);
            }

            if ($user->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'The admin account is not activated'
                ], 403);
            }

            return $next($request);
        });
    }

    // عرض جميع طلبات التسجيل المعلقة

    public function getPendingRegistrations()
    {
        $pendingUsers = User::where('status', 'pending')->get();

        return response()->json([
            'success' => true,
            'data' => $pendingUsers,
            'message' => 'Pending registration requests have been fetched'
        ]);
    }

    //الموافقة على تسجيل مستخدم

    public function approveUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->status = 'approved';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User approved: ' . $user->first_name . ' ' . $user->last_name,
            'user' => $user
        ]);
    }

    // رفض تسجيل مستخدم
    public function rejectUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'The user does not exist'
            ], 404);
        }

        $user->status = 'rejected';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User was rejected: ' . $user->first_name . ' ' . $user->last_name,
            'user' => $user
        ]);
    }

    // عرض جميع المستخدمين
    public function getAllUsers()
    {
        $users = User::all();

        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'All users have been retrieved'
        ]);
    }

    // حذف مستخدم
    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'The user does not exist'
            ], 404);
        }

        if ($user->user_type === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'The admin account cannot be deleted'
            ], 403);
        }

        $userName = $user->first_name . ' ' . $user->last_name;
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'The user has been deleted: ' . $userName
        ]);
    }

    //عرض إحصائيات النظام

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
            'message' => 'System statistics'
        ]);
    }
}
