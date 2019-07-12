<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: app_AutorizacionCredito_controller.php,v 1.1 2008/09/22 10:32:29 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */
  /**
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */
  
  class app_AutorizacionCredito_controller extends classModulo
  {
    /**
    * Constructor de la clase
    */
    function app_AutorizacionCredito_controller(){}
    
    /**
    * Funcion principal del modulo
    * @return boolean
    */
    function Main()
    {
      $request = $_REQUEST;
      $autoc = AutoCarga::factory('AutorizacionCreditoSQL', '', 'app', 'AutorizacionCredito');
      $action['volver'] = ModuloGetURL('system', 'Menu');
      $permisos = $autoc->ObtenerPermisos();
      
      $ttl_gral = "CUENTAS POR PAGAR";
      $titulo[0] = 'EMPRESAS';
      $url[0] = 'app';
      $url[1] = 'AutorizacionCredito'; 
      $url[2] = 'controller';
      $url[3] = 'Menu'; 
      $url[4] = 'permiso_autoc'; 
      $this->salida = gui_theme_menu_acceso($ttl_gral, $titulo, $permisos, $url, $action['volver']);
      
      return true;
      
    }
    
    /**
    * Funcion de control para el menu inicial
    *
    * @return boolean
    */
    function Menu()
    {
      $request = $_REQUEST;
      
      $action['volver'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'main');
      $action['autorizar_credito'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'AutorizarCredito');
      $action['buscar_cuen_autorizadas'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'BuscarCuentasAutorizadas');
      $action['generar_reporte'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'GenerarReporte');
      
      $act = AutoCarga::factory("AutorizacionCreditoHTML", "views", "app", "AutorizacionCredito");
      
      $this->salida = $act->formaMenu($action);
      
      return true;
    }    
    /**
    * Funcion de consulta de las cuentas permitidas para la autorizacion de credito 
    */
    function AutorizarCredito()
    {
      $request = $_REQUEST;
    
      $action['volver'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'Menu');
      $action['autorizar'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'RealizarAutorizacion');
      $action['paginador'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'AutorizarCredito',array("sw_oculto"=>$request['sw_oculto'],"buscar"=>$_REQUEST['buscar']));
      $action['buscar'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'AutorizarCredito');
      
      $act = AutoCarga::factory("AutorizacionCreditoHTML", "views", "app", "AutorizacionCredito");
      //print_r("_R ".$_REQUEST['buscar']."\n");
      //print_r("sw ".$request['sw_oculto']."\n");
      
      if($request['sw_oculto']=="consultar")
      {
        $mdl = Autocarga::factory("AutorizacionCreditoSQL", "", "app", "AutorizacionCredito");
        $datos = $mdl->ConsultarCuentasFiltro($_REQUEST['buscar'], $_REQUEST['offset']);
      }
      
      $this->salida = $act->formaAutorizarCredito($action, $datos, $mdl->pagina, $mdl->conteo, $_REQUEST['buscar'], $request);
      return true;
    }
    /**
    * Funcion que permite ingresar la informacion de la autorizacion del credito
    */
    function RealizarAutorizacion()
    {
      $request = $_REQUEST;
      $this->SetXajax(array("CalcularFormaPago"), "app_modules/AutorizacionCredito/RemoteXajax/AutoCredito.php");
      
      //print_r("id ".$request['paciente_id']);
      //print_r("tipo_id ".$request['tipo_id_paciente']);
      //print_r("ingreso ".$request['ingreso']);
      $action['volver'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'AutorizarCredito');
      $action['Registrar_Autorizacion'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'RegistrarAutorizacion');
      $mdl = AutoCarga::factory("AutorizacionCreditoSQL", "", "app", "AutorizacionCredito");
      $datos = $mdl->ConsultarClasiGrado($request);
      $destinos = $mdl->ConsultarDestinos();
      $plazos = $mdl->ConsultarPlazos();
      $responsable = $mdl->ConsultarResponsable($request);
      $grupo = $mdl->ConsultarGrupo();
      $repartos = $mdl->ConsultarRepartos();
      $tipoid = $mdl->ConsultarTiposId();
      //print_r("grado ".$datos[0]['grado']);
      //print_r("finan ".$datos[0]['clasi_financiera']);
      //print_r("cant ".count($datos));

      $act = AutoCarga::factory("AutorizacionCreditoHTML", "views", "app", "AutorizacionCredito");
      
      $this->salida .= $act->formaRealizarAutorizacion($action, $request, $datos, $destinos, $plazos, $responsable, $grupo, $repartos, $tipoid);
      return true;
    }
    /**
    * Funcion que permite almacenar la informacion de la autorizacion del credito
    */
    function RegistrarAutorizacion()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'AutorizarCredito'); 
      /*print_r("zona ".$request['zona']."\n");*/      
            
      $mdl = AutoCarga::factory("AutorizacionCreditoSQL", "", "app", "AutorizacionCredito");
      $mdl->IngresarAutorizacionCredito($request);
      
      $act = AutoCarga::factory("AutorizacionCreditoHTML", "views", "app", "AutorizacionCredito");
      $this->salida = $act->formaMensaje($action, 'LA AUTORIZACION DEL CREDITO FUE REGISTRADA');
      
      return true;
    }
    /**
    * Funcion que permite consultar las cuentas autorizadas
    */
    function BuscarCuentasAutorizadas()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'Menu');
      $action['Informacion_Autorizacion'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'ConsultarInformacionAutorizacion');
      $action['paginador'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'BuscarCuentasAutorizadas', array("buscar"=>$_REQUEST['buscar']));
      $action['buscar'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'BuscarCuentasAutorizadas');
            
      $mdl = AutoCarga::factory("AutorizacionCreditoSQL", "", "app", "AutorizacionCredito");
      $datos = $mdl->ConsultarCuentasAutorizadas($_REQUEST['buscar'], $_REQUEST['offset']);
      
      $act = AutoCarga::factory("AutorizacionCreditoHTML", "views", "app", "AutorizacionCredito");
      $this->salida = $act->formaBuscarCuentasAutorizadas($action, $datos, $mdl->pagina, $mdl->conteo, $_REQUEST['buscar'], $request);
      
      return true;
    }
    /**
    * Funcion que permite consultar el detalle de las autorizaciones de credito 
    */
    function ConsultarInformacionAutorizacion()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'BuscarCuentasAutorizadas');
      
      $mdl = AutoCarga::factory("AutorizacionCreditoSQL", "", "app", "AutorizacionCredito");
      $datos = $mdl->ConsultarClasiGrado($request);
      $InfoAutorizacion = $mdl->ConsultarInfoCuenAutorizada($request);
      $DetAutorizacion = $mdl->ConsultarDetCuenAutorizada($request);
      
      $act = AutoCarga::factory("AutorizacionCreditoHTML", "views", "app", "AutorizacionCredito");
      $this->salida = $act->formaInformacionAutorizacion($action, $request, $datos, $InfoAutorizacion, $DetAutorizacion);
      
      return true;
    }
    /**
    * Funcion que permite generar los reportes de las autorizaciones de credito realizadas
    */
    function GenerarReporte()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'Menu');
      $action['reporte'] = ModuloGetURL('app', 'AutorizacionCredito', 'controller', 'GenerarReporte');
      $act = AutoCarga::factory("AutorizacionCreditoHTML", "views", "app", "AutorizacionCredito");
      $this->salida = $act->formaGenerarReporte($action);
      
      return true;
    }
  } 
?>