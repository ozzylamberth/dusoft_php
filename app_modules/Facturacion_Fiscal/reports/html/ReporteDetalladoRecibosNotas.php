<?php

/**
 * $Id: ReporteDetalladoRecibosNotas.php,v 1.1.1.1 2009/09/11 20:36:46 hugo Exp $
 * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 */

class ReporteDetalladoRecibosNotas
{
	var $datos;
	
	function ReporteDetalladoRecibosNotas($datos=array())
	{
		return true;
	}

	function CrearReporte()
	{
			$salida = $this->EncabezadoEmpresa();
			$salida .= "<center><font size=\"2\">".$this->datos['titulo']." ".strtoupper(FormatoFecha(1))." , ".date("g:i a")."</font></center>\n";
			$style= "style=\"font-size:14px; font-weight:bold;\"";
			$style1= "style=\"font-size:14px\"";
		
			$salida.= "<table border=\"0\" align=\"center\"   width=\"75%\">";
			$salida.= "<tr class='modulo_table_list_title'>";
			$salida.= "<td width=\"100%\" align=\"CENTER\">DETALLADO RECIBOS - NOTAS DE CAJA";
			$salida.= "</td>";
			$salida.= "</tr>";
			$salida.= "</table><br>";
			$dat = $_SESSION[Listado];

				if(is_array($dat[RECIBOS]) AND sizeof($dat[RECIBOS]) > 0)
				{
						$total_factura = $Tabono_efectivo = $Tabono_cheque = $Tabono_tarjetas = $Tabono_chequespf = $Tabono_letras = $valor_total = 0;

						$salida.= "<table  border=\"1\" align=\"center\" width=\"85%\">";
						$salida.= "<tr >";
						$salida.= " <td width=\"100%\" colspan=\"6\" align=\"center\"><b><font size=\"2\">RECIBOS DE CAJA</font></b>";
						$salida.= " </td>";
						$salida.= "</tr>";

						$salida.= "<tr  class=\"$style\">";
						$salida.= " <td width=\"5%\" align=\"center\">RECIBO";
						$salida.= " </td>";
						$salida.= " <td width=\"25%\" align=\"center\">CLIENTE";
						$salida.= " </td>";
						$salida.= " <td width=\"8%\" align=\"center\">V. EFECTIVO";
						$salida.= " </td>";
						$salida.= " <td width=\"8%\" align=\"center\">V. CHEQUES";
						$salida.= " </td>";
						$salida.= " <td width=\"8%\" align=\"center\">V. TARJETAS";
						$salida.= " </td>";
						$salida.= " <td width=\"10%\" align=\"center\">V. TOTAL";
						$salida.= " </td>";
						$salida.= "</tr>";

						$total_usuario = $abono_efectivo = $abono_cheque = $abono_tarjetas = $abono_chequespf = $abono_letras = $valor_total = 0;
						$var = $dat[RECIBOS];
						for($i=0; $i<sizeof($var); $i++)
						{
							
								//$numeracion_inicial = $var[$k][prefijo].$var[$k][recibo_caja];
								$abono_efectivo += $var[$i][total_efectivo];
								$abono_cheque += $var[$i][total_cheques];
								$abono_tarjetas += $var[$i][total_tarjetas];
/*								$abono_chequespf += $var[$k][abono_chequespf];
								$abono_letras += $var[$k][abono_letras];*/
								$valor_total += $var[$i][total_abono];
								$total_usuario += $var[$i][total_abono];
								$salida.= "<tr>";
								$salida.= " <td width=\"5%\" align=\"center\">".$var[$i][prefijo].' '.$var[$i][recibo_caja]."";
								$salida.= " </td>";
								$salida.= " <td width=\"55%\" align=\"center\">".$var[$i][nombre_tercero]."";
								$salida.= " </td>";
/*								$salida.= " <td width=\"20%\" align=\"center\">".$var[$k][paciente]."";
								$salida.= " </td>";*/
								$salida.= " <td width=\"10%\" align=\"right\">".FormatoValor($var[$i][total_efectivo])."";
								$salida.= " </td>";
								$salida.= " <td width=\"10%\" align=\"right\">".FormatoValor($var[$i][total_cheques])."";
								$salida.= " </td>";
								$salida.= " <td width=\"10%\" align=\"right\">".FormatoValor($var[$i][total_tarjetas])."";
								$salida.= " </td>";
/*								$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($var[$k][abono_chequespf])."";
								$salida.= " </td>";
								$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($var[$k][abono_letras])."";
								$salida.= " </td>";*/
								$salida.= " <td width=\"10%\" align=\"right\">".FormatoValor($var[$i][total_abono])."";
								$salida.= " </td>";
								$salida.= "</tr>";
						}
							$total_factura += $total_usuario;
							$Tabono_efectivo += $abono_efectivo;
							$Tabono_cheque += $abono_cheque;
							$Tabono_tarjetas += $abono_tarjetas;
	/*						$Tabono_chequespf += $abono_chequespf;
							$Tabono_letras += $abono_letras;*/
						$salida.= "<tr>";
						$salida.= " <td width=\"100%\" colspan=\"2\" align=\"right\"><b>SUBTOTALES:</b></td>";
						$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_efectivo)."";
						$salida.= " </td>";
						$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_cheque)."";
						$salida.= " </td>";
						$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_tarjetas)."";
						$salida.= " </td>";
