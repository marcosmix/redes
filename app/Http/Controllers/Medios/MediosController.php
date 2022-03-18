<?php

namespace App\Http\Controllers\Medios;


use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Medios;

class MediosController extends  BaseController
{
    private $listadoMedios=['infobae','pagina12', 'lanacion', 'mdzol', 'LosAndesDiario', 'Cronistacom', 'diariodecuyoweb',
        'diariodecuyoweb', 'sanjuan8','DiarioMovilOk', 'diarioD3', 'tiempodesanjuan', 'damenoticias1', 'DiarioElZonda'];
    private $listadoResultadoMedios=[];

    public function GetListadoMedios(){return $this->listadoMedios;}
    
    public function Add_Resulado_Lista_De_Medios($query)
    {
        array_push($this->listadoResultadoMedios, Medios::Convertir_Consulta_En_Array($query));
    }
    public function Get_Listado_Medios_Tratados(){return $this->listadoResultadoMedios;}
    
    public function FitrarPorBuscqueda($frase)
    {
        $listado_twitts_con_coincidencias=[];

        foreach($this->listadoResultadoMedios as $listado_twitts)
        foreach($listado_twitts as $twitt)
            if(Medios::BuscarFrase($twitt,$frase))
            array_push($listado_twitts_con_coincidencias,$twitt);
        return $listado_twitts_con_coincidencias;       
    }
}
