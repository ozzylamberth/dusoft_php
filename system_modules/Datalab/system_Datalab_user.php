
<?php

/**
* Modulo de Datalab (PHP).
*
* Modulo para el mantenimiento de los cargos del tarifario cups y de
* los cargos de la interface con datalab, asi como sus equivalencias
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* system_Datalab_user.php
*
**/

class system_Datalab_user extends classModulo
{
	var $uno;//para los errores
	var $limit;
	var $conteo;

	function system_Datalab_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->PrincipalDatalab();
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

	function CalcularNumeroPasos($conteo)
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	function CalcularBarra($paso)
	{
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	function CalcularOffset($paso)
	{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	function BuscarRelacionarCargosDatala()//Busca los cargos CUPS y la relación con los de Datalab
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigodata'])
		{
			$codigo=$_REQUEST['codigodata'];
			$busqueda1="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descridata'])
		{
			$codigo=STRTOUPPER($_REQUEST['descridata']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['buscardata']<>NULL)
		{
			$busqueda3="AND (SELECT COUNT(C.codigo_cups)
					FROM interface_datalab_codigos AS C
					WHERE C.codigo_cups=A.cargo)>0";
		}
		else
		{
			$busqueda3='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.cargo,
					A.descripcion,
					(SELECT COUNT(C.codigo_cups)
					FROM interface_datalab_codigos AS C
					WHERE C.codigo_cups=A.cargo) AS contar
					FROM cups AS A,
					apoyod_tipos AS B,
					departamentos_cargos AS C,
					interface_datalab_departamentos AS D
					WHERE A.grupo_tipo_cargo=B.apoyod_tipo_id
					AND A.cargo=C.cargo
					AND C.departamento=D.departamento
					$busqueda1
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
		$query = "
				(
				SELECT A.cargo,
				A.descripcion,
				(SELECT COUNT(C.codigo_cups)
				FROM interface_datalab_codigos AS C
				WHERE C.codigo_cups=A.cargo) AS contar
				FROM cups AS A,
				apoyod_tipos AS B,
				departamentos_cargos AS C,
				interface_datalab_departamentos AS D
				WHERE A.grupo_tipo_cargo=B.apoyod_tipo_id
				AND A.cargo=C.cargo
				AND C.departamento=D.departamento
				$busqueda1
				$busqueda2
				$busqueda3
				ORDER BY A.cargo
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
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

	function BuscarRelacionarCargosDetalleDatala($cargo)//
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.codigo_datalab,
				A.sw_perfil
				FROM interface_datalab_codigos AS A
				WHERE A.codigo_cups='".$cargo."'
				ORDER BY A.codigo_datalab;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
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

	function EliminarCargosDatala()//
	{
		list($dbconn) = GetDBconn();
		$query = "DELETE FROM interface_datalab_codigos
				WHERE codigo_cups='".$_SESSION['datala']['cargosequi'][$_REQUEST['indcargocup']]['cargo']."'
				AND codigo_datalab=".$_REQUEST['indcargodat'].";";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->frmError["MensajeError"]="EL CARGO CUPS ".$_SESSION['datala']['cargosequi'][$_REQUEST['indcargocup']]['cargo']."
		Y EL CARGO DATALAB ".$_SESSION['datala']['cargosdata'][$_REQUEST['indcargodat']]['codigo_datalab']." FUERON BORRADOS";
		$this->uno=1;
		$this->RelacionarCargosDatala();
		return true;
	}

	function ValidarCargosDatala()//
	{
		if(is_numeric($_POST['cudacodigo'])==0)
		{
			$this->frmError["cudacodigo"]=1;
			$_POST['cudacodigo']='';
		}
		else
		{
			$valorcontr=intval($_POST['cudacodigo']);
		}
		if($_POST['cudacodigo']==NULL)
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
			$this->uno=1;
			$this->CrearCargosDatala();
			return true;
		}
		if($_POST['perfil']==2)
		{
			$_POST['perfil']=0;
		}
		if($_POST['guardar']=='GUARDAR')
		{
			list($dbconn) = GetDBconn();
			$query = "INSERT INTO interface_datalab_codigos
					(codigo_cups,
					codigo_datalab,
					sw_perfil)
					VALUES
					('".$_SESSION['datala']['cargosequi'][$_SESSION['datala']['indcargocup']]['cargo']."',
					".$valorcontr.",
					'".$_POST['perfil']."');";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL GUARDAR LOS DATOS.";
			}
			if($this->frmError["MensajeError"]==NULL)
			{
				$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE.";
			}
			$this->uno=1;
			$this->RelacionarCargosDatala();
			return true;
		}
		else if($_POST['guardar']=='GUARDAR Y REPETIR')
		{
			list($dbconn) = GetDBconn();
			$query = "INSERT INTO interface_datalab_codigos
					(codigo_cups,
					codigo_datalab,
					sw_perfil)
					VALUES
					('".$_SESSION['datala']['cargosequi'][$_SESSION['datala']['indcargocup']]['cargo']."',
					".$valorcontr.",
					'".$_POST['perfil']."');";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL GUARDAR LOS DATOS.";
			}
			$_POST['cudacodigo']='';
			if($this->frmError["MensajeError"]==NULL)
			{
				$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE.";
			}
			$this->uno=1;
			$this->CrearCargosDatala();
			return true;
		}
	}

	function BuscarConsultarCargosDatala()//
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigodata'])
		{
			$codigo=$_REQUEST['codigodata'];
			$busqueda1="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descridata'])
		{
			$codigo=STRTOUPPER($_REQUEST['descridata']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.cargo,
					A.descripcion,
					(SELECT COUNT(C.codigo_cups)
					FROM interface_datalab_codigos AS C
					WHERE C.codigo_cups=A.cargo) AS contar
					FROM cups AS A,
					apoyod_tipos AS B,
					departamentos_cargos AS C,
					interface_datalab_departamentos AS D
					WHERE A.grupo_tipo_cargo=B.apoyod_tipo_id
					AND A.cargo=C.cargo
					AND C.departamento=D.departamento
					AND (SELECT COUNT(C.codigo_cups)
					FROM interface_datalab_codigos AS C
					WHERE C.codigo_cups=A.cargo)>0
					$busqueda1
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
				SELECT A.cargo,
				A.descripcion,
				(SELECT COUNT(C.codigo_cups)
				FROM interface_datalab_codigos AS C
				WHERE C.codigo_cups=A.cargo) AS contar
				FROM cups AS A,
				apoyod_tipos AS B,
				departamentos_cargos AS C,
				interface_datalab_departamentos AS D
				WHERE A.grupo_tipo_cargo=B.apoyod_tipo_id
				AND A.cargo=C.cargo
				AND C.departamento=D.departamento
				AND (SELECT COUNT(C.codigo_cups)
				FROM interface_datalab_codigos AS C
				WHERE C.codigo_cups=A.cargo)>0
				$busqueda1
				$busqueda2
				ORDER BY A.cargo
				)
				LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
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

}//fin de la clase
?>
