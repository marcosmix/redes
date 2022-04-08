<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Foreach_;

class EstadisticasController extends Controller
{

    static public function  SepararConteoTwitter($listado)
    {
        $hashtag = [];
        $mentions = [];
        $words = [];

        foreach ($listado as $key => $item) {
            if (isset($key[0]))
                $inicial = $key[0];
            else
                $inicial = "";

            switch ($inicial) {
                case '@':
                    array_push($mentions, [$key => $item]);
                    break;
                case '#':
                    array_push($hashtag, [$key => $item]);
                    break;
                default:
                    array_push($words, [$key => $item]);
                    break;
            }
        }




        $hashtag = EstadisticasController::NormalizarResultados($hashtag);
        $mentions = EstadisticasController::NormalizarResultados($mentions);
        $words = EstadisticasController::NormalizarResultados($words);

        return ['hashtag' => $hashtag, 'mentions' => $mentions, 'words' => $words];
    }

    static public function  NormalizarResultados($resultado)
    {
        $resultados_normalizados = [];
        
        
                        

        foreach ($resultado as $r)
            foreach ($r as $key => $a)
                array_push($resultados_normalizados, ['palabra' => $key, 'cantidad' => $a]);
                   
        
        return $resultados_normalizados;
    }

    static public function OrdenarResultadosTwitter($listado)
    {
        $listado['hashtag'] = EstadisticasController::Ordenamiento($listado['hashtag']);
        $listado['mentions'] = EstadisticasController::Ordenamiento($listado['mentions']);
        $listado['words'] = EstadisticasController::Ordenamiento($listado['words']);

        return $listado;
    }

    static public function SacarPrcentajesPalabrasclabes($listado)
    {
        $listado['hashtag'] = EstadisticasController::CalculoPorcentajePorArray($listado['hashtag'],$listado['CantidadTotalHasgtag']);
        $listado['mentions'] = EstadisticasController::CalculoPorcentajePorArray($listado['mentions'], $listado['CantidadTotalMentions']);
        $listado['words'] = EstadisticasController::CalculoPorcentajePorArray($listado['words'], $listado['CantidadTotalWords']);

        return $listado;
    }

    static public function CalculoPorcentajePorArray($array, $N)
    {

        for ($i = 0; $i < count($array); $i++)
            $array[$i]['promedio'] =   round($array[$i]['cantidad'] * 100 / $N);
        return $array;
    }

    static public function Ordenamiento($listado)
    {
        $aux = null;
        $N = count($listado);

      

        for ($i = 0; $i < $N - 1; $i++)
            for ($j = 0; $j < $N - $i - 1; $j++)
                if ($listado[$j]['cantidad'] < $listado[$j + 1]['cantidad']) {
                    $aux = $listado[$j];
                    $listado[$j] = $listado[$j + 1];
                    $listado[$j + 1] = $aux;
                }



        return $listado;
    }

    static public function FiltrarPalabrasBasura($listado)
    {
       //var_dump($listado);
        $filtro_basura=[];
        $filtro_basura_array=[];

        foreach($listado as $palabra){
         if (strlen($palabra['palabra']) > 3||$palabra['palabra']=='fmi' || $palabra['palabra'] == 'FMI') 
            if($palabra['palabra'] != 'para'&& $palabra['palabra'] != 'fmi,')
                if(!str_starts_with($palabra['palabra'], 'https'))
                 $filtro_basura[$palabra['palabra']]=$palabra;
        }

        foreach($filtro_basura as $key)
        array_push($filtro_basura_array,$key);
        
      
        return $filtro_basura_array;

    }

    static public function AcortarResultados($listado, $cantidad = 5)
    {

        $listado['words']=EstadisticasController::FiltrarPalabrasBasura($listado['words']);
        
        $listado['hashtag'] = array_slice($listado['hashtag'], 0, $cantidad);
        $listado['mentions'] = array_slice($listado['mentions'], 0, $cantidad);
        $listado['words'] = array_slice($listado['words'], 0, $cantidad);

        return $listado;
    }

    static public function SumarCantidades($listado)
    {
        // var_dump($listado);die;
        $total = 0;
        foreach ($listado as $item)
            $total += $item['cantidad'];
        return $total;
    }

    static public function AgregarTotalesListadoPalabras($listado)
    {
        $listado['CantidadTotalHasgtag'] = EstadisticasController::SumarCantidades($listado['hashtag']);
        $listado['CantidadTotalMentions'] = EstadisticasController::SumarCantidades($listado['mentions']);
        $listado['CantidadTotalWords'] = EstadisticasController::SumarCantidades($listado['words']);
        return $listado;
    }


    
}
