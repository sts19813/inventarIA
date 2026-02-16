<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class RootController extends Controller
{
    public function __invoke()
    {
        // No autenticado → login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Usuario deshabilitado
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login');
        }

        // Redirección por rol
        return match ($user->role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'provider' => redirect()->route('provider.dashboard'),
            default    => redirect()->view('index'),
        };
    }
}
