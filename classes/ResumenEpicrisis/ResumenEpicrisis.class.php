<?php

class ResumenEpicrisis
{
	var $salida;
	var $error;
	var $mensajeDeError;
	var $ingreso;
	var $datosPaciente;
	var $datosEvolucion;
	var $imprimir;
	var $realimprimir;
	var $datosResponsable;
	
     
     /************************************************************************/
     /* SE DEBE REALIZAR UN TIPO CIERRE (sw_epicrisis), EL CUAL VERIFIQUE LA  
     /* EXISTENCIA DE LA INFO VALIDA PARA LA EPICRISIS.
     /*
     /* GENERAR EN LOS SUBMODULOS UN METODO Q RETORNE LOS DATOS PARA LA IMPRESION
     /* DE LA EPICRISIS EJ. GETDATOSEPICRISIS($TIPO='') PARECIDO A IMP. HISTORIA
     /* SE DEBE SWITCHEAR LA VARIABLE TIPO PARA VALIDAR LA MANERA DE RETORNO
     /* YA Q HABRA UN TIPO DE IMPRESION DE EPICRISIS DE SOAT, Y POR LO TANTO LOS
     /* DATOS SE MUESTRAN DE MANERA DIFERENTE.
     /************************************************************************/
     
     
     function ResumenEpicrisis()
     {
		$this->salida='';
		$this->error='';
		$this->mensajeDeError='';
		return true;
	}
     
	function Error()
	{
		return $this->error;
	}

	function ErrorMsg()
	{
		return $this->mensajeDeError;
	}

	function GetSalida()
	{
		return $this->salida;
	}

	function GetImpresion()
	{
		if($this->realimprimir==2)
		{
      		return $this->imprimir;
		}
		else
		{
			return false;
		}
	}

	function GetInformacionEmpresa($departamento)
	{
		list($dbconn) = GetDBconn();
		$sql="select b.tipo_id_tercero, b.id, b.razon_social, b.direccion, b.telefonos from departamentos as a, empresas as b where a.departamento='$departamento' and a.empresa_id=b.empresa_id;";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0) {
               return false;
          }
		while(!$result->EOF)
		{
			$dato=$result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $dato;
	}
     
     function CargarVariables()
     {
     	$this->realimprimir=1;
          list($dbconn) = GetDBconn();
          $query = "SELECT MAX(A.evolucion_id) 
                    FROM hc_evoluciones AS A,
                         profesionales AS B 
                    WHERE A.ingreso='".$this->ingreso."'
                    AND A.usuario_id = B.usuario_id
                    AND B.tipo_profesional IN ('1','2');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
 		list($evolucion) = $result->FetchRow();
          $this->evolucion = $evolucion;
          
          if(!$evolucion)
          {
               $query = "SELECT MAX(evolucion_id)
                         FROM hc_evoluciones
                         WHERE ingreso='".$this->ingreso."';";
               $result = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               list($evolucion) = $result->FetchRow();
               $this->evolucion = $evolucion;
          }

		if(!IncludeLib('datospaciente'))
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No se pudo cargar la libreria de datos de pacientes.";
      		return false;
          }
          
