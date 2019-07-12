<?php


/**
* Modulo de Creacion Agenda
*
* Modulo para crear la agenda de los profesionales, para poder realizar la asignacion de citas
* @author Jaime Andres Valencia Salazar <salazarvaljandresv@yahoo.es>
* @version 1.0
* @package SIIS
*/


/**
* CreacionAgenda
*
* Clase para accesar los metodos privados de la clase de presentaciï¿½, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserciï¿½ y la consulta de la creacion de agenda.
*
*/


class app_CreacionAgenda_user extends classModulo
{


/**
* Esta funcion Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function app_CreacionAgenda_user()
	{
		return true;
	}


/**
* Esta funcion es la que llama la funcion para mostrar las acciones que puede realizar el usuario
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function main()
	{
    if(!$this->Menu())
		{
			return false;
		}
		return true;
	}


/**
* Esta funcion muestra el listado de permisos que tiene el usuario.
*
* @access public
* @return boolean Para identificar que se realizo.
* @param string direccion donde debe conectar despues de recoger los permisos
*/

	function CitaConsulta($url)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		if(!empty($_SESSION['SYSTEM_USUARIO_ID']))
		{
			$sql="select b.tipo_consulta_id, e.descripcion as descripcion3, b.departamento, c.descripcion as descripcion2, d.empresa_id, d.razon_social as descripcion1 from userpermisos_creacion_agenda as a, tipos_consulta as b, departamentos as c, empresas as d, tipos_servicios_ambulatorios as e where a.tipo_consulta_id=b.tipo_consulta_id and a.usuario_id=".$_SESSION['SYSTEM_USUARIO_ID']." and b.departamento=c.departamento and c.empresa_id=d.empresa_id and b.tipo_consulta_id=e.tipo_servicio_amb_id order by empresa_id,departamento,tipo_consulta_id;";
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "EL USUARIO NO SE HA REGISTRADO.";
			return false;
		}
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
			while ($data = $result->FetchRow()) {
				$prueba4[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
			}
$i=1;
		}
		if($i<>0)
		{
			$mtz[0]='Empresa';
			$mtz[1]='Departamento';
			$mtz[2]='Tipos de Cita';
			$accion=ModuloGetURL('app','CreacionAgenda','user','main');
			$this->salida.=gui_theme_menu_acceso('MATRIZ DE PERMISOS ADMINISTRATIVOS',$mtz,$prueba4,$url,$accion);
			return true;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "EL USUARIO NO TIENE EMPRESAS PARA MOSTRAR.";
			return false;
		}
	}



/**
* Esta funcion retorna los profesionales a los que se le puede crear agenda
*
* @access public
* @return array retorna el vector con los profesionales a los que se les puede crear agenda.
*/


	function Profesionales()
	{
		list($dbconn) = GetDBconn();
		$sql="select a.tipo_id_tercero, a.tercero_id, b.tipo_id_tercero, b.tercero_id, e.nombre_tercero as nombre, d.estado from profesionales_empresas as a left join (select tipo_id_tercero, tercero_id from profesionales_especialidades as a join (select especialidad from tipos_consulta where departamento='".$_SESSION['CreacionAgenda']['departamento']."' and tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita'].") as b on (a.especialidad=b.especialidad)) as b on (a.tipo_id_tercero=b.tipo_id_tercero and a.tercero_id=b.tercero_id) join profesionales as c on (a.tipo_id_tercero=c.tipo_id_tercero and a.tercero_id=c.tercero_id)  left join profesionales_estado as d on(a.tipo_id_tercero=d.tipo_id_tercero and a.tercero_id=d.tercero_id and d.empresa_id='".$_SESSION['CreacionAgenda']['empresa']."' and d.departamento='".$_SESSION['CreacionAgenda']['departamento']."')
		left join terceros as e on(c.tipo_id_tercero=e.tipo_id_tercero and 	      c.tercero_id=e.tercero_id)
		 where a.empresa_id='".$_SESSION['CreacionAgenda']['empresa']."' order by e.nombre_tercero;";
		$result = $dbconn->Execute($sql);
		$i=$t=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				if($result->fields[5]==1 or (empty($result->fields[5]) and !($result->fields[5]==='0')))
				{
					$profesionales[0][$i]=$result->fields[0];
					$profesionales[1][$i]=$result->fields[1];
					$profesionales[2][$i]=$result->fields[4];
					if($result->fields[2]!='' and $result->fields[3]!='')
					{
						$profesionales2[0][$t]=$result->fields[2];
						$profesionales2[1][$t]=$result->fields[3];
						$profesionales2[2][$t]=$result->fields[4];
						$t++;
					}
					$i++;
				}
				$result->MoveNext();
			}
		}
		$result->close();
		$sql="select especialidad from tipos_consulta where tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita'].";";
		$result = $dbconn->Execute($sql);
		//print_r($result);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if($i<>0)
		{
			if($profesionales2!='' or !empty($result->fields[0]))
			{
				return $profesionales2;
			}
			else
			{
				return $profesionales;
			}
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "NO EXISTEN PROFESIONALES PARA ESA EMPRESA.";
			return false;
		}
	}




/**
* Esta funcion retorna los profesionales que tienen agenda del dia de hoy hacia adelante
*
* @access public
* @return array retorna el vector con los profesionales.
*/


	function Profesionales2()
	{
		list($dbconn) = GetDBconn();
		$sql="select distinct(tipo_id_profesional), profesional_id, c.nombre_tercero as nombre from agenda_turnos as a, profesionales as b, terceros as c, agenda_citas as d where empresa_id='".$_SESSION['BorrarAgenda']['empresa']."' and tipo_consulta_id=".$_SESSION['BorrarAgenda']['Cita']." and a.tipo_id_profesional=b.tipo_id_tercero and a.profesional_id=b.tercero_id and date(fecha_turno)>=date(now()) and c.tipo_id_tercero=b.tipo_id_tercero and c.tercero_id=b.tercero_id and a.sw_estado_cancelacion=0 and a.agenda_turno_id=d.agenda_turno_id and d.sw_estado_cancelacion=0;";
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$profesionales[0][$i]=$result->fields[0];
				$profesionales[1][$i]=$result->fields[1];
				$profesionales[2][$i]=$result->fields[2];
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $profesionales;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "NO EXISTEN PROFESIONALES CON AGENDA PARA ESA EMPRESA.";
			return false;
		}
	}



