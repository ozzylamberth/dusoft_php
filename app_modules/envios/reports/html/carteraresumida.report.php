<?php

	/**************************************************************************************
	* $Id: carteraresumida.report.php,v 1.4 2007/05/15 19:06:32 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/
	include_once "./app_modules/Cartera/classes/Cartera.class.php";		
	class carteraresumida_report extends Cartera
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
		
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			if($this->datos['enviado'] == '1')
				$titulo .= "<b $estilo>REPORTE DE VENCIMIENTOS RESUMIDO</b>";
			else if($this->datos['enviado'] == '0')
				$titulo .= "<b $estilo>REPORTE DE LA CARTERA NO ENVIADA RESUMIDA</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
											  'subtitulo'=>' ','logo'=>'logocliente.png','align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$Cartera = $this->ConsultarCarteraResumen();
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			if(sizeof($Cartera) > 0)
			{
				$Salida .= "	<table border=\"1\" cellpading=\"0\" cellspacing=\"0\" align=\"left\" $estilo>\n";
				
				$total = 0;
				$paso1 = $paso2 = true;
				for ($i=0; $i<15; $i++)
				{
					if($Cartera[$i]['nombre'] != "")
					{
						if($i <= 6 && $paso1 == true)
						{
							$Salida .= "		<tr>\n";
							$Salida .= "			<td colspan=\"2\" align=\"center\"><b>VENCIDOS</b></td>\n";
							$Salida .= "		</tr>\n";
							$Salida .= "		<tr>\n";
							$Salida .= "			<td align=\"center\" ><b>PERIODO</b></td>\n";
							$Salida .= "			<td align=\"center\"><b>TOTAL</b></td>\n";
							$Salida .= "		</tr>\n";
							$paso1 = false;
						}
						else if($i > 6 && $paso2 == true)
							{
								$Salida .= "		<tr>\n";
								$Salida .= "			<td colspan=\"2\" align=\"center\"><b>POR VENCER</b></td>\n";
								$Salida .= "		</tr>\n";
								$Salida .= "		<tr>\n";
								$Salida .= "			<td  align=\"center\"><b>PERIODO</b></td>\n";
								$Salida .= "			<td  align=\"center\"><b>TOTAL</b></td>\n";
								$Salida .= "		</tr>\n";
								$paso2 = false;
							}
						$total += $Cartera[$i]['saldo'];
						$Salida .= "		<tr height=\"23\">\n";
						$Salida .= "			<td width=\"280\" valign=\"top\"><b>".$Cartera[$i]['nombre']."</b></td>\n";
						$Salida .= "			<td align=\"right\" valign=\"top\">".formatoValor($Cartera[$i]['saldo'])."</td>\n";
						$Salida .= "		</tr>\n";
					}		
				}
					
				$Salida .= "		<tr height=\"35\">\n";
				$Salida .= "			<td valign=\"bottom\"><b>TOTAL GENERAL</b></td>\n";
				$Salida .= "			<td align=\"right\" valign=\"bottom\" ><b>".formatoValor($total)." </b></td>\n";
				$Salida .= "		</tr>\n";				
				$Salida .= "	</table><br>\n";
			}
			else
			{
				$Salida .= "			<center><b class=\"label\">NO HAY CARTERA PARA MOSTRAR</b></center>\n";
			}	
	    return $Salida;
		}
		
		/********************************************************************************
		* En esta funcion se realiza la consulta de la cartera de cada cliente, se 
		* realiza una evaluacion para determinar a que rango pertenecen los saldos y el .
		* valor pendiente
		*
		* @return boolean
		*********************************************************************************/
		function ConsultarCarteraResumen()
		{
			$empresa = $_SESSION['cartera']['empresa_id'];
			
			if($this->datos['enviado'] == '1')
			{
				/*$sql .= "SELECT (FF.fecha_vencimiento::date - NOW()::date)/30  AS intervalo , ";
				$sql .= "				SUM(FF.saldo) AS saldo ";
				$sql .= "FROM		view_fac_facturas FF ";
				$sql .= "WHERE 	FF.fecha_vencimiento IS NOT NULL ";
				$sql .= "AND		FF.empresa_id = '".$empresa."' ";
				//$sql .= "AND  	FF.saldo > 0 ";
				$sql .= "AND  	FF.estado = '0' ";
				$sql .= "GROUP BY intervalo ";
			 	$sql .= "ORDER BY intervalo ";*/
				
				$sql  = "SELECT	intervalo, ";
				$sql .= "				SUM(saldo) AS saldo ";
				$sql .= "FROM 	vista_cartera ";
				$sql .= "WHERE	empresa_id = '".$empresa."' ";
				$sql .= "GROUP BY intervalo ";
			 	echo $sql .= "ORDER BY intervalo ";
			}
			else if($this->datos['enviado'] == '0')
			{
				$sql .= "SELECT (FF.fecha_registro::date - NOW()::date)/30 AS intervalo , ";
				$sql .= "				SUM(FF.saldo) AS saldo ";
				$sql .= "FROM		fac_facturas FF ";
				$sql .= "WHERE  FF.empresa_id = '".$empresa."' ";
				$sql .= "AND    FF.sw_clase_factura='1'::bpchar ";
				$sql .= "AND    FF.estado = '0'::bpchar ";
				$sql .= "AND    FF.saldo > 0 ";
				$sql .= "AND    FF.fecha_vencimiento_factura IS NULL ";
				$sql .= "GROUP BY intervalo ";
			 	$sql .= "ORDER BY intervalo ";
			}
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$x = 0;
			if($this->datos['periodo'] != "" && $this->datos['periodo'] != "X") $x = $this->datos['periodo'];
			
			$total = 0;
			$periodos = array();
			
			while(!$rst->EOF)
			{	
				$diferencia = $rst->fields[0]*30;
				
				if($diferencia == 0)
				{
					$periodos[7]['saldo'] += $rst->fields[1];
					$periodos[7]['nombre'] = "ESTE MES";
				}
				if($diferencia < 0 && $x != 7)
				{
					$a = $diferencia*(-1);
					if($a <= 30 && $x <= 6)
					{
						$periodos[6]['saldo'] += $rst->fields[1];
						$periodos[6]['nombre'] = "A 30 DÍAS";
					}
					else if($a <= 60 && $x <= 5)
						{
							$periodos[5]['saldo'] += $rst->fields[1];
							$periodos[5]['nombre'] = "A 60 DÍAS";
						}
						else if($a <= 90 && $x <= 4)
							{
								$periodos[4]['saldo'] += $rst->fields[1];
								$periodos[4]['nombre'] = "A 90 DÍAS";
							}
							else if($a <= 120 && $x <= 3) 
								{
									$periodos[3]['saldo'] += $rst->fields[1];
									$periodos[3]['nombre'] = "A 120 DÍAS";
								}
								else if($a <= 150 && $x <= 2)
									{
										$periodos[2]['saldo'] += $rst->fields[1];
										$periodos[2]['nombre'] = "A 150 DÍAS";
									}
									else if($a <= 180 && $x <= 1)
										{
											$periodos[1]['saldo'] += $rst->fields[1];
											$periodos[1]['nombre'] = "A 180 DÍAS";
										}
										else if($x <= 0)
											{
												$periodos[0]['saldo'] += $rst->fields[1];
												$periodos[0]['nombre'] = " MAS DE 180";
											}
				}
				if($diferencia > 0 && $x != 7)
				{
					$a = $diferencia;
					if($a <= 30 && $x <= 6)
					{
						$periodos[8]['saldo'] += $rst->fields[1];
						$periodos[8]['nombre'] = "A 30 DÍAS";
					}
					else if($a <= 60 && $x <= 5)
						{
							$periodos[9]['saldo'] += $rst->fields[1];
							$periodos[9]['nombre'] = "A 60 DÍAS";
						}
						else if($a <= 90 && $x <= 4)
							{
								$periodos[10]['saldo'] += $rst->fields[1];
								$periodos[10]['nombre'] = "A 90 DÍAS";
							}
							else if($a <= 120 && $x <= 3)
								{
									$periodos[11]['saldo'] += $rst->fields[1];
									$periodos[11]['nombre'] = "A 120 DÍAS";
								}
								else if($a <= 150 && $x <= 2)
									{
										$periodos[12]['saldo'] += $rst->fields[1];
										$periodos[12]['nombre'] = "A 150 DÍAS";
									}
									else if($a <= 180 && $x <= 1)
										{
											$periodos[13]['saldo'] += $rst->fields[1];
											$periodos[13]['nombre'] = "A 180 DÍAS";
										}
										else if($x <= 0)
											{
												$periodos[14]['saldo'] += $rst->fields[1];
												$periodos[14]['nombre'] = "MAS DE 180";
											}
				}
				$rst->MoveNext();
				$total += $rst->fields[1];
			}
			$rst->Close();
			if($total > 0) return $periodos;
			else return array();
		}
	    //AQUI TODOS LOS METODOS QUE USTED QUIERA
	    //---------------------------------------
	}
?>