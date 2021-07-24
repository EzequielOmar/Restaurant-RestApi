<?php

use App\Models\Cliente;
use App\Models\Comentario;
use App\Models\Log;
use App\Models\Pedido;
use App\Models\Mesa;
use App\Models\Staff;

require_once './utiles/pdf.php';
require_once './utiles/enum.php';


class listadoApi
{
    public function StaffLogin($req, $res, $args)
    {
        $login = Log::where('operacion', '=', OperacionStaff::login)->get()->toArray();
        $res->getBody()->write(
            json_encode(
                array(
                    "Lista de Login: "  => $login
                )
            )
        );
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    public function StaffSector($req, $res, $args)
    {
        $sector = $args['sector'];
        switch ($sector) {
            case "mozo":
                $i = 2;
                break;
            case "bar":
                $i = 3;
                break;
            case "cocina":
                $i = 4;
                break;
            case "cerveza":
                $i = 5;
                break;
        }
        $opsPorSector = Log::selectRaw('sector, SUM(1) as Cantidad')
            ->groupBy('sector')->get()->toArray();
        if (isset($i))
            $opsTodosSector = Log::where('sector', '=', $i)->selectRaw('id_staff, SUM(1) as Cantidad')
                ->groupBy('id_staff')->get()->toArray();
        else
            $opsTodosSector = 'Error al ingresar el sector';
        $res->getBody()->write(
            json_encode(
                array(
                    "Cantidad de ops. por sector "  => $opsPorSector,
                    "Ops. por staff del sector " . $sector . ": " => $opsTodosSector
                )
            )
        );
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    public function StaffId($req, $res, $args)
    {
        $id = $args['id'];
        $opsPorId = Log::where('id_staff','=',$id)->orderBy('fecha', 'asc')->get()->toArray();
        if(!$opsPorId)
            $opsPorId = "El id ingresado es incorrecto, o el empleado no tiene ningun log, échelo.";
        $res->getBody()->write(
            json_encode(
                array(
                    "Ops. empleado id ".$id.": " => $opsPorId
                )
            )
        );
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

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

    public function PdfClientes($req, $res, $args)
    {
        $clientes = Cliente::withTrashed()->get()->all();
        $pdf = new MYPDFF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('Comanda-app');
        $pdf->SetTitle('Clientes');
        $pdf->SetHeaderData('', 0, 'Listado de clientes de ComandaApp.', 'by comanda-app.com');
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->AddPage();
        $pdf->Clientes($clientes);
        $res->getBody()->write($pdf->Output('clientes.pdf', 'I'));
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/pdf');
    }

    public function PdfStaff($req, $res, $args)
    {
        $staff = Staff::withTrashed()->get()->all();
        $pdf = new MYPDFF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('Comanda-app');
        $pdf->SetTitle('Staff');
        $pdf->SetHeaderData('', 0, 'Listado de staff de ComandaApp.', 'by comanda-app.com');
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->AddPage();
        $pdf->Staff($staff);
        $res->getBody()->write($pdf->Output('staff.pdf', 'I'));
        return $res->withStatus(200)
            ->withHeader('Content-Type', 'application/pdf');
    }
}
