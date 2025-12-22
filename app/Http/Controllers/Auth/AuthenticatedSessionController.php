<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request) // Pastikan use LoginRequest ada di atas
        {
            // 1. Validasi Login (Menjalankan logic di LoginRequest tadi)
            $request->authenticate();

            // 2. Regenerate Session (Keamanan)
            $request->session()->regenerate();

            // --- LOGIC REDIRECT SESUAI ROLE (Pindahkan kesini) ---
            $role = Auth::user()->role; 
            
            if($role == 'admin') {
                return redirect()->intended('dashboard/admin');
            }
            if($role == 'custody') {
                return redirect()->intended('dashboard/custody');
            }
            if($role == 'approver') {
                return redirect()->intended('dashboard/approver');
            }

            // Default redirect jika tidak punya role khusus
            return redirect()->intended('/dashboard');
        }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
