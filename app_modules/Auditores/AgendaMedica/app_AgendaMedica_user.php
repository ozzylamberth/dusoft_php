<?php

/**
* Modulo de Consulta Externa.
*
* Modulo para asignar, Cumplir, Atender y Cancelar Citas
* @author Jaime Andres Valencia Salazar <salazarvaljandresv@yahoo.es>
* @version 1.0
* @package SIIS
*/


/**
* AgendaMedica
*
* Clase para accesar los metodos privados de la clase de presentaciï¿½, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserciï¿½ y la consulta de la agenda medica.
* .
*/


class app_AgendaMedica_user extends classModulo
{

	/**
	* Esta funcion Inicializa las variable de la clase
	*
	* @access public
	* @return boolean Para identificar que se realizo.
	*/
	function app_AgendaMedica_user()
	{
		return true;
	}



/**
* Esta funcion es la que se llama de manera inicial, en donde se llama la funcion de menu que muestra las opciones del usuario para accesar ha consulta externa
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function main()
	{
		$this->Menu();
		return true;
	}



/**
* Esta funcion es la que instancia las clases necesarias para realizar la impresion de la informacion de la cita para la atencion.
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function FuncionParaImprimir()
	{
		$var = $_REQUEST;
//print_r($var);
		if (!IncludeFile("classes/reports/reports.class.php"))
		{
			$this->error = "No se pudo inicializar la Clase de Reportes";
      $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
			return false;
    }

				$classReport = new reports;
				$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='AgendaMedica',$reporte_name='tiquete_cita',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
        if(!$reporte)
				{
             $this->error = $classReport->GetError();
             $this->mensajeDeError = $classReport->MensajeDeError();
             unset($classReport);
             return false;
        }

        $resultado=$classReport->GetExecResultado();
        unset($classReport);

        if(!empty($resultado[codigo]))
				{
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }
				//$this->ListadoPacientesEvolucionCerrada();
		$this->PantallaFinal();
    return true;
}


/**
* Esta funcion revisa las atenciones anteriores que ha tenido el paciente en la institucion
*
* @access public
* @return int valor entre 0 y muchos cuando existe mas de una atencion.
* @param string tipo de documento
* @param string numero de documento
*/

	function BusquedaAtencionesAnterioresH($tipoidpaciente,$pacienteid)
	{
			list($dbconn) = GetDBconn();
			$sql="select count(*) from ingresos a, hc_evoluciones as b where a.ingreso=b.ingreso and a.tipo_id_paciente='".$tipoidpaciente."' and a.paciente_id='".$pacienteid."';";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $result->fields[0];
	}




/**
* Esta funcion revisa si el paciente tiene historia clinica en la institucion
*
* @access public
* @return int valor entre 0 y 1 para conocer si existe historia clinica del paciente.
* @param string tipo de documento
* @param string numero de documento
*/



	function BusquedaHistoriaClinicaAnterior($tipoidpaciente,$pacienteid)
	{
		list($dbconn) = GetDBconn();
		$sql="select count(*) from historias_clinicas where tipo_id_paciente='".$tipoidpaciente."' and paciente_id='".$pacienteid."';";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
	  {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
		if(!empty($result->fields[0]))
		{
			return $result->fields[0];
		}
		$sql="select count(*) from pacientes_historias_anteriores where tipo_id_paciente='".$tipoidpaciente."' and paciente_id='".$pacienteid."';";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
	  {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
		return $result->fields[0];
	}



/**
* Esta funcion busca la informacion importante que se necesita del plan para la impresion del baucher de la cita
*
* @access public
* @return array informacion del plan para la impresion del baucher.
* @param int identificacion del plan
*/



	function BusquedaResponsable($plan)
	{
		list($dbconn) = GetDBconn();
		//cambio dar
		$sql="select a.plan_descripcion, b.nombre_tercero, a.horas_cancelacion
					from planes as a, terceros as b
					where a.plan_id=$plan and a.tercero_id=b.tercero_id
					and a.tipo_tercero_id=b.tipo_id_tercero;";
		//fin cambio dar
		/*$sql="select a.plan_descripcion, b.nombre_tercero, a.telefono_cancelacion_cita,
					a.horas_cancelacion
					from planes as a, terceros as b
					where a.plan_id=$plan and a.tercero_id=b.tercero_id
					and a.tipo_tercero_id=b.tipo_id_tercero;";*/
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$dat['nombreres']=$result->fields[0];
		$dat['nombreter']=$result->fields[1];
		$dat['horasc']=$result->fields[2];
		//$dat['telefonocan']=$result->fields[2];
		//$dat['horasc']=$result->fields[3];
		return $dat;
	}

	//nuevo dar
	function BuscarDatosUnidad($dpto)
	{
			list($dbconn) = GetDBconn();
			$sql="select b.text1, b.ubicacion, b.telefono
						from departamentos as a, unidades_funcionales as b
						where a.departamento='$dpto'
						and a.unidad_funcional=b.unidad_funcional
						and a.empresa_id=b.empresa_id";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$var=$result->GetRowAssoc($ToUpper = false);
			$result->Close();
			return $var;
	}
	//fin canuevo dar


/**
* Esta funcion busca el nombre del usuario para imprimirlo en el baucher de la cita
*
* @access public
* @return string nombre del usuario que asigno la cita.
*/


	function BusquedaNomUsuario()
	{
		list($dbconn) = GetDBconn();
		$sql="select nombre from system_usuarios where usuario_id=".UserGetUID().";";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $result->fields[0];
	}



/**
* Esta funcion busca los diferentes motivos de consulta que se generaron en una atencion especifica
*
* @access public
* @return array informacion del motivo de consulta.
* @param int identificacion de la atencion
*/

	function BusquedaMotivos($evolucion)
	{
		list($dbconn) = GetDBconn();
		$sql="select a.hc_motivo_consulta_id, a.descripcion from hc_motivo_consulta as a where a.evolucion_id=".$evolucion.";";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
	  {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
		while(!$result->EOF)
		{
			$motivos[$result->fields[0]]=$result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $motivos;
	}


/**
* Esta funcion busca los diferentes diagnosticos que se generaron en una atencion especifica
*
* @access public
* @return array informacion del diagnostico.
* @param int identificacion de la atencion
*/

	function BusquedaDiagnosticos($evolucion)
	{
		list($dbconn) = GetDBconn();
		$sql="select b.diagnostico_id, b.diagnostico_nombre from hc_diagnosticos_ingreso as a join diagnosticos as b on(a.tipo_diagnostico_id=b.diagnostico_id) where a.evolucion_id=".$evolucion." LIMIT 1 OFFSET 0;";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
	  {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
		while(!$result->EOF)
		{
			$diagnosticos[$result->fields[0]]=$result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $diagnosticos;
	}



/**
* Esta funcion busca los diferentes tipos de atencion, entre ellos son atencion por soat y atencion por enfermedad profesional
*
* @access public
* @return array informacion del diagnostico.
* @param string tipo de identificacion
* @param string identificacion del paciente
*/

	function BusquedaAtencionRiesgo($TipoId,$PacienteId)
	{
		list($dbconn) = GetDBconn();
		$query = "select d.tipo_atencion_id, d.detalle, b.evolucion_id, date(b.fecha) from ingresos as a join hc_evoluciones as b on(a.paciente_id='".$PacienteId."' and a.tipo_id_paciente='".$TipoId."' and a.ingreso=b.ingreso and date(b.fecha)<=date(now())) join hc_atencion as c on(b.evolucion_id=c.evolucion_id) join hc_tipos_atencion as d on(c.tipo_atencion_id=d.tipo_atencion_id and (d.tipo_atencion_id='14' or d.tipo_atencion_id='02'));";
		//echo $query1 = "select d.tipo_atencion_id, d.detalle, b.evolucion_id, date(b.fecha) from ingresos as a join hc_evoluciones as b on(a.paciente_id='".$PacienteId."' and a.tipo_id_paciente='".$TipoId."' and a.ingreso=b.ingreso and date(b.fecha)&lt;date(now())) join hc_atencion as c on(b.evolucion_id=c.evolucion_id) join hc_tipos_atencion as d on(c.tipo_atencion_id=d.tipo_atencion_id and (d.tipo_atencion_id='14' or d.tipo_atencion_id='02'));";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
	  {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
		else
		{
			if(!$result->EOF)
			{
				$i=0;
				while(!$result->EOF)
				{
					$atencion[0][$i]=$result->fields[0];
					$atencion[1][$i]=$result->fields[1];
					$atencion[2][$i]=$result->fields[2];
					$atencion[3][$i]=$result->fields[3];
					$i++;
					$result->MoveNext();
				}
			}
		}
		return $atencion;
	}


	
/**
* Esta funcion busca si es la primera atencion del paciente en la institucion falta desarrollo de la misma.
*
* @access public
* @return boolean que se realizo la funcion.
*/

	function BusquedaAtencionesPrimeraVez()
	{
		list($dbconn) = GetDBconn();
		$sql="select count(*) from hc_evoluciones as a, ingresos as b where a.ingreso=b.ingreso and b.paciente_id='".$jjj."' and b.tipo_id_paciente='CC';";
		return true;
	}



	
/**
* Esta funcion implementa el llamado a la clase estandar para la peticion de los datos comletos de un paciente, teniendo en cuenta el regreso de la clase a la funcion.
*
* @access public
* @return boolean true si se realizo con exito la funcion y false si no fue asi
*/

	function PedirDatosPaciente()
	{
		if(empty($_SESSION['CumplirCita']['paciente_id']))
		{
			$_SESSION['CumplirCita']['paciente_id']=$_REQUEST['CUMPLIR']['paciente'];
			$_SESSION['CumplirCita']['tipo_id_paciente']=$_REQUEST['CUMPLIR']['tipo_id_paciente'];
			$_SESSION['CumplirCita']['plan_id']=$_REQUEST['CUMPLIR']['plan'];
			$_SESSION['CumplirCita']['numero_orden_id']=$_REQUEST['CUMPLIR']['numero_orden_id'];
		}
		if($_SESSION['CumplirCita']['paciente_id']!=$_REQUEST['CUMPLIR']['paciente'])
		{
		  $_SESSION['CumplirCita']['paciente_id']=$_REQUEST['CUMPLIR']['paciente'];
			$_SESSION['CumplirCita']['tipo_id_paciente']=$_REQUEST['CUMPLIR']['tipo_id_paciente'];
			$_SESSION['CumplirCita']['plan_id']=$_REQUEST['CUMPLIR']['plan'];
			$_SESSION['CumplirCita']['numero_orden_id']=$_REQUEST['CUMPLIR']['numero_orden_id'];
		}
		list($dbconn) = GetDBconn();
		$sql="select centro_utilidad from departamentos where empresa_id='".$_SESSION['CumplirCita']['empresa']."' and departamento='".$_SESSION['CumplirCita']['departamento']."';";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$Paciente=$this->ReturnModuloExterno('app','Pacientes','user');
		if(!is_object($Paciente))
		{
				$this->error = "La clase Pacientes no se pudo instanciar";
				$this->mensajeDeError = "";
				return false;
		}

		$_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$_SESSION['CumplirCita']['paciente_id'];
		$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$_SESSION['CumplirCita']['tipo_id_paciente'];
		$_SESSION['PACIENTES']['PACIENTE']['plan_id']=$_SESSION['CumplirCita']['plan_id'];
		$_SESSION['PACIENTES']['RETORNO']['argumentos']=array('CUMPLIR'=>array('tipo_id_paciente'=>$_SESSION['CumplirCita']['tipo_id_paciente'],'paciente'=>$_SESSION['CumplirCita']['paciente_id'],'plan'=>$_SESSION['CumplirCita']['plan_id'],'numero_orden_id'=>$_SESSION['CumplirCita']['numero_orden_id']));
		$_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
		$_SESSION['PACIENTES']['RETORNO']['modulo']='AgendaMedica';
		$_SESSION['PACIENTES']['RETORNO']['tipo']='user';
		$_SESSION['PACIENTES']['RETORNO']['metodo']='PedirDatosPaciente';
		if(!$Paciente->BuscarIngresoActivoPaciente($_SESSION['CumplirCita']['tipo_id_paciente'] ,$_SESSION['CumplirCita']['paciente_id'] ,$_SESSION['CumplirCita']['empresa'] ,$_SESSION['CumplirCita']['plan_id'], $accion=array('contenedor'=>'app','modulo'=>'AgendaMedica','tipo'=>'user','metodo'=>'RetornoPaciente')))
		{
			$this->error = $Paciente->error ;
			$this->mensajeDeError = $Paciente->mensajeDeError;
			unset($Paciente);
			return false;
		}
		else
		{
			if(!$Paciente->TipoRetorno AND empty($_REQUEST['HOMONIMO']))
			{
				$this->salida .= $Paciente->GetSalida();
				unset($Paciente);
				return true;
			}
			else
			{
				$_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$_SESSION['CumplirCita']['paciente_id'];
				$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$_SESSION['CumplirCita']['tipo_id_paciente'];
				$_SESSION['PACIENTES']['PACIENTE']['plan_id']=$_SESSION['CumplirCita']['plan_id'];
				$_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
				$_SESSION['PACIENTES']['RETORNO']['modulo']='AgendaMedica';
				$_SESSION['PACIENTES']['RETORNO']['tipo']='user';
				$_SESSION['PACIENTES']['RETORNO']['metodo']='RetornoPaciente';
				$this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
			}
		}
		return true;
	}


/**
* Esta funcion es la que recibe el llamado de la clase paciente y la que realiza el cambio de estado de la orden del paciente.
*
* @access public
* @return boolean true si se realizo con exito y false si no fue asi
*/


	function RetornoPaciente()
	{
		list($dbconn) = GetDBconn();
		if(!empty($_SESSION['PACIENTES']['RETORNO']['PASO']))
		{
			$dbconn->BeginTrans();
			$sql="update os_maestro set sw_estado=3 where numero_orden_id=".$_SESSION['CumplirCita']['numero_orden_id'].";";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB a: " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			$dbconn->CommitTrans();
		}
		unset($_SESSION['CumplirCita']['rango']);
		unset($_SESSION['CumplirCita']['tipo_afiliado_id']);
		unset($_SESSION['CumplirCita']['semanas']);
		unset($_SESSION['CumplirCita']['numerodecuenta']);
		unset($_SESSION['CumplirCita']['numero_orden_id']);
		unset($_SESSION['CumplirCita']['paciente_id']);
		if(!empty($_SESSION['CumplirCita']['profesional']))
		{
			if($this->ListadoCitasCumplidas()==false)
			{
				return false;
			}
		}
		else
		{
			$this->BuscarCita();
		}
		return true;
	}



//si no saca ningun error se podria borrar

// 	function LiquidacionCita()
// 	{
// 		list($dbconn) = GetDBconn();
// 		$dbconn->BeginTrans();
// 		if($_SESSION['LiquidarCitas']['Existe'])
// 		{
// 			$sql="update pacientes set residencia_direccion='".$_SESSION['LiquidarCitas']['Direccion']."', residencia_telefono='".$_SESSION['LiquidarCitas']['Telefono']."' where paciente_id='".$_SESSION['LiquidarCitas']['Documento']."' and tipo_id_paciente='".$_SESSION['LiquidarCitas']['TipoDocumento']."';";
// 		}
// 		else
// 		{
// 			$sql="insert into pacientes(paciente_id, tipo_id_paciente, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, residencia_direccion, residencia_telefono, sexo_id, fecha_registro, tipo_pais_id, tipo_dpto_id, tipo_mpio_id, usuario_id) values ('".$_SESSION['LiquidarCitas']['Documento']."', '".$_SESSION['LiquidarCitas']['TipoDocumento']."', '".$_SESSION['LiquidarCitas']['PrimerApellido']."', '".$_SESSION['LiquidarCitas']['SegundoApellido']."', '".$_SESSION['LiquidarCitas']['PrimerNombre']."', '".$_SESSION['LiquidarCitas']['SegundoNombre']."', '".$_SESSION['LiquidarCitas']['Direccion']."', '".$_SESSION['LiquidarCitas']['Telefono']."', '".$_SESSION['LiquidarCitas']['Sexo']."', '".date("Y-m-d H:i:s")."', '".GetVarConfigAplication('DefaultPais')."','".GetVarConfigAplication('DefaultDpto')."','".GetVarConfigAplication('DefaultMpio')."',".$_SESSION['SYSTEM_USUARIO_ID'].");";
// 			$_SESSION['LiquidarCitas']['Existe']='1';
// 		}
// 		$result = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB a: " . $dbconn->ErrorMsg();
// 			$dbconn->RollbackTrans();
// 			return false;
// 		}
// 
// 		$sql="select a.tarifario_id, a.cargo from tarifarios_equivalencias as a, tarifarios_detalle as b, plan_tarifario as c where cargo_base='".$_SESSION['LiquidarCitas']['cargo_cups']."' and a.tarifario_id=b.tarifario_id and a.cargo=b.cargo and b.grupo_tarifario_id=c.grupo_tarifario_id and b.subgrupo_tarifario_id=c.subgrupo_tarifario_id and c.plan_id=".$_SESSION['LiquidarCitas']['Responsable']." and b.tarifario_id=c.tarifario_id;";
// 		$result = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB b: " . $dbconn->ErrorMsg();
// 			$dbconn->RollbackTrans();
// 			return false;
// 		}
// 		$tarifario=$result->fields[0];
// 		$cargo=$result->fields[1];
// 		$sql="select nextval('hc_os_solicitudes_hc_os_solicitud_id_seq');";
// 		$result1 = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB c: " . $dbconn->ErrorMsg();
// 			$dbconn->RollbackTrans();
// 			return false;
// 		}
// 		$sql="insert into hc_os_solicitudes (hc_os_solicitud_id, cargo, plan_id, os_tipo_solicitud_id, sw_estado) values (".$result1->fields[0].", '".$_SESSION['LiquidarCitas']['cargo_cups']."', ".$_SESSION['LiquidarCitas']['Responsable'].", 'CIT', '0');";
// 		$r = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB d: " . $dbconn->ErrorMsg();
// 			$dbconn->RollbackTrans();
// 			return false;
// 		}
// 		$sql="insert into hc_os_solicitudes_citas (hc_os_solicitud_id, tipo_consulta_id) values (".$result1->fields[0].", ".$_SESSION['LiquidarCitas']['TipoCita'].");";
// 		$r = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg();
// 			$dbconn->RollbackTrans();
// 			return false;
// 		}
// 		$sql="insert into hc_os_autorizaciones (hc_os_solicitud_id, autorizacion_int, autorizacion_ext) values (".$result1->fields[0].", ".$_SESSION['LiquidarCitas']['NumAutorizacion'].", ".$_SESSION['LiquidarCitas']['NumAutorizacion'].");";
// 		$r = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB f: " . $dbconn->ErrorMsg();
// 			$dbconn->RollbackTrans();
// 			return false;
// 		}
// 		$sql="select nextval('os_ordenes_servicios_orden_servicio_id_seq');";
// 		$result2 = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB g: " . $dbconn->ErrorMsg();
// 			$dbconn->RollbackTrans();
// 			return false;
// 		}
// 		$_SESSION['LiquidarCitas']['servicio']=$this->BusquedaServicio($_SESSION['LiquidarCitas']['departamento']);
// 		$sql="insert into os_ordenes_servicios (orden_servicio_id, autorizacion_int, autorizacion_ext, plan_id, tipo_afiliado_id, rango, semanas_cotizadas, servicio, tipo_id_paciente, paciente_id, usuario_id, fecha_registro) values (".$result2->fields[0].", ".$_SESSION['LiquidarCitas']['NumAutorizacion'].", ".$_SESSION['LiquidarCitas']['NumAutorizacion'].", ".$_SESSION['LiquidarCitas']['Responsable'].", '".$_SESSION['LiquidarCitas']['tipo_afiliado_id']."', '".$_SESSION['LiquidarCitas']['rango']."', '".$_SESSION['LiquidarCitas']['semanas']."', '".$_SESSION['LiquidarCitas']['servicio']."', '".$_SESSION['LiquidarCitas']['TipoDocumento']."', '".$_SESSION['LiquidarCitas']['Documento']."', ".UserGetUID().", '".date("Y-m-d H:i:s")."');";
// 		$r = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB i: " . $dbconn->ErrorMsg();
// 			$dbconn->RollbackTrans();
// 			return false;
// 		}
// 		$sql="select nextval('os_maestro_numero_orden_id_seq');";
// 		$result4 = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB j: " . $dbconn->ErrorMsg();
// 			return false;
// 		}
// 		$sql="insert into os_maestro (numero_orden_id, orden_servicio_id, fecha_vencimiento, hc_os_solicitud_id, cargo_cups) values (".$result4->fields[0].", ".$result2->fields[0].", '".date("Y-m-d H:i:s")."', ".$result1->fields[0].",'".$_SESSION['LiquidarCitas']['cargo_cups']."');";
// 		$r = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB k: " . $dbconn->ErrorMsg();
// 			$dbconn->RollbackTrans();
// 			return false;
// 		}
// 		$sql="insert into os_internas(numero_orden_id, cargo, departamento) values(".$result4->fields[0].", '".$_SESSION['LiquidarCitas']['cargo_cups']."', '".$_SESSION['LiquidarCitas']['departamento']."');";
// 		$r = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB l: " . $dbconn->ErrorMsg();
// 			$dbconn->RollbackTrans();
// 			return false;
// 		}
// 		$sql="select nextval('os_maestro_cargos_os_maestro_cargos_id_seq');";
// 		$result5 = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB m: " . $dbconn->ErrorMsg();
// 			return false;
// 		}
// 		$sql="insert into os_maestro_cargos (os_maestro_cargos_id, numero_orden_id, tarifario_id, cargo) values (".$result5->fields[0].", ".$result4->fields[0].", '$tarifario', '$cargo');";
// 		$r = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB n: " . $dbconn->ErrorMsg();
// 			$dbconn->RollbackTrans();
// 			return false;
// 		}
// 
// 		/*$sql="insert into caja_ordenes_pago(plan_id, tarifario_id, cargo, tipo_servicio, paciente_id, tipo_id_paciente, fecha, tipo_id_afiliado, rango) values ('".$_SESSION['LiquidarCitas']['Responsable']."', '".$result->fields[0]."', '".$result->fields[1]."', ".$_SESSION['LiquidarCitas']['cita'].", '".$_SESSION['LiquidarCitas']['Documento']."', '".$_SESSION['LiquidarCitas']['TipoDocumento']."', '".date("Y-m-d H:i:s")."','".$_SESSION['LiquidarCitas']['tipo_afiliado_id']."', '".$_SESSION['LiquidarCitas']['rango']."');";
// 		$result = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 			$dbconn->RollbackTrans();
// 			return false;
// 		}*/
// 		$dbconn->CommitTrans();
// 		$this->PantallaFinal();
// 		return true;
// 	}




/**
* Esta funcion busca las diferentes cuentas por pagar que pueda tener el paciente a la hora de ser atendido
*
* @access public
* @return array informacion de cuentas por cobrar.
* @param string tipo de identificacion
* @param string identificacion del paciente
*/



	function CuentasxCobrar($Tipo,$Tercero)
	{
			list($dbconn) = GetDBconn();
			$query = "    select a.valor, a.saldo, a.fecha_vence, b.razon_social, c.descripcion
										from cuentasxcobrar as a, empresas as b, centros_utilidad as c
										where a.tipo_id_tercero='$Tipo' and a.tercero_id='$Tercero' and a.empresa_id='01'
										and a.empresa_id=b.empresa_id and b.empresa_id=c.empresa_id and
										a.centro_utilidad=c.centro_utilidad and a.saldo!=0";
			$result=$dbconn->Execute($query);
			$i=0;
			while(!$result->EOF)
			{
				$var[$i]=$result->GetRowAssoc($ToUpper = false);
				$i++;
				$result->MoveNext();
			}
			return $var;
	}


/**
* Esta funcion busca el listado de derechos que tiene un usuario para asignar y cancelar citas medicas
*
* @access public
* @return array informacion de los derechos que tiene el usuario.
* @param string url que identifica cual funcion es la que se accesa despues
*/

	function TipoConsulta($url)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		if(!empty($_SESSION['SYSTEM_USUARIO_ID']))
		{
			$sql="select b.tipo_consulta_id, e.descripcion as descripcion3, b.departamento, c.descripcion as descripcion2, d.empresa_id, d.razon_social as descripcion1, b.sw_anestesiologia, b.cargo_cups, b.sw_busqueda_citas from userpermisos_tipos_consulta as a, tipos_consulta as b, departamentos as c, empresas as d, tipos_servicios_ambulatorios as e where a.tipo_consulta_id=b.tipo_consulta_id and a.usuario_id=".$_SESSION['SYSTEM_USUARIO_ID']." and b.departamento=c.departamento and c.empresa_id=d.empresa_id and a.tipo_consulta_id=e.tipo_servicio_amb_id order by empresa_id,departamento,tipo_consulta_id;";
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no se ha registrado.";
			return false;
		}
		unset($_SESSION['SEGURIDAD']);
		if(empty($_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][0]))
		{
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($sql);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
			}
			else
			{
				while ($data = $result->FetchRow()) {
					$prueba6[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
					$i=1;
				}
			}
		}
		else
		{
			$i=1;
		}
		if($i<>0)
		{
			$mtz1[0]='Empresas';
			$mtz1[1]='Departamentos';
			$mtz1[2]='Tipos de Cita';
			$com[0]=$mtz1;
			$com[1]=$prueba6;
			if(empty($_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][0]))
			{
				$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][]=$mtz1;
				$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][2]=$prueba6;
			}
			if($url[4]=='LiquidarCitas')
			{
				$nombre='MATRIZ PARA LA LIQUIDACION DE CITAS';
			}
			else
			{
				if($url[3]=='BuscarPacienteCancelar')
				{
					$nombre='MATRIZ PARA CANCELAR CITAS';
				}
				else
				{
					$nombre='MATRIZ PARA LA ASIGNACION DE CITAS';
				}
			}
			$accion=ModuloGetURL('app','AgendaMedica','user','main');
			$this->salida.=gui_theme_menu_acceso($nombre,$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][0],$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][2],$url,$accion);
			return $com;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no tiene empresas para mostrar.";
			return false;
		}
	}



