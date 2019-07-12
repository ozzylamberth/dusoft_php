<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: DocumentosAutomaticos.class.php,v 1.2 2010/04/20 15:38:46 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : DocumentosAutomaticos
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  IncludeClass('ConexionBD');
  IncludeClass("doc_Bodegas_E008","Doc_Mov_Bodegas/E008","app","Inv_MovimientosBodegas");
  class DocumentosAutomaticos extends ConexionBD
  {
  
  var $mensaje;
    /**
    * Constructor de la clase
    */
    function DocumentosAutomaticos(){}
    /**
    *
    */
    function DocumentoPedido($empresa_id,$prefijo,$numero)
    {
      $dcc = new BodegasDocumentosComun();
      $e08 = new doc_bodegas_E008();
      $documento = $dcc->GetInfoBodegaMovimiento($empresa_id,$prefijo,$numero);
	  
      	  
      $info_doc = $this->ObtenerInformacionDocumento($documento['documento_id']);
	  /*print_r($info_doc);*/
      if(!empty($info_doc))
      {
        $pendiente = $this->ObtenerDatosPendientes($empresa_id,$prefijo,$numero);
		
        if(!empty($pendiente))
        {
          $existencias = $this->ObtenerInformacionExistencias($empresa_id,$prefijo,$numero);
          $temporales = $this->ObtenerDocumentos($empresa_id,$prefijo,$numero);
          $bodega_doc_id = ModuloGetVar('app','Inv_MovimientosBodegas','bodegas_doc_id');
          foreach($pendiente as $key => $dtl)
          {
            foreach($dtl as $k1 => $dt1)
            {
              foreach($dt1 as $k2 => $dt2)
              {
                $existencias[$dt2['codigo_producto']]['existencia'] = $existencias[$dt2['codigo_producto']]['existencia'] - $dt2['cantidad_pendiente'];
                if($existencias[$dt2['codigo_producto']]['existencia'] >= $existencias[$dt2['codigo_producto']]['existencia_minima'])
                {
                  if(empty($temporales[$dt2['solicitud_prod_a_bod_ppal_id']]))
                  {
                    /*$datos = $e08->CrearDoc($bodega_doc_id, 'DESPACHO AUTOMATICO POR AUTORIZAR', 1,$dt2['solicitud_prod_a_bod_ppal_id'],'', '', $dt2['empresa_id'],$info_doc['usuario_id']);*/
                    $datos = $e08->CrearDoc($bodega_doc_id, 'DESPACHO AUTOMATICO POR AUTORIZAR', 1,$dt2['solicitud_prod_a_bod_ppal_id'],'', '', $dt2['empresa_id']);
					$documentos_creados[] = $datos;
					
					/*
					Mensaje que Arroja el Modulo
					*/
					$this->mensaje .= $datos['doc_tmp_id'].",";
					
					if(!$datos) return false;
						$temporales[$dt2['solicitud_prod_a_bod_ppal_id']]['doc_tmp_id'] = $datos['doc_tmp_id'];
                  }
                  $rst = $e08->GuardarTemporal($bodega_doc_id,$temporales[$dt2['solicitud_prod_a_bod_ppal_id']]['doc_tmp_id'],$dt2['codigo_producto'],$dt2['cantidad_pendiente'],0,$dt2['costo_producto'],$info_doc['usuario_id'],$dt2['fecha_vencimiento'],$dt2['lote']);
				  if(!$rst) return false;
                }
                else
                {
                  $existencias[$dt2['codigo_producto']]['existencia'] = $existencias[$dt2['codigo_producto']]['existencia'] + $dt2['cantidad_pendiente'];
                }
              }
            }
          }
        }
      }
      return true;
    }
    /**
    *
    */
    function ObtenerInformacionDocumento($documento_id)
    {
      $sql  = "SELECT documento_id,";
      $sql .= " 	    empresa_id,";
      $sql .= " 	    centro_utilidad,";
      $sql .= " 	    bodega 	,";
      $sql .= " 	    bodegas_doc_id 	,";
      $sql .= " 	    sw_estado, ";
      $sql .= " 	    usuario_defecto_despacho AS usuario_id ";
      $sql .= "FROM   inv_bodegas_documentos ";
      $sql .= "WHERE  sw_genera_auto_despacho = '1' ";
      $sql .= "AND    documento_id =".$documento_id." ";
      /*print_r($sql);*/
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }

      $rst->Close();

      return $datos;
    }
    /**
    *
    */
    function ObtenerDatosPendientes($empresa_id,$prefijo,$numero)
    {
     /* $sql  = "SELECT EM.empresa_id, \n";
      $sql .= "       PF.solicitud_prod_a_bod_ppal_id, \n";
      $sql .= "       PF.solicitud_prod_a_bod_ppal_det_id, \n";
      $sql .= "       PF.cantidad_solicitad, \n";
      $sql .= "       PF.cantidad_despachada, \n";
      $sql .= "       PF.cantidad_pendiente , \n";
      $sql .= "       PF.cantidad_pendiente*MD.costo_inventario AS costo_producto, \n";
      $sql .= "       MD.codigo_producto 	, \n";
      $sql .= "       MD.cantidad , \n";
      $sql .= "       MD.porcentaje_gravamen, \n";
      $sql .= "       MD.total_costo, \n";
      $sql .= "       MD.existencia_bodega, \n";
      $sql .= "       MD.existencia_inventario, \n";
      $sql .= "       MD.costo_inventario, \n";
      $sql .= "       MD.fecha_vencimiento, \n";
      $sql .= "       MD.lote \n";
      $sql .= "FROM   inv_mov_pendientes_solicitudes_frm PF, \n";
      $sql .= "       solicitud_productos_a_bodega_principal_detalle SD LEFT JOIN  \n";
      $sql .= "       ( \n";
      $sql .= "         SELECT  DISTINCT TD.codigo_producto, \n";
      $sql .= "                 TM.solicitud_prod_a_bod_ppal_id \n";
      $sql .= "         FROM    inv_bodegas_movimiento_tmp_despachos_farmacias TM, \n";
      $sql .= "                 inv_bodegas_movimiento_tmp_d TD \n";
      $sql .= "         WHERE   TM.doc_tmp_id = TD.doc_tmp_id \n";
      $sql .= "       ) TM \n";
      $sql .= "       ON( TM.solicitud_prod_a_bod_ppal_id = SD.solicitud_prod_a_bod_ppal_id AND \n";
      $sql .= "           SD.codigo_producto = TM.codigo_producto ), \n";
      $sql .= "       inv_bodegas_movimiento_d MD, \n";
      $sql .= "       empresas EM \n";*/
      /*$sql .= "       inv_prioridades_despachos PD ";*/
      /*$sql .= "WHERE  PF.solicitud_prod_a_bod_ppal_id = SD.solicitud_prod_a_bod_ppal_id \n";
      $sql .= "AND    PF.solicitud_prod_a_bod_ppal_det_id = SD.solicitud_prod_a_bod_ppal_det_id \n";
      $sql .= "AND    SD.codigo_producto = MD.codigo_producto \n";
      $sql .= "AND    PF.empresa_id = MD.empresa_id \n";
      $sql .= "AND    MD.empresa_id = '".$empresa_id."' \n";
      $sql .= "AND    MD.prefijo = '".$prefijo."' \n";
      $sql .= "AND    MD.numero =  ".$numero." \n";
      $sql .= "AND    EM.empresa_id = PF.farmacia_id \n";*/
     /* $sql .= "AND    EM.id = PD.tercero_id ";
      $sql .= "AND    EM.tipo_id_tercero = PD.tipo_id_tercero ";*/
      /*$sql .= "AND    TM.codigo_producto IS NULL \n";
      $sql .= " ORDER BY SD.fecha_registro; \n";*/
      /*$sql .= "ORDER BY PD.prioridad ";*/
      $sql = "SELECT
				a.farmacia_id as empresa_id, 
				a.solicitud_prod_a_bod_ppal_id, 
				a.solicitud_prod_a_bod_ppal_det_id, 
				a.cantidad_solicitad, 
				a.cantidad_despachada, 
				a.cantidad_pendiente , 
				a.cantidad_pendiente*c.costo_inventario AS costo_producto, 
				c.codigo_producto 	, 
				c.cantidad , 
				c.porcentaje_gravamen, 
				c.total_costo, 
				c.existencia_bodega, 
				c.existencia_inventario, 
				c.costo_inventario, 
				c.fecha_vencimiento, 
				c.lote 
				FROM
				inv_mov_pendientes_solicitudes_frm as a
				JOIN solicitud_productos_a_bodega_principal_detalle as b ON (a.solicitud_prod_a_bod_ppal_det_id = b.solicitud_prod_a_bod_ppal_det_id)
				JOIN inv_bodegas_movimiento_d as c ON (b.codigo_producto = c.codigo_producto)
				LEFT JOIN (
						  SELECT DISTINCT
						  y.codigo_producto
						  FROM
						  inv_bodegas_movimiento_tmp_despachos_farmacias AS x
						  JOIN inv_bodegas_movimiento_tmp_d as y ON (x.doc_tmp_id = y.doc_tmp_id)
						  AND (x.usuario_id = y.usuario_id)
						  WHERE TRUE
						  AND y.empresa_id = '".trim($empresa_id)."'           
						  ) as d ON (b.codigo_producto = d.codigo_producto)
				WHERE TRUE
				AND c.empresa_id = '".trim($empresa_id)."'
				AND c.prefijo = '".trim($prefijo)."'           
				AND c.numero = '".trim($numero)."'           
				AND d.codigo_producto IS NULL
				ORDER BY b.fecha_registro ASC;";
	 /*print_r($sql);*/
	 
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][$rst->fields[1]][] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }

      $rst->Close();

      return $datos;
    }    
    /**
    *
    */
    function ObtenerDocumentos($empresa_id,$prefijo,$numero)
    {
      $sql  = "SELECT PF.solicitud_prod_a_bod_ppal_id, ";
      $sql .= "       TM.doc_tmp_id ";
      $sql .= "FROM   inv_mov_pendientes_solicitudes_frm PF, ";
      $sql .= "       solicitud_productos_a_bodega_principal_detalle SD,  ";
      $sql .= "       inv_bodegas_movimiento_d MD, ";
      $sql .= "       inv_bodegas_movimiento_tmp_despachos_farmacias TM ";
      $sql .= "WHERE  PF.solicitud_prod_a_bod_ppal_id = SD.solicitud_prod_a_bod_ppal_id ";
      $sql .= "AND    PF.solicitud_prod_a_bod_ppal_det_id = SD.solicitud_prod_a_bod_ppal_det_id ";
      $sql .= "AND    SD.codigo_producto = MD.codigo_producto ";
      $sql .= "AND    PF.empresa_id = MD.empresa_id ";
      $sql .= "AND    MD.empresa_id = '".$empresa_id."' ";
      $sql .= "AND    MD.prefijo = '".$prefijo."' ";
      $sql .= "AND    MD.numero =  ".$numero." ";
      $sql .= "AND    TM.solicitud_prod_a_bod_ppal_id = PF.solicitud_prod_a_bod_ppal_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }

      $rst->Close();

      return $datos;
    }
    /**
    *
    */
    function ObtenerInformacionExistencias($empresa_id,$prefijo,$numero)
    {
      $sql  = "SELECT DISTINCT EM.codigo_producto,";
      $sql .= " 	    EM.bodega,";
      $sql .= " 	    EM.existencia,";
      $sql .= " 	    EM.existencia_minima ";
      $sql .= "FROM   inv_bodegas_movimiento_d MD, ";
      $sql .= "       existencias_bodegas EM ";
      $sql .= "WHERE  MD.empresa_id = '".$empresa_id."' ";
      $sql .= "AND    MD.prefijo = '".$prefijo."' ";
      $sql .= "AND    MD.numero =  ".$numero." ";
      $sql .= "AND    EM.empresa_id = MD.empresa_id ";
      $sql .= "AND    EM.centro_utilidad = MD.centro_utilidad 	 ";
      $sql .= "AND    EM.codigo_producto = MD.codigo_producto 	 ";
      $sql .= "AND    EM.bodega = MD.bodega ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }

      $rst->Close();

      return $datos;
    }
  }
?>