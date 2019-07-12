<?php

/**
 * $Id: rotulo_laboratorio_lt.report.php,v 1.3 2010/02/26 12:36:19 sandra Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com) 
 * @package IPSOFT-SIIS
 *
 * Reporte de formulamedica para impresora pos
 */

class rotulo_laboratorio_lt_report extends pos_reports_class
{
    //constructor por default
    function rotulo_laboratorio_lt_report()
    {
        $this->pos_reports_class();
        return true;
    }

    function CrearReporte()
    {
		IncludeLib("tarifario");
		$reporte=&$this->driver; //obtener el driver
		$datos=&$this->datos; //obtener los datos enviados al reporte.
		$reporte->PrintFTexto($datos[0][razon_social],true,$align='center',false,true);
		$reporte->SaltoDeLinea();
		//$reporte->PrintFTexto('LISTA DE TRABAJO LABORATORIO',false,$align='center',false,false);
		//$reporte->PrintFTexto('LISTA DE TRABAJO '.$datos[0][dpto],false,$align='center',false,false);
		//$reporte->PrintFTexto('ITEM DE LA ORDEN: '.$datos[0][numero_orden_id],true,'center',false,true);
		//$reporte->PrintLinea();
		$datos[0][numero_cumplimiento]=str_pad($datos[0][numero_cumplimiento],3,0,STR_PAD_LEFT);
		//$reporte->PrintFTexto($datos[0][fecha_cumplimiento].' - '.$datos[0][numero_cumplimiento],true,'center',false,true);
		$datos[0][nombre] = substr($datos[0][nombre],0,29);
		$reporte->PrintFTexto('Paciente:'.$datos[0][nombre],false,'justify',false,true);
		$reporte->PrintFTexto($datos[0][tipo_id_paciente].'-'.$datos[0][paciente_id].'',false,'center',false,true);
    if($datos[0][historia_numero]!='')
		{
      if ($datos[0][historia_prefijo]!='')
			{
			  $historia = $datos[0][historia_prefijo].' - '.$datos[0][historia_numero];
			}
			else
			{
        $historia = $datos[0][historia_numero];
			}
			$reporte->PrintFTexto('No. Historia: '.$historia,false,'center',false,true);
		}

		//$reporte->PrintLinea();

		for($i=0;$i<sizeof($datos[0][cargos]);$i++)
		{
			/*if(($datos[0][cargos][$i][tipo_os_lista_id] != $datos[0][cargos][$i-1][tipo_os_lista_id]))
			{
				$datos[0][cargos][$i][nombre_lista] = substr($datos[0][cargos][$i][nombre_lista],0,23);
			  $reporte->PrintFTextoValor($datos[0][fecha_cumplimiento].' - '.$datos[0][numero_cumplimiento],$datos[0][cargos][$i][nombre_lista],0,false,$posiciones=24,$text_bold=true,$align_text='left');
			}*/
			$reporte->PrintFTexto('Item de la Orden: '.$datos[0][cargos][$i][numero_orden_id],false,'center',false,true);

			//$datos[0][cargos][$i][descripcion] = substr($datos[0][cargos][$i][descripcion],0,33);
			$datos[0][cargos][$i][descripcion] = substr($datos[0][cargos][$i][descripcion],0,26);
			//$reporte->PrintFTexto($datos[0][cargos][$i][numero_orden_id].' - '.$datos[0][cargos][$i][cargo].' - '.$datos[0][cargos][$i][descripcion],true,'left',false,false);

			$a=explode('-',$datos[0][fecha_cumplimiento]);
			$fecha=strftime("%B %d de %Y",mktime(0,0,0,date('m'),date('d'),date('Y')));
//$datos[0][numero_cumplimiento],

			$reporte->PrintFTexto('Fecha: '.$fecha.' '.date("H:i"),false,'justify',false,true);
			$reporte->PrintFTexto($datos[0][cargos][$i][cargo].' '.$datos[0][cargos][$i][descripcion],true,'justify',false,false);
			/*if($datos[0][cargos][$i][tipo_os_lista_id] != $datos[0][cargos][$i+1][tipo_os_lista_id])
			{
				$reporte->PrintLinea();
			}*/
			//'.$datos[0][departamento].' 
			if(!empty($datos[0][nombre_dpto]))
			{  $reporte->PrintFTexto('Departamento: '.$datos[0][nombre_dpto],false,'justify',false,true);  }
		}

        //$reporte->PrintEnd();
				$reporte->SaltoDeLinea(7);
        $reporte->OpenCajaMonedera();
        $reporte->PrintCutPaper();
        return true;
    }
}
?>

