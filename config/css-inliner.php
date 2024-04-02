<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Css Files
    |--------------------------------------------------------------------------
    |
    | Css file of your style for your emails
    | The content of these files will be added directly into the inliner
    | Use absolute paths, ie. public_path('css/main.css')
    |
    */

    'css_inliner' => [
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
