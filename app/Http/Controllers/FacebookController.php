<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class FacebookController extends Controller
{
    //
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function FacebookCallback()
    {
        $facebookUser = Socialite::driver('facebook')->stateless()->user();
        //dd($facebookUser);

        $user = User::where('facebook_id', $facebookUser->id)->first();

        if ($user) {
            Auth::login($user);

            return redirect('/dashboard');
        } else {
            $user = User::create([
                'email' => $facebookUser->email,
                'name' => $facebookUser->name,
                'facebook_id' => $facebookUser->id,
                'password' => encrypt('qwerty')
            ]);
            Auth::login($user);

            return redirect('/dashboard');
        }
    }
}
