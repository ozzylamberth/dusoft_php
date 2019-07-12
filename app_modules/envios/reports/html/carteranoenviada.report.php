<?php

	/**************************************************************************************
	* $Id: carteranoenviada.report.php,v 1.5 2007/07/03 21:03:39 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/
	include_once "./app_modules/Cartera/classes/Cartera.class.php";	
	class carteranoenviada_report extends Cartera
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
	  function carteranoenviada_report($datos=array())
	  {
			$this->datos=$datos;
	    return true;
	  }
		
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= "<b $estilo>REPORTE DE CARTERA NO ENVIADA, RESUMIDO POR ENTIDADES</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
											  'subtitulo'=>' ','logo'=>'logocliente.png','align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$this->Cliente = $this->datos['tercero'];
			$this->PeriodoSeleccionado = $this->datos['periodo'];
			
			$rqst['periodo'] = $this->datos['periodo'];
			$rqst['ordenar_por'] = $this->datos['ordenar_por'];
			$rqst['nombre_tercero'] = $this->datos['tercero'];
			
			$rst = $this->ConsultarCarteraClientesNoRadicadaReporte($rqst,$_SESSION['cartera']['empresa_id']);
			
			$Clientes = $rst['cartera'];
			$intervalos =  $rst['intervalos'];
			$total_cartera =  $rst['total_cartera'];
			
			if(sizeof($Clientes) > 0)
			{
				$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
				
				$Salida .= "	<table border=\"1\" cellpading=\"0\" cellspacing=\"0\" align=\"left\" $estilo>\n";
				$k= $size = 0;
				$p = $v = 0;
				
				foreach($intervalos as $key => $intvl)
				{
					if($intvl != "")
					{
						$Columna .= "			<td width=\"35\" align=\"center\"><b>$intvl</b></td>\n";
					}
				}
				$size += 23;
				
				$Salida .= "		<tr>";
				$Salida .= "			<td align=\"center\" width=\"50\"><b>CLIENTE</b></td>\n";
				$Salida .= $Columna;
				$Salida .= "			<td align=\"center\"><b>TOTAL</b></td>\n";
				$Salida .= "			<td align=\"center\"><b>PORCENT</b></td>\n";
				$Salida .= "		</tr>\n";
				
				$saldo = $total = 0;
									
				//for ($i=0; $i<sizeof($Clientes); $i++)
				foreach($Clientes as $key => $cartera)
				{
					if($cartera['empresa'] != "")
					{
						$saldo = 0;
						$Salida .= "		<tr height=\"23\">\n";
						$Salida .= "			<td width=\"280\" valign=\"top\"><b>".$cartera['empresa']."</b></td>\n";
						
						for($j = 0; $j<15; $j++)
						{
							if($intervalos[$j] != "")
							{		
								$saldo = $cartera['periodos'][$j]['saldo'];
								$saldoT[$j] += $cartera['periodos'][$j]['saldo'];
							
								$total += $saldo;	
								$Salida .= "			<td align=\"right\" valign=\"top\">".formatoValor($saldo)."</td>\n";
							}
						}
						
						$porcentaje = ($cartera['saldo']/$total_cartera)*100;
							
						$Salida .= "			<td align=\"right\" valign=\"top\">".formatoValor($cartera['saldo'])."</td>\n";
						$Salida .= "			<td align=\"right\" class=\"modulo_table_list_title\">".number_format($porcentaje,2,',','.')."%</td>";
						$Salida .= "		</tr>\n";
					}		
				}
					
				$Salida .= "		<tr height=\"35\">\n";
				$Salida .= "			<td valign=\"bottom\"><b>TOTAL</b></td>\n";
										
				for($j = 0; $j<15; $j++)
				{
					if($intervalos[$j] != "")
					{		
						$Salida .= "			<td align=\"right\" valign=\"bottom\" >".formatoValor($saldoT[$j])."</td>\n";
					}		
				}
				
				$Salida .= "			<td align=\"right\" valign=\"bottom\" ><b>".formatoValor($total)." </b></td>\n";
				$Salida .= "			<td align=\"center\">&nbsp;</td>\n";
				$Salida .= "		</tr>\n";				
				$Salida .= "	</table><br>\n";
			}
			else
			{
				$Salida .= "			<center><b class=\"label\">NO HAY CARTERA PARA MOSTRAR</b></center>\n";
			}
	    return $Salida;
		}
		
	  //AQUI TODOS LOS METODOS QUE USTED QUIERA
	  //---------------------------------------
	}

?>
