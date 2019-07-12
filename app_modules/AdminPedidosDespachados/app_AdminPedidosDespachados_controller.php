<?php
	/**
  * @package DUANA & CIA
  * @version 1.0 $Id: app_AdminPedidosDespachados_controller.php,v 1.0 $
  * @copyright DUANA & CIA 29-OCT-2012
  * @author L.G.T.L
  */
  /** 
  * Clase Control: AdminPedidosDespachados
  * Responsabilidad: Clase encargada del control de llamado de metodos en el modulo
  **/

    class app_AdminPedidosDespachados_controller extends classModulo
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
        function app_AdminPedidosDespachados_controller(){}

		
        /************************************************************ 
        Funcion principal del modulo 
		@return boolean
	    ************************************************************/
		function main()
		{
			$request = $_REQUEST;
						
			$url[0]='app';//Tipo de Modulo
			$url[1]='AdminPedidosDespachados';//Nombre del Modulo
			$url[2]='controller';//tipo controller...
			$url[3]='MenuBodegas';//Metodo.
			$url[4]='datos';//vector de $_request.
			$arreglo[0]='EMPRESA';//Sub Titulo de la Tabla
						
			//Generar busqueda de Permisos SQL
			$permiso = AutoCarga::factory("Permisos", "", "app","AdminPedidosDespachados");
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

			$vista = AutoCarga::factory("AdminPedidosDespachadosHTML", "views", "app","AdminPedidosDespachados");

			$sql = AutoCarga::factory("Permisos", "", "app","AdminPedidosDespachados");
			$datos['bodegas'] = $sql->ColocarBodegas(UserGetUID(), $empresa_id);

			$action['volver'] = ModuloGetURL("app","AdminPedidosDespachados","controller","main");

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
			$destinatario_despacho = $request['destinatario_despacho'];

			$vista = AutoCarga::factory("AdminPedidosDespachadosHTML", "views", "app","AdminPedidosDespachados");

			$sql = Autocarga::factory("AdminPedidosDespachadosSQL", "", "app","AdminPedidosDespachados");

			if(isset($pedido_id) && $destinatario_despacho == 0) {
  		 		$pedidos = $sql->ObtenerPedidoCliente($pedido_id);
  		 		$datos['destinatario_despacho'] = 0;
  		 		$datos['destinatario_despacho_label'] = "Cliente";
  		 	} elseif(isset($pedido_id) && $destinatario_despacho == 1) {
  		 		$pedidos = $sql->ObtenerPedidoFarmacia($empresa_id,$pedido_id);
  		 		$datos['destinatario_despacho'] = 1;
  		 		$datos['destinatario_despacho_label'] = "Farmacia";
  		 	}
  		 	
  		 	/*if(!empty($pedido_id)) {
  		 		$pedidos = $sql->ObtenerPedidosFarmacia($empresa_id,$pedido_id);
  		 	}*/

			//$action['volver'] = ModuloGetURL("app","AdminPedidosDespachados","controller","MenuBodegas")."&datos[empresa_id]=".$request['datos']['empresa_id'];
			$action['volver'] = ModuloGetURL("app","AdminPedidosDespachados","controller","main");
			$action['MenuPedidos'] = ModuloGetURL("app","AdminPedidosDespachados","controller","MenuPedidos")."&datos[empresa_id]=".$empresa_id."&datos[farmacia_id]=".$farmacia_id."&datos[centro_utilidad]=".$centro_utilidad."&datos[bodega]=".$bodega;

			$action['AdministrarDespachosPedido'] = ModuloGetURL("app","AdminPedidosDespachados","controller","AdministrarDespachosPedido");
			
			$datos['pedidos'] = $pedidos;

			$this->salida=$vista->MenuPedidos($action, $datos, $pedido_id);

			return true;
		}

		/***************************************************************
         * Funcion para visualizar el formulario de detalle del despacho del pedido
        ***************************************************************/
		function AdministrarDespachosPedido()
		{
			$request = $_REQUEST;
			$pedido_id = $request['pedido_id'];
			$despacho = $request['despacho'];
			$destinatario_despacho = $request['destinatario_despacho'];

			$vista = AutoCarga::factory("AdminPedidosDespachadosHTML", "views", "app","AdminPedidosDespachados");

			$sql = Autocarga::factory("AdminPedidosDespachadosSQL", "", "app","AdminPedidosDespachados");

	 		$datos['transportadoras'] = $sql->ObtenerTransportadoras();

	 		//$action['volver'] = ModuloGetURL("app","AdminPedidosDespachados","controller","MenuBodegas")."&datos[empresa_id]=".$request['datos']['empresa_id'];
			$action['volver'] = ModuloGetURL("app","AdminPedidosDespachados","controller","main");
			$action['GuardarDetalleDespachosPedido'] = ModuloGetURL("app","AdminPedidosDespachados","controller","GuardarDetalleDespachosPedido");

			$this->salida=$vista->AdministrarDespachosPedido($action, $datos, $pedido_id, $despacho, $destinatario_despacho);

			return true;
		}

		/***************************************************************
         * Funcion que guarda la información del detalle del despacho del pedido
        ***************************************************************/
		function GuardarDetalleDespachosPedido()
		{
			$datos = $_REQUEST;
			$despacho = $_REQUEST['despacho'];
			$destinatario_despacho = $_REQUEST['destinatario_despacho'];
			
			$vista = AutoCarga::factory("AdminPedidosDespachadosHTML", "views", "app","AdminPedidosDespachados");

			$sql = Autocarga::factory("AdminPedidosDespachadosSQL", "", "app","AdminPedidosDespachados");

			if($destinatario_despacho == 0) {
				$sql->crearRegistroDetalleDespachoPedidoCliente($datos);
				$idUltimoDetalleDespachos = $sql->obtenerIdUltimoDetalleDespachosCliente();

				for($i = 0; $i < count($despacho); $i++) 
				{
					$sql->asignarDetalleDespachosCliente($idUltimoDetalleDespachos[0]['max'], $despacho[$i]);
				}
			} elseif($destinatario_despacho == 1) {
				$sql->crearRegistroDetalleDespachoPedidoFarmacia($datos);
				$idUltimoDetalleDespachos = $sql->obtenerIdUltimoDetalleDespachosFarmacia();

				for($i = 0; $i < count($despacho); $i++) 
				{
					$sql->asignarDetalleDespachosFarmacia($idUltimoDetalleDespachos[0]['max'], $despacho[$i]);
				}
			}

			$action['volver'] = ModuloGetURL("app","AdminPedidosDespachados","controller","Main");

			$mensaje = "SE HA GUARDARDO EXITOSAMENTE LOS DETALLES DEL DESPACHO DEL PEDIDO";

			$this->salida=$vista->FormaMensajeModulo($action,$mensaje);

			return true;
		}		
}