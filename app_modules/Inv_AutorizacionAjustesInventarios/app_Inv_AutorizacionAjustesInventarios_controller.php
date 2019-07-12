<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_Inv_AutorizacionAjustesInventarios_controller.php,v 1.1 2010/04/09 19:50:04 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina
  */
  /**
  * Clase Control: Inv_AutorizacionAjustesInventarios
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina
  */
  class app_Inv_AutorizacionAjustesInventarios_controller extends classModulo
  {
    /**
    * Constructor de la clase
    */
    function app_Inv_AutorizacionAjustesInventarios_controller(){}
    /**
    * Funcion principal del modulo
    *
    * @return boolean
    */
    function main()
    {
      $cls = AutoCarga::factory("ListaReportes","classes","app","Inv_AutorizacionAjustesInventarios");
      $permisos = $cls->ObtenerPermisos(UserGetUID());
      
		$titulo[0] = 'EMPRESA';
		$titulo[1] = 'CENTRO UTILIDAD';
		$titulo[2] = 'BODEGAS';
		$url[0] = 'app';										//contenedor 
		$url[1] = 'Inv_AutorizacionAjustesInventarios';	  //módulo 
		$url[2] = 'controller';							//clase 
		$url[3] = 'Menu';				            //método 
		$url[4] = 'datos';					//indice del request
		$this->salida .= gui_theme_menu_acceso('AUTORIZACION/AUDITORIA - AJUSTES INVENTARIOS',$titulo,$permisos,$url,ModuloGetURL('system','Menu'));

      return true;
    }
	
	
    /**
    * Funcion de control, para la creacion del menu
    *
    * @return boolean
    */
    function Menu()
    {
      $request = $_REQUEST;
      if($request['datos'])
        SessionSetVar("DatosAjustes",$request['datos']);
	$empresa = SessionGetVar("DatosAjustes");

	$html = AutoCarga::factory("MensajesModuloHTML","views","app","Inv_AutorizacionAjustesInventarios");
	
	if($empresa['autorizador']=='0' || $empresa['autorizador']=='3')
	$action['autorizar'] = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','AutorizarDespachos');
	
	if($empresa['autorizador']=='1' || $empresa['autorizador']=='3')
	$action['auditoria'] = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','AuditoriaAjustes');
		
	if($empresa['autorizador']=='2' || $empresa['autorizador']=='3')
	$action['autorizar_despachos'] = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','AutorizarDespachos_FarmaciasClientes');
	
	$action['volver'] = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','main');
	$this->salida .= $html->FormaMenuInicial($action);
	return true;
    }
	
    /**
    * Funcion de control, para la creacion del menu
    *
    * @return boolean
    */
    function AutorizarDespachos()
    {
    $request = $_REQUEST;
	$empresa = SessionGetVar("DatosAjustes");
	$sql = AutoCarga::factory("ListaReportes","classes","app","Inv_AutorizacionAjustesInventarios");
	
	$datos = $sql->ObtenerDocumentosPorAutorizar($empresa,$request['buscador'],$request['offset']);
	$html = AutoCarga::factory("MensajesModuloHTML","views","app","Inv_AutorizacionAjustesInventarios");
	/*print_r($datos);*/
	
	$action['paginador'] = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','AutorizarDespachos',array("buscador"=>$request['buscador']));
	$action['volver'] = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','Menu'); 
	$this->salida .= $html->FormaAutorizarAjustes($action,$request,$datos,$sql->conteo, $sql->pagina);
	return true;
    }
	
    /**
    * Funcion de control, para la creacion del menu
    *
    * @return boolean
    */
    function DocumentoAutorizar()
    {
    $request = $_REQUEST;
	$empresa = SessionGetVar("DatosAjustes");
	$sql = AutoCarga::factory("ListaReportes","classes","app","Inv_AutorizacionAjustesInventarios");
	
	if($request['autorizar']=="1")
	$token = $sql->AutorizarDocumento($request['documento'],$request);
	
	$cabecera = $sql->DocumentoAutorizar($empresa,$request['documento']);
	$detalle = $sql->DocumentoAutorizar_d($empresa,$request['documento']);
	$html = AutoCarga::factory("MensajesModuloHTML","views","app","Inv_AutorizacionAjustesInventarios");
	$datos=array_merge((array)$cabecera,(array)$detalle);
	
	
	
	$action['jefe_bodega']=ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','DocumentoAutorizar',array(	"documento"=>
																																											array("usuario_id"=>$request['documento']['usuario_id'],
																																											"doc_tmp_id"=>$request['documento']['doc_tmp_id']),
																																											"campo"=>"usuario_jefe_bodega",
																																											"autorizar"=>"1")
																																											);
	$action['control_interno']=ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','DocumentoAutorizar',array(	"documento"=>
																																											array("usuario_id"=>$request['documento']['usuario_id'],
																																											"doc_tmp_id"=>$request['documento']['doc_tmp_id']),
																																											"campo"=>"usuario_control_interno",
																																											"autorizar"=>"1")
																																											);
	$action['volver'] = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','AutorizarDespachos');
	$this->salida .= $html->DocumentoAutorizar($action,$datos,$empresa);
	return true;
    }
	
	  /**
    * Funcion de control, Auditorìa Documentod e Ajuste
    *
    * @return boolean
    */
    function AuditoriaAjustes()
    {
    $request = $_REQUEST;
	
	$empresa = SessionGetVar("DatosAjustes");
	$sql = AutoCarga::factory("ListaReportes","classes","app","Inv_AutorizacionAjustesInventarios");
	
	$datos = $sql->ObtenerDocumentosAjustes($empresa,$request['buscador'],$request['offset']);
	$html = AutoCarga::factory("MensajesModuloHTML","views","app","Inv_AutorizacionAjustesInventarios");
	/*print_r($datos);*/
	
	$action['paginador'] = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','AuditoriaAjustes',array("buscador"=>$request['buscador']));
	$action['volver'] = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','Menu');
	$this->salida .= $html->FormaAuditoriaAjustes($action,$request,$datos,$sql->conteo, $sql->pagina);
	return true;
    }
	
	 function DocumentoAuditoria()
    {
    $request = $_REQUEST;
	$empresa = SessionGetVar("DatosAjustes");
	$sql = AutoCarga::factory("ListaReportes","classes","app","Inv_AutorizacionAjustesInventarios");
	
	if($request['auditar']=="1")
	$token = $sql->AuditarDocumento($request['documento'],$request);
	
	
	$cabecera = $sql->DocumentoAuditar($empresa,$request['documento']);
	$detalle = $sql->DocumentoAuditar_d($empresa,$request['documento']);
	$html = AutoCarga::factory("MensajesModuloHTML","views","app","Inv_AutorizacionAjustesInventarios");
	$datos=array_merge((array)$cabecera,(array)$detalle);
	
	$action['auditoria']=ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','DocumentoAuditoria',array(	"documento"=>
																																											array("empresa_id"=>$request['documento']['empresa_id'],
																																											"prefijo"=>$request['documento']['prefijo'],
																																											"numero"=>$request['documento']['numero']),
																																											"campo"=>"auditor",
																																											"auditar"=>"1")
																																											);
	$action['volver'] = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','AuditoriaAjustes');
	$this->salida .= $html->DocumentoAuditar($action,$datos,$empresa);
	return true;
    }
	
  
  
    /**
    * Funcion de control, para la creacion del menu
    *
    * @return boolean
    */
    function AutorizarDespachos_FarmaciasClientes()
    {
    $request = $_REQUEST;
	$empresa = SessionGetVar("DatosAjustes");
	$sql = AutoCarga::factory("ListaReportes","classes","app","Inv_AutorizacionAjustesInventarios");
	
	$datos = $sql->ObtenerDocumentosPorAutorizarDespacho($empresa,$request['buscador'],$request['offset']);
	$html = AutoCarga::factory("MensajesModuloHTML","views","app","Inv_AutorizacionAjustesInventarios");
	
	$action['paginador'] = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','AutorizarDespachos_FarmaciasClientes',array("buscador"=>$request['buscador']));
	$action['volver'] = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','Menu');
	$this->salida .= $html->FormaAutorizarDespachos($action,$request,$datos,$sql->conteo, $sql->pagina);
	return true;
    }
	
	 /**
    * Funcion de control, para la creacion del menu
    *
    * @return boolean
    */
    function DocumentoAutorizar_Despacho()
    {
    $request = $_REQUEST;
	$empresa = SessionGetVar("DatosAjustes");
	$sql = AutoCarga::factory("ListaReportes","classes","app","Inv_AutorizacionAjustesInventarios");
	
	/*print_r($_REQUEST);*/
	if($request['autorizar']['autorizar']=="1")
	$token = $sql->AutorizarProducto_Despacho($request['documento'],$request['autorizar'],$request);
	
	if($request['eliminar_autorizacion']['eliminar']=="1")
	$token = $sql->EliminarProducto_Despacho($request['documento'],$request['eliminar_autorizacion'],$request);
	
	$this->IncludeJS("CrossBrowser");
	$this->IncludeJS("CrossBrowserEvent");
	$this->IncludeJS("CrossBrowserDrag");
	
	$cabecera = $sql->DocumentoAutorizar_Despacho($empresa,$request['documento']);
	$detalle = $sql->DocumentoAutorizar_d($empresa,$request['documento']);
	$productos_autorizar = $sql->ObtenerProductos_Autorizacion($empresa,$request['documento']);
	$html = AutoCarga::factory("MensajesModuloHTML","views","app","Inv_AutorizacionAjustesInventarios");
	$datos=array_merge((array)$cabecera,(array)$detalle);
	$action['autorizar']=ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','DocumentoAutorizar_Despacho',array(	"documento"=>
																																											array("usuario_id"=>$request['documento']['usuario_id'],
																																											"doc_tmp_id"=>$request['documento']['doc_tmp_id']),
																																											"campo"=>"usuario_control_interno")
																																											);	
	$action['volver'] = ModuloGetURL('app','Inv_AutorizacionAjustesInventarios','controller','AutorizarDespachos_FarmaciasClientes');
	$this->salida .= $html->DocumentoAutorizar_Despachos($action,$datos,$productos_autorizar,$empresa,$request);
	return true;
    }
	
  
  }
?>