	function CitasEnFechaHora($fecha,$hora,$idcita)
	{
		list($dbconn) = GetDBconn();
		$sql="select d.descripcion, e.nombre_tercero, c.fecha_turno || ' ' || b.hora as fecha_completa from agenda_citas_asignadas as a, agenda_citas as b, agenda_turnos as c, tipos_consulta as d, terceros as e where c.fecha_turno='$fecha' and a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and a.sw_atencion!=1 and b.sw_estado_cancelacion=0 and c.sw_estado_cancelacion=0 and b.hora='$hora' and a.agenda_cita_asignada_id!=$idcita and c.tipo_consulta_id=d.tipo_consulta_id and c.tipo_id_profesional=e.tipo_id_tercero and c.profesional_id=tercero_id and a.paciente_id='".$_SESSION['AsignacionCitas']['Documento']."' and a.tipo_id_paciente='".$_SESSION['AsignacionCitas']['TipoDocumento']."';";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
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
				$citas[]=$result->GetRowAssoc(false);
				$result->MoveNext();
			}
		}
		return $citas;
	}





/**
* Esta funcion busca el listado de derechos que tiene un usuario para cumplir citas medicas
*
* @access public
* @return array informacion de los derechos que tiene el usuario.
* @param string url que identifica cual funcion es la que se accesa despues
*/

	function TipoConsultaCumplimiento($url)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		if(!empty($_SESSION['SYSTEM_USUARIO_ID']))
		{
			$sql="select b.tipo_consulta_id, e.descripcion as descripcion3, b.departamento, c.descripcion as  descripcion2, d.empresa_id, d.razon_social as descripcion1, b.cargo_cups from userpermisos_consultas_cumplimientos as a, tipos_consulta as b, departamentos as c, empresas as d, tipos_servicios_ambulatorios as e where a.tipo_consulta_id=b.tipo_consulta_id and a.usuario_id=".$_SESSION['SYSTEM_USUARIO_ID']." and b.departamento=c.departamento and c.empresa_id=d.empresa_id and b.tipo_consulta_id=e.tipo_servicio_amb_id order by empresa_id,departamento,tipo_consulta_id;";
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no se ha registrado.";
			return false;
		}
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($sql);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while ($data = $result->FetchRow()) {
				$prueba6[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
				$i=1;
			}
		}
		if($i<>0)
		{
			$mtz[0]='Empresas';
			$mtz[1]='Departamentos';
			$mtz[2]='Tipos de Cita';
			$com[0]=$mtz;
			$com[1]=$prueba6;
			$accion=ModuloGetURL('app','AgendaMedica','user','main');
			$this->salida.=gui_theme_menu_acceso('MATRIZ PARA EL CUMPLIMIENTO DE LAS CITAS',$com[0],$com[1],$url,$accion);
			return $com;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no tiene empresas para mostrar.";
			return false;
		}
	}



/**
* Esta funcion busca las citas que tenga el usuario hacia delante
*
* @access public
* @return array datos de las citas hacia delante que tenga el paciente.
* @param string tipo de identificacion paciente
* @param string identificacion paciente
*/


	/*function CitasPacienteIncumplidas()
	{
		list($dbconn) = GetDBconn();
		$sql="select a.agenda_cita_asignada_id, c.fecha_turno || ' ' || b.hora as fechacom, d.nombre, c.fecha_turno from agenda_citas_asignadas as a, agenda_citas as b, agenda_turnos as c, profesionales as d where a.paciente_id='".$_REQUEST['Documento']."' and a.tipo_id_paciente='CC' and a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and c.profesional_id=d.tercero_id and c.tipo_id_profesional=d.tipo_id_tercero and date(c.fecha_turno)>=date(now()) and c.tipo_consulta_id=".$_SESSION['CumplirCita']['cita']." and a.sw_atencion!=1 and b.sw_estado_cancelacion=0 order by (c.fecha_turno || ' ' || b.hora);";
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$Cita[0][$i]=$result->fields[0];
				$Cita[1][$i]=$result->fields[1];
				$Cita[2][$i]=$result->fields[2];
				$Cita[3][$i]=$result->fields[3];
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $Cita;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no tiene empresas para mostrar.";
			return false;
		}
	}*/




/**
* Esta funcion busca las citas que tenga el usuario hacia delante
*
* @access public
* @return array datos de las citas hacia delante que tenga el paciente.
* @param string tipo de identificacion paciente
* @param string identificacion paciente
*/

	function CitasAdelante($tipoid,$paciente)
	{
		list($dbconn) = GetDBconn();
		$sql="select c.fecha_turno || ' ' || b.hora as fecha, e.nombre_tercero, a.agenda_cita_asignada_id from agenda_citas_asignadas as a left join os_cruce_citas as g on (a.agenda_cita_asignada_id=g.agenda_cita_asignada_id) left join os_maestro as f on (g.numero_orden_id=f.numero_orden_id) left join os_ordenes_servicios as j on(f.orden_servicio_id=j.orden_servicio_id) left join os_maestro_cargos as h on(g.numero_orden_id=h.numero_orden_id) left join cuentas as i on(f.numerodecuenta=i.numerodecuenta), agenda_citas as b, agenda_turnos as c, profesionales as d, terceros as e where a.paciente_id='".$paciente."' and a.tipo_id_paciente='".$tipoid."' and a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and c.profesional_id=d.tercero_id and c.tipo_id_profesional=d.tipo_id_tercero and c.profesional_id=e.tercero_id and c.tipo_id_profesional=e.tipo_id_tercero and date(c.fecha_turno)>=date(now()) and a.sw_atencion!=1 and b.sw_estado_cancelacion=0 order by (c.fecha_turno || ' ' || b.hora);";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
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
				$adelante[0][$i]=$result->fields[0];
				$adelante[1][$i]=$result->fields[1];
				$i++;
				$result->MoveNext();
			}
		}
		return $adelante;
	}



/**
* Esta funcion busca las citas que tenga el usuario hacia delante
*
* @access public
* @return array datos de las citas hacia delante que tenga el paciente.
* @param string tipo de identificacion paciente
* @param string identificacion paciente
*/

	function CitasIncumplidasPaciente($tipoid,$paciente,$plan)
	{
		list($dbconn) = GetDBconn();
		if(!empty($plan))
		{
			$sql="select actividad_incumplimientos from planes where plan_id=$plan;";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$dato=$result->fields[0];
		}
		else
		{
			$dato=0;
		}
		$sql="select c.fecha_turno || ' ' || b.hora as fecha, f.nombre_tercero, a.agenda_cita_asignada_id
		from agenda_citas_asignadas as a, agenda_citas as b, agenda_turnos as c, profesionales as e, terceros as f, os_cruce_citas as h, os_maestro as i
		where h.numero_orden_id=i.numero_orden_id AND a.agenda_cita_asignada_id=h.agenda_cita_asignada_id AND a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and date(c.fecha_turno)<date(now()) and date(c.fecha_turno)>(date(now())-".$dato.") and a.paciente_id='$paciente' and tipo_id_paciente='$tipoid' and c.profesional_id=e.tercero_id and c.tipo_id_profesional=e.tipo_id_tercero and i.sw_estado!=3 and a.sw_atencion!=3 and e.tercero_id=f.tercero_id and e.tipo_id_tercero=f.tipo_id_tercero;";
		//echo $sql1="select c.fecha_turno || ' ' || b.hora as fecha, e.nombre, f.agenda_cita_asignada_id from agenda_citas_asignadas as a, agenda_citas as b, agenda_turnos as c, profesionales as e where a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and date(c.fecha_turno)&lt;date(now()) and date(c.fecha_turno)>(date(now())-".$result->fields[0].") and a.paciente_id='".$paciente."' and tipo_id_paciente='".$tipoid."' and c.profesional_id=e.tercero_id and c.tipo_id_profesional=e.tipo_id_tercero and a.sw_atencion!=3 and a.sw_estado!=1;";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
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
				$incumplida[0][$i]=$result->fields[0];
				$incumplida[1][$i]=$result->fields[1];
				$i++;
				$result->MoveNext();
			}
		}
		return $incumplida;
	}




/**
* Esta funcion busca el nombre completo del paciente
*
* @access public
* @return string nombre del paciente completo.
* @param string tipo de identificacion paciente
* @param string identificacion paciente
*/



	function BuscarNombrePaciente($tipoid,$paciente)
	{
		list($dbconn) = GetDBconn();
		$sql="select primer_nombre || ' ' || segundo_nombre || ' ' || primer_apellido || ' ' || segundo_apellido as nombre from pacientes where tipo_id_paciente='$tipoid' and paciente_id='$paciente'";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $result->fields[0];
	}




/**
* Esta funcion busca las diferentes citas que tenga para el dia actual en el tipo de cita en el que esta el usuario
*
* @access public
* @return array listado de las citas que tiene un paciente para el dia actual.
*/

	function CitasPacienteAtender()
	{
		list($dbconn) = GetDBconn();
		$sql="select a.agenda_cita_asignada_id, c.fecha_turno || ' ' || b.hora as
		fechacom, e.nombre_tercero as nombre, c.fecha_turno, f.sw_estado,
		a.agenda_cita_id, i.ingreso, j.plan_id, h.cargo, h.tarifario_id,
		f.numerodecuenta, j.tipo_afiliado_id, j.rango, j.semanas_cotizadas,
		j.autorizacion_int, j.autorizacion_ext, f.numero_orden_id, f.sw_estado,
		h.os_maestro_cargos_id, j.orden_servicio_id, f.cargo_cups
		from agenda_citas_asignadas as a left join os_cruce_citas
		as g on (a.agenda_cita_asignada_id=g.agenda_cita_asignada_id) left join
		os_maestro as f on (g.numero_orden_id=f.numero_orden_id) left join
		os_ordenes_servicios as j on(f.orden_servicio_id=j.orden_servicio_id) left join
		os_maestro_cargos as h on(g.numero_orden_id=h.numero_orden_id) left join cuentas
		as i on(f.numerodecuenta=i.numerodecuenta), agenda_citas as b, agenda_turnos as
		c, profesionales as d, terceros as e where
		a.paciente_id='".$_REQUEST['Documento']."' and
		a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."' and
		a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and
		c.profesional_id=d.tercero_id and c.tipo_id_profesional=d.tipo_id_tercero and
		c.profesional_id=e.tercero_id and c.tipo_id_profesional=e.tipo_id_tercero and
		date(c.fecha_turno)>=date(now()) and
		c.tipo_consulta_id=".$_SESSION['CumplirCita']['cita']." and a.sw_atencion!=1 and
		b.sw_estado_cancelacion=0 order by (c.fecha_turno || ' ' || b.hora);";
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$t=0;
				$s=0;
				while($t<sizeof($Cita[5]))
				{
					if($Cita[5][$t]==$result->fields[5]-1)
					{
						$s=1;
						break;
					}
					$t++;
				}
				if($s==0)
				{
					$Cita[0][$i]=$result->fields[0];
					$Cita[1][$i]=$result->fields[1];
					$Cita[2][$i]=$result->fields[2];
					$Cita[3][$i]=$result->fields[3];
					if($result->fields[4]==1)
					{
						$Cita[4][$i]=1;
					}
					elseif($result->fields[4]==2)
					{
						$Cita[4][$i]=3;
					}
					elseif($result->fields[4]==3)
					{
						$Cita[4][$i]=2;
					}
					elseif($result->fields[4]==5)
					{
						$Cita[4][$i]=5;
					}
					else
					{
					  $Cita[4][$i]=8;
					}
					$Cita[5][$i]=$result->fields[5];
					$Cita[6][$i]=$result->fields[7];//plan
					$Cita[7][$i]=$result->fields[8];
					$Cita[8][$i]=$result->fields[9];


					$Cita[9][$i]=$result->fields[10];//numerodecuenta
					$Cita[10][$i]=$result->fields[11];//tipo_afiliado_id
					$Cita[11][$i]=$result->fields[12];//rango
					$Cita[12][$i]=$result->fields[13];//semanas
					$Cita[13][$i]=$result->fields[14];
					$Cita[14][$i]=$result->fields[15];
					$Cita[15][$i]=$result->fields[16];//numero_orden_id
					$Cita[16][$i]=$result->fields[17];
					$Cita[17][$i]=$result->fields[18];
					$Cita[18][$i]=$result->fields[19];
					$Cita[20][$i]=$result->fields[20];//CUPS DAR
					$i++;
				}
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $Cita;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no tiene empresas para mostrar.";
			return false;
		}
	}


/**
* Esta funcion busca las diferentes citas que tenga para el dia actual en todos los tipos diferentes a donde esta el usuario
*
* @access public
* @return array listado de las citas que tiene un paciente para el dia actual.
*/

	function CitasPacienteAtender2()
	{
		list($dbconn) = GetDBconn();
		$sql="select a.agenda_cita_asignada_id, c.fecha_turno || ' ' || b.hora as
		fechacom, e.nombre_tercero as nombre, c.fecha_turno, f.sw_estado,
		a.agenda_cita_id, i.ingreso, j.plan_id, h.cargo, h.tarifario_id,
		f.numerodecuenta, j.tipo_afiliado_id, j.rango, j.semanas_cotizadas,
		j.autorizacion_int, j.autorizacion_ext, f.numero_orden_id, f.sw_estado,
		h.os_maestro_cargos_id, k.descripcion from agenda_citas_asignadas as a left join os_cruce_citas
		as g on (a.agenda_cita_asignada_id=g.agenda_cita_asignada_id) left join
		os_maestro as f on (g.numero_orden_id=f.numero_orden_id) left join
		os_ordenes_servicios as j on(f.orden_servicio_id=j.orden_servicio_id) left join
		os_maestro_cargos as h on(g.numero_orden_id=h.numero_orden_id) left join cuentas
		as i on(f.numerodecuenta=i.numerodecuenta), agenda_citas as b, agenda_turnos as
		c, profesionales as d, terceros as e, tipos_consulta as k where
		a.paciente_id='".$_REQUEST['Documento']."' and
		a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."' and
		a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and
		c.profesional_id=d.tercero_id and c.tipo_id_profesional=d.tipo_id_tercero and
		c.profesional_id=e.tercero_id and c.tipo_id_profesional=e.tipo_id_tercero and
		date(c.fecha_turno)>=date(now()) and
		c.tipo_consulta_id!=".$_SESSION['CumplirCita']['cita']." and c.tipo_consulta_id=k.tipo_consulta_id and a.sw_atencion!=1
		and
		b.sw_estado_cancelacion=0 order by (c.fecha_turno || ' ' || b.hora);";
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$t=0;
				$s=0;
				while($t<sizeof($Cita[5]))
				{
					if($Cita[5][$t]==$result->fields[5]-1)
					{
						$s=1;
						break;
					}
					$t++;
				}
				if($s==0)
				{
					$Cita[0][$i]=$result->fields[0];
					$Cita[1][$i]=$result->fields[1];
					$Cita[2][$i]=$result->fields[2];
					$Cita[3][$i]=$result->fields[3];
					if($result->fields[4]==1)
					{
						$Cita[4][$i]=1;
					}
					elseif($result->fields[4]==2)
					{
						$Cita[4][$i]=3;
					}
					elseif($result->fields[4]==3)
					{
						$Cita[4][$i]=2;
					}
					elseif($result->fields[4]==5)
					{
						$Cita[4][$i]=5;
					}
					else
					{
					  $Cita[4][$i]=8;
					}
					$Cita[5][$i]=$result->fields[5];
					$Cita[6][$i]=$result->fields[7];//plan
					$Cita[7][$i]=$result->fields[8];
					$Cita[8][$i]=$result->fields[9];


					$Cita[9][$i]=$result->fields[10];//numerodecuenta
					$Cita[10][$i]=$result->fields[11];//tipo_afiliado_id
					$Cita[11][$i]=$result->fields[12];//rango
					$Cita[12][$i]=$result->fields[13];//semanas
					$Cita[13][$i]=$result->fields[14];
					$Cita[14][$i]=$result->fields[15];
					$Cita[15][$i]=$result->fields[16];//numero_orden_id
					$Cita[16][$i]=$result->fields[17];
					$Cita[17][$i]=$result->fields[18];
					$Cita[18][$i]=$result->fields[19];
					$i++;
				}
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $Cita;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no tiene empresas para mostrar.";
			return false;
		}
	}



/**
* Esta funcion busca las diferentes citas que tenga para el dia actual con el nombre del paciente
*
* @access public
* @return array listado de las citas que tiene un paciente para el dia actual.
*/

	function CitasPacienteAtenderNombre()
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['nombres'])
		{
			$nombre=" AND k.primer_nombre || k.segundo_nombre || k.primer_apellido || k.segundo_apellido like '%".strtoupper($_REQUEST['nombres'])."%' ";
		}
		if($_REQUEST['Documento'] && $_REQUEST['TipoDocumento']){
      $doc=" AND a.paciente_id='".$_REQUEST['Documento']."' and a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."'";
		}
		$sql="select a.agenda_cita_asignada_id, c.fecha_turno || ' ' || b.hora as fechacom, e.nombre_tercero as nombre, c.fecha_turno, f.sw_estado, a.agenda_cita_id, i.ingreso, j.plan_id, h.cargo, h.tarifario_id, f.numerodecuenta, j.tipo_afiliado_id, j.rango, j.semanas_cotizadas, j.autorizacion_int, j.autorizacion_ext, f.numero_orden_id, f.sw_estado, h.os_maestro_cargos_id, j.orden_servicio_id, a.paciente_id, a.tipo_id_paciente,f.cargo_cups,
		k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombre_pac
		from agenda_citas_asignadas as a
		left join os_cruce_citas as g on (a.agenda_cita_asignada_id=g.agenda_cita_asignada_id)
		left join os_maestro as f on (g.numero_orden_id=f.numero_orden_id)
		left join os_ordenes_servicios as j on(f.orden_servicio_id=j.orden_servicio_id)
		left join os_maestro_cargos as h on(g.numero_orden_id=h.numero_orden_id)
		left join cuentas as i on(f.numerodecuenta=i.numerodecuenta)
		, agenda_citas as b, agenda_turnos as c, profesionales as d, terceros as e, pacientes as k
		where a.paciente_id=k.paciente_id  and a.tipo_id_paciente=k.tipo_id_paciente and a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and c.profesional_id=d.tercero_id and c.tipo_id_profesional=d.tipo_id_tercero and c.profesional_id=e.tercero_id and c.tipo_id_profesional=e.tipo_id_tercero and date(c.fecha_turno)>=date(now()) and c.tipo_consulta_id=".$_SESSION['CumplirCita']['cita']." and a.sw_atencion!=1 and b.sw_estado_cancelacion=0 $nombre $doc order by (c.fecha_turno || ' ' || b.hora);";

		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$t=0;
				$s=0;
				while($t<sizeof($Cita[5]))
				{
					if($Cita[5][$t]==$result->fields[5]-1)
					{
						$s=1;
						break;
					}
					$t++;
				}
				if($s==0)
				{
					$Cita[0][$i]=$result->fields[0];
					$Cita[1][$i]=$result->fields[1];
					$Cita[2][$i]=$result->fields[2];
					$Cita[3][$i]=$result->fields[3];
					if($result->fields[4]==1)
					{
						$Cita[4][$i]=1;
					}
					elseif($result->fields[4]==2)
					{
						$Cita[4][$i]=3;
					}
					elseif($result->fields[4]==3)
					{
						$Cita[4][$i]=2;
					}
					elseif($result->fields[4]==5)
					{
						$Cita[4][$i]=5;
					}
					else
					{
					  $Cita[4][$i]=8;
					}
					$Cita[5][$i]=$result->fields[5];
					$Cita[6][$i]=$result->fields[7];//plan
					$Cita[7][$i]=$result->fields[8];
					$Cita[8][$i]=$result->fields[9];


					$Cita[9][$i]=$result->fields[10];//numerodecuenta
					$Cita[10][$i]=$result->fields[11];//tipo_afiliado_id
					$Cita[11][$i]=$result->fields[12];//rango
					$Cita[12][$i]=$result->fields[13];//semanas
					$Cita[13][$i]=$result->fields[14];
					$Cita[14][$i]=$result->fields[15];
					$Cita[15][$i]=$result->fields[16];//numero_orden_id
					$Cita[16][$i]=$result->fields[17];
					$Cita[17][$i]=$result->fields[18];
					$Cita[18][$i]=$result->fields[19];
					$Cita[19][$i]=$result->fields[20];//paciente_id
					$Cita[20][$i]=$result->fields[21];//tipo_id_paciente
					$Cita[21][$i]=$result->fields[22];//CUPS DAR
					$Cita[22][$i]=$result->fields[23];//nombre paciente
					$i++;
				}
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $Cita;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no tiene empresas para mostrar.";
			return false;
		}
	}



