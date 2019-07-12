<?php

/**
* Submodulo de Revisiï¿½ por Sistemas.
*
* Submodulo para manejar el examen por sistemas que debe realizarse a un paciente en una evoluciï¿½.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_RevisionxSistemas.php,v 1.7 2006/12/19 21:00:15 jgomez Exp $
*/


/**
* RevisionxSistemas
*
* Clase para accesar los metodos privados de la clase de presentaciï¿½, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserciï¿½ y la consulta del
* submodulo de revisiï¿½ por sistemas.
*/

class RevisionxSistemas extends hc_classModules
{

/**
* Esta funciï¿½ Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function RevisionxSistemas()
	{
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
// 		'fecha'=>'01/27/2005',
// 		'autor'=>'JAIME ANDRES VALENCIA',
// 		'descripcion_cambio' => '',
// 		'requiere_sql' => false,
// 		'requerimientos_adicionales' => '',
// 		'version_kernel' => '1.0'
// 		);
// 		return $informacion;
// 	}


/**
* Esta funciï¿½ retorna los datos de la impresiï¿½ de la consulta del submodulo.
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
* Esta funciï¿½ retorna la presentaciï¿½ del submodulo (consulta o inserciï¿½).
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acciï¿½ a realizar.
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
* Esta funciï¿½ inserta los datos del submodulo.
*
* @access private
* @return boolean Informa si lo logro o no.
*/
	function InsertDatos()
	{
		$pfj=$this->frmPrefijo;
		$sql1="SELECT tipo_sistema_id from hc_tipos_sistemas";
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$rs=$dbconn->Execute($sql1);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$rs->EOF)
			{
				$dato[$rs->fields[0]]=1;
				$rs->MoveNext();
			}
		}
		$sql1="";
		$sql="";
		foreach($dato as $k=>$v)
		{
			$observ="observ";
			$observ.=$k.$pfj;
			$r=$_REQUEST[$observ];

			if((!empty($_REQUEST[$observ])))
			{
				$sql="select * from hc_revision_sistemas where tipo_sistema_id=$k and evolucion_id=".$this->evolucion.";";
				list($dbconn) = GetDBconn();
				$rs=$dbconn->Execute($sql);
				if($rs->RecordCount()==0)
				{
					$sql="insert into hc_revision_sistemas (observacion,evolucion_id,tipo_sistema_id) values ('$_REQUEST[$observ]','$this->evolucion','$k');";
						// Reportar errores para depuracion.
					error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
					list($dbconn) = GetDBconn();
					if(!$dbconn->Execute($sql))
					{
						$dbconn->RollbackTrans();
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
				}
				else
				{
					$sql="update hc_revision_sistemas set observacion ='$_REQUEST[$observ]' where tipo_sistema_id='$k' and evolucion_id='$this->evolucion';";
					error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
					list($dbconn) = GetDBconn();
					if(!$dbconn->Execute($sql))
					{
						$dbconn->RollbackTrans();
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
				}
			}
		}
		$dbconn->CommitTrans();
		 $this->RegistrarSubmodulo($this->GetVersion());            
    return true;
	}

//ok
function DatosRevisionSistemas()
{
	list($dbconn) = GetDBconn();
	$query ="SELECT hc_tipos_sistemas.tipo_sistema_id, nombre, observacion, evolucion_id, 
     			 hc_tipos_sistemas.sw_defecto
		    FROM hc_tipos_sistemas LEFT JOIN (SELECT * FROM hc_revision_sistemas WHERE evolucion_id=".$this->evolucion.") AS a
		    ON (hc_tipos_sistemas.tipo_sistema_id=a.tipo_sistema_id)
              WHERE (hc_tipos_sistemas.sw_sub_asignacion = '2' OR hc_tipos_sistemas.sw_sub_asignacion = '0')
              ORDER BY hc_tipos_sistemas.indice_orden;";
	$result = $dbconn->Execute($query);
	$i=0;
	if ($dbconn->ErrorNo() != 0)
	{
		return false;
	}
	else
	{
		while (!$result->EOF)
		{
			$datos[0][$i]=$result->fields[0];
			$datos[1][$i]=$result->fields[1];
			$datos[2][$i]=$result->fields[2];
			$datos[3][$i]=$result->fields[3];
			$datos[4][$i]=$result->fields[4];
			$result->MoveNext();
			$i++;
		}
	}
	return $datos;
}

/*PDTE*/
	function DatosConsultaRevision()
	{
		list($dbconn) = GetDBconn();
          /********OJO********/
          //Query(1) = Original, Query = Utilizado
          /********OJO********/
		$query1 = "SELECT nombre, observacion 
          		FROM hc_revision_sistemas, hc_tipos_sistemas 
                    WHERE hc_revision_sistemas.tipo_sistema_id=hc_tipos_sistemas.tipo_sistema_id 
                    AND evolucion_id=".$this->evolucion."
                    AND (hc_tipos_sistemas.sw_sub_asignacion = '2' OR hc_tipos_sistemas.sw_sub_asignacion = '0')
                    ORDER BY hc_tipos_sistemas.indice_orden;";
		
          $query = "SELECT nombre, observacion 
          		FROM hc_revision_sistemas, hc_tipos_sistemas 
                    WHERE hc_revision_sistemas.tipo_sistema_id=hc_tipos_sistemas.tipo_sistema_id 
                    AND evolucion_id=".$this->evolucion."
                    ORDER BY hc_tipos_sistemas.indice_orden;";

		$result = $dbconn->Execute($query);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$dato[0][$i]=$result->fields[0];
				$dato[1][$i]=$result->fields[1];
				$result->MoveNext();
				$i++;
			}
		}
		return $dato;
	}


}

?>
