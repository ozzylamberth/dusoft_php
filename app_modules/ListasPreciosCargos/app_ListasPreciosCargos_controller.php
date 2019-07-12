<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_ListasPreciosCargos_controller.php,v 1.5 2008/08/15 16:10:21 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Control: ListasPreciosCargos
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.5 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class app_ListasPreciosCargos_controller extends classModulo
  {
    /**
    * Constructor de la clase
    */
    function app_ListasPreciosCargos_controller(){}
    /**
    * Funcion principal del modulo
    * 
    * @return boolean
    */
    function Main()
    {
      $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
      $permisos = $cxp->ObtenerPermisos();
      
      $action['volver'] = ModuloGetURL('system','Menu');
      if(empty($permisos))
      {
        $mensaje = "SU USUARIO NO CUENTA CON PERMISOS PARA ACCEDER A ESTE MODULO";
        $html = AutoCarga::factory('MensajesModuloHTML','views','app','ListasPreciosCargos');
        $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      }
      else
      {
        $ttl_gral = "LISTAS DE PRECIOS SOBRE CARGOS";
        $titulo[0]='EMPRESAS';
  			$url[0]='app';							
  			$url[1]='ListasPreciosCargos';	
  			$url[2]='controller';	
  			$url[3]='MantenimientoListas';				
  			$url[4]='permiso_listas';
  			$this->salida = gui_theme_menu_acceso($ttl_gral,$titulo,$permisos,$url,$action['volver']);
      }
      return true;
    } 
    /**
    * Funcion de control para el mantenimiento de listas de precios
    *
    * @return boolean
    */
    function MantenimientoListas()
    {
      $request = $_REQUEST;
			$this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
      
      if(!empty($request['permiso_listas'])) SessionSetVar("EmpresasPrecios",$request['permiso_listas']);
      
      $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
      $listado = $cxp->ObtenerListas($request['buscador'],$request['offset']);
      
      $action['volver'] = ModuloGetURL('app','ListasPreciosCargos','controller','Main');
      $action['aceptar'] = ModuloGetURL('app','ListasPreciosCargos','controller','IngresarListaPrecios');
      $action['buscador'] = ModuloGetURL('app','ListasPreciosCargos','controller','MantenimientoListas');
      $action['paginador'] = ModuloGetURL('app','ListasPreciosCargos','controller','MantenimientoListas',array("buscador"=>$request['buscador']));
      $action['ver_detalle'] = ModuloGetURL('app','ListasPreciosCargos','controller','MostarDetalleLista');
      $action['crear_detalle'] = ModuloGetURL('app','ListasPreciosCargos','controller','CrearDetalleListaPrecios');
      $action['activar'] = ModuloGetURL('app','ListasPreciosCargos','controller','ModificarLista',array("lista_codigo"=>$request['lista_codigo']));
      $action['proveedor'] = ModuloGetURL('app','ListasPreciosCargos','controller','ProveedoresLista',array("lista_codigo"=>$request['lista_codigo']));
      $action['plan'] = ModuloGetURL('app','ListasPreciosCargos','controller','PlanesLista',array("lista_codigo"=>$request['lista_codigo']));
      $action['informacion'] = ModuloGetURL('app','ListasPreciosCargos','controller','ModificarInformacionLista',array("lista_codigo"=>$request['lista_codigo']));
      
      $html = AutoCarga::factory('ListaPreciosHTML','views','app','ListasPreciosCargos');
      $this->salida = $html->Formalnicial($action,$request['buscador'],$listado,$cxp->conteo,$cxp->pagina);

      return true;
    }
    /**
    * Funcion de control para el ingreso de listas de precios
    *
    * @return boolean
    */
    function IngresarListaPrecios()
    {
      $request = $_REQUEST;
      $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');

      $rst = $cxp->IngresarListaPrecios($request);
      $html = AutoCarga::factory('MensajesModuloHTML','views','app','ListasPreciosCargos');
      
      if(!$rst)
      {
        $mensaje = "ERROR ".$cxp->ErrMsg();
        
        $action['volver'] = ModuloGetURL('app','ListasPreciosCargos','controller','MantenimientoListas');
        $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      }
      else
      {
        $mensaje = "LA LISTA DE PRECIOS ".$RST.", FUE CREADA SATISFACTORIAMENTE ";
        $action['volver'] = ModuloGetURL('app','ListasPreciosCargos','controller','CrearDetalleListaPrecios',array("lista_codigo"=>$rst));
        $this->salida = $html->FormaMensajeModulo($action,$mensaje);        
      }
      return true;
    }
    /**
    * Funcion de control para la creacion del detalle de las listas de precios
    *
    * @return boolean
    */
    function CrearDetalleListaPrecios()
    {
      $request = $_REQUEST;
 			$this->SetXajax(array("SeleccionarTarifario","SeleccionarSubGrupos","CrearDetalleListaPrecios","SeleccionarGrupos","BuscarCargos","AdicionarCargos"),"app_modules/ListasPreciosCargos/RemoteXajax/Tarifarios.php");
      
      $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
      $tipos_tarifarios = $cxp->ObtenerTiposTarifarios();

      $action['volver'] = ModuloGetURL('app','ListasPreciosCargos','controller','MantenimientoListas');
      $action['aceptar'] = ModuloGetURL('app','ListasPreciosCargos','controller','DetalleListaCargos',array("lista_codigo"=>$request['lista_codigo']));

      $html = AutoCarga::factory('ListaPreciosHTML','views','app','ListasPreciosCargos');
      $this->salida .= $html->FormaGrupos($action,$tipos_tarifarios,$request['lista_codigo']);
      return true;
    }
    /**
    * Funcion de control para la vista del detalle de las listas de precios
    *
    * @return boolean
    */
    function MostarDetalleLista()
    {
      $request = $_REQUEST;
 			$this->SetXajax(array("ModificarValor"),"app_modules/ListasPreciosCargos/RemoteXajax/Tarifarios.php");

      $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
 
      $lista = $cxp->ObtenerCargosLista($request['lista']['lista_codigo'],$request['buscador'],$request[offset]);
      $html = AutoCarga::factory('ListaPreciosHTML','views','app','ListasPreciosCargos');
      
      $action['volver'] = ModuloGetURL('app','ListasPreciosCargos','controller','MantenimientoListas',array("lista"=>$request['lista']));
      $action['paginador'] = ModuloGetURL('app','ListasPreciosCargos','controller','MostarDetalleLista',array("lista"=>$request['lista']));
      $action['eliminar'] = ModuloGetURL('app','ListasPreciosCargos','controller','EliminarCargos',array("lista"=>$request['lista']));

      $this->salida .= $html->FormaListasPreciosDetalle($action,$request,$lista,$cxp->conteo,$cxp->pagina);
      return true;
    }    
    /**
    * Funcion de control para la asociacion de proveedores a las listas de precios
    *
    * @return boolean
    */
    function ProveedoresLista()
    {
      $request = $_REQUEST;
 			$this->SetXajax(array("VincularProveedor","DesvincularProveedor"),"app_modules/ListasPreciosCargos/RemoteXajax/Tarifarios.php");

      $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
 
      $lista = $cxp->ObtenerProveedoresLista($request['lista_codigo']);
      $proveedores = $cxp->ObtenerProveedores($request['lista_codigo']);
      $html = AutoCarga::factory('ListaPreciosHTML','views','app','ListasPreciosCargos');
      
      $action['volver'] = ModuloGetURL('app','ListasPreciosCargos','controller','MantenimientoListas');

      $this->salida .= $html->FormaListasProveedores($action,$request,$lista,$proveedores);
      return true;
    }
    /**
    * Funcion de control para la eliminacion de cargos del 
    * detalle de las listas de precios
    *
    * @return boolean
    */
    function EliminarCargos()
    {
      $request = $_REQUEST;
      
      $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
      $rst = $cxp->EliminarCargosLista($request['cargos'],$request['lista']['lista_codigo']);
      
      $html = AutoCarga::factory('MensajesModuloHTML','views','app','ListasPreciosCargos');
      
      if(!$rst)
        $mensaje = "ERROR ".$cxp->ErrMsg();
      else
        $mensaje = "LOS CARGOS SELECCIONADOS FUERON DESVINCULADOS DE LA LISTA CORRECTAMENTE ";
      
      $action['volver'] = ModuloGetURL('app','ListasPreciosCargos','controller','MostarDetalleLista',array("lista"=>$request['lista']));
      $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      
      return true;
    }    
    /**
    * Funcion de control para la modificacion del estado de la lista de precios
    *
    * @return boolean
    */
    function ModificarLista()
    {
      $request = $_REQUEST;
      
      $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
      $rst = $cxp->CambiarEstadoLista($request);
      
      $html = AutoCarga::factory('MensajesModuloHTML','views','app','ListasPreciosCargos');
      
      if(!$rst)
        $mensaje = "ERROR ".$cxp->ErrMsg();
      else
        $mensaje = "EL ESTADO DE LA LISTA SE HA CAMBIADO SATISFACTORIAMENTE";
      
      $action['volver'] = ModuloGetURL('app','ListasPreciosCargos','controller','MantenimientoListas');
      $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      
      return true;
    }
    /**
    * Funcion de control para la vista de la informacion de la lista de precios
    *
    * @return boolean
    */
    function ModificarInformacionLista()
    {
      $request = $_REQUEST;
      
      $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
      $informacion = $cxp->ObtenerListas($request);

      $html = AutoCarga::factory('ListaPreciosHTML','views','app','ListasPreciosCargos');
          
      $action['volver'] = ModuloGetURL('app','ListasPreciosCargos','controller','MantenimientoListas');
      $action['aceptar'] = ModuloGetURL('app','ListasPreciosCargos','controller','ModificarInformacion',array("lista_codigo"=> $request['lista_codigo']));
      $this->salida = $html->FormaModificarInformacionLista($action,$informacion[0]);
      
      return true;
    }
    /**
    * Funcion de control para la modificacion de la informacion de la lista de precios
    *
    * @return boolean
    */
    function ModificarInformacion()
    {
      $request = $_REQUEST;
      $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
      $rst = $cxp->ActualizarInformacion($request);
      
      $html = AutoCarga::factory('MensajesModuloHTML','views','app','ListasPreciosCargos');
      
      if(!$rst)
        $mensaje = "ERROR ".$cxp->ErrMsg();
      else
        $mensaje = "LA INFORMACION DE LA LISTA HA SIDO MODIFICADA";
      
      $action['volver'] = ModuloGetURL('app','ListasPreciosCargos','controller','ModificarInformacionLista',array("lista_codigo"=> $request['lista_codigo']));
      $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      
      return true;
    }
    /**
    *
    */
    function PlanesLista()
    {
      $request = $_REQUEST;
      
      IncludeFileModulo('Tarifarios','RemoteXajax','app','ListasPreciosCargos');
      $this->SetXajax(array("MostrarTiposAfiliados","RegistrarCobertura"));
      $this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
      
      $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
      $lista = $cxp->ObtenerPlanes();
      $html = AutoCarga::factory('ListaPreciosHTML','views','app','ListasPreciosCargos');
      
      $action['volver'] = ModuloGetURL('app','ListasPreciosCargos','controller','MantenimientoListas');

      $this->salida .= $html->FormaListasPlanes($action,$request,$lista);
      return true;
    }
  }
?>