<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: app_FacturasDespacho_controller.php
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */
class app_FacturasDespacho_controller extends classModulo {

    /**
     * Constructor de la clase
     */
    function app_FacturasDespacho_controller() {
        
    }

    /**
     *  Funcion principal del modulo
     *  @return boolean
     */
    function Main() {
        $request = $_REQUEST;
        $parametrizacionBod = AutoCarga::factory('FacturasDespachoSQL', '', 'app', 'FacturasDespacho');
        $action['volver'] = ModuloGetURL('system', 'Menu');
        $permisos = $parametrizacionBod->ObtenerPermisos();

        $ttl_gral = "FACTURACION DE DESPACHO";
        $titulo[0] = 'EMPRESAS';
        $url[0] = 'app';
        $url[1] = 'FacturasDespacho';
        $url[2] = 'controller';
        $url[3] = 'Menu';
        $url[4] = 'datos';
        $this->salida = gui_theme_menu_acceso($ttl_gral, $titulo, $permisos, $url, $action['volver']);
        return true;
    }

    /**
     *   Funcion de control para el menu inicial
     */
    function Menu() {
        $request = $_REQUEST;
        if ($_REQUEST['datos'])
            SessionSetVar("Datos", $_REQUEST['datos']);
        $datos = SessionGetVar("Datos");
        $action['FacturaDespacho'] = ModuloGetURL("app", "FacturasDespacho", "controller", "BuscarClientesDespachos");
        $action['FacturaDespachoGeneradas'] = ModuloGetURL("app", "FacturasDespacho", "controller", "Facturas_Generadas");
        $action['volver'] = ModuloGetURL("app", "FacturasDespacho", "controller", "Main");

        if ($request['permiso_FacturasDespacho']['empresa'])
            SessionSetVar("empresa_id", $request['permiso_FacturasDespacho']['empresa']);

        $act = AutoCarga::factory("FacturasDespachoHTML", "views", "app", "FacturasDespacho");
        $this->salida = $act->formaMenu($action);
        return true;
    }

    /**
     * Funcion Menu Nuevo Contrato
     * Funcion que permite 
     *   -Crear un Nuevo Contrato
     *   -Copiar Un Contrato
     * @return boolean
     */
    function BuscarClientesDespachos() {
        $request = $_REQUEST;
        $datos = SessionGetVar("Datos");
        /* print_r($datos); */

        $act = AutoCarga::factory("FacturasDespachoHTML", "views", "app", "FacturasDespacho");
        $sql = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
        $request['buscador']['empresa_id'] = $datos['empresa_id'];
        $tipos_ids_terceros = $sql->Tipos_Ids_Terceros();
        $Terceros_Clientes = $sql->Terceros_Clientes($request['buscador'], $request['offset']);
        $action['volver'] = ModuloGetURL("app", "FacturasDespacho", "controller", "Menu");
        $action['buscador'] = ModuloGetURL("app", "FacturasDespacho", "controller", "BuscarClientesDespachos");
        $action['paginador'] = ModuloGetURL('app', 'FacturasDespacho', 'controller', 'BuscarClientesDespachos', array("buscador" => $request['buscador']));

        $this->salida = $act->Listado_TercerosDespachos($action, $datos, $Terceros_Clientes, $tipos_ids_terceros, $sql->conteo, $sql->pagina);
        return true;
    }

    function tipo_factura() {

        $request = $_REQUEST;

        $view = AutoCarga::factory("FacturasDespachoHTML", "views", "app", "FacturasDespacho");

        $action['facturacion_individual'] = ModuloGetURL('app', 'FacturasDespacho', 'controller', 'Crear_Facturas', array("tipo_id_tercero" => $request['tipo_id_tercero'], "tercero_id" => $request['tercero_id']));
        $action['facturacion_agrupada'] = ModuloGetURL('app', 'FacturasDespacho', 'controller', 'facturacion_agrupada', array("tipo_id_tercero" => $request['tipo_id_tercero'], "tercero_id" => $request['tercero_id']));

        $action['volver'] = ModuloGetURL("app", "FacturasDespacho", "controller", "BuscarClientesDespachos");
        $this->salida = $view->tipo_factura($action);
        return true;
    }

