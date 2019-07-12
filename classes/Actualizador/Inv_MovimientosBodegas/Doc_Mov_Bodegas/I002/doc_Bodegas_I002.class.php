<?php

/* * ****************************************************************************
 * $Id: doc_Bodegas_I002.class.php,v 1.1 2009/07/17 19:08:17 johanna Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * $Revision: 1.1 $ 
 * 
 * @autor Jaime Gomez
 * ****************************************************************************** */

if (!IncludeClass('BodegasDocumentos'))
{
    die(MsgOut("Error al incluir archivo", "BodegasDocumentos"));
}

if (!IncludeClass('BodegasDocumentosComun', 'BodegasDocumentos'))
{
    die(MsgOut("Error al incluir archivo", "BodegasDocumentosComun"));
}

class doc_bodegas_I002 {

    function CrearDocTmp($observacion, $orden_id, $bodegas_doc_id)
    {
        $ClassDOC = new BodegasDocumentos($bodegas_doc_id);
        $objeto = $ClassDOC->GetOBJ();
        return $objeto->NewDocTemporal($observacion, $orden_id);
    }

    function TraerDatos($bodegas_doc_id)
    {
        $ClassDOC = new BodegasDocumentos($bodegas_doc_id);
        $objeto = $ClassDOC->GetOBJ();
        return $ClassDOC->GetInfoBodegaDocumento($bodegas_doc_id);
    }

    function TraerInfoDocTmp($bodegas_doc_id, $doc_tmp_id)
    {
        $ClassDOC = new BodegasDocumentos($bodegas_doc_id);
        return $ClassDOC->GetInfoBodegaDocumentoTMP($doc_tmp_id);
    }

    function TraerGetDocTemporal($bodegas_doc_id, $doc_tmp_id)
    {
        $ClassDOC = new BodegasDocumentos($bodegas_doc_id);
        $objeto = $ClassDOC->GetOBJ();
        return $objeto->GetDocTemporal($doc_tmp_id);
    }

    function AgregarItem($doc_tmp_id, $codigo_producto, $cantidad, $total_costo, $iva, $bodegas_doc_id, $lote, $fecha_vencimiento, $localizacion)
    {
        $ClassDOC = new BodegasDocumentosComun($bodegas_doc_id);
        //echo $doc_tmp_id.",".$codigo_producto.",".$cantidad.",".$iva.",".$total_costo,$lote,$fecha_vencimiento;
        return $ClassDOC->AddItemDocTemporal($doc_tmp_id, $codigo_producto, $cantidad, $iva, $total_costo, UserGETUID(), $fecha_vencimiento, $lote, $localizacion);
    }
    
    function modificarCodigoCum($codigo_producto, $codigo_cum){
        $query = "UPDATE inventarios_productos SET codigo_cum = '{$codigo_cum}' 
                  WHERE codigo_producto = '{$codigo_producto}'";
        //print_r($query);

        if (!$result = $this->ConexionBaseDatos($query))
            return false;

        return true;
    }
    
    function sincronizarCodigoCumProductos($productos){
        require_once ('nusoap/lib/nusoap.php');

        //$url_wsdl = "http://10.0.0.3/pg9/desarrollo/SIIS/ws/ws_UpdCum.php?wsdl";//2015-11-11
        //$url_wsdl = "http://10.0.1.80/SIIS/ws/codificacion_productos/ws_producto.php?wsdl";
        $soapclient = new nusoap_client($url_wsdl, true);
        
        $function = "updateCodCum";
        $inputs = array('datosCodigos' => $productos);

        $resultado = $soapclient->call($function, $inputs);

        if (isset($resultado['estado']) && $resultado['estado'] ) {
            return true;
        } else {
            return false;
        }
    }

    function EliminarItem($item_id, $bodegas_doc_id)
    {
        $ClassDOC = new BodegasDocumentosComun($bodegas_doc_id);
        return $ClassDOC->DelItemDocTemporal($item_id);
    }

    function ConsultarItems($doc_tmp_id, $bodegas_doc_id)
    {
        $ClassDOC = new BodegasDocumentosComun($bodegas_doc_id);
        return $ClassDOC->GetItemsDocTemporal($doc_tmp_id);
    }

    /*
     * Consultar Items Existencias
     */

