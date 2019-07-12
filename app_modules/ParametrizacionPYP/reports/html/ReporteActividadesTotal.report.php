<?php

/**
 * $Id: ReporteActividadesTotal.report.php,v 1.2 2007/02/01 19:56:30 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteActividadesTotal_report
{
	
	function ReporteSeguimientoCitas_report($datos=array())
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
		$this->datos=$_SESSION['reporte_3'];	
		
		$salida ="<html><body>";
		$meses=12;
		
		$salida .= "		<table class='normal_10' width='60%' align='center' border='1'>";
		$salida .= "			<tr class='modulo_table_list_title' align='center'>";
		$salida .= "				<td colspan='2' width='80%'>ACTIVIDAD</td>";
		$salida .= "				<td width='20%'>TOTALES</td>";
		$salida .= "			</tr>";
		foreach($this->datos as $key=>$valor1)
		{
			if($estilo=="modulo_list_oscuro")
				$estilo="modulo_list_claro";
			else
				$estilo="modulo_list_oscuro";
			
			$ban=false;
			$salida .= "			<tr class='modulo_list_claro' align='center'>";
			$salida .= "				<td width='40%' class='modulo_table_list_title' rowspan='".sizeof($valor1)."'>$key</td>";
			
			foreach($valor1 as $key1=>$valor2)
			{
				if($ban)
					$salida .= "			<tr class='modulo_list_claro' align='center'>";
				
				$salida .= "				<td width='60%' align='center'><b>".$key1."</b></td>";
				$sum=0;
				for($i=0;$i<$meses;$i++)
				{
					$flag=0;
					foreach($valor2 as $valor3)
					{
						if($valor3['mes']==($i+1))
						{
							$sum+=$valor3['count'];
							$flag=1;
							break;
						}
					}
				}
				$salida .= "				<td class='modulo_list_oscuro' align='center'><b>$sum</b></td>";
				$salida .= "			</tr>";
				
				$ban=true;
			}
		}
		
		$salida .= "	</table>";
		
		$salida.="</body></html>";
		
		return $salida;
	}
}
?>
