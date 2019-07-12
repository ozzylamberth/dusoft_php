<?php

/**
* Submodulo Indice Trauma Revisado.
*
* Submodulo para manejar el sistema de Traumas de los pacientes ingresados.
* @author Tizziano Perea O. <tperea@ipsoft-sa.com>
* @version 1.0
* @package SIIS
* $Id: hc_IndiceTrauma_Revisado.php,v 1.6 2007/03/15 20:50:08 tizziano Exp $
*/


/**
* IndiceTrauma_Revisado
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de IndiceTrauma_Revisado.
*/

class IndiceTrauma_Revisado extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	var $limit;
	var $conteo;

	function IndiceTrauma_Revisado()
	{
		$this->limit=GetLimitBrowser();
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
		if($this->tipo_profesional == 1 OR $this->tipo_profesional == 2)
		{
			$pfj=$this->frmPrefijo;
			if(empty($_REQUEST['accion'.$pfj]))
			{
				$this->frmForma();
			}
			else
			{
				if($_REQUEST['accion'.$pfj]=='Insertar_Indices')
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

				if($_REQUEST['accion'.$pfj]=='Listar_Indices')
				{
					if($this->Listar_ITR()==true)
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
		else
		{
			$this->GetConsulta();
		}
		return $this->salida;
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
		*		GetTipoRegion
		*
		*		Obtiene los diferentes valores de las regiones del trauma
		*
		*		@Author Tizziano Perea O
		*		@access Public
		*		@return bool-array-string
		*		@param array,
		*		@param array
		*/

		function GetTipoRegion()
		{
			$pfj=$this->frmPrefijo;
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();

			$query ="SELECT *
					 FROM hc_tipo_region
					 ORDER BY tipo_region_id ASC";

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
				while ($data = $result->FetchRow())
				{
					$Regiones[] = $data;

				}
				return $Regiones;
			}
		}//GetTipoRegion


		/**
		*		GetTipoTrauma
		*
		*		Obtiene los tipos de Traumas y sus Valores
		*
		*		@Author Tizziano Perea O
		*		@access Public
		*		@return bool
		*		@param array,
		*		@param array
		*/
		function GetTipoTrauma()
		{
			$pfj=$this->frmPrefijo;
			GLOBAL $ADODB_FETCH_MODE;

			list($dbconn) = GetDBconn();
			$query = "SELECT *
					  FROM hc_tipo_trauma
					  ORDER BY tipo_trauma_id ASC";

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
   				while ($data = $result->FetchRow())
				{
					$Trauma[] = $data;
				}
				return $Trauma;
			}
		}//GetTipoTrauma


		/**
		*		GetTiposSNC
		*
		*		Obtiene los valores del Sistema Nervioso Central
		*
		*		@Author Tizziano Perea O
		*		@access Public
		*		@return bool-array-string
		*		@param array,
		*		@param array
		*/
		function GetTiposSNC()
		{
			$pfj=$this->frmPrefijo;
			GLOBAL $ADODB_FETCH_MODE;

			list($dbconn) = GetDBconn();
			$query = "SELECT *
					  FROM hc_tipo_snc
					  ORDER BY tipo_snc_id ASC";

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
				while ($data = $result->FetchRow())
				{
					$SNC[] = $data;
				}
				return $SNC;
			}

		}//GetTiposSNC


		/**
		*		GetTiposSNC
		*
		*		Obtiene los valores de los Rango CardioVasculares
		*
		*		@Author Tizziano Perea O
		*		@access Public
		*		@return bool-array-string
		*		@param array,
		*		@param array
		*/
		function Get_TipoCardioVascular()
		{
			$pfj=$this->frmPrefijo;
			GLOBAL $ADODB_FETCH_MODE;

			list($dbconn) = GetDBconn();
			$query = "SELECT *
					  FROM hc_tipo_cardiovascular
					  ORDER BY tipo_cardiovascular_id ASC";

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
				while ($data = $result->FetchRow())
				{
					$CardioV[] = $data;
				}
				return $CardioV;
			}

		}//Get_TipoCardioVascular


		/**
		*		GetTiposSNC
		*
		*		Obtiene los valores de los Rangos Respiratorios
		*
		*		@Author Tizziano Perea O
		*		@access Public
		*		@return bool-array-string
		*		@param array,
		*		@param array
		*/
		function Get_TipoRespiratorio()
		{
			$pfj=$this->frmPrefijo;
			GLOBAL $ADODB_FETCH_MODE;

			list($dbconn) = GetDBconn();
			$query = "SELECT *
					  FROM hc_tipo_respiratorio
					  ORDER BY tipo_respiratorio_id ASC";

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
				while ($data = $result->FetchRow())
				{
					$Respiratorio[] = $data;
				}
				return $Respiratorio;
			}

		}//Get_TipoRespiratorio



	/**
	* Esta función inserta los datos del submodulo.
	*
	* @access private
	* @return boolean Informa si lo logro o no.
	*/
	function InsertDatos()
	{
		$pfj=$this->frmPrefijo;

		$fecha= $_REQUEST['fechadef'.$pfj];

		$cad=explode ('/',$fecha);
		$dia = $cad[0];
		$mes = $cad[1];
		$ano = $cad[2];
		$fecha=$cad[2].'-'.$cad[1].'-'.$cad[0];

		$fechaHora = $fecha." ".$_REQUEST['selectHora'.$pfj].":".$_REQUEST['selectMinutos'.$pfj];

		$Regiones = $_REQUEST['region'.$pfj];
		$Trauma = $_REQUEST['trauma'.$pfj];
		$SNC = $_REQUEST['snc'.$pfj];
		$Cardio = $_REQUEST['cardio'.$pfj];
		$Respiratorio = $_REQUEST['fr'.$pfj];

		if (empty ($Regiones))
		{
			$this->frmError["MensajeError"] = "FALTA EL VALOR CORRESPONDIENTE A LA REGION";
			$this->frmForma();
			return true;
		}

		if (empty ($Trauma))
		{
			$this->frmError["MensajeError"] = "FALTA EL VALOR CORRESPONDIENTE AL TIPO DE TRAUMA";
			$this->frmForma();
			return true;
		}

		if (empty ($SNC))
		{
			$this->frmError["MensajeError"] = "FALTA EL VALOR CORRESPONDIENTE AL SNC";
			$this->frmForma();
			return true;
		}

		if ($Cardio == '-1')
		{
			$this->frmError["MensajeError"] = "FALTA EL VALOR CORRESPONDIENTE AL RANGO CARDIOVASCULAR";
			$this->frmForma();
			return true;
		}

		if ($Respiratorio == '-1')
		{
			$this->frmError["MensajeError"] = "FALTA EL VALOR CORRESPONDIENTE AL RANGO RESPIRATORIO";
			$this->frmForma();
			return true;
		}

		if ($fecha == '--')
		{
			$this->frmError["MensajeError"] = "POR FAVOR INTRODUZCA LA FECHA CORRECTAMENTE";
			$this->frmForma();
			return true;
		}

		if ( strtotime($fecha) > strtotime(date("y-m-d")) )
		{
			$this->frmError["MensajeError"] = "LA FECHA DE REGISTRO DE INDICES NO PUEDE SER MAYOR A LA FECHA ACTUAL";
			$this->frmForma();
			return true;
		}

		//luego valido que no existan registros a esa hora
		list($dbconn) = GetDBconn();
		$query = "SELECT fecha_registro
				  FROM hc_indice_trauma_revisado
				  WHERE ingreso=".$this->ingreso." AND
				  fecha_registro = '$fechaHora'
				  ORDER BY fecha_registro DESC;";
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
				$this->frmError["MensajeError"] = "YA SE REGISTRARON ITR'S EN ESTA HORA ($fechaHora)";
				$this->frmForma();
				return true;
			}
		}

		list($dbconn) = GetDBconn();
		$query="INSERT INTO hc_indice_trauma_revisado  (ingreso,
														evolucion_id,
														fecha_registro,
														region,
														tipo_trauma,
														cardiovascular,
														respiratorio,
														snc,
														usuario_id)
												VALUES (".$this->ingreso.",
														".$this->evolucion.",
														'$fechaHora',
														'$Regiones',
														'$Trauma',
														'$Cardio',
														'$Respiratorio',
														'$SNC',
														".UserGetUID().");";
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
	*		Listar_ITR
	*
	*		Lista los resultados de las inserciones
	*
	*		@Author Tizziano Perea O.
	*		@access Public
	*		@return bool-array-string
	*		@param array,
	*		@param array
	*/

	function Listar_ITR()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo'.$pfj]))
		{
			 $query = "SELECT count(*)
			 		   FROM hc_indice_trauma_revisado
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

		$query = "SELECT A.fecha_registro, A.region,
				 		 A.tipo_trauma, A.cardiovascular,
						 A.respiratorio, A.snc, A.usuario_id,
						 B.usuario, B.usuario_id
				  FROM hc_indice_trauma_revisado AS A,
      				   system_usuarios AS B
				  WHERE A.ingreso='".$this->ingreso."'
				  AND B.usuario_id = A.usuario_id
				  ORDER BY fecha_registro
				  DESC LIMIT ".$this->limit." OFFSET $Of;";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$i=0;
			while(!$resulta->EOF)
			{
				$VectorI[$i]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
				$i++;
			}
			return $VectorI;
		}
	}
     
	/**
	*		Listar_ITR_Impresion
	*
	*		Lista los resultados de las inserciones
	*
	*		@Author Tizziano Perea O.
	*		@access Public
	*		@return bool-array-string
	*		@param array,
	*		@param array
	*/
     
	function Listar_ITR_Impresion()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
     
		$query = "SELECT A.fecha_registro, A.region,
				 		 A.tipo_trauma, A.cardiovascular,
						 A.respiratorio, A.snc, A.usuario_id,
						 B.usuario, B.usuario_id
				  FROM hc_indice_trauma_revisado AS A,
      				   system_usuarios AS B
				  WHERE A.ingreso='".$this->ingreso."'
				  AND B.usuario_id = A.usuario_id
				  ORDER BY fecha_registro;";
		$resulta = $dbconn->Execute($query);
          
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$i=0;
			while(!$resulta->EOF)
			{
				$VectorI[$i]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
				$i++;
			}
			return $VectorI;
		}
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

/*	function Borrar_ControlNeuro()
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
/*		function GetDatosUsuarioSistema($usuario)
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
		}/// GetDatosUsuarioSistema*/

}
?>