/**
* Esta funcion busca las diferentes citas que tenga para el dia actual en todos los tipos diferentes a donde esta el usuario por nombre
*
* @access public
* @return array listado de las citas que tiene un paciente para el dia actual.
*/

	function CitasPacienteAtenderNombre2()
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['nombres'])
		{
			$nombre=" and (k.primer_nombre || k.segundo_nombre like '%".$_REQUEST['nombres']."%' ";
			if($_REQUEST['apellidos'])
			{
				$nombre.="OR k.primer_apellido || k.segundo_apellido like '%".$_REQUEST['apellidos']."%')";
			}
			else
			{
				$nombre.=")";
			}
		}
		else
		{
			if($_REQUEST['apellidos'])
			{
				$nombre=" and (k.primer_apellido || k.segundo_apellido like '%".$_REQUEST['apellidos']."%')";
			}
		}
		$sql="select a.agenda_cita_asignada_id, c.fecha_turno || ' ' || b.hora as fechacom, e.nombre_tercero as nombre, c.fecha_turno, f.sw_estado, a.agenda_cita_id, i.ingreso, j.plan_id, h.cargo, h.tarifario_id, f.numerodecuenta, j.tipo_afiliado_id, j.rango, j.semanas_cotizadas, j.autorizacion_int, j.autorizacion_ext, f.numero_orden_id, f.sw_estado, h.os_maestro_cargos_id
		from agenda_citas_asignadas as a
		left join os_cruce_citas as g on (a.agenda_cita_asignada_id=g.agenda_cita_asignada_id)
		left join os_maestro as f on (g.numero_orden_id=f.numero_orden_id)
		left join os_ordenes_servicios as j on(f.orden_servicio_id=j.orden_servicio_id)
		left join os_maestro_cargos as h on(g.numero_orden_id=h.numero_orden_id)
		left join cuentas as i on(f.numerodecuenta=i.numerodecuenta)
		, agenda_citas as b, agenda_turnos as c, profesionales as d, terceros as e, pacientes as k
		where a.paciente_id=k.paciente_id  and a.tipo_id_paciente=k.tipo_id_paciente and a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and c.profesional_id=d.tercero_id and c.tipo_id_profesional=d.tipo_id_tercero and c.profesional_id=e.tercero_id and c.tipo_id_profesional=e.tipo_id_tercero and date(c.fecha_turno)>=date(now()) and c.tipo_consulta_id!=".$_SESSION['CumplirCita']['cita']." and a.sw_atencion!=1 and b.sw_estado_cancelacion=0 $nombre order by (c.fecha_turno || ' ' || b.hora);";
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$t=0;
				$s=0;
				while($t<sizeof($Cita[5]))
				{
					if($Cita[5][$t]==$result->fields[5]-1)
					{
						$s=1;
						break;
					}
					$t++;
				}
				if($s==0)
				{
					$Cita[0][$i]=$result->fields[0];
					$Cita[1][$i]=$result->fields[1];
					$Cita[2][$i]=$result->fields[2];
					$Cita[3][$i]=$result->fields[3];
					if($result->fields[4]==1)
					{
						$Cita[4][$i]=1;
					}
					elseif($result->fields[4]==2)
					{
						$Cita[4][$i]=3;
					}
					elseif($result->fields[4]==3)
					{
						$Cita[4][$i]=2;
					}
					elseif($result->fields[4]==5)
					{
						$Cita[4][$i]=5;
					}
					else
					{
					  $Cita[4][$i]=8;
					}
					$Cita[5][$i]=$result->fields[5];
					$Cita[6][$i]=$result->fields[7];//plan
					$Cita[7][$i]=$result->fields[8];
					$Cita[8][$i]=$result->fields[9];


					$Cita[9][$i]=$result->fields[10];//numerodecuenta
					$Cita[10][$i]=$result->fields[11];//tipo_afiliado_id
					$Cita[11][$i]=$result->fields[12];//rango
					$Cita[12][$i]=$result->fields[13];//semanas
					$Cita[13][$i]=$result->fields[14];
					$Cita[14][$i]=$result->fields[15];
					$Cita[15][$i]=$result->fields[16];//numero_orden_id
					$Cita[16][$i]=$result->fields[17];
					$Cita[17][$i]=$result->fields[18];
					$i++;
				}
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $Cita;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no tiene empresas para mostrar.";
			return false;
		}
	}




/**
* Esta funcion revisa si existe algun permiso para que un usuario asigne o cancele citas
*
* @access public
* @return int identifica si existen permisos para un usuario.
*/

	function BusquedaAsignacionCancelacion()
	{
		list($dbconn) = GetDBconn();
		if(!empty($_SESSION['SYSTEM_USUARIO_ID']))
		{
			$sql="select userpermisos_asignacion(".$_SESSION['SYSTEM_USUARIO_ID'].")";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				return false;
			}
			return $result->fields[0];
		}
		return true;
	}

/**
* Esta funcion revisa si existe algun permiso para que un usuario cumpla citas
*
* @access public
* @return int identifica si existen permisos para un usuario.
*/
	function BusquedaCumplimiento()
	{
		list($dbconn) = GetDBconn();
		if(!empty($_SESSION['SYSTEM_USUARIO_ID']))
		{
			$sql="select userpermisos_cumplimiento(".$_SESSION['SYSTEM_USUARIO_ID'].")";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				return false;
			}
			return $result->fields[0];
		}
		return true;
	}

/**
* Esta funcion revisa si existe algun permiso para que un usuario atienda citas
*
* @access public
* @return int identifica si existen permisos para un usuario.
*/
	function BusquedaAtencionCitas()
	{
		list($dbconn) = GetDBconn();
		if(!empty($_SESSION['SYSTEM_USUARIO_ID']))
		{
			$sql="select userpermisos_atencion_citas(".$_SESSION['SYSTEM_USUARIO_ID'].")";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				return false;
			}
			return $result->fields[0];
		}
		return true;
	}



/**
* Esta funcion busca los permisos que tiene un profesional para atender citas
*
* @access public
* @return boolean identifica si existen permisos para un usuario.
*/

	function TiposCitasAtender()
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		if(!empty($_SESSION['SYSTEM_USUARIO_ID']))
		{
			$sql="select distinct(d.tipo_consulta_id), g.descripcion as descripcion3, e.departamento, e.descripcion as descripcion2, c.empresa_id, c.razon_social as descripcion1, b.profesional_id, b.tipo_id_profesional, h.nombre_tercero as nombre, date(now()) as DiaEspe, d.hc_modulo, d.bodega_unico, d.especialidad from profesionales_usuarios as a, agenda_turnos as b, empresas as c, tipos_consulta as d, departamentos as e, profesionales as f, tipos_servicios_ambulatorios as g, terceros as h where a.usuario_id=".$_SESSION['SYSTEM_USUARIO_ID']." and a.tipo_tercero_id=b.tipo_id_profesional and a.tercero_id=b.profesional_id and a.tipo_tercero_id=h.tipo_id_tercero and a.tercero_id=h.tercero_id and b.empresa_id=c.empresa_id and b.tipo_consulta_id=d.tipo_consulta_id and d.departamento=e.departamento and b.profesional_id=f.tercero_id and b.tipo_id_profesional=f.tipo_id_tercero and b.fecha_turno>=date(now()) and d.tipo_consulta_id=g.tipo_servicio_amb_id order by d.tipo_consulta_id;";
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no se ha registrado.";
			return false;
		}
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($sql);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while ($data = $result->FetchRow()) {
				$prueba6[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
				$i=1;
			}
		}
		if($i<>0)
		{
			$mtz[0]='Empresas';
			$mtz[1]='Departamentos';
			$mtz[2]='Tipos de Cita';
			$url[0]='app';
			$url[1]='AgendaMedica';
			$url[2]='user';
			$url[3]='AgendaDia';
			$url[4]='Atencion';
			$accion=ModuloGetURL('system','Menu','user','main');
			$this->salida.=gui_theme_menu_acceso('ATENCION DE CITAS',$mtz,$prueba6,$url,$accion);
			return true;
		}
		else
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El usuario no tiene empresas para mostrar.";
			return false;
		}
	}





/**
* Esta funcion redirecciona la de la historia clinica hacia una funcion html
*
* @access public
* @return boolean identifica si se pudo realizar la funcion de html.
*/

	function LLegadaHistoriaClinica()
	{
		$this->AgendaDia();
		return true;
	}



/**
* Esta funcion despliega el listado de pacientes que van ha ser atendidos
*
* @access public
* @return array listado de pacientes que van ha ser atendidos.
*/
	function ListadoCitasAtender()
	{
		$a=explode(",",$_SESSION['Atencion']['profesional']);
		list($dbconn) = GetDBconn();

		$query = "SELECT agenda_turno_id FROM agenda_turnos
							WHERE date(fecha_turno)=date('".$_SESSION['Atencion']['DiaEspe']."')
							and tipo_consulta_id=".$_SESSION['Atencion']['cita']."
							and profesional_id='".$a[1]."'
							and tipo_id_profesional='".$a[0]."'
							and empresa_id='".$_SESSION['Atencion']['empresa']."'";
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
				if($i==0)
				{  $var.=$resulta->fields[0];  $i=1;}
				else
				{  $var.=','.$resulta->fields[0];  }
				$resulta->MoveNext();
		}
		$resulta->Close();

		$sql="select b.hora, c.agenda_cita_id, c.tipo_id_paciente,
		c.paciente_id,null as nombre_completo, f.paciente_id || f.tipo_id_paciente as mirar,
		j.ingreso, i.evolucion_id, i.estado, k.sw_estado, c.observacion,k.cargo_cups
		from agenda_citas as b
		left join agenda_citas_asignadas as c on (b.agenda_cita_id=c.agenda_cita_id and c.sw_atencion!=1)
		left join historias_clinicas as f on (c.tipo_id_paciente=f.tipo_id_paciente and c.paciente_id=f.paciente_id)
		left join os_cruce_citas as h on(c.agenda_cita_asignada_id=h.agenda_cita_asignada_id)
		left join os_maestro as k on(h.numero_orden_id=k.numero_orden_id)
		left join cuentas as j on(k.numerodecuenta=j.numerodecuenta)
		left join hc_evoluciones as i on (j.ingreso=i.ingreso)
		where b.agenda_turno_id in(".$var.") and b.sw_estado_cancelacion=0
		order by b.hora;";
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{

			while (!$result->EOF)
			{
				if(!empty($result->fields[2]) and !empty($result->fields[3]))
				{
					$t=0;
					$s=0;
					while($t<sizeof($turnosdia[2]))
					{
						if($turnosdia[2][$t]==$result->fields[2] and $turnosdia[3][$t]==$result->fields[3])
						{
							$s=1;
							break;
						}
						$t++;
					}
					if($s==0)
					{
						$turnosdia[0][$i]=$result->fields[0];
						$turnosdia[1][$i]=$result->fields[1];
						$turnosdia[2][$i]=$result->fields[2];
						$turnosdia[3][$i]=$result->fields[3];
						$turnosdia[4][$i]=$result->fields[4];
						if(!empty($result->fields[5]))
						{
							$turnosdia[5][$i]=1;
						}
						else
						{
							$turnosdia[5][$i]=0;
						}
						$turnosdia[6][$i]=$result->fields[6];
						$turnosdia[7][$i]=$result->fields[7];
						$turnosdia[8][$i]=$result->fields[8];
						$turnosdia[9][$i]=$result->fields[9];
						$turnosdia[10][$i]=$result->fields[10];
						$turnosdia[11][$i]=$result->fields[11];
						$i++;
					}
				}
				else
				{
					$turnosdia[0][$i]=$result->fields[0];
					$i++;
				}
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $turnosdia;
		}
		else
		{
			return false;
		}
	}




/**
* Esta funcion genera un listado de los dias que tienen citas disponibles
*
* @access public
* @return array listado de las fecha en donde existe citas disponibles.
* @param string sql para realizar la busqueda de dias con citas disponibles
*/

	function DiasCitas($sql)
	{
		list($dbconn) = GetDBconn();
		if(empty($sql))
		{
			if(empty($_SESSION['AsignacionCitas']['profesional']))
			{
				if(empty($_REQUEST['TipoBusqueda']) or $_REQUEST['TipoBusqueda']==1)
				{
					$sql="select a.fecha_turno
								from
								(select distinct(fecha_turno), c.estado
								from agenda_turnos as a
								left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero
								and c.departamento='".$_SESSION['AsignacionCitas']['departamento']."'
								and c.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'),
								agenda_citas as b
								where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']."
								and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'
								and sw_estado<cantidad_pacientes and date(fecha_turno)>=date(now())
								and b.sw_estado_cancelacion=0) as a
								where a.estado is null or a.estado=1 order by a.fecha_turno;";
// 					echo $sql1="select a.fecha_turno from (select distinct(fecha_turno), c.estado from agenda_turnos as a left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and c.departamento='".$_SESSION['AsignacionCitas']['departamento']."' and c.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'), agenda_citas as b where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and sw_estado&lt;cantidad_pacientes and date(fecha_turno)>=date(now())) as a where a.estado is null or a.estado=1 order by a.fecha_turno;";
				}
				else
				{
					if($_REQUEST['TipoBusqueda']==2)
					{
						$sql="select a.fecha_turno
									from
									(select distinct(fecha_turno), d.estado
									from agenda_turnos as a
									left join profesionales_estado as d on (a.profesional_id=d.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero
									and d.departamento='".$_SESSION['AsignacionCitas']['departamento']."'
									and d.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'),
									agenda_citas as b,profesionales as c
									where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']."
									and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and sw_estado<cantidad_pacientes
									and a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and sexo_id='M'
									and date(fecha_turno)>=date(now()) and b.sw_estado_cancelacion=0) as a
									where a.estado is null or a.estado=1 order by a.fecha_turno;";
// 						echo $sql1="select a.fecha_turno from (select distinct(fecha_turno), d.estado from agenda_turnos as a left join profesionales_estado as d on (a.profesional_id=d.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero and d.departamento='".$_SESSION['AsignacionCitas']['departamento']."' and d.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'), agenda_citas as b,profesionales as c where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and sw_estado&lt;cantidad_pacientes  and a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and sexo_id='M' and date(fecha_turno)>=date(now())) as a where a.estado is null or a.estado=1 order by a.fecha_turno;";
					}
					else
					{
						if($_REQUEST['TipoBusqueda']==3)
						{
							$sql="select a.fecha_turno
										from
										(select distinct(fecha_turno), d.estado
										from agenda_turnos as a
										left join profesionales_estado as d on (a.profesional_id=d.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero
										and d.departamento='".$_SESSION['AsignacionCitas']['departamento']."'
										and d.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'),
										agenda_citas as b,profesionales as c
										where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']."
										and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and sw_estado<cantidad_pacientes
										and a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and sexo_id='F'
										and date(fecha_turno)>=date(now()) and b.sw_estado_cancelacion=0) as a
										where a.estado is null or a.estado=1 order by a.fecha_turno;";
// 							echo $sql1="select a.fecha_turno from (select distinct(fecha_turno), d.estado from agenda_turnos as a left join profesionales_estado as d on (a.profesional_id=d.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero and d.departamento='".$_SESSION['AsignacionCitas']['departamento']."' and d.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'), agenda_citas as b,profesionales as c where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and sw_estado&lt;cantidad_pacientes  and a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and sexo_id='F' and date(fecha_turno)>=date(now())) as a where a.estado is null or a.estado=1 order by a.fecha_turno;";
						}
					}
				}
			}
			else
			{
				if(empty($sql))
				{
					$a=explode(",",$_SESSION['AsignacionCitas']['profesional']);
					$sql="select distinct(fecha_turno)
								from agenda_turnos as a, agenda_citas as b
								where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']."
								and empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'
								and sw_estado<cantidad_pacientes and profesional_id='".$a[0]."' and tipo_id_profesional='".$a[1]."'
								and date(fecha_turno)>=date(now()) and b.sw_estado_cancelacion=0
								order by fecha_turno;";
				//$sql1="select distinct(fecha_turno) from agenda_turnos as a, agenda_citas as b where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and sw_estado&lt;cantidad_pacientes and profesional_id='".$a[0]."' and tipo_id_profesional='".$a[1]."' and date(fecha_turno)>=date(now()) order by fecha_turno;";
					//echo $sql1;
				}
			}
		}
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				if($result->fields[0]>=date("Y-m-d"))
				{
					$fechas[$i]=$result->fields[0];
					$i++;
				}
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			$fechas1=array_unique($fechas);
			array_multisort($fechas1);
			return $fechas1;
		}
		else
		{
			return false;
		}
	}





	/**
	* Esta funcion genera un listado con los profesionales que tienen citas disponibles en el dia escogido
	*
	* @access public
	* @return array listado de los profesionales que tienen citas disponibles
	*/
	function Profesionales()
	{
	//					profesionales_departamentos as x
	//						and a.profesional_id=x.tercero_id and a.tipo_id_profesional=x.tipo_id_tercero
	//					and x.departamento='".$_SESSION['AsignacionCitas']['departamento']."'
		list($dbconn) = GetDBconn();
		if($_REQUEST['TipoBusqueda']==1 and empty($_REQUEST['DiaEspe']))
		{
			$sql="select a.nombre_tercero as nombre, a.tercero_id, a.tipo_id_tercero
						from
						(select distinct(d.nombre_tercero), b.tercero_id , b.tipo_id_tercero,
						c.estado from agenda_turnos as a
						left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero
						and c.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'
						and c.departamento='".$_SESSION['AsignacionCitas']['departamento']."'),
						profesionales as b, terceros as d
						where a.tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']."
						and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'
						and a.profesional_id=b.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero
						and a.profesional_id=d.tercero_id and a.tipo_id_profesional=b.tipo_id_tercero
						and date(a.fecha_turno)>=date(now())) as a
						where a.estado is null or a.estado=1 order by a.nombre_tercero;";
		}
		else
		{
			if($_REQUEST['TipoBusqueda']==2)
			{
				$sql="select a.nombre_tercero as nombre, a.tercero_id, a.tipo_id_tercero
							from
							(select distinct(d.nombre_tercero), b.tercero_id , b.tipo_id_tercero,
							c.estado from agenda_turnos as a
							left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero
							and c.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'
							and c.departamento='".$_SESSION['AsignacionCitas']['departamento']."'),
							profesionales as b, terceros as d
							where a.tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']."
							and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'
							and a.profesional_id=b.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero
							and a.profesional_id=d.tercero_id and a.tipo_id_profesional=b.tipo_id_tercero and b.sexo_id='M'
							and date(a.fecha_turno)>=date(now())) as a
							where a.estado is null or a.estado=1 order by a.nombre_tercero;";
			}
			else
			{
				if($_REQUEST['TipoBusqueda']==3)
				{
					$sql="select a.nombre_tercero as nombre, a.tercero_id, a.tipo_id_tercero
								from
								(select distinct(d.nombre_tercero), b.tercero_id , b.tipo_id_tercero,
								c.estado
								from agenda_turnos as a
								left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero
								and c.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'
								and c.departamento='".$_SESSION['AsignacionCitas']['departamento']."'),
								profesionales as b, terceros as d
								where a.tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']."
								and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'
								and a.profesional_id=b.tercero_id
								and a.tipo_id_profesional=d.tipo_id_tercero
								and a.profesional_id=d.tercero_id and a.tipo_id_profesional=b.tipo_id_tercero and b.sexo_id='F'
								and date(a.fecha_turno)>=date(now())) as a
								where a.estado is null or a.estado=1 order by a.nombre_tercero;";
				}
			}
		}
		if(!empty($_REQUEST['DiaEspe']))
		{
			$sql="select a.nombre_tercero as nombre, a.tercero_id, a.tipo_id_tercero
						from
						(select distinct(d.nombre_tercero), b.tercero_id , b.tipo_id_tercero,
						c.estado
						from agenda_turnos as a join agenda_citas as e on(a.agenda_turno_id=e.agenda_turno_id)
						left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero
						and c.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'
						and c.departamento='".$_SESSION['AsignacionCitas']['departamento']."'),
						profesionales as b, terceros as d
						where a.tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'
						and a.profesional_id=b.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero
						and a.profesional_id=d.tercero_id and a.tipo_id_profesional=b.tipo_id_tercero
						and date(a.fecha_turno)=date('".$_REQUEST['DiaEspe']."') and e.sw_estado_cancelacion='0'
						and e.sw_estado<a.cantidad_pacientes) as a
						where a.estado is null or a.estado=1 order by a.nombre_tercero;";
			/*$sql1="select a.nombre_tercero as nombre, a.tercero_id, a.tipo_id_tercero from (select distinct(d.nombre_tercero), b.tercero_id , b.tipo_id_tercero, c.estado from agenda_turnos as a join agenda_citas as e on(a.agenda_turno_id=e.agenda_turno_id) left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and c.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and c.departamento='".$_SESSION['AsignacionCitas']['departamento']."'), profesionales as b, terceros as d where a.tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and a.profesional_id=b.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero and a.profesional_id=d.tercero_id and a.tipo_id_profesional=b.tipo_id_tercero and date(a.fecha_turno)=date('".$_REQUEST['DiaEspe']."') and e.sw_estado_cancelacion='0' and e.sw_estado&lt;a.cantidad_pacientes) as a where a.estado is null or a.estado=1 order by a.nombre_tercero;";
			echo $sql1;*/
		}
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$profesional[0][$i]=$result->fields[0];
				$profesional[1][$i]=$result->fields[1];
				$profesional[2][$i]=$result->fields[2];
				$result->MoveNext();
				$i++;
			}
		}
		if($i<>0)
		{
			return $profesional;
		}
		else
		{
			return false;
		}
	}





