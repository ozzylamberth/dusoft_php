<?php

/**
 * $Id: estacion_enfermeria.inc.php,v 1.2 2005/06/07 18:40:57 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

function Plan($plan)
{
	list($dbconn) = GetDBconn();
  $querys = " SELECT plan_descripcion
										FROM 	planes where plan_id=$plan";
	$result = $dbconn->Execute($querys);
	if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				if(strlen($result->fields[0]) >25)
				{
						$plandes=substr($result->fields[0],0,25);
	 					return $plandes."..";
				}
				else
				{
					return $result->fields[0];
				}

}


function Habitacion($cuenta)
{
	list($dbconn) = GetDBconn();
 $querys = " SELECT C.cama, C.pieza
										FROM camas C, movimientos_habitacion MH
										WHERE C.cama =  MH.cama AND
													MH.numerodecuenta =$cuenta
													AND MH.fecha_egreso IS NULL";
	$result = $dbconn->Execute($querys);
	if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
	$var[]=$result->GetRowAssoc($ToUpper = false);
	return $var;
}

function GenerarListadoEstacion($arr)
{

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
					$va=Habitacion($arr[$i][cuenta]);
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
					$nombre_plan=Plan($arr[$i][plan]);
					$salida.="  <TD WIDTH='155' bgcolor=$estilo>".$nombre_plan."</TD>";
					$salida.="  <TD WIDTH='75' bgcolor=$estilo>".$arr[$i][cuenta]."</TD>";
					$salida.="</TR>";
		}
		$salida.="</table>";
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
