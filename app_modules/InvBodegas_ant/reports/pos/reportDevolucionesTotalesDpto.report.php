<?php

/**
 * $Id: reportDevolucionesTotalesDpto.report.php,v 1.1 2005/08/18 13:35:53 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba para impresora pos
 */

class reportDevolucionesTotalesDpto_report extends pos_reports_class
{

    //constructor por default
    function reportDevolucionesTotalesDpto_report(){
        $this->pos_reports_class();
        return true;
    }

    function CrearReporte(){
			$reporte=&$this->driver; //obtener el driver
			$datosDevoluciones=&$this->datos; //obtener los datos enviados al reporte.
			$reporte->PrintFTexto("$datosDevoluciones[razonsocial]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			//$reporte->SaltoDeLinea();
			$reporte->PrintFTexto("BODEGA",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			$reporte->PrintFTexto("$datosDevoluciones[BodegaId] - $datosDevoluciones[Bodega] ( $datosDevoluciones[CentroUtilidad] )",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			$reporte->SaltoDeLinea();
			$reporte->PrintFTexto("DEVOLUCION MEDICAMENTOS E INSUMOS",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			$reporte->PrintFTexto("$datosDevoluciones[descripcionDpto]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			$reporte->SaltoDeLinea();
      $Datos=$datosDevoluciones['Datos'];
			if($Datos){
				$reporte->SaltoDeLinea();
				$reporte->PrintFTextoValor($text='PRODUCTO',$valor='CANTIDAD',$decimales=0,$signoMoneda=false,$posiciones=11,$text_bold=true,$align_text='left');
				$reporte->SaltoDeLinea();
				for($i=0;$i<sizeof($Datos);$i++){
					$reporte->PrintFTextoValor($text="( )".$Datos[$i]['codigo_producto'].'  '.$Datos[$i]['desmed'],$Datos[$i]['cantidad'].' '.$Datos[$i]['abreviatura'],$decimales=2,$signoMoneda=false,$posiciones=9,$text_bold=false,$align_text='left');
					if($Datos[$i]['ubicacion']){
					$reporte->PrintFTexto($text="Ubicacion : ".$Datos[$i]['ubicacion'],$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
					}
				}
			}
			$reporte->SaltoDeLinea();
			$reporte->PrintEnd();
			$reporte->OpenCajaMonedera();
			$reporte->PrintCutPaper();
			return true;
    }

    //AQUI TODOS LOS METODOS QUE USTED QUIERA
    //---------------------------------------
}
?>
