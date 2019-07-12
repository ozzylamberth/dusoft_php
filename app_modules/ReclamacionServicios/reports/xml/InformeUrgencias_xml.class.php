<?php
	/**
  * $Id: InformeUrgencias_xml.class.php,v 1.1 2009/10/21 22:04:39 hugo Exp $ 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * @author Hugo Freddy Manrique Arango
  */
  class InformeUrgencias_xml
  {
    var $error = "";
    /**
    * Constructor de la clase
    */
    function InformeUrgencias_xml(){}
    /**
    * Funcion para generar el archivo xml
    *
    * @param array $parametros Arreglo de oarametros del request
    *
    * @return boolean
    */
    function GetReporteXml($parametros)
    {
      $nvd = AutoCarga::factory('Resolucion3047','classes','app','ReclamacionServicios');
      $mdl = AutoCarga::factory("ReclamacionServiciosSQL", "", "app", "ReclamacionServicios");
      
      if(!$rst = $nvd->ObtenerDatosInformeUrgencias($parametros))
      {
        $this->error = $nvd->mensajeDeError;
        return false;
      }
      $empresa      = $mdl->ConsultarEmpresa($rst['plan_id']);
      $tercero      = $mdl->ConsultarTerceros($rst['plan_id']);
      $paciente     = $mdl->ConsultarPaciente($rst['paciente_id'], $rst['tipo_id_paciente']);
      $usuario      = $mdl->ConsultarUsuario($rst['usuario_id']);
      $orig_aten    = $mdl->ConsultarCausaIng($rst['ingreso']);
      $ing_urg      = $mdl->ConsIngresoUrg($rst['ingreso']);
      $niv_triages  = $mdl->ConsultarTriageIng($rst['ingreso']);
      $pac_rem      = $mdl->ConsPacienteRemitido($rst['ingreso']);
      $diagnosticos = $mdl->ConsultarDiagnosticos($rst['ingreso']);
      $destino      = $mdl->ObtenerDestinoPaciente($rst['ingreso']);
      $coberturas   = $mdl->ConsCoberturaSalud($rst['ingreso']);

      $datos = $rst;
      $datos = array_merge($datos, $empresa);
      $datos = array_merge($datos, $tercero);
      $datos = array_merge($datos, $paciente);
      $datos = array_merge($datos, $usuario);
      $datos = array_merge($datos, $coberturas);
      $datos = array_merge($datos, $orig_aten);
      $datos = array_merge($datos, $ing_urg);
      $datos = array_merge($datos, $pac_rem);
      $datos = array_merge($datos, $destino);
      $datos = array_merge($datos, $niv_triages);
      
      $datos['diagnosticos'] = $diagnosticos;
      $datos = $nvd->ParseDatosInformeUrgencias($datos);
      
      $ctl = AutoCarga::factory("ClaseUtil");
      $ctl->toXML($datos,"InformeUrgencias","InformeUrgencias");

      return true;
    }
  }