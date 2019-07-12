<?php
  /**
  * $Id: CarteraC.class.php,v 1.11 2009/02/12 20:14:13 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.11 $ 
	* 
	* @autor Hugo F  Manrique 
  */
	class CarteraRadicada extends ConexionBD
	{
		var $Arreglo = array();
		/**
    * Constructor de la clase
    */
		function CarteraRadicada(){}
		/*
		*
		* @return boolean
		*/
    function ObtenerReporte($datos,$opcion)
		{
   
			$f = explode("/",$datos['fecha']);
			$rango = $f[2].$f[1];
     $periodo=$datos['periodo'];
  
      $fecha = $f[2]."-".$f[1]."-".$f[0];
			$facturacion = array();
      $facturacion = $this->ObtenerResumenCartera($periodo,$rango,$fecha,$datos['empresa_id'],$opcion,$datos);
      $pendientes = $this->ObtenerPendientesCartera($periodo,$rango,$fecha,$datos['empresa_id'],$opcion,$datos);
      if($opcion == "R")
      {
        if(!$datos['nombre_tercero'])
          $facturacion = $this->ObtenerPagares($periodo,$rango,$fecha,$datos['empresa_id'],$facturacion);
			
        $rst = $this->ObtenerAnticipos($fecha,$datos['empresa_id'],$facturacion,$datos);
        $anticipos = $rst['anticipos'];
        $facturacion = $rst['facturacion'];
        $rc_anticipos = $this->ObtenerRecibosAnticipos($fecha,$datos['empresa_id'],$datos);
      }
      $terceros = $this->ObtenerNombresTerceros($datos);
			
      $datosc = array();
			$intervalos = array();
    
      $terceros['-']['-'] = "PAGARES PARTICULARES";
     
      $saldo = 0;
			foreach($facturacion as $key => $cartera)
			{
				foreach($cartera as $keyI => $detalle)
				{
					$periodos = array();
					foreach($detalle as $keyA => $dtl)
					{
            $dtl['valor_pendiente'] = $pendientes[$key][$keyI][$keyA]['valor_pendiente'];
            $saldo += $dtl['total_intervalo'];
            if($dtl['total_intervalo'] != 0)
            {
              $periodos[$keyA] = $dtl;
              $intervalos[$keyA] = $keyA;
              
             
            }
					}
          
       
					if(!empty($periodos))
          {
						$datosc[$terceros[$key][$keyI]."<br>".$key." ".$keyI]['periodos'] = $periodos;
						$datosc[$terceros[$key][$keyI]."<br>".$key." ".$keyI]['cliente_id'] = $key." ".$keyI;
          }
          
          $vanticipos = $anticipos[$key][$keyI]['saldo'] - $rc_anticipos[$key][$keyI]['saldo'];
          
          if($vanticipos != 0 && ($rc_anticipos[$key][$keyI]['saldo'] != 0 || $anticipos[$key][$keyI]['saldo'] != 0 ))
          {
            $saldo += ($anticipos[$key][$keyI]['saldo'] - $rc_anticipos[$key][$keyI]['saldo']);
            $datosc[$terceros[$key][$keyI]."<br>".$key." ".$keyI]['anticipos'] = $anticipos[$key][$keyI]['saldo'];
            $datosc[$terceros[$key][$keyI]."<br>".$key." ".$keyI]['descargo'] = $rc_anticipos[$key][$keyI]['saldo'];
          }	
        }
			}
      if($datos['ordenar_por'] != "1")
        ksort($datosc);
        
			return array("cartera"=>$datosc,"intervalos"=>$intervalos,"total_cartera"=>$saldo);
		}
    /*
		*
		* @return boolean
		*/
    function ObtenerReporteCp($datos,$opcion)
		{
			$f = explode("/",$datos['fecha']);
			$rango = $f[2].$f[1];
     $periodo=$datos['periodo'];
  
      $fecha = $f[2]."-".$f[1]."-".$f[0];
			$facturacion = array();
      $facturacion = $this->ObtenerResumenCartera($periodo,$rango,$fecha,$datos['empresa_id'],$opcion,$datos);
      if($opcion == "R")
      {
        $facturacion = $this->ObtenerPagares($periodo,$rango,$fecha,$datos['empresa_id'],$facturacion);
        $rst = $this->ObtenerAnticipos($fecha,$datos['empresa_id'],$facturacion,$datos);
        $anticipos = $rst['anticipos'];
        $facturacion = $rst['facturacion'];
        $rc_anticipos = $this->ObtenerRecibosAnticipos($fecha,$datos['empresa_id'],$datos);
      }
			
      $datosc = array();
      $datosa = array();
			$intervalos = array();
      
      $saldo = 0;
			foreach($facturacion as $key => $cartera)
			{
				foreach($cartera as $keyI => $detalle)
				{
					$periodos = array();
					foreach($detalle as $keyA => $dtl)
					{
            $dtl['valor_pendiente'] = $pendientes[$key][$keyI][$keyA]['valor_pendiente'];
            
            if($dtl['total_intervalo'] != 0)
            {
              $datosc[$keyA]['total_intervalo'] += $dtl['total_intervalo'];
            }
					}
        }
			}
      
      $datosa['descargo'] = 0;
      $datosa['anticipos'] = 0;
      foreach($anticipos as $k1 => $dtl1 )
      {
        foreach($dtl1 as $k2 => $dtl2)
        {
          $datosa['anticipos'] += $dtl2['saldo'];
        }	
      }
      
      foreach($rc_anticipos as $k1 => $dtl1 )
      {
        foreach($dtl1 as $k2 => $dtl2)
        {
          $datosa['descargo'] += $dtl2['saldo'];
        }	
      }
      
      ksort($datosc);  
			return array("cartera"=>$datosc,"anticipos"=>$datosa);
		}
    /*
		* Funcion donde se elabora el arreglo de la cartera por plan
    *
    * @param array $datos Arreglo de datos de los filtros
    * @param string $opcion Indica cual cartera se buscara R->radicada N->no radicada
    *
		* @return boolean
		*/
    function ObtenerReportePlanes($datos,$opcion)
		{
      $f = explode("/",$datos['fecha']);
			$rango = $f[2].$f[1];
      $fecha = $f[2]."-".$f[1]."-".$f[0];
			$facturacion = array();
      
      $pendien = $this->ObtenerPendientesCarteraPlanes($rango,$fecha,$datos['empresa_id'],$opcion);
			$cartera = $this->ObtenerResumenCarteraPlanes($rango,$fecha,$datos['empresa_id'],$opcion,$datos);
      
      $datosc = $cartera['datos'];
			$intervalos = $cartera['intervalos'];
			$total_cartera = $cartera['total_cartera'];
      
      foreach($pendien as $k1 => $dtl1)
      {
        foreach($dtl1 as $k2 => $dtl2)
        {
          foreach($dtl2 as $k3 => $dtl3)
          {
            $datosc[$k1][$k2][$k2]['valor_pendiente'] = $dtl3['valor_pendiente'];
          }
        }
      }
      
      ksort($intervalos);
        
			return array("cartera"=>$datosc,"intervalos"=>$intervalos,"total_cartera"=>$total_cartera);
		}
    /*
		* Funcion donde se elabora el arreglo de la cartera por tipo de entidad
    *
    * @param array $datos Arreglo de datos de los filtros
    * @param string $opcion Indica cual cartera se buscara R->radicada N->no radicada
    *
		* @return boolean
		*/
    function ObtenerReporteTipoEntidad($datos,$opcion)
		{
      $f = explode("/",$datos['fecha']);
			$rango = $f[2].$f[1];
     $periodo=$datos['periodo'];
  
      $fecha = $f[2]."-".$f[1]."-".$f[0];
			$facturacion = array();
      
      $datos = $this->ObtenerResumenCarteraTipoEntidad($periodo,$rango,$fecha,$datos['empresa_id'],$opcion,$datos);
			if($opcion == "R")
      {
        $rst = $this->ObtenerAnticipos($fecha,$datos['empresa_id'],$datosc,$datos);
        $anticipos = $rst['anticipos'];
        $rc_anticipos = $this->ObtenerRecibosAnticipos($fecha,$datos['empresa_id'],$datos);
      }
      $datosc = $datos['datos'];
      $intervalos = $datos['intervalos'];
      $total_cartera = $datos['total_cartera'];
      
      $datosa = array();
      $datosa['descargo'] = 0;
      $datosa['anticipos'] = 0;
      foreach($anticipos as $k1 => $dtl1 )
      {
        foreach($dtl1 as $k2 => $dtl2)
        {
          $datosa['anticipos'] += $dtl2['saldo'];
        }	
      }
      
      foreach($rc_anticipos as $k1 => $dtl1 )
      {
        foreach($dtl1 as $k2 => $dtl2)
        {
          $datosa['descargo'] += $dtl2['saldo'];
        }	
      }
      //ksort($datosc);
      ksort($intervalos);
     
			return array("cartera"=>$datosc,"anticipos"=>$datosa,"intervalos"=>$intervalos,"total_cartera"=>$total_cartera);
		}
    /**
    * Funcion donde se hace la consulta de la cartera por tipo de entidad
    *
    * @param integer $rango Identificador del rango por el cual se va a filtrar
    * @param date $fecha1 Fecha para la seleccion de las facturas
    * @param string $empresa Identificador de la empresa
    * @param string $opcion Indica que tipo de cartera se necesita R->radicada o N->no radicada
    *                       si no se especifica se trae ambas
    * @param array $filtros Arreglo de datos con los filtros de la consulta
    *
    * @return mixed
    */
    function ObtenerResumenCarteraTipoEntidad($periodo,$rango,$fecha1,$empresa,$opcion,$filtros)
    { 
	  $sql  = "SELECT  TC.descripcion,";
      $sql .= "        X.intervalo, ";
      $sql .= "        SUM(X.debitos) - SUM(credito1) - SUM(credito2) AS total_intervalo ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  B.plan_id, ";
      $sql .= "                 A.intervalo, ";
      $sql .= "                 SUM(A.total_factura) + SUM(A.total_nota_debito) AS debitos,";
      $sql .= "                 SUM(A.retencion) + SUM(A.total_recibo) AS credito1,";
      $sql .= "                 SUM(A.total_nota_glosa)+ SUM(A.total_nota_ajuste) + SUM(A.total_nota_credito) AS credito2 ";
      $sql .= "         FROM    (";
      $sql .= "                   SELECT  prefijo,";
      $sql .= "                           factura_fiscal,";
      $sql .= "                           empresa_id,";
      $sql .= "                           CASE  WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 >= 13 THEN 13 ";
      $sql .= "                                 WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 BETWEEN 7 AND 12 THEN 7 ";
      $sql .= "                                 WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 <= 0 THEN 0 ";
      $sql .= "                                 ELSE ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 END AS intervalo, ";
      $sql .= "                           SUM(total_factura) AS total_factura,";
      $sql .= "                           SUM(retencion) AS retencion,";
      $sql .= "                           SUM(total_nota_debito) AS total_nota_debito,";
      $sql .= "                           SUM(total_recibo) AS total_recibo,";
      $sql .= "                           SUM(total_nota_glosa) AS total_nota_glosa,";
      $sql .= "                           SUM(total_nota_ajuste) AS total_nota_ajuste,";
      $sql .= "                           SUM(total_nota_credito) AS total_nota_credito ";
      $sql .= "                    FROM   cartera.facturas_resumen";
      $sql .= "                    WHERE  empresa_id = '".$empresa."'";
      $sql .= "                    AND    intervalo <= ".$rango." ";
      $sql .= "                    GROUP BY prefijo, factura_fiscal,empresa_id ";
      $sql .= "                    HAVING  SUM(total_nota_anulacion) = 0 ";
      $sql .= "                 ) AS A, ";
      $sql .= "                 ( ";
      $sql .= "                   SELECT  prefijo,  ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           empresa_id, ";
      $sql .= "                           plan_id ";
      $sql .= "                   FROM    fac_facturas ";
      $sql .= "                   WHERE   empresa_id = '".$empresa."' ";
      //$sql .= "                   AND     fecha_registro::date <= '".$fecha1."'::date ";
      $sql .= "                   AND     sw_clase_factura = '1' ";
      
      if($opcion == "R")
        $sql .= "                   AND     fecha_vencimiento_factura IS NOT NULL ";
      else if($opcion == "N")
        $sql .= "                   AND     fecha_vencimiento_factura IS NULL ";
      
      if($opcion != "N")
      {
        $sql .= "                 UNION ALL ";
        $sql .= "                 SELECT  prefijo,  ";
        $sql .= "                         factura_fiscal, ";
        $sql .= "                         empresa_id, ";
        $sql .= "                         plan_id ";
        $sql .= "                 FROM    facturas_externas ";
        $sql .= "                 WHERE   empresa_id = '".$empresa."' ";
        //$sql .= "                 AND     fecha_registro::date <= '".$fecha1."'::date ";
      }
      $sql .= "                 ) AS B ";
      $sql .= "         WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "         AND    A.prefijo = B.prefijo ";
      $sql .= "         AND    A.factura_fiscal = B.factura_fiscal ";
      $sql .= "         AND    B.plan_id IS NOT NULL ";
      $sql .= "         GROUP BY B.plan_id, A.intervalo ";
      $sql .= "        ) AS X, ";
      $sql .= "        planes PL, ";
      $sql .= "        tipos_cliente TC  ";
      $sql .= "WHERE   X.plan_id = PL.plan_id  ";
      $sql .= "AND     TC.tipo_cliente = PL.tipo_cliente  ";
	  if($periodo!='X' && (!empty($periodo)))
      {
        
       $sql .= " and  intervalo='".$periodo."'  ";
          
      }
      $sql .= "GROUP BY TC.descripcion,X.intervalo  ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $saldo = 0;
      $datos = array();
      $intervalos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
        $intervalos[$rst->fields[1]] = 1;
        $saldo += $rst->fields[2];
        $rst->MoveNext();
      }
      
      $rst->Close();
      return array("datos"=>$datos,"intervalos"=>$intervalos,"total_cartera"=>$saldo);
    }
    /**
    * Funcion donde se hace la consulta de la cartera por planes
    *
    * @param integer $rango Identificador del rango por el cual se va a filtrar
    * @param date $fecha1 Fecha para la seleccion de las facturas
    * @param string $empresa Identificador de la empresa
    * @param string $opcion Indica que tipo de cartera se necesita R->radicada o N->no radicada
    *                       si no se especifica se trae ambas
    * @param array $filtros Arreglo de datos con los filtros de la consulta
    *
    * @return mixed
    */
    function ObtenerResumenCarteraPlanes($rango,$fecha1,$empresa,$opcion,$filtros)
    { 
      $sql  = "SELECT  PL.plan_id,";
      $sql .= "        PL.plan_descripcion, ";
      $sql .= "        X.intervalo, ";
      $sql .= "        SUM(X.debitos) - SUM(credito1) - SUM(credito2) AS total_intervalo ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  B.plan_id, ";
      $sql .= "                 A.intervalo, ";
      $sql .= "                 SUM(A.total_factura) + SUM(A.total_nota_debito) AS debitos,";
      $sql .= "                 SUM(A.retencion) + SUM(A.total_recibo) AS credito1,";
      $sql .= "                 SUM(A.total_nota_glosa)+ SUM(A.total_nota_ajuste) + SUM(A.total_nota_credito) AS credito2 ";
      $sql .= "         FROM    (";
      $sql .= "                   SELECT  prefijo,";
      $sql .= "                           factura_fiscal,";
      $sql .= "                           empresa_id,";
      $sql .= "                           CASE  WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 >= 13 THEN 13 ";
      $sql .= "                                 WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 BETWEEN 7 AND 12 THEN 7 ";
      $sql .= "                                 WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 <= 0 THEN 0 ";
      $sql .= "                                 ELSE ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 END AS intervalo, ";
      $sql .= "                           SUM(total_factura) AS total_factura,";
      $sql .= "                           SUM(retencion) AS retencion,";
      $sql .= "                           SUM(total_recibo) AS total_recibo,";
      $sql .= "                           SUM(total_nota_debito) AS total_nota_debito,";
      $sql .= "                           SUM(total_nota_glosa) AS total_nota_glosa,";
      $sql .= "                           SUM(total_nota_ajuste) AS total_nota_ajuste,";
      $sql .= "                           SUM(total_nota_credito) AS total_nota_credito ";
      $sql .= "                    FROM   cartera.facturas_resumen";
      $sql .= "                    WHERE  empresa_id = '".$empresa."'";
      $sql .= "                    AND    intervalo <= ".$rango." ";
      $sql .= "                    GROUP BY prefijo, factura_fiscal,empresa_id ";
      $sql .= "                    HAVING  SUM(total_nota_anulacion) = 0 ";
      $sql .= "                 ) AS A, ";
      $sql .= "                 ( ";
      $sql .= "                   SELECT  prefijo,  ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           empresa_id, ";
      $sql .= "                           plan_id ";
      $sql .= "                   FROM    fac_facturas ";
      $sql .= "                   WHERE   empresa_id = '".$empresa."' ";
      $sql .= "                   AND     fecha_registro::date <= '".$fecha1."'::date ";
      $sql .= "                   AND     sw_clase_factura = '1' ";
      
      if($opcion == "R")
        $sql .= "                   AND     fecha_vencimiento_factura IS NOT NULL ";
      else if($opcion == "N")
        $sql .= "                   AND     fecha_vencimiento_factura IS NULL ";
      
      if($opcion != "N")
      {
        $sql .= "                 UNION ALL ";
        $sql .= "                 SELECT  prefijo,  ";
        $sql .= "                         factura_fiscal, ";
        $sql .= "                         empresa_id, ";
        $sql .= "                         plan_id ";
        $sql .= "                 FROM    facturas_externas ";
        $sql .= "                 WHERE   empresa_id = '".$empresa."' ";
        $sql .= "                 AND     fecha_registro::date <= '".$fecha1."'::date ";
      }
      $sql .= "                 ) AS B ";
      $sql .= "         WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "         AND    A.prefijo = B.prefijo ";
      $sql .= "         AND    A.factura_fiscal = B.factura_fiscal ";
      $sql .= "         AND    B.plan_id IS NOT NULL ";
      $sql .= "         GROUP BY B.plan_id, A.intervalo ";
      $sql .= "        ) AS X, ";
      $sql .= "        planes PL ";
      $sql .= "WHERE   X.plan_id = PL.plan_id  ";
      $sql .= "GROUP BY PL.plan_id,PL.plan_descripcion,X.intervalo  ";
      $sql .= "ORDER BY PL.plan_descripcion ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $saldo = 0;
      $datos = array();
      $intervalos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][$rst->fields[1]][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
        $intervalos[$rst->fields[2]] = 1;
        $saldo += $rst->fields[3];
        $rst->MoveNext();
      }
      
      $rst->Close();
      return array("datos"=>$datos,"intervalos"=>$intervalos,"total_cartera"=>$saldo);
    }
    /**
    *
    */
    function ObtenerResumenCartera($periodo,$rango,$fecha1,$empresa,$opcion,$filtros,$tipoFac = "T")
    {
      list($tipoId,$terceroId) = explode("/",$filtros['nombre_tercero']);
     
      $sql  = "SELECT * ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  A.tipo_id_tercero, ";
      $sql .= "                 A.tercero_id, ";
      $sql .= "                 A.intervalo, ";
      if($tipoFac != "T" )
      $sql .= "                 B.plan_id, ";
      $sql .= "                 SUM(debitos)- SUM(creditos) AS total_intervalo ";
      $sql .= "         FROM    (";
      $sql .= "                   SELECT  BTRIM(tercero_id) AS tercero_id,";
      $sql .= "                           tipo_id_tercero,";
      $sql .= "                           prefijo,";
      $sql .= "                           factura_fiscal,";
      $sql .= "                           empresa_id,";
      $sql .= "                           CASE  WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 >= 13 THEN 13 ";
      $sql .= "                                 WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 BETWEEN 7 AND 12 THEN 7 ";
      $sql .= "                                 WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 <= 0 THEN 0 ";
      $sql .= "                                 ELSE ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 END AS intervalo, ";
      $sql .= "                           SUM(total_factura) - SUM(retencion) + SUM(total_nota_debito) AS debitos,";
      $sql .= "                           SUM(total_recibo) + SUM(total_nota_glosa) + SUM(total_nota_ajuste) + SUM(total_nota_credito) AS creditos ";
      $sql .= "                    FROM   cartera.facturas_resumen";
      $sql .= "                    WHERE  empresa_id = '".$empresa."'";
      $sql .= "                    AND    intervalo <= ".$rango." ";
   
      
      if($filtros['nombre_tercero'] != "0" && $filtros['nombre_tercero'])
      {
        $sql .= "                    AND    tipo_id_tercero = '".$tipoId."'";
        $sql .= "                    AND    tercero_id = '".$terceroId."' ";  
      }
      $sql .= "                    GROUP BY tercero_id,tipo_id_tercero,prefijo, factura_fiscal,empresa_id ";
      $sql .= "                    HAVING  SUM(total_nota_anulacion) = 0 ";
      $sql .= "                 ) AS A, ";
      $sql .= "                 ( ";
      $sql .= "                   SELECT  prefijo,  ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           empresa_id ";
      if($tipoFac != "T" )
        $sql .= "                           ,plan_id ";
      $sql .= "                   FROM    fac_facturas ";
      $sql .= "                   WHERE   empresa_id = '".$empresa."' ";
      $sql .= "                   AND     fecha_registro::date <= '".$fecha1."'::date ";
      $sql .= "                   AND     sw_clase_factura = '1' ";
      //$sql .= "                   AND     estado IN ('0') ";
      if($filtros['nombre_tercero'] != "0" && $filtros['nombre_tercero'])
      {
        $sql .= "                    AND    tipo_id_tercero = '".$tipoId."'";
        $sql .= "                    AND    tercero_id = '".$terceroId."' ";  
      }
      
      if($opcion == "R")
        $sql .= "                   AND     fecha_vencimiento_factura IS NOT NULL ";
      else
        $sql .= "                   AND     fecha_vencimiento_factura IS NULL ";
      if($opcion == "R")
      {
        $sql .= "                 UNION ALL ";
        $sql .= "                 SELECT  prefijo,  ";
        $sql .= "                         factura_fiscal, ";
        $sql .= "                         empresa_id ";
        if($tipoFac != "T" )
          $sql .= "                           ,plan_id ";
        $sql .= "                 FROM    facturas_externas ";
        $sql .= "                 WHERE   empresa_id = '".$empresa."' ";
        $sql .= "                 AND     fecha_registro::date <= '".$fecha1."'::date ";
        if($filtros['nombre_tercero'] != "0" && $filtros['nombre_tercero'])
        {
          $sql .= "                    AND    tipo_id_tercero = '".$tipoId."'";
          $sql .= "                    AND    tercero_id = '".$terceroId."' ";  
        }
      }
      $sql .= "                 ) AS B ";
      $sql .= "         WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "         AND    A.prefijo = B.prefijo ";
      $sql .= "         AND    A.factura_fiscal = B.factura_fiscal ";
      if($tipoFac == "T" )  
        $sql .= "         GROUP BY A.tercero_id,A.tipo_id_tercero,A.intervalo ";
      else
      {
        $sql .= "         AND   B.plan_id IS NOT NULL ";
        $sql .= "         GROUP BY B.plan_id,A.tercero_id,A.tipo_id_tercero,A.intervalo ";
      }
      $sql .= "        ) AS X ";
      $sql .= "WHERE   total_intervalo <> 0 ";
      if($periodo!='X' && (!empty($periodo)))
      {
        
       $sql .= " and  intervalo='".$periodo."'  ";
          
      }
      if($filtros['ordenar_por'] == "1")
        $sql .= "ORDER BY total_intervalo ";
		
 			if(!$rst = $this->ConexionBaseDatos($sql))
			
	
				return false;
			
      if($tipoFac == "T")
      {
        while(!$rst->EOF)
        {
          $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
		      $rst->MoveNext();
        }
		  }
      else
      {
        while(!$rst->EOF)
        {
          if(empty($datos[$rst->fields[3]][$rst->fields[2]]))
            $datos[$rst->fields[3]][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
		      else
            $datos[$rst->fields[3]][$rst->fields[2]]['total_intervalo'] += $rst->fields[4];
          $rst->MoveNext();
        }
      }
			$rst->Close();
			
			return $datos;
    }   
    /**
    *
    */
    function ObtenerPendientesCartera($periodo,$rango,$fecha1,$empresa,$opcion,$filtros,$tipoFac = "T")
    {
      list($tipoId,$terceroId) = explode("/",$filtros['nombre_tercero']);
      
      $sql = "SELECT A.tipo_id_tercero, ";
      $sql .= "      A.tercero_id, ";
      $sql .= "      A.intervalo, ";
      $sql .= "      B.plan_id, ";
      $sql .= "      SUM(C.valor_pendiente) AS valor_pendiente  ";
      $sql .= "FROM  (";
      $sql .= "       SELECT  BTRIM(tercero_id) AS tercero_id,";
      $sql .= "               tipo_id_tercero,";
      $sql .= "               prefijo,";
      $sql .= "               factura_fiscal,";
      $sql .= "               empresa_id,";
      $sql .= "               CASE  WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 >= 13 THEN 13 ";
      $sql .= "                     WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 BETWEEN 7 AND 12 THEN 7 ";
      $sql .= "                     WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 <= 0 THEN 0 ";
      $sql .= "                     ELSE ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 END AS intervalo, ";
      $sql .= "               SUM(total_factura) - SUM(retencion) + SUM(total_nota_debito) AS debitos,";
      $sql .= "               SUM(total_recibo) + SUM(total_nota_glosa) + SUM(total_nota_ajuste) + SUM(total_nota_credito) AS creditos ";
      $sql .= "        FROM   cartera.facturas_resumen";
      $sql .= "        WHERE  empresa_id = '".$empresa."'";
      $sql .= "        AND    intervalo <= ".$rango." ";
      if($filtros['nombre_tercero'] != "0" && $filtros['nombre_tercero'])
      {
        $sql .= "                    AND    tipo_id_tercero = '".$tipoId."'";
        $sql .= "                    AND    tercero_id = '".$terceroId."' ";  
      }
      $sql .= "        GROUP BY tercero_id,tipo_id_tercero,prefijo, factura_fiscal,empresa_id ";
      $sql .= "        HAVING  SUM(total_nota_anulacion) = 0 ";
      $sql .= "      ) A, ";
      $sql .= "      ( ";
      $sql .= "          SELECT  prefijo,  ";
      $sql .= "                  factura_fiscal, ";
      $sql .= "                  empresa_id, ";
      $sql .= "                  plan_id ";
      $sql .= "          FROM    fac_facturas ";
      $sql .= "          WHERE   empresa_id = '".$empresa."' ";
      $sql .= "          AND     fecha_registro::date <= '".$fecha1."'::date ";
      $sql .= "          AND     sw_clase_factura = '1' ";
      $sql .= "          AND     estado IN ('0') ";
      if($filtros['nombre_tercero'] != "0" && $filtros['nombre_tercero'])
      {
        $sql .= "                    AND    tipo_id_tercero = '".$tipoId."'";
        $sql .= "                    AND    tercero_id = '".$terceroId."' ";  
      }
      if($opcion == "R")
        $sql .= "           AND     fecha_vencimiento_factura IS NOT NULL ";
      else
        $sql .= "           AND     fecha_vencimiento_factura IS NULL ";
      if($opcion == "R" && $tipoFac == "T")
      {
        $sql .= "          UNION ALL ";
        $sql .= "          SELECT  prefijo,  ";
        $sql .= "                  factura_fiscal, ";
        $sql .= "                  empresa_id, ";
        $sql .= "                  NULL AS plan_id ";
        $sql .= "          FROM    facturas_externas ";
        $sql .= "          WHERE   empresa_id = '".$empresa."' ";
        $sql .= "          AND     fecha_registro::date <= '".$fecha1."'::date ";
        if($filtros['nombre_tercero'] != "0" && $filtros['nombre_tercero'])
        {
          $sql .= "                    AND    tipo_id_tercero = '".$tipoId."'";
          $sql .= "                    AND    tercero_id = '".$terceroId."' ";  
        }
      }
      $sql .= "       ) B, ";
      $sql .= "       ( ";
      $sql .= "           SELECT SUM(valor_pendiente) AS valor_pendiente, "; 
      $sql .= "                  empresa_id,  ";
      $sql .= "                  prefijo, ";
      $sql .= "                  factura_fiscal ";
      $sql .= "           FROM   glosas GL ";
      $sql .= "           WHERE  sw_estado <> '0'::bpchar ";
      $sql .= "           AND    empresa_id = '".$empresa."'  ";
      $sql .= "           GROUP BY empresa_id,prefijo,factura_fiscal ";
      $sql .= "       ) C ";
      $sql .= "WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "AND    A.prefijo = B.prefijo ";
      $sql .= "AND    A.factura_fiscal = B.factura_fiscal ";
      $sql .= "AND    A.empresa_id = C.empresa_id ";
      $sql .= "AND    A.prefijo = C.prefijo ";
      $sql .= "AND    A.factura_fiscal = C.factura_fiscal "; 
      $sql .= "AND    C.valor_pendiente > 0 ";
        if($periodo!='X' && (!empty($periodo)))
      {
        
       $sql .= " and  A.intervalo='".$periodo."'  ";
          
      }
      
    /*  if($periodo!='X' || $periodo!='')
      {
        $sql .= "AND    A.intervalo='".$periodo."' ";
      }
*/      
      $sql .= "GROUP BY A.tercero_id,A.tipo_id_tercero,B.plan_id,A.intervalo; ";

 			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      if($tipoFac == "T")
      {
  			while(!$rst->EOF)
  			{
  				$datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
  						
  				$rst->MoveNext();
  		  }			
      }
      else
      {
        while(!$rst->EOF)
  			{
  				$datos[$rst->fields[3]][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
  						
  				$rst->MoveNext();
  		  }
      }
			$rst->Close();
			
			return $datos;
    }
    /**
    * Funcion donde se obtienen los valores pendientes de glosas por plan
    *
    * @param integer $rango Identificador del rango por el cual se va a filtrar
    * @param date $fecha1 Fecha para la seleccion de las facturas
    * @param string $empresa Identificador de la empresa
    * @param string $opcion Indica que tipo de cartera se necesita R->radicada o N->no radicada
    *                       si no se especifica se trae ambas
    *
    * @return mixed
    */
    function ObtenerPendientesCarteraPlanes($rango,$fecha1,$empresa,$opcion)
    {     
      $sql = "SELECT PL.plan_id, ";
      $sql .= "      PL.plan_descripcion, ";
      $sql .= "      A.intervalo, ";
      $sql .= "      SUM(C.valor_pendiente) AS valor_pendiente  ";
      $sql .= "FROM  (";
      $sql .= "       SELECT  prefijo,";
      $sql .= "               factura_fiscal,";
      $sql .= "               empresa_id,";
      $sql .= "               CASE  WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 >= 13 THEN 13 ";
      $sql .= "                     WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 BETWEEN 7 AND 12 THEN 7 ";
      $sql .= "                     WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 <= 0 THEN 0 ";
      $sql .= "                     ELSE ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 END AS intervalo, ";
      $sql .= "               SUM(total_factura) - SUM(retencion) + SUM(total_nota_debito) AS debitos,";
      $sql .= "               SUM(total_recibo) + SUM(total_nota_glosa) + SUM(total_nota_ajuste) + SUM(total_nota_credito) AS creditos ";
      $sql .= "        FROM   cartera.facturas_resumen";
      $sql .= "        WHERE  empresa_id = '".$empresa."'";
      $sql .= "        AND    intervalo <= ".$rango." ";
      $sql .= "        GROUP BY prefijo, factura_fiscal,empresa_id ";
      $sql .= "        HAVING  SUM(total_nota_anulacion) = 0 ";
      $sql .= "      ) A, ";
      $sql .= "      ( ";
      $sql .= "          SELECT  prefijo,  ";
      $sql .= "                  factura_fiscal, ";
      $sql .= "                  empresa_id, ";
      $sql .= "                  plan_id ";
      $sql .= "          FROM    fac_facturas ";
      $sql .= "          WHERE   empresa_id = '".$empresa."' ";
      $sql .= "          AND     fecha_registro::date <= '".$fecha1."'::date ";
      $sql .= "          AND     sw_clase_factura = '1' ";
      $sql .= "          AND     estado IN ('0') ";

      if($opcion == "R")
        $sql .= "           AND     fecha_vencimiento_factura IS NOT NULL ";
      else if($opcion == "N")
        $sql .= "           AND     fecha_vencimiento_factura IS NULL ";
        
      if($opcion != "N")
      {
        $sql .= "          UNION ALL ";
        $sql .= "          SELECT  prefijo,  ";
        $sql .= "                  factura_fiscal, ";
        $sql .= "                  empresa_id, ";
        $sql .= "                  plan_id ";
        $sql .= "          FROM    facturas_externas ";
        $sql .= "          WHERE   empresa_id = '".$empresa."' ";
        $sql .= "          AND     fecha_registro::date <= '".$fecha1."'::date ";
      }
      $sql .= "       ) B, ";
      $sql .= "       ( ";
      $sql .= "           SELECT SUM(valor_pendiente) AS valor_pendiente, "; 
      $sql .= "                  empresa_id,  ";
      $sql .= "                  prefijo, ";
      $sql .= "                  factura_fiscal ";
      $sql .= "           FROM   glosas GL ";
      $sql .= "           WHERE  sw_estado <> '0'::bpchar ";
      $sql .= "           AND    empresa_id = '".$empresa."'  ";
      $sql .= "           GROUP BY empresa_id,prefijo,factura_fiscal ";
      $sql .= "       ) C, ";
      $sql .= "       planes PL ";
      $sql .= "WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "AND    A.prefijo = B.prefijo ";
      $sql .= "AND    A.factura_fiscal = B.factura_fiscal ";
      $sql .= "AND    A.empresa_id = C.empresa_id ";
      $sql .= "AND    A.prefijo = C.prefijo ";
      $sql .= "AND    A.factura_fiscal = C.factura_fiscal "; 
      $sql .= "AND    C.valor_pendiente > 0 ";
      $sql .= "AND    PL.plan_id = B.plan_id ";
      $sql .= "GROUP BY PL.plan_id,PL.plan_descripcion,A.intervalo; ";

 			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      while(!$rst->EOF)
			{
 				$datos[$rst->fields[0]][$rst->fields[1]][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
 				$rst->MoveNext();
 		  }
      
			$rst->Close();
			
			return $datos;
    }
    /**
    *
    */
    function ObtenerPagares($periodo,$rango,$fecha1,$empresa,$datos)
    {            
      $sql .= "SELECT intervalo, ";
      $sql .= "       SUM(valor) - SUM(valor_abonos) AS total_intervalo ";
      $sql .= "FROM   (";
      $sql .= "         SELECT  prefijo,";
      $sql .= "                 numero,";
      $sql .= "                 empresa_id,";
      $sql .= "                 CASE WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 >= 13 THEN 13 ";
      $sql .= "                      WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 BETWEEN 7 AND 12 THEN 7 ";
      $sql .= "                      WHEN ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 <= 0 THEN 0 ";
      $sql .= "                      ELSE ('".$fecha1."'::date - (substring(MIN(intervalo)::text from 1 for 4)||'-'||substring(MIN(intervalo)::text from 5 for 6)||'-01')::date)/30 END AS intervalo, ";
      $sql .= "                 CASE WHEN SUM(valor_modificado) > 0 THEN SUM(valor_modificado) ";
      $sql .= "                      ELSE SUM(valor) END AS valor, ";
      $sql .= "                 SUM(valor_anulacion) AS valor_anulacion, ";
      $sql .= "                 SUM(valor_abonos) AS valor_abonos ";
      $sql .= "         FROM   cartera.pagares_resumen ";
      $sql .= "         WHERE  empresa_id = '".$empresa."'";
      $sql .= "         AND    intervalo <= ".$rango." ";
      $sql .= "         AND    fecha_registro::date <= '".$fecha1."'::date ";
      $sql .= "         GROUP BY prefijo,numero,empresa_id ";
      $sql .= "         HAVING SUM(valor_anulacion) = 0 ";
      $sql .= "       ) PA ";
       if($periodo!='X' && (!empty($periodo)))
      {
        
       $sql .= " where  intervalo='".$periodo."'  ";
          
      }
      $sql .= "GROUP BY intervalo ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos["-"]["-"][$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
						
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
    }
		/**
		*
		* @return boolean
    */
		function ObtenerUsuarioNombre($id)
		{
			$sql  = "SELECT nombre FROM system_usuarios WHERE usuario_id = ".$id;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			if (!$rst->EOF)
			{
				$UsuarioNombre = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $UsuarioNombre;
	 	}
		/**
		* Funcion donde se seleccionan el nombre de los terceros que son clientes y 
		* tienen envios radicados para que se pueda filtrar por ellos en una busqueda  
		* 
    * @param string $datos Identificador del tercero (opcional)
    *
		* @return array datos de tipo_id_terceros 
		*/
		function ObtenerNombresTerceros($datos)
		{
			list($tipoId,$terceroId) = explode("/",$datos['nombre_tercero']);
      //$this->debug=true;
      $sql	= "SELECT nombre_tercero, ";
			$sql .= "				tipo_id_tercero, ";
			$sql .= "				tercero_id ";
			$sql .= "FROM		terceros TE ";
      
      if($datos['nombre_tercero'] != "0" && $datos['nombre_tercero'])
      {
        $sql .= "WHERE  tipo_id_tercero = '".$tipoId."' ";
        $sql .= "AND    tercero_id = '".$terceroId."' ";
      }
      
			
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
		* Funcion donde se seleccionan el nombre de los terceros que son clientes y 
		* tienen envios radicados para que se pueda filtrar por ellos en una busqueda  
		* 
		* @return array datos de tipo_id_terceros 
		*/
		function ObtenerNombresPlanes($datos)
		{
      $sql	= "SELECT plan_descripcion, ";
			$sql .= "				plan_id ";
			$sql .= "FROM		planes ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$nombre = array();
			while(!$rst->EOF)
			{
				$nombre[$rst->fields[1]] = $rst->fields[0];
				$rst->MoveNext();
		  }
			$rst->Close();
						
			return $nombre;
		}
    /**
		* Funcion donde se seleccionan el nombre de los terceros que son clientes y 
		* tienen envios radicados para que se pueda filtrar por ellos en una busqueda  
		* 
		* @return array 
		*/
		function ObtenerPlanesTipoEntidad()
		{
      $sql	= "SELECT TC.descripcion, ";
			$sql .= "				TC.tipo_cliente, ";
			$sql .= "				PL.plan_id, ";
			$sql .= "				PL.tipo_tercero_id, ";
			$sql .= "				PL.tercero_id ";
			$sql .= "FROM		planes PL, ";
      $sql .= "       tipos_cliente TC ";
      $sql .= "WHERE  PL.tipo_cliente = TC.tipo_cliente ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$nombre = array();
			while(!$rst->EOF)
			{
				$nombre[$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);;
				$rst->MoveNext();
		  }
			$rst->Close();
						
			return $nombre;
		}
    /**
		*
		* @return boolean
		*/
		function ObtenerAnticipos($fechai,$empresa,$facturacion,$filtros)
		{
      list($tipoId,$terceroId) = explode("/",$filtros['nombre_tercero']);
      
      $sql  = "SELECT SUM(RC.total_abono) AS saldo, ";
      $sql .= "				RC.tipo_id_tercero, ";
			$sql .= "				RC.tercero_id ";
			$sql .= "FROM		recibos_caja RC, ";
			$sql .= "				rc_detalle_tesoreria_conceptos RS ";
			$sql .= "WHERE	RC.empresa_id = '".$empresa."' ";
			$sql .= "AND		RC.estado = '2' ";
			$sql .= "AND		RC.empresa_id = RS.empresa_id "; 	
			$sql .= "AND		RC.recibo_caja = RS.recibo_caja ";
			$sql .= "AND		RC.prefijo = RS.prefijo ";
			$sql .= "AND		RS.concepto_id = 'C013' ";
      $sql .= "AND		RC.sw_recibo_tesoreria = '1' ";
			$sql .= "AND		RC.fecha_ingcaja::date <= '".$fechai."' ";
      if($filtros['nombre_tercero'] && $filtros['nombre_tercero'] != '0')
      {
        $sql .= "AND		RC.tipo_id_tercero = '".$terceroId."' ";
        $sql .= "AND		RC.tercero_id = '".$tipoId."'";
			}
      $sql .= "GROUP BY RC.tipo_id_tercero,RC.tercero_id ";
			
      $datos = array();
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"] =  $rst->GetRowAssoc($ToUpper = false);
				$facturacion["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"][0]= $facturacion["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"][0];

        $rst->MoveNext();
		  }
			$rst->Close();
			
			return array("anticipos"=>$datos,"facturacion"=>$facturacion);			
		}
    /**
		*
		* @return boolean
		*/
		function ObtenerRecibosAnticipos($fechai,$empresa,$filtros)
		{
      list($tipoId,$terceroId) = explode("/",$filtros['nombre_tercero']);

			$sql  = "SELECT SUM(RC.total_abono) AS saldo, ";
      $sql .= "				RC.tipo_id_tercero, ";
			$sql .= "				RC.tercero_id ";
			$sql .= "FROM	  recibos_caja RC,  ";
			$sql .= "			  rc_tipos_documentos RD  ";
			$sql .= "WHERE	RC.empresa_id = '".$empresa."'  ";
			$sql .= "AND		RC.estado = '2'::bpchar ";
			$sql .= "AND		RC.sw_recibo_tesoreria = '1' ";
			$sql .= "AND		RC.fecha_ingcaja::date <= '".$fechai."' ";
			$sql .= "AND		RD.sw_cruzar_anticipos = '1' ";
			$sql .= "AND		RD.rc_tipo_documento = RC.rc_tipo_documento ";
      if($filtros['nombre_tercero'] && $filtros['nombre_tercero'] != '0')
      {
        $sql .= "AND		RC.tipo_id_tercero = '".$terceroId."' ";
        $sql .= "AND		RC.tercero_id = '".$tipoId."'";
			}
      $sql .= "GROUP BY RC.tipo_id_tercero,RC.tercero_id ";
      
			$datos = array();
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"] =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;	
		}
	}
?>