<?php

use App\Controllers\Container;
use App\Models\Factura;
use App\Models\Log;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Staff;

require_once './interfaces/IApiUsable.php';


require_once './utiles/container.php';
include_once './utiles/alfanum.php';
include_once './utiles/enum.php';
require_once './utiles/token.php';

class PedidoApi extends Container implements IApiUsable
{
    static public $path_fotos = "./uploads/";
    //chequeos
    private static function ChequearStock($idProd, $cantPedida)
    {
        $prod = Producto::find($idProd);
        if (!$prod || $prod->stock < $cantPedida)
            throw new Exception("Lo siento, nos quedamos sin " . $prod->nombre . ".");
    }
    private static function AsignarMesaYMozo($id_cliente)
    {
        $pedidoPrevio = Pedido::where('id_cliente', '=', $id_cliente)->get();
        if (!$pedidoPrevio->isEmpty())
            return array($pedidoPrevio[0]->codigo_mesa, $pedidoPrevio[0]->id_mozo);
        $mesasLibres = Mesa::where('estado', '=', EstadoDeMesa::abierta)->get();
        if ($mesasLibres->isEmpty())
            throw new Exception("Lo siento, nos quedamos sin mesas disponibles.");
        $mesaAsignada = $mesasLibres->filter(function ($mesa) {
            return $mesa->id_mozo_asignado !== 0;
        })->take(1);
        if ($mesaAsignada->isEmpty())
            throw new Exception("Lo siento, nos quedamos sin personal disponible para tomar tu pedido.");
        $objMesa = Mesa::find($mesaAsignada->all()[0]->id);
        $objMesa->estado = EstadoDeMesa::esperando;
        $objMesa->save();
        return array($objMesa->codigo, $objMesa->id_mozo_asignado);
    }
    //validacion
    private static function ChequearData($data)
    {
        $func = function ($id) {
            return !is_numeric($id);
        };
        if (empty($data["id_productos"]) || empty($data["cantidades"]))
            throw new Exception("Datos vacíos.");
        $data["id_productos"] = json_decode($data["id_productos"]);
        $data["cantidades"] = json_decode($data["cantidades"]);
        if (
            count($data["id_productos"]) !== count($data["cantidades"])
            || array_filter($data["id_productos"], $func)
            || array_filter($data["cantidades"], $func)
        )
            throw new Exception("Datos incorrectos.");
        return $data;
    }
    private static function ValidarPedidos($data, $id_cliente)
    {
        try {
            $pedidos = [];
            $codigo = GenerarCodigoAlfanumerico();
            [$mesaAsignada, $mozoAsignado]  = self::AsignarMesaYMozo($id_cliente);
            for ($i = 0; $i < count($data["id_productos"]); $i++) {
                $pedido = new Pedido();
                $pedido->id_producto = intval($data["id_productos"][$i]);
                $pedido->cantidad = intval($data["cantidades"][$i]);
                self::ChequearStock($pedido->id_producto, $pedido->cantidad);
                $pedido->codigo = $codigo;
                $pedido->codigo_mesa = $mesaAsignada;
                $pedido->id_cliente = $id_cliente;
                $pedido->id_mozo = $mozoAsignado;
                $pedido->estado = EstadoDePedido::comandado;
                array_push($pedidos, $pedido);
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $pedidos;
    }
    private static function AgregarPedidos($pedidos)
    {
        $dt = new DateTime("now", new DateTimeZone("America/Argentina/Buenos_Aires"));
        $fecha = $dt->format('Y-m-d');
        $hora = $dt->format('H:i:s');
        foreach ($pedidos as $ped) {
            $ped->fecha = $fecha;
            $ped->hora_comandado = $hora;
            $ped->save();
            $objProd = Producto::find($ped->id_producto);
            $objProd->stock -= $ped->cantidad;
            $objProd->save();
        }
        return array($pedidos[0]->codigo, $pedidos[0]->codigo_mesa);
    }
    private static function Facturar($id_mesa, $cod_pedidos, $id_cliente, $monto)
    {
        $fact = new Factura();
        $fact->id_mesa = $id_mesa;
        $fact->codigo_pedido = $cod_pedidos;
        $fact->id_cliente = $id_cliente;
        $fact->monto = number_format($monto, 2);
        $dt = new DateTime("now", new DateTimeZone("America/Argentina/Buenos_Aires"));
        $fact->fecha = $dt->format('Y-m-d H-i-s');
        $fact->save();
        return $fact->id;
    }
    private static function ValidarModificacion($req, $id)
    {
        $modif = Pedido::find($id);
        $id_staff = Token::ObtenerData($_COOKIE['token'])->id;
        if (!$modif)
            throw new Exception("El id ingresado no pertenece a un pedido existente.");
        $tiempoEstimado = $req->getParsedBody()['estimado'];
        if (!preg_match('/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/', $tiempoEstimado))
            throw new Exception("El formato del tiempo estimado es incorrecto. (hh:mm:ss) != " . $tiempoEstimado);
        $dt = new DateTime("now", new DateTimeZone("America/Argentina/Buenos_Aires"));
        switch ($req->getAttribute('sector')) {
            case Sector::mozo:
                if ($modif->id_mozo == $id_staff && $modif->estado == EstadoDePedido::listo) {
                    $modif->hora_entregado =  $dt->format('H:i:s');
                    $modif->estado = EstadoDePedido::entregado;
                    staffApi::CrearLog(OperacionStaff::despacho, $id_staff, Sector::mozo);
                }
                break;
            case Sector::bar:
            case Sector::cocina:
            case Sector::cerveza:
                if ($modif->producto->sector == $req->getAttribute('sector')) {
                    if ($modif->estado == EstadoDePedido::comandado && !empty($tiempoEstimado)) {
                        $modif->id_elaborador = $id_staff;
                        $modif->hora_tomado =  $dt->format('H:i:s');
                        $modif->hora_estimada = $tiempoEstimado;
                        $modif->estado = EstadoDePedido::preparacion;
                        staffApi::CrearLog(OperacionStaff::tomaServ, $id_staff, $req->getAttribute('sector'));
                    } elseif ($modif->estado == EstadoDePedido::preparacion && $modif->id_elaborador == $id_staff) {
                        $modif->hora_listo =  $dt->format('H:i:s');
                        $modif->estado = EstadoDePedido::listo;
                        staffApi::CrearLog(OperacionStaff::despacho, $id_staff,$req->getAttribute('sector'));
                    }
                }
                break;
        }
        return $modif;
    }

    /**
     *  GET -> Muestra los pedidos correspondientes - si coinciden los parametros.
     * */
    public function TraerUno($req, $res, $args)
    {
        $pedidos = Pedido::where('codigo_mesa', '=', $args['cod_mesa'])->where('codigo', '=', $args['cod_pedido'])->get();
        if (!$pedidos)
            $pedidos =  "No hay ningún pedido comandado con el cod_mesa:" . $args['cod_mesa'] . " y cod_pedido:" . $args['cod_pedido'] . ".";
        $res->getBody()->write(json_encode(array("Pedidos" => $pedidos)));
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
    /*
     *  POST -> Revisa el estado del pedido: case Entregado -> finaliza y factura. 
     *                                       case Comandado,Preparacion,Listo -> se cancela.
     *  ->redireciona a  comentario, le pasa id de factura o null.
     */
    public function BorrarUno($req, $res, $args)
    {
        $pedido = Pedido::where('codigo_mesa', '=', $args['cod_mesa'])->where('codigo', '=', $args['cod_pedido'])->get()->first();
        if (!$pedido) {
            $res->write(json_encode(
                array("Error:" => "No hay ningún pedido comandado 
                con el cod_mesa:" . $args['cod_mesa'] . " y cod_pedido:" . $args['cod_pedido'] . ".")
            ));
            return $res->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        if ($pedido->estado == EstadoDePedido::entregado) {
            $total = floatval(substr($pedido->producto->precio, 1)) * $pedido->cantidad;
            $id_fact = self::Facturar($pedido->mesa->id, $pedido->codigo, $req->getAttribute('id'), $total);
            $pedido->estado = EstadoDePedido::finalizado;
            $pedido->save();
            $pedido->delete();
        } else {
            $pedido->estado = EstadoDePedido::cancelado;
            $pedido->save();
            $pedido->delete();
        }
        return $res->withRedirect($this->router->pathFor('comentario', [], ['id_factura' => $id_fact]), 301);
    }
    /**
     * Recibe dos arrays de int id_productos = ['int',...] , cantidades = ['int',...] 
     * representando cada eslabon de los array, el id del producto y cantidad del mismo.
     * Se generará un pedido por id de producto, a todos los pedidos se le asignara igual mesa, igual codigo de pedido, y
     * mismo mozo.
     * Responde con error, o mensaje de éxito y codigo de mesa y pedido.
     */
    public function CargarUno($req, $res, $args)
    {
        try {
            $data = self::ChequearData($req->getParsedBody());
            $pedidos = self::ValidarPedidos($data, $req->getAttribute('id'));
            [$codigo_pedidos, $codigo_mesa] = self::AgregarPedidos($pedidos);
            if (!empty($_FILES["foto-mesa"]) && $_FILES["foto-mesa"]["error"] == 0)
                move_uploaded_file($_FILES["foto-mesa"]["tmp_name"], self::$path_fotos . $codigo_pedidos . ".jpg");
        } catch (Exception $e) {
            return $res->withJson("Error:" . $e->getMessage(), 400);
        }
        $res->getBody()->write(json_encode(array(
            "mensaje" => "Pedidos tomados con éxito.",
            "codigo-pedido" => $codigo_pedidos,
            "codigo-mesa" => $codigo_mesa,
        )));
        return $res->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
    /**
     * Modifica los pedidos según el sector del staff que realiza el request
     * mozo-> pone hora entrega y pasa a estado entregado (si esta en estado listo)
     * bar/cocina/cerveza 
     *  -> pone hora_tomado, hora_estimada y pasa a preparación (si esta en estado comandado y recibe hora estimada por form-urlencoded)
     *  -> pone hora_listo y pasa a listo (si esta en estado preparación)
     */
    public function ModificarUno($req, $res, $args)
    {
        try {
            $modif = self::ValidarModificacion($req, $args['id']);
            if (!$modif->isDirty())
                throw new Exception("No se han realizado modificaciones en el pedido.");
            if (!$modif->save())
                throw new Exception("Lo siento. Error interno del sistema al intentar modificar los datos.");
        } catch (Exception $e) {
            return $res->withJson(json_encode(array("Error:" => $e->getMessage())), 400)
                ->withHeader('Content-Type', 'application/json');;
        }
        $res->getBody()->write(json_encode(array("Éxito:" => "Modificado correctamente.")));
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
    /**
     * filtra los pedidos según el sector del staff que realiza el request
     */
    public function TraerTodos($req, $res, $args)
    {
        switch ($req->getAttribute('sector')) {
            case Sector::socio:
                $pedidos = Pedido::withTrashed()->get()->all();
                break;
            case Sector::mozo:
                $pedidos = Pedido::where('estado', '>', '0')->where('estado', '<', '4')->where('id_mozo', '=', $req->getAttribute('id'))->get()->all();
                break;
            case Sector::bar:
                $pedidos = Pedido::where('estado', '=', EstadoDePedido::comandado)->whereHas('producto', function ($p) {
                    $p->where('sector', '=', Sector::bar);
                })->get()->all();
                break;
            case Sector::cocina:
                $pedidos = Pedido::where('estado', '=', EstadoDePedido::comandado)->whereHas('producto', function ($p) {
                    $p->where('sector', '=', Sector::cocina);
                })->get()->all();
                break;
            case Sector::cerveza:
                $pedidos = Pedido::where('estado', '=', EstadoDePedido::comandado)->whereHas('producto', function ($p) {
                    $p->where('sector', '=', Sector::cerveza);
                })->get()->all();
                break;
        }
        if (!$pedidos)
            $pedidos = "Sin pedidos por el momento.";
        $res->getBody()->write(json_encode(array("Pedidos:" => $pedidos)));
        return $res->withStatus(400)
            ->withHeader('Content-Type', 'application/json');
    }
}
