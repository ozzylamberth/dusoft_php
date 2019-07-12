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
    function Obtener_ReporteDetalle($empresa,$filtros,$offset,$opcion = 1)
    {
 if($filtros['pedido_id']!="")
       $filtro .= " and  s.solicitud_prod_a_bod_ppal_id = ".$filtros['pedido_id']."";
/* $this->debug=true;*/
  $sql  = "
        select      
            t.codigo_producto,
            fc_descripcion_producto(t.codigo_producto) as producto,
            t.solicitud_prod_a_bod_ppal_id,
            t.solicitud_prod_a_bod_ppal_det_id,
             t.cantidad_pendiente,
             t.cantidad_solicitada,
             t.farmacia_id,
             f.razon_social,
             t.usuario_id,
             u.usuario,
             t.tabla
            from
              (
            SELECT 
                      sd.codigo_producto,
                      sd.solicitud_prod_a_bod_ppal_id,
                      sd.solicitud_prod_a_bod_ppal_det_id,
                      sd.cantidad_solic as cantidad_pendiente,
                      sd.cantidad_solic as cantidad_solicitada,
                      sd.farmacia_id,
                      s.usuario_id,
                      'solicitud_productos_a_bodega_principal_detalle' as tabla
                      from
                      solicitud_productos_a_bodega_principal_detalle sd 
                      JOIN solicitud_productos_a_bodega_principal s ON (sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id)
                      where
                            TRUE
                      and   s.sw_despacho = '0'
                      $filtro
                      UNION     
                      SELECT 
                      sd.codigo_producto,
                      sd.solicitud_prod_a_bod_ppal_id,
                      sd.solicitud_prod_a_bod_ppal_det_id,
                      ips.cantidad_pendiente,
                      ips.cantidad_solicitad as cantidad_solicitada,
                      ips.farmacia_id,
                      s.usuario_id,
                      'inv_mov_pendientes_solicitudes_frm' as tabla
                      from
                      solicitud_productos_a_bodega_principal_detalle sd
                      JOIN solicitud_productos_a_bodega_principal s ON (sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id)
                      JOIN inv_mov_pendientes_solicitudes_frm ips ON (sd.solicitud_prod_a_bod_ppal_det_id = ips.solicitud_prod_a_bod_ppal_det_id)
                           and (sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id)
                      where
                            TRUE
                      and   s.sw_despacho = '1'
                       $filtro
                 )as t,
                 empresas as f,
                 system_usuarios u 
                 where
                          f.empresa_id = t.farmacia_id
                      AND u.usuario_id = t.usuario_id ";
       
        $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
        $this->ProcesarSqlConteo($cont,$offset);
				$sql .= "ORDER BY t.solicitud_prod_a_bod_ppal_id  ASC ";
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      
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
    
 
   /***************************************************************************
   *Funcion consultar reserva pedidos eliminados
   ***************************************************************************/
    function Obtener_DetalleEliminados($offset)
	{
	 $sql  = " SELECT pedido_id, farmacia, usuario_solicitud, ";
	 $sql .= "               codigo_producto, cant_solicita, cant_pendiente, ";
	 $sql .= "               fc_descripcion_producto(codigo_producto) AS descripcion, usuario_id,usuario_ejecuta, fecha_registro ";
	 $sql .= "    FROM log_eliminacion_pedidos_farmacia ";
	
     $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
	 $this->ProcesarSqlConteo($cont,$offset);
	 $sql .= "ORDER BY 1 ASC ";
     $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
     
	 if(!$rst = $this->ConexionBaseDatos($sql)) 
	    return false;

	 $datos = array();
	 while (!$rst->EOF)
	 {
		$datos[ ] = $rst->GetRowAssoc($ToUpper = false);
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
      /*$this->debug=true;*/
      //print_r($filtros);
            
      $sql  = "
        select
        d.empresa_id,
        d.razon_social||'-'||b.descripcion as razon_social,
        a.solicitud_prod_a_bod_ppal_id,
        a.farmacia_id,
        a.centro_utilidad,
        a.bodega,
        a.usuario_id,
		a.fecha_registro::date as fecha,
        e.nombre 
        from
        solicitud_productos_a_bodega_principal as a
		JOIN bodegas as b ON (a.farmacia_id = b.empresa_id)
		AND (a.centro_utilidad = b.centro_utilidad)
		AND (a.bodega = b.bodega)
		JOIN centros_utilidad as c ON (b.empresa_id = c.empresa_id)
		AND (b.centro_utilidad = c.centro_utilidad)
        JOIN empresas as d ON (c.empresa_id = d.empresa_id)
        JOIN system_usuarios as e ON (a.usuario_id = e.usuario_id) ";
		
      if($filtros['pedido_id']!="")
        $sql .= "where  a.solicitud_prod_a_bod_ppal_id >= ".$filtros['pedido_id']."";
	   
      $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
      $this->ProcesarSqlConteo($cont,$offset);
	  $sql .= "ORDER BY a.solicitud_prod_a_bod_ppal_id  ASC ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      
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
    

	/*Obtener reporte gral pedidos*/
    function Obtener_ReporteGral($pedido)
    {
            
      $sql  = "
        select
        d.empresa_id,
        d.razon_social||'-'||b.descripcion as razon_social,
        a.solicitud_prod_a_bod_ppal_id,
        a.farmacia_id,
        a.centro_utilidad,
        a.bodega,
        a.usuario_id,
		a.fecha_registro::date as fecha,
        e.nombre 
        from
        solicitud_productos_a_bodega_principal as a
		JOIN bodegas as b ON (a.farmacia_id = b.empresa_id)
		AND (a.centro_utilidad = b.centro_utilidad)
		AND (a.bodega = b.bodega)
		JOIN centros_utilidad as c ON (b.empresa_id = c.empresa_id)
		AND (b.centro_utilidad = c.centro_utilidad)
        JOIN empresas as d ON (c.empresa_id = d.empresa_id)
        JOIN system_usuarios as e ON (a.usuario_id = e.usuario_id) ";
		
      $sql .= "WHERE  a.solicitud_prod_a_bod_ppal_id >= ".$pedido."";
	   
	  $sql .= "ORDER BY a.solicitud_prod_a_bod_ppal_id  ASC ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
   	    return false;

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
    
     function Buscar_PedidoEnBodega($solicitud_prod_a_bod_ppal_id,$solicitud_prod_a_bod_ppal_det_id,$tabla)
		{			
			//$this->debug=true;
      $sql  = "select
                *
                from
                inv_bodegas_movimiento_tmp_despachos_farmacias
                where
                solicitud_prod_a_bod_ppal_id = ".$solicitud_prod_a_bod_ppal_id.";";

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
    
     function Borrar($solicitud_prod_a_bod_ppal_id,$solicitud_prod_a_bod_ppal_det_id,$tabla)
		{			
			//$this->debug=true;
      $sql  = "delete from ".$tabla."
                where
                    solicitud_prod_a_bod_ppal_id = ".$solicitud_prod_a_bod_ppal_id."
                and solicitud_prod_a_bod_ppal_det_id = ".$solicitud_prod_a_bod_ppal_det_id.";";
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$rst->Close();
			return true;
		}
         
		 
		/************************************************************************************
		Funcion para registrar el usuario que realiza la eliminacion por reserva 
		de pedido farmacia				                                            19092012
		return boolean 
		************************************************************************************/
		function Registra_Accion_Delete($user,$pedidoId,$farmacia,$usuarioPedido,$codigo,$cant_sol,$cant_pen,$nombre)
		{
		 $sql  = "INSERT INTO log_eliminacion_pedidos_farmacia ";
		 $sql .= "                     ( ";
		 $sql .= "                       log_eliminacion_id, ";
		 $sql .= "                       pedido_id, ";
		 $sql .= "                       farmacia, ";
		 $sql .= "                       usuario_solicitud, ";
		 $sql .= "                       codigo_producto, ";
		 $sql .= "                       cant_solicita, ";
		 $sql .= "                       cant_pendiente, ";
		 $sql .= "                       usuario_id, ";
		 $sql .= "                       fecha_registro, ";
		 $sql .= "                       usuario_ejecuta ";
		 $sql .= "                      ) ";
		 $sql .= "                      VALUES ";
		 $sql .= "                     ( ";
		 $sql .= "                       DEFAULT, ";
		 $sql .= "                       '".$pedidoId."', ";
		 $sql .= "                       '".$farmacia."', ";
		 $sql .= "                       '".$usuarioPedido."', ";
		 $sql .= "                       '".$codigo."', ";
		 $sql .= "                       ".$cant_sol.", ";
		 $sql .= "                       ".$cant_pen.", ";
		 $sql .= "                       ".$user.", ";
		 $sql .= "                       now(), ";
		 $sql .= "                       '".$nombre."' ";
		 $sql .= "                      ) ";
		 
		 if(!$rst = $this->ConexionBaseDatos($sql))
		    return false;
			
		 $rst->Close();
		
		 return true;
		}
		
		/*Obtener nombre de usuario*/
		function GetName($user)
		{
		 $sql  = " SELECT nombre FROM system_usuarios ";
		 $sql .= " WHERE usuario_id = ".$user;
		 
		 if(!$rst = $this->ConexionBaseDatos($sql))
		    return false;
		
         $name = array();
         $name = $rst->GetRowAssoc($ToUpper = false);
         		 
		 $rst->Close();		
		 return $name;
		}

		/*Obtiene permiso a consulta reserva pedidos eliminados*/		
		function GetPermisoConsulta($user)
		{
		 $sql  = " SELECT empresa_id FROM userpermisos_consulta_pedidos_borrados ";
		 $sql .= " WHERE  usuario_id = ".$user;
           
		 if(!$rst = $this->ConexionBaseDatos($sql))
		    return false;
             
         $emp = array();
         while(!$rst->EOF)
		 { 
		  $emp[ ] = $rst->GetRowAssoc($ToUpper = false);
		  $rst->MoveNext();
         }		 
		 $rst->Close();		
		 return $emp;			
		}
		
    }
?>