    /**
     * Funcion Menu Nuevo Contrato
     * Funcion que permite 
     *   -Crear un Nuevo Contrato
     *   -Copiar Un Contrato
     * @return boolean
     */
    function Crear_Facturas() {
        $request = $_REQUEST;

        $datos = SessionGetVar("Datos");

        $documento_id = ModuloGetVar("app", "FacturasDespacho", "documento_factura_" . $datos['empresa_id'] . "");

        $act = AutoCarga::factory("FacturasDespachoHTML", "views", "app", "FacturasDespacho");
        $sql = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
        $sql_2 = AutoCarga::factory("ContratacionProductosClienteSQL", "classes", "app", "ContratacionProductosCliente");
        $Tercero = $sql_2->ConsultarTercero_Contrato($datos['empresa_id'], $_REQUEST['tercero_id'], $_REQUEST['tipo_id_tercero']);
        $request['buscador']['tipo_id_tercero'] = $_REQUEST['tipo_id_tercero'];
        $request['buscador']['tercero_id'] = $_REQUEST['tercero_id'];
        $Listado_Pedidos = $sql->Listado_Pedidos($datos['empresa_id'], $request['buscador'], $request['offset']);

        #$action['volver'] = ModuloGetURL("app", "FacturasDespacho", "controller", "BuscarClientesDespachos");
        $action['volver'] = ModuloGetURL("app", "FacturasDespacho", "controller", "tipo_factura", array("tipo_id_tercero" => $_REQUEST['tipo_id_tercero'], "tercero_id" => $_REQUEST['tercero_id']));

        $action['buscador'] = ModuloGetURL("app", "FacturasDespacho", "controller", "Crear_Facturas");
        $action['guardar'] = ModuloGetURL("app", "FacturasDespacho", "controller", "Generar_FacturaCliente");
        $action['paginador'] = ModuloGetURL('app', 'FacturasDespacho', 'controller', 'Crear_Facturas', array("buscador" => $request['buscador']));

        $this->salida = $act->Listado_PedidosFacturar($action, $datos, $documento_id, $Tercero, $Listado_Pedidos, $sql->conteo, $sql->pagina);
        return true;
    }

    function facturacion_agrupada() {

        $request = $_REQUEST;
        $datos_empresa = SessionGetVar("Datos");

        $documento_id = ModuloGetVar("app", "FacturasDespacho", "documento_factura_" . $datos_empresa['empresa_id'] . "");

        $view = AutoCarga::factory("FacturasDespachoHTML", "views", "app", "FacturasDespacho");
        $sql = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
        $sql_aux = AutoCarga::factory("ContratacionProductosClienteSQL", "classes", "app", "ContratacionProductosCliente");

        $this->SetXajax(array("consultar_pedidos_clientes", "enviar_documentos_agrupados"), "app_modules/FacturasDespacho/RemoteXajax/RemotosFacturacionDespachos.php", "ISO-8859-1");

        $datos_cliente = $sql_aux->ConsultarTercero_Contrato($datos_empresa['empresa_id'], $_REQUEST['tercero_id'], $_REQUEST['tipo_id_tercero']);

        $lista_pedidos_cliente = $sql->Listado_Pedidos($datos_empresa['empresa_id'], $request['buscador'], $request['offset']);

        $action['volver'] = ModuloGetURL("app", "FacturasDespacho", "controller", "tipo_factura", array("tipo_id_tercero" => $_REQUEST['tipo_id_tercero'], "tercero_id" => $_REQUEST['tercero_id']));

        $this->salida = $view->facturacion_agrupada($datos_cliente, $lista_pedidos_cliente, $datos_empresa, $documento_id, $action);
        return true;
    }

