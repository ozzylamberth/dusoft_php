<?php

/**
 * $Id: ReporteCirugias.php,v 1.7 2007/07/06 16:37:02 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 */

class ReporteCirugias
{
	var $datos;
	
	function ReporteCirugias($datos=array())
	{
		$this->datos['cirugias']  = $_SESSION['cirugia_report'];
		$this->datos['vector']    = $_SESSION['vector'];
		$this->datos['vector2']   = $_SESSION['vector2'];
          $this->datos['fecha_ini'] = $_REQUEST['fecha_ini'];
          $this->datos['fecha_fin'] = $_REQUEST['fecha_fin'];
		return true;
	}

	function CrearReporte()
	{
		$salida .= "<br><center><font size=\"5\">REPORTE DE PROGRAMACION DE CIRUGIAS</font>\n";
          $salida .= "<br><center><font size=\"2\">Reporte generado del día ".$this->datos['fecha_ini']." al ".$this->datos['fecha_fin']."</font><br><br>\n";
		
		$style= "style=\"font-size:10px; font-weight:bold;\"";
		$style1= "style=\"font-size:10px\"";
		
		$salida .= "<table align=\"center\" width=\"100%\" border=\"2\">\n";

		$j=0;
		foreach($this->datos['cirugias'] as $key=>$valor)
		{
			$salida .= "	<tr $style align=\"center\">\n";
			$salida .= "		<td colspan=\"12\">$key</td>";
			$salida .= "	</tr>\n";
			$salida .= "	<tr $style align=\"center\">\n";
			$salida .= "		<td width=\"5%\">HORA INICIO</td>";
			$salida .= "		<td width=\"5%\">TIEMPO CIRUGIA</td>";
			$salida .= "		<td width=\"10%\">PACIENTE</td>";
			$salida .= "		<td width=\"5%\">TELEFONO</td>";
			$salida .= "		<td width=\"10%\">PROCEDIMIENTO</td>";
			$salida .= "		<td width=\"10%\">CIRUJANO</td>";
			$salida .= "		<td width=\"10%\">ANESTESIOLOGO</td>";
			$salida .= "		<td width=\"10%\">AYUDANTE</td>";
			$salida .= "		<td width=\"10%\">T. CIRUGIA</td>";
			$salida .= "		<td width=\"5%\">EDAD</td>";
			$salida .= "		<td width=\"10%\">OBSERVACIONES</td>";
			$salida .= "		<td width=\"10%\">PLAN</td>";
			$salida .= "	</tr>\n";
			foreach($valor as $key1=>$valor1)
			{
				$table_proc="<table border=\"1\" width=\"100%\" height=\"100%\">";
				$r=0;
				foreach($valor1 as $key2=>$valor2)
				{
					$table_proc.="<tr $style1>";
					$table_proc.="		<td> ";
					$table_proc.="			".$valor2['descripcion_cups'].$esp;
					$table_proc.="		</td>";
					$table_proc.="</tr>";
					$r++;
				}
				$table_proc.="</table>";

				if($r<2)
					$table_proc=$valor1[$key2]['descripcion_cups'];
			
				$hora_i=explode(":",$valor1[$key2]['hora_i']);
			
				$salida .= "	<tr $style1 align=\"center\">\n";
				$salida .= "		<td>".substr(substr($valor1[$key2]['hora_inicio'],10,18),0,6)."</td>";
				$salida .= "		<td>".$valor1[$key2]['dif_hora']."&nbsp;</td>";
				$salida .= "		<td>".$valor1[$key2]['nombre_completo']."&nbsp;</td>";
				$salida .= "		<td>".str_replace("-","<br>",$valor1[$key2]['residencia_telefono'])."&nbsp;</td>";
				$salida .= "		<td height=\"100%\">".$table_proc."&nbsp;</td>";
				$salida .= "		<td>".$valor1[$key2]['nombre']."&nbsp;</td>";
				$salida .= "		<td>".$valor1[$key2]['nombre_anes']."&nbsp;</td>";
				$salida .= "		<td>".$valor1[$key2]['nombre_ayud']."&nbsp;</td>";
				$salida .= "		<td>".$valor1[$key2]['desc_ambito_cirugia']."&nbsp;</td>";
				$salida .= "		<td>".$valor1[$key2]['edad']." años</td>";
				$salida .= "		<td>".$valor1[$key2]['observaciones']."&nbsp;</td>";
				$salida .= "		<td>".$valor1[$key2]['plan_descripcion']."&nbsp;</td>";
				$salida .= "	</tr>\n";
			}
			
			$k=$this->datos['vector'][$j]['total_prog_sala'];
			$dif_hora_sala=$this->datos['vector'][$j]['total_hora_sala'];
			$dif_min_sala=$this->datos['vector'][$j]['total_min_sala'];
		
			$salida .= "	<tr $style>\n";
			$salida .= "		<td colspan=\"12\" align=\"right\">TOTAL PROGRAMACIONES DE $key : &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$k</label> &nbsp;&nbsp;&nbsp; TIEMPO :  &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$dif_hora_sala hora(s) y $dif_min_sala minuto(s)</label></td>";
			$salida .= "	</tr>\n";	
			$j++;
		}
		
		$sum_total_pro=$this->datos['vector2']['total_prog'];
		$total_horas=$this->datos['vector2']['total_hora'];
		$total_min=$this->datos['vector2']['total_min'];
		
		$salida .= "	<tr $style>\n";
		$salida .= "		<td colspan=\"12\" align=\"right\" class=\"label\">TOTAL PROGRAMACIONES : &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$sum_total_pro</label> &nbsp;&nbsp;&nbsp; TIEMPO :  &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$total_horas hora(s) y $total_min minuto(s)</label></td>";
		$salida .= "	</tr>\n";	
		$salida .= "</table>\n";
		
		echo $salida;
	}
}

$VISTA = "HTML";
$_ROOT = "../../../../";
include  $_ROOT."classes/rs_server/rs_server.class.php";
include	 $_ROOT."includes/enviroment.inc.php";
$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
IncludeFile($filename);

$rep=new ReporteCirugias();
$rep->CrearReporte();

?>