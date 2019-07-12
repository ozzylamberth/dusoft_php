<?
//ESTE ES EL QUE VA A QUED
/**
* Submodulo de Reserva de Sangre.
*
* Submodulo para manejar la reserva y/o cruzada de sangre.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_TransfusionSanguinea.php,v 1.6 2006/12/19 21:00:15 jgomez Exp $
*/


class TransfusionSanguinea extends hc_classModules
{

//clzc
	function TransfusionSanguinea(){
	  $this->limit=GetLimitBrowser();
		return true;
	}

/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

// 	function GetVersion(){
// 		$informacion=array(
// 		'version'=>'1',
// 		'subversion'=>'0',
// 		'revision'=>'0',
// 		'fecha'=>'01/27/2005',
// 		'autor'=>'LORENA ARAGON G.',
// 		'descripcion_cambio' => '',
// 		'requiere_sql' => false,
// 		'requerimientos_adicionales' => '',
// 		'version_kernel' => '1.0'
// 		);
// 		return $informacion;
// 	}

/**
* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/

	function GetEstado(){
    return true;
	}

/**
* Esta metodo captura los datos de la impresión de la Historia Clinica.
* @access private
* @return text Datos HTML de la pantalla.
*/

	function GetReporte_Html(){
		$imprimir=$this->frmHistoria();
		if($imprimir==false){
			return true;
		}
		return $imprimir;
	}

/**
* GetForma - Recibe la accion y la direcciona a la funcion correspondiente
*
* @return text
*/

	//clzc
	function GetForma(){

	  $pfj=$this->frmPrefijo;
    if(empty($_REQUEST["accion".$pfj])){
	    $this->frmForma();
		}elseif($_REQUEST["accion".$pfj]=='InsertarDatosTransfusion'){
      $this->InsertarDatosTransfusion();
			$this->frmForma();
		}elseif($_REQUEST["accion".$pfj]=='GuardarFechaFinalTransfusion'){
		  $this->InsertarFechaFinTransfusion();
      $this->frmForma();
		}elseif($_REQUEST["accion".$pfj]=='CallFrmInsertarReaccionAdversa'){
		  if($_REQUEST['SaveReaccion'.$pfj]){
        $this->InsertarReaccionAdversa();
				$this->FrmInsertarReaccionAdversa($_REQUEST['datos'.$pfj]);
			}elseif($_REQUEST['BUSCARDIAGNOSTICO'.$pfj]){

				 $_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['fecha_inicio_reaccion']=$_REQUEST['fecha_inicio_reaccion'.$pfj];
				 $_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['hora_inicio_reaccion']=$_REQUEST['hora_inicio_reaccion'.$pfj];
				 $_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['minutos_inicio_reaccion']=$_REQUEST['minutos_inicio_reaccion'.$pfj];
				 $_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['fecha_suspension_transfusion']=$_REQUEST['fecha_suspension_transfusion'.$pfj];
				 $_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['hora_suspension_transfusion']=$_REQUEST['hora_suspension_transfusion'.$pfj];
				 $_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['minutos_suspension_transfusion']=$_REQUEST['minutos_suspension_transfusion'.$pfj];
				 $_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['fecha_notificacion_medico']=$_REQUEST['fecha_notificacion_medico'.$pfj];
				 $_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['hora_notificacion_medico']=$_REQUEST['hora_notificacion_medico'.$pfj];
				 $_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['minutos_notificacion_medico']=$_REQUEST['minutos_notificacion_medico'.$pfj];
				 $_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['liquidos']=$_REQUEST['liquidos'.$pfj];
				 $_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['reaccionAdversa']=$_REQUEST['reaccionAdversa'.$pfj];
				 $_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['sel']=$_REQUEST['sel'.$pfj];
				 $_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['datos']=$_REQUEST['datos'.$pfj];
        $this->FrmBusquedaDiagnosticos();

			}else{
			  if($_REQUEST['codigoParaEliminar'.$pfj]){
				  unset($_SESSION['REACCIONES_TRANSFUSIONALES'.$pfj]['DIAGNOSTICOS'][$_REQUEST['codigoParaEliminar'.$pfj]]);
				}
        $this->FrmInsertarReaccionAdversa($_REQUEST['datos'.$pfj]);
			}
		}elseif($_REQUEST["accion".$pfj]=='CallFrmIngresarHemoclasificacionPaciente'){
		  if($_REQUEST['SaveHemoclasify'.$pfj]){
        $this->IngresarHemoclasificacion();
			}
      $this->FrmIngresarHemoclasificacionPaciente($_REQUEST['datos'.$pfj]);
		}elseif($_REQUEST["accion".$pfj]=='ConfirmarComponentesTransfusion'){
      if($this->RealizarConfirmacionComponentes()==true){
				$this->frmForma();
			}
		}elseif($_REQUEST["accion".$pfj]=='ResistroRecepcionBolsa'){
			if($_REQUEST['guardarDatos'.$pfj]){
				if($this->RegistrarDatosRecepcionBolsa()==true){
					$this->frmForma();
				}
			}elseif($_REQUEST['cancelarDatos'.$pfj]){
				$this->frmForma();
			}else{
				$this->FormaRegistroRecepcionBolsa($_REQUEST['IngresoId'.$pfj],$_REQUEST['alicuota'.$pfj],$_REQUEST['bolsaId'.$pfj]);
			}
		}elseif($_REQUEST["accion".$pfj]=='SeleccionDiagnostico'){
		  if($_REQUEST['Buscar'.$pfj]){
        $this->FrmBusquedaDiagnosticos($_REQUEST['codigoDes'.$pfj],$_REQUEST['descripcionDes'.$pfj]);
			}
			if($_REQUEST['salir'.$pfj] || $_REQUEST['seleccion'.$pfj]){
			   $_REQUEST['fecha_inicio_reaccion'.$pfj]=$_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['fecha_inicio_reaccion'];
				 $_REQUEST['hora_inicio_reaccion'.$pfj]=$_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['hora_inicio_reaccion'];
				 $_REQUEST['minutos_inicio_reaccion'.$pfj]=$_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['minutos_inicio_reaccion'];
				 $_REQUEST['fecha_suspension_transfusion'.$pfj]=$_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['fecha_suspension_transfusion'];
				 $_REQUEST['hora_suspension_transfusion'.$pfj]=$_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['hora_suspension_transfusion'];
				 $_REQUEST['minutos_suspension_transfusion'.$pfj]=$_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['minutos_suspension_transfusion'];
				 $_REQUEST['fecha_notificacion_medico'.$pfj]=$_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['fecha_notificacion_medico'];
				 $_REQUEST['hora_notificacion_medico'.$pfj]=$_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['hora_notificacion_medico'];
				 $_REQUEST['minutos_notificacion_medico'.$pfj]=$_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['minutos_notificacion_medico'];
				 $_REQUEST['liquidos'.$pfj]=$_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['liquidos'];
				 $_REQUEST['reaccionAdversa'.$pfj]=$_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['reaccionAdversa'];
				 $_REQUEST['sel'.$pfj]=$_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['sel'];
				 $datos=$_SESSION['REACCION_TRANSFUSIONAL'.$pfj]['datos'];
				 unset($_SESSION['REACCION_TRANSFUSIONAL'.$pfj]);
				 if($_REQUEST['seleccion'.$pfj]){
          $_SESSION['REACCIONES_TRANSFUSIONALES'.$pfj]['DIAGNOSTICOS'][$_REQUEST['codigoDiagnostico'.$pfj]]=$_REQUEST['nombreDiagnostico'.$pfj];
				 }
        $this->FrmInsertarReaccionAdversa($datos);
			}
		}elseif($_REQUEST["accion".$pfj]=='CallFrmConsultarReaccionAdversa'){
      $this->FrmConsultarReaccionAdversa($_REQUEST['datos'.$pfj],$_REQUEST['reaccion_adversa'.$pfj]);
		}
		return $this->salida;
	}

/**
* GetConsulta - Llama la funcion que muestra los registros de transfusiones insertados en el ingreso
*
* @return text
*/

