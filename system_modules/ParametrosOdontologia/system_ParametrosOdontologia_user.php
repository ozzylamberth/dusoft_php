
<?php

/**
* Modulo de ParametrosOdontologia (PHP).
*
* Modulo para el mantenimiento de los cargos del tarifario cups y de
* los cargos de la interface con datalab, asi como sus equivalencias
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* system_ParametrosOdontologia_user.php
*
**/

class system_ParametrosOdontologia_user extends classModulo
{
	var $uno;//para los errores
	var $limit;
	var $conteo;

	function system_ParametrosOdontologia_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->PrincipalPOdont();
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

	function BuscarTiposCuadrantesPOdont()
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT hc_tipo_cuadrante_id,
		descripcion,
		indice_orden,
		sw_mostrar
		FROM hc_tipos_cuadrantes_dientes
		ORDER BY indice_orden;";
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

	function ValidarNuevoTiposCuadrantesPOdont()
	{
		if($_POST['descripcio']==NULL)
		{
			$this->frmError["descripcio"]=1;
		}
		if(is_numeric($_POST['ordeindice'])==0)
		{
			$this->frmError["ordeindice"]=1;
		}
		if($this->frmError["descripcio"]==1 OR $this->frmError["ordeindice"]==1)
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno=1;
			$this->NuevoTiposCuadrantesPOdont();
			return true;
		}
		else
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query ="SELECT hc_tipo_cuadrante_id
			FROM hc_tipos_cuadrantes_dientes
			WHERE indice_orden=".$_POST['ordeindice'].";";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL GUARDAR LOS DATOS: ".$dbconn->ErrorMsg()."";
				$this->uno=1;
				$this->NuevoTiposCuadrantesPOdont();
				return true;
			}
			else if(($resulta->fields[0]<>NULL AND $_REQUEST['guarmodi1']==1) OR
			($resulta->fields[0]<>NULL AND $_REQUEST['guarmodi1']==2 AND
			$resulta->fields[0]<>$_SESSION['podont']['cuadragumo']['hc_tipo_cuadrante_id']))
			{
				$this->frmError["MensajeError"]="YA EXISTE UN REGISTRO CON EL INDICE DE ORDEN: ".$_POST['ordeindice']."";
				$this->uno=1;
				$this->NuevoTiposCuadrantesPOdont();
				return true;
			}
			if($_REQUEST['guarmodi1']==1)
			{
				$query ="INSERT INTO hc_tipos_cuadrantes_dientes
				(descripcion,
				indice_orden,
				sw_mostrar)
				VALUES
				('".$_POST['descripcio']."',
				".$_POST['ordeindice'].",
				'".$_REQUEST['swmostrara']."');";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollBackTrans();
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL GUARDAR LOS DATOS: ".$dbconn->ErrorMsg()."";
					$this->uno=1;
					$this->TiposCuadrantesPOdont();
					return true;
				}
				else
				{
					$dbconn->CommitTrans();
					$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
					$this->uno=1;
					$this->TiposCuadrantesPOdont();
					return true;
				}
			}
			else
			{
				$query ="UPDATE hc_tipos_cuadrantes_dientes SET
				descripcion='".$_POST['descripcio']."',
				indice_orden=".$_POST['ordeindice'].",
				sw_mostrar='".$_REQUEST['swmostrara']."'
				WHERE hc_tipo_cuadrante_id=".$_SESSION['podont']['cuadragumo']['hc_tipo_cuadrante_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollBackTrans();
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL GUARDAR LOS DATOS: ".$dbconn->ErrorMsg()."";
					$this->uno=1;
					$this->TiposCuadrantesPOdont();
					return true;
				}
				else
				{
					$dbconn->CommitTrans();
					$this->frmError["MensajeError"]="DATOS MODIFICADOS CORRECTAMENTE";
					$this->uno=1;
					$this->TiposCuadrantesPOdont();
					return true;
				}
			}
		}
	}

	function BuscarTiposProblemasPOdont()
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.hc_tipo_problema_diente_id,
		A.descripcion,
		A.sw_presupuesto,
		A.indice_orden,
		A.sw_cariado,
		A.sw_obturado,
		A.sw_perdidos,
		A.sw_sanos,
		A.sw_diente_completo
		FROM hc_tipos_problemas_dientes AS A
		ORDER BY A.indice_orden;";
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

	function ValidarNuevoTiposProblemasPOdont()//
	{
		if($_POST['descripcio']==NULL)
		{
			$this->frmError["descripcio"]=1;
		}
		if(is_numeric($_POST['ordeindice'])==0)
		{
			$this->frmError["ordeindice"]=1;
		}
		$switches=0;
		if($_POST['cariados']==1)
		{
			$switches++;
		}
		if($_POST['obturado']==1)
		{
			$switches++;
		}
		if($_POST['perdidos']==1)
		{
			$switches++;
		}
		if($_POST['dsanitos']==1)
		{
			$switches++;
		}
		if($switches>1)
		{
			$this->frmError["cariados"]=1;
			$this->frmError["obturado"]=1;
			$this->frmError["perdidos"]=1;
			$this->frmError["dsanitos"]=1;
		}
		if($_POST['descripcio']==NULL||$_POST['ordeindice']==NULL||
		$switches>1||$_POST['presupuest']==1)
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS O EL PROBLEMA ESTÁ CLASIFICADO EN MÁS DE UN TIPO DE DIENTE";
			$this->uno=1;
			$this->NuevoTiposProblemasPOdont();
			return true;
		}
		else
		{//--select setval('hc_tipos_problemas_dientes_hc_tipo_problema_diente_id_seq',20);
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query ="SELECT hc_tipo_problema_diente_id
			FROM hc_tipos_problemas_dientes
			WHERE indice_orden=".$_POST['ordeindice'].";";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL GUARDAR LOS DATOS: ".$dbconn->ErrorMsg()."";
				$this->uno=1;
				$this->NuevoTiposProblemasPOdont();
				return true;
			}
			else if(($resulta->fields[0]<>NULL AND $_REQUEST['guarmodi2']==1) OR
			($resulta->fields[0]<>NULL AND $_REQUEST['guarmodi2']==2 AND
			$resulta->fields[0]<>$_SESSION['podont']['problegumo']['hc_tipo_problema_diente_id']))
			{
				$this->frmError["MensajeError"]="YA EXISTE UN REGISTRO CON EL INDICE DE ORDEN: ".$_POST['ordeindice']."";
				$this->uno=1;
				$this->NuevoTiposProblemasPOdont();
				return true;
			}
			if($_REQUEST['guarmodi2']==1)
			{
				$query ="INSERT INTO hc_tipos_problemas_dientes
				(descripcion,
				sw_presupuesto,
				indice_orden,
				sw_cariado,
				sw_obturado,
				sw_perdidos,
				sw_sanos,
				sw_diente_completo)
				VALUES
				('".$_POST['descripcio']."',
				'".$_POST['presupuest']."',
				".$_POST['ordeindice'].",
				'".$_POST['cariados']."',
				'".$_POST['obturado']."',
				'".$_POST['perdidos']."',
				'".$_POST['dsanitos']."',
				'".$_POST['completo']."');";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollBackTrans();
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL GUARDAR LOS DATOS: ".$dbconn->ErrorMsg()."";
					$this->uno=1;
					$this->TiposProblemasPOdont();
					return true;
				}
				else
				{
					$dbconn->CommitTrans();
					$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
					$this->uno=1;
					$this->TiposProblemasPOdont();
					return true;
				}
			}
			else
			{
				$query ="UPDATE hc_tipos_problemas_dientes SET
				descripcion='".$_POST['descripcio']."',
				sw_presupuesto='".$_POST['presupuest']."',
				indice_orden=".$_POST['ordeindice'].",
				sw_cariado='".$_POST['cariados']."',
				sw_obturado='".$_POST['obturado']."',
				sw_perdidos='".$_POST['perdidos']."',
				sw_sanos='".$_POST['dsanitos']."',
				sw_diente_completo='".$_POST['completo']."'
				WHERE hc_tipo_problema_diente_id=".$_SESSION['podont']['problegumo']['hc_tipo_problema_diente_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollBackTrans();
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL GUARDAR LOS DATOS: ".$dbconn->ErrorMsg()."";
					$this->uno=1;
					$this->TiposProblemasPOdont();
					return true;
				}
				else
				{
					$dbconn->CommitTrans();
					$this->frmError["MensajeError"]="DATOS MODIFICADOS CORRECTAMENTE";
					$this->uno=1;
					$this->TiposProblemasPOdont();
					return true;
				}
			}
		}
	}

	function BuscarTiposSolucionesPOdont()
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT hc_tipo_producto_diente_id,
		descripcion,
		indice_orden
		FROM hc_tipos_productos_dientes
		ORDER BY indice_orden;";
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

	function ValidarNuevoTiposSolucionesPOdont()//
	{
		if($_POST['descripcio']==NULL)
		{
			$this->frmError["descripcio"]=1;
		}
		if(is_numeric($_POST['ordeindice'])==0)
		{
			$this->frmError["ordeindice"]=1;
		}
		if($this->frmError["descripcio"]==1 OR $this->frmError["ordeindice"]==1)
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno=1;
			$this->NuevoTiposSolucionesPOdont();
			return true;
		}
		else
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query ="SELECT hc_tipo_producto_diente_id
			FROM hc_tipos_productos_dientes
			WHERE indice_orden=".$_POST['ordeindice'].";";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL GUARDAR LOS DATOS: ".$dbconn->ErrorMsg()."";
				$this->uno=1;
				$this->NuevoTiposSolucionesPOdont();
				return true;
			}
			else if(($resulta->fields[0]<>NULL AND $_REQUEST['guarmodi3']==1) OR
			($resulta->fields[0]<>NULL AND $_REQUEST['guarmodi3']==2 AND
			$resulta->fields[0]<>$_SESSION['podont']['solucigumo']['hc_tipo_producto_diente_id']))
			{
				$this->frmError["MensajeError"]="YA EXISTE UN REGISTRO CON EL INDICE DE ORDEN: ".$_POST['ordeindice']."";
				$this->uno=1;
				$this->NuevoTiposSolucionesPOdont();
				return true;
			}
			if($_REQUEST['guarmodi3']==1)
			{
				$query ="INSERT INTO hc_tipos_productos_dientes
				(descripcion,
				indice_orden)
				VALUES
				('".$_POST['descripcio']."',
				".$_POST['ordeindice'].");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollBackTrans();
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL GUARDAR LOS DATOS: ".$dbconn->ErrorMsg()."";
					$this->uno=1;
					$this->TiposSolucionesPOdont();
					return true;
				}
				else
				{
					$dbconn->CommitTrans();
					$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
					$this->uno=1;
					$this->TiposSolucionesPOdont();
					return true;
				}
			}
			else
			{
				$query ="UPDATE hc_tipos_productos_dientes SET
				descripcion='".$_POST['descripcio']."',
				indice_orden=".$_POST['ordeindice']."
				WHERE hc_tipo_producto_diente_id=".$_SESSION['podont']['solucigumo']['hc_tipo_producto_diente_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollBackTrans();
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL GUARDAR LOS DATOS: ".$dbconn->ErrorMsg()."";
					$this->uno=1;
					$this->TiposSolucionesPOdont();
					return true;
				}
				else
				{
					$dbconn->CommitTrans();
					$this->frmError["MensajeError"]="DATOS MODIFICADOS CORRECTAMENTE";
					$this->uno=1;
					$this->TiposSolucionesPOdont();
					return true;
				}
			}
		}
	}

}//fin de la clase
?>
