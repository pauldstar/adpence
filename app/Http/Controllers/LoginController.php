<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     * @param Request $request
     * @return RedirectResponse
     */
    public function redirectToProvider(Request $request): RedirectResponse
    {
        return Socialite::driver($request->route('driver'))->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     * @param Request $request
     * @return RedirectResponse
     */
    public function handleProviderCallback(Request $request): RedirectResponse
    {
        $socialite = Socialite::driver($request->route('driver'))->user();

        $user = User::query()->where('email', $socialite->email)->firstOrCreate([
            'email' => $socialite->getEmail(),
            'name' => $socialite->getName(),
        ]);

        if (session('balance')) {
            $user->increment('balance', session('balance'));
            session(['balance' => 0]);
        }

        Auth::login($user);

        return redirect('/');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect('/');
    }
}
