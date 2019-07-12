<?php
	/**
  * @package DUANA & CIA
  * @version 1.0 $Id: app_AdminPedidosRecibidos_controller.php,v 1.0 $
  * @copyright DUANA & CIA 29-OCT-2012
  * @author L.G.T.L
  */
  /** 
  * Clase Control: AdminPedidosRecibidos
  * Responsabilidad: Clase encargada del control de llamado de metodos en el modulo
  **/

    class app_AdminPedidosRecibidos_controller extends classModulo
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
        function app_AdminPedidosRecibidos_controller(){}

		
        /************************************************************ 
        Funcion principal del modulo 
		@return boolean
	    ************************************************************/
		function main()
		{
			$request = $_REQUEST;
						
			$url[0]='app';//Tipo de Modulo
			$url[1]='AdminPedidosRecibidos';//Nombre del Modulo
			$url[2]='controller';//tipo controller...
			$url[3]='MenuBodegas';//Metodo.
			$url[4]='datos';//vector de $_request.
			$arreglo[0]='EMPRESA';//Sub Titulo de la Tabla
						
			//Generar busqueda de Permisos SQL
			$permiso = AutoCarga::factory("Permisos", "", "app","AdminPedidosRecibidos");
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

			$vista = AutoCarga::factory("AdminPedidosRecibidosHTML", "views", "app","AdminPedidosRecibidos");

			$sql = AutoCarga::factory("Permisos", "", "app","AdminPedidosRecibidos");
			$datos['bodegas'] = $sql->ColocarBodegas(UserGetUID(), $empresa_id);

			$action['volver'] = ModuloGetURL("app","AdminPedidosRecibidos","controller","main");

			//$this->salida=$vista->MenuBodegas($action,$empresa);
			$this->salida=$vista->MenuBodegas($action, $datos, $empresa_id);
			
			return true;
		}


		/***************************************************************
         * Funcion de menu de pedidos
        ***************************************************************/
		function MenuPedidos()
		{
			$request = $_REQUEST;
			$empresa_id = $request['datos']['empresa_id'];
			$pedido_id = $request['pedido_id'];

			$vista = AutoCarga::factory("AdminPedidosRecibidosHTML", "views", "app","AdminPedidosRecibidos");

			$sql = Autocarga::factory("AdminPedidosRecibidosSQL", "", "app","AdminPedidosRecibidos");
  		 	
  		 	if(isset($pedido_id)) {
  		 		$pedidos = $sql->ObtenerPedidosFarmacia($empresa_id,/*$farmacia_id,$centro_utilidad,$bodega,*/$pedido_id);
  		 	}

			//$action['volver'] = ModuloGetURL("app","AdminPedidosRecibidos","controller","MenuBodegas")."&datos[empresa_id]=".$request['datos']['empresa_id'];
			$action['volver'] = ModuloGetURL("app","AdminPedidosRecibidos","controller","main");
			$action['MenuPedidos'] = ModuloGetURL("app","AdminPedidosRecibidos","controller","MenuPedidos")."&datos[empresa_id]=".$empresa_id."&datos[farmacia_id]=".$farmacia_id."&datos[centro_utilidad]=".$centro_utilidad."&datos[bodega]=".$bodega;
			
			$datos['pedidos'] = $pedidos;

			$this->salida=$vista->MenuPedidos($action, $datos, $pedido_id);

			return true;
		}



		function AdministrarPedidoRecibido()
		{
			$request = $_REQUEST;
			//print_r($request);
			$pedido_id = $request['pedido_id'];
			$solicitud_prod_a_bod_ppal_det_des_id = $request['solicitud_prod_a_bod_ppal_det_des_id'];

			$vista = AutoCarga::factory("AdminPedidosRecibidosHTML", "views", "app","AdminPedidosRecibidos");

			$sql = Autocarga::factory("AdminPedidosRecibidosSQL", "", "app","AdminPedidosRecibidos");

			$detalle_despacho_pedido = $sql->ObtenerDetalleDespachoPedido($pedido_id,$solicitud_prod_a_bod_ppal_det_des_id);

	 		//$action['volver'] = ModuloGetURL("app","AdminPedidosRecibidos","controller","MenuBodegas")."&datos[empresa_id]=".$request['datos']['empresa_id'];
	 		$action['volver'] = ModuloGetURL("app","AdminPedidosRecibidos","controller","main");
			$action['GuardarDetallesPedidoRecibido'] = ModuloGetURL("app","AdminPedidosRecibidos","controller","GuardarDetallesPedidoRecibido");

			$this->salida=$vista->AdministrarPedidoRecibido($action, $detalle_despacho_pedido, $pedido_id);
			return true;
		}

		

		/***************************************************************
         * Funcion que guarda el detalle del pedido recibido
        ***************************************************************/
		function GuardarDetallesPedidoRecibido()
		{

			$datos = $_REQUEST;

			$vista = AutoCarga::factory("AdminPedidosRecibidosHTML", "views", "app","AdminPedidosRecibidos");

			$sql = Autocarga::factory("AdminPedidosRecibidosSQL", "", "app","AdminPedidosRecibidos");

			$sql->GuardarDetallesPedidoRecibido($datos);

			$action['volver'] = ModuloGetURL("app","AdminPedidosRecibidos","controller","main");

			$mensaje = "SE HA GUARDADO EXITOSAMENTE LA INFORMACION DE LO QUE SE RECIBIO DEL PEDIDO EN RELACION A LO QUE SE DESPACHO";

			$this->salida=$vista->FormaMensajeModulo($action,$mensaje);

			return true;
		}
		
}