    function ConsultarItemsExistencias($Codigo_Producto, $Empresa_Id, $Centro_Utilidad, $Bodega)
    {

        $query = "
									SELECT 	
                       codigo_producto,
                       existencia
									FROM	
                      existencias_bodegas
									WHERE 
                      empresa_id = '" . $Empresa_Id . "'
                      and
                      centro_utilidad = '" . $Centro_Utilidad . "'
                      and
                      bodega = '" . $Bodega . "'
                      and
                      codigo_producto = '" . $Codigo_Producto . "'
								";
        //print_r($query);

        if (!$result = $this->ConexionBaseDatos($query))
            return false;

        if ($result->RecordCount() > 0)
        {
            while (!$result->EOF)
            {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();

        return $vars;
    }

    /*
     * Consultar Autorizaciones 
     */

    function IngresosAutorizados($ComprasTemporal, $doc_tmp_id)
    {

        $query = "
									SELECT 	
                       *
									FROM	
                      compras_ordenes_pedidos_productosfoc
									WHERE 
                                      doc_tmp_id  = " . $doc_tmp_id . "
                                and   usuario_id  = " . UserGetUID() . " ";
        $query .= " and   empresa_id = '" . $ComprasTemporal[0]['empresa_id'] . "' ";
        $query .= " and   bodega     = '" . $ComprasTemporal[0]['bodega'] . "' ";
        $query .= " and   centro_utilidad = '" . $ComprasTemporal[0]['centro_utilidad'] . "' ";
        $query .= " and   orden_pedido_id = " . $ComprasTemporal[0]['orden_pedido_id'] . " ";
        $query .= " and   sw_autorizado = '1'
								";
        //print_r($query);

        if (!$result = $this->ConexionBaseDatos($query))
            return false;

        if ($result->RecordCount() > 0)
        {
            while (!$result->EOF)
            {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();

        return $vars;
    }

    function EliminarDocTemporal($doc_tmp_id, $bodegas_doc_id)
    {
        $ClassDOC = new BodegasDocumentosComun($bodegas_doc_id);
        return $ClassDOC->DelDocTemporal($doc_tmp_id);
    }

    function CrearDocumento($doc_tmp_id, $bodegas_doc_id)
    {        
        
        $ClassDOC = new BodegasDocumentos($bodegas_doc_id);
        $objeto = $ClassDOC->GetOBJ();
        return $objeto->CrearDocumento($doc_tmp_id);
    }

    function GetOrdenesCompra($proveedor, $empresa_id)
    {

        $query = "
									SELECT 	a.orden_pedido_id
									FROM	compras_ordenes_pedidos as a
									WHERE a.orden_pedido_id NOT IN 
																									( 
																										SELECT orden_pedido_id
																										FROM compras_ordenes_pedidos
                                                                                                        WHERE estado = '0'
																									)
									AND a.empresa_id='" . trim($empresa_id) . "'
									AND a.codigo_proveedor_id=$proveedor
									ORDER BY a.orden_pedido_id;
								";

        /* print_r($query);	 */
        if (!$result = $this->ConexionBaseDatos($query))
            return false;

        if ($result->RecordCount() > 0)
        {
            while (!$result->EOF)
            {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();

        return $vars;
    }

    function GetProveedores()
    {
        $query = "
									SELECT 	DISTINCT a.tipo_id_tercero,
													a.tercero_id,
													a.codigo_proveedor_id,
													b.nombre_tercero
									FROM	terceros_proveedores as a
									JOIN terceros as b
									ON
									(
										a.tipo_id_tercero = b.tipo_id_tercero
										AND a.tercero_id = b.tercero_id
									)
									JOIN compras_ordenes_pedidos as c
									ON
									(
										a.codigo_proveedor_id=c.codigo_proveedor_id
									)
									ORDER BY b.nombre_tercero;
								";

        if (!$result = $this->ConexionBaseDatos($query))
            return false;

        if ($result->RecordCount() > 0)
        {
            while (!$result->EOF)
            {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();

        return $vars;
    }

    function GetProductos($pagina = 1, $orden = '', $tipo_param = '', $param = '')
    {
        if (!empty($orden))
        {
            $filtro.=" AND a.orden_pedido_id=$orden";
        }

        if (!empty($tipo_param) && !empty($param))
        {
            switch ($tipo_param)
            {
                case 1:
                    $filtro.=" AND c.descripcion ILIKE '%$param%'";
                    break;

                case 2:
                    $filtro.=" AND b.codigo_producto ILIKE '%$tipo_param%'";
                    break;
            }
        }

        $query = "
								SELECT 	count(*)
								FROM	compras_ordenes_pedidos as a
								JOIN compras_ordenes_pedidos_detalle as b
								ON
								(
									a.orden_pedido_id = b.orden_pedido_id
								)
								JOIN inventarios_productos as c
								ON
								(
									b.codigo_producto=c.codigo_producto
								)
								WHERE a.estado='1'
                                AND (b.numero_unidades - COALESCE(numero_unidades_recibidas,0)) != 0
								$filtro";

        if (!$result = $this->ConexionBaseDatos($query))
            return false;

        $this->conteo = $result->fields[0];
        $this->ProcesarSqlConteo(10, $pagina);

        $query = "
									SELECT 	
													a.orden_pedido_id,
													b.item_id,
                          b.fecha_vencimiento_temp as fecha_vencimiento,
                          b.lote_temp as lote,
													b.codigo_producto,
													a.codigo_proveedor_id,
													fc_descripcion_producto(c.codigo_producto) as descripcion,
                          c.unidad_id as desunidad,
													c.contenido_unidad_venta,
													(b.numero_unidades - COALESCE(numero_unidades_recibidas,0) ) as cantidad,
													b.valor,
													b.porc_iva
									FROM	compras_ordenes_pedidos as a
									JOIN compras_ordenes_pedidos_detalle as b
									ON
									(
										a.orden_pedido_id = b.orden_pedido_id
									)
									JOIN inventarios_productos as c
									ON
									(
										b.codigo_producto=c.codigo_producto
									)
									WHERE a.estado='1'
                                    AND (b.numero_unidades - COALESCE(numero_unidades_recibidas,0)) != 0
									$filtro
									LIMIT " . $this->limit . " OFFSET " . $this->offset . "";
        //print_r($query);
        if (!$result = $this->ConexionBaseDatos($query))
            return false;

        if ($result->RecordCount() > 0)
        {
            while (!$result->EOF)
            {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();

        return $vars;
    }

    function ProcesarSqlConteo($limite = null, $offset = null)
    {
        $this->offset = 0;
        $this->paginaActual = 1;
        if ($limite == null)
        {
            $this->limit = GetLimitBrowser();
        }
        else
        {
            $this->limit = $limite;
        }

        if ($offset)
        {
            $this->paginaActual = intval($offset);
            if ($this->paginaActual > 1)
            {
                $this->offset = ($this->paginaActual - 1) * ($this->limit);
            }
        }

        return true;
    }

    /*     * ********************************************************************************
     * Funcion que permite realizar la conexion a la base de datos y ejecutar la 
     * consulta sql 
     * 
     * @param  string  $sql  sentencia sql a ejecutar $empresaid,$cuenta,$nivel,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_est,$sw_cc,$sw_dc
     * @return rst 
     * ********************************************************************************** */

    function ConexionBaseDatos($sql)
    {
        list($dbconn) = GetDBConn();
        //$dbconn->debug=true;
        $rst = $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0)
        {
            $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
            "<b class=\"label\">" . $this->frmError['MensajeError'] . "</b>";
            return false;
        }
        return $rst;
    }

    function ItemComprasEnMovimiento($doc_tmp_id, $item_id, $item_id_compras)
    {

        $sql = "UPDATE inv_bodegas_movimiento_tmp_d ";
        $sql .= "SET ";
        $sql .= "item_id_compras = " . $item_id_compras . "";
        $sql .= " Where ";
        $sql .= " item_id = " . $item_id . " ";
        $sql .= " and doc_tmp_id = " . $doc_tmp_id . " ";
        $sql .= " and usuario_id = " . UserGetUID() . "; ";

        //print_r($sql);


        if (!$result = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $result->Close();
    }

    function ConsultaItemTemporal($item_id)
    {
        $query = "
									SELECT 	*
									FROM	inv_bodegas_movimiento_tmp_d
									WHERE
									item_id= " . $item_id . "
									";


        if (!$result = $this->ConexionBaseDatos($query))
            return false;

        if ($result->RecordCount() > 0)
        {
            while (!$result->EOF)
            {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();

        return $vars;
    }

    /*     * ************************************************************************************
     * Nueva Funcion que permite relacionar una factura con una orden de compra, en el momento de hacer un ingreso 
     * de productos a la bodega.
     * @param $Numero_Factura,$Fecha_Factura,$Observaciones,$orden,$proveedor para hacer el ingreso.
     * @return token
     * ************************************************************************************* */

    function IngresarFacturaVentaProveedor($Numero_Factura, $Fecha_Factura, $Observaciones, $orden, $proveedor, $valor_factura)
    {
        list( $dia, $mes, $ano ) = split('[/.-]', $Fecha_Factura);
        $fecha = $ano . "/" . $mes . "/" . $dia;


        $sql = "INSERT INTO inv_facturas_proveedores (";
        $sql .= "       numero_factura     , ";
        $sql .= "       fecha     , ";
        $sql .= "		valor_factura,";
        $sql .= "       observaciones     , ";
        $sql .= "       orden_pedido_id     , ";
        $sql .= "       codigo_proveedor_id     , ";
        $sql .= "       empresa_id     , ";
        $sql .= "       centro_utilidad     , ";
        $sql .= "       bodega     ) ";
        $sql .= "VALUES ( ";
        $sql .= "        '" . $Numero_Factura . "',";
        $sql .= "        '" . $fecha . "',";
        $sql .= "        '" . $valor_factura . "',";
        $sql .= "        '" . $Observaciones . "',";
        $sql .= "        '" . $orden . "',";
        $sql .= "        '" . $proveedor . "',";

        $sql .= "        '" . SessionGetVar("Empresa_id") . "',";
        $sql .= "        '" . SessionGetVar("centro_utilidad") . "',";
        $sql .= "        '" . SessionGetVar("bodega") . "'";

        $sql .= "       ); ";
        //print_r($sql);


        if (!$result = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $result->Close();
    }

    /*
     * Funcion de Guardar Productos en la orden de Compra, en caso de un producto
     * llegue con diferentes lotes.
     */

    function IngresarProductoOrdenCompra($CodigoProducto, $Cantidades, $OrdenPedido, $PorcIva, $Valor)
    {

        $sql = "INSERT INTO compras_ordenes_pedidos_detalle (";
        $sql .= "       codigo_producto     , ";
        $sql .= "       numero_unidades     , ";
        $sql .= "       orden_pedido_id     , ";
        $sql .= "       porc_iva     , ";
        $sql .= "       valor,     ";
        $sql .= "       estado     ";
        $sql .= ") ";
        $sql .= "VALUES ( ";
        $sql .= "        '" . $CodigoProducto . "',";
        $sql .= "        " . $Cantidades . ",";
        $sql .= "        '" . $OrdenPedido . "',";
        $sql .= "        " . $PorcIva . ",";
        $sql .= "        " . $Valor . ",";
        $sql .= "        '1'";
        $sql .= "       ); ";
        //print_r($sql);


        if (!$result = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $result->Close();
    }

    function BuscarProductoOC($codigo_producto, $ItemIdCompras)
    {
        $sql = "SELECT
                        *
                  FROM
                      compras_ordenes_pedidos_detalle
                  WHERE
                          codigo_producto = '" . $codigo_producto . "'
                          AND     item_id = " . $ItemIdCompras . ";
                  ";
        // print_r($sql);

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $cuentas = Array();
        while (!$resultado->EOF)
        {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        return $cuentas;
    }

    /*
     * Funcion de Modificacion de un producto en la orden de Compra,  cuando un producto se ha ingresado, modiique las cantidades
     *  del lote original.
     */

    function ModificarProductoOrdenCompra($CodigoProducto, $Cantidades, $OrdenPedido, $PorcIva, $Valor, $Item_Id)
    {

        $sql = "UPDATE compras_ordenes_pedidos_detalle ";
        $sql .= "SET ";
        $sql .= "numero_unidades = (numero_unidades -'" . $Cantidades . "')";
        $sql .= " Where ";
        $sql .= " item_id = " . $Item_Id . " ";
        $sql .= " and codigo_producto ='" . $CodigoProducto . "'";
        $sql .= " and orden_pedido_id ='" . $OrdenPedido . "' ";
        $sql .= " and (numero_unidades -'" . $Cantidades . "') > 0; ";
        //print_r($sql);


        if (!$result = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $result->Close();
    }

    /*
     *  Funcion para el switch de ingreso de productos a la tabla temporal
     */

    function ItemAgregadoCompras($codigo, $can, $lote, $fecha_vencimiento, $ItemId)
    {

        $sql = " UPDATE compras_ordenes_pedidos_detalle ";
        $sql .= " SET ";
        $sql .= " fecha_vencimiento_temp = '" . $fecha_vencimiento . "', ";
        $sql .= " lote_temp = '" . $lote . "' ";
        $sql .= " Where ";
        $sql .= " item_id = " . $ItemId . " ";
        $sql .= " and ";
        $sql .= " codigo_producto ='" . $codigo . "';";
        //print_r($sql);


        if (!$result = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $result->Close();
    }

    function BuscarProductoLoteTemporal($doc_tmp_id, $usuario, $codigo_producto, $ItemIdCompras)
    {
        $sql = "SELECT
                        *
                  FROM
                      inv_bodegas_movimiento_tmp_d
                  WHERE
                          codigo_producto = '" . $codigo_producto . "'
                  AND     usuario_id = " . $usuario . "
                  AND     doc_tmp_id = " . $doc_tmp_id . "
                  AND     item_id_compras = " . $ItemIdCompras . ";
                  ";
        //print_r($sql);

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $cuentas = Array();
        while (!$resultado->EOF)
        {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        return $cuentas;
    }

    /*     * ************************************************************************************
     * Ingreso de Autorizaciones De Productos en Ordenes de Compra
     * @param $docs,$orden_pedido_id,$codigo_producto,$justificacion_ingreso,
      $usuario_id_autorizador,$usuario_id_autorizador_2,$observacion_autorizacion,
      $lote,$fecha_vencimiento,$cantidad,
      $fecha_ingreso,$porcentaje_gravamen,$valor_unitario_compra,
      $valor_unitario_factura,$total_costo,$empresa_id
     * @return token
     * ************************************************************************************* */

    function IngresoAutorizacion($docs, $orden_pedido_id, $codigo_producto, $justificacion_ingreso, $usuario_id_autorizador, $usuario_id_autorizador_2, $observacion_autorizacion, $lote, $fecha_vencimiento, $cantidad, $fecha_ingreso, $porcentaje_gravamen, $valor_unitario_compra, $valor_unitario_factura, $total_costo, $empresa_id)
    {
        $sql = "INSERT INTO inv_bodegas_movimiento_ordenes_compra_prod_autorizados (";
        $sql .= "       codigo_producto, ";
        $sql .= "       orden_pedido_id, ";
        $sql .= "		    justificacion_ingreso,";
        $sql .= "       usuario_id_autorizador, ";
        $sql .= "       usuario_id_autorizador_2, ";
        $sql .= "       observacion_autorizacion, ";
        $sql .= "       lote, ";
        $sql .= "       fecha_vencimiento, ";
        $sql .= "       cantidad, ";
        $sql .= "       fecha_solicitud, ";
        $sql .= "       porcentaje_gravamen, ";
        $sql .= "       valor_unitario_compra, ";
        $sql .= "       valor_unitario_factura, ";
        $sql .= "       total_costo, ";
        $sql .= "       empresa_id, ";
        $sql .= "       prefijo, ";
        $sql .= "       numero     ) ";
        $sql .= "VALUES ( ";
        $sql .= "        '" . $codigo_producto . "',";
        $sql .= "        " . $orden_pedido_id . ",";
        $sql .= "        '" . $justificacion_ingreso . "',";
        $sql .= "         " . $usuario_id_autorizador . ",";
        $sql .= "         " . $usuario_id_autorizador_2 . ",";
        $sql .= "        '" . $observacion_autorizacion . "',";
        $sql .= "        '" . $lote . "',";
        $sql .= "        '" . $fecha_vencimiento . "',";
        $sql .= "         " . $cantidad . ",";
        $sql .= "         '" . $fecha_ingreso . "',";
        $sql .= "         '" . $porcentaje_gravamen . "',";
        $sql .= "          " . $valor_unitario_compra . ",";
        $sql .= "          " . $valor_unitario_factura . ",";
        $sql .= "          " . $total_costo . ",";
        $sql .= "          '" . $empresa_id . "',";
        $sql .= "          '" . $docs['prefijo'] . "',";
        $sql .= "           " . $docs['numero'] . "";
        $sql .= "       ); ";
        //print_r($sql);


        if (!$result = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $result->Close();
    }

    function Listar_EvaluacionesVisuales()
    {
        $sql = "SELECT
                        *
                  FROM
                      esm_evaluacion_visual
                  WHERE
                          sw_estado = '1'; ";
        // print_r($sql);

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $cuentas = Array();
        while (!$resultado->EOF)
        {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        return $cuentas;
    }

    function BuscarItem($item_id, $doc_tmp_id)
    {
        $sql = "SELECT
                        fc_descripcion_producto_alterno(invp.codigo_producto) as descripcion_producto,
                        tmp.*,
                        invp.presentacioncomercial_id,
                        invp.cantidad as precantidad,
                        invp.codigo_invima,
                        fab.descripcion as fabricante
                  FROM
                      inv_bodegas_movimiento_tmp_d tmp,
                      inventarios_productos invp,
                      inv_fabricantes fab
                  WHERE
                          tmp.item_id = " . $item_id . "
                    and   tmp.doc_tmp_id = " . $doc_tmp_id . "
                    and   tmp.usuario_id = " . UserGetUID() . "
                    and   tmp.codigo_producto = invp.codigo_producto
                    and   invp.fabricante_id = fab.fabricante_id
                          ; ";
        // print_r($sql);

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $cuentas = Array();
        while (!$resultado->EOF)
        {
            $cuentas = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        return $cuentas;
    }

    /*
     * Funcion de Guardar Productos en la orden de Compra, en caso de un producto
     * llegue con diferentes lotes.
     */

    function Insertar_ActaTmp($Formulario, $query)
    {

        $sql = "INSERT INTO esm_acta_tecnica_tmp (";
        $sql .= "       empresa_id, ";
        $sql .= "       centro_utilidad, ";
        $sql .= "       bodega, ";
        $sql .= "       doc_tmp_id, ";
        $sql .= "       item_id, ";
        $sql .= "       usuario_id, ";
        $sql .= "       orden_pedido_id, ";
        $sql .= "       codigo_producto,     ";
        $sql .= "       lote,     ";
        $sql .= "       fecha_vencimiento,     ";
        $sql .= "       numero_factura,     ";
        $sql .= "       numero_remision,     ";
        $sql .= "       registro_sanitario,     ";
        $sql .= "       argumentacion_doble_muestreo,     ";
        $sql .= "       total_corrugadas,     ";
        $sql .= "       unidad_corrugadas,     ";
        $sql .= "       unidad_corrugadas_a_muestrear,     ";
        $sql .= "       corrugadas_a_muestrear,     ";
        $sql .= "       sw_concepto_calidad,     ";
        $sql .= "       observacion,     ";
        $sql .= "       responsable_realiza,     ";
        $sql .= "       responsable_verifica,     ";
        $sql .= "       cantidad,     ";
        $sql .= "       c_nc_lote,     ";
        $sql .= "       c_nc_vencimiento     ";
        $sql .= ") ";
        $sql .= "VALUES ( ";
        $sql .= "        '" . $Formulario['empresa_id'] . "', ";
        $sql .= "        '" . $Formulario['centro_utilidad'] . "', ";
        $sql .= "        '" . $Formulario['bodega'] . "', ";
        $sql .= "        " . $Formulario['doc_tmp_id'] . ", ";
        $sql .= "        " . $Formulario['item_id'] . ", ";
        $sql .= "        " . $Formulario['usuario_id'] . ", ";
        $sql .= "        " . $Formulario['orden_pedido_id'] . ", ";
        $sql .= "        '" . $Formulario['codigo_producto'] . "', ";
        $sql .= "        '" . $Formulario['lote'] . "', ";
        $sql .= "        '" . $Formulario['fecha_vencimiento'] . "', ";
        $sql .= "        '" . $Formulario['numero_factura'] . "', ";
        $sql .= "        '" . $Formulario['numero_remision'] . "', ";
        $sql .= "        '" . $Formulario['registro_sanitario'] . "', ";
        $sql .= "        '" . $Formulario['argumentacion_doble_muestreo'] . "', ";
        $sql .= "        '" . $Formulario['total_corrugadas'] . "', ";
        $sql .= "        '" . $Formulario['unidad_corrugadas'] . "', ";
        $sql .= "        '" . $Formulario['unidad_corrugadas_a_muestrear'] . "', ";
        $sql .= "        '" . $Formulario['corrugadas_a_muestrear'] . "', ";
        $sql .= "        '" . $Formulario['sw_concepto_calidad'] . "', ";
        $sql .= "        '" . $Formulario['observacion'] . "', ";
        $sql .= "        '" . $Formulario['responsable_realiza'] . "', ";
        $sql .= "        '" . $Formulario['responsable_verifica'] . "', ";
        $sql .= "        " . $Formulario['cantidad'] . ", ";
        $sql .= "        '" . $Formulario['c_nc_lote'] . "', ";
        $sql .= "        '" . $Formulario['c_nc_vencimiento'] . "' ";
        $sql .= "       ); ";
        $sql .= "  ";
        $sql .= "  " . $query;
        //print_r($sql);
        if (!$result = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];
        else
            return true;
        $result->Close();
    }

    /*
     * Funcion de Guardar Productos en la orden de Compra, en caso de un producto
     * llegue con diferentes lotes.
     */

    function Modificar_ActaTmp($Formulario, $query)
    {
        $sql = "UPDATE esm_acta_tecnica_tmp SET ";
        $sql .= "       orden_pedido_id = " . $Formulario['orden_pedido_id'] . ", ";
        $sql .= "       codigo_producto = '" . $Formulario['codigo_producto'] . "',     ";
        $sql .= "       lote = '" . $Formulario['lote'] . "',     ";
        $sql .= "       fecha_vencimiento = '" . $Formulario['fecha_vencimiento'] . "',     ";
        $sql .= "       numero_factura = '" . $Formulario['numero_factura'] . "',     ";
        $sql .= "       numero_remision = '" . $Formulario['numero_remision'] . "',     ";
        $sql .= "       registro_sanitario = '" . $Formulario['registro_sanitario'] . "',     ";
        $sql .= "       argumentacion_doble_muestreo = '" . $Formulario['argumentacion_doble_muestreo'] . "',     ";
        $sql .= "       total_corrugadas = '" . $Formulario['total_corrugadas'] . "',     ";
        $sql .= "       unidad_corrugadas = '" . $Formulario['unidad_corrugadas'] . "',     ";
        $sql .= "       unidad_corrugadas_a_muestrear = '" . $Formulario['unidad_corrugadas_a_muestrear'] . "',     ";
        $sql .= "       corrugadas_a_muestrear = '" . $Formulario['corrugadas_a_muestrear'] . "',     ";
        $sql .= "       sw_concepto_calidad = '" . $Formulario['sw_concepto_calidad'] . "',     ";
        $sql .= "       observacion = '" . $Formulario['observacion'] . "',     ";
        $sql .= "       responsable_realiza = '" . $Formulario['responsable_realiza'] . "',     ";
        $sql .= "       responsable_verifica = '" . $Formulario['responsable_verifica'] . "',     ";
        $sql .= "       cantidad = " . $Formulario['cantidad'] . ",     ";
        $sql .= "       c_nc_lote = '" . $Formulario['c_nc_lote'] . "',     ";
        $sql .= "       c_nc_vencimiento = '" . $Formulario['c_nc_vencimiento'] . "'     ";
        $sql .= " ";
        $sql .= " WHERE ";
        $sql .= "         item_id = " . $Formulario['item_id'] . "
                    and   doc_tmp_id = " . $Formulario['doc_tmp_id'] . "
                    and   usuario_id = " . UserGetUID() . ";";
        $sql .= "  ";
        $sql .= "  " . $query;
        //print_r($sql);
        if (!$result = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];
        else
            return true;
        $result->Close();
    }

    function BuscarResgistroActa($doc_tmp_id, $usuario_id, $item_id)
    {
        $sql = "SELECT
                        *
                  FROM
                      esm_acta_tecnica_tmp
                  WHERE
                          item_id = " . $item_id . "
                    and   doc_tmp_id = " . $doc_tmp_id . "
                    and   usuario_id = " . $usuario_id . "; ";
        //print_r($sql);

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $cuentas = Array();
        while (!$resultado->EOF)
        {
            $cuentas = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        return $cuentas;
    }

    function BuscarItem_EVisual($doc_tmp_id, $usuario_id, $item_id, $evaluacion_visual_id)
    {
        $sql = "SELECT
                        *
                  FROM
                      esm_acta_tecnica_evaluacion_visual_tmp
                  WHERE
                          item_id = " . $item_id . "
                    and   doc_tmp_id = " . $doc_tmp_id . "
                    and   usuario_id = " . $usuario_id . "
                    and   evaluacion_visual_id = " . $evaluacion_visual_id . "

					";
        // print_r($sql);

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $cuentas = Array();
        while (!$resultado->EOF)
        {
            $cuentas = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        return $cuentas;
    }

    function ActasTecnicas_Temporales($usuario_id, $doc_tmp_id)
    {
        $sql = "SELECT
                        *
                  FROM
                      esm_acta_tecnica_tmp
                  WHERE
                          doc_tmp_id = " . $doc_tmp_id . "
                    and   usuario_id = " . $usuario_id . "; ";
        //print_r($sql);

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $cuentas = Array();
        while (!$resultado->EOF)
        {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        return $cuentas;
    }

    function EvaluacionesVisuales_Temporales($usuario_id, $doc_tmp_id)
    {
        $sql = "SELECT
                        *
                  FROM
                      esm_acta_tecnica_evaluacion_visual_tmp
                  WHERE
                          doc_tmp_id = " . $doc_tmp_id . "
                    and   usuario_id = " . $usuario_id . "; ";
        //print_r($sql);

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $cuentas = Array();
        while (!$resultado->EOF)
        {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        return $cuentas;
    }

    function Buscar_ActasTecnicas($empresa_id, $prefijo, $numero)
    {
        $sql = "select
                      a.acta_tecnica_id,
                      a.prefijo,
                      a.numero,
                      a.codigo_producto,
                      fc_descripcion_producto(a.codigo_producto) as producto,
                      a.lote,
                      a.fecha_vencimiento,
                      a.observacion,
                      a.fecha_registro,
                      a.responsable_realiza,
                      a.responsable_verifica,
                      b.nombre
                      from
                      esm_acta_tecnica as a
                      JOIN system_usuarios as b ON (a.usuario_id = b.usuario_id)
                      where
                          a.empresa_id = '" . $empresa_id . "'
                      and a.prefijo = '" . $prefijo . "'
                      and a.numero = " . $numero . "
                      order by a.fecha_registro; ";
        //print_r($sql);

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $cuentas = Array();
        while (!$resultado->EOF)
        {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        return $cuentas;
    }

    /*
     * Funcion de Guardar Productos en la orden de Compra, en caso de un producto
     * llegue con diferentes lotes.
     */

    function Insertar_Acta($datos, $EvaluacionesVisuales_Productos, $docs)
    {

        foreach ($datos as $key => $valor)
        {
            $sql = "INSERT INTO esm_acta_tecnica (";
            $sql .= "       acta_tecnica_id, ";
            $sql .= "       empresa_id, ";
            $sql .= "       centro_utilidad, ";
            $sql .= "       bodega, ";
            $sql .= "       usuario_id, ";
            $sql .= "       orden_pedido_id, ";
            $sql .= "       codigo_producto,     ";
            $sql .= "       lote,     ";
            $sql .= "       fecha_vencimiento,     ";
            $sql .= "       numero_factura,     ";
            $sql .= "       numero_remision,     ";
            $sql .= "       registro_sanitario,     ";
            $sql .= "       argumentacion_doble_muestreo,     ";
            $sql .= "       total_corrugadas,     ";
            $sql .= "       unidad_corrugadas,     ";
            $sql .= "       unidad_corrugadas_a_muestrear,     ";
            $sql .= "       corrugadas_a_muestrear,     ";
            $sql .= "       sw_concepto_calidad,     ";
            $sql .= "       observacion,     ";
            $sql .= "       responsable_realiza,     ";
            $sql .= "       responsable_verifica,     ";
            $sql .= "       cantidad,     ";
            $sql .= "       c_nc_lote,     ";
            $sql .= "       c_nc_vencimiento,     ";
            $sql .= "       prefijo,     ";
            $sql .= "       numero     ";
            $sql .= ") ";
            $sql .= "VALUES ( ";
            $sql .= "        default, ";
            $sql .= "        '" . $valor['empresa_id'] . "', ";
            $sql .= "        '" . $valor['centro_utilidad'] . "', ";
            $sql .= "        '" . $valor['bodega'] . "', ";
            $sql .= "        " . UserGetUID() . ", ";
            $sql .= "        " . $valor['orden_pedido_id'] . ", ";
            $sql .= "        '" . $valor['codigo_producto'] . "', ";
            $sql .= "        '" . $valor['lote'] . "', ";
            $sql .= "        '" . $valor['fecha_vencimiento'] . "', ";
            $sql .= "        '" . $valor['numero_factura'] . "', ";
            $sql .= "        '" . $valor['numero_remision'] . "', ";
            $sql .= "        '" . $valor['registro_sanitario'] . "', ";
            $sql .= "        '" . $valor['argumentacion_doble_muestreo'] . "', ";
            $sql .= "        '" . $valor['total_corrugadas'] . "', ";
            $sql .= "        '" . $valor['unidad_corrugadas'] . "', ";
            $sql .= "        '" . $valor['unidad_corrugadas_a_muestrear'] . "', ";
            $sql .= "        '" . $valor['corrugadas_a_muestrear'] . "', ";
            $sql .= "        '" . $valor['sw_concepto_calidad'] . "', ";
            $sql .= "        '" . $valor['observacion'] . "', ";
            $sql .= "        '" . $valor['responsable_realiza'] . "', ";
            $sql .= "        '" . $valor['responsable_verifica'] . "', ";
            $sql .= "        " . $valor['cantidad'] . ", ";
            $sql .= "        '" . $valor['c_nc_lote'] . "', ";
            $sql .= "        '" . $valor['c_nc_vencimiento'] . "', ";
            $sql .= "        '" . $docs['prefijo'] . "', ";
            $sql .= "        " . $docs['numero'] . " ";
            $sql .= "       )RETURNING(acta_tecnica_id); ";
            $query = "";
            //print_r($sql);
            if (!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];
            $cuentas = Array();
            while (!$resultado->EOF)
            {
                $cuentas = $resultado->GetRowAssoc($ToUpper = false);
                $resultado->MoveNext();
            }

            foreach ($EvaluacionesVisuales_Productos as $key => $evp)
            {
                if ($valor['doc_tmp_id'] == $evp['doc_tmp_id'] &&
                        $valor['item_id'] == $evp['item_id'] &&
                        $valor['usuario_id'] == $evp['usuario_id'])
                {
                    $query .= " INSERT into esm_acta_tecnica_evaluacion_visual ( ";
                    $query .= " acta_tecnica_id, ";
                    $query .= " evaluacion_visual_id, ";
                    $query .= " observaciones, ";
                    $query .= " sw_cumple ) ";
                    $query .= " VALUES ( ";
                    $query .= " " . $cuentas['acta_tecnica_id'] . ", ";
                    $query .= " " . $evp['evaluacion_visual_id'] . ", ";
                    $query .= " '" . $evp['observaciones'] . "', ";
                    $query .= " '" . $evp['sw_cumple'] . "' ";
                    $query .= ");";
                }
            }
            // print_r($sql);
            //  print_r($query);
            if (!empty($query))
            {
                $result = $this->ConexionBaseDatos($query);
            }
        }
        //$resultado->Close(); 
    }

}

?>