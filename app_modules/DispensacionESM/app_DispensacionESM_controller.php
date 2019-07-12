<?php
	/** 
	* @package IPSOFT-SIIS        
	* @version $Id: app_DispensacionESM_controller.php,v 1.0
	* @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	/**
	* Clase Control: DispensacionESM
	* Clase encargada del control de llamado de metodos en el modulo
	*
	* @package IPSOFT-SIIS
	/*/
	IncludeClass("ClaseHTML");       
	IncludeClass("ClaseUtil");
	IncludeClass("DispensacionESMMedicamentos");
	class app_DispensacionESM_controller  extends classModulo
	{
    /**
		* Constructor de la clase
    */
    function app_DispensacionESM_controller(){}
   	/**
    * Funcion principal del modulo
    * @return boolean
		*/
		
		function main()
		{
			$request = $_REQUEST;
			$obj = AutoCarga::factory('DispensacionESMSQL', '', 'app', 'DispensacionESM');
			$permisos = $obj->ObtenerPermisos();    
		
			$ttl_gral = "DISPENSACION DE MEDICAMENTOS ";
			$mtz[0] = 'FARMACIAS';
			$mtz[1] = 'CENTRO DE UTILIDAD';
			$mtz[3] = 'BODEGA';
			$url[0] = 'app';
			$url[1] = 'DispensacionESM'; 
			$url[2] = 'controller';
			$url[3] = 'MenuDispensacionESM'; 
			$url[4] = 'DispensacionESM'; 
			
			$action['volver'] = ModuloGetURL('system', 'Menu');
			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
			return true;
		}
	 /*
		* Funcion que permite ir a los diferentes menus del modulo de DispensacionESM
		* @return boolean
    */    
		function MenuDispensacionESM()
		{
			$request = $_REQUEST;
       
      
			if($request['DispensacionESM']) 
			SessionSetVar("DatosEmpresaAF",$request['DispensacionESM']);
			$empresa = SessionGetVar("DatosEmpresaAF");
			$farmacia=$empresa['empresa_id'];
			$bodega=$empresa['bodega'];
		
			$bodegas_doc_id= ModuloGetVar('app','DispensacionESM','documento_dispensacion_'.trim($farmacia).'_'.trim($bodega));
      $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "main");
      $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
      $mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");	

			if(empty($bodegas_doc_id))
			{
        $this->salida = $act->FormaMenuMensaje($action);
			}
			else
			{
        $permisos = $mdl->ObtenerPermisos_FORMULACION($empresa);  
        $menu_dispensacion = $mdl->Consultar_menu_dispensacion_esm();
		  	$action['Formulas'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas");
        $action['formulacion'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Buscar_Pacientes_Formulas",array("DISPENSACION"=>"DISPENSACION"));
		  	$action['tickets'] = ModuloGetURL("app", "DispensacionESM", "controller", "Tickets_Dispensacion");
        
        $action['formulacion_externa_esm'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Buscar_Pacientes_Formulas",array("DISPENSACION"=>"DISPENSACION"));
		  	
        $action['formula_esm'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulasESM");
      
        
        
        $this->salida = $act->FormaMenu($action,$permisos,$menu_dispensacion);
			}
			return true;
		}
       
    /*
	  * Funcion que permite buscar las formulas del paciente
	  * @return boolean
    */
		function BuscardorDeFormulas()
		{
          $request = $_REQUEST;

          $mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
          $Tipo = $mdl->ConsultarTipoId();
          $empresa = SessionGetVar("DatosEmpresaAF");
          $farmacia=$empresa['empresa_id'];
          $conteo =$pagina=0;
          $datos=$mdl->Consulta_Formulas_Externas($request['buscador'],$request['offset']);
          $action['buscador']=ModuloGetURL('app','DispensacionESM','controller','BuscardorDeFormulas');
          $conteo= $mdl->conteo;
          $pagina= $mdl-> pagina;
          $action['paginador'] = ModuloGetURL('app', 'DispensacionESM', 'controller', 'BuscardorDeFormulas',array("buscador"=>$request['buscador']));
          $action['consul'] = ModuloGetURL("app", "DispensacionESM", "controller", "FormulasPaciente");
          $action['consulta_informacion'] = ModuloGetURL("app", "DispensacionESM", "controller", "Consulta_Informacion_Entrega");
          $action['anular'] = ModuloGetURL("app", "DispensacionESM", "controller", "Anular_Formula");
          $action['pendiente'] = ModuloGetURL("app", "DispensacionESM", "controller", "FormulasPaciente_p");
          
          $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "MenuDispensacionESM");
          $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
          $this->salida = $act->FormaBuscarFomula($action,$Tipo,$request['buscador'],$datos,$empresa,$conteo,$pagina);
          return true;
		}
  	/*
	  * Funcion que permite buscar las formulas del paciente y lista  los producto q se pueden despachar
	  * @return boolean */
  
    function FormulasPaciente()
		{
		
        $request = $_REQUEST;
        $empresa = SessionGetVar("DatosEmpresaAF");
        $formula_id=$request['formula_id'];
        $tipo_id_paciente=$request['tipo_id_paciente'];
        $paciente_id=$request['paciente_id'];
        $plan_id=$request['plan_id'];
        
        $dispensar=$request['dispensar'];

        IncludeFileModulo("DispensacionESM","RemoteXajax","app","DispensacionESM");
        $this->SetXajax(array("Autorizacion_Formula","Cambiarvetana","Autorizacion_despacho","Eliminar_codigo_prodcto_d","BuscarProducto1","GuardarPT","MostrarProductox","MandarInformacion","MostrarMensaje","InsertarDatosFormula_tmp","EliminarDatosFormula_tmp"),"app_modules/DispensacionESM/RemoteXajax/DispensacionESM.php","ISO-8859-1");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");
        
        
        
	     if($dispensar=='SI')
			 {
          if($request['DispensacionESM']) 
          SessionSetVar("DatosEmpresaAF",$request['DispensacionESM']);
          $empresa = SessionGetVar("DatosEmpresaAF");
			 }
       
       
			$bodegas_doc_id= ModuloGetVar('app','DispensacionESM','documento_dispensacion_'.trim($farmacia).'_'.trim($bodega));
		 
			$obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
			$today = date("Y-m-d"); 
			$hoy=explode("-", $today);
			$hoy_fecha= $hoy[2]."/".$hoy[1]."/".$hoy[0];

      /*variable */
			$dias_dipensados= ModuloGetVar('','','dispensacion_dias_ultima_entrega');
			
			list($a,$m,$d) = split("-",$today);
			$fecha_condias = date("Y-m-d",(mktime(0,0,0, $m,($d - $dias_dipensados),$a)));
			$fecha_condias_d=explode("-", $fecha_condias);
			$datos_ex=$obje->ConsultarUltimoResg_Dispens($formula_id,$today,$fecha_condias);
      $Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($formula_id);
	    $Datos_Ad=$obje->Dato_Adionales_afiliacion($request);
      $dix_r=$obje->Diagnostico_Real($formula_id);
	    $medi_form=$obje->Medicamentos_Formulados_R($formula_id);
			$fedatos= date("Y-m-d");
			$request['fecha'] = date("Y-m-d");
			$existe_f=$obje->ConsultarInformacion_Temporal_Formula($formula_id);
		
      $action['consul'] = ModuloGetURL("app", "DispensacionESM", "controller", "DetalleEntrega",array("tipo_id_paciente"=>$request['tipo_id_paciente'],"paciente_id"=>$request['paciente_id'],"evolucion"=>$evolucion));
			$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas");
			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$this->salida = $act->FormaFomulaPaciente($action,$request,$datos,$paciente[0],$Cabecera_Formulacion,$request,$Datos_Ad,$dix_r,$medi_form,$formula_id,$datos_ex,$dias_dipensados,$existe_f,$dusuario_id);
			return true;
		}

   	/*
	  * Funcion que permite preparar un documento con los medicamentos a despachar
	  * @return boolean */
  
		function Preparar_Documento_Dispensacion()
		{
		
          $request = $_REQUEST;
          $empresa = SessionGetVar("DatosEmpresaAF");

          IncludeFileModulo("DispensacionESM","RemoteXajax","app","DispensacionESM");
          $this->SetXajax(array("PacienteReclama","PersonaRclama","ValidarDatosPersona"),"app_modules/DispensacionESM/RemoteXajax/DispensacionESM.php","ISO-8859-1");
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("CrossBrowserDrag");
        
        
          $tipo_id_paciente=$request['tipo_id_paciente'];
          $paciente_id=$request['paciente_id'];
          $plan_id=$request['plan_id'];
          $formula_id=$request['formula_id'];
          $todo_pendiente=$request['todopendiente'];
          
          $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
          $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "FormulasPaciente",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"evoluciondispensar"=>$plan_id,"formula_id"=>$formula_id));
          $Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($formula_id);
          $temporales=$obje->Buscar_producto_tmp_conc($formula_id);
        
          $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
          $this->salida = $act->Forma_Preparar_Documento_Dispensar_($action,$empresa,$Cabecera_Formulacion,$temporales,$formula_id,$pendiente,$todo_pendiente);
          return true;
		}
   	/*
	  * Funcion que permite realizar la entrega de los medicamentos
	  * @return boolean */
  	function GenerarEntregaMedicamentos()
		{
          $request = $_REQUEST;
          $observacion=$request['observacion'];
          $formula_id=$request['formula_id'];
          $pendiente=$request['pendiente'];
          $empresa = SessionGetVar("DatosEmpresaAF");
		      $empre=$empresa['empresa_id'];
		      $centro=$empresa['centro_utilidad'];
		      $bodega=$empresa['bodega'];

		      $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
     		  $ParametrizacionReformular= ModuloGetVar('','','ParametrizacionReformular');
        
          $todo_pendiente=$request['todo_pendiente'];
          $Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($formula_id);
               
          if($todo_pendiente=='1')
          {
              $actualizacion=$obje->UpdateEstad_Form($formula_id);
          }
          $desp = AutoCarga::factory('DispensacionMedicamentos');
					$plan_id=$Cabecera_Formulacion['plan_id'];
          $temporales=$obje->Buscar_producto_tmp_conc($formula_id);
			   	$medi_form=$obje->Medicamentos_Formulados_R($formula_id);
          if($pendiente=='0')
          {
              $opcion=2;
          }else
          {
						$opcion=1;
          }
			   	$Datopciones = $desp->MenuOpcion_FormulacionExterna($opcion,$empre,$bodega,$ParametrizacionReformular,$observacion,$dats_productos_dis,$Cabecera_Formulacion,$plan_id,$formula_id,$medi_form,$todo_pendiente);
                                              
          $pendientes=$obje->Medicamentos_Pendientes_Esm($formula_id);
            $permisos = $obje->ObtenerPermisos_FORMULACION($empresa);  
          $action['modulo_formularcion'] = ModuloGetURL("app", "DispensacionESM", "controller", "MenuDispensacionESM",array("DispensacionESM"=>$permisos));
      
          $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
          $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "main");
          $this->salida .= $act->FormaPintarUltimoPaso($action,$formula_id,$pendientes,$todo_pendiente);
          return true;
		}
       
		/*
    * Funcion que permite  consultar el detalle de lo dispensado al paciente 
    * @return boolean
    */	
    function Consulta_Informacion_Entrega()
    {
        $request = $_REQUEST;
        $empresa = SessionGetVar("DatosEmpresaAF");
        $formula_id=$request['formula_id'];
        $tipo_id_paciente=$request['tipo_id_paciente'];
        $paciente_id=$request['paciente_id'];
        $plan_id=$request['plan_id'];

        $mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
        $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");

        $Cabecera_Formulacion=$mdl->Consulta_Formulacion_Real_I($formula_id);
        $Datos_Ad=$mdl->Dato_Adionales_afiliacion($Cabecera_Formulacion);
        $medicamentos=$mdl->Medicamentos_Dispensados_Esm_x_lote_total($formula_id);
       	$pendientes_dis=$mdl->pendientes_dispensados_ent_TOTAL($formula_id);
        $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas");
        $this->salida = $act->FormaPintarDetalle($action,$Cabecera_Formulacion,$Datos_Ad,$medicamentos,$pendientes_dis);
        return true;
    }
   	/*
    * Funcion que permite  consultar los medicamentos pendientes por dispensar    
    * @return boolean
    */
    function FormulasPaciente_p()
		{
          $request = $_REQUEST;
          $empresa = SessionGetVar("DatosEmpresaAF");
          $formula_id=$request['formula_id'];
          $tipo_id_paciente=$request['tipo_id_paciente'];
          $paciente_id=$request['paciente_id'];
          $plan_id=$request['plan_id'];
          $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");

          $Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($formula_id);
          $Datos_Ad=$obje->Dato_Adionales_afiliacion($request);
          $dix_r=$obje->Diagnostico_Real($formula_id);
          $medi_form=$obje->Medicamentos_Pendientes_Esm($formula_id);

          IncludeFileModulo("DispensacionESM","RemoteXajax","app","DispensacionESM");
          $this->SetXajax(array("Cambiarvetana2","Eliminar_codigo_prodcto_d2","BuscarProducto2","GuardarPTP","MostrarProductox2","MandarInformacion","MostrarMensaje","InsertarDatosFormula_tmp","EliminarDatosFormula_tmp"),"app_modules/DispensacionESM/RemoteXajax/DispensacionESM.php","ISO-8859-1");
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("CrossBrowserDrag");
          $fedatos= date("Y-m-d");
          $request['fecha'] = date("Y-m-d");

          $action['consul'] = ModuloGetURL("app", "DispensacionESM", "controller", "DetalleEntrega",array("tipo_id_paciente"=>$request['tipo_id_paciente'],"paciente_id"=>$request['paciente_id'],"evolucion"=>$evolucion));
          $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas");
          $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
          $this->salida = $act->FormaFomulaPaciente_P($action,$request,$datos,$paciente[0],$Cabecera_Formulacion,$request,$Datos_Ad,$opcion,$dix_r,$medi_form,$formula_id);
          return true;
		}
    /*
    * Funcion que permite preparar el documento a dispensar para los medicamentos pendientes    
    * @return boolean
    */
       
    function Preparar_Documento_Dispensacion_Pendientes()
		{
		
          $request = $_REQUEST;
          $empresa = SessionGetVar("DatosEmpresaAF");
          IncludeFileModulo("DispensacionESM","RemoteXajax","app","DispensacionESM");
          $this->SetXajax(array("PacienteReclama","PersonaRclama","ValidarDatosPersona"),"app_modules/DispensacionESM/RemoteXajax/DispensacionESM.php","ISO-8859-1");
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("CrossBrowserDrag");
          $formula_id=$request['formula_id'];
			
          $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
          $pendiente='0';
          $Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($formula_id);
          $temporales=$obje->Buscar_producto_tmp_conc($formula_id);
          $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
          $this->salida = $act->Forma_Preparar_Documento_Dispensar_($action,$empresa,$Cabecera_Formulacion,$temporales,$formula_id,$pendiente);
          return true;
		}
		/*
    * Funcion que permite anular una formula En el caso de que no este despachada     
    * @return boolean
    */
    	
		 function Anular_Formula()
      {

          $request = $_REQUEST;
          $empresa = SessionGetVar("DatosEmpresaAF");

          $formula_id=$request['formula_id'];
          $tipo_id_paciente=$request['tipo_id_paciente'];
          $paciente_id=$request['paciente_id'];
          $plan_id=$request['plan_id'];
          $mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
          $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
          $Token = $mdl->Actulizar_Estado_Formula_($formula_id); 	
        
          $url = ModuloGetURL("app","DispensacionESM","controller","BuscardorDeFormulas");
          $html .= "<script>";
          if(!$Token)
          $html .= " history.go(-1) ";
          else 
          {
            $html .= " alert('La Formula  se Anulo Correctamente'); ";
            $html .= "window.location=\"".$url."\";";
          }
          $html .= "</script>";
          $this->salida=$html;
          return true;
		}
  
    /*
    * Funcion que permite buscar los tickets de las formulas despachadas    
    * @return boolean
    */
     function Tickets_Dispensacion()
    {
      $request = $_REQUEST;
                  
      $mdl = AutoCarga::factory("Tickets_DispensacionHTML","views","app","DispensacionESM");
      $cls = AutoCarga::factory("Tickets_DispensacionSQL","classes","app","DispensacionESM");
      $Tipo_Id_paciente=$cls ->Tipos_Ids();
      $datosN = array();
     
      if($request['buscador'])
      {
        $datosN = $cls->Obtener_Reporte($request['buscador'],$request['offset']);
        $request['buscador']['usuario_id'] = UserGetUID();
        $request['buscador']['empresa_id'] = $empresa['empresa'];
      }
      $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "main");
      $action['buscar'] = ModuloGetURL('app','DispensacionESM','controller','Tickets_Dispensacion')."&reportes[empresa]=".$_REQUEST['reportes']['empresa']."";
      $action['paginador'] = ModuloGetURL('app','DispensacionESM','controller','Tickets_Dispensacion',array("buscador"=>$request['buscador']))."&reportes[empresa]=".$_REQUEST['reportes']['empresa']."";
      
      $this->salida .= $mdl->Forma($action,$request['buscador'],$datosN,$Tipo_Id_paciente, $cls->conteo, $cls->pagina);
      return true;
    } 
 
 /********************************FORMULACION MEDICAMENTOS ESM /************************
  /*
	  * Funcion que permite buscar las formulas del paciente
	  * @return boolean
    */
		function BuscardorDeFormulasESM()
		{
          $request = $_REQUEST;

          $mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
          $Tipo = $mdl->ConsultarTipoId();
          $empresa = SessionGetVar("DatosEmpresaAF");
          $farmacia=$empresa['empresa_id'];
          $conteo =$pagina=0;
          $datos=$mdl->Consulta_Formulacion_ESM($request['buscador'],$request['offset']);
		
          $action['buscador']=ModuloGetURL('app','DispensacionESM','controller','BuscardorDeFormulasESM');
          $conteo= $mdl->conteo;
          $pagina= $mdl-> pagina;
          $action['paginador'] = ModuloGetURL('app', 'DispensacionESM', 'controller', 'BuscardorDeFormulasESM',array("buscador"=>$request['buscador']));
          $action['consul'] = ModuloGetURL("app", "DispensacionESM", "controller", "FormulasPacienteESM");
          $action['pendiente'] = ModuloGetURL("app", "DispensacionESM", "controller", "FormulasPaciente_pESM");
          $action['consulta_informacion'] = ModuloGetURL("app", "DispensacionESM", "controller", "Consulta_Informacion_Entrega_ESM");
          $action['anular'] = ModuloGetURL("app", "DispensacionESM", "controller", "Anular_Formula_ESM");
          
          $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "MenuDispensacionESM");
          $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
          $this->salida = $act->FormaBuscarFomulaESM($action,$Tipo,$request['buscador'],$datos,$empresa,$conteo,$pagina);
          return true;
		}
 	/*
	  * Funcion que permite buscar las formulas del paciente y lista  los producto q se pueden despachar
	  * @return boolean
    */
		function FormulasPacienteESM()
		{
          $request = $_REQUEST;
          $empresa = SessionGetVar("DatosEmpresaAF");
               
          $formula_id=$request['formula_id'];
          $tipo_id_paciente=$request['tipo_id_paciente'];
          $paciente_id=$request['paciente_id'];
          $plan_id=$request['plan_id'];
          $dispensar=$request['dispensar'];
			
			
          IncludeFileModulo("DispensacionESM","RemoteXajax","app","DispensacionESM");
          $this->SetXajax(array("Autorizacion_FormulaESM","Autorizacion_despachoESM","CambiarvetanaESM","Eliminar_codigo_prodcto_dESM","BuscarProductoESM","GuardarPTESM","MostrarProductoxESM","EliminarDatosFormula_tmp"),"app_modules/DispensacionESM/RemoteXajax/DispensacionESM.php","ISO-8859-1");
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("CrossBrowserDrag");
      
          if($dispensar=='SI')
        {
					if($request['DispensacionESM']) 
          SessionSetVar("DatosEmpresaAF",$request['DispensacionESM']);
          $empresa = SessionGetVar("DatosEmpresaAF");
			 }
        $bodegas_doc_id= ModuloGetVar('app','DispensacionESM','documento_dispensacion_'.trim($farmacia).'_'.trim($bodega));
        $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
        
		
        $today = date("Y-m-d"); 
        $hoy=explode("-", $today);
        $hoy_fecha= $hoy[2]."/".$hoy[1]."/".$hoy[0];
        $dias_dipensados= ModuloGetVar('','','dispensacion_dias_ultima_entrega');
        list($a,$m,$d) = split("-",$today);
        $fecha_condias = date("Y-m-d",(mktime(0,0,0, $m,($d - $dias_dipensados),$a)));
        $fecha_condias_d=explode("-", $fecha_condias);
        $datos_ex=$obje->ConsultarUltimoResg_Dispens($formula_id,$today,$fecha_condias);
        $ips_tercero_id=$request['ips_tercero_id'];
        if(!empty($ips_tercero_id))
        {
            $opcion='1';
          }else
        {
          $opcion='0';
				}
		
        $Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I_ESM($formula_id);
	    	if($opcion=='1')
			{
				$Cabecera_Formulacion_AESM=$obje->Consulta_Formulacion_Real_A($formula_id);
				$Cabecera_Formulacion_AEM=$obje->Consulta_Formulacion_Real_AE($formula_id);
			}
          $Datos_Fueza = $obje->ObtenerFuezaPaciente($request); 	
          $Datos_Ad=$obje->Dato_Adionales_afiliacion($request);
          $ESM_pac=$obje->Consultar_ESM_P($request);
          $dix_r=$obje->Diagnostico_Real($formula_id);
	        $medi_form=$obje->Medicamentos_Formulados_R_ESM($formula_id);
          $fedatos= date("Y-m-d");
          $request['fecha'] = date("Y-m-d");
          $existe_f=$obje->ConsultarInformacion_Temporal_Formula($formula_id);
		
          $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulasESM");
          $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
          $this->salida = $act->FormaFomulaPaciente_ESM($action,$request,$datos,$paciente[0],$Cabecera_Formulacion,$Cabecera_Formulacion_AESM,$request,$Cabecera_Formulacion_AEM,$Datos_Fueza,$Datos_Ad,$ESM_pac,$opcion,$dix_r,$medi_form,$formula_id,$datos_ex,$dias_dipensados,$existe_f,$dusuario_id);
          return true;
		}
	/*
	  * Funcion que permite preparar un documento con los medicamentos a despachar
	  * @return boolean */
  
		function Preparar_Documento_DispensacionESM()
		{
		
            $request = $_REQUEST;
            $empresa = SessionGetVar("DatosEmpresaAF");
            IncludeFileModulo("DispensacionESM","RemoteXajax","app","DispensacionESM");
            $this->SetXajax(array("PacienteReclamaESM"),"app_modules/DispensacionESM/RemoteXajax/DispensacionESM.php","ISO-8859-1");
            $this->IncludeJS("CrossBrowser");
            $this->IncludeJS("CrossBrowserEvent");
            $this->IncludeJS("CrossBrowserDrag");
                    
            $tipo_id_paciente=$request['tipo_id_paciente'];
            $paciente_id=$request['paciente_id'];
            $plan_id=$request['plan_id'];
                
            $formula_id=$request['formula_id'];
            $todo_pendiente=$request['todopendiente'];
            $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
            $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "FormulasPacienteESM",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"evoluciondispensar"=>$plan_id,"formula_id"=>$formula_id));
        
            $Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I_ESM($formula_id);
            $temporales=$obje->Buscar_producto_tmp_c_ESM($formula_id);
            $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
            $this->salida = $act->Forma_Preparar_Documento_Dispensar_ESM($action,$empresa,$Cabecera_Formulacion,$temporales,$formula_id,$pendiente,$todo_pendiente);
            return true;
    }
			/*
	  * Funcion que permite realizar la entrega de los medicamentos
	  * @return boolean */
 
		function GenerarEntregaMedicamentosESM()
		{
          $request = $_REQUEST;
          $observacion=$request['observacion'];
          $formula_id=$request['formula_id'];
		      $pendiente=$request['pendiente'];
		      $empresa = SessionGetVar("DatosEmpresaAF");
		      $empre=$empresa['empresa_id'];
		      $centro=$empresa['centro_utilidad'];
		      $bodega=$empresa['bodega'];
          $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
          $ParametrizacionReformular= ModuloGetVar('','','ParametrizacionReformular');
			
           $todo_pendiente=$request['todo_pendiente'];
           $Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I_ESM($formula_id);
           if($todo_pendiente=='1' &&  $Cabecera_Formulacion['sw_ambulatoria']=='1')
          {
                $actualizacion=$obje->UpdateEstad_Form($formula_id);
			    }
            $desp = AutoCarga::factory('DispensacionMedicamentos');
            $plan_id=$Cabecera_Formulacion['plan_id'];
            $temporales=$obje->Buscar_producto_tmp_c_ESM($formula_id);
             
            $medi_form=$obje->Medicamentos_Formulados_R_ESM($formula_id);
           if($pendiente=='0')
          {
							
              $opcion=2;
          }else
          {
				
            $opcion=1;
				
          }
			
				$Datopciones = $desp->MenuOpcion_Esm($opcion,$empre,$bodega,$ParametrizacionReformular,$observacion,$dats_productos_dis,$Cabecera_Formulacion,$plan_id,$formula_id,$medi_form,$todo_pendiente);
        $pendientes=$obje->Medicamentos_Pendientes_Esm_($formula_id);
				$permisos = $obje->ObtenerPermisos_FORMULACION($empresa);
        $action['modulo_formularcion'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "main",array("Formulacion_Externa"=>$permisos));
    
        $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
        $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "main");
        $this->salida .= $act->FormaPintarUltimoPaso_ESM($action,$formula_id,$pendientes,$todo_pendiente);
        return true;
		}
 	/*
    * Funcion que permite  consultar los medicamentos pendientes por dispensar    
    * @return boolean
    */
		function FormulasPaciente_pESM()
		{
          $request = $_REQUEST;
          $empresa = SessionGetVar("DatosEmpresaAF");
          $formula_id=$request['formula_id'];
          $tipo_id_paciente=$request['tipo_id_paciente'];
          $paciente_id=$request['paciente_id'];
          $plan_id=$request['plan_id'];
          
          $ips_tercero_id=$request['ips_tercero_id'];
          $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
          if(!empty($ips_tercero_id))
          {
            $opcion='1';
          }else
          {
            $opcion='0';
          
          }
			
          $Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I_ESM($formula_id);
          if($opcion=='1')
        {
          $Cabecera_Formulacion_AESM=$obje->Consulta_Formulacion_Real_A($formula_id);
          $Cabecera_Formulacion_AEM=$obje->Consulta_Formulacion_Real_AE($formula_id);
        }
          $Datos_Fueza = $obje->ObtenerFuezaPaciente($request); 	
          $Datos_Ad=$obje->Dato_Adionales_afiliacion($request);
          $ESM_pac=$obje->Consultar_ESM_P($request);
          $dix_r=$obje->Diagnostico_Real($formula_id);
	        $medi_form=$obje->Medicamentos_Pendientes_Esm_($formula_id);
          IncludeFileModulo("DispensacionESM","RemoteXajax","app","DispensacionESM");
          $this->SetXajax(array("Cambiarvetana2ESM","Eliminar_codigo_prodcto_d2ESM","BuscarProducto2ESM","GuardarPTP_ESM","MostrarProductox2ESM"),"app_modules/DispensacionESM/RemoteXajax/DispensacionESM.php","ISO-8859-1");
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("CrossBrowserDrag");

          $fedatos= date("Y-m-d");
          $request['fecha'] = date("Y-m-d");
	
          $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulasESM");
          $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
          $this->salida = $act->FormaFomulaPaciente_P_ESM($action,$request,$datos,$paciente[0],$Cabecera_Formulacion,$Cabecera_Formulacion_AESM,$request,$Cabecera_Formulacion_AEM,$Datos_Fueza,$Datos_Ad,$ESM_pac,$opcion,$dix_r,$medi_form,$formula_id);
          return true;
		}
     /*
    * Funcion que permite preparar el documento a dispensar para los medicamentos pendientes    
    * @return boolean
    */
    
		function Preparar_Documento_Dispensacion_Pendientes_ESM()
		{
		
        $request = $_REQUEST;
        $empresa = SessionGetVar("DatosEmpresaAF");
        IncludeFileModulo("DispensacionESM","RemoteXajax","app","DispensacionESM");
        $this->SetXajax(array("PacienteReclamaESM","PersonaRclamaESM","ValidarDatosPersona"),"app_modules/DispensacionESM/RemoteXajax/DispensacionESM.php","ISO-8859-1");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");


			$formula_id=$request['formula_id'];
			
			$obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
			$pendiente='0';
			$Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I_ESM($formula_id);
			$temporales=$obje->Buscar_producto_tmp_c_ESM($formula_id);
			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$this->salida = $act->Forma_Preparar_Documento_Dispensar_ESM($action,$empresa,$Cabecera_Formulacion,$temporales,$formula_id,$pendiente);
			return true;
		}
    /*
    * Funcion que permite  detallar lo que se le a entregado al paciente 
    * @return boolean
    */	
	function Consulta_Informacion_Entrega_ESM()
    {

        $request = $_REQUEST;
        $empresa = SessionGetVar("DatosEmpresaAF");

        $formula_id=$request['formula_id'];
        $tipo_id_paciente=$request['tipo_id_paciente'];
        $paciente_id=$request['paciente_id'];
        $plan_id=$request['plan_id'];

        $mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
        $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");


        $Cabecera_Formulacion=$mdl->Consulta_Formulacion_Real_I_ESM($formula_id);

        $Datos_Fueza = $mdl->ObtenerFuezaPaciente($Cabecera_Formulacion); 	
        $Datos_Ad=$mdl->Dato_Adionales_afiliacion($Cabecera_Formulacion);
        $ESM_pac=$mdl->Consultar_ESM_P($Cabecera_Formulacion);

        $medicamentos=$mdl->Medicamentos_Dispensados_Esm_x_lote_total_ESM($formula_id);
    		$pendientes_dis=$mdl->pendientes_dispensados_ent_TOTAL_ESM($formula_id);

        $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulasESM");
        $this->salida = $act->FormaPintarDetalle_ESM($action,$Cabecera_Formulacion,$Datos_Fueza,$Datos_Ad,$ESM_pac,$medicamentos,$pendientes_dis);
        return true;
    }
    	/*
    * Funcion que permite anular una formula En el caso de que no este despachada     
    * @return boolean
    */	
      
      function Anular_Formula_ESM()
		{

			$request = $_REQUEST;
			$empresa = SessionGetVar("DatosEmpresaAF");

			$formula_id=$request['formula_id'];
			$tipo_id_paciente=$request['tipo_id_paciente'];
			$paciente_id=$request['paciente_id'];
			$plan_id=$request['plan_id'];

			$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");

			$Token = $mdl->Actulizar_Estado_Formula_($formula_id); 	
			$url = ModuloGetURL("app","DispensacionESM","controller","BuscardorDeFormulasESM");
			$html .= "<script>";
			if(!$Token)
			$html .= " history.go(-1) ";
			else 
			{
			$html .= "window.location=\"".$url."\";";
			}
			$html .= "</script>";
			$this->salida=$html;
			return true;
		}
    
	} 
?>