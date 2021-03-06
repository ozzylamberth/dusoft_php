<?php

class app_MtoSalud_Parametrizacion_controller extends classModulo
{
		
		/**
		* Constructor de la clase
		*/
		function app_MtoSalud_Parametrizacion_controller(){}
		/**
		* Funcion principal del modulo
    *
    * @return boolean
		*/
		function Main()
		{
			$usr = AutoCarga::factory("ParametrizacionMtoSalud", "", "app","MtoSalud_Parametrizacion");
			$mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","MtoSalud_Parametrizacion");
			$this->action['volver'] = ModuloGetURL('system','Menu');
      
			
			$permisos = $usr->ObtenerPermisos();
      
      if(empty($permisos))
      {
        $mensaje = "EL USUARIO NO TIENE PERMISOS PARA TRABAJAR EN ESTE MODULO";
        $this->salida = $mdl->FormaMensajeModulo($this->action,$mensaje);
        return true;
      }      
      
			$this->action['parametrizacion'] = ModuloGetURL('app','MtoSalud_Parametrizacion','controller','ParametrizarGuiaMtoSalud');			
			$this->salida = $mdl->FormaMenuInicial($this->action);
			return true;
		}	
	
		function ParametrizarGuiaMtoSalud()
		{
			$this->action['volver'] = ModuloGetURL('app','MtoSalud_Parametrizacion','controller','Main');
			$this->action['nuevaActividad'] = ModuloGetURL('app','MtoSalud_Parametrizacion','controller','CrearNuevaActividad');
			$this->action['guardarParametrizacion'] = ModuloGetURL('app','MtoSalud_Parametrizacion','controller','GuardarParametrizacion');
			$act = AutoCarga::factory("ParametrizacionMtoSalud", "", "app","MtoSalud_Parametrizacion");
			$actividades = $act->GetActividades();
			$etapas = $act->GetEtapas();
			$parametrizacion = $act->GetParametrizacion();			
			$this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
			$mdl = AutoCarga::factory("ParametrizacionMtoSaludHTML", "views", "app","MtoSalud_Parametrizacion");
			$this->salida .= $mdl->FormaParametrizarMtoSaludHTML($this->action,$actividades,$etapas,$parametrizacion);
			return true;
		}
		
		function CrearNuevaActividad()
		{
			$this->action['volver'] = ModuloGetURL('app','MtoSalud_Parametrizacion','controller','ParametrizarGuiaMtoSalud');
			$this->action['guardarActividad'] = ModuloGetURL('app','MtoSalud_Parametrizacion','controller','GuardarNuevaActividad');
			SessionSetVar("rutaImagenes",GetThemePath());
			$this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserDrag");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS('RemoteXajax/ParametrizacionMtoSalud.js', $contenedor='app', $modulo='MtoSalud_Parametrizacion');
			$file = 'app_modules/MtoSalud_Parametrizacion/RemoteXajax/ParametrizacionMtoSalud.php';
			$this->SetXajax(array("BuscarCargos"),$file);			
			$mdl = AutoCarga::factory("ParametrizacionMtoSaludHTML", "views", "app","MtoSalud_Parametrizacion");
			$this->salida .= $mdl->FormaAgregarActividadMtoSaludHTML($this->action);
			return true;
		}
		
		function GuardarNuevaActividad()
		{
			
			$this->request = $_REQUEST;
			$ing = AutoCarga::factory("ParametrizacionMtoSalud", "", "app","MtoSalud_Parametrizacion");
      $rst = $ing->IngresarDatosActividades($this->request);
			$this->action['volver'] = ModuloGetURL('app','MtoSalud_Parametrizacion','controller','CrearNuevaActividad');
      $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","MtoSalud_Parametrizacion");
     
      $mensaje = "EL INGRESO DE LA ACTIVIDAD, SE REALIZO CORRECTAMENTE";
      if(!$rst)
      {
        $mensaje = $ing->error."<br>ERROR ".$ing->mensajeDeError;
      }      
      $this->salida = $mdl->FormaMensajeModulo($this->action,$mensaje);
      
      return true;
		}
		
		function GuardarParametrizacion()
		{
			
			$this->request = $_REQUEST;
			$ing = AutoCarga::factory("ParametrizacionMtoSalud", "", "app","MtoSalud_Parametrizacion");
      $rst = $ing->IngresarDatosParametrizacion($this->request);
			$this->action['volver'] = ModuloGetURL('app','MtoSalud_Parametrizacion','controller','ParametrizarGuiaMtoSalud');
      $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","MtoSalud_Parametrizacion");
     
      $mensaje = "LA PARAMETRIZACION DE LAS ACTIVIDADES, SE REALIZO CORRECTAMENTE";
      if(!$rst)
      {
        $mensaje = $ing->error."<br>ERROR ".$ing->mensajeDeError;
      }      
      $this->salida = $mdl->FormaMensajeModulo($this->action,$mensaje);
      
      return true;
		}
		
		
	}	

?>