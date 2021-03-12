<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')
            ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            \Log::error('Unexpected error on Google login', [
                'code'    => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('login')->withErrors([
                'message' => 'Unexpected error occurred. Please try again later',
            ]);
        }


        $user = User::firstOrCreate([
            'email' => $user->getEmail(),
        ], [
            'name'     => $user->getName(),
            'password' => bcrypt(Str::random()),
            'api_token' => Str::random(60),
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }
}
