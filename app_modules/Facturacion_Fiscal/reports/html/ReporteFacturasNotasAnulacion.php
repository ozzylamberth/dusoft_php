<?php

/**
 * $Id: ReporteFacturasNotasAnulacion.php,v 1.5 2010/12/06 22:13:37 hugo Exp $
 * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 */

class ReporteFacturasNotasAnulacion
{
	var $datos;
	
	function ReporteFacturasNotasAnulacion($datos=array())
	{
		return true;
	}

	function CrearReporte()
	{
			$salida = $this->EncabezadoEmpresa($style);
			$salida .= "<center><font size=\"2\">".$this->datos['titulo']." ".strtoupper(FormatoFecha(1))." , ".date("g:i a")."</font></center>\n";
			$style= "style=\"font-size:14px; font-weight:bold;\"";
			$style1= "style=\"font-size:14px\"";
		
//$salida .= "<table align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
			$salida.= "<table border=\"0\" align=\"center\" width=\"100%\">";
			$salida.= "<tr class='modulo_table_list_title'>";
			$salida.= "<td width=\"100%\" align=\"CENTER\"><font size=\"2\">Facturación - Notas de Anulación.</font>";
			$salida.= "</td>";
			$salida.= "</tr>";
			$salida.= "</table><br>";
			$cont = true;
			$var = $_SESSION[Listado];
			if(is_array($var) AND sizeof($var) > 0)
			{
				$total_factura = $Tabono_efectivo = $Tabono_cheque = $Tabono_tarjetas = $Tabono_chequespf = $Tabono_letras = $valor_total = 0;
				for($i=0; $i<sizeof($var);)
				{
					$k = $i; 
					if($var[RECIBOS])
					$salida.= "<table  border=\"1\" align=\"center\"   width=\"100%\">";
					$salida.= "<tr class=\"$style\" onmouseout=mOut(this,\"".$backgrounds."\"); onmouseover=mOvr(this,'#43b7ba');>";
					$salida.= " <td width=\"100%\" colspan=\"9\" align=\"center\"><b><font size=\"2\">FACTURACIÓN ".$var[$k][sw_clase_factura]."</font></b>";
					$salida.= " </td>";
					$salida.= "</tr>";

					$salida.= "<tr class=\"$style\">";
					$salida.= " <td width=\"5%\" align=\"center\">FACTURA";
					$salida.= " </td>";
					$salida.= " <td width=\"25%\" align=\"center\">CLIENTE";
					$salida.= " </td>";
					$salida.= " <td width=\"20%\" align=\"center\">PACIENTE";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"center\">EFECTIVO";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"center\">CHEQUES";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"center\">TARJETAS";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"center\">CHEQUESPF";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"center\">LETRAS";
					$salida.= " </td>";
					$salida.= " <td width=\"10%\" align=\"center\">VALOR $";
					$salida.= " </td>";
					$salida.= "</tr>";
					
					$total_usuario = $abono_efectivo = $abono_cheque = $abono_tarjetas = $abono_chequespf = $abono_letras = 0;
					//while($var[$i][usuario_id]==$var[$k][usuario_id])
					while($var[$i][sw_clase_factura]==$var[$k][sw_clase_factura])
					{
						$numeracion_inicial = $var[$k][prefijo].$var[$k][factura_fiscal];
						if($var[$k][estado_factura] == 3)
						{
							$abono_efectivo -= $var[$k][abono_efectivo];
							$abono_cheque -= $var[$k][abono_cheque];
							$abono_tarjetas -= $var[$k][abono_tarjetas];
							$abono_chequespf -= $var[$k][abono_chequespf];
							$abono_letras -= $var[$k][abono_letras];
							$valor_total -= $var[$k][valor];
							$total_usuario -= $var[$k][valor];
						}
						else
						{
							$abono_efectivo += $var[$k][abono_efectivo];
							$abono_cheque += $var[$k][abono_cheque];
							$abono_tarjetas += $var[$k][abono_tarjetas];
							$abono_chequespf += $var[$k][abono_chequespf];
							$abono_letras += $var[$k][abono_letras];
							$valor_total += $var[$k][valor];
							$total_usuario += $var[$k][valor];
						}

						$salida.= "<tr class=\"$estilo\">";
						$salida.= " <td width=\"5%\" align=\"center\">".$var[$k][prefijo].' '.$var[$k][factura_fiscal]."";
						$salida.= " </td>";
						$salida.= " <td width=\"25%\" align=\"center\">".$var[$k][cliente]."";
						$salida.= " </td>";
						$salida.= " <td width=\"20%\" align=\"center\">".$var[$k][paciente]."";
						$salida.= " </td>";
						if($var[$k][estado_factura] == 3)
						{
							$salida.= " <td width=\"8%\" align=\"right\"><font color=\"red\">".FormatoValor($var[$k][abono_efectivo])."</font>";
							$salida.= " </td>";
							$salida.= " <td width=\"8%\" align=\"right\"><font color=\"red\">".FormatoValor($var[$k][abono_cheque])."</font>";
							$salida.= " </td>";
							$salida.= " <td width=\"8%\" align=\"right\"><font color=\"red\">".FormatoValor($var[$k][abono_tarjetas])."</font>";
							$salida.= " </td>";
							$salida.= " <td width=\"8%\" align=\"right\"><font color=\"red\">".FormatoValor($var[$k][abono_chequespf])."</font>";
							$salida.= " </td>";
							$salida.= " <td width=\"8%\" align=\"right\"><font color=\"red\">".FormatoValor($var[$k][abono_letras])."</font>";
							$salida.= " </td>";
							$salida.= " <td width=\"10%\" align=\"right\"><font color=\"red\">".FormatoValor($var[$k][valor])."</font>";
							$salida.= " </td>";
						}
						else
						{
							$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($var[$k][abono_efectivo])."";
							$salida.= " </td>";
							$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($var[$k][abono_cheque])."";
							$salida.= " </td>";
							$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($var[$k][abono_tarjetas])."";
							$salida.= " </td>";
							$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($var[$k][abono_chequespf])."";
							$salida.= " </td>";
							$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($var[$k][abono_letras])."";
							$salida.= " </td>";
							$salida.= " <td width=\"10%\" align=\"right\">".FormatoValor($var[$k][valor])."";
							$salida.= " </td>";
							$salida.= "</tr>";
						}

						//NOTAS DE ANULACION
						if($var[$k][estado_factura] == 3)
						{
							$salida.= "<tr class=\"modulo_table_title\">";
							$salida.= " <td width=\"100%\" align=\"left\" colspan=\"9\">NOTA DE ANULACIÓN";
							$salida.= " </td>";
							$salida.= "</tr>";
							$salida.= "<tr class=\"modulo_table_title\">";
							$salida.= " <td width=\"8%\" align=\"center\">Nro. Nota";
							$salida.= " </td>";
							$salida.= " <td width=\"8%\" align=\"center\">Fecha Registro";
							$salida.= " </td>";
							$salida.= " <td width=\"45%\" align=\"center\" colspan=\"3\">Usuario";
							$salida.= " </td>";
							$salida.= " <td width=\"8%\" align=\"center\" colspan=\"4\">Valor";
							$salida.= " </td>";
							$salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"".$backgrounds."\"); onmouseover=mOvr(this,'$cambia');>";
							$salida.= " <td width=\"8%\" align=\"center\"><font color=\"red\">".$var[$k][prefijo_nota].$var[$k][nota_credito_id]."</font>";
							$salida.= " </td>";
							$fecha_nota = explode(' ',$var[$k][fecha_registro_nota]);
							$salida.= " <td width=\"8%\" align=\"center\">".$fecha_nota[0]."";
							$salida.= " </td>";
							$salida.= " <td width=\"45%\" align=\"center\" colspan=\"3\">".$var[$k][usuario_nota]."";
							$salida.= " </td>";
							$salida.= " <td width=\"8%\" align=\"right\" colspan=\"4\"><font color=\"red\">".FormatoValor($var[$k][valor_nota])."</font>";
							$salida.= " </td>";
							$salida.= "</tr>";
							$salida.= "<tr class=\"modulo_list_claro\">";
							$salida.= " <td width=\"100%\" align=\"left\" colspan=\"9\">&nbsp;";
							$salida.= " </td>";
							$salida.= "</tr>";
						}
						//FIN NOTAS ANULACION
						$k++;
					}
					$numeracion_final = $var[$i][prefijo].$var[$i][factura_fiscal];
					$total_factura += $total_usuario;
					if($cont)
					{
						$Tabono_efectivo += $abono_efectivo;
						$Tabono_cheque += $abono_cheque;
						$Tabono_tarjetas += $abono_tarjetas;
						$Tabono_chequespf += $abono_chequespf;
						$Tabono_letras += $abono_letras;
					}
					$salida.= "<tr class=\"$estilo\">";
					$salida.= " <td width=\"100%\" colspan=\"3\" align=\"right\"><b>S. TOTALES ".$var[$i][sw_clase_factura].": </b></td>";
					$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_efectivo)."";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_cheque)."";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_tarjetas)."";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_chequespf)."";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_letras)."";
					$salida.= " </td>";
					$salida.= " <td width=\"100%\" align=\"center\">$&nbsp;<font color=\"red\"><b>".FormatoValor($total_usuario)."</font></b>";
					$salida.= " </td>";
					$salida.= "</tr>";

					$salida.= "<tr class=\"$estilo\">";
					$salida.= " <td width=\"100%\" colspan=\"9\" align=\"left\"><b>NUMERACIÓN ".$var[$i][sw_clase_factura]." DEL ".$numeracion_final." AL ".$numeracion_inicial." </b></td>";
					$salida.= "</tr>";

					$salida.= "<tr>";
					$salida.= " <td colspan=\"9\"width=\"100%\" align=\"center\">&nbsp;";
					$salida.= " </td>";
					$salida.= "</tr>";
					$i = $k;
					$cont = false;
				}
