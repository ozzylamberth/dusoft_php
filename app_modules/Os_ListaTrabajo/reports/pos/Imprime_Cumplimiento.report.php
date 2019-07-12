<?php

/**
 * @package IPSOFT-SIIS
 *
 * 
 */

class Imprime_Cumplimiento_report extends pos_reports_class
{
    
    //constructor por default
    function Imprime_Cumplimiento_report()
    {
        $this->pos_reports_class();
        return true;
    }
		
				
		
    /**
    *
    */
     function CrearReporte()
     {
          include_once("classes/fpdf/conversor.php");
          $reporte=&$this->driver; //obtener el driver
          $datos=&$this->datos; //obtener los datos enviados al reporte.

          $descripcion_small = substr ($datos['descripcion'],0,42);
          //stick de la placa
          $reporte->PrintFTexto($datos[razon_social],true,'center',false,false);
          $reporte->PrintFTexto("Pac: ".$datos[tipo_id_paciente]." ".$datos[paciente_id]." - ".$datos[nombre],true,'left',false,false);          
          $reporte->PrintFTexto($datos[plan_descripcion],true,'left',false,false);
          $reporte->PrintFTexto("Orden: ".$datos[numero_orden]."    Fecha: ".$datos[fecha_cumplimiento],true,'left',false,false);
          $reporte->PrintFTexto($descripcion_small,true,'left',false,false); 
          $USER_small = substr($datos[tecnico],0,22);
          if($datos[cama])
          {
          	$reporte->PrintFTexto($datos[servicio]."-".$datos[cama]." - ".$USER_small,true,'left',false,false);
          }else
          {
          	$reporte->PrintFTexto($datos[servicio]."  -  ".$USER_small,true,'left',false,false);
          }
          //$reporte->SaltoDeLinea();
          $reporte->PrintEnd();
          $reporte->PrintCutPaper();
          return true;
     }

}
?>