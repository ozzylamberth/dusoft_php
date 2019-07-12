<?php

/**
* Submodulo Control Neurologico.
*
* Submodulo para manejar el sistema neurologico de pacientes hospitalizados.
* @author Tizziano Perea O. <tperea@ipsoft-sa.com>
* @version 1.0
* @package SIIS
* $Id: hc_Control_Neurologico.php,v 1.3 2006/12/19 21:00:13 jgomez Exp $
*/


/**
* Control_Neurologico
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de motivo consulta.
*/

class Control_Neurologico extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	var $limit;
	var $conteo;

	function Control_Neurologico()
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
		if($this->tipo_profesional == 5)
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
				if($_REQUEST['accion'.$pfj]=='Listar_ControlesNeurologicos')
				{
					if($this->Listar_ControlesNeurologicos()==true)
					{
						$this->frmForma();
					}
					else
					{
						$this->frmForma();
					}
				}

				if ($_REQUEST['accion'.$pfj]== 'BorrarControlNeuro')
				{
					if($this->Borrar_ControlNeuro()==true)
					{
						$this->frmForma();
					}
					else
					{
						$this->frmForma();
					}
				}

				if ($_REQUEST['accion'.$pfj]== 'Insertar_ControlesNeurologicos')
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

	/*function PartirFecha($fecha)
	{
		$a=explode('-',$fecha);
		$b=explode(' ',$a[2]);
		$c=explode(':',$b[1]);
		$d=explode('.',$c[2]);
		return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
	}*/


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
		*		GetTallasPupilas
		*
		*		Obtiene las diferentes tipos de talla de pupilas
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*		@param array,
		*		@param array
		*/
		function GetTallasPupilas()
		{
			$pfj=$this->frmPrefijo;
			$query = "SELECT talla_pupila_id,descripcion
					  FROM hc_tipos_talla_pupila";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar las tallas de pupilas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					$mensaje = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_talla_pupila'";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmControlNeurologico',array("datosPaciente"=>$datosPaciente,"estacion"=>$estacion));
					$boton = "REGRESAR";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{
					while ($data = $result->FetchRow()){
						$Tallas[] = $data;
					}
				}
				return $Tallas;
			}
		}//GetTallasPupilas


		/**
		*		GetReaccionPupilas
		*
		*		Obtiene los tipos de reaccion de pupila
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*		@param array,
		*		@param array
		*/
		function GetReaccionPupilas()
		{
			$pfj=$this->frmPrefijo;
			$query = "SELECT reaccion_pupila_id, descripcion
								FROM hc_tipos_reaccion_pupila";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar las tallas de pupilas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					$mensaje = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_reaccion_pupila'";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmControlNeurologico',array("datosPaciente"=>$datosPaciente,"estacion"=>$estacion));
					$boton = "REGRESAR";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{
					while ($data = $result->FetchRow()){
						$Reaccion[] = $data;
					}
				}
				return $Reaccion;
			}
		}//GetReaccionPupilas


		/**
		*		GetNivelesConciencia
		*
		*		Obtiene los tipos de niveles de consciencia
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*		@param array,
		*		@param array
		*/
		function GetNivelesConciencia()
		{
			$pfj=$this->frmPrefijo;
			$query = "SELECT nivel_consciencia_id, descripcion
								FROM hc_tipos_nivel_consciencia";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla 'hc_tipos_nivel_consciencia'.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					$mensaje = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_nivel_consciencia'";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmControlNeurologico',array("datosPaciente"=>$datosPaciente,"estacion"=>$estacion));
					$boton = "REGRESAR";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{
					while ($data = $result->FetchRow()){
						$Nivel_Conciencia[] = $data;
					}
				}
				return $Nivel_Conciencia;
			}
		}//GetNIvelesConciencia


		/**
		*		GetTiposFuerza
		*
		*		Obtiene los tipos de fuerza
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*		@param array,
		*		@param array
		*/
		function GetTiposFuerza()
		{
			$pfj=$this->frmPrefijo;
			$query = "SELECT fuerza_id, descripcion
								FROM hc_tipos_fuerza";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla 'hc_tipos_fuerza'.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					$mensaje = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_fuerza'";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmControlNeurologico',array("datosPaciente"=>$datosPaciente,"estacion"=>$estacion));
					$boton = "REGRESAR";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{
					while ($data = $result->FetchRow()){
						$TiposFuerza[] = $data;
					}
				}
				return $TiposFuerza;
			}
		}//fin TiposFuerza


		/**
		*		GetTipoAperturaOcular
		*
		*		Obtiene los tipos de apertura ocular
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*		@param array,
		*		@param array
		*/
		function GetTipoAperturaOcular()
		{
			$pfj=$this->frmPrefijo;
			$query = "SELECT apertura_ocular_id, descripcion
					  FROM hc_tipos_apertura_ocular
					  ORDER BY apertura_ocular_id ASC";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla 'hc_tipos_apertura_ocular'.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					$mensaje = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_apertura_ocular'";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmControlNeurologico',array("datosPaciente"=>$datosPaciente,"estacion"=>$estacion));
					$boton = "REGRESAR";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{
					while ($data = $result->FetchRow()){
						$TipoAperturaOcular[] = $data;
					}
				}
				return $TipoAperturaOcular;
			}
		}//fin GetTipoAperturaOcular


		/**
		*		GetRespuestaVerbal
		*
		*		Obtiene los direfentes tipos de respuesta verbal
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*		@param array,
		*		@param array
		*/
		function GetRespuestaVerbal()
		{
			$pfj=$this->frmPrefijo;
			$FechaInicio = $this->datosPaciente[fecha_nacimiento];
			$FechaFin = date("Y-m-d");
			$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
			if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_lactante'))
			{
				$query = "SELECT respuesta_verbal_id, descripcion_lactante
						  FROM hc_tipos_respuesta_verbal
						  ORDER BY respuesta_verbal_id ASC";
			}
			else
			{
				$query = "SELECT respuesta_verbal_id, descripcion
						  FROM hc_tipos_respuesta_verbal
						  ORDER BY respuesta_verbal_id ASC";
			}
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla 'hc_tipos_respuesta_verbal'.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					$mensaje = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_respuesta_verbal'";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmControlNeurologico',array("datosPaciente"=>$datosPaciente,"estacion"=>$estacion));
					$boton = "REGRESAR";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{
					while ($data = $result->FetchRow())
					{
						$RespuestaVerbal[] = $data;
					}
				}
				return $RespuestaVerbal;
			}
		}//fin GetRespuestaVerbal


		/**
		*		GetRespuestaMotora
		*
		*		Selecciona los tipos de respuesta motora
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool-array-string
		*		@param array,
		*		@param array
		*/
		function GetRespuestaMotora()
		{
			$pfj=$this->frmPrefijo;
			$FechaInicio = $this->datosPaciente[fecha_nacimiento];
			$FechaFin = date("Y-m-d");
			$edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
			if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_lactante'))
			{
				$query = "SELECT respuesta_motora_id, descripcion_lactante
						 FROM hc_tipos_respuesta_motora
						 ORDER BY respuesta_motora_id ASC";
			}
			else
			{
				$query = "SELECT respuesta_motora_id, descripcion
						 FROM hc_tipos_respuesta_motora
						 ORDER BY respuesta_motora_id ASC";
			}
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla 'hc_tipos_respuesta_motora'.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if($result->EOF){
					$mensaje = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_respuesta_motora'";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmControlNeurologico',array("datosPaciente"=>$datosPaciente,"estacion"=>$estacion));
					$boton = "REGRESAR";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{
					while ($data = $result->FetchRow())
					{
						$RespuestaMotora[] = $data;
					}
				}
				return $RespuestaMotora;
			}
		}//fin GetRespuestaMotora


