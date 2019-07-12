<?php

	/**************************************************************************************
	* $Id: carterac.report.php,v 1.3 2007/08/09 19:44:11 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/
	include_once "./app_modules/Cartera/classes/CarteraC.class.php";
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
			$titulo .= "<b $estilo>CARTERA POR PLANES</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
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
			$saldo = $total_anticpos = 0;
			if(sizeof($Clientes) > 0)
			{
				$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
				
				$Salida .= "	<table border=\"1\" cellpading=\"0\" cellspacing=\"0\" align=\"left\" $estilo>\n";
				$Salida .= "		<tr>";
				$Salida .= "			<td align=\"center\" width=\"50\"><b>TERCERO</b></td>\n";
				foreach($intervalos as $key => $intvl)
				{
					$Salida .= "			<td width=\"35\" align=\"center\"><b>";
					switch($intvl)
					{
						case '-7': $Salida .= "Mas de 180"; break;
						case '-6': $Salida .= "151 a 180"; break;
						case '-5': $Salida .= "121 a 150"; break;
						case '-4': $Salida .= "91 a 120"; break;
						case '-3': $Salida .= "61 a 90"; break;
						case '-2': $Salida .= "31 a 60"; break;
						case '-1': $Salida .= "0 a 30"; break;
						case '0': $Salida .= "Corriente"; break;
					}
					$Salida .= "			</b></td>\n";
				}
				$Salida .= "			<td align=\"center\"><b>ANTICIPO</b></td>\n";
				$Salida .= "			<td align=\"center\"><b>TOTAL</b></td>\n";
				$Salida .= "		</tr>\n";
				
				foreach($Clientes as $key => $cartera)
				{
					$Salida .= "		<tr height=\"23\">\n";
					$Salida .= "			<td width=\"280\" valign=\"top\"><b>".$key." </b></td>\n";
					$tl = 0;
					foreach($intervalos as $keyf => $detalle)
					{
						$dtl = $cartera['periodos'][$keyf];
						$dbt = $dtl['total_factura']+$dtl['debito'];
						$cdt = $dtl['ajuste']+$dtl['glosa']+$dtl['credito']+$dtl['anulacion'] + $dtl['recibo'];
						
						$vlr = $dbt-$cdt;
						$Salida .= "			<td align=\"right\" valign=\"top\">".formatoValor($vlr)."</td>\n";
						$tl += $vlr;
					}
					$saldo += $tl;
					$total_anticipos += $cartera['anticipos'];
					
					$Salida .= "			<td align=\"right\" valign=\"top\">".formatoValor($cartera['anticipos'])."</td>\n";
					$Salida .= "			<td align=\"right\" valign=\"top\">".formatoValor($tl-$cartera['anticipos'])."</td>\n";
					$Salida .= "		</tr>\n";	
				}
					
 				$Salida .= "		<tr height=\"35\">\n";
				$Salida .= "			<td valign=\"bottom\" colspan=\"".(sizeof($intervalos)+1)."\"><b>TOTAL</b></td>\n";
				$Salida .= "			<td align=\"right\" valign=\"bottom\" ><b>".formatoValor($total_anticipos)." </b></td>\n";
				$Salida .= "			<td align=\"right\" valign=\"bottom\" ><b>".formatoValor($saldo - $total_anticipos)." </b></td>\n";
				$Salida .= "		</tr>\n";
				$Salida .= "	</table><br>\n";
			}
	    return $Salida;
		}
	}

?>
