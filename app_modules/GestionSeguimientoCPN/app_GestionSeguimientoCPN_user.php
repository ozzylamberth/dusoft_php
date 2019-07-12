
<?php

/**
* Modulo de GestionSeguimientoCPN (PHP).
*
//*
*
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
**/

/**
* app_GestionSeguimientoCPN_user.php
*
//*
**/


class app_GestionSeguimientoCPN_user extends classModulo
{
	
	function app_GestionSeguimientoCPN_user()
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
				$url[1]='GestionSeguimientoCPN';
				$url[2]='user';
				$url[3]='FrmGestionSeguimientoCPN';
				$url[4]='SeguimientoCPN';
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
				$titulo = "GESTION DE SEGUIMIENTO CPN";
				$boton = "VOLVER";//REGRESAR
				$accion=ModuloGetURL('system','Menu','user','main');
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return false;
		}
		return true;
	}
	
	function GetMotivosSeguimiento()
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT 	pyp_cpn_motivo_seguimiento_id AS motivo_id,
										pyp_cpn_motivo_descripcion AS descripcion
						FROM pyp_cpn_motivos";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error en el Modulo app_GestionSeguimientoCPN - GetMotivosSeguimiento - SQL";
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
	
	function IngresarSeguimientoCPN()
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT nextval('pyp_cpn_seguimiento_pyp_cpn_seguimiento_id_seq'::regclass);";
						
		$result = $dbconn->Execute($query);
		
		$segumiento_id=$result->fields[0];
		
		$paciente_id=str_replace(" ","",$_REQUEST['seguimiento']['pd']);
		$tipo_id_paciente=str_replace(" ","",$_REQUEST['seguimiento']['tpd']);
		
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
		
		$programa=ModuloGetVar('hc_submodulo','AtencionCPN','cpn');
			
		$query="SELECT MAX(pyp_cpn_seguimiento_id) 
						FROM pyp_cpn_seguimiento
						WHERE tipo_id_paciente='$tipo_id_paciente'
						AND paciente_id='$paciente_id'
						AND inscripcion_id=	
																(
																	SELECT inscripcion_id
																	FROM pyp_inscripciones_pacientes
																	WHERE tipo_id_paciente='$tipo_id_paciente'
																	AND paciente_id='$paciente_id'
																	AND programa_id=$programa
																	AND estado='1'
																)";
		$result = $dbconn->Execute($query);
		$num_seg=$result->fields[0];
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error en el Modulo GestionSeguimientoCPN - InsertarSeguimientoCPN - SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
			$this->ban=1;
			$dbconn->RollBackTrans();
			return false;
		}
		
		if(!$num_seg)
		{
			$query="INSERT INTO pyp_cpn_seguimiento
							(
								pyp_cpn_seguimiento_id,
								paciente_id,
								tipo_id_paciente,
								cita_asignada_id,
								observacion,
								contacto_telefonico,
								fecha_registro,
								inscripcion_id,
								usuario_id,
								evolucion_id,
								ips_direccionada
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
								'$ips'
							);";
			
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error en el Modulo GestionSeguimientoCPN - InsertarSeguimientoCPN - SQL 2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
				$this->ban=1;
				$dbconn->RollBackTrans();
				return false;
			}
		}
		else
		{
				//$segumiento_id=$result->fields[0];
				
				$query=
							"
								UPDATE pyp_cpn_seguimiento
								SET
								observacion='$observacion',
								contacto_telefonico='$telefono',
								ips_direccionada='$ips'
								WHERE pyp_cpn_seguimiento_id=$num_seg
							";
							
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error en el Modulo GestionSeguimientoCPN - InsertarSeguimientoCPN - SQL 3";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
				$this->ban=1;
				$dbconn->RollBackTrans();
				return false;
			}
		}

		for($i=0;$i<sizeof($motivos);$i++)
		{
			$query="INSERT INTO pyp_cpn_motivos_seguimiento
							(
								pyp_cpn_motivo_seguimiento_id,
								pyp_cpn_seguimiento_id
							)
							VALUES 
							(
								".$motivos[$i].",
								$segumiento_id
							);";
			
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0) 
			{
				$this->error = "Error en el Modulo app_GestionSeguimientoCPN - InsertarSeguimientoCPN - SQL4_$i";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
				$this->ban=1;
				$dbconn->RollBackTrans();
				return false;
			}
		}

		$dbconn->CommitTrans();
		
		$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE";
		$this->ban=1;
		$this->FrmSeguimientoCPN($_REQUEST['seguimiento'],$_REQUEST['opcion']);

		return true;
	}
	
	
	function GetDatosSeguimiento($evolucion,$inscripcion)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$sql="SELECT a.pyp_cpn_seguimiento_id,
									a.paciente_id,
									a.tipo_id_paciente,
									a.cita_asignada_id,
									a.observacion,
									a.contacto_telefonico,
									c.pyp_cpn_motivo_seguimiento_id,
									c.pyp_cpn_motivo_descripcion,
									f.nombre as profesional,
									a.ips_direccionada as ips,
									TO_CHAR(a.fecha_registro,'YYYY-MM-DD') AS fecha,
									i.sw_estado,
									k.fecha_turno,
									a.usuario_id,
									a.evolucion_id,
									a.inscripcion_id
					FROM pyp_cpn_seguimiento AS a
					LEFT JOIN pyp_cpn_motivos_seguimiento AS b
					ON
					(
						a.pyp_cpn_seguimiento_id=b.pyp_cpn_seguimiento_id
					)
					LEFT JOIN  pyp_cpn_motivos AS c
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
						AND 
						e.tipo_tercero_id=f.tipo_id_tercero
					)
					WHERE a.inscripcion_id=$inscripcion
					AND a.evolucion_id<=$evolucion
					ORDER BY a.fecha_registro DESC
					";
		
		
		$result = $dbconn->Execute($sql);
		
		if($dbconn->ErrorNo() != 0)
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
					$vars[$result->fields[0]][$result->fields[6]]=$result->GetRowAssoc($toUpper=false);
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
					$this->error = "Error en el Modulo app_GestionSeguimientoCPN - GetCitasID - SQL";
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
	
	function MonitoreoPacientes($filtro,$monitoreo)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$order="";
		$where="";
		$agenda="";
		
		if($filtro=='1')
		{
			$order="ORDER BY T1.fecha_turno asc";
		}
		else if($filtro=='2')
		{
			$order="ORDER BY T1.fecha_calulada_parto asc";
		}
		
		if($monitoreo)
		{
			$where = "WHERE date(T1.fecha_turno)>= date(now()) 
								AND T1.sw_estado!='3'
								OR T1.fecha_turno is null";
		}
		else
		{
			$where = "WHERE date(T1.fecha_turno) < date(now()) 
								AND T1.sw_estado!='3' 
								AND T1.fecha_turno is not null
								OR lower(T1.riesgo)='alto'";
		}
		
		$query="
						SELECT T1.*	
						FROM
						(
							SELECT DISTINCT
							a.inscripcion_id,
							TO_CHAR(d.fecha_ideal_proxima_cita,'YYYY-MM-DD') as fecha_ideal_proxima_cita,
							TO_CHAR(c.fecha_calulada_parto,'YYYY-MM-DD') as fecha_calulada_parto,
							c.fecha_ultimo_periodo,
							b.primer_nombre||' '||b.segundo_nombre ||' '|| b.primer_apellido ||' '|| b.segundo_apellido as nombre_paciente,
							b.residencia_direccion,
							b.residencia_telefono,
							a.paciente_id as pd,
							a.tipo_id_paciente as tpd,
							h.nombre,
							a.estado,
							d.evolucion_id,
							e.semana,
							e1.valor as hta,
							e.valor as diabetes_gestacional,
							f1.valor as remision1,
							f2.valor as remision2,
							p.fecha_turno,
							n.sw_estado,
							o.agenda_cita_id,
							ef.tipo_consulta_id,
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
								a.tipo_id_paciente=b.tipo_id_paciente 
								AND a.paciente_id=b.paciente_id
							)
							LEFT JOIN pyp_inscripcion_cpn AS c 
							ON
							(
								c.inscripcion_id=a.inscripcion_id
							)
							JOIN pyp_evoluciones_procesos AS d 
							ON
							(
								d.inscripcion_id=c.inscripcion_id
								AND d.evolucion_id = 
																	(
																		SELECT MAX(evolucion_id) 
																		FROM pyp_evoluciones_procesos
																		WHERE inscripcion_id=d.inscripcion_id
																	)
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
							LEFT JOIN pyp_cpn_seguimiento AS i
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
								AND ef.tipo_consulta_id=".$_SESSION['SeguimientoCPN']['tc_id']."
							)
						)AS T1
						$where
						$order";

		$result1 = $dbconn->Execute($query);
		
		$this->ProcesarSqlConteo($result1->RecordCount());
		
		$query1 = $query." LIMIT ".$this->limit." OFFSET ".$this->offset."";
		
		$result = $dbconn->Execute($query1);
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error en el Modulo app_GestionSeguimientoCPN - MonitoreoPacientes - SQL";
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
	
	function ProcesarSqlConteo($cont)
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
		
		$this->conteo=$cont;
		
		return true;
	}
	
	function ObtenerLimite()
	{
		$uid = UserGetUID();
		$this->limit = UserGetVar($uid,'LimitRows');
		/*if(empty($this->limit) || is_null($this->limit))
		{
			UserSetVar($uid,'LimitRows','10');
			$this->limit = UserGetVar($uid,'LimitRows');
		}*/

		return true;
	}
	
	function GetMotivo()
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
			
		$query="SELECT pyp_cpn_motivo_seguimiento_id as motivo_id,prioridad,
						pyp_cpn_motivo_descripcion as motivo
						FROM pyp_cpn_motivos";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error en el Modulo app_GestionSeguimientoCPN - GetMotivo - SQL";
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
	
	function GetMaxPr()
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT max(prioridad)
						FROM pyp_cpn_motivos";
						
		$result = $dbconn->Execute($query);
		
		$pr=$result->fields[0];
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error en el Modulo app_GestionSeguimientoCPN - GetMaxPr - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
			$this->ban=1;
			return false;
		}
		
		$dbconn->CommitTrans();
		
		return $pr;
	}
	
	
	function GetDiagnosticosPyp($evolucion_id)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT
						a.diagnostico_id,
						b.diagnostico_nombre,
						a.desc_cpn,
						CASE a.tipo_seguimiento
						WHEN 1 THEN 'SEGUIMIENTO URGENTE'
						WHEN 2 THEN 'SEGUIMIENTO REGULAR'
						WHEN 3 THEN 'SEGUIMIENTO PRIORITARIO'
						WHEN 4 THEN 'SEGUIMIENTO'
						END as tipo_seguimiento
						FROM pyp_diagnosticos as a
						JOIN diagnosticos as b 
						ON
						(
							a.diagnostico_id=b.diagnostico_id
						)
						LEFT JOIN hc_diagnosticos_ingreso as c 
						ON
						(
							b.diagnostico_id=c.tipo_diagnostico_id
						)
						LEFT JOIN hc_diagnosticos_egreso as d 
						ON
						(
							b.diagnostico_id=d.tipo_diagnostico_id
							AND 
							c.evolucion_id=d.evolucion_id
						)
						WHERE c.evolucion_id = $evolucion_id";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error en el Modulo app_GestionSeguimientoCPN - GetDiagnosticosPyp - SQL";
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
			$this->error = "Error en el Modulo app_GestionSeguimientoCPN - ConsultaPermisosTiposConsulta - SQL";
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
	
	
	function ConsultaIPS()
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$query="SELECT unidad_funcional,centro_utilidad,descripcion
						FROM unidades_funcionales
						WHERE empresa_id='".$_SESSION['SEGURIDAD']['EMPRESA_ID']."'";
		
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
	
	
	/**
	* Funcion que calcula y  retorna las semanas de gestacion de una paciente, sacando la fecha
	* inicial desde la tabla gestacion, mediante el campo (FUM -->fecha ultima mestruacion), la cual llega
	* a la variable $FechaIni y con la fecha actual, la cual es $FechaFin, se sacan las semanas de la paciente.
	* @return boolean
	* @param date fecha sacada de la tabla gestacion(campo --> fum)
	*/
		
	function CalcularSemanasGestante($FechaIni,$FechaFin='')
	{
		if(empty($FechaFin))
			$FechaFin=date("Y-m-d");
		
		$fech=strtok($FechaIni,"-");
		for($i=0;$i<3;$i++)
		{
				$date[$i]=$fech;
				$fech=strtok("-");
		}
		$fech=strtok($FechaFin,"-");
		for($i=0;$i<3;$i++)
		{
				$date1[$i]=$fech;
			$fech=strtok("-");
		}
		$edad=(ceil($date1[0])-$date[0]);
		$meses=$date1[1]-$date[1];
		$dias=$date1[2]-$date[2];
		$total=($edad*378)+($meses*31.5)+$dias;
		$meses1=(($total%378)/30);
		$meses1=$meses1*4.5;
		return $meses1;
	}
	
}//fin de la clase
?>
