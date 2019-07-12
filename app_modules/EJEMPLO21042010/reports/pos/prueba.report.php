<?php 
//Reporte de prueba para impresora pos

//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
class prueba_report extends pos_reports_class
{
    
    //constructor por default
    function prueba_report()
    {
        $this->pos_reports_class();
        return true;
    }
    
    function CrearReporte()
    {
        $reporte=&$this->driver; //obtener el driver
        $misdatos=&$this->datos; //obtener los datos enviados al reporte.
// 	$reporte->SetFontSizeGrande();
// 	$reporte->setFontResaltar(true);
// 	$reporte->PrintTexto($text='hola HOLA COMO ESTAS ESTO ES UN TEXTO LARGO MUY LARGO DEMACIADO LARGO PARA VER QUE PASA',$SaltoLinea=1);
// 	$reporte->setFontRedColor($RedColor=true);
// 	$reporte->PrintTexto($text='hola',$SaltoLinea=2);
// 	$reporte->setFontRedColor($RedColor=false);
	//$reporte->setFontResaltar(false);
	//$reporte->PrintTexto($text='hola',$SaltoLinea=1);
// 	$reporte->PrintFTexto($text='hola Clinica de Occidente Tulua S.A.',$bold=false,$align='left',$redColor=false,$size=false);
// 	
// 	$reporte->PrintFTexto($text='hola Clinica de Occidente Tulua S.A.',$bold=false,$align='left',$redColor=false,$size=true);
// 	
// 	$reporte->PrintFTexto($text='hola Clinica de Occidente Tulua S.A.',$bold=false,$align='left',$redColor=true,$size=false);
// 
// 	$reporte->PrintFTexto($text='hola Clinica de Occidente Tulua S.A.',$bold=false,$align='left',$redColor=true,$size=true);
// 	
// 	$reporte->PrintFTexto($text='hola Clinica de Occidente Tulua S.A.',$bold=true,$align='left',$redColor=false,$size=false);
// 	
// 	$reporte->PrintFTexto($text='hola Clinica de Occidente Tulua S.A.',$bold=true,$align='left',$redColor=false,$size=true);
// 
// 	$reporte->PrintFTexto($text='hola Clinica de Occidente Tulua S.A.',$bold=true,$align='left',$redColor=true,$size=false);
// 	
// 	$reporte->PrintFTexto($text='hola Clinica de Occidente Tulua S.A.',$bold=true,$align='left',$redColor=true,$size=true);
// 								
// 	$reporte->PrintEnd();
// 	$reporte->PrintCutPaper($full=false);

             /*
							$reporte->PrintFTexto($text='hola',$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
             $reporte->PrintFTexto($text='hola',$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
             $reporte->PrintFTexto($text='hola',$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
							*/
     
						
            $reporte->PrintFTexto($text='CLINICA OCCIDENTE TULUA S.A.',$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
            $reporte->SaltoDeLinea();
            $reporte->PrintFTexto($text='NIT 800.118.755-2',$bold=false,$align='center',$redColor=false,$FuenteGrande=false);
            $reporte->PrintFTexto($text='Cra. 34 No.26-09 Túlua Nariño (Valle)',$bold=false,$align='center',$redColor=false,$FuenteGrande=false);
            $reporte->SaltoDeLinea();
            $reporte->PrintFTexto($text='FACTURA CAMBIARIA DE COMPRAVENTA',$bold=true,$align='center',$redColor=false,$FuenteGrande=false);
            $reporte->PrintFTexto($text='No. FP-00004236',$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
            $reporte->SaltoDeLinea();
            $reporte->PrintFTexto($text='Fecha   : 02/05/2004 18:42',$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
            $reporte->PrintFTexto($text="Usuario : 173 - $misdatos[USUARIO]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
            $reporte->PrintFTexto($text="Caja    : $misdatos[CAJA]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
            $reporte->SaltoDeLinea();
            $reporte->PrintFTextoValor($text='DETALLE',$valor='VALOR',$decimales=0,$signoMoneda=false,$posiciones=11,$text_bold=true,$align_text='left');
            $reporte->SaltoDeLinea();
            $reporte->PrintFTextoValor($text='TEST DE ESCOLIESIS',$valor=32752,$decimales=0,$signoMoneda=true,$posiciones=11,$text_bold=false,$align_text='left');
            $reporte->PrintFTextoValor($text='PRUEBA DE RESISTENCIA KH2',$valor=1200000,$decimales=0,$signoMoneda=true,$posiciones=11,$text_bold=false,$align_text='left');
            $reporte->PrintFTextoValor($text='SODIO,POTASIO,CLORO,BROMO Y ELEMENTOS PESADOS EN DERIVADOS DE LA SANGRE',$valor=456258,$decimales=0,$signoMoneda=true,$posiciones=11,$text_bold=false,$align_text='left');
            $reporte->PrintFTextoValor($text='TEST DE PULGAS',$valor=100000,$decimales=0,$signoMoneda=true,$posiciones=11,$text_bold=false,$align_text='left');        
            $reporte->SaltoDeLinea();
            $reporte->PrintFTextoValor($text='TOTAL',$valor=1789010,$decimales=0,$signoMoneda=true,$posiciones=11,$text_bold=true,$align_text='right');
            
            $reporte->PrintEnd();
            $reporte->OpenCajaMonedera();
            $reporte->PrintCutPaper();

        return true;
    }
    
    

    
    //AQUI TODOS LOS METODOS QUE USTED QUIERA
    //---------------------------------------
}
?>
