<?php
define('FPDF_FONTPATH', 'includes/font/');
require("includes/fpdf.php");

class informe_contacto extends FPDF {
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
        $this->Cell(0, 6, "Listado de Clientes a llamar", "B", 0, 'C');
        $this->Ln();
        $this->SetFont('Times', 'B', 10);
        $this->Cell(0, 6, "Desde fecha:" . $this->desdefecha . " Hasta fecha:" . $this->hastafecha , 0, 0, 'R');
        $this->Ln();
        $this->Cell(30, 6, "Cod Clienteº", "B", 0, "L");
        $this->Cell(50, 6, "Nombre", "B", 0, "L");
        $this->Cell(77, 6, "Nombre Comercial", "B", 0, "L");
        $this->Cell(30, 6, "Telefono 1", "B", 0, "R");
        $this->Cell(30, 6, "Telefono 2", "B", 0, "R");
        $this->Cell(30, 6, "Telefono 3", "B", 0, "R");
        $this->Cell(30, 6, "Celular", "B", 0, "R");
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
        $fecha_contac = date('j'); // Toma el dia como un numero, fecha actual del sistema.
        $dia_contac = strftime('%A'); // Toma el nombre del dia, de la fecha actual del sistema.

        ibase_timefmt("%d/%m/%Y", IBASE_TIMESTAMP);
        $query = "SELECT IDCLIENTE, CLIENOMBRE, CLIENOMCOM, CLIENIT, CLIETEL1, CLIETEL2, CLIETEL3, CLIEMOVIL FROM CLIENTES
WHERE FECHACONTAC='$fecha_contac' OR DIACONTAC='$dia_contac' ";

        /*$where = build_where("A.MOVITIPO", $this->tipomov, "C",
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

        if ($where) $query .= " WHERE " . $where;*/

        $query .= " ORDER BY IDCLIENTE";

        $result = execute_query($this->db, $query);
        while (($row = fetch_object($result))) {
            // Aqui pinta las lineas de detalle
            $this->Cell(30, 6, $row->IDCLIENTE, 0, 0, "L");
            $y = $this->GetY();
            $this->Cell(50, 6, $row->CLIENOMBRE, 0, 0, "L");
            $this->Cell(77, 6, $row->CLIENOMCOM, 0, 0, "L");
            $this->XCell($y, 30, 6, $row->CLIETEL1, 0, "R", 0);
            $this->XCell($Y, 30, 6, $row->CLIETEL2, 0, "R", 0);
            $this->XCell($y, 30, 6, $row->CLIETEL3, 0, 0, "R", 0);
            $this->XCell($y, 30, 6, $row->CLIEMOVIL, 0, "R", 0);
            $this->XLn();
            
        } 
        
        free_result($result);
    } 
} 
