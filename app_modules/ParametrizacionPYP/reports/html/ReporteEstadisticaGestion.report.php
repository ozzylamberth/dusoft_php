<?php

/**
 * $Id: ReporteEstadisticaGestion.report.php,v 1.2 2007/02/01 19:56:30 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteEstadisticaGestion_report
{
	var $datos;
	
	function ReporteEstadisticaGestion_report($datos=array())
	{
		return true;
	}

	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	function CrearReporte()
	{
		$this->datos=$_SESSION['reporte_2'];
		$meses=array('ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC');
		
		$salida="<html><body>";
		$salida .= "		<table  width='60%' align='center' border='1'>";
		$salida .= "			<tr align='center'>";
		$salida .= "				<td align='center' colspan='2' width='10%'>ACTIVIDAD</td>";
		for($i=0;$i<sizeof($meses);$i++)
			$salida .= "				<td>".$meses[$i]."</td>";
		$salida .= "				<td align='center' width='10%'>TOTALES</td>";
		$salida .= "			</tr>";
		foreach($this->datos as $key=>$valor1)
		{
			$ban=false;
			$salida .= "			<tr align='center'>";
			$salida .= "				<td align='center' rowspan='".sizeof($valor1)."'>$key</td>";
			
			foreach($valor1 as $key1=>$valor2)
			{
				if($ban)
					$salida .= "			<tr align='center'>";
				
				$salida .= "				<td align='center'><b>".$key1."</b></td>";
				$sum=0;
				for($i=0;$i<sizeof($meses);$i++)
				{
					$flag=0;
					foreach($valor2 as $valor3)
					{
						if($valor3['mes']==($i+1))
						{
							$salida .= "				<td align='center' width='10%'><b>".$valor3['count']."</b></td>";
							$sum+=$valor3['count'];
							$flag=1;
							break;
						}
					}
					if($flag==0)
						$salida .= "<td align='center' width='10%'>&nbsp;</td>";
				}
				$salida .= "				<td align='center'><b>$sum</b></td>";
				$salida .= "			</tr>";
				
				$ban=true;
			}
		}
		
		$salida.= "	</table>";
			
		$salida.="</body></html>";
		
		return $salida;
	}
}
?>
