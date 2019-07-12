<?php

	/**************************************************************************************
	* $Id: reciboscaja.report.php,v 1.4 2007/08/09 19:44:11 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/
	IncludeClass('CarteraRecibos','','app','Cartera');
	class reciboscaja_report 
	{ 
		//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
		var $datos;
		
		//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
		//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
		var $title       = '';
		var $author      = '';
		var $sizepage    = 'leter';
		var $Orientation = '';
		var $grayScale   = false;
		var $headers     = array();
		var $footers     = array();
		
		//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	  function reciboscaja_report($datos=array())
	  {
			$this->datos=$datos;
	    return true;
	  }
		
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= "<b>RECIBOS DE CAJA <br> DE ".$this->datos['fecha_inicio']." A ".$this->datos['fecha_fin']."</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$estilo2 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px; text-indent:6pt\""; 
			$estilo3 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px; text-align:center\""; 

			$cr = new CarteraRecibos();
			$recibos = $cr->ObtenerRecibosCaja($this->datos['empresa_id'],$this->datos,$contar = "0");
			
			$html .= "<table border=\"1\" width=\"100%\" align=\"center\" cellpading= \"0\" cellspacing=\"0\" $estilo>\n";
			$html .= "	<tr $estilo3 align=\"center\">\n";
			$html .= "		<td width=\"8%\" ><b>Nº DOC</b></td>\n";
			$html .= "		<td width=\"8%\" ><b>FECHA</b></td>\n";
			$html .= "		<td width=\"8%\"><b>V. RECIBO</b></td>\n";
			$html .= "		<td width=\"8%\"><b>V. FACTURAS</b></td>\n";
			$html .= "		<td width=\"8%\"><b>CREDITOS</b></td>\n";
			$html .= "		<td width=\"8%\"><b>DEBITOS</b></td>\n";
			$html .= "		<td width=\"17%\"><b>FORMA PAGO</b></td>\n";
			$html .= "		<td width=\"17%\" ><b>TERCERO</b></td>\n";
			$html .= "		<td width=\"18%\" ><b>RESPONSABLE</b></td>\n";
			$html .= "	</tr>\n";
			
			$total = $totalI = $totalII = 0;
			
			foreach($recibos as $key => $detalle )
			{
				$total += $detalle['total_abono'];
				$totalI += $detalle['valor_final'];
				$totalII += $detalle['valor_credito'];
				$totalIV += $detalle['valor_facturas'];
				$html .= "	<tr $estilo>\n";
				$html .= "		<td align=\"left\"  >".$detalle['prefijo']." ".$detalle['recibo_caja']."</td>\n";
				$html .= "		<td align=\"center\">".$detalle['fecha_ingcaja']."</td>\n";
				$html .= "		<td align=\"right\" >$".FormatoValor($detalle['total_abono'])."</td>\n";
				$html .= "		<td align=\"right\" >$".FormatoValor($detalle['valor_facturas'])."</td>\n";
				$html .= "		<td align=\"right\" >$".FormatoValor($detalle['valor_credito'])."</td>\n";
				$html .= "		<td align=\"right\" >$".FormatoValor($detalle['valor_final'])."</td>\n";
				$html .= "		<td align=\"justify\"><menu><b class= \"label_mark\">".$detalle['forma_pago']."</b></menu></td>\n";
				$html .= "		<td align=\"justify\">".$detalle['nombre_tercero']."</td>\n";
				$html .= "		<td align=\"justify\">".$detalle['nombre']."</td>\n";
				$html .= "	</tr>\n";
			}
			
			$html .= "	<tr $estilo2 class=\"label\">\n";
			$html .= "		<td align=\"left\" colspan=\"2\"><b>TOTAL</b></td>\n";
			$html .= "		<td align=\"right\">$".FormatoValor($total)."</td>\n";
			$html .= "		<td align=\"right\">$".FormatoValor($totalIV)."</td>\n";
			$html .= "		<td align=\"right\">$".FormatoValor($totalII)."</td>\n";
			$html .= "		<td align=\"right\">$".FormatoValor($totalI)."</td>\n";
			$html .= "		<td colspan=\"3\">&nbsp;</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table><br>\n";
			
			$usuario = $cr->ObtenerUsuarioNombre(UserGetUID());
			$html .= "	<br><table border='0' width=\"100%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"justify\" width=\"50%\">\n";
			$html .= "				<font size='1' face='arial'>\n";
			$html .= "					Imprimió:&nbsp;".$usuario['nombre']."\n";
			$html .= "				</font>\n";
			$html .= "			</td>\n";
			$html .= "			<td align=\"right\" width=\"50%\">\n";
			$html .= "				<font size='1' face='arial'>\n";
			$html .= "					Fecha Impresión :&nbsp;&nbsp;".date("d/m/Y - h:i a")."\n";
			$html .= "				</font>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			
			return $html;
		}
	}

?>
