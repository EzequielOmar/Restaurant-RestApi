<?php

//mostrar errores por pantalla
error_reporting(-1);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';
require_once './controladores/staffApi.php';
require_once './controladores/clienteApi.php';
require_once './controladores/productoApi.php';
require_once './controladores/mesaApi.php';
require_once './controladores/pedidoApi.php';

//carga variables de entorno solo en modo dev
if (!isset($_SERVER['APP_ENV'])) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
    $dotenv->safeLoad();
}

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;
$app = new \Slim\App(["settings" => $config]);


//Sector publico de la app
$app->group('',function(){
    //$this->post('[/]',\Loguer::class . ':loguear');
    $this->post('/registro',\clienteApi::class .':CargarUno');
});

//Sector privado de la app
$app->group('/staff',function(){
    //-> /staff
    $this->group('', function () {
        $this->get('[/]', \staffApi::class.':TraerTodos');
        $this->post('[/]', \staffApi::class.':CargarUno');
        //$this->get('/{id}', \staffApi::class . ':traerUno');
        //$this->delete('/', \staffApi::class . ':BorrarUno');
        //$this->put('/', \staffApi::class . ':ModificarUno');
    });
    //-> /producto
    $this->group('/producto',function () {
        $this->get('[/]', \productoApi::class . ':traerTodos');
        $this->post('[/]', \productoApi::class . ':CargarUno');
        //$this->get('/{id}', \productoApi::class . ':traerUno');
        //$this->delete('/', \productoApi::class . ':BorrarUno');
        //$this->put('/', \productoApi::class . ':ModificarUno');
    });
    //-> /mesa
    $this->group('/mesa',function () {
        $this->get('[/]', \mesaApi::class . ':traerTodos');
        $this->post('[/]', \mesaApi::class . ':CargarUno');
        //$this->get('/{id}', \mesaApi::class . ':traerUno');
        //$this->delete('/', \mesaApi::class . ':BorrarUno');
        //$this->put('/', \mesaApi::class . ':ModificarUno');
    });
    //-> /pedido
    $this->group('/pedido',function () {
        $this->get('[/]', \pedidoApi::class . ':traerTodos');
        $this->post('[/]', \pedidoApi::class . ':CargarUno');
        //$this->get('/{id}', \mesaApi::class . ':traerUno');
        //$this->delete('/', \mesaApi::class . ':BorrarUno');
        //$this->put('/', \mesaApi::class . ':ModificarUno');
    });
});


$app->run();