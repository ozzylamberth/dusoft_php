<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: app_VentaFarmacia_controller.php,v 1.1 2010/06/03 20:43:44 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Hugo F  Manrique
 */

/**
 * Clase Control: VentaFarmacia
 * Clase encargada del control de llamado de metodos en el modulo
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 1.1 $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Hugo F  Manrique
 */
class app_VentaFarmacia_controller extends classModulo {

    /**
     * Constructor de la clase
     */
    function app_VentaFarmacia_controller() {

    }

    /**
     *  Funcion principal del modulo
     *  @return boolean
     */
    function main() {
        $request = $_REQUEST;
        $contratacion = AutoCarga::factory('AdministracionFarmaciaSQL', '', 'app', 'VentaFarmacia');
        $permisos = $contratacion->ObtenerPermisos();

        $ttl_gral = "ADMINISTRACIÒN DE FARMACIA";
        $mtz[0] = 'FARMACIAS';
        $mtz[1] = 'CENTRO DE UTILIDAD';
        $mtz[2] = 'DEPARTAMENTO';
        $mtz[3] = 'BODEGA';
        $url[0] = 'app';
        $url[1] = 'VentaFarmacia';
        $url[2] = 'controller';
        $url[3] = 'VentaProductos';
        $url[4] = 'VentaFarmacia';

        $action['volver'] = ModuloGetURL('system', 'Menu');
        $this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
        return true;
    }

    /**
     * Funcion de control donde se muestra la busqueda de productos
     *
     * @return boolean
     */
    function VentaProductos() {
        $request = $_REQUEST;
        //print_r($request);

        if ($request['VentaFarmacia'])
            SessionSetVar("DatosEmpresaAF", $request['VentaFarmacia']);

        IncludeFileModulo("VentaProductos", "RemoteXajax", "app", "VentaFarmacia");
        $this->SetXajax(array("BuscarProductos", "ValidarDatosProductoVenta", "MostrarDetallePedido", "EliminarTemporal", "EliminarDocumento", "DatosTercero", "BuscarTercero"), null, "ISO-8859-1");

        $mdl = AutoCarga::factory("AdministracionFarmaciaSQL", "classes", "app", "VentaFarmacia");
        $vnt = AutoCarga::factory("VentaFarmaciaSQL", "classes", "app", "VentaFarmacia");
        $frmcontra = AutoCarga::factory("AdministracionFarmaciaHTML", "views", "app", "VentaFarmacia");

        $empresa = SessionGetVar("DatosEmpresaAF");

        $documento = $vnt->ObtenerTemporal($empresa['bodegas_doc_id'], UserGetUID());
        $tipos_documentos = $mdl->ConsultarTipoId();
        $action['volver'] = ModuloGetURL("app", "VentaFarmacia", "controller", "main");
        $action['tercero'] = ModuloGetURL("app", "VentaFarmacia", "controller", "IngresoTercero");

        $this->salida = $frmcontra->FormaBuscarProductosVenta($action, $empresa, $documento, $tipos_documentos);

        return true;
    }

    /**
     * Funcion de control para el ingreso de la informacion del tercero
     *
     * @return boolean
     */
    function IngresoTercero() {
        $request = $_REQUEST;
        $request['usuario_id'] = UserGetUID();

        $vnt = AutoCarga::factory("VentaFarmaciaSQL", "classes", "app", "VentaFarmacia");
        $empresa = SessionGetVar("DatosEmpresaAF");

        $cajas = $vnt->ObtenerPermisosCajas($empresa);
        if (!empty($cajas)) {
            if (empty($request['retorno'])) {
                $rst = $vnt->IngresarTercero($request, $empresa);
                if (!$rst) {
                    $frm = AutoCarga::factory("AdministracionFarmaciaHTML", "views", "app", "VentaFarmacia");

                    $action['volver'] = ModuloGetURL("app", "VentaFarmacia", "controller", "VentaProductos", array("bodega" => $bod, "bodega_descrip" => $bodegades));
                    $mensaje = "HA OCURRIDO UN ERROR <br>" . $vnt->mensajeDeError;
                    $this->salida .= $frm->FormaMensajeModulo($action, $mensaje);
                    return true;
                }
            }

            $empresa['documento'] = $request['documento'];
            $empresa['tercero_id'] = $request['tercero_id'];
            $empresa['tipo_id_tercero'] = $request['tipo_id_tercero'];

            SessionSetVar("DatosEmpresaAF", $empresa);

            $url[0] = 'app';
            $url[1] = 'VentaFarmacia';
            $url[2] = 'controller';
            $url[3] = 'RealizarPagos';
            $url[4] = 'Caja';
            $url[5] = $rq;

            $mtz[0] = 'EMPRESA';
            $mtz[1] = 'CENTRO UTILIDAD';
            $mtz[2] = 'CAJA';

            $action = ModuloGetURL('app', 'VentaFarmacia', 'controller', "VentaProductos");
            $this->salida.= gui_theme_menu_acceso('CAJAS', $mtz, $cajas, $url, $action);
        } else {
            $frm = AutoCarga::factory("AdministracionFarmaciaHTML", "views", "app", "VentaFarmacia");
            $action['volver'] = ModuloGetURL('app', 'VentaFarmacia', 'controller', "VentaProductos");

            $mensaje = "ESTE USUARIO NO POSEE CAJAS ASOCIADAS";
            $this->salida .= $frm->FormaMensajeModulo($action, $mensaje);
        }
        return true;
    }

