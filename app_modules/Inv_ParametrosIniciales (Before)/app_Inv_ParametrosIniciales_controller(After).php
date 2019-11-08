<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_Inv_ParametrosIniciales_controller.php,v 1.29 2010/02/17 14:46:54 johanna Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  /**
  * Clase Control: Inv_CodificacionProductos
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.29 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
    class app_Inv_ParametrosIniciales_controller extends classModulo
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
        function app_Inv_ParametrosIniciales_controller(){}
        /**
        * Funcion principal del modulo
        *
        * @return boolean
        */
        
		function main()
		{	
			
			
			
			$request = $_REQUEST;
						
			$url[0]='app';                         //Tipo de Modulo
			$url[1]='Inv_ParametrosIniciales';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			$url[3]='MenuParametrosIniciales';   //Metodo.
			$url[4]='datos';						//vector de $_request.
			$arreglo[0]='EMPRESA';					//Sub Titulo de la Tabla
						
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("Permisos", "", "app","Inv_ParametrosIniciales");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$datos=$obj_busqueda->BuscarPermisos(); 
		
		//Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
										// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
			$forma = gui_theme_menu_acceso("PARAMETROS INICIALES - INVENTARIOS - SISTEMA",$arreglo,$datos,$url,ModuloGetURL('system','Menu')); 
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
		function MenuParametrosIniciales()
		{
  		/*Crear el Menú de Opciones*/
      $request = $_REQUEST;
  		$Obj_Menu=AutoCarga::factory("ParametrosInventarios_MenuHTML", "views", "app","Inv_ParametrosIniciales");
  		
  		//Volver a Empresas.
  		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","main");
  		//$_SESSION['datos']['empresa_id']=$_REQUEST['datos']['empresa_id'];
      if($request['datos']['empresa_id'])
        SessionSetVar("empresa_id",$request['datos']['empresa_id']);      
      //print_r(SessionGetVar("empresa_id"));
      //Mostramos el Objeto Creado.
  		$this->salida=$Obj_Menu->Menu($action);
  		
  		return true;
		}
		
    
    /* FUNCION CREAR ESTADOS DOCUMENTOS
		*  Funcion que consiste en Generar estados que pueden tener los documentos x empresa y usuario
		*  @param NULL
		*	return booleam.
		*/
		
		function ParametrizarEmpresas()
		{
		$request = $_REQUEST;
		
      
      /*
      * Para Manejo de Tabs
      */
      $this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
    
    
    
		IncludeFileModulo("RemotosParametrizarEmpresas","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("ListadoEmpresas","CambioEstado"),null,"ISO-8859-1");
                          
                          
		
    /*
    * Para Manejo de Ventanitas Flotantes... capitas!
    */
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("ParametrizarEmpresas_HTML", "views", "app","Inv_ParametrosIniciales");
				
		  
    
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
			
		
		$Html_FormMolecula=$Obj_Form->main($action,$request);
				
		
		$this->salida = $Html_FormMolecula;
    
    
    return true;
		
		}
    
    
		/* FUNCION CREAR ESTADOS DOCUMENTOS
		*  Funcion que consiste en Generar estados que pueden tener los documentos x empresa y usuario
		*  @param NULL
		*	return booleam.
		*/
		
		function CrearPerfilesTerapeuticos()
		{
		$request = $_REQUEST;
		$this->IncludeJS("TabPaneLayout");
		$this->IncludeJS("TabPaneApi");
		$this->IncludeJS("TabPane");

		IncludeFileModulo("RemotosPerfilesTerapeuticos","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("Listado_PerfilesTerapeuticos","Ingreso_PerfilTerapeutico","Insertar_PerfilTerapeutico",
		"Modificacion_PerfilTerapeutico","Modificar_PerfilTerapeutico","Borrar_PerfilTerapeutico"),null,"ISO-8859-1");

		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	

		$Obj_Form=AutoCarga::factory("CrearPerfilesTerapeuticos_HTML", "views", "app","Inv_ParametrosIniciales");

		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
		$Html_FormMolecula=$Obj_Form->main($action,$request);
		$this->salida = $Html_FormMolecula;
		return true;
		}
    
    
    
    /* FUNCION CREAR ESTADOS DOCUMENTOS
		*  Funcion que consiste en Generar estados que pueden tener los documentos x empresa y usuario
		*  @param NULL
		*	return booleam.
		*/
		
		function CrearEstadosDocumentos()
		{
		$request = $_REQUEST;
		
      
      /*
      * Para Manejo de Tabs
      */
      $this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
    
    
    
		IncludeFileModulo("RemotosEstadosDocumentos","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("EstadosDocumentosT","IngresoEstadoDocumento","InsertarEstadoDocumento",
                          "ModificarEstadoDocumento","CambioEstadoEstadoDocumento","ModEstadoDocumento",
                          "CambioEstadoEstadoDocumento","EstadosDocumentosCambiosT","CambiosNoAsignadosXEstado",
                          "AsignarCambiosXEstado","BorrarEstadosAsignados"),null,"ISO-8859-1");
                          
                          
		
    /*
    * Para Manejo de Ventanitas Flotantes... capitas!
    */
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("CrearEstadosDocumentos_HTML", "views", "app","Inv_ParametrosIniciales");
				
		  
    
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
			
		
		$Html_FormMolecula=$Obj_Form->main($action,$request);
				
		
		$this->salida = $Html_FormMolecula;
    
    
    return true;
		
		}
    
    
    
    
    
    
    
 
		function AsignarEstadosUsuariosBodega()
    {
    $request = $_REQUEST;
		  
      /*
      * Para Manejo de Tabs
      */
      $this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
    
    
    
		IncludeFileModulo("RemotosEstadosDocumentos","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("EstadosDocumentosT","IngresoEstadoDocumento","InsertarEstadoDocumento",
                          "ModificarEstadoDocumento","CambioEstadoEstadoDocumento","ModEstadoDocumento",
                          "CambioEstadoEstadoDocumento","EstadosDocumentosCambiosT","CambiosNoAsignadosXEstado",
                          "AsignarCambiosXEstado","BorrarEstadosAsignados"),null,"ISO-8859-1");
                          
                          
		
    /*
    * Para Manejo de Ventanitas Flotantes... capitas!
    */
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("AsignarEstadosUsuariosBodega_HTML", "views", "app","Inv_ParametrosIniciales");
		

        
      $request = $_REQUEST;
						
			$url[0]='app';                         //Tipo de Modulo
			$url[1]='Inv_ParametrosIniciales';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			$url[3]='AsignarEstadosUsuariosBodega_2';   //Metodo.
			$url[4]='arreglo';						//vector de $_request.
			$arreglo[0]='EMPRESA';					//Sub Titulo de la Tabla
						
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("ConsultasEstadosDocumentos", "classes", "app","Inv_ParametrosIniciales");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$datos=$obj_busqueda->Listar_Empresas(); 
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
    
    $VolverMenu=$action['volver'];
		//Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
										// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver ModuloGetURL('system','Menu')
		$html = gui_theme_menu_acceso("SELECCIONE LA EMPRESA",$arreglo,$datos,$url,$VolverMenu); 
		
		  
    
		
			
		
		$Html_FormMolecula=$Obj_Form->main($action,$request,$html);
				
		
		$this->salida = $Html_FormMolecula;
    
    
    return true;
    
    }
    
		
		/*
    * Pantalla 2 de Asignacion de Estados de documentos a Usuario
    * Requests
    * request[nombre_empresa];
    * request[empresa_id];
    */    
    
    
    function AsignarEstadosUsuariosBodega_2()
    {
    $request = $_REQUEST;
		$EmpresaId=$_REQUEST['arreglo']['empresa_id'];	

      
		IncludeFileModulo("RemotosEstadosDocumentos","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("UsuariosDocumentosBodegasT","UsuarioEstadosDocumentos","InsertarEstadoDocumento",
                          "ModificarEstadoDocumento","CambioEstadoEstadoDocumento","ModEstadoDocumento",
                          "CambioEstadoEstadoDocumento","EstadosDocumentosCambiosT","CambiosNoAsignadosXEstado",
                          "AsignarCambiosXEstado","BorrarEstadosAsignados","ListadoEstadosUsuarios","BorrarEstadoUsuario",
                          "AsignarEstadoUsuario","BuscarUsuario"),null,"ISO-8859-1");
    
    
    /*
    * Para Manejo de Ventanitas Flotantes... capitas!
    */
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("AsignarEstadosUsuariosBodega_HTML", "views", "app","Inv_ParametrosIniciales");
		
		
    $action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","AsignarEstadosUsuariosBodega");
    
    
		$Html_FormListaUsuarios=$Obj_Form->DesplegarListadoUsuarios($action,$EmpresaId);
		
    
		$this->salida = $Html_FormListaUsuarios;
    
    
    return true;
    
    }
    
    
    
    
    
    
    
    
    
    
		    
    /* FUNCION CREAR NOVEDADES DEVOLUCION PRODUCTOS
		*  Funcion que consiste en Generar la Interfaz para el Ingreso de NOVEDADES DEVOLUCION PRODUCTOS
    *  Para que en otros módulos, puedan ser extraidos y utilizados en devoluciones
		*  @param NULL
		*	return booleam.
		*/

		function CrearNovedadesDevolucionProductos()
		{
		$request = $_REQUEST;
		
		IncludeFileModulo("RemotosNovedadesDevolucion","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("NovedadesDevolucionT","IngresoNovedadDevolucion","InsertarNovedadDevolucion",
                          "ModificarNovedadDevolucion","GuardarModNovedadDevolucion","CambioEstadoNovedadDevolucion"),null,"ISO-8859-1");
                          
                          
		
    
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("CrearNovedadesDevolucionProductos_HTML", "views", "app","Inv_ParametrosIniciales");
				
		
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
			
		
		$Html_FormMolecula=$Obj_Form->main($request,$action);
				
		
		$this->salida = $Html_FormMolecula;
    
    
    return true;
		
		}
    
    
    
    
    
    
    
    
    
    
    
		/* FUNCION CREAR MENSAJES DEL SISTEMA
		*	Funcion que consiste en Generar la Interfaz para el Ingreso de Mensajes a utilizar en el sistema
		*	@param NULL
		*	return booleam.
		*/
		function CrearMensajeSistema()
		{
		$request = $_REQUEST;
		//Incluye al script controller el archivo remotos de XAJAX.
		IncludeFileModulo("RemotosMensajeSistema","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("MensajesSistemaT","IngresoMensajeSistema","InsertarMensajeSistema",
                          "GuardarModMensajeSistema","CambioEstadoMensajeSistema",
                          "BusquedaMensajesSistema","ModificarMensajeSistema"),null,"ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("CrearMensajeSistema_HTML", "views", "app","Inv_ParametrosIniciales");
		//Antes de crear el formulario para crear laboratorios, incluir informacion extraída de la base de datos como los Paises.
		
		//Boton Volver==>Ruta
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
					
		//Genero Formulario para la creacion de laboratorios.
		$Html_Form=$Obj_Form->main($request,$action);
				
		
		$this->salida = $Html_Form;
		
				
		return true;
		}
		
		
		
		

