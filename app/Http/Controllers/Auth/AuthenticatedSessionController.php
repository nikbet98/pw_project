<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\DataLayer;
use App\Providers\RouteServiceProvider;
use App\Models\User;





class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $dl = new DataLayer();
        $categories = $dl->listCategories();
        return view('auth.login')->with('categories', $categories);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        if (Auth::user()->isAdmin()) { // Usa la sintassi con parentesi
            return redirect()->intended(RouteServiceProvider::ADMIN_HOME); 
        } else {
            return redirect()->intended(RouteServiceProvider::HOME); 
        }
    }



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('home');
    }
}
