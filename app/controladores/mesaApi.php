<?php
require_once './modelos/mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaApi extends Mesa implements IApiUsable
{
 	public function TraerUno($request, $response, $args) {
     	return;
        //$id=$args['id'];
    	//$elCd=cd::TraerUnCd($id);
     	//$newResponse = $response->withJson($elCd, 200);  
    	//return $newResponse;
    }
    public function TraerTodos($request, $response, $args) {
      	$mesas = Mesa::GetArrayObj();
        if(is_null($mesas))
            return $response->getBody()->write("Error al obtener datos de la base de datos\n");
    	return count($mesas) > 0 ?
            $response->withJson($mesas , 200):
            $response->getBody()->write("No existe ningún mesa en la lista\n");
    }
    public function CargarUno($request, $response, $args) {
     	$parametros = $request->getParsedBody();
        $codigo= $parametros['codigo']?? null;
        $estado= $parametros['estado'] ?? null;
        if(empty($codigo)||empty($estado))
            return $response->getBody()->write("Error, datos faltantes.\n");
        $mesa = new Mesa();
        $mesa->codigo=trim($codigo);
        $mesa->estado=strtolower(trim($estado));
        if(!ctype_alnum($mesa->codigo)||strlen($mesa->codigo) != 5){
            return $response->getBody()->write("No corresponde el formato código.\n");
        }
        if($mesa->estado != "abierta" && $mesa->estado != "cerrada")
           return $response->getBody()->write("No corresponde el estado.\n");
        return $mesa->GuardarBD()? 
            $response->getBody()->write("Operación (alta de mesa) exitosa.\n"):
            $response->getBody()->write("Error, operación (alta de mesa) fallida.\n");
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