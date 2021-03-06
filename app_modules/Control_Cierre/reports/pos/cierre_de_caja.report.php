<?php

/**
 * $Id: cierre_de_caja.report.php,v 1.2 2005/10/27 14:31:04 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de facturapaciente para impresora pos
 */

class cierre_de_caja_report extends pos_reports_class
{
    
    //constructor por default
    function cierre_de_caja_report()
    {
        $this->pos_reports_class();
        return true;
    }
		
				
		
    /**
    *
    */
    function CrearReporte()
    {
        IncludeLib("tarifario");
				include_once("classes/fpdf/conversor.php");
        $reporte=&$this->driver; //obtener el driver
        $datos=&$this->datos; //obtener los datos enviados al reporte.
        $reporte->PrintFTexto($datos[razon_social],true,$align='center',false,true);
        $reporte->PrintFTexto($datos[utilidad],true,$align='center',false,true);
        //reporte->SaltoDeLinea();
        $reporte->PrintFTexto('CAJA: '.$datos[caja],true,'left',false,false);
        //$reporte->PrintFTexto($datos[caja],false,'center',false,false);
        $reporte->PrintFTexto('Nro DE CIERRE: '.$datos[cierre_de_caja_id],true,'left',false,false);
        //$reporte->SaltoDeLinea();
        $reporte->PrintFTexto('USUARIO: '.$datos[usuario_id]."-".$datos[usuario],true,'left',false,false);
				$fecha=explode(' ',$datos[fecha_registro]);
        $reporte->PrintFTexto('FECHA REGISTRO : '.$fecha[0],true,'left',false,false);
        $reporte->SaltoDeLinea();
				$reporte->PrintFTexto('TOTAL EFECTIVO: '.$datos[total_efectivo],true,'left',false,false);                
				$reporte->PrintFTexto('TOTAL CHEQUES: '.$datos[total_cheques],true,'left',false,false);                
				$reporte->PrintFTexto('TOTAL TARJETAS: '.$datos[total_tarjetas],true,'left',false,false);                
				$reporte->PrintFTexto('TOTAL DEVOLUCION: '.$datos[total_devolucion],true,'left',false,false);                
				$reporte->PrintFTexto('TOTAL ENTREGA: '.$datos[entrega_efectivo],true,'left',false,false);                
        $reporte->SaltoDeLinea();        
        $reporte->PrintFTexto('FECHA IMPRESI?N  : '.date('d/m/Y h:i'),false,'left',false,false);
        //$cad1=substr('Atendio: '.$datos[0][usuario_id].' - '.$datos[0][usuario],0,42);
        //$reporte->PrintFTexto('Atendio  : '.$datos[0][usuario_id].' - '.$datos[0][usuario],false,'left',false,false);
        //$reporte->PrintFTexto($cad1,false,'left',false,false);        
        //$reporte->SaltoDeLinea();
        $reporte->PrintEnd();
        //$reporte->OpenCajaMonedera();
        $reporte->PrintCutPaper();
        return true;
    }

}
?>
