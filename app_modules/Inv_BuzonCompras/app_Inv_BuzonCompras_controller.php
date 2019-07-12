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
    class app_Inv_BuzonCompras_controller extends classModulo
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
        function app_Inv_BuzonCompras_controller(){}
        /**
        * Funcion principal del modulo
        *
        * @return boolean
        */
        
		function main()
		{	
			
			$sql=AutoCarga::factory("Permisos", "", "app","Inv_BuzonCompras");
			
			$request = $_REQUEST;
						
			$url[0]='app';                         //Tipo de Modulo
			$url[1]='Inv_BuzonCompras';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			$url[3]='MenuBuzonCompras';   //Metodo.
			$url[4]='datos';						//vector de $_request.
			$arreglo[0]='EMPRESA';					//Sub Titulo de la Tabla
						
			//Generar de Busqueda de Permisos SQL
			
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$datos=$sql->BuscarPermisos(); 
		
		//Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
										// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
			$forma = gui_theme_menu_acceso("Buzon De Mensajes - Compras",$arreglo,$datos,$url,ModuloGetURL('system','Menu')); 
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
		function MenuBuzonCompras()
		{
  		/*Crear el Menú de Opciones*/
		$request = $_REQUEST;
  		IncludeFileModulo("RemotosBuzonCompras","RemoteXajax","app","Inv_BuzonCompras");
		$this->SetXajax(array("MensajesBuzonT","VerMensaje","CambioEstadoMensaje"),null,"ISO-8859-1");
                          
                          
		
    /*
    * Para Manejo de Ventanitas Flotantes... capitas!
    */
    $this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		
		
		
		$Obj_Menu=AutoCarga::factory("BuzonCompras_MenuHTML", "views", "app","Inv_BuzonCompras");
  		$sql=AutoCarga::factory("BuzonCompras", "", "app","Inv_BuzonCompras");
		
  		$action['volver'] = ModuloGetURL("app","Inv_BuzonCompras","controller","main");
		$num=$sql->ContrarMensajesBuzon($_REQUEST['datos']['empresa_id']);
		
		//$_REQUEST[0]['a'];
	
  		$this->salida=$Obj_Menu->Menu($action,$num);
  		
  		return true;
		}
		
    
    
    /* FUNCION CREAR ESTADOS DOCUMENTOS
		*  Funcion que consiste en Generar estados que pueden tener los documentos x empresa y usuario
		*  @param NULL
		*	return booleam.
		*/
		
		
    
}