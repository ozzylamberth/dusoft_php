<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: recibocaja_fpdf.class.php,v 1.1 2011/02/18 15:36:20 hugo Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (wwPA.ipsoft-sa.com)
  * @author Hugo F. Manrique
  */
  /**
  * Clase Reporte: recibocaja_fpdf 
  * Reporte de la formula medica de optometria
  *
  * @package IPSOFT-SIIS  
  * @version $Revision: 1.1 $
  * @copyright (C) 2007 IPSOFT - SA (wwPA.ipsoft-sa.com)
  * @author Hugo F. Manrique
  */
  class recibocaja_fpdf
  {
    /**
    * Constructor de la clase
    */
    function recibocaja_fpdf(){}
    /**
    * Funcion para generar el archivo pdf
    *
    * @param array $prm Arreglo de parametros del request
    * @param string $nombre Nombre del reporte
    * @param string $pathImagen Ruta de la imagen
    *
    * @return boolean
    */
    function GetReporteFPDF($prm,$nombre,$pathImagen)
    {
      //print_r($_REQUEST['parametros'] );
      //print_r($_REQUEST);
      IncludeClass('ImprimirSQL','','app','Cuentas');
      //IncludeClass('ImprimirHTML','','app','Cuentas');
      $cnt = new ImprimirSQL();
      //$html = new ImprimirHTML();
      if (empty($_REQUEST['PlanId']))
        $PlanId=$_SESSION['planid'];
     else
        $PlanId=$_REQUEST['PlanId'];
     $Recibo=$_REQUEST['parametros']['Recibo'];
     $Prefijo=$_REQUEST['prefijo'];
     $Empresa=$_REQUEST['parametros']['empresa'];
     $CenU=$_REQUEST['parametros']['cu'];
     $TipoId=$_REQUEST['parametros']['TipoId'];
     $PacienteId=$_REQUEST['parametros']['PacienteId'];
     $caja_id=$_REQUEST['parametros']['cajaid'];
     
			$datos = $cnt->BuscarDatos($Recibo,$Prefijo,$Empresa,$CenU,$TipoId,$PacienteId,$PlanId, $caja_id);
     IncludeLib("reportes/recibo_caja"); //car
       GenerarReciboCaja($datos);
       
      return true;
    }
  }
?>