/*						$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_chequespf)."";
						$salida.= " </td>";
						$salida.= " <td width=\"8%\" align=\"right\">".FormatoValor($abono_letras)."";
						$salida.= " </td>";*/
						$salida.= " <td width=\"100%\" align=\"center\">$&nbsp;<font color=\"red\"><b>".FormatoValor($total_usuario)."</font></b>";
						$salida.= " </td>";
						$salida.= "</tr>";

						$salida.= "<tr>";
						$salida.= " <td colspan=\"6\"width=\"100%\" align=\"center\">&nbsp;";
						$salida.= " </td>";
						$salida.= "</tr>";
						$salida.= "</table>";
				}
				else
				{
					$salida .= "<br><center><b>NO HAY MOVIMIENTO DE RECIBOS</b></center>";
				}
				
			//DEVOLUCIONES
				if(is_array($dat[DEVOLUCIONES]) AND sizeof($dat[DEVOLUCIONES]) > 0)
				{
						$salida.= "<table  border=\"1\" align=\"center\" width=\"55%\">";
						$salida.= "<tr>";
						$salida.= " <td width=\"100%\" colspan=\"3\" align=\"center\"><b><font size=\"2\">RECIBOS DE DEVOLUCIONES</font></b>";
						$salida.= " </td>";
						$salida.= "</tr>";

						$salida.= "<tr class=\"$style\">";
						$salida.= " <td width=\"15%\" align=\"center\">RECIBO DV. Nro";
						$salida.= " </td>";
						$salida.= " <td width=\"75%\" align=\"center\">CLIENTE";
						$salida.= " </td>";
						$salida.= " <td width=\"10%\" align=\"center\">V. DEVOLUCIÓN";
						$salida.= " </td>";
						$salida.= "</tr>";

						$total_usuario = $total_devolucion = 0;
						$var = $dat[DEVOLUCIONES];
						for($i=0; $i<sizeof($var); $i++)
						{
							
								$total_devolucion += $var[$i][total_devolucion];
								$total_usuario += $var[$i][total_devolucion];
								$valor_total -= $var[$i][total_devolucion];
								$salida.= "<tr>";
								$salida.= " <td width=\"15%\" align=\"center\">".$var[$i][prefijo].' '.$var[$i][recibo_caja]."";
								$salida.= " </td>";
								$salida.= " <td width=\"75%\" align=\"center\">".$var[$i][nombre_tercero]."";
								$salida.= " </td>";
								$salida.= " <td width=\"10%\" align=\"right\">".FormatoValor($var[$i][total_devolucion])."";
								$salida.= " </td>";
								$salida.= "</tr>";
						}
						$total_factura -= $total_usuario;
						$Tdevoluciones -= $total_devolucion;

						$salida.= "<tr>";
						$salida.= " <td width=\"90%\" align=\"right\" colspan=\"2\"><b>SUBTOTALES:</b></td>";
						$salida.= " <td width=\"10%\" align=\"center\">$&nbsp;<font color=\"red\" size=\"3\"><b>-".FormatoValor($total_usuario)."</font></b>";
						$salida.= " </td>";
						$salida.= "</tr>";


						$salida.= "<tr>";
						$salida.= " <td colspan=\"3\"width=\"100%\" align=\"center\">&nbsp;";
						$salida.= " </td>";
						$salida.= "</tr>";
						$salida.= "</table>";

				}
				else
				{
					$salida .= "<br><center><b>NO HAY MOVIMIENTO DE RECIBOS DEVOLUCIONES</b></center>";
				}
			//FIN DEVOLUCIONES

			//PAGARES
				if(is_array($dat[PAGARES]) AND sizeof($dat[PAGARES]) > 0)
				{
						$salida.= "<table  border=\"1\" align=\"center\" width=\"55%\">";
						$salida.= "<tr>";
						$salida.= " <td width=\"100%\" colspan=\"4\" align=\"center\"><b><font size=\"2\">PAGARES</font></b>";
						$salida.= " </td>";
						$salida.= "</tr>";

						$salida.= "<tr class=\"$style\">";
						$salida.= " <td width=\"15%\" align=\"center\">PAGARE Nro";
						$salida.= " </td>";
						$salida.= " <td width=\"65%\" align=\"center\">CLIENTE";
						$salida.= " </td>";
						$salida.= " <td width=\"10%\" align=\"center\">VALOR";
						$salida.= " </td>";
						$salida.= " <td width=\"10%\" align=\"center\">Observ.";
						$salida.= " </td>";
						$salida.= "</tr>";

						$total_usuario = $total_devolucion = 0;
						$var = $dat[PAGARES];
						for($i=0; $i<sizeof($var); $i++)
						{
							
								$total_pagare += $var[$i][valor];
								$total_usuario += $var[$i][valor];
								$valor_total += $var[$i][valor];
								$salida.= "<tr>";
								$salida.= " <td width=\"15%\" align=\"center\">".$var[$i][prefijo].' '.$var[$i][numero]."";
								$salida.= " </td>";
								$salida.= " <td width=\"65%\" align=\"center\">".$var[$i][nombre_tercero]."";
								$salida.= " </td>";
								$salida.= " <td width=\"10%\" align=\"right\">".FormatoValor($var[$i][valor])."";
								$salida.= " </td>";
								$salida.= " <td width=\"10%\" align=\"center\">";
								$salida .= "	<img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\" title=\"".$var[$i][observacion]."\">\n";
								$salida.= " </td>";
								$salida.= "</tr>";
						}
						$total_factura += $total_usuario;
						 $Tpagares+= $total_pagare;
						$salida.= "<tr class=\"$estilo\">";
						$salida.= " <td width=\"90%\" align=\"right\" colspan=\"2\"><b>SUBTOTALES:</b></td>";
						$salida.= " <td width=\"10%\" align=\"center\">$&nbsp;<font color=\"red\" size=\"2\"><b>".FormatoValor($total_usuario)."</font></b>";
						$salida.= " <td width=\"10%\" align=\"center\">&nbsp;";
						$salida.= " </td>";
						$salida.= "</tr>";

						$salida.= "<tr>";
						$salida.= " <td colspan=\"4\"width=\"100%\" align=\"center\">&nbsp;";
						$salida.= " </td>";
						$salida.= "</tr>";
						$salida.= "</table>";

				}
				else
				{
					$salida .= "<br><center><b>NO HAY MOVIMIENTO DE PAGARES</b></center>";
				}
			//FIN PAGARES

