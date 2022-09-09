<?php

namespace App\Models;
use App\Http\Controllers\Tratamiento;


class Medios
{
    private $listadoDeTwits;

    // public function __construct($query)
    // {
    //     $this->listadoDeTwits=$query;
    // }

    static function Convertir_Consulta_En_Array($query)
    {
        $consulta_tratada=[];
        //var_dump($query);die;
        foreach ($query as $tw) {

            $url = "https://twitter.com/" . $tw->user->screen_name . "/status/{$tw->id}";
            //if(isset($tw->entities->urls[0]->url))
            //$url= urldecode($tw->entities->urls[0]->url);


            $twitt = [
                'id_twitt' => $tw->id,
                'id_user' => $tw->user->id,
                'url' => $url,
                'url_user' => "https://twitter.com/{$tw->user->screen_name}",
                'cuerpo' => $tw->text,
                'menciones' => Tratamiento::Menciones($tw->entities->user_mentions),
                'hashtag' => Tratamiento::Hashctac($tw->entities->hashtags),
                'fecha' => Tratamiento::FormateoFecha($tw->created_at),
                'id_str' => $tw->id_str,
                'name' => $tw->user->name,
                'name_screen' => $tw->user->screen_name,
                'foto_perfil' => $tw->user->profile_image_url,
                'retweet' => $tw->retweet_count,
                'likes' => isset($tw->retweeted_status->favorite_count) ? $tw->retweeted_status->favorite_count : $tw->favorite_count,

            ];
            array_push($consulta_tratada, $twitt);
           
        }
        return $consulta_tratada;
    }

    static public function BuscarFrase($twitt, $frase)
    {
        
        $posicion_coincidencia = stripos($twitt['cuerpo'], $frase,0);
          
        if ($posicion_coincidencia === false)
        return false;
        else return true;
        
    }
}

?>