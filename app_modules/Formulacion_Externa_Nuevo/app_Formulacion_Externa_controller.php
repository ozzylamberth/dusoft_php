<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: app_Formulacion_Externa_controller.php,v 1.0
	* @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	/**
	* Clase Control: Formulacion_Externa
	* Clase encargada del control de llamado de metodos en el modulo
	*
	* @package IPSOFT-SIIS
	/*/
  IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");
  //IncludeClass("DispensacionMedicamentos");
	class app_Formulacion_Externa_controller  extends classModulo
	{
    /**
		* Constructor de la clase
    */
    function app_Formulacion_Externa_controller(){}
	/**
	* Funcion principal del modulo
	* @return boolean
		*/
		function main()
		{
			$request = $_REQUEST;
			$obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
			$permisos = $obje->ObtenerPermisos();    
		
			$ttl_gral = "FORMULACION MEDICAMENTOS ";
			$mtz[0] = 'FARMACIAS';
			$mtz[1] = 'CENTRO UTILIDAD';
			$mtz[2] = 'BODEGA';
			$url[0] = 'app';
			$url[1] = 'Formulacion_Externa'; 
			$url[2] = 'controller';
			$url[3] = 'Menu_Formulacion'; 
			$url[4] = 'Formulacion_Externa'; 
			
			$action['volver'] = ModuloGetURL('system', 'Menu');
			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
			return true;
		}
    /*
	    * Funcion que permite ir a los diferentes menus del modulo de Dispensacion
	    * @return boolean
	    */    
	  function Menu_Formulacion()
		{
			$request = $_REQUEST;
			if($request['Formulacion_Externa']) SessionSetVar("DatosEmpresaAF",$request['Formulacion_Externa']);
    
			$datos_empresa = SessionGetVar("DatosEmpresaAF");
      $empresa=$datos_empresa['empresa_id'];
			
      $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
    	
      $action['farmacovigilancia'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Menu_Farmacovigilancia");
			$action['FormulaInt'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Buscar_Pacientes_Formulas");
      $action['tickets'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Tickets_Dispensacion");
        
			$action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "main");
		  $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
			$this->salida = $act->FormaMenu($action,$permisos,$permisos2);
			return true;
		}
	 /*
	    * Funcion que permite ir al menu de Farmacovigilancia
	    * @return boolean
	    */
		function Menu_Farmacovigilancia()
		{
          $request = $_REQUEST;
          $datos_empresa = SessionGetVar("DatosEmpresaAF");

          $action['registro_farmacovigilancia'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Buscar_Pacientes");
          $action['buscar_registro'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Buscar_Registro_Pacientes");

          $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Menu_Formulacion");
          $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
          $this->salida = $act->FormaMenuFarmacovigilancia($action);
          return true;
	    }
    /*
	    * Funcion que permite Buscar los pacientes para el ingreso del reporte de farmacovigilancia
	    * @return boolean
	    */
   
      function Buscar_Pacientes()
	  {
          $request = $_REQUEST;
          $datos_empresa = SessionGetVar("DatosEmpresaAF");
          $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
          $TipoIdent = $obje->ConsultarTipoId();    
          $conteo =$pagina=0;

          $datos=$obje->Consultar_Datospacientes($request['buscador'],$request['offset']);
          $action['buscador']=ModuloGetURL('app','Formulacion_Externa','controller','Buscar_Pacientes');
          $conteo= $obje->conteo;
          $pagina= $obje-> pagina;
          $action['paginador'] = ModuloGetURL('app', 'Formulacion_Externa', 'controller', 'Buscar_Pacientes',array("buscador"=>$request['buscador']));
          $action['registro'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Plantilla_Registro");
          $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Menu_Farmacovigilancia");
          $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
          $this->salida = $act->formaRegistrarPacientes($action,$TipoIdent,$request['buscador'],$datos,$conteo,$pagina);
          return true;
	  }
	  /*
	    * Funcion Registrar Datos Basicos de la Plantilla de Farmacovigilancia
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
          $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
          $instuticion =$obje->consultar_Farmacias();
          $action['registrar'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Medicamentos_Farmacovigilancia",array("paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente,"apellidos"=>$apellidos,"nombres"=>$nombres,"sexo"=>$sexo));
          $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Buscar_Pacientes",array("paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente,"apellidos"=>$apellidos,"nombres"=>$nombres,"sexo"=>$sexo));
          $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
          $this->salida = $act->formaRegistrarPacientes_Plantilla($action,$instuticion,$paciente_id,$tipo_id_paciente,$apellidos,$nombres,$sexo,$request);
          return true;
      }
      /*
	    * Funcion Registrar Medicamentos a la plantilla de Farmacovigilancia
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

        $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
        $Formula_info=$obje->Consultar_Identificador_formula($formula,$tipo_id_paciente,$paciente_id);
        $formula_id=$Formula_info[0]['formula_id'];
			
        $datos=$obje->Medicamentos_Dispensados_Esm_x_lote_total($formula_id);
        $diagnostico=$obje->Diagnostico_Real($formula_id);

        $action['buscador']=ModuloGetURL('app','Formulacion_Externa','controller','Medicamentos_Farmacovigilancia',array("paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente,"apellidos"=>$apellidos,"nombres"=>$nombres,"sexo"=>$sexo,"fecha_notifica"=>$fecha_notifica,"institucion"=>$institucion,"formula"=>$formula,"reacciones"=>$reacciones,"fecha_sospecha"=>$fecha_sospecha));
        $conteo= $obje->conteo;
        $pagina= $obje-> pagina;
        $action['paginador'] = ModuloGetURL('app', 'Formulacion_Externa', 'controller', 'Medicamentos_Farmacovigilancia',array("buscador"=>$request['buscador'],"paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente,"apellidos"=>$apellidos,"nombres"=>$nombres,"sexo"=>$sexo,"fecha_notifica"=>$fecha_notifica,"institucion"=>$institucion,"formula"=>$formula,"reacciones"=>$reacciones,"fecha_sospecha"=>$fecha_sospecha));
        $action['registrar'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Insertar_Informacion_Farmacovigilancia",array("paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente,"apellidos"=>$apellidos,"nombres"=>$nombres,"sexo"=>$sexo,"fecha_notifica"=>$fecha_notifica,"institucion"=>$institucion,"formula"=>$formula,"reacciones"=>$reacciones,"fecha_sospecha"=>$fecha_sospecha,"formula_id"=>$formula_id));
        $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Plantilla_Registro",array("paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente,"apellidos"=>$apellidos,"nombres"=>$nombres,"sexo"=>$sexo));
        $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
        $this->salida = $act->formaRegistrarPacientes_medicamentos($action,$instuticion,$request['buscador'],$datos,$conteo,$pagina,$formula_id,$diagnostico);
        return true;
    }
      /*
	    * Funcion Registrar Toda la Informacion de Farmacovigilancia
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

          $mdl = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
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
          $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Menu_Farmacovigilancia");
          $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
          $this->salida = $act->FormaMensajegenerado($action,$esm_farmaco_id,$formula_id);
          return true;
      }
      /*
	    * Funcion Buscar los pacientes
	    * @return boolean
	    */
      function Buscar_Registro_Pacientes()
      {
        $request = $_REQUEST;
        $datos_empresa = SessionGetVar("DatosEmpresaAF");
        $mdl = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
        $conteo= $mdl->conteo;
        $pagina= $mdl-> pagina;

        if(!empty($request['buscador']))
        {
            $datos=$mdl->consulta_Informacion($request['buscador'],$request['offset']);
            $action['buscador']=ModuloGetURL('app','Formulacion_Externa','controller','Buscar_Registro_Pacientes');
            $conteo= $mdl->conteo;
            $pagina= $mdl-> pagina;
         }
        $action['paginador'] = ModuloGetURL('app', 'Formulacion_Externa', 'controller', 'Buscar_Registro_Pacientes',array("buscador"=>$request['buscador'],"paciente_id"=>$paciente_id,"tipo_id_paciente"=>$tipo_id_paciente,"apellidos"=>$apellidos,"nombres"=>$nombres,"sexo"=>$sexo,"fecha_notifica"=>$fecha_notifica,"institucion"=>$institucion,"formula"=>$formula,"reacciones"=>$reacciones,"fecha_sospecha"=>$fecha_sospecha));
        $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Menu_Farmacovigilancia");
        $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
        $this->salida = $act->FormaBuscarPlantillas($action,$datos,$conteo,$pagina,$request['buscador']);
        return true;
   
       }
   
       /*
	    * Funcion Permite registrar formulas
	    * @return boolean
	    */
      function Buscar_Pacientes_Formulas()
      {
        $request = $_REQUEST;
        $datos_empresa = SessionGetVar("DatosEmpresaAF");
      
      /*variable */
      
       $bodegas_doc_id= ModuloGetVar('app','Formulacion_Externa','documento_dispensacion_'.trim($datos_empresa['empresa_id']).'_'.trim($datos_empresa['bodega']));
	
        IncludeFileModulo("Formulacion_E","RemoteXajax","app","Formulacion_Externa");
        $this->SetXajax(array("ValidarPaciente"),"app_modules/Formulacion_Externa/RemoteXajax/Formulacion_E.php","ISO-8859-1");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");
        $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
        $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
        $TipoIdent = $obje->ConsultarTipoId();
        $Plan_P = $obje->planes_parametrizados();    
        $NO_ESM='NO';
        $this->action1 = ModuloGetURL('app','Formulacion_Externa','user','MenuHospitalizacion');
        $action['FormaDP'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "FormaDatosPaciente",array("NO_ESM"=>$NO_ESM));
        if($request['DISPENSACION']=="DISPENSACION") 
        {        
          $action['volver'] = ModuloGetURL("app", "DispensacionESM", "controller", "main");
        }else
        {
          $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "main");
             
        }
        $this->salida = $act->FormaBuscarPacientes($action,$Plan_P,$TipoIdent,$bodegas_doc_id);
        return true;

   }
     /*
	    * Funcion Permite ver la informacion del paciente
	    * @return boolean
	    */
	   	function FormaDatosPaciente()
      {
        $datos_empresa = SessionGetVar("DatosEmpresaAF");
        $request = $_REQUEST;
        $NO_ESM=$request['NO_ESM'];
        $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Formula_Digitalizar_Inicio",array("tipo_id_paciente"=>$request['TipoDocumento'],"paciente_id"=>$request['paciente_id'],"plan_id"=>$request['Responsable'],"opcion"=>$opcion));
        $action['cancelar'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Buscar_Pacientes_Formulas",array("NO_ESM"=>$NO_ESM));
        $_REQUEST['tipo_id_paciente'] = $request['TipoDocumento'];
        $_REQUEST['paciente_id'] = $request['Documento'];
        $_REQUEST['datos_afiliacion'] = 1;
        $_REQUEST['plan_id'] = $request['Responsable'];
        $_REQUEST['NO_ESM']=$NO_ESM;
        $pct->request=$_REQUEST;
        $pct = $this->ReturnModuloExterno('app','DatosPaciente','user');
        $pct->SetActionVolver($action['volver']);
        $pct->FormaDatosPaciente($action);
        $this->SetJavaScripts("Ocupaciones");
        $this->salida = $pct->salida;
        return true;
		}
     /*
	    * Funcion Permite Digitalizar una formula de un paciente
	    * @return boolean
	    */
		function Formula_Digitalizar_Inicio()
		{
            $request = $_REQUEST;
			$datos_empresa = SessionGetVar("DatosEmpresaAF");
            IncludeFileModulo("Formulacion_E","RemoteXajax","app","Formulacion_Externa");
            $this->SetXajax(array("ValidarFechas","Eliminar_dx","IngresarDX","MostrarDX"),"app_modules/Formulacion_Externa/RemoteXajax/Formulacion_E.php","ISO-8859-1");
            $this->IncludeJS("CrossBrowser");
            $this->IncludeJS("CrossBrowserEvent");
            $this->IncludeJS("CrossBrowserDrag");
            $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
			$inf_p = AutoCarga::factory('InformacionPacientes');
			
            $tipo_id_paciente=$request['tipo_id_paciente'];
            $paciente_id=$request['paciente_id'];
            $plan_id=$request['plan_id'];

            $obje->Eliminar_dis_tmp();
            $obje->Eliminar_POS_tmp($tipo_id_paciente,$paciente_id);
            $obje->Eliminar_DXT_tmp($tipo_id_paciente,$paciente_id);
            $obje->Eliminar_cabec_tmp($tipo_id_paciente,$paciente_id);
            
             $Tipo_Formula = $obje->Consultar_Tipos_Formulas(); 
            $validar_paciente = $obje->Validar_Paciente_tmp($request);
            
			/*$Datos_Paciente = $obje->ObtenerDatosAfiliado_($request); */
			$Datos_Paciente = $inf_p->ValidarInformacion($request); 
			if($Datos_Paciente==3)
				$Datos_Paciente = $obje->ObtenerDatosAfiliado_($request); 
				
			/*var_dump($Datos_Paciente);*/
            $Datos_Ad=$obje->Dato_Adionales_afiliacion($request);
            $profesionales=$obje->profesionales_();
          
            $fecha_recepcion=explode("/",$Datos_Paciente['fecha_nacimiento']);
            $FechaInicioI= $fecha_recepcion[0]."-".$fecha_recepcion[1]."-".$fecha_recepcion[2];
            $edad_paciente =$this->restaFechas($FechaInicioI);

            $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Buscar_Pacientes_Formulas");
            $opcion2=1;
            $action['continuar'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "FormaDiagnosticos",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id));
            $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
            $this->salida = $act->FormaCabeceraFormula($action,$request,$Datos_Paciente,$Datos_Ad,$validar_paciente,$edad_paciente,$dx_ingres,$profesionales,$Tipo_Formula);
            return true;
		}
	  /*
	    * Funcion Permite Registrar la cabecera de la formula y el diagnostico-*********
	    * @return boolean
	    */
		function FormaDiagnosticos()
		{
        $request = $_REQUEST;
        $datos_empresa = SessionGetVar("DatosEmpresaAF");
        $empresa=$datos_empresa['empresa_id'];
        $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
       
        $opcion_=$request['opcion_'];
        $this->IncludeJS("TabPaneLayout");
        $this->IncludeJS("TabPaneApi");
        $this->IncludeJS("TabPane");

        IncludeFileModulo("Formulacion_E","RemoteXajax","app","Formulacion_Externa");
        $this->SetXajax(array("IngresarDX","MostrarDX"),"app_modules/Formulacion_Externa/RemoteXajax/Formulacion_E.php","ISO-8859-1");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $formula=$request['formula'];
        $tipo_id_paciente=$request['tipo_id_paciente'];
        $paciente_id=$request['paciente_id'];
        $plan_id=$request['plan_id'];
        $formula_papel=$request['formula_papel'];

        $Existe_=$obje->Consulta_Formula_Existente($formula_papel,$tipo_id_paciente,$paciente_id);
        $action['volver_a'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Formula_Digitalizar_Inicio",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"formula"=>1,"tmp_id"=>$tmp_id));
	
		    if(empty($Existe_))
        {
            $Formula_pac=$obje->Guardar_Tmp_Cabecera_formulacionI($request,$datos_empresa);
            $tmp=$obje->Consulta_Max_Formulacion_tmp($empresa,$tipo_id_paciente,$paciente_id);
            $tmp_id=$tmp['tmp_id'];

            $url=ModuloGetURL("app", "Formulacion_Externa", "controller", "FormaDigitalizar_Ambulatoria",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"formula_id"=>$tmp_id));
            $html .= " <script>";
            $html .= " window.location=\"".$url."\";";
            $html .= " </script>";
            $this->salida=$html;
				}	else
        {
           $elimina=$obje->Eliminar_DX_Ttmp($tipo_id_paciente,$paciente_id);
			     $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
					 $this->salida = $act->FormaBloqueo($action,$request['buscador'],$datos,$conteo,$pagina,$dx_ingres);
		    
        }
        
      
        return true;
      }
      /*
	    * Funcion Permite Digitalizar Ambulatoria-*********
	    * @return boolean
	   */
      
     function FormaDigitalizar_Ambulatoria()
     {
          $request = $_REQUEST;
          $datos_empresa = SessionGetVar("DatosEmpresaAF");
       
          $empresa=$datos_empresa['empresa_id'];
        
          $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
          $inf_p = AutoCarga::factory('InformacionPacientes');
          $tipo_id_paciente=$request['tipo_id_paciente'];
          $paciente_id=$request['paciente_id'];
          $plan_id=$request['plan_id'];
          $formula_id=$request['formula_id'];
          
     
          IncludeFileModulo("Formulacion_E","RemoteXajax","app","Formulacion_Externa");
          $this->SetXajax(array("Marcar_Producto","Autorizacion_despacho","Cambiarvetana","Eliminar_codigo_prodcto_d","MostrarProductox","Cancelar_Proceso","GuardarPT","BuscarProducto1","Guardartmp_ambu","IngresarDX","MostrarDX"),"app_modules/Formulacion_Externa/RemoteXajax/Formulacion_E.php","ISO-8859-1");
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("CrossBrowserDrag");         
          $tiempo_entrega= ModuloGetVar('app','Formulacion_Externa','Tiempo_tratamiento_Formulacion_'.trim($empresa));
      
		
        
         if($request['autorizar']=='SI')
         {
               $vector=$obje->UpdateAutorizacion_por_Formula($formula_id,$request['observaciones']);
         }
        
       
        $conteo =$pagina=0;
        $datos=$obje->ConsultarListaDetalle($request['buscador'],$empresa,$tipo_id_paciente,$paciente_id,$datos_empresa,$request['offset']);
            
        $medi_form=$obje->Medicamentos_Formulados_tmp($tipo_id_paciente,$paciente_id,$formula_id);
            
        $action['buscador']=ModuloGetURL('app','Formulacion_Externa','controller','FormaDigitalizar_Ambulatoria',array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"formula_id"=>$formula_id));
        $conteo= $obje->conteo;
        $pagina= $obje-> pagina;
        $action['paginador'] = ModuloGetURL('app', 'Formulacion_Externa', 'controller', 'FormaDigitalizar_Ambulatoria',array("buscador"=>$request['buscador'],"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"formula_id"=>$formula_id));
        
        $action['autorizar'] = ModuloGetURL('app', 'Formulacion_Externa', 'controller', 'FormaDigitalizar_Ambulatoria',array("autorizar"=>"SI","tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"formula_id"=>$formula_id));
       
         $action['marcado'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "FormaDigitalizar_Ambulatoria",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"formula_id"=>$formula_id));
		$Datos_Paciente = $inf_p->ValidarInformacion($request); 
			if($Datos_Paciente==3)
				$Datos_Paciente = $obje->ObtenerDatosAfiliado_($request); 
		
      
        $Cabecera_Formulacion_=$obje->consultar_Formulacion_TITMP($request,$empresa,$formula_id);
        /*$Cabecera_Formulacion_.=$Datos_Paciente;*/
		
		$Datos_Ad=$obje->Dato_Adionales_afiliacion($request);
		
		
        $DX_=$obje->Diagnostico_Temporal($tipo_id_paciente,$paciente_id,$formula_id);
        $privilegios=$obje->Usuario_Privilegios_($datos_empresa);
      //  	$existe_f=$obje->ConsultarInformacion_Temporal_Formula($formula_id);
        $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Formula_Digitalizar_Inicio",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"formula_id"=>$formula_id));
        $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
       $this->salida = $act->FormaBuscarProductos_Ambulatoria($action,$request['buscador'],$datos,$conteo,$pagina,$medi_form,$Cabecera_Formulacion_,$DX_,$request,$Datos_Ad,$formula_id,$tipo_id_paciente,$paciente_id,$plan_id,$tiempo_entrega,$datos_empresa,$privilegios);

        return true;
      
	}
  
   	/*
	  * Funcion que permite preparar un documento con los medicamentos a despachar
	  * @return boolean */
  
		function Preparar_Documento_Dispensacion()
		{
		   
          $request = $_REQUEST;
          $datos_empresa = SessionGetVar("DatosEmpresaAF");
     
          IncludeFileModulo("Formulacion_E","RemoteXajax","app","Formulacion_Externa");
          $this->SetXajax(array("PacienteReclama"),"app_modules/Formulacion_Externa/RemoteXajax/Formulacion_E.php","ISO-8859-1");
			
			
          $formula_id=$request['formula_id'];
          $todo_pendiente=$request['todopendiente'];
     
            $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
          $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "FormaDigitalizar_Ambulatoria",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"formula_id"=>$formula_id));
          $Cabecera_Formulacion=$obje->consultar_Formulacion_TMP($datos_empresa,$formula_id);
          $temporales=$obje->Buscar_producto_tmp_conc($formula_id);
          $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "FormaDigitalizar_Ambulatoria",array("tipo_id_paciente"=>$Cabecera_Formulacion['tipo_id_paciente'],"paciente_id"=>$Cabecera_Formulacion['paciente_id'],"plan_id"=>$Cabecera_Formulacion['plan_id'],"formula_id"=>$formula_id));

          $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
          $this->salida = $act->Forma_Preparar_Documento_Dispensar_($action,$datos_empresa,$Cabecera_Formulacion,$temporales,$formula_id,$todo_pendiente);
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
			
            $datos_empresa = SessionGetVar("DatosEmpresaAF");
			
            $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
			$inf_p = AutoCarga::factory('InformacionPacientes');
            $ParametrizacionReformular= ModuloGetVar('','','ParametrizacionReformular');

            $todo_pendiente=$request['todo_pendiente'];
            $Cabecera_Formulacion=$obje->Formula_CamposB_tmp($datos_empresa,$formula_id);
            $DX_=$obje->Diagnostico_Temporal($Cabecera_Formulacion['tipo_id_paciente'],$Cabecera_Formulacion['paciente_id'],$formula_id);
			
			$Datos_Paciente = $inf_p->ValidarInformacion($Cabecera_Formulacion); 
			if($Datos_Paciente==3)
				$Datos_Paciente = $obje->ObtenerDatosAfiliado_($Cabecera_Formulacion); 
			
            $medi_form=$obje->Medicamentos_Formulados_tmp_($formula_id);
			
						
            $final_formula=$obje->FormulaReal_AMB($datos_empresa,$Cabecera_Formulacion,$DX_,$medi_form,$Datos_Paciente);

            $desp = AutoCarga::factory('DispensacionMedicamentos');
            $plan_id=$Cabecera_Formulacion['plan_id'];

            $MAX_F=$obje->Consulta_Max_Formulacion($datos_empresa['empresa_id'],$Cabecera_Formulacion['tipo_id_paciente'],$Cabecera_Formulacion['paciente_id']);

            $opcion=1;

            $Datopciones = $desp->MenuOpcion_FormulacionExterna($opcion,$datos_empresa,$MAX_F['tmp_id'],$observacion,$plan_id,$formula_id,$medi_form,$todo_pendiente);

            $pendientes=$obje->Medicamentos_Pendientes_Esm($MAX_F['tmp_id']);
            $Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($MAX_F['tmp_id']);

            $dix_r=$obje->Diagnostico_Real($MAX_F['tmp_id']);
            $medi_form=$obje->Medicamentos_Formulados_R($MAX_F['tmp_id']);

            $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
            $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "main");
			
			
			
            $this->salida .= $act->FormaPintarUltimoPaso($action,$MAX_F['tmp_id'],$pendientes,$todo_pendiente,'1',$medi_form,$Cabecera_Formulacion,$dix_r);
            return true;
		}
   
   /*
    * Funcion que permite buscar los tickets de las formulas despachadas    
    * @return boolean
    */
     function Tickets_Dispensacion()
    {
          $request = $_REQUEST;
          $datos_empresa = SessionGetVar("DatosEmpresaAF");
   
          $bodegas_doc_id= ModuloGetVar('app','Formulacion_Externa','documento_dispensacion_'.trim($datos_empresa['empresa_id']).'_'.trim($datos_empresa['bodega']));
	
          $mdl = AutoCarga::factory("Tickets_DispensacionHTML","views","app","Formulacion_Externa");
          $cls = AutoCarga::factory("Tickets_DispensacionSQL","classes","app","Formulacion_Externa");
          $Tipo_Id_paciente=$cls ->Tipos_Ids();
          $datosN = array();
     
          if($request['buscador'])
          {
                  $datosN = $cls->Obtener_Reporte($request['buscador'],$request['offset']);
               
          }
        $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "main");
        $action['buscar'] = ModuloGetURL('app','Formulacion_Externa','controller','Tickets_Dispensacion');
        $action['paginador'] = ModuloGetURL('app','Formulacion_Externa','controller','Tickets_Dispensacion',array("buscador"=>$request['buscador']));
        $action['pendiente'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "FormulasPaciente_p");
        $this->salida .= $mdl->Forma($action,$request['buscador'],$datosN,$Tipo_Id_paciente, $cls->conteo, $cls->pagina,$bodegas_doc_id);
        return true;
    } 
  
   	/*
    * Funcion que permite  consultar los medicamentos pendientes por dispensar    
    * @return boolean
    */
    function FormulasPaciente_p()
		{
          $request = $_REQUEST;
          $datos_empresa = SessionGetVar("DatosEmpresaAF");
          
          $formula_id=$request['formula_id'];
          $tipo_id_paciente=$request['tipo_id_paciente'];
          $paciente_id=$request['paciente_id'];
          $plan_id=$request['plan_id'];
          
          
          $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
       
          $Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($formula_id);
          $Datos_Ad=$obje->Dato_Adionales_afiliacion($request);
          $dix_r=$obje->Diagnostico_Real($formula_id);
          $medi_form=$obje->Medicamentos_Pendientes_Esm($formula_id);

          IncludeFileModulo("Formulacion_E","RemoteXajax","app","Formulacion_Externa");
          $this->SetXajax(array("Cambiarvetana2","Eliminar_codigo_prodcto_d2","BuscarProducto2","GuardarPTP","MostrarProductox2","MandarInformacion","MostrarMensaje","InsertarDatosFormula_tmp","EliminarDatosFormula_tmp"),"app_modules/Formulacion_Externa/RemoteXajax/Formulacion_E.php","ISO-8859-1");
        
           $fedatos= date("Y-m-d");
          $request['fecha'] = date("Y-m-d");

          $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Tickets_Dispensacion");
          $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
          $this->salida = $act->FormaFomulaPaciente_P($action,$request,$datos,$paciente[0],$Cabecera_Formulacion,$request,$Datos_Ad,$opcion,$dix_r,$medi_form,$formula_id,$datos_empresa);
          return true;
		}
    /*
    * Funcion que permite preparar el documento a dispensar para los medicamentos pendientes    
    * @return boolean
    */
      
    function Preparar_Documento_Dispensacion_Pendientes()
		{
		        $request = $_REQUEST;
            $datos_empresa = SessionGetVar("DatosEmpresaAF");

            IncludeFileModulo("Formulacion_E","RemoteXajax","app","Formulacion_Externa");
            $this->SetXajax(array("PacienteReclama_P"),"app_modules/Formulacion_Externa/RemoteXajax/Formulacion_E.php","ISO-8859-1");
            $formula_id=$request['formula_id'];

            $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
          
            $Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($formula_id);
            $temporales=$obje->Buscar_producto_tmp_p($formula_id);
            $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
            $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "FormulasPaciente_p",array("formula_id"=>$formula_id,"tipo_id_paciente"=>$Cabecera_Formulacion['tipo_id_paciente'],"paciente_id"=>$Cabecera_Formulacion['paciente_id']));
            $this->salida = $act->Forma_Preparar_Documento_DispensarPendientes($action,$datos_empresa,$Cabecera_Formulacion,$temporales,$formula_id);
            return true;
		}
  
  	/*
	  * Funcion que permite realizar la entrega de los medicamentos pendientes 
	  * @return boolean */
  	function EntregaMedicamentos_Pendientes()
		{
          $request = $_REQUEST;
          $observacion=$request['observacion'];
          $formula_id=$request['formula_id'];
          
		$datos_empresa = SessionGetVar("DatosEmpresaAF");

		$obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
		$ParametrizacionReformular= ModuloGetVar('','','ParametrizacionReformular');

		$Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($formula_id);
		$medi_form=$obje->Medicamentos_Formulados_R($formula_id);

		$desp = AutoCarga::factory('DispensacionMedicamentos');
		$opcion=2;
          	
     
          $Datopciones = $desp->MenuOpcion_FormulacionExterna($opcion,$datos_empresa,$formula_id,$observacion,$Cabecera_Formulacion['plan_id'],NULL,$medi_form,$todo_pendiente);
          $pendientes=$obje->Medicamentos_Pendientes_Esm($formula_id);
    
          $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
          $action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "main");
          $this->salida .= $act->FormaPintarUltimoPaso($action,$formula_id,$pendientes,$todo_pendiente);
          return true;
		}
  
  
   /*
	    * Funcion Permite Crear Formulacion Real ********
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
       
        $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
        $Cabecera_Formulacion_=$obje->consultar_Formulacion_ITMP($request,$empresa);
        $DX_=$obje->Diagnostico_Temporal($tipo_id_paciente,$paciente_id,$request['tmp_id']);
		    $MEDIC_=$obje->Medicamentos_Formulados_tmp($tipo_id_paciente,$paciente_id,$request['tmp_id']);
        
        $final_formula=$obje->FormulaReal_AMB($Cabecera_Formulacion_,$DX_,$MEDIC_);
        $B_TMP=$obje->Eliminar_tmp($request,$empresa);
	   	  $MAX_F=$obje->Consulta_Max_Formulacion($empresa,$tipo_id_paciente,$paciente_id);
        $Cabecera_Formulacion=$obje->Consulta_Formulacion_Real_I($MAX_F['tmp_id']);
        $Datos_Ad=$obje->Dato_Adionales_afiliacion($request);
        $dix_r=$obje->Diagnostico_Real($MAX_F['tmp_id']);
				$medi_form=$obje->Medicamentos_Formulados_R($MAX_F['tmp_id']);
        
        $permisos = $obje->ObtenerPermisos_dispensacion($datos_empresa);
	
				$action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "main");
				$action['anular'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Anulacion_Formula",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"tmp_id"=>$MAX_F['tmp_id']));
				$action['cambiar_modulo'] = ModuloGetURL("app", "DispensacionESM", "controller", "FormulasPaciente",array("dispensar"=>'SI',"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"formula_id"=>$MAX_F['tmp_id'],"DispensacionESM"=>$permisos,"plan_id"=>$plan_id));
		    $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
        $this->salida = $act->FormaCabeceraFormulaCompleta_ambu($action,$Cabecera_Formulacion,$Cabecera_Formulacion_AESM,$request,$Cabecera_Formulacion_AEM,$Datos_Fueza,$Datos_Ad,$ESM_pac,$ESM_,$opcion,$IPS_,$dix_r,$medi_form,$permisos);
				return true;
	
    }
      
    /*
	    * Funcion Permite Anular la  Formulacion Real ********
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
				$obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
		    $url = ModuloGetURL("app","Formulacion_Externa","controller","main");
        
				if($request['anular']=="SI")
        {
             $anulado=$obje->Actulizar_Estado_Formula_($tmp_id);
                           
        }
      
         $msn= " ";
        if($anulado=='1')
        {
             $msn.= " SE ANULO CORRECTAMENTE"; 
        
        }
 
        $formula_papel_=$obje->consultar_Formulacion_Papel($tmp_id);
        
        $action['continuar'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "Anulacion_Formula",array("anular"=>"SI","tmp_id"=>$tmp_id));
				$action['volver'] = ModuloGetURL("app", "Formulacion_Externa", "controller", "main");
        $act = AutoCarga::factory("Formulacion_ExternaHTML", "views", "app", "Formulacion_Externa");
        $this->salida = $act->FormaFormulaAnulada($action,$formula_papel_,$msn,$request);
        return true;
	
    }
    /*
        * Funcion Permite Restar Dos Fechas********
        * @return valor
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
	} 
?>