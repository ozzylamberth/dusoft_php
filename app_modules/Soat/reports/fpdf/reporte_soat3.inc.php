<?php
  /**
  * $Id: reporte_soat3.inc.php,v 1.2 2006/09/19 16:41:43 carlos Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  */
  function BuscarReporteAmbulanciaSoat($ambulancia)
  {
    UNSET($_SESSION['REPORTES']['VARIABLE']);
    $Dir = "cache/ambulancia_anexo_1.pdf";
    require("classes/fpdf/html_class.php");
    define('FPDF_FONTPATH','font/');
    if(!IncludeFile("classes/ReportesSoat/fpdf_reporte_soat.class.php"))
    {
      $this->error = "No se pudo inicializar la Clase de fpdf_reporte_soat";
      $this->mensajeDeError = "No se pudo Incluir el archivo : classes/ReportesSoat/fpdf_reporte_soat.class.php";
      return false;
    }
    $pdf= new fpdf_reporte_soat('P','mm','letter');
    $pdf->set_correcion_x(0.92);
    $pdf->set_correcion_y(0.6);
    $datos=$pdf->BuscarReporteAmbulanciaSoat($ambulancia);
    $pdf->AddPage();
    $pdf->SetFont('Arial','',8);
    foreach($datos as $k=>$v)
    { 
      $pdf->Text_corregida($v[0],$v[1],$v[2]);
    }
    
    $pdf->Output($Dir,'F');
    return $datos;
  }
?>