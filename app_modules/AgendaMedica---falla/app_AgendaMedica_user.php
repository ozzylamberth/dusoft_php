<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_AgendaMedica_user.php,v 1.18 2010/03/16 18:41:57 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author 
  */ 
  /**
  * Clase : AgendaMedica
  * Clase para accesar los metodos privados de la clase de presentaci�n,
  * se compone de metodos publicos para insertar en la base de datos, 
  * actualizar y borrar de la base de datos y mostrar
  * la forma de inserci�n y la consulta de la agenda medica.
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.18 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author 
  */
  class app_AgendaMedica_user extends classModulo
  {
  	/**
  	* Esta funcion Inicializa las variable de la clase
  	*
  	* @return boolean Para identificar que se realizo.
  	*/
    function app_AgendaMedica_user(){}
    /**
    * Esta funcion es la que se llama de manera inicial, en donde se llama 
    * la funcion de menu que muestra las opciones del usuario para accesar 
    * a consulta externa
    *
    * @return boolean Para identificar que se realizo.
    */
  	function main()
  	{
  		$this->Menu();
  		UNSET($_SESSION['PROMOCION_Y_PREVENCION']);
  		UNSET($_SESSION[$tipo_id_paciente][$paciente_id]['Programa_id']);
  		SessionDelVar("Programa_id");
  		SessionDelVar("Inscripcion_id");
  		return true;
  	}
    /**
    * Esta funcion es la que instancia las clases necesarias para realizar 
    * la impresion de la informacion de la cita para la atencion.
    *
    * @return boolean Para identificar que se realizo.
    */
    function FuncionParaImprimir()
    {
      $var = $_REQUEST;
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
    * Esta funcion revisa las atenciones anteriores que ha tenido el paciente en 
    * la institucion
    *
    * @param string tipo de documento
    * @param string numero de documento
    *
    * @return int valor entre 0 y muchos cuando existe mas de una atencion.
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
    * @param string tipo de documento
    * @param string numero de documento
    *
    * @return int valor entre 0 y 1 para conocer si existe historia clinica del paciente.
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
    * Esta funcion busca la informacion importante que se necesita del 
    * plan para la impresion del baucher de la cita
    *
    * @param int identificacion del plan
    *
    * @return array informacion del plan para la impresion del baucher.
    */
    function BusquedaResponsable($plan)
    {
  		list($dbconn) = GetDBconn();
  		//cambio dar
  		$sql="select a.plan_descripcion, b.nombre_tercero, a.horas_cancelacion, a.telefono_cancelacion_cita
  					from planes as a, terceros as b
  					where a.plan_id=$plan and a.tercero_id=b.tercero_id
  					and a.tipo_tercero_id=b.tipo_id_tercero;";
  		//fin cambio dar

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
  		$dat['telefono_cancelacion_cita']=$result->fields[3];
  		//$dat['horasc']=$result->fields[3];
  		return $dat;
  	}
    /**
    *
    * @return mixed
    */
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
		function BuscarDatosConsultorio($csulta)
  	{
  			list($dbconn) = GetDBconn();
  			$sql="select tc.direccion, tc.telefono
				from tipos_consultorios as tc, tipos_consulta_consultorios as tcc, consultorios c
				where tcc.tipo_consulta_id=$csulta
				and c.consultorio_id =tcc.consultorio_id
				and tc.tipo_consultorio = c.tipo_consultorio";
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
    /**
    * Esta funcion busca el nombre del usuario para imprimirlo en el baucher de la cita
    *
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
    * Esta funcion busca los diferentes motivos de consulta que se generaron 
    * en una atencion especifica
    *
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
    * Funcion  declarada para la busque da de la fecha de bnnacimeinto 
    * para los paciente requerimiento de la clinica de rehabilitacion
    *
    * @return mixed
    */
  	function BusquedaFechaNacimientoPaciente($PacienteId)
  	{
  		list($dbconn) = GetDBconn();
  		$sql="select fecha_nacimiento from pacientes where paciente_id = '".$PacienteId."';";
  		$result = $dbconn->Execute($sql);
  		if ($dbconn->ErrorNo() != 0)
  	  	{
  			$this->error = "Error al Cargar el Modulo";
  			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        			return false;
      		}
  		$FechaNcaimiento = $result->fields[0];
  		return $FechaNcaimiento;
  	}
    /**
    * Esta funcion busca los diferentes tipos de atencion, entre ellos son 
    * atencion por soat y atencion por enfermedad profesional
    *
    * @param string tipo de identificacion
    * @param string identificacion del paciente
    *
    * @return array informacion del diagnostico.
    */
  	function BusquedaAtencionRiesgo($TipoId,$PacienteId)
  	{
  		list($dbconn) = GetDBconn();
  		$query = "select d.tipo_atencion_id, d.detalle, b.evolucion_id, date(b.fecha) from ingresos as a join hc_evoluciones as b on(a.paciente_id='".$PacienteId."' and a.tipo_id_paciente='".$TipoId."' and a.ingreso=b.ingreso and date(b.fecha)<=date(now())) join hc_atencion as c on(b.evolucion_id=c.evolucion_id) join hc_tipos_atencion as d on(c.tipo_atencion_id=d.tipo_atencion_id and (d.tipo_atencion_id='14' or d.tipo_atencion_id='02'));";
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
    * Esta funcion busca si es la primera atencion del paciente en la institucion 
    * @todo falta desarrollo de la misma.
    *
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
  		$this->request = $_REQUEST;
  		if(empty($_SESSION['CumplirCita']['paciente_id']))
  		{
  			$_SESSION['CumplirCita']['plan_id'] = $this->request['CUMPLIR']['plan'];
  			$_SESSION['CumplirCita']['paciente_id'] = $this->request['CUMPLIR']['paciente'];
  			$_SESSION['CumplirCita']['numero_orden_id'] = $this->request['CUMPLIR']['numero_orden_id'];
  			$_SESSION['CumplirCita']['tipo_id_paciente'] = $this->request['CUMPLIR']['tipo_id_paciente'];
  		}
      
  		if($_SESSION['CumplirCita']['paciente_id']!= $this->request['CUMPLIR']['paciente'])
  		{
  			$_SESSION['CumplirCita']['plan_id'] = $this->request['CUMPLIR']['plan'];
  		  $_SESSION['CumplirCita']['paciente_id'] = $this->request['CUMPLIR']['paciente'];
  			$_SESSION['CumplirCita']['numero_orden_id'] = $this->request['CUMPLIR']['numero_orden_id'];
  			$_SESSION['CumplirCita']['tipo_id_paciente'] = $this->request['CUMPLIR']['tipo_id_paciente'];
  		}
			
			$this->Documento = $_SESSION['CumplirCita']['paciente_id'];
			$this->TipoDocumento = $_SESSION['CumplirCita']['tipo_id_paciente'];
			
			$datos = array();
			$datos['tipo_id_paciente'] = $_SESSION['CumplirCita']['tipo_id_paciente'];
			if($_SESSION['CumplirCita']['paciente_id'])
				$datos['paciente_id'] = $_SESSION['CumplirCita']['paciente_id'];
			$datos['plan_id'] = $_SESSION['CumplirCita']['plan_id'];
			
			$_REQUEST['tipo_id_paciente'] = $_SESSION['CumplirCita']['tipo_id_paciente'];
			$_REQUEST['paciente_id'] = $_SESSION['CumplirCita']['paciente_id'];
			$_REQUEST['plan_id'] = $_SESSION['CumplirCita']['plan_id'];
						
  		$DATOS['CUMPLIR'] = array('tipo_id_paciente'=>$_SESSION['CumplirCita']['tipo_id_paciente'],'paciente'=>$_SESSION['CumplirCita']['paciente_id'],'plan'=>$_SESSION['CumplirCita']['plan_id'],'numero_orden_id'=>$_SESSION['CumplirCita']['numero_orden_id']);
			$this->action['cancelar'] = ModuloGetURL('app','AgendaMedica','user','RetornoPaciente',$datos);
			$datos['paso'] = 1;
      $this->action['volver'] = ModuloGetURL('app','AgendaMedica','user','RetornoPaciente',$datos);
			
      $pct = $this->ReturnModuloExterno('app','DatosPaciente','user');
			$pct->datos_afiliado = false;
      
			$pct->SetActionVolver($this->action['volver']);
			$pct->FormaDatosPaciente($this->action);
			
			$this->SetJavaScripts("Ocupaciones");
			$this->salida = $pct->salida;
			return true;
    }
    /**
    * Esta funcion es la que recibe el llamado de la clase paciente y 
    * la que realiza el cambio de estado de la orden del paciente.
    *
    * @return boolean true si se realizo con exito y false si no fue asi
    */
  	function RetornoPaciente()
  	{
  		if($_REQUEST['paso'])
  		{
  			$sql  = "UPDATE  os_maestro "; 
        $sql .= "SET     sw_estado = '3' "; 
        $sql .= "WHERE   numero_orden_id = ".$_SESSION['CumplirCita']['numero_orden_id']."; ";
  			
        if(!$rst = $this->ConexionBaseDatos($sql, __LINE__))
          return false;
      }
  		unset($_SESSION['PACIENTES']);
  		unset($_SESSION['CumplirCita']['rango']);
  		unset($_SESSION['CumplirCita']['tipo_afiliado_id']);
  		unset($_SESSION['CumplirCita']['semanas']);
  		unset($_SESSION['CumplirCita']['numerodecuenta']);
  		unset($_SESSION['CumplirCita']['numero_orden_id']);
  		unset($_SESSION['CumplirCita']['paciente_id']);
  		unset($_SESSION['CumplirCita']['tipo_id_paciente']);
  		unset($_SESSION['CumplirCita']['plan_id']);
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
    /**
    * Esta funcion busca las diferentes cuentas por pagar que pueda tener el 
    * paciente a la hora de ser atendido
    *
    * @param string tipo de identificacion
    * @param string identificacion del paciente
    *
    * @return array informacion de cuentas por cobrar.
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
    * Esta funcion busca el listado de derechos que tiene un usuario para asignar 
    * y cancelar citas medicas
    *
    * @param string url que identifica cual funcion es la que se accesa despues
    *
    * @return array informacion de los derechos que tiene el usuario.
    */
  	function TipoConsulta($url)
  	{
  		GLOBAL $ADODB_FETCH_MODE;
  		list($dbconn) = GetDBconn();
      //$dbconn->debug = true;
      
  		if(!empty($_SESSION['SYSTEM_USUARIO_ID']))
  		{
  			$sql = "select  b.tipo_consulta_id, 
                        e.descripcion as descripcion3, 
                        b.departamento, 
                        c.descripcion as descripcion2, 
                        d.empresa_id, 
                        d.razon_social as descripcion1, 
                        b.sw_anestesiologia, 
                        b.cargo_cups, 
                        b.sw_busqueda_citas,
                        b.sw_tiempocita,
                        a.sw_vertodosplanes,
                        b.sw_anestesiologia,
                        c.sw_cargos_adicionales                        
                from    userpermisos_tipos_consulta as a, 
                        tipos_consulta as b, 
                        departamentos as c, 
                        empresas as d, 
                        tipos_servicios_ambulatorios as e 
                where   a.tipo_consulta_id=b.tipo_consulta_id 
                and     a.usuario_id=".$_SESSION['SYSTEM_USUARIO_ID']." 
                and     b.departamento=c.departamento
                and     c.empresa_id=d.empresa_id 
                and     a.tipo_consulta_id=e.tipo_servicio_amb_id 
                order by empresa_id,descripcion3,departamento,tipo_consulta_id;";
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
  			$i = 0;
  			if ($dbconn->ErrorNo() != 0)
  			{
  				$this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
  			}
        
 				while ($data = $result->FetchRow()) 
        {
  				$prueba6[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
  				$i=1;
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
  			$this->salida .= gui_theme_menu_acceso($nombre,$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][0],$_SESSION['SEGURIDAD'][$url[4]]['Arreglo'][2],$url,$accion);
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
    *
    */
  	function CitasEnFechaHora($fecha,$hora,$idcita)
  	{
  	  if(empty($fecha)){$fecha=date("Y-m-d");}
  		list($dbconn) = GetDBconn();
  		$sql="select d.descripcion, e.nombre_tercero, c.fecha_turno || ' ' || b.hora as fecha_completa
  		from agenda_citas_asignadas as a, agenda_citas as b, agenda_turnos as c, tipos_consulta as d, terceros as e
  		where c.fecha_turno='$fecha' and a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and a.sw_atencion!=1 and b.sw_estado='0' and c.sw_estado_cancelacion=0 and b.hora='$hora' and a.agenda_cita_asignada_id!=$idcita and c.tipo_consulta_id=d.tipo_consulta_id and c.tipo_id_profesional=e.tipo_id_tercero and c.profesional_id=tercero_id and a.paciente_id='".$_SESSION['AsignacionCitas']['Documento']."' and a.tipo_id_paciente='".$_SESSION['AsignacionCitas']['TipoDocumento']."';";
  		$result = $dbconn->Execute($sql);
  		if ($dbconn->ErrorNo() != 0){
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
    * Esta funcion busca el listado de derechos que tiene un usuario para cumplir 
    * citas medicas
    *
    * @param string url que identifica cual funcion es la que se accesa despues
    *
    * @return array informacion de los derechos que tiene el usuario.
    */
  	function TipoConsultaCumplimiento($url)
  	{
  		GLOBAL $ADODB_FETCH_MODE;
  		list($dbconn) = GetDBconn();
  		if(!empty($_SESSION['SYSTEM_USUARIO_ID']))
  		{
  			$sql = "select  b.tipo_consulta_id, 
                        e.descripcion as descripcion3, 
                        b.departamento, 
                        c.descripcion as  descripcion2, 
                        d.empresa_id, 
                        d.razon_social as descripcion1, 
                        b.cargo_cups, 
                        b.sw_tiempocita, ".
					//	f.sw_vertodosplanes,
                        "b.sw_anestesiologia,
                        c.sw_cargos_adicionales
                from    userpermisos_consultas_cumplimientos as a, 
                        tipos_consulta as b, 
                        departamentos as c, 
                        empresas as d, 
                        tipos_servicios_ambulatorios as e" . // ,
                 //       userpermisos_tipos_consulta f						
                " where   a.tipo_consulta_id=b.tipo_consulta_id 
                and     a.usuario_id=".UserGetUID()." 
                and     b.departamento=c.departamento 
                and     c.empresa_id=d.empresa_id 
                and     b.tipo_consulta_id=e.tipo_servicio_amb_id " .
              //  and     f.usuario_id = a.usuario_id
              //  and     f.tipo_consulta_id=a.tipo_consulta_id				
               " order by empresa_id,departamento,tipo_consulta_id ";
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
  			while ($data = $result->FetchRow()) 
        {
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
    * @param string tipo de identificacion paciente
    * @param string identificacion paciente
    *
    * @return array datos de las citas hacia delante que tenga el paciente.
    */
  	function CitasAdelante($tipoid,$paciente)
  	{
  		list($dbconn) = GetDBconn();
  		$sql="select c.fecha_turno || ' ' || b.hora as fecha, e.nombre_tercero, a.agenda_cita_asignada_id
  		from agenda_citas_asignadas as a left join os_cruce_citas as g on (a.agenda_cita_asignada_id=g.agenda_cita_asignada_id)
  		left join os_maestro as f on (g.numero_orden_id=f.numero_orden_id)
  		left join os_ordenes_servicios as j on(f.orden_servicio_id=j.orden_servicio_id)
  		left join os_maestro_cargos as h on(g.numero_orden_id=h.numero_orden_id)
  		left join cuentas as i on(f.numerodecuenta=i.numerodecuenta), agenda_citas as b, agenda_turnos as c, profesionales as d, terceros as e
  		where a.paciente_id='".$paciente."' and a.tipo_id_paciente='".$tipoid."' and a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and
  		c.profesional_id=d.tercero_id and c.tipo_id_profesional=d.tipo_id_tercero and c.profesional_id=e.tercero_id and c.tipo_id_profesional=e.tipo_id_tercero and date(c.fecha_turno)>=date(now()) and a.sw_atencion!=1 and b.sw_estado='0' order by (c.fecha_turno || ' ' || b.hora);";
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
  		if(!empty($plan)){
  			$sql="select actividad_incumplimientos from planes where plan_id=$plan;";
  			$result = $dbconn->Execute($sql);
  			if($dbconn->ErrorNo() != 0){
  				$this->error = "Error al Cargar el Modulo";
  				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  				return false;
  			}
  			$dato=$result->fields[0];
  		}else{
  			$dato=0;
  		}
  		$sql="SELECT c.fecha_turno || ' ' || b.hora as fecha, f.nombre_tercero, a.agenda_cita_asignada_id
            FROM  agenda_citas_asignadas as a, 
                  agenda_citas as b, 
                  agenda_turnos as c, 
                  profesionales as e,
                  terceros as f, 
                  os_cruce_citas as h, 
                  os_maestro as i
            WHERE h.numero_orden_id=i.numero_orden_id 
            AND a.agenda_cita_asignada_id=h.agenda_cita_asignada_id 
            AND a.agenda_cita_id=b.agenda_cita_id 
            and	b.agenda_turno_id=c.agenda_turno_id 
            and date(c.fecha_turno) < date(now()) 
            and date(c.fecha_turno)>(date(now())-".$dato.") 
            and a.paciente_id='$paciente' 
            and	tipo_id_paciente='$tipoid' 
            and c.profesional_id=e.tercero_id 
            and c.tipo_id_profesional=e.tipo_id_tercero 
            and i.sw_estado!=3 
            and a.sw_atencion!=3 
            and	e.tercero_id=f.tercero_id 
            and e.tipo_id_tercero=f.tipo_id_tercero 
            AND a.agenda_cita_asignada_id NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion);";
  		//echo $sql1="select c.fecha_turno || ' ' || b.hora as fecha, e.nombre, f.agenda_cita_asignada_id from agenda_citas_asignadas as a, agenda_citas as b, agenda_turnos as c, profesionales as e where a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and date(c.fecha_turno)&lt;date(now()) and date(c.fecha_turno)>(date(now())-".$result->fields[0].") and a.paciente_id='".$paciente."' and tipo_id_paciente='".$tipoid."' and c.profesional_id=e.tercero_id and c.tipo_id_profesional=e.tipo_id_tercero and a.sw_atencion!=3 and a.sw_estado!=1;";
  		$result = $dbconn->Execute($sql);
  		if($dbconn->ErrorNo() != 0){
  			$this->error = "Error al Cargar el Modulo";
  			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  			return false;
  		}else{
  			$i=0;
  			while(!$result->EOF){
  				$incumplida[0][$i]=$result->fields[0];
  				$incumplida[1][$i]=$result->fields[1];
  				$i++;
  				$result->MoveNext();
  			}
  		}
  		return $incumplida;
  	}
    /**
    * Esta funcion busca las citas que tenga el usuario hacia delante
    *
    * @access public
    * @return array datos de las citas hacia delante que tenga el paciente.
    * @param string tipo de identificacion paciente
    * @param string identificacion paciente
    */
  	function CitasPorCumplirPaciente($tipoid,$paciente,$plan)
  	{
  		list($dbconn) = GetDBconn();
  		$sql = "SELECT  c.fecha_turno || ' ' || b.hora as fecha, 
                      f.nombre_tercero, 
                      a.agenda_cita_asignada_id,
                      EP.descripcion AS especialidad_descripcion
              FROM    agenda_citas_asignadas as a, 
                      agenda_citas as b, 
                      agenda_turnos as c, 
                      profesionales as e,
                      profesionales_especialidades PE,
                      especialidades EP,
                      terceros as f, 
                      os_cruce_citas as h, 
                      os_maestro as i
              WHERE   h.numero_orden_id=i.numero_orden_id 
              AND     a.agenda_cita_asignada_id=h.agenda_cita_asignada_id 
              AND     a.agenda_cita_id=b.agenda_cita_id 
              and  		b.agenda_turno_id=c.agenda_turno_id 
              and     date(c.fecha_turno) >= date(now()) 
              and     a.paciente_id='$paciente' 
              and  		tipo_id_paciente='$tipoid' 
              and     c.profesional_id=e.tercero_id 
              and     c.tipo_id_profesional=e.tipo_id_tercero 
              and     i.sw_estado!=3 and a.sw_atencion!=3 
              and  		e.tercero_id=f.tercero_id 
              and     e.tipo_id_tercero=f.tipo_id_tercero 
              AND     PE.tipo_id_tercero = e.tipo_id_tercero
              AND     PE.tercero_id = e.tercero_id
              AND     PE.especialidad = Ep.especialidad
              AND     a.agenda_cita_asignada_id NOT IN 
                      (
                        SELECT  agenda_cita_asignada_id 
                        FROM    agenda_citas_asignadas_cancelacion
                      );";
  		//echo $sql1="select c.fecha_turno || ' ' || b.hora as fecha, e.nombre, f.agenda_cita_asignada_id from agenda_citas_asignadas as a, agenda_citas as b, agenda_turnos as c, profesionales as e where a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and date(c.fecha_turno)&lt;date(now()) and date(c.fecha_turno)>(date(now())-".$result->fields[0].") and a.paciente_id='".$paciente."' and tipo_id_paciente='".$tipoid."' and c.profesional_id=e.tercero_id and c.tipo_id_profesional=e.tipo_id_tercero and a.sw_atencion!=3 and a.sw_estado!=1;";
  		$result = $dbconn->Execute($sql);
  		if($dbconn->ErrorNo() != 0){
  			$this->error = "Error al Cargar el Modulo";
  			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  			return false;
  		}else{
  			$i=0;
  			while(!$result->EOF){
  				$porcumplir[0][$i]=$result->fields[0];
  				$porcumplir[1][$i]=$result->fields[1];
  				$porcumplir[2][$i]=$result->fields[3];
  				$i++;
  				$result->MoveNext();
  			}
  		}
  		return $porcumplir;
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
  		c, profesionales as d, terceros as e
  		where
  		a.paciente_id='".$_REQUEST['Documento']."' and
  		a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."' and
  		a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and
  		c.profesional_id=d.tercero_id and c.tipo_id_profesional=d.tipo_id_tercero and
  		c.profesional_id=e.tercero_id and c.tipo_id_profesional=e.tipo_id_tercero and
  		date(c.fecha_turno)>=date(now()) and
  		c.tipo_consulta_id=".$_SESSION['CumplirCita']['cita']." and a.sw_atencion!=1 and
  		b.sw_estado='0' order by (c.fecha_turno || ' ' || b.hora);";
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
  				
  		$sql="SELECT a.agenda_cita_asignada_id, a.fecha_turno|| ' ' ||a.hora as fechacom,
  								 a.nombre,a.fecha_turno,f.sw_estado,a.agenda_cita_id,i.ingreso, 
  								 j.plan_id, h.cargo, h.tarifario_id,f.numerodecuenta, j.tipo_afiliado_id, 
  								 j.rango, j.semanas_cotizadas,j.autorizacion_int, j.autorizacion_ext, 
  								 f.numero_orden_id, f.sw_estado,h.os_maestro_cargos_id,a.descripcion 
  		
  		FROM 
  		
  		(SELECT a.agenda_cita_asignada_id, c.fecha_turno,b.hora,e.nombre_tercero as nombre,
  		        a.agenda_cita_id,k.descripcion 
  			FROM agenda_citas_asignadas as a,agenda_citas as b,
  					agenda_turnos as c,profesionales as d,
  					terceros as e,tipos_consulta as k
  			WHERE a.paciente_id='".$_REQUEST['Documento']."' and
  						a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."' and
  						a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and
  						c.profesional_id=d.tercero_id and c.tipo_id_profesional=d.tipo_id_tercero and
  						c.profesional_id=e.tercero_id and c.tipo_id_profesional=e.tipo_id_tercero and
  						date(c.fecha_turno)>=date(now()) and
  						c.tipo_consulta_id!=".$_SESSION['CumplirCita']['cita']." and c.tipo_consulta_id=k.tipo_consulta_id and a.sw_atencion!=1
  						and (b.sw_estado='0' OR b.sw_estado='1') 		
  		) as a
  		left join os_cruce_citas as g on (a.agenda_cita_asignada_id=g.agenda_cita_asignada_id)
  		left join os_maestro as f on (g.numero_orden_id=f.numero_orden_id)
  		left join os_ordenes_servicios as j on(f.orden_servicio_id=j.orden_servicio_id)
  		left join os_maestro_cargos as h on(g.numero_orden_id=h.numero_orden_id)
  		left join cuentas as i on(f.numerodecuenta=i.numerodecuenta)	
  		order by (a.fecha_turno || ' ' || a.hora);";
  		
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
    *
    */
  	function GetFiltroNombres($N,$A)
    {
  		$Filtro =array();
  		
  		if(!empty($N)){
  			$Nsimilares = substr_count($N,"%");
  			//TODO CON LIKE PORQUE EN LA BASE DE DATOS LOS NOMBRES SE ESTAN GUADANDO CON ESPACIOS.
  			$Nsimilares = 1;
  			$N = str_replace ("%", " ", $N);
  	
  			$N = explode(" ",preg_replace("/\s{2,}/"," ",trim($N)));
  		
  			if(count($N)>1){
  				if($Nsimilares){
  						$Filtro[] = "(k.primer_nombre LIKE '%$N[0]%' AND k.segundo_nombre LIKE '%$N[1]%')";
  				}else{
  					$Filtro[] = "(k.primer_nombre = '$N[0]' AND k.segundo_nombre = '$N[1]')";
  				}
  			}else{
  				if(!empty($N[0])){
  					if($Nsimilares){
  						$Filtro[] = "(k.primer_nombre LIKE '%$N[0]%' OR k.segundo_nombre LIKE '%$N[0]%')";
  					}else{
  						$Filtro[] = "(k.primer_nombre = '$N[0]' OR k.segundo_nombre = '$N[0]')";
  					}
  				}
  			}
  		}
  		
  		if(!empty($A)){		
  			$Asimilares = substr_count($A,"%");
  			//TODO CON LIKE PORQUE EN LA BASE DE DATOS LOS NOMBRES SE ESTAN GUADANDO CON ESPACIOS.
  			$Asimilares = 1;
  			$A = str_replace ("%", " ", $A);	
  			$A = explode(" ",preg_replace("/\s{2,}/"," ",trim($A)));
  			if(count($A)>1){
  				if($Asimilares){
  					$Filtro[] = "(k.primer_apellido LIKE '%$A[0]%' AND k.segundo_apellido LIKE '%$A[1]%')";
  				}else{
  					$Filtro[] = "(k.primer_apellido = '$A[0]' AND k.segundo_apellido = '$A[1]')";
  				}
  			}else{
  				if(!empty($A[0])){
  					if($Asimilares){
  						$Filtro[] = "(k.primer_apellido LIKE '%$A[0]%')";
  					}else{
  						$Filtro[] = "(k.primer_apellido = '$A[0]')";
  					}
  				}
  			}
  		}
  		return $Filtro;
    }
    /**
    * Esta funcion busca las diferentes citas que tenga para el dia actual con el 
    * nombre del paciente
    *
    * @return array listado de las citas que tiene un paciente para el dia actual.
    */
  	function CitasPacienteAtenderNombre()
  	{
  		list($dbconn) = GetDBconn();
		$TIPO_ID = $_REQUEST['TipoDocumento'];
  		$ID = trim($_REQUEST['Documento']);
  		$N = strtoupper(trim($_REQUEST['nombres']));
  		$A = strtoupper(trim($_REQUEST['apellidos']));
  		if(empty($ID) && empty($N) && empty($A)){			
  			$this->frmError["MensajeError"]='DIGITE UN VALOR PARA LA BUSQUEDA .. (Documento, Nombres, Apellidos)';
  			$this->BuscarCita();
  			return true;
  		}
  		$FiltrarNombres = TRUE;
  		$Filtro = array();
  		$FiltrosPaciente = NULL;
  		if(!empty($ID)){
  			$IDsimilares = substr_count($ID,"%");
  			$ID = str_replace ("%", "", $ID);	
  			if(!$IDsimilares){
  				$Filtro[] = "k.paciente_id = '$ID'";
  				$FiltrarNombres = FALSE;
  			}else{
  				$Filtro[] = "k.paciente_id LIKE '$ID%'";
  			}	
  			if($TIPO_ID != -1){
  					$Filtro[] = "k.tipo_id_paciente = '$TIPO_ID'";
  			}
  		}
  		foreach($Filtro as $k=>$v){
  			$Filtros .= "AND $v\n";
  		}		
  		if($FiltrarNombres){			
  			$FN = $this->GetFiltroNombres($N,$A);
  			foreach($FN as $k=>$v){
  					$Filtros .= "AND $v\n";
  			}
  		}		
  		/*if($_REQUEST['nombres'])
  		{
  			$nombre=" AND k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido like '%".strtoupper($_REQUEST['nombres'])."%' ";
  		}
  		if($_REQUEST['Documento'] && $_REQUEST['TipoDocumento']){
        $doc=" AND a.paciente_id='".$_REQUEST['Documento']."' and a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."'";
  		}*/		
  		$sql="SELECT a.agenda_cita_asignada_id,
  								a.fecha_turno || ' ' || a.hora as fechacom,
  								a.nombre,
                  a.fecha_turno,
  								f.sw_estado,
                  a.agenda_cita_id,								
  								i.ingreso,
                  j.plan_id, 
                  h.cargo, 
  								h.tarifario_id, 
                  f.numerodecuenta, 
  								j.tipo_afiliado_id, 
                  j.rango, 
  								j.semanas_cotizadas, 
                  j.autorizacion_int, 
  								j.autorizacion_ext, 
                  f.numero_orden_id, 
  								f.sw_estado,
                  h.os_maestro_cargos_id, 
  								j.orden_servicio_id,
                  a.paciente_id, 
  		 						a.tipo_id_paciente,
                  f.cargo_cups,
  								a.nombre_pac
  								 
  		FROM 	
  		(
  			SELECT a.agenda_cita_asignada_id, c.fecha_turno, b.hora,
  						 e.nombre_tercero as nombre, a.agenda_cita_id,
  						 a.paciente_id, a.tipo_id_paciente,
  						 k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombre_pac 
  			
  			FROM 
  				agenda_citas_asignadas as a, 
  				agenda_citas as b, 
  				agenda_turnos as c, 
  				profesionales as d, 
  				terceros as e, 
  				pacientes as k 
  			
  			WHERE a.paciente_id=k.paciente_id 
  				AND a.tipo_id_paciente=k.tipo_id_paciente 
  				AND a.agenda_cita_id=b.agenda_cita_id 
  				AND b.agenda_turno_id=c.agenda_turno_id 
  				AND c.profesional_id=d.tercero_id 
  				AND c.tipo_id_profesional=d.tipo_id_tercero 
  				AND c.profesional_id=e.tercero_id 
  				AND c.tipo_id_profesional=e.tipo_id_tercero 
  				AND date(c.fecha_turno)>=date(now()) 
  				AND c.tipo_consulta_id='".$_SESSION['CumplirCita']['cita']."' 
  				AND a.sw_atencion!=1 
  				AND (b.sw_estado='0' OR b.sw_estado='1') 
  				$Filtros 
  		) AS a	
  		
  		
  		
  		LEFT JOIN os_cruce_citas as g ON (a.agenda_cita_asignada_id=g.agenda_cita_asignada_id) 
  		LEFT JOIN os_maestro as f ON (g.numero_orden_id=f.numero_orden_id) 
  		LEFT JOIN os_ordenes_servicios as j ON(f.orden_servicio_id=j.orden_servicio_id) 
  		LEFT JOIN os_maestro_cargos as h ON(g.numero_orden_id=h.numero_orden_id) 
  		LEFT JOIN cuentas as i ON(f.numerodecuenta=i.numerodecuenta)
  		WHERE f.sw_estado IS NOT NULL
  		ORDER BY (a.fecha_turno || ' ' || a.hora)";

  		//$dbconn->debug = true;
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
				
					/*if($result->fields[4] && $i > 0 && $Cita[19][$i-1] == $result->fields[20] && $Cita[20][$i-1] == $result->fields[21])
 					{
 						$i--;
 					}*/
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
  		where a.paciente_id=k.paciente_id  and a.tipo_id_paciente=k.tipo_id_paciente and a.agenda_cita_id=b.agenda_cita_id and b.agenda_turno_id=c.agenda_turno_id and c.profesional_id=d.tercero_id and c.tipo_id_profesional=d.tipo_id_tercero and c.profesional_id=e.tercero_id and c.tipo_id_profesional=e.tipo_id_tercero and date(c.fecha_turno)>=date(now()) and c.tipo_consulta_id!=".$_SESSION['CumplirCita']['cita']." and a.sw_atencion!=1 and b.sw_estado='0' $nombre order by (c.fecha_turno || ' ' || b.hora);";
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
  			$sql= "select distinct(d.tipo_consulta_id), 
  										g.descripcion as descripcion3, 
  										e.departamento, 
  										e.descripcion as descripcion2, 
  										c.empresa_id, 
  										c.razon_social as descripcion1, 
  										b.profesional_id, 
  										b.tipo_id_profesional, 
  										h.nombre_tercero as nombre, 
  										date(now()) as DiaEspe, 
  										d.hc_modulo, 
  										d.bodega_unico, 
  										d.especialidad 
  							from 	profesionales_usuarios as a, 
  										agenda_turnos as b, 
  										empresas as c, 
  										tipos_consulta as d, 
  										departamentos as e, 
  										profesionales as f, 
  										tipos_servicios_ambulatorios as g, 
  										terceros as h 
  						where a.usuario_id=".$_SESSION['SYSTEM_USUARIO_ID']." and a.tipo_tercero_id=b.tipo_id_profesional and a.tercero_id=b.profesional_id and a.tipo_tercero_id=h.tipo_id_tercero and a.tercero_id=h.tercero_id and b.empresa_id=c.empresa_id and b.tipo_consulta_id=d.tipo_consulta_id and d.departamento=e.departamento and b.profesional_id=f.tercero_id and b.tipo_id_profesional=f.tipo_id_tercero and b.fecha_turno>=date(now()) and d.tipo_consulta_id=g.tipo_servicio_amb_id order by d.tipo_consulta_id;"; 
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
  		$a = explode(",",$_SESSION['Atencion']['profesional']);
  		list($dbconn) = GetDBconn();
      //$dbconn->debug=true;
  		$query = "SELECT a.agenda_turno_id FROM agenda_turnos a
  							WHERE date(a.fecha_turno)=date('".$_SESSION['Atencion']['DiaEspe']."')
  							and a.tipo_consulta_id=".$_SESSION['Atencion']['cita']."
  							and a.profesional_id='".$a[1]."'
  							and a.tipo_id_profesional='".$a[0]."'
  							and a.empresa_id='".$_SESSION['Atencion']['empresa']."'";
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

  		$sql = "SELECT  b.hora, 
                      c.agenda_cita_id, 
                      c.tipo_id_paciente,
                  		c.paciente_id,
                      null as nombre_completo, 
                      f.paciente_id || f.tipo_id_paciente as mirar,
                  		j.ingreso, 
                      i.evolucion_id, 
                      i.estado, 
                      k.sw_estado, 
                      c.observacion,
                      k.cargo_cups,
                  		c.agenda_cita_id_padre,
                      carcit.descripcion as cargo_cita,
                      c.sw_anestesiologo
          		FROM    agenda_citas as b
                  		LEFT JOIN agenda_citas_asignadas as c 
                      ON (b.agenda_cita_id=c.agenda_cita_id and c.sw_atencion!=1)
                  		LEFT JOIN cargos_citas as carcit 
                      ON(c.cargo_cita=carcit.cargo_cita)
                  		LEFT JOIN historias_clinicas as f 
                      ON (c.tipo_id_paciente=f.tipo_id_paciente and c.paciente_id=f.paciente_id)
                  		LEFT JOIN os_cruce_citas as h 
                      ON(c.agenda_cita_asignada_id=h.agenda_cita_asignada_id)
                  		LEFT JOIN os_maestro as k 
                      ON(h.numero_orden_id=k.numero_orden_id)
                  		LEFT JOIN cuentas as j 
                      ON(k.numerodecuenta=j.numerodecuenta)
                  		LEFT JOIN hc_evoluciones as i 
                      ON (j.ingreso=i.ingreso AND 
                          i.fecha::date = date('".$_SESSION['Atencion']['DiaEspe']."'))
          		WHERE   b.agenda_turno_id in(".$var.") 
              AND     (b.sw_estado='0' OR b.sw_estado='1' OR b.sw_estado='2')
          		ORDER BY b.hora;";
  		$result = $dbconn->Execute($sql);
  		$i=0;
  		if ($dbconn->ErrorNo() != 0){
  			$this->error = "Error al Cargar el Modulo";
  			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  			return false;
  		}else{
  			while(!$result->EOF){
  				if(!empty($result->fields[2]) and !empty($result->fields[3])){
  					$t=0;
  					$s=0;
  					while($t<sizeof($turnosdia[2])){
  						if($turnosdia[2][$t]==$result->fields[2] and $turnosdia[3][$t]==$result->fields[3]){
  							$s=1;
  							break;
  						}
  						$t++;
  					}
  					if($s==0){
  						$turnosdia[0][$i]=$result->fields[0];
  						$turnosdia[1][$i]=$result->fields[1];
  						$turnosdia[2][$i]=$result->fields[2];
  						$turnosdia[3][$i]=$result->fields[3];
  						$turnosdia[4][$i]=$result->fields[4];
  						if(!empty($result->fields[5])){
  							$turnosdia[5][$i]=1;
  						}else{
  							$turnosdia[5][$i]=0;
  						}
  						$turnosdia[6][$i]=$result->fields[6];
  						$turnosdia[7][$i]=$result->fields[7];
  						$turnosdia[8][$i]=$result->fields[8];
  						$turnosdia[9][$i]=$result->fields[9];
  						$turnosdia[10][$i]=$result->fields[10];
  						$turnosdia[11][$i]=$result->fields[11];
  						$turnosdia[12][$i]=$result->fields[12];
  						$turnosdia[13][$i]=$result->fields[13];
  						$turnosdia[14][$i]=$result->fields[14];
  						$i++;
  					}
  				}else{
  					$turnosdia[0][$i]=$result->fields[0];
  					$i++;
  				}
  				$result->MoveNext();
  			}
  		}
  		if($i<>0){
  			return $turnosdia;
  		}else{
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
				  //Cambio el sw_estado x sw_cantidad_pacientes_asignados
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
								and b.sw_cantidad_pacientes_asignados < cantidad_pacientes and date(fecha_turno)>=date(now())
								and b.sw_estado=0) as a
								where a.estado is null or a.estado=1 order by a.fecha_turno;";
// 					echo $sql1="select a.fecha_turno from (select distinct(fecha_turno), c.estado from agenda_turnos as a left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and c.departamento='".$_SESSION['AsignacionCitas']['departamento']."' and c.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'), agenda_citas as b where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and sw_estado&lt;cantidad_pacientes and date(fecha_turno)>=date(now())) as a where a.estado is null or a.estado=1 order by a.fecha_turno;";
				}
				else
				{
					if($_REQUEST['TipoBusqueda']==2)
					{
					  //cambio el campo sw_estado x sw_cantidad_pacientes_asignados
						$sql="select a.fecha_turno
									from
									(select distinct(fecha_turno), d.estado
									from agenda_turnos as a
									left join profesionales_estado as d on (a.profesional_id=d.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero
									and d.departamento='".$_SESSION['AsignacionCitas']['departamento']."'
									and d.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'),
									agenda_citas as b,profesionales as c
									where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']."
									and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and b.sw_cantidad_pacientes_asignados < cantidad_pacientes
									and a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and sexo_id='M'
									and date(fecha_turno)>=date(now()) and b.sw_estado=0) as a
									where a.estado is null or a.estado=1 order by a.fecha_turno;";
// 						echo $sql1="select a.fecha_turno from (select distinct(fecha_turno), d.estado from agenda_turnos as a left join profesionales_estado as d on (a.profesional_id=d.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero and d.departamento='".$_SESSION['AsignacionCitas']['departamento']."' and d.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'), agenda_citas as b,profesionales as c where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and sw_estado&lt;cantidad_pacientes  and a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and sexo_id='M' and date(fecha_turno)>=date(now())) as a where a.estado is null or a.estado=1 order by a.fecha_turno;";
					}
					else
					{
						if($_REQUEST['TipoBusqueda']==3)
						{
						  //cambio el campo sw_estado x sw_cantidad_pacientes_asignados
							$sql="select a.fecha_turno
										from
										(select distinct(fecha_turno), d.estado
										from agenda_turnos as a
										left join profesionales_estado as d on (a.profesional_id=d.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero
										and d.departamento='".$_SESSION['AsignacionCitas']['departamento']."'
										and d.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'),
										agenda_citas as b,profesionales as c
										where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']."
										and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and sw_cantidad_pacientes_asignados < cantidad_pacientes
										and a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero and sexo_id='F'
										and date(fecha_turno)>=date(now()) and b.sw_estado=0) as a
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
					//cambio el turno sw_estado x sw_cantidad_pacientes_asignados
					$sql="select distinct(fecha_turno)
								from agenda_turnos as a, agenda_citas as b
								where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']."
								and empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'
								and sw_cantidad_pacientes_asignados < cantidad_pacientes and profesional_id='".$a[0]."' and tipo_id_profesional='".$a[1]."'
								and date(fecha_turno)>=date(now()) and b.sw_estado=0
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
		{//cambio el campo sw_estado x sw_cantidad_pacientes_asignados
			$sql="select a.nombre_tercero as nombre, a.tercero_id, a.tipo_id_tercero
						from
						(select distinct(d.nombre_tercero), b.tercero_id , b.tipo_id_tercero,
						c.estado
						from agenda_turnos as a
						join agenda_citas as e on(a.agenda_turno_id=e.agenda_turno_id)
						left join profesionales_estado as c on (a.profesional_id=c.tercero_id and a.tipo_id_profesional=c.tipo_id_tercero
						and c.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'
						and c.departamento='".$_SESSION['AsignacionCitas']['departamento']."'),
						profesionales as b, terceros as d
						where a.tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'
						and a.profesional_id=b.tercero_id and a.tipo_id_profesional=d.tipo_id_tercero
						and a.profesional_id=d.tercero_id and a.tipo_id_profesional=b.tipo_id_tercero
						and date(a.fecha_turno)=date('".$_REQUEST['DiaEspe']."') and e.sw_estado='0'
						and e.sw_cantidad_pacientes_asignados<a.cantidad_pacientes) as a
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
	  //cambio el campo sw_estado x sw_cantidad_pacientes_asignados
		$a=explode(",",$_SESSION['AsignacionCitas']['profesional']);
		list($dbconn) = GetDBconn();
		//Quite el estado de sw_estado=0
		$sql="SELECT a.hora,a.agenda_turno_id, a.agenda_cita_id,a.sw_estado
					FROM agenda_citas as a, agenda_turnos as b
					WHERE a.agenda_turno_id=b.agenda_turno_id AND b.profesional_id='".$a[0]."'
					AND b.tipo_id_profesional='".$a[1]."' AND
		 			date(b.fecha_turno)=date('".$_REQUEST['DiaEspe']."')
					AND empresa_id='".$_SESSION['AsignacionCitas']['empresa']."'
					AND tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']."
					ORDER BY a.hora;";
		//Quite esta condicion:  and a.sw_cantidad_pacientes_asignados < b.cantidad_pacientes
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
				$cita[3][$i]=$result->fields[3];
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
* Esta funcion genera un listado con los tamaños de turnos
*
* @access public
* @return array listado de los tamaños de turnos
*/


	function TamCita()
	{
		$a=explode(",",$_SESSION['AsignacionCitas']['profesional']);
		list($dbconn) = GetDBconn();
		$sql="select distinct a.duracion, a.cantidad_pacientes,a.agenda_turno_id
		from agenda_turnos as a, agenda_citas as b
		where date(a.fecha_turno)=date('".$_REQUEST['DiaEspe']."') and a.profesional_id='".$a[0]."' and a.tipo_id_profesional='".$a[1]."' and a.tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and a.empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and a.agenda_turno_id=b.agenda_turno_id;";
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


	function LlamarIngresarNota()
     {
          $_SESSION['INSERTAR']['AGENDAMEDICA']['INGRESO'] = $_REQUEST['ingreso'];
          $_SESSION['INSERTAR']['AGENDAMEDICA']['EVOLUCION'] = $_REQUEST['evolucion'];
          $_SESSION['INSERTAR']['AGENDAMEDICA']['NOMBRE'] = $_REQUEST['nombre'];
          $_SESSION['INSERTAR']['AGENDAMEDICA']['ACCION'] = 'AgendaMedica';
          
     	$this->ReturnMetodoExterno('app','Notas_y_Monitoreo','user','CallIngresarNota');
          return true;
     }


/**
* Esta funcion recibe la informacion de la llegada del modulo de autorizacion del paciente
*
* @access public
* @return boolean retorna verdadero si se cumplio con exito el retorno y false si no
*

	function LLegadaAutorizarPaciente()
	{
		unset($_SESSION['EMPLEADOR']);
		if(empty($_SESSION['AsignacionCitas']['cita']))
		{
			if(empty($_SESSION['CumplirCita']['cita']))
			{
				if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']))
				{
					$this->FormaMensaje('No tiene autorizacion','Autorizaci�',ModuloGetURL('app','AgendaMedica','','DatosPaciente'),'Volver');
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
					$this->FormaMensaje('No tiene autorizacion','Autorizaci�',ModuloGetURL('app','AgendaMedica','',$metodo),'Volver');
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
				}*
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
				$this->FormaMensaje('Se cancelo el proceso de autorizaci�','Autorizaci�',ModuloGetURL('app','AgendaMedica','','DatosPaciente'),'Volver');
				unset($_SESSION['AUTORIZACIONES']);
				return true;
			}*

			if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']) OR empty($_SESSION['AUTORIZACIONES']['RETORNO']['plan_id']))
			{
				$this->FormaMensaje('Se cancelo el proceso de autorizaci�','Autorizaci�',ModuloGetURL('app','AgendaMedica','','DatosPaciente'),'Volver');
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
	}*/
	function LLegadaAutorizarPaciente()
	{
    SessionDelVar("ValidacionXjax");
		unset($_SESSION['EMPLEADOR']);
    $datos = $_REQUEST;
    
    $_SESSION['AUTORIZACIONES']['RETORNO']['rango'] = $datos['autorizacion']['rango'];
    $_SESSION['AUTORIZACIONES']['RETORNO']['semanas'] = $datos['autorizacion']['semanas'];
    $_SESSION['DATOSPACIENTE']['paciente_id'] = $datos['autorizacion']['paciente_id'];
    $_SESSION['DATOSPACIENTE']['tipo_id_paciente'] = $datos['autorizacion']['tipo_id_paciente'];
    $_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'] = $datos['autorizacion']['tipoafiliado'];
    $_SESSION['AUTORIZACIONES']['RETORNO']['plan_id'] = $datos['autorizacion']['plan_id'];
    $_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'] = $datos['autorizacion']['numero_autorizacion'];
    $_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion'] = $datos['autorizacion']['numero_autorizacion'];

    $Mensaje = 'Auto';
    $TipoServicio = $_SESSION['AdmHospitalizacion']['tipo'];
    
    if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['ext']))
      $_SESSION['AUTORIZACIONES']['RETORNO']['ext']='NULL'; 
        
    $_SESSION['AdmHospitalizacion']['paciente']['autorizacion'] = $datos['autorizacion']['numero_autorizacion'];
    //empleador
    if(!empty($datos['autorizacion']['id_empleador']))
    {
      $_SESSION['EMPLEADOR']['id_empleador']=$datos['autorizacion']['id_empleador'];
      $_SESSION['EMPLEADOR']['tipo_empleador']=$datos['autorizacion']['tipo_empleador'];
    }

		if(empty($_SESSION['AsignacionCitas']['cita']))
		{
			if(empty($_SESSION['CumplirCita']['cita']))
			{
				if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']))
				{
					$this->FormaMensaje('No tiene autorizacion','Autorizaci�',ModuloGetURL('app','AgendaMedica','','DatosPaciente'),'Volver');
					return true;
				}
				//$_SESSION['DATOSPACIENTE']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'];
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
				}

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
					$this->FormaMensaje('No tiene autorizacion','Autorizaci�',ModuloGetURL('app','AgendaMedica','',$metodo),'Volver');
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
				}

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
				
				$dbconn->CommitTrans();

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
		{
			if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']) OR empty($_SESSION['AUTORIZACIONES']['RETORNO']['plan_id']))
			{
				$this->FormaMensaje('Se cancelo el proceso de autorizaci�n','Autorización',ModuloGetURL('app','AgendaMedica','','DatosPaciente'),'Volver');
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
			}

			unset($_SESSION['AUTORIZACIONES']);
			if($this->BuscarPaciente()==false)
			{
				return false;
			}
		}
		return true;
	}
		/**
		* Llama el modulo de autorizaciones
		* @access public
		* @return boolean
		* @param string tipo de documento
		* @param int numero de documento
		* @param int plan_id
		*/
		function AutorizarPaciente($td = null,$doc= null,$plan= null)
		{
   
			$retorno = array();
     
      if(empty($_REQUEST['CUMPLIR']['paciente']))
			{
        if(empty($_SESSION['AsignacionCitas']['cita']))
        {
          $sql = "SELECT  a.tarifario_id As tarifario, 
                          a.cargo 
                  FROM    tarifarios_equivalencias as a, 
                          tarifarios_detalle as b, 
                          plan_tarifario as c 
                  WHERE   cargo_base='".$_SESSION['LiquidarCitas']['cargo_cups']."' 
                  AND     a.tarifario_id=b.tarifario_id 
                  AND     a.cargo=b.cargo 
                  AND     b.grupo_tarifario_id=c.grupo_tarifario_id 
                  AND     b.subgrupo_tarifario_id=c.subgrupo_tarifario_id 
                  AND     c.plan_id=".$_SESSION['LiquidarCitas']['Responsable']." 
                  AND     b.tarifario_id = c.tarifario_id; ";
          
          if(!$rst = $this->ConexionBaseDatos($sql, __LINE__))
            return false;
          
          if(!$rst->EOF)
    			{
    				$datos = $rst->GetRowAssoc($ToUpper = false);
    				$rst->MoveNext();
    			}
    			$rst->Close();
          
          $_SESSION['LiquidarCitas']['servicio']=$this->BusquedaServicio($_SESSION['LiquidarCitas']['departamento']);
          
   				$datos['servicio'] = $_SESSION['LiquidarCitas']['servicio'];
          $datos['cups'] = $_SESSION['LiquidarCitas']['cargo_cups'];
          $datos['idp'] = $_SESSION['LiquidarCitas']['Documento'];
          $datos['tipoid'] = $_SESSION['LiquidarCitas']['TipoDocumento'];
          $datos['plan_id'] = $_SESSION['LiquidarCitas']['Responsable'];
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
          $sql  = " SELECT  a.tarifario_id AS tarifario,
                            a.cargo 
                    FROM    tarifarios_equivalencias as a, 
                            tarifarios_detalle as b, 
                            plan_tarifario as c 
                    WHERE   cargo_base = '".$_SESSION['AsignacionCitas']['cargo_cups']."' 
                    AND     a.tarifario_id = b.tarifario_id 
                    AND     a.cargo = b.cargo 
                    AND     b.grupo_tarifario_id = c.grupo_tarifario_id 
                    AND     b.subgrupo_tarifario_id = c.subgrupo_tarifario_id 
                    AND     c.plan_id = ".$_SESSION['AsignacionCitas']['Responsable']." 
                    AND     b.tarifario_id = c.tarifario_id; ";
          
          if(!$rst = $this->ConexionBaseDatos($sql, __LINE__))
            return false;
          
          if(!$rst->EOF)
    			{
    				$datos = $rst->GetRowAssoc($ToUpper = false);
    				$rst->MoveNext();
    			}
    			$rst->Close();
   				$_SESSION['AsignacionCitas']['servicio']=$this->BusquedaServicio($_SESSION['AsignacionCitas']['departamento']);

          $datos['servicio'] = $_SESSION['AsignacionCitas']['servicio'];
          $datos['cups']=$_SESSION['AsignacionCitas']['cargo_cups'];
          $datos['idp'] = $_SESSION['AsignacionCitas']['Documento'];
          $datos['tipoid'] = $_SESSION['AsignacionCitas']['TipoDocumento'];
          $datos['plan_id'] = $_SESSION['AsignacionCitas']['Responsable'];
          $retorno['departamento'] = $_SESSION['AsignacionCitas']['departamento'];
          $retorno['NoAutorizacion'] = $_REQUEST['NoAutorizacion'];
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
			
        $datos['cups'] = $_REQUEST['CUMPLIR']['cargo_cups'];
        $datos['cargo'] = $_SESSION['CumplirCita']['cargo'];
        $datos['servicio'] = $_SESSION['CumplirCita']['servicio'];
        $datos['tarifario'] = $_SESSION['CumplirCita']['tarifario'];
				$datos['idp'] = $_SESSION['CumplirCita']['paciente_id'];
				$datos['tipoid'] = $_SESSION['CumplirCita']['tipo_id_paciente'];
				$datos['plan_id'] = $_SESSION['CumplirCita']['plan_id'];
				$datos['orden_servicio_id'] = $_SESSION['CumplirCita']['orden_servicio_id'];
				$datos['tipo_servicio'] = 'CE';
        
        $retorno['departamento'] = $_SESSION['CumplirCita']['departamento'];
			}
     
      
      $datos['afiliado']['rango'] = $_REQUEST['rango'];
      $datos['afiliado']['Semanas'] = $_REQUEST['Semanas'];
      $datos['afiliado']['tipoafiliado'] = $_REQUEST['tipoafiliado'];
     
			$action1 = ModuloGetURL('app','AgendaMedica','user','ListadoCitasCumplidas',$retorno);  
			$action2 = ModuloGetURL('app','AgendaMedica','user','LLegadaAutorizarPaciente',$retorno);  
			
			IncludeClass('Autorizaciones','','app','NCAutorizaciones');
					
			$aut = new Autorizaciones();
			$planes = $aut->ObtenerTiposPlanes($datos['plan_id']);
       //print_r($planes);
			$Autoriza = $this->ReturnModuloExterno('app','NCAutorizaciones','user');
			
			if($planes['sw_tipo_plan'] == '0' ||$planes['sw_tipo_plan'] == '1' ||$planes['sw_tipo_plan'] == '2' || $planes['sw_tipo_plan'] == '3')
			{
				$Autoriza->SetActionVolver($action1);
				$Autoriza->SetActionAceptar($action2);
        //$Autoriza->SetDatosPaciente();
        
				if(!$Autoriza->SetClaseAutorizacion('AD'))
				{
					$this->FormaMensaje($Autoriza->frmError['mensajeError'],'AUTORIZACIONES');	
					return true;
				}
     
       
				$Autoriza->FormaValidarAutoAdmisionHospitalizacion($datos);
				$this->salida = $Autoriza->salida;
			}
			else
				{
					$mensaje = "EL TIPO DE PLAN: ".$planes['sw_tipo_plan'].", NO ES VALIDO, FAVOR REVISAR LA INTEGRIDAD DE LA BASE DE DATOS";

					if($_SESSION['AdmHospitalizacion']['tipoorden'] == 'Externa')
						$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','Buscar',array('TIPOORDEN'=>'Externa'));  
					else
						$this->action1 = ModuloGetURL('app','AdmisionHospitalizacion','user','MetodoBuscarHospitalizacion');
												
					$this-> FormaMensaje($mensaje,'AUTORIZACIONES');						
				}
			
			return true;
		}
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
				$_SESSION['LiquidarCitas']['anestesiologo']=$_REQUEST['anestesiologo'];
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
				$_SESSION['AsignacionCitas']['CentroRemision']=$_REQUEST['CentroRemision'];
				$_SESSION['AsignacionCitas']['anestesiologo'] = $_REQUEST['anestesiologo'];
        $_SESSION['AsignacionCitas']['rango']=$_REQUEST['rango'];
        $_SESSION['AsignacionCitas']['tipoafiliado']=$_REQUEST['tipoafiliado'];
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
				//$this->frmError["MensajeError"]="Debe digitar el documento.";
				$this->frmError["MensajeError"]="Debe llenar los campos obligatorios (*).";
				if(!$this->DatosPaciente())
				{
					return false;
				}
			$validar=$this->ValidaInscripcionPaciente($TipoDocumento,$Documento,SessionGetVar("Programa_id"));
			SessionSetVar("Evolucion_id",$validar[0][evolucion_id]);
			SessionSetVar("Inscripcion_id",$validar[0][inscripcion_id]);
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
			$validar=$this->ValidaInscripcionPaciente($TipoDocumento,$Documento,SessionGetVar("Programa_id"));
			SessionSetVar("Evolucion_id",$validar[0][evolucion_id]);
			SessionSetVar("Inscripcion_id",$validar[0][inscripcion_id]);
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
		$query = "SELECT  primer_apellido,
                      segundo_apellido,
											primer_nombre,
											segundo_nombre,
											residencia_telefono,
											sexo_id,
											residencia_direccion,
                      TO_CHAR(fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento
                FROM  pacientes 
                WHERE paciente_id = '$Documento' 
                AND   tipo_id_paciente = '$TipoDocumento' ";
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
					$_SESSION['LiquidarCitas']['DATOSPACIENTE']['fecha_nacimiento']=$result->fields[7];
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
					$_SESSION['AsignacionCitas']['DATOSPACIENTE']['fecha_nacimiento']=$result->fields[7];
				}
        
				$_SESSION['AsignacionCitas']['Existe']=True;
				$sql="select e.numero_orden_id, b.fecha_vencimiento, b.fecha_activacion, d.orden_servicio_id, d.tipo_afiliado_id, d.rango, d.semanas_cotizadas, d.autorizacion_int
				from hc_os_solicitudes_citas as a
				join os_maestro as b on(a.hc_os_solicitud_id=b.hc_os_solicitud_id)
				join os_ordenes_servicios as d on(b.orden_servicio_id=d.orden_servicio_id)
				join os_internas as e on(b.numero_orden_id=e.numero_orden_id)
				left join os_cruce_citas as c on(b.numero_orden_id=c.numero_orden_id)
				where (b.sw_estado=1 or b.sw_estado=2) and tipo_consulta_id=".$tipocita." and c.numero_orden_id is null and e.cargo='$TipoConsulta' and e.departamento='".$departamento."' and d.tipo_id_paciente='$TipoDocumento' and d.paciente_id='$Documento' and d.plan_id=$Responsable;";
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
    SessionSetVar("ValidacionXjax","1");
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
			$fecha_nacimiento = $_SESSION['LiquidarCitas']['DATOSPACIENTE']['fecha_nacimiento'];
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
			$fecha_nacimiento=$_SESSION['AsignacionCitas']['DATOSPACIENTE']['fecha_nacimiento'];
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
      $inf = AutoCarga::factory('InformacionAfiliados','classes','app','AgendaMedica');
      $rq['tipo_id_paciente'] = $TipoDocumento;
      $rq['paciente_id'] = $Documento;
      $datosP = $inf->ObtenerDatosAfiliados($rq);
      $flag = (empty($datosP))? false: true;
      
      if(!$this->FormaPedirDatos($TipoDocumento, $Documento, '', $flag, $Responsable, $primer_apellido, $segundo_apellido, $primer_nombre, $segundo_nombre, $residencia_telefono, $sexo_id, $residencia_direccion,$fecha_nacimiento))
      {
        return false;
      }
      return true;
    }
    else
    {
      $datos = SessionGetVar("DatosPaciente_CE");
      $mensaje="1- El paciente no se encuentra registrado en los pacientes.";
      if(!$this->FormaPedirDatos($TipoDocumento,$Documento,$mensaje,false,null,$datos['primer_apellido'],$datos['segundo_apellido'],$datos['primer_nombre'],$datos['segundo_nombre'],$datos['telefono_residencia'],$datos['tipo_sexo_id'],$datos['direccion_residencia'],$datos['fecha_nacimiento']))
      {
        return false;
      }
      return true;
    }
	}
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
* Esta funcion genera un listado de centros de remision
*
* @access public
* @return array retorna un vector
*/
	function CentrosRemision()
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT * FROM centros_remision";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$i=0;	
			/*while (!$result->EOF)
			{
				$profesional[0][$i]=$result->fields[0];
				$profesional[1][$i]=$result->fields[1];
				$profesional[2][$i]=$result->fields[2];
				$result->MoveNext();
				$i++;
			}*/
			
			
			while (!$result->EOF)
			{
				$vars[0][$i]=$result->fields[0];
				$vars[1][$i]=$result->fields[1];
				$result->MoveNext();
				$i++;
			}

			$result->Close();
			return $vars;
	}
	
	




/**
* Esta funcion genera un listado de los cargos que puede cobrarse en una consulta
*
* @access public
* @return array retorna un vector con los cargos y sus descripciones
*/

	function TipoConsulta1($cargo=null)
	{
		list($dbconn) = GetDBconn();
		
		if(!empty($cargo))
			$tipo="AND a.cargo_cita='$cargo'";
		
		if(!empty($_SESSION['AsignacionCitas']['cita']))
		{
			$query="select b.cargo_cita, 
        b.descripcion,
        sw_pyp 
        FROM  tipos_consultas_cargos as a, 
              cargos_citas as b 
        where a.cargo_cita=b.cargo_cita 
        and   a.tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']."
			$tipo;";

		}
		else
		{
			$query="select b.cargo_cita, b.descripcion,sw_pyp from tipos_consultas_cargos as a, cargos_citas as b where a.cargo_cita=b.cargo_cita and a.tipo_consulta_id=".$_SESSION['LiquidarCitas']['cita']."
			$tipo;";
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
				$tipoconsulta[2][$i]=$result->fields[2];
				$result->MoveNext();
				$i++;
			}
		}
		return $tipoconsulta;
	}


/**
* Esta funcion que lista los programas pyp
*
* @access public
* @return array
*/

	function GetProgramasPYP()
	{
		
		list($dbconn) = GetDBconn();
		
		$query="SELECT programa_id,
									 descripcion
						FROM pyp_programas
						WHERE sw_estado='1'";

		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo AgendaMedica - GetProgramasPYP - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		
		return $vars;
	}
	
	
	function ValidaInscripcionPaciente($tipo_id_paciente,$paciente_id,$programa)
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT a.inscripcion_id,a.programa_id,b.evolucion_id
						FROM 	pyp_inscripciones_pacientes as a
						JOIN pyp_evoluciones_procesos as b
						ON
						(
							a.inscripcion_id=b.inscripcion_id
							and b.evolucion_id = 	(
																			SELECT max(evolucion_id)
																			FROM pyp_evoluciones_procesos
																			where inscripcion_id=a.inscripcion_id
																		)
						)
						WHERE tipo_id_paciente='$tipo_id_paciente'
						AND paciente_id='$paciente_id'
						AND programa_id=$programa
						AND estado='1'";
		
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo AgendaMedica - ValidaInscripcionPaciente - SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		
		if(empty($vars))
			return false;
		
		return $vars;	
	}


	
/**
* Esta funcion identifica los datos basicos que se necesitan para generar una orden de cita
*
* @access public
* @return boolean retorna verdadero si se pudo conseguir la revision completa y falso si no se pudo
*/

	function GuardarCita()
	{
		foreach($_SESSION['form_asignar_turno'] as $v=>$v1)
		{
			if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID')
			{
				$_REQUEST[$v]=$v1;
			}
		}
		foreach($_SESSION['form_asignar_turno_url'] as $v=>$v1)
		{
			if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID')
			{
				$_REQUEST[$v]=$v1;
			}
		}
//print_r($_REQUEST['TipoCita'];
      if(empty($_SESSION['AsignacionCitas']['cita']) )
      {
        $_SESSION['LiquidarCitas']['PrimerApellido']=strtoupper($_REQUEST['PrimerApellido']);
        $_SESSION['LiquidarCitas']['SegundoApellido']=strtoupper($_REQUEST['SegundoApellido']);
        $_SESSION['LiquidarCitas']['PrimerNombre']=strtoupper($_REQUEST['PrimerNombre']);
        $_SESSION['LiquidarCitas']['SegundoNombre']=strtoupper($_REQUEST['SegundoNombre']);
        $_SESSION['LiquidarCitas']['FechaNacimiento']=$_REQUEST['FechaNacimiento'];
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
        $FechaNacimiento= $_SESSION['LiquidarCitas']['FechaNacimiento'];
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
        
        if($_REQUEST['Telefono'] && !$_SESSION['AsignacionCitas']['Telefono'])
          $_SESSION['AsignacionCitas']['Telefono']=$_REQUEST['Telefono'];
        $_SESSION['AsignacionCitas']['FechaNacimiento']=$_REQUEST['FechaNacimiento'];
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
        $FechaNacimiento = $_SESSION['AsignacionCitas']['FechaNacimiento'];
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
				if(!$FechaNacimiento){ $this->frmError["FechaNacimiento"]=1; }
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
						if(!$PacienteId || !$TipoId || $Sexo==-1 || !$PrimerNombre || !$FechaNacimiento || !$PrimerApellido || empty($PrimerNombre) || empty($FechaNacimiento) || empty($PrimerApellido) || empty($Telefono) || $Responsable==-1 || $TipoCita==-1 || $TipoConsulta==-1 || $Nivel==-1){
									if($Responsable==-1){ $this->frmError["Responsable"]=1; }
									if(!$PacienteId){ $this->frmError["PacienteId"]=1; }
									if(!$TipoId){ $this->frmError["TipoId"]=1; }
									if(!$PrimerNombre){ $this->frmError["PrimerNombre"]=1; }
									if(!$PrimerApellido){ $this->frmError["PrimerApellido"]=1; }
									if(!$FechaNacimiento){ $this->frmError["FechaNacimiento"]=1; }
									if(empty($Telefono)){$this->frmError["Telefono"]=1;}
									if($TipoCita==-1){$this->frmError["TipoCita"]=1;}
									if($TipoConsulta==-1){$this->frmError["TipoConsulta"]=1;}
									if($Sexo==-1){ $this->frmError["Sexo"]=1; }
									
                  $this->frmError["MensajeError"]="Faltan datos obligatorios.";
									$accion=ModuloGetURL('app','Triage','user','ValidarDatosPacienteNew');
										
                  $datos = SessionGetVar("DatosPaciente_CE");
                  if(is_array($datos))
                  {
                    $mensaje="El paciente no se encuentra registrado en los pacientes.";
                    $this->FormaPedirDatos($TipoDocumento,$Documento,$mensaje,false,null,$datos['primer_apellido'],$datos['segundo_apellido'],$datos['primer_nombre'],$datos['segundo_nombre'],$datos['telefono_residencia'],$datos['tipo_sexo_id'],$datos['direccion_residencia'],$datos['fecha_nacimiento']);
                  }
                  else
                  {
                    if(!$this->FormaPedirDatos($TipoId,$PacienteId,'El paciente no se encuentra registrado en los pacientes.',$Existe,$Responsable,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$Telefono,$Sexo,$Direccion,$FechaNacimiento))
											return false;
									}
                  return true;
						}
			}
		if(!empty($_SESSION['CumplirCita']['cita']))
		{
		  $_REQUEST['DiaEspe']=date("Y-m-d");
			$_REQUEST['profesional']=$_SESSION['CumplirCita']['profesional'];
			$_REQUEST['nompro']=$_SESSION['CumplirCita']['nompro'];
			$i=1;
			foreach($_SESSION['DatosCitas'] as $k=>$v){
				$cadena.=$v;
				if($i<sizeof($_SESSION['DatosCitas'])){
          				$cadena.=',';
				}
			}
			unset($_SESSION['DatosCitas']);
			$_REQUEST['datosArreglo']=$cadena;
		  	//$this->EscogerBusqueda();
			if(!$this->InsertarDatosPaciente())
			{
				if(!$this->FormaPedirDatos($TipoId,$PacienteId,'',false,$Responsable,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$Telefono,$Sexo,$Direccion,$FechaNacimiento))
						return false;
			}
			return true;
			/*if($this->InsertarDatosPaciente()==false)
			{
				return false;
			}*/
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
				//if(!$this->EscogerBusqueda())/**  OJO InsertarDatosPaciente ****/
				if(!$this->InsertarDatosPaciente())
				{
					if(!$this->FormaPedirDatos($TipoId,$PacienteId,'',false,$Responsable,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$Telefono,$Sexo,$Direccion,$FechaNacimiento))
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
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$datos=$result->GetRowAssoc(false);
		$sql="select a.agenda_cita_id,agenda_cita_id_padre from agenda_citas_asignadas as a where a.agenda_cita_asignada_id=".$_REQUEST['idcita'].";";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$datos1=$result->fields[0];
		$citaPadre=$result->fields[1];
		$sql="delete from os_cruce_citas where numero_orden_id=".$datos['numero_orden_id'].";";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from os_maestro_cargos where numero_orden_id=".$datos['numero_orden_id'].";";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from os_internas where numero_orden_id=".$datos['numero_orden_id'].";";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from os_maestro where numero_orden_id=".$datos['numero_orden_id'].";";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from os_ordenes_servicios_empleadores where orden_servicio_id=".$datos['orden_servicio_id'].";";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from os_ordenes_servicios where orden_servicio_id=".$datos['orden_servicio_id'].";";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from hc_os_autorizaciones where hc_os_solicitud_id=".$datos['hc_os_solicitud_id'].";";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from hc_os_solicitudes_citas where hc_os_solicitud_id=".$datos['hc_os_solicitud_id'].";";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$sql="delete from hc_os_solicitudes where hc_os_solicitud_id=".$datos['hc_os_solicitud_id'].";";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
    //Nueva parte para borrar la cita padre y las multicitas

		$sql="select a.agenda_cita_asignada_id,a.agenda_cita_id from agenda_citas_asignadas as a where a.agenda_cita_id_padre='".$citaPadre."';";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
      while(!$result->EOF){
        $sql="delete from agenda_citas_asignadas where agenda_cita_asignada_id='".$result->fields[0]."';";
				$result1=$dbconn->Execute($sql);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				//lo comente porque esto ya lo hace el trigger

				/*$sql="update agenda_citas set sw_cantidad_pacientes_asignados=sw_cantidad_pacientes_asignados-1 where agenda_cita_id='".$result->fields[1]."';";
				$result1=$dbconn->Execute($sql);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				$sql="update agenda_citas set sw_estado=0 where agenda_cita_id='".$result->fields[1]."';";
				$result1=$dbconn->Execute($sql);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB a: " . $sql. $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}*/
				unset($_SESSION['AsignacionCitas']['idcitas'][$result->fields[0]]);
        $result->MoveNext();
			}
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
	//naydu
		list($dbconn) = GetDBconn();
		$sql="SELECT $cita as id_cita, fecha_turno || ' ' || hora as fecha, nombre_tercero, f.sw_estado,
					c.consultorio_id, g.descripcion, hora,a.agenda_cita_id_padre
					FROM agenda_citas_asignadas as a
					LEFT JOIN os_cruce_citas e ON(e.agenda_cita_asignada_id=a.agenda_cita_asignada_id)
					LEFT JOIN os_maestro f ON(e.numero_orden_id=f.numero_orden_id),
					agenda_citas as b,
					agenda_turnos as c
					LEFT JOIN tipos_consultorios g ON(c.consultorio_id=g.tipo_consultorio),
					terceros as d
					WHERE a.agenda_cita_asignada_id=$cita	AND  a.agenda_cita_id=b.agenda_cita_id
					AND b.agenda_turno_id=c.agenda_turno_id AND c.profesional_id=d.tercero_id
					AND c.tipo_id_profesional=d.tipo_id_tercero ORDER BY b.agenda_cita_id;";
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
* Esta funcion realiza la consulta con el id de la cita para buscar la informacion referente a la misma.
*
* @access public
* @return array retorna un vector con los datos de la cita
* @param int identificador de la cita
*/

	function BuscarInformacionCitaPrincipal($cita)
	{
		list($dbconn) = GetDBconn();
		/*$sql = "SELECT  $cita as id_cita, 
                    fecha_turno || ' ' || hora as fecha, 
                    nombre_tercero, 
                    f.sw_estado,
										g.consultorio,
                    c.consultorio_id, 
                    cs.descripcion, 
                    hora,
                    a.agenda_cita_id_padre,
                    a.sw_anestesiologo
            FROM    agenda_citas_asignadas as a
                    LEFT JOIN os_cruce_citas e 
                    ON(e.agenda_cita_asignada_id=a.agenda_cita_asignada_id)
                    LEFT JOIN os_maestro f 
                    ON(e.numero_orden_id=f.numero_orden_id),
          					agenda_citas as b,
          					agenda_turnos as c
										LEFT JOIN tipos_consulta_consultorios as tcc
                    ON(c.tipo_consulta_id=tcc.tipo_consulta_id)
          					LEFT JOIN consultorios as g
										ON (c.consultorio_id=g.consultorio_id)
										LEFT JOIN tipos_consultorios as cs 
                    ON(cs.tipo_consultorio=g.tipo_consultorio),
          					terceros as d										
					WHERE     a.agenda_cita_asignada_id=$cita	
          AND       a.agenda_cita_id=b.agenda_cita_id
					AND       b.agenda_turno_id=c.agenda_turno_id 
          AND       c.profesional_id=d.tercero_id
					AND       c.tipo_id_profesional=d.tipo_id_tercero 
          AND       a.agenda_cita_id=a.agenda_cita_id_padre
					ORDER BY b.agenda_cita_id;";*/
					//naydu
					
					$sql = "		  SELECT  $cita as id_cita, 
                    fecha_turno || ' ' || hora as fecha, 
                    nombre_tercero, 
                    f.sw_estado,
                    c.consultorio_id, 
                    TL.descripcion, 
                    hora,
                    a.agenda_cita_id_padre,
                    a.sw_anestesiologo
            FROM    agenda_citas_asignadas as a
                    LEFT JOIN os_cruce_citas e 
                    ON(e.agenda_cita_asignada_id=a.agenda_cita_asignada_id)
                    LEFT JOIN os_maestro f 
                    ON(e.numero_orden_id=f.numero_orden_id),
          					agenda_citas as b,
          					agenda_turnos as c
          					LEFT JOIN(SELECT C.consultorio as nom_consultorio,
									   TC.descripcion,
									   TC.direccion as dir,
									   TC.telefono as tel,
									   TCC.tipo_consulta_id
								FROM   consultorios C,
									   tipos_consultorios TC,
									   tipos_consulta_consultorios TCC
								WHERE  C.tipo_consultorio = TC.tipo_consultorio
									   AND TCC.consultorio_id= C.consultorio_id ) AS TL
            ON(c.consultorio_id = TL.nom_consultorio AND c.tipo_consulta_id = TL.tipo_consulta_id),
          					terceros as d
					WHERE     a.agenda_cita_asignada_id=$cita
          AND       a.agenda_cita_id=b.agenda_cita_id
					AND       b.agenda_turno_id=c.agenda_turno_id 
          AND       c.profesional_id=d.tercero_id
					AND       c.tipo_id_profesional=d.tipo_id_tercero 
          AND       a.agenda_cita_id=a.agenda_cita_id_padre
					ORDER BY b.agenda_cita_id;";
		  


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
		//print_r($_REQUEST);
		
		
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
			$centinela=0;
			foreach($_REQUEST as $v=>$dato)
			{
				if(substr_count ($v,'seleccion')==1)
				{
					$centinela=1;
					break;
				}
			}
			if($_SESSION['AgendaMedica']['CitaPrioritaria']==1)
			{
				$vectorDatos=explode(',',$_REQUEST['datosArreglo']);
				if($vectorDatos[0])
				{
					$_REQUEST['seleccion0']=$vectorDatos[0];
				}
				$centinela=1;
			}
			if($centinela!=1)
			{
				$this->frmError["MensajeError"]="DEBE SELECCIONAR UNA HORA PARA LA CITA.";
				unset($_REQUEST['DiaEspe']);
				//$this->DatosPaciente();
				return false;
			}
			$a=explode(",",$_SESSION['AsignacionCitas']['profesional']);
			$sql= " select  b.agenda_cita_id
			from    agenda_turnos as a, 
			agenda_citas as b
			where   tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." 
			and     profesional_id='".$a[0]."' 
			and     tipo_id_profesional='".$a[1]."' 
			and     date(fecha_turno)=date('".$_REQUEST['DiaEspe']."') 
			and     empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' 
			and     a.agenda_turno_id=b.agenda_turno_id and (";
			$ban=0;
			foreach($_REQUEST as $v=>$dato){
				if(substr_count ($v,'seleccion')==1){
					if($ban==0){
						$ban=1;
					}else{
						$sql.=" or ";
					}
						$sql.="b.agenda_cita_id='".$dato."'";
				}
			}
			$sql.=");";
			$result = $dbconn->Execute($sql);
      
     // print_r($sql);
			if($dbconn->ErrorNo() != 0){
				if($this->EscogerBusqueda()==false){
					return false;
				}
				$sql="ROLLBACK";
				$dbconn->Execute($sql);
        
				return true;
			}
			if(!$result->EOF){
			  if($_SESSION['AgendaMedica']['CitaPrioritaria']!=1)
        {
					$query = "SELECT a.tipo_consulta_id, b.paciente_id
										FROM   agenda_turnos as a, 
                           agenda_citas_asignadas as b, 
                           agenda_citas as c
										WHERE  date(a.fecha_turno)=date('".$_REQUEST['DiaEspe']."')
										AND    a.tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']."
										AND    c.agenda_turno_id=a.agenda_turno_id
										AND    b.agenda_cita_id=c.agenda_cita_id
										AND    b.sw_atencion=0 AND c.sw_estado!='3'
										AND    b.tipo_id_paciente='".$_SESSION['AsignacionCitas']['TipoDocumento']."'
										AND b.paciente_id='".$_SESSION['AsignacionCitas']['Documento']."'; ";

					$resultd = $dbconn->Execute($query);
          
					if($dbconn->ErrorNo() != 0){
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					if(!$resultd->EOF){
							$this->frmError["MensajeError"]="ESTE PACIENTE YA TIENE ASIGNADA CITA PARA HOY.";
							//$this->DatosPaciente();
							//$this->EscogerBusqueda();
							return false;
					}

					$a=$result->fields[0];
					$result->close();
					
					$sql="select count(a.*)
					from agenda_citas as a, agenda_turnos as b
					where a.agenda_turno_id=b.agenda_turno_id and a.agenda_cita_id=$a and a.sw_cantidad_pacientes_asignados < b.cantidad_pacientes ;";
					$result1 = $dbconn->Execute($sql);
         
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB a1: " . $dbconn->ErrorMsg();
						$this->fileError = __FILE__;
						$this->lineError = __LINE__;	
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						return false;
					}else{
						if($result1->fields[0]==0){
							$this->EscogerBusqueda(1);
							return true;
						}
					}
				}
			}
		}
		$a=0;
		foreach($_REQUEST as $v=>$dato){
			if(substr_count ($v,'tmpcita')==1){
				$a=1;
				break;
			}
		}
		$dbconn->BeginTrans();
		if($a==0){
      //Inserta las citas en una tabla temporal
			$tmpcita=$this->SetCitasTmp($this->SostenerCitas(),$dbconn);
     
		}
		$i=0;
		while($i<sizeof($tmpcita)){
			$tmp='tmpcita';
			$tmp=$tmp.$i;
			$_REQUEST[$tmp]=$tmpcita[$i];
			$i++;
		}
    
    $fecha = explode("/",$_SESSION['AsignacionCitas']['FechaNacimiento']);
		if(sizeof($fecha) == 3)
			$fechanacimiento = $fecha[2]."-".$fecha[1]."-".$fecha[0];
		else
			$fechanacimiento = $_SESSION['AsignacionCitas']['FechaNacimiento'];
    
    $pct =AutoCarga::factory('Pacientes','','app','DatosPaciente');
		$paciente = $pct->ObtenerDatosPaciente($_SESSION['AsignacionCitas']['TipoDocumento'],$_SESSION['AsignacionCitas']['Documento']);

		if(!empty($paciente))
    {
      $sql="UPDATE  pacientes 
            SET     residencia_direccion='".$_SESSION['AsignacionCitas']['Direccion']."',
                    residencia_telefono='".$_SESSION['AsignacionCitas']['Telefono']."',
                    fecha_nacimiento='".$fechanacimiento."'
            WHERE   paciente_id='".$_SESSION['AsignacionCitas']['Documento']."'
            AND     tipo_id_paciente='".$_SESSION['AsignacionCitas']['TipoDocumento']."';";
      $result = $dbconn->Execute($sql);
      if ($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->fileError = __FILE__;
        $this->lineError = __LINE__;						
        $this->mensajeDeError = "Error DB a2: " . $dbconn->ErrorMsg();
        $sql="ROLLBACK";
        $dbconn->Execute($sql);
        return false;
      }
		}
    else
    {
				$sql="insert into pacientes(paciente_id, tipo_id_paciente, primer_apellido, segundo_apellido, primer_nombre,
							segundo_nombre, residencia_direccion, residencia_telefono,fecha_nacimiento, sexo_id, fecha_registro, tipo_pais_id,
							tipo_dpto_id, tipo_mpio_id, usuario_id, zona_residencia,ocupacion_id)
							values ('".$_SESSION['AsignacionCitas']['Documento']."', '".$_SESSION['AsignacionCitas']['TipoDocumento']."', '".$_SESSION['AsignacionCitas']['PrimerApellido']."', '".$_SESSION['AsignacionCitas']['SegundoApellido']."',
							'".$_SESSION['AsignacionCitas']['PrimerNombre']."', '".$_SESSION['AsignacionCitas']['SegundoNombre']."', '".$_SESSION['AsignacionCitas']['Direccion']."',
							'".$_SESSION['AsignacionCitas']['Telefono']."','".$fechanacimiento."', '".$_SESSION['AsignacionCitas']['Sexo']."', '".date("Y-m-d H:i:s")."', '".GetVarConfigAplication('DefaultPais')."', '".GetVarConfigAplication('DefaultDpto')."', '".GetVarConfigAplication('DefaultMpio')."',
							".$_SESSION['SYSTEM_USUARIO_ID'].", '".GetVarConfigAplication('DefaultZona')."',NULL);";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB a3: " . $dbconn->ErrorMsg();
					$this->fileError = __FILE__;
					$this->lineError = __LINE__;						
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
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB a4: " . $dbconn->ErrorMsg();
					$this->fileError = __FILE__;
					$this->lineError = __LINE__;						
					$sql="ROLLBACK";
					$dbconn->Execute($query);
					return false;
				}
				//OJO: REVISAR ESTO SI HAY ERROR
				//$_SESSION['AsignacionCitas']['Existe']=true;
		}
		unset($_SESSION['AsignacionCitas']['citaasignada']);
		foreach($_REQUEST as $v=>$dato)
    {
			if(substr_count ($v,'tmpcita')==1){
				$sql="select agenda_cita_id from tmp_citas_asignacion where tmp_cita_asignacion_id=".$dato.";";
				$result = $dbconn->Execute($sql);
        
         
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB q: " . $dbconn->ErrorMsg();
					//$dbconn->RollbackTrans();
					$sql="ROLLBACK";
					$dbconn->Execute($sql);
        
       	
        return false;
				}
				if(!$result->EOF){
					$a=$result->fields[0];
         
				}
				if(empty($agendaCitaPadre)){
          $agendaCitaPadre=$a;
				}
				$sql="select fecha_turno || ' ' || hora as fecha, hora
				from agenda_citas as a,agenda_turnos as b
				where agenda_cita_id=$a and a.agenda_turno_id=b.agenda_turno_id;";
				$result = $dbconn->Execute($sql);
        
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB u: " . $dbconn->ErrorMsg();
					//$dbconn->RollbackTrans();
					$sql="ROLLBACK";
					$dbconn->Execute($sql);
					return false;
				}
				if(!$result->EOF){
					$c=$result->fields[0];
				}
				//CAMBIO DAR
				if(date($_REQUEST['DiaEspe'])==date('Y-m-d')){
						if($result->fields[1] <= date('H:i')){
								$this->frmError["MensajeError"]="DEBE ELEGIR UNA HORA MAYOR A LA ACTUAL.";
								unset($_REQUEST['DiaEspe']);
								//$this->DatosPaciente();
								//$this->EscogerBusqueda();
								return false;
						}
				}
				//FIN CAMBIO DAR
				$sql="select nextval('agenda_citas_asignadas_agenda_cita_asignada_id_seq');";
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB i: " . $dbconn->ErrorMsg();
					//$dbconn->RollbackTrans();
					$sql="ROLLBACK";
					$dbconn->Execute($sql);
					return false;
				}
				if(!$result->EOF){
					$b=$result->fields[0];
				}
        $prioritaria=0;
        if($_SESSION['AgendaMedica']['CitaPrioritaria']==1){
          $prioritaria=1;
        }
				
        $centro_r = $_SESSION['AsignacionCitas']['CentroRemision'];
				$anestesiologo = $_SESSION['AsignacionCitas']['anestesiologo'];
        
        $sql = "INSERT INTO agenda_citas_asignadas 
                (
                  agenda_cita_asignada_id, 
                  agenda_cita_id, 
                  paciente_id, 
                  tipo_id_paciente, 
                  tipo_cita, 
                  plan_id,
                  cargo_cita,
                  observacion,
                  usuario_id,
                  sw_historia,
                  agenda_cita_id_padre,
                  fecha_registro,
                  sw_prioritaria,
                  centro_remision_id,
                  sw_anestesiologo
                ) 
                VALUES
                (
                   ".$b.",
                   ".$a.",
                  '".$_SESSION['AsignacionCitas']['Documento']."',
                  '".$_SESSION['AsignacionCitas']['TipoDocumento']."', 
                  '".$_SESSION['AsignacionCitas']['TipoCita']."',
                  '".$_SESSION['AsignacionCitas']['Responsable']."', 
                  '".$_SESSION['AsignacionCitas']['cargo_cups']."',
                  '".$_SESSION['AsignacionCitas']['Observacion']."',
                   ".UserGetUID().",
                  '".((empty($_REQUEST['historia']))? "0":"1")."',
                   ".$agendaCitaPadre.",
                  '".date("Y-m-d H:i:s")."',
                  '".$prioritaria."',
                   ".((empty($centro_r))? "NULL":"'".$centro_r."'").",
                   ".(($anestesiologo)? "'".$anestesiologo."'":"NULL")."
                )";
                  
				$_SESSION['AsignacionCitas']['idcitas'][$b]=$b;
				if(empty($_SESSION['AsignacionCitas']['citaasignada']))
					$_SESSION['AsignacionCitas']['citaasignada']=$b;
        
				$result = $dbconn->Execute($sql);
      
			
				if($dbconn->ErrorNo() != 0)
        {
          unset($_REQUEST['datosArreglo']);
				 
          $this->frmError["MensajeError"]="YA EXISTE UNA CITA ASIGNADA EN ESTE TURNO.";
					
          $this->EscogerBusqueda();
          unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
					//$dbconn->RollbackTrans();
					$sql="ROLLBACK";
					$dbconn->Execute($sql);
         
					return true;
				}
        
        $cargos_add = SessionGetvar("Cagos_Adicionados".UserGetUID());
        if(!empty($cargos_add))
        {
          foreach($cargos_add as $k => $d)
          {
            $sql  = "INSERT INTO cargos_adicionales_citas ";
            $sql .= "   ( ";
            $sql .= "     cargo, ";
            $sql .= "     agenda_cita_asignada_id, ";
            $sql .= "     usuario_id, ";
            $sql .= "     fecha_registro ";
            $sql .= "   ) ";
            $sql .= "VALUES ";
            $sql .= "   ( ";
            $sql .= "     '".$k."',";
            $sql .= "      ".$b.",";
            $sql .= "      ".UserGetUID().",";
            $sql .= "      NOW()";
            $sql .= "   ) ";
            
            $result = $dbconn->Execute($sql);
    				if ($dbconn->ErrorNo() != 0)
            {
    					$this->error = "Error al Cargar el Modulo";
    					$this->mensajeDeError = "Error DB s: " . $dbconn->ErrorMsg()." ".$sql;
    					unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
    					$dbconn->Execute("ROLLBACK");
    					return false;
    				}
          }
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
        //Valida Cuando es el caso de multicitas para que solo lo haga cuando es la cita padre
        if($agendaCitaPadre==$a)
        {
					$sql="select a.tarifario_id, a.cargo,b.descripcion from tarifarios_equivalencias as a, tarifarios_detalle as b, plan_tarifario as c where cargo_base='".$_SESSION['AsignacionCitas']['cargo_cups']."' and a.tarifario_id=b.tarifario_id and a.cargo=b.cargo and b.grupo_tarifario_id=c.grupo_tarifario_id and b.subgrupo_tarifario_id=c.subgrupo_tarifario_id and c.plan_id=".$_SESSION['AsignacionCitas']['Responsable']." and b.tarifario_id=c.tarifario_id;";
					$result = $dbconn->Execute($sql);
					if($dbconn->ErrorNo() != 0){
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
						$sql="insert into hc_os_solicitudes (hc_os_solicitud_id, cargo, plan_id, os_tipo_solicitud_id, sw_estado, paciente_id, tipo_id_paciente) values (".$result1->fields[0].", '".$_SESSION['AsignacionCitas']['cargo_cups']."', ".$_SESSION['AsignacionCitas']['Responsable'].", 'CIT', '0', '".$_SESSION['AsignacionCitas']['Documento']."', '".$_SESSION['AsignacionCitas']['TipoDocumento']."');";
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
						
						if($_SESSION['AsignarCita']['evento'])
							$evento = $_SESSION['AsignarCita']['evento'];
						else
							$evento = "NULL";
							
						if($c<date("Y-m-d H:m"))
						{
							$c=date("Y-m-d H:m",mktime(24,24,24,date("m"),date("d"),date("Y")));
						}				
                    $sql = "INSERT INTO os_ordenes_servicios (
														orden_servicio_id, 
														autorizacion_int, 
														autorizacion_ext, 
														plan_id, 
														tipo_afiliado_id, 
														rango, 
														semanas_cotizadas, 
														servicio, 
														tipo_id_paciente, 
														paciente_id, 
														usuario_id, 
														fecha_registro,
														evento_soat
											) 
											VALUES (".$result2->fields[0].", 
														".$_SESSION['AsignacionCitas']['NumAutorizacion'].", 
														".$_SESSION['AsignacionCitas']['NumAutorizacion'].", 
														".$_SESSION['AsignacionCitas']['Responsable'].", 
														'".$_SESSION['AsignacionCitas']['tipo_afiliado_id']."', 
														'".$_SESSION['AsignacionCitas']['rango']."', 
														'".$_SESSION['AsignacionCitas']['semanas']."', 
														'".$_SESSION['AsignacionCitas']['servicio']."', 
														'".$_SESSION['AsignacionCitas']['TipoDocumento']."', 
														'".$_SESSION['AsignacionCitas']['Documento']."', 
														".UserGetUID().", 
														'".date("Y-m-d H:i:s")."',
														".$evento."
										);";
						
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
				}
				unset($_REQUEST[$v]);
			}
		}
		$_SESSION['AsignacionCitas']['Existe']='1';
		//$dbconn->CommitTrans();
		$sql="COMMIT";
		$dbconn->Execute($sql);
		
		/******/ 
		if(SessionGetVar("Inscripcion_id"))
			$this->IngresoSegumientoPYP($agendaCitaPadre);
		
	  SessionDelVar("Cagos_Adicionados".UserGetUID());
		/******/  
		
		if(!empty($_SESSION['CumplirCita']['cita']))
		{
			unset($_SESSION['AsignacionCitas']);
			$this->ListadoCitasCumplidas();
		}elseif(!empty($_SESSION['PROMOCION_Y_PREVENCION']['GESTION_SEGUIMIENTO_CPN'])){
      $_SESSION['PROMOCION_Y_PREVENCION']['GESTION_SEGUIMIENTO_CPN_CITA']=$agendaCitaPadre;      
      $this->ReturnMetodoExterno('app','GestionSeguimientoCPN','user','FrmSeguimientoCPN',array('datos'=>$_SESSION['DatosSeguimientoCPN'],'opcion'=>$_SESSION['opcion']));		
      unset($_SESSION['AsignacionCitas']);
      unset($_SESSION['DATOSPACIENTE']);
      unset($_SESSION['CONSULTAEXT']);         
    }elseif(!empty($_SESSION['PROMOCION_Y_PREVENCION']['GESTION_SEGUIMIENTO_RENOPROTECCION'])){
			$_SESSION['PROMOCION_Y_PREVENCION']['GESTION_SEGUIMIENTO_RENOPROTECCION_CITA']=$agendaCitaPadre;      
      $this->ReturnMetodoExterno('app','GestionSeguimientoReno','user','FrmSeguimientoReno',array('datos'=>$_SESSION['DatosSeguimientoReno'],'opcion'=>$_SESSION['opcion']));		
      unset($_SESSION['AsignacionCitas']);
      unset($_SESSION['DATOSPACIENTE']);
      unset($_SESSION['CONSULTAEXT']); 
		}
		elseif(!empty($_SESSION['PROMOCION_Y_PREVENCION']['GESTION_SEGUIMIENTO_PFLIAR'])){
			$_SESSION['PROMOCION_Y_PREVENCION']['GESTION_SEGUIMIENTO_PFLIAR_CITA']=$agendaCitaPadre;      
      $this->ReturnMetodoExterno('app','GestionSeguimientoPFliar','user','FrmSeguimientoPFliar',array('datos'=>$_SESSION['DatosSeguimientoPFliar'],'opcion'=>$_SESSION['opcion']));		
      unset($_SESSION['AsignacionCitas']);
      unset($_SESSION['DATOSPACIENTE']);
      unset($_SESSION['CONSULTAEXT']); 
		}
		else
		{
			//aqui va cuando se termina de asignar la cita
			//cambio dar: verifica si agenda maneja caja o no
			if(ModuloGetVar('app','AgendaMedica','NoManejoCaja') == 1)
			{
				$this->frmError["MensajeError"]="SE ASIGNO LA CITA.";
				$_REQUEST='';
				$_REQUEST['metodo'] ='EscogerBusqueda';
				$_REQUEST['modulo'] ='AgendaMedica';
				
				$this->EscogerBusqueda();
				return true;
			}
			else
			{
				$this->PantallaFinal();
			}
		}
		
		return true;
	}
	
	
	function IngresoSegumientoPYP($agendaCitaPadre)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		if(empty($_SESSION['AsignacionCitas']['cita']))
		{
			$tipo_id_paciente=$_SESSION['LiquidarCitas']['TipoDocumento'];
		}
		else
		{
			$tipo_id_paciente=$_SESSION['AsignacionCitas']['TipoDocumento'];
		}
		
		if(empty($_SESSION['AsignacionCitas']['cita']))
		{
			$paciente_id=$_SESSION['LiquidarCitas']['Documento'];
		}
		else
		{
			$paciente_id=$_SESSION['AsignacionCitas']['Documento'];
		}
		
		$citas=$this->GetCitasID($paciente_id,$tipo_id_paciente,$agendaCitaPadre);
		$cita_asignada_id=$citas[0][agenda_cita_asignada_id];
		
		switch(SessionGetVar("Programa_id"))
		{
			case 1:
				$query="SELECT nextval('pyp_cpn_seguimiento_pyp_cpn_seguimiento_id_seq'::regclass);";
		
				$result = $dbconn->Execute($query);
				
				$segumiento_id=$result->fields[0];
			
				$query="INSERT INTO pyp_cpn_seguimiento
								(
									pyp_cpn_seguimiento_id,
									paciente_id,
									tipo_id_paciente,
									cita_asignada_id,
									fecha_registro,
									usuario_id,
									evolucion_id,
									inscripcion_id
								)
								VALUES
								(
									$segumiento_id,
									'$paciente_id',
									'$tipo_id_paciente',
									$cita_asignada_id,
									now(),
									".UserGetUID().",
									".SessionGetVar("Evolucion_id").",
									".SessionGetVar("Inscripcion_id")."
								);";
				
				$result = $dbconn->Execute($query);
				
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error en el Modulo AgendaMedica - IngresoSegumientoPYP - SQL 1";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
					$this->ban=1;
					$dbconn->RollBackTrans();
					return false;
				}
				
			break;
			
			case 2:
				$query="SELECT nextval('pyp_renoproteccion_seguimient_pyp_renoproteccion_seguimient_seq'::regclass);";
								
				$result = $dbconn->Execute($query);
				
				$segumiento_id=$result->fields[0];
			
				$query="INSERT INTO pyp_renoproteccion_seguimiento
								(
									pyp_renoproteccion_seguimiento_id,
									paciente_id,
									tipo_id_paciente,
									cita_asignada_id,
									fecha_registro,
									usuario_id,
									evolucion_id,
									inscripcion_id
								)
								VALUES
								(
									$segumiento_id,
									'$paciente_id',
									'$tipo_id_paciente',
									$cita_asignada_id,
									now(),
									".UserGetUID().",
									".SessionGetVar("Evolucion_id").",
									".SessionGetVar("Inscripcion_id")."
								);";
			
				$result = $dbconn->Execute($query);
				
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error en el Modulo AgendaMedica - InsertarSeguimientoPYP - SQL 2";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
					$this->ban=1;
					$dbconn->RollBackTrans();
					return false;
				}
				
			break;
			
			case 3:
				$query="SELECT nextval('pyp_plan_fliar_seguimiento_pyp_plan_fliar_seguimiento_id_seq'::regclass);";
								
				$result = $dbconn->Execute($query);
				
				$segumiento_id=$result->fields[0];
			
				$query="INSERT INTO pyp_plan_fliar_seguimiento
								(
									pyp_plan_fliar_seguimiento_id,
									paciente_id,
									tipo_id_paciente,
									cita_asignada_id,
									fecha_registro,
									usuario_id,
									evolucion_id,
									inscripcion_id
								)
								VALUES
								(
									$segumiento_id,
									'$paciente_id',
									'$tipo_id_paciente',
									$cita_asignada_id,
									now(),
									".UserGetUID().",
									".SessionGetVar("Evolucion_id").",
									".SessionGetVar("Inscripcion_id")."
								);";
				
				$result = $dbconn->Execute($query);
				
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error en el Modulo AgendaMedica - InsertarSeguimientoPYP - SQL 3";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
					$this->ban=1;
					$dbconn->RollBackTrans();
					return false;
				}
			break;
			
		}
		
		$dbconn->CommitTrans();
		return true;
	}
	
	
	function GetCitasID($paciente_id,$tipo_id_paciente,$cita_padre,$fecha_turno=null)
	{
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		
		$con="";
		if($cita_padre)
		{
			$con="AND a.agenda_cita_id_padre=$cita_padre";
		}
		elseif($fecha_turno)
		{
			$con="AND date(c.fecha_turno)='$fecha_turno' AND date(c.fecha_turno) > date(now())";
		}
		
		if(!empty($con))
		{
			$sql="
					SELECT a.agenda_cita_asignada_id
					FROM agenda_citas_asignadas AS a
					JOIN agenda_citas AS b
					ON
					(
						a.agenda_cita_id=b.agenda_cita_id
						AND a.agenda_cita_id NOT IN 
																			(
																				SELECT agenda_cita_asignada_id
																				FROM agenda_citas_asignadas_cancelacion
																			)
					)
					JOIN agenda_turnos AS c 
					ON
					(
						b.agenda_turno_id=c.agenda_turno_id
					)
					
					WHERE a.paciente_id='$paciente_id'
					AND a.tipo_id_paciente='$tipo_id_paciente'
					$con
			";
			
				$result = $dbconn->Execute($sql);
				
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error en el Modulo AgendaMedica - GetCitasID - SQL";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->frmError["MensajeError"]=$this->error."<br>".$this->mensajeDeError;
					$this->ban=1;
					$dbconn->RollBackTrans();
					return false;
				}
				else
				{
					if($result->RecordCount() > 0)
					{
						while(!$result->EOF)
						{
							$vars[]=$result->GetRowAssoc($toUpper=false);
							$result->MoveNext();
						}
					}
				}
				
			$dbconn->CommitTrans();
			return $vars;
		}
		return "";
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
			$query = "SELECT e.descripcion, d.servicio, d.via_ingreso, a.caja_id, d.departamento, d.descripcion as descripcion3, d.prefijo_fac_contado, d.prefijo_fac_credito,
										c.descripcion as descripcion2, c.centro_utilidad, c.empresa_id, b.razon_social as descripcion1,
										d.cuenta_tipo_id
										FROM userpermisos_cajas_rapidas as a, empresas as b, departamentos as c,
										cajas_rapidas as d, centros_utilidad as e
										WHERE a.usuario_id=".UserGetUID()." and d.departamento=c.departamento and d.departamento='".$_SESSION['AsignacionCitas']['departamento']."'
										and c.empresa_id=b.empresa_id and a.caja_id=d.caja_id
										and e.centro_utilidad=c.centro_utilidad and e.empresa_id=c.empresa_id";
		}
		else
		{
			$query = "SELECT e.descripcion, d.servicio, d.via_ingreso, a.caja_id, d.departamento, d.descripcion as descripcion3, d.prefijo_fac_contado, d.prefijo_fac_credito,
										c.descripcion as descripcion2, c.centro_utilidad, c.empresa_id, b.razon_social as descripcion1,
										d.cuenta_tipo_id
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
			//$_SESSION['CAJA']['datos']=$_REQUEST['datos'];
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
			$sql="select b.agenda_cita_id
			from agenda_turnos as a, agenda_citas as b
			where tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and profesional_id='".$a[0]."' and tipo_id_profesional='".$a[1]."' and date(fecha_turno)=date('".$_SESSION['CumplirCita']['DiaEspe']."') and empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and a.agenda_turno_id=b.agenda_turno_id and (";
		}
		else
		{
			$sql="select b.agenda_cita_id
			from agenda_turnos as a, agenda_citas as b
			where tipo_consulta_id=".$_SESSION['AsignacionCitas']['cita']." and profesional_id='".$a[0]."' and tipo_id_profesional='".$a[1]."' and date(fecha_turno)=date('".$_REQUEST['DiaEspe']."') and empresa_id='".$_SESSION['AsignacionCitas']['empresa']."' and a.agenda_turno_id=b.agenda_turno_id and (";
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
				$sql.="b.agenda_cita_id='".$dato."'   ";
			}
		}
		$sql.=") order by b.agenda_cita_id ;";
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
		  //cambio sw_estado x sw_cantidad_pacientes_asignados
			/*$sql="update agenda_citas set sw_cantidad_pacientes_asignados=sw_cantidad_pacientes_asignados+1 where agenda_cita_id=".$vec[$i].";";
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
			if($i==0){$estado=1;}else{$estado=2;}
			$sql="UPDATE agenda_citas
			SET sw_estado='$estado'
			WHERE agenda_cita_id=".$vec[$i]." AND
			sw_cantidad_pacientes_asignados=(SELECT a.cantidad_pacientes FROM agenda_turnos a,agenda_citas b WHERE agenda_cita_id=".$vec[$i]." AND b.agenda_turno_id=a.agenda_turno_id);";
			$result = $dbconn->Execute($sql);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				//$dbconn->RollbackTrans();
				$sql="ROLLBACK";
				$dbconn->Execute($sql);
				return false;
			}*/
			$sql="select nextval('tmp_citas_asignacion_tmp_cita_asignacion_id_seq');";
			$result = $dbconn->Execute($sql);
      //print_r($sql);
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
      //$dbconn->debug=true;
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

		foreach($_REQUEST as $v=>$dato){
		
			if(substr_count ($v,'tmpcita')==1){
			  //cambio el campo sw_estado x sw_cantidad_pacientes_asignados
				$sql="update agenda_citas set sw_cantidad_pacientes_asignados=sw_cantidad_pacientes_asignados-1,sw_estado='0' where agenda_cita_id=(select agenda_cita_id from tmp_citas_asignacion where tmp_cita_asignacion_id=".$dato.");";
				$result = $dbconn->Execute($sql);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
					$sql="delete from tmp_citas_asignacion where tmp_cita_asignacion_id=".$dato.";";
					$result = $dbconn->Execute($sql);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
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
		$query="select distinct a.nombre_tercero as nombre, a.descripcion, a.consultorio, a.profesional_id, a.tipo_id_profesional 
						from (select nombre_tercero, d.descripcion, c.consultorio, a.profesional_id, a.tipo_id_profesional, e.estado from agenda_turnos as a left join profesionales_estado as e on (a.profesional_id=e.tercero_id and a.tipo_id_profesional=e.tipo_id_tercero and e.empresa_id='".$_SESSION['CumplirCita']['empresa']."' and e.departamento='".$_SESSION['CumplirCita']['departamento']."') left join consultorios as c on (a.consultorio_id=c.consultorio_id) left join tipos_consultorios as d on(c.tipo_consultorio=d.tipo_consultorio), profesionales as b, terceros as g 
						where a.empresa_id='".$_SESSION['CumplirCita']['empresa']."' and a.tipo_consulta_id=".$_SESSION['CumplirCita']['cita']." and a.profesional_id=b.tercero_id and a.tipo_id_profesional=b.tipo_id_tercero and a.profesional_id=g.tercero_id and a.tipo_id_profesional=g.tipo_id_tercero and date(a.fecha_turno)=date(now())) as a where a.estado is null or a.estado=1 order by a.nombre_tercero;";
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

	function VerMultiCitas($turno,$hora)
	{
		list($dbconn) = GetDBconn();
    //$dbconn->debug = true;
		$query = "SELECT ACA.*
									FROM agenda_turnos AT,
											agenda_citas AC
											LEFT JOIN agenda_citas_asignadas ACA
											ON(AC.agenda_cita_id = ACA.agenda_cita_id)
									WHERE AT.fecha_turno::date = now()::date
									AND AT.agenda_turno_id = $turno
									AND AC.hora = '$hora'
									AND AT.agenda_turno_id = AC.agenda_turno_id
									AND AT.empresa_id='".$_SESSION['CumplirCita']['empresa']."'";
//					echo '<br><br><br>';
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if(!empty($resulta->fields[0]))
		{
			return $resulta->RecordCount();
		}
		else
		{
			return false;
		}
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
    //$dbconn->debug = true;
		$a=explode(",",$_SESSION['CumplirCita']['profesional']);
		$query = "SELECT agenda_turno_id 
              FROM agenda_turnos
							WHERE date(fecha_turno)=date(now())
							and tipo_consulta_id=".$_SESSION['CumplirCita']['cita']."
							and profesional_id='".$a[0]."'
							and tipo_id_profesional='".$a[1]."'
							and empresa_id='".$_SESSION['CumplirCita']['empresa']."'";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
    $var = "";
		while(!$resulta->EOF)
    {
      $var .= (($var == "")? "":",").$resulta->fields[0];
			$resulta->MoveNext();
		}
    
		$resulta->Close();
    
		$query="SELECT  b.hora, 
                    c.agenda_cita_asignada_id,
        						c.paciente_id, 
                    c.tipo_id_paciente,
        						null as nombre_paciente,
                    p.sw_estado,
        						c.observacion,
                    c.agenda_cita_id,
        						c.plan_id, 
                    h.cargo,
        						i.ingreso, 
                    h.tarifario_id,
        						p.numerodecuenta, 
                    p.numero_orden_id,
        						j.tipo_afiliado_id, 
                    j.rango,
        						j.semanas_cotizadas, 
                    j.autorizacion_int,
        						j.autorizacion_ext, 
                    h.os_maestro_cargos_id,
        						j.orden_servicio_id,
                    b.agenda_cita_id,
        						p.cargo_cups,
                    c.agenda_cita_id_padre,
        						AT.agenda_turno_id,
        						AT.cantidad_pacientes,
                    c.sw_anestesiologo					            
						FROM    agenda_citas as b
        						LEFT JOIN agenda_citas_asignadas as c 
                    ON (b.agenda_cita_id=c.agenda_cita_id and c.sw_atencion!='1')
        						LEFT JOIN os_cruce_citas as g 
                    ON (c.agenda_cita_asignada_id=g.agenda_cita_asignada_id)
        						LEFT JOIN os_maestro as p 
                    ON (g.numero_orden_id=p.numero_orden_id)
								LEFT JOIN os_ordenes_servicios as j 
                    ON(p.orden_servicio_id=j.orden_servicio_id)
        						LEFT JOIN os_maestro_cargos as h 
                    ON(g.numero_orden_id=h.numero_orden_id)
        						LEFT JOIN cuentas as i 
                    ON(p.numerodecuenta=i.numerodecuenta),
        						agenda_turnos AT
						WHERE   b.agenda_turno_id in(".$var.") 
            AND     (b.sw_estado='0' OR b.sw_estado='1' OR b.sw_estado='2')
						AND     b.agenda_turno_id = AT.agenda_turno_id
						order by b.hora,c.tipo_id_paciente,c.paciente_id;";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
    {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		
    while(!$result->EOF)
    {
			if(!empty($result->fields[2]) or !empty($result->fields[5]))
      {
				$t=0;
				$s=0;

				if($s==0)
        {
					$datos[0][$i]=$result->fields[0];
					$datos[1][$i]=$result->fields[1];
					$datos[2][$i]=$result->fields[2];
					$datos[3][$i]=$result->fields[3];
					$datos[4][$i]=$result->fields[4];

					if($result->fields[5]==1){
						$datos[5][$i]=1;
					}elseif($result->fields[5]==3){
						$datos[5][$i]=3;
					}elseif($result->fields[5]==2){
						$datos[5][$i]=2;
					}elseif($result->fields[5]==5){
						$datos[5][$i]=0;
					}else{
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
					$datos[23][$i]=$result->fields[23];//AgendaCitaPadre
					$datos[24][$i]=$result->fields[24];//agenda_turno_id
					$datos[25][$i]=$result->fields[25];//cantidad_pacientes
					$datos[26][$i]=$result->fields[26];//sw_anestesiologo
					//$datos[27][$i]=$result->fields[27];//descripcion cargos_citas
					$i++;
				}
			}
      else
      {
        //Aqui entran las citas que no tienen asignado ni paciente ni orden de servicio
				$datos[0][$i]=$result->fields[0];
				$datos[6][$i]=2;
				$datos[8][$i]=$result->fields[21];
				$datos[24][$i]=$result->fields[24];//agenda_turno_id
				$datos[25][$i]=$result->fields[25];//cantidad_pacientes
				$i++;
			}
			$result->MoveNext();
		}
    
		if($i<>0)
      return $datos;
		
		return false;
	}
	

  /**
  * Esta funcion retorna el listado de las citas con la posibilidad de asignar la cita sin importar si ya esta ocupada o no
  *
  * @access public
  * @return array retorna el listado de citas
  */
  
  	function CargosCitasAdicionales ($cita_id){
	
	list($dbconn) = GetDBconn();
	
	$query ="   select CA.cargo, CU.descripcion
				from cargos_adicionales_citas CA LEFT JOIN cups CU 
				ON(CU.cargo= CA.cargo)
				where agenda_cita_asignada_id =".$cita_id.";";
				
				$result = $dbconn->Execute($query);
				
				
		if($dbconn->ErrorNo() != 0)
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
	
	
	function ListadoCitasPrioritarias()
	{
		list($dbconn) = GetDBconn();
		$a=explode(",",$_SESSION['CumplirCita']['profesional']);
		$query="select b.hora, b.agenda_cita_id
		from agenda_turnos as a, agenda_citas as b
		where date(fecha_turno)=date(now()) and empresa_id='".$_SESSION['CumplirCita']['empresa']."' and tipo_consulta_id=".$_SESSION['CumplirCita']['cita']." and profesional_id='".$a[0]."' and tipo_id_profesional='".$a[1]."' and a.agenda_turno_id=b.agenda_turno_id  and (b.sw_estado='0' OR b.sw_estado='1' OR b.sw_estado='2') order by b.hora;";
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



	function BuscarPacientes(){
		$Buscar=$_REQUEST['Buscar'];
		if(empty($_REQUEST['Busqueda'])){
			$_REQUEST['Busqueda']=1;
		}
		if($_REQUEST['Busqueda']==1){
			$TipoId=$_REQUEST['TipoDocumento'];
			$PacienteId=$_REQUEST['Documento'];
			if(!$PacienteId){
				if(!$PacienteId){
					$this->frmError["Documento"]=1;
				}
				$this->frmError["MensajeError"]="Debe digitar el Nmero del Documento.";
				if(!$this->BuscarPacienteCancelar($mensaje,$arr)){
					return false;
				}
				return true;
			}
			$Datos=$this->Buscar1($TipoId,$PacienteId);
			if($Datos){
				$this->BuscarPacienteCancelar($mensaje,$Datos);
				return true;
			}else{
				$mensaje='La busqueda no arrojo resultados.';
				$this->BuscarPacienteCancelar($mensaje,$Datos);
				return true;
			}
		}
		if($_REQUEST['Busqueda']==2){
			$nombres=$_REQUEST['nombres'];
			$apellidos=$_REQUEST['apellidos'];
			if(!$nombres && !$apellidos){
				$this->frmError["MensajeError"]="Debe digitar el Nombre o el Apellido.";
				if(!$this->BuscarPacienteCancelar($mensaje,$arr)){
					return false;
				}
				return true;
			}
			$apellidos=strtoupper($apellidos);
			$nombres=strtoupper($nombres);
			if($apellidos!="" && $nombres==""){
				$Datos=$this->Buscar2($apellidos,$caso='A');
				if($Datos){
					$this->BuscarPacienteCancelar($mensaje,$Datos);
					return true;
				}else{
					$mensaje='La busqueda no arrojo resultados.';
					$this->BuscarPacienteCancelar($mensaje,$Datos);
					return true;
				}
			}
			if($apellidos=="" && $nombres!=""){
				$Datos=$this->Buscar2($nombres,$caso='N');
				if($Datos){
					$this->BuscarPacienteCancelar($mensaje,$Datos);
					return true;
				}else{
					$mensaje='La busqueda no arrojo resultados.';
					$this->BuscarPacienteCancelar($mensaje,$Datos);
					return true;
				}
			}
			if($apellidos!="" && $nombres!=""){
				$listaApellidos = explode(" ", $apellidos);
				$listaNombre = explode(" ", $nombres);
				$var=$listaNombre[0].'%'.$listaNombre[1].'%'.$listaApellidos[0].'%'.$listaApellidos[1];
				$Datos=$this->Buscar2($var,$caso='T');
				if($Datos){
					$this->BuscarPacienteCancelar($mensaje,$Datos);
					return true;
				}else{
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
			$query="select b.agenda_cita_id, a.paciente_id, a.tipo_id_paciente, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, b.agenda_cita_asignada_id, b.sw_atencion, d.fecha_turno || ' ' || c.hora as fecha_total, e.nombre_tercero, b.plan_id,b.agenda_cita_id_padre,
			(CASE WHEN b.agenda_cita_id=b.agenda_cita_id_padre THEN 1
			 ELSE 2
			END) as ordenamiento
			from pacientes as a
			join agenda_citas_asignadas as b on (a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id and b.agenda_cita_asignada_id not in (select agenda_cita_asignada_id from agenda_citas_asignadas_cancelacion))
			join agenda_citas as c on (b.agenda_cita_id=c.agenda_cita_id)
			join agenda_turnos as d on (c.agenda_turno_id=d.agenda_turno_id)
			join terceros as e on(d.profesional_id=e.tercero_id and d.tipo_id_profesional=e.tipo_id_tercero)
			where a.paciente_id='".$PacienteId."' and a.tipo_id_paciente='".$TipoId."' and d.tipo_consulta_id=".$_SESSION['CancelarCita']['cita']." and date(d.fecha_turno)>=date(now())      
			ORDER BY b.agenda_cita_id_padre,ordenamiento;";
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
			$query = "select b.agenda_cita_id, a.paciente_id, a.tipo_id_paciente, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, b.agenda_cita_asignada_id, b.sw_atencion, d.fecha_turno || ' ' || c.hora as fecha_total, e.nombre_tercero, b.plan_id,b.agenda_cita_id_padre,
			(CASE WHEN b.agenda_cita_id=b.agenda_cita_id_padre THEN 1
			 ELSE 2
			END) as ordenamiento
			from pacientes as a
			join agenda_citas_asignadas as b on (a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id and b.agenda_cita_asignada_id not in (select agenda_cita_asignada_id from agenda_citas_asignadas_cancelacion))
			join agenda_citas as c on (b.agenda_cita_id=c.agenda_cita_id)
			join agenda_turnos as d on (c.agenda_turno_id=d.agenda_turno_id)
			join terceros as e on(d.profesional_id=e.tercero_id and d.tipo_id_profesional=e.tipo_id_tercero)
			where (a.primer_apellido || ' ' || a.segundo_apellido) like '%$var%' and d.tipo_consulta_id=".$_SESSION['CancelarCita']['cita']." and date(d.fecha_turno)>=date(now())
			ORDER BY b.agenda_cita_id_padre,ordenamiento;";
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
			$query = "select b.agenda_cita_id, a.paciente_id, a.tipo_id_paciente, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, b.agenda_cita_asignada_id, b.sw_atencion, d.fecha_turno || ' ' || c.hora as fecha_total, e.nombre_tercero, b.plan_id,b.agenda_cita_id_padre,
			(CASE WHEN b.agenda_cita_id=b.agenda_cita_id_padre THEN 1
			 ELSE 2
			END) as ordenamiento
			from pacientes as a
      join agenda_citas_asignadas as b on (a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id and b.agenda_cita_asignada_id not in (select agenda_cita_asignada_id from agenda_citas_asignadas_cancelacion))
			join agenda_citas as c on (b.agenda_cita_id=c.agenda_cita_id)
			join agenda_turnos as d on (c.agenda_turno_id=d.agenda_turno_id)
			join terceros as e on(d.profesional_id=e.tercero_id and d.tipo_id_profesional=e.tipo_id_tercero)
			where (a.primer_nombre || ' ' || a.segundo_nombre) like '%$var%' and d.tipo_consulta_id=".$_SESSION['CancelarCita']['cita']." and date(d.fecha_turno)>=date(now())
			ORDER BY b.agenda_cita_id_padre,ordenamiento;";
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
			$query = "select b.agenda_cita_id, a.paciente_id, a.tipo_id_paciente, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, b.agenda_cita_asignada_id, b.sw_atencion, d.fecha_turno || ' ' || c.hora as fecha_total, e.nombre_tercero, b.plan_id,b.agenda_cita_id_padre,
			(CASE WHEN b.agenda_cita_id=b.agenda_cita_id_padre THEN 1
			 ELSE 2
			END) as ordenamiento
			from pacientes as a
			join agenda_citas_asignadas as b on (a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id and b.agenda_cita_asignada_id not in (select agenda_cita_asignada_id from agenda_citas_asignadas_cancelacion))
			join agenda_citas as c on (b.agenda_cita_id=c.agenda_cita_id)
			join agenda_turnos as d on (c.agenda_turno_id=d.agenda_turno_id)
			join terceros as e on(d.profesional_id=e.tercero_id and d.tipo_id_profesional=e.tipo_id_tercero)
			where (a.primer_nombre || ' ' || a.segundo_nombre || ' ' || a.primer_apellido || ' ' || a.segundo_apellido) like '%$var%' and d.tipo_consulta_id=".$_SESSION['CancelarCita']['cita']." and date(d.fecha_turno)>=date(now())
			ORDER BY b.agenda_cita_id_padre,ordenamiento;";
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
			$vars[$i][12]=$result->fields[12];//AgendaCitaIdPadre
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
	{ //Cambion Verificacion si ya Existe una evolucion Lorena

	  if($_REQUEST['Cancelar']==1){
		  $Datos=$_REQUEST['Datos'];
      if($this->VerificarEvolucionPaciente($Datos[7])==1){
        $mensaje="El Paciente ".$Datos[2].' '.$Datos[1].' - '.$Datos[5].' '.$Datos[6].' '.$Datos[3].' '.$Datos[4]." ya tiene una evolucion";
				if(!$this->BuscarPacienteCancelar($mensaje,$arr)){
					return false;
				}
				return true;
			}
		}
		//Fin cambio
		if($this->DatosAdicionalesBorrarCita()==false)
		{
			return false;
		}
		return true;
	}

	function VerificarEvolucionPaciente($AgendaCitaId){
		list($dbconn) = GetDBconn();
		$sql="SELECT *
		FROM os_cruce_citas a,os_maestro b,hc_evoluciones c
		WHERE a.agenda_cita_asignada_id='".$AgendaCitaId."' AND a.numero_orden_id=b.numero_orden_id AND
		b.numerodecuenta=c.numerodecuenta";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
      if($result->RecordCount()>0){
        return 1;
			}
		}
		return 0;
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

	function BorrarCita(){

		if($_REQUEST['justificacion']==-1 or strlen($_REQUEST['Observacion'])>256){
			if($_REQUEST['justificacion']==-1){
				$this->frmError["justificacion"]=1;
			}
			if(strlen($_REQUEST['Observacion'])>256){
				$this->frmError["Observacion"]=1;
			}
			$this->frmError["MensajeError"]="Faltan datos obligatorios o la cadena de observacion es muy larga.";
			if($this->DatosAdicionalesBorrarCita()==false){
				return false;
			}
			return true;
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$sql="insert into agenda_citas_asignadas_cancelacion (agenda_cita_asignada_id, tipo_cancelacion_id, observacion,fecha_registro,usuario_id) values (".$_SESSION['CancelarCita']['CITA']['cita_asignada_id'].", ".$_REQUEST['justificacion'].", '".$_REQUEST['Observacion']."','".date("Y-m-d H:i:s")."','".UserGetUID()."');";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error en la tabla agenda_citas_asignadas_cancelacion";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		//Lo hace el Trigger
		//Hallar las agendas citas asignadas con el mismo padre
		$query="SELECT agenda_cita_id_padre
		FROM agenda_citas_asignadas a
		WHERE a.agenda_cita_asignada_id=".$_SESSION['CancelarCita']['CITA']['cita_asignada_id']." AND a.sw_atencion!='1'
    AND a.paciente_id='".$_SESSION['CancelarCita']['CITA']['paciente_id']."' AND a.tipo_id_paciente='".$_SESSION['CancelarCita']['CITA']['tipo_id_paciente']."';";
		$resultCitas=$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error en la tabla agenda_cita_id_padre";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
      $DatosCitas=$resultCitas->GetRowAssoc(false);
		}
		//$sql="update agenda_citas_asignadas set sw_atencion=1 where agenda_cita_asignada_id=".$_SESSION['CancelarCita']['CITA']['cita_asignada_id'].";";
		//Cambia por la actualizacion de todos los padres
		$sql="UPDATE agenda_citas_asignadas SET sw_atencion=1 WHERE agenda_cita_id_padre=".$DatosCitas['agenda_cita_id_padre']." AND sw_atencion!='1'
    AND paciente_id='".$_SESSION['CancelarCita']['CITA']['paciente_id']."' AND tipo_id_paciente='".$_SESSION['CancelarCita']['CITA']['tipo_id_paciente']."';";
		//echo $sql;
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error en la tabla agenda_citas_asignadas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		//Lo hace el Trigger

		//esto lo quite porque causaba problema cambio dar agregue ,sw_estado_cancelacion='1' 
    //camio el campo sw_estado x sw_cantidad_pacientes_asignados
		//Coloco el for para que haga la actualizacion en todas las citas
		/*for($i=0;$i<sizeof($DatosCitas);$i++){
      $sql="UPDATE agenda_citas SET sw_cantidad_pacientes_asignados=sw_cantidad_pacientes_asignados-1,sw_estado='0'
			WHERE agenda_cita_id=".$DatosCitas[$i]['agenda_cita_id'].";";
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}*/
		if($_REQUEST['liberacion']==1){
		  $sql="delete from os_cruce_citas where agenda_cita_asignada_id=".$_SESSION['CancelarCita']['CITA']['cita_asignada_id'].";";
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo os_cruce_citas";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}else{
		  $sql="select numero_orden_id from os_cruce_citas where agenda_cita_asignada_id=".$_SESSION['CancelarCita']['CITA']['cita_asignada_id'].";";
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo os_cruce_citas";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			$sql="update os_maestro set sw_estado=9 where numero_orden_id=".$result->fields[0].";";
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo os_maestro";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		$dbconn->CommitTrans();
		if($this->AsignarNuevaCita()==false){
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
    //Insercion de datos Adicionales del Paciente
    $dbconn->BeginTrans();
    if($_REQUEST["raza".$pfj]!=-1)
    {
      $raza="'".$_REQUEST["raza".$pfj]."'";      
    }
    else
    {
      $raza='NULL';      
    }
    if($_REQUEST["mano_dominante".$pfj]!=-1)
    {
      $mano_dominante="'".$_REQUEST["mano_dominante".$pfj]."'";      
    }
    else
    {
      $mano_dominante='NULL';      
    }
    if($_REQUEST["preferencia_sexual".$pfj]!=-1)
    {
      $preferencia_sexual="'".$_REQUEST["preferencia_sexual".$pfj]."'";      
    }
    else
    {
      $preferencia_sexual='NULL';      
    }
    $query ="SELECT * FROM pacientes_datos_adicionales
    WHERE paciente_id='".$_REQUEST['pacienteid']."'
    AND tipo_id_paciente='".$_REQUEST['tipoid']."';";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0)
    {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $dbconn->RollbackTrans();
      return false;
    }
    if($result->RecordCount() > 0)
    {
      $query ="UPDATE pacientes_datos_adicionales
      SET tipo_raza_id=$raza,mano_dominante=$mano_dominante,
          preferencia_sexual=$preferencia_sexual
      WHERE paciente_id='".$_REQUEST['pacienteid']."' AND tipo_id_paciente='".$_REQUEST['tipoid']."';";
    }
    else
    {
      $query ="INSERT INTO
      pacientes_datos_adicionales(paciente_id, tipo_id_paciente,tipo_raza_id,mano_dominante,
      preferencia_sexual)
      VALUES('".$_REQUEST['pacienteid']."','".$_REQUEST['tipoid']."',$raza,$mano_dominante,$preferencia_sexual);";
    }
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0)
    {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $dbconn->RollbackTrans();
      return false;
    }       
		if($_REQUEST['ocupacion_id']!='')
		{
			$sql="update pacientes set ocupacion_id='".$_REQUEST['ocupacion_id']."' where paciente_id='".$_REQUEST['pacienteid']."' and tipo_id_paciente='".$_REQUEST['tipoid']."';";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
				return false;
			}
		}
    $dbconn->CommitTrans();
    //fin insercion
    
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
			$query = "SELECT  sexo_id,descripcion 
                FROM    tipo_sexo 
                WHERE   sw_mostrar  = '1'
                ORDER BY indice_de_orden";
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

	function verificarSecuenciaDatos($vectorDatos){

		list($dbconn) = GetDBconn();
		for($count=0;$count<sizeof($vectorDatos);$count++){
			if($count==0){
			  //cambio el campo sw_estado x sw_cantidad_pacientes_asignados
        $query="SELECT age.hora,tur.duracion,tur.fecha_turno
				FROM agenda_citas age,agenda_turnos tur
				WHERE age.agenda_cita_id='$vectorDatos[$count]' AND
				age.agenda_turno_id=tur.agenda_turno_id AND
				age.sw_cantidad_pacientes_asignados < tur.cantidad_pacientes AND
				age.sw_estado='0';";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}else{
          $datos=$result->RecordCount();
					if($datos){
            $horaCmp=$result->fields[0];
            $duracion=$result->fields[1];
						$fecha=$result->fields[2];
					}else{
					  $this->frmError["MensajeError"]="No se puede Seleccionar los Intervalos porque no tienen Secuencia";
            return 0;
					}
				}
			}else{
			  //cambio el campo sw_estado x sw_cantidad_pacientes_asignados
        $query="SELECT age.hora
				FROM agenda_citas age,agenda_turnos tur
				WHERE age.agenda_cita_id='$vectorDatos[$count]' AND
				age.agenda_turno_id=tur.agenda_turno_id AND
				age.sw_cantidad_pacientes_asignados < tur.cantidad_pacientes AND
				age.sw_estado='0';";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}else{
          $datos=$result->RecordCount();
					if($datos){
            $hora=$result->fields[0];
            (list($ano,$mes,$dia)=explode('-',$fecha));
						(list($hhCmp,$mmCmp)=explode(':',$horaCmp));
            (list($hh,$mm)=explode(':',$hora));
						if(mktime($hhCmp,$mmCmp+$duracion,0,$mes,$dia,$ano)!=mktime($hh,$mm,0,$mes,$dia,$ano)){
						  $this->frmError["MensajeError"]="No se puede Seleccionar los Intervalos porque no tienen Secuencia";
              return 0;
						}
            $horaCmp=$hora;
					}else{
					  $this->frmError["MensajeError"]="No se puede Seleccionar los Intervalos porque no tienen Secuencia";
            return 0;
					}
				}
			}
		}
		return 1;
	}

	function CrearCuenta()
	{
				IncludeLib('funciones_facturacion');
				list($dbconn) = GetDBconn();	
				$query = "SELECT centro_utilidad FROM departamentos
									WHERE departamento='".$_SESSION['CumplirCita']['departamento']."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0) {
						$this->error = "SELECT centro_utilidad";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->fileError = __FILE__;
						$this->lineError = __LINE__;
						$dbconn->RollbackTrans();
						return false;
				}
				$cu=$result->fields[0];
				$result->Close();
									
				CrearCuentaIngreso($dbconn,$_REQUEST['tipoid'],$_REQUEST['id'],$_SESSION['CumplirCita']['departamento'],$_REQUEST['plan'],$_SESSION['CumplirCita']['empresa'],$cu,$_REQUEST['afiliado'],$_REQUEST['rango'],$_REQUEST['sem'],$_REQUEST['auto']);
				
				$query = "UPDATE cuentas SET estado='0' WHERE numerodecuenta=".$_SESSION['FUNCIONES']['FACTURACION']['CUENTA']."";
				$dbconn->Execute($query);
				if($dbconn->Affected_Rows() == 0){
						$this->error = "fallo actualizacion [estado] en cuentas";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->lineError = __LINE__;
						$dbconn->RollbackTrans();
						return false;
				}
				
				//actualiza la orden				
				$query="UPDATE os_maestro SET sw_estado='2',numerodecuenta=".$_SESSION['FUNCIONES']['FACTURACION']['CUENTA']."
								WHERE numero_orden_id='".$_REQUEST['numero_orden_id']."' AND (sw_estado ='1' OR sw_estado='5')";
				$dbconn->Execute($query);
				if($dbconn->Affected_Rows() == 0){
						$this->error = "fallo actualizacion [estado] en cuentas";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->lineError = __LINE__;
						$dbconn->RollbackTrans();
						return false;
				}		
				
				$dbconn->CommitTrans();
				$this->ListadoCitasCumplidas();
				return true;
	}
	/***********************************************************************************
	* Funcion que permite mostrar la forma para selecionar el evento SOAT si el plan 
	* es tipo soat, si no se continua el procedimiento normal
	*
	* @return boolean
	************************************************************************************/
	function EventoSoat()
	{
		foreach($_SESSION['form_buscar_paciente'] as $v=>$v1)
		{
			if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID')
			{
				$_REQUEST[$v]=$v1;
			}
		}

		unset($_SESSION['AsignarCita']['evento']);
		$this->TipoConsulta = $_REQUEST['TipoConsulta'];
		$this->Documento = $_REQUEST['Documento'];
		$this->TipoDoc = $_REQUEST['TipoDocumento'];
		$this->Plan = $_REQUEST['Responsable'];
		if(!$this->TipoDoc || !$this->Documento || $this->Plan==-1)
		{
			$_SESSION['AsignacionCitas']['Documento'] = $_REQUEST['Documento'];
			$_SESSION['AsignacionCitas']['cargo_cups'] = $_REQUEST['TipoConsulta'];
			$_SESSION['AsignacionCitas']['Responsable'] = $_REQUEST['Responsable'];
			$_SESSION['AsignacionCitas']['TipoDocumento'] = $_REQUEST['TipoDocumento'];
			
			if(!$this->Documento)
			{
				$this->frmError["Documento"]=1;
			}
			if($this->Plan==-1)
			{
				$this->frmError["Responsable"]=1;
			}
 			if($this->TipoConsulta==-1)
 			{
 				$this->frmError["TipoConsulta"]=1;
 			}
			//$this->frmError["MensajeError"]="Debe digitar el documento.";
			$this->frmError["MensajeError"]="Debe llenar los campos obligatorios (*).";
			
			$this->DatosPaciente();
      return true;
		}
		$swPlan = $this->BuscarEventoSoat($this->Plan);
		
		if($swPlan == 1)
		{
			$vec = $_REQUEST['arreglo'];
			$arr['arreglo'] = $vec; 
			$arreglo = array('TipoConsulta'=>$this->TipoConsulta,'Documento'=>$this->Documento,'TipoDocumento'=>$this->TipoDoc,
											 'Responsable'=>$this->Plan,'rango' => $_REQUEST['rango'],'Semanas' => $_REQUEST['Semanas'],
                       'tipoafiliado' => $_REQUEST['tipoafiliado'],$arr);
			$this->action1 = ModuloGetURL('app','AgendaMedica','user','DatosPaciente');
			$this->action2 = ModuloGetURL('app','AgendaMedica','user','ValidarEventoSoat',$arreglo);
			$this->MostrarEventosSoat();
		}
		else
		{
			$this->DatosIniciales();
		}
		return true;
	}
	/**********************************************************************************
	* Funcion donde se averigua, si el plan es tipo soat o no
	* @param $plan int plan id
	*
	* @return string
	***********************************************************************************/
  function BuscarEventoSoat($plan)
  {
		$sql = "SELECT  sw_tipo_plan FROM planes WHERE plan_id= ".$plan." ";
		list($dbconn) = GetDBconn();		
		$rst = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Guardar en la Base de Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		return $rst->fields[0];
  }
  /***********************************************************************************
  * Funcion donde se buscan los eventos soat del paciente
  * @params tipo del documento
  *					documento del paciente
  *
  * @return array
  ************************************************************************************/
  function BuscarEventoSoatPaciente($TipoDo,$Docume)
	{
		$sql = "SELECT	A.evento,
										A.poliza,
										A.condicion_accidentado,
										A.saldo,
										A.codigo_eps,
										A.accidente_id,
										A.asegurado,
										A.empresa_id,
										C.nombre_tercero,
										E.razon_social,
										TO_CHAR(D.fecha_accidente,'DD/MM/YYYY') AS fecha_accidente,
										TO_CHAR(D.fecha_accidente,'HH:MM AM') AS hora_accidente,
										F.ingreso
							FROM 	soat_eventos AS A
										LEFT JOIN soat_accidente AS D 
										ON (A.accidente_id=D.accidente_id)
										LEFT JOIN ingresos_soat AS F
										ON (A.evento=F.evento),
										soat_polizas AS B,
										terceros AS C,
										empresas AS E
							WHERE A.tipo_id_paciente='".$TipoDo."'
							AND 	A.paciente_id='".$Docume."'
							AND 	A.poliza=B.poliza
							AND 	B.tipo_id_tercero=C.tipo_id_tercero
							AND 	B.tercero_id=C.tercero_id
							AND 	A.empresa_id=E.empresa_id
							ORDER BY poliza;";

		list($dbconn) = GetDBconn();
		$rst = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

		while(!$rst->EOF)
		{
			$eventos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
		}
		return $eventos;
	}
	/***********************************************************************************
	* Funcion donde se averiguan los datos del paciente
  * @params tipo del documento
  *					documento del paciente
  *
  * @return array
	************************************************************************************/
	function BuscarNombrePaci($TipoDo,$Doc)
	{
		
		$sql = "SELECT	primer_apellido||' '||segundo_apellido AS apellidos,
										primer_nombre||' '||segundo_nombre AS nombres
						FROM 		pacientes
						WHERE 	tipo_id_paciente='".$TipoDo."'
						AND 		paciente_id='".$Doc."';";
		
		list($dbconn) = GetDBconn();
		$rst = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$rst->EOF)
		{
			$paciente = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
		}
		return $paciente;
	}
	/***********************************************************************************
	* Funcion donde se valida si se selecciono un evento soat
	*
	* @return boolean 
	************************************************************************************/
	function ValidarEventoSoat()
	{
		$valor = $_REQUEST['eligevento'];
		if(!$valor)
		{
			$this->frmError['MensajeError'] = "SE DEBE SELECCIONAR UN ENVENTO SOAT PARA CONTINUAR CON LA ADMISION";
			$this->EventoSoat();
		}
		else
		{
			$_SESSION['AsignarCita']['evento'] = $valor;
			$this->DatosIniciales();
		}
		return true;
	}
/***********************************************************************************
  * Funcion donde se valida los grupos etnicos en la base de datos
  *
  * @return array 
  ************************************************************************************/
    
  function ConsultarTiposRazas()
  {
    $pfj=$this->frmPrefijo;
    list($dbconnect) = GetDBconn();
    $query = "select tipo_raza_id,descripcion from tipos_razas order by descripcion ";
    $result = $dbconnect->Execute($query);
    if ($dbconnect->ErrorNo() != 0)
    {
      $this->error = "Error al consultar hc_tipos_sanguineos";
      $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
        }
      }
    }
    return $vars;
  }
  
  function Get_datos_Adicionales($tipoidpaciente,$paciente)
  {
    list($dbconn) = GetDBconn();
    $query ="SELECT a.* FROM pacientes_datos_adicionales a    
    WHERE a.paciente_id='".$paciente."'
    AND a.tipo_id_paciente='".$tipoidpaciente."';";
     $res = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al traer informaci� de la tabla pacientes_datos_adicionales ";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }

    if($res->EOF)
    {
      return '';
    }
    return $res->GetRowAssoc($ToUpper = false);
  }
	
	/**
	***ConsultaNotasAdministrativas
	**/
	function ConsultaNotasAdministrativas($TipoId,$PacienteId,$fechaCita,$horaCita)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT * 
							FROM notas_administrativas_consulta_externa
							WHERE fecha_cita = '$fechaCita'
							AND hora_cita = '$horaCita'
							AND tipo_id_paciente = '$TipoId'
							AND paciente_id = '$PacienteId';";
			$rst = $dbconn->Execute($query);
      
      if ($dbconn->ErrorNo() != 0) 
      {
				$this->error = "Error al traer los cargos";
				echo "Error DB : " . $dbconn->ErrorMsg();
			}
      else
      {
        if(!$rst->EOF)
        {
          $datos = $rst->GetRowAssoc($ToUpper = false);
        }
      }
			return $datos;
	}

	/***
		/////////////////////////////////////////////////
	//////////////////CLINICA DE REHABILITACION/////////////////////////
		/////////////////////////////////////////////////
	****/
	function CantidadPacienteNuevoTurno($AgendaCitaId,$tipo_consulta)
	{
		list($dbconn) = GetDBconn();
		$sql="SELECT a.* FROM agenda_citas as b, agenda_citas_asignadas as a, agenda_turnos as c WHERE
		     	     
		     b.agenda_cita_id = ".$AgendaCitaId." AND
		     b.agenda_turno_id = c.agenda_turno_id AND
		     b.agenda_cita_id = a.agenda_cita_id AND
		     c.tipo_consulta_id = ".$tipo_consulta." AND
		     a.tipo_cita = '01';";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else{
      			$datos=$result->RecordCount();
      			if($datos){
        			return true;
				
      			}
			else{
				return false;
			}
    		}
	
	}
	
	function GetTipoPlan($responsable)
	{
		list($dbconn) = GetDBconn();
		$SQL="SELECT a.* FROM 	tipos_planes as a, planes as b WHERE
			b.plan_id = ".$responsable." AND b.sw_tipo_plan = a.sw_tipo_plan;";
		$rst = $dbconn->Execute($SQL);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer informacion de la tabla tipos_planes metodo GetTipoPlan.";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
				return false;
			}
			else{
				$datos=$rst->RecordCount();
      				if($datos){
        				$var=$rst->fields[0];
					$rst->Close();
					return $var;			
      				}
				
			}
		return true;
	}
	
	function CantidadCitasAsignadasPlan($AgendaCitaId,$TipoPlanId)
	{
		list($dbconn) = GetDBconn();
		$SQL="SELECT COUNT(a.agenda_cita_id)  FROM agenda_citas_asignadas as a, tipos_planes as b, planes as c
		WHERE a.agenda_cita_id=".$AgendaCitaId." AND a.plan_id=c.plan_id 
		AND c.sw_tipo_plan=b.sw_tipo_plan
		AND b.sw_tipo_plan='".$TipoPlanId."'
		AND a.agenda_cita_asignada_id NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion)
		;";
		
		
		$rst = $dbconn->Execute($SQL);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer informacion de la tabla agenda_citas_asiganadas metodo CantidadCitasAsignadasPlan.";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
				return false;
			}
			else{
				
        			$var=$rst->fields[0];
				$rst->Close();
				return $var;			
      				
				
			}
		return true;
	}
	
	
	/*foreach($ordenes AS $i => $v)
			{
				if($v[cantidad] > 0)
				{
					$cnt_citas_asignar=$v[cantidad];
					$j = 1;
					for($k = 0; $k<sizeof($agenda); $k++)
					{
						if($j == $v[cantidad]-1)
						{
							$k = sizeof($agenda);
						}
						$j++;
					}
				}
			}
			echo '<br>cantidad'.$cnt_citas_asignar;*/
	
	function PacienteFechaTurno($TipoId,$PacienteId,$fechacita,$tipo_consulta)
	{
		
		list($dbconn) = GetDBconn();
		$SQL="select count(a.*) from agenda_citas_asignadas as a, agenda_citas as b, agenda_turnos as c 
		 where a.paciente_id='".$PacienteId."' and a.tipo_id_paciente='".$TipoId."' and a.agenda_cita_id=b.agenda_cita_id AND c.tipo_consulta_id = ".$tipo_consulta."
		 and b.agenda_turno_id=c.agenda_turno_id and c.fecha_turno::date = '".$fechacita."'::date ;";
		
		
		$rst = $dbconn->Execute($SQL);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer informacion de la tabla agenda_citas_asiganadas metodo CantidadCitasAsignadasPlan.";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
				return false;
			}
			else{
				
        			$var=$rst->fields[0];
				$rst->Close();
				return $var;			
      				
				
			}
		return true;
	 
	}
	
	
	
	function OrganizarTurnosMinPaciente($datos,$cantidad)
	{
		//echo '<pre>';print_r($datos);
		
		$fecha=$datos[0][2];
		$hora=$datos[0][3];
		$k=0;
		$x=1;
		
		$i=0;
		while($i<$cantidad)
		{
			if($datos[$i][2]==$fecha && $datos[$i][3]==$hora){
				$cnt1[$k]=$x++;
				$i++;
			}
			else{ 
				$fecha=$datos[$i][2];
				$hora=$datos[$i][3];
				$k++;	
			}
		}
		
// 		echo '<pre>';
// 		print_r($cnt1);
		
		$j=0;
		$acu=0;
		$d=$cantidad-1;
		$m=0;
		
		while($acu<=$d)  
		{
			for($i=$m;$i<$cnt1[$j];$i++){
				for($y=$m;$y<$cnt1[$j];$y++){
					if($datos[$i][6]<$datos[$y][6]){
						$datos_aux=$datos[$y];
						$datos[$y]=$datos[$i];
						$datos[$i]=$datos_aux;
					}
				}
			}
			$acu = $cnt1[$j];
			$m=$acu;
			$j+=1;
			//echo $m.'-'.$cnt1[$j].' ';
		}
		
		return $datos;
		
	}
	
	
	
	/**
	***DatosAsignacionCitaAutomatica
	**/
	function DatosAsignacionCitaAutomatica()
	{
		
		/*echo '<pre>'.print_r($_SESSION['AsignacionCitas']);
		echo '<pre>'.print_r($_REQUEST);*/
		/*echo 'Modulo en construccion';*/
		
		list($dbconn) = GetDBconn();
		//$dbconn->debug=true;	
			
			
		if($this->ActualizarDatosPaciente($dbconn)){	
			
			//SELECCIONAR PROFESIONALES CON EL TIPO CONSULTA SELECCIONADA CON TURNOS DEL DIA 
			
			
			$fechacita=date('Y-m-d');
			$hora = date('H:i');
			$control=true;
			$condicion=">=";
			
			/////VALIDACION NECESARIA PARA NO ASIGNAR 2 CITAS EL MISMO DIA POR PACIENTE X DEPARTAMENTO
			while($control){
				$SQL="SELECT a.agenda_cita_id, a.hora, a.agenda_turno_id, a.sw_cantidad_pacientes_asignados, a.sw_estado, b.fecha_turno, b.tipo_id_profesional, b.profesional_id, b.tipo_consulta_id
					FROM agenda_turnos AS b, agenda_citas as a 
					WHERE 
					b.fecha_turno::date ".$condicion." '".$fechacita."'::date
					--AND a.hora > '".date('H:i')."'    
					AND b.tipo_consulta_id = ".$_SESSION['AsignacionCitas']['cita']."
					AND a.agenda_turno_id=b.agenda_turno_id
					AND a.sw_estado <> '3' 
					GROUP BY a.hora,
					a.agenda_cita_id
					, a.agenda_turno_id
					, a.sw_cantidad_pacientes_asignados
					, a.sw_estado
					, b.fecha_turno
					, b.tipo_id_profesional,
					b.profesional_id, b.tipo_consulta_id
					ORDER BY b.fecha_turno ASC
					
				";
				$rst = $dbconn->Execute($SQL);
			
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al traer informacion de las tablas agenda_turnos,agenda_citas.";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
					return false;
				}
				$fechacita=$rst->fields[5];
				if($this->PacienteFechaTurno($_REQUEST['TipoId'],$_REQUEST['PacienteId'],$fechacita,$_SESSION['AsignacionCitas']['cita'])>0){
					$condicion=">";
					$control=true;
					$rst->Close();
				}else{
					$condicion=">=";
					$control=false;
				}
			}
			
			
			$i=0;
			
			while(!$rst->EOF)
			{
				if($fechacita == $rst->fields[5])
				{	
					$h = explode(':',$hora);
					$h1 = explode(':',$rst->fields[1]);
					
					
					if($h[0] < $h1[0]){
						$profesionales[$i][0] = $rst->fields[6];//TIPO_ID_PROFESIONAL
						$profesionales[$i][1] = $rst->fields[7];//PROFESIONAL_ID
						$profesionales[$i][2] = $rst->fields[5];//FECHA_TURNO
						$profesionales[$i][3] = $rst->fields[1];//HORA
						$profesionales[$i][4] = $rst->fields[0];//AGENDA_CITA_ID
						$profesionales[$i][5] = $rst->fields[8];//TIPO_CONSULTA _ID
						$profesionales[$i][6] = $rst->fields[3];//CANTIDAD PACIENTES ASIGNADOS
						$profesionales[$i][7] = $rst->fields[2];//TURNO_ID
						//$rst->MoveNext();
						$i++;
					}
					$rst->MoveNext();
				}
				else
				{
					$profesionales[$i][0] = $rst->fields[6];//TIPO_ID_PROFESIONAL
					$profesionales[$i][1] = $rst->fields[7];//PROFESIONAL_ID
					$profesionales[$i][2] = $rst->fields[5];//FECHA_TURNO
					$profesionales[$i][3] = $rst->fields[1];//HORA
					$profesionales[$i][4] = $rst->fields[0];//AGENDA_CITA_ID
					$profesionales[$i][5] = $rst->fields[8];//TIPO_CONSULTA _ID
					$profesionales[$i][6] = $rst->fields[3];//CANTIDAD PACIENTES ASIGNADOS
					$profesionales[$i][7] = $rst->fields[2];//TURNO_ID
					$rst->MoveNext();
					$i++;
				}	
			}
			
			$cnt=$i;
			$rst->Close();
			
			$profesionales = $this->OrganizarTurnosMinPaciente($profesionales,$cnt);
			
			//echo '<pre>';print_r($profesionales);
			
			$asignada=false;
			$i=0;
			$fechapacientenuevo=$profesionales[$i][2];
			while(!$asignada && $i<$cnt){
			
				
				//////////validacion de paciente nuevo para que sea ingresado en 
				//////////if($fechapacientenuevo==$profesionales[$i][2]){////////1
				if(!$this->CantidadPacienteNuevoTurno($profesionales[$i][4],$_SESSION['AsignacionCitas']['cita']) || ($_REQUEST['TipoCita']!='01')){
					//AQUI ES DONDE SE HACE LA VALLIDACION DE LAS CANTIDADES DE PACIENTES POR TIPO PLAN
					//CON AYUDA DE LA FUNCION GetCitasTiposPlanes_ProfesionalDpto($TipoDocumento,$DocumentoId,$Departamento)
// 					
					//SELECCION DE CANTIDADES DE PACIENTES POR TIPO PLAN
					$departamento = $this->GetDepartamento($profesionales[$i][5]);
					$consulta=$this->GetCitasTiposPlanes_ProfesionalDpto($profesionales[$i][0],$profesionales[$i][1],$departamento);
					$TipoPlanPaciente = $this->GetTipoPlan($_SESSION['AsignacionCitas']['Responsable']);
					//CANTIDAD MAXIMA DE PACIENTES A ATENDER POR EL PLAN DE LA NUEVA CITA
					$TotalPacientesPlan=$consulta[$TipoPlanPaciente];
					
					if($this->CantidadCitasAsignadasPlan($profesionales[$i][4],$TipoPlanPaciente)<$TotalPacientesPlan){
						
						///////////////////////////TARIFARIOS
						$sql="SELECT a.tarifario_id, a.cargo,b.descripcion 
								FROM tarifarios_equivalencias as a, tarifarios_detalle as b, plan_tarifario as c 
								WHERE cargo_base='".$_SESSION['AsignacionCitas']['cargo_cups']."' 
								AND a.tarifario_id=b.tarifario_id 
								AND a.cargo=b.cargo 
								AND b.grupo_tarifario_id=c.grupo_tarifario_id 
								AND b.subgrupo_tarifario_id=c.subgrupo_tarifario_id 
								AND c.plan_id=".$_SESSION['AsignacionCitas']['Responsable']." 
								AND b.tarifario_id=c.tarifario_id;";
						$result = $dbconn->Execute($sql);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB E1: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
							return false;
						}
						$tarifario = $result->fields[0];
						$cargo     = $result->fields[1];
						$_SESSION['AsignacionCitas']['tarifario']=$tarifario;
						$_SESSION['AsignacionCitas']['cargo']=$cargo;
						$_SESSION['AsignacionCitas']['descripcioncargo']=$result->fields[2];
						$_SESSION['AsignacionCitas']['cantidad']=1;
						$_SESSION['AsignacionCitas']['PrimerNombre']    = $_REQUEST['PrimerNombre'];
						$_SESSION['AsignacionCitas']['SegundoNombre']   = $_REQUEST['SegundoNombre'];
						$_SESSION['AsignacionCitas']['PrimerApellido']  = $_REQUEST['PrimerApellido'];
						$_SESSION['AsignacionCitas']['SegundoApellido'] = $_REQUEST['SegundoApellido'];
						///////////////////////////FIN TARIFARIOS
						
						$sql="SELECT NEXTVAL('agenda_citas_asignadas_agenda_cita_asignada_id_seq');";
						$result8 = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB i: " . $dbconn->ErrorMsg();
							return false;
						}
						if(!$result8->EOF){
							$agenda_cita_asignada_id=$result8->fields[0];
							$result8->Close();
						}
						///////////////////////////////////////////////////
						$observacion = "ASIGNACION DE CITA AUTOMATICA";
						
						$sql="INSERT INTO agenda_citas_asignadas
							(
								agenda_cita_asignada_id,
								agenda_cita_id,
								paciente_id,
								tipo_id_paciente,
								tipo_cita,
								plan_id,
								cargo_cita,
								observacion,
								usuario_id,
								agenda_cita_id_padre,
								fecha_registro,
								sw_prioritaria
							)
							values
							(
								".$agenda_cita_asignada_id.",
								".$profesionales[$i][4].",
								'".$_REQUEST['PacienteId']."',
								'".$_REQUEST['TipoId']."',
								'".$_REQUEST['TipoCita']."',
								'".$_SESSION['AsignacionCitas']['Responsable']."',
								'".$_SESSION['AsignacionCitas']['cargo_cups']."',
								'".$observacion."',
								".UserGetUID().",
								".$profesionales[$i][4].",
								'".date("Y-m-d H:i:s")."',
								'0'
							);";

						$rst = $dbconn->Execute($sql);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar informaci� en la tabla agenda_citas_asignadas.";
							$this->mensajeDeError = "Error DB E2: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
							return false;
						}
						///////////////////////////////////////////////////////////////////////
						
						$sql="SELECT NEXTVAL('hc_os_solicitudes_hc_os_solicitud_id_seq');";
						$result1 = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB E3: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
							return false;
						}

						$sql="INSERT INTO hc_os_solicitudes 
									(
										hc_os_solicitud_id, 
										cargo, 
										plan_id, 
										os_tipo_solicitud_id, 
										sw_estado, 
										paciente_id, 
										tipo_id_paciente
									) 
									VALUES
									(
										".$result1->fields[0].",
										'".$_SESSION['AsignacionCitas']['cargo_cups']."',
										".$_SESSION['AsignacionCitas']['Responsable'].",
										'CIT',
										'0',
										'".$_SESSION['AsignacionCitas']['Documento']."',
										'".$_SESSION['AsignacionCitas']['TipoDocumento']."'
									);";
						$r = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB E4: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
							return false;
						}
						$sql="INSERT INTO hc_os_solicitudes_citas 
									(
										hc_os_solicitud_id,
										tipo_consulta_id
									)
									VALUES
									(
										".$result1->fields[0].",
										".$_SESSION['AsignacionCitas']['cita']."
									);";
						$r = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB E5: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
							return false;
						}

						$sql="INSERT INTO hc_os_autorizaciones
									(
										hc_os_solicitud_id,
										autorizacion_int,
										autorizacion_ext
									) values 
									(
										".$result1->fields[0].",
										".$_SESSION['AsignacionCitas']['NumAutorizacion'].",
										".$_SESSION['AsignacionCitas']['NumAutorizacion']."
									);";
						$r = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB E6: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
							return false;
						}
						
						$sql="SELECT NEXTVAL('os_ordenes_servicios_orden_servicio_id_seq');";
						$result2 = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB E6: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
							return false;
						}
						$evento = "NULL";
						$_SESSION['AsignacionCitas']['servicio'] = $this->BusquedaServicio($_SESSION['AsignacionCitas']['departamento']);	
						$sql = "INSERT INTO os_ordenes_servicios (
									orden_servicio_id, 
									autorizacion_int, 
									autorizacion_ext, 
									plan_id, 
									tipo_afiliado_id, 
									rango, 
									semanas_cotizadas, 
									servicio, 
									tipo_id_paciente, 
									paciente_id, 
									usuario_id,
									fecha_vencimiento, --JPS
									fecha_registro,
									evento_soat,
									departamento) 
								VALUES (".$result2->fields[0].", 
									".$_SESSION['AsignacionCitas']['NumAutorizacion'].", 
									".$_SESSION['AsignacionCitas']['NumAutorizacion'].", 
									".$_SESSION['AsignacionCitas']['Responsable'].", 
									'".$_SESSION['AsignacionCitas']['tipo_afiliado_id']."', 
									'".$_SESSION['AsignacionCitas']['rango']."', 
									'".$_SESSION['AsignacionCitas']['semanas']."', 
									'".$_SESSION['AsignacionCitas']['servicio']."', 
									'".$_SESSION['AsignacionCitas']['TipoDocumento']."', 
									'".$_SESSION['AsignacionCitas']['Documento']."', 
									".UserGetUID().",
									'".$profesionales[$i][2]."', --JPS
									'".date("Y-m-d H:i:s")."',
									".$evento.",
									'".$departamento."'
									);";
						
						$r = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB E7: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
							return false;
						}
						if(!empty($_SESSION['EMPLEADOR']))
						{
							$query = "INSERT INTO os_ordenes_servicios_empleadores
									(
										orden_servicio_id,
										tipo_id_empleador,
										empleador_id
									)
									VALUES
									(
										".$result2->fields[0].",
										'".$_SESSION['EMPLEADOR']['tipo_empleador']."',
										'".$_SESSION['EMPLEADOR']['id_empleador']."'
									)";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error INSERT INTO os_ordenes_servicios_empleadores ";
								$this->mensajeDeError = "Error DB E8 : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
								return false;
							}
						}
						
						$sql="SELECT NEXTVAL('os_maestro_numero_orden_id_seq');";
						$result4 = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB E9 : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
							return false;
						}
						/*if($profesionales[$i][2] < date("Y-m-d H:m"))
						{
							$fechaTurno =date("Y-m-d H:m",mktime(24,24,24,date("m"),date("d"),date("Y")));
						}*/
						$FechaTurno = $profesionales[$i][2];
						$HoraTurno = $profesionales[$i][3];
						$_SESSION['AsignacionCitas']['numero_orden_id']=$result4->fields[0];
						if(empty($_SESSION['CumplirCita']['cita']))
						{
							$sql="INSERT INTO os_maestro
								(
									numero_orden_id, 
									orden_servicio_id, 
									fecha_vencimiento, 
									hc_os_solicitud_id, 
									cargo_cups
								)
								VALUES
								(
									".$result4->fields[0].",
									".$result2->fields[0].",
									'".$FechaTurno."',
									".$result1->fields[0].",
									'".$_SESSION['AsignacionCitas']['cargo_cups']."'
								);";
						}
						else
						{
							$sql="INSERT INTO os_maestro
								(
									numero_orden_id,
									orden_servicio_id,
									fecha_vencimiento,
									hc_os_solicitud_id,
									cargo_cups,
									sw_estado
								)
								VALUES
								(
									".$result4->fields[0].",
									".$result2->fields[0].",
									'".$FechaTurno."',
									".$result1->fields[0].",
									'".$_SESSION['AsignacionCitas']['cargo_cups']."',
									'5'
								);";
						}
						$r = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB E10: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
							return false;
						}
						
						$sql="INSERT INTO os_internas
									(
										numero_orden_id,
										cargo,
										departamento
									)
									VALUES
									(
										".$result4->fields[0].",
										'".$_SESSION['AsignacionCitas']['cargo_cups']."',
										'".$_SESSION['AsignacionCitas']['departamento']."'
									);";
						$r = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB E11: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
							return false;
						}
						$sql="INSERT INTO os_cruce_citas
									(
										numero_orden_id,
										agenda_cita_asignada_id
									)
									VALUES
									(
										".$result4->fields[0].",
										 $agenda_cita_asignada_id
									 );";
						$r = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB E12: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
							return false;
						}
						$sql="SELECT NEXTVAL('os_maestro_cargos_os_maestro_cargos_id_seq');";
						$result5 = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB E13: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
							return false;
						}
						
						$_SESSION['AsignacionCitas']['os_maestro_cargos_id']=$result5->fields[0];
						$sql="INSERT into os_maestro_cargos 
									(
										os_maestro_cargos_id,
										numero_orden_id,
										tarifario_id,
										cargo
									)
									VALUES
									(
										".$result5->fields[0].",
										".$result4->fields[0].",
										'$tarifario',
										'$cargo'
									);";
						$r = $dbconn->Execute($sql);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB E14: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
							return false;
						}
						
						$asignada=true;
						$errorcnt="";
					}
					else{
						$errorcnt="LA CITA NO SE ASIGNO POR LA CANTIDAD DE PACIENTES POR TIPO PLAN. VERIFIQUE LA PARAMETRIZACION DE CITAS";
						$i++;
					}
				}/////FINIF
				else{
					//if($_REQUEST['TipoCita'] != '01')
					$i++;
					
				}
				//////}//////1
				/*else{
				
				OJO VALIDACION DE PACIENTE NUEVO DONDE NO HAYA CUPO DISPONIBLE
				
				}*/
			
			}
			if($asignada){
				$msg = "ASIGNACION DE CITA AUTOMATICA COMPLETA.";
				$SQL="SELECT nombre FROM profesionales WHERE tipo_id_tercero='".$profesionales[$i][0]."' AND tercero_id='".$profesionales[$i][1]."';";
				$r = $dbconn->Execute($SQL);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB E15: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
					return false;
				}
				$nombreprofesional = $r->fields[0];
				$_SESSION['AsignacionCitas']['nompro'] = $nombreprofesional;
				if(ModuloGetVar('app','AgendaMedica','NoManejoCaja') == 1)
				{
					$this->frmError["MensajeError"]="SE ASIGNO LA CITA<br>PARA EL DIA ".$FechaTurno."-".$HoraTurno." CON EL PROFESIONAL $nombreprofesional";
					echo $this->frmError["MensajeError"];
					$_REQUEST='';
					$_REQUEST['metodo'] ='EscogerBusqueda';
					$_REQUEST['modulo'] ='AgendaMedica';
					$this->EscogerBusqueda();
					return true;
				}
				else
				{
					$_SESSION['AsignacionCitas']['hora'] = $FechaTurno.":".$HoraTurno;
					$msg = "<br>PARA EL DIA ".$FechaTurno."-".$HoraTurno." CON EL PREFESIONAL $nombreprofesional<br>";
					$this->PantallaFinal('',$msg);
				}
			
			}
			else{
				
				$msg = "ASIGNACION DE CITA AUTOMATICA INCOMCOMPLETA. E1:".$errorcnt;
				$this->PantallaFinalError($msg);
			}	
		}
		else{
			$msg = "NO SE ACTUALIZARON LOS DATOS DEL PACIENTE.";
			$this->FormaAsignacionCitaAutomatica($msg);
	
		}	
		
		return true;
	}
	function GetDepartamento($tipo_consulta_id)
	{
		list($dbconn) = GetDBconn();
		$SQL="SELECT * FROM tipos_consulta WHERE tipo_consulta_id = '".$tipo_consulta_id."'";
		$rst = $dbconn->Execute($SQL);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al traer informaci0n de la tabla tipos_consulta.";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
			return false;
		}else{
            		$datos=$rst->RecordCount();
            		if($datos){
        			$var= $rst->fields[1];
				$rst->Close();
				return $var;
			}
        	}
	}
	
	function GetCitasTiposPlanes_ProfesionalDpto($TipoDocumento,$DocumentoId,$Departamento)
	{
        	list($dbconn) = GetDBconn();
        	
		$query="SELECT * FROM citas_tipo_plan WHERE tipo_id_tercero='".$TipoDocumento."' AND tercero_id='".$DocumentoId."' AND departamento_id='".$Departamento."';";
        	$result = $dbconn->Execute($query);
        	if ($dbconn->ErrorNo() != 0) {
           		$this->error = "Error al Cargar el Modulo[citas_tipo_plan]";
            		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';;
            		return false;
        	}else{
            		$datos=$result->RecordCount();
			$i=0;
			if($datos){
				while (!$result->EOF)
				{
					$vars[$result->fields[1]]=$result->fields[2];
					$i++;
					$result->MoveNext();
				}
			}	
			else{
				return false;	
            		}
        	}
		$result->Close();
        	return $vars;
        	
        	
	}	
	//////////////////////////////////////
	/////////////******************FIN METODOS CLINICA REHABILITACON
	///////////////////////////////
	function getValues($subject=Array())
	{
		$max = $min = $dat = array();
		$dat[maximo][0] = $subject[0][0];
		$dat[maximo][1] = $subject[0][1];
		$dat[maximo][2] = $subject[0][2];
		
		$dat[minimo][0] = $subject[0][0];
		$dat[minimo][1] = $subject[0][1];
		$dat[minimo][2] = $subject[0][2];
		
		foreach($subject AS $i => $v)
		{
			if($v[1] > $dat[maximo][1])
			{
				$dat[maximo][0] = $v[0];
				$dat[maximo][1] = $v[1];
				$dat[maximo][2] = $v[2];
			}
			if($v[1] < $dat[minimo][1])
			{
				$dat[minimo][0] = $v[0];
				$dat[minimo][1] = $v[1];   
				$dat[minimo][2] = $v[2];   
			}
		}
		return $dat;
	}
    /**
    *
    */
    function ActualizarDatosPaciente(&$dbconn)
    {
			$sql="SELECT COUNT(*) FROM pacientes 
						WHERE paciente_id='".$_REQUEST[PacienteId]."'
						and tipo_id_paciente='".$_REQUEST[TipoId]."';";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
      {
						$this->error = "Error al Cargar el Modulo";
						$this->fileError = __FILE__;
						$this->lineError = __LINE__;						
						$this->mensajeDeError = "Error DB a2: " . $dbconn->ErrorMsg();
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						return false;
			}
			if($result->fields[0] > 0)
      {
					$fec_nac = explode("/",$_REQUEST['FechaNacimiento']);
					$fec_nac = $fec_nac[2]."-".$fec_nac[1]."-".$fec_nac[0];
					$sql="UPDATE pacientes SET residencia_direccion='".$_REQUEST['Direccion']."',
								residencia_telefono='".$_REQUEST['Telefono']."',
								fecha_nacimiento = '".$fec_nac."'
								WHERE paciente_id='".$_REQUEST[PacienteId]."'
								AND tipo_id_paciente='".$_REQUEST[TipoId]."';";
					$result = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->fileError = __FILE__;
						$this->lineError = __LINE__;						
						$this->mensajeDeError = "Error DB a2: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						return false;
					}
			}
      else
      {
					$sql="INSERT INTO pacientes(paciente_id, tipo_id_paciente, primer_apellido, segundo_apellido, primer_nombre,
								segundo_nombre, residencia_direccion, residencia_telefono, sexo_id, fecha_registro, tipo_pais_id,
								tipo_dpto_id, tipo_mpio_id, usuario_id, zona_residencia,ocupacion_id,fecha_nacimiento)
								VALUES ('".$_REQUEST[PacienteId]."', '".$_REQUEST[TipoId]."', '".$_REQUEST['PrimerApellido']."', '".$_REQUEST['SegundoApellido']."',
								'".$_REQUEST['PrimerNombre']."', '".$_REQUEST['SegundoNombre']."', '".$_REQUEST['Direccion']."',
								'".$_REQUEST['Telefono']."', '".$_REQUEST['Sexo']."', '".date("Y-m-d H:i:s")."', '".GetVarConfigAplication('DefaultPais')."', '".GetVarConfigAplication('DefaultDpto')."', '".GetVarConfigAplication('DefaultMpio')."',
								".$_SESSION['SYSTEM_USUARIO_ID'].", '".GetVarConfigAplication('DefaultZona')."',NULL,'".$_REQUEST['FechaNacimiento']."');";
					$result = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB a3: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
						$this->fileError = __FILE__;
						$this->lineError = __LINE__;						
						$sql="ROLLBACK";
						$dbconn->Execute($sql);
						return false;
					}
					$query = "INSERT INTO historias_clinicas( tipo_id_paciente,
																											paciente_id,
																											historia_numero,
																											historia_prefijo,
																											fecha_creacion)
										VALUES ('".$_REQUEST[TipoId]."','".$_REQUEST[PacienteId]."','','','now()')";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB a4: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
						$this->fileError = __FILE__;
						$this->lineError = __LINE__;						
						$sql="ROLLBACK";
						$dbconn->Execute($query);
						return false;
					}
					//OJO: REVISAR ESTO SI HAY ERROR
					//$_SESSION['AsignacionCitas']['Existe']=true;
			}
			return true;
		}
    /**
    * Busca los planes existentes
    *
    * @return array
    */
    function ObtenerResponsables()
    { 
      //$this->debug=true;
      $sql  = "SELECT DISTINCT plan_id,";
      $sql .= "       plan_descripcion,";
      $sql .= "       tercero_id,";
      $sql .= "       tipo_tercero_id ";
      $sql .= "FROM   planes ";
      $sql .= "WHERE  fecha_final >= NOW() "; 
      $sql .= "AND    estado = '1' "; 
      $sql .= "AND    fecha_inicio <= NOW() ";
      $sql .= "AND    empresa_id = '".$_SESSION['AsignacionCitas']['empresa']."' ";
      $sql .= "ORDER BY plan_descripcion ";

      if(!$result = $this->ConexionBaseDatos($sql,__LINE__))
        return false;

      $datos = array();
      while (!$result->EOF) 
      {
        $datos[] = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
      }
      $result->Close();
      return $datos;
    }
    
     function ObtenerPermisosConsulta()
    { 
    	
      //$this->debug=true;
      $sql  = "SELECT sw_vertodosplanes ";
      $sql .= "FROM   userpermisos_tipos_consulta ";
      $sql .= "WHERE    tipo_consulta_id = ".$_SESSION['AsignacionCitas']['cita']." ";
      $sql .= "AND    usuario_id = ".UserGetUID()." ";
     // echo $sql;
    //  die();
      
      if(!$result = $this->ConexionBaseDatos($sql,__LINE__))
        return false;

      $datos = array();
      while (!$result->EOF) 
      {
        $datos[] = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
      }
      $result->Close();
      
      if(count($datos)>0)
      {
      	return $datos[0]['sw_vertodosplanes'];
      }
      
      return 0;
    }
    
    
    /**
    * Busca los planes que tiene permiso
    *
    * @return array
    */
    function ObtenerResponsablesPermisos()
    { 
    	
      //$this->debug=true;
      $sql  = "SELECT DISTINCT a.plan_id, ";
      $sql .= "       a.plan_descripcion, ";
      $sql .= "       a.tercero_id, ";
      $sql .= "       a.tipo_tercero_id ";
      $sql .= "FROM   planes a , ";
      $sql .= "       todoslosplanes b ";
      $sql .= "WHERE  fecha_final >= NOW() "; 
      $sql .= "AND    estado = '1' ";
      $sql .= "AND    a.plan_id=b.plan_id ";
      $sql .= "AND    fecha_inicio <= NOW() ";
      $sql .= "AND    a.empresa_id = '".$_SESSION['AsignacionCitas']['empresa']."' ";
      $sql .= "AND    b.usuario_id = ".UserGetUID()." ";
      $sql .= "ORDER BY plan_descripcion ";
      
      if(!$result = $this->ConexionBaseDatos($sql,__LINE__))
        return false;

      $datos = array();
      while (!$result->EOF) 
      {
        $datos[] = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
      }
      $result->Close();
      return $datos;
    }
    
		/**
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la consulta 
		* sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*/
		function ConexionBaseDatos($sql,$linea)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg()."<br>Linea ".$linea;
				return false;
			}
			return $rst;
		}

        /**
	*@method Despliegue de la oportunidad
	*@param $fecha Fecha de la oportunidad
	*@param $departamento Departamento
	*@param $especialidad Especialidad
	*@param $profesional M�dico especialista
	*/
	function DesplegarOportunidad($fecha, $departamento, $especialidad, $profesional = null, $tipo_id_profesional = null)
	{
                $oportunidad = 0;
                $sql  = "SELECT SUM(oportunidad) AS disponibles ";
                $sql .= " FROM vw_reporte_oportunidad vw ";
                $sql .= " INNER JOIN tipos_registro tr ON tr.tipo_registro = vw.tipo_registro ";
                $sql .= " WHERE CAST (fecha_turno AS DATE ) = '".$fecha."' ";
                $sql .= " AND especialidad_id = '".$especialidad."' ";
                $sql .= " AND departamento_id like '%".$departamento."%' ";
                if (!empty($profesional))
                {
                    $sql .= " AND profesional_id like '%".$profesional."%' ";
                }

                list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($sql);
		$i=0;

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return 0;
		}
		else
		{
			$oportunidad=$result->fields[0];
		}
                return $oportunidad;
	}

        /**
	*@method Consulta la Especialidad por Tipo de Consulta
	*@param $tipo_consulta Tipo de la consulta
	*/
	function EspecialidadTipoConsulta($tipo_consulta)
	{
		$especialidad = 0;
		$sql  = "SELECT especialidad FROM tipos_consulta ";
                $sql .= " WHERE tipo_consulta_id ='".$tipo_consulta."';";
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return 0;
		}
		else
		{
			$especialidad=$result->fields[0];
		}

		return $especialidad;
	}
  }
?>