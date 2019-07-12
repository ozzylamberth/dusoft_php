<?php

/**
* Submodulo de Ingreso de Gestacion.
* NUEVA VERSION
* Submodulo para manejar evoluciones de gestantes.
* @author Tizziano Perea <t_perea@yahoo.es>
* @version 1.0
* @package SIIS
* $Id: hc_IngresoGestacion.php,v 1.2 2005/03/09 13:24:20 tizziano Exp $
*/


/**
* IngresoGestacion
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de Ingreso de Gestacion.
*/

class IngresoGestacion extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function IngresoGestacion()
	{
       	return true;
	}


/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'01/27/2005',
		'autor'=>'TIZZIANO PEREA OCORO',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}


/**
* Esta función retorna los datos de la impresión de la consulta del submodulo.
*
* @access private
* @return text Datos HTML de la pantalla.
*/
	function GetConsulta()
	{
        $this->frmConsulta();
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
		else
		{
			if ($_REQUEST['accion'.$pfj]== 'insertar')
			{
				if($this->InsertDatos()==true)
				{
					$this->frmForma();
				}
				else
				{
					$this->frmForma();
				}
			}
			if ($_REQUEST['accion'.$pfj]== 'Desactivar_Gestacion')
			{
				if($this->DesactivarDatos()==true)
				{
					$this->frmForma();
				}
				else
				{
					$this->frmForma();
				}
			}
		}
		return $this->salida;
	}

/**
* Esta función retorna datos correspondientes a un control gestante.
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acción a realizar.
*/

	function DesactivarDatos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql="UPDATE gestacion SET estado='0' WHERE evolucion_id = ".$this->evolucion."
			 AND gestacion_id='".$_REQUEST['gestar'.$pfj]."';";
		$resulta=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al insertar en gestacion";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError["MensajeError"]="ETAPA DE GESTACION DESACTIVADA";
			return false;
		}
		return true;
	}

/**
* Esta función Realiza una busqueda de las pacientes con etapas de gestacion activas.
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acción a realizar.
*/

	function SeleccionGestantes()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql="SELECT gestacion_fecha_inicio,gestacion_fecha_fin,
				gestacion_num_embarazo,tipo_id_paciente,paciente_id,fum,fup,estado,
				gestacion_id FROM gestacion WHERE tipo_id_paciente='".$this->tipoidpaciente."' AND
				paciente_id='".$this->paciente."' AND estado='1';";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$i=0;
			while (!$result->EOF)
			{
				$datos[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
		return $datos;
	}

	function SexodePaciente()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql="SELECT sexo_id FROM pacientes
		      WHERE tipo_id_paciente='".$this->tipoidpaciente."'
			  AND paciente_id='".$this->paciente."';";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$i=0;
			while (!$result->EOF)
			{
				$sexpaciente[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
		return $sexpaciente;
	}

/**
* Esta función Retorna los Valores del resumen de las gestaciones.
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acción a realizar.
*/

  function Resumen_Gestaciones()
	{
    	$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql="SELECT gestacion_fecha_inicio,gestacion_fecha_fin,
				gestacion_num_embarazo,fum,fup,gestacion_id FROM gestacion
				WHERE tipo_id_paciente='".$this->tipoidpaciente."' AND
				paciente_id='".$this->paciente."';";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$i=0;
			while (!$result->EOF)
			{
				$datos[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
		return $datos;
	}

/**
* Esta función Inserta Valores correspondientes de a una gestacion.
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acción a realizar.
*/

	function InsertDatos()
	{
		$pfj=$this->frmPrefijo;

		$ifecha= $_REQUEST['ietapa'.$pfj];
		$ffecha= $_REQUEST['fetapa'.$pfj];
		$fumfecha= $_REQUEST['fmenstruacion'.$pfj];
		$fupfecha= $_REQUEST['fparto'.$pfj];

		$cad=explode ('/',$ifecha);
		$dia = $cad[0];
		$mes = $cad[1];
		$ano = $cad[2];
		$ifecha=$cad[2].'-'.$cad[1].'-'.$cad[0];

		$cad=explode ('/',$ffecha);
		$dia = $cad[0];
		$mes = $cad[1];
		$ano = $cad[2];
		$ffecha=$cad[2].'-'.$cad[1].'-'.$cad[0];

		$cad=explode ('/',$fumfecha);
		$dia = $cad[0];
		$mes = $cad[1];
		$ano = $cad[2];
		$fumfecha=$cad[2].'-'.$cad[1].'-'.$cad[0];

		$cad=explode ('/',$fupfecha);
		$dia = $cad[0];
		$mes = $cad[1];
		$ano = $cad[2];
		$fupfecha=$cad[2].'-'.$cad[1].'-'.$cad[0];


		if ( ($ifecha =='') || ($ffecha=='') || ($fumfecha=='') || (strtotime($ifecha)>=strtotime(date("y-m-d"))) || (strtotime($ffecha)<=strtotime($ifecha)) || (strtotime($fumfecha)>=strtotime($ifecha)) )//|| $fupfecha == '--')//(strtotime($fupfecha)>=strtotime($ifecha)) )
		{
			if ( ($ifecha =='') || (strtotime($ifecha)>=strtotime(date("y-m-d"))) )
			{
			    $this->frmError["inicio"]=1;
			}

			if ( ($ffecha=='') || (strtotime($ffecha)<=strtotime($ifecha)) )
			{
				$this->frmError["fin"]=1;
			}

			if ($fumfecha=='' ||  strtotime($fumfecha)>=strtotime($ifecha))
			{
				$this->frmError["fum"]=1;
			}

			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS ó LA FECHA NO ESTA EN UN RANGO VALIDO.";
		    return false;
		}

		if ($fupfecha == '--')
		{
			$fupfecha = 'NULL';
		}
		else
		{
			if (strtotime($fupfecha)>=strtotime($fumfecha))
			{
				$this->frmError["fup"]=1;
			}
			$fupfecha = "'$fupfecha'";
		}


		list($dbconn) = GetDBconn();
		$sql="INSERT INTO gestacion (gestacion_fecha_inicio,gestacion_fecha_fin,
				gestacion_num_embarazo,tipo_id_paciente,paciente_id,fum,fup, evolucion_id, usuario_id)
				VALUES ('".$ifecha."','".$ffecha."','".$_REQUEST['nembarazo'.$pfj]."',
				'".$this->tipoidpaciente."','".$this->paciente."','".$fumfecha."',".$fupfecha.",'".$this->evolucion."', ".UserGetUID().");";
		$resulta=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$this->frmError['MensajeError']="Datos Guardados Satisfactoriamente.";
			return true;
		}
	}
}
?>
