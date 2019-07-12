<?php

/**
* Submodulo de Protocolos Medicos.
*
* Submodulo para manejar los diferentes pasos que se debe seguir con un paciente según unas caracteristicas del
* paciente y demas datos.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_ProtocolosMedicos.php,v 1.2 2005/03/09 13:34:55 tizziano Exp $
*/


/**
* ProtocolosMedicos
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de protocolos medicos.
*/

class ProtocolosMedicos extends hc_classModules
{

/**
* Contiene el sexo del paciente con el cual se esta trabajando.
*
* @var text
* @access public
*/
	//var $sexo='';
	var $saber;


/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function ProtocolosMedicos()
	{
				//$this->sexo=1;
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
	  error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
	  list($dbconn) = GetDBconn();
	  $sql ="SELECT count(tipo_protocolo_id) FROM tipo_protocolo;";
	  $result=$dbconn->Execute($sql);
		$t=$result->fields[0];
	  $i=0;
		$observaciones="";
		$recomendaciones="";
	  while($i<$t)
	  {
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
	    $sql="SELECT detalle_protocolo_id,tipo_protocolo_id
			FROM detalle_protocolo WHERE tipo_protocolo_id=".($i+1).";";
			$result=$dbconn->Execute($sql);
			$r=0;
			while (!$result->EOF)
			{
			  $tipo_det[0][$r]=$result->fields[0];
				$tipo_det[1][$r]=$result->fields[1];
				$result->MoveNext();
				$r++;
			}
			$r=0;
			while($r<sizeof($tipo_det[0]))
		  {
		    $prot="prot";
		    $prot.=$tipo_det[0][$r].$pfj;
		    $clasificar="clasificar";
		    $clasificar.=$tipo_det[0][$r].$pfj;
		    $tratar="tratar";
		    $tratar.=$tipo_det[0][$r].$pfj;
	      if($_REQUEST[$prot]==1)
		    {
			    $sql="INSERT into hc_control_protocolos(evolucion_id,paciente_id,
					tipo_id_paciente,tipo_protocolo_id,detalle_protocolo_id,fecha,
					clasificar,tratar)values(".$this->evolucion.",'".$this->paciente."',
					'".$this->tipoidpaciente."',".($i+1).",".$tipo_det[0][$r].",
					'".date("Y-m-d")."'	,'".$_REQUEST[$clasificar]."',
					'".$_REQUEST[$tratar]."');";

					if(!$dbconn->Execute($sql))
	        {
				    $this->error = "Error DB:";
        		$this->mensajeDeError = $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
	          return false;
	        }

					$sql="SELECT apoyo_protocolo.detalle_protocolo_id FROM apoyo_protocolo,
					detalle_protocolo WHERE apoyo_protocolo.detalle_protocolo_id=
					detalle_protocolo.detalle_protocolo_id and
					apoyo_protocolo.detalle_protocolo_id=".$tipo_det[0][$r].";";
				  $result=$dbconn->Execute($sql);
					$s=0;
					while (!$result->EOF)
					{
						$tipo_apo[0][$s]=$result->fields[0];
						$result->MoveNext();
						$s++;
					}
					$s=0;
				  while($s<sizeof($tipo_apo[0]))
				  {
				    $exam="exam";
					  $exam.=$tipo_apo[0][$s].$pfj;
						$dat=$_REQUEST[$exam];
					  if(!empty($dat))
					  {
					    $a=explode(",",$_REQUEST[$exam]);
							$sql="INSERT into hc_solicitud_apoyo_diag (tipo_solicitud_id, observacion,
							tarifario_id,cargo,fecha,evolucion_id)values(".$a[0].",'".$a[1]."',
							'".$a[2]."','".$a[3]."','".date("Y-m-d")."',".$this->evolucion.");";
						  if(!$dbconn->Execute($sql))
	            {
				        $this->error = "Error DB:";
            		$this->mensajeDeError = $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
	              return false;
	            }
					  }
				    $s++;
				  }
	      }
		    $r++;
		  }
			$obser="observaciones";
			$obser.=($i+1).$pfj;
			if(!empty($_REQUEST[$obser]))
			{
		    $observaciones.=$_REQUEST[$obser]."\n";
			}
			$recom="recomendaciones";
			$recom.=($i+1).$pfj;
			if(!empty($_REQUEST[$recom]))
			{
		    $recomendaciones.=$_REQUEST[$recom]."\n";
			}
			$i++;
			$dbconn->CommitTrans();
	  }
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		if(!empty($observaciones))
		{
				$sql="SELECT hc_observacion_id,observaciones,recomendaciones FROM
				hc_observaciones WHERE evolucion_id=".$this->evolucion.";";
				$result=$dbconn->Execute($sql);
				if($result->fields[0]==0)
				{
					$sql="INSERT into hc_observaciones (observaciones, recomendaciones,
					evolucion_id)values('".$observaciones."', '".$recomendaciones."',
					".$this->evolucion.");";
					if(!$dbconn->Execute($sql))
					{
						$this->error = "Error DB:";
            		$this->mensajeDeError = $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
				else
				{
					$a=$result->fields[1];
					$a.=$observaciones;
					$observaciones=$a;
					$a=$result->fields[2];
					$a.=$recomendaciones;
					$recomendaciones=$a;
					$sql="UPDATE hc_observaciones set observaciones='".$observaciones."',
					recomendaciones='".$recomendaciones."'
					WHERE evolucion_id=".$this->evolucion.";";
					if(!$dbconn->Execute($sql))
					{
						$this->error = "Error DB:";
						$this->mensajeDeError = $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
			}
			$dbconn->CommitTrans();
			return true;
		}

/*
		function BusquedaFechaPaciente()
		{
			list($dbconn) = GetDBconn();
			ECHO $query = "SELECT fecha_nacimiento FROM pacientes WHERE
			tipo_id_paciente='".$this->tipoidpaciente."' and
			paciente_id='".$this->paciente."'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error DB:";
				$this->mensajeDeError = $dbconn->ErrorMsg();
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
*/

//CLZC  trae todoslos protocolos medicos que cumplen con las condiciones
//de busqueda para ese paciente.
		function BusquedaProtocoloMedico($edad_paciente)
		{
			list($dbconn) = GetDBconn();

			//DETERMINACION DEL ESTADO DE GESTACION - SOLO PARA MUJERES
	     $gestante = 0;
			 if(strtoupper($this->datosPaciente[sexo_id]) == 'F')
			 {
						echo $query = "SELECT count(gestacion_id)
						FROM	gestacion WHERE paciente_id='".$this->paciente."' and
						tipo_id_paciente= '".$this->tipoidpaciente."' and estado=1";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al consultar el estado de gestacion del apciente";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						else
						{
							while (!$result->EOF)
							{
								$vector[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
							}
						}

						if ($vector[count]>0)
						{
							$gestante = 1;
						}
			  }
				echo '--->';
				echo $gestante;
        echo '--->';
		  //FIN DE ESTADO DE GESTACION
      $edad_paciente_meses =  (($edad_paciente[años]*12) + ($edad_paciente[meses]));

			echo $query = "
			SELECT tipo_protocolo_id,nombre,caracteristicas,tiempo

			FROM tipo_protocolo as a

			WHERE gestante=	".$gestante."
						and (sexo='".$this->datosPaciente[sexo_id]."' OR sexo='0')
						and edad_min_meses<=".$edad_paciente_meses."
						and	edad_max_meses>=".$edad_paciente_meses."

		  UNION

			SELECT a.tipo_protocolo_id,	c.nombre,b.caracteristica,b.tiempo

			FROM
			      (SELECT DISTINCT tipo_protocolo_id

							FROM	protocolo_cronico,
							(
									SELECT tipo_cronico_id

									FROM cronicos

									WHERE sino='1'  and	paciente_id='".$this->paciente."' and
												tipo_id_paciente='".$this->tipoidpaciente."'
							) hola
							WHERE protocolo_cronico.tipo_cronico_id=hola.tipo_cronico_id
									EXCEPT
											SELECT tipo_protocolo_id

											FROM tipo_protocolo as a

											WHERE	gestante=	".$gestante."
													and (sexo='".$this->datosPaciente[sexo_id]."' OR sexo='0')
													and edad_min_meses<=".$edad_paciente_meses."
													and	edad_max_meses>=".$edad_paciente_meses."
						) as a,	protocolo_cronico as b, tipo_protocolo as c
			WHERE a.tipo_protocolo_id=b.tipo_protocolo_id and
			      a.tipo_protocolo_id=c.tipo_protocolo_id
						order by tipo_protocolo_id;";

			$result = $dbconn->Execute($query);
			$s=0;
			$this->saber=0;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error DB:";
				$this->mensajeDeError = $dbconn->ErrorMsg();
				return false;
			}
			else
			{
			//condicion que valida el tiempo transcurrido entre una realizacion de un protocolo
			// en una consulta y otro	protocolo en otra consulta

          /*else
						{
							while (!$result->EOF)
							{
								$vector[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
							}
						}*/

				while (!$result->EOF)
				{
					$query2 = "SELECT fecha FROM hc_control_protocolos WHERE
					tipo_protocolo_id=".$result->fields[0]." and
					paciente_id='".$this->paciente."' and
					tipo_id_paciente='".$this->tipoidpaciente."' order by fecha desc;";
					$result1=$dbconn->Execute($query2);
					if(empty($result1->fields[0]))
					{
						$oiga2=CalcularEdad(date("Y-m-d"),date("Y-m-d"));
					}
					else
					{
						$oiga2=CalcularEdad($result1->fields[0],date("Y-m-d"));
					}
					$a=$oiga2['meses'];
					if(empty($result1->fields[0]))
					{
						$tipo_pro[0][$s]=$result->fields[0];
						$tipo_pro[1][$s]=$result->fields[1];
						$tipo_pro[2][$s]=$result->fields[2];
						$s++;
						$this->saber=1;
					}
					else
					{
						if($a!=0)
						{
							if(($result->fields[3]<=$oiga2['meses']))
							{
								$tipo_pro[0][$s]=$result->fields[0];
								$tipo_pro[1][$s]=$result->fields[1];
								$tipo_pro[2][$s]=$result->fields[2];
								$s++;
								$t=1;
							}
						}
					}
					$result->MoveNext();
				}
			}
			return $tipo_pro;
		}


		//CLZC  trae todos los items que componen los protocolos medicos

		function BusquedaDetalleMedico($edad_paciente)
		{
			list($dbconn) = GetDBconn();
			//DETERMINACION DEL ESTADO DE GESTACION - SOLO PARA MUJERES
	     $gestante = 0;
			 if(strtoupper($this->datosPaciente[sexo_id]) == 'F')
			 {
						$query = "SELECT count(gestacion_id)
						FROM	gestacion WHERE paciente_id='".$this->paciente."' and
						tipo_id_paciente= '".$this->tipoidpaciente."' and estado=1";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al consultar el estado de gestacion del apciente";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						else
						{
							while (!$result->EOF)
							{
								$vector[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
							}
						}
						if ($vector[count]>0)
						{
							$gestante = 1;
						}
			  }
		  //FIN DE ESTADO DE GESTACION
      $edad_paciente_meses =  (($edad_paciente[años]*12) + ($edad_paciente[meses]));


			$query = "
			SELECT c.nombre,d.caracteristicas,c.tipo_protocolo_id, detalle_protocolo_id

			FROM detalle_protocolo as c JOIN

			(SELECT tipo_protocolo_id,nombre,	caracteristicas,tiempo

			FROM tipo_protocolo as a

			WHERE gestante= $gestante
            and (sexo='".$this->datosPaciente[sexo_id]."' OR sexo='0')
			      and edad_min_meses<=".$edad_paciente_meses."	and
			      edad_max_meses>=".$edad_paciente_meses."

		  UNION

			SELECT a.tipo_protocolo_id,c.nombre,b.caracteristica,	b.tiempo

			FROM
			    (
					  SELECT distinct tipo_protocolo_id

						FROM protocolo_cronico,
						    (SELECT tipo_cronico_id

								FROM cronicos

								WHERE sino='1'	and paciente_id='".$this->paciente."'
								and	tipo_id_paciente='".$this->tipoidpaciente."') hola

			      WHERE protocolo_cronico.tipo_cronico_id=hola.tipo_cronico_id

						  EXCEPT

			          SELECT tipo_protocolo_id

								FROM tipo_protocolo as a

								WHERE gestante=$gestante and (sexo='".$this->datosPaciente[sexo_id]."'
								OR sexo='0') and edad_min_meses<=".$edad_paciente_meses."
								and	edad_max_meses>=".$edad_paciente_meses.")

								as a, protocolo_cronico as b,	tipo_protocolo as c

								WHERE a.tipo_protocolo_id=b.tipo_protocolo_id
								      and a.tipo_protocolo_id=c.tipo_protocolo_id
								      order by tipo_protocolo_id) as d	on
								      c.tipo_protocolo_id=d.tipo_protocolo_id
											order by c.tipo_protocolo_id;";

			$result = $dbconn->Execute($query);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error DB:";
				$this->mensajeDeError = $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				while (!$result->EOF)
				{
					$tipo_det[0][$i]=$result->fields[0];
					$tipo_det[1][$i]=$result->fields[1];
					$tipo_det[2][$i]=$result->fields[2];
					$tipo_det[3][$i]=$result->fields[3];
					$result->MoveNext();
					$i++;
				}
			}
			return $tipo_det;
		}

    //CLZC  trae todos los apoyos e interconsultas que se pueden solicitar
		//para cada item del protocolo

		function BusquedaApoyoMedico($edad_paciente)
		{
			list($dbconn) = GetDBconn();

			//DETERMINACION DEL ESTADO DE GESTACION - SOLO PARA MUJERES
	     $gestante = 0;
			 if(strtoupper($this->datosPaciente[sexo_id]) == 'F')
			 {
						$query = "SELECT count(gestacion_id)
						FROM	gestacion WHERE paciente_id='".$this->paciente."' and
						tipo_id_paciente= '".$this->tipoidpaciente."' and estado=1";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al consultar el estado de gestacion del apciente";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						else
						{
							while (!$result->EOF)
							{
								$vector[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
							}
						}
						if ($vector[count]>0)
						{
							$gestante = 1;
						}
			  }
		  //FIN DE ESTADO DE GESTACION
      $edad_paciente_meses =  (($edad_paciente[años]*12) + ($edad_paciente[meses]));
			$query = "
			SELECT tipo_solicitud,observacion,tarifario_id, cargo,
			apoyo_protocolo.detalle_protocolo_id
			FROM apoyo_protocolo,	(SELECT c.nombre,d.caracteristicas,c.tipo_protocolo_id,
			detalle_protocolo_id	FROM detalle_protocolo as c join (SELECT tipo_protocolo_id,
			nombre,caracteristicas,	tiempo FROM tipo_protocolo as a
			WHERE gestante=$gestante


			and (sexo='".$this->datosPaciente[sexo_id]."' OR sexo='0')
			and edad_min_meses<=".$edad_paciente_meses."	and
			edad_max_meses>=".$edad_paciente_meses."


			UNION
			SELECT a.tipo_protocolo_id,c.nombre,b.caracteristica,b.tiempo
			FROM (SELECT distinct tipo_protocolo_id FROM protocolo_cronico,
			(SELECT tipo_cronico_id	FROM cronicos WHERE sino='1' and
			paciente_id='".$this->paciente."' and
			tipo_id_paciente='".$this->tipoidpaciente."') hola
			WHERE protocolo_cronico.tipo_cronico_id=hola.tipo_cronico_id EXCEPT
			SELECT tipo_protocolo_id FROM tipo_protocolo as a
			WHERE	gestante=$gestante

			and (sexo='".$this->datosPaciente[sexo_id]."' OR sexo='0')
			and edad_min_meses<=".$edad_paciente_meses."	and
			edad_max_meses>=".$edad_paciente_meses.")

			as a, protocolo_cronico as b,tipo_protocolo as c
			WHERE a.tipo_protocolo_id=b.tipo_protocolo_id and
			a.tipo_protocolo_id=c.tipo_protocolo_id order by tipo_protocolo_id) as d on
			c.tipo_protocolo_id=d.tipo_protocolo_id order by c.tipo_protocolo_id) as s
			WHERE apoyo_protocolo.detalle_protocolo_id=s.detalle_protocolo_id
			order by apoyo_protocolo.detalle_protocolo_id;";
			$result = $dbconn->Execute($query);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error DB:";
				$this->mensajeDeError = $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				while (!$result->EOF)
				{
					$tipo_apo[0][$i]=$result->fields[0];
					$tipo_apo[1][$i]=$result->fields[1];
					$tipo_apo[2][$i]=$result->fields[2];
					$tipo_apo[3][$i]=$result->fields[3];
					$tipo_apo[4][$i]=$result->fields[4];
					$result->MoveNext();
					$i++;
				}
			}
			return $tipo_apo;
		}

		function ConsultaProtocolosMedicos()
		{
			list($dbconn) = GetDBconn();
			$query = "
			SELECT tipo_protocolo.nombre, detalle_protocolo.nombre, clasificar,
			tratar, hc_control_protocolos.tipo_protocolo_id
			FROM hc_control_protocolos,	tipo_protocolo,detalle_protocolo
			WHERE hc_control_protocolos.tipo_protocolo_id=tipo_protocolo.tipo_protocolo_id
			and hc_control_protocolos.detalle_protocolo_id=detalle_protocolo.detalle_protocolo_id
			and evolucion_id=".$this->evolucion." and 	paciente_id='".$this->paciente."'
			and tipo_id_paciente='".$this->tipoidpaciente."'
			order by hc_control_protocolos.tipo_protocolo_id;";
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
					$hc_pro[0][$i]=$result->fields[0];
					$hc_pro[1][$i]=$result->fields[1];
					$hc_pro[2][$i]=$result->fields[2];
					$hc_pro[3][$i]=$result->fields[3];
					$hc_pro[4][$i]=$result->fields[4];
					$result->MoveNext();
					$i++;
				}
			}
			return $hc_pro;
		}


}
?>
