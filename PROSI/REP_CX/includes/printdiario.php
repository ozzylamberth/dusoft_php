<?php
define('FPDF_FONTPATH', 'includes/font/');
require("includes/fpdf.php");

class informe_diario extends FPDF {
    var $desdefecha;
    var $hastafecha;
    var $desdearticulo;
    var $hastaarticulo;
    var $tipomov;
    var $nombrecliente;
    var $nombreproveedor;

    var $db;
    var $maximo;

    function Header() // Cabecera del informe
    {
        $this->SetFont('Arial', '', 14);
        $this->Cell(0, 6, "Listado de diario de movimientos", "B", 0, 'C');
        $this->Ln();
        $this->SetFont('Times', 'B', 10);
        $this->Cell(0, 6, "Desde fecha:" . $this->desdefecha . " Hasta fecha:" . $this->hastafecha , 0, 0, 'R');
        $this->Ln();
        $this->Cell(10, 6, "Nº", "B", 0, "L");
        $this->Cell(10, 6, "Tipo", "B", 0, "L");
        $this->Cell(20, 6, "Fecha", "B", 0, "L");
        $this->Cell(60, 6, "Cliente", "B", 0, "L");
        $this->Cell(60, 6, "Proveedor", "B", 0, "L");
        $this->Cell(10, 6, "Cod.", "B", 0, "L");
        $this->Cell(50, 6, "Descripción", "B", 0, "L");
        $this->Cell(15, 6, "Cant.", "B", 0, "R");
        $this->Cell(20, 6, "Precio", "B", 0, "R");
        $this->Cell(0, 6, "Importe", "B", 0, "R");
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
        $suma_sa = 0; // Suma de los importes
        $suma_en = 0;

        ibase_timefmt("%d/%m/%Y", IBASE_TIMESTAMP);
        $query = "SELECT A.*, B.CLIENOMBRE AS NOMBRE_CLI, C.PROVNOMBRE AS NOMBRE_PRO, D.IDARTICULO, D.ARTIDESCCORTA FROM MOVIMIENTOS "
         . " A LEFT JOIN CLIENTES B ON A.IDCLIENTE = B.IDCLIENTE LEFT JOIN PROVEEDORES C ON A.IDPROVEEDOR = C.IDPROVEEDOR "
         . " INNER JOIN ARTICULOS D ON D.IDARTICULO = A.IDARTICULO ";

        $where = build_where("A.MOVITIPO", $this->tipomov, "C",
            "B.CLIENOMBRE", $this->nombrecliente, "C",
            "C.PROVNOMBRE", $this->nombreproveedor, "C");

        $filtrofecha = build_beetwen("A.MOVIFECHA", formatdate($this->desdefecha), formatdate($this->hastafecha), "C");
        if ($filtrofecha && $where)
            $where .= " AND " . $filtrofecha;
        else if ($filtrofecha)
            $where = $filtrofecha;

        $filtroart = build_beetwen("A.IDARTICULO", $this->desdearticulo, $this->hastaarticulo, "C");
        if ($filtroart && $where)
            $where .= " AND " . $filtroart;
        else if ($filtroart)
            $where = $filtroart;

        if ($where) $query .= " WHERE " . $where;

        $query .= " ORDER BY A.MOVIFECHA";

        $result = execute_query($this->db, $query);
        while (($row = fetch_object($result))) {
            // Aqui pinta las lineas de detalle
            $this->Cell(10, 6, $row->IDMOVIMIENTO, 0, 0, "L");
            $y = $this->GetY();
            $this->Cell(10, 6, $row->MOVITIPO, 0, 0, "L");
            $this->Cell(20, 6, $row->MOVIFECHA, 0, 0, "L");
            $this->XCell($y, 60, 6, $row->NOMBRE_CLI, 0, "L", 0);
            $this->XCell($y, 60, 6, $row->NOMBRE_PRO, 0, "L", 0);
            $this->Cell(10, 6, $row->IDARTICULO, 0, 0, "L", 0);
            $this->XCell($y, 50, 6, $row->ARTIDESCCORTA, 0, "L", 0);
            $this->Cell(15, 6, $row->MOVICANTIDAD, 0, 0, "R");
            $this->Cell(20, 6, number_format($row->MOVIPRECIO), 0, 0, "R");
            $this->Cell(0, 6, number_format($row->MOVIIMPORTE), 0, 0, "R");
            $this->XLn();
            if ($row->MOVITIPO == "SA")
                $suma_sa += $row->MOVIIMPORTE;
            else
                $suma_en += $row->MOVIIMPORTE;
        }
        $this->Ln(8);
        $this->SetFont("Times", "", 12);
        $this->Cell(40, 6, "Importe Salidas:", 0, 0, "L");
        $this->SetFont("Times", "IB", 14);
        $this->Cell(0, 6, "$ " . number_format($suma_sa), 0, 0, "L");
        $this->Ln(8);
        $this->SetFont("Times", "", 12);
        $this->Cell(40, 6, "Importe Entradas:", 0, 0, "L");
        $this->SetFont("Times", "IB", 14);
        $this->Cell(0, 6, "$ " . number_format($suma_en), 0, 0, "L");
        free_result($result);
    } 
} 
