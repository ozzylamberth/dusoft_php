<?php

/**
 * $Id: formula_ambulatoria.inc.php,v 1.24 2006/03/30 16:29:59 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el reporte en media carta para la formula medica ambulatoria
 */

     function GenerarFormula($datos)
     {
          IncludeLib("tarifario");
          $Dir="cache/formula_medica_amb".UserGetUID().".pdf";
          require("classes/fpdf/html_class.php");
          define('FPDF_FONTPATH','font/');
          $_SESSION['REPORTES']['VARIABLE']='formulacio_amb';
          $_SESSION['FORMULA_AMB']['DATOS']=$datos[0];
          $pdf=new PDF('P','mm','soat');
          $pdf->AliasNbPages();
          $pdf->AddPage();
		
          $pdf->SetFont('Arial','',7);
		if ($datos[0][uso_controlado]==1)
		{
			$subtitulo = 'MEDICAMENTO(S) DE USO CONTROLADO';
		}
		else
		{
			if($datos[0][item]=='POS')
			{
				$subtitulo = 'MEDICAMENTO(S) POS FORMULADO(S)<BR>Medicamentos esenciales en presentación genérica, según Acuerdo 083/97-CNSSS.';
			}

			if($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='1')
			{
				$subtitulo = 'MEDICAMENTO(S) NO POS SOLICITADO(S) A PETICION DEL PACIENTE';
			}
			elseif($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='0')
			{
				$subtitulo = 'MEDICAMENTO(S) NO POS JUSTIFICADO(S)';
			}
		}
		$html.="<TABLE BORDER='0' WIDTH='1520'>";
		$html.="<TR><TD ALIGN='CENTER' HEIGHT=22 WIDTH='720'><b>".$titulo."  ".$subtitulo."</b></TD></TR>";
		$html.="</table>";

		$html.="<TABLE BORDER='1' WIDTH='1520'>";
		$html.="<TR>";
		$pdf->WriteHTML($html);
		$html='';	
		$pdf->SetFont('Arial','',6);		
		$html.="<TD WIDTH='360' HEIGHT='22' ALIGN='RIGHT'>&nbsp;</TD><TD WIDTH='210' HEIGHT='22' ALIGN='CENTER'>Cantidad Solicitada</TD><TD HEIGHT=22 WIDTH='65' ALIGN='CENTER'><B>Cant.Entregada</B></TD><TD HEIGHT=22 WIDTH='81' ALIGN='CENTER'><B>Valor Unitario</B></TD><TD HEIGHT=22 WIDTH='73' ALIGN='CENTER'><B>Valor Subtotal</B></TD>";
		$html.="</TR>";

		for($i=0; $i<sizeof($datos);$i++)
		{
			$pdf->WriteHTML($html);
			$html='';		
			$pdf->SetFont('Arial','',8);		
			$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'><B>".($i+1).'. '.strtoupper($datos[$i][producto])."</B></TD>";
			$pdf->WriteHTML($html);
			$html='';
			$pdf->SetFont('Arial','',6);
			//pintar cantidad
			$e=$datos[$i][cantidad]/floor($datos[$i][cantidad]);
			if ($datos[$i][contenido_unidad_venta])
			{
				if($e==1)
				{
					$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='210'>".'Cant. Total: '.floor($datos[$i][cantidad]).' '.$datos[$i][descripcion].' por '.$datos[$i][contenido_unidad_venta]."</TD>";
				}
				else
				{
					$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='210'>".'Cant. Total: '.$datos[$i][cantidad].' '.$datos[$i][descripcion].' por '.$datos[$i][contenido_unidad_venta]."</TD>";
				}
			}
			else
			{
				if($e==1)
				{
					$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='210'>".'Cant. Total: '.floor($datos[$i][cantidad]).' '.$datos[$i][descripcion]."</TD>";
					
				}
				else
				{
          			$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='210'>".'Cant. Total: '.$datos[$i][cantidad].' '.$datos[$i][descripcion]."</TD>";
				}
			}
			$html.="<TD HEIGHT=30 WIDTH='65' ALIGN='LEFT'>&nbsp;</TD><TD HEIGHT=30 WIDTH='81' ALIGN='LEFT'>&nbsp;</TD><TD HEIGHT=30 WIDTH='73' ALIGN='LEFT'>&nbsp;</TD>";
			
			$html.="</TR>";

		      $posologia = '';
			//pintar formula para opcion 1 //caso ok
			if($datos[$i][tipo_opcion_posologia_id]== 1)
			{
				//$html.="<td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"3\">cada ".$datos[$i][posologia][0][periocidad_id]." ".$datos[$i][posologia][0][tiempo]."</td></tr>";
				$posologia = 'cada '.$datos[$i][posologia][0][periocidad_id].' '.$datos[$i][posologia][0][tiempo];
			}

               //pintar formula para opcion 2 //caso ok
			if($datos[$i][tipo_opcion_posologia_id]== 2)
			{
				//$html.="<td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"3\">".$datos[$i][posologia][0][descripcion]."</td></tr>";
        			$posologia = $datos[$i][posologia][0][descripcion];
			}

               //pintar formula para opcion 3  //caso ok
			if($datos[$i][tipo_opcion_posologia_id]== 3)
			{
				$momento = '';
				if($datos[$i][posologia][0][sw_estado_momento]== '1')
				{
					$momento = 'antes de ';
				}
				else
				{
					if($datos[$i][posologia][0][sw_estado_momento]== '2')
					{
						$momento = 'durante ';
					}
					else
					{
						if($datos[$i][posologia][0][sw_estado_momento]== '3')
							{
								$momento = 'despues de ';
							}
					}
				}
				$Cen = $Alm = $Des= '';
				$cont= 0;
				$conector = '  ';
				$conector1 = '  ';
				if($datos[$i][posologia][0][sw_estado_desayuno]== '1')
				{
					$Des = $momento.'el Desayuno';
					$cont++;
				}
				if($datos[$i][posologia][0][sw_estado_almuerzo]== '1')
				{
					$Alm = $momento.'el Almuerzo';
					$cont++;
				}
				if($datos[$i][posologia][0][sw_estado_cena]== '1')
				{
					$Cen = $momento.'la Cena';
					$cont++;
				}
				if ($cont== 2)
				{
					$conector = ' y ';
					$conector1 = '  ';
				}
				if ($cont== 1)
				{
					$conector = '  ';
					$conector1 = '  ';
				}
				if ($cont== 3)
				{
					$conector = ' , ';
					$conector1 = ' y ';
				}
				$posologia = $Des.$conector.$Alm.$conector1.$Cen;
			}

			//pintar formula para opcion 4 ok
			if($datos[$i][tipo_opcion_posologia_id]== 4)
			{
				$conector = '  ';
				$frecuencia='';
				$j=0;
				foreach ($datos[$i][posologia] as $k => $v)
				{
					if ($j+1 ==sizeof($datos[$i][posologia]))
					{
						$conector = '  ';
					}
					else
					{
						if ($j+2 ==sizeof($datos[$i][posologia]))
						{
							$conector = ' y ';
						}
						else
						{
							$conector = ' - ';
						}
					}
					$frecuencia = $frecuencia.$k.$conector;
					$j++;
				}
               	$posologia = 'a la(s): '.$frecuencia;
			}

			//pintar formula para opcion 5 //ok
			if($datos[$i][tipo_opcion_posologia_id]== 5)
			{
				$posologia = ' '.$datos[$i][posologia][0][frecuencia_suministro];
			}

				
			$pdf->WriteHTML($html);
			$html='';		
			$pdf->SetFont('Arial','',8);
			$html.="<TR>";
			$e=$datos[$i][dosis]/floor($datos[$i][dosis]);			
			if($e==1)
			{
				$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>".'Dosis: '.floor($datos[$i][dosis]).' '.$datos[$i][unidad_dosificacion].' '.$posologia."</TD>";
			}
			else
			{
			  $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>".'Dosis: '.$datos[$i][dosis].' '.$datos[$i][unidad_dosificacion].' '.$posologia."</TD>";
			}
			$pdf->WriteHTML($html);
			$html='';		
			$pdf->SetFont('Arial','',6);			
			
               $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='429'>".'Vía Administración : '.$datos[$i][via]."</TD>";

			$html.="</TR>";

			if ($datos[$i][observacion]!='')
			{
               	$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='789'>".'Observacion : '.$datos[$i][observacion]."</TD></TR>";
      		}

			for ($c=0; $c<1;$c++)
			{
				$html.="<TR>";
				$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
				$html.="</TR>";
			}

		}
		$html.="</TABLE>";
		$pdf->WriteHTML($html);
		
		$pdf->SetFont('Arial','',8);
		$html="<br><TABLE BORDER='0' WIDTH='1520'>";
		//$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>PROFESIONAL TRATANTE:<br></TD></TR>";

		//unica parte donde este reporte es diferente con respecto al de hospitalizacion
		//por que el medico que se pinta aqui es el de una evolucion especifica
		//que trae el resultado del query.  y en hospitalizacion quien firma la formula
		//es el medico de la max evolucion cerrada del ingreso.

		$largo = strlen($datos[0][nombre_tercero]);
		$cad = '___';
		for ($l=0; $l<$largo; $l++)
		{
			$cad = $cad.'_';
		}
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='250'>".$cad."</TD>";
		$id=$datos[0][tipo_id_paciente]."-".$datos[0][paciente_id];
		$largo = strlen($datos[0][paciente]);
		$cad = '___';
		for ($l=0; $l<$largo; $l++)
		{ $cad = $cad.'_'; }
		$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='250'>".$cad."</TD>";
		$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='150'>Expendedor</TD>";
		$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='110'>Valor Total</TD>";
		$html.="</TR>";

		$dias_vencimiento = ModuloGetVar('app', 'Central_de_Autorizaciones','vencimiento_formula_medica');
		$x=explode(' ',$datos[0][fecha]);
		$fecha_vencimiento=date("Y-m-d",strtotime("+".($dias_vencimiento-1)." days",strtotime(date($x[0]))));

		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' HEIGHT='22' WIDTH='250'>".strtoupper($datos[0][nombre_tercero])."</TD>";
		$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='250'>".$datos[0][paciente]."</TD>";
		$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='150'>&nbsp;</TD>";
		$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='110'>Cuota Moderadora: ".FormatoValor($datos[0][cuota_moderadora][cuota_moderadora])."</TD>";
		$html.="</TR>";
		if($datos[0][tarjeta_profesional] != '')
		{
               $html.="<TR>";
               $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='250'>".$datos[0][tipo_id_medico].': '.$datos[0][medico_id].' T.P.: '.$datos[0][tarjeta_profesional]."</TD>";
               $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='250'>".$id."   Tel : ".$datos[0][residencia_telefono]."</TD>";
               $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='150'>&nbsp;</TD>";
               $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='110'>Neto a pagar</TD>";
               $html.="</TR>";
		}
		else
		{
               $html.="<TR>";
               $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='250'>".$datos[0][tipo_id_medico].': '.$datos[0][medico_id]."</TD>";
               $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='250'>".$id."   Tel : ".$datos[0][residencia_telefono]."</TD>";
               $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='150'>&nbsp;</TD>";
               $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='110'>Neto a pagar</TD>";				
               $html.="</TR>";
		}
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='250'>".$datos[0][tipo_profesional]."</TD>";
		$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='250'>USUARIO</TD>";
		$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='150'>&nbsp;</TD>";
		$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='110'>  S.O.S</TD>";
		$html.="</TR>";
		$html.="</TABLE>";
		//$pdf->SetFont('Arial','',18);
		//$pdf->SetFont('Arial','',8);
		$pdf->WriteHTML($html);
		$pdf->Output($Dir,'F');
		return true;
		return true;
 }


//DEVUELVE LA FECHA EN FORMATO DIA, MES, AÑO
	function FechaStampC($fecha)
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

//DEVUELVE LA FECHA EN FORMATO AÑO, MES, DIA
	function FechaStampT($fecha)
	{
		if($fecha){
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
