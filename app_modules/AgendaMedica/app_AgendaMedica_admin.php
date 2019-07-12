<?php

/**
 * $Id: app_AgendaMedica_admin.php,v 1.6 2010/03/12 13:37:12 sandra Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS 
 *
 *
 */

class app_AgendaMedica_admin extends classModulo
{

	function app_AgendaMedica_admin()
	{
		return true;
	}

	function main()
	{
    if(!$this->Menu())
		{
			return false;
		}
		return true;
	}

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
			$this->mensajeDeError = "El usuario no se ha registrado.";
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
// 			while (!$result->EOF)
// 			{
// 				$prueba[$result->fields[4]]=$prueba[$result->fields[4]]+1;
// 				$prueba2[$result->fields[4]][$result->fields[2]]=$prueba2[$result->fields[4]][$result->fields[2]]+1;
// 				$prueba4[$result->fields[4]][]=array('descripcion1'=>$result->fields[5], 'descripcion2'=>$result->fields[3], 'descripcion3'=>$result->fields[1], 'empresa_id'=>$result->fields[4], 'departamento'=>$result->fields[2],'tipo_consulta_id'=>$result->fields[0]);
// 				$prueba3[$result->fields[4]][$result->fields[2]][$result->fields[0]]=$prueba4[$result->fields[4]][$result->fields[2]][$result->fields[0]]+1;
// 				$i++;
// 				$result->MoveNext();
// 			}
			while ($data = $result->FetchRow()) {
				$prueba4[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
			}
$i=1;
		}
		if($i<>0)
		{
			$mtz[0]='Empresas';
			$mtz[1]='Departamentos';
			$mtz[2]='Tipos de Cita';
			$this->salida.=gui_theme_menu_acceso('MATRIZ DE PERMISOS ADMINISTRATIVOS',$mtz,$prueba4,$url);
			return true;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no tiene empresas para mostrar.";
			return false;
		}
	}


	function Profesionales()
	{
		list($dbconn) = GetDBconn();
		$sql="select a.tipo_id_tercero, a.tercero_id, b.tipo_id_tercero, b.tercero_id, c.nombre, d.estado from profesionales_empresas as a left join (select tipo_id_tercero, tercero_id from profesionales_especialidades as a join (select especialidad from tipos_consulta where departamento='".$_SESSION['CreacionAgenda']['departamento']."' and tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita'].") as b on (a.especialidad=b.especialidad)) as b on (a.tipo_id_tercero=b.tipo_id_tercero and a.tercero_id=b.tercero_id) join profesionales as c on (a.tipo_id_tercero=c.tipo_id_tercero and a.tercero_id=c.tercero_id)  left join profesionales_estado as d on(a.tipo_id_tercero=d.tipo_id_tercero and a.tercero_id=d.tercero_id and d.empresa_id='".$_SESSION['CreacionAgenda']['empresa']."' and d.departamento='".$_SESSION['CreacionAgenda']['departamento']."') where a.empresa_id='".$_SESSION['CreacionAgenda']['empresa']."' order by c.nombre;";
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
		if($i<>0)
		{
			if($profesionales2!='')
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
			$this->mensajeDeError = "No Existen Profesionales para esa empresa.";
			return false;
		}
	}


	function Profesionales2()
	{
		list($dbconn) = GetDBconn();
		$sql="select distinct(tipo_id_profesional), profesional_id, b.nombre from agenda_turnos as a, profesionales as b where empresa_id='".$_SESSION['BorrarAgenda']['empresa']."' and tipo_consulta_id=".$_SESSION['BorrarAgenda']['Cita']." and a.tipo_id_profesional=b.tipo_id_tercero and a.profesional_id=b.tercero_id and date(fecha_turno)>=date(now());";
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
			$this->mensajeDeError = "No Existen Profesionales con agenda para esa empresa.";
			return false;
		}
	}


	function ListadoTurnosMes()
	{
		list($dbconn) = GetDBconn();
		$sql="select fecha_turno, agenda_turno_id from agenda_turnos as a where empresa_id='".$_SESSION['BorrarAgenda']['empresa']."' and tipo_consulta_id=".$_SESSION['BorrarAgenda']['Cita']." and date(fecha_turno)>=date(now()) and tipo_id_profesional='".$_SESSION['BorrarAgenda']['tipoid']."' and profesional_id='".$_SESSION['BorrarAgenda']['tercero']."' order by fecha_turno;";
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
			$this->mensajeDeError = "No Existen Profesionales con agenda para esa empresa.";
			return false;
		}
	}

	function ListadoTurnosDia()
	{
		list($dbconn) = GetDBconn();
		$a=explode(",",$_REQUEST['TurnoAgenda']);
		if(sizeof($a)==1)
		{
			$sql="select hora, agenda_cita_id,agenda_turno_id from agenda_citas where agenda_turno_id=".$_REQUEST['TurnoAgenda']." order by hora;";
		}
		else
		{
			$sql="select hora, agenda_cita_id from agenda_citas where (agenda_turno_id=".$a[0];
			foreach($a as $v=>$datos)
			{
				if(!empty($datos) and $v!=0)
				{
					$sql.=" or agenda_turno_id=".$datos;
				}
			}
			$sql.=") order by hora;";
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
			$this->mensajeDeError = "No Existen Profesionales con agenda para esa empresa.";
			return false;
		}
	}

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

	function Consultorio()
	{
		list($dbconn) = GetDBconn();
		$sql="select consultorio from consultorios where tipo_consulta_id='".$_SESSION['CreacionAgenda']['Cita']."';";
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
				$i++;
				$result->MoveNext();
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

	function Intervalo()
	{
		list($dbconn) = GetDBconn();
		$sql="select duracion from agenda_medica_tipo_intervalo;";
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
					if($s==1)
					{
						/*$sql="select case when count(*)=0 then 1 when count(*)!=0 then 0 end from (select a.fecha_turno || ' ' || b.hora as fecha_turnos from agenda_turnos as a, agenda_citas as b where date(a.fecha_turno)=date('".$fecha."') and a.profesional_id='".$_REQUEST['tercero']."' and a.tipo_id_profesional='".$_REQUEST['tipoid']."' and a.agenda_turno_id=b.agenda_turno_id) as a where to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')>=to_timestamp('".$_SESSION['FECHAS'][$t]."','YYYY-MM-DD HH24:MI') and to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')<=to_timestamp('".$_SESSION['FECHAS'][$i-1]."','YYYY-MM-DD HH24:MI');";
						//$sql1="select * from (select a.fecha_turno || ' ' || b.hora as fecha_turnos from agenda_turnos as a, agenda_citas as b where date(a.fecha_turno)=date('".$fecha."') and a.profesional_id='".$_REQUEST['tercero']."' and a.tipo_id_profesional='".$_REQUEST['tipoid']."' and a.agenda_turno_id=b.agenda_turno_id) as a where to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')>=to_timestamp('".$_SESSION['FECHAS'][$t]."','YYYY-MM-DD HH24:MI') and to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')&lt;=to_timestamp('".$_SESSION['FECHAS'][$i-1]."','YYYY-MM-DD HH24:MI');";
						echo $sql;
						$result=$dbconn->Execute($sql);
						if ($dbconn->ErrorNo() !=0)
						{
							$dbconn->RollbackTrans();
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						else
						{
							if($result->fields[0]==='1')
							{
								$dbconn->RollbackTrans();
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error Agenda Medica : No se puede crear la agenda del medico.";
								return false;
							}
						}*/
						$s=0;
					}
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
					$s++;
				}
				else
				{
					$sql="insert into agenda_citas (hora, agenda_turno_id) values ('".date("H:i",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))."',".$id.");";
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
			$i++;
		}
		/*$sql="select case when count(*)=0 then 1 when count(*)!=0 then 0 end from (select a.fecha_turno || ' ' || b.hora as fecha_turnos from agenda_turnos as a, agenda_citas as b where date(a.fecha_turno)=date('".$fecha."') and a.profesional_id='".$_REQUEST['tercero']."' and a.tipo_id_profesional='".$_REQUEST['tipoid']."' and a.agenda_turno_id=b.agenda_turno_id) as a where to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')>=to_timestamp('".$_SESSION['FECHAS'][$t]."','YYYY-MM-DD HH24:MI') and to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')<=to_timestamp('".$_SESSION['FECHAS'][$i-1]."','YYYY-MM-DD HH24:MI');";
		$sql1="select count(*) from (select a.fecha_turno || ' ' || b.hora as fecha_turnos from agenda_turnos as a, agenda_citas as b where date(a.fecha_turno)=date('".$fecha."') and a.profesional_id='".$_REQUEST['tercero']."' and a.tipo_id_profesional='".$_REQUEST['tipoid']."' and a.agenda_turno_id=b.agenda_turno_id) as a where to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')>=to_timestamp('".$_SESSION['FECHAS'][$t]."','YYYY-MM-DD HH24:MI') and to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')&lt;=to_timestamp('".$_SESSION['FECHAS'][$i-1]."','YYYY-MM-DD HH24:MI');";
			echo $sql;
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$dbconn->RollbackTrans();
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->fields[0]==='1')
			{
				$dbconn->RollbackTrans();
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error Agenda Medica : No se puede crear la agenda del medico.";
				return false;
			}
		}*/
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

	function BorrarAgenda()
	{
		$this->BorrarAgendaTurno();
		$this->ListadoAgendaMesTurnos();
		return true;
	}

	function BorrarAgendaDia()
	{
		if($this->BorrarAgendaCita()==2)
		{
			$this->ListadoAgendaMesTurnos();
			return true;
		}
		$this->ListadoDiaAgenda();
		return true;
	}

	//Funciones para la creacion de los profesionales OJO

	function DepartamentoProfesionales($url)
	{
		list($dbconn) = GetDBconn();
		if(!empty($_SESSION['SYSTEM_USUARIO_ID']))
		{
			$sql="select b.departamento, b.descripcion as descripcion1, c.empresa_id, c.razon_social as descripcion2 from userpermisos_mantenimiento_profesionales as a, departamentos as b, empresas as c where a.usuario_id=".$_SESSION['SYSTEM_USUARIO_ID']." and a.departamento=b.departamento and b.empresa_id=c.empresa_id order by empresa_id,departamento;";
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no se ha registrado.";
			return false;
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
				$prueba[$result->fields[2]]=$prueba[$result->fields[2]]+1;
				$prueba2[$result->fields[2]][$result->fields[1]]=$prueba2[$result->fields[2]][$result->fields[1]]+1;
				$prueba3[$result->fields[2]][]=array('descripcion2'=>$result->fields[1], 'descripcion1'=>$result->fields[3], 'empresa_id'=>$result->fields[2], 'departamento'=>$result->fields[0]);
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			$mtz[0][0]=$prueba;
			$mtz[0][1]='Empresas';
			$mtz[0][2]='empresa_id';
			$mtz[1][0]=$prueba2;
			$mtz[1][1]='Departamentos';
			$mtz[1][2]='departamento';
			$this->salida.=gui_theme_menu_acceso('MATRIZ DE PERMISOS ADMINISTRATIVOS',$mtz,$prueba3,$url);
			return true;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no tiene empresas para mostrar.";
			return false;
		}
	}


	function ProfesionalesCompleto()
	{
		list($dbconn) = GetDBconn();
		$sql="select a.tipo_id_tercero, a.tercero_id, c.nombre from profesionales_departamentos as a join departamentos as b using(departamento) join profesionales as c on (a.tipo_id_tercero=c.tipo_id_tercero and a.tercero_id=c.tercero_id) where b.empresa_id='".$_SESSION['ManProf']['empresa']."' and a.departamento='".$_SESSION['ManProf']['departamento']."' order by c.nombre;";
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
				$profesionales[0][$i]=$result->fields[0];
				$profesionales[1][$i]=$result->fields[1];
				$profesionales[2][$i]=$result->fields[2];
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			if($profesionales2!='')
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
			$this->mensajeDeError = "No Existen Profesionales para esa empresa.";
			return false;
		}
	}

	function BuscarPacientes()
	{
		$Buscar=$_REQUEST['Buscar'];
		if(empty($_REQUEST['Busqueda']))
		{
			$_REQUEST['Busqueda']=1;
		}
		if($_REQUEST['Busqueda']==1)
		{
			$TipoId=$_REQUEST['TipoDocumento'];
			$PacienteId=$_REQUEST['Documento'];
			if(!$PacienteId)
			{
				if(!$PacienteId)
				{
					$this->frmError["Documento"]=1;
				}
				$this->frmError["MensajeError"]="Debe digitar el Número del Documento.";
				if(!$this->ListarProfe($mensaje,$arr))
				{
					return false;
				}
				return true;
			}
				$Datos=$this->Buscar1($TipoId,$PacienteId);
				if($Datos)
				{
					$this->ListarProfe($mensaje,$Datos);
					return true;
				}
				else
				{
					$mensaje='La busqueda no arrojo resultados.';
					$this->ListarProfe($mensaje,$Datos);
					return true;
				}
		}
		if($_REQUEST['Busqueda']==2)
		{
			$nombres=$_REQUEST['nombres'];
			$nombres=strtoupper($nombres);
			$Datos=$this->Buscar2($nombres);
			if($Datos)
			{
				$this->ListarProfe($mensaje,$Datos);
				return true;
			}
			else
			{
				$mensaje='La busqueda no arrojo resultados.';
				$this->ListarProfe($mensaje,$Datos);
				return true;
			}
		}
		return true;
	}

	/**
  * Busca los datos de la admision cuando se conoce el tipo_id_paciente y paciente_id.
	* @access public
	* @return array
	* @param string tipo de documento
	* @param int numero de documento
	*/
  function Buscar1($TipoId,$PacienteId)
	{
			list($dbconn) = GetDBconn();
			$query="select a.nombre, case when c.estado is null then '1' else c.estado end, a.tipo_id_tercero, a.tercero_id, b.tipo_profesional, f.sexo_id, a.tarjeta_profesional from profesionales as a join tipo_sexo as f using(sexo_id) join profesionales_empresas as d on (a.tipo_id_tercero=d.tipo_id_tercero and a.tercero_id=d.tercero_id and empresa_id='".$_SESSION['ManProf']['empresa']."') join profesionales_departamentos as e on (d.tipo_id_tercero=e.tipo_id_tercero and d.tercero_id=e.tercero_id and e.departamento='".$_SESSION['ManProf']['departamento']."') join tipos_profesionales as b using(tipo_profesional) left join profesionales_estado as c on (a.tercero_id=c.tercero_id and a.tipo_id_tercero=c.tipo_id_tercero and c.departamento='".$_SESSION['ManProf']['departamento']."' and c.empresa_id='".$_SESSION['ManProf']['empresa']."') where a.tercero_id='".$PacienteId."' and a.tipo_id_tercero='".$TipoId."';";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$vars=$this->LlenaMatriz($result);
			return $vars;
	}

	/**
  * Busca los datos de la admision cuando se conoce el nombre o apellido del paciente.
	* @access public
	* @return array
	* @param string la cadena de busqueda
	* @param int si busca por apellido, nombre o por los dos
	*/
	function Buscar2($var)
	{
		list($dbconn) = GetDBconn();
		$query = "select a.nombre, case when c.estado is null then '1' else c.estado end, a.tipo_id_tercero, a.tercero_id, b.tipo_profesional, f.sexo_id, a.tarjeta_profesional from profesionales as a join tipo_sexo as f using(sexo_id) join profesionales_empresas as d on (a.tipo_id_tercero=d.tipo_id_tercero and a.tercero_id=d.tercero_id and empresa_id='".$_SESSION['ManProf']['empresa']."') join profesionales_departamentos as e on (d.tipo_id_tercero=e.tipo_id_tercero and d.tercero_id=e.tercero_id and e.departamento='".$_SESSION['ManProf']['departamento']."') join tipos_profesionales as b using(tipo_profesional) left join profesionales_estado as c on (a.tercero_id=c.tercero_id and a.tipo_id_tercero=c.tipo_id_tercero and c.departamento='".$_SESSION['ManProf']['departamento']."' and c.empresa_id='".$_SESSION['ManProf']['empresa']."') where a.nombre like '%$var%';";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$vars=$this->LlenaMatriz($result);
			return $vars;
	}

	function  LlenaMatriz($result)
	{
		$i=0;
		while (!$result->EOF)
		{
			$vars[0][$i]=$result->fields[0];
			$vars[1][$i]=$result->fields[1];
			$vars[2][$i]=$result->fields[2];
			$vars[3][$i]=$result->fields[3];
			$vars[4][$i]=$result->fields[4];
			$vars[5][$i]=$result->fields[5];
			$vars[6][$i]=$result->fields[6];
			$result->MoveNext();
			$i++;
		}
		$result->Close();
		return $vars;
	}

	function tipo_id_terceros()
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT tipo_id_tercero, descripcion FROM tipo_id_terceros ORDER BY indice_de_orden";
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
						return false;
					}
						while (!$result->EOF) {
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
 		 return $vars;
	}

	function TipoProfesional()
	{
		list($dbconn) = GetDBconn();
			$query = "select tipo_profesional,descripcion from tipos_profesionales;";
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
						return false;
					}
						while (!$result->EOF) {
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
 		 return $vars;
	}

	function sexo()
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT sexo_id,descripcion FROM tipo_sexo ORDER BY indice_de_orden";
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					if($result->EOF)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
						return false;
					}
						while (!$result->EOF)
						{
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
		return $vars;
	}

	function Estado()
	{
		list($dbconn) = GetDBconn();
			$query = "SELECT estado,descripcion FROM tipos_estados_profesionales;";
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					if($result->EOF)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
						return false;
					}
						while (!$result->EOF)
						{
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
		return $vars;
	}

	function Especialidad()
	{
		list($dbconn) = GetDBconn();
			$query = "select a.especialidad,b.descripcion from profesionales_especialidades as a, especialidades as b where a.tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and a.tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."' and a.especialidad=b.especialidad;";
			$result = $dbconn->Execute($query);
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
						$vars[$result->fields[0]]=$result->fields[1];
						$result->MoveNext();
					}
				}
				$result->Close();
		return $vars;
	}

	function TipoEspecialidades()
	{
		list($dbconn) = GetDBconn();
			$query = "select especialidad,descripcion from especialidades;";
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					if($result->EOF)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
						return false;
					}
						while (!$result->EOF)
						{
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
		return $vars;
	}

	function EliminarEspecialidad()
	{
		list($dbconn) = GetDBconn();
		$query = "delete from profesionales_especialidades where tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."' and especialidad='".$_REQUEST['espe']."';";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if($this->PantallaProfesional()==false)
		{
			return false;
		}
		return true;
	}

	function GuardarEspecialidad()
	{
		list($dbconn) = GetDBconn();
		$query = "insert into profesionales_especialidades (tipo_id_tercero, tercero_id, especialidad) values ('".$_SESSION['ManProf']['Profesional']['tipoid']."', '".$_SESSION['ManProf']['Profesional']['tercero']."', '".$_REQUEST['especialidad']."');";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return true;
	}

	function GuardarProfesional()
	{
		if($_SESSION['ManProf']['Profesional']['nombrep']!=$_REQUEST['nombrep'] or $_SESSION['ManProf']['Profesional']['TipoProf']!=$_REQUEST['TipoProf'] or $_SESSION['ManProf']['Profesional']['Sexo']!=$_REQUEST['Sexo'] or $_SESSION['ManProf']['Profesional']['TarjProf']!=$_REQUEST['TarjProf'] or $_SESSION['ManProf']['Profesional']['estado']!=$_REQUEST['estado'])
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query = "update profesionales set nombre='".$_REQUEST['nombrep']."', tipo_profesional='".$_REQUEST['TipoProf']."', tarjeta_profesional='".$_REQUEST['TarjProf']."', sexo_id='".$_REQUEST['Sexo']."' where tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."';";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			$query="select count(*) from profesionales_estado where  empresa_id='".$_SESSION['ManProf']['empresa']."' and departamento='".$_SESSION['ManProf']['departamento']."' and tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."';";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			if(!empty($result->fields[0]))
			{
				$query="update profesionales_estado set estado='".$_REQUEST['estado']."' where empresa_id='".$_SESSION['ManProf']['empresa']."' and departamento='".$_SESSION['ManProf']['departamento']."' and tipo_id_tercero='".$_SESSION['ManProf']['Profesional']['tipoid']."' and tercero_id='".$_SESSION['ManProf']['Profesional']['tercero']."';";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			else
			{
				$query="insert into profesionales_estado (estado, empresa_id, departamento, tipo_id_tercero, tercero_id) values ('".$_REQUEST['estado']."', '".$_SESSION['ManProf']['empresa']."', '".$_SESSION['ManProf']['departamento']."', '".$_SESSION['ManProf']['Profesional']['tipoid']."', '".$_SESSION['ManProf']['Profesional']['tercero']."');";
				echo $query;
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			$dbconn->CommitTrans();
		}
		$_SESSION['ManProf']['Profesional']['nombrep']=$_REQUEST['nombrep'];
		$_SESSION['ManProf']['Profesional']['TipoProf']=$_REQUEST['TipoProf'];
		$_SESSION['ManProf']['Profesional']['Sexo']=$_REQUEST['Sexo'];
		$_SESSION['ManProf']['Profesional']['TarjProf']=$_REQUEST['TarjProf'];
		$_SESSION['ManProf']['Profesional']['estado']=$_REQUEST['estado'];
		if($this->PantallaProfesional()==false)
		{
			return false;
		}
		return true;
	}

	function Desicion()
	{
		if(!empty($_REQUEST['volver']))
		{
			if($this->ListarProfe()==false)
			{
				return false;
			}
		}
		else
		{
			if(!empty($_REQUEST['ADICIONAR']))
			{
				if($_REQUEST['especialidad']!='-1')
				{
					if($this->GuardarEspecialidad()==false)
					{
						return false;
					}
				}
				if($this->PantallaProfesional()==false)
				{
					return false;
				}
			}
			else
			{
				if($this->GuardarProfesional()==false)
				{
					return false;
				}
			}
		}
		return true;
	}

	/*function BuscarTercero()
	{
	}*/

	//fin de funciones creacion profesionales

}



?>

