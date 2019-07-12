<?php
  /***
  * $Id: CarteraResumen.class.php,v 1.12 2009/06/26 13:53:16 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.12 $ 
	* 
	* @autor Hugo F  Manrique 
  */
	class CarteraResumen extends ConexionBD
	{
		var $Arreglo = array();
		/**
    * Construcutor de la clase
    */
		function CarteraResumen(){}
		/**
		* Funcion donde se obtienen los datos que haran parte del reporte
    *
    * @param array $datos Arreglo de datos con los filtros necesarios para hacer la consulta
    *
		* @return array
		*/		
		function ObtenerReporte($datos)
		{
      $rango1 = date("Ym", mktime(0, 0, 0,(intval($datos['mes'])), 0,$datos['anyo']));
			$rango2 = date("Ym", mktime(0, 0, 0,(intval($datos['mes'])+1), 0,$datos['anyo']));
			$fecha1 = date("Y-m-d", mktime(0, 0, 0,(intval($datos['mes'])), 0,$datos['anyo']));
			$fecha2 = date("Y-m-d", mktime(0, 0, 0,(intval($datos['mes'])+1), 0,$datos['anyo']));

      $a['inicial'] = $this->ObtenerFacturasEnviadas($datos['empresa_id'],$rango1);
      $a['inicial']['anticipo'] = $this->ObtenerAbonosAnticipos($datos['empresa_id'],$fecha1);
      $a['inicial']['descargo'] = $this->ObtenerDescargosAnticipos($datos['empresa_id'],$fecha1);
			$a['inicial']['pagares'] = $this->ObtenerPagares(" <= ".$rango1,$datos['empresa_id']); 
      
      $a['final'] = $this->ObtenerResumenCarteraMovimientos($rango2,$datos['empresa_id']);
      $a['final']['anticipo'] = $this->ObtenerAbonosAnticipos($datos['empresa_id'],$fecha1,$fecha2);
      $a['final']['descargo'] = $this->ObtenerDescargosAnticipos($datos['empresa_id'],$fecha1,$fecha2);
 			$a['final']['pagares'] = $this->ObtenerPagares(" = ".$rango2,$datos['empresa_id']); 
     
			return $a;		
		}
    /**
		* Funcion donde se consulta la informacion de la cartera, de acuerdo 
    * a un rango dado 
		*
    * @param string $empresa Identificador de la empresa
    * @param $rango integer Identificador del rango (formato YYYYMM)
    *
		* @return mixed
		*/
		function ObtenerFacturasEnviadas($empresa,$rango)
		{
      $sql  = "SELECT  SUM(total_factura) AS total_factura, ";
      $sql .= "        SUM(total_nota_debito) AS total_nota_debito,";
      $sql .= "        SUM(total_recibo) AS total_recibo, ";
      $sql .= "        SUM(total_nota_glosa) AS total_nota_glosa, ";
      $sql .= "        SUM(total_nota_ajuste) AS total_nota_ajuste, ";
      $sql .= "        SUM(total_nota_credito) AS total_nota_credito, ";
      $sql .= "        SUM(retencion) AS retencion ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  SUM(A.total_factura) AS total_factura, ";
      $sql .= "                 SUM(A.total_nota_debito) AS total_nota_debito,";
      $sql .= "                 SUM(A.total_recibo) AS total_recibo, ";
      $sql .= "                 SUM(A.total_nota_glosa) AS total_nota_glosa, ";
      $sql .= "                 SUM(A.total_nota_ajuste) AS total_nota_ajuste, ";
      $sql .= "                 SUM(A.total_nota_credito) AS total_nota_credito, ";
      $sql .= "                 SUM(A.retencion) AS retencion ";
      $sql .= "         FROM    (";
      $sql .= "                   SELECT  BTRIM(tercero_id) AS tercero_id,";
      $sql .= "                           tipo_id_tercero,";
      $sql .= "                           prefijo,";
      $sql .= "                           factura_fiscal,";
      $sql .= "                           empresa_id,";
      $sql .= "                           SUM(total_factura) AS total_factura, ";
      $sql .= "                           SUM(total_nota_debito) AS total_nota_debito,";
      $sql .= "                           SUM(total_recibo) AS total_recibo, ";
      $sql .= "                           SUM(total_nota_glosa) AS total_nota_glosa, ";
      $sql .= "                           SUM(total_nota_ajuste) AS total_nota_ajuste, ";
      $sql .= "                           SUM(total_nota_credito) AS total_nota_credito, ";
      $sql .= "                           SUM(total_nota_anulacion) AS total_nota_anulacion, ";
      $sql .= "                           SUM(retencion) AS retencion ";
      $sql .= "                    FROM   cartera.facturas_resumen";
      $sql .= "                    WHERE  empresa_id = '".$empresa."'";
      $sql .= "                    AND    intervalo <= ".$rango." ";
      $sql .= "                    GROUP BY tercero_id,tipo_id_tercero,prefijo, factura_fiscal,empresa_id ";
      $sql .= "                    HAVING  SUM(total_nota_anulacion) = 0 ";
      $sql .= "                 ) AS A, ";
      $sql .= "                 ( ";
      $sql .= "                   SELECT  prefijo,  ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           empresa_id ";
      $sql .= "                   FROM    fac_facturas ";
      $sql .= "                   WHERE   empresa_id = '".$empresa."' ";
      $sql .= "                   AND     sw_clase_factura = '1' ";
      $sql .= "                   UNION ALL ";
      $sql .= "                   SELECT  prefijo,  ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           empresa_id ";
      $sql .= "                   FROM    facturas_externas ";
      $sql .= "                   WHERE   empresa_id = '".$empresa."' ";
      $sql .= "                 ) AS B ";
      $sql .= "         WHERE   A.empresa_id = B.empresa_id ";
      $sql .= "         AND     A.prefijo = B.prefijo ";
      $sql .= "         AND     A.factura_fiscal = B.factura_fiscal ";
      $sql .= "         GROUP BY A.tercero_id,A.tipo_id_tercero ";
      $sql .= "        ) AS X ";
      
 			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      while(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
		  
			$rst->Close();
			
			return $datos;
		}
    /**
    * Funcion donde se obtiene la cartera de los pagares
    *
    * @param string $rango Condicion para la consulta (ej "<= 200803")
    * @param string $empresa Identificador de la empresa
    *
    * @return mixed
    */
    function ObtenerPagares($rango,$empresa)
    {
      $sql  = "SELECT  SUM(valor) - SUM(valor_abonos) AS total_intervalo ";
      $sql .= "FROM    (";
      $sql .= "          SELECT  prefijo,";
      $sql .= "                  numero,";
      $sql .= "                  empresa_id,";
      $sql .= "                  MIN(intervalo) AS intervalo, ";
      $sql .= "                  CASE WHEN SUM(valor_modificado) > 0 THEN SUM(valor_modificado) ";
      $sql .= "                       ELSE SUM(valor) END AS valor, ";
      $sql .= "                  SUM(valor_anulacion) AS valor_anulacion, ";
      $sql .= "                  SUM(valor_abonos) AS valor_abonos ";
      $sql .= "          FROM   cartera.pagares_resumen ";
      $sql .= "          WHERE  empresa_id = '".$empresa."'";
      $sql .= "          AND    intervalo ".$rango." ";
      $sql .= "          GROUP BY prefijo,numero,empresa_id ";
      $sql .= "          HAVING SUM(valor_anulacion) = 0 ";
      $sql .= "        ) PA ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos['total_intervalo'];
    }
    /**
    * Funcion donde se obtienen los movimientos de la cartera, el total de las
    * facturas, las notas de glosa, debito, credito, ajuste y anulacion y el 
    * valor de los recibos, para un rango dado
    * 
    * @param $rango integer Identificador del rango (formato YYYYMM)
    * @param string $empresa Identificador de la empresa
    *
    * @return mixed
    */
    function ObtenerResumenCarteraMovimientos($rango,$empresa)
    {
      $datos = array();
			
      $sql  = "SELECT  SUM(total_nota_debito) AS debito ";
      $sql .= "FROM   (  ";
      $sql .= "        SELECT prefijo,  ";
      $sql .= "               factura_fiscal, ";
      $sql .= "               empresa_id, ";
      $sql .= "               tercero_id,";
      $sql .= "               tipo_id_tercero,";
      $sql .= "               total_nota_debito ";
      $sql .= "        FROM   cartera.facturas_resumen";
      $sql .= "        WHERE  empresa_id = '".$empresa."'";
      $sql .= "        AND    intervalo = ".$rango." ";
      $sql .= "        AND    total_nota_anulacion = 0 ";
      $sql .= "      ) AS A, ";
      $sql .= "      ( ";
      $sql .= "        SELECT  prefijo,  ";
      $sql .= "                factura_fiscal, ";
      $sql .= "                empresa_id, ";
      $sql .= "                retencion_fuente ";
      $sql .= "        FROM    fac_facturas ";
      $sql .= "        WHERE   empresa_id = '".$empresa."' ";
      $sql .= "        AND     sw_clase_factura = '1' ";
      $sql .= "        UNION ALL ";
      $sql .= "        SELECT  prefijo,  ";
      $sql .= "                factura_fiscal, ";
      $sql .= "                empresa_id, ";
      $sql .= "                0 AS retencion_fuente ";
      $sql .= "        FROM    facturas_externas ";
      $sql .= "        WHERE   empresa_id = '".$empresa."' ";
      $sql .= "       ) AS B ";
      $sql .= "WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "AND    A.prefijo = B.prefijo ";
      $sql .= "AND    A.factura_fiscal = B.factura_fiscal ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			if(!$rst->EOF)
			{
				$datos['total_nota_debito'] = $rst->fields[0];
				$rst->MoveNext();
		  }
			$rst->Close();
      
      $sql  = "SELECT  SUM(total_recibo) AS recibo ";
      $sql .= "FROM   (  ";
      $sql .= "        SELECT prefijo,  ";
      $sql .= "               factura_fiscal, ";
      $sql .= "               empresa_id, ";
      $sql .= "               tercero_id,";
      $sql .= "               tipo_id_tercero,";
      $sql .= "               total_recibo ";
      $sql .= "        FROM   cartera.facturas_resumen";
      $sql .= "        WHERE  empresa_id = '".$empresa."'";
      $sql .= "        AND    intervalo = ".$rango." ";
      $sql .= "        AND    total_nota_anulacion = 0 ";
      $sql .= "      ) AS A ,";
      $sql .= "      ( ";
      $sql .= "        SELECT  prefijo,  ";
      $sql .= "                factura_fiscal, ";
      $sql .= "                empresa_id, ";
      $sql .= "                retencion_fuente ";
      $sql .= "        FROM    fac_facturas ";
      $sql .= "        WHERE   empresa_id = '".$empresa."' ";
      $sql .= "        AND     sw_clase_factura = '1' ";
      $sql .= "        UNION ALL ";
      $sql .= "        SELECT  prefijo,  ";
      $sql .= "                factura_fiscal, ";
      $sql .= "                empresa_id, ";
      $sql .= "                0 AS retencion_fuente ";
      $sql .= "        FROM    facturas_externas ";
      $sql .= "        WHERE   empresa_id = '".$empresa."' ";
      $sql .= "       ) AS B ";
      $sql .= "WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "AND    A.prefijo = B.prefijo ";
      $sql .= "AND    A.factura_fiscal = B.factura_fiscal ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			if(!$rst->EOF)
			{
				$datos['total_recibo'] = $rst->fields[0];
				$rst->MoveNext();
		  }
			$rst->Close();
      
      $sql  = "SELECT  SUM(total_nota_glosa) AS glosas ";
      $sql .= "FROM   (  ";
      $sql .= "        SELECT prefijo,  ";
      $sql .= "               factura_fiscal, ";
      $sql .= "               empresa_id, ";
      $sql .= "               tercero_id,";
      $sql .= "               tipo_id_tercero,";
      $sql .= "               total_nota_glosa ";
      $sql .= "        FROM   cartera.facturas_resumen";
      $sql .= "        WHERE  empresa_id = '".$empresa."'";
      $sql .= "        AND    intervalo = ".$rango." ";
      $sql .= "        AND    total_nota_anulacion = 0 ";
      $sql .= "      ) AS A, ";
      $sql .= "      ( ";
      $sql .= "        SELECT  prefijo,  ";
      $sql .= "                factura_fiscal, ";
      $sql .= "                empresa_id, ";
      $sql .= "                retencion_fuente ";
      $sql .= "        FROM    fac_facturas ";
      $sql .= "        WHERE   empresa_id = '".$empresa."' ";
      $sql .= "        AND     sw_clase_factura = '1' ";
      $sql .= "        UNION ALL ";
      $sql .= "        SELECT  prefijo,  ";
      $sql .= "                factura_fiscal, ";
      $sql .= "                empresa_id, ";
      $sql .= "                0 AS retencion_fuente ";
      $sql .= "        FROM    facturas_externas ";
      $sql .= "        WHERE   empresa_id = '".$empresa."' ";
      $sql .= "       ) AS B ";
      $sql .= "WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "AND    A.prefijo = B.prefijo ";
      $sql .= "AND    A.factura_fiscal = B.factura_fiscal ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			if(!$rst->EOF)
			{
				$datos['total_nota_glosa'] = $rst->fields[0];
				$rst->MoveNext();
		  }
			$rst->Close();
      
      $sql  = "SELECT  SUM(total_nota_ajuste) AS ajuste ";
      $sql .= "FROM   (  ";
      $sql .= "        SELECT prefijo,  ";
      $sql .= "               factura_fiscal, ";
      $sql .= "               empresa_id, ";
      $sql .= "               tercero_id,";
      $sql .= "               tipo_id_tercero,";
      $sql .= "               total_nota_ajuste ";
      $sql .= "        FROM   cartera.facturas_resumen";
      $sql .= "        WHERE  empresa_id = '".$empresa."'";
      $sql .= "        AND    intervalo = ".$rango." ";
      $sql .= "        AND    total_nota_anulacion = 0 ";
      $sql .= "      ) AS A, ";
      $sql .= "      ( ";
      $sql .= "        SELECT  prefijo,  ";
      $sql .= "                factura_fiscal, ";
      $sql .= "                empresa_id, ";
      $sql .= "                retencion_fuente ";
      $sql .= "        FROM    fac_facturas ";
      $sql .= "        WHERE   empresa_id = '".$empresa."' ";
      $sql .= "        AND     sw_clase_factura = '1' ";
      $sql .= "        UNION ALL ";
      $sql .= "        SELECT  prefijo,  ";
      $sql .= "                factura_fiscal, ";
      $sql .= "                empresa_id, ";
      $sql .= "                0 AS retencion_fuente ";
      $sql .= "        FROM    facturas_externas ";
      $sql .= "        WHERE   empresa_id = '".$empresa."' ";
      $sql .= "       ) AS B ";
      $sql .= "WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "AND    A.prefijo = B.prefijo ";
      $sql .= "AND    A.factura_fiscal = B.factura_fiscal ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			if(!$rst->EOF)
			{
				$datos['total_nota_ajuste'] = $rst->fields[0];
				$rst->MoveNext();
		  }
			$rst->Close();
      
      $sql  = "SELECT  SUM(total_nota_credito) AS credito ";
      $sql .= "FROM   (  ";
      $sql .= "        SELECT prefijo,  ";
      $sql .= "               factura_fiscal, ";
      $sql .= "               empresa_id, ";
      $sql .= "               tercero_id,";
      $sql .= "               tipo_id_tercero,";
      $sql .= "               total_nota_credito ";
      $sql .= "        FROM   cartera.facturas_resumen";
      $sql .= "        WHERE  empresa_id = '".$empresa."'";
      $sql .= "        AND    intervalo = ".$rango." ";
      $sql .= "        AND    total_nota_anulacion = 0 ";
      $sql .= "      ) AS A, ";
      $sql .= "      ( ";
      $sql .= "        SELECT  prefijo,  ";
      $sql .= "                factura_fiscal, ";
      $sql .= "                empresa_id, ";
      $sql .= "                retencion_fuente ";
      $sql .= "        FROM    fac_facturas ";
      $sql .= "        WHERE   empresa_id = '".$empresa."' ";
      $sql .= "        AND     sw_clase_factura = '1' ";
      $sql .= "        UNION ALL ";
      $sql .= "        SELECT  prefijo,  ";
      $sql .= "                factura_fiscal, ";
      $sql .= "                empresa_id, ";
      $sql .= "                0 AS retencion_fuente ";
      $sql .= "        FROM    facturas_externas ";
      $sql .= "        WHERE   empresa_id = '".$empresa."' ";
      $sql .= "       ) AS B ";
      $sql .= "WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "AND    A.prefijo = B.prefijo ";
      $sql .= "AND    A.factura_fiscal = B.factura_fiscal ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			if(!$rst->EOF)
			{
				$datos['total_nota_credito'] = $rst->fields[0];
				$rst->MoveNext();
		  }
			$rst->Close();
      
      $sql  = "SELECT SUM(total_factura) AS total_factura, ";
      $sql .= "       SUM(retencion) AS retencion ";
      $sql .= "FROM   (  ";
      $sql .= "        SELECT prefijo,  ";
      $sql .= "               factura_fiscal, ";
      $sql .= "               tercero_id,";
      $sql .= "               tipo_id_tercero,";
      $sql .= "               empresa_id, ";
      $sql .= "               total_factura, ";
      $sql .= "               retencion ";
      $sql .= "        FROM   cartera.facturas_resumen";
      $sql .= "        WHERE  empresa_id = '".$empresa."'";
      $sql .= "        AND    intervalo = ".$rango." ";
      $sql .= "      ) AS A, ";
      $sql .= "      ( ";
      $sql .= "        SELECT  prefijo,  ";
      $sql .= "                factura_fiscal, ";
      $sql .= "                empresa_id, ";
      $sql .= "                retencion_fuente ";
      $sql .= "        FROM    fac_facturas ";
      $sql .= "        WHERE   empresa_id = '".$empresa."' ";
      $sql .= "        AND     sw_clase_factura = '1' ";
      $sql .= "        UNION ALL ";
      $sql .= "        SELECT  prefijo,  ";
      $sql .= "                factura_fiscal, ";
      $sql .= "                empresa_id, ";
      $sql .= "                0 AS retencion_fuente ";
      $sql .= "        FROM    facturas_externas ";
      $sql .= "        WHERE   empresa_id = '".$empresa."' ";
      $sql .= "       ) AS B ";
      $sql .= "WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "AND    A.prefijo = B.prefijo ";
      $sql .= "AND    A.factura_fiscal = B.factura_fiscal ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			if(!$rst->EOF)
			{
				$datos['total_factura'] = $rst->fields[0];
				$datos['retencion'] = $rst->fields[1];
						
				$rst->MoveNext();
		  }
			$rst->Close();
      
      $sql  = "SELECT SUM(total_nota_anulacion) AS total_nota_anulacion, ";
      $sql .= "       SUM(retencion) AS retencion ";
      $sql .= "FROM   (";
      $sql .= "         SELECT  A.prefijo,  ";
      $sql .= "                 A.factura_fiscal, ";
      $sql .= "                 A.empresa_id, ";
      $sql .= "                 A.total_nota_anulacion,";
      $sql .= "                 B.retencion  ";
      $sql .= "         FROM    (";
      $sql .= "                   SELECT  prefijo,  ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           empresa_id, ";
      $sql .= "                           tercero_id,";
      $sql .= "                           tipo_id_tercero,";
      $sql .= "                           total_nota_anulacion ";
      $sql .= "                   FROM    cartera.facturas_resumen";
      $sql .= "                   WHERE   empresa_id = '".$empresa."'";
      $sql .= "                   AND     intervalo = ".$rango." ";
      $sql .= "                   AND     total_nota_anulacion > 0 ";
      $sql .= "                 ) AS A, ";
      $sql .= "                 ( ";
      $sql .= "                   SELECT  prefijo,  ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           empresa_id, ";
      $sql .= "                           tercero_id,";
      $sql .= "                           tipo_id_tercero,";
      $sql .= "                           SUM(retencion) AS retencion ";
      $sql .= "                   FROM    cartera.facturas_resumen";
      $sql .= "                   WHERE   empresa_id = '".$empresa."'";
      $sql .= "                   AND     intervalo <= ".$rango." ";
      $sql .= "                   GROUP BY  prefijo,factura_fiscal,empresa_id,tercero_id,tipo_id_tercero ";
      $sql .= "                 ) AS B ";
      $sql .= "       WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "       AND    A.prefijo = B.prefijo ";
      $sql .= "       AND    A.factura_fiscal = B.factura_fiscal ";
      $sql .= "   ) AS A ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
        
			if(!$rst->EOF)
			{
				$datos['total_nota_anulacion'] = $rst->fields[0];
				$datos['retencion'] = $datos['retencion'] - $rst->fields[1];		
				$rst->MoveNext();
		  }
			$rst->Close();
      
			return $datos;
    }
		/**
		* Funcion donde se obtiene el nombre de un usuario segubn su id
    * 
    * @param integer $id Identificador del usuario
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
    /*
		* Funcion donde se elabora el arreglo de la cartera por plan
    *
    * @param array $datos Arreglo de datos de los filtros
    * @param string $opcion Indica cual cartera se buscara R->radicada N->no radicada
    *
		* @return boolean
		*/
    function ObtenerReporteTipoEntidad($datos,$opcion)
		{
      $rango = $datos['anyo'].$datos['mes'];
 			$fecha = date("Y-m-d", mktime(0, 0, 0,(intval($datos['mes'])+1), 0,$datos['anyo']));
 			$fechai = date("Y-m-d", mktime(0, 0, 0,(intval($datos['mes'])), 0,$datos['anyo']));
      			
      $facturacion = array();
      $datosc = array();
      $datosi = array();
            
      $datosc = $this->ObtenerResumenCartera($rango,$fecha,$datos['empresa_id'],$opcion);
      $datosi = $this->ObtenerResumenCarteraInicial($rango,$fecha,$datos['empresa_id'],$opcion);
            
      foreach($datosc as $key => $dtl)
      {
        foreach($dtl as $k1 => $dtl1)
          $datosi[$key][$k1]['descripcion'] = $k1;
      } 
      
      foreach($datosi as $key => $dtl)
      {
        foreach($dtl as $k1 => $dtl1)
          $datosc[$key][$k1]['descripcion'] = $k1;
      }
      
      ksort($datosc);
      
      if($opcion != 'N')
      {
        $datosi['-']['PAGARES']['debitos'] = $this->ObtenerResumenPagares(" < ".$rango,$datos['empresa_id']);
        $datosc['-']['PAGARES']['debitos'] = $this->ObtenerResumenPagares(" = ".$rango,$datos['empresa_id']);
      
        $abonos = $this->ObtenerAbonosAnticipos($datos['empresa_id'],$fechai);
        $descargos = $this->ObtenerDescargosAnticipos($datos['empresa_id'],$fechai);
        
        $datosi['-']['ANTICIPOS (-)']['debitos'] = $abonos - $descargos;
        $datosc['-']['ANTICIPOS (-)']['creditos'] = $this->ObtenerAbonosAnticipos($datos['empresa_id'],$fechai,$fecha);
        $datosc['-']['ANTICIPOS (-)']['debitos'] = $this->ObtenerDescargosAnticipos($datos['empresa_id'],$fechai,$fecha);
      }
			return array("inicio"=>$datosi,"final"=>$datosc);
		}
    /*
		* Funcion donde se elabora el arreglo de la cartera por cliente
    *
    * @param array $datos Arreglo de datos de los filtros
    * @param string $opcion Indica cual cartera se buscara R->radicada N->no radicada
    *
		* @return boolean
		*/
    function ObtenerReporteClientes($datos,$opcion)
		{
      $rango = $datos['anyo'].$datos['mes'];
 			$fecha = date("Y-m-d", mktime(0, 0, 0,(intval($datos['mes'])+1), 0,$datos['anyo']));
 			$fechai = date("Y-m-d", mktime(0, 0, 0,intval($datos['mes']), 0,$datos['anyo']));
      
      $facturacion = array();
      $datosc = array();
      $datosi = array();      
      
      $datosc = $this->ObtenerResumenCarteraClientes($rango,$fecha,$datos['empresa_id'],$opcion);
      $datosi = $this->ObtenerResumenCarteraClientesInicial($rango,$fecha,$datos['empresa_id'],$opcion);
      
      if($opcion != 'N')
      {
        $datosi['-']['-']['PAGARES']['debitos'] = $this->ObtenerResumenPagares(" < ".$rango,$datos['empresa_id']);
        $datosc['-']['-']['PAGARES']['debitos'] = $this->ObtenerResumenPagares(" = ".$rango,$datos['empresa_id']);
        
        $inicial = $this->ObtenerSaldoAnticiposClientes($datos['empresa_id'],$fechai);
        foreach($inicial as $k1 => $dt1)
        {
          foreach($dt1 as $k2 => $dt2)
          {
            foreach($dt2 as $k3 => $dt3)
            {
              $datosi[$k1][$k2][$k3]['debitos'] += $dt3['debitos'];
              $datosi[$k1][$k2][$k3]['creditos'] += $dt3['creditos'];
            }
          }
        }
        
        $anticipos = $this->ObtenerAbonosAnticiposCliente($datos['empresa_id'],$fechai,$fecha);
        $descargos = $this->ObtenerDescargosAnticiposClienteI($datos['empresa_id'],$fechai,$fecha);
        
        foreach($anticipos as $k1 => $dt1)
        {
          foreach($dt1 as $k2 => $dt2)
          {
            foreach($dt2 as $k3 => $dt3)
              $datosc[$k1][$k2][$k3]['creditos'] += $dt3['anticipos'];
          }
        }
        
        foreach($descargos as $k1 => $dt1)
        {
          foreach($dt1 as $k2 => $dt2)
          {
            foreach($dt2 as $k3 => $dt3)
              $datosc[$k1][$k2][$k3]['debitos'] += $dt3['descargos'];
          }
        }
      }
      
      foreach($datosc as $k1 => $dt1)
      {
        foreach($dt1 as $k2 => $dt2)
        {
          foreach($dt2 as $k3 => $dt3)
            $datosi[$k1][$k2][$k3]['nombre_tercero'] = $k3;
        }
      }
      
      foreach($datosi as $k1 => $dt1)
      {
        foreach($dt1 as $k2 => $dt2)
        {
          foreach($dt2 as $k3 => $dt3)
            $datosc[$k1][$k2][$k3]['nombre_tercero'] = $k3;
        }
      }
      
			return array("inicio"=>$datosi,"final"=>$datosc);
		}
    /**
    *
    */
    function ObtenerResumenCarteraInicial($rango,$fecha1,$empresa,$opcion)
    { 
      $sql  = "SELECT  TC.tipo_cliente,";
      $sql .= "        TC.descripcion,";
      $sql .= "        SUM(X.total_factura) + SUM(X.total_nota_debito) AS debitos, ";
      $sql .= "        SUM(X.retencion) + SUM(X.total_recibo) + SUM(X.total_nota_glosa) + SUM(X.total_nota_ajuste) + SUM(X.total_nota_credito) AS creditos ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  B.plan_id, ";
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
      $sql .= "                           SUM(total_factura) AS total_factura,";
      $sql .= "                           SUM(retencion) AS retencion,";
      $sql .= "                           SUM(total_nota_debito) AS total_nota_debito,";
      $sql .= "                           SUM(total_recibo) AS total_recibo,";
      $sql .= "                           SUM(total_nota_glosa) AS total_nota_glosa,";
      $sql .= "                           SUM(total_nota_ajuste) AS total_nota_ajuste,";
      $sql .= "                           SUM(total_nota_credito) AS total_nota_credito ";
      $sql .= "                    FROM   cartera.facturas_resumen";
      $sql .= "                    WHERE  empresa_id = '".$empresa."'";
      $sql .= "                    AND    intervalo < ".$rango." ";
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
      $sql .= "                   AND     fecha_registro::date < '".$fecha1."'::date ";
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
        $sql .= "                 AND     fecha_registro::date < '".$fecha1."'::date ";
      }
      $sql .= "                 ) AS B ";
      $sql .= "         WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "         AND    A.prefijo = B.prefijo ";
      $sql .= "         AND    A.factura_fiscal = B.factura_fiscal ";
      $sql .= "         AND    B.plan_id IS NOT NULL ";
      $sql .= "         GROUP BY B.plan_id ";
      $sql .= "        ) AS X, ";
      $sql .= "        planes PL, ";
      $sql .= "        tipos_cliente TC  ";
      $sql .= "WHERE   X.plan_id = PL.plan_id  ";
      $sql .= "AND     TC.tipo_cliente = PL.tipo_cliente  ";
      $sql .= "GROUP BY TC.tipo_cliente,TC.descripcion  ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);

        $rst->MoveNext();
      }
      
      $rst->Close();
      
      return $datos;
    }
    /**
    *
    */
    function ObtenerResumenCartera($rango,$fecha1,$empresa,$opcion)
    { 
      $sql  = "SELECT  TC.tipo_cliente,";
      $sql .= "        TC.descripcion,";
      $sql .= "        SUM(X.total_nota_debito) AS debitos, ";
      $sql .= "        SUM(X.total_recibo) + SUM(X.total_nota_glosa) + SUM(X.total_nota_ajuste) + SUM(X.total_nota_credito) AS creditos ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  B.plan_id, ";
      $sql .= "                 SUM(A.total_nota_debito) AS total_nota_debito,";
      $sql .= "                 SUM(A.total_recibo) AS total_recibo,";
      $sql .= "                 SUM(A.total_nota_glosa) AS total_nota_glosa,";
      $sql .= "                 SUM(A.total_nota_ajuste) AS total_nota_ajuste,";
      $sql .= "                 SUM(A.total_nota_credito) AS total_nota_credito ";
      $sql .= "         FROM    (";
      $sql .= "                   SELECT  prefijo,";
      $sql .= "                           factura_fiscal,";
      $sql .= "                           empresa_id,";
      $sql .= "                           SUM(total_nota_debito) AS total_nota_debito,";
      $sql .= "                           SUM(total_recibo) AS total_recibo,";
      $sql .= "                           SUM(total_nota_glosa) AS total_nota_glosa,";
      $sql .= "                           SUM(total_nota_ajuste) AS total_nota_ajuste,";
      $sql .= "                           SUM(total_nota_credito) AS total_nota_credito ";
      $sql .= "                    FROM   cartera.facturas_resumen";
      $sql .= "                    WHERE  empresa_id = '".$empresa."'";
      $sql .= "                    AND    intervalo = ".$rango." ";
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
      $sql .= "         GROUP BY B.plan_id ";
      $sql .= "        ) AS X, ";
      $sql .= "        planes PL, ";
      $sql .= "        tipos_cliente TC  ";
      $sql .= "WHERE   X.plan_id = PL.plan_id  ";
      $sql .= "AND     TC.tipo_cliente = PL.tipo_cliente  ";
      $sql .= "GROUP BY TC.tipo_cliente,TC.descripcion  ";
      
 			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);

        $rst->MoveNext();
      }
      
			$rst->Close();
			
      $sql  = "SELECT  TC.tipo_cliente,";
      $sql .= "        TC.descripcion,";
      $sql .= "        SUM(X.total_factura) AS debitos, ";
      $sql .= "        SUM(X.retencion) AS creditos ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  B.plan_id, ";
      $sql .= "                 SUM(A.total_factura) AS total_factura,";
      $sql .= "                 SUM(A.retencion) AS retencion ";
      $sql .= "         FROM    (";
      $sql .= "                    SELECT prefijo, ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           empresa_id,";
      $sql .= "                           SUM(total_factura) AS total_factura,";
      $sql .= "                           SUM(retencion) AS retencion ";
      $sql .= "                    FROM   cartera.facturas_resumen";
      $sql .= "                    WHERE  empresa_id = '".$empresa."'";
      $sql .= "                    AND    intervalo = ".$rango." ";
      $sql .= "                    GROUP BY prefijo, factura_fiscal,empresa_id ";
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
      $sql .= "         GROUP BY B.plan_id ";
      $sql .= "        ) AS X, ";
      $sql .= "        planes PL, ";
      $sql .= "        tipos_cliente TC  ";
      $sql .= "WHERE   X.plan_id = PL.plan_id  ";
      $sql .= "AND     TC.tipo_cliente = PL.tipo_cliente  ";
      $sql .= "GROUP BY TC.tipo_cliente,TC.descripcion  ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      while(!$rst->EOF)
      {
        if(empty($datos[$rst->fields[0]][$rst->fields[1]]))
          $datos[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
        else
        {
          $datos[$rst->fields[0]][$rst->fields[1]]['debitos'] += $rst->fields[2];
          $datos[$rst->fields[0]][$rst->fields[1]]['creditos'] += $rst->fields[3];
        }
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      $sql  = "SELECT  TC.tipo_cliente,";
      $sql .= "        TC.descripcion,";
      $sql .= "        SUM(X.total_nota_anulacion) - SUM(X.retencion) AS creditos ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  B.plan_id, ";
      $sql .= "                 SUM(A.retencion) AS retencion, ";
      $sql .= "                 SUM(A.total_nota_anulacion) AS total_nota_anulacion ";
      $sql .= "         FROM    (";
      $sql .= "                   SELECT  X.prefijo,  ";
      $sql .= "                           X.factura_fiscal, ";
      $sql .= "                           X.empresa_id, ";
      $sql .= "                           SUM(retencion) AS retencion, ";
      $sql .= "                           SUM(total_nota_anulacion) AS total_nota_anulacion  ";
      $sql .= "                   FROM    (";
      $sql .= "                             SELECT  prefijo,  ";
      $sql .= "                                     factura_fiscal, ";
      $sql .= "                                     empresa_id, ";
      $sql .= "                                     total_nota_anulacion ";
      $sql .= "                             FROM    cartera.facturas_resumen";
      $sql .= "                             WHERE   empresa_id = '".$empresa."'";
      $sql .= "                             AND     intervalo = ".$rango." ";
      $sql .= "                             AND     total_nota_anulacion > 0 ";
      $sql .= "                           ) X, ";
      $sql .= "                           ( ";
      $sql .= "                             SELECT  prefijo,  ";
      $sql .= "                                     factura_fiscal, ";
      $sql .= "                                     empresa_id, ";
      $sql .= "                                     SUM(retencion) AS retencion ";
      $sql .= "                             FROM    cartera.facturas_resumen";
      $sql .= "                             WHERE   empresa_id = '".$empresa."'";
      $sql .= "                             AND     intervalo <= ".$rango." ";
      $sql .= "                             GROUP BY  prefijo,factura_fiscal,empresa_id";
      $sql .= "                           ) Y";
      $sql .= "                   WHERE  X.empresa_id = Y.empresa_id ";
      $sql .= "                   AND    X.prefijo = Y.prefijo ";
      $sql .= "                   AND    X.factura_fiscal = Y.factura_fiscal ";
      $sql .= "                   GROUP BY  X.prefijo,X.factura_fiscal,X.empresa_id";
      $sql .= "                 ) A, ";
      
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
      $sql .= "                 ) B ";
      $sql .= "         WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "         AND    A.prefijo = B.prefijo ";
      $sql .= "         AND    A.factura_fiscal = B.factura_fiscal ";
      $sql .= "         AND    B.plan_id IS NOT NULL ";
      $sql .= "         GROUP BY B.plan_id ";
      $sql .= "        ) AS X, ";
      $sql .= "        planes PL, ";
      $sql .= "        tipos_cliente TC  ";
      $sql .= "WHERE   X.plan_id = PL.plan_id  ";
      $sql .= "AND     TC.tipo_cliente = PL.tipo_cliente  ";
      $sql .= "GROUP BY TC.tipo_cliente,TC.descripcion  ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      while(!$rst->EOF)
      {
        if(empty($datos[$rst->fields[0]][$rst->fields[1]]))
          $datos[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
        else
          $datos[$rst->fields[0]][$rst->fields[1]]['creditos'] += $rst->fields[2];
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      return $datos;
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
		function ObtenerAnticipos($fechai,$empresa,$facturacion)
		{
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
		function ObtenerRecibosAnticipos($fechai,$empresa)
		{
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
    /**
    *
    */
    function ObtenerResumenCarteraClientesInicial($rango,$fecha1,$empresa,$opcion,$filtros)
    {
      list($tipoId,$terceroId) = explode("/",$filtros['nombre_tercero']);
      
      /*$sql  = "SELECT  X.tipo_id_tercero, ";
      $sql .= "        X.tercero_id, ";
      $sql .= "        X.nombre_tercero, ";
      $sql .= "        SUM(X.total_factura) + SUM(X.total_nota_debito) AS debitos, ";
      $sql .= "        SUM(X.retencion) + SUM(X.total_recibo) + SUM(X.total_nota_glosa) + SUM(X.total_nota_ajuste) + SUM(X.total_nota_credito) AS creditos ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  TE.tipo_id_tercero, ";
      $sql .= "                 TE.tercero_id, ";
      $sql .= "                 TE.nombre_tercero, ";
      $sql .= "                 SUM(A.total_factura) AS total_factura,";
      $sql .= "                 SUM(A.retencion) AS retencion,";
      $sql .= "                 SUM(A.total_nota_debito) AS total_nota_debito,";
      $sql .= "                 SUM(A.total_recibo) AS total_recibo,";
      $sql .= "                 SUM(A.total_nota_glosa) AS total_nota_glosa,";
      $sql .= "                 SUM(A.total_nota_ajuste) AS total_nota_ajuste,";
      $sql .= "                 SUM(A.total_nota_credito) AS total_nota_credito ";
      $sql .= "         FROM    (";
      $sql .= "                   SELECT  BTRIM(tercero_id) AS tercero_id,";
      $sql .= "                           tipo_id_tercero,";
      $sql .= "                           prefijo,";
      $sql .= "                           factura_fiscal,";
      $sql .= "                           empresa_id,";
      $sql .= "                           SUM(total_factura) AS total_factura,";
      $sql .= "                           SUM(retencion) AS retencion,";
      $sql .= "                           SUM(total_nota_debito) AS total_nota_debito,";
      $sql .= "                           SUM(total_recibo) AS total_recibo,";
      $sql .= "                           SUM(total_nota_glosa) AS total_nota_glosa,";
      $sql .= "                           SUM(total_nota_ajuste) AS total_nota_ajuste,";
      $sql .= "                           SUM(total_nota_credito) AS total_nota_credito ";
      $sql .= "                    FROM   cartera.facturas_resumen";
      $sql .= "                    WHERE  empresa_id = '".$empresa."'";
      $sql .= "                    AND    intervalo < ".$rango." ";
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
      $sql .= "                   FROM    fac_facturas ";
      $sql .= "                   WHERE   empresa_id = '".$empresa."' ";
      $sql .= "                   AND     fecha_registro::date < '".$fecha1."'::date ";
      $sql .= "                   AND     sw_clase_factura = '1' ";
      $sql .= "                   AND     plan_id IS NOT NULL ";
      if($filtros['nombre_tercero'] != "0" && $filtros['nombre_tercero'])
      {
        $sql .= "                    AND    tipo_id_tercero = '".$tipoId."'";
        $sql .= "                    AND    tercero_id = '".$terceroId."' ";  
      }
      
      if($opcion == "R")
        $sql .= "                   AND     fecha_vencimiento_factura IS NOT NULL ";
      else if($opcion == "N")
        $sql .= "                   AND     fecha_vencimiento_factura IS NULL ";
      
      if($opcion != "N")
      {
        $sql .= "                 UNION ALL ";
        $sql .= "                 SELECT  prefijo,  ";
        $sql .= "                         factura_fiscal, ";
        $sql .= "                         empresa_id ";
        $sql .= "                 FROM    facturas_externas ";
        $sql .= "                 WHERE   empresa_id = '".$empresa."' ";
        $sql .= "                 AND     fecha_registro::date < '".$fecha1."'::date ";
        if($filtros['nombre_tercero'] != "0" && $filtros['nombre_tercero'])
        {
          $sql .= "                    AND    tipo_id_tercero = '".$tipoId."'";
          $sql .= "                    AND    tercero_id = '".$terceroId."' ";  
        }
      }
      $sql .= "                 ) AS B, ";
      $sql .= "                 terceros TE ";
      $sql .= "         WHERE   A.empresa_id = B.empresa_id ";
      $sql .= "         AND     A.prefijo = B.prefijo ";
      $sql .= "         AND     A.factura_fiscal = B.factura_fiscal ";
      $sql .= "         AND     A.tercero_id = TE.tercero_id ";
      $sql .= "         AND     A.tipo_id_tercero = TE.tipo_id_tercero ";
      $sql .= "         GROUP BY TE.tercero_id,TE.tipo_id_tercero,TE.nombre_tercero ";
      $sql .= "       ) X ";
      $sql .= "GROUP BY X.tercero_id,X.tipo_id_tercero,X.nombre_tercero ";
      $sql .= "ORDER BY X.nombre_tercero "; */  
      
      $sql  = "SELECT  X.tipo_id_tercero, ";
      $sql .= "        X.tercero_id, ";
      $sql .= "        X.nombre_tercero, ";
      $sql .= "        SUM(X.total_factura) + SUM(X.total_nota_debito) AS debitos, ";
      $sql .= "        SUM(X.retencion) + SUM(X.total_recibo) + SUM(X.total_nota_glosa) + SUM(X.total_nota_ajuste) + SUM(X.total_nota_credito) AS creditos ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  TE.tipo_id_tercero, ";
      $sql .= "                 TE.tercero_id, ";
      $sql .= "                 TE.nombre_tercero, ";
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
      $sql .= "                           SUM(total_factura) AS total_factura,";
      $sql .= "                           SUM(retencion) AS retencion,";
      $sql .= "                           SUM(total_nota_debito) AS total_nota_debito,";
      $sql .= "                           SUM(total_recibo) AS total_recibo,";
      $sql .= "                           SUM(total_nota_glosa) AS total_nota_glosa,";
      $sql .= "                           SUM(total_nota_ajuste) AS total_nota_ajuste,";
      $sql .= "                           SUM(total_nota_credito) AS total_nota_credito ";
      $sql .= "                    FROM   cartera.facturas_resumen";
      $sql .= "                    WHERE  empresa_id = '".$empresa."'";
      $sql .= "                    AND    intervalo < ".$rango." ";
      if($filtros['nombre_tercero'] != "0" && $filtros['nombre_tercero'])
      {
        $sql .= "                    AND    tipo_id_tercero = '".$tipoId."'";
        $sql .= "                    AND    tercero_id = '".$terceroId."' ";  
      }
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
      $sql .= "                   AND     fecha_registro::date < '".$fecha1."'::date ";
      $sql .= "                   AND     sw_clase_factura = '1' ";
      $sql .= "                   AND     plan_id IS NOT NULL ";
      if($filtros['nombre_tercero'] != "0" && $filtros['nombre_tercero'])
      {
        $sql .= "                    AND    tipo_id_tercero = '".$tipoId."'";
        $sql .= "                    AND    tercero_id = '".$terceroId."' ";  
      }
      
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
        $sql .= "                 AND     fecha_registro::date < '".$fecha1."'::date ";
        if($filtros['nombre_tercero'] != "0" && $filtros['nombre_tercero'])
        {
          $sql .= "                    AND    tipo_id_tercero = '".$tipoId."'";
          $sql .= "                    AND    tercero_id = '".$terceroId."' ";  
        }
      }
      $sql .= "                 ) AS B, ";
      $sql .= "                 terceros TE, ";
      $sql .= "                 planes PL ";
      $sql .= "         WHERE   A.empresa_id = B.empresa_id ";
      $sql .= "         AND     A.prefijo = B.prefijo ";
      $sql .= "         AND     A.factura_fiscal = B.factura_fiscal ";
      $sql .= "         AND     B.plan_id = PL.plan_id ";
      $sql .= "         AND     PL.tercero_id = TE.tercero_id ";
      $sql .= "         AND     PL.tipo_tercero_id = TE.tipo_id_tercero ";
      $sql .= "         GROUP BY TE.tercero_id,TE.tipo_id_tercero,TE.nombre_tercero ";
      $sql .= "       ) X ";
      $sql .= "GROUP BY X.tercero_id,X.tipo_id_tercero,X.nombre_tercero ";
      $sql .= "ORDER BY X.nombre_tercero ";
      
 			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			

      while(!$rst->EOF)
      {
		    if(empty($datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]]))
          $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
        else
        {
          $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]]['debitos'] += $rst->fields[3];
          $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]]['creditos'] += $rst->fields[4];
        }
        $rst->MoveNext();
      }
		  
			$rst->Close();
			
			return $datos;
    }
    /**
		* Funcion donde se obtiene el valor de los anticipos hechos de acuerdo a unas
    * fechas dadas
    *
    * @param string $empresa Identificador de la empresa
		* @param date $fecha1 Fecha inicial, si se llega $fecha2, si no es la fecha 
    *                     hasta la cual se consultaran los anticipos
    * @param date $fecha2 Fecha final para la consulta de los anticipos
    *
    * @return mixed
		*/
		function ObtenerAnticiposCliente($empresa,$fecha1)
		{
      $sql  = "SELECT TE.tipo_id_tercero, ";
      $sql .= "       TE.tercero_id, ";
      $sql .= "       TE.nombre_tercero, ";
      $sql .= "       SUM(RC.total_abono) AS anticipos ";
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
			$sql .= "AND		RC.fecha_ingcaja::date <= '".$fecha1."' ";
      if($filtros['nombre_tercero'] && $filtros['nombre_tercero'] != '0')
      {
        $sql .= "AND		RC.tipo_id_tercero = '".$terceroId."' ";
        $sql .= "AND		RC.tercero_id = '".$tipoId."'";
			}
      
      $sql .= "AND   RC.tercero_id = TE.tercero_id ";
      $sql .= "AND   RC.tipo_id_tercero = TE.tipo_id_tercero ";
      $sql .= "GROUP BY TE.tipo_id_tercero,TE.tercero_id,TE.nombre_tercero ";
			
      $datos = array();
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
		* Funcion donde se obtiene el valor de los descargos de los anticipos hechos 
    * de acuerdo a unas fechas dadas
    *
    * @param string $empresa Identificador de la empresa
		* @param date $fecha1 Fecha inicial, si se llega $fecha2, si no es la fecha 
    *                     hasta la cual se consultaran los descargos
    *
    * @return mixed
		*/
		function ObtenerDescargosAnticiposCliente($empresa,$fecha1)
		{
			$sql  = "SELECT TE.tipo_id_tercero, ";
      $sql .= "       TE.tercero_id, ";
      $sql .= "       TE.nombre_tercero, ";
      $sql .= "       SUM(RC.total_abono) AS descargos ";
			$sql .= "FROM	  recibos_caja RC,  ";
			$sql .= "			  rc_tipos_documentos RD,  ";
      $sql .= "				terceros TE ";
			$sql .= "WHERE	RC.empresa_id = '".$empresa."'  ";
			$sql .= "AND		RC.estado = '2'::bpchar ";
			$sql .= "AND		RC.sw_recibo_tesoreria = '1' ";
			$sql .= "AND		RD.sw_cruzar_anticipos = '1' ";
			$sql .= "AND		RD.rc_tipo_documento = RC.rc_tipo_documento ";
			$sql .= "AND		RC.fecha_ingcaja::date <= '".$fecha1."' ";
      $sql .= "AND    RC.tercero_id = TE.tercero_id ";
      $sql .= "AND    RC.tipo_id_tercero = TE.tipo_id_tercero ";
      $sql .= "GROUP BY TE.tipo_id_tercero,TE.tercero_id,TE.nombre_tercero ";
      
			$datos = array();
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
    function ObtenerResumenCarteraClientes($rango,$fecha1,$empresa,$opcion)
    { 
      $sql  = "SELECT  TE.tipo_id_tercero, ";
      $sql .= "        TE.tercero_id, ";
      $sql .= "        TE.nombre_tercero, ";
      $sql .= "        SUM(X.total_nota_debito) AS debitos, ";
      $sql .= "        SUM(X.total_recibo) + SUM(X.total_nota_glosa) + SUM(X.total_nota_ajuste) + SUM(X.total_nota_credito) AS creditos ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  BTRIM(tercero_id) AS tercero_id, ";
      $sql .= "                 B.tipo_id_tercero, ";
      $sql .= "                 SUM(A.total_nota_debito) AS total_nota_debito,";
      $sql .= "                 SUM(A.total_recibo) AS total_recibo,";
      $sql .= "                 SUM(A.total_nota_glosa) AS total_nota_glosa,";
      $sql .= "                 SUM(A.total_nota_ajuste) AS total_nota_ajuste,";
      $sql .= "                 SUM(A.total_nota_credito) AS total_nota_credito ";
      $sql .= "         FROM    (";
      $sql .= "                   SELECT  prefijo,";
      $sql .= "                           factura_fiscal,";
      $sql .= "                           empresa_id,";
      $sql .= "                           SUM(total_nota_debito) AS total_nota_debito,";
      $sql .= "                           SUM(total_recibo) AS total_recibo,";
      $sql .= "                           SUM(total_nota_glosa) AS total_nota_glosa,";
      $sql .= "                           SUM(total_nota_ajuste) AS total_nota_ajuste,";
      $sql .= "                           SUM(total_nota_credito) AS total_nota_credito ";
      $sql .= "                    FROM   cartera.facturas_resumen";
      $sql .= "                    WHERE  empresa_id = '".$empresa."'";
      $sql .= "                    AND    intervalo = ".$rango." ";
      $sql .= "                    GROUP BY prefijo, factura_fiscal,empresa_id ";
      $sql .= "                    HAVING  SUM(total_nota_anulacion) = 0 ";
      $sql .= "                 ) AS A, ";
      $sql .= "                 ( ";
      $sql .= "                   SELECT  prefijo,  ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           empresa_id, ";
      $sql .= "                           tercero_id, ";
      $sql .= "                           tipo_id_tercero ";
      $sql .= "                   FROM    fac_facturas ";
      $sql .= "                   WHERE   empresa_id = '".$empresa."' ";
      $sql .= "                   AND     fecha_registro::date <= '".$fecha1."'::date ";
      $sql .= "                   AND     sw_clase_factura = '1' ";
      $sql .= "                   AND     plan_id IS NOT NULL ";
      
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
        $sql .= "                         tercero_id, ";
        $sql .= "                         tipo_id_tercero ";
        $sql .= "                 FROM    facturas_externas ";
        $sql .= "                 WHERE   empresa_id = '".$empresa."' ";
        $sql .= "                 AND     fecha_registro::date <= '".$fecha1."'::date ";
      }
      $sql .= "                 ) AS B ";
      $sql .= "         WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "         AND    A.prefijo = B.prefijo ";
      $sql .= "         AND    A.factura_fiscal = B.factura_fiscal ";
      $sql .= "         GROUP BY B.tipo_id_tercero,B.tercero_id ";
      $sql .= "        ) AS X, ";
      $sql .= "        terceros TE  ";
      $sql .= "WHERE   X.tipo_id_tercero = TE.tipo_id_tercero  ";
      $sql .= "AND     X.tercero_id = TE.tercero_id  ";
      $sql .= "GROUP BY TE.tipo_id_tercero,TE.tercero_id,TE.nombre_tercero  ";
      $sql .= "ORDER BY TE.nombre_tercero ";
      
 			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $datos = array();
      while(!$rst->EOF)
      {
        if(empty($datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]]))
          $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
        else
        {
          $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]]['creditos'] += $rst->fields[4];
          $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]]['debitos'] += $rst->fields[3];
        }
        $rst->MoveNext();
      }
      
			$rst->Close();
			
      $sql  = "SELECT  TE.tipo_id_tercero, ";
      $sql .= "        TE.tercero_id, ";
      $sql .= "        TE.nombre_tercero, ";
      $sql .= "        SUM(X.total_factura) AS debitos, ";
      $sql .= "        SUM(X.retencion) AS creditos ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  BTRIM(tercero_id) AS tercero_id, ";
      $sql .= "                 B.tipo_id_tercero, ";
      $sql .= "                 SUM(A.total_factura) AS total_factura,";
      $sql .= "                 SUM(A.retencion) AS retencion ";
      $sql .= "         FROM    (";
      $sql .= "                    SELECT prefijo, ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           empresa_id,";
      $sql .= "                           SUM(total_factura) AS total_factura,";
      $sql .= "                           SUM(retencion) AS retencion ";
      $sql .= "                    FROM   cartera.facturas_resumen";
      $sql .= "                    WHERE  empresa_id = '".$empresa."'";
      $sql .= "                    AND    intervalo = ".$rango." ";
      $sql .= "                    GROUP BY prefijo, factura_fiscal,empresa_id ";
      $sql .= "                 ) AS A, ";   
      $sql .= "                 ( ";
      $sql .= "                   SELECT  prefijo,  ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           empresa_id, ";
      $sql .= "                           tercero_id, ";
      $sql .= "                           tipo_id_tercero ";
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
        $sql .= "                   UNION ALL ";
        $sql .= "                   SELECT  prefijo,  ";
        $sql .= "                           factura_fiscal, ";
        $sql .= "                           empresa_id, ";
        $sql .= "                           tercero_id, ";
        $sql .= "                           tipo_id_tercero ";
        $sql .= "                   FROM    facturas_externas ";
        $sql .= "                   WHERE   empresa_id = '".$empresa."' ";
        $sql .= "                   AND     fecha_registro::date <= '".$fecha1."'::date ";
      }
      $sql .= "                 ) AS B ";
      $sql .= "         WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "         AND    A.prefijo = B.prefijo ";
      $sql .= "         AND    A.factura_fiscal = B.factura_fiscal ";
      $sql .= "         GROUP BY B.tipo_id_tercero,B.tercero_id ";
      $sql .= "        ) AS X, ";
      $sql .= "        terceros TE  ";
      $sql .= "WHERE   X.tipo_id_tercero = TE.tipo_id_tercero  ";
      $sql .= "AND     X.tercero_id = TE.tercero_id  ";
      $sql .= "GROUP BY TE.tipo_id_tercero,TE.tercero_id,TE.nombre_tercero  ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      while(!$rst->EOF)
      {
        if(empty($datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]]))
          $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
        else
        {
          $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]]['debitos'] += $rst->fields[3];
          $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]]['creditos'] += $rst->fields[4];
        }
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      $sql  = "SELECT  TE.tipo_id_tercero, ";
      $sql .= "        TE.tercero_id, ";
      $sql .= "        TE.nombre_tercero, ";
      $sql .= "        SUM(X.total_nota_anulacion) - SUM(X.retencion) AS creditos ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  BTRIM(tercero_id) AS tercero_id, ";
      $sql .= "                 B.tipo_id_tercero, ";
      $sql .= "                 SUM(A.retencion) AS retencion, ";
      $sql .= "                 SUM(A.total_nota_anulacion) AS total_nota_anulacion ";
      $sql .= "         FROM    (";
      $sql .= "                   SELECT  X.prefijo,  ";
      $sql .= "                           X.factura_fiscal, ";
      $sql .= "                           X.empresa_id, ";
      $sql .= "                           SUM(retencion) AS retencion, ";
      $sql .= "                           SUM(total_nota_anulacion) AS total_nota_anulacion,  ";
      $sql .= "                           SUM(total_nota_debito) AS total_nota_debito,";
      $sql .= "                           SUM(total_nota_glosa) AS total_nota_glosa,";
      $sql .= "                           SUM(total_nota_ajuste) AS total_nota_ajuste,";
      $sql .= "                           SUM(total_nota_credito) AS total_nota_credito ";

      $sql .= "                   FROM    (";
      $sql .= "                             SELECT  prefijo,  ";
      $sql .= "                                     factura_fiscal, ";
      $sql .= "                                     empresa_id, ";
      $sql .= "                                     total_nota_anulacion, ";
      $sql .= "                                     total_nota_debito,";
      $sql .= "                                     total_nota_glosa,";
      $sql .= "                                     total_nota_ajuste,";
      $sql .= "                                     total_nota_credito ";

      $sql .= "                             FROM    cartera.facturas_resumen";
      $sql .= "                             WHERE   empresa_id = '".$empresa."'";
      $sql .= "                             AND     intervalo = ".$rango." ";
      $sql .= "                             AND     total_nota_anulacion > 0 ";
      $sql .= "                           ) X, ";
      $sql .= "                           ( ";
      $sql .= "                             SELECT  prefijo,  ";
      $sql .= "                                     factura_fiscal, ";
      $sql .= "                                     empresa_id, ";
      $sql .= "                                     SUM(retencion) AS retencion ";
      $sql .= "                             FROM    cartera.facturas_resumen";
      $sql .= "                             WHERE   empresa_id = '".$empresa."'";
      $sql .= "                             AND     intervalo <= ".$rango." ";
      $sql .= "                             GROUP BY  prefijo,factura_fiscal,empresa_id";
      $sql .= "                           ) Y";
      $sql .= "                   WHERE  X.empresa_id = Y.empresa_id ";
      $sql .= "                   AND    X.prefijo = Y.prefijo ";
      $sql .= "                   AND    X.factura_fiscal = Y.factura_fiscal ";
      $sql .= "                   GROUP BY  X.prefijo,X.factura_fiscal,X.empresa_id";
      $sql .= "                 ) A, ";
      
      $sql .= "                 ( ";
      $sql .= "                   SELECT  prefijo,  ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           empresa_id, ";
      $sql .= "                           tercero_id, ";
      $sql .= "                           tipo_id_tercero ";
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
        $sql .= "                         tercero_id, ";
        $sql .= "                         tipo_id_tercero ";
        $sql .= "                 FROM    facturas_externas ";
        $sql .= "                 WHERE   empresa_id = '".$empresa."' ";
        $sql .= "                 AND     fecha_registro::date <= '".$fecha1."'::date ";
      }
      $sql .= "                 ) B ";
      $sql .= "         WHERE  A.empresa_id = B.empresa_id ";
      $sql .= "         AND    A.prefijo = B.prefijo ";
      $sql .= "         AND    A.factura_fiscal = B.factura_fiscal ";
      $sql .= "         GROUP BY B.tipo_id_tercero,B.tercero_id ";
      $sql .= "        ) AS X, ";
      $sql .= "        terceros TE  ";
      $sql .= "WHERE   X.tipo_id_tercero = TE.tipo_id_tercero  ";
      $sql .= "AND     X.tercero_id = TE.tercero_id  ";
      $sql .= "GROUP BY TE.tipo_id_tercero,TE.tercero_id,TE.nombre_tercero  ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      while(!$rst->EOF)
      {
        if(empty($datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]]))
          $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
        else
          $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]]['creditos'] += $rst->fields[3];
        $rst->MoveNext();
      }
      
      $rst->Close();
      
      return $datos;
    }
    /**
    * Funcion donde se obtiene la cartera de los pagares
    *
    * @param string $rango Condicion para la consulta (ej "<= 200803")
    * @param string $empresa Identificador de la empresa
    *
    * @return mixed
    */
    function ObtenerResumenPagares($rango,$empresa)
    {
      $sql  = "SELECT  SUM(valor) - SUM(valor_abonos) AS total_intervalo ";
      $sql .= "FROM    (";
      $sql .= "          SELECT  prefijo,";
      $sql .= "                  numero,";
      $sql .= "                  empresa_id,";
      $sql .= "                  CASE WHEN SUM(valor_modificado) > 0 THEN SUM(valor_modificado) ";
      $sql .= "                       ELSE SUM(valor) END AS valor, ";
      $sql .= "                  SUM(valor_anulacion) AS valor_anulacion, ";
      $sql .= "                  SUM(valor_abonos) AS valor_abonos ";
      $sql .= "          FROM   cartera.pagares_resumen ";
      $sql .= "          WHERE  empresa_id = '".$empresa."'";
      $sql .= "          AND    intervalo ".$rango." ";
      $sql .= "          GROUP BY prefijo,numero,empresa_id ";
      $sql .= "          HAVING SUM(valor_anulacion) = 0 ";
      $sql .= "        ) PA ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos['total_intervalo'];
    }
    /**
		*
		* @return boolean
		*/
		function ObtenerAbonosAnticipos($empresa,$fecha1,$fecha2)
		{
      $sql  = "SELECT SUM(RC.total_abono) AS saldo ";
			$sql .= "FROM		recibos_caja RC, ";
			$sql .= "				rc_detalle_tesoreria_conceptos RS ";
			$sql .= "WHERE	RC.empresa_id = '".$empresa."' ";
			$sql .= "AND		RC.estado = '2' ";
			$sql .= "AND		RC.empresa_id = RS.empresa_id "; 	
			$sql .= "AND		RC.recibo_caja = RS.recibo_caja ";
			$sql .= "AND		RC.prefijo = RS.prefijo ";
			$sql .= "AND		RS.concepto_id = 'C013' ";
      $sql .= "AND		RC.sw_recibo_tesoreria = '1' ";
      if(!$fecha2)
        $sql .= "AND		RC.fecha_ingcaja::date <= '".$fecha1."' ";
      else
      {
        $sql .= "AND		RC.fecha_ingcaja::date > '".$fecha1."' ";
        $sql .= "AND		RC.fecha_ingcaja::date <= '".$fecha2."' ";
      }
			
      $datos = array();
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos['saldo'];			
		}
        /**
		* Funcion donde se obtiene el valor de los anticipos hechos de acuerdo a unas
    * fechas dadas
    *
    * @param string $empresa Identificador de la empresa
		* @param date $fecha1 Fecha inicial, si se llega $fecha2, si no es la fecha 
    *                     hasta la cual se consultaran los anticipos
    * @param date $fecha2 Fecha final para la consulta de los anticipos
    *
    * @return mixed
		*/
		function ObtenerAbonosAnticiposCliente($empresa,$fecha1,$fecha2)
		{
      $sql  = "SELECT TE.tipo_id_tercero, ";
      $sql .= "       TE.tercero_id, ";
      $sql .= "       TE.nombre_tercero, ";
      $sql .= "       SUM(RC.total_abono) AS anticipos ";
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
			
      if(!$fecha2)
        $sql .= "AND		RC.fecha_ingcaja::date <= '".$fecha1."' ";
      else
      {
        $sql .= "AND		RC.fecha_ingcaja::date > '".$fecha1."' ";
        $sql .= "AND		RC.fecha_ingcaja::date <= '".$fecha2."' ";
      }
      
      if($filtros['nombre_tercero'] && $filtros['nombre_tercero'] != '0')
      {
        $sql .= "AND		RC.tipo_id_tercero = '".$terceroId."' ";
        $sql .= "AND		RC.tercero_id = '".$tipoId."'";
			}
      
      $sql .= "AND   RC.tercero_id = TE.tercero_id ";
      $sql .= "AND   RC.tipo_id_tercero = TE.tipo_id_tercero ";
      $sql .= "GROUP BY TE.tipo_id_tercero,TE.tercero_id,TE.nombre_tercero ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
        $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;			
		}
    /**
		*
		* @return boolean
		*/
		function ObtenerDescargosAnticipos($empresa,$fecha1,$fecha2)
		{
			$sql  = "SELECT SUM(RC.total_abono) AS saldo ";
			$sql .= "FROM	  recibos_caja RC,  ";
			$sql .= "			  rc_tipos_documentos RD  ";
			$sql .= "WHERE	RC.empresa_id = '".$empresa."'  ";
			$sql .= "AND		RC.estado = '2'::bpchar ";
			$sql .= "AND		RC.sw_recibo_tesoreria = '1' ";
			$sql .= "AND		RD.sw_cruzar_anticipos = '1' ";
			$sql .= "AND		RD.rc_tipo_documento = RC.rc_tipo_documento ";
      if(!$fecha2)
        $sql .= "AND		RC.fecha_ingcaja::date <= '".$fecha1."' ";
      else
      {
        $sql .= "AND		RC.fecha_ingcaja::date > '".$fecha1."' ";
        $sql .= "AND		RC.fecha_ingcaja::date <= '".$fecha2."' ";
      }
      
			$datos = array();
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos['saldo'];	
		}
    /**
		* Funcion donde se obtiene el valor de los descargos de los anticipos hechos 
    * de acuerdo a unas fechas dadas
    *
    * @param string $empresa Identificador de la empresa
		* @param date $fecha1 Fecha inicial, si se llega $fecha2, si no es la fecha 
    *                     hasta la cual se consultaran los descargos
    *
    * @return mixed
		*/
		function ObtenerDescargosAnticiposClienteI($empresa,$fecha1,$fecha2)
		{
			$sql  = "SELECT TE.tipo_id_tercero, ";
      $sql .= "       TE.tercero_id, ";
      $sql .= "       TE.nombre_tercero, ";
      $sql .= "       SUM(RC.total_abono) AS descargos ";
			$sql .= "FROM	  recibos_caja RC,  ";
			$sql .= "			  rc_tipos_documentos RD,  ";
      $sql .= "				terceros TE ";
			$sql .= "WHERE	RC.empresa_id = '".$empresa."'  ";
			$sql .= "AND		RC.estado = '2'::bpchar ";
			$sql .= "AND		RC.sw_recibo_tesoreria = '1' ";
			$sql .= "AND		RD.sw_cruzar_anticipos = '1' ";
			$sql .= "AND		RD.rc_tipo_documento = RC.rc_tipo_documento ";
      $sql .= "AND    RC.tercero_id = TE.tercero_id ";
      $sql .= "AND    RC.tipo_id_tercero = TE.tipo_id_tercero ";
      
      if(!$fecha2)
        $sql .= "AND		RC.fecha_ingcaja::date <= '".$fecha1."' ";
      else
      {
        $sql .= "AND		RC.fecha_ingcaja::date > '".$fecha1."' ";
        $sql .= "AND		RC.fecha_ingcaja::date <= '".$fecha2."' ";
      }
      $sql .= "GROUP BY TE.tipo_id_tercero,TE.tercero_id,TE.nombre_tercero ";
      
			$datos = array();
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
        $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;	
		}
    /**
		* Funcion donde se obtiene el valor de los descargos de los anticipos hechos 
    * de acuerdo a unas fechas dadas
    *
    * @param string $empresa Identificador de la empresa
		* @param date $fecha1 Fecha inicial, si se llega $fecha2, si no es la fecha 
    *                     hasta la cual se consultaran los descargos
    *
    * @return mixed
		*/
		function ObtenerSaldoAnticiposClientes($empresa,$fecha1)
		{
			$sql  = "SELECT TE.tipo_id_tercero, ";
      $sql .= "       TE.tercero_id, ";
      $sql .= "       TE.nombre_tercero, ";
      $sql .= "       SUM(RC.total_abono) AS creditos ";
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
      $sql .= "AND		RC.fecha_ingcaja::date <= '".$fecha1."'::date ";
      $sql .= "AND    RC.tercero_id = TE.tercero_id ";
      $sql .= "AND    RC.tipo_id_tercero = TE.tipo_id_tercero ";
      $sql .= "GROUP BY TE.tipo_id_tercero,TE.tercero_id,TE.nombre_tercero ";
			
      $datos = array();
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
        $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
		  }
			$rst->Close();
			
			
      $sql  = "SELECT TE.tipo_id_tercero, ";
      $sql .= "       TE.tercero_id, ";
      $sql .= "       TE.nombre_tercero, ";
      $sql .= "       SUM(RC.total_abono) AS debitos ";
			$sql .= "FROM	  recibos_caja RC,  ";
			$sql .= "			  rc_tipos_documentos RD,  ";
      $sql .= "				terceros TE ";
			$sql .= "WHERE	RC.empresa_id = '".$empresa."'  ";
			$sql .= "AND		RC.estado = '2'::bpchar ";
			$sql .= "AND		RC.sw_recibo_tesoreria = '1' ";
			$sql .= "AND		RD.sw_cruzar_anticipos = '1' ";
			$sql .= "AND		RD.rc_tipo_documento = RC.rc_tipo_documento ";
      $sql .= "AND    RC.tercero_id = TE.tercero_id ";
      $sql .= "AND    RC.tipo_id_tercero = TE.tipo_id_tercero ";
      $sql .= "AND		RC.fecha_ingcaja::date <= '".$fecha1."'::date ";
      $sql .= "GROUP BY TE.tipo_id_tercero,TE.tercero_id,TE.nombre_tercero ";
      
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
        if(empty($datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]]))
          $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
        else
          $datos["'".$rst->fields[0]."'"]["'".$rst->fields[1]."'"][$rst->fields[2]]['creditos'] -= $rst->fields[3];
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;	
		}
	}
?>