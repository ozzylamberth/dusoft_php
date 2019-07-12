<?php

/**
 * $Id: OrdenesdeCompra_html.report.php,v 1.5 2007/07/04 20:07:25 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 */

class OrdenesdeCompra_html_report
{
	var $datos;
	
	function OrdenesdeCompra_html_report($datos=array())
	{
		$this->datos['orden_pedido_id']=$_REQUEST['orden_pedido_id'];
		$this->datos['proveedor']=$_REQUEST['proveedor'];
		$this->datos['fecha_orden']=$_REQUEST['fecha_orden'];
		$this->datos['usuario']=$_REQUEST['usuario'];
		return true;
	}

	function CrearReporte()
	{
		$style	= "style=\"font-size:12px; font-weight:bold;\"";
		$style1	= "style=\"font-size:12px\"";
		
		$datosOrdenes = $this->GetDetalleOrdenes($this->datos['orden_pedido_id']); 
		$datosEmpresa=$this->GetEmpresa($_SESSION['OC']['empresa_id']);
		$datosProveedor=$this->GetProveedor($this->datos['proveedor']);
		
		$salida.="<br><table  align=\"left\" border=\"0\"  width=\"20%\">";
		$salida.="	<tr>";
		$salida.="		<td><img src=\"../../../../images/C.O.Cali.png\" height=\"80\" width=\"100\" border=\"0\"></td>";
		$salida.="	</tr>";
		$salida.="</table>";
		
		$salida .= "	<br><table border=\"1\" width=\"80%\" align=\"center\">";
		$salida .= "		<tr>";
		$salida .= "			<td width=\"10%\" $style>IDENTIFICACION</td>";
		$salida .= "			<td width=\"20%\" $style1>".$datosEmpresa['tipo_id_tercero']." - ".$datosEmpresa['id']."</td>";
		$salida .= "			<td width=\"10%\" $style>EMPRESA</td>";
		$salida .= "			<td width=\"20%\" $style1 colspan=\"3\">".$datosEmpresa['razon_social']."</td>";
		$salida .= "		</tr>";
		$salida .= "		<tr>";
		$salida .= "			<td width=\"10%\" $style>DIRECCION</td>";
		$salida .= "			<td width=\"20%\" $style1>".$datosEmpresa['direccion']."</td>";
		$salida .= "			<td width=\"10%\" $style>TELEFONO - FAX</td>";
		$salida .= "			<td width=\"20%\" $style1 colspan=\"3\">".$datosEmpresa['telefonos']." - ".$datosEmpresa['fax']."</td>";
		$salida .= "		</tr>";
		$salida .= "		<tr>";
		$salida .= "			<td width=\"10%\" $style>IDENTIFICACION</td>";
		$salida .= "			<td width=\"20%\" $style1>".$datosProveedor['tipo_id_tercero']." - ".$datosProveedor['tercero_id']."</td>";
		$salida .= "			<td width=\"10%\" $style>PROVEEDOR</td>";
		$salida .= "			<td width=\"20%\" $style1>".$datosProveedor['nombre_tercero']."</td>";
		$salida .= "			<td width=\"10%\" $style>NUMERO DE ORDEN</td>";
		$salida .= "			<td width=\"10%\" $style1>".$this->datos['orden_pedido_id']."</td>";
		$salida .= "		</tr>";
		$salida .= "		<tr>";
		$salida .= "			<td width=\"10%\" $style>FECHA DE ORDEN</td>";
		$salida .= "			<td width=\"20%\" $style1>".$this->datos['fecha_orden']."</td>";
		$salida .= "			<td width=\"10%\" $style>USUARIO</td>";
		$salida .= "			<td width=\"20%\" $style1 colspan=\"3\">".$this->datos['usuario']."</td>";
		$salida .= "		</tr>";
		$salida .= "	</table><br>";
		
		$j=0;
		$salida .= "	<table border=\"1\" width=\"100%\" align=\"center\">";
		$salida .= "		<tr $style align=\"center\">";
		$salida .= "			<td width=\"10%\">CÓDIGO</td>";
		$salida .= "			<td width=\"10%\">DESCRIPCIÓN</td>";
		$salida .= "			<td width=\"10%\">UNIDAD</td>";
		$salida .= "			<td width=\"10%\">CONTENIDO</td>";
		$salida .= "			<td width=\"10%\">VALOR</td>";
		$salida .= "			<td width=\"10%\">CANTIDAD</td>";
		$salida .= "			<td width=\"10%\">VALOR NETO</td>";
		$salida .= "			<td width=\"5%\"> % IVA</td>";
		$salida .= "			<td width=\"10%\">VALOR TOTAL</td>";
		$salida .= "		</tr>";
		
		$TotalCompra=0;
		foreach($datosOrdenes as $key=>$valor)
		{
			$salida .= "<tr $style1>";
			$salida .= "	<td align=\"center\">";
			$salida .= "		".$valor['codigo_producto']."";
			$salida .= "	</td>";
			$salida .= "	<td>";
			$salida .= "		".$valor['descripcion']."";
			$salida .= "	</td>";
			$salida .= "	<td>";
			$salida .= "		&nbsp;".$valor['ab_unidad']."";
			$salida .= "	</td>";
			$salida .= "	<td>";
			$salida .= "		&nbsp;".$valor['contenido_unidad_venta']."";
			$salida .= "	</td>";
			$salida .= "	<td align=\"right\">";
			$salida .= "		$ ".FormatoValor($valor['valor'])."";
			$salida .= "	</td>";
			$salida .= "	<td align=\"center\">";
			$salida .= "		".FormatoValor($valor['cantidad'])."";
			$salida .= "	</td>";
			$salida .= "	<td align=\"right\">";
			$salida .= "		$ ".FormatoValor($valor['valor']*$valor['cantidad'])."";
			$salida .= "	</td>";
			$salida .= "	<td align=\"right\">";
			$salida .= "		".FormatoValor($valor['porc_iva'])." %";
			$salida .= "	</td>";
			$salida .= "	<td align=\"right\">";
			$valor_total=$valor['cantidad']*($valor['valor']+($valor['valor']*$valor['porc_iva'])/100);
			$salida .= "		$ ".FormatoValor($valor_total)."";
			$salida .= "	</td>";
			$salida .= "</tr>";
			$TotalCompra+=$valor_total;
			$j++;
		}
		$salida .= "	<tr $style>";
		$salida .= "		<td colspan=\"8\" align=\"right\">TOTAL VALOR COMPRA</td>";
		$salida .= "		<td align=\"right\">";
		$salida .= "			$ ".FormatoValor($TotalCompra)."";
		$salida .= "		</td>";
		$salida .= "	</tr>";
		$salida .= "</table><br>";
		
		echo $salida;
	}
	
