<?php
	/***
	* $Id: carteravencimientos.report.php,v 1.4 2009/06/26 13:53:16 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
 	* @author Hugo Freddy Manrique
	*/
	IncludeClass('ConexionBD');
	IncludeClass('app_Cartera_Notas','','app','Cartera');
	class carteravencimientos_report
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
	  function carteravencimientos_report($datos=array())
	  {
			$this->datos=$datos;
	    return true;
	  }
		
		function GetMembrete()
		{			
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:13px\"";
			$titulo .= "<b $estilo>REPORTE DE VENCIMIENTOS PARA LA ENTIDAD<br>".$this->datos[0]['tipo_id']." ".$this->datos[0]['tercero_id']." ".$this->datos[0]['nombre']."</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
											  'subtitulo'=>' ','logo'=>'logocliente.png','align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$nts = new app_Cartera_Notas();
			$facturas = $nts->ObtenerReporteVencidos($this->datos[0],$this->datos[0]['empresa']);
			
			$html  = "			<center><b class=\"label\">NO HAY FACTURAS PARA ESTA ENTIDAD</b></center>\n";

			if(sizeof($facturas) > 0)
			{
				$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
				$html  = "	<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\" width=\"100%\" rules=\"all\">\n";
				$html .= "		<tr class=\"label\">\n";
				$html .= "			<td align=\"center\" width=\"%\"><b>FACTURA</b></td>\n";
				$html .= "			<td align=\"center\" width=\"%\"><b>F. REGISTRO</b></td>\n";
				$html .= "			<td align=\"center\" width=\"%\"><b>SALDO</b></td>\n";
				$html .= "			<td align=\"center\" width=\"%\"><b>Nº ENVIO</b></td>\n";
				$html .= "			<td align=\"center\" width=\"%\"><b>F. RADICA</b></td>\n";
				$html .= "		</tr>\n";
				
				foreach($facturas as $key => $detalle)
				{
					$suma += $detalle['saldo'];
					$html .= "		<tr $estilo>\n";
					$html .= "			<td align=\"left\" width=\"%\">".$detalle['prefijo']." ".$detalle['factura_fiscal']."</td>\n";
					$html .= "			<td align=\"center\" width=\"%\">".$detalle['fecha_registro']."</td>\n";
					$html .= "			<td align=\"right\" width=\"%\">".FormatoValor($detalle['saldo'])."</td>\n";
					$html .= "			<td align=\"left\"  width=\"%\">".$detalle['envio_id']."</td>\n";
					$html .= "			<td align=\"center\" width=\"%\">".$detalle['fecha_radicacion']."</td>\n";
					$html .= "		</tr>\n";
				}
				
				$html .= "		<tr class=\"label\">\n";
				$html .= "			<td align=\"center\" colspan=\"2\">TOTAL</td>\n";
				$html .= "			<td align=\"right\" >".FormatoValor($suma)."</td>\n";
				$html .= "			<td colspan=\"2\">&nbsp;</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
        $usuario = $nts->ObtenerUsuarioNombre(UserGetUID());
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
			}
	    return $html;
		}
	}
?>