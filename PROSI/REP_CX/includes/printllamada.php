<?php
define('FPDF_FONTPATH', 'includes/font/');
require("includes/fpdf.php");

class informe_llamada extends FPDF {
    var $desdefecha;
    var $hastafecha;
    var $tipollamada;
    var $nombrecliente;
    var $idobsllamada;

    var $db;
    var $maximo;

    function Header() // Cabecera del informe
    {
        $this->SetFont('Arial', '', 14);
        $this->Cell(0, 6, "Listado de Llamadas", "B", 0, 'C');
        $this->Ln();
        $this->SetFont('Times', 'B', 10);
        $this->Cell(0, 6, "Desde fecha:" . $this->desdefecha . " Hasta fecha:" . $this->hastafecha , 0, 0, 'R');
        $this->Ln();
        $this->Cell(10, 6, "Nº", "B", 0, "L");
        $this->Cell(20, 6, "Tipo", "B", 0, "L");
        $this->Cell(40, 6, "Fecha", "B", 0, "L");
        $this->Cell(65, 6, "Cliente", "B", 0, "L");
        $this->Cell(50, 6, "Obs Llamada", "B", 0, "L");
        $this->Cell(45, 6, "Se habla con", "B", 0, "L");
        $this->Cell(47, 6, "Realizada por", "B", 0, "L");
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
        

        ibase_timefmt("%d/%m/%Y", IBASE_TIMESTAMP);
        $query = "SELECT A.*, B.CLIENOMBRE AS CLIENOMBRE, C.DESOBSLLAMADA AS DESOBSLLAMADA, D.NOMBRE FROM LLAMADAS "
         . " A LEFT JOIN CLIENTES B ON A.IDCLIENTE = B.IDCLIENTE LEFT JOIN OBS_LLAMADA C ON A.OBSERVACION = C.IDOBSLLAMADA LEFT JOIN USUARIOS D ON A.IDUSUARIO = D.IDUSUARIO ";

        $where = build_where("A.TIPOLLAMADA", $this->tipollamada, "C",
            "B.CLIENOMBRE", $this->nombrecliente, "C",
            "A.OBSERVACION", $this->idobsllamada, "C");

        $filtrofecha = build_beetwen("A.FECHALLAMADA", formatdate($this->desdefecha), formatdate($this->hastafecha), "C");
        if ($filtrofecha && $where)
            $where .= " AND " . $filtrofecha;
        else if ($filtrofecha)
            $where = $filtrofecha;


        if ($where) $query .= " WHERE " . $where;

        $query .= " ORDER BY A.FECHALLAMADA";

        $result = execute_query($this->db, $query);
        while (($row = fetch_object($result))) {
            // Aqui pinta las lineas de detalle
            $this->Cell(10, 6, $row->IDLLAMADA, 0, 0, "L");
            $y = $this->GetY();
            $this->Cell(20, 6, $row->TIPOLLAMADA, 0, 0, "L");
            $this->Cell(40, 6, $row->FECHALLAMADA, 0, 0, "L");
            $this->XCell($y, 65, 6, $row->CLIENOMBRE, 0, "L", 0);
            $this->XCell($y, 50, 6, $row->DESOBSLLAMADA, 0, "L", 0);
            $this->XCell($y, 45, 6, $row->CONTESTA, 0, "L", 0);
            $this->XCell($y, 47, 6, $row->NOMBRE, 0, "L", 0);
            $this->XLn();
            
        }
        
    } 
} 
