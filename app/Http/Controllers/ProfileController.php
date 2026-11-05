<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

// small helper to create thumbnail using GD
function _create_thumbnail($sourcePath, $targetPath, $maxSize = 200) {
    try {
        $data = file_get_contents($sourcePath);
        if ($data === false) return false;
        $im = imagecreatefromstring($data);
        if (!$im) return false;
        $w = imagesx($im);
        $h = imagesy($im);
        $scale = min($maxSize / $w, $maxSize / $h, 1);
        $nw = (int)($w * $scale);
        $nh = (int)($h * $scale);
        $thumb = imagescale($im, $nw, $nh);
        ob_start();
        imagepng($thumb);
        $png = ob_get_clean();
        imagedestroy($im);
        imagedestroy($thumb);
        return $png !== false ? $png : false;
    } catch (\Throwable $e) {
        return false;
    }
}

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = auth()->user();
        if ($user->is_admin) {
            // Admin için kullanıcı listesi
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

            // Sorting (whitelisted)
            $allowedSortColumns = ['id', 'name', 'email', 'is_admin', 'is_active', 'department', 'title', 'created_at', 'updated_at'];
            $sortBy = $request->input('sort_by', 'created_at');
            if (!in_array($sortBy, $allowedSortColumns, true)) {
                $sortBy = 'created_at';
            }
            $sortOrder = strtolower($request->input('sort_order', 'desc'));
            if (!in_array($sortOrder, ['asc', 'desc'], true)) {
                $sortOrder = 'desc';
            }
            $query->orderBy($sortBy, $sortOrder);

            $users = $query->paginate(10);
            return view('profile.show', compact('users', 'user'));
        } else {
            // Normal kullanıcı için kendi profili
            return view('profile.show', compact('user'));
        }
    }

    // CRUD methods for user management (admin only)
    public function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }
        return view('profile.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->boolean('is_admin'),
            'is_active' => $request->boolean('is_active', true),
            'title' => $request->title,
            'department' => $request->department,
            'phone' => $request->phone,
            'bio' => $request->bio,
        ]);

        return redirect()->route('profile.show')->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    public function edit($id = null)
    {
        $id = $id ?? auth()->id();
        if (!auth()->user()->is_admin) {
            // If not admin, allow editing own profile
            if ($id != auth()->id()) {
                abort(403);
            }
            $user = auth()->user();
            return view('profile.edit', compact('user'));
        }

        $user = User::findOrFail($id);
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request, $id = null)
    {
        $id = $id ?? auth()->id();
        if (!auth()->user()->is_admin) {
            // If not admin, allow updating own profile
            if ($id != auth()->id()) {
                abort(403);
            }
            $user = auth()->user();
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'avatar' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:5120',
                'title' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'bio' => 'nullable|string',
            ]);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->title = $request->title;
            $user->department = $request->department;
            $user->phone = $request->phone;
            $user->bio = $request->bio;

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $path = $file->store('avatars', 'public');
                $user->avatar = $path;

                // create thumbnail
                $fullPath = storage_path('app/public/' . $path);
                $thumbData = _create_thumbnail($fullPath, null, 200);
                if ($thumbData) {
                    $thumbPath = 'avatars/thumbs/' . basename($path) . '.png';
                    Storage::disk('public')->put($thumbPath, $thumbData);
                    $user->avatar_thumbnail = $thumbPath;
                }
            }

            $user->save();
            return redirect()->route('profile.show')->with('success', 'Profil güncellendi.');
        }

        // Admin updating any user
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_admin = $request->boolean('is_admin');
        $user->is_active = $request->boolean('is_active');
        $user->title = $request->title;
        $user->department = $request->department;
        $user->phone = $request->phone;
        $user->bio = $request->bio;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public');
            $user->avatar = $path;

            // create thumbnail
            $fullPath = storage_path('app/public/' . $path);
            $thumbData = _create_thumbnail($fullPath, null, 200);
            if ($thumbData) {
                $thumbPath = 'avatars/thumbs/' . basename($path) . '.png';
                Storage::disk('public')->put($thumbPath, $thumbData);
                $user->avatar_thumbnail = $thumbPath;
            }
        }

        $user->save();
        return redirect()->route('profile.show')->with('success', 'Kullanıcı güncellendi.');
    }

    public function destroy($id)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $user = User::findOrFail($id);
        if ($user->id == auth()->id()) {
            return redirect()->back()->with('error', 'Kendi hesabınızı silemezsiniz.');
        }

        $user->delete();
        return redirect()->route('profile.show')->with('success', 'Kullanıcı silindi.');
    }
}
