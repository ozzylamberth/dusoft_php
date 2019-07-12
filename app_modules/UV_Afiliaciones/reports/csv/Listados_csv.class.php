<?php
	/**
  * $Id: Listados_csv.class.php,v 1.2 2009/10/05 18:27:11 hugo Exp $ 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * @author Hugo Freddy Manrique Arango
  */
  class Listados_csv
  {
    var $error = "";
    /**
    * Constructor de la clase
    */
    function Listados_csv(){}
    /**
    * Funcion para generar el reporte csv
    *
    * @return object Resulset de la consulta, para este caso solo se
    * ejecuta el query, pero no se reccorre el resulset
    */
    function GetReporteCsv($parametros)
    {
      $nvd = AutoCarga::factory('Novedades','','app','UV_Afiliaciones');
      $rst = $nvd->ObtenerListadoNovedades($parametros['fecha_inicio'],$parametros['fecha_final'],$parametros['plan']);
      if(!$rst) $this->error = $nvd->mensajeDeError;
      
      return $rst;
    }
  }
?>