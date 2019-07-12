<?php
/**
* Submodulo de ControlPrenatal (PHP).
*
* Submodulo para manejar la informacion de una madre mediante datos de parto y datos del recien nacido
* verificando su estado de salud en la madres en pre y post parto, al igual que la salud del recien nacido.
* @author Jairo Duvan Diaz Martinez <planetjd@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_Tamizaje.php,v 1.4 2006/12/19 21:00:15 jgomez Exp $
*/

/**
* ControlPrenatal_PHP
*
* Clase para procesar los datos del formulario mediante la operaciones de consulta ,captura y de insercion.
* del submodulo ControlPrenatal, se extiende la clase ControlPrenatal y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/
class Tamizaje
{

  /**
* Contiene toda la presentación en pantalla de la consulta.
*
* @var text
* @access public
*/
	var $consulta='';
	/**
* Contiene toda la presentación en pantalla del formulario de datos .
*
* @var text
* @access public
*/
	var $forma='';
	/**
* Contiene todo el manejo de validacion de los datos .
*
* @var text
* @access public
*/
	var $validacion='';
/**
* Contiene el dato de la evolucion en que se encuentra el paciente en el momento .
*
* @var integer
* @access public
*/
	var $evolucion='';
	/**
* Contiene el manejo de error del formulario .
*
* @var text
* @access public
*/
	var $frmError=array();
	/**
* Contiene un prefijo que se añade a los objetos para que no exista confusiones
* de nombres.
*
* @var string
* @access public
*/
	var $frmPrefijo='';
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
	function Tamizaje($evolucion)
	{
        $this->consulta='';
        $this->forma='';
        $this->validacion='';
		//$this->evolucion=9;
		$this->frmError=array();
		$this->frmPrefijo='';
        //$this->paciente=5;
		//$this->tipo='AS';
        $this->gestacion;
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
// 		'fecha'=>'',
// 		'autor'=>'JAIRO DUVAN DIAZ MARTINEZ',
// 		'descripcion_cambio' => '',
// 		'requiere_sql' => false,
// 		'requerimientos_adicionales' => '',
// 		'version_kernel' => '1.0'
// 		);
// 		return $informacion;
// 	}


	function GetConsulta()
	{
        $this->frmConsulta();
		    return $this->consulta;
	}

	function GetForma()
	{
	    $this->frmForma();
		  return $this->forma;
	}

    function GetValidacion()
	{
		return $this->validacion;
	}

	function SetPrefijo($pfj)
	{
	  $this->frmPrefijo=$pfj;
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
* Funcion que inserta los datos del control prenatal que se le hace a la paciente.
* y si esta en su misma evolucion, se pueden actualizar los datos.
* @return boolean
*/

function InsertDatos()
{
	    list($dbconn) = GetDBconn();

		  $bu="SELECT  gestacion_id from gestacion where estado =1 and paciente_id='$this->paciente'";
			$dato=$dbconn->Execute($bu);
		  $this->gestacion=$dato->fields[0];


		/*revisa si hay que actualizar o insertar los datos*/
		 list($conn) = GetDBconn();
     $bus="SELECT  count(evolucion_id) from hc_controles where evolucion_id='$this->evolucion'";
     $res=$conn->Execute($bus);
	   $contador=$res->fields[0];
   	/*aqui verificamos si hay que actualizar o insertar los datos*/

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
						 '$this->paciente',
             '$this->tipo',
						  $_REQUEST[peso],
              $_REQUEST[tenalta],
						  $_REQUEST[tenbaja],
              $_REQUEST[alturau],
              $_REQUEST[fcf],
						  $_REQUEST[movfetal],
						  $_REQUEST[cuellou],
						  $_REQUEST[edemas],
						  $_REQUEST[monife],
							$_REQUEST[semana],
						  $this->gestacion,
              $_REQUEST[presentacion],
						  $this->evolucion);";
//echo $sql;
//exit();
     }else

		  {
			   $sql="update  hc_controles
           set
					 paciente_id='$this->paciente',
				   tipo_id_paciente= '$this->tipo' ,
					 peso=$_REQUEST[peso],
					 talta=$_REQUEST[tenalta],
           tbaja= $_REQUEST[tenbaja],
					 alturauterina=$_REQUEST[alturau],
				 	 fcf=$_REQUEST[fcf],
					 movfetal=$_REQUEST[movfetal],
					 valorcuellouterino=$_REQUEST[cuellou],
					 edemas=$_REQUEST[edemas],
					 monitoreofetal=$_REQUEST[monife],
					 semanas=$_REQUEST[semana],
					 gestacion_id=$this->gestacion,
				   partos_presentacion_id=$_REQUEST[presentacion]
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
  $this->RegistrarSubmodulo($this->GetVersion());            
 return true;
}
}
?>
