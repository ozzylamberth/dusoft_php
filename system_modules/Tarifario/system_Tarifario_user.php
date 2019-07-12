
<?php

/**
* Modulo de Tarifarios (PHP).
*
* Modulo para el mantenimiento de los cargos del tarifario cups y de
* los demás tarifarios, así como las equivalencias entre los mismos
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* system_Tarifario_user.php
*
* Modulo que permite realizar un mantenimineto a todos los cargos del cups
* en cuanto al código, la descripción, la clasificación y demás datos,
* igualmente me permite realizar el mantenimiento a los cargos de los tarifarios
* que están en la aplicación, y establecer la equivalencia entre los cargos
**/

class system_Tarifario_user extends classModulo
{
	var $uno;//para los errores
	var $limit;
	var $conteo;

	function system_Tarifario_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->PrincipalTarifa();
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

	//BuscarTiposUnidades()
	function BuscarTiposUnidades()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_unidad_id,
								descripcion_corta ,descripcion
							FROM tipos_unidades_cargos;";
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

	function BuscarDesUnidad($tipounidad)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT
								descripcion_corta ,descripcion
							FROM tipos_unidades_cargos
							WHERE tipo_unidad_id='".$tipounidad."';";
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

	function BuscarTarifariosTari()//Busca los tarifarios
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT tarifario_id,
				descripcion
				FROM tarifarios
				WHERE tarifario_id<>'SYS'
				AND tarifario_id<>'CUPS'
				ORDER BY descripcion;";
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

	function BuscarAyudaTarifariosTari($tarifario)//Busca los tarifarios
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT tarifario_id,
				descripcion
				FROM tarifarios
				WHERE tarifario_id<>'SYS'
				AND tarifario_id<>'".$tarifario."'
				ORDER BY descripcion;";
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

