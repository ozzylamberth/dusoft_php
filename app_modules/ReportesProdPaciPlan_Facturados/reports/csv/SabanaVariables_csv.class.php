<?php
	/**
  * $Id: Cotizantes_csv.class.php,v 1.1 2008/09/01 20:42:45 hugo Exp $ 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * @author Hugo Freddy Manrique Arango
  */
  class SabanaVariables_csv
  {
    var $error = "";
    /**
    * Constructor de la clase
    */
    function Cotizantes_csv(){}
    /**
    * Funcion para generar el reporte csv
    *
    * @return object Resulset de la consulta, para este caso solo se
    * ejecuta el query, pero no se reccorre el resulset
    */
    function GetReporteCsv($parametros)
    {
      $sql = AutoCarga::factory("GenerarReporte","classes","app","ReportesProdPaciPlan_Facturados");
      //$sql->debug = true;
      $rst = $sql->ObtenerReporteSabana($parametros);
      if(!$rst) $this->error = $sql->mensajeDeError;
      
      return $rst;
    }
  }
?>