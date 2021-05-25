<?php
//mostrar errores por pantalla
error_reporting(-1);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

require_once './middlewares/autenticate.php';
require_once './middlewares/isStaff.php';

require_once './controladores/listado.php';
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
            ->write('La pagina no existe. Te fuiste de Comanda-app!');
    };
};

$app = new \Slim\App($config);


//Sector publico de la app
$app->group('',function(){
    //home
    $this->get('/',function($req,$res,$args){
        return $res->withJson("home page view.",200);
    })->add(new AuthMW($this->getContainer()->get('router')));
    //realizar pedido
    $this->post('/', \pedidoApi::class . ':CargarUno');
    //log cliente
    $this->get('/login',function($req,$res,$args){
        return $res->withJson("login client view",200);
    })->setName('login');
    $this->post('/login',\clienteApi::class . ':Loguear');
    //registro cliente
    $this->get('/registro',function($req,$res,$args){
        return $res->withJson("submit client view",200);
    });
    $this->post('/registro',\clienteApi::class .':CargarUno');
});

//Sector privado de la app
$app->group('/staff',function(){
    //-> /staff
    $this->group('', function () {
        //home 
        $this->get('[/]',\Listado::class.':HomePorSector')
            ->add(new isStaffMW())->add(new AuthMW($this->getContainer()->get('router')));
        //login staff
        $this->get('/login',function($req,$res,$args){
            return $res->withJson("login staff view",200);
        })->setName('staff-login');
        $this->post('/login', \staffApi::class.':Loguear');
        //alta staff
        $this->post('/alta', \staffApi::class.':CargarUno');
    });
    //-> /producto
    $this->group('/producto',function () {
        $this->get('[/]', \productoApi::class . ':traerTodos');
        $this->post('[/]', \productoApi::class . ':CargarUno');
    });
    //-> /mesa
    $this->group('/mesa',function () {
        $this->get('[/]', \mesaApi::class . ':traerTodos');
        $this->post('[/]', \mesaApi::class . ':CargarUno');
    });
    //-> /pedido
    $this->group('/pedido',function () {
        $this->get('[/]', \pedidoApi::class . ':traerTodos');
    });
});


$app->run();