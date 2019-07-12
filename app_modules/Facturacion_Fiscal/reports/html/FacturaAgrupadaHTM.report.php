<?php

/**
 * $Id: FacturaAgrupadaHTM.report.php,v 1.5 2010/12/06 22:13:37 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class FacturaAgrupadaHTM_report
{
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
	var $datos;
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function FacturaAgrupadaHTM_report($datos=array())
	{
			$this->datos=$datos;
			return true;
	}


	function GetMembrete()
	{
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
												'subtitulo'=>'','logo'=>'logocliente.png','align'=>'left'));
			return $Membrete;
	}

	/**
	*
	*/
	function CrearReporte()
	{
			IncludeLib('funciones_admision');
			include("classes/fpdf/conversor.php");
			$dat=$this->DatosFactura($this->datos[prefijo],$this->datos[numero]);

			$Salida.="<table border=0 width=100% align='center'>";
			$Salida.="<tr><td class=\"normal_10N\" width=50% colspan=\"2\"><B>".$dat[0][razon_social]."</B></td>";
			$Salida.="<td width=50% align='center' class=\"normal_10N\">FACTURA CAMBIARIA DE COMPRAVENTA</td></tr>";
			$Salida.="<tr><td class=\"normal_10\" colspan=\"2\">".$dat[0][tipoid].": ".$dat[0][id]."</td>";
			$Salida.="<td class=\"normal_10\" align='center'>No. ".$dat[0][prefijo]." ".$dat[0][factura_fiscal]."</td></tr>";
			$Salida.="<tr><td class=\"normal_10\">DIRECCION: ".$dat[0][direccion]."</td>";
			$Salida.="<td class=\"normal_10\"> TELEFONOS: ".$dat[0][telefonos]."</td>";
			$Salida.="<td class=\"normal_10\" align='center'>".$dat[0][municipio]."-".$dat[0][departamento]."</td></tr>";
			$Salida.="<tr><td colspan=\"3\" class=\"normal_10\">".$dat[0][texto1]."</td></tr>";
			$Salida.="<tr><td colspan=\"3\"><br></td></tr>";
			$Salida.="</table>";
			$Salida.="<table border=0 width=101% align='center'>";
			$Salida.="<tr><td class=\"normal_10\" width=35%>CLIENTE: ".$dat[0][nombre_tercero]."</td>";
			$Salida.="<td class=\"normal_10\" width=30% colspan=\"3\">".$dat[0][tipo_id_tercero].": ".$dat[0][tercero_id]."</td></tr>";
			$Salida.="<tr><td class=\"normal_10\" width=30%>PLAN: ".$dat[0][plan_descripcion]."</td>";
			$Salida.="<td class=\"normal_10\" colspan=\"2\" width=40%> DPTO: ".$dat[0][descripcion]."</td></tr>";
			$Salida.="<tr><td class=\"normal_10\">DIRECCION: ".$dat[0][dirter]."</td>";
			$Salida.="<td class=\"normal_10\">TELEFONOS: ".$dat[0][telter]."</td>";
			$Salida.="<td class=\"normal_10\" width=20%>FECHA ELAB.: ".FechaStamp($dat[0][fecha_registro])."</td>";
			$fecha=explode("-",$dat[0][fecha_registro]);
			$nueva = mktime(0,0,0, $fecha[1],$fecha[2],$fecha[0]) + 30 * 24 * 60 * 60;
			$nuevafecha=date("d/m/Y",$nueva);
			$Salida.="<td class=\"normal_10\" width=20%>FECHA VENC.: ".$nuevafecha."</td></tr>";
			/*$Salida.="<tr><td class=\"normal_10\">PACIENTE: ".$dat[0][nombre]."</td>";
			$Salida.="<td class=\"normal_10\">HIS/CLI: ".$dat[X]."</td>";
			$Salida.="<td>FECHA INGR.: ".FechaStamp($dat[0][fecha_registro])."</td><td width=150>FECHA EGRE.: ".$dat[X]."</td></tr>";*/
			$Salida.="<tr><td class=\"normal_10\">&nbsp;</td>";
			$Salida.="<td class=\"normal_10\">&nbsp;</td>";
			$Salida.="<td class=\"normal_10\">FECHA INGR.: ".FechaStamp($dat[0][fecha_registro])."</td>";
			$Salida.="<td class=\"normal_10\">FECHA EGRE.: </td></tr>";
			$Salida.="</table>";
			$Salida.="<br><table border=0 width=100% align='center'>";
			$Salida.="<tr>";
			//$Salida.="<td class=\"normal_10\" width=80% colspan=\"2\">CONCEPTO DE FACTURACION</td>";
			$Salida.="<td class=\"normal_10\" width=80% colspan=\"2\">".$dat[0][texto2]."</td>";
			$Salida.="<td class=\"normal_10\" align='center' width=20%>VALOR</td>";
			$Salida.="</tr>";
			$Salida.="<tr>";
			$Salida.="<td class=\"normal_10\" colspan=\"2\">".$dat[0][concepto]."</td>";
			$Salida.="<td class=\"normal_10\" align='center'>".FormatoValor($dat[0][total_factura])."</td>";
			$Salida.="</tr>";
			$Salida.="<tr>";
			$Salida.="<td class=\"normal_10\" align='right' colspan=\"2\">TOTAL FACTURADO</td>";
			$Salida.="<td class=\"normal_10\" align='center'>".FormatoValor($dat[0][total_factura])."</td>";
			$Salida.="</tr>";
			$Salida.="<tr>";
			$Salida.="<td class=\"normal_10\" align='right' colspan=\"2\">TOTAL IVA</td>";
			$Salida.="<td class=\"normal_10\" align='center'>".FormatoValor($dat[0][gravamen])."</td>";
			$Salida.="</tr>";
			/*$Salida.="<tr>";
			$Salida.="<td class=\"normal_10\" align='right' colspan=\"2\">TOTAL ABONOS(Copagos+Descuentos)</td>";
			$Salida.="<td class=\"normal_10\" align='center'>".FormatoValor($dat[0][valor_cuota_paciente]+$dat[0][descuento])."</td>";
			$Salida.="</tr>";*/
			$Salida.="<tr>";
			$total=FormatoValor($dat[0][total_factura]);
			$totall=str_replace(".","",$total);
			$Salida.="<td class=\"normal_10\" align='left' width=60%>SON :"."  ".convertir_a_letras($totall)."</td>";
			//$Salida.="<td class=\"normal_10\" align='left' width=60%>SON :"."</td>";
			$Salida.="<td class=\"normal_10\" align='right' width=20%>TOTAL A PAGAR</td>";
			$Salida.="<td class=\"normal_10\" align='center'>".FormatoValor($dat[0][total_factura])."</td>";
			$Salida.="</tr>";
			$Salida.="</table>";
			//LAS FIRMAS
			$Salida.="<br><br><table border=0 width=70% align='center'>";
			$Salida.="<tr>";
			$Salida.="<td class=\"normal_10\" width=50% align='center'>______________________________</td>";
			$Salida.="<td class=\"normal_10\" width=50% align='center'>______________________________</td>";
			$Salida.="</tr>";
			$Salida.="<tr>";
			$Salida.="<td class=\"normal_10\" width=50% align='center'>FIRMA CLIENTE</td>";
			$Salida.="<td class=\"normal_10\" width=50% align='center'>ELABORADO POR ".$this->NombreUsuario()."</td>";
			$Salida.="</tr>";
			$Salida.="<TR>";
			$Salida.="<TD class=\"normal_10\" COLSPAN='4' width=50% align='center'>&nbsp;</TD>";
			$Salida.="</TR>";
			$Salida.="<TR>";
			$Salida.="<TD class=\"normal_10\" COLSPAN='4' width=50% align='center'>Esta factura cambiaria de compraventa se asimila para todos sus efectos legales a la Letra de Cambio (articulo 621 - 774 del Codigo de Comercio), el comprador acepta que la firma que aparece como recibido esta avalando la firma del mismo.</TD>";
			$Salida.="</TR>";
			$Salida.="</table>";
			return $Salida;
	}

  /**
  *
  */
  function DatosFactura($prefijo,$factura)
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT f.numerodecuenta, f.prefijo, f.factura_fiscal, z.plan_id,
								b.plan_descripcion, c.nombre_tercero, c.tipo_id_tercero, c.tercero_id,
								i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid, i.id,
								j.departamento, k.municipio, c.direccion as dirter, c.telefono as telter,
								z.total_factura,z.concepto,z.fecha_registro, e.texto1, e.texto2, e.mensaje,
								z.gravamen, z.descuento, z.valor_cuota_paciente
								FROM fac_facturas_cuentas as f, fac_facturas as z, planes as b, terceros as c,
								empresas as i, tipo_dptos as j, tipo_mpios as k,
								fac_tipos_facturas as e
								WHERE f.factura_fiscal=$factura and f.prefijo='$prefijo'
								and z.prefijo=f.prefijo and z.factura_fiscal=f.factura_fiscal
								and z.plan_id=b.plan_id and b.tercero_id=c.tercero_id
								and b.tipo_tercero_id=c.tipo_id_tercero
								and z.prefijo=e.prefijo
								and z.empresa_id=i.empresa_id and i.tipo_pais_id=j.tipo_pais_id
								and i.tipo_dpto_id=j.tipo_dpto_id and i.tipo_pais_id=k.tipo_pais_id
								and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			while(!$result->EOF)
			{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}

			$result->Close();
			return $vars;
  }

	function NombreUsuario()
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT nombre FROM system_usuarios WHERE usuario_id=".UserGetUID()."";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			$result->Close();
			return $result->fields[0];
	}
}

?>
