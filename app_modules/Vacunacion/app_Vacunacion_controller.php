<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: app_Vacunacion_controller.php,v 1.3 2009/11/05 19:55:42 alexander Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma Vargas
  */
  /**
  * Clase Control: UV_Vacunacion
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma Vargas
  */
 class app_Vacunacion_controller extends classModulo
 {
    //Constructor
    function app_Vacunacion_controller(){}
       
    /*
    *Esta funcion es la que verifica los permisos de la empresa
    */
    function main()
    {  
      $mdl = AutoCarga::factory("VacunaHTML","views","app","Vacunacion");
      $action['volver'] = ModuloGetURL("system","Menu");
      SessionDelVar('vacunacion');
      $crt = AutoCarga::factory("Consultas_vacunas","classes","app","Vacunacion");
      $permisos = $crt->BuscarPermisos(UserGetUID());    
      $mtz[0]='EMPRESA';
      $url[0]='app';				        //contenedor
      $url[1]='Vacunacion';	            //módulo
      $url[2]='controller';		        //clase
      $url[3]='listadoParametros';	//método 
      $url[4]='permisos';		        //indice del request
      $this->salida .= gui_theme_menu_acceso('VACUNACION ', $mtz, $permisos, $url, ModuloGetURL('system','Menu'));
      return true;
    }
    
    /*
    *Esta funcion es la que me trae las vacunas existentes en la tabla vacunas_parametro
    */
    function listadoParametros()
    {
      $request = $_REQUEST;
      if($request['permisos'])
      SessionSetVar("PermisoVacuna",$request['permisos']);
          
      $action['volver'] = ModuloGetURL("app","Vacunacion","controller");  
      $action['vacuna'] = ModuloGetURL("app","Vacunacion","controller","listarVacunas");
      $action['buscador'] = ModuloGetURL("app","Vacunacion","controller","listadoParametros");
      $action['desactivar'] = ModuloGetURL("app","Vacunacion","controller","desactivar");
      $action['modificar'] = ModuloGetURL("app","Vacunacion","controller","modificarVacuna"); 
 
      $html = AutoCarga::factory("VacunaHTML","views","app","Vacunacion"); 
      $mdl = AutoCarga::factory("Consultas_vacunas","classes","app","Vacunacion");    
      $buscar_vacunas=$mdl->buscarVacunasParametros($request);
             
      $action['paginador'] = ModuloGetURL("app","Vacunacion","controller","listadoParametros");
      $this->salida=$html->VentanaAsignar($action,$buscar_vacunas,$mdl->conteo,$mdl->pagina);
      return true;
    }
   
    /*
    *Esta funcion se encarga de traer todas las vacunas existentes en la tabla cups
    */
    function listarVacunas()
    {
      $request = $_REQUEST;
      $mdl = AutoCarga::factory("VacunaHTML","views","app","Vacunacion");
      $action['volver'] = ModuloGetURL("app","Vacunacion","controller","listadoParametros"); 
      $action['buscar'] = ModuloGetURL("app","Vacunacion","controller","agregarVacuna");  
      $action['buscador'] = ModuloGetURL("app","Vacunacion","controller","listarVacunas");  
              
      $html = AutoCarga::factory("VacunaHTML","views","app","Vacunacion");  
      $mdl = AutoCarga::factory("Consultas_vacunas","classes","app","Vacunacion");    
      $buscar_vacunas=$mdl->buscarVacunasCups($request);
         
      $action['paginador'] = ModuloGetURL("app","Vacunacion","controller","listarVacunas",array("cargo"=>$request["cargo"],"descripcion"=>$request["descripcion"]));
      $this->salida=$html->VentanaBuscar($action,$buscar_vacunas,$mdl->conteo,$mdl->pagina,$request);
      return true;   
    }
   
    /**
    *Esta funcion es donde se parametrizan todos los datos de la vacuna y se manda a la funcion insertar.
    */
    function agregarVacuna()
    {
      $request = $_REQUEST;
      IncludeFileModulo("eventosDosis","RemoteXajax","app","Vacunacion");
      $this->SetXajax(array("registroDosis","registroRefuerzos"),null,"ISO-8859-1" );
       
      $action['volver'] = ModuloGetURL("app","Vacunacion","controller","listarVacunas"); 
      $action['insertar'] = ModuloGetURL("app","Vacunacion","controller","insertarDatos",array("cargo_cups"=>$request["cargo_cups"]));
           
      $html = AutoCarga::factory("VacunaHTML","views","app","Vacunacion");  
      $mdl = AutoCarga::factory("Consultas_vacunas","classes","app","Vacunacion");
       
      $unidades=$mdl->unidades_tiempo();
      $sexo=$mdl->genero();
      $aplicacion=$mdl->viaAdministracion();
      $this->salida=$html->VentanaVacuna($action,$request,$unidades,$sexo,$aplicacion);
      return true;
    }
    
    /**
    *Esta funcion inserta los datos parametrizados por el usuario y quedan ya guardados en la base de datos,
    *ademas ya queda en  ventanaBuscar 
    */
    function insertarDatos()
    {
      $request = $_REQUEST;
      $html = AutoCarga::factory("VacunaHTML","views","app","Vacunacion");
      $action['volver'] = ModuloGetURL("app","Vacunacion","controller","listarVacunas"); 
      $mdl = AutoCarga::factory("Consultas_vacunas","classes","app","Vacunacion");
           
      $empresas= SessionGetVar("PermisoVacuna");
      $insertar=$mdl->insertarVacuna($request,$empresas,$unidades);
      if(!$insertar)
        $mensaje = $html->mensajeDeError;
      else
        $mensaje = 'LOS DATOS HAN SIDO GUARDADOS SATISFACTORIAMENTE';
        
      $this->salida=$html->FormaMensajeModulo($action,$mensaje);
      return true;
    } 
    
    /**
    *Esta funcion es para descativar o activar una vacuna que se encuantre en la ventana Asignar 
    */
    function desactivar()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL("app","Vacunacion","controller","listadoParametros"); 
      $mdl = AutoCarga::factory("Consultas_vacunas","classes","app","Vacunacion");
      $html = AutoCarga::factory("VacunaHTML","views","app","Vacunacion");
      $action['desactivar'] = ModuloGetURL("app","Vacunacion","controller","desactivarVacuna",array("cargo_cups"=>$request["cargo_cups"]));
       
      $desactivar=$mdl->desactivarVacuna($request);
        if(!$desactivar)
          $mensaje = $html->mensajeDeError;
        else
        {
          if($request['sw_estado'] == "0")
            $mensaje = 'LA VACUNA HA SIDO DESACTIVADA';
          else
            $mensaje = 'LA VACUNA HA SIDO ACTIVADA';
        }       
        $this->salida=$html->FormaMensajeModulo($action,$mensaje);
        return true;
    }
    
    /**
    *Esta funcion me permite modificar una vacuna ya existente,
    */
    function modificarVacuna()
    {
      $request = $_REQUEST;
      IncludeFileModulo("eventosDosis","RemoteXajax","app","Vacunacion");
      $this->SetXajax(array("registroDosis","registroRefuerzos"),null,"ISO-8859-1" );
       
      $action['volver'] = ModuloGetURL("app","Vacunacion","controller","listadoParametros"); 
      $action['insertar'] = ModuloGetURL("app","Vacunacion","controller","modificarDatos",array("cargo_cups"=>$request["cargo_cups"]));
           
      $html = AutoCarga::factory("VacunaHTML","views","app","Vacunacion");  
      $mdl = AutoCarga::factory("Consultas_vacunas","classes","app","Vacunacion");
       
      $unidades=$mdl->unidades_tiempo();
      $sexo=$mdl->genero();
      $aplicacion=$mdl->viaAdministracion();
      $datos=$mdl->traerDatos($request);
      $datosDosis=$mdl->traerDatosDosis($request);
      $datosRefuerzos=$mdl->traerDatosRefuerzos($request);
      
      $this->salida=$html->VentanaVacuna($action,$datos,$unidades,$sexo,$aplicacion,$datosDosis,$datosRefuerzos);
      return true;
    }
    
    /**
    *esta funcion me permite guardar los datos de la vacuna ya modificada
    */
    function modificarDatos()
    {
      $request = $_REQUEST;
      $html = AutoCarga::factory("VacunaHTML","views","app","Vacunacion");
      $action['volver'] = ModuloGetURL("app","Vacunacion","controller","listadoParametros"); 
      $mdl = AutoCarga::factory("Consultas_vacunas","classes","app","Vacunacion");
           
      $empresas= SessionGetVar("PermisoVacuna");
      $modificar=$mdl->modificarVacuna($request,$empresas,$unidades);
      if(!$modificar)
        $mensaje = $html->mensajeDeError;
      else
        $mensaje = 'LOS DATOS HAN SIDO MODIFICADOS SATISFACTORIAMENTE';
      
      $this->salida=$html->FormaMensajeModulo($action,$mensaje);
      return true;
    }
       
 }
?>