	function BuscarCargosElegTarifa1($tarifario)//Busca los detalles del tarifario y las excepciones
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigotari'])
		{
			$codigo=$_REQUEST['codigotari'];
			$busqueda="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descritari'])
		{
			$codigo=STRTOUPPER($_REQUEST['descritari']);
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
					C.cargo AS algo,
					C.descripcion AS algoo
					FROM tarifarios_detalle AS A
					JOIN tarifarios_equivalencias AS B ON
					(A.tarifario_id=B.tarifario_id
					AND A.cargo=B.cargo
					AND A.tarifario_id='".$tarifario."')
					JOIN cups AS C ON
					(B.cargo_base=C.cargo)
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
				SELECT A.cargo,
				A.descripcion,
				C.cargo AS algo,
				C.descripcion AS algoo
				FROM tarifarios_detalle AS A
				JOIN tarifarios_equivalencias AS B ON
				(A.tarifario_id=B.tarifario_id
				AND A.cargo=B.cargo
				AND A.tarifario_id='".$tarifario."')
				JOIN cups AS C ON
				(B.cargo_base=C.cargo)
				$busqueda
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
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $var;
	}

	function BuscarCargosElegTarifa2($tarifario)//Busca los detalles del tarifario y las excepciones
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigotari'])
		{
			$codigo=$_REQUEST['codigotari'];
			$busqueda="AND C.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descritari'])
		{
			$codigo=STRTOUPPER($_REQUEST['descritari']);
			$busqueda2="AND UPPER(C.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT C.cargo,
					C.descripcion,
					A.cargo AS algo,
					A.descripcion AS algoo
					FROM tarifarios_detalle AS A
					JOIN tarifarios_equivalencias AS B ON
					(A.tarifario_id=B.tarifario_id
					AND A.cargo=B.cargo
					AND A.tarifario_id='".$tarifario."')
					JOIN cups AS C ON
					(B.cargo_base=C.cargo)
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
				SELECT C.cargo,
				C.descripcion,
				A.cargo AS algo,
				A.descripcion AS algoo
				FROM tarifarios_detalle AS A
				JOIN tarifarios_equivalencias AS B ON
				(A.tarifario_id=B.tarifario_id
				AND A.cargo=B.cargo
				AND A.tarifario_id='".$tarifario."')
				JOIN cups AS C ON
				(B.cargo_base=C.cargo)
				$busqueda
				$busqueda2
				ORDER BY C.cargo
				)
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

	function BuscarCargosEquiTarifa($tarifario)//Busca los detalles del tarifario y las excepciones
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigotari'])
		{
			$codigo=$_REQUEST['codigotari'];
			$busqueda="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descritari'])
		{
			$codigo=STRTOUPPER($_REQUEST['descritari']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['buscartari']<>NULL)
		{
			$busqueda3="AND contar_equivalencias(A.tarifario_id,A.cargo)=0";
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
					A.tarifario_id,
					contar_equivalencias(A.tarifario_id,A.cargo) AS num
					FROM tarifarios_detalle AS A
					WHERE A.tarifario_id='".$tarifario."'
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
		$query = "
				(
				SELECT A.cargo,
				A.descripcion,
				A.tarifario_id,
				contar_equivalencias(A.tarifario_id,A.cargo) AS num
				FROM tarifarios_detalle AS A
				WHERE A.tarifario_id='".$tarifario."'
				$busqueda
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
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $var;
	}

	function BuscarMostrarEquiTarifa($tarifario,$cargo)//
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT B.cargo_base,
				A.descripcion
				FROM tarifarios_equivalencias AS B,
				cups AS A
				WHERE B.tarifario_id='".$tarifario."'
				AND B.cargo='".$cargo."'
				AND B.cargo_base=A.cargo
				ORDER BY B.cargo_base;";
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

	function BuscarCargosBaseTarifa()//Busca los detalles del tarifario y las excepciones
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocrea'])
		{
			$codigo=$_REQUEST['codigocrea'];
			$busqueda="WHERE A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descricrea'])
		{
			if($busqueda)
			{
				$codigo=STRTOUPPER($_REQUEST['descricrea']);
				$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
			}
			else
			{
				$codigo=STRTOUPPER($_REQUEST['descricrea']);
				$busqueda2="WHERE UPPER(A.descripcion) LIKE '%$codigo%'";
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
					SELECT A.cargo,
					A.descripcion
					FROM cups AS A
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
				SELECT A.cargo,
				A.descripcion
				FROM cups AS A
				$busqueda
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
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $var;
	}

	function BuscarEquivalenciaTarifa($tarifario,$cargo)//
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT cargo_base
				FROM tarifarios_equivalencias
				WHERE tarifario_id='".$tarifario."'
				AND cargo='".$cargo."'
				ORDER BY cargo;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[$resulta->fields[0]]=1;
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarEquivalenciaTarifa()
	{
		if(empty($_SESSION['tarifa']['cargosbase']))
		{
			$this->CrearEquivalenciaTarifa();
			return true;
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$contador1=$contador2=0;
		$ciclo=sizeof($_SESSION['tarifa']['cargosbase']);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_POST['equivatari'.$i]<>NULL AND !($_SESSION['tarifa']['cargosbaeq'][$_SESSION['tarifa']['cargosbase'][$i]['cargo']]==1))
			{
				$contador1++;
				$porc=100;
				similar_text($_SESSION['tarifa']['cargosbase'][$i]['descripcion'],
				$_SESSION['tarifa']['cargosequi'][$_SESSION['tarifa']['indicarequ']]['descripcion'],$porc);
				$query = "INSERT INTO tarifarios_equivalencias
						(tarifario_id,
						cargo,
						cargo_base,
						porc_similitud)
						VALUES
						('".$_SESSION['tarifa']['tarifaeleg']."',
						'".$_SESSION['tarifa']['cargosequi'][$_SESSION['tarifa']['indicarequ']]['cargo']."',
						'".$_SESSION['tarifa']['cargosbase'][$i]['cargo']."',
						".$porc.");";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
			}
			else if($_POST['equivatari'.$i]==NULL AND $_SESSION['tarifa']['cargosbaeq'][$_SESSION['tarifa']['cargosbase'][$i]['cargo']]==1)
			{
				$contador2++;
				$query = "DELETE FROM tarifarios_equivalencias
						WHERE tarifario_id='".$_SESSION['tarifa']['tarifaeleg']."'
						AND cargo='".$_SESSION['tarifa']['cargosequi'][$_SESSION['tarifa']['indicarequ']]['cargo']."'
						AND cargo_base='".$_SESSION['tarifa']['cargosbase'][$i]['cargo']."';";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
			}
			$_POST['equivatari'.$i]='';
		}
		$dbconn->CommitTrans();
		$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
		<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
		$this->uno=1;
		$this->CrearEquivalenciaTarifa();
		return true;
	}

	function PermitirAyudaTarifa($tarifario)//
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT count(cargo)
				FROM tarifarios_equivalencias
				WHERE tarifario_id='".$tarifario."';";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $resulta->fields[0];
	}

	/*CAMBIAR*/

	function AyudaCopiar1Tarifa()//
	{
		if($_POST['tarifacopi']==-1)
		{
			$this->frmError["MensajeError"]="NO SELECCIONÓ NINGÚN TARIFARIO PARA COPIAR
			<br>O EL TARIFARIO ORIGEN ES EL MISMO DE DESTINO<br>NO SE GUARDARÓN COPIAS DE LOS CARGOS";
			$this->uno=1;
			$this->EquivalenciaTarifa();
			return true;
		}
		list($dbconn) = GetDBconn();
		if($_POST['tarifacopi'])
		{
			$query = "INSERT INTO tarifarios_equivalencias
					(
						cargo,
						tarifario_id,
						cargo_base
					)
					SELECT
					A.cargo,
					'".$_SESSION['tarifa']['tarifaeleg']."',
					B.cargo_base
					FROM
					(
						SELECT cargo
						FROM tarifarios_detalle
						WHERE tarifario_id='".$_POST['tarifacopi']."'
					) AS A,
					(
						SELECT cargo, cargo_base
						FROM tarifarios_equivalencias
					) AS B
					WHERE A.cargo=B.cargo;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		else
		{
			$query = "INSERT INTO tarifarios_equivalencias
					(
						cargo,
						tarifario_id,
						cargo_base
					)
					SELECT
					A.cargo,
					'".$_SESSION['tarifa']['tarifaeleg']."',
					B.cargo
					FROM
					(
						SELECT cargo
						FROM tarifarios_detalle
						WHERE tarifario_id='".$_POST['tarifacopi']."'
					) AS A,
					(
						SELECT cargo
						FROM cups
						WHERE tarifario_id='".GetVarConfigAplication('TarifarioBase')."'
					) AS B
					WHERE A.cargo=B.cargo;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		$this->frmError["MensajeError"]="NÚMERO DE INSERCIONES REALIZADAS: ".$dbconn->Affected_Rows()."";
		$this->uno=1;
		$this->EquivalenciaTarifa();
		return true;
	}

	function BuscarGruposCargosTarifa()//Trae los grupos y subgrupos de los cargos, así como el total y los ya definidos con periodos
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT B.grupo_tipo_cargo,
				B.descripcion AS des1,
				A.tipo_cargo,
				A.descripcion AS des2,
				contar_cargos_cups(A.grupo_tipo_cargo, A.tipo_cargo) AS cantidad
				FROM grupos_tipos_cargo AS B,
				tipos_cargos AS A
				WHERE B.grupo_tipo_cargo<>'SYS'
				AND B.grupo_tipo_cargo=A.grupo_tipo_cargo
				ORDER BY des1, des2;";
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

	function BuscarCargosTarifa($grupo,$tipos)//Función que busca los cargos del cups contra los que están en periodos tramites
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigotari'])
		{
			$codigo=$_REQUEST['codigotari'];
			$busqueda1="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descritari'])
		{
			$codigo=STRTOUPPER($_REQUEST['descritari']);
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
					SELECT A.cargo AS cargocups,
					A.descripcion,
					B.cargo AS cargotramite,
					B.dias_vigencia,
					B.dias_refrendar,
					B.dias_tramite_os
					FROM cups AS A
					LEFT JOIN os_tipos_periodos_tramites AS B ON
					(A.cargo=B.cargo)
					WHERE A.grupo_tipo_cargo='".$grupo."'
					AND A.tipo_cargo='".$tipos."'
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
				SELECT A.cargo AS cargocups,
				A.descripcion,
				B.cargo AS cargotramite,
				B.dias_vigencia,
				B.dias_refrendar,
				B.dias_tramite_os
				FROM cups AS A
				LEFT JOIN os_tipos_periodos_tramites AS B ON
				(A.cargo=B.cargo)
				WHERE A.grupo_tipo_cargo='".$grupo."'
				AND A.tipo_cargo='".$tipos."'
				$busqueda1
				$busqueda2
				ORDER BY cargocups
				)
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

	function ValidarCargosTarifa()//Guarda los parámetros de los tramites de cada cargo
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$contador1=$contador2=0;
		$ciclo=sizeof($_SESSION['tarifa']['cargcupspr']);
		for($i=0;$i<$ciclo;$i++)
		{
			if(($_POST['vigencia'.$i]<>NULL OR $_POST['refrenda'.$i]<>NULL OR
			$_POST['ordenser'.$i]<>NULL) AND $_POST['eliminar'.$i]==NULL)
			{
				if(is_numeric($_POST['vigencia'.$i])==1)
				{
					$vig=intval($_POST['vigencia'.$i]);
					if($vig>32000)
					{
						$vig=3;
					}
				}
				else
				{
					$vig=3;
				}
				if(is_numeric($_POST['refrenda'.$i])==1)
				{
					$ref=intval($_POST['refrenda'.$i]);
					if($ref>32000)
					{
						$ref=3;
					}
				}
				else
				{
					$ref=3;
				}
				if(is_numeric($_POST['ordenser'.$i])==1)
				{
					$ord=intval($_POST['ordenser'.$i]);
					if($ord>32000)
					{
						$ord=0;
					}
				}
				else
				{
					$ord=0;
				}
				if($_SESSION['tarifa']['cargcupspr'][$i]['cargotramite']==NULL)
				{
					$contador1++;
					$query = "INSERT INTO os_tipos_periodos_tramites
							(cargo,
							dias_vigencia,
							dias_refrendar,
							dias_tramite_os)
							VALUES
							('".$_SESSION['tarifa']['cargcupspr'][$i]['cargocups']."',
							'".$vig."',
							'".$ref."',
							'".$ord."');";
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
					$contador1++;
					$query = "UPDATE os_tipos_periodos_tramites SET
							dias_vigencia='".$vig."',
							dias_refrendar='".$ref."',
							dias_tramite_os='".$ord."'
							WHERE cargo='".$_SESSION['tarifa']['cargcupspr'][$i]['cargocups']."';";
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
			else if($_POST['eliminar'.$i]<>NULL AND $_SESSION['tarifa']['cargcupspr'][$i]['cargotramite']<>NULL)
			{
				$contador2++;
				$query = "DELETE FROM os_tipos_periodos_tramites
						WHERE cargo='".$_SESSION['tarifa']['cargcupspr'][$i]['cargocups']."';";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
			}
			$_POST['vigencia'.$i]='';
			$_POST['refrenda'.$i]='';
			$_POST['ordenser'.$i]='';
		}
		$dbconn->CommitTrans();
		$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
		<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
		$this->uno=1;
		$this->CargosTarifa();
		return true;
	}

	function BuscarTariModificarCargoTarifa($tarifario)//
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigotari'])
		{
			$codigo=$_REQUEST['codigotari'];
			$busqueda1="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descritari'])
		{
			$codigo=STRTOUPPER($_REQUEST['descritari']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['grupo'])
		{
			$codigo=STRTOUPPER($_REQUEST['grupo']);
			$busqueda3="AND A.grupo_tipo_cargo='".$codigo."'";
		}
		else
		{
			$busqueda3='';
		}
		if($_REQUEST['clasePr'])
		{
			$codigo=STRTOUPPER($_REQUEST['clasePr']);
			$busqueda4="AND A.tipo_cargo='".$codigo."'";
		}
		else
		{
			$busqueda4='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.cargo,
					A.descripcion,
					A.grupo_tipo_cargo,
					A.tipo_cargo,
					A.nivel,
					A.precio,
					A.tipo_unidad_id,
					B.descripcion AS des1,
					C.descripcion AS des2
					FROM tarifarios_detalle AS A,
					grupos_tipos_cargo AS B,
					tipos_cargos AS C
					WHERE A.tarifario_id='".$tarifario."'
					AND A.grupo_tipo_cargo<>'SYS'
					AND A.grupo_tipo_cargo=B.grupo_tipo_cargo
					AND B.grupo_tipo_cargo=C.grupo_tipo_cargo
					AND A.tipo_cargo=C.tipo_cargo
					$busqueda1
					$busqueda2
					$busqueda3
					$busqueda4
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
				A.grupo_tipo_cargo,
				A.tipo_cargo,
				A.nivel,
				A.precio,
				A.tipo_unidad_id,
				B.descripcion AS des1,
				C.descripcion AS des2
				FROM tarifarios_detalle AS A,
				grupos_tipos_cargo AS B,
				tipos_cargos AS C
				WHERE A.tarifario_id='".$tarifario."'
				AND A.grupo_tipo_cargo<>'SYS'
				AND A.grupo_tipo_cargo=B.grupo_tipo_cargo
				AND B.grupo_tipo_cargo=C.grupo_tipo_cargo
				AND A.tipo_cargo=C.tipo_cargo
				$busqueda1
				$busqueda2
				$busqueda3
				$busqueda4
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
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $var;
	}

	function BuscarTariModificar1CargosTarifa($tarifario,$cargo)//
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.cargo,
				A.descripcion,
				A.grupo_tarifario_id,
				A.subgrupo_tarifario_id,
				A.grupo_tipo_cargo,
				A.tipo_cargo,
				A.nivel,
				A.concepto_rips,
				A.precio,
				A.gravamen,
				A.sw_honorarios,
				A.sw_uvrs,
				A.sw_cantidad,
				A.grupos_mapipos,
				A.tipo_unidad_id,
				B.descripcion AS des1,
				C.descripcion AS des2,
				D.grupo_tarifario_descripcion AS des3,
				E.subgrupo_tarifario_descripcion AS des4
				FROM tarifarios_detalle AS A,
				grupos_tipos_cargo AS B,
				tipos_cargos AS C,
				grupos_tarifarios AS D,
				subgrupos_tarifarios AS E
				WHERE A.cargo='".$cargo."'
				AND A.tarifario_id='".$tarifario."'
				AND A.grupo_tipo_cargo=B.grupo_tipo_cargo
				AND A.grupo_tipo_cargo=C.grupo_tipo_cargo
				AND A.tipo_cargo=C.tipo_cargo
				AND A.grupo_tarifario_id=D.grupo_tarifario_id
				AND A.grupo_tarifario_id=E.grupo_tarifario_id
				AND A.subgrupo_tarifario_id=E.subgrupo_tarifario_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarTariModificar1CargosTarifa()//
	{
		if($_POST['descripcit']==NULL)
		{
			$this->frmError["descripcit"]=1;
		}
		if($_POST['nivelatent']==NULL)
		{
			$this->frmError["nivelatent"]=1;
		}
		if($_POST['conceptort']==NULL)
		{
			$this->frmError["conceptort"]=1;
		}
		if($_POST['tipounidad']==NULL)
		{
			$this->frmError["tipounidad"]=1;
		}
/*		else
		{
			if(!((is_numeric($_POST['preciocart'])==1) AND ($_POST['preciocart']<10000000000.00)))
			{
				$_POST['preciocart']='';
				$this->frmError["preciocart"]=1;
			}
		}*/
		if($_POST['unidad']==NULL)
		{
			$this->frmError["tipounidad"]=1;
		}
		if($_POST['gravamenct']==NULL)
		{
			$this->frmError["gravamenct"]=1;
		}
		else
		{
			if(!((is_numeric($_POST['gravamenct'])==1) AND ($_POST['gravamenct']<=100)))
			{
				$_POST['gravamenct']='';
				$this->frmError["gravamenct"]=1;
			}
		}
		if($_POST['honorariot']==NULL)
		{
			$this->frmError["honorariot"]=1;
		}
		if($_POST['swcantidat']==NULL)
		{
			$this->frmError["swcantidat"]=1;
		}
		if($_POST['grupo']==NULL)
		{
			$this->frmError["grupo"]=1;
		}
		if($_POST['clasePr']==NULL)
		{
			$this->frmError["clasePr"]=1;
		}
		if($_POST['grupotarif']==NULL)
		{
			$this->frmError["grupotarif"]=1;
		}
		if($_POST['subgrtarif']==NULL)
		{
			$this->frmError["subgrtarif"]=1;
		}
		if($_POST['gruposmapt']==NULL)
		{
			$this->frmError["gruposmapt"]=1;
		}
/*		if($_POST['descripcit']==NULL||$_POST['nivelatent']==NULL||
		$_POST['conceptort']==NULL||$_POST['swcantidat']==NULL||
		$_POST['tipounidad']==NULL||$_POST['swuvrspret']==NULL||
		$_POST['gravamenct']==NULL||$_POST['honorariot']==NULL||
		$_POST['grupo']==NULL||$_POST['clasePr']==NULL||
		$_POST['grupotarif']==NULL||$_POST['subgrtarif']==NULL||
		$_POST['gruposmapt']==NULL)*/
		if($_POST['descripcit']==NULL||$_POST['nivelatent']==NULL||
		$_POST['conceptort']==NULL||$_POST['swcantidat']==NULL||
		$_POST['tipounidad']==NULL||$_POST['unidad']==NULL||
		$_POST['gravamenct']==NULL||$_POST['honorariot']==NULL||
		$_POST['grupo']==NULL||$_POST['clasePr']==NULL||
		$_POST['grupotarif']==NULL||$_POST['subgrtarif']==NULL||
		$_POST['gruposmapt']==NULL)

		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS Y/O HAY DATOS INCORRECTOS";
			$this->uno=1;
			$this->TariModificar1CargosTarifa();
		}
		else
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
//					precio=".$_POST['preciocart'].",
//					sw_uvrs='".$_POST['swuvrspret']."',

			$_POST['gravamenct']=intval($_POST['gravamenct']);
			$query = "UPDATE tarifarios_detalle SET
					descripcion='".$_POST['descripcit']."',
					grupo_tipo_cargo='".$_POST['grupo']."',
					tipo_cargo='".$_POST['clasePr']."',
					grupo_tarifario_id='".$_POST['grupotarif']."',
					subgrupo_tarifario_id='".$_POST['subgrtarif']."',
					nivel='".$_POST['nivelatent']."',
					concepto_rips='".$_POST['conceptort']."',
					gravamen=".$_POST['gravamenct'].",
					sw_cantidad='".$_POST['swcantidat']."',
					sw_honorarios='".$_POST['honorariot']."',
					precio=".$_POST['unidad'].",
					grupos_mapipos='".$_POST['gruposmapt']."',
					tipo_unidad_id='".$_POST['tipounidad']."'
					WHERE cargo='".$_SESSION['tarifa']['datocargma']['cargo']."'
					AND tarifario_id='".$_SESSION['tarifa']['tarifaeleg']."';";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			$dbconn->CommitTrans();
			$_REQUEST['grupo']='';
			$_REQUEST['NomGrupo']='';
			$_REQUEST['clasePr']='';
			$_REQUEST['NomClase']='';
			$_POST['grupo']='';
			$_POST['NomGrupo']='';
			$_POST['clasePr']='';
			$_POST['NomClase']='';
			$this->frmError["MensajeError"]="EL CARGO No. ".$_SESSION['tarifa']['datocargma']['cargo']." FUE MODIFICADO CORRECTAMENTE";
			$this->uno=1;
			$this->TariModificarCargoTarifa();
		}
		return true;
	}

	function ValidarTariPedirNuevoCargoTarifa()//
	{
		if($_POST['codigocart']==NULL)
		{
			$this->frmError["codigocart"]=1;
		}
		else
		{
			if(!(ctype_alnum($_POST['codigocart'])==1))
			{
				$_POST['codigocart']='';
				$this->frmError["codigocart"]=1;
			}
			else
			{
				if(strlen($_POST['codigocart'])<5 OR strlen($_POST['codigocart'])>10)
				{
					$_POST['codigocart']='';
					$this->frmError["codigocart"]=1;
				}
				else
				{
					list($dbconn) = GetDBconn();
					$query ="SELECT cargo
							FROM tarifarios_detalle
							WHERE cargo='".$_POST['codigocart']."'
							AND tarifario_id='".$_SESSION['tarifa']['tarifaeleg']."';";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					if(!($resulta->fields[0]==NULL))
					{
						$_POST['codigocart']='';
						$this->frmError["codigocart"]=1;
					}
				}
			}
		}
		if($_POST['descripcit']==NULL)
		{
			$this->frmError["descripcit"]=1;
		}
		if($_POST['nivelatent']==NULL)
		{
			$this->frmError["nivelatent"]=1;
		}
		if($_POST['conceptort']==NULL)
		{
			$this->frmError["conceptort"]=1;
		}
		if($_POST['tipounidad']==NULL)
		{
			$this->frmError["tipounidad"]=1;
		}
		if($_POST['unidad']==NULL)
		{
			$this->frmError["tipounidad"]=1;
		}
/*		if($_POST['swuvrspret']==NULL)
		{
			$this->frmError["swuvrspret"]=1;
		}*/
		if($_POST['gravamenct']==NULL)
		{
			$this->frmError["gravamenct"]=1;
		}
		else
		{
			if(!((is_numeric($_POST['gravamenct'])==1) AND ($_POST['gravamenct']<=100)))
			{
				$_POST['gravamenct']='';
				$this->frmError["gravamenct"]=1;
			}
		}
		if($_POST['honorariot']==NULL)
		{
			$this->frmError["honorariot"]=1;
		}
		if($_POST['swcantidat']==NULL)
		{
			$this->frmError["swcantidat"]=1;
		}
		if($_POST['grupo']==NULL)
		{
			$this->frmError["grupo"]=1;
		}
		if($_POST['clasePr']==NULL)
		{
			$this->frmError["clasePr"]=1;
		}
		if($_POST['grupotarif']==NULL)
		{
			$this->frmError["grupotarif"]=1;
		}
		if($_POST['subgrtarif']==NULL)
		{
			$this->frmError["subgrtarif"]=1;
		}
		if($_POST['gruposmapt']==NULL)
		{
			$this->frmError["gruposmapt"]=1;
		}
/*		if($_POST['descripcit']==NULL||$_POST['nivelatent']==NULL||
		$_POST['conceptort']==NULL||$_POST['swcantidat']==NULL||
		$_POST['tipounidad']==NULL||$_POST['swuvrspret']==NULL||
		$_POST['gravamenct']==NULL||$_POST['honorariot']==NULL||
		$_POST['gruposmapt']==NULL||$_POST['codigocart']==NULL||
		$_POST['grupotarif']==NULL||$_POST['subgrtarif']==NULL||
		$_POST['grupo']==NULL||$_POST['clasePr']==NULL)*/
		if($_POST['descripcit']==NULL||$_POST['nivelatent']==NULL||
		$_POST['conceptort']==NULL||$_POST['swcantidat']==NULL||
		$_POST['tipounidad']==NULL||$_POST['gravamenct']==NULL||
		$_POST['honorariot']==NULL||$_POST['unidad']==NULL||
		$_POST['gruposmapt']==NULL||$_POST['codigocart']==NULL||
		$_POST['grupotarif']==NULL||$_POST['subgrtarif']==NULL||
		$_POST['grupo']==NULL||$_POST['clasePr']==NULL)
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS Y/O HAY DATOS INCORRECTOS";
			$this->uno=1;
			$this->TariPedirNuevoCargoTarifa();
		}
		else
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
//					precio,
//					sw_uvrs,
//					".$_POST['preciocart'].",
//					'".$_POST['swuvrspret']."',
			$_POST['gravamenct']=intval($_POST['gravamenct']);
			$query = "INSERT INTO tarifarios_detalle
					(cargo,
					tarifario_id,
					descripcion,
					grupo_tarifario_id,
					subgrupo_tarifario_id,
					grupo_tipo_cargo,
					tipo_cargo,
					nivel,
					precio,
					concepto_rips,
					gravamen,
					sw_cantidad,
					sw_honorarios,
					grupos_mapipos,
					tipo_unidad_id)
					VALUES
					('".$_POST['codigocart']."',
					'".$_SESSION['tarifa']['tarifaeleg']."',
					'".$_POST['descripcit']."',
					'".$_POST['grupotarif']."',
					'".$_POST['subgrtarif']."',
					'".$_POST['grupo']."',
					'".$_POST['clasePr']."',
					'".$_POST['nivelatent']."',
					".$_POST['unidad'].",
					'".$_POST['conceptort']."',
					".$_POST['gravamenct'].",
					'".$_POST['swcantidat']."',
					'".$_POST['honorariot']."',
					'".$_POST['gruposmapt']."',
					'".$_POST['tipounidad']."');";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			$dbconn->CommitTrans();
			$this->frmError["MensajeError"]="EL CARGO No. ".$_POST['codigocart']." FUE GUARDADO CORRECTAMENTE";
			$this->uno=1;
			$this->MenuMantenTarifa();
		}
		return true;
	}



	function BuscarConsultarCargoTarifa()//
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigotari'])
		{
			$codigo=$_REQUEST['codigotari'];
			$busqueda1="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descritari'])
		{
			$codigo=STRTOUPPER($_REQUEST['descritari']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['grupo'])
		{
			$codigo=STRTOUPPER($_REQUEST['grupo']);
			$busqueda3="AND A.grupo_tipo_cargo='".$codigo."'";
		}
		else
		{
			$busqueda3='';
		}
		if($_REQUEST['clasePr'])
		{
			$codigo=STRTOUPPER($_REQUEST['clasePr']);
			$busqueda4="AND A.tipo_cargo='".$codigo."'";
		}
		else
		{
			$busqueda4='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.cargo,
					A.descripcion,
					A.grupo_tipo_cargo,
					A.tipo_cargo,
					A.nivel,
					B.descripcion AS des1,
					C.descripcion AS des2
					FROM cups AS A,
					grupos_tipos_cargo AS B,
					tipos_cargos AS C
					WHERE A.grupo_tipo_cargo<>'SYS'
					AND A.grupo_tipo_cargo=B.grupo_tipo_cargo
					AND B.grupo_tipo_cargo=C.grupo_tipo_cargo
					AND A.tipo_cargo=C.tipo_cargo
					$busqueda1
					$busqueda2
					$busqueda3
					$busqueda4
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
				A.grupo_tipo_cargo,
				A.tipo_cargo,
				A.nivel,
				B.descripcion AS des1,
				C.descripcion AS des2
				FROM cups AS A,
				grupos_tipos_cargo AS B,
				tipos_cargos AS C
				WHERE A.grupo_tipo_cargo<>'SYS'
				AND A.grupo_tipo_cargo=B.grupo_tipo_cargo
				AND B.grupo_tipo_cargo=C.grupo_tipo_cargo
				AND A.tipo_cargo=C.tipo_cargo
				$busqueda1
				$busqueda2
				$busqueda3
				$busqueda4
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
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $var;
	}

	function BuscarModificarCargoTarifa()//
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigotari'])
		{
			$codigo=$_REQUEST['codigotari'];
			$busqueda1="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descritari'])
		{
			$codigo=STRTOUPPER($_REQUEST['descritari']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_POST['grupo'])
		{
			$codigo=STRTOUPPER($_POST['grupo']);
			$busqueda3="AND A.grupo_tipo_cargo='".$codigo."'";
		}
		else
		{
			$busqueda3='';
		}
		if($_POST['clasePr'])
		{
			$codigo=STRTOUPPER($_POST['clasePr']);
			$busqueda4="AND A.tipo_cargo='".$codigo."'";
		}
		else
		{
			$busqueda4='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.cargo,
					A.descripcion
					FROM cups AS A
					WHERE A.grupo_tipo_cargo<>'SYS'
					$busqueda1
					$busqueda2
					$busqueda3
					$busqueda4
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
				A.descripcion
				FROM cups AS A
				WHERE A.grupo_tipo_cargo<>'SYS'
				$busqueda1
				$busqueda2
				$busqueda3
				$busqueda4
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
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $var;
	}

	function BuscarCargoModificarTarifa($cargo)//
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.cargo,
				A.descripcion,
				A.grupo_tarifario_id,
				A.subgrupo_tarifario_id,
				A.grupo_tipo_cargo,
				A.tipo_cargo,
				A.nivel,
				A.concepto_rips,
				A.precio,
				A.gravamen,
				A.sw_cantidad,
				A.sw_honorarios,
				A.sw_uvrs,
				A.nivel_autorizador_id,
				A.sw_pos,
				A.grupos_mapipos,
				B.descripcion AS des1,
				C.descripcion AS des2,
				D.grupo_tarifario_descripcion AS des3,
				E.subgrupo_tarifario_descripcion AS des4
				FROM cups AS A,
				grupos_tipos_cargo AS B,
				tipos_cargos AS C,
				grupos_tarifarios AS D,
				subgrupos_tarifarios AS E
				WHERE A.cargo='".$cargo."'
				AND A.grupo_tipo_cargo=B.grupo_tipo_cargo
				AND A.grupo_tipo_cargo=C.grupo_tipo_cargo
				AND A.tipo_cargo=C.tipo_cargo
				AND A.grupo_tarifario_id=D.grupo_tarifario_id
				AND A.grupo_tarifario_id=E.grupo_tarifario_id
				AND A.subgrupo_tarifario_id=E.subgrupo_tarifario_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarNivelesTarifa()//
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT nivel,
				descripcion
				FROM niveles_atencion
				ORDER BY nivel;";
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

	function BuscarConceptosRipsTarifa()//
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT concepto_rips,
				descripcion
				FROM rips_conceptos
				ORDER BY concepto_rips;";
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

	function BuscarNivelAutorizadorTarifa()//
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT nivel_autorizador_id,
				descripcion
				FROM autorizaciones_niveles_autorizador
				ORDER BY nivel_autorizador_id;";
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

	function BuscarGruposMapiposTarifa()//
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT grupos_mapipos,
				descripcion
				FROM cups_grupos_mapipos
				ORDER BY grupos_mapipos;";
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

	function ValidarModificar1CargosTarifa()//
	{
		if($_POST['descripcit']==NULL)
		{
			$this->frmError["descripcit"]=1;
		}
		if($_POST['nivelatent']==NULL)
		{
			$this->frmError["nivelatent"]=1;
		}
		if($_POST['conceptort']==NULL)
		{
			$this->frmError["conceptort"]=1;
		}
		if($_POST['nivelautot']==NULL)
		{
			$this->frmError["nivelautot"]=1;
		}
		if($_POST['preciocart']==NULL)
		{
			$this->frmError["preciocart"]=1;
		}
		else
		{
			if(!((is_numeric($_POST['preciocart'])==1) AND ($_POST['preciocart']<10000000000.00)))
			{
				$_POST['preciocart']='';
				$this->frmError["preciocart"]=1;
			}
		}
		if($_POST['swuvrspret']==NULL)
		{
			$this->frmError["swuvrspret"]=1;
		}
		if($_POST['gravamenct']==NULL)
		{
			$this->frmError["gravamenct"]=1;
		}
		else
		{
			if(!((is_numeric($_POST['gravamenct'])==1) AND ($_POST['gravamenct']<=100)))
			{
				$_POST['gravamenct']='';
				$this->frmError["gravamenct"]=1;
			}
		}
		if($_POST['honorariot']==NULL)
		{
			$this->frmError["honorariot"]=1;
		}
		if($_POST['swposcargt']==NULL)
		{
			$this->frmError["swposcargt"]=1;
		}
		if($_POST['swcantidat']==NULL)
		{
			$this->frmError["swcantidat"]=1;
		}
		if($_POST['grupo']==NULL)
		{
			$this->frmError["grupo"]=1;
		}
		if($_POST['clasePr']==NULL)
		{
			$this->frmError["clasePr"]=1;
		}
		if($_POST['grupotarif']==NULL)
		{
			$this->frmError["grupotarif"]=1;
		}
		if($_POST['subgrtarif']==NULL)
		{
			$this->frmError["subgrtarif"]=1;
		}
		if($_POST['gruposmapt']==NULL)
		{
			$this->frmError["gruposmapt"]=1;
		}
		if($_POST['descripcit']==NULL||$_POST['nivelatent']==NULL||
		$_POST['conceptort']==NULL||$_POST['nivelautot']==NULL||
		$_POST['preciocart']==NULL||$_POST['swuvrspret']==NULL||
		$_POST['gravamenct']==NULL||$_POST['honorariot']==NULL||
		$_POST['swposcargt']==NULL||$_POST['swcantidat']==NULL||
		$_POST['grupo']==NULL||$_POST['clasePr']==NULL||
		$_POST['grupotarif']==NULL||$_POST['subgrtarif']==NULL||
		$_POST['gruposmapt']==NULL)
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS Y/O HAY DATOS INCORRECTOS";
			$this->uno=1;
			$this->Modificar1CargosTarifa();
		}
		else
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$_POST['gravamenct']=intval($_POST['gravamenct']);
			$query = "UPDATE cups SET
					descripcion='".$_POST['descripcit']."',
					grupo_tipo_cargo='".$_POST['grupo']."',
					tipo_cargo='".$_POST['clasePr']."',
					grupo_tarifario_id='".$_POST['grupotarif']."',
					subgrupo_tarifario_id='".$_POST['subgrtarif']."',
					nivel='".$_POST['nivelatent']."',
					concepto_rips='".$_POST['conceptort']."',
					precio=".$_POST['preciocart'].",
					gravamen=".$_POST['gravamenct'].",
					sw_cantidad='".$_POST['swcantidat']."',
					sw_honorarios='".$_POST['honorariot']."',
					sw_uvrs='".$_POST['swuvrspret']."',
					nivel_autorizador_id='".$_POST['nivelautot']."',
					sw_pos='".$_POST['swposcargt']."',
					grupos_mapipos='".$_POST['gruposmapt']."'
					WHERE cargo='".$_SESSION['tarif1']['datoscargo']['cargo']."';";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			$dbconn->CommitTrans();
			$_REQUEST['grupo']='';
			$_REQUEST['NomGrupo']='';
			$_REQUEST['clasePr']='';
			$_REQUEST['NomClase']='';
			$_POST['grupo']='';
			$_POST['NomGrupo']='';
			$_POST['clasePr']='';
			$_POST['NomClase']='';
			$this->frmError["MensajeError"]="EL CARGO No. ".$_SESSION['tarif1']['datoscargo']['cargo']." FUE MODIFICADO CORRECTAMENTE";
			$this->uno=1;
			$this->ModificarCargoTarifa();
		}
		return true;
	}

	function ValidarPedirIngresarCargoTarifa()//
	{
		if($_POST['numerocart']==NULL)
		{
			$this->frmError["numerocart"]=1;
		}
		else
		{
			if(!(ctype_alnum($_POST['numerocart'])==1))
			{
				$_POST['numerocart']='';
				$this->frmError["numerocart"]=1;
			}
			else
			{
				if(strlen($_POST['numerocart'])<6 OR strlen($_POST['numerocart'])>10)
				{
					$_POST['numerocart']='';
					$this->frmError["numerocart"]=1;
				}
			}
		}
		if($_POST['grupo']==NULL)
		{
			$this->frmError["grupo"]=1;
		}
		if($_POST['clasePr']==NULL)
		{
			$this->frmError["clasePr"]=1;
		}
		if($_POST['numerocart']==NULL||
		$_POST['grupo']==NULL||$_POST['clasePr']==NULL)
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS Y/O HAY DATOS INCORRECTOS";
			$this->uno=1;
			$this->PedirIngresarCargoTarifa();
		}
		else
		{
			list($dbconn) = GetDBconn();
			$query ="SELECT cargo FROM cups
					WHERE cargo='".$_POST['numerocart']."';";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			if($resulta->fields[0]==NULL)
			{
				$_SESSION['tarif1']['datocarnut']['cargo']=$_POST['numerocart'];
				$_SESSION['tarif1']['datocarnut']['grupotipoc']=$_POST['grupo'];
				$_SESSION['tarif1']['datocarnut']['nomgrtipoc']=$_POST['NomGrupo'];
				$_SESSION['tarif1']['datocarnut']['subgrtipoc']=$_POST['clasePr'];
				$_SESSION['tarif1']['datocarnut']['nomsutipoc']=$_POST['NomClase'];
				$this->IngresarCargoTarifa();
			}
			else
			{
				$this->frmError["MensajeError"]="EL CARGO No. ".$_POST['numerocart']." YA ESTÁ EN CUPS";
				$this->uno=1;
				$_POST['numerocart']='';
				$this->frmError["numerocart"]=1;
				$this->PedirIngresarCargoTarifa();
			}
		}
		return true;
	}

	function ValidarIngresarCargoTarifa()//
	{
		if($_POST['descripcit']==NULL)
		{
			$this->frmError["descripcit"]=1;
		}
		if($_POST['nivelatent']==NULL)
		{
			$this->frmError["nivelatent"]=1;
		}
		if($_POST['conceptort']==NULL)
		{
			$this->frmError["conceptort"]=1;
		}
		if($_POST['nivelautot']==NULL)
		{
			$this->frmError["nivelautot"]=1;
		}
		if($_POST['preciocart']==NULL)
		{
			$this->frmError["preciocart"]=1;
		}
		else
		{
			if(!((is_numeric($_POST['preciocart'])==1) AND ($_POST['preciocart']<10000000000.00)))
			{
				$_POST['preciocart']='';
				$this->frmError["preciocart"]=1;
			}
		}
		if($_POST['swuvrspret']==NULL)
		{
			$this->frmError["swuvrspret"]=1;
		}
		if($_POST['gravamenct']==NULL)
		{
			$this->frmError["gravamenct"]=1;
		}
		else
		{
			if(!((is_numeric($_POST['gravamenct'])==1) AND ($_POST['gravamenct']<=100)))
			{
				$_POST['gravamenct']='';
				$this->frmError["gravamenct"]=1;
			}
		}
		if($_POST['honorariot']==NULL)
		{
			$this->frmError["honorariot"]=1;
		}
		if($_POST['swposcargt']==NULL)
		{
			$this->frmError["swposcargt"]=1;
		}
		if($_POST['swcantidat']==NULL)
		{
			$this->frmError["swcantidat"]=1;
		}
		if($_POST['grupotarif']==NULL)
		{
			$this->frmError["grupotarif"]=1;
		}
		if($_POST['subgrtarif']==NULL)
		{
			$this->frmError["subgrtarif"]=1;
		}
		if($_POST['gruposmapt']==NULL)
		{
			$this->frmError["gruposmapt"]=1;
		}
		if($_POST['descripcit']==NULL||$_POST['nivelatent']==NULL||
		$_POST['conceptort']==NULL||$_POST['nivelautot']==NULL||
		$_POST['preciocart']==NULL||$_POST['swuvrspret']==NULL||
		$_POST['gravamenct']==NULL||$_POST['honorariot']==NULL||
		$_POST['swposcargt']==NULL||$_POST['swcantidat']==NULL||
		$_POST['grupotarif']==NULL||$_POST['subgrtarif']==NULL||
		$_POST['gruposmapt']==NULL)
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS Y/O HAY DATOS INCORRECTOS";
			$this->uno=1;
			$this->IngresarCargoTarifa();
		}
		else
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$_POST['gravamenct']=intval($_POST['gravamenct']);
			$query = "INSERT INTO cups
					(cargo,
					descripcion,
					grupo_tarifario_id,
					subgrupo_tarifario_id,
					grupo_tipo_cargo,
					tipo_cargo,
					nivel,
					concepto_rips,
					precio,
					gravamen,
					sw_cantidad,
					sw_honorarios,
					sw_uvrs,
					nivel_autorizador_id,
					sw_pos,
					grupos_mapipos)
					VALUES
					('".$_SESSION['tarif1']['datocarnut']['cargo']."',
					'".$_POST['descripcit']."',
					'".$_POST['grupotarif']."',
					'".$_POST['subgrtarif']."',
					'".$_SESSION['tarif1']['datocarnut']['grupotipoc']."',
					'".$_SESSION['tarif1']['datocarnut']['subgrtipoc']."',
					'".$_POST['nivelatent']."',
					'".$_POST['conceptort']."',
					".$_POST['preciocart'].",
					".$_POST['gravamenct'].",
					'".$_POST['swcantidat']."',
					'".$_POST['honorariot']."',
					'".$_POST['swuvrspret']."',
					'".$_POST['nivelautot']."',
					'".$_POST['swposcargt']."',
					'".$_POST['gruposmapt']."');";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			$dbconn->CommitTrans();
			$this->frmError["MensajeError"]="EL CARGO No. ".$_SESSION['tarif1']['datocarnut']['cargo']." FUE GUARDADO CORRECTAMENTE";
			$this->uno=1;
			$this->MantenimientoTarifa();
		}
		return true;
	}

	function BuscarGrupoTarifarioTarifa()//
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT grupo_tarifario_id,
				grupo_tarifario_descripcion
				FROM grupos_tarifarios
				WHERE grupo_tarifario_id<>'SYS'
				ORDER BY grupo_tarifario_descripcion;";
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

}//fin de la clase
?>
