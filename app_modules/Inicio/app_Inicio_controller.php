<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_Inicio_controller.php,v 1.2 2008/03/28 18:23:48 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Control: UV_Afiliaciones
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class app_Inicio_controller extends classModulo
	{
		/**
		* Constructor de la clase
		*/
		function app_Inicio_controller(){}
		/**
		* Funcion principal del modulo
    *
    * @return boolean
		*/
		function Main()
		{
      $action['Tablas'] = ModuloGetURL('app','Inicio','controller','ListadoTablas');
 			$mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","Inicio");
			$this->salida .= $mdl->FormaInicial($action);
			return true;
		}
    /**
    * Funcion de control para la lista de tablas
    *
    * @return boolean
    **/
    function ListadoTablas()
    {
      $ini = AutoCarga::factory("Inicio", "", "app","Inicio");
      $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","Inicio");      
      $tbl = $this->ReturnModuloExterno('system','Tablas','controller');

      $action['volver1'] = ModuloGetURL('app','Inicio','controller','Main');
      $action['volver2'] = ModuloGetURL('app','Inicio','controller','ListadoTablas');
      
      $tbl->SetActionVolver($action['volver2']);
      $datos = $ini->ListadoDeTablas();
      
      $url['contenedor']='system';
			$url['modulo']='Tablas';
			$url['tipo']='controller';
			$url['metodo']='Index';
			$arreglo[0]='TABLAS';
			
			$this->salida = $mdl->FormaMenuTablas('TABLAS',$arreglo,$datos,$url,$action['volver1']);
      return true;
    }
    /**
    * Funcion de control para la lista de tablas
    *
    * @return boolean
    **/
    function ListadoTablasMenu()
    {
      $ini = AutoCarga::factory("Inicio", "", "app","Inicio");
      $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","Inicio");      
      $tbl = $this->ReturnModuloExterno('system','Tablas','controller');
      
      $permisos = $ini->ObtenerPermisos();
      
      if(empty($permisos))
      {
        $action['volver'] = ModuloGetURL('system','Menu');
        $mensaje = "SU USUARIO NO TIENE PERMISOS PARA TRABAJAR EN ESTE MODULO";
        
        $this->salida = $mdl->FormaMensajeModulo($action,$mensaje);
        
        return true;
      }
      
      $action['volver1'] = ModuloGetURL('system','Menu');
      $action['volver2'] = ModuloGetURL('app','Inicio','controller','ListadoTablasMenu');
      
      $tbl->SetActionVolver($action['volver2']);
      
      $datos = array();
      if($permisos['sw_todas_tablas'] == '1')
        $datos = $ini->ListadoDeTablas();
      else
        $datos = $ini->ObtenerTablas();
      
      if(empty($datos))
      {
        $action['volver'] = ModuloGetURL('system','Menu');
        $mensaje = "SU USUARIO NO TIENE TABLAS PARAMETRIZADAS";
        
        $this->salida = $mdl->FormaMensajeModulo($action,$mensaje);
        
        return true;
      }
      
      $url['contenedor']='system';
			$url['modulo']='Tablas';
			$url['tipo']='controller';
			$url['metodo']='Index';
			$arreglo[0]='TABLAS - ADMINISTRATIVO';
			
			$this->salida = $mdl->FormaMenuTablas('TABLAS',$arreglo,$datos,$url,$action['volver1']);
      return true;
    }
  }
?>