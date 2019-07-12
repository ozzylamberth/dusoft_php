<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_Inv_NotasFacturasDespacho_controller.php,v 1.29 2010/02/17 14:46:54 johanna Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  /**
  * Clase Control: Inv_NotasFacturasDespacho
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.29 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
    class app_Inv_NotasFacturasDespacho_controller extends classModulo
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
        function app_Inv_NotasFacturasDespacho_controller(){}
        /**
        * Funcion principal del modulo
        *
        * @return boolean
        */
        
		function main()
		{	
			
			$request = $_REQUEST;
						
			$url[0]='app';                         //Tipo de Modulo
			$url[1]='Inv_NotasFacturasDespacho';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			$url[3]='MenuNotasFacturas_Proveedor';   //Metodo.
			$url[4]='datos';						//vector de $_request.
			$arreglo[0]='EMPRESA';					//Sub Titulo de la Tabla
						
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("Permisos", "", "app","Inv_NotasFacturasDespacho");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$datos=$obj_busqueda->BuscarPermisos(); 
		
		//Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
										// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
			$forma = gui_theme_menu_acceso("NOTAS CREDITO - DEBITO A FACTURAS DE DESPACHO",$arreglo,$datos,$url,ModuloGetURL('system','Menu')); 
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
  		$Obj_Menu=AutoCarga::factory("NotasFacturas_Proveedor_MenuHTML", "views", "app","Inv_NotasFacturasDespacho");
  		
  		//Volver a Empresas.
  		$action['volver'] = ModuloGetURL("app","Inv_NotasFacturasDespacho","controller","main")."&datos[empresa_id]=".$request['datos']['empresa_id']."";
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
		
      
      /*
      * Para Manejo de Tabs
      */
      $this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
    
    
    
		IncludeFileModulo("RemotosNotasFacturasDespacho","RemoteXajax","app","Inv_NotasFacturasDespacho");
		$this->SetXajax(array("Listar_TercerosProveedores","FacturasProveedor","VerNotasFacturaProveedor",
                          "Listar_FacturasProveedor","VerDetalleFacturaProveedor","VerDetallesNotaFacturaProveedor",
                          "Notas","AnularNotaFactura","AplicarAnulacionNota"));
    /*
    * Para Manejo de Ventanitas Flotantes... capitas!
    */
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("CrearNotasHTML", "views", "app","Inv_NotasFacturasDespacho");
		$sql=AutoCarga::factory("CrearNotasFacturasDespachos", "", "app","Inv_NotasFacturasDespacho");
    
		$documentos=$sql->ParametrosNotasDebitoCreditoFacturas($_REQUEST['datos']['empresa_id'],'2');
		//print_r($documentos);
	
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
        
    
		$action['volver'] = ModuloGetURL("app","Inv_NotasFacturasDespacho","controller","MenuNotasFacturas_Proveedor")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
			
		
		$Html_Form=$Obj_Form->main($action,$request,$html,$documentos);
				
		
		$this->salida = $Html_Form;
 
    return true;
		}
    
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
		
	IncludeFileModulo("RemotosNotasFacturasDespacho","RemoteXajax","app","Inv_NotasFacturasDespacho");
	$this->SetXajax(array("CrearNota","FormDetalleNota","AdicionarConceptoANota",
							  "ListarProductosFactura","NotaDetalles","BorrarDetalleNota","CrearDocumento",
							  "BorrarDocumento"));
  /*
    * Para Manejo de Ventanitas Flotantes... capitas!
    */
    $this->IncludeJS("CrossBrowser");
	$this->IncludeJS("CrossBrowserEvent");
	$this->IncludeJS("CrossBrowserDrag");
	
	$Obj_Form=AutoCarga::factory("CrearNotasHTML", "views", "app","Inv_NotasFacturasDespacho");
	$sql=AutoCarga::factory("CrearNotasFacturasDespachos", "", "app","Inv_NotasFacturasDespacho");
    
	$Tercero=$sql->BuscarTercero($request['tipo_id_tercero'],$request['tercero_id']);
    $Documento=$sql->BuscarDocumento($request['documento_id']);
    $Factura=$sql->BuscarFactura($request['prefijo'],$request['numero'],$_REQUEST['datos']['empresa_id']);   
    //print_r($Factura);
   
	$action['volver'] = ModuloGetURL("app","Inv_NotasFacturasDespacho","controller","CrearNotas")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
   	
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
	
	$token=$sql->CrearDocumentoTemporalNota($_REQUEST['datos']['empresa_id'],$request['tercero_id'],$request['tipo_id_tercero'],$request['prefijo'],$request['numero'],$request['documento_id'],$_REQUEST['doc_nota_tmp_id']);
	
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