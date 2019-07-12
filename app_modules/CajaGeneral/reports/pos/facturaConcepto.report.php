<?php

/**
 * $Id: facturaConcepto.report.php,v 1.5 2010/11/18 14:18:05 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de facturaConceptopaciente para impresora pos
 */

class facturaConcepto_report extends pos_reports_class
{
    
    //constructor por default
    function facturaConcepto_report()
    {
        $this->pos_reports_class();
        return true;
    }
		
    /**
    *
    */
    function CrearReporte()
    {
        $reporte=&$this->driver; //obtener el driver
        $datos=&$this->datos; //obtener los datos enviados al reporte.
        $reporte->PrintFTexto($datos[0][razon_social],true,$align='center',false,true);
        $reporte->PrintFTexto($datos[0][tipoid].' '.$datos[0][id],false,'center',false,false);
        $reporte->PrintFTexto($datos[0][direccion].' '.$datos[0][municipio].' - '.$datos[0][departamento],false,'center',false,false);
        $reporte->SaltoDeLinea();
        $reporte->PrintFTexto('FACTURA CAMBIARIA DE COMPRAVENTA',true,'center',false,false);
        $reporte->PrintFTexto('No. '.$datos[0][prefijo]."".$datos[0][factura_fiscal],true,'center',false,false);        
        $reporte->SaltoDeLinea();
				$reporte->PrintFTexto($datos[0][texto1],true,'center',false,false);                
        $reporte->SaltoDeLinea();        
        $reporte->PrintFTexto('Fecha  : '.date('d/m/Y h:i'),false,'left',false,false);
        $cad1=substr('Atendio: '.$datos[0][usuario_id].' - '.$datos[0][nombre],0,42);
        $reporte->PrintFTexto($cad1,false,'left',false,false);        
        $reporte->SaltoDeLinea();
				$reporte->PrintFTexto('Identifi: '.$datos[0][tipotercero].' '.$datos[0][tercero_id],false,'left',false,false);  				
				$reporte->PrintFTexto('Cliente : '.$datos[0][nombre_tercero],true,'left',false,false);  				
        $total=0;
				$moderadora=$copago=$nocub=0;
     		$reporte->SaltoDeLinea();	
        $reporte->PrintFTextoValor('DETALLE','VALOR',0,false,11,true,'left');  
        for($i=1; $i<sizeof($datos); $i++)
        {        
						$reporte->PrintFTextoValor($datos[$i][descripcion],$datos[$i][valor_total],0,true,11,false,'left');  											
        }//fin for
//VALORES DEL IVA
				$reporte->SaltoDeLinea();
				$reporte->PrintFTextoValor('%IVA','',0,false,11,true,'left');  
				$j=0;
				for($i=1; $i<sizeof($datos); )
				{     
					$j=$i;
					while($datos[$i][porcentaje_gravamen]==$datos[$j][porcentaje_gravamen])   
					{
						$total_iva=$datos[$j][valor_gravamen];
						$j++;
					}
					$iva=$datos[$j-1][porcentaje_gravamen];
					$i=$j;
					if($iva>0)
					{
						$reporte->PrintFTextoValor($iva,$total_iva,0,true,11,true,'left');
					}
				}//fin for
//FIN
        $reporte->SaltoDeLinea();
        $reporte->PrintFTextoValor('TOTAL',$datos[0][total_factura],0,true,11,true,'right');
				if(!empty($datos[0][texto2]))
        {
            $reporte->SaltoDeLinea();
            $reporte->SaltoDeLinea();        
            $reporte->PrintFTexto($datos[1][texto2],true,'center',false,false);          
        }
        if(!empty($datos[0][mensaje]))
        {
            $reporte->SaltoDeLinea();
            $reporte->SaltoDeLinea();        
            $reporte->PrintFTexto($datos[1][mensaje],true,'center',false,false);          
        }        
				$reporte->SaltoDeLinea();
				$reporte->SaltoDeLinea();
				$msj='Esta factura cambiaria de compraventa se asimila para todos sus efectos legales a la Letra de Cambio (artículo 621 – 774 del Código de Comercio), el comprador acepta que la firma que aparece como recibido está avalando la firma del mismo.';        
				$reporte->PrintFTexto($msj,true,'center',false,false);          
        $reporte->PrintEnd();
        //$reporte->OpenCajaMonedera();
        $reporte->PrintCutPaper();
        return true;
    }

}
?>
