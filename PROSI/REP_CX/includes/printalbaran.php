<?php
define('FPDF_FONTPATH', 'includes/font/');
require("includes/fpdf.php");

class informe_albaran extends FPDF {
    var $db; 
    var $idalbaran; 
    var $sumatotal = 0; 
    var $iva = 0;
    var $dto = 0;
    var $inc = 0;

    var $base_imp = 0;
    var $importe_dto = 0;
    var $importe_iva = 0;
    var $importe_inc = 0;
    var $total = 0;

    var $numalbaran;
    var $serie;
    var $fecha;
    var $cliente;
    var $idcliente;
    var $direccion;

    var $empresa;
    var $maximo;

    function Header() // Cabecera del albaran
    {
        $this->SetDrawColor(0, 0, 128);

        $this->empresa = leer_empresa();

        $this->Image($this->empresa["dirimagen"], 10, 10, 45, 25);

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 6, "Albarán Nº:" . $this->numalbaran . $this->serie, 0, 0, 'R');
        $this->Ln();
        $this->SetFont('Times', '', 10);
        $this->Cell(0, 6, "Fecha:" . $this->fecha, 0, 0, 'R');
        $this->Ln(20);
        $this->SetFont('Arial', '', 10);
        $this->Cell(10, 6, $this->empresa["empresa"], 0, 0, "L");
        $this->Ln(6);
        $this->SetFont('Arial', '', 8);
        $this->MultiCell(0, 6, $this->empresa["dircompleta"], 0, "L");
        $this->Cell(10, 6, "Telf.: " . $this->empresa["telefono"], 0, 0, "L");
        $this->Ln(-18);

        $this->SetFont('Times', '', 10);
        $this->SetTextColor(0, 0, 128);
        $this->Cell(120, 6, "Cliente", 0, 0, 'R');
        $this->SetTextColor(0, 0, 0);
        $this->MultiCell(0, 6, "Cod.Cli: " . $this->idcliente . "\n" . $this->cliente . "\n" . $this->direccion, 1, 'L');
        $this->Ln(18);

