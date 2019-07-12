<?php

/**
* Submodulo de Ocupacion del Paciente.
*
* Submodulo que permite reportar y editar la ocupacion de un paciente ingresado.
* @author Tizziano Perea Ocoro <tizzianop@gmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Ocupacion_Paciente.php,v 1.4 2005/09/01 18:47:58 tizziano Exp $
*/

/**
* Ocupacion_Paciente
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo Promocion_y_Prevencion, se extiende la clase Promocion_y_Prevencion y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/

class Ocupacion_Paciente extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	var $limit;
	var $conteo;
     var $finicio;
     var $ffin;

	function Ocupacion_Paciente()
	{
          if(!empty($_REQUEST['finicio']))
		{
			$this->finicio = $_REQUEST['finicio'];
		}
		if(!empty($_REQUEST['ffin']))
		{
			$this->ffin = $_REQUEST['ffin'];
		}

		$this->limit=GetLimitBrowser();
		$this->salida = '';
		return true;
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
		'fecha'=>'04/15/2005',
		'autor'=>'TIZZIANO PEREA OCORO',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
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
               $vectorI= $this->Busqueda_Avanzada_Ingresos();
               $this->frmForma($vectorI);
		}
		else
		{
			if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Ocupaciones')
			{
				$vectorD= $this->Busqueda_Avanzada_Ocupaciones();
				$this->CambiarDescripcion($vectorD);
			}

               
               if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Ingresos')
			{
				$vectorI= $this->Busqueda_Avanzada_Ingresos();
				$this->frmForma($vectorI);
			}
               
			if($_REQUEST['accion'.$pfj]=='insertar_ocupacion')
			{
				$this->Insertar_Ocupacion();
				$this->CambiarDescripcion();
			}

			if($_REQUEST['accion'.$pfj]=='cambiar_descripcion')
			{
				$this->CambiarDescripcion();
			}

			if($_REQUEST['accion'.$pfj]=='Insertar_Descripcion')
			{
                    $this->InsertarDescripcion();
                    $vectorI= $this->Busqueda_Avanzada_Ingresos();
	               $this->frmForma($vectorI);
			}

			if($_REQUEST['accion'.$pfj]=='Volver_Original')
			{   
                    $vectorI= $this->Busqueda_Avanzada_Ingresos();
	               $this->frmForma($vectorI);
			}
		}
		return $this->salida;
	}


	//cor - clzc-jea - ads
	function Busqueda_Avanzada_Ocupaciones()
	{
		$pfj=$this->frmPrefijo;

		list($dbconn) = GetDBconn();
		$codigo = STRTOUPPER ($_REQUEST['codigo'.$pfj]);
		$ocupacion  =STRTOUPPER($_REQUEST['ocupacion'.$pfj]);

		$busqueda1 = '';
		$busqueda2 = '';

		if ($codigo != '')
		{
			$busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
		}

		if (($ocupacion != '') AND ($codigo != ''))
		{
			if (eregi('%',$ocupacion))
			{
			    $busqueda2 ="AND diagnostico_nombre LIKE '$ocupacion'";
			}
			else
			{
				$busqueda2 ="AND diagnostico_nombre LIKE '%$ocupacion%'";
			}
		}

		if (($ocupacion != '') AND ($codigo == ''))
		{
			if (eregi('%',$ocupacion))
			{
    			$busqueda2 ="WHERE diagnostico_nombre LIKE '$ocupacion'";
			}
			else
			{
				$busqueda2 ="WHERE diagnostico_nombre LIKE '%$ocupacion%'";
			}
		}

		if(empty($_REQUEST['conteo'.$pfj]))
		{
			$query = "SELECT count(*)
					  FROM ocupaciones
					  $busqueda1 $busqueda2;";

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

		$query = "SELECT ocupacion_id, ocupacion_descripcion
				FROM ocupaciones
				$busqueda1 $busqueda2
				order by ocupacion_descripcion
				LIMIT ".$this->limit." OFFSET $Of;";

		$resulta = $dbconn->Execute($query);
		//$this->conteo=$resulta->RecordCount();
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}

		if($this->conteo==='0')
		{
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		return $var;
	}

     
     //cor - clzc-jea - ads
	function Busqueda_Avanzada_Ingresos()
	{
		$pfj=$this->frmPrefijo;
		$this->limit=1;
		list($dbconn) = GetDBconn();

		if(empty($_REQUEST['conteo'.$pfj]))
		{
               if(!empty($this->finicio) OR !empty($this->ffin))
               {
                    $sql = "SELECT count(*)
                            FROM ingresos 
                            WHERE fecha_registro 
                            BETWEEN '".$this->finicio."' AND '".$this->ffin."'
                            AND paciente_id = '".$this->paciente."'
                            AND tipo_id_paciente = '".$this->tipoidpaciente."';";
               }
               else
               {
                    $this->finicio = (date("Y-m-d", mktime(0,0,0,date("m"),date("d"),(date("Y")-1))));
                    $this->ffin = (date("Y-m-d"));
                    $sql = "SELECT count(*)
                            FROM ingresos 
                            WHERE fecha_registro 
                            BETWEEN '".$this->finicio."' AND '".$this->ffin."'
                            AND paciente_id = '".$this->paciente."'
                            AND tipo_id_paciente = '".$this->tipoidpaciente."';";
               }

			$resulta = $dbconn->Execute($sql);

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

          if(!empty($this->finicio) OR !empty($this->ffin))
          {
               $sql = "SELECT ingreso, fecha_registro
                       FROM ingresos 
                       WHERE fecha_registro 
                       BETWEEN '".$this->finicio."' AND '".$this->ffin."'
                       AND paciente_id = '".$this->paciente."'
                       AND tipo_id_paciente = '".$this->tipoidpaciente."'
                       LIMIT ".$this->limit." OFFSET $Of;;";
          }
          else
          {
               $this->finicio = (date("Y-m-d", mktime(0,0,0,date("m"),date("d"),(date("Y")-1))));
               $this->ffin = (date("Y-m-d"));
               $sql = "SELECT ingreso, fecha_registro 
                       FROM ingresos 
                       WHERE fecha_registro 
                       BETWEEN '".$this->finicio."' AND '".$this->ffin."'
                       AND paciente_id = '".$this->paciente."'
                       AND tipo_id_paciente = '".$this->tipoidpaciente."'
                       LIMIT ".$this->limit." OFFSET $Of;;";
          }

		$resulta = $dbconn->Execute($sql);
		//$this->conteo=$resulta->RecordCount();
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}

		if($this->conteo==='0')
		{
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
          $this->limit=GetLimitBrowser();
		return $var;
	}

     
	/**
	* Esta función inserta los datos del submodulo.
	*
	* @access private
	* @return boolean Informa si lo logro o no.
	*/

	function Insertar_Ocupacion()
	{
		$pfj=$this->frmPrefijo;
		foreach($_REQUEST['opD'.$pfj] as $k => $v)
		{
			$codigo = $v;
		}

		list($dbconn) = GetDBconn();

		$query = "UPDATE pacientes SET ocupacion_id = '$codigo'
				WHERE paciente_id = '".$this->paciente."'
				AND tipo_id_paciente = '".$this->tipoidpaciente."';";

		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return true;
	}


	/**
	* Esta función inserta los datos del submodulo.
	*
	* @access private
	* @return boolean Informa si lo logro o no.
	*/

	function ConsultaOcupacion()
	{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query = "SELECT A.ocupacion_descripcion, B.ocupacion_id
                    FROM ocupaciones AS A, pacientes AS B 
				WHERE A.ocupacion_id = B.ocupacion_id
                    AND B.paciente_id = '".$this->paciente."'
				AND B.tipo_id_paciente = '".$this->tipoidpaciente."';";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de diagnosticos de ingreso";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			$ocupacion_paciente = $result->fetchRow();
		}
		return $ocupacion_paciente;
	}

     
     function Consulta_DescripcionOcupacion()
     {
	     $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query = "SELECT A.descripcion, A.usuario_id, A.sw_estado, 
          			  A.fecha_registro,
          			  B.nombre, B.usuario
                    FROM pacientes_ocupacion AS A, system_usuarios AS B
				WHERE A.paciente_id = '".$this->paciente."'
				AND   A.tipo_id_paciente = '".$this->tipoidpaciente."'
                    AND A.usuario_id = B.usuario_id
                    ORDER BY A.fecha_registro desc;";
                    
		$resulta = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de diagnosticos de ingreso";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
          while(!$resulta->EOF)
        	{
               $datosfila=$resulta->GetRowAssoc($ToUpper = false);
               list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro']));
               list($ano,$mes,$dia) = explode("-",$fecha);
               list($hora,$min) = explode(":",$hora);
               $datosfila[hora]=$hora.":".$min;
               $descripciones[$fecha][]=$datosfila;
               $resulta->MoveNext();
          }
		return $descripciones;
     }
	/**
	* Esta función actualiza los datos del submodulo.
	*
	* @access private
	* @return boolean Informa si lo logro o no.
	*/

	function InsertarDescripcion()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		
          $ocupacion = $_REQUEST['ocupacion_id'.$pfj];
          $descripcion = $_REQUEST['descripcion_ocupacion'.$pfj];

          $sql2 = "UPDATE pacientes_ocupacion
			    SET sw_estado = '0'
			    WHERE paciente_id = '".$this->paciente."'
			    AND tipo_id_paciente = '".$this->tipoidpaciente."';";

		$resulta = $dbconn->Execute($sql2);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

          		
          $sql = "SELECT ocupacion_id
			   FROM pacientes_ocupacion
			   WHERE paciente_id = '".$this->paciente."'
			   AND tipo_id_paciente = '".$this->tipoidpaciente."'
                  AND ocupacion_id = '".$ocupacion."';";

		$resulta = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          
		list($existe_Ocupacion) = $resulta->FetchRow();
		if (empty($existe_Ocupacion))
		{
			$query = "INSERT INTO pacientes_ocupacion (paciente_id,
											   tipo_id_paciente,
											   ocupacion_id,
											   descripcion,
                                                          usuario_id,
                                                          sw_estado,
                                                          fecha_registro)
										VALUES('".$this->paciente."',
											  '".$this->tipoidpaciente."',
											  '$ocupacion',
											  '$descripcion',
                                                         ".$this->usuario_id.",
                                                         '1',
                                                         now());";
		}
		else
		{
			$query = "UPDATE pacientes_ocupacion
				     SET descripcion = '$descripcion'
                             usuario_id = ".$this->usuario_id."
                             sw_estado = '1'
                             fecha_registro = now()
					WHERE paciente_id = '".$this->paciente."'
					AND tipo_id_paciente = '".$this->tipoidpaciente."'
                         AND ocupacion_id = '$ocupacion';";
		}
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return true;
	}
     
     
     function Datos_Adicionales_Pacientes()
     {
		$pfj=$this->frmPrefijo;
     	GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $query = "SELECT E.direccion_trabajo, E.telefono_trabajo
                    FROM pacientes AS F 
                    LEFT JOIN pacientes_datos_adicionales AS E ON (F.paciente_id = E.paciente_id AND F.tipo_id_paciente = E.tipo_id_paciente)
                    WHERE F.paciente_id = '".$this->paciente."'
                    AND F.tipo_id_paciente = '".$this->tipoidpaciente."';";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		
          if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error en la consulta";
			$this->mensajeDeError = $query.$dbconn->ErrorMsg();
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;
			return false;
		}
		
          $this->datos_adicionales = $resultado->FetchRow();
		return true;
     }
     
     function Busqueda_OrigenesAtencion_Rips()
     {
		$pfj=$this->frmPrefijo;
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          
          if(!empty($this->finicio) OR !empty($this->ffin))
          {
               $sql = "SELECT ingreso, fecha_registro 
                       FROM ingresos 
                       WHERE fecha_registro 
                       BETWEEN '".$this->finicio."' AND '".$this->ffin."'
                       AND paciente_id = '".$this->paciente."'
                       AND tipo_id_paciente = '".$this->tipoidpaciente."';";
          }
          else
          {
          	$this->finicio = (date("Y-m-d", mktime(0,0,0,date("m"),date("d"),(date("Y")-1))));
               $this->ffin = (date("Y-m-d"));
               $sql = "SELECT ingreso, fecha_registro 
                       FROM ingresos 
                       WHERE fecha_registro 
                       BETWEEN '".$this->finicio."' AND '".$this->ffin."'
                       AND paciente_id = '".$this->paciente."'
                       AND tipo_id_paciente = '".$this->tipoidpaciente."';";
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          while($data = $resultado->FetchRow())
          {
          	$ingresos[] = $data;
          }
          return $ingresos;
     }

     
     function Causas_Externas($ingreso)
     {
		$pfj=$this->frmPrefijo;
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		$query = "SELECT A.detalle, B.evolucion_id
          		FROM hc_tipos_atencion A,
                    	hc_atencion B
                    WHERE B.ingreso = $ingreso
                    AND (B.tipo_atencion_id = '01' OR B.tipo_atencion_id = '14' OR B.tipo_atencion_id = '02')
                    AND B.tipo_atencion_id = A.tipo_atencion_id
                    ORDER BY B.ingreso DESC;";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          while($data = $resultado->FetchRow())
          {
          	$causas[] = $data;
          }
          return $causas;
     }

     function Diagnosticos($ingreso)
     {
		$pfj=$this->frmPrefijo;
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		$query = "SELECT A.diagnostico_nombre,
                    	  A.diagnostico_id,
                           B.sw_principal,
                           B.tipo_diagnostico
          		FROM diagnosticos A,
                    	hc_diagnosticos_ingreso B,
                         hc_evoluciones C
                    WHERE C.ingreso = $ingreso
                    AND B.evolucion_id = C.evolucion_id
                    AND B.tipo_diagnostico_id = A.diagnostico_id
                    ORDER BY B.evolucion_id DESC";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          while($data = $resultado->FetchRow())
          {
          	$dx[] = $data;
          }
          return $dx;
     }
    	
     function Motivos($ingreso)
     {
		$pfj=$this->frmPrefijo;
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		$query = "SELECT descripcion AS motivo_consulta,
          			  enfermedadactual AS enfermedad_actual, evolucion_id
          		FROM hc_motivo_consulta
                    WHERE ingreso = $ingreso
                    ORDER BY evolucion_id DESC;";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          while($data = $resultado->FetchRow())
          {
          	$motivos[] = $data;
          }
          return $motivos;
     }
    	
     
     function Via_Ingreso($ingreso)
     {
		$pfj=$this->frmPrefijo;
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		$query = "SELECT A.via_ingreso_nombre
          		FROM vias_ingreso A, 
                    	ingresos B
                    WHERE B.ingreso = $ingreso
                    AND A.via_ingreso_id = B.via_ingreso_id
                    ORDER BY B.ingreso DESC;";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          $via = $resultado->FetchRow();
          return $via;
     }

          
     function FechaStamp($fecha)
	{
		if($fecha){
               $fech = strtok ($fecha,"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               return  ceil($date[2])." / ".ceil($date[1])." / ".ceil($date[0]);
		}
	}

     /*		HoraStamp
     *
     *		Convierte los datos en Horas a partir de la Fecha Registro.
     *
     *		@Author Alexander Giraldo.
     *		@access Public
     *		@param integer => fecha_registro
     */
	function HoraStamp($hora)
	{
		$hor = strtok ($hora," ");
		for($l=0;$l<4;$l++)
		{
               $time[$l]=$hor;
               $hor = strtok (":");
		}

		$x = explode (".",$time[3]);
		return  $time[1].":".$time[2].":".$x[0];
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
