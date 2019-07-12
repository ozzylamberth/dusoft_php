<?php

/**
 * $Id: BodegasReposiciones.report.php,v 1.3 2007/07/10 13:47:32 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 */

class BodegasReposiciones_report
{
	var $datos;
	
	function BodegasReposiciones_report($datos=array())
	{
		$this->datos=$_REQUEST;
		return true;
	}

	function CrearReporte()
	{
		$style	= "style=\"font-size:12px; font-weight:bold;\"";
		$style1	= "style=\"font-size:12px\"";
		
		$datosReport=$this->GetProductosBodegasReposicion($this->datos['bodega']);
		
		$salida.="<br><table  align=\"center\" border=\"0\"  width=\"20%\">";
		$salida.="	<tr align=\"left\">";
		$salida.="		<td><img src=\"../../../../images/logocliente.png\" border=\"0\"></td>";
		$salida.="	</tr>";
		$salida.="</table>";
		
		/*Datos Cabecera*/
		$salida.="<table border=\"1\" width=\"100%\" align=\"center\">";
		$salida.="	<tr $style>";
		$salida.="		<td $style width=\"40%\">";
		$salida.="			BODEGA ORIGEN";
		$salida.="		</td>";
		$salida.="		<td $style width=\"40%\">";
		$salida.="			BODEGA DESTINO";
		$salida.="		</td>";
		$salida.="		<td width=\"20%\">";
		$salida.="			FECHA y HORA DE IMPRESION";
		$salida.="		</td>";
		$salida.="	</tr>";
		$salida.="	<tr $style1>";
		$salida.="		<td>";
		$salida.="			".$_SESSION['BodegasReposicion']['bodega']." - ".$_SESSION['BodegasReposicion']['bodega_desc'];
		$salida.="		</td>";
		$salida.="		<td>";
		$salida.="			".$this->datos['bodega']." - ".$this->datos['descripcion']."";
		$salida.="		</td>";
		$salida.="		<td>";
		$salida.="			".date("Y-m-d , g:i a")."";
		$salida.="		</td>";
		$salida.="	</tr>";
		$salida.="</table>";
		/*fin Cabecera*/
		
		$salida .= "	<br><table border=\"1\" width=\"100%\" align=\"center\">";
		$salida .= "		<tr $style align=\"center\">";
		$salida .= "			<td width=\"20%\">CODIGO PRODUCTO</td>";
		$salida .= "			<td width=\"40%\">DESCRIPCION</td>";
		$salida .= "			<td width=\"10%\">UNIDAD</td>";
		$salida .= "			<td width=\"10%\">CANTIDAD</td>";
		$salida .= "			<td width=\"20%\">COSTO UNITARIO</td>";
		$salida .= "		</tr>";
		
		foreach($datosReport as $valor)
		{
			$salida .= "		<tr $style1>";
			$salida .= "			<td>".$valor['codigo_producto']."</td>";
			$salida .= "			<td>".$valor['descripcion']."</td>";
			$salida .= "			<td>".$valor['descripcion_unidad']."</td>";
			$salida .= "			<td align=\"right\">".FormatoValor($valor['pedido'])."</td>";
			$salida .= "			<td align=\"right\"> $ ".FormatoValor($valor['costo'])."</td>";
			$salida .= "		</tr>";
		}
		$salida .= "		</table>";
		
		echo $salida;
	}
	
	function GetProductosBodegasReposicion($bodega)
	{
		list($dbconn) = GetDBconn();
	
		$query=	"
							SELECT
							a.codigo_producto,
							b.descripcion,
							c.descripcion as descripcion_unidad,
							a.bodega,
							abs(a.existencia_maxima - a.existencia) as pedido,
							a.existencia,
							a.existencia_minima,
							a.existencia_maxima,
							d.costo
							
							FROM
							existencias_bodegas as a,
							inventarios_productos as b,
							unidades as c,
							inventarios as d
							
							WHERE a.bodega = '$bodega'
							AND a.existencia < a.existencia_minima
							AND a.codigo_producto = b.codigo_producto
							AND c.unidad_id = b.unidad_id
							AND b.codigo_producto=d.codigo_producto
							AND a.estado='1'
							ORDER BY b.descripcion
						";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo InvBodegasReposicion - GetReporteBodegasReposicion SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		if($result->RecordCount() > 0)
		{
			while(!$result->EOF)
			{
				$vars[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		
		return $vars;
	}
	
}

$VISTA = "HTML";
$_ROOT = "../../../../";
include	 $_ROOT."includes/enviroment.inc.php";
$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
IncludeFile($filename);

$rep=new BodegasReposiciones_report();
$rep->CrearReporte();
?>