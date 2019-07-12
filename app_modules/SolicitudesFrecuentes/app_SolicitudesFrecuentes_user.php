
<?php

/**
* Modulo de Solicitudes Frecuentes (PHP).
*
* Modulo que se establece los apoyos diadnósticos, los medicamentos
* y los procedimientos quirurgicos más solicitados o utilizados
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_SolicitudesFrecuentes_user.php
*
* Clase que establece los diversos métodos para establecer los apoyos diagnósticos,
* los medicamentos y los procedimientos quirurgicos utilizados frecuentemente,
* según la especialidad o el departamento a donde pertenezcan
* Modulo que está inmerso en el modulo de Parametros de Historia Clinica,
* es decir que puede ser accesado desde el anterior o desde el mismo.
**/

class app_SolicitudesFrecuentes_user extends classModulo
{
	var $uno;//para los errores
	var $limit;
	var $conteo;//para saber cuantos registros encontró

	function app_SolicitudesFrecuentes_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->PrincipalSolfre2();
		return true;
	}

	function BorrarSolfre()//Función que borra las variables de sesion del modulo
	{
		UNSET($_SESSION['solfre']);
		UNSET($_SESSION['solfr']);
		$this->ReturnMetodoExterno('app','ParametrosHC','user','PrincipalParaHC');//si cambia a permisos 2
		return true;
	}

	function UsuariosSolfre()//Función de permisos
	{
		list($dbconn) = GetDBconn();
		$usuario=UserGetUID();
		$query = "SELECT A.empresa_id,
				B.razon_social AS descripcion1
				FROM userpermisos_solicitudes_frecuentes AS A,
				empresas AS B
				WHERE A.usuario_id=".$usuario."
				AND A.empresa_id=B.empresa_id
				ORDER BY descripcion1;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var1[$resulta->fields[1]]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		$mtz[0]='EMPRESAS';
		$url[0]='app';
		$url[1]='SolicitudesFrecuentes';
		$url[2]='user';
		$url[3]='PrincipalSolfre';
		$url[4]='permisosolfre';
		$this->salida .=gui_theme_menu_acceso('SOLICITUDES FRECUENTES', $mtz, $var1, $url, ModuloGetURL('system','Menu'));
		return true;
	}

	function SetStyle($campo)//Mensaje de error en caso de no encontrar los datos
	{
		if ($this->frmError[$campo] || $campo=="MensajeError")
		{
			if ($campo=="MensajeError")
			{
				return ("<tr><td class='label_error' colspan='2' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			else
			{
				return ("label_error");
			}
		}
		return ("label");
	}

	function CalcularNumeroPasos($conteo)//Función de las barras
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	function CalcularBarra($paso)//Función de las barras
	{
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	function CalcularOffset($paso)//Función de las barras
	{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	function BuscarDepartamentosSolfre($empresa)//Busca los departamentos de la empresa
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT departamento,
				descripcion
				FROM departamentos
				WHERE empresa_id='".$empresa."'
				ORDER BY descripcion;";
	/*	$query = "SELECT departamento,
				descripcion
				FROM departamentos
				WHERE empresa_id='".$empresa."'
				AND sw_internacion='1'
				ORDER BY descripcion;";*/
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $var;
	}

	function BuscarEspecialidadSolfre()//Busca las especialidades de los médicos
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigosolf'])
		{
			$codigo=$_REQUEST['codigosolf'];
			$busqueda="WHERE especialidad LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descrisolf'])
		{
			if($busqueda==NULL)
			{
				$codigo=STRTOUPPER($_REQUEST['descrisolf']);
				$busqueda2="WHERE UPPER(descripcion) LIKE '%$codigo%'";
			}
			else
			{
				$codigo=STRTOUPPER($_REQUEST['descrisolf']);
				$busqueda2="AND UPPER(descripcion) LIKE '%$codigo%'";
			}
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT especialidad,
					descripcion
					FROM especialidades
					$busqueda
					$busqueda2
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "SELECT especialidad,
				descripcion
				FROM especialidades
				$busqueda
				$busqueda2
				ORDER BY descripcion
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $var;
	}

	function ValidarEspecialidadSolfre()//No guarda la especialidad, solo la establece como selecciona
	{
		if($_POST['volver']=='ACEPTAR')
		{
			if(!empty($_POST['seleccion']))
			{
				$var=explode(',',$_POST['seleccion']);
				if($var[0]==$_SESSION['solfr']['especieleg'])
				{
					$this->frmError["MensajeError"]="NO SE CAMBIÓ LA ESPECIALIDAD";
				}
				else if($var[0]=='BORRAR')
				{
					$this->frmError["MensajeError"]="HA SIDO BORRADA LA ESPECIALIDAD";
					$_SESSION['solfr']['especieleg']='';
					$_SESSION['solfr']['desespeleg']='';
				}
				else
				{
					$this->frmError["MensajeError"]="HA SIDO SELECCIONADA UNA ESPECIALIDAD";
					$_SESSION['solfr']['especieleg']=$var[0];
					$_SESSION['solfr']['desespeleg']=$var[1];
				}
			}
			else if(empty($_POST['seleccion']) AND $_SESSION['solfr']['especieleg']==NULL)
			{
				$this->frmError["MensajeError"]="NO HA SIDO SELECCIONADA UNA ESPECIALIDAD";
			}
			else if(empty($_POST['seleccion']) AND $_SESSION['solfr']['especieleg']<>NULL)
			{
				$this->frmError["MensajeError"]="NO SE CAMBIÓ EL USUARIO";
			}
		}
		else if($_POST['volver']=='VOLVER')
		{
			$this->frmError["MensajeError"]="LA SELECCIÓN DE UNA ESPECIALIDAD<br>HA SIDO CANCELADA POR EL USUARIO";
		}
		$this->uno=1;
		if($_REQUEST['indice']==1)
		{
			$this->RelacionarApoyosdiSolfre();
		}
		else if($_REQUEST['indice']==2)
		{
			$this->RelacionarMedicamentosSolfre();
		}
		else if($_REQUEST['indice']==3)
		{
			$this->ConsultarApoyosdiSolfre();
		}
		else if($_REQUEST['indice']==4)
		{
			$this->ConsultarMedicamentosSolfre();
		}
		return true;
	}

	/********************FUNCIONES DE APOYOS DIAGNÓSTICOS********************/

	function BuscarRelacionarApoyosdiSolfre($departamento,$especialidad)//Busca todos los apoyos según los cargos y los ya guardados
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigosolf'])
		{
			$codigo=$_REQUEST['codigosolf'];
			$busqueda="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descrisolf'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrisolf']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($especialidad<>NULL)
		{
			$busqueda3="AND B.especialidad='".$especialidad."'";
		}
		else
		{
			$busqueda3="AND B.especialidad IS NULL";
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT DISTINCT A.cargo,
					A.descripcion,
					B.especialidad,
					B.apoyod_solicitud_frecuencia_id,
					B.departamento
					FROM apoyod_tipos AS C,
					cups AS A
					LEFT JOIN apoyod_solicitud_frecuencia AS B ON
					(
					A.cargo=B.cargo
					AND B.departamento='".$departamento."'
					$busqueda3
					)
					WHERE A.grupo_tipo_cargo=C.apoyod_tipo_id
					$busqueda
					$busqueda2
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "SELECT DISTINCT A.cargo,
				A.descripcion,
				B.especialidad,
				B.apoyod_solicitud_frecuencia_id,
				B.departamento
				FROM apoyod_tipos AS C,
				cups AS A
				LEFT JOIN apoyod_solicitud_frecuencia AS B ON
				(
				A.cargo=B.cargo
				AND B.departamento='".$departamento."'
				$busqueda3
				)
				WHERE A.grupo_tipo_cargo=C.apoyod_tipo_id
				$busqueda
				$busqueda2
				ORDER BY A.cargo
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $var;
	}

	function ValidarRelacionarApoyosdiSolfre()//Guarda las relaciones entre el departamento, la especialidad y el cargo (apoyo diagnóstico)
	{
		if($_SESSION['solfr']['cargosfrec']==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE: 0";
		}
		else
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$contador1=$contador2=0;
			for($i=0;$i<sizeof($_SESSION['solfr']['cargosfrec']);$i++)
			{
				if($_POST['frecuente'.$i]<>NULL AND $_SESSION['solfr']['cargosfrec'][$i]['departamento']==NULL)
				{
					$contador1++;
					if($_SESSION['solfr']['especieleg']<>NULL)
					{
						$query = "INSERT INTO apoyod_solicitud_frecuencia
								(departamento,
								especialidad,
								cargo)
								VALUES
								('".$_SESSION['solfr']['departeleg']."',
								'".$_SESSION['solfr']['especieleg']."',
								'".$_SESSION['solfr']['cargosfrec'][$i]['cargo']."');";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollBackTrans();
							return false;
						}
					}
					else
					{
						$query = "INSERT INTO apoyod_solicitud_frecuencia
								(departamento,
								cargo)
								VALUES
								('".$_SESSION['solfr']['departeleg']."',
								'".$_SESSION['solfr']['cargosfrec'][$i]['cargo']."');";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollBackTrans();
							return false;
						}
					}
				}
				else if($_POST['frecuente'.$i]==NULL AND $_SESSION['solfr']['cargosfrec'][$i]['departamento']<>NULL)
				{
					$contador2++;
					$query = "DELETE FROM apoyod_solicitud_frecuencia
							WHERE apoyod_solicitud_frecuencia_id='".$_SESSION['solfr']['cargosfrec'][$i]['apoyod_solicitud_frecuencia_id']."';";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollBackTrans();
						return false;
					}
				}
				$_POST['frecuente'.$i]='';
			}
			$dbconn->CommitTrans();
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
		}
		$this->uno=1;
		$this->RelacionarApoyosdiSolfre();
		return true;
	}

	function BuscarConsultarApoyosdiSolfre($departamento,$especialidad)//Busca todos los apoyos según los cargos ya guardados
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigosolf'])
		{
			$codigo=$_REQUEST['codigosolf'];
			$busqueda="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descrisolf'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrisolf']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($especialidad<>NULL)
		{
			$busqueda3="AND B.especialidad='".$especialidad."'";
		}
		else
		{
			$busqueda3='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT DISTINCT A.cargo,
					A.descripcion,
					D.descripcion AS des1
					FROM cups AS A,
					apoyod_solicitud_frecuencia AS B
					LEFT JOIN especialidades AS D ON
					(D.especialidad=B.especialidad),
					apoyod_tipos AS C
					WHERE B.departamento='".$departamento."'
					AND A.cargo=B.cargo
					AND A.grupo_tipo_cargo=C.apoyod_tipo_id
					$busqueda
					$busqueda2
					$busqueda3
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "SELECT DISTINCT A.cargo,
				A.descripcion,
				D.descripcion AS des1
				FROM cups AS A,
				apoyod_solicitud_frecuencia AS B
				LEFT JOIN especialidades AS D ON
				(D.especialidad=B.especialidad),
				apoyod_tipos AS C
				WHERE B.departamento='".$departamento."'
				AND A.cargo=B.cargo
				AND A.grupo_tipo_cargo=C.apoyod_tipo_id
				$busqueda
				$busqueda2
				$busqueda3
				ORDER BY A.cargo
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $var;
	}

	/********************FUNCIONES DE MEDICAMENTOS********************/

	function BuscarRelacionarMedicamentosSolfre($departamento,$especialidad)//Busca todos los medicamentos según los cargos y los ya guardados
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigosolf'])
		{
			$codigo=$_REQUEST['codigosolf'];
			$busqueda="AND D.codigo_producto LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descrisolf'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrisolf']);
			$busqueda2="AND UPPER(D.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($especialidad<>NULL)
		{
			$busqueda3="AND E.especialidad='".$especialidad."'";
		}
		else
		{
			$busqueda3="AND E.especialidad IS NULL";
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT DISTINCT A.grupo_id,
					A.descripcion AS des1,
					B.clase_id,
					B.descripcion AS des2,
					C.subclase_id,
					C.descripcion AS des3,
					D.codigo_producto,
					D.descripcion,
					E.inv_solicitud_frecuencia_id,
					E.departamento,
					E.especialidad
					FROM inv_grupos_inventarios AS A,
					inv_clases_inventarios AS B,
					inv_subclases_inventarios AS C,
					inventarios_productos AS D
					LEFT JOIN inv_solicitud_frecuencia AS E ON
					(
					D.codigo_producto=E.codigo_producto
					AND E.departamento='".$departamento."'
					$busqueda3
					)
					WHERE A.sw_medicamento='1'
					AND A.grupo_id=B.grupo_id
					AND B.grupo_id=C.grupo_id
					AND B.clase_id=C.clase_id
					AND C.grupo_id=D.grupo_id
					AND C.clase_id=D.clase_id
					AND C.subclase_id=D.subclase_id
					$busqueda
					$busqueda2
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "SELECT DISTINCT A.grupo_id,
				A.descripcion AS des1,
				B.clase_id,
				B.descripcion AS des2,
				C.subclase_id,
				C.descripcion AS des3,
				D.codigo_producto,
				D.descripcion,
				E.inv_solicitud_frecuencia_id,
				E.departamento,
				E.especialidad
				FROM inv_grupos_inventarios AS A,
				inv_clases_inventarios AS B,
				inv_subclases_inventarios AS C,
				inventarios_productos AS D
				LEFT JOIN inv_solicitud_frecuencia AS E ON
				(
				D.codigo_producto=E.codigo_producto
				AND E.departamento='".$departamento."'
				$busqueda3
				)
				WHERE A.sw_medicamento='1'
				AND A.grupo_id=B.grupo_id
				AND B.grupo_id=C.grupo_id
				AND B.clase_id=C.clase_id
				AND C.grupo_id=D.grupo_id
				AND C.clase_id=D.clase_id
				AND C.subclase_id=D.subclase_id
				$busqueda
				$busqueda2
				ORDER BY D.codigo_producto
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $var;
	}

	function ValidarRelacionarMedicamentosSolfre()//Guarda las relaciones entre el departamento, la especialidad y el medicamento
	{
		if($_SESSION['solfr']['medicafrec']==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE: 0";
		}
		else
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$contador1=$contador2=0;
			for($i=0;$i<sizeof($_SESSION['solfr']['medicafrec']);$i++)
			{
				if($_POST['frecuente'.$i]<>NULL AND $_SESSION['solfr']['medicafrec'][$i]['departamento']==NULL)
				{
					$contador1++;
					if($_SESSION['solfr']['especieleg']<>NULL)
					{
						$query = "INSERT INTO inv_solicitud_frecuencia
								(departamento,
								especialidad,
								codigo_producto)
								VALUES
								('".$_SESSION['solfr']['departeleg']."',
								'".$_SESSION['solfr']['especieleg']."',
								'".$_SESSION['solfr']['medicafrec'][$i]['codigo_producto']."');";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollBackTrans();
							return false;
						}
					}
					else
					{
						$query = "INSERT INTO inv_solicitud_frecuencia
								(departamento,
								codigo_producto)
								VALUES
								('".$_SESSION['solfr']['departeleg']."',
								'".$_SESSION['solfr']['medicafrec'][$i]['codigo_producto']."');";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollBackTrans();
							return false;
						}
					}
				}
				else if($_POST['frecuente'.$i]==NULL AND $_SESSION['solfr']['medicafrec'][$i]['departamento']<>NULL)
				{
					$contador2++;
					$query = "DELETE FROM inv_solicitud_frecuencia
							WHERE inv_solicitud_frecuencia_id='".$_SESSION['solfr']['medicafrec'][$i]['inv_solicitud_frecuencia_id']."';";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollBackTrans();
						return false;
					}
				}
				$_POST['frecuente'.$i]='';
			}
			$dbconn->CommitTrans();
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
		}
		$this->uno=1;
		$this->RelacionarMedicamentosSolfre();
		return true;
	}

	function BuscarConsultarMedicamentosSolfre($departamento,$especialidad)//Busca todos los medicamentos según los cargos ya guardados
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigosolf'])
		{
			$codigo=$_REQUEST['codigosolf'];
			$busqueda="AND D.codigo_producto LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descrisolf'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrisolf']);
			$busqueda2="AND UPPER(D.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($especialidad<>NULL)
		{
			$busqueda3="AND E.especialidad='".$especialidad."'";
		}
		else
		{
			$busqueda3='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT DISTINCT D.codigo_producto,
					D.descripcion,
					F.descripcion AS des1
					FROM inv_grupos_inventarios AS A,
					inv_clases_inventarios AS B,
					inv_subclases_inventarios AS C,
					inventarios_productos AS D,
					inv_solicitud_frecuencia E
					LEFT JOIN especialidades AS F ON
					(E.especialidad=F.especialidad)
					WHERE A.sw_medicamento='1'
					AND A.grupo_id=B.grupo_id
					AND B.grupo_id=C.grupo_id
					AND B.clase_id=C.clase_id
					AND C.grupo_id=D.grupo_id
					AND C.clase_id=D.clase_id
					AND C.subclase_id=D.subclase_id
					AND D.codigo_producto=E.codigo_producto
					$busqueda
					$busqueda2
					$busqueda3
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'];
			if($_REQUEST['Of'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of']='0';
				$_REQUEST['paso']='1';
			}
		}
		$query = "SELECT DISTINCT D.codigo_producto,
				D.descripcion,
				F.descripcion AS des1
				FROM inv_grupos_inventarios AS A,
				inv_clases_inventarios AS B,
				inv_subclases_inventarios AS C,
				inventarios_productos AS D,
				inv_solicitud_frecuencia E
				LEFT JOIN especialidades AS F ON
				(E.especialidad=F.especialidad)
				WHERE A.sw_medicamento='1'
				AND A.grupo_id=B.grupo_id
				AND B.grupo_id=C.grupo_id
				AND B.clase_id=C.clase_id
				AND C.grupo_id=D.grupo_id
				AND C.clase_id=D.clase_id
				AND C.subclase_id=D.subclase_id
				AND D.codigo_producto=E.codigo_producto
				$busqueda
				$busqueda2
				$busqueda3
				ORDER BY D.codigo_producto
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $var;
	}

}//fin clase user
?>
