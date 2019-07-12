<?php

/**
* Submodulo Consulta del Triage.
*
* Submodulo para manejar la impresion de reportes del Triage.
* @author Tizziano Perea O. <tperea@ipsoft-sa.com>
* @version 1.0
* @package SIIS
* $Id: hc_Consulta_Triage.php,v 1.5 2005/07/07 19:26:36 tizziano Exp $
*/


/**
* Consulta_Triage
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de Consulta Triage.
*/

class Consulta_Triage extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/


	function Consulta_Triage()
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
* Esta función retorna la presentación del submodulo (consulta o inserción).
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acción a realizar.
*/
	function GetForma()
	{
		if($this->tipo_profesional == 1 OR $this->tipo_profesional == 2)
		{
			$pfj=$this->frmPrefijo;
			if(empty($_REQUEST['accion'.$pfj]))
			{
				$this->frmForma();
			}
			else
			{
				if($_REQUEST['accion'.$pfj]=='Listar_ControlesNeurologicos')
				{
					if($this->Listar_ControlesNeurologicos()==true)
					{
						$this->frmForma();
					}
					else
					{
						$this->frmForma();
					}
				}
			}
		}
		else
		{
			$this->GetConsulta();
		}
		return $this->salida;
	}



	function ReconocerProfesional()
	{
		list($dbconn) = GetDBconn();
		$a=UserGetUID();
		if(!empty($a))
		{
			$sql="SELECT b.tipo_profesional
						FROM profesionales_usuarios as a,
						profesionales as b
						WHERE a.usuario_id=".$a."
						and a.tipo_tercero_id=b.tipo_id_tercero and a.tercero_id=b.tercero_id;";
		}
		else
		{
			return false;
		}
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer profesional";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if(!$result->EOF)
			{
				return $result->fields[0];
			}
			else
			{
				return false;
			}
		}
	}

	/**
	*		Pacientes_Remitidos
	*
	*		Obtiene el listado de pacientes remitidos por Triage
	*
	*		@Author Tizziano Perea O.
	*		@access Public
	*		@return bool-array-string
	*		@param array,
	*		@param array
	*/

	function Pacientes_Remitidos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query ="SELECT A.centro_remision,
						A.numero_remision,
						A.diagnostico_id,
						A.fecha_remision,
						A.hora_remision,
						A.observacion,
						B.centro_remision,
						B.descripcion,
						C.diagnostico_id,
						C.diagnostico_nombre
						FROM pacientes_remitidos AS A,
							 centros_remision AS B,
							 diagnosticos AS C
						WHERE ingreso=".$this->ingreso."
							  AND A.diagnostico_id=C.diagnostico_id
							  AND A.centro_remision=B.centro_remision";
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
			$Pacientes_Remitidos[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $Pacientes_Remitidos;
	}// Fin Pacientes_Remitidos


	/**
	*		Datos_Triage
	*
	*		Obtiene los datos de pacientes remitidos por Triage
	*
	*		@Author Tizziano Perea O.
	*		@access Public
	*		@return bool-array-string
	*		@param array,
	*		@param array
	*/

	function Datos_Triage()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query ="SELECT A.*, B.descripcion, C.diagnostico_id, D.diagnostico_nombre
				FROM triages AS A LEFT JOIN triages_diagnosticos AS C ON(C.triage_id = A.triage_id)
				LEFT JOIN diagnosticos AS D ON(D.diagnostico_id = C.diagnostico_id),
				departamentos AS B
				WHERE ingreso=".$this->ingreso."
				AND A.departamento = B.departamento;";

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
			$Datos_Triage[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $Datos_Triage;
	}// Fin Datos_Triage



		/*
		*/
		function BuscarCausas($Triage_id)
		{	$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();
			$query = "SELECT a.*, b.descripcion
					  FROM chequeo_triages as a, causas_probables as b
					  WHERE a.triage_id= $Triage_id
					  AND a.causa_probable_id=b.causa_probable_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while (!$result->EOF)
			{
				$causas[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}

			$result->Close();
			return $causas;
		}


		/*
		*/
		function BuscarSignosVitales($Triage_id)
		{
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM signos_vitales_triages
					  WHERE triage_id = $Triage_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			$sig =$result->GetRowAssoc($ToUpper = false);
			$result->Close();
			return $sig;
		}
}
?>