		if(!IncludeLib('historia_clinica'))
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No se pudo cargar la libreria de datos de Historia Clinica";
			return false;
          }

		if(!IncludeFile('classes/modules/hc_classmodules.class.php',true))
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El archivo 'includes/historia_clinica.inc.php' no existe.";
			return false;
          }

          $this->Datosingreso = GetDatosIngreso($this->evolucion,$this->ingreso);

          $this->datosPaciente = GetDatosPaciente("","",$this->ingreso);

     	$this->EvolucionGeneral = GetDatosEvolucion($this->evolucion);

          $this->tipo_profesional = $this->GetTipoProfesional();
          
		$this->Datos_Ingreso();
 
          $this->GetDatosResponsable();
          
          $this->GetDatosProfesional();
          
          $this->User = $this->GetDatosUsuarioSistema(UserGetUID());
          
          return true;
     }
     
     
     function BuscarCamaActiva($ingreso)
     {
		list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
		$query = "SELECT Count(movimiento_id) 
          		FROM movimientos_habitacion 
				WHERE ingreso = '".$this->ingreso."';";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          
          if($result->fields[0] > 0)
          {
               $query = "SELECT A.cama, A.fecha_ingreso, 
               			  B.fecha_registro AS fecha_egreso, '1' AS int
               		FROM movimientos_habitacion AS A
                    		LEFT JOIN ingresos_salidas AS B ON (A.ingreso = B.ingreso)
               		WHERE A.ingreso = '".$this->ingreso."'
               		AND A.movimiento_id = (SELECT MAX(movimiento_id) 
                         				   FROM movimientos_habitacion 
                                                WHERE ingreso = '".$this->ingreso."');";
          }
          else
          {
               $query = "SELECT A.sw_estado, B.fecha_ingreso, 
               			  C.fecha_registro AS fecha_egreso, '1' AS int
               		FROM pacientes_urgencias AS A, 
                    		ingresos AS B 
                    		LEFT JOIN ingresos_salidas AS C ON (B.ingreso = C.ingreso)
               		WHERE A.ingreso = '".$this->ingreso."'
               		AND A.ingreso = B.ingreso;";          
          }
                              
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          $this->DatosCama = $result->FetchRow();
          return true;
     }


	function GetDatosResponsable()
	{
		list($dbconn) = GetDBconn();
         
          $sql="SELECT a.plan_id, a.tipo_afiliado_id, a.rango, a.semanas_cotizadas, b.plan_descripcion, b.tipo_tercero_id, b.tercero_id, b.num_contrato, c.nombre_tercero, X.tipo_afiliado_nombre
               FROM cuentas as a LEFT JOIN tipos_afiliado AS X ON (a.tipo_afiliado_id = X.tipo_afiliado_id), planes as b, terceros as c
               WHERE
               a.plan_id = b.plan_id
               AND b.tercero_id = c.tercero_id
               AND b.tercero_id = c.tercero_id
               AND a.numerodecuenta = ".$this->EvolucionGeneral['numerodecuenta'].";";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error en la consulta";
			$this->mensajeDeError = $sql.$dbconn->ErrorMsg();
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;
			return false;
		}
		if(!$resultado->EOF)
		{
			$this->Responsable = $resultado->FetchRow();
		}
		return $this->Responsable;
	}

     function GetTipoProfesional()
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT tipo_profesional
               FROM profesionales_usuarios a, profesionales b
               WHERE a.tipo_tercero_id=b.tipo_id_tercero and
               a.tercero_id=b.tercero_id and
               a.usuario_id=".UserGetUID()."";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               return false;
          }
          list($this->tipo_profesional) = $result->FetchRow();
          return $this->tipo_profesional;
     }

	function GetServicio($departamento)
	{
		list($dbconn) = GetDBconn();
		$sql="select b.descripcion from departamentos as a, servicios as b where a.servicio=b.servicio and a.departamento='".$departamento."';";
		$result = $dbconn->Execute($sql);
		return $result->fields[0];
	}

	function GetDepartamento($departamento)
	{
		list($dbconn) = GetDBconn();
		$sql="select a.descripcion from departamentos as a where a.departamento='".$departamento."';";
		$result = $dbconn->Execute($sql);
		return $result->fields[0];
	}

	function GetDatosProfesional()
	{
          list($dbconn) = GetDBconn();
          /*$sql="SELECT A.tipo_id_tercero, A.tercero_id, A.nombre,
               	   A.tarjeta_profesional, B.especialidad, C.descripcion
               FROM profesionales AS A,
               	profesionales_usuarios AS E
               LEFT JOIN profesionales_especialidades AS B
               ON(E.tipo_tercero_id=B.tipo_id_tercero AND E.tercero_id=B.tercero_id)
               LEFT JOIN especialidades AS C ON(C.especialidad = B.especialidad)
               WHERE A.usuario_id =".$this->EvolucionGeneral['usuario_id']."
               AND A.usuario_id = E.usuario_id
               AND E.tercero_id = A.tercero_id
               AND E.tipo_tercero_id = A.tipo_id_tercero;";*/
			   
			$sql="SELECT   A.tipo_id_tercero, 
                                 A.tercero_id, 
                                 A.nombre,
               	                 A.tarjeta_profesional, 
                                 B.especialidad, 
                                 C.descripcion,
                                 A.firma
                     FROM   profesionales AS A,
               	                profesionales_usuarios AS E
                     LEFT JOIN profesionales_especialidades AS B ON(E.tipo_tercero_id=B.tipo_id_tercero AND E.tercero_id=B.tercero_id)
                     LEFT JOIN especialidades AS C ON(C.especialidad = B.especialidad)
                    WHERE   E.usuario_id =".$this->EvolucionGeneral['usuario_id']."
                    AND       E.tercero_id = A.tercero_id
                    AND       E.tipo_tercero_id = A.tipo_id_tercero;";   

		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
          {
               return false;
          }
		while(!$result->EOF)
		{
			$this->datosProfesional = $result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $this->datosProfesional;
	}

     
     function Datos_Ingreso()
	{
		list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
		$query = "SELECT A.fecha_registro, A.departamento, A.departamento_actual,
                           A.fecha_cierre, B.fecha, B.fecha_cierre AS cierre_evolucion,
                           C.descripcion 
                    FROM ingresos AS A
                    LEFT JOIN hc_evoluciones AS B ON (A.ingreso = B.ingreso)
                    LEFT JOIN departamentos AS C ON (A.departamento_actual = C.departamento)
                    WHERE A.ingreso='".$this->ingreso."'
                    AND B.evolucion_id = (SELECT MAX(evolucion_id) 
                    				  FROM hc_evoluciones 
                                          WHERE ingreso = '".$this->ingreso."' 
                                          AND fecha_cierre IS NOT NULL);";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$this->DatosIngreso_Paciente = $result->FetchRow();
			return $this->DatosIngreso_Paciente;
		}
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


	function IniciarImprimir()
	{
		$this->CabeceraImprimir();
          list($dbconn) = GetDBconn();
		$query = "SELECT * FROM historias_clinicas_epicrisis_submodulos;";	
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
          {
               return false;
		}

		if ($result->EOF) 
		{
			$this->imprimir .= "NO HAY DATOS VALIDOS PARA IMPRESION";
			return true;
		}
		else
		{
               while(!$result->EOF)
               {
                    $var[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();					
               }
               foreach($var as $k => $v)
               {						
                    $submodulo_obj=IncluirSubModuloHC($v[submodulo]);
                    if(!is_object($submodulo_obj)){
                         $this->error = "Error al cargar el submodulo";
                         $this->mensajeDeError = $submodulo_obj;
                    }
                    else
                    {
                         $submodulo_obj->InicializarSubmodulo($this->EvolucionGeneral,'',$this->datosPaciente,'','','',$this->paso,'frm_'.$v[submodulo],'','','','');
                         if(method_exists($submodulo_obj,'GetReporte_Html'))
                         {
                              $dato1=$submodulo_obj->GetReporte_Html();
                              if($dato1!=1)
                              {
                                   $this->realimprimir=2;
                                   $this->imprimir.=$dato1;
                              }
                         }
                    }
                    unset($dato1);
                    unset($submodulo_obj);
               }
               $result->close();
               if($this->realimprimir==2)
               {
                    $this->PiePaginaImprimir();
               }
		}
		$this->GetImpresion();
		return true;
	}

	function FechaStamp($fecha)
	{
		if($fecha)
          {
               $fech = strtok ($fecha,"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
		}
	}

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
}//fin clase
?>
