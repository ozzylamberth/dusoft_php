<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_ESM_Planos_controller.php,v 1.29 2010/02/17 14:46:54 johanna Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  /**
  * Clase Control: ESM_Planos
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.29 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
    class app_ESM_Planos_controller extends classModulo
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
        function app_ESM_Planos_controller(){}
        /**
        * Funcion principal del modulo
        *
        * @return boolean
        */
        
		function main()
		{	
			
			$request = $_REQUEST;
						
			$url[0]='app';                         //Tipo de Modulo
			$url[1]='ESM_Planos';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			$url[3]='Menu';   //Metodo.
			$url[4]='datos';						//vector de $_request.
			$arreglo[0]='EMPRESA';					//Sub Titulo de la Tabla
						
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("Permisos", "", "app","ESM_Planos");
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
			/*Crear el Menú de Opciones*/
			$request = $_REQUEST;
			$Obj_Menu=AutoCarga::factory("ESM_Planos_MenuHTML", "views", "app","ESM_Planos");
		
			$action['volver'] = ModuloGetURL("app","ESM_Planos","controller","main");

			if($request['datos']['empresa_id'])
			SessionSetVar("empresa_id",$request['datos']['empresa_id']);      
			//SessionSetVar("ssiid",$request['datos']['ssiid']);      

			$this->salida=$Obj_Menu->Menu($action);

			return true;
		}
		
 		function Descarga_PlanoFormulacion()
		{
			
			$request = $_REQUEST;
			    
		    //$sql = AutoCarga::factory("Consultas_ESM_Planos","classes","app","ESM_Planos");
	
	   
	      $action['buscar'] = ModuloGetURL('app','ESM_Planos','controller','Descarga_PlanoFormulacion');
	   	    
	    $Obj_Form=AutoCarga::factory("ESM_Planos_HTML", "views", "app","ESM_Planos");
	    $action['volver'] = ModuloGetURL("app","ESM_Planos","controller","Menu")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
	    $action['confirmar'] = ModuloGetURL("app","ESM_Planos","controller","ConfirmaCorte")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
	    //$action['opcion1'] = ModuloGetURL("app","ESM_Planos","controller","Crear_NuevoTemporal")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
	    $Html_Form=$Obj_Form->Vista_Formulario_Descargas($action,$request['buscador'],$DATOS);
	    $this->salida = $Html_Form;
		return true;
		}
		
		
		
		
		
   
  }
?>