<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: IngresoAfiliados.php,v 1.19 2008/01/11 15:36:03 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.19 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */

  /**
  * Funcion donde se crea el combo para la seleccionar el subgrupo principal
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function SeleccionarSubGrupoPrincipal($form)
  {
    $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
    $subgrupoprincipal = $afi->ObtenerSubGruposPrincipalesOcupacion($form['grandes_grupos']);

    $html  = "document.registrar_afiliacion.sub_grupos_principales.options.length = 0 ;\n";
        $html .= "document.registrar_afiliacion.sub_grupos_principales.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
        $html .= "document.registrar_afiliacion.sub_grupo.options.length = 0 ;\n";
        $html .= "document.registrar_afiliacion.sub_grupo.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
        $html .= "document.registrar_afiliacion.grupos_primarios.options.length = 0 ;\n";
        $html .= "document.registrar_afiliacion.grupos_primarios.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";

    $i = 1;
        foreach($subgrupoprincipal as $key => $dtl)
    {
            $html .= "document.registrar_afiliacion.sub_grupos_principales.options[".$i."] = new Option('".utf8_encode(substr($dtl['descripcion_ciuo_88_subgrupo_principal'],0,40))."','".$key."',false, false);\n";
            $html .= "document.registrar_afiliacion.sub_grupos_principales.options[".($i++)."].title = '".utf8_encode($dtl['descripcion_ciuo_88_subgrupo_principal'])."';\n";
        }
        $objResponse = new xajaxResponse();
        $objResponse->script($html);

    return $objResponse;
  }
  /**
  * Funcion donde se crea el combo para la seleccionar el subgrupo
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function SeleccionarSubGrupos($form)
  {
    $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
    $subgrupo = $afi->ObtenerSubGruposOcupacion($form['grandes_grupos'],$form['sub_grupos_principales']);

    $html  = "document.registrar_afiliacion.sub_grupo.options.length = 0 ;\n";
        $html .= "document.registrar_afiliacion.sub_grupo.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
        $html .= "document.registrar_afiliacion.grupos_primarios.options.length = 0 ;\n";
        $html .= "document.registrar_afiliacion.grupos_primarios.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";

    $i = 1;
        foreach($subgrupo as $key => $dtl)
    {
            $html .= "document.registrar_afiliacion.sub_grupo.options[".$i."] = new Option('".utf8_encode(substr($dtl['descripcion_ciuo_88_subgrupo'],0,40))."','".$key."',false, false);\n";
            $html .= "document.registrar_afiliacion.sub_grupo.options[".($i++)."].title = '".utf8_encode($dtl['descripcion_ciuo_88_subgrupo'])."';\n";
        }
        $objResponse = new xajaxResponse();
        $objResponse->script($html);

    return $objResponse;
  }
  /**
  * Funcion donde se crea el combo para la seleccionar el grupo primario
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function SeleccionarGruposPrimarios($form)
  {
    $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
    $grupo_primario = $afi->ObtenerGruposPrimariosOcupacion($form['grandes_grupos'],$form['sub_grupos_principales'],$form['sub_grupo']);

    $html  = "document.registrar_afiliacion.grupos_primarios.options.length = 0 ;\n";
        $html .= "document.registrar_afiliacion.grupos_primarios.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
        $i = 1;
        foreach($grupo_primario as $key => $dtl)
    {
            $html .= "document.registrar_afiliacion.grupos_primarios.options[".$i."] = new Option('".utf8_encode(substr($dtl['descripcion_ciuo_88_grupo_primario'],0,40))."','".$key."',false, false);\n";
            $html .= "document.registrar_afiliacion.grupos_primarios.options[".($i++)."].title = '".utf8_encode($dtl['descripcion_ciuo_88_grupo_primario'])."';\n";
        }
        $objResponse = new xajaxResponse();
        $objResponse->script($html);

    return $objResponse;
  }
  /**
  * Funcion donde se crea el combo para la seleccionar el grupo de la actividad economica
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function SeleccionarActividad($form)
  {
    $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
    $grupo_primario = $afi->ObtenerGruposActividadEconomica($form['division_actividad']);

    $html  = "document.registrar_afiliacion.grupo_actividad.options.length = 0 ;\n";
        $html .= "document.registrar_afiliacion.grupo_actividad.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
        $i = 1;
        foreach($grupo_primario as $key => $dtl)
    {
            $html .= "document.registrar_afiliacion.grupo_actividad.options[".$i."] = new Option('".utf8_encode(substr($dtl['descripcion_ciiu_r3_grupo'],0,40))."','".$key."',false, false);\n";
            $html .= "document.registrar_afiliacion.grupo_actividad.options[".($i++)."].title = '".utf8_encode($dtl['descripcion_ciiu_r3_grupo'])."';\n";
        }
        $objResponse = new xajaxResponse();
        $objResponse->script($html);

    return $objResponse;
  }
  /**
  * Funcion donde se alamacena los datos de los beneficiarios
  * que se adicionan en una variable de sesion
  *
  * @param array $forma Vector con los datos de la forma
  *
  * @return object
  */
  function AdicionarBeneficiario($forma)
  {
    $objResponse = new xajaxResponse();
    $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");

    $rst = $afi->VerificarExistenciaAfiliado($forma['tipo_id_beneficiario'],$forma['documento']);
    if(!$rst)
      $objResponse->assign('error','innerHTML',"EL DOCUMENTO ".$forma['tipo_id_beneficiario']." ".$forma['documento'].", YA HA SIDO REGISTRADO");
    else
    {
      $beneficiarios = array();
      $beneficiarios = SessionGetVar("beneficiarios");
      $beneficiarios[$forma['tipo_id_beneficiario']][$forma['documento']] = $forma;

      $datos = $afi->ObtenerTiposParentescos($forma['parentesco']);
      $beneficiarios[$forma['tipo_id_beneficiario']][$forma['documento']]['parentesco_texto'] = utf8_encode($datos[$forma['parentesco']]['descripcion_parentesco']);

      $datos_afiliado = $afi->ObtenerDatosAfiliados(array("tipo_id_paciente"=>$forma['tipo_id_beneficiario'],"documento"=>$forma['documento']));
      (empty($datos_afiliado))? $beneficiarios[$forma['tipo_id_beneficiario']][$forma['documento']]['accion'] = 0: $beneficiarios[$forma['tipo_id_beneficiario']][$forma['documento']]['accion'] = 1;

      SessionSetVar("beneficiarios",$beneficiarios);

      if($datos[$forma['parentesco']]['mensaje_confirmar_afiliacion'])
        $objResponse->alert($datos[$forma['parentesco']]['mensaje_confirmar_afiliacion']);

      $objResponse->call("cerrarVentana");
    }
    return $objResponse;
  }
  /**
  * Funcion donde se crea una tabla con los datos de los beneficiarios que
  * se van adicionando
  *
  * @return object
  */
  function MostrarTablaBeneficiarios()
  {
    $objResponse = new xajaxResponse();

    $beneficiarios = array();
    $beneficiarios = SessionGetVar("beneficiarios");
    
    if(!empty($beneficiarios))
    {
      $mdl = AutoCarga::factory("IngresoAfiliadoHTML", "views", "app","UV_Afiliaciones");
      $html = $mdl->FormaCapaBeneficiarios($beneficiarios);
    }
    $objResponse->assign("informacion","innerHTML",$html);

    return $objResponse;
  }
  /**
  * Funcion donde se remueve de la variable de sesion un beneficiario
  *
  * @param string $tipo_identificacion Tipo de identificacion del beneficiario
  * @param string $documento Documento de identificacion del beneficiario
  *
  * @return object
  */
  function EliminarBeneficiario($tipo_identificacion,$documento)
  {
    $objResponse = new xajaxResponse();

    $beneficiarios = array();
    $beneficiarios = SessionGetVar("beneficiarios");

    unset($beneficiarios[$tipo_identificacion][$documento]);

    $mdl = AutoCarga::factory("IngresoAfiliadoHTML", "views", "app","UV_Afiliaciones");

    $html = "";
    if(!empty($beneficiarios))
        $html = $mdl->FormaCapaBeneficiarios($beneficiarios);

    SessionSetVar("beneficiarios",$beneficiarios);
    $objResponse->assign("informacion","innerHTML",$html);

    return $objResponse;
  }
  /**
  * Funcion que hace la busqueda de un afiliado en la base de datos,
  * para indicar si pude hacerse una afiliacion o no
  *
  * @param array $datos Vector con los datos de la identificacion del afiliado
  *
  * @return object
  */
  function BuscarAfiliado($datos)
  {
    $objResponse = new xajaxResponse();
    $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");

    $registros = $afi->ObtenerDatosAfiliados($datos,"NOT");

    if(!empty($registros))
    {
      $html = "NO SE PUEDE REALIZAR UNA NUEVA AFILIACION, DEBIDO A QUE YA HAY UNA AFILIACION REGISTRADA";
      $objResponse->assign("error","innerHTML",$html);
    }
    else
    {
      $objResponse->call("continuarAfiliacion");
    }
    return $objResponse;
  }
  /**
  * Funcion que hace la seleccion por defecto de la ocupacion y la actividad economica
  *
  * @param array $form Vector con los datos de la forma
  * @param string $grupo Identificador del grupo de la actividad economica
  * @param string $grupo_ocupacion Identificador del grupo de la ocupacion
  *
  * @return object
  */
  function SeleccionarDatosDefecto($form,$grupo,$grupo_ocupacion)
  {
    $objResponse = new xajaxResponse();
    $html = "";
    if($grupo) $html .= SeleccionarDefaultActividad($form,$grupo,&$objResponse);

    if($grupo_ocupacion) $html .= SeleccionarOcupacionDefecto($grupo_ocupacion,&$objResponse);

    $objResponse->script($html);
    return $objResponse;
  }
  /**
  * Funcion que realiza el script para hacer la seleccion por defecto de
  * la actividad economica
  *
  * @param array $form Vector con los datos de la forma
  * @param string $grupo Identificador del grupo de la actividad economica
  * @param object $objResponse Objeto de la clase xajaxResponse
  *
  * @return String
  */
  function SeleccionarDefaultActividad($form,$grupo,$objResponse)
  {
    $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
    $grupo_primario = $afi->ObtenerGruposActividadEconomica($form['division_actividad']);
    $texto = "";

    $html  = "document.registrar_afiliacion.grupo_actividad.options.length = 0 ;\n";
    $html .= "document.registrar_afiliacion.grupo_actividad.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
    $i = 1;
    foreach($grupo_primario as $key => $dtl)
    {
      $html .= "document.registrar_afiliacion.grupo_actividad.options[".$i."] = new Option('".utf8_encode(substr($dtl['descripcion_ciiu_r3_grupo'],0,40))."','".$key."',false, false);\n";
      if($grupo == $key)
      {
        $texto = utf8_encode($dtl['descripcion_ciiu_r3_grupo']);
        $html .= "document.registrar_afiliacion.grupo_actividad.options[".$i."].selected = true;\n";
      }
      $html .= "document.registrar_afiliacion.grupo_actividad.options[".($i++)."].title = '".utf8_encode($dtl['descripcion_ciiu_r3_grupo'])."';\n";
    }

    $objResponse->assign('actividad_texto','innerHTML',$texto);
    return $html;
  }
  /**
  * Funcion que realiza el script para hacer la seleccion por defecto de
  * la ocupacion
  *
  * @param string $grupo Identificador del grupo de la ocupacion
  * @param object $objResponse Objeto de la clase xajaxResponse
  *
  * @return String
  */
  function SeleccionarOcupacionDefecto($grupo,$objResponse)
  {
    $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
    $datos = $afi->ObtenerDatosGrupoPrimario($grupo);

    $subgrupoprincipal = $afi->ObtenerSubGruposPrincipalesOcupacion($datos['ciuo_88_gran_grupo']);
    $subgrupo = $afi->ObtenerSubGruposOcupacion($datos['ciuo_88_gran_grupo'],$datos['ciuo_88_subgrupo_principal']);
    $grupo_primario = $afi->ObtenerGruposPrimariosOcupacion($datos['ciuo_88_gran_grupo'],$datos['ciuo_88_subgrupo_principal'],$datos['ciuo_88_subgrupo']);

    $html  = "document.registrar_afiliacion.sub_grupos_principales.options.length = 0 ;\n";
    $html .= "document.registrar_afiliacion.sub_grupos_principales.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
    $html .= "document.registrar_afiliacion.sub_grupo.options.length = 0 ;\n";
    $html .= "document.registrar_afiliacion.sub_grupo.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
    $html .= "document.registrar_afiliacion.grupos_primarios.options.length = 0 ;\n";
    $html .= "document.registrar_afiliacion.grupos_primarios.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";

    $i = 1;
    foreach($subgrupoprincipal as $key => $dtl)
    {
      $html .= "document.registrar_afiliacion.sub_grupos_principales.options[".$i."] = new Option('".utf8_encode(substr($dtl['descripcion_ciuo_88_subgrupo_principal'],0,40))."','".$key."',false, false);\n";
      if($key == $datos['ciuo_88_subgrupo_principal'])
        $html .= "document.registrar_afiliacion.sub_grupos_principales.options[".$i."].selected = true ;\n";
      $html .= "document.registrar_afiliacion.sub_grupos_principales.options[".($i++)."].title = '".utf8_encode($dtl['descripcion_ciuo_88_subgrupo_principal'])."';\n";
   }

    $html .= "document.registrar_afiliacion.sub_grupo.options.length = 0 ;\n";
    $html .= "document.registrar_afiliacion.sub_grupo.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
    $html .= "document.registrar_afiliacion.grupos_primarios.options.length = 0 ;\n";
    $html .= "document.registrar_afiliacion.grupos_primarios.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";

    $i = 1;
    foreach($subgrupo as $key => $dtl)
    {
      $html .= "document.registrar_afiliacion.sub_grupo.options[".$i."] = new Option('".utf8_encode(substr($dtl['descripcion_ciuo_88_subgrupo'],0,40))."','".$key."',false, false);\n";
      if($key == $datos['ciuo_88_subgrupo'])
        $html .= "document.registrar_afiliacion.sub_grupo.options[".$i."].selected = true;\n";
      $html .= "document.registrar_afiliacion.sub_grupo.options[".($i++)."].title = '".utf8_encode($dtl['descripcion_ciuo_88_subgrupo'])."';\n";
    }

    $html .= "document.registrar_afiliacion.grupos_primarios.options.length = 0 ;\n";
    $html .= "document.registrar_afiliacion.grupos_primarios.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
    $i = 1;
    $texto = "";
    foreach($grupo_primario as $key => $dtl)
    {
      $html .= "document.registrar_afiliacion.grupos_primarios.options[".$i."] = new Option('".utf8_encode(substr($dtl['descripcion_ciuo_88_grupo_primario'],0,40))."','".$key."',false, false);\n";
      if($key == $grupo)
      {
        $texto = utf8_encode($dtl['descripcion_ciuo_88_grupo_primario']);
        $html .= "document.registrar_afiliacion.grupos_primarios.options[".$i."].selected = true;\n";
      }
      $html .= "document.registrar_afiliacion.grupos_primarios.options[".($i++)."].title = '".utf8_encode($dtl['descripcion_ciuo_88_grupo_primario'])."';\n";
    }
    $html .= "  for(i=0; i< document.registrar_afiliacion.grandes_grupos.length;i++)";
    $html .= "  {";
    $html .= "    if(document.registrar_afiliacion.grandes_grupos.options[i].value == '".$datos['ciuo_88_gran_grupo']."')";
    $html .= "    {";
    $html .= "      document.registrar_afiliacion.grandes_grupos.options[i].selected = true;";
    $html .= "      break;";
    $html .= "    }";
    $html .= "  }";
    $objResponse->assign('ocupacion_texto','innerHTML',$texto);
    return $html;
  }
  /**
  * Permite hacer una valdacion del documento de identidad, permitiendo saber si
  * el documento ya existe o no
  *
  * @param string $tipo_documento_id Tipo de identificacion
  * @param string $documento_id Numero de identificacion
  *
  * return object
  */
  function ValidarAfiliado($tipo_documento_id,$documento_id)
  {
    $objResponse = new xajaxResponse();
    
    $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
    $rst = $afi->VerificarExistenciaAfiliado($tipo_documento_id,$documento_id);
    
    if($rst == true)
      $objResponse->call('Continuar');
    else
      $objResponse->assign('error','innerHTML',"EL DOCUMENTO $tipo_documento_id $documento_id, YA ESTA ASIGNADO A OTRO AFILIADO");
    return $objResponse;
  }
  /**
  * Funcion para hacer el cambio de subestados
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return String
  */
  function CambiarSubEstados($form)
  {
    $objResponse = new xajaxResponse();
    $afi = AutoCarga::factory("EstadosAfiliados", "", "app","UV_Afiliaciones");
    $subestados = $afi->ObtenerSubEstados($form['estado_afiliado_id']);
    
    if($form['estado_afiliado_id'] == "RE")
      $objResponse->assign("observaciones","style.display","block");
    else
      $objResponse->assign("observaciones","style.display","none");
      
    $html  = "document.cambiarestado.subestado_afiliado_id.options.length = 0 ;\n";
    $html .= "document.cambiarestado.subestado_afiliado_id.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
    $i = 1;
    $sl = "";
    foreach($subestados as $key => $dtl)
    {
      ($subestado == $key)? $sl = "true":$sl = "false";
      $html .= "document.cambiarestado.subestado_afiliado_id.options[".$i++."] = new Option('".utf8_encode($dtl['descripcion_subestado'])."','".$key."',false, $sl);\n";
    }

    $objResponse->script($html);
    return $objResponse;  
  }
  /**
  * Funcion para hacer la busqueda del afiliado
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return String
  */
  function BuscarAfiliadoPeriodoCobertura($form)
  {
    $objResponse = new xajaxResponse();
    $afi = AutoCarga::factory("EstudiantesCertificados", "", "app","UV_Afiliaciones");

    $registros = $afi->ObtenerInformacionBaseAfiliado($form['afiliado_tipo_id'],$form['afiliado_id']);
    if($registros === false)
    {
      $objResponse->assign("error","innerHTML",$afi->ErrMsg());
    }
    else if(empty($registros))
      {
        $html = "EL AFILIADO IDENTIFICADO CON ".$form['afiliado_tipo_id']." ".$form['afiliado_id'].", NO ESTA REGISTRADO EN EL SISTEMA O NO ES UN BENEFICIARIO ";
        $objResponse->assign("error","innerHTML",$html);
      }
      else
      {
        $objResponse->call("continuarAfiliacion");
      }
    return $objResponse;
  }
?>