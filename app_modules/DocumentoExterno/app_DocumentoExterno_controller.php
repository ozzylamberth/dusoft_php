<?php
	/**
  * @package DUANA & CIA
  * @version 1.0 $Id: app_DocumentoExterno_controller.php,v 1.0 $
  * @copyright DUANA & CIA 20-DIC-2012
  * @author L.G.T.L
  */
  /** 
  * Clase Control: DocumentoExterno
  * Responsabilidad: Clase encargada del control de llamado de metodos en el modulo
  **/

    class app_DocumentoExterno_controller extends classModulo
    {
        /**
        * @var array $action  Vector donde se almacenan los links de la aplicacion
        */
        var $action = array();
        /**
        * @var array $request Vector donde se almacenan los datos pasados por request
        */
        var $request = array();
		
		
        /************************************************************
        * Constructor de la clase
        ************************************************************/
        function app_DocumentoExterno_controller(){}

		
        /************************************************************ 
        Funcion principal del modulo 
		@return boolean
	    ************************************************************/
		function main()
		{
			$request = $_REQUEST;
						
			$url[0]='app';//Tipo de Modulo
			$url[1]='DocumentoExterno';//Nombre del Modulo
			$url[2]='controller';//tipo controller...
			$url[3]='MenuBodegas';//Metodo.
			$url[4]='datos';//vector de $_request.
			$arreglo[0]='EMPRESA';//Sub Titulo de la Tabla
						
			//Generar busqueda de Permisos SQL
			$permiso = AutoCarga::factory("Permisos", "", "app","DocumentoExterno");
			$datos=$permiso->BuscarPermisos(); 

			// Menu de empresas con permiso 
			$forma = gui_theme_menu_acceso("SELECCIONE EMPRESA",$arreglo,$datos,$url,ModuloGetURL('system','Menu')); 
			$this->salida=$forma;
				
			return true; 
		}


        /***************************************************************
         * Funcion de menu de bodegas
        ***************************************************************/
		function MenuBodegas()
		{
			/*Crear el Menu de bodegas*/
			$request = $_REQUEST;
			$empresa_id = $request['datos']['empresa_id'];

			$vista = AutoCarga::factory("DocumentoExternoHTML", "views", "app","DocumentoExterno");

			$sql = AutoCarga::factory("Permisos", "", "app","DocumentoExterno");
			$datos['bodegas'] = $sql->ColocarBodegas(UserGetUID(), $empresa_id);

			$action['volver'] = ModuloGetURL("app","DocumentoExterno","controller","main");

			$this->salida=$vista->MenuBodegas($action, $datos, $empresa_id);
			
			return true;
		}


		/********************************************************************************
         * Funcion que visualiza el formulario para el registro del documento externo
        ********************************************************************************/
		function VerFormularioDocumentoExterno()
		{
			$request = $_REQUEST;
			$empresa_id = $request['datos']['empresa_id'];
			$centro_utilidad = $request['datos']['centro_utilidad'];
			$bodega = $request['datos']['bodega'];

			$vista = AutoCarga::factory("DocumentoExternoHTML", "views", "app","DocumentoExterno");
			$sql = Autocarga::factory("DocumentoExternoSQL", "", "app","DocumentoExterno");

			$farmacias = $sql->ObtenerFarmacias($empresa_id);

			$action['volver'] = ModuloGetURL("app","DocumentoExterno","controller","main");
			$action['GuardarDocumentoExterno'] = ModuloGetURL("app","DocumentoExterno","controller","GuardarDocumentoExterno");

			$action['MenuPedidos'] = ModuloGetURL("app","DocumentoExterno","controller","MenuPedidos")."&datos[empresa_id]=".$empresa_id/*."&datos[farmacia_id]=".$farmacia_id*/."&datos[centro_utilidad]=".$centro_utilidad."&datos[bodega]=".$bodega;

			$this->salida=$vista->FormularioDocumentoExterno($action, /*$datos, $pedido_id, $empresa_id, $centro_utilidad, $bodega, */$farmacias);

			return true;
		}


		/***************************************************************
         * Función que guarda el documento externo
        ***************************************************************/
		function GuardarDocumentoExterno()
		{
			$datos = $_REQUEST;

			$sql = Autocarga::factory("DocumentoExternoSQL", "", "app","DocumentoExterno");

			$vista = AutoCarga::factory("DocumentoExternoHTML", "views", "app","DocumentoExterno");

	 		$datos['farmacia'] = explode(" - ", $datos['farmacia']);

	 		$datos['empresa_id'] = $datos['farmacia'][0];
	 		$datos['centro_utilidad'] = $datos['farmacia'][1];
	 		$datos['bodega'] = $datos['farmacia'][2];

	 		$sql->GuardarDocumentoExterno($datos);

	 		$action['volver'] = ModuloGetURL("app","DocumentoExterno","controller","main");

			$mensaje = "EL DOCUMENTO EXTERNO HA SIDO GUARDADO EXITOSAMENTE";

			$this->salida=$vista->FormaMensajeModulo($action, $mensaje);

			return true;
		}
}