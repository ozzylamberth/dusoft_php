<?php

/**
 * $Id: reportDevolucion.report.php,v 1.1 2007/05/28 13:25:41 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba para impresora pos
 */

class reportDevolucion_report extends pos_reports_class
{

    //constructor por default
    function reportDevolucion_report(){
        $this->pos_reports_class();
        return true;
    }

    function CrearReporte(){
        $reporte=&$this->driver; //obtener el driver
        $datosDevolucion=&$this->datos; //obtener los datos enviados al reporte.
				$reporte->PrintFTexto($text="$datosDevolucion[razonsocial]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
				$reporte->SaltoDeLinea();
        $reporte->PrintFTexto($text="$datosDevolucion[BodegaId] - $datosDevolucion[Bodega]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
				$reporte->SaltoDeLinea();
				$reporte->PrintFTexto($text="DEVOLUCION INSUMOS Y MEDICAMENTOS",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
        $reporte->SaltoDeLinea();
				$reporte->PrintFTexto($text="No. Devolucion : $datosDevolucion[documento]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Fecha         : $datosDevolucion[Fecha]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Estacion      : $datosDevolucion[cadenaestacion]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Dpto          : $datosDevolucion[cadptoestacion]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Devuelve      : $datosDevolucion[cadenausuario]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
  			$reporte->SaltoDeLinea();
        $reporte->PrintFTexto($text="Identifi : $datosDevolucion[tipoidPac] $datosDevolucion[paciente]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Paciente : $datosDevolucion[cadnombrepac]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Cama     : $datosDevolucion[pieza] $datosDevolucion[cama]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Plan     : $datosDevolucion[plan]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
        $reporte->PrintFTexto($text="Tipo Afi : $datosDevolucion[tipoafil]     Rango : $datosDevolucion[rango]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->SaltoDeLinea();
				$reporte->PrintFTextoValor($text='PRODUCTO',$valor='CANTIDAD',$decimales=0,$signoMoneda=false,$posiciones=11,$text_bold=true,$align_text='left');
				$reporte->SaltoDeLinea();
				$productos=$datosDevolucion[productos];
        for($i=0;$i<sizeof($productos);$i++){
				  $reporte->PrintFTextoValor($text="( )".$productos[$i]['codigo_producto'].'  '.$productos[$i]['desmed'],$productos[$i]['cantidad'].' '.$productos[$i]['abreviatura'],$decimales=2,$signoMoneda=false,$posiciones=9,$text_bold=false,$align_text='left');
					if($productos[$i]['ubicacion']){
					$reporte->PrintFTexto($text="Ubicacion : ".$productos[$i]['ubicacion'],$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
					}
					$reporte->SaltoDeLinea();
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
