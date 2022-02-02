<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Tratamiento;

class BusquedasController extends Controller
{
    public function abaut()
    {
        return response()->json(Tratamiento::DatosBusquedaTwitter());
    }

    public function BuscarFraseTwitter($frase='vacio')
    {
        return response(Tratamiento::DatosBusquedaTwitter());
    }

}