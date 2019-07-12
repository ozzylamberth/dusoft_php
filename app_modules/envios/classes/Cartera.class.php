<?php
  /******************************************************************************
  * $Id: Cartera.class.php,v 1.3 2007/08/09 19:44:11 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.3 $ 
	* 
	* @autor Hugo F  Manrique 
  ********************************************************************************/
	class Cartera
	{
		var $Arreglo = array();
		
		function Cartera(){}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ConsultarCarteraClientes($datos,$empresa)
		{
			$sql = $this->ObtenerCartera($datos,$empresa);
			//$retorno = $this->ObtenerArrayCartera($datos,$empresa,$sql);
			$retorno = $this->ObtenerArrayCarteraReporte($datos,$empresa,$sql);
			
			return $retorno;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ConsultarCarteraClientesNoRadicada($datos,$empresa)
		{
			$sql = $this->ObtenerCarteraNoRadicada($empresa,$datos);
			//$retorno = $this->ObtenerArrayCartera($datos,$empresa,$sql);
			$retorno = $this->ObtenerArrayCarteraReporte($datos,$empresa,$sql);

			return $retorno;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerArrayCartera($datos,$empresa,$sql,$label="empresa")
		{			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			$client = "";
			$identi = "";
			$periodos = array();
			$intervalos = array();
			$total_cartera = 0;
			
			$i = -1;
			$x = 0;
			$k = 0;
				
			if($datos['periodo'] != "" && $datos['periodo'] != "X") $x = $datos['periodo'];
			
			while(!$rst->EOF)
			{				
				if($client != $rst->fields[0]." ".$rst->fields[2])
				{
					($datos['ordenar_por'] == '1')? $i++: $i = $rst->fields[0]." ".$rst->fields[2];
					//$i++;
					$saldo = 0;
					$periodos = array();
					$Cartera[$i]['id'] = $rst->fields[2];
					$Cartera[$i][$label] = $rst->fields[0]; 
				}
				
				$client = $rst->fields[0]." ".$rst->fields[2];
				$diferencia = $rst->fields[5];
				
				if($diferencia == 0)
				{
					$periodos[7]['saldo'] += $rst->fields[3];
					$periodos[7]['valor_pendiente'] +=  $rst->fields[4];
					$saldo += $rst->fields[3];
					$intervalos[7] = "ESTE MES";
				}
				else if($diferencia < 0 && $x != 7)
				{
					$a = $diferencia*(-1);
					if($a <= 30 && $x <= 6)
					{
						$periodos[6]['saldo'] += $rst->fields[3];
						$periodos[6]['valor_pendiente'] +=  $rst->fields[4];
						$saldo += $rst->fields[3];
						$intervalos[6] = "A 30 DÍAS";
					}
					else if($a <= 60 && $x <= 5)
						{
							$periodos[5]['saldo'] += $rst->fields[3];
							$periodos[5]['valor_pendiente'] +=  $rst->fields[4];
							$saldo += $rst->fields[3];
							$intervalos[5] = "A 60 DÍAS";
						}
						else if($a <= 90 && $x <= 4)
							{
								$periodos[4]['saldo'] += $rst->fields[3];
								$periodos[4]['valor_pendiente'] +=  $rst->fields[4];
								$saldo += $rst->fields[3];
								$intervalos[4] = "A 90 DÍAS";
							}
							else if($a <= 120 && $x <= 3) 
								{
									$periodos[3]['saldo'] += $rst->fields[3];
									$periodos[3]['valor_pendiente'] +=  $rst->fields[4];
									$saldo += $rst->fields[3];
									$intervalos[3] = "A 120 DÍAS";
								}
								else if($a <= 150 && $x <= 2)
									{
										$periodos[2]['saldo'] += $rst->fields[3];
										$periodos[2]['valor_pendiente'] +=  $rst->fields[4];
										$saldo += $rst->fields[3];
										$intervalos[2] = "A 150 DÍAS";
									}
									else if($a <= 180 && $x <= 1)
										{
											$periodos[1]['saldo'] += $rst->fields[3];
											$periodos[1]['valor_pendiente'] +=  $rst->fields[4];
											$saldo += $rst->fields[3];
											$intervalos[1] = "A 180 DÍAS";
										}
										else if($x <= 0)
											{
												$periodos[0]['saldo'] += $rst->fields[3];
												$saldo += $rst->fields[3];
												$periodos[0]['valor_pendiente'] += $rst->fields[4];
							
												$intervalos[0] = " MAS DE 180";
											}
				}
				else if($diferencia > 0 && $x != 7)
				{
					$a = $diferencia;
					if($a <= 30 && $x <= 6)
					{
						$periodos[8]['saldo'] += $rst->fields[3];
						$periodos[8]['valor_pendiente'] +=  $rst->fields[4];
						$saldo += $rst->fields[3];
						$intervalos[8] = "A 30 DÍAS";
					}
					else if($a <= 60 && $x <= 5)
						{
							$periodos[9]['saldo'] += $rst->fields[3];
							$periodos[9]['valor_pendiente'] +=  $rst->fields[4];
							$saldo += $rst->fields[3];
							$intervalos[9] = "A 60 DÍAS";
						}
						else if($a <= 90 && $x <= 4)
							{
								$periodos[10]['saldo'] += $rst->fields[3];
								$periodos[10]['valor_pendiente'] +=  $rst->fields[4];
								$saldo += $rst->fields[3];
								$intervalos[10] = "A 90 DÍAS";
							}
							else if($a <= 120 && $x <= 3)
								{
									$periodos[11]['saldo'] += $rst->fields[3];
									$periodos[11]['valor_pendiente'] +=  $rst->fields[4];	
									$saldo += $rst->fields[3];
									$intervalos[11] = "A 120 DÍAS";
								}
								else if($a <= 150 && $x <= 2)
									{
										$periodos[12]['saldo'] += $rst->fields[3];
										$periodos[12]['valor_pendiente'] +=  $rst->fields[4];
										$saldo += $rst->fields[3];
										$intervalos[12] = "A 150 DÍAS";
									}
									else if($a <= 180 && $x <= 1)
										{
											$periodos[13]['saldo'] += $rst->fields[3];
											$periodos[13]['valor_pendiente'] +=  $rst->fields[4];	
											$saldo += $rst->fields[3];
											$intervalos[13] = "A 180 DÍAS";
										}
										else if($x <= 0)
											{
												$periodos[14]['saldo'] += $rst->fields[3];
												$periodos[14]['valor_pendiente'] += $rst->fields[4];
												$saldo += $rst->fields[3];
												$intervalos[14] = "MAS DE 180";
											}
				}
				$rst->MoveNext();
				if($client != $rst->fields[0]." ".$rst->fields[2])
				{
					if($saldo == 0)
					{
						unset($Cartera[$i]);
					}
					else
					{
						$Cartera[$i]['saldo'] = $saldo;
						$total_cartera +=  $saldo;
						$Cartera[$i]['periodos'] = $periodos;
						$saldo = 0;
					}
				}
			}
			
			if($total_cartera != 0)
			{
				$this->Arreglo = $Cartera;
				if($datos['ordenar_por'] == '1')
					$this->Ordenar(0,sizeof($Cartera));
				else
					ksort($this->Arreglo);
			}
			
			$rst->Close();
			
			return array("cartera"=>$this->Arreglo,"intervalos"=>$intervalos,"total_cartera"=>$total_cartera);
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ConsultarCarteraClientesReporte($datos,$empresa)
		{
			$sql = $this->ObtenerCartera($datos,$empresa);
			$retorno = $this->ObtenerArrayCarteraReporte($datos,$empresa,$sql);
			
			return $retorno;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ConsultarCarteraClientesNoRadicadaReporte($datos,$empresa)
		{
			$sql = $this->ObtenerCarteraNoRadicada($empresa,$datos);
			$retorno = $this->ObtenerArrayCarteraReporte($datos,$empresa,$sql);
			
			return $retorno;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/		
		function ObtenerArrayCarteraReporte($datos,$empresa,$sql,$label="empresa",$tipo="T")
		{
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			$client = "";
			$periodos = array();
			$intervalos = array();
			$total_cartera = 0;
			
			$i = -1;
			$x = 0;
			$k = 0;
				
			if($datos['periodo'] != "" && $datos['periodo'] != "X") $x = $datos['periodo'];
			
			while(!$rst->EOF)
			{				
				if($client != $rst->fields[0]." ".$rst->fields[2])
				{
					($datos['ordenar_por'] == '1')? $i++: $i = $rst->fields[0]." ".$rst->fields[2];

					$saldo = 0;
					$periodos = array();
					$Cartera[$i]['id'] = $rst->fields[2];
					if($tipo == "T")
						$Cartera[$i][$label] = $rst->fields[0]."<br>".$rst->fields[2]; 
					else
						$Cartera[$i][$label] = $rst->fields[0]; 
				}
				
				$client = $rst->fields[0]." ".$rst->fields[2];
				$diferencia = $rst->fields[5];
				
				if($diferencia >= 0)
				{
					$periodos[0]['saldo'] += $rst->fields[3];
					$periodos[0]['valor_pendiente'] +=  $rst->fields[4];
					$saldo += $rst->fields[3];
					$intervalos[0] = "CORRIENTE";
				}
				else if($diferencia < 0 && $x != 7)
				{
					$a = $diferencia*(-1);
					if($a <= 30 && $x <= 6)
					{
						$periodos[1]['saldo'] += $rst->fields[3];
						$periodos[1]['valor_pendiente'] +=  $rst->fields[4];
						$saldo += $rst->fields[3];
						$intervalos[1] = "1 - 30 DÍAS";
					}
					else if($a <= 60 && $x <= 5)
						{
							$periodos[2]['saldo'] += $rst->fields[3];
							$periodos[2]['valor_pendiente'] +=  $rst->fields[4];
							$saldo += $rst->fields[3];
							$intervalos[2] = "31 - 60 DÍAS";
						}
						else if($a <= 90 && $x <= 4)
							{
								$periodos[3]['saldo'] += $rst->fields[3];
								$periodos[3]['valor_pendiente'] +=  $rst->fields[4];
								$saldo += $rst->fields[3];
								$intervalos[3] = "61 - 90 DÍAS";
							}
							else if($a <= 120 && $x <= 3) 
								{
									$periodos[4]['saldo'] += $rst->fields[3];
									$periodos[4]['valor_pendiente'] +=  $rst->fields[4];
									$saldo += $rst->fields[3];
									$intervalos[4] = "91 - 120 DÍAS";
								}
								else if($a <= 150 && $x <= 2)
									{
										$periodos[5]['saldo'] += $rst->fields[3];
										$periodos[5]['valor_pendiente'] +=  $rst->fields[4];
										$saldo += $rst->fields[3];
										$intervalos[5] = "121 - 150 DÍAS";
									}
									else if($a <= 180 && $x <= 1)
										{
											$periodos[6]['saldo'] += $rst->fields[3];
											$periodos[6]['valor_pendiente'] +=  $rst->fields[4];
											$saldo += $rst->fields[3];
											$intervalos[6] = "151 - 180 DÍAS";
										}
										else if($a > 180 )
											{
												$periodos[7]['saldo'] += $rst->fields[3];
												$saldo += $rst->fields[3];
												$periodos[7]['valor_pendiente'] += $rst->fields[4];
							
												$intervalos[7] = "181 Y MAS DÍAS";
											}
				}
				
				$rst->MoveNext();
				if($client != $rst->fields[0]." ".$rst->fields[2])
				{
					if($saldo == 0)
					{
						unset($Cartera[$i]);
					}
					else
					{
						$Cartera[$i]['saldo'] = $saldo;
						$total_cartera +=  $saldo;
						$Cartera[$i]['periodos'] = $periodos;
						$saldo = 0;
					}
				}
			}
			
			if($total_cartera != 0)
			{
				$this->Arreglo = $Cartera;
				if($datos['ordenar_por'] == '1')
					$this->Ordenar(0,sizeof($Cartera));
				else
					ksort($this->Arreglo);
			}
			
			$rst->Close();
			ksort($intervalos);
			
			return array("cartera"=>$this->Arreglo,"intervalos"=>$intervalos,"total_cartera"=>$total_cartera);
		}
		/********************************************************************************
		* En esta funcion se realiza la consulta de la cartera de cada cliente, se 
		* realiza una evaluacion para determinar a que rango pertenecen los saldos y el .
		* valor pendiente
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerCartera($datos,$empresa)
		{
			$sql = "";
			if($datos['prefijo'] && $datos['factura_f']!= "")
			{
				$sql1 .= "SELECT tipo_id_tercero,tercero_id FROM view_fac_facturas ";
				$sql1 .= "WHERE	prefijo = '".$datos['prefijo']."' ";
				$sql1 .= "AND		factura_fiscal = ".$datos['factura_f']." ";
				$sql1 .= "AND		saldo > 0";
				$sql1 .= "AND		empresa_id = '".$empresa."' ";
				$sql1 .= "AND 	fecha_vencimiento IS NOT NULL ";
				
				if(!$rst = $this->ConexionBaseDatos($sql1))
					return false;
				
				if(!$rst->EOF)
				{				
					$datos['nombre_tercero'] = $rst->fields[0]."/".$rst->fields[1];
					$rst->MoveNext();
				}
				$rst->Close();
			}
			if(($datos['prefijo'] && $datos['factura_f']!= "" && $datos['nombre_tercero']) || empty($datos['prefijo']))
			{
				$sql  = "SELECT	nombre_tercero, ";
				$sql .= "				intervalo, ";
				$sql .= "		    cliente,";
				$sql .= "				saldo,";
				$sql .= "				valor_pendiente, ";
				$sql .= "				intervalo*30 AS diferencia ";
				$sql .= "FROM 	vista_cartera ";
				$sql .= "WHERE	empresa_id = '".$empresa."' ";
				if($datos['nombre_tercero'] != "" && $datos['nombre_tercero'] != "0")
				{
					$arreglo = explode("/",$datos['nombre_tercero']);

					$sql .= "AND		tipo_id_tercero = '".$arreglo[0]."' ";
					$sql .= "AND    tercero_id = '".$arreglo[1]."' ";
				}
			 	$sql .= "ORDER BY nombre_tercero,intervalo ";
			}
			return $sql;
		}
		/********************************************************************************
		* Funcion donde se obtinene la cartera no enviada de la empresa
		*********************************************************************************/
		function ObtenerCarteraNoRadicada($empresa,$datos)
		{
			$sql .= "SELECT TE.nombre_tercero AS empresa,";
			$sql .= "				TO_CHAR(FF.fecha_registro,'YYYY-MM') AS intervalo , ";
			$sql .= "				FF.tipo_id_tercero || ' '|| FF.tercero_id AS cliente, ";
			$sql .= "  			SUM(FF.saldo) AS saldo,  ";
			$sql .= "  			SUM(COALESCE(GL.valor_pendiente,0)) AS valor_pendiente, ";
			$sql .= "  			((FF.fecha_registro::date - NOW()::date) / 30)*30 AS diferencia ";
			$sql .= "FROM   fac_facturas FF LEFT JOIN  ";
			$sql .= "      	(	SELECT 	SUM(G.valor_pendiente) AS valor_pendiente,  ";
			$sql .= "      						G.prefijo,  ";
			$sql .= "      						G.factura_fiscal, ";
			$sql .= "      						G.empresa_id ";
			$sql .= "        	FROM		glosas G, ";
			$sql .= "        					fac_facturas F ";
			$sql .= "	     		WHERE		G.sw_estado <> '0'::bpchar ";
			$sql .= "					AND  		G.empresa_id = '".$empresa."' ";
			$sql .= "       	AND			G.prefijo = F.prefijo  ";
			$sql .= "       	AND			G.factura_fiscal = F.factura_fiscal  ";	
			$sql .= "       	AND			G.empresa_id = F.empresa_id ";	
			$sql .= "					AND    	F.estado = '0'::bpchar ";
			$sql .= "					AND    	F.saldo > 0 ";
			$sql .= "					AND    	F.fecha_vencimiento_factura IS NULL ";

			$sql .= "	     		GROUP BY 2,3,4) AS GL  ";
			$sql .= "       ON(	GL.prefijo = FF.prefijo AND ";
			$sql .= "       		GL.factura_fiscal = FF.factura_fiscal AND ";	
			$sql .= "       		GL.empresa_id = FF.empresa_id), ";	
			$sql .= "				terceros TE ";
			$sql .= "WHERE  FF.empresa_id = '".$empresa."' ";
			$sql .= "AND    FF.sw_clase_factura='1'::bpchar ";
			$sql .= "AND    FF.estado = '0'::bpchar ";
			$sql .= "AND    FF.saldo > 0 ";
			$sql .= "AND    FF.fecha_vencimiento_factura IS NULL ";
			$sql .= "AND		TE.tercero_id = FF.tercero_id ";
			$sql .= "AND		TE.tipo_id_tercero = FF.tipo_id_tercero ";
			
			if($datos['nombre_tercero'] != "" && $datos['nombre_tercero'] != "0")
			{
				$arreglo = explode("/",$datos['nombre_tercero']);

				$sql .= "AND		FF.tipo_id_tercero = '".$arreglo[0]."' ";
				$sql .= "AND    FF.tercero_id = '".$arreglo[1]."' ";
				$sql .= "AND		TE.tipo_id_tercero = '".$arreglo[0]."' ";
				$sql .= "AND    TE.tercero_id = '".$arreglo[1]."' ";
			}
			
			$sql .= "GROUP BY cliente,empresa,intervalo,diferencia ";
			$sql .= "ORDER BY empresa,intervalo ";
			
			return $sql;
		}
		/********************************************************************************
		* 
		* @params char $envio Indica que consulta se debe hacer si la cartera enviada o la
		*											no enviada
		* @params char $periodo Indica el periodo para el cual se sacara la cartera
		*********************************************************************************/
		function ConsultarCarteraPlanes($envio,$periodo,$empresa)
		{
			$sql = $this->ObtenerCarteraPlanes($envio,$periodo,$empresa);
			//$retorno = $this->ObtenerArrayCartera($datos,$empresa,$sql,$label="plan");
			$retorno = $this->ObtenerArrayCarteraReporte($datos,$empresa,$sql,$label="plan",$tipo="P");
			return $retorno;
		}
		/********************************************************************************
		* 
		* @params char $envio Indica que consulta se debe hacer si la cartera enviada o la
		*											no enviada
		* @params char $periodo Indica el periodo para el cual se sacara la cartera
		*********************************************************************************/
		function ConsultarCarteraPlanesReporte($envio,$periodo,$empresa)
		{
			$datos['pèriodo'] = $periodo;
			$sql = $this->ObtenerCarteraPlanes($envio,$periodo,$empresa);
			$retorno = $this->ObtenerArrayCarteraReporte($datos,$empresa,$sql,$label="plan");
			
			return $retorno;
		}
		/********************************************************************************
		* 
		*********************************************************************************/
		function ObtenerCarteraPlanes($envio,$periodo,$empresa)
		{	
			$sql .= "SELECT	FF.plan_descripcion, ";
			$sql .= "				'--' AS intervalo ,  ";
			$sql .= "				FF.plan_id,  ";
			$sql .= "				FF.saldo,  ";
			$sql .= "				COALESCE(GL.valor_pendiente,0) AS valor_pendiente,  ";
			$sql .= "				FF.diferencia  ";
			$sql .= "FROM		( ";
			$sql .= "					SELECT 	PL.plan_descripcion, ";
			$sql .= "									FF.plan_id,  ";
			$sql .= "									SUM(FF.saldo) AS saldo,  ";
			if($envio == '1')
				$sql .= "									(FF.fecha_vencimiento_factura::date - NOW()::date)/30 AS diferencia   ";
			else if($envio == '0')
				$sql .= "									(FF.fecha_registro::date - NOW()::date)/30 AS diferencia  ";
			
			$sql .= "					FROM 		fac_facturas FF, ";
			$sql .= "									planes PL ";
			$sql .= "					WHERE		FF.empresa_id = '".$empresa."'  ";
			$sql .= "					AND 		FF.sw_clase_factura='1'::bpchar ";
			$sql .= "					AND 		FF.estado = '0'::bpchar ";
			$sql .= "					AND 		FF.saldo > 0 ";
			$sql .= "					AND 		PL.plan_id = FF.plan_id ";
			if($envio == '1')
				$sql .= "					AND 	FF.fecha_vencimiento_factura IS NOT NULL  ";
			else if($envio == '0')
				$sql .= "					AND	 	FF.fecha_vencimiento_factura IS NULL  ";

				$sql .= "					GROUP BY PL.plan_descripcion,FF.plan_id,diferencia ";
			$sql .= "				) AS FF	LEFT JOIN ";
			$sql .= "				( SELECT 	SUM(G.valor_pendiente) AS valor_pendiente, ";
			$sql .= "									F.plan_id, ";
			if($envio == '1')
				$sql .= "									(F.fecha_vencimiento_factura::date - NOW()::date)/30 AS intervalo   ";
			else if($envio == '0')
				$sql .= "									(F.fecha_registro::date - NOW()::date)/30 AS intervalo  ";

			$sql .= "					FROM 		glosas G,";
			$sql .= "									fac_facturas F ";
			$sql .= "					WHERE 	G.sw_estado <> '0'::bpchar ";
			$sql .= "					AND			F.empresa_id = '".$empresa."'  ";
			$sql .= "					AND			G.prefijo = F.prefijo ";
			$sql .= "					AND			G.factura_fiscal = F.factura_fiscal ";
			$sql .= "					AND			G.empresa_id = F.empresa_id ";
			$sql .= "					AND     F.estado = '0'::bpchar ";
			$sql .= "					AND			G.valor_pendiente != 0 ";
			$sql .= "					AND			G.valor_pendiente IS NOT NULL ";
			
			if($envio == '1')
				$sql .= "					AND 	F.fecha_vencimiento_factura IS NOT NULL  ";
			else if($envio == '0')
				$sql .= "					AND	 	F.fecha_vencimiento_factura IS NULL  ";

			$sql .= "					GROUP BY  F.plan_id,intervalo ";
			$sql .= "				) AS GL ";
			$sql .= "				ON(	GL.plan_id = FF.plan_id AND ";
			$sql .= "						GL.intervalo = FF.diferencia) ";
			$sql .= "ORDER BY FF.plan_descripcion,FF.plan_id,FF.diferencia";
			
			return $sql;
		}
		/************************************************************************************
		* Funcion recursiva, donde se parte el vector y hace los llamados recursivos para 
		* ordenar el arreglo
		*
		* @params $ini primera posicion del arreglo a ordenar
		*					$fin ultima posicion del arreglo a ordenar
		*************************************************************************************/
	 	function Ordenar($ini,$fin)
	 	{	
	 		$p = 0;
	
			if ($ini < $fin)
			{
				$p = $this->ParticionarArreglo($ini, $fin);
				$this->Ordenar($ini, $p);			//llamada recursiva
				$this->Ordenar($p + 1, $fin);	//llamada recursiva
	 		}
	 		
		}
		/************************************************************************************
		* Funcion, donde se ordena el vector
		*
		* @params $ini primera posicion del arreglo a ordenar
		*					$fin ultima posicion del arreglo a ordenar
		*
		* @return $der posicion en la que se queda para hacer el ordenamiento de la siguiente 
		*							 parte del vector
		*************************************************************************************/
		function ParticionarArreglo($ini, $fin)
		{
			$pivote = $this->Arreglo[($ini + $fin) / 2]['saldo'];
			$izq = $ini - 1;
			$der = $fin + 1;
	
			while ($izq < $der) 
			{
				do 
				{
		    	$izq++;
	      }
	      while ($this->Arreglo[$izq]['saldo'] > $pivote);
	
	      do
	      {
		    	$der--;
	      }
	      while ($this->Arreglo[$der]['saldo'] < $pivote);
	           
	      if ($izq < $der) 
	      {
	      	$aux = $this->Arreglo[$izq];
		      $this->Arreglo[$izq] = $this->Arreglo[$der];
		      $this->Arreglo[$der] = $aux;
	      }
	    }
		  return $der;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerAnticipos($empresa)
		{
			$sql  = "SELECT CA.saldo, ";
			$sql .= "				CA.tipo_id_tercero||' '||CA.tercero_id ";
			$sql .= "FROM		rc_control_anticipos CA ";
			$sql .= "WHERE	CA.empresa_id = '".$empresa."' ";
			$sql .= "AND		CA.saldo > 0 ";
			
			$datos = array();
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]] =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
		
			return $datos;			
		}
		/********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*********************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
?>