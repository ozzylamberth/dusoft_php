<?php
/**
  * @package SIIS - Duana
  * @version $Id$
  * @copyright 
  * @author  Ronald Marin
  */
 /**
  * Clase Control: Clientes_Farmacias_Duana
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package-SIIS
  * @version $Revision: 1.7 $
  * @copyright 
  * @author
  */

  Class app_Clientes_Farmacias_Duana_controller extends classModulo 
  {
	/**
	* @var array $action  Vector donde se almacenan los links de la aplicacion
	*/
	var $action = array();
	/**
	* @var array $request Vector donde se almacenan los datos pasados por request
	*/
	var $request = array();
  
  
	/************************************************************************************
	* Constructor de la clase
	*************************************************************************************/
	function app_Clientes_Farmacias_Duana_controller(){}
		
    
	/************************************************************************************
	* Funcion principal del modulo  
	@return boolean
	*************************************************************************************/
	function Main()
	{
	   $ifp = AutoCarga::factory("InterfacesPlanesDuana","classes","app","Clientes_Farmacias_Duana"); //Model class
	   $permisos = $ifp->ObtenerPermisos(UserGetUID());

      
            $titulo[0] = 'EMPRESAS';
			$url[0] = 'app';						  //contenedor 
			$url[1] = 'Clientes_Farmacias_Duana';	  //modulo 
			$url[2] = 'controller';				      //clase 
			$url[3] = 'Menu';				          //metodo 
			$url[4] = 'AfiliacionClientes';		      //indice del request
			$this->salida .= gui_theme_menu_acceso('AFILIACION CLIENTES FARMACIAS',$titulo,$permisos,$url,ModuloGetURL('system','Menu'));

        return true;
    }
	
	
	/************************************************************************************
	* Funcion menu de opciones del modulo - empresa
         @return boolean
         *************************************************************************************/	
	function Menu()
    {
      /*$request = $_REQUEST;
		if($request['AfiliacionClientes'])
		SessionSetVar("ClientesFarmacias",$request['AfiliacionClientes']);*/
      
      $action['volver'] = ModuloGetURL('app','Clientes_Farmacias_Duana','controller','main');
      $action['buscarCliente'] = ModuloGetURL('app','Clientes_Farmacias_Duana','controller','Buscar_Cliente');
      
	  $mdl = AutoCarga::factory("MensajesModuloHTML","views","app","Clientes_Farmacias_Duana");
    
      $this->salida .= $mdl->FormaMenuInicial($action);
      return true;
    }
	
		
   /*****************************************************************************************
	* Funcion buscar cliente farmacia
	@return boolean
       ******************************************************************************************/
   function Buscar_Cliente()
   {
		IncludeFileModulo("IngresoClientes","RemoteXajax","app","Clientes_Farmacias_Duana");
 		$this->SetXajax(array("BuscarCliente"),null,"ISO-8859-1");

		$this->action['volver'] = ModuloGetURL('app','Clientes_Farmacias_Duana','controller','Menu');
		$this->action['registrar'] = ModuloGetURL('app','Clientes_Farmacias_Duana','controller','RegistrarAfiliacion'); 

		$cli = AutoCarga::factory("InterfacesPlanesDuana","classes","app","Clientes_Farmacias_Duana");
		$tipo_identificacion = $cli->ObtenerTiposIdentificacion();
		$planes = $cli->ObtenerPlanes();
		//print_r($planes);	
		$mdl = AutoCarga::factory("MensajesModuloHTML","views","app","Clientes_Farmacias_Duana");
		$this->salida .= $mdl->FormaBuscarCliente($this->action,$tipo_identificacion,$planes);
       
    return true;
   }

	/********************************************************************************************
	 *Funcion registrar afiliacion
          @return boolean
	*********************************************************************************************/
	
	function RegistrarAfiliacion()
	{
     $this->request = $_REQUEST;
     //print_r($this->request);
     IncludeFileModulo("IngresoClientes","RemoteXajax","app","Clientes_Farmacias_Duana");
 	 $this->SetXajax(array("BuscarMunicipios"),null,"ISO-8859-1");

	 $this->action['volver'] = ModuloGetURL('app','Clientes_Farmacias_Duana','controller','Menu');
     $this->action['crear'] = ModuloGetURL('app','Clientes_Farmacias_Duana','controller','IngresarAfiliacion');
   
	 $afi = Autocarga::factory("InterfacesPlanesDuana","classes","app","Clientes_Farmacias_Duana");
  
   //Objetos para los campos select del form
     $psis_id = GetVarConfigaplication('DefaultPais'); // obtener el pais desde la variable global de conf de la aplicacion
	 $tiposId = $afi->ObtenerTiposIdentificacion();
     $planes = $afi->ObtenerPlanes();
     $dpto = $afi->ObtenerDepartamentos($psis_id);
     $sex = $afi->ObtenerGenero();
	 $zona = $afi->ObtenerZona();
	 $estrato = $afi->ObtenerEstrato();
	 $estCivil = $afi->ObtenerEstadoCivil();
	 $tipoAfiliado = $afi->ObtenerTipoAfiliado();
   
     $mdl = AutoCarga::factory("MensajesModuloHTML","views","app","Clientes_Farmacias_Duana");
     $this->salida .= $mdl->FormaRegistrarCliente($this->action,$planes,$tiposId,$dpto,$psis_id,$this->request,$sex,$zona,$estrato,$estCivil,$tipoAfiliado);

      return true;
	
	}

    /********************************************************************************************
	*Funcion para la insercion de clientes de farmacia en base de datos
         @ return boolean
	*********************************************************************************************/
  
  function IngresarAfiliacion()
  {

   $this->request = $_REQUEST;
   $afi = AutoCarga::factory("InterfacesPlanesDuana","","app","Clientes_Farmacias_Duana");
   $this->action['volver'] = ModuloGetURL('app','Clientes_Farmacias_Duana','controller','Buscar_Cliente');
   $rst = $afi->IngresarDatosAfiliacion($this->request);
   
   if(!$rst)
   {
       
    $mdl = AutoCarga::factory("MensajesModuloHTML","views","app","Clientes_Farmacias_Duana");
    $this->salida = $mdl->FormaMensajeModulo($this->action,$ing->error."ERROR<br>".$ing->mensajeDeError);
    return true;
   }
  
   $mensaje = "EL CLIENTE FUE REGISTRADO CORRECTAMENTE";
   $mdl = AutoCarga::factory("MensajesModuloHTML","views","app","Clientes_Farmacias_Duana");
   $this->salida = $mdl->FormaMensajeModulo($this->action,$mensaje);
  
   
   return true;
  }
   



}

?>