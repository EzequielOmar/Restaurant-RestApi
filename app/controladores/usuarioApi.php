<?php
require_once './modelos/usuario.php';
require_once './interfaces/IApiUsable.php';

class usuarioApi extends Usuario implements IApiUsable
{
 	public function TraerUno($request, $response, $args) {
     	return;
        //$id=$args['id'];
    	//$elCd=cd::TraerUnCd($id);
     	//$newResponse = $response->withJson($elCd, 200);  
    	//return $newResponse;
    }
    public function TraerTodos($request, $response, $args) {
      	$usuarios=Usuario::GetUsuarios();
        if(is_null($usuarios))
            return $response->getBody()->write("Error al obtener datos de la base de datos\n");
    	return count($usuarios) > 0 ?
            $response->withJson($usuarios, 200):
            $response->getBody()->write("No existe ningún usuario en la lista\n");
    }
    public function CargarUno($request, $response, $args) {
     	$parametros = $request->getParsedBody();
        $nombre= $parametros['nombre']?? null;
        $apellido= $parametros['apellido'] ?? null;
        $clave= $parametros['clave'] ?? null;
        $sector= $parametros['sector'] ?? null;
        if(empty($nombre)||empty($apellido)||empty($clave)||empty($sector))
            return $response->getBody()->write("Error, datos faltantes.\n");
        $usr = new Usuario();
        $usr->nombre=ucwords(strtolower(trim($nombre)));
        $usr->apellido=ucwords(strtolower(trim($apellido)));
        $usr->clave=$clave;
        $usr->sector=strtolower(trim($sector));
        switch($usr->sector){
            case "bar":case "cerveceria":case "cocina":case "mozo":case "socio":
                break;
            default:
                return $response->getBody()->write("No corresponde el sector.\n");
        }
        $dt = new DateTime("now",new DateTimeZone("America/Argentina/Buenos_Aires"));
        $usr->fecha_ing = $dt->format('Y-m-d H-i-s');
        $usr->cant_op = 0;
        $usr->estado = "activo";
        return $usr->GuardarUsrBD()? 
            $response->getBody()->write("Operación (alta de usuario) exitosa.\n"):
            $response->getBody()->write("Error, operación (alta de usuario) fallida.\n");
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