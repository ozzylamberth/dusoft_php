<?php

/**
 * $Id: ReporteImpCuentas.php,v 1.4 2007/02/23 22:18:12 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 */

class ReporteImpCuentas
{
	var $datos;
	
	function ReporteImpCuentas($datos=array())
	{
		$this->datos['enlace']=$_REQUEST['enlace'];
		$this->datos['opcion']=$_REQUEST['opcion'];
		$this->datos['titulo']=$_REQUEST['titulo'];
		return true;
	}

	function CrearReporte()
	{
		$salida .= "<br><center><font size=\"4\">".$this->datos['titulo']."</font><br>".strtoupper(FormatoFecha(1))." , ".date("g:i a")."</center><br>\n";
		$style= "style=\"font-size:14px; font-weight:bold;\"";
		$style1= "style=\"font-size:14px\"";
		
		$entidad=array();
		$cont=0;
		
		$salida .= "<table align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
		foreach($_SESSION['listado'] as $key=>$valor)
		{
			$salida .= "	<tr align=\"center\" $style>";
			$salida .= "		<td align=\"center\" colspan=\"3\">DEPARTAMENTO  -  $key</td>";
			$salida .= "	</tr>\n";
			foreach($valor as $key1=>$valor1)
			{
				$salida .= "	<tr align=\"center\" $style>";
				$salida .= "		<td align =\"center\" colspan=\"3\">ESTACION  -  $key1</td>";
				$salida .= "	</tr>";
				$salida .= "	<tr>";
				$salida .= "		<td colspan=\"3\">";
				$salida .= "			<table align=\"center\" width=\"100%\" border=\"1\">";
				$salida .= "				<tr class=\"label\" align=\"center\" $style>";
				$salida .= "					<td width=\"5%\">CUENTA</td>";
				$salida .= "					<td width=\"10%\">ID</td>";
				$salida .= "					<td width=\"10%\">PACIENTE</td>";
				$salida .= "					<td width=\"10%\">AFILIACION</td>";
				$salida .= "					<td width=\"5%\">RANGO</td>";
				$salida .= "					<td width=\"5%\">HAB.</td>";
				$salida .= "					<td width=\"5%\">CAMA</td>";
				$salida .= "					<td width=\"10%\">FECHA INGRESO</td>";
				$salida .= "					<td width=\"5%\">TIEMPO<BR>HOSP (DIAS)</td>";
				$salida .= "					<td width=\"10%\">TERCERO</td>";
				$salida .= "					<td width=\"10%\">PLAN</td>";
				if($this->datos['enlace']==2 AND $this->datos['opcion']==3)
					$salida .= "					<td>ESTADO CUENTA</td>\n";
				$salida .= "					<td width=\"15%\">VALOR CUBIERTO + HAB</td>";
				$salida .= "				</tr>\n";
				$k=0;
				foreach($valor1 as $key2=>$valor2)
				{
					$entidad[$valor2['nombre_tercero']]['valor_cuenta']+=$valor2['t_vc_apc']+$valor2['t_vnc_apc'];
					$entidad[$valor2['nombre_tercero']]['contador']+=1;
					
					$salida .= "				<tr align=\"center\" $style1>";
					$salida .= "					<td>".$valor2['numerodecuenta']."</td>";
					$salida .= "					<td>".$valor2['tipo_id_paciente']." - ".$valor2['paciente_id']."</td>";
					$salida .= "					<td>".$valor2['nombre_completo']."</td>";
					$salida .= "					<td>".strtoupper($valor2['tipo_afiliado_nombre'])."</td>";
					$salida .= "					<td>".$valor2['rango']."</td>";
					$salida .= "					<td>".$valor2['pieza']."</td>";
					$salida .= "					<td>".$valor2['cama']."</td>";
					$salida .= "					<td>".date('Y-m-d g:i a',strtotime($valor2['fecha_ingreso']))."</td>";
					$salida .= "					<td>".$this->GetDiasHospitalizacion($valor2['fecha_ingreso'])."</td>";
					$salida .= "					<td>".$valor2['nombre_tercero']."</td>";
					$salida .= "					<td>".$valor2['plan_descripcion']."</td>";
					if($this->datos['enlace']==2 AND $this->datos['opcion']==3)
						$salida .= "					<td>".$valor2['estado_cuenta']."</td>\n";
					$salida .= "					<td> $ ".FormatoValor($valor2['t_vc_apc']+$valor2['t_vnc_apc'])."</td>";
					$salida .= "				</tr>";
					$sum+=$valor2['t_vc_apc']+$valor2['t_vnc_apc'];
					$cont++;
					$k++;
				}
				if($this->datos['enlace']==1)
				{
					$co=$this->GetCamas($valor[$key1][$key2]['estacion_id'],'0');
					$cd=$this->GetCamas($valor[$key1][$key2]['estacion_id'],'1');
					
					$salida .= "				<tr class=\"hc_table_submodulo_list_title\">\n";
					$salida .= "					<td  colspan=\"12\" align=\"right\">CAMAS DISPONIBLES : &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$cd</label> &nbsp;&nbsp;&nbsp; CAMAS OCUPADAS : &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$co</label> &nbsp;&nbsp;&nbsp; CANTIDAD : &nbsp;&nbsp;&nbsp; <label class=\"label_error\">$k</label> </td>\n";
					$salida .= "				</tr>\n";
				}
				
				$salida .= "			</table>";
				$salida .= "		</td>";
				$salida .= "	</tr>";
			}
		}
		$salida .= "	<tr $style align=\"center\">\n";
		$salida .= "		<td>ENTIDAD</td>\n";
		$salida .= "		<td>CANTIDAD</td>\n";
		$salida .= "		<td>VALOR CUENTA</td>\n";
		$salida .= "	</tr>\n";
		foreach($entidad as $key=>$valor_ent)
		{
			$salida .= "	<tr $style1>\n";
			$salida .= "		<td align=\"right\" class=\"label\">$key</td>\n";
			$salida .= "		<td align=\"right\" class=\"label\">".$valor_ent['contador']."</td>\n";
			$salida .= "		<td align=\"right\" class=\"label\"> $ ".FormatoValor($valor_ent['valor_cuenta'])."</td>\n";
			$salida .= "	</tr>\n";
		}
		
		$salida .= "	<tr $style align=\"center\">\n";
		$salida .= "		<td align=\"right\" class=\"label\">TOTAL : </td>\n";
		$salida .= "		<td align=\"right\" class=\"label\">$cont</td>";
		$salida .= "		<td align=\"right\" class=\"label\"> $ ".FormatoValor($sum)."</td>\n";
		$salida .= "	</tr>\n";
		
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
	
	
	function GetCamas($estacion,$estado)
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT count(DISTINCT b.cama)
						FROM piezas as a
						JOIN camas as b
						ON
						(
							a.pieza=b.pieza
						)
						WHERE a.estacion_id='$estacion'
						AND b.estado='$estado'";
		
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Facturacion - GetCamas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$num_camas=$result->fields[0];
		}
		
		return $num_camas;
	}
	
}

$VISTA = "HTML";
$_ROOT = "../../../../";
include  $_ROOT."classes/rs_server/rs_server.class.php";
include	 $_ROOT."includes/enviroment.inc.php";
$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
IncludeFile($filename);

$rep=new ReporteImpCuentas();
$rep->CrearReporte();

?>
