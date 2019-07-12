<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_UV_AfiliacionesNovedades_controller.php,v 1.1 2007/12/19 23:11:47 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Control: UV_AfiliacionesNovedades
  * Clase de control para el modulo de novedades registradas manualmente
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class app_UV_AfiliacionesNovedades_controller extends classModulo
	{
		/**
		* Constructor de la clase
		*/
		function app_UV_AfiliacionesNovedades_controller(){}
		/**
		* Funcion principal del modulo
    *
    * @return boolean
		*/
		function Main()
		{
			$afi = AutoCarga::factory("AfiliacionesNovedades", "", "app","UV_AfiliacionesNovedades");
			$mdl = AutoCarga::factory("AfiliacionesNovedadesHTML", "views", "app","UV_AfiliacionesNovedades");
      
      $permisos = $afi->ObtenerPermisos();
      $action['volver'] = ModuloGetURL('system','Menu');
      
      if($permisos)
      {
        $mensaje = "SU USUARIO NO TIENE PERMISOS PARA TRABAJAR EN ESTE MODULO";
        $this->salida = $mdl->FormaMensajeModulo($action,$mensaje);
        return true;
      }
      
      $action['aceptar'] = ModuloGetURL('app','UV_AfiliacionesNovedades','controller','MenuNovedades');
      $this->SetXajax(array("BuscarAfiliado"),"app_modules/UV_AfiliacionesNovedades/RemoteXajax/AfiliacionesNovedades.php");
			
      $tipos = $afi->ObtenerTiposIdentificacion();
			$this->salida = $mdl->FormaBuscarAfiliado($action,$tipos);
			return true;
		}
    /**
		* Funcion de control para mostrar el menu de novedades 
    *
    * @return boolean
		*/
		function MenuNovedades()
		{
			$request = $_REQUEST;
      
			$mdl = AutoCarga::factory("AfiliacionesNovedadesHTML", "views", "app","UV_AfiliacionesNovedades");
      
      $action['volver'] = ModuloGetURL('app','UV_AfiliacionesNovedades','controller','Main');
      $action['novedad'] = ModuloGetURL('app','UV_AfiliacionesNovedades','controller','ModificarDatos',array("afiliado_tipo_id"=>$request['afiliado_tipo_id'],"afiliado_id"=>$request['afiliado_id']));
			
			$this->salida = $mdl->FormaMenuInicial($action);
			return true;
		}
    /**
    * Funcion de control para hacer la modificacion de datos
    *
    * @return boolean
    */
    function ModificarDatos()
    {
      $request = $_REQUEST;
 			$mdl = AutoCarga::factory("AfiliacionesNovedadesHTML", "views", "app","UV_AfiliacionesNovedades");
			$afi = AutoCarga::factory("AfiliacionesNovedades", "", "app","UV_AfiliacionesNovedades");
      $this->SetXajax(array("AfiliadoExiste"),"app_modules/UV_AfiliacionesNovedades/RemoteXajax/AfiliacionesNovedades.php");

      $action['volver'] = ModuloGetURL('app','UV_AfiliacionesNovedades','controller','MenuNovedades',array("afiliado_tipo_id"=>$request['afiliado_tipo_id'],"afiliado_id"=>$request['afiliado_id']));
      $action['aceptar'] = ModuloGetURL('app','UV_AfiliacionesNovedades','controller','IngresarNovedades',array("afiliado_tipo_id_v"=>$request['afiliado_tipo_id'],"afiliado_id_v"=>$request['afiliado_id'],"novedad"=>$request['novedad']));
      
      $informacion = $afi->ObtenerInformacionAfiliado($request);
      $tipos = $afi->ObtenerTiposIdentificacion();

      $this->salida .= $mdl->FormaModificarInformacion($action,$informacion,$tipos,$request['novedad']);
      return true;
    }
    /**
    * Funcion de control para hacer el ingreso a la BD de la novedad generada
    *
    * @return boolean
    */
    function IngresarNovedades()
    {
      $request = $_REQUEST;
 			$mdl = AutoCarga::factory("AfiliacionesNovedadesHTML", "views", "app","UV_AfiliacionesNovedades");
			$afi = AutoCarga::factory("AfiliacionesNovedades", "", "app","UV_AfiliacionesNovedades");
      
      $mensaje = "";
      $action = array();
      $rst = $afi->IngresarNovedad($request);
      if(!$rst)
      {
        $action['volver'] = ModuloGetURL('app','UV_AfiliacionesNovedades','controller','ModificarDatos',array("afiliado_tipo_id"=>$request['afiliado_tipo_id_v'],"afiliado_id"=>$request['afiliado_id_v'],"novedad"=>$request['novedad']));
        $mensaje = "HA OCURRIDO UN ERROR AL ACTUALIZAR LOS DATOS <br>".$afi->ErrMsg();
      }
      else
      {
        if(!$request['afiliado_tipo_id'])
        {
          $request['afiliado_id'] = $request['afiliado_id_v'];
          $request['afiliado_tipo_id'] = $request['afiliado_tipo_id_v'];
        }
        $action['volver'] = ModuloGetURL('app','UV_AfiliacionesNovedades','controller','MenuNovedades',array("afiliado_tipo_id"=>$request['afiliado_tipo_id'],"afiliado_id"=>$request['afiliado_id']));
        $mensaje = "LA ACTUALIZACION DE LOS DATOS SE REALIZO CORRECTAMENTE";
      }
      $this->salida = $mdl->FormaMensajeModulo($action,$mensaje);
      return true;
    }
  }
?>