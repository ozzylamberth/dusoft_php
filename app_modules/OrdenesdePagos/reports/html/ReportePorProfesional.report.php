<?php

/**
 * $Id: ReportePorProfesional.report.php,v 1.1 2007/09/10 15:10:51 jgomez Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

   $fecha_ini=$_REQUEST['fecha_ini'];
   $fecha_fin=$_REQUEST['fecha_fin'];
   $tipo_id_profesional=$_REQUEST['tipo_id'];
   $profesional_id=$_REQUEST['profesional_id'];

class ReportePorProfesional_report
{
	var $datos;
	var $mensajeDeError;
	
  function ReportePorProfesional_report($datos=array())
	{
		//$this->fecha_ini=$_REQUEST['fecha_ini'];
		//$this->fecha_fin=$_REQUEST['fecha_fin'];
		//$this->sw=$_REQUEST['sw'];
		return true;
	}
	
  
/*********************************************************************************************
*
************************************************************************************************/
	function ReporteProfesional($fecha_ini,$fecha_fin,$tipo_id_profesional,$profesional_id)
	{
		
		list($dbconn) = GetDBconn();
		
		if(!empty($fecha_ini) AND !empty($fecha_fin))
		{
			$filtro="AND date(A.fecha)>='$fecha_ini'
								AND date(A.fecha)<='$fecha_fin'";
		}
		
		$query  = "SELECT  A.tipo_id_profesional,
												A.profesional_id,
												D.numero_factura_id,
												A.nombre,
												A.prefijo,
												A.numero,
												B.descripcion,
												A.valor_real as valor_a_pagar
								FROM
								(
									(
										SELECT 	a.tipo_id_profesional,
														a.profesional_id,
														d.nombre,
														a.valor_real,
														a.prefijo,
														a.numero,
														a.cargo,
														a.tarifario_id,
														c.fecha_registro as fecha
										FROM voucher_honorarios as a
										JOIN rc_detalle_tesoreria_facturas as b
										ON
										(
											a.numero_recibo = b.prefijo || b.recibo_caja
										)
										JOIN recibos_caja as c
										ON
										(
											b.empresa_id=c.empresa_id
											AND b.centro_utilidad=c.centro_utilidad
											AND b.prefijo=c.prefijo
											AND b.recibo_caja=c.recibo_caja
										)
										JOIN profesionales as d
										ON
										(
											a.profesional_id=d.tercero_id
											AND a.tipo_id_profesional=d.tipo_id_tercero
                      AND a.tipo_id_profesional='$tipo_id_profesional'
                      AND a.profesional_id='$profesional_id'
										)
										WHERE a.estado='1'
									)
									UNION
									(
										SELECT 	a.tipo_id_profesional,
														a.profesional_id,
														c.nombre,
														a.valor_real,
														a.prefijo,
														a.numero,
														a.cargo,
														a.tarifario_id,
														b.fecha
										FROM voucher_honorarios as a
										JOIN rc_detalle_tesoreria_facturas_externas as b
										ON
										(
											a.numero_recibo = b.numero_recibo
										)
										JOIN profesionales as c
										ON
										(
											a.profesional_id=c.tercero_id
											AND a.tipo_id_profesional=c.tipo_id_tercero
                      AND a.tipo_id_profesional='$tipo_id_profesional'
                      AND a.profesional_id='$profesional_id'
										)
										WHERE a.estado='1'
									)
								)as A
								LEFT JOIN voucher_honorarios_facturas_profesionales as C
								ON
								(
										A.prefijo=C.prefijo
										AND A.numero=C.numero
								)
								LEFT JOIN voucher_honorarios_cuentas_x_pagar as D
								ON
								(
									C.prefijo_cxp=D.prefijo
									AND C.numero_cxp=D.numero
									AND D.estado='1'
								),
								tarifarios_detalle as B
								
								WHERE 
										A.tarifario_id=B.tarifario_id 
										AND A.cargo=B.cargo
										AND A.valor_real > 0	
										$filtro
								ORDER BY A.nombre,A.numero
							";
	
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdePagos - ReporteGeneral SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return "Error DB : " . $dbconn->ErrorMsg();
		}
		else
		{
			if($result->RecordCount()>0)
			{
				while (!$result->EOF) 
				{
					$vars[$result->fields[0]."-".$result->fields[1]][]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		
		return $vars;
	}
  
	function CrearReporte($fecha_ini,$fecha_fin,$tipo_id_profesional,$profesional_id)
	{
		$this->salida .= "<br><center><font size=\"4\"> REPORTE HONORARIOS POR PROFESIONAL </font><br><br>".strtoupper(FormatoFecha(1)).", ".date("g:i a")."</center><br><br>\n";
		
		$style= "style=\"font-size:12px; font-weight:bold;\"";
		$style1= "style=\"font-size:12px\"";
				
		$datosG=$this->ReporteProfesional($fecha_ini,$fecha_fin,$tipo_id_profesional,$profesional_id);
	//	echo $this->mensajeDeError;
    //var_dump($datosG);
		
    $this->salida.="	<table border=\"0\" width=\"50%\" align=\"center\">";    
		$this->salida.="		<tr align=\"center\">";
		$this->salida.="			<td $style width=\"30%\">FECHAS: </td>";
		if(!empty($fecha_fin))
    {
        $this->salida.="			<td $style width=\"70%\">DESDE &nbsp;&nbsp;".$fecha_ini." &nbsp;&nbsp;HASTA &nbsp;&nbsp;".$fecha_fin."</td>";
    }
    else
    {
           $this->salida.="			<td $style width=\"70%\">DESDE &nbsp;&nbsp;".$fecha_ini." &nbsp;&nbsp;HASTA &nbsp;&nbsp;".date("Y-m-d")."</td>";
    }    
		$this->salida.="		</tr>";
		$this->salida.="	</table><br>";
		$this->sw=1;
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

$rep=new ReportePorProfesional_report();
// echo "f1".$fecha_ini=$_REQUEST['fecha_ini'];
//    echo "f2".$fecha_fin=$_REQUEST['fecha_fin'];
//    echo "t".$tipo_id_profesional=$_REQUEST['tipo_id'];
//    echo "d".$profesional_id=$_REQUEST['profesional_id'];
$rep->CrearReporte($fecha_ini,$fecha_fin,$tipo_id_profesional,$profesional_id);

?>