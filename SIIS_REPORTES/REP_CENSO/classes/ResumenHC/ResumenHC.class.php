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

	function ResumenHC()
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

	function GetDatosResponsable()
	{
		list($dbconn) = GetDBconn();
		$datosEvol = GetDatosEvolucion($this->evolucion);
		$sql="SELECT a.plan_id, a.tipo_afiliado_id, a.rango, a.semanas_cotizadas, b.plan_descripcion, b.tipo_tercero_id, b.tercero_id, b.num_contrato, c.nombre_tercero, X.tipo_afiliado_nombre
				FROM cuentas as a LEFT JOIN tipos_afiliado AS X ON (a.tipo_afiliado_id = X.tipo_afiliado_id), planes as b, terceros as c
				WHERE
				a.plan_id = b.plan_id
				AND b.tercero_id = c.tercero_id
				AND b.tercero_id = c.tercero_id
				AND a.numerodecuenta = ".$datosEvol['numerodecuenta'].";";

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
			$Responsable = $resultado->FetchRow();
		}
		return $Responsable;
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

     
	function GetDatosProfesional($evolucion)
	{
		list($dbconn) = GetDBconn();
		$sql="select b.tercero_id, b.tipo_id_tercero, b.nombre_tercero, c.tarjeta_profesional from profesionales_usuarios as a, terceros as b, profesionales as c where a.usuario_id=".$this->datosEvolucion['usuario_id']." and a.tercero_id=b.tercero_id and a.tipo_tercero_id=b.tipo_id_tercero and b.tipo_id_tercero=c.tipo_id_tercero and b.tercero_id=c.tercero_id;";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0) {
        return false;
    }
		while(!$result->EOF)
		{
			$profesional=$result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $profesional;
	}

     /*********************************************************************************************/
     /*********************************************************************************************/
     
	function Datos_Ingreso()
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
                         //return false;
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
               $result->close();
               $this->imprimir.=$vista_Nota;
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
                    /*if($result->fields[0]!='Apoyos_Diagnosticos')
                    {*/
                         $submodulo_obj=IncluirSubModuloHC($result->fields[0]);
                         if(!is_object($submodulo_obj)){
                              $this->error = "Error al cargar el submodulo";
                              $this->mensajeDeError = $submodulo_obj;
                              //return false;
                         }
                         else
                         {
                              $submodulo_obj->InitSubmodulo($this->datosEvolucion, $this->paso,'frm_'.$result->fields[0], $this->datosPaciente);
                              $dato=$submodulo_obj->GetConsulta();
                              if($dato!=1)
                              {
                                   $this->salida.=$dato;
                              }
                              //borrar en cualquier momento
                              if($result->fields[0]=='ControlPrenatal')
                              {break;}
                         }
                    //}
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

}//fin clase

?>
