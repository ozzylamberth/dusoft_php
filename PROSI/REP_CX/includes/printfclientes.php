<?php
define('FPDF_FONTPATH', 'includes/font/');
require("includes/fpdf.php");

class informe_clientes extends FPDF {
   

    var $db;
    var $maximo;

    function Header() // Cabecera del informe
    {
        $this->SetFont('Arial', '', 14);
        $this->Cell(0, 6, "Listado de Clientes ", "B", 0, 'C');
        $this->Ln();
        $this->SetFont('Times', 'B', 10);
        $this->Cell(0, 6, "Desde fecha:" . $this->desdefecha . " Hasta fecha:" . $this->hastafecha , 0, 0, 'R');
        $this->Ln();
		$this->SetFillColor(0, 0, 128);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(255, 255, 255);
        $this->Cell(30, 6, "Cod Clienteº", 1, 0, "C", 1);
        $this->Cell(50, 6, "Nombre", 1, 0, "C", 1);
        $this->Cell(77, 6, "Nombre Comercial", 1, 0, "C", 1);
		$this->Cell(26, 6, "Fecha Ingreso", 1, 0, "C", 1);
        $this->Cell(24, 6, "Telefono 1", 1, 0, "C", 1);
        $this->Cell(24, 6, "Telefono 2", 1, 0, "C", 1);
        $this->Cell(24, 6, "Telefono 3", 1, 0, "C", 1);
        $this->Cell(24, 6, "Celular", 1, 0, "C", 1);
        $this->Ln(8);
		$this->SetTextColor(0, 0, 0);
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
$dbh = $this->db;	

$cliecodigo = $this->cliecodigo;
$clienombre = $this->clienombre;
$clienomcom = $this->clienomcom;
$telefono1 = $this->telefono1;
$movil = $this->movil;
$desdefecha = $this->desdefecha;
$hastafecha = $this->hastafecha;
$query = "SELECT IDCLIENTE, CLIENOMBRE, CLIENOMCOM, CLIETEL1, CLIEMOVIL, CLIEFECALTA FROM CLIENTES ";
$query_records = "SELECT COUNT(*) AS NUMREG FROM CLIENTES ";

$where = build_where("IDCLIENTE", $cliecodigo, "C",
    "CLIENOMBRE", $clienombre, "C",
    "CLIENOMCOM", $clienomcom, "C",
    "CLIETEL1", $telefono1, "C",
    "CLIEMOVIL", $movil, "C");
    

$filtrofecha = build_beetwen("CLIEFECALTA", formatdate($desdefecha), formatdate($hastafecha), "C");

if ($where && $filtrofecha) 
	$where .= " AND ";
$where .= $filtrofecha;

if ($_GET["order"] >= "1" && $_GET["order"] <= "2")
    $order = $_GET["order"];
else
    $order = "1";

require("includes/consulta.php");


		
$i = 1;
while ($row = fetch_object($result)) {
if ($i > 20) {
                $this->AddPage();
                $i = 1;
            } 
            // Aqui pinta las lineas de detalle
            $this->Cell(30, 6, $row->IDCLIENTE, 0, 0, "L");
            $y = $this->GetY();
            $this->Cell(50, 6, $row->CLIENOMBRE, 0, 0, "L");
            $this->Cell(77, 6, $row->CLIENOMCOM, 0, 0, "L");
			$this->Cell(26, 6, $row->CLIEFECALTA, 0, 0, "L");
            $this->XCell($y, 24, 6, $row->CLIETEL1, 0, "R", 0);
            $this->XCell($Y, 24, 6, $row->CLIETEL2, 0, "R", 0);
            $this->XCell($y, 24, 6, $row->CLIETEL3, 0, 0, "R", 0);
            $this->XCell($y, 24, 6, $row->CLIEMOVIL, 0, "R", 0);
            $this->XLn();
			
			$i ++;
            
        } 
        
        free_result($result);
	
    } 
	function Footer() // Pie de página
    {
       
        $this->SetY(-70);
        
        
        $this->Ln(4);
        
        
        $this->Ln(8);

        
        
        $this->Ln(4);
       
        

        $this->Ln(16);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'R');
    } 
	
} 
?>