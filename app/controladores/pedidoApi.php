<?php
require_once './modelos/pedido.php';
require_once './interfaces/IApiUsable.php';
require_once __DIR__.'/../modelos/mesa.php';
require_once __DIR__.'/../modelos/pedido.php';
require_once __DIR__.'/../modelos/usuario.php';

class PedidoApi extends Pedido implements IApiUsable
{
    //FUNCIONES PRIVADAS
    private static function ValidarPedido(Pedido $ped, $response){
        if(!ctype_alnum($ped->codigo)||strlen($ped->codigo) != 5){
            return "No corresponde el formato del código.\n";
        }
        $mesa = Mesa::ObtenerPorCodigo($ped->codigo_mesa);
        if(empty($mesa)||$mesa->estado == "cerrada")
            return "No existe una mesa con ese código.\n";
        $prod = Producto::ObtenerPorID($ped->id_producto);
        if(empty($prod)||$prod->stock<$ped->cantidad)
            return "No tenemos stock para realizar el pedido.\n";
        $mozo = Usuario::ObtenerPorID($ped->id_mozo);
        if(empty($mozo)||$mozo->sector != "mozo")
            return "No hay personal para tomar el pedido.\n";
    }
    //FUNCIONES PUBLICAS
 	public function TraerUno($request, $response, $args) {
     	return;
        //$id=$args['id'];
    	//$elCd=cd::TraerUnCd($id);
     	//$newResponse = $response->withJson($elCd, 200);  
    	//return $newResponse;
    }
    public function TraerTodos($request, $response, $args) {
      	$pedidos = Pedido::GetArrayObj();
        if(is_null($pedidos))
            return $response->getBody()->write("Error al obtener datos de la base de datos\n");
    	return count($pedidos) > 0 ?
            $response->withJson($pedidos , 200):
            $response->getBody()->write("No existe ningún pedido en la lista\n");
    }
    public function CargarUno($request, $response, $args) {
     	$parametros = $request->getParsedBody();
        $codigo= $parametros['codigo']?? null;
        $codigo_mesa= $parametros['codigo_mesa'] ?? null;
        $id_producto= $parametros['id_producto'] ?? null;
        $cantidad= $parametros['cantidad'] ?? null;
        $id_mozo= $parametros['id_mozo'] ?? null;
        if(empty($codigo)||empty($codigo_mesa)||empty($id_producto)||
           empty($cantidad)||empty($id_mozo))
            return $response->getBody()->write("Error, datos faltantes.\n");
        $ped = new Pedido();
        $ped->codigo = $codigo;
        $ped->codigo_mesa = trim($codigo_mesa);
        $ped->estado = "comandado";
        $ped->id_producto = $id_producto;
        $ped->cantidad = $cantidad;
        $ped->id_mozo = $id_mozo;
        $ped->id_elaborador = 0;
        $error = self::ValidarPedido($ped,$response);  
        if(!empty($error)){
            return $response->getBody()->write($error);
        } 
        $dt = new DateTime("now",new DateTimeZone("America/Argentina/Buenos_Aires"));
        $ped->fecha = $dt->format('Y-m-d');
        $ped->hora_comandado = $dt->format('H:i:s');
        return $ped->GuardarBD()? 
            $response->getBody()->write("Operación (alta de pedido) exitosa.\n"):
            $response->getBody()->write("Error, operación (alta de pedido) fallida.\n");
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