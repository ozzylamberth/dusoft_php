<?php

class app_AgendaBusqueda_user extends classModulo
{

	function app_AgendaBusqueda_user()
	{
		return true;
	}

	function main()
	{
		if($this->BusquedaPermisos()==false)
		{
			return false;
		}
		return true;
	}

	function FuncionParaImprimir()
	{
		$var = $_SESSION['BusquedaAgenda']['datos_impresion'];
		//print_r($var);
		if (!IncludeFile("classes/reports/reports.class.php"))
		{
			$this->error = "No se pudo inicializar la Clase de Reportes";
			$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
			return false;
    		}

		$classReport = new reports;
		$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
		$reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='AgendaBusqueda',$reporte_name='impresion_agenda',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
		if(!$reporte)
		{
			$this->error = $classReport->GetError();
			$this->mensajeDeError = $classReport->MensajeDeError();
			unset($classReport);
			return false;
		}
		$resultado=$classReport->GetExecResultado();
		unset($classReport);
		if(!empty($resultado[codigo]))
		{
			"El PrintReport retorno : " . $resultado[codigo] . "<br>";
		}
		$this->ListadoCitasDia();
		return true;
	}

	function BusquedaIngresoPaciente($tipoid,$paciente)
	{
		list($dbconn) = GetDBconn();
		$sql="select b.evolucion_id from ingresos as a, hc_evoluciones as b where a.ingreso=b.ingreso and a.tipo_id_paciente='$tipoid' and a.paciente_id='$paciente' and b.estado='0' limit 1 offset 0;";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if($result->EOF)
		{
			return 'Historia Vacia';
		}
		else
		{
			return $result->fields[0];
		}
	}

