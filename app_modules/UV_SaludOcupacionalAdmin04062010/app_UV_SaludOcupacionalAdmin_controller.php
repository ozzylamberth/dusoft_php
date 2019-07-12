<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_UV_SaludOcupacionalAdmin_controller.php,v 1.32 2008/01/15 13:40:39 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */
  /**
  * Clase Control: UV_SaludOcupacionalAdmin
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.32 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */
	class app_UV_SaludOcupacionalAdmin_controller extends classModulo
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
            include "system_modules/Tablas/system_Tablas_controller.php";

            $tablas = new system_Tablas_controller();
            $tablas->SetActionVolver(ModuloGetURL('app','UV_SaludOcupacionalAdmin','controller','Main'));
            $this->action['espacios'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"UV_tipos_de_espacios"));
            $this->action['ocupaciones'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"UV_ocupaciones_SD"));
            

            $this->action['riesgos'] = ModuloGetURL('app','UV_SaludOcupacionalAdmin','controller','GestionRiesgos');
            $this->action['asignar'] = ModuloGetURL('app','UV_SaludOcupacionalAdmin','controller','AsignarAgentesOcupacion');
            $this->action['asignar_espacios'] = ModuloGetURL('app','UV_SaludOcupacionalAdmin','controller','AsignarAgentesEspacios');
            $this->action['man_cargos'] = ModuloGetURL('app','UV_SaludOcupacionalAdmin','controller','ManejodeCargos');
            
            $this->action['volver'] = ModuloGetURL('system','Menu');
            $afi = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
            $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_SaludOcupacionalAdmin");
            $this->salida = $mdl->FormaMenuInicial($this->action);
			return true;
		}



    /**
    * Funcion que consulta a los diferentes afiliados por diferentes criterios
    * access public;
    * @return boolean
    */
    function GestionRiesgos()
    {
        SessionSetVar("rutaImagenes",GetThemePath());
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS('RemoteXajax/SaludOcupacionalAdmin.js', $contenedor='app', $modulo='UV_SaludOcupacionalAdmin');
        $file = 'app_modules/UV_SaludOcupacionalAdmin/RemoteXajax/SaludOcupacionalAdmin.php';
        $this->SetXajax(array("CambiarEstado","ActuaAgenteRiesgoBD","EditarInfoAgente","FormaNuevoTipoRiesgo","GuardarTipoAgente","PintarTiposAgentes","EditarInfo","ActualizarTipoAgente","CrearAgenteRiesgos","CrearAgenteRiesgoBD"),$file);


        $this->action['volver'] = ModuloGetURL('app','UV_SaludOcupacionalAdmin','controller','Main');
        $clase_sql = AutoCarga::factory("SaludOcupacionalAdmin", "", "app","UV_SaludOcupacionalAdmin");
        $tipos_de_riesgo = $clase_sql->ObtenerTiposDeRiesgos();
//         $tipos_afiliados = $afi->ObtenerTiposAfiliados();
//         $estados_afiliados = $afi->ObtenerTiposEstadosAfiliados();
//         $dependencias = $afi->ObtenerDependenciasUV();
//         $estamentos = $afi->ObtenerEstamentos();
//         $tipos_aportantes = $afi->ObtenerTiposAportantes();
        $mdl = AutoCarga::factory("SaludOcupacionalAdminHTML", "views", "app","UV_SaludOcupacionalAdmin");
        $this->salida .= $mdl->FormaRiesgos($this->action,$tipos_de_riesgo,$agentes_de_riesgos);
    
        return true;
    }

    /**
    * Funcion que consulta a los diferentes afiliados por diferentes criterios
    * access public;
    * @return boolean
    */
    function AdministracionOcupaciones()
    {
        SessionSetVar("rutaImagenes",GetThemePath());
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS('RemoteXajax/ConsultaxAfiliados.js', $contenedor='app', $modulo='UV_MedicinaFamiliar');
        $file = 'app_modules/UV_MedicinaFamiliar/RemoteXajax/ConsultaxAfiliados.php';
        $this->SetXajax(array("ListarGruposFamiliares","ObtenerSubestados","BuscarDatos","BuscarBeneficiarios","BuscarBeneficiarios1","ObtenerMedicos","AsignarMedico_Grupo"),$file);


        $mdl = AutoCarga::factory("ConsultaAfiliadoHTML", "views", "app","UV_MedicinaFamiliar");
        
        $this->salida .= $mdl->FormaListarMedicos($this->action,$vector_medicos);
        return true;
    }
    /**
    * Funcion que sirve para la asociacion de agente de riesgos y ocupaciones
    * access public;
    * @return boolean
    */
    function AsignarAgentesOcupacion()
    {
        SessionSetVar("rutaImagenes",GetThemePath());
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS('RemoteXajax/SaludOcupacionalAdmin.js', $contenedor='app', $modulo='UV_SaludOcupacionalAdmin');
        $file = 'app_modules/UV_SaludOcupacionalAdmin/RemoteXajax/SaludOcupacionalAdmin.php';
        $this->SetXajax(array("ValidarCheck","TablaAgentesXOcupacion","llamarAgentesSegunOcupacion"),$file);
        $this->action['volver'] = ModuloGetURL('app','UV_SaludOcupacionalAdmin','controller','Main');
        $mdl = AutoCarga::factory("AsignarAgentesOcupacionHTML", "views", "app","UV_SaludOcupacionalAdmin");
        $this->salida .= $mdl->FormaAgentesPorOcupaciones($this->action);
        return true;
    }

    /**
    * Funcion que sirve para la asociacion de agente de riesgos y ocupaciones
    * access public;
    * @return boolean
    */
    function ManejodeCargos()
    {
        SessionSetVar("rutaImagenes",GetThemePath());
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS('RemoteXajax/SaludOcupacionalAdmin.js', $contenedor='app', $modulo='UV_SaludOcupacionalAdmin');
        $file = 'app_modules/UV_SaludOcupacionalAdmin/RemoteXajax/SaludOcupacionalAdmin.php';
        $this->SetXajax(array("UpCargoBD","Actualizar","RegistraCargoBD","CargosSegunOcupacion","CrearNuevoCargo","TablaOcupacion","ValidarCheck","TablaAgentesXOcupacion","llamarAgentesSegunOcupacion"),$file);
        $this->action['volver'] = ModuloGetURL('app','UV_SaludOcupacionalAdmin','controller','Main');
        $mdl = AutoCarga::factory("CargosOcupacionHTML", "views", "app","UV_SaludOcupacionalAdmin");
        $this->salida .= $mdl->FormaCargosPorOcupaciones($this->action);
        return true;
    }

    
    /**
    * Funcion que sirve para la asociacion de agente de riesgos y espacios
    * access public;
    * @return boolean
    */
    function AsignarAgentesEspacios()
    {
        SessionSetVar("rutaImagenes",GetThemePath());
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS('RemoteXajax/SaludOcupacionalAdmin.js', $contenedor='app', $modulo='UV_SaludOcupacionalAdmin');
        $file = 'app_modules/UV_SaludOcupacionalAdmin/RemoteXajax/SaludOcupacionalAdmin.php';
        $this->SetXajax(array("ValidarEspacioCheck","TablaAgentesXEspacios","llamarAgentesSegunEspacio"),$file);
        $this->action['volver'] = ModuloGetURL('app','UV_SaludOcupacionalAdmin','controller','Main');
        $mdl = AutoCarga::factory("AsignarAgentesEspacioHTML", "views", "app","UV_SaludOcupacionalAdmin");
        $this->salida .= $mdl->FormaAgentesPorEspacio($this->action);
        return true;
    }
  }
?>