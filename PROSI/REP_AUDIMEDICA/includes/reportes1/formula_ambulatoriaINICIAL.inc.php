<?php

/**
 * $Id: formula_ambulatoriaINICIAL.inc.php,v 1.1 2005/06/08 16:13:59 darling Exp $
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
	$titulo = 'FORMULA MEDICA';


/*
		$fechaI=FechaStampT($datos[0][fecha_nacimiento]);
		$fechaF=FechaStampT($datos[0][fecha_cierre]);
		$fechaEvolucion=FechaStampC($datos[0][fecha_cierre]);
		$edad = CalcularEdad($fechaI,$fechaF);

    $html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";

	$historia= '';
    $titulo = 'FORMULA MEDICA';

		if($datos[0][historia_numero]!="")
		{
			if($datos[0][historia_prefijo]!="")
			{
				$historia=$datos[0][historia_numero]."-". $datos[0][historia_prefijo];
			}
			else
			{
				$historia=$datos[0][paciente_id]."-".$datos[0][historia_prefijo];
			}
		}
		else
		{
		  $historia=$datos[0][paciente_id]."-".$datos[0][tipo_id_paciente];
		}

		$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='115'>IDENTIFICACION:</TD><TD ALIGN='LEFT' WIDTH='275' HEIGHT=22>".$datos[0][tipo_id_paciente]." : ".$datos[0][paciente_id]."</TD><TD HEIGHT=22 WIDTH='115'>HC:</TD><TD WIDTH='275' HEIGHT=22>".$historia."</TD></TR>";
		$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='115'>PACIENTE: </TD>";
		$nombre = $datos[0][paciente];
		$nombre = substr("$nombre", 0, 38);
		$html.="<TD ALIGN='LEFT' WIDTH='275' HEIGHT=22>".strtoupper($nombre)."."."</TD>";
		$html.="<TD HEIGHT=22 WIDTH='115'>No. EVOL.:</TD>";
		$html.="<TD WIDTH='275' HEIGHT=22>".$datos[0][evolucion_id]."</TD>";
		$html.="</TR>";
		$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='115'>EDAD:</TD><TD ALIGN='LEFT' WIDTH='275' HEIGHT=22>".$edad[edad_aprox]."</TD><TD HEIGHT=22 WIDTH='115'>SEXO:</TD><TD WIDTH='275' HEIGHT=22>".$datos[0][sexo_id]."</TD></TR>";
		$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='115'>FECHA SOLICITUD:</TD><TD ALIGN='LEFT' WIDTH='275' HEIGHT=22>".$fechaEvolucion."</TD><TD HEIGHT=22 WIDTH='115'>TIPO AFI.:</TD><TD WIDTH='275' HEIGHT=22>".strtoupper($datos[0][tipo_afiliado_nombre])."</TD></TR>";
		$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='115'>CLIENTE:</TD>";
		$cliente = $datos[0][cliente];
		$cliente = substr("$cliente", 0, 38);
		$html.="<TD ALIGN='LEFT' WIDTH='275' HEIGHT=22>".strtoupper($cliente)."."."</TD>";
		$html.="<TD HEIGHT=22 WIDTH='115'>RANGO:</TD><TD WIDTH='275' HEIGHT=22></TD>".$datos[0][rango]."</TR>";
		$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='115'>PLAN:</TD>";
		$plan = $datos[0][plan_descripcion];
		$plan = substr("$plan", 0, 38);
		$html.="<TD WIDTH='275' HEIGHT=25>".strtoupper($plan)."."."</TD>";
		$html.="</TD><TD HEIGHT=22 WIDTH='115'>EMPRESA:</TD>";
		$empleador = $datos[0][empleador];
		$empleador = substr("$empleador", 0, 38);
		$html.="<TD WIDTH='275' HEIGHT=22>".strtoupper($empleador)."."."</TD></TR>";
	    if ($datos[0][atencion]!= '')
		{
		    $html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='120'>T. CONTINGENCIA:</TD><TD ALIGN='LEFT' WIDTH='280' HEIGHT=22>".strtoupper($datos[0][atencion])."</TD></TR>";
		}

		if ($datos[0][uso_controlado]==1)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='115' HEIGHT=25>DIRECCION.:</TD>";
			$dir = $datos[0][residencia_direccion];
			$dir = substr("$dir", 0, 38);
			$html.="<TD WIDTH='275' HEIGHT=25>".strtoupper($dir)."."."</TD>";
			$html.="<TD WIDTH='115' HEIGHT=25>TELEFONO.:</TD>";
			$html.="<TD WIDTH='275' HEIGHT=25>".strtoupper($datos[0]['residencia_telefono'])."</TD>";
			$html.="</TR>";
		}*/

    	for ($c=0; $c<1;$c++)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
			$html.="</TR>";
		}

		if ($datos[0][uso_controlado]==1)
		{
			$subtitulo = 'MEDICAMENTO(S) DE USO CONTROLADO.';
		}
		else
		{
			if($datos[0][item]=='POS')
			{
				$subtitulo = 'MEDICAMENTO(S) POS FORMULADO(S).';
			}

			if($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='1')
			{
				$subtitulo = 'MEDICAMENTO(S) NO POS SOLICITADO(S) A PETICION DEL PACIENTE.';
			}
			elseif($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='0')
			{
				$subtitulo = 'MEDICAMENTO(S) NO POS JUSTIFICADO(S).';
			}
		}
		$html.="<TR><TD ALIGN='CENTER' HEIGHT=22 WIDTH='720'>".$titulo." : ".$subtitulo."</TD></TR>";
    $html.="</table>";
		for ($c=0; $c<1;$c++)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
			$html.="</TR>";
		}


