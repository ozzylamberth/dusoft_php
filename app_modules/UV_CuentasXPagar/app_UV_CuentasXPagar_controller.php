<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_UV_CuentasXPagar_controller.php,v 1.3 2008/10/23 22:09:23 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Control: UV_CuentasXPagar
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class app_UV_CuentasXPagar_controller extends classModulo
  {
    /**
    * Constructor de la clase
    */
    function app_UV_CuentasXPagar_controller(){}
    /**
    * Funcion donde se validan las variables de modulo necesarias para la ejecucion del mismo
    *
    * @param array $datos Arreglo con los datos del request 
    *
    * @return mixed
    */
    function validarVariables($datos)
    {
      if(ModuloGetVar('app','UV_CuentasXPagar','documento_cxp') == "")
        return "PARA EL MODULO ".$datos['modulo']." LA VARIABLE documento_cxp NO EXISTE O NO CONTIENE NINGUN VALOR";
              
      if(ModuloGetVar('app','UV_CuentasXPagar','dias_gracia') == "")
        return "PARA EL MODULO ".$datos['modulo']."  LA VARIABLE dias_gracia NO EXISTE O NO CONTIENE NINGUN VALOR";
      
      if(ModuloGetVar('app','UV_CuentasXPagar','cxp_tipo_reintegro') == "")
        return "PARA EL MODULO ".$datos['modulo']."  LA VARIABLE cxp_tipo_reintegro NO EXISTE O NO CONTIENE NINGUN VALOR";
      //ModuloGetVar('app','UV_CuentasXPagar','lista_cargos');
      return 1;
    }
    /**
    * Funcion principal del modulo
    * 
    * @return boolean
    */
    function Main()
    {
      $request = $_REQUEST;
      $cxp = AutoCarga::factory('CuentasXPagar','','app','UV_CuentasXPagar');
      
      SessionDelVar("EmpresasCuentas");
      $action['volver'] = ModuloGetURL('system','Menu');
      
      if(($rst = $this->validarVariables($request)) != 1)
      {
        $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_CuentasXPagar');
        $this->salida = $html->FormaMensajeModulo($action,$rst);
        return true;
      }
      
      $permisos = $cxp->ObtenerPermisos();
      
      if(empty($permisos))
      {
        $mensaje = "SU USUARIO NO CUENTA CON PERMISOS PARA ACCEDER A ESTE MODULO";
        $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_CuentasXPagar');
        $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      }
      else
      {
        $ttl_gral = "CUENTAS POR PAGAR";
        $titulo[0]='EMPRESAS';
  			$url[0]='app';							
  			$url[1]='UV_CuentasXPagar';	
  			$url[2]='controller';	
  			$url[3]='Menu';				
  			$url[4]='permiso_cxp';
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
      if(!SessionIsSetVar("EmpresasCuentas"))
        SessionSetVar("EmpresasCuentas",$request['permiso_cxp']);
      
      $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','Main');
      $action['radicar'] = ModuloGetURL('app','UV_CuentasXPagar','controller','RadicarFactura');
      $action['revisar_admin'] = ModuloGetURL('app','UV_CuentasXPagar','controller','MostrarTercerosCxP',array("admin"=>1));
      $action['revisar_medic'] = ModuloGetURL('app','UV_CuentasXPagar','controller','MostrarTercerosCxP',array("admin"=>2));
      $action['gestion_pagos'] = ModuloGetURL('app','UV_CxPGestionPagos','controller','MenuGestionPagos');
      $action['estados'] = ModuloGetURL('app','UV_CxPEstados','controller','MenuGestionestados');
      $action['cxp'] = ModuloGetURL('app','UV_CuentasXPagar','controller','MostrarCuentasPorPagar');
      $action['cxp_reintegro'] = ModuloGetURL('app','UV_CuentasXPagar','controller','CuentasPorPagarReintegro');
      $action['cxp_radicacion'] = ModuloGetURL('app','UV_CuentasXPagar','controller','ListarRadicaciones');
      
      $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_CuentasXPagar');
      $this->salida = $html->FormaMenuInicial($action);
      return true;
    }
    /**
    * Funcion de control para la radicacion de las facturas 
    * 
    * @return boolean
    */
    function RadicarFactura()
    {
      $this->SetXajax(array("ObtenerEspecialidad","ObtenerServicio"),"app_modules/UV_CuentasXPagar/RemoteXajax/CuentasXPagar.php");

      $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','Menu');
      //$action['rips'] = ModuloGetURL('app','UV_CuentasXPagar','controller','IngresarRips');
      $action['manual'] = ModuloGetURL('app','UV_CuentasXPagar','controller','RadicacionManualFacturas');
      
      $cxp = AutoCarga::factory('CuentasXPagar','','app','UV_CuentasXPagar');
      $mdl = AutoCarga::factory('CuentasXPagarHTML','views','app','UV_CuentasXPagar');
      
      $tipos_cuentas = $cxp->ObtenerTiposCuentas();
      $medios_pago = $cxp->ObtenerMediosDePago();

      $auditores_medicos = $cxp->ObtenerAuditoresMedicos();
      $auditores_admin = $cxp->ObtenerAuditoresAdministrativos();
      
      $this->salida = $mdl->FormaRegistrarRadicacionFactura($action,$tipos_cuentas,$medios_pago,$auditores_medicos,$auditores_admin);
      return true;
    }
    /**
    * Funcion de control para el registro de la radicacion manualmente
    *
    * @return boolean
    */
    function RadicacionManualFacturas()
    {
      $request = $_REQUEST; 
      
      $datos = array();
      
      $datos['auditor'] = $request['auditor'];
      $datos['medio_pago'] = $request['medio_pago'];
      $datos['tipo_cuenta'] = $request['tipo_cuenta'];
      $datos['tipo_servicio'] = $request['tipo_servicio'];
      $datos['auditor_medico'] = $request['auditor_medico'];
      $datos['tipo_especialidad'] = $request['tipo_especialidad'];
      $datos['tipo_ingreso'] = $request['tipo_ingreso'];
      $datos['numero_digitos'] = $request['numero_digitos'];
      
      $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','RadicarFactura');
      $action['aceptar'] = ModuloGetURL('app','UV_CuentasXPagar','controller','IngresarRadicacion',$datos);
    
      $cxp = AutoCarga::factory('CuentasXPagar','','app','UV_CuentasXPagar');
      $mdl = AutoCarga::factory('CuentasXPagarHTML','views','app','UV_CuentasXPagar');
      
      $proveedor = $cxp->ObtenerProveedores();
      
      $this->salida = $mdl->FormaRegistrarRadicacionManual($action,$proveedor);
      return true;
    }
    /**
    * Funcion de control para el ingreso de la radicacion
    *
    * @return boolean
    */
    function IngresarRadicacion()
    {
      $request = $_REQUEST; 
      
      $rq = array();
      $rq["auditor"] = $request['auditor'];
      $rq["tipo_cuenta"] = $request['tipo_cuenta'];
      $rq["auditor_medico"] = $request['auditor_medico'];
      $rq["numero_digitos"] = $request['numero_digitos'];
      $empresa = SessionGetVar("EmpresasCuentas");
    
      if($request['auditor_medico'] == '-1') $$request['auditor_medico'] = "NULL";
    
      $rdm = AutoCarga::factory('RadicacionManual','','app','UV_CuentasXPagar');
      $radicacion = $rdm->IngresarRadicacion($request,$empresa['empresa']);
      
      $mensaje = "LA RADICACION SE REALIZO DE MANERA CORRECTA<br>NUMERO DE RADICACION: ".$radicacion;
      if(!$radicacion)
        $mensaje = "ERROR: ".$rdm->mensajeDeError;
      else
        $rq["radicacion_id"] = $radicacion;
      
      $rq["proveedor"] = $request['proveedor'];
      $rq['fecha_radicacion'] = $request['fecha_radicacion'];
      
      $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_CuentasXPagar');
      
      if($request['tipo_ingreso'] == '1')
        $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','RegistroFacturas',$rq);
      else
        $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','IngresarRips',$rq);
      
      $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      return true;
    }
    /**
    * Funcion de control para el registro de facturas
    *
    * @return boolean
    */
    function RegistroFacturas()
    {
      $request = $_REQUEST; 
      
      $rq = array();
      $rq["auditor"] = $request['auditor'];
      $rq["proveedor"] = $request['proveedor'];
      $rq["tipo_cuenta"] = $request['tipo_cuenta'];
      $rq["radicacion_id"] = $request['radicacion_id'];
      $rq["auditor_medico"] = $request['auditor_medico'];
      
      $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','RadicarFactura');
    
      $rq['fecha_final'] = $request['fecha_final'];
      $rq['fecha_inicial'] = $request['fecha_inicial'];
      $rq['fecha_radicacion'] = $request['fecha_radicacion'];
      $action['aceptar'] = ModuloGetURL('app','UV_CuentasXPagar','controller','IngresarFactura',$rq);
      
      $cxp = AutoCarga::factory('CuentasXPagar','','app','UV_CuentasXPagar');
      $mdl = AutoCarga::factory('CuentasXPagarHTML','views','app','UV_CuentasXPagar');
      
      $tipos_documentos = $cxp->ObtenerTiposIdentificacion();
      $planes = $cxp->ObtenerPlanes($request);
      $proveedor = $cxp->ObtenerProveedores($rq["proveedor"]);
      
      $this->salida = $mdl->FormaRegistrarFacturaManual($action,$request,$tipos_documentos,$planes,$proveedor[0]);
      return true;
    }
    /**
    * Funcion de control para el ingreso de la factura al sistema
    *
    * @return boolean
    */
    function IngresarFactura()
    {
      $request = $_REQUEST; 

      $rq = array();
      $rq["auditor"] = $request['auditor'];
      $rq["tipo_cuenta"] = $request['tipo_cuenta'];
      $rq["auditor_medico"] = $request['auditor_medico'];

      $action['volver1'] = ModuloGetURL('app','UV_CuentasXPagar','controller','RadicarFactura',$rq);
      
      $rq["proveedor"] = $request['proveedor'];
      $rq["radicacion_id"] = $request["radicacion_id"];
            
      if($request['auditor_medico'] == '-1') $request['auditor_medico'] = "NULL";
      
      $empresa = SessionGetVar("EmpresasCuentas");
      $documento = ModuloGetVar('app','UV_CuentasXPagar','documento_cxp');
      $dias_gracia = ModuloGetVar('app','UV_CuentasXPagar','dias_gracia');
      
      $rdm = AutoCarga::factory('RadicacionManual','','app','UV_CuentasXPagar');
      $rst = $rdm->IngresarFactura($request,$empresa['empresa'],$documento,$dias_gracia);
      
      $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_CuentasXPagar');
      $mensaje = "EL REGISTRO DE LA FACTURA SE REALIZO CORRECTAMENTE"; 
      if(!$rst) 
      {
        $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','RegistroFacturas',$rq);;
        $mensaje = "ERROR: ".$rdm->mensajeDeError;
        $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      }
      else
      {
        $rq['fecha_final'] = $rst['fecha_final'];
        $rq['fecha_inicial'] = $rst['fecha_inicial'];
        $rq['fecha_radicacion'] = $request['fecha_radicacion'];
        
        $action['volver2'] = ModuloGetURL('app','UV_CuentasXPagar','controller','RegistroFacturas',$rq);
        $this->salida = $html->FormaMensajeModuloAdicionar($action,$mensaje,"factura");
      }
      return true;
    }
    /**
    * Funcion de control para la recepcion de RIPS, archivo AC
    *
    * @return boolean
    */
    function IngresarRips()
    {
      $request = $_REQUEST; 
      
      $datos = array();
      $datos["auditor"] = $request['auditor'];
      $datos["tipo_cuenta"] = $request['tipo_cuenta'];
      $datos["numero_digitos"] = $request['numero_digitos'];
      $datos["auditor_medico"] = $request['auditor_medico'];
      $datos['medio_pago'] = $request['medio_pago'];
      $datos['tipo_servicio'] = $request['tipo_servicio'];
      $datos['tipo_especialidad'] = $request['tipo_especialidad'];
      $datos["radicacion_id"] = $request["radicacion_id"];
      $datos["listado"] = $request["listado"];
      
      if($request['listado'])
        $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','ListarRadicaciones');
      else
        $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','RadicarFactura');
      $action['aceptar'] = ModuloGetURL('app','UV_CuentasXPagar','controller','IngresarArchivosRips',$datos);
    
      $ing = AutoCarga::factory('IngresoRipsHTML','views','app','UV_CuentasXPagar');
      $this->salida = $ing->FormaRecepcionRips($action);
      return true;
    }
    /**
    * Funcion de control para la recepcion de los demas archivos RIPS
    *
    * @return boolean
    */
    function IngresarArchivosRips()
    {
      $request = $_REQUEST;
      
      $datos = array();
      $datos["auditor"] = $request['auditor'];
      $datos["tipo_cuenta"] = $request['tipo_cuenta'];
      $datos["numero_digitos"] = $request['numero_digitos'];
      $datos["auditor_medico"] = $request['auditor_medico'];
      $datos['medio_pago'] = $request['medio_pago'];
      $datos['tipo_servicio'] = $request['tipo_servicio'];
      $datos['tipo_especialidad'] = $request['tipo_especialidad'];
      $datos['radicacion_id'] = $request['radicacion_id'];
      $datos["listado"] = $request["listado"];

      if($request['listado'])
        $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','ListarRadicaciones');
      else
        $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','RadicarFactura');
      
      $action['aceptar'] = ModuloGetURL('app','UV_CuentasXPagar','controller','IngresarArchivosRipsContinuacion',$datos);
      $ing = AutoCarga::factory('IngresoRipsHTML','views','app','UV_CuentasXPagar');
      $ct = AutoCarga::factory('IngresoRips','','app','UV_CuentasXPagar');
      
      $rst = $ct->SubirArchivoControl($request);
      if(!$rst)
      {
        $mensaje = "ERROR: ".$ct->mensajeDeError;
        $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_CuentasXPagar');
        $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      }
      else
      {
        $idx = UserGetUID();
        SessionSetVar("Archivos_rips",$ct->archivos);
        SessionSetVar("Indices_Archivo",array($idx=>"".$ct->indice.""));
        SessionSetVar("NombresArchivos_rips",$ct->nombre_archivos);
        $this->salida = $ing->FormaRecepcionRipsContinuacion($action,$ct->archivos[$idx."_".$ct->indice]);
      }
      return true;
    }
    /**
    * Funcion de control para el ingreso de la informacion de los archivos del RIPS
    *
    * @return boolean
    */
    function IngresarArchivosRipsContinuacion()
    {
      $request = $_REQUEST;

      if($request['listado'])
        $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','ListarRadicaciones');
      else
        $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','RadicarFactura');

      if($request['listado'])
        $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','ListarRadicaciones');
      else
        $action['aceptar'] = ModuloGetURL('app','UV_CuentasXPagar','controller','RadicarFactura');
      
      $archivos = SessionGetVar("Archivos_rips");
      $indices = SessionGetVar("Indices_Archivo");
      $empresa = SessionGetVar("EmpresasCuentas");
      
      $ing = AutoCarga::factory('IngresoRips','','app','UV_CuentasXPagar');
      $ing->nombre_archivos = SessionGetVar("NombresArchivos_rips");
      
      $rst = $ing->SubirArchivosRips($archivos,$indices[UserGetUID()]);
      
      $mensaje = "LOS ARCHIVOS HAN SIDO PROCESADOS CORRECTAMENTE"; 
      if(!$rst)  
      {
        $mensaje = $ing->ObtenerError();
      }
      else
      { 
        $documento = ModuloGetVar('app','UV_CuentasXPagar','documento_cxp');
        $dias_gracia = ModuloGetVar('app','UV_CuentasXPagar','dias_gracia');
        $rst = $ing->SubirRegistrosRips($ing->nombre_archivos,$indices[UserGetUID()],$documento,$empresa['empresa'],$request,$dias_gracia);
        if(!$rst) $mensaje = $ing->ErrMsg();
      }
      
      $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_CuentasXPagar');
      $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      
      return true;
    }
    /**
    * Funcion de control para mostra el resumen por cliente de las cuentas por pagar
    *
    * @return boolean
    */
    function MostrarCuentasPorPagar()
    {
      $ing = AutoCarga::factory('CuentasXPagar','','app','UV_CuentasXPagar');
      $mdl = AutoCarga::factory('CuentasXPagarHTML','views','app','UV_CuentasXPagar');
      
      $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','Menu');

      $empresa = SessionGetVar("EmpresasCuentas");
      $datos = $ing->ObtenerCxP($empresa['empresa']);
      
      $this->salida .= $mdl->FormaMostrarCarteraClientes($action,$datos['cxp_cliente'],$datos['intervalos'],$datos['total']);
      return true;
    }    
    /**
    * Funcion de control para mostrar los terceros asociados a una cuenta por pagar
    *
    * @return boolean
    */
    function MostrarTercerosCxP()
    {
      $request = $_REQUEST;
      if($request['admin'] !== null) SessionSetVar("TipoRevision",$request['admin']);

      $cxp = AutoCarga::factory('CuentasXPagar','','app','UV_CuentasXPagar');
      $mdl = AutoCarga::factory('CuentasXPagarHTML','views','app','UV_CuentasXPagar');
      
      $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','Menu');
      $action['buscar'] = ModuloGetURL('app','UV_CuentasXPagar','controller','MostrarTercerosCxP');
      $action['paginador'] = ModuloGetURL('app','UV_CuentasXPagar','controller','MostrarTercerosCxP',array("buscador"=>$request['buscador']));
      $action['aceptar'] = ModuloGetURL('app','UV_CuentasXPagar','controller','MostarFacturasTercerosCxP');

      $empresa = SessionGetVar("EmpresasCuentas");
      $tiposdocumentos = $cxp->ObtenerTipoIdTerceros();
      
      if($request['buscador'])
        $terceros = $cxp->ObtenerTercerosCxP($empresa['empresa'],$request['buscador'],SessionGetVar("TipoRevision"));
      
      $this->salida .= $mdl->FormaMostrarTercerosCxP($action,$tiposdocumentos,$request['buscador'],$terceros,$cxp->conteo,$cxp->pagina);
      return true;
    }
    /**
    * Funcion de control para mostrar las facturas de un tercero en particular
    *
    * @return boolean
    */
    function MostarFacturasTercerosCxP()
    {
      $request = $_REQUEST;
            
      $this->SetXajax(array("SolicitarValidacion","IngresarValidacion"),"app_modules/UV_CuentasXPagar/RemoteXajax/CuentasXPagar.php");
     	$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
      
      $cxp = AutoCarga::factory('CuentasXPagar','','app','UV_CuentasXPagar');
      $mdl = AutoCarga::factory('CuentasXPagarHTML','views','app','UV_CuentasXPagar');
      
      $rq = array("tipo_id_tercero"=>$request['tipo_id_tercero'],"tercero_id"=>$request['tercero_id']);
      
      $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','MostrarTercerosCxP',array("buscador"=>$rq));
      $action['buscar'] = ModuloGetURL('app','UV_CuentasXPagar','controller','MostarFacturasTercerosCxP',$rq);
      $action['aceptar_rips'] = ModuloGetURL('app','UV_CuentasXPagar','controller','MostarDetalleFacturaRips',$rq);
      $action['aceptar_no_rips'] = ModuloGetURL('app','UV_CuentasXPagar','controller','MostarDetalleFacturaNORips',$rq);
      
      $rq['buscador'] = $request['buscador'];
      $action['paginador'] = ModuloGetURL('app','UV_CuentasXPagar','controller','MostarFacturasTercerosCxP',$rq);

      $empresa = SessionGetVar("EmpresasCuentas");
      $proveedor = $cxp->ObtenerTercerosCxP($empresa['empresa'],$request,SessionGetVar("TipoRevision"));
      $key = key($proveedor);
      $facturas = $cxp->ObtenerFacturasTercerosCxP($empresa['empresa'],$request,SessionGetVar("TipoRevision"));
      $this->salida .= $mdl->FormaInformacionFactura($action,$request['buscador'],$proveedor[$key],$facturas,$cxp->conteo,$cxp->pagina);
      return true;
    }
    /**
    * Funcion de control para mostrar la informacion de la factura cuando se ha 
    * ingresado por rips
    *
    * @return array
    */
    function MostarDetalleFacturaRips()
    {
      $request = $_REQUEST;
      $func = array("Objetar","ModificarObjecion","VincularDetalleM",
                    "DesvincularDetalleM","VincularDetalle","DesvincularDetalle",
                    "RegistrarObjeccion","RegistrarObjeccionT","DesvincularCXP",
                    "AsociarCXP","AsociarDetalleCargo","AsociarDetalleMedicamento",
                    "FinalizarRevision");

      $this->SetXajax($func,"app_modules/UV_CuentasXPagar/RemoteXajax/CuentasXPagar.php");
      $this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
      
      $cxp = AutoCarga::factory('CuentasXPagar','','app','UV_CuentasXPagar');
      $ods = AutoCarga::factory('Ordenes','','app','UV_CuentasXPagar');
      $mdl = AutoCarga::factory('CuentasXPagarHTML','views','app','UV_CuentasXPagar');
      
      $rq = array("tipo_id_tercero"=>$request['tipo_id_tercero'],"tercero_id"=>$request['tercero_id']);
      $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','MostarFacturasTercerosCxP',$rq);
      $action['revision'] = ModuloGetURL('app','UV_CuentasXPagar','controller','TerminarRevision',$rq);
      
      $empresa = SessionGetVar("EmpresasCuentas");
      $tipoauditor = SessionGetVar("TipoRevision");
      
      $paciente = $cxp->ObtenerPacientes($request);
      $datosp = $cxp->ObtenerTercerosCxP($empresa['empresa'],$request,$tipoauditor);
      $proveedor = $datosp[key($datosp)];
      
      $datosf = $cxp->ObtenerFacturasTercerosCxP($empresa['empresa'],$request,$tipoauditor);
      $factura = $datosf[key($datosf)];
      $cxp->ActualizarEstadoDocumento($factura,$tipoauditor);
      $request['codigo_proveedor'] = $factura['codigo_proveedor_id'];

      $validacion = $cxp->ObtenerValidacionRips($request['codigo_proveedor']);
      $otros = $cxp->ObtenerOtrosServiciosFactura($request,$empresa['empresa']);
      $medic = $cxp->ObtenerMedicamentosFactura($request,$empresa['empresa']);
      $cargo = $cxp->ObtenerCargosFactura($request,$empresa['empresa'],$validacion);
      $glosa = $cxp->ObtenerInformacionGlosa($factura);
      $historico = $cxp->ObtenerHistoricoEstados($factura);
      
      $detalle = array();
      $ordenes = $ods->ObtenerOrdenesServicio($empresa['empresa'],$request,$factura['fecha_documento']);
      $num_or = "";
      foreach($ordenes as $key => $dtl)
        ($num_or == "")? $num_or = $key : $num_or .= ",".$key;
      
      if($num_or != "")
        $detalle = $ods->ObtenerOrdenesServicioDetalle($request,$num_or,$factura);
      
      $this->salida .= $mdl->FormaDetalleCxP($action,$proveedor,$paciente,$cargo,$medic,$otros,$factura,$ordenes,$detalle,$glosa,$historico);
      return true;
    }
    /**
    * Funcion de control para mostrar la informacion de la factura cuando no ha sido 
    * ingresada por rips
    *
    * @return array
    */
    function MostarDetalleFacturaNORips()
    {
      $request = $_REQUEST;
      $func = array("AgregarCargo","AgregarMedicamento","FormaAdicionarCargo",
                    "FormaAdicionarMedic","FormaAdicionarOtro","AdicionarDetalleCxP",
                    "Eliminar","Objetar","RegistrarObjeccion","RegistrarObjeccionT",
                    "AsociarCXP","DesvincularCXP","AsociarDetalleCargo","VincularDetalle",
                    "DesvincularDetalle","AsociarDetalleMedicamento","VincularDetalleM",
                    "DesvincularDetalleM","FinalizarRevision","ModificarObjecion");

      $this->SetXajax($func,"app_modules/UV_CuentasXPagar/RemoteXajax/CuentasXPagarManual.php");
      $this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
      
      $cxp = AutoCarga::factory('CuentasXPagar','','app','UV_CuentasXPagar');
      $cxm = AutoCarga::factory('CuentasXPagarManual','','app','UV_CuentasXPagar');
      $ods = AutoCarga::factory('Ordenes','','app','UV_CuentasXPagar');
      $mdl = AutoCarga::factory('CuentasXPagarManualHTML','views','app','UV_CuentasXPagar');
      
      $rq = array("tipo_id_tercero"=>$request['tipo_id_tercero'],"tercero_id"=>$request['tercero_id']);
      $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','MostarFacturasTercerosCxP',$rq);
      $action['revision'] = ModuloGetURL('app','UV_CuentasXPagar','controller','TerminarRevision',$rq);

      $empresa = SessionGetVar("EmpresasCuentas");
      $tipoauditor = SessionGetVar("TipoRevision");
      
      $datosp = $cxp->ObtenerTercerosCxP($empresa['empresa'],$request,SessionGetVar("TipoRevision"));
      $datosf = $cxp->ObtenerFacturasTercerosCxP($empresa['empresa'],$request,SessionGetVar("TipoRevision"));
      $paciente = $cxp->ObtenerPacientes($request);
      
      $proveedor = $datosp[key($datosp)];
      $factura = $datosf[key($datosf)];
      $cxp->ActualizarEstadoDocumento($factura,$tipoauditor);
      $request['codigo_proveedor'] = $factura['codigo_proveedor_id'];
            
      $detalle = array();
      $ordenes = $ods->ObtenerOrdenesServicio($empresa['empresa'],$request,$factura['fecha_documento']);
      $num_or = "";
      foreach($ordenes as $key => $dtl)
        ($num_or == "")? $num_or = $key : $num_or .= ",".$key;
      
      if($num_or != "")
        $detalle = $ods->ObtenerOrdenesServicioDetalle($request,$num_or,$factura);
      
      $cargo = $cxm->ObtenerCargosFactura($request,$empresa['empresa']);
      $medic = $cxm->ObtenerMedicamentosFactura($request,$empresa['empresa']);
      $otros = $cxm->ObtenerOtrosServiciosFactura($request,$empresa['empresa']);
      $glosa = $cxp->ObtenerInformacionGlosa($factura);
      $historico = $cxp->ObtenerHistoricoEstados($factura);
      
      $this->salida .= $mdl->FormaDetalleCxP($action,$proveedor,$paciente,$factura,$cargo,$medic,$otros,$ordenes,$detalle,$glosa,$historico);
      return true;
    }
    /**
    *
    */
    function TerminarRevision()
    {
      $request = $_REQUEST;
      $rq = array("tipo_id_tercero"=>$request['tipo_id_tercero'],"tercero_id"=>$request['tercero_id']);
      $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','MostarFacturasTercerosCxP',$rq);

      $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_CuentasXPagar');
      $cxp = AutoCarga::factory('CuentasXPagar','','app','UV_CuentasXPagar');
      
      $empresa = SessionGetVar("EmpresasCuentas");
      $tipoauditor = SessionGetVar("TipoRevision");
      
      $estado = "";
      if($tipoauditor == 1)
        $estado = "RD";
      else if ($tipoauditor == 2)
        $estado = "RP";
      
      $rst = $cxp->ActualizarDocumento($request['prefijo'],$request['numero'],$empresa['empresa'],$estado);
      $mensaje = "LA FINALIZACION DE REVISION DEL DOCUMENTO HA SIDO REGISTRADO"; 
      if($rst === false) 
      {
        $mensaje = "ERROR: ".$cxp->mensajeDeError;
      }
      $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      return true;
    }    
    /**
    *
    */
    function CuentasPorPagarReintegro()
    {
      $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','Menu');
      $action['aceptar'] = ModuloGetURL('app','UV_CuentasXPagar','controller','IngresarCxPReintegro');
      
      $func = array("BuscarFamiliar","BuscarAfiliado","AsiganarAfiliado","AsignarFamiliar","SeleccionaraOcupacion","AsignarOcupacion","ValidarForma","BuscarTercero","AsignarTercero");
      $this->SetXajax($func,"app_modules/UV_CuentasXPagar/RemoteXajax/Reintegros.php");
      $this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
      
      $html = AutoCarga::factory('ReintegrosHTML','views','app','UV_CuentasXPagar');
      $mdl = AutoCarga::factory('Reintegros','','app','UV_CuentasXPagar');
      $cxp = AutoCarga::factory('CuentasXPagar','','app','UV_CuentasXPagar');
      
      $empresa = SessionGetVar("EmpresasCuentas");
      $conceptos = $mdl->ObtenerConceptosReintegro();
      $dependencia = $mdl->ObtenerDependenciasUV();
      $auditores = $cxp->ObtenerAuditoresAdministrativos();

      $this->salida = $html->FormadatosReintegro($action,$conceptos,$dependencia,$auditores);
      return true;
    }
    /**
    *
    */
    function IngresarCxPReintegro()
    {
      $request = $_REQUEST;

      $html = AutoCarga::factory('MensajesModuloHTML','views','app','UV_CuentasXPagar');
      $mdl = AutoCarga::factory('Reintegros','','app','UV_CuentasXPagar');
      $empresa = SessionGetVar("EmpresasCuentas");
      $dias_gracia = ModuloGetVar('app','UV_CuentasXPagar','dias_gracia');
      $cxp_tipo_reintegro = ModuloGetVar('app','UV_CuentasXPagar','cxp_tipo_reintegro');
      $documento = ModuloGetVar('app','UV_CuentasXPagar','documento_cxp');
      
      $rst = $mdl->IngresarRadicacion($request,$dias_gracia,$documento,$cxp_tipo_reintegro,$empresa['empresa']);
      
      if($rst === false) 
      {
        $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','CuentasPorPagarReintegro');
        $mensaje = "ERROR: ".$mdl->mensajeDeError;
      }
      else
      {
        $mensaje = "LA RADICACION DE LA CUENTA DE REINTEGRO SE REALIZO CORRECTAMENTE, Nº RADICACION: ".$rst['cxp_radicacion_id'].", Nº RADICACION REINTEGRO ".$rst['cxp_reintegro_id'].""; 
        $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','CuerpoFacturaReintegro',$rst);
      }
      
      $this->salida = $html->FormaMensajeModulo($action,$mensaje);
      return true;
    }
    /**
    *
    */
    function CuerpoFacturaReintegro()
    {
      $request = $_REQUEST;
      
      $cxp = AutoCarga::factory('CuentasXPagar','','app','UV_CuentasXPagar');
      
      $datosp = array();
      $empresa = SessionGetVar("EmpresasCuentas");
      
      if($request['codigo_proveedor'])
        $datosp = $cxp->ObtenerTercerosCxP($empresa['empresa'],$request,null,2);
      
      $datosf = $cxp->ObtenerFacturasTercerosCxP($empresa['empresa'],$request,null,2);
      $factura = $datosf[key($datos_f)];
      $paciente = $cxp->ObtenerPacientes($request);
      
      $lista_cargos  = $cxp->ObtenerListaPreciosComparacion($request['codigo_proveedor'],$factura['numero_contrato'],$factura['fecha_documento']);
      if(!$lista_cargos) $lista_cargos['lista_codigo'] = ModuloGetVar('app','UV_CuentasXPagar','lista_cargos');
      
      $cargo = $cxp->ObtenerCargosFactura($request,$empresa['empresa']);
      $medic = $cxp->ObtenerMedicamentosFactura($request,$empresa['empresa']);
      $otros = $cxp->ObtenerOtrosServiciosFactura($request,$empresa['empresa']);
      
      return true;
    }
    /**
    *
    */
    function ListarRadicaciones()
    {
      $request = $_REQUEST;
      $cxp = AutoCarga::factory('CuentasXPagar','','app','UV_CuentasXPagar');
      $ltd = AutoCarga::factory('Listados','','app','UV_CuentasXPagar');
      $mdl = AutoCarga::factory('ListadosHTML','views','app','UV_CuentasXPagar');
      
      $buscador = array();
      $buscador['cxp_radicacion_id'] = $request['cxp_radicacion_id']; 
      $buscador['tipo_id_tercero'] = $request['tipo_id_tercero'];
      $buscador['tercero_id'] = $request['tercero_id'];
      $buscador['nombre_tercero'] = $request['nombre_tercero']; 
      $buscador['fecha_radicacion'] = $request['fecha_radicacion']; 
      $buscador['fecha_registro'] = $request['fecha_registro'];
      $buscador['buscar'] = $request['buscar'];
      
      $empresa = SessionGetVar("EmpresasCuentas");
      
      $msgError = "";
      $lista = array();
      $pagina = $conteo = 0;
      
      if($request['buscar'])
      {
        $action['rips'] = ModuloGetURL('app','UV_CuentasXPagar','controller','IngresarRips',array("listado"=>"1"));
        $lista = $ltd->ObtenerListadoRadicacion($empresa['empresa'],$request);
        $conteo = $ltd->conteo;
        $pagina = $ltd->pagina;
        $msgError = $ltd->ErrMsg();
      }
      
      $tiposdocumentos = $cxp->ObtenerTipoIdTerceros();
      
      $action['volver'] = ModuloGetURL('app','UV_CuentasXPagar','controller','Menu');
      $action['buscar'] = ModuloGetURL('app','UV_CuentasXPagar','controller','ListarRadicaciones');
      $action['paginador'] = ModuloGetURL('app','UV_CuentasXPagar','controller','ListarRadicaciones',$buscador);
     
      $this->salida .= $mdl->FormaListadoRadicaciones($action,$tiposdocumentos,$buscador,$lista,$pagina,$conteo,$msgError);
      return true;
    }
  }
?>