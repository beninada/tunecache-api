<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3_default'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3_default' => [
            'driver' => 's3',
            'key' => env('AWS_S3_ADMIN_ACCESS_KEY_ID'),
            'secret' => env('AWS_S3_ADMIN_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_DEFAULT_BUCKET'),
            'url' => env('AWS_DEFAULT_CDN_URL'),
        ],

        's3_cover' => [
            'driver' => 's3',
            'key' => env('AWS_S3_ADMIN_ACCESS_KEY_ID'),
            'secret' => env('AWS_S3_ADMIN_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_TRACK_COVER_BUCKET'),
            'url' => env('AWS_TRACK_COVER_CDN_URL'),
        ],

        's3_images' => [
            'driver' => 's3',
            'key' => env('AWS_S3_ADMIN_ACCESS_KEY_ID'),
            'secret' => env('AWS_S3_ADMIN_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_IMAGE_BUCKET'),
            'url' => env('AWS_IMAGE_CDN_URL'),
        ],

        's3_tracks' => [
            'driver' => 's3',
            'key' => env('AWS_S3_ADMIN_ACCESS_KEY_ID'),
            'secret' => env('AWS_S3_ADMIN_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_TRACK_BUCKET'),
            'url' => env('AWS_TRACK_CDN_URL'),
        ],

    ],

];
