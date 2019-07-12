<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_Formulacion_Externa_Facturacion_controller.php,v 1.29 2010/02/17 14:46:54 johanna Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  /**
  * Clase Control: Formulacion_Externa_Facturacion
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.29 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
    class app_Formulacion_Externa_Facturacion_controller extends classModulo
    {
        /**
        * @var array $action  Vector donde se almacenan los links de la aplicacion
        */
        var $action = array();
		
        /**
        * @var array $request Vector donde se almacenan los datos pasados por request
        */
        var $request = array();
		/**
        * @var string $direccion_carpeta  Variable donde se guarda la direccion general de Archvos
		* para descarga.
        */
		var $direccion_carpeta;
		
        /**
        * Constructor de la clase
        */
        function app_Formulacion_Externa_Facturacion_controller(){}
        /**
        * Funcion principal del modulo
        *
        * @return boolean
        */
        
		function main()
		{	
			
			$request = $_REQUEST;
						
			$url[0]='app';                         //Tipo de Modulo
			$url[1]='Formulacion_Externa_Facturacion';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			$url[3]='Menu';   //Metodo.
			$url[4]='datos';						//vector de $_request.
			$arreglo[0]='EMPRESA';					//Sub Titulo de la Tabla
			$arreglo[1]='CENTRO UTILIDAD';					//Sub Titulo de la Tabla
						
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("Permisos", "", "app","Formulacion_Externa_Facturacion");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$datos=$obj_busqueda->BuscarPermisos(); 
		
		//Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
										// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
			$forma = gui_theme_menu_acceso("FACTURACION",$arreglo,$datos,$url,ModuloGetURL('system','Menu')); 
			$this->salida=$forma;
			
			/*$weekNum = date('W') - date('W',strtotime(date('Y-m-01'))) + 1;
			
			$this->salida .= "estas en la semana $weekNum de ".date("F");*/
      /*			
			//(nombre de la Tabla Acceso,
			FormaMostrarMenuHospitalizacion($forma); //Invocar un view, para mostrar la informacion.
      */
			 /*7 días; 24 horas; 60 minutos; 60 segundos*/
			/* $utilidades=AutoCarga::factory('ClaseUtil');
			 $fecha = "2011-09-01";
			 $periodos=$utilidades->Obtener_PeriodosSemanales($fecha);
			 echo(" <pre> 1 arreglo ".print_r($periodos, true)." </pre> ");*/
			return true; 

		}
		
      /*
      * FUNCION DE MENU PRINCIPAL
      */
		function Menu()
		{
			/*Crear el Menú de Opciones*/
			$request = $_REQUEST;
			if($request['datos'])
			{
			SessionSetVar("empresa_id",$request['datos']['empresa_id']);      
			SessionSetVar("datos_facturacion",$request['datos']);
			}
			$datos = SessionGetVar("datos_facturacion");
			$Obj_Menu=AutoCarga::factory("Formulacion_Externa_Facturacion_MenuHTML", "views", "app","Formulacion_Externa_Facturacion");
			$action['volver'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","main");

			$this->salida=$Obj_Menu->Menu($action,$datos);

			return true;
		}
		
    //$sql->mensajeDeError
	
		
		function Crear_Factura()
		{
		$request = $_REQUEST;

		$sql = AutoCarga::factory("Consultas_Formulacion_Externa_Facturacion","classes","app","Formulacion_Externa_Facturacion");
		$plan = $sql->ObtenerContratoId($_REQUEST['datos']['empresa_id']);
		//print_r($plan);

		/*EN CASO DE NO ENCONTRAR PLAN, ENTONCES TOMAR EL PLAN DEL REQUEST*/
		$planes_request = explode("@",$request['buscador']['plan_id']);
		if(!empty($request['buscador']['plan_id']))
		{
		$plan['plan_id'] = $planes_request[0];
		$plan['tipo_tercero_id'] = $planes_request[1];
		$plan['tercero_id'] = $planes_request[2];
		}
	
	
		$porcentaje = ModuloGetVar("","","ESM_PorcentajeIntermediacion");
		if(!empty($request['buscador']['fecha_inicio']) && !empty($request['buscador']['fecha_final']))
		  {
		 
			$sql->Borrar_Temporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid']);
			$fecha_i=explode("/",$request['buscador']['fecha_inicio']);
			$fecha_inicio=$fecha_i[2]."-".$fecha_i[1]."-".$fecha_i[0];
			$fecha_f=explode("/",$request['buscador']['fecha_final']);
			$fecha_final=$fecha_f[2]."-".$fecha_f[1]."-".$fecha_f[0];
			
			
			$AGRUPAR_TRASLADOS = $sql->Obtener_TrasladosESM($_REQUEST['datos']['empresa_id'],$fecha_inicio,$fecha_final,$plan);
			$AGRUPAR_DESPACHOS = $sql->Obtener_DespachosESM($_REQUEST['datos']['empresa_id'],$fecha_inicio,$fecha_final,$plan);
			$AGRUPAR_DISPENSACION = $sql->Obtener_DispensacionESM($_REQUEST['datos']['empresa_id'],$fecha_inicio,$fecha_final,$plan);
			$AGRUPAR_DISPENSACION_PENDIENTES = $sql->Obtener_DispensacionPendientesESM($_REQUEST['datos']['empresa_id'],$fecha_inicio,$fecha_final,$plan);
			
		 //   print_r($AGRUPAR_DESPACHOS);
		 
			if(!empty($plan) && $porcentaje!="")
			  {
			  $token=$sql->Insertar_CabeceraTemporal($_REQUEST,$plan,$fecha_inicio,$fecha_final);
				if($token)
				{
				  foreach($AGRUPAR_TRASLADOS as $key => $valor)
				  {
				  //$Precio=$sql->Buscar_PrecioProducto_Lista($_REQUEST['datos']['empresa_id'],$plan['plan_id'],$valor['codigo_producto']);
				  $ok=$sql->Insertar_DetalleTemporal_1($_REQUEST,$valor['codigo_producto'],$valor['total'],$plan['plan_id'],"fe_facturacion_tmpl_traslados",$valor['sw_bodegamindefensa'],$valor['sw_entregado_off']);
				  }
				  
				  //print_r($AGRUPAR_TRASLADOS);
				  //print_r($AGRUPAR_DESPACHOS);
				  foreach($AGRUPAR_DESPACHOS as $key => $valor)
				  {
				  
					$ok=$sql->Insertar_DetalleTemporal_1($_REQUEST,$valor['codigo_producto'],$valor['total'],$plan['plan_id'],"fe_facturacion_tmpl_despachados",$valor['sw_bodegamindefensa'],$valor['sw_entregado_off']);
				  }
				  
				  foreach($AGRUPAR_DISPENSACION as $key => $valor)
				  {
				  
				  $porc=0;
								  
					
					$ok=$sql->Insertar_DetalleTemporal($_REQUEST,$valor['codigo_producto'],$valor['total'],round(($valor['valor']/$valor['total']),2),"fe_facturacion_tmpl_dispensados",0);
				  }
				  
				   foreach($AGRUPAR_DISPENSACION_PENDIENTES as $key => $valor)
				  {
				  $ok=$sql->Insertar_DetalleTemporal($_REQUEST,$valor['codigo_producto'],$valor['total'],($valor['valor']/$valor['total']),"fe_facturacion_tmpl_dispensacion_pendientes",0);
				  }
				}
			  }
		  }
		$action['buscar'] = ModuloGetURL('app','Formulacion_Externa_Facturacion','controller','Crear_Factura');

		$porcentaje = ModuloGetVar("","","ESM_PorcentajeIntermediacion");
		$DATOS=$sql->Buscar_CabeceraTemporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid']);
		$DATOS_TRASLADOS=$sql->Buscar_DetalleTemporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid'],"fe_facturacion_tmpl_traslados");
		$DATOS_DESPACHOS=$sql->Buscar_DetalleTemporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid'],"fe_facturacion_tmpl_despachados");
		$DATOS_DISPENSADOS=$sql->Buscar_DetalleTemporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid'],"fe_facturacion_tmpl_dispensados");
		$DATOS_PENDIENTES_DISPENSADOS=$sql->Buscar_DetalleTemporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid'],"fe_facturacion_tmpl_dispensacion_pendientes");
		$planes = $sql->planes_parametrizados();


		$Obj_Form=AutoCarga::factory("Formulacion_Externa_Facturacion_HTML", "views", "app","Formulacion_Externa_Facturacion");
		$action['volver'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
		$action['confirmar'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","ConfirmaFactura")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
		$Html_Form=$Obj_Form->Vista_Formulario($action,$planes,$request['buscador'],$DATOS,$DATOS_TRASLADOS,$DATOS_DESPACHOS,$DATOS_DISPENSADOS,$DATOS_PENDIENTES_DISPENSADOS,$plan);
		$this->salida = $Html_Form;
		return true;
		}
    
    function ConfirmaFactura()
		{
		$request = $_REQUEST;

		IncludeFileModulo("Remotos_Formulacion_Externa_Facturacion","RemoteXajax","app","Formulacion_Externa_Facturacion");
		$this->SetXajax(array("Listado_Bodegas"),null,"ISO-8859-1");

		$sql = AutoCarga::factory("Consultas_Formulacion_Externa_Facturacion","classes","app","Formulacion_Externa_Facturacion");
		$plan = $sql->ObtenerContratoId($_REQUEST['datos']['empresa_id']);

		$planes_request = explode("@",$request['buscador']['plan_id']);
		if(!empty($request['buscador']['plan_id']))
		{
		$plan['plan_id'] = $planes_request[0];
		$plan['tipo_tercero_id'] = $planes_request[1];
		$plan['tercero_id'] = $planes_request[2];
		}
	
		$Documento=$sql->InformacionDocumento($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid']);
		$DATOS=$sql->Buscar_CabeceraTemporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid']);
		$DATOS_DETALLE=$sql->AgrupacionProductos($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid']);
		//print_r($DATOS_DETALLE);

		$Token=$sql->Insertar_Factura($_REQUEST,$Documento,$DATOS);
		//$Token=0;
		if($Token)
		{
		  foreach($DATOS_DETALLE as $key=>$valor)
		  {
		  $ok=$sql->Insertar_DetalleFactura($valor,$Documento);
		  $valor_total=$valor_total+$valor['valor_total'];
		  }
		  $token=$sql->Estado_DESPACHOS_TRASLADOS($DATOS['empresa_id'],$DATOS['fecha_inicio'],$DATOS['fecha_fin'],$Documento,"inv_bodegas_movimiento_traslados_esm");
		  $token=$sql->Estado_DESPACHOS_TRASLADOS($DATOS['empresa_id'],$DATOS['fecha_inicio'],$DATOS['fecha_fin'],$Documento,"inv_bodegas_movimiento_despacho_campania");
		  $token=$sql->Estado_DISPENSADOS_PENDIENTES($DATOS['empresa_id'],$DATOS['fecha_inicio'],$DATOS['fecha_fin'],$Documento,"esm_formulacion_despachos_medicamentos",$plan);
		  $token=$sql->Estado_DISPENSADOS_PENDIENTES($DATOS['empresa_id'],$DATOS['fecha_inicio'],$DATOS['fecha_fin'],$Documento,"esm_formulacion_despachos_medicamentos_pendientes",$plan);
		  $Modifica= $sql->Asignar_ValorTotal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid'],$Documento,$valor_total);
		  $BorrarTemporal= $sql->Borrar_Temporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid']);
		}
		$url = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
			
		$html .= "<script>";
		if(!$Token)
		  $html .= " history.go(-1) ";
		  else 
			{
			$html .= " alert(\"Fue Creada con Exito, la Factura ".$Documento['prefijo']."-".$Documento['numeracion']."\");";
			$html .= "window.location=\"".$url."\";";
			}
		$html .= "</script>";
		$this->salida=$html;
		return true;
		}
   
	/*
	* FUNCION QUE PERMITE GENERAR LA INTERFAZ PARA LA CREACION DE RIPS DE FORMULACION
	*/
	function DescargaRips()
	{
	$request = $_REQUEST;
	$datos = SessionGetVar("datos_facturacion");
	$ctl = Autocarga::factory("ClaseUtil");
	$sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","Formulacion_Externa_Facturacion");
	$sql1 = AutoCarga::factory("Consultas_Formulacion_Externa_Facturacion","classes","app","Formulacion_Externa_Facturacion");
	$html=AutoCarga::factory("Formulacion_Externa_Facturacion_HTML", "views", "app","Formulacion_Externa_Facturacion");
	$planes = $sql1->planes_parametrizados();
		
	if(count($request['facturas'])>1)
	$token=$sql->RegistrarEnvio($datos,$request,$request['buscador']);
		
	if(!empty($request['buscador']['plan_id']))
		{
	$facturas =$sql->BuscarFacturas($datos,$request['buscador']);
		}
	
	
	
	$action['guardar_envio'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","DescargaRips",array("buscador"=>$request['buscador']));
	$action['generacion_rips'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Rips",array("adicionales"=>$request['buscador'],"buscador"=>$token));
	$action['buscador'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","DescargaRips");
	$action['volver'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Menu");
	$this->salida=$html->MenuRips($action,$planes,$request['buscador'],$facturas,$token);
	return true;
	}
	
	
	/*
	* INFORMES RIPS.
	* A PARTIR DE LOS ENVIOS GENERADOS EN EL MODULO, ESTA FUNCION PERMITE
	* DESCARGAR LOS RIPS DE LA FORMULACION DIARIA.
	*/
	function Rips()
	{
	global $ConfigAplication;
	$request = $_REQUEST;
	$datos = SessionGetVar("datos_facturacion");
	
	$sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","Formulacion_Externa_Facturacion");
	$sql1 = AutoCarga::factory("Consultas_Formulacion_Externa_Facturacion","classes","app","Formulacion_Externa_Facturacion");
	$html=AutoCarga::factory("Formulacion_Externa_Facturacion_HTML", "views", "app","Formulacion_Externa_Facturacion");
	$this->direccion_carpeta= $ConfigAplication['DIR_SIIS']."tmp/RIPS/";
	$clase= $ConfigAplication['DIR_SIIS']."classes/RipsDuana/";
	
	
	if(!empty($request['datos_envio']))
		{
		$rips = AutoCarga::factory("RipsDuana");
		mkdir($this->direccion_carpeta,0777);
		
		$rips->DireccionClase=$clase;
		$comprimir=$rips->MenuOpcion('1',$datos,$request,$this->direccion_carpeta);
		}

	
	$envios = $sql->ConsultarEnvios($datos,$request['buscador']);
	
	$planes = $sql1->planes_parametrizados();
	$action['volver'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","DescargaRips");
	$this->salida = $html->DescargaRips($action,$planes,$request['buscador'],$request['datos_envio'],$envios,$comprimir);
	return true;
	}
	
	
	
   function Glosas()
		{
	$request = $_REQUEST;
	$datos = SessionGetVar("datos_facturacion");
	
    IncludeFileModulo("Remotos_Formulacion_Externa_Facturacion","RemoteXajax","app","Formulacion_Externa_Facturacion");
	$this->SetXajax(array("Listado_ConceptoEspecifico"),null,"ISO-8859-1");
    
    $sql = AutoCarga::factory("Consultas_Formulacion_Externa_Facturacion","classes","app","Formulacion_Externa_Facturacion");
    /*$plan = $sql->ObtenerContratoId($datos['empresa_id']);*/
    /*print_r($datos);*/
    if(!empty($request['buscador']['prefijo']) || !empty($request['buscador']['numero']) || !empty($request['buscador']['fecha_inicio']) && !empty($request['buscador']['fecha_final']))
      {
        $DATOS=$sql->BuscarFacturas($datos['empresa_id'],$datos['ssiid'],$_REQUEST['buscador']['factura'],$request['buscador']['fecha_inicio'],$request['buscador']['fecha_final'],$request['buscador']['prefijo'],$request['buscador']['numero']);
       } 
       
    $action['buscar'] = ModuloGetURL('app','Formulacion_Externa_Facturacion','controller','Glosas');
    $Obj_Form=AutoCarga::factory("Formulacion_Externa_Facturacion_HTML", "views", "app","Formulacion_Externa_Facturacion");
    $action['volver'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Menu");
    $action['crear_glosa'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Verificar_Glosa")."&datos[empresa_id]=".$datos['empresa_id']."&datos[ssiid]=".$datos['ssiid']."";
    $Html_Form=$Obj_Form->Vista_Facturas($action,$DATOS);
    $this->salida = $Html_Form;
	return true;
	}
		
	function Verificar_Glosa()
	{
	$request = $_REQUEST;

	IncludeFileModulo("Remotos_Formulacion_Externa_Facturacion","RemoteXajax","app","Formulacion_Externa_Facturacion");
	$this->SetXajax(array("Listado_Temporales"),null,"ISO-8859-1");

	$sql = AutoCarga::factory("Consultas_Formulacion_Externa_Facturacion","classes","app","Formulacion_Externa_Facturacion");
	$DATOS=$sql->Buscar_GlosasFacturas($_REQUEST['datos']['empresa_id'],$_REQUEST['prefijo'],$_REQUEST['factura_fiscal']);
	
	$action['buscar'] = ModuloGetURL('app','Formulacion_Externa_Facturacion','controller','Glosas');
	$Obj_Form=AutoCarga::factory("Formulacion_Externa_Facturacion_HTML", "views", "app","Formulacion_Externa_Facturacion");
	$action['volver'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
	$action['crear_glosa'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Crear_Glosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";

	if(empty($DATOS))
	{
	$url = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Crear_NuevaGlosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&prefijo=".$_REQUEST['prefijo']."&factura_fiscal=".$_REQUEST['factura_fiscal']."";
	}
	else
		{
		$url = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Modificar_Glosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$DATOS['esm_glosa_id']."";
		}
      
    $html .= "<script>";
    $html .= "window.location=\"".$url."\";";
    $html .= "</script>";
    $this->salida=$html;
	
	return true;
	}
	
	function Crear_NuevaGlosa()
	{
	$request = $_REQUEST;

	IncludeFileModulo("Remotos_Formulacion_Externa_Facturacion","RemoteXajax","app","Formulacion_Externa_Facturacion");
	$this->SetXajax(array("Listado_ConceptoEspecifico"),null,"ISO-8859-1");
	
	$sql = AutoCarga::factory("Consultas_Formulacion_Externa_Facturacion","classes","app","Formulacion_Externa_Facturacion");
	$MotivoGlosa=$sql->Buscar_GlosasMotivos($_REQUEST['datos']['empresa_id'],$_REQUEST['prefijo'],$_REQUEST['factura_fiscal']);
	$glosas_concepto_general=$sql->Buscar_GlosasConceptoGeneral($_REQUEST['datos']['empresa_id'],$_REQUEST['prefijo'],$_REQUEST['factura_fiscal']);
	$DATOS=$sql->BuscarFactura($_REQUEST['datos']['empresa_id'],$_REQUEST['prefijo'],$_REQUEST['factura_fiscal']);
	
	
	$action['buscar'] = ModuloGetURL('app','Formulacion_Externa_Facturacion','controller','Glosas');
	$action['guardar'] = ModuloGetURL('app','Formulacion_Externa_Facturacion','controller','ConfirmaGlosa');
	$Obj_Form=AutoCarga::factory("Formulacion_Externa_Facturacion_HTML", "views", "app","Formulacion_Externa_Facturacion");
	$action['volver'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Glosas")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
	
	$html = $Obj_Form->Forma_NuevaGlosa($action,$MotivoGlosa,$DATOS,$glosas_concepto_general);
    $this->salida=$html;
	
	return true;
	}
   
	function ConfirmaGlosa()
	{
	$request = $_REQUEST;

	IncludeFileModulo("Remotos_Formulacion_Externa_Facturacion","RemoteXajax","app","Formulacion_Externa_Facturacion");
	$this->SetXajax(array("Listado_Bodegas"),null,"ISO-8859-1");

	$fecha_i=explode("-",$request['fecha_glosa']);
    $fecha_glosa=$fecha_i[2]."-".$fecha_i[1]."-".$fecha_i[0];
	
	$sql = AutoCarga::factory("Consultas_Formulacion_Externa_Facturacion","classes","app","Formulacion_Externa_Facturacion");
	
	$DATOS=$sql->Insertar_GlosaFactura($_REQUEST,$fecha_glosa);
	$url = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Modificar_Glosa")."&sw_glosa_total_factura=".$_REQUEST['sw_glosa_total_factura']."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$DATOS['esm_glosa_id']."";
	$html .= "<script>";
	if(empty($DATOS))
	$html .= " history.go(-1) ";
	else 
	{
	$html .= " alert(\"Fue Creada con Exito, La Nota ".$DATOS['esm_glosa_id']."- La Factura ".$_REQUEST['prefijo']."-".$_REQUEST['factura_fiscal']."\");";
	$html .= "window.location=\"".$url."\";";
	}
	$html .= "</script>";
	$this->salida=$html;
	return true;
	}

	function Modificar_Glosa()
	{
	$request = $_REQUEST;

	IncludeFileModulo("Remotos_Formulacion_Externa_Facturacion","RemoteXajax","app","Formulacion_Externa_Facturacion");
	$this->SetXajax(array("Listado_ConceptoEspecifico","VerGlosa","AnularGlosaDetalle",
                        "AceptarGlosaDetalle","VerGlosa_Total","AceptarGlosaTotal",
                        "AplicarGlosaGeneral","AnularGlosa"),null,"ISO-8859-1");
	$sql = AutoCarga::factory("Consultas_Formulacion_Externa_Facturacion","classes","app","Formulacion_Externa_Facturacion");
	$this->IncludeJS("CrossBrowser");
	$this->IncludeJS("CrossBrowserEvent");
	$this->IncludeJS("CrossBrowserDrag");	
	
  if($_REQUEST['sw_glosa_total_factura']!="")
  {
  $sql->Cambiar_TipoGlosa($_REQUEST['esm_glosa_id'],$_REQUEST['sw_glosa_total_factura']);
  }
	
	$DATOS=$sql->Buscar_GlosaActiva($_REQUEST['esm_glosa_id']);
	$sw_glosa_total_factura = '0';
  if($DATOS['sw_glosa_total_factura']=='0')
		{
		$glosas_valor=$sql->Actualizar_ValorGlosa($_REQUEST['esm_glosa_id']);
		$DATOS_DETALLE=$sql->Buscar_DetalleGlosa($_REQUEST['esm_glosa_id']);
    $sw_glosa_total_factura = '1';
	
		}
	$DATOS=$sql->Buscar_GlosaActiva($_REQUEST['esm_glosa_id']);
	$Obj_Form=AutoCarga::factory("Formulacion_Externa_Facturacion_HTML", "views", "app","Formulacion_Externa_Facturacion");
	$action['volver'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
	$action['glosar'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Glosar")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$_REQUEST['esm_glosa_id']."&opc=1";
	$action['cambiar_tipo_glosa'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Modificar_Glosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$_REQUEST['esm_glosa_id']."&sw_glosa_total_factura=".$sw_glosa_total_factura."";
	$html = $Obj_Form->Forma_Glosa($action,$DATOS,$DATOS_DETALLE);
	$this->salida=$html;
	
	return true;
	}
	
	function Glosar()
	{
	$request = $_REQUEST;

	IncludeFileModulo("Remotos_Formulacion_Externa_Facturacion","RemoteXajax","app","Formulacion_Externa_Facturacion");
	$this->SetXajax(array("GuardarGlosa","Listado_ConceptoEspecifico"),null,"ISO-8859-1");
	
	$sql = AutoCarga::factory("Consultas_Formulacion_Externa_Facturacion","classes","app","Formulacion_Externa_Facturacion");
	
	$DATOS=$sql->Buscar_GlosaActiva($_REQUEST['esm_glosa_id']);
	$MotivoGlosa=$sql->Buscar_GlosasMotivos($_REQUEST['datos']['empresa_id'],$_REQUEST['prefijo'],$_REQUEST['factura_fiscal']);
	$glosas_concepto_general=$sql->Buscar_GlosasConceptoGeneral($_REQUEST['datos']['empresa_id'],$_REQUEST['prefijo'],$_REQUEST['factura_fiscal']);
	if($DATOS['sw_glosa_total_factura']=='0')
			{
			$glosas_valor=$sql->Actualizar_ValorGlosa($_REQUEST['esm_glosa_id']);
			$DetalleFactura=$sql->DetalleFactura($DATOS,$_REQUEST['datos']['empresa_id'],$_REQUEST['esm_glosa_id']);
		
			}
	$DATOS=$sql->Buscar_GlosaActiva($_REQUEST['esm_glosa_id']);
	$Obj_Form=AutoCarga::factory("Formulacion_Externa_Facturacion_HTML", "views", "app","Formulacion_Externa_Facturacion");
	$action['volver'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Modificar_Glosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$_REQUEST['esm_glosa_id']."";
	$action['guardar'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","GuardarGlosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$_REQUEST['esm_glosa_id']."";
	$html = $Obj_Form->Forma_Glosar($action,$DATOS,$DetalleFactura,$MotivoGlosa,$glosas_concepto_general);
	$this->salida=$html;
	
	return true;
	}
	
	/* CORTES DIARIOS */
	   	
    function  Cortes_diarios()
	{
		$request = $_REQUEST;
		$datos = SessionGetVar("datos_facturacion");
		
		$sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","Formulacion_Externa_Facturacion");
		$sql1 = AutoCarga::factory("Consultas_Formulacion_Externa_Facturacion","classes","app","Formulacion_Externa_Facturacion");
		$html=AutoCarga::factory("Formulacion_Externa_Facturacion_HTML", "views", "app","Formulacion_Externa_Facturacion");
		$planes = $sql1->planes_parametrizados();
		$CortesCentro =$sql->ConsultarCorte_GeneralCentro($datos);
		$token=$sql->Definir_Lapso($CortesCentro);
		$CortesCentro =$sql->ConsultarCorte_GeneralCentro($datos);
		
		/*echo(" <pre> Informacion ".print_r($CortesCentro, true)." </pre> ");*/
		$Numero_Formulas=ModuloGetVar("app","Formulacion_Externa_Facturacion","numero_formulas");
		$sql->NumeroFormulas =$Numero_Formulas;
		if(!empty($request['buscador']))
		{
			/*echo(" <pre> REQUEST ".print_r($request, true)." </pre> ");*/
			$FormulacionDiaria=$sql->ConsultaCortes_Mensuales($request['buscador'],$request['buscador']['offset'],$datos);
		}
		
		$NumeroFormulas=$Numero_Formulas;
		$action['crear_corte'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","GenerarCorte",array("buscador"=>$request['buscador']));
		$action['buscar'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Cortes_diarios");
		$action['volver'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Menu");
		$action['paquete'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Cortes_diarios",array("buscador"=>$request['buscador']));
	    $Html_Form=$html->Vista_Formulario($action,$datos,$planes,$request['buscador'],$CortesCentro,$FormulacionDiaria,$NumeroFormulas);
	    $this->salida = $Html_Form;
		return true;
	}

	
	function GenerarCorte()
	{
	$request = $_REQUEST;
	$datos = SessionGetVar("datos_facturacion");
	$sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","Formulacion_Externa_Facturacion");
	$Numero_Formulas=ModuloGetVar("app","Formulacion_Externa_Facturacion","numero_formulas");
	$sql->NumeroFormulas =$Numero_Formulas;
	$CortesCentro =$sql->ConsultarCorte_GeneralCentro($datos);
	if(!empty($request['buscador']))
		{
			$FormulacionDiaria=$sql->ConsultaCortes_Mensuales($request['buscador'],-1,$datos);
		}
	$NumeroRegistros=count($FormulacionDiaria)-2;
	$query = "INSERT INTO ff_cortes
	(
	empresa_id,
	centro_utilidad,
	numero,
	lapso,
	corte_general_id,
	usuario_id,
	fecha_inicial,
	fecha_final
	)
	VALUES
	(
	'".trim($datos['empresa_id'])."',
	'".trim($datos['centro_utilidad'])."',
	".$CortesCentro['numeracion'].",
	".$CortesCentro['lapso'].",
	".$CortesCentro['corte_general_id'].",
	".UserGetUID().",
	 '".trim($sql->DividirFecha($request['buscador']['fecha_inicio']))."',
	 '".trim($sql->DividirFecha($request['buscador']['fecha_final']))."'
	);	";
	
	for($i=0;$i<$NumeroRegistros;$i++)
		{
		$bodegas_doc_id = $FormulacionDiaria[$i]['bodegas_doc_id'];
		$numeracion = $FormulacionDiaria[$i]['numeracion'];
		$formula_id =  $FormulacionDiaria[$i]['formula_id'];
		$plan_id =  $FormulacionDiaria[$i]['plan_id'];
		
		for($j=0;$j<count($FormulacionDiaria['detalle'][$bodegas_doc_id][$numeracion]);$j++)
			{
			$query .= " INSERT INTO ff_cortes_detalle
			(
			item_id,
			empresa_id,
			centro_utilidad,
			numero,
			lapso,
			formula_id,
			plan_id,
			codigo_producto,
			cantidad,
			total_venta
			)
			VALUES
			(
			DEFAULT,
			'".trim($datos['empresa_id'])."',
			'".trim($datos['centro_utilidad'])."',
			".$CortesCentro['numeracion'].",
			".$CortesCentro['lapso'].",
			".$formula_id.",
			".$plan_id.",
			'".trim($FormulacionDiaria['detalle'][$bodegas_doc_id][$numeracion][$j]['codigo_producto'])."',
			".$FormulacionDiaria['detalle'][$bodegas_doc_id][$numeracion][$j]['cantidad'].",
			".$FormulacionDiaria['detalle'][$bodegas_doc_id][$numeracion][$j]['total_venta']."	);	";
			}
			
		for($j=0;$j<count($FormulacionDiaria['detalle_pendientes'][$formula_id]);$j++)
			{
			$query .= " INSERT INTO ff_cortes_detalle
			(
			item_id,
			empresa_id,
			centro_utilidad,
			numero,
			lapso,
			formula_id,
			plan_id,
			codigo_producto,
			cantidad,
			total_venta,
			pendiente_dispensado
			)
			VALUES
			(
			DEFAULT,
			'".trim($datos['empresa_id'])."',
			'".trim($datos['centro_utilidad'])."',
			".$CortesCentro['numeracion'].",
			".$CortesCentro['lapso'].",
			".$formula_id.",
			".$plan_id.",
			'".trim($FormulacionDiaria['detalle_pendientes'][$formula_id][$j]['codigo_producto'])."',
			".$FormulacionDiaria['detalle_pendientes'][$formula_id][$j]['cantidad'].",
			".$FormulacionDiaria['detalle_pendientes'][$formula_id][$j]['total_venta'].",
			'1');	";
			}
		
		}
	$query .= "	UPDATE ff_cortes_mensual
					SET
					ultima_fecha_corte = '".trim($sql->DividirFecha($request['buscador']['fecha_final']))."',
					numero = numero + 1
					WHERE TRUE
					AND empresa_id ='".trim($datos['empresa_id'])."'
					AND centro_utilidad = '".trim($datos['centro_utilidad'])."'
					AND lapso = '".trim($CortesCentro['lapso'])."'
					AND corte_general_id = ".$CortesCentro['corte_general_id'].";	";
					
	$query .= "	UPDATE ff_cortes_generales
					SET
					numeracion = numeracion + 1
					WHERE TRUE
					AND corte_general_id = ".$CortesCentro['corte_general_id']."
					AND empresa_id ='".trim($datos['empresa_id'])."'
					AND centro_utilidad = '".trim($datos['centro_utilidad'])."';	";
	$token=$sql ->EjecutarConsultas($query);
	
	$arreglo['buscador']['lapso']=$CortesCentro['lapso'];
	$arreglo['buscador']['separador']=";";
	$action['cortes'] = ModuloGetURL('app','Formulacion_Externa_Facturacion','controller','DescargaDeCortes',array("buscador"=>$arreglo['buscador']));
	$html = "<script>";
    if(!$token)
	{
      $html .= " alert(\"ERROR AL CREAR EL CORTE\");";
	  $html .= " history.go(-1) ";
	 }
      else 
        {
        $html .= " alert(\"Fue Creado Con Exito, El Corte #".$CortesCentro['numeracion'].", Para Esta Farmacia.\");";
        $html .= "window.location=\"".$action['cortes']."\";";
        }
    $html .= "</script>";
	$this->salida .= $html;
	return true;
	}
	
	/*
	* METODO QUE PERMITE AL USUARIO, DESCARGAR LOS CORTES
	* QUE SE HAN HECHO EN LAS FARMACIAS CON RESPECTO A LA FORMULACION
	* Y DISPENSACION
	*/
	function DescargaDeCortes()
	{
	$request = $_REQUEST;
	$datos = SessionGetVar("datos_facturacion");
	$sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","Formulacion_Externa_Facturacion");
	
	$sql1 = AutoCarga::factory("Consultas_Formulacion_Externa_Facturacion","classes","app","Formulacion_Externa_Facturacion");
	$html=AutoCarga::factory("Formulacion_Externa_Facturacion_HTML", "views", "app","Formulacion_Externa_Facturacion");
	$planes = $sql1->planes_parametrizados();
	
	/*echo(" <pre> REQUEST ".print_r($request, true)." </pre> ");*/
	/*echo(" <pre> DATOS ".print_r($datos, true)." </pre> ");*/
	if(!empty($request['buscador']))
		{
		$FormulasCortes=$sql->ConsultarCortes($request['buscador'],$datos);
		}
	
	$Numero_Formulas=ModuloGetVar("app","Formulacion_Externa_Facturacion","numero_formulas");
	$action['buscar'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","DescargaDeCortes");
	$action['volver'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Menu");
	
	if($request['numero']>0)
	{
	$direccion=$this->GenerarArchivo($request);
	}
	
	$this->salida .= $html->Vista_DescargaCortes($action,$datos,$planes,$request['buscador'],$FormulasCortes,$direccion);
	return true;
	}	
	
	
	/*
	* Funcion Para Generar Archivos de los cortes
	*/
	function GenerarArchivo($request)
	{
	global $ConfigAplication;
	$ctl = Autocarga::factory("ClaseUtil");
	$datos = SessionGetVar("datos_facturacion");
	$sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","Formulacion_Externa_Facturacion");
	$Numero_Formulas=ModuloGetVar("app","Formulacion_Externa_Facturacion","numero_formulas");
	$sql->NumeroFormulas =$Numero_Formulas;
	/*Cre el Directorio Principal de Cortes*/
	mkdir($ConfigAplication['DIR_SIIS']."tmp/cortes/",0777);
		
	if($request['numero']>0)
	{
	/*
	* Genero La Direccion Donde se guardaran los Paquetes del Cortes
	*/
	$direccion = $ConfigAplication['DIR_SIIS']."tmp/cortes/corte".$datos['empresa_id']."".$datos['centro_utilidad']."".$request['buscador']['lapso']."".$request['numero']."/";
		/*
		* Si no existe el directorio, lo creo Guardandolos en una carpeta que se 
		* llamará "cortes(empresa,centro_utilidad,lapso,y el numero del corte)"
		*/
		$ctl->BorrarDirectorio($direccion);
		
		if(!is_dir($direccion))
			{
			mkdir($direccion,0777);
			}
			
			$sql->GenerarCortes($direccion,$request,$datos);
			
	}
	return $direccion;
	}
	
	/*
	* Funcion que Permite Auditar los Cortes de Formulacion hechas
	* a cada Farmacia
	*/
	function AuditoriaCortes()
	{
	$request = $_REQUEST;
	$datos = SessionGetVar("datos_facturacion");
	$sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","Formulacion_Externa_Facturacion");
	$html=AutoCarga::factory("Formulacion_Externa_Facturacion_HTML", "views", "app","Formulacion_Externa_Facturacion");
	
	$this->IncludeJS("CrossBrowser");
	$this->IncludeJS("CrossBrowserEvent");
	$this->IncludeJS("CrossBrowserDrag");	
	
	if(!empty($request['formulario']))
		{
		$sql->Auditar_Corte($request['formulario']);
		}
		
	if(!empty($request['buscador']))
		{
		$datos_= $sql->BuscarCortes_Auditoria($request['buscador'],$request['offset']);
		}
	
	$action['volver'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Menu");
	$action['paginador'] = ModuloGetURL('app','Formulacion_Externa_Facturacion','controller','AuditoriaCortes',array("buscador"=>$request['buscador']));
	$action['buscar'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","AuditoriaCortes");
	$this->salida .= $html->AuditoriaCortes($action,$datos_,$sql->conteo, $sql->pagina);
	return true;
	}
		
	
	function Pre_GenerarFactura()
	{
	$request = $_REQUEST;
	$datos = SessionGetVar("datos_facturacion");
	$sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","Formulacion_Externa_Facturacion");
	$sql1 = AutoCarga::factory("Consultas_Formulacion_Externa_Facturacion","classes","app","Formulacion_Externa_Facturacion");
	
	$planes = $sql1->planes_parametrizados_($datos);
	$ciudades = $sql->CiudadesFacturan_Formulacion();
	$html=AutoCarga::factory("Formulacion_Externa_Facturacion_HTML", "views", "app","Formulacion_Externa_Facturacion");
	
	if(!empty($request['buscador']['lapso']))
		{
		$PreFactura=$sql ->Consultar_PreFactura($request['buscador']);
		}
	/*print_r($request);
	print_r($PreFactura);*/
	$action['volver'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Menu");
	$action['paginador'] = ModuloGetURL('app','Formulacion_Externa_Facturacion','controller','Pre_GenerarFactura',array("buscador"=>$request['buscador']));
	$action['facturar'] = ModuloGetURL('app','Formulacion_Externa_Facturacion','controller','Facturar',array("buscador"=>$request['buscador']));
	$action['buscar'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Pre_GenerarFactura");
	$this->salida=$html ->Pre_GenerarFactura($action,$planes,$ciudades,$request['buscador'],$PreFactura);
	return true;
	}
	
	/*
	* Funcion Controlador, que permite Generar la Factura de Formulacion
	*/
	function Facturar()
	{
	$request = $_REQUEST;
	$datos = SessionGetVar("datos_facturacion");
	$sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","Formulacion_Externa_Facturacion");
	$html=AutoCarga::factory("Formulacion_Externa_Facturacion_HTML", "views", "app","Formulacion_Externa_Facturacion");
	
	$datos_factura = $sql->ConsultarDatos_Factura($datos);
	$detalle_factura = $sql ->ConsultaDatos_Facturacion($request['buscador']);
	
	$token = $sql->CrearFactura($datos_factura,$detalle_factura,$request['buscador']);
	
	$arreglo['buscador']['empresa_id']=$datos_factura['empresa_id'];
	$arreglo['buscador']['prefijo']=$datos_factura['prefijo'];
	$arreglo['buscador']['factura_fiscal']=$datos_factura['numeracion'];
			
	
	$action['volver'] = ModuloGetURL('app','Formulacion_Externa_Facturacion','controller','Pre_GenerarFactura',array("buscador"=>$request['buscador']));
	$action['facturas'] = ModuloGetURL('app','Formulacion_Externa_Facturacion','controller','Facturas',array("buscador"=>$arreglo['buscador']));
	$this->salida = $html->ConfirmacionFactura($action,$token,$datos_factura);
	return true;
	}
	
	
	/*
	* Funcion Para La Impresion de las Facturas de Formulacion
	*/
	function Facturas()
	{
	$request = $_REQUEST;
	$datos = SessionGetVar("datos_facturacion");
	$sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","Formulacion_Externa_Facturacion");
	$sql1 = AutoCarga::factory("Consultas_Formulacion_Externa_Facturacion","classes","app","Formulacion_Externa_Facturacion");
	$html=AutoCarga::factory("Formulacion_Externa_Facturacion_HTML", "views", "app","Formulacion_Externa_Facturacion");
	
	if(!empty($request['buscador']))
		{
		$facturas = $sql->FacturasFormulacion($request['buscador'],$datos,$request['offset']);
		}
	
	$prefijos = $sql ->PrefijosFactura($datos);
	$planes = $sql1->planes_parametrizados();
	
	$action['paginador'] = ModuloGetURL('app','Formulacion_Externa_Facturacion','controller','Facturas',array("buscador"=>$request['buscador']));
	$action['volver'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Menu");
	$action['buscador'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Facturas");
	$this->salida = $html->Facturas($action,$request['buscador'],$facturas,$prefijos,$planes,$sql->conteo, $sql->pagina);
	return true;
	}
	
	
	
	function VerFacturas_Fomulacion()
	{
	$request = $_REQUEST;
	$datos = SessionGetVar("datos_facturacion");
	
	return true;
	}
	
   function ConfirmaCorte()
		{
		$request = $_REQUEST;
		    
		IncludeFileModulo("Remotos_Formulacion_Externa_Facturacion","RemoteXajax","app","Formulacion_Externa_Facturacion");
		$this->SetXajax(array("Listado_Bodegas"),null,"ISO-8859-1");
		    
    $sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","Formulacion_Externa_Facturacion");
    $DATOS=$sql->Buscar_CabeceraTemporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid']);
    $DATOS_DISPENSADOS=$sql->Buscar_DetalleTemporal_Dispensados($_REQUEST['datos']['empresa_id'],$DATOS['corte_tmp_id']);
    $DATOS_PENDIENTES_DISPENSADOS=$sql->Buscar_DetalleTemporal_Dispensados_Pendientes($_REQUEST['datos']['empresa_id'],$DATOS['corte_tmp_id']);
    
     
    $Token=$sql->Insertar_Corte($_REQUEST,$DATOS);
    if($Token!=false)
    {
      foreach($DATOS_DISPENSADOS as $key=>$valor)
      {
      $ok=$sql->Insertar_DetalleCorte($valor,$Token,"esm_corte_dispensacion","esm_corte_dispensacion_id");
      }
      foreach($DATOS_PENDIENTES_DISPENSADOS as $key=>$valor)
      {
      $ok=$sql->Insertar_DetalleCorte($valor,$Token,"esm_corte_dispensacion_pendientes","esm_corte_dispensacion_pendientes_id");
      }
      //$token=$sql->Estado_DESPACHOS_TRASLADOS($DATOS['empresa_id'],$DATOS['fecha_inicio'],$DATOS['fecha_fin'],$Documento,"inv_bodegas_movimiento_traslados_esm");
      //$token=$sql->Estado_DESPACHOS_TRASLADOS($DATOS['empresa_id'],$DATOS['fecha_inicio'],$DATOS['fecha_fin'],$Documento,"inv_bodegas_movimiento_despacho_campania");
      $token=$sql->Estado_DISPENSADOS($DATOS['empresa_id'],$DATOS['fecha_inicio'],$DATOS['fecha_final'],$Token,"esm_formulacion_despachos_medicamentos");
      $token=$sql->Estado_DISPENSADOS($DATOS['empresa_id'],$DATOS['fecha_inicio'],$DATOS['fecha_final'],$Token,"esm_formulacion_despachos_medicamentos_pendientes");
      $BorrarTemporal= $sql->Borrar_Temporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid']);
    }

    $url = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
    $html .= "<script>";
    if(!$Token)
      $html .= " history.go(-1) ";
      else 
        {
        $html .= " alert(\"Fue Creado con Exito, El Corte ".$Token['corte_id']."\");";
        $html .= "window.location=\"".$url."\";";
        }
    $html .= "</script>";
    $this->salida=$html;
    return true;
		}
		


	function Cortes_diarios_Descarga()
	{
		
	$request = $_REQUEST;
		
	$sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","Formulacion_Externa_Facturacion");
	$action['buscar'] = ModuloGetURL('app','Formulacion_Externa_Facturacion','controller','Cortes_diarios_Descarga');
	

	$DATOS=$sql->Buscar_Cabecera_real($_REQUEST['datos']['empresa_id'],$request['buscador']['no_corte']);
	 
	
	$Obj_Form=AutoCarga::factory("ESM_CortesDiarios_HTML", "views", "app","Formulacion_Externa_Facturacion");
	$action['volver'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
	$action['confirmar'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","ConfirmaCorte")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
	//$action['opcion1'] = ModuloGetURL("app","Formulacion_Externa_Facturacion","controller","Crear_NuevoTemporal")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
	$Html_Form=$Obj_Form->Vista_Formulario_Descargas($action,$request['buscador'],$DATOS,$DATOS_TRASLADOS,$DATOS_DESPACHOS,$DATOS_DISPENSADOS,$DATOS_PENDIENTES_DISPENSADOS,$plan);
	$this->salida = $Html_Form;
	return true;
	}
		
   
  }
?>