	function GetConsulta(){
    $accion='accion'.$pfj;
		if(empty($_REQUEST[$accion])){
			$this->frmConsulta();
		}
		return $this->salida;
	}

/**
* TraerComponentes - Consulta los tipos de componentes sanguineos de la base de datos
*
* @return array
*/

	 function TraerComponentes(){

		list($dbconn) = GetDBconn();
		$query = "SELECT * FROM  hc_tipos_componentes ORDER BY hc_tipo_componente asc";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al traer los componentes";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				while(!$result->EOF){
					$vars[]= $result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
		return $vars;
	}

/**
* GetGruposSanguineos - el grupo sanguineo y el rh de los tipos de componentes
*
* @return array
*/

	function GetGruposSanguineos(){
    list($dbconn) = GetDBconn();
		$query = "SELECT grupo_sanguineo, rh
							FROM hc_tipos_sanguineos";
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener los tipos de grupos sanguineos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}else{
			while(!$result->EOF){
				$grupoSanguineos[] = $result->FetchRow();
			}
			return $grupoSanguineos;
		}
		return true;
	}

/**
* InsertarDatosTransfusion - funsion que se encarga de insertar los datos de la trasfusion a la base de datos
*
* @return boolean
*/

	function InsertarDatosTransfusion(){

		$pfj=$this->frmPrefijo;
    if(empty($_REQUEST['cantBolsas'.$pfj]) || empty($_REQUEST['numSello'.$pfj]) ||
		  empty($_REQUEST['fechaVencimiento'.$pfj]) || empty($_REQUEST['tipoSanguineo'.$pfj]) ||
			empty($_REQUEST['fechaInicio'.$pfj]) || empty($_REQUEST['HoraInicio'.$pfj]) ||
			empty($_REQUEST['MinutoInicio'.$pfj])){
			$this->frmError["MensajeError"] = "TODOS LOS CAMPOS SON OBLIGATORIOS";
			return true;
		}
		$f=explode('-',$_REQUEST['fechaVencimiento'.$pfj]);
		$fechaVencimiento=$f[2].'-'.$f[1].'-'.$f[0];
		list($grupoSanguineo,$rh) = explode(".-.",$_REQUEST['tipoSanguineo'.$pfj]);
		$f=explode('-',$_REQUEST['fechaInicio'.$pfj]);
		$fechaInicio=$f[2].'-'.$f[1].'-'.$f[0];
		list($dbconn) = GetDBconn();
		//luego valido que no existan registros a esa hora
		$query = "SELECT fecha
								FROM hc_control_transfusiones
								WHERE ingreso = '".$this->ingreso."' AND
											fecha = '".$fechaInicio." ".$_REQUEST['HoraInicio'.$pfj].":".$_REQUEST['MinutoInicio'.$pfj]."'
								ORDER BY fecha DESC";
		$dbconn->BeginTrans();
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla hc_control_transfusiones.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			$dbconn->RollbackTrans();
			return false;
		}else{
			if(!$result->EOF){
				$this->frmError["MensajeError"] = "EN LA FECHA-HORA '".$selectHora.":".$selectMinutos."' YA EXISTEN REGISTROS, ESPECIFIQUE UNA HORA DIFERENTE";
				$this->frmForma();
				return true;
			}
			if(!empty($_REQUEST['IngresoBolsaId'.$pfj])){$ingresoBolsaId="'".$_REQUEST['IngresoBolsaId'.$pfj]."'";}else{$ingresoBolsaId='NULL';}
			if(!empty($_REQUEST['numeroAlicuota'.$pfj])){$numeroAlicuota=$_REQUEST['numeroAlicuota'.$pfj];}else{$numeroAlicuota='0';}
			$query = "INSERT INTO hc_control_transfusiones(ingreso,
																										fecha,
																										numero_bolsas,
																										numero_sello_calidad,
																										fecha_vencimiento,
																										grupo_sanguineo,
																										rh,
																										fecha_final,
																										usuario,
																										hc_tipo_componente,
																										ingreso_bolsa_id,
																										numero_alicuota,
																										entidad_origen
																										)
																						VALUES ('".$this->ingreso."',
																										'".$fechaInicio." ".$_REQUEST['HoraInicio'.$pfj].":".$_REQUEST['MinutoInicio'.$pfj]."',
																										'".$_REQUEST['cantBolsas'.$pfj]."',
																										'".$_REQUEST['numSello'.$pfj]."',
																										'$fechaVencimiento 00:00:00',
																										'$grupoSanguineo',
																										'$rh',
																										NULL,
																										".UserGetUID().",
																										'".$_REQUEST['componente'.$pfj]."',
																										$ingresoBolsaId,
																										'$numeroAlicuota',
																										'".$_REQUEST['origenComponente'.$pfj]."')";//echo "<br><br>".$query;
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Error al intentar ingresar los datos <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				$dbconn->RollbackTrans();
				return false;
			}else{//ModuloGetURL('app','EstacionEnfermeria','user','CallControlesPacientes',array("control_id"=>24,"control_descripcion"=>"CONTROL DE TRANSFUSIONES","estacion"=>$datos))
				if($ingresoBolsaId!='NULL'){
				  $query="UPDATE banco_sangre_bolsas_alicuotas SET sw_estado='2' WHERE ingreso_bolsa_id=$ingresoBolsaId AND numero_alicuota='$numeroAlicuota'";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Error al intentar ingresar los datos <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						$dbconn->RollbackTrans();
						return false;
					}else{
						if(!empty($_REQUEST['numeroReserva'.$pfj])){
						  $query="SELECT a.tipo_componente_id,a.cantidad_componente
							FROM banco_sangre_reserva_detalle a
							WHERE a.solicitud_reserva_sangre_id='".$_REQUEST['numeroReserva'.$pfj]."'";
							$result = $dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0){
								$this->error = "Error al ejecutar la conexion";
								$this->mensajeDeError = "Error al intentar ingresar los datos <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								$dbconn->RollbackTrans();
								return false;
							}else{
								if($result->RecordCount()>0){
									while(!$result->EOF){
										$vars[]=$result->GetRowAssoc($toUpper=false);
										$result->MoveNext();
									}
								}
								for($i=0;$i<sizeof($vars);$i++){
									$query="SELECT CASE WHEN (SELECT count(*)
									FROM banco_sangre_entrega_bolsas a,hc_control_transfusiones b
									WHERE a.ingreso_bolsa_id=b.ingreso_bolsa_id AND a.numero_alicuota=b.numero_alicuota AND
									a.tipo_componente_id='".$vars[$i]['tipo_componente_id']."' AND a.solicitud_reserva_sangre_id='".$_REQUEST['numeroReserva'.$pfj]."')='".$vars[$i]['cantidad_componente']."' THEN 1
									ELSE '0' END";
									$result = $dbconn->Execute($query);
									if($dbconn->ErrorNo() != 0){
										$this->error = "Error al ejecutar la conexion";
										$this->mensajeDeError = "Error al intentar ingresar los datos <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
										$dbconn->RollbackTrans();
										return false;
									}else{
                    if($result->fields[0]!='1'){
										  $salir=1;
                      break;
										}
									}
								}
								if($salir!='1'){
								  $query="UPDATE banco_sangre_reserva SET sw_estado='4' WHERE solicitud_reserva_sangre_id='".$_REQUEST['numeroReserva'.$pfj]."'";
									$result = $dbconn->Execute($query);
									if($dbconn->ErrorNo() != 0){
										$this->error = "Error al ejecutar la conexion";
										$this->mensajeDeError = "Error al intentar ingresar los datos <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
										$dbconn->RollbackTrans();
										return false;
									}
								}
							}
						}
					}
				}
        unset($_REQUEST['cantBolsas'.$pfj]);
				unset($_REQUEST['numSello'.$pfj]);
				unset($_REQUEST['fechaVencimiento'.$pfj]);
				unset($_REQUEST['tipoSanguineo'.$pfj]);
				unset($_REQUEST['fechaInicio'.$pfj]);
				unset($_REQUEST['HoraInicio'.$pfj]);
				unset($_REQUEST['MinutoInicio'.$pfj]);
				unset($_REQUEST['componente'.$pfj]);
				unset($_REQUEST['IngresoBolsaId'.$pfj]);
				unset($_REQUEST['numeroAlicuota'.$pfj]);
				unset($_REQUEST['origenComponente'.$pfj]);
				unset($_REQUEST['origen']);
			}
			$dbconn->CommitTrans();
			 $this->RegistrarSubmodulo($this->GetVersion());            
      return true;
		}
	}

