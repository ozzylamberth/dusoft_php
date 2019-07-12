<?php
//Reporte de formulamedica para impresora pos

//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
class impresion_agenda_report extends pos_reports_class
{
    //constructor por default
    function impresion_agenda_report()
    {
        $this->pos_reports_class();
        return true;
    }

    function CrearReporte()
    {
        $reporte=&$this->driver; //obtener el driver
        $datos=&$this->datos; //obtener los datos enviados al reporte.
	$reporte->PrintFTexto('AGENDA MEDICA',true,$align='center',false,true);
        $reporte->SaltoDeLinea();
	foreach($datos as $k=>$v)
	{
		break;
	}
	$reporte->PrintFTexto('DOCTOR (A): '.$v[nombre_tercero],true,$align='center',false,false);
        $reporte->PrintFTexto('Identificación: '.$v[tipo_id_tercero].' '.$v[tercero_id],true, $align='center',false,false);
	$reporte->PrintFTexto('Especialidad: '.$v[descripcion],true, $align='center',false,false);
	$reporte->PrintFTexto('Fecha de Consulta: '.$v[fecha_turno],true, $align='center',false,false);
        $reporte->SaltoDeLinea();
	$i=0;
	foreach($datos as $k=>$v)
	{
	if($v[sw_atencion]==0)
	{
		$f=strlen($v[hora])+1+strlen($v[tipo_id_paciente])+1+strlen($v[paciente_id])+1;
		$k=($f-39)*-1;
		if ($v[sw_estado]==1)
		{
			$x= 'A';
			$e = $e+1;
		}
		elseif($v[sw_estado]==2)
		{
			$x= 'P';
			$u = $u+1;
		}
		elseif($v[sw_estado]==3)
		{
			$x= 'C';
			$q = $q+1;
		}
		$reporte->PrintFTextoValor($v[hora].' '.$v[tipo_id_paciente].' '.$v[paciente_id].' '.substr($v[nombre_completo],0,$k),$x,0,false,2,false,'left');
	}
		$i=$i+1;
	}
	$reporte->SaltoDeLinea();            
	$reporte->PrintFTexto('(A)= Activa  (P)= Pagada   (C)= Cancelada',false,'left',false,false);
	$reporte->SaltoDeLinea();            
	$reporte->PrintFTexto('(A) = '.$e,false,'left',false,false);
	$reporte->PrintFTexto('(P) = '.$u,false,'left',false,false);
	$reporte->PrintFTexto('(C) = '.$q,false,'left',false,false);
	$reporte->PrintEnd();
        $reporte->PrintCutPaper();
        return true;
    }
}
?>

