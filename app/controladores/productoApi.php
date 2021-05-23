<?php
require_once './modelos/producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoApi extends Producto implements IApiUsable
{
 	public function TraerUno($request, $response, $args) {
     	return;
        //$id=$args['id'];
    	//$elCd=cd::TraerUnCd($id);
     	//$newResponse = $response->withJson($elCd, 200);  
    	//return $newResponse;
    }
    public function TraerTodos($request, $response, $args) {
      	$productos = Producto::GetArrayObj();
        if(is_null($productos))
            return $response->withJson("Error al obtener datos de la base de datos",500);
    	return count($productos) > 0 ?
            $response->withJson($productos , 200):
            $response->withJson("No existe ningún producto en la lista",204);
    }
    public function CargarUno($request, $response, $args) {
        $elem = Validar::Producto($request->getParsedBody());
        if(is_string($elem))
            return $response->withJson($elem,400);
        return $elem->GuardarBD()? 
            $response->withJson("Operación (alta de producto) exitosa.",201):
            $response->withJson("Error, operación (alta de producto) fallida.",500);
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
        //$response->withJson("<h1>Modificar  uno</h1>");
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