/**
* Esta funcion retorna el listado de los turnos de un profesional
*
* @access public
* @return array retorna el vector con los turnos de un profesional.
*/


	function ListadoTurnosMes()
	{
		list($dbconn) = GetDBconn();
		$sql="select distinct fecha_turno, a.agenda_turno_id from agenda_turnos as a, agenda_citas as b where empresa_id='".$_SESSION['BorrarAgenda']['empresa']."' and tipo_consulta_id=".$_SESSION['BorrarAgenda']['Cita']." and date(fecha_turno)>=date(now()) and tipo_id_profesional='".$_SESSION['BorrarAgenda']['DatosProf']['tipoid']."' and profesional_id='".$_SESSION['BorrarAgenda']['DatosProf']['tercero']."' and a.sw_estado_cancelacion=0 and a.agenda_turno_id=b.agenda_turno_id and b.sw_estado_cancelacion=0 order by fecha_turno;";
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$turnos[0][$i]=$result->fields[0];
				$turnos[1][$i]=$result->fields[1];
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $turnos;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "NO EXISTEN PROFESIONALES CON AGENDA PARA ESA EMPRESA.";
			return false;
		}
	}


/**
* Esta funcion retorna el listado de los turnos de un profesional por dia
*
* @access public
* @return array retorna el vector con los turnos de un profesional por dia.
*/


	function ListadoTurnosDia()
	{
		list($dbconn) = GetDBconn();
		$a=explode(",",$_REQUEST['TurnoAgenda']);
		if(sizeof($a)==1)
		{
			$sql="select a.hora, a.agenda_cita_id, a.agenda_turno_id, c.primer_nombre || ' ' || c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre_completo, c.residencia_telefono, c.paciente_id, c.tipo_id_paciente from agenda_citas as a left join agenda_citas_asignadas as b on(a.agenda_cita_id=b.agenda_cita_id) left join pacientes as c on(b.paciente_id=c.paciente_id and b.tipo_id_paciente=c.tipo_id_paciente) where a.agenda_turno_id=".$_REQUEST['TurnoAgenda']." and a.sw_estado_cancelacion=0 order by a.hora;";
		}
		else
		{
			$sql="select a.hora, a.agenda_cita_id, a.agenda_turno_id, c.primer_nombre || ' ' || c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre_completo, c.residencia_telefono, c.paciente_id, c.tipo_id_paciente from agenda_citas as a left join agenda_citas_asignadas as b on(a.agenda_cita_id=b.agenda_cita_id) left join pacientes as c on(b.paciente_id=c.paciente_id and b.tipo_id_paciente=c.tipo_id_paciente) where (a.agenda_turno_id=".$a[0];
			foreach($a as $v=>$datos)
			{
				if(!empty($datos) and $v!=0)
				{
					$sql.=" or a.agenda_turno_id=".$datos;
				}
			}
			$sql.=") and a.sw_estado_cancelacion=0 order by a.hora;";
		}
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$turnosdia[0][$i]=$result->fields[0];
				$turnosdia[1][$i]=$result->fields[1];
				$turnosdia[2][$i]=$result->fields[2];
				$turnosdia[3][$i]=$result->fields[3];
				$turnosdia[4][$i]=$result->fields[4];
				$turnosdia[5][$i]=$result->fields[5];
				$turnosdia[6][$i]=$result->fields[6];
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $turnosdia;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "NO EXISTEN PROFESIONALES CON AGENDA PARA ESA EMPRESA.";
			return false;
		}
	}




/**
* Esta funcion retorna el listado de los profesionales por especialidad
*
* @access public
* @return array retorna el vector con los profesionales por especialidad.
* @param string numero de identificacion de especialidad
*/


	function BuscarProfesionales($especialidad)
	{
		list($dbconn) = GetDBconn();
		if(!empty($especialidad))
		{
			$sql="select a.tipo_id_tercero, a.tercero_id, c.nombre, d.estado from profesionales_empresas as a join profesionales_especialidades as b on(a.tipo_id_tercero=b.tipo_id_tercero and a.tercero_id=b.tercero_id and b.especialidad='$especialidad') join profesionales as c on (a.tipo_id_tercero=c.tipo_id_tercero and a.tercero_id=c.tercero_id)  left join profesionales_estado as d on(a.tipo_id_tercero=d.tipo_id_tercero and a.tercero_id=d.tercero_id and d.empresa_id='".$_SESSION['BorrarAgenda']['empresa']."' and d.departamento='".$_SESSION['BorrarAgenda']['departamento']."') where a.empresa_id='".$_SESSION['BorrarAgenda']['empresa']."' order by c.nombre;";
		}
		else
		{
			$sql="select a.tipo_id_tercero, a.tercero_id, c.nombre, d.estado from profesionales_empresas as a join profesionales as c on (a.tipo_id_tercero=c.tipo_id_tercero and a.tercero_id=c.tercero_id)  left join profesionales_estado as d on(a.tipo_id_tercero=d.tipo_id_tercero and a.tercero_id=d.tercero_id and d.empresa_id='".$_SESSION['BorrarAgenda']['empresa']."' and d.departamento='".$_SESSION['BorrarAgenda']['departamento']."') where a.empresa_id='".$_SESSION['BorrarAgenda']['empresa']."' order by c.nombre;";
		}
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while (!$result->EOF)
		{
			if($result->fields[5]==1 or (empty($result->fields[5]) and !($result->fields[5]==='0')))
			{
				$profesionales[]=$result->GetRowAssoc(false);
			}
			$result->MoveNext();
		}
		return $profesionales;
	}


