<?php

/**
 * @package DUANA & CIA
 * @version 1.0 $Id: app_ESM_AdminPqrs_controller.php,v 1.0 $
 * @copyright DUANA & CIA JUN-2012
 * @author R.O.M.A
 */
/**
 * Clase Control: ESM_AdminPqrs
 * Responsabilidad: Clase encargada del control de llamado de metodos en el modulo
 * */
/* if (!IncludeClass('LogMenus')) {
  die(MsgOut("Error al incluir archivo", "LogMenus"));
  } */

IncludeClass("ClaseHTML");
IncludeClass("ClaseUtil");

class app_ESM_AdminPqrs_controller extends classModulo {

    /**
     * @var array $action  Vector donde se almacenan los links de la aplicacion
     */
    var $action = array();

    /**
     * @var array $request Vector donde se almacenan los datos pasados por request
     */
    var $request = array();

    /*     * **********************************************************
     * Constructor de la clase
     * ********************************************************** */

    function app_ESM_AdminPqrs_controller()
    {
        
    }

    /*     * ********************************************************** 
      Funcion principal del modulo
      @return boolean
     * ********************************************************** */

    function main()
    {

        $request = $_REQUEST;

        /*         * *registro log x acceso en menus** */
        /* if($request['module'] || $request['menuOp'])
          {
          $moduleLog = $request['module'];
          $menuLog = $request['menuOp'];
          $VarClass = new LogMenus();
          $LogApp = $VarClass->RegistrarAccesoAplicacion($moduleLog,$menuLog);
          } */
        /*         * *************************************** */

        $url[0] = 'app';                                      //Tipo de Modulo
        $url[1] = 'ESM_AdminPqrs';                //Nombre del Modulo
        $url[2] = 'controller';                     //tipo controller...
        $url[3] = 'MenuOp';                      //Metodo.
        $url[4] = 'datos';         //vector de $_request.
        $arreglo[0] = 'EMPRESAS';     //Sub Titulo de la Tabla
        //Generar busqueda de Permisos SQL
        $permiso = AutoCarga::factory("Permisos", "", "app", "ESM_AdminPqrs");
        $datos = $permiso->BuscarPermisos();

        // Menu de empresas con permiso 
        $forma = gui_theme_menu_acceso("MODULO GESTION PQRS", $arreglo, $datos, $url, ModuloGetURL('system', 'Menu'));
        $this->salida = $forma;

        return true;
    }

    /*     * *************************************************************
     * Funcion de menu de opciones
     * ************************************************************* */

    function MenuOp()
    {
        /* Crear el Menu de opciones */
        $request = $_REQUEST;
        if ($request['datos']['empresa_id'])
            SessionSetVar("empresa_id", $request['datos']['empresa_id']);

        $empresa = SessionGetVar("empresa_id");

        // print_r($empresa);	
        $vista = AutoCarga::factory("ESM_AdminPqrs_MenuHTML", "views", "app", "ESM_AdminPqrs");

        //$action['volver'] = ModuloGetURL("app","ESM_AdminPqrs","controller","main")."&datos[empresa_id]=".$request['datos']['empresa_id']."";
        $action['volver'] = ModuloGetURL("app", "ESM_AdminPqrs", "controller", "main");


        $sql = Autocarga::factory("DMLs_pqrs", "", "app", "ESM_AdminPqrs");

        $usuarioResponsable = $sql->buscarUsuarioResponsable(UserGetUID());

        //echo print_r($usuarioResponsable);

        SessionSetVar("buscar_propios", null);

        if (count($usuarioResponsable) > 0)
        {
            SessionSetVar("responsable", $usuarioResponsable);
        }
        else
        {
            SessionSetVar("responsable", null);
        }

        $this->salida = $vista->MenuOpciones($action, $empresa);

        return true;
    }

    /*     * *************************************************************
     * Funcion crear caso pqrs
     * ************************************************************* */

