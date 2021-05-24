<?php
//mostrar errores por pantalla
error_reporting(-1);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

require_once './middlewares/autenticate.php';

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
$config['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('La pagina no existe. Te fuiste de Comanda app!');
    };
};

$app = new \Slim\App($config);


//Sector publico de la app
$app->group('',function(){
    //home
    $this->get('/',function($req,$res,$args){
        return $res->withJson("home",200);
    })->add(new Autenticate($this->getContainer()->get('router')));
    //log cliente
    $this->get('/login',function($req,$res,$args){
        return $res->withJson("pagina formulario login cliente",200);
    })->setName('login');
    $this->post('/login',\clienteApi::class . ':Loguear');
    //registro cliente
    $this->get('/registro',function($req,$res,$args){
        return $res->withJson("pagina formulario registro cliente",200);
    });
    $this->post('/registro',\clienteApi::class .':CargarUno');
    //realizar pedido
    $this->post('/pedir', \pedidoApi::class . ':CargarUno');
});

//Sector privado de la app
$app->group('/staff',function(){
    //-> /staff
    $this->group('', function () {
        //home staff
        $this->get('[/]',function($req,$res,$args){
            return $res->withJson("home staff",200);
        })->add(new Autenticate($this->getContainer()->get('router')));
        //login staff
        $this->get('/login',function($req,$res,$args){
            return $res->withJson("pagina formulario login staff",200);
        })->setName('login');
        $this->post('/login', \staffApi::class.':Loguear');
        //alta staff
        $this->post('/alta', \staffApi::class.':CargarUno');
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
        //$this->get('/{id}', \mesaApi::class . ':traerUno');
        //$this->delete('/', \mesaApi::class . ':BorrarUno');
        //$this->put('/', \mesaApi::class . ':ModificarUno');
    });
});


$app->run();