/**
* Esta funcion retorna el listado de las fechas con agenda activa
*
* @access public
* @return array retorna el vector con las fechas activas.
*/

	function BusquedaAgendas()
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		foreach($_SESSION['BorrarAgenda']['datos'] as $k=>$v)
		{
			$_REQUEST[$k]=$v;
		}
		foreach($_REQUEST as $v=>$datos)
		{
			if(substr_count ($v,'seleccion')==1)
			{
				$_SESSION['BorrarAgenda']['datos'][$v]=$datos;
				$a=explode(",",$datos);
				if(sizeof($a)==1)
				{
					$sql="select a.agenda_cita_id, a.hora, b.*, c.especialidad from agenda_citas as a join agenda_turnos as b on(a.agenda_turno_id=b.agenda_turno_id) join tipos_consulta as c on (b.tipo_consulta_id=c.tipo_consulta_id) where a.agenda_turno_id=".$datos." and a.sw_estado_cancelacion=0 order by b.fecha_turno || ' ' || a.hora;";
				}
				else
				{
					$sql="select a.agenda_cita_id, a.hora, b.*, c.especialidad from agenda_citas as a join agenda_turnos as b on(a.agenda_turno_id=b.agenda_turno_id) join tipos_consulta as c on (b.tipo_consulta_id=c.tipo_consulta_id) where (a.agenda_turno_id=".$a[0];
					foreach($a as $v=>$datos1)
					{
						if(!empty($datos1) and $v!=0)
						{
							$sql.=" or a.agenda_turno_id=".$datos1;
						}
					}
					$sql.=") and a.sw_estado_cancelacion=0 order by b.fecha_turno || ' ' || a.hora;";
				}
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
				while (!$result->EOF)
				{
					$agenda_cita[$result->fields['agenda_turno_id']][$result->fields['agenda_cita_id']]=$result->GetRowAssoc(false);
					$result->MoveNext();
				}
			}
		}
		return $agenda_cita;
	}




/**
* Esta funcion cambia el turno completo de la agenda de un profesional
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no
*/

	function CambiarAgendaTurnoCompleto()
	{
		if(empty($_REQUEST['DiaEspe']))
		{
			if($this->CambiarAgendaCompleta('ESCOJE LA FECHA')==false)
			{
				return false;
			}
		}
		else
		{
			if($_REQUEST['DiaEspe']<date("Y-m-d"))
			{
				if($this->CambiarAgendaCompleta('FECHA ANTERIOR AL DÍA DE HOY')==false)
				{
					return false;
				}
			}
			else
			{
				if(empty($_REQUEST['Cambiar']) or $_REQUEST['justificacion']==-1)
				{
					if($_REQUEST['justificacion']==-1)
					{
						$this->frmError["justificacion"]=1;
					}
					if($this->CambiarAgendaCompleta()==false)
					{
						return false;
					}
				}
				else
				{
					if($this->PantallaFinalCambioAgenda()==false)
					{
						return false;
					}
				}
			}
		}
		return true;
	}



/**
* Esta funcion muestra la ultima pantalla en la busqueda de agendas
*
* @access public
* @return array retorna vector con la informacion de la agenda
*/

	function BusquedaAgendasPantallaFinal()
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		foreach($_REQUEST as $v=>$datos)
		{
			if(substr_count ($v,'turno')==1)
			{
				$a=explode(",",$datos);
				if(sizeof($a)==1)
				{
					$sql="select a.agenda_cita_id, a.hora, b.*, c.especialidad, d.agenda_cita_asignada_id, f.sw_estado, g.paciente_id || ' - ' || g.tipo_id_paciente as identificacion, g.primer_nombre || ' ' || g.segundo_nombre || ' ' || g.primer_apellido || ' ' || g.segundo_apellido as nombre_completo from agenda_citas as a join agenda_turnos as b on(a.agenda_turno_id=b.agenda_turno_id) join tipos_consulta as c on (b.tipo_consulta_id=c.tipo_consulta_id) left join agenda_citas_asignadas as d on(a.agenda_cita_id=d.agenda_cita_id) left join pacientes as g on(d.tipo_id_paciente=g.tipo_id_paciente and d.paciente_id=g.paciente_id) left join os_cruce_citas as e on (d.agenda_cita_asignada_id=e.agenda_cita_asignada_id) left join os_maestro as f on(e.numero_orden_id=f.numero_orden_id) where a.agenda_turno_id=".$datos." and a.sw_estado_cancelacion=0 and (f.sw_estado!=3 or f.sw_estado is null) order by b.fecha_turno || ' ' || a.hora;";
				}
				else
				{
					$sql="select a.agenda_cita_id, a.hora, b.*, c.especialidad, d.agenda_cita_asignada_id, f.sw_estado, g.paciente_id || ' - ' || g.tipo_id_paciente as identificacion, g.primer_nombre || ' ' || g.segundo_nombre || ' ' || g.primer_apellido || ' ' || g.segundo_apellido as nombre_completo from agenda_citas as a join agenda_turnos as b on(a.agenda_turno_id=b.agenda_turno_id) join tipos_consulta as c on (b.tipo_consulta_id=c.tipo_consulta_id) left join agenda_citas_asignadas as d on(a.agenda_cita_id=d.agenda_cita_id) left join pacientes as g on(d.tipo_id_paciente=g.tipo_id_paciente and d.paciente_id=g.paciente_id) left join os_cruce_citas as e on (d.agenda_cita_asignada_id=e.agenda_cita_asignada_id) left join os_maestro as f on(e.numero_orden_id=f.numero_orden_id) where (a.agenda_turno_id=".$a[0];
					foreach($a as $v=>$datos1)
					{
						if(!empty($datos1) and $v!=0)
						{
							$sql.=" or a.agenda_turno_id=".$datos1;
						}
					}
					$sql.=") and a.sw_estado_cancelacion=0 and (f.sw_estado!=3 or f.sw_estado is null) order by b.fecha_turno || ' ' || a.hora;";
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($sql);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while (!$result->EOF)
				{
					$agenda_cita[$result->fields['agenda_turno_id']][$result->fields['agenda_cita_id']][$result->fields['agenda_cita_asignada_id']]=$result->GetRowAssoc(false);
					$result->MoveNext();
				}
			}
		}
		return $agenda_cita;
	}


