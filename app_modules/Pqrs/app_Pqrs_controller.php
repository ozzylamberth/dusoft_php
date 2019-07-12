<?php
	/**
  * @package DUANA & CIA
  * @version 1.0 $Id: app_Pqrs_controller.php,v 1.0 $
  * @copyright DUANA & CIA JUN-2012
  * @author R.O.M.A
  */
  /** 
  * Clase Control: Pqrs
  * Responsabilidad: Clase encargada del control de llamado de metodos en el modulo
  **/

    class app_Pqrs_controller extends classModulo
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
        function app_Pqrs_controller(){}

		
        /************************************************************ 
        Funcion principal del modulo 
		@return boolean
	    ************************************************************/
		function main()
		{	
			
			$request = $_REQUEST;
						
			$url[0]='app';                         	            //Tipo de Modulo
			$url[1]='Pqrs';   	                                //Nombre del Modulo
			$url[2]='controller';                  			//tipo controller...
			$url[3]='MenuOp';   			                //Metodo.
			$url[4]='datos';									//vector de $_request.
			$arreglo[0]='EMPRESAS';					//Sub Titulo de la Tabla
						
			//Generar busqueda de Permisos SQL
			$permiso = AutoCarga::factory("Permisos", "", "app","Pqrs");
			$datos=$permiso->BuscarPermisos(); 
		
			// Menu de empresas con permiso 
			$forma = gui_theme_menu_acceso("MODULO GESTION PQRS",$arreglo,$datos,$url,ModuloGetURL('system','Menu')); 
			$this->salida=$forma;
				
 			return true; 
		}


        /***************************************************************
         * Funcion de menu de opciones
        ***************************************************************/
		function MenuOp()
		{
  		 /*Crear el Menu de opciones*/
         $request = $_REQUEST;
		 if($request['datos']['empresa_id'])
            SessionSetVar("empresa_id",$request['datos']['empresa_id']);     
		 
         $empresa = SessionGetVar("empresa_id");	
         		 
		// print_r($empresa);	
  		 $vista = AutoCarga::factory("ESM_AdminPqrs_MenuHTML", "views", "app","Pqrs");
         
  		 //$action['volver'] = ModuloGetURL("app","ESM_AdminPqrs","controller","main")."&datos[empresa_id]=".$request['datos']['empresa_id']."";
  		 $action['volver'] = ModuloGetURL("app","Pqrs","controller","main");
  		 
		 
  		 $this->salida=$vista->MenuOpciones($action,$empresa);
  		 
  		 return true;
		}   

        /***************************************************************
         * Funcion crear caso pqrs
        ***************************************************************/
        function Crear_caso()
        {
		 $request = $_REQUEST;
		 
		 IncludeFileModulo("Remotos_pqrs","RemoteXajax","app","Pqrs");
 		 $this->SetXajax(array("GetUserFarm"),null,"ISO-8859-1");
		 
		 $empresa_id = $request['datos']['empresa_id'];
		 //print_r($empresa_id);
		 
		 $sql = Autocarga::factory("Permisos", "", "app","Pqrs");
  		 $vista = AutoCarga::factory("ESM_AdminPqrs_MenuHTML", "views", "app","Pqrs");		 
		 
		 $farmacias = $sql->BuscarBodegas($empresa_id);
		 $razonS = $sql->ListarEmpresas($empresa_id);
		 $categoria = $sql->ListarCategorias();
		 $estadoCaso = $sql->EstadoCasos();
		 $fuerzas = $sql->ListadoFuerzas();
		 $consec = $sql->SerialCaso();
		 
		 $action['volver'] = ModuloGetURL("app","Pqrs","controller","MenuOp")."&datos[empresa_id]=".$request['datos']['empresa_id']."";
		 $action['crea_caso'] = ModuloGetURL("app","Pqrs","controller","Registrar_Caso");
		 
		 $this->salida = $vista->FormaCrearCaso($action,$farmacias,$empresa_id,$razonS,$categoria,$estadoCaso,$fuerzas,$consec);
		 return true;
		}

		
        /***************************************************************
         * Funcion: registrar caso pqrs
        ***************************************************************/
        function Registrar_Caso()
        {
		 $request = $_REQUEST;	
		 
		 //print_r($request);
		 
  		 $vista = AutoCarga::factory("ESM_AdminPqrs_MenuHTML", "views", "app","Pqrs");
		 $sql = Autocarga::factory("DMLs_pqrs", "", "app","Pqrs");
         $action['volver'] = ModuloGetURL("app","Pqrs","controller","MenuOp")."&datos[empresa_id]=".$request['empresa'];

		 $grabar = $sql->insertar_caso($request);
		 
		 if(!$grabar)
		 {
          $mensaje = " ERROR EN LA GRABACION DEL REGISTRO";
         }
		 else
		  {
		   $mensaje = " CASO REGISTRADO CORRECTAMENTE";
		  }
		  
         $this->salida = $vista->FormaMensajeModulo($action,$mensaje); 
   
	     return true;	
		}
	
        /***************************************************************
         * Funcion: Consultas informacion pqrs
        ***************************************************************/
		function Actualizacion_Pqrs()
		{
		 $request = $_REQUEST;
         $empresa_id = $request['datos']['empresa_id'];

		 $this->IncludeJS("CrossBrowser");
		 $this->IncludeJS("CrossBrowserEvent");
		 $this->IncludeJS("CrossBrowserDrag");
		 
  		 $vista = AutoCarga::factory("ESM_AdminPqrs_MenuHTML", "views", "app","Pqrs");
		 $sql = Autocarga::factory("DMLs_pqrs", "", "app","Pqrs");		 
		 
		 $action['volver'] = ModuloGetURL("app","Pqrs","controller","MenuOp")."&datos[empresa_id]=".$request['datos']['empresa_id'];
		 $action['paginador'] = ModuloGetURL('app','Pqrs','controller','Actualizacion_Pqrs',array("buscador"=>$request['buscador']));
		 $action['buscador'] = ModuloGetURL("app", "Pqrs", "controller", "Actualizacion_Pqrs");
         
		 if($request['buscador'])
		 {
		  $datosPqrsAct = $sql->Listar_datosPqrsAct($request['buscador'],$request['offset']);
		 }
         
		 $this->salida = $vista->Listado_pqrsAct($action,$request,$datosPqrsAct,$sql->conteo, $sql->pagina);		 
		 return true;
		}
		
		
        /***************************************************************
         * Funcion: LLamado vista actualizar Casos de Pqrs
        ***************************************************************/		
		function ActualizarCasos()
		{
		 $request = $_REQUEST;
         $empresa=$request['datos']['empresa_id'];
		 $caso = $request['caso'];
		 $bodega =$request['bodega'];
		 $resp = $request['responsable'];
		 $categoria = $request['categoria'];
		 
  		 $vista = AutoCarga::factory("ESM_AdminPqrs_MenuHTML", "views", "app","Pqrs");
		 $sql = Autocarga::factory("DMLs_pqrs", "", "app","Pqrs");
		 
		 $action['volver'] = ModuloGetURL("app","Pqrs","controller","Actualizacion_Pqrs")."&datos[empresa_id]=".$request['datos']['empresa_id'];
		 
		 $datos_caso = $sql->Listar_CasosUpd($request['caso']);
		 $this->salida = $vista->FormaActCaso($action,$datos_caso,$empresa,$caso,$bodega,$resp,$categoria);
		 
		 return true;
		}
		

        /***************************************************************
         * Funcion: Actualizacion Casos de Pqrs en BD
        ***************************************************************/
        function UpdateCaso()
		{
		 $request = $_REQUEST;
         
		 $numcaso = $request['caso'];
		 $empresa = $request['empresa_id'];
		 $observ = $request['observacionAct'];
		 if($request['cerrar_caso'])
		 { 
		  $cerrar = $request['cerrar_caso'];
		 }
		 
  		 $vista = AutoCarga::factory("ESM_AdminPqrs_MenuHTML", "views", "app","Pqrs");
		 $sql = Autocarga::factory("DMLs_pqrs", "", "app","Pqrs");		  
         
         $action['volver'] = ModuloGetURL("app","Pqrs","controller","MenuOp")."&datos[empresa_id]=".$request['empresa_id']; 
	
         $actualizar = $sql->ActualizarCasoPqrs($numcaso,$empresa,$observ,$cerrar);
		 
		 if(!$actualizar)
		 {
	      $mensaje = "ERROR EN LA ACTUALIZACION, VERIFICAR DATOS.";
		 }
         else		 
		  { $mensaje = "CASO ACTUALIZADO SATISFACTORIAMENTE."; }
		 
		 $this->salida = $vista->FormaMensajeModulo($action,$mensaje);
		 
		 return true;
		}
		 
		 
		 
		 
		 
		 
		 
		
		
}