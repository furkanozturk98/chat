<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class TwitterLoginController extends Controller
{
    /**
     * Redirect the user to the twitter authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider()
    {
        return Socialite::driver('twitter')
            ->redirect();
    }

    /**
     * Obtain the user information from twitter.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('twitter')->user();
        } catch (\Exception $e) {
            \Log::error('Unexpected error on twitter login', [
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
            'image' => 'profile.jpg'
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }
}
