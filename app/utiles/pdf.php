<?php


class MYPDFF extends TCPDF
{
    public function Clientes($clientes)
    {
        $header = array(
            'id', 'mail', 'nombre', 'apellido', 'cel', 'fecha_ing', 'fecha_baja'
        );
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        $w = array(8, 55, 25, 25, 25, 25, 25);
        for ($i = 0; $i < count($w); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $fill = 0;
        foreach ($clientes as $row) {
            $this->Cell($w[0], 6, $row->id, 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row->mail, 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $row->nombre, 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 6, $row->apellido, 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 6, $row->cel, 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 6, substr($row->fecha_ing, 0, 10), 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 6,  substr($row->fecha_baja, 0, 10), 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
    public function Staff($clientes)
    {
        $header = array(
            'id', 'dni', 'nombre', 'apellido', 'sector', 'fecha_ing', 'fecha_baja'
        );
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        $w = array(8, 25, 25, 25, 15, 25, 25);
        for ($i = 0; $i < count($w); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $fill = 0;
        foreach ($clientes as $row) {
            $this->Cell($w[0], 6, $row->id, 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row->dni, 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $row->nombre, 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 6, $row->apellido, 'LR', 0, 'R', $fill);
            $this->Cell($w[4], 6, $row->sector, 'LR', 0, 'R', $fill);
            $this->Cell($w[5], 6, substr($row->fecha_ing, 0, 10), 'LR', 0, 'R', $fill);
            if (!$row->fecha_baja)
                $row->fecha_baja = '-';
            $this->Cell($w[5], 6,  substr($row->fecha_baja, 0, 10), 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}
