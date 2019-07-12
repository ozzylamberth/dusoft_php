<?php
	/**
  * $Id: Listados_csv.class.php,v 1.2 2009/10/05 18:27:11 sandra 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * @author Mauricio Adrian Medina Santacruz 
  */
 class ESM_Planos_csv
  {
    var $error = "";
    /**
    * Constructor de la clase
    */
    function ESM_Planos_csv(){}
    /**
    * Funcion para generar el reporte csv
    *
    * @return object Resulset de la consulta, para este caso solo se
    * ejecuta el query, pero no se reccorre el resulset
    */
    function GetReporteCsv($parametros)
    {	
   //print_r($parametros);
      $nvd =AutoCarga::factory('Consultas_ESM_Planos','','app','ESM_Planos');
       $rst=$nvd->Consulta_Formulacion($parametros['buscador']);
      if(!$rst) $this->error = $nvd->mensajeDeError;
      return $rst;
    }
  }
?>