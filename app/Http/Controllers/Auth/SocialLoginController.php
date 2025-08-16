<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SocialLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // যদি session থাকে তবে সরাসরি, না হলে stateless ব্যবহার করুন
            $googleUser = Socialite::driver('google')->user();
            // যদি আবার error দেয় তবে stateless() দিয়ে চেষ্টা করুন
            // $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                ['name' => $googleUser->getName()]
            );

            Auth::login($user);

            return redirect()->route('welcome');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'oauth' => 'Google login failed: ' . $e->getMessage(),
            ]);
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            // যদি session mismatch error দেয় তবে stateless() ব্যবহার করুন
            $fbUser = Socialite::driver('facebook')->user();
            // বিকল্প:
            // $fbUser = Socialite::driver('facebook')->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $fbUser->getEmail()],
                [
                    'name' => $fbUser->getName(),
                    'password' => bcrypt(str()->random(16)), // new user হলে dummy password
                ]
            );

            Auth::login($user);

            return redirect()->route('home')->with('success', 'Logged in with Facebook!');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'facebook' => 'Facebook login failed: ' . $e->getMessage(),
            ]);
        }
    }
}
