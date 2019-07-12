<?php

/**
* Submodulo de Finalidad.
*
* Submodulo para manejar la finalidad de la atención prestada a un paciente en una evolución (rips)
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_Finalidad.php,v 1.5 2006/12/19 21:00:13 jgomez Exp $
*/


/**
* Finalidad
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de finalidad.
*/

class Finalidad extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function Finalidad()
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
		$pfj=$this->frmPrefijo;
	  	list($dbconn) = GetDBconn();

          $query="SELECT count(*)
               FROM hc_finalidad AS A,
                    hc_evoluciones AS C
               WHERE A.evolucion_id = C.evolucion_id
               AND C.ingreso = ".$this->ingreso.";";

          $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error="ERROR EN LA CONSULTA";
			$this->mensajeDeError="SQL : ".$query;
			return false;
		}
		list($existe)=$result->FetchRow();
		$result->Close();

		if($existe)
		{
			return true;
		}
		else
		{
			return false;
		}
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
          $query="select tipo_finalidad_id, sw_cobro_citas_nopyp from hc_finalidad where evolucion_id='$this->evolucion';";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               return false;
          }
          else
          {
          $sw_nopyp=$result->fields[1];
          $finalidad="finalidad".$pfj;

          if($result->RecordCount()==0)
          {
               $sql="";
               if(empty($_REQUEST['no_es_pyp']))
               {	if (empty ($_REQUEST[$finalidad]))
                    {
                         $this->frmError["MensajeError"] = "POR FAVOR, USTED DEBE ESCOGER UNA FINALIDAD";
                         $this->frmForma();
                         return true;
                    }
                    $sql="insert into hc_finalidad (tipo_finalidad_id,evolucion_id) values('".$_REQUEST[$finalidad]."','$this->evolucion')";
               }
               else
               {
                    if (empty ($_REQUEST[$finalidad]))
                    {
                         $this->frmError["MensajeError"] = "POR FAVOR, USTED DEBE ESCOGER UNA FINALIDAD";
                         $this->frmForma();
                         return true;
                    }
                    $sql="insert into hc_finalidad (tipo_finalidad_id,evolucion_id,sw_cobro_citas_nopyp) values('".$_REQUEST[$finalidad]."','$this->evolucion','1')";
               }
               // Reportar errores para depuracion.
               error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
               if(!$dbconn->Execute($sql))
               {
                    $error=$dbconn->ErrorMsg();
                    echo "$error";
                    return false;
               }
          }
          else
          {
               if(empty($_REQUEST['no_es_pyp']))
               {
                    if (empty ($_REQUEST[$finalidad]))
                    {
                         $this->frmError["MensajeError"] = "POR FAVOR, USTED DEBE ESCOGER UNA FINALIDAD";
                         $this->frmForma();
                         return true;
                    }
                    $sql="update hc_finalidad set tipo_finalidad_id='".$_REQUEST[$finalidad]."', sw_cobro_citas_nopyp='0' where evolucion_id='$this->evolucion';";
               }
               else
               {
                    if (empty ($_REQUEST[$finalidad]))
                    {
                         $this->frmError["MensajeError"] = "POR FAVOR, USTED DEBE ESCOGER UNA FINALIDAD";
                         $this->frmForma();
                         return true;
                    }
                    $sql="update hc_finalidad set tipo_finalidad_id='".$_REQUEST[$finalidad]."', sw_cobro_citas_nopyp='1' where evolucion_id='$this->evolucion';";
               }
               // Reportar errores para depuracion.
               error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
               if(!$dbconn->Execute($sql))
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    echo "$error";
                    return false;
               }
          }
          $sql="delete from hc_finalidad_detalle where evolucion_id=$this->evolucion;";
          $result=$dbconn->Execute($sql);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          foreach($_REQUEST as $k=>$v)
          {
               if(substr_count ($k,'otros'.$_REQUEST[$finalidad])==1)
               {
                    $a=explode(',',$v);
                    $sql="insert into hc_finalidad_detalle(evolucion_id, tipo_finalidad_detalle, tipo_finalidad_id) values ($this->evolucion,".$a[1].",'".$a[0]."');";
                    $result=$dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
                    }
               }
          }
          $this->frmError['MensajeError']="Datos Guardados Satisfactoriamente";
          $this->RegistrarSubmodulo($this->GetVersion());
          return true;
          }
     }



	function ConsultaFinalidad()
	{
		list($dbconn) = GetDBconn();
		$edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->datosEvolucion['fecha']);
		$query = "SELECT detalle,a.tipo_finalidad_id,b.evolucion_id, 
          		case when (a.sexo_id='".$this->datosPaciente['sexo_id']."' 
                    and a.gestacion is null) 
                    then 1 when (a.sexo_id='".$this->datosPaciente['sexo_id']."' 
                    and a.gestacion<=(select count(*) 
                    from gestacion where tipo_id_paciente='".$this->datosPaciente['tipo_id_paciente']."' 
                    and paciente_id='".$this->datosPaciente['paciente_id']."' 
                    and estado='1')) 
                    then 1 when (a.edad_max<=0 and a.edad_min>=0) 
                    then 1 else 0 end as riesgo, b.sw_cobro_citas_nopyp 
                    FROM hc_tipos_finalidad as a 
                    left join hc_finalidad as b on (a.tipo_finalidad_id=b.tipo_finalidad_id and b.evolucion_id=".$this->evolucion.") 
                    where (a.sexo_id='".$this->datosPaciente['sexo_id']."' or a.sexo_id is null) 
                    order by tipo_finalidad_id asc;";
		
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
				$finali[0][$i]=$result->fields[0];
				$finali[1][$i]=$result->fields[1];
				$finali[2][$i]=$result->fields[2];
				$finali[3][$i]=$result->fields[3];
				$finali[4][$i]=$result->fields[4];
				$result->MoveNext();
				$i++;
			}
		}
		return $finali;
	}


	function ConsultaFinalidadDetalle($tipo)
	{
		list($dbconn) = GetDBconn();
		$query = "select a.tipo_finalidad_detalle, a.descripcion, b.evolucion_id from hc_tipos_finalidad_detalle as a left join hc_finalidad_detalle as b on(a.tipo_finalidad_id=b.tipo_finalidad_id and a.tipo_finalidad_detalle=b.tipo_finalidad_detalle) where a.tipo_finalidad_id='".$tipo."' order by a.tipo_finalidad_detalle;";
		
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
				$datos[$result->fields[0]]=$result->GetRowAssoc(false);
				$result->MoveNext();
			}
		}
		return $datos;
	}




	function FinalidadConsulta()
	{
		list($dbconn) = GetDBconn();
          $query = "SELECT detalle,a.tipo_finalidad_id,b.evolucion_id FROM hc_tipos_finalidad as a join hc_finalidad as b on (a.tipo_finalidad_id=b.tipo_finalidad_id and b.evolucion_id=".$this->evolucion.") order by tipo_finalidad_id desc;";
          $result = $dbconn->Execute($query);
          $i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $result;
	}

}

?>
