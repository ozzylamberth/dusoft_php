<?php

/**
 * $Id: reportSolicitudDepartamento.report.php,v 1.1.1.1 2009/09/11 20:36:49 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba para impresora pos
 */

class reportSolicitudDepartamento_report extends pos_reports_class
{

    //constructor por default
    function reportSolicitudDepartamento_report(){
        $this->pos_reports_class();
        return true;
    }

    function CrearReporte(){
        $reporte=&$this->driver; //obtener el driver
        $datosSolicitud=&$this->datos; //obtener los datos enviados al reporte.
				$reporte->PrintFTexto("$datosSolicitud[razonsocial]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
				//$reporte->SaltoDeLinea();
				$reporte->PrintFTexto("BODEGA",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
        $reporte->PrintFTexto("$datosSolicitud[BodegaId] - $datosSolicitud[Bodega] ( $datosSolicitud[CentroUtilidad] )",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
				$reporte->SaltoDeLinea();
				$reporte->PrintFTexto("SOLICITUD DE MEDICAMENTOS E INSUMOS",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
        $reporte->PrintFTexto("$datosSolicitud[descripcionDpto]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
        $reporte->SaltoDeLinea();
        $Datos=$datosSolicitud['Datos'];
				$marca=0;
				foreach($Datos as $paciente=>$vector){
				  $pacienteAnt=-1;
          foreach($vector as $solicitudId=>$vector1){
					  $solicitudIdAnt=-1;
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

                if($solicitudId!=$solicitudIdAnt){
								  $solicitudIdAnt=$solicitudId;
									$reporte->PrintFTexto($text="No. Solicitud : $solicitudId",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$reporte->PrintFTexto($text="Fecha         : $datos[fecha_solicitud]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$cadenaestacion=substr($datos['estacion_id'].' - '.$datos['nomestacion'],0,31);
									$reporte->PrintFTexto($text="Estacion      : $cadenaestacion",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$cadenausuario=substr($datos['usuario_id'].' - '.$datos['usuarioestacion'],0,31);
									$reporte->PrintFTexto($text="Solicita      : $cadenausuario",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$reporte->SaltoDeLinea();
									$reporte->PrintFTextoValor($text='MEDICAMENTO',$valor='CANTIDAD',$decimales=0,$signoMoneda=false,$posiciones=11,$text_bold=true,$align_text='left');
				          $reporte->SaltoDeLinea();
									$reporte->PrintFTextoValor($text="( )".$datos['codigo_producto'].'  '.$datos['desmed'],$datos['cant_solicitada'].' '.$datos['abreviatura'],$decimales=2,$signoMoneda=false,$posiciones=9,$text_bold=false,$align_text='left');
									if($datos['ubicacion']){
									$reporte->PrintFTexto($text="Ubicacion : ".$datos['ubicacion'],$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									}
									$reporte->SaltoDeLinea();
									$marca='1';
								}else{
                  $reporte->PrintFTextoValor($text="( )".$datos['codigo_producto'].'  '.$datos['desmed'],$datos['cant_solicitada'].' '.$datos['abreviatura'],$decimales=2,$signoMoneda=false,$posiciones=9,$text_bold=false,$align_text='left');
									if($datos['ubicacion']){
									$reporte->PrintFTexto($text="Ubicacion : ".$datos['ubicacion'],$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									}
									$reporte->SaltoDeLinea();
								}
							}else{

							  if($solicitudId!=$solicitudIdAnt){
								  $solicitudIdAnt=$solicitudId;
									$reporte->PrintFTexto($text="No. Solicitud : $solicitudId",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$reporte->PrintFTexto($text="Fecha         : $datos[fecha_solicitud]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$cadenaestacion=substr($datos['estacion_id'].' - '.$datos['nomestacion'],0,31);
									$reporte->PrintFTexto($text="Estacion      : $cadenaestacion",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$cadenausuario=substr($datos['usuario_id'].' - '.$datos['usuarioestacion'],0,31);
									$reporte->PrintFTexto($text="Solicita      : $cadenausuario",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									$reporte->SaltoDeLinea();
									$reporte->PrintFTextoValor($text='MEDICAMENTO',$valor='CANTIDAD',$decimales=0,$signoMoneda=false,$posiciones=11,$text_bold=true,$align_text='left');
				          $reporte->SaltoDeLinea();
									$reporte->PrintFTextoValor($text="( )".$datos['codigo_producto'].'  '.$datos['desmed'],$datos['cant_solicitada'].' '.$datos['abreviatura'],$decimales=2,$signoMoneda=false,$posiciones=9,$text_bold=false,$align_text='left');
									if($datos['ubicacion']){
									$reporte->PrintFTexto($text="Ubicacion : ".$datos['ubicacion'],$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
									}
									$reporte->SaltoDeLinea();
								}else{
                  $reporte->PrintFTextoValor($text="( )".$datos['codigo_producto'].'  '.$datos['desmed'],$datos['cant_solicitada'].' '.$datos['abreviatura'],$decimales=2,$signoMoneda=false,$posiciones=9,$text_bold=false,$align_text='left');
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
				  $reporte->PrintFTextoValor($text="( )".$medicamentos[$i]['codigo_producto'].'  '.$medicamentos[$i]['desmed'],$medicamentos[$i]['cant_solicitada'].' '.$medicamentos[$i]['abreviatura'],$decimales=2,$signoMoneda=false,$posiciones=9,$text_bold=false,$align_text='left');
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
