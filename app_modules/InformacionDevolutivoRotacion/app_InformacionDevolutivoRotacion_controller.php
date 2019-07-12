<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: app_InformacionDevolutivoRotacion_controller.php,v 1.0
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	/**
	* Clase Control: InformacionDevolutivoRotacion
	* Clase encargada del control de llamado de metodos en el modulo
	*
	* @package IPSOFT-SIIS
	/*/
	class app_InformacionDevolutivoRotacion_controller  extends classModulo
	{
	/**
		* Constructor de la clase
	*/
	function app_InformacionDevolutivoRotacion_controller()
	{}
	/**
        *  Funcion principal del modulo
        *  @return boolean
    */
		function Main()
		{
			$request = $_REQUEST;
			$obj = AutoCarga::factory('InformacionDevolutivoRotacionSQL', '', 'app', 'InformacionDevolutivoRotacion');
			$permisos = $obj->ObtenerPermisos();    
			
			$ttl_gral = "INFORMACION";
			$mtz[0]='FARMACIAS';
			$url[0] = 'app';
			$url[1] = 'InformacionDevolutivoRotacion'; 
			$url[2] = 'controller';
			$url[3] = 'Menu'; 
			$url[4] = 'InformacionDevolutivoRotacion'; 
			
			$action['volver'] = ModuloGetURL('system', 'Menu');
			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
			return true;
		}    
	/**
        *  Funcion Menu principal 
        *  @return boolean
    */	function Menu()
		{
			$request = $_REQUEST;
			if($request['InformacionDevolutivoRotacion']) SessionSetVar("DatosFarmacia",$request['InformacionDevolutivoRotacion']);
			
			$farm = SessionGetVar("DatosFarmacia");
			$farmacia=$farm['farmacia_id'];
			
			$consulta = AutoCarga::factory('InformacionDevolutivoRotacionSQL', '', 'app', 'InformacionDevolutivoRotacion');
			$datos = $consulta->ConsultarInformacionDevolucion($farmacia);
			$num=count($datos);
		 
			$action['devolutivos'] = ModuloGetURL("app", "InformacionDevolutivoRotacion", "controller", "consultarDetalleDevolutivo");
			$action['volver'] = ModuloGetURL("app", "InformacionDevolutivoRotacion", "controller", "Main");
			$act = AutoCarga::factory("InformacionDevolutivoRotacionHTML", "views", "app", "InformacionDevolutivoRotacion");
			$this->salida = $act->FormaMenu($action,$num);
			return true;
			
		}
	/**
        *  Funcion Consultar Detalle De la Solicitud De Devolucion
        *  @return boolean
    */
		
		function consultarDetalleDevolutivo()
		 {
			$request = $_REQUEST;
			$farmacia= SessionGetVar("DatosFarmacia");
			$farmacia_id=$farmacia['farmacia_id'];
			IncludeFileModulo("Devolutivo","RemoteXajax","app","InformacionDevolutivoRotacion");
			$this->SetXajax(array("ActualizarDatos"),"app_modules/InformacionDevolutivoRotacion/RemoteXajax/Devolutivo.php","ISO-8859-1");
			
			$consulta = AutoCarga::factory('InformacionDevolutivoRotacionSQL', '', 'app', 'InformacionDevolutivoRotacion');
			$datos = $consulta->ConsultarInformacionDevolucion($farmacia_id);
			
			$num=count($datos);
			$action['volver'] = ModuloGetURL("app", "InformacionDevolutivoRotacion", "controller", "Menu");
			$act = AutoCarga::factory("InformacionDevolutivoRotacionHTML", "views", "app", "InformacionDevolutivoRotacion");
			$this->salida = $act->DetalleSolicitudDevolucion($action,$datos,$num,$farmacia);
			return true;
		}
	
	}
?>