<?php

/**
 * $Id: ordenservicio.inc.php,v 1.25 2005/07/25 15:01:00 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */
	function RevisarCama($orden)
	{
		list($dbconn) = GetDBconn();
		$query = "select cama,departamento
					from hc_os_solicitudes_manuales_datos_adicionales where orden_servicio_id=$orden";
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




	function GenerarOrden($vector)
	{
		IncludeLib("tarifario_cargos");
		IncludeLib("funciones_facturacion");
		IncludeLib("funciones_central_impresion");
		$_SESSION['REPORTES']['VARIABLE']='orden_servicio';
		$datos[0]=EncabezadoReporteOrden($vector['orden']);
		$dat=ReporteOrdenServicio($vector['orden']);
		$_SESSION['ORDENSERVICIO']['DATOS']=$datos[0];
		$_SESSION['ORDENSERVICIO']['DAT']=$dat;
		$_SESSION['ORDENSERVICIO']['VECTOR']=$vector;
/*		$fechaI=FechaStampJT($datos[0][fecha_nacimiento]);
		$fechaIngreso=FechaStampJ($datos[0][fechaingreso]);
		$fechaSolicitud=FechaStampJ($datos[0][fechasolicitud]);
		$edad=CalcularEdad($fechaI,$fechaF);*/
		$Dir="cache/ordenservicio".$vector['orden'].".pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$pdf=new PDF('P','mm','soat');
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Arial','',8);
		$total=0;
		$profe='';
		$copago=$moderadora=$nocub=0;
		if(!empty($datos[0][observacion]))
		{
			$html.="<TR>";
			$html.="<TD WIDTH='760' HEIGHT=25>OBSERVACION: ".$datos[0][observacion]."</TD>";
			$html.="</TR>";
		}
		for($i=0; $i<sizeof($dat);)
		{
			$x=$i;
			while($dat[$i][cargo_cups]==$dat[$x][cargo_cups])
			{
				if(empty($dat[$x][evolucion_id]))
				{
					$pro=$dat[$x][profesional];
					if($pro!=$profe)
					{
						$profe=$pro;
						$html.="<TR>";
						$html.="<TD WIDTH='760' HEIGHT=25>PROFESIONAL: ".$pro."</TD>";
						$html.="</TR><br>";
					}
				}
				else
				{
					$pro=Profesional($dat[$x][evolucion_id]);
					if($pro!=$profe)
					{
						$profe=$pro;
						$html.="<TR>";
						$html.="<TD WIDTH='760' HEIGHT=25>PROFESIONAL: ".$pro."</TD>";
						$html.="</TR><br>";
					}
				}		

				$inter=$dat[$x][especialidad_nombre];
				$html.="<TR>";
				$html.="<TD WIDTH='760' HEIGHT=25>".$dat[$x][numero_orden_id].' - '.$dat[$x][cargo_cups].' -  ( '.$dat[$i][cantidad].' ) '.$dat[$x][descripcion].' '.$inter."</TD>";
				$html.="</TR>";
				$diag=Diagnostico($dat[$x][evolucion_id]);				
				$diagnostico='';
				$diagS=DiagnosticoSolicitud($dat[$i][hc_os_solicitud_id]);
				if(!empty($diagS))
				{		$diagnostico=$diagS;		}	
				else
				{		$diagnostico=$diag;		}
				if(!empty($diagnostico))
				{
					$html.="<TR>";
					$html.="<TD WIDTH='760' HEIGHT=25>DIAGNOSTICOS: ".$diagnostico."</TD>";
					$html.="</TR>";
				}				
				if(!empty($dat[$x][obsapoyo]))
				{
					$html.="<TR>";
					$html.="<TD WIDTH='760' HEIGHT=25>OBSERVACION: ".$dat[$x][obsapoyo]."</TD>";
					$html.="</TR>";
				}
				if(!empty($dat[$x][obsinter]))
				{
					$html.="<TR>";
					$html.="<TD WIDTH='760' HEIGHT=25>OBSERVACION: ".$dat[$x][obsinter]."</TD>";
					$html.="</TR>";
				}
				if(!empty($dat[$x][obsnoqx]))
				{
					$html.="<TR>";
					$html.="<TD WIDTH='760' HEIGHT=25>OBSERVACION: ".$dat[$x][obsnoqx]."</TD>";
					$html.="</TR>";
				}
				$html.="<TR>";
				$html.="<TD WIDTH='380' HEIGHT=25>VALIDA A PARTIR DE : ".FechaStampJ($dat[$x][fecha_activacion])."</TD>";
				$html.="<TD WIDTH='380' HEIGHT=25>FECHA DE VENCIMIENTO: ".FechaStampJ($dat[$x][fecha_vencimiento])."</TD>";
				$html.="</TR>";
				if(!empty($dat[$x][requisitos]))
				{
					$html.="<TR>";
					$html.="<TD WIDTH='760' HEIGHT=25>RECOMENDACIONES : ".$dat[$x][requisitos]."</TD>";
					$html.="</TR>";
				}
				$x++;
				while($dat[$i][cargo_cups]==$dat[$x][cargo_cups])
				{
					$x++;
				}
			}
			$i=$x;
		}
		if(!empty($dat[0][desdpto]))
		{
			$html.="<TR>";
			$html.="<TD WIDTH='760' HEIGHT=25><br><b>PRESTADOR : ".$dat[0][desdpto].' - '.$datos[0][razon_social]."</b></TD>";
			$html.="</TR>";
			$html.="<TR>";
			$ubicacion=$datos[0][direccion];
			if(!empty($dat[0][ubidpto]))
			{  $ubicacion=$dat[0][ubidpto];  }
			$tel=$datos[0][telefonos];
			if(!empty($dat[0][teldpto]))
			{  $tel=$dat[0][teldpto];  }

			$html.="<TD WIDTH='380' HEIGHT=25>DIRECCION : ".$ubicacion."</TD>";
			$html.="<TD WIDTH='380' HEIGHT=25>TELEFONO : ".$tel."</TD>";
			$html.="</TR>";
		}
		elseif(!empty($dat[0][nompro]))
		{
			$html.="<TR>";
			$html.="<TD WIDTH='760' HEIGHT=25><br><b>PRESTADOR: ".$dat[0][nompro]."</b></TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='380' HEIGHT=25>DIRECCION: ".$dat[0][dirpro]."</TD>";
			$html.="<TD WIDTH='380' HEIGHT=25>TELEFONO: ".$dat[0][telpro]."</TD>";
			$html.="</TR>";
		}
		if($dat[0][sw_estado]==7)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='260' HEIGHT=25>NOTA: ".$datos[0][nombre_tercero]." por favor hacer Tr?mite de la Transcripci?n a ".$datos[0][razon_social]."</TD>";
			$html.="</TR>";
		}
		$cargo_liq=array();
		$d=0;
		while($d<sizeof($dat))
		{
				$cargo_liq[]=array('tarifario_id'=>$dat[$d]['tarifario_id'],'cargo'=>$dat[$d]['cargo'],'cantidad'=>1,'autorizacion_int'=>$dat[$d]['autorizacion_int'],'autorizacion_ext'=>$dat[$d]['autorizacion_ext']);
				$d++;
		}
		$cargo_fact=array();
		$cargo_fact=LiquidarCargosCuentaVirtual($cargo_liq,'','','',$datos[0][plan_id] ,$datos[0][tipo_afiliado_id] ,$datos[0][rango] ,$datos[0][semanas_cotizacion],$datos[0][servicio]);
		$copago=$cargo_fact[valor_cuota_paciente];
		$moderadora=$cargo_fact[valor_cuota_moderadora];
		$total=$cargo_fact[valor_total_paciente];
		$nocub=$cargo_fact[valor_no_cubierto];
		if($copago > 0)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='300' HEIGHT=25>PRESTADOR: ".$datos[0][nombre_copago]."</TD>";
			$html.="<TD WIDTH='460' HEIGHT=25>$ ".$copago."</TD>";
			$html.="</TR>";
		}
		if($moderadora > 0)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='300' HEIGHT=25>".$datos[0][nombre_cuota_moderadora]."</TD>";
			$html.="<TD WIDTH='460' HEIGHT=25>$ ".$moderadora."</TD>";
			$html.="</TR>";
		}
		if($nocub > 0)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='300' HEIGHT=25>VALOR NO CUBIERTO</TD>";
			$html.="<TD WIDTH='460' HEIGHT=25>$ ".$nocub."</TD>";
			$html.="</TR>";
		}
		if($total > 0)
		{
			$html.="<TR>";
			$html.="<TD WIDTH='300' HEIGHT=25>TOTAL A PAGAR</TD>";
			$html.="<TD WIDTH='460' HEIGHT=25>$ ".$total."</TD>";
			$html.="</TR>";
		}
		$html.="</TABLE>";
		$pdf->WriteHTML($html);
		//$pdf->SetLineWidth(0.7);
		//$pdf->RoundedRect(7, 5, 202, 120, 3.5, '');
		$pdf->Output($Dir,'F');
		//unset ($_SESSION['ORDENSERVICIO']['DATOS']);
		//unset ($_SESSION['ORDENSERVICIO']['DAT']);
		return true;
	}

	function Profesional($evolucion)
	{
		list($dbconn) = GetDBconn();
		$query = "select c.nombre_tercero
					from hc_evoluciones as a, profesionales_usuarios as b, terceros as c
					where a.evolucion_id=".$evolucion." and a.usuario_id=b.usuario_id and
					b.tipo_tercero_id=c.tipo_id_tercero and b.tercero_id=c.tercero_id";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Guardar en la Base de Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$var=$resulta->fields[0];
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
