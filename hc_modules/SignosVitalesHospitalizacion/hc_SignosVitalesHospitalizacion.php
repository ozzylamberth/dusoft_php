<?php

/**
* Submodulo de Signos Vitales Hospitalizacion.
*
* Submodulo para manejar los signos vitales de un paciente hospitalizado.
* @author Tizziano Perea Ocoro <t_perea@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_SignosVitalesHospitalizacion.php,v 1.9 2009/04/22 19:19:59 johanna Exp $
*/


/**
* SignosVitalesHospitalizacion
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de signos vitales hospitalizacion.
*/

class SignosVitalesHospitalizacion extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	var $limit;
	var $conteo;

	function SignosVitalesHospitalizacion()
	{
		$this->limit=5;
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
			if($_REQUEST['accion'.$pfj]=='ListarSignosVitales')
			{
    				$this-> frmForma();
			}

			if ($_REQUEST['accion'.$pfj]== 'BorrarSignoVital')
			{
				if($this->BorrarSignoVital()==true)
				{
					$this->frmForma();
				}
				else
				{
					$this->frmForma();
				}
			}

			if ($_REQUEST['accion'.$pfj]== 'InsertarSignosVitales')
			{
				if($this->InsertarSignosVitales()==true)
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


	function GetConsulta()
	{
          $this->frmConsulta();
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
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="SELECT count(*)
			   FROM hc_signos_vitales
			   WHERE ingreso = ".$this->ingreso.";";
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
	*		GetSignosVitalesSitios
	*
	*		Obtiene el sitios donde o modo como se tomaran los signos vitales.
	*
	*		@Author Tizziano Perea O.
	*		@access Public
	*		@return bool
	*/
	function GetSignosVitalesSitios($sitio='')
	{
		$pfj=$this->frmPrefijo;
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$sitios=array();

      	if(empty($sitio))
		{
			$query="SELECT *
					FROM hc_signos_vitales_sitios
					ORDER BY indice_orden";

		}
		else
		{
			$query="SELECT *
			FROM hc_signos_vitales_sitios
			WHERE sitio_id='$sitio'
			ORDER BY sitio_id";
		}
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_signos_vitales_sitios\"<br>";
               $this->mensajeDeError = $query;
               return false;
          }
          if(empty($sitio))
          {
               while ($data = $resultado->FetchRow())
               {
                    $sitios[]=$data;
               }
          }
          else
          {
               $data = $resultado->FetchRow();
               $sitios[]=$data;
          }
          return $sitios;
	}

	/**
	*		InsertarSignosVitales
	*
	*		Inserta los signos vitales de cada paciente hospitalizado en la HC
	*
	*		@Author Tizziano Perea O.
	*		@access Public
	*		@return bool
	*/
     function InsertarSignosVitales()
     {
          $pfj=$this->frmPrefijo;
          
          $vec=$this->GetSignosObligatorios();
          
          list($dbconn) = GetDBconn();

          $fechaHora = $_REQUEST['selectHora'.$pfj].":".$_REQUEST['selectMinutos'.$pfj];
          $fc= $_REQUEST['fc'.$pfj];
          $fr= $_REQUEST['fr'.$pfj];
          $pic=$_REQUEST['pic'.$pfj];
          $pvc=$_REQUEST['pvc'.$pfj];
          $peso=$_REQUEST['peso'.$pfj];
          $tpiel=$_REQUEST['tpiel'.$pfj];
          $ta_alta=$_REQUEST['taa'.$pfj];
          $ta_baja=$_REQUEST['tab'.$pfj];
          $servo=$_REQUEST['servo'.$pfj];
          $manual=$_REQUEST['manual'.$pfj];
          $sato=$_REQUEST['sato'.$pfj];
          $sitio=$_REQUEST['sitio'.$pfj];
          $observacion=$_REQUEST['observacion'.$pfj];
          $sistole= $ta_baja * 2;
          $diastole=$ta_alta;
          $media= (($sistole + $diastole)/3);
          $eva=$_REQUEST['eva'.$pfj];

          //VALIDACIONES
          
          if (strlen($observacion) > 256)
          {
               $this->frmError["MensajeError"] = "LA OBSERVACION SOLO PUEDE SER MENOR O IGUAL A 256 CARACTERES";
               $this->frmForma();
               return true;
          }

          if($sitio==-1 && empty($ta_baja) && empty($ta_alta) && empty($fc) && empty($fr) && empty($pic) && empty($sato) && empty($pvc) && empty($peso) && empty($tpiel) && empty($servo) && empty($eva) && empty($manual))
          {
               $this->frmError["MensajeError"] = "DEBE INGRESAR AL MENOS UN DATO";
               $this->frmForma();
               return true;
          }
          
          //luego valido que no existan registros a esa hora
          $query = "SELECT fecha
                    FROM hc_signos_vitales
                    WHERE ingreso=".$this->ingreso." AND
                    fecha = '$fechaHora'
                    ORDER BY fecha DESC";
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla \"hc_signos_vitales\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if(!$result->EOF)
               {
                    $this->frmError["MensajeError"] = "YA EXISTEN O SE REGISTRARON SIGNOS VITALES EN ESTA HORA ($fechaHora)";
                    $this->frmForma();
                    return true;
               }
          }

          if (empty($fc)) $fc=0;
          if (empty($fr)) $fr=0;
          if (empty($pvc)) $pvc=0;
          if (empty($pic)) $pic=0;
          if (empty($ta_alta)) $ta_alta=0;
          if (empty($ta_baja)) $ta_baja=0;
          if (empty($media)) $media=0;
          if (empty($tpiel)) $tpiel=0;
          if (empty($servo)) $servo=0;
          if (empty($manual)) $manual=0;
          if (empty($sato)) $sato=0;
          if (empty($peso)) $peso=0;
          if (empty($observacion)) $observacion='';
          if ($sitio==-1) $sitio = "NULL"; else $sitio = "'$sitio'";
          if (empty($eva)) $eva=0;

          
          if(empty($fc) AND !empty($vec['sw_obligatorio_fc']))
          {
                    $this->frmError["MensajeError"] = "EL CAMPO FRECUENCIA CARDIACA ES OBLIGATORIO";
               $this->frmForma();
               return true;
          }
          
          if (is_numeric($fc) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO FRECUENCIA CARDIACA NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
               $this->frmForma();
               return true;
          }
          $fc = floor ($fc);

          if(empty($fr) AND !empty($vec['sw_obligatorio_fr']))
          {
                    $this->frmError["MensajeError"] = "EL CAMPO FRECUENCIA RESPIRATORIA ES OBLIGATORIO";
               $this->frmForma();
               return true;
          }
          
          if (is_numeric($fr) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO FRECUENCIA RESPIRATORIA NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
               $this->frmForma();
               return true;
          }
          $fr = floor ($fr);
          
          if(empty($pvc) AND !empty($vec['sw_obligatorio_pvc']))
          {
                    $this->frmError["MensajeError"] = "EL CAMPO FRECUENCIA RESPIRATORIA ES OBLIGATORIO";
               $this->frmForma();
               return true;
          }
          
          if (is_numeric($pvc) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO PVC NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
               $this->frmForma();
               return true;
          }
          $pvc = floor ($pvc);
          
          if(empty($pic) AND !empty($vec['sw_obligatorio_presion_intracraneana']))
          {
                    $this->frmError["MensajeError"] = "EL CAMPO FRECUENCIA RESPIRATORIA ES OBLIGATORIO";
               $this->frmForma();
               return true;
          }
          
          if (is_numeric($pic) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO PIC NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
               $this->frmForma();
               return true;
          }
          
          if(empty($peso) AND !empty($vec['sw_obligatorio_peso']))
          {
               $this->frmError["MensajeError"] = "EL CAMPO PESO ES OBLIGATORIO";
               $this->frmForma();
               return true;
          }
          
          if (is_numeric($peso) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO PESO NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
               $this->frmForma();
               return true;
          }
          
          if(empty($tpiel) AND !empty($vec['sw_obligatorio_temp_piel']))
          {
               $this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA ES OBLIGATORIO";
               $this->frmForma();
               return true;
          }

          if (is_numeric($tpiel) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
               $this->frmForma();
               return true;
          }

          if($tpiel > 43)
          {
               $this->frmError["MensajeError"]="LA TEMPERATURA EXCEDE EL VALOR DEL RANGO NORMAL.";
               $this->frmForma();
               return true;
          }
          
          if(empty($manual) AND !empty($vec['sw_obligatorio_manual']))
          {
               $this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA MANUAL ES OBLIGATORIO";
               $this->frmForma();
               return true;
          }
          
          if (is_numeric($manual) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA MANUAL NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
               $this->frmForma();
               return true;
          }
          
          if(empty($servo) AND !empty($vec['sw_obligatorio_servo']))
          {
               $this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA DE INCUBADORA ES OBLIGATORIA";
               $this->frmForma();
               return true;
          }
          
          if (is_numeric($servo) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA DE INCUBADORA NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
               $this->frmForma();
               return true;
          }
          
          if(empty($sato) AND !empty($vec['sw_obligatorio_sato2']))
          {
               $this->frmError["MensajeError"] = "EL CAMPO EL CAMPO SAT 0 ES OBLIGATORIO";
               $this->frmForma();
               return true;
          }

          if (is_numeric($sato) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO SAT 0<sub>2</sub> NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
               $this->frmForma();
               return true;
          }

          if ($sato > '100')
          {
               $this->frmError["MensajeError"] = "EL CAMPO SATO DEBE SER MENOR O IGUAL AL 100%";
               $this->frmForma();
               return true;
          }
          $media = floor ($media);
          
          if(empty ($ta_alta) AND !empty($vec['sw_obligatorio_ta_alta']))
          {
               $this->frmError["MensajeError"] = "DEBE LLENAR TA - ALTA";
               $this->frmForma();
               return true;
          }
          
          if (is_numeric($ta_alta) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO TENSION ARTERIAL ALTA NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
               $this->frmForma();
               return true;
          }
          $ta_alta = floor ($ta_alta);
          
          if(empty ($ta_baja) AND !empty($vec['sw_obligatorio_ta_baja']))
          {
               $this->frmError["MensajeError"] = "DEBE LLENAR TA - BAJA";
               $this->frmForma();
               return true;
          }
          
          if (is_numeric($ta_baja) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO TENSION ARTERIAL BAJA NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
               $this->frmForma();
               return true;
          }
          $ta_baja = floor ($ta_baja);
          
          $restriccion = $ta_baja - $ta_alta;
          if($restriccion > 0)
          {
               $this->frmError["MensajeError"] = "LA T.A. SISTOLICA DEBE SER MAYOR A LA T.A. DIASTOLICA";
               $this->frmForma();
               return true;
          }
          
          if($sitio=="NULL" AND !empty($vec['sw_obligatorio_sitio_id']))
          {
               $this->frmError["MensajeError"] = "DEBE LLENAR SITIO";
               $this->frmForma();
               return true;
          }
          
          if(empty($eva) AND !empty($vec['sw_obligatorio_evaluacion_dolor']))
          {
               $this->frmError["MensajeError"] = "EL CAMPO ESCALA VISUAL ANALOGA ES OBLIGATORIO";
               $this->frmForma();
               return true;
          }

          $query="INSERT INTO hc_signos_vitales ( sitio_id,
                                                            fecha,
                                                            fc,
                                                            pvc,
                                                            ta_alta,
                                                            media,
                                                            temp_piel,
                                                            servo,
                                                            manual,
                                                            presion_intracraneana,
                                                            ingreso,
                                                            usuario_id,
                                                            peso,
                                                            ta_baja,
                                                            evolucion_id,
                                                            fecha_registro,
                                                            observacion,
                                                            sato2,
                                                            evaluacion_dolor,
                                                            fr)
                                                  VALUES ($sitio,
                                                            '$fechaHora',
                                                            $fc,
                                                            $pvc,
                                                            $ta_alta,
                                                            $media,
                                                            $tpiel,
                                                            $servo,
                                                            $manual,
                                                            $pic,
                                                            ".$this->ingreso.",
                                                            ".UserGetUID().",
                                                            $peso,
                                                            $ta_baja,
                                                            ".$this->evolucion.",
                                                            now(),
                                                            '$observacion',
                                                            $sato,
                                                            $eva,
                                                            $fr
                                                            );";
          //echo "<br>".$query;exit;
          $resultado = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
               $this->RegistrarSubmodulo($this->GetVersion());            
          return true;
     }
		
		
     function GetSignosObligatorios()
     {
          
          list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          
          $query="SELECT campo,sw_obligatorio
                              FROM hc_signos_vitales_obligatorios;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo SignosVitalesHospitalizacion - GetSignosObligatorios";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          while($data = $result->FetchRow())
          {
               $Datos[$data['campo']]= $data['campo'];
               $Datos['sw_obligatorio_'.$data['campo']]= $data['sw_obligatorio'];
          }
          $result->Close();
               
          return $Datos;
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
          $pfj=$this->frmPrefijo;
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

	/**
		*		BorrarSignoVital
		*
		*		Borra ultimo signo vital insertado
		*
		*		@Author Tizziano Perea O.
		*		@access Public
		*		@return bool
	*/
	function BorrarSignoVital()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql="DELETE FROM hc_signos_vitales
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

     /**
     *		GetAlarmaRangoControl
     *
     *		Verifica si el valor del control se encuentra dentrol del rango para ese control
     *
     *		@Author Rosa Maria Angel
     *		@access Public
     *		@return bool - string
     *		@param integer => control_id
     *		@param character => sexo del paciente
     *		@param integer => edad del paciente
     *		@param integer => valor a verificar
     */
     function GetAlarmaRangoControl($control,$sexo,$edad,$temp)
     {
          $pfj=$this->frmPrefijo;
          $query = "SELECT *
                    FROM hc_rangos_controles
                    WHERE control_id = $control AND
                    sexo = '".$sexo."' AND
                    ($edad BETWEEN edad_min AND edad_max AND
                    $temp BETWEEN rango_min AND rango_max)";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar obtener la fecha de nacimiento del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if(($result->EOF)){
                    return "Alarma";
               }
               else{
                    return "Normal";
               }
          }
          return true;
     }//GetAlarmaRangoControl

	/**
		*		ListarSignosVitales
		*
		*		Crea lista de los signos vitales tomados al paciente
		*
		*		@Author Tizziano Perea O.
		*		@access Public
		*		@return bool
		*/

	function ListarSignosVitales()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo'.$pfj]))
		{
			$query = "SELECT count(*)
					FROM hc_signos_vitales
					WHERE ingreso='".$this->ingreso."';";

			$resulta = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
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

			$query = "SELECT *
					FROM hc_signos_vitales
					WHERE ingreso='".$this->ingreso."'
					ORDER BY fecha DESC
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
			$vectorSignos[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $vectorSignos;
	}

     /**
     *		ListarSignosVitales
     *
     *		Crea lista de los signos vitales tomados al paciente
     *
     *		@Author Tizziano Perea O.
     *		@access Public
     *		@return bool
     */

	function ListarSignos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query = "SELECT *
				  FROM hc_signos_vitales
				  WHERE ingreso='".$this->ingreso."'
				  ORDER BY fecha DESC;";
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
			$vectorSig[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $vectorSig;
	}


	function Consulta_Triage()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query = "SELECT triage_id
				  FROM triages
				  WHERE ingreso='".$this->ingreso."';";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		list($triage) = $resulta->FetchRow();
		return $triage;
	}


}
?>