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

   

    static public function ContarPalabras($query, $conteo_palabras = [])
    {
        foreach ($query as $tw) {
            $text = explode(" ", strtolower($tw->text));

            foreach ($text as $palabra)
                if (!array_key_exists($palabra, $conteo_palabras))
                    $conteo_palabras[$palabra] = 1;
                else
                    $conteo_palabras[$palabra] += 1;
            
        }
        
        return $conteo_palabras;
    }

    static public function ContarPalabrasMedios($query, $conteo_palabras = [])
    {
        foreach ($query as $tw) {
          
            $text = explode(" ", strtolower($tw['cuerpo']));

            foreach ($text as $palabra)
                if (!array_key_exists($palabra, $conteo_palabras))
                    $conteo_palabras[$palabra] = 1;
                else
                    $conteo_palabras[$palabra] += 1;
        }

        return $conteo_palabras;
    }

    static public function Hashctac($array)
    {
        $hashtac=array();
        foreach($array as $elemento)
        $hashtac=$elemento->text;

        return $hashtac;
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

    static public function UnificarResultados($resultados)
    {
        $D=count($resultados);die;

        $resultadosUnificados = $resultados[0]->statuses;
        for ($i = 0; $i < $D - 1; $i++)
            array_merge((array)$resultadosUnificados, (array)$resultados[$i]->statuses);
    }

    static public function OrdenarResultadosTwitter($twtts,$presicion=15,$parametro="retweet")
    {
        var_dump($twtts);die;
        
    }

    static public function ConsultaBusquedaTwitter($query)
    {
        $consulta_tratada=array();
        
        //var_dump(Tratamiento::UnificarResultados($consultas));
        
        $consultas=$query[0];
        $conteo_likes = 0;
        $conteo_retewtts = 0;


        foreach($consultas as $consulta)
        foreach($consulta->statuses as $tw)
        {
            
            $url="https://twitter.com/". $tw->user->screen_name."/status/{$tw->id}";
            //if(isset($tw->entities->urls[0]->url))
            //$url= urldecode($tw->entities->urls[0]->url);

        
            $twitt=[
                'id_twitt' => $tw->id,
                'id_user' => $tw->user->id,
                'url' => $url,
                'url_user'=> "https://twitter.com/{$tw->user->screen_name}",
                'cuerpo' => $tw->text,
                'menciones' => Tratamiento::Menciones($tw->entities->user_mentions),
                'hashtag' =>Tratamiento::Hashctac($tw->entities->hashtags),
                'fecha' =>Tratamiento::FormateoFecha($tw->created_at),
                'id_str' => $tw->id_str,
                'name' => $tw->user->name,
                'name_screen' => $tw->user->screen_name,
                'foto_perfil'=>$tw->user->profile_image_url,
                'retweet'=> $tw->retweet_count,
                'likes'=> isset($tw->retweeted_status->favorite_count) ? $tw->retweeted_status->favorite_count : $tw->favorite_count,

            ];
            array_push($consulta_tratada,$twitt);
            $conteo_likes += $twitt['retweet'];
            $conteo_retewtts += $twitt['likes'];
        }
        
        

        $estadistica=[
            'palabrasClaves'=> $query['estadisticas'],
            'likes'=>$conteo_likes,
            'retweet' => $conteo_retewtts,

            ];
        
        
      

        $data=['listadoTwitter'=>$consulta_tratada,'estadistica'=>$estadistica];
        //return $consulta_tratada;
    
        
        return $data;
    }

}