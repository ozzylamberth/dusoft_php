<?php

/**
 * $Id: reportDevolucionesDepartamento.report.php,v 1.1 2005/08/18 13:35:43 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba para impresora pos
 */

class reportDevolucionesDepartamento_report extends pos_reports_class
{

    //constructor por default
    function reportDevolucionesDepartamento_report(){
        $this->pos_reports_class();
        return true;
    }

    function CrearReporte(){
        $reporte=&$this->driver; //obtener el driver
        $datosDevolucion=&$this->datos; //obtener los datos enviados al reporte.
				$reporte->PrintFTexto("$datosDevolucion[razonsocial]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
				//$reporte->SaltoDeLinea();
				$reporte->PrintFTexto("BODEGA",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
        $reporte->PrintFTexto("$datosDevolucion[BodegaId] - $datosDevolucion[Bodega] ( $datosDevolucion[CentroUtilidad] )",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
				$reporte->SaltoDeLinea();
				$reporte->PrintFTexto("DEVOLUCION MEDICAMENTOS E INSUMOS",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
        $reporte->PrintFTexto("$datosDevolucion[descripcionDpto]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
        $reporte->SaltoDeLinea();
        $Datos=$datosDevolucion['Datos'];
				$marca=0;
				foreach($Datos as $paciente=>$vector){
				  $pacienteAnt=-1;
          foreach($vector as $devolucionId=>$vector1){
					  $devolucionIdAnt=-1;
            foreach($vector1 as $consecutivoId=>$datos){
						  if($paciente!=$pacienteAnt){
                $pacienteAnt=$paciente;
								if($marca=='1'){
								$reporte->PrintFTexto("-----------------------------------",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
								}
								$reporte->SaltoDeLinea();
								$reporte->PrintFTexto($text="Identifi : $paciente",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
								$reporte->PrintFTexto($text="Paciente : $datos[nombrepac]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
								$reporte->PrintFTexto($text="Cama     : $datos[pieza] $datos[cama]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
								$reporte->PrintFTexto($text="Plan     : $datos[plan_descripcion]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
								$reporte->PrintFTexto($text="Tipo Afi : $datos[tipo_afiliado_id]     Rango : $datos[rango]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
								$reporte->SaltoDeLinea();

                if($devolucionId!=$devolucionIdAnt){
								  $devolucionIdAnt=$devolucionId;
									$reporte->PrintFTexto($text="No. Devolucion : $devolucionId",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$reporte->PrintFTexto($text="Fecha         : $datos[fecha]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$cadenaestacion=substr($datos['estacion_id'].' - '.$datos['nomestacion'],0,31);
									$reporte->PrintFTexto($text="Estacion      : $cadenaestacion",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$cadenausuario=substr($datos['usuario_id'].' - '.$datos['usuarioestacion'],0,31);
									$reporte->PrintFTexto($text="Devuelve      : $cadenausuario",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$reporte->SaltoDeLinea();
									$reporte->PrintFTextoValor($text='MEDICAMENTO',$valor='CANTIDAD',$decimales=0,$signoMoneda=false,$posiciones=11,$text_bold=true,$align_text='left');
				          $reporte->SaltoDeLinea();
									$reporte->PrintFTextoValor($text="( )".$datos['codigo_producto'].'  '.$datos['desmed'],$datos['cantidad'].' '.$datos['abreviatura'],$decimales=2,$signoMoneda=false,$posiciones=9,$text_bold=false,$align_text='left');
									if($datos['ubicacion']){
									$reporte->PrintFTexto($text="Ubicacion : ".$datos['ubicacion'],$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									}
									$reporte->SaltoDeLinea();
									$marca='1';
								}else{
                  $reporte->PrintFTextoValor($text="( )".$datos['codigo_producto'].'  '.$datos['desmed'],$datos['cantidad'].' '.$datos['abreviatura'],$decimales=2,$signoMoneda=false,$posiciones=9,$text_bold=false,$align_text='left');
									if($datos['ubicacion']){
									$reporte->PrintFTexto($text="Ubicacion : ".$datos['ubicacion'],$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									}
									$reporte->SaltoDeLinea();
								}
							}else{

							  if($devolucionId!=$devolucionIdAnt){
								  $devolucionIdAnt=$devolucionId;
									$reporte->PrintFTexto($text="No. Devolucion : $devolucionId",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$reporte->PrintFTexto($text="Fecha         : $datos[fecha]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$cadenaestacion=substr($datos['estacion_id'].' - '.$datos['nomestacion'],0,31);
									$reporte->PrintFTexto($text="Estacion      : $cadenaestacion",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$cadenausuario=substr($datos['usuario_id'].' - '.$datos['usuarioestacion'],0,31);
									$reporte->PrintFTexto($text="Solicita      : $cadenausuario",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$reporte->SaltoDeLinea();
									$reporte->PrintFTextoValor($text='MEDICAMENTO',$valor='CANTIDAD',$decimales=0,$signoMoneda=false,$posiciones=11,$text_bold=true,$align_text='left');
				          $reporte->SaltoDeLinea();
									$reporte->PrintFTextoValor($text="( )".$datos['codigo_producto'].'  '.$datos['desmed'],$datos['cantidad'].' '.$datos['abreviatura'],$decimales=2,$signoMoneda=false,$posiciones=9,$text_bold=false,$align_text='left');
									if($datos['ubicacion']){
									$reporte->PrintFTexto($text="Ubicacion : ".$datos['ubicacion'],$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									}
									$reporte->SaltoDeLinea();
								}else{
                  $reporte->PrintFTextoValor($text="( )".$datos['codigo_producto'].'  '.$datos['desmed'],$datos['cantidad'].' '.$datos['abreviatura'],$decimales=2,$signoMoneda=false,$posiciones=9,$text_bold=false,$align_text='left');
									if($datos['ubicacion']){
									$reporte->PrintFTexto($text="Ubicacion : ".$datos['ubicacion'],$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									}
									$reporte->SaltoDeLinea();
								}
							}
				    }
				  }
				}
				/*$reporte->PrintFTexto($text="Fecha    : $datosSolicitud[Fecha]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Estacion : $datosSolicitud[cadenaestacion]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Dpto     : $datosSolicitud[cadptoestacion]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Solicita : $datosSolicitud[cadenausuario]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
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
				  $reporte->PrintFTextoValor($text="( )".$medicamentos[$i]['codigo_producto'].'  '.$medicamentos[$i]['desmed'],$medicamentos[$i]['cantidad'].' '.$medicamentos[$i]['abreviatura'],$decimales=2,$signoMoneda=false,$posiciones=9,$text_bold=false,$align_text='left');
					if($medicamentos[$i]['ubicacion']){
					$reporte->PrintFTexto($text="Ubicacion : ".$medicamentos[$i]['ubicacion'],$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
					}
					$reporte->SaltoDeLinea();
				}*/
				$reporte->PrintEnd();
				$reporte->OpenCajaMonedera();
				$reporte->PrintCutPaper();
        return true;
    }

    //AQUI TODOS LOS METODOS QUE USTED QUIERA
    //---------------------------------------
}
?>