	function BusquedaPermisosUsuarios()
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		if(!empty($_SESSION['SYSTEM_USUARIO_ID']))
		{
			$sql="select a.departamento, b.descripcion as descripcion2, c.empresa_id, c.razon_social as descripcion1, a.sw_mostrar_historia from userpermisos_busqueda_agenda as a, departamentos as b, empresas as c where a.usuario_id=".$_SESSION['SYSTEM_USUARIO_ID']." and a.departamento=b.departamento and b.empresa_id=c.empresa_id;";
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
				while ($data = $result->FetchRow()) {
					$prueba6[$data['descripcion1']][$data['descripcion2']]=$data;
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
			$mtz1[0]='Empresas';
			$mtz1[1]='Departamentos';
			$com[0]=$mtz1;
			$com[1]=$prueba6;
			$url[0]='app';
			$url[1]='AgendaBusqueda';
			$url[2]='user';
			$url[3]='SeleccionParametros';
			$url[4]='BusquedaAgenda';
			if(empty($_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][0]))
			{
				$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][]=$mtz1;
				$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][2]=$prueba6;
			}
			$nombre='MATRIZ PARA BUSQUEDA DE CITAS';
			$accion=ModuloGetURL('system','Menu','user','main');
			$this->salida.=gui_theme_menu_acceso($nombre,$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][0],$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][2],$url,$accion);
			return $com;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no tiene empresas para mostrar.";
			return false;
		}
		return true;
	}

	function tipo_id_paciente()
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT tipo_id_paciente, descripcion FROM tipos_id_pacientes ORDER BY indice_de_orden";
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

	function ProfesionalesCitasDia()
	{
		$_SESSION['BusquedaAgenda']['profesional']=$_REQUEST['tercero_id'].','.$_REQUEST['tipo_id_tercero'];
		$_SESSION['BusquedaAgenda']['nompro']=$_REQUEST['nombre'];
		if($this->ListadoCitasDia()==false)
		{
			return false;
		}
		return true;
	}

	function BusquedaProfesionalesConsulta()
	{
		if(empty($_REQUEST['DiaEspe']))
		{
			$_REQUEST['DiaEspe']=date("Y-m-d");
		}
		return false;
	}

	function Profesionales()
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['TipoBusqueda']==1 and empty($_REQUEST['DiaEspe'])){
			$sql="select a.nombre_tercero as nombre, a.tercero_id, a.tipo_id_tercero
						from
						(select distinct(d.nombre_tercero), b.tercero_id , b.tipo_id_tercero,c.estado
						from agenda_turnos as a
						left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and c.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."' and c.departamento='".$_SESSION['BusquedaAgenda']['departamento']."' and c.estado='1')
						join profesionales_departamentos as x on(c.tipo_id_tercero=x.tipo_id_tercero and c.tercero_id=x.tercero_id and x.departamento='".$_SESSION['BusquedaAgenda']['departamento']."'),
						profesionales as b, terceros as d
						where a.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."'
						and a.profesional_id=b.tercero_id
						and a.tipo_id_profesional=d.tipo_id_tercero
						and a.profesional_id=d.tercero_id and a.tipo_id_profesional=b.tipo_id_tercero
						and date(a.fecha_turno)>=date(now())) as a
						where a.estado is null or a.estado=1 order by a.nombre_tercero;";
		}elseif($_REQUEST['TipoBusqueda']==2){
				$sql="select a.nombre_tercero as nombre, a.tercero_id, a.tipo_id_tercero
							from
							(select distinct(d.nombre_tercero), b.tercero_id , b.tipo_id_tercero,
							c.estado
							from agenda_turnos as a
							left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero
							and c.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."'
							and c.departamento='".$_SESSION['BusquedaAgenda']['departamento']."'
							and c.estado='1')
							join profesionales_departamentos as x on(c.tipo_id_tercero=x.tipo_id_tercero and c.tercero_id=x.tercero_id
							and x.departamento='".$_SESSION['BusquedaAgenda']['departamento']."'),
							profesionales as b, terceros as d
							where  a.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."'
							and a.profesional_id=b.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero
							and a.profesional_id=d.tercero_id and a.tipo_id_profesional=b.tipo_id_tercero
							and b.sexo_id='M' and date(a.fecha_turno)>=date(now())) as a
							where a.estado is null or a.estado=1
							order by a.nombre_tercero;";
				//echo $sql;
		}elseif($_REQUEST['TipoBusqueda']==3){
					$sql="select a.nombre_tercero as nombre, a.tercero_id, a.tipo_id_tercero
								from (select distinct(d.nombre_tercero), b.tercero_id , b.tipo_id_tercero, c.estado
								from agenda_turnos as a
								left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero
								and c.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."'
								and c.departamento='".$_SESSION['BusquedaAgenda']['departamento']."'
								and c.estado='1')
								join profesionales_departamentos as x on(c.tipo_id_tercero=x.tipo_id_tercero and c.tercero_id=x.tercero_id
								and x.departamento='".$_SESSION['BusquedaAgenda']['departamento']."'),
								profesionales as b, terceros as d
								where a.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."'
								and a.profesional_id=b.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero
								and a.profesional_id=d.tercero_id and a.tipo_id_profesional=b.tipo_id_tercero
								and b.sexo_id='F' and date(a.fecha_turno)>=date(now())) as a
								where a.estado is null or a.estado=1
								order by a.nombre_tercero;";
					//echo $sql;
		}
		if(!empty($_REQUEST['DiaEspe']) && $_REQUEST['TipoBusqueda']!=2 && $_REQUEST['TipoBusqueda']!=3){
			if(!($_REQUEST[tipo_consulta]=='-1')){
				if(!empty($_REQUEST[tipo_consulta])){
					$d="and a.tipo_consulta_id=".$_REQUEST[tipo_consulta];
				}
			}
			$sql="select a.nombre_tercero as nombre, a.tercero_id, a.tipo_id_tercero
					from
					(select distinct(d.nombre_tercero), b.tercero_id , b.tipo_id_tercero,
					c.estado
					from agenda_turnos as a
					left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero
					and c.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."'
					and c.departamento='".$_SESSION['BusquedaAgenda']['departamento']."' and c.estado='1')
					join profesionales_departamentos as x on(c.tipo_id_tercero=x.tipo_id_tercero and c.tercero_id=x.tercero_id
					and x.departamento='".$_SESSION['BusquedaAgenda']['departamento']."'),
					profesionales as b, terceros as d
					where  a.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."'
					and a.profesional_id=b.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero
					and a.profesional_id=d.tercero_id and a.tipo_id_profesional=b.tipo_id_tercero
					and date(a.fecha_turno)=date('".$_REQUEST['DiaEspe']."') $d) as a
					where a.estado is null or a.estado=1
					order by a.nombre_tercero;";
		/*	$sql="select a.nombre_tercero as nombre, a.tercero_id, a.tipo_id_tercero
					from
					(select distinct(d.nombre_tercero), b.tercero_id , b.tipo_id_tercero,
					c.estado
					from agenda_turnos as a
					left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero
					and c.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."'
					and c.departamento='".$_SESSION['BusquedaAgenda']['departamento']."'),
					profesionales as b, terceros as d
					where  a.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."'
					and a.profesional_id=b.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero
					and a.profesional_id=d.tercero_id and a.tipo_id_profesional=b.tipo_id_tercero
					and date(a.fecha_turno)=date('".$_REQUEST['DiaEspe']."') $d) as a
					where a.estado is null or a.estado=1
					order by a.nombre_tercero;";*/
			//echo $sql;
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
				$profesional[0][$i]=$result->fields[0];
				$profesional[1][$i]=$result->fields[1];
				$profesional[2][$i]=$result->fields[2];
				$result->MoveNext();
				$i++;
			}
		}
		if($i<>0)
		{
			return $profesional;
		}
		else
		{
			return false;
		}
	}

	function DiasCitas()
	{
		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['DiaEspe']))
		{
			$_REQUEST['DiaEspe']=date("Y-m-d");
		}
		if(empty($_SESSION['AsignacionCitas']['profesional']))
		{
			if(empty($_REQUEST['TipoBusqueda']) or $_REQUEST['TipoBusqueda']==1)
			{
				$sql="select a.fecha_turno
				from
				(select distinct(fecha_turno), c.estado
				from agenda_turnos as a
				left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and c.departamento='".$_SESSION['BusquedaAgenda']['departamento']."' and c.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."'), agenda_citas as b
				where a.agenda_turno_id=b.agenda_turno_id and a.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."' and date(fecha_turno)>=date('".$_REQUEST['DiaEspe']."') and b.sw_estado='0') as a
				where a.estado is null or a.estado=1 order by a.fecha_turno;";
				//echo $sql1="select a.fecha_turno from (select distinct(fecha_turno), c.estado from agenda_turnos as a left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and c.departamento='".$_SESSION['BusquedaAgenda']['departamento']."' and c.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."'), agenda_citas as b where a.agenda_turno_id=b.agenda_turno_id and a.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."' and date(fecha_turno)>=date('".$_REQUEST['DiaEspe']."') and b.sw_estado_cancelacion=0) as a where a.estado is null or a.estado=1 order by a.fecha_turno;";
			}
			else
			{

				if($_REQUEST['TipoBusqueda']==2)
				{
				  //cambio  el sw_estado x sw_cantidad_pacientes_asignados
					$sql="select a.fecha_turno
					from (select distinct(fecha_turno), d.estado
					    from agenda_turnos as a
							left join profesionales_estado as d on (a.profesional_id=d.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero and d.departamento='".$_SESSION['AsignacionCitas']['departamento']."' and d.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'),
				      agenda_citas as b,profesionales as c
				 where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and sw_cantidad_pacientes_asignados < cantidad_pacientes  and a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and sexo_id='M' and date(fecha_turno)>=date(now()) and b.sw_estado='0') as a where a.estado is null or a.estado=1 order by a.fecha_turno;";
					//echo $sql1="select a.fecha_turno from (select distinct(fecha_turno), d.estado from agenda_turnos as a left join profesionales_estado as d on (a.profesional_id=d.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero and d.departamento='".$_SESSION['AsignacionCitas']['departamento']."' and d.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'), agenda_citas as b,profesionales as c where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and sw_estado&lt;cantidad_pacientes  and a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and sexo_id='M' and date(fecha_turno)>=date(now())) as a where a.estado is null or a.estado=1 order by a.fecha_turno;";
				}
				else
				{
					if($_REQUEST['TipoBusqueda']==3)
					{
					  //cambio  el sw_estado x sw_cantidad_pacientes_asignados
						$sql="select a.fecha_turno from
						(select distinct(fecha_turno), d.estado from agenda_turnos as a left join profesionales_estado as d on (a.profesional_id=d.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero and d.departamento='".$_SESSION['AsignacionCitas']['departamento']."' and d.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'),
						agenda_citas as b,profesionales as c
						where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and sw_cantidad_pacientes_asignados < cantidad_pacientes  and a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and sexo_id='F' and date(fecha_turno)>=date(now()) and b.sw_estado='0') as a where a.estado is null or a.estado=1 order by a.fecha_turno;";
						//echo $sql1="select a.fecha_turno from (select distinct(fecha_turno), d.estado from agenda_turnos as a left join profesionales_estado as d on (a.profesional_id=d.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero and d.departamento='".$_SESSION['AsignacionCitas']['departamento']."' and d.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'), agenda_citas as b,profesionales as c where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and sw_estado&lt;cantidad_pacientes  and a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and sexo_id='F' and date(fecha_turno)>=date(now())) as a where a.estado is null or a.estado=1 order by a.fecha_turno;";
					}
				}
			}
		}
		else
		{
			$a=explode(",",$_SESSION['AsignacionCitas']['profesional']);
			//cambio  el sw_estado x sw_cantidad_pacientes_asignados
			$sql="select distinct(fecha_turno) from agenda_turnos as a, agenda_citas as b where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and sw_cantidad_pacientes_asignados < cantidad_pacientes and profesional_id='".$a[0]."' and tipo_id_profesional='".$a[1]."' and date(fecha_turno)>=date(now()) and b.sw_estado='0' order by fecha_turno;";
		//$sql1="select distinct(fecha_turno) from agenda_turnos as a, agenda_citas as b where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and sw_estado&lt;cantidad_pacientes and profesional_id='".$a[0]."' and tipo_id_profesional='".$a[1]."' and date(fecha_turno)>=date(now()) order by fecha_turno;";
			//echo $sql1;
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
				$fechas[$i]=$result->fields[0];
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			$fechas1=array_unique($fechas);
			array_multisort($fechas1);
			return $fechas1;
		}
		else
		{
			return false;
		}
	}

	function CitasDia()
	{
		$a=explode(",",$_SESSION['BusquedaAgenda']['profesional']);
		list($dbconn) = GetDBconn();
    //$dbconn->debug=true;
		$sql="select a.hora, b.fecha_turno, a.agenda_turno_id, c.paciente_id, c.tipo_id_paciente,
					d.primer_nombre || ' ' || d.segundo_nombre || ' ' || d.primer_apellido || ' ' || d.segundo_apellido as nombre_completo,
					e.plan_id, e.plan_descripcion, b.tipo_consulta_id, f.descripcion, c.cargo_cita, c.sw_atencion, h.sw_estado,
					i.tipo_id_tercero, i.tercero_id, i.nombre_tercero
					from agenda_citas as a
					left join  agenda_citas_asignadas as c on (a.agenda_cita_id=c.agenda_cita_id)
					left join pacientes as d on(c.paciente_id=d.paciente_id and c.tipo_id_paciente=d.tipo_id_paciente)
					left join planes as e on(c.plan_id=e.plan_id)
					left join os_cruce_citas as g on(c.agenda_cita_asignada_id=g.agenda_cita_asignada_id)
					left join os_maestro as h on(g.numero_orden_id=h.numero_orden_id),
					agenda_turnos as b ,tipos_servicios_ambulatorios as f, terceros as i
					where b.profesional_id=i.tercero_id and b.tipo_id_profesional=i.tipo_id_tercero
					and a.agenda_turno_id=b.agenda_turno_id and b.profesional_id='".$a[0]."' and b.tipo_id_profesional='".$a[1]."'
					and date(b.fecha_turno)=date('".$_REQUEST['DiaEspe']."')
					and b.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."'
					and b.tipo_consulta_id=f.tipo_servicio_amb_id
					and a.sw_estado IN ('0', '1') order by a.hora, b.tipo_consulta_id;";
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
				$cita[$i]=$result->GetRowAssoc(false);
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $cita;
		}
		else
		{
			return false;
		}
	}

	function BusquedaTipoConsulta()
	{
		list($dbconn) = GetDBconn();
		$sql="select distinct(a.tipo_consulta_id), c.descripcion from agenda_turnos as a, tipos_consulta as b, tipos_servicios_ambulatorios as c where b.departamento='".$_SESSION['BusquedaAgenda']['departamento']."' and a.empresa_id='".$_SESSION['BusquedaAgenda']['empresa']."' and a.tipo_consulta_id=b.tipo_consulta_id and b.tipo_consulta_id=c.tipo_servicio_amb_id and date(a.fecha_turno)=date('".$_REQUEST['DiaEspe']."');";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while (!$result->EOF)
		{
			$cita[$i]=$result->GetRowAssoc(false);
			$i++;
			$result->MoveNext();
		}
		return $cita;
	}

		function BuscarDepartamento()
	  {
		 list($dbconn) = GetDBconn();
          $query = "SELECT empresa_id,
				             centro_utilidad,
				             unidad_funcional,
				             departamento,
				             descripcion,
				             servicio
				      FROM departamentos;";
         $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
         {
	 	      $this->error = "Error al Cargar el Modulo";
		      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
	        return false;
         }
        if(!$resulta->EOF)
         {
	  	     while(!$resulta->EOF)
	  	      {
		 	  	    $dpt[]=$resulta->GetRowAssoc($ToUpper = false);
			  	    $resulta->MoveNext();
	  	      }
	       }

      return $dpt;
	  }
}
?>
