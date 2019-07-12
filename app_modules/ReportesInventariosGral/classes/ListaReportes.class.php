<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ListaReportes.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: ListaReportes
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ListaReportes extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function ListaReportes(){}
    /*
    * Funcion donde se obtienen los permisos de los usuarios para acceder al modulo
    *
    * @return mixed
    */
		function ObtenerPermisos($usuario)
		{			
			
			$sql  = "SELECT		E.empresa_id AS empresa, ";
			$sql .= "				E.razon_social AS razon_social, ";
			$sql .= "				C.descripcion AS descripcion_centro_utilidad, ";
			$sql .= "				C.centro_utilidad, ";
			$sql .= "				B.descripcion AS descripcion_bodega, ";
			$sql .= "				B.bodega ";
			$sql .= "FROM	  	userpermisos_reportes_gral as G";
			$sql .= "       			JOIN bodegas as B ON (G.empresa_id = B.empresa_id) 
										AND (G.centro_utilidad = B.centro_utilidad)
										JOIN centros_utilidad as C ON (B.empresa_id = C.empresa_id)
										AND (B.centro_utilidad = C.centro_utilidad)
										JOIN empresas as E ON (C.empresa_id = E.empresa_id) ";
			$sql .= " WHERE	G.usuario_id = ".trim($usuario)." ";
			

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[2]][$rst->fields[4]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
		}
    /**
		* Funcion domde se seleccionan los tipos de id de los terceros 
		* 
		* @return array datos de tipo_id_terceros 
		*/
		function ObtenerTipoIdTerceros()
		{
			$sql  = "SELECT tipo_id_tercero, ";
      $sql .= "       descripcion ";
      $sql .= "FROM   tipo_id_terceros ";
	
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
     /**
		* Funcion domde se seleccionan los tipos de id de los terceros 
		* 
		* @return array datos de tipo_id_terceros 
		*/
		function ObtenerTiposDeBloqueo()
		{
			$sql  = "SELECT ";
      $sql .= "       descripcion, ";
      $sql .= "       tipo_bloqueo_id ";
      $sql .= "FROM   inv_tipos_bloqueos ";
      $sql .= "WHERE  estado='1' ";
	
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
    /**
    * Funcion donde se obtiene el listado de contratos 
    *
    * @param string $empresa Identificador de la empresa
    * @param array $filtros Arreglo con los filtros para la busqueda de la nota
    * @param integer $offset Pagina actual
    *
    * @return mixed
    */
    function ObtenerListadoProveedores($empresa,$filtros,$offset)
    {
      $sql .= "SELECT CP.contratacion_prod_id, ";
      $sql .= "	      CP.no_contrato, ";
      $sql .= "	      CP.descripcion, ";
      $sql .= "	      TO_CHAR(CP.fecha_inicio,'DD/MM/YYYY') AS fecha_inicio, ";
      $sql .= "	      TO_CHAR(CP.fecha_vencimiento,'DD/MM/YYYY') AS fecha_vencimiento, ";
      $sql .= "	      CP.condiciones_entrega, ";
      $sql .= "	      CP.observaciones , ";
      $sql .= "	      CP.estado 	, ";
      $sql .= "	      CP.codigo_proveedor_id, ";
 			$sql .= "   		TE.tercero_id, ";
			$sql .= "   		TE.tipo_id_tercero, ";
			$sql .= "				TE.nombre_tercero ";
      $sql .= "FROM   contratacion_produc_proveedor CP , ";
      $sql .= "       terceros TE  ";
			$sql .= "WHERE  CP.empresa_id = '".$empresa."' ";
			$sql .= "AND 		TE.tercero_id = CP.tercero_id ";
			$sql .= "AND		TE.tipo_id_tercero = CP.tipo_id_tercero ";
			$sql .= "AND		CP.fecha_vencimiento >= NOW()::date ";
      
      if($filtros['tipo_id_tercero'] != '-1')
        $sql .= "AND 		TE.tipo_id_tercero = '".$filtros['tipo_id_tercero']."' ";
      
      if($filtros['tercero_id'])
        $sql .= "AND 		TE.tercero_id = '".$filtros['tercero_id']."' ";
      
      if($filtros['nombre_tercero'])
        $sql .= "AND 		TE.nombre_tercero ILIKE '%".$filtros['nombre_tercero']."%' ";
              
      $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
      
      $this->ProcesarSqlConteo($cont,$offset);
				
			$sql .= "ORDER BY TE.nombre_tercero  ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funcion donde se obtiene el listado de proveedores con productos no conformes 
    *
    * @param string $empresa Identificador de la empresa
    * @param array $filtros Arreglo con los filtros para la busqueda de la nota
    * @param integer $offset Pagina actual
    *
    * @return mixed
    */
    function ObtenerListadoProveedoresNoConforme($empresa,$filtros,$offset)
    {
      $sql  = "SELECT COUNT(*) AS cantidad, ";
      $sql .= "       TO_CHAR(CO.fecha_registro,'DD/MM/YYYY') AS fecha_registro,";
 			$sql .= "   		CO.codigo_proveedor_id, ";
 			$sql .= "   		TE.tercero_id, ";
			$sql .= "   		TE.tipo_id_tercero, ";
			$sql .= "				TE.nombre_tercero ";
      $sql .= "FROM   compras_ordenes_pedidos CO , ";
      $sql .= "       compras_ordenes_pedidos_detalle PD , ";
      $sql .= "       terceros_proveedores TP , ";
      $sql .= "       terceros TE  ";
			$sql .= "WHERE  CO.empresa_id = '".$empresa."' ";
			$sql .= "AND 		CO.orden_pedido_id = PD.orden_pedido_id ";
			$sql .= "AND 		CO.codigo_proveedor_id = TP.codigo_proveedor_id ";
			$sql .= "AND 		TE.tercero_id = TP.tercero_id ";
			$sql .= "AND		TE.tipo_id_tercero = TP.tipo_id_tercero ";
      $sql .= "AND    PD.sw_ingresonc = '1' ";
      
      if($filtros['fecha_inicio'])
        $sql .= "AND     CO.fecha_registro::date >= '".$this->DividirFecha($filtros['fecha_inicio'])."'::date ";
      
      if($filtros['fecha_fin'])
        $sql .= "AND     CO.fecha_registro::date <= '".$this->DividirFecha($filtros['fecha_inicio'])."'::date ";
      
      if($filtros['tipo_id_tercero'] != '-1')
        $sql .= "AND 		TE.tipo_id_tercero = '".$filtros['tipo_id_tercero']."' ";
      
      if($filtros['tercero_id'])
        $sql .= "AND 		TE.tercero_id = '".$filtros['tercero_id']."' ";
      
      if($filtros['nombre_tercero'])
        $sql .= "AND 		TE.nombre_tercero ILIKE '%".$filtros['nombre_tercero']."%' ";
              
      $sql .= "GROUP BY CO.fecha_registro,CO.codigo_proveedor_id,TE.tercero_id, ";
			$sql .= "   		TE.tipo_id_tercero, TE.nombre_tercero ";
      
      $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
      
      $this->ProcesarSqlConteo($cont,$offset);
			
			$sql .= "ORDER BY TE.nombre_tercero  ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * 
    *
    * @param string $empresa Identificador de la empresa
    * @param array $filtros Arreglo con los filtros para la busqueda de la nota
    * @param integer $offset Pagina actual
    *
    * @return mixed
    */
    function ObtenerListadoProveedoresDetalle($filtros)
    {
      $sql .= "SELECT CP.codigo_producto,";
      $sql .= " 	    CP.precio,";
      $sql .= " 	    CP.valor_pactado,";
      $sql .= " 	    CP.valor_porcentaje,";
      $sql .= " 	    CP.valor_total_pactado, ";
 			$sql .= "   		IC.descripcion AS laboratorio, ";
 			$sql .= "   		IT.descripcion AS molecula, ";
 			$sql .= "   		IV.descripcion, ";
 			$sql .= "   		IV.descripcion_abreviada, ";
 			$sql .= "   		IG.sw_medicamento, ";
 			$sql .= "   		IG.sw_insumos, ";
			$sql .= "   		ME.cod_anatomofarmacologico, ";
			$sql .= "   		ME.cod_principio_activo, ";
			$sql .= "   		ME.cod_forma_farmacologica, ";
			$sql .= "   		ME.cod_concentracion, ";
			$sql .= "   		ME.unidad_medida_medicamento_id 	 ";
      $sql .= "FROM   contratacion_produc_prov_detalle CP , ";
      $sql .= "       inv_grupos_inventarios IG,";
      $sql .= "       inv_subclases_inventarios IT,";
      $sql .= "       inv_clases_inventarios IC,";
      $sql .= "       inventarios_productos IV  ";
      $sql .= "       LEFT JOIN medicamentos ME ";
      $sql .= "       ON(ME.codigo_medicamento = IV.codigo_producto) ";
			$sql .= "WHERE  CP.contratacion_prod_id = '".$filtros['contratacion_prod_id']."' ";
			$sql .= "AND 		CP.codigo_producto = IV.codigo_producto ";
      $sql .= "AND    IT.grupo_id = IV.grupo_id ";
      $sql .= "AND    IT.clase_id = IV.clase_id ";
      $sql .= "AND    IT.subclase_id = IV.subclase_id ";
      $sql .= "AND    IC.grupo_id = IT.grupo_id ";
      $sql .= "AND    IC.clase_id = IT.clase_id ";
      $sql .= "AND    IG.grupo_id = IC.grupo_id ";
      
      if($filtros['tipos_productos'] == 'M')
        $sql .= "AND    IG.sw_medicamento = '1' ";
      else if($filtros['tipos_productos'] == 'I')
        $sql .= "AND    IG.sw_insumos = '1' ";
      
			$sql .= "ORDER BY CP.codigo_producto  ";

      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funcion donde se obtiene el listado de productos no conformes por proveedor
    *
    * @param string $empresa Identificador de la empresa
    * @param array $filtros Arreglo con los filtros para la busqueda de la nota
    *
    * @return mixed
    */
    function ObtenerListadoProveedoresNoConformeDetalle($filtros)
    {
      $sql  = "SELECT PD.codigo_producto,";
      $sql .= "       PD.numero_unidades_recibidas,";
      $sql .= " 	    PD.lote_temp AS lote,";
      $sql .= " 	    TO_CHAR(PD.fecha_vencimiento_temp,'DD/MM/YYYY') AS fecha_vencimiento, 	";
 			$sql .= "   		IC.descripcion AS laboratorio, ";
 			$sql .= "   		IT.descripcion AS molecula, ";
 			$sql .= "   		IV.descripcion, ";
 			$sql .= "   		IV.descripcion_abreviada, ";
      $sql .= "   		IG.sw_medicamento, ";
 			$sql .= "   		IG.sw_insumos, ";
			$sql .= "   		ME.cod_anatomofarmacologico, ";
			$sql .= "   		ME.cod_principio_activo, ";
			$sql .= "   		ME.cod_forma_farmacologica, ";
			$sql .= "   		ME.cod_concentracion, ";
			$sql .= "   		ME.unidad_medida_medicamento_id 	 ";
      $sql .= "FROM   compras_ordenes_pedidos CO , ";
      $sql .= "       compras_ordenes_pedidos_detalle PD , ";
      $sql .= "       inv_grupos_inventarios IG,";
      $sql .= "       inv_subclases_inventarios IT,";
      $sql .= "       inv_clases_inventarios IC,";
      $sql .= "       inventarios_productos IV  ";
      $sql .= "       LEFT JOIN medicamentos ME ";
      $sql .= "       ON(ME.codigo_medicamento = IV.codigo_producto) ";
			$sql .= "WHERE  CO.codigo_proveedor_id = '".$filtros['codigo_proveedor_id']."' ";
			$sql .= "AND 		CO.orden_pedido_id = PD.orden_pedido_id ";
      $sql .= "AND    PD.sw_ingresonc = '1' ";      
      $sql .= "AND    CO.fecha_registro::date = '".$this->DividirFecha($filtros['fecha_registro'])."'::date ";
      $sql .= "AND 		PD.codigo_producto = IV.codigo_producto ";
      $sql .= "AND    IT.grupo_id = IV.grupo_id ";
      $sql .= "AND    IT.clase_id = IV.clase_id ";
      $sql .= "AND    IT.subclase_id = IV.subclase_id ";
      $sql .= "AND    IC.grupo_id = IT.grupo_id ";
      $sql .= "AND    IC.clase_id = IT.clase_id ";
      $sql .= "AND    IG.grupo_id = IC.grupo_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funcion donde se obtiene el listado de productos sin movimiento
    *
    * @param string $empresa Identificador de la empresa
    * @param array $filtros Arreglo con los filtros para la busqueda de la nota
    *
    * @return mixed
    */
    function ObtenerListadoProductosSinMovimiento($empresa,$centro_utilidad,$bodega,$filtros,$offset,$opcion = 1)
    {
      $sql  = "SELECT TO_CHAR(EM.fecha_movimiento,'DD/MM/YYYY') AS fecha_movimiento, 	";
 			$sql .= "   		edad_completa(EM.fecha_movimiento::date) AS tiempo_movimiento, ";
 			$sql .= "   		IC.descripcion AS laboratorio, ";
 			$sql .= "   		IT.descripcion AS molecula, ";
 			$sql .= "   		IV.descripcion, ";
 			$sql .= "   		IV.descripcion_abreviada, ";
      $sql .= "   		IG.sw_medicamento, ";
 			$sql .= "   		IG.sw_insumos, ";
 			$sql .= "   		EM.codigo_producto, ";
 			$sql .= "   		EM.existencia, ";
			$sql .= "   		ME.cod_anatomofarmacologico, ";
			$sql .= "   		ME.cod_principio_activo, ";
			$sql .= "   		ME.cod_forma_farmacologica, ";
			$sql .= "   		ME.cod_concentracion, ";
			$sql .= "   		ME.unidad_medida_medicamento_id 	 ";
      $sql .= "FROM   inv_grupos_inventarios IG,";
      $sql .= "       inv_subclases_inventarios IT,";
      $sql .= "       inv_clases_inventarios IC,";
      $sql .= "       inventarios_productos IV  ";
      $sql .= "       LEFT JOIN medicamentos ME ";
      $sql .= "       ON(ME.codigo_medicamento = IV.codigo_producto), ";
      $sql .= "       (";
      $sql .= "         SELECT  empresa_id,";
      $sql .= "                 codigo_producto,";
      $sql .= "                 SUM(existencia) AS existencia,";
      $sql .= "                 MAX(fecha_movimiento) AS fecha_movimiento";
      $sql .= "         FROM    existencias_bodegas";
      $sql .= "         WHERE   empresa_id = '".trim($empresa)."' ";
	  $sql .= "			AND centro_utilidad ='".trim($centro_utilidad)."' ";
	  $sql .= "			AND bodega ='".trim($bodega)."' ";
      $sql .= "         GROUP BY empresa_id,codigo_producto";
      $sql .= "         ORDER BY codigo_producto";
      $sql .= "       ) EM ";
			$sql .= "WHERE  EM.codigo_producto = IV.codigo_producto ";
      $sql .= "AND    IT.grupo_id = IV.grupo_id ";
      $sql .= "AND    IT.clase_id = IV.clase_id ";
      $sql .= "AND    IT.subclase_id = IV.subclase_id ";
      $sql .= "AND    IC.grupo_id = IT.grupo_id ";
      $sql .= "AND    IC.clase_id = IT.clase_id ";
      $sql .= "AND    IG.grupo_id = IC.grupo_id ";
      $sql .= "AND    EM.existencia > 0 ";
      if($filtros['fecha_inicio'])
        $sql .= "AND   EM.fecha_movimiento::date <= '".$this->DividirFecha($filtros['fecha_inicio'])."'::date ";
      
      
      if($opcion == 1)
      {
        $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
        $this->ProcesarSqlConteo($cont,$offset);
				
        $sql .= "ORDER BY EM.fecha_movimiento  ASC ";
  			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      }
      else
        $sql .= "ORDER BY EM.fecha_movimiento  ASC ";
        
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funcion donde se obtiene el listado de productos con fecha de vencimiento proxima
    *
    * @param string $empresa Identificador de la empresa
    * @param array $filtros Arreglo con los filtros para la busqueda de la nota
    *
    * @return mixed
    */
    function ObtenerListadoProductosVencimiento($empresa,$centro_utilidad,$bodega,$filtros,$dias_vence,$offset,$opcion = 1)
    {
		/*$sql  = "SELECT TO_CHAR(EM.fecha_vencimiento,'DD/MM/YYYY') AS fecha_vencimiento, 	";
		$sql .= "        EM.lote,";
		$sql .= "   		IC.descripcion AS laboratorio, ";
		$sql .= "   		IT.descripcion AS molecula, ";
		$sql .= "   		IV.descripcion, ";
		$sql .= "   		IV.descripcion_abreviada, ";
		$sql .= "   		IG.sw_medicamento, ";
		$sql .= "   		IG.sw_insumos, ";
		$sql .= "   		EM.codigo_producto, ";
		$sql .= "   		EM.existencia, ";
		$sql .= "   		ME.cod_anatomofarmacologico, ";
		$sql .= "   		ME.cod_principio_activo, ";
		$sql .= "   		ME.cod_forma_farmacologica, ";
		$sql .= "   		ME.cod_concentracion, ";
		$sql .= "   		ME.unidad_medida_medicamento_id 	 ";
		$sql .= "FROM   inv_grupos_inventarios IG,";
		$sql .= "       inv_subclases_inventarios IT,";
		$sql .= "       inv_clases_inventarios IC,";
		$sql .= "       inventarios IE,";
		$sql .= "       inventarios_productos IV  ";
		$sql .= "       LEFT JOIN medicamentos ME ";
		$sql .= "       ON(ME.codigo_medicamento = IV.codigo_producto), ";
		$sql .= "       (";
		$sql .= "         SELECT  empresa_id,";
		$sql .= "                 codigo_producto,";
		$sql .= "                 fecha_vencimiento, ";
		$sql .= "                 lote, ";
		$sql .= "                 SUM(existencia_actual) AS existencia ";
		$sql .= "         FROM    existencias_bodegas_lote_fv ";
		$sql .= "         WHERE   empresa_id = '".trim($empresa)."' ";
		$sql .= "			AND centro_utilidad = '".trim($centro_utilidad)."' ";
		$sql .= "			AND bodega = '".trim($bodega)."' ";
		$sql .= "         GROUP BY empresa_id,codigo_producto,fecha_vencimiento,lote ";
		$sql .= "         ORDER BY codigo_producto";
		$sql .= "       ) EM ";
		$sql .= "WHERE  EM.codigo_producto = IV.codigo_producto ";
		$sql .= "AND    IT.grupo_id = IV.grupo_id ";
		$sql .= "AND    IT.clase_id = IV.clase_id ";
		$sql .= "AND    IT.subclase_id = IV.subclase_id ";
		$sql .= "AND    IC.grupo_id = IT.grupo_id ";
		$sql .= "AND    IC.clase_id = IT.clase_id ";
		$sql .= "AND    IG.grupo_id = IC.grupo_id ";
		$sql .= "AND    IE.empresa_id = '".trim($empresa)."' ";
		$sql .= "AND    IE.codigo_producto = IV.codigo_producto ";
		$sql .= "AND    EM.existencia > 0 ";*/
      
	  $sql = "	SELECT 
					a.codigo_producto,
					fc_descripcion_producto(a.codigo_producto)as descripcion,
					TO_CHAR(a.fecha_vencimiento,'DD/MM/YYYY') AS fecha_vencimiento,
					a.lote,
					a.existencia_actual as existencia
					FROM
					existencias_bodegas_lote_fv as a
					JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
					WHERE TRUE
					AND a.empresa_id = '".trim($empresa)."'
					AND a.centro_utilidad = '".trim($centro_utilidad)."'
					AND a.bodega = '".trim($bodega)."'
					AND a.existencia_actual >0 ";
      
      $ctl = AutoCarga::factory("ClaseUtil");
      $fecha = $ctl->sumaDia(date("d/m/Y"),$dias_vence,"/");
      $sql .= " AND   a.fecha_vencimiento::date <= '".$this->DividirFecha($fecha)."'::date ";
		/*print_r($sql);*/
      if($opcion == 1)
      {
        $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
        $this->ProcesarSqlConteo($cont,$offset);
				
        $sql .= " ORDER BY a.fecha_vencimiento  ASC,b.descripcion ASC ";
  			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      }
      else
        $sql .= " ORDER BY a.fecha_vencimiento  ASC,b.descripcion ASC ";
        
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      
      //print_r($sql);
      
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
    * Funcion donde se obtiene el listado de productos con fecha de vencimiento proxima
    *
    * @param string $empresa Identificador de la empresa
    * @param array $filtros Arreglo con los filtros para la busqueda de la nota
    *
    * @return mixed
    */
    function ObtenerListadoUsuarioCodBarras($empresa,$filtros,$offset,$opcion = 1)
    {
     // $this->debug=true;
      $sql  = "Select  u.usuario,u.descripcion,u.nombre,";
      $sql .= " emp.razon_social ";
      
      
      $sql .= " From ";
      $sql .= " system_usuarios u, ";
      $sql .= " inv_bodegas_userpermisos ub, ";
      $sql .= " empresas emp ";
     
      
      
      $sql .= " where ";
      $sql .= " ub.usuario_id = u.usuario_id ";
      $sql .= " and ub.usuario_id Not In ";
      $ctl = AutoCarga::factory("ClaseUtil");    
       
      $sql .= "                     (";
      $sql .= "                     Select usrc.usuario_id";
      $sql .= "                     from";
      $sql .= "                     usuarios_busquedas usrc ";
      $sql .= "                     WHERE "; 
      $sql .= "                     usrc.cnt_busquedas_codigos_barras > 0 "; 
       if($filtros['fecha_inicio'])
          {
          $sql .= "AND   usrc.fecha_busqueda::date >= '".$this->DividirFecha($filtros['fecha_inicio'])."'::date ";
          }
          if($filtros['fecha_final'])
          {
          $sql .= "AND   usrc.fecha_busqueda::date <= '".$this->DividirFecha($filtros['fecha_final'])."'::date ";
          }
       $sql .= "                     ) ";   
       
          $sql .= "AND  ub.empresa_id = emp.empresa_id "; 
      

      if($opcion == 1)
        {
                                    $sql .= " GROUP BY u.descripcion,u.nombre,emp.razon_social,u.usuario ";
                                    $sql .= " ORDER BY u.usuario  ASC ";
                                    $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
                                    $this->ProcesarSqlConteo($cont,$offset);
                                    
                                    
                        //            $sql .= "ORDER BY usrc.fecha_busqueda  ASC ";
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
        }
      else
      {
           $sql .= " GROUP BY u.descripcion,u.nombre,emp.razon_social,u.usuario ";
           $sql .= "ORDER BY u.usuario  ASC ";
     }
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      
      //print_r($sql);
      
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
    * Funcion donde se obtiene el listado de contratos 
    *
    * @param string $empresa Identificador de la empresa
    * @param array $filtros Arreglo con los filtros para la busqueda de la nota
    * @param integer $offset Pagina actual
    *
    * @return mixed
    */
    function ObtenerPacientesEstados($TipoBloqueo,$opcion,$offset)
    {
     //$this->debug=true;
      $num=count($TipoBloqueo);
      $i=1;
      
      foreach($TipoBloqueo as $k=>$valor)
      {
      $sql .= "SELECT pac.*, ";
      $sql .= "				blo.descripcion ";
      $sql .= "FROM   pacientes pac , ";
      $sql .= "       inv_tipos_bloqueos blo ";
			$sql .= " WHERE  ";
			$sql .= "   		pac.tipo_bloqueo_id = blo.tipo_bloqueo_id ";
      $sql .= " AND		pac.tipo_bloqueo_id = '".$valor."' ";
      
      if($i<$num)
      $sql .= " UNION  ";
      $i++;
      }
      if($num==0)
      {
      $sql .= "SELECT pac.*, ";
      $sql .= "				blo.descripcion ";
      $sql .= "FROM   pacientes pac , ";
      $sql .= "       inv_tipos_bloqueos blo ";
			$sql .= " WHERE  ";
			$sql .= "   		pac.tipo_bloqueo_id = blo.tipo_bloqueo_id ";
      }

       
      if($opcion == 1)
      {
        $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
        $this->ProcesarSqlConteo($cont,$offset);
				
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      }
      
        
        
      if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
    }
    
    
    function ObtenerFarmacias()
		{			
			//$this->debug=true;
      $sql  = "SELECT	E.* ";
			$sql .= "FROM	  ";
      $sql .= "       empresas E ";
			$sql .= "WHERE	E.sw_tipo_empresa = '1' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
		}
   
    
    function ObtenerPrefijosDespachosPendientes()
		{			
			//$this->debug=true;
      $sql  = "SELECT	prefijo ";
			$sql .= "FROM	  ";
      $sql .= "       inv_mov_pendientes_solicitudes_frm ";
			$sql .= " Group By prefijo ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
		}
    
    
    function ObtenerDespachosPendientes($empresa_origen,$farmacia_destino,$datos)
		{			
			/*$this->debug=true;*/ 
      if($numero!="")
      {
      $adicional = " AND numero = ".$numero." ";
      }
      
      $sql .= "SELECT	 ";
      $sql .= "   a.solicitud_prod_a_bod_ppal_id,
             a.cantidad_pendiente,
             a.cantidad_solicitada,
             a.farmacia_id,
			 a.codigo_producto,
			 fc_descripcion_producto(a.codigo_producto) as producto,
             f.razon_social||' ('||cent.descripcion ||'-'||bod.descripcion||')' as razon_social,
             a.usuario_id
            from
              (
            SELECT 
                      sd.solicitud_prod_a_bod_ppal_id,
                      sd.cantidad_solic as cantidad_pendiente,
                      sd.cantidad_solic as cantidad_solicitada,
					  sd.codigo_producto,
                      s.farmacia_id,
					  s.centro_utilidad,
					  s.bodega,
                      s.usuario_id
                      from
                      solicitud_productos_a_bodega_principal_detalle sd,
                      solicitud_productos_a_bodega_principal s
                      where TRUE
                      and   sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id
                      and   s.empresa_destino = '".trim($empresa_origen)."'
                      and   s.sw_despacho = '0'
					  and	  sd.cantidad_solic > 0 ";
					  
					  if($datos['fecha_inicio'])
					  $sql .= " and   s.fecha_registro::date >= '".$this->DividirFecha($datos['fecha_inicio'])."'::date  ";
					  if($datos['fecha_final'])
					  $sql .= " and   s.fecha_registro::date <= '".$this->DividirFecha($datos['fecha_final'])."'::date ";
                      
					  $sql .= "
					  UNION     
                      SELECT 
                      sd.solicitud_prod_a_bod_ppal_id,
                      ips.cantidad_pendiente,
                      ips.cantidad_solicitad as cantidad_solicitada,
					  sd.codigo_producto,
                      ips.farmacia_id,
					  s.centro_utilidad,
					  s.bodega,
                      s.usuario_id
                      from
                      solicitud_productos_a_bodega_principal_detalle sd,
                      solicitud_productos_a_bodega_principal s,
                      inv_mov_pendientes_solicitudes_frm ips
                      where TRUE ";
                      if($datos['fecha_inicio'])
					  $sql .= " and   s.fecha_registro::date >= '".$this->DividirFecha($datos['fecha_inicio'])."'::date  ";
					  if($datos['fecha_final'])
					  $sql .= " and   s.fecha_registro::date <= '".$this->DividirFecha($datos['fecha_final'])."'::date ";
					  
					  $sql .= "and   sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id
                      and   s.empresa_destino = '".trim($empresa_origen)."'
                      and   s.sw_despacho = '1'
                      and   sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id
                      and   sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id
                      and   sd.solicitud_prod_a_bod_ppal_det_id = ips.solicitud_prod_a_bod_ppal_det_id
					  and	  ips.cantidad_solicitad >0
                 )as a
                 JOIN bodegas as bod ON (a.farmacia_id = bod.empresa_id)
				 AND (a.centro_utilidad = bod.centro_utilidad)
				 AND (a.bodega = bod.bodega)
				 JOIN centros_utilidad as cent ON (bod.empresa_id = cent.empresa_id)
				 AND (bod.centro_utilidad = cent.centro_utilidad)
				 JOIN empresas as f ON (cent.empresa_id = f.empresa_id)
				 WHERE TRUE
				 AND   f.razon_social||' '||cent.descripcion ||' '||bod.descripcion ILIKE '%".trim($farmacia_destino)."%'
				 order by a.farmacia_id ASC,a.centro_utilidad ASC,a.bodega ASC,producto ASC; ";
		
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
		}
    
    
    
    
    
    
    
        function ObtenerProveedoresOC($empresa_id)
		{			
			/*$this->debug=true;*/
		$sql .= "SELECT ";
		$sql .= "   		TE.tercero_id, ";
		$sql .= "   		TE.tipo_id_tercero, ";
		$sql .= "				TE.nombre_tercero,  ";
		$sql .= "				TP.codigo_proveedor_id  ";
		$sql .= "FROM   terceros_proveedores TP  ";
		$sql .= "      	JOIN terceros TE ON(TE.tercero_id = TP.tercero_id) 
							AND (TE.tipo_id_tercero = TP.tipo_id_tercero) ";
		$sql .= " WHERE TRUE ";
		$sql .= "   		AND TP.codigo_proveedor_id IN ";
		$sql .= "		    ( Select comp.codigo_proveedor_id";
		$sql .= "         from";
		$sql .= "         compras_ordenes_pedidos comp ";
		$sql .= "         where ";
		$sql .= "         comp.estado= '1' ";
		$sql .= "		AND empresa_id = '".trim($empresa_id)."'";
		$sql .= "         ) 
		ORDER BY nombre_tercero";
//    print_r($sql);
      
      if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
		}
    
    
    
     function ObtenerComprasPendientes($empresa,$codigo_proveedor_id,$orden_pedido_id,$datos)
		{			
		/*$this->debug=true;*/
      if($codigo_proveedor_id!="")
      {
      $adicional .= " AND comp.codigo_proveedor_id = ".$codigo_proveedor_id." ";
      }
      
      if($orden_pedido_id!="")
      {
      $adicional .= " AND comp.orden_pedido_id = ".$orden_pedido_id." ";
      }
      

	$sql .= "SELECT ";
	$sql .= "   		TE.tercero_id, ";
	$sql .= "   		TE.tipo_id_tercero, ";
	$sql .= "				TE.nombre_tercero,  ";
	$sql .= "				TP.codigo_proveedor_id,  ";
	$sql .= "       comp.orden_pedido_id,";
	$sql .= "       comp.fecha_orden,";
	$sql .= "       compd.codigo_producto,";
	$sql .= "       compd.numero_unidades,";
	$sql .= "       compd.numero_unidades_recibidas,";
	$sql .= "       fc_descripcion_producto(compd.codigo_producto) as producto ";


	$sql .= "FROM   terceros_proveedores TP , ";
	$sql .= "       terceros TE,  ";
	$sql .= "       inventarios_productos prod, ";
	$sql .= "       compras_ordenes_pedidos comp, ";
	$sql .= "       compras_ordenes_pedidos_detalle compd ";
	$sql .= "WHERE  ";
	$sql .= "   		comp.empresa_id = '".trim($empresa)."' ";
	$sql .= "   		AND ";
	$sql .= "   		comp.estado = '1' ";

	$sql .= "     ".$adicional;
	
	if($datos['fecha_inicio'])
	$sql .= " and   comp.fecha_registro::date >= '".$this->DividirFecha($datos['fecha_inicio'])."'::date  ";
	if($datos['fecha_final'])
	$sql .= " and   comp.fecha_registro::date <= '".$this->DividirFecha($datos['fecha_final'])."'::date ";
	
	$sql .= "   		AND ";
	$sql .= "   		comp.codigo_proveedor_id = TP.codigo_proveedor_id ";
	$sql .= "   		AND ";
	$sql .= "   		TP.tercero_id = TE.tercero_id ";
	$sql .= "   		AND ";
	$sql .= "   		TP.tipo_id_tercero = TE.tipo_id_tercero ";
	$sql .= "   		AND ";
	$sql .= "       comp.orden_pedido_id = compd.orden_pedido_id ";
	$sql .= "   		AND ";
	$sql .= "       COALESCE(compd.numero_unidades,0) <> COALESCE(compd.numero_unidades_recibidas,0) ";
	$sql .= "       AND ";
	$sql .= "       compd.codigo_producto = prod.codigo_producto ";
	$sql .= "		ORDER BY  comp.orden_pedido_id ";
//    print_r($sql);
      
      if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
		}
    
    
      /**
    * Funcion donde se obtiene el listado de productos sin movimiento
    *
    * @param string $empresa Identificador de la empresa
    * @param array $filtros Arreglo con los filtros para la busqueda de la nota
    *
    * @return mixed
    */
    function ObtenerListadoLogAuditoria($empresa,$filtros,$offset,$opcion = 1)
    {
      //$this->debug=true;
		$sql  = "SELECT ";
		$sql .= "   		ag.auditoria_general_id, ";
		$sql .= "   		ag.accion_id, ";
		$sql .= "   		ag.novedad_indicador_id, ";
		$sql .= "   		ag.table_name, ";
		$sql .= "   		ag.fecha_registro, ";
		$sql .= "   		ag.usuario_registro, ";
		$sql .= "   		ac.descripcion, ";
		$sql .= "   		ni.descripcion as indicador, ";
		$sql .= "   		us.nombre ";
		$sql .= "FROM   auditorias_generales ag,";
      $sql .= "       acciones ac, ";
      $sql .= "       novedades_indicadores ni, ";
      $sql .= "       system_usuarios us ";
      $sql .= "WHERE  ";
      $sql .= "       ag.accion_id= ac.accion_id ";
      $sql .= "AND    ag.novedad_indicador_id = ni.novedad_indicador_id ";
      $sql .= "AND    ag.usuario_registro = us.usuario_id ";
      
	  if($filtros['nombre_tabla'])
	  $sql .= " AND ag.table_name ILIKE '%".$filtros['nombre_tabla']."%' ";
	  
	  
      if($filtros['fecha_inicio'])
        $sql .= "AND   ag.fecha_registro::date >= '".$this->DividirFecha($filtros['fecha_inicio'])."'::date ";
      
      if($filtros['fecha_final'])
        $sql .= "AND   ag.fecha_registro::date <= '".$this->DividirFecha($filtros['fecha_final'])."'::date ";
      
      if($opcion == 1)
      {
        $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
        $this->ProcesarSqlConteo($cont,$offset);
				
        $sql .= "ORDER BY ag.fecha_registro  ASC ";
  			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      }
      else
        $sql .= "ORDER BY ag.fecha_registro  ASC ";
      // print_r($filtros);
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funcion donde se obtiene el listado de productos sin movimiento
    *
    * @param string $empresa Identificador de la empresa
    * @param array $filtros Arreglo con los filtros para la busqueda de la nota
    *
    * @return mixed
    */
    function ObtenerListadoDetalleLogAuditoria($auditoria_general_id)
    {
   //   $this->debug=true;
      $sql  = "SELECT ";
 			$sql .= "       ag.*,";
      $sql .= "   		ac.descripcion, ";
 			$sql .= "   		ni.descripcion as indicador, ";
 			$sql .= "   		ni.puntaje, ";
 			$sql .= "   		us.nombre ";
 			$sql .= "FROM   auditorias_generales ag,";
      $sql .= "       acciones ac, ";
      $sql .= "       novedades_indicadores ni, ";
      $sql .= "       system_usuarios us ";
      $sql .= "WHERE  ";
      $sql .= "   		ag.auditoria_general_id = ".$auditoria_general_id." ";
      $sql .= "AND    ag.accion_id= ac.accion_id ";
      $sql .= "AND    ag.novedad_indicador_id = ni.novedad_indicador_id ";
      $sql .= "AND    ag.usuario_registro = us.usuario_id; ";
    
      // print_r($filtros);
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
		* Funcion donde se obtiene el nombre de un usuario
		*
		* @param int $usuario Identificacion del usuario
		*
		* @return mixed
		*/
		function ObtenerInformacionUsuario($usuario)
		{
			$sql .= "SELECT	nombre ";
			$sql .= "FROM		system_usuarios "; 
			$sql .= "WHERE	usuario_id = ".$usuario." ";		
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			if(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
    
		/**
		* Funcion donde se obtiene el nombre de Empresa
		*
		* @param int $usuario Identificacion del usuario
		*
		* @return mixed
		*/
		function NombreEmpresa($empresa_id)
		{
			
      $sql .= "SELECT	
                      razon_social,
                      empresa_id ";
			$sql .= "FROM		empresas "; 
			$sql .= "WHERE	empresa_id = '".$empresa_id."'; ";		
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			if(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
		
		  
    function ObtenerListadoSelectivo($empresa,$filtro)
		{			
			//$this->debug=true;
			$tp = explode(',',$filtro['tipo_producto']);
			$tipo_producto = " '".$tp[0]."','".$tp[1]."' ";
			if($filtro['opcion']=='1')
				$sql  = "
				SELECT
				a.codigo_producto,
				fc_descripcion_producto(a.codigo_producto) as producto,
				a.total as rotacion,
				a.existencia
				FROM
				(
							SELECT
							x.codigo_producto,
							SUM(x.total_egreso) AS total,
							AVG(y.existencia) as existencia
							FROM
							(
								SELECT
								e.codigo_producto,
								SUM(e.cantidad) as total_egreso
								FROM
								inv_bodegas_movimiento as a
								JOIN inv_bodegas_documentos as b ON(a.documento_id = b.documento_id)
								AND (a.empresa_id = b.empresa_id)
								AND (a.centro_utilidad = b.centro_utilidad)
								AND (a.bodega = b.bodega)
								JOIN documentos as c ON (b.documento_id = c.documento_id)
								AND (b.empresa_id = c.empresa_id) 
								JOIN tipos_doc_generales AS d ON (c.tipo_doc_general_id = d.tipo_doc_general_id)
								AND (d.inv_tipo_movimiento IN('E'))
								JOIN inv_bodegas_movimiento_d as e ON (a.empresa_id = e.empresa_id)
								AND (a.prefijo = e.prefijo)
								AND (a.numero = e.numero)
								JOIN inventarios_productos as f ON (e.codigo_producto = f.codigo_producto)
								JOIN inv_subclases_inventarios as g ON (f.grupo_id = g.grupo_id)
								AND (f.clase_id = g.clase_id)
								AND (f.subclase_id = g.subclase_id)
								JOIN inv_clases_inventarios as h ON (g.grupo_id = h.grupo_id)
								AND (g.clase_id = h.clase_id)
								JOIN inv_grupos_inventarios as i ON(h.grupo_id = i.grupo_id)
								WHERE TRUE
								AND (c.tipo_doc_general_id not in (SELECT tipo_doc_general_id FROM Inv_Documentos_Rotacion WHERE empresa_id= '".trim($empresa['empresa'])."' AND tipo_doc_general_id = c.tipo_doc_general_id)) 
								and   a.fecha_registro::date BETWEEN '".trim($this->DividirFecha($filtro['fecha_inicio']))."'::date  
								and   '".trim($this->DividirFecha($filtro['fecha_final']))."'::date 
								AND a.empresa_id = '".trim($empresa['empresa'])."' 
								AND a.bodega='".trim($empresa['bodega'])."'
								AND a.centro_utilidad='".trim($empresa['centro_utilidad'])."'
								AND i.sw_medicamento  IN (".$tipo_producto.")
								GROUP BY  e.codigo_producto
								UNION
										SELECT
										c.codigo_producto,
										SUM(c.cantidad) as total_egreso
										FROM
										bodegas_documentos as a
										JOIN bodegas_doc_numeraciones as b ON (a.bodegas_doc_id = b.bodegas_doc_id)
										JOIN bodegas_documentos_d as c ON (a.bodegas_doc_id = c.bodegas_doc_id)
										AND (a.numeracion = c.numeracion)
										AND (b.tipo_movimiento IN('E'))
										JOIN inventarios_productos as d ON (c.codigo_producto = d.codigo_producto)
										JOIN inv_subclases_inventarios as e ON (d.grupo_id = e.grupo_id)
										AND (d.clase_id = e.clase_id)
										AND (d.subclase_id = e.subclase_id)
										JOIN inv_clases_inventarios as f ON (e.grupo_id = f.grupo_id)
										AND (e.clase_id = f.clase_id)
										JOIN inv_grupos_inventarios as g ON(f.grupo_id = g.grupo_id)
										WHERE TRUE
										and   a.fecha_registro::date BETWEEN '".trim($this->DividirFecha($filtro['fecha_inicio']))."'::date  
										and   '".trim($this->DividirFecha($filtro['fecha_final']))."'::date 
										AND b.empresa_id = '".trim($empresa['empresa'])."' 
										AND b.bodega='".trim($empresa['bodega'])."'
										AND b.centro_utilidad='".trim($empresa['centro_utilidad'])."'
										AND g.sw_medicamento IN (".$tipo_producto.")
										GROUP BY  c.codigo_producto
										ORDER BY total_egreso DESC
							) AS x
							JOIN existencias_bodegas as y ON (x.codigo_producto = y.codigo_producto)
							AND (y.empresa_id = '".trim($empresa['empresa'])."' )
							AND (y.bodega='".trim($empresa['bodega'])."')
							AND (y.centro_utilidad='".trim($empresa['centro_utilidad'])."')
							WHERE
							y.existencia >0
							group by x.codigo_producto
							ORDER BY total DESC
							LIMIT ".$filtro['cantidad_conteo']."
				)as a
				ORDER BY RANDOM();";
		
			if($filtro['opcion']=='2')
			$sql  = "
			SELECT
			x.codigo_producto,
			fc_descripcion_producto(x.codigo_producto) as producto,
			x.existencia,
			x.costo
			FROM
					(
					SELECT
					a.codigo_producto,
					a.existencia,
					b.costo
					FROM
					existencias_bodegas as a
					JOIN inventarios as b ON (a.empresa_id = b.empresa_id)
					AND (a.codigo_producto = b.codigo_producto)
					JOIN inventarios_productos as c ON (b.codigo_producto = c.codigo_producto)
					JOIN inv_subclases_inventarios as d ON (c.grupo_id = d.grupo_id)
					AND (c.clase_id = d.clase_id)
					AND (c.subclase_id = d.subclase_id)
					JOIN inv_clases_inventarios as e ON (d.grupo_id = e.grupo_id)
					AND (d.clase_id = e.clase_id)
					JOIN inv_grupos_inventarios as f ON (e.grupo_id = f.grupo_id)
					WHERE TRUE
					AND a.empresa_id = '".trim($empresa['empresa'])."' 
					AND a.bodega='".trim($empresa['bodega'])."'
					AND a.centro_utilidad='".trim($empresa['centro_utilidad'])."'
					AND f.sw_medicamento IN (".$tipo_producto.")
					AND a.existencia >0
					ORDER BY costo DESC
					LIMIT ".$filtro['cantidad_conteo']."
					) as x
			ORDER BY RANDOM();";
			
			if($filtro['opcion']=='3')
			$sql  = "SELECT
			a.codigo_producto,
			fc_descripcion_producto(a.codigo_producto) as producto,
			a.existencia
			FROM
			existencias_bodegas as a
			JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
			JOIN inv_subclases_inventarios as c ON (b.grupo_id = c.grupo_id)
			AND (b.clase_id = c.clase_id)
			AND (b.subclase_id = c.subclase_id)
			JOIN inv_clases_inventarios as d ON (c.grupo_id = d.grupo_id)
			AND (c.clase_id = d.clase_id)
			JOIN inv_grupos_inventarios as e ON (d.grupo_id = e.grupo_id)
			WHERE TRUE
			AND a.empresa_id = '".trim($empresa['empresa'])."'
			AND a.centro_utilidad = '".trim($empresa['centro_utilidad'])."'
			AND a.bodega = '".trim($empresa['bodega'])."'
			AND e.sw_medicamento IN (".$tipo_producto.")
			AND a.existencia > 0
			ORDER BY RANDOM(),a.codigo_producto
			LIMIT ".$filtro['cantidad_conteo'].";";
		/*print_r($sql);*/
		if(!$rst = $this->ConexionBaseDatos($sql))
		return false;

		$datos = array();
		while(!$rst->EOF)
		{
		$datos[] = $rst->GetRowAssoc($ToUpper = false);
		$rst->MoveNext();
		}

		$rst->Close();
		return $datos;
		}
		
		function ObtenerLotesSelectivo($empresa,$productos)
		{	
		
		$sql = "
		SELECT
		a.codigo_producto,
		a.lote,
		a.fecha_vencimiento,
		a.existencia_actual
		FROM
		existencias_bodegas_lote_fv as a
		WHERE TRUE
		AND a.codigo_producto IN (".$productos.")
		AND a.existencia_actual >0
		AND a.empresa_id = '".trim($empresa['empresa'])."'
		AND a.centro_utilidad = '".trim($empresa['centro_utilidad'])."'
		AND a.bodega = '".trim($empresa['bodega'])."';	";

		if(!$rst = $this->ConexionBaseDatos($sql))
		return false;

		$datos = array();
		while(!$rst->EOF)
		{
		$datos[$rst->fields[0]][]  = $rst->GetRowAssoc($ToUpper = false);
		$rst->MoveNext();
		}

		$rst->Close();
		return $datos;
		}
		
		
		function ObtenerDespachosIngresos($empresa,$filtros,$offset,$opcion = 1)
		{	
		
		$sql = "
					SELECT
					a.solicitud_prod_a_bod_ppal_id,
					g.razon_social ||':'||f.descripcion||'-'||e.descripcion as farmacia,
					b.prefijo||'-'||b.numero as documento_despacho,
					h.fecha_registro as fecha_despacho,
					c.prefijo||'-'||c.numero as documento_ingreso,
					i.fecha_registro as fecha_ingreso,
					u.usuario_id,
					u.nombre
					FROM
					solicitud_productos_a_bodega_principal AS a
					JOIN inv_bodegas_movimiento_despachos_farmacias as b ON (a.solicitud_prod_a_bod_ppal_id = b.solicitud_prod_a_bod_ppal_id)
					LEFT JOIN inv_bodegas_movimiento_ingresosdespachos_farmacias as c ON (b.empresa_id = c.empresa_despacho)
					AND (b.prefijo = c.prefijo_despacho)
					AND (b.numero = c.numero_despacho)
					LEFT JOIN empresas as d ON (c.empresa_id = d.empresa_id)
					LEFT JOIN bodegas as e ON (a.farmacia_id = e.empresa_id)
					AND (a.centro_utilidad = e.centro_utilidad)
					AND (a.bodega = e.bodega)
					LEFT JOIN centros_utilidad as f ON (e.empresa_id = f.empresa_id)
					AND (e.centro_utilidad = f.centro_utilidad)
					LEFT JOIN empresas as g ON (f.empresa_id = g.empresa_id)
					LEFT JOIN inv_bodegas_movimiento as h ON (b.empresa_id = h.empresa_id)
					AND (b.prefijo = h.prefijo)
					AND (b.numero = h.numero)
					LEFT JOIN inv_bodegas_movimiento as i ON (c.empresa_id = i.empresa_id)
					AND (c.prefijo = i.prefijo)
					AND (c.numero = i.numero)
					LEFT JOIN system_usuarios as u ON (i.usuario_id = u.usuario_id)
					WHERE TRUE
					AND a.empresa_destino = '".trim($empresa['empresa'])."' ";
		if($filtros['farmacia'])
		$sql .= "AND   g.razon_social ||':'||f.descripcion||'-'||e.descripcion ILIKE '%".$filtros['farmacia']."%' ";
		if($filtros['fecha_inicio'])
		$sql .= "AND   i.fecha_registro::date >= '".$this->DividirFecha($filtros['fecha_inicio'])."'::date ";
		if($filtros['solicitud_prod_a_bod_ppal_id'])
		$sql .= "AND   a.solicitud_prod_a_bod_ppal_id = '".$filtros['solicitud_prod_a_bod_ppal_id']."' ";
		if($filtros['prefijo'])
		$sql .= "AND   b.prefijo = '".$filtros['prefijo']."' ";
		if($filtros['numero'])
		$sql .= "AND   b.numero = '".$filtros['numero']."' ";
		if($filtros['solicitud_prod_a_bod_ppal_id'])
		$sql .= "AND   a.solicitud_prod_a_bod_ppal_id = '".$filtros['solicitud_prod_a_bod_ppal_id']."' ";
		if($filtros['fecha_final'])
		$sql .= "AND   i.fecha_registro::date <= '".$this->DividirFecha($filtros['fecha_final'])."'::date ";
		
		if($opcion == 1)
		{
		$cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
		$this->ProcesarSqlConteo($cont,$offset);

		$sql .= " ORDER BY a.solicitud_prod_a_bod_ppal_id ";
		$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
		}
		else
			{
		$sql .= " ORDER BY a.solicitud_prod_a_bod_ppal_id ";
			}
		
		if(!$rst = $this->ConexionBaseDatos($sql))
		return false;

		$datos = array();
		while(!$rst->EOF)
		{
		$datos[$rst->fields[0]] [$rst->fields[1]] [$rst->fields[2]] [$rst->fields[4]]  = $rst->GetRowAssoc($ToUpper = false);
		$rst->MoveNext();
		}

		$rst->Close();
		return $datos;
		}
		
		/**
		* Funcion donde se obtiene el nombre de un usuario
		*
		* @param int $usuario Identificacion del usuario
		*
		* @return mixed
		*/
		function ObtenerPrefijosDespachosFarmacias($empresa_id)
		{
			$sql .= "SELECT DISTINCT
			a.prefijo
			FROM
			inv_bodegas_movimiento_despachos_farmacias as a
			WHERE empresa_id = '".trim($empresa_id)."' ";		
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[]  = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}

  }
?>