<?php
require_once './utiles/validar.php';
require_once './interfaces/IApiUsable.php';
require_once './modelos/cliente.php';
require_once './utiles/token.php';

session_start();

class clienteApi extends Cliente implements IApiUsable{
 	public function TraerUno($request, $response, $args) {
     	return;
        //$id=$args['id'];
    	//$elCd=cd::TraerUnCd($id);
     	//$newResponse = $response->withJson($elCd, 200);  
    	//return $newResponse;
    }
    public function TraerTodos($request, $response, $args) {
      	$cliente=Cliente::GetArrayObj();
        if(is_null($cliente))
            return $response->withJson("Error al obtener datos de la base de datos.",500);
    	return count($cliente) > 0 ?
            $response->withJson($cliente, 200):
            $response->withJson("No existe ningún cliente en la lista.",200);
    }
    public function CargarUno($request, $response, $args) {
        $elem = Validar::cliente($request->getParsedBody());
        if(is_string($elem))
            return $response->withJson($elem,400);
        return $elem->GuardarBD()? 
            $response->withJson("Operación (alta de cliente) exitosa.",201):
            $response->withJson("Error, operación (alta de cliente) fallida.",500);
    }
    public function BorrarUno($request, $response, $args) {
        return;
        //$parametros = $request->getParsedBody();
     	//$id=$parametros['id'];
     	//$cd= new cd();
     	//$cd->id=$id;
     	//$cantidadDeBorrados=$cd->BorrarCd();
     	//$objDelaRespuesta= new stdclass();
	    //$objDelaRespuesta->cantidad=$cantidadDeBorrados;
	    //if($cantidadDeBorrados>0)
	    //	{
	    //		 $objDelaRespuesta->resultado="algo borro!!!";
	    //	}
	    //	else
	    //	{
	    //		$objDelaRespuesta->resultado="no Borro nada!!!";
	    //	}
	    //$newResponse = $response->withJson($objDelaRespuesta, 200);  
      	//return $newResponse;
    }
    public function ModificarUno($request, $response, $args) {
        return;
        //$response->getBody()->write("<h1>Modificar  uno</h1>");
     	//$parametros = $request->getParsedBody();
	    //var_dump($parametros);    	
	    //$micd = new cd();
	    //$micd->id=$parametros['id'];
	    //$micd->titulo=$parametros['titulo'];
	    //$micd->cantante=$parametros['cantante'];
	    //$micd->año=$parametros['anio'];
	   	//$resultado =$micd->ModificarCdParametros();
	   	//$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		//$objDelaRespuesta->resultado=$resultado;
		//return $response->withJson($objDelaRespuesta, 200);		
    }
	public function Loguear($request, $response, $args){
		$elem = Validar::logCliente($request->getParsedBody());
		if(is_string($elem))
            return $response->withJson($elem,400);
		$data = array('id'=>$elem->id,
					  'mail'=>$elem->mail,
					  'nombre'=>$elem->nombre,
					  'cel'=>$elem->cel);
		$_SESSION['token'] = Token::Crear($data);
		return $response->withJson("Logueo exitoso.",200);
	}
}