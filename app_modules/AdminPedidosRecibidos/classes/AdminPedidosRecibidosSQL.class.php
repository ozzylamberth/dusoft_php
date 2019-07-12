<?php
  class AdminPedidosRecibidosSQL extends ConexionBD
  {
    /*********************************
    * Constructor
    *********************************/
    function AdminPedidosRecibidosSQL(){}
		 


    function ObtenerPedidosFarmacia($empresa,/*$farmacia_id,$centro_utilidad,$bodega,*/$pedido_id)
    {
  	  $sql  = "SELECT SD.solicitud_prod_a_bod_ppal_id, ";
      //$sql .= "       DD.numero ";
      $sql .= "       DD.solicitud_prod_a_bod_ppal_det_des_id, ";
      $sql .= "       DD.numero_guia ";
  	  /*$sql .= "       CASE WHEN estado = '0' THEN 'Registrado' ";
  	  $sql .= "       	WHEN estado = '1' THEN 'Separado' ";
  	  $sql .= "       	WHEN estado = '2' THEN 'Auditado' ";
  	  $sql .= "       	WHEN estado = '3' THEN 'En Despacho' ";
  	  $sql .= "       	WHEN estado = '4' THEN 'Despachado' ELSE '' END AS estado ";*/
      $sql .= "FROM   solicitud_productos_a_bodega_principal SD, ";
      $sql .= "       solicitud_productos_a_bodega_principal_detalle_despacho DD ";
      //$sql .= "       inv_bodegas_movimiento_despachos_farmacias DF ";
      $sql .= "WHERE  SD.empresa_destino = '".trim($empresa)."' ";
      /*$sql .= "AND    SD.farmacia_id = '".trim($farmacia_id)."' ";
      $sql .= "AND    SD.centro_utilidad = '".trim($centro_utilidad)."' ";
      $sql .= "AND    SD.bodega = '".trim($bodega)."' ";*/
      $sql .= "AND	  SD.sw_despacho = '1' ";
      $sql .= "AND    SD.estado = '4' ";
      //$sql .= "AND    SD.solicitud_prod_a_bod_ppal_id = DF. solicitud_prod_a_bod_ppal_id ";
      $sql .= "AND    SD.solicitud_prod_a_bod_ppal_id = DD.solicitud_prod_a_bod_ppal_id ";
      $sql .= "AND    NOT EXISTS (SELECT solicitud_prod_a_bod_ppal_det_rec_id 
                      FROM solicitud_productos_a_bodega_principal_detalle_recibido DR
                      WHERE DD.solicitud_prod_a_bod_ppal_det_des_id=DR.solicitud_prod_a_bod_ppal_det_des_id) ";
      if(!empty($pedido_id)) {
      	$sql .= "AND    SD.solicitud_prod_a_bod_ppal_id LIKE '".$pedido_id."%' ";
      }
      $sql .= "UNION DISTINCT ";
      $sql .= "SELECT DISTINCT SD.solicitud_prod_a_bod_ppal_id, ";
      //$sql .= "                DF.numero ";
      $sql .= "       DD.solicitud_prod_a_bod_ppal_det_des_id, ";
      $sql .= "       DD.numero_guia ";
      /*$sql .= "       CASE WHEN estado = '0' THEN 'Registrado' ";
  	  $sql .= "       	WHEN estado = '1' THEN 'Separado' ";
  	  $sql .= "       	WHEN estado = '2' THEN 'Auditado' ";
  	  $sql .= "       	WHEN estado = '3' THEN 'En Despacho' ";
  	  $sql .= "       	WHEN estado = '4' THEN 'Despachado' ELSE '' END AS estado ";*/
      $sql .= "FROM   solicitud_productos_a_bodega_principal SD, ";
      $sql .= "       solicitud_productos_a_bodega_principal_detalle SE, ";
      $sql .= "       inv_mov_pendientes_solicitudes_frm FR, ";
      $sql .= "       solicitud_productos_a_bodega_principal_detalle_despacho DD ";
      //$sql .= "       inv_bodegas_movimiento_despachos_farmacias DF ";
	    $sql .= "WHERE  SD.empresa_destino = '".trim($empresa)."' ";
      /*$sql .= "AND    SD.farmacia_id = '".trim($farmacia_id)."' ";
      $sql .= "AND    SD.centro_utilidad = '".trim($centro_utilidad)."' ";
      $sql .= "AND    SD.bodega = '".trim($bodega)."' ";*/
      $sql .= "AND	  SD.sw_despacho = '1' ";
      $sql .= "AND    SD.estado = '4' ";
      //$sql .= "AND    SD.solicitud_prod_a_bod_ppal_id = DF. solicitud_prod_a_bod_ppal_id ";
      $sql .= "AND    SD.solicitud_prod_a_bod_ppal_id = DD.solicitud_prod_a_bod_ppal_id ";
      $sql .= "AND	  SD.solicitud_prod_a_bod_ppal_id = SE.solicitud_prod_a_bod_ppal_id ";
      $sql .= "AND    SE.solicitud_prod_a_bod_ppal_id = FR.solicitud_prod_a_bod_ppal_id ";
      $sql .= "AND    SE.solicitud_prod_a_bod_ppal_det_id = FR.solicitud_prod_a_bod_ppal_det_id ";
      $sql .= "AND    NOT EXISTS (SELECT solicitud_prod_a_bod_ppal_det_rec_id 
                      FROM solicitud_productos_a_bodega_principal_detalle_recibido DR
                      WHERE DD.solicitud_prod_a_bod_ppal_det_des_id=DR.solicitud_prod_a_bod_ppal_det_des_id) ";
      if(!empty($pedido_id)) {
      	$sql .= "AND    SD.solicitud_prod_a_bod_ppal_id LIKE '".$pedido_id."%' ";
      }
      $sql .= "ORDER BY 1 ";
      /*print_r($sql);*/
      //echo $sql;
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



    function ObtenerDetalleDespachoPedido($pedido_id,$solicitud_prod_a_bod_ppal_det_des_id)
    {
      $sql  = "SELECT solicitud_prod_a_bod_ppal_det_des_id,
                    descripcion,
                    cantidad_cajas, 
                    cantidad_neveras, 
                    temperatura_neveras, 
                    peso, 
                    placa_vehiculo, 
                    numero_guia, 
                    nombre_conductor ";
      $sql .= "FROM   solicitud_productos_a_bodega_principal_detalle_despacho DD,
                      inv_transportadoras TR ";
      $sql .= "WHERE  DD.solicitud_prod_a_bod_ppal_id = '".trim($pedido_id)."' ";
      $sql .= "AND  DD.solicitud_prod_a_bod_ppal_det_des_id = '".trim($solicitud_prod_a_bod_ppal_det_des_id)."' ";
      $sql .= "AND  DD.transportadora_id = TR.transportadora_id ";
      /*print_r($sql);*/
      //echo $sql;
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



    function GuardarDetallesPedidoRecibido($datos)
	  {
  		$sql = " INSERT INTO solicitud_productos_a_bodega_principal_detalle_recibido(
  		solicitud_prod_a_bod_ppal_det_des_id,
  		cantidad_cajas,
      cantidad_neveras,
      temperatura_neveras,
      observacion,
  		usuario_id)
  		VALUES     (
  			'".trim($datos['detalle_despacho_id'])."',
        '".trim($datos['cantidad_cajas_recibidas'])."',
        '".trim($datos['cantidad_neveras_recibidas'])."',
        '".trim($datos['temperatura_recibida'])."',
        '".trim($datos['observacion'])."',
        ".UserGetUID()."
  		)";
      //echo $sql;
  		
  		if(!$resultado = $this->ConexionBaseDatos($sql))
  		{
  		$cad="Operacion Invalida";
  		return false;//$cad;
  		}
  		$resultado->Close();
  		return true;
	  }

  }
?>