<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: app_Compras_Orden_Compras_controller.php,v 1.0
	* @copyright (C) 2010  IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	/**
	* Clase Control: Compras_Orden_Compras
	* Clase encargada del control de llamado de metodos en el modulo
	*
	* @package IPSOFT-SIIS
	/*/
	class app_Compras_Orden_Compras_controller  extends classModulo
	{
	/**
		* Constructor de la clase
	*/
	function app_Compras_Orden_Compras_controller()
	{}
	/**
        *  Funcion principal del modulo
        *  @return boolean
    */
		function Main()
		{
			$request = $_REQUEST;
			$rotacion= AutoCarga::factory('Compras_Orden_ComprasSQL', '', 'app', 'Compras_Orden_Compras');
			$permisos = $rotacion->ObtenerPermisos();    
			$ttl_gral = "COMPRAS";
			$mtz[0]='EMPRESA PRINCIPAL';
			$url[0] = 'app';
			$url[1] = 'Compras_Orden_Compras'; 
			$url[2] = 'controller';
			$url[3] = 'Menu'; 
			$url[4] = 'autoria'; 
			$action['volver'] = ModuloGetURL('system', 'Menu');
			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
			return true;
		}    
	/*
	* Funcion de control para el Menu Inicial
	* @return boolean
	*/
		function Menu()
		{
      $request = $_REQUEST;
      if($request['autoria']) SessionSetVar("DatosEmpresaAF",$request['autoria']);
      $emp = SessionGetVar("DatosEmpresaAF");
      $empresa=$emp['empresa_id'];
      $datos = AutoCarga::factory('Compras_Orden_ComprasSQL', '', 'app', 'Compras_Orden_Compras');
      $permisos = $datos->ListarCentrodeUtilidad($empresa);
      $permisos2= $datos->ObtenerBodegaFarmacia($empresa);   
      $c1=$permisos[$empresa]['descripcion'];
      $ce='CENTRO DE UTILIDAD';
      $cont=$ce." [".$c1."] ";
      $ttl_gral = " CENTRO DE UTILIDAD";
      $mtz[0]=$cont;
      $url[0] = 'app';
      $url[1] = 'Compras_Orden_Compras'; 
      $url[2] = 'controller';
      $url[3] = 'Empresas'; 
      $url[4] = 'Compras_Orden_Compras'; 
      $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Main");
      $this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos2, $url, $action['volver']);
      return true;      
		}
	/*
	   * Funcion que permite Seleccionar Una Opcion del Menu 
	   *  @return boolean
	   */
		function Empresas()
		{
			$request = $_REQUEST;
			$emp = SessionGetVar("DatosEmpresaAF");
			$empresa=$emp['empresa_id'];
			
      $centro_utilidad=$emp['centro_utilidad'];
			$this->SetXajax(array("EmpresaOrdenPedido","Proveedores","PasarVariablesOrden","TProveedores","LTProveedores","ContinuarCrearOC"),"app_modules/Compras_Orden_Compras/RemoteXajax/Compras_Orden_Compras.php","ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$contratacion = AutoCarga::factory('Compras_Orden_ComprasSQL', '', 'app', 'Compras_Orden_Compras');
			SessionSetVar("bodega",$request['Compras_Orden_Compras']['bodega']);
			$bod = SessionGetVar("bodega");
			$action['Pre-orden'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "BuscarPreOrden");
			$action['BuscarPre'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarOrdenes");
			$action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Main");
			$action['unificarcompras'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "UnificarOrdenesCompras");
			$act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");

			$this->salida = $act->FormaMenu($action,$empresa);
			return true;
		}
	/*
	   * Funcion que permite Buscar La Preorden Generada desde la Rotacion 
	   *  @return boolean
	 */
		function BuscarPreOrden()
		{
			$request = $_REQUEST;
			$mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
			$act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
			$conteo =$pagina=0;
				if(!empty($request['buscador']))
				{
					$datos=$mdl->consultarInformacionPreOrden($request['buscador'],$request['offset']);
					$action['buscador']=ModuloGetURL('app','Compras_Orden_Compras','controller','BuscarPreOrden');
					$action['paginador'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "BuscarPreOrden",array("buscador"=>$request['buscador']));
					$conteo= $mdl->conteo;
					$pagina= $mdl-> pagina;
				}
				$action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
				$action['detalle'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "DetallePreorden");
				$this->salida = $act->FormaBuscarDocumento($action,$request['buscador'],$datos,$conteo,$pagina);
				return true;
		}
	/*
	   * Funcion que permite  detallar la pre orden de compras
	   *  @return boolean
	 */
		function DetallePreorden()
		{
        $request = $_REQUEST;
        $emp = SessionGetVar("DatosEmpresaAF");
        $empresa=$emp['empresa_id'];
        SessionSetVar("Farmacia",$request['farmacia_id']);
        $Farmac = SessionGetVar("Farmacia");
        SessionSetVar("preorden",$request['preorden_id']);
        $preorden_id = SessionGetVar("preorden");
        $this->SetXajax(array("InformacionOrdenComp","AsiganarCondiciones","TrasferirInformacion"),"app_modules/Compras_Orden_Compras/RemoteXajax/Compras_Orden_Compras.php","ISO-8859-1");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");
        $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
        $dat=$mdl->ListarProveedoresGeneradosPO($preorden_id);
        $conteo =$pagina=0;
        if(!empty($request['buscador']))
        {
          $datos=$mdl->ConsultarDetallePreOrden($preorden_id,$request['buscador'],$request['offset']);
          $action['buscador']=ModuloGetURL('app','Compras_Orden_Compras','controller','DetallePreorden',array("preorden_id"=>$preorden_id));
          $action['paginador'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "DetallePreorden",array("buscador"=>$request['buscador']));
          $conteo= $mdl->conteo;
          $pagina= $mdl-> pagina;
        }
        $act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
        $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "BuscarPreOrden");
        $this->salida = $act->FormaDetalleDocumento($action,$dat,$datos,$conteo,$pagina,$preorden_id,$empresa);
        return true;
		}
   /*
	   * Funcion que permite generar la orden de compras 
	   *  @return boolean
	 */
	    
		function GenerarOrdenCompras()
		{
			$request = $_REQUEST;
			$Farmac = SessionGetVar("Farmacia");
			$preorden_id = SessionGetVar("preorden");
      SessionSetVar("proveedor",$request['proveed']);
      $proveedo = SessionGetVar("proveedor");
      SessionSetVar("preorden",$request['preorden_id']);
      $preorden = SessionGetVar("preorden");
      SessionSetVar("empres",$request['empresa']);
      $empresa = SessionGetVar("empres");
		  $sel = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
			$rst =$sel->SeleccionarInformacionDetalle($preorden,$proveedo);
			$dat=$sel->insertarOrden_Pedido($proveedo,$empresa);
			$inf=$sel->SeleccionarMaxcompras_ordenes_pedidos($proveedo,$empresa);
			$orden_pedido_id=$inf['0']['numero'];
			$infd=$sel->Ingresarcompras_ordenes_pedidos_detalle($rst,$orden_pedido_id);
			$dtos=$sel->ActuEstado($preorden,$proveedo);
			return true;
		}
   /*
	   * Funcion que permite consultar las ordenes de compras
	   *  @return boolean
	 */
		function ConsultarOrdenes()
		{
      $request = $_REQUEST;
   
      IncludeFileModulo("Compras_Orden_Compras","RemoteXajax","app","Compras_Orden_Compras");
			$this->SetXajax(array("AnularOC"));
                  
      $mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
      $act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
      $conteo =$pagina=0;
      $orden_pedido_id = $request ['orden_pedido_id'];
      $tiposdoc = $mdl->ConsultarTipoId();
      if(!empty($request['buscador']))
      {
        $datos=$mdl->ConsultarOrdenComprasGeneradas($request['buscador'],$request['offset'],$orden_pedido_id);
        $action['buscador']=ModuloGetURL('app','Compras_Orden_Compras','controller','ConsultarOrdenes');
        $action['paginador'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarOrdenes",array("buscador"=>$request['buscador'],"orden_pedido_id"=>$orden_pedido_id));
        $conteo= $mdl->conteo;
        $pagina= $mdl-> pagina;
      }
      $action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
      $action['detalle2'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "DetalleOrdenCompra");
      $action['asignar'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "AsignarCondiciones");
      $this->salida = $act->FormaBuscarDocumentoOrdenCompra($action,$request['buscador'],$datos,$conteo,$pagina,$tiposdoc);
      return true;
		}
    /*
    * Funcion que permite detallar la orden de compras generadas
    *  @return boolean
    */
		function DetalleOrdenCompra()
		{
			$request = $_REQUEST;
			SessionSetVar("empresa_i",$request['empresa_id']);
			$empresa_id = SessionGetVar("empresa_i");
			SessionSetVar("orden_pedid",$request['orden_pedido_id']);
			$orden_pedido_id = SessionGetVar("orden_pedid");
			$tipo_id_tercero=$request['tipo_id_tercero'];
			$tercero_id=$request['tercero_id'];
			$nombre=$request['nombre'];
			$razon=$request['razon_social'];
			$empresa_id = SessionGetVar("empresa_i");
			SessionSetVar("orden_pedid",$request['orden_pedido_id']);
			$orden_pedido_id = SessionGetVar("orden_pedid");
			$mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
			$act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
			$conteo =$pagina=0;
			$datos=$mdl->ConsultarDetalleCompra($orden_pedido_id);
			$dats=$mdl->ConsultarCondicionesEstablecidas();
			$action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarOrdenes",array("orden_pedido_id"=>$orden_pedido_id));
			$this->salida = $act->FormaDetalleDocumentoOrdenCompra($action,$datos,$conteo,$pagina,$tipo_id_tercero,$tercero_id,$nombre,$razon,$orden_pedido_id);
			return true;
		}
	/*
	   * Funcion que permite asignarle Condiciones de orden de compra 
	   *  @return boolean
	 */
		function AsignarCondiciones()
		{
			$request = $_REQUEST;
			SessionSetVar("empresa_i",$request['empresa_id']);
			$empresa_id = SessionGetVar("empresa_i");
			SessionSetVar("orden_pedid",$request['orden_pedido_id']);
			$orden_pedido_id = SessionGetVar("orden_pedid");
			$mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
			$dats=$mdl->ConsultarCondicionesEstablecidas();
			$this->SetXajax(array("TrasferirCondicion"),"app_modules/Compras_Orden_Compras/RemoteXajax/Compras_Orden_Compras.php","ISO-8859-1");
			$act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
			$action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarOrdenes");
			$this->salida = $act->FormaAsiganarCondiciones($action,$orden_pedido_id,$empresa_id,$dats);
			return true;
		}
   /*
	   * Funcion que permite crear el documento de pedido apartir de las ordenes de compras que aun tengan productos pendientes por recibir 
	   *  @return boolean
	 */
		
		function CrearDocumentosPedido()
		{
			$request = $_REQUEST;
			$empresa_id = SessionGetVar("empresa_i");
			$action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarOrdenes");
			$this->salida = $act->FormaAsiganarCondiciones($action,$orden_pedido_id,$empresa_id);  
			return true;
		}
   /*
	   * Funcion que permite Unificar las preordenes de compras por proveedor
	   *  @return boolean
	 */
	
		function UnificacionOrdePedidoxProveedor()
		{
			$request = $_REQUEST;
			SessionSetVar("empresapedido",$request['empresapedido']);
			$empresapedido = SessionGetVar("empresapedido");
			SessionSetVar("proveedor_id",$request['proveedor']);
			$proveedor = SessionGetVar("proveedor_id");
			SessionSetVar("nombre_tercer",$request['nombre_tercero']);
			$nombre_tercero = SessionGetVar("nombre_tercer");
			SessionSetVar("tipo_id_terc",$request['tipo_id_tercero']);
			$tipo_id_tercero = SessionGetVar("tipo_id_terc");
			SessionSetVar("tercero",$request['tercero_id']);
			$tercero_id = SessionGetVar("tercero");
			SessionSetVar("razon_social",$request['razon_social']);
			$razon_social = SessionGetVar("razon_social");
			$this->SetXajax(array("TransfeOrdenPedido"),"app_modules/Compras_Orden_Compras/RemoteXajax/Compras_Orden_Compras.php","ISO-8859-1");
			$act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
			$action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarOrdenes");
			$this->salida = $act->FormaDocumentoOrdenPedido($action,$orden_pedido_id,$empresa_id,$nombre_tercero,$tipo_id_tercero,$tercero_id,$razon_social);  
			return true;
		}
   /*
	   * Funcion que permite  crear y Unificar las ordenes de compras para un documento de pedido
	   *  @return boolean
	 */
		
		function CrearDocumentoYUnificar()
		{
			$request = $_REQUEST;
			$observacion=$request['observa'];
			
      $emp = SessionGetVar("DatosEmpresaAF");
			$empresa=$emp['empresa_id'];
    
      
			$proveedor = SessionGetVar("proveedor_id");
			$nombre_tercero = SessionGetVar("nombre_tercer");
			$tipo_id_tercero = SessionGetVar("tipo_id_terc");
			$tercero_id = SessionGetVar("tercero");
			$razon_social = SessionGetVar("razon_social");
			$empresapedido = SessionGetVar("empresapedido");
      
     
			$mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
		
			$dat=$mdl->ingresarDocumentoDePedido($empresa,$proveedor,$observacion);
			$dt=$mdl->SeleccionarDocumentoDePedido($empresa,$proveedor);
			
			$id=$dt[0]['id'];
			$datos=$mdl->ListarDetalleOrdenPedidoXProveedor($empresa,$proveedor);
    
      if(empty($datos))
      {
        	$datos=$mdl->ListarDetalleOrdenPedidoXProveedorDos($empresa,$proveedor);
      }
      
      if(!empty($datos))
      {
          foreach($datos as $key => $dtl)
          {
          
            $datos2=$mdl->UnificarDatos2($dtl['codigo_producto'],$proveedor);
            $dat2=$mdl->InsertarDatosPendientes($datos2,$id);
			    }
      }
			
			
			$consul=$mdl->ConsultarDocumentoPedidoOP($id);
			$inf=$mdl->SeleccionarMaxiPedido();
	    $pedido_id=$inf[0]['numero'];
			$inOp=$mdl->insertarOrden_Pedido($pedido_id,$proveedor,$empresa,$empresa);
			$maxi=$mdl->SeleccionarMaxcompras_ordenes_pedidos($proveedor,$empresa);
			$numero=$maxi[0]['numero'];
			$inf=$mdl->Ingresarcompras_ordenes_pedidos_detalle_d($consul,$numero);
  		$actu=$mdl->ActualizarSw_unificadaOp($empresa,$proveedor,$numero,$id);
			$act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
			$action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
			$this->salida = $act->FormaDocumentoDePedido($action,$id,$empresa,$nombre_tercero,$tipo_id_tercero,$tercero_id,$razon_social,$numero);
			
      return true;
		}
   /*
	   * Funcion que permite Listar las ordenes de compras por proveedor para ser unificadas
	   *  @return boolean
	 */
		
		function UnificarOrdenesCompras()
		{
			$request = $_REQUEST;
			$mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
			$dat=$mdl->ListarProveedoresOrdenCompra();
			$this->SetXajax(array("MostrarOrdenesCompra","CargarOrdenCompra","DetalleOrdenCompra","cancelarTodoOrdenPedido","unificarTodasOrdenes"),"app_modules/Compras_Orden_Compras/RemoteXajax/Compras_Orden_Compras.php","ISO-8859-1");
			$act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
			$action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
			$this->salida = $act->FormaUnificarOrdenes($action,$dat);  
			return true;
		}
   /*
	   * Funcion que permite Unificar las ordenes de compras por proveedor
	   *  @return boolean
	 */
		
		function UnificarOrdenPedidoProveedor()
		{
			$request = $_REQUEST;
			$emp = SessionGetVar("DatosEmpresaAF");
			$empresa=$emp['empresa_id'];
			SessionSetVar("proveedor",$request['proveedor']);
			$proveedor = SessionGetVar("proveedor");
			$mdl = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
		    $dts=$mdl->SeleccionarInformacionTmp_OrdenPedido($proveedor);
			$dt=$mdl->SeleccionarMaxcompras_ordenes_pedidos($proveedor,$empresa);
			$orden_pedido_id=$dt['0']['numero'];
			
			$infd=$mdl->Ingresarcompras_ordenes_pedidos_d($dts,$orden_pedido_id);
			$actu=$mdl->ActuEstadosOrdenesPedidoUnificadas($dts,$proveedor);
		  $eli=$mdl->Eliminar_tmp_OrdenPedido($proveedor);
			$act = AutoCarga::factory("Compras_Orden_ComprasHTML", "views", "app", "Compras_Orden_Compras");
			$action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
      
      
			if($infd!=true)
			{
			$msg1 = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>".$ingc->mensajeDeError;
			} 
			else
			{
			$msg1 = "EL INGRESO SE HA REALIZADO SATISFACTORIAMENTE";
			}
			    
			$this->salida = $act->FormaMensaje($action,$msg0, $msg1);
			return true;
		}
		
		
		function CrearComprasOrdenesPedidos()
		{
			$request = $_REQUEST;
			
			IncludeFileModulo("Compras_Orden_Compras","RemoteXajax","app","Compras_Orden_Compras");
			$this->SetXajax(array("SeleccionDeProductos","BuscarProductos",
								  "AgregarItemOC","DetalleOC","BorrarItemOC","ConfirmarOC"));
			
			    $this->IncludeJS("CrossBrowser");
				$this->IncludeJS("CrossBrowserEvent");
				$this->IncludeJS("CrossBrowserDrag");	

			
			$form = AutoCarga::factory("Compras_OrdenesPedidosHTML", "views", "app", "Compras_Orden_Compras");
			$sql = AutoCarga::factory("Compras_Orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
			//SeleccionDeProductos
			$Token=$sql->IngresarOrdenCompra($_REQUEST['orden_pedido_id'],$_REQUEST['codigoproveedorid'],$_REQUEST['empresa_id']);
			
			$InfoProveedor=$sql->InformacionTercerosProveedores($_REQUEST['codigoproveedorid']);
			
			$action['volver'] = ModuloGetURL("app", "Compras_Orden_Compras", "controller", "Empresas");
      
			
			$this->salida = $form->FormaOC($action,$InfoProveedor);
			return true;
		}
	}
?>
