<?php
	/**
	* $Id: notascredito.report.php,v 1.1 2010/03/09 13:40:54 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass("NotasFacturas","classes","app","NotasFacturasContado");
	class notascredito_report 
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
	  function notascredito_report($datos=array())
	  {
			$this->datos=$datos;			
	    return true;
	  }
		/**
    *
    */
		function GetMembrete()
		{
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:10pt\"";
			$titulo .= "<b $est >NOTA ".strtoupper($this->datos['tabla'])."<br>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$nc = new NotasFacturas();
			
			$nota = $nc->ObtenerNota($this->datos);
			$detl = $nc->ObtenerConceptosNota($this->datos);
			
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			
			$html .= "<table border=\"0\" width=\"100%\">\n";
			$html .= "	<tr height=\"21\">\n";
			$html .= "		<td class=\"label\" width=\"12%\">Nota ".ucfirst($this->datos['tabla']).":</td>\n";			
			$html .= "		<td class=\"label\" style=\"text-indent:10pt;text-align:left\">".$this->datos['prefijo']." ".$this->datos['numero']."</td>\n";			
			$html .= "	</tr>\n";
			$html .= "	<tr height=\"21\">\n";
			$html .= "		<td class=\"label\" width=\"12%\">Fecha</td>\n";			
			$html .= "		<td class=\"label\" style=\"text-indent:10pt;text-align:left\">".$nota['fecha_registro']."</td>\n";			
			$html .= "	</tr>\n";
			$html .= "	<tr height=\"21\">\n";
			$html .= "		<td class=\"label\" width=\"16%\">Tercero:</td>\n";			
			$html .= "		<td class=\"normal_10\" style=\"text-indent:10pt;text-align:left\">".$nota['tipo_id_tercero']." ".$nota['tercero_id']." - ".$nota['nombre_tercero']."</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<table border=\"0\" width=\"100%\">\n";
			$html .= "	<tr height=\"21\">\n";
			$html .= "		<td class=\"label\" width=\"8%\">Factura Nº:</td>\n";			
			$html .= "		<td class=\"normal_10\"  width=\"8%\">".$nota['prefijo_factura']." ".$nota['factura_fiscal']."</td>\n";
			$html .= "		<td class=\"label\" width=\"16%\" style=\"text-indent:10pt\">Fecha Factura:</td>\n";			
			$html .= "		<td class=\"normal_10\" style=\"text-indent:10pt\">".$nota['fecha_factura']."</td>\n";
			$html .= "	</tr>\n";	
			$html .= "</table>\n";	
			$html .= "<table border=\"0\" width=\"100%\">\n";			
			$html .= "	<tr height=\"21\">\n";
			$html .= "		<td colspan=\"2\" class=\"label\" width=\"16%\" >Total Factura:</td>\n";			
			$html .= "		<td colspan=\"2\" class=\"label\" width=\"8%\" style=\"text-indent:10pt\" align=\"right\">$".formatoValor($nota['total_factura'])."</td>\n";
			$html .= "		<td></td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr height=\"21\">\n";
			$html .= "		<td colspan=\"2\" class=\"label\">Valor Nota ".ucfirst($this->datos['tabla']).":</td>\n";			
			$html .= "		<td colspan=\"2\" class=\"label\" style=\"text-indent:10pt\" align=\"right\">$".formatoValor($nota['valor_nota'])."</td>\n";
			$html .= "		<td></td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr height=\"21\">\n";
			$html .= "		<td colspan=\"2\" class=\"label\">Saldo Factura:</td>\n";			
			$html .= "		<td colspan=\"2\" class=\"label\" style=\"text-indent:10pt\" align=\"right\">$".formatoValor($nota['saldo'])."</td>\n";
			$html .= "		<td></td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";	
			$html .= "<table border=\"0\" width=\"100%\">\n";			
			if($nota['auditor'])
			{
				$html .= "	<tr height=\"21\">\n";
				$html .= "		<td colspan=\"2\" class=\"label\" width=\"16%\">Auditor(a):</td>\n";			
				$html .= "		<td colspan=\"2\" class=\"label\" style=\"text-indent:10pt\">".$nota['auditor']."</td>\n";
				$html .= "	</tr>\n";
			}
			
			if($nota['observacion'])
			{
				$html .= "	<tr height=\"21\">\n";
				$html .= "		<td colspan=\"2\" class=\"label\" width=\"16%\">Observación:</td>\n";
				$html .= "		<td colspan=\"2\" class=\"label\" style=\"text-indent:10pt;text-align:justify\">".$nota['observacion']."</b></td>\n";				
				$html .= "	</tr>\n";
			}
			
			$html .= "</table>\n";
			$html .= "	<br>\n";
			$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
			$html .= "		<tr class=\"label\">\n";
			$html .= "			<td align=\"center\" width=\"45%\"><b>CONCEPTO</b></td>\n";
			$html .= "			<td align=\"center\" width=\"%\"><b>DEPARTAMENTO / TERCERO</b></td>\n";
			$html .= "			<td align=\"center\" width=\"8%\"><b>VALOR</b></td>\n";
			$html .= "		</tr>\n";
			
			foreach($detl as $key => $Concep)
			{
				$html .= "		<tr class=\"normal_10\">\n";
				$html .= "			<td >".$Concep['descripcion']."</td>\n";
				$html .= "			<td >".$Concep['departamento']." ".(($Concept['nombre_tercero'])? "/".$Concept['nombre_tercero']:"")."</td>\n";
				$html .= "			<td align=\"right\">$".formatoValor($Concep['valor'])."</td>\n";
				$html .= "		</tr>\n";
			}			
			$html .= "	</table><br><br><br>\n";
			
			$html .= "	<table style=\"border-top:1px solid #000000\" width=\"30%\">\n";		
			$html .= "		<tr class=\"label\">";
			$html .= "			<td>".$nota['nombre']."</td>\n";
			$html .= "		</tr>";
			$html .= "	</table>";
			
			$usuario = $nc->ObtenerInformacionUsuario($this->datos['usuario_id']);
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