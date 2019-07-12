<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_ReportesProdPaciPlan_Facturados_controller.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  /**
  * Clase Control:ReportesProdPaciPlan_Facturados
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
    class app_ReportesProdPaciPlan_Facturados_controller extends classModulo
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
        function app_ReportesProdPaciPlan_Facturados_controller(){}
        /**
        * Funcion principal del modulo
        *
        * @return boolean
        */
        
		function main()
		{	
			
			
			
			$request = $_REQUEST;
						
			$url[0]='app';                         //Tipo de Modulo
			$url[1]='ReportesProdPaciPlan_Facturados';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			//$url[3]='Menu_VerificacionProductosDevueltos';   //Metodo.
			$url[3]='Menu';   //Metodo.
			$url[4]='datos';						//vector de $_request.
			$arreglo[0]='EMPRESA';					//Sub Titulo de la Tabla
						
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("Permisos", "", "app","ReportesProdPaciPlan_Facturados");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$datos=$obj_busqueda->BuscarPermisos(); 
		
		//Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
										// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
			$forma = gui_theme_menu_acceso("MODULO DE REPORTES DE PRODUCTOS PACIENTES Y PLANES FACTURADOS",$arreglo,$datos,$url,ModuloGetURL('system','Menu')); 
			$this->salida=$forma;
					
      /*			
			//(nombre de la Tabla Acceso,
			FormaMostrarMenuHospitalizacion($forma); //Invocar un view, para mostrar la informacion.
      */
			return true; 

		}
	/*
	* Método para  crear el Menú
	* 
	*/
    function Menu()
		{	
      $request = $_REQUEST;
	  
      if($request['datos']['empresa_id'])
        SessionSetVar("empresa_id",$request['datos']['empresa_id']);  
      
      $action['volver'] = ModuloGetURL("app","ReportesProdPaciPlan_Facturados","controller","main");
      $Obj_Menu=AutoCarga::factory("Reportes_MenuHTML", "views", "app","ReportesProdPaciPlan_Facturados");
      
      $this->salida .=$Obj_Menu->Menu($action);
      return true; 
		}
      
		/*
		* FUNCION PRINCIPAL DEL MODULO DE CREAR LOS REPORTES
		*/
	  function CrearReporte()
		{	
      $request = $_REQUEST;
      /*
      * Para Manejo de Ventanitas Flotantes... capitas!
      */
      $empresa = SessionGetVar("empresa_id");
      
      $action['volver'] = ModuloGetURL("app","ReportesProdPaciPlan_Facturados","controller","Menu");
      $action['buscar'] = ModuloGetURL("app","ReportesProdPaciPlan_Facturados","controller","CrearReporte");
      if($request['fecha_inicial'] && $request['fecha_final'])
      {
        $sql = AutoCarga::factory("GenerarReporte","classes","app","ReportesProdPaciPlan_Facturados");
        $reportes = $sql->ObtenerReportes($empresa,$request);
      }
      $Obj_Menu=AutoCarga::factory("Reportes_MenuHTML", "views", "app","ReportesProdPaciPlan_Facturados");
      
      $this->salida =$Obj_Menu->CrearReportes($action,$request,$reportes);
      return true; 
		}   
  }
?>