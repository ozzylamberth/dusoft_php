<?php

/**
 * $Id: hc_AccionPreventiva.php,v 1.12 2006/12/19 21:00:12 jgomez Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Submodulo para controlar las acciones preventivas de odontologia a realizar en el paciente
 */

/**
* Accion Preventiva
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de accion preventiva.
*/

class AccionPreventiva extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function AccionPreventiva()
	{
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
			$this->frmForma();
		}
		elseif($_REQUEST['accion'.$pfj]=='insertar')
		{
			if($this->InsertDatos()==true)
			{
				$this->frmForma();
			}
		}
		return $this->salida;
	}

	function BuscarAccionPreventiva()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT A.tipo_accion_id,
		A.nombre,
		B.hc_accion_preventiva_id,
		B.sw_accion_preventiva,
		B.descripcion,
          B.fecha_registro
		FROM hc_tipos_accion_preventiva AS A
		LEFT JOIN hc_accion_preventiva AS B ON
		(A.tipo_accion_id=B.tipo_accion_id
		AND B.evolucion_id=".$this->evolucion.")
		ORDER BY A.tipo_accion_id;";
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

	function InsertDatos()
	{
		$pfj=$this->frmPrefijo;
		$ciclo=sizeof($_REQUEST['datos'.$pfj]);
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		for($i=0;$i<$ciclo;$i++)
		{
			if($_REQUEST['datos'.$pfj][$i]['hc_accion_preventiva_id']<>NULL)
			{
				$query="UPDATE hc_accion_preventiva SET
				sw_accion_preventiva='".$_REQUEST['accionprev'.$pfj.$i]."',
				descripcion='".$_REQUEST['observacio'.$pfj.$i]."',
                    fecha_registro = now()
				WHERE evolucion_id=".$this->evolucion."
				AND hc_accion_preventiva_id=".$_REQUEST['datos'.$pfj][$i]['hc_accion_preventiva_id'].";";
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			else if($_REQUEST['datos'.$pfj][$i]['hc_accion_preventiva_id']==NULL)
			{
				$query="INSERT INTO hc_accion_preventiva
				(evolucion_id,
				tipo_accion_id,
				sw_accion_preventiva,
				descripcion,
				tipo_id_paciente,
				paciente_id,
                    fecha_registro)
				VALUES
				(".$this->evolucion.",
				".$_REQUEST['datos'.$pfj][$i]['tipo_accion_id'].",
				'".$_REQUEST['accionprev'.$pfj.$i]."',
				'".$_REQUEST['observacio'.$pfj.$i]."',
				'".$this->tipoidpaciente."',
				'".$this->paciente."',
                    now());";
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
		}
		$dbconn->CommitTrans();
    $this->RegistrarSubmodulo($this->GetVersion());
		$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
		return true;
	}

	function BuscarAccionPreventiva2()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT A.tipo_accion_id,
		A.nombre,
		B.hc_accion_preventiva_id,
		B.sw_accion_preventiva,
		B.descripcion,
          B.fecha_registro
		FROM hc_tipos_accion_preventiva AS A,
		hc_accion_preventiva AS B
		WHERE A.tipo_accion_id=B.tipo_accion_id
		AND B.evolucion_id=".$this->evolucion."
		ORDER BY A.tipo_accion_id;";
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


	function BuscarAccionPreventivaAnterior()
	{
		list($dbconn) = GetDBconn();
		$query="SELECT A.tipo_accion_id,A.nombre, B.hc_accion_preventiva_id,
                         B.sw_accion_preventiva, B.descripcion, B.evolucion_id, B.fecha_registro
                    FROM hc_tipos_accion_preventiva AS A,
                         hc_accion_preventiva AS B
                    WHERE B.tipo_id_paciente='".$this->tipoidpaciente."'
                         AND B.paciente_id='".$this->paciente."'
                         AND A.tipo_accion_id=B.tipo_accion_id
                         AND B.evolucion_id != ".$this->evolucion."
                    ORDER BY A.tipo_accion_id;";
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

}
?>
