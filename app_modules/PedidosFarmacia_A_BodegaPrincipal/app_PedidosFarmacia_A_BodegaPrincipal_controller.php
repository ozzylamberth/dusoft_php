<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: app_PedidosFarmacia_A_BodegaPrincipal_controller.php,v 1.0
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	/**
	* Clase Control: PedidosFarmacia_A_BodegaPrincipal
	* Clase encargada del control de llamado de metodos en el modulo
	*
	* @package IPSOFT-SIIS
	/*/
	
	class app_PedidosFarmacia_A_BodegaPrincipal_controller  extends classModulo
	{
	/**
		* Constructor de la clase
	*/
	function app_PedidosFarmacia_A_BodegaPrincipal_controller()
	{}
	/**
        *  Funcion principal del modulo
        *  @return boolean
    */
		function Main()
		{
			$request = $_REQUEST;
			$contratacion = AutoCarga::factory('PedidosFarmacia_A_BodegaPrincipalSQL', '', 'app', 'PedidosFarmacia_A_BodegaPrincipal');
			$permisos = $contratacion->ObtenerPermisos();    
			$ttl_gral = "PEDIDOS DE FARMACIA";
			$mtz[0]='EMPRESA GENERAL';
			$mtz[1]='CENTRO';
			$mtz[2]='BODEGAS';
			$url[0] = 'app';
			$url[1] = 'PedidosFarmacia_A_BodegaPrincipal'; 
			$url[2] = 'controller';
			$url[3] = 'MenuPedido'; 
			$url[4] = 'PedidosFarmacia_A_BodegaPrincipal'; 
			$action['volver'] = ModuloGetURL('system', 'Menu');
			
			/*$html .= "	<form id=\"Formulario\" name=\"Formulario\" action=\"\" method=\"POST\" onSubmit=\"Validar_Permisos(document.Formulario); return false;\">";
			$html .= "	<table class=\"modulo_table_list\" width=\"100%\">";
			$html .= "		<tr class=\"modulo_list_claro\">";
			$html .= "			<td >";
			$html .= "			<b>CODIGO DE LA FARMACIA:</b> ";
			$html .= "			</td>";
			$html .= "			<td>";
			$html .= "				<input type=\"text\" style=\"width:100%\" class=\"input-text\" name=\"empresa_id\" id=\"empresa_id\">	";
			$html .= "			</td>";
			$html .= "			<td>";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"SELECCIONAR FARMACIA\"> ";
			$html .= "			</td>";
			$html .= "		</tr>";
			$html .= "	</table>";
			$html .= "	</form>";*/
			
			$html .= "<script>";
			$html .= "	function Validar_Permisos()";
			$html .= "	{";
			$html .= "switch (Formulario.empresa_id.value)";
			$html .= "{";
			foreach($permisos as $k=>$valor)
			{
			$action['carga']=ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "Menu",array("PedidosFarmacia_A_BodegaPrincipal"=>array("descripcion"=>$k,"descripcion1"=>$k,"descripcion2"=>$k,"empresa_id"=>$valor['empresa_id'],"centro_utilidad"=>$valor['centro_utilidad'],"usuario_id"=>UserGetUID())));
			$html .= " case '".$valor['empresa_id']."':";
			$html .= "window.location=\"".$action['carga']."\";";
			$html .= " break;";
			
			}
			$html .= " default:";
			$html .= "	alert('NO EXISTE LA FARMACIA, O NO TIENES PERMISO PARA ACCEDER A ELLA');";
			$html .= "	return false;";
			$html .= "	}";
			$html .= "	}";
			$html .= "</script>";
			
			
			
			$mtz[0].= $html;
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
			if($request['PedidosFarmacia_A_BodegaPrincipal']) 
			SessionSetVar("DatosEmpresaAFS",$request['PedidosFarmacia_A_BodegaPrincipal']);
			$emp = SessionGetVar("DatosEmpresaAFS");
			$empresa=$emp['empresa_id'];
			$centro_utilidad=$emp['centro_utilidad'];
			$contratacion = AutoCarga::factory('PedidosFarmacia_A_BodegaPrincipalSQL', '', 'app', 'PedidosFarmacia_A_BodegaPrincipal');
			$permisos = $contratacion->ListarCentrodeUtilidad($empresa);
			$permisos2= $contratacion->ObtenerBodegaFarmacia($empresa,$centro_utilidad);   
			$c1=$permisos[$empresa]['descripcion'];
			$ce='CENTRO DE UTILIDAD';
			$cont=$ce." [".$c1."] ";
		
			$ttl_gral = " CENTRO DE UTILIDAD";
			$mtz[0]=$cont;
			$url[0] = 'app';
			$url[1] = 'PedidosFarmacia_A_BodegaPrincipal'; 
			$url[2] = 'controller';
			$url[3] = 'MenuPedido'; 
			$url[4] = 'PedidosFarmacia_A_BodegaPrincipal'; 
			$action['volver'] = ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "Main");
			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos2, $url, $action['volver']);
			return true;      
		}
	/*
		* Funcion Contiene Un Menu Con Diferentes Opciones de Pedido:
		* - Realizar el documento de pedido
		* - Consultar los documentos de pedido
		*  @return boolean
	*/
		function MenuPedido()
		{
			$request = $_REQUEST;
			if($request['PedidosFarmacia_A_BodegaPrincipal']) 
			SessionSetVar("DatosEmpresaAFS",$request['PedidosFarmacia_A_BodegaPrincipal']);
			
			$emp = SessionGetVar("DatosEmpresaAFS");
			
			$empresa=$emp['empresa_id'];
			$centro=$emp['centro_utilidad'];
		    $contratacion = AutoCarga::factory('PedidosFarmacia_A_BodegaPrincipalSQL', '', 'app', 'PedidosFarmacia_A_BodegaPrincipal');
			SessionSetVar("bodega",$request['PedidosFarmacia_A_BodegaPrincipal']['bodega']);
			/*$bod = SessionGetVar("bodega");*/
			$bod = $emp['bodega'];
			SessionSetVar("bodegaDesc",$request['PedidosFarmacia_A_BodegaPrincipal']['descripcion3']);
          	$bodegades = SessionGetVar("bodegaDesc");
		    $this->SetXajax(array("EmpresaDestino_","MostrarCentroUtilidad","MostrarBodegas","ValidarInformacion_EmpresaDestino"),"app_modules/PedidosFarmacia_A_BodegaPrincipal/RemoteXajax/DatosPedido.php","ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$contratacion = AutoCarga::factory('PedidosFarmacia_A_BodegaPrincipalSQL', '', 'app', 'PedidosFarmacia_A_BodegaPrincipal');
            $aux = $contratacion->Consultar_Empresa_aux($empresa,$centro,$bod);
			/*print_r($emp);*/
			$action['SolPB'] = ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "Pedidos_Productos_Bodega_Principal");
			$action['consulatadoc'] = ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "ConsultarDocumentosDePedidoDetalle",array("bodega"=>$bod));
			$action['volver'] = ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "Main");
			$act = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalHTML", "views", "app", "PedidosFarmacia_A_BodegaPrincipal");
			$this->salida = $act->FormaMenu($action,$emp,$bod,$aux);
			return true;
		}
	/*
		* Funcion que contiene un menu para los tipos de productos 
		*  @return boolean
	*/
		function Pedidos_Productos_Bodega_Principal()
		{
			$request = $_REQUEST;
			$emp = SessionGetVar("DatosEmpresaAFS");
			$empresa=$emp['empresa_id'];
			$empresa_id=$reques['empresa'];
			$centro=$emp['centro_utilidad'];
			$this->SetXajax(array("ValidarSelec","MostrarMensaje","MostrarFormaGenerarPedidoCompleta","TransVariablesP"),"app_modules/PedidosFarmacia_A_BodegaPrincipal/RemoteXajax/DatosPedido.php","ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$Centrid=$emp['centro_utilidad'];
			/*$bod = SessionGetVar("bodega");*/
			$bod = $emp['bodega'];
			$bodegades = SessionGetVar("bodegaDesc");
			$mgl = AutoCarga::factory('PedidosFarmacia_A_BodegaPrincipalSQL', '', 'app', 'PedidosFarmacia_A_BodegaPrincipal');
			
      $aux = $mgl->Consultar_Empresa_aux($empresa,$centro,$bod);
			$tipos = $mgl->TipoProductos();
			$ConTmp = $mgl->ConsultarSolicitud_bod_prpal_tmp($empresa,$Centrid,$bod);
           	$action['SolPB'] = ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "GenerarPedido_A_Bodega_Principal");
			$action['volver'] = ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "MenuPedido");
			$act = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalHTML", "views", "app", "PedidosFarmacia_A_BodegaPrincipal");
			$this->salida = $act->FormaMenu2($action,$tipos,$empresa,$Centrid,$bod,$aux);
			return true;
		}
	/*
		* Funcion que permite  Hacer la Busqueda y listar los items de acuerdo al tipo de producto
		*  @return boolean
	*/
		function GenerarPedido_A_Bodega_Principal()
		{
		    $request = $_REQUEST;
		    if($request['tipo_producto_id'])
            SessionSetVar("tipoid",$request['tipo_producto_id']);
			$emp = SessionGetVar("DatosEmpresaAFS");
			$tipo_producto_id=SessionGetVar("tipoid");
			$empresa=$emp['empresa_id'];
			$this->SetXajax(array("Eliminar_Producto_Seleccionado_","ProductosSeleccionados","ValidarDatosProducto","InsertarDatosTmp","EliminarTmp","ValidarSel","MostrarMensaje","MostrarFormaCompleta","TransVariablesP","CancelarCreacion","TransUrl"),"app_modules/PedidosFarmacia_A_BodegaPrincipal/RemoteXajax/DatosPedido.php","ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$contratacion = AutoCarga::factory('PedidosFarmacia_A_BodegaPrincipalSQL', '', 'app', 'PedidosFarmacia_A_BodegaPrincipal');
			/*print_r($emp);*/
			$bod = $emp['bodega'];
			$Centrid=$emp['centro_utilidad'];
			$bodegades = SessionGetVar("bodegaDesc");
			$Pactivo = $contratacion->inv_moleculas();
			$PTera = $contratacion->Inv_med_anatofarmacologico();
			$aux = $contratacion->Consultar_Empresa_aux($empresa,$Centrid,$bod);
			$empresa_destino=$aux[0]['empresa_destino'];
			$centro_destino=$aux[0]['centro_destino'];
			$bogega_destino=$aux[0]['bogega_destino'];
			
			$ConTmp = $contratacion->ConsultarSolicitud_pro_a_bod_prpal_tmp($empresa,$Centrid,$bod);
			if(!empty($request['buscador']))
			{
				$datos=$contratacion->ListarTodoProductosDeFarmacia($tipo_producto_id,$empresa_destino,$bogega_destino,$request['buscador'],$request['offset']);
			
				$action['buscador']=ModuloGetURL('app','PedidosFarmacia_A_BodegaPrincipal','controller','GenerarPedido_A_Bodega_Principal');
				$conteo= $contratacion->conteo;
				$pagina= $contratacion-> pagina;
			}
			$action['paginador'] = ModuloGetURL('app', 'PedidosFarmacia_A_BodegaPrincipal', 'controller', 'GenerarPedido_A_Bodega_Principal',array("buscador"=>$request['buscador']));
			$action['volver'] = ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "Pedidos_Productos_Bodega_Principal");
			$act = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalHTML", "views", "app", "PedidosFarmacia_A_BodegaPrincipal");
			$this->salida = $act->FormaBuscarHacerPedido($action,$Pactivo,$PTera,$datos,$conteo,$pagina,$request['buscador'],$empresa,$Centrid,$bod,$tipo_producto_id,$ConTmp,$empresa_destino,$centro_destino,$bogega_destino);
			return true;
		}
	/*
		*Funcion que permite generar el documento de pedido
	    *  @return boolean
	*/
		function GenerarDocumentoPedido()
		{
			$request = $_REQUEST;
			$emp = SessionGetVar("DatosEmpresaAFS");
			/*$bod = SessionGetVar("bodega");*/
			$bod = $emp['bodega'];
			$empresa=$emp['empresa_id'];
			$Centrid=$emp['centro_utilidad'];
			$bodegades = SessionGetVar("bodegaDesc");
			$observacion=$request['observacion'];
			$tipo_producto_id = SessionGetVar("tipoid");
			$empresa_destino=$request['empresa_destino'];
			$contratacion = AutoCarga::factory('PedidosFarmacia_A_BodegaPrincipalSQL', '', 'app', 'PedidosFarmacia_A_BodegaPrincipal');
			$datmp = $contratacion->Consultartmp($empresa,$Centrid,$bod);
			$tipo_pedido=$request['tipo_pedido'];
			if(!empty($datmp))
			{
				$IngvBoDo=$contratacion->IngresoSolicitud_Productos_A_Bodega_principal($empresa,$Centrid,$bod,$observacion,$tipo_producto_id,$empresa_destino,$tipo_pedido);
				$SelcMax=$contratacion->SelecMaxSolicitud_productos_a_bodega_principal($empresa,$Centrid,$bod,$empresa_destino);
				$solici_prod_a_bod_ppal_id=$SelcMax[0]['solicitud_prod_a_bod_ppal_id'];
				$IngDet=$contratacion->IngresoProductos_A_Bodega_principal_detalle($solici_prod_a_bod_ppal_id,$empresa,$Centrid,$bod,$datmp);
				$DeSoli=$contratacion->EliminarSol_pro_a_bod_prpal_tmp($empresa,$Centrid,$bod);
				$DeAux=$contratacion-> EliminarAux_solicitudes($empresa,$Centrid,$bod);
		   }
			
			$SelcMax=$contratacion->SelecMaxSolicitud_productos_a_bodega_principal($empresa,$Centrid,$bod,$empresa_destino);
			$solici_prod_a_bod_ppal_id=$SelcMax[0]['solicitud_prod_a_bod_ppal_id'];
			$action['volver'] = ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "MenuPedido") ;      
			$act = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalHTML", "views", "app", "PedidosFarmacia_A_BodegaPrincipal");
			$this->salida = $act->FormaMostrarDocumentoGenerado($action,$solici_prod_a_bod_ppal_id,$empresa,$bod,$Centrid);
			return true;
		}
	/*
		* funcion que contiene el menu para consultar de acuerdo al tipo de producto los pedidos que se han realizado
		*  @return boolean
	*/
		function ConsultarDocumentosDePedido()
		{
			$request = $_REQUEST;
			$emp = SessionGetVar("DatosEmpresaAFS");
			$empresa=$emp['empresa_id'];
			/*$bod = SessionGetVar("bodega");*/
			$bod = $emp['bodega'];
			$Centrid=$emp['centro_utilidad'];
			$bodegades = SessionGetVar("bodegaDesc");
			$contratacion = AutoCarga::factory('PedidosFarmacia_A_BodegaPrincipalSQL', '', 'app', 'PedidosFarmacia_A_BodegaPrincipal');
			$tipos = $contratacion->TipoProductos();	
			$action['SolPB'] = ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "ConsultarDocumentosDePedidoDetalle");
			$action['volver'] = ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "MenuPedido");
			$act = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalHTML", "views", "app", "PedidosFarmacia_A_BodegaPrincipal");
			$this->salida = $act->FormaMenu2($action,$tipos,$empresa,$Centrid,$bod);
			return true;
		}
	/*
		*funcion que permite listar y hacer la busqueda de los  documentos de pedido generados.
		* @return boolean
	*/	
		function ConsultarDocumentosDePedidoDetalle()
		{
			$request = $_REQUEST;
		    if($request['bodega'])
            SessionSetVar("codbodega",$request['bodega']);
			$bod=SessionGetVar("codbodega");
		
			$emp = SessionGetVar("DatosEmpresaAFS");
			$empresa=$emp['empresa_id'];
		
			$Centrid=$emp['centro_utilidad'];
		
			$bodegades = SessionGetVar("bodegaDesc");
			$contratacion = AutoCarga::factory('PedidosFarmacia_A_BodegaPrincipalSQL', '', 'app', 'PedidosFarmacia_A_BodegaPrincipal');
			if(!empty($request['buscador']))
			{
				$datos=$contratacion->consulta_solicitud_productos_a_bodega_principal($empresa,$bod,$Centrid,$request['buscador'],$request['offset']);
				$action['buscador']=ModuloGetURL('app','PedidosFarmacia_A_BodegaPrincipal','controller','ConsultarDocumentosDePedidoDetalle',array("tipo_producto_id"=>$tipo_producto_id));
				$conteo= $contratacion->conteo;
				$pagina= $contratacion-> pagina;
			}
			$action['paginador'] = ModuloGetURL('app', 'PedidosFarmacia_A_BodegaPrincipal', 'controller', 'ConsultarDocumentosDePedidoDetalle',array("buscador"=>$request['buscador'],"tipo_producto_id"=>$tipo_producto_id));
			$action['s_id'] = ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "DocumentoPedidoDetallado");
			$action['volver'] = ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "MenuPedido");
			$act = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalHTML", "views", "app", "PedidosFarmacia_A_BodegaPrincipal");
			$this->salida = $act->FormaBuscarDocumento($action,$datos,$conteo,$pagina,$request['buscador'],$emp,$bod);
		    return true;
		}
	/*
		*Funcion que permite listar el detalle del documento de pedido seleccionado
		* @return boolean
	*/
		function DocumentoPedidoDetallado()
		{
			$request = $_REQUEST;
			$solicitud_prod_a_bod_ppal_id=$request['solicitud_prod_a_bod_ppal_id'];
			$emp = SessionGetVar("DatosEmpresaAFS");
			$empresa=$emp['empresa_id'];
			SessionSetVar("codbodega",$request['bodega']);
			$bod=SessionGetVar("codbodega");
			
			$Centrid=$emp['centro_utilidad'];
			$contratacion = AutoCarga::factory('PedidosFarmacia_A_BodegaPrincipalSQL', '', 'app', 'PedidosFarmacia_A_BodegaPrincipal');
			$this->SetXajax(array("CambiarCantidad","TransVariables"),"app_modules/PedidosFarmacia_A_BodegaPrincipal/RemoteXajax/DatosPedido.php","ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
					
			$datos=$contratacion->ObtenerDetalleDeSolicitu($solicitud_prod_a_bod_ppal_id,$empresa,$bod,$Centrid,$TipoProducto,$request['offset']);
			
			$action['volver'] = ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "ConsultarDocumentosDePedidoDetalle");
			$action['paginador'] = ModuloGetURL('app', 'PedidosFarmacia_A_BodegaPrincipal', 'controller', 'DocumentoPedidoDetallado',array("buscador"=>$request['buscador']));
			$act = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalHTML", "views", "app", "PedidosFarmacia_A_BodegaPrincipal");
			$this->salida = $act->FormaDetalleDocumentoPedido($action,$datos,$conteo,$paginaM,$solicitud_prod_a_bod_ppal_id,$empresa,$Centrid,$bod);
			return true;
		}
				
	}
?>