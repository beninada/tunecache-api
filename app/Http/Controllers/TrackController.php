<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Track;

class TrackController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'tracks' => ['required'],
        ]);
    }

    public function store(Request $request)
    {
    }

    public function upload(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $files = $request->file('tracks');
        $s3 = Storage::disk('s3_tracks');
        $tracks = [];

        foreach ($files as $file) {
            $uuid = (string) Str::uuid();

            // Upload the file to S3 with uuid as name
            $s3->put($uuid, file_get_contents($file), 'public');

            // Store the track in local database
            $tracks[] = Track::create([
                'uuid' => $uuid,
                'user_id' => $request->user_id,
            ]);
        }

        return $tracks;
    }
}
