<?php
define('FPDF_FONTPATH', 'includes/font/');
require("includes/fpdf.php");

class informe_producto extends FPDF {
    var $desdefecha;
    var $hastafecha;
    var $desdearticulo;
    var $hastaarticulo;

    var $db;
    var $maximo;

    function Header() // Cabecera del informe
    {
        $this->SetFont('Arial', '', 14);
        $this->Cell(0, 6, "Listado total x referencia de producto", "B", 0, 'C');
        $this->Ln();
        $this->SetFont('Times', 'B', 12);
        $this->Cell(0, 6, "Desde fecha:" . $this->desdefecha . " Hasta fecha:" . $this->hastafecha , 0, 0, 'R');
        $this->Ln();
        $this->Cell(20, 6, "Tip Mov", "B", 0, "L");
        $this->Cell(30, 6, "Cod Art", "B", 0, "L");
        $this->Cell(50, 6, "Nombre", "B", 0, "L");
        $this->Cell(37, 6, "Cantidad", "B", 0, "R");
        $this->Cell(50, 6, "Subtotal", "B", 0, "R");
        $this->Cell(40, 6, "Descuento", "B", 0, "R");
        $this->Cell(50, 6, "Total", "B", 0, "R");
        $this->Ln(8);
        $this->SetFont('Times', '', 10);
    } 

    function XCell($y, $w, $h, $txt, $border, $align, $fill)
    { 
        // $this->SetY($y);
        $x = $this->GetX();
        $this->MultiCell($w, $h, $txt, $border, $align, $fill);
        if ($this->GetY() > $this->maximo)
            $this->maximo = $this->GetY();
        $this->SetY($y);
        $this->SetX($x + $w);
    } 

    function XLn()
    {
        $this->SetX(0);
        $this->SetY($this->maximo);
        $this->maximo = 0;
    } 

