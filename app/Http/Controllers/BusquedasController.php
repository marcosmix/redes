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
        return explode('%20',$frase);
    }


    public function ultimosDias($connection,$query, $D = 3,$cantPorFecha=15)
    {
        date_default_timezone_set('America/Argentina/San_Juan');
        $fecha=date("Y-m-d");
        $resultados=array();
        $resultadosUnificados=array();

        for($i=0;$i<$D;$i++)
            array_push($resultados,$connection->get("search/tweets", ['q' => $query, 'count' =>$cantPorFecha, 'exclude_replies' => true, 'lang' => 'es','until'=>date("Y-m-d",strtotime($fecha."-".$i."days"))]));

        $resultadosUnificados=$resultados[0]->statuses;
        for($i=0;$i<$D-1;$i++)
        array_merge((array)$resultadosUnificados,(array)$resultados[$i]->statuses);

        return $resultados;
    }

    public function testBuscarFraseTwitter($frase = 'vacio')
    {
       
        $connection=ConexionController::ConectarTwitter();
        //$content = $connection->get("account/verify_credentials");
        //$consulta=$connection->get('statuses/home_timeline',['count'=>25,'exclude_replies'=>true]);

        $query=Tratamiento::ConvertirFraseEnConsulta($frase=$this->ConvertirCadena_Array($frase));
        return response()->json(Tratamiento::ConsultaBusquedaTwitter($this->ultimosDias($connection, $query)));
    }
}
