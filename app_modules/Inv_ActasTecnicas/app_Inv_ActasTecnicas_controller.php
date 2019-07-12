<?php
	/**
  * @package DUANA & CIA
  * @version $Id: app_Inv_ActasTecnicas_controller.php,v 1 $
  * @copyright DUANA & CIA
  * @author R.O.M.A
  */
  /** 
  * Clase Control: Inv_ActasTecnicas_Proveedor
  * Clase encargada del control de llamado de metodos en el modulo
  **/

    class app_Inv_ActasTecnicas_controller extends classModulo
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
        function app_Inv_ActasTecnicas_controller(){}

		
        /************************************************************ 
        Funcion principal del modulo 
		@return boolean
	    ************************************************************/
		function main()
		{	
			
			$request = $_REQUEST;
						
			$url[0]='app';                         	            //Tipo de Modulo
			$url[1]='Inv_ActasTecnicas';   			//Nombre del Modulo
			$url[2]='controller';                  			//tipo controller...
			$url[3]='MenuActasTecnicas';   			//Metodo.
			$url[4]='datos';									//vector de $_request.
			$arreglo[0]='EMPRESAS';					//Sub Titulo de la Tabla
						
			//Generar busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("Permisos", "", "app","Inv_ActasTecnicas");
			$datos=$obj_busqueda->BuscarPermisos(); 
		
			// Menu de empresas con permiso 
			$forma = gui_theme_menu_acceso("ACTAS TECNICAS PRODUCTOS ",$arreglo,$datos,$url,ModuloGetURL('system','Menu')); 
			$this->salida=$forma;
				
 			return true; 
		}
		
        /***************************************************************
         * FUNCION DE MENU PRINCIPAL
        ***************************************************************/
		function MenuActasTecnicas()
		{
  		 /*Crear el Menú de Opciones*/
         $request = $_REQUEST;
  		 $Obj_Menu=AutoCarga::factory("ActasTecnicas_MenuHTML", "views", "app","Inv_ActasTecnicas");
  		
  		 //Volver a Empresas.
  		 $action['volver'] = ModuloGetURL("app","Inv_ActasTecnicas","controller","main")."&datos[empresa_id]=".$request['datos']['empresa_id']."";
  		 
         if($request['datos']['empresa_id'])
            SessionSetVar("empresa_id",$request['datos']['empresa_id']);      
         
  		 $this->salida=$Obj_Menu->Menu($action);
  		
  		 return true;
		}
		
    
    
		/*************************************************************************************
		*  Funcion busqueda facturas por proveedor 
		*  @param NULL
		*  return boolean.
		**************************************************************************************/
       function CrearNotas()
       {
		  $request = $_REQUEST;
		  $empresa = SessionGetVar("PermisosReportesGral");
		  //echo "controller: ";
		  //print_r($_REQUEST);
		  
		  $html_form=AutoCarga::factory("CrearNotasHTML", "views", "app","Inv_ActasTecnicas");
		  $sql=AutoCarga::factory("CrearNotasFacturasProveedores", "", "app","Inv_ActasTecnicas");
		  
		  $TiposIdTerceros=$sql->Listar_TiposIdTerceros();
		  
		  $datosN = array();
		  //print_r($request['buscador']);
		  if($request['buscador'])
		  {
			$datosN = $sql->Obtener_FacturasProveedor($request['buscador'],$_REQUEST['datos']['empresa_id'],$request['offset']);
			$request['buscador']['usuario_id'] = UserGetUID();
		  }
		  $action['volver'] = ModuloGetURL("app","Inv_ActasTecnicas","controller","MenuActasTecnicas")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
		  $action['buscar'] = ModuloGetURL('app','Inv_ActasTecnicas','controller','CrearNotas');
		  //$action['crear_nota'] = ModuloGetURL('app','Inv_ActasTecnicas','controller','crear_nota_temporal');
		  $action['detalle_fac'] = ModuloGetURL('app','Inv_ActasTecnicas','controller','Det_factura_prov');
		  //$action['ver_notas'] = ModuloGetURL('app','Inv_ActasTecnicas','controller','DocumentoFinal');
		  $action['paginador'] = ModuloGetURL('app','Inv_ActasTecnicas','controller','CrearNotas',array("buscador"=>$request['buscador']))."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
		  
		  $this->salida .= $html_form->main($action,$request['buscador'],$TiposIdTerceros,$datosN, $sql->conteo, $sql->pagina);
		  return true;
       }    

    /*******************************************************************
    * Funcion Detalles de factura
	*return boolean
	********************************************************************/
     function Det_factura_prov()
	 {
	  $request = $_REQUEST;
	  IncludeFileModulo("RemotosNotasFacturas_Proveedor","RemoteXajax","app","Inv_ActasTecnicas");
      $this->SetXajax(array("FormaActa","RegistrarActaTecnica"),null,"ISO-8859-1");
	  
	  $fac = $_REQUEST['numero_factura'];
	  $prov = $_REQUEST['codigo_proveedor_id'];
	  $emp = SessionGetVar("empresa_id");	
      $cls = AutoCarga::factory("CrearNotasFacturasProveedores", "", "app","Inv_ActasTecnicas");	
	  $datosF = $cls->Obtener_DetFactura($fac,$prov,$emp); 
	  //print_r($datosF);
	  
	  $action['volver'] = ModuloGetURL("app","Inv_ActasTecnicas","controller","CrearNotas")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
	  $mensaje = "DETALLE DE FACTURA # ".$fac;
	  $forma = AutoCarga::factory("CrearNotasHTML", "views", "app","Inv_ActasTecnicas");
	  $this->salida .= $forma->FormaDetalle($action,$mensaje,$datosF,$fac,$prov);
	  return true;
	 }


    /********************************************************************
	 /*  
    function crear_nota_temporal()
    {
      $request = $_REQUEST;
      IncludeFileModulo("RemotosNotasFacturas_Proveedor","RemoteXajax","app","Inv_ActasTecnicas");
      $this->SetXajax(array("GuardarGlosa","Listado_ConceptoEspecifico"),null,"ISO-8859-1");
      $empresa = SessionGetVar("PermisosReportesGral");
      
      
	  
      $html_form=AutoCarga::factory("CrearNotasHTML", "views", "app","Inv_ActasTecnicas");
      $sql=AutoCarga::factory("CrearNotasFacturasProveedores", "", "app","Inv_ActasTecnicas");
	  $sql_2 = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
      //print_r($_REQUEST);
      if($_REQUEST['cantidad_registros']>0)
      {
        for($i=0;$i<$_REQUEST['cantidad_registros'];$i++)
        {
        if($_REQUEST[$i]!="")
          {
          $Token=$sql->GuardarTemporal_Detalle($_REQUEST[$i],$_REQUEST['cantidad'.$i],$_REQUEST['codigo_concepto_general'.$i],$_REQUEST['codigo_concepto_especifico'.$i],
          $_REQUEST['valor_concepto'.$i],$_REQUEST['observacion'.$i],$_REQUEST['nota_mayor_valor'.$i],$_REQUEST['valor'.$i],$_REQUEST,$_REQUEST['porc_iva'.$i],$_REQUEST['sube_baja_costo'.$i]);
          }
        }
      }
      //print_r($_REQUEST);
      
      $Temporal=$sql->BuscarTemporales($_REQUEST);
      $action['volver'] = ModuloGetURL("app","Inv_ActasTecnicas","controller","CrearNotas")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
      $action['guardar'] = ModuloGetURL('app','Inv_ActasTecnicas','controller','crear_nota_temporal');
      $action['CrearDocumento'] = ModuloGetURL('app','Inv_ActasTecnicas','controller','DocumentoFinal');
      $action['EliminarItems'] = ModuloGetURL('app','Inv_ActasTecnicas','controller','crear_nota_temporal')."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&numero_factura=".$_REQUEST['numero_factura']."&codigo_proveedor_id=".$_REQUEST['codigo_proveedor_id']."";
      $action['paginador'] = ModuloGetURL('app','Inv_ActasTecnicas','controller','crear_nota_temporal',array("buscador"=>$request['buscador']))."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&numero_factura=".$_REQUEST['numero_factura']."&codigo_proveedor_id=".$_REQUEST['codigo_proveedor_id']."";
      if(empty($Temporal))
      {
      $sql->Crear_Temporal($_REQUEST);
      }
      
      if($_REQUEST['op']=='1')
      {
      $sql->EliminarItem_Temporal($_REQUEST);
      }
      
      $Temporal=$sql->BuscarTemporales($_REQUEST);
      $Parametros=$sql->Parametros_Notas($_REQUEST,'Inv_ActasTecnicas');
      $Temporal_Nota=$sql->Detalle_NotaTemporal($_REQUEST);
      $Detalle=$sql->BuscarDetalle($_REQUEST,$request['buscador'],$request['offset']);
      //print_r($_REQUEST);
      //$MotivoGlosa=$sql->Buscar_GlosasMotivos($_REQUEST['datos']['empresa_id'],$_REQUEST['prefijo'],$_REQUEST['factura_fiscal']);
      $glosas_concepto_general=$sql->Buscar_GlosasConceptoGeneral();
	  $Parametros_Retencion=$sql_2->Parametros_Retencion($_REQUEST['datos']['empresa_id'],$Temporal['anio_factura']);
      //print_r($Temporal_Nota);
      $this->salida .= $html_form->Creacion_Nota($action,$request['buscador'],$Temporal,$Detalle,$glosas_concepto_general,$Temporal_Nota,$Parametros,$Parametros_Retencion, $sql->conteo, $sql->pagina);
      return true;
    }    */
    
    function DocumentoFinal()
    {
      $request = $_REQUEST;
     $empresa = SessionGetVar("PermisosReportesGral");
      
      
      $html_form=AutoCarga::factory("CrearNotasHTML", "views", "app","Inv_ActasTecnicas");
      $sql=AutoCarga::factory("CrearNotasFacturasProveedores", "", "app","Inv_ActasTecnicas");
      $Parametros=$sql->Parametros_Notas($_REQUEST,'Inv_ActasTecnicas');
      //print_r($Parametros);
      
      if($_REQUEST['crear_nota']=='1')
      {
      $Temporal_BajaCosto=$sql->Detalle_NotaTemporal($_REQUEST,'1');
      $Temporal_SubeCosto=$sql->Detalle_NotaTemporal($_REQUEST,'0');
        if(count($Temporal_BajaCosto)>0)
        {
        $token=$sql->GuardarTransaccion($Parametros[0]['documento_id_debito'],$Parametros[0]['prefijo_debito'],$Parametros[0]['numeracion_debito'],$_REQUEST,$Temporal_BajaCosto,$_REQUEST['valor_debito'],'inv_notas_debito_proveedor','debito','-','0');        
        }
        if(count($Temporal_SubeCosto)>0)
        {
        $token=$sql->GuardarTransaccion($Parametros[0]['documento_id_credito'],$Parametros[0]['prefijo_credito'],$Parametros[0]['numeracion_credito'],$_REQUEST,$Temporal_SubeCosto,$_REQUEST['valor_credito'],'inv_notas_credito_proveedor','credito','+','1');
        }
        
        if($token)
          $sql->Borrar_Temporal($_REQUEST);

      //print_r($_REQUEST);
      }
      $NotasCredito=$sql->Notas_Proveedor($_REQUEST,'inv_notas_credito_proveedor');
      $NotasDebito=$sql->Notas_Proveedor($_REQUEST,'inv_notas_debito_proveedor');
      $NotasDevolucion=$sql->Notas_DevolucionProveedor($_REQUEST);
      
      $action['volver'] = ModuloGetURL("app","Inv_ActasTecnicas","controller","CrearNotas")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
       
      $this->salida .= $html_form->Documentos_Nota($action,$NotasCredito,$NotasDebito,$NotasDevolucion, $sql->conteo, $sql->pagina);
      return true;
    }    
    
    
		/*function CrearNotas()
		{
		$request = $_REQUEST;

      $this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
    
    
    
		IncludeFileModulo("RemotosNotasFacturas_Proveedor","RemoteXajax","app","Inv_NotasFacturasProveedor");
		$this->SetXajax(array("Listar_TercerosProveedores","FacturasProveedor","VerNotasFacturaProveedor",
                          "Listar_FacturasProveedor","VerDetalleFacturaProveedor","VerDetallesNotaFacturaProveedor",
                          "Notas","AnularNotaFactura","AplicarAnulacionNota","VerDetalleCalificacion"));
    
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("CrearNotasHTML", "views", "app","Inv_NotasFacturasProveedor");
		$sql=AutoCarga::factory("CrearNotasFacturasProveedores", "", "app","Inv_NotasFacturasProveedor");
    
		$documentos=$sql->ParametrosNotasDebitoCreditoFacturas($_REQUEST['datos']['empresa_id'],'1');
			
	   $TiposIdTerceros=$sql->Listar_TiposIdTerceros();
        
        $html.="<select class=\"select\" id=\"tipo_id_tercero\">";
        $html.="<option value=\"\"></option>";
        foreach($TiposIdTerceros as $key=>$tit)
        {
          $html.="<option value=\"".$tit['tipo_id_tercero']."\">";
          $html.="".$tit['tipo_id_tercero'];
          $html.="</option>";
        }
        $html.="</select>";
        
    
		$action['volver'] = ModuloGetURL("app","Inv_NotasFacturasProveedor","controller","MenuActasTecnicas")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
			
		
		$Html_Form=$Obj_Form->main($action,$request,$html,$documentos);
				
		
		$this->salida = $Html_Form;
 
    return true;
		}*/
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /*
	* Funcion para Crear las Notas Credito y Debido a Facturas de Proveedor
	* Opera Segun Dos Casos
	* 1.	Caso de que sea un nuevo Documento.
	* 1.1.	Tomar opcion_documento =1 (Del Request)
	* 1.2.	Ver cual es el proximo Id.  (select max(doc_nota_tmp_id) FROM inv_notas_facturas_proveedor_tmp y sumarle 1
	* 1.3.	Insertar en la base de datos los datos que vienen en el Request.
	* 2. 	Caso de que sea un documento ya existente.
	* 2.1.	Tomar opcion_documento =1 (Del Request)
	* 2.2. Tomar el doc_nota_tmp_id y buscar el documento Temporal
	* 3.	Hace una Forma general, donde Inicialmente se le envía siempre como parámetros la empresa_id y el doc_nota_tmp_id
	* 4.	Carga la Info, de Cabecera y con Xajax, el detalle del Documento temporal.
	*/
    
    function Crear_NotasFacturasProveedor()
		{
		$request = $_REQUEST;
		
	IncludeFileModulo("RemotosNotasFacturas_Proveedor","RemoteXajax","app","Inv_ActasTecnicas");
	$this->SetXajax(array("CrearNota","FormDetalleNota","AdicionarConceptoANota",
							  "ListarProductosFactura","NotaDetalles","BorrarDetalleNota","CrearDocumento",
							  "BorrarDocumento"));
 
    $this->IncludeJS("CrossBrowser");
	$this->IncludeJS("CrossBrowserEvent");
	$this->IncludeJS("CrossBrowserDrag");
	
	$Obj_Form=AutoCarga::factory("CrearNotasHTML", "views", "app","Inv_ActasTecnicas");
	$sql=AutoCarga::factory("CrearNotasFacturasProveedores", "", "app","Inv_ActasTecnicas");
    
	$Tercero=$sql->BuscarTercero($request['tipo_id_tercero'],$request['tercero_id']);
    $Documento=$sql->BuscarDocumento($request['documento_id']);
    $Factura=$sql->BuscarFactura($request['numero_factura'],$_REQUEST['datos']['empresa_id']);   
    //print_r($Factura);
   
	$action['volver'] = ModuloGetURL("app","Inv_ActasTecnicas","controller","CrearNotas")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
   	
	/*
	* Opcion Documento
	* 1)  Crear Nuevo Documentoc		
	* 2) Continuar con un Documento Ya creado
	*/
	
	//print_r($_REQUEST);
	if($_REQUEST['opcion_documento']=='1')
	{
	/*
	* Acá debe ir La creacion de un nuevo documento temporal
	* Tablas: 
	* - inv_notas_facturas_proveedor_tmp: Cabecera.
	* - inv_notas_facturas_proveedor_d_tmp: detalle.
	*/
	
	$token=$sql->CrearDocumentoTemporalNota($_REQUEST['datos']['empresa_id'],$request['tercero_id'],$request['tipo_id_tercero'],$request['numero_factura'],$request['documento_id'],$_REQUEST['doc_nota_tmp_id']);
	
					if(!$token)
					{
					$this->error="ERROR EN LA CREACION DEL DOCUMENTO TEMPORAL!!";
				//	return false;
					
					}
	$this->salida ="Joder :".($doc_nota_tmp_id_);
	}
	else
	{
	$this->salida ="Error";
	}
	
	$Html_Form=$Obj_Form->NotasFacturasProveedor($action,$request,$Tercero,$Documento,$Factura,$_REQUEST['doc_nota_tmp_id']);
		
	$this->salida = $Html_Form;
    
    return true;
		
		}
  
    
}