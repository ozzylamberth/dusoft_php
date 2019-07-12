<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: GestionEstados.class.php,v 1.1 2008/10/10 22:27:29 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : CuentsXPagar
  * Clase en la que se maneja la logica de cuentas por pagar
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class GestionEstados extends ConexionBD
  {
    /**
    * Contructor de la clase
    */
    function GestionEstados(){}
    /**
    * Retorna el error generado en cualquiera de los proceso realizados por esta clase
    *
    * @return String
    */
    function ObtenerError()
    {
      return $this->error;
    }
    /**
		* Funcion domde se obtienen los tipos de identificacion de terceros 
		* 
		* @return mixed 
		*/
		function ObtenerTipoIdTerceros()
		{
			$sql  = "SELECT tipo_id_tercero, ";
      $sql .= "       descripcion ";
      $sql .= "FROM   tipo_id_terceros ";
      $sql .= "ORDER BY descripcion ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;

      $datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
    /**
    * Funcion donde se obtienen las facturas que pueden ser cambiadas de estado
    *
    * @param String $empresa Identificador de la empresa
    * @param array $datos Arreglo con los datos de los filtros de busquedad
    * @param integer $offset Referencia del paginador
    *
    * @return array
    */
    function ObtenerFacturas($empresa,$datos,$offset)
    {  
      $sql  = "SELECT DISTINCT CF.prefijo_factura,";
      $sql .= "       CF.numero_factura, ";
      $sql .= "       CF.empresa_id, ";
      $sql .= "       TO_CHAR(CF.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
      $sql .= "       TO_CHAR(CF.fecha_documento,'DD/MM/YYYY') AS fecha_documento, ";
      $sql .= "       TO_CHAR(CR.fecha_radicacion,'DD/MM/YYYY') AS fecha_radicacion, ";
      $sql .= "       CF.valor_total 	, ";
      $sql .= "       CF.prefijo, ";
      $sql .= "       CF.numero, ";
      $sql .= "       CH.fecha_estado, ";
      $sql .= "       CR.cxp_radicacion_id, ";
      $sql .= "       CT.tipo_cxp_descripcion, ";
      $sql .= "       CE.cxp_estado_descripcion, ";
      $sql .= "       TP.tipo_id_tercero, ";
      $sql .= "       TP.tercero_id, ";
      $sql .= "       TP.nombre_tercero ";
      $sql .= "FROM   cxp_facturas CF  ";
      $sql .= "       LEFT JOIN  ( ";
      $sql .= "         SELECT  MAX(cxp_historico_estado_id), ";
      $sql .= "                 empresa_id 	, ";
      $sql .= "                 prefijo, ";
      $sql .= "                 numero, ";
      $sql .= "                 TO_CHAR(fecha_registro,'DD/MM/YYYY') AS fecha_estado ";
      $sql .= "         FROM    cxp_historico_estados "; 
      $sql .= "         WHERE   empresa_id = '".$empresa."' ";  
      $sql .= "         GROUP BY empresa_id,prefijo,numero,fecha_registro "; 
      $sql .= "       ) AS CH ";  
      $sql .= "       ON (CF.empresa_id = CH.empresa_id 	AND ";
      $sql .= "           CF.prefijo = CH.prefijo AND ";
      $sql .= "           CF.numero = CH.numero ), ";
      $sql .= "       cxp_radicacion CR, ";
      $sql .= "       cxp_tipos CT, ";
      $sql .= "       cxp_estados CE, ";
      $sql .= "       terceros TP ";
      $sql .= "WHERE  CF.tipo_cxp = CT.tipo_cxp ";
      $sql .= "AND    CF.empresa_id = '".$empresa."' ";  
      $sql .= "AND    CF.tercero_id = TP.tercero_id ";
      $sql .= "AND    CF.tipo_id_tercero = TP.tipo_id_tercero ";
      $sql .= "AND    CR.cxp_radicacion_id = CF.cxp_radicacion_id ";
      $sql .= "AND    CF.cxp_estado = CE.cxp_estado ";
      $sql .= "AND    CF.cxp_estado NOT IN ('OP','PA') ";

      if($datos['numero_radicacion'])
        $sql .= "AND   CR.cxp_radicacion_id = ".$datos['numero_radicacion']." ";
        
      if($datos['prefijo'])
        $sql .= "AND   CF.prefijo_factura ILIKE '".$datos['prefijo']."' ";
      
      if($datos['factura'])
        $sql .= "AND   CF.numero_factura = ".$datos['factura']." ";
      
      if($datos['fecha_inicio'])
        $sql .= "AND   CR.fecha_radicacion >= '".$this->DividirFecha($datos['fecha_inicio'])."'::date ";
      
      if($datos['fecha_fin'])
        $sql .= "AND   CR.fecha_radicacion <= '".$this->DividirFecha($datos['fecha_fin'])."'::date ";
          
      if($datos['tipo_id_tercero'] != '-1' && $datos['tipo_id_tercero'])
      {
        $sql .= "AND    CF.tercero_id = '".$datos['tercero_id']."' ";
        $sql .= "AND    CF.tipo_id_tercero = '".$datos['tipo_id_tercero']."' ";    
      }
      
      $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
      $this->ProcesarSqlConteo($cont,$offset);
      
      $sql .= "ORDER BY CR.cxp_radicacion_id ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $retorno;
    }
    /**
    * Funcion donde se obtienen las pre-ordenes de pago
    *
    * @param string $empresa Identificador de la empresa
    * @param array $datos Arreglo con los datos de los filtros de busquedad
    * @param string $offset Referencia del paginador
    * @param string $estado Referencia del estado, en cual no deben estar las pre-ordenes de pagpo ej ( "0','3")
    * @paran string $medio_pago Referencia al medio de pago que deben tener las factuars
    *
    * @return mixed
    */
    function ObtenerPreOrdenesPagos($empresa,$datos,$offset,$estado,$medio_pago)
    { 
      $sql  = "SELECT DISTINCT CP.cxp_orden_pago_id,";
      $sql .= "       CP.observacion_estado,";
      $sql .= " 	    CP.num_orden_gasto,";
      $sql .= " 	    CP.sw_estado,";
      $sql .= " 	    TO_CHAR(CP.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
      $sql .= " 	    US.nombre ";
      $sql .= "FROM   cxp_ordenes_pagos CP, ";
      $sql .= "       cxp_ordenes_pagos_d CD, ";
      $sql .= "       system_usuarios US, ";
      $sql .= "       ( SELECT  CF.prefijo,";
      $sql .= "                 CF.numero, ";
      $sql .= "                 CF.empresa_id ";
      $sql .= "         FROM    cxp_facturas CF, ";
      $sql .= "                 cxp_radicacion CR, ";
      $sql .= "                 cxp_tipos CT ";
      $sql .= "         WHERE   CF.tipo_cxp = CT.tipo_cxp ";
      $sql .= "         AND     CF.empresa_id = '".$empresa."' ";
      $sql .= "         AND     CR.cxp_radicacion_id = CF.cxp_radicacion_id ";
      $sql .= "         AND     CR.cxp_medio_pago_id = '".$medio_pago."' ";
      
      if($datos['prefijo'])
        $sql .= "         AND   CF.prefijo_factura ILIKE '".$datos['prefijo']."' ";
      
      if($datos['factura'])
        $sql .= "         AND   CF.numero_factura = ".$datos['factura']." ";
      
      if($datos['numero_radicacion'])
        $sql .= "         AND   CR.cxp_radicacion_id = ".$datos['numero_radicacion']." ";

      
      $sql .= "       ) AS CF ";
      $sql .= "WHERE  CF.prefijo = CD.prefijo ";
      $sql .= "AND    CF.numero = CD.numero ";
      $sql .= "AND    CF.empresa_id = CD.empresa_id ";
      $sql .= "AND    CD.cxp_orden_pago_id = CP.cxp_orden_pago_id ";
      $sql .= "AND    CP.sw_estado NOT IN('".$estado."') ";
      $sql .= "AND    US.usuario_id = CP.usuario_registro ";
      
      if($datos['numero_orden_pago'])
        $sql .= "AND   CP.cxp_orden_pago_id = ".$datos['numero_orden_pago']." ";
      
      if($datos['numero_radicacion_ext'])
        $sql .= "AND   CP.num_orden_gasto = ".$datos['numero_radicacion_ext']." ";
      
      if($datos['fecha_inicio'])
        $sql .= "AND   CP.fecha_registro >= '".$this->DividirFecha($datos['fecha_inicio'])."'::date ";
      
      if($datos['fecha_fin'])
        $sql .= "AND   CP.fecha_registro <= '".$this->DividirFecha($datos['fecha_fin'])."'::date ";
      /*        
      if($datos['tipo_id_tercero'] != '-1' && $datos['tipo_id_tercero'])
      {
        $sql .= "AND    CF.tercero_id = '".$datos['tercero_id']."' ";
        $sql .= "AND    CF.tipo_id_tercero = '".$datos['tipo_id_tercero']."' ";    
      }
      */
      $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
      $this->ProcesarSqlConteo($cont,$offset);
      
      $sql .= "ORDER BY CP.cxp_orden_pago_id DESC ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $retorno;
    }
    /**
    * Funcion donde se obtienen las facturas pertenecientes a una preorden de pago
    *
    * @param String $empresa Identificador de la empresa
    * @param integere $cxp_orden_pago referencia a la preorden de pago
    *
    * @return array
    */
    function ObtenerFacturasPreOrdenPago($empresa,$cxp_orden_pago)
    { 
      $sql  = "SELECT CF.prefijo_factura, ";
      $sql .= "       CF.numero_factura, ";
      $sql .= "       CF.numero, ";
      $sql .= "       CF.prefijo, ";
      $sql .= "       CF.valor_total, ";
      $sql .= "       CF.valor_iva ";
      $sql .= "FROM   cxp_facturas CF, ";
      $sql .= "       cxp_ordenes_pagos_d CD ";
      $sql .= "WHERE  CF.prefijo = CD.prefijo ";
      $sql .= "AND    CF.numero = CD.numero ";
      $sql .= "AND    CF.empresa_id = CD.empresa_id ";
      $sql .= "AND    CD.cxp_orden_pago_id = ".$cxp_orden_pago." ";
      $sql .= "AND    CF.empresa_id = '".$empresa."' ";
            
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $retorno;
    }
    /**
    * Funcion donde se obtienen los estados de las facturas
    *
    * @return mixed
    */
    function ObtenerEstados()
    { 
      $sql  = "SELECT cxp_estado, ";
      $sql .= "	      cxp_estado_descripcion ";
      $sql .= "FROM   cxp_estados ";
      $sql .= "WHERE  sw_mostrar = '1' ";
            
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $retorno;
    }
    /**
    * Funcion donde se hace la actualizacion de los estados de las facturas
    *
    * @param array $datos Arreglo con los datos de las facturas
    * @param string $empresa Identificador de la empresa
    * 
    * @return mixed
    */
    function ActualizarEstado($datos,$empresa)
    { 
      $this->ConexionTransaccion();
      
      foreach($datos['factura'] as $prf => $prefijo)
      {
        foreach($prefijo as $nmr => $numero)
        {
          $sql  = "UPDATE cxp_facturas ";
          $sql .= "SET    cxp_estado = '".$datos['nuevo_estado']."', ";
          $sql .= "       usuario_ultima_actualizacion = ".UserGetUID().",";
          $sql .= "	      fecha_ultima_actualizacion = NOW() ";
          $sql .= "WHERE  empresa_id = '".$empresa."' ";
          $sql .= "AND    prefijo = '".$prf."' ";
          $sql .= "AND    numero = ".$nmr." ";
          
          if(!$rst = $this->ConexionTransaccion($sql))  return false;
        }
      }
      
      $this->Commit();
      
      return true;
    }
    /**
    * Funcion donde se hace la actualizacion de los estados de 
    * la preorden de pago y las facturas asociadas a la misma
    *
    * @param array $facturas Arreglo con los datos de las facturas
    * @param string $estado_factura Estado al cual pasaran las facturas
    * @param integer $cxp_orden_pago_id Identificador de la preorden de pago
    * @param string $estado_orden Estado al cual pasara la preorden de pago
    * @param string $empresa Identificador de la empresa
    * 
    * @return mixed
    */
    function CambiarEstadoOrdenPago($facturas,$estado_factura,$cxp_orden_pago_id,$estado_orden,$empresa)
    {
      $this->ConexionTransaccion();
      
      $sql  = "UPDATE cxp_ordenes_pagos ";
      $sql .= "SET    usuario_ultima_actualizacion = ".UserGetUID().", ";
      $sql .= "       fecha_ultima_actualizacion = NOW(), ";
      $sql .= "       sw_estado = '".$estado_orden."' ";
      $sql .= "WHERE  cxp_orden_pago_id = ".$cxp_orden_pago_id." ";
      
      if(!$rst = $this->ConexionTransaccion($sql))  return false;
      
      foreach($facturas as $key => $dtl)
      {
        $sql  = "UPDATE cxp_facturas ";
        $sql .= "SET    cxp_estado = '".$estado_factura."', ";
        $sql .= "       usuario_ultima_actualizacion = ".UserGetUID().",";
        $sql .= "	      fecha_ultima_actualizacion = NOW() ";
        $sql .= "WHERE  empresa_id = '".$empresa."' ";
        $sql .= "AND    prefijo = '".$dtl['prefijo']."' ";
        $sql .= "AND    numero = ".$dtl['numero']." ";
        
        if(!$rst = $this->ConexionTransaccion($sql))  return false;
      }
      
      $this->Commit();
      
      return true;
    }
  }
?>