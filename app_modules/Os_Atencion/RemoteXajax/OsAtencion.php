<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: OsAtencion.php,v 1.7 2010/02/26 12:36:19 sandra Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */ 
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.7 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  
  /**
  * Funcion para hacer la validacion del tipo de afiliado y el rango
  * para un afiliado
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function ValidarPlanAtencion($form)
  {
    $objResponse = new xajaxResponse();
    $sla = AutoCarga::factory('InformacionAfiliados','classes','app','Os_Atencion');
     
    $tipos_plan = $sla->ObtenerTipoPlan($form['plan']);
    if($tipos_plan['sw_tipo_plan']==1)
    {
      $mensaje = "LOS PLANES SOAT DEBEN REALIZAR EL PROCESO EN LA CENTRAL DE AUTORIZACIONES.";
      $objResponse->assign("error","innerHTML",$mensaje);
      return $objResponse;
    }
    
    if($form['prefijo'] || $form['historia'])
    {
      $sla->ValidarHistoria($form);
      if(!$sla)
      {
        $html = "LA HISTORIA NO EXISTE ";
        $objResponse->assign("error","innerHTML",$html);
        return $objResponse;
      }
    }
    
    $form['tipo_id_paciente'] = $form['Tipo'];
    $form['paciente_id'] = $form['Documento'];
    $form['plan_id'] = $form['plan'];       
    
    $inp = AutoCarga::factory('InformacionPacientes');
    $datos = $inp->ValidarInformacion($form);
      
    if(is_array($datos))
    {
      if(empty($datos))
      {
        $html = "AL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id']." NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO ";
        $objResponse->assign("error","innerHTML",$html);
        return $objResponse;
      }
    }
    else if($datos == 3)
    {
      $datos = $sla->ObtenerDatosAfiliados($form);
      if($datos === false)
      {
        $objResponse->assign("error","innerHTML",$sla->ErrMsg());
        return $objResponse;
      }
      else
      {
        $validacion = $sla->ObtenerInformacionPlan($form['plan_id']);
        if(!empty($datos) && $datos['plan_atencion'] != $form['plan_id'])
        {
          if($validacion['sw_afiliados'] != '2')
          {
            $html  = "EL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id']." NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO ";
            $html .= "<br>PLAN DE AFILIACION ".$datos['plan_descripcion']." ";
            $objResponse->assign("error","innerHTML",$html);
            return $objResponse;
          }
        }
        else if(empty($datos) && $validacion['sw_afiliados'] == '1')
        {
          $mensaje = "EL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id'].", NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO";
          $objResponse->assign("error","innerHTML",$mensaje);
          return $objResponse;
        }
      }
    }
    
    $datos_plan = $sla->ObtenerInformacionPlan($form['plan']);
    if($datos_plan['sw_afiliados'] == '1')
    {        
      $datosPlan = $sla->ObtenerDatosPlanAfiliado($form);
      if(empty($datosPlan))
      {
        $mensaje = "EL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id'].", NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO"; 
        $objResponse->assign("error","innerHTML",$mensaje);
      }
      else
      {
        SessionSetVar("DatosPaciente",$datos);
        $objResponse->script("Continuar(document.formabuscar);");
      }
    }
    else
    {
      SessionSetVar("DatosPaciente",$datos);
      $objResponse->script("Continuar(document.formabuscar);");
    }
    return $objResponse;
  }
  /**
  * Funcion donde se realiza la busqueda de los diagnosticos
  *
  * @param array $form Arreglo de datos de la forma
  * @param string $cups Identificador del cargo cups
  * @param integer $tmp_solicitud_manual_id Identificador temporal del cargo
  * @param integer $offset Identificador del numero de pagina del paginador
  *
  * @return object
  */
  function BuscarDiagnosticos($form,$cups,$tmp_solicitud_manual_id,$offset)
  {
    $objResponse = new xajaxResponse();
    
    $html = "";
    if($form['codigo'] != "" || $form['diagnostico'] != "")
    {
      $mdl = AutoCarga::factory("OS_AtencionSQL","classes","app","Os_Atencion");
      $diagnosticos = $mdl->ObtenerDiagnosticos($form,$tmp_solicitud_manual_id,$offset);
      
      if(!empty($diagnosticos))
      {
        $pgh = AutoCarga::factory("ClaseHTML");
        $action = "BuscarDiagnosticos('".$cups."','".$tmp_solicitud_manual_id."'";
        $html .= $pgh->ObtenerPaginadoXajax($mdl->conteo,$mdl->pagina,$action,0);

        $html .= "  <input type=\"hidden\" name=\"cups\" value=\"".$cups."\">\n";
        $html .= "  <input type=\"hidden\" name=\"tmp_solicitud_manual_id\" value=\"".$tmp_solicitud_manual_id."\">\n";
        $html .= "  <table width=\"98%\" align=\"center\">\n";
        $html .= "    <tr>\n";
        $html .= "      <td align=\"right\">\n";
        $html .= "        <input type=\"submit\" name=\"guardar\" value=\"Guardar Diagnosticos\" class=\"input-submit\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "  </table>\n";
        $html .= "  <table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td rowspan=\"2\" width=\"10%\">CODIGO</td>\n";
        $html .= "      <td rowspan=\"2\" width=\"70%\">DIAGNOSTICO</td>\n";
        $html .= "      <td colspan=\"3\">TIPO DX</td>\n";
        $html .= "      <td rowspan=\"2\" width=\"3%\">PR</td>\n";
        $html .= "      <td rowspan=\"2\" width=\"8%\">OP</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"3%\">ID</td>\n";
        $html .= "      <td width=\"3%\">CN</td>\n";
        $html .= "      <td width=\"3%\">CR</td>\n";
        $html .= "    </tr>\n";
        foreach($diagnosticos as $key => $dtl)
        {
          $est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
          $html .= "    <tr class=\"".$est."\">\n";
          $html .= "      <td>".$dtl['diagnostico_id']."</td>\n";
          $html .= "      <td>".$dtl['diagnostico_nombre']."</td>\n";
          $html .= "      <td align=\"center\">\n";
          $html .= "        <input type=\"radio\" name=\"tipo_diagnostico[".$dtl['diagnostico_id']."]\" value=\"1\">\n";
          $html .= "      </td>\n";          
          $html .= "      <td align=\"center\">\n";
          $html .= "        <input type=\"radio\" name=\"tipo_diagnostico[".$dtl['diagnostico_id']."]\" value=\"2\">\n";
          $html .= "      </td>\n";          
          $html .= "      <td align=\"center\">\n";
          $html .= "        <input type=\"radio\" name=\"tipo_diagnostico[".$dtl['diagnostico_id']."]\" value=\"3\">\n";
          $html .= "      </td>\n";          
          $html .= "      <td align=\"center\">\n";
          $html .= "        <input type=\"radio\" name=\"sw_principal\" value=\"".$dtl['diagnostico_id']."\">\n";
          $html .= "      </td>\n";
          $html .= "      <td align=\"center\">\n";
          $html .= "        <input type=\"checkbox\" name=\"diagnosticos[".$dtl['diagnostico_id']."]\" value=\"".$dtl['diagnostico_id']."\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
        }
        $html .= "  </table>\n";
        $html .= "  <table width=\"98%\" align=\"center\">\n";
        $html .= "    <tr>\n";
        $html .= "      <td align=\"right\">\n";
        $html .= "        <input type=\"submit\" name=\"guardar\" value=\"Guardar Diagnosticos\" class=\"input-submit\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "  </table>\n";
        $html .= $pgh->ObtenerPaginadoXajax($mdl->conteo,$mdl->pagina,$action,1);
        $html .= "<br>\n";
      }
      else
      {
        $html .= "<center class=\"label_error\">\n";
        $html .= "  LA BUSQUEDA BO ARROJO RESULTADOS\n";
        $html .= "</center>\n";
      }
    }
    else
    {
      $html .= "<center class=\"normal_10AN\">\n";
      $html .= "  FAVOR INGRESAR UN PARAMETRO DE BUSQUEDA\n";
      $html .= "</center>\n";
    }
    $objResponse->assign("resultado_busqueda","innerHTML",$html);
    $objResponse->script("MostrarSpan('Contenedor');document.buscador_diagnosticos.action=\"javascript:BuscarDiagnosticos('".$cups."','".$tmp_solicitud_manual_id."',0)\";");
    return $objResponse;
  }
  /**
  * Funcion donde se realiza el ingreso de los daigosticos asociados a un cargo
  *
  * @param array $form Arreglo de datos de la forma
  *
  * @return object
  */
  function IngresarDiagnosticos($form)
  {
    $objResponse = new xajaxResponse();

    $msj = "";
    
    if(empty($form['diagnosticos']))
      $msj = "NO SE HA SELCCIONADO NINGUN DIAGNOSTICO\n";
    else
    {
      foreach($form['diagnosticos'] as $key => $dtl)
      {
        if(empty($form['tipo_diagnostico'][$key]))
        {
          $msj = "PARA EL DIAGNOSTICO ".$key." NO DE HA INDICADO EL TIPO DX\n";
          break;
        }
      }
    }
    if($msj != "")
      $objResponse->alert($msj);
    else
    {
      $mdl = AutoCarga::factory("OS_AtencionSQL","classes","app","Os_Atencion");
      $sw_primero = false;
      if($form['sw_principal'] == "")
      {
        $cantidad = $mdl->ObtenerCantidadDiagnosticosIngresados($form['tmp_solicitud_manual_id']);
        if(!$cantidad['cantidad'])
          $sw_primero = true;
      }
      
      $rst = $mdl->IngresarDiagnosticosCargo($form,$sw_primero);
      if(!$rst)
        $objResponse->alert($mdl->mensajeDeError);
      else
      {
        $diag = $mdl->ObtenerDiagnosticosIngresados($form['tmp_solicitud_manual_id'],$form['cups']);
        $html = "";
        if(!empty($diag[$form['cups']]))
        {
          $html  = "<table width=\"100%\" align=\"center\" >\n";
          $html .= "  <tr class=\"modulo_table_list_title\">\n";
          $html .= "    <td colspan=\"5\">DIAGNOSTICOS ASOCIADOS AL CARGO ".$form['cups']."</td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr class=\"modulo_table_list_title\">\n";
          $html .= "    <td width=\"10%\">CODIGO</td>\n";
          $html .= "    <td width=\"70%\">DESCRIPCION</td>\n";
          $html .= "    <td width=\"8%\">TIPO DX</td>\n";
          $html .= "    <td width=\"4%\">PR</td>\n";
          $html .= "    <td width=\"8%\">OP</td>\n";
          $html .= "  </tr>\n";
          
          foreach($diag[$form['cups']] as $key => $dtl)
          {
            $est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
          
            $html .= "  <tr class=\"".$est."\" >\n";
            $html .= "    <td>".$dtl['diagnostico_id']."</td>\n";
            $html .= "    <td>".$dtl['diagnostico_nombre']."</td>\n";
            $html .= "    <td>\n";
            if($dtl['tipo_diagnostico'] == '1')
              $html .= "      <img src=\"".GetThemePath()."/images/id.png\" border=\"0\">\n";
            else if($dtl['tipo_diagnostico'] == '2')
              $html .= "      <img src=\"".GetThemePath()."/images/cn.png\" border=\"0\">\n";
            else if($dtl['tipo_diagnostico'] == '3')
              $html .= "      <img src=\"".GetThemePath()."/images/cr.png\" border=\"0\">\n";
            
            $html .= "    </td>\n";
            $html .= "    <td>\n";
            if($dtl['sw_principal'] == '1')
              $html .= "      <img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">\n";
            $html .= "    </td>\n";
            $html .= "    <td align=\"center\">\n";
            $html .= "      <a href=\"javascript:EliminarDiagnostico('".$form['tmp_solicitud_manual_id']."','".$form['cups']."','".$dtl['diagnostico_id']."')\">\n";
            $html .= "        <img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
            $html .= "      </a>\n";
            $html .= "    </td>\n";
            $html .= "  </tr>\n";
          }
          $html .= "</table>\n";
        }
        $objResponse->assign("diagnosticos_cargo_".$form['cups'],"innerHTML",$html);
        $msj1 = "<center class=\"normal_10AN\">DIAGNOSTICOS AGREGADOS CORRECTAMENTE</center>\n"; 
        $objResponse->assign("resultado_busqueda","innerHTML",$msj1);
      }
    }
    return $objResponse;
  }
  /**
  * Funcion para realizar la eliminacion de un diagnostico asociado
  *
  * @param integer $tmp_solicitud_manual_id Identificador del temporal la solicitud
  * @param string $cups Identificador del cargo cups
  * @param string $diagnostico_id Identificador del diagnostico
  *
  * @return object
  */
  function EliminarDiagnostico($tmp_solicitud_manual_id,$cups,$diagnostico_id)
  {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("OS_AtencionSQL","classes","app","Os_Atencion");

    $rst = $mdl->EliminarDiagnosticosCargo($tmp_solicitud_manual_id,$cups,$diagnostico_id);
    if(!$rst)
      $objResponse->alert($mdl->mensajeDeError);
    else
    {
      $diag = $mdl->ObtenerDiagnosticosIngresados($tmp_solicitud_manual_id,$cups);
      $html = "";
      if(!empty($diag[$cups]))
      {
        $html  = "<table width=\"100%\" align=\"center\" >\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "    <td colspan=\"5\">DIAGNOSTICOS ASOCIADOS AL CARGO ".$form['cups']."</td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "    <td width=\"10%\">CODIGO</td>\n";
        $html .= "    <td width=\"70%\">DESCRIPCION</td>\n";
        $html .= "    <td width=\"8%\">TIPO DX</td>\n";
        $html .= "    <td width=\"4%\">PR</td>\n";
        $html .= "    <td width=\"8%\">OP</td>\n";
        $html .= "  </tr>\n";
        
        foreach($diag[$cups] as $key => $dtl)
        {
          $est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
        
          $html .= "  <tr class=\"".$est."\" >\n";
          $html .= "    <td>".$dtl['diagnostico_id']."</td>\n";
          $html .= "    <td>".$dtl['diagnostico_nombre']."</td>\n";
          $html .= "    <td>\n";
          if($dtl['tipo_diagnostico'] == '1')
            $html .= "      <img src=\"".GetThemePath()."/images/id.png\" border=\"0\">\n";
          else if($dtl['tipo_diagnostico'] == '2')
            $html .= "      <img src=\"".GetThemePath()."/images/cn.png\" border=\"0\">\n";
          else if($dtl['tipo_diagnostico'] == '3')
            $html .= "      <img src=\"".GetThemePath()."/images/cr.png\" border=\"0\">\n";
          
          $html .= "    </td>\n";
          $html .= "    <td>\n";
          if($dtl['sw_principal'] == '1')
            $html .= "      <img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">\n";
          $html .= "    </td>\n";
          $html .= "    <td align=\"center\">\n";
          $html .= "      <a href=\"javascript:EliminarDiagnostico('".$dtl['tmp_solicitud_manual_id']."','".$dtl['cargo_cups']."','".$dtl['diagnostico_id']."')\">\n";
          $html .= "        <img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
          $html .= "      </a>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
        }
        $html .= "</table>\n";
      }
      $objResponse->assign("diagnosticos_cargo_".$cups,"innerHTML",$html);
    }
    return $objResponse;
  }
  
  /*
  *Funcion que actualiza el email del paciente
  * @Fecha: 20-VI-2012
  * @author: Steven H. Gamboa
  */
  function UpdateEmailPaciente($id,$tipo,$email)
  {
    $objResponse = new xajaxResponse();
    $sla = AutoCarga::factory('InformacionAfiliados','classes','app','Os_Atencion');
	
	if($email=="")
	{
		$objResponse->alert("Por Favor ingrese un Correo Electronico valido.");
		//return $objResponse;	
	}
	else
	{
		$validacionmail = explode("@",$email);
		if(count($validacionmail)==2)
		{
			$validacionmail = explode(".",$validacionmail[1]);
			if(count($validacionmail)==2)
			{
				if(strlen($validacionmail[1])==3 || strlen($validacionmail[1])==2)
				{
					$respuesta = $sla->ActualizarEmailPaciente($id,$tipo,$email);
					$objResponse->alert($respuesta);
				}
				else
				{
					$objResponse-> alert("Correo Electronico No valido");
				}
			}
			else
			{
				if(count($validacionmail)==3)
				{
					if(strlen($validacionmail[2])==2)
					{
						$respuesta = $sla->ActualizarEmailPaciente($id,$tipo,$email);
						$objResponse->alert($respuesta);
					}
					else
					{
						$objResponse-> alert("Correo Electronico No valido");
					}
				}
				else
				{
					$objResponse-> alert("Correo Electronico No valido");
				}
			}
			/*if(count($validacionmail)==2 ||  count($validacionmail)==3)
			{
				$respuesta = $sla->ActualizarEmailPaciente($id,$tipo,$email);
				$objResponse->alert($respuesta);
			}
			else
			{
				$objResponse-> alert("Mail No valido");
			}*/
		}
		else
		{
			$objResponse-> alert("Correo Electronico No valido");
		}
	}
	return $objResponse;

  }
  
?>