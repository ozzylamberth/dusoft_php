<?php

/**
 * $Id: factura.report.php,v 1.5 2010/11/18 14:18:05 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de facturapaciente para impresora pos
 */

class factura_report extends pos_reports_class
{
    
    //constructor por default
    function factura_report()
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
		
			if($datos[1][sw_tipo]!=1)
			{
        $reporte->PrintFTexto($datos[0][razon_social],true,$align='center',false,true);
        //reporte->SaltoDeLinea();
        $reporte->PrintFTexto($datos[0][tipo_id_tercero].' '.$datos[0][id],false,'center',false,false);
        $reporte->PrintFTexto($datos[0][direccion].' '.$datos[0][municipio].' - '.$datos[0][departamento],false,'center',false,false);
        $reporte->SaltoDeLinea();
        $reporte->PrintFTexto('RECIBO DE CAJA',true,'center',false,false);
        $reporte->PrintFTexto('No. '.$datos[1][prefijo]."-".$datos[1][factura_fiscal],true,'center',false,false);        
        $reporte->SaltoDeLinea();
				$reporte->PrintFTexto($datos[1][texto1],true,'center',false,false);                
        $reporte->SaltoDeLinea();        
        $reporte->PrintFTexto('Fecha  : '.date('d/m/Y h:i'),false,'left',false,false);
        $cad1=substr('Atendio: '.$datos[0][usuario_id].' - '.$datos[0][usuario],0,42);
        //$reporte->PrintFTexto('Atendio  : '.$datos[0][usuario_id].' - '.$datos[0][usuario],false,'left',false,false);
        $reporte->PrintFTexto($cad1,false,'left',false,false);        
        $reporte->SaltoDeLinea();
        $reporte->PrintFTexto('Identifi: '.$datos[0][tipo_id_paciente].' '.$datos[0][paciente_id],false,'left',false,false);
        $reporte->PrintFTexto('Paciente: '.$datos[0][nombre],false,'left',false,false);
				//la factura para el paciente
				if($datos[1][sw_tipo]==0 OR $datos[1][sw_tipo]==2)
        {  
  					$reporte->PrintFTexto('Cliente : '.$datos[0][nombre],true,'left',false,false); 				
						$reporte->PrintFTexto('Entidad : '.$datos[0][nombre_tercero],false,'left',false,false);  				
				}//fac para la entidad
				elseif($datos[1][sw_tipo]==1)
        {  
						$reporte->PrintFTexto('Cliente : '.$datos[0][nombre_tercero],true,'left',false,false);  				
				}
				$reporte->PrintFTexto('Plan    : '.$datos[0][plan_descripcion],false,'left',false,false);
        $reporte->PrintFTexto('Tipo Afi: '.$datos[0][tipo_afiliado_nombre].'     Rango: '.$datos[0][rango],false,'left',false,false);
        $total=0;
				$moderadora=$copago=$nocub=0;
     		$reporte->SaltoDeLinea();	
				//factura paciente		
				if($datos[1][sw_tipo]==0)
        {  $reporte->PrintFTextoValor('DETALLE','',0,false,11,true,'left');   }
				else//factura cliente
        {  $reporte->PrintFTextoValor('DETALLE','VALOR',0,false,11,true,'left');  }
        //$reporte->SaltoDeLinea();
        for($i=1; $i<sizeof($datos);)
        {
            $x=$i;          
            $reporte->PrintFTexto($datos[$i][descripcion],true,'left',false,false);
            while($datos[$i][cargo]==$datos[$x][cargo]
              AND $datos[$i][tarifario_id]==$datos[$x][tarifario_id])
            {
				if($datos[$x][transaccion]<>$datos[$x-1][transaccion])
				{
									//factura cliente
									if($datos[1][sw_tipo]==1)
									{
											$reporte->PrintFTextoValor($datos[$x][desccargo],$datos[$x][precio],0,true,11,false,'left');  											
									}
									else
									{   //factura paciente	
											$reporte->PrintFTexto($datos[$x][desccargo],false,'left',false,false);
									}
				}					
                  $x++;
            }//fin while principal
            $i=$x;
        }//fin for
        $reporte->SaltoDeLinea();
				//factura paciente
				if($datos[1][sw_tipo]==0)
				{
						if($datos[1][valor_cuota_paciente]>0 AND $datos[1][sw_cuota_moderadora]=='1')
						{   $reporte->PrintFTextoValor($datos[0][nombre_copago],$datos[1][valor_cuota_paciente],0,true,11,false,'left');  }
						if($datos[1][valor_cuota_moderadora]>0 AND $datos[1][sw_cuota_moderadora]=='1') 
						{  $reporte->PrintFTextoValor($datos[0][nombre_cuota_moderadora],$datos[1][valor_cuota_moderadora],0,true,11,false,'left');  }
						if($datos[1][valor_cargos]>0 AND $datos[1][sw_cuota_moderadora]=='2')
						{  $reporte->PrintFTextoValor('Valor no Cubierto',$datos[1][valor_cargos],0,true,11,false,'left');  }
						//$total=$datos[0][valor_total_paciente];
				}//factura cliente
				elseif($datos[1][sw_tipo]==1)
				{
						$reporte->PrintFTextoValor('Valor  Cubierto',$datos[1][valor_cargo],0,true,11,false,'left');  
						//$total=$datos[0][valor_total_empresa];
				}				
				if($datos[1][gravamen] > 0)
				{
						$reporte->PrintFTextoValor('IVA',$datos[1][gravamen],0,true,11,false,'right');					
				}				
				/*if($datos[0][valor_descuento_paciente] > 0)
				{
						$reporte->PrintFTextoValor('Descuento',$datos[0][valor_descuento_paciente],0,true,11,false,'right');					
				}*/
        $reporte->PrintFTextoValor('TOTAL CUENTA',$datos[1][total_factura],0,true,11,true,'right');
				if($datos[1][descuento]>0)
				{
					$totalpaciente=$datos[1][total_factura]-$datos[1][descuento];
					$reporte->PrintFTextoValor('TOTAL DESCUENTO',$datos[1][descuento],0,true,11,true,'right');
					$reporte->PrintFTextoValor('TOTAL A PAGAR',$totalpaciente,0,true,11,true,'right');
				}
				else
				{
					$reporte->PrintFTextoValor('TOTAL A PAGAR',$datos[1][total_factura],0,true,11,true,'right');
				}
        if(!empty($datos[0][texto2]))
        {
            $reporte->SaltoDeLinea();
            $reporte->SaltoDeLinea();        
            $reporte->PrintFTexto($datos[1][texto2],true,'center',false,false);          
        }
        if(!empty($datos[1][mensaje]))
        {
            $reporte->SaltoDeLinea();
            $reporte->SaltoDeLinea();        
            $reporte->PrintFTexto($datos[1][mensaje],true,'center',false,false);          
        }        
				$reporte->SaltoDeLinea();
				$reporte->SaltoDeLinea();
				/*$msj='Esta factura cambiaria de compraventa se asimila para todos sus efectos legales a la Letra de Cambio (artÃ­culo 621 â€“ 774 del CÃ³digo de Comercio), el comprador acepta que la firma que aparece como recibido estÃ¡ avalando la firma del mismo.';        
				$reporte->PrintFTexto($msj,true,'center',false,false);          
				$msj='No efectuar Retención de Industria y Comercio por los pagos provenientes del Sistema Integral de Seguridad Social en Salud Art. 111 Ley 788/2002; Art.5 Decreto Municipal 295/2002.';        
				$reporte->PrintFTexto($msj,true,'center',false,false);          
				$msj='Código CIIU 307-08 tarifa 6.6 X 1000 por conceptos diferentes a los del numeral 1.';        
				$reporte->PrintFTexto($msj,true,'center',false,false);          
				$msj='Efectuar Retención en la Fuente a titulo de Renta del 2% Art.75 Ley 1111/2006.';        
				$reporte->PrintFTexto($msj,true,'center',false,false);*/          
        $reporte->PrintEnd();
        //$reporte->OpenCajaMonedera();
        $reporte->PrintCutPaper();

        return true;
			}
			else
			{
        $reporte->PrintCutPaper();
        return true;
			}
    }

}
?>
