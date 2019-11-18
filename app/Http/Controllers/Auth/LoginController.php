<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $jwt = $user->createToken('Laravel Password Grant Client')->accessToken;
                $user['token'] = $jwt;
            } else {
                return response(['errors' => ['Your password is incorrect']], 422);
            }
        } else {
            return response(['errors' => ['A user with this email does not exist']], 422);
        }

        return $user;
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        return response([], 200);
    }

    public function check(Request $request)
    {
        $loggedInUser = Auth::user();

        if (!$loggedInUser) {
            return response([], 401);
        }

        return $loggedInUser->load('profile');
    }
}
