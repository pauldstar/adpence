<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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
            'email' => $socialite->email,
            'name' => $socialite->name,
        ]);

        $this->transferGuestSessionBalanceToAccount($user);

        Auth::login($user);

        return redirect('/');
    }

    private function transferGuestSessionBalanceToAccount(Model $user)
    {
        if (session()->has('sessionUuid')) {
            $trans = Transaction::select('amount')
                ->firstWhere('uuid', Session::pull('sessionUuid'));
            $user->increment('balance', $trans->amount);
            $trans->delete();
        }
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect('/');
    }
}
