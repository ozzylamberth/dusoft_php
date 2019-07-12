<?php
//Reporte de formulamedica para impresora pos

//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
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
        $reporte->SaltoDeLinea();
        //$reporte->PrintFTexto('Cliente : '.$datos[Tercero],false,'left',false,false);
        $reporte->PrintFTexto('Plan    : '.$datos[Responsable],false,'left',false,false);          
        $reporte->SaltoDeLinea();
        $reporte->PrintFTextoValor('Valor a cancelar por el Usuario',$datos[liqcita][0][total_paciente],0,true,11,false,'left');
        $reporte->SaltoDeLinea();        
        $reporte->PrintFTexto('Sr. Usuario si Requiere Cancelar la Cita Minimo '.$datos[DiasCancelacion].' horas antes al Teléfono '.$datos[TelefonoCancelacion],false,'left',false,false);              
        $reporte->PrintEnd();
       // $reporte->OpenCajaMonedera();
        $reporte->PrintCutPaper();
        return true;
    }
}
?>

