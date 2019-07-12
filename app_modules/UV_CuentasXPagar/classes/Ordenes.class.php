<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: Ordenes.class.php,v 1.3 2008/10/23 22:09:23 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : Ordenes
  * Clase encargada de hacer las consultas de las ordenes de servicios
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class Ordenes extends ConexionBD
  {
    /**
    * Variable para guardar los datos de las ordenes
    *
    * @var array
    * @access public
    */
    var $numero_orden = array();
    /**
    * Constructor de la clase
    */
    function Ordenes(){}
    /**
    * Funcion donde se obtienen el detalle de las ordenes de servicio creadas
    *
    * @param array $datos Fitros para la busqueda de las ordenes de servicio
    * @param string $numeros cadena con los numeros de las ordenes de servicio 
    * @param array $factura Arreglo con la informacion de la factura 
    * 
    * @return mixed
    */
    function ObtenerOrdenesServicioDetalle($datos,$numeros,$factura)
    {
      $ordenes = array();
      
      $sql  = "SELECT ES.eps_orden_servicio, ";
      $sql .= "       ES.autorizacion_id, ";
      $sql .= "       TE.cargo_base, ";
      $sql .= "       TD.cargo, ";
      $sql .= "       TD.tarifario_id, ";
      $sql .= "       TD.descripcion AS descripcion_equivalencia, ";
      $sql .= "       CU.descripcion AS descripcion_base, ";
      $sql .= "       EC.cantidad ,";
      $sql .= "       EC.valor, ";
      $sql .= "       EC.eps_orden_servicio_cargo, ";
      $sql .= "       CO.cxp_detalle_factura_id, ";
      $sql .= "       CASE WHEN CO.prefijo IS NULL THEN '0' ";
      $sql .= "            WHEN CO.prefijo = '".$factura['prefijo']."' AND CO.numero = ".$factura['numero']." THEN '1' ";
      $sql .= "       ELSE  CO.prefijo ||' '|| CO.numero END AS marca ";
      $sql .= "FROM   eps_ordenes_servicios_cargos EC ";
      $sql .= "       LEFT JOIN ";
      $sql .= "       ( SELECT  CC.eps_orden_servicio_cargo, ";
      $sql .= "                 CC.cxp_detalle_factura_id,  ";
      $sql .= "                 CD.prefijo,  ";
      $sql .= "                 CD.numero  ";
      $sql .= "         FROM    cxp_detalle_facturas_cargos_ordenes CC, ";
      $sql .= "                 cxp_detalle_facturas CD ";
      $sql .= "         WHERE   CD.cxp_detalle_factura_id = CC.cxp_detalle_factura_id ";
      $sql .= "         AND     CC.eps_orden_servicio IN (".$numeros.") ";
      $sql .= "       ) AS CO  ";
      $sql .= "       ON (EC.eps_orden_servicio_cargo = CO.eps_orden_servicio_cargo), ";
      $sql .= "       eps_ordenes_servicios ES, ";
 			$sql .= "				tarifarios_detalle TD, ";
 			$sql .= "				tarifarios_equivalencias TE, ";
 			$sql .= "				cups CU ";
      $sql .= "WHERE  ES.eps_orden_servicio = EC.eps_orden_servicio ";
      $sql .= "AND    TD.tarifario_id = EC.tarifario_id ";
      $sql .= "AND    TD.cargo = EC.cargo ";
      $sql .= "AND    TD.tarifario_id = TE.tarifario_id ";
      $sql .= "AND    TD.cargo = TE.cargo ";
      $sql .= "AND    Cu.cargo = TE.cargo_base ";
      $sql .= "AND    ES.codigo_proveedor_id = ".$datos['codigo_proveedor']." "; 
      $sql .= "AND    ES.eps_orden_servicio IN (".$numeros.") ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      while(!$rst->EOF)
			{
				$ordenes[$rst->fields[0]]['cargos'][$rst->fields[2]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
        
      $sql  = "SELECT ES.eps_orden_servicio, ";
      $sql .= "       ES.autorizacion_id, ";
      $sql .= "       EO.descripcion_concepto_adicional, ";
      $sql .= "       EC.valor ";
      $sql .= "FROM   eps_ordenes_servicios_conceptos EC, "; 
      $sql .= "       eps_ordenes_servicios ES, ";
      $sql .= "       eps_solicitudes_ordenes_conceptos EO ";
      $sql .= "WHERE  ES.eps_orden_servicio = EC.eps_orden_servicio ";
      $sql .= "AND    EC.eps_solicitud_orden_concepto = EO.eps_solicitud_orden_concepto ";
      $sql .= "AND    ES.codigo_proveedor_id = ".$datos['codigo_proveedor']." "; 
      $sql .= "AND    ES.eps_orden_servicio IN (".$numeros.") ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      while(!$rst->EOF)
			{
				$ordenes[$rst->fields[0]]['conceptos'][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
        
      $sql  = "SELECT ES.eps_orden_servicio, ";
      $sql .= "       ES.autorizacion_id, ";
      $sql .= "				IM.codigo_producto, ";
			$sql .= "				IM.descripcion AS descripcion_producto, ";
			$sql .= "				EM.cantidad, ";
			$sql .= "				EM.valor, ";
      $sql .= "       EM.eps_orden_servicio_medicamento, ";
      $sql .= "       CO.cxp_detalle_factura_id, ";
      $sql .= "       CASE WHEN CO.prefijo IS NULL THEN '0' ";
      $sql .= "            WHEN CO.prefijo = '".$factura['prefijo']."' AND CO.numero = ".$factura['numero']." THEN '1' ";
      $sql .= "       ELSE  CO.prefijo ||' '|| CO.numero END AS marca ";
      $sql .= "FROM   eps_ordenes_servicios_medicamentos EM ";
      $sql .= "       LEFT JOIN ";
      $sql .= "       ( SELECT  CC.eps_orden_servicio_medicamento, ";
      $sql .= "                 CC.cxp_detalle_factura_id,  ";
      $sql .= "                 CD.prefijo,  ";
      $sql .= "                 CD.numero  ";
      $sql .= "         FROM    cxp_detalle_facturas_medicamentos_ordenes CC, ";
      $sql .= "                 cxp_detalle_facturas CD ";
      $sql .= "         WHERE   CD.cxp_detalle_factura_id = CC.cxp_detalle_factura_id ";
      $sql .= "         AND     CC.eps_orden_servicio IN (".$numeros.") ";
      $sql .= "       ) AS CO  ";
      $sql .= "       ON (EM.eps_orden_servicio_medicamento = CO.eps_orden_servicio_medicamento), ";      
      $sql .= "       eps_ordenes_servicios ES, ";
      $sql .= "      	inventarios_productos IM ";
      $sql .= "WHERE  ES.eps_orden_servicio = EM.eps_orden_servicio ";
      $sql .= "AND	  IM.codigo_producto = EM.codigo_medicamento ";
      $sql .= "AND    ES.codigo_proveedor_id = ".$datos['codigo_proveedor']." "; 
      $sql .= "AND    ES.eps_orden_servicio IN (".$numeros.") ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      while(!$rst->EOF)
			{
				$ordenes[$rst->fields[0]]['medicamentos'][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $ordenes;
    }
    /**
    * Funcion donde se obtine la informacion de las ordenes de servicio 
    * junto con la informacion del proveedor y paciente involucrado
    *
    * @param string $empresa Identificador de la enpresa 
    * @param array $datos Fitros para la busqueda de las ordenes de servicio
    * @param string $fecha Filtro adicional para la fecha del documento
    *
    * @return mixed
    */
    function ObtenerOrdenesServicio($empresa,$datos,$fecha)
    {   
      $sql  = "SELECT OS.*, ";
      $sql .= " 	    EE.descripcion_estamento, 	";
      $sql .= " 	    PA.primer_nombre, ";
      $sql .= " 	    PA.segundo_nombre, ";
      $sql .= " 	    PA.primer_apellido, ";
      $sql .= " 	    PA.segundo_apellido ";
      $sql .= "FROM   pacientes PA, ";
      $sql .= "       ( SELECT  ES.eps_orden_servicio,";
      $sql .= " 	 	            ES.autorizacion_id,";
      $sql .= " 	              TO_CHAR(ES.fecha_registro,'DD/MM/YYYY') AS fecha_registro ,";
      $sql .= "	                TO_CHAR(ES.fecha_vencimiento,'DD/MM/YYYY') AS fecha_vencimiento,";
      $sql .= "	                ES.tipo_id_paciente,";
      $sql .= " 	              ES.paciente_id, ";
      $sql .= "	                ES.observacion,";
      $sql .= "	                ES.codigo_proveedor_id ,";
      $sql .= " 	              ES.estamento_id, 	 ";
      $sql .= " 	              '0' AS marca	";
      $sql .= "         FROM    eps_ordenes_servicios ES ";
      $sql .= "                 LEFT JOIN cxp_facturas_ordenes CO ";
      $sql .= "                 ON (ES.eps_orden_servicio = CO.eps_orden_servicio) ";
      $sql .= "         WHERE  ES.empresa_id = '".$empresa."' ";
      $sql .= "         AND    ES.codigo_proveedor_id = ".$datos['codigo_proveedor']." "; 
      $sql .= "         AND    ES.fecha_registro::date < '".$fecha."'::date "; 
      $sql .= "         AND    ES.estado IN ('1') "; 
      $sql .= "         AND    CO.eps_orden_servicio IS NULL  "; 
      $sql .= "         UNION ALL ";
      $sql .= "         SELECT  ES.eps_orden_servicio,";
      $sql .= " 	 	            ES.autorizacion_id,";
      $sql .= " 	              TO_CHAR(ES.fecha_registro,'DD/MM/YYYY') AS fecha_registro ,";
      $sql .= "	                TO_CHAR(ES.fecha_vencimiento,'DD/MM/YYYY') AS fecha_vencimiento,";
      $sql .= "	                ES.tipo_id_paciente,";
      $sql .= " 	              ES.paciente_id, ";
      $sql .= "	                ES.observacion,";
      $sql .= "	                ES.codigo_proveedor_id ,";
      $sql .= " 	              ES.estamento_id,	";
      $sql .= " 	              '1' AS marca	";
      $sql .= "         FROM    eps_ordenes_servicios ES, ";
      $sql .= "                 cxp_facturas_ordenes CO ";
      $sql .= "         WHERE  ES.empresa_id = '".$empresa."' ";
      $sql .= "         AND    ES.codigo_proveedor_id = ".$datos['codigo_proveedor']." "; 
      $sql .= "         AND    ES.fecha_registro::date < '".$fecha."'::date "; 
      $sql .= "         AND    ES.estado IN ('1') "; 
      $sql .= "         AND    ES.eps_orden_servicio = CO.eps_orden_servicio ";
      $sql .= "         AND    CO.empresa_id = '".$empresa."' ";
      $sql .= "         AND 	 CO.prefijo = '".$datos['prefijo']."' ";
      $sql .= "         AND 	 CO.numero = ".$datos['numero']." ";
      $sql .= "       ) AS OS ";
      $sql .= "       LEFT JOIN eps_estamentos EE ";
      $sql .= "       ON (EE.estamento_id = OS.estamento_id) ";
      $sql .= "WHERE  OS.tipo_id_paciente = PA.tipo_id_paciente ";
      $sql .= "AND 	  OS.paciente_id = PA.paciente_id ";
      $sql .= "ORDER BY OS.eps_orden_servicio DESC ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $ordenes = array();
      while(!$rst->EOF)
			{
				$ordenes[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $ordenes;
    }
    /**
    * Funcion donde se registra la asociacion de la orden de servicio con la factura
    *
    * @param string $orden Numero de la orden de servicio
    * @param string $empresa Identificador de la empresa
    * @param string $prefijo Prefijo del documento
    * @param string $numero Numero del documento
    *
    * @return boolean
    */
    function RegistrarAsociacionOrden($orden,$empresa,$prefijo,$numero)
    {
      $sql  = "INSERT INTO cxp_facturas_ordenes (";
      $sql .= "       empresa_id ,";
      $sql .= "       prefijo,";
      $sql .= "       numero,";
      $sql .= "       eps_orden_servicio ,";
      $sql .= "       usuario_registro ";
      $sql .= "     )";
      $sql .= "VALUES (";
      $sql .= "     '".$empresa."',";
      $sql .= "     '".$prefijo."', ";
      $sql .= "      ".$numero.", ";
      $sql .= "      ".$orden.", ";
      $sql .= "      ".UserGetUID()." ";
      $sql .= "     )";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return true;
    }
    /**
    * Funcion donde se registra la desvinculacion de la orden de servicio con la factura
    *
    * @param string $orden Numero de la orden de servicio
    * @param string $empresa Identificador de la empresa
    * @param string $prefijo Prefijo del documento
    * @param string $numero Numero del documento
    *
    * @return boolean
    */
    function RegistrarDesvinculacionOrden($orden,$empresa,$prefijo,$numero)
    {
      $sql  = "DELETE FROM cxp_facturas_ordenes ";
      $sql .= "WHERE  empresa_id = '".$empresa."' ";
      $sql .= "AND    prefijo = '".$prefijo."' ";
      $sql .= "AND    numero = ".$numero." ";
      $sql .= "AND    eps_orden_servicio = ".$orden." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return true;
    }
    /**
		* Funcion donde se obtienen los cargos del detalle de la factura
		* 
    * @param string $empresa Identificador de la empresa
    * @param string $prefijo Prefijo del documento
    * @param string $numero Numero del documento
    * @param string $cargo Referencia del cargo
    *
		* @return mixed 
		*/
		function ObtenerCargosFactura($empresa,$prefijo,$numero,$cargo)
		{
      $sql .= "SELECT CD.cxp_detalle_factura_id , ";
      $sql .= "       CD.prefijo ,";
      $sql .= "       CD.numero ,";
      $sql .= "       CD.referencia ,";
      $sql .= "       CD.cantidad ,";
      $sql .= " 	    CD.valor_unitario ,";
      $sql .= " 	    CD.valor_total ,";
      $sql .= " 	    CD.autorizacion, ";
      $sql .= " 	    CU.descripcion ";
      $sql .= "FROM   cxp_detalle_facturas CD, ";
      $sql .= "				cups CU ";
      $sql .= "WHERE  CD.empresa_id = '".$empresa."' ";
      $sql .= "AND 	  CD.prefijo = '".$prefijo."' ";
      $sql .= "AND    CD.numero = ".$numero." ";
      $sql .= "AND 	  CD.cx_tipo_cargo_id  = 'CC' ";
      $sql .= "AND 	  CD.referencia  = '".$cargo."' ";
      $sql .= "AND 	  CD.referencia  = CU.cargo ";
      
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
		* Funcion donde se obtienen los medicamentos del detalle de la factura
		* 
    * @param string $empresa Identificador de la empresa
    * @param string $prefijo Prefijo del documento
    * @param string $numero Numero del documento
    * @param string $codigo Referencia del medicamento
    *
		* @return mixed 
		*/
		function ObtenerMedicamentosFactura($empresa,$prefijo,$numero,$codigo)
		{
      $sql .= "SELECT CD.cxp_detalle_factura_id , ";
      $sql .= "       CD.prefijo ,";
      $sql .= "       CD.numero ,";
      $sql .= "       CD.referencia ,";
      $sql .= "       CD.cantidad ,";
      $sql .= " 	    CD.valor_unitario ,";
      $sql .= " 	    CD.valor_total ,";
      $sql .= " 	    CD.autorizacion, ";
      $sql .= " 	    CD.descripcion ";
      $sql .= "FROM   cxp_detalle_facturas CD, ";
      $sql .= "       (";
      $sql .= "         SELECT cod_anatomofarmacologico||cod_principio_activo||cod_forma_farmacologica||cod_concentracion AS codigo";
      $sql .= "         FROM   medicamentos  ";
      $sql .= "         WHERE  codigo_medicamento =  '".$codigo."' ";
      $sql .= "       ) AS ME ";
      $sql .= "WHERE  CD.empresa_id = '".$empresa."' ";
      $sql .= "AND 	  CD.prefijo = '".$prefijo."' ";
      $sql .= "AND    CD.numero = ".$numero." ";
      $sql .= "AND 	  CD.cx_tipo_cargo_id  = 'IM' ";
      $sql .= "AND 	  CD.referencia IN (ME.codigo,'".$codigo."') ";
      
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
    * Funcion donde se registra el vinculo entre el detalle de la orden de servicio y 
    * el detalle de la factura
    * 
    * @param string $cxp_detalle_id Identificador del detalle de la factura
    * @param string $orden Identificador de la orden de servicio
    * @param string $orden_cargo identificacion del detalle de la orden de servicio
    * @param int $valor Valor del detalle de la orden de servicio
    *
    * @return boolean
    */
    function RegistrarVinculoDetalleCargo($cxp_detalle_id,$orden,$orden_cargo,$valor)
    {
      $sql  = "INSERT INTO cxp_detalle_facturas_cargos_ordenes(";
      $sql .= "     cxp_detalle_factura_id, ";
      $sql .= "     eps_orden_servicio , "; 	
      $sql .= "     eps_orden_servicio_cargo, "; 	
      $sql .= "     valor,"; 	
      $sql .= "     usuario_registro ";
      $sql .= "   )";
      $sql .= "VALUES (";
      $sql .= "   ".$cxp_detalle_id.", ";
      $sql .= "   ".$orden.", ";
      $sql .= "   ".$orden_cargo.", ";
      $sql .= "   ".$valor.", ";
      $sql .= "   ".UserGetUID()." ";
      $sql .= "   );";
            
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return true;
    }    
    /**
    * Funcion donde se elimina el vinculo entre el detalle de la orden de servicio y 
    * el detalle de la factura
    * 
    * @param string $cxp_detalle_id Identificador del detalle de la factura
    * @param string $orden Identificador de la orden de servicio
    * @param string $orden_cargo identificacion del detalle de la orden de servicio
    *
    * @return boolean
    */
    function RegistrarDesvinculacionDetalleCargo($cxp_detalle_id,$orden,$orden_cargo)
    {
      $sql  = "DELETE FROM cxp_detalle_facturas_cargos_ordenes ";
      $sql .= "WHERE  cxp_detalle_factura_id = ".$cxp_detalle_id." ";
      $sql .= "AND    eps_orden_servicio = ".$orden." "; 	
      $sql .= "AND    eps_orden_servicio_cargo = ".$orden_cargo."; "; 	
            
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return true;
    }    
    /**
    * Funcion donde se registra el vinculo entre el detalle de la orden de servicio y 
    * el detalle de la factura
    * 
    * @param string $cxp_detalle_id Identificador del detalle de la factura
    * @param string $orden Identificador de la orden de servicio
    * @param string $orden_medicamento identificacion del detalle de la orden de servicio
    * @param int $valor Valor del detalle de la orden de servicio
    *
    * @return boolean
    */
    function RegistrarVinculoDetalleMedicamento($cxp_detalle_id,$orden,$orden_medicamento,$valor)
    {
      $sql  = "INSERT INTO cxp_detalle_facturas_medicamentos_ordenes(";
      $sql .= "     cxp_detalle_factura_id, ";
      $sql .= "     eps_orden_servicio , "; 	
      $sql .= "     eps_orden_servicio_medicamento, "; 	
      $sql .= "     valor,"; 	
      $sql .= "     usuario_registro ";
      $sql .= "   )";
      $sql .= "VALUES (";
      $sql .= "   ".$cxp_detalle_id.", ";
      $sql .= "   ".$orden.", ";
      $sql .= "   ".$orden_medicamento.", ";
      $sql .= "   ".$valor.", ";
      $sql .= "   ".UserGetUID()." ";
      $sql .= "   );";
            
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return true;
    }    
    /**
    * Funcion donde se elimina el vinculo entre el detalle de la orden de servicio y 
    * el detalle de la factura
    * 
    * @param string $cxp_detalle_id Identificador del detalle de la factura
    * @param string $orden Identificador de la orden de servicio
    * @param string $orden_medicamento identificacion del detalle de la orden de servicio
    *
    * @return boolean
    */
    function RegistrarDesvinculacionDetalleMedicamento($cxp_detalle_id,$orden,$orden_medicamento)
    {
      $sql  = "DELETE FROM cxp_detalle_facturas_medicamentos_ordenes ";
      $sql .= "WHERE  cxp_detalle_factura_id = ".$cxp_detalle_id." ";
      $sql .= "AND    eps_orden_servicio = ".$orden." "; 	
      $sql .= "AND    eps_orden_servicio_medicamento = ".$orden_medicamento."; "; 	
            
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return true;
    }
  }
?>