<?php

/**
* Submodulo de Datos de la Mujer Embarazada.
*
* Submodulo para manejar la mujer embarazada y sus diferentes controles según los trimestres y las semanas de embarazo
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_DatosEmbarazo.php,v 1.2 2005/03/08 23:31:13 tizziano Exp $
*/


/**
* DatosEmbarazo
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de datos embarazo.
*/

class DatosEmbarazo extends hc_classModules
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
	function DatosEmbarazo()
	{
       	$this->paciente;
		$this->tipoidpaciente;
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
		$spy=0;
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query = "select gestacion_id,fum from gestacion where estado=1 and paciente_id='".$this->paciente."' and tipo_id_paciente='".$this->tipoidpaciente."';";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
		  return false;
    }
		else
		{
			$semana=$this->CalcularSemanasGestante($result->fields[1],date("Y-m-d"));
			$query = "select gestacion_id from historia_reproductiva where gestacion_id=".$result->fields[0].";";
			$result1 = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				return false;
			}
			else
			{
				if(empty($result1->fields[0]))
				{
					if(!empty($result->fields[0]))
					{
						$sql="insert into historia_reproductiva (gestacion_id, edad_histo, paridad, infertilidad, retencion_placentaria, rngrande, rnpequeno, htainducida, gemelar, muerte_neonatal, parto_dificil) values (".$result->fields[0].", '".$_REQUEST['edad'.$pfj]."', '".$_REQUEST['paridad'.$pfj]."', '".$_REQUEST['infertilidad'.$pfj]."', '".$_REQUEST['retencion'.$pfj]."', '".$_REQUEST['rngrande'.$pfj]."', '".$_REQUEST['rnpequeno'.$pfj]."', '".$_REQUEST['htainducida'.$pfj]."', '".$_REQUEST['gemelar'.$pfj]."', '".$_REQUEST['mneonato'.$pfj]."', '".$_REQUEST['dificil'.$pfj]."');";
						$i=1;
						$emocion=0;
						while($i<4)
						{
							$emocional='emocional'.$pfj.$i;
							$familiar='familiar'.$pfj.$i;
							$emocion=$emocion+$_REQUEST[$emocional];
							$fam=$fam+$_REQUEST[$familiar];
							$i++;
						}
						if($emocion==1)
						{$emocion=0;}
						if($fam==1)
						{$fam=0;}
						if($semana>=14 and $semana<=27)
						{
							$dato=1;
						}
						elseif($semana>=28 and $semana<=32)
						{
							$dato=2;
						}
						elseif($semana>=33 and $semana<42)
						{
							$dato=3;
						}
						elseif($semana>=42)
						{
							$dato=3;
							$spy=1;
						}
						$query = "select count(*) from hc_condiciones_asociadas where gestacion_id=".$result->fields[0]." and condiciones_asociadas_id=$dato;";
						$result1 = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							echo "hola";
							return false;
						}
						else
						{
							if(empty($result1->fields[0]))
							{
								if($spy==0)
								{
									$i=1;
									$emocion=0;
									while($i<4)
									{
										$emocional='emocional'.$i.$pfj;
										$familiar='familiar'.$i.$pfj;
										$emocion=$emocion+$_REQUEST[$emocional];
										$fam=$fam+$_REQUEST[$familiar];
										$i++;
									}
									if($emocion==1)
									{$emocion=0;}
									if($fam==1)
									{$fam=0;}
									$sql2="insert into hc_condiciones_asociadas(evolucion_id, gestacion_id, ectopico, enf_renal_cronica, diabetes_gestacional, diabetes_mellitus, enfermedad_cardiaca, enfermedad_infecciosa_aguda, edad_autoinmune, anemia, hemorragia, vaginal, prolongado, hta, rpm, polihidramnios, rciu, embarazo_multiple, mala_presentacion, isoinmunizacion, emocional, soporte_familiar,condiciones_asociadas_id) values (".$this->evolucion.", ".$result->fields[0].", '".$_REQUEST['ectopico'.$pfj]."', '".$_REQUEST['renal'.$pfj]."', '".$_REQUEST['gestacional'.$pfj]."', '".$_REQUEST['mellitus'.$pfj]."', '".$_REQUEST['cardiaca'.$pfj]."', '".$_REQUEST['infecciosa'.$pfj]."', '".$_REQUEST['autoinmune'.$pfj]."', '".$_REQUEST['anemia'.$pfj]."', '".$_REQUEST['hemorragia'.$pfj]."', '".$_REQUEST['vaginal'.$pfj]."', '".$_REQUEST['prolongado'.$pfj]."', '".$_REQUEST['hta'.$pfj]."', '".$_REQUEST['rpm'.$pfj]."', '".$_REQUEST['polihidraminios'.$pfj]."', '".$_REQUEST['rciu'.$pfj]."', '".$_REQUEST['multiple'.$pfj]."', '".$_REQUEST['presentacion'.$pfj]."', '".$_REQUEST['isoinmunizacion'.$pfj]."', '$emocion', '$fam',$dato);";
								}
								else
								{
									$sql2="insert into hc_condiciones_asociadas(evolucion_id, gestacion_id, prolongado, condiciones_asociadas_id) values (".$this->evolucion.", ".$result->fields[0].", '".$_REQUEST['prolongado'.$pfj]."',$dato);";
								}
							}
							else
							{
								$i=1;
								$emocion=0;
								while($i<4)
								{
									$emocional='emocional'.$i.$pfj;
									$familiar='familiar'.$i.$pfj;
									$emocion=$emocion+$_REQUEST[$emocional];
									$fam=$fam+$_REQUEST[$familiar];
									$i++;
								}
								if($emocion==1)
								{$emocion=0;}
								if($fam==1)
								{$fam=0;}
								if($spy==0)
								{
									$sql2="update hc_condiciones_asociadas set ectopico='".$_REQUEST['ectopico'.$pfj]."', enf_renal_cronica='".$_REQUEST['renal'.$pfj]."', diabetes_gestacional='".$_REQUEST['gestacional'.$pfj]."', diabetes_mellitus='".$_REQUEST['mellitus'.$pfj]."', enfermedad_cardiaca='".$_REQUEST['cardiaca'.$pfj]."', enfermedad_infecciosa_aguda='".$_REQUEST['infecciosa'.$pfj]."', edad_autoinmune='".$_REQUEST['autoinmune'.$pfj]."', anemia='".$_REQUEST['anemia'.$pfj]."', hemorragia='".$_REQUEST['hemorragia'.$pfj]."', vaginal='".$_REQUEST['vaginal'.$pfj]."', prolongado='".$_REQUEST['prolongado'.$pfj]."', hta='".$_REQUEST['hta'.$pfj]."', rpm='".$_REQUEST['rpm'.$pfj]."', polihidramnios='".$_REQUEST['polihidraminios'.$pfj]."', rciu='".$_REQUEST['rciu'.$pfj]."', embarazo_multiple='".$_REQUEST['multiple'.$pfj]."', mala_presentacion='".$_REQUEST['presentacion'.$pfj]."', isoinmunizacion='".$_REQUEST['isoinmunizacion'.$pfj]."', emocional='$emocion', soporte_familiar='$fam' where  gestacion_id=".$result->fields[0]." and condiciones_asociadas_id=$dato;";
								}
								else
								{
									$sql2="update hc_condiciones_asociadas set prolongado='".$_REQUEST['prolongado'.$pfj]."' where  gestacion_id=".$result->fields[0]." and condiciones_asociadas_id=$dato;";
								}
							}
						}
						$dbconn->BeginTrans();
						error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
						if(!$dbconn->Execute($sql))
						{
							$error=$dbconn->ErrorMsg();
							echo "$error";
							$dbconn->RollbackTrans();
							return false;
						}
						else
						{
							if(!$dbconn->Execute($sql2))
							{
								$error=$dbconn->ErrorMsg();
								echo "$error";
								$dbconn->RollbackTrans();
								return false;
							}
						}
						$dbconn->CommitTrans();
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					if(!empty($result->fields[0]))
					{
						if($semana>=14 and $semana<=27)
						{
							$dato=1;
						}
						elseif($semana>=28 and $semana<=32)
						{
							$dato=2;
						}
						elseif($semana>=33 and $semana<42)
						{
							$dato=3;
						}
						elseif($semana>=42)
						{
							$dato=3;
							$spy=1;
						}
						$query = "select count(*) from hc_condiciones_asociadas where gestacion_id=".$result->fields[0]." and condiciones_asociadas_id=$dato;";
						$result1 = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							echo "hola";
							return false;
						}
						else
						{
							if(empty($result1->fields[0]))
							{
								$i=1;
								$emocion=0;
								while($i<4)
								{
									$emocional='emocional'.$i.$pfj;
									$familiar='familiar'.$i.$pfj;
									$emocion=$emocion+$_REQUEST[$emocional];
									$fam=$fam+$_REQUEST[$familiar];
									$i++;
								}
								if($emocion==1)
								{$emocion=0;}
								if($fam==1)
								{$fam=0;}
								if($spy==0)
								{
									$sql2="insert into hc_condiciones_asociadas(evolucion_id, gestacion_id, ectopico, enf_renal_cronica, diabetes_gestacional, diabetes_mellitus, enfermedad_cardiaca, enfermedad_infecciosa_aguda, edad_autoinmune, anemia, hemorragia, vaginal, prolongado, hta, rpm, polihidramnios, rciu, embarazo_multiple, mala_presentacion, isoinmunizacion, emocional, soporte_familiar,condiciones_asociadas_id) values (".$this->evolucion.", ".$result->fields[0].", '".$_REQUEST['ectopico'.$pfj]."', '".$_REQUEST['renal'.$pfj]."', '".$_REQUEST['gestacional'.$pfj]."', '".$_REQUEST['mellitus'.$pfj]."', '".$_REQUEST['cardiaca'.$pfj]."', '".$_REQUEST['infecciosa'.$pfj]."', '".$_REQUEST['autoinmune'.$pfj]."', '".$_REQUEST['anemia'.$pfj]."', '".$_REQUEST['hemorragia'.$pfj]."', '".$_REQUEST['vaginal'.$pfj]."', '".$_REQUEST['prolongado'.$pfj]."', '".$_REQUEST['hta'.$pfj]."', '".$_REQUEST['rpm'.$pfj]."', '".$_REQUEST['polihidraminios'.$pfj]."', '".$_REQUEST['rciu'.$pfj]."', '".$_REQUEST['multiple'.$pfj]."', '".$_REQUEST['presentacion'.$pfj]."', '".$_REQUEST['isoinmunizacion'.$pfj]."', '$emocion', '$fam',$dato);";
								}
								else
								{
									$sql2="insert into hc_condiciones_asociadas(evolucion_id, gestacion_id, prolongado, condiciones_asociadas_id) values (".$this->evolucion.", ".$result->fields[0].", '".$_REQUEST['prolongado'.$pfj]."',$dato);";
								}
								$dbconn->BeginTrans();
								error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
								if(!$dbconn->Execute($sql2))
								{
									$error=$dbconn->ErrorMsg();
									echo "$error";
									$dbconn->RollbackTrans();
									return false;
								}
								else
								{
									$dbconn->CommitTrans();
								}
							}
							else
							{
								$i=1;
								$emocion=0;
								while($i<4)
								{
									$emocional='emocional'.$i.$pfj;
									$familiar='familiar'.$i.$pfj;
									$emocion=$emocion+$_REQUEST[$emocional];
									$fam=$fam+$_REQUEST[$familiar];
									$i++;
								}
								if($emocion==1)
								{$emocion=0;}
								if($fam==1)
								{$fam=0;}
								if($spy==0)
								{
									$sql2="update hc_condiciones_asociadas set ectopico='".$_REQUEST['ectopico'.$pfj]."', enf_renal_cronica='".$_REQUEST['renal'.$pfj]."', diabetes_gestacional='".$_REQUEST['gestacional'.$pfj]."', diabetes_mellitus='".$_REQUEST['mellitus'.$pfj]."', enfermedad_cardiaca='".$_REQUEST['cardiaca'.$pfj]."', enfermedad_infecciosa_aguda='".$_REQUEST['infecciosa'.$pfj]."', edad_autoinmune='".$_REQUEST['autoinmune'.$pfj]."', anemia='".$_REQUEST['anemia'.$pfj]."', hemorragia='".$_REQUEST['hemorragia'.$pfj]."', vaginal='".$_REQUEST['vaginal'.$pfj]."', prolongado='".$_REQUEST['prolongado'.$pfj]."', hta='".$_REQUEST['hta'.$pfj]."', rpm='".$_REQUEST['rpm'.$pfj]."', polihidramnios='".$_REQUEST['polihidraminios'.$pfj]."', rciu='".$_REQUEST['rciu'.$pfj]."', embarazo_multiple='".$_REQUEST['multiple'.$pfj]."', mala_presentacion='".$_REQUEST['presentacion'.$pfj]."', isoinmunizacion='".$_REQUEST['isoinmunizacion'.$pfj]."', emocional='$emocion', soporte_familiar='$fam' where  gestacion_id=".$result->fields[0]." and condiciones_asociadas_id=$dato;";
								}
								else
								{
									$sql2="update hc_condiciones_asociadas set prolongado='".$_REQUEST['prolongado'.$pfj]."' where  gestacion_id=".$result->fields[0]." and condiciones_asociadas_id=$dato;";
								}
								$dbconn->BeginTrans();
								error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
								if(!$dbconn->Execute($sql2))
								{
									$error=$dbconn->ErrorMsg();
									echo "$error";
									$dbconn->RollbackTrans();
									return false;
								}
								else
								{
									$dbconn->CommitTrans();
								}
							}
						}
					}
					else
					{
						return false;
					}
				}
			}
		}
		return true;
	}

	function Sexo()
	{
		list($dbconn) = GetDBconn();
		$query = "select sexo_id from pacientes where tipo_id_paciente='".$this->tipoidpaciente."' and paciente_id='".$this->paciente."'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
		  echo "hola";
      return false;
    }
		else
		{
			return $result->fields[0];
		}
		return true;
	}

	function FechaNacimiento()
	{
		list($dbconn) = GetDBconn();
		$query = "select fecha_nacimiento from pacientes where tipo_id_paciente='".$this->tipoidpaciente."' and paciente_id='".$this->paciente."'";
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
		  echo "hola";
      return false;
    }
		else
		{
		  $i=0;
      while (!$result->EOF)
		  {
  	    $fecha=$result->fields[0];
				$i++;
	 		  $result->MoveNext();
			}
		}
		return $fecha;
	}

	function GestacionPaciente($fecha,$gestacion)
	{
		list($dbconn) = GetDBconn();
		$query = "select gestacion_id,fum from gestacion as a where a.paciente_id='".$this->paciente."' and a.tipo_id_paciente='".$this->tipoidpaciente."' and a.estado=1;";
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
		  echo "hola";
      return false;
    }
		else
		{
		  $i=0;
      while (!$result->EOF)
		  {
  	    $gestacion=$result->fields[0];
				$fecha=$result->fields[1];
				$i++;
	 		  $result->MoveNext();
			}
		}
	}

	function HistoriaReproductiva()
	{
		list($dbconn) = GetDBconn();
		$query = "select b.gestacion_id, b.edad_histo, b.paridad, b.infertilidad, b.retencion_placentaria, b.rngrande, b.rnpequeno, b.htainducida, b.gemelar, b.muerte_neonatal, b.parto_dificil from gestacion as a,historia_reproductiva as b where a.paciente_id='".$this->paciente."' and a.tipo_id_paciente='".$this->tipoidpaciente."' and a.estado=1 and a.gestacion_id=b.gestacion_id;";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			echo "hola";
			return false;
		}
		else
		{
			$i=0;
			while ($i<sizeof($result->fields))
			{
				$cron[$i]=$result->fields[$i];
				$i++;
			}
		}
		return $cron;
	}

	function CondicionesAsociadas($gestacion,$semana)
	{
		list($dbconn) = GetDBconn();
		if(!empty($gestacion))
		{
			if($semana>=14 and $semana<=27)
			{
				$query = "select evolucion_id, gestacion_id, condiciones_asociadas_id, ectopico, enf_renal_cronica, diabetes_gestacional, diabetes_mellitus, enfermedad_cardiaca, enfermedad_infecciosa_aguda, edad_autoinmune, anemia, hemorragia, vaginal, prolongado, hta, rpm, polihidramnios, rciu, embarazo_multiple, mala_presentacion, isoinmunizacion, emocional, soporte_familiar from hc_condiciones_asociadas where gestacion_id=".$gestacion." and condiciones_asociadas_id=1;";
			}
			elseif($semana>=28 and $semana<=32)
			{
				$query = "select evolucion_id, gestacion_id, condiciones_asociadas_id, ectopico, enf_renal_cronica, diabetes_gestacional, diabetes_mellitus, enfermedad_cardiaca, enfermedad_infecciosa_aguda, edad_autoinmune, anemia, hemorragia, vaginal, prolongado, hta, rpm, polihidramnios, rciu, embarazo_multiple, mala_presentacion, isoinmunizacion, emocional, soporte_familiar from hc_condiciones_asociadas where gestacion_id=".$gestacion." and condiciones_asociadas_id=2;";
			}
			elseif($semana>=32)
			{
				$query = "select evolucion_id, gestacion_id, condiciones_asociadas_id, ectopico, enf_renal_cronica, diabetes_gestacional, diabetes_mellitus, enfermedad_cardiaca, enfermedad_infecciosa_aguda, edad_autoinmune, anemia, hemorragia, vaginal, prolongado, hta, rpm, polihidramnios, rciu, embarazo_multiple, mala_presentacion, isoinmunizacion, emocional, soporte_familiar from hc_condiciones_asociadas where gestacion_id=".$gestacion." and condiciones_asociadas_id=3;";
			}
		}
		else
		{
			$query = "select count(*) from hc_condiciones_asociadas where gestacion_id=0";
		}
		$result2 = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			echo "hola";
			return false;
		}
		else
		{
			$i=0;
			while ($i<sizeof($result2->fields))
			{
				$dato[$i]=$result2->fields[$i];
				$i++;
			}
		}
		return $dato;
	}

	function CondicionesAsociadasConsulta($gestacion)
	{
		list($dbconn) = GetDBconn();
		if(!empty($gestacion))
		{
			$query = "select evolucion_id, gestacion_id, condiciones_asociadas_id, ectopico, enf_renal_cronica, diabetes_gestacional, diabetes_mellitus, enfermedad_cardiaca, enfermedad_infecciosa_aguda, edad_autoinmune, anemia, hemorragia, vaginal, prolongado, hta, rpm, polihidramnios, rciu, embarazo_multiple, mala_presentacion, isoinmunizacion, emocional, soporte_familiar from hc_condiciones_asociadas where gestacion_id=".$gestacion." order by condiciones_asociadas_id;";
		}
		else
		{
			$query = "select count(*) from hc_condiciones_asociadas where gestacion_id=0";
		}
		$result2 = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			echo "hola";
			return false;
		}
		else
		{
			$j=0;
			while(!$result2->EOF)
			{
				$i=0;
				while ($i<sizeof($result2->fields))
				{
					$dato[$j][$i]=$result2->fields[$i];
					$i++;
				}
				$j++;
				$result2->MoveNext();
			}
		}
		return $dato;
	}

}
?>
