<?php
	/**
  * $Id: InformePresuntaInconsistencia_xml.class.php,v 1.1 2009/10/21 22:04:39 hugo Exp $ 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * @author Hugo Freddy Manrique Arango
  */
  class InformePresuntaInconsistencia_xml
  {
    var $error = "";
    /**
    * Constructor de la clase
    */
    function InformePresuntaInconsistencia_xml(){}
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
      
      if(!$rst = $nvd->ObtenerDatosInformePresuntaInconsistencia($parametros))
      {
        $this->error = $nvd->mensajeDeError;
        return false;
      }
      
      $ing = $mdl->ObtenerDatosIngreso($rst['ingreso']);
      
      $empresa = $mdl->ConsultarEmpresa($rst['plan_id']);
      $tercero = $mdl->ConsultarTerceros($rst['plan_id']);
      $paciente = $mdl->ConsultarPaciente($ing['paciente_id'], $ing['tipo_id_paciente']);
      $usuario = $mdl->ConsultarUsuario($rst['usuario_id']);
      $coberturas = $mdl->ConsCoberturaSalud($rst['ingreso']); 

      $datos = $empresa;
      $datos = array_merge($datos, $ing);
      $datos = array_merge($datos, $tercero);
      $datos = array_merge($datos, $paciente);
      $datos = array_merge($datos, $usuario);
      $datos = array_merge($datos, $coberturas);
      $datos = array_merge($datos, $rst);

      $datos = $nvd->ParseDatosInformePresuntaInconsistencia($datos);
      
      $ctl = AutoCarga::factory("ClaseUtil");
      $ctl->toXML($datos,"InformePresuntaInconsistencia","InformePresuntaInconsistencia");

      return true;
    }
  }