/**
* Esta funcion cambia el turno de una agenda de manera completa
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no fue asi
*/

	function CambiarAgendaCompletaTotal()
	{
		$b=explode(',',$_REQUEST['Profesional']);
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		foreach($_SESSION['BorrarAgenda']['DatosAgenda'] as $k=>$v)
		{
			$turno=0;
			foreach($v as $t=>$s)
			{
				$cita=0;
				foreach($s as $p=>$m)
				{
					if($turno==0)
					{
						$sql="select nextval('public.agenda_turnos_seq');";
						$result = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						$id=$result->fields[0];
						$sql="insert into agenda_turnos (agenda_turno_id, fecha_turno, duracion, tipo_registro, profesional_id,  tipo_consulta_id, cantidad_pacientes, usuario_id, fecha_registro, tipo_id_profesional, consultorio_id, empresa_id) values (".$id.", '".$_REQUEST['DiaEspe']."' ,".$m['duracion']." ,'".$m['tipo_registro']."' ,'".$b[1]."' ,'".$m['tipo_consulta_id']."' ,".$m['cantidad_pacientes']." ,".$_SESSION['SYSTEM_USUARIO_ID']." ,'".date("Y-m-d H:i:s")."' ,'".$b[0]."', '".$m['consultorio_id']."', '".$m['empresa_id']."');";
						$result = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						$sql="update agenda_turnos set sw_estado_cancelacion=1 where agenda_turno_id=$k;";
						$result = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						$sql="insert into agenda_turnos_cancelados (agenda_turno_id,agenda_tipo_justificacion_id) values (".$k.", ".$_REQUEST['justificacion'].");";
						$result = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						$turno=1;
					}
					if($cita==0)
					{
						$sql="select nextval('agenda_citas_agenda_cita_id_seq');";
						$result=$dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						$agenda_cita_id=$result->fields[0];
						$sql="insert into agenda_citas (agenda_cita_id, hora, agenda_turno_id) values (".$agenda_cita_id.", '".$m['hora']."',".$id.");";
						$result=$dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						$sql="update agenda_citas set sw_estado_cancelacion=1 where agenda_cita_id=".$t.";";
						$result=$dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						$sql="insert into agenda_citas_canceladas(agenda_cita_id, agenda_tipo_justificacion_id)values (".$t.", ".$_REQUEST['justificacion'].");";
						$result=$dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						$cita=1;
					}
					if(!empty($p))
					{
						$sql="update agenda_citas_asignadas set agenda_cita_id=$agenda_cita_id where agenda_cita_asignada_id=$p;";
						$result=$dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						$sql="update agenda_citas set sw_estado=sw_estado+1 where agenda_cita_id=$agenda_cita_id";
						$result=$dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}
				}
			}
		}
		if($this->ListadoAgendaMesTurnos()==false)
		{
			return false;
		}
		//$dbconn->RollbackTrans();
		$dbconn->CommitTrans();
		return true;
	}


/**
* Esta funcion revisa que el cambiar la agenda sea posible
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no fue asi
*/

	function CambiarAgendaTurno()
	{
		if(empty($_REQUEST['DiaEspe']))
		{
			if($this->CambiarAgendaDia('ESCOJE LA FECHA')==false)
			{
				return false;
			}
		}
		else
		{
			if($_REQUEST['DiaEspe']<date("Y-m-d"))
			{
				if($this->CambiarAgendaDia('FECHA ANTERIOR AL DÍA DE HOY')==false)
				{
					return false;
				}
			}
			else
			{
				if(empty($_REQUEST['Cambiar']) or $_REQUEST['justificacion']==-1)
				{
					if($_REQUEST['justificacion']==-1)
					{
						$this->frmError["justificacion"]=1;
					}
					if($this->CambiarAgendaDia()==false)
					{
						return false;
					}
				}
				else
				{
					if($this->CambioDeAgendaUnica()==false)
					{
						return false;
					}
					if($this->CambiarAgenda()==false)
					{
						return false;
					}
				}
			}
		}
		return true;
	}





