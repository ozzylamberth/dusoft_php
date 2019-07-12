<?php
	/**
  * $Id: Listados_csv.class.php,v 1.1 2009/01/14 22:22:50 hugo Exp $ 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * @author Hugo Freddy Manrique Arango
  */
  class Listados_csv
  {
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
      $cxp = AutoCarga::factory('Listados','','app','UV_CuentasXPagar');
      $rst = $cxp->ObtenerListadoRadicacionCsv('01',$parametros);
      return $rst;
    }
    /**
    * Funcion para generar el reporte csv
    *
    * @return array Para este caso se ejecuta la consulta y se arma el vector,
    * El arreglo solo puede ser de dos dimensiones ej $datos[0]['numero de cuenta']
    */
    function GetReporteXls($parametros)
    {
      $cxp = AutoCarga::factory('Listados','','app','UV_CuentasXPagar');
      $datos = $cxp->ObtenerListadoRadicacion('01',$parametros);
      return $datos;
    }
  }