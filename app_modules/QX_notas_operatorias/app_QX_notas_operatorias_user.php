<?php
class app_QX_notas_operatorias_user extends classModulo
{

	function app_QX_notas_operatorias_user()
	{
	  $this->limit=GetLimitBrowser();
		return true;
	}
/**
* Funcion que llama la forma donde se muestran los departamentos del sistema a los que el usuario puede accesar
* @return array
*/
	function main(){
	  if(!$this->FrmLogueoCirugias()){
      return false;
    }
		return true;
	}
/**
* Funcion que consulta en la base de datos los permisos del usuario para trabajar en los departamentos
* @return array
*/
	function LogueoCirugias(){
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$query = "SELECT x.empresa_id,y.razon_social as descripcion1,x.centro_utilidad,z.descripcion as descripcion2,x.departamento,l.descripcion as descripcion3  FROM userpermisos_cirugia x,empresas as y,centros_utilidad as z,departamentos as l WHERE x.usuario_id = ".UserGetUID()." AND x.empresa_id=y.empresa_id AND x.empresa_id=z.empresa_id AND x.centro_utilidad=z.centro_utilidad AND x.empresa_id=l.empresa_id AND x.centro_utilidad=l.centro_utilidad AND l.departamento=x.departamento";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		if($result->EOF){
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}else{
			while ($data = $result->FetchRow()) {
				$datos[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
			}
			$mtz[0]="EMPRESA";
			$mtz[1]="CENTRO UTILIDAD";
			$mtz[4]="DEPARTAMENTO";
			$vars[0]=$mtz;
			$vars[1]=$datos;
			return $vars;
		}
	}

/**
* Funcion que trae los datos del lugar donde esta logueado el usuario
* @return boolean
*/
	function consultaLogueo(){
	  $_SESSION['LocalCirugias']['Empresa']=$_REQUEST['datos_query']['empresa_id'];
		$_SESSION['LocalCirugias']['NombreEmp']=$_REQUEST['datos_query']['descripcion1'];
		$_SESSION['LocalCirugias']['CentroUtili']=$_REQUEST['datos_query']['centro_utilidad'];
		$_SESSION['LocalCirugias']['NombreCU']=$_REQUEST['datos_query']['descripcion2'];
		$_SESSION['LocalCirugias']['Departamento']=$_REQUEST['datos_query']['departamento'];
		$_SESSION['LocalCirugias']['NombreDpto']=$_REQUEST['datos_query']['descripcion3'];
		$this->listadoCirugiasNotas();
		return true;
	}

	function CirugiasporNotas(){

		list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT b.qx_cumplimiento_id,a.tipo_tercero_id,a.tercero_id,
		(SELECT d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_apellido as nombre
		FROM pacientes d
		WHERE d.tipo_id_paciente=c.tipo_id_paciente AND d.paciente_id=c.paciente_id) as nombre
		FROM profesionales_usuarios a,qx_cumplimiento_procedimientos b,qx_cumplimientos c
		WHERE a.usuario_id='".UserGetUID()."' AND a.tipo_tercero_id=b.tipo_id_cirujano AND
		a.tercero_id=b.cirujano_id AND b.qx_cumplimiento_id=c.qx_cumplimiento_id AND c.estado=1";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
	}

	function CirugiasSincumplir(){

		list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT b.programacion_id,a.tipo_tercero_id,a.tercero_id,
		(SELECT d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_apellido as nombre
		FROM pacientes d
		WHERE d.tipo_id_paciente=c.tipo_id_paciente AND d.paciente_id=c.paciente_id) as nombre
		FROM profesionales_usuarios a,qx_procedimientos_programacion b,qx_programaciones c
		WHERE a.usuario_id='".UserGetUID()."' AND a.tipo_tercero_id=b.tipo_id_cirujano AND
		a.tercero_id=b.cirujano_id AND b.programacion_id=c.programacion_id AND c.estado=1";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
	}













	/*function BuscarPacientesEstacion()
	{
		//GLOBAL $ADODB_FETCH_MODE;
		//$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		list($dbconn) = GetDBconn();
		$sql="SELECT c.paciente_id, c.tipo_id_paciente, c.primer_nombre || ' ' ||
					c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre,
					e.color, d.hora_llegada, e.tiempo_atencion, b.ingreso, f.evolucion_id,
					to_char(f.fecha,'YYYY-MM-DD HH24:MI') as fecha, f.usuario_id, h.nombre,
					a.estacion_id, d.nivel_triage_id, d.plan_id, d.triage_id, d.punto_triage_id,
					d.punto_admision_id, d.sw_no_atender, i.numerodecuenta

					FROM pacientes_urgencias as a join ingresos as b
					on (a.ingreso=b.ingreso and a.estacion_id='".$_SESSION['AtencionUrgencias']['estacion_id']."')
					join pacientes as c on (b.paciente_id=c.paciente_id and b.tipo_id_paciente=c.tipo_id_paciente
					and b.estado=1)
					left join triages as d on (a.triage_id=d.triage_id)
					left join niveles_triages as e on (d.nivel_triage_id=e.nivel_triage_id
					and e.nivel_triage_id!=0) left join hc_evoluciones as f on (b.ingreso=f.ingreso and f.estado=1)
					left join profesionales_usuarios as g on (f.usuario_id=g.usuario_id)
					left join profesionales as h on (g.tercero_id=h.tercero_id
					and g.tipo_tercero_id=h.tipo_id_tercero)
					left join cuentas as i on(a.ingreso=i.ingreso and i.estado=1)
					order by e.indice_de_orden, d.hora_llegada;";
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
			$spy=0;
			while (!$result->EOF)
			{
				if(!empty($result->fields[4]))
				{
					$a=explode("-",$result->fields[4]);
					$b=explode(" ",$a[2]);
					$c=explode(":",$b[1]);
					if(date("Y-m-d H:i:s",mktime($c[0],$c[1],$c[2],$a[1],$b[0],$a[0]))<date("Y-m-d H:i:s",mktime(date("H"), (date("i")-$result->fields[5]), 0, date("m"), date("d"), date("Y"))))
					{
						$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=1;
						if($result->fields[3]=='AZUL')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelazulclaro';
						}
						elseif($result->fields[3]=='ROJO')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelrojoclaro';
						}
						elseif($result->fields[3]=='VERDE')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelverdeclaro';
						}
						elseif($result->fields[3]=='AMARILLO')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelamarilloclaro';
						}
						else
						{
							if($spy==0)
							{
								$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_oscuro';
								$spy=1;
							}
							else
							{
								$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_claro';
								$spy=0;
							}
						}
					}
					else
					{
						$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=2;
						if($result->fields[3]=='AZUL')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelazulclaro';
						}
						elseif($result->fields[3]=='ROJO')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelrojoclaro';
						}
						elseif($result->fields[3]=='VERDE')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelverdeclaro';
						}
						elseif($result->fields[3]=='AMARILLO')
						{
							$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='tr_tdlabelamarilloclaro';
						}
						else
						{
							if($spy==0)
							{
								$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_oscuro';
								$spy=1;
							}
							else
							{
								$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_claro';
								$spy=0;
							}
						}
					}
					$total=(mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y"))-mktime($c[0],$c[1],$c[2],$a[1],$b[0],$a[0]));
					//echo 'Segundos:'.$segundos=$total%60;
					$total=floor($total/60);
					$minutos=($total%60);
					$total=floor($total/60);
					$horas=($total%24);
					$total=floor($total/24);
					$mostrar="";
					if(!empty($total))
					{
						$mostrar=$total.' dias, ';
					}
					$mostrar.=$horas.':'.$minutos;
					$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][1]=$mostrar;
				}
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][0]=1;
				if($spy==0 and empty($prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]))
				{
					$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_oscuro';
					$spy=1;
				}
				else
				{
					if(empty($prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]))
					{
						$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][2]='modulo_list_claro';
						$spy=0;
					}
				}
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][3]=$result->fields[6];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[7];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[8];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[9];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[10];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[11];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[12];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[13];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[14];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[15];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[16];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[17];
				$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=$result->fields[18];
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			//print_r($prueba);
			return $prueba;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No existen paciente para esta estacion de enfermeria.";
			return false;
		}
	}

	function ClasificarTriage()
	{
		$_SESSION['Atencion']['estacion_id']=$_REQUEST['estacion_id'];
		$_SESSION['Atencion']['ingreso']=$_REQUEST['ingreso'];
		$_SESSION['Atencion']['modulo']=$_REQUEST['moduloh'];
		$_SESSION['TRIAGE']['ATENCION']['tipo_id_paciente']=$_REQUEST['tipo_id_paciente'];
		$_SESSION['TRIAGE']['ATENCION']['paciente_id']=$_REQUEST['paciente_id'];
		$_SESSION['TRIAGE']['ATENCION']['plan_id']=$_REQUEST['plan_id'];
		$_SESSION['TRIAGE']['ATENCION']['triage_id']=$_REQUEST['triage_id'];
		$_SESSION['TRIAGE']['ATENCION']['punto_triage_id']=$_REQUEST['punto_triage_id'];
		$_SESSION['TRIAGE']['ATENCION']['punto_admision_id']=$_REQUEST['punto_admision_id'];
		$_SESSION['TRIAGE']['ATENCION']['sw_no_atender']=$_REQUEST['sw_no_atender'];
		$_SESSION['TRIAGE']['ATENCION']['RETORNO']['contenedor']='app';
		$_SESSION['TRIAGE']['ATENCION']['RETORNO']['modulo']='AtencionInterconsulta';
		$_SESSION['TRIAGE']['ATENCION']['RETORNO']['tipo']='user';
		$_SESSION['TRIAGE']['ATENCION']['RETORNO']['metodo']='RetornoTriage';
		$_SESSION['TRIAGE']['ATENCION']['RETORNO']['argumentos']=array();
		$this->ReturnMetodoExterno('app','Triage','user','LlamarClasificacionMedico');
		return true;
	}

	function RetornoTriage()
	{
		unset($_SESSION['TRIAGE']['ATENCION']);
		if(empty($_SESSION['RETORNO']['TRIAGE']['ATENCION']) or empty($_SESSION['Atencion']['ingreso']))
		{
			unset($_SESSION['RETORNO']['TRIAGE']['ATENCION']);
			$this->ListadoPaciente();
		}
		else
		{
			unset($_SESSION['RETORNO']['TRIAGE']['ATENCION']);
			$this->ContinuarHistoria();
		}
		return true;
	}


	function BuscarPacienteHosptalizados()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT MH.cama, B.pieza, C.ingreso, D.paciente_id, D.tipo_id_paciente,
							E.primer_nombre || ' ' || E.segundo_nombre || ' ' || E.primer_apellido || ' ' ||
							E.segundo_apellido as nombretotal, G.evolucion_id, G.usuario_id,
							to_char(G.fecha,'YYYY-MM-DD HH24:MI') as fecha, I.nombre,
							A.numerodecuenta
							FROM movimientos_habitacion AS MH, ( SELECT ID.ingreso_dpto_id, ID.numerodecuenta,
							ID.departamento, ID.estacion_id, EE.descripcion, ID.orden_hospitalizacion_id
							FROM ingresos_departamento ID, estaciones_enfermeria EE

							WHERE ID.estado = '1'
							AND EE.estacion_id = ID.estacion_id
							AND EE.estacion_id = '".$_SESSION['AtencionUrgencias']['estacion_id']."' ) AS A,
							camas B, cuentas C, ingresos D left join hc_evoluciones as G on(D.ingreso=G.ingreso
							and G.estado=1) left join profesionales_usuarios as H on(G.usuario_id=H.usuario_id)
							left join profesionales as I on(H.tercero_id=I.tercero_id
							and H.tipo_tercero_id=I.tipo_id_tercero), pacientes E, departamentos F
							WHERE MH.ingreso_dpto_id = A.ingreso_dpto_id AND MH.fecha_egreso IS NULL
							AND MH.cama = B.cama AND C.numerodecuenta = A.numerodecuenta
							AND C.ingreso = D.ingreso AND C.estado = '1'
							AND D.paciente_id = E.paciente_id
							AND D.tipo_id_paciente = E.tipo_id_paciente
							AND F.departamento = A.departamento
							ORDER BY MH.cama, B.pieza,G.evolucion_id;";
		//echo $query;
		$result = $dbconn->Execute($query);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$spy=0;
			while (!$result->EOF)
			{
				$prueba[$result->fields[3]][$result->fields[4]][$result->fields[5]][]=$result->GetRowAssoc(false);
				$hospitaesta[0][$i]=$result->fields[0];
				$hospitaesta[1][$i]=$result->fields[1];
				$hospitaesta[2][$i]=$result->fields[2];
				$hospitaesta[3][$i]=$result->fields[3];
				$hospitaesta[4][$i]=$result->fields[4];
				$hospitaesta[5][$i]=$result->fields[5];
				$i++;
				$result->MoveNext();
			}
		}
		$hospitaesta1[]=$hospitaesta;
		$hospitaesta1[]=$prueba;
		if($i<>0)
		{
			return $hospitaesta1;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No existen paciente para esta estacion de enfermeria.";
			return false;
		}
	}

	function PacientesClasificacionTriage()
	{
		list($dbconn) = GetDBconn();
		$sql="select b.tipo_id_paciente, b.paciente_id, b.plan_id, b.triage_id,
					b.punto_triage_id, b.punto_admision_id, b.sw_no_atender, c.primer_nombre || ' ' ||
					c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre
					from triage_no_atencion as a join triages as b on(a.triage_id=b.triage_id
					and a.estacion_id='".$_SESSION['AtencionUrgencias']['estacion_id']."'
					and b.nivel_triage_id='0')
					join pacientes as c on (b.tipo_id_paciente=c.tipo_id_paciente
					and b.paciente_id=c.paciente_id);";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$i=0;
			while(!$result->EOF)
			{
				$pacientestriage[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc(false);
				$result->MoveNext();
				$i=1;
			}
			if($i==1)
			{
				return $pacientestriage;
			}
			else
			{
				return false;
			}
		}
	}

	function TipoModulo()
	{
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		$sql="select hc_modulo from system_hc_modulos where rips_tipo_id=11";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $result->fields[0];
	}

	function ReconocerProfesional()
	{
		list($dbconn) = GetDBconn();
		$a=UserGetUID();
		if(!empty($a))
		{
			$sql="SELECT b.tipo_profesional
						FROM profesionales_usuarios as a,
						profesionales as b
						WHERE a.usuario_id=".$a."
						and a.tipo_tercero_id=b.tipo_id_tercero and a.tercero_id=b.tercero_id;";
		}
		else
		{
			return false;
		}
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer profesional";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if(!$result->EOF)
			{
				return $result->fields[0];
			}
			else
			{
				return false;
			}
		}
	}




	function RevisarInterConsultas($ingreso)
	{
			list($dbconn) = GetDBconn();
			$a=UserGetUID();
	 $sql="SELECT b.especialidad,a.evolucion_id,v.descripcion,a.plan_id,a.os_tipo_solicitud_id,a.hc_os_solicitud_id
							FROM hc_os_solicitudes a,hc_os_solicitudes_interconsultas b,planes c,especialidades v
							,hc_evoluciones m
							,profesionales_especialidades z
							,profesionales_usuarios p
							WHERE a.evolucion_id=m.evolucion_id
							AND a.plan_id=c.plan_id
							AND v.especialidad=b.especialidad
							AND a.hc_os_solicitud_id=b.hc_os_solicitud_id
							AND m.ingreso='$ingreso'
							AND m.estado='0'
							AND b.especialidad in(z.especialidad)
							AND z.tercero_id=p.tercero_id
						  AND z.tipo_id_tercero=p.tipo_tercero_id
					  	AND p.usuario_id=$a";

			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al traer las Interconsultas";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			$i=0;
			while (!$result->EOF)
			{
						$var[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
			}
			return $var;
	}*/


}
?>
