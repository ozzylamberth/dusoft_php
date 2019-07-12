<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_UV_SolicitudesAutorizaciones_controller.php,v 1.11 2009/02/04 14:19:51 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Control: UV_SolicitudesAutorizaciones
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.11 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class app_UV_SolicitudesAutorizaciones_controller extends classModulo
  {
    /**
    * Constructor de la clase
    */
    function app_UV_SolicitudesAutorizaciones_controller(){}
    /**
    * Funcion donde se eliminan variables de sesion usuadas en el modulo
    */
    function eliminarSessiones()
    {
      SessionDelVar("MedicamentosProveedores");
      SessionDelVar("Medicamentos");
      SessionDelVar("Proveedores");
      SessionDelVar("MedicamentosProveedores");
      SessionDelVar("Equivalencias");
      SessionDelVar("CargosProveedores");
      SessionDelVar("CargosProveedores");
    }
    /**
    * Funcion donde se validan las variables de modulo necesarias para la ejecucion del mismo
    *
    * @return mixed
    */
    function validarVariables()
    {
      if(ModuloGetVar('app','UV_SolicitudesAutorizaciones','dias_vencimiento') == "")
        return "PARA EL MODULO UV_SolicitudesAutorizaciones LA VARIABLE dias_vencimiento NO EXISTE O NO CONTIENE NINGUN VALOR";
        
      if(ModuloGetVar('app','UV_SolicitudesAutorizaciones','precio_tope') == "")
        return "PARA EL MODULO UV_SolicitudesAutorizaciones LA VARIABLE precio_tope NO EXISTE O NO CONTIENE NINGUN VALOR";

      if(ModuloGetVar('app','UV_SolicitudesAutorizaciones','lista_precios') == "")
        return "PARA EL MODULO UV_SolicitudesAutorizaciones LA VARIABLE lista_precios NO EXISTE O NO CONTIENE NINGUN VALOR";

      if(ModuloGetVar('app','UV_SolicitudesAutorizaciones','nivel_autorizador') == "")
        return "PARA EL MODULO UV_SolicitudesAutorizaciones LA VARIABLE nivel_autorizador NO EXISTE O NO CONTIENE NINGUN VALOR";

      if(ModuloGetVar('app','UV_SolicitudesAutorizaciones','dias_vencimiento_os') == "")
        return "PARA EL MODULO UV_SolicitudesAutorizaciones LA VARIABLE dias_vencimiento_os NO EXISTE O NO CONTIENE NINGUN VALOR";
      
      return 1;
    }
    /**
    * Funcion principal del modulo
    * 
    * @return boolean
    */
    function Main()
    {
      $cxp = AutoCarga::factory('SolicitudesAutorizacion','','app','UV_SolicitudesAutorizaciones');
      
      SessionDelVar("EmpresasSolicitudes");
      SessionDelVar("CargosAdicionados");
      SessionDelVar("ConceptosAdicionados");
      SessionDelVar("MedicamentosAdicionados");
      
      $action['volver'] = ModuloGetURL('system','Menu');
      if(($rst = $this->validarVariables()) != 1)
      {
        $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_SolicitudesAutorizaciones');
        $this->salida = $html->FormaMensajeModulo($action,$rst);
        return true;
      }
      
      $permisos = $cxp->ObtenerPermisos();
      
      if(empty($permisos))
      {
        $mensaje = "SU USUARIO NO CUENTA CON PERMISOS PARA ACCEDER A ESTE MODULO";
        $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_SolicitudesAutorizaciones');
        $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      }
      else
      {
        $ttl_gral = "SOLICITUDES";
        $titulo[0]='EMPRESAS';
  			$url[0]='app';							
  			$url[1]='UV_SolicitudesAutorizaciones';	
  			$url[2]='controller';	
  			$url[3]='Menu';				
  			$url[4]='permiso_ss';
  			$this->salida = gui_theme_menu_acceso($ttl_gral,$titulo,$permisos,$url,$action['volver']);
      }
      return true;
    } 
    /**
    * Funcion de control para el menu
    * 
    * @return boolean
    */
    function Menu()
    {
      $request = $_REQUEST;
      if(!empty($request['permiso_ss'])) SessionSetVar("EmpresasSolicitudes",$request['permiso_ss']);
      
      $action['volver'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','Main');
      $action['solicitud'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','BusquedaCreacionSolicitudes');
      $action['ordenes'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','ListadoOrdenesServicio');
      
      $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_SolicitudesAutorizaciones');
      $this->salida = $html->FormaMenuInicial($action);
      return true;
    }
    /**
    * Funcion de control para la busqueda o creacion de solicitudes de servicios
    *
    * @return boolean
    */
    function BusquedaCreacionSolicitudes()
    {
      $request = $_REQUEST;
 			$this->SetXajax(array("ValidarPaciente"),"app_modules/UV_SolicitudesAutorizaciones/RemoteXajax/Solicitudes.php");
			$this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
      
      $this->eliminarSessiones();
      
      $action['volver'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','Menu');
      $action['aceptar'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','DatosPaciente');

      $slc = AutoCarga::factory('SolicitudesAutorizacion','','app','UV_SolicitudesAutorizaciones');

      $empresa = SessionGetVar("EmpresasSolicitudes");
      $tiposdocumentos = $slc->ObtenerTiposIdentificacion();
      $planes = $slc->ObtenerPlanes();
      
      $html = AutoCarga::factory('ListaSolicitudesHTML','views','app','UV_SolicitudesAutorizaciones');
      $conteo = $pagina = 0;
      if(!empty($request['buscador']))
      {
        $listado = $slc->ObtenerListaSolicitudesNoAutorizadas($request['buscador'],$request['offset']);
        
        $action['buscador'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','BusquedaCreacionSolicitudes');
        $action['autorizar'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','AutorizarCargos');
        $action['paginador'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','BusquedaCreacionSolicitudes',array("buscador"=>$request['buscador']));

        $conteo = $slc->conteo;
        $pagina = $slc->pagina;
      }
      
      $this->salida = $html->FormaOrdenes($action,$planes,$tiposdocumentos,$request['buscador'],$listado,$conteo,$pagina);

      return true;
    }
    /**
    * Funcion de control para la solicitud de datos del paciente
    * 
    * @return boolean
    */
    function DatosPaciente()
    {
      $request = $_REQUEST;
      $label = array();
      $adicionales = array();
     
      $pct =AutoCarga::factory('Pacientes','','app','DatosPaciente');
			$paciente = $pct->ObtenerDatosPaciente($request['tipo_id_paciente'],$request['paciente_id']);
			
      $datos = array();
      $datos['paciente_id'] = $request['paciente_id'];
      $datos['tipo_id_paciente'] = $request['tipo_id_paciente'];
      
      if(empty($paciente))
        $action['aceptar'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','IngresarDatosPaciente',$datos);
      else
        $action['aceptar'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','ActualizarDatosPaciente',$datos);

      $afiliado =  SessionGetVar("DatosPaciente");
      
      if(empty($paciente)) 
      {
        $paciente = $afiliado;
      }
      else
      {
        $paciente['rango'] = $afiliado['rango'];
        $paciente['estamento_id']=  $afiliado['estamento_id'];
        $paciente['tipo_afiliado_id']=  $afiliado['tipo_afiliado_id'];
        $paciente['semanas_cotizadas']=  $afiliado['semanas_cotizadas'];
      }
      
      $obliga = $pct->ObtenerCamposObligatorios();
			
      $label['plan'] = $pct->ObtenerDatosPlanDescripcion($request['plan_id']);
			$label['tipo'] = $pct->ObtenerDescripcionId($request['tipo_id_paciente']);
      
      if(empty($paciente))
      {
        $paciente['zona_residencia'] = GetVarConfigAplication('DefaultZona');					
        $paciente['tipo_pais_id'] = GetVarConfigAplication('DefaultPais');
        $paciente['tipo_dpto_id'] = GetVarConfigAplication('DefaultDpto');
        $paciente['tipo_mpio_id'] = GetVarConfigAplication('DefaultMpio');
      }

      $label['nombre_pais'] = $pct->ObtenerNombrePais($paciente['tipo_pais_id']);
			$label['nombre_municipio'] = $pct->ObtenerNombreCiudad($paciente['tipo_pais_id'],$paciente['tipo_dpto_id'],$paciente['tipo_mpio_id']);
			$label['nombre_departamento'] = $pct->ObtenerNombreDepartamento($paciente['tipo_pais_id'],$paciente['tipo_dpto_id']);
      
      if($paciente['tipo_comuna_id'])
        $label['nombre_comuna'] = $pct->ObtenerNombreComuna($paciente['tipo_pais_id'],$paciente['tipo_dpto_id'],$paciente['tipo_mpio_id'],$paciente['tipo_comuna_id']);
			
      if($paciente['tipo_barrio_id'])
        $label['nombre_barrio'] = $pct->ObtenerNombreBarrio($paciente['tipo_pais_id'],$paciente['tipo_dpto_id'],$paciente['tipo_mpio_id'],$paciente['tipo_comuna_id'],$paciente['tipo_barrio_id']);              
      
			if($paciente['ocupacion_id'])
				$label['nombre_ocupacion'] = $pct->ObtenerNombreOcupacion($paciente['ocupacion_id']);
      
      $adicionales['tipos_sexo'] = $pct->ObtenerTiposSexo();
      $adicionales['unidad_peso'] = $pct->ObtenerUnidad('peso');
      $adicionales['unidad_talla'] = $pct->ObtenerUnidad('talla');	
      $adicionales['estado_civil'] = $pct->ObtenerEstadoCivil();
			$adicionales['zonas_residencia'] = $pct->ObtenerZonasResidencia();
			$adicionales['rangos'] = $pct->ObtenerRangosNiveles($request['plan_id']);
      $adicionales['tipos_pacientes'] = $pct->ObtenerTiposAfiliados($request['plan_id']);
      
      $action['cancelar'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','BusquedaCreacionSolicitudes');
      
      $html = AutoCarga::factory('SolicitudAutorizacionHTML','views','app','UV_SolicitudesAutorizaciones');
      $this->salida .= $html->FormaDatosPaciente($action,$request,$paciente,$obliga,$adicionales,$label);
      return true;
    }
    /**
    * Funcion de control para hacer el ingreso de los datos del paciente
    *
    * @return boolean
    */
    function IngresarDatosPaciente()
    {
      $request = $_REQUEST;

      $pct =AutoCarga::factory('Pacientes','','app','DatosPaciente');
      $rst = $pct->IngresarDatosPaciente($request);
      if(!$rst)
      {
        $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_SolicitudesAutorizaciones');
        $mensaje = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>".$pct->frmError['MensajeError'];
        $action['volver'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','BusquedaCreacionSolicitudes');
        
        $this->salida .= $html->FormaMensajeModulo($action,$mensaje);
      }
      else
      {
        $datos = array();
        $datos['plan_id'] = $request['plan_id'];
        $datos['paciente_id'] = $request['paciente_id'];
        $datos['tipo_id_paciente'] = $request['tipo_id_paciente'];
        $datos['rango'] = $request['rango'];
        $datos['estamento_id']=  $request['estamento_id'];
        $datos['tipo_afiliado_id']=  $request['tipo_afiliado_id'];
        $datos['semanas_cotizadas']=  $request['semanas_cotizadas'];
        
        $action['aceptar'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','RegistrarCargosMedicamentos',$datos);
      	$this->salida .= "<script>location.href = \"".$action['aceptar']."\"</script>\n";
      }
      return true;
    }
    /**
    * Funcion de control para hacer la actualizacion de los datos del paciente
    *
    * @return boolean
    */
    function ActualizarDatosPaciente()
    {
			$request = $_REQUEST;

      $pct =AutoCarga::factory('Pacientes','','app','DatosPaciente');
      $rst = $pct->ActualizarDatosPaciente($request);
      if(!$rst)
      {
        $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_SolicitudesAutorizaciones');
        $mensaje = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>".$pct->frmError['MensajeError'];
        $action['volver'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','BusquedaCreacionSolicitudes');
        
        $this->salida .= $html->FormaMensajeModulo($action,$mensaje);
      }
      else
      {
        $datos = array();
        $datos['plan_id'] = $request['plan_id'];
        $datos['paciente_id'] = $request['paciente_id'];
        $datos['tipo_id_paciente'] = $request['tipo_id_paciente'];
        $datos['rango'] = $request['rango'];
        $datos['estamento_id']=  $request['estamento_id'];
        $datos['tipo_afiliado_id']=  $request['tipo_afiliado_id'];
        $datos['semanas_cotizadas']=  $request['semanas_cotizadas'];
        
        $action['aceptar'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','RegistrarCargosMedicamentos',$datos);
        $this->salida .= "<script>location.href = \"".$action['aceptar']."\"</script>\n";
      }
      return true;
    }
    /**
    * Funcion de control para hacer la solicitud de cargos y medicamentos
    *
    * @return boolean
    */
    function RegistrarCargosMedicamentos()
    {
      $request = $_REQUEST;
      
      $datos = array();
      $datos['plan_id'] = $request['plan_id'];
      $datos['paciente_id'] = $request['paciente_id'];
      $datos['tipo_id_paciente'] = $request['tipo_id_paciente'];
      $datos['estamento_id'] = $request['estamento_id'];
      
			$this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
 			$this->SetXajax(array("BuscarCargos","BuscarMedicamentos","Adicionar","Eliminar","ValidarDatos"),"app_modules/UV_SolicitudesAutorizaciones/RemoteXajax/Solicitud.php");
      $html = AutoCarga::factory('SolicitudAutorizacionHTML','views','app','UV_SolicitudesAutorizaciones');
      
      $action['volver'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','BusquedaCreacionSolicitudes');
      $action['aceptar'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','IngresarSolicitud',$datos);
			
      $sm = AutoCarga::factory('SolicitudesAutorizacion','','app','UV_SolicitudesAutorizaciones');
      $grupos = $sm->ObtenerGruposTiposCargos();
      $conceptos = $sm->ObtenerTiposConceptos();
      $dias = ModulogetVar('app','UV_SolicitudesAutorizaciones','dias_vencimiento');
      $solicitudes = $sm->ObtenerSolicitudesPendientes($request,$dias);
      
      if($grupos === false || $conceptos === false || $solicitudes === false)
      {
        $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_SolicitudesAutorizaciones');
        $mensaje = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>".$sm->mensajeDeError;
        $this->salida .= $html->FormaMensajeModulo($action,$mensaje);
        return true;
      }
      
      $rst = $sm->ObtenerPlanes($request);
      $label = array();
      $label['plan_descripcion'] = $rst[0]['plan_descripcion'];
      $label['mensaje_plan'] = $rst[0]['mensaje_plan'];
      $label['rango'] = $request['rango'];
      $label['semanas_cotizadas'] = $request['semanas_cotizadas'];
      $label['estamento'] = $sm->ObtenerDescripcionEstamento($request['estamento_id']);
      $label['tipo_afiliado'] = $sm->ObtenerDescripcionTiposAfiliados($request['tipo_afiliado_id']);
      $label['paciente'] = $sm->ObtenerPaciente($request);

      $this->salida .= $html->FormaDatosSolicitud($action,$request,$grupos,$conceptos,$solicitudes,$label);
      return true;
    }
    /**
    * Funcion de control para hacer el ingreso de los datos de la solicitud
    *
    * @return boolean
    */
    function IngresarSolicitud()
    {
      $request = $_REQUEST;
      $cargos = SessionGetVar("CargosAdicionados");
      $conceptos = SessionGetVar("ConceptosAdicionados");
      $medicamentos = SessionGetVar("MedicamentosAdicionados");
    
      $sm = AutoCarga::factory('SolicitudesAutorizacion','','app','UV_SolicitudesAutorizaciones');
      $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_SolicitudesAutorizaciones');
      
      $cargo_qx = ModulogetVar('app','UV_SolicitudesAutorizaciones','grupo_tipo_cargo_qx');
      
      $rst = $sm->IngresarSolicitud($request,$cargos,$medicamentos,$conceptos,$cargo_qx);
      
      $datos = array();
      if(!$rst)
      {
        $mensaje = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>".$sm->mensajeDeError;
      }
      else
      {
        $mensaje = "LA SOLICITUD Nº ".$rst." SE REALIZO SATISFACTORIAMENTE";
      }
      
      $empresa = SessionGetVar("EmpresasSolicitudes");
      if(!$empresa['nivel_autorizador_id'])
        $action['volver'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','BusquedaCreacionSolicitudes');
      else
      {
        $dat = array();
        $dat['plan_id'] = $request['plan_id'];
        $dat['paciente_id'] = $request['paciente_id'];            
        $dat['tipo_id_paciente'] = $request['tipo_id_paciente'];            
        $dat['estamento_id'] = $request['estamento_id'];            
        $dat['numero_solicitud_orden'] = $rst;  
        $action['volver'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','AutorizarCargos',$dat);
      }
      
      SessionDelVar("CargosAdicionados");
      SessionDelVar("ConceptosAdicionados");
      SessionDelVar("MedicamentosAdicionados");
      
      $this->salida .= $html->FormaMensajeModulo($action,$mensaje);
      return true;
    }
    /**
    * Funcion de control para la seleccion de cargos
    *
    * @return boolean
    */
    function AutorizarCargos()
    {
      $request = $_REQUEST;
      
      $dat['plan_id'] = $request['plan_id'];
      $dat['paciente_id'] = $request['paciente_id'];            
      $dat['tipo_id_paciente'] = $request['tipo_id_paciente'];            
      $dat['numero_solicitud_orden'] = $request['numero_solicitud_orden'];    
      
      $this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
 			$this->SetXajax(array("SeleccionarItems","IngresarItemsSeleccionados","RegistrarCargos"),"app_modules/UV_SolicitudesAutorizaciones/RemoteXajax/Solicitud.php");
      
      $action['volver'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','BusquedaCreacionSolicitudes');
      $action['recargar'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','AutorizarCargos',$dat);
      $action['cargos'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','SeleccionarProveedor');
      $action['medicamentos'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','SeleccionarProveedorMedicamentos');
      $action['conceptos'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','SeleccionarProveedorConceptos');
      
      $sm = AutoCarga::factory('SolicitudesAutorizacion','','app','UV_SolicitudesAutorizaciones');
      $html = AutoCarga::factory('SolicitudAutorizacionHTML','views','app','UV_SolicitudesAutorizaciones');

      $inp = AutoCarga::factory('InformacionPacientes');
      $paciente = $inp->ValidarInformacion($request);
      SessionSetVar("NumeroSolicitudOrden",$request['numero_solicitud_orden']);
      SessionDelVar("CargosProveedores");
      SessionDelVar("MedicamentosProveedores");
      
      if(!is_array($paciente) && $paciente == 3 || $paciente['tipo_afiliado_id'] == "")
        $paciente = $sm->ObtenerDatosAfiliados($request);
      else if(is_numeric($paciente))
      {
        $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_SolicitudesAutorizaciones');
        $mensaje = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>".$inp->ObtenerClasificacionErrores($paciente);;
        $this->salida .= $html->FormaMensajeModulo($action,$mensaje);
        return true;
      }
      
      $rst = $sm->ObtenerPlanes($request);
      $label = array();
      $label['plan_descripcion'] = $rst[0]['plan_descripcion'];
      $label['mensaje_plan'] = $rst[0]['mensaje_plan'];
      $label['rango'] = $paciente['rango'];
      $label['semanas_cotizadas'] = $paciente['semanas_cotizadas'];
      $label['estamento'] = $sm->ObtenerDescripcionEstamento($paciente['estamento_id']);
      $label['tipo_afiliado'] = $sm->ObtenerDescripcionTiposAfiliados($paciente['tipo_afiliado_id']);
      $label['paciente'] = $paciente;
      
      $empresa = SessionGetVar("EmpresasSolicitudes");
      $paciente['plan_id'] = $request['plan_id'];
      SessionSetVar("DatosPacienteSolicitud",$paciente);
      
      $datos = array();
      $datos['plan_id'] = $request['plan_id'];
      $datos['paciente_id'] = $request['paciente_id'];
      $datos['estamento_id'] = $paciente['estamento_id'];
      $datos['tipo_id_paciente'] = $request['tipo_id_paciente'];
      $datos['numero_solicitud_orden'] = $request['numero_solicitud_orden'];
      $action['aceptar'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','RegistrarOrdenServicio',$datos);

      $dias = ModulogetVar('app','UV_SolicitudesAutorizaciones','dias_vencimiento');
      
      $solicitudes = $sm->ObtenerSolicitudesAutorizar($request,$dias,$empresa['nivel_autorizador_id']);
      $cirugia = $sm->ObtenerSolicitudesCargosQXPendientes($request,$dias,$empresa['nivel_autorizador_id']);
      $this->salida .= $html->FormaDatosSolicitudAutorizar($action,$request,$solicitudes,$label,$empresa['nivel_autorizador_id'],$cirugia);
      return true;
    }
    /**
    * Funcion de control pàra hacer la seleccion de proveedores de cargos
    *
    * @return boolean
    */
    function SeleccionarProveedor()
    {
      $request = $_REQUEST;
      $paciente = SessionGetVar("DatosPacienteSolicitud");
      $solicitud = SessionGetVar("NumeroSolicitudOrden");
      
      $prv = AutoCarga::factory('Proveedores','','app','UV_SolicitudesAutorizaciones');
      $proveedores = $prv->ObtenerProveedores($request['cargos']['cargo'],$request['grupo_tipo_cargo'],$paciente,$solicitud,$request['buscador'],$request['offset']);
      $equivalencias = SessionGetVar("Equivalencias");

      if(empty($proveedores) && empty($request['buscador'])) 
      {       
        $this->salida .= "<script>\n";
        foreach($request['cargos']['solicitud'] as $key => $dtl)
        {
          if(empty($equivalencias[$dtl][$key]))
            $this->salida .= "		window.opener.document.getElementsByName('cargos[".$dtl."][".$key."][cargo]')[0].checked = false;\n";
        }
        $this->salida .= "</script>\n";
        
        $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_SolicitudesAutorizaciones');
        $action['volver'] = "javascript:window.close()";
        $mensaje = "PARA LOS CARGOS SELECCIONADOS NO EXISTE PROVEEDOR ASOCIADO <br>".$prv->mensajeDeError;
        $this->salida .= $html->FormaMensajeModulo($action,$mensaje);
        return true;
      }
      
      SessionSetVar("Proveedores",$proveedores);
      $tiposdocumentos = $prv->ObtenerTipoIdTerceros();
      
      if(!SessionIsSetVar("CargosProveedores"))
        SessionSetVar("CargosProveedores",$request['cargos']['proveedor']);

      $rq = array();
      $rq['cargos'] = $request['cargos'];
      $rq['cantidad'] = $request['cantidad'];
      $rq['grupo_tipo_cargo'] = $request['grupo_tipo_cargo'];
      
      $action['buscador'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','SeleccionarProveedor',$rq);
      $rq['buscador'] = $request['buscador'];
      $action['paginador'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','SeleccionarProveedor',$rq);
      $action['proveedor'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','SeleccionarCargosProveedor',$rq);
      
      $html = AutoCarga::factory('ProveedoresHTML','views','app','UV_SolicitudesAutorizaciones');
      $this->salida .= $html->FormaMostrarProveedoresCargos($action,$tiposdocumentos,$request['buscador'],$proveedores,$request['cargos']['solicitud'],$equivalencias,$prv->conteo,$prv->pagina);
      
      return true;
    }
    /**
    * Funcion de control para la seleccion de cargos
    *
    * @return boolean
    */
    function SeleccionarCargosProveedor()
    {
      $request = $_REQUEST;
 			$this->SetXajax(array("AdicionarCargos","EliminarCargos"),"app_modules/UV_SolicitudesAutorizaciones/RemoteXajax/Proveedor.php");
      
      $paciente = SessionGetVar("DatosPacienteSolicitud");

      $prv = AutoCarga::factory('Proveedores','','app','UV_SolicitudesAutorizaciones');
      $html = AutoCarga::factory('ProveedoresHTML','views','app','UV_SolicitudesAutorizaciones');
      $solicitud = SessionGetVar("NumeroSolicitudOrden");
      
      $cargos = $prv->ObtenerCargosProveedores($request['cargos']['cargo'],$request['grupo_tipo_cargo'],$request['codigo_proveedor_id'],$paciente,$solicitud);
      $proveedor = SessionGetVar("Proveedores");
            
      $rq = array();
      $rq['grupo_tipo_cargo'] = $request['grupo_tipo_cargo'];
      $rq['codigo_proveedor_id'] = $request['codigo_proveedor_id'];
      
      $action['aceptar'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','RegistrarCargosSeleccionados',$rq);
      $rq['cargos'] = $request['cargos'];
      
      $action['volver'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','SeleccionarProveedor',$rq);
      
      $equivalencias = SessionGetVar("Equivalencias");
      $cargos_add = SessionGetVar("CargosProveedores");
      
      $this->salida .= $html->FormaMostrarCargos($action,$cargos,$proveedor[$request['codigo_proveedor_id']],$cargos_add[$request['codigo_proveedor_id']],$equivalencias,$request['cargos']['cantidad'],$request['cargos']['solicitud']);
      
      return true;
    }
    /**
    * Funcion de control para la seleccion de proveedores de medicamentos
    *
    * @return boolean
    */
    function SeleccionarProveedorMedicamentos()
    {
      $request = $_REQUEST;
            
      $prv = AutoCarga::factory('Proveedores','','app','UV_SolicitudesAutorizaciones');
      $proveedores = $prv->ObtenerProveedoresMedicamentos($request['productos']['producto'],$request['buscador'],$request['buscador'],$request['offset']);
      
      if(empty($proveedores) && empty($request['buscador'])) 
      {
        $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_SolicitudesAutorizaciones');
        
        $this->salida .= "<script>\n";
        foreach($request['productos']['producto'] as $key => $dtl)
        {
          if(empty($equivalencias[$key]))
            $this->salida .= "		window.opener.document.getElementsByName('medicamento[".$key."][producto]')[0].checked = false;\n";
        }
        $this->salida .= "</script>\n";
        
        $action['volver'] = "javascript:window.close()";
        $mensaje = "PARA LOS MEDICAMENTOS SELECCIONADOS NO EXISTE PROVEEDOR ASOCIADO";
        $this->salida .= $html->FormaMensajeModulo($action,$mensaje);
        return true;
      }
      
      SessionSetVar("Proveedores",$proveedores);
      $tiposdocumentos = $prv->ObtenerTipoIdTerceros();
      
      if(!SessionIsSetVar("MedicamentosProveedores"))
        SessionSetVar("MedicamentosProveedores",$request['productos']['proveedor']);

      $rq = array();
      $rq['productos'] = $request['productos'];
      
      $action['buscador'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','SeleccionarProveedorMedicamentos',$rq);
      $rq['buscador'] = $request['buscador'];
      $action['paginador'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','SeleccionarProveedorMedicamentos',$rq);
      $action['proveedor'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','SeleccionarMedicamentosProveedor',$rq);
      
      $html = AutoCarga::factory('ProveedoresHTML','views','app','UV_SolicitudesAutorizaciones');
      $this->salida .= $html->FormaMostrarProveedoresMedicamentos($action,$tiposdocumentos,$request['buscador'],$proveedores,$request['productos']['producto'],$prv->conteo,$prv->pagina);
      
      return true;
    }
    /**
    * Funcion de control para hacer la seleccion de medicamentos
    *
    * @return boolean
    */
    function SeleccionarMedicamentosProveedor()
    {
      $request = $_REQUEST;
 			$this->SetXajax(array("AdicionarMedicamentos","EliminarMedicamentos"),"app_modules/UV_SolicitudesAutorizaciones/RemoteXajax/Proveedor.php");

      $prv = AutoCarga::factory('Proveedores','','app','UV_SolicitudesAutorizaciones');
      $html = AutoCarga::factory('ProveedoresHTML','views','app','UV_SolicitudesAutorizaciones');
      
      $medicamentos = $prv->ObtenerMedicamentosProveedores($request['productos']['producto'],$request['codigo_proveedor_id']);
      $proveedor = SessionGetVar("Proveedores");
            
      $rq = array();
      $rq['codigo_proveedor_id'] = $request['codigo_proveedor_id'];      
      $rq['productos'] = $request['productos'];
      
      $action['volver'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','SeleccionarProveedorMedicamentos',$rq);
      
      $medica_add = SessionGetVar("MedicamentosProveedores");
      $seleccionados = SessionGetVar("Medicamentos");
      
      $this->salida .= $html->FormaMostrarMedicamentos($action,$medicamentos,$proveedor[$request['codigo_proveedor_id']],$medica_add[$request['codigo_proveedor_id']],$seleccionados);
      
      return true;
    }
    /**
    * Funcion de control para la seleccion de proveedores de conceptos
    *
    * @return boolean
    */
    function SeleccionarProveedorConceptos()
    {
      $request = $_REQUEST;
      
      $prv = AutoCarga::factory('Proveedores','','app','UV_SolicitudesAutorizaciones');
      $proveedores = $prv->ObtenerProveedoresConceptos($request['buscador'],$request['offset']);
          
      $action['buscador'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','SeleccionarProveedorConceptos');
      $action['paginador'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','SeleccionarProveedorConceptos',array("buscador"=>$request['buscador']));
      
      $tiposdocumentos = $prv->ObtenerTipoIdTerceros();
      $html = AutoCarga::factory('ProveedoresHTML','views','app','UV_SolicitudesAutorizaciones');
      $this->salida .= $html->FormaMostrarProveedoresConceptos($action,$tiposdocumentos,$request['buscador'],$proveedores,$request['conceptos']['concepto'],$request['conceptos']['proveedor'],$prv->conteo,$prv->pagina);
      
      return true;
    }
    /**
    * Funcion de control para hacer el registro de las ordenes de servicio
    *
    * @return boolean
    */
    function RegistrarOrdenServicio()
    {
      $request = $_REQUEST;
      $medica_add = SessionGetVar("Medicamentos");
      $cargos_add = SessionGetVar("Equivalencias");
      
      $os = AutoCarga::factory('Ordenes','','app','UV_SolicitudesAutorizaciones');
      $empresa = SessionGetVar("EmpresasSolicitudes");
      $dias = ModuloGetVar('app','UV_SolicitudesAutorizaciones','dias_vencimiento_os');
      $rst = $os->CrearOdenServicio($request,$empresa['empresa'],$dias,$cargos_add,$medica_add);
      
      if($rst === false) 
      {
        $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_SolicitudesAutorizaciones');
        $action['volver'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','BusquedaCreacionSolicitudes');
        $mensaje = "ERROR: ".$os->mensajeDeError;
        $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      }
      else
        $this->MostrarOrdenServicio($rst);
      return true;
    }
    /**
    * Funcion de control para visualizar el buscador de ordenes de servicio
    *
    * @return boolean
    */
    function ListadoOrdenesServicio()
    {
      $request = $_REQUEST;
      
      $action['volver'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','Menu');

      $slc = AutoCarga::factory('SolicitudesAutorizacion','','app','UV_SolicitudesAutorizaciones');

      $empresa = SessionGetVar("EmpresasSolicitudes");
      $tiposdocumentos = $slc->ObtenerTiposIdentificacion();
      $proveedores = $slc->ObtenerProveedores($empresa['empresa']);
      
      $html = AutoCarga::factory('ListaSolicitudesHTML','views','app','UV_SolicitudesAutorizaciones');
      $conteo = $pagina = 0;
      if(!empty($request['buscador']))
      {
        $ods = AutoCarga::factory('Ordenes','','app','UV_SolicitudesAutorizaciones');
  
        $listado = $ods->ObtenerOrdenesServicio($empresa['empresa'],$request['buscador'],$request['offset']);
        
        $action['buscador'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','ListadoOrdenesServicio');
        $action['ver'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','MostrarOrdenServicio');
        $action['paginador'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','ListadoOrdenesServicio',array("buscador"=>$request['buscador']));

        $conteo = $ods->conteo;
        $pagina = $ods->pagina;
      }
      $this->salida = $html->FormaListaOdenes($action,$proveedores,$tiposdocumentos,$request['buscador'],$listado,$conteo,$pagina);
      return true;
    }
    /**
    * Funcion de cotrol para mostrar las ordenes de servicio seleccionadas
    *
    * @param array $numero_orden Arreglo de datos con las ordenes de servicio creadas
    *
    * @return boolean
    */
    function MostrarOrdenServicio($numero_orden)
    {
      $request = $_REQUEST;

      $action['volver'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','ListadoOrdenesServicio');
      if(!empty($numero_orden))  
      {
        $action['volver'] = ModuloGetURL('app','UV_SolicitudesAutorizaciones','controller','BusquedaCreacionSolicitudes');
        $request['numeros_ordenes'] = $numero_orden;
      }
        
      $ods = AutoCarga::factory('Ordenes','','app','UV_SolicitudesAutorizaciones');
      
      $empresa = SessionGetVar("EmpresasSolicitudes");
      $ordenes = $ods->ObtenerOrdenesServicio($empresa['empresa'],$request);
      $detalle = $ods->ObtenerOrdenesServicioDetalle($request);
      
      $html = AutoCarga::factory('ListaSolicitudesHTML','views','app','UV_SolicitudesAutorizaciones');
      $this->salida .= $html->FormaMostarOrden($action,$ordenes,$detalle,$request['numeros_ordenes'],$empresa['empresa']);
      return true;
    }
  }
?>