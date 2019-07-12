<?
/**
* Submodulo de Examenes Clinicos.
*
* Submodulo para manejar la reserva y/o cruzada de sangre.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_Examenes.php,v 1.3 2006/12/19 21:00:13 jgomez Exp $
*/


class Examenes extends hc_classModules
{
//clzc-dd-ok
	function Examenes()
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
// 		'autor'=>'CLAUDIA LILIANA ZUÑIGA CAÑON',
// 		'descripcion_cambio' => '',
// 		'requiere_sql' => false,
// 		'requerimientos_adicionales' => '',
// 		'version_kernel' => '1.0'
// 		);
// 		return $informacion;
// 	}


//clzc-dd-ok
	function GetConsulta()
	{
		$accion='accion'.$pfj;

		if(empty($_REQUEST[$accion]))
		{
			$this->frmConsulta();
		}
		else
		{
			if($_REQUEST[$accion]=='consultad')
			{
        $this->FormaDetalleExamenes($_REQUEST['lab_id'],$_REQUEST['examen'],
				$_REQUEST['fecha_lab'], $_REQUEST['laboratorio'], $_REQUEST['observacion_m'],
				$_REQUEST['observacion_b'], $_REQUEST['informacion']);
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


//clzc-dd-ok
	function GetForma()
	{
				$pfj=$this->frmPrefijo;
				if(empty($_REQUEST['accion'.$pfj]))
				{
					$this->frmForma();
				}
				else
				{
						if($_REQUEST['accion'.$pfj]=='forma')
						{
								$this->frmCrearFormaE($_REQUEST['id'],$_REQUEST['examen'],$_REQUEST['informacion']);
						}

						if($_REQUEST['accion'.$pfj]=='insertar')
						{
								$this->Insertar($_REQUEST['id']);
								$this->frmForma();
						}
				}
				return $this->salida;
	}



  //funcion que trae todos los titulos de los examenes
	//clzc-dd-ok
	function ConsultaExamen()
	{
		list($dbconnect) = GetDBconn();
		$query = "SELECT lab_examen_cargo_id,titulo_examen, informacion from lab_examenes_cargos";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$fact[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
	  return $fact;
  }


	//funcion que trae todos los componentes del examne para mostrarlos en la pantalla
		//clzc-dd-ok
	function ConsultaComponentesExamen($id)
	{
		list($dbconnect) = GetDBconn();
    $query = "SELECT  a.lab_plantilla_id,
		                  a.nombre_examen,
											a.unidades,
											b.otro_nombre_examen,
											b.indice_de_orden,
											c.sexo_id,
                      c.rango_min,
											c.rango_max,
											c.lab_examen_id,
											c.edad_min,
											c.edad_max,
											a.lab_examen_id,
											d.lab_examen_opcion_id,
											d.opcion
										  from
											     lab_examenes a,
                           lab_examenes_detalle b left join
													 lab_plantilla1 c on (b.lab_examen_id = c.lab_examen_id) left join
													 lab_plantilla2 as d on (b.lab_examen_id = d.lab_examen_id)
										 WHERE  b.lab_examen_cargo_id=".$id."
										 AND b.lab_examen_id=a.lab_examen_id order by b.indice_de_orden";

    $result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
		 $i=0;
			while (!$result->EOF)
			{
			$fact[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
		//print_r($fact);
		return $fact;
  }





//ok clzc-dd
function Insertar($id)
	{

		//esta funcion a penas la voy a hacert
	/*
		if($_REQUEST['fecha_reg']=='' || )
		{
									if($_REQUEST['descrip']==''){ $this->frmError["des"]=1; }
									if($_REQUEST['empresa']==-1){ $this->frmError["emp"]=1; }
									$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
									$this->FormaInsertNewPerfil($_REQUEST['empresa'],$_REQUEST['descrip']);
									return true;
		}*/

		list($dbconn) = GetDBconn();
    //realiza el id manual de la tabla
		$query="SELECT nextval('hc_examenes_numero_examen_seq')";
		$result=$dbconn->Execute($query);
		$lab_id=$result->fields[0];
    //fin de la operacion
    $fecha= $_REQUEST['fecha_lab'];
		$indice = $_REQUEST['items'];
		$ob_b =$_REQUEST['ob_b'];
		$ob_m =$_REQUEST['ob_m'];
    $lab =$_REQUEST['laboratorio'];
		$query="INSERT INTO hc_lab_examenes
						(lab_id, lab_examen_cargo_id, evolucion_id,fecha_lab,fecha_reg, observacion_b, observacion_m, laboratorio)
		  			 VALUES  (
					            $lab_id, $id, ".$this->evolucion.",'$fecha',now(), '$ob_b', '$ob_m', '$lab'
						         )";

    $resulta1=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		  {
				$this->error = "Error al insertar en system_perfiles";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
    else
		  { 
			   for ($i=0; $i< $indice; $i++)
           {

             $nom='nom'.$i;
						 $res='res'.$i;
						 $query="INSERT INTO hc_lab_examenes_detalles
					    (lab_examen_id, lab_id, resultado)
		  		     VALUES  (
							 '".$_REQUEST[$nom]."',
							    $lab_id,
							 '".$_REQUEST[$res]."'
					     )";

						 $resulta2=$dbconn->Execute($query);
		         if ($dbconn->ErrorNo() != 0)
		          {
				       $this->error = "Error al insertar en system_perfiles";
				       $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				       return false;
			        }
						 else

						   {
                $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
                $this->RegistrarSubmodulo($this->GetVersion());            
                return true;
							 }
            }
			}
	}


function ConsultaExamenesPaciente()
{
    list($dbconnect) = GetDBconn();
    $paciente = $this->paciente;
		$tipoid   = $this->tipoidpaciente;
    $query= "select a.ingreso,b.evolucion_id,c.lab_id,d.titulo_examen,d.informacion,
		         c.laboratorio,c.fecha_lab, c.observacion_b, c.observacion_m
             FROM ingresos a,hc_evoluciones b,hc_lab_examenes c,lab_examenes_cargos d
             WHERE tipo_id_paciente= '$tipoid' AND paciente_id= '$paciente'
                   AND a.ingreso=b.ingreso AND b.evolucion_id=c.evolucion_id
                   AND c.lab_examen_cargo_id=d.lab_examen_cargo_id order by c.fecha_lab  desc";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$fact[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
	  return $fact;
}

 function ConsultaDetalle($lab_id)
 {
		list($dbconnect) = GetDBconn();
//si ejecuto este query no me salen los rangos porque no estoy haciendo el join con las plantillas
//pero no me repite el mismo resultado en la prueba de embarazo
		 $query= "SELECT a.lab_examen_id,a.lab_id,a.resultado,a.lab_examen_id,
									b.lab_plantilla_id,b.nombre_examen,b.unidades,
									b.clasificacion_id FROM hc_lab_examenes_detalles a,lab_examenes b
 									WHERE lab_id='$lab_id' AND a.lab_examen_id=b.lab_examen_id";

//si ejecuto este llama los cargos pero me esta repitiendo dos veces el mismo resultado en la prueba de embarazo
//este paciente se tomo dos examenes de embarazo pero solo quiero el resultado de uno.
    echo $query1=   "SELECT a.lab_examen_id,a.lab_id,a.resultado,a.lab_examen_id,
              b.lab_plantilla_id,b.nombre_examen,b.unidades,b.clasificacion_id,c.rango_max,
							c.rango_min, c.sexo_id
							FROM lab_examenes b, hc_lab_examenes_detalles a left join lab_plantilla1 c on
							(a.lab_examen_id = c.lab_examen_id) left join lab_plantilla2 as d on
							(a.lab_examen_id = d.lab_examen_id)
							WHERE  lab_id='$lab_id' AND a.lab_examen_id=b.lab_examen_id";




		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la tabla hc_lab_examenes_detalles";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$fact[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
	  return $fact;

 }

 //funcion que me convierte la opcion escogida en numero por el valor en letras pos, neg, reac, no reac, etc
 function ConversionOpcion($resultado, $id)
 {
    list($dbconnect) = GetDBconn();

		$query= "SELECT opcion FROM lab_plantilla2
 									WHERE lab_examen_id='$id' AND lab_examen_opcion_id= '$resultado'";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la tabla hc_lab_examenes_detalles";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$fact[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
	  return $fact;
 }
}
?>






