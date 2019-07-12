<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_ESM_Facturacion_controller.php,v 1.29 2010/02/17 14:46:54 johanna Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  /**
  * Clase Control: ESM_Facturacion
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.29 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
    class app_ESM_Facturacion_controller extends classModulo
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
        * Constructor de la clase
        */
        function app_ESM_Facturacion_controller(){}
        /**
        * Funcion principal del modulo
        *
        * @return boolean
        */
        
		function main()
		{	
			
			$request = $_REQUEST;
						
			$url[0]='app';                         //Tipo de Modulo
			$url[1]='ESM_Facturacion';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			$url[3]='Menu';   //Metodo.
			$url[4]='datos';						//vector de $_request.
			$arreglo[0]='EMPRESA';					//Sub Titulo de la Tabla
						
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("Permisos", "", "app","ESM_Facturacion");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$datos=$obj_busqueda->BuscarPermisos(); 
		
		//Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
										// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
			$forma = gui_theme_menu_acceso("FACTURACION",$arreglo,$datos,$url,ModuloGetURL('system','Menu')); 
			$this->salida=$forma;
					
      /*			
			//(nombre de la Tabla Acceso,
			FormaMostrarMenuHospitalizacion($forma); //Invocar un view, para mostrar la informacion.
      */
			return true; 

		}
		
      /*
      * FUNCION DE MENU PRINCIPAL
      */
		function Menu()
		{
			/*Crear el Men� de Opciones*/
			$request = $_REQUEST;
			$Obj_Menu=AutoCarga::factory("ESM_Facturacion_MenuHTML", "views", "app","ESM_Facturacion");
		
			$action['volver'] = ModuloGetURL("app","ESM_Facturacion","controller","main");

			if($request['datos']['empresa_id'])
			SessionSetVar("empresa_id",$request['datos']['empresa_id']);      
			//SessionSetVar("ssiid",$request['datos']['ssiid']);      

			$this->salida=$Obj_Menu->Menu($action);

			return true;
		}
		
    //$sql->mensajeDeError
	
		
		function Crear_Factura()
		{
		$request = $_REQUEST;
		    
    $sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
    $plan = $sql->ObtenerContratoId($_REQUEST['datos']['empresa_id']);
    //print_r($plan);
    $porcentaje = ModuloGetVar("","","ESM_PorcentajeIntermediacion");
    if(!empty($request['buscador']['fecha_inicio']) && !empty($request['buscador']['fecha_final']))
      {
     
        $sql->Borrar_Temporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid']);
        $fecha_i=explode("/",$request['buscador']['fecha_inicio']);
        $fecha_inicio=$fecha_i[2]."-".$fecha_i[1]."-".$fecha_i[0];
        $fecha_f=explode("/",$request['buscador']['fecha_final']);
        $fecha_final=$fecha_f[2]."-".$fecha_f[1]."-".$fecha_f[0];
        
        
        $AGRUPAR_TRASLADOS = $sql->Obtener_TrasladosESM($_REQUEST['datos']['empresa_id'],$fecha_inicio,$fecha_final);
        $AGRUPAR_DESPACHOS = $sql->Obtener_DespachosESM($_REQUEST['datos']['empresa_id'],$fecha_inicio,$fecha_final);
        $AGRUPAR_DISPENSACION = $sql->Obtener_DispensacionESM($_REQUEST['datos']['empresa_id'],$fecha_inicio,$fecha_final);
        $AGRUPAR_DISPENSACION_PENDIENTES = $sql->Obtener_DispensacionPendientesESM($_REQUEST['datos']['empresa_id'],$fecha_inicio,$fecha_final);
        
     //   print_r($AGRUPAR_DESPACHOS);
     
        if(!empty($plan) && $porcentaje!="")
          {
          $token=$sql->Insertar_CabeceraTemporal($_REQUEST,$plan,$fecha_inicio,$fecha_final);
            if($token)
            {
              foreach($AGRUPAR_TRASLADOS as $key => $valor)
              {
              //$Precio=$sql->Buscar_PrecioProducto_Lista($_REQUEST['datos']['empresa_id'],$plan['plan_id'],$valor['codigo_producto']);
              $ok=$sql->Insertar_DetalleTemporal_1($_REQUEST,$valor['codigo_producto'],$valor['total'],$plan['plan_id'],"esm_facturacion_temporal_traslados",$valor['sw_bodegamindefensa'],$valor['sw_entregado_off']);
              }
              
              //print_r($AGRUPAR_TRASLADOS);
              //print_r($AGRUPAR_DESPACHOS);
              foreach($AGRUPAR_DESPACHOS as $key => $valor)
              {
              
                $ok=$sql->Insertar_DetalleTemporal_1($_REQUEST,$valor['codigo_producto'],$valor['total'],$plan['plan_id'],"esm_facturacion_temporal_despachados",$valor['sw_bodegamindefensa'],$valor['sw_entregado_off']);
              }
              
              foreach($AGRUPAR_DISPENSACION as $key => $valor)
              {
              
              $porc=0;
                              
                
                $ok=$sql->Insertar_DetalleTemporal($_REQUEST,$valor['codigo_producto'],$valor['total'],round(($valor['valor']/$valor['total']),2),"esm_facturacion_temporal_dispensados",0);
              }
              
               foreach($AGRUPAR_DISPENSACION_PENDIENTES as $key => $valor)
              {
              $ok=$sql->Insertar_DetalleTemporal($_REQUEST,$valor['codigo_producto'],$valor['total'],($valor['valor']/$valor['total']),"esm_facturacion_temporal_dispensados_pendientes",0);
              }
            }
          }
      }
      $action['buscar'] = ModuloGetURL('app','ESM_Facturacion','controller','Crear_Factura');
    
    $porcentaje = ModuloGetVar("","","ESM_PorcentajeIntermediacion");
    $DATOS=$sql->Buscar_CabeceraTemporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid']);
    $DATOS_TRASLADOS=$sql->Buscar_DetalleTemporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid'],"esm_facturacion_temporal_traslados");
    $DATOS_DESPACHOS=$sql->Buscar_DetalleTemporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid'],"esm_facturacion_temporal_despachados");
    $DATOS_DISPENSADOS=$sql->Buscar_DetalleTemporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid'],"esm_facturacion_temporal_dispensados");
    $DATOS_PENDIENTES_DISPENSADOS=$sql->Buscar_DetalleTemporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid'],"esm_facturacion_temporal_dispensados_pendientes");
    
    
   
    $Obj_Form=AutoCarga::factory("ESM_Facturacion_HTML", "views", "app","ESM_Facturacion");
    $action['volver'] = ModuloGetURL("app","ESM_Facturacion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
    $action['confirmar'] = ModuloGetURL("app","ESM_Facturacion","controller","ConfirmaFactura")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
    //$action['opcion1'] = ModuloGetURL("app","ESM_Facturacion","controller","Crear_NuevoTemporal")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
    $Html_Form=$Obj_Form->Vista_Formulario($action,$request['buscador'],$DATOS,$DATOS_TRASLADOS,$DATOS_DESPACHOS,$DATOS_DISPENSADOS,$DATOS_PENDIENTES_DISPENSADOS,$plan);
    $this->salida = $Html_Form;
		return true;
		}
    
    function ConfirmaFactura()
		{
		$request = $_REQUEST;
		    
		IncludeFileModulo("Remotos_ESM_Facturacion","RemoteXajax","app","ESM_Facturacion");
		$this->SetXajax(array("Listado_Bodegas"),null,"ISO-8859-1");
		    
    $sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
    $plan = $sql->ObtenerContratoId($_REQUEST['datos']['empresa_id']);
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
      $token=$sql->Estado_DISPENSADOS_PENDIENTES($DATOS['empresa_id'],$DATOS['fecha_inicio'],$DATOS['fecha_fin'],$Documento,"esm_formulacion_despachos_medicamentos");
      $token=$sql->Estado_DISPENSADOS_PENDIENTES($DATOS['empresa_id'],$DATOS['fecha_inicio'],$DATOS['fecha_fin'],$Documento,"esm_formulacion_despachos_medicamentos_pendientes");
      $Modifica= $sql->Asignar_ValorTotal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid'],$Documento,$valor_total);
      $BorrarTemporal= $sql->Borrar_Temporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid']);
    }
    $url = ModuloGetURL("app","ESM_Facturacion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
        
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
   
   function Glosas()
		{
		$request = $_REQUEST;
		
    IncludeFileModulo("Remotos_ESM_Facturacion","RemoteXajax","app","ESM_Facturacion");
	$this->SetXajax(array("Listado_ConceptoEspecifico"),null,"ISO-8859-1");
    
    $sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
    $plan = $sql->ObtenerContratoId($_REQUEST['datos']['empresa_id']);
    //print_r($_REQUEST);
    if(!empty($request['buscador']['prefijo']) || !empty($request['buscador']['numero']) || !empty($request['buscador']['fecha_inicio']) && !empty($request['buscador']['fecha_final']))
      {
        $DATOS=$sql->BuscarFacturas($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid'],$_REQUEST['buscador']['factura'],$request['buscador']['fecha_inicio'],$request['buscador']['fecha_final'],$request['buscador']['prefijo'],$request['buscador']['numero']);
       } 
       
    $action['buscar'] = ModuloGetURL('app','ESM_Facturacion','controller','Glosas');
    $Obj_Form=AutoCarga::factory("ESM_Facturacion_HTML", "views", "app","ESM_Facturacion");
    $action['volver'] = ModuloGetURL("app","ESM_Facturacion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
    $action['crear_glosa'] = ModuloGetURL("app","ESM_Facturacion","controller","Verificar_Glosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
    $Html_Form=$Obj_Form->Vista_Facturas($action,$DATOS);
    $this->salida = $Html_Form;
	return true;
	}
		
	function Verificar_Glosa()
	{
	$request = $_REQUEST;

	IncludeFileModulo("Remotos_ESM_Facturacion","RemoteXajax","app","ESM_Facturacion");
	$this->SetXajax(array("Listado_Temporales"),null,"ISO-8859-1");

	$sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
	$DATOS=$sql->Buscar_GlosasFacturas($_REQUEST['datos']['empresa_id'],$_REQUEST['prefijo'],$_REQUEST['factura_fiscal']);
	
	$action['buscar'] = ModuloGetURL('app','ESM_Facturacion','controller','Glosas');
	$Obj_Form=AutoCarga::factory("ESM_Facturacion_HTML", "views", "app","ESM_Facturacion");
	$action['volver'] = ModuloGetURL("app","ESM_Facturacion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
	$action['crear_glosa'] = ModuloGetURL("app","ESM_Facturacion","controller","Crear_Glosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";

	if(empty($DATOS))
	{
	$url = ModuloGetURL("app","ESM_Facturacion","controller","Crear_NuevaGlosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&prefijo=".$_REQUEST['prefijo']."&factura_fiscal=".$_REQUEST['factura_fiscal']."";
	}
	else
		{
		$url = ModuloGetURL("app","ESM_Facturacion","controller","Modificar_Glosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$DATOS['esm_glosa_id']."";
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

	IncludeFileModulo("Remotos_ESM_Facturacion","RemoteXajax","app","ESM_Facturacion");
	$this->SetXajax(array("Listado_ConceptoEspecifico"),null,"ISO-8859-1");
	
	$sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
	$MotivoGlosa=$sql->Buscar_GlosasMotivos($_REQUEST['datos']['empresa_id'],$_REQUEST['prefijo'],$_REQUEST['factura_fiscal']);
	$glosas_concepto_general=$sql->Buscar_GlosasConceptoGeneral($_REQUEST['datos']['empresa_id'],$_REQUEST['prefijo'],$_REQUEST['factura_fiscal']);
	$DATOS=$sql->BuscarFactura($_REQUEST['datos']['empresa_id'],$_REQUEST['prefijo'],$_REQUEST['factura_fiscal']);
	
	
	$action['buscar'] = ModuloGetURL('app','ESM_Facturacion','controller','Glosas');
	$action['guardar'] = ModuloGetURL('app','ESM_Facturacion','controller','ConfirmaGlosa');
	$Obj_Form=AutoCarga::factory("ESM_Facturacion_HTML", "views", "app","ESM_Facturacion");
	$action['volver'] = ModuloGetURL("app","ESM_Facturacion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
	
	$html = $Obj_Form->Forma_NuevaGlosa($action,$MotivoGlosa,$DATOS,$glosas_concepto_general);
    $this->salida=$html;
	
	return true;
	}
   
	function ConfirmaGlosa()
	{
	$request = $_REQUEST;

	IncludeFileModulo("Remotos_ESM_Facturacion","RemoteXajax","app","ESM_Facturacion");
	$this->SetXajax(array("Listado_Bodegas"),null,"ISO-8859-1");

	$fecha_i=explode("-",$request['fecha_glosa']);
    $fecha_glosa=$fecha_i[2]."-".$fecha_i[1]."-".$fecha_i[0];
	
	$sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
	
	$DATOS=$sql->Insertar_GlosaFactura($_REQUEST,$fecha_glosa);
	$url = ModuloGetURL("app","ESM_Facturacion","controller","Modificar_Glosa")."&sw_glosa_total_factura=".$_REQUEST['sw_glosa_total_factura']."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$DATOS['esm_glosa_id']."";
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

	IncludeFileModulo("Remotos_ESM_Facturacion","RemoteXajax","app","ESM_Facturacion");
	$this->SetXajax(array("Listado_ConceptoEspecifico","VerGlosa","AnularGlosaDetalle",
                        "AceptarGlosaDetalle","VerGlosa_Total","AceptarGlosaTotal",
                        "AplicarGlosaGeneral","AnularGlosa"),null,"ISO-8859-1");
	$sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
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
	$Obj_Form=AutoCarga::factory("ESM_Facturacion_HTML", "views", "app","ESM_Facturacion");
	$action['volver'] = ModuloGetURL("app","ESM_Facturacion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
	$action['glosar'] = ModuloGetURL("app","ESM_Facturacion","controller","Glosar")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$_REQUEST['esm_glosa_id']."&opc=1";
	$action['cambiar_tipo_glosa'] = ModuloGetURL("app","ESM_Facturacion","controller","Modificar_Glosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$_REQUEST['esm_glosa_id']."&sw_glosa_total_factura=".$sw_glosa_total_factura."";
	$html = $Obj_Form->Forma_Glosa($action,$DATOS,$DATOS_DETALLE);
	$this->salida=$html;
	
	return true;
	}
	
	function Glosar()
	{
	$request = $_REQUEST;

	IncludeFileModulo("Remotos_ESM_Facturacion","RemoteXajax","app","ESM_Facturacion");
	$this->SetXajax(array("GuardarGlosa","Listado_ConceptoEspecifico"),null,"ISO-8859-1");
	
	$sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
	
	$DATOS=$sql->Buscar_GlosaActiva($_REQUEST['esm_glosa_id']);
	$MotivoGlosa=$sql->Buscar_GlosasMotivos($_REQUEST['datos']['empresa_id'],$_REQUEST['prefijo'],$_REQUEST['factura_fiscal']);
	$glosas_concepto_general=$sql->Buscar_GlosasConceptoGeneral($_REQUEST['datos']['empresa_id'],$_REQUEST['prefijo'],$_REQUEST['factura_fiscal']);
	if($DATOS['sw_glosa_total_factura']=='0')
			{
			$glosas_valor=$sql->Actualizar_ValorGlosa($_REQUEST['esm_glosa_id']);
			$DetalleFactura=$sql->DetalleFactura($DATOS,$_REQUEST['datos']['empresa_id'],$_REQUEST['esm_glosa_id']);
		
			}
	$DATOS=$sql->Buscar_GlosaActiva($_REQUEST['esm_glosa_id']);
	$Obj_Form=AutoCarga::factory("ESM_Facturacion_HTML", "views", "app","ESM_Facturacion");
	$action['volver'] = ModuloGetURL("app","ESM_Facturacion","controller","Modificar_Glosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$_REQUEST['esm_glosa_id']."";
	$action['guardar'] = ModuloGetURL("app","ESM_Facturacion","controller","GuardarGlosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$_REQUEST['esm_glosa_id']."";
	$html = $Obj_Form->Forma_Glosar($action,$DATOS,$DetalleFactura,$MotivoGlosa,$glosas_concepto_general);
	$this->salida=$html;
	
	return true;
	}
	
	/* CORTES DIARIOS */
	   	
    function  Cortes_diarios()
	{
		$request = $_REQUEST;
			    
	    $sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","ESM_Facturacion");
	
	   //$sql->Borrar_Temporal($_REQUEST['datos']['empresa_id']);
	   if(!empty($request['buscador']['fecha_inicio']) && !empty($request['buscador']['fecha_final']))
	      {
	      
	        $sql->Borrar_Temporal($_REQUEST['datos']['empresa_id']);
	        $fecha_i=explode("/",$request['buscador']['fecha_inicio']);
	        $fecha_inicio=$fecha_i[2]."-".$fecha_i[1]."-".$fecha_i[0];
	        $fecha_f=explode("/",$request['buscador']['fecha_final']);
	        $fecha_final=$fecha_f[2]."-".$fecha_f[1]."-".$fecha_f[0];
	        
	        
	        $AGRUPAR_TRASLADOS = $sql->Obtener_TrasladosESM($_REQUEST['datos']['empresa_id'],$fecha_inicio,$fecha_final);
	        $AGRUPAR_DESPACHOS = $sql->Obtener_DespachosESM($_REQUEST['datos']['empresa_id'],$fecha_inicio,$fecha_final);
	        $AGRUPAR_DISPENSACION = $sql->Obtener_DispensacionESM($_REQUEST['datos']['empresa_id'],$fecha_inicio,$fecha_final);
	        $AGRUPAR_DISPENSACION_PENDIENTES = $sql->Obtener_DispensacionPendientesESM($_REQUEST['datos']['empresa_id'],$fecha_inicio,$fecha_final);
	        
	       
	       
	          $token=$sql->Insertar_CabeceraTemporal($_REQUEST,$fecha_inicio,$fecha_final);
			
	            if($token)
	            {
	              foreach($AGRUPAR_TRASLADOS as $key => $valor)
	              {
	                $ok=$sql->Insertar_DetalleTemporal_traslado($_REQUEST,$token['corte_tmp_id'],$valor['empresa_id'],$valor['prefijo'],$valor['numero']);
	             			  
				  }
	            foreach($AGRUPAR_DESPACHOS as $key => $valor)
	            {
	              
	                $ok=$sql->Insertar_DetalleTemporal_campania($_REQUEST,$token['corte_tmp_id'],$valor['empresa_id'],$valor['prefijo'],$valor['numero']);
	           	}
	            foreach($AGRUPAR_DISPENSACION as $key => $valor)
	            {
	                 $ok=$sql->Insertar_DetalleTemporal_dispensacion($_REQUEST,$token['corte_tmp_id'],$valor['esm_formulacion_despacho_id']);
	            }
	              
	            foreach($AGRUPAR_DISPENSACION_PENDIENTES as $key => $valor)
	            {
	              
	                 $ok=$sql->Insertar_DetalleTemporal_dispensacion_pendientes($_REQUEST,$token['corte_tmp_id'],$valor['bodegas_doc_id'],$valor['numeracion']);
	            }
	          
	      }
		  }
	      $action['buscar'] = ModuloGetURL('app','ESM_Facturacion','controller','Cortes_diarios');
	    
	  
		$DATOS=$sql->Buscar_CabeceraTemporal($_REQUEST['datos']['empresa_id'],$_REQUEST['datos']['ssiid']);
	
	    //$DATOS_TRASLADOS=$sql->Buscar_DetalleTemporal_traslado($_REQUEST['datos']['empresa_id'],$DATOS['corte_tmp_id']);
	   // $DATOS_DESPACHOS=$sql->Buscar_DetalleTemporal_campania($_REQUEST['datos']['empresa_id'],$DATOS['corte_tmp_id']);
		  $DATOS_DISPENSADOS=$sql->Buscar_DetalleTemporal_Dispensados($_REQUEST['datos']['empresa_id'],$DATOS['corte_tmp_id']);
	    $DATOS_PENDIENTES_DISPENSADOS=$sql->Buscar_DetalleTemporal_Dispensados_Pendientes($_REQUEST['datos']['empresa_id'],$DATOS['corte_tmp_id']);
	 
	    $Obj_Form=AutoCarga::factory("ESM_CortesDiarios_HTML", "views", "app","ESM_Facturacion");
	    $action['volver'] = ModuloGetURL("app","ESM_Facturacion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
	    $action['confirmar'] = ModuloGetURL("app","ESM_Facturacion","controller","ConfirmaCorte")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
	    //$action['opcion1'] = ModuloGetURL("app","ESM_Facturacion","controller","Crear_NuevoTemporal")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
	    $Html_Form=$Obj_Form->Vista_Formulario($action,$request['buscador'],$DATOS,$DATOS_TRASLADOS,$DATOS_DESPACHOS,$DATOS_DISPENSADOS,$DATOS_PENDIENTES_DISPENSADOS,$plan);
	    $this->salida = $Html_Form;
		return true;
	}

   function ConfirmaCorte()
		{
		$request = $_REQUEST;
		    
		IncludeFileModulo("Remotos_ESM_Facturacion","RemoteXajax","app","ESM_Facturacion");
		$this->SetXajax(array("Listado_Bodegas"),null,"ISO-8859-1");
		    
    $sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","ESM_Facturacion");
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

    $url = ModuloGetURL("app","ESM_Facturacion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
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
			    
		    $sql = AutoCarga::factory("Consultas_ESM_Cortes","classes","app","ESM_Facturacion");
	
	   
	      $action['buscar'] = ModuloGetURL('app','ESM_Facturacion','controller','Cortes_diarios_Descarga');
	    
	
		  $DATOS=$sql->Buscar_Cabecera_real($_REQUEST['datos']['empresa_id'],$request['buscador']['no_corte']);
	     
	    
	    $Obj_Form=AutoCarga::factory("ESM_CortesDiarios_HTML", "views", "app","ESM_Facturacion");
	    $action['volver'] = ModuloGetURL("app","ESM_Facturacion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
	    $action['confirmar'] = ModuloGetURL("app","ESM_Facturacion","controller","ConfirmaCorte")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
	    //$action['opcion1'] = ModuloGetURL("app","ESM_Facturacion","controller","Crear_NuevoTemporal")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
	    $Html_Form=$Obj_Form->Vista_Formulario_Descargas($action,$request['buscador'],$DATOS,$DATOS_TRASLADOS,$DATOS_DESPACHOS,$DATOS_DISPENSADOS,$DATOS_PENDIENTES_DISPENSADOS,$plan);
	    $this->salida = $Html_Form;
		return true;
		}
		
		
		
		
		
   
  }
?>