<?php
	/**
  * $Id: Plano_csv.class.php,v 1.1.1.1 2009/09/11 20:36:58 hugo Exp $ 
  * @copyright (C) 2010 Cosmitet LTDA
  * @package SINERGIAS
  * 
  * @author
  */
  class Planos_csv
  {
    var $error = "";
    /**
    * Constructor de la clase
    */
    function Planos_csv(){}
    /**
    * Funcion para generar el reporte csv
    *
    * @return object Resulset de la consulta, para este caso solo se
    * ejecuta el query, pero no se reccorre el resulset
    */
    function GetReporteCsv($parametros)
    {

      $nvd = AutoCarga::factory('Plano','','app','UV_Afiliaciones');
      $rst = $nvd->ObtenerPlano($parametros);
      if(!$rst) $this->error = $nvd->mensajeDeError;
      
      return $rst;
    }
  }