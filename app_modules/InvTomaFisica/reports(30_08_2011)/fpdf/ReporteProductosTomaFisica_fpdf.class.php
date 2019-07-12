<?php
    /**
    * @package IPSOFT-SIIS
    * @version $Id: ReporteProductosTomaFisica.report.php,v 1.1 2009/12/31 13:52:24 johanna Exp $ 
    * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
    * @author JAIME GOMEZ
    */
    
    /**
    * Clase Reporte: ReporteProductosTomaFisica_report 
    * reporte con los datos de todos los productos de una toma fisica determinada.
    * @package IPSOFT-SIIS
    * @version $Revision: 1.1 $
    * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
    * @author Jaime Gomez
    */

   
  class ReporteProductosTomaFisica_fpdf 
	{ 
         var $error = "";
    /**
    * Constructor de la clase
    */
    function ReporteProductosTomaFisica_fpdf(){}
    /**
    * Funcion para generar el archivo pdf
    *
    * @param array $prm Arreglo de oarametros del request
    *
    * @return boolean
    */
    function GetReporteFPDF($prm,$nombre,$pathImagen)
    {
      $_SESSION['REPORTES']['VARIABLE'] = "";
      $this->GenerarAutorizacionServicios($datos,$nombre,$pathImagen,$prm);
      return true;
    }
    /**
    * Metodo donde se genera el reporte en pdf
    *
    * @param array $datos Arreglo de datos con la informacion a incluir en el pdf
    * @param string $Dir Directorio donde se creara el archivo
    * @param string $pathImagen Ruta del logo del archivo
    * @param array $prm Arreglo de datos con los parametros pasados al reporte
    *
    * @return true;
    */
   function GenerarAutorizacionServicios($datos,$Dir,$pathImagen,$prm)
   {
       $consulta=  AutoCarga::factory("TomaFisicaSQL","","app","InvTomaFisica");
       $datox=$consulta->ReporteProductosTomaFisica($_REQUEST['parametros']['datos']['toma_fisica'],$_REQUEST['parametros']['datos']['empresa_id'],$_REQUEST['parametros']['datos']['centro_utilidad'],$_REQUEST['parametros']['datos']['bodega'],$_REQUEST['parametros']['datos']['filtro']);
       //var_dump($datox);
       define('FPDF_FONTPATH','font/');
       //$pdf=new PDF('P','mm','mcarta1');
        $pdf=new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','',7);
  	       
    if(!empty($datox))
    {
      $y0 = $pdf->GetY();
       $datobodega=$consulta->bodegasname($_REQUEST['parametros']['datos']['bodega'],$_REQUEST['parametros']['datos']['centro_utilidad'],$_REQUEST['parametros']['datos']['empresa_id']);
       $pdf->Ln(2);
       $pdf->SetFont('Arial','B',7);
       $pdf->Cell(90,5,"REPORTE DE PRODUCTOS DE LA TOMA FISICA ".$_REQUEST['parametros']['datos']['toma_fisica']."",0);
       $pdf->Cell(28,5,"NUMERO DE LISTA:",0,1);
       $pdf->Ln(2);
       $pdf->Cell(90,5,"BODEGA :".$datobodega[0]['descripcion']."",0);
       $pdf->Cell(28,5,"CONTEO:",0,1);
       $pdf->Ln(2);
       $pdf->Cell(90,5,"USUARIO :______________________________",0);
       $pdf->Cell(28,5,"FECHA :_______________________",0,1);
       $pdf->Ln(2);
     
       $y1 = 0;
       $pg = $pdf->h ;
       foreach($datox as $key=>$valor)
       {
          $pdf->Ln(3);
          $pdf->SetFont('Arial','B',7);
          $pdf->Cell(190,4,"".$key."",1,1,"C");
          $pdf->Cell(20,4,"ETIQUETA",1,0,"C");
          $pdf->Cell(30,4,"CODIGO PRODUCTO",1,0,"C");
          $pdf->Cell(60,4,"DESCRIPCION",1,0,"C");
          $pdf->Cell(30,4,"FECHA VENCIMIENTO",1,0,"R");
          $pdf->Cell(20,4,"LOTE",1,0,"C");
          $pdf->Cell(30,4,"CANTIDAD",1,1,"C");
          
          $pdf->SetFont('Arial','B',5);
          foreach($valor as $key1=>$valor1)
          {
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            
            $pdf->Cell(20,4,"".$valor1['etiqueta']."",0,0,"C");
            $pdf->Cell(30,4,"".$valor1['codigo_producto']."",0,0,"C");
            $pdf->SetX($x+100);
            $pdf->Cell(30,4,"".$valor1['fecha_vencimiento']."",0,0,"C");
            $pdf->Cell(30,4,"".$valor1['lote']."",0,0,"C");
            $pdf->Cell(20,4,"",0,0,"C");
            

            $pdf->SetX($x+50);
            $pdf->MultiCell(50,4,$valor1['descripcion_producto'],0,"C");
            $y1 = $pdf->GetY();
                        
            $h=$y1-$y;
            if($h < 0)
            {
              $y = $y0;
              $h = $y1 - $y0;
            } 
            $pdf->Rect($x,$y,190,$h);

            $pdf->Line($x+20,$y,$x+20,$y+$h);
            $pdf->Line($x+50,$y,$x+50,$y+$h);
            $pdf->Line(120,$y,120,$y+$h);
            $pdf->Line(150,$y,150,$y+$h);
            $pdf->Line(170,$y,170,$y+$h);
            
          }
       }       
     }
     $pdf->WriteHTML($salida); 
     //return $pdf2;return true;
     $pdf->Output($Dir,'F');
     return true;
  }
}

?>
