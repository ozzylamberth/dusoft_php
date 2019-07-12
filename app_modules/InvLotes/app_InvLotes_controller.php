<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: app_InvTraslados_controller.php,v 1.1 2012/01/24 15:54:34 hugo Exp $Revision: 1.1 $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Manuel Saenz Grijalba
 */
class app_InvLotes_controller extends classModulo {

    /**
     * Constructor de la clase
     */
    function app_InvLotes_controller()
    {
        
    }

    /**
     * Funcion principal del modulo
     * @return boolean
     */
    function Main()
    {
        $request = $_REQUEST;
        $parametrizacion = AutoCarga::factory('InvTrasladosSQL', 'classes', 'app', 'InvLotes');
        $action['volver'] = ModuloGetURL('system', 'Menu');
        $permisos = $parametrizacion->ObtenerPermisos();

        $ttl_gral = "TRASLADOS EXISTENCIAS";
        $titulo[0] = 'EMPRESAS';
        $url[0] = 'app';
        $url[1] = 'InvLotes';
        $url[2] = 'controller';
        $url[3] = 'Bodegas';
        $url[4] = 'permiso_invTraslados';
        $this->salida = gui_theme_menu_acceso($ttl_gral, $titulo, $permisos, $url, $action['volver']);
        return true;
    }

    function Bodegas()
    {
        $request = $_REQUEST;
        $pct = AutoCarga::factory('InvTrasladosSQL', 'classes', 'app', 'InvLotes');
        $mdl = AutoCarga::factory("InvTrasladosHTML", "views", "app", "InvLotes");
        if ($_REQUEST['permiso_invTraslados']['empresa'] != "")
        {
            $_SESSION['empresa_bod'] = $_REQUEST['permiso_invTraslados']['empresa'];
        }
        $empresa_id = $_SESSION['empresa_bod'];

        $bodegas = $pct->ObtenerBodegas($empresa_id);
        $action['volver'] = ModuloGetURL("app", "InvLotes", "controller", "Main");
        $action['busqueda'] = ModuloGetURL("app", "InvLotes", "controller", "BusquedaProductos");
        $this->salida = $mdl->formaBodega($action, $bodegas);
        return true;
    }

    /**
     * Funcion funcion para el control de la busqueda de medicamentos
     *
     */
    function BusquedaProductos()
    {
        $request = $_REQUEST;
        $pct = AutoCarga::factory('InvTrasladosSQL', 'classes', 'app', 'InvLotes');
        $mdl = AutoCarga::factory("InvTrasladosHTML", "views", "app", "InvLotes");
        $empresa_id = SessionGetVar("empresa_bod");
        $sw = 1;
        if (!empty($request["nomProd"]) || !empty($request["codigo"]))
        {
            $descripcion_medicamento = $request["nomProd"];
            $codigo_medicamento = $request["codigo"];
            $param = array();
            $param["nomProd"] = $descripcion_medicamento;
            $param["codigo"] = $codigo_medicamento;
            $buscarMedicamento = $pct->BuscarMedicamento($empresa_id, $request['offset'], $codigo_medicamento, $descripcion_medicamento, $request['codBod'], $request['cu']);
            $sw = 0;
            //print_r($buscarMedicamento);
        }
        $action['volver'] = ModuloGetURL("app", "InvLotes", "controller", "Bodegas");
        $action['parametrizar_busqueda'] = ModuloGetURL("app", "InvLotes", "controller", "BusquedaProductos");
        $action['paginador'] = ModuloGetURL("app", "InvLotes", "controller", "BusquedaProductos", $param);
        $action['buscarporid'] = ModuloGetURL("app", "InvLotes", "controller", "BusquedaProductosPorId");
        $this->salida = $mdl->BuscarMedicamentos($action, $empresa_id, $buscarMedicamento, $pct->conteo, $pct->pagina, $request, $sw);


        return true;
    }

