<?php
	/**
  * $Id: ReporteProductosStock_csv.class.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * @author Johanna Alarcon Duque
  */
  IncludeClass("TomaFisicaSQL","","app","InvTomaFisica");
  class ReporteProductosStock_csv
  {
    var $error = "";
    /**
    * Constructor de la clase
    */
    function ReporteProductosStock_csv(){}
    /**
    * Funcion para generar el reporte csv
    *
    * @return object Resulset de la consulta, para este caso solo se
    * ejecuta el query, pero no se reccorre el resulset
    */
    function GetReporteCsv($parametros)
    {
      //$sql = AutoCarga::factory("GenerarReporte","classes","app","ReportesProdPaciPlan_Facturados");
      //$sql->debug = true;
      //print_r($parametros);
      //exit;
      $consulta=new TomaFisicaSQL();
      $rst = $consulta->ObtenerReporteStockInicial($parametros);
      if(!$rst) $this->error = $consulta->mensajeDeError;
      
      return $rst;
    }
  }
?>