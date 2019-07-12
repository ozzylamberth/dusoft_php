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
			$this->SetXajax(array("Cambiarvetana","Eliminar_codigo_prodcto_d","BuscarProducto1","GuardarPT","MostrarProductox","MandarInformacion","MostrarMensaje","InsertarDatosFormula_tmp","EliminarDatosFormula_tmp"),"app_modules/DispensacionESM/RemoteXajax/DispensacionESM.php","ISO-8859-1");
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
		//	$paciente = $mdl->ObtenerFormulasMedicas($request);
    
			//$datos=$mdl->Hc_Formulacion_Antecedentes($fedatos,$request);
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
			
		
		
		
		
    /*  CUANDO RECLAMA EL PACIENTE EL VA */
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
    * Funcion que permite  entregar los productos a despachar
    * @return boolean
    */
		function Medicamentos_A_Despachar()
		{
    		$request = $_REQUEST;
     
			$empresa = SessionGetVar("DatosEmpresaAF");
			
			IncludeFileModulo("DispensacionESM","RemoteXajax","app","DispensacionESM");
			$this->SetXajax(array("MostrarMensajes","VerificarDispensacionESM","EntregaProductosFormula","InformacionTemporal","InsertarInformacionTemporal","EliminarInformacionTemporal","Eliminarporcompletot"),null,"ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
         
		   $evolucion=$request['evolucion'];
			if($request['tipo_id_paciente'])
			SessionSetVar("tipo_paciente",$request['tipo_id_paciente']);
			$tipo_id_paciente=SessionGetVar("tipo_paciente");

			if($request['paciente_id'])
			SessionSetVar("pacie_id",$request['paciente_id']);
			$paciente_id=SessionGetVar("pacie_id");

			$cant=$request['cantidad'];
			$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
			$informacion =$mdl->ConsultarInformacionEntregaMedicamentos($tipo_id_paciente,$paciente_id);
  
			$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id));
			$action['entrega'] = ModuloGetURL("app", "DispensacionESM", "controller", "GuardarMedicamentosTmp",array("evolucion"=>$evolucion));
			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$this->salida = $act->FormaPintarDespachos($action,$informacion,$tipo_id_paciente,$paciente_id,$cant,$evolucion);
			return true;
		}
	
	/*
	  * Funcion que permite Mostrar los medicamentos con la cantidad a entregar
	  * @return boolean
    */
	function GuardarMedicamentosTmp()
		{
			$request = $_REQUEST;
			
			$empresa = SessionGetVar("DatosEmpresaAF");
			$empre=$empresa['empresa_id'];
			$centro=$empresa['centro_utilidad'];
			$bodega=$empresa['bodega'];
            $evolucion=$request['evolucion'];
			if($request['entrega'])
			SessionSetVar("entrega",$request['entrega']);
			$entrega=SessionGetVar("entrega");

			$tipo_id_paciente=SessionGetVar("tipo_paciente");
			$paciente_id=SessionGetVar("pacie_id");

			$empresa_nombre=$empresa['descripcion1'];
			$centro_nombre=$empresa['descripcion2'];
			$bodega_nombre=$empresa['descripcion4'];

			IncludeFileModulo("DispensacionESM","RemoteXajax","app","DispensacionESM");
			$this->SetXajax(array("PacienteReclama","PersonaRclama","ValidarDatosPersona"),"app_modules/DispensacionESM/RemoteXajax/DispensacionESM.php","ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			
			$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
			$Paciente=$mdl->DatosPaciente($tipo_id_paciente,$paciente_id);
			$Primer_nombre=$Paciente ['0']['primer_nombre'];
			$segundo_nombre=$Paciente ['0']['segundo_nombre'];
			$primer_apellido=$Paciente ['0']['primer_apellido'];
			$segundo_apellido=$Paciente ['0']['segundo_apellido'];
			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "Medicamentos_A_Despachar",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"evolucion"=>$evolucion));
			$this->salida = $act->PintarTabla($action,$empresa_nombre,$centro_nombre,$bodega_nombre,$inf,$paciente_id,$tipo_id_paciente,$Primer_nombre,$segundo_nombre,$primer_apellido,$segundo_apellido,$entrega,$evolucion);
			return true;
		}

	/* 	Funcion que Permite imprimir en Formato No pos los medicamentos que el paciente tiene pendiente
		    @return boolean
	*/
	  function  ReportePosPendiente()
		{
			$request = $_REQUEST;
	       $empresa = SessionGetVar("DatosEmpresaAF");
			$empre=$empresa['empresa_id'];
			$centro=$empresa['centro_utilidad'];
			$bodega=$empresa['bodega'];
			$evolucion=$request['evolucion'];
			   
			$tipo_id_paciente=$request['tipo_id_paciente'];
			$paciente_id=$request['paciente_id'];
			$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
			$datosPendientes=$mdl->ConsultarInformacionPediente($paciente_id,$tipo_id_paciente,$evolucion);
			$Paciente=$mdl->DatosPaciente($tipo_id_paciente,$paciente_id);
						
			$datos = array();
			array_push($datos, $empresa);
			array_push($datos, $Paciente);
			array_push($datos, $datosPendientes);
		
     	if (!IncludeFile("classes/reports/reports.class.php"))
			{
				$this->error = "No se pudo inicializar la Clase de Reportes";
				$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
				return false;
			}
				
			$classReport = new reports;
			$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
			$reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='DispensacionESM',$reporte_name='DispensacionESMPosPendientes',$datos,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
			if(!$reporte)
			{
					$this->error = $classReport->GetError();
					$this->mensajeDeError = $classReport->MensajeDeError();
					unset($classReport);
					return false;
			}

			$resultado=$classReport->GetExecResultado();
			unset($classReport);
			return true;
		}
	
    	/*
	 * Funcion donde se genera la entrega final si la persona que ha reclamado es diferente al paciente
	   * @return boolean
    */  
		function GenerarEntrega()
		{
			$request = $_REQUEST;
			$observacion=SessionGetVar("observar");

			$tipo=$request['tipo'];
			$documento=$request['doc'];
			$nombre=$request['nombre'];
			$evolucion=$request['evolucion'];
			$entrega=SessionGetVar("entrega");
			$empresa = SessionGetVar("DatosEmpresaAF");
			$empre=$empresa['empresa_id'];
			$centro=$empresa['centro_utilidad'];
			$bodega=$empresa['bodega'];

			$tipo_id_paciente=SessionGetVar("tipo_paciente");
			$paciente_id=SessionGetVar("pacie_id");
			$empresa_nombre=$empresa['descripcion1'];
			$centro_nombre=$empresa['descripcion2'];
			$bodega_nombre=$empresa['descripcion4'];
			$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
			$ParametrizacionReformular= ModuloGetVar('','','ParametrizacionReformular');
			$Paciente=$mdl->DatosPaciente($tipo_id_paciente,$paciente_id);
			$Primer_nombre=$Paciente ['0']['primer_nombre'];
			$primer_apellido=$Paciente ['0']['primer_apellido'];
			$segundo_nombre=$Paciente ['0']['segundo_nombre'];
			$segundo_apellido=$Paciente ['0']['segundo_apellido'];
			$nombrepaciente=$Primer_nombre." ".$segundo_nombre." ".$primer_apellido." ".$segundo_apellido;
	    if($request['variable']!=1)
			{
				$totaldespachado=0;
				$totalpendientes=0;
				$cantidadpendiente=0;
			  foreach($entrega as $k1 => $dt1)
				{
					foreach($dt1 as $k2 => $dt2)
					{
						foreach($dt2 as $k3 => $dt3)
						{
							foreach($dt3 as $k4 => $dt4)
							{
								if($dt4['entrega'] > 0 && $dt4['entrega']!="")
								{
									$fdatos=explode("/",$k3);
									$fedatos= $fdatos[2]."-".$fdatos[1]."-".$fdatos[0];
									$exitenciasfv=$mdl->ConsultarExistenciasfv($empre,$centro,$k2,$bodega,$fedatos,$k4);
									$consultarInformacionBodega=$mdl->consultarInformacionBodega($empre,$centro,$k2,$bodega);
									$existenciasBfv=$exitenciasfv[0]['existencia_actual'];
									$existenciabodega=$consultarInformacionBodega[0]['existencia'];
									$existencia_actual=$existenciasBfv - $dt4['entrega'];
									$existencia_ac=$existenciabodega - $dt4['entrega'];
									$totaldespachado=$totaldespachado + $dt4['entrega'];
									$actualexiste=$mdl->UpdateExistenciasfv($empre,$centro,$k2,$bodega,$fedatos,$k4,$existencia_actual);
									$actuaExiBodega=$mdl->UpdateExistencias_Bodegas($empre,$centro,$k2,$bodega,$existencia_ac);
								}
							}
						}
					}
					$cantidadpendiente=($dt4['totalmedicamento'])- $totaldespachado;
					if($cantidadpendiente>0)
					{
						$pendientes=$mdl->Pendientes_X_DispensacionESM($empre,$centro,$bodega,$paciente_id,$tipo_id_paciente,$dt4['original'],$cantidadpendiente,$dt4['unidad'],$evolucion);
					}
					$totaldespachado=0;
							
				}	
				$desp = AutoCarga::factory('DispensacionESMMedicamentos');
				$opcion=1;
			
				$bodegas_doc_id= ModuloGetVar('app','DispensacionESM','Egreso_DispensacionESM_farmacia_'.$empre);
				$numero= $desp->AsignarNumeroDocumentoDespacho($bodegas_doc_id);
				$datos2 = $desp->Seleccionbodegas_doc_numeraciones($empre,$centro,$bodega,$bodegas_doc_id);
				$numeracion=$datos2['0']['numeracion'];
				$Datopciones = $desp->MenuOpcion($opcion,$tipo_id_paciente,$paciente_id,$empre,$bodega,$bodegas_doc_id,$numeracion,$ParametrizacionReformular,$datos,$observacion,$entrega,$nombrepaciente,$tipo,$documento,$nombre,$evolucion);
			}																																				
			$informacion=$mdl->ConsultarInformacionPediente($paciente_id,$tipo_id_paciente,$evolucion);

            $action['pos']=ModuloGetURL('app','DispensacionESM','controller','ReportePosPendiente',array("evolucion"=>$evolucion,"paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente));
			$action['medica_carta']=ModuloGetURL('app','DispensacionESM','controller','GenerarEntrega',array("variable"=>1,"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"evolucion"=>$evolucion));
	

	    if($request['variable']==1)
			{
					IncludeLib("reportes/DispensacionESMMedicamentosPendientes");
                    
						GenerarReportePendiente($empresa,$Paciente,$informacion);
                                      
						$RUTA = $_ROOT ."cache/DispensacionESMMedicamentosPendientes.pdf";
                        $DIR="printer.php?ruta=$RUTA";
                        $RUTA1= GetBaseURL() . $DIR;
                        $mostrar ="\n<script language='javascript'>\n";
                        $mostrar.="var rem=\"\";\n";
                        $mostrar.="  function abreVentana(){\n";
                        $mostrar.="    var url2=\"\"\n";
                        $mostrar.="    var width=\"400\"\n";
                        $mostrar.="    var height=\"300\"\n";
                        $mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
                        $mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
                        $mostrar.="    var nombre=\"Printer_Mananger\";\n";
                        $mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
                        $mostrar.="    var url2 ='$RUTA1';\n";
                        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
                        $mostrar.="</script>\n";
                        $this->salida.="$mostrar";
                        $this->salida.="<BODY onload=abreVentana();>";
      }
			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas");
			$this->salida = $act->FormaPintarUltimoPaso($action,$tipo_id_paciente,$paciente_id,$Primer_nombre,$segundo_nombre,$primer_apellido,$segundo_apellido,$informacion,$empresa,$evolucion);
		
			return true;
		}
		
		/*
    * Funcion que permite  detallar lo que se le a entregado al paciente 
    * @return boolean
    */	
		function DetalleEntrega()
    {
      $request = $_REQUEST;
     
      $tipo_id_paciente=$request['tipo_id_paciente'];
      $paciente_id=$request['paciente_id'];
      $codigo_medicamento=$request['codigo_medicamento'];
	  $evolucion=$request['evolucion'];
   
   /*   if($request['fecha_formulacion'])
      SessionSetVar("fec_formulacion",$request['fecha_formulacion']);
      $fecha_formulacion=SessionGetVar("fec_formulacion");*/
    
      $mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
	  
	  
      $Maxi = $mdl->ConsultarUltimoResg($tipo_id_paciente,$paciente_id,$codigo_medicamento,$evolucion);
      $maximo=$Maxi['0']['maxi'];
	  $MaxiNR = $mdl->ConsultarUltimoResgNoDespachado($tipo_id_paciente,$paciente_id,$codigo_medicamento,$evolucion);
      $maximoNR=$MaxiNR['0']['maxi'];
	  $Paciente=$mdl->DatosPaciente($tipo_id_paciente,$paciente_id);
      $Primer_nombre=$Paciente ['0']['primer_nombre'];
      $segundo_nombre=$Paciente ['0']['segundo_nombre'];
      $primer_apellido=$Paciente ['0']['primer_apellido'];
      $segundo_apellido=$Paciente ['0']['segundo_apellido'];
    
      $si_esta=$mdl->Consultarhc_formuladesp_medicamentos_id($maximo,$tipo_id_paciente,$paciente_id,$codigo_medicamento,$evolucion);
      $si_estaNR=$mdl->Consultarhc_formuladesp_medicamentos_idNR($maximoNR,$tipo_id_paciente,$paciente_id,$codigo_medicamento,$evolucion);

	  $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
      $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas",array("tipo_id_paciente"=>$request['tipo_id_paciente'],"paciente_id"=>$request['paciente_id']));
      $this->salida = $act->FormaPintarDetalle($action,$si_esta,$tipo_id_paciente,$paciente_id,$Primer_nombre,$segundo_nombre,$primer_apellido,$segundo_apellido,$si_estaNR);
      return true;
    }
   
    /* Funcion que permite Mostrar el Menu para los pendientes del paciente
	*@return boolean
	*/
	
	function MenuPendientes()
	{
	        $request = $_REQUEST;
		
			$empresa = SessionGetVar("DatosEmpresaAF");
      	    if($request['tipo_id_paciente'])
			SessionSetVar("tipo_paciente",$request['tipo_id_paciente']);
			$tipo_id_paciente=SessionGetVar("tipo_paciente");

			if($request['paciente_id'])
			SessionSetVar("pacie_id",$request['paciente_id']);
			$paciente_id=SessionGetVar("pacie_id");
			$evolucion=$request['evolucion_id'];
			$action['r_evento'] = ModuloGetURL("app", "DispensacionESM", "controller", "Registro_pendiente",array("datos"=>$request));
			$action['b_evento'] = ModuloGetURL("app", "DispensacionESM", "controller", "Buscar_pendiente",array("datos"=>$request));
			
			$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
			$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas");
			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$this->salida = $act->FormaMenuPendiente($action,$request,$paciente);
			return true;
	}
	/**/
	function Registro_pendiente()
	{
		$request = $_REQUEST;
	    $datos=$request['datos'];
	    $tipo=$datos['paciente_id'];
	
		$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
		$paciente = $mdl->Consultar_DatosA_Paciente($datos);
	    $bandera=0;
		$action['entrega'] = ModuloGetURL("app", "DispensacionESM", "controller", "Registro_pendiente",array("datos"=>$datos));
        $informacion=$mdl->ConsultarInformacionPediente($datos['paciente_id'],$datos['tipo_id_paciente'],$datos['evolucion_id']);
        $msm=" ";
		if($request['bandera']==1)
		{
		   $bandera=1;
		   $evento = $mdl->Registrar_Evento($datos,$request,$informacion);
		   if($evento==true)
		   {
		     $msm= " <td colspan=\"3\" align=\"left\" class=\"formulacion_table_list\" > SE REGISTRO EL EVENTO  </td> ";
		   }
		  
		}
     
		$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "MenuPendientes",array("tipo_id_paciente"=>$datos['tipo_id_paciente'],"paciente_id"=>$datos['paciente_id'],"apellidos"=>$datos['apellidos'],"nombres"=>$datos['nombres'],"fecha_formulacion"=>$datos['fecha_formulacion'],"fecha_finalizacion"=>$datos['fecha_finalizacion'],"nombre"=>$datos['nombre'],"evolucion_id"=>$datos['evolucion_id'],"plan_descripcion"=>$datos['plan_descripcion']));
		$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
		$this->salida = $act->FormaRegistrarEvento($action,$datos,$paciente,$informacion,$bandera,$msm);
	    return true;
	
	}
	/**/
	function Buscar_pendiente()
	{
	   $request = $_REQUEST;
	   $datos=$request['datos'];
	
		$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
        $paciente = $mdl->Consultar_DatosA_Paciente($datos);
		IncludeFileModulo("DispensacionESM","RemoteXajax","app","DispensacionESM");
		$this->SetXajax(array("MostrarMensajes","VerificarDispensacionESM","EntregaProductosFormulaPendientes","InformacionTemporal","InsertarInformacionTemporal","EliminarInformacionTemporal","Eliminarporcompletot"),null,"ISO-8859-1");
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");
		$evento=$mdl->ConsultarEventoActivo($datos['paciente_id'],$datos['tipo_id_paciente'],$datos['evolucion_id']);
		$informacion=$mdl->ConsultarInformacionPediente($datos['paciente_id'],$datos['tipo_id_paciente'],$datos['evolucion_id']);
		$action['entrega'] = ModuloGetURL("app", "DispensacionESM", "controller", "InformacionEntregaPendiente",array("tipo_id_paciente"=>$datos['tipo_id_paciente'],"paciente_id"=>$datos['paciente_id'],"evolucion_id"=>$datos['evolucion_id'],"datos"=>$datos));
	
	    $action['n_reclama'] = ModuloGetURL("app", "DispensacionESM", "controller", "InformacionNoReclamaPaciente",array("tipo_id_paciente"=>$datos['tipo_id_paciente'],"paciente_id"=>$datos['paciente_id'],"evolucion_id"=>$datos['evolucion_id']));
	    $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "MenuPendientes",array("tipo_id_paciente"=>$datos['tipo_id_paciente'],"paciente_id"=>$datos['paciente_id'],"apellidos"=>$datos['apellidos'],"nombres"=>$datos['nombres'],"fecha_formulacion"=>$datos['fecha_formulacion'],"fecha_finalizacion"=>$datos['fecha_finalizacion'],"nombre"=>$datos['nombre'],"evolucion_id"=>$datos['evolucion_id'],"plan_descripcion"=>$datos['plan_descripcion']));
		$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
		$this->salida = $act->FormaBuscar_pendiente($action,$datos,$paciente,$informacion,$evento);

	    return true;
	}

	/*
	  * Funcion que permite realizar la entrega de los productos pendientes
	  * @return boolean
    */
	
		function InformacionEntregaPendiente()
		{
			$request = $_REQUEST;
		    $empresa = SessionGetVar("DatosEmpresaAF");
			$empre=$empresa['empresa_id'];
			$centro=$empresa['centro_utilidad'];
			$bodega=$empresa['bodega'];
			$datos=$request['datos'];
			if($request['entrega'])
			SessionSetVar("entrega",$request['entrega']);
			$entrega=SessionGetVar("entrega");
      
			$tipo_id_paciente=$request['tipo_id_paciente'];
			$paciente_id=$request['paciente_id'];
             $evolucion=$request['evolucion_id'];
			$empresa_nombre=$empresa['descripcion1'];
			$centro_nombre=$empresa['descripcion2'];
            $bodega_nombre=$empresa['descripcion4'];
		   
			IncludeFileModulo("DispensacionESM","RemoteXajax","app","DispensacionESM");
			$this->SetXajax(array("PacienteReclamaPendiente","PersonaRclamaPendiente","ValidarDatosPersona"),"app_modules/DispensacionESM/RemoteXajax/DispensacionESM.php","ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			
			$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
			$Paciente=$mdl->DatosPaciente($tipo_id_paciente,$paciente_id);
			$Primer_nombre=$Paciente ['0']['primer_nombre'];
			$segundo_nombre=$Paciente ['0']['segundo_nombre'];
			$primer_apellido=$Paciente ['0']['primer_apellido'];
			$segundo_apellido=$Paciente ['0']['segundo_apellido'];
			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "Buscar_pendiente",array("datos"=>$datos));
			$action['entrega'] = ModuloGetURL("app", "DispensacionESM", "controller", "GenerarEntregaMedicamentosPendientes",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"evolucion_id"=>$evolucion));
			
			
			$this->salida = $act->PintarTablaPendiente($action,$empresa_nombre,$centro_nombre,$bodega_nombre,$inf,$paciente_id,$tipo_id_paciente,$Primer_nombre,$segundo_nombre,$primer_apellido,$segundo_apellido,$entrega);
			return true;

		}
	
	/*
	   * Funcion que permite generar el despacho de los medicamentos pendientes 
	   * @return boolean
    */
		function GenerarEntregaMedicamentosPendientes()
		{
        $request = $_REQUEST;
        $observacion=$request['observar'];
        $entrega=SessionGetVar("entrega");

        $empresa = SessionGetVar("DatosEmpresaAF");
        $empre=$empresa['empresa_id'];
        $centro=$empresa['centro_utilidad'];
        $bodega=$empresa['bodega'];

		$tipo_id_paciente=$request['tipo_id_paciente'];
		$paciente_id=$request['paciente_id'];
		$evolucion=$request['evolucion_id'];

        $empresa_nombre=$empresa['descripcion1'];
        $centro_nombre=$empresa['descripcion2'];
        $bodega_nombre=$empresa['descripcion4'];
		   
		$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
		$ParametrizacionReformular= ModuloGetVar('','','ParametrizacionReformular');
		$Paciente=$mdl->DatosPaciente($tipo_id_paciente,$paciente_id);
		$Primer_nombre=$Paciente ['0']['primer_nombre'];
		$primer_apellido=$Paciente ['0']['primer_apellido'];
		$segundo_nombre=$Paciente ['0']['segundo_nombre'];
		$segundo_apellido=$Paciente ['0']['segundo_apellido'];
		$nombrepaciente=$Primer_nombre." ".$segundo_nombre." ".$primer_apellido." ".$segundo_apellido;

	     if($request['variable']!=1)
			{
				$totaldespachado=0;
				$totalpendientes=0;
				$cantidadpendiente=0;
			
				foreach($entrega as $k1 => $dt1)
				{
				
					foreach($dt1 as $k2 => $dt2)
					{
				
						foreach($dt2 as $k3 => $dt3)
						{
							foreach($dt3 as $k4 => $dt4)
							{
								if($dt4['entrega'] > 0 && $dt4['entrega']!="")
								{
							
									$fdatos=explode("/",$k3);
									$fedatos= $fdatos[2]."-".$fdatos[1]."-".$fdatos[0];
						    						
									$exitenciasfv=$mdl->ConsultarExistenciasfv($empre,$centro,$k2,$bodega,$fedatos,$k4);
									$consultarInformacionBodega=$mdl->consultarInformacionBodega($empre,$centro,$k2,$bodega);
									$existenciasBfv=$exitenciasfv[0]['existencia_actual'];
									$existenciabodega=$consultarInformacionBodega[0]['existencia'];
									$existencia_actual=$existenciasBfv - $dt4['entrega'];
									$existencia_ac=$existenciabodega - $dt4['entrega'];
									$totaldespachado=$totaldespachado + $dt4['entrega'];
          						
									$actualexiste=$mdl->UpdateExistenciasfv($empre,$centro,$k2,$bodega,$fedatos,$k4,$existencia_actual);
									$actuaExiBodega=$mdl->UpdateExistencias_Bodegas($empre,$centro,$k2,$bodega,$existencia_ac);
								}
							}
						}
					}
					
					$actualizacion=$mdl->ActualizarEstadoPendientes($tipo_id_paciente,$paciente_id,$dt4['original']);
					$cantidadpendiente=($dt4['totalmedicamento'])- $totaldespachado;
					if($cantidadpendiente>0)
					{
					$pendientes=$mdl->Pendientes_X_DispensacionESM($empre,$centro,$bodega,$paciente_id,$tipo_id_paciente,$dt4['original'],$cantidadpendiente,$dt4['unidad'],$evolucion,$k2,$totaldespachado);
						
					}
	                
					$totaldespachado=0;
				}	
							
				$desp = AutoCarga::factory('DispensacionESMMedicamentos');
				$opcion=2;
			
				$bodegas_doc_id= ModuloGetVar('app','DispensacionESM','Egreso_DispensacionESM_farmacia_'.$empre);
				$numero= $desp->AsignarNumeroDocumentoDespacho($bodegas_doc_id);
				$datos2 = $desp->Seleccionbodegas_doc_numeraciones($empre,$centro,$bodega,$bodegas_doc_id);
				$numeracion=$datos2['0']['numeracion'];
			
				$Datopciones = $desp->MenuOpcion($opcion,$tipo_id_paciente,$paciente_id,$empre,$bodega,$bodegas_doc_id,$numeracion,$ParametrizacionReformular,$datos,$observacion,$entrega,$nombrepaciente,$tipo,$documento,$nombre,$evolucion);
			}
			
			$evento=$mdl->CerrarEventoPaciente($request);
			$informacion=$mdl->ConsultarInformacionPediente($paciente_id,$tipo_id_paciente,$evolucion);
			
			$action['pos']=ModuloGetURL('app','DispensacionESM','controller','ReportePosPendiente',array("evolucion"=>$evolucion,"paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente));
			$action['medica_carta']=ModuloGetURL('app','DispensacionESM','controller','GenerarEntregaMedicamentosPendientes',array("variable"=>1,"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"evolucion_id"=>$evolucion));
		
		
			if($request['variable']==1)
			{
						IncludeLib("reportes/DispensacionESMMedicamentosPendientes");
                    
						GenerarReportePendiente($empresa,$Paciente,$informacion);
                                      
						$RUTA = $_ROOT ."cache/DispensacionESMMedicamentosPendientes.pdf";
                        $DIR="printer.php?ruta=$RUTA";
                        $RUTA1= GetBaseURL() . $DIR;
                        $mostrar ="\n<script language='javascript'>\n";
                        $mostrar.="var rem=\"\";\n";
                        $mostrar.="  function abreVentana(){\n";
                        $mostrar.="    var url2=\"\"\n";
                        $mostrar.="    var width=\"400\"\n";
                        $mostrar.="    var height=\"300\"\n";
                        $mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
                        $mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
                        $mostrar.="    var nombre=\"Printer_Mananger\";\n";
                        $mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
                        $mostrar.="    var url2 ='$RUTA1';\n";
                        $mostrar.="    rem = window.open(url2, nombre, str)};\n";
                        $mostrar.="</script>\n";
                        $this->salida.="$mostrar";
                        $this->salida.="<BODY onload=abreVentana();>";
      }
			
			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas");
			$this->salida .= $act->FormaPintarUltimoPaso($action,$tipo_id_paciente,$paciente_id,$Primer_nombre,$segundo_nombre,$primer_apellido,$segundo_apellido,$informacion,$empresa,$evolucion);
			return true;
		}		
	
		function InformacionNoReclamaPaciente()
		{
			$request = $_REQUEST;
		    $empresa = SessionGetVar("DatosEmpresaAF");
			$empre=$empresa['empresa_id'];
			$centro=$empresa['centro_utilidad'];
			$bodega=$empresa['bodega'];
		
      
			$tipo_id_paciente=$request['tipo_id_paciente'];
			$paciente_id=$request['paciente_id'];
            $evolucion=$request['evolucion_id'];
			$empresa_nombre=$empresa['descripcion1'];
			$centro_nombre=$empresa['descripcion2'];
            $bodega_nombre=$empresa['descripcion4'];
		   
		     $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas");
			
			
			$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
			$evento=$mdl->CerrarEventoPaciente($request);
			$Noreclama=$mdl->PacienteNoReclamaActualizar($request);
			if($Noreclama==true)
			{
                 $msm=" LOS PENDIENTES NO FUERON RECLAMADOS POR EL PACIENTE. ";

			}
			else
			{
			     $msm=" ERROR AL MOMENTO DE ACTUALIZAR LOS PENDIENTES ";

			
			}
			
			$this->salida = $act->MensajePacienteNoReclama($action,$msm);
    		return true;

		}
	 /**/
	 function PacienteNoReclamaDatos()
	 {
	        $request = $_REQUEST;
	        $empresa = SessionGetVar("DatosEmpresaAF");
			$empre=$empresa['empresa_id'];
			$centro=$empresa['centro_utilidad'];
			$bodega=$empresa['bodega'];
		
      		$tipo_id_paciente=$request['tipo_id_paciente'];
			$paciente_id=$request['paciente_id'];
            $evolucion=$request['evolucion_id'];
			$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
			$ParametrizacionReformular= ModuloGetVar('','','ParametrizacionReformular');
			$Paciente=$mdl->DatosPaciente($tipo_id_paciente,$paciente_id);
			
			$action['entrega'] = ModuloGetURL("app", "DispensacionESM", "controller", "PacienteNReclamaTotal",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"evolucion_id"=>$evolucion));

			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas");
			$this->salida = $act->FormaNoreclama_paciente($action,$Paciente);
			return true;

	 }
	function PacienteNReclamaTotal()
	{
		$request = $_REQUEST;
		
		$empresa = SessionGetVar("DatosEmpresaAF");
		$empre=$empresa['empresa_id'];
		
		$centro=$empresa['centro_utilidad'];
		$bodega=$empresa['bodega'];
        $observar=$request['observar'];
		$tipo_id_paciente=$request['tipo_id_paciente'];
		$paciente_id=$request['paciente_id'];
		$evolucion=$request['evolucion_id'];
		$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
		$datos=$mdl->SelecrHc_formulacion_despachos_medicamentos($tipo_id_paciente,$paciente_id);
	
		$variableDias= ModuloGetVar('','','ParametrizacionReformular');
		
		$Paciente=$mdl->DatosPaciente($tipo_id_paciente,$paciente_id);
		foreach($datos as $k1 => $dt1)
		{
		        if($dt1['unidad_perioricidad_entrega']=='dia(s)')
                    {
                      $dias=$dt1['tiempo_perioricidad_entrega']-$variableDias;
                    }
                    else
                    {
                           if($dt1['unidad_perioricidad_entrega']=='semana(s)')
                           {
                             $semana=7;
                             $total_dias =$dt1['tiempo_perioricidad_entrega'] * $semana;
                             $dias=$total_dias-$variableDias;
                         
                           }
                           else
                           {
                               if($dt1['unidad_perioricidad_entrega']=='mes(es)')
                               {
                                 $mes=30;
                                 $total_dias =$dt1['tiempo_perioricidad_entrega'] * $mes;
                                 $dias=$total_dias-$variableDias;
                              
                               }
                               else
                               {
                               
                                 $año=365;
                                 $total_dias =$dt1['tiempo_perioricidad_entrega'] * $año;
                                 $dias=$total_dias-$variableDias;
                               
                               }
                           }
                    }
				if($dt1['fecha_formulacion']==$dt1['fecha_proxima_entrega'])
				{
				
					$dt1['fecha_formulacion'];
					$fdatos = explode("-", $dt1['fecha_formulacion']);
					$fecha_formula= $fdatos[2]."-".$fdatos[1]."-".$fdatos[0];
                                         
					list($a,$m,$d)=split("-", $dt1['fecha_formulacion']);

					$fecha_Entrega = date("Y-m-d",(mktime(0,0,0, $m,($d+$variableDias),$a)));

					list($a,$m,$d) = split("-",$fecha_Entrega);

					$tdias=$dias + $variableDias;
					$fecha_proxima = date("Y-m-d",(mktime(0,0,0, $m,($d + $tdias),$a)));

                }
				else 
				{
								 
					list($a,$m,$d) = explode("-", $dt1['fecha_proxima_entrega']);
					
					
					$fecha_Entrega = date("Y-m-d",(mktime(0,0,0, $m,($d+ $variableDias),$a)));
					$tdias= $dias + $variableDias;
					list($a,$m,$d) = split("-",$fecha_Entrega);
					$fecha_proxima = date("Y-m-d",mktime(0,0,0, $m,($d +$tdias),$a));
				}
			$info=$mdl->Insertarhc_formulacion_despachos_medicamentos($tipo_id_paciente,$paciente_id,$evolucion,$dt1['codigo_medicamento_formulado'],$fecha_Entrega,$fecha_proxima,$empre,$observar,$dt1['cantidad_entrega']);

					
		}
			$dat =$mdl->EliminarTodoTemporal($tipo_id_paciente,$paciente_id);
		
	    $act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
		$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas");
		$this->salida = $act->FormaMensajeNoReclamaPaciente($action,$Paciente);
	
	    return true;
	}
	
	
	
   /*
	  * Funcion que permite Mostrar Todos los medicamentos Pendientes del paciente
	  * @return boolean
    */
	/*	function FormulasPacientePendiente()
		{
			$request = $_REQUEST;
			$empresa = SessionGetVar("DatosEmpresaAF");
      	     if($request['tipo_id_paciente'])
			SessionSetVar("tipo_paciente",$request['tipo_id_paciente']);
			$tipo_id_paciente=SessionGetVar("tipo_paciente");

			if($request['paciente_id'])
			SessionSetVar("pacie_id",$request['paciente_id']);
			IncludeFileModulo("DispensacionESM","RemoteXajax","app","DispensacionESM");
			$this->SetXajax(array("MostrarMensajes","VerificarDispensacionESM","EntregaProductosFormulaPendientes","InformacionTemporal","InsertarInformacionTemporal","EliminarInformacionTemporal","Eliminarporcompletot"),null,"ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			
			$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
			$fedatos= date("Y-m-d");
			$request['fecha'] = date("Y-m-d");
			$paciente = $mdl->ObtenerFormulasMedicas($request);
			$datos=$mdl->ConsultarInformacionPediente($request['paciente_id'],$request['tipo_id_paciente']);
			
			$action['entrega'] = ModuloGetURL("app", "DispensacionESM", "controller", "InformacionEntregaPendiente");
			$action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "BuscardorDeFormulas");
			$act = AutoCarga::factory("DispensacionESMHTML", "views", "app", "DispensacionESM");
			$this->salida = $act->FormaFomulaPacientePendiente($action,$request,$datos,$paciente[0]);
			return true;
		}
	*/
	
	} 
?>