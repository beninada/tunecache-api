<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Playlist;
use App\Models\User;
use App\Models\Track;

class PlaylistController extends Controller
{
        /*
    |--------------------------------------------------------------------------
    | Playlist Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new playlists as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    protected function validator(array $data)
    {
        return Validator::make($data, Playlist::$createRules);
    }

    protected function create(Request $request, $user_id)
    {
        try {

            $data = $request->all();

            $validator = $this->validator($data);

            if ($validator->fails()) {
                return response(['errors' => $validator->errors()->all()], 422);
            }

            $playlist = Playlist::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'user_id' => $user_id,
            ]);

            $playlist->save();
            return $playlist;

        } catch (\Exception $e) {
            \Log::error('Playlist creation failure: ' . $e);
            return response(['errors' => [$e->getMessage()]], 500);
        }
    }

    public function getPlaylists($user_id)
    {
        return User::findOrFail($user_id)->playlists;
    }

    public function getOne($id)
    {
        return Playlist::where('id', $id)->first();
    }

    public function getTracks($id)
    {
        $playlist = Playlist::findOrFail($id);

        return $playlist->tracks;
    }

    protected function insertTrack(Request $request, $id)
    {
        try {

            $playlist = Playlist::findOrFail($id);

            $track = Track::findOrFail($request['track_id']);

            return $playlist->tracks()->attach($track);

        } catch (\Exception $e) {
            \Log::error('Playlist creation failure: ' . $e);
            return response(['errors' => ['There was a problem adding a track to your playlist']], 500);
        }
    }

}
