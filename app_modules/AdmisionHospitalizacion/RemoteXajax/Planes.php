<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Planes.php,v 1.8 2011/03/29 14:46:17 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.8 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function ValidarPaciente($form)
  {
    $objResponse = new xajaxResponse();

    $mensaje = "";
    if($form['TipoDocumento'] == "-1")
      $mensaje = "SE DEBE SELECCIONAR EL TIPO DE DOCUMENTO";
    else if($form['Documento'] == "")
      $mensaje = "SE DEBE INGRESAR EL NUMERO DEL DOCUMENTO";
      else if($form['Responsable'] == '-1')
        $mensaje = "SE DEBE SELECCIONAR EL PLAN";
            
    if($mensaje == "")
    {
      $form['tipo_id_paciente'] = $form['TipoDocumento'];
      $form['paciente_id'] = $form['Documento'];
      $form['plan_id'] = $form['Responsable'];    
            
      $inp = AutoCarga::factory('InformacionPacientes');
      $datos = $inp->ValidarInformacion($form);
      
      $sla = AutoCarga::factory("InformacionAfiliados","","app","AdmisionHospitalizacion");

      if(is_array($datos))
      {
        if(empty($datos))
        {
          $html = "AL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id']." NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO ";
          $objResponse->assign("errorA","innerHTML",$html);
        }
        else
        {
          SessionSetVar("DatosPaciente",$datos);
          $objResponse->call("Continuar");
        }
      }
      else
      {
        if(is_numeric($datos))
          $objResponse->assign("errorA","innerHTML",$inp->ObtenerClasificacionErrores($datos));
        
        if($datos == 3)
        {
          $datos = $sla->ObtenerDatosAfiliados($form);
          if($datos === false)
            $objResponse->assign("errorA","innerHTML",$sla->ErrMsg());
          else
          {
            $validacion = $sla->ObtenerInformacionPlan($form['plan_id']);
            if(!empty($datos) && $datos['plan_atencion'] != $form['plan_id'])
            {
              if($validacion['sw_afiliados'] == '2' OR $validacion['sw_tipo_plan'] == '1')
              {
                SessionSetVar("DatosPaciente",$datos);
                $objResponse->call("Continuar");
              }
              else
              {
                $html  = "EL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id']." NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO ";
                $html .= "<br>PLAN DE AFILIACION ".$datos['plan_descripcion']." ";
                $objResponse->assign("errorA","innerHTML",$html);
              }
            }
            else
            {
              //$validacion = $sla->ObtenerDatosPlanAfiliado($form);
              if(empty($datos) && $validacion['sw_afiliados'] == '1')
              {
                $mensaje = "EL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id'].", NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO";
                $objResponse->assign("errorA","innerHTML",$mensaje);
              }
              else
              {
                SessionSetVar("DatosPaciente",$datos);
                $objResponse->call("Continuar");
              }
            }
          }
        }
      }
    }
    else
    {
      $objResponse->assign("error","innerHTML",$mensaje);
    }
    return $objResponse;
  }
  /**
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function BuscarPlan($form)
  {
    $objResponse = new xajaxResponse();

    $form['tipo_id_paciente'] = $form['TipoDocumento'];
    $form['paciente_id'] = $form['Documento'];
    $form['plan_id'] = $form['Responsable'];

    $plan  = AutoCarga::factory('InformacionAfiliados','classes','app','AdmisionHospitalizacion');
    
    $datosPlan = $plan->ObtenerPlanes($form['plan_id']);
        
  	if($datosPlan[0]['sw_atender_bd'] == '1')
    {
      $inp= AutoCarga::factory('InformacionPacientes');
      $datos = $inp->ValidarInformacion($form);
      
      if(is_array($datos) && empty($datos))
      {
        $objResponse->assign('errorA','innerHTML','NO SE PUEDE ADMITIR');
        return $objResponse;
      }
    }
    
    $objResponse->call("Continuar");
    return $objResponse;
  }
    /**
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function BuscarPlanRangoTAfiliados($form)
  {
    $objResponse = new xajaxResponse();
    
    if(is_array($form))
	{
      $formani=$form['Responsable'];
	}  
    else
	{
      $formani=$form;
	}  
    $plan  = AutoCarga::factory('InformacionAfiliados','classes','app','AdmisionHospitalizacion');
    $rangoniveles = $plan->ObtenerRangosNiveles($formani);
    $tipoafiliado = $plan->ObtenerTiposAfiliados($formani);

    $this->salida .= "	<table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
	$this->salida .= "		<tr class=\"modulo_list_claro\">\n";
	$this->salida .= "			<td>TIPO AFILIADO</td>\n";
    $this->salida .= "			 <select name=\"tipo_afiliado_id\" class=\"select\">\n";
    foreach($tipoafiliado as $key => $dtl)
    {
      $this->salida .=" 			 <option value=\"".$dtl['tipo_afiliado_id']."\">".$dtl['tipo_afiliado_nombre']."</option>\n";
    }
	$this->salida .= "				</select>\n";
	$this->salida .= "		</tr>\n";
	
	$this->salida .= "		<tr class=\"modulo_list_claro\">\n";
	$this->salida .= "			<td>RANGO</td>\n";
    $this->salida .= "			 <select name=\"rango\" class=\"select\">\n";
    foreach($rangoniveles as $indice=>$valor)
    {
      $this->salida .=" 			 <option value=\"".$valor['rango']."\">".$valor['rango']."</option>\n";
    }
	$this->salida .= "				</select>\n";
	$this->salida .= "		</tr>\n";
    
   
    $objResponse->assign('rangoTipoAfiliadC','innerHTML',$this->salida);
    return $objResponse;
  }
?>