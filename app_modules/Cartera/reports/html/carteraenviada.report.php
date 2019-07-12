<?php
	/**
	* $Id: carteraenviada.report.php,v 1.8 2009/06/26 13:53:16 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
 	* @author Hugo Freddy Manrique
	*/
	IncludeClass("ConexionBD");
	IncludeClass("CarteraRadicada","classes","app","Cartera");
	class carteraenviada_report extends CarteraRadicada
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
	  function carteraenviada_report($datos=array())
	  {
			$this->datos=$datos;
	    return true;
	  }
		
		function GetMembrete()
		{			
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			
      if($this->datos['opcion'] == 'R')
				$titulo .= "<b $estilo>REPORTE DE VENCIMIENTOS RESUMIDO POR ENTIDADES</b>";
			else if($this->datos['opcion'] == 'N')
				$titulo .= "<b $estilo>REPORTE DE CARTERA NO ENVIADA - RESUMIDO POR ENTIDADES</b>";

			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
											  'subtitulo'=>' ','logo'=>'logocliente.png','align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$this->datos['nombre_tercero'] = $this->datos['tercero'];
      $this->datos['fecha'] = date("d/m/Y");
              
      $rst = $this->ObtenerReporte($this->datos,$this->datos['opcion']);
  			
			$Clientes = $rst['cartera'];
			$intervalos =  $rst['intervalos'];
			$total_cartera =  $rst['total_cartera'];
			
      ksort($intervalos);
			ksort($Clientes);
      
      $html  = "			<center><b class=\"label\">NO HAY CARTERA PARA MOSTRAR</b></center>\n";

			if(sizeof($Clientes) > 0)
			{
				$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
				$tmn = sizeof($intervalos)*80 + 240;
        if($this->datos['opcion'] == "R") $tmn += 80;
        
        $html  = "	<table align=\"center\" width=\"".$tmn."\" border=\"1\" cellpading=\"0\" cellspacing=\"0\" align=\"left\" $estilo>\n";
				$html .= "		<tr>\n";
				$html .= "			<td align=\"center\" width=\"80\" ><b>TERCERO</b></td>\n";
				foreach($intervalos as $key => $intvl)
				{
					$html .= "			<td width=\"80\" align=\"center\" ><b>";
					switch($intvl)
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
					$html .= "			</b></td>\n";
				}
				if($this->datos['opcion'] == "R")
          $html .= "			<td align=\"center\" width=\"80\" ><b>ANTICIPOS</b></td>\n";
				$html .= "			<td align=\"center\" width=\"80\" ><b>TOTAL</b></td>\n";
        $html .= "			<td align=\"center\" width=\"80\" ><b>PORCENT</b></td>\n";
				$html .= "		</tr>\n";
				
				$saldo = $total = $antcp = 0;
        
        $i=0;
				foreach($Clientes as $key => $cartera)
				{
					$html .= "		<tr height=\"23\">\n";
					$html .= "			<td width=\"280\" valign=\"top\"><b>".str_replace("'","",$key)." </b></td>\n";
					$tl = 0;
					foreach($intervalos as $keyf => $detalle)
					{
						$dtl = $cartera['periodos'][$keyf];
						$totalI[$keyf] += $dtl['total_intervalo'];
						
						$html .= "			<td align=\"right\" valign=\"top\">".formatoValor($dtl['total_intervalo'])."</td>\n";
						$tl += $dtl['total_intervalo'];
					}
					
          $totalcliente = $tl - ($cartera['anticipos'] - $cartera['descargo']);
					$porcentaje = ($totalcliente/$total_cartera)*100;
          $total_anticipos += ($cartera['anticipos'] - $cartera['descargo']);
          $saldo += $tl - ($cartera['anticipos'] - $cartera['descargo']);
					if($this->datos['opcion'] == "R")
            $html .= "			<td align=\"right\" valign=\"top\">".formatoValor(($cartera['anticipos'] - $cartera['descargo']))."</td>\n";
					$html .= "			<td align=\"right\" valign=\"top\">".formatoValor($totalcliente)."</td>\n";
					$html .= "			<td align=\"right\" valign=\"top\">".number_format($porcentaje,2,',','.')."%</td>";
          $html .= "		</tr>\n";	
				}
					
 				$html .= "		<tr >\n";
				$html .= "			<td valign=\"bottom\"><b>TOTAL</b></td>\n";
        foreach($intervalos as $keyf => $detalle)
				{
					$html .= "			<td align=\"right\" valign=\"bottom\">".formatoValor($totalI[$keyf])."</td>\n";
				}
				if($this->datos['opcion'] == "R")
          $html .= "			<td align=\"right\" valign=\"bottom\" ><b>".formatoValor($total_anticipos)." </b></td>\n";
				$html .= "			<td align=\"right\" valign=\"bottom\" ><b>".formatoValor($saldo)." </b></td>\n";
				$html .= "			<td align=\"right\" valign=\"bottom\" >&nbsp;</td>\n";
				$html .= "		</tr>\n";
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