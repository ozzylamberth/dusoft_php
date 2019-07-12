
<?php

/**
* Modulo de InvProveedores (PHP).
*
* Modulo para la administración de los proveedores de insumos y medicamentos,
* teniendo presente los parametros de evaluación, su información básica,
* modelos de pago, acuerdos y cualquier tipo de datos, que permita la
* valoración y los recursos para la negociación directa con los mismos
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_InvProveedores_user.php
*
* Clase que permite el acceso a los datos de los proveedores de mi inventario,
* así mismo permite realizar los procesos administrativos sobre los mismos
**/

class app_InvProveedores_user extends classModulo
{
	var $uno;//para los errores
	var $limit;
	var $conteo;

	function app_InvProveedores_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->PrincipalInvPro2();//2
		return true;
	}

	function UsuariosInvPro()//Función de permisos
	{
		list($dbconn) = GetDBconn();
		$usuario=UserGetUID();
		$query = "SELECT A.empresa_id,
				B.razon_social AS descripcion1
				FROM userpermisos_invproveedores AS A,
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
		$url[1]='InvProveedores';
		$url[2]='user';
		$url[3]='PrincipalInvPro';
		$url[4]='permisoinvpro';
		$this->salida .=gui_theme_menu_acceso('PROVEEDORES DE INVENTARIOS', $mtz, $var1, $url, ModuloGetURL('system','Menu'));
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

	function BuscarProveedorInvPro($empresa)//Busca los terceros que sean proveedores de la empresa
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigoinvp'])
		{
			$codigo=$_REQUEST['codigoinvp'];
			$busqueda="AND A.tercero_id LIKE '%$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descriinvp'])
		{
			$codigo=STRTOUPPER($_REQUEST['descriinvp']);
			$busqueda2="AND UPPER(B.nombre_tercero) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM
					(
						SELECT A.codigo_proveedor_id,
						A.empresa_id,
						A.tipo_id_tercero,
						A.tercero_id,
						A.empresa_id_centro,
						A.centro_utilidad,
						A.estado,
						B.nombre_tercero,
						C.descripcion
						FROM terceros_proveedores AS A
						LEFT JOIN centros_utilidad AS C ON
						(C.centro_utilidad=A.centro_utilidad
						AND c.empresa_id='".$empresa."'),
						terceros AS B
						WHERE (A.empresa_id='".$empresa."'
						OR empresa_id_centro='".$empresa."')
						AND A.tipo_id_tercero=B.tipo_id_tercero
						AND A.tercero_id=B.tercero_id
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
				SELECT A.codigo_proveedor_id,
				A.empresa_id,
				A.tipo_id_tercero,
				A.tercero_id,
				A.empresa_id_centro,
				A.centro_utilidad,
				A.estado,
				B.nombre_tercero,
				C.descripcion
				FROM terceros_proveedores AS A
				LEFT JOIN centros_utilidad AS C ON
				(C.centro_utilidad=A.centro_utilidad
				AND c.empresa_id='".$empresa."'),
				terceros AS B
				WHERE (A.empresa_id='".$empresa."'
				OR empresa_id_centro='".$empresa."')
				AND A.tipo_id_tercero=B.tipo_id_tercero
				AND A.tercero_id=B.tercero_id
				$busqueda
				$busqueda2
				ORDER BY B.nombre_tercero
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

	function BuscarCentroUtilidad($empresa)//Busca los Centros de utilidad de la empresa si los tiene
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT centro_utilidad,
				descripcion
				FROM centros_utilidad
				WHERE empresa_id='".$empresa."'
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

	function GuardarTerceroInvPro()//Llama a la forma de editar el proveedor del modulo Terceros
	{
		$_SESSION['INFORM']['RETORNO']['contenedor']='app';
		$_SESSION['INFORM']['RETORNO']['modulo']='InvProveedores';
		$_SESSION['INFORM']['RETORNO']['tipo']='user';
		$_SESSION['INFORM']['RETORNO']['metodo']='RetornaTerceroInvPro';
		$_SESSION['tercer']['empresa']=$_SESSION['invpro']['empresa'];
		$_SESSION['tercer']['razonso']=$_SESSION['invpro']['razonso'];
		$_SESSION['tercer']['tipo_id_tercero']=$_POST['tipoTerceroId'];
		$_SESSION['tercer']['tercero_id']=$_POST['codigo'];
		$_SESSION['tercer']['nombre_tercero']=$_POST['nombre'];
		$this->ReturnMetodoExterno('app','Terceros','user','BusquedaTercer');//IngresaTercer
		return true;
	}

	function RetornaTerceroInvPro()//Borra las variables de sesión externas y continua el proceso
	{
		$_POST['tipoTerceroId']=$_SESSION['tercer']['tipo_id_tercero'];
		$_POST['codigo']=$_SESSION['tercer']['tercero_id'];
		$_POST['nombre']=$_SESSION['tercer']['nombre_tercero'];
		if($_SESSION['INFORM']['RETORNO']['sw']==1)
		{
			$this->frmError["MensajeError"]="EL USUARIO CANCELÓ LA TRANSACCIÓN
			<br>NO SE GUARDÓ NI SE MODIFICÓ INFORMACIÓN SOBRE EL TERCERO";
			$this->uno=1;
			UNSET($_SESSION['tercer']);
			UNSET($_SESSION['INFORM']);
			$this->IngresaProveedorInvPro();
			return true;
		}
		else if($_SESSION['INFORM']['RETORNO']['sw']==2)
		{
			$this->frmError["MensajeError"]="DATOS DEL TERCERO GUARDADOS CORRECTAMENTE ";
			$this->uno=1;
			UNSET($_SESSION['tercer']);
			UNSET($_SESSION['INFORM']);
			$this->IngresaProveedorInvPro();
			return true;
		}
		else if($_SESSION['INFORM']['RETORNO']['sw']==3)
		{
			$this->frmError["MensajeError"]="DATOS DE LA EMPRESA INCOMPLETOS";
			$this->uno=1;
			UNSET($_SESSION['tercer']);
			UNSET($_SESSION['INFORM']);
			$this->IngresaProveedorInvPro();
			return true;
		}
	}

	function BuscarFormasPagoInvPro()//Busca las formas de pago
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT formas_pago_id,
				descripcion
				FROM compras_formas_pago
				ORDER BY formas_pago_id;";
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

	function MostrarFormasPagoInvPro($proveedor)//Busca las formas de pago
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT formas_pago_id
				FROM compras_proveedores_formas_pago
				WHERE codigo_proveedor_id=".$proveedor."
				ORDER BY formas_pago_id;";
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

	function GuardarProveedorInvPro()//Guarda los datos del tercero proveedor
	{
		if($_POST['tipoTerceroId']==NULL)
		{
			$this->frmError["tipoTerceroId"]=1;
		}
		if($_POST['codigo']==NULL)
		{
			$this->frmError["codigo"]=1;
		}
		if($_POST['nombre']==NULL)
		{
			$this->frmError["nombre"]=1;
		}
		if(empty($_POST['estado']))
		{
			$this->frmError["estado"]=1;
		}
		if(is_numeric($_POST['cupoprovip'])==0)
		{//$this->frmError["cupoprovip"]=1;
			$_POST['cupoprovip']='';
		}
		else
		{
			$cupo=doubleval($_POST['cupoprovip']);
			if($cupo >= 100000000000000)//14+1
			{//$this->frmError["cupoprovip"]=1;
				$_POST['cupoprovip']='';
			}
		}
		if(is_numeric($_POST['tiempoenip'])==0)
		{
			$this->frmError["tiempoenip"]=1;
			$_POST['tiempoenip']='';
		}
		else
		{
			$entre=intval($_POST['tiempoenip']);
			if($entre > 32000)
			{
				$this->frmError["tiempoenip"]=1;
				$_POST['tiempoenip']='';
			}
		}
		if(is_numeric($_POST['diasgracip'])==0)
		{
			$this->frmError["diasgracip"]=1;
			$_POST['diasgracip']='';
		}
		else
		{
			$graci=intval($_POST['diasgracip']);
			if($graci > 32000)
			{
				$this->frmError["diasgracip"]=1;
				$_POST['diasgracip']='';
			}
		}
		if(is_numeric($_POST['diascredip'])==0)
		{
			$this->frmError["diascredip"]=1;
			$_POST['diascredip']='';
		}
		else
		{
			$credi=intval($_POST['diascredip']);
			if($credi > 32000)
			{
				$this->frmError["diascredip"]=1;
				$_POST['diascredip']='';
			}
		}
		if(is_numeric($_POST['desporcoip'])==0)
		{
			$this->frmError["desporcoip"]=1;
			$_POST['desporcoip']='';
		}
		else
		{
			$descu=doubleval($_POST['desporcoip']);
			if($descu > 100)//999.9999
			{
				$this->frmError["desporcoip"]=1;
				$_POST['desporcoip']='';
			}
		}
		$this->frmError["formpagoip"]=0;
		for($i=0;$i<$_POST['formpagoip'];$i++)
		{
			if($_POST['formpagoip'.$i]<>NULL)
			{
				$this->frmError["formpagoip"]=0;
				break;
			}
			else
			{
				$this->frmError["formpagoip"]=1;
			}
		}
		if($_POST['tipoTerceroId']==NULL||$_POST['codigo']==NULL||
		$_POST['nombre']==NULL||$_POST['tiempoenip']==NULL||
		$_POST['diasgracip']==NULL||$_POST['diascredip']==NULL||
		$_POST['desporcoip']==NULL||$this->frmError["formpagoip"]==1||
		empty($_POST['estado']))//$_POST['cupoprovip']==NULL||
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno = 1;
			$this->IngresaProveedorInvPro();
			return true;
		}
		else
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			if($_POST['estado']==2)
			{
				$_POST['estado']=0;
			}
			if($_POST['cupoprovip']==NULL)
			{
				$cupo="NULL";
			}
			$query = "SELECT tipo_id_tercero,
					tercero_id
					FROM terceros_proveedores
					WHERE (empresa_id='".$_SESSION['invpro']['empresa']."'
					OR empresa_id_centro='".$_SESSION['invpro']['empresa']."')
					AND tipo_id_tercero='".$_POST['tipoTerceroId']."'
					AND tercero_id='".$_POST['codigo']."';";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			if($resulta->EOF)
			{
				$query = "SELECT NEXTVAL ('terceros_proveedores_codigo_proveedor_id_seq');";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
				$indice=$resulta->fields[0];
				if($_POST['centroutil']==NULL)
				{
					$query = "INSERT INTO terceros_proveedores
							(codigo_proveedor_id,
							tipo_id_tercero,
							tercero_id,
							empresa_id,
							estado,
							cupo,
							tiempo_entrega,
							dias_gracia,
							dias_credito,
							descuento_por_contado)
							VALUES
							(".$indice.",
							'".$_POST['tipoTerceroId']."',
							'".$_POST['codigo']."',
							'".$_SESSION['invpro']['empresa']."',
							'".$_POST['estado']."',
							".$cupo.",
							".$entre.",
							".$graci.",
							".$credi.",
							".$descu.");";
				}
				else
				{
					$query = "INSERT INTO terceros_proveedores
							(codigo_proveedor_id,
							tipo_id_tercero,
							tercero_id,
							empresa_id_centro,
							centro_utilidad,
							estado,
							cupo,
							tiempo_entrega,
							dias_gracia,
							dias_credito,
							descuento_por_contado)
							VALUES
							(".$indice.",
							'".$_POST['tipoTerceroId']."',
							'".$_POST['codigo']."',
							'".$_SESSION['invpro']['empresa']."',
							'".$_POST['centroutil']."',
							'".$_POST['estado']."',
							".$cupo.",
							".$entre.",
							".$graci.",
							".$credi.",
							".$descu.");";
				}
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
				for($i=0;$i<$_POST['formpagoip'];$i++)
				{
					if($_POST['formpagoip'.$i]<>NULL)
					{
						$query = "INSERT INTO compras_proveedores_formas_pago
								(codigo_proveedor_id,
								formas_pago_id)
								VALUES
								(".$indice.",
								'".$_POST['formpagoip'.$i]."');";
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
			}
			else
			{
				$this->frmError["MensajeError"]="EL TERCERO YA SE ENCUENTRA<br>
				 COMO UN PROVEEDOR DE INVENTARIOS";
				$this->uno = 1;
			}
			$dbconn->CommitTrans();
			$this->PrincipalInvPro();
			return true;
		}
	}

	function CambiarEstadoInvPro()//Cambia el estado de los proveedores de inventarios
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['estado']==1)
		{
			$query = "UPDATE terceros_proveedores SET estado=0
					WHERE codigo_proveedor_id=".$_REQUEST['provelegip'].";";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
				return false;
			}
		}
		else if($_REQUEST['estado']==0)
		{
			$query = "UPDATE terceros_proveedores SET estado=1
					WHERE codigo_proveedor_id=".$_REQUEST['provelegip'].";";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
				return false;
			}
		}
		$this->PrincipalInvPro();
		return true;
	}

	function BuscarDatosProveedorInvPro($codigo,$empresa)//Función que busca los datos de un solo proveedor
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.empresa_id,
				A.tipo_id_tercero,
				A.tercero_id,
				A.empresa_id_centro,
				A.centro_utilidad,
				A.estado,
				A.dias_gracia,
				A.dias_credito,
				A.tiempo_entrega,
				A.descuento_por_contado,
				A.cupo,
				B.nombre_tercero,
				B.tipo_pais_id,
				B.tipo_dpto_id,
				B.tipo_mpio_id,
				B.direccion,
				B.telefono,
				B.fax,
				B.email,
				B.celular,
				B.busca_persona,
				C.descripcion,
				(
					SELECT F.evaluacion_id
					FROM terceros_proveedores AS G
					LEFT JOIN compras_proveedores_evaluaciones AS F ON
					(G.codigo_proveedor_id=F.codigo_proveedor_id
					AND G.codigo_proveedor_id=".$codigo.")
					WHERE
					(
						SELECT MAX(H.fecha_evaluacion)
						FROM terceros_proveedores AS I
						LEFT JOIN compras_proveedores_evaluaciones AS H ON
						(I.codigo_proveedor_id=H.codigo_proveedor_id
						AND I.codigo_proveedor_id=".$codigo.")
					)=F.fecha_evaluacion
				) AS evaluacion
				FROM terceros_proveedores AS A
				LEFT JOIN centros_utilidad AS C ON
				(C.centro_utilidad=A.centro_utilidad
				AND C.empresa_id='".$empresa."'),
				terceros AS B
				WHERE A.codigo_proveedor_id=".$codigo."
				AND (A.empresa_id='".$empresa."'
				OR empresa_id_centro='".$empresa."')
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

	function BuscarDatosEvaluacionInvPro($evaluacion)//Función que busca los datos de la evaluación del proveedor
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.fecha_evaluacion,
				A.puntaje_evaluacion
				FROM compras_proveedores_evaluaciones AS A
				WHERE A.evaluacion_id=".$evaluacion.";";
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

	function ModificarTerceroInvPro()//Llama a la forma de editar el proveedor del modulo Terceros
	{
		$_SESSION['INFORM']['RETORNO']['contenedor']='app';
		$_SESSION['INFORM']['RETORNO']['modulo']='InvProveedores';
		$_SESSION['INFORM']['RETORNO']['tipo']='user';
		$_SESSION['INFORM']['RETORNO']['metodo']='RetornaMTerceroInvPro';
		$_SESSION['tercer']['empresa']=$_SESSION['invpro']['empresa'];
		$_SESSION['tercer']['razonso']=$_SESSION['invpro']['razonso'];
		$_SESSION['tercer']['tipo_id_tercero']=$_POST['tipoTerceroId'];
		$_SESSION['tercer']['tercero_id']=$_POST['codigo'];
		$_SESSION['tercer']['nombre_tercero']=$_POST['nombre'];
		$this->ReturnMetodoExterno('app','Terceros','user','BusquedaTercer');//IngresaTercer
		return true;
	}

	function RetornaMTerceroInvPro()//Borra las variables de sesión externas y continua el proceso
	{
		$_POST['tipoTerceroId']=$_SESSION['tercer']['tipo_id_tercero'];
		$_POST['codigo']=$_SESSION['tercer']['tercero_id'];
		$_POST['nombre']=$_SESSION['tercer']['nombre_tercero'];
		if($_SESSION['INFORM']['RETORNO']['sw']==1)
		{
			$this->frmError["MensajeError"]="EL USUARIO CANCELÓ LA TRANSACCIÓN
			<br>NO SE GUARDÓ NI SE MODIFICÓ INFORMACIÓN SOBRE EL TERCERO";
			$this->uno=1;
			UNSET($_SESSION['tercer']);
			UNSET($_SESSION['INFORM']);
			$this->ModificarDatosProveedorInvPro();
			return true;
		}
		else if($_SESSION['INFORM']['RETORNO']['sw']==2)
		{
			$this->frmError["MensajeError"]="DATOS DEL TERCERO GUARDADOS CORRECTAMENTE ";
			$this->uno=1;
			UNSET($_SESSION['tercer']);
			UNSET($_SESSION['INFORM']);
			$this->ModificarDatosProveedorInvPro();
			return true;
		}
		else if($_SESSION['INFORM']['RETORNO']['sw']==3)
		{
			$this->frmError["MensajeError"]="DATOS DE LA EMPRESA INCOMPLETOS";
			$this->uno=1;
			UNSET($_SESSION['tercer']);
			UNSET($_SESSION['INFORM']);
			$this->ModificarDatosProveedorInvPro();
			return true;
		}
	}

	function ModificarProveedorInvPro()//Valida y modifica los datos del proveedor
	{
		if($_POST['tipoTerceroId']==NULL)
		{
			$this->frmError["tipoTerceroId"]=1;
		}
		if($_POST['codigo']==NULL)
		{
			$this->frmError["codigo"]=1;
		}
		if($_POST['nombre']==NULL)
		{
			$this->frmError["nombre"]=1;
		}
		if(empty($_POST['estadoM']))
		{
			$this->frmError["estadoM"]=1;
		}
		if(is_numeric($_POST['cupoprovipM'])==0)
		{//$this->frmError["cupoprovipM"]=1;
			$_POST['cupoprovipM']='';
		}
		else
		{
			$cupo=doubleval($_POST['cupoprovipM']);
			if($cupo >= 100000000000000)//14+1
			{//$this->frmError["cupoprovipM"]=1;
				$_POST['cupoprovipM']='';
			}
		}
		if(is_numeric($_POST['tiempoenipM'])==0)
		{
			$this->frmError["tiempoenipM"]=1;
			$_POST['tiempoenipM']='';
		}
		else
		{
			$entre=intval($_POST['tiempoenipM']);
			if($entre > 32000)
			{
				$this->frmError["tiempoenipM"]=1;
				$_POST['tiempoenipM']='';
			}
		}
		if(is_numeric($_POST['diasgracipM'])==0)
		{
			$this->frmError["diasgracipM"]=1;
			$_POST['diasgracipM']='';
		}
		else
		{
			$graci=intval($_POST['diasgracipM']);
			if($graci > 32000)
			{
				$this->frmError["diasgracipM"]=1;
				$_POST['diasgracipM']='';
			}
		}
		if(is_numeric($_POST['diascredipM'])==0)
		{
			$this->frmError["diascredipM"]=1;
			$_POST['diascredipM']='';
		}
		else
		{
			$credi=intval($_POST['diascredipM']);
			if($credi > 32000)
			{
				$this->frmError["diascredipM"]=1;
				$_POST['diascredipM']='';
			}
		}
		if(is_numeric($_POST['desporcoipM'])==0)
		{
			$this->frmError["desporcoipM"]=1;
			$_POST['desporcoipM']='';
		}
		else
		{
			$descu=doubleval($_POST['desporcoipM']);
			if($descu > 100)//999.9999
			{
				$this->frmError["desporcoipM"]=1;
				$_POST['desporcoipM']='';
			}
		}
		$this->frmError["formpagoipM"]=0;
		for($i=0;$i<$_POST['formpagoipM'];$i++)
		{
			if($_POST['formpagoipM'.$i]<>NULL)
			{
				$this->frmError["formpagoipM"]=0;
				break;
			}
			else
			{
				$this->frmError["formpagoipM"]=1;
			}
		}
		if($_POST['tipoTerceroId']==NULL||$_POST['codigo']==NULL||
		$_POST['nombre']==NULL||$_POST['tiempoenipM']==NULL||
		$_POST['diasgracipM']==NULL||$_POST['diascredipM']==NULL||
		$_POST['desporcoipM']==NULL||$this->frmError["formpagoipM"]==1||
		empty($_POST['estadoM']))//$_POST['cupoprovipM']==NULL||
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno = 1;
			$this->ModificarDatosProveedorInvPro();
			return true;
		}
		else
		{
			list($dbconn) = GetDBconn();
			if($_POST['estadoM']==2)
			{
				$_POST['estadoM']=0;
			}
			if($_POST['cupoprovipM']==NULL)
			{
				$cupo="NULL";
			}
			if($_POST['centroutilM']==NULL AND $_SESSION['invenp']['datos']['centro_utilidad']==NULL)
			{
				$query = "UPDATE terceros_proveedores SET
						estado='".$_POST['estadoM']."',
						cupo=".$cupo.",
						tiempo_entrega=".$entre.",
						dias_gracia=".$graci.",
						dias_credito=".$credi.",
						descuento_por_contado=".$descu."
						WHERE codigo_proveedor_id=".$_SESSION['invenp']['provineleg'].";";
			}
			else if($_POST['centroutilM']<>NULL AND $_SESSION['invenp']['datos']['centro_utilidad']==NULL)
			{
				$query = "UPDATE terceros_proveedores SET
						estado='".$_POST['estadoM']."',
						centro_utilidad='".$_POST['centroutilM']."',
						empresa_id_centro='".$_SESSION['invpro']['empresa']."',
						empresa_id=NULL,
						cupo=".$cupo.",
						tiempo_entrega=".$entre.",
						dias_gracia=".$graci.",
						dias_credito=".$credi.",
						descuento_por_contado=".$descu."
						WHERE codigo_proveedor_id=".$_SESSION['invenp']['provineleg'].";";
			}
			else if($_POST['centroutilM']==NULL AND $_SESSION['invenp']['datos']['centro_utilidad']<>NULL)
			{
				$query = "UPDATE terceros_proveedores SET
						estado='".$_POST['estadoM']."',
						centro_utilidad=NULL,
						empresa_id_centro=NULL,
						empresa_id='".$_SESSION['invpro']['empresa']."',
						cupo=".$cupo.",
						tiempo_entrega=".$entre.",
						dias_gracia=".$graci.",
						dias_credito=".$credi.",
						descuento_por_contado=".$descu."
						WHERE codigo_proveedor_id=".$_SESSION['invenp']['provineleg'].";";
			}
			else if($_POST['centroutilM']<>NULL AND $_SESSION['invenp']['datos']['centro_utilidad']<>NULL)
			{
				$query = "UPDATE terceros_proveedores SET
						estado='".$_POST['estadoM']."',
						centro_utilidad='".$_POST['centroutilM']."',
						empresa_id_centro='".$_SESSION['invpro']['empresa']."',
						cupo=".$cupo.",
						tiempo_entrega=".$entre.",
						dias_gracia=".$graci.",
						dias_credito=".$credi.",
						descuento_por_contado=".$descu."
						WHERE codigo_proveedor_id=".$_SESSION['invenp']['provineleg'].";";
			}
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$query = "DELETE FROM compras_proveedores_formas_pago
					WHERE codigo_proveedor_id=".$_SESSION['invenp']['provineleg'].";";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$dbconn->RollBackTrans();
			}
			for($i=0;$i<$_POST['formpagoipM'];$i++)
			{
				if($_POST['formpagoipM'.$i]<>NULL)
				{
					$query = "INSERT INTO compras_proveedores_formas_pago
							(codigo_proveedor_id,
							formas_pago_id)
							VALUES
							(".$_SESSION['invenp']['provineleg'].",
							'".$_POST['formpagoipM'.$i]."');";
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
			$_SESSION['invenp']['datos']=$this->BuscarDatosProveedorInvPro($_SESSION['invenp']['provineleg'],$_SESSION['invpro']['empresa']);
			if($_SESSION['invenp']['datos']['evaluacion']<>NULL)
			{
				$var=$this->BuscarDatosEvaluacionInvPro($_SESSION['invenp']['datos']['evaluacion']);
				$_SESSION['invenp']['datos']['fecha']=$var['fecha_evaluacion'];
				$_SESSION['invenp']['datos']['puntaje']=$var['puntaje_evaluacion'];
			}
			$this->MenuProveedorInvPro();
			return true;
		}
	}

	function BuscarCriteriosEvaluacionInvPro($empresa)//Busca los criterios y sub criterios activos para la evaluación
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.tipo_calificacion_id,
				A.descripcion AS des1,
				B.item_id,
				B.descripcion AS des2,
				B.puntaje
				FROM compras_proveedores_calificaciones_tipos AS A,
				compras_proveedores_calificaciones_items AS B
				WHERE A.empresa_id='".$empresa."'
				AND A.tipo_calificacion_id=B.tipo_calificacion_id
				AND A.estado='1'
				AND B.estado='1'
				ORDER BY A.tipo_calificacion_id, B.item_id;";
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

	function ValidarCriteriosEvaluacionInvPro()//Válida los criterios elegidos para calificar al proveedor
	{
		$ciclo=sizeof($_SESSION['invenp']['criterioev']);
		$sw=0;
		$puntaje=0;
		for($i=0;$i<$ciclo;)
		{
			$k=$i;
			while($_SESSION['invenp']['criterioev'][$i]['tipo_calificacion_id']==$_SESSION['invenp']['criterioev'][$k]['tipo_calificacion_id'])
			{
				if(!empty($_POST['evaluacion'.$i]) AND $_POST['evaluacion'.$i]==$_SESSION['invenp']['criterioev'][$k]['item_id'])
				{
					$_SESSION['invenp']['evaluacion'][$i]['tipo_calificacion_id']=$_SESSION['invenp']['criterioev'][$k]['tipo_calificacion_id'];
					$_SESSION['invenp']['evaluacion'][$i]['item_id']=$_SESSION['invenp']['criterioev'][$k]['item_id'];
					$puntaje=$puntaje+$_SESSION['invenp']['criterioev'][$k]['puntaje'];
				}
				$k++;
			}
			if(empty($_POST['evaluacion'.$i]))
			{
				$sw=1;
			}
			$i=$k;
		}
		if($_POST['fecha']==NULL)
		{
			$this->frmError["fecha"]=1;
		}
		else
		{
			$var=explode('/',$_POST['fecha']);
			$day=$var[0];
			$mon=$var[1];
			$yea=$var[2];
			if(checkdate($mon, $day, $yea)==0)
			{
				$_POST['fecha']='';
				$this->frmError["fecha"]=1;
				$this->frmError["MensajeError"]="FECHA CON FORMATO NO VÁLIDO";
			}
			else
			{
				$fech=date("Y-m-d");
				if($fech < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
				{
					$_POST['fecha']='';
					$this->frmError["fecha"]=1;
					$this->frmError["MensajeError"]="FECHA MAYOR A LA DEL DÍA DE HOY";
				}
				else
				{
					if($_SESSION['invenp']['datos']['fecha']<>NULL)
					{
						if($_SESSION['invenp']['datos']['fecha'] >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
						{
							$_POST['fecha']='';
							$this->frmError["fecha"]=1;
							$this->frmError["MensajeError"]="FECHA MENOR O IGUAL A LA DE LA ÚLTIMA EVALUACIÓN";
						}
						else
						{//CICLO
							$_SESSION['invenp']['evaluacion']['fecha']=$yea.'-'.$mon.'-'.$day;
						}
					}
					else
					{
						$_SESSION['invenp']['evaluacion']['fecha']=$yea.'-'.$mon.'-'.$day;
					}
				}
			}
		}
		$_SESSION['invenp']['evaluacion']['puntatotal']=$puntaje;
		if($sw==1||$_POST['fecha']==NULL)
		{
			if($this->frmError["MensajeError"]==NULL)
			{
				$this->frmError["MensajeError"]="FALTAN UNO O MÁS CRITERIOS POR CALIFICAR";
			}
			$this->uno = 1;
			$this->CriteriosEvaluacionInvPro();
			return true;
		}
		else
		{
			$this->MostrarCriteriosEvaluacionInvPro();
			return true;
		}
	}

	function GuardarCriteriosEvaluacionInvPro()//Guarda los criterios seleccionados una vez confirmados
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query = "SELECT NEXTVAL ('compras_proveedores_evaluaciones_evaluacion_id_seq');";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollBackTrans();
			return false;
		}
		$indice=$resulta->fields[0];
		$query = "INSERT INTO compras_proveedores_evaluaciones
				(evaluacion_id,
				fecha_evaluacion,
				codigo_proveedor_id,
				puntaje_evaluacion)
				VALUES
				(".$indice.",
				'".$_SESSION['invenp']['evaluacion']['fecha']."',
				".$_SESSION['invenp']['provineleg'].",
				".$_SESSION['invenp']['evaluacion']['puntatotal'].");";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollBackTrans();
			return false;
		}
		$ciclo=sizeof($_SESSION['invenp']['criterioev']);
		for($i=0;$i<$ciclo;)
		{
			$k=$i;
			while($_SESSION['invenp']['criterioev'][$i]['tipo_calificacion_id']==$_SESSION['invenp']['criterioev'][$k]['tipo_calificacion_id'])
			{
				if($_SESSION['invenp']['evaluacion'][$i]['tipo_calificacion_id']==$_SESSION['invenp']['criterioev'][$k]['tipo_calificacion_id']
				AND $_SESSION['invenp']['evaluacion'][$i]['item_id']==$_SESSION['invenp']['criterioev'][$k]['item_id'])
				{
					$query = "INSERT INTO compras_proveedores_calificaciones
							(tipo_calificacion_id,
							evaluacion_id,
							item_id)
							VALUES
							(".$_SESSION['invenp']['evaluacion'][$i]['tipo_calificacion_id'].",
							".$indice.",
							".$_SESSION['invenp']['evaluacion'][$i]['item_id'].");";
					$resulta = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollBackTrans();
						return false;
					}
				}
				$k++;
			}
			$i=$k;
		}
		$dbconn->CommitTrans();
		if($dbconn->ErrorNo() == 0)
		{
			$_SESSION['invenp']['datos']['fecha']=$_SESSION['invenp']['evaluacion']['fecha'];
			$_SESSION['invenp']['datos']['puntaje']=$_SESSION['invenp']['evaluacion']['puntatotal'];
			$_SESSION['invenp']['datos']['evaluacion']=$indice;
		}
		$this->MenuProveedorInvPro();
		return true;
	}

	function BuscarHistorialEvaluacionInvPro($codigo)//Busca el historial de evaluaciones de un proveedor
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.evaluacion_id,
				A.fecha_evaluacion,
				A.puntaje_evaluacion,
				B.tipo_calificacion_id,
				B.item_id,
				C.descripcion AS des2,
				C.puntaje,
				D.descripcion AS des1
				FROM compras_proveedores_evaluaciones AS A,
				compras_proveedores_calificaciones AS B,
				compras_proveedores_calificaciones_items AS C,
				compras_proveedores_calificaciones_tipos AS D
				WHERE A.codigo_proveedor_id=".$codigo."
				AND A.evaluacion_id=B.evaluacion_id
				AND B.tipo_calificacion_id=C.tipo_calificacion_id
				AND B.item_id=C.item_id
				AND C.tipo_calificacion_id=D.tipo_calificacion_id
				ORDER BY A.fecha_evaluacion DESC;";
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

	function BuscarModificarEvaluacionInvPro($evaluacion)//Función que busca los items de la última evaluación para ser modificados
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.tipo_calificacion_id,
				A.descripcion AS des1,
				B.item_id,
				B.descripcion AS des2,
				B.puntaje,
				C.item_id AS guardado
				FROM compras_proveedores_calificaciones_tipos AS A,
				compras_proveedores_calificaciones_items AS B
				LEFT JOIN compras_proveedores_calificaciones AS C ON
				(B.tipo_calificacion_id=C.tipo_calificacion_id
				AND B.item_id=C.item_id
				AND C.evaluacion_id=".$evaluacion.")
				WHERE A.tipo_calificacion_id=B.tipo_calificacion_id
				AND A.estado='1'
				AND B.estado='1'
				ORDER BY A.tipo_calificacion_id, B.item_id;";
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

	function ValidarModificarEvaluacionInvPro()//Válida y guarda las modificaciones a la última evaluación
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query = "DELETE FROM compras_proveedores_calificaciones
				WHERE evaluacion_id=".$_SESSION['invenp']['datos']['evaluacion'].";";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollBackTrans();
			return false;
		}
		$ciclo=sizeof($_SESSION['invenp']['modcriteva']);
		$sw=0;
		$puntaje=0;
		for($i=0;$i<$ciclo;)
		{
			$k=$i;
			while($_SESSION['invenp']['modcriteva'][$i]['tipo_calificacion_id']==$_SESSION['invenp']['modcriteva'][$k]['tipo_calificacion_id'])
			{
				if(!empty($_POST['evaluacionM'.$i]) AND $_POST['evaluacionM'.$i]==$_SESSION['invenp']['modcriteva'][$k]['item_id'])
				{
					$query = "INSERT INTO compras_proveedores_calificaciones
							(tipo_calificacion_id,
							evaluacion_id,
							item_id)
							VALUES
							(".$_SESSION['invenp']['modcriteva'][$k]['tipo_calificacion_id'].",
							".$_SESSION['invenp']['datos']['evaluacion'].",
							".$_SESSION['invenp']['modcriteva'][$k]['item_id'].");";
					$resulta = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollBackTrans();
						return false;
					}
					$puntaje=$puntaje+$_SESSION['invenp']['modcriteva'][$k]['puntaje'];
				}
				$k++;
			}
			if(empty($_POST['evaluacionM'.$i]))
			{
				$sw=1;
			}
			$i=$k;
		}
		$query = "UPDATE compras_proveedores_evaluaciones SET
				puntaje_evaluacion=".$puntaje."
				WHERE evaluacion_id=".$_SESSION['invenp']['datos']['evaluacion'].";";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollBackTrans();
			return false;
		}
		$_SESSION['invenp']['datos']['puntaje']=$puntaje;
		$dbconn->CommitTrans();
		if($sw==1)
		{
			$this->frmError["MensajeError"]="FALTAN UNO O MÁS CRITERIOS POR CALIFICAR";
			$this->uno = 1;
			$this->ModificarEvaluacionInvPro();
			return true;
		}
		else
		{
			$this->MenuProveedorInvPro();
			return true;
		}
	}

}//fin de la clase
?>
