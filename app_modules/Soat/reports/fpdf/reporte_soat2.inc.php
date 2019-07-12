<?php
  /**
  * $Id: reporte_soat2.inc.php,v 1.1 2006/08/16 20:01:59 carlos Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  */
	function BuscarAtencionMedica($evento,$ingreso)
	{
    UNSET($_SESSION['REPORTES']['VARIABLE']);
    $Dir="cache/certificado_atencion_medica_1.pdf";
    require("classes/fpdf/html_class.php");
    define('FPDF_FONTPATH','font/');
    if(!IncludeFile("classes/ReportesSoat/fpdf_reporte_soat.class.php"))
    {
      $this->error = "No se pudo inicializar la Clase de fpdf_reporte_soat";
      $this->mensajeDeError = "No se pudo Incluir el archivo : classes/ReportesSoat/fpdf_reporte_soat.class.php";
      return false;
    }
    $pdf= new fpdf_reporte_soat('P','mm','legal');
    $pdf->set_correcion_x(1);
    $pdf->set_correcion_y(1);
    $datos=$pdf->BuscarAtencionMedica($evento,$ingreso);
    $pdf->AddPage();
    $pdf->SetFont('Arial','',8);
    $vect=$datos[DATOS];
    UNSET($datos[DATOS]);
    foreach($datos as $k=>$v)
    { 
      $pdf->Text_corregida($v[0],$v[1],$v[2]);
    }
    
    $pdf->Output($Dir,'F');
    return $vect;
	}
?>