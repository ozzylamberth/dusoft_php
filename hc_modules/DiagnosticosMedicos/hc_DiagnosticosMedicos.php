<?php

/**
* Submodulo de Diagnosticos Medicos.
*
* Submodulo para manejar los ingresos de Diagnosticos Medicos.
* @author Tizziano Perea Ocoro <tperea@ipsoft-sa.com>
* @version 1.0
* @package SIIS
* $Id: hc_DiagnosticosMedicos.php,v 1.5 2006/12/19 21:00:13 jgomez Exp $
*/


/**
* DiagnosticosMedicos
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de Diagnosticos Medicos.
*/

class DiagnosticosMedicos extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/


	function DiagnosticosMedicos()
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
* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/

	function GetEstado()
	{
		$pfj=$this->frmPrefijo;
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
		$profesional=$this->ReconocerProfesional();
		if($profesional==3 OR $profesional==4)
		{
			$this->GetConsulta();
		}
		else
		{
			$pfj=$this->frmPrefijo;
			if(empty($_REQUEST['accion'.$pfj]))
			{
				$this->frmForma();
			}
			else
			{
				if($_REQUEST['accion'.$pfj]=='Insertar_Diagnosticos')
				{
					if($this->InsertDatos()==true)
					{
						$this->frmForma();
					}
					else
					{
						$this->frmForma();
					}
				}
			}
		}
		return $this->salida;
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


	function PartirFecha($fecha)
	{
		$a=explode('-',$fecha);
		$b=explode(' ',$a[2]);
		$c=explode(':',$b[1]);
		$d=explode('.',$c[2]);
		return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
	}



	function ReconocerProfesional()
	{
		list($dbconn) = GetDBconn();
		$a=UserGetUID();
		if(!empty($a))
		{
		  $sql="SELECT b.tipo_profesional
				FROM profesionales_usuarios as a,
				profesionales as b
				WHERE a.usuario_id=".$a."
				and a.tipo_tercero_id=b.tipo_id_tercero and a.tercero_id=b.tercero_id;";
		}
		else
		{
			return false;
		}
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer profesional";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if(!$result->EOF)
			{
				return $result->fields[0];
			}
			else
			{
				return false;
			}
		}
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
		$datos=$this->Busqueda_Diagnosticos_Medicos();
		$diagnostico = $_REQUEST['descripciones'.$pfj];
		$porcentaje = 100;
		similar_text ($diagnostico,$datos['descripcion'],$porcentaje);
		if ($porcentaje != 100)
		{
			if(!empty($diagnostico))
			{
				list($dbconn) = GetDBconn();
				$sql="UPDATE hc_diagnosticos_medicos
					  SET sw_estado = '0'
					  WHERE ingreso = ".$this->ingreso."
					  AND evolucion_id = ".$this->evolucion.";";

				$resulta=$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al insertar los diagnosticos medicos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}

				$sql2="INSERT INTO hc_diagnosticos_medicos
									(descripcion,
									evolucion_id,
									ingreso,
									usuario_id,
									fecha_registro,
									sw_estado)
							VALUES('".$diagnostico."',
									".$this->evolucion.",
									".$this->ingreso.",
									".UserGetUID().",
									now(),
									'1');";
				$result=$dbconn->Execute($sql2);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al insertar los diagnosticos medicos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					$this->frmError['MensajeError']="Datos Guardados Satisfactoriamente.";
					$this->RegistrarSubmodulo($this->GetVersion());
          return true;
				}
			}
		}
		else
		{
			$this->frmError['MensajeError']="Las Cadenas son Iguales.";
			return true;
		}
		return true;
	}


	function Busqueda_Diagnosticos_Medicos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
		$sql = "SELECT A.descripcion, A.fecha_registro, B.nombre, B.usuario
				FROM hc_diagnosticos_medicos AS A, system_usuarios AS B
				WHERE A.ingreso =".$this->ingreso."
				AND B.usuario_id=A.usuario_id
				AND sw_estado = '1';";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta = $dbconn->Execute($sql);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;          
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al seleccionar los datos del almacenamiento";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
               while(!$resulta->EOF)
               {
                    $datosfila=$resulta->GetRowAssoc($ToUpper = false);
                    list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro']));//substr(,0,10);
                    list($ano,$mes,$dia) = explode("-",$fecha);//substr(,0,10);
                    list($hora,$min) = explode(":",$hora);//substr(,0,10);
                    $datosfila[hora]=$hora.":".$min;
                    $datos[$fecha][]=$datosfila;
                    $resulta->MoveNext();
               }
		}
		return $datos;
	}
}
?>
