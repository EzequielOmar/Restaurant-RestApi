<?php
require_once './interfaces/IApiUsable.php';
require_once './modelos/pedido.php';
require_once './modelos/mesa.php';
require_once './modelos/staff.php';

class PedidoApi extends Pedido implements IApiUsable
{
 	public function TraerUno($req, $res, $args) {
     	return;
    }
    public function TraerTodos($req, $res, $args) {
      	$pedidos = Pedido::GetArrayObj();
        if(is_null($pedidos))
            return $res->withJson("Error al obtener datos de la base de datos\n",500);
    	return count($pedidos) > 0 ?
            $res->withJson($pedidos , 200):
            $res->withJson("No existe ningún pedido en la lista\n",204);
    }
    public function CargarUno($req, $res, $args) {
        $elem = Validar::Pedido($req->getParsedBody());
        if(is_string($elem))
            return $res->withJson($elem,400);
        return $elem->GuardarBD()? 
            $res->withJson("Operación (alta de pedido) exitosa.\n",201):
            $res->withJson("Error, operación (alta de pedido) fallida.\n",500);
    }
    public function BorrarUno($req, $res, $args) {
        return;
    }
    public function ModificarUno($req, $res, $args) {
        return;
    }


}