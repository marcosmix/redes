<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;


class Tratamiento extends Controller{

    static public function DatosBusquedaTwitter()
    {
       $twts=file_get_contents("../resources/datosTW.json");
      
       //var_dump($twts);die;
       return $twts;
    }

    static public function Hashctac($array)
    {
        $hashtac=array();
        foreach($array as $elemento)
        $hashtac=$elemento->text;

        return $hashtac;
    }

    static public function ConsultaBusquedaTwitter($consulta)
    {
        $consulta_tratada=array();
  
        foreach($consulta->statuses as $tw)
        {
            
          
            $twitt=[
                'id_twitt' => $tw->id,
                'id_twitt' => $tw->user->id,
                'url' => "www.twitter.com",
                'cuerpo' => $tw->text,
                //'menciones' => $tw->menciones,
                'hashtag' =>Tratamiento::Hashctac($tw->entities->hashtags),
                'fecha' => $tw->created_at,
                'menciones' =>"usuario mencionado",
                'id_str' => $tw->id_str,
                'name' => $tw->user->name,
                //'screen_name' => $tw->screen_name,
                //'description' => $tw->description
                
            ];
            array_push($consulta_tratada,$twitt);
        }
     
        return $consulta_tratada;
        
    }

}