//******************************FOR DE MED*********************************
    $html .="<TABLE BORDER='0' WIDTH='1000' ALIGN='CENTER'>";
		for($i=0; $i<sizeof($datos);$i++)
		{
			$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='320'>".($i+1).'. '.strtoupper($datos[$i][producto])."</TD>";
			//pintar cantidad
			$e=$datos[$i][cantidad]/floor($datos[$i][cantidad]);
			if ($datos[$i][contenido_unidad_venta])
			{
				if($e==1)
				{
					$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='320'>".'Cantidad : '.floor($datos[$i][cantidad]).' '.$datos[$i][descripcion].' por '.$datos[$i][contenido_unidad_venta]."</TD></TR>";
				}
				else
				{
					$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='320'>".'Cantidad : '.$datos[$i][cantidad].' '.$datos[$i][descripcion].' por '.$datos[$i][contenido_unidad_venta]."</TD></TR>";
				}
			}
			else
			{
				if($e==1)
				{
					$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='320'>".'Cantidad : '.floor($datos[$i][cantidad]).' '.$datos[$i][descripcion]."</TD></TR>";
				}
				else
				{
          $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='320'>".'Cantidad : '.$datos[$i][cantidad].' '.$datos[$i][descripcion]."</TD></TR>";
				}
			}

      if($datos[$i][via]!='')
			{
			  $html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='720'>".'Via de Administracion : '.$datos[$i][via]."</TD></TR>";
			}

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

			$e=$datos[$i][dosis]/floor($datos[$i][dosis]);
			if($e==1)
			{
				$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='720'>".'Dosis : '.floor($datos[$i][dosis]).' '.$datos[$i][unidad_dosificacion].' '.$posologia."</TD></TR>";
			}
			else
			{
			  $html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='720'>".'Dosis : '.$datos[$i][dosis].' '.$datos[$i][unidad_dosificacion].' '.$posologia."</TD></TR>";
			}

			if ($datos[$i][observacion]!='')
			{
				$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='720'>".'Observacion : '.$datos[$i][observacion]."</TD></TR>";
      }

			for ($c=0; $c<1;$c++)
			{
				$html.="<TR>";
				$html.="<TD WIDTH='660' HEIGHT='22' ALIGN='RIGHT'>".""."</TD>";
				$html.="</TR>";
			}

		}

		$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>PROFESIONAL TRATANTE:</TD></TR>";

		if(!empty($datos[0][cuota_moderadora][cuota_moderadora]))
		{
			$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>&nbsp;</TD><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>".'CUOTA MODERADORA:'.$datos[0][cuota_moderadora][cuota_moderadora]."</TD></TR>";
		}
		else
		{
    		$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>&nbsp;</TD></TR>";
		}
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
		$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>".$cad."</TD>";

		//dignosticos
		if (($datos[0][diagnostico_ingreso]!='') OR ($datos[0][diagnostico_egreso]!=''))
		{
			$html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='130'>DIAGNOSTICO(S) : </TD>";
		}

		$diagnostico_ingreso = '';
		foreach ($datos[0][diagnostico_ingreso] as $k => $v)
		{
      if($diagnostico_ingreso == '')
			{
          $diagnostico_ingreso.= $v[diagnostico_id];
			}
			else
			{
          $diagnostico_ingreso.= ' - '.$v[diagnostico_id];
			}
		}

    $diagnostico_egreso = '';
		foreach ($datos[0][diagnostico_egreso] as $k => $v)
		{
      if($diagnostico_egreso == '')
			{
          $diagnostico_egreso.= $v[diagnostico_id];
			}
			else
			{
          $diagnostico_egreso.= ' - '.$v[diagnostico_id];
			}
		}
		//if($diagnostico_ingreso != '' OR $diagnostico_egreso != '')
		//{
		  $html.="<TD ALIGN='LEFT' HEIGHT=22 WIDTH='230'>".$diagnostico_ingreso." ".$diagnostico_egreso."</TD></TR>";
		//}
    //fin de los dignosticos

		$dias_vencimiento = ModuloGetVar('app', 'Central_de_Autorizaciones','vencimiento_formula_medica');
		$x=explode(' ',$datos[0][fecha]);
		$fecha_vencimiento=date("Y-m-d",strtotime("+".($dias_vencimiento-1)." days",strtotime(date($x[0]))));

		$html.="<TR><TD ALIGN='LEFT' HEIGHT='22' WIDTH='360'>".strtoupper($datos[0][nombre_tercero])."</TD><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>".'VALIDEZ : '.$dias_vencimiento.' Dias'.' Calendario'."</TD></TR>";
		if($datos[0][tarjeta_profesional] != '')
		{
				$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>".$datos[0][tipo_id_medico].': '.$datos[0][medico_id].' T.P.: '.$datos[0][tarjeta_profesional]."</TD><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>".'FECHA DE VENCIMIENTO : '.FechaStampC($fecha_vencimiento)."</TD></TR>";
		}
		else
		{
				$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>".$datos[0][tipo_id_medico].': '.$datos[0][medico_id]."</TD><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>".'FECHA DE VENCIMIENTO : '.FechaStampC($fecha_vencimiento)."</TD></TR>";
		}
		$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='720'>".$datos[0][tipo_profesional]."</TD></TR>";

		//$html.="<BR><TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>FIRMA PACIENTE:</TD></TR>";
		$html.="<BR>";
		$id=$datos[0][tipo_id_paciente]."-".$datos[0][paciente_id];
		$largo = strlen($datos[0][paciente]);
		$cad = '___';
		for ($l=0; $l<$largo; $l++)
		{
			$cad = $cad.'_';
		}
		$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>".$cad."</TD></TR>";
		$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>".$datos[0][paciente]."</TD></TR>";
		$html.="<TR><TD ALIGN='LEFT' HEIGHT=22 WIDTH='360'>".$id."   Tel : ".$datos[0][residencia_telefono]."</TD></TR>";


		$html.="</TABLE>";
		$pdf->SetFont('Arial','',18);
		$pdf->SetFont('Arial','',8);
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
