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
        $this->Cell(0, 6, "Listado de cantidad de productos solicitados por el cliente ", "B", 0, 'C');
        $this->Ln();
        $this->SetFont('Times', 'B', 10);
        $this->Cell(0, 6, "Desde fecha:" . $this->desdefecha . " Hasta fecha:" . $this->hastafecha , 0, 0, 'R');
        $this->Ln();
        $this->Cell(30, 6, "Cod Cliente", "B", 0, "L");
        $this->Cell(70, 6, "Nombre", "B", 0, "L");
        $this->Cell(70, 6, "Productos solicitados", "B", 0, "R");
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
        $query = "SELECT A.TOTCANT, B.IDCLIENTE, B.CLIENOMBRE FROM TOTALES A INNER JOIN CLIENTES B ON A.IDCLIENTE = B.IDCLIENTE";

        if ($where) $query .= " WHERE " . $where;

        $query .= " ORDER BY A.TOTCANT ASC";

        $result = execute_query($this->db, $query);
        while (($row = fetch_object($result))) {

            // Aqui pinta las lineas de detalle
            $this->Cell(30, 6, $row->IDCLIENTE, 0, 0, "L");
            $this->Cell(70, 6, $row->CLIENOMBRE, 0, 0, "L");
            $this->Cell(70, 6, $row->TOTCANT, 0, 0, "R");
            $this->Ln(4);
            $nuevacantidad="";
           
        }
        free_result($result);
    } 
} 

?>