    /**
     * Funcion de control para la solicitud de la informacion de los pagos
     *
     * @return boolean
     */
    function RealizarPagos() {
        $request = $_REQUEST;
        $request['usuario_id'] = UserGetUID();
        if (!empty($request['Caja']))
            SessionSetVar("DatosCajaDrogueria", $request['Caja']);

        $vnt = AutoCarga::factory("VentaFarmaciaSQL", "classes", "app", "VentaFarmacia");
        IncludeFileModulo("VentaProductos", "RemoteXajax", "app", "VentaFarmacia");
        $this->SetXajax(array("RealizarPago", "EvaluarDatos", "IngresarPagos"), null, "ISO-8859-1");

        $empresa = SessionGetVar("DatosEmpresaAF");
        $caja = SessionGetVar("DatosCajaDrogueria");

        $cajas = $vnt->ObtenerReciboSinCuadre($empresa, $caja);
        if (!empty($cajas)) {
            $frm = AutoCarga::factory("AdministracionFarmaciaHTML", "views", "app", "VentaFarmacia");
            $action['volver'] = ModuloGetURL('app', 'VentaFarmacia', 'controller', "IngresoTercero", array("retorno" => "1"));

            $mensaje = "EXISTEN RECIBOS DE OTROS USUARIOS SIN CUADRAR!!";
            $this->salida .= $frm->FormaMensajeModulo($action, $mensaje);
            return true;
        }

        $cjs = AutoCarga::factory('CajaHTML', 'views', 'app', 'VentaFarmacia');

        $request['documento'] = $empresa['documento'];
        $datos = $vnt->ObtenerProductosTemporal($empresa['documento'], $empresa);
        $pagos = $vnt->ObtenerInformacionPagosTemp($empresa['documento']);

        $action['volver'] = ModuloGetURL("app", "VentaFarmacia", "controller", "VentaProductos");
        $action['pagar'] = ModuloGetURL("app", "VentaFarmacia", "controller", "IngresarPagos");
        $this->salida .= $cjs->FormaPagos($action, $request, $datos, $pagos, $empresa);

        return true;
    }

    /**
     * Funcion de control para el ingreso de la informacion del pago y la creacion de la
     * factura
     *
     * @return boolean
     */
    function IngresarPagos() {
        $request = $_REQUEST;
        //print_r($request);

        $empresa = SessionGetVar("DatosEmpresaAF");
        $caja = SessionGetVar("DatosCajaDrogueria");

        $mdl = AutoCarga::factory("VentaFarmaciaSQL", "classes", "app", "VentaFarmacia");

        $rst = $mdl->IngresarFacturaVenta($request, $empresa, $caja);
        if ($rst === false) {
            $action['volver'] = ModuloGetURL('app', 'VentaFarmacia', 'controller', "RealizarPagos");
            $frm = AutoCarga::factory("AdministracionFarmaciaHTML", "views", "app", "VentaFarmacia");

            $mensaje = "ERROR " . $mdl->mensajeDeError;
            $this->salida .= $frm->FormaMensajeModulo($action, $mensaje);
        } else {
            $rst['empresa_id'] = $empresa['empresa_id'];
            $action['volver'] = ModuloGetURL('app', 'VentaFarmacia', 'controller', "VentaProductos");

            $cnt = AutoCarga::factory('CajaHTML', 'views', 'app', 'VentaFarmacia');
            $this->salida .= $cnt->FormaImprimirFactura($action, $rst);
        }
        return true;
    }

    /*     * *******************************************************
      Controlador Reimpresion facturas -31082012
     * ******************************************************* */

    function Reimprimir() {
        $request = $_REQUEST;
        if ($request['DatosEmpresa']) {
            $empresa = $request['DatosEmpresa']['empresa_id'];
            $centro = $request['DatosEmpresa']['centro_utilidad'];
            $bodega = $request['DatosEmpresa']['bodega'];
        }
        //print_r($bodega);

        $action['volver'] = ModuloGetURL('app', 'VentaFarmacia', 'controller', "VentaProductos");
        $action['reimpresion_final'] = ModuloGetURL('app', 'VentaFarmacia', 'controller', "ReimpresionFinal");

        $cnt = AutoCarga::factory('CajaHTML', 'views', 'app', 'VentaFarmacia');
        $this->salida .= $cnt->FormaDatosReimpresion($action, $empresa, $centro, $bodega);

        return true;
    }

    /*     * *******************************************************
      Controlador Reimpresion final -31082012
     * ******************************************************* */

    function ReimpresionFinal() {
        $request = $_REQUEST;

        //print_r($request);
        $rst = array();
        $rst['prefijo'] = $request['prefijo'];
        $rst['factura_fiscal'] = $request['factura_fiscal'];
        $rst['empresa_id'] = $request['empresa_id'];
        //print_r($rst);
        $empresa = $request['empresa_id'];
        $centro = $request['centro_utilidad'];
        $bodega = $request['bodega'];

        $cnt = AutoCarga::factory('CajaHTML', 'views', 'app', 'VentaFarmacia');
        $cls = AutoCarga::factory("ReporteFacturaSQL", "classes", "app", "VentaFarmacia");
        $verifica_fac = $cls->VerificarFactura($rst);

        if (empty($verifica_fac['documento_id'])) {
            $mensaje = "EL NUMERO DE FACTURA INGRESADO NO EXISTE :" . $rst['prefijo'] . " " . $rst['factura_fiscal'];
            $action['volver'] = ModuloGetURL('app', 'VentaFarmacia', 'controller', "Reimprimir") . "&DatosEmpresa[empresa_id]=" . $empresa . "&DatosEmpresa[centro_utilidad]=" . $centro . "&DatosEmpresa[bodega]=" . $bodega;
            $this->salida .= $cnt->Mensajes($action, $mensaje);
        } else {

            $action['volver'] = ModuloGetURL('app', 'VentaFarmacia', 'controller', "VentaProductos");
            $this->salida .= $cnt->FormaImprimirFactura($action, $rst);
        }


        return true;
    }

}

?>