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
        return explode('%',$frase);
    }

    public function testBuscarFraseTwitter($frase = 'vacio')
    {
       
        $connection=ConexionController::ConectarTwitter();
        $content = $connection->get("account/verify_credentials");
        //esto funciona 
        //$consulta=$connection->get('statuses/home_timeline',['count'=>25,'exclude_replies'=>true]);
       $frase=$this->ConvertirCadena_Array($frase);
        $consulta = $connection->get("search/tweets", ['q' => '#' . $frase[0]]);
        
        return response()->json(Tratamiento::ConsultaBusquedaTwitter($consulta));
    }
}
