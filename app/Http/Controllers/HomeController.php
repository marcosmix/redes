<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;


class HomeController extends Controller{

    public function index()
    {
        return response()->json(['bienvenida'=>'Bienvenido a la API creada por nodos: De que se habla']);
    }

}