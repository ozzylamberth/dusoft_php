<?php
  /*******************************************************************************
  * $Id: Cartera.class.php,v 1.9 2009/06/26 13:53:16 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.9 $ 
	* 
	* @autor Hugo F  Manrique 
  ********************************************************************************/
	class Cartera
	{
		var $Arreglo = array();
		
		function Cartera(){}
		/*********************************************************************************
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
		/*********************************************************************************
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
		/*********************************************************************************
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
		/*********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ConsultarCarteraClientesReporte($datos,$empresa)
		{
      $terceros = $this->ObtenerNombresTerceros();
      $facturacion = $this->ObtenerResumenCartera($datos,$empresa);
      //$facturacion = $this->ObtenerPagares($rango,$fecha,$datos['empresa_id'],$facturacion);
			$arreglo = $this->ObtenerRelacionAnticipos($datos,$empresa,$facturacion);
      $anticipos = $arreglo['anticipos'];
      $facturacion = $arreglo['facturas'];
      
      $datosc = array();
			$intervalos = array();
      $total = 0;
			foreach($facturacion as $key => $cartera)
			{
				foreach($cartera as $keyI => $detalle)
				{
					$periodos = array();
					foreach($detalle as $keyA => $dtl)
					{	
            if(($dtl['saldo']) != 0)
            {
              $total += $dtl['saldo'];
              $periodos[$keyA] = $dtl;
              $intervalos[$keyA] = $keyA;
            }
					}
					if(!empty($periodos))
						$datosc[$terceros[$key][$keyI]."<br>".$key." ".$keyI]['periodos'] = $periodos;
          
          if($anticipos[$key][$keyI]['saldoa'] != 0 )
          {
            $total -=$anticipos[$key][$keyI]['saldoa'];
            $datosc[$terceros[$key][$keyI]."<br>".$key." ".$keyI]['anticipos'] = $anticipos[$key][$keyI]['saldoa'];
          }	
        }
			}
			return array("cartera"=>$datosc,"intervalos"=>$intervalos,"total_cartera"=>$total);
		}
    /**
		* Funcion donde se seleccionan el nombre de los terceros que son clientes y 
		* tienen envios radicados para que se pueda filtrar por ellos en una busqueda  
		* 
		* @return array datos de tipo_id_terceros 
		*/
		function ObtenerNombresTerceros()
		{
			$sql	= "SELECT nombre_tercero, ";
			$sql .= "				tipo_id_tercero, ";
			$sql .= "				tercero_id ";
			$sql .= "FROM		terceros TE ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$nombre = array();
			while(!$rst->EOF)
			{
				$nombre["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"] = $rst->fields[0];
				$rst->MoveNext();
		  }
			$rst->Close();
						
			return $nombre;
		}
    /**
		* En esta funcion se realiza la consulta de la cartera de cada cliente, se 
		* realiza una evaluacion para determinar a que rango pertenecen los saldos y el .
		* valor pendiente
		*
		* @return boolean
		*/
		function ObtenerResumenCartera($datos,$empresa)
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
      
      $resumen = array();
			if(($datos['prefijo'] && $datos['factura_f']!= "" && $datos['nombre_tercero']) || empty($datos['prefijo']))
			{
        list($tipoId,$terceroId) = explode("/",$datos['nombre_tercero']);
        
        $sql  = "SELECT A.tipo_id_tercero, ";
        $sql .= "       A.tercero_id, ";
  			$sql .= "       CASE  WHEN (NOW()::date - B.fecha_vencimiento_factura::date )/30 >= 13 THEN 13 ";
        $sql .= "             WHEN (NOW()::date - B.fecha_vencimiento_factura::date )/30 BETWEEN 7 AND 12 THEN 7 ";
        $sql .= "             WHEN (NOW()::date - B.fecha_vencimiento_factura::date )/30 <= 0 THEN 0 ";
        $sql .= "             ELSE (NOW()::date - B.fecha_vencimiento_factura::date )/30 END AS intervalo, ";
        $sql .= "       A.empresa_id, ";
        $sql .= "       SUM(debitos)- SUM(creditos) AS saldo ";
        $sql .= "FROM   (";
        $sql .= "         SELECT  BTRIM(tercero_id) AS tercero_id,";
        $sql .= "                 tipo_id_tercero,";
        $sql .= "                 prefijo,";
        $sql .= "                 factura_fiscal,";
        $sql .= "                 empresa_id,";
        $sql .= "                 SUM(total_factura) - SUM(retencion) + SUM(total_nota_debito) AS debitos,";
        $sql .= "                 SUM(total_recibo) + SUM(total_nota_glosa) + SUM(total_nota_ajuste) + SUM(total_nota_credito) AS creditos ";
        $sql .= "         FROM    cartera.facturas_resumen";
        $sql .= "         WHERE   empresa_id = '".$empresa."'";
        if($datos['nombre_tercero'] != "" && $datos['nombre_tercero'] != "0")
  			{
  				$sql .= "         AND		  tipo_id_tercero = '".$tipoId."' ";
  				$sql .= "         AND     tercero_id = '".$terceroId."' ";
  			}
        $sql .= "         GROUP BY tercero_id,tipo_id_tercero,prefijo, factura_fiscal,empresa_id ";
        $sql .= "         HAVING  SUM(total_nota_anulacion) = 0 ";
        $sql .= "       ) AS A, ";
        $sql .= "       ( ";
        $sql .= "         SELECT  prefijo,  ";
        $sql .= "                 factura_fiscal, ";
        $sql .= "                 empresa_id, ";
        $sql .= "                 fecha_vencimiento_factura ";
        $sql .= "         FROM    fac_facturas ";
        $sql .= "         WHERE   empresa_id = '".$empresa."' ";
        $sql .= "         AND     sw_clase_factura = '1' ";
        $sql .= "         AND     fecha_vencimiento_factura IS NOT NULL ";
        if($datos['nombre_tercero'] != "" && $datos['nombre_tercero'] != "0")
  			{
  				$sql .= "         AND		  tipo_id_tercero = '".$tipoId."' ";
  				$sql .= "         AND     tercero_id = '".$terceroId."' ";
  			}
        
        $sql .= "          UNION ALL  ";
        $sql .= "          SELECT  prefijo,  ";
        $sql .= "                  factura_fiscal, ";
        $sql .= "                  empresa_id, ";
        $sql .= "                  fecha_vencimiento AS fecha_vencimiento_factura ";
        $sql .= "          FROM    facturas_externas ";
        $sql .= "          WHERE   empresa_id = '".$empresa."' ";
        if($datos['nombre_tercero'] != "" && $datos['nombre_tercero'] != "0")
  			{
  				$sql .= "         AND		  tipo_id_tercero = '".$tipoId."' ";
  				$sql .= "         AND     tercero_id = '".$terceroId."' ";
  			}
        $sql .= "       ) AS B ";
        $sql .= "WHERE  A.empresa_id = B.empresa_id ";
        $sql .= "AND    A.prefijo = B.prefijo ";
        $sql .= "AND    A.factura_fiscal = B.factura_fiscal ";
        $sql .= "GROUP BY A.empresa_id,A.tercero_id,A.tipo_id_tercero,intervalo; ";

        if(!$rst = $this->ConexionBaseDatos($sql))
          return false;
			
  			while(!$rst->EOF)
  			{
  				$resumen["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
  						
  				$rst->MoveNext();
  		  }
  			$rst->Close();
  			
			}
			return $resumen;
		}
		/*********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ConsultarCarteraClientesNoRadicadaReporte($datos,$empresa)
		{
      $terceros = $this->ObtenerNombresTerceros();
      $facturacion = $this->ObtenerResumenCarteraNoRadicada($empresa,$datos);
      
      $datosc = array();
			$intervalos = array();
      $total = 0;
			foreach($facturacion as $key => $cartera)
			{
				foreach($cartera as $keyI => $detalle)
				{
					$periodos = array();
					foreach($detalle as $keyA => $dtl)
					{	
            if(($dtl['saldo']) != 0)
            {
              $total += $dtl['saldo'];
              $periodos[$keyA] = $dtl;
              $intervalos[$keyA] = $keyA;
            }
					}
					if(!empty($periodos))
						$datosc[$terceros[$key][$keyI]."<br>".$key." ".$keyI]['periodos'] = $periodos;
        }
			}
      
			return array("cartera"=>$datosc,"intervalos"=>$intervalos,"total_cartera"=>$total);
		}
    /**
		* Funcion donde se obtinene la cartera no enviada de la empresa
		*/
		function ObtenerResumenCarteraNoRadicada($empresa,$datos)
		{
			list($tipoid,$terceroid) = explode("/",$datos['nombre_tercero']);

      $sql  = "SELECT A.tipo_id_tercero, ";
      $sql .= "       A.tercero_id, ";
			$sql .= "       CASE  WHEN (NOW()::date - B.fecha_registro::date )/30 >= 13 THEN 13 ";
      $sql .= "             WHEN (NOW()::date - B.fecha_registro::date )/30 BETWEEN 7 AND 12 THEN 7 ";
      $sql .= "             WHEN (NOW()::date - B.fecha_registro::date )/30 <= 0 THEN 0 ";
      $sql .= "             ELSE (NOW()::date - B.fecha_registro::date )/30 END AS intervalo, ";
      $sql .= "       A.empresa_id, ";
      $sql .= "       SUM(debitos)- SUM(creditos) AS saldo ";
      $sql .= "FROM   (";
      $sql .= "         SELECT  BTRIM(tercero_id) AS tercero_id,";
      $sql .= "                 tipo_id_tercero,";
      $sql .= "                 prefijo,";
      $sql .= "                 factura_fiscal,";
      $sql .= "                 empresa_id,";
      $sql .= "                 SUM(total_factura) - SUM(retencion) + SUM(total_nota_debito) AS debitos,";
      $sql .= "                 SUM(total_recibo) + SUM(total_nota_glosa) + SUM(total_nota_ajuste) + SUM(total_nota_credito) AS creditos ";
      $sql .= "         FROM    cartera.facturas_resumen";
      $sql .= "         WHERE   empresa_id = '".$empresa."'";
      if($datos['nombre_tercero'] != "" && $datos['nombre_tercero'] != "0")
			{
				$sql .= "         AND		  tipo_id_tercero = '".$tipoid."' ";
				$sql .= "         AND     tercero_id = '".$terceroid."' ";
			}
      $sql .= "         GROUP BY tercero_id,tipo_id_tercero,prefijo, factura_fiscal,empresa_id ";
      $sql .= "         HAVING  SUM(total_nota_anulacion) = 0 ";
      $sql .= "       ) AS A, ";
      $sql .= "       ( ";
      $sql .= "         SELECT  prefijo,  ";
      $sql .= "                 factura_fiscal, ";
      $sql .= "                 empresa_id, ";
      $sql .= "                 fecha_registro ";
      $sql .= "         FROM    fac_facturas ";
      $sql .= "         WHERE   empresa_id = '".$empresa."' ";
      $sql .= "         AND     sw_clase_factura = '1' ";
      $sql .= "         AND     fecha_vencimiento_factura IS NULL ";
      if($datos['nombre_tercero'] != "" && $datos['nombre_tercero'] != "0")
			{
				$sql .= "         AND		  tipo_id_tercero = '".$tipoid."' ";
				$sql .= "         AND     tercero_id = '".$terceroid."' ";
			}
      $sql .= "       ) AS B ";
      $sql .= "WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "AND    A.prefijo = B.prefijo ";
      $sql .= "AND    A.factura_fiscal = B.factura_fiscal ";
      $sql .= "GROUP BY A.empresa_id,A.tercero_id,A.tipo_id_tercero,intervalo; ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
    
      while(!$rst->EOF)
      {
        $resumen["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
            
        $rst->MoveNext();
      }
      $rst->Close();
      
			return $resumen;
		}
		/*********************************************************************************
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
										else if($a <= 360 )
											{
												$periodos[7]['saldo'] += $rst->fields[3];
												$saldo += $rst->fields[3];
												$periodos[7]['valor_pendiente'] += $rst->fields[4];
												$intervalos[7] = "181 - 360 DÍAS";
											}
                      else if($a > 360 )
  											{
  												$periodos[8]['saldo'] += $rst->fields[3];
  												$saldo += $rst->fields[3];
  												$periodos[8]['valor_pendiente'] += $rst->fields[4];
  							
  												$intervalos[8] = "MAS DE 360 DÍAS";
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
		/*********************************************************************************
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
		/*********************************************************************************
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
		/*********************************************************************************
		* 
		* @params char $envio Indica que consulta se debe hacer si la cartera enviada o la
		*											no enviada
		* @params char $periodo Indica el periodo para el cual se sacara la cartera
		*********************************************************************************/
		function ConsultarCarteraPlanes($envio,$periodo,$empresa)
		{
			$retorno = $this->ObtenerCarteraPlanes($envio,$periodo,$empresa);
			//$retorno = $this->ObtenerArrayCartera($datos,$empresa,$sql,$label="plan");
			//$retorno = $this->ObtenerArrayCarteraReporte($datos,$empresa,$sql,$label="plan",$tipo="P");
			return $retorno;
		}
		/*********************************************************************************
		* 
		* @params char $envio Indica que consulta se debe hacer si la cartera enviada o la
		*											no enviada
		* @params char $periodo Indica el periodo para el cual se sacara la cartera
		*********************************************************************************/
		function ConsultarCarteraPlanesReporte($envio,$periodo,$empresa)
		{
			$datos['pèriodo'] = $periodo;
			$retorno = $this->ObtenerCarteraPlanes($envio,$periodo,$empresa);
			//$retorno = $this->ObtenerArrayCarteraReporte($datos,$empresa,$sql,$label="plan");
			
			return $retorno;
		}
		/*********************************************************************************
		* 
		*********************************************************************************/
		function ObtenerCarteraPlanes($envio,$periodo,$empresa)
		{	
			$sql .= "SELECT	plan_id, ";
      $sql .= "       plan_descripcion,";
      $sql .= "       intervalo,";
      $sql .= "       SUM(saldo) AS saldo,";
      $sql .= "       SUM(valor_pendiente) AS valor_pendiente ";
      $sql .= "FROM   (";
			$sql .= "         SELECT	FF.plan_descripcion, ";
			$sql .= "				          FF.plan_id,  ";
			$sql .= "				          FF.saldo,  ";
			$sql .= "				          COALESCE(GL.valor_pendiente,0) AS valor_pendiente,  ";
			$sql .= "				          CASE WHEN FF.diferencia >= 13 THEN 13 ";
      $sql .= "                      WHEN FF.diferencia BETWEEN 7 AND 12 THEN 7 ";
      $sql .= "                      WHEN FF.diferencia <= 0 THEN 0 ";
      $sql .= "                      ELSE FF.diferencia END AS intervalo ";
			$sql .= "         FROM	( ";
			$sql .= "					        SELECT 	PL.plan_descripcion, ";
			$sql .= "									        FF.plan_id,  ";
			$sql .= "									        SUM(FF.saldo) AS saldo,  ";
			if($envio == '1')
				$sql .= "									      (NOW()::date - FF.fecha_vencimiento_factura::date)/30 AS diferencia   ";
			else if($envio == '0')
				$sql .= "									      (NOW()::date - FF.fecha_registro::date)/30 AS diferencia  ";
			
			$sql .= "					        FROM 		fac_facturas FF, ";
			$sql .= "									        planes PL ";
			$sql .= "					        WHERE		FF.empresa_id = '".$empresa."'  ";
			$sql .= "					        AND 		FF.sw_clase_factura='1'::bpchar ";
			$sql .= "					        AND 		FF.estado = '0'::bpchar ";
			$sql .= "					        AND 		FF.saldo > 0 ";
			$sql .= "					        AND 		PL.plan_id = FF.plan_id ";
			if($envio == '1')
				$sql .= "					        AND 	FF.fecha_vencimiento_factura IS NOT NULL  ";
			else if($envio == '0')
				$sql .= "					        AND	 	FF.fecha_vencimiento_factura IS NULL  ";

				$sql .= "					        GROUP BY PL.plan_descripcion,FF.plan_id,diferencia ";
			$sql .= "					      ) AS FF	LEFT JOIN ";
			$sql .= "				        ( SELECT 	SUM(G.valor_pendiente) AS valor_pendiente, ";
			$sql .= "									        F.plan_id, ";
			if($envio == '1')
				$sql .= "									      (F.fecha_vencimiento_factura::date - NOW()::date)/30 AS intervalo   ";
			else if($envio == '0')
				$sql .= "									      (F.fecha_registro::date - NOW()::date)/30 AS intervalo  ";

			$sql .= "					        FROM 		glosas G,";
			$sql .= "									        fac_facturas F ";
			$sql .= "					        WHERE 	G.sw_estado <> '0'::bpchar ";
			$sql .= "					        AND			F.empresa_id = '".$empresa."'  ";
			$sql .= "					        AND			G.prefijo = F.prefijo ";
			$sql .= "					        AND			G.factura_fiscal = F.factura_fiscal ";
			$sql .= "					        AND			G.empresa_id = F.empresa_id ";
			$sql .= "					        AND     F.estado = '0'::bpchar ";
			$sql .= "					        AND			G.valor_pendiente != 0 ";
			$sql .= "					        AND			G.valor_pendiente IS NOT NULL ";
			
			if($envio == '1')
				$sql .= "					        AND 	F.fecha_vencimiento_factura IS NOT NULL  ";
			else if($envio == '0')
				$sql .= "					        AND	 	F.fecha_vencimiento_factura IS NULL  ";

			$sql .= "					        GROUP BY  F.plan_id,intervalo ";
			$sql .= "				        ) AS GL ";
			$sql .= "				        ON(	GL.plan_id = FF.plan_id AND ";
			$sql .= "						        GL.intervalo = FF.diferencia) ";
			$sql .= "         ORDER BY FF.plan_descripcion,FF.plan_id,FF.diferencia";
			$sql .= "       ) AS A ";
      $sql .= "GROUP BY plan_id,plan_descripcion,intervalo ";
			
 			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

      $total_cartera = 0;
      $datos = array();
      $intervalos = array();
      while(!$rst->EOF)
			{
        $datos[$rst->fields[0]." ".$rst->fields[1]][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
        $datos[$rst->fields[0]." ".$rst->fields[1]]['plan_id'] = $rst->fields[0];
        $datos[$rst->fields[0]." ".$rst->fields[1]]['plan_descripcion'] = $rst->fields[1];
        
        $intervalos[$rst->fields[2]] = 1;
        $total_cartera += $rst->fields[3];
        $rst->MoveNext();
      }
			return array("cartera"=>$datos,"intervalos"=>$intervalos,"total_cartera"=>$total_cartera);
		}
		/*************************************************************************************
		* Funcion recursiva, donde se parte el vector y hace los llamados recursivos para 
		* ordenar el arreglo
		*
		* @params $ini primera posicion del arreglo a ordenar
		*					$fin ultima posicion del arreglo a ordenar
		*/
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
		/*************************************************************************************
		* Funcion, donde se ordena el vector
		*
		* @params $ini primera posicion del arreglo a ordenar
		*					$fin ultima posicion del arreglo a ordenar
		*
		* @return $der posicion en la que se queda para hacer el ordenamiento de la siguiente 
		*							 parte del vector
		*/
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
		/*********************************************************************************
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
    /**
		*
		* @return boolean
		*/
		function ObtenerRelacionAnticipos($datos,$empresa,$facturacion)
		{
      $sql  = "         SELECT COALESCE(SUM(RC.total_abono),0) AS saldoa, ";
      $sql .= "                RC.tipo_id_tercero,";
      $sql .= "                RC.tercero_id ";
			$sql .= "         FROM	 recibos_caja RC, ";
			$sql .= "				         rc_detalle_tesoreria_conceptos RS ";
			$sql .= "         WHERE	 RC.empresa_id = '".$empresa."' ";
			$sql .= "         AND		RC.estado = '2' ";
			$sql .= "         AND		RC.empresa_id = RS.empresa_id "; 	
			$sql .= "         AND		RC.recibo_caja = RS.recibo_caja ";
			$sql .= "         AND		RC.prefijo = RS.prefijo ";
			$sql .= "         AND		RS.concepto_id = 'C013' ";
      $sql .= "         AND		RC.sw_recibo_tesoreria = '1' ";
      if($datos['nombre_tercero'] != "" && $datos['nombre_tercero'] != "0")
			{
				list($tipoId,$terceroId) = explode("/",$datos['nombre_tercero']);
				$sql .= "AND		RC.tipo_id_tercero = '".$tipoId."' ";
				$sql .= "AND    RC.tercero_id = '".$terceroId."' ";
			}
      $sql .= "         GROUP BY 2,3 ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$resumen = array();
			while(!$rst->EOF)
			{
				$resumen["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"]['saldoa'] =  $rst->fields[0];
				$facturacion["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"][0] = $facturacion["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"][0];
        $rst->MoveNext();
		  }
			$rst->Close();
      
			$sql  = "         SELECT COALESCE(SUM(RC.total_abono),0) AS saldoa, ";
      $sql .= "                RC.tipo_id_tercero,";
      $sql .= "                RC.tercero_id ";
			$sql .= "         FROM	 recibos_caja RC,  ";
			$sql .= "			           rc_tipos_documentos RD  ";
			$sql .= "         WHERE  RC.empresa_id = '".$empresa."'  ";
			$sql .= "         AND		 RC.estado = '2'::bpchar ";
			$sql .= "         AND		 RC.sw_recibo_tesoreria = '1' ";
			$sql .= "         AND		 RD.sw_cruzar_anticipos = '1' ";
			$sql .= "         AND		 RD.rc_tipo_documento = RC.rc_tipo_documento ";
      if($datos['nombre_tercero'] != "" && $datos['nombre_tercero'] != "0")
			{
				list($tipoId,$terceroId) = explode("/",$datos['nombre_tercero']);
				$sql .= "AND		RC.tipo_id_tercero = '".$tipoId."' ";
				$sql .= "AND    RC.tercero_id = '".$terceroId."' ";
			}
      $sql .= "         GROUP BY 2,3 ";
      
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
        $resumen["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"]['saldoa'] = $resumen["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"]['saldoa'] - $rst->fields[0];
				if(empty($resumen["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"]))
					$facturacion["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"][0] = $facturacion["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"][0];
        
        $rst->MoveNext();
		  }
			$rst->Close();
			
			return array("anticipos"=>$resumen,"facturas"=>$facturacion);	
		}
    /**
		* Retorna las empresas a las cuales tiene permisos el usuario de acceder
		*
    * @param integer $usuario Identificador del usuario
		* @access public
		*/
		function BuscarEmpresasUsuario($usuario)
		{
			$sql .= "SELECT A.empresa_id AS empresa_id, ";
			$sql .= "				B.razon_social AS razon_social, ";
			$sql .= "				A.centro_utilidad AS centro_utilidad, ";
			$sql .= "				C.descripcion AS descripcion_centro_utilidad ";
			$sql .= "FROM 	userpermisos_cartera AS A, ";
			$sql .= "				empresas AS B, ";
			$sql .= "				centros_utilidad AS C ";
			$sql .= "WHERE 	A.usuario_id = ".$usuario." ";
			$sql .= "AND 		A.empresa_id = B.empresa_id ";
			$sql .= "AND 		A.centro_utilidad=C.centro_utilidad ";
			$sql .= "AND 		A.empresa_id=C.empresa_id;";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while(!$rst->EOF)
			{
				$empresas[$rst->fields[1]][$rst->fields[3]]=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $empresas;
		}
		/**
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug = true;
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