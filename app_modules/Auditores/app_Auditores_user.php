<?php

/**
 * $Id: app_Auditores_user.php,v 1.3 2005/10/14 13:58:05 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Clase que permite establecer las funciones administrativas de los usuarios
 */

class app_Auditores_user extends classModulo
{
	var $uno;//para los errores
	var $limit;
	var $conteo;

	function app_Auditores_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->PrincipalAudito2();
		return true;
	}

	function UsuariosAudito()//Función de permisos
	{
		list($dbconn) = GetDBconn();
		$usuario=UserGetUID();
		$query = "SELECT A.empresa_id,
				B.razon_social AS descripcion1
				FROM userpermisos_auditores AS A,
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
		$url[1]='Auditores';
		$url[2]='user';
		$url[3]='PrincipalAudito';
		$url[4]='permisosaudito';
		$this->salida .=gui_theme_menu_acceso('AUDITORES', $mtz, $var1, $url, ModuloGetURL('system','Menu'));
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

	/********************FUNCIONES DE LA OPCIÓN AUDITORES********************/
	function BuscarFuncionesAudito($empresa)//Busca todos los auditores internos
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigoaudi'])
		{
			$codigo=$_REQUEST['codigoaudi'];
			$busqueda="AND A.usuario LIKE '%$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descriaudi'])
		{
			$codigo=STRTOUPPER($_REQUEST['descriaudi']);
			$busqueda2="AND UPPER(A.nombre) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM
					(
						SELECT A.usuario_id,
						A.usuario,
						A.nombre,
						C.usuario_id AS auditor,
						C.sw_tipo_funcion
						FROM system_usuarios AS A,
						system_usuarios_empresas AS B
						LEFT JOIN system_usuarios_funciones AS C ON
						(B.usuario_id=C.usuario_id)
						WHERE B.empresa_id='".$empresa."'
						AND B.usuario_id=A.usuario_id
						AND A.activo='1'
						AND A.sw_admin='0'
						AND A.usuario_id<>".UserGetUID()."
						$busqueda
						$busqueda2
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
				SELECT A.usuario_id,
				A.usuario,
				A.nombre,
				C.usuario_id AS auditor,
				C.sw_tipo_funcion
				FROM system_usuarios AS A,
				system_usuarios_empresas AS B
				LEFT JOIN system_usuarios_funciones AS C ON
				(B.usuario_id=C.usuario_id)
				WHERE B.empresa_id='".$empresa."'
				AND B.usuario_id=A.usuario_id
				AND A.activo='1'
				AND A.sw_admin='0'
				AND A.usuario_id<>".UserGetUID()."
				$busqueda
				$busqueda2
				ORDER BY A.nombre
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

	function ValidarFuncionesAudito()//
	{
		$ciclo=sizeof($_SESSION['audit1']['auditores']);
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		for($i=0;$i<$ciclo;$i++)
		{
			if(!empty($_POST['funcion'.$i]) AND $_POST['funcion'.$i]<>-1)
			{
				if($_SESSION['audit1']['auditores'][$i]['auditor']==NULL)
				{
					$query = "INSERT INTO system_usuarios_funciones
							(usuario_id,
							sw_tipo_funcion)
							VALUES
							(".$_SESSION['audit1']['auditores'][$i]['usuario_id'].",
							'".$_POST['funcion'.$i]."');";
					$resulta = $dbconn->Execute($query);
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
					$query = "UPDATE system_usuarios_funciones SET
							sw_tipo_funcion='".$_POST['funcion'.$i]."'
							WHERE usuario_id=".$_SESSION['audit1']['auditores'][$i]['usuario_id'].";";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollBackTrans();
						return false;
					}
				}
			}
			else if(!empty($_POST['funcion'.$i])
			AND $_POST['funcion'.$i]==-1
			AND $_SESSION['audit1']['auditores'][$i]['auditor']<>NULL)
			{
				$query = "DELETE FROM system_usuarios_funciones
						WHERE usuario_id=".$_SESSION['audit1']['auditores'][$i]['usuario_id'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
			}
		}
		$dbconn->CommitTrans();
		$this->FuncionesAudito();
		return true;
	}

	function BuscarAuditoresInternosAudito()//Busca todos los auditores internos
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.usuario_id,
				A.extension,
				A.celular,
				A.estado,
				B.nombre,
				D.descripcion
				FROM auditores_internos AS A,
				system_usuarios AS B,
				system_usuarios_funciones AS C,
				tipos_auditoria AS D
				WHERE C.usuario_id=B.usuario_id
				AND C.usuario_id=A.usuario_id
				AND A.tipo_auditoria_id=D.tipo_auditoria_id
				ORDER BY B.nombre, A.estado;";
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

	function BuscarAuditorInAudito()//Busca los usuarios del sistema que no sean auditores in
	{
		list($dbconn) = GetDBconn();//estado del usuario
		$query = "SELECT A.usuario_id,
				A.nombre
				FROM system_usuarios AS A,
				system_usuarios_funciones AS B
				WHERE B.usuario_id=A.usuario_id
				AND A.activo='1'
				AND A.sw_admin='0'
				AND A.usuario_id<>".UserGetUID()."
				AND B.sw_tipo_funcion<>1
				EXCEPT
				(
					SELECT D.usuario_id,
					D.nombre
					FROM system_usuarios AS D,
					auditores_internos AS C
					WHERE D.usuario_id=C.usuario_id
				)
				ORDER BY nombre;";
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

	function ModificaAuditorInAudito($auditor)//Modifica un auditor interno
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT extension,
				celular,
				estado,
				tipo_auditoria_id
				FROM auditores_internos
				WHERE usuario_id=".$auditor.";";
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

	function TraerAuditorias()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_auditoria_id,descripcion
				FROM tipos_auditoria;";
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

	function AuditorInActivo()//Funcion que cambia el estado del auditor interno
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['estado']==1)
		{
		$query = "UPDATE auditores_internos SET estado=1
				WHERE usuario_id=".$_REQUEST['usuario'].";";
		}
		else
		{
		$query = "UPDATE auditores_internos SET estado=0
				WHERE usuario_id=".$_REQUEST['usuario'].";";
		}
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
			return false;
		}
		$this->AuditoresInternosAudito();
		return true;
	}

	function ValidarAuditorInAudito()//Guarda un usuario como auditor interno
	{
		if($_POST['auditorctra']==NULL)
		{
			$this->frmError["auditorctra"]=1;
		}
		if(is_numeric($_POST['telefono'])==0)
		{
			$_POST['telefono']='';
		}
		$telefono=explode(' ',$_POST['celular']);
		for($t=0;$t<sizeof($telefono);$t++)
		{
			if(is_numeric($telefono[$t])==0)
			{
				$_POST['celular']='';
				break;
			}
		}
		if($_POST['estadoin']==NULL)
		{
			$this->frmError["estadoin"]=1;
		}
		if($_POST['auditorctra']==NULL||$_POST['estadoin']==NULL)
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno=1;
			$this->IngresaAuditorInAudito();
			return true;
		}
		else
		{
			if($_POST['estadoin']==2)
			{
				$_POST['estadoin']=0;
			}
			list($dbconn) = GetDBconn();
			if($_REQUEST['inguarmodi']==1)
			{
				$query = "INSERT INTO auditores_internos
						(usuario_id,
						extension,
						celular,
						estado,
						tipo_auditoria_id)
						VALUES
						(".$_POST['auditorctra'].",
						'".$_POST['telefono']."',
						'".$_POST['celular']."',
						'".$_POST['estadoin']."',
						'".$_POST['tipoauditoria']."');";
			}
			else if($_REQUEST['inguarmodi']==2)
			{
				$query = "UPDATE auditores_internos SET
						extension='".$_POST['telefono']."',
						celular='".$_POST['celular']."',
						estado='".$_POST['estadoin']."',
						tipo_auditoria_id='".$_POST['tipoauditoria']."'
						WHERE usuario_id=".$_POST['auditorctra'].";";
			}
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$this->AuditoresInternosAudito();
			return true;
		}
	}

	function EliminarAuditorInAudito()//Elimina un auditor interno
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query = "DELETE FROM auditores_internos
				WHERE usuario_id=".$_REQUEST['usuario'].";";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$dbconn->RollBackTrans();
		}
		$dbconn->CommitTrans();
		$this->AuditoresInternosAudito();
		return true;
	}

	function BuscarAuditoresExternosAudito()//Busca todos los auditores externos
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.usuario_id,
				A.telefonos,
				A.celular,
				A.estado,
				A.tipo_id_tercero,
				A.tercero_id,
				B.nombre,
				D.nombre_tercero
				FROM auditores_externos AS A,
				system_usuarios AS B,
				system_usuarios_funciones AS C,
				terceros AS D
				WHERE C.usuario_id=B.usuario_id
				AND A.usuario_id=B.usuario_id
				AND A.tipo_id_tercero=D.tipo_id_tercero
				AND A.tercero_id=D.tercero_id
				ORDER BY B.nombre, A.estado;";
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

	function BuscarAuditorExAudito()//Busca los usuarios del sistema que no sean auditores ex
	{
		list($dbconn) = GetDBconn();//estado del usuario
		$query = "SELECT A.usuario_id,
				A.nombre
				FROM system_usuarios AS A,
				system_usuarios_funciones AS B
				WHERE B.usuario_id=A.usuario_id
				AND A.activo='1'
				AND A.sw_admin='0'
				AND A.usuario_id<>".UserGetUID()."
				AND B.sw_tipo_funcion<>1
				ORDER BY nombre;";
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

	function ModificaAuditorExAudito($auditor)//Modifica un auditor externo
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.tipo_id_tercero,
				A.tercero_id,
				A.telefonos,
				A.celular,
				A.estado,
				B.nombre_tercero
				FROM auditores_externos AS A,
				terceros AS B
				WHERE A.usuario_id=".$auditor."
				AND A.tipo_id_tercero=B.tipo_id_tercero
				AND A.tercero_id=B.tercero_id;";
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

	function AuditorExActivo()//Funcion que cambia el estado del auditor externo
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['estado']==1)
		{
		$query = "UPDATE auditores_externos SET estado=1
				WHERE usuario_id=".$_REQUEST['usuario'].";";
		}
		else
		{
		$query = "UPDATE auditores_externos SET estado=0
				WHERE usuario_id=".$_REQUEST['usuario'].";";
		}
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
			return false;
		}
		$this->AuditoresExternosAudito();
		return true;
	}

	function ValidarAuditorExAudito()//Guarda un usuario como auditor externo
	{
		if($_POST['auditorctra']==NULL)
		{
			$this->frmError["auditorctra"]=1;
		}
		$telefono=explode(' ',$_POST['telefono']);
		for($t=0;$t<sizeof($telefono);$t++)
		{
			if(is_numeric($telefono[$t])==0)
			{
				$this->frmError["telefono"]=1;
				$_POST['telefono']='';
				break;
			}
		}
		$telefono=explode(' ',$_POST['celular']);
		for($t=0;$t<sizeof($telefono);$t++)
		{
			if(is_numeric($telefono[$t])==0)
			{
				$_POST['celular']='';
				break;
			}
		}
		if($_POST['estadoex']==NULL)
		{
			$this->frmError["estadoex"]=1;
		}
		if(empty($_POST['tipoTerceroId']))
		{
			$this->frmError["tipoTerceroId"]=1;
		}
		if(empty($_POST['codigo']))
		{
			$this->frmError["codigo"]=1;
		}
		if(empty($_POST['nombre']))
		{
			$this->frmError["nombre"]=1;
		}
		if($_POST['auditorctra']==NULL||$_POST['estadoex']==NULL||
		$_POST['telefono']==NULL||empty($_POST['tipoTerceroId'])||
		empty($_POST['codigo']))
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno=1;
			$this->IngresaAuditorExAudito();
			return true;
		}
		else
		{
			if($_POST['estadoex']==2)
			{
				$_POST['estadoex']=0;
			}
			list($dbconn) = GetDBconn();
			if($_REQUEST['exguarmodi']==1)
			{
				$query = "INSERT INTO auditores_externos
						(usuario_id,
						tipo_id_tercero,
						tercero_id,
						telefonos,
						celular,
						estado)
						VALUES
						(".$_POST['auditorctra'].",
						'".$_POST['tipoTerceroId']."',
						'".$_POST['codigo']."',
						'".$_POST['telefono']."',
						'".$_POST['celular']."',
						'".$_POST['estadoex']."');";
			}
			else if($_REQUEST['exguarmodi']==2)
			{
				$query = "UPDATE auditores_externos SET
						tipo_id_tercero='".$_POST['tipoTerceroId']."',
						tercero_id='".$_POST['codigo']."',
						telefonos='".$_POST['telefono']."',
						celular='".$_POST['celular']."',
						estado='".$_POST['estadoex']."'
						WHERE usuario_id=".$_POST['auditorctra']."
						AND tipo_id_tercero='".$_POST['tipocambio']."'
						AND tercero_id='".$_POST['terccambio']."';";
			}
			$resulta = $dbconn->Execute($query);
/*			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}*/
			$this->AuditoresExternosAudito();
			return true;
		}
	}

	function EliminarAuditorExAudito()//Elimina un auditor externo
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query = "DELETE FROM auditores_externos
				WHERE usuario_id=".$_REQUEST['usuario']."
				AND tipo_id_tercero='".$_REQUEST['tipoterid']."'
				AND tercero_id='".$_REQUEST['terceroid']."';";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$dbconn->RollBackTrans();
		}
		$dbconn->CommitTrans();
		$this->AuditoresExternosAudito();
		return true;
	}

	function CambiarAuditorExterno($auditor)//Determina si se puede cambiar la empresa del auditor externo
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT B.plan_id
				FROM auditores_externos AS A,
				planes_auditores_ext AS B
				WHERE A.usuario_id=".$auditor."
				AND A.usuario_id=B.usuario_id;";
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

}//fin de la clase
?>
