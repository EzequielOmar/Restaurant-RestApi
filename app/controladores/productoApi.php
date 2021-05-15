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
            return $response->getBody()->write("Error al obtener datos de la base de datos\n");
    	return count($productos) > 0 ?
            $response->withJson($productos , 200):
            $response->getBody()->write("No existe ningún producto en la lista\n");
    }
    public function CargarUno($request, $response, $args) {
     	$parametros = $request->getParsedBody();
        $nombre= $parametros['nombre']?? null;
        $descripcion= $parametros['descripcion'] ?? null;
        $sector= $parametros['sector'] ?? null;
        $precio= $parametros['precio'] ?? null;
        $stock= $parametros['stock'] ?? null;
        if(empty($nombre)||empty($descripcion)||empty($sector)||empty($precio)||empty($stock))
            return $response->getBody()->write("Error, datos faltantes.\n");
        $prod = new Producto();
        $prod->nombre=ucfirst(strtolower(trim($nombre)));
        $prod->descripcion=ucfirst(strtolower(trim($descripcion)));
        $prod->sector=strtolower(trim($sector));
        switch($prod->sector){
            case "bar":case "cerveza":case "cocina":case "mozo":case "socio":
                break;
                default:
                return $response->getBody()->write("No corresponde el sector.\n");
        }
        if(!is_numeric($precio)||!is_numeric($stock)){
            return $response->getBody()->write("Error al cargar los datos\n");
        }
        $prod->precio = str_replace(',','.',$precio);
        $prod->stock = $stock;
        return $prod->GuardarBD()? 
            $response->getBody()->write("Operación (alta de producto) exitosa.\n"):
            $response->getBody()->write("Error, operación (alta de producto) fallida.\n");
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