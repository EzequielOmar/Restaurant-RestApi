<?php
require_once './utiles/token.php';

/**
 * Chequea que el usuario que hace la peticion sea un staff de la empresa
 * de serasi, guarda el atributo id_staff, y sector_staff en el request, a modo de accederlo
 * en la peticion.
 * Si no es staff, retorna status 403, no tiene permisos.
 */
class isStaffMW{
    public function __invoke($req, $res, $next){
        if(!empty($_SESSION['token'])){
            $data = Token::ObtenerData($_SESSION['token']);
            if(!empty($data->sector)){
                $req = $req->withAttribute('id',$data->id);
                $req = $req->withAttribute('sector',$data->sector);
                $res = $next($req, $res);
                return $res;
            }
        }
        return $res->withJson("No tiene permisos para ver este contenido",403);
    }
}
?>