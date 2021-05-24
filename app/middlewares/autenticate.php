<?php
require_once 'middleware.php';
require_once './utiles/token.php';

class Autenticate extends Middleware{
    public function __construct($router){
        parent::__construct($router);
    }

    public function __invoke($request, $response, $next){
        try{
            if(!empty($_SESSION['token'])){
                Token::Verificar($_SESSION['token']);
                $data = Token::ObtenerData($_SESSION['token']);
                $response = $next($request, $response);
                $response->getBody()->write('Bienvenido/a '.$data->nombre);
                return $response;
            }
        }catch(Exception $e){
            echo('Error en token: '.$e->getMessage());
        }
        return $response->withRedirect($this->router->pathFor('login'),303);
    }
}
?>