/* FUNCION CREAR CONDICIONES COMPRAS
		*	Funcion que consiste en Generar la Interfaz para ingresar Condiciones de Compras
    * Y puedan ser utilizadas en el momento de generar una orden de compra/contratacion
    *	@param NULL
		*	return booleam.
		*/

    
		function CrearCondicionesCompra()
		{
    $request = $_REQUEST;
		//Incluye al script controller el archivo remotos de XAJAX.
		IncludeFileModulo("RemotosCondicionesCompra","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("CondicionesCompraT","IngresoCondicionesCompra","InsertarCondicionCompra",
                          "GuardarModCondicionCompra","CambioEstadoCondicionCompra",
                          "BusquedaCondicionCompra","ModificarCondicionCompra"),null,"ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("CrearCondicionesCompra_HTML", "views", "app","Inv_ParametrosIniciales");
		//Antes de crear el formulario para crear laboratorios, incluir informacion extraída de la base de datos como los Paises.
		
		//Boton Volver==>Ruta
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
					
		//Genero Formulario para la creacion de laboratorios.
		$Html_Form=$Obj_Form->main($request,$action);
				
		
		$this->salida = $Html_Form;
		
				
		return true;
		}
    
    
    
    
    
    
    
    
    /* FUNCION TIPOS SALIDAS PRODUCTOS
		*	Funcion que consiste en Generar la Interfaz para ingresar al sistema,
    * los diferentes motivos para la salida de un producto.
    *	@param NULL
		*	return booleam.
		*/

    
		function CrearTiposSalidasProductos()
		{
    
     $request = $_REQUEST;
		//Incluye al script controller el archivo remotos de XAJAX.
		IncludeFileModulo("RemotosTiposSalidasProductos","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("TiposSalidasProductosT","IngresoTipoSalidaProducto","InsertarTipoSalidaProducto",
                          "GuardarModTipoSalidaProducto","CambioEstadoTipoSalidaProducto",
                          "BusquedaTipoSalidaProducto","ModificarTipoSalidaProducto"),null,"ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
                          
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("CrearTiposSalidasProductos_HTML", "views", "app","Inv_ParametrosIniciales");
		
		
		//Boton Volver==>Ruta
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
					
		$Html_Form=$Obj_Form->main($request,$action);
				
		
		$this->salida = $Html_Form;
		
				
		return true;
		}
    
    
    
      /* FUNCION CREAR BANCOS
		*	Funcion que consiste en Generar la Interfaz para ingresar al sistema,
    * Bancos, para su uso en algun proceso
    *	@param NULL
		*	return booleam.
		*/

    
		function CrearBancos()
		{
    
     $request = $_REQUEST;
		//Incluye al script controller el archivo remotos de XAJAX.
		IncludeFileModulo("RemotosBancos","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("BancosT","IngresoBanco","InsertarBanco",
                          "GuardarModBanco","CambioEstadoBanco",
                          "BusquedaBancos","ModificarBanco","SeleccionarDepto","SeleccionarMpio"),null,"ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
                          
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("CrearBancos_HTML", "views", "app","Inv_ParametrosIniciales");
    
    
		$obj_busqueda=AutoCarga::factory("Permisos", "", "app","Inv_ParametrosIniciales");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
	 $datos=$obj_busqueda->BuscarDatosEmpresa($_SESSION['datos']['empresa_id']); 
	 
		//Boton Volver==>Ruta
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
		SessionSetVar("tipo_pais_id",'CO');
    
    
    
    //$Html_Form = "Hola:".$datos[0]['tipo_pais_id'];
    $Html_Form=$Obj_Form->main($request,$action);
		
    
		
		$this->salida = $Html_Form;
		
				
		return true;
		}
    
    
    /* FUNCION CREAR TIPOS DE DISPENSACION
		*	Funcion que consiste en Generar la Interfaz para ingresar al sistema,
    * los diferentes tipos de dispensacion que puede tener una farmacia
    *	@param NULL
		*	return booleam.
		*/

    
		function CrearTiposDispensacion()
		{
    
     $request = $_REQUEST;
		//Incluye al script controller el archivo remotos de XAJAX.
		IncludeFileModulo("RemotosTiposDispensacion","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("TiposDispensacionT","IngresoTipoDispensacion","InsertarTipoDispensacion",
                          "GuardarModTipoDispensacion","CambioEstadoTipoDispensacion",
                          "BusquedaTipoDispensacion","ModificarTipoDispensacion"),null,"ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
                          
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("CrearTiposDispensacion_HTML", "views", "app","Inv_ParametrosIniciales");
		
		
		//Boton Volver==>Ruta
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
					
		$Html_Form=$Obj_Form->main($request,$action);
				
		
		$this->salida = $Html_Form;
		
				
		return true;
		}
    
    
    
    
    /* FUNCION ASIGNACION DE TOPES DE DISPENSACION POR FARMACIA
		*	Funcion que consiste en Generar la Interfaz para ingresar al sistema,
    * los diferentes tipos de dispensacion que puede tener una farmacia
    *	@param NULL
		*	return booleam.
		*/

    
		function AsignarTopesDispensacionFarmacias()
		{
    $request = $_REQUEST;
		
                          
                          
		
    
      $url[0]='app';                         //Tipo de Modulo
			$url[1]='Inv_ParametrosIniciales';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			$url[3]='AsignarTopesDispensacionFarmacias_2';   //Metodo.
			$url[4]='arreglo';						//vector de $_request.
			$arreglo[0]='EMPRESA';					//Sub Titulo de la Tabla
						
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("ConsultasEstadosDocumentos", "classes", "app","Inv_ParametrosIniciales");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$datos=$obj_busqueda->Listar_Empresas(); 
		  $action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
    
      $VolverMenu=$action['volver'];
		//Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
										// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver ModuloGetURL('system','Menu')
		$html = gui_theme_menu_acceso("SELECCIONE LA EMPRESA(FARMACIA)",$arreglo,$datos,$url,$VolverMenu); 
    
    
    
		$Obj_Form=AutoCarga::factory("AsignarTopesDispensacionFarmacias_HTML", "views", "app","Inv_ParametrosIniciales");
				
		  
    
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
			
		
		$Html_Form=$Obj_Form->main($action,$request,$html);
				
		
		$this->salida = $Html_Form;
    
    
    return true;
		}
    
    
    
    
    
    function AsignarTopesDispensacionFarmacias_2()
		{
    $request = $_REQUEST;
		$EmpresaId=$_REQUEST['arreglo']['empresa_id'];	
      
      /*
      * Para Manejo de Tabs
      */
      $this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
    
    
    
		IncludeFileModulo("RemotosTopesDispensacionFarmacias","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("TiposDispensacionT","IngresoTopeDispensacion","InsertarAsignarDispensacionTope",
                          "ModificarTopeDispensacion","ModEstadoDocumento",
                          "CambioEstado","ModTopeDispensacion",
                          "TiposDispensacionAsignadas"),null,"ISO-8859-1");
    
    /*
    * Para Manejo de Ventanitas Flotantes... capitas!
    */
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
    $Obj_Form=AutoCarga::factory("AsignarTopesDispensacionFarmacias_HTML", "views", "app","Inv_ParametrosIniciales");
    
    $action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","AsignarTopesDispensacionFarmacias");
    
    
		$Html_Form=$Obj_Form->pantalla_2($action,$EmpresaId);
		
    
		$this->salida = $Html_Form;
    
    
   return true; 
    
    
    }
    
    
    
      /* FUNCION CREAR TIPOS DE DISPENSACION
		*	Funcion que consiste en Generar la Interfaz para ingresar al sistema,
    * los diferentes tipos de dispensacion que puede tener una farmacia
    *	@param NULL
		*	return booleam.
		*/

    
		function CrearTiposBloqueos()
		{
    
     $request = $_REQUEST;
		//Incluye al script controller el archivo remotos de XAJAX.
		IncludeFileModulo("RemotosTiposBloqueos","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("TiposBloqueosT","IngresoTipoBloqueo","InsertarTipoBloqueo",
                          "GuardarModTipoBloqueo","CambioEstadoTipoBloqueo",
                          "BusquedaTipoBloqueo","ModificarTipoBloqueo"),null,"ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
                          
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("CrearTiposBloqueos_HTML", "views", "app","Inv_ParametrosIniciales");
		
		
		//Boton Volver==>Ruta
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
					
		$Html_Form=$Obj_Form->main($request,$action);
				
		
		$this->salida = $Html_Form;
		
				
		return true;
		}
    
    
    
    
    function BloquearClientes()
		{
    
     $request = $_REQUEST;
		
    
    
    
    //Incluye al script controller el archivo remotos de XAJAX.
		IncludeFileModulo("RemotosBloquearClientes","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("MenuDeEmpresas"),null,"ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
                          
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("BloquearClientes_HTML", "views", "app","Inv_ParametrosIniciales");
		
		
		//Boton Volver==>Ruta
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
					
		$Html_Form=$Obj_Form->menu($request,$action);
				
		
		$this->salida = $Html_Form;
		
				
		return true;
		}
    
    
    function BloquearTerceros()
		{
    
     $request = $_REQUEST;
		
    //Incluye al script controller el archivo remotos de XAJAX.
		IncludeFileModulo("RemotosBloquearClientes","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("TercerosT","CambioBloqueo","TiposBloqueos","CambioEstadoTercero","BuscarTercero"),null,"ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
    
    $sql = AutoCarga::factory("ConsultasBloquearTerceros","classes","app","Inv_ParametrosIniciales");
    $TiposIdTerceros_=$sql->TiposIdTerceros();

    
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("BloquearClientes_HTML", "views", "app","Inv_ParametrosIniciales");
		
    
    
    
    
    
    
		
		//Boton Volver==>Ruta
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","BloquearClientes");
					
		$Html_Form=$Obj_Form->BloquearTerceros($request,$action,$TiposIdTerceros_);
				
		
		$this->salida = $Html_Form;
		
				
		return true;
		}
    
    
    function BloquearPacientes()
		{
    
     $request = $_REQUEST;
		
    //Incluye al script controller el archivo remotos de XAJAX.
		IncludeFileModulo("RemotosBloquearPacientes","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("PacientesT","CambioBloqueo","TiposBloqueos","CambioEstadoPaciente","BuscarPaciente"),null,"ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
    
    $sql = AutoCarga::factory("ConsultasBloquearTerceros","classes","app","Inv_ParametrosIniciales");
    $TiposIdPacientes=$sql->TiposIdPacientes();
       
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("BloquearClientes_HTML", "views", "app","Inv_ParametrosIniciales");
		
		
    
    
    //Boton Volver==>Ruta
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","BloquearClientes");
					
		
    
    $Html_Form=$Obj_Form->BloquearPacientes($request,$action,$TiposIdPacientes);
				
		
		$this->salida = $Html_Form;
		
				
		return true;
		}
        
    
    function DefinirCostosDeVentaProductos()
		{
    
    $request = $_REQUEST;
		IncludeFileModulo("RemotosDefinirCostosDeVentaProductos","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("CentrosUtilidad","Bodegas","Esconder",
                          "Listado_Precios","RegistrarListaPrecios","CatalogoProductosFarmacia","SeleccionDeProductos","BuscarProductos",
                          "RegistrarItemListaPrecios","ListadoItemsListaPrecios","BuscarProductosListaPrecios","EliminarItemListaPrecios",
                          "Productos_CreadosBuscados","EmpresasT_2","Buscador","AsignarCostosXProducto","FormaDinamica2",
                          "GuardarDaticos2"),null,"ISO-8859-1");
    
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
    
    
    /*
      * Para Manejo de Tabs
      */
     $this->IncludeJS("TabPaneLayout");
		 $this->IncludeJS("TabPaneApi");
     $this->IncludeJS("TabPane");
    
    
    
    $Obj_Form=AutoCarga::factory("DefinirCostosDeVentaProductos_HTML", "views", "app","Inv_ParametrosIniciales");
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
		
		$Html_Form=$Obj_Form->main($action,$request,$html);
		
		$this->salida = $Html_Form;
    
    return true;
		}
    
   
  
  
		function AsignarCondicionesDeDevolucionProductosXLaboratorio()
    {
    $request = $_REQUEST;
    $empresa_id = SessionGetVar("empresa_id");
    
		IncludeFileModulo("RemotosAsignarCondicionesDeDevolucionProductosXLaboratorio","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("TercerosProveedoresT","IngresoPoliticasDevolucion","BorrarDaticos",
                          "CorreosAdicionales","PoliticasDevolucion","FormaDinamica",
                          "GuardarDaticos","FormaDinamica2","GuardarDaticos2",
                          "FormaDinamica3","GuardarDaticos3","FormaDinamica4","GuardarDaticos4","FormaDinamica5","GuardarDaticos5"),null,"ISO-8859-1");
                          
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("AsignarCondicionesDeDevolucionProductosXLaboratorio_HTML", "views", "app","Inv_ParametrosIniciales");
		

		//Boton Volver==>Ruta
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
			
		
		$Html_FormPoliticasDevolucion=$Obj_Form->main($action,$empresa_id,$html);
				
		
		$this->salida .= $Html_FormPoliticasDevolucion;
		//$this->salida = SessionGetVar("empresa_id");
    
    
    return true;
    
    }
    
    
    function Menu_DefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica()
    {
    $request = $_REQUEST;
    
    
    
    
      $url[0]='app';                         //Tipo de Modulo
			$url[1]='Inv_ParametrosIniciales';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			$url[3]='DefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica';   //Metodo.
			$url[4]='datos';						//vector de $_request.*/
      $arreglo[0]='SELECCIONE FARMACIA';					//Sub Titulo de la Tabla
						
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("ConsultasDefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica", "", "app","Inv_ParametrosIniciales");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$datos=$obj_busqueda->Listar_Farmacias(); 
		  $volver = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
		//Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
										// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
			$forma = gui_theme_menu_acceso("TIEMPOS DE ENTREGA DE MEDICAMENTOS CON FORMULA MEDICA",$arreglo,$datos,$url,$volver); 
			$this->salida=$forma;
		
		   
    return true;
    
    }

    
    
    function DefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica()
    {
    $request = $_REQUEST;
	
			$Empresa_Id=$_REQUEST['datos']['empresa_id'];	
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("ConsultasDefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica", "", "app","Inv_ParametrosIniciales");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$volver = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
		//Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
										// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
		  $Farmacia=$obj_busqueda->BuscarFarmacia($Empresa_Id);
      
		IncludeFileModulo("RemotosDefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("IngresoTiempo","BuscarTiempoEntregaMedicamentos","GuardarDaticos"),null,"ISO-8859-1");
                          
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("DefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica_HTML", "views", "app","Inv_ParametrosIniciales");
		

		//Boton Volver==>Ruta
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","Menu_DefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica");
			
		
		$Html_Form=$Obj_Form->main($action,$Empresa_Id,$Farmacia);
				
		
		$this->salida = $Html_Form;
    
    
    return true;
    
    }

   
   
   function DefinirProductosRequierenAutorizacionDespachosPedidos()
    {
    //Generar de Busqueda de Permisos SQL
	
		IncludeFileModulo("RemotosDefinirProductosRequierenAutorizacionDespachosPedidos","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("buscar_clases_grupo","ProductosT","buscar_subclases_clase_grupo","AutorizaDesautoriza","Productos_CreadosBuscados"),null,"ISO-8859-1");
    $sql = AutoCarga::factory("ConsultasDefinirProductosRequierenAutorizacionDespachosPedidos","classes","app","Inv_ParametrosIniciales");
    $Grupos=$sql->ListadoGrupos(); 

    
    $Obj_Form=AutoCarga::factory("DefinirProductosRequierenAutorizacionDespachosPedidos_HTML", "views", "app","Inv_ParametrosIniciales");
		

		//Boton Volver==>Ruta
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
			
		
		$Html_Form=$Obj_Form->main($action,$Empresa_Id,$Grupos);
				
		
		$this->salida = $Html_Form;
    
    
    return true;
    
    }
   
   
   
   
   function Menu_CrearRutasDeViajes()
    {
    $request = $_REQUEST;
    
    
      $url[0]='app';                         //Tipo de Modulo
			$url[1]='Inv_ParametrosIniciales';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			$url[3]='CrearRutasDeViajes';   //Metodo.
			$url[4]='datos';						//vector de $_request.*/
      $arreglo[0]='SELECCIONE EMPRESA';					//Sub Titulo de la Tabla
						
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("ConsultasCrearRutasDeViajes", "", "app","Inv_ParametrosIniciales");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$datos=$obj_busqueda->Listar_Empresas(); 
		  $volver = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
		//Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
										// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
			$forma = gui_theme_menu_acceso("CREACION DE RUTAS DE VIAJE",$arreglo,$datos,$url,$volver); 
			$this->salida=$forma;
		
		   
    return true;
    }

  
  
  
  
  
  
   function CrearRutasDeViajes()
		{
		$request = $_REQUEST;
		 
    
		IncludeFileModulo("RemotosCrearRutasDeViajes","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("ZonasT","IngresoZonas","InsertarZonas",
                          "ModificarZonas","CrearRutasDeViaje","ModZonas","CambioEstado",
                          "AsignarDepartamentosZonas","SeleccionarMpio",
                          "AsignarCambiosXEstado","BorrarMpioZona","VerZona",
                          "InsertarConfigurarZonas","IngresoRutaViaje","InformacionEmpresa",
                          "InsertarRutaViaje","RutasViajesT","ModificarRutaViaje","ModRutaViaje",
                          "CambioEstadoRutaViaje","ConfigurarRutaViaje","EmpresasXZonas","InsertarConfigurarRuta",
                          "MostrarInfoRuta","BorrarRuta"),null,"ISO-8859-1");
                          
                          
		/*
      * Para Manejo de Tabs
      */
      $this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
    
    
    /*
    * Para Manejo de Ventanitas Flotantes... capitas!
    */
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("CrearRutasDeViajes_HTML", "views", "app","Inv_ParametrosIniciales");
				
		  
    
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","Menu_CrearRutasDeViajes");
			
		
		$Html_Form=$Obj_Form->main($action,$request);
				
		
		$this->salida = $Html_Form;
    
    
    return true;
		
		}
   
   /**
        *  Funcion que busca los estados del documento
        */
  function EstadosDocumentos()
  {
    $this->SetXajax(array("MostrarEstados","GuardarEstados","EstadosMod","EliminarEstados"),"app_modules/Inv_ParametrosIniciales/RemoteXajax/RemotoEstadosDocumentos.php","ISO-8859-1");
    $request = $_REQUEST;
    $empresa_id = SessionGetVar("empresa_id");
    
    $action['volver'] = ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "MenuParametrosIniciales");
    
    $mdl = AutoCarga::factory("ParametrizacionEstadosTiposDocumentos","","app","Inv_ParametrosIniciales");
    $documentos=$mdl->BuscarDocumentos();
    $estados=$mdl->BuscarEstados();
    
    $action['guardar'] = ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "GuardarEstados",array("tipo_doc_general_id"=>$_REQUEST['tipo_doc_general_id']));
    
    $act = AutoCarga::factory("ParametrosInventarios_MenuHTML", "views", "app", "Inv_ParametrosIniciales");
    $this->salida = $act->formaParametrizacionEstadosDocu($documentos,$estados,$action,$empresa_id);
    
    return true; 
  }
    
    
    
    function MenuAsignarDocumentosABodegas()
		{
		$request = $_REQUEST;
		 
    
		IncludeFileModulo("RemotosAsignarDocumentosABodegas","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("CentrosDeUtilidad","Bodegas"),null,"ISO-8859-1");
     
    
		//Empresas
    $sql=AutoCarga::factory("ConsultasAsignarDocumentosABodegas", "", "app","Inv_ParametrosIniciales");
		$Empresas=$sql->Listar_Empresas(); 
    
    
    $Obj_Form=AutoCarga::factory("AsignarDocumentosABodegas_HTML", "views", "app","Inv_ParametrosIniciales");
     
    
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
		$request['nombre_opcion']="ASIGNAR DOCUMENTOS A BODEGAS";
    $request['url_destino']=ModuloGetURL("app","Inv_ParametrosIniciales","controller","AsignarDocumentosABodegas");;
		$Html_Form=$Obj_Form->main($action,$request,$Empresas);
		
		$this->salida = $Html_Form;
        
    return true;
  	}
   
    
    
    function AsignarDocumentosABodegas()
		{
		$request = $_REQUEST;
		 
    
		IncludeFileModulo("RemotosAsignarDocumentosABodegas","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("DocumentosT","AsignarDocumentoABodega","DocumentosAsignadoABodegaT","CambioEstado"),null,"ISO-8859-1");
   
     /*
      * Para Manejo de Tabs
      */
      $this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");

   
    /*
    * Para Manejo de Ventanitas Flotantes... capitas!
    */
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		//Empresas
    $sql=AutoCarga::factory("ConsultasAsignarDocumentosABodegas", "", "app","Inv_ParametrosIniciales");
		//$Empresas=$sql->Listar_Empresas(); 
    
    
    $Obj_Form=AutoCarga::factory("AsignarDocumentosABodegas_HTML", "views", "app","Inv_ParametrosIniciales");
     
    
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuAsignarDocumentosABodegas");
		
		$Html_Form=$Obj_Form->AsignarDocumentosABodegas($action,$request);
		
		$this->salida = $Html_Form;
        
    return true;
  	}
   

    function MenuAsignarDocumentosBodegasAUsuarios()
		{
		$request = $_REQUEST;
		 
    
		IncludeFileModulo("RemotosAsignarDocumentosABodegas","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("CentrosDeUtilidad","Bodegas"),null,"ISO-8859-1");
     
    
		//Empresas
    $sql=AutoCarga::factory("ConsultasAsignarDocumentosABodegas", "", "app","Inv_ParametrosIniciales");
		$Empresas=$sql->Listar_Empresas(); 
    
    
    $Obj_Form=AutoCarga::factory("AsignarDocumentosABodegas_HTML", "views", "app","Inv_ParametrosIniciales");
     
    
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
		
		$request['nombre_opcion']="ASIGNAR DOCUMENTOS DE BODEGAS A USUARIOS";
    $request['url_destino']=ModuloGetURL("app","Inv_ParametrosIniciales","controller","AsignarDocumentosBodegasAUsuarios");;
    $Html_Form=$Obj_Form->main($action,$request,$Empresas);
		
		$this->salida = $Html_Form;
        
    return true;
  	}

function AsignarDocumentosBodegasAUsuarios()
		{
		$request = $_REQUEST;
		 
    
		IncludeFileModulo("RemotosAsignarDocumentosABodegas","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("UsuariosDocumentosBodegasT","ListadoDocumentosBodega",
                          "DocumentosBodega","GuardarDocumentoUsuarioBodega","UsuariosDocumentosT",
                          "DocumentosBodegaXUsuario","ListadoDocumentosBodegaXUsuario","QuitarDocumentoUsuarioBodega"),null,"ISO-8859-1");
   
     /*
      * Para Manejo de Tabs
      */
      $this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");

   
    /*
    * Para Manejo de Ventanitas Flotantes... capitas!
    */
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		//Empresas
    $sql=AutoCarga::factory("ConsultasAsignarDocumentosABodegas", "", "app","Inv_ParametrosIniciales");
		//$Empresas=$sql->Listar_Empresas(); 
    
    
    $Obj_Form=AutoCarga::factory("AsignarDocumentosABodegas_HTML", "views", "app","Inv_ParametrosIniciales");
     
    
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuAsignarDocumentosBodegasAUsuarios");
		
		$Html_Form=$Obj_Form->AsignarDocumentosBodegasAUsuarios($action,$request);
		
		$this->salida = $Html_Form;
        
    return true;
  	}
   
   
   
   function MenuParametrizarDocumentosPorDepartamentos()
		{
		$request = $_REQUEST;
		 
    
		IncludeFileModulo("RemotosParametrizarDocumentosPorDepartamentos","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("CentrosDeUtilidad","UnidadesFuncionales"),null,"ISO-8859-1");
     
     /*
    * Para Manejo de Ventanitas Flotantes... capitas!
    */
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
    
    
    
		//Empresas
    $sql=AutoCarga::factory("ConsultasParametrizarDocumentosPorDepartamentos", "", "app","Inv_ParametrosIniciales");
		$Empresas=$sql->Listar_Empresas(); 
    
    
    $Obj_Form=AutoCarga::factory("ParametrizarDocumentosPorDepartamentos_HTML", "views", "app","Inv_ParametrosIniciales");
     
    
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
		
		$request['nombre_opcion']="PARAMETRIZAR DOCUMENTOS POR DEPARTAMENTO";
    $request['url_destino']=ModuloGetURL("app","Inv_ParametrosIniciales","controller","ParametrizarDocumentosPorDepartamentos");;
    $Html_Form=$Obj_Form->main($action,$request,$Empresas);
		
		$this->salida = $Html_Form;
        
    return true;
  	}
    
    
    
    function ParametrizarDocumentosPorDepartamentos()
		{
		$request = $_REQUEST;
		 
    
		IncludeFileModulo("RemotosParametrizarDocumentosPorDepartamentos","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("DepartamentosT","ListadoTiposDocumentosSinAsignar",
                          "ListarTiposDocumentosNoAsignados","AsignarTipoDocumentoADepartamentos","Listar_TiposDocumentosAsignadosADepartamentos",
                          "CambioEstado","ListadoTiposDocumentosAsignados","QuitarDocumentoUsuarioBodega"),null,"ISO-8859-1");
   
    /*
    * Para Manejo de Ventanitas Flotantes... capitas!
    */
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		//Empresas
    $sql=AutoCarga::factory("ConsultasParametrizarDocumentosPorDepartamentos", "", "app","Inv_ParametrosIniciales");
		//$Empresas=$sql->Listar_Empresas(); 
    
    
    $Obj_Form=AutoCarga::factory("ParametrizarDocumentosPorDepartamentos_HTML", "views", "app","Inv_ParametrosIniciales");
     
    
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrizarDocumentosPorDepartamentos");
		
		$Html_Form=$Obj_Form->AsignarDocumentosADepartamentos($action,$request);
		
		$this->salida = $Html_Form;
        
    return true;
  	}
    
    
    
    function ParametrizarPrioridadBodegasDespachos()
		{
		$request = $_REQUEST;
		 
    
		IncludeFileModulo("RemotosParametrizarPrioridadBodegasDespachos","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("EmpresasT","DefinirPrioridad"),null,"ISO-8859-1");
     
     /*
    * Para Manejo de Ventanitas Flotantes... capitas!
    */
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
    
    
    $Obj_Form=AutoCarga::factory("ParametrizarPrioridadBodegasDespachos_HTML", "views", "app","Inv_ParametrosIniciales");
     
    
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
				
    $Html_Form=$Obj_Form->main($action,$request,$Empresas);
		
		$this->salida = $Html_Form;
        
    return true;
  	}
  
  /**
        *  Funcion que busca los estados del documento
        */
  function ProductosConteo()
   {
    $this->SetXajax(array("ProductosConteo","GuardarProductosConteo","GuardarActivado"),"app_modules/Inv_ParametrosIniciales/RemoteXajax/RemotosProductosConteo.php","ISO-8859-1");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserEvent");
    $this->IncludeJS("CrossBrowserDrag");
    $request = $_REQUEST;
    $empresa_id = SessionGetVar("empresa_id");
    
    $action['volver'] = ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "MenuParametrosIniciales");
    
    $mdl = AutoCarga::factory("ParametrizacionTomaFisica","","app","Inv_ParametrosIniciales");
    $parametrizacion=$mdl->Buscarparamtomafisica();
    
    $act = AutoCarga::factory("ParametrosInventarios_MenuHTML", "views", "app", "Inv_ParametrosIniciales");
    $this->salida = $act->formaMenuProdutosConteo($action,$parametrizacion);
    
    return true; 
   }

  /**
      *  Funcion que muestra el menu de Farmacovigilancia
      */
  function Farmacovigilancia()
  {
    $request = $_REQUEST;
    $empresa_id = SessionGetVar("empresa_id");
    
    $action['volver'] = ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "MenuParametrosIniciales");
    $action['registrar_pacientes']=ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "RegistrarPacientes");
    $action['bloquear_productos']=ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "BloquearProductos");
    
    $act = AutoCarga::factory("CrearFarmacovigilanciaHTML", "views", "app", "Inv_ParametrosIniciales");
    $this->salida = $act->formaMenuFarmacovigilancia($action);
    
    return true; 
  }
   
  /**
     *  Funcion que registra los pacientes que tienen efectos con los medicamentos
      */
  function RegistrarPacientes()
  {
    $this->SetXajax(array("RegistraPaciente","BuscarProducto","SeleccionarProducto","GuardarFarmacovigilancia"),"app_modules/Inv_ParametrosIniciales/RemoteXajax/RemotosFarmacovigilancia.php","ISO-8859-1");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserEvent");
    $this->IncludeJS("CrossBrowserDrag");
    $request = $_REQUEST;
    $empresa_id = SessionGetVar("empresa_id");
    
    $action['volver'] = ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "Farmacovigilancia");
    
    $mdl = AutoCarga::factory("ConsultasParamFarmacovigilancia","","app","Inv_ParametrosIniciales");
    $tipo_documento=$mdl->BuscarTipo_documento();
    $act = AutoCarga::factory("CrearFarmacovigilanciaHTML", "views", "app", "Inv_ParametrosIniciales");
		$conteo =$pagina=0;
   
    if(!empty($request['buscar_usuarios']))
    {
      $buscar_pacientes=$mdl->Consultarpacientes($request['buscar_usuarios'],$request['offset']);
      $action['buscar_usuarios']=ModuloGetURL('app','Inv_ParametrosIniciales','controller','RegistrarPacientes');
      $action['paginador'] = ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "RegistrarPacientes",array("buscar_usuarios"=>$request['buscar_usuarios']));
      $conteo= $mdl->conteo;
      $pagina= $mdl-> pagina;
    }
			
    $this->salida = $act->formaRegistrarPacientes($action,$tipo_documento,$request['buscar_usuarios'],$conteo,$pagina,$buscar_pacientes);
    return true; 
  }
  
  /**
     *  Funcion que registra los pacientes que tienen efectos con los medicamentos
      */
  function BloquearProductos()
  {
    $this->SetXajax(array("GuardarProductoBloq","ListarProductosABloquear","ListarProductosLote"),"app_modules/Inv_ParametrosIniciales/RemoteXajax/RemotosFarmacovigilancia.php","ISO-8859-1");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserEvent");
    $this->IncludeJS("CrossBrowserDrag");
    $request = $_REQUEST;
    $empresa_id = SessionGetVar("empresa_id");
    $mdl = AutoCarga::factory("ConsultasParamFarmacovigilancia","","app","Inv_ParametrosIniciales");
    $Laboratorios=$mdl->ListaLaboratorios();
    $Moleculas=$mdl->ListaMoleculas();
    
    $act = AutoCarga::factory("CrearFarmacovigilanciaHTML", "views", "app", "Inv_ParametrosIniciales");
    $this->salida = $act->formaBloquearProductosLote($action,$Laboratorios,$Moleculas);
    
    return true; 
  }
  
  /**
     *  Funcion que parametriza bodegas virtuales
      */
  function BodegasVirtuales()
  {
    $this->SetXajax(array("GuardarBodegaVirtual"),"app_modules/Inv_ParametrosIniciales/RemoteXajax/RemotosBodegaVirtual.php","ISO-8859-1");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserEvent");
    $this->IncludeJS("CrossBrowserDrag");
    $request = $_REQUEST;
    
    $offset=$request['offset'];
    $empresa_id = SessionGetVar("empresa_id");
    $conteo =$pagina=0;
    
    $action['volver'] = ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "MenuParametrosIniciales");
    $action['paginador'] = ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "BodegasVirtuales");
    
    $mdl = AutoCarga::factory("ConsultasBodegasVirtuales","","app","Inv_ParametrosIniciales");
    $ListarProductos=$mdl->ListarBodegas($offset);
    
    $conteo= $mdl->conteo;
		$pagina= $mdl-> pagina;
    
    $act = AutoCarga::factory("CrearBodegasVirtuales_HTML", "views", "app", "Inv_ParametrosIniciales");
    $this->salida = $act->formaBodegasVirtuales($action,$conteo,$pagina,$ListarProductos);
    
    return true; 
  }
  
  /**
     *  Funcion que parametriza las torres de productos y el dueño
      */
  function TorresProductos()
  {
    $this->SetXajax(array("GuardarTorreProd"),"app_modules/Inv_ParametrosIniciales/RemoteXajax/RemotosTorreProd.php","ISO-8859-1");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserEvent");
    $this->IncludeJS("CrossBrowserDrag");
    $request = $_REQUEST;
    
    $offset=$request['offset'];
    $empresa_id = SessionGetVar("empresa_id");
    $conteo =$pagina=0;
    
    $action['volver'] = ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "MenuParametrosIniciales");
    $action['paginador'] = ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "TorresProductos");
    
    $mdl = AutoCarga::factory("ConsultasParamTorresP","","app","Inv_ParametrosIniciales");
    $ListarProductos=$mdl->ListarProductos($empresa_id,$offset);
    $BuscarTorres=$mdl->BuscarTorres();
    
    $conteo= $mdl->conteo;
		$pagina= $mdl-> pagina;
    
    $act = AutoCarga::factory("CrearParamTorres_HTML", "views", "app", "Inv_ParametrosIniciales");
    $this->salida = $act->formaTorresProd($action,$conteo,$pagina,$ListarProductos);
    
    return true; 
  }
  
  /**
     *  Funcion que parametriza las torres de productos y el dueño
      */
  function AutorJefes()
  {
    $this->SetXajax(array("GuardarJefe"),"app_modules/Inv_ParametrosIniciales/RemoteXajax/RemotosJefesAutor.php","ISO-8859-1");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserEvent");
    $this->IncludeJS("CrossBrowserDrag");
    $request = $_REQUEST;
    
    $offset=$request['offset'];
    $empresa_id = SessionGetVar("empresa_id");
    $conteo =$pagina=0;
    
    $action['volver'] = ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "MenuParametrosIniciales");
    $action['paginador'] = ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "TorresProductos");
    
    $mdl = AutoCarga::factory("ConsultasParamJefesAuto","","app","Inv_ParametrosIniciales");
    
    $ListarProductos=$mdl->Buscarparamprod($empresa_id);
    //print_r($ListarProductos);
    //$BuscarTorres=$mdl->BuscarTorres();
    
    $conteo= $mdl->conteo;
		$pagina= $mdl-> pagina;
    
    $act = AutoCarga::factory("CrearParamJefesAu_HTML", "views", "app", "Inv_ParametrosIniciales");
    $this->salida = $act->formaJefesAuto($action,$conteo,$pagina,$ListarProductos);
    
    return true; 
  }
    /**
    *
    */
    function DiasEnvios()
    {
      $request = $_RQUEST;
      $empresa_id = SessionGetVar("empresa_id");
      
      $mdl = AutoCarga::factory("TiposProductosHTML","views","app","Inv_ParametrosIniciales");
      $cls = AutoCarga::factory("TiposProductosSQL","classes","app","Inv_ParametrosIniciales");
      $tiposP = $cls->ObtenerTiposProductos($empresa_id);
      
      $this->SetXajax(array("IngresarDias"),"app_modules/Inv_ParametrosIniciales/RemoteXajax/RemotosTiposProductos.php","ISO-8859-1");

      $action['volver'] = ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "MenuParametrosIniciales");
      $adicionales['usuario_id'] = UserGetUID();
      $adicionales['empresa_id'] = $empresa_id;
      $this->salida = $mdl->FormaDiasEnvio($action,$tiposP,$adicionales);
      return true;
    }

		/* FUNCION CREAR ESTADOS DOCUMENTOS
		*  Funcion que consiste en Generar estados que pueden tener los documentos x empresa y usuario
		*  @param NULL
		*	return booleam.
		*/
		
		function UnidadesNegocio()
		{
		$request = $_REQUEST;
		$this->IncludeJS("TabPaneLayout");
		$this->IncludeJS("TabPaneApi");
		$this->IncludeJS("TabPane");
		$sql = AutoCarga::factory("UnidadesNegocioSQL","classes","app","Inv_ParametrosIniciales");
		IncludeFileModulo("RemotosUnidadesNegocio","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("Formulario_UnidadesNegocio","GuardarDatos"),null,"ISO-8859-1");

		if($_REQUEST['cambiar_estado']!="")
			{
			$sql->Inactivar_UnidadNegocio($_REQUEST);
			}
		
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		$Listado_UnidadesNegocio = $sql->Listado_UnidadesNegocio($request['buscador'],$request['offset']);
		$html=AutoCarga::factory("UnidadesNegocioHTML", "views", "app","Inv_ParametrosIniciales");
		$action['buscador']=ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "UnidadesNegocio");
		$action['paginador'] = ModuloGetURL('app','Inv_ParametrosIniciales','controller','UnidadesNegocio',array("buscador"=>$request['buscador']));
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
		$Forma=$html->Forma($action,$Listado_UnidadesNegocio,$sql->conteo, $sql->pagina);
		$this->salida = $Forma;
		return true;
		}
		
		/* FUNCION CREAR ESTADOS DOCUMENTOS
		*  Funcion que consiste en Generar estados que pueden tener los documentos x empresa y usuario
		*  @param NULL
		*	return booleam.
		*/
		
		function TiposCliente()
		{
		$request = $_REQUEST;
		$this->IncludeJS("TabPaneLayout");
		$this->IncludeJS("TabPaneApi");
		$this->IncludeJS("TabPane");
		$sql = AutoCarga::factory("TiposClienteSQL","classes","app","Inv_ParametrosIniciales");
		IncludeFileModulo("RemotosTiposCliente","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("Formulario_TiposCliente","GuardarDatos"),null,"ISO-8859-1");

		if($_REQUEST['cambiar_estado']!="")
			{
			$sql->Inactivar_TipoCliente($_REQUEST);
			}
		
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		$Listado_TiposCliente = $sql->Listado_TiposCliente($request['buscador'],$request['offset']);
		$html=AutoCarga::factory("TiposClienteHTML", "views", "app","Inv_ParametrosIniciales");
		$action['buscador']=ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "TiposCliente");
		$action['paginador'] = ModuloGetURL('app','Inv_ParametrosIniciales','controller','TiposCliente',array("buscador"=>$request['buscador']));
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
		$Forma=$html->Forma($action,$Listado_TiposCliente,$sql->conteo, $sql->pagina);
		$this->salida = $Forma;
		return true;
		}
		
		
		/* FUNCION CREAR ESTADOS DOCUMENTOS
		*  Funcion que consiste en Generar estados que pueden tener los documentos x empresa y usuario
		*  @param NULL
		*	return booleam.
		*/
		
		function Transportadoras()
		{
		$request = $_REQUEST;
		$this->IncludeJS("TabPaneLayout");
		$this->IncludeJS("TabPaneApi");
		$this->IncludeJS("TabPane");
		$sql = AutoCarga::factory("TransportadorasSQL","classes","app","Inv_ParametrosIniciales");
		IncludeFileModulo("RemotosTransportadoras","RemoteXajax","app","Inv_ParametrosIniciales");
		$this->SetXajax(array("Formulario_Transportadora","GuardarDatos"),null,"ISO-8859-1");

		if($_REQUEST['cambiar_estado']!="")
			{
			$sql->Inactivar_Transportadoras($_REQUEST);
			}
		
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		$Listado_TiposCliente = $sql->Listado_Transportadoras($request['buscador'],$request['offset']);
		$html=AutoCarga::factory("TransportadorasHTML", "views", "app","Inv_ParametrosIniciales");
		$action['buscador']=ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "Transportadoras");
		$action['paginador'] = ModuloGetURL('app','Inv_ParametrosIniciales','controller','Transportadoras',array("buscador"=>$request['buscador']));
		$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
		$Forma=$html->Forma($action,$Listado_TiposCliente,$sql->conteo, $sql->pagina);
		$this->salida = $Forma;
		return true;
		}
  }
?>