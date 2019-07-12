<?php

/**
 * $Id: tiquete_cita.report.php,v 1.4 2010/03/12 13:35:33 sandra Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS 
 *
 * Reporte de formulamedica para impresora pos
 */


class tiquete_cita_report extends pos_reports_class
{
    //constructor por default
    function tiquete_cita_report()
    {
        $this->pos_reports_class();
        return true;
    }

    function CrearReporte()
    {
        IncludeLib("tarifario");
        $reporte=&$this->driver; //obtener el driver
        $datos=&$this->datos; //obtener los datos enviados al reporte.
        $reporte->PrintFTexto($datos[empresa],true,$align='center',false,true);
        $reporte->SaltoDeLinea();
        $reporte->PrintFTexto('TIQUETE CITA - '.$datos[idcita],true,$align='center',false,false);
        $reporte->SaltoDeLinea();
        $reporte->PrintFTexto('Identifi: '.$datos[identificacion],false,'left',false,false);
        $reporte->PrintFTexto('Paciente: '.$datos[paciente],false,'left',false,false);
        $reporte->SaltoDeLinea();
        $reporte->PrintFTexto('FECHA DE CITA',true,'center',false,false);
	$datos1=explode(' ',$datos[fechacita]);
        $reporte->PrintFTexto('Día: '.$datos1[0].' '.$datos1[1].' '.$datos1[2].' '.$datos1[3].' '.$datos1[4].' '.$datos1[5],true,'left',false,false);
	$reporte->PrintFTexto('Hora: '.$datos1[6].' '.$datos1[7].' '.$datos1[8].' '.$datos1[9],true,'left',false,false);
        $reporte->SaltoDeLinea();            
        $reporte->PrintFTexto('Departamento: '.$datos[departamento],false,'left',false,false);
        $reporte->PrintFTexto('Tipo Cita   : '.$datos[tipoconsulta],false,'left',false,false);
        $reporte->PrintFTexto('Profesional : '.$datos[profesional],false,'left',false,false);
		$reporte->PrintFTexto('Ubicación   : '.$datos[ubicacion],false,'left',false,false);
		$reporte->PrintFTexto('Dirección   : '.$datos[departamentoUbicacion],false,'left',false,false);
		$reporte->PrintFTexto('Teléfono   : '.$datos[TelefonoCancelacion],false,'left',false,false);
		IF($datos[nom_consultorio]<> NULL){
		$reporte->PrintFTexto('No. Consultorio   : '.$datos[nom_consultorio],false,'left',false,false);
		}
        $reporte->SaltoDeLinea();
        //$reporte->PrintFTexto('Cliente : '.$datos[Tercero],false,'left',false,false);
        $reporte->PrintFTexto('Plan    : '.$datos[Responsable],false,'left',false,false);          
        $reporte->SaltoDeLinea();
        $reporte->PrintFTexto('Valor a cancelar por el Usuario',false,'left',false,false);
        $reporte->PrintFTextoValor(' ',$datos[liqcita][0][total_paciente],0,true,35,false,'left');
        $reporte->SaltoDeLinea();
	 $reporte->PrintFTexto('Fecha de asignacion Cita: '.$datos[fecharegistro],false,'left',false,false);
	 $reporte->SaltoDeLinea();
        $reporte->PrintFTexto('Sr. Usuario si Requiere Cancelar la Cita  Minimo '.$datos[DiasCancelacion].' horas antes al Teléfono '.$datos[TelefonoCancelacion],false,'left',false,false);              
        
		$reporte->PrintEnd();
       // $reporte->OpenCajaMonedera();
        $reporte->PrintCutPaper();
        return true;
    }
}
?>