/**
* Esta funcion genera un listado con los turnos que estan disponibles con el profesional escogido y la fecha escogida
*
* @access public
* @return array listado de las citas disponibles
*/

	function CitasDia()
	{
		$a=explode(",",$_SESSION['AsignacionCitas']['profesional']);
		list($dbconn) = GetDBconn();
		$sql="select a.hora,a.agenda_turno_id, a.agenda_cita_id
					from agenda_citas as a, agenda_turnos as b
					where a.agenda_turno_id=b.agenda_turno_id and b.profesional_id='".$a[0]."'
					and b.tipo_id_profesional='".$a[1]."' and
		 			date(b.fecha_turno)=date('".$_REQUEST['DiaEspe']."')
					and empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'
					and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']."
					and a.sw_estado < b.cantidad_pacientes
					and a.sw_estado_cancelacion=0 order by a.hora;";
		 /*$sql1="select a.hora from agenda_citas as a, agenda_turnos as b where a.agenda_turno_id=b.agenda_turno_id and b.profesional_id='".$a[0]."' and b.tipo_id_profesional='".$a[1]."' and date(b.fecha_turno)=date('".$_REQUEST['DiaEspe']."') and empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and a.sw_estado&lt;b.cantidad_pacientes and a.sw_estado_cancelacion=0 order by a.hora;";
		echo $sql1;*/
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$cita[0][$i]=$result->fields[0];
				$cita[1][$i]=$result->fields[1];
				$cita[2][$i]=$result->fields[2];
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $cita;
		}
		else
		{
			return false;
		}
	}





/**
* Esta funcion genera un listado con los tamaÃ±os de turnos
*
* @access public
* @return array listado de los tamaÃ±os de turnos
*/


	function TamCita()
	{
		$a=explode(",",$_SESSION['AsignacionCitas']['profesional']);
		list($dbconn) = GetDBconn();
		$sql="select distinct a.duracion, a.cantidad_pacientes,a.agenda_turno_id from agenda_turnos as a, agenda_citas as b where date(a.fecha_turno)=date('".$_REQUEST['DiaEspe']."') and a.profesional_id='".$a[0]."' and a.tipo_id_profesional='".$a[1]."' and a.tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and a.agenda_turno_id=b.agenda_turno_id;";
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
				$dato[0][$i]=$result->fields[0];
				$dato[1][$i]=$result->fields[1];
				$dato[2][$i]=$result->fields[2];
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $dato;
		}
		else
		{
			return false;
		}
	}





/**
* Esta funcion recibe la informacion de la llegada del modulo de autorizacion del paciente
*
* @access public
* @return boolean retorna verdadero si se cumplio con exito el retorno y false si no
*/

	function LLegadaAutorizarPaciente()
	{
		unset($_SESSION['EMPLEADOR']);
		if(empty($_SESSION['AsignacionCitas']['cita']))
		{
			if(empty($_SESSION['CumplirCita']['cita']))
			{
				if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']))
				{
					$this->FormaMensaje('No tiene autorizacion','Autorización',ModuloGetURL('app','AgendaMedica','','DatosPaciente'),'Volver');
					return true;
				}
				$_SESSION['DATOSPACIENTE']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'];
				$_SESSION['LiquidarCitas']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'];
				$_SESSION['LiquidarCitas']['rango']=$_SESSION['AUTORIZACIONES']['RETORNO']['rango'];
				$_SESSION['LiquidarCitas']['semanas']=$_SESSION['AUTORIZACIONES']['RETORNO']['semanas'];
				$_SESSION['LiquidarCitas']['NumAutorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
				//CAMBIO DAR
				if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ext']))
				{  $_SESSION['LiquidarCitas']['NumAutorizacionExt']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];  }
				//hay datos del empleador
				if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']))
				{
						$_SESSION['EMPLEADOR']['tipo_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador'];
						$_SESSION['EMPLEADOR']['id_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador'];
						//$_SESSION['EMPLEADOR']['empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['empleador'];
						//$_SESSION['EMPLEADOR']['telefono_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['telefono_empleador'];
						//$_SESSION['EMPLEADOR']['direccion_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['direccion_empleador'];
				}
				//FIN CAMBIO DAR

				unset($_SESSION['AUTORIZACIONES']);
				if($this->BuscarPaciente()==false)
				{
					return false;
				}
			}
			else
			{
				if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']) OR empty($_SESSION['AUTORIZACIONES']['RETORNO']['plan_id']))
				{
					if(!empty($_SESSION['CumplirCita']['profesional']))
					{
						$metodo='ListadoCitasCumplidas';
					}
					else
					{
						$metodo='BuscarCita';
					}
					$this->FormaMensaje('No tiene autorizacion','Autorización',ModuloGetURL('app','AgendaMedica','',$metodo),'Volver');
					unset($_SESSION['AUTORIZACIONES']);
					return true;
				}
				$_SESSION['DATOSPACIENTE']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'];
				$_SESSION['CumplirCita']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'];
				$_SESSION['CumplirCita']['rango']=$_SESSION['AUTORIZACIONES']['RETORNO']['rango'];
				$_SESSION['CumplirCita']['semanas']=$_SESSION['AUTORIZACIONES']['RETORNO']['semanas'];
				$_SESSION['CumplirCita']['NumAutorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
				//CAMBIO DAR
				if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ext']))
				{  $_SESSION['CumplirCita']['NumAutorizacionExt']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];  }
				//hay datos del empleador
				if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']))
				{
						$_SESSION['EMPLEADOR']['tipo_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador'];
						$_SESSION['EMPLEADOR']['id_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador'];
						//$_SESSION['EMPLEADOR']['empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['empleador'];
						//$_SESSION['EMPLEADOR']['telefono_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['telefono_empleador'];
						//$_SESSION['EMPLEADOR']['direccion_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['direccion_empleador'];
				}
				//FIN CAMBIO DAR

				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();
				$sql="update os_ordenes_servicios set tipo_afiliado_id='".$_SESSION['CumplirCita']['tipo_afiliado_id']."', rango='".$_SESSION['CumplirCita']['rango']."', semanas_cotizadas=".$_SESSION['CumplirCita']['semanas']." where orden_servicio_id=".$_SESSION['CumplirCita']['orden_servicio_id'].";";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB a: " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				$sql="update os_maestro set sw_estado=5 where numero_orden_id=".$_SESSION['CumplirCita']['numero_orden_id'].";";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB a: " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				//cambio dar
				if(!empty($_SESSION['EMPLEADOR']))
				{
						$query = "SELECT * FROM os_ordenes_servicios_empleadores
											WHERE orden_servicio_id=".$_SESSION['CumplirCita']['orden_servicio_id'].";";
						$results = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB a: " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						//si tiene empleador
						if(!$results->EOF)
						{
								$query = "UPDATE os_ordenes_servicios_empleadores SET
																							empleador_id='".$_SESSION['EMPLEADOR']['id_empleador']."',
																							tipo_id_empleador='".$_SESSION['EMPLEADOR']['tipo_empleador']."'
													WHERE orden_servicio_id=".$_SESSION['CumplirCita']['orden_servicio_id'].";";
								$result = $dbconn->Execute($sql);
								if ($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB a: " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
								}
						}
						else
						{
								//no tenia en la tabla pero si tiene empelador
								$query = "INSERT INTO os_ordenes_servicios_empleadores(
																												orden_servicio_id,
																												tipo_id_empleador,
																												empleador_id)
													VALUES(".$_SESSION['CumplirCita']['orden_servicio_id'].",'".$_SESSION['EMPLEADOR']['tipo_empleador']."','".$_SESSION['EMPLEADOR']['id_empleador']."')";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error INSERT INTO os_ordenes_servicios_empleadores ";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
								}
						}
				}
				//fin cambio dar

				$dbconn->CommitTrans();
				/*if($this->PedirDatosPaciente()==false)
				{
					return false;
				}*/
				if(!empty($_SESSION['CumplirCita']['profesional']))
				{
					if($this->ListadoCitasCumplidas()==false)
					{
						return false;
					}
				}
				else
				{
					if($this->BuscarCita()==false)
					{
						return false;
					}
				}
			}
		}
		else
		{//print_r($_SESSION['AUTORIZACIONES']['RETORNO']['plan_id']);
			/*f(empty($_SESSION['AUTORIZACIONES']['RETORNO']['plan_id']))
			{
				$this->FormaMensaje('Se cancelo el proceso de autorización','Autorización',ModuloGetURL('app','AgendaMedica','','DatosPaciente'),'Volver');
				unset($_SESSION['AUTORIZACIONES']);
				return true;
			}*/

			if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']) OR empty($_SESSION['AUTORIZACIONES']['RETORNO']['plan_id']))
			{
				$this->FormaMensaje('Se cancelo el proceso de autorización','Autorización',ModuloGetURL('app','AgendaMedica','','DatosPaciente'),'Volver');
				unset($_SESSION['AUTORIZACIONES']);
				return true;
			}
			$_SESSION['DATOSPACIENTE']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'];
			$_SESSION['AsignacionCitas']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'];
			$_SESSION['AsignacionCitas']['rango']=$_SESSION['AUTORIZACIONES']['RETORNO']['rango'];
			$_SESSION['AsignacionCitas']['semanas']=$_SESSION['AUTORIZACIONES']['RETORNO']['semanas'];
			$_SESSION['AsignacionCitas']['NumAutorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
			//CAMBIO DAR
			if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ext']))
			{  $_SESSION['AsignacionCitas']['NumAutorizacionExt']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];  }
			//hay datos del empleador
			if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']))
			{
					$_SESSION['EMPLEADOR']['tipo_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador'];
					$_SESSION['EMPLEADOR']['id_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador'];
					//$_SESSION['EMPLEADOR']['empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['empleador'];
					//$_SESSION['EMPLEADOR']['telefono_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['telefono_empleador'];
					//$_SESSION['EMPLEADOR']['direccion_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['direccion_empleador'];
			}
			//FIN CAMBIO DAR

			unset($_SESSION['AUTORIZACIONES']);
			if($this->BuscarPaciente()==false)
			{
				return false;
			}
		}
		return true;
	}




	
/**
* Esta funcion remite al usuario al modulo de autorizaciones para generar la misma
*
* @access public
* @return boolean retorna verdadero si se cumplio con exito el llamado a la autorizacion y false si no
*/

	function AutorizarPaciente()
	{
		unset($_SESSION['AUTORIZACIONES']);
		if(empty($_REQUEST['CUMPLIR']['paciente']))
		{
			if(empty($_SESSION['AsignacionCitas']['cita']))
			{
				list($dbconn) = GetDBconn();
				$sql="select a.tarifario_id, a.cargo from tarifarios_equivalencias as a, tarifarios_detalle as b, plan_tarifario as c where cargo_base='".$_SESSION['LiquidarCitas']['cargo_cups']."' and a.tarifario_id=b.tarifario_id and a.cargo=b.cargo and b.grupo_tarifario_id=c.grupo_tarifario_id and b.subgrupo_tarifario_id=c.subgrupo_tarifario_id and c.plan_id=".$_SESSION['LiquidarCitas']['Responsable']." and b.tarifario_id=c.tarifario_id;";
				//select b.tarifario_id, b.cargo from cargos_citas as a, equiv_cargos_citas as b where a.cargo_cita=".$_SESSION['LiquidarCitas']['TipoConsulta']." and a.cargo_cita=b.cargo_cita and b.plan_id=".$_SESSION['LiquidarCitas']['Responsable'].";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$_SESSION['LiquidarCitas']['servicio']=$this->BusquedaServicio($_SESSION['LiquidarCitas']['departamento']);
				//CAMBIO DAR
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CUPS']=$_SESSION['LiquidarCitas']['cargo_cups'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['EMPLEADOR']=true;
				//FIN CAMBIO DAR
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['LiquidarCitas']['Documento'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['LiquidarCitas']['TipoDocumento'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$_SESSION['LiquidarCitas']['Responsable'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']='CONSULTAEXTERNA';
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION']='Cargo';
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO']=$result->fields[1];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TARIFARIO']=$result->fields[0];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']=$_SESSION['LiquidarCitas']['servicio'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=array();
				$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
				$_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='AgendaMedica';
				$_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
				$_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='LLegadaAutorizarPaciente';
				$this->ReturnMetodoExterno('app','Autorizacion','user','SolicitudAutorizacion');
			}
			else
			{
				foreach($_REQUEST as $k=>$v)
				{
					if (substr_count ($k,'seleccion')==1)
					{
						$_SESSION['DatosCitas'][$k]=$v;
					}
				}
				list($dbconn) = GetDBconn();
				 $sql="select a.tarifario_id, a.cargo from tarifarios_equivalencias as a, tarifarios_detalle as b, plan_tarifario as c where cargo_base='".$_SESSION['AsignacionCitas']['cargo_cups']."' and a.tarifario_id=b.tarifario_id and a.cargo=b.cargo and b.grupo_tarifario_id=c.grupo_tarifario_id and b.subgrupo_tarifario_id=c.subgrupo_tarifario_id and c.plan_id=".$_SESSION['AsignacionCitas']['Responsable']." and b.tarifario_id=c.tarifario_id;";
				//select b.tarifario_id, b.cargo from cargos_citas as a, equiv_cargos_citas as b where a.cargo_cita=".$_SESSION['AsignacionCitas']['TipoConsulta']." and a.cargo_cita=b.cargo_cita and b.plan_id=".$_SESSION['AsignacionCitas']['Responsable'].";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$_SESSION['AsignacionCitas']['servicio']=$this->BusquedaServicio($_SESSION['AsignacionCitas']['departamento']);
				//CAMBIO DAR
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CUPS']=$_SESSION['AsignacionCitas']['cargo_cups'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['EMPLEADOR']=true;
				unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['orden_servicio_id']);
				//FIN CAMBIO DAR
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['AsignacionCitas']['Documento'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['AsignacionCitas']['TipoDocumento'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$_SESSION['AsignacionCitas']['Responsable'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']='CONSULTAEXTERNA';
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION']='Cargo';
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO']=$result->fields[1];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TARIFARIO']=$result->fields[0];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']=$_SESSION['AsignacionCitas']['servicio'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=array('departamento'=>$_SESSION['AsignacionCitas']['departamento'],'NoAutorizacion'=>$_REQUEST['NoAutorizacion']);
				$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
				$_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='AgendaMedica';
				$_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
				$_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='LLegadaAutorizarPaciente';
				$this->ReturnMetodoExterno('app','Autorizacion','user','SolicitudAutorizacion');
			}
		}
		else
		{
			if(!empty($_REQUEST['CUMPLIR']['paciente']))
			{
				$_SESSION['CumplirCita']['paciente_id']=$_REQUEST['CUMPLIR']['paciente'];
				$_SESSION['CumplirCita']['tipo_id_paciente']=$_REQUEST['CUMPLIR']['tipo_id_paciente'];
				$_SESSION['CumplirCita']['plan_id']=$_REQUEST['CUMPLIR']['plan'];
				$_SESSION['CumplirCita']['cargo']=$_REQUEST['CUMPLIR']['cargo'];
				$_SESSION['CumplirCita']['tarifario']=$_REQUEST['CUMPLIR']['tarifario'];
				$_SESSION['CumplirCita']['numerodecuenta']=$_REQUEST['CUMPLIR']['numerodecuenta'];
				$_SESSION['CumplirCita']['numero_orden_id']=$_REQUEST['CUMPLIR']['numero_orden_id'];
				$_SESSION['CumplirCita']['orden_servicio_id']=$_REQUEST['CUMPLIR']['orden_servicio_id'];
			}

			$_SESSION['CumplirCita']['servicio']=$this->BusquedaServicio($_SESSION['CumplirCita']['departamento']);
			//CAMBIO DAR
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CUPS']=$_REQUEST['CUMPLIR']['cargo_cups'];
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['EMPLEADOR']=true;
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['orden_servicio_id']=$_SESSION['CumplirCita']['orden_servicio_id'];
			//FIN CAMBIO DAR
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['CumplirCita']['paciente_id'];
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['CumplirCita']['tipo_id_paciente'];
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$_SESSION['CumplirCita']['plan_id'];
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']='CONSULTAEXTERNA';
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION']='Cargo';
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO']=$_SESSION['CumplirCita']['cargo'];
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TARIFARIO']=$_SESSION['CumplirCita']['tarifario'];
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']=$_SESSION['CumplirCita']['servicio'];
			$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=array('departamento'=>$_SESSION['CumplirCita']['departamento']);
			$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
			$_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='AgendaMedica';
			$_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
			$_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='LLegadaAutorizarPaciente';
			$_SESSION['DPTO']=$_SESSION['CumplirCita']['departamento'];
			$this->ReturnMetodoExterno('app','Autorizacion','user','SolicitudAutorizacion');
		}
		return true;
	}

	/*function Nivel()
	{
		list($dbconn) = GetDBconn();
		if(empty($_SESSION['AsignacionCitas']['Responsable']))
		{
			$Responsable=$_SESSION['LiquidarCitas']['Responsable'];
		}
		else
		{
			$Responsable=$_SESSION['AsignacionCitas']['Responsable'];
		}
		$sql="select nivel from cuota_paciente where plan_id=".$Responsable.";";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$result->EOF)
		{
			$nivel[0][$i]=$result->fields[0];
			$nivel[1][$i]=$result->fields[0];
			$result->MoveNext();
			$i++;
		}
		return $nivel;
	}*/



/**
* Esta funcion revisa que existan todos los datos del paciente para ingresar el mismo a la base de datos de la institucion
*
* @access public
* @return boolean retorna verdadero si se cumplio con exito la toma de los datos y falso si no se realizo
*/


	function DatosIniciales()
	{
		if(empty($_SESSION['AsignacionCitas']['cita']))
		{
			if(!empty($_REQUEST['Documento']))
			{
				$_SESSION['LiquidarCitas']['Responsable']=$_REQUEST['Responsable'];
				$_SESSION['LiquidarCitas']['cargo_cups']=$_REQUEST['TipoConsulta'];
				$_SESSION['LiquidarCitas']['TipoDocumento']=$_REQUEST['TipoDocumento'];
				$_SESSION['LiquidarCitas']['Documento']=$_REQUEST['Documento'];
			}
			$TipoDocumento=$_SESSION['LiquidarCitas']['TipoDocumento'];
			$Documento=$_SESSION['LiquidarCitas']['Documento'];
			$Responsable=$_SESSION['LiquidarCitas']['Responsable'];
			$TipoConsulta=$_SESSION['LiquidarCitas']['cargo_cups'];
			$cita=$_SESSION['LiquidarCitas']['cita'];
		}
		else
		{
			if(!empty($_REQUEST['Documento']))
			{
				$_SESSION['AsignacionCitas']['Responsable']=$_REQUEST['Responsable'];
				$_SESSION['AsignacionCitas']['cargo_cups']=$_REQUEST['TipoConsulta'];
				$_SESSION['AsignacionCitas']['TipoDocumento']=$_REQUEST['TipoDocumento'];
				$_SESSION['AsignacionCitas']['Documento']=$_REQUEST['Documento'];
			}
			$TipoDocumento=$_SESSION['AsignacionCitas']['TipoDocumento'];
			$Documento=$_SESSION['AsignacionCitas']['Documento'];
			$Responsable=$_SESSION['AsignacionCitas']['Responsable'];
			$TipoConsulta=$_SESSION['AsignacionCitas']['cargo_cups'];
			$cita=$_SESSION['AsignacionCitas']['cita'];
		}

		//cambio dar
		$Paciente=$this->ReturnModuloExterno('app','Pacientes','user');
		if(!is_object($Paciente))
		{
						$this->error = "La clase Pacientes no se pudo instanciar";
						$this->mensajeDeError = "";
						return false;
		}
		$_SESSION['PACIENTES']['RETORNO']['argumentos']=array('HOMONIMO'=>true);
		$_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
		$_SESSION['PACIENTES']['RETORNO']['modulo']='AgendaMedica';
		$_SESSION['PACIENTES']['RETORNO']['tipo']='user';
		$_SESSION['PACIENTES']['RETORNO']['metodo']='DatosIniciales';
		if(!$Paciente->BuscarIngresoActivoPaciente($TipoDocumento,$Documento,$_SESSION['AsignacionCitas']['empresa'],$Responsable,$accion=array('contenedor'=>'app','modulo'=>'AgendaMedica','tipo'=>'user','metodo'=>'DatosPaciente')))
		{
						$this->error = $Paciente->error ;
						$this->mensajeDeError = $Paciente->mensajeDeError;
						unset($Paciente);
						return false;
		}
		else
		{
					if(!$Paciente->TipoRetorno AND empty($_REQUEST['HOMONIMO']))
					{
											$this->salida .= $Paciente->GetSalida();
											unset($Paciente);
											return true;
					}
		}
		//fin cambio dar

		if(($TipoDocumento!='MS' && $TipoDocumento!='AS' && !$Documento) or ($Responsable==-1) or ($TipoConsulta==-1))
		{
			if(!$Documento)
			{
				$this->frmError["Documento"]=1;
			}
			if($Responsable==-1)
			{
				$this->frmError["Responsable"]=1;
			}
 			if($TipoConsulta==-1)
 			{
 				$this->frmError["TipoConsulta"]=1;
 			}
				$this->frmError["MensajeError"]="Debe digitar el documento.";
				if(!$this->DatosPaciente())
				{
					return false;
				}
			return true;
		}
		else
		{
			if($this->BuscarAlPaciente()==false)
			{
				if($this->AutorizarPaciente()==false)
				{
					return false;
				}
			}
			else
			{
				if($this->PantallaOrdenes()==false)
				{
					return false;
				}
				return true;
			}
		}
		return true;
	}





/**
* Esta funcion identifica la orden de servicio con la que se va ha asignar la cita
*
* @access public
* @return boolean retorna verdadero si se cumplen las condiciones y falso si no
*/
	function PacienteOrdenServicio()
	{
		$_SESSION['AsignacionCitas']['NumeroOrden']=$_REQUEST['numero_orden_id'];
		$_SESSION['AsignacionCitas']['tipo_afiliado_id']=$_REQUEST['tipo_afiliado_id'];
		$_SESSION['AsignacionCitas']['rango']=$_REQUEST['rango'];
		$_SESSION['AsignacionCitas']['semanas']=$_REQUEST['semanas_cotizadas'];
		$_SESSION['AsignacionCitas']['NumAutorizacion']=$_REQUEST['autorizacion_int'];
		$_SESSION['AsignacionCitas']['NumAutorizacionExt']=$_REQUEST['autorizacion_ext'];
		if($this->BuscarPaciente()==false)
		{
			return false;
		}
		return true;
	}






/**
* Esta funcion busca la existencia del paciente
*
* @access public
* @return boolean retorna verdadero si se cumplen las condiciones y falso si no
*/

	function BuscarAlPaciente()
	{
		if(empty($_SESSION['AsignacionCitas']['cita']))
		{
			$TipoDocumento=$_SESSION['LiquidarCitas']['TipoDocumento'];
			$Documento=$_SESSION['LiquidarCitas']['Documento'];
			$TipoConsulta=$_SESSION['LiquidarCitas']['cargo_cups'];
			$Responsable=$_SESSION['LiquidarCitas']['Responsable'];
			$departamento=$_SESSION['LiquidarCitas']['departamento'];
			$tipocita=$_SESSION['LiquidarCitas']['cita'];
		}
		else
		{
			$TipoDocumento=$_SESSION['AsignacionCitas']['TipoDocumento'];
			$Documento=$_SESSION['AsignacionCitas']['Documento'];
			$TipoConsulta=$_SESSION['AsignacionCitas']['cargo_cups'];
			$Responsable=$_SESSION['AsignacionCitas']['Responsable'];
			$departamento=$_SESSION['AsignacionCitas']['departamento'];
			$tipocita=$_SESSION['AsignacionCitas']['cita'];
		}
		list($dbconn) = GetDBconn();
		$query = "SELECT primer_apellido,
												segundo_apellido,
												primer_nombre,
												segundo_nombre,
												residencia_telefono,
												sexo_id,
												residencia_direccion
                FROM pacientes WHERE paciente_id='$Documento' AND tipo_id_paciente='$TipoDocumento'";
			unset($_SESSION['AsignacionCitas']['ORDENES']);
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
			$datos=false;
			$datos=$result->RecordCount();
			if($datos!=false)
			{
				if(empty($_SESSION['AsignacionCitas']['cita']))
				{
					$_SESSION['LiquidarCitas']['DATOSPACIENTE']['primer_apellido']=$result->fields[0];
					$_SESSION['LiquidarCitas']['DATOSPACIENTE']['segundo_apellido']=$result->fields[1];
					$_SESSION['LiquidarCitas']['DATOSPACIENTE']['primer_nombre']=$result->fields[2];
					$_SESSION['LiquidarCitas']['DATOSPACIENTE']['segundo_nombre']=$result->fields[3];
					$_SESSION['LiquidarCitas']['DATOSPACIENTE']['residencia_telefono']=$result->fields[4];
					$_SESSION['LiquidarCitas']['DATOSPACIENTE']['sexo_id']=$result->fields[5];
					$_SESSION['LiquidarCitas']['DATOSPACIENTE']['residencia_direccion']=$result->fields[6];
				}
				else
				{
					$_SESSION['AsignacionCitas']['DATOSPACIENTE']['primer_apellido']=$result->fields[0];
					$_SESSION['AsignacionCitas']['DATOSPACIENTE']['segundo_apellido']=$result->fields[1];
					$_SESSION['AsignacionCitas']['DATOSPACIENTE']['primer_nombre']=$result->fields[2];
					$_SESSION['AsignacionCitas']['DATOSPACIENTE']['segundo_nombre']=$result->fields[3];
					$_SESSION['AsignacionCitas']['DATOSPACIENTE']['residencia_telefono']=$result->fields[4];
					$_SESSION['AsignacionCitas']['DATOSPACIENTE']['sexo_id']=$result->fields[5];
					$_SESSION['AsignacionCitas']['DATOSPACIENTE']['residencia_direccion']=$result->fields[6];
				}
				$_SESSION['AsignacionCitas']['Existe']=True;
				$sql="select e.numero_orden_id, b.fecha_vencimiento, b.fecha_activacion, d.orden_servicio_id, d.tipo_afiliado_id, d.rango, d.semanas_cotizadas, d.autorizacion_int from hc_os_solicitudes_citas as a join os_maestro as b on(a.hc_os_solicitud_id=b.hc_os_solicitud_id) join os_ordenes_servicios as d on(b.orden_servicio_id=d.orden_servicio_id) join os_internas as e on(b.numero_orden_id=e.numero_orden_id) left join os_cruce_citas as c on(b.numero_orden_id=c.numero_orden_id) where (b.sw_estado=1 or b.sw_estado=2) and tipo_consulta_id=".$tipocita." and c.numero_orden_id is null and e.cargo='$TipoConsulta' and e.departamento='".$departamento."' and d.tipo_id_paciente='$TipoDocumento' and d.paciente_id='$Documento' and d.plan_id=$Responsable;";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				if(!empty($result->fields[0]))
				{
					while (!$result->EOF)
					{
						$_SESSION['AsignacionCitas']['ORDENES'][$result->fields[0]]=$result->GetRowAssoc(false);
						$result->MoveNext();
					}
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				$_SESSION['AsignacionCitas']['Existe']=false;
				return false;
			}
	}




/**
* Esta funcion busca la existencia del paciente
*
* @access public
* @return boolean retorna verdadero si se cumplen las condiciones y falso si no
*/

	function BuscarPaciente()
	{
		if(empty($_SESSION['AsignacionCitas']['cita']))
		{
			$TipoDocumento=$_SESSION['LiquidarCitas']['TipoDocumento'];
			$Documento=$_SESSION['LiquidarCitas']['Documento'];
			$Responsable=$_SESSION['LiquidarCitas']['Responsable'];
			$TipoConsulta=$_SESSION['LiquidarCitas']['cargo_cups'];
			$cita=$_SESSION['LiquidarCitas']['cita'];
			$primer_apellido=$_SESSION['LiquidarCitas']['DATOSPACIENTE']['primer_apellido'];
			$segundo_apellido=$_SESSION['LiquidarCitas']['DATOSPACIENTE']['segundo_apellido'];
			$primer_nombre=$_SESSION['LiquidarCitas']['DATOSPACIENTE']['primer_nombre'];
			$segundo_nombre=$_SESSION['LiquidarCitas']['DATOSPACIENTE']['segundo_nombre'];
			$residencia_telefono=$_SESSION['LiquidarCitas']['DATOSPACIENTE']['residencia_telefono'];
			$sexo_id=$_SESSION['LiquidarCitas']['DATOSPACIENTE']['sexo_id'];
			$residencia_direccion=$_SESSION['LiquidarCitas']['DATOSPACIENTE']['residencia_direccion'];
			unset($_SESSION['LiquidarCitas']['DATOSPACIENTE']);
		}
		else
		{
			$TipoDocumento=$_SESSION['AsignacionCitas']['TipoDocumento'];
			$Documento=$_SESSION['AsignacionCitas']['Documento'];
			$Responsable=$_SESSION['AsignacionCitas']['Responsable'];
			$TipoConsulta=$_SESSION['AsignacionCitas']['cargo_cups'];
			$cita=$_SESSION['AsignacionCitas']['cita'];
			$primer_apellido=$_SESSION['AsignacionCitas']['DATOSPACIENTE']['primer_apellido'];
			$segundo_apellido=$_SESSION['AsignacionCitas']['DATOSPACIENTE']['segundo_apellido'];
			$primer_nombre=$_SESSION['AsignacionCitas']['DATOSPACIENTE']['primer_nombre'];
			$segundo_nombre=$_SESSION['AsignacionCitas']['DATOSPACIENTE']['segundo_nombre'];
			$residencia_telefono=$_SESSION['AsignacionCitas']['DATOSPACIENTE']['residencia_telefono'];
			$sexo_id=$_SESSION['AsignacionCitas']['DATOSPACIENTE']['sexo_id'];
			$residencia_direccion=$_SESSION['AsignacionCitas']['DATOSPACIENTE']['residencia_direccion'];
			unset($_SESSION['AsignacionCitas']['DATOSPACIENTE']);
		}
		if($TipoDocumento=='MS' || $TipoDocumento=='AS')
		{
			$mensaje='El paciente es NN.';
			if(!$this->FormaPedirDatos($TipoDocumento,$Documento,$mensaje))
			{
				return false;
			}
			return true;
		}
 		list($dbconn) = GetDBconn();
			if($_SESSION['AsignacionCitas']['Existe']==true)
			{
					$sql="select a.tarifario_id, a.cargo from tarifarios_equivalencias as a, tarifarios_detalle as b, plan_tarifario as c where cargo_base='$TipoConsulta' and a.tarifario_id=b.tarifario_id and a.cargo=b.cargo and b.grupo_tarifario_id=c.grupo_tarifario_id and b.subgrupo_tarifario_id=c.subgrupo_tarifario_id and c.plan_id='$Responsable' and b.tarifario_id=c.tarifario_id;";
					$result2 = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB s: " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				if(!$this->FormaPedirDatos($TipoDocumento, $Documento, '', True, $Responsable, $primer_apellido, $segundo_apellido, $primer_nombre, $segundo_nombre, $residencia_telefono, $sexo_id, $residencia_direccion))
				{
					return false;
				}
				return true;
			}
      else
			{
				$mensaje="El paciente no se encuentra registrado en los pacientes.";
				if(!$this->FormaPedirDatos($TipoDocumento,$Documento,$mensaje,false))
				{
					return false;
				}
				return true;
			}
	}

	/*function BuscarPaciente1()
	{
		$TipoDocumento=$_REQUEST['TipoDocumento'];
		$Documento=$_REQUEST['Documento'];
		if($TipoDocumento!='MS' && $TipoDocumento!='AS' && !$Documento)
		{
			if(!$Documento)
			{
				$this->frmError["Documento"]=1;
			}
				$this->frmError["MensajeError"]="Debe digitar el documento.";
				if(!$this->BuscarCita())
				{
					return false;
				}
			return true;
		}
		if($TipoDocumento=='MS' || $TipoDocumento=='AS')
		{
			$mensaje='El paciente es NN.';
			if(!$this->FormaPedirDatos($TipoDocumento,$Documento,$mensaje))
			{
				return false;
			}
			return true;
		}
		list($dbconn) = GetDBconn();
		$query = "SELECT primer_apellido,
												segundo_apellido,
												primer_nombre,
												segundo_nombre,
												residencia_telefono,
												sexo_id,
												residencia_direccion
                FROM pacientes WHERE tipo_id_paciente='$TipoDocumento' AND paciente_id='$Documento'";
		$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
			$datos=$result->RecordCount();
			if($datos!=0)
			{	if(!$this->FormaPedirDatos($TipoDocumento,$Documento,'',True,$Responsable,$result->fields[0],$result->fields[1],$result->fields[2],$result->fields[3],$result->fields[4],$result->fields[5],$result->fields[6]))
				{
					return false;
				}
				return true;
			}
      else
			{
				$mensaje="El paciente no se encuentra registrado en los pacientes.";
				if(!$this->FormaPedirDatos($TipoDocumento,$Documento,$mensaje,false))
				{
					return false;
				}
				return true;
			}
	}*/





/**
* Esta funcion genera un listado de los diferentes tipos de cita con los que se puede clasificar una cita
*
* @access public
* @return array retorna un vector con los tipos de cita
* @param int valor del tipo de cita ha buscar
*/

	function TipoCita($cita)
	{
		list($dbconn) = GetDBconn();
		if(!empty($cita))
		{
			$dat=" where sw_anestesiologia='".$cita."'";
		}
		$query="select tipo_cita,descripcion from tipos_cita$dat;";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->EOF)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'tipos_cita' esta vacia ";
				return false;
			}
			$i=0;
			while (!$result->EOF)
			{
				$tipocita[0][$i]=$result->fields[0];
				$tipocita[1][$i]=$result->fields[1];
				$result->MoveNext();
				$i++;
			}
		}
		return $tipocita;
	}






/**
* Esta funcion genera un listado de los cargos que puede cobrarse en una consulta
*
* @access public
* @return array retorna un vector con los cargos y sus descripciones
*/

	function TipoConsulta1()
	{
		list($dbconn) = GetDBconn();
		if(!empty($_SESSION['AsignacionCitas']['cita']))
		{
			$query="select b.cargo_cita, b.descripcion from tipos_consultas_cargos as a, cargos_citas as b where a.cargo_cita=b.cargo_cita and a.tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita'].";";
			//echo $query;
		}
		else
		{
			$query="select b.cargo_cita, b.descripcion from tipos_consultas_cargos as a, cargos_citas as b where a.cargo_cita=b.cargo_cita and a.tipo_consulta_id=".$_SESSION['LiquidarCitas']['cita'].";";
		}
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->EOF)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'tipo_consultas_cargos' esta vacia ";
				return false;
			}
			$i=0;
			while (!$result->EOF)
			{
				$tipoconsulta[0][$i]=$result->fields[0];
				$tipoconsulta[1][$i]=$result->fields[1];
				$result->MoveNext();
				$i++;
			}
		}
		return $tipoconsulta;
	}





