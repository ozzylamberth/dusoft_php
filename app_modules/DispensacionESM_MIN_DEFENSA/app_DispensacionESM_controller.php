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
		  	$action['Formulas'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas");
			$this->salida = $act->FormaMenu($action);
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
		  
			/*$request['buscador']['fecha'] = date("Y-m-d");*/
			$conteo =$pagina=0;
          
			$datos=$mdl->Consulta_Formulacion_Activas($request['buscador'],$request['offset']);
		
			$action['buscador']=ModuloGetURL('app','DispensacionESM','controller','BuscardorDeFormulas');
			$conteo= $mdl->conteo;
			$pagina= $mdl-> pagina;
			$action['paginador'] = ModuloGetURL('app', 'DispensacionESM', 'controller', 'BuscardorDeFormulas',array("buscador"=>$request['buscador']));
			$action['consul'] = ModuloGetURL("app", "DispensacionESM", "controller", "FormulasPaciente");
			$action['consulta_informacion'] = ModuloGetURL("app", "DispensacionESM", "controller", "Consulta_Informacion_Entrega");
			
			$action['anular'] = ModuloGetURL("app", "DispensacionESM", "controller", "Anular_Formula");
		
			$action['pendiente'] = ModuloGetURL("app", "DispensacionESM", "controller", "FormulasPaciente_p");
		    $Planes = $mdl->ConsultaPlanes_Bodega($farmacia);
			
			$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "MenuDispensacionESM");
			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$this->salida = $act->FormaBuscarFomula($action,$Tipo,$request['buscador'],$datos,$empresa,$conteo,$pagina,$Planes);
			return true;
		}
	/*
	  * Funcion que permite buscar las formulas del paciente y lista  los producto q se pueden despachar
	  * @return boolean
    */
		function FormulasPaciente()
		{
			$request = $_REQUEST;
			$empresa = SessionGetVar("DatosEmpresaAF");
           
			$formula_id=$request['formula_id'];
			$tipo_id_paciente=$request['tipo_id_paciente'];
			$paciente_id=$request['paciente_id'];
			$plan_id=$request['plan_id'];
			
			
		
		/*	$empresa = SessionGetVar("DatosEmpresaAF");
			$farmacia=$empresa['empresa_id'];
			$bodega=$empresa['bodega'];*/
			$dispensar=$request['dispensar'];
			
			
				IncludeFileModulo("DispensacionESM","RemoteXajax","app","DispensacionESM");
			$this->SetXajax(array("Cambiarvetana","Autorizacion_despacho","Eliminar_codigo_prodcto_d","BuscarProducto1","GuardarPT","MostrarProductox","MandarInformacion","MostrarMensaje","InsertarDatosFormula_tmp","EliminarDatosFormula_tmp"),"app_modules/DispensacionESM/RemoteXajax/DispensacionESM.php","ISO-8859-1");
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
			//print_r($formula_id);
			$Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($formula_id);
	    	if($opcion=='1')
			{
				$Cabecera_Formulacion_AESM=$obje->Consulta_Formulacion_Real_A($formula_id);
				$Cabecera_Formulacion_AEM=$obje->Consulta_Formulacion_Real_AE($formula_id);
			}
			
		
			
			$Datos_Fueza = $obje->ObtenerFuezaPaciente($request); 	
			$Datos_Ad=$obje->Dato_Adionales_afiliacion($request);
			$ESM_pac=$obje->Consultar_ESM_P($request);
			$dix_r=$obje->Diagnostico_Real($formula_id);
	
	        $medi_form=$obje->Medicamentos_Formulados_R($formula_id);
			
			$fedatos= date("Y-m-d");
			$request['fecha'] = date("Y-m-d");
		//	$paciente = $mdl->ObtenerFormulasMedicas($request);
    
			//$datos=$mdl->Hc_Formulacion_Antecedentes($fedatos,$request);
            $action['consul'] = ModuloGetURL("app", "DispensacionESM", "controller", "DetalleEntrega",array("tipo_id_paciente"=>$request['tipo_id_paciente'],"paciente_id"=>$request['paciente_id'],"evolucion"=>$evolucion));
			$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas");
			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$this->salida = $act->FormaFomulaPaciente($action,$request,$datos,$paciente[0],$Cabecera_Formulacion,$Cabecera_Formulacion_AESM,$request,$Cabecera_Formulacion_AEM,$Datos_Fueza,$Datos_Ad,$ESM_pac,$opcion,$dix_r,$medi_form,$formula_id,$datos_ex,$dias_dipensados);
			return true;
		}
		/* PENDIENTES */
		function FormulasPaciente_p()
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
			
			$Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($formula_id);
	    	if($opcion=='1')
			{
				$Cabecera_Formulacion_AESM=$obje->Consulta_Formulacion_Real_A($formula_id);
				$Cabecera_Formulacion_AEM=$obje->Consulta_Formulacion_Real_AE($formula_id);
			}
			
		
			
			$Datos_Fueza = $obje->ObtenerFuezaPaciente($request); 	
			$Datos_Ad=$obje->Dato_Adionales_afiliacion($request);
			$ESM_pac=$obje->Consultar_ESM_P($request);
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
			$this->salida = $act->FormaFomulaPaciente_P($action,$request,$datos,$paciente[0],$Cabecera_Formulacion,$Cabecera_Formulacion_AESM,$request,$Cabecera_Formulacion_AEM,$Datos_Fueza,$Datos_Ad,$ESM_pac,$opcion,$dix_r,$medi_form,$formula_id);
			return true;
		}
			
		
		/*  PREPARAR EL DOCUMENTO PARA DESPACHAR */
	
		
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
			
			$obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
				
		
			$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "FormulasPaciente",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"evoluciondispensar"=>$plan_id,"formula_id"=>$formula_id));
		
			$Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($formula_id);
			$temporales=$obje->Buscar_producto_tmp_conc($formula_id);
			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$this->salida = $act->Forma_Preparar_Documento_Dispensar_($action,$empresa,$Cabecera_Formulacion,$temporales,$formula_id);
			return true;
		}
		
		
		/*  PREPARANDO PRODUCTOS PENDIENSTE A ENTREGAR */
		
		
		
		
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
			
		
		
		
		
    /*  CUANDO RECLAMA EL PACIENTE  */
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
			
							
				$desp = AutoCarga::factory('DispensacionMedicamentos');
						     
				$Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($formula_id);
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
			
				$Datopciones = $desp->MenuOpcion_Esm($opcion,$empre,$bodega,$ParametrizacionReformular,$observacion,$dats_productos_dis,$Cabecera_Formulacion,$plan_id,$formula_id,$medi_form);
			
			//$informacion=$obje->ConsultarInformacionPediente_ESM($formula_id);
				$pendientes=$obje->Medicamentos_Pendientes_Esm($formula_id);
		
			
		
		
	    	//$action['pos']=ModuloGetURL('app','Dispensacion','controller','ReportePosPendiente',array("evolucion"=>$evolucion,"paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente));
			
			
			
			//$action['modulo_formularcion']=ModuloGetURL('app','Dispensacion','controller','GenerarEntregaMedicamentos',array("variable"=>1,"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"evolucion"=>$evolucion));
	
	
			$permisos = $obje->ObtenerPermisos_Formula();

					
		    $action['modulo_formularcion'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "main",array("Formulacion_Externa"=>$permisos));

	
	
	
			$action['medica_carta']=ModuloGetURL('app','Dispensacion','controller','GenerarEntregaMedicamentos',array("variable"=>1,"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"evolucion"=>$evolucion));
	
			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "main");
			$this->salida .= $act->FormaPintarUltimoPaso($action,$formula_id,$pendientes);
			return true;
		}		
   /*  RECLAMA  DIFERENTE AL PACIENTE */
	
	/*
	 * Funcion que permite registrar los datos de la persona diferente al paciente que reclama los medicamentos
	   * @return boolean
    */
  	function DatosPersonaReclama()
		{
			$request = $_REQUEST;
			if($request['observacion'])
			SessionSetVar("observar",$request['observacion']);
			$observacion=SessionGetVar("observar");
		    $formula_id=$request['formula_id'];
			
			
			$empresa = SessionGetVar("DatosEmpresaAF");
			$empre=$empresa['empresa_id'];
			$centro=$empresa['centro_utilidad'];
			$bodega=$empresa['bodega'];
			            
			$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$TipoId = $mdl->ConsultarTipoId();
			$action['guardar'] = ModuloGetURL("app", "DispensacionESM", "controller", "GenerarEntrega",array("evolucion"=>$evolucion));
			$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "Preparar_Documento_Dispensacion",array("formula_id"=>$formula_id));
			$this->salida = $act->FormaPersonaReclama($action,$TipoId);
			return true;
		}
   
			
		
		/*
    * Funcion que permite  detallar lo que se le a entregado al paciente 
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

		$Datos_Fueza = $mdl->ObtenerFuezaPaciente($Cabecera_Formulacion); 	
		$Datos_Ad=$mdl->Dato_Adionales_afiliacion($Cabecera_Formulacion);
		$ESM_pac=$mdl->Consultar_ESM_P($Cabecera_Formulacion);

		$medicamentos=$mdl->Medicamentos_Dispensados_Esm_x_lote_total($formula_id);
      
		
		$pendientes_dis=$mdl->pendientes_dispensados_ent_TOTAL($formula_id);

      $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas");
      $this->salida = $act->FormaPintarDetalle($action,$Cabecera_Formulacion,$Datos_Fueza,$Datos_Ad,$ESM_pac,$medicamentos,$pendientes_dis);
      return true;
    }
    /* Funcion Anular Formula */
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
			$html .= "window.location=\"".$url."\";";
			}
			$html .= "</script>";
			$this->salida=$html;
			return true;
		}
	} 
?>