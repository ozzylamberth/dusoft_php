<?php

/**
 * $Id: formula_hospitalaria_soluciones_y_medicamentos.inc.php,v 1.3 2007/08/06 14:18:52 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

	function GenerarFormula($datosPaciente, $vectorOriginal, $datosProfesional)
	{
		$Dir="cache/formula_medica_hos.pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$_SESSION['REPORTES']['VARIABLE']='formula_hosp';
		$pdf=new PDF('P','mm','soat');
		//$total=sizeof($datos2);
          //foreach()
          //for($i=0; $i<sizeof($vectorOriginal))
          $total = 1;
		if($total<=2)
		{
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->SetFont('Arial','',8);
			$reporte.=cabecera($datosPaciente);
			$reporte.=cuerpo($vectorOriginal,0,$total);
			$reporte.=final($datosProfesional);
			$pdf->WriteHTML($reporte);
		}
		else
		{
			$totalenter=intval($total/3);
			$totalresid=$total%3;
			for($j=0;$j<$totalenter;$j++)
			{
				$pdf->AliasNbPages();
				$pdf->AddPage();
				$pdf->SetFont('Arial','',8);
				$reporte =cabecera($datosPaciente);
				$inicial=$j*3;
				$reporte.=cuerpo($vectorOriginal,$inicial,3);//datos,numerodemedicamentos,dondeinicia
				$pdf->WriteHTML($reporte);
			}
			if($totalresid==1 OR $totalresid==2)
			{
				$pdf->AliasNbPages();
				$pdf->AddPage();
				$pdf->SetFont('Arial','',8);
				$reporte =cabecera($datosPaciente);
				$inicial=$j*3;
				$reporte.=cuerpo($vectorOriginal,$inicial,$totalresid);
				$reporte.=final($datosProfesional);
				$pdf->WriteHTML($reporte);
			}
			else if($totalresid==0)
			{
				$pdf->AliasNbPages();
				$pdf->AddPage();
				$pdf->SetFont('Arial','',8);
				$reporte =final($datosProfesional);
				$pdf->WriteHTML($reporte);
			}
		}
		$pdf->Output($Dir,'F');
		return True;
	}

	function cabecera($datos)
	{
		$fechaI=FechaStampJT($datos[fecha_nacimiento]);
		$fechaF=FechaStampJT($datos[fecha_cierre]);
		$fechaIngreso=FechaStampJ($datos[fecha_ingreso]);
		$fechaEvolucion=FechaStampJ($datos[fecha_cierre]);
		$edad=CalcularEdad($fechaI,$fechaF);

		$html ="<br><br><br><TABLE BORDER='0' WIDTH='1520'>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>IDENTIFICACION:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$datos['tipo_id_paciente']." ".$datos['paciente_id']."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>HC:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>";
		if($datos[0]['historia_numero']!="")
		{
			if($datos[0]['historia_prefijo']!="")
			{
				$html.= $datos['historia_numero']." - ". $datos['historia_prefijo'];
			}
			else
			{
				$html.= $datos['paciente_id']." - ".$datos['historia_prefijo'];
			}
		}
		else
		{
			$html.= $datos['paciente_id']." - ".$datos['tipo_id_paciente'];
		}
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>PACIENTE:</TD>";
		$nombre = $datos['paciente'];
		$nombre = substr("$nombre", 0, 38);
		$html.="<TD WIDTH='270' HEIGHT=25><b>".strtoupper($nombre).""."</b></TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>No. DE INGRESO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$datos['ingreso']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>EDAD:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$edad['anos'].' AÑOS'."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>SEXO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$datos['sexo_id']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>FECHA INGRESO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$fechaIngreso."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>FECHA SOLICITUD:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$fechaEvolucion."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>RANGO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($datos['rango'])."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>TIPO DE AFILIADO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($datos['tipo_afiliado_nombre'])."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>CLIENTE:</TD>";
		$cliente = $datos['cliente'];
		$cliente = substr("$cliente", 0, 38);
		$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($cliente)."."."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>PLAN:</TD>";
		$plan = $datos['plan_descripcion'];
		$plan = substr("$plan", 0, 38);
		$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($plan)."."."</TD>";
		$html.="</TR>";
		if ($datos[uso_controlado]==1)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='110' HEIGHT=25>DIRECCION.:</TD>";
			$dir = $datos['residencia_direccion'];
			$dir = substr("$dir", 0, 38);
			$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($dir)."."."</TD>";
			$html.="<TD WIDTH='110' HEIGHT=25>TELEFONO.:</TD>";
			$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($datos['residencia_telefono'])."</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='110' HEIGHT=25>DEPARTAMENTO.:</TD>";
			$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper( $datos['departamento'])."."."</TD>";
			$html.="<TD WIDTH='110' HEIGHT=25>MUNICIPIO.:</TD>";
			$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($datos['municipio'])."</TD>";
			$html.="</TR>";
		}
		return $html;
	}

	function cuerpo($datos,$inicio,$cuantos)
	{
		$titulo = 'FORMULA MEDICA';
		/*if ($datos[0]['uso_controlado']==1)
		{
			$subtitulo = 'MEDICAMENTO(S) DE USO CONTROLADO.';
		}
		else
		{*/
			$subtitulo = 'MEDICAMENTO(S) Y/O SOLUCION(ES) FORMULADO(S) DE CONTROL ESPECIAL.';
               /*if($datos[0]['item']=='POS')
			{
				$subtitulo = 'MEDICAMENTO(S) POS FORMULADO(S).';
			}
			if($datos[0]['item']=='NO POS' AND $datos[0]['sw_paciente_no_pos']=='1')
			{
				$subtitulo = 'MEDICAMENTO(S) NO POS SOLICITADO(S) A PETICI?N DEL PACIENTE.';
			}
			elseif($datos[0]['item']=='NO POS' AND $datos[0]['sw_paciente_no_pos']=='0')
			{
				$subtitulo = 'MEDICAMENTO(S) NO POS JUSTIFICADO(S).';
			}*/
		//}
          
          $html ="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25><br>";
		$html.="<b>".$titulo."".' : '."".$subtitulo."</b>";
		$html.="</TD>";
		$html.="</TR>";
		//$limite=$inicio+$cuantos;
		//for($i=$inicio;($i<$limite);$i++)
		//{
			$html .= Pintar_FormulacionConsultada($datos, 'hosp');
		//}               
		return $html;
	}
     
     /*
     * Forma que permite dibujar la consulta de los medicamentos.
     *
     * @autor Tizziano Perea
     */
	function Pintar_FormulacionConsultada($vectorOriginal, $tipo_formulacion)
     {
          foreach($vectorOriginal as $k => $vector1)
          {
               for($i=0;$i<sizeof($vector1);$i++)
               {
                    $salida.="<tr>";
                    
                    if($vector1[$i]['tipo_solicitud'] == "M")
                    { 
                    	$salida.="<td colspan=\"3\" width=\"300\" height=\"25\"><b>".$vector1[$i]['producto']." - ( ".$vector1[$i]['codigo_producto']." - ";
                         if(empty($vector1[$i]['codigo_pos']))
                         {
                         	$salida.="".$vector1[$i]['item']." )";
                         }else{
                         	$salida.="".$vector1[$i]['codigo_pos']." )";
                         }
                         $salida.="</b></td>";
                    }
                    else
                    {
                         if($vector1[$i]['num_mezcla'] != $vector1[$i-1]['num_mezcla'])
                         {
                              $salida.="<td colspan=\"3\" width=\"300\" height=\"10\"><b>";
                              for($j=0; $j<sizeof($vector1); $j++)
                              {
                                   if($vector1[$i]['num_mezcla'] == $vector1[$j]['num_mezcla'])
                                   {
                                        $salida.="".$vector1[$j]['producto']." - ( ".$vector1[$j]['codigo_producto']." - <label class=\"label_mark\">".$vector1[$j]['dosis']." ".$vector1[$j]['unidad_suministro']."</label>)<br>";
                                   }
                              }
                              $salida.="</b></td>";
                         }
                    }
                    
                    $salida.="</tr>";
    
                    if($vector1[$i]['tipo_solicitud'] == "M")
                    {
                         $salida.="<tr>";
                         $salida.="<td colspan=\"6\" width=\"760\" height=\"25\">";
                         $salida.="<table width=\"760\">";
                         
                         $salida.="<tr>";
                         $salida.="<td colspan = 3 align=\"left\" width=\"300\" height=\"25\">Via de Administracion:     ".$vector1[$i][via]."</td>";
                         $salida.="</tr>";
     
                         $salida.="<tr>";
                         $salida.="<td align=\"left\" width=\"300\" height=\"25\">Dosis:</td>";
                         $e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
                         if($e==1)
                         {
                              $salida.="  <td align=\"left\" width=\"100\" height=\"25\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                         }
                         else
                         {
                              $salida.="  <td align=\"left\" width=\"100\" height=\"25\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                         }
                         
                         $salida.="<td align=\"left\" width=\"150\" height=\"25\">".$vector1[$i][frecuencia]."</td>";                         

                         $salida.="</tr>";
          
                         $salida.="<tr>";
                         $salida.="<td align=\"left\" width=\"300\" height=\"25\">Cantidad:</td>";
                         $e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
                         if($vector1[$i][contenido_unidad_venta])
                         {
                              if($e==1)
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\" width=\"200\" height=\"25\">".floor($vector1[$i][cantidad])." ".$vector1[$i][unidad]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                              }
                              else
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\" width=\"200\" height=\"25\">".$vector1[$i][cantidad]." ".$vector1[$i][unidad]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                              }
                         }
                         else
                         {
                              if($e==1)
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\" width=\"200\" height=\"25\">".floor($vector1[$i][cantidad])." ".$vector1[$i][unidad]."</td>";
                              }
                              else
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\" width=\"200\" height=\"25\">".$vector1[$i][cantidad]." ".$vector1[$i][unidad]."</td>";
                              }
                         }
                         $salida.="</tr>";
                         if($vector1[$i][observacion] != "")
                         {
                              $salida.="<tr>";
                              $salida.="  <td align=\"left\" width=\"300\" height=\"25\">Observación:</td>";
                              $salida.="  <td align=\"left\" colspan=\"2\" width=\"400\" height=\"25\">".$vector1[$i][observacion]."</td>";
                              $salida.="</tr>";
                         }
                         $salida.="</table>";
                         $salida.="</td>";
                         $salida.="</tr>";
     
                    }else
                    {
                         if($vector1[$i]['num_mezcla'] != $vector1[$i-1]['num_mezcla'])
                         {
                              $salida.="<tr>";
                              $salida.="<td colspan=\"6\" width=\"760\" height=\"1\">";
                              $salida.="<table>";

                              $salida.="<tr>";
                              $salida.="  <td align=\"left\" width=\"300\" height=\"25\">Cantidad Total:</td>";
                              $salida.="  <td align=\"left\" colspan=\"2\" width=\"200\" height=\"25\">".floor($vector1[$i][cantidad])." SOLUCION(ES)</td>";
                              $salida.="</tr>";
                              
                              $salida.="<tr>";
                              $salida.="  <td align=\"left\" width=\"300\" height=\"25\">Volumen de Infusión:</td>";
                              $salida.="  <td align=\"left\" colspan=\"2\" width=\"200\" height=\"25\">".floor($vector1[$i][volumen_infusion])." ".strtoupper($vector1[$i][unidad_volumen])."</td>";
                              $salida.="</tr>";
                         
                              if($vector1[$i][observacion] != "")
                              {
                                   $salida.="<tr>";
                                   $salida.="  <td align=\"left\" width=\"300\" height=\"25\">Observación:</td>";
                                   $salida.="  <td align=\"left\" colspan=\"2\" width=\"200\" height=\"25\">".$vector1[$i][observacion]."</td>";
                                   $salida.="</tr>";
                              }
        
                              $salida.="</table>";
		                    $salida.="</td>";
                              $salida.="</tr>";
                         }
                    }
               } //fin del for muy importante
          }
     	return $salida;
     }


	function final($datos)
	{
		$html ="<TR>";
		$html.="<TD WIDTH='380' HEIGHT=25><br>MEDICO TRATANTE:</TD>";
		/*if(!empty($datos[cuota_moderadora][cuota_moderadora]))
		{
			$html.="<TD WIDTH='380' HEIGHT=25>".'CUOTA MODERADORA:'.$datos['cuota_moderadora']['cuota_moderadora']."</TD>";
		}*/
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'>";
		$html.="<br>________________________________________________";
		$html.="</TD>";
		$html.="</TR>";
		if($datos[tarjeta_profesional] != '')
		{
			$html.="<TR>";
			$html.="<TD WIDTH='760' HEIGHT=25>".strtoupper($datos[nombre_tercero])."<br>".$datos[tipo_id_medico].': '.$datos[medico_id].' - T.P.: '.$datos[tarjeta_profesional].' - '.$datos[tipo_profesional]."</TD>";
			$html.="</TR>";
		}
		else
		{
			$html.="<TR>";
			$html.="<TD WIDTH='760' HEIGHT=25>".strtoupper($datos[nombre_tercero])."<br>".$datos[tipo_id_medico].': '.$datos[medico_id]."</TD>";
			$html.="</TR>";
		}
		$html.="</TABLE>";
		return $html;
	}

	function FechaStampJ($fecha)
	{
		if($fecha)
		{
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}
			return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
		}
	}

	function FechaStampJT($fecha)
	{
		if($fecha)
		{
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}
			return  ceil($date[0])."/".ceil($date[1])."/".ceil($date[2]);
		}
	}

?>
