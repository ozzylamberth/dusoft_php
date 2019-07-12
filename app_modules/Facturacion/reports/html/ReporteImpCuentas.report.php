<?php

/**
 * $Id: ReporteImpCuentas.report.php,v 1.3 2007/09/26 21:38:38 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteImpCuentas_report
{
	var $datos;
	
	function ReporteImpCuentas_report($datos=array())
	{
		$this->datos=$datos;
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
		$salida .= "<br><center><font size=\"4\">".$this->datos['titulo']."</font><br>".strtoupper(FormatoFecha(1)).", ".date("g:i a")."</center><br>\n";
		
		$style= "style=\"font-size:14px; font-weight:bold;\"";
		$style1= "style=\"font-size:14px\"";
		$salida .= "<table align=\"center\" width=\"95%\" border=\"1\" class=\"modulo_table_list\">\n";
		foreach($_SESSION['listado'] as $key=>$valor)
		{
			$salida .= "	<tr align=\"center\" $style>";
			$salida .= "		<td align =\"center\">DEPARTAMENTO  -  $key</td>";
			$salida .= "	</tr>\n";
			foreach($valor as $key1=>$valor1)
			{
				$salida .= "	<tr align=\"center\" border=\"1\" $style>";
				$salida .= "		<td align =\"center\">ESTACION  -  $key1</td>";
				$salida .= "	</tr>";
				$salida .= "	<tr>";
				$salida .= "		<td>";
				$salida .= "			<table align=\"center\" width=\"100%\" border=\"1\">";
				$salida .= "				<tr align=\"center\" $style>";
				$salida .= "					<td>ID</td>";
				$salida .= "					<td>PACIENTE</td>";
				$salida .= "					<td>HAB.</td>";
				$salida .= "					<td>CAMA</td>";
				$salida .= "					<td>FECHA INGRESO</td>";
				$salida .= "					<td>TIEMPO<BR>HOSP (DIAS)</td>";
				$salida .= "					<td>TERCERO</td>";
				$salida .= "					<td>PLAN</td>";
				if($this->datos['enlace']==2 AND $this->datos['opcion']==3)
						$salida .= "					<td>ESTADO CUENTA</td>\n";
				$salida .= "					<td>VALOR CUBIERTO</td>";
				$salida .= "					<td>VALOR NO CUBIERTO</td>";
				$salida .= "				</tr>\n";
				
				foreach($valor1 as $valor2)
				{
					$salida .= "				<tr align=\"center\" $style1>";
					$salida .= "					<td>".$valor2['tipo_id_paciente']." - ".$valor2['paciente_id']."</td>";
					$salida .= "					<td>".$valor2['nombre_completo']."</td>";
					$salida .= "					<td>".$valor2['pieza']."</td>";
					$salida .= "					<td>".$valor2['cama']."</td>";
					$salida .= "					<td>".date('Y-m-d g:i a',strtotime($valor2['fecha_ingreso']))."</td>";
					$salida .= "					<td>".$this->GetDiasHospitalizacion($valor2['fecha_ingreso'])."</td>";
					$salida .= "					<td>".$valor2['nombre_tercero']."</td>";
					$salida .= "					<td>".$valor2['plan_descripcion']."</td>";
					if($this->datos['enlace']==2 AND $this->datos['opcion']==3)
						$salida .= "					<td>".$valor2['estado_cuenta']."</td>\n";
					$salida .= "					<td> $ ".FormatoValor($valor2['t_vc_apc'])."</td>";
					$salida .= "					<td> $ ".FormatoValor($valor2['t_vnc_apc'])."</td>";
					$salida .= "				</tr>";
					$sum+=$valor2['t_vc_apc']+$valor2['t_vnc_apc'];
				}
				$salida .= "			</table>";
				$salida .= "		</td>";
				$salida .= "	</tr>";
			}
		}
		$salida .= "	<tr align=\"center\" $style>";
		$salida .= "		<td align=\"right\"> TOTAL CUENTA: $ ".FormatoValor($sum)."</td>";
		$salida .= "	</tr>";
		$salida .= "</table>";
		
		return $salida;
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
?>
