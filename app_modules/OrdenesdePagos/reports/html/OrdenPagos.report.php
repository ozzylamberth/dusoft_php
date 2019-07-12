<?php

/**
 * $Id: OrdenPagos.report.php,v 1.1 2007/05/14 19:41:58 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class OrdenPagos
{
	var $datos;
	
	function OrdenPagos($datos=array())
	{
		$this->datos['TipoProf']=$_REQUEST['TipoProf'];
		$this->datos['Prof']=$_REQUEST['Prof'];
		$this->datos['Nombre']=$_REQUEST['nombre'];
		$this->datos['plan']=$_REQUEST['plan'];
		$this->datos['fecha_ini']=$_REQUEST['fecha_ini'];
		$this->datos['fecha_fin']=$_REQUEST['fecha_fin'];
		$this->datos['radicado']=$_REQUEST['radicado'];
		$this->datos['recaudo']=$_REQUEST['recaudo'];
		$this->datos['empresa_id']=$_REQUEST['empresa_id'];
		$this->datos['prefijo']=$_REQUEST['prefijo'];
		$this->datos['numero']=$_REQUEST['numero'];
		
		return true;
	}
	
	function CrearReporte()
	{
		list($plan,$nom_plan)=explode("çç",$this->datos['plan']);
		
		$listado_ordenes=$this->GetOrdenesdePagoTotal($this->datos['TipoProf'],$this->datos['Prof'],$plan,$this->datos['fecha_ini'],$this->datos['fecha_fin'],$this->datos['radicado'],$this->datos['recaudo'],$this->datos['empresa_id'],$this->datos['prefijo'],$this->datos['numero']);
		
		$salidaP .= "<br><center><font size=\"5\">ORDENES DE PAGOS</font><br><br>".strtoupper(FormatoFecha(1)).", ".date("g:i a")."</center><br><br>\n";
		
		$style= "style=\"font-size:12px; font-weight:bold;\"";
		$style1= "style=\"font-size:12px\"";
				
		if($listado_ordenes)
		{
			foreach($listado_ordenes as $key=>$valor)
			{
				$salida1="";
				$salida="";
				foreach($valor as $key1=>$valor1)
				{
					$salida1= "	<table align=\"center\" width=\"100%\" border=\"1\">\n";
					$salida1.= "		<tr $style align=\"center\">";
					$salida1.= "			<td width=\"10%\">FACTURA</td>";
					$salida1.= "			<td width=\"10%\">VOUCHER</td>";
					$salida1.= "			<td width=\"20%\">CARGO</td>";
					$salida1.= "			<td width=\"10%\">RECIBO</td>";
					$salida1.= "			<td width=\"15%\">PACIENTE</td>";
					$salida1.= "			<td width=\"15%\">PLAN</td>";
					$salida1.= "			<td width=\"10%\">VALOR VOUCHER</td>";
					$salida1.= "			<td width=\"10%\">VALOR FACTURA</td>";
					$salida1.= "    </tr>";
					$j=0;
					foreach($valor1 as $key2=>$valor2)
					{
						$a=true;
						foreach($valor2 as $key3=>$valor3)
						{
							if($j % 2 == 0)
							{
								$estilo='modulo_list_oscuro';
							}
							else
							{
								$estilo='modulo_list_claro';
							}
							
							$salida1.= "		<tr $style1 align=\"center\">";
							$salida1.= "			<td>".$valor3['numero_factura_id']."</td>";
							$salida1.= "			<td>".$valor3['prefijo_v']." - ".$valor3['numero_v']."</td>";
							$salida1.= "			<td>".$valor3['desc_cargo']."</td>";
							$salida1.= "			<td>".$valor3['numero_recibo']."&nbsp;</td>";
							$salida1.= "			<td>".$valor3['nombre_paciente']."</td>";
							$salida1.= "			<td>".$valor3['plan_descripcion']."</td>";
							$salida1.= "			<td align=\"right\"> $ ".FormatoValor($valor3['valor_real'])."</td>";
							if($a)
							{
								$salida1.= "			<td align=\"right\" rowspan=\"".sizeof($valor2)."\"> $ ".FormatoValor($valor3['valor'])."</td>";
								$a=false;
							}
							$salida1.= "		</tr>";
								
							$j++;
						}
					}
					$salida1.= "	</table>";
					
					$salida.= "	<br><table align=\"center\" width=\"100%\" border=\"1\">\n";
					$salida.= "		<tr>\n";
					$salida.= "				<td $style width=\"15%\" align=\"left\">ORDEN DE PAGO</td>";
					$salida.= "				<td $style1 width=\"15%\" align=\"left\">$key1</td>";
					$salida.= "				<td $style width=\"15%\" align=\"left\">FECHA</td>";
					$salida.= "				<td $style1 width=\"20%\" align=\"left\">".$valor1[$key2][$key3]['fecha']."</td>";
					$salida.= "				<td $style width=\"15%\" align=\"left\">VALOR</td>";
					$salida.= "				<td $style1 width=\"30%\" align=\"left\"> $ ".FormatoValor($valor1[$key2][$key3]['valor_total'])."</td>";
					$salida.= "		</tr>\n";
					$salida.= "	</table>\n";
					$salida.= "	$salida1";
					$i++;
				}
				
				
				$salida0 .= "	<table border=\"1\" width=\"100%\" align=\"center\">";    
				$salida0 .= "		<tr align=\"center\">";
				$salida0 .= "			<td width=\"20%\" $style>IDENTIFICACION</td>";
				$salida0 .= "			<td width=\"20%\" $style1>$key</td>";
				$salida0 .= "    	<td width=\"20%\" $style>PROFESIONAL</td>"; 
				$salida0 .= "			<td width=\"25%\" $style1>".$listado_ordenes[$key][$key1][$key2][$key3]['nombre']."</td>";
				$salida0 .= "		</tr>";
				$salida0 .= "		</table>";
				$salida0 .= "	$salida<br><br>";
			}
		}

		if(!empty($this->datos['fecha_ini']) AND !empty($this->datos['fecha_fin']))
		{
			$salidaP .= "	<table width=\"100%\" align=\"center\" border=\"1\">";    
			$salidaP .= "    <tr>";
			$salidaP .= "    	<td align=\"center\" width=\"20%\" $style>FECHA RADICACION</td>";
			$salidaP .= "    	<td align=\"left\" width=\"80%\"$style1>DESDE ". $this->datos['fecha_ini']." HASTA ".$this->datos['fecha_fin']."</td>";
			$salidaP .= "    </tr>";  
			$salidaP .= "	</table>";
		}
		
		if(!empty($plan))
		{
			$salidaP .= "	<table width=\"100%\" align=\"center\" border=\"1\">";    
			$salidaP .= "    <tr>";
			$salidaP .= "    	<td align=\"center\" width=\"20%\" $style>PLAN</td>";
			$salidaP .= "    	<td  align=\"left\" width=\"80%\" $style1>".$plan." - ".$nom_plan."</td>";
			$salidaP .= "    </tr>";  
			$salidaP .= "	</table>";
		}
		$salidaP .= "	<br>";
		$salidaP .= "    $salida0";
		
		echo $salidaP;
	}
	
	
	function GetOrdenesdePagoTotal($TipoidProf,$Prof,$plan,$fecha_ini,$fecha_fin,$radicado,$recaudo,$empresa,$prefijo,$numero)
	{
		list($dbconn) = GetDBconn();
		
		if(!empty($TipoidProf) AND !empty($Prof))
		{
			$datos.=" AND a.tipo_id_profesional='$TipoidProf'
							 	AND a.profesional_id='$Prof'";
		}
		
		if(!empty($fecha_ini) AND !empty($fecha_fin))
		{
			$datos.=" AND date(d.fecha_radicacion)>='$fecha_ini'
							 	AND date(d.fecha_radicacion)<='$fecha_fin'";
		}
		
		if(!empty($empresa) AND !empty($prefijo) AND !empty($numero))
		{
			$datos.="AND a.empresa_id='$empresa'
						 	AND a.prefijo='$prefijo'
						 	AND a.numero=$numero";
		}
		
		if($plan)
		{
			$datos.=" AND d.plan_id=$plan";
		}
		
		if($radicado)
		{
			$datos.=" AND d.fecha_radicacion IS NOT NULL";
		}
		
		if($recaudo)
		{
			$datos.=" AND d.numero_recibo IS NOT NULL";
		}
		
		$query = "	SELECT
										d.tipo_id_profesional,
										d.profesional_id,
										a.prefijo as prefijo_op,
										a.numero as numero_op,
										e.nombre,
										b.numero_factura_id,
										d.prefijo as prefijo_v,
										d.numero as numero_v,
										d.valor_real,
										b.valor,
										a.valor_total,
										d.numero_recibo,
										f.primer_apellido || ' ' || f.segundo_apellido || ' ' || f.primer_nombre || ' ' || f.segundo_nombre as nombre_paciente,
										d.plan_id,
										g.plan_descripcion,
										h.nombre_tercero,
										a.fecha_registro,
										TO_CHAR(a.fecha_registro,'YYYY-MM-DD') as fecha,
										a.empresa_id,
										i.descripcion as desc_cargo,
										d.numerodecuenta
								FROM voucher_honorarios_ordenes_de_pago as a
								JOIN voucher_honorarios_cuentas_x_pagar as b
								ON
								(
									a.empresa_id=b.empresa_id
									AND a.prefijo=b.prefijo_orden
									AND a.numero=b.numero_orden
								)
								JOIN voucher_honorarios_facturas_profesionales as c
								ON
								(
									a.empresa_id=c.empresa_id
									AND b.prefijo=c.prefijo_cxp
									AND b.numero=c.numero_cxp
								)
								JOIN voucher_honorarios as d
								ON
								(
									c.empresa_id=d.empresa_id
									AND c.prefijo=d.prefijo
									AND c.numero=d.numero
								)
								JOIN profesionales as e
								ON
								(
									d.tipo_id_profesional=e.tipo_id_tercero
									AND d.profesional_id=e.tercero_id
								)
								JOIN pacientes as f
								ON
								(
									d.tipo_id_paciente=f.tipo_id_paciente
									AND d.paciente_id=f.paciente_id
								)
								JOIN planes as g
								ON
								(
									d.plan_id=g.plan_id
								)
								LEFT JOIN terceros as h
								ON
								(
									g.tipo_tercero_id=h.tipo_id_tercero
									AND g.tercero_id=h.tercero_id
								)
								JOIN tarifarios_detalle as i
								ON
								(
									d.tarifario_id=i.tarifario_id
									AND d.cargo=i.cargo
								)
								WHERE a.estado='1'
								AND a.sw_cancelado='0'
								$datos 
								ORDER BY a.fecha_registro DESC
								";
		
		$result=$dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount()>0)
			{
				if($result->RecordCount()>0)
				{
					while (!$result->EOF) 
					{
						$vars[$result->fields[0]."-".$result->fields[1]][$result->fields[2]."-".$result->fields[3]][$result->fields[5]][]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
		}
		return $vars;
	}
}

$VISTA = "HTML";
$_ROOT = "../../../../";
include  $_ROOT."classes/rs_server/rs_server.class.php";
include	 $_ROOT."includes/enviroment.inc.php";
$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
IncludeFile($filename);

$rep=new OrdenPagos();
$rep->CrearReporte();

?>
