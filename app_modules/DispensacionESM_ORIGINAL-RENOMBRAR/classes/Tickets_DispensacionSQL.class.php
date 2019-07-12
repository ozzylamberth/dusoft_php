<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Tickets_DispensacionSQL.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina
  */
  /**
  * Clase: Tickets_DispensacionSQL
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina
  */
  class Tickets_DispensacionSQL extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function Tickets_DispensacionSQL(){}
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
 // $this->debug=true;
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
                            
                            s.empresa_destino = '09'
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
                            s.empresa_destino = '09'
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
    
    
      /**
    * Funcion donde se obtiene el listado de productos sin movimiento
    *
    * @param string $empresa Identificador de la empresa
    * @param array $filtros Arreglo con los filtros para la busqueda de la nota
    *
    * @return mixed
    */
    function Obtener_Reporte($filtros,$offset,$opcion = 1)
    {
     
      if($filtros['nombre_paciente']!="")
        {
        $filtro .= " and b.primer_nombre ||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido ILIKE '%".$filtros['nombre_paciente']."%' ";
        }
      if($filtros['formula_papel']!="")
        {
        $filtro .= " and a.formula_papel = '".$filtros['formula_papel']."' ";
        }
      if($filtros['tipo_id_paciente']!="")
        {
        $filtro .= " and b.tipo_id_paciente = '".$filtros['tipo_id_paciente']."' ";
        }
      if($filtros['paciente_id']!="")
        {
        $filtro .= " and b.paciente_id = '".$filtros['paciente_id']."' ";
        }
      
      $sql  = "
              select
              a.formula_id,
              a.formula_papel,
              a.fecha_formula,
              a.sw_estado,
              a.tipo_formula,
              b.tipo_id_paciente,
              b.paciente_id,
              b.primer_nombre ||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre_paciente
              from 
              esm_formula_externa as a
              JOIN pacientes as b ON (a.tipo_id_paciente = b.tipo_id_paciente)
              and (a.paciente_id = b.paciente_id)
              JOIN system_usuarios as c ON (a.usuario_id = c.usuario_id)
              where
              a.sw_estado IN ('0')";
        $sql .= $filtro;
      
        $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
        $this->ProcesarSqlConteo($cont,$offset);
				$sql .= "ORDER BY a.formula_id  ASC ";
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
		* Funcion donde se obtiene el nombre de un usuario
		*
    * @param int $usuario Identificacion del usuario
		*
    * @return mixed
    */
		function Tipos_Ids()
		{
			$sql .= "SELECT	* ";
			$sql .= "FROM		tipos_id_pacientes "; 
			 //print_r($sql);
      if(!$rst = $this->ConexionBaseDatos($sql))
      return false;
      $datos = array();
      while(!$rst->EOF)
      {
      $datos[] = $rst->GetRowAssoc($ToUpper);
      $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
		}
    
     function Buscar_PedidoEnBodega($solicitud_prod_a_bod_ppal_id,$solicitud_prod_a_bod_ppal_det_id,$tabla)
		{			

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

      function Medicamentos_Dispensados_Esm_x_lote_ESM($formula_id)
      { 

      $fecha_hoy=date('Y-m-d');
      
      $sql = " select
      dd.codigo_producto,
      dd.cantidad as numero_unidades,
      dd.fecha_vencimiento ,
      dd.lote,
      fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
      d.usuario_id,
      sys.nombre,
      sys.descripcion
      FROM
      esm_formulacion_despachos_medicamentos as dc,
      bodegas_documentos as d,
      bodegas_documentos_d AS dd,
      system_usuarios  sys
      WHERE
      dc.bodegas_doc_id = d.bodegas_doc_id
      and        dc.numeracion = d.numeracion
      and        dc.formula_id = ".$formula_id."
      and        d.bodegas_doc_id = dd.bodegas_doc_id
      and        d.numeracion = dd.numeracion
      and       d.usuario_id=sys.usuario_id
      ";
      //print_r($sql);
      if(!$rst = $this->ConexionBaseDatos($sql))
      return false;
      $datos = array();
      while(!$rst->EOF)
      {
      $datos[] = $rst->GetRowAssoc($ToUpper);
      $rst->MoveNext();
      }
      $rst->Close();
      return $datos;

      }
      function Medicamentos_Dispensados_Esm_x_lote($formula_id)
      { 

      $fecha_hoy=date('Y-m-d');
      
      $sql = " select
      dd.codigo_producto,
      dd.cantidad as numero_unidades,
      dd.fecha_vencimiento ,
      dd.lote,
      fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
      fc_descripcion_producto_molecula(dd.codigo_producto) as molecula,
      d.usuario_id,
      sys.nombre,
      sys.descripcion,
      fc_codigo_mindefensa(dd.codigo_producto) as codigo_producto_mini,
      dd.sw_pactado,
      dd.total_costo
      FROM
      esm_formulacion_despachos_medicamentos as dc,
      bodegas_documentos as d,
      bodegas_documentos_d AS dd,
      system_usuarios  sys
      WHERE
      dc.bodegas_doc_id = d.bodegas_doc_id
      and        dc.numeracion = d.numeracion
      and        dc.formula_id = ".$formula_id."
      and        d.bodegas_doc_id = dd.bodegas_doc_id
      and        d.numeracion = dd.numeracion
      and       d.usuario_id=sys.usuario_id
      ";
      //print_r($sql);
      if(!$rst = $this->ConexionBaseDatos($sql))
      return false;
      $datos = array();
      while(!$rst->EOF)
      {
      $datos[] = $rst->GetRowAssoc($ToUpper);
      $rst->MoveNext();
      }
      $rst->Close();
      return $datos;

      }

  }
?>