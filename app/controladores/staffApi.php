<?php
require_once './utiles/validar.php';
require_once './interfaces/IApiUsable.php';

class staffApi extends Staff implements IApiUsable{
 	public function TraerUno($request, $response, $args) {
     	return;
        //$id=$args['id'];
    	//$elCd=cd::TraerUnCd($id);
     	//$newResponse = $response->withJson($elCd, 200);  
    	//return $newResponse;
    }
    public function TraerTodos($request, $response, $args) {
      	$staff=Staff::GetArrayObj();
        if(is_null($staff))
            return $response->withJson("Error al obtener datos de la base de datos.",500);
    	return count($staff) > 0 ?
            $response->withJson($staff, 200):
            $response->withJson("No existe ningún staff en la lista.",200);
    }
    public function CargarUno($request, $response, $args) {
        $elem = Validar::Staff($request->getParsedBody());
        if(is_string($elem))
            return $response->withJson($elem,400);
        return $elem->GuardarBD()? 
            $response->withJson("Operación (alta de staff) exitosa.",201):
            $response->withJson("Error, operación (alta de staff) fallida.",500);
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
}