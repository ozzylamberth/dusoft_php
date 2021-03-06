<?php

class ResumenHC
{
	var $salida;
	var $error;
	var $mensajeDeError;
	var $evolucion;
	var $datosPaciente;
	var $datosEvolucion;
	var $imprimir;
	var $realimprimir;
	var $datosResponsable;

	function ResumenHC()
	{
		$this->salida='';
		$this->error='';
		$this->mensajeDeError='';
    IncludeClass('AutoCarga');
    IncludeClass('ConexionBD');
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

	function GetImpresion()
	{
		if($this->realimprimir==2)
		{
			return $this->imprimir;
		}
		return false;
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

// 	function GetDatosResponsable()
// 	{
// 		list($dbconn) = GetDBconn();
// 		$datosEvol = GetDatosEvolucion($this->evolucion);
// 		$sql="SELECT a.plan_id, a.tipo_afiliado_id, a.rango, a.semanas_cotizadas, b.plan_descripcion, b.tipo_tercero_id, b.tercero_id, b.num_contrato, c.nombre_tercero, X.tipo_afiliado_nombre
// 				FROM cuentas as a LEFT JOIN tipos_afiliado AS X ON (a.tipo_afiliado_id = X.tipo_afiliado_id), planes as b, terceros as c
// 				WHERE
// 				a.plan_id = b.plan_id
// 				AND b.tercero_id = c.tercero_id
// 				AND b.tercero_id = c.tercero_id
// 				AND a.numerodecuenta = ".$datosEvol['numerodecuenta'].";";
// 
// 		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
// 		$resultado = $dbconn->Execute($sql);
// 		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
// 
// 		if ($dbconn->ErrorNo() != 0) {
// 			$this->error = "Error en la consulta";
// 			$this->mensajeDeError = $sql.$dbconn->ErrorMsg();
// 			$this->fileError = __FILE__;
// 			$this->lineError = __LINE__;
// 			return false;
// 		}
// 		if(!$resultado->EOF)
// 		{
// 			$Responsable = $resultado->FetchRow();
// 		}
// 		return $Responsable;
// 	}

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


	function GetSalida()
	{
		return $this->salida;
	}

	
     function Consulta_NotasMedicas()
	{
		list($dbconn) = GetDBconn();
		$query= "SELECT A.ingreso,
					 A.fecha_registro, A.evolucion_id,
					 A.nota_medica, B.nombre, B.usuario
				FROM notas_medicas AS A,
					system_usuarios AS B
				WHERE A.ingreso='".$this->datosEvolucion[ingreso]."'
				AND B.usuario_id=A.usuario_id
				ORDER BY fecha_registro DESC;";

		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while(!$resulta->EOF)
			{
				$datosfila=$resulta->GetRowAssoc($ToUpper = false);
				list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro']));//substr(,0,10);
				list($ano,$mes,$dia) = explode("-",$fecha);
				list($hora,$min) = explode(":",$hora);
				$datosfila[hora]=$hora.":".$min;
				$fecha = $fecha;
				$notas[$fecha][]=$datosfila;
				$resulta->MoveNext();
			}
		}
		return $notas;
	}

     
// 	function GetDatosProfesional($evolucion)
// 	{
// 		list($dbconn) = GetDBconn();
// 		$sql="select b.tercero_id, b.tipo_id_tercero, b.nombre_tercero, c.tarjeta_profesional from profesionales_usuarios as a, terceros as b, profesionales as c where a.usuario_id=".$this->datosEvolucion['usuario_id']." and a.tercero_id=b.tercero_id and a.tipo_tercero_id=b.tipo_id_tercero and b.tipo_id_tercero=c.tipo_id_tercero and b.tercero_id=c.tercero_id;";
// 		$result = $dbconn->Execute($sql);
// 		if($dbconn->ErrorNo() != 0) {
//         return false;
//     }
// 		while(!$result->EOF)
// 		{
// 			$profesional=$result->GetRowAssoc(false);
// 			$result->MoveNext();
// 		}
// 		return $profesional;
// 	}

	function GetDatosProfesional()
	{
          list($dbconn) = GetDBconn();
          /*if ($this->tipo_profesional == 1 OR $this->tipo_profesional == 2)
          {
               $sql="SELECT A.tipo_id_tercero, A.tercero_id, A.nombre,
                    A.tarjeta_profesional, B.especialidad, C.descripcion
                    FROM profesionales AS A, system_usuarios AS D,
                    profesionales_usuarios AS E
                    LEFT JOIN profesionales_especialidades AS B
                    ON(E.tipo_tercero_id=B.tipo_id_tercero AND E.tercero_id=B.tercero_id)
                    LEFT JOIN especialidades AS C ON(C.especialidad = B.especialidad)
                    WHERE D.usuario_id =".UserGetUID()."
                    AND D.usuario_id=E.usuario_id
                    AND E.tercero_id=A.tercero_id
                    AND E.tipo_tercero_id=A.tipo_id_tercero;";
          }
          else
          {
               $sql="SELECT A.tipo_id_tercero, A.tercero_id, A.nombre,
                    A.tarjeta_profesional, B.especialidad, C.descripcion
                    FROM profesionales AS A, system_usuarios AS D,
                    profesionales_usuarios AS E
                    LEFT JOIN profesionales_especialidades AS B
                    ON(E.tipo_tercero_id=B.tipo_id_tercero AND E.tercero_id=B.tercero_id)
                    LEFT JOIN especialidades AS C ON(C.especialidad = B.especialidad)
                    WHERE D.usuario_id =".$this->EvolucionGeneral['usuario_id']."
                    AND D.usuario_id=E.usuario_id
                    AND E.tercero_id=A.tercero_id
                    AND E.tipo_tercero_id=A.tipo_id_tercero;";
          }*/
		  
		  if ($this->tipo_profesional == 1 OR $this->tipo_profesional == 2)
          {
               $sql=" SELECT   A.tipo_id_tercero, 
                                       A.tercero_id, 
                                       A.nombre,
                                      A.tarjeta_profesional, 
                                      B.especialidad, 
                                      C.descripcion, A.firma
                          FROM   profesionales AS A, 
                                     system_usuarios AS D,
                                     profesionales_usuarios AS E
                           LEFT JOIN profesionales_especialidades AS B ON(E.tipo_tercero_id=B.tipo_id_tercero AND E.tercero_id=B.tercero_id)
                           LEFT JOIN especialidades AS C ON(C.especialidad = B.especialidad)
                           WHERE  D.usuario_id =".$this->EvolucionGeneral['usuario_id']."
                           AND      D.usuario_id=E.usuario_id
                           AND      E.tercero_id=A.tercero_id
                           AND      E.tipo_tercero_id=A.tipo_id_tercero;";
          }
          else
          {
               $sql=" SELECT  A.tipo_id_tercero, 
                                      A.tercero_id, 
                                      A.nombre,
                                      A.tarjeta_profesional, 
                                      B.especialidad, 
                                      C.descripcion, 
                                      A.firma
                        FROM     profesionales AS A, 
                                     system_usuarios AS D,
                                     profesionales_usuarios AS E
                         LEFT JOIN profesionales_especialidades AS B ON(E.tipo_tercero_id=B.tipo_id_tercero AND E.tercero_id=B.tercero_id)
                         LEFT JOIN especialidades AS C ON(C.especialidad = B.especialidad)
                        WHERE  D.usuario_id =".$this->EvolucionGeneral['usuario_id']."
                        AND      D.usuario_id=E.usuario_id
                        AND      E.tercero_id=A.tercero_id
                        AND      E.tipo_tercero_id=A.tipo_id_tercero;";
          }
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

     /*********************************************************************************************/
     /*********************************************************************************************/
     
/*	function Datos_Ingreso()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.fecha_registro, B.fecha_cierre, B.fecha
          		FROM ingresos AS A
				LEFT JOIN hc_evoluciones AS B ON (A.ingreso = B.ingreso)
				WHERE B.evolucion_id='".$this->evolucion."';";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$this->DatosIngreso_Paciente = $result->GetRows();
			return $this->DatosIngreso_Paciente;
		}
	}*/
     
	function Datos_Ingreso()
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.fecha_registro, A.departamento, A.departamento_actual,
				  B.fecha_cierre, B.fecha, C.descripcion, C.unidad_funcional
                      FROM ingresos AS A
				  LEFT JOIN hc_evoluciones AS B ON (A.ingreso = B.ingreso)
				  LEFT JOIN departamentos AS C ON (A.departamento_actual = C.departamento)
				  WHERE A.ingreso='".$this->ingreso."'
				  AND B.evolucion_id='".$this->evolucion."';";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$this->DatosIngreso_Paciente = $result->GetRows();
			return $this->DatosIngreso_Paciente;
		}
	}
     /*********************************************************************************************/
     /*********************************************************************************************/
     
     
	function IniciarImprimir()
	{
		$this->realimprimir=1;
		if(!IncludeLib('datospaciente')){
		$this->error = "Error al Cargar el Modulo";
		$this->mensajeDeError = "No se pudo cargar la libreria de datos de pacientes.";
		return false;
    }

    if(!IncludeLib('historia_clinica')){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "No se pudo cargar la libreria de datos de Historia Clinica";
        return false;
    }

		if(!IncludeFile('classes/modules/hc_classmodules.class.php',true)){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "El archivo 'includes/historia_clinica.inc.php' no existe.";
        return false;
    }


		$this->datosPaciente = GetDatosPaciente('','','',$this->evolucion);
		$this->datosEvolucion=GetDatosEvolucion($this->evolucion);
		$this->CabeceraImprimir();
		list($dbconn) = GetDBconn();
    
		$this->datosProfesional=$this->GetDatosProfesional($this->datosEvolucion['evolucion_id']);
		$vista_Nota = $this->Vista_NotaMedica();

	$query = "SELECT a.submodulo
				FROM historias_clinicas_templates a
				WHERE hc_modulo = '" . $this->datosEvolucion['hc_modulo'] . "'
				ORDER BY a.paso,a.secuencia;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			return false;
		}

