<?php

/**
* Submodulo de Cronicos.
*
* Submodulo para manejar los problemas cronicos de un paciente.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_Cronicos.php,v 1.5 2005/05/12 20:19:48 tizziano Exp $
*/


/**
* Cronicos
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de cronicos.
*/

class Cronicos extends hc_classModules
{


/**
* Contiene el la identificación de los cronicos de un paciente.
*
* @var int
* @access private
*/
	var $idcronico='';

/**
* Contiene la identificación del tipo de cronico de un paciente.
*
* @var int
* @access private
*/
	var $idtipocronico='';

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function Cronicos()
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
		else
		{
			if($this->InsertDatos()==true)
			{
				$this->frmForma();
			}
		}
		return $this->salida;
	}

/**
* Esta función inserta los datos del submodulo.
*
* @access private
* @return boolean Informa si lo logro o no.
*/
	function InsertDatos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
          $Cronicos = $_REQUEST['Vec_Cronico'.$pfj];
          foreach ($Cronicos as $k => $v)
          {
              $j = $v;
              $query = "select cronico_id
                        from cronicos
                        where paciente_id='".$this->paciente."'
                        and tipo_id_paciente='".$this->tipoidpaciente."'
                        and tipo_cronico_id='$v';";
              $result = $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0)
              {
                    return false;
              }
              else
              {
                    list($cronico) = $result->FetchRow();
                    if(empty($cronico) AND $_REQUEST['sino'.$j.$pfj] == 1)
                    {
                        $sino='sino'.$j.$pfj;
                        $sql="insert into cronicos (paciente_id,tipo_id_paciente,sino,tipo_cronico_id) values('".$this->paciente."','".$this->tipoidpaciente."','".$_REQUEST[$sino]."',$v);";
                        list($dbconn) = GetDBconn();
                        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
                        $result = $dbconn->Execute($sql);
                        if($dbconn->ErrorNo() != 0)
                        {
                              $this->error = "Error al Cargar el Modulo";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              return false;
                        }
                    }
                    if (!empty($cronico))
                    {
                        $this->idcronico=$cronico;
                        $this->idtipocronico=$v;
                        if(!$this->UpdateDatos())
                        {
                              echo $error;
                              return false;
                        }
                    }
              }
              $i++;
          }
          return true;
       }	

/**
* Esta función actualiza los datos del submodulo.
*
* @access private
* @return boolean Informa si lo logro o no.
*/
	function UpdateDatos()
	{
		$pfj=$this->frmPrefijo;
		$sino='sino'.$this->idtipocronico.$pfj;
		$sql="update cronicos set sino='".$_REQUEST[$sino]."' where cronico_id=".$this->idcronico.";";
		list($dbconn) = GetDBconn();
		error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
		if(!$dbconn->Execute($sql))
		{
			$error=$dbconn->ErrorMsg();
			echo "$error";
			return false;
		}
		else
		{
			return true;
		}
	}


	function BusquedaAntecedentes1()
	{
		list($dbconn) = GetDBconn();
		$query = "select tipo_cronicos.tipo_cronico_id,nombre,sino from tipo_cronicos left join cronicos on (tipo_cronicos.tipo_cronico_id=cronicos.tipo_cronico_id) where tipo_id_paciente='".$this->tipoidpaciente."' and paciente_id='".$this->paciente."' order by tipo_cronicos.tipo_cronico_id;";
		$result = $dbconn->Execute($query);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
    	}
		else
		{
			while (!$result->EOF)
			{
				$cron[0][$i]=$result->fields[0];
				$cron[1][$i]=$result->fields[1];
				$cron[2][$i]=$result->fields[2];
				$result->MoveNext();
				$i++;
			}
		}
		return $cron;
	}

	function BusquedaAntecedentes()
	{
		list($dbconn) = GetDBconn();
		$query = "select tipo_cronicos.tipo_cronico_id,nombre,sino from tipo_cronicos left join cronicos on (tipo_cronicos.tipo_cronico_id=cronicos.tipo_cronico_id) where sino='1' and tipo_id_paciente='".$this->tipoidpaciente."' and paciente_id='".$this->paciente."' order by tipo_cronicos.tipo_cronico_id;";
		$result = $dbconn->Execute($query);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
    	}
		else
		{
			while (!$result->EOF)
			{
				$cron[0][$i]=$result->fields[0];
				$cron[1][$i]=$result->fields[1];
				$cron[2][$i]=$result->fields[2];
				$result->MoveNext();
				$i++;
			}
		}
		return $cron;
	}

	function BusquedaTipoAntecedentes($cron)
	{
		list($dbconn) = GetDBconn();
		$query = "select tipo_cronico_id,nombre from tipo_cronicos except select tipo_cronico_id,nombre from (select tipo_cronicos.tipo_cronico_id,nombre from tipo_cronicos left join cronicos on (tipo_cronicos.tipo_cronico_id=cronicos.tipo_cronico_id) where paciente_id='".$this->paciente."' and tipo_id_paciente='".$this->tipoidpaciente."') as hola ORDER BY tipo_cronico_id DESC;";
	    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
    	}
		else
		{
			$i=sizeof($cron[0]);
			while (!$result->EOF)
			{
				$cron[0][$i]=$result->fields[0];
				$cron[1][$i]=$result->fields[1];
				$result->MoveNext();
				$i++;
			}
		}
		return $cron;
	}

}
?>
