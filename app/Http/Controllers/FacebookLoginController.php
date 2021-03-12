<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class FacebookLoginController extends Controller
{
    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')
            ->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
        } catch (\Exception $e) {
            \Log::error('Unexpected error on facebook login', [
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
