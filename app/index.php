<?php
use Illuminate\Database\Capsule\Manager as Capsule;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ .'/../TCPDF/tcpdf.php';
require_once './utiles/enum.php';
require_once './middlewares/corsMW.php';
require_once './middlewares/authMW.php';
require_once './middlewares/isTipoMW.php';
require_once './middlewares/isSectorMW.php';
require_once './controladores/productoApi.php';
require_once './controladores/clienteApi.php';
require_once './controladores/staffApi.php';
require_once './controladores/mesaApi.php';
require_once './controladores/pedidoApi.php';
require_once './controladores/cierreApi.php';
require_once './controladores/listadoApi.php';

//mostrar errores por pantalla
error_reporting(-1);
ini_set('display_errors', 1);
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
$config['errorHandler'] = $config['notFoundHandler'];
$config['phpErrorHandler'] = $config['notFoundHandler'];
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
//rutas
//sin log
$app->group('', function () {
    $this->get('[/]', \productoApi::class . ':TraerTodos')->setName('home');;
    $this->map(['GET', 'POST'], '/login', \clienteApi::class . ':Loguear')->setName('login');
    $this->map(['GET', 'POST'], '/staff/login', \staffApi::class . ':Loguear')->setName('staff-login');
    $this->map(['GET', 'POST'], '/registro', \clienteApi::class . ':CargarUno');
})->add(new corsMW());
//con log
$app->group('', function () {
    //cliente
    $this->group('/pedir', function () {
        $this->post('[/]', \pedidoApi::class . ':CargarUno');
        $this->get('/{cod_mesa:[0-9^a-z^A-Z]+}/{cod_pedido:[0-9^a-z^A-Z]+}', \pedidoApi::class . ':TraerUno');
        $this->post('/{cod_mesa:[0-9^a-z^A-Z]+}/{cod_pedido:[0-9^a-z^A-Z]+}', \pedidoApi::class . ':BorrarUno');
        $this->map(['GET', 'POST'], '/comentario', \cierreApi::class . ':CargarUno')->setName('comentario');
    })->add(isTipoMW::class . ':Cliente');
    //staff
    $this->group('/pedidos', function () {
        $this->get('[/]', \pedidoApi::class . ':TraerTodos');
        $this->put('/{id:[0-9]+}', \pedidoApi::class . ':ModificarUno');
    })->add(isTipoMW::class . ':Staff');
    //admin
    $this->group('/staff', function () {
        $this->get('[/]', \staffApi::class . ':TraerTodos');
        $this->get('/{id:[0-9]+}', \staffApi::class . ':TraerUno');
        $this->post('[/]', \staffApi::class . ':CargarUno');
        $this->put('/{id:[0-9]+}', \staffApi::class . ':ModificarUno');
        $this->delete('/{id:[0-9]+}', \staffApi::class . ':BorrarUno');
    })->add(new isSectorMW(Sector::socio))->add(isTipoMW::class . ':Staff');
    $this->group('/producto', function () {
        $this->get('[/]', \productoApi::class . ':TraerTodos');
        $this->get('/{id:[0-9]+}', \productoApi::class . ':TraerUno');
        $this->post('[/]', \productoApi::class . ':CargarUno');
        $this->put('/{id:[0-9]+}', \productoApi::class . ':ModificarUno');
        $this->delete('/{id:[0-9]+}', \productoApi::class . ':BorrarUno');
    })->add(new isSectorMW(Sector::socio))->add(isTipoMW::class . ':Staff');
    $this->group('/mesa', function () {
        $this->get('[/]', \mesaApi::class . ':TraerTodos');
        $this->get('/{id:[0-9]+}', \mesaApi::class . ':TraerUno');
        $this->post('[/]', \mesaApi::class . ':CargarUno');
        $this->put('/{id:[0-9]+}', \mesaApi::class . ':ModificarUno');
        $this->delete('/{id:[0-9]+}', \mesaApi::class . ':BorrarUno');
    })->add(new isSectorMW(Sector::socio))->add(isTipoMW::class . ':Staff');
    $this->group('/listado',function(){
        $this->get('/login',\listadoApi::class . ':StaffLogin');
        $this->get('/operaciones/{sector:[a-z]+}',\listadoApi::class . ':StaffSector');
        $this->get('/operaciones/{id:[0-9]+}',\listadoApi::class . ':StaffId');
        $this->group('/pedido',function(){
            $this->get('/venta/{take:[0-9]+}',\listadoApi::class . ':PedidoVenta');
            $this->get('/demorado',\listadoApi::class . ':PedidoFueraDeTiempo');
            $this->get('/cancelado',\listadoApi::class . ':PedidoCancelado');
        });
        $this->group('/mesa',function(){
            $this->get('/usada/{take:[0-9]+}',\listadoApi::class . ':MesaUsada');
            $this->get('/factura',\listadoApi::class . ':MesaFactura');
            $this->get('/comentario/{take:[0-9]+}',\listadoApi::class . ':MesaComentario');
        });
        $this->get('/clientes',\listadoApi::class . ':PdfClientes');
        $this->get('/staff',\listadoApi::class . ':PdfStaff');
    });//->add(new isSectorMW(Sector::socio))->add(isTipoMW::class . ':Staff');
});//->add(new AuthMW($app->getContainer()))->add(new corsMW());

$app->run();