/**
* Esta funcion cambia una hora especifica de un turno a otro turno
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no fue asi
*/


	function CambioDeAgendaUnica()
	{
		$a=explode(',',$_REQUEST['citas']);
		$b=explode(',',$_REQUEST['Profesional']);
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$sql="select nextval('public.agenda_turnos_seq');";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$id=$result->fields[0];
		$sql="insert into agenda_turnos (agenda_turno_id, fecha_turno, duracion, tipo_registro, profesional_id,  tipo_consulta_id, cantidad_pacientes, usuario_id, fecha_registro, tipo_id_profesional, consultorio_id, empresa_id) values (".$id.", '".$_REQUEST['DiaEspe']."' ,".$_SESSION['BorrarAgenda']['DatosCitas'][$a[0]][$a[1]]['duracion']." ,'".$_SESSION['BorrarAgenda']['DatosCitas'][$a[0]][$a[1]]['tipo_registro']."' ,'".$b[1]."' ,'".$_SESSION['BorrarAgenda']['DatosCitas'][$a[0]][$a[1]]['tipo_consulta_id']."' ,".$_SESSION['BorrarAgenda']['DatosCitas'][$a[0]][$a[1]]['cantidad_pacientes']." ,".$_SESSION['SYSTEM_USUARIO_ID']." ,'".date("Y-m-d H:i:s")."' ,'".$b[0]."', '".$_SESSION['BorrarAgenda']['DatosCitas'][$a[0]][$a[1]]['consultorio_id']."', '".$_SESSION['BorrarAgenda']['DatosCitas'][$a[0]][$a[1]]['empresa_id']."');";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="select nextval('agenda_citas_agenda_cita_id_seq');";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$agenda_cita_id=$result->fields[0];
		$sql="insert into agenda_citas (agenda_cita_id, hora, agenda_turno_id) values (".$agenda_cita_id.", '".$_REQUEST['hora'].':'.$_REQUEST['minutos']."',".$id.");";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		foreach($_SESSION['BorrarAgenda']['DatosCitas1'] as $k=>$v)
		{
			$sql="update agenda_citas_asignadas set agenda_cita_id=$agenda_cita_id where agenda_cita_asignada_id=$k;";
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			$sql="update agenda_citas set sw_estado=sw_estado+1 where agenda_cita_id=$agenda_cita_id";
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		$sql="update agenda_citas set sw_estado_cancelacion=1 where agenda_cita_id=".$a[1].";";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="insert into agenda_citas_canceladas(agenda_cita_id, agenda_tipo_justificacion_id)values (".$a[1].", ".$_REQUEST['justificacion'].");";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		//$dbconn->RollbackTrans();
		$dbconn->CommitTrans();
		return true;
	}





/**
* Esta funcion cambia una hora especifica de un turno a otro turno
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no fue asi
* @param int identificacion unica del turno
*/

	function BusquedaEspecialidad($turno)
	{
		list($dbconn) = GetDBconn();
		$sql="select b.especialidad from agenda_turnos as a, tipos_consulta as b where a.agenda_turno_id=$turno and a.tipo_consulta_id=b.tipo_consulta_id;";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $result->fields[0];
	}



/**
* Esta funcion retorna un vector con los tipos de justificacion
*
* @access public
* @return array retorna un vector con la informacion de la justificacion
*/

	function BusquedaTipoJustificacion()
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$sql="select * from agenda_tipos_justificacion";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while (!$result->EOF)
		{
			$citas[$result->fields['agenda_tipo_justificacion_id']]=$result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $citas;
	}




/**
* Esta funcion retorna un vector con la informacion de la cita especifica
*
* @access public
* @return array retorna un vector con la informacion de la cita
* @param int identificacion unica de la cita
*/

	function BusquedaDatosTurno($cita)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$sql="select a.agenda_cita_asignada_id, a.paciente_id, a.tipo_id_paciente,a.tipo_cita, a.plan_id, a.cargo_cita, a.observacion, b.primer_nombre || ' ' || b.segundo_nombre || ' ' || b.primer_apellido || ' ' || b.segundo_apellido as nombre, c.numero_orden_id from agenda_citas_asignadas as a, pacientes as b,os_cruce_citas as c  where a.agenda_cita_id=$cita and a.sw_atencion=0 and a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id and a.agenda_cita_asignada_id=c.agenda_cita_asignada_id;";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while (!$result->EOF)
		{
			$citas[$result->fields['agenda_cita_asignada_id']]=$result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $citas;
	}


/**
* Esta funcion borra el turno si este no tiene ninguna cita
*
* @access public
* @return int informando si se borro la agenda o no
*/

	function BorrarAgendaTurno()
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		foreach($_REQUEST as $v=>$datos)
		{
			if(substr_count ($v,'seleccion')==1)
			{
				$a=explode(",",$datos);
				if(sizeof($a)==1)
				{
					$sql="select count(agenda_cita_id) from agenda_citas where agenda_turno_id=".$datos." and sw_estado!=0;";
				}
				else
				{
					$sql="select count(agenda_cita_id) from agenda_citas where sw_estado!=0 and (agenda_turno_id=".$a[0];
					foreach($a as $v=>$datos1)
					{
						if(!empty($datos1) and $v!=0)
						{
							$sql.=" or agenda_turno_id=".$datos1;
						}
					}
					$sql.=");";
				}
				$result = $dbconn->Execute($sql);
				$i=0;
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
					$saber=$result->fields[0];
					if($saber==0)
					{
						if(sizeof($a)==1)
						{
							$sql="delete from agenda_citas where agenda_turno_id=".$datos.";";
						}
						else
						{
							$sql="delete from agenda_citas where agenda_turno_id=".$a[0];
							foreach($a as $v=>$datos1)
							{
								if(!empty($datos1) and $v!=0)
								{
									$sql.=" or agenda_turno_id=".$datos1;
								}
							}
							$sql.=";";
						}
						$result = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						if(sizeof($a)==1)
						{
							$sql="delete from agenda_turnos where agenda_turno_id=".$datos.";";
						}
						else
						{
							$sql="delete from agenda_turnos where agenda_turno_id=".$a[0];
							foreach($a as $v=>$datos1)
							{
								if(!empty($datos1) and $v!=0)
								{
									$sql.=" or agenda_turno_id=".$datos1;
								}
							}
							$sql.=";";
						}
						$result = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						unset($_REQUEST[$v]);
					}
					else
					{
						break;
					}
				}
			}
		}
		$dbconn->CommitTrans();
		return $saber;
	}



/**
* Esta funcion borra las citas de un turno
*
* @access public
* @return int informando si se borro la cita o no
*/



	function BorrarAgendaCita()
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		foreach($_REQUEST as $v=>$datos)
		{
			if(substr_count ($v,'seleccion')==1)
			{
				$a=explode(",",$datos);
				$sql="select count(*) from agenda_citas where agenda_turno_id='".$a[1]."';";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
					$borra=1;
					if($result->fields[0]==1)
					{
						$borra=2;
					}
				}
				$sql="select count(*) from agenda_citas where sw_estado!=0 and agenda_cita_id=".$a[0].";";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
					$borra1=1;
					if($result->fields[0]==1)
					{
						$borra1=2;
					}
				}
				if($borra1==1)
				{
					$sql="delete from agenda_citas where agenda_cita_id=".$a[0].";";
					$result = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
					else
					{
						if($borra==2)
						{
							$sql="delete from agenda_turnos where agenda_turno_id=".$a[1];
							$result = $dbconn->Execute($sql);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
							}
						}
						unset($_REQUEST[$v]);
					}
				}
			}
		}
		$dbconn->CommitTrans();
		return $borra;
	}



