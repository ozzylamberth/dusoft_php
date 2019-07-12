<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: GestionPagos.class.php,v 1.2 2008/10/23 22:09:09 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : GestionPagos
  * Clase en la que se maneja la logica de la gestion de pagos
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class GestionPagos extends ConexionBD
  {
    /**
    * Contructor de la clase
    */
    function GestionPagos(){}
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
    * Funcion donde se obtienen las facturas, que pueden ser 
    * incluidas en una orden de pago
    *
    * @param string $empresa Identificador de la empresa
    * @param array $datos Arreglo con los datos de los filtros de busquedad
    * @paran string $estado Referencia al estado en el que se deben encontrar las factuars
    * @paran string $medio_pago Referencia al medio de pago que deben tener las factuars
    *
    * @return array
    */
    function ObtenerFacturas($empresa,$datos,$estado,$medio_pago)
    {      
      $sql  = "SELECT CF.prefijo_factura,";
      $sql .= "       CF.numero_factura, ";
      $sql .= "       CF.empresa_id, ";
      $sql .= "       TO_CHAR(CF.fecha_documento,'DD/MM/YYYY') AS fecha_documento, ";
      $sql .= "       TO_CHAR(CR.fecha_radicacion,'DD/MM/YYYY') AS fecha_radicacion, ";
      $sql .= "       CF.valor_total 	, ";
      $sql .= "       CF.valor_iva 	, ";
      $sql .= "       CF.prefijo, ";
      $sql .= "       CF.numero, ";
      $sql .= "       CR.cxp_radicacion_id, ";
      $sql .= "       CT.tipo_cxp_descripcion, ";
      $sql .= "       TP.tipo_id_tercero, ";
      $sql .= "       TP.tercero_id, ";
      $sql .= "       TP.nombre_tercero ";
      $sql .= "FROM   cxp_facturas CF, ";
      $sql .= "       cxp_radicacion CR, ";
      $sql .= "       cxp_tipos CT, ";
      $sql .= "       terceros TP ";
      $sql .= "WHERE  CF.tipo_cxp = CT.tipo_cxp ";
      $sql .= "AND    CF.empresa_id = '".$empresa."' ";  
      $sql .= "AND    CF.tercero_id = TP.tercero_id ";
      $sql .= "AND    CF.tipo_id_tercero = TP.tipo_id_tercero ";
      $sql .= "AND    CR.cxp_radicacion_id = CF.cxp_radicacion_id ";
      $sql .= "AND    CF.cxp_estado = '".$estado."' ";
      $sql .= "AND    CR.cxp_medio_pago_id = '".$medio_pago."' ";

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
      
      $sql .= "ORDER BY CF.prefijo_factura, CF.numero_factura ";
      
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
    * Funcion que permite hacer el ingreso de una orden de pago
    * 
    * @param array $datos Arreglo con los datos de las facturas
    * @param string $empresa Identificador de la empresa
    * @paran string $estado Referencia al estado al cual pasaran las facturas
    *
    * @return mixed
    */
    function IngresarOrdenpago($datos,$empresa,$estado)
    {
      $sql = "SELECT NEXTVAL('cxp_ordenes_pagos_cxp_orden_pago_id_seq') AS indice ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $indice = array();
			if (!$rst->EOF)
			{
				$indice = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
      $nu = $indice['indice'] - 1;
      if($indice['indice'] == 1) $nu = 1;
      
      $sqe = "SELECT SETVAL('cxp_ordenes_pagos_cxp_orden_pago_id_seq',".$nu.") ";

      $sql = "LOCK TABLE cxp_ordenes_pagos IN ROW EXCLUSIVE MODE; ";
      $this->ConexionTransaccion();
      
      if(!$rst = $this->ConexionTransaccion($sql)) 
      {
        if(!$rst = $this->ConexionBaseDatos($sqe)) return false;
        return false;
      }
      
      $sql  = "INSERT INTO cxp_ordenes_pagos (";
      $sql .= "   cxp_orden_pago_id,";
      $sql .= "   empresa_id,";
      $sql .= "   usuario_registro";
      $sql .= "   )";
      $sql .= "VALUES ( ";
      $sql .= "    ".$indice['indice'].",";
      $sql .= "   '".$empresa."',";
      $sql .= "    ".UserGetUID()." ";
      $sql .= ") ";
      
      if(!$rst = $this->ConexionTransaccion($sql)) 
      {
        if(!$rst = $this->ConexionBaseDatos($sqe)) return false;
        return false;
      }
      
      foreach($datos['factura'] as $prf => $prefijo)
      {
        foreach($prefijo as $nmr => $numero)
        {
          $sql  = "INSERT INTO cxp_ordenes_pagos_d( ";
          $sql .= "     cxp_orden_pago_id ,";
          $sql .= "     empresa_id ,";
          $sql .= "     prefijo ,";
          $sql .= "     numero ";
          $sql .= " ) ";
          $sql .= " VALUES ( ";
          $sql .= "    ".$indice['indice'].",";
          $sql .= "   '".$empresa."',";
          $sql .= "   '".$prf."',";
          $sql .= "   ".$nmr." ";
          $sql .= " ) ";
          
          if(!$rst = $this->ConexionTransaccion($sql)) 
          {
            if(!$rst = $this->ConexionBaseDatos($sqe)) return false;
            return false;
          }
          
          $sql  = "UPDATE cxp_facturas ";
          $sql .= "SET    cxp_estado = '".$estado."' ";
          $sql .= "WHERE  empresa_id = '".$empresa."' ";
          $sql .= "AND    prefijo = '".$prf."' ";
          $sql .= "AND    numero = ".$nmr." ";
          
          if(!$rst = $this->ConexionTransaccion($sql)) 
          {
            if(!$rst = $this->ConexionBaseDatos($sqe)) return false;
            return false;
          }
        }
      }
      
      $this->Commit();
      
      return $indice['indice'];
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
      $sql .= " 	    CP.empresa_id,";
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
    * Funcion donde se obtinen las facturas asociadas a un apreorden de pago
    *
    * @param integer $orden_pago identificador de la preorden de pago
    *
    * @return mixed
    */
    function ObtenerFacturasOrdenPago($orden_pago)
    {
      $sql  = "SELECT CF.prefijo_factura, ";
      $sql .= "       CF.numero_factura, ";
      $sql .= "       TO_CHAR(CF.fecha_documento,'YYYY-MM-DD') AS fecha_documento, ";
      $sql .= "       CF.valor_total,";
      $sql .= "       CF.valor_iva ";
      $sql .= "FROM   cxp_facturas CF, ";
      $sql .= "       cxp_ordenes_pagos_d CO ";
      $sql .= "WHERE  CF.empresa_id = CO.empresa_id ";
      $sql .= "AND    CF.prefijo = CO.prefijo ";
      $sql .= "AND    CF.numero = CO.numero ";
      $sql .= "AND    CO.cxp_orden_pago_id = ".$orden_pago." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
            
      return $rst;
    }
    /**
    * Funcion que permite hacer el registro del numero de radicacion externo
    * para una preorden de pago 
    *
    * @param string $numero_orden_gasto Numero de radiacion
    * @param integer $cxp_orden_pago_id Identificador de la preorden de pago
    *
    * @return mixed
    */
    function RegistrarNumeroRadicacion($numero_orden_gasto,$cxp_orden_pago_id)
    {
      $sql  = "UPDATE cxp_ordenes_pagos ";
      $sql .= "SET    num_orden_gasto = '".$numero_orden_gasto."', ";
      $sql .= "       fecha_radicacion = NOW(), ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID().", ";
      $sql .= "       fecha_ultima_actualizacion = NOW(), ";
      $sql .= "       sw_estado = '2' ";
      $sql .= "WHERE  cxp_orden_pago_id = ".$cxp_orden_pago_id." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
            
      return true;
    }    
    /**
    * Funcion que permite hacer el registro de la observacion al estado 
    * de una preorden de pago 
    *
    * @param string $observacion Cadena de texto
    * @param integer $cxp_orden_pago_id Identificador de la preorden de pago
    *
    * @return mixed
    */
    function RegistrarEstadoObservacion($observacion,$cxp_orden_pago_id)
    {
      $sql  = "UPDATE cxp_ordenes_pagos ";
      $sql .= "SET    observacion_estado = '".$observacion."', ";
      $sql .= "       usuario_ultima_actualizacion = ".UserGetUID().", ";
      $sql .= "       fecha_ultima_actualizacion = NOW() ";
      $sql .= "WHERE  cxp_orden_pago_id = ".$cxp_orden_pago_id." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $sql  = "INSERT INTO cxp_ordenes_pagos_h ( ";
      $sql .= "       cxp_orden_pago_id, ";
      $sql .= "       observacion_estado, ";
      $sql .= "       usuario_id ) ";
      $sql .= "VALUES (";
      $sql .= "       ".$cxp_orden_pago_id.", ";
      $sql .= "       '".$observacion."', ";
      $sql .= "       ".UserGetUID()." ";
      $sql .= "       )";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      return true;
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
    * Funcion donde se obtienen el detalle de la prorden de pago
    *
    * @param string $empresa Identificador de la empresa
    * @param integer $cxp_orden_pago identificador de la preorden de pago
    *
    * @return mixed
    */
    function ObtenerDetallePreOrdenPago($empresa,$cxp_orden_pago)
    { 
      $sql  = "SELECT DISTINCT CP.cxp_orden_pago_id,";
      $sql .= " 	    CP.num_orden_gasto,";
      $sql .= " 	    TO_CHAR(CP.fecha_registro,'DD/MM/YYYY') AS fecha_elab_preorden, ";
      $sql .= " 	    CF.descripcion_especialidad, ";
      $sql .= " 	    CF.fecha_radicacion, ";
      $sql .= " 	    CF.fecha_inicial, ";
      $sql .= " 	    CF.fecha_final, ";
      $sql .= " 	    CF.tipo_id_tercero, ";
      $sql .= " 	    CF.tercero_id ";
      $sql .= "FROM   cxp_ordenes_pagos CP, ";
      $sql .= "       cxp_ordenes_pagos_d CD, ";
      $sql .= "       ( SELECT  CF.prefijo,";
      $sql .= "                 CF.numero, ";
      $sql .= "                 CF.empresa_id, ";
      $sql .= "                 CF.tipo_id_tercero, ";
      $sql .= "                 CF.tercero_id, ";
      $sql .= "                 CE.descripcion_especialidad, ";
      $sql .= "                 TO_CHAR(CR.fecha_radicacion,'DD/MM/YYYY') AS fecha_radicacion, ";
      $sql .= "                 TO_CHAR(CR.fecha_inicial,'DD/MM/YYYY') AS fecha_inicial, ";
      $sql .= "                 TO_CHAR(CR.fecha_final,'DD/MM/YYYY') AS fecha_final ";
      $sql .= "         FROM    cxp_facturas CF, ";
      $sql .= "                 cxp_radicacion CR, ";
      $sql .= "                 cxp_tipos CT, ";
      $sql .= "                 cxp_especialidades CE ";
      $sql .= "         WHERE   CF.tipo_cxp = CT.tipo_cxp ";
      $sql .= "         AND     CF.empresa_id = '".$empresa."' ";
      $sql .= "         AND     CR.cxp_radicacion_id = CF.cxp_radicacion_id ";
      $sql .= "         AND     CR.cxp_especialidad_id = CE.cxp_especialidad_id ";
      $sql .= "       ) AS CF ";
      $sql .= "WHERE  CF.prefijo = CD.prefijo ";
      $sql .= "AND    CF.numero = CD.numero ";
      $sql .= "AND    CF.empresa_id = CD.empresa_id ";
      $sql .= "AND    CD.cxp_orden_pago_id = CP.cxp_orden_pago_id ";
      $sql .= "AND    CP.cxp_orden_pago_id = ".$cxp_orden_pago." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			if (!$rst->EOF)
			{
				$retorno = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $retorno;
    }
    /**
    * Funcion donde se obtienen los proveedores
    *
    * @param array $datos Arreglo con los datos de filtro del proveedor
    *
    * @return mixed
    */
    function ObtenerProveedores($datos)
    {      
      $sql  = "SELECT TR.nombre_tercero,  ";
      $sql .= "       TR.telefono,  ";
      $sql .= "       TR.direccion,  ";
      $sql .= "       TR.tercero_id, ";
      $sql .= "       TR.tipo_id_tercero ";
      $sql .= "FROM   terceros TR ";
      $sql .= "WHERE  TR.tipo_id_tercero = '".$datos['tipo_id_tercero']."'  ";
      $sql .= "AND    TR.tercero_id = '".$datos['tercero_id']."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
			if(!$rst->EOF)
			{
				$retorno = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			return $retorno;
    }
    /**
		* Funcion donde se obtiene las grupos presupuestales y su valor, dentro del
    * detallado de las facturas
    * 
    * @param string $empresa Identificador de la empresa
    * @param integer $cxp_orden_pago identificador de la preorden de pago
    *
		* @return mixed 
		*/
		function ObtenerDetalleOrdenGasto($empresa,$cxp_orden_pago)
		{
      $sql  = "SELECT GP.descripcion AS cuenta,";
      $sql .= "       SUM(CD.valor_total) AS valor_total ";
      $sql .= "FROM   cxp_detalle_facturas CD, ";
      $sql .= "       cxp_facturas CF, ";
      $sql .= "       cxp_ordenes_pagos_d CO, ";
      $sql .= "       grupos_presupuestales GP, ";
      $sql .= "       cxp_tipos_cargos CC ";
      $sql .= "WHERE  CD.cx_tipo_cargo_id = CC.	cx_tipo_cargo_id ";
      $sql .= "AND    CD.empresa_id = '".$empresa."' ";
      $sql .= "AND    CD.prefijo = CF.prefijo ";
      $sql .= "AND    CD.numero = CF.numero ";
      $sql .= "AND    CD.empresa_id = CF.empresa_id ";      
      $sql .= "AND    CF.prefijo = CO.prefijo ";
      $sql .= "AND    CF.numero = CO.numero ";
      $sql .= "AND    CF.empresa_id = CO.empresa_id ";
      $sql .= "AND    CF.sw_rips = '1' ";
      $sql .= "AND    CO.cxp_orden_pago_id = ".$cxp_orden_pago." ";
      $sql .= "AND    CC.cuenta = GP.cuenta ";
      $sql .= "GROUP BY GP.descripcion ";
      
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      return $retorno;
		}
    /**
    * Funcion donde se obtiene la informacion de los pacientes relacionados en la 
    * preorden de pago
    *
    * @param string $empresa Identificador de la empresa
    * @param integer $cxp_orden_pago identificador de la preorden de pago
    *
    * @return mixed
    */
    function ObtenerPacientes($empresa,$cxp_orden_pago)
    {
      $sql  = "SELECT CP.tipo_id_paciente,";
      $sql .= "       CP.paciente_id,";
      $sql .= "       AF.descripcion_estamento ";
      $sql .= "FROM   cxp_pacientes_facturas CP, "; 
      $sql .= "       cxp_facturas CF, ";
      $sql .= "       cxp_ordenes_pagos_d CO, ";
      $sql .= "       ( ";
      $sql .= "           SELECT  EB.afiliado_id, ";
      $sql .= "                   EB.afiliado_tipo_id, ";
      $sql .= "                   'FAM. '||EE.descripcion_estamento AS descripcion_estamento ";
      $sql .= "           FROM    eps_afiliados_beneficiarios EB,"; 
      $sql .= "                   eps_afiliados_cotizantes EC, " ;
      $sql .= "                   eps_estamentos EE " ;
      $sql .= "           WHERE   EB.cotizante_tipo_id = EC.afiliado_tipo_id";
      $sql .= "           AND     EB.cotizante_id = EC.afiliado_id ";
      $sql .= "           AND     EB.eps_afiliacion_id = EC.eps_afiliacion_id ";
      $sql .= "           AND     EC.estamento_id = EE.estamento_id ";
      $sql .= "           UNION ALL ";
      $sql .= "           SELECT  EC.afiliado_id, ";
      $sql .= "                   EC.afiliado_tipo_id,";
      $sql .= "                   EE.descripcion_estamento ";
      $sql .= "           FROM    eps_afiliados_cotizantes EC, " ;
      $sql .= "                   eps_estamentos EE " ;
      $sql .= "           WHERE   EC.estamento_id = EE.estamento_id  ";
      $sql .= "           UNION ALL ";
      $sql .= "           SELECT  paciente_id AS afiliado_id, ";
      $sql .= "                   tipo_id_paciente AS afiliado_tipo_id,";
      $sql .= "                   'ESTUDIANTE' AS descripcion_estamento ";
      $sql .= "           FROM    interfaz_uv.bd_estudiantes " ;
      $sql .= "         ) AS AF ";
      $sql .= "WHERE    CF.empresa_id = '".$empresa."' ";
      $sql .= "AND      CP.prefijo = CF.prefijo ";
      $sql .= "AND      CP.numero = CF.numero ";
      $sql .= "AND      CP.empresa_id = CF.empresa_id ";      
      $sql .= "AND      CF.prefijo = CO.prefijo ";
      $sql .= "AND      CF.numero = CO.numero ";
      $sql .= "AND      CF.empresa_id = CO.empresa_id ";
      $sql .= "AND      CF.sw_rips = '1' ";
      $sql .= "AND      AF.afiliado_id = CP.paciente_id ";
      $sql .= "AND      AF.afiliado_tipo_id = CP.tipo_id_paciente ";
      $sql .= "AND      CO.cxp_orden_pago_id = ".$cxp_orden_pago." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      return $retorno;
    }
    /**
    * Funcion donde se obtiene la informacion de las ordenes de servicio
    * relacionadas a la preorden de pago
    * 
    * @param string $empresa Identificador de la empresa
    * @param integer $cxp_orden_pago identificador de la preorden de pago
    *
    * @return mixed
    */
    function ObtenerOrdenesServicio($empresa,$cxp_orden_pago)
    {     
      $sql  = "SELECT CP.tipo_id_paciente,";
      $sql .= "       CP.paciente_id,";
      $sql .= "       SUM(AC.valor) AS valor, ";
      $sql .= "       COUNT(*) AS cantidad ";
      $sql .= "FROM   cxp_pacientes_facturas CP, "; 
      $sql .= "       cxp_facturas CF, ";
      $sql .= "       cxp_ordenes_pagos_d CO, ";
      $sql .= "      (";
      $sql .= "         SELECT  EC.eps_orden_servicio, ";
      $sql .= "                 EC.valor, ";
      $sql .= "                 CD.empresa_id,  ";
      $sql .= "                 CD.prefijo,  ";
      $sql .= "                 CD.numero  ";
      $sql .= "         FROM    eps_ordenes_servicios_cargos EC, ";
      $sql .= "                 cxp_detalle_facturas_cargos_ordenes CC, ";
      $sql .= "                 cxp_detalle_facturas CD ";
      $sql .= "         WHERE  CD.cxp_detalle_factura_id = CC.cxp_detalle_factura_id ";
      $sql .= "         AND    EC.eps_orden_servicio = CC.eps_orden_servicio ";      
      $sql .= "         UNION ALL ";
      $sql .= "         SELECT  EC.eps_orden_servicio, ";
      $sql .= "                 EC.valor, ";
      $sql .= "                 CD.empresa_id,  ";
      $sql .= "                 CD.prefijo,  ";
      $sql .= "                 CD.numero  ";
      $sql .= "         FROM    eps_ordenes_servicios_medicamentos EC, ";
      $sql .= "                 cxp_detalle_facturas_medicamentos_ordenes CC, ";
      $sql .= "                 cxp_detalle_facturas CD ";
      $sql .= "         WHERE   CD.cxp_detalle_factura_id = CC.cxp_detalle_factura_id ";
      $sql .= "         AND     EC.eps_orden_servicio = CC.eps_orden_servicio ";
      $sql .= "     ) AS AC ";
      $sql .= "WHERE    CF.empresa_id = '".$empresa."' ";
      $sql .= "AND      CP.prefijo = CF.prefijo ";
      $sql .= "AND      CP.numero = CF.numero ";
      $sql .= "AND      CP.empresa_id = CF.empresa_id ";      
      $sql .= "AND      CF.prefijo = CO.prefijo ";
      $sql .= "AND      CF.numero = CO.numero ";
      $sql .= "AND      CF.empresa_id = CO.empresa_id ";      
      $sql .= "AND      CF.prefijo = AC.prefijo ";
      $sql .= "AND      CF.numero = AC.numero ";
      $sql .= "AND      CF.empresa_id = AC.empresa_id ";
      $sql .= "AND      CF.sw_rips = '1' ";
      $sql .= "AND      CO.cxp_orden_pago_id = ".$cxp_orden_pago." ";
      $sql .= "GROUP BY CP.paciente_id,CP.tipo_id_paciente ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $retorno = array();
			while (!$rst->EOF)
			{
				$retorno[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      $sql  = "SELECT CP.tipo_id_paciente,";
      $sql .= "       CP.paciente_id,";
      $sql .= "       SUM(ES.valor) AS valor, ";
      $sql .= "       COUNT(*) AS cantidad ";
      $sql .= "FROM   cxp_pacientes_facturas CP, "; 
      $sql .= "       cxp_facturas CF, ";
      $sql .= "       cxp_ordenes_pagos_d CO, ";
      $sql .= "       cxp_facturas_ordenes CR, ";
      $sql .= "       eps_ordenes_servicios ES ";
      $sql .= "WHERE  CF.empresa_id = '".$empresa."' ";
      $sql .= "AND    CP.prefijo = CF.prefijo ";
      $sql .= "AND    CP.numero = CF.numero ";
      $sql .= "AND    CP.empresa_id = CF.empresa_id ";      
      $sql .= "AND    CF.prefijo = CO.prefijo ";
      $sql .= "AND    CF.numero = CO.numero ";
      $sql .= "AND    CF.empresa_id = CO.empresa_id ";      
      $sql .= "AND    CF.prefijo = CR.prefijo ";
      $sql .= "AND    CF.numero = CR.numero ";
      $sql .= "AND    CF.empresa_id = CR.empresa_id ";
      $sql .= "AND    CO.cxp_orden_pago_id = ".$cxp_orden_pago." ";
      $sql .= "AND    ES.eps_orden_servicio = CR.eps_orden_servicio ";      
      $sql .= "GROUP BY CP.paciente_id,CP.tipo_id_paciente ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      while (!$rst->EOF)
			{
				$retorno[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      return $retorno;
    }
  }
?>