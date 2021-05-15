<?php

$mw2 = function($request, $response, $next){
    if($request->isGet())
    {
       $response->getBody()->write('<p>NO necesita credenciales para los get </p>');
       $response = $next($request, $response);
    }
    else
    {
      $response->getBody()->write('<p>verifico credenciales</p>');
      $ArrayDeParametros = $request->getParsedBody();
      $nombre=$ArrayDeParametros['nombre'];
      $tipo=$ArrayDeParametros['tipo'];
      if($tipo=="administrador")
      {
        $response->getBody()->write("<h3>Bienvenido $nombre </h3>");
        $response = $next($request, $response);
      }
      else
      {
        $response->getBody()->write('<p>no tenes habilitado el ingreso</p>');
      }  
    }
    $response->getBody()->write('<p>vuelvo del verificador de credenciales</p>');
    return $response;   
};




?>