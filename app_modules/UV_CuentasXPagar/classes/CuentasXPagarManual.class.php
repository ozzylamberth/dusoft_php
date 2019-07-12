<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: CuentasXPagarManual.class.php,v 1.2 2008/10/10 22:24:17 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : CuentasXPagarManual
  * Clase en la que se maneja la logica de cuentas por pagar
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class CuentasXPagarManual extends ConexionBD
  {
    /**
    * Contructor de la clase
    */
    function CuentasXPagarManual(){}
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
		* Funcion donde se buscan los cargos registrados en el sistema
    * 
    * @param array $datos Vector con los filtros para la busqueda
    * @param int $off Parametro del offset, para la paginacion
    * @param int $op Indica si se hara una busqueda completa o no
    *
    * @return mixed
		*/
		function ObtenerCargos($datos,$off,$op = 1)
		{
			$sql  = "SELECT DISTINCT CU.cargo,";
			$sql .= "				CU.descripcion, ";
			$sql .= "				AT.apoyod_tipo_id, ";
			$sql .= "				AT.descripcion as tipo ";
			$sql .= "FROM		cups CU,";
			$sql .= "				apoyod_tipos AT ";
			$sql .= "WHERE	CU.grupo_tipo_cargo = AT.apoyod_tipo_id ";		
			$sql .= "AND 		CU.sw_activo = '1' ";
			if($datos['cargo'])
        $sql .= "AND		CU.cargo ILIKE '".$datos['cargo']."' ";
			if($datos['descripcion'])
        $sql .= "AND		CU.descripcion ILIKE '%".$datos['descripcion']."%' ";
      if($datos['grupo_tipo_cargo'] != '-1' && $datos['grupo_tipo_cargo'])
        $sql .= "AND    CU.grupo_tipo_cargo = '".$datos['grupo_tipo_cargo']."' ";
			
      if($op !== 0)
      {
        $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
        $this->ProcesarSqlConteo($cont,$off,$cant);
      
        $sql .= "ORDER BY CU.descripcion ";
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			}
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			return $retorno;
		}
    /**
    * Funcion donde se obtienen los diferentes grupos a los cuales 
    * perteneecen lo cargos
    *
    * @return mixed
    */
    function ObtenerGruposTiposCargos()
    {
      $sql  = "SELECT grupo_tipo_cargo, ";
      $sql .= "       descripcion ";
      $sql .= "FROM   grupos_tipos_cargo ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
      while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $datos;
    }
    /**
    * Funcion donde se ingresan los cargos correspondientes al detalle de las facturas
    *
    * @param string $empresa Identificador de la empresa
    * @param string $prefijo Prefijo del documento a ingresar
    * @param string $numero Numero del documento
    * @param array $datos Arreglo de datos con la informacion a ser ingresada
    * @param string $tipocxp identificador del tipo de detalle que se va a ingresar
    *
    * @return boolean
    */
    function IngresarDetalleCxPCargos($empresa,$prefijo,$numero,$datos,$tipocxp)
    {
      ($datos['autorizacion'])? $auto = $datos['autorizacion']: $auto = "NULL"; 
      (!$datos['cargo'])? $datos['cargo'] = "(SELECT NEXTVAL('referencia_cxp_detalle_otros')) ":$datos['cargo'] = "'".$datos['cargo']."'";
      
      $sql = "INSERT INTO cxp_detalle_facturas ";
      $sql .= "( ";
      $sql .= "    cxp_detalle_factura_id ,";
      $sql .= "    empresa_id ,";
      $sql .= "    prefijo ,";
      $sql .= "    numero ,";
      $sql .= "    cx_tipo_cargo_id ,";
      $sql .= "    referencia ,";
      $sql .= "    descripcion ,";
      $sql .= "    valor_unitario ,";
      $sql .= "    cantidad ,";
      $sql .= "    valor_total ,";
      $sql .= "    autorizacion, ";
      $sql .= "    usuario_ultima_actualizacion, ";
      $sql .= "    fecha_ultima_actualizacion ";
      $sql .= "    )";
      $sql .= "VALUES (";
      $sql .= "    DEFAULT,";
      $sql .= "   '".$empresa."',";
      $sql .= "   '".$prefijo."',";
      $sql .= "    ".$numero.",";
      $sql .= "    '".$tipocxp."',";
      $sql .= "    ".$datos['cargo'].",";
      $sql .= "   '".$datos['descripcion']."',";
      $sql .= "    ".$datos['valor_unitario'].",";
      $sql .= "    ".$datos['cantidad'].",";
      $sql .= "    ".($datos['valor_unitario'] * $datos['cantidad']).", ";
      $sql .= "    ".$auto.", ";
      $sql .= "    ".UserGetUID().", ";
      $sql .= "     NOW() ";
      $sql .= "    )";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return $datos;
    }
    /**
    * Funcion donde se elimina el detalle de una cuenta por pagar
    *
    * @param string $cxp_detalle_id Identificador del detalle de la factura
    *
    * @return array
    */
    function EliminarDetalleCxP($cxp_detalle_id)
    {
      $sql  = "DELETE FROM cxp_detalle_facturas ";
      $sql .= "WHERE  cxp_detalle_factura_id = ".$cxp_detalle_id." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return true;
    }
    /**
		* Funcion donde se obtienen los cargos del detalle de la factura
		* 
    * @param array $datos Arreglo de datos con la informacion de la factura
    * @param string $empresa Identificador de la empresa
    *
		* @return mixed 
		*/
		function ObtenerCargosFactura($datos,$empresa)
		{ 
      $sql  = "SELECT CD.cxp_detalle_factura_id , ";
      $sql .= " 	    CD.prefijo ,";
      $sql .= " 	    CD.numero ,";
      $sql .= " 	    CD.referencia ,";
      $sql .= " 	    CD.descripcion ,";
      $sql .= " 	    CD.cantidad ,";
      $sql .= " 	    CD.porcentaje_gravamen ,";
      $sql .= " 	    CD.valor_gravamen ,";
      $sql .= " 	    CD.valor_unitario ,";
      $sql .= " 	    CD.valor_total ,";
      $sql .= " 	    CD.autorizacion, ";
      $sql .= " 	    CD.sw_objetado, ";
      $sql .= " 	    CO.valor, ";
      $sql .= " 	    CG.cxp_glosa_id ";
      $sql .= "FROM   cxp_detalle_facturas CD ";
      $sql .= "       LEFT JOIN cxp_glosas_detalles CG ";
      $sql .= "       ON( CG.cxp_detalle_factura_id = CD.cxp_detalle_factura_id )";
      $sql .= "       LEFT JOIN cxp_detalle_facturas_cargos_ordenes CO ";
      $sql .= "       ON( CO.cxp_detalle_factura_id = CD.cxp_detalle_factura_id )";
      $sql .= "WHERE  CD.empresa_id = '".$empresa."' ";
      $sql .= "AND 	  CD.prefijo = '".$datos['prefijo']."' ";
      $sql .= "AND 	  CD.numero = ".$datos['numero']." ";
      $sql .= "AND 	  CD.cx_tipo_cargo_id  = 'CC' ";
      
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
    * @param array $datos Arreglo de datos con la informacion de la factura
    * @param string $empresa Identificador de la empresa
    *
		* @return mixed 
		*/
		function ObtenerMedicamentosFactura($datos,$empresa)
		{ 
      $sql  = "SELECT CD.cxp_detalle_factura_id , ";
      $sql .= " 	    CD.prefijo ,";
      $sql .= " 	    CD.numero ,";
      $sql .= " 	    CD.referencia ,";
      $sql .= " 	    CD.descripcion ,";
      $sql .= " 	    CD.cantidad ,";
      $sql .= " 	    CD.porcentaje_gravamen ,";
      $sql .= " 	    CD.valor_gravamen ,";
      $sql .= " 	    CD.valor_unitario ,";
      $sql .= " 	    CD.valor_total ,";
      $sql .= " 	    CD.autorizacion, ";
      $sql .= " 	    CD.sw_objetado, ";
      $sql .= " 	    CO.valor, ";
      $sql .= " 	    CG.cxp_glosa_id ";
      $sql .= "FROM   cxp_detalle_facturas CD ";
      $sql .= "       LEFT JOIN cxp_glosas_detalles CG ";
      $sql .= "       ON( CG.cxp_detalle_factura_id = CD.cxp_detalle_factura_id )";
      $sql .= "       LEFT JOIN cxp_detalle_facturas_medicamentos_ordenes CO ";
      $sql .= "       ON( CO.cxp_detalle_factura_id = CD.cxp_detalle_factura_id )";
      $sql .= "WHERE  CD.empresa_id = '".$empresa."' ";
      $sql .= "AND 	  CD.prefijo = '".$datos['prefijo']."' ";
      $sql .= "AND 	  CD.numero = ".$datos['numero']." ";
      $sql .= "AND 	  CD.cx_tipo_cargo_id  = 'IM' ";
      
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
		* Funcion donde se obtienen los detalles de otros servicios asociados 
    * a las facturas
		* 
    * @param array $datos Arreglo de datos con la informacion de la factura
    * @param string $empresa Identificador de la empresa
    *
		* @return mixed 
		*/
		function ObtenerOtrosServiciosFactura($datos,$empresa)
		{ 
      $sql  = "SELECT CD.cxp_detalle_factura_id , ";
      $sql .= " 	    CD.prefijo ,";
      $sql .= " 	    CD.numero ,";
      $sql .= " 	    CD.referencia ,";
      $sql .= " 	    CD.descripcion ,";
      $sql .= " 	    CD.cantidad ,";
      $sql .= " 	    CD.porcentaje_gravamen ,";
      $sql .= " 	    CD.valor_gravamen ,";
      $sql .= " 	    CD.valor_unitario ,";
      $sql .= " 	    CD.valor_total ,";
      $sql .= " 	    CD.autorizacion, ";
      $sql .= " 	    CD.sw_objetado, ";
      $sql .= " 	    CG.cxp_glosa_id ";
      $sql .= "FROM   cxp_detalle_facturas CD ";
      $sql .= "       LEFT JOIN cxp_glosas_detalles CG ";
      $sql .= "       ON( CG.cxp_detalle_factura_id = CD.cxp_detalle_factura_id ) ";
      $sql .= "WHERE  CD.empresa_id = '".$empresa."' ";
      $sql .= "AND 	  CD.prefijo = '".$datos['prefijo']."' ";
      $sql .= "AND 	  CD.numero = ".$datos['numero']." ";
      $sql .= "AND 	  CD.cx_tipo_cargo_id  = 'OT' ";
      
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
		* Funcion donde se buscan los medicamentos registrados en el sistema
    * 
    * @param array $datos Vector con los filtros para la busqueda
    * @param String $empresa Identificador de la enpresa
    * @param int $off Parametro del offset, para la paginacion
    * @param int $op Indica si se hara una busqueda completa o no
    *
    * @return array
		*/
		function ObtenerMedicamentos($datos,$empresa,$off,$op = 1)
		{
			$sql  = "SELECT CASE WHEN ME.sw_pos = '1' THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item,";
			$sql .= "				IM.codigo_producto, ";
			$sql .= "				IM.descripcion AS descripcion_producto, ";
			$sql .= "				IA.descripcion AS principio_activo,";
			$sql .= "				ME.nivel_autorizador_id ";
      $sql .= "FROM 	inventarios_productos IM, ";
			$sql .= "				inv_med_cod_principios_activos IA,  ";
			$sql .= "				inventarios IT,  ";
			$sql .= "				medicamentos ME  ";
			$sql .= "WHERE	IM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		IM.estado = '1' ";
			$sql .= "AND 		IT.estado = '1' ";
			$sql .= "AND 		IT.empresa_id = '".$empresa."' ";
			$sql .= "AND 		IT.codigo_producto = IM.codigo_producto ";
			
      if($datos['codigo']) 
        $sql .= "AND	  IM.codigo_producto = '".$datos['codigo']."' ";
      if($datos['descripcion']) 
        $sql .= "AND	  IM.descripcion ILIKE '%".$datos['descripcion']."%' ";
      if($datos['principio_activo']) 
        $sql .= "AND	  IA.descripcion ILIKE '%".$datos['principio_activo']."%' ";
      
      if($op !== 0)
      {
        $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
        $this->ProcesarSqlConteo($cont,$off,$cant);
      
        $sql .= "ORDER BY descripcion_producto ";
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			}
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    /**
		* Funcion donde se obtiene la informacion del detalle de la factura
		* 
    * @param string $cxp_detalle_factura_id Identificador del detalle de la factura
    * 
		* @return mixed 
		*/
		function ObtenerDetalleFactura($cxp_detalle_factura_id)
		{ 
      $sql  = "SELECT CD.cxp_detalle_factura_id , ";
      $sql .= " 	    CD.prefijo ,";
      $sql .= " 	    CD.numero ,";
      $sql .= " 	    CD.referencia ,";
      $sql .= " 	    CD.descripcion ,";
      $sql .= " 	    CD.cantidad ,";
      $sql .= " 	    CD.porcentaje_gravamen ,";
      $sql .= " 	    CD.valor_gravamen ,";
      $sql .= " 	    CD.valor_unitario ,";
      $sql .= " 	    CD.valor_total ,";
      $sql .= " 	    CD.autorizacion, ";
      $sql .= " 	    CO.valor, ";
      $sql .= " 	    CG.cxp_glosa_id ";
      $sql .= "FROM   cxp_detalle_facturas CD ";
      $sql .= "       LEFT JOIN cxp_glosas_detalles CG ";
      $sql .= "       ON( CG.cxp_detalle_factura_id = CD.cxp_detalle_factura_id ) ";
      $sql .= "WHERE  CD.cxp_detalle_factura_id = ".$cxp_detalle_factura_id." ";
      
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
  }
?>