    function Crear_caso()
    {
        $request = $_REQUEST;

        IncludeFileModulo("Remotos_repositorio", "RemoteXajaX", "app", "ESM_AdminPqrs");

        $this->IncludeJS('RemoteXajaX/Buscador.js', 'app', 'ESM_AdminPqrs');
        $this->IncludeJS('RemoteXajaX/ESM_AdminPqrs.js', 'app', 'ESM_AdminPqrs');


        $this->SetXajax(array("UsuariosFarmacia", "GetUserFarm", "formularioServicioAlCliente","autoCompletado","formularioLogistica", "obtenerCategoriaPorArea", "obtenerPrioridadPorArea", "buscarCliente", "buscarProducto", "buscarClienteLogistica"), null, "ISO-8859-1");

        $empresa_id = $request['datos']['empresa_id'];
        //print_r($empresa_id);

        SessionSetVar("empresa_permiso", $request['datos']['empresa_id']);

        $sql = Autocarga::factory("Permisos", "", "app", "ESM_AdminPqrs");
        $sqlPqrs = Autocarga::factory("DMLs_pqrs", "", "app", "ESM_AdminPqrs");

        $vista = AutoCarga::factory("ESM_AdminPqrs_MenuHTML", "views", "app", "ESM_AdminPqrs");

        $farmacias = $sql->BuscarBodegas($empresa_id);
        $razonS = $sql->ListarEmpresas($empresa_id);
        $categoria = $sql->ListarCategorias();
        $estadoCaso = $sql->EstadoCasos();
        $fuerzas = $sql->ListadoFuerzas();
        $consec = $sql->SerialCaso();

        $areasPorEmpresa = $sqlPqrs->obtenerAreasPorEmpresa($empresa_id);


        $action['volver'] = ModuloGetURL("app", "ESM_AdminPqrs", "controller", "MenuOp") . "&datos[empresa_id]=" . $request['datos']['empresa_id'] . "";
        $action['crea_caso'] = ModuloGetURL("app", "ESM_AdminPqrs", "controller", "Registrar_Caso");

        $this->salida = $vista->FormaCrearCaso($action, $farmacias, $empresa_id, $razonS, $categoria, $estadoCaso, $fuerzas, $consec, $areasPorEmpresa);

        return true;
    }

    /*     * *************************************************************
     * Funcion: registrar caso pqrs
     * ************************************************************* */

    function Registrar_Caso()
    {
        $request = $_REQUEST;
     
   
        $vista = AutoCarga::factory("ESM_AdminPqrs_MenuHTML", "views", "app", "ESM_AdminPqrs");
        $sql = Autocarga::factory("DMLs_pqrs", "", "app", "ESM_AdminPqrs");
        $action['volver'] = ModuloGetURL("app", "ESM_AdminPqrs", "controller", "MenuOp") . "&datos[empresa_id]=" . $request['empresa'];

        $grabar = $sql->insertar_caso($request, $_FILES);

        if (!$grabar['insert'])
        {
            $mensaje = " ERROR EN LA GRABACION DEL REGISTRO";
        }
        else
        {
            $mensaje = " CASO REGISTRADO CORRECTAMENTE CASO No. ".$grabar['codigo'];
        }

        $this->salida = $vista->FormaMensajeModulo($action, $mensaje);

        return true;
    }

    /*     * *************************************************************
     * Funcion: Consultas informacion pqrs
     * ************************************************************* */