/**
* Esta funcion identifica los datos basicos que se necesitan para generar una orden de cita
*
* @access public
* @return boolean retorna verdadero si se pudo conseguir la revision completa y falso si no se pudo
*/

	function GuardarCita()
	{
		if(empty($_SESSION['AsignacionCitas']['cita']))
		{
			$_SESSION['LiquidarCitas']['PrimerApellido']=strtoupper($_REQUEST['PrimerApellido']);
			$_SESSION['LiquidarCitas']['SegundoApellido']=strtoupper($_REQUEST['SegundoApellido']);
			$_SESSION['LiquidarCitas']['PrimerNombre']=strtoupper($_REQUEST['PrimerNombre']);
			$_SESSION['LiquidarCitas']['SegundoNombre']=strtoupper($_REQUEST['SegundoNombre']);
			$_SESSION['LiquidarCitas']['Telefono']=$_REQUEST['Telefono'];
			$_SESSION['LiquidarCitas']['Sexo']=$_REQUEST['Sexo'];
			$_SESSION['LiquidarCitas']['Existe']=$_REQUEST['Existe'];
			$_SESSION['LiquidarCitas']['Direccion']=urldecode($_REQUEST['Direccion']);
			$_SESSION['LiquidarCitas']['TipoCita']=$_REQUEST['TipoCita'];
			$_SESSION['LiquidarCitas']['Observacion']=$_REQUEST['Observacion'];
			$Responsable=$_SESSION['LiquidarCitas']['Responsable'];
			$PacienteId=$_SESSION['LiquidarCitas']['Documento'];
			$PrimerApellido=$_SESSION['LiquidarCitas']['PrimerApellido'];
			$SegundoApellido=$_SESSION['LiquidarCitas']['SegundoApellido'];
			$PrimerNombre=$_SESSION['LiquidarCitas']['PrimerNombre'];
			$SegundoNombre=$_SESSION['LiquidarCitas']['SegundoNombre'];
			$Telefono=$_SESSION['LiquidarCitas']['Telefono'];
			$TipoId=$_SESSION['LiquidarCitas']['TipoDocumento'];
			$Sexo=$_SESSION['LiquidarCitas']['Sexo'];
			$Existe=$_SESSION['LiquidarCitas']['Existe'];
			$Direccion=$_SESSION['LiquidarCitas']['Direccion'];
			$TipoCita=$_SESSION['LiquidarCitas']['TipoCita'];
			$TipoConsulta=$_SESSION['LiquidarCitas']['TipoConsulta'];
			$tipoafiliado=$_SESSION['LiquidarCitas']['tipo_afiliado_id'];
			$rango=$_SESSION['LiquidarCitas']['rango'];
		}
		else
		{
			$_SESSION['AsignacionCitas']['PrimerApellido']=strtoupper($_REQUEST['PrimerApellido']);
			$_SESSION['AsignacionCitas']['SegundoApellido']=strtoupper($_REQUEST['SegundoApellido']);
			$_SESSION['AsignacionCitas']['PrimerNombre']=strtoupper($_REQUEST['PrimerNombre']);
			$_SESSION['AsignacionCitas']['SegundoNombre']=strtoupper($_REQUEST['SegundoNombre']);
			$_SESSION['AsignacionCitas']['Telefono']=$_REQUEST['Telefono'];
			$_SESSION['AsignacionCitas']['Sexo']=$_REQUEST['Sexo'];
			//$_SESSION['AsignacionCitas']['Existe']=$_REQUEST['Existe'];
			$_SESSION['AsignacionCitas']['Direccion']=urldecode($_REQUEST['Direccion']);
			$_SESSION['AsignacionCitas']['TipoCita']=$_REQUEST['TipoCita'];
			$_SESSION['AsignacionCitas']['Observacion']=$_REQUEST['Observacion'];
			$_SESSION['AsignacionCitas']['Nivel']=$_REQUEST['Nivel'];
			$Responsable=$_SESSION['AsignacionCitas']['Responsable'];
			$PacienteId=$_SESSION['AsignacionCitas']['Documento'];
			$PrimerApellido=$_SESSION['AsignacionCitas']['PrimerApellido'];
			$SegundoApellido=$_SESSION['AsignacionCitas']['SegundoApellido'];
			$PrimerNombre=$_SESSION['AsignacionCitas']['PrimerNombre'];
			$SegundoNombre=$_SESSION['AsignacionCitas']['SegundoNombre'];
			$Telefono=$_SESSION['AsignacionCitas']['Telefono'];
			$TipoId=$_SESSION['AsignacionCitas']['TipoDocumento'];
			$Sexo=$_SESSION['AsignacionCitas']['Sexo'];
			$Existe=$_SESSION['AsignacionCitas']['Existe'];
			$Direccion=$_SESSION['AsignacionCitas']['Direccion'];
			$TipoCita=$_SESSION['AsignacionCitas']['TipoCita'];
			$TipoConsulta=$_SESSION['AsignacionCitas']['TipoConsulta'];
			$tipoafiliado=$_SESSION['AsignacionCitas']['tipo_afiliado_id'];
			$rango=$_SESSION['AsignacionCitas']['rango'];
		}
		if($TipoId=='AS' or $TipoId=='MS')
		{
			if($Responsable==-1 || $Sexo==-1 || empty($PrimerNombre) || empty($PrimerApellido) || empty($Telefono) || $TipoCita==-1 || $TipoConsulta==-1)
			{
				if($Responsable==-1){ $this->frmError["Responsable"]=1; }
				if($Sexo==-1){ $this->frmError["Sexo"]=1; }
				if(empty($PrimerNombre)){$this->frmError["PrimerNombre"]=1;}
				if(empty($PrimerApellido)){$this->frmError["PrimerApellido"]=1;}
				if(empty($Telefono)){$this->frmError["Telefono"]=1;}
				if($TipoCita==-1){$this->frmError["TipoCita"]=1;}
				if($TipoConsulta==-1){$this->frmError["TipoConsulta"]=1;}
				$this->frmError["MensajeError"]="Faltan datos obligatorios.";
				if(!$this->FormaPedirDatos($TipoId, $PacienteId, 'El paciente es NN.', false, $Responsable, $PrimerApellido, $SegundoApellido, $PrimerNombre, $SegundoNombre, $Telefono, $Sexo,$Direccion))
				{
					return false;
				}
				return true;
			}
		}
			else{
						if(!$PacienteId || !$TipoId || $Sexo==-1 || !$PrimerNombre || !$PrimerApellido|| empty($PrimerNombre) || empty($PrimerApellido) || empty($Telefono) || $Responsable==-1 || $TipoCita==-1 || $TipoConsulta==-1 || $Nivel==-1){
									if($Responsable==-1){ $this->frmError["Responsable"]=1; }
									if(!$PacienteId){ $this->frmError["PacienteId"]=1; }
									if(!$TipoId){ $this->frmError["TipoId"]=1; }
									if(!$PrimerNombre){ $this->frmError["PrimerNombre"]=1; }
									if(!$PrimerApellido){ $this->frmError["PrimerApellido"]=1; }
									if(empty($Telefono)){$this->frmError["Telefono"]=1;}
									if($TipoCita==-1){$this->frmError["TipoCita"]=1;}
									if($TipoConsulta==-1){$this->frmError["TipoConsulta"]=1;}
									if($Sexo==-1){ $this->frmError["Sexo"]=1; }
										$this->frmError["MensajeError"]="Faltan datos obligatorios.";
										$accion=ModuloGetURL('app','Triage','user','ValidarDatosPacienteNew');
										if(!$this->FormaPedirDatos($TipoId,$PacienteId,'El paciente no se encuentra registrado en los pacientes.',$Existe,$Responsable,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$Telefono,$Sexo,$Direccion)){
												return false;
										}
										return true;
						}
			}
		if(!empty($_SESSION['CumplirCita']['cita']))
		{
			if($this->InsertarDatosPaciente()==false)
			{
				return false;
			}
		}
		else
		{
			if(empty($_SESSION['AsignacionCitas']['cita']))
			{
				if(!$this->LiquidacionCita())
				{
					return false;
				}
			}
			else
			{
				if(!$this->EscogerBusqueda())
				{
					return false;
				}
			}
		}
			return true;
	}





/**
* Esta funcion retorna la descripcion del servicio del departamento en donde se esta asignando la cita
*
* @access public
* @return string retorna la descripcion del servicio
* @param int identificador del servicio
*/


	function BuscarDescripcionServicio($serv)
	{
		list($dbconn) = GetDBconn();
		$sql="select descripcion from servicios where servicio='".$serv."'";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $dbconn->ErrorMsg();
			return false;
		}
		return $result->fields[0];
	}





/**
* Esta funcion realiza el llamado a la funcion de eliminar citas y retorna a escoger busqueda
*
* @access public
* @return boolean retorna verdadero si se realizo con exito todo el proceso y falso si no fue asi
*/

	function EliminarCitasEscogerBusqueda()
	{
		$this->EliminarCita();
		//echo $this->mensajeDeError;
		if($this->EscogerBusqueda()==false)
		{
			return false;
		}
		return true;
	}





/**
* Esta funcion cambia el responsable de la atencion
*
* @access public
* @return boolean retorna verdadero si se realizo con exito todo el proceso y falso si no fue asi
*/

	function CambiarValorResponsable()
	{
		if($_REQUEST['Responsable']!=$_REQUEST['CUMPLIR']['plan'])
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$sql="update os_ordenes_servicios set plan_id=".$_REQUEST['Responsable']." where orden_servicio_id=".$_REQUEST['CUMPLIR']['orden_servicio_id'].";";
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			$sql="update agenda_citas_asignadas set plan_id=".$_REQUEST['Responsable']." where agenda_cita_asignada_id=".$_REQUEST['CUMPLIR']['agenda_cita_asignada_id'].";";
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			$dbconn->CommitTrans();
		}
		if($this->ListadoCitasCumplidas()==false)
		{
			return false;
		}
		return true;
	}




/**
* Esta funcion llama la funcion de cambio de responsable
*
* @access public
* @return boolean retorna verdadero si se realizo con exito todo el proceso y falso si no fue asi
*/

	function CambioResponsable()
	{
		if($this->ResponsableActual()==false)
		{
			return false;
		}
		return true;
	}





