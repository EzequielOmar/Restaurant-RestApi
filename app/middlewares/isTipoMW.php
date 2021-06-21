<?php
require_once './utiles/token.php';

/**
 *recibe en el constructor $tipo (cliente o staff) 
 *filtra si el usuario es cliente o staff.
 *Caso contrario retorna  No tiene permisos para ver este contenido - 403
 */
class isTipoMW
{
    private static $_tipo;
    public function __construct($tipo)
    {
        self::$_tipo = $tipo;
    }

    public function __invoke($req, $res, $next)
    {
        if (!empty($_COOKIE['token'])) {
            $data = Token::ObtenerData($_COOKIE['token']);
            if (self::$_tipo == Tipo::cliente && !empty($data->mail)) {
                $req = $req->withAttribute('id', $data->id);
                $req = $req->withAttribute('nombre', $data->nombre);
                $res = $next($req, $res);
                return $res;
            }
            if (self::$_tipo == Tipo::staff && !empty($data->sector)) {
                $req = $req->withAttribute('id', $data->id);
                $req = $req->withAttribute('sector', $data->sector);
                $res = $next($req, $res);
                return $res;
            }
        }
        return $res->withJson("No tiene permisos para ver este contenido", 403);
    }
}
