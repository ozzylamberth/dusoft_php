<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_ESM_OrdenesRequisicion_controller.php,v 1.29 2010/02/17 14:46:54 johanna Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  /**
  * Clase Control: ESM_OrdenesRequisicion
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.29 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
    class app_ESM_OrdenesRequisicion_controller extends classModulo
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
        function app_ESM_OrdenesRequisicion_controller(){}
        /**
        * Funcion principal del modulo
        *
        * @return boolean
        */
        
		function main()
		{	
			$request = $_REQUEST;
						
			$url[0]='app';                         //Tipo de Modulo
			$url[1]='ESM_OrdenesRequisicion';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			$url[3]='Menu';   //Metodo.
			$url[4]='datos';						//vector de $_request.
			$arreglo[0]='EMPRESA';					//Sub Titulo de la Tabla
			$arreglo[1]='CENTRO UTILIDAD';					//Sub Titulo de la Tabla
			$arreglo[2]='BODEGA';					//Sub Titulo de la Tabla
						
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("Permisos", "", "app","ESM_OrdenesRequisicion");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$datos=$obj_busqueda->BuscarPermisos(); 
		
		//Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
										// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
			$forma = gui_theme_menu_acceso("ORDENES DE REQUISICION",$arreglo,$datos,$url,ModuloGetURL('system','Menu')); 
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
			/*Crear el Menú de Opciones*/
			$request = $_REQUEST;
			$Obj_Menu=AutoCarga::factory("ESM_OrdenesRequisicion_MenuHTML", "views", "app","ESM_OrdenesRequisicion");
      IncludeFileModulo("Remotos_ESM_OrdenesRequisicion","RemoteXajax","app","ESM_OrdenesRequisicion");
      $this->SetXajax(array("Buscar_OrdenRequisicion"),null,"ISO-8859-1");
			//print_r($_REQUEST);
			$action['volver'] = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","main");

			if($request['datos']['empresa_id'])
			SessionSetVar("empresa_id",$request['datos']['empresa_id']);      
			//SessionSetVar("ssiid",$request['datos']['ssiid']);      
			$this->salida=$Obj_Menu->Menu($action);
  		
  		return true;
		}
		
    //$sql->mensajeDeError
	
		
		function Crear_OrdenesRequisicion()
		{
			$request = $_REQUEST;

			IncludeFileModulo("Remotos_ESM_OrdenesRequisicion","RemoteXajax","app","ESM_OrdenesRequisicion");
			$this->SetXajax(array("Listado_Temporales"),null,"ISO-8859-1");

			$sql = AutoCarga::factory("Consultas_OrdenesRequisicion","classes","app","ESM_OrdenesRequisicion");

			$Obj_Form=AutoCarga::factory("ESM_OrdenesRequisicion_HTML", "views", "app","ESM_OrdenesRequisicion");
			$action['volver'] = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
			$action['opcion1'] = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Crear_NuevoTemporal")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
		
			$Html_Form=$Obj_Form->Vista_OrdenesRequisicionTemporales($action);
			$this->salida = $Html_Form;
			return true;
		}
    
    function Crear_NuevoTemporal()
		{
		$request = $_REQUEST;
		    
		IncludeFileModulo("Remotos_ESM_OrdenesRequisicion","RemoteXajax","app","ESM_OrdenesRequisicion");
		$this->SetXajax(array("Listado_Bodegas"),null,"ISO-8859-1");

		$sql = AutoCarga::factory("Consultas_OrdenesRequisicion","classes","app","ESM_OrdenesRequisicion");

		$ESM = $sql->Listar_ESM();
		$TiposFuerzas = $sql->Listar_TiposFuerzas();
		$TiposRequisiciones = $sql->Listado_TiposRequisicion();
		$CentrosUtilidad = $sql->Listado_CentrosUtilidad($_REQUEST['datos']['empresa_id']);
		//print_r($CentrosUtilidad);
		$Obj_Form=AutoCarga::factory("ESM_OrdenesRequisicion_HTML", "views", "app","ESM_OrdenesRequisicion");
		$action['volver'] = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Crear_OrdenesRequisicion")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
		$action['opcion1'] = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Crear_NuevoTemporal")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
		$action['Guardar'] = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Guardar_NuevoTemporal");
		$Html_Form=$Obj_Form->Vista_FormularioNuevoDoc($action,$ESM,$TiposFuerzas,$TiposRequisiciones,$CentrosUtilidad);
		$this->salida = $Html_Form;
		return true;
		}
    
    function Guardar_NuevoTemporal()
		{
		$request = $_REQUEST;
		$sql = AutoCarga::factory("Consultas_OrdenesRequisicion","classes","app","ESM_OrdenesRequisicion");
    $token=$sql->Insertar_ORequisicionTemporal($_REQUEST);
    $url = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Modificar_Temporal")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&orden_requisicion_tmp_id=".$token['orden_requisicion_tmp_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
    
    $html .= "<script>";
    if(!$token)
      $html .= " history.go(-1) ";
      else 
        $html .= "window.location=\"".$url."\";";
    $html .= "</script>";
    $this->salida=$html;
		return true;
		}
    
    function Modificar_Temporal()
		{
		$request = $_REQUEST;
		
    IncludeFileModulo("Remotos_ESM_OrdenesRequisicion","RemoteXajax","app","ESM_OrdenesRequisicion");
		$action['volver'] = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Crear_OrdenesRequisicion")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
    $this->SetXajax(array("Listado_Productos","Guardar_Registros","Borrar_Item","Listado_Productos_TMP","Guardar_Cambios","CrearDocumento"),null,"ISO-8859-1");
    $sql = AutoCarga::factory("Consultas_OrdenesRequisicion","classes","app","ESM_OrdenesRequisicion");
    $DocTemporal=$sql->Obtener_InfoDocTemporal($_REQUEST['orden_requisicion_tmp_id'],$_REQUEST['datos']['empresa_id']);
    $BodegaSatelite=$sql->Bodega($_REQUEST['datos']['empresa_id'],$DocTemporal['centro_utilidad'],$DocTemporal['bodega']);
    //print_r($BodegaSatelite);
    //print_r($DocTemporal);
	//print_r("lleg");
    $Obj_Form=AutoCarga::factory("ESM_OrdenesRequisicion_HTML", "views", "app","ESM_OrdenesRequisicion");
    $html = $Obj_Form->Vista_FormularioModificarDoc($action,$DocTemporal,$BodegaSatelite);    
    $this->salida=$html;
		return true;
		}
		
    /*  CREAR ORDENES DE SUMINISTRO */
	
	    function Crear_OrdenesSuministro()
		{
			$request = $_REQUEST;

			IncludeFileModulo("Remotos_ESM_OrdenesRequisicion","RemoteXajax","app","ESM_OrdenesRequisicion");
			$this->SetXajax(array("Listado_Temporales_Suministro"),null,"ISO-8859-1");

			$sql = AutoCarga::factory("Consultas_OrdenesRequisicion","classes","app","ESM_OrdenesRequisicion");

			$Obj_Form=AutoCarga::factory("ESM_OrdenesSuministros_HTML", "views", "app","ESM_OrdenesRequisicion");
			$action['volver'] = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
			$action['suministro'] = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Crear_NuevoTemporal_suministro")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
		
			$Html_Form=$Obj_Form->Vista_OrdenesSuministrosTemporales($action);
			$this->salida = $Html_Form;
			return true;
		}
    
		function Crear_NuevoTemporal_suministro()
		{
			$request = $_REQUEST;

			IncludeFileModulo("Remotos_ESM_OrdenesRequisicion","RemoteXajax","app","ESM_OrdenesRequisicion");
			$this->SetXajax(array("Listado_Bodegas"),null,"ISO-8859-1");

			$sql = AutoCarga::factory("Consultas_OrdenesSuministro","classes","app","ESM_OrdenesRequisicion");

			$ESM = $sql->Listar_ESM();
			$TiposFuerzas = $sql->Listar_TiposFuerzas();
			$TiposRequisiciones = $sql->Listado_TiposRequisicion();
			$CentrosUtilidad = $sql->Listado_CentrosUtilidad($_REQUEST['datos']['empresa_id']);
			//print_r($CentrosUtilidad);
			$Obj_Form=AutoCarga::factory("ESM_OrdenesSuministros_HTML", "views", "app","ESM_OrdenesRequisicion");
			$action['volver'] = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Crear_OrdenesSuministro")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
			$action['opcion1'] = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Crear_NuevoTemporal_suministro")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
			$action['Guardar'] = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Guardar_NuevoTemporal_Suministro");
			$Html_Form=$Obj_Form->Vista_FormularioNuevoDoc_Suministro($action,$ESM,$TiposFuerzas,$TiposRequisiciones,$CentrosUtilidad);
			$this->salida = $Html_Form;
			return true;
		}
	/*  GUARDAR EL DOCUMENTO TEMPORAL DE LA ORDEN DE SUMINISTRO*/

		function Guardar_NuevoTemporal_Suministro()
		{
			$request = $_REQUEST;
			$sql = AutoCarga::factory("Consultas_OrdenesSuministro","classes","app","ESM_OrdenesRequisicion");
			$token=$sql->Insertar_OSuministroTemporal($_REQUEST);
			$url = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Modificar_Temporal_Suministro")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&orden_requisicion_tmp_id=".$token['orden_requisicion_tmp_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";

			$html .= "<script>";
			if(!$token)
			$html .= " history.go(-1) ";
			else 
			$html .= "window.location=\"".$url."\";";
			$html .= "</script>";
			$this->salida=$html;
		return true;
		}
    
		function Modificar_Temporal_Suministro()
		{
			$request = $_REQUEST;
			
			IncludeFileModulo("Remotos_ESM_OrdenesRequisicion","RemoteXajax","app","ESM_OrdenesRequisicion");
			$action['volver'] = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Crear_OrdenesSuministro")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
			$this->SetXajax(array("CrearDocumento_suministro","Guardar_Cambios_Suministros","Modificar_Informacion_por_pac","Borrar_Item_suminstro_Tmp","Listado_Productos_TMP_s","Regresar_Buscardor_Item","Borrar_Item_suminstr","GuardarPT","Listado_pacientes","Listado_Productos","Buscar_pacientes_s","Guardar_Registros","Borrar_Item","Listado_Productos_TMP","Guardar_Cambios","CrearDocumento"),null,"ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			
			$sql = AutoCarga::factory("Consultas_OrdenesSuministro","classes","app","ESM_OrdenesRequisicion");
			$DocTemporal=$sql->Obtener_InfoDocTemporal($_REQUEST['orden_requisicion_tmp_id'],$_REQUEST['datos']['empresa_id']);
			$BodegaSatelite=$sql->Bodega($_REQUEST['datos']['empresa_id'],$DocTemporal['centro_utilidad'],$DocTemporal['bodega']);
			//print_r($BodegaSatelite);
			//print_r($DocTemporal);
			$Obj_Form=AutoCarga::factory("ESM_OrdenesSuministros_HTML", "views", "app","ESM_OrdenesRequisicion");
			$html = $Obj_Form->Vista_FormularioModificarDoc_suministro($action,$DocTemporal,$BodegaSatelite);    
			$this->salida=$html;
		return true;
		}
	
	
	
  }
?>