/**
* Esta funcion elimina la cita completamente, solo puede realizarse en el momento de asignar la cita.
*
* @access public
* @return boolean retorna verdadero si se realizo con exito todo el proceso y falso si no fue asi
*/

	function EliminarCita()
	{
		list($dbconn) = GetDBconn();
		/*os_cruce_citas numero_orden_id
		os_maestro_cargos numero_orden_id
		os_internas numero_orden_id
		os_maestro numero_orden_id
		os_ordenes_servicios orden_servicio_id
		hc_os_autorizaciones hc_os_solicitud_id
		hc_os_solicitudes_citas hc_os_solicitud_id
		hc_os_solicitudes hc_os_solicitud_id
		agenda_citas_asignadas agenda_cita_asignada_id
		agenda_citas agenda_cita_id*/
		$dbconn->BeginTrans();
		$sql="select b.numero_orden_id, b.orden_servicio_id, b.hc_os_solicitud_id  from os_cruce_citas as a, os_maestro as b  where a.agenda_cita_asignada_id=".$_REQUEST['idcita']." and a.numero_orden_id=b.numero_orden_id;";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$datos=$result->GetRowAssoc(false);
		$sql="select a.agenda_cita_id from agenda_citas_asignadas as a where a.agenda_cita_asignada_id=".$_REQUEST['idcita'].";";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$datos1=$result->fields[0];
		$sql="delete from os_cruce_citas where numero_orden_id=".$datos['numero_orden_id'].";";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from os_maestro_cargos where numero_orden_id=".$datos['numero_orden_id'].";";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from os_internas where numero_orden_id=".$datos['numero_orden_id'].";";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from os_maestro where numero_orden_id=".$datos['numero_orden_id'].";";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from os_ordenes_servicios_empleadores where orden_servicio_id=".$datos['orden_servicio_id'].";";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from os_ordenes_servicios where orden_servicio_id=".$datos['orden_servicio_id'].";";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from hc_os_autorizaciones where hc_os_solicitud_id=".$datos['hc_os_solicitud_id'].";";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from hc_os_solicitudes_citas where hc_os_solicitud_id=".$datos['hc_os_solicitud_id'].";";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from hc_os_solicitudes where hc_os_solicitud_id=".$datos['hc_os_solicitud_id'].";";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from agenda_citas_asignadas where agenda_cita_asignada_id=".$_REQUEST['idcita'].";";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="update agenda_citas set sw_estado=sw_estado-1 where agenda_cita_id=".$datos1.";";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$dbconn->CommitTrans();
		unset($_SESSION['AsignacionCitas']['idcitas'][$_REQUEST['idcita']]);
		return true;
	}






/**
* Esta funcion realiza la consulta con el id de la cita para buscar la informacion referente a la misma.
*
* @access public
* @return array retorna un vector con los datos de la cita
* @param int identificador de la cita
*/

	function BuscarInformacionCita($cita)
	{
		list($dbconn) = GetDBconn();
		$sql="select $cita as id_cita, fecha_turno || ' ' || hora as fecha, nombre_tercero, f.sw_estado,
					c.consultorio_id, g.descripcion, hora
					from agenda_citas_asignadas as a, agenda_citas as b,
					agenda_turnos as c
					left join tipos_consultorios as g on(c.consultorio_id=g.tipo_consultorio),
					terceros as d, os_cruce_citas as e, os_maestro as f
					where a.agenda_cita_asignada_id=$cita and e.agenda_cita_asignada_id=a.agenda_cita_asignada_id
					and e.numero_orden_id=f.numero_orden_id and a.agenda_cita_id=b.agenda_cita_id
					and b.agenda_turno_id=c.agenda_turno_id and c.profesional_id=d.tercero_id
					and c.tipo_id_profesional=d.tipo_id_tercero;";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $dbconn->ErrorMsg();
			return false;
		}
		$datos=$result->GetRowAssoc(false);
		return $datos;
	}





/**
* Esta funcion realiza la insercion de la cita y la creacion de la orden de servicio si no existe, si existe solo asocia la cita con esta orden.
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no fue asi.
*/


	function InsertarDatosPaciente()
	{
		$_SESSION['AsignacionCitas']['servicio']=$this->BusquedaServicio($_SESSION['AsignacionCitas']['departamento']);
		list($dbconn) = GetDBconn();
		if(!empty($_SESSION['DatosCitas']))
		{
			foreach($_SESSION['DatosCitas'] as $k=>$v)
			{
				$_REQUEST[$k]=$v;
			}
			unset($_SESSION['DatosCitas']);
		}
		else
		{
			$a=explode(",",$_SESSION['AsignacionCitas']['profesional']);
			$sql="select b.agenda_cita_id from agenda_turnos as a, agenda_citas as b where tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and profesional_id='".$a[0]."' and tipo_id_profesional='".$a[1]."' and date(fecha_turno)=date('".$_REQUEST['DiaEspe']."') and empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and a.agenda_turno_id=b.agenda_turno_id and (";
			$ban=0;
			foreach($_REQUEST as $v=>$dato)
			{
				if(substr_count ($v,'seleccion')==1)
				{
					if($ban==0)
					{
						$ban=1;
					}
					else
					{
						$sql.=" or ";
					}
					$sql.="b.agenda_cita_id='".$dato."'";
				}
			}
			$sql.=");";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				if($this->EscogerBusqueda()==false)
				{
					return false;
				}

				$sql="ROLLBACK";
				$dbconn->Execute($sql);
				return true;
			}
			if(!$result->EOF)
			{
				//CAMBIO DAR
	  		$query = "SELECT a.tipo_consulta_id, b.paciente_id
									FROM agenda_turnos as a, agenda_citas_asignadas as b, agenda_citas as c
									WHERE date(a.fecha_turno)=date('".$_REQUEST['DiaEspe']."')
									AND a.tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']."
									AND c.agenda_turno_id=a.agenda_turno_id
									AND b.agenda_cita_id=c.agenda_cita_id
									AND b.sw_atencion=0
									AND b.tipo_id_paciente='".$_SESSION['AsignacionCitas']['TipoDocumento']."'
									AND b.paciente_id='".$_SESSION['AsignacionCitas']['Documento']."'";
				$resultd = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				if(!$resultd->EOF)
				{
						$this->frmError["MensajeError"]="ESTE PACIENTE YA TIENE ASIGNADA CITA PARA HOY.";
						$this->EscogerBusqueda();
						return true;
				}
				//FIN CAMBIO DAR
				$a=$result->fields[0];
				$result->close();
				$sql="select count(a.*) from agenda_citas as a, agenda_turnos as b where a.agenda_turno_id=b.agenda_turno_id and a.agenda_cita_id=$a and a.sw_estado<b.cantidad_pacientes";
				$result1 = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB a: " . $dbconn->ErrorMsg();
					$sql="ROLLBACK";
					$dbconn->Execute($sql);
					return false;
				}
				if($result1->fields[0]==0)
				{
					$this->EscogerBusqueda(1);
					return true;
				}
			}
		}
		$a=0;
		unset($_SESSION['DatosCitas']);
		foreach($_REQUEST as $v=>$dato)
		{
			if (substr_count ($v,'tmpcita')==1)
			{
				$a=1;
				break;
			}
		}
		$dbconn->BeginTrans();
		if($a==0)
		{
			$tmpcita=$this->SetCitasTmp($this->SostenerCitas(),&$dbconn);
		}
		$i=0;
		while($i<sizeof($tmpcita))
		{
			$tmp='tmpcita';
			$tmp=$tmp.$i;
			$_REQUEST[$tmp]=$tmpcita[$i];
			$i++;
		}
		if($_SESSION['AsignacionCitas']['Existe'])
		{
				$sql="update pacientes set residencia_direccion='".$_SESSION['AsignacionCitas']['Direccion']."',
							residencia_telefono='".$_SESSION['AsignacionCitas']['Telefono']."'
							where paciente_id='".$_SESSION['AsignacionCitas']['Documento']."'
							and tipo_id_paciente='".$_SESSION['AsignacionCitas']['TipoDocumento']."';";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB a: " . $dbconn->ErrorMsg();
					$sql="ROLLBACK";
					$dbconn->Execute($sql);
					return false;
				}
		}
		else
		{
				$sql="insert into pacientes(paciente_id, tipo_id_paciente, primer_apellido, segundo_apellido, primer_nombre,
							segundo_nombre, residencia_direccion, residencia_telefono, sexo_id, fecha_registro, tipo_pais_id,
							tipo_dpto_id, tipo_mpio_id, usuario_id, zona_residencia)
							values ('".$_SESSION['AsignacionCitas']['Documento']."', '".$_SESSION['AsignacionCitas']['TipoDocumento']."', '".$_SESSION['AsignacionCitas']['PrimerApellido']."', '".$_SESSION['AsignacionCitas']['SegundoApellido']."',
							'".$_SESSION['AsignacionCitas']['PrimerNombre']."', '".$_SESSION['AsignacionCitas']['SegundoNombre']."', '".$_SESSION['AsignacionCitas']['Direccion']."',
							'".$_SESSION['AsignacionCitas']['Telefono']."', '".$_SESSION['AsignacionCitas']['Sexo']."', '".date("Y-m-d H:i:s")."', '".GetVarConfigAplication('DefaultPais')."', '".GetVarConfigAplication('DefaultDpto')."', '".GetVarConfigAplication('DefaultMpio')."',
							".$_SESSION['SYSTEM_USUARIO_ID'].", '".GetVarConfigAplication('DefaultZona')."');";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB a: " . $dbconn->ErrorMsg();
					$sql="ROLLBACK";
					$dbconn->Execute($sql);
					return false;
				}

				$query = "INSERT INTO historias_clinicas( tipo_id_paciente,
																										paciente_id,
																										historia_numero,
																										historia_prefijo,
																										fecha_creacion)
									VALUES ('".$_SESSION['AsignacionCitas']['TipoDocumento']."','".$_SESSION['AsignacionCitas']['Documento']."','','','now()')";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB a: " . $dbconn->ErrorMsg();
					$sql="ROLLBACK";
					$dbconn->Execute($query);
					return false;
				}
				//OJO: REVISAR ESTO SI HAY ERROR
				//$_SESSION['AsignacionCitas']['Existe']=true;
		}

		foreach($_REQUEST as $v=>$dato)
		{
			if(substr_count ($v,'tmpcita')==1)
			{
				$sql="select agenda_cita_id from tmp_citas_asignacion where tmp_cita_asignacion_id=".$dato.";";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB q: " . $dbconn->ErrorMsg();
					//$dbconn->RollbackTrans();
					$sql="ROLLBACK";
					$dbconn->Execute($sql);
					return false;
				}
				if(!$result->EOF)
				{
					$a=$result->fields[0];
				}
				$sql="select fecha_turno || ' ' || hora as fecha, hora from agenda_citas as a,agenda_turnos as b where agenda_cita_id=$a and a.agenda_turno_id=b.agenda_turno_id;";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB u: " . $dbconn->ErrorMsg();
					//$dbconn->RollbackTrans();
					$sql="ROLLBACK";
					$dbconn->Execute($sql);
					return false;
				}
				if(!$result->EOF)
				{
					$c=$result->fields[0];
				}
				//CAMBIO DAR
				if(date($_REQUEST['DiaEspe'])==date('Y-m-d'))
				{
						if($result->fields[1] <= date('H:i'))
						{
								$this->frmError["MensajeError"]="DEBE ELEGIR UNA HORA MAYOR A LA ACTUAL.";
								$this->EscogerBusqueda();
								return true;
						}
				}
				//FIN CAMBIO DAR
				$sql="select nextval('agenda_citas_asignadas_agenda_cita_asignada_id_seq');";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB i: " . $dbconn->ErrorMsg();
					//$dbconn->RollbackTrans();
					$sql="ROLLBACK";
					$dbconn->Execute($sql);
					return false;
				}
				if(!$result->EOF)
				{
					$b=$result->fields[0];
				}
				if(empty($_REQUEST['historia']))
				{
					 $sql="insert into agenda_citas_asignadas (agenda_cita_asignada_id, agenda_cita_id, paciente_id, tipo_id_paciente, tipo_cita, plan_id,cargo_cita,observacion,usuario_id) values(".$b.",".$a.",'".$_SESSION['AsignacionCitas']['Documento']."','".$_SESSION['AsignacionCitas']['TipoDocumento']."', '".$_SESSION['AsignacionCitas']['TipoCita']."','".$_SESSION['AsignacionCitas']['Responsable']."', '".$_SESSION['AsignacionCitas']['cargo_cups']."','".$_SESSION['AsignacionCitas']['Observacion']."',".UserGetUID().");";
				}
				else
				{
					$sql="insert into agenda_citas_asignadas (agenda_cita_asignada_id, agenda_cita_id, paciente_id, tipo_id_paciente, tipo_cita, plan_id,cargo_cita,observacion,usuario_id, sw_historia) values(".$b.",".$a.",'".$_SESSION['AsignacionCitas']['Documento']."','".$_SESSION['AsignacionCitas']['TipoDocumento']."', '".$_SESSION['AsignacionCitas']['TipoCita']."','".$_SESSION['AsignacionCitas']['Responsable']."', '".$_SESSION['AsignacionCitas']['cargo_cups']."','".$_SESSION['AsignacionCitas']['Observacion']."',".UserGetUID().", '1');";
				}
				$_SESSION['AsignacionCitas']['idcitas'][$b]=$b;
				$_SESSION['AsignacionCitas']['citaasignada']=$b;
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError["MensajeError"]="YA EXISTE UNA CITA ASIGNADA EN ESTE TURNO.";
					$this->EscogerBusqueda();
					unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
					//$dbconn->RollbackTrans();
					$sql="ROLLBACK";
					$dbconn->Execute($sql);
					return true;
				}
				$sql="delete from tmp_citas_asignacion where tmp_cita_asignacion_id=".$dato.";";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB s: " . $dbconn->ErrorMsg();
					unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
					//$dbconn->RollbackTrans();
					$sql="ROLLBACK";
					$dbconn->Execute($sql);
					return false;
				}
				$sql="select a.tarifario_id, a.cargo,b.descripcion from tarifarios_equivalencias as a, tarifarios_detalle as b, plan_tarifario as c where cargo_base='".$_SESSION['AsignacionCitas']['cargo_cups']."' and a.tarifario_id=b.tarifario_id and a.cargo=b.cargo and b.grupo_tarifario_id=c.grupo_tarifario_id and b.subgrupo_tarifario_id=c.subgrupo_tarifario_id and c.plan_id=".$_SESSION['AsignacionCitas']['Responsable']." and b.tarifario_id=c.tarifario_id;";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg();
					unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
					//$dbconn->RollbackTrans();
					$sql="ROLLBACK";
					$dbconn->Execute($sql);
					return false;
				}
				$tarifario=$result->fields[0];
				$cargo=$result->fields[1];
				$_SESSION['AsignacionCitas']['tarifario']=$tarifario;
				$_SESSION['AsignacionCitas']['cargo']=$cargo;
				$_SESSION['AsignacionCitas']['descripcioncargo']=$result->fields[2];
				$_SESSION['AsignacionCitas']['cantidad']=1;
				//desde aqui lo de hc_os_solicitudes y todo eso
				if(!empty($_SESSION['AsignacionCitas']['NumeroOrden']))
				{
					$_SESSION['AsignacionCitas']['numero_orden_id']=$_SESSION['AsignacionCitas']['NumeroOrden'];
					$sql="select os_maestro_cargos_id from os_maestro_cargos where numero_orden_id=".$_SESSION['AsignacionCitas']['numero_orden_id'];
					$r = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg();
						//$dbconn->RollbackTrans();
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						return false;
					}
					$_SESSION['AsignacionCitas']['os_maestro_cargos_id']=$r->fields[0];
					unset($r);
					$sql="insert into os_cruce_citas(numero_orden_id, agenda_cita_asignada_id) values(".$_SESSION['AsignacionCitas']['NumeroOrden'].", $b);";
					$r = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg();
						unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
						//$dbconn->RollbackTrans();
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						return false;
					}
				}
				else
				{

					$_SESSION['AsignacionCitas']['servicio']=$this->BusquedaServicio($_SESSION['AsignacionCitas']['departamento']);
					$sql="select nextval('hc_os_solicitudes_hc_os_solicitud_id_seq');";
					$result1 = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg();
						unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
						//$dbconn->RollbackTrans();
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						return false;
					}
					$sql="insert into hc_os_solicitudes (hc_os_solicitud_id, cargo, plan_id, os_tipo_solicitud_id, sw_estado) values (".$result1->fields[0].", '".$_SESSION['AsignacionCitas']['cargo_cups']."', ".$_SESSION['AsignacionCitas']['Responsable'].", 'CIT', '0');";
					$r = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg();
						unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
						//$dbconn->RollbackTrans();
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						return false;
					}
					$sql="insert into hc_os_solicitudes_citas (hc_os_solicitud_id, tipo_consulta_id) values (".$result1->fields[0].", ".$_SESSION['AsignacionCitas']['cita'].");";
					$r = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg();
						unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
						//$dbconn->RollbackTrans();
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						return false;
					}
					$sql="insert into hc_os_autorizaciones (hc_os_solicitud_id, autorizacion_int, autorizacion_ext) values (".$result1->fields[0].", ".$_SESSION['AsignacionCitas']['NumAutorizacion'].", ".$_SESSION['AsignacionCitas']['NumAutorizacion'].");";
					$r = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg();
						unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
						//$dbconn->RollbackTrans();
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						return false;
					}
					$sql="select nextval('os_ordenes_servicios_orden_servicio_id_seq');";
					$result2 = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg();
						unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
						//$dbconn->RollbackTrans();
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						return false;
					}
					$sql="insert into os_ordenes_servicios (orden_servicio_id, autorizacion_int, autorizacion_ext, plan_id, tipo_afiliado_id, rango, semanas_cotizadas, servicio, tipo_id_paciente, paciente_id, usuario_id, fecha_registro) values (".$result2->fields[0].", ".$_SESSION['AsignacionCitas']['NumAutorizacion'].", ".$_SESSION['AsignacionCitas']['NumAutorizacion'].", ".$_SESSION['AsignacionCitas']['Responsable'].", '".$_SESSION['AsignacionCitas']['tipo_afiliado_id']."', '".$_SESSION['AsignacionCitas']['rango']."', '".$_SESSION['AsignacionCitas']['semanas']."', '".$_SESSION['AsignacionCitas']['servicio']."', '".$_SESSION['AsignacionCitas']['TipoDocumento']."', '".$_SESSION['AsignacionCitas']['Documento']."', ".UserGetUID().", '".date("Y-m-d H:i:s")."');";
					$r = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg();
						unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
						//$dbconn->RollbackTrans();
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						return false;
					}
					//cambio dar
					//hay datos del empleador
					if(!empty($_SESSION['EMPLEADOR']))
					{
							$query = "INSERT INTO os_ordenes_servicios_empleadores(
																											orden_servicio_id,
																											tipo_id_empleador,
																											empleador_id)
												VALUES(".$result2->fields[0].",'".$_SESSION['EMPLEADOR']['tipo_empleador']."','".$_SESSION['EMPLEADOR']['id_empleador']."')";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error INSERT INTO os_ordenes_servicios_empleadores ";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollbackTrans();
									return false;
							}
					}
					//fin cambio dar

					$sql="select nextval('os_maestro_numero_orden_id_seq');";
					$result4 = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						return false;
					}
					if($c<date("Y-m-d H:m"))
					{
						$c=date("Y-m-d H:m",mktime(24,24,24,date("m"),date("d"),date("Y")));
					}
					$_SESSION['AsignacionCitas']['numero_orden_id']=$result4->fields[0];
					if(empty($_SESSION['CumplirCita']['cita']))
					{
						$sql="insert into os_maestro (numero_orden_id, orden_servicio_id, fecha_vencimiento, hc_os_solicitud_id, cargo_cups) values (".$result4->fields[0].", ".$result2->fields[0].", '".$c."', ".$result1->fields[0].",'".$_SESSION['AsignacionCitas']['cargo_cups']."');";
					}
					else
					{
						$sql="insert into os_maestro (numero_orden_id, orden_servicio_id, fecha_vencimiento, hc_os_solicitud_id, cargo_cups, sw_estado) values (".$result4->fields[0].", ".$result2->fields[0].", '".$c."', ".$result1->fields[0].",'".$_SESSION['AsignacionCitas']['cargo_cups']."', '5');";
					}
					$r = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB k: " . $dbconn->ErrorMsg();
						unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						//$dbconn->RollbackTrans();
						return false;
					}
					$sql="insert into os_internas(numero_orden_id, cargo, departamento) values(".$result4->fields[0].", '".$_SESSION['AsignacionCitas']['cargo_cups']."', '".$_SESSION['AsignacionCitas']['departamento']."');";
					$r = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg();
						unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						//$dbconn->RollbackTrans();
						return false;
					}
					$sql="insert into os_cruce_citas(numero_orden_id, agenda_cita_asignada_id) values(".$result4->fields[0].", $b);";
					$r = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg();
						unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						//$dbconn->RollbackTrans();
						return false;
					}
					$sql="select nextval('os_maestro_cargos_os_maestro_cargos_id_seq');";
					$result5 = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						return false;
					}
					$_SESSION['AsignacionCitas']['os_maestro_cargos_id']=$result5->fields[0];
					$sql="insert into os_maestro_cargos (os_maestro_cargos_id, numero_orden_id, tarifario_id, cargo) values (".$result5->fields[0].", ".$result4->fields[0].", '$tarifario', '$cargo');";
					$r = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg();
						unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						//$dbconn->RollbackTrans();
						return false;
					}
				}
				unset($_REQUEST[$v]);
			}
		}
		$_SESSION['AsignacionCitas']['Existe']='1';
		//$dbconn->CommitTrans();
		$sql="COMMIT";
		$dbconn->Execute($sql);
		if(!empty($_SESSION['CumplirCita']['cita']))
		{
			unset($_SESSION['AsignacionCitas']);
			$this->ListadoCitasCumplidas();
		}
		else
		{
			$this->PantallaFinal();
		}
		return true;
	}





/**
* Esta funcion realiza la busqueda del servicio con el departamento.
*
* @access public
* @return int identificador del servicio.
* @param string identificacion del departamento
*/


	function BusquedaServicio($depto)
	{
		list($dbconn) = GetDBconn();
		$sql="select servicio from departamentos where departamento='".$depto."';";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $result->fields[0];
	}





/**
* Esta funcion realiza la busqueda del protocolo del plan al cual se le esta asignando la cita
*
* @access public
* @return string url del protocolo del plan.
*/

	function Protocolo()
	{
		$PlanId=$_SESSION['AsignacionCitas']['Responsable'];
		list($dbconn) = GetDBconn();
		$query = "select protocolos from planes
												where plan_id='$PlanId'";
		$result=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
		}

		//$result->Close();
		return $result->fields[0];
	}




