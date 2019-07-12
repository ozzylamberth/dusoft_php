<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: app_Compras_Orden_Compras_controller.php,v 1.0
 * @copyright (C) 2010  IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja Torres 
 */

/**
 * Clase Control: Compras_Orden_Compras
 * Clase encargada del control de llamado de metodos en el modulo
 *
 * @package IPSOFT-SIIS
  / */
class app_Compras_Orden_Compras_controller extends classModulo {

    /**
     * Constructor de la clase
     */
    function app_Compras_Orden_Compras_controller() {
        
    }

    /**
     *  Funcion principal del modulo
     *  @return boolean
     */
    function Main() {
        $request = $_REQUEST;
        $rotacion = AutoCarga::factory('Compras_Orden_ComprasSQL', '', 'app', 'Compras_Orden_Compras');
        $permisos = $rotacion->ObtenerPermisos();
        $ttl_gral = "COMPRAS";
        $mtz[0] = 'EMPRESA PRINCIPAL';
        $url[0] = 'app';
        $url[1] = 'Compras_Orden_Compras';
        $url[2] = 'controller';
        $url[3] = 'Menu';
        $url[4] = 'autoria';
        $action['volver'] = ModuloGetURL('system', 'Menu');
        $this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
        return true;
    }

    /*
     * Funcion de control para el Menu Inicial
     * @return boolean
     */