//TOTALES MEDIOS DE PAGO
				$salida.= "<table  border=\"1\" align=\"center\" width=\"70%\">";
				$salida.= "<tr  class=\"$style\">";
				$salida.= " <td width=\"40%\" align=\"right\">TOTALES";
				$salida.= " </td>";
				$salida.= " <td width=\"10%\" align=\"center\">T. EFECTIVO";
				$salida.= " </td>";
				$salida.= " <td width=\"10%\" align=\"center\">T. CHEQUES";
				$salida.= " </td>";
				$salida.= " <td width=\"10%\" align=\"center\">T. TARJETAS";
				$salida.= " </td>";
				$salida.= " <td width=\"10%\" align=\"center\">T. DEVOLUCIONES";
				$salida.= " </td>";
				$salida.= " <td width=\"10%\" align=\"center\">T. PAGARES";
				$salida.= " </td>";
				$salida.= " <td width=\"10%\" align=\"center\">TOTALES";
				$salida.= " </td>";
				$salida.= "</tr>";
				$salida.= "<tr>";
				$salida.= " <td width=\"40%\" align=\"right\">&nbsp;";
				$salida.= " </td>";
				$salida.= " <td width=\"10%\" align=\"right\">".FormatoValor($Tabono_efectivo)."";
				$salida.= " </td>";
				$salida.= " <td width=\"10%\" align=\"right\">".FormatoValor($Tabono_cheque)."";
				$salida.= " </td>";
				$salida.= " <td width=\"10%\" align=\"right\">".FormatoValor($Tabono_tarjetas)."";
				$salida.= " </td>";
				$salida.= " <td width=\"10%\" align=\"right\"><font color=\"red\"><b>".FormatoValor($Tdevoluciones)."</b></font>";
				$salida.= " </td>";
				$salida.= " <td width=\"10%\" align=\"right\">".FormatoValor($Tpagares)."";
				$salida.= " </td>";
				$salida.= " <td width=\"10%\" align=\"right\">".FormatoValor($valor_total)."";
				$salida.= " </td>";
				$salida.= "</tr>";
				$salida.= "<tr>";
				$salida.= " <td width=\"100%\" colspan=\"6\" align=\"right\"><b>TOTAL FACTURACIÓN: </b>";
				$salida.= " <td width=\"100%\" align=\"center\">$&nbsp;&nbsp;<font color=\"red\" size=\"2\"><b>".FormatoValor($total_factura)."</font></b>";
				$salida.= " </td>";
				$salida.= "</tr>";
//FIN TOTALES MEDIOS DE PAGO
				$salida.= "</table>";
				$salida.= "<br><table  border=\"0\" class=\"$style\" width=\"100%\" align=\"center\" >\n";
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
		echo $salida;
	}

  	function EncabezadoEmpresa()
  	{
      	$datos=$this->DatosEncabezadoEmpresa();
      	$html = "	<table  border=\"0\" width=\"100%\" align=\"center\" >\n";
      	$html .= " 		<tr>\n";
      	$html .= " 			<td width=\"100%\" align=\"center\"><font size=\"4\"><b>".$datos[razon_social]."</b></font></td>\n";
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

$rep=new ReporteDetalladoRecibosNotas();
$rep->CrearReporte();

?>
