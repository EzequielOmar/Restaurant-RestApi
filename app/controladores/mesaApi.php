<?php
require_once './modelos/mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaApi extends Mesa implements IApiUsable
{
 	public function TraerUno($request, $response, $args) {
     	return;
    }
    public function TraerTodos($request, $response, $args) {
      	$mesas = Mesa::GetArrayObj();
        if(is_null($mesas))
            return $response->withJson("Error al obtener datos de la base de datos\n",500);
    	return count($mesas) > 0 ?
            $response->withJson($mesas , 200):
            $response->withJson("No existe ningún mesa en la lista\n",204);
    }
    public function CargarUno($request, $response, $args) {
		$elem = Validar::Mesa($request->getParsedBody());
		if(is_string($elem))
			return $response->withJson($elem,400);
        return $elem->GuardarBD()? 
            $response->withJson("Operación (alta de mesa) exitosa.\n",201):
            $response->withJson("Error, operación (alta de mesa) fallida.\n",500);
    }
    public function BorrarUno($request, $response, $args) {
        return;
    }
    public function ModificarUno($request, $response, $args) {
        return;
    }


}