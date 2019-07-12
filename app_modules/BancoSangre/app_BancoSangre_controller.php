<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: app_BancoSangre_controller.php,v 1.1 2009/01/09 14:26:46 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */
  /**
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */
  
  class app_BancoSangre_controller extends classModulo
  {
    /**
    * Constructor de la clase
    */
    function app_BancoSangre_controller(){}
    
    /**
    * Funcion principal del modulo
    * @return boolean
    */
    function Main()
    {
      $request = $_REQUEST;
      $banco = AutoCarga::factory('BancoSangreSQL', '', 'app', 'BancoSangre');
      $action['volver'] = ModuloGetURL('system', 'Menu');
      $permisos = $banco->ObtenerPermisos();
      
      $ttl_gral = "BANCO DE SANGRE";
      $titulo[0] = 'EMPRESAS';
      $url[0] = 'app';
      $url[1] = 'BancoSangre'; 
      $url[2] = 'controller';
      $url[3] = 'Menu'; 
      $url[4] = 'permiso_banco'; 
      $this->salida = gui_theme_menu_acceso($ttl_gral, $titulo, $permisos, $url, $action['volver']);
      
      return true;
      
    }
    
    /**
    * Funcion de control para el menu inicial
    *
    * @return boolean
    */
    function Menu()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'main');
      $action['ficha_donante'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'FichaDonante');
      $action['registrar_donaciones'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'RegistrarDonaciones');
      $action['consulta_donantes'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'ConsultaDonantes'); 
      $action['fraccionamiento_sangre'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'FraccionamientoSangre');
      $action['consulta_frac_sangre'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'ConsultaFracSangre');
      $action['hemocomponentes_ob'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'HemocomponentesOB');
      $act = AutoCarga::factory("BancoSangreHTML", "views", "app", "BancoSangre");      
      $this->salida = $act->formaMenu($action);
      
      return true;
    }
    /**
    * Funcion que permite ingresar la informacion del donante
    */
    function FichaDonante()
    {
      $this->SetXajax(array("BuscarClasiFinanciera", "BuscarGrado", "CalcEdad", "ValidarCedula", "BuscarDatosPaciente"), "app_modules/BancoSangre/RemoteXajax/ClasiFinanciera.php");
      $action['registrar_donante'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'RegistrarDonante');
      $action['volver_menu'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'Menu');
      $request = $_REQUEST;
      
      $mdl = AutoCarga::factory('BancoSangreSQL', '', 'app', 'BancoSangre');
      $tipos_donante = $mdl->ConsultarTiposDonante();
      $convenios = $mdl->ConsultarConvenios();
      $tipos_id = $mdl->ConsultarTipoId();
      
      $act = AutoCarga::factory("BancoSangreHTML", "views", "app", "BancoSangre");
      $this->salida = $act->formaFichaDonante($action, $tipos_donante, $convenios, $tipos_id);
      
      return true;
    }
    /**
    * Funcion que permite ingresar la informacion de los signos vitales y la tipificacion
    * del donante
    */
    
    function RegistrarDonante()
    {
      $request = $_REQUEST;
      
      $action['volver_ficha'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'Menu');
      $action['registrar_signos'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'IngresarSignosTipificacion');
      
      $mdl = AutoCarga::factory("BancoSangreSQL", "", "app", "BancoSangre");
      $grupos_sang = $mdl->ConsultarGrupoSanguineo();
      $rh = $mdl->ConsultarRH();
      $subgrupo_rh = $mdl->ConsultarSubgrupoRH();
      
      $fecha = $request['fechaNacimiento'];
      $fn = explode("/",$fecha);
      if(sizeof($fn)==3) $fNac=$fn[2]."-".$fn[1]."-".$fn[0];
      
      $edad = $mdl->CalcularEdad($fNac);
      //print_r($edad['edad']." ");
      
      $cod_donante = $mdl->IngresarDetalleDonante($request, $edad['edad']);
      $tipificacion = $mdl->ConsultarTipificacion($cod_donante['cod_don']);
      
      $act = AutoCarga::factory("BancoSangreHTML", "views", "app", "BancoSangre");
      $this->salida = $act->formaSignosVitales($action, $grupos_sang, $rh, $subgrupo_rh, $request, $cod_donante, $tipificacion);
      //$this->salida .= "<pre>".print_r($request, true)."</pre>\n";
      
      return true;
    }
    /**
    * Funcion que permite ingresar las respuestas del cuestionario al donante
    */
    function IngresarSignosTipificacion()
    {
      //$this->SetXajax(array("EvaluarRespuesta"),"app_modules/BancoSangre/RemoteXajax/ClasiFinanciera.php");
          
      $request = $_REQUEST;
      $action['volver_signos'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'Menu');
      $action['ingresar_respuestas'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'IngresarRespuestas');
      
      if(!$request['subgrupoRH'])
      {
        $sgrupo = "";
        $sgrh = "";
      }else{
        $sg = explode("/",$request['subgrupoRH']);
        
        if(sizeof($sg)==2)
        {
          $sgrupo = $sg[0];
          $sgrh = $sg[1];
        }          
      }
      
      $mdl = AutoCarga::factory('BancoSangreSQL', '', 'app', 'BancoSangre');
      $cod_donante = $mdl->IngresarSignosTipificacion($request, $sgrupo, $sgrh);
      
      $preg_c = $mdl->ConsultarCuestionario($request);
      
      $act = AutoCarga::factory("BancoSangreHTML", "views", "app", "BancoSangre");
      $this->salida = $act->formaCuestionario($action, $preg_c, $request);
      //$this->salida .= "<pre>".print_r($request, true)."</pre>\n";
      return true;
    }
    /**
    * Funcion que permite generar reportes e impresiones 
    */
    function IngresarRespuestas()
    {
      $request = $_REQUEST;
      
      $action['volver'] = ModuloGetURL('app', 'BancoSangre', 'Controller', 'Menu');
      $action['solicitar_pruebas'] = ModuloGetURL('app', 'BancoSangre', 'Controller', 'SolicitarPruebas');
      
      $mdl = AutoCarga::factory('BancoSangreSQL', '', 'app', 'BancoSangre');
      $codDon = $mdl->AlmacenarRespuestas($request);

      $mensaje="EL INGRESO DE LA FICHA DEL DONANTE SE REALIZO SATISFACTORIAMENTE";
            
      $act = AutoCarga::factory("BancoSangreHTML", "views", "app", "BancoSangre");
      $this->salida = $act->formaMensaje($action, $mensaje, $request);
      //$this->salida .= "<pre>".print_r($request, true)."</pre>\n";
      return true;
    }
    
    function SolicitarPruebas()
    {
      $request = $_REQUEST;
      $act = AutoCarga::factory("BancoSangreHTML", "views", "app", "BancoSangre");
      $this->salida = $act->formaSolicitarPruebas();
      $this->salida .= "<pre>".print_r($request, true)."</pre>\n";
      return true;
    }
    /**
    * Funcion que permite mostrar la forma para el ingreso de la informacion de la donacion
    */
    
    function RegistrarDonaciones()
    {
      $this->SetXajax(array("BuscarClasiFinanciera", "BuscarGrado", "CalcEdad", "ValidarCedula", "BuscarFichaDonante", "BuscarEstadoCausa"), "app_modules/BancoSangre/RemoteXajax/ClasiFinanciera.php");
      
      $action['volver_menu'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'Menu');
      $action['registrar_donacion'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'IngresarRegistroDonacion');
      $mdl = AutoCarga::factory('BancoSangreSQL', '', 'app', 'BancoSangre');
      $tipos_id = $mdl->ConsultarTipoId();
      
      $act = AutoCarga::factory("BancoSangreHTML", "views", "app", "BancoSangre");
      $this->salida = $act->formaRegistrarDonaciones($action, $tipos_id);
      return true;
    }
    /**
    * Funcion que permite almacenar la informacion del registro de donacion
    */
    function IngresarRegistroDonacion()
    {
      $request = $_REQUEST;      
      
      $action['volver'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'Menu');
      
      $mdl = AutoCarga::factory('BancoSangreSQL', '', 'app', 'BancoSangre');
      $request['detRegDon'] = $mdl->IngresoRegistroDonacion($request);
      
      $mensaje="EL INGRESO DEL REGISTRO DE DONACION SE REALIZO SATISFACTORIAMENTE";
      
      $act = AutoCarga::factory("BancoSangreHTML", "views", "app", "BancoSangre");
      $this->salida = $act->formaMensajeIngresoRegistro($action, $mensaje, $request);
      //$this->salida .= "<pre>".print_r($request, true)."</pre>\n";
      return true;
    }
    /**
    * Funcion para la consulta de los registros de donacion
    */
    function ConsultaDonantes()
    {
      $request = $_REQUEST;
    
      $action['volver'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'Menu');
      $action['buscar'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'ConsultaDonantes');
      $action['paginador'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'ConsultaDonantes', array("sw_oculto"=>$request['sw_oculto'], "cedula"=>$request['cedula'], "tipoId"=>$request['tipoId'], "grupoSanguineo"=>$request['grupoSanguineo'], "factorRH"=>$request['factorRH'], "subgrupoRH"=>$request['subgrupoRH'], "fechaInicio"=>$request['fechaInicio'], "fechaFin"=>$request['fechaFin']));
    
      $mdl = AutoCarga::factory("BancoSangreSQL", "", "app", "BancoSangre");
      
      $tipos_id = $mdl->ConsultarTipoId();
      $grupos_sang = $mdl->ConsultarGrupoSanguineo();
      $rh = $mdl->ConsultarRH();
      $subgrupo_rh = $mdl->ConsultarSubgrupoRH();
      
      if($request['sw_oculto']=="consultar")
      {
        if(!$request['subgrupoRH'])
        {
          $sgrupo = "";
          $sgrh = "";
        }else{
          $sg = explode("/", $request['subgrupoRH']);
          if(sizeof($sg)==2)
          {
            $sgrupo = $sg[0];
            $sgrh = $sg[1];
          }
        }
        $datos_donacion = $mdl->ConsultarDonacionFiltro($request, $_REQUEST['offset'], $sgrupo, $sgrh);     
      }
      
      $act = AutoCarga::factory("BancoSangreHTML", "views", "app", "BancoSangre");
      $this->salida = $act->formaConsultaDonantes($action, $tipos_id, $grupos_sang, $rh, $subgrupo_rh, $request, $datos_donacion, $mdl->pagina, $mdl->conteo);
      
      //$this->salida .= "<pre>".print_r($request, true)."</pre>\n";
      
      return true;
    }
    /**
    * Funcion que permite mostrar la forma para el ingreso de la informacion del 
    * fraccionamiento de sangre
    */
    function FraccionamientoSangre()
    {
      $this->SetXajax(array("BuscarClasiFinanciera", "BuscarGrado", "CalcEdad", "ValidarCedula", "BuscarFichaDonante", "BuscarEstadoCausa", "BuscarFichaFrac"), "app_modules/BancoSangre/RemoteXajax/ClasiFinanciera.php");
      
      $request = $_REQUEST;
      
      $action['volver_menu'] = ModuloGetURL("app", "BancoSangre", "controller", "Menu");
      $action['registrar_frac'] = ModuloGetURL("app", "BancoSangre", "controller", "IngresoFraccionamiento");
      
      $mdl = AutoCarga::factory("BancoSangreSQL", "", "app", "BancoSangre");
      
      $tipos_id = $mdl->ConsultarTipoId();
      
      $act = AutoCarga::factory("BancoSangreHTML", "views", "app", "BancoSangre");
      $this->salida = $act->formaFracSangre($action, $tipos_id);
      
      return true;
    }
    /**
    * Funcion que permite almacenar la informacion del fraccionamiento de sangre
    */    
    function IngresoFraccionamiento()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL("app", "BancoSangre", "controller", "Menu");
      
      $mensaje = "EL INGRESO DEL FRACCIONAMIENTO DE SANGRE SE REALIZO SATISFACTORIAMENTE";
      
      $mdl = AutoCarga::factory("BancoSangreSQL", "", "app", "BancoSangre");
      $request['det_frac'] = $mdl->IngresarFracSangre($request);
      
      $act = AutoCarga::factory("BancoSangreHTML", "views", "app", "BancoSangre");
      $this->salida = $act->formaMensajeIngresoFrac($action, $mensaje, $request);
      //$this->salida .= "<pre>".print_r($request, true)."</pre>\n";
      return true;
    }
    /**
    * Funcion para la consulta del fraccionamiento de sangre
    */
    function ConsultaFracSangre()
    {
      $request = $_REQUEST;
      
      $action['volver'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'Menu');
      $action['buscar_frac'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'ConsultaFracSangre');
      $action['paginador'] = ModuloGetURL('app', 'BancoSangre', 'controller', 'ConsultaFracSangre', array("sw_oculto"=>$request['sw_oculto'], "tipoProducto"=>$request['tipoProducto'], "fechaInicio"=>$request['fechaInicio'],
      "fechaFin"=>$request['fechaFin']));
      
      $mdl = AutoCarga::factory("BancoSangreSQL", "", "app", "BancoSangre");
      $tipoProd = $mdl->ConsultarTipoProductoFrac();
      
      if($request['sw_oculto']=="consultar")
      {
        $datos_frac = $mdl->ConsultarFracSangFiltro($request, $_REQUEST['offset']);
      }
      
      $act = AutoCarga::factory("BancoSangreHTML", "views", "app", "BancoSangre");
      $this->salida = $act->formaConsultaFracSangre($action, $request, $tipoProd, $datos_frac, $mdl->pagina, $mdl->conteo);
      //$this->salida .= print_r($request, true);
      
      return true;
    }
    
    function HemocomponentesOB()
    {
      $request = $_REQUEST;
      $action['volver_menu'] = ModuloGetURL("app", "BancoSangre", "controller", "Menu");
      $action['registrar_hemocom'] = ModuloGetURL("app", "BancoSangre", "controller", "RegistrarHemocomponentes");
      
      $tipo_prof = ModuloGetvar('','','tipo_profesional');
      
      $mdl = Autocarga::factory("BancoSangreSQL", "", "app", "BancoSangre");
      $procedencias = $mdl->ConsultarProcedencias();
      $grupos_sang = $mdl->ConsultarGrupoSanguineo();
      $rh = $mdl->ConsultarRH();
      $subgrupo_rh = $mdl->ConsultarSubgrupoRH();
      $tipoProd = $mdl->ConsultarTipoProductoFrac();
      $responsables = $mdl->ConsultarResponsables($tipo_prof);
      
      $act = AutoCarga::factory("BancoSangreHTML", "views", "app", "BancoSangre");
      $this->salida = $act->formaIngresoHemocomponentes($action, $procedencias, $grupos_sang, $rh, $subgrupo_rh, $tipoProd, $responsables);
      
      return true;
    }
    
    function RegistrarHemocomponentes()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL("app", "BancoSangre", "controller", "Menu");
      
      $mdl = AutoCarga::factory("BancoSangreSQL", "", "app", "BancoSangre");
      $hemocom = $mdl->IngresarHemocomponentes($request);
      
      $mensaje = "El INGRESO DEL HEMOCOMPONENTE SE REALIZO SATISFACTORIAMENTE ";
      
      $act = AutoCarga::factory("BancoSangreHTML", "views", "app", "BancoSangre");
      $this->salida  = $act->formaMensajeHemocom($action, $mensaje, $request);
      //$this->salida .= print_r($request, true);
      
      return true;
    }
  }
?>