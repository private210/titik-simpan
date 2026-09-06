<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\StrongPassword;
use Database\Seeders\CategorySeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => __('messages.auth.login_failed')])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => ['required', 'string', 'max:72', 'confirmed', new StrongPassword],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $this->seedDefaultCategories($user);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/')->with('success', __('messages.auth.registered'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function redirectToGoogle(Request $request)
    {
        if (! config('services.google.client_id')) {
            return redirect(auth()->check() ? '/profile' : '/login')
                ->with('error', __('messages.auth.google_not_configured'));
        }

        $request->session()->put('google_intent', auth()->check() ? 'sync' : 'login');

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $google = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            Log::warning('google oauth failed: '.$e->getMessage());

            return redirect('/login')->with('error', __('messages.auth.google_failed'));
        }

        $intent = $request->session()->pull('google_intent', 'login');

        if ($intent === 'sync' && auth()->check()) {
            return $this->syncWithGoogle($google);
        }

        return $this->loginWithGoogle($google, $request);
    }

    private function loginWithGoogle($google, Request $request)
    {
        $user = User::where('google_id', $google->getId())
            ->orWhere('email', $google->getEmail())
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $google->getId(),
                'avatar' => $google->getAvatar(),
            ]);
        } else {
            $user = User::create([
                'name' => $google->getName() ?: $google->getEmail(),
                'email' => $google->getEmail(),
                'google_id' => $google->getId(),
                'avatar' => $google->getAvatar(),
                'password' => Str::password(32),
            ]);

            $this->seedDefaultCategories($user);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/')->with('success', __('messages.auth.google_logged_in'));
    }

    private function syncWithGoogle($google)
    {
        $user = auth()->user();

        if ($user->google_id === $google->getId()
            || ($user->google_id === null && $user->email === $google->getEmail())) {
            $user->update([
                'google_id' => $google->getId(),
                'name' => $google->getName() ?: $user->name,
                'email' => $google->getEmail(),
                'avatar' => $google->getAvatar(),
            ]);

            return redirect('/profile')->with('success', __('messages.profile.google_synced'));
        }

        return redirect('/profile')->with('error', 'Akun Google tidak cocok dengan profil ini.');
    }

    private function seedDefaultCategories(User $user): void
    {
        CategorySeeder::seedFor($user);
    }
}
