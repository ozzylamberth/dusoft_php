<?php
    function Carnet_Jaime()
    {
        $_ROOT='../../../';
        $VISTA='HTML';
        include $_ROOT.'includes/enviroment.inc.php';

    //    IncludeLib("funciones_admision");
    //    IncludeLib("funciones_facturacion");
        $Dir="cache/carnet_jaime.pdf";

        require("classes/fpdf/html_class.php");
        require("classes/ReportesSoat/fpdf_reporte_soat.class.php");
//             {
//                 $this->error = "No se pudo inicializar la Clase de fpdf_reporte_soat";
//                 $this->mensajeDeError = "No se pudo Incluir el archivo : classes/ReportesSoat/fpdf_reporte_soat.class.php";
//                 return false;
//             }
        //include_once("classes/fpdf/conversor.php");
        define('FPDF_FONTPATH','font/');

        $pdf= new fpdf_reporte_soat('P','mm','legal');
         $pdf->set_correcion_x(1);//0.92
            $pdf->set_correcion_y(1);//0.60
        //$pdf2d=new PDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial','',10);
            //foreach($datos as $k=>$v)
            //{ 

/////////////////hoja1

                $pdf->Text_corregida(9,45,"80096802");//CEDULA
                $pdf->Text_corregida(52,45,"GOMEZ GUERRERO");//APELLIDOS
                $pdf->Text_corregida(52,52,"JAIME ANDRES");//NOMBRES
                $pdf->SetFont('Arial','',7);
                $pdf->Text_corregida(9,61,"UNIVERSIDAD XXX"); //U
                $pdf->SetFont('Arial','',10);
                $pdf->Text_corregida(52,61,"08-07-1982");//NACIMIENTO
                $pdf->Text_corregida(9,69,"08-07-2082");//VENCE
                $pdf->Text_corregida(58,69,"X");//COTIZA
                $pdf->Text_corregida(89,69,"X");//BENEFCIA
            //}



                $pdf->Text_corregida(114,45,"80096802");//CEDULA
                $pdf->Text_corregida(154,45,"GOMEZ GUERRERO");//APELLIDOS
                $pdf->Text_corregida(154,52,"JAIME ANDRES");//NOMBRES
                $pdf->SetFont('Arial','',7);
                $pdf->Text_corregida(114,61,"UNIVERSIDAD XXX"); //U
                $pdf->SetFont('Arial','',10);
                $pdf->Text_corregida(154,61,"08-07-1982");//NACIMIENTO
                $pdf->Text_corregida(114,69,"08-07-2082");//VENCE
                $pdf->Text_corregida(161,69,"X");//COTIZA
                $pdf->Text_corregida(192,69,"X");//BENEFCIA

///////////////////



/////////////////hoja1

                $pdf->Text_corregida(9,109,"80096802");//CEDULA
                $pdf->Text_corregida(52,109,"GOMEZ GUERRERO");//APELLIDOS
                $pdf->Text_corregida(52,117,"JAIME ANDRES");//NOMBRES
                $pdf->SetFont('Arial','',7);
                $pdf->Text_corregida(9,125,"UNIVERSIDAD XXX"); //U
                $pdf->SetFont('Arial','',10);
                $pdf->Text_corregida(52,125,"08-07-1982");//NACIMIENTO
                $pdf->Text_corregida(9,132,"08-07-2082");//VENCE
                $pdf->Text_corregida(58,132,"X");//COTIZA
                $pdf->Text_corregida(89,132,"X");//BENEFCIA
            //}



                $pdf->Text_corregida(114,109,"80096802");//CEDULA
                $pdf->Text_corregida(154,109,"GOMEZ GUERRERO");//APELLIDOS
                $pdf->Text_corregida(154,117,"JAIME ANDRES");//NOMBRES
                $pdf->SetFont('Arial','',7);
                $pdf->Text_corregida(114,125,"UNIVERSIDAD XXX"); //U
                $pdf->SetFont('Arial','',10);
                $pdf->Text_corregida(154,125,"08-07-1982");//NACIMIENTO
                $pdf->Text_corregida(114,132,"08-07-2082");//VENCE
                $pdf->Text_corregida(161,132,"X");//COTIZA
                $pdf->Text_corregida(192,132,"X");//BENEFCIA

///////////////////


/////////////////hoja2

                $pdf->Text_corregida(9,173,"80096802");//CEDULA
                $pdf->Text_corregida(52,173,"GOMEZ GUERRERO");//APELLIDOS
                $pdf->Text_corregida(52,181,"JAIME ANDRES");//NOMBRES
                $pdf->SetFont('Arial','',7);
                $pdf->Text_corregida(9,189,"UNIVERSIDAD XXX"); //U
                $pdf->SetFont('Arial','',10);
                $pdf->Text_corregida(52,189,"08-07-1982");//NACIMIENTO
                $pdf->Text_corregida(9,196,"08-07-2082");//VENCE
                $pdf->Text_corregida(58,196,"X");//COTIZA
                $pdf->Text_corregida(89,196,"X");//BENEFCIA
            //}



                $pdf->Text_corregida(114,173,"80096802");//CEDULA
                $pdf->Text_corregida(154,173,"GOMEZ GUERRERO");//APELLIDOS
                $pdf->Text_corregida(154,181,"JAIME ANDRES");//NOMBRES
                $pdf->SetFont('Arial','',7);
                $pdf->Text_corregida(114,189,"UNIVERSIDAD XXX"); //U
                $pdf->SetFont('Arial','',10);
                $pdf->Text_corregida(154,189,"08-07-1982");//NACIMIENTO
                $pdf->Text_corregida(114,196,"08-07-2082");//VENCE
                $pdf->Text_corregida(161,196,"X");//COTIZA
                $pdf->Text_corregida(192,196,"X");//BENEFCIA

///////////////////





            
            
        //echo "aqui toy";
        //$usu=NombreUsuario();
//         $html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
//         $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
//         $html.="<tr><td width=760>jaime andres gomezCUALQUIER DUDA AL RESPECTO DE ESTE DOCUMENTO, POR FAVOR COMUNIQUELO A COORDINACION GENERAL</td></tr>";
//         $html.="</table>";
//         $pdf->WriteHTML($html);
        $pdf->Output($Dir,'F');
        return true;
    }

Carnet_Jaime();

?>