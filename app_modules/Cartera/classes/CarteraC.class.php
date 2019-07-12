<?php
  /**
  * $Id: CarteraC.class.php,v 1.12 2009/06/26 13:53:16 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.12 $ 
	* 
	* @autor Hugo F  Manrique 
  */
	class CarteraC
	{
		var $Arreglo = array();
		
		function CarteraC(){}
		/*
		*
		* @return boolean
		*/
    function ObtenerReporte($datos)
		{
			$f = explode("/",$datos['fecha']);
			$rango = $f[2].$f[1];
      $fecha = $f[2]."-".$f[1]."-".$f[0];
			$facturacion = array();
      $facturacion = $this->ObtenerResumenCartera($rango,$fecha,$datos['empresa_id']);
      $facturacion = $this->ObtenerPagares($rango,$fecha,$datos['empresa_id'],$facturacion);
			
			$rst = $this->ObtenerAnticipos($fecha,$datos['empresa_id'],$facturacion);
      $anticipos = $rst['anticipos'];
      $facturacion = $rst['facturacion'];
			$rst = $this->ObtenerRecibosAnticipos($fecha,$datos['empresa_id'],$facturacion);
      $facturacion = $rst['facturacion'];
      $rc_anticipos = $rst['descargos'];
			
      //$terceros = $this->ObtenerNombresTerceros();
			$datosc = array();
			$intervalos = array();
      $saldo = 0;
			foreach($facturacion as $key => $dtlII)
			{
        foreach($dtlII as $keyII => $cartera)
        {
          foreach($cartera as $keyI => $detalle)
          {
            $indice = $keyI."<br>".$key." ".$keyII;
            $periodos = array();
            foreach($detalle as $keyA => $dtl)
            {
              $saldo += $dtl['total_intervalo'];
              if($dtl['total_intervalo'] != 0)
              {
                $periodos[$keyA] = $dtl;
                $intervalos[$keyA] = $keyA;
              }
            }
            if(!empty($periodos))
              $datosc[$indice]['periodos'] = $periodos;

            $vanticipos = $anticipos[$key][$keyII][$keyI]['saldo'] - $rc_anticipos[$key][$keyII][$keyI]['saldo'];

            if($vanticipos != 0)
            {
              $datosc[$indice]['anticipos'] = $anticipos[$key][$keyII][$keyI]['saldo'];
              $datosc[$indice]['descargo'] = $rc_anticipos[$key][$keyII][$keyI]['saldo'];
            }	
          }
        }
      }
      
			return array("cartera"=>$datosc,"intervalos"=>$intervalos);
		}
    /**
    *
    */
    function ObtenerResumenCartera($rango,$fecha1,$empresa,$opcion,$filtros,$tipoFac = "T")
    {     
      $sql  = "SELECT * FROM ( ";
      $sql .= "SELECT  X.tipo_id_tercero, ";
      $sql .= "        X.tercero_id, ";
      $sql .= "        X.nombre_tercero, ";
      $sql .= "        X.intervalo, ";      
      $sql .= "        SUM(X.total_factura) + SUM(X.total_nota_debito) -(SUM(X.retencion) + SUM(X.total_recibo) + SUM(X.total_nota_glosa) + SUM(X.total_nota_ajuste) + SUM(X.total_nota_credito)) AS total_intervalo ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  TE.tipo_id_tercero, ";
      $sql .= "                 TE.tercero_id,";
      $sql .= "                 TE.nombre_tercero, ";
      $sql .= "                 A.intervalo, ";
      $sql .= "                 SUM(A.total_factura) AS total_factura,";
      $sql .= "                 SUM(A.retencion) AS retencion,";
      $sql .= "                 SUM(A.total_nota_debito) AS total_nota_debito,";
      $sql .= "                 SUM(A.total_recibo) AS total_recibo,";
      $sql .= "                 SUM(A.total_nota_glosa) AS total_nota_glosa,";
      $sql .= "                 SUM(A.total_nota_ajuste) AS total_nota_ajuste,";
      $sql .= "                 SUM(A.total_nota_credito) AS total_nota_credito ";
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
      $sql .= "                    GROUP BY tercero_id,tipo_id_tercero,prefijo, factura_fiscal,empresa_id ";
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
      $sql .= "                   AND     plan_id IS NOT NULL ";
      $sql .= "                   UNION ALL ";
      $sql .= "                   SELECT  prefijo,  ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           empresa_id, ";
      $sql .= "                           plan_id ";
      $sql .= "                   FROM    facturas_externas ";
      $sql .= "                   WHERE   empresa_id = '".$empresa."' ";
      //$sql .= "                   AND     fecha_registro::date <= '".$fecha1."'::date ";
      $sql .= "                 ) AS B, ";
      $sql .= "                 planes PL, ";
      $sql .= "                 terceros TE ";
      $sql .= "         WHERE   A.empresa_id = B.empresa_id ";
      $sql .= "         AND     A.prefijo = B.prefijo ";
      $sql .= "         AND     A.factura_fiscal = B.factura_fiscal ";
      $sql .= "         AND     B.plan_id = PL.plan_id ";
      $sql .= "         AND     PL.tercero_id = TE.tercero_id ";
      $sql .= "         AND     PL.tipo_tercero_id = TE.tipo_id_tercero ";
      $sql .= "         GROUP BY TE.tipo_id_tercero,TE.tercero_id,TE.nombre_tercero,A.intervalo ";
      $sql .= "        ) AS X ";
      $sql .= "GROUP BY X.tipo_id_tercero,X.tercero_id,X.nombre_tercero,X.intervalo ";
      $sql .= ") AS Y ";
      //$sql .= "WHERE   total_intervalo <> 0 ";
      if($filtros['ordenar_por'] == "1")
        $sql .= "ORDER BY total_intervalo ";
      
 			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      while(!$rst->EOF)
      {
        $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]][$rst->fields[3]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
		  
			$rst->Close();
			
			return $datos;
    }
    /**
    *
    */
    function ObtenerPagares($rango,$fecha1,$empresa,$datos)
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
      $sql .= "GROUP BY intervalo ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos["-"]["-"]["PAGARES"][$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
						
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
		*
		* @return boolean
		*/
		function ObtenerAnticipos($fechai,$empresa,$facturacion)
		{
      $sql  = "SELECT SUM(RC.total_abono) AS saldo, ";
      $sql .= "				TE.tipo_id_tercero, ";
			$sql .= "				TE.tercero_id, ";
      $sql .= "       TE.nombre_tercero ";
			$sql .= "FROM		recibos_caja RC, ";
			$sql .= "				rc_detalle_tesoreria_conceptos RS, ";
      $sql .= "				terceros TE ";
			$sql .= "WHERE	RC.empresa_id = '".$empresa."' ";
			$sql .= "AND		RC.estado = '2' ";
			$sql .= "AND		RC.empresa_id = RS.empresa_id "; 	
			$sql .= "AND		RC.recibo_caja = RS.recibo_caja ";
			$sql .= "AND		RC.prefijo = RS.prefijo ";
			$sql .= "AND		RS.concepto_id = 'C013' ";
      $sql .= "AND		RC.sw_recibo_tesoreria = '1' ";
			$sql .= "AND		RC.fecha_ingcaja::date <= '".$fechai."' ";
      $sql .= "AND    RC.tercero_id = TE.tercero_id ";
      $sql .= "AND    RC.tipo_id_tercero = TE.tipo_id_tercero ";
      $sql .= "GROUP BY TE.tipo_id_tercero,TE.tercero_id,TE.nombre_tercero ";
			
      $datos = array();
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"][$rst->fields[3]] =  $rst->GetRowAssoc($ToUpper = false);
				$facturacion["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"][$rst->fields[3]][0]['nombre_tercero']= $rst->fields[3];

        $rst->MoveNext();
		  }
			$rst->Close();
			return array("anticipos"=>$datos,"facturacion"=>$facturacion);			
		}
    /**
		*
		* @return boolean
		*/
		function ObtenerRecibosAnticipos($fechai,$empresa,$facturacion)
		{
			$sql  = "SELECT SUM(RC.total_abono) AS saldo, ";
      $sql .= "				TE.tipo_id_tercero, ";
			$sql .= "				TE.tercero_id, ";
      $sql .= "       TE.nombre_tercero ";
			$sql .= "FROM	  recibos_caja RC,  ";
			$sql .= "			  rc_tipos_documentos RD,  ";
      $sql .= "				terceros TE ";
			$sql .= "WHERE	RC.empresa_id = '".$empresa."'  ";
			$sql .= "AND		RC.estado = '2'::bpchar ";
			$sql .= "AND		RC.sw_recibo_tesoreria = '1' ";
			$sql .= "AND		RC.fecha_ingcaja::date <= '".$fechai."' ";
			$sql .= "AND		RD.sw_cruzar_anticipos = '1' ";
			$sql .= "AND		RD.rc_tipo_documento = RC.rc_tipo_documento ";
			$sql .= "AND    RC.tercero_id = TE.tercero_id ";
      $sql .= "AND    RC.tipo_id_tercero = TE.tipo_id_tercero ";
      $sql .= "GROUP BY TE.tipo_id_tercero,TE.tercero_id,TE.nombre_tercero ";
      
			$datos = array();
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"][$rst->fields[3]] =  $rst->GetRowAssoc($ToUpper = false);
        $facturacion["'".$rst->fields[1]."'"]["'".$rst->fields[2]."'"][$rst->fields[3]][0]['nombre_tercero']= $rst->fields[3];
        $rst->MoveNext();
		  }
			$rst->Close();
			
			return array("descargos" => $datos,"facturacion"=>$facturacion);	
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
				echo $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
?>