	function GetDetalleOrdenes($orden_pedido_id)
	{
		list($dbconn) = GetDBconn();
		
		$query = 	"
								SELECT 	b.codigo_producto,
												c.descripcion,
												c.unidad_id,
												c.contenido_unidad_venta,
												b.numero_unidades as cantidad,
												b.valor,
												b.porc_iva,
												d.descripcion as ab_unidad
												
								FROM 		compras_ordenes_pedidos as a,
												compras_ordenes_pedidos_detalle as b,
												inventarios_productos as c,
												unidades as d
												
								WHERE 	a.orden_pedido_id=b.orden_pedido_id
								AND 		b.codigo_producto=c.codigo_producto
								AND 		c.unidad_id=d.unidad_id
								AND 		a.orden_pedido_id=$orden_pedido_id
							";
		
		$result = $dbconn->Execute($query);
	
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdeCompra - GetOrdenesCompra";
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
		$result->Close();
		
		return $vars;
	}
	
	function GetEmpresa()
	{
		list($dbconn) = GetDBconn();
		
		$query = 	"	SELECT 	*
								FROM empresas
								WHERE empresa_id='".$_SESSION['OC']['empresa_id']."'";
		
		$result = $dbconn->Execute($query);
	
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdeCompra - GetEmpresa";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		if($result->RecordCount() > 0)
		{
			while(!$result->EOF)
			{
				$vars=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		return $vars;
	}
	
	function GetProveedor($proveedor)
	{
		list($dbconn) = GetDBconn();
		
		$query = 	"	SELECT 	a.tipo_id_tercero,
												a.tercero_id,
												b.nombre_tercero
								FROM 	terceros_proveedores as a,
											terceros as b
								WHERE a.codigo_proveedor_id='".$proveedor."'
								AND a.tipo_id_tercero=b.tipo_id_tercero
								AND a.tercero_id=b.tercero_id;
							";
		
		$result = $dbconn->Execute($query);
	
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdeCompra - GetEmpresa";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		if($result->RecordCount() > 0)
		{
			while(!$result->EOF)
			{
				$vars=$result->GetRowAssoc($ToUpper = false);
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

$rep=new OrdenesdeCompra_html_report();
$rep->CrearReporte();
?>