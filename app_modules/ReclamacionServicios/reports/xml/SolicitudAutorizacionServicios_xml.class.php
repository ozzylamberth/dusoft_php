<?php
	/**
  * $Id: SolicitudAutorizacionServicios_xml.class.php,v 1.1 2009/10/21 22:04:39 hugo Exp $ 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * @author Hugo Freddy Manrique Arango
  */
  class SolicitudAutorizacionServicios_xml
  {
    var $error = "";
    /**
    * Constructor de la clase
    */
    function SolicitudAutorizacionServicios_xml(){}
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

      if(!$rst = $nvd->ObtenerDatosSolicitudAutorizacionServicios($parametros))
      {
        $this->error = $nvd->mensajeDeError;
        return false;
      }
      
      $empresa    = $mdl->ConsultarEmpresa($rst['plan_id']);
      $paciente   = $mdl->ConsultarPaciente($rst['paciente_id'], $rst['tipo_id_paciente']);
      $tercero    = $mdl->ConsultarTerceros($rst['plan_id']);
      $coberturas = $mdl->ConsCoberturaSaludPlan($rst['plan_id']);
      
      $datos = $rst;
      $datos = array_merge($datos, $empresa);
      $datos = array_merge($datos, $tercero);
      $datos = array_merge($datos, $paciente);
      $datos = array_merge($datos, $coberturas);
      
      if($rst['solicitud_manual'] == '0')
      {
        $via_ing_cama = $mdl->ConsultarViaIngresoCama($rst['ingreso']);
        $diagnosticos = $mdl->ConsultarDiagnosticos($rst['ingreso'],$rst['profesional_id']);
        $prof = $mdl->ConsTipoProfesFiltro($rst['ingreso'], $rst['profesional_id']);
        
        $datos = array_merge($datos, $via_ing_cama);
        $datos = array_merge($datos, $prof);

        $datos['diagnosticos'] = $diagnosticos;
      }
      
      $datos = $nvd->ParseDatosAutorizacionServicios($datos);
      
      $ctl = AutoCarga::factory("ClaseUtil");
      $ctl->toXML($datos,"SolicitudAutorizacionServicios","SolicitudAutorizacionServicios");

      return true;
    }
  }