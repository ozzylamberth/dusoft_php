<?php

/**
 * $Id: reportDespacha.report.php,v 1.1.1.1 2009/09/11 20:36:49 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba para impresora pos
 */

class reportDespacha_report extends pos_reports_class
{

    //constructor por default
    function reportDespacha_report(){
      $this->pos_reports_class();
      return true;
    }

    function CrearReporte(){
      echo 'eeeeeeeeeeeeeeeeeeeeeeee';
			$reporte=&$this->driver; //obtener el driver
			$datosDespacho=&$this->datos; //obtener los datos enviados al reporte.
			$reporte->PrintFTexto($text="$datosDespacho[razonsocial]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			$reporte->PrintFTexto($text="$datosDespacho[BodegaId] - $datosDespacho[Bodega]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			$reporte->SaltoDeLinea();
			$reporte->PrintFTexto($text="DESPACHO DE MEDICAMENTOS",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			$reporte->SaltoDeLinea();
			$reporte->PrintFTexto($text="Despacha : $datosDespacho[cadusuSYstem]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->SaltoDeLinea();
			$reporte->PrintFTexto($text="Fecha    : $datosDespacho[Fecha]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Estacion : $datosDespacho[cadenaestacion]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Dpto     : $datosDespacho[cadptoestacion]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Solicita : $datosDespacho[cadusuestacion]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->SaltoDeLinea();
			$reporte->PrintFTexto($text="Identifi : $datosDespacho[tipoIdPaciente] $datosDespacho[Paciente]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Paciente : $datosDespacho[cadenanombre]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Cama     : $datosDespacho[pieza] $datosDespacho[cama]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Plan     : $datosDespacho[plan]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Tipo Afi : $datosDespacho[tipoAfiliadoId]     Rango : $datosDespacho[rango]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->SaltoDeLinea();
			$reporte->PrintFTextoValor($text='MEDICAMENTO',$valor='CANTIDAD',$decimales=0,$signoMoneda=false,$posiciones=11,$text_bold=true,$align_text='left');
			$reporte->SaltoDeLinea();
			$medicamentos=$datosDespacho[medicamentos];
			for($i=0;$i<sizeof($medicamentos);$i++)//por cada medicamento de la solicitud
			{
				$reporte->PrintFTextoValor($text="( )".$medicamentos[$i]['codigo_producto'].'  '.$medicamentos[$i]['descripmed'],$medicamentos[$i]['cantidad'].' '.$medicamentos[$i]['abreviatura'],$decimales=2,$signoMoneda=false,$posiciones=11,$text_bold=false,$align_text='left');
			}
			$reporte->SaltoDeLinea();
			$reporte->PrintEnd();
			$reporte->OpenCajaMonedera();
			$reporte->PrintCutPaper();
			return true;
    }
	}
?>
