<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Track;
use App\Models\User;
use Intervention\Image\ImageManager;

class TrackController extends Controller
{
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tracks' => ['required'],
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $files = $request->file('tracks');
        $s3 = Storage::disk('s3_tracks');
        $tracks = [];

        try {
            foreach ($files as $file) {
                $uuid = (string) Str::uuid();

                // Upload the file to S3 with uuid as name
                $s3->put('tracks/'.$uuid, file_get_contents($file), 'public');

                // Store the track in local database
                $tracks[] = Track::create([
                    'uuid' => $uuid,
                    'user_id' => $request->user_id,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Track(s) upload failure: ' . $e);
            return response(['errors' => [$e->getMessage()]], 500);
        }

        return $tracks;
    }

    public function get(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $tracks = $user->tracks;

        return $tracks;
    }

    public function getOne(Request $request, $uuid)
    {
        $track = Track::where('uuid', $uuid)->first();

        if (!$track) {
            return response(['errors' => ['Track not found.']], 404);
        }

        return $track;
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => ['required', 'exists:tracks'],
            'title' => ['required', 'string'],
            'bpm' => ['numeric'],
            'key' => ['string', Rule::in(Track::$musicalKeys)],
            'scale' => ['string', Rule::in(Track::$musicalScales)],
            'description' => ['string'],
            'duration' => ['numeric']
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $track = Track::where('uuid', $request->uuid)->first();

        $track->title = $request->title;
        $track->uri = Str::slug($request->title, '-');
        $track->bpm = $request->bpm;
        $track->key = $request->key;
        $track->scale = $request->scale;
        $track->description = $request->description;
        $track->duration = $request->duration;
        $track->save();

        return $track;
    }

    public function uploadTrackArtwork(Request $request, $track_id)
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required', 'max:10240', 'mimes:jpeg,jpg,png'],
            'user_id' => ['required', 'exists:users,id'],
            'track_id' => ['required', 'exists:tracks,id'],
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        // get the logged in user
        $loggedInUser = Auth::user();

        // filename is the user's id concatenated w/ timestamp in folder e.g. dev-images/customer-profile/
        $filename = $loggedInUser->id . '_' . $track_id . '_' . time();
        $file = $request->file;
        $extension = $file->extension();
        $path = $filename . '.' . $extension;

        $manager = new ImageManager(array('driver' => 'imagick'));

        $croppedImage = $manager->make($file->getRealPath())->fit(800, 800);

        $croppedImage->encode();

        // upload the image to s3
        try {
            Storage::disk('s3_cover')->put($path, $croppedImage);
            $url = env('AWS_TRACK_COVER_ROUTE_53') . $path;
        } catch (\Exception $e) {
            \Log::error('S3 upload exception: ' . $e);
            throw $e;
        }

        return Track::where('id', $track_id)->update(['cover_image' => $url]);
    }
}