/**
* Esta funcion realiza la busqueda del permiso que tiene un usuario para cancelar una cita medica
*
* @access public
* @return boolean retorna verdadero si se realizo todo el proceso y falso si no fue asi.
*/


	function BuscarPermiso()
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		if(!empty($_SESSION['AsignacionCitas']['departamento']))
		{
			$query = "SELECT e.descripcion, d.servicio, d.via_ingreso, a.caja_id, d.departamento, d.descripcion as descripcion3, d.prefijo_fac_contado, d.prefijo_fac_credito,  d.tipo_num_recibos,
										c.descripcion as descripcion2, c.centro_utilidad, c.empresa_id, b.razon_social as descripcion1,
										d.tipo_factura_id
										FROM userpermisos_cajas_rapidas as a, empresas as b, departamentos as c,
										cajas_rapidas as d, centros_utilidad as e
										WHERE a.usuario_id=".UserGetUID()." and d.departamento=c.departamento and d.departamento='".$_SESSION['AsignacionCitas']['departamento']."'
										and c.empresa_id=b.empresa_id and a.caja_id=d.caja_id
										and e.centro_utilidad=c.centro_utilidad and e.empresa_id=c.empresa_id";
		}
		else
		{
			$query = "SELECT e.descripcion, d.servicio, d.via_ingreso, a.caja_id, d.departamento, d.descripcion as descripcion3, d.prefijo_fac_contado, d.prefijo_fac_credito,  d.tipo_num_recibos,
										c.descripcion as descripcion2, c.centro_utilidad, c.empresa_id, b.razon_social as descripcion1,
										d.tipo_factura_id
										FROM userpermisos_cajas_rapidas as a, empresas as b, departamentos as c,
										cajas_rapidas as d, centros_utilidad as e
										WHERE a.usuario_id=".UserGetUID()." and d.departamento=c.departamento and d.departamento='".$_SESSION['CumplirCita']['departamento']."'
										and c.empresa_id=b.empresa_id and a.caja_id=d.caja_id
										and e.centro_utilidad=c.centro_utilidad and e.empresa_id=c.empresa_id";
		}
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta=$dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
		}
		if(!empty($_SESSION['AsignacionCitas']['cita']))
		{
			$_SESSION['AsignacionCitas']['cuantascajas']=$resulta->RecordCount();
		}
		else
		{
			$_SESSION['CumplirCita']['cuantascajas']=$resulta->RecordCount();
		}
		while ($data = $resulta->FetchRow())
		{
				$centro[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
				$seguridad[$data['empresa_id']][$data['departamento']][$data['caja_id']]=1;
		}
		$url[0]='app';
		$url[1]='CajaGeneral';
		$url[2]='user';
		$url[3]='CajaRapida';
		$url[4]='Caja';
		$arreglo[0]='EMPRESA';
		$arreglo[1]='DEPARTAMENTO';
		$arreglo[2]='CAJA RAPIDA';
		$_SESSION['SEGURIDAD']['CAJARAPIDA']['arreglo']=$arreglo;
		$_SESSION['SEGURIDAD']['CAJARAPIDA']['caja']=$centro;
		$_SESSION['SEGURIDAD']['CAJARAPIDA']['url']=$url;
		$_SESSION['SEGURIDAD']['CAJARAPIDA']['puntos']=$seguridad;
		return true;
	}

// 	function RetornoAutorizacionNueva()
// 	{
// 		if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']))
// 		{
// 			return true;
// 		}
// 		else
// 		{
// 			return true;
// 		}
// 	}




/**
* Esta funcion redirecciona el link de caja rapida cuando existe mas de una opcion para la misma
*
* @access public
* @return boolean retorna verdadero si se realizo todo el proceso y falso si no fue asi.
*/



		function MenuCaja()
		{
			$datos=array('vector'=>$_REQUEST['vector'],'nom'=>$_REQUEST['nom'],'tipoid'=>$_REQUEST['tipoid'],'id'=>$_REQUEST['id'],'afiliado'=>$_REQUEST['afiliado'],'rango'=>$_REQUEST['rango'],'sem'=>$_REQUEST['sem'],'plan'=>$_REQUEST['plan'],'auto'=>$_REQUEST['auto'],'servicio'=>$_REQUEST['servicio'],'depto'=>$_REQUEST['depto']);
			$_SESSION['CAJA']['liq']=$_REQUEST['liq'];
			$_SESSION['CAJA']['op']=$_REQUEST['op'];
			$_SESSION['CAJA']['datos']=$_REQUEST['datos'];
			$_SESSION['CAJA']['vector']=$_REQUEST['vector'];
			$_SESSION['CAJA']['arr']=$_REQUEST['arr'];
			foreach($_SESSION['SEGURIDAD']['CAJARAPIDA']['caja'] as $k=>$v)
			{
				foreach($v as $t=>$h)
				{
					foreach($h as $p=>$m)
					{
						$_SESSION['SEGURIDAD']['CAJARAPIDA']['caja'][$k][$t][$p]['datoscaja']=$datos;
					}
				}
			}
			if(empty($_SESSION['CumplirCita']['cita']))
			{
				$this->salida.= gui_theme_menu_acceso('CAJA RAPIDA',$_SESSION['SEGURIDAD']['CAJARAPIDA']['arreglo'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['caja'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['url'],ModuloGetURL('app','AgendaMedica','user','PantallaFinal',array('DiaEspe'=>$_REQUEST['DiaEspe'])));
			}
			else
			{
				$this->salida.= gui_theme_menu_acceso('CAJA RAPIDA',$_SESSION['SEGURIDAD']['CAJARAPIDA']['arreglo'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['caja'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['url'],ModuloGetURL($_SESSION['CONSULTAEXT']['RETORNO']['contenedor'],$_SESSION['CONSULTAEXT']['RETORNO']['modulo'],$_SESSION['CONSULTAEXT']['RETORNO']['tipo'],$_SESSION['CONSULTAEXT']['RETORNO']['metodo'],array()));
			}
			return true;
		}



// 	function GuardarPaciente()
// 	{
// 		$Responsable=$_REQUEST['Responsable'];
// 		$PacienteId=$_REQUEST['PacienteId'];
// 		$PrimerApellido=strtoupper($_REQUEST['PrimerApellido']);
// 		$SegundoApellido=strtoupper($_REQUEST['SegundoApellido']);
// 		$PrimerNombre=strtoupper($_REQUEST['PrimerNombre']);
// 		$SegundoNombre=strtoupper($_REQUEST['SegundoNombre']);
// 		$Telefono=$_REQUEST['Telefono'];
// 		$TipoId=$_REQUEST['TipoId'];
// 		$Sexo=$_REQUEST['Sexo'];
// 		$Existe=$_REQUEST['Existe'];
// 		$Direccion=$_REQUEST['Direccion'];
// 		$TipoCita=$_REQUEST['TipoCita'];
// 		$TipoConsulta=$_REQUEST['TipoConsulta'];
// 		if($TipoId=='AS' or $TipoId=='MS')
// 		{
// 			if($Responsable==-1 || $Sexo==-1 || empty($PrimerNombre) || empty($PrimerApellido) || empty($Telefono) || $TipoCita==-1 || $TipoConsulta==-1)
// 			{
// 				if($Responsable==-1){ $this->frmError["Responsable"]=1; }
// 				if($Sexo==-1){ $this->frmError["Sexo"]=1; }
// 				if(empty($PrimerNombre)){$this->frmError["PrimerNombre"]=1;}
// 				if(empty($PrimerApellido)){$this->frmError["PrimerApellido"]=1;}
// 				if(empty($Telefono)){$this->frmError["Telefono"]=1;}
// 				if($TipoCita==-1){$this->frmError["TipoCita"]=1;}
// 				if($TipoConsulta==-1){$this->frmError["TipoConsulta"]=1;}
// 				$this->frmError["MensajeError"]="Faltan datos obligatorios.";
// 				if(!$this->FormaPedirDatos1($TipoId, $PacienteId, 'El paciente es NN.', false, $Responsable, $PrimerApellido, $SegundoApellido, $PrimerNombre, $SegundoNombre, $Telefono, $Sexo,$Direccion))
// 				{
// 					return false;
// 				}
// 				return true;
// 			}
// 		}
// 			else{
// 						if(!$PacienteId || !$TipoId || $Sexo==-1 || !$PrimerNombre || !$PrimerApellido|| empty($PrimerNombre) || empty($PrimerApellido) || empty($Telefono) || $Responsable==-1 || $TipoCita==-1 || $TipoConsulta==-1 || empty($_REQUEST['pais']) || empty($_REQUEST['dpto']) || empty($_REQUEST['mpio']) || empty($_REQUEST['FechaNacimiento']) || $_REQUEST['Ocupacion']==-1 || $_REQUEST['EstadoCivil']==-1){
// 									if($Responsable==-1){ $this->frmError["Responsable"]=1; }
// 									if(!$PacienteId){ $this->frmError["PacienteId"]=1; }
// 									if(!$TipoId){ $this->frmError["TipoId"]=1; }
// 									if(!$PrimerNombre){ $this->frmError["PrimerNombre"]=1; }
// 									if(!$PrimerApellido){ $this->frmError["PrimerApellido"]=1; }
// 									if(empty($Telefono)){$this->frmError["Telefono"]=1;}
// 									if(empty($_REQUEST['pais'])){$this->frmError["pais"]=1;}
// 									if(empty($_REQUEST['dpto'])){$this->frmError["dpto"]=1;}
// 									if(empty($_REQUEST['mpio'])){$this->frmError["mpio"]=1;}
// 									if(empty($_REQUEST['FechaNacimiento'])){$this->frmError["FechaNacimiento"]=1;}
// 									if($_REQUEST['Ocupacion']==-1){$this->frmError["Ocupacion"]=1;}
// 									if($_REQUEST['EstadoCivil']==-1){$this->frmError["EstadoCivil"]=1;}
// 									if($TipoCita==-1){$this->frmError["TipoCita"]=1;}
// 									if($TipoConsulta==-1){$this->frmError["TipoConsulta"]=1;}
// 									if($Sexo==-1){ $this->frmError["Sexo"]=1; }
// 										$this->frmError["MensajeError"]="Faltan datos obligatorios.";
// 										$accion=ModuloGetURL('app','Triage','user','ValidarDatosPacienteNew');
// 										if(!$this->FormaPedirDatos1()){
// 												return false;
// 										}
// 										return true;
// 						}
// 			}
// 			if(!$this->InsertarDatosPaciente1())
// 			{
// 				return false;
// 			}
// 			return true;
// 	}
// 
// 	function InsertarDatosPaciente1()
// 	{
// 		list($dbconn) = GetDBconn();
// 		$dbconn->BeginTrans();
// 		$sql="insert into pacientes (paciente_id, tipo_id_paciente, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, fecha_nacimiento, residencia_direccion, residencia_telefono, zona_residencia, ocupacion_id, fecha_registro, sexo_id, tipo_estado_civil_id, tipo_pais_id, tipo_dpto_id, tipo_mpio_id, usuario_id, nombre_madre) values ('".$_REQUEST['PacienteId']."', '".$_REQUEST['TipoId']."', '".$_REQUEST['PrimerApellido']."', '".$_REQUEST['SegundoApellido']."', '".$_REQUEST['PrimerNombre']."', '".$_REQUEST['SegundoNombre']."', '".$_REQUEST['FechaNacimiento']."', '".$_REQUEST['Direccion']."', '".$_REQUEST['Telefono']."', '".$_REQUEST['Zona']."', '".$_REQUEST['Ocupacion']."', '".date("Y-m-d H:i:s")."', '".$_REQUEST['Sexo']."', '".$_REQUEST['EstadoCivil']."', '".$_REQUEST['pais']."', '".$_REQUEST['dpto']."', '".$_REQUEST['mpio']."', ".$_SESSION['SYSTEM_USUARIO_ID'].", '".$_REQUEST['Mama']."');";
// 		$result = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 			$dbconn->RollbackTrans();
// 			return false;
// 		}
// 		$sql="select agenda_cita_asignada_id from agenda_citas_asignadas_no_pacientes where paciente_id='".$_REQUEST['PacienteId']."' and tipo_id_paciente='".$_REQUEST['TipoId']."' order by agenda_cita_asignada_id;";
// 		$result = $dbconn->Execute($sql);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 			$dbconn->RollbackTrans();
// 			return false;
// 		}
// 		else
// 		{
// 			$i=0;
// 			while (!$result->EOF)
// 			{
// 				$a[$i]=$result->fields[0];
// 				$result->MoveNext();
// 				$i++;
// 			}
// 		}
// 		$i=0;
// 		while($i<sizeof($a))
// 		{
// 			$sql="update agenda_citas_asignadas set paciente_id='".$_REQUEST['PacienteId']."', tipo_id_paciente='".$_REQUEST['TipoId']."' where agenda_cita_asignada_id=".$a[$i].";";
// 			$result = $dbconn->Execute($sql);
// 			if ($dbconn->ErrorNo() != 0)
// 			{
// 				$this->error = "Error al Cargar el Modulo";
// 				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 				$dbconn->RollbackTrans();
// 				return false;
// 			}
// 			$sql="delete from agenda_citas_asignadas_no_pacientes where agenda_cita_asignada_id=".$a[$i].";";
// 			$result = $dbconn->Execute($sql);
// 			if ($dbconn->ErrorNo() != 0)
// 			{
// 				$this->error = "Error al Cargar el Modulo";
// 				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 				$dbconn->RollbackTrans();
// 				return false;
// 			}
// 			$i++;
// 		}
//  		$dbconn->CommitTrans();
// 		return true;
// 	}





/**
* Esta funcion realiza un bloqueo temporal de las citas para que no puedan ser accesadas por otro usuario hasta que este no las suelte
*
* @access public
* @return int identificacion de la cita capturada
*/


	function SostenerCitas()
	{
		$a=explode(",",$_SESSION['AsignacionCitas']['profesional']);
		if(!empty($_SESSION['CumplirCita']['DiaEspe']))
		{
			$sql="select b.agenda_cita_id from agenda_turnos as a, agenda_citas as b where tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and profesional_id='".$a[0]."' and tipo_id_profesional='".$a[1]."' and date(fecha_turno)=date('".$_SESSION['CumplirCita']['DiaEspe']."') and empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and a.agenda_turno_id=b.agenda_turno_id and (";
		}
		else
		{
			$sql="select b.agenda_cita_id from agenda_turnos as a, agenda_citas as b where tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and profesional_id='".$a[0]."' and tipo_id_profesional='".$a[1]."' and date(fecha_turno)=date('".$_REQUEST['DiaEspe']."') and empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and a.agenda_turno_id=b.agenda_turno_id and (";
		}
		$ban=0;
		foreach($_REQUEST as $v=>$dato)
		{
			if(substr_count ($v,'seleccion')==1)
			{
				if($ban==0)
				{
					$ban=1;
				}
				else
				{
					$sql.=" or ";
				}
				$sql.="b.agenda_cita_id='".$dato."'";
			}
		}
		$sql.=");";
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$sql="ROLLBACK";
			$dbconn->Execute($sql);
			return false;
		}
		$i=0;
		while (!$result->EOF)
		{
			$numerocita[$i]=$result->fields[0];
			$result->MoveNext();
			$i++;
		}
		return $numerocita;
	}


/**
* Esta funcion realiza la insercion de las citas en una tabla temporal para que no puedan ser accesadas por otro usuario
*
* @access public
* @return int identificacion de la cita en la tabla temporal
* @param array vector en donde se encuentra la inforrmacion de las citas
* @param clase clase de conexion a la base de datos
*/

	function SetCitasTmp($vec,$dbconn)
	{
		$i=0;
		//list($dbconn) = GetDBconn();
		//$dbconn->BeginTrans();
		while($i<sizeof($vec))
		{
			$sql="update agenda_citas set sw_estado=sw_estado+1 where agenda_cita_id=".$vec[$i].";";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				//$dbconn->RollbackTrans();
				$sql="ROLLBACK";
				$dbconn->Execute($sql);
				return false;
			}
			$sql="select nextval('tmp_citas_asignacion_tmp_cita_asignacion_id_seq');";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				//$dbconn->RollbackTrans();
				$sql="ROLLBACK";
				$dbconn->Execute($sql);
				return false;
			}
			if(!$result->EOF)
			{
				$a=$result->fields[0];
			}
			$tmpnum[$i]=$a;
			$sql="insert into tmp_citas_asignacion (tmp_cita_asignacion_id,agenda_cita_id,fecha_creacion) values (".$a.", ".$vec[$i].", '".date("Y-m-d H:i:s")."')";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				//$dbconn->RollbackTrans();
				$sql="ROLLBACK";
				$dbconn->Execute($sql);
				return false;
			}
			$i++;
		}
		//$dbconn->CommitTrans();
		return $tmpnum;
	}


/**
* Esta funcion realiza el borrado de las citas que se han captado como temporales
*
* @access public
* @return boolean identifica si el proceso de borrado de la tabla temporal se realizo
*/

	function DelCitasTmp()
	{
		list($dbconn) = GetDBconn();

		foreach($_REQUEST as $v=>$dato)
		{
			if(substr_count ($v,'tmpcita')==1)
			{
				$sql="update agenda_citas set sw_estado=sw_estado-1 where agenda_cita_id=(select agenda_cita_id from tmp_citas_asignacion where tmp_cita_asignacion_id=".$dato.");";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				$sql="delete from tmp_citas_asignacion where tmp_cita_asignacion_id=".$dato.";";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
		}
		$dbconn->CommitTrans();
		return true;
	}


/**
* Esta funcion muestra el listado de los profesional que tienen citas libres para atender en el dia especificado
*
* @access public
* @return array retorna vector con los datos de los profesionales
*/

	function ProfeConsul()
	{
		list($dbconn) = GetDBconn();
		$query="select distinct a.nombre_tercero as nombre, a.descripcion, a.consultorio, a.profesional_id, a.tipo_id_profesional from (select nombre_tercero, d.descripcion, c.consultorio, a.profesional_id, a.tipo_id_profesional, e.estado from agenda_turnos as a left join profesionales_estado as e on (a.profesional_id=e.tercero_id and a.tipo_id_profesional=e.tipo_id_tercero and e.empresa_id='".$_SESSION['CumplirCita']['empresa']."' and e.departamento='".$_SESSION['CumplirCita']['departamento']."') left join consultorios as c on (a.consultorio_id=c.consultorio) left join tipos_consultorios as d on(c.tipo_consultorio=d.tipo_consultorio), profesionales as b, terceros as g where a.empresa_id='".$_SESSION['CumplirCita']['empresa']."' and a.tipo_consulta_id=".$_SESSION['CumplirCita']['cita']." and a.profesional_id=b.tercero_id and a.tipo_id_profesional=b.tipo_id_tercero and a.profesional_id=g.tercero_id and a.tipo_id_profesional=g.tipo_id_tercero and date(a.fecha_turno)=date(now())) as a where a.estado is null or a.estado=1 order by a.nombre_tercero;";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while (!$result->EOF)
		{
			$profconsul[0][$i]=$result->fields[0];
			$profconsul[1][$i]=$result->fields[2];
			$profconsul[2][$i]=$result->fields[1];
			$profconsul[3][$i]=$result->fields[3];
			$profconsul[4][$i]=$result->fields[4];
			$result->MoveNext();
			$i++;
		}
		if($i<>0)
		{
			return $profconsul;
		}
		else
		{
			return false;
		}
	}






/**
* Esta funcion retorna la descripcion del cargo especifico
*
* @access public
* @return string retorna descripcion del cargo solicitado
* @param string tarifario de donde es el cargo
* @param string cargo del cual se necesita la descripcion
*/

	function BusquedaDescripcionCargo($tarifario,$cargo)
	{
		list($dbconn) = GetDBconn();
		$sql="select descripcion from tarifarios_detalle where tarifario_id='$tarifario' and cargo='$cargo';";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $result->fields[0];
	}




/**
* Esta funcion retorna el listado de las citas para cumplir
*
* @access public
* @return array retorna el listado de citas para cumplir
*/

	function ListadoCitas()
	{
		list($dbconn) = GetDBconn();
		$a=explode(",",$_SESSION['CumplirCita']['profesional']);

		$query = "SELECT agenda_turno_id FROM agenda_turnos
							WHERE date(fecha_turno)=date(now())
							and tipo_consulta_id=".$_SESSION['CumplirCita']['cita']."
							and profesional_id='".$a[0]."'
							and tipo_id_profesional='".$a[1]."'
							and empresa_id='".$_SESSION['CumplirCita']['empresa']."'";
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
				if($i==0)
				{  $var.=$resulta->fields[0];  $i=1;}
				else
				{  $var.=','.$resulta->fields[0];  }
				$resulta->MoveNext();
		}
		$resulta->Close();

		$query="select b.hora, c.agenda_cita_asignada_id,
						c.paciente_id, c.tipo_id_paciente, null as nombre_paciente,
						p.sw_estado, c.observacion,c.agenda_cita_id, c.plan_id, h.cargo,
						i.ingreso, h.tarifario_id, p.numerodecuenta, p.numero_orden_id,
						j.tipo_afiliado_id, j.rango, j.semanas_cotizadas, j.autorizacion_int, j.autorizacion_ext, h.os_maestro_cargos_id, j.orden_servicio_id,
						b.agenda_cita_id, p.cargo_cups
						from agenda_citas as b
						left join agenda_citas_asignadas as c on (b.agenda_cita_id=c.agenda_cita_id and c.sw_atencion!='1')
						left join os_cruce_citas as g on (c.agenda_cita_asignada_id=g.agenda_cita_asignada_id)
						left join os_maestro as p on (g.numero_orden_id=p.numero_orden_id)
						left join os_ordenes_servicios as j on(p.orden_servicio_id=j.orden_servicio_id)
						left join os_maestro_cargos as h on(g.numero_orden_id=h.numero_orden_id)
						left join cuentas as i on(p.numerodecuenta=i.numerodecuenta)
						where b.agenda_turno_id in(".$var.") and b.sw_estado_cancelacion='0'
						order by b.hora;";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while (!$result->EOF)
		{
			if(!empty($result->fields[2]) or !empty($result->fields[5]))
			{
				$t=0;
				$s=0;
				while($t<sizeof($datos[0]))
				{
					if($datos[2][$t]==$result->fields[2] and $datos[3][$t]==$result->fields[3])
					{
						if($datos[8][$t]==$result->fields[7]-1)
						{
							$s=1;
							break;
						}
					}
					$t++;
				}
				if($s==0)
				{
					$datos[0][$i]=$result->fields[0];
					$datos[1][$i]=$result->fields[1];
					$datos[2][$i]=$result->fields[2];
					$datos[3][$i]=$result->fields[3];
					$datos[4][$i]=$result->fields[4];
					/*if($result->fields[5]==1)
					{
						if(!empty($result->fields[10]))
						{
							$datos[5][$i]=2;
						}
						else
						{
							$datos[5][$i]=1;
						}
					}
					else
					{
						$datos[5][$i]=0;
					}*/
					if($result->fields[5]==1)
					{
						$datos[5][$i]=1;
					}
					elseif($result->fields[5]==3)
					{
						$datos[5][$i]=3;
					}
					elseif($result->fields[5]==2)
					{
						$datos[5][$i]=2;
					}
					elseif($result->fields[5]==5)
					{
						$datos[5][$i]=0;
					}
					else
					{
					  $datos[5][$i]=8;
					}
					$datos[6][$i]=0;
					$datos[7][$i]=$result->fields[6];
					$datos[8][$i]=$result->fields[7];
					$datos[9][$i]=$result->fields[8];
					$datos[10][$i]=$result->fields[9];
					$datos[11][$i]=$result->fields[11];
					$datos[12][$i]=$result->fields[12];
					$datos[13][$i]=$result->fields[13];
					$datos[14][$i]=$result->fields[14];
					$datos[15][$i]=$result->fields[15];
					$datos[16][$i]=$result->fields[16];
					$datos[17][$i]=$result->fields[17];
					$datos[18][$i]=$result->fields[18];
					$datos[19][$i]=$result->fields[19];
					$datos[20][$i]=$result->fields[20];
					$datos[22][$i]=$result->fields[22];//CAMBIO DAR
					$i++;
				}
			}
			else
			{
				$datos[0][$i]=$result->fields[0];
				$datos[6][$i]=2;
				$datos[8][$i]=$result->fields[21];
				$i++;
			}
			$result->MoveNext();
		}
		if($i<>0)
		{
			return $datos;
		}
		else
		{
			return false;
		}
	}





