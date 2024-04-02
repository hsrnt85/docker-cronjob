<?php

class Auth
{

    public static function create_token(){

        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = substr(str_shuffle($permitted_chars), 0, 20);

        return $token;
    }

}