/**
* Esta función inserta los datos del submodulo.
*
* @access private
* @return boolean Informa si lo logro o no.
*/
	function InsertDatos()
	{
		$pfj=$this->frmPrefijo;
		$Tiempo = $_REQUEST['selectHora'.$pfj].":".$_REQUEST['selectMinutos'.$pfj];
		$TallaPupilaI = $_REQUEST['pupilaI'.$pfj];
		$TallaPupilaD = $_REQUEST['pupilaD'.$pfj];
		$ReaccionPupilaI = $_REQUEST['reaccionI'.$pfj];
		$ReaccionPupilaD = $_REQUEST['reaccionD'.$pfj];
		$Niveles_Conciencia = $_REQUEST['orientado'.$pfj];
		$Brazo_izq = $_REQUEST['braizq'.$pfj];
		$Brazo_der = $_REQUEST['brader'.$pfj];
		$Pierna_izq = $_REQUEST['pierizq'.$pfj];
		$Pierna_der = $_REQUEST['pierder'.$pfj];
		$Apertura_Ocular = $_REQUEST['ao'.$pfj];
		$Respuesta_Verbal = $_REQUEST['rv'.$pfj];
		$Respuesta_Motora = $_REQUEST['rm'.$pfj];

		if (empty($TallaPupilaI) && empty($TallaPupilaD) && empty($Niveles_Conciencia) && empty($Apertura_Ocular) && empty($Respuesta_Verbal) && empty($Respuesta_Motora))
		{
			$this->frmError["MensajeError"] = "DEBE INGRESAR AL MENOS UN DATO";
			$this->frmForma();
			return true;
		}

		//luego valido que no existan registros a esa hora
		list($dbconn) = GetDBconn();
		$query = "SELECT fecha
				  FROM hc_controles_neurologia
				  WHERE ingreso=".$this->ingreso." AND
				  fecha = '$Tiempo'
				  ORDER BY fecha DESC;";
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
				$this->frmError["MensajeError"] = "YA SE REGISTRARON CONTROLES NEUROLOGICOS EN ESTA HORA ($Tiempo)";
				$this->frmForma();
				return true;
			}
		}

		if (empty($TallaPupilaI)) $TallaPupilaI='NULL';
		if (empty($TallaPupilaD)) $TallaPupilaD='NULL';
		if (empty($Niveles_Conciencia)) $Niveles_Conciencia='NULL';
		if (empty($Apertura_Ocular)) $Apertura_Ocular='NULL';
		if (empty($Respuesta_Verbal)) $Respuesta_Verbal='NULL';
		if (empty($Respuesta_Motora)) $Respuesta_Motora='NULL';

		if ($Apertura_Ocular == 'NULL' && $Respuesta_Verbal != 'NULL' && $Respuesta_Motora != 'NULL')
		{
			$this->frmError["MensajeError"] = "DEBE COMPLETAR DEBIDAMENTE LA ESCALA DE GLASGOW";
			$this->frmForma();
			return true;
		}

		if ($Apertura_Ocular != 'NULL' && $Respuesta_Verbal == 'NULL' && $Respuesta_Motora != 'NULL')
		{
			$this->frmError["MensajeError"] = "DEBE COMPLETAR DEBIDAMENTE LA ESCALA DE GLASGOW";
			$this->frmForma();
			return true;
		}

		if ($Apertura_Ocular != 'NULL' && $Respuesta_Verbal != 'NULL' && $Respuesta_Motora == 'NULL')
		{
			$this->frmError["MensajeError"] = "DEBE COMPLETAR DEBIDAMENTE LA ESCALA DE GLASGOW";
			$this->frmForma();
			return true;
		}

		list($dbconn) = GetDBconn();
		$query="INSERT INTO hc_controles_neurologia (fecha,
													pupila_talla_d,
													pupila_talla_i,
													pupila_reaccion_d,
													pupila_reaccion_i,
													tipo_nivel_consciencia_id,
													fuerza_brazo_d,
													fuerza_brazo_i,
													fuerza_pierna_d,
													fuerza_pierna_i,
													tipo_apertura_ocular_id,
													tipo_respuesta_verbal_id,
													tipo_respuesta_motora_id,
													usuario_id,
													ingreso,
													evolucion_id,
													fecha_registro)
											VALUES ('$Tiempo',
													$TallaPupilaD,
													$TallaPupilaI,
													'$ReaccionPupilaD',
													'$ReaccionPupilaI',
													$Niveles_Conciencia,
													'$Brazo_der',
													'$Brazo_izq',
													'$Pierna_der',
													'$Pierna_izq',
													$Apertura_Ocular,
													$Respuesta_Verbal,
													$Respuesta_Motora,
													".UserGetUID().",
													".$this->ingreso.",
													".$this->evolucion.",
													now());";
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

	/**
	*		Listar_ControlesNeurologicos
	*
	*		Lista los resultados de las inserciones
	*
	*		@Author Tizziano Perea O.
	*		@access Public
	*		@return bool-array-string
	*		@param array,
	*		@param array
	*/

	function Listar_ControlesNeurologicos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo'.$pfj]))
		{
			 $query = "SELECT count(*)
			 		   FROM hc_controles_neurologia
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

		$query = "SELECT A.*, B.descripcion
				 FROM hc_controles_neurologia
				 AS A left join hc_tipos_nivel_consciencia AS B
				 on (B.nivel_consciencia_id=A.tipo_nivel_consciencia_id)
				 WHERE ingreso='".$this->ingreso."'
				 ORDER BY fecha_registro
				 DESC LIMIT ".$this->limit." OFFSET $Of;";

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
			$VectorControl[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $VectorControl;
	}


	/**
	*		Listar_ControlesNeuro
	*
	*		Lista los resultados de las inserciones
	*
	*		@Author Tizziano Perea O.
	*		@access Public
	*		@return bool-array-string
	*		@param array,
	*		@param array
	*/


	function Listar_ControlesNeuro()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();

		$query = "SELECT A.*, B.descripcion
				 FROM hc_controles_neurologia
				 AS A left join hc_tipos_nivel_consciencia AS B
				 on (B.nivel_consciencia_id=A.tipo_nivel_consciencia_id)
				 WHERE ingreso='".$this->ingreso."'
				 ORDER BY fecha_registro DESC;";

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
			$VectorCon[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $VectorCon;
	}


	/**
	*		Borrar_ControlNeuro
	*
	*		Borra los registros de la tabla de Control Neurologico
	*
	*		@Author Tizziano Perea O.
	*		@access Public
	*		@return bool-array-string
	*		@param array,
	*		@param array
	*/

	function Borrar_ControlNeuro()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql="DELETE FROM  hc_controles_neurologia
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

}
?>
