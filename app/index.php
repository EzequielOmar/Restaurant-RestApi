<?php
//mostrar errores por pantalla
error_reporting(-1);
ini_set('display_errors', 1);
//requerimientos
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require __DIR__ . '/../vendor/autoload.php';
require_once './controladores/usuarioApi.php';
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
    $this->post('[/]', \usuarioApi::class.':CargarUno'/*function (Request $request, Response $response) {    
            $response->getBody()->write("post-> cargar un usuario");
            return $response;
        }*/
    );
    //$this->get('/{id}', \usuarioApi::class . ':traerUno');
    //$this->delete('/', \usuarioApi::class . ':BorrarUno');
    //$this->put('/', \usuarioApi::class . ':ModificarUno');
});

//productos
$app->group('/producto', function () {
    $this->get('[/]',/* \productoApi::class . ':traerTodos'*/function (Request $request, Response $response) {    
            $response->getBody()->write("get-> ver lista de productos");
            return $response;
        }
    );
    $this->post('[/]', /*\productoApi::class . ':CargarUno'*/function (Request $request, Response $response) {    
            $response->getBody()->write("post-> cargar un producto");
            return $response;
        }
    );
    //$this->get('/{id}', \productoApi::class . ':traerUno');
    //$this->delete('/', \productoApi::class . ':BorrarUno');
    //$this->put('/', \productoApi::class . ':ModificarUno');
});
$app->run();