//TOTALES MEDIOS DE PAGO
					$salida.= "<tr class=\"$style\">";
					$salida.= "<td width=\"85%\" align=\"center\" colspan=\"9\">";
					$salida.= "<table  border=\"1\" align=\"center\" width=\"100%\">";
					$salida.= "<tr class=\"$style\">";
					$salida.= " <td width=\"100%\" align=\"center\"colspan=\"6\">TOTALES FACTURACIÓN";
					$salida.= " </td>";
					$salida.= "</tr>";
					$salida.= "<tr class=\"$style\">";
					$salida.= " <td width=\"8%\" align=\"center\">T. EFECTIVO";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"center\">T. CHEQUES";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"center\">T. TARJETAS";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"center\">T. CHEQUESPF";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"center\">T. LETRAS";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"center\">S. EMPRESA + PACIENTE";
					$salida.= " </td>";
					$salida.= "</tr>";
					$salida.= "<tr class=\"$estilo\" onmouseout=mOut(this,\"".$backgrounds."\"); onmouseover=mOvr(this,'$cambia');>";
					$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($Tabono_efectivo)."";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($Tabono_cheque)."";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($Tabono_tarjetas)."";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($Tabono_chequespf)."";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($Tabono_letras)."";
					$salida.= " </td>";
					$salida.= " <td width=\"8%\" align=\"right\">$&nbsp;<font color=\"red\">".FormatoValor($valor_total)."</font>";
					$salida.= " </td>";
					$salida.= "</tr>";
					$salida.= "</table>";
					$salida.= "</td>";
					$salida.= "</tr>";
