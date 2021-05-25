<?php
require_once './modelos/pedido.php';

class Listado{
 	public function HomePorSector($req,$res,$args){
        $sector = $req->getAttribute('sector');
        $id = $req->getAttribute('id');
        switch($sector){
            case 1:
                //todos los listados y home page de socios.
                return $res->withJson("jeefe listado");
                break;
            case 2:
                $lista = Pedido::PendidosPorIdMozo($id);
                return $lista? $res->withJson($lista,200):$res->withJson("Sin órdenes.",200);
                break;
            case 3:case 4:case 5:
                $lista = Pedido::PendientesPorSector($sector);
                return $lista? $res->withJson($lista,200):$res->withJson("Sin pedidos pendientes.",200);
        }
    }
}