    function Menu() {
        $request = $_REQUEST;
        if ($request['autoria'])
            SessionSetVar("DatosEmpresaAF", $request['autoria']);
        $emp = SessionGetVar("DatosEmpresaAF");
        $empresa = $emp['empresa_id'];
        $datos = AutoCarga::factory('Compras_Orden_ComprasSQL', '', 'app', 'Compras_Orden_Compras');
        $permisos = $datos->ListarCentrodeUtilidad($empresa);
        $permisos2 = $datos->ObtenerBodegaFarmacia($empresa);
        $c1 = $permisos[$empresa]['descripcion'];
        $ce = 'CENTRO DE UTILIDAD';
        $cont = $ce . " [" . $c1 . "] ";
        $ttl_gral = " CENTRO DE UTILIDAD";
        $mtz[0] = $cont;
        $url[0] = 'app';
        $url[1] = 'Compras_Orden_Compras';
        $url[2] = 'controller';
        $url[3] = 'Empresas';
        $url[4] = 'Compras_Orden_Compras';
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Main");
        $this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos2, $url, $action['volver']);
        return true;
    }

    /*
     * Funcion que permite Seleccionar Una Opcion del Menu 
     *  @return boolean
     */

    function Empresas() {
        $request = $_REQUEST;
        $emp = SessionGetVar("DatosEmpresaAF");
        $empresa = $emp['empresa_id'];

        $centro_utilidad = $emp['centro_utilidad'];
        $this->SetXajax(array("EmpresaOrdenPedido", "Proveedores", "PasarVariablesOrden", "TProveedores", "LTProveedores", "ContinuarCrearOC"), "app_modules/Compras_Orden_Compras/RemoteXajax/Compras_Orden_Compras.php", "ISO-8859-1");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");
        $contratacion = AutoCarga::factory('Compras_Orden_ComprasSQL', '', 'app', 'Compras_Orden_Compras');
        SessionSetVar("bodega", $request['Compras_Orden_Compras']['bodega']);
        $bod = SessionGetVar("bodega");
        $action['Pre-orden'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "BuscarPreOrden");
        $action['BuscarPre'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarOrdenes");
        $action['div'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Ordenes_Compras");
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Main");
        $action['unificarcompras'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "UnificarOrdenesCompras");
        $action['novedadesordenescompra'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "GestionarNovedadesDetallesOrdenesCompras");
        $action['editarordenescompra'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "EditarOrdenesCompras");
        $action['consultarauditoriasordenescompra'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarAuditoriasDetallesOrdenesCompras");
        $action['subir_plano_rotacion'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "subir_plano_rotacion");


        $act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");

        $this->salida = $act->FormaMenu($action, $empresa);
        return true;
    }

    /*
     * Funcion que permite Buscar La Preorden Generada desde la Rotacion 
     *  @return boolean
     */

    function BuscarPreOrden() {
        $request = $_REQUEST;
        $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
        $conteo = $pagina = 0;
        if (!empty($request['buscador'])) {
            //$datos=$mdl->consultarInformacionPreOrden($request['buscador'],$request['offset']);
            $action['buscador'] = ModuloGetURL('app', 'Compras_Orden_Compras', 'controller', 'BuscarPreOrden');
            $action['paginador'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "BuscarPreOrden", array("buscador" => $request['buscador']));
            $conteo = $mdl->conteo;
            $pagina = $mdl->pagina;
        }
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
        $action['detalle'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "DetallePreorden");
        $this->salida = $act->FormaBuscarDocumento($action, $request['buscador'], $datos, $conteo, $pagina);
        return true;
    }

    /*
     * Funcion que permite  detallar la pre orden de compras
     *  @return boolean
     */

    function DetallePreorden() {
        $request = $_REQUEST;
        $emp = SessionGetVar("DatosEmpresaAF");
        $empresa = $emp['empresa_id'];
        SessionSetVar("Farmacia", $request['farmacia_id']);
        $Farmac = SessionGetVar("Farmacia");
        SessionSetVar("preorden", $request['preorden_id']);
        $preorden_id = SessionGetVar("preorden");
        $this->SetXajax(array("InformacionOrdenComp", "AsiganarCondiciones", "TrasferirInformacion"), "app_modules/Compras_Orden_Compras/RemoteXajax/Compras_Orden_Compras.php", "ISO-8859-1");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");
        $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $dat = $mdl->ListarProveedoresGeneradosPO($preorden_id);
        $conteo = $pagina = 0;
        if (!empty($request['buscador'])) {
            $datos = $mdl->ConsultarDetallePreOrden($preorden_id, $request['buscador'], $request['offset']);
            $action['buscador'] = ModuloGetURL('app', 'Compras_Orden_Compras', 'controller', 'DetallePreorden', array("preorden_id" => $preorden_id));
            $action['paginador'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "DetallePreorden", array("buscador" => $request['buscador']));
            $conteo = $mdl->conteo;
            $pagina = $mdl->pagina;
        }
        $act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Ordenes_Compras");
        $this->salida = $act->FormaDetalleDocumento($action, $dat, $datos, $conteo, $pagina, $preorden_id, $empresa);
        return true;
    }

    /*
     * Funcion que permite generar la orden de compras 
     *  @return boolean
     */

    function GenerarOrdenCompras() {
        $request = $_REQUEST;
        $Farmac = SessionGetVar("Farmacia");
        $preorden_id = SessionGetVar("preorden");
        SessionSetVar("proveedor", $request['proveed']);
        $proveedo = SessionGetVar("proveedor");
        SessionSetVar("preorden", $request['preorden_id']);
        $preorden = SessionGetVar("preorden");
        SessionSetVar("empres", $request['empresa']);
        $empresa = SessionGetVar("empres");
        $sel = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $rst = $sel->SeleccionarInformacionDetalle($preorden, $proveedo);
        $dat = $sel->insertarOrden_Pedido($proveedo, $empresa);
        $inf = $sel->SeleccionarMaxcompras_ordenes_pedidos($proveedo, $empresa);
        $orden_pedido_id = $inf['0']['numero'];
        $infd = $sel->Ingresarcompras_ordenes_pedidos_detalle($rst, $orden_pedido_id);
        $dtos = $sel->ActuEstado($preorden, $proveedo);
        return true;
    }

    /*
     * Funcion que permite consultar las ordenes de compras
     *  @return boolean
     */

    function ConsultarOrdenes() {
        $request = $_REQUEST;

        IncludeFileModulo("Compras_Orden_Compras", "RemoteXajax", "app", "Compras_Orden_Compras");
        $this->SetXajax(array("AnularOC", "ModificarOC", "ModificacionDetalleOC", "ValidarModificacionOC"), null, "ISO-8859-1");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
        $conteo = $pagina = 0;
        $orden_pedido_id = $request ['orden_pedido_id'];
        $tiposdoc = $mdl->ConsultarTipoId();
        if (!empty($request['buscador'])) {
            $datos = $mdl->ConsultarOrdenComprasGeneradas($request['buscador'], $request['offset'], $orden_pedido_id);
            $action['buscador'] = ModuloGetURL('app', 'Compras_Orden_Compras', 'controller', 'ConsultarOrdenes');
            $action['paginador'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarOrdenes", array("buscador" => $request['buscador'], "orden_pedido_id" => $orden_pedido_id));
            $conteo = $mdl->conteo;
            $pagina = $mdl->pagina;
        }
        $unidades_negocio = $mdl->UnidadesNegocio();
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
        $action['detalle2'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "DetalleOrdenCompra");
        $action['asignar'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "AsignarCondiciones");
        $this->salida = $act->FormaBuscarDocumentoOrdenCompra($action, $request['buscador'], $datos, $conteo, $pagina, $tiposdoc, $unidades_negocio);
        return true;
    }

    /*
     * Funcion que permite detallar la orden de compras generadas
     *  @return boolean
     */

    function DetalleOrdenCompra() {
        $request = $_REQUEST;
        SessionSetVar("empresa_i", $request['empresa_id']);
        $empresa_id = SessionGetVar("empresa_i");
        SessionSetVar("orden_pedid", $request['orden_pedido_id']);
        $orden_pedido_id = SessionGetVar("orden_pedid");
        $tipo_id_tercero = $request['tipo_id_tercero'];
        $tercero_id = $request['tercero_id'];
        $nombre = $request['nombre'];
        $razon = $request['razon_social'];
        $empresa_id = SessionGetVar("empresa_i");
        SessionSetVar("orden_pedid", $request['orden_pedido_id']);
        $orden_pedido_id = SessionGetVar("orden_pedid");
        $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
        $conteo = $pagina = 0;
        $datos = $mdl->ConsultarDetalleCompra($orden_pedido_id);
        $dats = $mdl->ConsultarCondicionesEstablecidas();
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarOrdenes", array("orden_pedido_id" => $orden_pedido_id));
        $this->salida = $act->FormaDetalleDocumentoOrdenCompra($action, $datos, $conteo, $pagina, $tipo_id_tercero, $tercero_id, $nombre, $razon, $orden_pedido_id);
        return true;
    }

    /*
     * Funcion que permite asignarle Condiciones de orden de compra 
     *  @return boolean
     */

    function AsignarCondiciones() {
        $request = $_REQUEST;
        SessionSetVar("empresa_i", $request['empresa_id']);
        $empresa_id = SessionGetVar("empresa_i");
        SessionSetVar("orden_pedid", $request['orden_pedido_id']);
        $orden_pedido_id = SessionGetVar("orden_pedid");
        $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $dats = $mdl->ConsultarCondicionesEstablecidas();
        $observacion = $mdl->Observaciones($orden_pedido_id);
        $this->SetXajax(array("TrasferirCondicion"), "app_modules/Compras_Orden_Compras/RemoteXajax/Compras_Orden_Compras.php", "ISO-8859-1");
        $act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarOrdenes");
        $this->salida = $act->FormaAsiganarCondiciones($action, $orden_pedido_id, $empresa_id, $dats, $observacion);
        return true;
    }

    /*
     * Funcion que permite crear el documento de pedido apartir de las ordenes de compras que aun tengan productos pendientes por recibir 
     *  @return boolean
     */

    function CrearDocumentosPedido() {
        $request = $_REQUEST;
        $empresa_id = SessionGetVar("empresa_i");
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarOrdenes");
        $this->salida = $act->FormaAsiganarCondiciones($action, $orden_pedido_id, $empresa_id);
        return true;
    }

    /*
     * Funcion que permite Unificar las preordenes de compras por proveedor
     *  @return boolean
     */

    function UnificacionOrdePedidoxProveedor() {
        $request = $_REQUEST;
        SessionSetVar("empresapedido", $request['empresapedido']);
        $empresapedido = SessionGetVar("empresapedido");
        SessionSetVar("proveedor_id", $request['proveedor']);
        $proveedor = SessionGetVar("proveedor_id");
        SessionSetVar("nombre_tercer", $request['nombre_tercero']);
        $nombre_tercero = SessionGetVar("nombre_tercer");
        SessionSetVar("tipo_id_terc", $request['tipo_id_tercero']);
        $tipo_id_tercero = SessionGetVar("tipo_id_terc");
        SessionSetVar("tercero", $request['tercero_id']);
        $tercero_id = SessionGetVar("tercero");
        SessionSetVar("razon_social", $request['razon_social']);
        $razon_social = SessionGetVar("razon_social");
        $this->SetXajax(array("TransfeOrdenPedido"), "app_modules/Compras_Orden_Compras/RemoteXajax/Compras_Orden_Compras.php", "ISO-8859-1");
        $act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarOrdenes");
        $this->salida = $act->FormaDocumentoOrdenPedido($action, $orden_pedido_id, $empresa_id, $nombre_tercero, $tipo_id_tercero, $tercero_id, $razon_social);
        return true;
    }

    /*
     * Funcion que permite  crear y Unificar las ordenes de compras para un documento de pedido
     *  @return boolean
     */

    function CrearDocumentoYUnificar() {
        $request = $_REQUEST;
        $observacion = $request['observa'];

        $emp = SessionGetVar("DatosEmpresaAF");
        $empresa = $emp['empresa_id'];


        $proveedor = SessionGetVar("proveedor_id");
        $nombre_tercero = SessionGetVar("nombre_tercer");
        $tipo_id_tercero = SessionGetVar("tipo_id_terc");
        $tercero_id = SessionGetVar("tercero");
        $razon_social = SessionGetVar("razon_social");
        $empresapedido = SessionGetVar("empresapedido");


        $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

        $dat = $mdl->ingresarDocumentoDePedido($empresa, $proveedor, $observacion);
        $dt = $mdl->SeleccionarDocumentoDePedido($empresa, $proveedor);

        $id = $dt[0]['id'];
        $datos = $mdl->ListarDetalleOrdenPedidoXProveedor($empresa, $proveedor);

        if (empty($datos)) {
            $datos = $mdl->ListarDetalleOrdenPedidoXProveedorDos($empresa, $proveedor);
        }

        if (!empty($datos)) {
            foreach ($datos as $key => $dtl) {

                $datos2 = $mdl->UnificarDatos2($dtl['codigo_producto'], $proveedor);
                $dat2 = $mdl->InsertarDatosPendientes($datos2, $id);
            }
        }


        $consul = $mdl->ConsultarDocumentoPedidoOP($id);
        $inf = $mdl->SeleccionarMaxiPedido();
        $pedido_id = $inf[0]['numero'];
        $inOp = $mdl->insertarOrden_Pedido($pedido_id, $proveedor, $empresa, $empresa);
        $maxi = $mdl->SeleccionarMaxcompras_ordenes_pedidos($proveedor, $empresa);
        $numero = $maxi[0]['numero'];
        $inf = $mdl->Ingresarcompras_ordenes_pedidos_detalle_d($consul, $numero);
        $actu = $mdl->ActualizarSw_unificadaOp($empresa, $proveedor, $numero, $id);
        $act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
        $this->salida = $act->FormaDocumentoDePedido($action, $id, $empresa, $nombre_tercero, $tipo_id_tercero, $tercero_id, $razon_social, $numero);

        return true;
    }

    /*
     * Funcion que permite Listar las ordenes de compras por proveedor para ser unificadas
     *  @return boolean
     */

    function UnificarOrdenesCompras() {
        $request = $_REQUEST;
        $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $dat = $mdl->ListarProveedoresOrdenCompra();
        $this->SetXajax(array("MostrarOrdenesCompra", "CargarOrdenCompra", "DetalleOrdenCompra", "cancelarTodoOrdenPedido", "unificarTodasOrdenes"), "app_modules/Compras_Orden_Compras/RemoteXajax/Compras_Orden_Compras.php", "ISO-8859-1");
        $act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
        $this->salida = $act->FormaUnificarOrdenes($action, $dat);
        return true;
    }

    /*
     * Funcion que permite consultar las ordenes de compras con las novedades de sus detalles
     *  @return boolean
     */

    function GestionarNovedadesDetallesOrdenesCompras() {
        $request = $_REQUEST;
        $session = $_SESSION;

        $empresa_id = $session['DatosEmpresaAF']['empresa_id'];

        IncludeFileModulo("Compras_Orden_Compras", "RemoteXajax", "app", "Compras_Orden_Compras");
        $this->SetXajax(array("GuardarNovedadOrdenCompra", "ActualizarNovedadOrdenCompra"), null, "ISO-8859-1");

        $path_app = basename(GetVarConfigaplication('DIR_SIIS')); //Nombre aplicacion
        $ip = $_SERVER['SERVER_ADDR'];
        $ruta_archivo = "http://" . $ip . "/" . $path_app . "/repositorio/";

        $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
        $conteo = $pagina = 0;
        $orden_pedido_id = $request ['orden_pedido_id'];
        //$tiposdoc = $mdl->ConsultarTipoId();
        if (!empty($request['buscador'])) {
            $observaciones = $mdl->ConsultarObservacionesOrdenesCompra();
            $datos = $mdl->ConsultarNovedadesDetallesOrdenesCompras($empresa_id, $request['buscador'], $request['offset'], $orden_pedido_id);
            $action['buscador'] = ModuloGetURL('app', 'Compras_Orden_Compras', 'controller', 'GestionarNovedadesDetallesOrdenesCompras');
            $action['guardar'] = ModuloGetURL('app', 'Compras_Orden_Compras', 'controller', 'GuardarNovedadDetalleOrdenCompra');
            $action['borrarArchivoNovedad'] = ModuloGetURL('app', 'Compras_Orden_Compras', 'controller', 'BorrarArchivoNovedadDetalleOrdenCompra');
            $action['borrarNovedad'] = ModuloGetURL('app', 'Compras_Orden_Compras', 'controller', 'BorrarNovedadDetalleOrdenCompra');
            $action['paginador'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarOrdenes", array("buscador" => $request['buscador'], "orden_pedido_id" => $orden_pedido_id));
            $conteo = $mdl->conteo;
            $pagina = $mdl->pagina;
        }
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
        $action['detalle2'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "DetalleOrdenCompra");
        $action['asignar'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "AsignarCondiciones");
        $this->salida = $act->FormaBuscarNovedadesDetallesOrdenesCompras($action, $request['buscador'], $datos, $observaciones, $ruta_archivo, $conteo, $pagina/* , $tiposdoc */);
        return true;
    }

    function EditarOrdenesCompras() {
        $request = $_REQUEST;
        $session = $_SESSION;

        $orden_compra = $request['orden_compra'];

        //$bodega = $session['DatosEmpresaAF']['descripcion1'];
        $empresa_id = $session['DatosEmpresaAF']['empresa_id'];

        IncludeFileModulo("Compras_Orden_Compras", "RemoteXajax", "app", "Compras_Orden_Compras");
        $this->SetXajax(array("SeleccionDeProductos", "BuscarProductos", "AgregarItemOCEdicion", "DetalleOCEdicion", "BorrarItemOCEdicion"/* , "ConfirmarOC" */), null, "ISO-8859-1");

        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

        $mensaje = "";

        $existencia_documento_temporal = $sql->VerificarExistenciaDocumentoTemporal($orden_compra, $empresa_id);
        $documento_temporal = $existencia_documento_temporal[0]['orden_pedido_id'];

        if (!empty($documento_temporal)) {
            $mensaje = "NO ES POSIBLE MODIFICAR LA ORDEN DE COMPRA " . $orden_compra . ", YA QUE EN BODEGA ESTÁN REALIZANDO EL INGRESO DE ESTA ORDEN DE COMPRA";
        }

        $existencia_documento = $sql->VerificarExistenciaDocumento($orden_compra, $empresa_id);
        $documento = $existencia_documento[0]['orden_pedido_id'];

        if (!empty($documento)) {
            $mensaje = "NO ES POSIBLE MODIFICAR LA ORDEN DE COMPRA " . $orden_compra . ", YA QUE ESTA ORDEN DE COMPRA YA HA SIDO RECIBIDA TOTALMENTE";
        }

        $empresa_correspondiente = $sql->VerificarEmpresa($orden_compra, $empresa_id);
        $empresa = $empresa_correspondiente[0]['orden_pedido_id'];
        $codigo_proveedor_id = $empresa_correspondiente[0]['codigo_proveedor_id'];

        if (empty($empresa)) {
            $mensaje = "NO ES POSIBLE MODIFICAR LA ORDEN DE COMPRA " . $orden_compra . ", YA QUE NO CORRESPONDE A LA EMPRESA";
        }

        $form = AutoCarga::factory("Compras_OrdenesPedidosHTML", "views", "app", "Compras_Orden_Compras");

        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");

        $this->salida = $form->FormaEOC($action, $orden_compra, $empresa_id, $mensaje, $codigo_proveedor_id);
        return true;
    }

    /*
     * Funcion que permite consultar las auditorias de los detalles (items) de las ordenes de compra
     *  @return boolean
     */

    function ConsultarAuditoriasDetallesOrdenesCompras() {
        $request = $_REQUEST;
        $session = $_SESSION;

        $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
        $conteo = $pagina = 0;
        $orden_pedido_id = $request ['orden_pedido_id'];
        $tiposdoc = $mdl->ConsultarTipoId();
        if (!empty($request['buscador'])) {
            //$request['buscador']['empresa_id'] = $session['DatosEmpresaAF']['centro_utilidad'];
            $request['buscador']['empresa_id'] = $session['DatosEmpresaAF']['empresa_id'];
            //$request['buscador']['centro_utilidad'] = $session['bodega'];

            $datos = $mdl->ConsultarAuditoriasDetallesOrdenesCompras($request['buscador']/* , $request['offset'] *//* , $orden_pedido_id */);
            $datos_documento_temporal = $mdl->ConsultarAuditoriasDetallesOrdenesComprasDocumentoTemporal($request['buscador']/* , $request['offset'], $orden_pedido_id */);
            $datos_documento = $mdl->ConsultarAuditoriasDetallesOrdenesComprasDocumento($request['buscador']/* , $request['offset'], $orden_pedido_id */);
            $action['buscador'] = ModuloGetURL('app', 'Compras_Orden_Compras', 'controller', 'ConsultarAuditoriasDetallesOrdenesCompras');
            $action['paginador'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarAuditoriasDetallesOrdenesCompras", array("buscador" => $request['buscador'], "orden_pedido_id" => $orden_pedido_id));
            $conteo = $mdl->conteo;
            $pagina = $mdl->pagina;
        }
        $unidades_negocio = $mdl->UnidadesNegocio();
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
        $action['detalle2'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "DetalleOrdenCompra");
        $action['asignar'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "AsignarCondiciones");
        $this->salida = $act->FormaConsultarAuditoriasDetallesOrdenesCompras($action, $request['buscador'], $datos, $conteo, $pagina, $tiposdoc, $unidades_negocio, $datos_documento_temporal, $datos_documento);
        return true;
    }

    function GuardarNovedadDetalleOrdenCompra() {
        $request = $_REQUEST;
        $file = $_FILES;

        $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

        $novedad_orden_compra_id = $request['novedad_orden_compra_id'];
        $observacion_id = $request['observacion'];
        $descripcion = $request['descripcion'];
        $compra_orden_detalle_id = $request['compra_orden_detalle_id'];
        $fecha_posible_envio = $request['fecha_posible_envio'];

        $nuevo_nombre = "";

        if (empty($novedad_orden_compra_id) && !empty($observacion_id)) {
            if (!empty($file['archivo']['name'])) {
                $nombre_original_archivo = $file['archivo']['name'];

                $nombre = basename($file['archivo']['name']);
                $dataimg = explode(".", $nombre); //separa la extension del archivo sin el punto (genera un arreglo)

                $random = rand(0, 10000000); //obtiene un numero aleatorio con valor semilla 10000000 
                $path_app = basename(GetVarConfigaplication('DIR_SIIS'));
                $nuevo_nombre = 'NOC_' . $random . "." . $dataimg['1'];

                $ruta_archivo = $_SERVER['DOCUMENT_ROOT'] . "/" . $path_app . "/repositorio/" . $nuevo_nombre;

                move_uploaded_file($file['archivo']['tmp_name'], $ruta_archivo);
            }

            $sql->GuardarNovedadOrdenCompra($compra_orden_detalle_id, $observacion_id, $descripcion, $fecha_posible_envio);

            if (!empty($nuevo_nombre)) {
                $novedad_orden_compra = $sql->ObtenerUltimoIdNovedadOrdenCompra();
                $novedad_orden_compra_id = $novedad_orden_compra['novedad_orden_compra_id'];

                $sql->GuardarArchivoNovedadOrdenCompra($novedad_orden_compra_id, $nuevo_nombre, $nombre_original_archivo);
            }
        } else if (!empty($novedad_orden_compra_id) && !empty($observacion_id)) {
            if (!empty($file['archivo']['name'])) {
                $nombre_original_archivo = $file['archivo']['name'];

                $nombre = basename($file['archivo']['name']);
                $dataimg = explode(".", $nombre); //separa la extension del archivo sin el punto (genera un arreglo)

                $random = rand(0, 10000000); //obtiene un numero aleatorio con valor semilla 10000000 
                $path_app = basename(GetVarConfigaplication('DIR_SIIS'));
                $nuevo_nombre = 'NOC_' . $random . "." . $dataimg['1'];

                $ruta_archivo = $_SERVER['DOCUMENT_ROOT'] . "/" . $path_app . "/repositorio/" . $nuevo_nombre;

                move_uploaded_file($file['archivo']['tmp_name'], $ruta_archivo);
            }

            $sql->ActualizarNovedadOrdenCompra($novedad_orden_compra_id, $observacion_id, $descripcion, $fecha_posible_envio);

            if (!empty($nuevo_nombre)) {
                $sql->GuardarArchivoNovedadOrdenCompra($novedad_orden_compra_id, $nuevo_nombre, $nombre_original_archivo);
            }
        }

        $consulta = ModuloGetURL('app', 'Compras_Orden_Compras', 'controller', 'GestionarNovedadesDetallesOrdenesCompras', array("buscador[fecha_inicio]" => $request['buscador']['fecha_inicio'], "buscador[orden]" => $request['buscador']['orden']));

        header('Location: ' . $consulta . '');
    }

    /*
     * Funcion que permite eliminar los archivos de las novedades de (los detalles de) las ordenes de compras
     *  @return boolean
     */

    function BorrarArchivoNovedadDetalleOrdenCompra() {
        $request = $_REQUEST;

        $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

        $id_archivo_unico = $request['id_archivo_unico'];
        $archivo_unico = $request['archivo_unico'];

        $sql->BorrarUnicoArchivoNovedadOrdenCompra($id_archivo_unico);

        $ruta = $_SERVER['DOCUMENT_ROOT'] . "/" . basename(GetVarConfigaplication('DIR_SIIS')) . "/repositorio/" . $archivo_unico;
        unlink($ruta);

        $consulta = ModuloGetURL('app', 'Compras_Orden_Compras', 'controller', 'GestionarNovedadesDetallesOrdenesCompras', array("buscador[fecha_inicio]" => $request['buscador']['fecha_inicio'], "buscador[orden]" => $request['buscador']['orden']));

        header('Location: ' . $consulta . '');
    }

    /*
     * Funcion que permite eliminar las novedades de (los detalles de) las ordenes de compras
     *  @return boolean
     */

    function BorrarNovedadDetalleOrdenCompra() {
        $request = $_REQUEST;

        $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

        $novedad_orden_compra_id = $request['novedad_orden_compra_id'];
        $archivo = $request['archivo'];

        $sql->BorrarArchivoNovedadOrdenCompra($novedad_orden_compra_id);
        $sql->BorrarNovedadOrdenCompra($novedad_orden_compra_id);

        for ($i = 0; $i <= count($archivo); $i++) {
            $ruta = $_SERVER['DOCUMENT_ROOT'] . "/" . basename(GetVarConfigaplication('DIR_SIIS')) . "/repositorio/" . $archivo[$i];
            unlink($ruta);
        }

        $consulta = ModuloGetURL('app', 'Compras_Orden_Compras', 'controller', 'GestionarNovedadesDetallesOrdenesCompras', array("buscador[fecha_inicio]" => $request['buscador']['fecha_inicio'], "buscador[orden]" => $request['buscador']['orden']));

        header('Location: ' . $consulta . '');
    }

    /*
     * Funcion que permite Unificar las ordenes de compras por proveedor
     *  @return boolean
     */

    function UnificarOrdenPedidoProveedor() {
        $request = $_REQUEST;
        $emp = SessionGetVar("DatosEmpresaAF");
        $empresa = $emp['empresa_id'];
        SessionSetVar("proveedor", $request['proveedor']);
        $proveedor = SessionGetVar("proveedor");
        $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $dts = $mdl->SeleccionarInformacionTmp_OrdenPedido($proveedor);

        $dt = $mdl->SeleccionarMaxcompras_ordenes_pedidos($proveedor, $empresa);
        $orden_pedido_id = $dt['0']['numero'];

        $infd = $mdl->Ingresarcompras_ordenes_pedidos_d($dts, $orden_pedido_id);

        $actu = $mdl->ActuEstadosOrdenesPedidoUnificadas($dts, $proveedor);
        $eli = $mdl->Eliminar_tmp_OrdenPedido($proveedor);
        $act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");


        if ($infd != true) {
            $msg1 = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>" . $ingc->mensajeDeError;
        } else {
            $msg1 = "EL INGRESO SE HA REALIZADO SATISFACTORIAMENTE";
        }

        $this->salida = $act->FormaMensaje($action, $msg0, $msg1);
        return true;
    }

    function CrearComprasOrdenesPedidos() {
        $request = $_REQUEST;

        IncludeFileModulo("Compras_Orden_Compras", "RemoteXajax", "app", "Compras_Orden_Compras");
        $this->SetXajax(array("SeleccionDeProductos", "BuscarProductos",
            "AgregarItemOC", "DetalleOC", "BorrarItemOC", "ConfirmarOC"), null, "ISO-8859-1");

        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");


        $form = AutoCarga::factory("Compras_OrdenesPedidosHTML", "views", "app", "Compras_Orden_Compras");
        $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

        //SeleccionDeProductos
        if ($_REQUEST['unidad_negocio']['codigo_unidad_negocio'] != "") {
            $sql->ModificarUnidadNegocio($_REQUEST['orden_pedido_id'], $_REQUEST['codigoproveedorid'], $_REQUEST['unidad_negocio']['codigo_unidad_negocio']);
        }

        //Cargar Archivo
        if ($request['accion'] == 'subir_archivo') {

            $productos_validos = array();
            $productos_invalidos = array();

            $cargar_archivo = $this->cargar_archivo_plano();
            $contenido = $cargar_archivo['datos'];

            if (count($contenido) > 0) {

                $validacion_archivo = $this->validar_contenido_archivo_plano($contenido);
                $validacion_costos_archivo = $this->validar_costos_productos_archivo_plano($_REQUEST['empresa_id'], $_REQUEST['codigoproveedorid'], $_REQUEST['orden_pedido_id'], $validacion_archivo['productos_validos']);


                $productos_validos = $validacion_costos_archivo['productos_validos'];
                $productos_invalidos = array_merge($validacion_archivo['productos_invalidos'], $validacion_costos_archivo['productos_invalidos']);

                foreach ($productos_validos as $key => $value) {
                    $token = $sql->AgregarItemOC($_REQUEST['orden_pedido_id'], $_REQUEST['empresa_id'], $value['codigo_producto'], $value['cantidad_solicitada'], $value['costo'], $value['iva']);

                    if (!$token) {
                        array_push($productos_invalidos, $value);
                    }
                }
            }
        }

        $Token = $sql->IngresarOrdenCompra($_REQUEST['orden_pedido_id'], $_REQUEST['codigoproveedorid'], $_REQUEST['empresa_id']);
        $Compras = $sql->Compras_Validador($_REQUEST['orden_pedido_id'], $_REQUEST['codigoproveedorid']);
        $unidades_negocio = $sql->UnidadesNegocio();

        $html .= "<script>";
        if (empty($Compras))
            $html .= " history.go(-1); ";
        $html .= "</script>";

        $InfoProveedor = $sql->InformacionTercerosProveedores($_REQUEST['codigoproveedorid']);




        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
        $action['GuardarUnidad'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "CrearComprasOrdenesPedidos");
        $action['buscador'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "CrearComprasOrdenesPedidos");
        $action['descargar_archivo'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "descargar_archivo_plano", array("productos_invalidos" => $productos_invalidos));

        $this->salida .= $html;
        $this->salida .= $form->FormaOC($action, $InfoProveedor, $unidades_negocio, $Compras, $productos_invalidos);
        return true;
    }

    function cargar_archivo_plano() {
        //var_dump($_FILES);
        $mensaje_error = "";

        if ($_FILES['archivo_plano']['error'] != 0) {

            switch ($_FILES['archivo_plano']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $mensaje_error = "EL ARCHIVO QUE SE ESTA SUBIENDO EXCEDE EL TAMAÑO PERMITIDO";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $mensaje_error = "EL ARCHIVO QUE SE ESTA SUBIENDO EXCEDE EL TAMAÑO PERMITIDO EN LA FORMA";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $mensaje_error = "EL ARCHIVO SOLO FUE SUBIDO PARCIALMENTE";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $mensaje_error = "EL ARCHIVO NO FUE SUBIDO";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $mensaje_error = "NO HAY DIRECTORIO TEMPORAL PARA SUBIR EL ARCHIVO";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $mensaje_error = "HA OCURRIDO UN ERROR AL MOMENTO DE COPIAR EL ARCHIVO A DISCO";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $mensaje_error = "HA OCURRIDO UN ERROR CON LA EXTENSION DEL ARCHIVO";
                    break;
                default:
                    $mensaje_error = "HA OCURRIDO UN ERROR DESCONOCIDO MIENTRAS SE REALIZABA EL PROCESO";
                    break;
            }
            return false;
        }

        if (is_uploaded_file($_FILES['archivo_plano']['tmp_name'])) {

            //$mensaje_error .= ' - Archivo Cargado';
            $datos_archivo = array();

            $archivo = fopen($_FILES['archivo_plano']['tmp_name'], "r");

            while (!feof($archivo)) {
                $datos = explode(",", fgets($archivo));
                array_push($datos_archivo, $datos);
            }
        }

        return array('mensaje_error' => $mensaje_error, 'datos' => $datos_archivo);
    }

    function descargar_archivo_plano() {

        $request = $_REQUEST;

        if (!empty($request['productos_invalidos'])) {

            $datos_consulta = $request['productos_invalidos'];
            $nombre_archivo = date("Y-m-d-H-i-s") . "_productos_invalidos.txt";

            $resultado = $this->crear_archivo($datos_consulta, $nombre_archivo);

            // Descargar Archivo                
            if ($resultado['continuar']) {

                $fullPath = $resultado['archivo'];

                if ($fd = fopen($fullPath, "r")) {

                    $fsize = filesize($fullPath);
                    $path_parts = pathinfo($fullPath);

                    header("Content-type: application/octet-stream");
                    header("Content-Disposition: filename=\"" . $path_parts["basename"] . "\"");
                    header("Content-length: $fsize");
                    header("Cache-control: private"); //use this to open files directly
                    while (!feof($fd)) {
                        $buffer = fread($fd, 2048);
                        echo $buffer;
                    }
                }
                fclose($fd);
                exit();
            }
        }
        exit();
    }

    function crear_archivo($datos_consulta, $nombre_archivo) {


        $archivo = getcwd() . "/tmp/" . $nombre_archivo;

        $string = "";

        $fp = fopen($archivo, "w+") or die("can't open file");

        foreach ($datos_consulta as $key => $value) {
            $string = trim($value['codigo_producto']) . "," . $value['cantidad_solicitada'] . "\n";
            $data = array(trim($value['codigo_producto']), $value['cantidad_solicitada'], $value['codigo_proveedor']);
            $data = join(",", $data) . "\n";
            fputs($fp, $data);
        }

        fclose($fp);

        if (file_exists($archivo) == 1) {
            $sw = true;
        } else {
            $sw = false;
        }

        return array('continuar' => $sw, 'archivo' => $archivo);
    }

    function validar_contenido_archivo_plano($contenido) {

        $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $productos_validos = array();
        $productos_invalidos = array();


        foreach ($contenido as $key => $value) {

            $codigo_producto = empty($value[0]) ? '' : trim($value[0]);
            $cantidad_solicitada = empty($value[1]) ? 0 : trim($value[1]);
            $codigo_proveedor = empty($value[2]) ? '' : trim($value[2]);

            $producto = array('codigo_producto' => $codigo_producto, 'cantidad_solicitada' => $cantidad_solicitada, 'codigo_proveedor' => $codigo_proveedor);

            $existe_producto = $sql->validar_producto($codigo_producto);

            if (count($existe_producto) > 0 && $cantidad_solicitada > 0) {
                array_push($productos_validos, $producto);
            } else {
                array_push($productos_invalidos, $producto);
            }
        }

        return array('productos_validos' => $productos_validos, 'productos_invalidos' => $productos_invalidos);
    }

    function validar_costos_productos_archivo_plano($empresa_id, $codigo_proveedor_id, $numero_orden, $productos) {

        $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $productos_validos = array();
        $productos_invalidos = array();

        foreach ($productos as $key => $value) {

            $lista_productos = $sql->listar_productos($empresa_id, $codigo_proveedor_id, $numero_orden, $value['codigo_producto']);

            if (count($lista_productos) == 0) {
                array_push($productos_invalidos, $value);
            } else {
                $producto = $lista_productos[0];

                $value['costo'] = $producto['costo_ultima_compra'];
                $value['iva'] = $producto['iva'];
                
                if ($producto['costo_ultima_compra'] <= 0) {
                    array_push($productos_invalidos, $value);
                } else {
                    array_push($productos_validos, $value);
                }
            }
        }

        return array('productos_validos' => $productos_validos, 'productos_invalidos' => $productos_invalidos);
    }

    function Ordenes_Compras() {

        $request = $_REQUEST;
        $this->IncludeJS("TabPaneLayout");
        $this->IncludeJS("TabPaneApi");
        $this->IncludeJS("TabPane");
        $emp = SessionGetVar("DatosEmpresaAF");
        $empresa = $emp['empresa_id'];

        IncludeFileModulo("Compras_Orden_Compras", "RemoteXajax", "app", "Compras_Orden_Compras");
        $this->SetXajax(array("proveedores_por_producto"), null, "ISO-8859-1");

        $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

        $this->IncludeJS("TabPaneLayout");
        $this->IncludeJS("TabPaneApi");
        $this->IncludeJS("TabPane");

        $query = "";
        $cabecera = $sql->BuscarCabecera_Preorden($empresa, '1');
        if (empty($cabecera))
            $cabecera = $sql->BuscarCabecera_Preorden($empresa, '0');

        if ($_REQUEST['eliminar'] == '1') {
            $sql->EliminarItem_Preorden($_REQUEST, $cabecera['preorden_id']);
        }


        if ($_REQUEST['guardar'] == '1') {
            for ($i = 0; $i < $_REQUEST['registros']; $i++) {
                if ($_REQUEST[$i] != "" && is_numeric($_REQUEST['cantidad' . $i]) && is_numeric($_REQUEST['valor_unitario' . $i]) && is_numeric($_REQUEST['porc_iva' . $i]) && $_REQUEST['valor_unitario' . $i] > 0 && $_REQUEST['cantidad' . $i] > 0) {
                    $query .= "
					INSERT INTO informacion_preorden_detalle
					(
					preorden_detalle_id,
					preorden_id,
					codigo_proveedor_id,
					codigo_producto,
					cantidad,
					usuario_id,
					valor_unitario,
					porc_iva
					)
					VALUES
					(
					DEFAULT,
					'" . $cabecera['preorden_id'] . "',
					'" . $_REQUEST['codigo_proveedor_id' . $i] . "',
					'" . $_REQUEST[$i] . "',
					'" . $_REQUEST['cantidad' . $i] . "',
					'" . UserGetUID() . "',
					'" . $_REQUEST['valor_unitario' . $i] . "',
					'" . $_REQUEST['porc_iva' . $i] . "'
					); \n";
                }
            }
            $sql->Detalle_Preorden($query);
        }
        $dat = $sql->Solicitudes_Generadas_x_Rotacion($request['buscador'], $request['offset']);
        $productos_preorden = $sql->Preorden_Compra($empresa, $cabecera['preorden_id']);
        $productos = $sql->Productos_EquivalenciasRotacion($empresa, $cabecera['preorden_id']);
        $conteo = $pagina = 0;
        $conteo = $sql->conteo;
        $pagina = $sql->pagina;

        /* print_r($productos_preorden); */

        /* if(!empty($request['buscador']))
          {
          $datos=$mdl->consultarInformacionPreOrden_($request['buscador'],$request['offset']);
          $action['buscador']=ModuloGetURL('app','Compras_Orden_Compras','controller','Ordenes_Compras');

          $conteo= $mdl->conteo;
          $pagina= $mdl-> pagina;
          } */
        /* print_r($productos); */

        $action['paginador'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Ordenes_Compras", array("buscador" => $request['buscador']));
        $action['detalle'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "DetallePreorden");
        $action['crear_compras'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Creacion_ComprasFinal", array("preorden_id" => $cabecera['preorden_id']));
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
        $act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
        $action['pre_orden'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Ordenes_Compras");

        $this->salida = $act->FormaDivididaPre_ordenC($action, $dat, $empresa, $datos, $productos, $productos_preorden, $cabecera['preorden_id'], $sql->conteo, $sql->pagina);
        return true;
    }

    function Creacion_ComprasFinal() {
        $request = $_REQUEST;

        $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $html = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
        $productos_preorden = $sql->Preorden_Compra($empresa, $cabecera['preorden_id']);
        $emp = SessionGetVar("DatosEmpresaAF");
        $empresa = $emp['empresa_id'];
        /* print_r($productos_preorden); */

        $rotacion = "
			INSERT INTO informacion_preorden_rotacion (
			preorden_id,
			cod_principio_activo,
			concentracion,
			unidad_id,
			descripcion,
			cantidad
			)
				SELECT
				'" . trim($request['preorden_id']) . "' as preorden_id,
				A.subclase_id,
				A.contenido_unidad_venta,
				A.unidad_id,
				B.descripcion||'-'||A.contenido_unidad_venta||'-'||C.descripcion as molecula,
				SUM(A.cantidad) as cantidad
				FROM  (
				SELECT 
				SUM(a.cantidad) as cantidad,
				b.subclase_id,
				upper(replace(b.contenido_unidad_venta, ' ', ''))as contenido_unidad_venta,
				d.unidad_id
				FROM
				solicitud_gerencia as a
				JOIN inventarios_productos as b ON(a.codigo_producto = b.codigo_producto)
				JOIN inv_subclases_inventarios as c ON(b.grupo_id = c.grupo_id)
				AND (b.clase_id = c.clase_id)
				AND (b.subclase_id = c.subclase_id)
				JOIN unidades as d ON (b.unidad_id = d.unidad_id)
				group by b.subclase_id,
				upper(replace(b.contenido_unidad_venta, ' ', '')),
				d.unidad_id
				UNION ALL
				SELECT
				SUM(a.cantidad) as cantidad,
				b.cod_principio_activo as subclase_id,
				upper(replace(a.concentracion, ' ', ''))as contenido_unidad_venta,
				a.unidad_id
				FROM
				solicitud_gerencia as a
				JOIN inv_med_cod_principios_activos as b ON(a.cod_principio_activo = b.cod_principio_activo)
				JOIN unidades as c ON (a.unidad_id = c.unidad_id)
				WHERE
				a.cod_principio_activo IS NOT NULL
				group by b.cod_principio_activo,
				upper(replace(a.concentracion, ' ', '')),
				a.unidad_id
				) A
				JOIN inv_med_cod_principios_activos as B ON (A.subclase_id = B.cod_principio_activo)
				JOIN unidades as C ON(A.unidad_id = C.unidad_id)
				WHERE TRUE
				GROUP BY A.subclase_id,
				A.contenido_unidad_venta,
				B.descripcion,
				C.descripcion,
				A.unidad_id;  ";
        $rotacion .= "	DELETE FROM solicitud_gerencia where TRUE;";
        $sql->EjecutarConsultas($rotacion);
        /* print_r($rotacion); */

        /* PRODUCTOS Q ESTAN EN LA PRE- ORDEN Y PASARAN A COMPRAS */
        foreach ($productos_preorden as $ll => $val) {
            foreach ($val as $ll2 => $val2) {
                $NumCompras = $sql->SeleccionarMaxcompras_ordenes_pedidos("-1", "-1");

                $query = "INSERT	INTO compras_ordenes_pedidos
								(
								orden_pedido_id,
								codigo_proveedor_id,
								empresa_id,
								fecha_orden,
								usuario_id,
								estado,
								preorden_id
								)
								VALUES
								(
								'" . ($NumCompras[0]['numero'] + 1) . "',
								'" . trim($ll2) . "',
								'" . trim($empresa) . "',
								NOW(),
								'" . UserGetUID() . "',
								'1',
								'" . trim($request['preorden_id']) . "'
								); \n";
                foreach ($val2 as $ll3 => $val3) {
                    $query .= "INSERT	INTO compras_ordenes_pedidos_detalle
								(
								orden_pedido_id,
								codigo_producto,
								numero_unidades,
								valor,
								porc_iva,
								estado,
								item_id
								)
								VALUES
								(
								'" . ($NumCompras[0]['numero'] + 1) . "',
								'" . trim($val3['codigo_producto']) . "',
								" . trim($val3['cantidad']) . ",
								" . trim($val3['valor_unitario']) . ",
								" . trim($val3['porc_iva']) . ",
								'1',
								DEFAULT
								); \n";

                    $query .= "	UPDATE informacion_preorden_detalle
										SET
										sw_unificada = '1'
										WHERE
										preorden_id = '" . trim($request['preorden_id']) . "'
										AND codigo_producto = 	'" . trim($val3['codigo_producto']) . "'
										AND codigo_proveedor_id = '" . trim($ll2) . "'; \n";
                }
                /* print_r($query); */
                $sql->EjecutarConsultas($query);
            }
        }
        $query = "  SELECT o.orden_pedido_id,  
					o.codigo_proveedor_id,
					o.empresa_id,
					o.usuario_id,
					To_char(o.fecha_orden,'dd-mm-yyyy') as fecha_registro,
					s.nombre,
					e.razon_social,
					e.tipo_id_tercero as tipo_id_empresa,
					e.id as id_empresa,
					ter.nombre_tercero,
					ter.tipo_id_tercero,
					ter.tercero_id,
					ter.direccion,
					ter.telefono,
					o.estado,
					o.observacion,
					u.codigo_unidad_negocio,
					u.imagen,
					u.descripcion
					FROM        compras_ordenes_pedidos  o
					LEFT JOIN unidades_negocio as u ON (o.codigo_unidad_negocio = u.codigo_unidad_negocio),
					system_usuarios  s,
					empresas e,
					terceros_proveedores p,
					terceros ter
					WHERE       o.empresa_id=e.empresa_id
					and     o.usuario_id=s.usuario_id
					and     o.codigo_proveedor_id=p.codigo_proveedor_id
					and     p.tipo_id_tercero=ter.tipo_id_tercero
					and     p.tercero_id=ter.tercero_id 
					AND     o.sw_unificada='0' 
					AND	o.empresa_id = '" . trim($empresa) . "'
					AND	o.preorden_id = '" . trim($request['preorden_id']) . "'
					order by o.orden_pedido_id; ";
        $OrdenesCompra = $sql->EjecutarConsultas($query);
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Ordenes_Compras");
        $this->salida = $html->FormaMensaje_preorden($action, $OrdenesCompra);
        return true;
    }

    function subir_plano_rotacion() {

        IncludeFileModulo("Compras_Orden_Compras", "RemoteXajax", "app", "Compras_Orden_Compras");
        $this->SetXajax(array("mostrar_detalle_orden_compra"), null, "ISO-8859-1");

        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $view = AutoCarga::factory("Compras_OrdenesPedidosHTML", "views", "app", "Compras_Orden_Compras");
        $sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");

        $request = $_REQUEST;

        $datos_empresa = SessionGetVar("DatosEmpresaAF");
        $empresa_id = $datos_empresa['empresa_id'];


        $productos_validos = array();
        $productos_invalidos = array();

        //echo "<pre>";
        //print_r($request);
        //print_r($datos_empresa);
        //echo "</pre>";
        //Cargar Archivo
        if ($request['accion'] == 'subir_archivo') {

            $cargar_archivo = $this->cargar_archivo_plano();

            $contenido = $cargar_archivo['datos'];

            if (count($contenido) > 0) {

                $validacion_archivo = $this->validar_contenido_archivo_plano($contenido);
                $validacion_proveedor = $this->validar_proveedor($validacion_archivo['productos_validos']);

                $productos_validos = $validacion_proveedor['productos_validos'];
                $productos_invalidos = array_merge($validacion_archivo['productos_invalidos'], $validacion_proveedor['productos_invalidos']);

                $productos_unficados = $this->unificar_productos_proveedor($productos_validos);

                $productos_validos = array();
                foreach ($productos_unficados as $codigo_proveedor => $lista_productos) {
                    $validacion_costos_archivo = $this->validar_costos_productos_archivo_plano($empresa_id, $codigo_proveedor, 0, $lista_productos);

                    $productos_validos = array_merge($productos_validos, $validacion_costos_archivo['productos_validos']);
                    $productos_invalidos = array_merge($productos_invalidos, $validacion_costos_archivo['productos_invalidos']);
                }

                $productos_unficados = $this->unificar_productos_proveedor($productos_validos);



                $numeros_ordenes = array();

                foreach ($productos_unficados as $codigo_proveedor => $productos) {
                    $numero_orden = $sql->SeleccionarMaxcompras_ordenes_pedidos("-1", "-1");
                    $numero_orden = ($numero_orden[0]['numero'] + 1);

                    $orden_compra = $sql->IngresarOrdenCompra($numero_orden, $codigo_proveedor, $empresa_id);

                    if ($orden_compra) {

                        array_push($numeros_ordenes, array('numero_orden' => $numero_orden, 'codigo_proveedor' => $codigo_proveedor));

                        foreach ($productos as $key => $value) {
                            $token = $sql->AgregarItemOC($numero_orden, $empresa_id, $value['codigo_producto'], $value['cantidad_solicitada'], $value['costo'], $value['iva']);

                            if (!$token) {
                                array_push($productos_invalidos, $value);
                            }
                        }
                    } else {
                        $productos_invalidos = array_merge($productos_invalidos, $productos);
                    }
                }

                //echo "<pre>";
                //print_r(array('numeros_ordenes' => $numeros_ordenes, 'productos_unficados' => $productos_unficados, 'productos_invalidos' => $productos_invalidos));
                //echo "</pre>";
                //exit();
            }
        }


        $action['subir_plano'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "subir_plano_rotacion");
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
        $action['descargar_archivo'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "descargar_archivo_plano", array("productos_invalidos" => $productos_invalidos));

        $this->salida = $view->subir_plano_rotacion($action, $numeros_ordenes, $productos_invalidos);

        return true;
    }

    function validar_proveedor($lista_productos) {

        $productos_validos = array();
        $productos_invalidos = array();

        foreach ($lista_productos as $key => $value) {

            if (empty($value['codigo_proveedor']) || $value['codigo_proveedor'] == 0) {
                array_push($productos_invalidos, $value);
            } else {
                array_push($productos_validos, $value);
            }
        }
        return array('productos_validos' => $productos_validos, 'productos_invalidos' => $productos_invalidos);
    }

    function unificar_productos_proveedor($lista_productos) {


        $listado = array();
        foreach ($lista_productos as $key => $value) {
            $listado[$value['codigo_proveedor']][] = $value;
        }
        return $listado;
    }

}
?>
