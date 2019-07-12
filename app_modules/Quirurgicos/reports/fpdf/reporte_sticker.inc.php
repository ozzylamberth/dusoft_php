<?php

/**
 * $Id: reporte_sticker.inc.php,v 1.1 2007/04/18 22:05:07 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

        function Generar_reporte_sticker($programacionId)
        {
            $Dir="cache/sticker$programacionId.pdf";
            require("classes/fpdf/html_class.php");
            define('FPDF_FONTPATH','font/');
						IncludeClass('fpdf_reporte_sticker','','app','Quirurgicos');

            $pdf= new fpdf_reporte_sticker('P','mm','letter');
            //$pdf->fpdf_reporte_soat($orientation='P',$unit='mm',$format='legal');//letter
            $pdf->set_correcion_x(0.92);
            $pdf->set_correcion_y(0.60);
            $datos=$pdf->TraerDatos($programacionId);
            $pdf->AddPage();
            $pdf->SetFont('Arial','B',10);
            foreach($datos as $k=>$v)
            { 
                $pdf->Text_corregida($v[0],$v[1],$v[2]);
            }
            
            $pdf->Output($Dir,'F');
            return true;
        }
?>