//FIN TOTALES MEDIOS DE PAGO

				$salida.= "<tr class=\"$style\" onmouseout=mOut(this,\"".$backgrounds."\"); onmouseover=mOvr(this,'#5efb6e');>";
				$salida.= " <td width=\"100%\" colspan=\"8\" align=\"right\"><b>TOTAL FACTURACIÓN: </b>";
				$salida.= " <td width=\"100%\" align=\"center\">$&nbsp;&nbsp;<font color=\"red\"><b>".FormatoValor($total_factura)."</font></b>";
				$salida.= " </td>";
				$salida.= "</tr>";
				$salida.= "</table><br>";
				$salida.= "<table  border=\"0\" class=\"$style\" width=\"100%\" align=\"center\" >\n";
				$datos_usuario = $this->DatosUsuario();
				$salida.= "	<tr class=\"$style\">\n";
				$salida.= "		<td width=\"100%\" class=\"$style\" align=\"right\">Impresión realizada por ".UserGetUID().' '.$datos_usuario[nombre]."</td>\n";
				$salida.= "	</tr>\n";
				$salida.= "	<tr class=\"$style\">\n";
				$salida.= "		<td width=\"100%\" class=\"$style\" align=\"right\">".date('Y-m-d h:m:s')."</td>\n";
				$salida.= "	</tr>\n";
				$salida.= "	<tr class=\"$style\">\n";
				$salida.= "		<td width=\"100%\" class=\"$style\" align=\"right\">".GetIPAddress()."</td>\n";
				$salida.= "	</tr>\n";
				$salida.= "</table>\n";
			}
			else
			{
				$salida.= "<br><center><b>NO HAY MOVIMIENTO</b></center>";
			}
		echo $salida;
	}

  	function EncabezadoEmpresa($style)
  	{
      	$datos=$this->DatosEncabezadoEmpresa($style);
      	$html = "\n";
      	$html .= "	<table  border=\"0\" class=\"$style\" width=\"100%\" align=\"center\" >\n";
      	$html .= " 		<tr class=\"$style\">\n";
      	$html .= " 			<td width=\"100%\" class=\"$style\" align=\"center\"><font size=\"4\"><b>".$datos[razon_social]."</b></font></td>\n";
      	$html .= " 		</tr>\n";
      	$html .= " </table>\n";
				return $html;
  	}

	function DatosEncabezadoEmpresa()
  {
      list($dbconn) = GetDBconn();
      $query = "select *
                from empresas as b
                where  b.empresa_id='".$_SESSION['FACTURACION']['EMPRESA']."'";
      $resulta=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Guardar en la Base de Datos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
      }
      $var=$resulta->GetRowAssoc($ToUpper = false);
      return $var;
  }

	function DatosUsuario()
  {
      list($dbconn) = GetDBconn();
      $query = "SELECT *
                FROM system_usuarios
                WHERE usuario_id = ".UserGetUID()."";
      $resulta=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al seleccionar system_usuarios";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
      }
      $var=$resulta->GetRowAssoc($ToUpper = false);
      return $var;
  }

}

$VISTA = "HTML";
$_ROOT = "../../../../";
include  $_ROOT."classes/rs_server/rs_server.class.php";
include	 $_ROOT."includes/enviroment.inc.php";
$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
IncludeFile($filename);

$rep=new ReporteFacturasNotasAnulacion();
$rep->CrearReporte();

?>
