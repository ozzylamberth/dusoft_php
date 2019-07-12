<?php
	/**************************************************************************************
	* $Id: carteravencimientos.report.php,v 1.1 2007/05/15 19:06:32 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/
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
			$titulo .= "<b $estilo>REPORTE DE VENCIMIENTOS PARA LA ENTIDAD<br>".$this->datos[0]['tipo_id']."".$this->datos[0]['tercero_id']." ".$this->datos[0]['nombre']."</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
											  'subtitulo'=>' ','logo'=>'logocliente.png','align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$nts = new app_Cartera_Notas();
			$facturas = $nts->ObtenerReporteVencidos($this->datos[0],$this->datos[0]['empresa']);
			
			if(sizeof($facturas) > 0)
			{
				$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
				$Salida .= "	<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\" width=\"100%\" rules=\"all\">\n";
				$Salida .= "		<tr class=\"label\">\n";
				$Salida .= "			<td align=\"center\" width=\"%\"><b>FACTURA</b></td>\n";
				$Salida .= "			<td align=\"center\" width=\"%\"><b>F. REGISTRO</b></td>\n";
				$Salida .= "			<td align=\"center\" width=\"%\"><b>SALDO</b></td>\n";
				$Salida .= "			<td align=\"center\" width=\"%\"><b>Nº ENVIO</b></td>\n";
				$Salida .= "			<td align=\"center\" width=\"%\"><b>F. RADICA</b></td>\n";
				$Salida .= "		</tr>\n";
				
				foreach($facturas as $key => $detalle)
				{
					$suma += $detalle['saldo'];
					$Salida .= "		<tr $estilo>\n";
					$Salida .= "			<td align=\"left\" width=\"%\">".$detalle['prefijo']." ".$facturas[$i]['factura_fiscal']."</td>\n";
					$Salida .= "			<td align=\"center\" width=\"%\">".$detalle['fecha_registro']."</td>\n";
					$Salida .= "			<td align=\"right\" width=\"%\">".FormatoValor($detalle['saldo'])."</td>\n";
					$Salida .= "			<td align=\"left\"  width=\"%\">".$detalle['envio_id']."</td>\n";
					$Salida .= "			<td align=\"center\" width=\"%\">".$detalle['fecha_radicacion']."</td>\n";
					$Salida .= "		</tr>\n";
				}
				
				$Salida .= "		<tr class=\"label\">\n";
				$Salida .= "			<td align=\"center\" colspan=\"2\">TOTAL</td>\n";
				$Salida .= "			<td align=\"right\" >".FormatoValor($suma)."</td>\n";
				$Salida .= "			<td colspan=\"2\">&nbsp;</td>\n";
				$Salida .= "		</tr>\n";
				
				$Salida .= "		</table>\n";
			}
			else
			{
				$Salida .= "			<center><b class=\"label\">NO HAY FACTURAS PARA ESTA ENTIDAD</b></center>\n";
			}
	    return $Salida;
		}
	    //AQUI TODOS LOS METODOS QUE USTED QUIERA
	    //---------------------------------------
	}

?>