		if ($result->EOF) {
			$this->imprimir .= "HISTORIA CLINICA VACIA";
			return true;
		}
			else
			{
				while(!$result->EOF)
				{
					$submodulo_obj=IncluirSubModuloHC($result->fields[0]);
					if(!is_object($submodulo_obj)){
						$this->error = "Error al cargar el submodulo";
						$this->mensajeDeError = $submodulo_obj;
					}
					else
					{
						$submodulo_obj->InitSubmodulo($this->datosEvolucion, $this->paso,'frm_'.$result->fields[0], $this->datosPaciente);
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
					$result->MoveNext();
				}
				$this->imprimir.=$vista_Nota;
				$result->close();
				if($this->realimprimir==2)
				{
					$this->PiePaginaImprimir();
				}
			}
			return true;
	}

	function Iniciar()
	{
		if(!IncludeLib('datospaciente')){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "No se pudo cargar la libreria de datos de pacientes.";
               return false;
    		}	
	
          if(!IncludeLib('historia_clinica')){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "No se pudo cargar la libreria de datos de Historia Clinica";
               return false;
          }

          if(!IncludeFile('classes/modules/hc_classmodules.class.php',true)){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "El archivo 'includes/historia_clinica.inc.php' no existe.";
               return false;
    		}
		$y=47;
          
		$this->datosPaciente = GetDatosPaciente('','','',$this->evolucion);
		$this->datosEvolucion=GetDatosEvolucion($this->evolucion);
		$this->cabecera();
          $vista_Nota = $this->Vista_NotaMedica();

          list($dbconn) = GetDBconn();
    
    		$query = "SELECT a.submodulo
              FROM historias_clinicas_templates a
              WHERE hc_modulo = '" . $this->datosEvolucion['hc_modulo'] . "'
              ORDER BY a.paso,a.secuencia;";
    
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
               return false;
          }

