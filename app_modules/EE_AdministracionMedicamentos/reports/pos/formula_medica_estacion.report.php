<?php

/**
 * $Id: formula_medica_estacion.report.php,v 1.2 2011/04/26 15:14:17 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de Formulacion de medicamentos pàra la impresora POS
 */

//este reporte es usado desde la central de impresion de hospitalizacion
//segun la orden se puede generar cuatro tipos distintos de
//formulas (pos, no pos justificados , no pos a peticion del paciente y de uso controlado)

class formula_medica_estacion_report extends pos_reports_class
{
    //constructor por default
    function formula_medica_estacion_report()
    {
        $this->pos_reports_class();
        return true;
    }

    function CrearReporte()
    {
          IncludeLib("tarifario");
          $reporte=&$this->driver; //obtener el driver
          $datos=&$this->datos;    //obtener los datos enviados al reporte.

          $reporte->PrintFTexto($datos[0][razon_social],true,$align='center',false,true);
          $reporte->PrintFTexto($datos[0][tipo_empresa].' '.$datos[0][id],false,'center',false,false);
          $reporte->SaltoDeLinea();
          if ($datos[0][uso_controlado]==1)
          {
          	$reporte->PrintFTexto('FORMULA MEDICA PARA DESPACHO DE',true,$align='center',false,true);
               $reporte->PrintFTexto('MEDICAMENTOS DE USO CONTROLADO',true,$align='center',false,true);
          }
          else
          {
          	$reporte->PrintFTexto('FORMULA MEDICA',true,$align='center',false,true);
          }
          $reporte->SaltoDeLinea();

          $reporte->PrintFTexto('No. Ingreso: '.$datos[0][ingreso],false,'left',false,false);
          $reporte->PrintFTexto('Fecha Impr : '.date('d/m/Y h:i'),false,'left',false,false);
          $reporte->PrintFTexto('Identifi   : '.$datos[0][tipo_id_paciente].' '.$datos[0][paciente_id],false,'left',false,false);
          $reporte->PrintFTexto('Paciente   : '.$datos[0][paciente],false,'left',false,false);
          $reporte->PrintFTexto('Cliente    : '.$datos[0][cliente],false,'left',false,false);
          $reporte->PrintFTexto('Plan       : '.$datos[0][plan_descripcion],false,'left',false,false);
          $reporte->PrintFTexto('Tipo Afi   : '.$datos[0][tipo_afiliado_nombre].'     Rango: '.$datos[0][rango],false,'left',false,false);
		$edad=CalcularEdad($datos[0][fecha_nacimiento],$datos[0][fecha_cierre]);
          $reporte->PrintFTexto('Edad       : '.$edad['anos'].' Años',false,'left',false,false);
		$reporte->PrintFTexto('Sexo       : '.$datos[0][sexo_id],false,'left',false,false);
          
          //Se imprime si es Medicamento de consumo directo.
          $reporte->PrintFTexto('Dir. Res.  : '.$datos[0][residencia_direccion],false,'left',false,false);
          $reporte->PrintFTexto('Tel. Res.  : '.$datos[0][residencia_telefono],false,'left',false,false);

          //Diagnosticos
          /*$reporte->SaltoDeLinea();
          $reporte->PrintFTexto('DIAGNOSTICOS',true,'center',false,false);
          */
          
		$reporte->SaltoDeLinea();
          $subtitulo = 'MEDICAMENTO(S) Y/O SOLUCION(ES).';
          
          $reporte->PrintFTexto($subtitulo,true,'center',false,false);

          //Armado del vector de Medicamentos
          foreach($datos[1] as $k => $Vector)
          {  
          	for($j=0; $j<sizeof($Vector); $j++)
               {
               	$Vmedicamentos[] = $Vector[$j];
               }
          }
          
          
          for($i=0; $i<sizeof($Vmedicamentos);$i++)
          {
               if($Vmedicamentos[$i][tipo_solicitud] == "M")
               {
          		$reporte->PrintFTexto(($i+1).'. '.$Vmedicamentos[$i][producto],true,'left',false,false);               
               }else{
                    if($Vmedicamentos[$i][num_mezcla] != $Vmedicamentos[$i-1][num_mezcla])
                    {
                         $Ind = $i+1;
                         for($j=0; $j<sizeof($Vmedicamentos);$j++)
                         {
                              if($Vmedicamentos[$i][num_mezcla] == $Vmedicamentos[$j][num_mezcla])
                              {
                         		$reporte->PrintFTexto(($Ind).'. '.$Vmedicamentos[$j][producto].' ('.$Vmedicamentos[$j][dosis].' '.$Vmedicamentos[$j][unidad_suministro].')',true,'left',false,false);
                                   $Ind++;
                              }
                         }
                    }
               }

               if ($Vmedicamentos[$i][via_administracion]!='')
               {
                    $reporte->PrintFTexto('Via de Administracion: '.$Vmedicamentos[$i][via_administracion],false,'left',false,false);
               }

               if($Vmedicamentos[$i][tipo_solicitud] == "M")
               {
				$reporte->PrintFTexto('Dosis : '.$Vmedicamentos[$i][dosis].' '.$Vmedicamentos[$i][unidad_dosificacion],false,'left',false,false);
                    
				$reporte->PrintFTexto($Vmedicamentos[$i][frecuencia],false,'left',false,false);                    
                    
				$reporte->PrintFTexto('Cantidad : '.$Vmedicamentos[$i][cantidad].' '.$Vmedicamentos[$i][unidad],false,'left',false,false);                    
				
                    if($Vmedicamentos[$i][observacion])
                    {
                         $reporte->PrintFTexto('Observacion : '.$Vmedicamentos[$i][observacion],false,'left',false,false);                    
               	}
                    
               }else{
               	if($Vmedicamentos[$i][num_mezcla] != $Vmedicamentos[$i-1][num_mezcla])
                    {
                         $reporte->PrintFTexto('Cantidad Total   : '.$Vmedicamentos[$i][cantidad].' Solucion(ES)',false,'left',false,false);
                                        
                         $reporte->PrintFTexto('Volumen Infusion : '.$Vmedicamentos[$i][volumen_infusion].' '.$Vmedicamentos[$i][unidad_volumen],false,'left',false,false);
                         
                         if($Vmedicamentos[$i][observacion])
                         {
                              $reporte->PrintFTexto('Observacion : '.$Vmedicamentos[$i][observacion],false,'left',false,false);
                         }
                    }
               }
               
               $reporte->SaltoDeLinea();
          }

          $reporte->SaltoDeLinea(2);
          $reporte->PrintFTexto('USUARIO:',true,$align='left',false,false);
          $reporte->SaltoDeLinea(4);
          $reporte->PrintFTexto('--------------------------------',true,$align='left',false,false);

          //medico de la ultima evolucion cerrada del ingreso - caso especial para hospitalizacion
          $reporte->PrintFTexto($datos[2][nombre_tercero],false,'left',false,false);
          if($datos[2][tarjeta_profesional] != '')
          {
               $reporte->PrintFTexto($datos[2][tipo_id_medico].': '.$datos[2][medico_id].' T.P.: '.$datos[2][tarjeta_profesional],false,'left',false,false);
          }
          else
          {
               $reporte->PrintFTexto($datos[2][tipo_id_medico].': '.$datos[2][medico_id],false,'left',false,false);
          }
          $reporte->PrintFTexto($datos[2][tipo_profesional],false,'left',false,false);
                    
          $reporte->PrintEnd();
          $reporte->OpenCajaMonedera();
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

