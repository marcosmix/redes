<?php
namespace App\Http\Controllers;

use DateTime;
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

    static public function mm_numero($mes)
    {
        switch($mes)
        {

        }
    }
    static public function FormateoFecha($fecha)
    {
        $fecha=new DateTime($fecha);
        $fecha=$fecha->format('d-m-Y');
        $fecha_array=explode('-',$fecha);
        $fecha_array=implode("/",$fecha_array);
        
        return $fecha_array;
    }

    static public function ConvertirFraseEnConsulta($frase)
    {
        $query=$frase[0];
        $N=count($frase);

        for($i=1;$i<$N-1;$i++)
        $query=$query."%20".$frase[$i];

        if($N>1)
        $query= $query . "%20" . $frase[$N-1];

        //echo $query;die;
        return $query;
    }

    static public function Menciones($menciones)
    {
        
        $usuarios_menciondos=array();
        foreach($menciones as $mencion)
             array_push($usuarios_menciondos,$mencion->name);
        return $usuarios_menciondos;
    }

    static public function ConsultaBusquedaTwitter($consulta)
    {
        $consulta_tratada=array();
        

        foreach($consulta->statuses as $tw)
        {
            $url="";
            if(isset($tw->entities->urls[0]->url))
            $url= urldecode($tw->entities->urls[0]->url);
            
            $twitt=[
                'id_twitt' => $tw->id,
                'id_twitt' => $tw->user->id,
                'url' => $url,
                'cuerpo' => $tw->text,
                'menciones' => Tratamiento::Menciones($tw->entities->user_mentions),
                'hashtag' =>Tratamiento::Hashctac($tw->entities->hashtags),
                'fecha' =>Tratamiento::FormateoFecha($tw->created_at),
                'id_str' => $tw->id_str,
                'name' => $tw->user->name,
                
            ];
            array_push($consulta_tratada,$twitt);
        }
     
        return $consulta_tratada;
        
    }

}