    function Detalles()
    {

        $total_sa = 0; // Suma de los importes
        $total_en = 0;
        $total_desa = 0;
        $total_deen = 0;
        $total_imsa = 0;
        $total_imen = 0;
        
        $query = "SELECT * FROM ARTICULOS ";

        $filtroart = build_beetwen("IDARTICULO", $this->desdearticulo, $this->hastaarticulo, "C");
        if ($filtroart && $where)
            $where .= " AND " . $filtroart;
        else if ($filtroart)
            $where = $filtroart;

        if ($where) $query .= " WHERE " . $where;

        $query .= " ORDER BY IDARTICULO";

        $result = execute_query($this->db, $query);
        while (($row = fetch_object($result))) {

        $this->idarticulo = $row->IDARTICULO;
        $this->artidesccorta = $row->ARTIDESCCORTA;

        $suma_sa = 0;
        $suma_casa = 0;
        $suma_imsa = 0;
        $descuentosa = 0;
        
        ibase_timefmt("%d/%m/%Y", IBASE_TIMESTAMP);
        $query = "SELECT * FROM MOVIMIENTOS";
        $this->tipomov = "SA";
        $where = build_where("MOVITIPO", $this->tipomov, "C",
            "IDARTICULO", $this->idarticulo, "C");

        $filtrofecha = build_beetwen("MOVIFECHA", formatdate($this->desdefecha), formatdate($this->hastafecha), "C");
        if ($filtrofecha && $where)
            $where .= " AND " . $filtrofecha;
        else if ($filtrofecha)
            $where = $filtrofecha;

        if ($where) $query .= " WHERE " . $where;

        $query .= " ORDER BY MOVIFECHA";

        $result1 = execute_query($this->db, $query);
        while (($row1 = fetch_object($result1))) {
            // Aqui pinta las lineas de detalle
        $valor=$row1->MOVIPRECIO * $row1->MOVICANTIDAD;
        $suma_sa += $valor;
        $suma_casa += $row1->MOVICANTIDAD;
        $suma_imsa += $row1->MOVIIMPORTE;

      }
        $descuentosa = $suma_sa - $suma_imsa;
        $total_sa+=$suma_sa;
        $total_desa+=$descuentosa;
        $total_imsa+=$suma_imsa;
        $this->Cell(20, 6, $this->tipomov, 0, 0, "L");
        $this->Cell(30, 6, $row->IDARTICULO, 0, 0, "L");
        $y = $this->GetY();
        $this->Cell(50, 6, $row->ARTIDESCCORTA, 0, 0, "L");
        $this->Cell(37, 6, number_format($suma_casa), 0, 0, "R");
        $this->XCell($y, 50, 6, "$ " . number_format($suma_sa), 0, "R", 0);
        $this->XCell($y, 40, 6, "$ " . number_format($descuentosa), 0, "R", 0);
        $this->XCell($y, 50, 6, "$ " . number_format($suma_imsa), 0, "R", 0);
        $this->XLn();
        free_result($result1);
        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        $suma_en= 0;
        $suma_caen = 0;
        $suma_imen = 0;
        $descuentoen = 0;

        ibase_timefmt("%d/%m/%Y", IBASE_TIMESTAMP);
        $query = "SELECT * FROM MOVIMIENTOS";
        $this->tipomov = "EN";
        $where = build_where("MOVITIPO", $this->tipomov, "C",
            "IDARTICULO", $this->idarticulo, "C");

        $filtrofecha = build_beetwen("MOVIFECHA", formatdate($this->desdefecha), formatdate($this->hastafecha), "C");
        if ($filtrofecha && $where)
            $where .= " AND " . $filtrofecha;
        else if ($filtrofecha)
            $where = $filtrofecha;

        if ($where) $query .= " WHERE " . $where;

        $query .= " ORDER BY MOVIFECHA";

        $result2 = execute_query($this->db, $query);
        while (($row2 = fetch_object($result2))) {
            // Aqui pinta las lineas de detalle
        $valor=$row2->MOVIPRECIO * $row2->MOVICANTIDAD;
        $suma_en += $valor;
        $suma_caen += $row2->MOVICANTIDAD;
        $suma_imen += $row2->MOVIIMPORTE;

      }
        $descuentoen = $suma_en - $suma_imen;
        $total_en+=$suma_en;
        $total_deen+=$descuentoen;
        $total_imen+=$suma_imen;
        $this->Cell(20, 6, $this->tipomov, 0, 0, "L");
        $this->Cell(30, 6, $row->IDARTICULO, 0, 0, "L");
        $y = $this->GetY();
        $this->Cell(50, 6, $row->ARTIDESCCORTA, 0, 0, "L");
        $this->Cell(37, 6, number_format($suma_caen), 0, 0, "R");
        $this->XCell($y, 50, 6, "$ " . number_format($suma_en), 0, "R", 0);
        $this->XCell($y, 40, 6, "$ " . number_format($descuentoen), 0, "R", 0);
        $this->XCell($y, 50, 6, "$ " . number_format($suma_imen), 0, "R", 0);
        $this->XLn();
        free_result($result2);

        }
        $this->Ln(8);
        $this->SetFont("Times", "", 12);
        $this->Cell(40, 6, "Subtotal Salidas:", 0, 0, "L");
        $this->SetFont("Times", "IB", 14);
        $this->Cell(40, 6, "$ ". number_format($total_sa), 0, 0, "R");
        $this->SetFont("Times", "", 12);
        $this->Cell(40, 6, "Subtotal Entradas:", 0, 0, "L");
        $this->SetFont("Times", "IB", 14);
        $this->Cell(40, 6, "$ ". number_format($total_en), 0, 0, "R");
        $this->Ln(8);
        $this->SetFont("Times", "", 12);
        $this->Cell(40, 6, "Descuento Salidas:", 0, 0, "L");
        $this->SetFont("Times", "IB", 14);
        $this->Cell(40, 6, "$ ". number_format($total_desa), 0, 0, "R");
        $this->SetFont("Times", "", 12);
        $this->Cell(40, 6, "Descuento Entradas:", 0, 0, "L");
        $this->SetFont("Times", "IB", 14);
        $this->Cell(40, 6, "$ ". number_format($total_deen), 0, 0, "R");
        $this->Ln(8);
        $this->SetFont("Times", "", 12);
        $this->Cell(40, 6, "Total Salidas:", 0, 0, "L");
        $this->SetFont("Times", "IB", 14);
        $this->Cell(40, 6, "$ ". number_format($total_imsa), 0, 0, "R");
        $this->SetFont("Times", "", 12);
        $this->Cell(40, 6, "Total Entradas:", 0, 0, "L");
        $this->SetFont("Times", "IB", 14);
        $this->Cell(40, 6, "$ ". number_format($total_imen), 0, 0, "R");

        free_result($result);
    } 
} 
