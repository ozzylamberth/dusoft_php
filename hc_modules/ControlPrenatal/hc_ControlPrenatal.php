<?php
/**
* Submodulo de ControlPrenatal (PHP).
*
* Submodulo para manejar la informacion de una madre mediante datos de parto y datos del recien nacido
* verificando su estado de salud en la madres en pre y post parto, al igual que la salud del recien nacido.
* @author Jairo Duvan Diaz Martinez <planetjd@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_ControlPrenatal.php,v 1.3 2006/06/30 16:01:58 luis Exp $
*/

/**
* ControlPrenatal_PHP
*
* Clase para procesar los datos del formulario mediante la operaciones de consulta ,captura y de insercion.
* del submodulo ControlPrenatal, se extiende la clase ControlPrenatal y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/
class ControlPrenatal extends hc_classModules
{

	/**
* Contiene la identificacion del paciente, el id para poder trabajar en los demas procesos
*
* @var string
* @access public
*/
  var $paciente='';
	/**
* Contiene el tipo de paciente al que pertenece ese id para poder seguir con el proceso
*
* @var string
* @access public
*/
	var $tipo='';
	/**
* Contiene el id de la gestacion, osea que contiene los datos del  embarazo actual de la paciente.
*
* @var string
* @access public
*/
	var $gestacion='';
	function ControlPrenatal($evolucion)
	{
    $this->gestacion;
		return true;
	}

	function GetConsulta()
	{
		if($this->frmConsulta()==false)
		{
			return true;
		}
		return $this->salida;
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
		'autor'=>'JAIRO DUVAN DIAZ MARTINEZ',
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


	function GetForma()
	{
	    if(empty($_REQUEST['accion']))
		{
	    $this->frmForma();
		}
		else
		{
			if($this->InsertDatos()==true)
			{
				//$this->frmError["MensajeError"]='DATOS GUARDADOS SATISFACTORIAMENTE';
				$this->frmForma();
			}
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
    
  function ConsultaControl()
	{
	$this->tipo=$this->tipoidpaciente;
   list($dbconn) = GetDBconn();
			  	$query="SELECT c.peso,c.talta, c.tbaja,c.alturauterina,
					c.fcf,
					case c.movfetal when 1 then 'Si' else 'No' end
					,case c.valorcuellouterino when 1 then 'Si' else 'No' end
					, case c.edemas when 1 then 'Si' else 'No' end,
					case c.monitoreofetal when 1 then 'Si' else 'No' end,f.tipo,c.evolucion_id,e.fecha
					from hc_controles c ,gestacion d,hc_evoluciones e, hc_tipo_partos_presentacion f
					where f.partos_presentacion_id=c.partos_presentacion_id and
					e.evolucion_id=c.evolucion_id and
					c.gestacion_id=d.gestacion_id and d.estado=1 and d.tipo_id_paciente='".$this->tipo."' and d.paciente_id='".$this->paciente."' order by evolucion_id asc";
 //echo $query;
 //exit();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al consulta de la tabla hc_controles";
				return false;
		}

		return $result;
	}


	function ConsultaGestacion()
	{
		$this->tipo=$this->tipoidpaciente;
   	list($onn) = GetDBconn();
		$buscon = "select fum,gestacion_id from gestacion as a where a.paciente_id='".
		$this->paciente."' and a.tipo_id_paciente='".$this->tipo."' and a.estado=1;";
		$source=$onn->Execute($buscon);
					if ($onn->ErrorNo() != 0)
					{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}

						return $source;
	}


	function RevisarAltura($gestacion)
	{
	$this->tipo=$this->tipoidpaciente;
		list($dbconn) = GetDBconn();
		$bu="SELECT  alturauterina,talta,tbaja,semanas,peso from hc_controles where gestacion_id=".$gestacion." order by evolucion_id";
		$dat=$dbconn->Execute($bu);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al buscar en la tabla hc_controles.";
				return false;
		}

		return $dat;
  }

function ComboPartosPresentacion()
{
  list($dbconn) = GetDBconn();
 	$query4 = "SELECT partos_presentacion_id,tipo FROM hc_tipo_partos_presentacion";
	$presentacion=$dbconn->Execute($query4);
	if ($dbconn->ErrorNo() != 0)
	{
		return false;
	}
 return $presentacion;

}



/*esta funcion evita que afecte el cache en las graficas, asi que hay que tener cuidado
 * le adiciona al nombre una numeracion para asi visualizar el archivo.
*/
function AsignaNombreVirtual()
{
	list($dbconn) = GetDBconn();
	$query="select nextval('asignanombrevirtualgraph_seq');";
	$resulta=$dbconn->execute($query);
	if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al traer la secuencia";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
return $resulta->fields[0];
}




/**
* Funcion que inserta los datos del control prenatal que se le hace a la paciente.
* y si esta en su misma evolucion, se pueden actualizar los datos.
* @return boolean
*/

		function InsertDatos()
		{

				//	print_r($_REQUEST);
					//exit;
								$pfj=$this->frmPrefijo;

								if(!$_REQUEST['peso'.$pfj] || !$_REQUEST['tenalta'.$pfj] || !$_REQUEST['tenbaja'.$pfj] ||  !$_REQUEST['fcf'.$pfj])
								{
								//echo "entro$pfj";
								//exit();
										if(!$_REQUEST['peso'.$pfj]) { $this->frmError["peso"]=1; }
										if(!$_REQUEST['tenalta'.$pfj]) { $this->frmError["taa"]=1; }
										if(!$_REQUEST['tenbaja'.$pfj]) { $this->frmError["tab"]=1; }
										//if(!$_REQUEST['alturau'.$pfj]) { $this->frmError["au"]=1; }
										if(!$_REQUEST['fcf'.$pfj]) { $this->frmError["fcf"]=1; }
										$this->frmError["MensajeError"]='FALTAN DATOS OBLIGATORIOS';
										$this->salida="";
										$this->frmForma();
										return true;
								}


								list($dbconn) = GetDBconn();
								$bu="SELECT  gestacion_id from gestacion where estado =1 and paciente_id='".$this->paciente."'";
								$dato=$dbconn->Execute($bu);
								$this->gestacion=$dato->fields[0];


							/*revisa si hay que actualizar o insertar los datos*/
							list($conn) = GetDBconn();
							$bus="SELECT  count(evolucion_id) from hc_controles where evolucion_id='".$this->evolucion."'";
							$res=$conn->Execute($bus);
							$contador=$res->fields[0];
							/*aqui verificamos si hay que actualizar o insertar los datos*/


							if(empty($_REQUEST['alturau'.$pfj]))
							{
								$ALTURA='NULL';
							}
							else
							{
									$ALTURA=$_REQUEST['alturau'.$pfj];
							}

							if ((empty($contador)) or ($contador==0))
						{
									$sql="insert into hc_controles
									( paciente_id,
										tipo_id_paciente,
										peso,
										talta,
										tbaja,
										alturauterina,
										fcf,
										movfetal,
										valorcuellouterino,
										edemas,
										monitoreofetal,
										semanas,
										gestacion_id,
										partos_presentacion_id,
										evolucion_id
											)
											values(
											'".$this->paciente."',
											'".$this->tipoidpaciente."',
												'".$_REQUEST['peso'.$pfj]."',
												'".$_REQUEST['tenalta'.$pfj]."',
												'".$_REQUEST['tenbaja'.$pfj]."',
												$ALTURA,
												'".$_REQUEST['fcf'.$pfj]."',
												'".$_REQUEST['movfetal'.$pfj]."',
												'".$_REQUEST['cuellou'.$pfj]."',
												'".$_REQUEST['edemas'.$pfj]."',
												'".$_REQUEST['monife'.$pfj]."',
												'".$_REQUEST['semana'.$pfj]."',
												".$this->gestacion.",
												'".$_REQUEST['presentacion'.$pfj]."',
												".$this->evolucion.");";

					//echo $sql;
					//exit();
							}else

								{

									$peso ="peso".$pfj;
									$tenalta='tenalta'.$pfj;
									$tenbaja='tenbaja'.$pfj;
									//$alturau='alturau'.$pfj;
									$movfetal='movfetal'.$pfj;
									$cuellou='cuellou'.$pfj;
									$edemas='edemas'.$pfj;
									$monife='monife'.$pfj;
									$fcf='fcf'.$pfj;
									$semana='semana'.$pfj;
									$presentacion='presentacion'.$pfj;

										$sql="update  hc_controles
										set
										paciente_id='".$this->paciente."',
										tipo_id_paciente='".$this->tipoidpaciente."' ,
										peso=$_REQUEST[$peso],
										talta=$_REQUEST[$tenalta],
										tbaja= $_REQUEST[$tenbaja],
										alturauterina=$ALTURA,
										fcf=$_REQUEST[$fcf],
										movfetal=$_REQUEST[$movfetal],
										valorcuellouterino=$_REQUEST[$cuellou],
										edemas=$_REQUEST[$edemas],
										monitoreofetal=$_REQUEST[$monife],
										semanas=$_REQUEST[$semana],
										gestacion_id=$this->gestacion,
										partos_presentacion_id=$_REQUEST[$presentacion]
										where evolucion_id=$this->evolucion;";
								}


//echo $sql;
//exit();
			// Reportar errores para depuracion.
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
list($dbconn) = GetDBconn();
if(!$dbconn->Execute($sql))
{
	$error=$dbconn->ErrorMsg();
	echo"$error";
	return false;
}
 return true;
}
}
?>
