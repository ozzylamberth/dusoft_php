<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: app_Dispensacion_controller.php,v 1.0
  * @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja Torres
  */
  /**
  * Clase Control: Dispensacion
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  /*/
  IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");
  IncludeClass("DispensacionMedicamentos");
  class app_Dispensacion_controller  extends classModulo
  {
    /**
    * Constructor de la clase
    */
    function app_Dispensacion_controller(){}
    /**
    * Funcion principal del modulo
    * @return boolean
    */

function main()
    {
      $request = $_REQUEST;
      $sql = AutoCarga::factory('DispensacionSQL', '', 'app', 'Dispensacion');
      $permisos = $sql->ObtenerPermisos();

      $ttl_gral = "DISPENSACION DE MEDICAMENTOS ";
      $mtz[0] = 'FARMACIAS';
      $mtz[1] = 'CENTRO DE UTILIDAD';
      $mtz[3] = 'BODEGA';
      $url[0] = 'app';
      $url[1] = 'Dispensacion';
      $url[2] = 'controller';
      $url[3] = 'MenuDispensacion';
      $url[4] = 'Dispensacion';


      $action['volver'] = ModuloGetURL('system', 'Menu');
      $this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
      return true;
    }
   /*
    * Funcion que permite ir a los diferentes menus del modulo de Dispensacion
    * @return boolean
    */
    function MenuDispensacion()
    {
      $request = $_REQUEST;
      if($request['Dispensacion'])
      SessionSetVar("DatosEmpresaAF",$request['Dispensacion']);
      $empresa = SessionGetVar("DatosEmpresaAF");
      $farmacia=$empresa['empresa_id'];

      $bodega=$empresa['bodega'];

          $bodegas_doc_id= ModuloGetVar('app','Dispensacion','documento_dispensacion_'.trim($farmacia).'_'.trim($bodega));

      $action['volver'] = ModuloGetURL("app", "Dispensacion", "controller", "main");
            $act = AutoCarga::factory("DispensacionHTML", "views", "app", "Dispensacion");
      $mdl = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");

      if(empty($bodegas_doc_id))
      {
        $this->salida = $act->FormaMenuMensaje($action);
      }
      else
      {
        $action['Formulas'] = ModuloGetURL("app", "Dispensacion", "controller", "BuscardorDeFormulas");
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

      $mdl = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
      $Tipo = $mdl->ConsultarTipoId();
      $empresa = SessionGetVar("DatosEmpresaAF");
            $farmacia=$empresa['empresa_id'];

      $request['buscador']['fecha'] = date("Y-m-d");
      $conteo =$pagina=0;

      $datos=$mdl->ObtenerFormulasMedicas($request['buscador'],$request['offset']);
      $action['buscador']=ModuloGetURL('app','Dispensacion','controller','BuscardorDeFormulas');
      $conteo= $mdl->conteo;
      $pagina= $mdl-> pagina;
      $action['paginador'] = ModuloGetURL('app', 'Dispensacion', 'controller', 'BuscardorDeFormulas',array("buscador"=>$request['buscador'],"plan"=>$plan_atencion));
      $action['consul'] = ModuloGetURL("app", "Dispensacion", "controller", "FormulasPaciente");

      $action['pendiente'] = ModuloGetURL("app", "Dispensacion", "controller", "MenuPendientes");
        $Planes = $mdl->ConsultaPlanes_Bodega($farmacia);

      $action['volver'] = ModuloGetURL("app", "Dispensacion", "controller", "MenuDispensacion");
      $act = AutoCarga::factory("DispensacionHTML", "views", "app", "Dispensacion");
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
          $evolucion=$request['evolucion_id'];

          IncludeFileModulo("Dispensacion","RemoteXajax","app","Dispensacion");
          $this->SetXajax(array("Cambiarvetana","Autorizacion_despacho","Eliminar_codigo_prodcto_d","BuscarProducto1","GuardarPT","MostrarProductox","MandarInformacion","MostrarMensaje","InsertarDatosFormula_tmp","EliminarDatosFormula_tmp"),"app_modules/Dispensacion/RemoteXajax/Dispensacion.php","ISO-8859-1");
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("CrossBrowserDrag");

          $mdl = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
          $fedatos= date("Y-m-d");
          $request['fecha'] = date("Y-m-d");

          $paciente = $mdl->ObtenerFormulasMedicas($request);
          $medi_form=$mdl->Medicamentos_Formulados_R($paciente);

          $action['volver'] = ModuloGetURL("app", "Dispensacion", "controller", "BuscardorDeFormulas");
          $act = AutoCarga::factory("DispensacionHTML", "views", "app", "Dispensacion");
          $this->salida = $act->FormaFomulaPaciente($action,$paciente,$medi_form,$request,$datos,$paciente,$evolucion);
          return true;
    }
    /*  PREPARAR EL DOCUMENTO PARA DESPACHAR */
    function Preparar_Documento_Dispensacion()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("DatosEmpresaAF");

      IncludeFileModulo("Dispensacion","RemoteXajax","app","Dispensacion");
      $this->SetXajax(array("PacienteReclama","PersonaRclama","ValidarDatosPersona"),"app_modules/Dispensacion/RemoteXajax/Dispensacion.php","ISO-8859-1");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserEvent");
      $this->IncludeJS("CrossBrowserDrag");
      $fedatos= date("Y-m-d");
      $request['fecha'] = date("Y-m-d");
      $evolucion=$request['evolucion'];

      $mdl = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
      $action['volver'] = ModuloGetURL("app", "Dispensacion", "controller", "FormulasPaciente",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"evolucion"=>$evolucion));

      $paciente = $mdl->ObtenerFormulasCabecera($evolucion,$request);
      $temporales=$mdl->Buscar_producto_tmp_c($evolucion);
      $plan_id=$paciente['plan_id'];
      $tipo_id_paciente=$paciente['tipo_id_paciente'];
      $paciente_id=$paciente['paciente_id'];
      $todo_pendiente=$request['todopendiente'];

      $action['volver'] = ModuloGetURL("app", "Dispensacion", "controller", "FormulasPaciente",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"evolucion"=>$evolucion));
      $act = AutoCarga::factory("DispensacionHTML", "views", "app", "Dispensacion");
      $this->salida = $act->Forma_Preparar_Documento_Dispensar_($action,$empresa,$paciente,$temporales,$evolucion,$pendiente,$todo_pendiente);
      return true;
    }



    /*  CUANDO RECLAMA EL PACIENTE  */
    function GenerarEntregaMedicamentos()
    {
        $request = $_REQUEST;
        $observacion=$request['observacion'];
        $evolucion=$request['evolucion'];

        $pendiente=$request['pendiente'];

        $empresa = SessionGetVar("DatosEmpresaAF");
        $empre=$empresa['empresa_id'];
        $centro=$empresa['centro_utilidad'];
        $bodega=$empresa['bodega'];
        $fedatos= date("Y-m-d");
        $request['fecha'] = date("Y-m-d");
        $obje = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
        $ParametrizacionReformular= ModuloGetVar('','','ParametrizacionReformular');


        $desp = AutoCarga::factory('DispensacionMedicamentos');
        $Cabecera_Formulacion = $obje->ObtenerFormulasCabecera($evolucion,$request);
        $plan=$Cabecera_Formulacion['plan_id'];


        if($pendiente=='0')
        {
          $opcion=2;
        }else
        {
          $opcion=1;

        }
         $todo_pendiente=$request['todo_pendiente'];

         if($todo_pendiente=='1')
         {
        $actualizacion=$obje->UpdateEstad_Form($evolucion);


         }

        $Datopciones = $desp->MenuOpcion($opcion,$empre,$bodega,$ParametrizacionReformular,$observacion,$plan,$evolucion,$todo_pendiente);


        $pendientes=$obje->Medicamentos_Pendientes($evolucion);
      $act = AutoCarga::factory("DispensacionHTML", "views", "app", "Dispensacion");
      $action['volver'] = ModuloGetURL("app", "Dispensacion", "controller", "main");
      $this->salida .= $act->FormaPintarUltimoPaso($action,$formula_id,$pendientes,$evolucion,$todo_pendiente);
      return true;
    }


   /********************** MENU PENDIENTES    **********************************************/
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
          $action['r_evento'] = ModuloGetURL("app", "Dispensacion", "controller", "Registro_pendiente",array("datos"=>$request));
          $action['b_evento'] = ModuloGetURL("app", "Dispensacion", "controller", "Buscar_pendiente",array("datos"=>$request));

          $mdl = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
          $action['volver'] = ModuloGetURL("app", "Dispensacion", "controller", "BuscardorDeFormulas");
          $act = AutoCarga::factory("DispensacionHTML", "views", "app", "Dispensacion");
          $this->salida = $act->FormaMenuPendiente($action,$request,$paciente);
          return true;
  }
  /**/
  function Registro_pendiente()
  {
      $request = $_REQUEST;
      $datos=$request['datos'];
      $tipo=$datos['paciente_id'];

      $mdl = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
      $paciente = $mdl->Consultar_DatosA_Paciente($datos);
      $bandera=0;
      $action['entrega'] = ModuloGetURL("app", "Dispensacion", "controller", "Registro_pendiente",array("datos"=>$datos));
      $informacion=$mdl->Medicamentos_Pendientes($datos['evolucion_id']);


        $msm=" ";
    if($request['bandera']==1)
    {
       $bandera=1;
       $evento = $mdl->Registrar_Evento($datos,$request,$informacion);
       if($evento==true)
       {
         $msm= " <td colspan=\"1\" align=\"left\" class=\"formulacion_table_list\" > SE REGISTRO EL EVENTO  </td> ";
       }else
       {

        $msm= " <td colspan=\"3\" align=\"left\" class=\"formulacion_table_list\" > ".$mdl->mensajeDeError."</td> ";

       }

    }
    $action['pendiente'] = ModuloGetURL("app", "Dispensacion", "controller", "FormulasPaciente_p");

    $action['volver'] = ModuloGetURL("app", "Dispensacion", "controller", "MenuPendientes",array("tipo_id_paciente"=>$datos['tipo_id_paciente'],"paciente_id"=>$datos['paciente_id'],"apellidos"=>$datos['apellidos'],"nombres"=>$datos['nombres'],"fecha_formulacion"=>$datos['fecha_formulacion'],"fecha_finalizacion"=>$datos['fecha_finalizacion'],"nombre"=>$datos['nombre'],"evolucion_id"=>$datos['evolucion_id'],"plan_descripcion"=>$datos['plan_descripcion']));
    $act = AutoCarga::factory("DispensacionHTML", "views", "app", "Dispensacion");
    $this->salida = $act->FormaRegistrarEvento($action,$datos,$paciente,$informacion,$bandera,$msm,$datos['evolucion_id']);
      return true;

  }

    /* PENDIENTES */
    function FormulasPaciente_p()
    {
        $request = $_REQUEST;
        $empresa = SessionGetVar("DatosEmpresaAF");

        $evolucion=$request['evolucion'];
        $mdl = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
        $fedatos= date("Y-m-d");
        $request['fecha'] = date("Y-m-d");

        $paciente = $mdl->ObtenerFormulasCabecera($evolucion,$request);
        $medi_form=$mdl->Medicamentos_Pendientes($evolucion);

        IncludeFileModulo("Dispensacion","RemoteXajax","app","Dispensacion");
        $this->SetXajax(array("Cambiarvetana2","Eliminar_codigo_prodcto_d2","BuscarProducto2","GuardarPTP","MostrarProductox2","MandarInformacion","MostrarMensaje","InsertarDatosFormula_tmp","EliminarDatosFormula_tmp"),"app_modules/Dispensacion/RemoteXajax/Dispensacion.php","ISO-8859-1");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

      $action['volver'] = ModuloGetURL("app", "Dispensacion", "controller", "BuscardorDeFormulas");
      $act = AutoCarga::factory("DispensacionHTML", "views", "app", "Dispensacion");
      $this->salida = $act->FormaFomulaPaciente_P($action,$request,$paciente,$medi_form,$evolucion);
      return true;
    }

  /*  PREPARANDO PRODUCTOS PENDIENTES A ENTREGAR */
    function Preparar_Documento_Dispensacion_Pendientes()
    {

      $request = $_REQUEST;
      $empresa = SessionGetVar("DatosEmpresaAF");

      IncludeFileModulo("Dispensacion","RemoteXajax","app","Dispensacion");
      $this->SetXajax(array("PacienteReclama","PersonaRclama","ValidarDatosPersona"),"app_modules/Dispensacion/RemoteXajax/Dispensacion.php","ISO-8859-1");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserEvent");
      $this->IncludeJS("CrossBrowserDrag");

      $fedatos= date("Y-m-d");
      $request['fecha'] = date("Y-m-d");
      $evolucion=$request['evolucion'];

        $mdl = AutoCarga::factory("DispensacionSQL", "classes", "app", "Dispensacion");
        $pendiente='0';
        $paciente = $mdl->ObtenerFormulasCabecera($evolucion,$request);
        $plan_id=$paciente['plan_id'];
        $tipo_id_paciente=$paciente['tipo_id_paciente'];
        $paciente_id=$paciente['paciente_id'];
        $temporales=$mdl->Buscar_producto_tmp_c($evolucion);
        $act = AutoCarga::factory("DispensacionHTML", "views", "app", "Dispensacion");
        $action['volver'] = ModuloGetURL("app", "Dispensacion", "controller", "FormulasPaciente_p",array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"plan_id"=>$plan_id,"evolucion"=>$evolucion));

      $this->salida = $act->Forma_Preparar_Documento_Dispensar_($action,$empresa,$paciente,$temporales,$evolucion,$pendiente);
      return true;
    }

  }
?>