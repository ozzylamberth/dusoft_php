<?php

/**
* Submodulo de Evoluciones Odontologicas.
*
* Submodulo para manejar los reportes de las evoluciones odontologicas.
* @author Tizziano Perea Ocoro <tizzianop@gmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Evoluciones_Odontologicas.php,v 1.16 2007/07/09 19:20:53 tizziano Exp $
*/

/**
* Evoluciones_Odontologicas.php
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo del Evoluciones Odontologicas.
*/

class Evoluciones_Odontologicas extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function Evoluciones_Odontologicas()
	{
		$this->salida = '';
		return true;
	}


/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

// 	function GetVersion()
// 	{
// 		$informacion=array(
// 		'version'=>'1',
// 		'subversion'=>'0',
// 		'revision'=>'0',
// 		'fecha'=>'04/19/2005',
// 		'autor'=>'TIZZIANO PEREA OCORO',
// 		'descripcion_cambio' => '',
// 		'requiere_sql' => false,
// 		'requerimientos_adicionales' => '',
// 		'version_kernel' => '1.0'
// 		);
// 		return $informacion;
// 	}


/**
* Esta función retorna los datos de la impresión de la consulta del submodulo.
*
* @access private
* @return text Datos HTML de la pantalla.
*/
	function GetConsulta()
	{
		if($this->frmConsulta()==false)
		{
			return true;
		}
		return $this->salida;
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
	function GetForma()
	{
		$pfj=$this->frmPrefijo;
    		if(empty($_REQUEST['accion'.$pfj]))
		{
			$this->frmForma();
		}
		return $this->salida;
	}
     
     
     function BuscarPlan()
	{
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $query="SELECT A.plan_id, B.departamento 
                  FROM cuentas AS A, hc_evoluciones AS B
                  WHERE A.numerodecuenta = B.numerodecuenta
                  AND A.ingreso = B.ingreso
                  AND A.ingreso = ".$this->ingreso.";";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $Plan = $resultado->FetchRow();
          $this->plan = $Plan[plan_id];
          $this->departamento = $Plan[departamento];
          $this->GetServicio($this->departamento);
          return true;
     }
	
     function GetServicio($departamento)
	{
		list($dbconn) = GetDBconn();
		$sql="select b.servicio from departamentos as a, servicios as b where a.servicio=b.servicio and a.departamento='".$departamento."';";
		$result = $dbconn->Execute($sql);
          $this->servicio = $result->fields[0];
		return true;
	}


	function Get_Evoluciones_Odontologicas()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="SELECT A.hc_odontograma_primera_vez_id, A.evolucion_id, B.fecha
		FROM hc_odontogramas_primera_vez AS A, hc_evoluciones AS B
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		AND A.sw_activo='1'
		AND A.evolucion_id = B.evolucion_id
		ORDER BY A.hc_odontograma_primera_vez_id ASC;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while($data = $resulta->FetchRow())
		{
			$odonto[] = $data;
		}

		$query="SELECT A.hc_odontograma_tratamiento_id
		FROM hc_odontogramas_tratamientos AS A
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		AND A.sw_activo='1';";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$odontotra=$resulta->fields[0];
		
          if(!empty($odonto))
		{
			for($i=0; $i<sizeof($odonto); $i++)
			{
				$query="(SELECT A.hc_tipo_ubicacion_diente_id,
				A.hc_odontograma_primera_vez_id AS odontogramas,
                    B.descripcion AS des1,
				C.descripcion AS des2,
				D.descripcion AS des3,
				E.cargo,
				F.descripcion AS des4,
                    H.diagnostico_id,
                    H.fecha_registro,
                    H.evolucion_id,
                    I.diagnostico_nombre,
                    H.usuario_id,
                    1 AS control
				FROM hc_odontogramas_primera_vez_detalle AS A,
				hc_tipos_cuadrantes_dientes AS B,
				hc_tipos_problemas_dientes AS C,
				hc_tipos_productos_dientes AS D,
				hc_tipos_problemas_soluciones_dientes AS E,
				cups AS F, hc_odontogramas_tratamientos_evolucion_primera_vez AS H,
                    diagnosticos AS I
				WHERE A.hc_odontograma_primera_vez_id=".$odonto[$i][0]."
				AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
				AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
				AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
				AND A.hc_tipo_problema_diente_id=E.hc_tipo_problema_diente_id
				AND A.hc_tipo_producto_diente_id=E.hc_tipo_producto_diente_id
                    AND A.hc_odontograma_primera_vez_detalle_id=H.hc_odontograma_primera_vez_detalle_id
                    AND H.diagnostico_id=I.diagnostico_id
                    AND H.sw_principal = 1
				AND (A.estado='0'
                    )
				AND E.cargo=F.cargo
				ORDER BY A.hc_tipo_ubicacion_diente_id, control)
                    
                    UNION

				(SELECT A.hc_tipo_ubicacion_diente_id,
				A.hc_odontograma_tratamiento_id AS odontogramas,
                     B.descripcion AS des1, 
                     C.descripcion AS des2, 
                     D.descripcion AS des3, 
                     E.cargo, 
                     F.descripcion AS des4, 
                     H.diagnostico_id, 
                     H.fecha_registro, 
                     H.evolucion_id, 
                     I.diagnostico_nombre,
                     H.usuario_id,
                     2 AS control

				FROM hc_odontogramas_tratamientos_detalle AS A, 
                    hc_tipos_cuadrantes_dientes AS B, hc_tipos_problemas_dientes AS C,
                    hc_tipos_productos_dientes AS D, 
                    hc_tipos_problemas_soluciones_dientes AS E, cups AS F, 
                    hc_odontogramas_tratamientos_evolucion_tratamiento AS H, 
                    diagnosticos AS I 

				WHERE A.hc_odontograma_tratamiento_id=".$odontotra."
                    AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id 
                    AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id 
                    AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id 
                    AND A.hc_tipo_problema_diente_id=E.hc_tipo_problema_diente_id 
                    AND A.hc_tipo_producto_diente_id=E.hc_tipo_producto_diente_id 
                    AND A.hc_odontograma_tratamiento_detalle_id=H.hc_odontograma_tratamiento_detalle_id 
                    AND H.diagnostico_id=I.diagnostico_id
                    AND H.sw_principal = 1
                    AND (A.estado='0'
                    OR A.estado='4')
                    AND E.cargo=F.cargo 
                    ORDER BY A.hc_tipo_ubicacion_diente_id, control);";

				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while($var = $resulta->FetchRow())
				{
					$odonto[$i][evo][] = $var;
				}
			}
		}
          $this->RegistrarSubmodulo($this->GetVersion());
		return $odonto;
	}

	//PARA frmhistoria y frmconsulta
	function Get_Evoluciones_Odontologicas2()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="SELECT A.hc_odontograma_primera_vez_id, A.evolucion_id, B.fecha
		FROM hc_odontogramas_primera_vez AS A, hc_evoluciones AS B
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		--AND A.evolucion_id=".$this->evolucion."
		AND A.evolucion_id = B.evolucion_id
		AND A.sw_activo='1'
		ORDER BY A.hc_odontograma_primera_vez_id ASC;";
		//
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while($data = $resulta->FetchRow())
		{
			$odonto[] = $data;
		}

		$query="SELECT A.hc_odontograma_tratamiento_id
		FROM hc_odontogramas_tratamientos AS A,
		hc_odontogramas_tratamientos_detalle AS B
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		--AND B.evolucion_id=".$this->evolucion."
		AND A.sw_activo='1'
		AND A.hc_odontograma_tratamiento_id=B.hc_odontograma_tratamiento_id
		;";
		//
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$odontotra=$resulta->fields[0];
		
          if(!empty($odonto))
		{
			for($i=0; $i<sizeof($odonto); $i++)
			{
				$query="(SELECT A.hc_tipo_ubicacion_diente_id,
				A.hc_odontograma_primera_vez_id AS odontogramas,
                    B.descripcion AS des1,
				C.descripcion AS des2,
				D.descripcion AS des3,
				E.cargo,
				F.descripcion AS des4,
                    H.diagnostico_id,
                    H.fecha_registro,
                    H.evolucion_id,
                    I.diagnostico_nombre,
                    H.usuario_id,
                    1 AS control
				FROM hc_odontogramas_primera_vez_detalle AS A,
				hc_tipos_cuadrantes_dientes AS B,
				hc_tipos_problemas_dientes AS C,
				hc_tipos_productos_dientes AS D,
				hc_tipos_problemas_soluciones_dientes AS E,
				cups AS F, hc_odontogramas_tratamientos_evolucion_primera_vez AS H,
                    diagnosticos AS I
				WHERE A.hc_odontograma_primera_vez_id=".$odonto[$i][0]."
				AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
				AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
				AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
				AND A.hc_tipo_problema_diente_id=E.hc_tipo_problema_diente_id
				AND A.hc_tipo_producto_diente_id=E.hc_tipo_producto_diente_id
                    AND A.hc_odontograma_primera_vez_detalle_id=H.hc_odontograma_primera_vez_detalle_id
                    AND H.diagnostico_id=I.diagnostico_id
                    AND H.sw_principal = 1
				AND (A.estado='0'
                    )
				AND E.cargo=F.cargo
				ORDER BY A.hc_tipo_ubicacion_diente_id, control)
                    
                    UNION

				(SELECT A.hc_tipo_ubicacion_diente_id,
				A.hc_odontograma_tratamiento_id AS odontogramas,
                     B.descripcion AS des1, 
                     C.descripcion AS des2, 
                     D.descripcion AS des3, 
                     E.cargo, 
                     F.descripcion AS des4, 
                     H.diagnostico_id, 
                     H.fecha_registro, 
                     H.evolucion_id, 
                     I.diagnostico_nombre,
                     H.usuario_id,
                     2 AS control

				FROM hc_odontogramas_tratamientos_detalle AS A, 
                    hc_tipos_cuadrantes_dientes AS B, hc_tipos_problemas_dientes AS C,
                    hc_tipos_productos_dientes AS D, 
                    hc_tipos_problemas_soluciones_dientes AS E, cups AS F, 
                    hc_odontogramas_tratamientos_evolucion_tratamiento AS H, 
                    diagnosticos AS I 

				WHERE A.hc_odontograma_tratamiento_id=".$odontotra."
                    AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id 
                    AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id 
                    AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id 
                    AND A.hc_tipo_problema_diente_id=E.hc_tipo_problema_diente_id 
                    AND A.hc_tipo_producto_diente_id=E.hc_tipo_producto_diente_id 
                    AND A.hc_odontograma_tratamiento_detalle_id=H.hc_odontograma_tratamiento_detalle_id 
                    AND H.diagnostico_id=I.diagnostico_id
                    AND H.sw_principal = 1
                    AND (A.estado='0'
                    OR A.estado='4')
                    AND E.cargo=F.cargo 
                    ORDER BY A.hc_tipo_ubicacion_diente_id, control);";

				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while($var = $resulta->FetchRow())
				{
					$odonto[$i][evo][] = $var;
				}
			}
		}
          $this->RegistrarSubmodulo($this->GetVersion());
		return $odonto;
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

	function NombreUs($user)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT nombre
		FROM system_usuarios
		WHERE usuario_id=".$user.";";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

		list($usuario) = $resulta->FetchRow();
		return $usuario;
	}

	function BuscarApoyosOdontograma()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT A.hc_odontograma_primera_vez_id,
          A.evolucion_id,
		B.cargo,
		B.cantidad,
		B.estado,
		C.descripcion,
          D.fecha,
          B.cantidad_pend
		FROM hc_odontogramas_primera_vez AS A 
          LEFT JOIN hc_evoluciones AS D on (A.evolucion_id=D.evolucion_id),
		hc_odontogramas_primera_vez_apoyod AS B,
          cups AS c
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		AND A.sw_activo='1'
		AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
          AND (B.estado='0' OR (B.estado='1' AND B.cantidad_pend > 0))
		AND B.cargo=C.cargo;";
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

     function Select_DX_Apoyos($cargo, $odontograma)
	{
     	GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$query="SELECT diagnostico_id, evolucion_id AS evolucion_ppto, 
          fecha_registro AS fechareg_ppto, usuario_id AS usuarioid_ppto,
          cantidad_realizada
		FROM hc_odontogramas_tratamientos_evolucion_apoyod
		WHERE cargo = $cargo
		AND hc_odontograma_primera_vez_id = $odontograma
          AND sw_principal='1';";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
          $resulta = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          while($data = $resulta->FetchRow())
          {
          	$dx_ppto[] = $data;
          }
          return $dx_ppto;
	}
     
	function BuscarPresupuestosOdontograma()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT A.hc_odontograma_primera_vez_id,
          A.evolucion_id,
		B.cargo,
		B.cantidad,
		B.estado,
		C.descripcion,
		D.fecha,
		B.cantidad_pend,
		B.hc_odontogramas_primera_vez_presupuesto_id
		FROM hc_odontogramas_primera_vez AS A 
          LEFT JOIN hc_evoluciones AS D on (A.evolucion_id=D.evolucion_id),
		hc_odontogramas_primera_vez_presupuesto AS B,
          cups AS c
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		AND A.sw_activo='1'
		AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
          AND (B.estado='0' OR (B.estado='1' AND B.cantidad_pend > 0))
		AND B.cargo=C.cargo;";
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
     
	
     function Select_DX_Presupuestos($cargo, $odontograma,$hc_odontogramas_primera_vez_presupuesto_id)
	{
     	GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$query="SELECT diagnostico_id, evolucion_id AS evolucion_ppto, 
									fecha_registro AS fechareg_ppto, usuario_id AS usuarioid_ppto,
									cantidad_realizada
						FROM hc_odontogramas_tratamientos_evolucion_presupuesto
						WHERE cargo = $cargo
						AND hc_odontograma_primera_vez_id = $odontograma
						AND sw_principal='1'
						AND hc_odontogramas_tratamientos_evolucion_presupuesto_id=$hc_odontogramas_primera_vez_presupuesto_id;";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
		$resulta = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

/*	echo	$query="SELECT diagnostico_id, evolucion_id AS evolucion_ppto, 
					fecha_registro AS fechareg_ppto, usuario_id AS usuarioid_ppto,
					cantidad_realizada
		FROM hc_odontogramas_tratamientos_evolucion_presupuesto
		WHERE cargo = $cargo
		AND hc_odontograma_primera_vez_id = $odontograma
				AND sw_principal='1';";
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
				$resulta = $dbconn->Execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;*/
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while($data = $resulta->FetchRow())
          {
          	$dx_ppto[] = $data;
          }
          return $dx_ppto;
	}
    
}

?>
