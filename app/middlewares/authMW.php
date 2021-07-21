<?php

use App\Controllers\Container;

require_once './utiles/container.php';
require_once './utiles/token.php';

/**
 * Chequea que el usuario esté logueado mediante JWT,
 * si el token es invaáido, redirecciona al login corresponiente.
 * (si la url contiene staff/ -> /staff/login, si no  -> /login)
 */
class AuthMW extends Container
{
    public function __invoke($req, $res, $next)
    {
        try {
            if (!empty($_COOKIE['token'])) {
                Token::Verificar($_COOKIE['token']);
                $res = $next($req, $res);
                return $res;
            }
        } catch (Exception $e) {
            return $res->withJson("Su sesión ha expirado. Por favor, loguearse nuevamente.", 200);
        }
        if (
            str_contains($req->getUri()->getPath(), "pedidos") ||
            str_contains($req->getUri()->getPath(), "staff") ||
            str_contains($req->getUri()->getPath(), "producto") ||
            str_contains($req->getUri()->getPath(), "mesa")
        )
            return $res->withRedirect($this->router->pathFor('staff-login'), 307);
        return $res->withRedirect($this->router->pathFor('login'), 307);
    }
}
