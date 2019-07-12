<?php
  /**
  * $Id: salida.report.php,v 1.2 2005/06/03 19:32:12 leydi Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  *
  */
  class salida_report extends pos_reports_class
  { 
    /**
    * Constructor de la clase
    */
    function salida_report($datos=array())
    {
      $this->pos_reports_class();
      return true;
    }
    /**
    *
    */
    function CrearReporte()
    {
      $reporte=&$this->driver; //obtener el driver
      $datos=&$this->datos; //obtener los datos enviados al reporte.
      
      $reporte->PrintFTexto($datos[razon_social],true,$align='center',false,true);
      $reporte->PrintFTexto($datos[direccion],false,'center',false,false);
      $reporte->SaltoDeLinea();
      $reporte->PrintFTexto("SALIDA PACIENTYE",false,'center',false,false);
      //$reporte->PrintFTexto('RECIBO DE CAJA',true,'center',false,false);
      //$reporte->PrintFTexto('No. '.$datos[prefijo].$datos[recibo_caja],true,'center',false,false);        
      $reporte->SaltoDeLinea();
				//$reporte->PrintFTexto($datos[1][texto1],true,'center',false,false);                
			$reporte->PrintFTexto("Identificacion: ".$datos['tipoid']." ".$datos['paciente'],true,'left',false,false);                
			$nombre =substr("Nombre: ".$datos['nombre'],0,42);
      
      $reporte->PrintFTexto($nombre,true,'left',false,false);
      $reporte->SaltoDeLinea();
      $reporte->PrintFTexto("El paciente se encuentra a Paz y Salvo,",false,'left',false,false);
      $reporte->PrintFTexto("Se Autoriza la Salida al Paciente de la",false,'left',false,false);
      $reporte->PrintFTexto("Institución ",false,'left',false,false);
      $reporte->SaltoDeLinea();
      $reporte->PrintFTexto("______________________",false,'left',false,false);
      $reporte->PrintFTexto("  FIRMA AUTORIZADA",false,'left',false,false);
      $reporte->PrintEnd();
      $reporte->PrintCutPaper();
        
      return true;
    }
  }
?>