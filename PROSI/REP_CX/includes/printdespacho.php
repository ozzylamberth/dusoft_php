<?php
define('FPDF_FONTPATH', 'includes/font/');
require("includes/fpdf.php");

class informe_despacho extends FPDF {
    var $db; 
    var $iddespacho;
    var $totalfactura = 0; // Suma de todos los importes
    var $totalfacturas = 0; // Suma de todos los importes
    

    function Header() // Cabecera del albaran
    {
        $this->SetDrawColor(0, 0, 128);

        $this->empresa = leer_empresa();

        $this->Image($this->empresa["dirimagen"], 10, 10, 45, 25);

        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 6, "Despacho Nº:" . $this->numdespacho . $this->serie, 0, 0, 'R');
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
        $this->Cell(120, 6, "Mensajero", 0, 0, 'R');
        $this->SetTextColor(0, 0, 0);
        $this->MultiCell(0, 6, "Cod.Mensj: " . $this->idmensajero . "\n" . $this->mensajero, 1, 'L');
        $this->Ln(18);

        $this->SetFillColor(0, 0, 128);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(255, 255, 255);
        $this->Cell(40, 4, "Nro Factura", 1 , 0, "C", 1);
        $this->Cell(50, 4, "Fecha Envio", 1, 0, "C", 1);
        $this->Cell(60, 4, "Cliente", 1, 0, "C", 1);
        $this->Cell(40, 4, "Valor Factura", 1, 0, "C", 1);
        $this->Ln(6);
        $this->SetTextColor(0, 0, 0);
    } 

    
function CalculaTotales()
    {

        $this->totalfacturas = $this->totalfactura + $this->totalfacturas;
    }
    
    function CargarDespacho()
    { 
        // Primero carga la cabecera del albaran
        ibase_timefmt("%d/%m/%Y", IBASE_TIMESTAMP);
        $sql = "SELECT A.*, B.IDMENSAJERO, B.MENSDESCRIC FROM DESPACHOS A INNER JOIN MENSAJEROS B ON B.IDMENSAJERO = A.IDMENSAJERO
        WHERE A.IDDESPACHO = " . $this->iddespacho;
        $result = execute_query($this->db, $sql);
        $row = fetch_object($result);

        $this->numdespacho = $row->NUMDESPACHO;
        $this->serie = $row->SERIE;
        $this->fecha = $row->DESPFECHA;
        $this->idmensajero = $row->IDMENSAJERO;
        $this->mensajero = $row->MENSDESCRIC;


        free_result($result);


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
        $sql = "SELECT A.*, B.NUMFACTURA, C.IDNOTA, C.NOTAFECHAENVIO, D.CLIENOMBRE FROM DESPACHOS_D A, FACTURAS B, NOTAS_ENTREGA C, CLIENTES D
        WHERE A.IDDESPACHO = " . $this->iddespacho . " AND A.IDFACTURA = B.IDFACTURA
        AND B.IDFACTURA = C.IDFACTURA AND C.IDCLIENTE = D.IDCLIENTE ORDER BY IDLINEA";
        $result = execute_query($this->db, $sql);
        $this->SetTextColor(0, 0, 0);
        $i = 1;
        while (($row = fetch_object($result))) {
        $this->idnota = $row->IDNOTA;
        $sql1 = "SELECT SUM(IMPORTE) AS SUMA FROM DETALLES WHERE IDNOTA = " . $this->idnota;
        $result1 = execute_query($this->db, $sql1);
        $row1 = fetch_object($result1);
        $this->totalfactura = $row1->SUMA;
            if ($i > 20) {
                $this->AddPage();
                $i = 1;
            } 

            $this->Cell(40, 4, $row->NUMFACTURA, 0 , 0, "C");
            $y = $this->GetY();
            $this->XCell($y, 50, 4, $row->NOTAFECHAENVIO, 0, "C", 0);
            $this->Cell(60, 4, $row->CLIENOMBRE, 0, 0, "C");
            $this->Cell(40, 4, "$ " . number_format($row1->SUMA), 0, 0, "R");
            $this->Ln(4);
            $i ++;
            $this->CalculaTotales();
        } 

        free_result($result);
        
        }
function Footer() // Pie de página
    {
        $this->SetY(-70);
        $this->SetTextColor(0, 0, 128);
        $this->SetDrawColor(0, 0, 128);
        $this->Cell(30, 4, "", 0, 0,"C");
        $this->Cell(25, 4, "", 0, 0,"C");
        $this->Cell(30, 4, "", 0, 0, "C");
        $this->Cell(70, 4, "", 0, 0, "C");
        $this->Cell(35, 4, "TOTAL", "B", 0, "C");
        $this->Ln(4);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(30, 4, "", 0, 0, "C");
        $this->Cell(25, 4, "", 0, 0, "C");
        $this->Cell(30, 4, "", 0, 0, "C");
        $this->Cell(70, 4, "", 0, 0, "C");
        $this->SetFont("Times", "IB", 14);
        $this->Cell(35, 8, "$ " . number_format($this->totalfacturas), 0, 0, "C");
        $this->SetFont("Times", "", 10);
        $this->Ln(8);

        

        $this->Ln(16);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'R');
    }
}

?>
