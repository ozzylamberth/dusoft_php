<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: app_ParametrizacionTAO_controller.php,v 1.1 2010/04/27 15:54:34 hugo Exp $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author
  */
  
  class app_ParametrizacionTAO_controller extends classModulo
  {
    /**
    * Constructor de la clase
    */
    function app_ParametrizacionTAO_controller(){}
    
    /**
    * Funcion principal del modulo
    * @return boolean
    */
    function Main()
    {
      $request = $_REQUEST;
      $parametrizacion = AutoCarga::factory('ParametrizacionTAOSQL', 'classes', 'app', 'ParametrizacionTAO');
      $action['volver'] = ModuloGetURL('system', 'Menu');
      $permisos = $parametrizacion->ObtenerPermisos();    
      
      $ttl_gral = "PARAMETRIZACION TAO";
      $titulo[0] = 'EMPRESAS';
      $url[0] = 'app';
      $url[1] = 'ParametrizacionTAO'; 
      $url[2] = 'controller';
      $url[3] = 'Menu'; 
      $url[4] = 'permiso_parametrizacionTAO'; 
      $this->salida = gui_theme_menu_acceso($ttl_gral, $titulo, $permisos, $url, $action['volver']);
      return true;
    }
    
    /**
    * Funcion de control para el Menu Principal
    */
    function Menu()
    {
      $request = $_REQUEST;
      $action['parametrizar_busqueda'] = ModuloGetURL("app", "ParametrizacionTAO", "controller", "BusquedaParametrizacion");
      $action['volver'] = ModuloGetURL("app", "ParametrizacionTAO", "controller", "Main");
      
      if($request['permiso_parametrizacionTAO']['empresa'])
        SessionSetVar("empresa_id",$request['permiso_parametrizacionTAO']['empresa']);
      
      $act = AutoCarga::factory("ParametrizacionTAOHTML", "views", "app", "ParametrizacionTAO");
      $this->salida = $act->formaMenu($action);
      
      return true;      
    }
    
    
    /**
    * Funcion para relizar la busqueda de medicamentos
    */
    function BusquedaParametrizacion()
    {
      IncludeFileModulo('ParametrizacionTAO','RemoteXajax','app','ParametrizacionTAO');
      $this->SetXajax(array("AsignarTao"),null,'ISO-8859-1');
      
      $request = $_REQUEST;
      $descripcion_medicamento = null;
      $codigo_medicamento = null;
      
      $empresa_id = SessionGetVar("empresa_id");
      $pct = AutoCarga::factory("ParametrizacionTAOSQL", "classes", "app", "ParametrizacionTAO");
      $mdl = AutoCarga::factory("ParametrizacionTAOHTML", "views", "app", "ParametrizacionTAO");
      
      if($request["buscar"])
      {
          $descripcion_medicamento = $request["descripcion"];
          $codigo_medicamento = $request["codigo"];
      }
      $medicamentos = $pct->ConsultarMedicamentos($empresa_id, $descripcion_medicamento,$codigo_medicamento,$request['offset']);
      $action['volver'] = ModuloGetURL("app", "ParametrizacionTAO", "controller", "Menu");
      $action['parametrizar_busqueda'] = ModuloGetURL("app", "ParametrizacionTAO", "controller", "BusquedaParametrizacion");
      $action['paginador'] = ModuloGetURL("app","ParametrizacionTAO","controller","BusquedaParametrizacion");
      
      $this->salida = $mdl->FormaBuscarMedicamentos($action,$medicamentos,$empresa_id,$pct->conteo,$pct->pagina);
      
      
      return true;
    }
  }
 ?>