/**
* GetTransfusiones - funsion que obtiene de la base de datos las transfusiones realizadas en el mismo ingreso
*
* @return array
*/

		function GetTransfusiones(){
			$query = "SELECT a.*,b.componente,c.hc_trasfucion_id as reaccion_adversa
								FROM hc_control_transfusiones a
								LEFT JOIN hc_control_transfusiones_notas_reaccion_adversas c ON (a.ingreso=c.ingreso AND a.fecha=c.fecha),
								hc_tipos_componentes b WHERE
								a.ingreso='".$this->ingreso."' AND a.hc_tipo_componente=b.hc_tipo_componente
								ORDER BY fecha DESC";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Error al intentar obtener los registros de transfusiones del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}else{
				if($result->EOF){
					return "ShowMensaje";
				}else{
					while($data = $resultado->FetchRow()) {
						$transfusionesPaciente[] = $data;
					}
					return $transfusionesPaciente;
				}
			}
		}//GetTransfusiones

/**
* GetDatosUsuarioSistema - funsion que obtiene de la base de datos los datos del usuario
*
* @return array
*/
		function GetDatosUsuarioSistema($usuario){
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
* InsertarFechaFinTransfusion - Actualiza el registro de una transfusion para ponerle la fecha de finalización de la misma
*
* @return boolean
*/
	function InsertarFechaFinTransfusion(){
	  $pfj=$this->frmPrefijo;
		$indice = $_REQUEST['indice'.$pfj];
		if(empty($_REQUEST['fechaFin'.$indice.$pfj]) || empty($_REQUEST['Horas'.$indice.$pfj]) || empty($_REQUEST['Minutos'.$indice.$pfj]) || empty($_REQUEST['fechaInicio'.$indice.$pfj])){
			$this->frmError["MensajeError"] = "DEBE INGRESAR TODOS LOS DATOS PARA LA FECHA FINAL DE LA TRANSFUSION";
			return true;
		}
		$f=explode('-',$_REQUEST['fechaFin'.$indice.$pfj]);
		$fechaFin=$f[2].'-'.$f[1].'-'.$f[0];
	  $query = "UPDATE hc_control_transfusiones
							SET fecha_final = '".$fechaFin." ".$_REQUEST['Horas'.$indice.$pfj].":".$_REQUEST['Minutos'.$indice.$pfj]."'
							WHERE ingreso = ".$this->ingreso." AND
										fecha = '".$_REQUEST['fechaInicio'.$indice.$pfj]."'";//ECHO "<BR> $query";
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar ingresar la fecha final de la transfusion <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		 $this->RegistrarSubmodulo($this->GetVersion());            
    return true;
	}//InsertarFechaFinTransfusion


/**
* GetGrupoSanguineoPaciente - Obtiene los datos del grupo sanguineo del paciente
*
* @return array
*/

		function GetGrupoSanguineoPaciente()
		{//echo "<br><br>GetGrupoSanguineoPaciente<br>estacion<br>"; print_r($estacion);echo "<br><br>datos estacion<br>"; print_r($datos_estacion);
			$query =  "SELECT grupo_sanguineo,
												rh,
												fecha_registro,
												laboratorio,
												usuario_id
								FROM pacientes_grupo_sanguineo
								WHERE paciente_id = '".$this->paciente."' AND
											tipo_id_paciente = '".$this->tipoidpaciente."' AND
											estado='1';";//echo "<br><br>$query";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Error al intentar obtener el G.S. y RH del paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}else{
				if($result->EOF){
					return "ShowMensaje";
				}else{
				  return $result->FetchRow();
				}
			}
		}//GetGrupoSanguineoPaciente