/**
* Esta funcion permite cancelar una cita de una agenda
*
* @access public
* @return int informando si se cancelo la cita o no
*/

	function CancelarAgendaCita()
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		foreach($_REQUEST as $v=>$datos)
		{
			if(substr_count ($v,'seleccion')==1)
			{
				$a=explode(",",$datos);
				$sql="select count(*) from agenda_citas where agenda_turno_id='".$a[1]."';";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
					$borra=1;
					if($result->fields[0]==1)
					{
						$borra=2;
					}
				}
				if($borra==1)
				{
					$sql="update agenda_citas set sw_estado_cancelacion='1' where agenda_cita_id=".$a[0].";";
					$result = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
				if($borra==2)
				{
					$sql="update agenda_citas set sw_estado_cancelacion='1' where agenda_cita_id=".$a[0];
					$result = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
					$sql="update agenda_turnos set sw_estado_cancelacion='1' where agenda_turno_id=".$a[1];
					$result = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
					unset($_REQUEST[$v]);
				}
			}
		}
		$dbconn->CommitTrans();
		return $borra;
	}



/**
* Esta funcion retorna un vector identificando los turnos programados
*
* @access public
* @return array retorna un vector con la informacion de los turnos programados
*/

	function TurnosProgramados()
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['saber']==1)
		{
			$sql="select a.fecha_turno, (select min(hora) from agenda_citas as b where b.agenda_turno_id=a.agenda_turno_id) as hora_min, (select max(hora) from agenda_citas as b where b.agenda_turno_id=a.agenda_turno_id) as hora_max from agenda_turnos as a where profesional_id='".$_SESSION['CreacionAgenda']['tercero']."' and tipo_id_profesional='".$_SESSION['CreacionAgenda']['tipoid']."' and tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita']." and date(fecha_turno)>=date(now()) order by a.fecha_turno, hora_min;";
		}
		else
		{
			$sql="select count(a.fecha_turno) from agenda_turnos as a where profesional_id='".$_SESSION['CreacionAgenda']['tercero']."' and tipo_id_profesional='".$_SESSION['CreacionAgenda']['tipoid']."' and tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita']." and date(fecha_turno)>=date(now());";
		}
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			echo "hola";
			return false;
		}
		else
		{
			if($_REQUEST['saber']==1)
			{
				while (!$result->EOF)
				{
					$a=explode("-",$result->fields[0]);
					$turnos[0][$i]=$a[0];
					$turnos[1][$i]=$a[0].'-'.$a[1];
					$turnos[2][$i]=$result->fields[0];
					$turnos[3][$i]=$result->fields[1];
					$turnos[4][$i]=$result->fields[2];
					$i++;
					$result->MoveNext();
				}
			}
			else
			{
				$i=1;
				$turnos=$result->fields[0];
			}
		}
		if($i<>0)
		{
			return $turnos;
		}
		else
		{
			return false;
		}
	}



/**
* Esta funcion retorna un vector con los dias festivos
*
* @access public
* @return array retorna un vector con los dias festivos
*/

	function Festivos($a)
	{
		list($dbconn) = GetDBconn();
		$sql="select dia from dias_festivos where extract(year from dia)=".$a.";";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			echo "hola";
			return false;
		}
		else
		{
			$i=0;
			while (!$result->EOF)
			{
				$festivos[$i]=$result->fields[0];
				$i++;
				$result->MoveNext();
			}
		}
		return $festivos;
	}





/**
* Esta funcion retorna la informacion del intervalo e inserta en un vector la informacion de las citas
*
* @access public
* @return int retorna el intervalo
*/


	function CitasDias($todo)
	{
		$a=explode("-",$_REQUEST['DiaEspe']);
		$b=explode(" ",$a[2]);
		$c=explode(":",$b[1]);
		$i=$b[0];
		$c[1]=$_REQUEST['iniminutos'];
		while($i==date("d",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])))
		{
			$j=array_keys($_SESSION['FECHAS'],date("Y-m-d H:i",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])));
			$s=$j[0];
			if($s!='' or $s===0)
			{
				break;
			}
			else
			{
				$c[1]=$c[1]+$_REQUEST['interval'];
			}
		}
		$i=$j[0];
		$a=explode("-",$_REQUEST['DiaEspe']);
		$b=explode(" ",$a[2]);
		$c=explode(":",$b[1]);
		$cont=0;
		while($i<sizeof($_SESSION['FECHAS']))
		{
			if($_SESSION['FECHAS'][$i]>=date("Y-m-d H:i",mktime($c[0]+6,59,0,$a[1],$b[0],$a[0])))
			{
				break;
			}
			else
			{
				$dias[$cont]=$_SESSION['FECHAS'][$i];
				$cont++;
			}
			$i++;
		}
		$a=explode("-",$_REQUEST['DiaEspe']);
		$b=explode(" ",$a[2]);
		$c=explode(":",$b[1]);
		$c[1]=$_REQUEST['iniminutos'];
		$d=($c[1]/5);
		if($d==0 or $d==2 or $d==4 or $d==6 or $d==8 or $d==10)
		{
			$dia=date("Y-m-d H:i",mktime($c[0],0,0,$a[1],$b[0],$a[0]));
			$s=$_REQUEST['interval'];
		}
		else
		{
				$dia=date("Y-m-d H:i",mktime($c[0],5,0,$a[1],$b[0],$a[0]));
				$s=$_REQUEST['interval']+5;
		}
		$cont=0;
		$p=0;
		while($dia<=date("Y-m-d H:i",mktime($c[0]+6,59,0,$a[1],$b[0],$a[0])))
		{
			if($dia==$dias[$cont])
			{
				$cont++;
			}
			else
			{
				$todo[$p]=$dia;
				$p++;
			}
			$a1=explode("-",$_REQUEST['DiaEspe']);
			$b1=explode(" ",$a1[2]);
			$dia=date("Y-m-d H:i",mktime($c[0],$s,0,$a1[1],$b1[0],$a1[0]));
			$s=$s+$_REQUEST['interval'];
		}
		//print_r($todo);
		if($c[0]==0)
		{
			return 1;
		}
		elseif($c[0]==6)
		{
			return 2;
		}
		elseif($c[0]==12)
		{
			return 3;
		}
		elseif($c[0]==18)
		{
			return 4;
		}
	}





