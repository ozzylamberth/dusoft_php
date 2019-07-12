
<?php

/**
* Modulo de Paragrafos (PHP).
*
* Modulo para el mantenimiento de los cargos del tarifario cups y de
* los cargos de la interface con datalab, asi como sus equivalencias
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* system_Paragrafos_user.php
*
**/

class system_Paragrafos_user extends classModulo
{
	var $uno;//para los errores
	var $limit;
	var $conteo;

	function system_Paragrafos_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->PrincipalParagrafos();
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

	function BuscarTiposParagr()//Busca los cargos CUPS y la relación con los de Paragrafos
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.tipo_para_imd,
				A.descripcion
				FROM tipos_paragrafados_imd AS A
				WHERE A.tipo_para_imd<>0;";
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

	function ValidarNuevosTiposParagr()//
	{
		list($dbconn) = GetDBconn();
		if($_POST['descritipo']<>NULL)
		{
			$query = "SELECT A.tipo_para_imd
					FROM tipos_paragrafados_imd AS A
					WHERE A.tipo_para_imd<>0
					AND A.descripcion='".$_POST['descritipo']."';";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			if($resulta->fields[0]==NULL)
			{
				$query ="INSERT INTO tipos_paragrafados_imd
						(descripcion)
						VALUES
						('".$_POST['descritipo']."');";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]=$dbconn->ErrorMsg();
				}
				else
				{
					$this->frmError["MensajeError"]="CLASIFICACIÓN GUARDADA CORRECTAMENTE";
				}
				$this->uno=1;
				$this->PrincipalParagrafos();
				return true;
			}
			else
			{
				$this->frmError["MensajeError"]="YA EXISTE UNA CLASIFICACIÓN CON EL NOMBRE ".$_POST['descritipo']."";
				$this->uno=1;
				$this->PrincipalParagrafos();
				return true;
			}
		}
		else
		{
			$this->frmError["MensajeError"]="EL CAMPO SE ENCUENTRA VACÍO, DIGITE UN NOMBRE VÁLIDO";
			$this->uno=1;
			$this->PrincipalParagrafos();
			return true;
		}
	}

	function MostrarServicios()//Muestra y modifica la información del plan escogido
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.servicio,
				A.descripcion,
				B.departamento,
				B.descripcion AS descdept,
				C.empresa_id,
				C.razon_social
				FROM servicios AS A,
				departamentos AS B,
				empresas AS C
				WHERE A.servicio=B.servicio
				AND A.sw_asistencial='1'
				AND A.servicio<>'0'
				AND B.empresa_id=C.empresa_id
				ORDER BY C.empresa_id, A.servicio, B.departamento;";
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

	function BuscarContarProductosParagr($tipopara,$servicio,$departamento)//
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT count(*) FROM
				(
				SELECT D.tipo_para_imd
				FROM inventarios_productos AS A,
				tipos_paragrafados_imd_detalle AS D
				WHERE A.codigo_producto=D.codigo_producto
				AND D.tipo_para_imd=".$tipopara."
				AND D.servicio='".$servicio."'
				AND D.departamento='".$departamento."'
				) AS r;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $resulta->fields[0];
	}

	function BuscarModificarProductosParagr($empresa,$tipopara,$servicio,$departamento)//
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['codigotipa'])
		{
			$codigo=$_REQUEST['codigotipa'];
			$busqueda1="AND A.codigo_producto LIKE '$codigo%'";
		}
		else
		{
			$busqueda1='';
		}
		if($_REQUEST['descritipa'])
		{
			$codigo=STRTOUPPER($_REQUEST['descritipa']);
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
					SELECT A.codigo_producto,
					A.descripcion,
					D.tipo_para_imd
					FROM inventarios_productos AS A,
					inventarios AS B
					LEFT JOIN tipos_paragrafados_imd_detalle AS D ON
					(B.codigo_producto=D.codigo_producto
					AND D.tipo_para_imd=".$tipopara."
					AND D.servicio='".$servicio."'
					AND D.departamento='".$departamento."'),
					inv_grupos_contrataciones AS C
					WHERE B.empresa_id='".$empresa."'
					AND B.codigo_producto=A.codigo_producto
					AND B.grupo_contratacion_id=C.grupo_contratacion_id
					AND C.grupo_contratacion_id<>'0'
					$busqueda1
					$busqueda2
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
				SELECT A.codigo_producto,
				A.descripcion,
				D.tipo_para_imd
				FROM inventarios_productos AS A,
				inventarios AS B
				LEFT JOIN tipos_paragrafados_imd_detalle AS D ON
				(B.codigo_producto=D.codigo_producto
				AND D.tipo_para_imd=".$tipopara."
				AND D.servicio='".$servicio."'
				AND D.departamento='".$departamento."'),
				inv_grupos_contrataciones AS C
				WHERE B.empresa_id='".$empresa."'
				AND B.codigo_producto=A.codigo_producto
				AND B.grupo_contratacion_id=C.grupo_contratacion_id
				AND C.grupo_contratacion_id<>'0'
				$busqueda1
				$busqueda2
				ORDER BY A.codigo_producto
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

	function ValidarModificarProductosParagr()//
	{
 		list($dbconn) = GetDBconn();
 		$dbconn->BeginTrans();
		$contador1=$contador2=0;
		for($i=0;$i<sizeof($_SESSION['paragr']['codigosimd']);$i++)
		{
			if($_SESSION['paragr']['codigosimd'][$i]['tipo_para_imd']<>NULL AND $_POST['grabarimd'.$i]==NULL)
			{
				$contador1++;
				$query ="DELETE FROM tipos_paragrafados_imd_detalle
						WHERE tipo_para_imd=".$_SESSION['paragr']['tipoparagr']."
						AND servicio='".$_SESSION['paragr']['servicimdp']."'
						AND departamento='".$_SESSION['paragr']['departimdp']."'
						AND codigo_producto='".$_SESSION['paragr']['codigosimd'][$i]['codigo_producto']."';";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]=$dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
				}
			}
			else if($_SESSION['paragr']['codigosimd'][$i]['tipo_para_imd']==NULL AND $_POST['grabarimd'.$i]<>NULL)
			{
				$contador2++;
				$query ="INSERT INTO tipos_paragrafados_imd_detalle
						(tipo_para_imd,
						servicio,
						departamento,
						codigo_producto)
						VALUES
						(".$_SESSION['paragr']['tipoparagr'].",
						'".$_SESSION['paragr']['servicimdp']."',
						'".$_SESSION['paragr']['departimdp']."',
						'".$_SESSION['paragr']['codigosimd'][$i]['codigo_producto']."');";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]=$dbconn->ErrorMsg();
					$dbconn->RollBackTrans();
				}
			}
		}
		$dbconn->CommitTrans();
		if($this->frmError["MensajeError"]==NULL)
		{
			$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador2."
			<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador1."";
		}
		$this->uno=1;
		$this->ModificarProductosParagr();
		return true;
	}

	function BuscarConsultarProductosParagr($tipopara,$servicio,$departamento)//
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.codigo_producto,
				A.descripcion
				FROM inventarios_productos AS A,
				tipos_paragrafados_imd_detalle AS D
				WHERE A.codigo_producto=D.codigo_producto
				AND D.tipo_para_imd=".$tipopara."
				AND D.servicio='".$servicio."'
				AND D.departamento='".$departamento."'
				ORDER BY A.codigo_producto;";
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

}//fin de la clase
?>
