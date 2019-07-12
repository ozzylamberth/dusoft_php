<?php

/**
 * $Id: ReporteSeguimientoCitas.report.php,v 1.2 2007/02/01 19:56:30 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteSeguimientoCitas_report
{
	var $datos;
	
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
			$dat=$_SESSION['DATOS_REPORTE_SEGUIMIENTO_CPN'];
			
			$salida ="<html><body>";
			$salida .= "   <table align=\"center\" width=\"100%\">";
			$salida .= "   	<tr><td colspan=\"19\"><font size=\"3\"> REPORTE SEGUIMIENTO DE CITAS - ".date("Y-m-d")." </font></td></tr>";
			$salida .= "   </table>";
			$salida .= "   <table width=\"100%\" align=\"center\" border=\"1\">";
			$salida .= "			<tr align=\"center\">";
			$salida .= "				<td rowspan=\"2\" width=\"10%\">FECHA CONTACTO</td>";
			$salida .= "				<td rowspan=\"2\" width=\"20%\">NOMBRE</td>";
			$salida .= "				<td colspan=\"2\" width=\"5%\">TIPO ATENCION</td>";
			$salida .= "				<td colspan=\"3\" width=\"10%\">CLASIFICACION RIESGO</td>";
			$salida .= "				<td colspan=\"2\" width=\"10%\">TIPO RIESGO</td>";
			$salida .= "				<td colspan=\"4\" width=\"10%\">PATOLOGIA ASOCIADA</td>";
			$salida .= "				<td colspan=\"2\" width=\"5%\">CUMPLIMIENTO CITA</td>";
			$salida .= "				<td colspan=\"4\" width=\"20%\">ACCION DE SEGUIMIENTO</td>";
			$salida .= "			</tr>";
			$salida .= "			<tr align=\"center\">";
			$salida .= "				<td align=\"center\" width=\"5%\">1 VEZ</td>";
			$salida .= "				<td align=\"center\" width=\"5%\">CONTROL</td>";
			$salida .= "				<td align=\"center\" width=\"3%\">BAJO</td>";
			$salida .= "				<td align=\"center\" width=\"3%\">ALTO</td>";
			$salida .= "				<td align=\"center\" width=\"3%\">SIN RIESGO</td>";
			$salida .= "				<td align=\"center\" width=\"5%\">BIOLOGICO</td>";
			$salida .= "				<td align=\"center\" width=\"5%\">PSICOSOCIAL</td>";
			$salida.= "					<td align=\"center\" width=\"2.5%\">ITU</td>";
			$salida .= "				<td align=\"center\" width=\"2.5%\">CERVICOVAGINITIS</td>";
			$salida .= "				<td align=\"center\" width=\"2.5%\">HTA</td>";
			$salida .= "				<td align=\"center\" width=\"2.5%\">DIABETES GESTASIONAL</td>";
			$salida .= "				<td align=\"center\" width=\"5%\">SI</td>";
			$salida .= "				<td align=\"center\" width=\"5%\">NO</td>";
			$salida .= "				<td align=\"center\" width=\"2.5%\">HALLAZGO EN CONTACTO TELEFONICO</td>";
			$salida .= "				<td align=\"center\" width=\"2.5%\">DIRECCIONAMIENTO A OTRA IPS</td>";
			$salida .= "				<td align=\"center\" width=\"2.5%\">CAPTACION EFECTIVA</td>";
			$salida .= "				<td align=\"center\" width=\"12.5%\">CAUSA</td>";
			$salida .= "			</tr>";
			
			foreach($dat as $reporte)
			{

				$salida .= "			<tr>";
				$salida .= "				<td align=\"center\">".substr($reporte['fecha_contacto'],0,10)."</td>";
				$salida .= "				<td align=\"center\">".$reporte['nombre_paciente']."</td>";
				
				if($reporte['tipo_atencion']=='PRIMERA ATENCION')
				{
					$salida .= "				<td align=\"center\"><b>Si</b></td>";
					$salida .= "				<td align=\"center\">&nbsp;</td>";	
				}
				else if($reporte['tipo_atencion']=='CONTROL')
				{
					$salida .= "				<td align=\"center\">&nbsp;</td>";	
					$salida .= "				<td align=\"center\"><b>Si</b></td>";
				}
				else
				{
					$salida .= "				<td align=\"center\">&nbsp;</td>";	
					$salida .= "				<td align=\"center\">&nbsp;</td>";
				}
				
				if($reporte['riesgo']=='BAJO')
				{
					$salida .= "				<td align=\"center\"><b>Si</b></td>";
					$salida .= "				<td align=\"center\">&nbsp;</td>";	
					$salida .= "				<td align=\"center\">&nbsp;</td>";	
				}
				elseif($reporte['riesgo']=='ALTO')
				{
					$salida .= "				<td align=\"center\">&nbsp;</td>";	
					$salida .= "				<td align=\"center\"><b>Si</b></td>";
					$salida .= "				<td align=\"center\">&nbsp;</td>";	
				}
				elseif(!$reporte['riesgo'])
				{
					$salida .= "				<td align=\"center\">&nbsp;</td>";	
					$salida .= "				<td align=\"center\">&nbsp;</td>";	
					$salida .= "				<td align=\"center\"><b>Si</b></td>";
				}	
				
				if($reporte['riesgo']=='ALTO')
				{
					$salida .= "				<td align=\"center\">".$reporte['biologico']."</td>";
					$salida .= "				<td align=\"center\">".$reporte['psicosocial']."</td>";
				}
				else
				{
					$salida .= "				<td align=\"center\">&nbsp;</td>";
					$salida .= "				<td align=\"center\">&nbsp;</td>";
				}
				
				if($reporte['itu'])
					$salida .= "				<td align=\"center\"><b>Si</b></td>";
				else
					$salida .= "				<td align=\"center\">No</td>";
				
				if($reporte['cervico'])
					$salida .= "				<td align=\"center\"><b>Si</b></td>";
				else
					$salida .= "				<td align=\"center\">No</td>";
				
				if($reporte['hta'])
					$salida .= "				<td align=\"center\"><b>Si</b></td>";
				else
					$salida .= "				<td align=\"center\">No</td>";
				
				if($reporte['diabetes_gestacional'])
				{
					$salida .= "				<td align=\"center\"><b>Si</b></td>";
				}
				else
				{
					$salida .= "				<td align=\"center\">No</td>";	
				}
				$cumplio=0;
				if($reporte['sw_estado'])
				{
					if($reporte['sw_estado']=='3')
					{
						$salida.= "<td align=\"center\"><b>Si</b></td>";
						$salida.= "<td align=\"center\">&nbsp;</td>";	
						$cumplio=1;
					}
					else
					{
						$salida .= "<td align=\"center\">&nbsp;</td>";
						if($reporte['fecha_turno'] < date("Y-m-d"))
							$salida .= "<td align=\"center\"><b>No</b></td>";
						else
							$salida .= "<td align=\"center\">&nbsp;</td>";
					}
				}
				else
				{
					$salida .= "<td align=\"center\">&nbsp;</td>";	
					$salida .= "<td align=\"center\">&nbsp;</td>";
				}
				
				/*if($reporte['contacto_telefonico'])
					$ver1="<a href=\"javascript:Inicio('".$reporte['seguimiento_id']."','capa1$k')\">Ver</a>";
				else
					$ver1="";*/
				
				$salida .= "				<td align=\"center\">".$reporte['contacto_telefonico']."&nbsp;</td>";	
				
				/*if($reporte['ips_dir'])
					$ver2="<a href=\"javascript:Inicio('".$reporte['seguimiento_id']."','capa2$k')\">Ver</a>";
				else
					$ver2="";*/
				
				$salida .= "				<td align=\"center\">".$reporte['ips_dir']."&nbsp;</td>";
				
				if($cumplio==1 AND (!empty($reporte['contacto_telefonico']) OR !empty($reporte['ips_dir'])))
					//$ver3="<a href=\"javascript:Inicio('".$reporte['seguimiento_id']."','capa3$k')\">Ver</a>";
					$ver3="Si  Cumplida - ".$reporte['fecha_turno'];
				else
					$ver3="&nbsp;";
					
				$salida .= "				<td align=\"center\">$ver3</td>";
				$salida .= "				<td align=\"center\">".$reporte['observacion']."</td>";
				$salida .= "			</tr>";
				
				$k++;
			}
			
			$salida .= "	</table>";
			
		$salida.="</body></html>";
		
		return $salida;
	}
}
?>
