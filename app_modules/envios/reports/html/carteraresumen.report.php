<?php

	/**************************************************************************************
	* $Id: carteraresumen.report.php,v 1.3 2007/08/09 19:44:11 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/
	include_once "./app_modules/Cartera/classes/CarteraResumen.class.php";
	class carteraresumen_report extends CarteraResumen
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
	  function carteraresumen_report($datos=array())
	  {
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
			
			$this->datos=$datos;
	    return true;
	  }
		
		function GetMembrete()
		{
			$dia = date("d", mktime(0, 0, 0,(intval($this->datos['mes'])+1), 0,date("Y")));
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= "<b $estilo>CONCILIACION CUENTAS POR COBRAR A ".$this->meses[$this->datos['mes']]." ".$dia." DE ".date("Y")."</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}
		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {				
			$mes = date("m", mktime(0, 0, 0,(intval($this->datos['mes'])), 0,date("Y")));
			
			$this->datos['empresa_id'] = $_SESSION['cartera']['empresa_id'];
			$datos = $this->ObtenerReporte($this->datos);
	
			$nombre = $this->ObtenerUsuarioNombre(UserGetUID());
			$saldoinicial = $datos['inicial']['factura'] + $datos['inicial']['debito'] -$datos['inicial']['credito']-$datos['inicial']['ajuste']-$datos['inicial']['glosas']-$datos['inicial']['recibo']-$datos['inicial']['anulacion'];
			$subtotalpositivo = $datos['final']['factura']+$datos['final']['debito']+$saldoinicial;
			$subtotalnegativo = $datos['final']['glosas']+$datos['final']['ajuste']+$datos['final']['credito']+$datos['final']['recibo']+$datos['final']['anulacion']+$datos['final']['anticipo'];
			
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			
			$html  = "<center>\n"; 
			$html .= "<table border=\"1\" width=\"60%\" cellpading=\"0\" cellspacing=\"0\" align=\"center\" $estilo>\n";
			$html .= "	<tr class= \"label\">\n";
			$html .= "		<td width=\"55%\">&nbsp;</td>\n";
			$html .= "		<td align=\"center\">TOTAL</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td width=\"55%\">SALDO A ".$this->meses[$mes]." DE ".date("Y")." (+)</td>\n";
			$html .= "		<td align=\"right\">".FormatoValor($saldoinicial )."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td>FACTURAS ".$this->meses[$this->datos['mes']]." (+)</td>\n";
			$html .= "		<td align=\"right\">".FormatoValor($datos['final']['factura'])."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td>NOTAS DEBITO (+)</td>\n";
			$html .= "		<td align=\"right\">".FormatoValor($datos['final']['debito'])."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"label\">\n";
			$html .= "		<td>&nbsp;</td>\n";
			$html .= "		<td align=\"right\">$".FormatoValor($subtotalpositivo)."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td colspan=\"2\">&nbsp;</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td>NOTAS CREDITO (-)</td>\n";
			$html .= "		<td align=\"right\">".FormatoValor($datos['final']['glosas']+$datos['final']['ajuste']+$datos['final']['credito']+$datos['final']['anulacion'])."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td>CANCELACIONES (-)</td>\n";
			$html .= "		<td align=\"right\">".FormatoValor($datos['final']['recibo'])."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td>ANTICIPOS (-)</td>\n";
			$html .= "		<td align=\"right\">".FormatoValor($datos['final']['anticipo'])."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"label\">\n";
			$html .= "		<td>&nbsp;</td>\n";
			$html .= "		<td align=\"right\">$".FormatoValor($subtotalnegativo)."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td colspan=\"2\">&nbsp;</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"label\" >\n";
			$html .= "		<td align=\"center\">TOTAL GENERAL</td>\n";
			$html .= "		<td align=\"right\">$".FormatoValor($subtotalpositivo-$subtotalnegativo)."</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table><br>\n";
			$html .= "<table width=\"60%\" align=\"center\" class=\"label\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td>ELABORADO POR</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td>&nbsp;</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<label style=\"text-decoration :overline\">".$nombre."\n";
			for($i = strlen($nombre); $i<60 ; $i++)
				$html .= "&nbsp;";
				
			$html .= "			</label>\n";
			$html .= "			</td>\n";
			$html .= "	</tr>\n";
			$html .= "<table>\n";
			$html .= "</center>\n";
	    return $html;
		}
	}

?>
