<?php

/**
 * $Id: reporte_soat1.inc.php,v 1.1 2006/08/16 20:01:41 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

        function Generar_Pdf_reporte_soat1($TipoDo,$Docume,$evento)
        {
            UNSET($_SESSION['REPORTES']['VARIABLE']);
            $Dir="cache/reclamacion_entidades_1.pdf";
            require("classes/fpdf/html_class.php");
            define('FPDF_FONTPATH','font/');
            if(!IncludeFile("classes/ReportesSoat/fpdf_reporte_soat.class.php"))
            {
                $this->error = "No se pudo inicializar la Clase de fpdf_reporte_soat";
                $this->mensajeDeError = "No se pudo Incluir el archivo : classes/ReportesSoat/fpdf_reporte_soat.class.php";
                return false;
            }
            $pdf= new fpdf_reporte_soat('P','mm','legal');
            //$pdf->fpdf_reporte_soat($orientation='P',$unit='mm',$format='legal');//letter
            $pdf->set_correcion_x(0.92);
            $pdf->set_correcion_y(0.60);
            $datos=$pdf->TraerDatosReclamacionEntidades($TipoDo,$Docume,$evento);
            $pdf->AddPage();
            $pdf->SetFont('Arial','',8);
            foreach($datos as $k=>$v)
            { 
                $pdf->Text_corregida($v[0],$v[1],$v[2]);
            }
            
            $pdf->Output($Dir,'F');
            return true;
        }
?>
