<?php
define('FPDF_FONTPATH', 'includes/font/');
require("includes/fpdf.php");

class informe_pedidos extends FPDF {
    var $desdefecha;
    var $hastafecha;
    var $idruta;

    var $db;
    var $maximo;

    function Header() // Cabecera del informe
    {
        $this->SetFont('Arial', '', 14);
        $this->Cell(0, 6, "Listado de Pedidos", "B", 0, 'C');
        $this->Ln();
        $this->SetFont('Times', 'B', 10);
        $this->Cell(0, 6, "Desde fecha:" . $this->desdefecha . " Hasta fecha:" . $this->hastafecha , 0, 0, 'R');
        $this->Ln();
        $this->Cell(30, 6, "Nro Facturaº", "B", 0, "L");
        $this->Cell(30, 6, "Nro Entregaº", "B", 0, "L");
        $this->Cell(50, 6, "Fecha", "B", 0, "L");
        $this->Cell(70, 6, "Cliente", "B", 0, "L");
        $this->Cell(70, 6, "Direccion", "B", 0, "L");
        $this->Cell(27, 6, "Ruta", "B", 0, "L");
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
        $query = "SELECT A.NUMFACTURA, B.NUMNOTA, A.SERIE, A.FECHA, C.CLIENOMBRE, C.CLIEDIRECCION, D.DESRUTA, B.IDNOTA, A.IDFACTURA FROM FACTURAS A INNER JOIN NOTAS_ENTREGA B ON A.IDFACTURA = B.IDFACTURA INNER JOIN CLIENTES C ON B.IDCLIENTE = C.IDCLIENTE INNER JOIN RUTAS D ON C.IDRUTA= D.IDRUTA ";

        $where = build_where("C.IDRUTA", $this->idruta, "C");

        $filtrofecha = build_beetwen("A.FECHA", formatdate($this->desdefecha), formatdate($this->hastafecha), "C");
        if ($filtrofecha && $where)
            $where .= " AND " . $filtrofecha;
        else if ($filtrofecha)
            $where = $filtrofecha;


        if ($where) $query .= " WHERE " . $where;

        $query .= " ORDER BY A.NUMFACTURA";

        $result = execute_query($this->db, $query);
        while (($row = fetch_object($result))) {
            // Aqui pinta las lineas de detalle
            $this->Cell(30, 6, $row->NUMFACTURA, 0, 0, "L");
            $y = $this->GetY();
            $this->Cell(30, 6, $row->NUMNOTA, 0, 0, "L");
            $this->Cell(50, 6, $row->FECHA, 0, 0, "L");
            $this->XCell($y, 70, 6, $row->CLIENOMBRE, 0, "L", 0);
            $this->XCell($y, 70, 6, $row->CLIEDIRECCION, 0, "L", 0);
            $this->XCell($y, 28, 6, $row->DESRUTA, 0, "L", 0);
            $this->XLn();
            
        }
        
    } 
} 
