
<?php

/**
* Submodulo de Diagrama de Indice de Placa Bacteriana Oleary.
*
* Submodulo para manejar el IPB Oleary del paciente.
* @author Jorge Eliecer Avila Garzon <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_DiagramaIPBOlearyTra.php,v 1.18 2007/07/09 19:20:53 tizziano Exp $
*/

/**
* Diagrama de IPB Oleary
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de DiagrmaIPBOleary.
*/

class DiagramaIPBOlearyTra extends hc_classModules
{

	function DiagramaIPBOlearyTra()
	{
		return true;
	}

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

	function GetReporte_Html()
	{
		$imprimir=$this->frmHistoria();
		if($imprimir==false)
		{
			return true;
		}
		return $imprimir;
	}

	function GetConsulta()
	{
		if($this->frmConsulta()==false)
		{
			return true;
		}
		return $this->salida;
	}

	function GetEstado()
	{
		list($dbconn) = GetDBconn();
		$query="UPDATE hc_indice_ipb_oleary_trata SET
		sw_activo='0'
		WHERE evolucion_id=".$this->evolucion."
		AND tipo_id_paciente='".$this->tipoidpaciente."'
		AND paciente_id='".$this->paciente."'
		AND sw_activo='1';";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			return false;
		}
	 	return true;
	}

	function GetForma()//Desde esta funcion es de JORGE AVILA
	{
		$pfj=$this->frmPrefijo;
		if(empty($_REQUEST['accion'.$pfj]))
		{
			$this->frmForma();
		}
		elseif($_REQUEST['accion'.$pfj]=='insertar')
		{
			if($this->InsertDatos()==true)
			{
				$this->frmForma();
			}
		}
		elseif($_REQUEST['accion'.$pfj]=='eliminar')
		{
			if($this->EliminDatos()==true)
			{
				$this->frmForma();
			}
		}
		return $this->salida;
	}

	function BuscarTipoUbicacion()
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT hc_tipo_ubicacion_diente_id,
		indice_orden
		FROM hc_tipos_ubicaciones_dientes
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

	function BuscarTipoCuadrantes()
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT A.hc_tipo_cuadrante_id,
		A.descripcion,
		A.indice_orden
		FROM hc_tipos_cuadrantes_dientes AS A,
		hc_tipos_cuadrantes_dientes_oleary AS B
		WHERE A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_diente_oleary_id
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

	function BuscarOdontogramaControl()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT hc_odontograma_primera_vez_id
		FROM hc_odontogramas_primera_vez
		WHERE evolucion_id=".$this->evolucion."
		AND tipo_id_paciente='".$this->tipoidpaciente."'
		AND paciente_id='".$this->paciente."'
		AND sw_activo='1';";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $resulta->fields[0];
	}

	function BuscarIPBOleary()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT A.hc_indice_ipb_oleary_trata_detalle_id,
		A.hc_tipo_ubicacion_diente_id,
		A.fecha_registro,
		B.descripcion AS des1
		FROM hc_indice_ipb_oleary_trata_detalle AS A,
		hc_tipos_cuadrantes_dientes AS B,
		hc_indice_ipb_oleary_trata AS C
		WHERE A.hc_indice_ipb_oleary_trata_id=C.hc_indice_ipb_oleary_trata_id
		AND A.hc_tipo_cuadrante_diente_oleary_id=B.hc_tipo_cuadrante_id
		AND C.evolucion_id=".$this->evolucion."
		AND C.tipo_id_paciente='".$this->tipoidpaciente."'
		AND C.paciente_id='".$this->paciente."'
		AND C.sw_activo='1'
		ORDER BY A.hc_tipo_ubicacion_diente_id,B.descripcion;";
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

	function BuscarIPBOlearyConsulta()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT A.hc_indice_ipb_oleary_trata_detalle_id,
		A.hc_tipo_ubicacion_diente_id,
		A.fecha_registro,
		B.descripcion AS des1
		FROM hc_indice_ipb_oleary_trata_detalle AS A,
		hc_tipos_cuadrantes_dientes AS B
		WHERE A.hc_indice_ipb_oleary_trata_id=
		(SELECT MAX(C.hc_indice_ipb_oleary_trata_id)
		FROM hc_indice_ipb_oleary_trata AS C
		WHERE C.tipo_id_paciente='".$this->tipoidpaciente."'
		AND C.paciente_id='".$this->paciente."')
		AND A.hc_tipo_cuadrante_diente_oleary_id=B.hc_tipo_cuadrante_id
		ORDER BY A.hc_tipo_ubicacion_diente_id,B.descripcion;";
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

