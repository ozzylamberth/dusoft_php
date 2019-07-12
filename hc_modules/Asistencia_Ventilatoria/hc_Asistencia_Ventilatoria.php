<?php

/**
* Submodulo de Asistencia Ventilatoria.
*
* Submodulo para manejar las variables de Asistencia Ventilatoria los paciente UCI Y UCIN.
* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Asistencia_Ventilatoria.php,v 1.3 2006/12/19 21:00:13 jgomez Exp $
*/


/**
* Asistencia_Ventilatoria
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de Asistencia Ventilatoria.
*/

class Asistencia_Ventilatoria extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	var $limit;
	var $conteo;

	function Asistencia_Ventilatoria()
	{
		$this->limit=5;
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
			if($_REQUEST['accion'.$pfj]=='ListarAV')
			{
				$vectorAsistencia= $this->GetAsistenciaVentilatoria();
				$this-> frmForma();
			}

			if ($_REQUEST['accion'.$pfj]== 'BorrarAV')
			{
				if($this->BorrarAsistenciaVentilatoria()==true)
				{
					$this->frmForma();
				}
				else
				{
					$this->frmForma();
				}
			}

			if ($_REQUEST['accion'.$pfj]== 'InsertarAV')
			{
				if($this->InsertarAsistenciaVentilatoria()==true)
				{
					$this->frmForma();
				}
				else
				{
					$this->frmForma();
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

		/*
		*		BorrarAsistenciaVentilatoria
		*
		*
		*		@Author Tizziano Perea O.
		*		@access Public
		*/
		function BorrarAsistenciaVentilatoria()
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();
			$sql="DELETE FROM hc_asistencia_ventilatoria
				WHERE evolucion_id=".$this->evolucion."
				AND fecha_registro='".$_REQUEST['fechar'.$pfj]."';";
					$result = $dbconn->Execute($sql);
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			return true;
		}

		/*
		*		GetAsistenciaVentilatoriaModos
		*
		*
		*		@Author Arley Velásquez
		*		@access Public
		*/
		function GetAsistenciaVentilatoriaModos()
		{
			$pfj=$this->frmPrefijo;
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$modos=array();
			$FechaInicio = $this->datosPaciente[fecha_nacimiento];
			$FechaFin = date("Y-m-d");
			$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
			if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_pediatrica'))
			{
				$query="SELECT *
						FROM hc_asistencia_ventilatoria_modos
						WHERE sw_neonatos='1'
						ORDER BY indice_orden";
			}
			else
			{
				$query="SELECT *
						FROM hc_asistencia_ventilatoria_modos
						ORDER BY indice_orden";
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultado) {
				$this->error = "Error al consultar la tabla \"hc_asistencia_ventilatoria_modos\"<br>";
				$this->mensajeDeError = $query;
				return false;
			}
				while ($data = $resultado->FetchRow()) {
				$modos[]=$data;
			}
			return $modos;
		}


		/**
		*		GetAsistenciaVentilatoria
		*
		*		Obtine los datos de las asistencias del paciente para mostrarlas en pantalla
		*
		*		@Author Rosa Maria Angel D.
		*		@return bool - array
		*		@access Public
		*		@param integer => numero de ingreso del paciente
		*/
		function GetAsistenciaVentilatoria()
		{

			list($dbconn) = GetDBconn();
			$pfj=$this->frmPrefijo;
    		if(empty($_REQUEST['conteo'.$pfj]))
			{
				$query="SELECT A.*, C.*
						FROM (		(SELECT a.*
									FROM  hc_asistencia_ventilatoria a
									WHERE A.ingreso=".$this->ingreso."
									) AS A
									LEFT JOIN
									(SELECT f.concentracion_id, f.descripcion as descripcion_f
									FROM hc_tipos_concentracion_oxigenoterapia f
									) AS B
									ON A.f102_id = B.concentracion_id
							) AS A
									LEFT JOIN
									( SELECT s.*
										FROM hc_asistencia_ventilatoria_modos S
									) AS C
									ON A.modo_id = C.modo_id
						ORDER BY A.fecha DESC";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$this->conteo=$resulta->RecordCount();
			}

			else
			{
				$this->conteo=$_REQUEST['conteo'.$pfj];
			}
			if(!$_REQUEST['Of'.$pfj])
			{
				$Of='0';
			}
			else
			{
				$Of=$_REQUEST['Of'.$pfj];
				if($Of > $this->conteo)
				{
					$Of=0;
					$_REQUEST['Of'.$pfj]=0;
					$_REQUEST['paso1'.$pfj]=1;
				}
			}

				$query="SELECT A.*, C.*
						FROM (		(SELECT a.*
									FROM  hc_asistencia_ventilatoria a
									WHERE A.ingreso=".$this->ingreso."
									) AS A
									LEFT JOIN
									(SELECT f.concentracion_id, f.descripcion as descripcion_f
									FROM hc_tipos_concentracion_oxigenoterapia f
									) AS B
									ON A.f102_id = B.concentracion_id
							) AS A
									LEFT JOIN
									( SELECT s.*
										FROM hc_asistencia_ventilatoria_modos S
									) AS C
									ON A.modo_id = C.modo_id
						ORDER BY A.fecha DESC
						LIMIT ".$this->limit." OFFSET $Of;";
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
				$vectorAsistencia[$i]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
				$i++;
			}
   			if($this->conteo==='0')
			{
				$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
				return false;
			}
			return $vectorAsistencia;
		}//GetAsistenciaVentilatoria



		function GetAsistenciaVentilatoriaGeneral()
		{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();
			$query="SELECT A.*, C.*
					FROM (		(SELECT a.*
								FROM  hc_asistencia_ventilatoria a
								WHERE A.ingreso=".$this->ingreso."
								) AS A
								LEFT JOIN
								(SELECT f.concentracion_id, f.descripcion as descripcion_f
								FROM hc_tipos_concentracion_oxigenoterapia f
								) AS B
								ON A.f102_id = B.concentracion_id
						) AS A
						LEFT JOIN
						( SELECT s.*
							FROM hc_asistencia_ventilatoria_modos S
						) AS C
						ON A.modo_id = C.modo_id
						ORDER BY A.fecha DESC;";
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
				$vectorAsis[$i]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
				$i++;
			}
   			return $vectorAsis;
		}//GetAsistenciaVentilatoriaGeneral



		/*
		*		GetControlOxiConcentraciones
		*
		*		@Author Arley Velásquez
		*		@access Public
		*/
		function GetControlOxiConcentraciones($posicion_id,$valor)
		{
			$pfj=$this->frmPrefijo;
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$option="";
			switch ($valor)
			{
				case 0:
								$conc=array();
								$query = "SELECT * FROM hc_tipos_concentracion_oxigenoterapia WHERE concentracion_id='".$posicion_id."'";
								$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
								$resultado=$dbconn->Execute($query);
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								if (!$resultado) {
									$this->error = "Error, no se encuentra el registro en \"hc_tipos_concentracion_oxigenoterapia\" con la concentracion_id \"$posicion_id\"";
									$this->mensajeDeError = $query;
									return false;
								}
								while ($data = $resultado->FetchRow()) {
									$conc[]=$data;
								}
								return $conc;
				break;
				case 1:
								$query = "SELECT * FROM hc_tipos_concentracion_oxigenoterapia";
								$resultado=$dbconn->Execute($query);
									if (!$resultado) {
										$this->error = "Error, la tabla hc_tipos_concentracion_oxigenoterapia no contiene registros";
										$this->mensajeDeError = $query;
										return false;
									}
									while ($data = $resultado->FetchNextObject($toUpper=false))
									{
										if ($data->concentracion_id==$posicion_id)
											$option.="<option value='".$data->concentracion_id."' selected>".$data->descripcion."</option>\n";
										else
											$option.="<option value='".$data->concentracion_id."'>".$data->descripcion."</option>\n";
									}
								return $option;
				break;
			}
		}


		/**
		*		InsertarAsistenciaVentilatoria
		*
		*		Esta función lista a tdodos los pacientes de la estacion para poder insertar
		*		en un determinado horario el registro de la asistencia  ventilatoria
		*
		*		@Author Tizziano Perea O
		*		@access Public
		*		@return bool
		*/
		function InsertarAsistenciaVentilatoria()
		{
			$pfj=$this->frmPrefijo;
			$fechaHora = $_REQUEST['selectHora'.$pfj].":".$_REQUEST['selectMinutos'.$pfj];
			$modo_id = $_REQUEST['modo'.$pfj];
			$f102_id = $_REQUEST['f102'.$pfj];
			$fr_respiratoria = $_REQUEST['fr_respiratoria'.$pfj];
			$fr_ventilatoria = $_REQUEST['fr_ventilatoria'.$pfj];
			$expontanea = $_REQUEST['expontanea'.$pfj];
			$volumen = $_REQUEST['volumen'.$pfj];
			$sens = $_REQUEST['sens'.$pfj];
			$p_insp = $_REQUEST['p_insp'.$pfj];
			$ti = $_REQUEST['ti'.$pfj];
			$i_e = $_REQUEST['i_e'.$pfj];
			$peep = $_REQUEST['peep'.$pfj];
			$pip = $_REQUEST['pip'.$pfj];
			$paw = $_REQUEST['paw'.$pfj];
			$t_via_a = $_REQUEST['t_via_a'.$pfj];
			$pp = $_REQUEST['pp'.$pfj];
			$pm = $_REQUEST['pm'.$pfj];
			$etco2 = $_REQUEST['etco2'.$pfj];
			list($dbconn) = GetDBconn();

			//valido que por lo menos digitó un dato
			if(empty($fr_respiratoria) && empty($fr_ventilatoria) && empty($expontanea) && empty($t_via_a) && empty($volumen) && empty($sens) && empty($p_insp) && empty($ti) && empty($i_e) && empty($peep) && empty($pip) && empty($paw) && empty($pp) && empty($pm) && empty($etco2))
			{
				$this->frmError["MensajeError"] = "DEBE INGRESAR AL MENOS UN DATO";
				$this->frmForma();
				return true;
			}
			//luego valido que no existan registros a esa hora
			$query = "SELECT fecha
								FROM hc_asistencia_ventilatoria
								WHERE ingreso=".$this->ingreso." AND
									  fecha = '$fechaHora'
								ORDER BY fecha DESC";
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla \"hc_asistencia_ventilatoria\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if(!$result->EOF)
				{
					$this->frmError["MensajeError"] = "EN LA FECHA-HORA $fechaHora YA EXISTEN REGISTROS, ESPECIFIQUE UNA HORA DIFERENTE";
					$this->frmForma();
					return true;
				}
			}

			if ($f102_id == 0) $f102_id = "NULL"; else $f102_id = "'$f102_id'";
			if (empty($fr_respiratoria)) $fr_respiratoria=0;
			if (empty($fr_ventilatoria)) $fr_ventilatoria=0;
			if (empty($expontanea)) $expontanea=0;
			if (empty($volumen)) $volumen=0;
			if (empty($sens)) $sens=0;
			if (empty($p_insp)) $p_insp=0;
			if (empty($ti)) $ti=0;
			if (empty($i_e)) $i_e=0;
			if (empty($peep)) $peep=0;
			if (empty($pip)) $pip=0;
			if (empty($paw)) $paw=0;
			if (empty($t_via_a)) $t_via_a=0;
			if (empty($pp)) $pp = 0;
			if (empty($pm)) $pm = 0;
			if (empty($etco2)) $etco2 = 0;

			if (is_numeric($fr_respiratoria) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO FRECUENCIA RESPIRATORIA NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
				$this->frmForma();
				return true;
			}
			$fr_respiratoria = floor ($fr_respiratoria);

			if (is_numeric($fr_ventilatoria) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO FRECUENCIA VENTILATORIA NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
				$this->frmForma();
				return true;
			}

			if (is_numeric($expontanea) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO EXPONTANEA NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
				$this->frmForma();
				return true;
			}
			$expontanea = floor ($expontanea);

			if (is_numeric($etco2) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO ETC 0<sub>2</sub> NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
				$this->frmForma();
				return true;
			}

			if (is_numeric($ti) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO TI NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
				$this->frmForma();
				return true;
			}

			if (is_numeric($peep) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO PEEP NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
				$this->frmForma();
				return true;
			}
			$peep = floor ($peep);

			if (is_numeric($pip) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA PICO NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
				$this->frmForma();
				return true;
			}

			if (is_numeric($pp) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA MESETA NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
				$this->frmForma();
				return true;
			}

			if (is_numeric($pm) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA MEDIA NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
				$this->frmForma();
				return true;
			}

			if (is_numeric($paw) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO PAW NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
				$this->frmForma();
				return true;
			}

			if (is_numeric($t_via_a) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA VIA ANAL NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
				$this->frmForma();
				return true;
			}

			if (is_numeric($volumen) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO VOLUMEN NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
				$this->frmForma();
				return true;
			}
			$volumen = floor ($volumen);

			if (is_numeric($sens) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO SENS NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
				$this->frmForma();
				return true;
			}
			$sens = floor ($sens);

			if (is_numeric($p_insp) == false)
			{
				$this->frmError["MensajeError"] = "EL CAMPO P INSP NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
				$this->frmForma();
				return true;
			}
			$p_insp = floor ($p_insp);

			$query="INSERT INTO hc_asistencia_ventilatoria (fecha,
															modo_id,
															f102_id,
															fr_respiratoria,
															fr_ventilatoria,
															expontanea,
															volumen,
															sens,
															p_insp,
															ti,
															i_e,
															peep,
															pip,
															paw,
															t_via_a,
															pp,
															pm,
															etco2,
															ingreso,
															usuario_id,
															evolucion_id,
															fecha_registro)
													VALUES ('$fechaHora',
															'$modo_id',
															$f102_id,
															$fr_respiratoria,
															$fr_ventilatoria,
															$expontanea,
															$volumen,
															$sens,
															$p_insp,
															$ti,
															'$i_e',
															$peep,
															$pip,
															$paw,
															$t_via_a,
															$pp,
															$pm,
															$etco2,
															".$this->ingreso.",
															".UserGetUID().",
															".$this->evolucion.",
															'$fechaHora')";
			//echo "<br><br>".$query."<br>";
			$resultado = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar ingresar la asistencia ventilatoria.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			$this->RegistrarSubmodulo($this->GetVersion());
      return true;
		}



		/*		GetDatosUsuarioSistema
		*
		*		Obtiene el nombre de usuario del sistema
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*		@param integer => usuario_id
		*/
		function GetDatosUsuarioSistema($usuario)
		{
			$query = "SELECT usuario,
					nombre
					FROM system_usuarios
					WHERE usuario_id = $usuario";
			//echo "<br>$query";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar obtener los datos del usuario.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					return "ShowMensaje";
				}
				else
				{
					while ($data = $result->FetchRow()){
						$DatosUser[] = $data;
					}
					return $DatosUser;
				}
			}
		}/// GetDatosUsuarioSistema
}
?>
