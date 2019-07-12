<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: app_Formulacion_ExternaESM_controller.php,v 1.0
	* @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	/**
	* Clase Control: Formulacion_ExternaESM
	* Clase encargada del control de llamado de metodos en el modulo
	*
	* @package IPSOFT-SIIS
	/*/
  IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");
  //IncludeClass("DispensacionMedicamentos");
	class app_Formulacion_ExternaESM_controller  extends classModulo
	{
    /**
		* Constructor de la clase
    */
    function app_Formulacion_ExternaESM_controller(){}
	/**
	* Funcion principal del modulo
	* @return boolean
		*/
		function main()
		{
			$request = $_REQUEST;
			$obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
			$permisos = $obje->ObtenerPermisos();    
			
			$ttl_gral = "FORMULACION MEDICAMENTOS ";
			$mtz[0] = 'FARMACIAS';
			$url[0] = 'app';
			$url[1] = 'Formulacion_ExternaESM'; 
			$url[2] = 'controller';
			$url[3] = 'Menu_Formulacion'; 
			$url[4] = 'Formulacion_ExternaESM'; 
			
			$action['volver'] = ModuloGetURL('system', 'Menu');
			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
			return true;
		}
    /*
	    * Funcion que permite ir a l menu del modulo de Formulacion externa  Esm
	    * @return boolean
	    */
    
     function Menu_Formulacion()
		{
        $request = $_REQUEST;
        if($request['Formulacion_ExternaESM']) SessionSetVar("DatosEmpresaAF",$request['Formulacion_ExternaESM']);
        $datos_empresa = SessionGetVar("DatosEmpresaAF");
        
        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        if(!empty($request['orden_id']))
        {
            $obje->Eliminar_tmp_suministro($request['orden_id']);
        }

        $empresa=$datos_empresa['empresa_id'];
        
        $permisos = $obje->ObtenerPermisos_FORMULACION($empresa);  
        $permisos2 = $obje->ObtenerPermisos_SUMINISTROS($empresa); 			

        $action['farmacovigilancia'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Menu_Farmacovigilancia");
        $action['FormulaInt'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Buscar_Pacientes_Formulas",array("opcion"=>0));
        $action['FormulaExt'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Buscar_Pacientes_Formulas",array("opcion"=>1));
        $action['suministros'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Suministros_esm",array("permisos2"=>$permisos2));
        $action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "main");
        $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
        $this->salida = $act->FormaMenu($action,$permisos,$permisos2);
        return true;
		}
    /**
    * Funcion  para el menu de farmacovigilancia 
    * @return boolean
		*/
      function Menu_Farmacovigilancia()
      {
          $request = $_REQUEST;
          $datos_empresa = SessionGetVar("DatosEmpresaAF");

          $action['registro_farmacovigilancia'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Buscar_Pacientes");
          $action['buscar_registro'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Buscar_Registro_Pacientes");

          $action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Menu_Formulacion");
          $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
          $this->salida = $act->FormaMenuFarmacovigilancia($action);
          return true;
	    }
    /**
    * Funcion para registrar la informacion de farmacovigilancia permite realizar la buisqueda del paciente al cual se le realizara el reporte 
    * @return boolean
		*/
    function Buscar_Pacientes()
	  {
        $request = $_REQUEST;
        $datos_empresa = SessionGetVar("DatosEmpresaAF");
        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        $TipoIdent = $obje->ConsultarTipoId();    
        $conteo =$pagina=0;
        $datos=$obje->Consultar_Datospacientes($request['buscador'],$request['offset']);
        $action['buscador']=ModuloGetURL('app','Formulacion_ExternaESM','controller','Buscar_Pacientes');
        $conteo= $obje->conteo;
        $pagina= $obje-> pagina;
        $action['paginador'] = ModuloGetURL('app', 'Formulacion_ExternaESM', 'controller', 'Buscar_Pacientes',array("buscador"=>$request['buscador']));
        $action['registro'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Plantilla_Registro");
        $action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Menu_Farmacovigilancia");
        $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
        $this->salida = $act->formaRegistrarPacientes($action,$TipoIdent,$request['buscador'],$datos,$conteo,$pagina);
        return true;
	  }
    /**
    * Funcion  para  registrar la infomacion de farmacovigilancia
    * @return boolean
		*/
	  function Plantilla_Registro()
	  {
          $request = $_REQUEST;
          $paciente_id=$request['paciente_id'];
          $tipo_id_paciente=$request['tipo_id_paciente'];
          $apellidos=$request['apellidos'];
          $nombres=$request['nombres'];
          $sexo=$request['sexo_id'];
          $datos_empresa = SessionGetVar("DatosEmpresaAF");

          $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
          $instuticion = $obje->consultar_Instituciones();  
          $action['registrar'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Medicamentos_Farmacovigilancia",array("paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente,"apellidos"=>$apellidos,"nombres"=>$nombres,"sexo"=>$sexo));
          $action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Buscar_Pacientes",array("paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente,"apellidos"=>$apellidos,"nombres"=>$nombres,"sexo"=>$sexo));
          $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
          $this->salida = $act->formaRegistrarPacientes_Plantilla($action,$instuticion,$paciente_id,$tipo_id_paciente,$apellidos,$nombres,$sexo,$request);
          return true;
	  }
           
   /**
    * Funcion  para  ingresar la informacion segun los medicamentos dispensados con lote y fecha de vencimiento
	* @return boolean
	*/
    function Medicamentos_Farmacovigilancia()
	 {
        $request = $_REQUEST;
        $datos_empresa = SessionGetVar("DatosEmpresaAF");
        $paciente_id=$request['paciente_id'];
        $tipo_id_paciente=$request['tipo_id_paciente'];
        $apellidos=$request['apellidos'];
        $nombres=$request['nombres'];
        $sexo=$request['sexo_id'];
        $fecha_notifica=$request['fecha_notifica'];
        $institucion=$request['institucion'];
        $formula=$request['formula'];
        $reacciones=$request['reacciones'];
        $fecha_sospecha=$request['fecha_sospecha'];

        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        $Formula_info=$obje->Consultar_Identificador_formula($formula,$tipo_id_paciente,$paciente_id);
        $formula_id=$Formula_info[0]['formula_id'];
        $datos=$obje->Medicamentos_Dispensados_Esm_x_lote_total($formula_id);

        $action['buscador']=ModuloGetURL('app','Formulacion_ExternaESM','controller','Medicamentos_Farmacovigilancia',array("paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente,"apellidos"=>$apellidos,"nombres"=>$nombres,"sexo"=>$sexo,"fecha_notifica"=>$fecha_notifica,"institucion"=>$institucion,"formula"=>$formula,"reacciones"=>$reacciones,"fecha_sospecha"=>$fecha_sospecha));
        $conteo= $obje->conteo;
        $pagina= $obje-> pagina;
        $action['paginador'] = ModuloGetURL('app', 'Formulacion_ExternaESM', 'controller', 'Medicamentos_Farmacovigilancia',array("buscador"=>$request['buscador'],"paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente,"apellidos"=>$apellidos,"nombres"=>$nombres,"sexo"=>$sexo,"fecha_notifica"=>$fecha_notifica,"institucion"=>$institucion,"formula"=>$formula,"reacciones"=>$reacciones,"fecha_sospecha"=>$fecha_sospecha));
        $action['registrar'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Insertar_Informacion_Farmacovigilancia",array("paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente,"apellidos"=>$apellidos,"nombres"=>$nombres,"sexo"=>$sexo,"fecha_notifica"=>$fecha_notifica,"institucion"=>$institucion,"formula"=>$formula,"reacciones"=>$reacciones,"fecha_sospecha"=>$fecha_sospecha,"formula_id"=>$formula_id));

        $action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Plantilla_Registro",array("paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente,"apellidos"=>$apellidos,"nombres"=>$nombres,"sexo"=>$sexo));
        $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
        $this->salida = $act->formaRegistrarPacientes_medicamentos($action,$instuticion,$request['buscador'],$datos,$conteo,$pagina,$formula_id);

        return true;
	}
   /**
	* Funcion  para  ingresar la informacion  registrada por paciente 
	* @return boolean
		*/
   function Insertar_Informacion_Farmacovigilancia()
   {
        $request = $_REQUEST;
        $datos_empresa = SessionGetVar("DatosEmpresaAF");
        $paciente_id=$request['paciente_id'];
        $tipo_id_paciente=$request['tipo_id_paciente'];
        $apellidos=$request['apellidos'];
        $nombres=$request['nombres'];
        $sexo=$request['sexo_id'];
        $fecha_notifica=$request['fecha_notifica'];
        $institucion=$request['institucion'];
        $formula=$request['formula'];
        $reacciones=$request['reacciones'];
        $fecha_sospecha=$request['fecha_sospecha'];
        $formula_id=$request['formula_id'];
        $observaciones=$request['observaciones'];
        $diagnostico=$request['diagnostico'];

        $mdl = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        $bandera=0;
        $cantidad_registro=$request['cantidad_registros'];
        for($co=0;$co<$cantidad_registro;$co++)
        {
          if(!empty($request[$co]))
          {
              $bandera=$bandera+1;
          }
			
        }
        if($bandera>0)
        {
          $ingreso_farmacovigilancia=$mdl->ingreso_farmacovigilancia($request);
          $esm_farmaco_id=$ingreso_farmacovigilancia['esm_farmaco_id'];	
        }
        $cantidad_registro=$request['cantidad_registros'];
        for($cont=0;$cont<$cantidad_registro;$cont++)
        {
          if(!empty($request[$cont]))
          {
            $observa=$request['observa'.$cont];
            $fecha_in=$request['fecha_in'.$cont];
            $fecha_fin=$request['fecha_fin'.$cont];
            $dosis=$request['dosis'.$cont];
            $fecha=$request['fecha_v'.$cont];
            $lote=$request['lote'.$cont];
            $producto=$request[$cont];
				    $mdl->Ingreso_Farmacovigilancia_id($esm_farmaco_id,$observa,$fecha_in,$fecha_fin,$producto,$lote,$fecha,$dosis);
						}
				}
      $action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Menu_Farmacovigilancia");
      $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
      $this->salida = $act->FormaMensajegenerado($action,$esm_farmaco_id,$formula_id);
      return true;
   }
   /*
	* Funcion  para  Buscar los registros que se han realizado a los diferentes pacientes
	* @return boolean
		*/
   function Buscar_Registro_Pacientes()
   {
        $request = $_REQUEST;
        $datos_empresa = SessionGetVar("DatosEmpresaAF");
        $mdl = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        $conteo= $mdl->conteo;
        $pagina= $mdl-> pagina;

        if(!empty($request['buscador']))
        {
              $datos=$mdl->consulta_Informacion($request['buscador'],$request['offset']);
              $action['buscador']=ModuloGetURL('app','Formulacion_ExternaESM','controller','Buscar_Registro_Pacientes');
              $conteo= $mdl->conteo;
              $pagina= $mdl-> pagina;
        }
        $action['paginador'] = ModuloGetURL('app', 'Formulacion_ExternaESM', 'controller', 'Buscar_Registro_Pacientes',array("buscador"=>$request['buscador'],"paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente,"apellidos"=>$apellidos,"nombres"=>$nombres,"sexo"=>$sexo,"fecha_notifica"=>$fecha_notifica,"institucion"=>$institucion,"formula"=>$formula,"reacciones"=>$reacciones,"fecha_sospecha"=>$fecha_sospecha));
        $action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Menu_Farmacovigilancia");
        $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
        $this->salida = $act->FormaBuscarPlantillas($action,$datos,$conteo,$pagina,$request['buscador']);
        return true;
   
   }
    /*
	* Funcion  para  buscar un paciente y realizar la formulacion
	* @return boolean
		*/
   function Buscar_Pacientes_Formulas()
   {
        $request = $_REQUEST;
        $datos_empresa = SessionGetVar("DatosEmpresaAF");

        IncludeFileModulo("Formulacion_E","RemoteXajax","app","Formulacion_ExternaESM");
        $this->SetXajax(array("ValidarPaciente"),"app_modules/Formulacion_ExternaESM/RemoteXajax/Formulacion_E.php","ISO-8859-1");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $opcion=$request['opcion'];
        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        $Plan_P = $obje->ConsultarPlan_Parametrizado();    
        $TipoIdent = $obje->ConsultarTipoId();
        $dispensacion_dias_ultima_entrega= ModuloGetVar('','','dispensacion_dias_ultima_entrega');
        $fecha_fin = date("Y-m-d"); 
        list($a,$m,$d) = split("-",$fecha_fin);
        $fecha_inicio = date("Y-m-d",(mktime(0,0,0, $m,($d - $dispensacion_dias_ultima_entrega),$a)));
        $Datos_Consulta =$obje->Consultar_Productos_Dispensados_por_Fechas($request['tipo'],$request['documento'],$fecha_inicio,$fecha_fin);
        $this->action1 = ModuloGetURL('app','Formulacion_ExternaESM','user','MenuHospitalizacion');
        $action['FormaDP'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "FormaDatosPaciente",array("opcion"=>$opcion));
      
        if($request['DISPENSACION']=="DISPENSACION") 
        {        
          $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "main");
        }else
        {
          $action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "main");
             
        }

        $action['consulta_producto'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Buscar_Pacientes_Formulas",array("opcion"=>$opcion));
        $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
        $this->salida = $act->FormaBuscarPacientes($action,$Plan_P,$TipoIdent,$request,$Datos_Consulta,$conteo,$pagin,$opcion,$dispensacion_dias_ultima_entrega);
        return true;
   
   }
    /*
	* Funcion  para  buscar un paciente y realizar la formulacion
	* @return boolean
		*/
   	function FormaDatosPaciente()
		{
        $datos_empresa = SessionGetVar("DatosEmpresaAF");

        $request = $_REQUEST;
        $opcion=$request['opcion'];
        $action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Formula_Digitalizar_Inicio",array("tipo_id_paciente"=>$request['TipoDocumento'],"paciente_id"=>$request['paciente_id'],"plan_id"=>$request['Responsable'],"opcion"=>$opcion));
        $action['cancelar'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Buscar_Pacientes_Formulas",array("opcion"=>$opcion));
        $_REQUEST['tipo_id_paciente'] = $request['TipoDocumento'];
        $_REQUEST['paciente_id'] = $request['Documento'];
        $_REQUEST['datos_afiliacion'] = 1;
        $_REQUEST['plan_id'] = $request['Responsable'];
        $pct->request=$_REQUEST;
        $pct = $this->ReturnModuloExterno('app','DatosPaciente','user');

        $pct->SetActionVolver($action['volver']);
        $pct->FormaDatosPaciente($action);

        $this->SetJavaScripts("Ocupaciones");
        $this->salida = $pct->salida;
     
			return true;
		}
     /*
    * Funcion  para  buscar un paciente y realizar la formulacion
    * @return boolean
		*/
   
    function Formula_Digitalizar_Inicio()
		{
        $request = $_REQUEST;
        $opcion=$request['opcion'];
        $datos_empresa = SessionGetVar("DatosEmpresaAF");
        IncludeFileModulo("Formulacion_E","RemoteXajax","app","Formulacion_ExternaESM");
        $this->SetXajax(array("Eliminar_dx","IngresarDX","MostrarProfesionales","Mostrar_profesion_IPS","Mostrar_profesion_IPS_ESM","MostrarDX"),"app_modules/Formulacion_ExternaESM/RemoteXajax/Formulacion_E.php","ISO-8859-1");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');

        $tipo_id_paciente=$request['tipo_id_paciente'];
        $paciente_id=$request['paciente_id'];
        $plan_id=$request['plan_id'];

        $obje->Eliminar_POS_tmp($tipo_id_paciente,$paciente_id);
        $obje->Eliminar_DXT_tmp($tipo_id_paciente,$paciente_id);
        $obje->Eliminar_cabec_tmp($tipo_id_paciente,$paciente_id);

        $validar_paciente = $obje->Validar_Paciente_tmp($request);


        $Tipo_Formula = $obje->Consultar_Tipos_Formulas(); 
        $Tipo_Evento = $obje->Consultar_Tipos_Eventos(); 	
        $Datos_Paciente = $obje->ObtenerDatosAfiliado_($request); 


        $fecha_recepcion=explode("/",$Datos_Paciente['fecha_nacimiento']);
        $FechaInicioI= $fecha_recepcion[0]."-".$fecha_recepcion[1]."-".$fecha_recepcion[2];
        $edad_paciente =$this->restaFechas($FechaInicioI);



        $Datos_Fueza = $obje->ObtenerFuezaPaciente($request); 	
        $Datos_Ad=$obje->Dato_Adionales_afiliacion($request);
        $IPS_=$obje->Consultar_IPS_();
        $ESM_pac=$obje->Consultar_ESM_P($request);
        $ESM_=$obje->Consultar_ESM_($ESM_pac);
        $action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Buscar_Pacientes_Formulas",array("opcion"=>$opcion));
        $opcion2=1;
        $action['continuar'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "FormaDiagnosticos",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"opcion2"=>$opcion2));


        $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
        $this->salida = $act->FormaCabeceraFormula($action,$Tipo_Formula,$Tipo_Evento,$request,$Datos_Paciente,$Datos_Fueza,$Datos_Ad,$ESM_pac,$ESM_,$opcion,$IPS_,$validar_paciente,$edad_paciente,$dx_ingres);

        return true;
		}
		/*
    * Funcion  para  restar dos fechas
    */
   function restaFechas($dFecIni,$dFecFin)
	{
	
		$dFecFin = date("d-m-Y");
	
		$dFecIni = str_replace("-","",$dFecIni);
		$dFecIni = str_replace("/","",$dFecIni);
		$dFecFin = str_replace("-","",$dFecFin);
		$dFecFin = str_replace("/","",$dFecFin);

		ereg("([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
		ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);

		$date1 = mktime(0,0,0,$aFecIni[2], $aFecIni[1], $aFecIni[3]);
		$date2 = mktime(0,0,0,$aFecFin[2], $aFecFin[1], $aFecFin[3]);

		return round(($date2 - $date1) / (60 * 60 * 24));
     }
  
     /*
    * Funcion  para  guardar la informacion basica de la formula  y consultar los medicamentos a formular
    * @return boolean
		*/
  	function FormaDiagnosticos()
		{
          $request = $_REQUEST;
          $datos_empresa = SessionGetVar("DatosEmpresaAF");
          $empresa=$datos_empresa['empresa_id'];
          $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
          $opcion=$request['opcion'];
          $opcion_=$request['opcion_'];
          $this->IncludeJS("TabPaneLayout");
          $this->IncludeJS("TabPaneApi");
          $this->IncludeJS("TabPane");
          if($opcion_=='1' OR $opcion_=='0')
          {
              $opcion=$opcion_;
          }
          IncludeFileModulo("Formulacion_E","RemoteXajax","app","Formulacion_ExternaESM");
          $this->SetXajax(array("Guardartmp_ins_hospita","IngresarDX","MostrarProfesionales","Mostrar_profesion_IPS","Mostrar_profesion_IPS_ESM","MostrarDX"),"app_modules/Formulacion_ExternaESM/RemoteXajax/Formulacion_E.php","ISO-8859-1");
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("CrossBrowserDrag");
          $opcion2=$request['opcion2'];
          $opcion3=1;
          $formula=$request['formula'];
          $tipo_id_paciente=$request['tipo_id_paciente'];
          $paciente_id=$request['paciente_id'];
          $plan_id=$request['plan_id'];
          $formula_papel=$request['formula_papel'];
          $Existe_=$obje->Consulta_Formula_Existente($formula_papel,$tipo_id_paciente,$paciente_id);
          $action['volver_a'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "main");
	        $action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Formula_Digitalizar_Inicio",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"formula"=>1,"tmp_id"=>$tmp_id,"opcion"=>$opcion));
          $Datos_empresa_dig=$obje->ObtenerPermisos_FORMULACION($empresa);
          if(empty($Existe_))
          {
    
                if($opcion=='0')
                {
                      if($opcion2=='1')
                      {
                      $Formula_pac=$obje->Guardar_Tmp_Cabecera_formulacionI($request,$datos_empresa);
                      }
                }
               if($opcion=='1')
                {
                    if($opcion2=='1')
                    {
                        $Formula_pac=$obje->Guardar_Tmp_Cabecera_formulacionE($request,$datos_empresa);
                    }
                        
                }
              $tmp=$obje->Consulta_Max_Formulacion_tmp($empresa,$tipo_id_paciente,$paciente_id);
              $tmp_id=$tmp['tmp_id'];
              if($tmp['sw_ambulatoria']=='1')
              {
               $url=ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "FormaDigitalizar_Ambulatoria",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"opcion2"=>$opcion2,"formula_id"=>$tmp_id));
               $html .= " <script>";
               $html .= " window.location=\"".$url."\";";
                $html .= " </script>";
               $this->salida=$html;
              
              }else
              {
                    if($request['eliminar_medica']=='1')
                    {
                        $dx=$request['producto_eliminar'];
                        $fe_medicamento_id=$request['fe_medicamento_id'];
                        $elimina=$obje->Eliminar_prod_tmp($dx,$tipo_id_paciente,$paciente_id,$fe_medicamento_id,$tmp_id);
                      
                    }
                    if($request['marcado']=='1')
                    {
                        $dx=$request['producto_eliminar'];
                        $fe_medicamento_id=$request['fe_medicamento_id'];
                        $elimina=$obje->Update_Marcar($fe_medicamento_id);
                        
                    }
                    $medi_form=$obje->Medicamentos_Formulados_tmp($tipo_id_paciente,$paciente_id,$tmp_id);
                    $conteo =$pagina=0;
                    $lista=$obje->Lista_Plan_idConsul($plan_id);
                    $lista_id=$lista['lista_precios'];
                    /*if($lista_id=='0000')
                    {
                      $datos="";
                    
                    }
                    else
                    {*/
                      $datos=$obje->ConsultarListaDetalle($request['buscador'],$lista_id,$empresa,$tipo_id_paciente,$paciente_id,$Datos_empresa_dig,$request['offset']);
                          
                    /*}*/
                    $action['buscador']=ModuloGetURL('app','Formulacion_ExternaESM','controller','FormaDiagnosticos',array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"tmp_id"=>$tmp_id));
                    $conteo= $obje->conteo;
                    $pagina= $obje-> pagina;
                    $action['paginador'] = ModuloGetURL('app', 'Formulacion_ExternaESM', 'controller', 'FormaDiagnosticos',array("buscador"=>$request['buscador'],"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"tmp_id"=>$tmp_id));
                    $action['formulacion'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "FormaDiagnosticos",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"formula"=>1,"tmp_id"=>$tmp_id,"opcion"=>$opcion));
                    $action['eliminar_med'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "FormaDiagnosticos",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"tmp_id"=>$tmp_id));
                    $action['marcado'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "FormaDiagnosticos",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"tmp_id"=>$tmp_id));
                    $action['buscador_2']=ModuloGetURL('app','Formulacion_ExternaESM','controller','FormaDiagnosticos',array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"tmp_id"=>$tmp_id));
                    if($request['insumo_s']=='1')
                    {
                      
                      if($lista_id=='0000')
                      {
                        $datos="";
                    
                      }
                      else
                      {
                        $datos_insumos=$obje->ConsultarListaDetalle_insumos($request['buscador_2'],$lista_id,$empresa,$tipo_id_paciente,$paciente_id,$Datos_empresa_dig,$request['offset']);
                          
                      }
                    
                    }
							
                    $action['finaliza_formulacion_real'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Creacion_Real_Formulacion",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"tmp_id"=>$tmp_id));
                    $Cabecera_Formulacion_=$obje->consultar_Formulacion_TITMP($request,$empresa,$tmp_id);
                    $Datos_Fueza = $obje->ObtenerFuezaPaciente($request); 	
                    $Datos_Ad=$obje->Dato_Adionales_afiliacion($request);
                    $ESM_pac=$obje->Consultar_ESM_P($request);
                    if($opcion=='1')
                    {
                      $Cabecera_Formulacion_AESM=$obje->Consulta_Formulacion_TMPA($tmp_id);
                      $Cabecera_Formulacion_AEM=$obje->Consulta_FormulacionTMPE($tmp_id);
                    }
                  $DX_=$obje->Diagnostico_Temporal($tipo_id_paciente,$paciente_id,$tmp_id);
                  if($formula==1)
                  {
                  
                      $codigo_medicamento=$request['codigo_medicamento'];
                      $medicamento_Datos=$obje->Medicamento_Formular_Inform($codigo_medicamento);
                      $via_admon=$obje->tipo_via_administracion($codigo_medicamento);
                      $unidadesViaAdministracion = $obje->GetunidadesViaAdministracion($via_admon[0]['via_administracion_id']);

                      $action['cancelar'] = ModuloGetURL('app', 'Formulacion_ExternaESM', 'controller', 'FormaDiagnosticos',array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"tmp_id"=>$tmp_id));
                      $action['guardar_formula'] = ModuloGetURL('app', 'Formulacion_ExternaESM', 'controller', 'FormaDiagnosticos',array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"formula"=>1,"tmp_id"=>$tmp_id));
                      $var_e='0';
                      
                    if($request['guardar_formula_']==1)
                    {
                          $inform=$obje->Insertar_Medicamentos($request);
                          if($inform=='1')
                          {
                                $mensaje = " GUARDADO CORRECTAMENTE";
                                $var_e='1';
                          }
                          else
                          {
                              $mensaje=$inform;
                              $var_e='0';
                          }
                    }
              		$act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
									$this->salida = $act->FormaBuscarProductos_For($action,$request['buscador'],$datos,$conteo,$pagina,$formula,$medicamento_Datos,$via_admon,$mensaje,$var_e,$medi_form,$opcion,$Cabecera_Formulacion_,$DX_,$request,$Datos_Fueza,$Datos_Ad,$ESM_pac,$Cabecera_Formulacion_AESM,$Cabecera_Formulacion_AEM,$tmp_id,$datos_insumos,$plan_id);
							  	}else
							  	{
									$act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
									$this->salida = $act->FormaBuscarProductos_For($action,$request['buscador'],$datos,$conteo,$pagina,$formula,$medicamento_Datos,$via_admon,$mensaje,$var_e,$medi_form,$opcion,$Cabecera_Formulacion_,$DX_,$request,$Datos_Fueza,$Datos_Ad,$ESM_pac,$Cabecera_Formulacion_AESM,$Cabecera_Formulacion_AEM,$tmp_id,$datos_insumos,$plan_id);
								}	
					}
			}
			else
			{
			        $elimina=$obje->Eliminar_DX_Ttmp($tipo_id_paciente,$paciente_id);
              $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
              $this->salida = $act->FormaBloqueo($action,$request['buscador'],$datos,$conteo,$pagina,$dx_ingres);
			}
		         return true;
		}
	
     /*
    * Funcion  para  crea la formula real de hospitalizacion
    * @return boolean
		*/

    function Creacion_Real_Formulacion()
    {
        $request = $_REQUEST;
        $datos_empresa = SessionGetVar("DatosEmpresaAF");
        $empresa=$datos_empresa['empresa_id'];
        $cantidad=$request['cantidad_registros'];
        $tipo_id_paciente=$request['tipo_id_paciente'];
        $paciente_id=$request['paciente_id'];
        $plan_id=$request['plan_id'];
        $opcion3=$request['opcion3'];
        $opcion=$request['opcion'];
        
        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        $tipo_form=$obje->consultar_tipo_formula_tmp($request,$empresa);
        $tipo_formula=$tipo_form['tipo_formulacion'];
	
        if($tipo_formula==0)
        {
            $Cabecera_Formulacion_=$obje->consultar_Formulacion_ITMP($request,$empresa);
        }
        if($tipo_formula==1)
        {
	
          $Cabecera_Formulacion_=$obje->consultar_Formulacion_ETMP($request,$empresa);
	
        }

        $DX_=$obje->Diagnostico_Temporal($tipo_id_paciente,$paciente_id,$request['tmp_id']);
        $MEDIC_=$obje->Medicamentos_Formulados_tmp($tipo_id_paciente,$paciente_id,$request['tmp_id']);
        $final_formula=$obje->FormulaReal_($Cabecera_Formulacion_,$DX_,$MEDIC_,$tipo_formula);
        $B_TMP=$obje->Eliminar_tmp($request,$empresa);
        $MAX_F=$obje->Consulta_Max_Formulacion($empresa,$tipo_id_paciente,$paciente_id);
        $Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($MAX_F['tmp_id']);

        if($opcion=='1')
        {
         
          $Cabecera_Formulacion_AESM=$obje->Consulta_Formulacion_Real_A($MAX_F['tmp_id']);
          $Cabecera_Formulacion_AEM=$obje->Consulta_Formulacion_Real_AE($MAX_F['tmp_id']);
        }
        $Datos_Fueza = $obje->ObtenerFuezaPaciente($request); 	
        $Datos_Ad=$obje->Dato_Adionales_afiliacion($request);
        $ESM_pac=$obje->Consultar_ESM_P($request);
        $dix_r=$obje->Diagnostico_Real($MAX_F['tmp_id']);
        $medi_form=$obje->Medicamentos_Formulados_R($MAX_F['tmp_id']);
	
        $permisos = $obje->ObtenerPermisos_dispensacion();

        $action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "main");
        $action['anular'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Anulacion_Formula",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"tmp_id"=>$MAX_F['tmp_id']));

        $action['cambiar_modulo'] = ModuloGetURL("app", "DispensacionESM", "controller", "FormulasPacienteESM",array("dispensar"=>'SI',"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"formula_id"=>$MAX_F['tmp_id'],"DispensacionESM"=>$permisos,"plan_id"=>$plan_id));
        $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
        $this->salida = $act->FormaCabeceraFormulaCompleta($action,$Cabecera_Formulacion,$Cabecera_Formulacion_AESM,$request,$Cabecera_Formulacion_AEM,$Datos_Fueza,$Datos_Ad,$ESM_pac,$ESM_,$opcion,$IPS_,$dix_r,$medi_form,$permisos);
        return true;
	
	}
     /*
    * Funcion  para  buscan los medicamentos ambulatorios
    * @return boolean
		*/
		function FormaDigitalizar_Ambulatoria()
    {
        $request = $_REQUEST;
        $datos_empresa = SessionGetVar("DatosEmpresaAF");
        $empresa=$datos_empresa['empresa_id'];
				$obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        
        
        $tipo_id_paciente=$request['tipo_id_paciente'];
        $paciente_id=$request['paciente_id'];
        $plan_id=$request['plan_id'];
        $formula_id=$request['formula_id'];
        $opcion=$request['opcion'];
        
        
        IncludeFileModulo("Formulacion_E","RemoteXajax","app","Formulacion_ExternaESM");
        $this->SetXajax(array("Guardartmp_ambu","IngresarDX","MostrarProfesionales","Mostrar_profesion_IPS","Mostrar_profesion_IPS_ESM","MostrarDX"),"app_modules/Formulacion_ExternaESM/RemoteXajax/Formulacion_E.php","ISO-8859-1");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $Datos_empresa_dig=$obje->ObtenerPermisos_FORMULACION($empresa);
          
        if($request['eliminar_medica']=='1')
        {
              $dx=$request['producto_eliminar'];
              $fe_medicamento_id=$request['fe_medicamento_id'];
              $elimina=$obje->Eliminar_prod_tmp_amb($dx,$tipo_id_paciente,$paciente_id,$fe_medicamento_id,$formula_id);
            
        }
		
        if($request['marcado']=='1')
        {
            $dx=$request['producto_eliminar'];
            $fe_medicamento_id=$request['fe_medicamento_id'];
            $elimina=$obje->Update_Marcar($fe_medicamento_id);
				}
			
			
				$conteo =$pagina=0;
				$lista=$obje->Lista_Plan_idConsul($plan_id);
				$lista_id=$lista['lista_precios'];
       
				/*if($lista_id=='0000')
				{
          $datos="";
							
				}
				else
				{*/
				    	$datos=$obje->ConsultarListaDetalle($request['buscador'],$lista_id,$empresa,$tipo_id_paciente,$paciente_id,$Datos_empresa_dig,$request['offset']);
						        
				//}
				$medi_form=$obje->Medicamentos_Formulados_tmp($tipo_id_paciente,$paciente_id,$formula_id);
				
				$action['buscador']=ModuloGetURL('app','Formulacion_ExternaESM','controller','FormaDigitalizar_Ambulatoria',array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"formula_id"=>$formula_id));
				$conteo= $obje->conteo;
				$pagina= $obje-> pagina;
				$action['paginador'] = ModuloGetURL('app', 'Formulacion_ExternaESM', 'controller', 'FormaDigitalizar_Ambulatoria',array("buscador"=>$request['buscador'],"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"formula_id"=>$formula_id));
        $action['eliminar_med'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "FormaDigitalizar_Ambulatoria",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"formula_id"=>$formula_id));
				$action['marcado'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "FormaDigitalizar_Ambulatoria",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"formula_id"=>$formula_id));
				
				$action['finaliza_formulacion_real'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Creacion_Real_Formulacion_ambulatoria",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"tmp_id"=>$formula_id));
				
				$Cabecera_Formulacion_=$obje->consultar_Formulacion_TITMP($request,$empresa,$formula_id);
				$Datos_Fueza = $obje->ObtenerFuezaPaciente($request); 	
				$Datos_Ad=$obje->Dato_Adionales_afiliacion($request);
				$ESM_pac=$obje->Consultar_ESM_P($request);
				if($opcion=='1')
					{
						$Cabecera_Formulacion_AESM=$obje->Consulta_Formulacion_TMPA($formula_id);
						$Cabecera_Formulacion_AEM=$obje->Consulta_FormulacionTMPE($formula_id);
					}

        $DX_=$obje->Diagnostico_Temporal($tipo_id_paciente,$paciente_id,$formula_id);
        $action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Formula_Digitalizar_Inicio",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"opcion"=>$opcion,"formula_id"=>$formula_id));
        $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
        $this->salida = $act->FormaBuscarProductos_Ambulatoria($action,$request['buscador'],$datos,$conteo,$pagina,$formula,$medicamento_Datos,$via_admon,$mensaje,$var_e,$medi_form,$opcion,$Cabecera_Formulacion_,$DX_,$request,$Datos_Fueza,$Datos_Ad,$ESM_pac,$Cabecera_Formulacion_AESM,$Cabecera_Formulacion_AEM,$formula_id,$tipo_id_paciente,$paciente_id,$plan_id,$opcion);
        return true;
	
	}
    /*
    * Funcion  para  crea la formula real ambulatoria
    * @return boolean
		*/
    function Creacion_Real_Formulacion_ambulatoria()
    {
        $request = $_REQUEST;
        $datos_empresa = SessionGetVar("DatosEmpresaAF");
        $empresa=$datos_empresa['empresa_id'];
        $cantidad=$request['cantidad_registros'];
        $tipo_id_paciente=$request['tipo_id_paciente'];
        $paciente_id=$request['paciente_id'];
        $plan_id=$request['plan_id'];
        $opcion3=$request['opcion3'];
        $opcion=$request['opcion'];
        
        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        $tipo_form=$obje->consultar_tipo_formula_tmp($request,$empresa);
        $tipo_formula=$tipo_form['tipo_formulacion'];
        if($tipo_formula==0)
        {
            $Cabecera_Formulacion_=$obje->consultar_Formulacion_ITMP($request,$empresa);
        }
        if($tipo_formula==1)
        {
          $Cabecera_Formulacion_=$obje->consultar_Formulacion_ETMP($request,$empresa);
        }
        
        $DX_=$obje->Diagnostico_Temporal($tipo_id_paciente,$paciente_id,$request['tmp_id']);
		    $MEDIC_=$obje->Medicamentos_Formulados_tmp($tipo_id_paciente,$paciente_id,$request['tmp_id']);
        $final_formula=$obje->FormulaReal_AMB($Cabecera_Formulacion_,$DX_,$MEDIC_,$tipo_formula);
        $B_TMP=$obje->Eliminar_tmp($request,$empresa);
           
        $MAX_F=$obje->Consulta_Max_Formulacion($empresa,$tipo_id_paciente,$paciente_id);
        $Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($MAX_F['tmp_id']);
        if($opcion=='1')
        {
          $Cabecera_Formulacion_AESM=$obje->Consulta_Formulacion_Real_A($MAX_F['tmp_id']);
          $Cabecera_Formulacion_AEM=$obje->Consulta_Formulacion_Real_AE($MAX_F['tmp_id']);
        }
        $Datos_Fueza = $obje->ObtenerFuezaPaciente($request); 	
        $Datos_Ad=$obje->Dato_Adionales_afiliacion($request);
        $ESM_pac=$obje->Consultar_ESM_P($request);
        $dix_r=$obje->Diagnostico_Real($MAX_F['tmp_id']);
        $medi_form=$obje->Medicamentos_Formulados_R($MAX_F['tmp_id']);
				$permisos = $obje->ObtenerPermisos_dispensacion();

				$action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "main");
				$action['anular'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Anulacion_Formula",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"tmp_id"=>$MAX_F['tmp_id']));
				$action['cambiar_modulo'] = ModuloGetURL("app", "DispensacionESM", "controller", "FormulasPaciente",array("dispensar"=>'SI',"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"formula_id"=>$MAX_F['tmp_id'],"DispensacionESM"=>$permisos,"plan_id"=>$plan_id));
        $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
        $this->salida = $act->FormaCabeceraFormulaCompleta_ambu($action,$Cabecera_Formulacion,$Cabecera_Formulacion_AESM,$request,$Cabecera_Formulacion_AEM,$Datos_Fueza,$Datos_Ad,$ESM_pac,$ESM_,$opcion,$IPS_,$dix_r,$medi_form,$permisos);
        return true;
	
	}
      /*
    * Funcion  para  anular la la formula real 
    * @return boolean
		*/
    function Anulacion_Formula()
    {
        $request = $_REQUEST;
        $datos_empresa = SessionGetVar("DatosEmpresaAF");
        $empresa=$datos_empresa['empresa_id'];
        $tmp_id=$request['tmp_id'];
        $tipo_id_paciente=$request['tipo_id_paciente'];
        $paciente_id=$request['paciente_id'];
        $plan_id=$request['plan_id'];
        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        $dix_r=$obje->Actulizar_Estado_Formula_($tmp_id);
        $formula_papel_=$obje->consultar_Formulacion_Papel($tmp_id);
				$action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "main");
        $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
        $this->salida = $act->FormaFormulaAnulada($action,$formula_papel_,$tipo_id_paciente,$paciente_id);
        return true;
    }
    /*
    * Funcion  para  crear el menu del suministro para las ESM 
    * @return boolean
		*/

    function Suministros_esm()
		{
        $request = $_REQUEST;
        $permisos2=$request['permisos2'];
        $datos_empresa = SessionGetVar("DatosEmpresaAF");
        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        $ESM = $obje->Listar_ESM();
		   	$action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Menu_Formulacion",array("Formulacion_ExternaESM"=>$datos_empresa));
        $action['Guardar'] = ModuloGetURL("app","Formulacion_ExternaESM","controller","Guardar_NuevoTemporal_Suministro");
        $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
		    $this->salida = $act->Vista_FormularioNuevoDoc_Suministro($action,$ESM,$permisos2,$TiposRequisiciones,$CentrosUtilidad);
        return true;
		}
    /*
    * Funcion  para  guardar los datos basicos del suministro temporal 
    * @return boolean
		*/
    
    function Guardar_NuevoTemporal_Suministro()
		{
          $request = $_REQUEST;
          $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
          $token=$obje->Insertar_OSuministroTemporal($_REQUEST);
          $url = ModuloGetURL("app","Formulacion_ExternaESM","controller","Modificar_Temporal_Suministro")."&suministro_id=".$token['formula_suministro_id_tmp']."";
          $html .= "<script>";
          if(!$token)
          $html .= " history.go(-1) ";
          else 
          $html .= "window.location=\"".$url."\";";
          $html .= "</script>";
          $this->salida=$html;
          return true;
		}
    /*
    * Funcion  para  modificar la informacion temporal del sumistro 
    * @return boolean
		*/
    
		function Modificar_Temporal_Suministro()
		{
          $request = $_REQUEST;
          IncludeFileModulo("Formulacion_E","RemoteXajax","app","Formulacion_ExternaESM");
          $this->SetXajax(array("Listado_Pacientes","Borrar_Total_Paciente","Borrar_Item_suminstro_paciente","Listado_Productos_A_Suministrar","GuardarPT","Borrar_Item_suminstr","Regresar_Buscardor_Item","Listado_Productos_TMP_s","Redireccionar"),"app_modules/Formulacion_ExternaESM/RemoteXajax/Formulacion_E.php","ISO-8859-1");
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("CrossBrowserDrag");
          $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
          $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
         
          $datos_empresa = SessionGetVar("DatosEmpresaAF");
          $DocTemporal=$obje->Obtener_InfoDocTemporal($_REQUEST['suministro_id']);
          $BodegaSatelite=$obje->Bodega($DocTemporal['empresa_id'],$DocTemporal['centro_utilidad'],$DocTemporal['bodega']);
          $bodegas_doc_id= ModuloGetVar('app','Formulacion_ExternaESM','egreso_suministro_empresa_'.trim($DocTemporal['empresa_id']).'_'.trim($DocTemporal['bodega']));
          $Tipo=$obje->ConsultarTipoId();
          $action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Menu_Formulacion",array("Formulacion_ExternaESM"=>$datos_empresa,"orden_id"=>$_REQUEST['suministro_id']));
          $this->salida = $act->Vista_FormularioModificarDoc_suministro($action,$DocTemporal,$BodegaSatelite,$Tipo,$bodegas_doc_id);  
          return true;
		}
     /*
    * Funcion  para  crear el suminstro real 
    * @return boolean
		*/
   		function CrearSuministro()
		{
		
        $request = $_REQUEST;
        $orden_id=$request['orden'];
        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        $DocTemporal=$obje->Obtener_InfoDocTemporal($orden_id);
        
          
          IncludeFileModulo("Formulacion_E","RemoteXajax","app","Formulacion_ExternaESM");
          $this->SetXajax(array("imprimir_documento","Listado_Productos_A_Suministrar","GuardarPT","Borrar_Item_suminstr","Regresar_Buscardor_Item","Listado_Productos_TMP_s","Redireccionar"),"app_modules/Formulacion_ExternaESM/RemoteXajax/Formulacion_E.php","ISO-8859-1");
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("CrossBrowserDrag");
          
              
          $bodegas_doc_id= ModuloGetVar('app','Formulacion_ExternaESM','egreso_suministro_empresa_'.trim($DocTemporal['empresa_id']).'_'.trim($DocTemporal['bodega']));
          $datos2= $obje->AsignarNumeroDocumentoDespacho_d($bodegas_doc_id);
          $numeracion=$datos2['numeracion'];
          $Pacientes=$obje->Listado_ProductosTemporales($orden_id);
          $inv_bodegas = $obje->IngresarInv_Bodegas_documentos($bodegas_doc_id,$DocTemporal['observacion'],$numeracion);
          $inv_suministro = $obje->IngresarCabecera_Suministro_documentos($bodegas_doc_id,$numeracion,$DocTemporal['tipo_id_tercero'],$DocTemporal['tercero_id'],$DocTemporal['empresa_id'],$DocTemporal['centro_utilidad'],$DocTemporal['bodega'],$DocTemporal['observacion']);
          $porcentaje= ModuloGetVar('','','ESM_PorcentajeIntermediacion');
           $k=0;
          foreach($Pacientes as $k1 => $paciente)
          {
          
            $Plan=$obje->Plan_Paciente($paciente['identificacion']);
            $lista=$obje->Lista_Plan_idConsul($Plan['plan_atencion']);
            $lista_id=$lista['lista_precios'];
            
                  $temporales=$obje->Listado_ProductosTemporales_por_paciente($orden_id,$paciente['identificacion']);
                $totalcosto=0;
                $pactado= " ";
                $ind=0;
                foreach($temporales as $k1 => $dt1)
                {
              
                  $datos=$obje->ConsultarListaDetalle_producto_lista($lista_id,$DocTemporal['empresa_id'],$dt1['codigo_producto']);
                  
                  $pactado='1';
                  $valor=$datos['precio'];
                  if(empty($datos))
                  {
                
                    $datos=$obje->ConsultarListaDetalle_BASE($lista_id,$DocTemporal['empresa_id'],$dt1['codigo_producto']);
                  
                    if(empty($datos))
                    {
                      $datos=$obje->ConsultarListaDetalle_NO_PAC($DocTemporal['empresa_id'],$dt1['codigo_producto']);
                    
                    }
                    
                    $pactado='0';
                    $pactado='0';
                    $porc = ($porcentaje/100)+1;
                    $valor= ($datos['precio']/$porc)+$datos['precio'];
                    
                  }
                
                  $costo_producto=$dt1['cantidad'] * $valor;
                  $detalle=$obje->Guardar_Inv_bodegas_documento_d($dt1['codigo_producto'],$dt1['cantidad'],$costo_producto,$bodegas_doc_id,$numeracion,$DocTemporal['empresa_id'],$pactado,$DocTemporal['centro_utilidad'],$DocTemporal['bodega'],$dt1['fecha_vencimiento'],$dt1['lote']);
                  if($detalle==true)
                  {
                    $detalle=$obje->Guardar_Inv_bodegas_documento_dd($bodegas_doc_id,$numeracion,$paciente['tipo_id_paciente'],$paciente['paciente_id'],$dt1['codigo_producto'],$dt1['cantidad'],$dt1['fecha_vencimiento'],$dt1['lote']);
                       $k++;
                  }
                  
                  $totalcosto=$totalcosto + $costo_producto;
                  $ind++;									
                }
                $actuInv_Bodegas=$obje->UpdateCostos($bodegas_doc_id,$numeracion,$totalcosto);
                
          }
          
          
            $obje->Eliminar_tmp_suministro($orden_id);
              
        if($k>0)
        {
              
              $action['volver'] = ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "main");
                
              $act = AutoCarga::factory("Formulacion_ExternaESMHTML", "views", "app", "Formulacion_ExternaESM");
              $html = $act->Vista_suministros_Final($action,$bodegas_doc_id,$numeracion);    
              $this->salida=$html;

      }
		return true;
		}
       
	} 
?>