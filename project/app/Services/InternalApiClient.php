<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternalApiClient
{
    public static $client = null;

    public static function setup()
    {
        self::$client = DB::table('oauth_clients')->whereNull('user_id')->where('password_client', 1)->first();
    }

    public static function getClientSecret()
    {
        if (self::$client === null) {
            self::setup();
        }

        return self::$client->secret;
    }

    public static function getClientId()
    {
        if (self::$client === null) {
            self::setup();
        }

        return self::$client->id;
    }

    public static function login($email, $password)
    {
        $params = [
            'grant_type' => 'password',
            'client_id' => self::getClientId(),
            'client_secret' => self::getClientSecret(),
            'username' => $email,
            'password' => $password,
            'client_ip_address' => \Illuminate\Support\Facades\Request::ip(),
            'scope' => '*',
        ];

        $r = Request::create('/oauth/token', 'POST', $params);

        $response = app()->handle($r);

        return $response;
    }

    public static function refreshToken($token)
    {
        $params = [
            'grant_type' => 'refresh_token',
            'client_id' => self::getClientId(),
            'client_secret' => self::getClientSecret(),
            'refresh_token' => $token,
            'scope' => '*',
        ];

        $r = Request::create('/oauth/token', 'POST', $params);
        $response = app()->handle($r);

        return $response;
    }

    public static function personalToken($request)
    {
        $r = Request::create('/oauth/personal-access-tokens', $request->method(), $request->all());
        $response = app()->handle($r);

        return $response;
    }

    public static function revokePersonalToken($request)
    {
        $r = Request::create('/oauth/personal-access-tokens/'.$request->token, $request->method(), $request->all());
        $response = app()->handle($r);

        return $response;
    }

    public static function scopes($request)
    {
        $r = Request::create('/oauth/scopes', $request->method(), $request->all());
        $response = app()->handle($r);

        return $response;
    }
}
