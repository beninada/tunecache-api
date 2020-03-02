<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\ArtistProfile;
use App\Models\CustomerProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Newsletter;
use Intervention\Image\ImageManager;

class UserController extends Controller
{
    protected function updateUserValidator($id, array $data)
    {
        return Validator::make($data, [
            'username' => ['sometimes', 'string', 'max:25'],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
            'role' => ['sometimes', 'in:admin,customer,artist'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
        ]);
    }

    protected function updateProfileValidator(array $data)
    {
        return Validator::make($data, [
            'fb_url' => ['sometimes', 'nullable', 'url'],
            'tw_url' => ['sometimes', 'nullable', 'url'],
            'ig_url' => ['sometimes', 'nullable', 'url'],
            'sc_url' => ['sometimes', 'nullable', 'url'],
            'bio' => ['sometimes', 'nullable', 'max:10000'],
            'short_bio' => ['sometimes', 'nullable', 'max:1000'],
        ]);
    }

    protected function uploadImageValidator(array $data)
    {
        return Validator::make($data, [
            'file' => ['required', 'max:10240', 'mimes:jpeg,jpg,png'],
            'type' => ['required', 'in:customer_profile,artist_profile,artist_banner'],
        ]);
    }

    public function index(Request $request)
    {
        if ($request['artists']) {
            return $this->getArtists($request->all());
        }

        return [];
    }

    public function show($role, $uri)
    {
        return $this->getByUri($uri);
    }

    public function update(Request $request, $id)
    {
        $validator = $this->updateUserValidator($id, $request->all());

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        // find the user or return not found
        $user = User::findOrFail($id);
        $originalEmail = $user->email;
        $originalUsername = $user->username;

        if (isset($request['password'])) {
            $request['password'] = Hash::make($request['password']);
        }

        $user->update($request->all());

        if (\App::environment() == 'prd') {
            // if the user updated their email, we need to update our Mailchimp list
            if ($user->email != $originalEmail) {
                Newsletter::updateEmailAddress($originalEmail, $user->email);
            }

            // if the user updated their username, we need to update our Mailchimp list
            if ($user->username != $originalUsername) {
                Newsletter::subscribeOrUpdate($user->email, ['UNAME' => $user->username]);
            }
        }

        return $user;
    }

    public function updateCustomerProfile(Request $request, $id)
    {
        $validator = $this->updateProfileValidator($request->all());

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        // get the logged in user
        $loggedInUser = Auth::user();

        // make sure this user has a customer profile
        if (!$loggedInUser->hasRole('customer')) {
            return response (['errors' => ['This user does not have a customer profile']], 422);
        }

        // update user profile fields
        $customerProfile = CustomerProfile::where('user_id', $id);
        $customerProfile->update($request->all());

        return $loggedInUser->load('profile');
    }

    public function updateArtistProfile(Request $request, $id)
    {
        $validator = $this->updateProfileValidator($request->all());

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        // get the logged in user
        $loggedInUser = Auth::user();

        // make sure this user has an artist profile
        if (!$loggedInUser->hasRole('artist')) {
            return response (['errors' => ['This user does not have a artist profile']], 422);
        }

        // update user profile fields
        $artistProfile = ArtistProfile::where('user_id', $id);
        $artistProfile->update($request->all());

        return $loggedInUser->load('profile');
    }

    public function uploadImage(Request $request, $userId)
    {
        $validator = $this->uploadImageValidator($request->all());

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        // get the logged in user
        $loggedInUser = Auth::user();

        // filename is the user's id concatenated w/ timestamp in folder e.g. dev-images/customer-profile/
        $filename = $userId . '_' . time();
        $type = $request->type;
        $file = $request->file;
        $extension = $file->extension();
        $path = $type . '/' . $filename . '.' . $extension;
        
        $manager = new ImageManager(array('driver' => 'imagick'));

        switch ($type) {
            case 'customer_profile':
            case 'artist_profile':
                $croppedImage = $manager->make($file->getRealPath())->fit(800, 800);
                break;
            case 'artist_banner':
                $croppedImage = $manager->make($file->getRealPath())->fit(800, 800);
                break;
            default:
        }

        $croppedImage->encode();

        // upload the image to s3
        try {
            $s3 = Storage::disk('s3_images');
            $s3->put($path, $croppedImage);
            $url = Storage::cloud()->url($path);
        } catch (\Exception $e) {
            \Log::error('S3 upload exception: ' . $e);
            throw $e;
        }

        // update profile table with image url
        switch ($type) {
            case 'customer_profile':
                User::where('id', $userId)->update(['profile_image' => $url]);
                break;
            case 'artist_profile':
                User::where('id', $userId)->update(['profile_image' => $url]);
                break;
            case 'artist_banner':
                User::where('id', $userId)->update(['profile_image' => $url]);
                break;
            default:
        }

        return $loggedInUser->load('profile');
    }

    public function getByUri($uri)
    {
        return User::where('uri', $uri)->with('profile')->firstOrFail();
    }

    public function getArtists(array $data)
    {
        return User::whereHas('roles', function ($q) {
            $q->where('name', 'artist');
        })->with('profile')->get();
    }
}
