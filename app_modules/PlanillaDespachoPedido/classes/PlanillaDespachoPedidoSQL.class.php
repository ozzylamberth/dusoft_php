<?php
  class PlanillaDespachoPedidoSQL extends ConexionBD
  {
    /*********************************
    * Constructor
    *********************************/
    function PlanillaDespachoPedidoSQL(){}



    function ObtenerDetallesDespachos($empresa)
    {
      $sql  = "SELECT SDD.solicitud_prod_a_bod_ppal_det_des_id AS detalle_despacho_id, ";
      $sql .= "     SDD.solicitud_prod_a_bod_ppal_det_des_id||' - Farmacia ' AS detalle_despacho_id_label, ";
      $sql .= "     SDD.planilla_despacho_id, ";
      $sql .= "     SDD.solicitud_prod_a_bod_ppal_id AS pedido_id, ";
      $sql .= "     SDD.fecha_registro, ";
      $sql .= "     'farmacia' AS destinatario ";
      $sql .= "FROM   solicitud_productos_a_bodega_principal SD, ";
      $sql .= "       solicitud_productos_a_bodega_principal_detalle_despacho SDD, ";
      $sql .= "       bodegas BD, ";
      $sql .= "       centros_utilidad CU, ";
      $sql .= "       empresas EM ";
      $sql .= "WHERE  SD.empresa_destino = '".trim($empresa)."' ";
      $sql .= "AND    SD.sw_despacho = '1' ";
      $sql .= "AND    SD.estado = '4' ";
      $sql .= "AND    SDD.solicitud_prod_a_bod_ppal_id = SD.solicitud_prod_a_bod_ppal_id ";
      $sql .= "AND    SD.farmacia_id = BD.empresa_id ";
      $sql .= "AND    SD.centro_utilidad = BD.centro_utilidad ";
      $sql .= "AND    SD.bodega = BD.bodega ";
      $sql .= "AND    BD.empresa_id = CU.empresa_id ";
      $sql .= "AND    BD.centro_utilidad = CU.centro_utilidad ";
      $sql .= "AND    CU.empresa_id = EM.empresa_id ";
      $sql .= "AND    SDD.planilla_despacho_id IS NULL ";
      //$sql .= "ORDER BY 1 ";

      $sql .= " UNION DISTINCT ";

      $sql .= "SELECT VDD.venta_orden_pedido_det_des_id AS detalle_despacho_id, ";
      $sql .= "     VDD.venta_orden_pedido_det_des_id||' - Cliente ' AS detalle_despacho_id_label, ";
      $sql .= "     VDD.planilla_despacho_id, ";
      $sql .= "     VDD.venta_orden_pedido_id AS pedido_id, ";
      $sql .= "     VDD.fecha_registro, ";
      $sql .= "     'cliente' AS destinatario ";
      $sql .= "FROM   ventas_ordenes_pedidos VOP, ";
      $sql .= "       ventas_ordenes_pedidos_detalle_despacho VDD ";
      $sql .= "WHERE  VOP.empresa_id = '".trim($empresa)."' ";
      $sql .= "AND    VOP.estado = '3' ";
      $sql .= "AND    VOP.estado_pedido = '4' ";
      $sql .= "AND    VDD.venta_orden_pedido_id = VOP.pedido_cliente_id ";
      $sql .= "AND    VDD.planilla_despacho_id IS NULL ";
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


    function ObtenerPlanillasDespachos($empresa,$planilla_despacho_id)
    {
      $sql  = "SELECT DISTINCT ON (planilla_despacho_id) ";
      $sql .= "planilla_despacho_id ";
      $sql .= "FROM (SELECT DISTINCT ON (SDD.planilla_despacho_id) ";
      $sql .= "     SDD.planilla_despacho_id ";
      $sql .= "FROM   solicitud_productos_a_bodega_principal SD, ";
      $sql .= "       solicitud_productos_a_bodega_principal_detalle_despacho SDD, ";
      $sql .= "       bodegas BD, ";
      $sql .= "       centros_utilidad CU, ";
      $sql .= "       empresas EM ";
      $sql .= "WHERE  SD.empresa_destino = '".trim($empresa)."' ";
      $sql .= "AND    SD.sw_despacho = '1' ";
      $sql .= "AND    SD.estado = '4' ";
      $sql .= "AND    SDD.solicitud_prod_a_bod_ppal_id = SD.solicitud_prod_a_bod_ppal_id ";
      $sql .= "AND    SD.farmacia_id = BD.empresa_id ";
      $sql .= "AND    SD.centro_utilidad = BD.centro_utilidad ";
      $sql .= "AND    SD.bodega = BD.bodega ";
      $sql .= "AND    BD.empresa_id = CU.empresa_id ";
      $sql .= "AND    BD.centro_utilidad = CU.centro_utilidad ";
      $sql .= "AND    CU.empresa_id = EM.empresa_id ";
      $sql .= "AND    SDD.planilla_despacho_id = '".$planilla_despacho_id."' ";
      //$sql .= "ORDER BY 1 ";

      $sql .= " UNION DISTINCT ";

      $sql .= "SELECT DISTINCT ON (VDD.planilla_despacho_id) ";
      $sql .= "     VDD.planilla_despacho_id ";
      $sql .= "FROM   ventas_ordenes_pedidos VOP, ";
      $sql .= "       ventas_ordenes_pedidos_detalle_despacho VDD ";
      $sql .= "WHERE  VOP.empresa_id = '".trim($empresa)."' ";
      $sql .= "AND    VOP.estado = '3' ";
      $sql .= "AND    VOP.estado_pedido = '4' ";
      $sql .= "AND    VDD.venta_orden_pedido_id = VOP.pedido_cliente_id ";
      $sql .= "AND    VDD.planilla_despacho_id = '".$planilla_despacho_id."' ";
      $sql .= ") AS planillas_despachos ";
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


    function crearPlanillaDespachos($empresa_id)
  	{
  		$sql = "INSERT INTO planillas_despachos(
          		  usuario_id)
          		VALUES     (
          			".UserGetUID()."
          		)";
  		//echo $sql."<br>";
  		if(!$resultado = $this->ConexionBaseDatos($sql))
  		{
  		$cad="Operacion Invalida";
  		return false;//$cad;
  		} 
  		$resultado->Close();
  		return true;
  	}



	  function obtenerIdUltimoPlanillaDespachos()
    {
	    $sql  = "SELECT MAX(planilla_despacho_id) ";
      $sql .= "FROM   planillas_despachos ";
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



    function asignarPlanillaDespachosFarmacias($planilla_despacho_id, $detalle_despacho_id)
  	{
  		$sql = "UPDATE solicitud_productos_a_bodega_principal_detalle_despacho
              SET planilla_despacho_id = ".$planilla_despacho_id."
  	          WHERE solicitud_prod_a_bod_ppal_det_des_id = '".$detalle_despacho_id."' ;";
  		//echo $sql."<br><br>";
  		if(!$resultado = $this->ConexionBaseDatos($sql))
  		{
  		$cad="Operacion Invalida";
  		return false;
  		} 
  		$resultado->Close();
  		return true;
  	}



    function asignarPlanillaDespachosClientes($planilla_despacho_id, $detalle_despacho_id)
    {
      $sql = "UPDATE ventas_ordenes_pedidos_detalle_despacho
              SET planilla_despacho_id = ".$planilla_despacho_id."
              WHERE venta_orden_pedido_det_des_id = '".$detalle_despacho_id."' ;";
      //echo $sql."<br><br>";
      if(!$resultado = $this->ConexionBaseDatos($sql))
      {
      $cad="Operacion Invalida";
      return false;
      } 
      $resultado->Close();
      return true;
    }



    function obtenerDetallesDespachosPlanillaDespachos($planilla_despacho_id)
    {
      $sql  = "SELECT 'farmacia' AS destinatario,
                      SDD.solicitud_prod_a_bod_ppal_det_des_id AS pedido_id, 
                      SDD.placa_vehiculo, 
                      SDD.cantidad_cajas, 
                      SDD.cantidad_neveras, 
                      SDD.temperatura_neveras, 
                      (SELECT descripcion
                       FROM inv_transportadoras TR
                       WHERE TR.transportadora_id = SDD.transportadora_id) AS transportadora, 
                      SDD.numero_guia, 
                      (SELECT EM.razon_social ||' ::: '|| BD.descripcion 
                       FROM solicitud_productos_a_bodega_principal SD, 
                            bodegas BD, 
                            centros_utilidad CU, 
                            empresas EM
                       WHERE SD.solicitud_prod_a_bod_ppal_id = SDD.solicitud_prod_a_bod_ppal_id 
                        AND SD.farmacia_id = BD.empresa_id 
                        AND SD.centro_utilidad = BD.centro_utilidad 
                        AND SD.bodega = BD.bodega
                        AND BD.empresa_id = CU.empresa_id 
                        AND BD.centro_utilidad = CU.centro_utilidad
                        AND CU.empresa_id = EM.empresa_id) AS empresa, 
                      (SELECT CU.ubicacion 
                       FROM solicitud_productos_a_bodega_principal SD, 
                            bodegas BD, 
                            centros_utilidad CU
                       WHERE SD.solicitud_prod_a_bod_ppal_id = SDD.solicitud_prod_a_bod_ppal_id 
                        AND SD.farmacia_id = BD.empresa_id 
                        AND SD.centro_utilidad = BD.centro_utilidad 
                        AND SD.bodega = BD.bodega
                        AND BD.empresa_id = CU.empresa_id 
                        AND BD.centro_utilidad = CU.centro_utilidad) AS direccion, 
                      (SELECT TM.municipio
                       FROM solicitud_productos_a_bodega_principal SD, 
                            bodegas BD, 
                            centros_utilidad CU, 
                            tipo_mpios TM 
                       WHERE SD.solicitud_prod_a_bod_ppal_id = SDD.solicitud_prod_a_bod_ppal_id 
                        AND SD.farmacia_id = BD.empresa_id 
                        AND SD.centro_utilidad = BD.centro_utilidad 
                        AND SD.bodega = BD.bodega 
                        AND BD.empresa_id = CU.empresa_id 
                        AND BD.centro_utilidad = CU.centro_utilidad 
                        AND CU.tipo_pais_id = TM.tipo_pais_id 
                        AND CU.tipo_dpto_id = TM.tipo_dpto_id 
                        AND CU.tipo_mpio_id = TM.tipo_mpio_id) AS ciudad ";
      $sql .= "FROM   solicitud_productos_a_bodega_principal_detalle_despacho SDD ";
      $sql .= "WHERE   SDD.planilla_despacho_id = '".$planilla_despacho_id."' ";

      $sql .= " UNION DISTINCT ";

      $sql .= "SELECT 'cliente' AS destinatario,
                      VDD.venta_orden_pedido_det_des_id AS pedido_id, 
                      VDD.placa_vehiculo, 
                      VDD.cantidad_cajas, 
                      VDD.cantidad_neveras, 
                      VDD.temperatura_neveras, 
                      (SELECT descripcion
                       FROM inv_transportadoras TR
                       WHERE TR.transportadora_id = VDD.transportadora_id) AS transportadora, 
                      VDD.numero_guia, 
                      (SELECT TR.nombre_tercero
                       FROM ventas_ordenes_pedidos VOP, 
                            terceros TR
                       WHERE VDD.venta_orden_pedido_id = VOP.pedido_cliente_id 
                        AND VOP.tipo_id_tercero = TR.tipo_id_tercero
                        AND VOP.tercero_id = TR.tercero_id) AS empresa, 
                      (SELECT TR.direccion
                       FROM ventas_ordenes_pedidos VOP, 
                            terceros TR
                       WHERE VDD.venta_orden_pedido_id = VOP.pedido_cliente_id 
                        AND VOP.tipo_id_tercero = TR.tipo_id_tercero
                        AND VOP.tercero_id = TR.tercero_id) AS direccion, 
                      (SELECT TM.municipio
                       FROM ventas_ordenes_pedidos VOP, 
                            terceros TR, 
                            tipo_mpios TM 
                       WHERE VDD.venta_orden_pedido_id = VOP.pedido_cliente_id 
                        AND VOP.tipo_id_tercero = TR.tipo_id_tercero
                        AND VOP.tercero_id = TR.tercero_id
                        AND TR.tipo_pais_id = TM.tipo_pais_id 
                        AND TR.tipo_dpto_id = TM.tipo_dpto_id 
                        AND TR.tipo_mpio_id = TM.tipo_mpio_id) AS ciudad ";
      $sql .= "FROM   ventas_ordenes_pedidos_detalle_despacho VDD ";
      $sql .= "WHERE   VDD.planilla_despacho_id = '".$planilla_despacho_id."' ";

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



    function obtenerEfcDetalleDespachos($despacho_detalle_id)
    {
      $sql  = "SELECT 'farmacia' AS destinatario, 
                      numero ";
      $sql .= "FROM   inv_bodegas_movimiento_despachos_farmacias SDD ";
      $sql .= "WHERE  solicitud_prod_a_bod_ppal_det_des_id = '".$despacho_detalle_id."' ";

      $sql .= " UNION DISTINCT ";

      $sql .= "SELECT 'cliente' AS destinatario, 
                      numero ";
      $sql .= "FROM   inv_bodegas_movimiento_despachos_clientes SDD ";
      $sql .= "WHERE  venta_orden_pedido_det_des_id = '".$despacho_detalle_id."' ";

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

  }
?>