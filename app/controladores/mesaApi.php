<?php

use App\Models\Mesa;

require_once './interfaces/IApiUsable.php';
require_once './modelos/mesa.php';
include_once './utiles/alfanum.php';

class MesaApi implements IApiUsable
{
    /**
     * HACER LOGICA DE ASIGNAR MESAS A MOZOS
     * , LLAMAR DESPUES DE AGREGAR CADA MESA.
     */
    public function TraerUno($req, $res, $args)
    {
        return;
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
        $mesa->estado = 1;
        $mesa->id_mozo_asignado = 0;
        $mesa->save();
        return $res->withJson(json_encode(array("mensaje" => "Alta de mesa exitosa.")), 201)
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
