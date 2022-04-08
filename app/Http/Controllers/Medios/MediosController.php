<?php

namespace App\Http\Controllers\Medios;


use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Medios;
use App\Http\Controllers\Tratamiento;
use App\Http\Controllers\EstadisticasController;

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
        {    
            if(Medios::BuscarFrase($twitt,$frase))
                array_push($listado_twitts_con_coincidencias,$twitt);
        }
            
        return $listado_twitts_con_coincidencias;       
    }

    public function Estadisitcas($listado)
    {
      
        $conteo_likes=0;
        $conteo_retewtts=0;

        foreach ($listado as $twitt) {
                $conteo_likes += $twitt['likes'];
                $conteo_retewtts +=$twitt['retweet'];
            }

            
            //estadisticas --------------------
            $conteo_palabras = [];
            
            
            $conteo_palabras = Tratamiento::ContarPalabrasMedios($listado, $conteo_palabras);

        $resultados_estadisticas = EstadisticasController::SepararConteoTwitter($conteo_palabras);

        $resultados_estadisticas = EstadisticasController::OrdenarResultadosTwitter($resultados_estadisticas);
        
        $resultados_estadisticas = EstadisticasController::AcortarResultados($resultados_estadisticas, 5);
        
        
        $resultados_estadisticas = EstadisticasController::AgregarTotalesListadoPalabras($resultados_estadisticas);
        $resultados_estadisticas = EstadisticasController::SacarPrcentajesPalabrasclabes($resultados_estadisticas);
        //----------------------------------
        
        $estadistica = [
            'palabrasClaves' => $resultados_estadisticas ,
            'likes' => $conteo_likes,
            'retweet' => $conteo_retewtts,

        ];
        
        return $estadistica;
        
    }

    

}
