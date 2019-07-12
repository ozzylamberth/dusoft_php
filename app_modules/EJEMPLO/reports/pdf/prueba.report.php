<?php
//Reporte de prueba para impresora pos

//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
class prueba_report extends pdf_reports_class
{
    
    //constructor por default
    function prueba_report($orientacion,$unidad,$formato,$html)
    {
        $this->pdf_reports_class($orientacion,$unidad,$formato,$html);
        return true;
    }
    
    function CrearReporte()
    {
        $reporte=&$this->driver; //obtener el driver
        $misdatos=&$this->datos; //obtener los datos enviados al reporte.

				$reporte->AddPage();
				$salida="<table border='1'>";
				if( $i % 2)
				{
					$estilo2='#CCCCCC';
				}
				else
				{
					$estilo2='#DDDDDD';
				}
				$salida.="  <TD  WIDTH='60' bgcolor=$estilo>asdfasdf</TD>";
				$salida.="  <TD  WIDTH='60' bgcolor=$estilo>asdfasdfasdf</TD>";
				$salida.="  <TD  WIDTH='250' bgcolor=$estilo>asdfasdfasdf</TD>";
				$salida.="  <TD WIDTH='105' bgcolor=$estilo>asdfasdfasdf</TD>";
				$salida.="  <TD WIDTH='103' bgcolor=$estilo>asdfasdfasdf</TD>";
				$salida.="  <TD WIDTH='58' bgcolor=$estilo>asdfasdfasdf</TD>";
				//$dias=GetDiasHospitalizacion($arr[$i][fecha_ingreso]);
				$salida.="  <TD WIDTH='60'>asdfasdfasdf</TD>";
				$salida.="</TR>";
				$salida.="</table>";
				$reporte->SetFont('arial','B',18);
				$reporte->SetTextColor(203,203,203);
				$reporte->RotatedText(60,80,GetVarConfigAplication('Cliente'),35);
				$reporte->SetFont('arial','',7);
				$reporte->SetTextColor(2,2,2);
				$reporte->WriteHTML($salida);

        return true;
    }

    

    
    //AQUI TODOS LOS METODOS QUE USTED QUIERA
    //---------------------------------------
}
?>
