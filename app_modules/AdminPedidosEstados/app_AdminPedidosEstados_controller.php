<?php
	/**
  * @package DUANA & CIA
  * @version 1.0 $Id: app_AdminPedidosEstados_controller.php,v 1.0 $
  * @copyright DUANA & CIA 29-OCT-2012
  * @author L.G.T.L
  */
  /** 
  * Clase Control: AdminPedidosEstados
  * Responsabilidad: Clase encargada del control de llamado de metodos en el modulo
  **/

    class app_AdminPedidosEstados_controller extends classModulo
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
        function app_AdminPedidosEstados_controller(){}

		
        /************************************************************ 
        Funcion principal del modulo 
		@return boolean
	    ************************************************************/
		function main()
		{
			$request = $_REQUEST;
						
			$url[0]='app';//Tipo de Modulo
			$url[1]='AdminPedidosEstados';//Nombre del Modulo
			$url[2]='controller';//tipo controller...
			$url[3]='MenuBodegas';//Metodo.
			$url[4]='datos';//vector de $_request.
			$arreglo[0]='EMPRESA';//Sub Titulo de la Tabla
						
			//Generar busqueda de Permisos SQL
			$permiso = AutoCarga::factory("Permisos", "", "app","AdminPedidosEstados");
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

			$vista = AutoCarga::factory("AdminPedidosEstadosHTML", "views", "app","AdminPedidosEstados");

			$sql = AutoCarga::factory("Permisos", "", "app","AdminPedidosEstados");
			$datos['bodegas'] = $sql->ColocarBodegas(UserGetUID(), $empresa_id);

			$action['volver'] = ModuloGetURL("app","AdminPedidosEstados","controller","main");

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
			$centro_utilidad = $request['datos']['centro_utilidad'];
			$bodega = $request['datos']['bodega'];
			$pedido_id = $request['pedido_id'];
			$destinatario_pedido = $request['destinatario_pedido'];

			$vista = AutoCarga::factory("AdminPedidosEstadosHTML", "views", "app","AdminPedidosEstados");
			$sql = Autocarga::factory("AdminPedidosEstadosSQL", "", "app","AdminPedidosEstados");

			if(isset($pedido_id) && $destinatario_pedido == 0) {
  		 		$pedidos = $sql->ObtenerPedidosClientes($pedido_id);
  		 		$datos['destinatario_pedido'] = 0;
  		 		$datos['destinatario_pedido_label'] = "Cliente";
  		 	} elseif(isset($pedido_id) && $destinatario_pedido == 1) {
  		 		$pedidos = $sql->ObtenerPedidosFarmacias($empresa_id,$pedido_id);
  		 		$datos['destinatario_pedido'] = 1;
  		 		$datos['destinatario_pedido_label'] = "Farmacia";
  		 	}

			//$action['volver'] = ModuloGetURL("app","AdminPedidosEstados","controller","MenuBodegas")."&datos[empresa_id]=".$request['datos']['empresa_id'];
			$action['volver'] = ModuloGetURL("app","AdminPedidosEstados","controller","main");
			$action['MenuPedidos'] = ModuloGetURL("app","AdminPedidosEstados","controller","MenuPedidos")."&datos[empresa_id]=".$empresa_id/*."&datos[farmacia_id]=".$farmacia_id*/."&datos[centro_utilidad]=".$centro_utilidad."&datos[bodega]=".$bodega;
			
			$datos['pedidos'] = $pedidos;

			$this->salida=$vista->MenuPedidos($action, $datos, $pedido_id, $empresa_id, $centro_utilidad, $bodega);

			return true;
		}

		
		/***************************************************************
         * Función que visualiza el formulario para la asignación de responsable para cada uno de los estados del pedido y cambiar el estado del pedido
        ***************************************************************/
		function ResponsablesEstadosPedido()
		{
			$request = $_REQUEST;
			$empresa_id = $request['empresa_id'];
			$centro_utilidad = $request['centro_utilidad'];
			$bodega = $request['bodega'];
			$pedido_id = $request['datos']['pedido_id'];
			$destinatario_pedido = $request['datos']['destinatario_pedido'];
			$estado = $request['datos']['estado'];

			$sql = Autocarga::factory("AdminPedidosEstadosSQL", "", "app","AdminPedidosEstados");

			if($estado == "Registrado") {
				$siguiente_estado = "Separar";
			} elseif($estado == "Separado")
			{
				$siguiente_estado = "Auditoria";
			} elseif($estado == "Auditado")
			{
				$siguiente_estado = "Proceso de Despacho";
				if($destinatario_pedido == 0) {
					$estado_pedido = $sql->ObtenerEstadoPedidoCliente($pedido_id);
				} elseif($destinatario_pedido == 1) {
					$sw_despacho_pedido = $sql->ObtenerSwDespachoPedidoFarmacia($pedido_id);
				}
			} elseif($estado == "En Despacho")
			{
				$siguiente_estado = "Despachar";
			}

			$estado = strtoupper($estado);

			$vista = AutoCarga::factory("AdminPedidosEstadosHTML", "views", "app","AdminPedidosEstados");

			$responsablesEstadosPedido = "";

			if($destinatario_pedido == 0) {
				$numeroRegistrosEstadosPedido = $sql->VerificarExistenciaEstadosPedidoCliente($pedido_id);

				if($numeroRegistrosEstadosPedido[0]['count'] == 0) {
		 			for($i = 1; $i <= 4; $i++)
	 				{
		 				$sql->crearRegistrosResponsablesEstadosPedidoCliente($pedido_id, $i);
		 			}
		 		} else {
		 			$responsablesEstadosPedido = $sql->ObtenerResponsablesEstadosPedidoCliente($pedido_id);
		 		}

		 		$datos['destinatario_pedido'] = $destinatario_pedido;
		 		$datos['destinatario_pedido_label'] = "CLIENTE";
			} elseif($destinatario_pedido == 1) {
				$numeroRegistrosEstadosPedido = $sql->VerificarExistenciaEstadosPedidoFarmacia($pedido_id);

				if($numeroRegistrosEstadosPedido[0]['count'] == 0) {
		 			for($i = 1; $i <= 4; $i++)
	 				{
		 				$sql->crearRegistrosResponsablesEstadosPedidoFarmacia($pedido_id, $i);
		 			}
		 		} else {
		 			$responsablesEstadosPedido = $sql->ObtenerResponsablesEstadosPedidoFarmacia($pedido_id);
		 		}

		 		$datos['destinatario_pedido'] = $destinatario_pedido;
		 		$datos['destinatario_pedido_label'] = "FARMACIA";
			}

			if(isset($sw_despacho_pedido) || isset($estado_pedido)) {
				if($destinatario_pedido == 0) {
					$datos['estado'] = $estado_pedido[0]['estado'];
				} elseif($destinatario_pedido == 1) {
					$datos['sw_despacho'] = $sw_despacho_pedido[0]['sw_despacho'];
				}
			}
			
  		 	$datos['responsablesEstadosPedido'] = $responsablesEstadosPedido;
	 		$datos['usuarios'] = $sql->ObtenerOperariosBodega();

	 		//$action['volver'] = ModuloGetURL("app","AdminPedidosEstados","controller","MenuBodegas")."&datos[empresa_id]=".$request['datos']['empresa_id'];
	 		$action['volver'] = ModuloGetURL("app","AdminPedidosEstados","controller","main");
			$action['AsignarResponsablesEstadosPedido'] = ModuloGetURL("app","AdminPedidosEstados","controller","AsignarResponsablesEstadosPedido");

			$datos['pedidos'] = $pedidos;

			$this->salida=$vista->ResponsablesEstadosPedido($action, $datos, $pedido_id, $estado, $siguiente_estado, $empresa_id, $centro_utilidad, $bodega);

			return true;
		}

		
		/***************************************************************
         * Funcion de asignación de responsable para cada uno de los estados del pedido
        ***************************************************************/
		function AsignarResponsablesEstadosPedido()
		{
			$request = $_REQUEST;
			$empresa_id = $request['empresa_id'];
			$centro_utilidad = $request['centro_utilidad'];
			$bodega = $request['bodega'];
			$pedido_id = $request['pedido_id'];
			$destinatario_pedido = $request['destinatario_pedido'];

			if($request['responsable_separar'])
			{
				$responsable_id = $request['responsable_separar'];
				$siguiente_estado = 1;
			}

			if($request['responsable_auditar'])
			{
				$responsable_id = $request['responsable_auditar'];
				$siguiente_estado = 2;
			}

			if($request['responsable_despacho'])
			{
				$responsable_id = $request['responsable_despacho'];
				$siguiente_estado = 3;
			}

			if($request['responsable_despachar'])
			{
				$responsable_id = $request['responsable_despachar'];
				$siguiente_estado = 4;
			}

			$vista = AutoCarga::factory("AdminPedidosEstadosHTML", "views", "app","AdminPedidosEstados");

			$sql = Autocarga::factory("AdminPedidosEstadosSQL", "", "app","AdminPedidosEstados");

			if($destinatario_pedido == 0) {
				$sql->EnviarPedidoSiguienteEstadoCliente($pedido_id, $siguiente_estado);
				
				$sql->AsignarResponsableSiguienteEstadoPedidoCliente($pedido_id, $siguiente_estado, $responsable_id);

				$destinatario_pedido_label = "CLIENTE";
			} elseif($destinatario_pedido == 1) {
				$sql->EnviarPedidoSiguienteEstadoFarmacia($pedido_id, $siguiente_estado);
				
				$sql->AsignarResponsableSiguienteEstadoPedidoFarmacia($pedido_id, $siguiente_estado, $responsable_id);

				$destinatario_pedido_label = "FARMACIA";
			}

			$action['volver'] = ModuloGetURL("app","AdminPedidosEstados","controller","MenuPedidos");

			$mensaje = "EL CAMBIO DE ESTADO Y LA ASIGNACION DE RESPONSABLE HAN SIDO REALIZADOS EXITOSAMENTE";

			$this->salida=$vista->FormaMensajeModulo($action,$mensaje,$destinatario_pedido_label, $empresa_id, $centro_utilidad, $bodega);

			return true;
		}		
		
}