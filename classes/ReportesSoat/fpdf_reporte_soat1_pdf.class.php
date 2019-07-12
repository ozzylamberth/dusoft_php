<?php

	class fpdf_reporte_soat1_pdf 
	{	
		function fpdf_reporte_soat1_pdf()
		{
			return true;
		}

//**********************************************
//**********************************************
		//$datos=TraerDatos($TipoDo,$Docume,$evento);
		/*echo '<pre>';
		print_r($datos,false);
		echo '</pre>';*/
		function Ver_Pdf_reporte_soat1($TipoDo,$Docume,$evento)
		{
			UNSET($_SESSION['REPORTES']['VARIABLE']);
			$Dir="cache/reclamacion_entidades_1.pdf";
			require("classes/fpdf/html_class.php");
			define('FPDF_FONTPATH','font/');
			//IncludeClass("ReportesSoat");
			if(!IncludeFile("classes/ReportesSoat/fpdf_reporte_soat1.class.php"))
			{
				$this->error = "No se pudo inicializar la Clase de fpdf_reporte_soat1_pdf";
				$this->mensajeDeError = "No se pudo Incluir el archivo : classes/ReportesSoat/fpdf_reporte_soat1_pdf.class.php";
				return false;
			}
			$pdf= new fpdf_reporte_soat1();
			$pdf->fpdf_reporte_soat1($orientation='P',$unit='mm',$format='legal');//letter
			$pdf->set_correcion_x(1);
			$pdf->set_correcion_y(1);
			$datos=$pdf->TraerDatos($TipoDo,$Docume,$evento);
			$pdf->AddPage();
			$pdf->SetFont('Arial','',8);
echo '<pre>';
		print_r($datos,false);
echo '</pre>';
			foreach($datos as $k=>$v)
			{ 
					$pdf->Text_corregida($v[0],$v[1],$v[2]);
			}
			
			$pdf->Output($Dir,'F');
			return true;
		}
	}//end of class

?>
