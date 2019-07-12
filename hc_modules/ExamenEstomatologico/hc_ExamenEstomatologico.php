<?php

/**
* Submodulo de ExamenEstomatologico.
*
* Submodulo para manejar el examen por sistemas que debe realizarse a un paciente en una evolucion.
* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_ExamenEstomatologico.php,v 1.8 2006/12/19 21:00:13 jgomez Exp $
*/


/**
* ExamenEstomatologico
*
* Clase para accesar los metodos privados de la clase de presentacion, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de insercin y la consulta del
* submodulo de revisin por sistemas.
*/

class ExamenEstomatologico extends hc_classModules
{

/**
* Esta funcion Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function ExamenEstomatologico()
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
// 		'autor'=>'TIZZIANO PEREA OCORO',
// 		'descripcion_cambio' => '',
// 		'requiere_sql' => false,
// 		'requerimientos_adicionales' => '',
// 		'version_kernel' => '1.0'
// 		);
// 		return $informacion;
// 	}


/**
* Esta funcin retorna los datos de la impresión de la consulta del submodulo.
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
		$pfj=$this->frmPrefijo;
       	list($dbconn) = GetDBconn();
		$query="SELECT count(*)
			FROM hc_sistemas_estomatologicos
			WHERE evolucion_id=".$this->evolucion.";";
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
			$estado=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}

		if ($estado[count] == 0)
		{
			return false;
		}
		else
		{
		 	return true;
		}
	}


/**
* Esta funcin retorna la presentación del submodulo (consulta o inserción).
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la accin a realizar.
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
               
               if($_REQUEST['accion'.$pfj]=='primera_vez')
			{
				$this->frm_ExamenEstomatologico_PrimeraVez();
			}

		}
		return $this->salida;
	}

/**
* Esta funcion obtiene los patrones maestros
*
* @access private
* @return boolean Informa si lo logro o no.
*/

	function GetEstomatologico_Maestro()
	{
		$pfj = $this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql = "SELECT *
				FROM hc_examen_estomatologico_maestro;";

		$resultado = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while($data = $resultado->GetAssoc())
			{
				$maestro[] = $data;
			}
			return $maestro;
		}
	}


/**
* Esta funcion obtiene los patrones maestros
*
* @access private
* @return boolean Informa si lo logro o no.
*/

	function GetEstomatologico_MaestroForaneo()
	{
		$pfj = $this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql = "SELECT C.*
				FROM hc_sistemas_estomatologicos AS A
				LEFT JOIN hc_tipos_sistemas_estomatologicos AS B ON
					   (A.tipo_sistema_id = B.tipo_sistema_id)
				LEFT JOIN hc_examen_estomatologico_maestro AS C ON
					   (B.estomatologico_maestro_id = C.estomatologico_maestro_id)
				WHERE A.evolucion_id =".$this->evolucion."
				AND A.ingreso =".$this->ingreso.";";

		$resultado = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while($data = $resultado->GetAssoc())
			{
				$maestrico[] = $data;
			}
			return $maestrico;
		}
	}



/**
* Esta funcion obtiene los patrones maestros
*
* @access private
* @return boolean Informa si lo logro o no.
*/

	function GetTipos_Sistemas()
	{
		$pfj = $this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql = "SELECT estomatologico_maestro_id, nombre, tipo_sistema_id, sw_mostrar_normal_si, sw_defecto
				FROM hc_tipos_sistemas_estomatologicos ORDER BY tipo_sistema_id;";

		$resultado = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while(!$resultado->EOF)
			{
				$datos[] = $resultado->GetRowAssoc($ToUpper = false);
				$resultado->MoveNext();
			}
			return $datos;
		}
	}


