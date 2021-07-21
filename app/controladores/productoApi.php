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
    private static function ValidarModif($params, $id)
    {
        $modif = Producto::find($id);
        if (!$modif)
            throw new Exception("El id ingresado no pertenece a un producto existente.");
        $nombre = $params['nombre'] ?? $modif->nombre;
        $descripcion = $params['descripcion'] ?? $modif->descripcion;
        $sector = $params['sector'] ?? $modif->sector;
        $precio = $params['precio'] ?? $modif->precio;
        $stock = $params['stock'] ?? $modif->stock;

        $modif->nombre = ucfirst(strtolower(trim($nombre)));
        $modif->descripcion = ucfirst(strtolower(trim($descripcion)));
        $modif->sector = trim($sector);
        if ($precio[0] != '$')
            $modif->precio = '$' . str_replace(',', '.', $precio);
        $modif->stock = $stock;
        if (!is_numeric(substr($modif->precio, 1)) || !is_numeric($modif->stock))
            throw new Exception("Error, formato incorrecto");
        if ($modif->sector < 1 || $modif->sector > 5)
            throw new Exception("No corresponde el sector.");
        return $modif;
    }
    public function TraerUno($req, $res, $args)
    {
        $prod = Producto::find($args['id']);
        if (!$prod)
            $prod = "No se encontró el id " . $args['id']  . ".";
        $res->getBody()->write(json_encode(array("Producto: " . $args['id'] => $prod)));
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodos($req, $res, $args)
    {
        $productos = Producto::all();
        if (!$productos)
            $productos = "Lo siento, no tenemos productos a disposición. No sé para que abrimos hoy.";
        $res->getBody()->write(json_encode(array("productos" => $productos)));
        return $res->withStatus(200)
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
        $res->getBody()->write(json_encode(array("mensaje" => "Alta de producto exitosa.")));
        return $res->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }
    public function BorrarUno($req, $res, $args)
    {
        $prod = Producto::find($args['id']);
        try {
            if (!$prod)
                throw new Exception("No se encontró el id " . $args['id']  . ".");
            if (!$prod->delete())
                throw new Exception("Error de sistema, el dato no se eliminó.");
        } catch (Exception $e) {
            return $res->withJson("Error:" . $e->getMessage(), 400);
        }
        $res->getBody()->write(json_encode(array("Mensaje" => "Se eliminó: " . $prod->nombre . ".")));
        return $res->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
    public function ModificarUno($req, $res, $args)
    {
        try {
            $modif = self::ValidarModif($req->getParsedBody(), $args['id']);
            if (!$modif->isDirty())
                throw new Exception("No se han realizado modificaciones.");
            if (!$modif->save())
                throw new Exception("Lo siento. Error interno del sistema al intentar modificar los datos.");
        } catch (Exception $e) {
            return $res->withJson(json_encode(array("Error:" => $e->getMessage())), 400)
                ->withHeader('Content-Type', 'application/json');;
        }
        $res->getBody()->write(json_encode(array("Éxito:" => $modif->nombre . " modificado correctamente.")));
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}
