<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Track;

class TrackController extends Controller
{
    protected function uploadValidator(array $data)
    {
        return Validator::make($data, [
            'tracks' => ['required'],
        ]);
    }

    protected function updateValidator(array $data)
    {
        return Validator::make($data, [
            'uuid' => ['required', 'exists:tracks'],
            'title' => ['required', 'string'],
            'bpm' => ['numeric'],
            'key' => ['string'],
            'description' => ['string'],
        ]);
    }

    public function upload(Request $request)
    {
        $validator = $this->uploadValidator($request->all());

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

    public function update(Request $request)
    {
        $validator = $this->updateValidator($request->all());

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $track = Track::where('uuid', $request->uuid)->first();

        $track->update([
            'title' => $request->title,
            'uri' => Str::slug($request->title, '-'),
            'bpm' => $request->bpm,
            'key' => $request->key,
            'description' => $request->description,
        ]);

        return $track;
    }
}