    function Actualizacion_Pqrs()
    {
        $request = $_REQUEST;
        $empresa_id = $request['datos']['empresa_id'];

        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        if (isset($request["datos"]["consulta_propios"]))
        {
            SessionSetVar("buscar_propios", true);
        }

        $vista = AutoCarga::factory("ESM_AdminPqrs_MenuHTML", "views", "app", "ESM_AdminPqrs");
        $sql = Autocarga::factory("DMLs_pqrs", "", "app", "ESM_AdminPqrs");

        $action['volver'] = ModuloGetURL("app", "ESM_AdminPqrs", "controller", "MenuOp") . "&datos[empresa_id]=" . $request['datos']['empresa_id'];
        $action['paginador'] = ModuloGetURL('app', 'ESM_AdminPqrs', 'controller', 'Actualizacion_Pqrs', array("buscador" => $request['buscador']));
        $action['buscador'] = ModuloGetURL("app", "ESM_AdminPqrs", "controller", "Actualizacion_Pqrs");

        if ($request['buscador'])
        {
            $permiso = AutoCarga::factory("Permisos", "", "app", "ESM_AdminPqrs");
            $permisos = $permiso->BuscarPermisos();


            $datosPqrsAct = $sql->Listar_datosPqrsAct($request['buscador'], $request['offset'], $permisos);
            //print_r($datosPqrsAct);
        }


        $this->salida = $vista->Listado_pqrsAct($action, $request, $datosPqrsAct, $sql->conteo, $sql->pagina);
        return true;
    }

    /*     * *************************************************************
     * Funcion: LLamado vista actualizar Casos de Pqrs
     * ************************************************************* */

    function ActualizarCasos()
    {

        $this->IncludeJS('RemoteXajaX/ESM_AdminPqrs.js', 'app', 'ESM_AdminPqrs');
        $request = $_REQUEST;
        $encabezado["empresa"] = $request['datos']['empresa_id'];
        $encabezado["caso"] = $request['caso'];
        $encabezado["cliente"] = $request['cliente'];
        $encabezado["resp"] = $request['responsable'];
        $encabezado["categoria"] = $request['categoria'];
        $encabezado["usuario"] = $request['usuario'];
        $encabezado["estadoCaso"] = $request["estado_caso"];
        $encabezado["calificacion"] = $request["calificacion"];


        $vista = AutoCarga::factory("ESM_AdminPqrs_MenuHTML", "views", "app", "ESM_AdminPqrs");
        $sql = Autocarga::factory("DMLs_pqrs", "", "app", "ESM_AdminPqrs");

        $action['volver'] = ModuloGetURL("app", "ESM_AdminPqrs", "controller", "Actualizacion_Pqrs") . "&datos[empresa_id]=" . $request['datos']['empresa_id'];

        $datos_caso = $sql->Listar_CasosUpd($request['caso']);

        $paciente=$sql->obtenerPacienteCaso($encabezado["caso"]);

        $this->salida = $vista->FormaActCaso($action, $datos_caso, $encabezado,$paciente);

        return true;
    } 

    /*     * *************************************************************
     * Funcion: Actualizacion Casos de Pqrs en BD
     * ************************************************************* */

    function UpdateCaso()
    {
        $request = $_REQUEST;

        $numcaso = $request['caso'];
        $empresa = $request['empresa_id'];
        $observ = $request['observacionAct'];
        $codigocaso = $request["codigocaso"];
        $calificacion = "";

        if ($request['cerrar_caso'])
        {
            $cerrar = $request['cerrar_caso'];
            $calificacion = $request["calificacion"];
        }


        $vista = AutoCarga::factory("ESM_AdminPqrs_MenuHTML", "views", "app", "ESM_AdminPqrs");
        $sql = Autocarga::factory("DMLs_pqrs", "", "app", "ESM_AdminPqrs");

        $action['volver'] = ModuloGetURL("app", "ESM_AdminPqrs", "controller", "MenuOp") . "&datos[empresa_id]=" . $request['empresa_id'];

        $actualizar = $sql->ActualizarCasoPqrs($numcaso, $empresa, $observ, $cerrar, $calificacion, $codigocaso);

        if (!$actualizar)
        {
            $mensaje = "ERROR EN LA ACTUALIZACION, VERIFICAR DATOS.";
        }
        else
        {
            $mensaje = "CASO ACTUALIZADO SATISFACTORIAMENTE.";
        }

        $this->salida = $vista->FormaMensajeModulo($action, $mensaje);

        return true;
    }

}
