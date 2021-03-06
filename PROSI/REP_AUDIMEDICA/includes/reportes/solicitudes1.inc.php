<?php

/**
 * $Id: solicitudes.inc.php,v 1.15 2005/07/25 15:01:07 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */
	function GenerarSolicitud($vector)
	{
		IncludeLib("tarifario_cargos");
		IncludeLib("funciones_central_impresion");
		if(!empty($vector['evolucion']))
		{
				$_SESSION['SOLICITUD']['DATOS']=$datos[0]=EncabezadoReporteEvolucion($vector['evolucion'],$vector['TipoDocumento'],$vector['Documento']);
				$_SESSION['SOLICITUD']['DAT']=$dat=BuscarSolicitudesEvolucion($vector['evolucion']);
		}
		else
		{
				$_SESSION['SOLICITUD']['DATOS']=$datos[0]=EncabezadoReporteIngreso($vector['ingreso'],$vector['TipoDocumento'],$vector['Documento']);
				$_SESSION['SOLICITUD']['DAT']=$dat=BuscarSolicitudesIngreso($vector['ingreso']);
		}
		$Dir="cache/solicitudes".UserGetUID().".pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$_SESSION['REPORTES']['VARIABLE']='solicitudes';
		$pdf=new PDF('P','mm','soat');
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Arial','',8);
		$html.="<TABLE BORDER='0' WIDTH='1520'>";
		$diag=Diagnostico($dat[0][evolucion_id]);
		/*if(!empty($diag))
		{
			$html.="<TR>";
			$html.="<TD WIDTH='760' HEIGHT=25>DIAGNOSTICO(S) :".$diag."</TD>";
			$html.="</TR>";
		}*/
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25><br>";
		$html.="<b>SOLICITUD DE SERVICIOS</b>";
		$html.="<br></TD>";
		$html.="</TR>";
		for($i=0; $i<sizeof($dat);$i++)
		{
			$inter=Interconsulta($dat[$i][hc_os_solicitud_id]);
			$html.="<TR>";
			$html.="<TD WIDTH='760' HEIGHT=25>".$dat[$i][hc_os_solicitud_id].' - '.$dat[$i][cargos].' - ( '.$dat[$i][cantidad].' )'.$dat[$i][descar].' '.$inter."</TD>";
			$html.="</TR>";
			$html.="<TR>";
			$diagnostico='';
			$diagS=DiagnosticoSolicitud($dat[$i][hc_os_solicitud_id]);
			if(!empty($diagS))
			{		$diagnostico=$diagS;		}	
			else
			{		$diagnostico=$diag;		}
			$html.="<TD WIDTH='760' HEIGHT=25>DIAGNOSTICO(S): $diagnostico</TD>";
			$html.="</TR>";			
			if(!empty($dat[$i][obsapoyo]))
			{
				$pdf->WriteHTML($html);
				$html='';
				$html.="<TR>";
				$html.="<TD WIDTH='760' HEIGHT=25>".$pdf->MultiCell(195,3,"OBSERVACION: ".$dat[$i][obsapoyo],0,'J',0)."</TD>";
				$html.="</TR>";
				/*$html.="<TR>";
				$html.="<TD WIDTH='760' HEIGHT=25>OBSERVACION: ".$dat[$i][obsapoyo]."</TD>";
				$html.="</TR>";*/
			}
			if(!empty($dat[$i][obsinter]))
			{
				$pdf->WriteHTML($html);
				$html='';
				$html.="<TR>";
				$html.="<TD WIDTH='760' HEIGHT=25>".$pdf->MultiCell(195,3,"OBSERVACION: ".$dat[$i][obsinter],0,'J',0)."</TD>";
				//$html.="<TD WIDTH='760' HEIGHT=25>OBSERVACION: ".$dat[$i][obsinter]."</TD>";
				$html.="</TR>";
			}
			if(!empty($dat[$i][obsnoqx]))
			{
				$pdf->WriteHTML($html);
				$html='';
				$html.="<TR>";
				$html.="<TD WIDTH='760' HEIGHT=25>".$pdf->MultiCell(195,3,"OBSERVACION: ".$dat[$i][obsnoqx],0,'J',0)."</TD>";
				//$html.="<TD WIDTH='760' HEIGHT=25>OBSERVACION: ".$dat[$i][obsnoqx]."</TD>";
				$html.="</TR>";
			}
			if(!empty($dat[$i][trap]))
			{
				$html.="<TR>";
				$html.="<TD WIDTH='760' HEIGHT=25>OBSERVACION: ".$dat[$i][trap]." DIAS DE TRAMITE</TD>";
				$html.="</TR>";
			}
			elseif(!empty($dat[$i][tra]))
			{
				$html.="<TR>";
				$html.="<TD WIDTH='760' HEIGHT=25>OBSERVACION: ".$dat[$i][tra]." DIAS DE TRAMITE</TD>";
				$html.="</TR>";
			}
		}
		$html.="</TABLE>";
		$pdf->WriteHTML($html);
		//$pdf->SetLineWidth(0.7);
		//$pdf->RoundedRect(7, 5, 202, 120, 3.5, '');//120
		$pdf->Output($Dir,'F');
		unset ($_SESSION['SOLICITUD']['DAT']);
		unset ($_SESSION['SOLICITUD']['DATOS']);
		//unset ($_SESSION['REPORTES']['VARIABLE']);
		return True;
	}

	function Profesional($evolucion)
	{
		list($dbconn) = GetDBconn();
		$query = "select c.nombre_tercero, f.especialidad, g.descripcion
						from hc_evoluciones as a, profesionales_usuarios as b, terceros as c,
						profesionales_especialidades as f, especialidades as g
						where a.evolucion_id=".$evolucion." and a.usuario_id=b.usuario_id
						and b.tipo_tercero_id=c.tipo_id_tercero and b.tercero_id=c.tercero_id
						and f.tipo_id_tercero=c.tipo_id_tercero and f.tercero_id=c.tercero_id
						and f.especialidad=g.especialidad";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Guardar en la Base de Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		$resulta->Close();
		return $var;
	}

	function Interconsulta($hc_os_solicitud_id)
	{
		list($dbconn) = GetDBconn();
		$query = "select c.descripcion as especialidad_nombre
						from hc_os_solicitudes as a, hc_os_solicitudes_interconsultas as b, especialidades as c
						where a.hc_os_solicitud_id=b.hc_os_solicitud_id and b.especialidad=c.especialidad and
						a.hc_os_solicitud_id = $hc_os_solicitud_id";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Guardar en la Base de Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if(!$resulta->EOF)
		{
			$var=$resulta->fields[0];
		}
		return $var;
	}

	function Historia($tipo,$id)
	{
		list($dbconn) = GetDBconn();
		$query = "select historia_prefijo  as prefijo,
						historia_numero as numero
						from historias_clinicas
						where tipo_id_paciente='$tipo' and paciente_id='$id'";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Guardar en la Base de Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if(!$resulta->EOF)
		{
			$var=$resulta->GetRowAssoc($ToUpper = false);
		}
		$resulta->Close();
		return $var;
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
