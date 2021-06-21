<?php
//mostrar errores por pantalla
error_reporting(-1);
ini_set('display_errors', 1);

use Illuminate\Database\Capsule\Manager as Capsule;

require __DIR__ . '/../vendor/autoload.php';
require_once './utiles/enum.php';
require_once './middlewares/corsMW.php';
require_once './middlewares/authMW.php';
require_once './middlewares/isTipoMW.php';
require_once './controladores/productoApi.php';
require_once './controladores/clienteApi.php';
require_once './controladores/staffApi.php';
require_once './controladores/mesaApi.php';
require_once './controladores/pedidoApi.php';
require_once './controladores/cierreApi.php';


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
unset($app->getContainer()['errorHandler']); //temp despues sobreescribir errohabndler
unset($app->getContainer()['phpErrorHandler']); //temp despues sobreescribir errohabndler
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

/*
use App\Models\Producto;
Producto::truncate();
use App\Models\Staff;
Staff::truncate();
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Mesa;
Cliente::truncate();
Pedido::truncate();
Mesa::truncate();
//LIMPIAR TABLAS Y REINICIAR IDS
*/

//routes
$app->group('', function () {
    $this->get('[/]', \productoApi::class . ':TraerTodos')->setName('home');;
    $this->map(['GET', 'POST'], '/login', \clienteApi::class . ':Loguear')->setName('login');
    $this->map(['GET', 'POST'], '/staff/login', \staffApi::class . ':Loguear')->setName('staff-login');
    $this->map(['GET', 'POST'], '/registro', \clienteApi::class . ':CargarUno');
})->add(new corsMW());
$app->group('', function () {
    $this->group('', function () {
        $this->post('[/]', \pedidoApi::class . ':CargarUno');
        $this->get('/{cod_mesa:[0-9^a-z]+}/{cod_pedido:[0-9^a-z]+}', \pedidoApi::class . ':TraerUno');
        $this->post('/{cod_mesa:[0-9^a-z]+}/{cod_pedido:[0-9^a-z]+}', \pedidoApi::class . ':BorrarUno');
        $this->map(['GET', 'POST'], '/comentario', \cierreApi::class . ':CargarUno')->setName('comentario');
    })/*->add(new isTipoMW(Tipo::cliente))*/;
    $this->group('', function () {
        $this->group('/pedidos', function () {
            $this->get('[/]', \pedidoApi::class . ':TraerTodos'); //hacer //(muestra distinto segun sector, pero para todo staff)
            $this->put('/{id:[0-9]+}', \pedidoApi::class . ':ModificarUno'); //todo staff tmbn
        });
        //para estos tres es solo admin, hacer mw.
        $this->group('/staff', function () {
            $this->get('[/]', \staffApi::class . ':TraerTodos');
            $this->get('/{id:[0-9]+}', \staffApi::class . ':TraerUno');
            $this->post('[/]', \staffApi::class . ':CargarUno');
            $this->put('/{id:[0-9]+}', \staffApi::class . ':ModificarUno');
            $this->delete('/{id:[0-9]+}', \staffApi::class . ':BorrarUno');
        });
        $this->group('/producto', function () {
            $this->get('[/]', \productoApi::class . ':TraerTodos');
            $this->get('/{id:[0-9]+}', \productoApi::class . ':TraerUno');
            $this->post('[/]', \productoApi::class . ':CargarUno');
            $this->put('/{id:[0-9]+}', \productoApi::class . ':ModificarUno');
            $this->delete('/{id:[0-9]+}', \productoApi::class . ':BorrarUno');
        });
        $this->group('/mesa', function () {
            $this->get('[/]', \mesaApi::class . ':TraerTodos');
            $this->get('/{id:[0-9]+}', \mesaApi::class . ':TraerUno');
            $this->post('[/]', \mesaApi::class . ':CargarUno');
            $this->put('/{id:[0-9]+}', \mesaApi::class . ':ModificarUno');
            $this->delete('/{id:[0-9]+}', \mesaApi::class . ':BorrarUno');
        });
    })/*->add(new isTipoMW(Tipo::staff))*/;
})->add(new AuthMW($app->getContainer()))->add(new corsMW());
$app->run();

/*->add(new AuthMW($this->getContainer()->get('router')))*/
