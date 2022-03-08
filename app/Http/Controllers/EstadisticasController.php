<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;


class EstadisticasController extends Controller{

    static public function  SepararConteoTwitter($listado)
    {
        $hashtag=[];
        $mentions=[];
        $words=[];

        foreach ($listado as $key => $item)
        {
            if(isset($key[0]))
            $inicial=$key[0];
            else
            $inicial="";

            switch($inicial)
            {
                case '@':array_push($mentions,[$key=>$item]); break;
                case '#':array_push($hashtag, [$key => $item]); break;
                default: array_push($words, [$key => $item]);break;
            }
        }



       
        $hashtag=EstadisticasController::NormalizarResultados($hashtag);
        $mentions = EstadisticasController::NormalizarResultados($mentions);
        $words = EstadisticasController::NormalizarResultados($words);

        return ['hashtag'=>$hashtag,'mentions'=>$mentions,'words'=>$words];
    }

    static public function  NormalizarResultados($resultado)
    {
        $resultados_normalizados=[];

      
        foreach($resultado as $r)
          foreach ($r as $key => $a)
              array_push($resultados_normalizados,['palabra'=>$key,'cantidad'=>$a]);
        
        return $resultados_normalizados;

    }

    static public function OrdenarResultadosTwitter($listado)
    {
        $listado['hashtag']=EstadisticasController::Ordenamiento($listado['hashtag']);
        $listado['mentions'] = EstadisticasController::Ordenamiento($listado['mentions']);
        $listado['words'] = EstadisticasController::Ordenamiento($listado['words']);  
        
        return $listado; 
    }

    static public function Ordenamiento($listado)
    {
        $aux = null;
        $N = count($listado);
    
        for ($i = 0; $i < $N - 1; $i++)
            for ($j = 0; $j < $N - $i - 1; $j++) 
                if ($listado[$j]['cantidad'] < $listado[$j + 1]['cantidad']) 
                    {
                        $aux = $listado[$j];
                        $listado[$j] = $listado[$j + 1];
                        $listado[$j + 1] = $aux;
                    }
                
            
            
      return $listado;

    }

    static public function AcortarResultados($listado, $cantidad=5)
    {

        $listado['hashtag'] = array_slice($listado['hashtag'],0,$cantidad);
        $listado['mentions'] = array_slice($listado['mentions'],0,$cantidad);
        $listado['words'] = array_slice($listado['words'],0,$cantidad);

        return $listado; 
    }


}