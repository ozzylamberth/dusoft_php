<?php
	/**
  * @package DUANA & CIA
  * @version 1.0 $Id: app_AdminPedidosConsultas_controller.php,v 1.0 $
  * @copyright DUANA & CIA 24-NOV-2012
  * @author L.G.T.L
  */
  /** 
  * Clase Control: AdminPedidosConsultas
  * Responsabilidad: Clase encargada del control de llamado de metodos en el modulo
  **/

    class app_AdminPedidosConsultas_controller extends classModulo
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
        function app_AdminPedidosConsultas_controller(){}

		
        /************************************************************ 
        Funcion principal del modulo 
		@return boolean
	    ************************************************************/
		function main()
		{
			$request = $_REQUEST;
						
			$url[0]='app';//Tipo de Modulo
			$url[1]='AdminPedidosConsultas';//Nombre del Modulo
			$url[2]='controller';//tipo controller...
			$url[3]='ListadoDespachos';//Metodo.
			$url[4]='datos';//vector de $_request.
			$arreglo[0]='EMPRESA';//Sub Titulo de la Tabla
						
			//Generar busqueda de Permisos SQL
			$permiso = AutoCarga::factory("Permisos", "", "app","AdminPedidosConsultas");
			$datos=$permiso->BuscarPermisos(); 

			// Menu de empresas con permiso 
			$forma = gui_theme_menu_acceso("SELECCIONE EMPRESA",$arreglo,$datos,$url,ModuloGetURL('system','Menu')); 
			$this->salida=$forma;
				
			return true; 
		}


        /***************************************************************
         * Funcion de vizualicación del listado de despachos
        ***************************************************************/
		function ListadoDespachos()
		{
			$request = $_REQUEST;
			
			$vista = AutoCarga::factory("AdminPedidosConsultasHTML", "views", "app","AdminPedidosConsultas");

			$filtro['destinatario'] = $request['destinatario'];

			if($filtro['destinatario'] == "1") {
				$filtro['label_destinatario'] = "Cliente";
			} else if($filtro['destinatario'] == "2") {
				$filtro['label_destinatario'] = "Farmacia";
			} else {
				$filtro['label_destinatario'] = "";
			}

			$filtro['numero_pedido'] = $request['numero_pedido'];

			$filtro['fecha_inicial_vista'] = $request['fecha_inicial'];
			
			$filtro['fecha_inicial'] = explode("/", $request['fecha_inicial']);

			$filtro['fecha_inicial'] = $filtro['fecha_inicial'][2]."-".$filtro['fecha_inicial'][1]."-".$filtro['fecha_inicial'][0]." 00:00:00.000000";
						
			$filtro['fecha_final_vista'] = $request['fecha_final'];

			$filtro['fecha_final'] = explode("/", $request['fecha_final']);

			$filtro['fecha_final'] = $filtro['fecha_final'][2]."-".$filtro['fecha_final'][1]."-".$filtro['fecha_final'][0]." 23:59:59.000000";
						
			$filtro['departamento'] = $request['departamento'];

			$filtro['municipio'] = $request['municipio'];

			$filtro['tipo_pedido'] = $request['tipo_pedido'];

			if($filtro['tipo_pedido'] == "0") {
				$filtro['label_tipo_pedido'] = "Normal";
			} else if($filtro['tipo_pedido'] == "1") {
				$filtro['label_tipo_pedido'] = "General";
			} else {
				$filtro['label_tipo_pedido'] = "";
			}
			
			$sql = AutoCarga::factory("AdminPedidosConsultasSQL", "", "app","AdminPedidosConsultas");
			
			if(!empty($filtro['destinatario']) || !empty($filtro['numero_pedido']) || $filtro['fecha_inicial'] != "-- 00:00:00.000000" || $filtro['fecha_final'] != "-- 23:59:59.000000" || !empty($filtro['departamento']) || !empty($filtro['municipio']) || !empty($filtro['tipo_pedido'])) {
				$despachos_pedidos = $sql->ObtenerDespachosPedidos($filtro,$request['offset']);
			}

			for($i = 0; $i < count($despachos_pedidos); $i++) {
				$despachos_pedidos[$i]['diferencia_fechas'] = $this->diferenciaEntreFechas($despachos_pedidos[$i]['fecha_recibido'], $despachos_pedidos[$i]['fecha_registro_ingreso_inventario']);
			}

			$action['volver'] = ModuloGetURL("app","AdminPedidosConsultas","controller","main");
			$action['ListadoDespachos'] = ModuloGetURL("app","AdminPedidosConsultas","controller","ListadoDespachos");
			$action['paginador'] = ModuloGetURL("app","AdminPedidosConsultas","controller","ListadoDespachos",array("fecha_inicial"=>$filtro['fecha_inicial_vista'], "fecha_final"=>$filtro['fecha_final_vista'], "departamento"=>$filtro['departamento'], "municipio"=>$filtro['municipio'], "tipo_pedido"=>$filtro['tipo_pedido']));

			$this->salida=$vista->ListadoDespachos($action, $filtro, $despachos_pedidos, $sql->conteo, $sql->pagina);
			
			return true;
		}


		/***************************************************************
         * Función que cálcula la diferencia entre fechas con formato "aa-mm-dd hh:mm:ss.dddddd" (donde "dddddd" son cifras fraccionarias de los segundos)
        ***************************************************************/
		function diferenciaEntreFechas($fecha_inicial, $fecha_final)
		{
		   $fecha_inicial = explode(".", $fecha_inicial);
		   $fecha_inicial = strtotime($fecha_inicial[0]);

		   $fecha_final = explode(".", $fecha_final);
		   $fecha_final = strtotime($fecha_final[0]);

		   $resultado = $fecha_final - $fecha_inicial;

		   $resultado = $resultado / 60 / 60 / 24;

		   $resultado = round($resultado);

		   if($resultado < 0) {
		   		$resultado = "";
		   }

		   return $resultado;
		}
		
}