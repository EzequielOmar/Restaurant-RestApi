<?php
//mostrar errores por pantalla
error_reporting(-1);
ini_set('display_errors', 1);

use Illuminate\Database\Capsule\Manager as Capsule;

require __DIR__ . '/../vendor/autoload.php';
require_once './middlewares/autenticate.php';
require_once './middlewares/isStaff.php';

//require_once './controladores/listado.php'; //LISTADO AFUERA POR AHORIZZZZZZZ

require_once './controladores/productoApi.php';
require_once './controladores/clienteApi.php';
require_once './controladores/staffApi.php';
require_once './controladores/mesaApi.php';
require_once './controladores/pedidoApi.php';

//carga variables de entorno solo en modo dev
if (!isset($_SERVER['APP_ENV'])) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->safeLoad();
}

//App config
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;
$config['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('La pagina no existe. Te fuiste de Comanda-app!');
    };
};

//App instance
$app = new \Slim\App($config);

// Eloquent
$container = $app->getContainer(); 
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $_ENV['MYSQL_HOST'],
    'database'  => $_ENV['MYSQL_DB'],
    'username'  => $_ENV['MYSQL_USER'],
    'password'  => $_ENV['MYSQL_PASS'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

//routes
//Sector publico de la app
$app->group('', function () {
    $this->get('[/]', \productoApi::class . ':TraerTodos')/*->add(new AuthMW($this->getContainer()->get('router')))*/;
    $this->post('[/]', \pedidoApi::class . ':CargarUno');
    $this->get('/login', function ($req, $res, $args) {
        return $res->withJson("login client view", 200);
    })->setName('login');
    $this->post('/login', \clienteApi::class . ':Loguear');
    $this->get('/registro', function ($req, $res, $args) {
        return $res->withJson("submit client view", 200);
    });
    $this->post('/registro', \clienteApi::class . ':CargarUno');
});
//Sector privado de la app
$app->group('/staff', function () {
    $this->get('[/]', /*\Listado::class . ':HomePorSector'*/\StaffApi::class . ':TraerTodos')
        /*->add(new isStaffMW())->add(new AuthMW($this->getContainer()->get('router')))*/;
    $this->get('/login', function ($req, $res, $args) {
        return $res->withJson("login staff view", 200);
    })->setName('staff-login');
    $this->post('/login', \staffApi::class . ':Loguear');
    $this->post('/alta', \staffApi::class . ':CargarUno');
    $this->group('/producto', function () {
        $this->get('[/]', \productoApi::class . ':traerTodos');
        $this->post('[/]', \productoApi::class . ':CargarUno');
    });
    $this->group('/mesa', function () {
        $this->get('[/]', \mesaApi::class . ':traerTodos');
        $this->post('[/]', \mesaApi::class . ':CargarUno');
    });
    $this->group('/pedido', function () {
        $this->get('[/]', \pedidoApi::class . ':traerTodos');
    });
});

$app->run();
