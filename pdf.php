<?
	/*	$Dir="cache/ejemplo.pdf";
		require_once("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$pdf=new PDF();
		$pdf->SetFont('Arial','B',16);
 		$pdf->AddPage();
		$pdf->WriteHTML("hola $_REQUEST[nombre]");
		$pdf->Output($Dir,'I');*/


		$arr=$_REQUEST['arr'];
		//print_r($arr); exit;
		$Dir="cache/estacion_enf.pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$_SESSION['REPORTES']['VARIABLE']='estacion_enf';
		$pdf=new PDF();
		$pdf->AddPage();
		for($i=0;$i<sizeof($arr);$i++)
		{
					if( $i % 2){ $estilo2='#CCCCCC';}
					else {$estilo2='#DDDDDD';}
					//$va=Habitacion($arr[$i][cuenta]);
					$salida.="<TR>";
					if(empty($va[0][pieza])){$pieza="---";}else{$pieza=$va[0][pieza];}
					if(empty($va[0][cama])){$cama="---";}else{$cama=$va[0][cama];}
					$salida.="  <TD  WIDTH='70' bgcolor=$estilo>".$pieza."</TD>";
					$salida.="  <TD  WIDTH='70' bgcolor=$estilo>".$cama."</TD>";
					$d=" ";
					//$nombre =$arr[$i][primer_nombre].$d.$arr[$i][segundo_nombre].$d.$arr[$i][primer_apellido].$d.$arr[$i][segundo_apellido];
					$nombre =$arr[$i][primer_nombre].$d.$arr[$i][primer_apellido];

					$salida.="  <TD  WIDTH='260' bgcolor=$estilo>".$nombre."</TD>";
					$salida.="  <TD WIDTH='100' bgcolor=$estilo>".$arr[$i][fec_ing]."</TD>";
					//$nombre_plan=Plan($arr[$i][plan]);
					$salida.="  <TD WIDTH='155' bgcolor=$estilo>".$nombre_plan."</TD>";
					$salida.="  <TD WIDTH='75' bgcolor=$estilo>".$arr[$i][cuenta]."</TD>";
					$salida.="</TR>";
		}
		$salida.="</table>";
		$pdf->SetFont('Arial','B',25);
		$pdf->SetTextColor(203,203,203);
   	$pdf->RotatedText(40,100,'CLINICA DE OCCIDENTE TULUA',35);
		$pdf->SetFont('Arial','',7);
		$pdf->SetTextColor(2,2,2);
		$pdf->WriteHTML($salida);
		$pdf->Output($Dir,'I');
		return true;

?>
