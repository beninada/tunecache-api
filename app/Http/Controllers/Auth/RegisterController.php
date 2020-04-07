<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\UserRegistered;
use App\Models\ArtistProfile;
use App\Models\CustomerProfile;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    protected function validator(array $data)
    {
        return Validator::make($data, User::$createRules);
    }

    protected function create(Request $request)
    {
        $data = $request->all();
        $validator = $this->validator($data);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        try {
            $user = User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole($data['role']);

            $data['user_id'] = $user->id;
            $profile = [];

            if ($data['role'] == 'artist') {
                $profile = ArtistProfile::create($data);
            } elseif ($data['role'] == 'customer') {
                $profile = CustomerProfile::create($data);
            }

            $profile->user()->save(User::find($user->id));
            $jwt = $user->createToken('Laravel Password Grant Client')->accessToken;
            $user['token'] = $jwt;

            // subscribe the user to our main list on Mailchimp
            if (\App::environment() == 'prd') {
                Newsletter::subscribe($user->email, ['UNAME' => $user->username]);
                Newsletter::addTags([$data['role']], $user->email);
            }

            // send the user an email after registration
            Mail::to($user)->send(new UserRegistered());
        } catch (\Exception $e) {
            \Log::error('User creation failure: ' . $e);
            return response(['errors' => [$e->getMessage()]], 500);
        }

        return $user;
    }
}
