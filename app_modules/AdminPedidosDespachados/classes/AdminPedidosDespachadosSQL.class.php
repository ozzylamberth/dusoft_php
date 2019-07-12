<?php
  class AdminPedidosDespachadosSQL extends ConexionBD
  {
    /*********************************
    * Constructor
    *********************************/
    function AdminPedidosDespachadosSQL(){}



    function ObtenerPedidoCliente($pedido_cliente_id)
    {
      $sql  = "SELECT VOP.pedido_cliente_id AS pedido_id, ";

      $sql .= "     DC.numero, ";
      $sql .= "     TR.nombre_tercero AS nombre, ";
      //$sql .= "     EM.razon_social, ";
      $sql .= "     DC.venta_orden_pedido_det_des_id AS detalle_despacho_id ";
      
      $sql .= "FROM   ventas_ordenes_pedidos VOP ";
      $sql .= "       LEFT JOIN terceros TR ON (VOP.tercero_id = TR.tercero_id) ";
      $sql .= "         AND (VOP.tipo_id_tercero = TR.tipo_id_tercero) ";
      $sql .= "       LEFT JOIN inv_tipos_bloqueos ITB ON (TR.tipo_bloqueo_id = ITB.tipo_bloqueo_id), ";

      $sql .= "       inv_bodegas_movimiento_despachos_clientes DC ";

      //$sql .= "       terceros TR ";

      
      $sql .= "WHERE  VOP.pedido_cliente_id = '".trim($pedido_cliente_id)."' ";
      $sql .= "AND    VOP.estado_pedido = '4' ";

      $sql .= "AND    VOP.pedido_cliente_id = DC.pedido_cliente_id ";

      //$sql .= "AND    VOP.empresa_id = BD.empresa_id ";

      //$sql .= "AND    VOP.tipo_id_tercero = TR.tipo_id_tercero ";
      //$sql .= "AND    VOP.tercero_id = TR.tercero_id ";

      if(!empty($pedido_id)) {
        $sql .= "AND    VO.pedido_cliente_id = '".trim($pedido_cliente_id)."' ";
      }
      $sql .= "ORDER BY 1 ";
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


    function ObtenerPedidoFarmacia($empresa,$pedido_id)
    {
      $sql  = "SELECT SD.solicitud_prod_a_bod_ppal_id AS pedido_id, ";
      $sql .= "     DF.numero, ";
      $sql .= "     EM.razon_social||' ::: '|| BD.descripcion AS nombre, ";
      /*$sql .= "     BD.descripcion, ";
      $sql .= "     EM.razon_social, ";*/
      $sql .= "     DF.solicitud_prod_a_bod_ppal_det_des_id AS detalle_despacho_id ";
      
      $sql .= "FROM   solicitud_productos_a_bodega_principal SD, "; 
      $sql .= "       inv_bodegas_movimiento_despachos_farmacias DF, ";
      $sql .= "       bodegas BD, ";

      $sql .= "       centros_utilidad CU, ";
      $sql .= "       empresas EM ";
      
      $sql .= "WHERE  SD.empresa_destino = '".trim($empresa)."' ";
      /*$sql .= "AND    SD.farmacia_id = '".trim($farmacia_id)."' ";
      $sql .= "AND    SD.centro_utilidad = '".trim($centro_utilidad)."' ";
      $sql .= "AND    SD.bodega = '".trim($bodega)."' ";*/
      //$sql .= "AND    SD.sw_despacho = '0' ";//DEFAULT
      $sql .= "AND    SD.sw_despacho = '1' ";
      $sql .= "AND    SD.estado = '4' ";
      $sql .= "AND    DF.solicitud_prod_a_bod_ppal_id = SD.solicitud_prod_a_bod_ppal_id ";

      $sql .= "AND    SD.farmacia_id = BD.empresa_id ";
      $sql .= "AND    SD.centro_utilidad = BD.centro_utilidad ";
      $sql .= "AND    SD.bodega = BD.bodega ";

      $sql .= "AND    BD.empresa_id = CU.empresa_id ";
      $sql .= "AND    BD.centro_utilidad = CU.centro_utilidad ";
      $sql .= "AND    CU.empresa_id = EM.empresa_id ";

      if(!empty($pedido_id)) {
        //$sql .= "AND    SD.solicitud_prod_a_bod_ppal_id LIKE '".$pedido_id."%' ";
        $sql .= "AND    SD.solicitud_prod_a_bod_ppal_id = '".$pedido_id."' ";
      }
      /*$sql .= "UNION DISTINCT ";
      $sql .= "SELECT DISTINCT SD.solicitud_prod_a_bod_ppal_id, ";
      $sql .= "     DF.numero, ";
      //$sql .= "     DF.rutaviaje_destinoempresa_id, ";
      $sql .= "     BD.descripcion, ";
      $sql .= "     EM.razon_social, ";
      $sql .= "     DF.solicitud_prod_a_bod_ppal_det_des_id ";
      $sql .= "FROM   solicitud_productos_a_bodega_principal SD, ";
      $sql .= "       solicitud_productos_a_bodega_principal_detalle SE, ";
      $sql .= "       inv_mov_pendientes_solicitudes_frm FR, ";
      $sql .= "       inv_bodegas_movimiento_despachos_farmacias DF, ";
      $sql .= "       bodegas BD, ";

      $sql .= "       centros_utilidad CU, ";
      $sql .= "       empresas EM ";

      $sql .= "WHERE  SD.empresa_destino = '".trim($empresa)."' ";
      $sql .= "AND    SD.farmacia_id = '".trim($farmacia_id)."' ";
      $sql .= "AND    SD.centro_utilidad = '".trim($centro_utilidad)."' ";
      $sql .= "AND    SD.bodega = '".trim($bodega)."' ";
      //$sql .= "AND    SD.sw_despacho = '1' ";//DEFAULT
      $sql .= "AND    SD.sw_despacho = '1' ";
      $sql .= "AND    SD.estado = '4' ";
      $sql .= "AND    DF.solicitud_prod_a_bod_ppal_id = SD.solicitud_prod_a_bod_ppal_id ";

      $sql .= "AND    SD.farmacia_id = BD.empresa_id ";
      $sql .= "AND    SD.centro_utilidad = BD.centro_utilidad ";
      $sql .= "AND    SD.bodega = BD.bodega ";

      $sql .= "AND    BD.centro_utilidad = CU.centro_utilidad ";
      $sql .= "AND    CU.empresa_id = EM.empresa_id ";

      $sql .= "AND    SD.solicitud_prod_a_bod_ppal_id = SE.solicitud_prod_a_bod_ppal_id ";
      $sql .= "AND    SE.solicitud_prod_a_bod_ppal_id = FR.solicitud_prod_a_bod_ppal_id ";
      $sql .= "AND    SE.solicitud_prod_a_bod_ppal_det_id = FR.solicitud_prod_a_bod_ppal_det_id ";
      if(!empty($pedido_id)) {
        $sql .= "AND    SD.solicitud_prod_a_bod_ppal_id LIKE '".$pedido_id."%' ";
      }*/
      $sql .= "ORDER BY 1 ";
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


    function ObtenerTransportadoras()
    {

	    $sql  = "SELECT transportadora_id, descripcion ";
      $sql .= "FROM   inv_transportadoras ";
      $sql .= "ORDER BY descripcion ";
      //echo $sql."<br>";
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



    function crearRegistroDetalleDespachoPedidoCliente($datos)
    {
      $sql = " INSERT INTO ventas_ordenes_pedidos_detalle_despacho(
      venta_orden_pedido_id,
      transportadora_id,
      cantidad_cajas,
      cantidad_neveras,
      temperatura_neveras,
      peso,
      placa_vehiculo,
      numero_guia,
      nombre_conductor,
      usuario_id)
      VALUES     (
        '".trim($datos['pedido_id'])."',
        '".trim($datos['transportadora_id'])."',
        '".trim($datos['cantidad_cajas'])."',
        '".trim($datos['cantidad_neveras'])."',
        '".trim($datos['temperatura'])."',
        '".trim($datos['peso'])."',
        '".trim($datos['placa_vehiculo'])."',
        '".trim($datos['numero_guia'])."',
        '".trim($datos['nombre_conductor'])."',
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



    function crearRegistroDetalleDespachoPedidoFarmacia($datos)
  	{
  		$sql = " INSERT INTO solicitud_productos_a_bodega_principal_detalle_despacho(
  		solicitud_prod_a_bod_ppal_id,
  		transportadora_id,
  		cantidad_cajas,
  		cantidad_neveras,
  		temperatura_neveras,
  		peso,
  		placa_vehiculo,
  		numero_guia,
  		nombre_conductor,
  		usuario_id)
  		VALUES     (
  			'".trim($datos['pedido_id'])."',
  			'".trim($datos['transportadora_id'])."',
  			'".trim($datos['cantidad_cajas'])."',
  			'".trim($datos['cantidad_neveras'])."',
  			'".trim($datos['temperatura'])."',
  			'".trim($datos['peso'])."',
  			'".trim($datos['placa_vehiculo'])."',
  			'".trim($datos['numero_guia'])."',
  			'".trim($datos['nombre_conductor'])."',
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



    function obtenerIdUltimoDetalleDespachosCliente()
    {
      $sql  = "SELECT MAX(venta_orden_pedido_det_des_id) ";
      $sql .= "FROM   ventas_ordenes_pedidos_detalle_despacho ";
      //echo $sql."<br>";
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



	  function obtenerIdUltimoDetalleDespachosFarmacia()
    {
	    $sql  = "SELECT MAX(solicitud_prod_a_bod_ppal_det_des_id) ";
      $sql .= "FROM   solicitud_productos_a_bodega_principal_detalle_despacho ";
      //echo $sql."<br>";
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



    function asignarDetalleDespachosCliente($detalle_despacho_id, $numero)
    {
      $sql = "  UPDATE inv_bodegas_movimiento_despachos_clientes
                    SET venta_orden_pedido_det_des_id = ".$detalle_despacho_id."
                  WHERE numero = '".$numero."' ;";
      //echo $sql."<br><br>";
      if(!$resultado = $this->ConexionBaseDatos($sql))
      {
      $cad="Operacion Invalida";
      return false;//$cad;
      } 
      $resultado->Close();
      return true;
    }



    function asignarDetalleDespachosFarmacia($detalle_despacho_id, $numero)
  	{
  		$sql = "  UPDATE inv_bodegas_movimiento_despachos_farmacias
                	  SET solicitud_prod_a_bod_ppal_det_des_id = ".$detalle_despacho_id."
  	              WHERE numero = '".$numero."' ;";
  		//echo $sql."<br><br>";
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