<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_Inv_AutorizacionesIngreso_controller.php,v 1.29 2010/02/17 14:46:54 johanna Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  /**
  * Clase Control: Inv_AutorizacionesIngreso
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.29 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
    class app_Inv_AutorizacionesIngreso_controller extends classModulo
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
        function app_Inv_AutorizacionesIngreso_controller(){}
        /**
        * Funcion principal del modulo
        *
        * @return boolean
        */
        
		function main()
		{	
			
			$request = $_REQUEST;
						
			$url[0]='app';                         //Tipo de Modulo
			$url[1]='Inv_AutorizacionesIngreso';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			$url[3]='Menu';   						//Metodo.
			$url[4]='datos';						//vector de $_request.
			$arreglo[0]='EMPRESA';					//Sub Titulo de la Tabla
						
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("Permisos", "", "app","Inv_AutorizacionesIngreso");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$datos=$obj_busqueda->BuscarPermisos(); 
		
		//Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
										// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
			$forma = gui_theme_menu_acceso("AUTORIZACIONES DE INGRESO DE PRODUCTOS X ORDEN DE COMPRA",$arreglo,$datos,$url,ModuloGetURL('system','Menu')); 
			$this->salida=$forma;
				
 			return true; 
		}
		
      /*
      * FUNCION DE MENU PRINCIPAL
      */
		function Menu()
		{
  		/*Crear el Menú de Opciones*/
      $request = $_REQUEST;
  		$Obj_Menu=AutoCarga::factory("AutorizacionesIngreso_MenuHTML", "views", "app","Inv_AutorizacionesIngreso");
  		
  		//Volver a Empresas.
  		$action['volver'] = ModuloGetURL("app","Inv_AutorizacionesIngreso","controller","main");
  		      
	    $this->salida=$Obj_Menu->Menu($action,$request['datos']['empresa_id']);
  		
  		return true;
		}
		
    
    
    /* FUNCION CONSULTAR/AUTORIZAR INGRESOS
		*  Funcion que consiste en Autorizar Ingresar Productos en el momento de Ingresar por Ordenes de Compra. 
		*  @param NULL
		*	return booleam.
		*/
		
		function Consultar_AutorizarProductosIngresos()
		{
		$request = $_REQUEST;
		
      
		IncludeFileModulo("RemotosAutorizacionesIngreso","RemoteXajax","app","Inv_AutorizacionesIngreso");
		$this->SetXajax(array("Listar_ProductosPorAutorizar","Autorizar","RegistrarAutorizacion"));
                          
                       
		
		/*
		* Para Manejo de Ventanitas Flotantes... capitas!
		*/
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("AutorizacionesIngresoHTML", "views", "app","Inv_AutorizacionesIngreso");
    
		$action['volver'] = ModuloGetURL("app","Inv_AutorizacionesIngreso","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
			
		
		$Html_Form=$Obj_Form->main($action,$request);
				
		
		$this->salida = $Html_Form;
    
    
    return true;
		
		}
    
    
  
    
}