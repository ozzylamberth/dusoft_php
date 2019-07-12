
<?php

/**
* Modulo de Proveedores (PHP).
*
* Modulo para el manejo de la contratación de los proveedores
* (determinar las características de los planes)
* Esta contratación es por prestación de servicios
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Proveedores_user.php
*
* Clase que establece los métodos de acceso y búsqueda de información con
* las opciones de los detalles de la contratación de los proveedores
**/

class app_Proveedores_user extends classModulo
{
	var $uno;//para los errores
	var $limit;
	var $conteo;

	function app_Proveedores_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->PrincipalProvee2();
		return true;
	}

	function UsuariosProvee()//Función de permisos
	{
		list($dbconn) = GetDBconn();
		$usuario=UserGetUID();
		$query = "SELECT A.empresa_id,
				B.razon_social AS descripcion1
				FROM userpermisos_proveedores AS A,
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
		$url[1]='Proveedores';
		$url[2]='user';
		$url[3]='ProvedorProvee';
		$url[4]='permisosprovee';
		$this->salida .=gui_theme_menu_acceso('PROVEEDORES DE SERVICIOS', $mtz, $var1, $url, ModuloGetURL('system','Menu'));
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

	function GuardarTerceroProvee()//Llama a la forma de editar el proveedor del modulo Terceros
	{//$regreso$_SESSION['INFORM']['RETORNO']['metodo']=$regreso;
		$_SESSION['INFORM']['RETORNO']['contenedor']='app';
		$_SESSION['INFORM']['RETORNO']['modulo']='Proveedores';
		$_SESSION['INFORM']['RETORNO']['tipo']='user';
		$_SESSION['INFORM']['RETORNO']['metodo']='RetornaTercerProvee';
		$_SESSION['tercer']['empresa']=$_SESSION['provee']['empresa'];
		$_SESSION['tercer']['razonso']=$_SESSION['provee']['razonso'];
		$_SESSION['tercer']['tipo_id_tercero']=$_POST['tipoTerceroId'];
		$_SESSION['tercer']['tercero_id']=$_POST['codigo'];
		$_SESSION['tercer']['nombre_tercero']=$_POST['nombre'];
		$this->ReturnMetodoExterno('app','Terceros','user','BusquedaTercer');//IngresaTercer
		return true;
	}

	function RetornaTercerProvee()//Borra las variables de sesión externas y continua el proceso
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
			$this->IngresaProvProvee();
			return true;
		}
		else if($_SESSION['INFORM']['RETORNO']['sw']==2)
		{
			$this->frmError["MensajeError"]="DATOS DEL TERCERO GUARDADOS CORRECTAMENTE ";
			$this->uno=1;
			UNSET($_SESSION['tercer']);
			UNSET($_SESSION['INFORM']);
			$this->IngresaTecerServicProvee(1);
			return true;
		}
		else if($_SESSION['INFORM']['RETORNO']['sw']==3)
		{
			$this->frmError["MensajeError"]="DATOS DE LA EMPRESA INCOMPLETOS";
			$this->uno=1;
			UNSET($_SESSION['tercer']);
			UNSET($_SESSION['INFORM']);
			$this->IngresaProvProvee();
			return true;
		}
	}

	function ModificarTerceroProvee()//Llama a la forma de editar el proveedor del modulo Terceros
	{
		$_SESSION['INFORM']['RETORNO']['contenedor']='app';
		$_SESSION['INFORM']['RETORNO']['modulo']='Proveedores';
		$_SESSION['INFORM']['RETORNO']['tipo']='user';
		$_SESSION['INFORM']['RETORNO']['metodo']='RetornaMTercerProvee';
		$_SESSION['tercer']['empresa']=$_SESSION['provee']['empresa'];
		$_SESSION['tercer']['razonso']=$_SESSION['provee']['razonso'];
		$_SESSION['tercer']['tipo_id_tercero']=$_POST['tipoTerceroId'];
		$_SESSION['tercer']['tercero_id']=$_POST['codigo'];
		$_SESSION['tercer']['nombre_tercero']=$_POST['nombre'];
		$this->ReturnMetodoExterno('app','Terceros','user','BusquedaTercer');//IngresaTercer
		return true;
	}

	function RetornaMTercerProvee()//Borra las variables de sesión externas y continua el proceso
	{
		$_POST['tipoTerceroId']=$_SESSION['tercer']['tipo_id_tercero'];
		$_POST['codigo']=$_SESSION['tercer']['tercero_id'];
		$_POST['nombre']=$_SESSION['tercer']['nombre_tercero'];
		if($_SESSION['INFORM']['RETORNO']['sw']==1)
		{
			$this->frmError["MensajeError"]="EL USUARIO CANCELÓ LA TRANSACCIÓN
			<br>NO SE GUARDÓ NI SE MODIFICÓ INFORMACIÓN SOBRE EL TERCERO, NI SOBRE EL PLAN";
			$this->uno=1;
			$_SESSION['propla']['cancelo']=1;
			UNSET($_SESSION['tercer']);
			UNSET($_SESSION['INFORM']);
			$this->ModificarProvProvee();
			return true;
		}
		else if($_SESSION['INFORM']['RETORNO']['sw']==2)
		{
			$this->frmError["MensajeError"]="DATOS DEL TERCERO GUARDADOS CORRECTAMENTE ";
			$this->uno=1;
			UNSET($_SESSION['tercer']);
			UNSET($_SESSION['INFORM']);
			$this->IngresaTecerServicProvee(2);
			return true;
		}
		else if($_SESSION['INFORM']['RETORNO']['sw']==3)
		{
			$this->frmError["MensajeError"]="DATOS DE LA EMPRESA INCOMPLETOS";
			$this->uno=1;
			$_SESSION['propla']['cancelo']=1;
			UNSET($_SESSION['tercer']);
			UNSET($_SESSION['INFORM']);
			$this->ModificarProvProvee();
			return true;
		}
	}

	function BuscarServiciosProvee()//Función que busca los servicios disponibles
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT servicio,
				descripcion
				FROM servicios
				WHERE sw_asistencial='1'
				ORDER BY servicio;";
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

	function BuscarNivelesAteProvee()//Función que busca los niveles de atención disponibles
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT nivel,
				descripcion,
				descripcion_corta
				FROM niveles_atencion
				ORDER BY nivel;";
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

	function BuscarTarifariosProvee()//Busca los tarifarios
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT tarifario_id,
				descripcion
				FROM tarifarios
				WHERE tarifario_id<>'SYS'
				ORDER BY descripcion;";
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

	function BuscarProvedorPlanes($empresa)//Busca los planes de la empresa seleccionada
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigoctra'])
		{
			$codigo=$_REQUEST['codigoctra'];
			$busqueda="AND A.num_contrato LIKE '%$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['ctradescri'])
		{
			$codigo=STRTOUPPER($_REQUEST['ctradescri']);
			$busqueda2="AND UPPER(A.plan_descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query ="SELECT count(*) FROM
					(
						SELECT A.plan_proveedor_id,
						A.plan_descripcion,
						A.estado,
						B.nombre_tercero,
						A.num_contrato
						FROM planes_proveedores AS A,
						terceros AS B
						WHERE empresa_id='".$empresa."'
						AND A.tipo_id_tercero=B.tipo_id_tercero
						AND A.tercero_id=B.tercero_id
						$busqueda
						$busqueda2
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
					SELECT A.plan_proveedor_id,
					A.plan_descripcion,
					A.estado,
					B.nombre_tercero,
					A.num_contrato
					FROM planes_proveedores AS A,
					terceros AS B
					WHERE empresa_id='".$empresa."'
					AND A.tipo_id_tercero=B.tipo_id_tercero
					AND A.tercero_id=B.tercero_id
					$busqueda
					$busqueda2
					ORDER BY A.estado DESC, A.num_contrato ASC
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

	function BuscarEncargadosProvee($empresa)//Busca los usuarios del sistema
	{
		list($dbconn) = GetDBconn();//estado del usuario
		$query ="SELECT A.usuario_id,
				A.nombre
				FROM system_usuarios AS A,
				system_usuarios_empresas AS B,
				system_usuarios_funciones AS C
				WHERE B.empresa_id='".$empresa."'
				AND B.usuario_id=A.usuario_id
				AND B.usuario_id=C.usuario_id
				AND C.sw_tipo_funcion<>2
				AND A.sw_admin='0'
				AND A.activo='1'
				ORDER BY nombre;";
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

	function GuardarProvPlanProvee()//Válida y guarda los datos del contrato del proveedor
	{
		$this->uno=0;
		if(empty($_POST['descrictra']))
		{
			$this->frmError["descrictra"]=1;
		}
		if(empty($_POST['nombre']))
		{
			$this->frmError["nombre"]=1;
		}
		if(empty($_POST['codigo']))
		{
			$this->frmError["codigo"]=1;
		}
		if(empty($_POST['tipoTerceroId']))
		{
			$this->frmError["tipoTerceroId"]=1;
		}
		if($_POST['contactoctra']==NULL)
		{
			$this->frmError["contactoctra"]=1;
		}
		if($_POST['numeroctra']==NULL)
		{
			$this->frmError["numeroctra"]=1;
		}
		if(is_numeric($_POST['valorctra'])==0)
		{
			$this->frmError["valorctra"]=1;
			$_POST['valorctra']='';
		}
		else
		{
			$valorcontr=doubleval($_POST['valorctra']);
			if($valorcontr >= 100000000000000)
			{
				$this->frmError["valorctra"]=1;
				$_POST['valorctra']='';
			}
		}
		if(empty($_POST['feinictra']))
		{
			$this->frmError["feinictra"]=1;
		}
		else
		{//La fecha no va validada con la fecha del sistema
			$fecdes=explode('/',$_POST['feinictra']);
			$day=$fecdes[0];
			$mon=$fecdes[1];
			$yea=$fecdes[2];
			if(checkdate($mon, $day, $yea)==0)
			{
				$_POST['feinictra']='';
				$this->frmError["feinictra"]=1;
			}
			else
			{
				$fecdes=$yea.'-'.$mon.'-'.$day;
			}
		}
		if(empty($_POST['fefinctra']))
		{
			$this->frmError["fefinctra"]=1;
		}
		else
		{
			$fechas=explode('/',$_POST['fefinctra']);
			$day=$fechas[0];
			$mon=$fechas[1];
			$yea=$fechas[2];
			if(checkdate($mon, $day, $yea)==0)
			{
				$_POST['fefinctra']='';
				$this->frmError["fefinctra"]=1;
			}
			else
			{
				$fech=date ("Y-m-d");
				if($fech >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
				{
					$_POST['fefinctra']='';
					$this->frmError["fefinctra"]=1;
				}
				else if(!empty($_POST['feinictra']))
				{
					if($fecdes >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
					{
						$_POST['fefinctra']='';
						$this->frmError["fefinctra"]=1;
					}
					else
					{
						$fechas=$yea.'-'.$mon.'-'.$day;
					}
				}
			}
		}
		if($_POST['telefono1']==NULL)
		{
			$this->frmError["telefono1"]=1;
		}
		if($_POST['usuariosctra']==NULL)
		{
			$this->frmError["usuariosctra"]=1;
		}
		if(empty($_POST['descrictra'])||empty($_POST['nombre'])||
		empty($_POST['codigo'])||empty($_POST['tipoTerceroId'])||
		empty($_POST['fefinctra'])||empty($_POST['feinictra'])||
		empty($_POST['valorctra'])||$_POST['telefono1']==NULL||
		$_POST['numeroctra']==NULL||$_POST['contactoctra']==NULL||
		$_POST['usuariosctra']==NULL)
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno=1;
			$this->IngresaProvProvee();
			return true;
		}
		else
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query ="SELECT NEXTVAL ('planes_proveedores_plan_proveedor_id_seq');";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			$indice=$resulta->fields[0];
			$usuario=UserGetUID();
			$query = "INSERT INTO planes_proveedores
					(plan_proveedor_id,
					empresa_id,
					tipo_id_tercero,
					tercero_id,
					plan_descripcion,
					num_contrato,
					fecha_inicio,
					fecha_final,
					monto_contrato,
					saldo_contrato,
					observacion,
					fecha_registro,
					usuario_id,
					estado,
					contacto,
					lineas_contacto)
					VALUES
					(".$indice.",
					'".$_SESSION['provee']['empresa']."',
					'".$_POST['tipoTerceroId']."',
					'".$_POST['codigo']."',
					'".$_POST['descrictra']."',
					'".$_POST['numeroctra']."',
					'".$fecdes."',
					'".$fechas."',
					".$valorcontr.",
					".$valorcontr.",
					'".$_POST['observacion']."',
					'".date("Y-m-d H:i:s")."',
					".$usuario.", '0',
					'".$_POST['contactoctra']."',
					'".$_POST['telefono1']."');";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			$query ="INSERT INTO planes_encargados_proveedores
					(plan_proveedor_id,
					usuario_id)
					VALUES
					(".$indice.",
					".$_POST['usuariosctra'].");";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			$dbconn->CommitTrans();
			$this->ProvedorProvee();
			return true;
		}
	}

	function MostrarProvedorPlanes($plan)//Muestra y modifica la información del plan escogido
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.tercero_id,
				A.tipo_id_tercero,
				A.plan_descripcion,
				A.num_contrato,
				A.fecha_inicio,
				A.fecha_final,
				A.monto_contrato,
				A.saldo_contrato,
				A.observacion,
				A.estado,
				A.contacto,
				A.lineas_contacto,
				B.usuario_id,
				C.nombre
				FROM planes_proveedores AS A,
				planes_encargados_proveedores AS B,
				system_usuarios AS C
				WHERE A.plan_proveedor_id=".$plan."
				AND A.plan_proveedor_id=B.plan_proveedor_id
				AND B.usuario_id=C.usuario_id;";
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

	function ModificarProvPlanProvee()//Válida y modifica los datos del contrato del proveedor
	{
		$this->uno=0;
		if(empty($_POST['descrictraM']))
		{
			$this->frmError["descrictraM"]=1;
		}
		if(empty($_POST['nombre']))
		{
			$this->frmError["nombre"]=1;
		}
		if(empty($_POST['codigo']))
		{
			$this->frmError["codigo"]=1;
		}
		if(empty($_POST['tipoTerceroId']))
		{
			$this->frmError["tipoTerceroId"]=1;
		}
		if($_POST['contactoctraM']==NULL)
		{
			$this->frmError["contactoctraM"]=1;
		}
		if($_POST['usuariosctraM']==NULL)
		{
			$this->frmError["usuariosctraM"]=1;
		}
		if($_POST['numeroctraM']==NULL)
		{
			$this->frmError["numeroctraM"]=1;
		}
		if(is_numeric($_POST['valorctraM'])==0)
		{
			$this->frmError["valorctraM"]=1;
			$_POST['valorctraM']='';
		}
		else
		{
			$valorcontr=doubleval($_POST['valorctraM']);
			if($valorcontr >= 100000000000000)
			{
				$this->frmError["valorctraM"]=1;
				$_POST['valorctraM']='';
			}
		}
		if(empty($_POST['feinictraM']))
		{
			$this->frmError["feinictraM"]=1;
		}
		else
		{//La fecha no va validada con la fecha del sistema
			$fecdes=explode('/',$_POST['feinictraM']);
			$day=$fecdes[0];
			$mon=$fecdes[1];
			$yea=$fecdes[2];
			if(checkdate($mon, $day, $yea)==0)
			{
				$_POST['feinictraM']='';
				$this->frmError["feinictraM"]=1;
			}
			else
			{
				$fecdes=$yea.'-'.$mon.'-'.$day;
			}
		}
		if(empty($_POST['fefinctraM']))
		{
			$this->frmError["fefinctraM"]=1;
		}
		else
		{
			$fechas=explode('/',$_POST['fefinctraM']);
			$day=$fechas[0];
			$mon=$fechas[1];
			$yea=$fechas[2];
			if(checkdate($mon, $day, $yea)==0)
			{
				$_POST['fefinctraM']='';
				$this->frmError["fefinctraM"]=1;
			}
			else
			{
				$fech=date ("Y-m-d");
				if($fech >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
				{
					$_POST['fefinctraM']='';
					$this->frmError["fefinctraM"]=1;
				}
				else if(!empty($_POST['feinictraM']))
				{
					if($fecdes >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
					{
						$_POST['fefinctraM']='';
						$this->frmError["fefinctraM"]=1;
					}
					else
					{
						$fechas=$yea.'-'.$mon.'-'.$day;
					}
				}
			}
		}
		if($_POST['telefono1M']==NULL)
		{
			$this->frmError["telefono1M"]=1;
		}
		if(empty($_POST['descrictraM'])||empty($_POST['nombre'])||
		empty($_POST['codigo'])||empty($_POST['tipoTerceroId'])||
		empty($_POST['fefinctraM'])||empty($_POST['feinictraM'])||
		empty($_POST['valorctraM'])||$_POST['telefono1M']==NULL||
		$_POST['numeroctraM']==NULL||$_POST['contactoctraM']==NULL||
		$_POST['usuariosctraM']==NULL)
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno=1;
			$this->ModificarProvProvee();
			return true;
		}
		else
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query = "UPDATE planes_proveedores SET
					tipo_id_tercero='".$_POST['tipoTerceroId']."',
					tercero_id='".$_POST['codigo']."',
					plan_descripcion='".$_POST['descrictraM']."',
					num_contrato='".$_POST['numeroctraM']."',
					fecha_inicio='".$fecdes."',
					fecha_final='".$fechas."',
					monto_contrato=".$valorcontr.",
					observacion='".$_POST['observacionM']."',
					contacto='".$_POST['contactoctraM']."',
					lineas_contacto='".$_POST['telefono1M']."'
					WHERE plan_proveedor_id=".$_SESSION['propla']['planelpr'].";";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$dbconn->RollBackTrans();
				$this->frmError["MensajeError"]="ESTE TERCERO NO ES UN PROVEEDOR DE SERVICIOS DE SALUD";
				$this->uno=1;
				$this->ModificarProvProvee();
				return true;
			}
			$query ="UPDATE planes_encargados_proveedores SET
					usuario_id=".$_POST['usuariosctraM']."
					WHERE plan_proveedor_id=".$_SESSION['propla']['planelpr'].";";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			$dbconn->CommitTrans();
			$_SESSION['propla']['nombelpr']=$_POST['nombre'];
			$_SESSION['propla']['numeelpr']=$_POST['numeroctraM'];
			$_SESSION['propla']['descelpr']=$_POST['descrictraM'];
			$this->ProvedorPlanProvee();
			return true;
		}
	}

	function CambiarEstadoProvProvee()//Cambia el estado del contrato del proveedor
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['estado']==1)
		{
			$query = "UPDATE planes_proveedores SET estado=0
					WHERE plan_proveedor_id=".$_REQUEST['planelegc'].";";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
				return false;
			}
		}
		else
		{
			$query ="SELECT COUNT(plan_proveedor_id)
					FROM plan_tarifario_proveedores
					WHERE plan_proveedor_id=".$_REQUEST['planelegc'].";";
			$resulta2 = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$resulta2->fields[0]=0;
			}
			if($resulta2->fields[0]>0)
			{
				$query = "UPDATE planes_proveedores SET estado=1
						WHERE plan_proveedor_id=".$_REQUEST['planelegc'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
					return false;
				}
			}
		}
		$this->ProvedorProvee();
		return true;
	}

	function BuscarGruposProvProvee($plan)//Busca los grupos y subgrupos disponibles, así como los ya contratados
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT DISTINCT C.tarifario_id,
				E.descripcion,
				A.grupo_tarifario_id,
				A.grupo_tarifario_descripcion,
				B.subgrupo_tarifario_id,
				B.subgrupo_tarifario_descripcion,
				D.porcentaje
				FROM tarifarios AS E,
				grupos_tarifarios AS A,
				subgrupos_tarifarios AS B,
				tarifarios_detalle AS C
				LEFT JOIN plan_tarifario_proveedores AS D ON
				(
					D.plan_proveedor_id=".$plan."
					AND C.tarifario_id=D.tarifario_id
					AND C.grupo_tarifario_id=D.grupo_tarifario_id
					AND C.subgrupo_tarifario_id=D.subgrupo_tarifario_id
				)
				WHERE A.grupo_tarifario_id=B.grupo_tarifario_id
				AND C.grupo_tarifario_id=B.grupo_tarifario_id
				AND C.subgrupo_tarifario_id=B.subgrupo_tarifario_id
				AND C.tarifario_id<>'SYS'
				AND C.grupo_tarifario_id<>'00'
				AND C.tarifario_id=E.tarifario_id
				ORDER BY A.grupo_tarifario_id,
				B.subgrupo_tarifario_id;";
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

	function BuscarEmpresasProvee()//Busca las empresas existentes
	{
		list($dbconn) = GetDBconn();
		$usuario=UserGetUID();
		$query ="SELECT empresa_id,
				razon_social
				FROM empresas;";
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

	function ValidarTarifarioProvProvee()//Valida el plan tarifario del contrato del proveedor
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['propl1']['grutaprovc']);
		for($i=0;$i<$ciclo;)
		{
			$k=$i;
			while($_SESSION['propl1']['grutaprovc'][$i]['grupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$k]['grupo_tarifario_id'])
			{
				$l=$k;
				$a=$l;
				while($_SESSION['propl1']['grutaprovc'][$k]['grupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$l]['grupo_tarifario_id']
				AND $_SESSION['propl1']['grutaprovc'][$k]['subgrupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$l]['subgrupo_tarifario_id'])
				{
					if($_SESSION['propl1']['grutaprovc'][$l]['porcentaje']<>NULL)
					{
						$a=$l;
					}
					$l++;
				}
				$g1=0;
				if(is_numeric($_POST['porceprovc'.$a])==1)
				{
					$por1=doubleval($_POST['porceprovc'.$a]);
					if($por1 <= 999.9999 AND $por1 >= -999.9999)//$por1 < 999.9999
					{
						$g1=1;
					}
				}
				else
				{
					$por1=0.00;
				}
				if($_POST['tarifprovc'.$a]<>NULL AND $g1==1)
				{
					if($_SESSION['propl1']['grutaprovc'][$a]['porcentaje']==NULL)
					{
						$query = "INSERT INTO plan_tarifario_proveedores
								(plan_proveedor_id,
								grupo_tarifario_id,
								subgrupo_tarifario_id,
								tarifario_id,
								porcentaje)
								VALUES
								(".$_SESSION['propla']['planelpr'].",
								'".$_SESSION['propl1']['grutaprovc'][$a]['grupo_tarifario_id']."',
								'".$_SESSION['propl1']['grutaprovc'][$a]['subgrupo_tarifario_id']."',
								'".$_POST['tarifprovc'.$a]."',
								".$por1.");";
						$resulta = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollBackTrans();
							return false;
						}
					}
					else if($_POST['tarifprovc'.$a]<>NULL
					AND ($_POST['tarifprovc'.$a]<>$_SESSION['propl1']['grutaprovc'][$a]['tarifario_id']
					OR $_SESSION['propl1']['grutaprovc'][$a]['porcentaje']<>$por1))
					{
						$query = "UPDATE plan_tarifario_proveedores SET
								tarifario_id='".$_POST['tarifprovc'.$a]."',
								porcentaje=".$por1."
								WHERE plan_proveedor_id=".$_SESSION['propla']['planelpr']."
								AND grupo_tarifario_id='".$_SESSION['propl1']['grutaprovc'][$a]['grupo_tarifario_id']."'
								AND subgrupo_tarifario_id='".$_SESSION['propl1']['grutaprovc'][$a]['subgrupo_tarifario_id']."';";
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
				else
				{
					if($_POST['tarifprovc'.$a]==NULL AND $_SESSION['propl1']['grutaprovc'][$a]['porcentaje']<>NULL)
					{
						$query = "DELETE FROM plan_tarifario_proveedores
								WHERE plan_proveedor_id=".$_SESSION['propla']['planelpr']."
								AND grupo_tarifario_id='".$_SESSION['propl1']['grutaprovc'][$a]['grupo_tarifario_id']."'
								AND subgrupo_tarifario_id='".$_SESSION['propl1']['grutaprovc'][$a]['subgrupo_tarifario_id']."';";
						$dbconn->Execute($query);
					}
				}
				$_POST['tarifprovc'.$a]='';
				$_POST['porceprovc'.$a]='';
				$k=$l;
			}
			$i=$k;
		}
		$dbconn->CommitTrans();
		$query ="SELECT count(plan_proveedor_id) FROM plan_tarifario_proveedores
				WHERE plan_proveedor_id=".$_SESSION['propla']['planelpr'].";";
		$resulta2 = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$resulta2->fields[0]=0;
		}/*SI SE INCLUYE EL GRUPO DE SISTEMA, CAMBIAR A MÁS 1*/
		if($resulta2->fields[0]<1)
		{
			$query ="UPDATE planes_proveedores SET estado=0
					WHERE plan_proveedor_id=".$_SESSION['propla']['planelpr'].";";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
				$this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
				return false;
			}
		}
		$this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
		$this->uno=1;
		$this->TarifarioProvProvee();
		return true;
	}

	function ValidarCopiarTarifarioProvProvee()//
	{
		if($_POST['tarifario2']<>NULL AND $_POST['tarifario2']<>$_SESSION['propla']['planelpr'])
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			if($_POST['copiartari']==1)
			{
				$query ="DELETE FROM plan_tarifario_proveedores
						WHERE plan_proveedor_id=".$_SESSION['propla']['planelpr']."
						AND grupo_tarifario_id<>'00';";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS";
					$dbconn->RollBackTrans();
				}
				$query ="INSERT INTO plan_tarifario_proveedores
						(
							plan_proveedor_id,
							grupo_tarifario_id,
							subgrupo_tarifario_id,
							tarifario_id,
							porcentaje
						)
						SELECT
						".$_SESSION['propla']['planelpr'].",
						grupo_tarifario_id,
						subgrupo_tarifario_id,
						tarifario_id,
						porcentaje
						FROM plan_tarifario_proveedores
						WHERE plan_proveedor_id=".$_POST['tarifario2']."
						AND tarifario_id<>'SYS'
						AND grupo_tarifario_id<>'00';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS";
					$dbconn->RollBackTrans();
				}
			}
			if($_POST['copiartariex']==1 AND $_POST['copiartari']==1)
			{
				$query ="INSERT INTO excepciones_proveedores
						(
							plan_proveedor_id,
							tarifario_id,
							cargo,
							porcentaje
						)
						SELECT
						".$_SESSION['propla']['planelpr'].",
						tarifario_id,
						cargo,
						porcentaje
						FROM excepciones_proveedores
						WHERE plan_proveedor_id=".$_POST['tarifario2'].";";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL COPIAR LOS DATOS";
					$dbconn->RollBackTrans();
				}
			}
			$dbconn->CommitTrans();
		}
		$_POST['copiartari']='';
		$_POST['copiartariex']='';
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
		}
		$this->uno=1;
		$this->TarifarioProvProvee();
		return true;
	}

	function BuscarConsulCargosTarifarioProvee($plan,$grd,$sud)//Busca los detalles del tarifario y las excepciones
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigoctra'])
		{
			$codigo=$_REQUEST['codigoctra'];
			$busqueda="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descrictra'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrictra']);
			$busqueda2="AND UPPER(A.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['tarifactra'])
		{
			$codigo=STRTOUPPER($_REQUEST['tarifactra']);
			$busqueda3="AND A.tarifario_id='$codigo'";
		}
		else
		{
			$busqueda3='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query ="SELECT count(*) FROM (
					(
						SELECT A.cargo,
						A.descripcion,
						B.tarifario_id,
						C.descripcion AS destarifario
						FROM tarifarios_detalle AS A
						LEFT JOIN plan_tarifario_proveedores AS B ON
						(B.plan_proveedor_id=$plan
						AND A.grupo_tarifario_id=B.grupo_tarifario_id
						AND A.subgrupo_tarifario_id=B.subgrupo_tarifario_id
						AND A.tarifario_id=B.tarifario_id),
						tarifarios AS C
						WHERE A.grupo_tarifario_id='$grd'
						AND A.subgrupo_tarifario_id='$sud'
						AND A.tarifario_id=C.tarifario_id
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
				B.tarifario_id,
				C.descripcion AS destarifario
				FROM tarifarios_detalle AS A
				LEFT JOIN plan_tarifario_proveedores AS B ON
				(B.plan_proveedor_id=$plan
				AND A.grupo_tarifario_id=B.grupo_tarifario_id
				AND A.subgrupo_tarifario_id=B.subgrupo_tarifario_id
				AND A.tarifario_id=B.tarifario_id),
				tarifarios AS C
				WHERE A.grupo_tarifario_id='$grd'
				AND A.subgrupo_tarifario_id='$sud'
				AND A.tarifario_id=C.tarifario_id
				$busqueda
				$busqueda2
				$busqueda3
				ORDER BY A.tarifario_id, A.cargo
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

	function BuscarCarTarProvProvee($plan,$grd,$sud)//Busca los detalles del tarifario y las excepciones
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigoctra'])
		{
			$codigo=$_REQUEST['codigoctra'];
			$busqueda="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda='';
		}
		if($_REQUEST['descrictra'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrictra']);
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
					A.precio,
					A.nivel,
					B.porcentaje,
					'0' AS sw_no_contratado,
					0 AS excepcion,
					A.sw_uvrs
					FROM tarifarios_detalle AS A,
					plan_tarifario_proveedores AS B
					WHERE B.plan_proveedor_id = $plan
					AND B.grupo_tarifario_id = '$grd'
					AND B.subgrupo_tarifario_id	= '$sud'
					AND B.grupo_tarifario_id = A.grupo_tarifario_id
					AND B.subgrupo_tarifario_id	= A.subgrupo_tarifario_id
					AND B.tarifario_id = A.tarifario_id
					AND excepciones_prov
					(B.plan_proveedor_id, B.tarifario_id, A.cargo) = 0
					$busqueda
					$busqueda2
					)
					UNION
					(
					SELECT A.cargo,
					A.descripcion,
					A.precio,
					A.nivel,
					B.porcentaje,
					B.sw_no_contratado,
					1 AS excepcion,
					A.sw_uvrs
					FROM tarifarios_detalle AS A,
					excepciones_proveedores AS B
					WHERE B.plan_proveedor_id = $plan
					AND A.grupo_tarifario_id = '$grd'
					AND A.subgrupo_tarifario_id	= '$sud'
					AND B.tarifario_id = A.tarifario_id
					AND B.cargo = A.cargo
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
				A.precio,
				A.nivel,
				B.porcentaje,
				'0' AS sw_no_contratado,
				0 AS excepcion,
				A.sw_uvrs
				FROM tarifarios_detalle AS A,
				plan_tarifario_proveedores AS B
				WHERE B.plan_proveedor_id = $plan
				AND B.grupo_tarifario_id = '$grd'
				AND B.subgrupo_tarifario_id	= '$sud'
				AND B.grupo_tarifario_id = A.grupo_tarifario_id
				AND B.subgrupo_tarifario_id	= A.subgrupo_tarifario_id
				AND B.tarifario_id = A.tarifario_id
				AND excepciones_prov
				(B.plan_proveedor_id, B.tarifario_id, A.cargo) = 0
				$busqueda
				$busqueda2
				ORDER BY A.cargo
				)
				UNION
				(
				SELECT A.cargo,
				A.descripcion,
				A.precio,
				A.nivel,
				B.porcentaje,
				B.sw_no_contratado,
				1 AS excepcion,
				A.sw_uvrs
				FROM tarifarios_detalle AS A,
				excepciones_proveedores AS B
				WHERE B.plan_proveedor_id = $plan
				AND A.grupo_tarifario_id = '$grd'
				AND A.subgrupo_tarifario_id	= '$sud'
				AND B.tarifario_id = A.tarifario_id
				AND B.cargo = A.cargo
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
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarExceTariProvProvee()//Valida las excepciones al plan tarifario
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$ciclo=sizeof($_SESSION['propl1']['cargotaric']);
		for($i=0;($i<$ciclo);$i++)
		{
			$g1=0;
			if($_SESSION['propl1']['cargotaric'][$i]['excepcion']==1)
			{
				$query = "DELETE FROM excepciones_proveedores
						WHERE plan_proveedor_id=".$_SESSION['propla']['planelpr']."
						AND tarifario_id='".$_SESSION['propl1']['dattarprov']['tarifario_id']."'
						AND cargo='".$_SESSION['propl1']['cargotaric'][$i]['cargo']."';";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
			}
			if(is_numeric($_POST['porexctra'.$i])==1)
			{
				$por1=doubleval($_POST['porexctra'.$i]);
				if($por1 <= 999.9999 AND $por1 >= -999.9999)//$por1 < 999.9999
				{
					$g1=1;
				}
			}
			else
			{
				$por1=0.00;
			}
			if(!$_POST['porexctra'.$i]==NULL AND $g1==1 AND $_POST['contratado'.$i]==NULL)
			{
				if($_SESSION['propl1']['dattarprov']['porcentaje']<>$por1)
				{
					$query = "INSERT INTO excepciones_proveedores
							(plan_proveedor_id,
							tarifario_id,
							cargo,
							porcentaje)
							VALUES
							(".$_SESSION['propla']['planelpr'].",
							'".$_SESSION['propl1']['dattarprov']['tarifario_id']."',
							'".$_SESSION['propl1']['cargotaric'][$i]['cargo']."',
							".$por1.");";
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
			else if($_POST['contratado'.$i]<>NULL)
			{
				$query = "INSERT INTO excepciones_proveedores
						(plan_proveedor_id,
						tarifario_id,
						cargo,
						porcentaje,
						sw_no_contratado)
						VALUES
						(".$_SESSION['propla']['planelpr'].",
						'".$_SESSION['propl1']['dattarprov']['tarifario_id']."',
						'".$_SESSION['propl1']['cargotaric'][$i]['cargo']."',
						0, 1);";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
			}
			$_POST['porexctra'.$i]='';
			$_POST['contratado'.$i]='';
		}
		$dbconn->CommitTrans();
		$this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
		$this->uno=1;
		$this->TariExceProvProvee();
		return true;
	}

	function BuscarServicioProvProvee($plan)//Busca los servicios contratados
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT servicio,
				nivel
				FROM planes_proveedores_servicios
				WHERE plan_proveedor_id=".$plan."
				ORDER BY servicio, nivel;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[$resulta->fields[0]][$resulta->fields[1]]=1;
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarServiciosProvProvee()//Guarda los servicios, según los niveles que contrato
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query = "DELETE FROM planes_proveedores_servicios
				WHERE plan_proveedor_id=".$_SESSION['propla']['planelpr'].";";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollBackTrans();
			return false;
		}
		$ciclo=sizeof($_SESSION['propl1']['serviprov']);
		$ciclo1=sizeof($_SESSION['propl1']['nivelprov']);
		for($l=0;$l<$ciclo;$l++)
		{
			for($m=0;$m<$ciclo1;$m++)
			{
				if($_POST['nivelprovctra'.$l.$m]<>NULL)
				{
					$query = "INSERT INTO planes_proveedores_servicios
							(plan_proveedor_id,
							servicio,
							nivel)
							VALUES
							(".$_SESSION['propla']['planelpr'].",
							'".$_POST['nivelprovctra'.$l.$m]."',
							'".($m+1)."');";
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
		$dbconn->CommitTrans();
		$this->frmError["MensajeError"]="DATOS GUARDADOS Y/O ELIMINADOS CORRECTAMENTE";
		$this->uno=1;
		$this->ServiciosProvProvee();
		return true;
	}

	function ValidarIngresaTercerServicProvee()//Guardar proveedor como prestador de servicios
	{
		if(empty($_POST['estado']))
		{
			$this->frmError["estado"]=1;
		}
		if(empty($_POST['estado']))
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno = 1;
			if($_REQUEST['destino']==1)
			{
				$this->IngresaTecerServicProvee(1);
			}
			else if($_REQUEST['destino']==2)
			{
				$this->IngresaTecerServicProvee(2);
			}
		}
		else
		{
			list($dbconn) = GetDBconn();
			if($_POST['estado']==2)
			{
				$_POST['estado']=0;
			}
			$query = "INSERT INTO terceros_proveedores_servicios_salud
					(empresa_id,
					tipo_id_tercero,
					tercero_id,
					estado)
					VALUES
					('".$_SESSION['provee']['empresa']."',
					'".$_POST['tipoTerceroId']."',
					'".$_POST['codigo']."',
					'".$_POST['estado']."');";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$query = "UPDATE terceros_proveedores_servicios_salud SET
						estado='".$_POST['estado']."'
						WHERE empresa_id='".$_SESSION['provee']['empresa']."'
						AND tipo_id_tercero='".$_POST['tipoTerceroId']."'
						AND tercero_id='".$_POST['codigo']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
			if($_REQUEST['destino']==1)
			{
				$this->IngresaProvProvee();
			}
			else if($_REQUEST['destino']==2)
			{
				$this->ModificarProvProvee();
			}
		}
		return true;
	}

	function TercerosProvee()//Trae los datos para el combo, del tipo de identificación de los terceros
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_id_tercero,
				descripcion
				FROM tipo_id_terceros
				ORDER BY indice_de_orden;";
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

	function BuscarTercerServicProvee($empresa)//
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['tipodoctra'])
		{
			$codigo=$_REQUEST['tipodoctra'];
			$busqueda1="AND A.tipo_id_tercero='$codigo'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['codigoctra'])
		{
			$codigo=$_REQUEST['codigoctra'];
			$busqueda2="AND A.tercero_id LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['descrictra'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrictra']);
			$busqueda3="AND UPPER(B.nombre_tercero) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda3='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT A.tipo_id_tercero,
					A.tercero_id,
					A.estado,
					B.nombre_tercero,
					B.telefono
					FROM terceros_proveedores_servicios_salud AS A,
					terceros AS B
					WHERE A.empresa_id='".$empresa."'
					AND A.tipo_id_tercero=B.tipo_id_tercero
					AND A.tercero_id=B.tercero_id
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
				SELECT A.tipo_id_tercero,
				A.tercero_id,
				A.estado,
				B.nombre_tercero,
				B.telefono
				FROM terceros_proveedores_servicios_salud AS A,
				terceros AS B
				WHERE A.empresa_id='".$empresa."'
				AND A.tipo_id_tercero=B.tipo_id_tercero
				AND A.tercero_id=B.tercero_id
				$busqueda1
				$busqueda2
				$busqueda3
				ORDER BY A.tipo_id_tercero, A.tercero_id
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

	function BuscarPlanesProvee($empresa,$tipoidterc,$tercerosid)//
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT plan_proveedor_id,
				plan_descripcion,
				num_contrato,
				fecha_inicio,
				fecha_final,
				estado
				FROM planes_proveedores
				WHERE empresa_id='".$empresa."'
				AND tipo_id_tercero='".$tipoidterc."'
				AND tercero_id='".$tercerosid."'
				ORDER BY estado DESC;";
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

	function BuscarGruposCargosProvee($empresa,$tipoidter,$terceroid,$plan)//Busca los grupos de los cargos del cups
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT DISTINCT Y.grupo_tipo_cargo,
				Y.tipo_cargo,
				R.descripcion AS des1,
				S.descripcion AS des2
				FROM plan_tarifario_proveedores AS X,
				tarifarios_detalle AS Y,
				grupos_tipos_cargo AS R,
				tipos_cargos AS S
				WHERE X.plan_proveedor_id=".$plan."
				AND X.tarifario_id=Y.tarifario_id
				AND X.grupo_tarifario_id=Y.grupo_tarifario_id
				AND X.subgrupo_tarifario_id=Y.subgrupo_tarifario_id
				AND Y.grupo_tipo_cargo=R.grupo_tipo_cargo
				AND Y.grupo_tipo_cargo=S.grupo_tipo_cargo
				AND Y.tipo_cargo=S.tipo_cargo
				AND Y.cargo NOT IN
				(SELECT B.cargo
				FROM plan_tarifario_proveedores AS A,
				tarifarios_detalle AS B,
				excepciones_proveedores AS C
				WHERE A.plan_proveedor_id=".$plan."
				AND A.tarifario_id=B.tarifario_id
				AND A.grupo_tarifario_id=B.grupo_tarifario_id
				AND A.subgrupo_tarifario_id=B.subgrupo_tarifario_id
				AND C.plan_proveedor_id=".$plan."
				AND B.tarifario_id=C.tarifario_id
				AND B.cargo=C.cargo
				AND C.sw_no_contratado=1);";
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

	function BuscarCargosProvee($empresa,$tipoidt,$tercero,$plan,$grupo,$tipos)//Función que busca los cargos del cups contra los contratados por el proveedor de servicios
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigoctra'])
		{
			$codigo=$_REQUEST['codigoctra'];
			$busqueda1="AND S.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descrictra'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrictra']);
			$busqueda2="AND UPPER(S.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM (
					(
					SELECT S.cargo AS cargocontr,
					S.descripcion AS des1,
					S.tarifario_id,
					X.descripcion AS des2,
					T.cargo AS cargoexcep,
					T.sw_no_contratado,
					V.cargo_base,
					Y.descripcion AS des3,
					W.cargo AS cargoprove
					FROM plan_tarifario_proveedores AS R,
					tarifarios_detalle AS S
					LEFT JOIN excepciones_proveedores AS T ON
					(T.plan_proveedor_id=".$plan."
					AND T.tarifario_id=S.tarifario_id
					AND T.cargo=S.cargo)
					LEFT JOIN tarifarios_equivalencias AS V ON
					(S.tarifario_id=V.tarifario_id
					AND S.cargo=V.cargo)
					LEFT JOIN cups AS Y ON
					(V.cargo_base=Y.cargo)
					LEFT JOIN terceros_proveedores_cargos AS W ON
					(V.cargo_base=W.cargo
					AND W.empresa_id='".$empresa."'
					AND W.tipo_id_tercero='".$tipoidt."'
					AND W.tercero_id='".$tercero."'),
					tarifarios AS X
					WHERE R.plan_proveedor_id=".$plan."
					AND R.tarifario_id=S.tarifario_id
					AND R.grupo_tarifario_id=S.grupo_tarifario_id
					AND R.subgrupo_tarifario_id=S.subgrupo_tarifario_id
					AND S.grupo_tipo_cargo='".$grupo."'
					AND S.tipo_cargo='".$tipos."'
					AND S.tarifario_id=X.tarifario_id
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
		}/*echo*/
		$query = "
				(
				SELECT S.cargo AS cargocontr,
				S.descripcion AS des1,
				S.tarifario_id,
				X.descripcion AS des2,
				T.cargo AS cargoexcep,
				T.sw_no_contratado,
				V.cargo_base,
				Y.descripcion AS des3,
				W.cargo AS cargoprove
				FROM plan_tarifario_proveedores AS R,
				tarifarios_detalle AS S
				LEFT JOIN excepciones_proveedores AS T ON
				(T.plan_proveedor_id=".$plan."
				AND T.tarifario_id=S.tarifario_id
				AND T.cargo=S.cargo)
				LEFT JOIN tarifarios_equivalencias AS V ON
				(S.tarifario_id=V.tarifario_id
				AND S.cargo=V.cargo)
				LEFT JOIN cups AS Y ON
				(V.cargo_base=Y.cargo)
				LEFT JOIN terceros_proveedores_cargos AS W ON
				(V.cargo_base=W.cargo
				AND W.empresa_id='".$empresa."'
				AND W.tipo_id_tercero='".$tipoidt."'
				AND W.tercero_id='".$tercero."'),
				tarifarios AS X
				WHERE R.plan_proveedor_id=".$plan."
				AND R.tarifario_id=S.tarifario_id
				AND R.grupo_tarifario_id=S.grupo_tarifario_id
				AND R.subgrupo_tarifario_id=S.subgrupo_tarifario_id
				AND S.grupo_tipo_cargo='".$grupo."'
				AND S.tipo_cargo='".$tipos."'
				AND S.tarifario_id=X.tarifario_id
				$busqueda1
				$busqueda2
				ORDER BY S.cargo
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

	function ValidarCargosProvee()//Función que guarda o borra los cargos que presta el proveedor
	{
		if(empty($_POST['ayuda']))
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$contador1=$contador2=0;
			$ciclo=sizeof($_SESSION['propla']['cargcupspr']);
			for($i=0;$i<$ciclo;$i++)
			{
				if($_POST['cargcupspr'.$i]<>NULL
				AND $_SESSION['propla']['cargcupspr'][$i]['cargoprove']==NULL
				AND $_SESSION['propla']['cargcupspr'][$i]['cargo_base']<>NULL
				AND !($_SESSION['propla']['cargcupspr'][$i]['cargoexcep']<>NULL
				AND $_SESSION['propla']['cargcupspr'][$i]['sw_no_contratado']==1))
				{
					$contador1++;
					$query = "INSERT INTO terceros_proveedores_cargos
							(empresa_id,
							tipo_id_tercero,
							tercero_id,
							cargo)
							VALUES
							('".$_SESSION['provee']['empresa']."',
							'".$_SESSION['propla']['tipoidtpro']."',
							'".$_SESSION['propla']['terceropro']."',
							'".$_SESSION['propla']['cargcupspr'][$i]['cargo_base']."');";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$dbconn->RollBackTrans();
					}
				}
				else if($_POST['cargcupspr'.$i]==NULL
				AND $_SESSION['propla']['cargcupspr'][$i]['cargoprove']<>NULL
				AND $_SESSION['propla']['cargcupspr'][$i]['cargo_base']<>NULL
				AND !($_SESSION['propla']['cargcupspr'][$i]['cargoexcep']<>NULL
				AND $_SESSION['propla']['cargcupspr'][$i]['sw_no_contratado']==1))
				{
					$contador2++;
					$query = "DELETE FROM terceros_proveedores_cargos
							WHERE empresa_id='".$_SESSION['provee']['empresa']."'
							AND tipo_id_tercero='".$_SESSION['propla']['tipoidtpro']."'
							AND tercero_id='".$_SESSION['propla']['terceropro']."'
							AND cargo='".$_SESSION['propla']['cargcupspr'][$i]['cargo_base']."';";
					$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$dbconn->RollBackTrans();
					}
				}
			}
			$dbconn->CommitTrans();
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
			$this->uno=1;
			$this->CargosProvee();
			return true;
		}
		else
		{
			if($_POST['ayuda']==1)
			{
				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();
 				$query = "DELETE FROM terceros_proveedores_cargos
						WHERE cargo IN
						(
							SELECT A.cargo
							FROM terceros_proveedores_cargos AS A,
							cups AS B
							WHERE B.grupo_tipo_cargo='".$_SESSION['propla']['grupcargpr'][$_SESSION['propla']['grucareleg']]['grupo_tipo_cargo']."'
							AND B.tipo_cargo='".$_SESSION['propla']['grupcargpr'][$_SESSION['propla']['grucareleg']]['tipo_cargo']."'
							AND B.cargo=A.cargo
							AND A.empresa_id='".$_SESSION['provee']['empresa']."'
							AND A.tipo_id_tercero='".$_SESSION['propla']['tipoidtpro']."'
							AND A.tercero_id='".$_SESSION['propla']['terceropro']."'
						);";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
				$query = "INSERT INTO terceros_proveedores_cargos
						(
							empresa_id,
							tipo_id_tercero,
							tercero_id,
							cargo
						)
						SELECT
						'".$_SESSION['provee']['empresa']."',
						'".$_SESSION['propla']['tipoidtpro']."',
						'".$_SESSION['propla']['terceropro']."',
						cargo
						FROM cups
						WHERE grupo_tipo_cargo='".$_SESSION['propla']['grupcargpr'][$_SESSION['propla']['grucareleg']]['grupo_tipo_cargo']."'
						AND tipo_cargo='".$_SESSION['propla']['grupcargpr'][$_SESSION['propla']['grucareleg']]['tipo_cargo']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
				$dbconn->CommitTrans();
				$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
				$this->uno=1;
				$this->CargosProvee();
				return true;
			}
			else if($_POST['ayuda']==2)
			{
				$this->ConfirmarCargosProvee();
				return true;
			}
			else if($_POST['ayuda']==3)
			{
				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();
				$contador1=0;
				$ciclo=sizeof($_SESSION['propla']['cargcupspr']);
				for($i=0;$i<$ciclo;$i++)
				{
					if($_SESSION['propla']['cargcupspr'][$i]['cargoprove']==NULL
					AND $_SESSION['propla']['cargcupspr'][$i]['cargo_base']<>NULL
					AND !($_SESSION['propla']['cargcupspr'][$i]['cargoexcep']<>NULL
					AND $_SESSION['propla']['cargcupspr'][$i]['sw_no_contratado']==1))
					{
						$contador1++;
						$query = "INSERT INTO terceros_proveedores_cargos
								(empresa_id,
								tipo_id_tercero,
								tercero_id,
								cargo)
								VALUES
								('".$_SESSION['provee']['empresa']."',
								'".$_SESSION['propla']['tipoidtpro']."',
								'".$_SESSION['propla']['terceropro']."',
								'".$_SESSION['propla']['cargcupspr'][$i]['cargo_base']."');";
						$resulta = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$dbconn->RollBackTrans();
						}
					}
				}
				$dbconn->CommitTrans();
				$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."";
				$this->uno=1;
				$this->CargosProvee();
				return true;
			}
			else if($_POST['ayuda']==4)
			{
				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();
				$contador1=0;
				$ciclo=sizeof($_SESSION['propla']['cargcupspr']);
				for($i=0;$i<$ciclo;$i++)
				{
					if($_SESSION['propla']['cargcupspr'][$i]['cargoprove']<>NULL
					AND $_SESSION['propla']['cargcupspr'][$i]['cargo_base']<>NULL
					AND !($_SESSION['propla']['cargcupspr'][$i]['cargoexcep']<>NULL
					AND $_SESSION['propla']['cargcupspr'][$i]['sw_no_contratado']==1))
					{
						$contador1++;
						$query = "DELETE FROM terceros_proveedores_cargos
								WHERE empresa_id='".$_SESSION['provee']['empresa']."'
								AND tipo_id_tercero='".$_SESSION['propla']['tipoidtpro']."'
								AND tercero_id='".$_SESSION['propla']['terceropro']."'
								AND cargo='".$_SESSION['propla']['cargcupspr'][$i]['cargo_base']."';";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$dbconn->RollBackTrans();
						}
					}
				}
				$dbconn->CommitTrans();
				$this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE: ".$contador1."";
				$this->uno=1;
				$this->CargosProvee();
				return true;
			}
		}
	}

	function BorrarCargosProvee()//Función que borra todos los cargos una vez confirmados por el usuario
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query = "DELETE FROM terceros_proveedores_cargos
				WHERE cargo IN
				(
					SELECT A.cargo
					FROM terceros_proveedores_cargos AS A,
					cups AS B
					WHERE B.grupo_tipo_cargo='".$_SESSION['propla']['grupcargpr'][$_SESSION['propla']['grucareleg']]['grupo_tipo_cargo']."'
					AND B.tipo_cargo='".$_SESSION['propla']['grupcargpr'][$_SESSION['propla']['grucareleg']]['tipo_cargo']."'
					AND B.cargo=A.cargo
					AND A.empresa_id='".$_SESSION['provee']['empresa']."'
					AND A.tipo_id_tercero='".$_SESSION['propla']['tipoidtpro']."'
					AND A.tercero_id='".$_SESSION['propla']['terceropro']."'
				);";
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollBackTrans();
			return false;
		}
		$dbconn->CommitTrans();
		$this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
		$this->uno=1;
		$this->CargosProvee();
		return true;
	}

	function BuscarDepartamentosProvee($empresa)//Busca los departamentos de la empresa
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT departamento,
				descripcion
				FROM departamentos
				WHERE empresa_id='".$empresa."'
				AND sw_internacion='1'
				ORDER BY descripcion;";
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

	function BuscarGruposCargosInternosProvee($departamento)//Busca los grupos de los cargos del cups
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT B.grupo_tipo_cargo,
				B.descripcion AS des1,
				A.tipo_cargo,
				A.descripcion AS des2,
				contar_cargos_cups(A.grupo_tipo_cargo, A.tipo_cargo) AS cantidad,
				contar_departamentos_cargos
				(A.grupo_tipo_cargo, A.tipo_cargo, '".$departamento."') AS grupoesta
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
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarCargosInternosProvee($grupo,$tipos,$departamento)//Función que busca los cargos del cups contra los prestados por el departamento de mi empresa
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigoctra'])
		{
			$codigo=$_REQUEST['codigoctra'];
			$busqueda1="AND A.cargo LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descrictra'])
		{
			$codigo=STRTOUPPER($_REQUEST['descrictra']);
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
					B.cargo AS cargoproveedor
					FROM cups AS A
					LEFT JOIN departamentos_cargos AS B ON
					(A.cargo=B.cargo
					AND B.departamento='".$departamento."')
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
				B.cargo AS cargoproveedor
				FROM cups AS A
				LEFT JOIN departamentos_cargos AS B ON
				(A.cargo=B.cargo
				AND B.departamento='".$departamento."')
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
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarCargosInternosProvee()//Función que guarda o borra los cargos que presta el proveedor
	{
		if(empty($_POST['ayuda']))
		{
			list($dbconn) = GetDBconn();
			$contador1=$contador2=0;
			$ciclo=sizeof($_SESSION['propla']['cargosinpr']);
			for($i=0;$i<$ciclo;$i++)
			{
				if($_POST['cargcupspr'.$i]<>NULL AND $_SESSION['propla']['cargosinpr'][$i]['cargoproveedor']==NULL)
				{
					$contador1++;
					$query = "INSERT INTO departamentos_cargos
							(departamento,
							cargo)
							VALUES
							('".$_SESSION['propla']['departeleg']."',
							'".$_SESSION['propla']['cargosinpr'][$i]['cargocups']."');";
					$resulta = $dbconn->Execute($query);
				}
				else if($_POST['cargcupspr'.$i]==NULL AND $_SESSION['propla']['cargosinpr'][$i]['cargoproveedor']<>NULL)
				{
					$contador2++;
					$query = "DELETE FROM departamentos_cargos
							WHERE departamento='".$_SESSION['propla']['departeleg']."'
							AND cargo='".$_SESSION['propla']['cargosinpr'][$i]['cargocups']."';";
					$dbconn->Execute($query);
				}
			}
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
			$this->uno=1;
			$this->CargosInternosProvee();
			return true;
		}
		else
		{
			if($_POST['ayuda']==1)
			{
				list($dbconn) = GetDBconn();
 				$query = "DELETE FROM departamentos_cargos
						WHERE cargo IN
						(
							SELECT A.cargo
							FROM departamentos_cargos AS A,
							cups AS B
							WHERE B.grupo_tipo_cargo='".$_SESSION['propla']['grucarinpr'][$_SESSION['propla']['grcaineleg']]['grupo_tipo_cargo']."'
							AND B.tipo_cargo='".$_SESSION['propla']['grucarinpr'][$_SESSION['propla']['grcaineleg']]['tipo_cargo']."'
							AND B.cargo=A.cargo
							AND A.departamento='".$_SESSION['propla']['departeleg']."'
						);";
				$dbconn->Execute($query);
				$query = "INSERT INTO departamentos_cargos
						(
							departamento,
							cargo
						)
						SELECT
						'".$_SESSION['propla']['departeleg']."',
						cargo
						FROM cups
						WHERE grupo_tipo_cargo='".$_SESSION['propla']['grucarinpr'][$_SESSION['propla']['grcaineleg']]['grupo_tipo_cargo']."'
						AND tipo_cargo='".$_SESSION['propla']['grucarinpr'][$_SESSION['propla']['grcaineleg']]['tipo_cargo']."';";
				$resulta = $dbconn->Execute($query);
				$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
				$this->uno=1;
				$this->CargosInternosProvee();
				return true;
			}
			else if($_POST['ayuda']==2)
			{
				$this->ConfirmarCargosInternosProvee();
				return true;
			}
			else if($_POST['ayuda']==3)
			{
				list($dbconn) = GetDBconn();
				$contador1=0;
				$ciclo=sizeof($_SESSION['propla']['cargosinpr']);
				for($i=0;$i<$ciclo;$i++)
				{
					if($_SESSION['propla']['cargosinpr'][$i]['cargoproveedor']==NULL)
					{
						$contador1++;
						$query = "INSERT INTO departamentos_cargos
							(departamento,
							cargo)
							VALUES
							('".$_SESSION['propla']['departeleg']."',
							'".$_SESSION['propla']['cargosinpr'][$i]['cargocups']."');";
						$resulta = $dbconn->Execute($query);
					}
				}
				$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."";
				$this->uno=1;
				$this->CargosInternosProvee();
				return true;
			}
			else if($_POST['ayuda']==4)
			{
				list($dbconn) = GetDBconn();
				$contador1=0;
				$ciclo=sizeof($_SESSION['propla']['cargosinpr']);
				for($i=0;$i<$ciclo;$i++)
				{
					if($_SESSION['propla']['cargosinpr'][$i]['cargoproveedor']<>NULL)
					{
						$contador1++;
						$query = "DELETE FROM departamentos_cargos
							WHERE departamento='".$_SESSION['propla']['departeleg']."'
							AND cargo='".$_SESSION['propla']['cargosinpr'][$i]['cargocups']."';";
						$dbconn->Execute($query);
					}
				}
				$this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE: ".$contador1."";
				$this->uno=1;
				$this->CargosInternosProvee();
				return true;
			}
		}
	}

	function BorrarCargosInternosProvee()//Función que borra todos los cargos una vez confirmados por el usuario
	{
		list($dbconn) = GetDBconn();
		$query = "DELETE FROM departamentos_cargos
						WHERE cargo IN
						(
							SELECT A.cargo
							FROM departamentos_cargos AS A,
							cups AS B
							WHERE B.grupo_tipo_cargo='".$_SESSION['propla']['grucarinpr'][$_SESSION['propla']['grcaineleg']]['grupo_tipo_cargo']."'
							AND B.tipo_cargo='".$_SESSION['propla']['grucarinpr'][$_SESSION['propla']['grcaineleg']]['tipo_cargo']."'
							AND B.cargo=A.cargo
							AND A.departamento='".$_SESSION['propla']['departeleg']."'
						);";
		$dbconn->Execute($query);
		$this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
		$this->uno=1;
		$this->CargosInternosProvee();
		return true;
	}

}//fin de la clase
?>
