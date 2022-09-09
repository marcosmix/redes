<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Abraham\TwitterOAuth\TwitterOAuth;

class ConexionController extends Controller{

    static public function ConectarTwitter()
    {
       
        $connection = new TwitterOAuth(
            env('consumer_api_key'),
            env('consumer_api_key_secret'),
            env('access_token'),
            env('access_token_secre')
        );

        return $connection;
    }
}