<?php

use App\Models\Mesa;

require_once './interfaces/IApiUsable.php';
require_once './modelos/mesa.php';
include_once './utiles/alfanum.php';
include_once './utiles/enum.php';

class MesaApi implements IApiUsable
{
    public function TraerUno($req, $res, $args)
    {
        $mesa = Mesa::find($args['id']);
        if (!$mesa)
            $mesa = "No se encontró el id " . $args['id']  . ".";
        $res->getBody()->write(json_encode(array("Mesa: " . $args['id'] => $mesa)));
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodos($req, $res, $args)
    {
        $lista = Mesa::all();
        $res->getBody()->write(json_encode(array("mesas" => $lista)));
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno($req, $res, $args)
    {
        $mesa = new Mesa();
        $mesa->codigo = GenerarCodigoAlfanumerico();
        $mesa->estado = EstadoDeMesa::abierta;
        $mesa->id_mozo_asignado = 0;
        $mesa->save();
        $res->getBody()->write(json_encode(array("mensaje" => "Alta de mesa exitosa.")));
        return $res->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }
    public function BorrarUno($req, $res, $args)
    {
        $mesa = Mesa::find($args['id']);
        if ($mesa) {
            if (!$mesa->delete())
                $mesa = "Error de sistema, el dato no se eliminó.";
            $res->getBody()->write(json_encode(array("Mensaje" => "Se eliminó: " . $mesa->codigo . ".")));
            return $res->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        }
        $mesa = "No se encontró el id " . $args['id']  . ".";
        $res->getBody()->write(json_encode(array("Error" => $mesa)));
        return $res->withStatus(400)
            ->withHeader('Content-Type', 'application/json');
    }
    public function ModificarUno($req, $res, $args)
    {
        try {
            $modif = Mesa::find($args['id']);
            if (!$modif)
                throw new Exception("El id ingresado no pertenece a un producto existente.");
            $data = $req->getParsedBody();
            $estado = $data['estado'] ?? $modif->estado;
            if (empty($estado))
                throw new Exception("Error, datos faltantes.");
            if (!is_numeric($estado) || $estado > 4 || $estado < 1)
                throw new Exception("Error de formato al cargar datos");
            $modif->estado = $estado;
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
