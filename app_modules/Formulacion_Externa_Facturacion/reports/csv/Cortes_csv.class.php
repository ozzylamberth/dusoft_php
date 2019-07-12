<?php
	/**
  * $Id: Listados_csv.class.php,v 1.2 2009/10/05 18:27:11 sandra 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * @author Sandra Pantoja 
  */
 class Cortes_csv
  {
    var $error = "";
    /**
    * Constructor de la clase
    */
    function Cortes_csv(){}
    /**
    * Funcion para generar el reporte csv
    *
    * @return object Resulset de la consulta, para este caso solo se
    * ejecuta el query, pero no se reccorre el resulset
    */
    function GetReporteCsv($parametros)
    {	
   
      $nvd =AutoCarga::factory('Consultas_ESM_Cortes','','app','Formulacion_Externa_Facturacion');
      $rst = $nvd->Informacion_cortes_Final($parametros['corte_id']);

	  
      if(!$rst) $this->error = $nvd->mensajeDeError;
      
      return $rst;
    }
  }
?>