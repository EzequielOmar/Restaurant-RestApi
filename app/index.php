<?php

//mostrar errores por pantalla
error_reporting(-1);
ini_set('display_errors', 1);

//requerimientos
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
//use Psr\Http\Server\RequestInterface;
//use Psr\Http\Server\RequestHandlerInterface;

require __DIR__ . '/../vendor/autoload.php';
require_once './controladores/usuarioApi.php';
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

//usuarios
$app->group('/usuario', function () {
    $this->get('[/]', \usuarioApi::class.':TraerTodos');
    $this->post('[/]', \usuarioApi::class.':CargarUno');
    //$this->get('/{id}', \usuarioApi::class . ':traerUno');
    //$this->delete('/', \usuarioApi::class . ':BorrarUno');
    //$this->put('/', \usuarioApi::class . ':ModificarUno');
});

//productos
$app->group('/producto',function () {
    $this->get('[/]', \productoApi::class . ':traerTodos');
    $this->post('[/]', \productoApi::class . ':CargarUno');
    //$this->get('/{id}', \productoApi::class . ':traerUno');
    //$this->delete('/', \productoApi::class . ':BorrarUno');
    //$this->put('/', \productoApi::class . ':ModificarUno');
});

//mesas
$app->group('/mesa',function () {
    $this->get('[/]', \mesaApi::class . ':traerTodos');
    $this->post('[/]', \mesaApi::class . ':CargarUno');
    //$this->get('/{id}', \mesaApi::class . ':traerUno');
    //$this->delete('/', \mesaApi::class . ':BorrarUno');
    //$this->put('/', \mesaApi::class . ':ModificarUno');
});

//pedidos
$app->group('/pedido',function () {
    $this->get('[/]', \pedidoApi::class . ':traerTodos');
    $this->post('[/]', \pedidoApi::class . ':CargarUno');
    //$this->get('/{id}', \mesaApi::class . ':traerUno');
    //$this->delete('/', \mesaApi::class . ':BorrarUno');
    //$this->put('/', \mesaApi::class . ':ModificarUno');
});

$app->run();