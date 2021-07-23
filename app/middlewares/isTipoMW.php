<?php
require_once './utiles/token.php';

/**
 *recibe en el constructor $tipo (cliente o staff) 
 *filtra si el usuario es cliente o staff.
 *Caso contrario retorna  No tiene permisos para ver este contenido - 403
 */
class isTipoMW
{
    public function Cliente($req, $res, $next)
    {
        if (!empty($_COOKIE['token'])) {
            $data = Token::ObtenerData($_COOKIE['token']);
            if (!empty($data->mail)) {
                $req = $req->withAttribute('id', $data->id);
                $req = $req->withAttribute('nombre', $data->nombre);
                $res = $next($req, $res);
                return $res;
            }
        }
        return $res->withJson("No tiene permisos para ver este contenido", 403);
    }
    public function Staff($req, $res, $next)
    {
        if (!empty($_COOKIE['token'])) {
            $data = Token::ObtenerData($_COOKIE['token']);
            if (!empty($data->sector)) {
                $req = $req->withAttribute('id', $data->id);
                $req = $req->withAttribute('sector', $data->sector);
                $res = $next($req, $res);
                return $res;
            }
        }
        return $res->withJson("No tiene permisos para ver este contenido", 403);
    }
}
