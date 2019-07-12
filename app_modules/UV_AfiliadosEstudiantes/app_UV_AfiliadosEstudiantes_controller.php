<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_UV_AfiliadosEstudiantes_controller.php,v 1.1.1.1 2009/09/11 20:36:58 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Control: UV_AfiliadosEstudiantes
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1.1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class app_UV_AfiliadosEstudiantes_controller extends classModulo
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
		function app_UV_AfiliadosEstudiantes_controller(){}
		/**
		* Funcion principal del modulo
    *
    * @return boolean
		*/
		function RetiroEstudiantes()
		{
      $request = $_REQUEST;
			
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $this->IncludeJS('RemoteXajax/ConsultaxAfiliados.js', $contenedor='app', $modulo='UV_Afiliaciones');
      
      $afi = AutoCarga::factory("Listados", "", "app","UV_AfiliadosEstudiantes");
			$mdl = AutoCarga::factory("ListadosHTML", "views", "app","UV_AfiliadosEstudiantes");
      
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Novedades');
      $action['buscar'] = ModuloGetURL('app','UV_AfiliadosEstudiantes','controller','RetiroEstudiantes');
      $action['retirar'] = ModuloGetURL('app','UV_AfiliadosEstudiantes','controller','RegistrarRetiroEstudiantes');
      $action['paginador'] = ModuloGetURL('app','UV_AfiliadosEstudiantes','controller','RetiroEstudiantes',array("buscador"=>$request['buscador']));
      
      $tipos_documento = $afi->ObtenerTiposIdentificacion();
      $estamentos = $afi->ObtenerEstamentos();
      $afiliados = $afi->ObtenerListaBeneficiarios($request['buscador'] ,$request['offset']);
			
      $this->salida = $mdl->FormaListadoEstudiantes($action,$request['buscador'],$tipos_documento,$estamentos,$afiliados,$afi->pagina,$afi->conteo,$afi->msgError);
			return true;
		}
    /**
    *
    */
    function RegistrarRetiroEstudiantes()
    {
      $request = $_REQUEST;
      
      $estado['estado_afiliado_id'] = ModuloGetVar('app','UV_AfiliadosEstudiantes','estado_afiliado');
      $estado['subestado_afiliado_id'] = ModuloGetVar('app','UV_AfiliadosEstudiantes','subestado_afiliado');
      
      $afi = AutoCarga::factory("Listados", "", "app","UV_AfiliadosEstudiantes");
      $rst = $afi->ActualizarEstadosAfiliado($request['afi'],$estado);
      
      $action['volver'] = ModuloGetURL('app','UV_AfiliadosEstudiantes','controller','RetiroEstudiantes');
      $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_AfiliadosEstudiantes");
     
      $mensaje = "EL RETIRO DE LOS AFILIADOS, SE REALIZO CORRECTAMENTE";
      if(!$rst)
      {
        $mensaje = $afi->error."<br>ERROR ".$ing->mensajeDeError;
      }

      $this->salida = $mdl->FormaMensajeModulo($action,$mensaje);
      
      return true;
    }
  }
?>