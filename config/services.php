<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'nextcloud' => [
    'base_url' => env('NEXTCLOUD_BASE_URL', 'https://nextcloud.kfifood.com'),
    'username' => env('NEXTCLOUD_USERNAME', 'induction'),
    'password' => env('NEXTCLOUD_PASSWORD', '@Human2025'),
    'webdav_path' => env('NEXTCLOUD_WEBDAV_PATH', '/remote.php/dav/files/induction'),
    'folders' => [
        'photos' => 'foto',
        'cvs' => 'cv',
        'cover_letters' => 'cover',
        'certificates' => 'certificate'
    ]
],
];
