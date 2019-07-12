
<?php

/**
* Modulo de Compras (PHP).
*
* Modulo que establece los procesos y métodos, para realizar las compras
* de los medicamentos e insumos de la empresa, considerando el respectivo
* registro en contabilidad y actualizando el inventario de la misma.
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Compras_user.php
*
* Clase que permite el acceso a los datos de compras, establece los mecanismos
* para llevar a cabo una compra, controlando todos los requerimientos de
* seguridad y actualizando el inventario; relacionado con el modulo InvProveedores
**/

class app_Compras_user extends classModulo
{
	var $uno;//para los errores
	var $limit;
	var $conteo;

	function app_Compras_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->PrincipalCompra2();
		return true;
	}

	function UsuariosCompra()//Función de permisos
	{
		list($dbconn) = GetDBconn();
		$usuario=UserGetUID();
		$query = "SELECT A.empresa_id,
				B.razon_social AS descripcion1,
				A.centro_utilidad,
				C.descripcion AS descripcion2
				FROM userpermisos_compras AS A,
				empresas AS B,
				centros_utilidad AS C
				WHERE A.usuario_id=".$usuario."
				AND A.empresa_id=B.empresa_id
				AND A.centro_utilidad=C.centro_utilidad
				AND A.empresa_id=C.empresa_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var2[$resulta->fields[1]][$resulta->fields[3]]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		$mtz[0]='EMPRESAS';
		$mtz[1]='CENTRO DE UTILIDAD';
		$url[0]='app';
		$url[1]='Compras';
		$url[2]='user';
		$url[3]='PrincipalCompra';
		$url[4]='permisocompras';
		$this->salida .=gui_theme_menu_acceso('COMPRAS', $mtz, $var2, $url, ModuloGetURL('system','Menu'));
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

	function BuscarParametrosEvaluacionCompra($empresa)//Busca los criterios de evaluación de cada empresa
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_calificacion_id,
				descripcion,
				estado
				FROM compras_proveedores_calificaciones_tipos
				WHERE empresa_id='".$empresa."'
				ORDER BY tipo_calificacion_id;";
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

	function CambiarEstadoCompra()//Cambia de estado la criterio de evaluación
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['estado']==1)
		{
			$query = "UPDATE compras_proveedores_calificaciones_tipos SET estado=0
					WHERE tipo_calificacion_id=".$_REQUEST['critereleg'].";";
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
			$query = "UPDATE compras_proveedores_calificaciones_tipos SET estado=1
					WHERE tipo_calificacion_id=".$_REQUEST['critereleg'].";";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
				return false;
			}
		}
		$this->ParametrosEvaluacionCompra();
		return true;
	}

	function BuscarCriterioEvaluacionCompra($criterio)//Busca los sub - criterios de cada criterio de evaluación
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT descripcion,
				estado
				FROM compras_proveedores_calificaciones_tipos
				WHERE tipo_calificacion_id=".$criterio.";";
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

	function BuscarSubCriterioEvaluacionCompra($criterio)//Busca los sub - criterios de cada criterio de evaluación
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_calificacion_id,
				item_id,
				descripcion,
				puntaje,
				estado
				FROM compras_proveedores_calificaciones_items
				WHERE tipo_calificacion_id=".$criterio."
				ORDER BY tipo_calificacion_id, item_id;";
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

	function CambiarEstadoSubCompra()//Cambia de estado la criterio de evaluación
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['estado']==1)
		{
			$query = "UPDATE compras_proveedores_calificaciones_items SET estado=0
					WHERE item_id=".$_REQUEST['itemideleg'].";";
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
			$query = "UPDATE compras_proveedores_calificaciones_items SET estado=1
					WHERE item_id=".$_REQUEST['itemideleg'].";";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " .$dbconn->ErrorMsg();
				return false;
			}
		}
		$this->MenuParametrosCompra();
		return true;
	}

	function ValidarCriterioEvaluacionCompra()//Guarda el nuevo criterio de evaluación
	{
		if($_POST['descripcion']==NULL)
		{
			$this->frmError["descripcion"]=1;
		}
		if(empty($_POST['estado']))
		{
			$this->frmError["estado"]=1;
		}
		if($_POST['descripcion']==NULL||empty($_POST['estado']))
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno = 1;
			$this->IngresaCriterioEvaluacionCompra();
			return true;
		}
		else
		{
			list($dbconn) = GetDBconn();
			if($_POST['estado']==2)
			{
				$_POST['estado']=0;
			}
			$query = "INSERT INTO compras_proveedores_calificaciones_tipos
					(descripcion,
					estado,
					empresa_id)
					VALUES
					('".strtoupper($_POST['descripcion'])."',
					'".$_POST['estado']."',
					'".$_SESSION['compra']['empresa']."');";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$this->ParametrosEvaluacionCompra();
			return true;
		}
	}

	function Validar2CriterioEvaluacionCompra()//Válida y modifica los datos del criterio
	{
		if($_POST['descripcionM']==NULL)
		{
			$this->frmError["descripcionM"]=1;
		}
		if(empty($_POST['estadoM']))
		{
			$this->frmError["estadoM"]=1;
		}
		if($_POST['descripcionM']==NULL||empty($_POST['estadoM']))
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno = 1;
			$this->ModificarCriterioEvaluacionCompra();
			return true;
		}
		else
		{
			list($dbconn) = GetDBconn();
			if($_POST['estadoM']==2)
			{
				$_POST['estadoM']=0;
			}
			$query = "UPDATE compras_proveedores_calificaciones_tipos SET
					descripcion='".strtoupper($_POST['descripcionM'])."',
					estado='".$_POST['estadoM']."'
					WHERE tipo_calificacion_id=".$_SESSION['compr1']['critipeleg'].";";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$_SESSION['compr1']['descrieleg']=strtoupper($_POST['descripcionM']);
			$this->MenuParametrosCompra();
			return true;
		}
	}

	function ValidarSubCriterioEvaluacionCompra()//Guarda los datos del sub criterio de evaluación
	{
		if($_POST['descripcion']==NULL)
		{
			$this->frmError["descripcion"]=1;
		}
		if($_POST['puntaje']==NULL)
		{
			$this->frmError["puntaje"]=1;
		}
		else
		{
			if(is_numeric($_POST['puntaje'.$i])==1)
			{
				$punt=intval($_POST['puntaje'.$i]);
				if($punt > 32000)
				{
					$this->frmError["puntaje"]=1;
					$this->frmError["MensajeError"]="PUNTAJE ES UN VALOR NÚMERICO ENTERO";
					$_POST['puntaje']='';
				}
			}
			else
			{
				$this->frmError["puntaje"]=1;
				$this->frmError["MensajeError"]="PUNTAJE ES UN VALOR NÚMERICO ENTERO";
				$_POST['puntaje']='';
			}
		}
		if(empty($_POST['estado']))
		{
			$this->frmError["estado"]=1;
		}
		if($_POST['descripcion']==NULL||$_POST['puntaje']==NULL||empty($_POST['estado']))
		{
			if($this->frmError["MensajeError"]==NULL)
			{
				$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			}
			$this->uno = 1;
			$this->IngresaSubCriterioEvaluacionCompra();
			return true;
		}
		else
		{
			list($dbconn) = GetDBconn();
			if($_POST['estado']==2)
			{
				$_POST['estado']=0;
			}
			$query = "INSERT INTO compras_proveedores_calificaciones_items
					(descripcion,
					estado,
					puntaje,
					tipo_calificacion_id)
					VALUES
					('".strtoupper($_POST['descripcion'])."',
					'".$_POST['estado']."',
					'".$_POST['puntaje']."',
					".$_SESSION['compr1']['critipeleg'].");";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$this->MenuParametrosCompra();
			return true;
		}
	}

	function Validar2SubCriterioEvaluacionCompra()//Válida y modifica los datos del sub criterio
	{
		if($_POST['descripcionM']==NULL)
		{
			$this->frmError["descripcionM"]=1;
		}
		if(empty($_POST['estadoM']))
		{
			$this->frmError["estadoM"]=1;
		}
		if($_POST['puntajeM']==NULL)
		{
			$this->frmError["puntajeM"]=1;
		}
		else
		{
			if(is_numeric($_POST['puntajeM'.$i])==1)
			{
				$punt=intval($_POST['puntajeM'.$i]);
				if($punt > 32000)
				{
					$this->frmError["puntajeM"]=1;
					$this->frmError["MensajeError"]="PUNTAJE ES UN VALOR NÚMERICO ENTERO";
					$_POST['puntajeM']='';
				}
			}
			else
			{
				$this->frmError["puntajeM"]=1;
				$this->frmError["MensajeError"]="PUNTAJE ES UN VALOR NÚMERICO ENTERO";
				$_POST['puntajeM']='';
			}
		}
		if($_POST['descripcionM']==NULL||$_POST['puntajeM']==NULL||empty($_POST['estadoM']))
		{
			if($this->frmError["MensajeError"]==NULL)
			{
				$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			}
			$this->uno = 1;
			$this->ModificarSubCriterioEvaluacionCompra();
			return true;
		}
		else
		{
			list($dbconn) = GetDBconn();
			if($_POST['estadoM']==2)
			{
				$_POST['estadoM']=0;
			}
			$query = "UPDATE compras_proveedores_calificaciones_items SET
					descripcion='".strtoupper($_POST['descripcionM'])."',
					puntaje=".$punt.",
					estado='".$_POST['estadoM']."'
					WHERE tipo_calificacion_id=".$_SESSION['compr1']['critipeleg']."
					AND item_id=".$_POST['itemM'].";";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$this->MenuParametrosCompra();
			return true;
		}
	}

	function BuscarProveedoresProductosCompra($empresa)//Busca los terceros que sean proveedores de la empresa
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocomp'])
		{
			$codigo=$_REQUEST['codigocomp'];
			$busqueda1="AND A.tercero_id LIKE '%$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descricomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['descricomp']);
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
						B.nombre_tercero,
						C.descripcion
						FROM terceros_proveedores AS A
						LEFT JOIN centros_utilidad AS C ON
						(C.centro_utilidad=A.centro_utilidad
						AND C.empresa_id='".$empresa."'),
						terceros AS B
						WHERE (A.empresa_id='".$empresa."'
						OR A.empresa_id_centro='".$empresa."')
						AND A.tipo_id_tercero=B.tipo_id_tercero
						AND A.tercero_id=B.tercero_id
						AND A.estado='1'
						$busqueda1
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
				B.nombre_tercero,
				C.descripcion
				FROM terceros_proveedores AS A
				LEFT JOIN centros_utilidad AS C ON
				(C.centro_utilidad=A.centro_utilidad
				AND C.empresa_id='".$empresa."'),
				terceros AS B
				WHERE (A.empresa_id='".$empresa."'
				OR A.empresa_id_centro='".$empresa."')
				AND A.tipo_id_tercero=B.tipo_id_tercero
				AND A.tercero_id=B.tercero_id
				AND A.estado='1'
				$busqueda1
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

	function BuscarMenuProveProduCompra($empresa,$codigo)//Función que busca los datos del proveedor
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.empresa_id,
				A.tipo_id_tercero,
				A.tercero_id,
				A.empresa_id_centro,
				A.centro_utilidad,
				A.estado,
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

	function BuscarDatosEvaluacionCompra($evaluacion)//Función que busca los datos de la evaluación del proveedor
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

	function BuscarConsultarProveConProduCompra($empresa,$proveedor)//Busca los productos que ofrece el proveedor
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocomp'])
		{
			$codigo=$_REQUEST['codigocomp'];
			$busqueda1="AND B.codigo_producto LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descricomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['descricomp']);
			$busqueda2="AND UPPER(B.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['grupo'])
		{
			$codigo=STRTOUPPER($_REQUEST['grupo']);
			$busqueda3="AND B.grupo_id='$codigo'";
		}
		else
		{
			$busqueda3='';
		}
		if($_REQUEST['clasePr'])
		{
			$codigo=STRTOUPPER($_REQUEST['clasePr']);
			$busqueda4="AND B.clase_id='$codigo'";
		}
		else
		{
			$busqueda4='';
		}
		if($_REQUEST['subclase'])
		{
			$codigo=STRTOUPPER($_REQUEST['subclase']);
			$busqueda5="AND B.subclase_id='$codigo'";
		}
		else
		{
			$busqueda5='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM
					(
						SELECT B.codigo_producto,
						B.descripcion,
						B.porc_iva,
						B.contenido_unidad_venta,
						D.valor,
						D.fecha_lista,
						D.fecha_vigencia,
						D.codigo_producto_proveedor,
						E.descripcion AS desunidad
						FROM inventarios AS A,
						inventarios_productos AS B,
						compras_proveedores_productos AS C
						LEFT JOIN compras_proveedores_productos_cotizaciones AS D ON
						(D.empresa_id='".$empresa."'
						AND D.codigo_proveedor_id=".$proveedor."
						AND D.empresa_id=C.empresa_id
						AND D.codigo_proveedor_id=C.codigo_proveedor_id
						AND D.codigo_producto=C.codigo_producto),
						unidades AS E
						WHERE A.empresa_id='".$empresa."'
						AND C.codigo_proveedor_id=".$proveedor."
						AND A.empresa_id=C.empresa_id
						AND A.codigo_producto=B.codigo_producto
						AND B.codigo_producto=C.codigo_producto
						AND A.estado=1
						AND B.unidad_id=E.unidad_id
						$busqueda1
						$busqueda2
						$busqueda3
						$busqueda4
						$busqueda5
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
				SELECT B.codigo_producto,
				B.descripcion,
				B.porc_iva,
				B.contenido_unidad_venta,
				D.valor,
				D.fecha_lista,
				D.fecha_vigencia,
				D.codigo_producto_proveedor,
				E.descripcion AS desunidad
				FROM inventarios AS A,
				inventarios_productos AS B,
				compras_proveedores_productos AS C
				LEFT JOIN compras_proveedores_productos_cotizaciones AS D ON
				(D.empresa_id='".$empresa."'
				AND D.codigo_proveedor_id=".$proveedor."
				AND D.empresa_id=C.empresa_id
				AND D.codigo_proveedor_id=C.codigo_proveedor_id
				AND D.codigo_producto=C.codigo_producto),
				unidades AS E
				WHERE A.empresa_id='".$empresa."'
				AND C.codigo_proveedor_id=".$proveedor."
				AND A.empresa_id=C.empresa_id
				AND A.codigo_producto=B.codigo_producto
				AND B.codigo_producto=C.codigo_producto
				AND A.estado=1
				AND B.unidad_id=E.unidad_id
				$busqueda1
				$busqueda2
				$busqueda3
				$busqueda4
				$busqueda5
				ORDER BY B.codigo_producto
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

	function BuscarPreciosProveConProduCompra($empresa,$proveedor)//Busca los precios de los productos que ofrece el proveedor
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocomp'])
		{
			$codigo=$_REQUEST['codigocomp'];
			$busqueda1="AND B.codigo_producto LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descricomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['descricomp']);
			$busqueda2="AND UPPER(B.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['grupo'])
		{
			$codigo=STRTOUPPER($_REQUEST['grupo']);
			$busqueda3="AND B.grupo_id='$codigo'";
		}
		else
		{
			$busqueda3='';
		}
		if($_REQUEST['clasePr'])
		{
			$codigo=STRTOUPPER($_REQUEST['clasePr']);
			$busqueda4="AND B.clase_id='$codigo'";
		}
		else
		{
			$busqueda4='';
		}
		if($_REQUEST['subclase'])
		{
			$codigo=STRTOUPPER($_REQUEST['subclase']);
			$busqueda5="AND B.subclase_id='$codigo'";
		}
		else
		{
			$busqueda5='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM
					(
						SELECT B.codigo_producto,
						B.descripcion,
						B.porc_iva,
						B.contenido_unidad_venta,
						D.valor,
						D.fecha_lista,
						D.fecha_vigencia,
						D.codigo_producto_proveedor,
						D.numero_cotizacion,
						E.descripcion AS desunidad
						FROM inventarios AS A,
						inventarios_productos AS B,
						compras_proveedores_productos AS C
						LEFT JOIN compras_proveedores_productos_cotizaciones AS D ON
						(D.empresa_id='".$empresa."'
						AND D.codigo_proveedor_id=".$proveedor."
						AND D.empresa_id=C.empresa_id
						AND D.codigo_proveedor_id=C.codigo_proveedor_id
						AND D.codigo_producto=C.codigo_producto),
						unidades AS E
						WHERE A.empresa_id='".$empresa."'
						AND C.codigo_proveedor_id=".$proveedor."
						AND A.empresa_id=C.empresa_id
						AND A.codigo_producto=B.codigo_producto
						AND B.codigo_producto=C.codigo_producto
						AND A.estado=1
						AND B.unidad_id=E.unidad_id
						$busqueda1
						$busqueda2
						$busqueda3
						$busqueda4
						$busqueda5
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
				SELECT B.codigo_producto,
				B.descripcion,
				B.porc_iva,
				B.contenido_unidad_venta,
				D.valor,
				D.fecha_lista,
				D.fecha_vigencia,
				D.codigo_producto_proveedor,
				D.numero_cotizacion,
				E.descripcion AS desunidad
				FROM inventarios AS A,
				inventarios_productos AS B,
				compras_proveedores_productos AS C
				LEFT JOIN compras_proveedores_productos_cotizaciones AS D ON
				(D.empresa_id='".$empresa."'
				AND D.codigo_proveedor_id=".$proveedor."
				AND D.empresa_id=C.empresa_id
				AND D.codigo_proveedor_id=C.codigo_proveedor_id
				AND D.codigo_producto=C.codigo_producto),
				unidades AS E
				WHERE A.empresa_id='".$empresa."'
				AND C.codigo_proveedor_id=".$proveedor."
				AND A.empresa_id=C.empresa_id
				AND A.codigo_producto=B.codigo_producto
				AND B.codigo_producto=C.codigo_producto
				AND A.estado=1
				AND B.unidad_id=E.unidad_id
				$busqueda1
				$busqueda2
				$busqueda3
				$busqueda4
				$busqueda5
				ORDER BY B.codigo_producto
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

	function ValidarPreciosProveConProduCompra()//Válida y  guarda los precios y fechas de vigencia de la lista de los proveedores
	{
		if($_SESSION['compr1']['codigospro']==NULL)
		{
			$this->frmError["MensajeError"]="NO SE GUARDARÓN DATOS";
		}
		else
		{
			if($_POST['fechalista']<>NULL)
			{
				$var=explode('/',$_POST['fechalista']);
				$day=$var[0];
				$mon=$var[1];
				$yea=$var[2];
				if(checkdate($mon, $day, $yea)==0)
				{
					$_POST['fechalista']='';
					$this->frmError["MensajeError"]="FECHA DE LISTA CON FORMATO NO VÁLIDO";
				}
				else
				{
					$fech=date("Y-m-d");
					if($fech < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
					{
						$_POST['fechalista']='';
						$this->frmError["MensajeError"]="FECHA DE LISTA MAYOR A LA DEL DÍA DE HOY";
					}
					else
					{
						$fechalista=$yea.'-'.$mon.'-'.$day;
						$_POST['fechalista']=$fechalista;
					}
				}
			}
			if($_POST['fechavigen']<>NULL)
			{
				$var=explode('/',$_POST['fechavigen']);
				$day=$var[0];
				$mon=$var[1];
				$yea=$var[2];
				if(checkdate($mon, $day, $yea)==0)
				{
					$_POST['fechavigen']='';
					if($this->frmError["MensajeError"]==NULL)
					{
						$this->frmError["MensajeError"]="FECHA DE VIGENCIA CON FORMATO NO VÁLIDO";
					}
					else
					{
						$this->frmError["MensajeError"].="<br>FECHA DE VIGENCIA CON FORMATO NO VÁLIDO";
					}
				}
				else
				{
					$fech=date("Y-m-d");
					if($fech >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
					{
						$_POST['fechavigen']='';
						if($this->frmError["MensajeError"]==NULL)
						{
							$this->frmError["MensajeError"]="FECHA DE VIGENCIA MENOR O IGUAL A LA DEL DÍA DE HOY";
						}
						else
						{
							$this->frmError["MensajeError"].="<br>FECHA DE VIGENCIA MENOR O IGUAL A LA DEL DÍA DE HOY";
						}
					}
					else
					{
						$fechavigen=$yea.'-'.$mon.'-'.$day;
						$_POST['fechavigen']=$fechavigen;
					}
				}
			}
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$usuario=UserGetUID();
			$contador1=$contador2=$contador3=0;
			$ciclo=sizeof($_SESSION['compr1']['codigospro']);
			for($i=0;$i<$ciclo;$i++)
			{
				if($_POST['valor'.$i]<>NULL)
				{
					if(is_numeric($_POST['valor'.$i])==1)
					{
						$valor=doubleval($_POST['valor'.$i]);
						if($valor >= 10000000000000)
						{
							$_POST['valor'.$i]='';
						}
						else
						{
							$_POST['valor'.$i]=$valor;
						}
					}
					else
					{
						$_POST['valor'.$i]='';
					}
				}
				if($_POST['fechalista'.$i]<>NULL)
				{
					$var=explode('/',$_POST['fechalista'.$i]);
					$day=$var[0];
					$mon=$var[1];
					$yea=$var[2];
					if(checkdate($mon, $day, $yea)==0)
					{
						$_POST['fechalista'.$i]='';
					}
					else
					{
						$fech=date("Y-m-d");
						if($fech < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
						{
							$_POST['fechalista'.$i]='';
						}
						else
						{
							$_POST['fechalista'.$i]=$yea.'-'.$mon.'-'.$day;
						}
					}
				}
				else
				{
					if($fechalista<>NULL AND $_POST['fechalista'.$i]==NULL)
					{
						$_POST['fechalista'.$i]=$fechalista;
					}
				}
				if($_POST['fechavigen'.$i]<>NULL)
				{
					$var=explode('/',$_POST['fechavigen'.$i]);
					$day=$var[0];
					$mon=$var[1];
					$yea=$var[2];
					if(checkdate($mon, $day, $yea)==0)
					{
						$_POST['fechavigen'.$i]='';
					}
					else
					{
						$fech=date("Y-m-d");
						if($fech >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
						{
							$_POST['fechavigen'.$i]='';
						}
						else
						{
							$_POST['fechavigen'.$i]=$yea.'-'.$mon.'-'.$day;
						}
					}
				}
				else
				{
					if($fechavigen<>NULL AND $_POST['fechavigen'.$i]==NULL)
					{
						$_POST['fechavigen'.$i]=$fechavigen;
					}
				}
				if($_POST['valor'.$i]<>NULL AND $_POST['fechalista'.$i]<>NULL AND $_POST['cotizacprv'.$i]<>NULL)
				{
					if($_POST['fechavigen'.$i]==NULL)
					{
						$fechavig="NULL";
					}
					else
					{
						$fechavig="'".$_POST['fechavigen'.$i]."'";
					}
					if($_SESSION['compr1']['codigospro'][$i]['valor']==NULL)//Para grabar
					{
						$contador1++;
						$query = "INSERT INTO compras_proveedores_productos_cotizaciones
								(codigo_proveedor_id,
								empresa_id,
								codigo_producto,
								valor,
								fecha_lista,
								fecha_vigencia,
								codigo_producto_proveedor,
								numero_cotizacion,
								usuario_id)
								VALUES
								(".$_SESSION['compr1']['provineleg'].",
								'".$_SESSION['compra']['empresa']."',
								'".$_SESSION['compr1']['codigospro'][$i]['codigo_producto']."',
								".$_POST['valor'.$i].",
								'".$_POST['fechalista'.$i]."',
								$fechavig,
								'".$_POST['codiprdprv'.$i]."',
								'".$_POST['cotizacprv'.$i]."',
								".$usuario.");";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollBackTrans();
							return false;
						}
					}
					else if($_SESSION['compr1']['codigospro'][$i]['valor']<>NULL
					AND ($_SESSION['compr1']['codigospro'][$i]['valor']<>$_POST['valor'.$i]
					OR $_SESSION['compr1']['codigospro'][$i]['fecha_lista']<>$_POST['fechalista'.$i]
					OR $_SESSION['compr1']['codigospro'][$i]['fecha_vigencia']<>$_POST['fechavigen'.$i]
					OR $_SESSION['compr1']['codigospro'][$i]['codigo_producto_proveedor']<>$_POST['codiprdprv'.$i]))//Para modificar
					{
						$contador2++;
						$query = "UPDATE compras_proveedores_productos_cotizaciones SET
								valor=".$_POST['valor'.$i].",
								fecha_lista='".$_POST['fechalista'.$i]."',
								fecha_vigencia=$fechavig,
								codigo_producto_proveedor='".$_POST['codiprdprv'.$i]."',
								numero_cotizacion='".$_POST['cotizacprv'.$i]."'
								WHERE codigo_proveedor_id=".$_SESSION['compr1']['provineleg']."
								AND empresa_id='".$_SESSION['compra']['empresa']."'
								AND codigo_producto='".$_SESSION['compr1']['codigospro'][$i]['codigo_producto']."';";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollBackTrans();
							return false;
						}
					}
					else if($_POST['eliminar'.$i]<>NULL AND $_SESSION['compr1']['codigospro'][$i]['valor']<>NULL)
					{
						$contador3++;
						$query = "DELETE FROM compras_proveedores_productos_cotizaciones
								WHERE codigo_proveedor_id=".$_SESSION['compr1']['provineleg']."
								AND empresa_id='".$_SESSION['compra']['empresa']."'
								AND codigo_producto='".$_SESSION['compr1']['codigospro'][$i]['codigo_producto']."';";
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
				$_POST['eliminar'.$i]='';
				$_POST['valor'.$i]='';
				$_POST['cotizacprv'.$i]='';
				$_POST['codiprdprv'.$i]='';
				$_POST['fechalista'.$i]='';
				$_POST['fechavigen'.$i]='';
			}
			$dbconn->CommitTrans();
			if($this->frmError["MensajeError"]==NULL)
			{
				$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
				<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
				<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
			}
			else
			{
				$this->frmError["MensajeError"].="<br>DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
				<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
				<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
			}
		}
		$this->uno=1;
		$this->PreciosProveConProduCompra();
		return true;
	}

	function BuscarRelacionarProveConProduCompra($empresa,$proveedor)//Busca los productos de la empresa, tanto los que ofrece como los que no
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocomp'])
		{
			$codigo=$_REQUEST['codigocomp'];
			$busqueda1="AND B.codigo_producto LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descricomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['descricomp']);
			$busqueda2="AND UPPER(B.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['grupo'])
		{
			$codigo=STRTOUPPER($_REQUEST['grupo']);
			$busqueda3="AND B.grupo_id='$codigo'";
		}
		else
		{
			$busqueda3='';
		}
		if($_REQUEST['clasePr'])
		{
			$codigo=STRTOUPPER($_REQUEST['clasePr']);
			$busqueda4="AND B.clase_id='$codigo'";
		}
		else
		{
			$busqueda4='';
		}
		if($_REQUEST['subclase'])
		{
			$codigo=STRTOUPPER($_REQUEST['subclase']);
			$busqueda5="AND B.subclase_id='$codigo'";
		}
		else
		{
			$busqueda5='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM
					(
						SELECT B.codigo_producto,
						B.descripcion,
						B.porc_iva,
						B.contenido_unidad_venta,
						C.codigo_proveedor_id AS provee,
						D.descripcion AS desunidad
						FROM inventarios AS A,
						inventarios_productos AS B
						LEFT JOIN compras_proveedores_productos AS C ON
						(C.empresa_id='".$empresa."'
						AND C.codigo_proveedor_id=".$proveedor."
						AND B.codigo_producto=C.codigo_producto),
						unidades AS D
						WHERE A.empresa_id='".$empresa."'
						AND A.codigo_producto=B.codigo_producto
						AND A.estado=1
						AND B.unidad_id=D.unidad_id
						$busqueda1
						$busqueda2
						$busqueda3
						$busqueda4
						$busqueda5
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
				SELECT B.codigo_producto,
				B.descripcion,
				B.porc_iva,
				B.contenido_unidad_venta,
				C.codigo_proveedor_id AS provee,
				D.descripcion AS desunidad
				FROM inventarios AS A,
				inventarios_productos AS B
				LEFT JOIN compras_proveedores_productos AS C ON
				(C.empresa_id='".$empresa."'
				AND C.codigo_proveedor_id=".$proveedor."
				AND B.codigo_producto=C.codigo_producto),
				unidades AS D
				WHERE A.empresa_id='".$empresa."'
				AND A.codigo_producto=B.codigo_producto
				AND A.estado=1
				AND B.unidad_id=D.unidad_id
				$busqueda1
				$busqueda2
				$busqueda3
				$busqueda4
				$busqueda5
				ORDER BY B.codigo_producto
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

	function ValidarRelacionarProveConProduCompra()//Válida los seleccionados y los guarda o los elimina
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$usuario=UserGetUID();
		$contador1=$contador2=0;
		$ciclo=sizeof($_SESSION['compr1']['codigospro']);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_SESSION['compr1']['codigospro'][$i]['provee']==NULL AND $_POST['eliminar'.$i]<>NULL)//Para grabar
			{
				$contador1++;
				$query = "INSERT INTO compras_proveedores_productos
						(codigo_proveedor_id,
						empresa_id,
						codigo_producto,
						usuario_id)
						VALUES
						(".$_SESSION['compr1']['provineleg'].",
						'".$_SESSION['compra']['empresa']."',
						'".$_SESSION['compr1']['codigospro'][$i]['codigo_producto']."',
						".$usuario.");";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
			}
			else if($_SESSION['compr1']['codigospro'][$i]['provee']<>NULL AND $_POST['eliminar'.$i]==NULL)//Para grabar
			{
				$contador2++;
				$query = "DELETE FROM compras_proveedores_productos
						WHERE codigo_proveedor_id=".$_SESSION['compr1']['provineleg']."
						AND empresa_id='".$_SESSION['compra']['empresa']."'
						AND codigo_producto='".$_SESSION['compr1']['codigospro'][$i]['codigo_producto']."';";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
			}
			$_POST['eliminar'.$i]='';
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
		}
		else
		{
			$this->frmError["MensajeError"].="<br>DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
		}
		$this->uno=1;
		$this->RelacionarProveConProduCompra();
		return true;
	}

	function BuscarDepartamentosCompra($empresa)//Busca los departamentos de la empresa
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT departamento,
				descripcion
				FROM departamentos
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

	function NombreUsuarioCompra()//Busca el nombre del usuario
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT usuario_id,
				nombre
				FROM system_usuarios
				WHERE usuario_id=".UserGetUID().";";
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

	function ValidarCrearRequisicionCompra()//Válida y guarda los datos de la solicitud
	{
		if($_SESSION['compr2']['usuarioide']==NULL)
		{
			$this->frmError["usuario"]=1;
		}
		if($_POST['departamen']==NULL)
		{
			$this->frmError["departamen"]=1;
		}
		if($_POST['razonsolic']==NULL)
		{
			$this->frmError["razonsolic"]=1;
		}
		if($_SESSION['compr2']['usuarioide']==NULL||
		$_POST['departamen']==NULL||$_POST['razonsolic']==NULL)
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno=1;
			$this->CrearRequisicionCompra();
			return true;
		}
		else
		{
			$de=explode(',',$_POST['departamen']);
			$_SESSION['compr2']['departaide']=$de[0];
			$_SESSION['compr2']['departades']=$de[1];
			$_SESSION['compr2']['razonsolco']=$_POST['razonsolic'];
			$_SESSION['compr2']['fecharequi']=date("d/m/Y");
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query = "SELECT NEXTVAL ('compras_requisiciones_requisicion_id_seq');";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			$_SESSION['compr2']['requisicio']=$resulta->fields[0];
			$query = "INSERT INTO compras_requisiciones
					(requisicion_id,
					empresa_id,
					departamento,
					usuario_id,
					fecha_requisicion,
					razon_solicitud,
					estado)
					VALUES
					(".$_SESSION['compr2']['requisicio'].",
					'".$_SESSION['compra']['empresa']."',
					'".$_SESSION['compr2']['departaide']."',
					 ".$_SESSION['compr2']['usuarioide'].",
					'".date("Y-m-d")."',
					'".$_SESSION['compr2']['razonsolco']."',
					'1');";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			$dbconn->CommitTrans();
			$this->CrearRequisicionProCompra();
			return true;
		}
	}

	function BuscarListaCotizarCompra($empresa,$requisicion)//Busca los productos con solicitud pendiente o por asignar una solicitud
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocomp'])
		{
			$codigo=$_REQUEST['codigocomp'];
			$busqueda1="AND B.codigo_producto LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descricomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['descricomp']);
			$busqueda2="AND UPPER(B.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['grupo'])
		{
			$codigo=STRTOUPPER($_REQUEST['grupo']);
			$busqueda3="AND B.grupo_id='$codigo'";
		}
		else
		{
			$busqueda3='';
		}
		if($_REQUEST['clasePr'])
		{
			$codigo=STRTOUPPER($_REQUEST['clasePr']);
			$busqueda4="AND B.clase_id='$codigo'";
		}
		else
		{
			$busqueda4='';
		}
		if($_REQUEST['subclase'])
		{
			$codigo=STRTOUPPER($_REQUEST['subclase']);
			$busqueda5="AND B.subclase_id='$codigo'";
		}
		else
		{
			$busqueda5='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM
					(
						SELECT B.codigo_producto,
						B.descripcion,
						B.porc_iva,
						B.contenido_unidad_venta,
						D.descripcion AS desunidad,
						C.cantidad
						FROM inventarios AS A,
						inventarios_productos AS B
						LEFT JOIN compras_requisiciones_detalle AS C ON
						(C.requisicion_id=".$requisicion."
						AND C.codigo_producto=B.codigo_producto),
						unidades AS D
						WHERE A.empresa_id='".$empresa."'
						AND A.codigo_producto=B.codigo_producto
						AND A.estado=1
						AND B.unidad_id=D.unidad_id
						$busqueda1
						$busqueda2
						$busqueda3
						$busqueda4
						$busqueda5
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
				SELECT B.codigo_producto,
				B.descripcion,
				B.porc_iva,
				B.contenido_unidad_venta,
				D.descripcion AS desunidad,
				C.cantidad
				FROM inventarios AS A,
				inventarios_productos AS B
				LEFT JOIN compras_requisiciones_detalle AS C ON
				(C.requisicion_id=".$requisicion."
				AND C.codigo_producto=B.codigo_producto),
				unidades AS D
				WHERE A.empresa_id='".$empresa."'
				AND A.codigo_producto=B.codigo_producto
				AND A.estado=1
				AND B.unidad_id=D.unidad_id
				$busqueda1
				$busqueda2
				$busqueda3
				$busqueda4
				$busqueda5
				ORDER BY B.codigo_producto
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

	function ValidarCrearRequisicionProCompra()//Válida el número de unidades que se requieren para la requisición
	{
		if($_SESSION['compr2']['requisicio']==NULL AND $_SESSION['compr2']['listaprodu']==NULL)
		{
			$this->frmError["MensajeError"]="ES POSIBLE QUE SE HAYAN PERDIDO ALGUNOS DATOS";
			$this->uno=1;
			$this->RequisicionesCompra();
			return true;
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$contador1=$contador2=0;
		$ciclo=sizeof($_SESSION['compr2']['listaprodu']);
		for($i=0;$i<$ciclo;$i++)
		{
			if(!empty($_POST['uncantidad'.$i]) AND is_numeric($_POST['uncantidad'.$i])==1)
			{
				$_SESSION['compr2']['sicancelar']=1;
				$can=doubleval($_POST['uncantidad'.$i]);
				if($can < 10000000 AND $_SESSION['compr2']['listaprodu'][$i]['cantidad']==NULL)
				{
					$contador1++;
					$query = "INSERT INTO compras_requisiciones_detalle
							(requisicion_id,
							codigo_producto,
							cantidad)
							VALUES
							(".$_SESSION['compr2']['requisicio'].",
							'".$_SESSION['compr2']['listaprodu'][$i]['codigo_producto']."',
							".$_POST['uncantidad'.$i].");";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollBackTrans();
						return false;
					}
				}
				else if($can < 10000000 AND $_SESSION['compr2']['listaprodu'][$i]['cantidad']<>NULL
				AND $can<>$_SESSION['compr2']['listaprodu'][$i]['cantidad'])
				{
					$contador2++;
					$query = "UPDATE compras_requisiciones_detalle SET
							cantidad=".$_POST['uncantidad'.$i]."
							WHERE requisicion_id=".$_SESSION['compr2']['requisicio']."
							AND codigo_producto='".$_SESSION['compr2']['listaprodu'][$i]['codigo_producto']."';";
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
		$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
				<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."";
		$this->uno=1;
		$this->CrearRequisicionProCompra();
		return true;
	}

	function BuscarModifRequisicionCompra($empresa)//Busca las requisiciones
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocomp'])
		{
			$codigo=$_REQUEST['codigocomp'];
			$busqueda1="AND A.requisicion_id LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['nombrecomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['nombrecomp']);
			$busqueda2="AND UPPER(B.nombre) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['descricomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['descricomp']);
			$busqueda3="AND UPPER(A.departamento)='$codigo'";
		}
		else
		{
			$busqueda3='';
		}
		if($_REQUEST['fecharcomp'])
		{
			$fecha=explode('/',$_REQUEST['fecharcomp']);
			if(checkdate($fecha[1], $fecha[0], $fecha[2])==1)
			{
				$codigo=$fecha[2].'-'.$fecha[1].'-'.$fecha[0];
				$busqueda4="AND A.fecha_requisicion>='$codigo'";
			}
			else
			{
				$busqueda4='';
			}
		}
		else
		{
			$busqueda4='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM
					(
						SELECT A.requisicion_id,
						A.departamento,
						A.usuario_id,
						A.fecha_requisicion,
						A.razon_solicitud,
						B.nombre,
						C.descripcion,
						(
							SELECT COUNT(D.codigo_producto)
							FROM compras_requisiciones_detalle AS D
							WHERE D.requisicion_id=A.requisicion_id
						) AS cantidad
						FROM compras_requisiciones AS A,
						system_usuarios AS B,
						departamentos AS C
						WHERE A.empresa_id='".$empresa."'
						AND A.estado=1
						AND A.usuario_id=B.usuario_id
						AND A.empresa_id=C.empresa_id
						AND A.departamento=C.departamento
						$busqueda1
						$busqueda2
						$busqueda3
						$busqueda4
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
				SELECT A.requisicion_id,
				A.departamento,
				A.usuario_id,
				A.fecha_requisicion,
				A.razon_solicitud,
				B.nombre,
				C.descripcion,
				(
					SELECT COUNT(D.codigo_producto)
					FROM compras_requisiciones_detalle AS D
					WHERE D.requisicion_id=A.requisicion_id
				) AS cantidad
				FROM compras_requisiciones AS A,
				system_usuarios AS B,
				departamentos AS C
				WHERE A.empresa_id='".$empresa."'
				AND A.estado=1
				AND A.usuario_id=B.usuario_id
				AND A.empresa_id=C.empresa_id
				AND A.departamento=C.departamento
				$busqueda1
				$busqueda2
				$busqueda3
				$busqueda4
				ORDER BY A.fecha_requisicion DESC, A.requisicion_id DESC
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

	function ValidarModifRequisicionProCompra()//Válida las modificaciones que se le hagan a la requisición
	{
		if($_SESSION['compr2']['requisicio']==NULL AND $_SESSION['compr2']['listaprodu']==NULL)
		{
			$this->frmError["MensajeError"]="ES POSIBLE QUE SE HAYAN PERDIDO ALGUNOS DATOS";
			$this->uno=1;
			$this->RequisicionesCompra();
			return true;
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$contador1=$contador2=$contador3=0;
		$ciclo=sizeof($_SESSION['compr2']['listaprodu']);
		for($i=0;$i<$ciclo;$i++)
		{
			if(!empty($_POST['uncantidad'.$i]) AND is_numeric($_POST['uncantidad'.$i])==1)
			{
				$can=doubleval($_POST['uncantidad'.$i]);
				if($can < 10000000 AND $_SESSION['compr2']['listaprodu'][$i]['cantidad']==NULL)
				{
					$_SESSION['compr2']['totalguare']++;
					$contador1++;
					$query = "INSERT INTO compras_requisiciones_detalle
							(requisicion_id,
							codigo_producto,
							cantidad)
							VALUES
							(".$_SESSION['compr2']['requisicio'].",
							'".$_SESSION['compr2']['listaprodu'][$i]['codigo_producto']."',
							".$_POST['uncantidad'.$i].");";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollBackTrans();
						return false;
					}
				}
				else if($can < 10000000 AND $_SESSION['compr2']['listaprodu'][$i]['cantidad']<>NULL
				AND $can<>$_SESSION['compr2']['listaprodu'][$i]['cantidad'])
				{
					$contador2++;
					$query = "UPDATE compras_requisiciones_detalle SET
							cantidad=".$_POST['uncantidad'.$i]."
							WHERE requisicion_id=".$_SESSION['compr2']['requisicio']."
							AND codigo_producto='".$_SESSION['compr2']['listaprodu'][$i]['codigo_producto']."';";
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
			else if(empty($_POST['uncantidad'.$i]) AND $_SESSION['compr2']['totalguare']>1
			AND $_SESSION['compr2']['listaprodu'][$i]['cantidad']<>NULl)
			{
				$_SESSION['compr2']['totalguare']--;
				$contador3++;
				$query = "DELETE FROM compras_requisiciones_detalle
						WHERE requisicion_id=".$_SESSION['compr2']['requisicio']."
						AND codigo_producto='".$_SESSION['compr2']['listaprodu'][$i]['codigo_producto']."';";
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
		$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
				<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
				<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		$this->uno=1;
		$this->ModifRequisicionProCompra();
		return true;
	}

	function BuscarRazonCancelarRequiCompra($empresa,$requisicion)//Busca el detalle de una requisición
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT B.codigo_producto,
				B.descripcion,
				B.porc_iva,
				B.contenido_unidad_venta,
				C.cantidad,
				D.descripcion AS desunidad
				FROM inventarios AS A,
				inventarios_productos AS B,
				compras_requisiciones_detalle AS C,
				unidades AS D
				WHERE A.empresa_id='".$empresa."'
				AND C.requisicion_id=".$requisicion."
				AND A.codigo_producto=B.codigo_producto
				AND C.codigo_producto=B.codigo_producto
				AND B.unidad_id=D.unidad_id
				ORDER BY B.codigo_producto;";
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

	function ValidarRazonCancelarRequiCompra()//
	{
		if($_POST['justifican']==NULL)
		{
			$this->frmError["justifican"]=1;
			$this->frmError["MensajeError"]="PARA ANULAR ESTA REQUISICIÓN ES NECESARIO UNA JUSTIFICACIÓN";
			$this->uno=1;
			$this->RazonCancelarRequiCompra();
			return true;
		}
		else
		{
			list($dbconn) = GetDBconn();
			$query = "UPDATE compras_requisiciones SET
					observacion='".$_POST['justifican']."',
					estado='2'
					WHERE requisicion_id=".$_SESSION['compr2']['requisicio']."
					AND empresa_id='".$_SESSION['compra']['empresa']."';";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$query = "UPDATE compras_requisiciones_detalle SET
					estado='0'
					WHERE requisicion_id=".$_SESSION['compr2']['requisicio'].";";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$this->frmError["MensajeError"]="LA REQUISICIÓN No. ".$_SESSION['compr2']['requisicio']." HA SIDO ANULADA CORRECTAMENTE";
			$this->uno=1;
			$this->CancelarRequisicionCompra();
			return true;
		}
	}

	function BuscarConsultarRequisicionCompra($empresa)//Busca todas las requisiciones
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocomp'])
		{
			$codigo=$_REQUEST['codigocomp'];
			$busqueda1="AND A.requisicion_id LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['nombrecomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['nombrecomp']);
			$busqueda2="AND UPPER(B.nombre) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['descricomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['descricomp']);
			$busqueda3="AND UPPER(A.departamento)='$codigo'";
		}
		else
		{
			$busqueda3='';
		}
		if($_REQUEST['fecharcomp'])
		{
			$fecha=explode('/',$_REQUEST['fecharcomp']);
			if(checkdate($fecha[1], $fecha[0], $fecha[2])==1)
			{
				$codigo=$fecha[2].'-'.$fecha[1].'-'.$fecha[0];
				$busqueda4="AND A.fecha_requisicion>='$codigo'";
			}
			else
			{
				$busqueda4='';
			}
		}
		else
		{
			$busqueda4='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM
					(
						SELECT A.requisicion_id,
						A.departamento,
						A.usuario_id,
						A.fecha_requisicion,
						A.razon_solicitud,
						A.observacion,
						A.estado,
						B.nombre,
						C.descripcion,
						(
							SELECT COUNT(D.codigo_producto)
							FROM compras_requisiciones_detalle AS D
							WHERE D.requisicion_id=A.requisicion_id
						) AS cantidad
						FROM compras_requisiciones AS A,
						system_usuarios AS B,
						departamentos AS C
						WHERE A.empresa_id='".$empresa."'
						AND A.usuario_id=B.usuario_id
						AND A.empresa_id=C.empresa_id
						AND A.departamento=C.departamento
						$busqueda1
						$busqueda2
						$busqueda3
						$busqueda4
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
				SELECT A.requisicion_id,
				A.departamento,
				A.usuario_id,
				A.fecha_requisicion,
				A.razon_solicitud,
				A.observacion,
				A.estado,
				B.nombre,
				C.descripcion,
				(
					SELECT COUNT(D.codigo_producto)
					FROM compras_requisiciones_detalle AS D
					WHERE D.requisicion_id=A.requisicion_id
				) AS cantidad
				FROM compras_requisiciones AS A,
				system_usuarios AS B,
				departamentos AS C
				WHERE A.empresa_id='".$empresa."'
				AND A.usuario_id=B.usuario_id
				AND A.empresa_id=C.empresa_id
				AND A.departamento=C.departamento
				$busqueda1
				$busqueda2
				$busqueda3
				$busqueda4
				ORDER BY A.fecha_requisicion DESC, A.requisicion_id DESC
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

	function BuscarCrearCotizacionCompra($empresa)//Busca los datos para armar la matriz de solicitud de cotizaciones
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT DISTINCT A.codigo_producto,
				(
					SELECT SUM(B.cantidad)
					FROM compras_requisiciones_detalle AS B,
					compras_requisiciones AS C
					WHERE C.empresa_id='".$empresa."'
					AND C.estado='1'
					AND C.requisicion_id=B.requisicion_id
					AND B.codigo_producto=A.codigo_producto
					AND B.estado='1'
				) AS cantotal,
				D.descripcion,
				F.codigo_proveedor_id,
				G.tipo_id_tercero,
				G.tercero_id,
				G.dias_gracia,
				G.tiempo_entrega,
				H.nombre_tercero
				FROM compras_requisiciones_detalle AS A,
				compras_requisiciones AS C,
				inventarios_productos AS D,
				compras_proveedores_productos AS F,
				terceros_proveedores AS G,
				terceros AS H
				WHERE C.empresa_id='".$empresa."'
				AND C.estado='1'
				AND C.requisicion_id=A.requisicion_id
				AND A.codigo_producto=D.codigo_producto
				AND A.estado='1'
				AND C.empresa_id=F.empresa_id
				AND A.codigo_producto=F.codigo_producto
				AND F.codigo_proveedor_id=G.codigo_proveedor_id
				AND G.estado='1'
				AND G.tipo_id_tercero=H.tipo_id_tercero
				AND G.tercero_id=H.tercero_id
				ORDER BY H.nombre_tercero, A.codigo_producto;";
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

	function BuscarCrearCotizacionCompra2($empresa)//Busca los datos para armar la matriz de solicitud de cotizaciones
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT DISTINCT A.codigo_producto,
				(
					SELECT SUM(B.cantidad)
					FROM compras_requisiciones_detalle AS B,
					compras_requisiciones AS C
					WHERE C.empresa_id='".$empresa."'
					AND C.estado='1'
					AND C.requisicion_id=B.requisicion_id
					AND B.codigo_producto=A.codigo_producto
				) AS cantotal,
				D.descripcion,
				F.codigo_proveedor_id
				FROM compras_requisiciones_detalle AS A,
				compras_requisiciones AS C,
				inventarios_productos AS D
				LEFT JOIN compras_proveedores_productos AS F ON
				(F.empresa_id='".$empresa."'
				AND D.codigo_producto=F.codigo_producto)
				WHERE C.empresa_id='".$empresa."'
				AND C.estado='1'
				AND C.requisicion_id=A.requisicion_id
				AND A.codigo_producto=D.codigo_producto
				AND F.codigo_proveedor_id IS NULL
				ORDER BY A.codigo_producto;";
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

	function BuscarCantidadProductoCompra($empresa)//Busca los datos para armar la matriz de solicitud de cotizaciones
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT DISTINCT A.codigo_producto,
				(
					SELECT SUM(B.cantidad)
					FROM compras_requisiciones_detalle AS B,
					compras_requisiciones AS C
					WHERE C.empresa_id='".$empresa."'
					AND C.estado='1'
					AND C.requisicion_id=B.requisicion_id
					AND B.codigo_producto=A.codigo_producto
				) AS cantotal,
				0 AS insertar,
				E.codigo_producto AS temporal,
				E.cantidad_comprar
				FROM compras_requisiciones AS C,
				compras_requisiciones_detalle AS A
				LEFT JOIN compras_cantidad_productos AS E ON
				(A.codigo_producto=E.codigo_producto)
				WHERE C.empresa_id='".$empresa."'
				AND C.estado='1'
				AND C.requisicion_id=A.requisicion_id
				ORDER BY A.codigo_producto;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[$resulta->fields[0]]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarCrearCotizacionCompra()//Válida y guarda las cotizaciones que se solicitan
	{
		$produtempo=$this->BuscarCantidadProductoCompra($_SESSION['compra']['empresa']);
		list($dbconn) = GetDBconn();
		$usuario=UserGetUID();
		$dbconn->BeginTrans();
		$contador1=0;
		$ciclo=sizeof($_SESSION['compr2']['bsolicitud']);
		for($i=0;$i<$ciclo;)
		{
			$k=$i;
			$insertar=0;
			while($_SESSION['compr2']['bsolicitud'][$i]['codigo_proveedor_id']==$_SESSION['compr2']['bsolicitud'][$k]['codigo_proveedor_id'])
			{
				if($_POST['solicitar'.$k]<>NULL)
				{
					$insertar=1;
				}
				$k++;
			}
			if($insertar==1)
			{
				$contador1++;
				$query = "SELECT NEXTVAL ('compras_cotizaciones_cotizacion_id_seq');";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollBackTrans();
				}
				$indice=$resulta->fields[0];
				$query = "INSERT INTO compras_cotizaciones
						(cotizacion_id,
						codigo_proveedor_id,
						empresa_id,
						fecha_cotizacion,
						estado,
						usuario_id)
						VALUES
						(".$indice.",
						".$_SESSION['compr2']['bsolicitud'][$i]['codigo_proveedor_id'].",
						'".$_SESSION['compra']['empresa']."',
						'".date("Y-m-d")."',
						'1',
						".$usuario.");";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
				else
				{
					$k=$i;
					while($_SESSION['compr2']['bsolicitud'][$i]['codigo_proveedor_id']==$_SESSION['compr2']['bsolicitud'][$k]['codigo_proveedor_id'])
					{
						if($_POST['solicitar'.$k]<>NULL)
						{
							$query = "INSERT INTO compras_cotizaciones_detalle
									(cotizacion_id,
									codigo_producto,
									cantidad)
									VALUES
									(".$indice.",
									'".$_SESSION['compr2']['bsolicitud'][$k]['codigo_producto']."',
									".$_SESSION['compr2']['bsolicitud'][$k]['cantotal'].");";
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollBackTrans();
								$contador1=0;
								return false;
							}
							if($produtempo[$_SESSION['compr2']['bsolicitud'][$k]['codigo_producto']]['codigo_producto']<>NULL
							AND $produtempo[$_SESSION['compr2']['bsolicitud'][$k]['codigo_producto']]['insertar']==0)
							{
								if($produtempo[$_SESSION['compr2']['bsolicitud'][$k]['codigo_producto']]['temporal']==NULL)
								{
									$query = "INSERT INTO compras_cantidad_productos
											(codigo_producto,
											empresa_id,
											cantidad_comprar)
											VALUES
											('".$_SESSION['compr2']['bsolicitud'][$k]['codigo_producto']."',
											'".$_SESSION['compra']['empresa']."',
											".$_SESSION['compr2']['bsolicitud'][$k]['cantotal'].");";
									$resulta = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollBackTrans();
										$contador1=0;
										return false;
									}
								}
								else if($produtempo[$_SESSION['compr2']['bsolicitud'][$k]['codigo_producto']]['temporal']<>NULL)
								{
									$valornuevo=$produtempo[$_SESSION['compr2']['bsolicitud'][$k]['codigo_producto']]['cantotal']+$_SESSION['compr2']['bsolicitud'][$k]['cantotal'];
									$query = "UPDATE compras_cantidad_productos SET
											cantidad_comprar=".$valornuevo."
											WHERE codigo_producto='".$_SESSION['compr2']['bsolicitud'][$k]['codigo_producto']."'
											AND empresa_id='".$_SESSION['compra']['empresa']."';";
									$resulta = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollBackTrans();
										$contador1=0;
										return false;
									}
								}
								$produtempo[$_SESSION['compr2']['bsolicitud'][$k]['codigo_producto']]['insertar']=1;
							}
						}
						$k++;
					}
				}
			}
			$i=$k;
		}
		$dbconn->CommitTrans();
		if($contador1<>0)
		{
			$query = "UPDATE compras_requisiciones SET
					estado='0'
					WHERE estado='1';";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				$contador1=0;
				return false;
			}
		}
		$this->frmError["MensajeError"]="SOLICITUDES DE COTIZACIONES GUARDADAS CORRECTAMENTE: ".$contador1."";
		$this->uno=1;
		$this->CotizacionesCompra();//PENDIENTE POR AGREGAR: aqui debera ir a la otra pantalla
		return true;
	}

	function BuscarModifCotizacionCompra($empresa)//Busca las cotizaciones activas
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocomp'])
		{
			$codigo=$_REQUEST['codigocomp'];
			$busqueda1="AND A.cotizacion_id LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['nombrecomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['nombrecomp']);
			$busqueda2="AND UPPER(C.nombre_tercero) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['fecharcomp'])
		{
			$fecha=explode('/',$_REQUEST['fecharcomp']);
			if(checkdate($fecha[1], $fecha[0], $fecha[2])==1)
			{
				$codigo=$fecha[2].'-'.$fecha[1].'-'.$fecha[0];
				$busqueda3="AND A.fecha_cotizacion>='$codigo'";
			}
			else
			{
				$busqueda3='';
			}
		}
		else
		{
			$busqueda3='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM
					(
						SELECT A.cotizacion_id,
						A.codigo_proveedor_id,
						A.fecha_cotizacion,
						B.tipo_id_tercero,
						B.tercero_id,
						B.dias_gracia,
						B.tiempo_entrega,
						C.nombre_tercero,
						(
							SELECT COUNT(D.codigo_producto)
							FROM compras_cotizaciones_detalle AS D
							WHERE D.cotizacion_id=A.cotizacion_id
						) AS cantidad
						FROM compras_cotizaciones AS A,
						terceros_proveedores AS B,
						terceros AS C
						WHERE A.empresa_id='".$empresa."'
						AND A.estado='1'
						AND A.codigo_proveedor_id=B.codigo_proveedor_id
						AND B.estado='1'
						AND B.tipo_id_tercero=C.tipo_id_tercero
						AND B.tercero_id=C.tercero_id
						$busqueda1
						$busqueda2
						$busqueda3
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
				SELECT A.cotizacion_id,
				A.codigo_proveedor_id,
				A.fecha_cotizacion,
				B.tipo_id_tercero,
				B.tercero_id,
				B.dias_gracia,
				B.tiempo_entrega,
				C.nombre_tercero,
				(
					SELECT COUNT(D.codigo_producto)
					FROM compras_cotizaciones_detalle AS D
					WHERE D.cotizacion_id=A.cotizacion_id
				) AS cantidad
				FROM compras_cotizaciones AS A,
				terceros_proveedores AS B,
				terceros AS C
				WHERE A.empresa_id='".$empresa."'
				AND A.estado='1'
				AND A.codigo_proveedor_id=B.codigo_proveedor_id
				AND B.estado='1'
				AND B.tipo_id_tercero=C.tipo_id_tercero
				AND B.tercero_id=C.tercero_id
				$busqueda1
				$busqueda2
				$busqueda3
				ORDER BY A.fecha_cotizacion DESC, A.cotizacion_id DESC
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

	function BuscarModifCotizacionProCompra($empresa,$cotizacion)//Busca los productos para agregar y eliminar
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocomp'])
		{
			$codigo=$_REQUEST['codigocomp'];
			$busqueda1="AND B.codigo_producto LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descricomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['descricomp']);
			$busqueda2="AND UPPER(B.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['grupo'])
		{
			$codigo=STRTOUPPER($_REQUEST['grupo']);
			$busqueda3="AND B.grupo_id='$codigo'";
		}
		else
		{
			$busqueda3='';
		}
		if($_REQUEST['clasePr'])
		{
			$codigo=STRTOUPPER($_REQUEST['clasePr']);
			$busqueda4="AND B.clase_id='$codigo'";
		}
		else
		{
			$busqueda4='';
		}
		if($_REQUEST['subclase'])
		{
			$codigo=STRTOUPPER($_REQUEST['subclase']);
			$busqueda5="AND B.subclase_id='$codigo'";
		}
		else
		{
			$busqueda5='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM
					(
						SELECT B.codigo_producto,
						B.descripcion,
						B.porc_iva,
						B.contenido_unidad_venta,
						D.descripcion AS desunidad,
						C.cantidad
						FROM inventarios AS A,
						inventarios_productos AS B
						LEFT JOIN compras_cotizaciones_detalle AS C ON
						(C.cotizacion_id=".$cotizacion."
						AND C.codigo_producto=B.codigo_producto),
						unidades AS D
						WHERE A.empresa_id='".$empresa."'
						AND A.codigo_producto=B.codigo_producto
						AND A.estado=1
						AND B.unidad_id=D.unidad_id
						$busqueda1
						$busqueda2
						$busqueda3
						$busqueda4
						$busqueda5
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
				SELECT B.codigo_producto,
				B.descripcion,
				B.porc_iva,
				B.contenido_unidad_venta,
				D.descripcion AS desunidad,
				C.cantidad
				FROM inventarios AS A,
				inventarios_productos AS B
				LEFT JOIN compras_cotizaciones_detalle AS C ON
				(C.cotizacion_id=".$cotizacion."
				AND C.codigo_producto=B.codigo_producto),
				unidades AS D
				WHERE A.empresa_id='".$empresa."'
				AND A.codigo_producto=B.codigo_producto
				AND A.estado=1
				AND B.unidad_id=D.unidad_id
				$busqueda1
				$busqueda2
				$busqueda3
				$busqueda4
				$busqueda5
				ORDER BY B.codigo_producto
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

	function ValidarModifCotizacionProCompra()//Válida y guarda las cantidades para los productos
	{
		if($_SESSION['compr2']['cotizacion']==NULL AND $_SESSION['compr2']['cotiproduc']==NULL)
		{
			$this->frmError["MensajeError"]="ES POSIBLE QUE SE HAYAN PERDIDO ALGUNOS DATOS";
			$this->uno=1;
			$this->CotizacionesCompra();
			return true;
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$contador1=$contador2=$contador3=0;
		$ciclo=sizeof($_SESSION['compr2']['cotiproduc']);
		for($i=0;$i<$ciclo;$i++)
		{
			if(!empty($_POST['uncantidad'.$i]) AND is_numeric($_POST['uncantidad'.$i])==1)
			{
				$can=doubleval($_POST['uncantidad'.$i]);
				if($can < 10000000 AND $_SESSION['compr2']['cotiproduc'][$i]['cantidad']==NULL)
				{
					$_SESSION['compr2']['totalguaco']++;
					$contador1++;
					$query = "INSERT INTO compras_cotizaciones_detalle
							(cotizacion_id,
							codigo_producto,
							cantidad)
							VALUES
							(".$_SESSION['compr2']['cotizacion'].",
							'".$_SESSION['compr2']['cotiproduc'][$i]['codigo_producto']."',
							".$_POST['uncantidad'.$i].");";
					$resulta = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollBackTrans();
						return false;
					}
				}
				else if($can < 10000000 AND $_SESSION['compr2']['cotiproduc'][$i]['cantidad']<>NULL
				AND $can<>$_SESSION['compr2']['cotiproduc'][$i]['cantidad'])
				{
					$contador2++;
					$query = "UPDATE compras_cotizaciones_detalle SET
							cantidad=".$_POST['uncantidad'.$i]."
							WHERE cotizacion_id=".$_SESSION['compr2']['cotizacion']."
							AND codigo_producto='".$_SESSION['compr2']['cotiproduc'][$i]['codigo_producto']."';";
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
			else if(empty($_POST['uncantidad'.$i]) AND $_SESSION['compr2']['totalguaco']>1
			AND $_SESSION['compr2']['cotiproduc'][$i]['cantidad']<>NULl)
			{
				$_SESSION['compr2']['totalguaco']--;
				$contador3++;
				$query = "DELETE FROM compras_cotizaciones_detalle
						WHERE cotizacion_id=".$_SESSION['compr2']['cotizacion']."
						AND codigo_producto='".$_SESSION['compr2']['cotiproduc'][$i]['codigo_producto']."';";
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
		$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
				<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."
				<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador3."";
		$this->uno=1;
		$this->ModifCotizacionProCompra();
		return true;
	}

	function BuscarRazonCancelarCotizCompra($empresa,$cotizacion)//Busca los productos para agregar y eliminar
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT B.codigo_producto,
				B.descripcion,
				B.porc_iva,
				B.contenido_unidad_venta,
				D.descripcion AS desunidad,
				C.cantidad
				FROM inventarios AS A,
				inventarios_productos AS B,
				compras_cotizaciones_detalle AS C,
				unidades AS D
				WHERE A.empresa_id='".$empresa."'
				AND C.cotizacion_id=".$cotizacion."
				AND A.codigo_producto=B.codigo_producto
				AND B.unidad_id=D.unidad_id
				AND C.codigo_producto=B.codigo_producto
				ORDER BY B.codigo_producto;";
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

	function ValidarRazonCancelarCotizCompra()//Válida la anulación de una solicitud de cotización
	{
		if($_POST['justifican']==NULL)
		{
			$this->frmError["justifican"]=1;
			$this->frmError["MensajeError"]="PARA ANULAR ESTA COTIZACIÓN ES NECESARIO UNA JUSTIFICACIÓN";
			$this->uno=1;
			$this->RazonCancelarCotizCompra();
			return true;
		}
		else
		{
			list($dbconn) = GetDBconn();
			$query = "UPDATE compras_cotizaciones SET
					observacion='".$_POST['justifican']."',
					estado='2'
					WHERE cotizacion_id=".$_SESSION['compr2']['cotizacion']."
					AND empresa_id='".$_SESSION['compra']['empresa']."';";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$this->frmError["MensajeError"]="LA SOLICITUD DE COTIZACIÓN No. ".$_SESSION['compr2']['cotizacion']." HA SIDO CENCELADA CORRECTAMENTE";
			$this->uno=1;
			$this->CancelarCotizacionCompra();
			return true;
		}
	}

	function BuscarConsultarCotizacionCompra($empresa)//Busca
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocomp'])
		{
			$codigo=$_REQUEST['codigocomp'];
			$busqueda1="AND A.cotizacion_id LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['nombrecomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['nombrecomp']);
			$busqueda2="AND UPPER(C.nombre_tercero) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['fecharcomp'])
		{
			$fecha=explode('/',$_REQUEST['fecharcomp']);
			if(checkdate($fecha[1], $fecha[0], $fecha[2])==1)
			{
				$codigo=$fecha[2].'-'.$fecha[1].'-'.$fecha[0];
				$busqueda3="AND A.fecha_cotizacion>='$codigo'";
			}
			else
			{
				$busqueda3='';
			}
		}
		else
		{
			$busqueda3='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM
					(
						SELECT A.cotizacion_id,
						A.codigo_proveedor_id,
						A.fecha_cotizacion,
						A.observacion,
						A.estado,
						A.fecha_recibido,
						B.tipo_id_tercero,
						B.tercero_id,
						C.nombre_tercero,
						(
							SELECT COUNT(D.codigo_producto)
							FROM compras_cotizaciones_detalle AS D
							WHERE D.cotizacion_id=A.cotizacion_id
						) AS cantidad
						FROM compras_cotizaciones AS A,
						terceros_proveedores AS B,
						terceros AS C
						WHERE A.empresa_id='".$empresa."'
						AND A.codigo_proveedor_id=B.codigo_proveedor_id
						AND B.tipo_id_tercero=C.tipo_id_tercero
						AND B.tercero_id=C.tercero_id
						$busqueda1
						$busqueda2
						$busqueda3
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
				SELECT A.cotizacion_id,
				A.codigo_proveedor_id,
				A.fecha_cotizacion,
				A.observacion,
				A.estado,
				A.fecha_recibido,
				B.tipo_id_tercero,
				B.tercero_id,
				C.nombre_tercero,
				(
					SELECT COUNT(D.codigo_producto)
					FROM compras_cotizaciones_detalle AS D
					WHERE D.cotizacion_id=A.cotizacion_id
				) AS cantidad
				FROM compras_cotizaciones AS A,
				terceros_proveedores AS B,
				terceros AS C
				WHERE A.empresa_id='".$empresa."'
				AND A.codigo_proveedor_id=B.codigo_proveedor_id
				AND B.tipo_id_tercero=C.tipo_id_tercero
				AND B.tercero_id=C.tercero_id
				$busqueda1
				$busqueda2
				$busqueda3
				ORDER BY A.fecha_cotizacion DESC, A.cotizacion_id DESC
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

	function BuscarRecibirPreciosCotizacionCompra($empresa,$cotizacion,$proveedor)//Busca los productos para agregar y eliminar
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT B.codigo_producto,
				B.descripcion,
				B.porc_iva,
				B.contenido_unidad_venta,
				D.descripcion AS desunidad,
				C.cantidad,
				E.codigo_producto AS cotizado,
				E.codigo_producto_proveedor
				FROM inventarios AS A,
				inventarios_productos AS B,
				compras_cotizaciones_detalle AS C
				LEFT JOIN compras_proveedores_productos_cotizaciones AS E ON
				(E.codigo_proveedor_id=".$proveedor."
				AND E.empresa_id='".$empresa."'
				AND C.codigo_producto=E.codigo_producto),
				unidades AS D
				WHERE A.empresa_id='".$empresa."'
				AND C.cotizacion_id=".$cotizacion."
				AND A.codigo_producto=B.codigo_producto
				AND B.unidad_id=D.unidad_id
				AND C.codigo_producto=B.codigo_producto
				ORDER BY B.codigo_producto;";
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

	function ValidarRecibirPreciosCotizacionCompra()//Función que válida los precios y fechas de los productos cotizados
	{
		if($_SESSION['compr2']['preciospro']==NULL)
		{
			$this->frmError["MensajeError"]="NO SE GUARDARÓN DATOS";
			$this->uno=1;
			$this->RecibirCotizacionCompra();
			return true;
		}
		else if($_POST['cotizacprv']==NULL)
		{
			$this->frmError["cotizacprv"]=1;
			$this->frmError["MensajeError"]="FALTA EL NÚMERO DE LA COTIZACIÓN";
			$this->uno=1;
			$this->RecibirPreciosCotizacionCompra();
			return true;
		}
		else
		{
			if($_POST['fechalista']<>NULL)
			{
				$var=explode('/',$_POST['fechalista']);
				$day=$var[0];
				$mon=$var[1];
				$yea=$var[2];
				if(checkdate($mon, $day, $yea)==0)
				{
					$_POST['fechalista']='';
					$this->frmError["MensajeError"]="FECHA DE LISTA CON FORMATO NO VÁLIDO";
				}
				else
				{
					$fech=explode('-',date("Y-m-d"));
					if(date("Y-m-d", mktime(1,1,1,$fech[1],$fech[2]+3,$fech[0])) < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
					{
						$_POST['fechalista']='';
						$this->frmError["MensajeError"]="FECHA DE LISTA MAYOR A TRES (3) DÍAS CON RESPECTO A LA FECHA DE HOY";
					}
					else
					{
						$fechalista=$yea.'-'.$mon.'-'.$day;
						$_POST['fechalista']=$fechalista;
					}
				}
			}
			if($_POST['fechavigen']<>NULL)
			{
				$var=explode('/',$_POST['fechavigen']);
				$day=$var[0];
				$mon=$var[1];
				$yea=$var[2];
				if(checkdate($mon, $day, $yea)==0)
				{
					$_POST['fechavigen']='';
					if($this->frmError["MensajeError"]==NULL)
					{
						$this->frmError["MensajeError"]="FECHA DE VIGENCIA CON FORMATO NO VÁLIDO";
					}
					else
					{
						$this->frmError["MensajeError"].="<br>FECHA DE VIGENCIA CON FORMATO NO VÁLIDO";
					}
				}
				else
				{
					$fech=date("Y-m-d");
					if($fech >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
					{
						$_POST['fechavigen']='';
						if($this->frmError["MensajeError"]==NULL)
						{
							$this->frmError["MensajeError"]="FECHA DE VIGENCIA MENOR O IGUAL A LA DEL DÍA DE HOY";
						}
						else
						{
							$this->frmError["MensajeError"].="<br>FECHA DE VIGENCIA MENOR O IGUAL A LA DEL DÍA DE HOY";
						}
					}
					else
					{
						$fechavigen=$yea.'-'.$mon.'-'.$day;
						$_POST['fechavigen']=$fechavigen;
					}
				}
			}
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$usuario=UserGetUID();
			$contador1=$contador2=0;
			$ciclo=sizeof($_SESSION['compr2']['preciospro']);
			for($i=0;$i<$ciclo;$i++)
			{
				if($_POST['valor'.$i]<>NULL)
				{
					if(is_numeric($_POST['valor'.$i])==1)
					{
						$valor=doubleval($_POST['valor'.$i]);
						if($valor >= 10000000000000)
						{
							$_POST['valor'.$i]='';
						}
						else
						{
							$_POST['valor'.$i]=$valor;
						}
					}
					else
					{
						$_POST['valor'.$i]='';
					}
				}
				if($_POST['fechalista'.$i]<>NULL)
				{
					$var=explode('/',$_POST['fechalista'.$i]);
					$day=$var[0];
					$mon=$var[1];
					$yea=$var[2];
					if(checkdate($mon, $day, $yea)==0)
					{
						$_POST['fechalista'.$i]='';
					}
					else
					{
						//$fech=date("Y-m-d");
						//if($fech < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
						$fech=explode('-',date("Y-m-d"));
						if(date("Y-m-d", mktime(1,1,1,$fech[1],$fech[2]+3,$fech[0])) < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
						{
							$_POST['fechalista'.$i]='';
						}
						else
						{
							$_POST['fechalista'.$i]=$yea.'-'.$mon.'-'.$day;
						}
					}
				}
				else
				{
					if($fechalista<>NULL AND $_POST['fechalista'.$i]==NULL)
					{
						$_POST['fechalista'.$i]=$fechalista;
					}
				}
				if($_POST['fechavigen'.$i]<>NULL)
				{
					$var=explode('/',$_POST['fechavigen'.$i]);
					$day=$var[0];
					$mon=$var[1];
					$yea=$var[2];
					if(checkdate($mon, $day, $yea)==0)
					{
						$_POST['fechavigen'.$i]='';
					}
					else
					{
						$fech=date("Y-m-d");
						if($fech >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
						{
							$_POST['fechavigen'.$i]='';
						}
						else
						{
							$_POST['fechavigen'.$i]=$yea.'-'.$mon.'-'.$day;
						}
					}
				}
				else
				{
					if($fechavigen<>NULL AND $_POST['fechavigen'.$i]==NULL)
					{
						$_POST['fechavigen'.$i]=$fechavigen;
					}
				}
				if($_POST['valor'.$i]<>NULL AND $_POST['fechalista'.$i]<>NULL)
				{
					if($_POST['fechavigen'.$i]==NULL)
					{
						$fechavig="NULL";
					}
					else
					{
						$fechavig="'".$_POST['fechavigen'.$i]."'";
					}
					if($_SESSION['compr2']['preciospro'][$i]['cotizado']==NULL)//Para grabar
					{
						$contador1++;
						$query = "INSERT INTO compras_proveedores_productos_cotizaciones
								(codigo_proveedor_id,
								empresa_id,
								codigo_producto,
								valor,
								fecha_lista,
								fecha_vigencia,
								codigo_producto_proveedor,
								numero_cotizacion,
								usuario_id)
								VALUES
								(".$_SESSION['compr2']['codigoprov'].",
								'".$_SESSION['compra']['empresa']."',
								'".$_SESSION['compr2']['preciospro'][$i]['codigo_producto']."',
								".$_POST['valor'.$i].",
								'".$_POST['fechalista'.$i]."',
								$fechavig,
								'".$_POST['codiprdprv'.$i]."',
								'".$_POST['cotizacprv']."',
								".$usuario.");";
						$dbconn->Execute($query);//'".$_POST['fechavigen'.$i]."'
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollBackTrans();
							return false;
						}
					}
					else if($_SESSION['compr2']['preciospro'][$i]['cotizado']<>NULL)//Para modificar
					{
						$contador2++;
						$query = "UPDATE compras_proveedores_productos_cotizaciones SET
								valor=".$_POST['valor'.$i].",
								fecha_lista='".$_POST['fechalista'.$i]."',
								fecha_vigencia=$fechavig,
								codigo_producto_proveedor='".$_POST['codiprdprv'.$i]."',
								numero_cotizacion='".$_POST['cotizacprv']."'
								WHERE codigo_proveedor_id=".$_SESSION['compr2']['codigoprov']."
								AND empresa_id='".$_SESSION['compra']['empresa']."'
								AND codigo_producto='".$_SESSION['compr2']['preciospro'][$i]['codigo_producto']."';";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollBackTrans();
							return false;
						}
					}
					$query = "UPDATE compras_cotizaciones_detalle SET
							sw_cotizado='1'
							WHERE cotizacion_id=".$_SESSION['compr2']['cotizacion']."
							AND codigo_producto='".$_SESSION['compr2']['preciospro'][$i]['codigo_producto']."';";
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
			$query = "UPDATE compras_cotizaciones SET
					estado='0',
					fecha_recibido='".date("Y-m-d")."'
					WHERE cotizacion_id=".$_SESSION['compr2']['cotizacion']."
					AND codigo_proveedor_id=".$_SESSION['compr2']['codigoprov']."
					AND empresa_id='".$_SESSION['compra']['empresa']."';";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			$dbconn->CommitTrans();
			if($this->frmError["MensajeError"]==NULL)
			{
				$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
				<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."";
			}
			else
			{
				$this->frmError["MensajeError"].="<br>DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
				<br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador2."";
			}
			$this->uno=1;
			$this->RecibirPreciosCotizacionCompra();
			return true;
		}
	}

	function BuscarProductosCompararCompra($empresa)//Función que busca los productos que están cotizados
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocomp'])
		{
			$codigo=$_REQUEST['codigocomp'];
			$busqueda1="AND B.codigo_producto LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descricomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['descricomp']);
			$busqueda2="AND UPPER(B.descripcion) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['grupo'])
		{
			$codigo=STRTOUPPER($_REQUEST['grupo']);
			$busqueda3="AND B.grupo_id='$codigo'";
		}
		else
		{
			$busqueda3='';
		}
		if($_REQUEST['clasePr'])
		{
			$codigo=STRTOUPPER($_REQUEST['clasePr']);
			$busqueda4="AND B.clase_id='$codigo'";
		}
		else
		{
			$busqueda4='';
		}
		if($_REQUEST['subclase'])
		{
			$codigo=STRTOUPPER($_REQUEST['subclase']);
			$busqueda5="AND B.subclase_id='$codigo'";
		}
		else
		{
			$busqueda5='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM
					(
						SELECT DISTINCT B.codigo_producto,
						B.descripcion,
						B.porc_iva,
						B.contenido_unidad_venta,
						A.nivel_autorizacion_id,
						E.descripcion AS autorizacion,
						F.descripcion AS desunidad,
						G.cantidad_comprar,
						(
							SELECT COUNT(D.codigo_proveedor_id)
							FROM compras_proveedores_productos_cotizaciones AS D
							WHERE D.codigo_producto=C.codigo_producto
						) AS totalcotiz
						FROM inventarios AS A
						LEFT JOIN compras_cantidad_productos AS G ON
						(A.empresa_id='".$empresa."'
						AND A.codigo_producto=G.codigo_producto),
						inventarios_productos AS B,
						compras_proveedores_productos_cotizaciones AS C,
						inv_niveles_autorizacion_compras AS E,
						unidades AS F
						WHERE A.empresa_id='".$empresa."'
						AND A.empresa_id=C.empresa_id
						AND A.codigo_producto=B.codigo_producto
						AND B.codigo_producto=C.codigo_producto
						AND A.nivel_autorizacion_id=E.nivel_autorizacion_id
						AND A.estado='1'
						AND B.unidad_id=F.unidad_id
						$busqueda1
						$busqueda2
						$busqueda3
						$busqueda4
						$busqueda5
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
				SELECT DISTINCT B.codigo_producto,
				B.descripcion,
				B.porc_iva,
				B.contenido_unidad_venta,
				A.nivel_autorizacion_id,
				E.descripcion AS autorizacion,
				F.descripcion AS desunidad,
				G.cantidad_comprar,
				(
					SELECT COUNT(D.codigo_proveedor_id)
					FROM compras_proveedores_productos_cotizaciones AS D
					WHERE D.codigo_producto=C.codigo_producto
				) AS totalcotiz
				FROM inventarios AS A
				LEFT JOIN compras_cantidad_productos AS G ON
				(A.empresa_id='".$empresa."'
				AND A.codigo_producto=G.codigo_producto),
				inventarios_productos AS B,
				compras_proveedores_productos_cotizaciones AS C,
				inv_niveles_autorizacion_compras AS E,
				unidades AS F
				WHERE A.empresa_id='".$empresa."'
				AND A.empresa_id=C.empresa_id
				AND A.codigo_producto=B.codigo_producto
				AND B.codigo_producto=C.codigo_producto
				AND A.nivel_autorizacion_id=E.nivel_autorizacion_id
				AND A.estado='1'
				AND B.unidad_id=F.unidad_id
				$busqueda1
				$busqueda2
				$busqueda3
				$busqueda4
				$busqueda5
				ORDER BY B.codigo_producto
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

	function BuscarCuadroComparativoCompra($empresa,$producto)//Busca las características del proveedor
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.tipo_id_tercero,
				A.tercero_id,
				A.nombre_tercero,
				B.estado,
				C.codigo_proveedor_id,
				C.numero_cotizacion,
				C.valor,
				C.fecha_lista,
				C.fecha_vigencia,
				C.codigo_producto_proveedor,
				(
					SELECT COUNT(D.orden_pedido_id)
					FROM compras_ordenes_pedidos AS D,
					compras_ordenes_pedidos_detalle AS E
					WHERE C.empresa_id='".$empresa."'
					AND C.codigo_producto='".$producto."'
					AND D.empresa_id=C.empresa_id
					AND D.orden_pedido_id=E.orden_pedido_id
					AND E.codigo_producto=C.codigo_producto
					AND D.codigo_proveedor_id=C.codigo_proveedor_id
					AND D.estado='1'
				) AS orden
				FROM terceros AS A,
				terceros_proveedores AS B,
				compras_proveedores_productos_cotizaciones AS C
				WHERE C.empresa_id='".$empresa."'
				AND C.codigo_producto='".$producto."'
				AND C.codigo_proveedor_id=B.codigo_proveedor_id
				AND B.tipo_id_tercero=A.tipo_id_tercero
				AND B.tercero_id=A.tercero_id
				ORDER BY A.nombre_tercero;";
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

	function BuscarConsultarSolicitarPedidoCompra($empresa,$producto,$proveedor)//Busca las ordenes de pedido pendientes
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.orden_pedido_id,
				A.fecha_orden,
				B.numero_unidades,
				B.valor,
				B.porc_iva
				FROM compras_ordenes_pedidos AS A,
				compras_ordenes_pedidos_detalle AS B
				WHERE A.empresa_id='".$empresa."'
				AND A.codigo_proveedor_id=".$proveedor."
				AND A.orden_pedido_id=B.orden_pedido_id
				AND B.codigo_producto='".$producto."'
				AND A.estado='1'
				ORDER BY A.fecha_orden DESC;";
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

	function BuscarDatosProveedorCompra($empresa,$producto,$proveedor)//Busca las características del proveedor
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.tipo_id_tercero,
				A.tercero_id,
				A.nombre_tercero,
				A.tipo_pais_id,
				A.tipo_dpto_id,
				A.tipo_mpio_id,
				A.direccion,
				A.telefono,
				A.fax,
				A.email,
				A.celular,
				A.busca_persona,
				B.estado,
				B.dias_gracia,
				B.dias_credito,
				B.tiempo_entrega,
				B.descuento_por_contado,
				B.cupo,
				C.codigo_proveedor_id,
				C.numero_cotizacion,
				C.valor,
				C.fecha_lista,
				C.fecha_vigencia,
				C.codigo_producto_proveedor,
				(
					SELECT COUNT(D.orden_pedido_id)
					FROM compras_ordenes_pedidos AS D,
					compras_ordenes_pedidos_detalle AS E
					WHERE C.empresa_id='".$empresa."'
					AND C.codigo_producto='".$producto."'
					AND C.codigo_proveedor_id=".$proveedor."
					AND D.empresa_id=C.empresa_id
					AND D.orden_pedido_id=E.orden_pedido_id
					AND E.codigo_producto=C.codigo_producto
					AND D.codigo_proveedor_id=C.codigo_proveedor_id
					AND D.estado='1'
				) AS orden,
				(
					SELECT F.evaluacion_id
					FROM terceros_proveedores AS G
					LEFT JOIN compras_proveedores_evaluaciones AS F ON
					(G.codigo_proveedor_id=F.codigo_proveedor_id
					AND G.codigo_proveedor_id=".$proveedor.")
					WHERE
					(
						SELECT MAX(H.fecha_evaluacion)
						FROM terceros_proveedores AS I
						LEFT JOIN compras_proveedores_evaluaciones AS H ON
						(I.codigo_proveedor_id=H.codigo_proveedor_id
						AND I.codigo_proveedor_id=".$proveedor.")
					) = F.fecha_evaluacion
				) AS evaluacion
				FROM terceros AS A,
				terceros_proveedores AS B,
				compras_proveedores_productos_cotizaciones AS C
				WHERE C.empresa_id='".$empresa."'
				AND C.codigo_producto='".$producto."'
				AND C.codigo_proveedor_id=".$proveedor."
				AND C.codigo_proveedor_id=B.codigo_proveedor_id
				AND B.tipo_id_tercero=A.tipo_id_tercero
				AND B.tercero_id=A.tercero_id
				ORDER BY A.nombre_tercero;";
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

	function ValidarSolicitarPedidoCompra()//Guarda la orden de pedido una vez se haya confirmado
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query = "SELECT orden_pedido_id
				FROM compras_ordenes_pedidos
				WHERE empresa_id='".$_SESSION['compra']['empresa']."'
				AND codigo_proveedor_id=".$_SESSION['compr2']['datosprove']['codigo_proveedor_id']."
				AND estado='1';";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if($resulta->fields[0]<>NULL)
		{
			$ordenpedid=$resulta->fields[0];
		}
		else
		{
			$query = "SELECT NEXTVAL ('compras_ordenes_pedidos_orden_pedido_id_seq');";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			$ordenpedid=$resulta->fields[0];
			$query = "INSERT INTO compras_ordenes_pedidos
					(orden_pedido_id,
					codigo_proveedor_id,
					empresa_id,
					fecha_orden,
					estado,
					usuario_id)
					VALUES
					(".$ordenpedid.",
					".$_SESSION['compr2']['datosprove']['codigo_proveedor_id'].",
					'".$_SESSION['compra']['empresa']."',
					'".date("Y-m-d")."',
					'1',
					".UserGetUID().");";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
		}
		if($_SESSION['compr2']['datosprove']['orden']==0)
		{
			$query = "INSERT INTO compras_ordenes_pedidos_detalle
					(orden_pedido_id,
					codigo_producto,
					numero_unidades,
					valor,
					porc_iva,
					estado,
					acta_autorizacion)
					VALUES
					(".$ordenpedid.",
					'".$_SESSION['compr2']['datosprodu']['codigoprod']."',
					".$_POST['numUnidades'].",
					".$_SESSION['compr2']['datosprove']['valor'].",
					".$_SESSION['compr2']['datosprodu']['procentiva'].",
					'1',
					'".$_POST['actautoriz']."');";
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
			$query = "SELECT numero_unidades
					FROM  compras_ordenes_pedidos_detalle
					WHERE orden_pedido_id=".$ordenpedid."
					AND codigo_producto='".$_SESSION['compr2']['datosprodu']['codigoprod']."';";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			$totales=$_POST['numUnidades'];
			$totales=$totales+$resulta->fields[0];
			$query = "UPDATE compras_ordenes_pedidos_detalle SET
					numero_unidades=".$totales."
					WHERE orden_pedido_id=".$ordenpedid."
					AND codigo_producto='".$_SESSION['compr2']['datosprodu']['codigoprod']."';";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
		}
		if($_SESSION['compr2']['datosprodu']['canticompr']<>NULL)
		{
			if($_POST['numUnidades']>=$_SESSION['compr2']['datosprodu']['canticompr'])
			{
				$query = "DELETE FROM compras_cantidad_productos
						WHERE codigo_producto='".$_SESSION['compr2']['datosprodu']['codigoprod']."'
						AND empresa_id='".$_SESSION['compra']['empresa']."';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
					return false;
				}
				$_SESSION['compr2']['datosprodu']['canticompr']='';
			}
			else if($_POST['numUnidades']<$_SESSION['compr2']['datosprodu']['canticompr'])
			{
				$_SESSION['compr2']['datosprodu']['canticompr']=$_SESSION['compr2']['datosprodu']['canticompr']-$_POST['numUnidades'];
				$query = "UPDATE compras_cantidad_productos SET
						cantidad_comprar=".$_SESSION['compr2']['datosprodu']['canticompr']."
						WHERE codigo_producto='".$_SESSION['compr2']['datosprodu']['codigoprod']."'
						AND empresa_id='".$_SESSION['compra']['empresa']."';";
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
		$this->CuadroComparativoCompra();
		return true;
	}

	function BuscarEnviarOrdenPedidoCompra($empresa)//Busca las cotizaciones activas
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocomp'])
		{
			$codigo=$_REQUEST['codigocomp'];
			$busqueda1="AND A.orden_pedido_id LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['nombrecomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['nombrecomp']);
			$busqueda2="AND UPPER(C.nombre_tercero) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['fecharcomp'])
		{
			$fecha=explode('/',$_REQUEST['fecharcomp']);
			if(checkdate($fecha[1], $fecha[0], $fecha[2])==1)
			{
				$codigo=$fecha[2].'-'.$fecha[1].'-'.$fecha[0];
				$busqueda3="AND A.fecha_orden>='$codigo'";
			}
			else
			{
				$busqueda3='';
			}
		}
		else
		{
			$busqueda3='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM
					(
						SELECT A.orden_pedido_id,
						A.codigo_proveedor_id,
						A.fecha_orden,
						B.tipo_id_tercero,
						B.tercero_id,
						B.dias_gracia,
						B.tiempo_entrega,
						C.nombre_tercero,
						C.direccion,
						C.telefono,
						C.fax,
						C.email,
						C.celular,
						C.busca_persona,
						(
							SELECT COUNT(D.codigo_producto)
							FROM compras_ordenes_pedidos_detalle AS D
							WHERE D.orden_pedido_id=A.orden_pedido_id
						) AS cantidad
						FROM compras_ordenes_pedidos AS A,
						terceros_proveedores AS B,
						terceros AS C
						WHERE A.empresa_id='".$empresa."'
						AND A.estado='1'
						AND A.codigo_proveedor_id=B.codigo_proveedor_id
						AND B.estado='1'
						AND B.tipo_id_tercero=C.tipo_id_tercero
						AND B.tercero_id=C.tercero_id
						$busqueda1
						$busqueda2
						$busqueda3
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
				SELECT A.orden_pedido_id,
				A.codigo_proveedor_id,
				A.fecha_orden,
				B.tipo_id_tercero,
				B.tercero_id,
				B.dias_gracia,
				B.tiempo_entrega,
				C.nombre_tercero,
				C.direccion,
				C.telefono,
				C.fax,
				C.email,
				C.celular,
				C.busca_persona,
				(
					SELECT COUNT(D.codigo_producto)
					FROM compras_ordenes_pedidos_detalle AS D
					WHERE D.orden_pedido_id=A.orden_pedido_id
				) AS cantidad
				FROM compras_ordenes_pedidos AS A,
				terceros_proveedores AS B,
				terceros AS C
				WHERE A.empresa_id='".$empresa."'
				AND A.estado='1'
				AND A.codigo_proveedor_id=B.codigo_proveedor_id
				AND B.estado='1'
				AND B.tipo_id_tercero=C.tipo_id_tercero
				AND B.tercero_id=C.tercero_id
				$busqueda1
				$busqueda2
				$busqueda3
				ORDER BY A.fecha_orden DESC, A.orden_pedido_id DESC
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

	function BuscarEnviarOrdenPedidoProCompra($empresa,$orden)//Busca los productos de la orden de pedido
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT B.codigo_producto,
				B.descripcion,
				B.contenido_unidad_venta,
				D.descripcion AS desunidad,
				C.numero_unidades,
				C.valor,
				C.porc_iva,
				C.acta_autorizacion
				FROM inventarios AS A,
				inventarios_productos AS B,
				compras_ordenes_pedidos_detalle AS C,
				unidades AS D
				WHERE A.empresa_id='".$empresa."'
				AND C.orden_pedido_id=".$orden."
				AND A.codigo_producto=B.codigo_producto
				AND B.unidad_id=D.unidad_id
				AND C.codigo_producto=B.codigo_producto
				ORDER BY B.codigo_producto;";
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

	function ValidarEnviarOrdenPedidoProCompra()//Función que cambia de estado la orden de pedido como enviada
	{
		list($dbconn) = GetDBconn();
		$query = "UPDATE compras_ordenes_pedidos SET
				estado='3',
				fecha_envio='".date("Y-m-d")."'
				WHERE orden_pedido_id=".$_SESSION['compr2']['ordenpedid']."
				AND codigo_proveedor_id=".$_SESSION['compr2']['proveeorde']."
				AND empresa_id='".$_SESSION['compra']['empresa']."';";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->frmError["MensajeError"].="LA ORDEN DE PEDIDO No. ".$_SESSION['compr2']['ordenpedid']."
		<br>HA SIDO GUARDADA COMO ENVIADA AL PROVEEDOR";
		$this->uno=1;
		$this->EnviarOrdenPedidoCompra();
		return true;
	}

	function BuscarRecibirOrdenPedidoCompra($empresa)//Busca las ordenes de pedido pendientes por recibir mercancia
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocomp'])
		{
			$codigo=$_REQUEST['codigocomp'];
			$busqueda1="AND A.orden_pedido_id LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['nombrecomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['nombrecomp']);
			$busqueda2="AND UPPER(C.nombre_tercero) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['fecharcomp'])
		{
			$fecha=explode('/',$_REQUEST['fecharcomp']);
			if(checkdate($fecha[1], $fecha[0], $fecha[2])==1)
			{
				$codigo=$fecha[2].'-'.$fecha[1].'-'.$fecha[0];
				$busqueda3="AND A.fecha_orden>='$codigo'";
			}
			else
			{
				$busqueda3='';
			}
		}
		else
		{
			$busqueda3='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM
					(
						SELECT A.orden_pedido_id,
						A.codigo_proveedor_id,
						A.fecha_orden,
						A.fecha_envio,
						B.tipo_id_tercero,
						B.tercero_id,
						B.dias_gracia,
						B.tiempo_entrega,
						C.nombre_tercero,
						C.direccion,
						C.telefono,
						C.fax,
						C.email,
						C.celular,
						C.busca_persona,
						(
							SELECT COUNT(D.codigo_producto)
							FROM compras_ordenes_pedidos_detalle AS D
							WHERE D.orden_pedido_id=A.orden_pedido_id
						) AS cantidad
						FROM compras_ordenes_pedidos AS A,
						terceros_proveedores AS B,
						terceros AS C
						WHERE A.empresa_id='".$empresa."'
						AND A.estado='3'
						AND A.codigo_proveedor_id=B.codigo_proveedor_id
						AND B.tipo_id_tercero=C.tipo_id_tercero
						AND B.tercero_id=C.tercero_id
						$busqueda1
						$busqueda2
						$busqueda3
					) AS r;";//AND B.estado='1'
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
				SELECT A.orden_pedido_id,
				A.codigo_proveedor_id,
				A.fecha_orden,
				A.fecha_envio,
				B.tipo_id_tercero,
				B.tercero_id,
				B.dias_gracia,
				B.tiempo_entrega,
				C.nombre_tercero,
				C.direccion,
				C.telefono,
				C.fax,
				C.email,
				C.celular,
				C.busca_persona,
				(
					SELECT COUNT(D.codigo_producto)
					FROM compras_ordenes_pedidos_detalle AS D
					WHERE D.orden_pedido_id=A.orden_pedido_id
				) AS cantidad
				FROM compras_ordenes_pedidos AS A,
				terceros_proveedores AS B,
				terceros AS C
				WHERE A.empresa_id='".$empresa."'
				AND A.estado='3'
				AND A.codigo_proveedor_id=B.codigo_proveedor_id
				AND B.tipo_id_tercero=C.tipo_id_tercero
				AND B.tercero_id=C.tercero_id
				$busqueda1
				$busqueda2
				$busqueda3
				ORDER BY A.fecha_orden DESC, A.orden_pedido_id DESC
				)
				LIMIT ".$this->limit." OFFSET $Of;";//AND B.estado='1'
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

	function BuscarConsultarOrdenPedidoCompra($empresa)//Busca todas las ordenes de pedido, sin importar el estado
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigocomp'])
		{
			$codigo=$_REQUEST['codigocomp'];
			$busqueda1="AND A.orden_pedido_id LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['nombrecomp'])
		{
			$codigo=STRTOUPPER($_REQUEST['nombrecomp']);
			$busqueda2="AND UPPER(C.nombre_tercero) LIKE '%$codigo%'";
		}
		else
		{
			$busqueda2='';
		}
		if($_REQUEST['fecharcomp'])
		{
			$fecha=explode('/',$_REQUEST['fecharcomp']);
			if(checkdate($fecha[1], $fecha[0], $fecha[2])==1)
			{
				$codigo=$fecha[2].'-'.$fecha[1].'-'.$fecha[0];
				$busqueda3="AND A.fecha_orden>='$codigo'";
			}
			else
			{
				$busqueda3='';
			}
		}
		else
		{
			$busqueda3='';
		}
		if(empty($_REQUEST['conteo']))
		{
			$query = "SELECT count(*) FROM
					(
						SELECT A.orden_pedido_id,
						A.codigo_proveedor_id,
						A.fecha_orden,
						A.fecha_envio,
						A.fecha_recibido,
						A.estado,
						B.tipo_id_tercero,
						B.tercero_id,
						B.dias_gracia,
						B.tiempo_entrega,
						C.nombre_tercero,
						C.direccion,
						C.telefono,
						C.fax,
						C.email,
						C.celular,
						C.busca_persona,
						(
							SELECT COUNT(D.codigo_producto)
							FROM compras_ordenes_pedidos_detalle AS D
							WHERE D.orden_pedido_id=A.orden_pedido_id
						) AS cantidad
						FROM compras_ordenes_pedidos AS A,
						terceros_proveedores AS B,
						terceros AS C
						WHERE A.empresa_id='".$empresa."'
						AND A.codigo_proveedor_id=B.codigo_proveedor_id
						AND B.tipo_id_tercero=C.tipo_id_tercero
						AND B.tercero_id=C.tercero_id
						$busqueda1
						$busqueda2
						$busqueda3
					) AS r;";//AND B.estado='1'
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
				SELECT A.orden_pedido_id,
				A.codigo_proveedor_id,
				A.fecha_orden,
				A.fecha_envio,
				A.fecha_recibido,
				A.estado,
				B.tipo_id_tercero,
				B.tercero_id,
				B.dias_gracia,
				B.tiempo_entrega,
				C.nombre_tercero,
				C.direccion,
				C.telefono,
				C.fax,
				C.email,
				C.celular,
				C.busca_persona,
				(
					SELECT COUNT(D.codigo_producto)
					FROM compras_ordenes_pedidos_detalle AS D
					WHERE D.orden_pedido_id=A.orden_pedido_id
				) AS cantidad
				FROM compras_ordenes_pedidos AS A,
				terceros_proveedores AS B,
				terceros AS C
				WHERE A.empresa_id='".$empresa."'
				AND A.codigo_proveedor_id=B.codigo_proveedor_id
				AND B.tipo_id_tercero=C.tipo_id_tercero
				AND B.tercero_id=C.tercero_id
				$busqueda1
				$busqueda2
				$busqueda3
				ORDER BY A.fecha_orden DESC, A.orden_pedido_id DESC
				)
				LIMIT ".$this->limit." OFFSET $Of;";//AND B.estado='1'
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

	
	/*$this->BuscarRequisicionesCompras($_SESSION['compra']['empresa']);
	function BuscarRequisicionesCompras($empresa)//Función que busca los productos que están por debajo del stock
	{
		list($dbconn) = GetDBconn();
		ECHO $query = "SELECT A.codigo_producto,
				A.existencia_minima,
				A.existencia_maxima,
				A.existencia,
				A.costo_anterior,
				A.costo,
				A.nivel_autorizacion_id
				FROM inventarios AS A
				WHERE A.empresa_id='".$empresa."'
				AND A.estado='1'
				AND A.existencia_minima>0
				ORDER BY A.codigo_producto
				LIMIT 20 OFFSET 0;";
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
	}*/

}//fin de la clase
?>
