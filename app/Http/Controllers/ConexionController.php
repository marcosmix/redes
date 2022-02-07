<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Abraham\TwitterOAuth\TwitterOAuth;

class ConexionController extends Controller{

    static public function ConectarTwitter()
    {
        $consumer_api_key = 'RcPrp0xpAkFo0f9VUMVedxA43';
        $consumer_api_key_secret = '70hfrTBDwGtPsXg6ul4aK282486ADuxHVn1YfALs8FDITFTVfK';
        $authentication_tokens_bearer = 'AAAAAAAAAAAAAAAAAAAAAA%2FuYgEAAAAA3Zr%2Bs8TGkhayI6SnttL4qw4J6GE%3DkZuvjPCurOMbCJ4eAhaKEGaiZVmlqLvQtcOkAm3SeeiJb98BTn';
        $access_token = '1427480618661728256-jauOdcazJtrP8E5d0viIkkfFGWiL5J';
        $access_token_secret = 'ZyQIcWc9u0exilBdWep0xCZldyzCquJB61bGeV8g8DjRY';
        $connection = new TwitterOAuth(
            $consumer_api_key,
            $consumer_api_key_secret,
            $access_token,
            $access_token_secret
        );

        return $connection;
    }
}