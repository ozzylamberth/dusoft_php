<?php
	/***
	* $Id: carteraresumida.report.php,v 1.8 2009/06/26 13:53:16 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
 	* @author Hugo F. Manrique
	*/
	IncludeClass("ConexionBD");
	IncludeClass("CarteraRadicada","classes","app","Cartera");
	class carteraresumida_report extends CarteraRadicada
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
	  function carteraresumida_report($datos=array())
	  {
			$this->datos=$datos;
	    return true;
	  }
		/**
    *
    */
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			if($this->datos['opcion'] == 'R')
				$titulo .= "<b $estilo>REPORTE DE VENCIMIENTOS RESUMIDO</b>";
			else if($this->datos['opcion'] == 'N')
				$titulo .= "<b $estilo>REPORTE DE CARTERA NO ENVIADA RESUMIDA</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
											  'subtitulo'=>' ','logo'=>'logocliente.png','align'=>'left'));
			return $Membrete;
		}
    /**
		* FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		*/
    function CrearReporte()
	  {
      $this->datos['nombre_tercero'] = $this->datos['tercero'];
      $this->datos['fecha'] = date("d/m/Y");

      $Cartera = $this->ObtenerReporteCp($this->datos,$this->datos['opcion']);
      
      $estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";

			$html  = "			<center><b class=\"label\">NO HAY CARTERA PARA MOSTRAR</b></center>\n";
      if(sizeof($Cartera) > 0)
			{
				$html  = "	<table align=\"center\" border=\"1\" width=\"100%\" cellpading=\"0\" cellspacing=\"0\" align=\"left\" $estilo>\n";
        $html .= "		<tr class=\"label\" align=\"center\">\n";
        $html .= "			<td >PERIODO</td>\n";
        $html .= "			<td >TOTAL POR INTERVALO</td>\n";
        $html .= "		</tr>\n";
				
				$saldo = 0;
				foreach($Cartera['cartera'] as $key => $dtl)
				{
          $html .= "		<tr height=\"23\" class=\"normal_10\">\n";
          $html .= "			<td width=\"25%\" ><b>";
					switch($key)
					{
						case '13': $html .= "Mas de 360"; break;
						case '6': $html .= "151 a 180"; break;
						case '5': $html .= "121 a 150"; break;
						case '4': $html .= "91 a 120"; break;
						case '3': $html .= "61 a 90"; break;
						case '2': $html .= "31 a 60"; break;
						case '1': $html .= "0 a 30"; break;
						case '0': $html .= "Corriente"; break;
            default: $html .= "181 a 360"; break;
					}
					$html .= "			</td>\n";
          $html .= "			<td align=\"right\" width=\"50%\" >".formatoValor($dtl['total_intervalo'])."</td>\n";
          $html .= "		</tr>\n";
          $saldo += $dtl['total_intervalo']; 
				}
				$anticipo = $Cartera['anticipos']['anticipos']-$Cartera['anticipos']['descargo'];
				$html .= "		<tr class=\"label\">\n";
				$html .= "			<td valign=\"bottom\">TOTAL CARTERA</td>\n";
				$html .= "			<td align=\"right\" valign=\"bottom\" ><b>".formatoValor($saldo)." </b></td>\n";
				$html .= "		</tr>\n";				
				if($this->datos['opcion'] == "R")
        {
          $html .= "		<tr class=\"label\">\n";
          $html .= "			<td valign=\"bottom\">TOTAL EN ANTICIPOS</td>\n";
          $html .= "			<td align=\"right\" valign=\"bottom\" ><b>".formatoValor($anticipo)." </b></td>\n";
          $html .= "		</tr>\n";
          $html .= "		<tr class=\"label\">\n";
          $html .= "			<td valign=\"bottom\">TOTAL</td>\n";
          $html .= "			<td align=\"right\" valign=\"bottom\" ><b>".formatoValor($saldo - $anticipo)." </b></td>\n";
          $html .= "		</tr>\n";		        
				}
        $html .= "	</table><br>\n";
        $html .= "	<br><table border='0' width=\"100%\">\n";
        $html .= "		<tr>\n";
        $html .= "			<td align=\"justify\" width=\"50%\">\n";
        $html .= "				<font size='1' face='arial'>\n";
        $html .= "					Imprimió:&nbsp;".$this->ObtenerUsuarioNombre(UserGetUID())."\n";
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