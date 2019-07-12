
<?php

/**
* Modulo de Salud Ocupacional (PHP).
*
* Modulo para relacionar las enfermedades provocadas con profesiones laborales
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_SaludOcupacional_user.php
*
* Clase para establecer y mostrar las relaciones que existen entre algunas
* enfermedades provocadas por la profesión o por el alto riesgo de adquirirla
**/

class app_SaludOcupacional_user extends classModulo
{
	var $uno;//para los errores
	var $limit;
	var $conteo;

	function app_SaludOcupacional_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->PrincipalSalud2();
		return true;
	}

	function BorrarSalud()//Función que borra las variables de sesion del modulo
	{
		UNSET($_SESSION['salude']);
		UNSET($_SESSION['salud']);
		$this->ReturnMetodoExterno('app','ParametrosHC','user','PrincipalParaHC');//si cambia a permisos 2
		return true;
	}

	function UsuariosSalud()//Función de permisos
	{
		list($dbconn) = GetDBconn();
		$usuario=UserGetUID();
		$query = "SELECT A.empresa_id,
				B.razon_social AS descripcion1
				FROM userpermisos_salud_ocupacional AS A,
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
		$url[1]='SaludOcupacional';
		$url[2]='user';
		$url[3]='PrincipalSalud';
		$url[4]='permisosalud';
		$this->salida .=gui_theme_menu_acceso('SALUD OCUPACIONAL', $mtz, $var1, $url, ModuloGetURL('system','Menu'));
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

	function BuscarEnfermedadSalud1()//
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigosalu'])
		{
			$codigo=$_REQUEST['codigosalu'];
			$busqueda="WHERE diagnostico_id LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descrisalu'])
		{
			if($busqueda==NULL)
			{
				$codigo=STRTOUPPER($_REQUEST['descrisalu']);
				$busqueda2="WHERE UPPER(diagnostico_nombre) LIKE '%$codigo%'";
			}
			else
			{
				$codigo=STRTOUPPER($_REQUEST['descrisalu']);
				$busqueda2="AND UPPER(diagnostico_nombre) LIKE '%$codigo%'";
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
					SELECT diagnostico_id,
					diagnostico_nombre
					FROM diagnosticos
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
		$query = "
				(
				SELECT A.diagnostico_id,
				A.diagnostico_nombre,
					(
						SELECT count(*)
						FROM enfermedades_por_ocupaciones
						WHERE A.diagnostico_id=diagnostico_id
					) AS num
				FROM diagnosticos AS A
				$busqueda
				$busqueda2
				)
				ORDER BY A.diagnostico_id
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

	function BuscarMostrarEquiSalud1($diagnostico)//
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.ocupacion_id,
				A.ocupacion_descripcion
				FROM ocupaciones AS A,
				enfermedades_por_ocupaciones AS B
				WHERE B.diagnostico_id='".$diagnostico."'
				AND B.ocupacion_id=A.ocupacion_id
				ORDER BY A.ocupacion_id;";
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

	function BuscarOcupacionesSalud1($diagnostico)//
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocrea'])
		{
			$codigo=$_REQUEST['codigocrea'];
			$busqueda="WHERE ocupacion_id LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descricrea'])
		{
			if($busqueda==NULL)
			{
				$codigo=STRTOUPPER($_REQUEST['descricrea']);
				$busqueda2="WHERE UPPER(ocupacion_descripcion) LIKE '%$codigo%'";
			}
			else
			{
				$codigo=STRTOUPPER($_REQUEST['descricrea']);
				$busqueda2="AND UPPER(ocupacion_descripcion) LIKE '%$codigo%'";
			}
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo1']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT ocupacion_id,
					ocupacion_descripcion
					FROM ocupaciones
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
			$this->conteo=$_REQUEST['conteo1'];
		}
		if(!$_REQUEST['Of1'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of1'];
			if($_REQUEST['Of1'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of1']='0';
				$_REQUEST['paso1']='1';
			}
		}
		$query = "
				(
				SELECT A.ocupacion_id,
				A.ocupacion_descripcion,
					(
						SELECT count(*)
						FROM enfermedades_por_ocupaciones
						WHERE A.ocupacion_id=ocupacion_id
						AND diagnostico_id='".$diagnostico."'
					) AS num
				FROM ocupaciones AS A
				$busqueda
				$busqueda2
				)
				ORDER BY A.ocupacion_id
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

	function ValidarEquivalenciaSalud1()//
	{
		if(empty($_SESSION['salud']['profesion1']))
		{
			$this->CrearEquivalenciaSalud1();
			return true;
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$contador1=$contador2=0;
		for($i=0;$i<sizeof($_SESSION['salud']['profesion1']);$i++)
		{
			if($_POST['equivasalu'.$i]<>NULL AND $_SESSION['salud']['profesion1'][$i]['num']==0)
			{
				$contador1++;
				$query = "INSERT INTO enfermedades_por_ocupaciones
						(diagnostico_id,
						ocupacion_id)
						VALUES
						('".$_SESSION['salud']['diagnosti1'][$_SESSION['salud']['indicedieq']]['diagnostico_id']."',
						'".$_SESSION['salud']['profesion1'][$i]['ocupacion_id']."');";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
			}
			else if($_POST['equivasalu'.$i]==NULL AND $_SESSION['salud']['profesion1'][$i]['num']<>0)
			{
				$contador2++;
				$query = "DELETE FROM enfermedades_por_ocupaciones
						WHERE diagnostico_id='".$_SESSION['salud']['diagnosti1'][$_SESSION['salud']['indicedieq']]['diagnostico_id']."'
						AND ocupacion_id='".$_SESSION['salud']['profesion1'][$i]['ocupacion_id']."';";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
			}
			$_POST['equivasalu'.$i]='';
		}
		$dbconn->CommitTrans();
		$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
		<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
		$this->uno=1;
		$this->CrearEquivalenciaSalud1();
		return true;
	}

	function BuscarOcupacionesSalud2()//
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigosalu'])
		{
			$codigo=$_REQUEST['codigosalu'];
			$busqueda="WHERE ocupacion_id LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descrisalu'])
		{
			if($busqueda==NULL)
			{
				$codigo=STRTOUPPER($_REQUEST['descrisalu']);
				$busqueda2="WHERE UPPER(ocupacion_descripcion) LIKE '%$codigo%'";
			}
			else
			{
				$codigo=STRTOUPPER($_REQUEST['descrisalu']);
				$busqueda2="AND UPPER(ocupacion_descripcion) LIKE '%$codigo%'";
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
					SELECT ocupacion_id,
					ocupacion_descripcion
					FROM ocupaciones
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
		$query = "
				(
				SELECT A.ocupacion_id,
				A.ocupacion_descripcion,
					(
						SELECT count(*)
						FROM enfermedades_por_ocupaciones
						WHERE A.ocupacion_id=ocupacion_id
					) AS num
				FROM ocupaciones AS A
				$busqueda
				$busqueda2
				)
				ORDER BY A.ocupacion_id
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

	function BuscarMostrarEquiSalud2($profesion)//
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.diagnostico_id,
				A.diagnostico_nombre
				FROM diagnosticos AS A,
				enfermedades_por_ocupaciones AS B
				WHERE B.ocupacion_id='".$profesion."'
				AND B.diagnostico_id=A.diagnostico_id
				ORDER BY A.diagnostico_id;";
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

	function BuscarEnfermedadSalud2($profesion)//
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocrea'])
		{
			$codigo=$_REQUEST['codigocrea'];
			$busqueda="WHERE diagnostico_id LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descricrea'])
		{
			if($busqueda==NULL)
			{
				$codigo=STRTOUPPER($_REQUEST['descricrea']);
				$busqueda2="WHERE UPPER(diagnostico_nombre) LIKE '%$codigo%'";
			}
			else
			{
				$codigo=STRTOUPPER($_REQUEST['descricrea']);
				$busqueda2="AND UPPER(diagnostico_nombre) LIKE '%$codigo%'";
			}
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo1']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT diagnostico_id,
					diagnostico_nombre
					FROM diagnosticos
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
			$this->conteo=$_REQUEST['conteo1'];
		}
		if(!$_REQUEST['Of1'])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of1'];
			if($_REQUEST['Of1'] > $this->conteo)
			{
				$Of='0';
				$_REQUEST['Of1']='0';
				$_REQUEST['paso1']='1';
			}
		}
		$query = "
				(
				SELECT A.diagnostico_id,
				A.diagnostico_nombre,
					(
						SELECT count(*)
						FROM enfermedades_por_ocupaciones
						WHERE A.diagnostico_id=diagnostico_id
						AND ocupacion_id='".$profesion."'
					) AS num
				FROM diagnosticos AS A
				$busqueda
				$busqueda2
				)
				ORDER BY A.diagnostico_id
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

	function ValidarEquivalenciaSalud2()//
	{
		if(empty($_SESSION['salud']['diagnosti2']))
		{
			$this->CrearEquivalenciaSalud2();
			return true;
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$contador1=$contador2=0;
		for($i=0;$i<sizeof($_SESSION['salud']['diagnosti2']);$i++)
		{
			if($_POST['equivasalu'.$i]<>NULL AND $_SESSION['salud']['diagnosti2'][$i]['num']==0)
			{
				$contador1++;
				$query = "INSERT INTO enfermedades_por_ocupaciones
						(ocupacion_id,
						diagnostico_id)
						VALUES
						('".$_SESSION['salud']['profesion2'][$_SESSION['salud']['indiceoceq']]['ocupacion_id']."',
						'".$_SESSION['salud']['diagnosti2'][$i]['diagnostico_id']."');";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
			}
			else if($_POST['equivasalu'.$i]==NULL AND $_SESSION['salud']['diagnosti2'][$i]['num']<>0)
			{
				$contador2++;
				$query = "DELETE FROM enfermedades_por_ocupaciones
						WHERE ocupacion_id='".$_SESSION['salud']['profesion2'][$_SESSION['salud']['indiceoceq']]['ocupacion_id']."'
						AND diagnostico_id='".$_SESSION['salud']['diagnosti2'][$i]['diagnostico_id']."';";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
			}
			$_POST['equivasalu'.$i]='';
		}
		$dbconn->CommitTrans();
		$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
		<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
		$this->uno=1;
		$this->CrearEquivalenciaSalud2();
		return true;
	}

}//fin de la clase
?>
