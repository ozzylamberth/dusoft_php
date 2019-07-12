<?php
	/**
	* $Id: carteraresumen.report.php,v 1.6 2009/06/26 13:53:16 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
 	* @author Hugo F. Manrique
	*/
  IncludeClass("ConexionBD");
	IncludeClass("CarteraResumen","classes","app","Cartera");
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
		
		/**
    * CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
    */
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
		/**
    *
    * @return array
    */
		function GetMembrete()
		{
			$dia = date("d", mktime(0, 0, 0,(intval($this->datos['mes'])+1), 0,$this->datos['anyo']));
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= "<b $estilo>CONCILIACION CUENTAS POR COBRAR A ".$this->meses[$this->datos['mes']]." ".$dia." DE ".$this->datos['anyo']."</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}
		/**
    * FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
    *
    * @return string
    */
		function CrearReporte()
	  {				
			$mes = date("m", mktime(0, 0, 0,(intval($this->datos['mes'])), 0,$this->datos['anyo']));
      $anyo = date("Y", mktime(0, 0, 0,(intval($this->datos['mes'])), 0,$this->datos['anyo']));
			
			$this->datos['empresa_id'] = $_SESSION['cartera']['empresa_id'];
			$retorno = $this->ObtenerReporte($this->datos);
			$nombre = $this->ObtenerUsuarioNombre(UserGetUID());
			
      $ini = $retorno['inicial'];
      $fin = $retorno['final'];
      $saldoInicial = $ini['total_factura']	+ $ini['total_nota_debito'] + $ini['pagares'] 
                      - $ini['total_recibo'] - $ini['total_nota_glosa'] - $ini['retencion']	
                      - $ini['total_nota_ajuste'] - $ini['total_nota_credito'] 
                      -($ini['anticipo'] - $ini['descargo']);

      $debito  = $fin['total_factura'] + $fin['total_nota_debito']+
                 $fin['pagares'] + $fin['descargo'] + $saldoInicial ;
			$credito = $fin['total_nota_glosa'] + $fin['total_nota_ajuste']+ $fin['retencion'] +
                 $fin['total_nota_credito'] + $fin['total_recibo']+
                 $fin['total_nota_anulacion'] + $fin['anticipo'];
			
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			
			$html  = "<center>\n"; 
      $html .= "<table border=\"1\" width=\"60%\" cellpading=\"0\" cellspacing=\"0\" align=\"center\" $estilo>\n";
			$html .= "	<tr class= \"label\">\n";
			$html .= "		<td width=\"55%\" >&nbsp;</td>\n";
			$html .= "		<td align=\"center\">TOTAL</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td >SALDO A ".$this->meses[$mes]." DE ".$anyo." (+)</td>\n";
			$html .= "		<td align=\"right\">".FormatoValor($saldoInicial )."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td >FACTURAS ".$this->meses[$this->datos['mes']]." (+)</td>\n";
			$html .= "		<td align=\"right\">".FormatoValor($fin['total_factura'])."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td >NOTAS DEBITO (+)</td>\n";
			$html .= "		<td align=\"right\">".FormatoValor($fin['total_nota_debito'])."</td>\n";
			$html .= "	</tr>\n";
      $html .= "	<tr>\n";
			$html .= "		<td >PAGARES (+)</td>\n";
			$html .= "		<td align=\"right\">".FormatoValor($fin['pagares'])."</td>\n";
			$html .= "	</tr>\n";
      $html .= "	<tr>\n";
			$html .= "		<td>DESCARGO ANTICIPOS (+)</td>\n";
			$html .= "		<td align=\"right\">".FormatoValor($fin['descargo'])."</td>\n";
			$html .= "	</tr>\n";	
			$html .= "	<tr class=\"label\">\n";
			$html .= "		<td >&nbsp;</td>\n";
			$html .= "		<td align=\"right\">$".FormatoValor($debito)."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td colspan=\"2\">&nbsp;</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td >RETENCION (-)</td>\n";
			$html .= "		<td align=\"right\">".FormatoValor($fin['retencion'])."</td>\n";
			$html .= "	</tr>\n";			
      $html .= "	<tr>\n";
			$html .= "		<td >NOTAS CREDITO (-)</td>\n";
			$html .= "		<td align=\"right\">".FormatoValor($fin['total_nota_glosa'] + $fin['total_nota_ajuste']+ $fin['total_nota_credito'] + $fin['total_nota_anulacion'])."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td >CANCELACIONES (-)</td>\n";
			$html .= "		<td align=\"right\">".FormatoValor($fin['total_recibo'])."</td>\n";
			$html .= "	</tr>\n";
      $html .= "	<tr>\n";
			$html .= "		<td>ANTICIPOS (-)</td>\n";
			$html .= "		<td align=\"right\">".FormatoValor($fin['anticipo'])."</td>\n";
			$html .= "	</tr>\n";			
			$html .= "	<tr class=\"label\">\n";			
      $html .= "		<td>&nbsp;</td>\n";
			$html .= "		<td align=\"right\">$".FormatoValor($credito)."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td colspan=\"2\">&nbsp;</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr class=\"label\" >\n";
			$html .= "		<td >TOTAL GENERAL</td>\n";
			$html .= "		<td align=\"right\">$".FormatoValor($debito - $credito)."</td>\n";
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
      
      $html .= "	<br><table border='0' width=\"100%\">\n";
      $html .= "		<tr>\n";
      $html .= "			<td align=\"justify\" width=\"50%\">\n";
      /*$html .= "				<font size='1' face='arial'>\n";
      $html .= "					Imprimió:&nbsp;".$this->ObtenerUsuarioNombre(UserGetUID())."\n";
      $html .= "				</font>\n";*/
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