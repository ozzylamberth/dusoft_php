<?php

/**
 * $Id: reportSolicitud.report.php,v 1.3 2005/08/18 13:36:09 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba para impresora pos
 */

class reportSolicitud_report extends pos_reports_class
{

    //constructor por default
    function reportSolicitud_report(){
        $this->pos_reports_class();
        return true;
    }

    function CrearReporte(){
        $reporte=&$this->driver; //obtener el driver
        $datosSolicitud=&$this->datos; //obtener los datos enviados al reporte.
				$reporte->PrintFTexto($text="$datosSolicitud[razonsocial]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
				$reporte->SaltoDeLinea();
        $reporte->PrintFTexto($text="$datosSolicitud[BodegaId] - $datosSolicitud[Bodega]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
				$reporte->SaltoDeLinea();
				$reporte->PrintFTexto($text="SOLICITUD DE MEDICAMENTOS",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
        $reporte->SaltoDeLinea();
        $reporte->PrintFTexto($text="No. Solicitud : $datosSolicitud[SolicitudId]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Fecha         : $datosSolicitud[Fecha]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Estacion      : $datosSolicitud[cadenaestacion]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Dpto          : $datosSolicitud[cadptoestacion]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Solicita      : $datosSolicitud[cadenausuario]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
  			$reporte->SaltoDeLinea();
        $reporte->PrintFTexto($text="Identifi : $datosSolicitud[tipoidPac] $datosSolicitud[paciente]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Paciente : $datosSolicitud[cadnombrepac]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Cama     : $datosSolicitud[pieza] $datosSolicitud[cama]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Plan     : $datosSolicitud[plan]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
        $reporte->PrintFTexto($text="Tipo Afi : $datosSolicitud[tipoafil]     Rango : $datosSolicitud[rango]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->SaltoDeLinea();
				$reporte->PrintFTextoValor($text='MEDICAMENTO',$valor='CANTIDAD',$decimales=0,$signoMoneda=false,$posiciones=11,$text_bold=true,$align_text='left');
				$reporte->SaltoDeLinea();
				$medicamentos=$datosSolicitud[medicamentos];
        for($i=0;$i<sizeof($medicamentos);$i++){
				  $reporte->PrintFTextoValor($text="( )".$medicamentos[$i]['codigo_producto'].'  '.$medicamentos[$i]['desmed'],$medicamentos[$i]['cant_solicitada'].' '.$medicamentos[$i]['abreviatura'],$decimales=2,$signoMoneda=false,$posiciones=9,$text_bold=false,$align_text='left');
					if($medicamentos[$i]['ubicacion']){
					$reporte->PrintFTexto($text="Ubicacion : ".$medicamentos[$i]['ubicacion'],$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
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
