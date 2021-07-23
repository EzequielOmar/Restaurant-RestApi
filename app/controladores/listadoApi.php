<?php

use App\Models\Comentario;
use App\Models\Factura;
use App\Models\Pedido;
use App\Models\Mesa;
use App\Models\Producto;
use App\Models\Staff;


class listadoApi
{
    public function PedidoVenta($req, $res, $args)
    {
        $take = $args['take'];
        $masVendido = Pedido::onlyTrashed()->selectRaw('id_producto, SUM(cantidad) as Vendidos')
            ->groupBy('id_producto')->orderBy('Vendidos', 'desc')->get()->take($take)->toArray();
        $menosVendido = Pedido::onlyTrashed()->selectRaw('id_producto, SUM(cantidad) as Vendidos')
            ->groupBy('id_producto')->orderBy('Vendidos', 'asc')->get()->take($take)->toArray();
        $res->getBody()->write(
            json_encode(
                array(
                    "Producto mas vendido: "  => $masVendido,
                    "Producto menos vendido: " => $menosVendido
                )
            )
        );
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    public function PedidoFueraDeTiempo($req, $res, $args)
    {
        $fueraTiempo = Pedido::withTrashed()
            ->whereRaw("(hora_listo != '00:00:00' AND (hora_tomado + hora_estimada) < hora_listo)")->get();
        $res->getBody()->write(
            json_encode(
                array(
                    "Entregados fuera de tiempo: "  => $fueraTiempo
                )
            )
        );
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    public function PedidoCancelado($req, $res, $args)
    {
        $fueraTiempo = Pedido::withTrashed()
            ->where('estado', '=', EstadoDePedido::cancelado)->get();
        $res->getBody()->write(
            json_encode(
                array(
                    "Entregados fuera de tiempo: "  => $fueraTiempo
                )
            )
        );
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    public function MesaUsada($req, $res, $args)
    {
        $take = $args['take'];
        $masUsada = Mesa::selectRaw("mesa.codigo, SUM(pedido.hora_cierre - pedido.hora_comandado) as TiempoDeUso")
            ->join('pedido', 'mesa.codigo', '=', 'pedido.codigo_mesa')
            ->groupBy('mesa.codigo')->orderBy('TiempoDeUso', 'desc')->take($take)->get();
        $menosUsada = Mesa::selectRaw("mesa.codigo, SUM(pedido.hora_cierre - pedido.hora_comandado) as TiempoDeUso")
            ->join('pedido', 'mesa.codigo', '=', 'pedido.codigo_mesa')
            ->groupBy('mesa.codigo')->orderBy('TiempoDeUso', 'asc')->take($take)->get();
        $res->getBody()->write(
            json_encode(
                array(
                    "Mesa mas usada: "  => $masUsada,
                    "Mesa menos usada: " => $menosUsada
                )
            )
        );
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
    public function MesaFactura($req, $res, $args)
    {
        $desde = $req->getParams()['desde'];
        $hasta = $req->getParams()['hasta'];
        $mayorFacturacion = Mesa::selectRaw("mesa.codigo, SUM(factura.monto) as TotalFactura")
            ->join('factura', 'mesa.id', '=', 'factura.id_mesa')
            ->groupBy('mesa.codigo')->orderBy('TotalFactura', 'desc')->take(1)->get();
        $menorFacturacion = Mesa::selectRaw("mesa.codigo, SUM(factura.monto) as TotalFactura")
            ->join('factura', 'mesa.id', '=', 'factura.id_mesa')
            ->groupBy('mesa.codigo')->orderBy('TotalFactura', 'asc')->take(1)->get();
        $mayorFactura = Mesa::selectRaw("mesa.codigo, factura.monto")
            ->join('factura', 'mesa.id', '=', 'factura.id_mesa')
            ->groupBy('mesa.codigo', 'factura.monto')->orderBy('factura.monto', 'desc')->take(1)->get();
        $menorFactura = Mesa::selectRaw("mesa.codigo, factura.monto")
            ->join('factura', 'mesa.id', '=', 'factura.id_mesa')
            ->groupBy('mesa.codigo', 'factura.monto')->orderBy('factura.monto', 'asc')->take(1)->get();

        if (isset($desde) && isset($hasta)) {
            $filtro = Mesa::selectRaw("mesa.codigo, SUM(factura.monto) as TotalFactura")
                ->join('factura', 'mesa.id', '=', 'factura.id_mesa')
                ->whereRaw("(factura.fecha >= '" . $desde . " 00:00:00 '" . " AND factura.fecha <= '" . $hasta . " 00:00:00' " . ")")
                ->groupBy('mesa.codigo')->get();
            $res->getBody()->write(json_encode(array("Total de facturas por mesa entre " . $desde . " y " . $hasta => $filtro)));
        }
        $res->getBody()->write(
            json_encode(
                array(
                    "Mesa con mayor factura total: "  => $mayorFacturacion,
                    "Mesa con menor factura total: "  => $menorFacturacion,
                    "Mesa con factura mas alta: "  => $mayorFactura,
                    "Mesa con factura mas baja: "  => $menorFactura,
                )
            )
        );
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    public function MesaComentario($req, $res, $args)
    {
        $take = $args['take'];
        $mejores = Comentario::where('comentario', '!=', '')
            ->orderByRaw('((rate_mesa+rate_rest+rate_mozo+rate_cocina)/4) desc')
            ->take($take)->get();
        $peores = Comentario::where('comentario', '!=', '')
            ->orderByRaw('((rate_mesa+rate_rest+rate_mozo+rate_cocina)/4) asc')
            ->take($take)->get();
        $res->getBody()->write(
            json_encode(
                array(
                    "Mejores comentarios: "  => $mejores,
                    "Peores comentarios: " => $peores
                )
            )
        );
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}
