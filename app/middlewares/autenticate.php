<?php
require_once 'middleware.php';
require_once './utiles/token.php';

/**
 * Chequea que el usuario (cliente o staff) esté logueado mediante JWT,
 * si el token es invalido, redirecciona al login corresponiente. 
 */
class AuthMW extends Middleware{
    public function __construct($router){
        parent::__construct($router);
    }

    public function __invoke($req, $res, $next){
        try{
            if(!empty($_SESSION['token'])){
                Token::Verificar($_SESSION['token']);
                $res = $next($req, $res);
                return $res;
            }
        }catch(Exception $e){
            return $res->withJson("Su sesión ha expirado. Por favor, loguearse nuevamente.",200);
        }
        if( str_contains($req->getUri()->getPath(),"staff"))
            return $res->withRedirect($this->router->pathFor('staff-login'),303);
        return $res->withRedirect($this->router->pathFor('login'),303);
    }
}
?>