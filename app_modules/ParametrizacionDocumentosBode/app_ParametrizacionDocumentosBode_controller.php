<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: app_ParametrizacionDocumentosBode_controller.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  
  class app_ParametrizacionDocumentosBode_controller extends classModulo
  {
    /**
         * Constructor de la clase
        */
    function app_ParametrizacionDocumentosBode_controller(){}
    
   /**
        *  Funcion principal del modulo
        *  @return boolean
        */
    function Main()
    {
      $request = $_REQUEST;
      $parametrizacionBod = AutoCarga::factory('ParametrizacionDocumentosBodeSQL', '', 'app', 'ParametrizacionDocumentosBode');
      $action['volver'] = ModuloGetURL('system', 'Menu');
      $permisos = $parametrizacionBod->ObtenerPermisos();    
      
      $ttl_gral = "PARAMETRIZACION DE DOCUMENTOS-BODEGA";
      $titulo[0] = 'EMPRESAS';
      $url[0] = 'app';
      $url[1] = 'ParametrizacionDocumentosBode'; 
      $url[2] = 'controller';
      $url[3] = 'Menu'; 
      $url[4] = 'permiso_parametrizacionBod'; 
      $this->salida = gui_theme_menu_acceso($ttl_gral, $titulo, $permisos, $url, $action['volver']);
     
      return true;
    }
    
    /**
          *   Funcion de control para el menu inicial
          */
    function Menu()
    {
      $request = $_REQUEST;
      $action['parametrizar_busqueda_documentos'] = ModuloGetURL("app", "ParametrizacionDocumentosBode", "controller", "BuscarListadoUsuarios");
      $action['volver'] = ModuloGetURL("app", "ParametrizacionDocumentosBode", "controller", "Main");
      
      if($request['permiso_parametrizacionBod']['empresa'])
        SessionSetVar("empresa_id",$request['permiso_parametrizacionBod']['empresa']);
      
      $act = AutoCarga::factory("ParametrizacionDocumentosBodeHTML", "views", "app", "ParametrizacionDocumentosBode");
      $this->salida = $act->formaMenu($action);
      
      return true;      
    }
    
     /**
            *  Funcion que busca el listado de usuarios
            */
    function BuscarListadoUsuarios()
    {
      $this->SetXajax(array("BuscarUsuarios"),"app_modules/ParametrizacionDocumentosBode/RemoteXajax/EventosDocumentosBod.php");
      $request = $_REQUEST;
      $empresa_id = SessionGetVar("empresa_id");
      
      $action['volver'] = ModuloGetURL("app", "ParametrizacionDocumentosBode", "controller", "Menu");
      $action['asignarBusqueda'] = ModuloGetURL("app","ParametrizacionDocumentosBode","controller","AsignarBusqueda");
      
      if(!empty($request['buscar_usuarios']))
      {
        $action['paginador'] = ModuloGetURL("app", "ParametrizacionDocumentosBode", "controller", "BuscarListadoUsuarios", array("usuario_id"=>$_REQUEST['usuario_id']));
      
        $mdl = AutoCarga::factory("ParametrizacionDocumentosBodeSQL","","app","ParametrizacionDocumentosBode");
        $buscar_usuariosperm = $mdl->ConsultarpermisosUsuarios($request['buscar_usuarios'],$request['offset'],$empresa_id);
        $action['buscar_usuarios'] = ModuloGetURL("app", "ParametrizacionDocumentosBode", "controller", "BuscarListadoUsuarios");
      }
      $act = AutoCarga::factory("ParametrizacionDocumentosBodeHTML", "views", "app", "ParametrizacionDocumentosBode");
      $this->salida = $act->formaBuscarUsuariosDocume($buscar_usuariosperm,$action,$request['buscar_usuarios'],$mdl->pagina,$mdl->conteo);
      //print_r($request);
      return true; 
    }
    
     /**
            *   Funcion que asigna a los usuarios un tipo de busqueda
            */
    function AsignarBusqueda()
    {
      $request = $_REQUEST;
      $empresa_id = SessionGetVar("empresa_id");
      $action['volver'] = ModuloGetURL("app", "ParametrizacionDocumentosBode", "controller", "BuscarListadoUsuarios");
      $action['guardarAsignacionBusqueda'] = ModuloGetURL("app", "ParametrizacionDocumentosBode", "controller", "IngresarAsignacionBusqueda",array("usuario_id"=>$_REQUEST['usuario_id']));
      
      $mdl = AutoCarga::factory("ParametrizacionDocumentosBodeSQL","","app","ParametrizacionDocumentosBode");
      $buscar_paramebusdoc = $mdl->Consultarparametrosbod($request['usuario_id'],$empresa_id);
      
      $act = AutoCarga::factory("ParametrizacionDocumentosBodeHTML", "views", "app", "ParametrizacionDocumentosBode");
      $this->salida = $act->formaAsignacionBusqueda($action,$buscar_paramebusdoc);
      
      return true; 
    }
    
     /**
            *  Funcion que ingresa la asignacion de los parametros de busqueda que puede tener un usuario
            */
    function IngresarAsignacionBusqueda()
    {
      $request = $_REQUEST;
      $empresa_id = SessionGetVar("empresa_id");
      
      $mdl = AutoCarga::factory("ParametrizacionDocumentosBodeSQL","","app","ParametrizacionDocumentosBode");
      $act = AutoCarga::factory("ParametrizacionDocumentosBodeHTML", "views", "app", "ParametrizacionDocumentosBode");
      
      $action['volver'] = ModuloGetURL("app", "ParametrizacionDocumentosBode", "controller", "AsignarBusqueda",array("usuario_id"=>$_REQUEST['usuario_id']));
      
      $actu_asigbusqueda = $mdl->IngresarParameBusquedaBode($request,$empresa_id);
      
      $mensaje = "EL INGRESO O ACTUALIZACION DE LOS DATOS DEL TIEMPO, SE REALIZO CORRECTAMENTE";
      if(!$actu_asigbusqueda)
      {
        $mensaje = $ing->error."<br>ERROR EN EL MOMENTO DE INGRESAR LOS DATOS ".$ing->mensajeDeError;
      } 
      
      $this->salida = $act->formaMensajeInTc($action,$mensaje);
      
      return true; 
    }
  }
?>