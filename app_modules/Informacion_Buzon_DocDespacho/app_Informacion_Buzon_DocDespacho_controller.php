<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: app_Informacion_Buzon_DocDespacho_controller.php,v 1.0
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	/**
	* Clase Control: Informacion_Buzon_DocDespacho
	* Clase encargada del control de llamado de metodos en el modulo
	*
	* @package IPSOFT-SIIS
	/*/
	class app_Informacion_Buzon_DocDespacho_controller  extends classModulo
	{
	/**
		* Constructor de la clase
	*/
	function app_Informacion_Buzon_DocDespacho_controller()
	{}
	/**
        *  Funcion principal del modulo
        *  @return boolean
    */
		function Main()
		{
			$request = $_REQUEST;
			$contratacion = AutoCarga::factory('Informacion_Buzon_DocDespachoSQL', '', 'app', 'Informacion_Buzon_DocDespacho');
			$permisos = $contratacion->ObtenerPermisos();    
			
			$ttl_gral = "INFORMACION";
			$mtz[0]='EMPRESA';
			$url[0] = 'app';
			$url[1] = 'Informacion_Buzon_DocDespacho'; 
			$url[2] = 'controller';
			$url[3] = 'Menu'; 
			$url[4] = 'Informacion_Buzon_DocDespacho'; 
			
			$action['volver'] = ModuloGetURL('system', 'Menu');
			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
			return true;
		}    
	/*
		* Funcion de control para el Menu Inicial
	*/
		function Menu()
		{
			$request = $_REQUEST;
			if($request['Informacion_Buzon_DocDespacho']) SessionSetVar("DatosEmpresaAF",$request['Informacion_Buzon_DocDespacho']);
			$emp = SessionGetVar("DatosEmpresaAF");
			$empresa=$emp['empresa_id'];
			$consulta = AutoCarga::factory('Informacion_Buzon_DocDespachoSQL', '', 'app', 'Informacion_Buzon_DocDespacho');
			$datos = $consulta->ConsultarDocumentDespa($empresa);   
			$cantidad=count($datos);
			
		  				
			$action['Informa'] = ModuloGetURL("app", "Informacion_Buzon_DocDespacho", "controller", "BuscarDocumentosDes");
			$action['volver'] = ModuloGetURL("app", "Informacion_Buzon_DocDespacho", "controller", "Main");
			
			$act = AutoCarga::factory("Informacion_Buzon_DocDespachoHTML", "views", "app", "Informacion_Buzon_DocDespacho");
			$this->salida = $act->FormaMenu($action,$cantidad);
			//print_r($emp);
			return true;
		}
		
		function BuscarDocumentosDes()
		 {
		 $request = $_REQUEST;
		 $emp = SessionGetVar("DatosEmpresaAF");
		 $empresa=$emp['empresa_id'];
		 $empname=$emp['descripcion1'];
		 $consulta = AutoCarga::factory('Informacion_Buzon_DocDespachoSQL', '', 'app', 'Informacion_Buzon_DocDespacho');
		 $datos = $consulta->ConsultarDocumentDespa($empresa);
		 
		 $action['DocRev'] = ModuloGetURL("app", "Informacion_Buzon_DocDespacho", "controller", "ConsultarDocDespachoInfor");
		 $action['verdetalle'] = ModuloGetURL("app", "Informacion_Buzon_DocDespacho", "controller", "ConsultarDocumentoDespachoDetalle");
		 
		 
		 $action['volver'] = ModuloGetURL("app", "Informacion_Buzon_DocDespacho", "controller", "Menu");
		 $act = AutoCarga::factory("Informacion_Buzon_DocDespachoHTML", "views", "app", "Informacion_Buzon_DocDespacho");
		 $this->salida = $act->FormaListarDocumentosDes($action,$datos,$empname);

		 //print_r($datos);
		 return true;
		 }
		 
		 function ConsultarDocumentoDespachoDetalle()
		{
		 $request = $_REQUEST;
		 $empresa_id=$request['empresa'];
		 $prefijo=$request['prefijo'];
		 $numero=$request['numero'];
		 $farmacia_id=$request['farmacia_id'];
		 $consulta = AutoCarga::factory('Informacion_Buzon_DocDespachoSQL', '', 'app', 'Informacion_Buzon_DocDespacho');
		 $InserUs =$consulta->AuditoriaUsuariosDocDespacho($empresa_id,$prefijo,$numero,$farmacia_id);
		 
		 $ActDato =$consulta->ActualizarCampoSw_revisado($empresa_id,$prefijo,$numero);
		 $datos =$consulta->ConsultarDetalleDeDocumentoDespacho($empresa_id,$prefijo,$numero);
		 $action['volver'] = ModuloGetURL("app", "Informacion_Buzon_DocDespacho", "controller", "Menu");

		 $act = AutoCarga::factory("Informacion_Buzon_DocDespachoHTML", "views", "app", "Informacion_Buzon_DocDespacho");
		 $this->salida = $act->FormaListarDescripcion($action,$datos);
		// print_r($request);
		
		 return true;
		
		}
		
		function ConsultarDocDespachoInfor()
		{
		
		$request = $_REQUEST;
         $emp = SessionGetVar("DatosEmpresaAF");
		 $empresa=$emp['empresa_id'];
		 $emprenom=$emp['descripcion1'];
	
		 $consulta = AutoCarga::factory('Informacion_Buzon_DocDespachoSQL', '', 'app', 'Informacion_Buzon_DocDespacho');
		if(!empty($request['buscador']))
			{
				$datos=$consulta->ConsultarDocumentDespaVerf($empresa,$request['buscador'],$request['offset']);
				$action['buscador']=ModuloGetURL('app','Informacion_Buzon_DocDespacho','controller','ConsultarDocDespachoInfor');
				$conteo= $consulta->conteo;
				$pagina= $consulta-> pagina;
			}
			$action['paginador'] = ModuloGetURL('app', 'Informacion_Buzon_DocDespacho', 'controller', 'ConsultarDocDespachoInfor',array("buscador"=>$request['buscador']));
		  $action['verdetalleInfor'] = ModuloGetURL("app", "Informacion_Buzon_DocDespacho", "controller", "ConsultarDocumentoDespachoDetalleInformados");

		  $action['volver'] = ModuloGetURL("app", "Informacion_Buzon_DocDespacho", "controller", "Menu");
 
		 $act = AutoCarga::factory("Informacion_Buzon_DocDespachoHTML", "views", "app", "Informacion_Buzon_DocDespacho");
		 $this->salida = $act->FormaConsultarInformado($action,$datos,$conteo,$pagina,$request['buscador'],$emprenom);
		 // print_r($emp);
		return true;
		
		}
	
		function ConsultarDocumentoDespachoDetalleInformados()
		{
		$request = $_REQUEST;
		 $empresa_id=$request['empresa'];
		 $prefijo=$request['prefijo'];
		 $numero=$request['numero'];
		 $farmacia_id=$request['farmacia_id'];
		 $consulta = AutoCarga::factory('Informacion_Buzon_DocDespachoSQL', '', 'app', 'Informacion_Buzon_DocDespacho');
		 $InserUs =$consulta->AuditoriaUsuariosDocDespacho($empresa_id,$prefijo,$numero,$farmacia_id);
		 $datos =$consulta->ConsultarDetalleDeDocumentoDespacho($empresa_id,$prefijo,$numero);
		 $action['volver'] = ModuloGetURL("app", "Informacion_Buzon_DocDespacho", "controller", "ConsultarDocDespachoInfor");

		 $act = AutoCarga::factory("Informacion_Buzon_DocDespachoHTML", "views", "app", "Informacion_Buzon_DocDespacho");
		 $this->salida = $act->FormaListarDescripcion($action,$datos);
		// print_r($request);
		return true;
		}
	}
?>