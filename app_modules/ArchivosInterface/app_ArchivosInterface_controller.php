<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_ArchivosInterface_controller.php,v 1.1 2010/12/17 19:20:05 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Control: ArchivosInterface
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class app_ArchivosInterface_controller extends classModulo
	{
    /**
    * Constructor de la clase
    */
    function app_ArchivosInterface_controller(){}
    /**
    * Metodo de control principal del modulo
    *
    * @return boolean
    */
    function main()
    {
      $arc = AutoCarga::factory("PlanosSQL","classes","app","ArchivosInterface");
      
      $permisos = $arc->ObtenerPermisos(UserGetUID());
      if(!empty($permisos))
      {
        $url[0] = 'app';
  			$url[1] = 'ArchivosInterface';
  			$url[2] = 'controller';
  			$url[3] = 'MenuInicial';
  			$url[4] = 'Archivos';
  			$arreglo[0] = 'EMPRESA';
					
        $this->salida = gui_theme_menu_acceso('CARGAR ARCHIVOS CAPITADOS',$arreglo,$permisos,$url,ModuloGetURL('system','Menu'));
      }
      else
      {
        $mdl = AutoCarga::factory("MensajesModuloHTML","views","app","ArchivosInterface");
        
        $mensaje = "SU USUARIO NO TIENE PERMISOS PARA TRABAJAR EN ESTE MODULO";
        $action['volver'] = ModuloGetURL('system','Menu');
        $this->salida = $mdl->FormaMensajeModulo($action,$mensaje);
      }

      return true;
    }
    /**
    * Metodo de control para hacer la seleccion del plan
    *
    * @param boolean
    */
    function MenuInicial()
    {
      $request = $_REQUEST;
      if(!empty($request['Archivos']))
        SessionSetVar('EmpresaArchivosInterface',$request['Archivos']);
      
      $mdl = AutoCarga::factory("MensajesModuloHTML","views","app","ArchivosInterface");
      
      $action['volver'] = ModuloGetURL('app','ArchivosInterface','controller','main');
      $action['aceptar'] = ModuloGetURL('app','ArchivosInterface','controller','SubirArchivo');
      
      $this->salida .= $mdl->FormaMenuInicial($action);
      return true;
    }    
    /**
    * Metodo de control para hacer la seleccion del plan
    *
    * @param boolean
    */
    function SubirArchivo()
    {
      $request = $_REQUEST;
      
      $empresa = SessionGetVar('EmpresaArchivosInterface');
      $mdl = AutoCarga::factory("ArchivosHTML","views","app","ArchivosInterface");
      
      $action['volver'] = ModuloGetURL('app','ArchivosInterface','controller','MenuInicial');
      $action['aceptar'] = ModuloGetURL('app','ArchivosInterface','controller','IngresarArchivo',array("archivo_subir"=>$request['archivo_subir']));
      
      $this->salida .= $mdl->FormaRecepcionArchivos($action,$request);
      return true;
    }
    /**
    * Metodo de control para subir el archivo del plan
    *
    * @return boolean
    */
    function IngresarArchivo()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar('EmpresaArchivosInterface');

      $request['usuario_id'] = UserGetUID();
      $request['empresa_id'] = $empresa['empresa_id'];
      $arc = AutoCarga::factory("PlanosSQL","classes","app","ArchivosInterface");
      
      $rst = true;
      switch($request['archivo_subir'])
      {
        case 'DE': $rst = $arc->SubirArchivoPlanoDespachos($request) ; break;
        case 'FR': $metodo_ir = ""; break;
        case 'MD': $rst = $arc->SubirArchivoPlanoMedicos($request); break;
        case 'PA': $rst = $arc->SubirArchivoPlanoAfiliados($request); break;
      }
      
      $mensaje = "EL ARCHIVO ".$_FILES ['archivo_capitado']['name'].", FUE CARGADO CORRECTAMENTE";
      if(!$rst)
        $mensaje = $arc->mensajeDeError;
      
      $mdl = AutoCarga::factory("MensajesModuloHTML","views","app","ArchivosInterface");
        
      $action['volver'] = ModuloGetURL('app','ArchivosInterface','controller','SubirArchivo',array("archivo_subir"=>$request['archivo_subir']));
      $this->salida = $mdl->FormaMensajeModulo($action,$mensaje);

      return true;
    }
  }  
?>