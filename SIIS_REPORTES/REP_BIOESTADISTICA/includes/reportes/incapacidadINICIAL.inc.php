<?php

/**
 * $Id: incapacidadINICIAL.inc.php,v 1.2 2005/06/07 18:40:58 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */
	function GenerarIncapacidad($datos)
	{
// 		$fechaI=FechaStampJT($datos[0][fecha_nacimiento]);
// 		$fechaF=FechaStampJT($datos[0][fecha_cierre]);
// 		$fechaIngreso=FechaStampJ($datos[0][fecha_ingreso]);
// 		$fechaEvolucion=FechaStampJ($datos[0][fecha_cierre]);
// 		$edad=CalcularEdad($fechaI,$fechaF);
		$Dir="cache/incapacidad_medica".UserGetUID().".pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$_SESSION['REPORTES']['VARIABLE']='incapacidad';
		$_SESSION['INCAPACIDAD']['DATOS']=$datos[0];
		$pdf=new PDF('P','mm','soat');
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Arial','',8);

/*		$html.="<TABLE BORDER='0' WIDTH='1520'>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER'>";
		if(is_file('images/logocliente.png'))
		{
			$html.="".$pdf->image('images/logocliente.png',10,6,18)."";
		}
		$html.="</TD>";
		$html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25>";
		$html.="<b>INCAPACIDAD MEDICA</b>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25><br><br>DOCUMENTO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$datos[0]['tipo_id_paciente']." ".$datos[0]['paciente_id']."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>HC:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>";
		if($datos[0]['historia_numero']!="")
		{
			if($datos[0]['historia_prefijo']!="")
			{
				$html.= $datos[0]['historia_numero']." - ". $datos[0]['historia_prefijo'];
			}
			else
			{
				$html.= $datos[0]['paciente_id']." - ".$datos[0]['historia_prefijo'];
			}
		}
		else
		{
			$html.= $datos[0]['paciente_id']." - ".$datos[0]['tipo_id_paciente'];
		}
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>NOMBRE:</TD>";
		$nombre = $datos[0]['paciente'];
		$nombre = substr("$nombre", 0, 38);
		$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($nombre)."."."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>No. DE INGRESO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$datos[0]['ingreso']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>EDAD:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$edad['anos'].' AÑOS'."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>SEXO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$datos[0]['sexo_id']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>FECHA INGRESO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$fechaIngreso."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>FECHA SOLICITUD:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".$fechaEvolucion."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>CLIENTE:</TD>";
		$cliente = $datos[0]['cliente'];
		$cliente = substr("$cliente", 0, 38);
		$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($cliente)."."."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>PLAN:</TD>";
		$plan = $datos[0]['plan_descripcion'];
		$plan = substr("$plan", 0, 38);
		$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($plan)."."."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='110' HEIGHT=25>TIPO DE AFILIADO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($datos[0]['tipo_afiliado_nombre'])."</TD>";
		$html.="<TD WIDTH='110' HEIGHT=25>RANGO:</TD>";
		$html.="<TD WIDTH='270' HEIGHT=25>".strtoupper($datos[0]['rango'])."</TD>";
		$html.="</TR>";*/
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25><br><b>".strtoupper($datos[0]['tipo_incapacidad_descripcion'])."</b></TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='380' HEIGHT=25><br>SERVICIO:".' '."".$datos[0]['servicio']."</TD>";
		$html.="<TD WIDTH='380' HEIGHT=25>DURACION:".' '."".$datos[0]['dias_de_incapacidad']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='380' HEIGHT=25>FECHA DE EMISION:".' '."".$datos[0]['fecha']."</TD>";
		$html.="<TD WIDTH='380' HEIGHT=25>FECHA DE TERMINACION:".' '."".$datos[0]['fecha_terminacion']."</TD>";
		$html.="</TR>";


//cambio dar
		$html.="<TR>";
		$html.="<TD WIDTH='100' HEIGHT=25>DIAGNOSTICO:</TD>";
		$html.="<TD WIDTH='500' HEIGHT=25>";
				if(!empty($datos[0][diagnostico_ingreso]) AND empty($datos[0][diagnostico_egreso]))
				{
						foreach ($datos[0][diagnostico_ingreso] as $k => $v)
						{
							$html.="$v[diagnostico_id]-";
						}
				}
				else
				{
						foreach ($datos[0][diagnostico_egreso] as $k => $v)
						{
							$html.="$v[diagnostico_id]";
						}
				}
		$html.="</TD>";
		$html.="</TR>";
//fin cambio dar


		$html.="<TR>";
		$html.="<TD WIDTH='95' HEIGHT=25><br><b>OBSERVACION:</b></TD>";
		$html.="</TR>";
		//$html.="<TD WIDTH='665' HEIGHT=25>".substr(strtoupper($datos[0]['observacion_incapacidad']),0,99)."";
		/*$html.="<TR>";
		$html.="<TD WIDTH='665' HEIGHT=10></TD>";
		$html.="</TR>";*/



	/*	if(substr(strtoupper($datos[0]['observacion_incapacidad']),99,110)==NULL)
		{
			$html.="</TD>";
		}
		else
		{
			$html.="".'-'."</TD>";
		}
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".substr(strtoupper($datos[0]['observacion_incapacidad']),99,110)."";
		if(substr(strtoupper($datos[0]['observacion_incapacidad']),209,110)==NULL)
		{
			$html.="</TD>";
		}
		else
		{
			$html.="".'-'."</TD>";
		}
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".substr(strtoupper($datos[0]['observacion_incapacidad']),209,110)."";
		if(substr(strtoupper($datos[0]['observacion_incapacidad']),319,110)==NULL)
		{
			$html.="</TD>";
		}
		else
		{
			$html.="".'-'."</TD>";
		}
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".substr(strtoupper($datos[0]['observacion_incapacidad']),319,110)."";
		if(substr(strtoupper($datos[0]['observacion_incapacidad']),429,110)==NULL)
		{
			$html.="</TD>";
		}
		else
		{
			$html.="".'-'."</TD>";
		}*/



/*		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".substr(strtoupper($datos[0]['observacion_incapacidad']),429,110)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25><br>MEDICO TRATANTE:</TD>";
		$html.="</TR>";
/*		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760'>";
		$html.="________________________________________________";
		$html.="</TD>";
		$html.="</TR>";*/
/*		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".strtoupper($datos[0]['nombre_tercero'])."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".$datos[0]['tipo_id_medico'].': '.$datos[0]['medico_id']."".'   T.P.: '.$datos[0]['tarjeta_profesional']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".$datos[0]['tipo_profesional']."</TD>";
		$html.="</TR>";*/
		$html.="</TABLE>";
		$pdf->WriteHTML($html);

		if(!empty($datos[0]['observacion_incapacidad']))
		{
			$salida=$pdf->MultiCell(170,4,strtoupper($datos[0]['observacion_incapacidad']),$border=0,$align='J',$fill=0);
			$pdf->WriteHTML($salida);
		}


		//$pdf->SetLineWidth(0.7);
		//$pdf->RoundedRect(7, 5, 202, 120, 3.5, '');//120
		$pdf->Output($Dir,'F');
		//unset ($_SESSION['INCAPACIDAD']['DATOS']);
		//unset ($_SESSION['REPORTES']['VARIABLE']);
		return True;
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
