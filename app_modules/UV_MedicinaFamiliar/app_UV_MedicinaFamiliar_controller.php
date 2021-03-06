<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_UV_MedicinaFamiliar_controller.php,v 1.32 2008/01/15 13:40:39 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */
  /**
  * Clase Control: UV_MedicinaFamiliar
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.32 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */
	class app_UV_MedicinaFamiliar_controller extends classModulo
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
		function app_Main_controller(){}
		/**
		* Funcion principal del modulo
    *
    * @return boolean
		*/
		function Main()
		{
            $afi = AutoCarga::factory("Afiliaciones1", "", "app","UV_MedicinaFamiliar");
            $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_MedicinaFamiliar");

            $this->action['volver'] = ModuloGetURL('system','Menu');
            SessionDelVar("permisosAfiliaciones");
      
            $permisos = $afi->ObtenerPermisos();
      
            if(empty($permisos))
            {
            $mensaje = "SU USUARIO NO TIENE PERMISOS PARA TRABAJAR EN ESTE MODULO";
            $this->salida = $mdl->FormaMensajeModulo($this->action,$mensaje);
            return true;
            }
      
      
      
			//$this->action['afiliacion'] = ModuloGetURL('app','UV_Afiliaciones','controller','BuscarAfiliado');
			//$this->action['modificacion'] = ModuloGetURL('app','UV_Afiliaciones','controller','ModificarAfiliado');
            $this->action['consultar_medico'] = ModuloGetURL('app','UV_MedicinaFamiliar','controller','Listar_Medicos');
            $this->action['consulta_afiliados'] = ModuloGetURL('app','UV_MedicinaFamiliar','controller','Consulta_Afiliados');
            
            //$this->action['novedades'] = ModuloGetURL('app','UV_Afiliaciones','controller','Novedades');
			//$this->action['estudiante'] = ModuloGetURL('app','UV_Afiliaciones','controller','PeriodosCobertura');
			$this->salida = $mdl->FormaMenuInicial($this->action);
			return true;
		}



    /**
    * Funcion que consulta a los diferentes afiliados por diferentes criterios
    * access public;
    * @return boolean
    */
    function Consulta_Afiliados()
    {
        SessionSetVar("rutaImagenes",GetThemePath());
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS('RemoteXajax/ConsultaxAfiliados.js', $contenedor='app', $modulo='UV_MedicinaFamiliar');
        $file = 'app_modules/UV_MedicinaFamiliar/RemoteXajax/ConsultaxAfiliados.php';
        $this->SetXajax(array("ObtenerSubestados","BuscarDatos","BuscarBeneficiarios","ObtenerMedicos","AsignarMedico_Grupo"),$file);
        $this->action['volver'] = ModuloGetURL('app','UV_MedicinaFamiliar','controller','Main');
        $this->action['sacar_info'] = ModuloGetURL('app','UV_MedicinaFamiliar','controller','Info_AfiliadosCotizante');
    
        $afi = AutoCarga::factory("Afiliaciones1", "", "app","UV_MedicinaFamiliar");
        $tipo_identificacion = $afi->ObtenerTiposIdentificacion();
        $tipos_afiliados = $afi->ObtenerTiposAfiliados();
        $estados_afiliados = $afi->ObtenerTiposEstadosAfiliados();
        $dependencias = $afi->ObtenerDependenciasUV();
        $estamentos = $afi->ObtenerEstamentos();
        $tipos_aportantes = $afi->ObtenerTiposAportantes();
        $mdl = AutoCarga::factory("ConsultaAfiliadoHTML", "views", "app","UV_MedicinaFamiliar");
        
        $this->salida .= $mdl->FormaConsultaAfiliado($this->action,$tipo_identificacion,$tipos_afiliados,$estados_afiliados,$dependencias,$estamentos,$tipos_aportantes);
        if($_REQUEST['volver']=='Volver')
        {
            //var_dump(SessionGetVar("BUSQUEDA"));
           // $datos=SessionGetVar("BUSQUEDA");
            //var_dump(SessionGetVar("PAGINA"));
            $pagina=SessionGetVar("PAGINA");
            $this->salida .="<script language=\"javaScript\">
                    function mOvr(src,clrOver) 
                    {
                    src.style.background = clrOver;
                    }
                        BuscarAfiliados2('".$pagina."',0);
                        
                   </script>";
        }
        else
        {
            $datos=SessionDelVar("BUSQUEDA");
            $pagina=SessionDelVar("PAGINA");
        }    
        return true;
    }

    /**
    * Funcion que consulta a los diferentes afiliados por diferentes criterios
    * access public;
    * @return boolean
    */
    function Listar_Medicos()
    {
        SessionSetVar("rutaImagenes",GetThemePath());
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS('RemoteXajax/ConsultaxAfiliados.js', $contenedor='app', $modulo='UV_MedicinaFamiliar');
        $file = 'app_modules/UV_MedicinaFamiliar/RemoteXajax/ConsultaxAfiliados.php';
        $this->SetXajax(array("ListarGruposFamiliares","ObtenerSubestados","BuscarDatos","BuscarBeneficiarios","BuscarBeneficiarios1","ObtenerMedicos","AsignarMedico_Grupo"),$file);
        $this->action['volver'] = ModuloGetURL('app','UV_MedicinaFamiliar','controller','Main');
        $afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_MedicinaFamiliar");
        $mdl = AutoCarga::factory("ConsultaAfiliadoHTML", "views", "app","UV_MedicinaFamiliar");
        $vector_medicos=$afi->ListarMedicosCon_N_gf();
        $this->salida .= $mdl->FormaListarMedicos($this->action,$vector_medicos);
        return true;
    }
    /**
    * Funcion que consulta a los diferentes afiliados por diferentes criterios
    * access public;
    * @return boolean
    */
    function Info_AfiliadosCotizante()
    {
        SessionSetVar("rutaImagenes",GetThemePath());
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS('RemoteXajax/ConsultaxAfiliados.js', $contenedor='app', $modulo='UV_MedicinaFamiliar');
        $file = 'app_modules/UV_MedicinaFamiliar/RemoteXajax/ConsultaxAfiliados.php';
        $this->SetXajax(array(),$file);
        $this->action['volver'] = ModuloGetURL('app','UV_MedicinaFamiliar','controller','Consulta_Afiliados');
        $this->action['registrar'] = ModuloGetURL('app','UV_MedicinaFamiliar','controller','Consulta_Afiliados');
        $afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_MedicinaFamiliar");
        $datos_cotizante = $afi->GetDatosAfiliado($_REQUEST['eps_afiliacion_id'],$_REQUEST['afiliado_tipo_id'],$_REQUEST['afiliado_id']);
        //var_dump($datos_cotizante);
           $tipos_afiliados = $afi->ObtenerTiposAfiliados();
                     $estados_afiliados = $afi->ObtenerTiposEstadosAfiliados();
                     $dependencias = $afi->ObtenerDependenciasUV();
                     $estamentos = $afi->ObtenerEstamentos();
                     $tipos_aportantes = $afi->ObtenerTiposAportantes();
        $mdl = AutoCarga::factory("ConsultaAfiliadoCotizanteHTML", "views", "app","UV_MedicinaFamiliar");

        $this->salida .= $mdl->FormaDatosAfiliado($this->action,$datos_cotizante,$_REQUEST['salida']);
        return true;
    }

  }
?>