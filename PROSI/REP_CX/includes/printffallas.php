<?php
define('FPDF_FONTPATH', 'includes/font/');
require("includes/fpdf.php");

class informe_fallas extends FPDF {
   

    var $db;
    var $maximo;

    function Header() // Cabecera del informe
    {
        $this->SetFont('Arial', '', 16);
        $this->Cell(0, 6, "Listado de Fallas ", "B", 0, 'C');
        $this->Ln();
        $this->SetFont('Times', 'B', 9);
        $this->Cell(0, 6, "Desde fecha:" . $this->desdefecha . " Hasta fecha:" . $this->hastafecha , 0, 0, 'R');
        $this->Ln();
		$this->SetFillColor(0, 0, 128);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(255, 255, 255);
        
        $this->Cell(6, 6, "Nº", 1, 0, "C", 1);
        $this->Cell(25, 6, "Falla", 1, 0, "C", 1);
        $this->Cell(25, 6, "Departamento", 1, 0, "C", 1);
		$this->Cell(30, 6, "Informa", 1, 0, "C", 1);
        $this->Cell(20, 6, "Fec Registro", 1, 0, "C", 1);
        $this->Cell(20, 6, "Fec Problema", 1, 0, "C", 1);
        $this->Cell(50, 6, "Descripcion", 1, 0, "C", 1);
        $this->Cell(20, 6, "Estado", 1, 0, "C", 1);
        $this->Cell(50, 6, "Solucion", 1, 0, "C", 1);
        $this->Cell(15, 6, "Usuario", 1, 0, "C", 1);
        $this->Ln(8);
		$this->SetTextColor(0, 0, 0);
        $this->SetFont('Times', '', 7);
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

$registro_id = $this->registro_id;
$tipo_falla_id = $this->tipo_falla_id;
$departamento = $this->departamento;
$estado = $this->estado;
$desdefecha = $this->desdefecha;
$hastafecha = $this->hastafecha;
$query = "SELECT a.registro_id, b.tipo_falla, a.descripcion as problema, c.usuario, c.nombre as nombre_usuario, a.fecha_registro, a.fecha_ocurrio, d.descripcion as departamento, a.solucion, e.estado, e.descripcion as descripcion_estado, a.informante FROM registros_fallas_siis a INNER JOIN tipos_fallas_sistema b ON a.tipo_falla_id = b.tipo_falla_id INNER JOIN system_usuarios c ON a.usuario_id = c.usuario_id INNER JOIN departamentos d ON a.departamento = d.departamento INNER JOIN fallas_estado e ON a.estado = e.estado";
$query_records = "SELECT COUNT(*) AS NUMREG FROM registros_fallas_siis a INNER JOIN tipos_fallas_sistema b ON a.tipo_falla_id = b.tipo_falla_id INNER JOIN system_usuarios c ON a.usuario_id = c.usuario_id INNER JOIN departamentos d ON a.departamento = d.departamento INNER JOIN fallas_estado e ON a.estado = e.estado";

$where = build_where("a.registro_id", $registro_id, "C",
    "b.tipo_falla_id", $tipo_falla_id, "C",
    "c.usuario_id", $usuario_id, "C",
    "d.departamento", $departamento, "C",
	"e.estado", $estado, "C");
    

$filtrofecha = build_beetwen("a.fecha_registro", formatdate($desdefecha), formatdate($hastafecha), "C");


if ($where && $filtrofecha) 
	$where .= " AND ";
$where .= $filtrofecha;

if ($_GET["order"] >= "1" && $_GET["order"] <= "9")
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
            $this->Cell(6, 6, $row->registro_id, 0, 0, "L");
            $y = $this->GetY();
            $this->XCell($y, 25, 6, $row->tipo_falla, 0, "L", 0);
            $this->XCell($y, 25, 6, $row->departamento, 0, "L", 0);
			$this->XCell($y, 30, 6, $row->informante, 0, "L", 0);
            $this->Cell(20, 6, $row->fecha_registro, 0, "C");
            $this->Cell(20, 6, $row->fecha_ocurrio, 0, "C");
            $this->XCell($y, 50, 6, $row->problema, 0, "L", 0);
            $this->Cell(20, 6, $row->descripcion_estado, 0, "C");
            $this->XCell($y, 50, 6, $row->solucion, 0, "L", 0);
            $this->Cell(15, 6, $row->usuario, 0, "C");
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