/**
* Esta funcin inserta los datos del submodulo.
*
* @access private
* @return boolean Informa si lo logro o no.
*/
	function InsertDatos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql1="SELECT tipo_sistema_id from hc_tipos_sistemas_estomatologicos";
		$dbconn->BeginTrans();
		$rs=$dbconn->Execute($sql1);
		if($dbconn->ErrorNo() != 0)
		{
			$dbconn->RollbackTrans();
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$rs->EOF)
			{
				$dato[] = $rs->GetRowAssoc($ToUpper = false);
				$rs->MoveNext();
			}
		}

		$sql1="";
		$sql="";

		foreach($dato as $k=>$v)
		{
			foreach ($v as $k2=>$vect)
			{
				$anormal= $_REQUEST['anormal'.$vect.$pfj];
				$observacion= $_REQUEST['observacion'.$vect.$pfj];
				if($anormal != '')
				{
					if( (!empty($observacion)) || (empty($observacion) && $anormal == '1')  || (empty($observacion) && $anormal == '0'))
					{
						$sql="SELECT * FROM hc_sistemas_estomatologicos WHERE tipo_sistema_id =$vect
																		AND evolucion_id =".$this->evolucion."
																		AND ingreso =".$this->ingreso.";";
						$resulta =$dbconn->Execute($sql);
						if($resulta->RecordCount()==0)
						{
							if (!empty($observacion))
							{
								$observacion = "'$observacion'";
							}
							else
							{
								$observacion = 'NULL';
							}

							$query="INSERT INTO hc_sistemas_estomatologicos (normal,
                                                                                    tipo_sistema_id,
                                                                                    evolucion_id,
                                                                                    ingreso,
                                                                                    observacion,
                                                                                    fecha_registro)
                                                                           VALUES ('$anormal',
                                                                                    '$vect',
                                                                                    ".$this->evolucion.",
                                                                                    ".$this->ingreso.",
                                                                                    $observacion,
                                                                                    now());";
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$dbconn->RollbackTrans();
								$this->error = "Error al ejecutar la conexion";
								$this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								return false;
							}
							else
							{
								$this->frmError['MensajeError']="DATOS GUARDADOS SATISFACTORIAMENTE";
							}
						}
						else
						{
							if (!empty($observacion))
							{
								$observacion = "'$observacion'";
							}
							else
							{
								$observacion = 'NULL';
							}
							list($dbconn) = GetDBconn();
							$sql="UPDATE hc_sistemas_estomatologicos SET normal='$anormal', observacion= $observacion, fecha_registro= now()
																	WHERE tipo_sistema_id='$vect'
																	AND evolucion_id=".$this->evolucion."
																	AND ingreso= ".$this->ingreso.";";

							$resulta = $dbconn->Execute($sql);
							if ($dbconn->ErrorNo() != 0)
							{
								$dbconn->RollbackTrans();
								$this->error = "Error al ejecutar la conexion";
								$this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								return false;
							}
							else
							{
								$this->frmError['MensajeError']="DATOS ACTUALIZADOS SATISFACTORIAMENTE";
							}
						}
					}
				}
			}
		}

		$hallazgo = $_REQUEST['hallazgo'.$pfj];
		if (!empty($hallazgo))
		{
			$sql2="SELECT evolucion_id,ingreso
					FROM hc_sistemas_estomatologico_hallazgo
					WHERE evolucion_id=".$this->evolucion."
					AND ingreso= ".$this->ingreso.";";
			$rs=$dbconn->Execute($sql2);
			if($dbconn->ErrorNo() != 0)
			{
				$dbconn->RollbackTrans();
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			if($rs->RecordCount()==0)
			{
				$sql3 = "INSERT INTO hc_sistemas_estomatologico_hallazgo (evolucion_id,
																		  ingreso,
																		  descripcion_hallazgo)
																VALUES (".$this->evolucion.",
																		".$this->ingreso.",
																		'$hallazgo');";

				$resulta = $dbconn->Execute($sql3);
				if ($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollbackTrans();
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}
			}
			else
			{
				$sql3 = "UPDATE hc_sistemas_estomatologico_hallazgo
						 SET descripcion_hallazgo = '$hallazgo'
						 WHERE evolucion_id = ".$this->evolucion."
						 AND ingreso = ".$this->ingreso.";";

				$resulta = $dbconn->Execute($sql3);
				if ($dbconn->ErrorNo() != 0)
				{
					$dbconn->RollbackTrans();
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}
			}
		}
		$dbconn->CommitTrans();
		$this->RegistrarSubmodulo($this->GetVersion());
    return true;
	}


	function DatosConsultaRevision()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query ="SELECT A.normal, A.tipo_sistema_id, A.evolucion_id,
				 A.ingreso, A.observacion, B.tipo_sistema_id, B.nombre,
				 B.estomatologico_maestro_id, B.sw_defecto, A.fecha_registro
				 FROM hc_sistemas_estomatologicos AS A
				 LEFT JOIN hc_tipos_sistemas_estomatologicos AS B
				 ON (A.tipo_sistema_id = B.tipo_sistema_id)
				 WHERE A.evolucion_id = ".$this->evolucion."
				 AND A.ingreso = ".$this->ingreso.";";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$consultas[] = $result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		return $consultas;
	}


	function DatosConsultaRevisionHallazgo()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query ="SELECT * FROM hc_sistemas_estomatologico_hallazgo
				 WHERE evolucion_id = ".$this->evolucion."
				 AND ingreso = ".$this->ingreso.";";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$hallazgos = $result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		return $hallazgos;
	}
     
     function Get_ExamenEstomatologico_PrimeraVez()
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $query= "SELECT evolucion_id 
                    FROM hc_odontogramas_primera_vez
                    WHERE tipo_id_paciente = '".$this->tipoidpaciente."'
                    AND paciente_id = '".$this->paciente."'
                    AND (sw_activo = '1' OR sw_activo = '0');"; 
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          list($primera_Evo) = $resultado->FetchRow();
          $this->primera_Evo = $primera_Evo;
 
          if (!empty($primera_Evo))
          {
               $sql ="SELECT A.normal, A.tipo_sistema_id, A.evolucion_id,
                         A.ingreso, A.observacion, B.tipo_sistema_id, B.nombre,
                         B.estomatologico_maestro_id, B.sw_defecto, A.fecha_registro
                         FROM hc_sistemas_estomatologicos AS A
                         LEFT JOIN hc_tipos_sistemas_estomatologicos AS B
                         ON (A.tipo_sistema_id = B.tipo_sistema_id)
                         WHERE A.evolucion_id = ".$primera_Evo.";";
               $result = $dbconn->Execute($sql);

               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
               else
               {
                    while (!$result->EOF)
                    {
                         $Examen[] = $result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();
                    }
               }
          }        
          return $Examen;
     }
     
    	function Get_RevisionHallazgo_PrimeraVez()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query ="SELECT * FROM hc_sistemas_estomatologico_hallazgo
				 WHERE evolucion_id = ".$this->primera_Evo.";";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$hallazgos = $result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		return $hallazgos;
	}
	
     function GetEstomatologico_PrimeraVez()
	{
		$pfj = $this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql = "SELECT C.*
				FROM hc_sistemas_estomatologicos AS A
				LEFT JOIN hc_tipos_sistemas_estomatologicos AS B ON
					   (A.tipo_sistema_id = B.tipo_sistema_id)
				LEFT JOIN hc_examen_estomatologico_maestro AS C ON
					   (B.estomatologico_maestro_id = C.estomatologico_maestro_id)
				WHERE A.evolucion_id =".$this->primera_Evo.";";

		$resultado = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while($data = $resultado->GetAssoc())
			{
				$maestrico[] = $data;
			}
			return $maestrico;
		}
	}
     
     function PartirFecha($fecha)
     {
          $a=explode('-',$fecha);
          $b=explode(' ',$a[2]);
          $c=explode(':',$b[1]);
          $d=explode('.',$c[2]);
          return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
     }

}

?>
