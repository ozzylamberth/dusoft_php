<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ListaReportes.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina
  */
  /**
  * Clase: ListaReportes
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina
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
			$sql  = "SELECT	E.empresa_id AS empresa, ";
			$sql .= "				E.razon_social AS razon_social, ";
			$sql .= "				E.tipo_id_tercero,  ";
			$sql .= "				E.id  ";
			$sql .= "FROM	  userpermisos_reportes_gral G,";
      $sql .= "       empresas E ";
			$sql .= "WHERE	G.usuario_id = ".$usuario." ";
			$sql .= "AND	  G.empresa_id = E.empresa_id";

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

    function ObtenerEsm()
		{			
			$sql  = "select
                a.tipo_id_tercero,
                a.tercero_id,
                b.nombre_tercero
                from
                esm_empresas as a 
                JOIN terceros as b ON (a.tipo_id_tercero = b.tipo_id_tercero) 
                and (a.tercero_id = b.tercero_id)
                order by b.nombre_tercero ASC
                ";

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
    
      /**
    * Funcion donde se obtiene el listado de productos sin movimiento
    *
    * @param string $empresa Identificador de la empresa
    * @param array $filtros Arreglo con los filtros para la busqueda de la nota
    *
    * @return mixed
    */
    function Obtener_Reporte($empresa,$filtros,$offset,$opcion = 1)
    {
      //$this->debug=true;
      //print_r($filtros);
      list($tipo_id_tercero,$tercero_id) = explode("@",$filtros['esm_empresa']);
      if(!empty($filtros['esm_empresa']))
      {
      $wh = "
                    and d.tipo_id_tercero = '".$tipo_id_tercero."'
                    and  d.tercero_id = '".$tercero_id."' ";
      }
      
        $sql  = "select d.tipo_id_tercero, 
        d.tercero_id,
        e.nombre_tercero,
        b.empresa_id,
        b.centro_utilidad,
        b.bodega,
        f.centro_utilidad_destino,
        f.bodega_destino,
        g.campo_serial,
        SUM(c.cantidad)as total,
        c.codigo_producto,
        fc_descripcion_producto(c.codigo_producto) as producto,
        SUM(c.cantidad/((extract(month from age('2010-12-31'::date ,'2010-11-01'::date))+1))) as prom
        from
        ".$filtros['radio']." as a
        JOIN inv_bodegas_movimiento as b ON (a.empresa_id = b.empresa_id) 
        and (a.prefijo = b.prefijo) 
        and (a.numero = b.numero)
        JOIN inv_bodegas_movimiento_d as c ON (b.empresa_id = c.empresa_id)
        and (b.prefijo = c.prefijo)
        and (b.numero = c.numero)
        JOIN esm_orden_requisicion as d ON (a.orden_requisicion_id = d.orden_requisicion_id)
        JOIN terceros as e ON (d.tipo_id_tercero = e.tipo_id_tercero) and (d.tercero_id = e.tercero_id)
        LEFT JOIN inv_bodegas_movimiento_traslados as f ON
        (a.empresa_id = f.empresa_id) and 
        (a.prefijo = f.prefijo) and 
        (a.numero = f.numero)
        LEFT JOIN esm_pre_ordenes_requisicion_tmp as g ON 
        (g.tipo_id_tercero=d.tipo_id_tercero)and
        (d.tercero_id = g.tercero_id)and
        (b.empresa_id = g.empresa_id_registro) and
        (c.codigo_producto = g.codigo_producto)and
        (COALESCE(f.centro_utilidad_destino,'')=COALESCE(g.centro_utilidad,'')) and
        (COALESCE(f.bodega_destino,'')=COALESCE(g.bodega,''))
          WHERE
          b.sw_estado = '1'
          ".$wh."
          ";
      if($filtros['fecha_inicio'])
        $sql .= "AND   b.fecha_registro::date >= '".$this->DividirFecha($filtros['fecha_inicio'])."'::date ";
      
      if($filtros['fecha_final'])
        $sql .= "AND   b.fecha_registro::date <= '".$this->DividirFecha($filtros['fecha_final'])."'::date ";
      /*
      --and d.tipo_id_tercero||''||d.tercero_id||''||b.empresa_id||''||c.codigo_producto||''||COALESCE(f.centro_utilidad_destino,'')||''||COALESCE(f.bodega_destino,'') NOT IN ( select tipo_id_tercero||''||tercero_id||''||empresa_id_registro||''||codigo_producto||''||COALESCE(centro_utilidad,'')||''||COALESCE(bodega,'')
                                                                                                                                                        from
                                                                                                                                                        esm_pre_ordenes_requisicion_tmp
                                                                                                                                                        )
      */
      if($opcion == 1)
      {
        $sql .= " group by c.codigo_producto,
        d.tipo_id_tercero,d.tercero_id,e.nombre_tercero,b.empresa_id,
        b.centro_utilidad,g.campo_serial,
        b.bodega,f.centro_utilidad_destino,f.bodega_destino ";
        $cont  = "SELECT COUNT(*) FROM (".$sql.")as A  ";
        $this->ProcesarSqlConteo($cont,$offset);
				$sql .= "ORDER BY d.tercero_id,c.codigo_producto  ASC ";
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      }
      else
        {
        $sql .= " group by c.codigo_producto,
        d.tipo_id_tercero,d.tercero_id,e.nombre_tercero,b.empresa_id,
        b.centro_utilidad,g.campo_serial,
        b.bodega,f.centro_utilidad_destino,f.bodega_destino ";
      $sql .= "ORDER BY d.tercero_id,c.codigo_producto  ASC ";
        }
      
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
		* Funcion donde se obtiene la Agrupacion de Farmacias del temporal
		*
    * @param 
		*
    * @return mixed
    */
		function Agrupacion_Empresas()
		{
      $sql .= "select 
      a.empresa_id_registro,
      b.razon_social
      from
      esm_pre_ordenes_requisicion_tmp as a
      JOIN empresas as b ON (a.empresa_id_registro = b.empresa_id)
      group by a.empresa_id_registro,b.razon_social ";		
			
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
		* Funcion donde se obtiene las Esms Por Farmacia
		*
    * @param string $empresa_id
		*
    * @return mixed
    */
		function ESM_PorFarmacia_Distribucion($empresa_id)
		{
      $sql .= "select 
      a.tipo_id_tercero,
      a.tercero_id,
      b.nombre_tercero,
      a.empresa_id,
      a.centro_utilidad,
      a.bodega
      from
      esm_pre_ordenes_requisicion_tmp as a
      JOIN terceros as b ON (a.tipo_id_tercero = b.tipo_id_tercero)
      and(a.tercero_id = b.tercero_id)
      where
      a.empresa_id_registro = '".$empresa_id."'
      and a.empresa_id IS NULL
      group by a.tipo_id_tercero,
      a.tercero_id,
      b.nombre_tercero,
      a.empresa_id,
      a.centro_utilidad,
      a.bodega
      order by a.empresa_id DESC
      
      ";		
			//$this->debug=true;
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
		* Funcion donde se obtiene las Esms Por Farmacia
		*
    * @param string $empresa_id
		*
    * @return mixed
    */
		function ESM_PorFarmacia_Suministro($empresa_id)
		{
      $sql .= "select 
      a.tipo_id_tercero,
      a.tercero_id,
      b.nombre_tercero,
      a.empresa_id,
      a.centro_utilidad,
      a.bodega,
      c.descripcion
      from
      esm_pre_ordenes_requisicion_tmp as a
      JOIN terceros as b ON (a.tipo_id_tercero = b.tipo_id_tercero)
      and(a.tercero_id = b.tercero_id)
      JOIN bodegas as c ON (a.empresa_id = c.empresa_id)and(a.centro_utilidad = c.centro_utilidad)and(a.bodega = c.bodega)
      where
      a.empresa_id_registro = '".$empresa_id."'
      and a.empresa_id IS NOT NULL
      group by a.tipo_id_tercero,
      a.tercero_id,
      b.nombre_tercero,
      a.empresa_id,
      a.centro_utilidad,
      a.bodega,
      c.descripcion
      order by a.empresa_id DESC
      
      ";		
			//$this->debug=true;
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
		* Funcion donde se obtiene las Esms Por Farmacia
		*
    * @param string $empresa_id
		*
    * @return mixed
    */
		function ESM_ProductosDistribucion($empresa_id,$tipo_id_tercero,$tercero_id)
		{
      $sql .= "select 
      a.campo_serial,
      a.codigo_producto,
      fc_descripcion_producto(a.codigo_producto)as descripcion,
      fc_codigo_mindefensa(a.codigo_producto)as codigo_mindefensa,
      a.cantidad_solicitada
      from
      esm_pre_ordenes_requisicion_tmp as a
       where
      a.empresa_id_registro = '".$empresa_id."'
      and a.tipo_id_tercero = '".$tipo_id_tercero."'
      and a.tercero_id = '".$tercero_id."'
      and a.empresa_id IS NULL
      
      order by a.empresa_id DESC
      
      ";		
			//$this->debug=true;
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
		* Funcion donde se obtiene las Esms Por Farmacia
		*
    * @param string $empresa_id
		*
    * @return mixed
    */
		function ESM_ProductosSuministro($empresa_id,$tipo_id_tercero,$tercero_id,$centro_utilidad,$bodega)
		{
      $sql .= "select 
      a.campo_serial,
      a.codigo_producto,
      fc_descripcion_producto(a.codigo_producto)as descripcion,
      fc_codigo_mindefensa(a.codigo_producto)as codigo_mindefensa,
      a.cantidad_solicitada
      from
      esm_pre_ordenes_requisicion_tmp as a
       where
      a.empresa_id_registro = '".$empresa_id."'
      and a.tipo_id_tercero = '".$tipo_id_tercero."'
      and a.tercero_id = '".$tercero_id."'
      and a.empresa_id = '".$empresa_id."'
      and a.centro_utilidad = '".$centro_utilidad."'
      and a.bodega = '".$bodega."'
      
      order by a.empresa_id DESC
      
      ";		
			//$this->debug=true;
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
    
    
    function Insertar_PreOrdenRequisicion($tipo_id_tercero,$tercero_id,$codigo_producto,
                                          $empresa_id,$centro_utilidad_destino,$bodega_destino,$pedido)
		{
		if($centro_utilidad_destino != "" && $bodega_destino!="")
    {
    $campos = " ,empresa_id ";
    $campos .= ",centro_utilidad ";
    $campos .= ",bodega ";
    
    $valor = " ,'".$empresa_id."' ";
    $valor .= " ,'".$centro_utilidad_destino."' ";
    $valor .= " ,'".$bodega_destino."' ";
    }
    
    $sql  = "INSERT INTO esm_pre_ordenes_requisicion_tmp (";
		$sql .= "       empresa_id_registro, ";
		$sql .= "       tipo_id_tercero, ";
		$sql .= "       tercero_id, ";
		$sql .= "       usuario_id, ";
		$sql .= "       codigo_producto, ";
		$sql .= "       cantidad_solicitada ";
		$sql .= " ".$campos;
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        '".$empresa_id."', ";
		$sql .= "        '".$tipo_id_tercero."', ";
		$sql .= "        '".$tercero_id."', ";
		$sql .= "        ".UserGetUID().", ";
		$sql .= "        '".$codigo_producto."', ";
		$sql .= "        ".$pedido." ";
    $sql .= "".$valor;
		$sql .= "       ); ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		
    $rst->Close();
		return true;
  	}
    
    /**
		* Funcion donde se obtiene las Esms Por Farmacia
		*
    * @param string $empresa_id
		*
    * @return mixed
    */
		function Tipos_Requisicion($opc)
		{
      $sql .= "select
      a.tipo_orden_requisicion,
      a.descripcion_orden_requisicion,
      a.movimiento
      from
      esm_tipos_ordenes_requisicion as a
      where
      a.sw_estado = '1'
      and a.movimiento = '".$opc."'
      order by a.descripcion_orden_requisicion
      ";		
			//$this->debug=true;
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
		* Funcion donde se obtiene ltipos de Fuerzas
		*
    * @param string $empresa_id
		*
    * @return mixed
    */
		function Tipos_Fuerzas()
		{
        $sql .= "select
        a.tipo_fuerza_id,
        a.codigo_fuerza,
        a.descripcion
        from
        esm_tipos_fuerzas as a
        where
        a.sw_activo = '1'
        order by a.descripcion
      ";		
			//$this->debug=true;
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
    
    
    function Guardar_RequisicionTemporal($DATOS)
		{
		if($DATOS['empresa_id'] != "" && $DATOS['centro_utilidad']!="")
    {
    $campos = " ,empresa_id ";
    $campos .= ",centro_utilidad ";
    $campos .= ",bodega ";
    
    $valor = " ,'".$DATOS['empresa_id']."' ";
    $valor .= " ,'".$DATOS['centro_utilidad']."' ";
    $valor .= " ,'".$DATOS['bodega']."' ";
    }
    
    $sql  = "INSERT INTO esm_orden_requisicion_tmp (";
		$sql .= "       orden_requisicion_tmp_id, ";
		$sql .= "       empresa_id_registro, ";
		$sql .= "       tipo_id_tercero, ";
		$sql .= "       tercero_id, ";
		$sql .= "       usuario_id, ";
		$sql .= "       tipo_fuerza_id, ";
		$sql .= "       tipo_orden_requisicion, ";
		$sql .= "       observacion ";
		$sql .= " ".$campos;
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        default, ";
		$sql .= "        '".$DATOS['empresa_id_registro']."', ";
		$sql .= "        '".$DATOS['tipo_id_tercero']."', ";
		$sql .= "        '".$DATOS['tercero_id']."', ";
		$sql .= "        ".UserGetUID().", ";
		$sql .= "        ".$DATOS['tipo_fuerza_id'].", ";
		$sql .= "        ".$DATOS['tipo_orden_requisicion'].", ";
		$sql .= "        '".$DATOS['observacion']."' ";
		$sql .= "".$valor;
		$sql .= "       )RETURNING(orden_requisicion_tmp_id); ";			
		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
        {
        $datos = array(); //Definiendo que va a ser un arreglo.
    			
    			while(!$rst->EOF) //Recorriendo el Vector;
    			{
    				$datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
    				$rst->MoveNext();
    			}
    			$rst->Close();
    		}
    $sql ="";
    for($i=0;$i<$DATOS['registros'];$i++)
    {
    $sql .= "INSERT INTO esm_orden_requisicion_tmp_d (";
		$sql .= "       orden_requisicion_tmp_id, ";
		$sql .= "       codigo_producto, ";
		$sql .= "       cantidad_solicitada ";
		$sql .= "                                         ) ";
		$sql .= "VALUES ( ";
		$sql .= "        ".$datos['orden_requisicion_tmp_id'].", ";
		$sql .= "        '".$DATOS['codigo_producto'][$i]."', ";
		$sql .= "        ".$DATOS['cantidad_solicitada'][$i]." ";
		$sql .= "       ); ";	
    $sql .= "Delete 
    from 
    esm_pre_ordenes_requisicion_tmp
    where
    campo_serial=".$DATOS['campo_serial'][$i]." ";
		$sql .= "       ; ";			
		} 
        //print_r($sql);
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;

    $rst->Close();
		return $datos;
  	}
    
    
    function Eliminar_Item($campo_serial)
		{
		    
    $sql  = "Delete 
    from 
    esm_pre_ordenes_requisicion_tmp
    where
    campo_serial=".$campo_serial."; ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		
    $rst->Close();
		return true;
  	}

  }
?>