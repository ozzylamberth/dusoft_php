<?php
  class AdminPedidosConsultasSQL extends ConexionBD
  {
    /*********************************
    * Constructor
    *********************************/
    function AdminPedidosConsultasSQL(){}
		 


    function ObtenerDespachosPedidos($datos,$offset)
    {
      $sql  = "SELECT pedido,
                      estado,
                      fecha_registro_pedido,
                      farmacia,
                      numero,
                      responsable_estado,
                      fecha_despacho,
                      cantidad_cajas_despachadas,
                      cantidad_neveras_despachadas,
                      nombre_conductor,
                      numero_guia,
                      fecha_recibido,
                      cantidad_cajas_recibidas,
                      cantidad_neveras_recibidas,
                      observacion,
                      id_transportadora,
                      fecha_registro_ingreso_inventario,
                      transportadora
                FROM ( ";

      if($datos['destinatario'] != 1) {
        $sql  .= "     SELECT SD.solicitud_prod_a_bod_ppal_id AS pedido, 
                    CASE WHEN SD.estado = '0' THEN 'Registrado' 
                    WHEN SD.estado = '1' THEN 'Separado' 
                    WHEN SD.estado = '2' THEN 'Auditado' 
                    WHEN SD.estado = '3' THEN 'En Zona de Despacho' 
                    WHEN SD.estado = '4' THEN 'Despachado' ELSE '' END AS estado, 
                    SD.fecha_registro AS fecha_registro_pedido, 
                    EM.razon_social||' ::: '|| BD.descripcion AS farmacia,
                    BM.numero,
                    (SELECT nombre
                      FROM operarios_bodega
                      WHERE operario_id = (SELECT responsable_id
                                          FROM solicitud_productos_a_bodega_principal_estado SE 
                                          WHERE SD.solicitud_prod_a_bod_ppal_id = SE.solicitud_prod_a_bod_ppal_id
                                            AND SD.estado = SE.estado)) AS responsable_estado,
                    (SELECT DD.fecha_registro 
                      FROM solicitud_productos_a_bodega_principal_detalle_despacho DD 
                      WHERE SD.solicitud_prod_a_bod_ppal_id = DD.solicitud_prod_a_bod_ppal_id 
                        AND DD.solicitud_prod_a_bod_ppal_det_des_id = BM.solicitud_prod_a_bod_ppal_det_des_id) AS fecha_despacho,
                    (SELECT DD.cantidad_cajas 
                      FROM solicitud_productos_a_bodega_principal_detalle_despacho DD 
                      WHERE SD.solicitud_prod_a_bod_ppal_id = DD.solicitud_prod_a_bod_ppal_id 
                        AND DD.solicitud_prod_a_bod_ppal_det_des_id = BM.solicitud_prod_a_bod_ppal_det_des_id) AS cantidad_cajas_despachadas,
                    (SELECT DD.cantidad_neveras 
                      FROM solicitud_productos_a_bodega_principal_detalle_despacho DD 
                      WHERE SD.solicitud_prod_a_bod_ppal_id = DD.solicitud_prod_a_bod_ppal_id 
                        AND DD.solicitud_prod_a_bod_ppal_det_des_id = BM.solicitud_prod_a_bod_ppal_det_des_id) AS cantidad_neveras_despachadas,
                    (SELECT DD.nombre_conductor 
                      FROM solicitud_productos_a_bodega_principal_detalle_despacho DD 
                      WHERE SD.solicitud_prod_a_bod_ppal_id = DD.solicitud_prod_a_bod_ppal_id 
                        AND DD.solicitud_prod_a_bod_ppal_det_des_id = BM.solicitud_prod_a_bod_ppal_det_des_id) AS nombre_conductor,
                    (SELECT DD.numero_guia 
                      FROM solicitud_productos_a_bodega_principal_detalle_despacho DD 
                      WHERE SD.solicitud_prod_a_bod_ppal_id = DD.solicitud_prod_a_bod_ppal_id 
                        AND DD.solicitud_prod_a_bod_ppal_det_des_id = BM.solicitud_prod_a_bod_ppal_det_des_id) AS numero_guia,
                    (SELECT DR.fecha_registro 
                      FROM solicitud_productos_a_bodega_principal_detalle_recibido DR, solicitud_productos_a_bodega_principal_detalle_despacho DD 
                      WHERE DR.solicitud_prod_a_bod_ppal_det_des_id = DD.solicitud_prod_a_bod_ppal_det_des_id 
                        AND DD.solicitud_prod_a_bod_ppal_det_des_id = BM.solicitud_prod_a_bod_ppal_det_des_id) AS fecha_recibido,
                    (SELECT DR.cantidad_cajas 
                      FROM solicitud_productos_a_bodega_principal_detalle_recibido DR, solicitud_productos_a_bodega_principal_detalle_despacho DD 
                      WHERE DR.solicitud_prod_a_bod_ppal_det_des_id = DD.solicitud_prod_a_bod_ppal_det_des_id 
                        AND DD.solicitud_prod_a_bod_ppal_det_des_id = BM.solicitud_prod_a_bod_ppal_det_des_id) AS cantidad_cajas_recibidas,
                    (SELECT DR.cantidad_neveras 
                      FROM solicitud_productos_a_bodega_principal_detalle_recibido DR, solicitud_productos_a_bodega_principal_detalle_despacho DD 
                      WHERE DR.solicitud_prod_a_bod_ppal_det_des_id = DD.solicitud_prod_a_bod_ppal_det_des_id 
                        AND DD.solicitud_prod_a_bod_ppal_det_des_id = BM.solicitud_prod_a_bod_ppal_det_des_id) AS cantidad_neveras_recibidas,   
                    (SELECT DR.observacion 
                      FROM solicitud_productos_a_bodega_principal_detalle_recibido DR, solicitud_productos_a_bodega_principal_detalle_despacho DD 
                      WHERE DR.solicitud_prod_a_bod_ppal_det_des_id = DD.solicitud_prod_a_bod_ppal_det_des_id 
                        AND DD.solicitud_prod_a_bod_ppal_det_des_id = BM.solicitud_prod_a_bod_ppal_det_des_id) AS observacion,   
                    (SELECT DD.transportadora_id 
                      FROM solicitud_productos_a_bodega_principal_detalle_despacho DD 
                      WHERE SD.solicitud_prod_a_bod_ppal_id = DD.solicitud_prod_a_bod_ppal_id 
                        AND DD.solicitud_prod_a_bod_ppal_det_des_id = BM.solicitud_prod_a_bod_ppal_det_des_id) AS id_transportadora,
                    (SELECT IB.fecha_registro 
                      FROM inv_bodegas_movimiento_ingresosdespachos_farmacias BF,
                           inv_bodegas_movimiento IB
                      WHERE BM.empresa_id = BF.empresa_despacho 
                        AND BM.prefijo = BF.prefijo_despacho
                        AND BM.numero = BF.numero
                        AND BF.empresa_id = IB.empresa_id
                        AND BF.prefijo = IB.prefijo
                        AND BF.numero = IB.numero) AS fecha_registro_ingreso_inventario,
                    (SELECT TR.descripcion FROM solicitud_productos_a_bodega_principal_detalle_despacho DD, inv_transportadoras TR 
                      WHERE SD.solicitud_prod_a_bod_ppal_id = DD.solicitud_prod_a_bod_ppal_id 
                        AND DD.solicitud_prod_a_bod_ppal_det_des_id = BM.solicitud_prod_a_bod_ppal_det_des_id 
                        AND DD.transportadora_id = TR.transportadora_id) AS transportadora ";
        $sql  .= "FROM solicitud_productos_a_bodega_principal SD, 
                    inv_bodegas_movimiento_despachos_farmacias BM, 
                    bodegas BD, 
                    centros_utilidad CU, 
                    empresas EM";
                    if($datos['municipio'] != "") {
                      $sql  .= ", tipo_mpios TM";
                    }
                    if($datos['departamento'] != "") {
                      $sql  .= ", tipo_mpios TM, tipo_dptos TD";
                    }
        $sql  .= " WHERE SD.solicitud_prod_a_bod_ppal_id = BM.solicitud_prod_a_bod_ppal_id 
                    AND SD.farmacia_id = BD.empresa_id 
                    AND SD.centro_utilidad = BD.centro_utilidad 
                    AND SD.bodega = BD.bodega 
                    AND BD.empresa_id = CU.empresa_id 
                    AND BD.centro_utilidad = CU.centro_utilidad 
                    AND CU.empresa_id = EM.empresa_id ";

        if($datos['numero_pedido'] != "") {
          $numero_pedido = $datos['numero_pedido'];
          $sql  .= "AND SD.solicitud_prod_a_bod_ppal_id = '$numero_pedido' ";
        }

        if($datos['fecha_inicial'] != '-- 00:00:00.000000') {
          $fecha_inicial = $datos['fecha_inicial'];
          $sql  .= "AND SD.fecha_registro >= '$fecha_inicial' ";
        }

        if($datos['fecha_final'] != '-- 23:59:59.000000') {
          $fecha_final = $datos['fecha_final'];
          $sql  .= "AND SD.fecha_registro <= '$fecha_final' ";
        }

        if($datos['municipio'] != "") {
          $municipio = $datos['municipio'];
          $sql  .= "AND CU.tipo_pais_id = TM.tipo_pais_id 
                    AND CU.tipo_dpto_id = TM.tipo_dpto_id 
                    AND CU.tipo_mpio_id = TM.tipo_mpio_id
                    AND TO_ASCII(TM.municipio) ILIKE TO_ASCII('$municipio%') ";
        }

        if($datos['departamento'] != "") {
          $departamento = $datos['departamento'];
          $sql  .= "AND CU.tipo_pais_id = TM.tipo_pais_id 
                    AND CU.tipo_dpto_id = TM.tipo_dpto_id 
                    AND CU.tipo_mpio_id = TM.tipo_mpio_id

                    AND TM.tipo_pais_id = TD.tipo_pais_id 
                    AND TM.tipo_dpto_id = TD.tipo_dpto_id 

                    AND TO_ASCII(TD.departamento) ILIKE TO_ASCII('$departamento%') ";
        }

        if($datos['tipo_pedido'] != "") {
          $tipo_pedido = $datos['tipo_pedido'];
          $sql  .= "AND SD.tipo_pedido = '$tipo_pedido' ";
        }

        $sql .= " UNION DISTINCT ";//Unión de consulta que obtiene los pedidos de farmacia, que no han sido despachados (sin registro relacionado en la tabla "inv_bodegas_movimiento_despachos_farmacias")

        $sql .= "SELECT SD.solicitud_prod_a_bod_ppal_id AS pedido, 
                    CASE WHEN SD.estado = '0' THEN 'Registrado' 
                    WHEN SD.estado = '1' THEN 'Separado' 
                    WHEN SD.estado = '2' THEN 'Auditado' 
                    WHEN SD.estado = '3' THEN 'En Zona de Despacho' 
                    WHEN SD.estado = '4' THEN 'Despachado' ELSE '' END AS estado, 
                    SD.fecha_registro AS fecha_registro_pedido, 
                    EM.razon_social||' ::: '|| BD.descripcion AS farmacia,
                    NULL AS numero,
                    (SELECT nombre
                      FROM operarios_bodega
                      WHERE operario_id = (SELECT responsable_id
                                          FROM solicitud_productos_a_bodega_principal_estado SE 
                                          WHERE SD.solicitud_prod_a_bod_ppal_id = SE.solicitud_prod_a_bod_ppal_id
                                            AND SD.estado = SE.estado)) AS responsable_estado,
                    NULL AS fecha_despacho, 
                    NULL AS cantidad_cajas_despachadas, 
                    NULL AS cantidad_neveras_despachadas, 
                    NULL AS nombre_conductor, 
                    NULL AS numero_guia, 
                    NULL AS fecha_recibido, 
                    NULL AS cantidad_cajas_recibidas, 
                    NULL AS cantidad_neveras_recibidas, 
                    NULL AS observacion, 
                    NULL AS id_transportadora, 
                    NULL AS fecha_registro_ingreso_inventario, 
                    NULL AS transportadora ";
        $sql  .= "FROM solicitud_productos_a_bodega_principal SD, 
                    bodegas BD, 
                    centros_utilidad CU, 
                    empresas EM";
                    if($datos['municipio'] != "") {
                      $sql  .= ", tipo_mpios TM";
                    }
                    if($datos['departamento'] != "") {
                      $sql  .= ", tipo_mpios TM, tipo_dptos TD";
                    }
        $sql  .= " WHERE SD.farmacia_id = BD.empresa_id 
                    AND SD.centro_utilidad = BD.centro_utilidad 
                    AND SD.bodega = BD.bodega 
                    AND BD.empresa_id = CU.empresa_id 
                    AND BD.centro_utilidad = CU.centro_utilidad 
                    AND CU.empresa_id = EM.empresa_id ";

        if($datos['numero_pedido'] != "") {
          $numero_pedido = $datos['numero_pedido'];
          $sql  .= "AND SD.solicitud_prod_a_bod_ppal_id = '$numero_pedido' ";
        }

        if($datos['fecha_inicial'] != '-- 00:00:00.000000') {
          $fecha_inicial = $datos['fecha_inicial'];
          $sql  .= "AND SD.fecha_registro >= '$fecha_inicial' ";
        }

        if($datos['fecha_final'] != '-- 23:59:59.000000') {
          $fecha_final = $datos['fecha_final'];
          $sql  .= "AND SD.fecha_registro <= '$fecha_final' ";
        }

        if($datos['municipio'] != "") {
          $municipio = $datos['municipio'];
          $sql  .= "AND CU.tipo_pais_id = TM.tipo_pais_id 
                    AND CU.tipo_dpto_id = TM.tipo_dpto_id 
                    AND CU.tipo_mpio_id = TM.tipo_mpio_id
                    AND TO_ASCII(TM.municipio) ILIKE TO_ASCII('$municipio%') ";
        }

        if($datos['departamento'] != "") {
          $departamento = $datos['departamento'];
          $sql  .= "AND CU.tipo_pais_id = TM.tipo_pais_id 
                    AND CU.tipo_dpto_id = TM.tipo_dpto_id 
                    AND CU.tipo_mpio_id = TM.tipo_mpio_id

                    AND TM.tipo_pais_id = TD.tipo_pais_id 
                    AND TM.tipo_dpto_id = TD.tipo_dpto_id 

                    AND TO_ASCII(TD.departamento) ILIKE TO_ASCII('$departamento%') ";
        }

        if($datos['tipo_pedido'] != "") {
          $tipo_pedido = $datos['tipo_pedido'];
          $sql  .= " AND SD.tipo_pedido = '$tipo_pedido' ";
        }

        $sql  .= " AND NOT EXISTS (SELECT empresa_id
                      FROM inv_bodegas_movimiento_despachos_farmacias DF
                      WHERE SD.solicitud_prod_a_bod_ppal_id=DF.solicitud_prod_a_bod_ppal_id) ";
      }

      if($datos['destinatario'] != 2) {
        if($datos['destinatario'] != 1) {
          $sql .= " UNION DISTINCT ";
        }

        $sql .= "SELECT VO.pedido_cliente_id AS pedido, 
                    CASE WHEN VO.estado_pedido = '0' THEN 'Registrado' 
                    WHEN VO.estado_pedido = '1' THEN 'Separado' 
                    WHEN VO.estado_pedido = '2' THEN 'Auditado' 
                    WHEN VO.estado_pedido = '3' THEN 'En Zona de Despacho' 
                    WHEN VO.estado_pedido = '4' THEN 'Despachado' ELSE '' END AS estado, 
                    VO.fecha_registro AS fecha_registro_pedido, 
                    (SELECT TR.nombre_tercero||' -- '|| TM.municipio 
                    FROM terceros TR
                    WHERE VO.tipo_id_tercero = TR.tipo_id_tercero
                      AND VO.tercero_id = TR.tercero_id) AS farmacia, 
                    IB.numero AS numero, 
                    (SELECT nombre
                      FROM operarios_bodega
                      WHERE operario_id = (SELECT responsable_id
                                          FROM ventas_ordenes_pedidos_estado VOPE 
                                          WHERE VO.pedido_cliente_id = VOPE.pedido_cliente_id
                                            AND VO.estado_pedido = VOPE.estado)) AS responsable_estado,
                    (SELECT VDD.fecha_registro 
                      FROM ventas_ordenes_pedidos_detalle_despacho VDD 
                      WHERE VO.pedido_cliente_id = VDD.venta_orden_pedido_id 
                        AND VDD.venta_orden_pedido_det_des_id = IB.venta_orden_pedido_det_des_id) AS fecha_despacho,
                    (SELECT VDD.cantidad_cajas 
                      FROM ventas_ordenes_pedidos_detalle_despacho VDD 
                      WHERE VO.pedido_cliente_id = VDD.venta_orden_pedido_id 
                        AND VDD.venta_orden_pedido_det_des_id = IB.venta_orden_pedido_det_des_id) AS cantidad_cajas_despachadas,
                    (SELECT VDD.cantidad_neveras 
                      FROM ventas_ordenes_pedidos_detalle_despacho VDD 
                      WHERE VO.pedido_cliente_id = VDD.venta_orden_pedido_id 
                        AND VDD.venta_orden_pedido_det_des_id = IB.venta_orden_pedido_det_des_id) AS cantidad_neveras_despachadas,
                    (SELECT VDD.nombre_conductor 
                      FROM ventas_ordenes_pedidos_detalle_despacho VDD 
                      WHERE VO.pedido_cliente_id = VDD.venta_orden_pedido_id 
                        AND VDD.venta_orden_pedido_det_des_id = IB.venta_orden_pedido_det_des_id) AS nombre_conductor,
                    (SELECT VDD.numero_guia 
                      FROM ventas_ordenes_pedidos_detalle_despacho VDD 
                      WHERE VO.pedido_cliente_id = VDD.venta_orden_pedido_id 
                        AND VDD.venta_orden_pedido_det_des_id = IB.venta_orden_pedido_det_des_id) AS numero_guia,
                    NULL AS fecha_recibido, 
                    NULL AS cantidad_cajas_recibidas, 
                    NULL AS cantidad_neveras_recibidas, 
                    NULL AS observacion, 
                    NULL AS id_transportadora, 
                    NULL AS fecha_registro_ingreso_inventario, 
                    (SELECT TR.descripcion FROM ventas_ordenes_pedidos_detalle_despacho VDD, inv_transportadoras TR 
                      WHERE VO.pedido_cliente_id = VDD.venta_orden_pedido_id 
                        AND VDD.venta_orden_pedido_det_des_id = IB.venta_orden_pedido_det_des_id 
                        AND VDD.transportadora_id = TR.transportadora_id) AS transportadora ";
        $sql  .= "FROM ventas_ordenes_pedidos VO, 
                    inv_bodegas_movimiento_despachos_clientes IB, 
                    terceros_clientes TC, 
                    terceros TR, 
                    tipo_mpios TM";

        if($datos['departamento'] != "") {
          $sql  .= ", tipo_dptos TD";
        }

        $sql  .= " WHERE VO.pedido_cliente_id = IB.pedido_cliente_id ";

        $sql  .= "AND VO.empresa_id = TC.empresa_id 
                  AND VO.tipo_id_tercero = TC.tipo_id_tercero 
                  AND VO.tercero_id = TC.tercero_id 

                  AND TC.tipo_id_tercero = TR.tipo_id_tercero 
                  AND TC.tercero_id = TR.tercero_id 

                  AND TR.tipo_pais_id = TM.tipo_pais_id 
                  AND TR.tipo_dpto_id = TM.tipo_dpto_id 
                  AND TR.tipo_mpio_id = TM.tipo_mpio_id ";

        if($datos['numero_pedido'] != "") {
          $numero_pedido = $datos['numero_pedido'];
          $sql  .= "AND VO.pedido_cliente_id = '$numero_pedido' ";
        }

        if($datos['fecha_inicial'] != '-- 00:00:00.000000') {
          $fecha_inicial = $datos['fecha_inicial'];
          $sql  .= "AND VO.fecha_registro >= '$fecha_inicial' ";
        }

        if($datos['fecha_final'] != '-- 23:59:59.000000') {
          $fecha_final = $datos['fecha_final'];
          $sql  .= "AND VO.fecha_registro <= '$fecha_final' ";
        }

        if($datos['municipio'] != "") {
          $municipio = $datos['municipio'];
          $sql  .= "AND TO_ASCII(TM.municipio) ILIKE TO_ASCII('$municipio%') ";
        }

        if($datos['departamento'] != "") {
          $departamento = $datos['departamento'];
          $sql  .= "AND TM.tipo_pais_id = TD.tipo_pais_id 
                    AND TM.tipo_dpto_id = TD.tipo_dpto_id 

                    AND TO_ASCII(TD.departamento) ILIKE TO_ASCII('$departamento%') ";
        }
        
        $sql .= " UNION DISTINCT ";//Unión de consulta que obtiene los pedidos de cliente que no han sido despachados (sin registro relacionado en la tabla "inv_bodegas_movimiento_despachos_clientes")

        $sql .= "SELECT VO.pedido_cliente_id AS pedido, 
                    CASE WHEN VO.estado_pedido = '0' THEN 'Registrado' 
                    WHEN VO.estado_pedido = '1' THEN 'Separado' 
                    WHEN VO.estado_pedido = '2' THEN 'Auditado' 
                    WHEN VO.estado_pedido = '3' THEN 'En Zona de Despacho' 
                    WHEN VO.estado_pedido = '4' THEN 'Despachado' ELSE '' END AS estado, 
                    VO.fecha_registro AS fecha_registro_pedido, 
                    (SELECT TR.nombre_tercero||' -- '|| TM.municipio
                    FROM terceros TR
                    WHERE VO.tipo_id_tercero = TR.tipo_id_tercero
                      AND VO.tercero_id = TR.tercero_id) AS farmacia, 
                    NULL AS numero, 
                    (SELECT nombre
                      FROM operarios_bodega
                      WHERE operario_id = (SELECT responsable_id
                                          FROM ventas_ordenes_pedidos_estado VOPE 
                                          WHERE VO.pedido_cliente_id = VOPE.pedido_cliente_id
                                            AND VO.estado_pedido = VOPE.estado)) AS responsable_estado,
                    NULL AS fecha_despacho, 
                    NULL AS cantidad_cajas_despachadas, 
                    NULL AS cantidad_neveras_despachadas, 
                    NULL AS nombre_conductor, 
                    NULL AS numero_guia, 
                    NULL AS fecha_recibido, 
                    NULL AS cantidad_cajas_recibidas, 
                    NULL AS cantidad_neveras_recibidas, 
                    NULL AS observacion, 
                    NULL AS id_transportadora, 
                    NULL AS fecha_registro_ingreso_inventario, 
                    NULL AS transportadora ";//EM.razon_social AS farmacia, --
                    
        /*$sql  .= "FROM ventas_ordenes_pedidos VO, 
                    empresas EM";*/

        $sql  .= "FROM ventas_ordenes_pedidos VO, 
                    terceros_clientes TC, 
                    terceros TR, 
                    tipo_mpios TM";

        if($datos['departamento'] != "") {
          $sql  .= ", tipo_dptos TD";
        }
                    
        //$sql  .= " WHERE VO.empresa_id = EM.empresa_id ";

        $sql  .= " WHERE NOT EXISTS (SELECT empresa_id
                      FROM inv_bodegas_movimiento_despachos_clientes DC
                      WHERE VO.pedido_cliente_id = DC.pedido_cliente_id) ";

        $sql  .= "AND VO.empresa_id = TC.empresa_id 
                  AND VO.tipo_id_tercero = TC.tipo_id_tercero 
                  AND VO.tercero_id = TC.tercero_id 

                  AND TC.tipo_id_tercero = TR.tipo_id_tercero 
                  AND TC.tercero_id = TR.tercero_id 

                  AND TR.tipo_pais_id = TM.tipo_pais_id 
                  AND TR.tipo_dpto_id = TM.tipo_dpto_id 
                  AND TR.tipo_mpio_id = TM.tipo_mpio_id ";

        if($datos['numero_pedido'] != "") {
          $numero_pedido = $datos['numero_pedido'];
          $sql  .= "AND VO.pedido_cliente_id = '$numero_pedido' ";
        }

        if($datos['fecha_inicial'] != '-- 00:00:00.000000') {
          $fecha_inicial = $datos['fecha_inicial'];
          $sql  .= "AND VO.fecha_registro >= '$fecha_inicial' ";
        }

        if($datos['fecha_final'] != '-- 23:59:59.000000') {
          $fecha_final = $datos['fecha_final'];
          $sql  .= "AND VO.fecha_registro <= '$fecha_final' ";
        }

        if($datos['municipio'] != "") {
          $municipio = $datos['municipio'];
          $sql  .= "AND TO_ASCII(TM.municipio) ILIKE TO_ASCII('$municipio%') ";
        }

        if($datos['departamento'] != "") {
          $departamento = $datos['departamento'];
          $sql  .= "AND TM.tipo_pais_id = TD.tipo_pais_id 
                    AND TM.tipo_dpto_id = TD.tipo_dpto_id 

                    AND TO_ASCII(TD.departamento) ILIKE TO_ASCII('$departamento%') ";
        }

        /*$sql  .= " AND NOT EXISTS (SELECT empresa_id
                      FROM inv_bodegas_movimiento_despachos_clientes DC
                      WHERE VO.pedido_cliente_id = DC.pedido_cliente_id)) AS despachos";*/
      }

      $sql  .= " ) AS despachos";

      $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
      $this->ProcesarSqlConteo($cont,$offset);
      //$sql .= "ORDER BY a.fecha_formula  DESC ";
      $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset;

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
	
  }
?>