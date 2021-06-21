<?php

use App\Controllers\Container;
use App\Models\Comentario;
use App\Models\Factura;

require_once './modelos/factura.php';
require_once './modelos/comentario.php';
require_once './utiles/container.php';


class cierreApi extends Container
{
    private static function Validar($data, $id_mesa)
    {
        $rate_mesa = $data['rate_mesa'] ?? null;
        $rate_rest = $data['rate_rest'] ?? null;
        $rate_mozo = $data['rate_mozo'] ?? null;
        $rate_cocina = $data['rate_cocina'] ?? null;
        $comentario = $data['comentario'] ?? null;
        if (empty($rate_mesa) || empty($rate_rest) || empty($rate_mozo) || empty($rate_cocina))
            throw new Exception("Error, faltan datos.");
        if (!is_numeric($rate_mesa) || !is_numeric($rate_rest)  || !is_numeric($rate_mozo)  || !is_numeric($rate_cocina))
            throw new Exception("Error, formato incorrecto.");
        $coment = new Comentario();
        $coment->id_mesa = $id_mesa;
        $coment->rate_mesa = intval(trim($rate_mesa));
        $coment->rate_rest = intval(trim($rate_rest));
        $coment->rate_mozo = intval(trim($rate_mozo));
        $coment->rate_cocina = intval(trim($rate_cocina));
        $coment->comentario = $comentario;
        if (
            $coment->rate_mesa < 0 || $coment->rate_mesa > 10  ||
            $coment->rate_rest < 0 || $coment->rate_rest > 10  ||
            $coment->rate_mozo < 0 || $coment->rate_mozo > 10  ||
            $coment->rate_cocina < 0 || $coment->rate_cocina > 10
        )
            throw new Exception("Error, valor incorrecto.");
        $dt = new DateTime("now", new DateTimeZone("America/Argentina/Buenos_Aires"));
        $coment->fecha = $dt->format('Y-m-d H-i-s');
        return $coment;
    }
    /**
     * if Get -> muestra datos de factura (solo si el pedido fue facturado)
     *  y pide que se deje comentario
     * if Post -> valida, envía y guarda el comentario al servidor.
     */
    public function CargarUno($req, $res, $args)
    {
        $data = $req->getParams();
        if ($req->isGet()) {
            $id_factura = $data["id_factura"];
            if (!$id_factura)
                return $res->withJson(json_encode(
                    array(
                        "Mensaje" => "Sentimos que haya tenido que cancelar su pedido. 
                        Por favor, complete el formulario y háganos saber su opinión."
                    )
                ), 200)->withHeader('Content-Type', 'application/json');
            return $res->withJson(json_encode(
                array(
                    "Mensaje" => "Gracias! Ojalá lo haya disfrutado. " . $req->getAttribute('rate_rest') .
                        ", no te vallas sin dejarnos tu comentario.",
                    "Factura" => Factura::find($id_factura)
                )
            ), 200)->withHeader('Content-Type', 'application/json');
        }
        //post
        try {
            $coment = self::Validar($req->getParsedBody(), $data["id_mesa"]);
            $coment->save();
        } catch (Exception $e) {
            return $res->withJson("Error:" . $e->getMessage(), 400)
                ->withHeader('Content-Type', 'application/json');
        }
        return $res->withJson(json_encode(array("Mensaje" => "Gracias por dejarnos tu opinión.")), 201)
            ->withHeader('Content-Type', 'application/json');
    }
}