    /**
     * Funcion funcion para el control de la busqueda de medicamentos por codigo
     *
     */
    function BusquedaProductosPorId()
    {
        $request = $_REQUEST;
        
        /*print_r($_POST);
        echo "<br><br><br>";
        print_r($_GET);
        echo "<br><br><br>";
        print_r($request);
        echo "<br><br><br>";
        print_r($_SESSION);
        echo "<br><br><br>";*/
        
        IncludeFileModulo("InvLotes", "RemoteXajax", "app", "InvLotes");
        $this->SetXajax(array("EditarFecha"), null, "ISO-8859-1");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");
        
        $pct = AutoCarga::factory('InvTrasladosSQL', 'classes', 'app', 'InvLotes');
        $mdl = AutoCarga::factory("InvTrasladosHTML", "views", "app", "InvLotes");
        $codigo_medicamento = $request['codPro'];
        $empresa_id = SessionGetVar("empresa_bod");
        $buscarMedicamentoPorCodigo = $pct->BuscarMedicamentoPorId($empresa_id, $codigo_medicamento, $request['codBod'], $request['cu']);

        $exi_bod = $pct->Exist_Bod_general($codigo_medicamento, $empresa_id, $request['codBod'], $request['cu']);
        //print_r($exi_bod);

        $action['volver'] = ModuloGetURL("app", "InvLotes", "controller", "BusquedaProductos", array("codBod" => $request['codBod'], "cu" => $request['cu']));
        $action['insertarcambios'] = ModuloGetURL("app", "InvLotes", "controller", "InsertarCambios");
        $action['guardarnuevaexistenciabodega'] = ModuloGetURL("app", "InvLotes", "controller", "GuardarExistenciaBodega");
        $this->salida = $mdl->ModificacionSaldos($action, $request, $buscarMedicamentoPorCodigo, $exi_bod['existencia']);

        return true;
    }
    
    /**
     * Funcion para guardar una nueva existencia en bodega
     *
     */
    function GuardarExistenciaBodega()
    {
        $request = $_REQUEST;
        
        /*print_r($_POST);
        echo "<br><br><br>";
        print_r($_GET);
        echo "<br><br><br>";
        print_r($request);
        echo "<br><br><br>";
        print_r($_SESSION);
        echo "<br><br><br>";*/
        
        $empresa_id = SessionGetVar("empresa_bod");
        $centro_utilidad = $request['cod_centro_utilidad'];
        $codigo_producto = $request['cod_producto'];
        $bodega = $request['cod_bodega'];
        $fecha_vencimiento = $request['nueva_fecha_vencimiento'];
        $lote = $request['nuevo_lote'];
        
        $sql = AutoCarga::factory('InvTrasladosSQL', 'classes', 'app', 'InvLotes');
        
        $sql->GuardarExistenciaBodega($empresa_id, $centro_utilidad, $codigo_producto, $bodega, $fecha_vencimiento, $lote);
        
        $consulta = ModuloGetURL('app', 'InvLotes', 'controller', 'BusquedaProductosPorId', array("codPro" => $codigo_producto, "codBod" => $bodega, "cu" => $centro_utilidad));

        header('Location: ' . $consulta . '');
    }

    /**
     * Funcion funcion para el control de la insercion de datos
     *
     */
    function InsertarCambios()
    {
        $request = $_REQUEST;
        //print_r($request);
        $empresa_id = SessionGetVar("empresa_bod");
        $pct = AutoCarga::factory('InvTrasladosSQL', 'classes', 'app', 'InvLotes');
        $mdl = AutoCarga::factory("InvTrasladosHTML", "views", "app", "InvLotes");
        $pct->ActualizarSaldos($request);
        $codigo_medicamento = $request['codPro'][0];
        $action['buscarporid'] = ModuloGetURL("app", "InvLotes", "controller", "BusquedaProductosPorId", array("codBod" => $request['codBod'], "cu" => $request['cu']));
        $this->salida = $mdl->MensajeExito($action, $codigo_medicamento);

        return true;
    }

}

?>
