<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_Inv_NotasFacturas_Proveedor_controller.php,v 1.29 2010/02/17 14:46:54 johanna Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  /**
  * Clase Control: Inv_NotasFacturas_Proveedor
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.29 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
    class app_Inv_NotasFacturasProveedor_controller extends classModulo
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
        function app_Inv_NotasFacturasProveedor_controller(){}
        /**
        * Funcion principal del modulo
        *
        * @return boolean
        */
        
		function main()
		{	
			
			$request = $_REQUEST;
						
			$url[0]='app';                         //Tipo de Modulo
			$url[1]='Inv_NotasFacturasProveedor';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			$url[3]='MenuNotasFacturas_Proveedor';   //Metodo.
			$url[4]='datos';						//vector de $_request.
			$arreglo[0]='EMPRESA';					//Sub Titulo de la Tabla
						
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("Permisos", "", "app","Inv_NotasFacturasProveedor");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$datos=$obj_busqueda->BuscarPermisos(); 
		
		//Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
										// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
			$forma = gui_theme_menu_acceso("NOTAS CREDITO - DEBITO A FACTURAS DE PROVEEDORES",$arreglo,$datos,$url,ModuloGetURL('system','Menu')); 
			$this->salida=$forma;
				
 			return true; 
		}
		
      /*
      * FUNCION DE MENU PRINCIPAL
      */
		function MenuNotasFacturas_Proveedor()
		{
  		/*Crear el Menú de Opciones*/
      $request = $_REQUEST;
  		$Obj_Menu=AutoCarga::factory("NotasFacturas_Proveedor_MenuHTML", "views", "app","Inv_NotasFacturasProveedor");
  		
  		//Volver a Empresas.
  		$action['volver'] = ModuloGetURL("app","Inv_NotasFacturasProveedor","controller","main")."&datos[empresa_id]=".$request['datos']['empresa_id']."";
  		//$_SESSION['datos']['empresa_id']=$_REQUEST['datos']['empresa_id'];
      if($request['datos']['empresa_id'])
        SessionSetVar("empresa_id",$request['datos']['empresa_id']);      
      //print_r(SessionGetVar("empresa_id"));
      //Mostramos el Objeto Creado.
  		$this->salida=$Obj_Menu->Menu($action);
  		
  		return true;
		}
		
    
    
		/* FUNCION CREAR NOTAS (DEBITOS/CREDITOS)
		*  Funcion que consiste en Generar NOTAS A LAS FACTURAS 
		*  @param NULL
		*  return booleam.
		*/
		
      function CrearNotas()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosReportesGral");
      //print_r($_REQUEST);
      
      $html_form=AutoCarga::factory("CrearNotasHTML", "views", "app","Inv_NotasFacturasProveedor");
      $sql=AutoCarga::factory("CrearNotasFacturasProveedores", "", "app","Inv_NotasFacturasProveedor");
      
      $TiposIdTerceros=$sql->Listar_TiposIdTerceros();
      
      $datosN = array();
      //print_r($request['buscador']);
      if($request['buscador'])
      {
        $datosN = $sql->Obtener_FacturasProveedor($request['buscador'],$_REQUEST['datos']['empresa_id'],$request['offset']);
        $request['buscador']['usuario_id'] = UserGetUID();
      }
      $action['volver'] = ModuloGetURL("app","Inv_NotasFacturasProveedor","controller","MenuNotasFacturas_Proveedor")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
      $action['buscar'] = ModuloGetURL('app','Inv_NotasFacturasProveedor','controller','CrearNotas');
      $action['crear_nota'] = ModuloGetURL('app','Inv_NotasFacturasProveedor','controller','crear_nota_temporal');
      $action['ver_notas'] = ModuloGetURL('app','Inv_NotasFacturasProveedor','controller','DocumentoFinal');
      $action['paginador'] = ModuloGetURL('app','Inv_NotasFacturasProveedor','controller','CrearNotas',array("buscador"=>$request['buscador']))."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
      
      $this->salida .= $html_form->main($action,$request['buscador'],$TiposIdTerceros,$datosN, $sql->conteo, $sql->pagina);
      return true;
    }    
       
    function crear_nota_temporal()
    {
      $request = $_REQUEST;
      IncludeFileModulo("RemotosNotasFacturas_Proveedor","RemoteXajax","app","Inv_NotasFacturasProveedor");
      $this->SetXajax(array("GuardarGlosa","Listado_ConceptoEspecifico"),null,"ISO-8859-1");
      $empresa = SessionGetVar("PermisosReportesGral");
      
      
      $html_form=AutoCarga::factory("CrearNotasHTML", "views", "app","Inv_NotasFacturasProveedor");
      $sql=AutoCarga::factory("CrearNotasFacturasProveedores", "", "app","Inv_NotasFacturasProveedor");
	  $sql_2 = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
      /*print_r($_REQUEST);*/
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
      $action['volver'] = ModuloGetURL("app","Inv_NotasFacturasProveedor","controller","CrearNotas")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
      $action['guardar'] = ModuloGetURL('app','Inv_NotasFacturasProveedor','controller','crear_nota_temporal');
      $action['CrearDocumento'] = ModuloGetURL('app','Inv_NotasFacturasProveedor','controller','DocumentoFinal');
      $action['EliminarItems'] = ModuloGetURL('app','Inv_NotasFacturasProveedor','controller','crear_nota_temporal')."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&numero_factura=".$_REQUEST['numero_factura']."&codigo_proveedor_id=".$_REQUEST['codigo_proveedor_id']."";
      $action['paginador'] = ModuloGetURL('app','Inv_NotasFacturasProveedor','controller','crear_nota_temporal',array("buscador"=>$request['buscador']))."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&numero_factura=".$_REQUEST['numero_factura']."&codigo_proveedor_id=".$_REQUEST['codigo_proveedor_id']."";
      if(empty($Temporal))
      {
      $sql->Crear_Temporal($_REQUEST);
      }
      
      if($_REQUEST['op']=='1')
      {
      $sql->EliminarItem_Temporal($_REQUEST);
      }
      
      $Temporal=$sql->BuscarTemporales($_REQUEST);
      $Parametros=$sql->Parametros_Notas($_REQUEST,'Inv_NotasFacturasProveedor');
      $Temporal_Nota=$sql->Detalle_NotaTemporal($_REQUEST);
      $Detalle=$sql->BuscarDetalle($_REQUEST,$request['buscador'],$request['offset']);
      //print_r($_REQUEST);
      //$MotivoGlosa=$sql->Buscar_GlosasMotivos($_REQUEST['datos']['empresa_id'],$_REQUEST['prefijo'],$_REQUEST['factura_fiscal']);
      $glosas_concepto_general=$sql->Buscar_GlosasConceptoGeneral();
	  $Parametros_Retencion=$sql_2->Parametros_Retencion($_REQUEST['datos']['empresa_id'],$Temporal['anio_factura']);
      //print_r($Temporal_Nota);
      $this->salida .= $html_form->Creacion_Nota($action,$request['buscador'],$Temporal,$Detalle,$glosas_concepto_general,$Temporal_Nota,$Parametros,$Parametros_Retencion, $sql->conteo, $sql->pagina);
      return true;
    }    
    
    function DocumentoFinal()
    {
      $request = $_REQUEST;
     $empresa = SessionGetVar("PermisosReportesGral");
      
      
      $html_form=AutoCarga::factory("CrearNotasHTML", "views", "app","Inv_NotasFacturasProveedor");
      $sql=AutoCarga::factory("CrearNotasFacturasProveedores", "", "app","Inv_NotasFacturasProveedor");
      $Parametros=$sql->Parametros_Notas($_REQUEST,'Inv_NotasFacturasProveedor');
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
      
      $action['volver'] = ModuloGetURL("app","Inv_NotasFacturasProveedor","controller","CrearNotas")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
       
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
        
    
		$action['volver'] = ModuloGetURL("app","Inv_NotasFacturasProveedor","controller","MenuNotasFacturas_Proveedor")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
			
		
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
		
	IncludeFileModulo("RemotosNotasFacturas_Proveedor","RemoteXajax","app","Inv_NotasFacturasProveedor");
	$this->SetXajax(array("CrearNota","FormDetalleNota","AdicionarConceptoANota",
							  "ListarProductosFactura","NotaDetalles","BorrarDetalleNota","CrearDocumento",
							  "BorrarDocumento"));
 
    $this->IncludeJS("CrossBrowser");
	$this->IncludeJS("CrossBrowserEvent");
	$this->IncludeJS("CrossBrowserDrag");
	
	$Obj_Form=AutoCarga::factory("CrearNotasHTML", "views", "app","Inv_NotasFacturasProveedor");
	$sql=AutoCarga::factory("CrearNotasFacturasProveedores", "", "app","Inv_NotasFacturasProveedor");
    
	$Tercero=$sql->BuscarTercero($request['tipo_id_tercero'],$request['tercero_id']);
    $Documento=$sql->BuscarDocumento($request['documento_id']);
    $Factura=$sql->BuscarFactura($request['numero_factura'],$_REQUEST['datos']['empresa_id']);   
    //print_r($Factura);
   
	$action['volver'] = ModuloGetURL("app","Inv_NotasFacturasProveedor","controller","CrearNotas")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
   	
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
	$this->salida ="Pailas";
	}
	
	$Html_Form=$Obj_Form->NotasFacturasProveedor($action,$request,$Tercero,$Documento,$Factura,$_REQUEST['doc_nota_tmp_id']);
		
	$this->salida = $Html_Form;
    
    return true;
		
		}
  
    
}