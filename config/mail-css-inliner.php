<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mail CSS Inliner
    |--------------------------------------------------------------------------
    |
    | CSS files to include in every sent e-mail.
    | Use absolute paths, for example: `resource_path('mail/css/style.css')`.
    |
    */

    'mail-css-inliner' => [
        'transport' => env('MAIL_DRIVER'),
        'host' => env('MAIL_HOST'),
        'port' => env('MAIL_PORT'),
        'encryption' => env('MAIL_ENCRYPTION'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'sender' => env('MAIL_FROM_NAME'),
        'timeout' => null,
        'auth_mode' => null,
        'verify_peer' => false,
    ],

];
