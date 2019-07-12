<?php

/**
* Submodulo de Signos Vitales.
*
* Submodulo para manejar los signos vitales de un paciente en una evolución.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_SignosVitales.php,v 1.6 2006/12/19 21:00:15 jgomez Exp $
*/


/**
* SignosVitales
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de signos vitales.
*/

class SignosVitales extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function SignosVitales()
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
* Esta función retorna los datos para la impresión que se realizara en el archivo PDF.
*
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
		$sql="";
		$sql="select signos_vitales_consulta_id from hc_signos_vitales_consultas where evolucion_id=".$this->evolucion."";
		list($dbconn) = GetDBconn();
		$result=$dbconn->Execute($sql);
		$fc='fc'.$pfj;
		$temperatura='temperatura'.$pfj;
		$peso='peso'.$pfj;
		$taalta='taalta'.$pfj;
		$tabaja='tabaja'.$pfj;
		$fr='fr'.$pfj;
		$talla='talla'.$pfj;
		if($_REQUEST[$fc]==='' or !is_numeric($_REQUEST[$fc]))
		{
			if(!is_numeric($_REQUEST[$fc]) and !($_REQUEST[$fc]===''))
			{
				$this->frmError['MensajeError']="Se Escribieron letras en FC";
				$_REQUEST[$fc]='';
				return true;
			}
			$_REQUEST[$fc]='NULL';
		}
		if($_REQUEST[$temperatura]==='' or !is_numeric($_REQUEST[$temperatura]))
		{
			if(!is_numeric($_REQUEST[$temperatura]) and !($_REQUEST[$temperatura]===''))
			{
				$this->frmError['MensajeError']="Se Escribieron letras en Temperatura";
				$_REQUEST[$temperatura]='';
				return true;
			}
			$_REQUEST[$temperatura]='NULL';
		}

		if($_REQUEST[$temperatura] > 43)
		{
			$this->frmError['MensajeError']="La Temperatura excede el valor.";
			$_REQUEST[$temperatura]='';
			return true;
		}

		if($_REQUEST[$peso]==='' or !is_numeric($_REQUEST[$peso]))
		{
			if(!is_numeric($_REQUEST[$peso]) and !($_REQUEST[$peso]===''))
			{
				$this->frmError['MensajeError']="Se Escribieron letras en Peso";
				$_REQUEST[$peso]='';
				return true;
			}
			$_REQUEST[$peso]='NULL';
		}
		
          if($_REQUEST[$taalta]==='' or $_REQUEST[$tabaja]===''  or (!is_numeric($_REQUEST[$taalta]) or !is_numeric($_REQUEST[$tabaja])))
		{
			if((!is_numeric($_REQUEST[$taalta]) or !is_numeric($_REQUEST[$tabaja])) and (!($_REQUEST[$taalta]==='') or !($_REQUEST[$tabaja]==='')))
			{
				$this->frmError['MensajeError']="Se Escribieron letras en Tensión Arterial";
				return true;
			}
			$_REQUEST[$taalta]='NULL';
			$_REQUEST[$tabaja]='NULL';
		}
		
          if($_REQUEST[$fr]==='' or !is_numeric($_REQUEST[$fr]))
		{
			if(!is_numeric($_REQUEST[$fr]) and !($_REQUEST[$fr]===''))
			{
				$this->frmError['MensajeError']="Se Escribieron letras en FR";
				$_REQUEST[$fr]='';
				return true;
			}
			$_REQUEST[$fr]='NULL';
		}
		
          if(empty($_REQUEST[$talla]) or !is_numeric($_REQUEST[$talla]))
		{
			if(!is_numeric($_REQUEST[$talla]) and !($_REQUEST[$talla]===''))
			{
				$this->frmError['MensajeError']="Se Escribieron letras en Talla";
				return true;
			}
			$_REQUEST[$talla]='NULL';
		}
		
          if($_REQUEST[$talla]=='NULL' and $_REQUEST[$fr]=='NULL' and $_REQUEST[$tabaja]=='NULL' and $_REQUEST[$taalta]=='NULL' and $_REQUEST[$peso]=='NULL' and $_REQUEST[$temperatura]=='NULL' and $_REQUEST[$fc]=='NULL')
		{
			$this->frmError['MensajeError']="Debe Ingresar Datos Para la Inserción";
			return true;
		}
		if($result->RecordCount()==0)
		{
               $sql="insert into hc_signos_vitales_consultas (fc,temperatura,peso,taalta,tabaja,fr,talla,evolucion_id,fecha_registro)
               values(".$_REQUEST[$fc].",".$_REQUEST[$temperatura].",".$_REQUEST[$peso]."
               ,".$_REQUEST[$taalta].",".$_REQUEST[$tabaja]."
               ,".$_REQUEST[$fr].",".$_REQUEST[$talla].",'$this->evolucion',now())";
                    // Reportar errores para depuracion.
               error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
               list($dbconn) = GetDBconn();
               if(!$dbconn->Execute($sql))
               {
                    $dbconn->ErrorMsg();
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               else
               {
                    $this->frmError['MensajeError']="Datos Guardados Satisfactoriamente";
               }
		}
		else
		{
               $sql="update hc_signos_vitales_consultas set fc=".$_REQUEST[$fc].", temperatura=".$_REQUEST[$temperatura].", peso=".$_REQUEST[$peso].", taalta=".$_REQUEST[$taalta].", tabaja=".$_REQUEST[$tabaja].", fr=".$_REQUEST[$fr].", talla=".$_REQUEST[$talla].", fecha_registro = now() where evolucion_id='$this->evolucion';";
			list($dbconn) = GetDBconn();
               if(!$dbconn->Execute($sql))
               {
                    $dbconn->ErrorMsg();
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               else
               {
                    $this->frmError['MensajeError']="Datos Guardados Satisfactoriamente";
               }
		}
          
          /*INSERCION O ACTUALIZACION DE LOS VALORES DE LOS TIPOS DE METRICAS
            TALES COMO EL PESO O LA TALLA*/
            
          $query_metricas ="SELECT COUNT(*)
          			   FROM pacientes_metricas
                            WHERE paciente_id = '".$this->paciente."'
                            AND tipo_id_paciente = '".$this->tipoidpaciente."';";
          $resultado = $dbconn->Execute($query_metricas);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta en hc_solicitudes_suministros_estacion_detalle";
               $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          list($metricas) = $resultado->FetchRow();
          
          if($metricas < 1)
          {
          	for($i=0; $i<2; $i++)
               {
               	if($i == 0)
                    {
                    	$valor_metrica = $_REQUEST[$peso];
                         $tipo_metrica = "peso";
                         
                    }elseif($i == 1)
                    {
                    	$valor_metrica = $_REQUEST[$talla];
                         $tipo_metrica = "talla";
                    }
                    
                    $queryIM = "INSERT INTO pacientes_metricas  (paciente_id,
                                                            	tipo_id_paciente,
                                                            	tipo_metrica_id,
                                                            	valor_metrica,
                                                            	fecha_registro,
                                                            	sw_calculada,
                                                            	usuario_id)
                                                       VALUES   ('".$this->paciente."',
                                                       		'".$this->tipoidpaciente."',
                                                                 '$tipo_metrica',
                                                                 $valor_metrica,
                                                                 now(),
                                                                 '0',
                                                                 ".$this->usuario_id.");";
                    $resultado = $dbconn->Execute($queryIM);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al actualizar datos en la tabla pacientes_metricas";
                         $this->mensajeDeError = "Error al actualizar datos en la tabla pacientes_metricas";
                         return false;
                    }
               }
          }
          else
          {
          	for($i=0; $i<2; $i++)
               {
               	if($i == 0)
                    {
                    	$valor_metrica = $_REQUEST[$peso];
                         $tipo_metrica = "peso";
                         
                    }elseif($i == 1)
                    {
                    	$valor_metrica = $_REQUEST[$talla];
                         $tipo_metrica = "talla";
                    }
                    
                    $queryIM = "UPDATE pacientes_metricas 
                    		  SET valor_metrica = $valor_metrica, usuario_id = ".$this->usuario_id."
                    		  WHERE paciente_id = '".$this->paciente."'
                                AND tipo_id_paciente = '".$this->tipoidpaciente."'
                                AND tipo_metrica_id = '$tipo_metrica';";
                    
                    $resultado = $dbconn->Execute($queryIM);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al actualizar datos en la tabla pacientes_metricas";
                         $this->mensajeDeError = "Error al actualizar datos en la tabla pacientes_metricas";
                         return false;
                    }
               }
          }
           $this->RegistrarSubmodulo($this->GetVersion());            
          return true;
	}

	
     function DatosSignos()
	{
     	$sql="select taalta,tabaja,fc,temperatura,fr,peso,talla,fecha_registro from hc_signos_vitales_consultas where evolucion_id=".$this->evolucion.";";
		list($dbconn) = GetDBconn();
     	$result=$dbconn->Execute($sql);
		return $result;
	}

	
     function BusquedaDatosSignos()
	{
		list($dbconn) = GetDBconn();
		$query = "select taalta,tabaja,fc,temperatura,fr,peso,talla,fecha_registro from hc_signos_vitales_consultas where evolucion_id=".$this->evolucion.";";
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
				$dato[0][$i]=$result->fields[0];
				$dato[1][$i]=$result->fields[1];
				$dato[2][$i]=$result->fields[2];
				$dato[3][$i]=$result->fields[3];
				$dato[4][$i]=$result->fields[4];
				$dato[5][$i]=$result->fields[5];
				$dato[6][$i]=$result->fields[6];
                    $dato[7][$i]=$result->fields[7];
				$result->MoveNext();
				$i++;
			}
		}
		return $dato;
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
