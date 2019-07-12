<?php

/**
 * $Id: DispensacionPosPendientes.report.php,v 1.0 2010/07/08 
 * @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de Medicamentos Pendientes por Entregar (p�ra la impresora POS)
 */
	class DispensacionPosPendientes_report extends pos_reports_class
	{
		/*constructor por default*/
		function DispensacionPosPendientes_report()
		{
			$this->pos_reports_class();
			return true;
		}

		function CrearReporte()
		{
			$reporte=&$this->driver; //obtener el driver
			$datos=&$this->datos;    //obtener los datos enviados al reporte.
			$reporte->PrintFTexto($datos[0]['descripcion1'],true,$align='center',false,true);
			$reporte->PrintFTexto($datos[0]['descripcion2'],false,'center',false,false);
			$reporte->SaltoDeLinea();
        			
			$reporte->PrintFTexto('MEDICAMENTO(S) PENDIENTE(S)',true,$align='center',false,true);
        
			$reporte->SaltoDeLinea();

			$reporte->PrintFTexto('Fecha Impr : '.date('d/m/Y h:i'),false,'left',false,false);
			$reporte->PrintFTexto('Identifi   : '.$datos[1][0]['tipo_id_paciente'].' '.$datos[1][0]['paciente_id'],false,'left',false,false);
			$reporte->PrintFTexto('Paciente   : '.$datos[1][0]['primer_apellido'].' '.$datos[1][0]['segundo_apellido'].' '.$datos[1][0]['paciente_id'].' '.$datos[1][0]['primer_nombre'].' '.$datos[1][0]['segundo_nombre'],false,'left',false,false);
			$reporte->PrintFTexto('Edad       : '.$datos[1][0]['edad'],false,'left',false,false);
			$reporte->PrintFTexto('Sexo       : '.$datos[1][0]['sexo_id'],false,'left',false,false);
         
			$reporte->PrintFTexto('Dir. Res.  : '.$datos[1][0]['residencia_direccion'],false,'left',false,false);
			$reporte->PrintFTexto('Tel. Res.  : '.$datos[1][0]['residencia_telefono'],false,'left',false,false);
	      
			$reporte->SaltoDeLinea();
			$subtitulo = 'MEDICAMENTO(S).';
          
			$reporte->PrintFTexto($subtitulo,true,'center',false,false);
	
			foreach($datos[2] as $k => $Vector)
			{	  
				for($j=0; $j<sizeof($Vector); $j++)
               {
					$Mpendiente[] = $Vector[$j];
               }
			}
                   
			for($i=0; $i<sizeof($Mpendiente);$i++)
			{
		
            	$reporte->PrintFTexto('Codigo : '.$Mpendiente[$i]['codigo_medicamento'],false,'left',false,false);
                $reporte->PrintFTexto('Medicamento Formulado : '.$Mpendiente[$i]['nombre_medicamento'].' '.$Mpendiente[$i]['contenido_unidad_venta'].' '.$Mpendiente[$i]['unidad'],false,'left',false,false);
            	$reporte->PrintFTexto('Cantidad : '.$Mpendiente[$i]['cantidad_acomulada'].' '.$Mpendiente[$i]['unidades'],false,'left',false,false);                    
			    $reporte->SaltoDeLinea();
			}
   
			$reporte->SaltoDeLinea(2);
			$reporte->PrintFTexto('PACIENTE:',true,$align='left',false,false);
			$reporte->SaltoDeLinea(4);
			$reporte->PrintFTexto('--------------------------------',true,$align='left',false,false);

			$reporte->PrintEnd();
			//$reporte->OpenCajaMonedera();
			$reporte->PrintCutPaper();
			return true;
		}	

		function FechaStamp($fecha)
		{
          if($fecha){
	          $fech = strtok ($fecha,"-");
     	     for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
          }
		}
	}
?>