/**
* Esta funcion retorna el listado de las citas con la posibilidad de asignar la cita sin importar si ya esta ocupada o no
*
* @access public
* @return array retorna el listado de citas
*/

	function ListadoCitasPrioritarias()
	{
		list($dbconn) = GetDBconn();
		$a=explode(",",$_SESSION['CumplirCita']['profesional']);
		$query="select b.hora, b.agenda_cita_id from agenda_turnos as a, agenda_citas as b where date(fecha_turno)=date(now()) and empresa_id='".$_SESSION['CumplirCita']['empresa']."' and tipo_consulta_id=".$_SESSION['CumplirCita']['cita']." and profesional_id='".$a[0]."' and tipo_id_profesional='".$a[1]."' and a.agenda_turno_id=b.agenda_turno_id  and b.sw_estado_cancelacion=0 order by b.hora;";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while (!$result->EOF)
		{
			$datos[0][$i]=$result->fields[0];
			$datos[1][$i]=$result->fields[1];
			$i++;
			$result->MoveNext();
		}
		if($i<>0)
		{
			return $datos;
		}
		else
		{
			return false;
		}
	}

// 	function BuscarDatosPaciente()
// 	{
// 		list($dbconn) = GetDBconn();
// 		$a=explode(",",$_REQUEST['paciente']);
// 		$query="select primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, sexo_id, telefono, direccion, b.plan_id, b.cargo_cita, b.tipo_cita from agenda_citas_asignadas_no_pacientes as a, agenda_citas_asignadas as b where a.agenda_cita_asignada_id=".$_REQUEST['citaasignada']." and a.paciente_id='".$a[1]."' and a.tipo_id_paciente='".$a[0]."' and b.agenda_cita_asignada_id=".$_REQUEST['citaasignada'].";";
// 		$result = $dbconn->Execute($query);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 			return false;
// 		}
// 		$datos[]=$result->fields[0];
// 		$datos[]=$result->fields[1];
// 		$datos[]=$result->fields[2];
// 		$datos[]=$result->fields[3];
// 		$datos[]=$result->fields[4];
// 		$datos[]=$result->fields[5];
// 		$datos[]=$result->fields[6];
// 		$datos[]=$result->fields[7];
// 		$datos[]=$result->fields[8];
// 		$datos[]=$result->fields[9];
// 		return $datos;
// 	}





/**
* Esta funcion busca a los pacientes para cancelar las citas
*
* @access public
* @return array retorna el listado de citas para cancelar
*/



	function BuscarPacientes()
	{
		$Buscar=$_REQUEST['Buscar'];
		if(empty($_REQUEST['Busqueda']))
		{
			$_REQUEST['Busqueda']=1;
		}
		if($_REQUEST['Busqueda']==1)
		{
			$TipoId=$_REQUEST['TipoDocumento'];
			$PacienteId=$_REQUEST['Documento'];
			if(!$PacienteId)
			{
				if(!$PacienteId)
				{
					$this->frmError["Documento"]=1;
				}
				$this->frmError["MensajeError"]="Debe digitar el Número del Documento.";
				if(!$this->BuscarPacienteCancelar($mensaje,$arr))
				{
					return false;
				}
				return true;
			}
				$Datos=$this->Buscar1($TipoId,$PacienteId);
				if($Datos)
				{
					$this->BuscarPacienteCancelar($mensaje,$Datos);
					return true;
				}
				else
				{
					$mensaje='La busqueda no arrojo resultados.';
					$this->BuscarPacienteCancelar($mensaje,$Datos);
					return true;
				}
		}
		if($_REQUEST['Busqueda']==2)
		{
			$nombres=$_REQUEST['nombres'];
			$apellidos=$_REQUEST['apellidos'];
			if(!$nombres && !$apellidos)
			{
				$this->frmError["MensajeError"]="Debe digitar el Nombre o el Apellido.";
				if(!$this->BuscarPacienteCancelar($mensaje,$arr))
				{
					return false;
				}
				return true;
			}
			$apellidos=strtoupper($apellidos);
			$nombres=strtoupper($nombres);
			if($apellidos!="" && $nombres=="")
			{
				$Datos=$this->Buscar2($apellidos,$caso='A');
				if($Datos)
				{
					$this->BuscarPacienteCancelar($mensaje,$Datos);
					return true;
				}
				else
				{
					$mensaje='La busqueda no arrojo resultados.';
					$this->BuscarPacienteCancelar($mensaje,$Datos);
					return true;
				}
			}
			if($apellidos=="" && $nombres!="")
			{
				$Datos=$this->Buscar2($nombres,$caso='N');
				if($Datos)
				{
					$this->BuscarPacienteCancelar($mensaje,$Datos);
					return true;
				}
				else
				{
					$mensaje='La busqueda no arrojo resultados.';
					$this->BuscarPacienteCancelar($mensaje,$Datos);
					return true;
				}
			}
			if($apellidos!="" && $nombres!="")
			{
				$listaApellidos = explode(" ", $apellidos);
				$listaNombre = explode(" ", $nombres);
				$var=$listaNombre[0].'%'.$listaNombre[1].'%'.$listaApellidos[0].'%'.$listaApellidos[1];
				$Datos=$this->Buscar2($var,$caso='T');
				if($Datos)
				{
					$this->BuscarPacienteCancelar($mensaje,$Datos);
					return true;
				}
				else
				{
					$mensaje='La busqueda no arrojo resultados.';
					$this->BuscarPacienteCancelar($mensaje,$Datos);
					return true;
				}
			}
		}
		return true;
	}

	/**
  * Busca los datos de las citas cuando se conoce el tipo_id_paciente y paciente_id.
	* @access public
	* @return array
	* @param string tipo de documento
	* @param string numero de documento
	*/
	function Buscar1($TipoId,$PacienteId)
	{
			list($dbconn) = GetDBconn();
			$query="select b.agenda_cita_id, a.paciente_id, a.tipo_id_paciente, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, b.agenda_cita_asignada_id, b.sw_atencion, d.fecha_turno || ' ' || c.hora as fecha_total, e.nombre_tercero, b.plan_id from pacientes as a join agenda_citas_asignadas as b on (a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id) join agenda_citas as c on (b.agenda_cita_id=c.agenda_cita_id) join agenda_turnos as d on (c.agenda_turno_id=d.agenda_turno_id) join terceros as e on(d.profesional_id=e.tercero_id and d.tipo_id_profesional=e.tipo_id_tercero) where a.paciente_id='".$PacienteId."' and a.tipo_id_paciente='".$TipoId."' and d.tipo_consulta_id=".$_SESSION['CancelarCita']['cita']." and date(d.fecha_turno)>=date(now());";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$vars=$this->LlenaMatriz($result);
			return $vars;
	}

	/**
  * Busca los datos de las citas cuando se conoce el nombre o apellido del paciente.
	* @access public
	* @return array
	* @param string la cadena de busqueda
	* @param char si busca por apellido, nombre o por los dos
	*/
	function Buscar2($var,$caso)
	{
		list($dbconn) = GetDBconn();
		if($caso=='A')
		{
			$query = "select b.agenda_cita_id, a.paciente_id, a.tipo_id_paciente, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, b.agenda_cita_asignada_id, b.sw_atencion, d.fecha_turno || ' ' || c.hora as fecha_total, e.nombre_tercero, b.plan_id from pacientes as a join agenda_citas_asignadas as b on (a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id) join agenda_citas as c on (b.agenda_cita_id=c.agenda_cita_id) join agenda_turnos as d on (c.agenda_turno_id=d.agenda_turno_id) join terceros as e on(d.profesional_id=e.tercero_id and d.tipo_id_profesional=e.tipo_id_tercero) where (a.primer_apellido || ' ' || a.segundo_apellido) like '%$var%' and d.tipo_consulta_id=".$_SESSION['CancelarCita']['cita']." and date(d.fecha_turno)>=date(now());";
			$result=$dbconn->Execute($query);
			$datos=$result->RecordCount();

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$vars=$this->LlenaMatriz($result);
			return $vars;
		}
		if($caso=='N')
		{
			$query = "select b.agenda_cita_id, a.paciente_id, a.tipo_id_paciente, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, b.agenda_cita_asignada_id, b.sw_atencion, d.fecha_turno || ' ' || c.hora as fecha_total, e.nombre_tercero, b.plan_id from pacientes as a join agenda_citas_asignadas as b on (a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id) join agenda_citas as c on (b.agenda_cita_id=c.agenda_cita_id) join agenda_turnos as d on (c.agenda_turno_id=d.agenda_turno_id) join terceros as e on(d.profesional_id=e.tercero_id and d.tipo_id_profesional=e.tipo_id_tercero) where (a.primer_nombre || ' ' || a.segundo_nombre) like '%$var%' and d.tipo_consulta_id=".$_SESSION['CancelarCita']['cita']." and date(d.fecha_turno)>=date(now());";
			$result=$dbconn->Execute($query);
			$datos=$result->RecordCount();
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$vars=$this->LlenaMatriz($result);
			return $vars;
		}
		if($caso=='T')
		{
			$query = "select b.agenda_cita_id, a.paciente_id, a.tipo_id_paciente, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, b.agenda_cita_asignada_id, b.sw_atencion, d.fecha_turno || ' ' || c.hora as fecha_total, e.nombre_tercero, b.plan_id from pacientes as a join agenda_citas_asignadas as b on (a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id) join agenda_citas as c on (b.agenda_cita_id=c.agenda_cita_id) join agenda_turnos as d on (c.agenda_turno_id=d.agenda_turno_id) join terceros as e on(d.profesional_id=e.tercero_id and d.tipo_id_profesional=e.tipo_id_tercero) where (a.primer_nombre || ' ' || a.segundo_nombre || ' ' || a.primer_apellido || ' ' || a.segundo_apellido) like '%$var%' and d.tipo_consulta_id=".$_SESSION['CancelarCita']['cita']." and date(d.fecha_turno)>=date(now());";
			$result=$dbconn->Execute($query);
			$datos=$result->RecordCount();
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$vars=$this->LlenaMatriz($result);
			return $vars;
		}
	}






/**
* funcion estandar para llenar la matriz que se retorna despues de la busqueda de los pacientes para cancelar las citas
* @access public
* @return array
* @param clase clase resultado de la consulta realizada.
*/

	function  LlenaMatriz($result)
	{
		$i=0;
		while (!$result->EOF)
		{
			$vars[$i][0]=$result->fields[0];
			$vars[$i][1]=$result->fields[1];
			$vars[$i][2]=$result->fields[2];
			$vars[$i][3]=$result->fields[3];
			$vars[$i][4]=$result->fields[4];
			$vars[$i][5]=$result->fields[5];
			$vars[$i][6]=$result->fields[6];
			$vars[$i][7]=$result->fields[7];
			$vars[$i][8]=$result->fields[8];
			$vars[$i][9]=$result->fields[9];
			$vars[$i][10]=$result->fields[10];
			$vars[$i][11]=$result->fields[11];
			$result->MoveNext();
			$i++;
		}
		$result->Close();
		return $vars;
	}




/**
* funcion que redirecciona el proceso hacia el borrado de la cita
* @access public
* @return boolean verdadero si el proceso se realizo correctamente y false si no
*/

	function BorrarCitaDatos()
	{
		if($this->DatosAdicionalesBorrarCita()==false)
		{
			return false;
		}
		return true;
	}





/**
* funcion que realiza la consulta de las diferentes justificaciones del paciente para cancelar la cita
* @access public
* @return array vector de datos con las justificaciones para la cancelacion de la cita
*/

	function BuscarJustificacion()
	{
		list($dbconn) = GetDBconn();
		$sql="select tipo_cancelacion_id, descripcion from tipos_cancelacion order by numero_orden;";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		while (!$result->EOF)
		{
			$datos[$result->fields[0]]=$result->GetRowAssoc(false);
			$result->MoveNext();
			$i++;
		}
		return $datos;
	}




/**
* funcion que borra la cita con la justificacion necesaria
* @access public
* @return boolean verdadero si el proceso se realizo con exito y falso si no
*/

	function BorrarCita()
	{
		if($_REQUEST['justificacion']==-1 or strlen($_REQUEST['Observacion'])>256)
		{
			if($_REQUEST['justificacion']==-1)
			{
				$this->frmError["justificacion"]=1;
			}
			if(strlen($_REQUEST['Observacion'])>256)
			{
				$this->frmError["Observacion"]=1;
			}
			$this->frmError["MensajeError"]="Faltan datos obligatorios o la cadena de observacion es muy larga.";
			if($this->DatosAdicionalesBorrarCita()==false)
			{
				return false;
			}
			return true;
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$sql="insert into agenda_citas_asignadas_cancelacion (agenda_cita_asignada_id, tipo_cancelacion_id, observacion) values (".$_SESSION['CancelarCita']['CITA']['cita_asignada_id'].", ".$_REQUEST['justificacion'].", '".$_REQUEST['Observacion']."');";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="update agenda_citas_asignadas set sw_atencion=1 where agenda_cita_asignada_id=".$_SESSION['CancelarCita']['CITA']['cita_asignada_id'].";";
		//echo $sql;
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		//esto lo quite porque causaba problema cambio dar agregue ,sw_estado_cancelacion='1'
		$sql="update agenda_citas set sw_estado=sw_estado-1
					where agenda_cita_id=".$_SESSION['CancelarCita']['CITA']['cita_id'].";";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		if($_REQUEST['liberacion']==1)
		{
			$sql="delete from os_cruce_citas where agenda_cita_asignada_id=".$_SESSION['CancelarCita']['CITA']['cita_asignada_id'].";";
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		else
		{
			$sql="select numero_orden_id from os_cruce_citas where agenda_cita_asignada_id=".$_SESSION['CancelarCita']['CITA']['cita_asignada_id'].";";
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			$sql="update os_maestro set sw_estado=9 where numero_orden_id=".$result->fields[0].";";
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		$dbconn->CommitTrans();
		if($this->AsignarNuevaCita()==false)
		{
			return false;
		}
		return true;
	}




	/**
	* Busca el nombre del pais
	* @access public
	* @return array
	* @param int codigo del pais
	*/
	function nombre_pais($Pais)
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT pais FROM tipo_pais WHERE tipo_pais_id='$Pais'";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{

					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
						return false;
					}
				}
				$result->Close();
		return $result->fields[0];
	}

	/**
	* Busca el nombre del departamento
	* @access public
	* @return array
	* @param int codigo del pais
  * @param int codigo del departamento
	*/
	function nombre_dpto($Pais,$Dpto)
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM tipo_dptos WHERE tipo_pais_id='$Pais' AND tipo_dpto_id='$Dpto'";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{

					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
						return false;
					}
				}
				$result->Close();
		return $result->fields[2];
	}

	/**
	* Busca el nombre de la ciudad o municipio
	* @access public
	* @return array
	* @param int codigo del pais
  * @param int codigo del departamento
	* @param int codigo del municipio
	*/
	function nombre_ciudad($Pais,$Dpto,$Mpio)
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM tipo_mpios WHERE tipo_pais_id='$Pais' AND tipo_dpto_id='$Dpto' AND tipo_mpio_id='$Mpio'";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{

					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
						return false;
					}
				}
				$result->Close();
		return $result->fields[3];
	}


	/**
	* Busca los tipos de zonas de residencia
	* @access public
	* @return array
	*/
	 function ZonasResidencia()
	 {
				list($dbconn) = GetDBconn();
				$query="SELECT zona_residencia,descripcion FROM zonas_residencia";
				$result=$dbconn->Execute($query);
				$i=0;
				while(!$result->EOF)
				{
						$zonas[$i]=$result->GetRowAssoc($ToUpper = false);
						$i++;
						$result->MoveNext();
		    }
      return $zonas;
	 }

	 /*function ocupacion()
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT ocupacion_id,ocupacion_descripcion FROM ocupaciones ORDER BY indice_de_orden";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{

					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'ocupaciones' esta vacia ";
						return false;
					}
						while (!$result->EOF) {
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
		return $vars;
	}*/




/**
* funcion que muestra la forma para solicitar el oficio del paciente
* @access public
* @return boolean verdadero si el proceso se realizo con exito y falso si no
*/

	function PeticionOficio()
	{
		if($this->MuetraOcupaciones()==false)
		{
			return false;
		}
		return true;
	}





/**
* funcion que realiza la actualizacion de la ocupacion
* @access public
* @return boolean verdadero si el proceso se realizo con exito y falso si no
*/

	function SeguirAtencion()
	{
		list($dbconn) = GetDBconn();
		if($_REQUEST['ocupacion_id']!='')
		{
			$sql="update pacientes set ocupacion_id='".$_REQUEST['ocupacion_id']."' where paciente_id='".$_REQUEST['pacienteid']."' and tipo_id_paciente='".$_REQUEST['tipoid']."';";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}

		$query = "SELECT hc_modulo FROM tipos_consultas_cargos
							WHERE tipo_consulta_id=".$_SESSION['Atencion']['cita']."
							AND cargo_cita='".$_REQUEST['cups_cita']."'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

		if(!empty($result->fields[0]))
		{  $_SESSION['Atencion']['hc_modulo']=$result->fields[0];   }

		$accion=ModuloHCGetURL('','',$_REQUEST['ingreso'],$_SESSION['Atencion']['hc_modulo'],$_SESSION['Atencion']['hc_modulo'],array('HC_DATOS_CONTROL'=>array('DEPARTAMENTO'=>$_SESSION['Atencion']['departamento'],'ESPECIALIDAD'=>$_SESSION['Atencion']['especialidad'])));
		$this->salida=$this->VolverListado($accion);
		return true;
	}





/**
* funcion que realiza la actualizacion de la ocupacion
* @access public
* @return string descripcion de la ocupacion
*/

	function BuscaOcupacion()
	{
		list($dbconn) = GetDBconn();
		$sql="select ocupacion_descripcion from pacientes as a, ocupaciones as b where paciente_id='".$_REQUEST['pacienteid']."' and tipo_id_paciente='".$_REQUEST['tipoid']."' and a.ocupacion_id=b.ocupacion_id;";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $result->fields[0];
	}

	/**
	* Busca los diferentes tipos de estado civil utilizados en la aplicacion
	* @access public
	* @return array
	*/
	function estadocivil()
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM tipo_estado_civil WHERE tipo_estado_civil_id!=0 ORDER BY indice_de_orden";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{

					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
						return false;
					}
						while (!$result->EOF) {
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
		return $vars;
	}



/**
* funcion que crea el vector de los diferentes tipos de identificacion
* @access public
* @return array datos de los tipos pacientes
*/

	function tipo_id_paciente()
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT tipo_id_paciente, descripcion FROM tipos_id_pacientes ORDER BY indice_de_orden";
			$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
						return false;
					}
						while (!$result->EOF) {
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
 		 return $vars;
	}



/**
* funcion que consulta la descripcion de un tipo de identificacion especifico
* @access public
* @return string descripcion del tipo de identificacion
*/


	function mostrar_id_paciente($TipoId)
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT descripcion FROM tipos_id_pacientes WHERE tipo_id_paciente='$TipoId'";
			$result = $dbconn->Execute($query);
			$datos=$result->RecordCount();

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
						return false;
					}
				}
				if($datos){
				$Tipo=$result->fields[0];
				$result->Close();
				}
		return $Tipo;
	}


	/**
	* Busca los diferentes tipos de sexo utilizados en la aplicacion
	* @access public
	* @return array
	*/
  function sexo()
  {
			list($dbconn) = GetDBconn();
			$result="";
			$query = "SELECT sexo_id,descripcion FROM tipo_sexo ORDER BY indice_de_orden";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
						return false;
					}
						while (!$result->EOF) {
							$vars[$result->fields[0]]=$result->fields[1];
							$result->MoveNext();
						}
				}
				$result->Close();
		return $vars;
	}

	/**
  * Busca los diferentes tipos de responsable (planes)
	* @access public
	* @return array
	*/
	function responsables()
	{
			list($dbconn) = GetDBconn();
			$query="SELECT plan_id,plan_descripcion,tercero_id,tipo_tercero_id FROM planes
							WHERE fecha_final >= now() and estado=1 and fecha_inicio <= now() order by plan_descripcion";
			$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else{
					if($result->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla 'planes' esta vacia ";
						return false;
					}
						$i=0;
						while (!$result->EOF)
						{
							$planes[$i]=$result->fields[0].'-'.$result->fields[1].'-'.$result->fields[2].'-'.$result->fields[3];
							$result->MoveNext();
							$i++;
						}
				}
			$result->Close();
		return $planes;
	}

	/**
	* Busca los diferentes tipos de responsable (planes)
	* @access public
	* @return string
	* @param int id del tercero
	* @param string tipo_id_tercero
	*/
	function BuscarNombreTercero($TerceroId,$TipoTercero)
	{
			list($dbconn) = GetDBconn();
				$query="SELECT nombre_tercero FROM terceros WHERE tercero_id='$TerceroId' AND tipo_id_tercero='$TipoTercero'";
				$result = $dbconn->Execute($query);

					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
			$result->Close();
			return $result->fields[0];
	}

	function NombrePaciente($Documento,$TipoDocumento)
	{
				list($dbconn) = GetDBconn();
				$query = "SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido
									FROM pacientes
									WHERE paciente_id='$TipoDocumento' AND tipo_id_paciente ='$Documento'";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				return $resulta->fields[0];
	}

//------------------FUNCIONES PARA LOS CAMPOS DE MOSTRAR BD--------------

		function PlantilaBD($plan)
		{
				list($dbconn) = GetDBconn();
				$sql="SELECT plantilla_bd_id FROM plantillas_planes WHERE plan_id=$plan";
				$result=$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				if(!$result->EOF)
				{  $var=$result->fields[0];  }

				$result->Close();
				return $var;
		}


		function CamposMostrarBD($campo,$plantilla)
		{
				list($dbconn) = GetDBconn();
			 	$sql="SELECT nombre_mostrar,sw_mostrar FROM plantillas_detalles
							WHERE descripcion_campo='$campo' AND plantilla_bd_id=$plantilla";
				$result=$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				$var=$result->GetRowAssoc($ToUpper = false);
				$result->Close();
				return $var;
		}	

}
?>
