<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Pacientes.php,v 1.1 2009/09/02 13:08:12 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
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
        else if($form['TipoConsulta'] == "-1")
          $mensaje = "SE DEBE SELECCIONAR EL TIPO DE CONSULTA";
    
/*    if($mensaje == "")
    {
      $form['tipo_id_paciente'] = $form['TipoDocumento'];
      $form['paciente_id'] = $form['Documento'];
      $form['plan_id'] = $form['Responsable'];
      
      $inp = AutoCarga::factory('InformacionPacientes');
      $datos = $inp->ValidarInformacion($form);
        
      if(is_array($datos))
      {
        if(empty($datos))
        {
          $html = "AL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id']." NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO ";
          $objResponse->assign("error","innerHTML",$html);
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
          $objResponse->assign("error","innerHTML",$inp->ObtenerClasificacionErrores($datos));
        
        if($datos == 3)
        {
          $sla = AutoCarga::factory('InformacionAfiliados','','app','AgendaMedica');
          $datos = $sla->ObtenerDatosAfiliados($form);
          if($datos === false)
            $objResponse->assign("error","innerHTML",$sla->ErrMsg());
          else
          {
            if(empty($datos))
            {
              $html = "AL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id']." NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO ";
              $objResponse->assign("error","innerHTML",$html);
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
    else
    {
      $objResponse->assign("error","innerHTML",$mensaje);
    }*/
    $objResponse->call("Continuar");
    return $objResponse;
  }
?>