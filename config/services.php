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

    'ispeks' => [
        'agency_username' => env('ISPEKS_AGENCY_USERNAME'),
        'email' => env('ISPEKS_EMAIL'),
        'email_cc' => env('ISPEKS_EMAIL_CC'),
        'public_key' => env('ISPEKS_PUBLIC_KEY'),
        'public_key_id' => env('ISPEKS_PUBLIC_KEY_ID'),
        'private_key' => env('ISPEKS_PRIVATE_KEY'),
        'private_key_id' => env('ISPEKS_PRIVATE_KEY_ID'),
        'passphrase' => env('ISPEKS_PASSPHRASE'),
        'local_folder_in' => resource_path()."/ispeks/in/",
        'local_folder_out' => resource_path()."/ispeks/out/",
        'folder_in' => env('ISPEKS_FOLDER_IN'),
        'folder_out' => env('ISPEKS_FOLDER_OUT')
    ],

];
