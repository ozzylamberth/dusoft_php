<?php

	/**************************************************************************************
	* $Id: carteratipoentidad.report.php,v 1.4 2007/07/03 21:03:39 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/
	include_once "./app_modules/Cartera/classes/Cartera.class.php";	
	class carteratipoentidad_report extends Cartera
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
	  function carteratipoentidad_report($datos=array())
	  {
			$this->datos=$datos;
	    return true;
	  }
		
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			if($this->datos['enviado'] == '1')
				$titulo .= "<b $estilo>REPORTE DE VENCIMIENTOS RESUMIDO POR TIPO DE ENTIDAD</b>";
			else if($this->datos['enviado'] == '0')
				$titulo .= "<b $estilo>REPORTE DE CARTERA NO ENVIADA, RESUMIDA POR TIPO DE ENTIDAD</b>";
				
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
											  'subtitulo'=>' ','logo'=>'logocliente.png','align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$rst = $this-> ConsultarCarteraResumen();
			
			$Clientes = $rst['cartera'];
			$intervalos =  $rst['intervalos'];
			$total_cartera =  $rst['total_cartera'];
			
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			
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
				$Salida .= "			<td align=\"center\" width=\"50\"><b>TIPO ENTIDAD</b></td>\n";
				$Salida .= $Columna;
				$Salida .= "			<td align=\"center\"><b>TOTAL</b></td>\n";
				$Salida .= "			<td align=\"center\"><b>PORCENT</b></td>\n";
				$Salida .= "		</tr>\n";
				
				$saldo = $total = 0;
				
				foreach($Clientes as $key => $cartera)
				{
					if($cartera['tipo_cliente'] != "")
					{
						$saldo = 0;
						$Salida .= "		<tr height=\"23\">\n";
						$Salida .= "			<td width=\"280\" valign=\"top\"><b>".$cartera['tipo_cliente']."</b></td>\n";
						
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
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ConsultarCarteraResumen()
		{
			$empresa = $_SESSION['cartera']['empresa_id'];

			if($this->datos['enviado'] == '1')
			{
				$sql .= "SELECT TC.descripcion, ";
				$sql .= "				(FF.vencimiento::date - NOW()::date)/30 AS intervalo , ";
				$sql .= "				TC.tipo_cliente, ";
				$sql .= "				SUM(FF.saldo) AS saldo , ";
				$sql .= "				SUM(FF.saldo) AS saldo, ";
				$sql .= "				(FF.vencimiento::date - NOW()::date)/30 AS intervalo ";
				
				$sql .= "FROM		( SELECT 	FF.fecha_vencimiento AS vencimiento, ";
				$sql .= "									PL.tipo_cliente, ";
				$sql .= "									SUM(FF.saldo) AS saldo ";
				$sql .= "					FROM 		facturas_externas FF,";
				$sql .= "									planes PL ";
				$sql .= "					WHERE 	FF.empresa_id = '".$empresa."' ";
				$sql .= "					AND 		FF.fecha_vencimiento IS NOT NULL ";
				$sql .= "					AND			FF.plan_id = PL.plan_id ";
				$sql .= "					AND			FF.estado = '0' ";
				$sql .= "					GROUP BY 1,2 ";
				$sql .= "					UNION ";
				$sql .= "					SELECT 	FF.fecha_vencimiento_factura AS vencimiento, ";
				$sql .= "									PL.tipo_cliente, ";
				$sql .= "									SUM(FF.saldo) AS saldo ";
				$sql .= "					FROM 		fac_facturas FF,";
				$sql .= "									planes PL ";
				$sql .= "					WHERE 	FF.empresa_id = '".$empresa."' ";
				$sql .= "					AND			FF.sw_clase_factura = '1' ";
				$sql .= "					AND 		FF.fecha_vencimiento_factura IS NOT NULL ";
				$sql .= "					AND			FF.plan_id = PL.plan_id ";
				$sql .= "					AND			FF.estado = '0' ";
				$sql .= "					GROUP BY 1,2 ";
				$sql .= "				) AS FF,";
				$sql .= "			 	tipos_cliente TC ";
				$sql .= "WHERE 	TC.tipo_cliente = FF.tipo_cliente ";
				$sql .= "GROUP BY TC.descripcion, intervalo,TC.tipo_cliente ";
				$sql .= "ORDER BY TC.descripcion, intervalo ";
			}
			else if($this->datos['enviado'] == '0')
			{
				$sql .= "SELECT TC.descripcion, ";
				$sql .= "				(FF.fecha_registro::date - NOW()::date)/30 AS intervalo , ";
				$sql .= "				TC.tipo_cliente, ";
				$sql .= "				SUM(FF.saldo) AS saldo, ";
				$sql .= "				SUM(FF.saldo) AS saldo, ";
				$sql .= "				(FF.fecha_registro::date - NOW()::date)/30 AS intervalo  ";
				$sql .= "FROM		fac_facturas FF,";
				$sql .= "				planes PL, ";
				$sql .= "			 	tipos_cliente TC ";
				$sql .= "WHERE 	FF.empresa_id = '".$empresa."' ";
				$sql .= "AND		FF.sw_clase_factura = '1' ";
				$sql .= "AND		FF.estado = '0' ";
				$sql .= "AND 		FF.fecha_vencimiento_factura IS NULL ";
				$sql .= "AND		FF.plan_id = PL.plan_id ";
				$sql .= "AND	 	TC.tipo_cliente = PL.tipo_cliente ";
				$sql .= "GROUP BY TC.descripcion, intervalo,TC.tipo_cliente ";
				$sql .= "ORDER BY TC.descripcion, intervalo ";			
			}

			$datos['pèriodo'] = $this->datos['periodo'];
			$retorno = $this->ObtenerArrayCarteraReporte($datos,$empresa,$sql,$labe="tipo_cliente");
			
			return $retorno;
			//return $Cartera;
		}
	    //AQUI TODOS LOS METODOS QUE USTED QUIERA
	    //---------------------------------------
	}

?>
