<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ApiClient
{
    public static function get($endpoint)
    {
        return Http::withToken(Session::get('jwt_token'))
        -> get(env('API_BASE_URL') . $endpoint);
    }

    public static function post($endpoint, $data)
    {
        return Http::withToken(Session::get('jwt_token'))
        ->post(env('API_BASE_URL') . $endpoint, $data);
    }

    public static function put($endpoint, $data)
    {
        return Http::withToken(Session::get('jwt_token'))
        ->put(env('API_BASE_URL') . $endpoint, $data);
    }

    public static function delete($endpoint)
    {
        return Http::withToken(Session::get('jwt_token'))
        ->delete(env('API_BASE_URL') . $endpoint);
    }
}