<?php

/**
* Submodulo de Protocolos Medicos.
*
* Submodulo para manejar los diferentes pasos que se debe seguir con un paciente según unas caracteristicas del
* paciente y demas datos.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_Vacunacion.php,v 1.2 2005/04/14 20:48:38 tizziano Exp $
*/


/**
* ProtocolosMedicos
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de protocolos medicos.
*/

class Vacunacion extends hc_classModules
{

/**
* Contiene la identificación del paciente que se esta utilizando en el submodulo.
*
* @var text
* @access public
*/
	var $paciente='';

/**
* Contiene la identificación del paciente que se esta utilizando en el submodulo.
*
* @var text
* @access public
*/
	var $tipoidpaciente='';


/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function Vacunacion()
	{
/*		$this->paciente='23';
		$this->tipoidpaciente='CC';*/
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
		'fecha'=>'',
		'autor'=>'JAIME ANDRES VALENCIA',
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
* Esta función retorna la presentación del submodulo (consulta o inserción).
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acción a realizar.
*/
	function GetForma()
	{
		$pfj=$this->frmPrefijo;
		$a=explode(',',$_REQUEST['accion'.$pfj]);
		if($a[0]=="consulta")
		{
			$this->frmConsulta();
			return $this->salida;
		}
		if($a[0]=="insertar")
		{
			if($this->InsertDatos($a[1]))
			{
				$this->frmForma($a[1]);
			}
		}
		else
		{
			$this->frmForma($a[0]);
		}
		  return $this->salida;
	}

/**
* Esta función inserta los datos del submodulo.
*
* @access private
* @return boolean Informa si lo logro o no.
*/
	function InsertDatos($oiga)
	{
		$pfj=$this->frmPrefijo;
		error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
		list($dbconn) = GetDBconn();
		if($oiga=='otros')
		{
			$sql="select b.dosis_id,c.vacuna_id from ((select b.dosis_id from dosis as b)except(select b.dosis_id from vacunas as a, dosis as b where a.vacuna_id=b.vacuna_id and b.ley=1 and b.edad is not null and b.tipo_edad is not null)) as a, dosis as b left join hc_vacunas_cumplidas as d on (b.dosis_id=d.dosis_id), vacunas as c where a.dosis_id=b.dosis_id and b.vacuna_id=c.vacuna_id order by b.vacuna_id,b.dosis_id;";
		}
		else
		{
			$sql="select b.dosis_id,a.vacuna_id from vacunas as a, dosis as b left join hc_vacunas_cumplidas as c on (b.dosis_id=c.dosis_id) where a.vacuna_id=b.vacuna_id and b.ley=1 and b.edad is not null and b.tipo_edad is not null order by b.vacuna_id,b.dosis_id;";
		}
		$result=$dbconn->Execute($sql);
		$s=0;
		while (!$result->EOF)
		{
			$dosis[0][$s]=$result->fields[0];
			$dosis[1][$s]=$result->fields[1];
			$result->MoveNext();
			$s++;
		}
		$i=0;
		while($i<sizeof($dosis[0]))
		{
			$dos="dosis";
			$dos.=$dosis[0][$i].$pfj;
			$sql="";
			if(!empty($_REQUEST[$dos]))
			{
				if($_REQUEST[$dos]==1)
				{
					$sql="insert into hc_vacunas_cumplidas(evolucion_id,dosis_id,fecha,paciente_id,tipo_id_paciente,vacuna_id) values (".$this->evolucion.", ".$dosis[0][$i].", '".date("Y-m-d")."', '".$this->paciente."', '".$this->tipoidpaciente."', ".$dosis[1][$i].");";
				}
				else
				{
					$fecha="fecha";
					$fecha.=$dosis[0][$i].$pfj;
					$lugar="lugar";
					$lugar.=$dosis[0][$i].$pfj;
					if(empty($_REQUEST[$fecha]))
					{
						if(empty($_REQUEST[$lugar]))
						{
							$sql="insert into hc_vacunas_cumplidas(dosis_id, paciente_id, tipo_id_paciente, vacuna_id)values(".$dosis[0][$i].",'".$this->paciente."','".$this->tipoidpaciente."',".$dosis[1][$i].");";
						}
						else
						{
							$sql="insert into hc_vacunas_cumplidas(dosis_id, paciente_id, tipo_id_paciente, vacuna_id,lugar)values(".$dosis[0][$i].",'".$this->paciente."','".$this->tipoidpaciente."',".$dosis[1][$i].",'".$_REQUEST[$lugar]."');";
						}
					}
					else
					{
						if(empty($_REQUEST[$lugar]))
						{
							$sql="insert into hc_vacunas_cumplidas(dosis_id, fecha, paciente_id, tipo_id_paciente, vacuna_id)values(".$dosis[0][$i].",'".$_REQUEST[$fecha]."','".$this->paciente."','".$this->tipoidpaciente."',".$dosis[1][$i].");";
						}
						else
						{
							$sql="insert into hc_vacunas_cumplidas(dosis_id, fecha, paciente_id, tipo_id_paciente, vacuna_id,lugar)values(".$dosis[0][$i].",'".$_REQUEST[$fecha]."','".$this->paciente."','".$this->tipoidpaciente."',".$dosis[1][$i].",'".$_REQUEST[$lugar]."');";
						}
					}
				}
			}
			if(!empty($sql))
			{
				$dbconn->BeginTrans();
				if(!$dbconn->Execute($sql))
				{
					$error=$dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					echo "$error";
					return false;
				}
				$dbconn->CommitTrans();
			}
			$i++;
		}
		return true;
	}


}
?>
