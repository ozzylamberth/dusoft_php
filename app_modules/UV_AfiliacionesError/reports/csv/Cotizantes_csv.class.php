<?php
	/**
  * $Id: Cotizantes_csv.class.php,v 1.1.1.1 2009/09/11 20:36:58 hugo Exp $ 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * @author Hugo Freddy Manrique Arango
  */
  class Cotizantes_csv
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
      $nvd = AutoCarga::factory('ModificarDatosAfiliados','','app','UV_Afiliaciones');
      $rst = $nvd->ObtenerCotizantes($parametros);
      if(!$rst) $this->error = $nvd->mensajeDeError;
      
      return $rst;
    }
  }