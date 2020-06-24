<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    /**
     * Unauthenticated routes
     */
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@create');
    Route::get('users/{role}/{uri}/profile', 'UserController@show');
    Route::get('users', 'UserController@index');
    Route::post('password/forgot', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    Route::get('tracks', 'TrackController@get');
    Route::get('tracks/{uuid}', 'TrackController@getOne');

    Route::get('users/{user_id}/playlists', 'PlaylistController@getPlaylists');
    Route::get('playlists/{id}', 'PlaylistController@getOne');
    Route::get('playlists/{id}/tracks', 'PlaylistController@getTracks');

    /**
     * Authenticated routes
     */
    Route::middleware('auth:api')->group(function () {
        Route::get('logout', 'Auth\LoginController@logout');
        Route::get('me', 'Auth\LoginController@check');
    });

    /**
     * Authenticated routes requiring self authentication check
     */
    Route::middleware(['auth:api', 'auth.self'])->group(function () {
        Route::match(['put', 'patch'], 'users/{user_id}', 'UserController@update');
        Route::match(['put', 'patch'], 'users/customers/{user_id}/profile', 'UserController@updateCustomerProfile');
        Route::match(['put', 'patch'], 'users/artists/{user_id}/profile', 'UserController@updateArtistProfile');
        Route::post('users/{user_id}/images', 'UserController@uploadImage');

        Route::post('tracks/upload', 'TrackController@upload');
        Route::match(['put', 'patch'], 'tracks', 'TrackController@update');
        Route::post('tracks/{id}/cover-art', 'TrackController@uploadTrackArtwork');

        Route::post('users/{user_id}/playlists', 'PlaylistController@create');
        Route::post('playlists/{id}/tracks', 'PlaylistController@insertTrack');

        Route::get('tracks/{id}/rights', 'TrackController@rights');
        Route::post('tracks/{id}/rights', 'TrackController@setRights');
    });
});
