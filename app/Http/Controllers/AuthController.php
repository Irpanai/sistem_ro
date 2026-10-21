<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Auth::attempt(['username' => $request->username, 'password' => $request->password], (bool) $request->remember)) {
            $request->session()->regenerate();

            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors([
            'username' => 'Kredensial yang dimasukkan tidak sesuai.',
        ])->onlyInput('username');
    }

    public function switchRole(Request $request): RedirectResponse
    {
        $roleUsername = $request->query('user', 'admin_noc');
        $targetUser = User::where('username', $roleUsername)->first();

        if ($targetUser) {
            Auth::login($targetUser);
            $request->session()->regenerate();

            return $this->redirectBasedOnRole($targetUser)
                ->with('success', 'Beralih peran ke: '.$targetUser->name.' ('.ucfirst($targetUser->role).')');
        }

        return redirect()->route('login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar dari sistem.');
    }

    private function redirectBasedOnRole(User $user): RedirectResponse
    {
        if ($user->isKlien()) {
            return redirect()->route('klien.portal');
        }

        if ($user->isTeknisi()) {
            return redirect()->route('teknisi.portal');
        }

        return redirect()->route('dashboard');
    }
}