// 	function InsertDatos()
// 	{
// 		$pfj=$this->frmPrefijo;
// 		$this->frmError["MensajeError"]="";
// 		if($_REQUEST['tipoubicpb'.$pfj]==NULL OR $_REQUEST['tipocuadpb'.$pfj]==NULL)
// 		{
// 			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
// 			return true;
// 		}
// 		else
// 		{
// 			list($dbconn) = GetDBconn();
// 			$dbconn->BeginTrans();
// 			$query="SELECT hc_indice_ipb_oleary_id
// 			FROM hc_indice_ipb_oleary
// 			WHERE tipo_id_paciente='".$this->tipoidpaciente."'
// 			AND paciente_id='".$this->paciente."'
// 			AND sw_activo='1';";
// 			$resulta = $dbconn->Execute($query);
// 			if($dbconn->ErrorNo() != 0)
// 			{
// 				$this->error = "Error al Cargar el Modulo";
// 				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 				return false;
// 			}
// 			$ipbpri=$resulta->fields[0];
// 			if(!empty($ipbpri))
// 			{
// 				$query="SELECT hc_indice_ipb_oleary_trata_id
// 				FROM hc_indice_ipb_oleary_trata
// 				WHERE evolucion_id=".$this->evolucion."
// 				AND tipo_id_paciente='".$this->tipoidpaciente."'
// 				AND paciente_id='".$this->paciente."'
// 				AND sw_activo='1'
// 				AND hc_indice_ipb_oleary_id=".$ipbpri.";";
// 				$resulta = $dbconn->Execute($query);
// 				if($dbconn->ErrorNo() != 0)
// 				{
// 					$this->error = "Error al Cargar el Modulo";
// 					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 					return false;
// 				}
// 				$ipbtra=$resulta->fields[0];
// 				if(empty($ipbtra))
// 				{
// 					$query="SELECT NEXTVAL ('hc_indice_ipb_oleary_trata_hc_indice_ipb_oleary_trata_id_seq');";
// 					$resulta = $dbconn->Execute($query);
// 					if($dbconn->ErrorNo() != 0)
// 					{
// 						$this->error = "Error al Cargar el Modulo";
// 						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 						return false;
// 					}
// 					$ipbtra=$resulta->fields[0];
// 					$query="INSERT INTO hc_indice_ipb_oleary_trata
// 					(hc_indice_ipb_oleary_trata_id,
// 					tipo_id_paciente,
// 					paciente_id,
// 					evolucion_id,
// 					sw_activo,
// 					hc_indice_ipb_oleary_id)
// 					VALUES
// 					(".$ipbtra.",
// 					'".$this->tipoidpaciente."',
// 					'".$this->paciente."',
// 					".$this->evolucion.",
// 					'1',
// 					".$ipbpri.");";
// 					$resulta = $dbconn->Execute($query);
// 					if($dbconn->ErrorNo() != 0)
// 					{
// 						$this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
// 						$dbconn->RollbackTrans();
// 						return false;
// 					}
// 				}
// 			}
// 			else
// 			{
// 				$this->frmError["MensajeError"]="EL IPB O'LEARY DE PRIMERA VEZ NO SE ENCONTRÓ";
// 				return true;
// 			}
// 			$query="SELECT B.hc_tipo_ubicacion_diente_id
// 			FROM hc_odontogramas_primera_vez AS A,
// 			hc_odontogramas_primera_vez_detalle AS B
// 			WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
// 			AND A.paciente_id='".$this->paciente."'
// 			AND A.sw_activo='1'
// 			AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
// 			AND B.hc_tipo_ubicacion_diente_id=".$_REQUEST['tipoubicpb'.$pfj]."
// 			AND
// 			(
// 				(
// 					(
// 						B.hc_tipo_problema_diente_id=2
// 						OR B.hc_tipo_problema_diente_id=4
// 						OR B.hc_tipo_problema_diente_id=5
// 						OR B.hc_tipo_problema_diente_id=8
// 						OR B.hc_tipo_problema_diente_id=12
// 						OR B.hc_tipo_problema_diente_id=31
// 					)
// 					AND B.estado<'5'
// 				)
// 				OR
// 				(
// 					(
// 						B.hc_tipo_problema_diente_id=3
// 						OR B.hc_tipo_problema_diente_id=23
// 					)
// 					AND B.estado='0'
// 				)
// 			);";
// 			$resulta = $dbconn->Execute($query);
// 			if($dbconn->ErrorNo() != 0)
// 			{
// 				$this->error = "Error al Cargar el Modulo";
// 				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 				return false;
// 			}
// 			if($resulta->fields[0]<>NULL)
// 			{
// 				$this->frmError["MensajeError"]="EL DIENTE ".$_REQUEST['tipoubicpb'.$pfj]." NO ESTÁ EN BOCA";
// 				return true;
// 			}
// 			$query="SELECT B.hc_tipo_ubicacion_diente_id
// 			FROM hc_odontogramas_tratamientos AS A,
// 			hc_odontogramas_tratamientos_detalle AS B
// 			WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
// 			AND A.paciente_id='".$this->paciente."'
// 			AND A.sw_activo='1'
// 			AND A.hc_odontograma_tratamiento_id=B.hc_odontograma_tratamiento_id
// 			AND B.hc_tipo_ubicacion_diente_id=".$_REQUEST['tipoubicpb'.$pfj]."
// 			AND (B.hc_tipo_problema_diente_id=3
// 			OR B.hc_tipo_problema_diente_id=23)
// 			AND B.estado='0';";
// 			$resulta = $dbconn->Execute($query);
// 			if($dbconn->ErrorNo() != 0)
// 			{
// 				$this->error = "Error al Cargar el Modulo";
// 				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 				return false;
// 			}
// 			if($resulta->fields[0]<>NULL)
// 			{
// 				$this->frmError["MensajeError"]="EL DIENTE ".$_REQUEST['tipoubicpb'.$pfj]." NO ESTÁ EN BOCA";
// 				return true;
// 			}
// 			$a=explode(',',$_REQUEST['tipocuadpb'.$pfj]);
// 			if((($_REQUEST['tipoubicpb'.$pfj]>=11 AND $_REQUEST['tipoubicpb'.$pfj]<=28)
// 			OR ($_REQUEST['tipoubicpb'.$pfj]>=51 AND $_REQUEST['tipoubicpb'.$pfj]<=65))
// 			AND ($a[0]==3 OR $a[1]==3 OR $a[2]==3))//($_REQUEST['tipocuadpb'.$pfj]==3)
// 			{
// 				$this->frmError["MensajeError"]="SUPERFICIE NO VÁLIDA PARA EL DIENTE ".$_REQUEST['tipoubicpb'.$pfj]."";
// 				return true;
// 			}
// 			else if((($_REQUEST['tipoubicpb'.$pfj]>=31 AND $_REQUEST['tipoubicpb'.$pfj]<=48)
// 			OR ($_REQUEST['tipoubicpb'.$pfj]>=71 AND $_REQUEST['tipoubicpb'.$pfj]<=85))
// 			AND ($a[0]==2 OR $a[1]==2 OR $a[2]==2))//($_REQUEST['tipocuadpb'.$pfj]==2)
// 			{
// 				$this->frmError["MensajeError"]="SUPERFICIE NO VÁLIDA PARA EL DIENTE ".$_REQUEST['tipoubicpb'.$pfj]."";
// 				return true;
// 			}
// 			if($a[0]<>0)
// 			{
// 				$query="SELECT hc_indice_ipb_oleary_trata_detalle_id
// 				FROM hc_indice_ipb_oleary_trata_detalle
// 				WHERE hc_indice_ipb_oleary_trata_id=".$ipbtra."
// 				AND hc_tipo_cuadrante_diente_oleary_id=".$a[0]."
// 				AND hc_tipo_ubicacion_diente_id='".$_REQUEST['tipoubicpb'.$pfj]."';";
// 				$resulta = $dbconn->Execute($query);
// 				if($dbconn->ErrorNo() != 0)
// 				{
// 					$this->error = "Error al Cargar el Modulo";
// 					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 					return false;
// 				}
// 				if(empty($resulta->fields[0]))
// 				{
// 					$query="INSERT INTO hc_indice_ipb_oleary_trata_detalle
// 					(hc_indice_ipb_oleary_trata_id,
// 					hc_tipo_cuadrante_diente_oleary_id,
// 					hc_tipo_ubicacion_diente_id)
// 					VALUES
// 					(".$ipbtra.",
// 					".$a[0].",
// 					'".$_REQUEST['tipoubicpb'.$pfj]."');";
// 					$resulta = $dbconn->Execute($query);
// 					if($dbconn->ErrorNo() != 0)
// 					{
// 						$this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
// 						$dbconn->RollbackTrans();
// 						return false;
// 					}
// 					$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
// 				}
// 				else
// 				{
// 					$this->frmError["MensajeError"]="DATOS IGUALES YA ALMACENADOS";
// 				}
// 			}
// 			if($a[1]<>0)
// 			{
// 				$query="SELECT hc_indice_ipb_oleary_trata_detalle_id
// 				FROM hc_indice_ipb_oleary_trata_detalle
// 				WHERE hc_indice_ipb_oleary_trata_id=".$ipbtra."
// 				AND hc_tipo_cuadrante_diente_oleary_id=".$a[1]."
// 				AND hc_tipo_ubicacion_diente_id='".$_REQUEST['tipoubicpb'.$pfj]."';";
// 				$resulta = $dbconn->Execute($query);
// 				if($dbconn->ErrorNo() != 0)
// 				{
// 					$this->error = "Error al Cargar el Modulo";
// 					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 					return false;
// 				}
// 				if(empty($resulta->fields[0]))
// 				{
// 					$query="INSERT INTO hc_indice_ipb_oleary_trata_detalle
// 					(hc_indice_ipb_oleary_trata_id,
// 					hc_tipo_cuadrante_diente_oleary_id,
// 					hc_tipo_ubicacion_diente_id)
// 					VALUES
// 					(".$ipbtra.",
// 					".$a[1].",
// 					'".$_REQUEST['tipoubicpb'.$pfj]."');";
// 					$resulta = $dbconn->Execute($query);
// 					if($dbconn->ErrorNo() != 0)
// 					{
// 						$this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
// 						$dbconn->RollbackTrans();
// 						return false;
// 					}
// 					$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
// 				}
// 				else
// 				{
// 					$this->frmError["MensajeError"]="DATOS IGUALES YA ALMACENADOS";
// 				}
// 			}
// 			if($a[2]<>0)
// 			{
// 				$query="SELECT hc_indice_ipb_oleary_trata_detalle_id
// 				FROM hc_indice_ipb_oleary_trata_detalle
// 				WHERE hc_indice_ipb_oleary_trata_id=".$ipbtra."
// 				AND hc_tipo_cuadrante_diente_oleary_id=".$a[2]."
// 				AND hc_tipo_ubicacion_diente_id='".$_REQUEST['tipoubicpb'.$pfj]."';";
// 				$resulta = $dbconn->Execute($query);
// 				if($dbconn->ErrorNo() != 0)
// 				{
// 					$this->error = "Error al Cargar el Modulo";
// 					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 					return false;
// 				}
// 				if(empty($resulta->fields[0]))
// 				{
// 					$query="INSERT INTO hc_indice_ipb_oleary_trata_detalle
// 					(hc_indice_ipb_oleary_trata_id,
// 					hc_tipo_cuadrante_diente_oleary_id,
// 					hc_tipo_ubicacion_diente_id)
// 					VALUES
// 					(".$ipbtra.",
// 					".$a[2].",
// 					'".$_REQUEST['tipoubicpb'.$pfj]."');";
// 					$resulta = $dbconn->Execute($query);
// 					if($dbconn->ErrorNo() != 0)
// 					{
// 						$this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
// 						$dbconn->RollbackTrans();
// 						return false;
// 					}
// 					$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
// 				}
// 				else
// 				{
// 					$this->frmError["MensajeError"]="DATOS IGUALES YA ALMACENADOS";
// 				}
// 			}
// 			$dbconn->CommitTrans();
// 			return true;
// 		}
// 	}

  //MODIFICACIÓN DE LA FUNCIÓN INSERTAR DATOS PARA PODER PROCESAR VARIAS
  //SUPERFICIES A LA VEZ CON EL MISMO DIAGNOSTICO.
  function InsertDatos()
  {
    $val=false;//VALIDAR QUE POR LO MENOS HALLA UN TIPO DE UBICACION A INSERTAR
    $pfj=$this->frmPrefijo;
    $this->frmError["MensajeError"]="";
		$fecha_registro=date("Y-m-d");
    //FOR PARA LOS $_REQUEST['tipoubicpb'.$i] ANTES ERA $_REQUEST['tipoubicpb'.$pfj]
    for($i=11; $i<86; $i++)
    {
      if($_REQUEST['tipoubicpb'.$i]==on AND $_REQUEST['tipocuadpb'.$pfj]<>NULL)
      {
        $val=true;//VALIDAR QUE POR LO MENOS HALLA UN TIPO DE UBICACION A INSERTAR
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="SELECT hc_indice_ipb_oleary_id
        FROM hc_indice_ipb_oleary
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        $ipbpri=$resulta->fields[0];
        if(!empty($ipbpri))
        {
          $query="SELECT hc_indice_ipb_oleary_trata_id
          FROM hc_indice_ipb_oleary_trata
          WHERE evolucion_id=".$this->evolucion."
          AND tipo_id_paciente='".$this->tipoidpaciente."'
          AND paciente_id='".$this->paciente."'
          AND sw_activo='1'
          AND hc_indice_ipb_oleary_id=".$ipbpri.";";
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          $ipbtra=$resulta->fields[0];
          if(empty($ipbtra))
          {
            $query="SELECT NEXTVAL ('hc_indice_ipb_oleary_trata_hc_indice_ipb_oleary_trata_id_seq');";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
            $ipbtra=$resulta->fields[0];
            $query="INSERT INTO hc_indice_ipb_oleary_trata
            (hc_indice_ipb_oleary_trata_id,
            tipo_id_paciente,
            paciente_id,
            evolucion_id,
            sw_activo,
            hc_indice_ipb_oleary_id,
						fecha_registro)
            VALUES
            (".$ipbtra.",
            '".$this->tipoidpaciente."',
            '".$this->paciente."',
            ".$this->evolucion.",
            '1',
            ".$ipbpri.",
						now());";//'".$fecha_registro."'
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
              $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
              $dbconn->RollbackTrans();
              return false;
            }
          }
        }
        else
        {
          $this->frmError["MensajeError"]="EL IPB O'LEARY DE PRIMERA VEZ NO SE ENCONTRÓ";
          return true;
        }
        $query="SELECT B.hc_tipo_ubicacion_diente_id
        FROM hc_odontogramas_primera_vez AS A,
        hc_odontogramas_primera_vez_detalle AS B
        WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND A.sw_activo='1'
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND B.hc_tipo_ubicacion_diente_id=".$i."
        AND
        (
          (
            (
              B.hc_tipo_problema_diente_id=2
              OR B.hc_tipo_problema_diente_id=4
              OR B.hc_tipo_problema_diente_id=5
              OR B.hc_tipo_problema_diente_id=8
              OR B.hc_tipo_problema_diente_id=12
              OR B.hc_tipo_problema_diente_id=31
            )
            AND B.estado<'5'
          )
          OR
          (
            (
              B.hc_tipo_problema_diente_id=3
              OR B.hc_tipo_problema_diente_id=23
            )
            AND B.estado='0'
          )
        );";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        if($resulta->fields[0]<>NULL)
        {
          $this->frmError["MensajeError"]="EL DIENTE ".$i." NO ESTÁ EN BOCA";
          return true;
        }
        $query="SELECT B.hc_tipo_ubicacion_diente_id
        FROM hc_odontogramas_tratamientos AS A,
        hc_odontogramas_tratamientos_detalle AS B
        WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND A.sw_activo='1'
        AND A.hc_odontograma_tratamiento_id=B.hc_odontograma_tratamiento_id
        AND B.hc_tipo_ubicacion_diente_id=".$i."
        AND (B.hc_tipo_problema_diente_id=3
        OR B.hc_tipo_problema_diente_id=23)
        AND B.estado='0';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        if($resulta->fields[0]<>NULL)
        {
          $this->frmError["MensajeError"]="EL DIENTE ".$i." NO ESTÁ EN BOCA";
          return true;
        }
        $a=explode(',',$_REQUEST['tipocuadpb'.$pfj]);
        if((($i>=11 AND $i<=28)
        OR ($i>=51 AND $i<=65))
        AND ($a[0]==3 OR $a[1]==3 OR $a[2]==3))//($_REQUEST['tipocuadpb'.$pfj]==3)
        {
          $this->frmError["MensajeError"]="SUPERFICIE NO VÁLIDA PARA EL DIENTE ".$i."";
          return true;
        }
        else if((($i>=31 AND $i<=48)
        OR ($i>=71 AND $i<=85))
        AND ($a[0]==2 OR $a[1]==2 OR $a[2]==2))//($_REQUEST['tipocuadpb'.$pfj]==2)
        {
          $this->frmError["MensajeError"]="SUPERFICIE NO VÁLIDA PARA EL DIENTE ".$i."";
          return true;
        }
        if($a[0]<>0)
        {
          $query="SELECT hc_indice_ipb_oleary_trata_detalle_id
          FROM hc_indice_ipb_oleary_trata_detalle
          WHERE hc_indice_ipb_oleary_trata_id=".$ipbtra."
          AND hc_tipo_cuadrante_diente_oleary_id=".$a[0]."
          AND hc_tipo_ubicacion_diente_id='".$i."';";
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          if(empty($resulta->fields[0]))
          {
            $query="INSERT INTO hc_indice_ipb_oleary_trata_detalle
            (hc_indice_ipb_oleary_trata_id,
            hc_tipo_cuadrante_diente_oleary_id,
            hc_tipo_ubicacion_diente_id,
						fecha_registro)
            VALUES
            (".$ipbtra.",
            ".$a[0].",
            '".$i."',
						now());";//'".$fecha_registro."'
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
              $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
              $dbconn->RollbackTrans();
              return false;
            }
            $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
          }
          else
          {
            $this->frmError["MensajeError"]="DATOS IGUALES YA ALMACENADOS";
          }
        }
        if($a[1]<>0)
        {
          $query="SELECT hc_indice_ipb_oleary_trata_detalle_id
          FROM hc_indice_ipb_oleary_trata_detalle
          WHERE hc_indice_ipb_oleary_trata_id=".$ipbtra."
          AND hc_tipo_cuadrante_diente_oleary_id=".$a[1]."
          AND hc_tipo_ubicacion_diente_id='".$i."';";
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          if(empty($resulta->fields[0]))
          {
            $query="INSERT INTO hc_indice_ipb_oleary_trata_detalle
            (hc_indice_ipb_oleary_trata_id,
            hc_tipo_cuadrante_diente_oleary_id,
            hc_tipo_ubicacion_diente_id,
						fecha_registro)
            VALUES
            (".$ipbtra.",
            ".$a[1].",
            '".$i."',
						now());";//'".$fecha_registro."'
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
              $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
              $dbconn->RollbackTrans();
              return false;
            }
            $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
          }
          else
          {
            $this->frmError["MensajeError"]="DATOS IGUALES YA ALMACENADOS";
          }
        }
        if($a[2]<>0)
        {
          $query="SELECT hc_indice_ipb_oleary_trata_detalle_id
          FROM hc_indice_ipb_oleary_trata_detalle
          WHERE hc_indice_ipb_oleary_trata_id=".$ipbtra."
          AND hc_tipo_cuadrante_diente_oleary_id=".$a[2]."
          AND hc_tipo_ubicacion_diente_id='".$i."';";
          $resulta = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          if(empty($resulta->fields[0]))
          {
            $query="INSERT INTO hc_indice_ipb_oleary_trata_detalle
            (hc_indice_ipb_oleary_trata_id,
            hc_tipo_cuadrante_diente_oleary_id,
            hc_tipo_ubicacion_diente_id,
						fecha_registro)
            VALUES
            (".$ipbtra.",
            ".$a[2].",
            '".$i."',
						now());";//'".$fecha_registro."'
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
              $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
              $dbconn->RollbackTrans();
              return false;
            }
            $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
          }
          else
          {
            $this->frmError["MensajeError"]="DATOS IGUALES YA ALMACENADOS";
          }
        }
        $dbconn->CommitTrans();
      }//FIN IF DE LOS on 
      else
        if($_REQUEST['tipocuadpb'.$pfj]==NULL)
        { 
          $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS(tipo cuadrante.)";
          return true;
        }
    }//FIN FOR DE LOS $_REQUEST['op'.$i]  
    if(!$val)
    { 
      $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS(tipo ubicación.)";
      return true;
    }
    else
    {
     $this->RegistrarSubmodulo($this->GetVersion());
	return true;
    }
  }
  //FIN MODIFICACIÓN DE INSERTAR DATOS

  function EliminDatos()
  {
    $pfj=$this->frmPrefijo;
    list($dbconn) = GetDBconn();
    $query="DELETE FROM hc_indice_ipb_oleary_trata_detalle
    WHERE hc_indice_ipb_oleary_trata_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
    $resulta = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0)
    {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
    else
    {
      $query="DELETE FROM hc_indice_ipb_oleary_trata_detalle
      WHERE evolucion_id=".$this->evolucion."
      AND tipo_id_paciente='".$this->tipoidpaciente."'
      AND paciente_id='".$this->paciente."'
      AND sw_activo='1';";
      $resulta = $dbconn->Execute($query);
      $this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
      return true;
    }
	}

	function CalcularIPBOlearyTra()
	{
//(numero de superficies con placa/numero de superficies totales)*100
//numero de superficies totales=numero de dientes * 4, tengo que buscar el numero de dientes del paciente
		$val=($this->BuscarDienteCantidadNoBocaTra1()+
		$this->BuscarDienteCantidadNoBocaTra3());
		$numerodien=52-$val;
		$placab=$this->BuscarSuperficiesplacaTra();
		$numerosupe=($numerodien*4);
		$ipboleary=($placab/$numerosupe)*100;
		return $ipboleary;
	}

	function BuscarDienteCantidadNoBocaTra1()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id
		FROM hc_odontogramas_primera_vez AS A,
		hc_odontogramas_primera_vez_detalle AS B
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		AND A.sw_activo='1'
		AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
		AND ((B.estado IN ('1', '2', '3', '4')
		AND (B.hc_tipo_problema_diente_id=2
		OR B.hc_tipo_problema_diente_id=4
		OR B.hc_tipo_problema_diente_id=5
		OR B.hc_tipo_problema_diente_id=8
		OR B.hc_tipo_problema_diente_id=12
		OR B.hc_tipo_problema_diente_id=31))
		OR (B.estado IN ('0')
		AND (B.hc_tipo_problema_diente_id=3
		OR B.hc_tipo_problema_diente_id=23)))
		ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$var=$resulta->RecordCount();
		return $var;
	}

	function BuscarDienteCantidadNoBocaTra3()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id
		FROM hc_odontogramas_tratamientos AS A,
		hc_odontogramas_tratamientos_detalle AS B
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		AND A.sw_activo='1'
		AND A.hc_odontograma_tratamiento_id=B.hc_odontograma_tratamiento_id
		AND ((B.estado IN ('1', '2', '3', '4')
		AND (B.hc_tipo_problema_diente_id=2
		OR B.hc_tipo_problema_diente_id=4
		OR B.hc_tipo_problema_diente_id=5
		OR B.hc_tipo_problema_diente_id=8
		OR B.hc_tipo_problema_diente_id=12
		OR B.hc_tipo_problema_diente_id=31))
		OR (B.estado IN ('0')
		AND (B.hc_tipo_problema_diente_id=3
		OR B.hc_tipo_problema_diente_id=23)))
		ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$var=$resulta->RecordCount();
		return $var;
	}

	function CalcularIPBOlearyTraConsulta()
	{
//(numero de superficies con placa/numero de superficies totales)*100
//numero de superficies totales=numero de dientes * 4, tengo que buscar el numero de dientes del paciente
		$val=($this->BuscarDienteCantidadNoBocaTra1Consulta()+
		$this->BuscarDienteCantidadNoBocaTra3Consulta());
		$numerodien=52-$val;
		$placab=$this->BuscarSuperficiesplacaTraConsulta();
		$numerosupe=($numerodien*4);
		$ipboleary=($placab/$numerosupe)*100;
		return $ipboleary;
	}

	function BuscarDienteCantidadNoBocaTra1Consulta()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id
		FROM hc_odontogramas_primera_vez AS A,
		hc_odontogramas_primera_vez_detalle AS B
		WHERE A.hc_odontograma_primera_vez_id=
		(SELECT MAX(D.hc_odontograma_primera_vez_id)
		FROM hc_odontogramas_primera_vez AS D
		WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
		AND D.paciente_id='".$this->paciente."')
		AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
		AND ((B.estado IN ('1', '2', '3', '4')
		AND (B.hc_tipo_problema_diente_id=2
		OR B.hc_tipo_problema_diente_id=4
		OR B.hc_tipo_problema_diente_id=5
		OR B.hc_tipo_problema_diente_id=8
		OR B.hc_tipo_problema_diente_id=12
		OR B.hc_tipo_problema_diente_id=31))
		OR (B.estado IN ('0')
		AND (B.hc_tipo_problema_diente_id=3
		OR B.hc_tipo_problema_diente_id=23)))
		ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$var=$resulta->RecordCount();
		return $var;
	}

	function BuscarDienteCantidadNoBocaTra3Consulta()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id
		FROM hc_odontogramas_tratamientos AS A,
		hc_odontogramas_tratamientos_detalle AS B
		WHERE A.hc_odontograma_tratamiento_id=
		(SELECT MAX(D.hc_odontograma_tratamiento_id)
		FROM hc_odontogramas_tratamientos AS D
		WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
		AND D.paciente_id='".$this->paciente."')
		AND A.hc_odontograma_tratamiento_id=B.hc_odontograma_tratamiento_id
		AND ((B.estado IN ('1', '2', '3', '4')
		AND (B.hc_tipo_problema_diente_id=2
		OR B.hc_tipo_problema_diente_id=4
		OR B.hc_tipo_problema_diente_id=5
		OR B.hc_tipo_problema_diente_id=8
		OR B.hc_tipo_problema_diente_id=12
		OR B.hc_tipo_problema_diente_id=31))
		OR (B.estado IN ('0')
		AND (B.hc_tipo_problema_diente_id=3
		OR B.hc_tipo_problema_diente_id=23)))
		ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$var=$resulta->RecordCount();
		return $var;
	}

	function CalcularIPBOleary()
	{
//(numero de superficies con placa/numero de superficies totales)*100
//numero de superficies totales=numero de dientes * 4, tengo que buscar el numero de dientes del paciente
		$numerodien=52-$this->BuscarDienteCantidadNoBoca();
		$placab=$this->BuscarSuperficiesplaca();
		$numerosupe=($numerodien*4);
		$ipboleary=($placab/$numerosupe)*100;
		return $ipboleary;
	}

	function CalcularIPBOlearyConsulta()
	{
//(numero de superficies con placa/numero de superficies totales)*100
//numero de superficies totales=numero de dientes * 4, tengo que buscar el numero de dientes del paciente
		$numerodien=52-$this->BuscarDienteCantidadNoBocaConsulta();
		$placab=$this->BuscarSuperficiesplacaConsulta();
		$numerosupe=($numerodien*4);
		$ipboleary=($placab/$numerosupe)*100;
		return $ipboleary;
	}

	function BuscarDienteCantidadNoBoca()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id
		FROM hc_odontogramas_primera_vez AS A,
		hc_odontogramas_primera_vez_detalle AS B
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		AND A.sw_activo='1'
		AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
		AND (B.hc_tipo_problema_diente_id=2
		OR B.hc_tipo_problema_diente_id=4
		OR B.hc_tipo_problema_diente_id=5
		OR B.hc_tipo_problema_diente_id=8
		OR B.hc_tipo_problema_diente_id=12
		OR B.hc_tipo_problema_diente_id=31)
		ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$var=$resulta->RecordCount();
		return $var;
	}

	function BuscarDienteCantidadNoBocaConsulta()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id
		FROM hc_odontogramas_primera_vez AS A,
		hc_odontogramas_primera_vez_detalle AS B
		WHERE A.hc_odontograma_primera_vez_id=
		(SELECT MAX(D.hc_odontograma_primera_vez_id)
		FROM hc_odontogramas_primera_vez AS D
		WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
		AND D.paciente_id='".$this->paciente."')
		AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
		AND (B.hc_tipo_problema_diente_id=2
		OR B.hc_tipo_problema_diente_id=4
		OR B.hc_tipo_problema_diente_id=5
		OR B.hc_tipo_problema_diente_id=8
		OR B.hc_tipo_problema_diente_id=12
		OR B.hc_tipo_problema_diente_id=31)
		ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$var=$resulta->RecordCount();
		return $var;
	}

	function BuscarSuperficiesplaca()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT B.hc_tipo_cuadrante_diente_oleary_id
		FROM hc_indice_ipb_oleary AS A,
		hc_indice_ipb_oleary_detalle AS B
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		AND A.sw_activo='1'
		AND A.hc_indice_ipb_oleary_id=B.hc_indice_ipb_oleary_id
		ORDER BY B.hc_tipo_cuadrante_diente_oleary_id;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$total=0;
		while(!$resulta->EOF)
		{
			if($resulta->fields[0]<>11)
			{
				$total++;
			}
			else
			{
				$total=$total+4;
			}
			$resulta->MoveNext();
		}
		return $total;
	}

	function BuscarSuperficiesplacaConsulta()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT B.hc_tipo_cuadrante_diente_oleary_id
		FROM hc_indice_ipb_oleary_detalle AS B
		WHERE B.hc_indice_ipb_oleary_id=
		(SELECT MAX(A.hc_indice_ipb_oleary_id)
		FROM hc_indice_ipb_oleary AS A
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."')
		ORDER BY B.hc_tipo_cuadrante_diente_oleary_id;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$total=0;
		while(!$resulta->EOF)
		{
			if($resulta->fields[0]<>11)
			{
				$total++;
			}
			else
			{
				$total=$total+4;
			}
			$resulta->MoveNext();
		}
		return $total;
	}

	function BuscarSuperficiesplacaTra()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT B.hc_tipo_cuadrante_diente_oleary_id
		FROM hc_indice_ipb_oleary_trata AS A,
		hc_indice_ipb_oleary_trata_detalle AS B
		WHERE A.evolucion_id=".$this->evolucion."
		AND A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		AND A.sw_activo='1'
		AND A.hc_indice_ipb_oleary_trata_id=B.hc_indice_ipb_oleary_trata_id
		ORDER BY B.hc_tipo_cuadrante_diente_oleary_id;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$total=0;
		while(!$resulta->EOF)
		{
			if($resulta->fields[0]<>11)
			{
				$total++;
			}
			else
			{
				$total=$total+4;
			}
			$resulta->MoveNext();
		}
		return $total;
	}

	function BuscarSuperficiesplacaTraConsulta()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT B.hc_tipo_cuadrante_diente_oleary_id
		FROM hc_indice_ipb_oleary_trata_detalle AS B
		WHERE B.hc_indice_ipb_oleary_trata_id=
		(SELECT MAX(A.hc_indice_ipb_oleary_trata_id)
		FROM hc_indice_ipb_oleary_trata AS A
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."')
		ORDER BY B.hc_tipo_cuadrante_diente_oleary_id;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$total=0;
		while(!$resulta->EOF)
		{
			if($resulta->fields[0]<>11)
			{
				$total++;
			}
			else
			{
				$total=$total+4;
			}
			$resulta->MoveNext();
		}
		return $total;
	}

	function BuscarEnviarPintarOleary()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT B.hc_tipo_ubicacion_diente_id,
		B.hc_tipo_cuadrante_diente_oleary_id
		FROM hc_indice_ipb_oleary AS A,
		hc_indice_ipb_oleary_detalle AS B
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		AND A.sw_activo='1'
		AND A.hc_indice_ipb_oleary_id=B.hc_indice_ipb_oleary_id
		ORDER BY B.hc_tipo_ubicacion_diente_id,
		B.hc_tipo_cuadrante_diente_oleary_id;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i][0]=$resulta->fields[0];
			$var[$i][1]=$resulta->fields[1];
			$i++;
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarEnviarPintarOlearyConsulta()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT B.hc_tipo_ubicacion_diente_id,
		B.hc_tipo_cuadrante_diente_oleary_id
		FROM hc_indice_ipb_oleary_detalle AS B
		WHERE B.hc_indice_ipb_oleary_id=
		(SELECT MAX(A.hc_indice_ipb_oleary_id)
		FROM hc_indice_ipb_oleary AS A
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."')
		ORDER BY B.hc_tipo_ubicacion_diente_id,
		B.hc_tipo_cuadrante_diente_oleary_id;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i][0]=$resulta->fields[0];
			$var[$i][1]=$resulta->fields[1];
			$i++;
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarEnviarPintarNoBoca()//falta la copia del de tratamiento
	{
		list($dbconn) = GetDBconn();
		$query="SELECT B.hc_tipo_ubicacion_diente_id,
		B.hc_tipo_problema_diente_id
		FROM hc_odontogramas_primera_vez AS A,
		hc_odontogramas_primera_vez_detalle AS B
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		AND A.sw_activo='1'
		AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
		AND B.hc_tipo_ubicacion_diente_id<49
		AND (B.hc_tipo_problema_diente_id=2
		OR B.hc_tipo_problema_diente_id=4
		OR B.hc_tipo_problema_diente_id=5
		OR B.hc_tipo_problema_diente_id=8
		OR B.hc_tipo_problema_diente_id=12
		OR B.hc_tipo_problema_diente_id=31)
		ORDER BY B.hc_tipo_ubicacion_diente_id ASC;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i][0]=$resulta->fields[0];
			$var[$i][1]=$resulta->fields[1];
			$i++;
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarEnviarPintarNoBocaConsulta()//falta la copia del de tratamiento
	{
		list($dbconn) = GetDBconn();
		$query="SELECT B.hc_tipo_ubicacion_diente_id,
		B.hc_tipo_problema_diente_id
		FROM hc_odontogramas_primera_vez_detalle AS B
		WHERE B.hc_odontograma_primera_vez_id=
		(SELECT MAX(A.hc_odontograma_primera_vez_id)
		FROM hc_odontogramas_primera_vez AS A
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."')
		AND B.hc_tipo_ubicacion_diente_id<49
		AND (B.hc_tipo_problema_diente_id=2
		OR B.hc_tipo_problema_diente_id=4
		OR B.hc_tipo_problema_diente_id=5
		OR B.hc_tipo_problema_diente_id=8
		OR B.hc_tipo_problema_diente_id=12
		OR B.hc_tipo_problema_diente_id=31)
		ORDER BY B.hc_tipo_ubicacion_diente_id ASC;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i][0]=$resulta->fields[0];
			$var[$i][1]=$resulta->fields[1];
			$i++;
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarEnviarPintarOlearyTra()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT B.hc_tipo_ubicacion_diente_id,
		B.hc_tipo_cuadrante_diente_oleary_id
		FROM hc_indice_ipb_oleary_trata AS A,
		hc_indice_ipb_oleary_trata_detalle AS B
		WHERE A.evolucion_id=".$this->evolucion."
		AND A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		AND A.sw_activo='1'
		AND A.hc_indice_ipb_oleary_trata_id=B.hc_indice_ipb_oleary_trata_id
		ORDER BY B.hc_tipo_ubicacion_diente_id,
		B.hc_tipo_cuadrante_diente_oleary_id;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i][0]=$resulta->fields[0];
			$var[$i][1]=$resulta->fields[1];
			$i++;
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarEnviarPintarOlearyTraConsulta()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT B.hc_tipo_ubicacion_diente_id,
		B.hc_tipo_cuadrante_diente_oleary_id
		FROM hc_indice_ipb_oleary_trata_detalle AS B
		WHERE B.hc_indice_ipb_oleary_trata_id=
		(SELECT MAX(A.hc_indice_ipb_oleary_trata_id)
		FROM hc_indice_ipb_oleary_trata AS A
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."')
		ORDER BY B.hc_tipo_ubicacion_diente_id,
		B.hc_tipo_cuadrante_diente_oleary_id;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i][0]=$resulta->fields[0];
			$var[$i][1]=$resulta->fields[1];
			$i++;
			$resulta->MoveNext();
		}
		return $var;
	}

	function BuscarEnviarPintarNoBocaTra($var2)//falta la copia del de tratamiento
	{
		list($dbconn) = GetDBconn();
		$query="SELECT B.hc_tipo_ubicacion_diente_id,
		B.hc_tipo_problema_diente_id
		FROM hc_odontogramas_primera_vez AS A,
		hc_odontogramas_primera_vez_detalle AS B
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		AND A.sw_activo='1'
		AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
		AND B.hc_tipo_ubicacion_diente_id<49
		AND (B.hc_tipo_problema_diente_id=3
		OR B.hc_tipo_problema_diente_id=23)
		AND B.estado='0'
		ORDER BY B.hc_tipo_ubicacion_diente_id ASC;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var4[$i][0]=$resulta->fields[0];
			if($resulta->fields[1]==3)
			{
				$var4[$i][1]=8;
			}
			else
			{
				$var4[$i][1]=12;
			}
			$i++;
			$resulta->MoveNext();
		}
		$query="SELECT B.hc_tipo_ubicacion_diente_id,
		B.hc_tipo_problema_diente_id
		FROM hc_odontogramas_tratamientos AS A,
		hc_odontogramas_tratamientos_detalle AS B
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."'
		AND A.sw_activo='1'
		AND A.hc_odontograma_tratamiento_id=B.hc_odontograma_tratamiento_id
		AND B.hc_tipo_ubicacion_diente_id<49
		AND ((B.hc_tipo_problema_diente_id=3
		OR B.hc_tipo_problema_diente_id=23)
		AND B.estado='0')
		ORDER BY B.hc_tipo_ubicacion_diente_id ASC;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=$k=0;
		while(!$resulta->EOF)
		{
			if($var4[$k][0]<$resulta->fields[0]
			AND $var4[$k][0]<>NULL)
			{
				$var[$i][0]=$var4[$k][0];
				$var[$i][1]=$var4[$k][1];
				$k++;
			}
			else
			{
				$var[$i][0]=$resulta->fields[0];
				if($resulta->fields[1]==3)
				{
					$var[$i][1]=8;
				}
				else
				{
					$var[$i][1]=12;
				}
			}
			$i++;
			$resulta->MoveNext();
		}
		for(;$var4[$k][0]<>NULL;$k++)
		{
			$var[$i][0]=$var4[$k][0];
			$var[$i][1]=$var4[$k][1];
			$i++;
		}
		$k=$l=0;
		$ciclo1=sizeof($var);
		$ciclo2=sizeof($var2);
		if($ciclo1>=$ciclo2)
		{
			for($i=0;$i<$ciclo1;$i++)
			{
				if($var[$i][0]==$var2[$l][0] AND $var2[$l][0]<>NULL)
				{
					$var3[$k][0]=$var[$i][0];
					$var3[$k][1]=$var[$i][1];
					$l++;
				}
				else if($var2[$l][0]<$var[$i][0] AND $var2[$l][0]<>NULL)
				{
					$var3[$k][0]=$var2[$l][0];
					$var3[$k][1]=$var2[$l][1];
					$l++;
					$i--;
				}
				else if($var2[$l][0]>$var[$i][0] AND $var2[$l][0]<>NULL)
				{
					$var3[$k][0]=$var[$i][0];
					$var3[$k][1]=$var[$i][1];
				}
				else if($var2[$l][0]==NULL)
				{
					$var3[$k][0]=$var[$i][0];
					$var3[$k][1]=$var[$i][1];
				}
				$k++;
			}
		}
		else if($ciclo1<$ciclo2)
		{
			for($i=0;$i<$ciclo2;$i++)
			{
				if($var[$i][0]==$var2[$l][0] AND $var[$i][0]<>NULL)
				{
					$var3[$k][0]=$var[$i][0];
					$var3[$k][1]=$var[$i][1];
					$l++;
				}
				else if($var2[$l][0]<$var[$i][0] AND $var[$i][0]<>NULL)
				{
					$var3[$k][0]=$var2[$l][0];
					$var3[$k][1]=$var2[$l][1];
					$l++;
					$i--;
				}
				else if($var2[$l][0]>$var[$i][0] AND $var[$i][0]<>NULL)
				{
					$var3[$k][0]=$var[$i][0];
					$var3[$k][1]=$var[$i][1];
				}
				else if($var[$i][0]==NULL)
				{
					$var3[$k][0]=$var2[$l][0];
					$var3[$k][1]=$var2[$l][1];
					$l++;
				}
				$k++;
			}
		}
		return $var3;
	}

	function BuscarEnviarPintarNoBocaTraConsulta($var2)//falta la copia del de tratamiento
	{
		list($dbconn) = GetDBconn();
		$query="SELECT B.hc_tipo_ubicacion_diente_id,
		B.hc_tipo_problema_diente_id
		FROM hc_odontogramas_primera_vez_detalle AS B
		WHERE B.hc_odontograma_primera_vez_id=
		(SELECT MAX(A.hc_odontograma_primera_vez_id)
		FROM hc_odontogramas_primera_vez AS A
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."')
		AND B.hc_tipo_ubicacion_diente_id<49
		AND (B.hc_tipo_problema_diente_id=3
		OR B.hc_tipo_problema_diente_id=23)
		AND B.estado='0'
		ORDER BY B.hc_tipo_ubicacion_diente_id ASC;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var4[$i][0]=$resulta->fields[0];
			if($resulta->fields[1]==3)
			{
				$var4[$i][1]=8;
			}
			else
			{
				$var4[$i][1]=12;
			}
			$i++;
			$resulta->MoveNext();
		}
		$query="SELECT B.hc_tipo_ubicacion_diente_id,
		B.hc_tipo_problema_diente_id
		FROM hc_odontogramas_tratamientos_detalle AS B
		WHERE B.hc_odontograma_tratamiento_id=
		(SELECT MAX(A.hc_odontograma_tratamiento_id)
		FROM hc_odontogramas_tratamientos AS A
		WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
		AND A.paciente_id='".$this->paciente."')
		AND B.hc_tipo_ubicacion_diente_id<49
		AND ((B.hc_tipo_problema_diente_id=3
		OR B.hc_tipo_problema_diente_id=23)
		AND B.estado=0)
		ORDER BY B.hc_tipo_ubicacion_diente_id ASC;";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=$k=0;
		while(!$resulta->EOF)
		{
			if($var4[$k][0]<$resulta->fields[0]
			AND $var4[$k][0]<>NULL)
			{
				$var[$i][0]=$var4[$k][0];
				$var[$i][1]=$var4[$k][1];
				$k++;
			}
			else
			{
				$var[$i][0]=$resulta->fields[0];
				if($resulta->fields[1]==3)
				{
					$var[$i][1]=8;
				}
				else
				{
					$var[$i][1]=12;
				}
			}
			$i++;
			$resulta->MoveNext();
		}
		for(;$var4[$k][0]<>NULL;$k++)
		{
			$var[$i][0]=$var4[$k][0];
			$var[$i][1]=$var4[$k][1];
			$i++;
		}
		$k=$l=0;
		$ciclo1=sizeof($var);
		$ciclo2=sizeof($var2);
		if($ciclo1>=$ciclo2)
		{
			for($i=0;$i<$ciclo1;$i++)
			{
				if($var[$i][0]==$var2[$l][0] AND $var2[$l][0]<>NULL)
				{
					$var3[$k][0]=$var[$i][0];
					$var3[$k][1]=$var[$i][1];
					$l++;
				}
				else if($var2[$l][0]<$var[$i][0] AND $var2[$l][0]<>NULL)
				{
					$var3[$k][0]=$var2[$l][0];
					$var3[$k][1]=$var2[$l][1];
					$l++;
					$i--;
				}
				else if($var2[$l][0]>$var[$i][0] AND $var2[$l][0]<>NULL)
				{
					$var3[$k][0]=$var[$i][0];
					$var3[$k][1]=$var[$i][1];
				}
				else if($var2[$l][0]==NULL)
				{
					$var3[$k][0]=$var[$i][0];
					$var3[$k][1]=$var[$i][1];
				}
				$k++;
			}
		}
		else if($ciclo1<$ciclo2)
		{
			for($i=0;$i<$ciclo2;$i++)
			{
				if($var[$i][0]==$var2[$l][0] AND $var[$i][0]<>NULL)
				{
					$var3[$k][0]=$var[$i][0];
					$var3[$k][1]=$var[$i][1];
					$l++;
				}
				else if($var2[$l][0]<$var[$i][0] AND $var[$i][0]<>NULL)
				{
					$var3[$k][0]=$var2[$l][0];
					$var3[$k][1]=$var2[$l][1];
					$l++;
					$i--;
				}
				else if($var2[$l][0]>$var[$i][0] AND $var[$i][0]<>NULL)
				{
					$var3[$k][0]=$var[$i][0];
					$var3[$k][1]=$var[$i][1];
				}
				else if($var[$i][0]==NULL)
				{
					$var3[$k][0]=$var2[$l][0];
					$var3[$k][1]=$var2[$l][1];
					$l++;
				}
				$k++;
			}
		}
		return $var3;
	}

}
?>
