<?php

use App\Models\Cliente;

require_once './modelos/cliente.php';
include_once './utiles/hash.php';

class clienteApi 
{
    private static function Validar($params)
    {
        $mail = $params['mail'] ?? null;
        $nombre = $params['nombre'] ?? null;
        $apellido = $params['apellido'] ?? null;
        $clave = $params['clave'] ?? null;
        $cel = $params['cel'] ?? null;
        if (empty($mail) || empty($nombre) || empty($apellido) || empty($clave) || empty($cel))
            throw new Exception("Error, faltan datos.");
        $cli = new Cliente();
        $cli->mail = trim($mail);
        $cli->nombre = ucwords(strtolower(trim($nombre)));
        $cli->apellido = ucwords(strtolower(trim($apellido)));
        $cli->clave = HashClave($clave);
        $cli->cel = trim($cel);
        $dt = new DateTime("now", new DateTimeZone("America/Argentina/Buenos_Aires"));
        if (
            !str_contains($cli->mail, "@") || !str_contains($cli->mail, ".com")
            || !ctype_alpha($cli->nombre) || !ctype_alpha($cli->apellido) ||
            !is_numeric($cli->cel) || strlen($cli->cel) < 10
        )
            throw new Exception("Error, formato incorrecto.");
        if (!Cliente::where('mail', '=', $cli->mail)->get()->isEmpty())
            throw new Exception("Ya existe un cliente con el mail " . $cli->mail . ".");
        return $cli;
    }

    public static function ValidarLog($params)
    {
        $mail = $params['mail'] ?? null;
        $clave = $params['clave'] ?? null;
        $cli = Cliente::where('mail', '=', $mail)->first();
        if (!$cli)
            throw new Exception("No existe un cliente con ese mail. Regístrese.");
        if ($cli->clave != HashClave($clave))
            throw new Exception("Contraseña incorrecta.");
        return $cli;
    }

    public function TraerUno($req, $res, $args)
    {
        return;
    }

    public function TraerTodos($req, $res, $args)
    {
        $lista = Cliente::all();
        $res->getBody()->write(json_encode(array("clientes" => $lista)));
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($req, $res, $args)
    {
        if ($req->isGet()) {
            $res->getBody()->write(json_encode(array("Mensaje" => "Bienvenido a Comanda. Registre sus datos para ingresar.")));
            return $res->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        }
        try {
            $cliente = self::Validar($req->getParsedBody());
            $cliente->save();
        } catch (Exception $e) {
            return $res->withJson("Error:" . $e->getMessage(), 400);
        }
        $res->getBody()->write(json_encode(
            array("mensaje" => "Registro exitoso. ¡Bienvenid@ " . $cliente->nombre . "!")
        ));
        return $res->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function Loguear($req, $res, $args)
    {
        if ($req->isGet()) {
            $res->getBody()->write(json_encode(array("Mensaje" => "Por favor, ingrese los datos para realizar el login.")));
            return $res->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        }
        try {
            $cliente = self::ValidarLog($req->getParsedBody());
            $data = array(
                'id' => $cliente->id,
                'mail' => $cliente->mail,
                'nombre' => $cliente->nombre,
                'cel' => $cliente->cel
            );
            $token = Token::Crear($data);
            setcookie("token", $token, time() + 360, "/"); //6min
        } catch (Exception $e) {
            return $res->withJson("Error:" . $e->getMessage(), 400);
        }
        $res->getBody()->write(json_encode(
            array("mensaje" => "Logueo exitoso. ¿Cómo has estado, " . $cliente->nombre . "?")
        ));
        return $res->withStatus(201)->withHeader('Content-Type', 'application/json');
    }
}
