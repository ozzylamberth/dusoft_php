<?php
	/**
  * @package DUANA & CIA
  * @version 1.0 $Id: app_PlanillaDespachoPedido_controller.php,v 1.0 $
  * @copyright DUANA & CIA 03-DIC-2012
  * @author L.G.T.L
  */
  /** 
  * Clase Control: PlanillaDespachoPedido
  * Responsabilidad: Clase encargada del control de llamado de metodos en el modulo
  **/

    class app_PlanillaDespachoPedido_controller extends classModulo
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
        function app_PlanillaDespachoPedido_controller(){}

		
        /************************************************************ 
        Funcion principal del modulo 
		@return boolean
	    ************************************************************/
		function main()
		{
			$request = $_REQUEST;
						
			$url[0]='app';//Tipo de Modulo
			$url[1]='PlanillaDespachoPedido';//Nombre del Modulo
			$url[2]='controller';//tipo controller...
			$url[3]='MenuBodegas';//Metodo.
			$url[4]='datos';//vector de $_request.
			$arreglo[0]='EMPRESA';//Sub Titulo de la Tabla
						
			//Generar busqueda de Permisos SQL
			$permiso = AutoCarga::factory("Permisos", "", "app","PlanillaDespachoPedido");
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

			$vista = AutoCarga::factory("PlanillaDespachoPedidoHTML", "views", "app","PlanillaDespachoPedido");

			$sql = AutoCarga::factory("Permisos", "", "app","PlanillaDespachoPedido");
			$datos['bodegas'] = $sql->ColocarBodegas(UserGetUID(), $empresa_id);

			$action['volver'] = ModuloGetURL("app","PlanillaDespachoPedido","controller","main");

			$this->salida=$vista->MenuBodegas($action, $datos, $empresa_id);
			
			return true;
		}


		/***************************************************************
         * Funcion de menu de (detalles de) despachos
        ***************************************************************/
		function MenuDetallesDespachos()
		{
			$request = $_REQUEST;
			//print_r($request);
			$empresa_id = $request['datos']['empresa_id'];
			$pedido_id = $request['pedido_id'];
			$planilla_id = "";
			$planillas_despachos = array();

			$vista = AutoCarga::factory("PlanillaDespachoPedidoHTML", "views", "app","PlanillaDespachoPedido");

			$sql = Autocarga::factory("PlanillaDespachoPedidoSQL", "", "app","PlanillaDespachoPedido");

  		 	if(isset($request['planilla_id'])) {
  		 		$empresa_id = $request['empresa_id'];
  		 		if(!empty($request['planilla_id'])) {
	  		 		$planilla_id = $request['planilla_id'];
	  		 		$planillas_despachos = $sql->ObtenerPlanillasDespachos($empresa_id,$planilla_id);
  		 		}
  		 	}

  		 	$detalles_despachos = $sql->ObtenerDetallesDespachos($empresa_id);
  		 	
			$action['volver'] = ModuloGetURL("app","PlanillaDespachoPedido","controller","main");

			$action['GenerarPlanillaDespachos'] = ModuloGetURL("app","PlanillaDespachoPedido","controller","GenerarPlanillaDespachos");

			$action['MenuPlanillas'] = ModuloGetURL("app","PlanillaDespachoPedido","controller","MenuDetallesDespachos");
			
			$datos['detalles_despachos'] = $detalles_despachos;

			$this->salida=$vista->MenuDetallesDespachos($action, $datos, $pedido_id, $empresa_id, $planilla_id, $planillas_despachos);

			return true;
		}

		/***************************************************************
         * Funcion que genera la planilla de despachos
        ***************************************************************/
		function GenerarPlanillaDespachos()
		{
			$datos = $_REQUEST;
			$empresa_id = $datos['empresa_id'];
			
			$detalle_despachos = $_REQUEST['detalle_despachos'];
			
			$vista = AutoCarga::factory("PlanillaDespachoPedidoHTML", "views", "app","PlanillaDespachoPedido");

			$sql = Autocarga::factory("PlanillaDespachoPedidoSQL", "", "app","PlanillaDespachoPedido");

			$sql->crearPlanillaDespachos($empresa_id);

			$idUltimoPlanillaDespachos = $sql->obtenerIdUltimoPlanillaDespachos();

			for($i = 0; $i < count($detalle_despachos); $i++) 
			{
				if(strpos($detalle_despachos[$i], " - farmacia") !== false) {
					$detalle_despachos[$i] = str_replace(" - farmacia", "", $detalle_despachos[$i]);
					$sql->asignarPlanillaDespachosFarmacias($idUltimoPlanillaDespachos[0]['max'], $detalle_despachos[$i]);
				}

				if(strpos($detalle_despachos[$i], " - cliente") !== false) {
					$detalle_despachos[$i] = str_replace(" - cliente", "", $detalle_despachos[$i]);
					$sql->asignarPlanillaDespachosClientes($idUltimoPlanillaDespachos[0]['max'], $detalle_despachos[$i]);
				}
			}
			
			$action['volver'] = ModuloGetURL("app","PlanillaDespachoPedido","controller","Main");

			$mensaje = "SE HA GUARDARDO EXITOSAMENTE LA PLANILLA DE DESPACHOS NUMERO ".$idUltimoPlanillaDespachos[0]['max']."";

			$this->salida=$vista->FormaMensajeModulo($action,$mensaje,$idUltimoPlanillaDespachos[0]['max']);

			return true;
		}

		/***************************************************************
         * Funcion que visualiza la planilla de despachos
        ***************************************************************/
		function VisualizarPlanillaDespachos()
		{
			$datos = $_REQUEST;
			
			$planilla_despachos_id = $_REQUEST['planilla_despachos_id'];

			$vista = AutoCarga::factory("PlanillaDespachoPedidoHTML", "views", "app","PlanillaDespachoPedido");

			$sql = Autocarga::factory("PlanillaDespachoPedidoSQL", "", "app","PlanillaDespachoPedido");

			$detalles_despachos = $sql->obtenerDetallesDespachosPlanillaDespachos($planilla_despachos_id);
			//print_r($detalles_despachos);

			for($i = 0; $i < count($detalles_despachos); $i++){
				$efc_detalle_despacho[$detalles_despachos[$i]['pedido_id']] = $sql->obtenerEfcDetalleDespachos($detalles_despachos[$i]['pedido_id']);
				/*print_r($efc_detalle_despacho[$detalles_despachos[$i]['pedido_id']]);
				echo "<br><br>";*/
			}

			/*$idUltimoPlanillaDespachos = $sql->obtenerIdUltimoPlanillaDespachos();

			for($i = 0; $i < count($detalle_despachos); $i++) 
			{
				$sql->asignarPlanillaDespachos($idUltimoPlanillaDespachos[0]['max'], $detalle_despachos[$i]);
			}

			$action['volver'] = ModuloGetURL("app","PlanillaDespachoPedido","controller","Main");

			$mensaje = "SE HA GUARDARDO EXITOSAMENTE LA PLANILLA DE DESPACHOS";*/

			$this->salida=$vista->PlanillaDespachos($planilla_despachos_id,$detalles_despachos,$efc_detalle_despacho);

			return true;
		}
}