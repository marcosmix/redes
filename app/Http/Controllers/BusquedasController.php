<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Tratamiento;
use App\Http\Controllers\ConexionController;


class BusquedasController extends Controller
{
    public function abaut()
    {
        return response()->json(Tratamiento::DatosBusquedaTwitter());
    }

    public function BuscarFraseTwitter($frase = 'vacio')
    {
        return response(Tratamiento::DatosBusquedaTwitter());
    }

    private function ConvertirCadena_Array($frase)
    {
        return explode('%20', $frase);
    }

    private function EliminarRepetidos($query,$parametro= 'text')
    {
        $N = count($query);

        $array_sin_repetir=[];
        $query_sin_repetir=[];

        for ($i = 0; $i < $N - 2; $i++)
            if(!array_key_exists($query[$i]->text,$array_sin_repetir))
                {
                    $array_sin_repetir[$query[$i]->text]=$i;
                    $query_sin_repetir[]=$query[$i];
                }

        
        return $query_sin_repetir;

        //     if($query[$i]->text== $query[$i+1]->text)
        //     {
        //         unset($query[$i+1]);
        //         //var_dump($query[$i]->text);
        //         //var_dump($query[$i+1]->text);
        //         //die;
        //     }

        // }

        return $query;
    }

    private function OrdenarResultadosTwitter($query, $presicion = 15, $parametro = "retweet_count",$parametro2='')
    {
        
        $aux = null;
        $N = count($query);
        
        for ($i = 0; $i < $N - 1; $i++)
            for ($j = 0; $j < $N - $i - 1; $j++)
            {
                if ($query[$j]->retweet_count < $query[$j + 1]->retweet_count) {
                    if ($query[$j]->retweet_count < $query[$j + 1]->retweet_count) {
                        $aux = $query[$j];
                        $query[$j] = $query[$j + 1];
                        $query[$j + 1]= $aux;}
                        
                    }

                if ($query[$j]->retweet_count == $query[$j + 1]->retweet_count)
                if ($query[$j]->favorite_count < $query[$j + 1]->favorite_count) {
                         $aux = $query[$j];
                        $query[$j] = $query[$j + 1];
                        $query[$j + 1] = $aux;
                    }
                 
            }

        $query=$this->EliminarRepetidos($query);
        return array_slice($query, 0, $presicion);
    }

    public function ultimosDias($connection, $query, $D = 3, $cantPorFecha = 50)
    {
        date_default_timezone_set('America/Argentina/San_Juan');
        $fecha = date("Y-m-d");
        $resultados = array();
        $resultadosUnificados = array();
       //var_dump(date("Y-m-d", strtotime($fecha . "+1days")));
       
       
       array_push($resultados, $connection->get("search/tweets", ['q' => $query, 'count' => $cantPorFecha, 'exclude_replies' => true, 'lang' => 'es', 'until' => date("Y-m-d", strtotime($fecha . "+1days"))]));
        for ($i = 0; $i < $D-1; $i++)
        {
            //var_dump(date("Y-m-d", strtotime($fecha . "-" . $i . "days")));
            array_push($resultados, $connection->get("search/tweets", ['q' => $query, 'count' => $cantPorFecha, 'exclude_replies' => true, 'lang' => 'es', 'until' => date("Y-m-d", strtotime($fecha . "-" . $i . "days"))]));
        }
        
        foreach ($resultados as $r)
            $r->statuses = $this->OrdenarResultadosTwitter($r->statuses);

        $resultadosUnificados = $resultados[0]->statuses;
        for ($i = 0; $i < $D - 1; $i++)
            array_merge((array)$resultadosUnificados, (array)$resultados[$i]->statuses);

        return $resultados;
    }

    public function testBuscarFraseTwitter($frase = 'vacio')
    {
        $connection = ConexionController::ConectarTwitter();

        $query = Tratamiento::ConvertirFraseEnConsulta($frase = $this->ConvertirCadena_Array($frase));

        return response()->json(Tratamiento::ConsultaBusquedaTwitter($this->ultimosDias($connection, $query)));
    }
}
