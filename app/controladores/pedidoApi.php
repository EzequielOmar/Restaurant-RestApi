<?php

use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Staff;

require_once './interfaces/IApiUsable.php';
require_once './modelos/pedido.php';
require_once './modelos/mesa.php';
require_once './modelos/staff.php';
require_once './modelos/producto.php';
include_once './utiles/alfanum.php';
include_once './utiles/enum.php';

class PedidoApi implements IApiUsable
{
    static public $path_fotos = "./uploads/";

    private static function ChequearStock($idProd, $cantPedida)
    {
        $prod = Producto::findOrFail($idProd);
        if ($prod->stock < $cantPedida)
            throw new Exception("Lo siento, nos quedamos sin " . $prod->nombre . ".");
    }

    private static function AsignarMesaYMozo()
    {
        $mesasLibres = Mesa::where('estado', '=', EstadoDeMesa::abierta)->get();
        if ($mesasLibres->isEmpty())
            throw new Exception("Lo siento, nos quedamos sin mesas disponibles.");
        $mesaAsignada = $mesasLibres->filter(function ($mesa) {
            return $mesa->id_mozo_asignado !== 0;
        })->take(1);
        if ($mesaAsignada->isEmpty())
            throw new Exception("Lo siento, nos quedamos sin personal disponible para tomar tu pedido.");
        $objMesa = Mesa::find($mesaAsignada->all()[0]->id);
        $objMesa->estado = 2;
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

    private static function ValidarPedidos($data)
    {
        try {
            $pedidos = [];
            $codigo = GenerarCodigoAlfanumerico();
            [$mesaAsignada, $mozoAsignado]  = self::AsignarMesaYMozo();
            for ($i = 0; $i < count($data["id_productos"]); $i++) {
                $pedido = new Pedido();
                $pedido->id_producto = intval($data["id_productos"][$i]);
                $pedido->cantidad = intval($data["cantidades"][$i]);
                self::ChequearStock($pedido->id_producto, $pedido->cantidad);
                $pedido->codigo = $codigo;
                $pedido->codigo_mesa = $mesaAsignada;
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
        return array($pedidos[0]->codigo,$pedidos[0]->codigo_mesa);
    }

    public function TraerUno($req, $res, $args)
    {
        return;
    }
    public function TraerTodos($req, $res, $args)
    {
        return;
    }
    public function CargarUno($req, $res, $args)
    {
        try {
            $data = self::ChequearData($req->getParsedBody());
            $pedidos = self::ValidarPedidos($data);
            [$codigo_pedidos,$codigo_mesa] = self::AgregarPedidos($pedidos);
            if (!empty($_FILES["foto-mesa"]) && $_FILES["foto-mesa"]["error"] == 0)
                move_uploaded_file($_FILES["foto-mesa"]["tmp_name"], self::$path_fotos . $codigo_pedidos . ".jpg");
        } catch (Exception $e) {
            return $res->withJson("Error:" . $e->getMessage(), 400);
        }
        return $res->withJson(json_encode(array(
                "mensaje" => "Pedidos tomados con éxito.",
                "codigo-pedido" => $codigo_pedidos,
                "codigo-mesa" => $codigo_mesa,
            )), 200)->withHeader('Content-Type', 'application/json');
    }
    public function BorrarUno($req, $res, $args)
    {
        return;
    }
    public function ModificarUno($req, $res, $args)
    {
        return;
    }
}
