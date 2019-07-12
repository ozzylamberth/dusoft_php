<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: app_InfoProductosDisponiblesPpl_controller.php,v 1.0
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	/**
	* Clase Control: InfoProductosDisponiblesPpl 
	* Clase encargada del control de llamado de metodos en el modulo
	*
	* @package IPSOFT-SIIS
	/*/
	
	class app_InfoProductosDisponiblesPpl_controller  extends classModulo
	{
	/**
		* Constructor de la clase
	*/
	function app_InfoProductosDisponiblesPpl_controller()
	{}
	/**
        *  Funcion principal del modulo
        *  @return boolean
    */
		function Main()
		{
			$request = $_REQUEST;
			$informacion = AutoCarga::factory('InfoProductosDisponiblesPplSQL', '', 'app', 'InfoProductosDisponiblesPpl');
			$permisos = $informacion->ObtenerPermisos();    
			$ttl_gral = "PEDIDOS DE FARMACIA";
			$mtz[0]='FARMACIAS';
			$url[0] = 'app';
			$url[1] = 'InfoProductosDisponiblesPpl'; 
			$url[2] = 'controller';
			$url[3] = 'Menu'; 
			$url[4] = 'InfoProductosDisponiblesPpl'; 
			$action['volver'] = ModuloGetURL('system', 'Menu');
			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
			return true;
		}    
	/*
		* Funcion de control para el Menu Inicial
		*  @return boolean
	*/
		function Menu()
		{
			$request = $_REQUEST;
						
			if($request['InfoProductosDisponiblesPpl']) SessionSetVar("DatosEmpresaAF",$request['InfoProductosDisponiblesPpl']);
			$emp = SessionGetVar("DatosEmpresaAF");
			$farmacia=$emp['empresa_id'];
			
			$action['continuar'] = ModuloGetURL("app", "InfoProductosDisponiblesPpl", "controller","BuscarInformacion");
		    $action['volver'] = ModuloGetURL("app", "InfoProductosDisponiblesPpl", "controller", "Main");
			$act = AutoCarga::factory("InfoProductosDisponiblesPplHTML", "views", "app", "InfoProductosDisponiblesPpl");
			$this->salida = $act->FormaMenu($action);
			return true;      
		}
		/*
		* Funcion para buscar la informacion de la empresa principal
		*  @return boolean
	*/
		function BuscarInformacion()
		{
		    $request = $_REQUEST;
			$sel = AutoCarga::factory("InfoProductosDisponiblesPplSQL", "", "app", "InfoProductosDisponiblesPpl");
			$rst =$sel->ListaEmpresas();
		    IncludeFileModulo("DatosInformacion","RemoteXajax","app","InfoProductosDisponiblesPpl");
			$this->SetXajax(array("MostrarCentroUtilidad","MostrarBodegas","InformacionFinal"),null,"ISO-8859-1" );
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$action['continuarinf'] = ModuloGetURL("app", "InfoProductosDisponiblesPpl", "controller","BuscarProducto");
		    $action['volver'] = ModuloGetURL("app", "InfoProductosDisponiblesPpl", "controller", "Menu");
			$act = AutoCarga::factory("InfoProductosDisponiblesPplHTML", "views", "app", "InfoProductosDisponiblesPpl");
			$this->salida = $act->FormaEmpresa($action,$rst,$request);
			return true;      
		}
		/*
		* Funcion para buscar un producto
		*  @return boolean
	*/
		function  BuscarProducto()
		{
			$request = $_REQUEST;
				
			$empresa = $request["empresa"];
			$centro = $request["centro"];
			$bodega = $request["bodega"];
			
			$sel = AutoCarga::factory("InfoProductosDisponiblesPplSQL", "", "app", "InfoProductosDisponiblesPpl");
			 IncludeFileModulo("DatoSInformacion","RemoteXajax","app","InfoProductosDisponiblesPpl");
			$this->SetXajax(array("DetalleInformacion","Solicitar"),null,"ISO-8859-1" );
				$conteo =$pagina=0;
			if(!empty($request['buscador'] ))
			{
				$datos=$sel->ObtenerProductos($empresa,$centro,$bodega,$request['buscador'],$request['offset']);
				
				$action['buscador']=ModuloGetURL('app','InfoProductosDisponiblesPpl','controller','BuscarProducto',array("empresa"=>$empresa,"centro"=>$centro,"bodega"=>$bodega));
				$action['paginador'] = ModuloGetURL("app", "InfoProductosDisponiblesPpl", "controller", "BuscarProducto",array("buscador"=>$request['buscador'],"empresa"=>$empresa,"centro"=>$centro,"bodega"=>$bodega));
				$conteo= $sel->conteo;
				$pagina= $sel-> pagina;
			}
      $action['paginador'] = ModuloGetURL("app", "InfoProductosDisponiblesPpl", "controller", "BuscarProducto",array("buscador"=>$request['buscador'],"empresa"=>$empresa,"centro"=>$centro,"bodega"=>$bodega));

			$action['volver'] = ModuloGetURL("app", "InfoProductosDisponiblesPpl", "controller", "BuscarInformacion");
			$act = AutoCarga::factory("InfoProductosDisponiblesPplHTML", "views", "app", "InfoProductosDisponiblesPpl");
			$this->salida =$act->FormaProducto($action,$request['buscador'],$datos,$conteo,$pagina,$empresa,$centro,$bodega);

		    return true; 
		}
		function RealizarDocumentoSolicitud()
		{
		     $request = $_REQUEST;
				
			$empresa = SessionGetVar("empresa");
			$centro = SessionGetVar("centro");
			$bodega = SessionGetVar("bodega");
			$disponibles=$request['disponibles'];
			$producto=$request['producto'];
			$sel = AutoCarga::factory("InfoProductosDisponiblesPplSQL", "", "app", "InfoProductosDisponiblesPpl");
			$datos=$sel->ConsultarInformacionProducto($producto);
			
		    $action['continuar'] = ModuloGetURL("app", "InfoProductosDisponiblesPpl", "controller","GenerarSolicitud",array("producto"=>$producto));
			$action['volver'] = ModuloGetURL("app", "InfoProductosDisponiblesPpl", "controller", "BuscarInformacion");
			$act = AutoCarga::factory("InfoProductosDisponiblesPplHTML", "views", "app", "InfoProductosDisponiblesPpl");
			$this->salida =$act->GenerarDocumento($action,$datos,$disponibles,$producto);
		 return true;
		}
		function GenerarSolicitud()
		{
        $request = $_REQUEST;
        $emp = SessionGetVar("DatosEmpresaAF");
        $farmacia=$emp['empresa_id'];
        $empresa = SessionGetVar("empresa");
        $centro = SessionGetVar("centro");
        $bodega = SessionGetVar("bodega");
        $observar=$request['observar'];
        $producto=$request['producto'];
        $tipo=$request['tipo'];
        $cantidad=$request['txtcantidad'];
        $sel = AutoCarga::factory("InfoProductosDisponiblesPplSQL", "", "app", "InfoProductosDisponiblesPpl");

        $dat=$sel->ConsultarDatosFarmacia($farmacia);
        $centrof=$dat[0]['centro_utilidad'];
        $bode=$dat[0]['bodega'];
        $SelcMax=$sel->SelecMaxSolicitud_productos_a_bodega_principal();
        $solici_prod_a_bod_ppal_id=$SelcMax[0]['solicitud_prod_a_bod_ppal_id'];
        $datos=$sel->IngresoSolicitud_Productos_A_Bodega_principal($farmacia,$centrof,$bode,$observar,$tipo,$empresa);
        $dat=$sel->IngresoProductos_A_Bodega_principal_detalle($solici_prod_a_bod_ppal_id,$farmacia,$centrof,$bode,$producto,$cantidad,$tipo);
        $action['volver'] = ModuloGetURL("app", "InfoProductosDisponiblesPpl", "controller", "Menu");
        $act = AutoCarga::factory("InfoProductosDisponiblesPplHTML", "views", "app", "InfoProductosDisponiblesPpl");
        $this->salida = $act->FormaMostrarDocumentoGenerado($action,$solici_prod_a_bod_ppal_id);
        return true;
			
		
		}
		
	}
?>