          if ($result->EOF) {
               $this->salida .= "HISTORIA CLINICA VACIA";
               return true;
          }
          else
          {
               while(!$result->EOF)
               {
                    $submodulo_obj=IncluirSubModuloHC($result->fields[0]);
                    if(!is_object($submodulo_obj)){
                         $this->error = "Error al cargar el submodulo";
                         $this->mensajeDeError = $submodulo_obj;
                    }
                    else
                    {
                         $submodulo_obj->InitSubmodulo($this->datosEvolucion, $this->paso,'frm_'.$result->fields[0], $this->datosPaciente);
                         $dato=$submodulo_obj->GetConsulta();
                         if($dato!=1)
                         {
                              $this->salida.=$dato;
                         }
                    }
                    unset($dato);
                    unset($submodulo_obj);
                    $result->MoveNext();
               }
               $this->salida.=$vista_Nota;
               $result->close();
          }
          return true;
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

     
	
     function PartirFecha($fecha)
	{
		$a=explode('-',$fecha);
		$b=explode(' ',$a[2]);
		$c=explode(':',$b[1]);
		$d=explode('.',$c[2]);
		return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
	}

     //SOLO PARA LA SOS//
     function Direccion_IPS($unidad)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $query = "SELECT descripcion, ubicacion, telefono 
                    FROM unidades_funcionales
                    WHERE unidad_funcional = '".$unidad."';";
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
          $Dir_Ips = $resultado->FetchRow();
		return $Dir_Ips;
     }

     function Datos_Adicionales_Pacientes($paciente_id, $tipo_id_paciente)
     {
     	GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $query = "SELECT A.descripcion AS zona_r, B.grupo_sanguineo, B.rh,
                           C.descripcion AS estado_civil, D.ocupacion_descripcion, 
                           E.direccion_trabajo, E.telefono_trabajo, E.nombre_aviso, 
                           E.telefono_aviso
                    FROM pacientes AS F 
                    LEFT JOIN pacientes_grupo_sanguineo AS B ON (F.paciente_id = B.paciente_id AND F.tipo_id_paciente = B.tipo_id_paciente AND estado = '1')
                    LEFT JOIN tipo_estado_civil AS C ON (F.tipo_estado_civil_id = C.tipo_estado_civil_id)
                    LEFT JOIN pacientes_datos_adicionales AS E ON (F.paciente_id = E.paciente_id AND F.tipo_id_paciente = E.tipo_id_paciente)
                    LEFT JOIN ocupaciones AS D ON (F.ocupacion_id = D.ocupacion_id),
                    	zonas_residencia AS A
                    WHERE F.paciente_id = '".$paciente_id."'
                    AND F.tipo_id_paciente = '".$tipo_id_paciente."'
                    AND F.zona_residencia = A.zona_residencia;";
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
               $this->mensajeDeError = "Ocurri? un error al intentar obtener los datos del usuario.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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

		function CargarVariables()
		{
     	$this->realimprimir=1;
          list($dbconn) = GetDBconn();
		$query = "SELECT ingreso FROM hc_evoluciones WHERE evolucion_id='".$this->evolucion."';";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$i=0;
			while (!$result->EOF)
			{
				$datos[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
 		$this->ingreso = $datos[0][ingreso];
		
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
          
          $this->Datos_Adicionales_Pacientes($this->datosPaciente[paciente_id], $this->datosPaciente[tipo_id_paciente]);
          
          $this->User = $this->GetDatosUsuarioSistema(UserGetUID());
          
          return true;
     }

}//fin clase

?>