/**
* GetNotasReaccionAdversasPaciente - Obtiene las reacciones adversas de una transfusion en una determinada fecha
*
* @return array
*/
	function	GetNotasReaccionAdversasPaciente($fecha){
		list($dbconnect) = GetDBconn();
		$query="SELECT ingreso,fecha,observacion,usuario_id,fecha_registro,sw_reaccion
						FROM hc_control_transfusiones_notas_reaccion_adversas
						WHERE ingreso='".$this->ingreso."'
						AND date(fecha)='$fecha'
						ORDER BY fecha_registro DESC";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
			$this->error = "Error al traer las notas de reacciones adversas";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
      return false;
		}
		 if($result->EOF)
		 {return 'ShowMensage';}
			while (!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		  return $vector;
	}

/**
* InsertarReaccionAdversa - Inserta Reacciones adversas de la transfusion en la base de datos
*
* @return boolean
*/
		function InsertarReaccionAdversa(){

			$pfj=$this->frmPrefijo;
			$datos=$_REQUEST['datos'.$pfj];
			if(empty($_REQUEST['reaccionAdversa'.$pfj]) || empty($_REQUEST['sel'.$pfj])){
			  if(empty($_REQUEST['reaccionAdversa'.$pfj])){
          if(empty($_REQUEST['reaccionAdversa'.$pfj])){$this->frmError["reaccionAdversa".$pfj]=1;}
				}
				if(empty($_REQUEST['sel'.$pfj])){
          if(empty($_REQUEST['sel'.$pfj])){$this->frmError["sel".$pfj]=1;}
				}
				$this->frmError["MensajeError"] = "FALTAN DATOS OBLIGATORIOS";
				return true;
			}
			if($_REQUEST['hora_inicio_reaccion'.$pfj]!=-1 && $_REQUEST['minutos_inicio_reaccion'.$pfj]!=-1){
        (list($dia,$mes,$ano)=explode('-',$_REQUEST['fecha_inicio_reaccion'.$pfj]));
        $fecha_inicio_reaccion="'".$ano."-".$mes."-".$dia." ".$_REQUEST['hora_inicio_reaccion'.$pfj].":".$_REQUEST['minutos_inicio_reaccion'.$pfj].":00'";
				if($fecha_inicio_reaccion > "'".date("Y-m-d H:i:s")."'"){
					$this->frmError["MensajeError"] = "ERROR: LA FECHA DE INICIO DE LA REACCION ES MAYOR A LA ACTUAL";
					return true;
				}
			}else{
        $fecha_inicio_reaccion='NULL';
			}
			if($_REQUEST['hora_suspension_transfusion'.$pfj]!=-1 && $_REQUEST['minutos_suspension_transfusion'.$pfj]!=-1){
				(list($dia,$mes,$ano)=explode('-',$_REQUEST['fecha_suspension_transfusion'.$pfj]));
        $fecha_suspension_transfusion="'".$ano."-".$mes."-".$dia." ".$_REQUEST['hora_suspension_transfusion'.$pfj].":".$_REQUEST['minutos_suspension_transfusion'.$pfj].":00'";
				if($fecha_suspension_transfusion > "'".date("Y-m-d H:i:s")."'"){
					$this->frmError["MensajeError"] = "ERROR: LA FECHA DE SUSPENSION DE LA REACCION ES MAYOR A LA ACTUAL";
					return true;
				}
			}else{
        $fecha_suspension_transfusion='NULL';
			}
			if($_REQUEST['hora_notificacion_medico'.$pfj]!=-1 && $_REQUEST['minutos_notificacion_medico'.$pfj]!=-1){
				(list($dia,$mes,$ano)=explode('-',$_REQUEST['fecha_notificacion_medico'.$pfj]));
        $fecha_notificacion_medico="'".$ano."-".$mes."-".$dia." ".$_REQUEST['hora_notificacion_medico'.$pfj].":".$_REQUEST['minutos_notificacion_medico'.$pfj].":00'";
				if($fecha_notificacion_medico > "'".date("Y-m-d H:i:s")."'"){
					$this->frmError["MensajeError"] = "ERROR: LA FECHA DE NOTIFICACION AL MEDICO ES MAYOR A LA ACTUAL";
					return true;
				}
			}else{
        $fecha_notificacion_medico='NULL';
			}
			if($_REQUEST['uso_liquidos_endovenosos'.$pfj]){
        $uso_liquidos_endovenosos='1';
			}else{
        $uso_liquidos_endovenosos='0';
			}

			//if(empty($_SESSION['REACCIONES_TRANSFUSIONALES'.$pfj]['REACCION'])){
      $query="SELECT nextval('hc_control_transfusiones_notas_reaccion_ad_hc_trasfucion_id_seq')";
			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);
      $ReaccionId=$result->fields[0];
			$query="INSERT INTO hc_control_transfusiones_notas_reaccion_adversas
							(hc_trasfucion_id,ingreso,fecha,usuario_id,observacion,fecha_registro,sw_reaccion,
							fecha_inicio_reaccion,fecha_suspension_transfusion,
							fecha_notificacion_medico,sw_liquidos_endovenosos,liquidos_endovenosos)
							VALUES('".$ReaccionId."','".$this->ingreso."','".$datos[fecha]."','".UserGetUID()."',
							'".$_REQUEST['reaccionAdversa'.$pfj]."',now(),'".$_REQUEST['sel'.$pfj]."',
							$fecha_inicio_reaccion,$fecha_suspension_transfusion,$fecha_notificacion_medico,
							'$uso_liquidos_endovenosos','".$_REQUEST['liquidos'.$pfj]."')";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Error al intentar ingresar la reacción adversa<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}else{
			  foreach($_SESSION['REACCIONES_TRANSFUSIONALES'.$pfj]['DIAGNOSTICOS'] as $codigo=>$descripcion){
				 $query="INSERT INTO hc_control_transfusiones_reacciones_diagnosticos(hc_trasfucion_id,diagnostico_id)
					VALUES('".$ReaccionId."','".$codigo."')";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0){
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Error al intentar ingresar la reacción adversa<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						return false;
					}
				}
				unset($_SESSION['REACCIONES_TRANSFUSIONALES'.$pfj]['DIAGNOSTICOS']);
        unset($_REQUEST['reaccionAdversa'.$pfj]);
				unset($_REQUEST['sel'.$pfj]);
				unset($_REQUEST['fecha_inicio_reaccion'.$pfj]);
				unset($_REQUEST['hora_inicio_reaccion'.$pfj]);
				unset($_REQUEST['minutos_inicio_reaccion'.$pfj]);
				unset($_REQUEST['fecha_suspension_transfusion'.$pfj]);
        unset($_REQUEST['hora_suspension_transfusion'.$pfj]);
				unset($_REQUEST['hora_notificacion_medico'.$pfj]);
				unset($_REQUEST['fecha_notificacion_medico'.$pfj]);
				unset($_REQUEST['minutos_suspension_transfusion'.$pfj]);
				unset($_REQUEST['minutos_notificacion_medico'.$pfj]);
				unset($_REQUEST['uso_liquidos_endovenosos'.$pfj]);
				unset($_REQUEST['liquidos'.$pfj]);
				$this->frmError["MensajeError"]="DATAOS GUARDADOS SATISFACTORIAMENTE";
				//$_SESSION['REACCIONES_TRANSFUSIONALES'.$pfj]['REACCION']=$ReaccionId;
			}
			/*}else{
        $query="UPDATE hc_control_transfusiones_notas_reaccion_adversas
							SET observacion=,sw_reaccion=,fecha_inicio_reaccion=,
							fecha_suspension_transfusion=,fecha_notificacion_medico=,
							sw_liquidos_endovenosos=,liquidos_endovenoso= WHERE
							hc_trasfucion_id='".$_SESSION['REACCIONES_TRANSFUSIONALES'.$pfj]['REACCION']."'";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Error al intentar ingresar la reacción adversa<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}
			}*/
			 $this->RegistrarSubmodulo($this->GetVersion());            
      return true;
		}//InsertarReaccionAdversa

