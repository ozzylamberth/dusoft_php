<?php
	/***
	* $Id: carterafacturado.report.php,v 1.2 2009/06/26 13:53:16 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
 	* @author Hugo Freddy Manrique
	*/
	IncludeClass('ConexionBD');
	IncludeClass('app_Cartera_Notas','','app','Cartera');
	class carterafacturado_report 
	{ 
		//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
		var $datos;
		var $meses = array();
		
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
	  function carterafacturado_report($datos=array())
	  {
			$this->datos=$datos;

			$this->meses['01'] = "ENERO";
			$this->meses['02'] = "FEBRERO";
			$this->meses['03'] = "MARZO";
			$this->meses['04'] = "ABRIL";
			$this->meses['05'] = "MAYO";
			$this->meses['06'] = "JUNIO";
			$this->meses['07'] = "JULIO";
			$this->meses['08'] = "AGOSTO";
			$this->meses['09'] = "SEPTIEMBRE";
			$this->meses['10'] = "OCTUBRE";
			$this->meses['11'] = "NOVIEMBRE";
			$this->meses['12'] = "DICIEMBRE";

	    return true;
	  }
		
		function GetMembrete()
		{
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo = "<b>REPORTE FACTURAS</b>";
			$subtit = "<b>".$this->meses[$this->datos['mes']]." ".$this->datos['anyo']." ";
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>$subtit,
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

			$cr = new app_Cartera_Notas();
			$facturas = $cr->ObtenerFacturas($this->datos,$this->datos['empresa']);
			
			$html .= "<table border=\"1\" width=\"100%\" align=\"center\" cellpading= \"0\" cellspacing=\"0\" rules=\"all\" $estilo>\n";
			$html .= "	<tr $estilo3 align=\"center\">\n";
			$html .= "		<td width=\"20%\"><b>Nº FACTURA</b></td>\n";
			$html .= "		<td width=\"20%\"><b>FECHA</b></td>\n";
			$html .= "		<td width=\"20%\"><b>TOTAL</b></td>\n";
			$html .= "		<td width=\"20%\"><b>V. RTF.</b></td>\n";
			$html .= "		<td width=\"20%\"><b>SALDO</b></td>\n";
			$html .= "	</tr>\n";
			
			$total = $rtf = 0;
			
			foreach($facturas as $key => $detalle )
			{
				$total += $detalle['total_factura'];
				$rtf += $detalle['retencion_fuente'];
				
				$html .= "	<tr $estilo>\n";
				$html .= "		<td align=\"left\"  >".$detalle['prefijo']." ".$detalle['factura_fiscal']."</td>\n";
				$html .= "		<td align=\"center\">".$detalle['fecha_registro']."</td>\n";
				$html .= "		<td align=\"right\" >$".FormatoValor($detalle['total_factura'])."</td>\n";
				$html .= "		<td align=\"right\" >$".FormatoValor($detalle['retencion_fuente'])."</td>\n";
				$html .= "		<td align=\"right\" >$".FormatoValor($detalle['total_factura'] - $detalle['retencion_fuente'])."</td>\n";
				$html .= "	</tr>\n";
			}
			
			$html .= "	<tr $estilo2 class=\"label\">\n";
			$html .= "		<td align=\"left\" colspan=\"2\"><b>TOTAL</b></td>\n";
			$html .= "		<td align=\"right\">$".FormatoValor($total)."</td>\n";
			$html .= "		<td align=\"right\">$".FormatoValor($rtf)."</td>\n";
			$html .= "		<td align=\"right\">$".FormatoValor($total - $rtf)."</td>\n";
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