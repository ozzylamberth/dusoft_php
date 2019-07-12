<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: app_ReclamacionServicios_controller.php,v 1.1 2009/06/03 10:53:24 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */
  /**
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */
  
  class app_ReclamacionServicios_controller extends classModulo
  {
    /**
    * Constructor de la clase
    */
    function app_ReclamacionServicios_controller(){}
  
    /**
    * Funcion principal del modulo
    * @return boolean
    */
    function Main()
    {
      $request = $_REQUEST;
      $reclamacion = AutoCarga::factory('ReclamacionServiciosSQL', '', 'app', 'ReclamacionServicios');
      $action['volver'] = ModuloGetURL('system', 'Menu');
      $permisos = $reclamacion->ObtenerPermisos();    
      
      $ttl_gral = "RECLAMACION DE SERVICIOS";
      $titulo[0] = 'EMPRESAS';
      $url[0] = 'app';
      $url[1] = 'ReclamacionServicios'; 
      $url[2] = 'controller';
      $url[3] = 'Menu'; 
      $url[4] = 'permiso_reclamacion'; 
      $this->salida = gui_theme_menu_acceso($ttl_gral, $titulo, $permisos, $url, $action['volver']);
      
      return true;
    }    
    /**
    * Funcion de control para el menu inicial
    */
    function Menu()
    {
      $request = $_REQUEST;
      $action['inconsistencias_ent_pago'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPaciente");
      $action['atencion_urgencias'] = ModuloGetURL("app", "ReclamacionServicios", "contoller", "BuscarPacienteAU");
      $action['autorizacion_servicios'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPacienteAS");
      $action['buscar'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarDocumentos");
      $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "Main");
      
      if($request['permiso_reclamacion'])
        SessionSetVar("PermisosReclamacion",$request['permiso_reclamacion']);
      
      $act = AutoCarga::factory("ReclamacionServiciosHTML", "views", "app", "ReclamacionServicios");
      $this->salida = $act->formaMenu($action);
      
      return true;      
    }
    /**
    * Funcion que permite buscar la informacion del paciente
    */
    function BuscarPaciente()
    {
      $this->SetXajax(array("SeleccionarIngreso"),"app_modules/ReclamacionServicios/RemoteXajax/EventosIngresos.php");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserEvent");
      $this->IncludeJS("CrossBrowserDrag");
    
      $request = $_REQUEST;
      
      $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "Menu");
      $action['buscar_paciente'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPaciente");
      $action['paginador'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPaciente", array("sw_oculto"=>$request['sw_oculto'], "tipoId"=>$request['tipoId'], "noId"=>$request['noId'], "nombre"=>$request['nombre'], "apellido"=>$request['apellido']));
      $action['det_ingresos'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "InconsisEntPago");      
      
      $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
      
      $tipos_id = $mdl->ConsultarTipoId();
            
      if($request['sw_oculto']=="consultar")
      {
        $datos_pac = $mdl->ConsultarPacienteFiltro($request, $_REQUEST['offset']);
        $causa_ingreso = "normal";
        $ingresos = $mdl->ConsIngresosFiltro($request['noId'], $request['tipoId']);
      }
      
      $act = AutoCarga::factory("ReclamacionServiciosHTML", "views", "app", "ReclamacionServicios");
      $this->salida = $act->formaBuscarPaciente($action, $tipos_id, $request, $datos_pac, $mdl->pagina, $mdl->conteo, $causa_ingreso, $ingresos);
      return true;
    }      
    /**
    * Funcion que permite registrar las inconsistencias en la informacion del paciente
    */
    function InconsisEntPago()
    {
      $this->SetXajax(array("MostrarFecha"), "app_modules/ReclamacionServicios/RemoteXajax/EventosIngresos.php");
      $request = $_REQUEST;
      
      $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPaciente");
      $action['ing_inconsistencias'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "IngresoInconsis");
      
      $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
      
      $form['tipo_id_paciente'] = $request['tipoId'];
      $form['paciente_id'] = $request['noId'];
      $form['plan_id'] = $request['plan_id'];       
      
      $inp = AutoCarga::factory('InformacionPacientes');
      $datos = $inp->ValidarInformacion($form);
      
      $paciente = $mdl->ConsultarPaciente($request['noId'], $request['tipoId']);
      
      if(is_array($datos) && !empty($datos))
      {
        $paciente['primer_apellido_u'] = $datos['primer_apellido'];
        $paciente['segundo_apellido_u'] = $datos['segundo_apellido'];  
        $paciente['primer_nombre_u'] = $datos['primer_nombre'];
        $paciente['segundo_nombre_u'] = $datos['segundo_nombre'];
        $paciente['fecha_nacimiento_u'] = $datos['fecha_nacimiento'];  
        $paciente['residencia_direccion_u'] = $datos['residencia_direccion'];  
        $paciente['residencia_telefono_u'] = $datos['residencia_telefono'];
        $paciente['tipo_dpto_id_u'] = $datos['tipo_dpto_id'];
        $paciente['tipo_mpio_id_u'] = $datos['tipo_mpio_id'];
        $paciente['departamento_u'] = $datos['departamento'];
        $paciente['municipio_u'] = $datos['municipio'];
      }
      
      $empresa = $mdl->ConsultarEmpresa($request['plan_id']);
      $tercero = $mdl->ConsultarTerceros($request['plan_id']);
      $tipos_id = $mdl->ConsultarTipoId();
      $usuario = $mdl->ConsultarUsuario();
      $coberturas = $mdl->ConsCoberturaSalud($request['ingreso']);
      $inconsistencias = $mdl->ConsTiposInconsistencias();
      
      $fecha = date("Y-m-d");
      //$fecha = "2009-06-27";
      $request['noForm'] = "1";      
      
      $act = AutoCarga::factory("ReclamacionServiciosHTML", "views", "app", "ReclamacionServicios");
      
      $this->salida  = $act->formaInconsisEntPago($action, $c, $fecha, $empresa, $tercero, $paciente, $request, $tipos_id, $usuario, $coberturas, $inconsistencias);
     
      return true;
    }
    /**
    * Funcion que permite almacenar las inconsistencias encontradas en la informacion de un 
    * paciente
    */
    function IngresoInconsis()
    {
      $request = $_REQUEST;
      
      $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
      $request['desc_inconsis'] = $mdl->ConsTiposInconsistFiltro($request['inconsistencia']);
      
      if($request['desc_tipo_id'])
        $request['desc_tipo_id'] = $mdl->ConsTipoIdFiltro($request['seltDoc']);
      
      $empresa = $mdl->ConsultarEmpresa($request['plan_id']);
      $tercero = $mdl->ConsultarTerceros($request['plan_id']);
      $paciente = $mdl->ConsultarPaciente($request['noId_u'], $request['tipoId_u']);
      $usuario = $mdl->ConsultarUsuario();
      $coberturas = $mdl->ConsCoberturaSalud($request['ingreso']); 
      
      $request = array_merge($request, $empresa);
      $request = array_merge($request, $tercero);
      $request = array_merge($request, $paciente);
      $request = array_merge($request, $usuario);
      $request = array_merge($request, $coberturas);
            
      $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPaciente");
	  
      $cons = $mdl->ConsultarConsecutivo($request['fecha'], $request['noForm']);
            
      if(empty($cons))
      { 
        $c = 1;
        $request['tipo_ing'] = 'nuevo';
      }else{
        $c = $cons[0]['consecutivo']+1;
        $request['tipo_ing'] = 'actualizado';
      }
                  
      if($request['tipo_ing']=='nuevo')
      {
        $ing_cons = $mdl->IngresarConsecutivo($c, $request['fecha'], $request['noForm']);
      }else{
        $ing_cons = $mdl->ActualizarConsecutivo($c, $request['fecha'], $request['noForm']);
      }
	  
      $request['consec'] = $c;
      
      $ing_incon = $mdl->IngresarInconsistencias($request);
        
      $act = AutoCarga::factory("ReclamacionServiciosHTML", "views", "app", "ReclamacionServicios");

      $mensaje = "EL INGRESO DEL FORMULARIO SE REALIZO CORRECTAMENTE ";
      $this->salida  = $act->formaMensajeInconsis($action, $mensaje, $RUTA,$request,"1");
            
      return true;
    }
    /**
    * Funcion que permite buscar la informacion del paciente
    */
    function BuscarPacienteAU()
    {
      $this->SetXajax(array("SeleccionarIngreso"),"app_modules/ReclamacionServicios/RemoteXajax/EventosIngresos.php");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserEvent");
      $this->IncludeJS("CrossBrowserDrag");
      
      $request = $_REQUEST;
      
      $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "Menu");
      $action['buscar_paciente'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPacienteAU");
      $action['paginador'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPacienteAU", array("sw_oculto"=>$request['sw_oculto'], "tipoId"=>$request['tipoId'], "noId"=>$request['noId'], "nombre"=>$request['nombre'], "apellido"=>$request['apellido']));
      $action['det_ingresos'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "AtencionUrgencias");      
      
      $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
      
      $tipos_id = $mdl->ConsultarTipoId();
            
      if($request['sw_oculto']=="consultar")
      {
        $datos_pac = $mdl->ConsultarPacienteFiltro($request, $_REQUEST['offset']);
        $causa_ingreso = "urgencias";
        $ingresos = $mdl->ConsIngresosUrgFiltro($request['noId'], $request['tipoId']);
      }
      
      
      
      $act = AutoCarga::factory("ReclamacionServiciosHTML", "views", "app", "ReclamacionServicios");
      $this->salida = $act->formaBuscarPaciente($action, $tipos_id, $request, $datos_pac, $mdl->pagina, $mdl->conteo, $causa_ingreso, $ingresos);
      return true;      
    }
    /**
    * Funcion que permite consultar la informacion de la atencion inicial de un paciente en
    * urgencias
    */
    function AtencionUrgencias()
    {
      $request = $_REQUEST;
     
      $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPacienteAU");
      $action['ing_aten_urg'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "IngresoAtencionUrgencias");
      
      $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
      
      $empresa = $mdl->ConsultarEmpresa($request['plan_id']);
      $tercero = $mdl->ConsultarTerceros($request['plan_id']);
      $paciente = $mdl->ConsultarPaciente($request['noId'], $request['tipoId']);
      $tipos_id = $mdl->ConsultarTipoId();
      $usuario = $mdl->ConsultarUsuario();
      $coberturas = $mdl->ConsCoberturaSalud($request['ingreso']);
      $orig_aten = $mdl->ConsultarCausaIng($request['ingreso']);
      $ing_urg = $mdl->ConsIngresoUrg($request['ingreso']);
      $niv_triages = $mdl->ConsultarTriageIng($request['ingreso']);
      $pac_rem = $mdl->ConsPacienteRemitido($request['ingreso']);
     
      $diagnosticos = $mdl->ConsultarDiagnosticos($request['ingreso']);
      $origenes = $mdl->ObtenerOrigenAtencion();
      $destino = $mdl->ObtenerDestinoPaciente($request['ingreso']);
      $datos=$mdl->ConsultarUltimaEvolucion($request['ingreso']);
      $evolucion_id=$datos[0]['evolucion'];
      $destino2=$mdl->ConsultarDestinoPaciente($evolucion_id,$request['ingreso']);
      
      $fecha = date("Y-m-d");
      
      $request['noForm'] = "2";           
      
      $act = AutoCarga::factory("ReclamacionServiciosHTML", "views", "app", "ReclamacionServicios");
      $this->salida = $act->formaAtencionUrgencias($action, $request, $empresa, $tercero, $paciente, $tipos_id, $usuario, $coberturas, $fecha, $orig_aten, $ing_urg, $niv_triages, $pac_rem, $diagnosticos,$origenes,$destino,$destino2);
      return true;
    }
    /**
    * Funcion que permite almacenar la informacion de la generacion del reporte de la 
    * atencion inicial de un paciente en urgencias
    */
    function IngresoAtencionUrgencias()
    {
      $request = $_REQUEST;
      
      $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
      
      $empresa = $mdl->ConsultarEmpresa($request['plan_id']);
      $tercero = $mdl->ConsultarTerceros($request['plan_id']);
      $paciente = $mdl->ConsultarPaciente($request['noId_u'], $request['tipoId_u']);
      $usuario = $mdl->ConsultarUsuario();
      $coberturas = $mdl->ConsCoberturaSalud($request['ingreso']);
      $orig_aten = $mdl->ConsultarCausaIng($request['ingreso']);
      $ing_urg = $mdl->ConsIngresoUrg($request['ingreso']);
      $niv_triages = $mdl->ConsultarTriageIng($request['ingreso']);
      $pac_rem = $mdl->ConsPacienteRemitido($request['ingreso']);
      $destino = $mdl->ObtenerDestinoPaciente($request['ingreso']);

      $request = array_merge($request, $empresa);
      $request = array_merge($request, $tercero);
      $request = array_merge($request, $paciente);
      $request = array_merge($request, $usuario);
      $request = array_merge($request, $coberturas);
      
      $request = array_merge($request, $orig_aten);
      $request = array_merge($request, $ing_urg);
      $request = array_merge($request, $pac_rem);
      $request = array_merge($request, $destino);
      $request = array_merge($request, $niv_triages);
      
      $cons = $mdl->ConsultarConsecutivo($request['fecha'], $request['noForm']);
      if(empty($cons))
      { 
        $c = 1;
        $request['tipo_ing'] = 'nuevo';
      }
      else
      {
        $c = $cons[0]['consecutivo']+1;
        $request['tipo_ing'] = 'actualizado';
      }
      
      $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPacienteAU");
      
      if($request['tipo_ing']=='nuevo')
        $ing_cons = $mdl->IngresarConsecutivo($c, $request['fecha'], $request['noForm']);
      else
        $ing_cons = $mdl->ActualizarConsecutivo($c, $request['fecha'], $request['noForm']);
      
      $request['consec'] = $c;
            
      $ing_aten_urg = $mdl->IngRepAtencionUrgencias($request);
      
      $act = AutoCarga::factory("ReclamacionServiciosHTML", "views", "app", "ReclamacionServicios");
      
      $mensaje = "EL INGRESO DEL FORMULARIO SE REALIZO CORRECTAMENTE ";           
      
      $this->salida = $act->formaMensajeInconsis($action, $mensaje, $RUTA,$request,"2");
      return true;
    }
    /**
    * Funcion que permite buscar la informacion del paciente
    */    
    function BuscarPacienteAS()
    {
      $this->SetXajax(array("SeleccionarIngreso"),"app_modules/ReclamacionServicios/RemoteXajax/EventosIngresos.php");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserEvent");
      $this->IncludeJS("CrossBrowserDrag");
    
      $request = $_REQUEST;
      $request['ing_pac'] = "SI";
      
      $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "Menu");
      $action['buscar_paciente'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPacienteAS");
      $action['paginador'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPacienteAS", array("sw_oculto"=>$request['sw_oculto'], "tipoId"=>$request['tipoId'], "noId"=>$request['noId'], "nombre"=>$request['nombre'], "apellido"=>$request['apellido']));
      $action['det_ingresos'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "OrdenServicio");
      $action['ing_paciente'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "IngresarPaciente");
	  
      $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
      
      $tipos_id = $mdl->ConsultarTipoId();
            
      if($request['sw_oculto']=="consultar")
      {
        $datos_pac = $mdl->ConsultarPacienteFiltro($request, $_REQUEST['offset']);
        $planes = $mdl->ConsultarPlanes();
        $ingresos = $mdl->ConsIngresosAutoriza($request['noId'], $request['tipoId']);
        $causa_ingreso = "autorizacion";
      }
      SessionDelVar("CargosAdicionados");
      $act = AutoCarga::factory("ReclamacionServiciosHTML", "views", "app", "ReclamacionServicios");
      $this->salida  = $act->formaBuscarPaciente($action, $tipos_id, $request, $datos_pac, $mdl->pagina, $mdl->conteo, $causa_ingreso, $ingresos, $planes);
     
      return true;
    }
    /**
    * Funcion que permite mostrar la forma de ingreso de la informacion del paciente
    */
    function IngresarPaciente()
    {
      $request = $_REQUEST;
     
      $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "SeleccionarCargos", array("tipo_id_paciente"=>$request['tipoId'], "plan_id"=>$request['plan_id']));
      $action['cancelar'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPacienteAS");
      
      $_REQUEST['tipo_id_paciente'] = $request['tipoId'];
      $_REQUEST['paciente_id'] = $request['noId'];
      $_REQUEST['datos_afiliacion'] = 1;
      
      
      $pct = $this->ReturnModuloExterno('app','DatosPaciente','user');
      
      $pct->SetActionVolver($action['volver']);
      $pct->FormaDatosPaciente($action);
    
      $this->SetJavaScripts("Ocupaciones");
      $this->salida = $pct->salida;
     // $this->salida .= "<pre>".print_r($request, true)."</pre>";
      return true;
    }
    /**
    * Funcion que permite mostrar la informacion de los cargos a relacionar con la solicitud de autorizacion de servicios
    */
    function SeleccionarCargos()
    {
      $this->SetXajax(array("BuscarCUPS", "RelacionarServicio", "AdicionarCargo", "EliminarCargo"), "app_modules/ReclamacionServicios/RemoteXajax/EventosIngresos.php","ISO-8859-1");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserEvent");
      $this->IncludeJS("CrossBrowserDrag");
      
      $request = $_REQUEST;
     
      
      $action['det_ingreso'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "SolicitudAutorizacionManual", array("tipoId"=>$request['tipo_id_paciente'], "noId"=>$request['paciente_id'], "plan_id"=>$request['plan_id']));
      $action['cons_cargo'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "SeleccionarCargos", array("tipo_id_paciente"=>$request['tipo_id_paciente'], "paciente_id"=>$request['paciente_id'], "plan_id"=>$request['plan_id']));
      if(!$request['sw_volver'])  
        $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "IngresarPaciente", array("tipoId"=>$request['tipo_id_paciente'], "noId"=>$request['paciente_id'], "plan_id"=>$request['plan_id']));
      else
        $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPacienteAS");
      
      $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
      $cargos = $mdl->ConsultarSolicitudCargos();
      $paciente = $mdl->ConsultarPaciente($request['paciente_id'], $request['tipo_id_paciente']);
      $deptos = $mdl->ConsultarDepartamentos();
      $origenes = $mdl->ObtenerOrigenAtencion();
      
      $act = AutoCarga::factory("ReclamacionServiciosHTML", "views", "app", "ReclamacionServicios");
      $this->salida  = $act->formaSeleccionarCargos($action, $request, $cargos, $paciente, $cups, $deptos,$origenes);
      
      return true;
    }
    /**
    * Funcion que permite mostrar la informacion de las ordenes de servicio y los cargos relacionados 
    * en el ingreso de un paciente
    */
    function OrdenServicio()
    {
      $request = $_REQUEST;
      $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
      
      $orden = $mdl->ConsultarOrdenServicio($request['ingreso']);
      $cargos = $mdl->ConsultarSolicitudCargos();
      $paciente = $mdl->ConsultarPaciente($request['noId'], $request['tipoId']);
      $origenes = $mdl->ObtenerOrigenAtencion();
      $request['tipo_sol'] = "coningreso";
      
      $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controler", "BuscarPacienteAS");
      $action['det_ingresos'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "SolicitudAutorizacionServ",array("ingreso"=>$request['ingreso'], "noId"=>$request['noId'], "tipoId"=>$request['tipoId'],"tipo_sol"=>$request['tipo_sol']));
      $action['det_ingreso_cargo'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "SolicitudAutorizacionManual", array("tipoId"=>$request['tipo_id_paciente'], "noId"=>$request['paciente_id'], "plan_id"=>$request['plan_id']));
      
      $act = AutoCarga::factory("ReclamacionServiciosHTML", "views", "app", "ReclamacionServicios");
        
      $this->salida  = $act->formaOrdenServicio($action, $orden, $request, $paciente, $cargos,$origenes);
      return true;
    }
    /**
    * Funcion que permite mostrar la informacion de la atencion y los servicios solicitados 
    * para el paciente
    */
    function SolicitudAutorizacionServ()
    {          
      $request = $_REQUEST;
    
      $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controler", "OrdenServicio", array("ingreso"=>$request['ingreso'],"noId"=>$request['noId'], "tipoId"=>$request['tipoId']));
      $action['ing_solic_autoriza'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "IngSolicitudAutorizacion");
      
      $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
      
      $empresa = $mdl->ConsultarEmpresa($request['plan_id']);
      $tercero = $mdl->ConsultarTerceros($request['plan_id']);
      $paciente = $mdl->ConsultarPaciente($request['noId'], $request['tipoId']);
      $coberturas = $mdl->ConsCoberturaSaludPlan($request['plan_id']);
      $orig_aten = $mdl->ConsultarCausaIng($request['ingreso']);
      $via_ing_cama = $mdl->ConsultarViaIngresoCama($request['ingreso']);
      $cargos_orden = $mdl->ConsultarCargosOrden($request['ingreso'], $request['usuario_id']);//
     
      $cadenaJusti=" ";
      $cant_cg = count($request['cargos']);
       
      for($i=0;$i<$cant_cg;$i++)
      {
         
          $solicitud= $mdl->ConsultarSolicitud_id_Cargo($request['cargos'][$i],$request['noId'],$request['tipoId']);
          $sol=$solicitud[0]['hc_os_solicitud_id'];
          $justXSolic = $mdl->GetJustificacion($sol);
          $jus=$justXSolic[0]['observacion'];
          $cadenaJusti=$cadenaJusti."  ". $jus;  
      }
      $diagnosticos = $mdl->ConsultarDiagnosticos($request['ingreso'], $request['usuario_id']);//
      $prof = $mdl->ConsTipoProfesFiltro($request['ingreso'], $request['usuario_id']);//
      $orig_aten = $mdl->ConsultarCausaIng($request['ingreso']);
      $origenes = $mdl->ObtenerOrigenAtencion();
      $fecha = date("Y-m-d");
      $anyo = date("Y");
      $request['noForm'] = "3";           
      $request['origen_atencion'] = $orig_aten['origen_atencion'];
      $act = AutoCarga::factory("ReclamacionServiciosHTML", "views", "app", "ReclamacionServicios");
            
      $this->salida  = $act->formaSolicitudAutorizacionServ($action, $fecha, $empresa, $tercero, $paciente, $request, $coberturas, $orig_aten, $via_ing_cama, $anyo, $cargos_orden, $diagnosticos, $prof,$origenes,$cadenaJusti);
    
     return true;
    }
    /**
    * Funcion que permite almacenar las solicitudes de autorizacion de servicios    
    */
    function IngSolicitudAutorizacion()
    {
      $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPacienteAS");
      
      $request = $_REQUEST;
      
      $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
      
      $empresa = $mdl->ConsultarEmpresa($request['plan_id']);
      $tercero = $mdl->ConsultarTerceros($request['plan_id']);
      $paciente = $mdl->ConsultarPaciente($request['noId_u'], $request['tipoId_u']);
      $coberturas = $mdl->ConsCoberturaSaludPlan($request['plan_id']);
      $orig_aten = $mdl->ConsultarCausaIng($request['ingreso']);
      $via_ing_cama = $mdl->ConsultarViaIngresoCama($request['ingreso']);
      $cargos_orden = $mdl->ConsultarCargosOrden($request['ingreso'], $request['usuario_id'],$request['cargos']);//
      $diagnosticos = $mdl->ConsultarDiagnosticos($request['ingreso'], $request['usuario_id']);
      $prof = $mdl->ConsTipoProfesFiltro($request['ingreso'], $request['usuario_id']);
      
      $request = array_merge($request, $empresa);
      $request = array_merge($request, $tercero);
      $request = array_merge($request, $paciente);
      $request = array_merge($request, $coberturas);
      $request = array_merge($request, $orig_aten);
      $request = array_merge($request, $via_ing_cama);
      $request = array_merge($request, $prof);
      
      $request['cargos'] = $cargos_orden;
      $request['diagnosticos'] = $diagnosticos;
	  
      $anyo = date("Y");
      $cons = $mdl->ConsultarConsecutivoAnyo($anyo, $request['noForm']);
      
      if(empty($cons))
      { 
        $c = 1;
        $request['tipo_ing'] = 'nuevo';
      }else{
        $c = $cons[0]['consecutivo']+1;
        $request['tipo_ing'] = 'actualizado';
      }
      
      $act = AutoCarga::factory("ReclamacionServiciosHTML", "views", "app", "ReclamacionServicios");      
      
      if($request['tipo_ing']=='nuevo')
      {
        $ing_cons = $mdl->IngresarConsecutivo($c, $request['fecha'], $request['noForm']);
      }else{
        $ing_cons = $mdl->ActualizarConsecutivoAnyo($c, $anyo, $request['noForm']);
      }
      
      $request['consec'] = $c;
      $num_sol = $mdl->IngSolicitudAutorizaServ($request);
    
      $mensaje = "EL INGRESO DEL FORMULARIO SE REALIZO CORRECTAMENTE ";
      
      $this->salida  = $act->formaMensajeInconsis($action, $mensaje, $RUTA,$request,"3");
      return true;
    }
    /**
    * Funcion que permite mostrar la informacion de la atencion y los servicios solicitados 
    * para el paciente
    */
    function SolicitudAutorizacionManual()
    {
      $request = $_REQUEST;
     
      $cargos = SessionGetVar("CargosAdicionados");
      if($request['sw_volver'])
        $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "SeleccionarCargos", array("tipo_id_paciente"=>$request['tipoId'], "paciente_id"=>$request['noId'], "plan_id"=>$request['plan_id'], "sw_volver"=>$request['sw_volver']));
      else
        $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "SeleccionarCargos", array("tipo_id_paciente"=>$request['tipoId'], "paciente_id"=>$request['noId'], "plan_id"=>$request['plan_id']));
      $action['ing_solicitud_manual'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "IngSolicitudAutorizacionManual");
      
      $request['noForm'] = "3"; 
      
      $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
      $empresa = $mdl->ConsultarEmpresaManual($request['plan_id']);
      $paciente = $mdl->ConsultarPaciente($request['noId'], $request['tipoId']);
      $tercero = $mdl->ConsultarTercerosManual($request['plan_id']);
      $cargo = $mdl->ConsSolicitudCargosFiltro($request['cargo']);
      $coberturas = $mdl->ConsCoberturaSaludPlan($request['plan_id']);
      $origenes = $mdl->ObtenerOrigenAtencion();
     
      $act = AutoCarga::factory("ReclamacionServiciosHTML", "views", "app", "ReclamacionServicios");
      
      $this->salida = $act->formaSolicitudAutorizacionManual($action, $request, $empresa, $paciente, $tercero, $cargo, $coberturas, $cargos,$origenes);
      return true;
    }
    /**
    * Funcion que permite almacenar las solicitudes de autorizacion de servicios realizadas manualmente
    */
    function IngSolicitudAutorizacionManual()
    {
      //IncludeFileModulo("reporte_AutorizacionServicios.inc", "reports/fpdf", "app", "ReclamacionServicios");
      $request = $_REQUEST;
     
      $cargos = SessionGetVar("CargosAdicionados");
      
      $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarPacienteAS");
      $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
      $empresa = $mdl->ConsultarEmpresaManual($request['plan_id']);
      $paciente = $mdl->ConsultarPaciente($request['noId_u'], $request['tipoId_u']);
      $tercero = $mdl->ConsultarTercerosManual($request['plan_id']);
      
      $coberturas = $mdl->ConsCoberturaSaludPlan($request['plan_id']);
      
      $request = array_merge($request, $empresa);
      $request = array_merge($request, $tercero);
      $request = array_merge($request, $paciente);
      $request = array_merge($request, array("cargos"=>$cargos));
      $request = array_merge($request, $coberturas);
      
      $request['solicitud'] = "manual"; 
      
      $act = AutoCarga::factory("ReclamacionServiciosHTML", "views", "app", "ReclamacionServicios");
      
      $anyo = date("Y");
      $cons = $mdl->ConsultarConsecutivoAnyo($anyo, $request['noForm']);
      
      if(empty($cons))
      {
        $c = 1;
        $request['tipo_ing'] = 'nuevo';
      }else{
        $c = $cons[0]['consecutivo']+1;
        $request['tipo_ing'] = 'actualizado';
      }     
      
      if($request['tipo_ing']=='nuevo')
      {
        $ing_cons = $mdl->IngresarConsecutivo($c, $request['fecha'], $request['noForm']);
      }else{
        $ing_cons = $mdl->ActualizarConsecutivoAnyo($c, $anyo, $request['noForm']);
      }
      
      $request['consec'] = $c;
      
      $num_sol = $mdl->IngSolicitudAutorizaServ($request);
    
      
      $mensaje = "EL INGRESO DEL FORMULARIO SE REALIZO CORRECTAMENTE ";
      
      $this->salida  = $act->formaMensajeInconsis($action, $mensaje, $RUTA,$request,"3");
      return true;
    }
    /**
    * Funcion que permite buscar los documentos ya realizados
    * Autorizaciones, inconsistencias y atenciuon urgencias
    *
    * @return boolean
    */
    function BuscarDocumentos()
    {    
      $request = $_REQUEST;
      $emp = SessionSetVar("PermisosReclamacion");
      
      $action['volver'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "Menu");
      $action['buscar'] = ModuloGetURL("app", "ReclamacionServicios", "controller", "BuscarDocumentos");
      
      $rcl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
      $mdl = AutoCarga::factory("ReclamacionServiciosHTML", "views", "app", "ReclamacionServicios");
      
      $tipos_id = $rcl->ConsultarTipoId();
      
      $this->salida = $mdl->FormaBuscarDocumentos($action, $tipos_id, $request);
      return true;
    }
  }
?>