/**
* ConsultaFactor - Busca los factores para la hemoclasificacion
*
* @return array
*/

		function ConsultaFactor(){
		list($dbconn) = GetDBconn();
		$query = "SELECT DISTINCT grupo_sanguineo FROM hc_tipos_sanguineos";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
			  while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
	  return $vars;
  }

/**
* TotalBacteriologos - Bacteriologos del Banco de Sangre
*
* @return array
*/
	function TotalBacteriologos(){

		list($dbconn) = GetDBconn();
		$query="SELECT b.tipo_id_tercero,b.tercero_id,b.nombre FROM banco_sangre_profesionales a,profesionales b
		WHERE a.tipo_id_tercero=b.tipo_id_tercero AND a.tercero_id=b.tercero_id AND b.tipo_profesional='6' AND b.estado=1 ORDER BY nombre";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF) {
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
	}

/**
* IngresarHemoclasificacion - Inserta los datos del grupo sanguineo y rh de un paciente X
*
* @return boolean
*/
		function IngresarHemoclasificacion(){
		  $pfj=$this->frmPrefijo;

			if($_REQUEST['grupo_sanguineo'.$pfj]==-1 || $_REQUEST['rh'.$pfj]==-1 || !$_REQUEST['fecha_examen'.$pfj]){
				if($_REQUEST['grupo_sanguineo'.$pfj]==-1){$this->frmError["grupo_sanguineo"]=1;}
				if($_REQUEST['rh'.$pfj]==-1){$this->frmError["rh"]=1;}
				if(!$_REQUEST['fecha_examen'.$pfj]){$this->frmError["fecha_examen"]=1;}
				$this->frmError["MensajeError"]="Complete los Datos Obligatorios.";
				return true;
		  }
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query="UPDATE pacientes_grupo_sanguineo
			SET estado='0'
			WHERE tipo_id_paciente='".$this->tipoidpaciente."' AND paciente_id='".$this->paciente."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				$fechaExamen=ereg_replace("-","/",$_REQUEST['fecha_examen'.$pfj]);
				(list($diaExa,$mesExa,$anoExa)=explode('/',$fechaExamen));
				if($_REQUEST['bacteriologo'.$pfj]!=-1){
				(list($bacteriologo,$Tipobacteriologo)=explode('/',$_REQUEST['bacteriologo'.$pfj]));
				$bacteriologo="'".$bacteriologo."'";
				$Tipobacteriologo="'".$Tipobacteriologo."'";
				}else{
        $bacteriologo='NULL';
				$Tipobacteriologo='NULL';
				}
				$query="INSERT INTO pacientes_grupo_sanguineo(tipo_id_paciente,paciente_id,grupo_sanguineo,rh,laboratorio,observaciones,fecha_examen,
				tipo_id_bacteriologo,bacteriologo_id,usuario_id,fecha_registro,estado)VALUES('".$this->tipoidpaciente."','".$this->paciente."',
				'".$_REQUEST['grupo_sanguineo'.$pfj]."','".$_REQUEST['rh'.$pfj]."','".$_REQUEST['laboratorio'.$pfj]."','".$_REQUEST['observaciones'.$pfj]."','$anoExa-$mesExa-$diaExa',
				$Tipobacteriologo,$bacteriologo,'".UserGetUID()."','".date('Y-m-d H:i:s')."','1')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			$dbconn->CommitTrans();
			$this->frmError["MensajeError"]="Datos Guardados Correctamente.";
			 $this->RegistrarSubmodulo($this->GetVersion());            
      return true;
		}//IngresarHemoclasificacion

