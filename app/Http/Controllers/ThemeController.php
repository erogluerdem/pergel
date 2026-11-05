<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    /**
     * Kullanıcı tema tercihini kaydet.
     * POST /user/theme
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'theme'     => ['required', 'string', 'in:santiye,corporate,nature,industrial,health'],
            'dark_mode' => ['sometimes', 'boolean'],
        ]);

        if (!Auth::check()) {
            // Oturum açık değilse sadece 200 dön — localStorage yedek olarak çalışır
            return response()->json(['status' => 'guest']);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->theme     = $validated['theme'];
        $user->dark_mode = $validated['dark_mode'] ?? false;
        $user->save();

        return response()->json([
            'status'    => 'ok',
            'theme'     => $user->theme,
            'dark_mode' => $user->dark_mode,
        ]);
    }
}