/**
* Esta funcion retorna un vector con los consultorios
*
* @access public
* @return array retorna un vector con los consultorios
*/


	function Consultorio()
	{
		list($dbconn) = GetDBconn();
		$sql="select consultorio, consultorio from tipos_consulta_consultorios as a where tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita'].";";
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			echo "hola";
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$consultorio[0][$i]=$result->fields[0];
				$consultorio[1][$i]=$result->fields[1];
				$result->MoveNext();
				$i++;
			}
		}
		if($i<>0)
		{
			return $consultorio;
		}
		else
		{
			return false;
		}
	}




/**
* Esta funcion retorna un vector con los tipos de registro
*
* @access public
* @return array retorna un vector con los tipos de registro
*/


	function TipoRegistro()
	{
		list($dbconn) = GetDBconn();
		$sql="select tipo_registro, descripcion from tipos_registro;";
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			echo "hola";
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$tipocita[0][$i]=$result->fields[0];
				$tipocita[1][$i]=$result->fields[1];
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $tipocita;
		}
		else
		{
			return false;
		}
	}





/**
* Esta funcion retorna un vector con los diferentes intervalos de una cita
*
* @access public
* @return array retorna un vector con los diferentes intervalos
*/
	function Intervalo()
	{
		list($dbconn) = GetDBconn();
		$sql="select duracion from agenda_medica_tipo_intervalo order by duracion;";
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			echo "hola";
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$intervalo[$i]=$result->fields[0];
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $intervalo;
		}
		else
		{
			return false;
		}
	}





/**
* Esta funcion retorna un vector con la cantidad de pacientes que se puede tener en una cita
*
* @access public
* @return array retorna un vector con la cantidad de pacientes
*/
	function Pacientes()
	{
		list($dbconn) = GetDBconn();
		$sql="select cantidad from agenda_medica_tipo_cantidad_pacientes;";
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			echo "hola";
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$pacientes[$i]=$result->fields[0];
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $pacientes;
		}
		else
		{
			return false;
		}
	}