/**
* ConsultaReservaSangre - Obtiene de la base de datos las reservas realizadas del paciente en el ingreso
*
* @return array
*/
	function ConsultaReservaSangre(){

		list($dbconnect) = GetDBconn();
		$query= "SELECT b.solicitud_reserva_sangre_id,c.tipo_componente_id,b.fecha_hora_reserva,dpto.descripcion as departamento,b.sw_urgencia,pac.grupo_sanguineo,pac.rh,
		c.cantidad_componente,tiposcom.componente,c.sw_estado,
		(SELECT sum(cantidad_confirmado) FROM banco_sangre_reserva_detalle_confirmadas w WHERE w.solicitud_reserva_sangre_id=c.solicitud_reserva_sangre_id AND w.tipo_componente_id=c.tipo_componente_id) as confirmadas
		FROM banco_sangre_reserva b
		LEFT JOIN departamentos dpto ON(dpto.departamento=b.departamento)
		LEFT JOIN pacientes_grupo_sanguineo pac ON(pac.tipo_id_paciente=b.tipo_id_paciente AND pac.paciente_id=b.paciente_id)
		,banco_sangre_reserva_detalle c
		LEFT JOIN hc_tipos_componentes tiposcom ON(tiposcom.hc_tipo_componente=c.tipo_componente_id)
		WHERE
		b.solicitud_reserva_sangre_id=c.solicitud_reserva_sangre_id AND b.sw_estado='1' AND
		b.tipo_id_paciente='".$this->tipoidpaciente."' AND b.paciente_id='".$this->paciente."' ORDER BY b.fecha_hora_reserva";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al Consultar la Sangre Reservada";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			while (!$result->EOF){
				$fact[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
	  return $fact;
  }

/**
* UnidadesPatinaje - Obtiene de la base de datos los componentes que van a ser entregados
*
* @return array
* @param $Solicitud
* @param $Componente
*/

	function UnidadesPatinaje($Solicitud,$Componente){
    list($dbconn) = GetDBconn();
    $query="SELECT a.ingreso_bolsa_id,a.numero_alicuota,c.bolsa_id,c.grupo_sanguineo,c.rh,c.sello_calidad,c.tipo_componente,c.fecha_vencimiento,
		(SELECT 1 FROM banco_sangre_recepcion_bolsas w WHERE a.ingreso_bolsa_id=w.ingreso_bolsa_id AND a.numero_alicuota=w.numero_alicuota) as recibido,
		(SELECT 1 FROM banco_sangre_entrega_bolsas_enrega_confirmacion z WHERE a.ingreso_bolsa_id=z.ingreso_bolsa_id AND a.numero_alicuota=z.numero_alicuota)as despachado,
		(SELECT y.sw_estado FROM banco_sangre_bolsas_alicuotas y WHERE a.ingreso_bolsa_id=y.ingreso_bolsa_id AND a.numero_alicuota=y.numero_alicuota)as estado_bolsa
		FROM banco_sangre_entrega_bolsas a,banco_sangre_bolsas c
		WHERE a.solicitud_reserva_sangre_id='$Solicitud' AND a.tipo_componente_id='$Componente' AND
		a.ingreso_bolsa_id||' '||a.numero_alicuota IN (SELECT b.ingreso_bolsa_id||' '||b.numero_alicuota FROM banco_sangre_entrega_bolsas_enrega_confirmacion b) AND
		a.ingreso_bolsa_id=c.ingreso_bolsa_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
			  while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}

/**
* RealizarConfirmacionComponentes - Realiza la confirmacion de los componentes para que sean entregados
*
* @return boolean
*/

	function RealizarConfirmacionComponentes(){
    $pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
    foreach($_REQUEST['Solicitar'.$pfj] as $componenteTot=>$valor){
      (list($componente,$reserva)=explode('||//',$componenteTot));
			$valores=$_REQUEST['ValorPendiente'.$pfj];
			$valor=$valores[$componente.'||//'.$reserva];
			if($_REQUEST[$componente.'||//'.$reserva.$pfj]>$valor){
        $this->frmError["MensajeError"]="Las Cantidades para Confirmar no pueden ser mayores a las cantidades Pendientes";
				return true;
			}
			$query="INSERT INTO banco_sangre_reserva_detalle_confirmadas(solicitud_reserva_sangre_id,tipo_componente_id,cantidad_confirmado,fecha_confirmacion,usuario_id)
			VALUES('".$reserva."','".$componente."','".$_REQUEST[$componente.'||//'.$reserva.$pfj]."','".date("Y-m-d H:i:s")."','".UserGetUID()."')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		$dbconn->CommitTrans();
		unset($_REQUEST['Solicitar'.$pfj]);
		 $this->RegistrarSubmodulo($this->GetVersion());            
    return true;
  }

/**
* RegistrarDatosRecepcionBolsa - Registra en la base de datos que la bolsa fue recibida
*
* @return boolean
*/

	function RegistrarDatosRecepcionBolsa(){
	  $pfj=$this->frmPrefijo;
    list($dbconn) = GetDBconn();
    $query="INSERT INTO banco_sangre_recepcion_bolsas (ingreso_bolsa_id,numero_alicuota,observaciones,fecha_recepcion,usuario_id)
		VALUES('".$_REQUEST['IngresoId'.$pfj]."','".$_REQUEST['alicuota'.$pfj]."','".$_REQUEST['observaciones'.$pfj]."','".date("Y-m-d H:i:s")."','".UserGetUID()."')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
     $this->RegistrarSubmodulo($this->GetVersion());            
    return true;
	}

	function RegistrosDiagnosticos($codigo,$descripcion){

    $pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		if($codigo){
      $concat=" WHERE diagnostico_id LIKE '%$codigo%'";
			$s=1;
		}
		if($descripcion){
      if($s==1){
        $concat.=" AND diagnostico_nombre LIKE '%".strtoupper($descripcion)."%'";
			}else{
        $concat=" WHERE diagnostico_nombre LIKE '%".strtoupper($descripcion)."%'";
			}
		}
    if(empty($_REQUEST['conteo'.$pfj])){
			$query = "SELECT count(*) FROM diagnosticos $concat";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}else{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj]){
			$Of='0';
		}else{
			$Of=$_REQUEST['Of'.$pfj];
      if($Of > $this->conteo){
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}
	  $query = "SELECT diagnostico_id,diagnostico_nombre FROM diagnosticos $concat
		LIMIT " . $this->limit . " OFFSET $Of";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$resulta->EOF){
				$var[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
		}
   	if($this->conteo==='0'){
		  $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$resulta->Close();
		return $var;
	}

	function GetDatosReaccionTransfusional($reaccion_adversa){
    list($dbconn) = GetDBconn();
    $query="SELECT a.observacion,a.usuario_id,a.fecha_registro,a.sw_reaccion,a.fecha_inicio_reaccion,
		a.fecha_suspension_transfusion,a.fecha_notificacion_medico,a.sw_liquidos_endovenosos,
		a.liquidos_endovenosos
		FROM hc_control_transfusiones_notas_reaccion_adversas a
		WHERE a.hc_trasfucion_id='$reaccion_adversa'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		return $vars;
	}

	function GetDiagnosticosReaccionTransfusional($reaccion_adversa){
    list($dbconn) = GetDBconn();
    $query="SELECT a.diagnostico_id,b.diagnostico_nombre
		FROM hc_control_transfusiones_reacciones_diagnosticos a,diagnosticos b
		WHERE a.hc_trasfucion_id='$reaccion_adversa' AND a.diagnostico_id=b.diagnostico_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
			  while(!$result->EOF){
				  $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
				}
			}
		}
		return $vars;
	}










}
?>






