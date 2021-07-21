<?php
require_once './utiles/token.php';

/**
 *recibe en el constructor $sector (de staff) 
 *filtra si el usuario es del sector recibido.
 *Caso contrario retorna  No tiene permisos para ver este contenido - 403
 */
class isSectorMW
{
    private static $_sector;
    public function __construct($sector)
    {
        self::$_sector = $sector;
    }

    public function __invoke($req, $res, $next)
    {
        if (!empty($_COOKIE['token'])) {
            $data = Token::ObtenerData($_COOKIE['token']);
            if (!empty($data->sector) && $data->sector == self::$_sector) {
                $res = $next($req, $res);
                return $res;
            }
        }
        return $res->withJson("No tiene permisos para ver este contenido", 403);
    }
}
