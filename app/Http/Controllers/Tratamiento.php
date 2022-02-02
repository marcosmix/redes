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
}