<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->get('/','HomeController@index');
$router->get('/buscar/tw/{frase}', ' @BuscarFraseTwitter');
$router->get('/buscar/tw-test/{frase}', 'BusquedasController@testBuscarFraseTwitter');
$router->get('/buscar/tw-medios-test/{frase}','BusquedasController@BuscarFrasTwitterMedios');