        $this->SetFillColor(0, 0, 128);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(255, 255, 255);
        $this->Cell(20, 4, "Código", 1 , 0, "C", 1);
        $this->Cell(70, 4, "Descripción", 1, 0, "C", 1);
        $this->Cell(30, 4, "Cantidad", 1, 0, "C", 1);
        $this->Cell(35, 4, "Precio", 1, 0, "C", 1);
        $this->Cell(0, 4, "Importe", 1, 0, "C", 1);
        $this->Ln(6);
        $this->SetTextColor(0, 0, 0);
    } 

    function CalculaTotales()
    {
        if ($this->dto > 0)
            $this->importe_dto = number_format($this->sumatotal * $this->dto / 100, 2);

        $this->base_imp = $this->sumatotal - $this->importe_dto;

        if ($this->iva > 0)
            $this->importe_iva = $this->base_imp * $this->iva / 100;

        if ($this->inc > 0)
            $this->importe_inc = number_format(($this->base_imp + $this->importe_iva) * $this->inc / 100, 2);

        $this->total = $this->base_imp + $this->importe_iva + $this->importe_inc;
    } 

    function CargarAlbaran()
    { 
        // Primero carga la cabecera del albaran
        ibase_timefmt("%d/%m/%Y", IBASE_TIMESTAMP);
        $sql = "SELECT A.*, B.NOMBRE, B.DIRECCION, B.NIF, B.DIRECCION, B.NUMERO, B.PISO, B.LOCALIDAD, B.CP, B.PROVINCIA FROM ALBARANES A INNER JOIN CLIENTES B ON B.IDCLIENTE = A.IDCLIENTE WHERE A.IDALBARAN = " . $this->idalbaran;
        $result = execute_query($this->db, $sql);
        $row = fetch_object($result);

        $this->numalbaran = $row->NUMALBARAN;
        $this->serie = $row->SERIE;
        $this->fecha = $row->FECHA;
        $this->idcliente = $row->IDCLIENTE;
        $this->cliente = $row->NOMBRE;

        if ($row->NIF) $this->cliente .= "\n" . $row->NIF;
        $this->direccion = $row->DIRECCION;
        if ($row->NUMERO) $this->direccion .= ", " . $row->NUMERO;
        if ($row->PISO) $this->direccion .= ", " . $row->PISO;
        if ($row->LOCALIDAD) $this->direccion .= "\n" . $row->LOCALIDAD;
        if ($row->CP) $this->direccion .= " " . $row->CP;
        if ($row->PROVINCIA) $this->direccion .= " (" . $row->PROVINCIA . ")";

        $this->dto = $row->DTO;
        $this->iva = $row->IVA;
        $this->inc = $row->INC;

        free_result($result);

        $sql = "SELECT SUM(IMPORTE) AS SUMA FROM DETALLES WHERE IDALBARAN = " . $this->idalbaran;
        $result = execute_query($this->db, $sql);
        $row = fetch_object($result);
        $this->sumatotal = $row->SUMA;

        $this->CalculaTotales();
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
        // Aqui imprime los detalles del albaran
        $sql = "SELECT A.*, B.DESCCORTA FROM DETALLES A, ARTICULOS B WHERE A.IDALBARAN = " . $this->idalbaran . " AND A.IDARTICULO = B.IDARTICULO ORDER BY IDLINEA";
        $result = execute_query($this->db, $sql);
        $this->SetTextColor(0, 0, 0);
        $i = 1;
        while (($row = fetch_object($result))) {
            if ($i > 20) {
                $this->AddPage();
                $i = 1;
            } 
            $this->Cell(20, 4, $row->IDARTICULO, 0 , 0, "L");
            $y = $this->GetY();
            $this->XCell($y, 70, 4, $row->DESCCORTA, 0, "L", 0);
            $this->Cell(30, 4, $row->CANTIDAD, 0, 0, "R");
            $this->Cell(35, 4, number_format($row->PRECIO, 2, ",", "."), 0, 0, "R");
            $this->Cell(0, 4, number_format($row->IMPORTE, 2, ",", "."), 0, 0, "R");
            $this->Ln(4);
            $i ++;
        } 

        free_result($result);
    } 

    function Footer() // Pie de página
    {
        $this->SetY(-70);
        $this->SetTextColor(0, 0, 128);
        $this->SetDrawColor(0, 0, 128);
        $this->Cell(30, 4, "Total", "B", 0, "C");
        $this->Cell(25, 4, "Dto.%", "B", 0, "C");
        $this->Cell(30, 4, "Importe Descuento", "B", 0, "C");
        $this->Cell(70, 4, "", 0, 0, "C");
        $this->Cell(35, 4, "TOTAL", "B", 0, "C");
        $this->Ln(4);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(30, 4, number_format($this->sumatotal, 2, ",", "."), 0, 0, "C");
        $this->Cell(25, 4, $this->dto, 0, 0, "C");
        $this->Cell(30, 4, number_format($this->importe_dto, 2, ",", "."), 0, 0, "C");
        $this->Cell(70, 4, "", 0, 0, "C");
        $this->SetFont("Times", "IB", 14);
        $this->Cell(35, 8, number_format($this->total, 2, ",", ".") . " $", 0, 0, "C");
        $this->SetFont("Times", "", 10);
        $this->Ln(8);

        $this->SetTextColor(0, 0, 128);
        $this->Cell(30, 4, "Base Imponible", "B", 0, "C");
        $this->Cell(25, 4, "IVA%", "B", 0, "C");
        $this->Cell(30, 4, "Importe IVA", "B", 0, "C");
        $this->Cell(25, 4, "Rec.%", "B", 0, "C");
        $this->Cell(30, 4, "Importe Recargo", "B", 0, "C");
        $this->Ln(4);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(30, 4, number_format($this->base_imp, 2, ",", "."), 0, 0, "C");
        $this->Cell(25, 4, $this->iva, 0, 0, "C");
        $this->Cell(30, 4, number_format($this->importe_iva, 2, ",", "."), 0, 0, "C");
        $this->Cell(25, 4, $this->inc, 0, 0, "C");
        $this->Cell(30, 4, number_format($this->importe_inc, 2, ",", "."), 0, 0, "C");

        $this->Ln(16);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'R');
    } 
} 

?>
