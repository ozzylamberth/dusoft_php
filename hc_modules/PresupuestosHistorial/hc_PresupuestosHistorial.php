
<?php

/**
* Submodulo de Presupuestos.
*
* Submodulo para controlar los procedimientos que se le harán a un
* paciente en las citas de consulta externa odontológicas de tratamiento
* @author Jorge Eliecer Avila Garzon <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_PresupuestosHistorial.php,v 1.3 2007/07/09 19:20:54 tizziano Exp $
*/

/**
* Accion Preventiva
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de presupuestos.
*/

class PresupuestosHistorial extends hc_classModules
{
	var $limit;
	var $conteo;

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function PresupuestosHistorial()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

/**
* Esta función retorna los datos de concernientes a la version del submodulo
*
* @access private
*/

	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'01/27/2005',
		'autor'=>'JORGE ELIECER AVILA',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}

/**
* Esta metodo captura los datos de la impresión de la Historia Clinica.
* @access private
* @return text Datos HTML de la pantalla.
*/
	function GetReporte_Html()
	{
		$imprimir=$this->frmHistoria();
		if($imprimir==false)
		{
			return true;
		}
		return $imprimir;
	}

/**
* Esta función retorna los datos de la impresión de la consulta del submodulo.
*
* @access private
* @return text Datos HTML de la pantalla.
*/
	function GetConsulta()//Corregida para el submodulo
	{
			if($this->frmConsulta()==false)
			{
				return true;
			}
			return $this->salida;
	}

