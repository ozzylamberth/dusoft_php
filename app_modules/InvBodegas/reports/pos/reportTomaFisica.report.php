<?php

/**
 * $Id: reportTomaFisica.report.php,v 1.1.1.1 2009/09/11 20:36:49 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba para impresora pos
 */

class reportTomaFisica_report extends pos_reports_class
{

    //constructor por default
    function reportTomaFisica_report(){
      $this->pos_reports_class();
      return true;
    }

    function CrearReporte(){
      echo 'eeeeeeeeeeeeeeeeeeeeeeee';
			$reporte=&$this->driver; //obtener el driver
			
			$datosToma=&$this->datos; //obtener los datos enviados al reporte.						
			$datosTomaUn=$datosToma['datosUn'];
			$primerArray=$datosTomaUn[0];
			$SegundoArray=$datosTomaUn[1];
			$reporte->PrintFTexto($text="$datosToma[Empresa]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			$reporte->PrintFTexto($text="$datosToma[BodegaId] - $datosToma[Bodega]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			$reporte->SaltoDeLinea();
			$reporte->PrintFTexto($text="TOMA FISICA DE PRODUCTOS",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);			
			$reporte->SaltoDeLinea();
			$reporte->PrintFTexto($text="Fecha    : $primerArray[fecha_registro]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="No. Toma : $primerArray[toma_fisica_id]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Usuario  : $primerArray[nombre]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);				
			$reporte->SaltoDeLinea();
			$reporte->PrintFTextoValor($text='PRODUCTO',$valor='CANTIDAD',$decimales=0,$signoMoneda=false,$posiciones=11,$text_bold=true,$align_text='left');
			$reporte->SaltoDeLinea();	
			for($i=0;$i<sizeof($SegundoArray);$i++)//por cada medicamento de la solicitud
			{
				$reporte->PrintFTextoValor($text=$SegundoArray[$i]['codigo_producto'].'  '.$SegundoArray[$i]['nomproducto'],'_____',$decimales=2,$signoMoneda=false,$posiciones=9,$text_bold=false,$align_text='left');
				if($SegundoArray[$i]['ubicacion']){
				$reporte->PrintFTexto($text="Ubicacion : ".$SegundoArray[$i]['ubicacion'],$bold=true,$align='left',$redColor=false,$FuenteGrande=false);
				}
				$reporte->SaltoDeLinea();	
			}
			$reporte->SaltoDeLinea();
			$reporte->PrintEnd();
			$reporte->OpenCajaMonedera();
			$reporte->PrintCutPaper();
			return true;
    }
	}
?>
