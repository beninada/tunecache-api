<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Track;
use App\Models\User;

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
        $tracks = User::where('id', $request->user_id)->first()->tracks;

        if (!$tracks) {
            return [];
        }

        return $tracks;
    }

    public function getOne(Request $request, $uuid)
    {
        $track = Track::where('uuid', $uuid)->first();

        if (!$track) {
            return response(['errors' => ['Track not found.']], 422);
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
        $track->save();

        return $track;
    }
}