/**
* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/
	function GetEstado()
	{
		 return true;
	}

/**
* Esta función retorna la presentación del submodulo (consulta o inserción).
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acción a realizar.
*/
	function GetForma()//Desde esta funcion es de JORGE AVILA
	{
		$pfj=$this->frmPrefijo;
		if(empty($_REQUEST['accion'.$pfj]))
		{
			$this->frmForma();//$this->frmevolucion();
		}
		elseif($_REQUEST['accion'.$pfj]=='cancelar')
		{
			$this->frmCancelar();
		}
		elseif($_REQUEST['accion'.$pfj]=='modificarotros')
		{
			$this->frmCargosPlan();
		}
		elseif($_REQUEST['accion'.$pfj]=='insertaraotros')
		{
			if($this->InsertPlanCargos()==true)
			{
				$this->frmCargosPlan();
			}
		}
		elseif($_REQUEST['accion'.$pfj]=='insertarjustif')
		{
			if($this->CancelDatos()==true)
			{
				$this->frmForma();
			}
		}
		elseif($_REQUEST['accion'.$pfj]=='activar')
		{
			if($this->ActivaDatos()==true)
			{
				$this->frmForma();
			}
		}
		return $this->salida;
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

	function BuscarCargosPlan()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="SELECT hc_odontograma_primera_vez_id
		FROM hc_odontogramas_primera_vez
		WHERE tipo_id_paciente='".$this->tipoidpaciente."'
		AND paciente_id='".$this->paciente."'
		AND sw_activo='1';";//evolucion_id=".$this->evolucion." AND
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$odonto=$resulta->fields[0];
		$codigo = STRTOUPPER ($_REQUEST['codigo'.$pfj]);
		$diagnostico  =STRTOUPPER($_REQUEST['diagnostico'.$pfj]);
		$busqueda1 = '';
		$busqueda2 = '';
		if ($codigo != '')
		{
			$busqueda1 ="AND A.cargo LIKE '$codigo%'";
		}
		if($diagnostico != '')
		{
			$busqueda2 ="AND B.descripcion LIKE '%$diagnostico%'";
		}
		if(empty($_REQUEST['conteo'.$pfj]))
		{
			$query ="SELECT count(*) FROM
				 	(
					SELECT A.cargo,
					B.descripcion
					FROM hc_tipos_plan_tratamiento_cargo AS A,
					cups AS B
					WHERE A.cargo=B.cargo
					AND A.hc_tipo_plan_tratamiento_id=".$_REQUEST['tipo_plan_tratam'.$pfj]."
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
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'.$pfj];
			if($Of > $this->conteo)
			{
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}
		$query="SELECT A.cargo,
					A.hc_tipo_plan_tratamiento_id,
					B.descripcion,
					C.cantidad,
					C.cargo AS guarda,
					D.descripcion AS desplantra
					FROM hc_tipos_plan_tratamiento_cargo AS A
					LEFT JOIN hc_odontogramas_primera_vez_presupuesto AS C ON
					(A.cargo=C.cargo
					AND C.hc_odontograma_primera_vez_id=".$odonto."),
					cups AS B,
					hc_tipos_plan_tratamiento AS D
					WHERE A.cargo=B.cargo
					AND A.hc_tipo_plan_tratamiento_id=".$_REQUEST['tipo_plan_tratam'.$pfj]."
					AND A.hc_tipo_plan_tratamiento_id=D.hc_tipo_plan_tratamiento_id
					$busqueda1
					$busqueda2
					ORDER BY A.cargo
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
		if($this->conteo==='0')
		{
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
		}
		return $var;
	}

	function InsertPlanCargos()
	{
		$pfj=$this->frmPrefijo;
		$contador1=$contador2=0;
		list($dbconn) = GetDBconn();
		$query="SELECT hc_odontograma_primera_vez_id
		FROM hc_odontogramas_primera_vez
		WHERE tipo_id_paciente='".$this->tipoidpaciente."'
		AND paciente_id='".$this->paciente."'
		AND sw_activo='1';";//evolucion_id=".$this->evolucion." AND
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$odonto=$resulta->fields[0];
		$dbconn->BeginTrans();
		for($i=0;$i<sizeof($_REQUEST['vector'.$pfj]);$i++)
		{
			if($_REQUEST['cantidad'.$i.$pfj]==NULL)
			{
				$_REQUEST['cantidad'.$i.$pfj]=1;
			}
			if($_REQUEST['ayudas'.$i.$pfj]<>NULL AND $_REQUEST['vector'.$pfj][$i]['guarda']==NULL)
			{
				$contador1++;
				$query="INSERT INTO hc_odontogramas_primera_vez_presupuesto
				(hc_odontograma_primera_vez_id,
				cargo,
				cantidad,
				usuario_id)
				VALUES
				(".$odonto.",
				'".$_REQUEST['ayudas'.$i.$pfj]."',
				".$_REQUEST['cantidad'.$i.$pfj].",
				".UserGetUID().");";
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollbackTrans();
					$this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
					return true;
				}
			}
			else if($_REQUEST['ayudas'.$i.$pfj]==NULL AND $_REQUEST['vector'.$pfj][$i]['guarda']<>NULL)
			{
				$contador2++;
				$query="DELETE FROM hc_odontogramas_primera_vez_presupuesto
				WHERE hc_odontograma_primera_vez_id=".$odonto."
				AND cargo='".$_REQUEST['vector'.$pfj][$i]['cargo']."';";
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollbackTrans();
					$this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
					return true;
				}
			}
		}
		$dbconn->CommitTrans();
		$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
		<br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
          $this->RegistrarSubmodulo($this->GetVersion());
		return true;
	}

	function BuscarTiposPlanTratamiento()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT hc_tipo_plan_tratamiento_id,
		descripcion
		FROM hc_tipos_plan_tratamiento;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
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

	function BuscarCargosActivo($cargo)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT hc_odontograma_primera_vez_id
		FROM hc_odontogramas_primera_vez
		WHERE tipo_id_paciente='".$this->tipoidpaciente."'
		AND paciente_id='".$this->paciente."'
		AND sw_activo='1';";//evolucion_id=".$this->evolucion." AND
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$odonto=$resulta->fields[0];
		if(!empty($odonto))
		{
			$query="SELECT A.hc_tipo_ubicacion_diente_id,
			A.estado,
			B.descripcion
			FROM hc_odontogramas_primera_vez_detalle AS A,
			hc_tipos_cuadrantes_dientes AS B,
			hc_tipos_problemas_soluciones_dientes AS E
			WHERE A.hc_odontograma_primera_vez_id=".$odonto."
			AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
			AND A.hc_tipo_problema_diente_id=E.hc_tipo_problema_diente_id
			AND A.hc_tipo_producto_diente_id=E.hc_tipo_producto_diente_id
			AND A.estado NOT IN ('3','8')
			AND E.cargo='".$cargo."'
			ORDER BY A.hc_tipo_ubicacion_diente_id;";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
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
		}
		return $var;
	}

	function BuscarCargosPresActivo($tipoplan)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT hc_odontograma_primera_vez_id
		FROM hc_odontogramas_primera_vez
		WHERE tipo_id_paciente='".$this->tipoidpaciente."'
		AND paciente_id='".$this->paciente."'
		AND sw_activo='1';";//evolucion_id=".$this->evolucion." AND
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$odonto=$resulta->fields[0];
		if(!empty($odonto))
		{
			$query="SELECT DISTINCT A.descripcion,
			B.cargo,
				(SELECT COUNT(C.cargo)
				FROM hc_tipos_problemas_soluciones_dientes AS C,
				hc_odontogramas_primera_vez_detalle AS D
				WHERE D.hc_odontograma_primera_vez_id=".$odonto."
				AND D.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
				AND D.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
				AND D.estado NOT IN ('3','8')
				AND C.cargo=A.cargo) AS cantidad
			FROM cups AS A,
			hc_tipos_plan_tratamiento_cargo AS B,
			hc_tipos_problemas_soluciones_dientes AS C,
			hc_odontogramas_primera_vez_detalle AS D
			WHERE A.cargo=B.cargo
			AND B.hc_tipo_plan_tratamiento_id=".$tipoplan."
			AND D.hc_odontograma_primera_vez_id=".$odonto."
			AND D.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
			AND D.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
			AND D.estado NOT IN ('3','8')
			AND C.cargo=A.cargo;";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
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
		}
		return $var;
	}

	function BuscarCargosPresInActivo($tipoplan)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT hc_odontograma_primera_vez_id
		FROM hc_odontogramas_primera_vez
		WHERE tipo_id_paciente='".$this->tipoidpaciente."'
		AND paciente_id='".$this->paciente."'
		AND sw_activo='1';";//evolucion_id=".$this->evolucion." AND
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$odonto=$resulta->fields[0];
		if(!empty($odonto))
		{
			$query="SELECT DISTINCT A.descripcion,
			B.cargo,
				(SELECT COUNT(C.cargo)
				FROM hc_tipos_problemas_soluciones_dientes AS C,
				hc_odontogramas_primera_vez_detalle AS D
				WHERE D.hc_odontograma_primera_vez_id=".$odonto."
				AND D.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
				AND D.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
				AND D.estado='2'
				AND C.cargo=A.cargo) AS cantidad
			FROM cups AS A,
			hc_tipos_plan_tratamiento_cargo AS B,
			hc_tipos_problemas_soluciones_dientes AS C,
			hc_odontogramas_primera_vez_detalle AS D
			WHERE A.cargo=B.cargo
			AND B.hc_tipo_plan_tratamiento_id=".$tipoplan."
			AND D.hc_odontograma_primera_vez_id=".$odonto."
			AND D.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
			AND D.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
			AND D.estado='2'
			AND C.cargo=A.cargo;";
			$resulta = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0)
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
		}
		return $var;
	}

	function BuscarParaCancelar($odetalleid)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT A.hc_tipo_ubicacion_diente_id,
		A.estado,
		B.descripcion AS des1,
		C.descripcion AS des2,
		D.descripcion AS des3
		FROM hc_odontogramas_primera_vez_detalle AS A,
		hc_tipos_cuadrantes_dientes AS B,
		hc_tipos_problemas_dientes AS C,
		hc_tipos_productos_dientes AS D
		WHERE A.hc_odontograma_primera_vez_detalle_id=".$odetalleid."
		AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
		AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
		AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
		AND C.sw_presupuesto='1'
		ORDER BY A.hc_tipo_ubicacion_diente_id;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
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

	function CancelDatos()
	{
		$pfj=$this->frmPrefijo;
		$this->frmError["MensajeError"]="";
		if($_REQUEST['justificac'.$pfj]==NULL)
		{
			$this->frmError["MensajeError"]="LA JUSTIFICACIÓN ES OBLIGATORIA - NO SE CANCELÓ EL PROCEDIMIENTO";
			return true;
		}
		list($dbconn) = GetDBconn();
		$query="UPDATE hc_odontogramas_primera_vez_detalle SET
		estado='2',
		usuario_id_justifica=".UserGetUID().",
		justifica_cancelacion='".$_REQUEST['justificac'.$pfj]."'
		WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$this->frmError["MensajeError"]="EL CARGO FUE CANCELADO CORRECTAMENTE";
			return true;
		}
	}

	function ActivaDatos()
	{
		$pfj=$this->frmPrefijo;
		$this->frmError["MensajeError"]="";
		list($dbconn) = GetDBconn();
		$query="UPDATE hc_odontogramas_primera_vez_detalle SET
		estado='1',
		usuario_id_justifica=".'NULL'."
		WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$this->frmError["MensajeError"]="EL CARGO CAMBIÓ A PENDIENTE POR REALIZAR";
			$this->RegistrarSubmodulo($this->GetVersion());
               return true;
		}
	}

	function BuscarCuentas($cuenta)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT tipo_afiliado_id,
		rango,
		semanas_cotizadas
		FROM cuentas
		WHERE numerodecuenta=".$cuenta.";";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$odonto[0]=$resulta->fields[0];
		$odonto[1]=$resulta->fields[1];
		$odonto[2]=$resulta->fields[2];
		return $odonto;
	}

	function BuscarApoyosOdontologia()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="SELECT hc_odontograma_primera_vez_id
		FROM hc_odontogramas_primera_vez
		WHERE tipo_id_paciente='".$this->tipoidpaciente."'
		AND paciente_id='".$this->paciente."'
		AND sw_activo='1';";//evolucion_id=".$this->evolucion." AND
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$odonto=$resulta->fields[0];
		$query="SELECT A.cargo,
		B.descripcion,
		C.descripcion_ubicacion,
		C.cantidad,
		C.estado
		FROM hc_odontogramas_apoyod AS A,
		cups AS B,
		hc_odontogramas_primera_vez_apoyod AS C
		WHERE A.cargo=B.cargo
		AND A.cargo=C.cargo
		AND C.hc_odontograma_primera_vez_id=".$odonto."
		AND C.estado NOT IN ('3','8');";
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

	function BuscarPlanTratamientoCargo($tipoplan)
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="SELECT hc_odontograma_primera_vez_id
		FROM hc_odontogramas_primera_vez
		WHERE tipo_id_paciente='".$this->tipoidpaciente."'
		AND paciente_id='".$this->paciente."'
		AND sw_activo='1';";//evolucion_id=".$this->evolucion."AND
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$odonto=$resulta->fields[0];
		$query="SELECT A.cargo,
		A.descripcion,
		B.hc_tipo_plan_tratamiento_id,
		C.cantidad,
		C.estado
		FROM cups AS A,
		hc_tipos_plan_tratamiento_cargo AS B,
		hc_odontogramas_primera_vez_presupuesto AS C
		WHERE B.hc_tipo_plan_tratamiento_id=".$tipoplan."
		AND B.cargo=C.cargo
		AND C.hc_odontograma_primera_vez_id=".$odonto."
		AND B.cargo=A.cargo
		AND C.estado NOT IN ('3','8');";
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

	function BuscarPlan()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="SELECT plan_id
		FROM cuentas
		WHERE ingreso =".$this->ingreso.";";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		list($plan) = $resulta->FetchRow();
		return $plan;
	}

}
?>
