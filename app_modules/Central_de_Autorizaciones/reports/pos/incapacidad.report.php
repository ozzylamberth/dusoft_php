<?php

/**
 * $Id: incapacidad.report.php,v 1.1.1.1 2009/09/11 20:36:19 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de formulamedica para impresora pos
 */

class incapacidad_report extends pos_reports_class
{
    //constructor por default
    function incapacidad_report()
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
				$reporte->PrintFTexto($datos[0][tipo_id_tercero].' '.$datos[0][id],false,'center',false,false);
				$reporte->PrintFTexto($datos[0][direccion].' '.$datos[0][municipio].' '.$datos[0][departamento],false,'center',false,false);
				$reporte->SaltoDeLinea();
        $reporte->PrintFTexto('INCAPACIDAD MEDICA',true,$align='center',false,true);
				$reporte->SaltoDeLinea();


        //ojo cambie tipo_id por tipo_id_paciente porque asi se manda desde hospitalizacion, ojo
				//cuadrar en ambulatorio para que el dato llegue con este nombre
				$reporte->PrintFTexto('Identifi   : '.$datos[0][tipo_id_paciente].' '.$datos[0][paciente_id],false,'left',false,false);
				$reporte->PrintFTexto('Paciente   : '.$datos[0][paciente],false,'left',false,false);
				$reporte->PrintFTexto('Cliente    : '.$datos[0][cliente],false,'left',false,false);
				$reporte->PrintFTexto('Plan       : '.$datos[0][plan_descripcion],false,'left',false,false);
        $reporte->PrintFTexto('Tipo Afi   : '.$datos[0][tipo_afiliado_nombre].'     Rango: '.$datos[0][rango],false,'left',false,false);

				$reporte->PrintFTexto('Atención   : '.$datos[0][evolucion_id],false,'left',false,false);

       // $cadena = substr($cadena, 0,31)

        $reporte->SaltoDeLinea();
				$reporte->PrintFTexto(strtoupper($datos[0][tipo_incapacidad_descripcion]),true,'center',false,false);
				$reporte->SaltoDeLinea();
        $reporte->PrintFTexto('Servicio             : '.$datos[0][servicio],false,'left',false,false);
               	$fecha = explode("-",$datos[0][fecha_inicio]);
          		$fecha_inicio = $fecha[2]."/".$fecha[1]."/".$fecha[0];
				$reporte->PrintFTexto('Fecha de Emisión     : '.$fecha_inicio,false,'left',false,false);
        $reporte->PrintFTexto('Fecha de Terminación : '.$datos[0][fecha_terminacion],false,'left',false,false);
				$reporte->PrintFTexto('Duración             : '.$datos[0][dias_de_incapacidad].' dias',false,'left',false,false);
				$reporte->SaltoDeLinea();
				if($datos[0][observacion_incapacidad]!='')
				{
				  $reporte->PrintFTexto('Observación : '.$datos[0][observacion_incapacidad],false,'left',false,false);
				}
				$reporte->SaltoDeLinea();
        $reporte->PrintFTexto('Diagnostico(s)  :',false,'left',false,false);

				if(!empty($datos[0][diagnostico_ingreso]) AND empty($datos[0][diagnostico_egreso]))
				{
						foreach ($datos[0][diagnostico_ingreso] as $k => $v)
						{
							$reporte->PrintFTexto($v[diagnostico_id].' - '.$v[diagnostico_nombre],false,'left',false,false);
						}
				}
				else
				{
						foreach ($datos[0][diagnostico_egreso] as $k => $v)
						{
							$reporte->PrintFTexto($v[diagnostico_id].' - '.$v[diagnostico_nombre],false,'left',false,false);
						}
				}
				$reporte->SaltoDeLinea(2);
				$reporte->PrintFTexto('MEDICO TRATANTE:',true,$align='left',false,false);
				$reporte->SaltoDeLinea(4);
				$reporte->PrintFTexto('--------------------------------',true,$align='left',false,false);
        $reporte->PrintFTexto($datos[0][nombre_tercero],false,'left',false,false);
				if($datos[0][tarjeta_profesional] != '')
				{
				  $reporte->PrintFTexto($datos[0][tipo_id_medico].': '.$datos[0][medico_id].' T.P.: '.$datos[0][tarjeta_profesional],false,'left',false,false);
				}
				else
				{
          $reporte->PrintFTexto($datos[0][tipo_id_medico].': '.$datos[0][medico_id],false,'left',false,false);
				}
				$reporte->PrintFTexto($datos[0][tipo_profesional],false,'left',false,false);

        $reporte->PrintEnd();
        $reporte->OpenCajaMonedera();
        $reporte->PrintCutPaper();
        return true;
    }
}
?>

