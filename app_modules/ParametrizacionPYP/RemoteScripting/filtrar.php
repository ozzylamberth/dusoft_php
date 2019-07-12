<?php
	/**************************************************************************************
	* $Id: filtrar.php,v 1.2 2007/02/01 19:56:30 luis Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Hugo F. Manrique	
	**************************************************************************************/
	$VISTA = "HTML";
	$_ROOT = "../../../";
	include  $_ROOT."classes/rs_server/rs_server.class.php";
	include	 $_ROOT."includes/enviroment.inc.php";
	include	 $_ROOT."classes/modules/hc_classmodules.class.php";
	$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
	IncludeFile($filename);
	
	class procesos_admin extends rs_server
	{
		function filtroReporte($param)
		{
			$salida.="<form name=\"forma_op\" action=\"\" method=\"post\">";
			
			$salida.=" OPCION ";

			switch($param[0])
			{
				case 1:
					$vector=array('1 VEZ','CONTROL');
				break;
				case 2:
					$vector=array('BAJO','ALTO','SIN RIESGO');
				break;
				case 3:
					$vector=array('BIOLOGICO','PSICOSOCIAL');
				break;
				case 4:
					$vector=array('ITU','CERVICOVAGINITIS','HTA','DIABETES GESTACIONAL');
				break;
				case 5:
					$vector=array('SI','NO');
				break;
				case 6:
					$vector=array('CONTACTO TELEFONICO','DIRECCIONAMIENTO A OTRA IPS','CAPTACION EFECTIVA','CAUSA');
				break;
			}
			
			$salida.="<select name=\"opcion\" class=\"select\" onChange=\"EnviarOpcion(document.forma_op.opcion.value)\">";	
			for($i=0;$i<sizeof($vector);$i++)
			{	
				if($i==0)
					$salida.="	<option value=\"".($i+1)."\" selected>".$vector[$i]."</option>";
				else
					$salida.="	<option value=\"".($i+1)."\">".$vector[$i]."</option>";
			}
			$salida.="</select>";
			
			$salida.="</form>";
			
			return $salida;
		}
		
		function VerSeguimiento($param)
		{
			$datos=$this->GetDatosSeguimiento($param[0]);
			
			$salida="";
			$motivo_seguimiento="";
				
			foreach($datos as $key=>$nivel1)
			{
				foreach($nivel1 as $key1=>$nivel2)
				{
					$motivo_seguimiento.= $nivel2[$key1]['pyp_cpn_motivo_descripcion']." , ";
				}
				
				$salida.= "	<table width=\"100%\" align=\"center\" border=\"0\">";
				$salida.= "			<tr class=\"modulo_list_claro\" align=\"left\">";
				$salida.= "				<td><label class=\"label\">Fecha</label></td>";
				$salida.= "				<td colspan=\"3\">".$nivel2[$key]['fecha']."</td>";
				$salida.= "			</tr>";
				$salida.= "			<tr class=\"modulo_list_oscuro\" align=\"left\">";
				$salida.= "				<td width=\"20%\"><label class=\"label\">paciente</label></td>";
				$salida.= "				<td colspan=\"3\" width=\"80%\">".$nivel2[$key]['nombre_paciente']."</td>";
				$salida.= "			</tr>";
				$salida.= "			<tr class=\"modulo_list_claro\" align=\"left\">";
				$salida.= "				<td><label class=\"label\">Tipo</label></td>";
				$salida.= "				<td width=\"10%\">".$nivel2[$key]['tipo_id_paciente']."</td>";
				$salida.= "				<td width=\"10%\"><label class=\"label\">identificacion</label></td>";
				$salida.= "				<td width=\"60%\">".$nivel2[$key]['paciente_id']."</td>";
				$salida.= "			</tr>";
				$salida.= "			<tr class=\"modulo_list_oscuro\" align=\"left\">";
				$salida.= "				<td><label class=\"label\">Motivos de seguimiento</label></td>";
				$salida.= "				<td colspan=\"3\">".trim($motivo_seguimiento," ,")."</td>";
				$salida.= "			</tr>";
				if($nivel2[$key]['ips_dir'])
				{
					$salida.= "			<tr class=\"hc_table_submodulo_list_title\" align=\"left\">";
					$salida.= "				<td width=\"20%\"><label class=\"label\">Direccionada a IPS</label></td>";
					$salida.= "				<td colspan=\"3\" width=\"80%\"><b>".$nivel2[$key]['ips_dir']."</b></td>";
					$salida.= "			</tr>";
				}
				if($nivel2[$key]['contacto_telefonico'])
				{
					$salida.= "			<tr class=\"hc_table_submodulo_list_title\" align=\"left\">";
					$salida.= "				<td><label class=\"label\">Contacto Telefonico</label></td>";
					$salida.= "				<td colspan=\"3\"><b>".$nivel2[$key]['contacto_telefonico']."</b></td>";
					$salida.= "			</tr>";
				}
				$salida.= "			<tr class=\"modulo_list_oscuro\" align=\"left\">";
				$salida.= "				<td><label class=\"label\">Observaciones</label></td>";
				$salida.= "				<td colspan=\"3\">".$nivel2[$key]['observacion']."</td>";
				$salida.= "			</tr>";
				$salida.= "			<tr class=\"modulo_list_claro\" align=\"left\">";
				$salida.= "				<td><label class=\"label\">Se asigno cita</label></td>";
				
				if($nivel2[$key]['cita_asignada_id'])
				{
					if($nivel2[$key]['sw_estado']=='3')
						$salida.= "				<td colspan=\"3\"><b>Si <label class=\"label_error\">Cumplida</label>- ".$nivel2[$key]['fecha_turno']."</b></td>";
					else
						$salida.= "				<td colspan=\"3\"><b>Si - ".$nivel2[$key]['fecha_turno']."</b></td>";
				}
				else
				{
					$salida.= "				<td colspan=\"3\"><b>No Asignada</b></td>";
				}
					
				$salida.= "			</tr>";
				$salida.= "</table>";
			}
			
			return $salida;
		}
		
		function GetDatosSeguimiento($segumiento_id)
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
	
			$sql="SELECT 
						a.pyp_cpn_seguimiento_id,
						c.pyp_cpn_motivo_seguimiento_id,
						c.pyp_cpn_motivo_descripcion,
						a.paciente_id,
						a.tipo_id_paciente,
						a.cita_asignada_id,
						a.usuario_id,
						a.evolucion_id,
						a.inscripcion_id,
						a.observacion,
						a.contacto_telefonico,
						inscpn.fecha_calulada_parto,
						inscpn.fecha_ultimo_periodo,
						pac.primer_nombre||' '||pac.segundo_nombre ||' '|| pac.primer_apellido ||' '|| pac.segundo_apellido as nombre_paciente,
						a.ips_direccionada as ips_dir,
						f.nombre,
						TO_CHAR(a.fecha_registro,'YYYY-MM-DD') AS fecha,
						i.sw_estado,
						k.fecha_turno
						FROM pacientes as pac
						JOIN pyp_inscripciones_pacientes as insp
						ON
						(
							insp.tipo_id_paciente=pac.tipo_id_paciente
							AND insp.paciente_id=pac.paciente_id
						)
						JOIN pyp_inscripcion_cpn as inscpn
						ON
						(
							insp.inscripcion_id=inscpn.inscripcion_id
						)
						JOIN pyp_evoluciones_procesos as evp
						ON
						(
							insp.inscripcion_id=evp.inscripcion_id
						)
						JOIN pyp_cpn_seguimiento AS a
						ON
						(
							evp.inscripcion_id=a.inscripcion_id
							AND evp.evolucion_id=a.evolucion_id
						)
						JOIN pyp_cpn_motivos_seguimiento AS b
						ON
						(
							a.pyp_cpn_seguimiento_id=b.pyp_cpn_seguimiento_id
						)
						JOIN  pyp_cpn_motivos AS c
						ON
						(
							b.pyp_cpn_motivo_seguimiento_id=c.pyp_cpn_motivo_seguimiento_id
						)
						LEFT JOIN agenda_citas_asignadas AS g
						ON
						(
							a.cita_asignada_id=g.agenda_cita_asignada_id
							AND a.cita_asignada_id NOT IN (
																						SELECT agenda_cita_asignada_id
																						FROM agenda_citas_asignadas_cancelacion
																						)
						)
						LEFT JOIN os_cruce_citas AS h 
						ON
						(
							g.agenda_cita_asignada_id=h.agenda_cita_asignada_id
						)
						LEFT JOIN os_maestro AS i 
						ON
						(
							h.numero_orden_id=i.numero_orden_id
						)
						LEFT JOIN agenda_citas AS j
						ON
						(
							g.agenda_cita_id=j.agenda_cita_id
						)
						LEFT JOIN agenda_turnos AS k 
						ON
						(
							j.agenda_turno_id=k.agenda_turno_id
						)
						,
						pyp_inscripciones_pacientes AS d
						LEFT JOIN profesionales_usuarios AS e
						ON
						(
							d.usuario_id=e.usuario_id
						)
						LEFT JOIN profesionales AS f
						ON
						(
							e.tercero_id=f.tercero_id
							AND e.tipo_tercero_id=f.tipo_id_tercero
						)
						WHERE a.pyp_cpn_seguimiento_id=$segumiento_id
						";
			
			$result = $dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error en el Modulo app_GestionSeguimientoCPN - GetDatosSeguimiento - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
				$this->ban=1;
				$dbconn->RollBackTrans();
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[0]][$result->fields[1]][]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			$dbconn->CommitTrans();
			return $vars;
		}
		
		function Reporte($param)
		{
			$datos=$this->ReporteSeguimientoCitas($param);
			
			$getTheme=SessionGetVar("GetThemePath");
			
			$salida="";
			$k=0;
			
			$salida .= "   <table class=\"normal_10\" width=\"100%\" align=\"center\" border=\"0\">";
			$salida .= "			<tr class=\"modulo_table_list_title\">";
			$salida .= "				<td rowspan=\"2\" width=\"10%\">FECHA CONTACTO</td>";
			$salida .= "				<td rowspan=\"2\" width=\"20%\">NOMBRE</td>";
			$salida .= "				<td colspan=\"2\" width=\"5%\">TIPO ATENCION</td>";
			$salida .= "				<td colspan=\"3\" width=\"10%\">CLASIFICACION RIESGO</td>";
			$salida .= "				<td colspan=\"2\" width=\"10%\">TIPO RIESGO</td>";
			$salida .= "				<td colspan=\"4\" width=\"10%\">PATOLOGIA ASOCIADA</td>";
			$salida .= "				<td colspan=\"2\" width=\"5%\">CUMPLIMIENTO CITA</td>";
			$salida .= "				<td colspan=\"4\" width=\"20%\">ACCION DE SEGUIMIENTO</td>";
			$salida .= "			</tr>";
			$salida .= "			<tr class=\"modulo_table_title\">";
			$salida .= "				<td align=\"center\" width=\"5%\">1 VEZ</td>";
			$salida .= "				<td align=\"center\" width=\"5%\">CONTROL</td>";
			$salida .= "				<td align=\"center\" width=\"3%\">BAJO</td>";
			$salida .= "				<td align=\"center\" width=\"3%\">ALTO</td>";
			$salida .= "				<td align=\"center\" width=\"3%\">SIN RIESGO</td>";
			$salida .= "				<td align=\"center\" width=\"5%\">BIOLOGICO</td>";
			$salida .= "				<td align=\"center\" width=\"5%\">PSICOSOCIAL</td>";
			$salida.= "					<td align=\"center\" width=\"2.5%\">ITU</td>";
			$salida .= "				<td align=\"center\" width=\"2.5%\">CERVICOVAGINITIS</td>";
			$salida .= "				<td align=\"center\" width=\"2.5%\">HTA</td>";
			$salida .= "				<td align=\"center\" width=\"2.5%\">DIABETES GESTASIONAL</td>";
			$salida .= "				<td align=\"center\" width=\"5%\">SI</td>";
			$salida .= "				<td align=\"center\" width=\"5%\">NO</td>";
			$salida .= "				<td align=\"center\" width=\"2.5%\">HALLAZGO EN CONTACTO TELEFONICO</td>";
			$salida .= "				<td align=\"center\" width=\"2.5%\">DIRECCIONAMIENTO A OTRA IPS</td>";
			$salida .= "				<td align=\"center\" width=\"2.5%\">CAPTACION EFECTIVA</td>";
			$salida .= "				<td align=\"center\" width=\"12.5%\">CAUSA</td>";
			$salida .= "			</tr>";
			if(!$datos)
			{
				$salida .= "			<tr class=\"modulo_list_oscuro\">";
				$salida .= "				<td colspan=\"21\" align=\"center\"><label class=\"label_error\">NO SE ENCONTRARON RESGITROS EN LA BUSQUEDA</label></td>";
				$salida .= "			</tr>";
			}
			else
			{
				$k=0;
					
					foreach($datos as $reporte)
					{
						if($k % 2 == 0)
						{
							$estilo='modulo_list_oscuro';
							$background = "#CCCCCC";
						}
						else
						{
							$estilo='modulo_list_claro';
							$background = "#DDDDDD";
						}
						
						$salida .= "			<tr class=\"$estilo\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>";
						$salida .= "				<td align=\"center\" width=\"10%\">".substr($reporte['fecha_contacto'],0,10)."</td>";
						$salida .= "				<td align=\"center\" width=\"20%\">".$reporte['nombre_paciente']."</td>";
						
						if($reporte['tipo_atencion']=='PRIMERA ATENCION')
						{
							$salida .= "				<td align=\"center\" width=\"5%\"><img src=\"".$getTheme."/images/delete.gif\"></td>";
							$salida .= "				<td align=\"center\" width=\"5%\">&nbsp;</td>";	
						}
						elseif($reporte['tipo_atencion']=='CONTROL')
						{
							$salida .= "				<td align=\"center\" width=\"5%\">&nbsp;</td>";	
							$salida .= "				<td align=\"center\" width=\"5%\"><img src=\"".$getTheme."/images/delete.gif\"></td>";
						}
						else
						{
							$salida .= "				<td align=\"center\" width=\"5%\">&nbsp;</td>";
							$salida .= "				<td align=\"center\" width=\"5%\">&nbsp;</td>";	
						}
						
						if($reporte['riesgo']=='BAJO')
						{
							$salida .= "				<td align=\"center\" width=\"3%\"><img src=\"".$getTheme."/images/delete.gif\"></td>";
							$salida .= "				<td align=\"center\" width=\"3%\">&nbsp;</td>";	
							$salida .= "				<td align=\"center\" width=\"3%\">&nbsp;</td>";	
						}
						elseif($reporte['riesgo']=='ALTO')
						{
							$salida .= "				<td align=\"center\" width=\"3%\">&nbsp;</td>";	
							$salida .= "				<td align=\"center\" width=\"3%\"><img src=\"".$getTheme."/images/delete.gif\"></td>";
							$salida .= "				<td align=\"center\" width=\"3%\">&nbsp;</td>";	
						}
						elseif(is_null($reporte['riesgo']))
						{
							$salida .= "				<td align=\"center\" width=\"3%\">&nbsp;</td>";	
							$salida .= "				<td align=\"center\" width=\"3%\">&nbsp;</td>";	
							$salida .= "				<td align=\"center\" width=\"3%\"><img src=\"".$getTheme."/images/delete.gif\"></td>";
						}
						
						if($reporte['biologico'] OR $reporte['psicosocial'])
						{
							$salida .= "				<td align=\"center\">".$reporte['biologico']."</td>";
							$salida .= "				<td align=\"center\">".$reporte['psicosocial']."</td>";
						}
						else
						{
							$salida .= "				<td align=\"center\">&nbsp;</td>";
							$salida .= "				<td align=\"center\">&nbsp;</td>";
						}
						
						if($reporte['itu'])
							$salida .= "				<td align=\"center\"><img src=\"".$getTheme."/images/delete.gif\"></td>";
						else
							$salida .= "				<td align=\"center\">&nbsp;</td>";
						
						if($reporte['cervico'])
							$salida .= "				<td align=\"center\"><img src=\"".$getTheme."/images/delete.gif\"></td>";
						else
							$salida .= "				<td align=\"center\">&nbsp;</td>";
							
						if($reporte['hta'])
							$salida .= "				<td align=\"center\"><img src=\"".$getTheme."/images/delete.gif\"></td>";
						else
							$salida .= "				<td align=\"center\">&nbsp;</td>";
						
						if($reporte['diabetes_gestacional'])
							$salida .= "				<td align=\"center\"><img src=\"".$getTheme."/images/delete.gif\"></td>";
						else
							$salida .= "				<td align=\"center\">&nbsp;</td>";	
							
						$cumplio=0;
						if($reporte['sw_estado'])
						{
							if($reporte['sw_estado']=='3')
							{
								$salida.= "<td align=\"center\"><img src=\"".$getTheme."/images/delete.gif\"></td>";
								$salida.= "<td align=\"center\">&nbsp;</td>";	
								$cumplio=1;
							}
							else
							{
								$salida .= "<td align=\"center\">&nbsp;</td>";
								if($reporte['fecha_turno'] < date("Y-m-d"))
									$salida .= "<td align=\"center\"><img src=\"".$getTheme."/images/delete.gif\"></td>";
								else
									$salida .= "<td align=\"center\">&nbsp;</td>";
							}
						}
						else
						{
							$salida .= "<td align=\"center\">&nbsp;</td>";	
							$salida .= "<td align=\"center\">&nbsp;</td>";
						}
						
						if($reporte['contacto_telefonico'])
							$ver1="<a href=\"javascript:Inicio('".$reporte['seguimiento_id']."','capa1$k')\">Ver</a>";
						else
							$ver1="&nbsp;";
							
						$salida .= "				<td align=\"center\">$ver1</td>";	
						
						if($reporte['ips_dir'])
							$ver2="<a href=\"javascript:Inicio('".$reporte['seguimiento_id']."','capa2$k')\">Ver</a>";
						else
							$ver2="&nbsp;";
						
						$salida .= "				<td align=\"center\">$ver2</td>";
						
						if($cumplio==1 AND (!empty($reporte['contacto_telefonico']) OR !empty($reporte['ips_dir'])))
							$ver3="<a href=\"javascript:Inicio('".$reporte['seguimiento_id']."','capa3$k')\">Ver</a>";
						else
							$ver3="&nbsp;";	
							
						$salida .= "				<td align=\"center\">$ver3</td>";
						$salida .= "				<td align=\"center\">".strtoupper($reporte['observacion'])."</td>";
						$salida .= "			</tr>";
						
						$k++;
					}
				
			}
			$salida .= "	</table>";
			
			$_SESSION['DATOS_REPORTE_SEGUIMIENTO_CPN']=$datos;
			
			return $salida;
		}
		
		function ReporteSeguimientoCitas($param)
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			
			$this->ProcesarSqlConteo();
			
			$fecha="";
			$filtro="";
			$order="";
			
			$fecha_ini=$this->FechaStamp($param[0]);
			$fecha_fin=$this->FechaStamp($param[1]);
	
			if(!empty($fecha_ini) and !empty($fecha_fin))
			{
				$fecha="AND i.fecha_registro>='$fecha_ini' AND i.fecha_registro<='$fecha_fin'";
			}
			switch($param[2])
			{
				case 1:
						switch($param[3])
						{
							case 1:
								$filtro="AND d.sw_estado='2'";
							break;
							case 2:
								$filtro="AND d.sw_estado='3'";
							break;
						}
				break;
				case 2:
					switch($param[3])
					{
						case 1:
							$filtro="AND f.clasificacion_riesgo='1'";
						break;
						case 2:
							$filtro="AND f.clasificacion_riesgo='2'";
						break;
						case 3:
							$filtro="AND f.clasificacion_riesgo is null";
						break;
					}
				break;
				case 3:
					switch($param[3])
					{
					case 1:
						$filtro="AND f.riesgo_biologico >= 0";
					break;
					case 2:
						$filtro="AND f.riesgo_psicosocial >= 0";
					break;
					}
				break;
				case 4:
						switch($param[3])
						{
							case 1:
								$filtro="AND lower(pdiag.desc_cpn)='itu'";
							break;
							case 2:
								$filtro="AND lower(pdiag1.desc_cpn)='cervicovaginitis'";
							break;
							case 3:
								$filtro="AND e1.valor is not null";
							break;
							case 4:
								$filtro="AND e.valor is not null";
							break;
						}
				break;
				case 5:
						switch($param[3])
						{
							case 1:
								$filtro="AND n.sw_estado='3'";
							break;
							case 2:
								$filtro="AND n.sw_estado!='3' AND n.sw_estado is not null";
							break;
						}
				break;
				case 6:
					switch($param[3])
						{
							case 1:
								$filtro="AND i.contacto_telefonico is not null";
							break;
							case 2:
								$filtro="AND i.ips_direccionada!=''";
							break;
							case 3:
								$filtro="AND n.sw_estado='3' AND (i.contacto_telefonico is not null OR i.ips_direccionada is not null)";
							break;
							case 4:
								$filtro="AND i.observacion is not null";
							break;
						}
				break;
			}
	
			switch($param[4])
			{
				case 1:
					$order="ORDER BY b.primer_nombre||' '||b.segundo_nombre ||' '|| b.primer_apellido ||' '|| b.segundo_apellido";
				break;
				case 2:
					$order="ORDER BY i.fecha_registro";
				break;
			}
			
			$query="	SELECT 
							DISTINCT a.inscripcion_id,
							d.fecha_ideal_proxima_cita,
							c.fecha_calulada_parto,
							c.fecha_ultimo_periodo,
							b.primer_nombre||' '||b.segundo_nombre ||' '|| b.primer_apellido ||' '|| b.segundo_apellido as nombre_paciente,
							b.residencia_direccion,
							b.residencia_telefono,
							a.paciente_id as pd,
							a.tipo_id_paciente as tpd,
							h.nombre,
							a.estado,
							e.semana,
							pdiag.desc_cpn as itu,
							pdiag1.desc_cpn as cervico,
							e1.valor as hta,
							e.valor as diabetes_gestacional,
							f.riesgo_biologico as biologico,
							f.riesgo_psicosocial as psicosocial,
							f1.valor as remision1,
							f2.valor as remision2,
							p.fecha_turno,
							n.sw_estado,
							i.ips_direccionada as ips_dir,
							i.pyp_cpn_seguimiento_id as seguimiento_id,
							i.fecha_registro as fecha_contacto,
							i.contacto_telefonico,
							i.evolucion_id,
							i.observacion,
							CASE f.clasificacion_riesgo 
							WHEN 1 THEN 'BAJO'
							WHEN 2 THEN 'ALTO'
							END as riesgo,
							CASE d.sw_estado
							WHEN '1' THEN 'INSCRITO SIN ATENCION'
							WHEN '2' THEN 'PRIMERA ATENCION'
							WHEN '3' THEN 'CONTROL'
							WHEN '4' THEN 'CIERRE'
							END as tipo_atencion
							FROM pacientes as b
							JOIN pyp_inscripciones_pacientes AS a 
							ON
							(
								a.tipo_id_paciente=b.tipo_id_paciente  AND a.paciente_id=b.paciente_id
							)
							JOIN pyp_inscripcion_cpn AS c 
							ON
							(
								c.inscripcion_id=a.inscripcion_id
							)
							JOIN pyp_evoluciones_procesos AS d 
							ON
							(
								d.inscripcion_id=c.inscripcion_id
							)
							LEFT JOIN pyp_cpn_registro_riesgo_evolucion as e
							ON
							(
								d.inscripcion_id=e.inscripcion_id 
								AND e.evolucion_id=d.evolucion_id
								AND e.riesgo_id=3
							)
							LEFT JOIN pyp_cpn_registro_riesgo_evolucion as e1
							ON
							(
								d.inscripcion_id=e1.inscripcion_id 
								AND e1.evolucion_id=d.evolucion_id
								AND e1.riesgo_id=12
							)
							LEFT JOIN pyp_cpn_conducta as f
							ON
							(
								d.inscripcion_id=f.inscripcion_id 
								AND f.evolucion_id=d.evolucion_id
							)
							LEFT JOIN pyp_cpn_codigos_evolucion_gestacion_valores as f1
							ON
							(
								d.inscripcion_id=f1.inscripcion_id 
								AND f1.evolucion_id=d.evolucion_id
								AND f1.codigo_evolucion_id=5
							)
							LEFT JOIN pyp_cpn_codigos_evolucion_gestacion_valores as f2
							ON
							(
								d.inscripcion_id=f2.inscripcion_id 
								AND f2.evolucion_id=d.evolucion_id
								AND f2.codigo_evolucion_id=6
							)
							LEFT JOIN profesionales_usuarios AS g 
							ON
							(
								a.usuario_id=g.usuario_id
							)
							LEFT JOIN profesionales AS h 
							ON
							(
								g.tipo_tercero_id=h.tipo_id_tercero AND g.tercero_id=h.tercero_id
							)
							JOIN pyp_cpn_seguimiento AS i
							ON
							(
								d.inscripcion_id=i.inscripcion_id
								AND d.evolucion_id=i.evolucion_id
							)
							LEFT JOIN pyp_cpn_motivos_seguimiento AS j
							ON
							(
								i.pyp_cpn_seguimiento_id=j.pyp_cpn_seguimiento_id
							)
							LEFT JOIN  pyp_cpn_motivos AS k
							ON
							(
								j.pyp_cpn_motivo_seguimiento_id=k.pyp_cpn_motivo_seguimiento_id
							)
							LEFT JOIN agenda_citas_asignadas AS l 
							ON
							(
								i.cita_asignada_id=l.agenda_cita_asignada_id
								AND i.cita_asignada_id NOT IN (
																							SELECT agenda_cita_asignada_id
																							FROM agenda_citas_asignadas_cancelacion
																							)
							)
							LEFT JOIN os_cruce_citas AS m 
							ON
							(
								l.agenda_cita_asignada_id=m.agenda_cita_asignada_id
							)
							LEFT JOIN os_maestro AS n 
							ON
							(
								m.numero_orden_id=n.numero_orden_id
							)
							LEFT JOIN agenda_citas AS o
							ON
							(
								l.agenda_cita_id=o.agenda_cita_id
							)
							LEFT JOIN agenda_turnos AS p 
							ON
							(
								o.agenda_turno_id=p.agenda_turno_id
							)
							LEFT JOIN hc_diagnosticos_ingreso as diagin
							ON
							(
								d.evolucion_id=diagin.evolucion_id
							)
							LEFT JOIN hc_diagnosticos_egreso as diageg
							ON
							(
								d.evolucion_id=diageg.evolucion_id
								
							)
							LEFT JOIN diagnosticos as diag
							ON
							(
								diag.diagnostico_id=diagin.tipo_diagnostico_id
								OR diag.diagnostico_id=diageg.tipo_diagnostico_id
							)
							LEFT JOIN pyp_diagnosticos as pdiag
							ON
							(
								diag.diagnostico_id=pdiag.diagnostico_id
								AND lower(pdiag.desc_cpn)='itu'
							)
							LEFT JOIN pyp_diagnosticos as pdiag1
							ON
							(
								diag.diagnostico_id=pdiag1.diagnostico_id
								AND lower(pdiag1.desc_cpn)='cervicovaginitis'
							)
						WHERE	p.fecha_turno is not null
						$fecha
						$filtro
						$order
						LIMIT ".$this->limit." OFFSET ".$this->offset."";
	
			$result = $dbconn->Execute($query);
			
			$this->conteo=$result->RecordCount();
			
			if ($dbconn->ErrorNo() != 0) 
			{
				$this->error = "Error en el Modulo app_ParametrizacionPYP - ReporteSegumientoCitas - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
				$this->ban=1;
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			$dbconn->CommitTrans();
			
			return $vars;
		}
		
		function ProcesarSqlConteo()
		{
			$this->paginaActual = 1;
			$this->offset = 0;
			$this->limit = 20;
			
			if($_REQUEST['offset'])
			{
				$this->paginaActual = intval($_REQUEST['offset']);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}
			
			return true;
		}
		
		function FechaStamp($fecha)
		{
			if($fecha)
			{
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}
				
				return  ceil($date[2])."-".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."-".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
			}
		}
		
		
	}
	$oRS = new procesos_admin();
	$oRS->action();	
?>