    function Generar_FacturaCliente() {
        $request = $_REQUEST;

        $datos = SessionGetVar("Datos");

        $sql = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
        $sql_2 = AutoCarga::factory("ContratacionProductosClienteSQL", "classes", "app", "ContratacionProductosCliente");
        $sql_bodegas = AutoCarga::factory("MovBodegasSQL", "classes", "app", "Inv_MovimientosBodegas");

        $Tercero = $sql_2->ConsultarTercero_Contrato($datos['empresa_id'], $_REQUEST['tercero_id'], $_REQUEST['tipo_id_tercero']);
        $documento_id = ModuloGetVar("app", "FacturasDespacho", "documento_factura_" . $datos['empresa_id'] . "");
        $Documento_Factura = $sql->Documento_Factura($documento_id);
        $Parametros_Retencion = $sql->Parametros_Retencion($datos['empresa_id']);
        
        

        //====== Sincronizacion FI =============
        $dusoft_fi = AutoCarga::factory("SincronizacionDusoftFI", "SincronizacionDusoftFI", "", "");

        //39014
        //39022
        //$resultado_sincronizacion_ws = $dusoft_fi->facturas_venta_fi($datos['empresa_id'], 'ME', 38812);
        //echo "<pre>";
        //print_r($resultado_sincronizacion_ws);
        //exit();        
        //======================================

        $query = "";
        $query = $sql->InsertarFactura($Documento_Factura, $Tercero, $_REQUEST, $Parametros_Retencion);

        for ($i = 0; $i < $_REQUEST['cantidad_registros']; $i++) {
            if ($_REQUEST[$i] != "") {
                $DocumentoBodega = explode("/", $_REQUEST[$i]);
                $resultado = $sql_bodegas->SacarDocumento($DocumentoBodega[0], $DocumentoBodega[1], $DocumentoBodega[2]);
                $update_despachos .= $sql->Actualizar_Despachos($DocumentoBodega[0], $DocumentoBodega[1], $DocumentoBodega[2]);
                foreach ($resultado['DETALLE'] as $k => $valor) {
                    if($Tercero['facturar_iva'] == '0'){
                        $valor['porcentaje_gravamen'] = 0;
                    }
                    $query .= $sql->InsertarFactura_d($Documento_Factura, $valor);
                }
            }
        }
        if (trim($query) != "") {
            $query .= $sql->ActualizarNumeracion($Documento_Factura);
            $query .= $update_despachos;
        }
        
        $request['buscador']['prefijo'] = $Documento_Factura['prefijo'];
        $request['buscador']['numero'] = $Documento_Factura['numeracion'];

        $token = $sql->EjecutarSql($query);

        $html .= "<script>";
        if (!$token) {
            $html .= " history.go(-1) ";
        } else {
            $resultado_sincronizacion_ws = $dusoft_fi->facturas_venta_fi($datos['empresa_id'], $Documento_Factura['prefijo'], $Documento_Factura['numeracion']);

            $request['resultado_sincronizacion_ws']['mensaje_ws'] = $resultado_sincronizacion_ws['mensaje_ws'];
            $request['resultado_sincronizacion_ws']['mensaje_bd'] = $resultado_sincronizacion_ws['mensaje_bd'];

            $action['recarga'] = ModuloGetURL("app", "FacturasDespacho", "controller", "Facturas_Generadas", array("buscador" => $request['buscador'], 'resultado_ws' => $request['resultado_sincronizacion_ws']));

            $html .= "window.location=\"" . $action['recarga'] . "\";";
        }
        $html .= "</script>";
        $this->salida = $html;


        return true;
    }

