<?php
require_once './interfaces/IApiUsable.php';
include_once './utiles/hash.php';
include_once './utiles/enum.php';

use App\Models\Mesa;
use \App\Models\Staff as Staff;

class staffApi implements IApiUsable
{
    private static $nro_max_MesasPorMozo = 5;

    private static function AsignarMesasAMozos()
    {
        $mozosDisponibles = Staff::where('sector', '=', Sector::mozo)
            ->where('estado', '=', EstadoDeStaff::disponible)->get()->all();
        foreach ($mozosDisponibles as $mozo) {
            $asignadas = Mesa::where('id_mozo_asignado', '=', $mozo->id)->count();
            while ($asignadas < self::$nro_max_MesasPorMozo) {
                $mesaLibre = Mesa::where('estado', '=', EstadoDeMesa::abierta)
                    ->where('id_mozo_asignado', '=', 0)->take(1)->get();
                if ($mesaLibre->isEmpty())
                    break;
                $mesaLibre[0]->id_mozo_asignado = $mozo->id;
                $mesaLibre[0]->save();
                $asignadas++;
            }
            if($asignadas == self::$nro_max_MesasPorMozo){
                $mozo->estado = EstadoDeStaff::ocupado;
                $mozo->save;
            }
        }
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
        if (!is_numeric($stf->dni) || strlen($stf->dni) != 8 || !ctype_alpha($stf->nombre) || !ctype_alpha($stf->apellido))
            throw new Exception("Error, formato incorrecto.");
        if ($stf->sector < 1 || $stf->sector > 5)
            throw new Exception("No corresponde el sector.");
        if (!Staff::where('dni', '=', $stf->dni)->get()->isEmpty())
            throw new Exception("Ya existe el empleado con dni nro. " . $stf->dni . ".");
        return $stf;
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

    public function TraerUno($req, $res, $args)
    {
        return;
    }
    public function TraerTodos($req, $res, $args)
    {
        $lista = Staff::all();
        return $res->withJson(json_encode(array("personal" => $lista)), 200)
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
        return $res->withJson(json_encode(array("mensaje" => "Alta de staff exitosa.")), 201)
            ->withHeader('Content-Type', 'application/json');
    }
    public function BorrarUno($req, $res, $args)
    {
        return;
    }
    public function ModificarUno($req, $res, $args)
    {
        return;
    }
    public function Loguear($req, $res, $args)
    {
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
            $_SESSION['token'] = Token::Crear($data);
            self::AsignarMesasAMozos();
        } catch (Exception $e) {
            return $res->withJson("Error:" . $e->getMessage(), 400)
                ->withHeader('Content-Type', 'application/json');
        }
        return $res->withJson(json_encode(
            array(
                "mensaje" => "Logueo exitoso. Bienvenido " . $staff->nombre
            )
        ), 201)->withHeader('Content-Type', 'application/json');
    }
}
