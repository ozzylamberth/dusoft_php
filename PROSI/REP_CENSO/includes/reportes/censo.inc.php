<?php

/**
 * $Id: censo.inc.php,v 1.2 2005/06/07 18:40:57 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */


	function GetDiasHospitalizacion($fecha_ingreso)
	{
		list($Fecha,$Horas) = explode(" ",$fecha_ingreso);//obtiene solo la fecha sin horas
		if($Fecha == date('Y-m-d'))
		{
			list($h,$m,$s) = explode(":",date('H:i:s'));
			list($hh,$mm,$ss) = explode(":",$Horas);

			$total = date('g:i:s',(mktime($h,$m,$s,date('m'),date('d'),date('Y')) - mktime($hh,$mm,$ss,date('m'),date('d'),date('Y'))));
			list($h,$m,$s) = explode(":",$total);
			$total = $h." h, ".$m." m, ".$s." s.";
		}
		else
		{
			$date = explode("-",$Fecha);//obtengo por separado año, mes, dia
			$annos = ceil(date("Y") - $date[0]);
			$meses = date("m") - $date[1];
			$dias = (date("d")-$date[2]);
			$total = ($annos*365) + ($meses*30) + $dias;
			$total.= " días";
		}
		return $total;
	}



function GenerarListadoCenso($arr)
{
		$Dir="cache/censo.pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$_SESSION['REPORTES']['VARIABLE']='censo';//variable de asignacion.....para mostrar la cabecera.
		$pdf=new PDF();
		$pdf->AddPage();
		for($i=0;$i<sizeof($arr);$i++)
		{
					if( $i % 2){ $estilo2='#CCCCCC';}
					else {$estilo2='#DDDDDD';}

					$salida.="  <TD  WIDTH='60' bgcolor=$estilo>".$arr[0][pieza]."</TD>";
					$salida.="  <TD  WIDTH='60' bgcolor=$estilo>".$arr[0][cama]."</TD>";
					$d=" ";//si colocamos los espacios directamente saca un error.
					//$arr[$i][tipo_id_paciente].$d.$arr[$i][paciente_id].$d.$d.
					//$nombre =$arr[$i][primer_nombre].$d.$arr[$i][segundo_nombre].$d.$arr[$i][primer_apellido].$d.$arr[$i][segundo_apellido];
					$nombre =$arr[$i][primer_nombre].$d.$arr[$i][primer_apellido];

					$salida.="  <TD  WIDTH='250' bgcolor=$estilo>".$nombre."</TD>";
					$salida.="  <TD WIDTH='100' bgcolor=$estilo>".$arr[$i][fecha_ingreso]."</TD>";
					if(strlen($arr[$i][plan_descripcion]) >25)
					{
							$plandes=substr($arr[$i][plan_descripcion],0,25);
							$plandes."..";
					}
					else
					{
							$plandes=$arr[$i][plan_descripcion];
					}
					$salida.="  <TD WIDTH='155' bgcolor=$estilo>$plandes</TD>";
					$salida.="  <TD WIDTH='58' bgcolor=$estilo>".$arr[$i][numerodecuenta]."</TD>";
					$dias=GetDiasHospitalizacion($arr[$i][fecha_ingreso]);
					$salida.="  <TD WIDTH='45'>".$dias."</TD>";
					$salida.="</TR>";
		}
		$salida.="</table>";
		//echo "-->>".$salida; exit;
  	$pdf->SetFont('Arial','B',25);
		$pdf->SetTextColor(203,203,203);
    $pdf->RotatedText(40,100,GetVarConfigAplication('Cliente'),35);
		$pdf->SetFont('Arial','',7);
		$pdf->SetTextColor(2,2,2);
	  $pdf->WriteHTML($salida);
		$pdf->Output($Dir,'F');
		return true;
}
?>
