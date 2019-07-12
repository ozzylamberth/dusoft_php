<?php

/**
 * $Id: ReportePacientesCuentas.php,v 1.2 2007/02/20 15:17:47 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReportePacientesCuentas
{
	var $datos;
	
	function ReportePacientesCuentas($datos=array())
	{
		return true;
	}

	function CrearReporte()
	{
		$salida="";
		$salida .= "<br><center><font size=\"4\">REPORTE DE PACIENTES EN HOPITALIZACION, URGENCIAS </font><br>".strtoupper(FormatoFecha(1))." , ".date("g:i a")."</center><br>\n";
		$style= "style=\"font-size:14px; font-weight:bold;\"";
		$style1= "style=\"font-size:14px\"";
		
		$salida .= "<table align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
		if($_SESSION['list_1'][0])
		{
			$salida .= "	<tr $style align=\"center\">\n";
			$salida .= "		<td colspan=\"6\">PACIENTES HOSPITALIZADOS</td>";
			$salida .= "	</tr>\n";
			$salida .= "	<tr $style align=\"center\">\n";
			$salida .= "		<td width=\"10%\">CUENTA</td>";
			$salida .= "		<td width=\"15%\">IDENTIFICACION</td>";
			$salida .= "		<td width=\"30%\">NOMBRE PACIENTE</td>";
			$salida .= "		<td width=\"15%\">VALOR CARGOS</td>";
			$salida .= "		<td width=\"15%\">CARGOS HABITACION</td>";
			$salida .= "		<td width=\"15%\">VALOR CARGO + HAB</td>";
			$salida .= "	</tr>\n";
			$a=0;
			
			foreach($_SESSION['list_1'][0] as $key=>$valor)
			{
				if($a%2==0)
					$estilo="modulo_list_oscuro";
				else
					$estilo="modulo_list_claro";
				
				$salida .= "	<tr $style1 align=\"center\">\n";
				$salida .= "		<td>".$valor['numerodecuenta']."</td>";
				$salida .= "		<td>".$valor['tipo_id_paciente']."-".$valor['paciente_id']."</td>";
				$salida .= "		<td>".$valor['nombre_completo']."</td>";
				$salida .= "		<td align=\"right\"> $ ".FormatoValor($valor['valor_cubierto'])."</td>";
				$salida .= "		<td align=\"right\"> $ ".FormatoValor($valor['habitacion'])."</td>";
				$salida .= "		<td align=\"right\"> $ ".FormatoValor($valor['cargo_mas_hab'])."</td>";
				$salida .= "	</tr>\n";
				
				$sum_a+=$valor['valor_cubierto'];
				$s_per_a+=$valor['habitacion'];
				$cargo_mas_hab_a+=$valor['cargo_mas_hab'];
				$a++;
			}
			$salida .= "	<tr $style align=\"center\">\n";
			$salida .= "		<td colspan=\"3\" align=\"right\">TOTAL PACIENTES HOSPITALIZADOS : $a</td>";
			$salida .= "		<td align=\"right\"> $ ".FormatoValor($sum_a)."</td>";
			$salida .= "		<td align=\"right\"> $ ".FormatoValor($s_per_a)."</td>";
			$salida .= "		<td align=\"right\"> $ ".FormatoValor($cargo_mas_hab_a)."</td>";
			$salida .= "	</tr>\n";
		}
		if($_SESSION['list_1'][1])
		{
			$salida .= "	<tr $style class=\"modulo_table_list_title\" align=\"center\">\n";
			$salida .= "		<td colspan=\"6\">PACIENTES EN URGENCIAS</td>";
			$salida .= "	</tr>\n";
			$salida .= "	<tr $style align=\"center\">\n";
			$salida .= "		<td>CUENTA</td>";
			$salida .= "		<td>IDENTIFICACION</td>";
			$salida .= "		<td>NOMBRE PACIENTE</td>";
			$salida .= "		<td>VALOR CARGOS</td>";
			$salida .= "		<td>CARGOS HABITACION</td>";
			$salida .= "		<td>VALOR CARGO + HAB</td>";
			$salida .= "	</tr>\n";
			$b=0;
			foreach($_SESSION['list_1'][1] as $key=>$valor)
			{
				if($b%2==0)
					$estilo="modulo_list_oscuro";
				else
					$estilo="modulo_list_claro";
				
				$salida .= "	<tr $style1 align=\"center\">\n";
				$salida .= "		<td>".$valor['numerodecuenta']."</td>";
				$salida .= "		<td>".$valor['tipo_id_paciente']."-".$valor['paciente_id']."</td>";
				$salida .= "		<td>".$valor['nombre_completo']."</td>";

				$salida .= "		<td align=\"right\"> $ ".FormatoValor($valor['valor_cubierto'])."</td>";
				$salida .= "		<td align=\"right\"> $ ".FormatoValor($valor['habitacion'])."</td>";
				$salida .= "		<td align=\"right\"> $ ".FormatoValor($valor['cargo_mas_hab'])."</td>";
				$salida .= "	</tr>\n";
				
				$sum_b+=$valor['valor_cubierto'];
				$s_per_b+=$valor['habitacion'];
				$cargo_mas_hab_b+=$valor['cargo_mas_hab'];
				$b++;
			}
			$salida .= "	<tr $style align=\"center\">\n";
			$salida .= "		<td colspan=\"3\" align=\"right\">TOTAL PACIENTES EN URGENCIAS : $b </td>";
			$salida .= "		<td align=\"right\"> $ ".FormatoValor($sum_b)."</td>";
			$salida .= "		<td align=\"right\"> $ ".FormatoValor($s_per_b)."</td>";
			$salida .= "		<td align=\"right\"> $ ".FormatoValor($cargo_mas_hab_b)."</td>";
			$salida .= "	</tr>\n";
		}
		if($_SESSION['list_1'][2])
		{
			$salida .= "	<tr $style align=\"center\">\n";
			$salida .= "		<td colspan=\"5\">PACIENTES AMBULATORIOS</td>";
			$salida .= "	</tr>\n";
			$salida .= "	<tr $style align=\"center\">\n";
			$salida .= "		<td>CUENTA</td>";
			$salida .= "		<td>IDENTIFICACION</td>";
			$salida .= "		<td>NOMBRE PACIENTE</td>";
			$salida .= "		<td>VALOR CARGOS</td>";
			$salida .= "		<td>CARGOS HABITACION</td>";
			$salida .= "		<td>VALOR CARGO + HAB</td>";
			$salida .= "	</tr>\n";
			$c=0;
			foreach($_SESSION['list_1'][2] as $key=>$valor)
			{
				if($c%2==0)
					$estilo="modulo_list_oscuro";
				else
					$estilo="modulo_list_claro";
				
				$salida .= "	<tr $style1 align=\"center\">\n";
				$salida .= "		<td>".$valor['numerodecuenta']."</td>";
				$salida .= "		<td>".$valor['tipo_id_paciente']."-".$valor['paciente_id']."</td>";
				$salida .= "		<td>".$valor['nombre_completo']."</td>";
				$salida .= "		<td align=\"right\"> $ ".FormatoValor($valor['valor_cubierto'])."</td>";
				$salida .= "		<td align=\"right\"> $ ".FormatoValor($valor['habitacion'])."</td>";
				$salida .= "		<td align=\"right\"> $ ".FormatoValor($valor['cargo_mas_hab'])."</td>";
				$salida .= "	</tr>\n";
				
				$sum_c+=$valor['valor_cubierto'];
				$s_per_c+=$valor['habitacion'];
				$cargo_mas_hab_c+=$valor['cargo_mas_hab'];
				$c++;
			}
			$salida .= "	<tr $style align=\"center\">\n";
			$salida .= "		<td colspan=\"3\" align=\"right\">TOTAL PACIENTES EN AMBULATORIO : $c </td>";
			$salida .= "		<td align=\"right\"> $ ".FormatoValor($sum_c)."</td>";
			$salida .= "		<td align=\"right\"> $ ".FormatoValor($s_per_c)."</td>";
			$salida .= "		<td align=\"right\"> $ ".FormatoValor($cargo_mas_hab_c)."</td>";
			$salida .= "	</tr>\n";
			$n_total=$a+$b+$c;
			$suma_total=$sum_a+$sum_b+$sum_c;
			$s_per_total=$s_per_a+$s_per_b+$s_per_c;
			$car_hab_total=$cargo_mas_hab_a+$cargo_mas_hab_b+$cargo_mas_hab_c;
			$salida .= "	<tr $style align=\"center\">\n";
			$salida .= "		<td colspan=\"3\" align=\"right\">TOTAL PACIENTES : $n_total </td>";
			$salida .= "		<td align=\"right\"> $ ".FormatoValor($suma_total)."</td>";
			$salida .= "		<td align=\"right\"> $ ".FormatoValor($s_per_total)."</td>";
			$salida .= "		<td align=\"right\"> $ ".FormatoValor($car_hab_total)."</td>";
			$salida .= "	</tr>\n";
		}
		
		$salida .= "</table>\n";
		
		
		echo $salida;
	}
	
	function GetDiasHospitalizacion($fecha_ingreso)
	{
		$date1=date('Y-m-d H:i:s');
		$fecha_in=explode(".",$fecha_ingreso);
		$fecha_ingreso=$fecha_in[0];
		$date2=$fecha_ingreso;
		$s = strtotime($date1)-strtotime($date2);
		$d = intval($s/86400);
		$s -= $d*86400;
		$h = intval($s/3600);
		$s -= $h*3600;
		$m = intval($s/60);
		$s -= $m*60;
		$dif= (($d*24)+$h).hrs." ".$m."min";
		$dif2= $d;
		return $dif2;
	}//Fin GetDiasHospitalizacion
}

$VISTA = "HTML";
$_ROOT = "../../../../";
include  $_ROOT."classes/rs_server/rs_server.class.php";
include	 $_ROOT."includes/enviroment.inc.php";
$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
IncludeFile($filename);

$rep=new ReportePacientesCuentas();
$rep->CrearReporte();

?>
