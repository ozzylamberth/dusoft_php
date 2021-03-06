<?php

/**
 * $Id: ReporteGeneral.report.php,v 1.3 2007/09/10 15:13:53 jgomez Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class ReporteGeneral_report
{
	var $datos;
	
	function ReporteGeneral_report($datos=array())
	{
		$this->fecha_ini=$_REQUEST['fecha_ini'];
		$this->fecha_fin=$_REQUEST['fecha_fin'];
		$this->sw=$_REQUEST['sw'];
		return true;
	}
	
	function CrearReporte()
	{
		$this->salida .= "<br><center><font size=\"4\"> REPORTE TOTAL POR PROFESIONAL </font><br><br>".strtoupper(FormatoFecha(1)).", ".date("g:i a")."</center><br><br>\n";
		
		$style= "style=\"font-size:12px; font-weight:bold;\"";
		$style1= "style=\"font-size:12px\"";
				
		$datosG=$_SESSION['ordenes_de_pago']['reporte_general'];
		$this->salida.="	<table border=\"0\" width=\"50%\" align=\"center\">";    
		$this->salida.="		<tr align=\"center\">";
		$this->salida.="			<td $style width=\"30%\">FECHAS: </td>";
		$this->salida.="			<td $style width=\"70%\">DESDE &nbsp;&nbsp;".$this->fecha_ini." &nbsp;&nbsp;HASTA &nbsp;&nbsp;".$this->fecha_fin."</td>";
		$this->salida.="		</tr>";
		$this->salida.="	</table><br>";
		
		$i=0;
		$total=0;
		$cols=2;
		$a=false;
		
		if(!empty($this->sw))
		{
			$a=true;
			$cols=3;
			$w1="75%";
		}
		
		$this->salida.="	<table border=\"1\" width=\"100%\" align=\"center\">";    
		$this->salida.="		<tr $style align=\"center\">";
		$this->salida.="			<td width=\"10%\">IDENTIFICACION</td>";
		$this->salida.="			<td width=\"$w\">NOMBRE PROFESIONAL</td>";
		if($a)
			$this->salida.="			<td width=\"55%\">VOUCHERS</td>";
		$this->salida.="			<td width=\"15%\">VALOR A PAGAR</td>";
		$this->salida.="		</tr>";
		foreach($datosG as $key=>$valor)
		{
			if($i%2==0)
				$estilo="modulo_list_claro";
			else
				$estilo="modulo_list_oscuro";
			
			$salida1="				<table border=\"1\" width=\"100%\">";
			$salida1.="					<tr $style>";
			$salida1.="						<td  align=\"center\" width=\"15%\">";
			$salida1.="							VOUCHER";
			$salida1.="						</td>";
			$salida1.="						<td  align=\"center\" width=\"10%\">";
			$salida1.="							FACTURA MEDICO";
			$salida1.="						</td>";
			$salida1.="						<td  align=\"center\" width=\"65%\">";
			$salida1.="							DESCRIPCION";
			$salida1.="						</td>";
			$salida1.="						<td  align=\"center\" width=\"10%\">";
			$salida1.="							VALOR VOUCHER";
			$salida1.="						</td>";
			$salida1.="					</tr>";

			$total_profesional=0;
			
			foreach($valor as $key1=>$valor1)
			{
				if($estilo=="modulo_list_claro")
					$estilo1="modulo_list_oscuro";
				else
					$estilo1="modulo_list_claro";
			
				$salida1.="					<tr $style1>";
				$salida1.="						<td  align=\"center\">";
				$salida1.="							".$valor1['prefijo']." - ".$valor1['numero']."";
				$salida1.="						</td>";
				$salida1.="						<td  align=\"center\">";
				$salida1.="							".$valor1['numero_factura_id']."";
				$salida1.="						</td>";
				$salida1.="						<td  align=\"center\">";
				$salida1.="							".strtoupper($valor1['descripcion'])."";
				$salida1.="						</td>";
				$salida1.="						<td  align=\"center\">";
				$salida1.="							$ ".FormatoValor($valor1['valor_a_pagar'])."";
				$salida1.="						</td>";
				$salida1.="					</tr>";
				
				$total_profesional+=$valor1['valor_a_pagar'];
			}
			
			$salida1.="				</table>";
			
			$salida0.="		<tr $style1>";
			$salida0.="			<td  align=\"center\">".$key."</td>";
			$salida0.="			<td  align=\"center\">".$valor[$key1]['nombre']."</td>";
			if($a)
			{
				$salida0.="			<td  align=\"center\">";
				$salida0.="				$salida1";
				$salida0.="			</td>";
			}
			$salida0.="			<td  align=\"right\"> $ ".FormatoValor($total_profesional)."</td>";
			$salida0.="		</tr>";
			$total+=$total_profesional;
			$i++;
		}
		$this->salida.="		$salida0";
		$this->salida.="		<tr $style>";
		$this->salida.="			<td colspan=\"$cols\" align=\"right\">VALOR TOTAL</td>";
		$this->salida.="			<td align=\"right\"> $ ".FormatoValor($total)."</td>";
		$this->salida.="		</tr>";
		$this->salida.="	</table><br>";
		
		echo $this->salida;
	}
}

$VISTA = "HTML";
$_ROOT = "../../../../";
include  $_ROOT."classes/rs_server/rs_server.class.php";
include	 $_ROOT."includes/enviroment.inc.php";
$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
IncludeFile($filename);

$rep=new ReporteGeneral_report();
$rep->CrearReporte();

?>