<?php

/**
 * $Id: incapacidad.inc.php,v 1.21 2005/06/15 18:21:23 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */
	function GenerarIncapacidad($datos)
	{
		$Dir="cache/incapacidad_medica".UserGetUID().".pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$_SESSION['REPORTES']['VARIABLE']='incapacidad';
		$_SESSION['INCAPACIDAD']['DATOS']=$datos[0];
		$pdf=new PDF('P','mm','soat');
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Arial','',8);

		/*$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25><b>SOLICITUD DE INCAPACIDADES Y/O LICENCIAS DE MATERNIDAD</b></TD>";
		$html.="</TR>";*/
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760' HEIGHT=25><b>INFORMACION SOBRE LA INCAPACIDAD</b></TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='300' HEIGHT=25>Tipo Contingencia:".' '."".strtoupper($datos[0]['tipo_incapacidad_descripcion'])."</TD>";
		if($datos[0]['sw_prorroga']==1)
		{  $pro='SI';  }
		else
		{  $pro='NO';  }
		$html.="<TD WIDTH='210' HEIGHT=25>Pr?rroga : $pro</TD>";
		if(!empty($datos[0]['clase']))
		{  $html.="<TD WIDTH='200' HEIGHT=25>Clase Atenci?n : ".$datos[0]['clase']."</TD>";  }
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='300' HEIGHT=25>Fecha de Inicio:".' '."".$datos[0]['fecha']."</TD>";
		$html.="<TD WIDTH='210' HEIGHT=25>Fecha de Terminaci?n:".' '."".$datos[0]['fecha_terminacion']."</TD>";
		$html.="<TD WIDTH='250' HEIGHT=25><b>Duraci?n:".' '."".$datos[0]['dias_de_incapacidad']." d?as</b></TD>";
		$html.="</TR>";


//cambio dar
		$html.="<TR>";
		$html.="<TD WIDTH='80' HEIGHT=25>Diagn?stico:</TD>";
		$html.="<TD WIDTH='220' HEIGHT=25>";
		if(!empty($datos[0][diagnostico_ingreso]) AND empty($datos[0][diagnostico_egreso]))
		{
				foreach ($datos[0][diagnostico_ingreso] as $k => $v)
				{
					$html.="$v[diagnostico_id]";
					$descripcion=$v[diagnostico_nombre];
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
		$pdf->WriteHTML($html);
		$html='';
		//$html.="<TD WIDTH='400' HEIGHT=25>Descripci?n: ".$descripcion."</TD>";
		$html.="<TD WIDTH='400' HEIGHT=25>".$pdf->MultiCell(110,3,"Descripci?n: ".$descripcion,0,'J',0)."</TD>";
		$html.="</TR>";
//fin cambio dar

		$pdf->WriteHTML($html);
		$html='';
		$html.="<TR>";
		$html.="<TD WIDTH='760' HEIGHT=25>".$pdf->MultiCell(195,3,"Observaci?n: ".$datos[0]['observacion_incapacidad'],0,'J',0)."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='LEFT' WIDTH='760' HEIGHT=25><br><b>INFORMACION DE LA IPS Y DEL PROFESIONAL</b></br></TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='280' HEIGHT=25>NOMBRE IPS: ".$datos[0][ips]."</TD>";
		$html.="</TR>";
		$html.="</TABLE>";
		$html.="<TABLE BORDER='0' WIDTH='1520'>";
		$html.="<TR>";
		$pdf->WriteHTML($html);
		$html='';
		$pdf->SetFont('Arial','',6);
		$html.="<TD WIDTH='400' HEIGHT=25>Certifico que la informaci?n Registrada es veridica y libre de ser confirmada</TD>";
		$html.="<TD WIDTH='380' HEIGHT=25>Al firmar como cotizante acepto las condiciones impuestas para mi recuperaci?n</TD>";
		$html.="</TR>";
		$pdf->WriteHTML($html);
		$pdf->SetFont('Arial','',8);		
		$html='';
		$html.="<TR>";
		$html.="<TD WIDTH='760'><br>&nbsp;</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='400' HEIGHT=25>".strtoupper($datos[0]['nombre_tercero'])."</TD>";
		$html.="<TD WIDTH='380' HEIGHT=25>".strtoupper($datos[0]['paciente'])."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='400' HEIGHT=25>".$datos[0]['tipo_id_medico'].': '.$datos[0]['medico_id']."".'   T.P.: '.$datos[0]['tarjeta_profesional']."</TD>";
		$html.="<TD WIDTH='380' HEIGHT=25>".$datos[0]['tipo_id_paciente'].': '.$datos[0]['paciente_id']."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='400' HEIGHT=25>".$datos[0]['tipo_profesional']."</TD>";
		$html.="<TD WIDTH='380' HEIGHT=25>COTIZANTE</TD>";
		$html.="</TR>";
		$html.="</TABLE>";


		$pdf->WriteHTML($html);
		$pdf->Output($Dir,'F');
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
