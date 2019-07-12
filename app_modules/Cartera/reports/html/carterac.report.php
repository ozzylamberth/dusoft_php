<?php
	/***
	* $Id: carterac.report.php,v 1.10 2009/06/26 13:53:16 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
 	* @author Hugo Freddy Manrique
	*/
	IncludeClass("CarteraC","classes","app","Cartera");
	class carterac_report extends CarteraC
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
	  function carterac_report($datos=array())
	  {
			$this->datos=$datos;
	    return true;
	  }
		
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= "<b $estilo>CARTERA POR FECHA DE CORTE</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>"<b $estilo>".$this->datos['fecha']."</b>",
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}
    //FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {				
			$this->datos['empresa_id'] = $_SESSION['cartera']['empresa_id'];
			//$this->datos['fecha'] = $this->datos[0]['fecha'];
			
			$dat= $this->ObtenerReporte($this->datos);
			$Clientes = $dat['cartera'];
			$intervalos = $dat['intervalos'];
			
			ksort($intervalos);
			ksort($Clientes);
			$html = "";
			$saldo = $total_anticpos = $total_descargo = 0;
			if(sizeof($Clientes) > 0)
			{
				$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
				$tmn = sizeof($intervalos)*80 + 480;
				$html  = "	<table border=\"1\" width=\"".$tmn."\" cellpading=\"0\" cellspacing=\"0\" align=\"left\" $estilo>\n";
				$html .= "		<tr>\n";
				$html .= "			<td align=\"center\" width=\"80\" rowspan=\"2\"><b>TERCERO</b></td>\n";
				foreach($intervalos as $key => $intvl)
				{
					$html .= "			<td width=\"80\" align=\"center\" rowspan=\"2\"><b>";
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
				$html .= "			<td align=\"center\" width=\"80\" rowspan=\"2\"><b>TOTAL</b></td>\n";
				$html .= "			<td align=\"center\" width=\"240\" colspan=\"3\"><b>ANTICIPOS</b></td>\n";
				$html .= "			<td align=\"center\" width=\"80\" rowspan=\"2\"><b>TOTAL CARTERA</b></td>\n";
				$html .= "		</tr>\n";
        $html .= "		<tr>\n";
        $html .= "			<td align=\"center\"><b>RECAUDO</b></td>\n";
        $html .= "			<td align=\"center\"><b>DESCARGO</b></td>\n";
        $html .= "			<td align=\"center\"><b>SIN CRUZAR</b></td>\n";
				$html .= "		</tr>\n";
				$i=0;
				foreach($Clientes as $key => $cartera)
				{
					$html .= "		<tr height=\"23\">\n";
					$html .= "			<td width=\"280\" valign=\"top\"><b>".str_replace("'","",$key)." </b></td>\n";
					$tl = 0;
					foreach($intervalos as $keyf => $detalle)
					{
						$dtl = $cartera['periodos'][$keyf];
						$dbt = $dtl['total_intervalo'];
						//$cdt = $dtl['ajuste']+$dtl['glosa']+$dtl['credito']+$dtl['anulacion'] + $dtl['recibo'];
						
						$vlr = $dbt;
						$html .= "			<td align=\"right\" valign=\"top\">".formatoValor($vlr)."</td>\n";
						$tl += $vlr;
					}
          //($tl == 0)? $tc = 0 : $tc = $tl - $cartera['descargo'];
					$saldo += $tl;
          $tc = $tl - ( $cartera['anticipos'] - $cartera['descargo']);
					$total_anticipos += $cartera['anticipos'];
					$total_descargo += $cartera['descargo'];
					$total_cartera += $tc;
					
					$html .= "			<td align=\"right\" valign=\"top\">".formatoValor($tl)."</td>\n";
					$html .= "			<td align=\"right\" valign=\"top\">".formatoValor($cartera['anticipos'])."</td>\n";
					$html .= "			<td align=\"right\" valign=\"top\">".formatoValor($cartera['descargo'])."</td>\n";
					$html .= "			<td align=\"right\" valign=\"top\">".formatoValor($cartera['anticipos'] - $cartera['descargo'])."</td>\n";
          
					$html .= "			<td align=\"right\" valign=\"top\">".formatoValor($tc)."</td>\n";
					$html .= "		</tr>\n";	
				}
					
 				$html .= "		<tr height=\"35\">\n";
				$html .= "			<td valign=\"bottom\" colspan=\"".(sizeof($intervalos)+1)."\"><b>TOTAL</b></td>\n";
				$html .= "			<td align=\"right\" valign=\"bottom\" ><b>".formatoValor($saldo)." </b></td>\n";
				$html .= "			<td align=\"right\" valign=\"bottom\" ><b>".formatoValor($total_anticipos)." </b></td>\n";
				$html .= "			<td align=\"right\" valign=\"bottom\" ><b>".formatoValor($total_descargo)." </b></td>\n";
				$html .= "			<td align=\"right\" valign=\"bottom\" ><b>".formatoValor($total_anticipos - $total_descargo)." </b></td>\n";
				$html .= "			<td align=\"right\" valign=\"bottom\" ><b>".formatoValor($total_cartera)." </b></td>\n";
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