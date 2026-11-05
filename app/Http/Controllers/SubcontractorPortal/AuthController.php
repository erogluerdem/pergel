<?php

namespace App\Http\Controllers\SubcontractorPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('subcontractor-portal.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::guard('subcontractor')->attempt(array_merge($credentials, ['status' => 'active']), $remember)) {
            throw ValidationException::withMessages([
                'email' => __('Girdiğiniz bilgiler geçerli bir taşeron hesabı ile eşleşmiyor.'),
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::guard('subcontractor')->user();
        $user->forceFill(['last_login_at' => now()])->save();

        return redirect()->intended(route('subcontractor.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('subcontractor')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('subcontractor.login');
    }
}
