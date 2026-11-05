<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter by admin status
        if ($request->filled('admin_filter')) {
            $adminFilter = $request->input('admin_filter');
            if ($adminFilter === 'admin') {
                $query->where('is_admin', true);
            } elseif ($adminFilter === 'user') {
                $query->where('is_admin', false);
            }
        }

        // Filter by verification status
        if ($request->filled('verified_filter')) {
            $verifiedFilter = $request->input('verified_filter');
            if ($verifiedFilter === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($verifiedFilter === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(10)->withQueryString();

        // Statistics for dashboard cards
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('is_admin', true)->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'recent_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        if (!pergel_can_create_user()) {
            return redirect()->route('admin.license.upgrade', ['reason' => 'user_quota'])
                ->with('error', 'Topluluk (Free) sürümünde en fazla 2 kullanıcı oluşturabilirsiniz. Yeni kullanıcı eklemek için lisansınızı yükseltin.');
        }

        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if (!pergel_can_create_user()) {
            return redirect()->route('admin.license.upgrade', ['reason' => 'user_quota'])
                ->with('error', 'Kullanıcı kotası aşıldı! Topluluk sürümünde maksimum 2 kullanıcı sınırı bulunmaktadır.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->boolean('is_admin'),
            'email_verified_at' => $request->boolean('verified') ? now() : null,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    public function show(User $user)
    {
        // Get user activity statistics
        $userStats = [
            'created_at' => $user->created_at,
            'last_login' => $user->updated_at, // This would be better with actual last_login field
            'status' => $user->email_verified_at ? 'Doğrulanmış' : 'Doğrulanmamış',
            'role' => $user->is_admin ? 'Admin' : 'Kullanıcı',
        ];

        return view('admin.users.show', compact('user', 'userStats'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->boolean('is_admin'),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($request->has('verified')) {
            $userData['email_verified_at'] = $request->boolean('verified') ? now() : null;
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla güncellendi.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting the last admin user
        if ($user->is_admin && User::where('is_admin', true)->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Son admin kullanıcısı silinemez.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla silindi.');
    }

    public function toggleAdmin(User $user)
    {
        // Prevent removing admin rights from the last admin
        if ($user->is_admin && User::where('is_admin', true)->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Son admin kullanıcısının yetkisi kaldırılamaz.'
            ], 400);
        }

        $user->update(['is_admin' => !$user->is_admin]);

        return response()->json([
            'success' => true,
            'message' => 'Kullanıcı yetkisi başarıyla güncellendi.',
            'is_admin' => $user->is_admin
        ]);
    }

    public function toggleVerification(User $user)
    {
        $user->update([
            'email_verified_at' => $user->email_verified_at ? null : now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Doğrulama durumu başarıyla güncellendi.',
            'verified' => (bool) $user->email_verified_at
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,make_admin,remove_admin,verify,unverify',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id'
        ]);

        $userIds = $request->input('user_ids');
        $action = $request->input('action');

        switch ($action) {
            case 'delete':
                // Prevent deleting all admin users
                $adminCount = User::where('is_admin', true)->count();
                $adminsToDelete = User::whereIn('id', $userIds)->where('is_admin', true)->count();
                
                if ($adminCount - $adminsToDelete < 1) {
                    return redirect()->back()->with('error', 'En az bir admin kullanıcısı kalmalıdır.');
                }
                
                User::whereIn('id', $userIds)->delete();
                $message = 'Seçili kullanıcılar silindi.';
                break;

            case 'make_admin':
                User::whereIn('id', $userIds)->update(['is_admin' => true]);
                $message = 'Seçili kullanıcılar admin yapıldı.';
                break;

            case 'remove_admin':
                $adminCount = User::where('is_admin', true)->count();
                $adminsToRemove = User::whereIn('id', $userIds)->where('is_admin', true)->count();
                
                if ($adminCount - $adminsToRemove < 1) {
                    return redirect()->back()->with('error', 'En az bir admin kullanıcısı kalmalıdır.');
                }
                
                User::whereIn('id', $userIds)->update(['is_admin' => false]);
                $message = 'Admin yetkileri kaldırıldı.';
                break;

            case 'verify':
                User::whereIn('id', $userIds)->update(['email_verified_at' => now()]);
                $message = 'Seçili kullanıcılar doğrulandı.';
                break;

            case 'unverify':
                User::whereIn('id', $userIds)->update(['email_verified_at' => null]);
                $message = 'Doğrulama durumları kaldırıldı.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    public function exportCsv(Request $request)
    {
        $query = User::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('admin_filter')) {
            $adminFilter = $request->input('admin_filter');
            if ($adminFilter === 'admin') {
                $query->where('is_admin', true);
            } elseif ($adminFilter === 'user') {
                $query->where('is_admin', false);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        $csvData = "Ad,E-posta,Rol,Doğrulama Durumu,Kayıt Tarihi\n";
        
        foreach ($users as $user) {
            $csvData .= sprintf(
                '"%s","%s","%s","%s","%s"' . "\n",
                $user->name,
                $user->email,
                $user->is_admin ? 'Admin' : 'Kullanıcı',
                $user->email_verified_at ? 'Doğrulanmış' : 'Doğrulanmamış',
                $user->created_at->format('d.m.Y H:i')
            );
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="kullanicilar_' . date('Y-m-d') . '.csv"');
    }
}