<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Solicitudes.php,v 1.1 2008/03/13 13:36:18 hugo Exp $
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
  * Funcion para hacer la validacion de pacientes
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function ValidarPaciente($form)
  {
    $objResponse = new xajaxResponse();
    
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
        $objResponse->call("ContinuarSolicitud");
      }
    }
    else
    {
      if(is_numeric($datos))
        $objResponse->assign("error","innerHTML",$inp->ObtenerClasificacionErrores($datos));
      
      if($datos == 3)
      {
        $sla = AutoCarga::factory('SolicitudesAutorizacion','','app','UV_SolicitudesAutorizaciones');
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
            $objResponse->call("ContinuarSolicitud");
          }
        }
      }
    }
    return $objResponse;
  }
?>