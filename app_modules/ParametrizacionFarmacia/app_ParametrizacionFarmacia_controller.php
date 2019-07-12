<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: app_ParametrizacionFarmacia_controller.php,v 1.0
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	/**
	* Clase Control: ParametrizacionFarmacia
	* Clase encargada del control de llamado de metodos en el modulo
	*
	* @package IPSOFT-SIIS
	/*/
	class app_ParametrizacionFarmacia_controller  extends classModulo
	{
	/**
		* Constructor de la clase
	*/
	function app_AdministracionFarmacia_controller()
	{}
	/**
        *  Funcion principal del modulo
        *  @return boolean
    */
		function Main()
		{
			$request = $_REQUEST;
			$Permisos = AutoCarga::factory('ParametrizacionFarmaciaSQL', '', 'app', 'ParametrizacionFarmacia');
			$Informacion = $Permisos->ObtenerPermisos();    
			$ttl_gral = "FARMACIA";
			$mtz[0]='FARMACIAS';
			$url[0] = 'app';
			$url[1] = 'ParametrizacionFarmacia'; 
			$url[2] = 'controller';
			$url[3] = 'Menu'; 
			$url[4] = 'ParametrizacionFarmacia'; 
			$action['volver'] = ModuloGetURL('system', 'Menu');
			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $Informacion, $url, $action['volver']);
			return true;
		}    
	/*
		* Funcion de control para el Menu Inicial
		 *  @return boolean
	*/
		function Menu()
		{
			$request = $_REQUEST;
			if($request['ParametrizacionFarmacia']) SessionSetVar("DatosFarmacia",$request['ParametrizacionFarmacia']);
			$emp = SessionGetVar("DatosFarmacia");
			$empresa=$emp['empresa_id'];
			$action['parametrizfarma'] = ModuloGetURL("app", "ParametrizacionFarmacia", "controller", "ParametrizacionFarmacia");
			$action['volver'] = ModuloGetURL("app", "ParametrizacionFarmacia", "controller", "Main");
			$act = AutoCarga::factory("ParametrizacionFarmaciaHTML", "views", "app", "ParametrizacionFarmacia");
			$this->salida = $act->FormaMenu($action);
			return true;      
		}
	/*
		* Funcion que permite Prametrizar la Farmacia 
		* Parametrizar el tipo de atencion 
		* -Atiende al publico Directamente y/o
		* -Atiende Unicamente con Formula Medica 
		**  @return boolean 
	*/
		function ParametrizacionFarmacia()
		{
			$request = $_REQUEST;
			$emp = SessionGetVar("DatosFarmacia");
			$empresa=$emp['empresa_id'];
			
			$this->SetXajax(array("UpdateTipoAtencion","ModificarParametrizacion","ParameMODF","ParametrosVentaP","ParameSi"),"app_modules/ParametrizacionFarmacia/RemoteXajax/DatosFarmacia.php","ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$mdl = AutoCarga::factory("ParametrizacionFarmaciaSQL", "classes", "app", "ParametrizacionFarmacia");
			$datos=$mdl->ConsultarInformacionFarmacia($empresa);
			$act = AutoCarga::factory("ParametrizacionFarmaciaHTML", "views", "app", "ParametrizacionFarmacia");
					
			$action['volver'] = ModuloGetURL("app", "ParametrizacionFarmacia", "controller", "Menu");
			$this->salida = $act->FormaFarmacia($action,$datos,$empresa);
			return true;
		}    
	} 
?>