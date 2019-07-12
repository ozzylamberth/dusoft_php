<?php

/**
 * $Id: reportDocumentoBodega.report.php,v 1.3 2005/08/09 21:41:46 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba para impresora pos
 */

class reportDocumentoBodega_report extends pos_reports_class
{

    //constructor por default
    function reportDocumentoBodega_report(){
      $this->pos_reports_class();
      return true;
    }

    function CrearReporte(){
      //echo 'eeeeeeeeeeeeeeeeeeeeeeee';
			$reporte=&$this->driver; //obtener el driver
			$datosDocumento=&$this->datos; //obtener los datos enviados al reporte.

			$reporte->PrintFTexto($text="$datosDocumento[Empresa]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			$reporte->PrintFTexto($text="$datosDocumento[BodegaId] - $datosDocumento[Bodega]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			$reporte->SaltoDeLinea();
			$reporte->PrintFTexto($text="DOCUMENTO DE BODEGA",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			$reporte->SaltoDeLinea();
			$reporte->PrintFTexto($text="Fecha    : $datosDocumento[fecha]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Document : $datosDocumento[Documento] Pf: $datosDocumento[Prefijo]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Concepto : $datosDocumento[cadenaconcepto]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Costo T  : $datosDocumento[costo]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
      if($datosDocumento[cadBodTrans]){
			$reporte->PrintFTexto($text="BodegaTr : $datosDocumento[cadBodTrans]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			}
			$reporte->SaltoDeLinea();
			if($datosDocumento[solicitud_id]){
			  $reporte->PrintFTexto($text="Estacion : $datosDocumento[cadenaEstacion]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Dpto     : $datosDocumento[caddptoEsta]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Solicita : $datosDocumento[cadusuEsta]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			}
			/*$reporte->PrintFTexto($text="Paciente : $datosDespacho[cadenanombre]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Cama     : $datosDespacho[pieza] $datosDespacho[cama]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Plan     : $datosDespacho[plan]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Tipo Afi : $datosDespacho[tipoAfiliadoId]     Rango : $datosDespacho[rango]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			*/
			$reporte->SaltoDeLinea();
			$reporte->PrintFTextoValor($text='PRODUCTO',$valor='CANTIDAD',$decimales=0,$signoMoneda=false,$posiciones=11,$text_bold=true,$align_text='left');
			$reporte->SaltoDeLinea();
			$productos=$datosDocumento['Productos'];
			for($i=0;$i<sizeof($productos);$i++)//por cada medicamento de la solicitud
			{
				$reporte->PrintFTextoValor($text="( )".$productos[$i]['codigo_producto'].'  '.$productos[$i]['descripcion_abreviada'],$productos[$i]['cantidad'].' '.$productos[$i]['abreviatura'],$decimales=2,$signoMoneda=false,$posiciones=11,$text_bold=false,$align_text='left');
			}
			$reporte->SaltoDeLinea();
			$reporte->PrintEnd();
			$reporte->OpenCajaMonedera();
			$reporte->PrintCutPaper();
			return true;
    }
	}
?>
