<?php
require_once './utiles/validar.php';
require_once './interfaces/IApiUsable.php';

class staffApi extends Staff implements IApiUsable{
 	public function TraerUno($req, $res, $args) {
     	return;
    }
    public function TraerTodos($req, $res, $args) {
      	$staff=Staff::GetArrayObj();
        if(is_null($staff))
            return $res->withJson("Error al obtener datos de la base de datos.",500);
    	return count($staff) > 0 ?
            $res->withJson($staff, 200):
            $res->withJson("No existe ningún staff en la lista.",200);
    }
    public function CargarUno($req, $res, $args) {
        $elem = Validar::Staff($req->getParsedBody());
        if(is_string($elem))
            return $res->withJson($elem,400);
        return $elem->GuardarBD()? 
            $res->withJson("Operación (alta de staff) exitosa.",201):
            $res->withJson("Error, operación (alta de staff) fallida.",500);
    }
    public function BorrarUno($req, $res, $args) {
        return;
    }
    public function ModificarUno($req, $res, $args) {
        return;
    }
	public function Loguear($req, $res, $args){
		$elem = Validar::logStaff($req->getParsedBody());
		if(is_string($elem))
            return $res->withJson($elem,400);
		$data = array('id'=>$elem->id,
					  'dni'=>$elem->dni,
					  'nombre'=>$elem->nombre,
					  'sector'=>$elem->sector);
		$_SESSION['token'] = Token::Crear($data);
		return $res->withJson("Logueo exitoso.",200);
	}
}