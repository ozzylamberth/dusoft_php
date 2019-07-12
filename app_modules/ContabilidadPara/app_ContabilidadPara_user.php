
<?php

/**
* Modulo de Parametros de Contabilidad (PHP).
*
* Modulo que permite parametrizar las características de la contabilidad
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_ContabilidadPara_user.php
*
**/

class app_ContabilidadPara_user extends classModulo
{
	var $uno;//para los errores
	var $limit;
	var $conteo;

	function app_ContabilidadPara_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->PrincipalConpar2();
		return true;
	}

	function UsuariosConpar()//Función de permisos
	{
		list($dbconn) = GetDBconn();
		$usuario=UserGetUID();
		$query ="SELECT A.empresa_id,
				B.razon_social AS descripcion1
				FROM userpermisos_contabilidadpara AS A,
				empresas AS B
				WHERE A.usuario_id=".$usuario."
				AND A.empresa_id=B.empresa_id
				ORDER BY descripcion1;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
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
		$url[1]='ContabilidadPara';
		$url[2]='user';
		$url[3]='PrincipalConpar';
		$url[4]='permisoscontpa';
		$this->salida .=gui_theme_menu_acceso('PARÁMETROS DE CONTABILIDAD', $mtz, $var1, $url, ModuloGetURL('system','Menu'));
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

	function LlamaServiciosConpar()//Función que llama al HTML
	{
		$this->ServiciosConpar();
		return true;
	}

	function LlamaParametrosCuentasConpar()//Función que llama al plan de cuentas
	{
		if($_POST['servicio']==NULL OR $_POST['departam']==NULL)
		{
			$this->frmError["MensajeError"]="POR FAVOR, SELECCIONE UN SERVICIO Y/O UN DEPARTAMENTO";
			$this->uno=1;
			$this->LlamaServiciosConpar();
			return true;
		}
		$this->ParametrosCuentasConpar();
		return true;
	}

	function BuscarParametrosCuentasConpar($empresa,$departamento)//='2'='010501'
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.grupo_tipo_cargo,
				A.descripcion AS des1,
				B.tipo_cargo,
				B.descripcion AS des2,
				C.cuenta,
				D.descripcion
				FROM grupos_tipos_cargo AS A,
				tipos_cargos AS B
				LEFT JOIN cg_parametros_cuentas AS C ON
					(C.empresa_id='".$empresa."'
					AND C.departamento='".$departamento."'
					AND C.grupo_tipo_cargo=B.grupo_tipo_cargo
					AND C.tipo_cargo=B.tipo_cargo)
				LEFT JOIN cg_plan_de_cuentas AS D ON
					(D.empresa_id='".$empresa."'
					AND D.cuenta=C.cuenta)
				WHERE A.grupo_tipo_cargo=B.grupo_tipo_cargo
				ORDER BY A.grupo_tipo_cargo,
				B.tipo_cargo;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarPlanCuentasConpar($empresa)
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT cuenta,
				descripcion
				FROM cg_plan_de_cuentas
				WHERE empresa_id='".$empresa."'
				AND sw_cuenta_movimiento='1'
				ORDER BY cuenta;";//AND sw_estado='1'
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarParametrosCuentasConpar()//
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$this->frmError["MensajeError"]='';
		$ciclo=sizeof($_SESSION['conpa1']['grutaconpa']);
		for($i=0;$i<$ciclo;$i++)
		{
			$g1=0;
			if($_POST['cuentaconp'.$i]<>NULL)
			{
				$g1=1;
			}
			if($_SESSION['conpa1']['grutaconpa'][$i]['cuenta']==NULL AND $g1==1)
			{
				$query ="INSERT INTO cg_parametros_cuentas
						(empresa_id,
						departamento,
						grupo_tipo_cargo,
						tipo_cargo,
						cuenta)
						VALUES
						('".$_SESSION['conpar']['empresa']."',
						'".$_SESSION['conpa1']['departamcp']."',
						'".$_SESSION['conpa1']['grutaconpa'][$i]['grupo_tipo_cargo']."',
						'".$_SESSION['conpa1']['grutaconpa'][$i]['tipo_cargo']."',
						'".$_POST['cuentaconp'.$i]."');";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollBackTrans();
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$i=$ciclo;
				}
			}
			else if($_SESSION['conpa1']['grutaconpa'][$i]['cuenta']<>NULL AND $g1==1
			AND $_SESSION['conpa1']['grutaconpa'][$i]['cuenta']<>$_POST['cuentaconp'.$i])
			{
				$query ="UPDATE cg_parametros_cuentas SET
						cuenta='".$_POST['cuentaconp'.$i]."'
						WHERE empresa_id='".$_SESSION['conpar']['empresa']."'
						AND departamento='".$_SESSION['conpa1']['departamcp']."'
						AND grupo_tipo_cargo='".$_SESSION['conpa1']['grutaconpa'][$i]['grupo_tipo_cargo']."'
						AND tipo_cargo='".$_SESSION['conpa1']['grutaconpa'][$i]['tipo_cargo']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollBackTrans();
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR MODIFICAR LOS DATOS";
					$i=$ciclo;
				}
			}
			else if($_SESSION['conpa1']['grutaconpa'][$i]['cuenta']<>NULL AND $_POST['cuentaconp'.$i]==NULL)
			{
				$query ="DELETE FROM cg_parametros_cuentas
						WHERE empresa_id='".$_SESSION['conpar']['empresa']."'
						AND departamento='".$_SESSION['conpa1']['departamcp']."'
						AND grupo_tipo_cargo='".$_SESSION['conpa1']['grutaconpa'][$i]['grupo_tipo_cargo']."'
						AND tipo_cargo='".$_SESSION['conpa1']['grutaconpa'][$i]['tipo_cargo']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollBackTrans();
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR ELIMINAR LOS DATOS";
					$i=$ciclo;
				}
			}
			$_POST['cuentaconp'.$i]='';
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
		}
		$this->uno=1;
		$this->ParametrosCuentasConpar();
		return true;
	}

	function BuscarServiciosConpar()//Busca los servicios
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT servicio,
				descripcion
				FROM servicios
				WHERE servicio<>'0'
				ORDER BY servicio;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function LlamaParaExceCuentasConpar()
	{
		$this->ParaExceCuentasConpar();
		return true;
	}

	function BuscarParaExceCuentasConpar($empresa,$departamen,$grd,$sud)//
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigoconp'])
		{
			$codigo=$_REQUEST['codigoconp'];
			$busqueda="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descriconp'])
		{
			$codigo=STRTOUPPER($_REQUEST['descriconp']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query ="SELECT count(*) FROM (
					(
						SELECT A.cargo,
						A.descripcion,
						B.cuenta
						FROM cups AS A
						LEFT JOIN cg_excepciones_parametros_cuentas AS B ON
						(B.empresa_id='".$empresa."'
						AND B.departamento='".$departamen."'
						AND A.cargo=B.cargo)
						WHERE A.grupo_tipo_cargo='".$grd."'
						AND A.tipo_cargo='".$sud."'
						$busqueda
						$busqueda2
						$busqueda3
					)
					) AS r;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
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
		$query ="
				(
				SELECT A.cargo,
				A.descripcion,
				B.cuenta
				FROM cups AS A
				LEFT JOIN cg_excepciones_parametros_cuentas AS B ON
				(B.empresa_id='".$empresa."'
				AND B.departamento='".$departamen."'
				AND A.cargo=B.cargo)
				WHERE A.grupo_tipo_cargo='".$grd."'
				AND A.tipo_cargo='".$sud."'
				$busqueda
				$busqueda2
				$busqueda3
				ORDER BY A.cargo
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarParaExceCuentasConpar()//
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$this->frmError["MensajeError"]='';
		$contador1=$contador2=$contador3=0;
		$ciclo=sizeof($_SESSION['conpa1']['cargocuecp']);
		for($i=0;($i<$ciclo);$i++)
		{
			if($_POST['cuentaexpc'.$i]<>NULL AND $_SESSION['conpa1']['cargocuecp'][$i]['cuenta']==NULL
			AND $_POST['cuentaexpc'.$i]<>$_SESSION['conpa1']['datgruconp']['cuenta'])
			{
				$contador1++;
				$query ="INSERT INTO cg_excepciones_parametros_cuentas
						(empresa_id,
						departamento,
						cargo,
						cuenta)
						VALUES
						('".$_SESSION['conpar']['empresa']."',
						'".$_SESSION['conpa1']['departamcp']."',
						'".$_SESSION['conpa1']['cargocuecp'][$i]['cargo']."',
						'".$_POST['cuentaexpc'.$i]."');";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollBackTrans();
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR GUARDAR LOS DATOS";
					$i=$ciclo;
				}
			}
			else if($_POST['cuentaexpc'.$i]<>NULL AND $_SESSION['conpa1']['cargocuecp'][$i]['cuenta']<>NULL
			AND $_POST['cuentaexpc'.$i]<>$_SESSION['conpa1']['datgruconp']['cuenta'])
			{
				$contador2++;
				$query ="UPDATE cg_excepciones_parametros_cuentas SET
						cuenta='".$_POST['cuentaexpc'.$i]."'
						WHERE empresa_id='".$_SESSION['conpar']['empresa']."'
						AND departamento='".$_SESSION['conpa1']['departamcp']."'
						AND cargo='".$_SESSION['conpa1']['cargocuecp'][$i]['cargo']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollBackTrans();
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR MODIFICAR LOS DATOS";
					$i=$ciclo;
				}
			}
			else if(($_POST['cuentaexpc'.$i]==NULL AND $_SESSION['conpa1']['cargocuecp'][$i]['cuenta']<>NULL)
			OR $_POST['cuentaexpc'.$i]==$_SESSION['conpa1']['datgruconp']['cuenta'])
			{
				$contador3++;
				$query ="DELETE FROM cg_excepciones_parametros_cuentas
						WHERE empresa_id='".$_SESSION['conpar']['empresa']."'
						AND departamento='".$_SESSION['conpa1']['departamcp']."'
						AND cargo='".$_SESSION['conpa1']['cargocuecp'][$i]['cargo']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollBackTrans();
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INTENTAR ELIMINAR LOS DATOS";
					$i=$ciclo;
				}
			}
			$_POST['cuentaexpc'.$i]='';
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		}
		$this->uno=1;
		$this->ParaExceCuentasConpar();
		return true;
	}

}//fin de la clase
?>
