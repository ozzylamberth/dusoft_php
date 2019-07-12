<?php

class ImpresionHistoria
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
	var $centro_remision;
     	
	function ImpresionHistoria()
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

          $this->Datosingreso = GetDatosIngreso($this->ingreso);

          $this->datosPaciente = $this->GetDatosPaciente("","",$this->ingreso,"");

     	$this->EvolucionGeneral = GetDatosEvolucion($this->evolucion);

          $this->tipo_profesional = GetTipoProfesional();
          
		$this->Datos_Ingreso();
 
          $this->GetDatosResponsable();
          
          $this->GetDatosProfesional();
          
          $this->User = $this->GetDatosUsuarioSistema(UserGetUID());
          
          return true;
     }
     
     function &GetDatosPaciente($pacienteId='',$tipoIdPaciente='',$ingreso='',$evolucion='')
     {
          if((empty($pacienteId) || empty($tipoIdPaciente)) && empty($ingreso) && empty($evolucion))
          {
               return false;
          }
          static $DatosPacientesTipo_id;
          static $DatosPacientesIngreso;
          static $DatosPacientesEvolucion;
     
          if($pacienteId!="" && $tipoIdPaciente!="")
          {
               if(!$DatosPacientesTipo_id[$tipoIdPaciente][$pacienteId])
               {
                    GLOBAL $ADODB_FETCH_MODE;
                    list($dbconn) = GetDBconn();
                    $query="SELECT  c.primer_apellido, c.segundo_apellido, c.primer_nombre,
                         c.segundo_nombre, c.sexo_id, c.fecha_nacimiento, c.residencia_direccion,
                         c.paciente_id, c.tipo_id_paciente,
                         b.historia_prefijo, b.historia_numero,
                         c.residencia_telefono, c.tipo_pais_id, c.tipo_dpto_id,
                         c.tipo_mpio_id
                         FROM historias_clinicas as b, pacientes as c
                         WHERE c.paciente_id='$pacienteId'
                         and c.tipo_id_paciente='$tipoIdPaciente'
                         and b.paciente_id=c.paciente_id
                         and b.tipo_id_paciente=c.tipo_id_paciente;";
     
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $result = $dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
                    if ($dbconn->ErrorNo() != 0) return false;
     
                    if ($result->EOF)
                    {
                         $query="SELECT primer_apellido, segundo_apellido, primer_nombre,
                              segundo_nombre, sexo_id, fecha_nacimiento, residencia_direccion,
                              paciente_id, tipo_id_paciente,
                              residencia_telefono, tipo_pais_id, tipo_dpto_id,tipo_mpio_id
                              FROM pacientes
                              WHERE paciente_id='$pacienteId'
                              and tipo_id_paciente='$tipoIdPaciente';";
     
                         $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                         $result = $dbconn->Execute($query);
                         $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
                         if ($dbconn->ErrorNo() != 0) return false;
                         if ($result->EOF) return false;
                    }
     
                    $datos=$result->FetchRow();
                    $result->Close();
     
                    $DatosPacientesTipo_id[$tipoIdPaciente][$pacienteId] = &$datos;
                    $DatosUbicacion=$this->GetInfoUbicacion($datos['tipo_pais_id'],$datos['tipo_dpto_id'],$datos['tipo_mpio_id']);
                    $DatosPacientesTipo_id[$tipoIdPaciente][$pacienteId]['municipio']=$DatosUbicacion['municipio'];
                    $DatosPacientesTipo_id[$tipoIdPaciente][$pacienteId]['departamento']=$DatosUbicacion['departamento'];
                    $DatosPacientesTipo_id[$tipoIdPaciente][$pacienteId]['pais']=$DatosUbicacion['pais'];
               }
               return $DatosPacientesTipo_id[$tipoIdPaciente][$pacienteId];
          }
     
          if(!empty($ingreso))
          {
               if(!$DatosPacientesIngreso[$ingreso])
               {
                    GLOBAL $ADODB_FETCH_MODE;
                    list($dbconn) = GetDBconn();
     
     
     
                    $query="SELECT c.primer_apellido, c.segundo_apellido, c.primer_nombre, c.segundo_nombre,
                         c.sexo_id, c.fecha_nacimiento, c.residencia_direccion, c.paciente_id,
                         c.tipo_id_paciente, b.historia_prefijo,
                         b.historia_numero, c.residencia_telefono, c.tipo_pais_id, c.tipo_dpto_id, c.tipo_mpio_id
     
                         FROM pacientes as c , historias_clinicas as b, ingresos as a
     
                         WHERE a.ingreso=$ingreso
                         and c.paciente_id=a.paciente_id
                         and c.tipo_id_paciente=a.tipo_id_paciente
     
                         and b.paciente_id=c.paciente_id
                         and b.tipo_id_paciente=c.tipo_id_paciente;";
     
     
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $result = $dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if ($dbconn->ErrorNo() != 0) return false;
                    if ($result->EOF)
                    {
                         $query="SELECT c.primer_apellido, c.segundo_apellido, c.primer_nombre, c.segundo_nombre,
                              c.sexo_id, c.fecha_nacimiento, c.residencia_direccion, c.paciente_id,
                              c.tipo_id_paciente, c.residencia_telefono, c.tipo_pais_id, c.tipo_dpto_id, c.tipo_mpio_id
     
                              FROM pacientes as c , ingresos as a
     
                              WHERE a.ingreso=".$ingreso."
                              and c.paciente_id=a.paciente_id
                              and c.tipo_id_paciente=a.tipo_id_paciente";
     
                         $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                         $result = $dbconn->Execute($query);
                         $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
                         if ($dbconn->ErrorNo() != 0) return false;
                         if ($result->EOF) return false;
                    }
     
                    $datos=$result->FetchRow();
                    $result->Close();
                    $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']] = &$datos;
                    $DatosUbicacion=$this->GetInfoUbicacion($datos['tipo_pais_id'],$datos['tipo_dpto_id'],$datos['tipo_mpio_id']);
                    $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']]['municipio']=$DatosUbicacion['municipio'];
                    $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']]['departamento']=$DatosUbicacion['departamento'];
                    $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']]['pais']=$DatosUbicacion['pais'];
                    $DatosPacientesIngreso[$ingreso]['tipo_id_paciente']=$datos['tipo_id_paciente'];
                    $DatosPacientesIngreso[$ingreso]['paciente_id']=$datos['paciente_id'];
               }
               return $DatosPacientesTipo_id[$DatosPacientesIngreso[$ingreso]['tipo_id_paciente']][$DatosPacientesIngreso[$ingreso]['paciente_id']];
          }
     
          if(!empty($evolucion))
          {
               if(!$DatosPacientesEvolucion[$evolucion])
               {
                    GLOBAL $ADODB_FETCH_MODE;
                    list($dbconn) = GetDBconn();
     
                    $query="SELECT c.primer_apellido, c.segundo_apellido, c.primer_nombre,
                         c.segundo_nombre, c.sexo_id, c.fecha_nacimiento, c.residencia_direccion,
                         c.paciente_id, c.tipo_id_paciente,
                         e.historia_prefijo, e.historia_numero,
                         c.residencia_telefono, c.tipo_pais_id, c.tipo_dpto_id,
                         c.tipo_mpio_id
                         FROM hc_evoluciones as b, historias_clinicas as e,
                         pacientes as c, ingresos as a
                         WHERE b.evolucion_id=$evolucion and a.ingreso=b.ingreso
                         and a.tipo_id_paciente=c.tipo_id_paciente and   a.paciente_id=c.paciente_id
                         and c.tipo_id_paciente=e.tipo_id_paciente and   c.paciente_id=e.paciente_id;";
     
     
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $result = $dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if ($dbconn->ErrorNo() != 0) return false;
                    if ($result->EOF)
                    {
                         $query="SELECT c.primer_apellido, c.segundo_apellido, c.primer_nombre,
                              c.segundo_nombre, c.sexo_id, c.fecha_nacimiento, c.residencia_direccion,
                              c.paciente_id, c.tipo_id_paciente,
                              c.residencia_telefono, c.tipo_pais_id, c.tipo_dpto_id,
                              c.tipo_mpio_id
                              FROM hc_evoluciones as b, pacientes as c, ingresos as a
                              WHERE b.evolucion_id=$evolucion and a.ingreso=b.ingreso
                              and c.paciente_id=a.paciente_id and c.tipo_id_paciente=a.tipo_id_paciente;";
     
                         $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                         $result = $dbconn->Execute($query);
                         $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
                         if ($dbconn->ErrorNo() != 0) return false;
                         if ($result->EOF) return false;
                    }
     
                    $datos=$result->FetchRow();
                    $result->Close();
                    $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']] = &$datos;
                    $DatosUbicacion=$this->GetInfoUbicacion($datos['tipo_pais_id'],$datos['tipo_dpto_id'],$datos['tipo_mpio_id']);
                    $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']]['municipio']=$DatosUbicacion['municipio'];
                    $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']]['departamento']=$DatosUbicacion['departamento'];
                    $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']]['pais']=$DatosUbicacion['pais'];
                    $DatosPacientesEvolucion[$evolucion]['tipo_id_paciente']=$datos['tipo_id_paciente'];
                    $DatosPacientesEvolucion[$evolucion]['paciente_id']=$datos['paciente_id'];
               }
               return $DatosPacientesTipo_id[$DatosPacientesEvolucion[$evolucion]['tipo_id_paciente']][$DatosPacientesEvolucion[$evolucion]['paciente_id']];
          }
     
          return false;
     }
    
     function GetInfoUbicacion($tipo_pais_id,$tipo_dpto_id,$tipo_mpio_id)
     {
          static $datosUbicacion;
     
          if(empty($tipo_pais_id) || empty($tipo_dpto_id) || empty($tipo_mpio_id))
          {
               return false;
          }
     
          if(!$datosUbicacion[$tipo_pais_id][$tipo_dpto_id][$tipo_mpio_id])
          {
               list($dbconn) = GetDBconn();
               GLOBAL $ADODB_FETCH_MODE;
     
               $sql=" SELECT municipio,departamento,pais
                         FROM tipo_mpios a, tipo_dptos b, tipo_pais c
                         WHERE a.tipo_mpio_id = '$tipo_mpio_id'
                         AND a.tipo_dpto_id='$tipo_dpto_id'
                         AND a.tipo_pais_id='$tipo_pais_id'
                         AND b.tipo_dpto_id=a.tipo_dpto_id
                         AND b.tipo_pais_id=a.tipo_pais_id
                         AND c.tipo_pais_id=b.tipo_pais_id";
     
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $result = $dbconn->Execute($sql);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
               if ($dbconn->ErrorNo() != 0) return false;
               if ($result->EOF) return false;
               $fila= $result->FetchRow();
               $result->Close();
               $datosUbicacion[$tipo_pais_id][$tipo_dpto_id][$tipo_mpio_id]['municipio']=$fila['municipio'];
               $datosUbicacion[$tipo_pais_id][$tipo_dpto_id][$tipo_mpio_id]['departamento']=$fila['departamento'];
               $datosUbicacion[$tipo_pais_id][$tipo_dpto_id][$tipo_mpio_id]['pais']=$fila['pais'];
               unset($fila);
          }
          return $datosUbicacion[$tipo_pais_id][$tipo_dpto_id][$tipo_mpio_id];
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
     }
	
     function GetDatosResponsable()
	{
		list($dbconn) = GetDBconn();
         
          $sql="SELECT a.plan_id, a.tipo_afiliado_id, a.rango, a.semanas_cotizadas, b.plan_descripcion, b.tipo_tercero_id, b.tercero_id, b.num_contrato, c.nombre_tercero, X.tipo_afiliado_nombre
                FROM cuentas as a LEFT JOIN tipos_afiliado AS X ON (a.tipo_afiliado_id = X.tipo_afiliado_id), planes as b, terceros as c
                WHERE a.plan_id = b.plan_id
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
			   
			$sql=" SELECT  A.tipo_id_tercero, 
                                 A.tercero_id, 
                                 A.nombre,
               	                 A.tarjeta_profesional, 
                                 B.especialidad, 
                                 C.descripcion,
                                 A.firma
                    FROM    profesionales AS A,
                              	profesionales_usuarios AS E
                    LEFT JOIN profesionales_especialidades AS B ON(E.tipo_tercero_id=B.tipo_id_tercero AND E.tercero_id=B.tercero_id)
                    LEFT JOIN especialidades AS C ON(C.especialidad = B.especialidad)
                    WHERE  E.usuario_id =".$this->EvolucionGeneral['usuario_id']."
                    AND      E.tercero_id = A.tercero_id
                    AND      E.tipo_tercero_id = A.tipo_id_tercero;";   
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

     function GetServicio($departamento)
	{
		list($dbconn) = GetDBconn();
		$sql="select b.descripcion from departamentos as a, servicios as b where a.servicio=b.servicio and a.departamento='".$departamento."';";
		$result = $dbconn->Execute($sql);
		return $result->fields[0];
	}
     
     function Consulta_NotasMedicas()
	{
		list($dbconn) = GetDBconn();
		$query= "SELECT A.ingreso,
					 A.fecha_registro, A.evolucion_id,
					 A.nota_medica, B.nombre, B.usuario
				FROM notas_medicas AS A,
					system_usuarios AS B
				WHERE A.ingreso='".$this->ingreso."'
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
     
     function IncluirSubModuloHC($submodulo)
     {
          global $VISTA;
          if(empty($submodulo)){
               return false;
          }
          
          $fileName = "hc_modules/$submodulo/hc_$submodulo.php";
          
          if(!IncludeFile($fileName)){
               return "El archivo '$fileName' no existe.";
          }
          
          $className="$submodulo";
        
          if(!class_exists($className))
             return "La clase '$className' no existe.";
             
          $fileName = "hc_modules/$submodulo/hc_$submodulo"."_$VISTA.php";
          
          if(IncludeFile($fileName))
          {
            $className="$submodulo"."_$VISTA";
          
            if(!class_exists($className))
               return "La clase '$className' no existe.";
          }
          
          $SUBMODULO= new $className();
          
          return $SUBMODULO;
     }
    /**
    *
    */
    function IniciarImprimir()
    {
//      $this->imprimir .= "<table width=\"100%\">\n";
      $this->imprimir .= "  <thead>\n";
      $this->imprimir .= "    <tr>\n";
      $this->imprimir .= "      <th>\n";
      $this->CabeceraImprimir();
      $this->imprimir .= "      </th>\n";
      $this->imprimir .= "    </tr>\n";
      $this->imprimir .= "  </thead>\n";

      $vista_Nota = $this->Vista_NotaMedica();
      list($dbconn) = GetDBconn();
      $query = "SELECT A.*
                FROM  (
                        ( 
                          SELECT DISTINCT A.submodulo, 
                                  B.paso 
                          FROM    hc_evoluciones_submodulos AS A, 
                                  hc_evoluciones_submodulos_paso AS B 
                          WHERE   A.ingreso = ".$this->EvolucionGeneral['ingreso']."
                          AND     A.submodulo = B.submodulo
                        )
                        UNION ALL
                        (
                          SELECT  A.submodulo, 
                                  B.paso
                          FROM    system_hc_submodulos AS A,
                                  hc_evoluciones_submodulos_paso AS B
                          WHERE   A.sw_print_persist = '1'
                          AND     A.submodulo = B.submodulo
                        )
                      ) AS A
                ORDER BY A.paso ASC ";
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
				
        $this->imprimir .= "  <tbody>\n";
        $this->imprimir .= "    <tr>\n";
        $this->imprimir .= "      <td>\n";
      
        foreach($var as $k => $v)
        {						
          $submodulo_obj=$this->IncluirSubModuloHC($v[submodulo]);
          if(!is_object($submodulo_obj))
          {
            $this->error = "Error al cargar el submodulo";
            $this->mensajeDeError = $submodulo_obj;
          }
          else
          {
            $submodulo_obj->InicializarSubmodulo($this->EvolucionGeneral,'',$this->datosPaciente,'','','',$this->paso,'frm_'.$v[submodulo],'','','','');
           
          // print_r("<br><br><br><br><br>jjjjj");
            //print_r($submodulo_obj);
           if(method_exists($submodulo_obj,'GetReporte_Html'))
            {
              $dato1=$submodulo_obj->GetReporte_Html();
               //   print_r($dato1);
              
              
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
        $this->imprimir .= $vista_Nota;

        if($this->realimprimir==2) 
          $this->PiePaginaImprimir();
        else
        {
          $this->imprimir .= "      </td>\n";
          $this->imprimir .= "    </tr>\n";
          $this->imprimir .= "  </tbody>\n";
        }
      }
      $this->imprimir .= "</table>\n";
      $this->GetImpresion();
      return true;
    }
    /**
    * Funcion donde se convierte la fecha que esta en formato YYYY-DD-MM a
    * DD/MM/YYYY
    * 
    * @param string $fecha Fecha a convertir
    *
    * @return string
    */
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
