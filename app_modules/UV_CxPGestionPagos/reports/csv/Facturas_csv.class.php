<?php
	/**
  * $Id: Facturas_csv.class.php,v 1.1 2008/10/28 13:12:54 hugo Exp $ 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * @author Hugo Freddy Manrique Arango
  */
  class Facturas_csv
  {
    var $error = "";
    /**
    * Constructor de la clase
    */
    function Facturas_csv(){}
    /**
    * Funcion para generar el reporte csv
    *
    * @return object Resulset de la consulta, para este caso solo se
    * ejecuta el query, pero no se reccorre el resulset
    */
    function GetReporteCsv($parametros)
    {
      $gp = AutoCarga::factory('GestionPagos','','app','UV_CxPGestionPagos');
      $rst = $gp->ObtenerFacturasOrdenPago($parametros['orden_pago']);
      if(!$rst) $this->error = $gp->mensajeDeError;
      
      return $rst;
    }
  }
 ?>