/**
* Esta funcion crea las agendas con sus turnos especificos segun lo solicite el usuario del sistema
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no
*/



	function GuardarDatos()
	{
		list($dbconn) = GetDBconn();
		$i=0;
		$s=0;
		$dbconn->BeginTrans();
		while($i<sizeof($_SESSION['FECHAS']))
		{
			if(!empty($_SESSION['FECHAS'][$i]))
			{
				$a=explode("-",$_SESSION['FECHAS'][$i]);
				$b=explode(" ",$a[2]);
				$c=explode(":",$b[1]);
				$fechaac=date("Y-m-d",mktime(0,0,0,$a[1],$b[0],$a[0]));
				if($fechaac!=$fecha)
				{
					$sql="select case when count(*)=0 then 0 when count(*)!=0 then 1 end from (select a.fecha_turno || ' ' || b.hora as fecha_turnos from agenda_turnos as a, agenda_citas as b where date(a.fecha_turno)=date('".$fechaac."') and a.profesional_id='".$_SESSION['CreacionAgenda']['tercero']."' and a.tipo_id_profesional='".$_SESSION['CreacionAgenda']['tipoid']."' and a.agenda_turno_id=b.agenda_turno_id and a.sw_estado_cancelacion='0' and b.sw_estado_cancelacion='0') as a where to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')>=to_timestamp('".date("Y-m-d H:i",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))."','YYYY-MM-DD HH24:MI') and to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')<=to_timestamp('".date("Y-m-d H:i",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))."','YYYY-MM-DD HH24:MI');";
					//echo '<br>';
					/*$sql1="select * from (select a.fecha_turno || ' ' || b.hora as fecha_turnos from agenda_turnos as a, agenda_citas as b where date(a.fecha_turno)=date('".$fecha."') and a.profesional_id='".$_REQUEST['tercero']."' and a.tipo_id_profesional='".$_REQUEST['tipoid']."' and a.agenda_turno_id=b.agenda_turno_id) as a where to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')>=to_timestamp('".$_SESSION['FECHAS'][$t]."','YYYY-MM-DD HH24:MI') and to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')&lt;=to_timestamp('".$_SESSION['FECHAS'][$i-1]."','YYYY-MM-DD HH24:MI');";
					echo $sql;*/
					$result=$dbconn->Execute($sql);
					if ($dbconn->ErrorNo() !=0)
					{
						$dbconn->RollbackTrans();
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					if($result->fields[0]==='1')
					{
					}
					else
					{
						$a=explode("-",$_SESSION['FECHAS'][$i]);
						$b=explode(" ",$a[2]);
						$c=explode(":",$b[1]);
						$fecha=date("Y-m-d",mktime(0,0,0,$a[1],$b[0],$a[0]));
						$t=$i;
						$sql="select nextval('public.agenda_turnos_seq');";
						$result=$dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$dbconn->RollbackTrans();
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						$id=$result->fields[0];
						$sql="insert into agenda_turnos (agenda_turno_id, fecha_turno, duracion, tipo_registro, profesional_id,  tipo_consulta_id, cantidad_pacientes, usuario_id, fecha_registro, tipo_id_profesional, consultorio_id, empresa_id) values (".$id.", '".$fecha."' ,".$_REQUEST['interval']." ,'".$_REQUEST['tiporegistro']."' ,'".$_SESSION['CreacionAgenda']['tercero']."' ,'".$_SESSION['CreacionAgenda']['Cita']."' ,".$_REQUEST['pacientes']." ,".$_SESSION['SYSTEM_USUARIO_ID']." ,'".date("Y-m-d H:i:s")."' ,'".$_SESSION['CreacionAgenda']['tipoid']."','".$_REQUEST['consultorio']."','".$_SESSION['CreacionAgenda']['empresa']."');";
						//echo '<br>';
						$result=$dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						else
						{
							$sql2="insert into agenda_citas (hora, agenda_turno_id) values ('".date("H:i",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))."',".$id.");";
							$result=$dbconn->Execute($sql2);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
							}
						}
					}
				}
				else
				{
					$sql="select case when count(*)=0 then 0 when count(*)!=0 then 1 end from (select a.fecha_turno || ' ' || b.hora as fecha_turnos from agenda_turnos as a, agenda_citas as b where date(a.fecha_turno)=date('".$fechaac."') and a.profesional_id='".$_SESSION['CreacionAgenda']['tercero']."' and a.tipo_id_profesional='".$_SESSION['CreacionAgenda']['tipoid']."' and a.agenda_turno_id=b.agenda_turno_id and a.sw_estado_cancelacion='0' and b.sw_estado_cancelacion='0') as a where to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')>=to_timestamp('".date("Y-m-d H:i",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))."','YYYY-MM-DD HH24:MI') and to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')<=to_timestamp('".date("Y-m-d H:i",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))."','YYYY-MM-DD HH24:MI');";
					$result=$dbconn->Execute($sql);
					if ($dbconn->ErrorNo() !=0)
					{
						$dbconn->RollbackTrans();
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					if($result->fields[0]==='1')
					{
					}
					else
					{
						$sql="insert into agenda_citas (hora, agenda_turno_id) values ('".date("H:i",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))."',".$id.");";
						//echo '<br>';
						$result=$dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}
				}
			}
			$i++;
		}
		$dbconn->CommitTrans();
		SessionDelVar('FECHAS');
		SessionDelVar('CITASMES');
		SessionDelVar('CITASDIA');
		$_REQUEST['accion']='';
		$i=0;
		$t=0;
		while($i<13)
		{
			$dato=$i."mes";
			if(!empty($_REQUEST[$dato]))
			{
				$t++;
				if($t==1)
				{
					break;
				}
			}
			$i++;
		}
		if($t>=1)
		{
			foreach($_REQUEST as $v=>$v1)
			{
				if($v!='1mes' and $v!='2mes' and $v!='3mes' and $v!='4mes' and $v!='5mes' and $v!='6mes' and $v!='7mes' and $v!='8mes' and $v!='9mes' and $v!='10mes' and $v!='11mes' and $v!='12mes' and $v!='guardar' and $v!='mes1' and $v!='mes2' and $v!='mes3' and $v!='mes4' and $v!='mes5' and $v!='mes6' and $v!='mes7' and $v!='mes8' and $v!='mes9' and $v!='mes10' and $v!='mes11' and $v!='mes12' and $v!='DiaEspe' and $v!='metodo')
				{
					$_REQUEST[$v]=$v1;
				}
				else
				{
					if($v!='guardar' and $v!='DiaEspe' and $v!='mes1' and $v!='mes2' and $v!='mes3' and $v!='mes4' and $v!='mes5' and $v!='mes6' and $v!='mes7' and $v!='mes8' and $v!='mes9' and $v!='mes10' and $v!='mes11' and $v!='mes12')
					{
						$dato='mes'.$v1;
						$_REQUEST[$dato]=$v1;
						$_REQUEST['accion']='add';
						$_REQUEST['Enviar']='Enviar';
					}
					else
					{
						unset($_REQUEST[$v]);
					}
				}
			}
		}
		$this->CreacionAgenda();
		return true;
	}

/**
* Esta funcion redirecciona el proceso de borrado de la agenda
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no
*/

	function BorrarAgenda()
	{
		$this->BorrarAgendaTurno();
		$this->ListadoAgendaMesTurnos();
		return true;
	}

/**
* Esta funcion redirecciona el proceso de borrado de la cita
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no
*/

	function BorrarAgendaDia()
	{
		if(!empty($_REQUEST['Borrar']))
		{
			if($this->BorrarAgendaCita()==2)
			{
				$this->ListadoAgendaMesTurnos();
				return true;
			}
		}
		else
		{
			if($this->CancelarAgendaCita()==2)
			{
				$this->ListadoAgendaMesTurnos();
				return true;
			}
		}
		$this->ListadoDiaAgenda();
		return true;
	}

	/*
	* Cambiamos el formato timestamp a un formato de fecha legible para el usuario
	*/

	function FormateoFechaMes($fecha)
	{
    if(!empty($fecha))
    {
        $f=explode(".",$fecha);
        $fecha_arreglo=explode(" ",$f[0]);
        $fecha_real=explode("-",$fecha_arreglo[0]);
        return ucwords(strftime("%B",strtotime($fecha_arreglo[0])));
    }
    else
    {
      return "-----";
    }
		return true;
	}

  function FormateoFechaDia($fecha)
	{
    if(!empty($fecha))
    {
        $f=explode(".",$fecha);
        $fecha_arreglo=explode(" ",$f[0]);
        $fecha_real=explode("-",$fecha_arreglo[0]);
        return ucwords(strftime("%A - %d",strtotime($fecha_arreglo[0])));
    }
    else
    {
      return "-----";
    }
		return true;
	}
}
?>
