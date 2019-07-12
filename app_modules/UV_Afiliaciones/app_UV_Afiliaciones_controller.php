<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_UV_Afiliaciones_controller.php,v 1.7 2009/12/14 14:42:34 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Control: UV_Afiliaciones
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.7 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class app_UV_Afiliaciones_controller extends classModulo
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
		function app_UV_Afiliaciones_controller(){}
		/**
		* Funcion principal del modulo
    *
    * @return boolean
		*/
		function Main()
		{
			$afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
			$mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_Afiliaciones");

      $this->action['volver'] = ModuloGetURL('system','Menu');
      SessionDelVar("permisosAfiliaciones");
      
      $permisos = $afi->ObtenerPermisos();
      
      if(empty($permisos))
      {
        $mensaje = "SU USUARIO NO TIENE PERMISOS PARA TRABAJAR EN ESTE MODULO";
        $this->salida = $mdl->FormaMensajeModulo($this->action,$mensaje);
        return true;
      }
      
      SessionSetVar("permisosAfiliaciones",$permisos);
      
			$this->action['afiliacion'] = ModuloGetURL('app','UV_Afiliaciones','controller','BuscarAfiliado');
			$this->action['modificacion'] = ModuloGetURL('app','UV_Afiliaciones','controller','ModificarAfiliado');
			$this->action['consulta_afiliados'] = ModuloGetURL('app','UV_Afiliaciones','controller','Consulta_Afiliados');
			$this->action['novedades'] = ModuloGetURL('app','UV_Afiliaciones','controller','Novedades');
			$this->action['archivos_planos'] = ModuloGetURL('app','UV_Afiliaciones','controller','ReportesArchivosPlanos');
			$this->action['estudiante'] = ModuloGetURL('app','UV_Afiliaciones','controller','PeriodosCobertura');
      $this->action['impresion_carnet'] = ModuloGetURL('app','UV_Afiliaciones','controller','impresion_carnet');
			$this->salida = $mdl->FormaMenuInicial($this->action,$permisos[UserGetUID()]);
			return true;
		}
		/**
		* Funcion de control para la busqueda de afiliados
    *
    * @return boolean
		*/
		function BuscarAfiliado()
		{
 			$this->SetXajax(array("BuscarAfiliado"),"app_modules/UV_Afiliaciones/RemoteXajax/IngresoAfiliados.php");

      $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Main');
			$this->action['registrar'] = ModuloGetURL('app','UV_Afiliaciones','controller','RegistrarAfiliacion');

			$afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_Afiliaciones");
			$tipo_identificacion = $afi->ObtenerTiposIdentificacion();
			
			$mdl = AutoCarga::factory("IngresoAfiliadoHTML", "views", "app","UV_Afiliaciones");
			$this->salida .= $mdl->FormaBuscarAfiliado($this->action,$tipo_identificacion);
			return true;
		}
    /**
    * Funcion de control para adicionar beneficiarios a un cotizante previamente registrado
    *
    * @return boolean
    */
    function AdicionarBeneficiario()
    {
 			$this->SetXajax(array("MostrarTablaBeneficiarios","EliminarBeneficiario"),"app_modules/UV_Afiliaciones/RemoteXajax/IngresoAfiliados.php");
      SessionDelVar("beneficiarios");
      
      $this->request = $_REQUEST;
      $this->request['documento'] = $this->request['afiliado_id'];
      $this->request['tipo_id_paciente'] = $this->request['afiliado_tipo_id'];
      
 			$afi = AutoCarga::factory("ConsultarBeneficiarios", "", "app","UV_Afiliaciones");
      $mdl = AutoCarga::factory("RegistrarBeneficiarioHTML", "views", "app","UV_Afiliaciones");

      $beneficiarios = $afi->ObtenerBeneficiariosCotizante($this->request,2);
      $afiliado = $afi->ObtenerDatosAfiliados($this->request,'NOT');

      $ctz["cotizante_tipo_id"] = $afiliado['afiliado_tipo_id'];
      $ctz["cotizante_id"] = $afiliado['afiliado_id'];
      $ctz["eps_afiliacion_id"]= $afiliado['eps_afiliacion_id'];

      $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Consulta_Afiliados');
			$this->action['beneficiario'] = ModuloGetURL('app','UV_Afiliaciones','controller','IngresarBeneficiario',$ctz);
      $this->action['crear'] = ModuloGetURL('app','UV_Afiliaciones','controller','IngresarAfiliacionNuevoBeneficiario',$ctz);
      
      $this->salida = $mdl->FormaInformacionCotizante($this->action,$afiliado,$beneficiarios);
      return true;
    }
    /**
    * Funcion de control para el registro de los beneficiarios asociados a una afiliacion
    *
    * @return boolean
    */
    function IngresarAfiliacionNuevoBeneficiario()
    {
      $this->request = $_REQUEST;
      $beneficiarios = SessionGetVar("beneficiarios");
      
      if(empty($beneficiarios))
      {
        $this->Consulta_Afiliados();
        return true;
      } 
      $ing = AutoCarga::factory("IngresarAfiliados", "", "app","UV_Afiliaciones");
      $rst = $ing->IngresarDatosAfiliacionBeneficiarios($this->request,$beneficiarios);
      
      $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Consulta_Afiliados');
      $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_Afiliaciones");
     
      $mensaje = "EL INGRESO O ACTUALIZACION DE LOS DATOS DE LOS BENEFICIARIOS, SE REALIZO CORRECTAMENTE";
      if(!$rst)
      {
        $mensaje = $ing->error."<br>ERROR ".$ing->mensajeDeError;
      }
      else
      {
        SessionDelVar("beneficiarios");
      }
      $this->salida = $mdl->FormaMensajeModulo($this->action,$mensaje);
      
      return true;
		}
		/**
		* Funcion de control para el registro de afiliaciones
    *
    * @return boolean		
    */
		function RegistrarAfiliacion()
		{
      $this->request = $_REQUEST;
			$this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','BuscarAfiliado');

			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->SetXajax(array("SeleccionarDatosDefecto","SeleccionarSubGrupoPrincipal","SeleccionarSubGrupos","SeleccionarGruposPrimarios","SeleccionarActividad","SeleccionarClase","MostrarInformacionPlan"),"app_modules/UV_Afiliaciones/RemoteXajax/IngresoAfiliados.php","ISO-8859-1");
      
			$afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
			$tipo_afiliacion = $afi->ObtenerTiposAfiliaciones();
			$tipo_aportante = $afi->ObtenerTiposAportantes();
			$tipo_afiliado = $afi->ObtenerTiposAfiliados();
			$estado_civil = $afi->ObtenerTiposEstadoCivil();
			$estamentos = $afi->ObtenerEstamentos();
			$estratos = $afi->ObtenerTiposEstratosSocioeconomicos();
			$ocupacion = $afi->ObtenerGruposOcupacion();
			$actividad = $afi->ObtenerDivisionActividadEconomica();
			$dependencia = $afi->ObtenerDependenciasUV();
			$afiliado = $afi->ObtenerDatosAfiliados($this->request);
			$convenio = $afi->ObtenerTercerosConvenios();
      $parentesco = $afi->ObtenerTiposParentescos();
      $planes = $afi->ObtenerPlanes();
      $puntos = $afi->ObtenerPuntosAtencion();
      
      $dafiliados = array("tipo_id_paciente"=>$this->request['tipo_id_paciente'],"documento"=>$this->request['documento']);
      if(!empty($afiliado))
        $this->action['crear'] = ModuloGetURL('app','UV_Afiliaciones','controller','ActualizarAfiliacion',$dafiliados);
      else
      {
        $this->action['crear'] = ModuloGetURL('app','UV_Afiliaciones','controller','IngresarAfiliacion',$dafiliados);
        
        $interface = AutoCarga::factory("Interfaces", "", "app","UV_Afiliaciones");
        $afiliado = $interface->GetDatosFuncionario($this->request['tipo_id_paciente'], $this->request['documento']); 
        
        if(empty($afiliado))
        {
          global $ConfigAplication;
          $afiliado = $afi->ObtenerDatosLugarResidencia($ConfigAplication);
        }
        else
        {
          $dts['DefaultPais'] = $afiliado['tipo_pais_id'];
          $dts['DefaultDpto'] = $afiliado['tipo_dpto_id'];
          $dts['DefaultMpio'] = $afiliado['tipo_mpio_id'];
        
          $a = $afi->ObtenerDatosLugarResidencia($dts);
          $afiliado['departamento_municipio'] = $a['departamento_municipio'];
        }
      }
      
      $pensiones = $afi->ObtenerFondosPensiones();
      $eps = $afi->ObtenerEPS();
      $mdl = AutoCarga::factory("IngresoAfiliadoHTML", "views", "app","UV_Afiliaciones");
      $this->salida .= $mdl->FormaRegistrarAfiliacion($this->action,$this->request,$tipo_afiliacion,$estado_civil,$estratos,$tipo_afiliado,$tipo_aportante,$estamentos,$pensiones,$eps,$ocupacion,$actividad,$dependencia,$afiliado,$convenio,$parentesco,$planes,$puntos);

      return true;
		}
    /**
		* Funcion de control para el ingreso de afiliaciones
    *
    * @return boolean
		*/
		function IngresarAfiliacion()
		{
			$this->request = $_REQUEST;
			$this->SetXajax(array("MostrarTablaBeneficiarios","EliminarBeneficiario"),"app_modules/UV_Afiliaciones/RemoteXajax/IngresoAfiliados.php");
      
      $ing = AutoCarga::factory("IngresarAfiliados", "", "app","UV_Afiliaciones");
      $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
      $estamentos = $afi->ObtenerEstamentos();
      
      $rst = $ing->IngresarDatosAfiliacion($this->request,$estamentos);
      $afiliado = $afi->ObtenerDatosAfiliados($this->request,"NOT");
			
      $this->action['crear'] = ModuloGetURL('app','UV_Afiliaciones','controller','IngresarAfiliacionBeneficiario',array("cotizante_tipo_id"=>$afiliado['afiliado_tipo_id'],"cotizante_id"=>$afiliado['afiliado_id'],"eps_afiliacion_id"=>$afiliado['eps_afiliacion_id']));
      $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','BuscarAfiliado');
			$this->action['beneficiario'] = ModuloGetURL('app','UV_Afiliaciones','controller','IngresarBeneficiario',array("cotizante_tipo_id"=>$afiliado['afiliado_tipo_id'],"cotizante_id"=>$afiliado['afiliado_id'],"eps_afiliacion_id"=>$afiliado['eps_afiliacion_id']));
      
      if(!$rst)
      {
        $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','BuscarAfiliado');
       
        $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_Afiliaciones");
        $this->salida = $mdl->FormaMensajeModulo($this->action,$ing->error."<br>".$ing->mensajeDeError);
        return true;
      }
      
      if($this->request['tipo_afiliacion'] == "I")
      {
        $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','BuscarAfiliado');
        $mensaje = "EL INGRESO DE LOS DATOS DE LA AFILIACION, SE REALIZO CORRECTAMENTE";
       
        $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_Afiliaciones");
        $this->salida = $mdl->FormaMensajeModulo($this->action,$mensaje);
      }
      else
      {
        SessionDelVar("beneficiarios");
        $mdl = AutoCarga::factory("IngresoAfiliadoHTML", "views", "app","UV_Afiliaciones");
        $this->salida = $mdl->FormaInformacionCotizante($this->action,$afiliado);
      }
      
			return true;
		}
    /**
		* Funcion de control para la actualizacion de afiliaciones
    *
    * @return boolean
		*/
		function ActualizarAfiliacion()
		{
			$this->request = $_REQUEST;
    	$this->SetXajax(array("MostrarTablaBeneficiarios","EliminarBeneficiario"),"app_modules/UV_Afiliaciones/RemoteXajax/IngresoAfiliados.php");
  
      $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
      $ing = AutoCarga::factory("IngresarAfiliados", "", "app","UV_Afiliaciones");
      $rst = $ing->ActualizarDatosAfiliacion($this->request);
      
      if(!$rst)
      {
        $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','BuscarAfiliado');
       
        $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_Afiliaciones");
        $this->salida = $mdl->FormaMensajeModulo($this->action,$ing->error."<br>".$ing->mensajeDeError);
        return true;
      }
      
      $afiliado = $afi->ObtenerDatosAfiliados($this->request);
   		
 			$this->action['beneficiario'] = ModuloGetURL('app','UV_Afiliaciones','controller','IngresarBeneficiario');
			$this->action['crear'] = ModuloGetURL('app','UV_Afiliaciones','controller','IngresarAfiliacionBeneficiario',array("cotizante_tipo_id"=>$afiliado['afiliado_tipo_id'],"cotizante_id"=>$afiliado['afiliado_id'],"eps_afiliacion_id"=>$afiliado['eps_afiliacion_id']));

      if($this->request['tipo_afiliacion'] == "I")
      {
        $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','BuscarAfiliado');
        $mensaje = "EL INGRESO DE LOS DATOS DE LA AFILIACION, SE REALIZO CORRECTAMENTE";
       
        $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_Afiliaciones");
        $this->salida = $mdl->FormaMensajeModulo($this->action,$mensaje);
      }
      else
      {
        SessionDelVar("beneficiarios");
        $mdl = AutoCarga::factory("IngresoAfiliadoHTML", "views", "app","UV_Afiliaciones");
        $this->salida = $mdl->FormaInformacionCotizante($this->action,$afiliado);
      }
      
			return true;
		}
    /**
		* Funcion de control para la busqueda de afiliados
    *
    * @return boolean
		*/
		function IngresarBeneficiario()
		{
      $request = $_REQUEST;
     
 			if((!$request['afiliado_tipo_id'] && !$request['documento']) OR ($request['afiliado_tipo_id'] == "undefined" && $request['documento'] == "undefined"))
      {
        $this->SetXajax(array("BuscarAfiliado"),"app_modules/UV_Afiliaciones/RemoteXajax/IngresoAfiliados.php");

        $ctz["eps_afiliacion_id"] = $request['eps_afiliacion_id'];
        $ctz["cotizante_tipo_id"] = $request['cotizante_tipo_id'];
        $ctz["cotizante_id"] = $request['cotizante_id'];
        
        $this->action['volver'] = "javascript:window.close()";
        $this->action['registrar'] = ModuloGetURL('app','UV_Afiliaciones','controller','IngresarBeneficiarioDatos',$ctz);
        
        $afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_Afiliaciones");
        $tipo_identificacion = $afi->ObtenerTiposIdentificacion();
			
        $mdl = AutoCarga::factory("IngresoAfiliadoHTML", "views", "app","UV_Afiliaciones");
        $this->salida .= $mdl->FormaBuscarAfiliado($this->action,$tipo_identificacion);
			}
      else
        $this->IngresarBeneficiarioDatos();
        
      return true;
		}
    /**
		* Funcion de control para el ingreso de beneficiarios
    *
    * @return boolean
		*/
		function IngresarBeneficiarioDatos()
		{
			$this->request = $_REQUEST;
      
 			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->SetXajax(array("SeleccionarSubGrupoPrincipal","SeleccionarSubGrupos","SeleccionarGruposPrimarios","AdicionarBeneficiario","MostrarInformacionPlan"),"app_modules/UV_Afiliaciones/RemoteXajax/IngresoAfiliados.php","ISO-8859-1");

      //if(SessionGetVar("beneficiarios"))
      $beneficiarios = SessionGetVar("beneficiarios");
      
      $mdl = AutoCarga::factory("IngresoAfiliadoHTML", "views", "app","UV_Afiliaciones");
			$afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
			
      $tipo_afiliacion = $afi->ObtenerTiposAfiliaciones();
      $eps = $afi->ObtenerEPS();
			$ocupacion = $afi->ObtenerGruposOcupacion();
      //$tipo_identificacion = $afi->ObtenerTiposIdentificacion();
      $parentesco = $afi->ObtenerTiposParentescos();
      $puntos = $afi->ObtenerPuntosAtencion();
      
      if($this->request['tipo_id_paciente'])
        $this->request['afiliado_tipo_id'] = $this->request['tipo_id_paciente']; 
      
      if(empty($beneficiarios[$this->request['afiliado_tipo_id']][$this->request['documento']]))
			{
        $datos['afiliado_tipo_id'] = $this->request['afiliado_tipo_id'];
        $datos['afiliado_id'] = $this->request['documento'];
        
        $dfg = AutoCarga::factory("ModificarDatosAfiliados", "", "app","UV_Afiliaciones");
        $beneficiarios[$datos['afiliado_tipo_id']][$datos['afiliado_id']] = $dfg->ObtenerDatosAfiliadoBeneficiarioRetirado($datos);
        
        if(!empty($afiliado))
          $beneficiarios[$datos['afiliado_tipo_id']][$datos['afiliado_id']]['actualizar'] = "1";
      }
      $afiliado = $beneficiarios[$this->request['afiliado_tipo_id']][$this->request['documento']];
      if(empty($afiliado))
      {
        global $ConfigAplication;
        $afiliado = $afi->ObtenerDatosLugarResidencia($ConfigAplication);
        $afiliado['pais'] = $afiliado['tipo_pais_id']; 
        $afiliado['dpto'] = $afiliado['tipo_dpto_id'];
        $afiliado['mpio'] = $afiliado['tipo_mpio_id'];
        $afiliado['ubicacion_hd'] = $afiliado['departamento_municipio'];
        $afiliado['tipo_id_beneficiario'] = $this->request['afiliado_tipo_id'];
        $afiliado['documento'] = $this->request['documento'];
        
        $planes = $afi->ObtenerPlanCotizante(null,$this->request);
        $afiliado['plan_atencion'] = $planes['plan_id'];
        $afiliado['plan_descripcion'] = $planes['plan_descripcion'];
      }
      else
      {
        $planes = $afi->ObtenerPlanCotizante($afiliado['plan_atencion'],$this->request);
        if(empty($afiliado['plan_atencion'])) $afiliado['plan_atencion'] = $planes['plan_id'];
        
        $afiliado['plan_descripcion'] = $planes['plan_descripcion'];
      }

      $afiliado['fecha_vencimiento_ctz'] = $afi->ObtenerFechaVencimientoCotizante($this->request);
      $this->salida .= $mdl->FormaRegistrarAfiliacionBeneficiario($this->action,$this->request,$tipo_afiliacion,$eps,$ocupacion,$afiliado,$tipo_identificacion,$parentesco,$puntos);
      return true;
		}
    /**
		* Funcion de control para el registro de los beneficiarios asociados a una afiliacion
    *
    * @return boolean
		*/
		function IngresarAfiliacionBeneficiario()
		{
			$this->request = $_REQUEST;
      $beneficiarios = SessionGetVar("beneficiarios");
      
      $ing = AutoCarga::factory("IngresarAfiliados", "", "app","UV_Afiliaciones");
      $rst = $ing->IngresarDatosAfiliacionBeneficiarios($this->request,$beneficiarios);
      
      $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','BuscarAfiliado');
      $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_Afiliaciones");
     
      $mensaje = "EL INGRESO O ACTUALIZACION DE LOS DATOS DE LOS BENEFICIARIOS, SE REALIZO CORRECTAMENTE";
      if(!$rst)
      {
        $mensaje = $ing->error."<br>ERROR ".$ing->mensajeDeError;
      }
      else
      {
        SessionDelVar("beneficiarios");
      }
      $this->salida = $mdl->FormaMensajeModulo($this->action,$mensaje);
      
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
      $this->IncludeJS('RemoteXajax/ConsultaxAfiliados.js', $contenedor='app', $modulo='UV_Afiliaciones');
      
      IncludeFileModulo("ConsultaxAfiliados","RemoteXajax", "app", "UV_Afiliaciones");
      IncludeFileModulo("IngresoAfiliados","RemoteXajax", "app", "UV_Afiliaciones");
      $this->SetXajax(array("ObtenerSubestados","BuscarDatos","EntidadesConvenios","MostrarInformacionPlan"),null,"ISO-8859-1");
      
      $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Main');
      $this->action['sacar_info'] = ModuloGetURL('app','UV_Afiliaciones','controller','Info_AfiliadosCotizante');
  
      $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
      $tipo_identificacion = $afi->ObtenerTiposIdentificacion();
      $tipos_afiliados = $afi->ObtenerTiposAfiliados();
      $estados_afiliados = $afi->ObtenerTiposEstadosAfiliados();
      $dependencias = $afi->ObtenerDependenciasUV();
      $estamentos = $afi->ObtenerEstamentos();
      $tipos_aportantes = $afi->ObtenerTiposAportantes();
      $convenios = $afi->ObtenerTercerosConvenios();
      $planes = $afi->ObtenerPlanes();
      
      $mdl = AutoCarga::factory("ConsultaAfiliadoHTML", "views", "app","UV_Afiliaciones");
      
      $this->salida .= $mdl->FormaConsultaAfiliado($this->action,$tipo_identificacion,$tipos_afiliados,$estados_afiliados,$dependencias,$estamentos,$tipos_aportantes,$convenios,$planes);
      if($_REQUEST['volver']=='Volver')
      {
        $pagina=SessionGetVar("PAGINA");
        $this->salida .= "<script language=\"javaScript\">\n";
        $this->salida .= "  function mOvr(src,clrOver) \n";
        $this->salida .= "  {\n";
        $this->salida .= "    src.style.background = clrOver;\n";
        $this->salida .= "  }\n";
        $this->salida .= "  BuscarAfiliados2('".$pagina."',0);\n";
        $this->salida .= "</script>\n";
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
    function Info_AfiliadosCotizante()
    {
      SessionSetVar("rutaImagenes",GetThemePath());
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $this->IncludeJS('RemoteXajax/ConsultaxAfiliados.js', $contenedor='app', $modulo='UV_Afiliaciones');

      $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Consulta_Afiliados');
      $this->action['registrar'] = ModuloGetURL('app','UV_Afiliaciones','controller','Consulta_Afiliados');
      $afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_Afiliaciones");
	  $bene = AutoCarga::factory("ConsultarBeneficiarios", "", "app","UV_Afiliaciones");
  
      $datos_cotizante = $afi->GetDatosAfiliado($_REQUEST['eps_afiliacion_id'],$_REQUEST['afiliado_tipo_id'],$_REQUEST['afiliado_id']);
      $beneficiarios = $bene->ObtenerBeneficiariosCotizante($_REQUEST,2);
	  
	  $mdl = AutoCarga::factory("ConsultaAfiliadoCotizanteHTML", "views", "app","UV_Afiliaciones");

      $this->salida .= $mdl->FormaDatosAfiliado($this->action,$datos_cotizante,$_REQUEST['salida'],"",$beneficiarios);
      return true;
    }
    /**
    * Funcion de control para la ampliacion del periodo de cobertura del servicio de salud
    *
    * @return boolean
    */
    function ModificarFechasConvenio()
    {
      $this->request = $_REQUEST;
            
      $url = array( 'afiliado_id'=>$this->request['afiliado_id'],
                    'afiliado_tipo_id'=>$this->request['afiliado_tipo_id'],
                    'eps_afiliacion_id'=>$this->request['eps_afiliacion_id']);
      
      $convenio = AutoCarga::factory("ConsultarAfiliadosConvenio", "", "app","UV_Afiliaciones");
      $datos = $convenio->ObtenerFechasConvenio($this->request);
      $mdl = AutoCarga::factory("IngresoAfiliadoHTML", "views", "app","UV_Afiliaciones");
      
      $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Consulta_Afiliados');
      $this->action['aceptar'] = ModuloGetURL('app','UV_Afiliaciones','controller','ActualizarPeriodoCobertura',$url);
      
      $this->salida .= $mdl->FormaModificarFechasConvenio($this->action,$datos);
      return true;
    }
    /**
    * Funcion de control para realizar la modificacion del periodo de cobertura
    *
    * @return boolean
    */
    function ActualizarPeriodoCobertura()
    {
      $this->request = $_REQUEST;
      
      $afi = AutoCarga::factory("IngresarAfiliados", "", "app","UV_Afiliaciones");
      $rst = $afi->ActualizarFechaConvenio($this->request);
      
      $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Consulta_Afiliados');
      $mensaje = "LA ACTUALIZACION DE LOS DATOS SE REALIZO CORRECTAMENTE";
      
      if(!$rst)
        $mensaje = "HA OCURRIDO UN ERROR AL TRATAR DE ACTUA:LIZAR EL REGISTRO <br>".$afi->ErrMsg();
      
      $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_Afiliaciones");
      $this->salida = $mdl->FormaMensajeModulo($this->action,$mensaje);

      return true;
    }
    /**
    * Funcion de control para la modifiacion de los datos de los afiliados
    *
    * @return boolean
    */
    function ModificarAfiliado()
    {
 			$request = $_REQUEST;
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      
      $action['buscar'] = ModuloGetURL('app','UV_Afiliaciones','controller','ModificarAfiliado');
      $action['paginador'] = ModuloGetURL('app','UV_Afiliaciones','controller','ModificarAfiliado',array("buscador"=>$request['buscador']));
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Main');
      $action['retirar'] = ModuloGetURL('app','UV_Afiliaciones','controller','RegistrarRetiroAfiliados');
      
      $afi = AutoCarga::factory("ModificarDatosAfiliados", "", "app","UV_Afiliaciones");
			$tipos_documento = $afi->ObtenerTiposIdentificacion();
			$tipos_afiliados = $afi->ObtenerTiposAfiliados();
      $estamentos = $afi->ObtenerEstamentos();
      
      $estado['estado_afiliado_id'] = ModuloGetVar('app','UV_Afiliaciones','estado_afiliado');
      $estado['subestado_afiliado_id'] = ModuloGetVar('app','UV_Afiliaciones','subestado_afiliado');
      
      if(!empty($request['buscador']))
      {
        $afiliados = $afi->ObtenerListaAfiliados($request['buscador'],$request['offset']);
        $action['modificar'] = ModuloGetURL('app','UV_Afiliaciones','controller','ModificarRegistroDatosAfiliado',array("buscador"=>$request['buscador']));
        $action['estados'] = ModuloGetURL('app','UV_Afiliaciones','controller','CambiarEstadoAfiliado',array("buscador"=>$request['buscador']));
      }
      $html = AutoCarga::factory("ModificarDatosAfiliadosHTML", "views", "app","UV_Afiliaciones");
      $this->salida .= $html->FormaBuscadorAfiliados($action,$request['buscador'],$tipos_documento,$estamentos,$tipos_afiliados,$estado,$afiliados,$afi->pagina,$afi->conteo,$afi->ErrMsg());
      return true;
    }
    /**
		* Funcion de control para el registro de los datos del afiliado
    *
    * @return boolean		
    */
		function ModificarRegistroDatosAfiliado()
		{
			$request = $_REQUEST;
			$action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','ModificarAfiliado',array("buscador"=>$request['buscador']));

			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->SetXajax(array("SeleccionarDatosDefecto","SeleccionarSubGrupoPrincipal","SeleccionarSubGrupos","SeleccionarGruposPrimarios","SeleccionarActividad","ValidarAfiliado","SeleccionarDatosDefectoClase","SeleccionarClase","MostrarInformacionPlan"),"app_modules/UV_Afiliaciones/RemoteXajax/IngresoAfiliados.php","ISO-8859-1");
      
			$afi = AutoCarga::factory("ModificarDatosAfiliados", "", "app","UV_Afiliaciones");
			$ocupacion = $afi->ObtenerGruposOcupacion();
      $eps = $afi->ObtenerEPS();
      $tipo_identificacion = $afi->ObtenerTiposIdentificacion();
      $parentesco = $afi->ObtenerTiposParentescos();
      $planes = $afi->ObtenerPlanes();
      $puntos = $afi->ObtenerPuntosAtencion();
      
      $action['crear'] = ModuloGetURL('app','UV_Afiliaciones','controller','ActualizarInformacionAfiliados',array("eps_afiliacion_id" => $request['eps_afiliacion_id'],"afiliado_tipo_id" => $request['afiliado_tipo_id'],"afiliado_id" => $request['afiliado_id'],"eps_tipo_afiliado_id"=>$request['eps_tipo_afiliado_id'],"buscador"=>$request['buscador']));
			
      $mdl = AutoCarga::factory("ModificarDatosAfiliadosHTML", "views", "app","UV_Afiliaciones");
      if(trim($request['eps_tipo_afiliado_id']) == "C")
      {
        $dependencia = $afi->ObtenerDependenciasUV();
        $pensiones = $afi->ObtenerFondosPensiones();
        $convenio = $afi->ObtenerTercerosConvenios();
        $estamentos = $afi->ObtenerEstamentos();
        $actividad = $afi->ObtenerDivisionActividadEconomica();
        $estado_civil = $afi->ObtenerTiposEstadoCivil();
        $estratos = $afi->ObtenerTiposEstratosSocioeconomicos();
        $tipo_afiliacion = $afi->ObtenerTiposAfiliaciones();
        $tipo_aportante = $afi->ObtenerTiposAportantes();
        $tipo_afiliado = $afi->ObtenerTiposAfiliados();
        $afiliado = $afi->ObtenerDatosAfiliado($request);
        $this->salida .= $mdl->FormaModificarInformacionCotizante($action,$afiliado,$tipo_identificacion,$tipo_afiliacion,$estado_civil,$estratos,$tipo_afiliado,$tipo_aportante,$estamentos,$pensiones,$eps,$ocupacion,$actividad,$dependencia,$convenio,$parentesco,$planes,$puntos);
      }
      else
      {
        $afiliado = $afi->ObtenerDatosAfiliadoBeneficiario($request);
        $afiliado['fecha_vencimiento_ctz'] = $afi->ObtenerFechaVencimientoCotizante($afiliado);
        $this->salida .= $mdl->FormaModificarInformacionBeneficiario($action,$eps,$ocupacion,$afiliado,$tipo_identificacion,$parentesco,$planes,$puntos);
      }
      return true;
		}
    /**
		* Funcion de control para la actualizacion de datos de los afiliados
    *
    * @return boolean
		*/
		function ActualizarInformacionAfiliados()
		{
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','ModificarAfiliado',array("buscador"=>$request['buscador']));
     
      $ing = AutoCarga::factory("ModificarDatosAfiliados", "", "app","UV_Afiliaciones");

      if(trim($request['eps_tipo_afiliado_id']) == "C")
        $rst = $ing->ActualizarDatosAfiliacionCotizante($request);
      else
        $rst = $ing->ActualizarDatosAfiliacionBeneficiario($request);
        
      $mensaje = "LA INFORMACION DEL AFILIADO FUE ACTUALIZADA CORRECTAMENTE";
      if(!$rst)
        $mensaje = $ing->error."<br>".$ing->mensajeDeError;
      
      $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_Afiliaciones");
      $this->salida = $mdl->FormaMensajeModulo($action,$mensaje);
      
			return true;
		}
    /**
		* Funcion de control para hacer el cambio de estado de los afiliados
    *
    * @return boolean		
    */
		function CambiarEstadoAfiliado()
		{
			$request = $_REQUEST;
			$action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','ModificarAfiliado',array("buscador"=>$request['buscador']));

			$this->SetXajax(array("CambiarSubEstados"),"app_modules/UV_Afiliaciones/RemoteXajax/IngresoAfiliados.php");
      
			$afi = AutoCarga::factory("EstadosAfiliados", "", "app","UV_Afiliaciones");
      $datos = $afi->ObtenerEstadosAfiliado($request);
      $estados = $afi->ObtenerEstados($datos['estado_afiliado_id']);
			$anteriores = array("estado_afiliado_id"=>$datos['estado_afiliado_id'],"subestado_afiliado_id"=>$datos['subestado_afiliado_id']);
      
      $action['actualizar'] = ModuloGetURL('app','UV_Afiliaciones','controller','RegistarCambioEstadoAfiliado',array("eps_afiliacion_id" => $request['eps_afiliacion_id'],"afiliado_tipo_id" => $request['afiliado_tipo_id'],"afiliado_id" => $request['afiliado_id'],"eps_tipo_afiliado_id"=>$request['eps_tipo_afiliado_id'],"buscador"=>$request['buscador'],"anterior"=>$anteriores));
      $mdl = AutoCarga::factory("ModificarDatosAfiliadosHTML", "views", "app","UV_Afiliaciones");
      $this->salida .= $mdl->FormaModificarEstadoAfiliado($action,$datos,$estados);

      return true;
		}
    /**
    * Funcion de control para registrar en la base de datos el cambio de estado 
    * de los afiliados
    *
    * @return boolean
    */
    function RegistarCambioEstadoAfiliado()
    {
      $request = $_REQUEST;
     
      $afi = AutoCarga::factory("EstadosAfiliados", "", "app","UV_Afiliaciones");
      $estados = array();
      $beneficiarios = array();
      
      $mensaje = "";
      if(trim($request['eps_tipo_afiliado_id']) == "C")
      {
        $mensaje = " Y DE LOS BENEFICIARIOS ASOCIADOS ";
        $beneficiarios = $afi->ObtenerBeneficiariosCotizante($request);
        $estados = $afi->ObtenerEstadosFlujos($request);
      }
      $rst = $afi->ActualizarrEstadosAfiliado($request,$beneficiarios,$estados);
      
			$action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','ModificarAfiliado',array("buscador"=>$request['buscador']));
      $mensaje = "LA ACTUALIZACION DEL ESTADO DEL AFILIADO ".$mensaje.", SE REALIZO CORRECTAMENTE";
      if(!$rst)
        $mensaje = $afi->error."<br>ERROR ".$afi->mensajeDeError;

      $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_Afiliaciones");
      $this->salida = $mdl->FormaMensajeModulo($action,$mensaje);
      return true;
    }
    /**
		* Funcion de control para el menu de novedades
    *
    * @return boolean
		*/
		function Novedades()
		{
			$mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_Afiliaciones");

      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Main');
			$action['pila_salud'] = ModuloGetURL('app','UV_Afiliaciones','controller','CargarPILA',array("tipo_archivo"=>"S"));
			$action['pila_pension'] = ModuloGetURL('app','UV_Afiliaciones','controller','CargarPILA',array("tipo_archivo"=>"P"));
			$action['novedades'] = ModuloGetURL('app','UV_Afiliaciones','controller','CargarNovedades');
			$action['lista_novedades_pila'] = ModuloGetURL('app','UV_Afiliaciones','controller','ListadoNovedadesNoProcesadasPILA');
			$action['lista_novedades'] = ModuloGetURL('app','UV_Afiliaciones','controller','ListadoNovedadesNoProcesadas');
			$action['historial'] = ModuloGetURL('app','UV_Afiliaciones','controller','HistorialEstados');
			$action['convenios'] = ModuloGetURL('app','UV_Afiliaciones','controller','HistorialFechasConvenios');
			$action['lista_periodos'] = ModuloGetURL('app','UV_Afiliaciones','controller','ListadoPeriodosCobertura');
			$action['archivos'] = ModuloGetURL('app','UV_Afiliaciones','controller','ArchivosNovedades');
			$action['retiros'] = ModuloGetURL('app','UV_AfiliadosEstudiantes','controller','RetiroEstudiantes');
      
      $permisos = SessionGetVar("permisosAfiliaciones");
      
      $this->salida = $mdl->FormaMenuNovedades($action,$permisos[UserGetUID()]);
			return true;
		}
    /**
    * Funcion de control para el ingreso de los archivos de novedades del pila
    *
    * @return boolean
    */
    function CargarPILA()
    {
      $request = $_REQUEST;
      $action['aceptar'] = ModuloGetURL('app','UV_Afiliaciones','controller','SubirArchivoPILA',array("tipo_archivo"=>$request['tipo_archivo']));
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Novedades');
      
      $mdl = AutoCarga::factory("NovedadesHTML", "views", "app","UV_Afiliaciones");
      if($request['tipo_archivo'] == "S")
      {
        $this->salida = $mdl->FormaCargarArchivosSalud($action);
      }
      else
      {
        $this->salida = $mdl->FormaCargarArchivosPension($action);
      }
      return true;
    }
    /**
    * Funcion de control para el registro de los datos contenidos en el pila
    *
    * @return boolean
    */
    function SubirArchivoPILA()
    {
      $request = $_REQUEST;
      
      $nvd = AutoCarga::factory("Novedades", "", "app","UV_Afiliaciones");
      $rst = true;
      if($request['tipo_archivo'] == "S")
        $rst = $nvd->SubirRegistrosSalud($request);
      else
        $rst = $nvd->SubirRegistrosPension($request);
      
      $mensaje = "LOS ARCHIVOS HAN SIDO PROCESADOS CORRECTAMENTE"; 
      if(!$rst)
        $mensaje = $nvd->Err()."<br>ERROR ".$nvd->mensajeDeError;

      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','CargarPILA',array("tipo_archivo"=>$request['tipo_archivo']));
      $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_Afiliaciones");
      $this->salida = $mdl->FormaMensajeModulo($action,$mensaje);
      
      return true;
    }
    /**
    * Funcion de control para el ingreso de los archivos de novedades
    *
    * @return boolean
    */
    function CargarNovedades()
    {
      $request = $_REQUEST;
      $action['aceptar'] = ModuloGetURL('app','UV_Afiliaciones','controller','SubirArchivoPILA',array("tipo_archivo"=>$request['tipo_archivo']));
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Novedades');
      
      $mdl = AutoCarga::factory("NovedadesHTML", "views", "app","UV_Afiliaciones");
      $this->salida = $mdl->FormaCargarArchivosNovedades($action);

      return true;
    }
    /**
    * Funcion de control para mostar la lista de novedades que no fueron procesadas
    * de los archivos del pila
    *
    * @return boolean
    */
    function ListadoNovedadesNoProcesadasPILA()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Novedades');
      $action['buscar'] = ModuloGetURL('app','UV_Afiliaciones','controller','ListadoNovedadesNoProcesadasPILA');
      $action['paginador'] = ModuloGetURL('app','UV_Afiliaciones','controller','ListadoNovedadesNoProcesadasPILA',array("fecha_buscador"=>$request['fecha_buscador']));

      $nvd = AutoCarga::factory("Novedades", "", "app","UV_Afiliaciones");
      $mdl = AutoCarga::factory("NovedadesHTML", "views", "app","UV_Afiliaciones");
      
      $lista = $nvd->ObtenerListaNovedadesPILA($request['offset'],$request['fecha_buscador']);
      $this->salida .= $mdl->FormaListaNovedadesNOProcesadasPILA($action,$lista,$nvd->conteo,$nvd->pagina,$request);
      return true;
    }
    /**
    * Funcion de control para mostar la lista de novedades que no fueron procesadas
    *
    * @return boolean
    */
    function ListadoNovedadesNoProcesadas()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Novedades');
      $action['buscar'] = ModuloGetURL('app','UV_Afiliaciones','controller','ListadoNovedadesNoProcesadas',array("fecha_buscador"=>$request['fecha_buscador']));
      $action['paginador'] = ModuloGetURL('app','UV_Afiliaciones','controller','ListadoNovedadesNoProcesadas');

      $nvd = AutoCarga::factory("Novedades", "", "app","UV_Afiliaciones");
      $mdl = AutoCarga::factory("NovedadesHTML", "views", "app","UV_Afiliaciones");
      
      $lista = $nvd->ObtenerListaNovedades($request['offset'],$request['fecha_buscador']);
      $this->salida .= $mdl->FormaListaNovedadesNOProcesadas($action,$lista,$nvd->conteo,$nvd->pagina,$request);
      return true;
    }
    /**
    * Funcion de control para mostar el historial de cambios en el estado de los afiliados
    *
    * @return boolean
    */
    function HistorialEstados()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Novedades');
      $action['buscar'] = ModuloGetURL('app','UV_Afiliaciones','controller','HistorialEstados');
      $action['paginador'] = ModuloGetURL('app','UV_Afiliaciones','controller','HistorialEstados',array("buscador"=>$request['buscador'],"fecha_registro"=>$request['fecha_registro']));

      $nvd = AutoCarga::factory("EstadosAfiliados", "", "app","UV_Afiliaciones");
      $mdl = AutoCarga::factory("NovedadesHTML", "views", "app","UV_Afiliaciones");
      
      $tipos_id = $nvd->ObtenerTiposIdentificacion();
      $lista = $nvd->ObtenerHistorialEstados($request,$request['offset']);
      
      $this->salida .= $mdl->FormaHistorialEstados($action,$lista,$nvd->conteo,$nvd->pagina,$request,$tipos_id);
      return true;
    }
    /**
    * Funcion de control para mostar el historial de cambios en las fechas de convenio
    *
    * @return boolean
    */
    function HistorialFechasConvenios()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Novedades');
      $action['buscar'] = ModuloGetURL('app','UV_Afiliaciones','controller','HistorialFechasConvenios');
      $action['paginador'] = ModuloGetURL('app','UV_Afiliaciones','controller','HistorialFechasConvenios',array("buscador"=>$request['buscador'],"fecha_registro"=>$request['fecha_registro']));

      $nvd = AutoCarga::factory("Novedades", "", "app","UV_Afiliaciones");
      $mdl = AutoCarga::factory("NovedadesHTML", "views", "app","UV_Afiliaciones");
      
      $tipos_id = $nvd->ObtenerTiposIdentificacion();
      $lista = $nvd->ObtenerHistorialFechasConvenio($request,$request['offset']);
      
      $this->salida .= $mdl->FormaHistorialFechasConvenios($action,$lista,$nvd->conteo,$nvd->pagina,$request,$tipos_id);
      return true;
    }
    /**
    * Funcion de control para buscar los estudiantes para el periodo de cobertura
    *
    * @return boolean
    */
    function PeriodosCobertura()
    {
 			$request = $_REQUEST;
      $this->SetXajax(array("BuscarAfiliadoPeriodoCobertura"),"app_modules/UV_Afiliaciones/RemoteXajax/IngresoAfiliados.php");

      $est = AutoCarga::factory("EstudiantesCertificados", "", "app","UV_Afiliaciones");
      $mdl = AutoCarga::factory("EstudiantesCertificadosHTML", "views", "app","UV_Afiliaciones");
      
      $action['registrar'] = ModuloGetURL('app','UV_Afiliaciones','controller','RegistrarPeriodosCobertura');
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Main');

      $tipos_id = $est->ObtenerTiposIdentificacion();
      $this->salida = $mdl->FormaBuscarAfiliado($action,$tipos_id);
      
      return true;
    }
    /**
    * Funcion de control para hacer el registro del periodo de cobertura
    *
    * @return boolean
    */
    function RegistrarPeriodosCobertura()
    {
      $request = $_REQUEST;
      $est = AutoCarga::factory("EstudiantesCertificados", "", "app","UV_Afiliaciones");
      $mdl = AutoCarga::factory("EstudiantesCertificadosHTML", "views", "app","UV_Afiliaciones");

      $cotizante = array();
      $datos = $est->ObtenerInformacionBaseAfiliado($request['afiliado_tipo_id'],$request['afiliado_id']);
      $periodos = $est->ObtenerPeriodosCobertura();
      $ultimo_periodo = $est->ObtenerUltimoPeriodoCobertura($request['afiliado_tipo_id'],$request['afiliado_id']);
      if($datos['marca'] == '2')
        $cotizante = $est->ObtenerBeneficiariosCotizante($request);
      
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','PeriodosCobertura');
      $action['aceptar'] = ModuloGetURL('app','UV_Afiliaciones','controller','IngresarPeriodoCobertura',array('afiliado_tipo_id'=>$request['afiliado_tipo_id'],'afiliado_id'=>$request['afiliado_id']));

      $this->salida = $mdl->FormaModificarFechasConvenio($action,$datos,$periodos,$ultimo_periodo,$cotizante);
      
      return true;
    }
    /**
    * Funcion de control para hacer el ingreso de los periodos de cobertura al sistema
    *
    * @return boolean
    */
    function IngresarPeriodoCobertura()
    {
      $request = $_REQUEST;
      $est = AutoCarga::factory("EstudiantesCertificados", "", "app","UV_Afiliaciones");
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','PeriodosCobertura');

      $rst = $est->IngresarPeriodoCobertura($request);

      $mensaje = "LOS DATOS DEL PERIODO DE COBERTURA DEL AFILIADO SE HAN GUARDADO CORRECTAMENTE"; 
      if(!$rst)
        $mensaje = $est->Err()."<br>ERROR ".$est->mensajeDeError;
        
      $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_Afiliaciones");
      $this->salida = $mdl->FormaMensajeModulo($action,$mensaje);
      return true;
    }
    /**
    * Funcion de control para mostar la lista de periodos de cobertura
    *
    * @return boolean
    */
    function ListadoPeriodosCobertura()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Novedades');
      $action['buscar'] = ModuloGetURL('app','UV_Afiliaciones','controller','ListadoPeriodosCobertura',array("buscador"=>$request['buscador']));
      $action['anular'] = ModuloGetURL('app','UV_Afiliaciones','controller','AnularPeriodoCobertura');
      $action['paginador'] = ModuloGetURL('app','UV_Afiliaciones','controller','ListadoPeriodosCobertura');

      $est = AutoCarga::factory("EstudiantesCertificados", "", "app","UV_Afiliaciones");
      $mdl = AutoCarga::factory("EstudiantesCertificadosHTML", "views", "app","UV_Afiliaciones");

      $tipos_id = $est->ObtenerTiposIdentificacion();
      $lista = $est->ObtenerListaPeriodos($request['buscador'],$request['offset']);
      $this->salida .= $mdl->FormaListaPeriodosCobertura($action,$lista,$est->conteo,$est->pagina,$request,$tipos_id);
      return true;
    }
    /**
    * Funcion de control para anular los periodos de cobertura
    *
    * @return boolean
    */
    function AnularPeriodoCobertura()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','ListadoPeriodosCobertura');

      $est = AutoCarga::factory("EstudiantesCertificados", "", "app","UV_Afiliaciones");

      $rst = $est->AnularPeriodo($request);
      
      $mensaje = "EL PERIODO DE COBERTURA FUE ANULADO CORRECTAMENTE"; 
      if(!$rst)
        $mensaje = $nvd->Err()."<br>ERROR ".$nvd->mensajeDeError;

      $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_Afiliaciones");
      $this->salida = $mdl->FormaMensajeModulo($action,$mensaje);
      
      return true;
    }    
    /**
    * Funcion de control para anular los periodos de cobertura
    *
    * @return boolean
    */
    function ArchivosNovedades()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Novedades');
      $action['reportes'] = ModuloGetURL('app','UV_Afiliaciones','controller','ArchivosNovedades');
      
      $permisos = SessionGetVar("permisosAfiliaciones");
      
      $nvd = AutoCarga::factory("Novedades", "", "app","UV_Afiliaciones");
      $empresa = $permisos[key($permisos)]['empresa_id'];
      $dtempresas = $nvd->ObtenerCodigoSGSS($empresa);
      $planes = $nvd->ObtenerPlanesAfiliacion();
      
      $fecha_novedad = ModuloGetVar('app','UV_Afiliaciones','fecha_ultimo_archivo');
      $mdl = AutoCarga::factory("NovedadesHTML", "views", "app","UV_Afiliaciones");
      $this->salida = $mdl->FormaGeneraArchivosNovedades($action,$datos,$dtempresas,$fecha_novedad,$request,$planes);
      if($request['fecha_final'])
      {
        $f = explode("/",$request['fecha_final']);
        $f1 = explode("/",$fecha_novedad);
        
        if(sizeof($f1) == 0 || $f[2]."/".$f[1]."/".$f[0] > $f1[2]."/".$f1[1]."/".$f1[0])
          ModuloSetVar('app', 'UV_Afiliaciones', 'fecha_ultimo_archivo', $request['fecha_final']);
      }
      return true;
    }    
    /**
    * Funcion de control para anular los periodos de cobertura
    *
    * @return boolean
    */
    function ReportesArchivosPlanos()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Main');
      $action['reportes'] = ModuloGetURL('app','UV_Afiliaciones','controller','ReportesArchivosPlanos');
            
      $mdl = AutoCarga::factory("NovedadesHTML", "views", "app","UV_Afiliaciones");
      $this->salida = $mdl->FormaGeneraReportesArchivosPlanos($action,$request);
     
     return true;
    }
    /**
    * Funcion que consulta a los diferentes afiliados por diferentes criterios
    * access public;
    * @return boolean
    */
    function Solicitud_CartaConvenio()
    {
        SessionSetVar("rutaImagenes",GetThemePath());
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS('RemoteXajax/ConsultaxAfiliados.js', $contenedor='app', $modulo='UV_Afiliaciones');
        $file = 'app_modules/UV_Afiliaciones/RemoteXajax/ConsultaxAfiliados.php';
        $this->SetXajax(array("Llamar_ciudades","RegistroCarta"),$file);
        $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Consulta_Afiliados');
        $this->action['registrar'] = ModuloGetURL('app','UV_Afiliaciones','controller','Crear_Carta',array('eps_afiliacion_id'=>$_REQUEST['eps_afiliacion_id'],'afiliado_tipo_id'=>$_REQUEST['afiliado_tipo_id'],'afiliado_id'=>$_REQUEST['afiliado_id'],"eps_tipo_afiliado_id"=>$_REQUEST['eps_tipo_afiliado_id']));
        $afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_Afiliaciones");
        $datos_cotizante = $afi->GetDatosAfiliado($_REQUEST['eps_afiliacion_id'],$_REQUEST['afiliado_tipo_id'],$_REQUEST['afiliado_id']);
        $departamentos = $afi->ObtenerDepartamentos();
        $mdl = AutoCarga::factory("MenuCartasHTML", "views", "app","UV_Afiliaciones");
        $this->salida .= $mdl->FormaCartaConvenio($this->action,$datos_cotizante,$departamentos);
        return true;
    }
    /**
    * Funcion que consulta a los diferentes afiliados por diferentes criterios
    * access public;
    * @return boolean
    */
    function Crear_Carta()
    {
      $request = $_REQUEST;
      SessionSetVar("rutaImagenes",GetThemePath());

      $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Consulta_Afiliados');
      $afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_Afiliaciones");
      $datos_cotizante = $afi->GetDatosAfiliado($_REQUEST['eps_afiliacion_id'],$_REQUEST['afiliado_tipo_id'],$_REQUEST['afiliado_id']);
        
      $mdl = AutoCarga::factory("MenuCartasHTML", "views", "app","UV_Afiliaciones");
      $this->salida .= $mdl->ImpresionCarta($this->action,$_REQUEST,$datos_cotizante);
      return true;
    }
    /**
    * Funcion que REALIZA EL PROCESO DE IMPRESION DE CARNETS
    * access public;
    * @return boolean
    */
    function impresion_carnet()
    {
      SessionSetVar("rutaImagenes",GetThemePath());

      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $this->IncludeJS('RemoteXajax/ConsultaxAfiliados.js', $contenedor='app', $modulo='UV_Afiliaciones');
      $file = 'app_modules/UV_Afiliaciones/RemoteXajax/ConsultaxAfiliados.php';
      $this->SetXajax(array("Crear_el_pdf_carnet","BuscarDatosCarnet","ImpresionCarnetParte1"),$file);

      $afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_Afiliaciones");
      
      $tipo_identificacion = $afi->ObtenerTiposIdentificacion();
      $tipos_afiliados = $afi->ObtenerTiposAfiliados();
      
      $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','Main');
      $afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_Afiliaciones");
      $mdl = AutoCarga::factory("ConsultaAfiliadoHTML", "views", "app","UV_Afiliaciones");
      $this->salida .= $mdl->FormaImpresionCarnets($this->action,$tipo_identificacion,$tipos_afiliados);
      return true;
    }
    /**
    * Funcion de control prara hacer el restiro de afiliados
    */
    function RegistrarRetiroAfiliados()
    {
      $request = $_REQUEST;
      
      $estado['estado_afiliado_id'] = ModuloGetVar('app','UV_Afiliaciones','estado_afiliado');
      $estado['subestado_afiliado_id'] = ModuloGetVar('app','UV_Afiliaciones','subestado_afiliado');
      
      $afi = AutoCarga::factory("Listados", "", "app","UV_AfiliadosEstudiantes");
      $rst = $afi->ActualizarEstadosAfiliado($request['afi'],$estado,"C");
      
      $action['volver'] = ModuloGetURL('app','UV_Afiliaciones','controller','ModificarAfiliado');
      $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_AfiliadosEstudiantes");
     
      $mensaje = "EL RETIRO DE LOS AFILIADOS, SE REALIZO CORRECTAMENTE";
      if(!$rst)
        $mensaje = $afi->error."<br>ERROR ".$afi->mensajeDeError;
      
      $this->salida = $mdl->FormaMensajeModulo($action,$mensaje);
      
      return true;
    }
  }
?>