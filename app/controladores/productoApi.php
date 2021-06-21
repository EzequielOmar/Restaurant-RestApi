<?php

use App\Models\Producto;

require_once './modelos/producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoApi implements IApiUsable
{
    private static function Validar($params)
    {
        $nombre = $params['nombre'] ?? null;
        $descripcion = $params['descripcion'] ?? null;
        $sector = $params['sector'] ?? null;
        $precio = $params['precio'] ?? null;
        $stock = $params['stock'] ?? null;
        if (empty($nombre) || empty($descripcion) || empty($precio) || empty($stock) || empty($sector))
            throw new Exception("Error, datos faltantes.");
        if (!is_numeric($precio) || !is_numeric($stock))
            throw new Exception("Error de formato al cargar datos");
        $prod = new Producto();
        $prod->nombre = ucfirst(strtolower(trim($nombre)));
        $prod->descripcion = ucfirst(strtolower(trim($descripcion)));
        $prod->sector = trim($sector);
        $prod->precio = '$' . str_replace(',', '.', $precio);
        $prod->stock = $stock;
        if ($prod->sector < 1 || $prod->sector > 5)
            throw new Exception("No corresponde el sector.");
        if (!Producto::where('nombre', '=', $prod->nombre)->get()->isEmpty())
            throw new Exception("Ya existe un producto con el nombre: " . $prod->nombre . ".");
        return $prod;
    }
    public function TraerUno($req, $res, $args)
    {
        return;
    }
    public function TraerTodos($req, $res, $args)
    {
        $productos = Producto::get()->all();
        if(!$productos)
            $productos = "Lo siento, no tenemos productos a disposición. No sé para que abrimos hoy.";
        return $res->withJson(json_encode(array("productos" => $productos)), 200)
            ->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno($req, $res, $args)
    {
        try {
            $prod = self::Validar($req->getParsedBody());
            $prod->save();
        } catch (Exception $e) {
            return $res->withJson("Error:" . $e->getMessage(), 400);
        }
        return $res->withJson(json_encode(array("mensaje" => "Alta de producto exitosa.")), 201)
            ->withHeader('Content-Type', 'application/json');
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
