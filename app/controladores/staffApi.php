<?php
require_once './interfaces/IApiUsable.php';
include_once './utiles/hash.php';
include_once './utiles/enum.php';

use App\Models\Log;
use App\Models\Mesa;
use App\Models\Operacion;
use \App\Models\Staff;

class staffApi implements IApiUsable
{
    private static $nro_max_MesasPorMozo = 5;
    private static $largo_dni = 8;

    private static function AsignarMesasAMozos($mozo_id)
    {
        Mesa::where('estado', '=', EstadoDeMesa::abierta)->where('id_mozo_asignado', '=', 0)
            ->limit(self::$nro_max_MesasPorMozo)->update(['id_mozo_asignado' => $mozo_id]);
    }
    private static function Validar($params)
    {
        $dni = $params['dni'] ?? null;
        $nombre = $params['nombre'] ?? null;
        $apellido = $params['apellido'] ?? null;
        $clave = $params['clave'] ?? null;
        $sector = $params['sector'] ?? null;
        if (empty($dni) || empty($nombre) || empty($apellido) || empty($clave) || empty($sector))
            throw new Exception("Error, faltan datos.");
        $stf = new Staff();
        $stf->dni = str_replace('.', '', trim($dni));
        $stf->nombre = ucwords(strtolower(trim($nombre)));
        $stf->apellido = ucwords(strtolower(trim($apellido)));
        $stf->clave = HashClave($clave);
        $stf->sector = trim($sector);
        $dt = new DateTime("now", new DateTimeZone("America/Argentina/Buenos_Aires"));
        $stf->fecha_ing = $dt->format('Y-m-d H-i-s');
        $stf->estado = 3;
        if (!is_numeric($stf->dni) || strlen($stf->dni) != self::$largo_dni || !ctype_alpha($stf->nombre) || !ctype_alpha($stf->apellido))
            throw new Exception("Error, formato incorrecto.");
        if ($stf->sector < 1 || $stf->sector > 5)
            throw new Exception("No corresponde el sector.");
        if (!Staff::where('dni', '=', $stf->dni)->get()->isEmpty())
            throw new Exception("Ya existe el empleado con dni nro. " . $stf->dni . ".");
        return $stf;
    }
    private static function ValidarModif($params, $id)
    {
        $modif = Staff::find($id);
        if (!$modif)
            throw new Exception("El id ingresado no pertenece a un empleado existente.");
        $dni = $params['dni'] ?? $modif->dni;
        $nombre = $params['nombre'] ?? $modif->nombre;
        $apellido = $params['apellido'] ?? $modif->apellido;
        $sector = $params['sector'] ?? $modif->sector;
        $modif->dni = str_replace('.', '', trim($dni));
        $modif->nombre = ucwords(strtolower(trim($nombre)));
        $modif->apellido = ucwords(strtolower(trim($apellido)));
        $modif->sector = trim($sector);
        if (!is_numeric($modif->dni) || strlen($modif->dni) != self::$largo_dni || !ctype_alpha($modif->nombre) || !ctype_alpha($modif->apellido))
            throw new Exception("Error, formato incorrecto.");
        if ($modif->sector < 1 || $modif->sector > 5)
            throw new Exception("No corresponde el sector.");
        return $modif;
    }
    private static function ValidarLog($params)
    {
        $dni = $params['dni'] ?? null;
        $clave = $params['clave'] ?? null;
        $staff = Staff::where('dni', '=', $dni)->first();
        if (!$staff)
            throw new Exception("No existe un staff con ese dni. Regístrese.");
        if ($staff->clave != HashClave($clave))
            throw new Exception("Contraseña incorrecta.");
        return $staff;
    }
    public static function CrearLog($operacion,$staff_id,$staff_sector){
        $log = new Log();
        $log->operacion = $operacion;
        $log->id_staff = $staff_id;
        $log->sector = $staff_sector;
        $dt = new DateTime("now", new DateTimeZone("America/Argentina/Buenos_Aires"));
        $log->fecha = $dt->format('Y-m-d H-i-s');
        $log->save();
    }
    public function TraerUno($req, $res, $args)
    {
        $stf = Staff::find($args['id']);
        if (!$stf)
            $stf = "No se encontró el id " . $args['id']  . ".";
        $res->getBody()->write(json_encode(array("Staff: " . $args['id'] => $stf)));
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodos($req, $res, $args)
    {
        $lista = Staff::all();
        $res->getBody()->write(json_encode(array("personal" => $lista)));
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno($req, $res, $args)
    {
        try {
            $staff = self::Validar($req->getParsedBody());
            $staff->save();
        } catch (Exception $e) {
            return $res->withJson("Error:" . $e->getMessage(), 400)
                ->withHeader('Content-Type', 'application/json');
        }
        $res->getBody()->write(json_encode(array("mensaje" => "Alta de staff exitosa.")));
        return $res->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }
    public function BorrarUno($req, $res, $args)
    {
        $stf = Staff::find($args['id']);
        try {
            if (!$stf)
                throw new Exception("No se encontró el id " . $args['id']  . ".");
            $stf->estado = EstadoDeStaff::baja;
            if (!$stf->save() || !$stf->delete())
                throw new Exception("Error de sistema, el dato no se eliminó.");
        } catch (Exception $e) {
            return $res->withJson("Error:" . $e->getMessage(), 400)->withHeader('Content-Type', 'application/json');
        }
        $res->getBody()->write(json_encode(array("Mensaje" => "Se eliminó: " . $stf->nombre . ".")));
        return $res->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
    public function ModificarUno($req, $res, $args)
    {
        try {
            $modif = self::ValidarModif($req->getParsedBody(), $args['id']);
            if (!$modif->isDirty())
                throw new Exception("No se han realizado modificaciones.");
            if (!$modif->save())
                throw new Exception("Lo siento. Error interno del sistema al intentar modificar los datos.");
        } catch (Exception $e) {
            return $res->withJson(json_encode(array("Error:" => $e->getMessage())), 400)
                ->withHeader('Content-Type', 'application/json');;
        }
        $res->getBody()->write(json_encode(array("Éxito:" => $modif->nombre . " modificado correctamente.")));
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
    public function Loguear($req, $res, $args)
    {
        if ($req->isGet())
            return $res->withJson(json_encode(array("Mensaje" => "Login para nuestro staff. Si no trabajas con nosotros estas en el sitio equivocado.")), 200)
                ->withHeader('Content-Type', 'application/json');
        try {
            $staff = self::ValidarLog($req->getParsedBody());
            $staff->estado = EstadoDeStaff::disponible;
            $staff->save();
            $data = array(
                'id' => $staff->id,
                'dni' => $staff->dni,
                'nombre' => $staff->nombre,
                'sector' => $staff->sector
            );
            $token = Token::Crear($data);
            setcookie("token", $token, time() + 360, "/"); //6min
            if ($staff->sector != Sector::socio)
                self::CrearLog(OperacionStaff::login,$staff->id, $staff->sector);
            if ($staff->sector == Sector::mozo)
                self::AsignarMesasAMozos($staff->id);
        } catch (Exception $e) {
            return $res->withJson("Error:" . $e->getMessage(), 400)
                ->withHeader('Content-Type', 'application/json');
        }
        $res->getBody()->write(json_encode(
            array(
                "Mensaje" => "Logueo exitoso. Buona giornata! " . $staff->nombre
            )
        ));
        return $res->withStatus(201)->withHeader('Content-Type', 'application/json');
    }
}
