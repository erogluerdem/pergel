<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LockScreenController extends Controller
{
    /**
     * Show the standalone lock screen.
     */
    public function show(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.show');
        }

        // Ensure page_locked flag is active in SQL
        if (!$user->page_locked) {
            $user->page_locked = true;
            $user->save();
        }

        return view('auth-lock-screen', [
            'user' => $user,
        ]);
    }

    /**
     * Lock the session for the currently authenticated user (AJAX / POST).
     */
    public function lock(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Oturum bulunamadı.'], 401);
        }

        $user->page_locked = true;
        $user->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'locked' => true,
                'message' => 'Ekran başarıyla kilitlendi.',
            ]);
        }

        return redirect()->route('lock-screen.show');
    }

    /**
     * Unlock the screen using the user's password (AJAX / POST).
     */
    public function unlock(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Oturum süresi dolmuş.'], 401);
        }

        $request->validate([
            'password' => 'required|string',
        ], [
            'password.required' => 'Lütfen şifrenizi girin.',
        ]);

        $now = now();

        // 1. Check temporary rate-limit lock
        if (!empty($user->page_lock_locked_until) && $user->page_lock_locked_until > $now) {
            $diffMinutes = $now->diffInMinutes($user->page_lock_locked_until) + 1;
            return response()->json([
                'success' => false,
                'locked' => true,
                'rate_limited' => true,
                'message' => "Çok fazla hatalı deneme yapıldı. Lütfen {$diffMinutes} dakika sonra tekrar deneyin.",
            ], 429);
        }

        // 2. Verify password against SQL password hash
        if (!Hash::check($request->password, $user->password)) {
            $user->page_lock_failed_attempts = ($user->page_lock_failed_attempts ?? 0) + 1;
            $remaining = max(0, 5 - $user->page_lock_failed_attempts);

            if ($user->page_lock_failed_attempts >= 5) {
                $user->page_lock_locked_until = $now->copy()->addMinutes(5);
                $user->page_lock_failed_attempts = 0;
                $user->save();

                return response()->json([
                    'success' => false,
                    'locked' => true,
                    'rate_limited' => true,
                    'message' => '5 kez hatalı şifre girdiniz. Güvenlik sebebiyle ekran 5 dakika kilitlendi.',
                ], 429);
            }

            $user->save();

            return response()->json([
                'success' => false,
                'locked' => true,
                'remaining_attempts' => $remaining,
                'message' => "Hatalı şifre! (Kalan deneme hakkı: {$remaining})",
            ], 422);
        }

        // 3. Password correct: Reset lock & counters in SQL
        $user->page_locked = false;
        $user->page_lock_failed_attempts = 0;
        $user->page_lock_locked_until = null;
        $user->save();

        // Clear intended URL if it contains lock-screen
        session()->forget('url.intended');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'locked' => false,
                'message' => 'Kilit başarıyla açıldı.',
                'redirect' => url('/'),
            ]);
        }

        return redirect()->to('/');
    }

    /**
     * Check lock status for current session.
     */
    public function status(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['authenticated' => false, 'locked' => false]);
        }

        return response()->json([
            'authenticated' => true,
            'locked' => (bool) $user->page_locked,
            'locked_until' => $user->page_lock_locked_until,
        ]);
    }
}
