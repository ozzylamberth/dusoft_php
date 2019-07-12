<?php

/**
 * $Id: ordenservicio.inc.php,v 1.4 2007/01/17 23:15:34 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

	function GenerarOrden($vector)
	{
		IncludeLib("tarifario_cargos");
		IncludeLib("funciones_facturacion");
		IncludeLib("funciones_central_impresion");
		$datos2[0]=EncabezadoReporteOrden($vector['orden']);
		$dat2=ReporteOrdenServicio($vector['orden']);
		$total=sizeof($dat2);
		if($total<=2)
		{
			$pdf->AddPage();
			$pdf->SetFont('Arial','',8);
			$reporte.=cabecera($datos2);
			$reporte.=cuerpo($dat2,0,$total);
			$reporte.=final($datos2,$dat2);
			$pdf->WriteHTML($reporte);
			//$pdf->SetLineWidth(0.7);
			//$pdf->RoundedRect(7, 5, 202, 120, 3.5, '');//120
		}
		else
		{
			$totalenter=intval($total/3);
			$totalresid=$total%3;
			for($j=0;$j<$totalenter;$j++)
			{
				$pdf->AddPage();
				$pdf->SetFont('Arial','',8);
				$reporte =cabecera($datos2);
				$inicial=$j*3;
				$reporte.=cuerpo($dat,$inicial,3);//datos,numerodemedicamentos,dondeinicia
				$pdf->WriteHTML($reporte);
				//$pdf->SetLineWidth(0.7);
				//$pdf->RoundedRect(7, 5, 202, 120, 3.5, '');//120
			}
			if($totalresid==1 OR $totalresid==2)
			{
				$pdf->AddPage();
				$pdf->SetFont('Arial','',8);
				$reporte =cabecera($datos2);
				$inicial=$j*3;
				$reporte.=cuerpo($datos2,$inicial,$totalresid);
				$reporte.=final($datos2,$dat2);
				$pdf->WriteHTML($reporte);
				//$pdf->SetLineWidth(0.7);
				//$pdf->RoundedRect(7, 5, 202, 120, 3.5, '');//120
			}
			else if($totalresid==0)
			{
				$pdf->AddPage();
				$pdf->SetFont('Arial','',8);
				$reporte =final($datos2);
				$pdf->WriteHTML($reporte);
				//$pdf->SetLineWidth(0.7);
				//$pdf->RoundedRect(7, 5, 202, 120, 3.5, '');//120
			}
		}
		$pdf->Output($Dir,'F');
		return true;
	}

	function cabecera($datos)
	{
		IncludeLib("tarifario_cargos");
		IncludeLib("funciones_facturacion");
		IncludeLib("funciones_central_impresion");
		$fechaI=FechaStampJT($datos[0][fecha_nacimiento]);
		$fechaF=FechaStampJT($datos[0][fecha_cierre]);
		$fechaIngreso=FechaStampJ($datos[0][fecha_ingreso]);
		$fechaEvolucion=FechaStampJ($datos[0][fecha_cierre]);
		$edad=CalcularEdad($fechaI,$fechaF);
		$html ="<TABLE BORDER='0' WIDTH='1520'>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER'>";
		if(is_file('images/logocliente.png'))
		{
			$html.="".$pdf->image('images/logocliente.png',10,6,18)."";
		}
		$html.="</TD>";
		$html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25>";
		$html.="<b>".strtoupper($datos[0][razon_social])."</b>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25>";
		$html.="<b>".$datos[0][tipo_id_tercero].' : '.$datos[0][id]."</b>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD ALIGN='CENTER' WIDTH='760' HEIGHT=25>";
		$html.="<b>ORDEN DE SERVICIO No. ".$dat[0][orden_servicio_id]."</b>";
		$html.="</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='115' HEIGHT=25><br><br>DOCUMENTO:</TD>";
		$html.="<TD WIDTH='275' HEIGHT=25>".$datos[0][tipo_id_paciente].' '.$datos[0][paciente_id]."</TD>";
		$html.="<TD WIDTH='115' HEIGHT=25>HC:</TD>";
		$html.="<TD WIDTH='275' HEIGHT=25>";
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
		$html.="<TD WIDTH='115' HEIGHT=25>NOMBRE:</TD>";
		$nombre = $datos[0]['nombre'];
		$nombre = substr("$nombre", 0, 38);
		$html.="<TD ALIGN='LEFT' WIDTH='275' HEIGHT=22>".strtoupper($nombre)."."."</TD>";
		$html.="<TD WIDTH='115' HEIGHT=25>FECHA INGRESO: </TD>";
		$html.="<TD WIDTH='275' HEIGHT=25>".$fechaIngreso."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='40' HEIGHT=25>EDAD:</TD>";
		$html.="<TD WIDTH='75' HEIGHT=25>".$edad['anos'].' AÑOS'."</TD>";
		$html.="<TD WIDTH='40' HEIGHT=25>SEXO:</TD>";
		$html.="<TD WIDTH='200' HEIGHT=25>".$datos[0]['sexo_id']."</TD>";
		$html.="<TD WIDTH='230' HEIGHT=25>FECHA SOLICITUD: ".$fechaSolicitud."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='115' HEIGHT=25>TIPO DE AFILIADO:</TD>";
		$html.="<TD WIDTH='275' HEIGHT=25>".strtoupper($datos[0]['tipo_afiliado_nombre'])."</TD>";
		$html.="<TD WIDTH='115' HEIGHT=25>RANGO:</TD>";
		$html.="<TD WIDTH='275' HEIGHT=25>".strtoupper($datos[0]['rango'])."</TD>";
		$html.="</TR>";
		$html.="<TR>";
		$html.="<TD WIDTH='115' HEIGHT=25>CLIENTE:</TD>";
		$cliente = $datos[0]['nombre_tercero'];
		$cliente = substr("$cliente", 0, 38);
		$html.="<TD WIDTH='275' HEIGHT=25>".strtoupper($cliente)."."."</TD>";
		$html.="<TD WIDTH='115' HEIGHT=25>PLAN:</TD>";
		$plan = $datos[0]['plan_descripcion'];
		$plan = substr("$plan", 0, 38);
		$html.="<TD WIDTH='275' HEIGHT=25>".strtoupper($plan)."."."</TD>";
		$html.="</TR>";
		if(!empty($datos[0][ingreso]))
		{
			$cama=BuscarCamaActiva($datos[0][ingreso]);
		}
		else
		{
			$res=BuscarCama($vector['orden']);
			$cama=$res[cama];
		}
		$html.="<TR>";
		$html.="<TD WIDTH='200' HEIGHT=25>CAMA: ".$cama."</TD>";
		$html.="<TD WIDTH='300' HEIGHT=25>ATENDIDO : ".$datos[0][usuario_id].' - '.$datos[0][usuario]."</TD>";
		$html.="</TR>";
		if(!empty($datos[0][observacion]))
		{
			$html.="<TR>";
			$html.="<TD WIDTH='760' HEIGHT=25>OBSERVACION: ".$datos[0][observacion]."</TD>";
			$html.="</TR>";
		}
		return $html;
	}

	function cuerpo($dat,$inicio,$cuantos)
	{
		IncludeLib("tarifario_cargos");
		IncludeLib("funciones_facturacion");
		IncludeLib("funciones_central_impresion");
		$html='';
		$total=0;
		$profe='';
		$copago=$moderadora=$nocub=0;
		$limite=$inicio+$cuantos;
		for($i=$inicio;($i<$limite);)
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
				$diag=Diagnostico($dat[$x][evolucion_id]);
				if(!empty($diag))
				{
					$html.="<TR>";
					$html.="<TD WIDTH='760' HEIGHT=25>DIAGNOSTICOS: ".$diag."</TD>";
					$html.="</TR>";
				}
				$inter=$dat[$x][especialidad_nombre];
				$html.="<TR>";
				$html.="<TD WIDTH='760' HEIGHT=25>".$dat[$x][numero_orden_id].' - '.$dat[$x][cargo_cups].' -  ( '.$dat[$i][cantidad].' ) '.$dat[$x][descripcion].' '.$inter."</TD>";
				$html.="</TR>";
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
		return $html;
	}

	function final($datos,$dat)
	{
		IncludeLib("tarifario_cargos");
		IncludeLib("funciones_facturacion");
		IncludeLib("funciones_central_impresion");
		if(!empty($dat[0][desdpto]))
		{
			$html ="<TR>";
			$html.="<TD WIDTH='760' HEIGHT=25><br><b>PRESTADOR : ".$dat[0][desdpto].' - '.$datos[0][razon_social]."</b></TD>";
			$html.="</TR>";
			$html.="<TR>";
			$html.="<TD WIDTH='380' HEIGHT=25>DIRECCION : ".$dat[0][direccion]."</TD>";
			$html.="<TD WIDTH='380' HEIGHT=25>TELEFONO : ".$dat[0][telefonos]."</TD>";
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
		return $html;
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

	function BuscarCama($orden)
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
