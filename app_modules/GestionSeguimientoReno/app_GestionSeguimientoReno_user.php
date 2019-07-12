
<?php

/**
* Modulo de GestionSeguimientoReno (PHP).
*
//*
*
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
**/

/**
* app_GestionSeguimientoReno_user.php
*
//*
**/


class app_GestionSeguimientoReno_user extends classModulo
{
	
	function app_GestionSeguimientoReno_user()
	{
			return true;
	}

	function main()
	{
		$this->PrincipalPYP();
		return true;
	}

	function UsuariosPyP()//Función de permisos
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		if(!empty($_SESSION['SYSTEM_USUARIO_ID']))
		{
				$sql="SELECT B.empresa_id, 
							B.razon_social as desc_emp,
							C.centro_utilidad,
							C.descripcion as desc_cen,
							D.unidad_funcional,
							D.descripcion as desc_uni,
							E.departamento,
							E.descripcion as desc_dept,
							F.tipo_consulta_id,
							F.descripcion as desc_cons,
							H.cargo_cita,
							H.descripcion as desc_cita
							FROM userpermisos_pypadmin AS A, 
							empresas AS B,
							centros_utilidad AS C, 
							unidades_funcionales AS D,
							departamentoS AS E,
							tipos_consulta AS F,
							tipos_consultas_cargos AS G,
							cargos_citas AS H,
							pyp_cpn_seguimiento_consultas AS I,
							userpermisos_tipos_consulta AS J
							WHERE A.usuario_id=".$_SESSION['SYSTEM_USUARIO_ID']."  
							AND A.empresa_id=B.empresa_id
							AND B.empresa_id=C.empresa_id
							AND C.centro_utilidad=D.centro_utilidad
							AND D.unidad_funcional=E.unidad_funcional
							AND E.departamento=F.departamento
							AND F.tipo_consulta_id=G.tipo_consulta_id
							AND G.cargo_cita=H.cargo_cita
							AND I.empresa_id=B.empresa_id
							AND I.centro_utilidad=C.centro_utilidad
							AND I.unidad_funcional=D.unidad_funcional
							AND I.departamento=E.departamento
							AND I.tipo_consulta_id=F.tipo_consulta_id
							AND I.tipo_consulta_id=G.tipo_consulta_id
							AND A.usuario_id=J.usuario_id
							AND I.tipo_consulta_id=J.tipo_consulta_id
							ORDER BY E.departamento";
		}
		else
		{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "El usuario no se ha registrado.";
				return false;
		}
		unset($_SESSION['SEGURIDAD']);
		if(empty($_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][0]))
		{
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($sql);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				$i=0;
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
				}
				else
				{
					while ($data = $result->FetchRow()) 
					{
							$prueba6[$data['desc_emp']][$data['desc_cen']][$data['desc_uni']][$data['desc_dept']]=$data;
							$_SESSION['SEGURIDAD']['EMPRESA_ID']=$data['empresa_id'];
							$_SESSION['SEGURIDAD']['EMPRESA']=$data['desc_emp'];
							$i=1;
					}
				}
		}
		else
		{
			$i=1;
		}
		if($i<>0)
		{
				$mtz1[0]="EMPRESA";
				$mtz1[1]="CENTRO DE UTILIDAD";
				$mtz1[2]="UNIDAD_FUNCIONAL";
				$mtz1[3]="DEPARTAMENTO";
				$com[0]=$mtz1;
				$com[1]=$prueba6;
				$url[0]='app';
				$url[1]='GestionSeguimientoReno';
				$url[2]='user';
				$url[3]='FrmGestionSeguimientoReno';
				$url[4]='SeguimientoReno';
				if(empty($_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][0]))
				{
					$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][]=$mtz1;
					$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][2]=$prueba6;
				}
				$nombre='SELECCIONAR DEPARTAMENTO';
				$accion=ModuloGetURL('system','Menu','user','main');
				$this->salida.=gui_theme_menu_acceso($nombre,$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][0],$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][2],$url,$accion);
				return $com;
		}
		else
		{
				$mensaje = "EL USUARIO NO TIENE PERMISOS PARA ACCEDER AL MODULO.";
				$titulo = "GESTION DE SEGUIMIENTO RENOPROTECCION";
				$boton = "VOLVER";//REGRESAR
				$accion=ModuloGetURL('system','Menu','user','main');
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return false;
		}
		return true;
	}
	
	function IngresarSeguimientoReno()
	{
	
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT nextval('pyp_renoproteccion_seguimient_pyp_renoproteccion_seguimient_seq'::regclass);";
		$result = $dbconn->Execute($query);
		$segumiento_id=$result->fields[0];
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error en el Modulo GestionSeguimientoReno - IngresarSeguimientoReno - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
			$this->ban=1;
			$dbconn->RollBackTrans();
			return false;
		}
		else
		{
			$paciente_id=trim($_REQUEST['seguimiento']['pd']);
			$tipo_id_paciente=trim($_REQUEST['seguimiento']['tpd']);
			
			if(!$_REQUEST['cita_asignada_id'])
				$cita_asignada_id='NULL';
			else
				$cita_asignada_id=$_REQUEST['cita_asignada_id'];
		
			$observacion=$_REQUEST['observacion'];
			$telefono=$_REQUEST['telefono'];
			
			$inscripcion_id=$_REQUEST['seguimiento']['inscripcion_id'];
			$evolucion_id=$_REQUEST['seguimiento']['evolucion_id'];
			
			$motivos=$_REQUEST['motivos'];
			$ips=$_REQUEST['ips'];
			
			$programa=ModuloGetVar('hc_submodulo','AtencionReno','Renoproteccion');
			
			$query="SELECT MAX(pyp_renoproteccion_seguimiento_id) 
							FROM pyp_renoproteccion_seguimiento
							WHERE tipo_id_paciente='$tipo_id_paciente'
							AND paciente_id='$paciente_id'
							AND inscripcion_id=	(
																		SELECT inscripcion_id
																		FROM pyp_inscripciones_pacientes
																		WHERE tipo_id_paciente='$tipo_id_paciente'
																		AND paciente_id='$paciente_id'
																		AND programa_id=$programa
																		AND estado='1'
																	)";
			$result = $dbconn->Execute($query);
			$num_seg=$result->fields[0];
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error en el Modulo GestionSeguimientoReno - InsertarSeguimientoReno - SQL 1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
				$this->ban=1;
				$dbconn->RollBackTrans();
				return false;
			}
			
			if(!$num_seg)
			{
				$query="INSERT INTO pyp_renoproteccion_seguimiento
								(
									pyp_renoproteccion_seguimiento_id,
									paciente_id,
									tipo_id_paciente,
									cita_asignada_id,
									observacion,
									contacto_telefonico,
									fecha_registro,
									inscripcion_id,
									usuario_id,
									evolucion_id,
									ips_direccionada,
									motivos_seguimiento
								)
								VALUES
								(
									$segumiento_id,
									'$paciente_id',
									'$tipo_id_paciente',
									$cita_asignada_id,
									'$observacion',
									'$telefono',
									now(),
									$inscripcion_id,
									".UserGetUID().",
									$evolucion_id,
									'$ips',
									'$motivos'
								);";
				
				$result = $dbconn->Execute($query);
				
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error en el Modulo GestionSeguimientoReno - InsertarSeguimientoReno - SQL 2";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
					$this->ban=1;
					$dbconn->RollBackTrans();
					return false;
				}
			}
			else
			{
					$query=
								"
									UPDATE pyp_renoproteccion_seguimiento
									SET
									observacion='$observacion',
									contacto_telefonico='$telefono',
									ips_direccionada='$ips',
									motivos_seguimiento='$motivos'
									WHERE pyp_renoproteccion_seguimiento_id=$num_seg
								";
								
				$result = $dbconn->Execute($query);
				
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error en el Modulo GestionSeguimientoReno - InsertarSeguimientoReno - SQL 3";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
					$this->ban=1;
					$dbconn->RollBackTrans();
					return false;
				}
			}
		}
		
		$dbconn->CommitTrans();
		
		$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE";
		$this->ban=1;
		$this->FrmSeguimientoReno($_REQUEST['seguimiento'],$_REQUEST['opcion'],$_REQUEST['ordenar']);

		return true;
	}
	
	
	function GetDatosSeguimiento($evolucion,$inscripcion)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$sql="SELECT	a.pyp_renoproteccion_seguimiento_id,
									a.paciente_id,
									a.tipo_id_paciente,
									a.cita_asignada_id,
									a.observacion,
									a.contacto_telefonico,
									a.ips_direccionada as ips,
									a.usuario_id,
									a.motivos_seguimiento,
									TO_CHAR(a.fecha_registro,'YYYY-MM-DD') AS fecha,
									d.descripcion,
									i.sw_estado as estado_cita,
									k.fecha_turno as fecha_contacto
					FROM pyp_renoproteccion_seguimiento AS a
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
					LEFT JOIN system_usuarios as d
					ON
					(
						a.usuario_id=d.usuario_id
					)
					
					WHERE a.inscripcion_id=$inscripcion
					AND a.evolucion_id<=$evolucion
					ORDER BY a.fecha_registro DESC
					";
		
		$result = $dbconn->Execute($sql);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error en el Modulo GestionSeguimientoReno - GetDatosSeguimiento - SQL";
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
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		
		$dbconn->CommitTrans();
		return $vars;
	}
	
	function GetCitasID($paciente_id,$tipo_id_paciente,$cita_padre,$fecha_turno)
	{
	
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$con="";
		if($cita_padre)
		{
			$con="AND a.agenda_cita_id_padre=$cita_padre";
		}
		elseif($fecha_turno)
		{
			$con="AND date(c.fecha_turno)='$fecha_turno' AND date(c.fecha_turno) >= date(now())";
		}
		
		if(!empty($con))
		{
			$sql="
						SELECT a.agenda_cita_asignada_id
						FROM agenda_citas_asignadas AS a
						JOIN agenda_citas AS b
						ON
						(
							a.agenda_cita_id=b.agenda_cita_id
							AND a.agenda_cita_id NOT IN 
																				(
																					SELECT agenda_cita_asignada_id
																					FROM agenda_citas_asignadas_cancelacion
																				)
						)
						JOIN agenda_turnos AS c 
						ON
						(
							b.agenda_turno_id=c.agenda_turno_id
						)
						WHERE a.paciente_id='$paciente_id'
						AND a.tipo_id_paciente='$tipo_id_paciente'
						$con
					";
			
				$result = $dbconn->Execute($sql);
				
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error en el Modulo app_GestionSeguimientoReno - GetCitasID - SQL";
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
							$vars[]=$result->GetRowAssoc($toUpper=false);
							$result->MoveNext();
						}
					}
				}
				
			$dbconn->CommitTrans();
			return $vars;
		}
		return "";
	}
	
	function MonitoreoPacientes($ordenar,$monitoreo)
	{
	
		list($dbconn) = GetDBconn();
		
		if($ordenar==1)
			$order="ORDER BY n.fecha_turno";
		elseif($ordenar==2)
			$order="ORDER BY e.estadio_kdoqi";
		
		if($monitoreo)
		{
			$where="WHERE date(n.fecha_turno) >= date(now())
										AND l.sw_estado!='3'
										OR n.fecha_turno is null";
		}
		else
		{
			$where="WHERE date(n.fecha_turno) < date(now())
							AND l.sw_estado!='3'
							AND n.fecha_turno is not null
							OR e.estadio_kdoqi>=3";
		}
		
		$sqlCont="
						SELECT COUNT(*)
						FROM pacientes as a
						JOIN pyp_inscripciones_pacientes AS b 
						ON
						(
							a.tipo_id_paciente=b.tipo_id_paciente  AND a.paciente_id=b.paciente_id
						)
						JOIN pyp_inscripcion_renoproteccion AS c 
						ON
						(
							b.inscripcion_id=c.inscripcion_id
						)
						JOIN pyp_evoluciones_procesos AS d 
						ON
						(
							c.inscripcion_id=d.inscripcion_id
							AND d.evolucion_id = 
																(
																	SELECT MAX(evolucion_id) 
																	FROM pyp_evoluciones_procesos
																	WHERE inscripcion_id=d.inscripcion_id
																)
						)
						LEFT JOIN pyp_renoproteccion_conducta as e
						ON
						(
							d.inscripcion_id=e.inscripcion_id 
							AND d.evolucion_id=e.evolucion_id
						)
						LEFT JOIN pyp_renoproteccion_codigos_evolucion_valores as f1
						ON
						(
							d.inscripcion_id=f1.inscripcion_id 
							AND d.evolucion_id=f1.evolucion_id
							AND f1.codigo_evolucion_id=1
						)
						LEFT JOIN pyp_renoproteccion_codigos_evolucion_valores as f2
						ON
						(
							d.inscripcion_id=f2.inscripcion_id
							AND d.evolucion_id=f2.evolucion_id
							AND f2.codigo_evolucion_id=3
						)
						LEFT JOIN profesionales_usuarios AS g 
						ON
						(
							g.usuario_id=".UserGetUID()."
						)
						LEFT JOIN profesionales AS h
						ON
						(
							g.tipo_tercero_id=h.tipo_id_tercero AND g.tercero_id=h.tercero_id
						)
						LEFT JOIN pyp_renoproteccion_seguimiento AS i
						ON
						(
							d.inscripcion_id=i.inscripcion_id
							AND d.evolucion_id=i.evolucion_id
						)
						LEFT JOIN agenda_citas_asignadas AS j 
						ON
						(
							i.cita_asignada_id=j.agenda_cita_asignada_id
							AND i.cita_asignada_id NOT IN (
																						SELECT agenda_cita_asignada_id
																						FROM agenda_citas_asignadas_cancelacion
																						)
						)
						LEFT JOIN os_cruce_citas AS k 
						ON
						(
							j.agenda_cita_asignada_id=k.agenda_cita_asignada_id
						)
						LEFT JOIN os_maestro AS l 
						ON
						(
							k.numero_orden_id=l.numero_orden_id
						)
						LEFT JOIN agenda_citas AS m
						ON
						(
							j.agenda_cita_id=m.agenda_cita_id
						)
						LEFT JOIN agenda_turnos AS n 
						ON
						(
							m.agenda_turno_id=n.agenda_turno_id
						)
						JOIN agenda_citas_asignadas AS af  
						ON
						(
							a.tipo_id_paciente=af.tipo_id_paciente 
							AND a.paciente_id=af.paciente_id
							AND af.agenda_cita_asignada_id =
																							(
																								SELECT MAX(agenda_cita_asignada_id)
																								FROM	agenda_citas_asignadas
																								WHERE tipo_id_paciente=a.tipo_id_paciente 
																								AND paciente_id=a.paciente_id
																							)
						)
						JOIN agenda_citas AS df
						ON
						(
							af.agenda_cita_id=df.agenda_cita_id
						)
						JOIN agenda_turnos AS ef 
						ON
						(
							df.agenda_turno_id=ef.agenda_turno_id
							AND ef.tipo_consulta_id=".$_SESSION['SeguimientoReno']['tc_id']."
						)
						$where";
		
		$this->ProcesarSqlConteo($sqlCont);
		
		$query="
					SELECT 
					TO_CHAR(d.fecha_ideal_proxima_cita,'YYYY-MM-DD') as fecha_ideal_proxima_cita,
					a.primer_nombre||' '||a.segundo_nombre ||' '|| a.primer_apellido ||' '|| a.segundo_apellido as nombre_paciente,
					a.residencia_direccion,
					a.residencia_telefono,
					a.paciente_id as pd,
					a.tipo_id_paciente as tpd,
					ef.tipo_consulta_id,
					CASE d.sw_estado
					WHEN '2' THEN 'PRIMERA ATENCION'
					WHEN '3' THEN 'CONTROL'
					WHEN '4' THEN 'CIERRE'
					END as tipo_atencion,
					e.estadio_kdoqi,
					e.riesgo_deterioro_acelerado,
					e.adherencia_farmacologica,
					f1.valor as remision_internista,
					f2.valor as remision_nutricionista,
					h.nombre,
					n.fecha_turno as fecha_contacto,
					l.sw_estado as estado_cita,
					m.agenda_cita_id,
					d.evolucion_id,
					d.inscripcion_id
					FROM pacientes as a
					JOIN pyp_inscripciones_pacientes AS b 
					ON
					(
						a.tipo_id_paciente=b.tipo_id_paciente  AND a.paciente_id=b.paciente_id
					)
					JOIN pyp_inscripcion_renoproteccion AS c 
					ON
					(
						b.inscripcion_id=c.inscripcion_id
					)
					JOIN pyp_evoluciones_procesos AS d 
					ON
					(
						c.inscripcion_id=d.inscripcion_id
						AND d.evolucion_id = 
															(
																SELECT MAX(evolucion_id) 
																FROM pyp_evoluciones_procesos
																WHERE inscripcion_id=c.inscripcion_id
															)
					)
					LEFT JOIN pyp_renoproteccion_conducta as e
					ON
					(
						d.inscripcion_id=e.inscripcion_id 
						AND d.evolucion_id=e.evolucion_id
					)
					LEFT JOIN pyp_renoproteccion_codigos_evolucion_valores as f1
					ON
					(
						d.inscripcion_id=f1.inscripcion_id 
						AND d.evolucion_id=f1.evolucion_id
						AND f1.codigo_evolucion_id=1
					)
					LEFT JOIN pyp_renoproteccion_codigos_evolucion_valores as f2
					ON
					(
						d.inscripcion_id=f2.inscripcion_id
						AND d.evolucion_id=f2.evolucion_id
						AND f2.codigo_evolucion_id=3
					)
					LEFT JOIN profesionales_usuarios AS g 
					ON
					(
						g.usuario_id=".UserGetUID()."
					)
					LEFT JOIN profesionales AS h
					ON
					(
						g.tipo_tercero_id=h.tipo_id_tercero AND g.tercero_id=h.tercero_id
					)
					LEFT JOIN pyp_renoproteccion_seguimiento AS i
					ON
					(
						d.inscripcion_id=i.inscripcion_id
						AND d.evolucion_id=i.evolucion_id
					)
					LEFT JOIN agenda_citas_asignadas AS j 
					ON
					(
						i.cita_asignada_id=j.agenda_cita_asignada_id
						AND i.cita_asignada_id NOT IN (
																					SELECT agenda_cita_asignada_id
																					FROM agenda_citas_asignadas_cancelacion
																					)
					)
					LEFT JOIN os_cruce_citas AS k 
					ON
					(
						j.agenda_cita_asignada_id=k.agenda_cita_asignada_id
					)
					LEFT JOIN os_maestro AS l 
					ON
					(
						k.numero_orden_id=l.numero_orden_id
					)
					LEFT JOIN agenda_citas AS m
					ON
					(
						j.agenda_cita_id=m.agenda_cita_id
					)
					LEFT JOIN agenda_turnos AS n 
					ON
					(
						m.agenda_turno_id=n.agenda_turno_id
					)
					JOIN agenda_citas_asignadas AS af  
					ON
					(
						a.tipo_id_paciente=af.tipo_id_paciente 
						AND a.paciente_id=af.paciente_id
						AND af.agenda_cita_asignada_id =
																						(
																							SELECT MAX(agenda_cita_asignada_id)
																							FROM	agenda_citas_asignadas
																							WHERE tipo_id_paciente=a.tipo_id_paciente 
																							AND paciente_id=a.paciente_id
																						)
					)
					JOIN agenda_citas AS df
					ON
					(
						af.agenda_cita_id=df.agenda_cita_id
					)
					JOIN agenda_turnos AS ef 
					ON
					(
						df.agenda_turno_id=ef.agenda_turno_id
						AND ef.tipo_consulta_id=".$_SESSION['SeguimientoReno']['tc_id']."
					)
					$where
					$order
					LIMIT ".$this->limit." OFFSET ".$this->offset."
				";
				
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error en el Modulo GestionSeguimientoReno - MonitoreoPacientes - SQL";
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
		
		return $vars;
	}
	
	function ProcesarSqlConteo($sqlCont)
	{
		$this->paginaActual = 1;
		$this->offset = 0;
		$this->limit=20;
		
		if($_REQUEST['offset'])
		{
			$this->paginaActual = intval($_REQUEST['offset']);
			if($this->paginaActual > 1)
			{
				$this->offset = ($this->paginaActual - 1) * ($this->limit);
			}
		}
		
		list($dbconn) = GetDBconn();
		
		$result = $dbconn->Execute($sqlCont);
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error en el Modulo GestionSeguimientoReno - ProcesarSqlConteo - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
			$this->ban=1;
			return false;
		}
		else
			$this->conteo=$result->fields[0];
		
		return true;
	}
	
	function ConsultaPermisosTiposConsulta($tipo_consulta)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT *
						FROM userpermisos_tipos_consulta
						WHERE usuario_id=".UserGetUID();
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error en el Modulo app_GestionSeguimientoCPN - ConsultaIPS - SQL";
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
	
}//fin de la clase
?>