    function generar_facturacion_agrupada() {

        $request = $_REQUEST;

        $datos_empresa = SessionGetVar("Datos");
        $documentos = $request['documentos'];

        $dusoft_fi = AutoCarga::factory("SincronizacionDusoftFI", "SincronizacionDusoftFI", "", "");

        $sql_facturacion = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
        $sql_clientes = AutoCarga::factory("ContratacionProductosClienteSQL", "classes", "app", "ContratacionProductosCliente");
        $sql_bodegas = AutoCarga::factory("MovBodegasSQL", "classes", "app", "Inv_MovimientosBodegas");

        $datos_cliente = $sql_clientes->ConsultarTercero_Contrato($datos_empresa['empresa_id'], $request['tercero_id'], $request['tipo_id_tercero']);
        $documento_id = ModuloGetVar("app", "FacturasDespacho", "documento_factura_" . $datos_empresa['empresa_id'] . "");
        $documento_facturacion = $sql_facturacion->Documento_Factura($documento_id);
        $parametros_retencion = $sql_facturacion->Parametros_Retencion($datos_empresa['empresa_id']);

        // Validar que no exista la factura
        $factura_agrupada = $sql_facturacion->validar_factura_agrupada($documento_facturacion['empresa_id'], $documento_facturacion['prefijo'], $documento_facturacion['numeracion']);


        if (count($factura_agrupada) == 0) {

            $query .= $sql_facturacion->insertar_factura_agrupada($documento_facturacion, $datos_cliente, $parametros_retencion);

            foreach ($documentos as $key => $value) {

                $docs = explode("/", $value);
                $docs_empresa_id = $docs[0];
                $docs_prefijo = $docs[1];
                $docs_numero = $docs[2];

                $datos_documento_despacho['pedido_cliente_id'] = $docs[5];
                $datos_documento_despacho['tipo_id_vendedor'] = $docs[3];
                $datos_documento_despacho['vendedor_id'] = $docs[4];
                $datos_documento_despacho['tipo_id_tercero'] = $request['tipo_id_tercero'];
                $datos_documento_despacho['tercero_id'] = $request['tercero_id'];



                $obtener_documento_despacho = $sql_bodegas->SacarDocumento($docs_empresa_id, $docs_prefijo, $docs_numero);
                $query_actualizar_documento_despacho .= $sql_facturacion->Actualizar_Despachos($docs_empresa_id, $docs_prefijo, $docs_numero);

                foreach ($obtener_documento_despacho['DETALLE'] as $k => $medicamentos) {
                    if($datos_cliente['facturar_iva'] == '0'){
                        $medicamentos['porcentaje_gravamen'] = 0;
                    }
                    $query .= $sql_facturacion->insertar_detalle_factura_agrupada($documento_facturacion, $datos_documento_despacho, $medicamentos);
                }
            }

            if (trim($query) != "") {
                $query .= $sql_facturacion->ActualizarNumeracion($documento_facturacion);
                $query .= $query_actualizar_documento_despacho;
            }

            $request['buscador']['prefijo'] = $documento_facturacion['prefijo'];
            $request['buscador']['numero'] = $documento_facturacion['numeracion'];

            //$url = ModuloGetURL("app", "FacturasDespacho", "controller", "Facturas_Generadas", array("buscador" => $request['buscador']));

            $token = $sql_facturacion->EjecutarSql($query);

            if (!$token) {
                $html = "
                    <script>
                        alert('SE HA GENERADO UN ERROR AL CREAR LA FACTURA No.[{$documento_facturacion['prefijo']}-{$documento_facturacion['numeracion']}]');
                        history.go(-1);
                    </script>
                ";
            } else {

                $resultado_sincronizacion_ws = $dusoft_fi->facturas_venta_fi($documento_facturacion['empresa_id'], $documento_facturacion['prefijo'], $documento_facturacion['numeracion']);

                $request['resultado_sincronizacion_ws']['mensaje_ws'] = $resultado_sincronizacion_ws['mensaje_ws'];
                $request['resultado_sincronizacion_ws']['mensaje_bd'] = $resultado_sincronizacion_ws['mensaje_bd'];

                $url = ModuloGetURL("app", "FacturasDespacho", "controller", "Facturas_Generadas", array("buscador" => $request['buscador'], 'resultado_ws' => $request['resultado_sincronizacion_ws']));

                $html = "
                    <script>
                        alert('SE HA GENERADO LA FACTURA No.[{$documento_facturacion['prefijo']}-{$documento_facturacion['numeracion']}]');
                        window.location='{$url}';
                    </script>
                ";
            }
        } else {
            $html = "
                <script>
                    alert('SE HA GENERADO UN ERROR (Duplicate-key) AL CREAR LA FACTURA No.[{$documento_facturacion['prefijo']}-{$documento_facturacion['numeracion']}]');
                    history.go(-1);
                </script>
            ";
        }


        $this->salida = $html;
        return true;
    }

    /**
     *  Funcion que busca el listado todas las facturas
     */
    function Facturas_Generadas() {

        $request = $_REQUEST;
        $datos = SessionGetVar("Datos");

        /*echo "<pre>";
        var_dump($request['buscador']);
        echo "</pre>";*/


        $act = AutoCarga::factory("FacturasDespachoHTML", "views", "app", "FacturasDespacho");
        $sql = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");

        $this->SetXajax(array("sincronizar_facturas_pendientes_ws_fi"), "app_modules/FacturasDespacho/RemoteXajax/RemotosFacturacionDespachos.php", "ISO-8859-1");

        $tipos_ids_terceros = $sql->Tipos_Ids_Terceros();
        $Prefijos_Facturas = $sql->Prefijos_Facturas($datos['empresa_id']);

        if (!empty($request['buscador']))
            $Listado_FacturasCliente = $sql->Listado_FacturasCliente($datos['empresa_id'], $request['buscador'], $request['offset']);

        $action['buscador'] = ModuloGetURL("app", "FacturasDespacho", "controller", "Facturas_Generadas");
        $action['paginador'] = ModuloGetURL('app', 'FacturasDespacho', 'controller', 'Facturas_Generadas', array("buscador" => $request['buscador']));
        $action['volver'] = ModuloGetURL("app", "FacturasDespacho", "controller", "Menu");
        $this->salida = $act->Listado_FacturasGeneradas($action, $datos, $Listado_FacturasCliente, $tipos_ids_terceros, $Prefijos_Facturas, $sql->conteo, $sql->pagina);

        return true;
    }

}

?>