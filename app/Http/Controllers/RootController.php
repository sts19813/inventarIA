<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;


class RootController extends Controller
{
    public function __invoke()
    {
        // Si no hay sesión → login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Seguridad extra (por si acaso)
        if (!$user) {
            return redirect()->route('login');
        }

        // Usuario inactivo
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login');
        }

        return match ($user->role) {

            default    => redirect()->route('login'),
        };
    }
}