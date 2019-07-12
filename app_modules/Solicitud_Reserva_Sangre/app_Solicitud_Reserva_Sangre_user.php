<?php

/**
*MODULO para el Manejo de Programacion e cirugias del Sistema
*
* @author Lorena Aragon
*/

// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Emai: intersof@telesat.com.co
// ----------------------------------------------------------------------

/**
*Contiene los metodos para realizar la programacion de cirugias del paciente
*/
class app_Solicitud_Reserva_Sangre_user extends classModulo
{

	function app_Solicitud_Reserva_Sangre_user()
	{
	 $this->limit=GetLimitBrowser();
	 //$this->limit=2;
   return true;
	}
/**
* Funcion que llama la forma donde se muestran los departamentos del sistema a los que el usuario puede accesar
* @return array
*/
	function main(){
		if(!$this->MenuConsultas()){
      return false;
    }
		return true;
	}


	function LlamaReservasSangreDiarias(){
    if($_REQUEST['destino']!=1){
	  $this->ConsultaReservasSangreDiarias($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['grupoSanguineo'],$_REQUEST['Fecha'],$_REQUEST['estado']);
		}else{
    $this->CompatibilidadSangre();
		}
		return true;

	}

	function ReservasSangreDiarias($TipoDocumento,$Documento,$grupoSanguineo,$Fecha,$estado){

		list($dbconn) = GetDBconn();
		$query1 ="SELECT count(*)
		FROM banco_sangre_reserva a
		LEFT JOIN departamentos c ON(a.departamento=c.departamento),
		pacientes b
		WHERE a.paciente_id=b.paciente_id AND a.tipo_id_paciente=b.tipo_id_paciente";
		$query ="SELECT a.solicitud_reserva_sangre_id,a.paciente_id,a.tipo_id_paciente,
		b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
		c.descripcion as dpto,a.ubicacion_paciente,a.grupo_sanguineo,a.rh,a.fecha_hora_reserva,a.sw_estado
		FROM banco_sangre_reserva a
		LEFT JOIN departamentos c ON(a.departamento=c.departamento),
		pacientes b
		WHERE a.paciente_id=b.paciente_id AND a.tipo_id_paciente=b.tipo_id_paciente";
		if($TipoDocumento && $Documento){
      $query.=" AND a.paciente_id='".$Documento."' AND a.tipo_id_paciente='".$TipoDocumento."'";
			$query1.=" AND a.paciente_id='".$Documento."' AND a.tipo_id_paciente='".$TipoDocumento."'";
		}
		if($grupoSanguineo!=-1 && !empty($grupoSanguineo)){
		  (list($grupoSangineo,$rh)=explode('/',$grupoSanguineo));
      $query.=" AND grupo_sanguineo='".$grupoSangineo."' AND rh='".$rh."'";
			$query1.=" AND grupo_sanguineo='".$grupoSangineo."' AND rh='".$rh."'";
		}
		if($Fecha && $Fecha!='TODAS LAS FECHAS'){
		 $query.=" AND date(a.fecha_hora_reserva)='".$Fecha."'";
		 $query1.=" AND date(a.fecha_hora_reserva)='".$Fecha."'";
		}
		if(!$estado){
      $estado=1;
		}
		if($estado){
      $query.=" AND a.sw_estado='".$estado."'";
		  $query1.=" AND a.sw_estado='".$estado."'";
		}
		if(empty($_REQUEST['conteo'])){
		  $result = $dbconn->Execute($query1);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$result->fetchRow();
    }else{
        $this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of']){
        $Of='0';
		}else{
       $Of=$_REQUEST['Of'];
		}
		$query.=" LIMIT " . $this->limit . " OFFSET $Of";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		return $vars;
	}



	function PedirDatosPaciente(){

	  $cancelar=$_REQUEST['cancelar'];
		if($cancelar){
      $this->FormaProgramacionesQuirurgicas('',1);
			return true;
		}
		$TipoId=$_REQUEST['TipoDocumento'];
		$PacienteId=$_REQUEST['Documento'];
		if(!$PacienteId){
			$this->frmError["MensajeError"]="El tipo de Documento del Paciente es Obligatorio";
		  $accion=ModuloGetURL('app','Quirurgicos','user','PedirDatosPaciente');
			$bandera=1;
		  if($this->IdentificacionPaciente($TipoId,$PacienteId)){
			  return true;
			}
		}
		unset($_SESSION['PACIENTES']);
		$_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$PacienteId;
		$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$TipoId;
		$_SESSION['PACIENTES']['PACIENTE']['plan_id']=2;
		$_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
		$_SESSION['PACIENTES']['RETORNO']['modulo']='Solicitud_Reserva_Sangre';
		$_SESSION['PACIENTES']['RETORNO']['tipo']='user';
		$_SESSION['PACIENTES']['RETORNO']['metodo']='LlamaReservaSangrePaciente';
		$_SESSION['PACIENTES']['RETORNO']['argumentos']=array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"departamento"=>$_REQUEST['departamento'],"ubicacionPaciente"=>$_REQUEST['ubicacionPaciente'],"responsableSolicitud"=>$_REQUEST['responsableSolicitud']);
		$this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
		return true;
	}

	function LlamaModuloEntregaComponentes(){
	  $_SESSION['PACIENTES']['RETORNO']['PASO']=1;
		$_SESSION['RESERVA_SANGRE']['RETORNO']['contenedor']='app';
		$_SESSION['RESERVA_SANGRE']['RETORNO']['modulo']='Solicitud_Reserva_Sangre';
		$_SESSION['RESERVA_SANGRE']['RETORNO']['tipo']='user';
		$_SESSION['RESERVA_SANGRE']['RETORNO']['metodo']='CompatibilidadSangre';
		$_SESSION['RESERVA_SANGRE']['RETORNO']['argumentos']=array("TipoId"=>$_REQUEST['TipoDocumento'],"PacienteId"=>$_REQUEST['Documento'],"reservaId"=>$_REQUEST['reservaId']);
		$this->ReturnMetodoExterno('app','Banco_Sangre','user','BuscarBolsasParaEngregar');
		return true;
	}

	function LlamaReservaSangrePaciente(){
	  $this->ListadoReservasPacientes($_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],$_REQUEST['responsableSolicitud']);
    return true;
	}

/**
* Funcion que retorna los tipo de documentos de la base de datos que puede tener el paciente
* @return array
*/
	function tipo_id_paciente(){
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_id_paciente,descripcion FROM tipos_id_pacientes ORDER BY indice_de_orden";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
* Funcion que retorna los tipo de documentos de la base de datos que puede tener el paciente
* @return array
*/
	function tiposdepartamentos(){
		list($dbconn) = GetDBconn();
		$query = "SELECT a.departamento,a.descripcion
		FROM departamentos a,servicios b
		WHERE a.servicio=b.servicio AND b.sw_asistencial='1' AND
		a.empresa_id='01' ORDER BY a.descripcion";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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

	function LlamaReservaSangre(){
    $this->LlamaReserva_Sangre_qx($_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],$_REQUEST['responsableSolicitud']);
		return true;
	}

/**
* Funcion que busca en la base de datos el nombre de un paciente a partir de su identificacion
* @return string
* @param string tipo del documento del paciente
* @param string numero del documento del paciente
*/
 function BuscarNombresPaciente($tipo,$documento)
 {
		list($dbconn) = GetDBconn();
		$query = "SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo en la tabla pacientes";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		return $result->fields[0];
 }

  function sexoPaciente($TipoId,$PacienteId){
		list($dbconn) = GetDBconn();
		$query = "SELECT sexo_id FROM pacientes WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo en la tabla pacientes";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'pacientes' esta vacia ";
				return false;
			}
		}
		return $result->fields[0];
  }

/**
* Funcion que busca en la base de datos la fecha de nacimiento de un paciente a partir de su identificacion
* @return string
* @param string tipo del documento del paciente
* @param string numero del documento del paciente
*/
	function Edad($TipoId,$PacienteId){
		list($dbconn) = GetDBconn();
		$query = "SELECT fecha_nacimiento FROM pacientes WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'pacientes' esta vacia ";
				return false;
			}
		}
		$result->Close();
		$FechaNacimiento=$result->fields[0];
		return $FechaNacimiento;
  }

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
					$vars[$result->fields[0]]=$result->fields[0];
					$result->MoveNext();
				}
			}
		}
	  return $vars;
  }

	function ConsultaFactorRh(){
		list($dbconn) = GetDBconn();
		$query = "SELECT a.grupo_sanguineo,a.rh,b.descripcion
		FROM hc_tipos_sanguineos a,hc_tipos_rh b
		WHERE a.rh=b.rh
		ORDER BY a.indice_de_orden";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
			  while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($ToUpper = false);;
					$result->MoveNext();
				}
			}
		}
	  return $vars;
  }

	function ConsultaComponente($hcReservaSangreId){
		list($dbconn) = GetDBconn();
		$query = "SELECT b.hc_tipo_componente,b.componente
		FROM hc_tipos_componentes b";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$comp[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
	  return $comp;
  }

	function ReservasAnterioresPaciente($TipoId,$PacienteId){

		list($dbconn) = GetDBconn();
		$query ="SELECT a.solicitud_reserva_sangre_id,a.fecha_hora_reserva,
		b.descripcion
		FROM banco_sangre_reserva a
		LEFT JOIN departamentos b ON(a.departamento=b.departamento)
		WHERE a.paciente_id='$PacienteId' AND a.tipo_id_paciente='$TipoId'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$comp[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
	  return $comp;
	}

	function ComponentesSangre($solicitud){
    list($dbconn) = GetDBconn();
		$query ="SELECT a.tipo_componente_id,a.cantidad_componente
		FROM banco_sangre_reserva_detalle a
		WHERE a.solicitud_reserva_sangre_id='$solicitud' AND a.sw_estado='1'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$comp[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
	  return $comp;
	}

	function nombredpto($departamento){

		list($dbconn) = GetDBconn();
		$query ="SELECT a.descripcion
		FROM departamentos a
		WHERE a.departamento='$departamento'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$comp=$result->GetRowAssoc($ToUpper = false);
		}
	  return $comp;
	}

	function GuardarReservaSangre(){
	  if($_REQUEST['Salir']){
	    $this->ListadoReservasPacientes($_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],$_REQUEST['responsableSolicitud']);
			return true;
		}
		if($_REQUEST['ModificarFactor']){
		  $accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','GuardarRegistroFactor',array("sw_urgencia"=>$_REQUEST['sw_urgencia'],
			"fecha_reserva"=>$_REQUEST['fecha_reserva'],"hora"=>$_REQUEST['hora'],"minutos"=>$_REQUEST['minutos'],"motivo_reserva"=>$_REQUEST['motivo_reserva'],
			"confirmarR"=>$_REQUEST['confirmarR'],"embarazos_previos"=>$_REQUEST['embarazos_previos'],"fecha_ultimo_embarazo"=>$_REQUEST['fecha_ultimo_embarazo'],
			"estado_gestacion"=>$_REQUEST['estado_gestacion'],"departamento"=>$_REQUEST['departamento'],"ubicacionPaciente"=>$_REQUEST['ubicacionPaciente'],"responsableSolicitud"=>$_REQUEST['responsableSolicitud']));
			$this->RegistroFactorSanguineoPaciente($_REQUEST['TipoId'],$_REQUEST['PacienteId'],1,$accion);
			return true;
		}
		if($_REQUEST['SeleccionFactor']){
		  $accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','GuardarRegistroFactor',array("sw_urgencia"=>$_REQUEST['sw_urgencia'],
			"fecha_reserva"=>$_REQUEST['fecha_reserva'],"hora"=>$_REQUEST['hora'],"minutos"=>$_REQUEST['minutos'],"motivo_reserva"=>$_REQUEST['motivo_reserva'],
			"confirmarR"=>$_REQUEST['confirmarR'],"embarazos_previos"=>$_REQUEST['embarazos_previos'],"fecha_ultimo_embarazo"=>$_REQUEST['fecha_ultimo_embarazo'],
			"estado_gestacion"=>$_REQUEST['estado_gestacion'],"departamento"=>$_REQUEST['departamento'],"ubicacionPaciente"=>$_REQUEST['ubicacionPaciente'],"responsableSolicitud"=>$_REQUEST['responsableSolicitud']));
			$this->RegistroFactorSanguineoPaciente($_REQUEST['TipoId'],$_REQUEST['PacienteId'],'',$accion);
			return true;
		}
		if(!$_REQUEST['fecha_reserva'] || $_REQUEST['hora']==-1 || $_REQUEST['minutos']==-1){
			if(!$_REQUEST['fecha_reserva']){$this->frmError["fecha_reserva"]=1;}
			if($_REQUEST['hora']==-1){$this->frmError["hora"]=1;}
			if($_REQUEST['minutos']==-1){$this->frmError["minutos"]=1;}
      $this->frmError["MensajeError"]="Faltan Datos Obligatorios";
      $this->LlamaReserva_Sangre_qx($_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],$_REQUEST['responsableSolicitud']);
			return true;
		}
		$bandera=1;
		$encuentra=1;
		$comp=$this->ConsultaComponente();
		$i=0;
		while($i<sizeof($comp) && $bandera==1){
			$v='Cantidad'.$comp[$i]['hc_tipo_componente'];
			if($_REQUEST[$v]){
			  $encuentra=2;
			if(!is_numeric($_REQUEST[$v])){
			  $bandera=2;
			}
			}
			$i++;
		}
		if($bandera!=1 || $encuentra==1){
      $this->frmError["MensajeError"]="Las Cantidades Seleccionadas para los Componentes deben ser Enteras";
      $this->LlamaReserva_Sangre_qx($_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],$_REQUEST['responsableSolicitud']);
			return true;
		}
    list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		(list($dia,$mes,$ano)=explode('-',$_REQUEST['fecha_reserva']));
    $fecha=$ano.'-'.$mes.'-'.$dia.' '.$_REQUEST['hora'].':'.$_REQUEST['minutos'];
		if($_REQUEST['fecha_ultimo_embarazo']){
		(list($dia,$mes,$ano)=explode('-',$_REQUEST['fecha_ultimo_embarazo']));
    $fechaUltEmbarazo="'$ano-$mes-$dia'";
		}else{
      $fechaUltEmbarazo='NULL';
		}
    $query ="SELECT nextval('banco_sangre_reserva_solicitud_reserva_sangre_id_seq')";
    $result = $dbconn->Execute($query);
    $SolitudReserva=$result->fields[0];
  if($_REQUEST['departamento']==-1){
      $dpto='NULL';
		}else{
      $dpto="'".$_REQUEST['departamento']."'";
		}
		if(!$_REQUEST['grupo_sanguineo']){
      $grupo_sanguineo='NULL';
		}else{
      $grupo_sanguineo="'".$_REQUEST['grupo_sanguineo']."'";
		}
		if(!$_REQUEST['rh']){
      $rh='NULL';
		}else{
      $rh="'".$_REQUEST['rh']."'";
		}
  $query ="INSERT INTO banco_sangre_reserva(solicitud_reserva_sangre_id,paciente_id,tipo_id_paciente,
		                                            ubicacion_paciente,responsable_solicitud,
																								departamento,sw_urgencia,grupo_sanguineo,
																								rh,
																								fecha_hora_reserva,
																								transfuciones_ant,reacciones_adv,
																								descripcion_reac,embarazos_previos,
																								fecha_ultimo_embarazo,motivo_reserva,
																								sw_estado,estado_gestacion,
																								usuario_id,fecha_registro)VALUES(
																								'$SolitudReserva',
																								'".$_REQUEST['PacienteId']."','".$_REQUEST['TipoId']."',
																								'".$_REQUEST['ubicacionPaciente']."','".$_REQUEST['responsableSolicitud']."',
																								$dpto,'".$_REQUEST['sw_urgencia']."',
																								$grupo_sanguineo,$rh,
																								'".$fecha."','".$_REQUEST['transfuciones_ant']."',
																								'".$_REQUEST['reacciones_adv']."','".$_REQUEST['descripcion_reac']."',
																								'".$_REQUEST['embarazos_previos']."',".$fechaUltEmbarazo.",
																								'".$_REQUEST['motivo_reserva']."','1','".$_REQUEST['estado_gestacion']."',
																								'".UserGetUID()."','".date('Y-m-d H:i:s')."')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
		  $comp =$this->ConsultaComponente();
			$i=0;
			while($i<sizeof($comp)){
				$v='Cantidad'.$comp[$i]['hc_tipo_componente'];
				if($_REQUEST[$v]){
				  if($_REQUEST['confirmarR']){
            $estado='2';
					}else{
            $estado='1';
					}
					$query ="INSERT INTO banco_sangre_reserva_detalle(solicitud_reserva_sangre_id,tipo_componente_id,cantidad_componente,sw_estado)
					VALUES('$SolitudReserva','".$comp[$i]['hc_tipo_componente']."','".$_REQUEST[$v]."','$estado')";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
				$i++;
			}
			$selecciones=$_REQUEST['seleccion'];
			if(sizeof($selecciones)>0){
        for($i=0;$i<sizeof($selecciones);$i++){
          $query ="INSERT INTO banco_sangre_reserva_otros_servicios(solicitud_reserva_sangre_id,cargo)
					VALUES('$SolitudReserva','".$selecciones[$i]."')";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
			}
			$dbconn->CommitTrans();
			$mensaje='Reserva de Sangre Exitosa';
			$titulo='RESERVA SANGRE';
			$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','IdentificacionPaciente');
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		$mensaje='La Reserva no fue Creada, Consulte con el Administrador ';
		$titulo='RESERVA SANGRE';
		$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','IdentificacionPaciente');
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		return true;
	}

	function LlamaCompatibilidadSangre(){
    $this->CompatibilidadSangre($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['grupoSanguineo'],$_REQUEST['fechaReserva']);
		return true;
	}

	function ReservasConGlobulos($TipoDocumento,$Documento,$grupoSanguineo,$fechaReserva,$departamento){

    list($dbconn) = GetDBconn();
    if($TipoDocumento && $Documento){
      $concat.=" AND a.tipo_id_paciente='$TipoDocumento' AND a.paciente_id='$Documento'";
		}
		if($grupoSanguineo!=-1 && !empty($grupoSanguineo)){
      (list($grupoS,$rh)=explode('/',$grupoSanguineo));
      $concat.=" AND a.grupo_sanguineo='$grupoS' AND a.rh='$rh'";
		}
		if($fechaReserva){
		  $fechaReserva=ereg_replace("-","/",$fechaReserva);
      (list($dia,$mes,$ano)=explode('/',$fechaReserva));
      $concat.=" AND date(a.fecha_hora_reserva)='".$ano.'-'.$mes.'-'.$dia."'";
		}
		if($departamento && $departamento!='-1'){
      $concat.=" AND a.departamento='$departamento'";
		}
		$query1="SELECT COUNT(*)
		FROM banco_sangre_reserva a
		LEFT JOIN banco_sangre_cruzes_sanguineos x ON(a.solicitud_reserva_sangre_id=x.solicitud_reserva_sangre_id AND x.estado='1')
    LEFT JOIN banco_sangre_bolsas y ON(x.ingreso_bolsa_id=y.ingreso_bolsa_id)
		LEFT JOIN pacientes_grupo_sanguineo l ON(a.tipo_id_paciente=l.tipo_id_paciente AND a.paciente_id=l.paciente_id AND l.estado='1')
		LEFT JOIN banco_sangre_cruzes_sanguineos_entregados e ON(e.cruze_sanguineo_id=x.cruze_sanguineo_id)
		,banco_sangre_reserva_detalle b,hc_tipos_componentes c
		WHERE
		a.solicitud_reserva_sangre_id=b.solicitud_reserva_sangre_id AND
		b.tipo_componente_id=c.hc_tipo_componente AND (b.sw_estado='1' OR b.sw_estado='2') AND c.sw_cruze='1' $concat";

		$query="SELECT a.tipo_id_paciente,a.paciente_id,
		(SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido FROM pacientes WHERE a.tipo_id_paciente=tipo_id_paciente AND a.paciente_id=paciente_id) as nombre,
		l.grupo_sanguineo,l.rh,
		a.responsable_solicitud,a.fecha_hora_reserva,a.solicitud_reserva_sangre_id,c.componente,b.cantidad_componente,x.ingreso_bolsa_id,y.bolsa_id,y.sello_calidad as sellobolsa,y.grupo_sanguineo as grupobolsa,y.rh as rhbolsa,x.cruze_sanguineo_id,x.compatibilidad,
		e.cruze_sanguineo_id as confirma,x.estado as correccion,x.sw_reserva_levantada
		FROM banco_sangre_reserva a
		LEFT JOIN banco_sangre_cruzes_sanguineos x ON(a.solicitud_reserva_sangre_id=x.solicitud_reserva_sangre_id AND x.estado='1')
    LEFT JOIN banco_sangre_bolsas y ON(x.ingreso_bolsa_id=y.ingreso_bolsa_id)
		LEFT JOIN pacientes_grupo_sanguineo l ON(a.tipo_id_paciente=l.tipo_id_paciente AND a.paciente_id=l.paciente_id AND l.estado='1')
		LEFT JOIN banco_sangre_cruzes_sanguineos_entregados e ON(e.cruze_sanguineo_id=x.cruze_sanguineo_id)
		,banco_sangre_reserva_detalle b,hc_tipos_componentes c
		WHERE
		a.solicitud_reserva_sangre_id=b.solicitud_reserva_sangre_id AND
		b.tipo_componente_id=c.hc_tipo_componente AND (b.sw_estado='1' OR b.sw_estado='2') AND c.sw_cruze='1' $concat ORDER BY x.cruze_sanguineo_id DESC,a.solicitud_reserva_sangre_id,x.compatibilidad";
    
		if(empty($_REQUEST['conteo'])){
		  $result = $dbconn->Execute($query1);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$result->fetchRow();
    }else{
        $this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of']){
        $Of='0';
		}else{
       $Of=$_REQUEST['Of'];
		}
		$query.=" LIMIT " . $this->limit . " OFFSET $Of";
		//echo $query1;
		//echo '<BR><BR>';
		//echo $query;
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}else{
			while(!$result->EOF){
				$vars[$result->fields[7]][$result->fields[10]]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		return $vars;
	}

	function LlamaFormaCruzarSangre(){
	  if($_REQUEST['salir']){
      $this->CompatibilidadSangre();
			return true;
		}
    $this->FormaCruzarSangre($_REQUEST['tipoId'],$_REQUEST['paciente'],$_REQUEST['nombre'],$_REQUEST['fecha'],$_REQUEST['responsable'],$_REQUEST['grupo'],$_REQUEST['rh'],$_REQUEST['reservaId'],
		$_REQUEST['bolsaBusqueda'],$_REQUEST['grupo_sanguineoBusqueda'],$_REQUEST['fechaBusqueda']);
		return true;
	}

	function UnidadesSanguineasDisponibles($reservaId,$bolsaBusqueda,$grupo_sanguineoBusqueda,$fechaBusqueda){

    list($dbconn) = GetDBconn();
		if($bolsaBusqueda){
		  $concat.=" AND a.bolsa_id LIKE '%$bolsaBusqueda%'";
		}
    if($grupo_sanguineoBusqueda!=-1 && !empty($grupo_sanguineoBusqueda)){
      (list($grupoS,$rh)=explode('/',$grupo_sanguineoBusqueda));
      $concat.=" AND a.grupo_sanguineo='$grupoS' AND a.rh='$rh'";
		}
		if($fechaBusqueda){
		  $fechaBusqueda=ereg_replace("-","/",$fechaBusqueda);
      (list($dia,$mes,$ano)=explode('/',$fechaBusqueda));
      $concat.=" AND date(a.fecha_vencimiento)='".$ano.'-'.$mes.'-'.$dia."'";
		}
		$query1 ="SELECT count(*)
		FROM banco_sangre_bolsas a,
		banco_sangre_albaranes l
		LEFT JOIN terceros_sgsss c ON(l.entidad_origen=c.codigo_sgsss)
		LEFT JOIN terceros d ON(c.tipo_id_tercero=d.tipo_id_tercero AND c.tercero_id=d.tercero_id),
		((SELECT DISTINCT x.ingreso_bolsa_id FROM banco_sangre_bolsas x,banco_sangre_bolsas_alicuotas z,hc_tipos_componentes y WHERE x.ingreso_bolsa_id=z.ingreso_bolsa_id AND z.sw_estado='1' AND x.tipo_componente=y.hc_tipo_componente AND y.sw_cruze=1) EXCEPT (SELECT z.ingreso_bolsa_id FROM banco_sangre_cruzes_sanguineos z WHERE z.solicitud_reserva_sangre_id='$reservaId' AND z.estado='1')) as x
		WHERE a.ingreso_bolsa_id=x.ingreso_bolsa_id AND a.registro_albaran_id=l.registro_albaran_id $concat";

		$query="SELECT a.ingreso_bolsa_id,a.bolsa_id,a.sello_calidad,a.grupo_sanguineo,a.rh,a.fecha_vencimiento,d.nombre_tercero,a.fecha_extraccion
		FROM banco_sangre_bolsas a,
		banco_sangre_albaranes l
		LEFT JOIN terceros_sgsss c ON(l.entidad_origen=c.codigo_sgsss)
		LEFT JOIN terceros d ON(c.tipo_id_tercero=d.tipo_id_tercero AND c.tercero_id=d.tercero_id),
		((SELECT DISTINCT x.ingreso_bolsa_id FROM banco_sangre_bolsas x,banco_sangre_bolsas_alicuotas z,hc_tipos_componentes y WHERE x.ingreso_bolsa_id=z.ingreso_bolsa_id AND z.sw_estado='1' AND x.tipo_componente=y.hc_tipo_componente AND y.sw_cruze=1) EXCEPT (SELECT z.ingreso_bolsa_id FROM banco_sangre_cruzes_sanguineos z WHERE z.solicitud_reserva_sangre_id='$reservaId' AND z.estado='1')) as x
		WHERE a.ingreso_bolsa_id=x.ingreso_bolsa_id AND a.registro_albaran_id=l.registro_albaran_id $concat";
		if(empty($_REQUEST['conteo'])){
		  $result = $dbconn->Execute($query1);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$result->fetchRow();
    }else{
        $this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of']){
        $Of='0';
		}else{
       $Of=$_REQUEST['Of'];
		}
		$query.=" LIMIT " . $this->limit . " OFFSET $Of";
		//echo $query;
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}
		return $vars;
	}

	function RegistroResultadosCruze(){

    list($dbconn) = GetDBconn();
    $query="SELECT a.hemoclasificacion_manual_anti_a,a.hemoclasificacion_manual_anti_b,a.hemoclasificacion_manual_anti_ab,a.hemoclasificacion_manual_anti_d,
		a.interpretacion_grupo_manual,a.interpretacion_rh_manual,
		a.hemoclasificacion_gel_anti_a,a.hemoclasificacion_gel_anti_b,a.hemoclasificacion_gel_anti_ab,a.hemoclasificacion_gel_anti_d,a.celulas_a,a.celulas_b,a.celulas_0,
		a.interpretacion_grupo_gel,a.interpretacion_rh_gel,
		a.rai_cel1,a.rai_cel2,a.rai_auto,a.rai_otros,a.tipo_id_profesional_gel,a.profesional_gel_id,prof.nombre as profesionalinter,a.fecha_registro
		FROM banco_sangre_cruzes_sanguineos a
		LEFT JOIN profesionales prof ON(a.tipo_id_profesional_gel=prof.tipo_id_tercero AND a.profesional_gel_id=prof.tercero_id)
		,banco_sangre_reserva b
		WHERE b.paciente_id='".$_REQUEST['paciente']."' AND b.tipo_id_paciente='".$_REQUEST['tipoId']."' AND a.solicitud_reserva_sangre_id=b.solicitud_reserva_sangre_id AND
		a.estado='1' ORDER BY a.fecha_registro DESC";
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
        $vars=$result->GetRowAssoc($toUpper=false);
				$_REQUEST['hemoclasifyManualA']=$vars['hemoclasificacion_manual_anti_a'];
				$_REQUEST['hemoclasifyManualB']=$vars['hemoclasificacion_manual_anti_b'];
				$_REQUEST['hemoclasifyManualAB']=$vars['hemoclasificacion_manual_anti_ab'];
				$_REQUEST['hemoclasifyManualD']=$vars['hemoclasificacion_manual_anti_d'];
				$_REQUEST['grupoManual']=$vars['interpretacion_grupo_manual'].'/'.$vars['interpretacion_rh_manual'];
				$_REQUEST['hemoclasifyGelA']=$vars['hemoclasificacion_gel_anti_a'];
				$_REQUEST['hemoclasifyGelB']=$vars['hemoclasificacion_gel_anti_b'];
				$_REQUEST['hemoclasifyGelAB']=$vars['hemoclasificacion_gel_anti_ab'];
				$_REQUEST['hemoclasifyGelD']=$vars['hemoclasificacion_gel_anti_d'];
				$_REQUEST['celulasA']=$vars['celulas_a'];
				$_REQUEST['celulasB']=$vars['celulas_b'];
				$_REQUEST['celulas0']=$vars['celulas_0'];
				$_REQUEST['grupoGel']=$vars['interpretacion_grupo_gel'].'/'.$vars['interpretacion_rh_gel'];
				$_REQUEST['CelI']=$vars['rai_cel1'];
				$_REQUEST['CelII']=$vars['rai_cel2'];
				$_REQUEST['Auto']=$vars['rai_auto'];
				$_REQUEST['OtrosRai']=$vars['rai_otros'];
				$_REQUEST['profesionalinter']=$vars['profesionalinter'];
			}
		}
		$datosProfe=$this->ConfirmacionMedicoDatos();
		if($datosProfe){
      $_REQUEST['bacteriologoManual']=$datosProfe['tercero_id'].'/'.$datosProfe['tipo_tercero_id'];
      $_REQUEST['bacteriologoGel']=$datosProfe['tercero_id'].'/'.$datosProfe['tipo_tercero_id'];
			$_REQUEST['bacteriologoEntrega']=$datosProfe['tercero_id'].'/'.$datosProfe['tipo_tercero_id'];
		}
    $this->FormaResultadosCruze($_REQUEST['bolsa'],$_REQUEST['tipoId'],$_REQUEST['paciente'],$_REQUEST['nombre'],$_REQUEST['fechaReserva'],$_REQUEST['responsable'],$_REQUEST['grupo'],$_REQUEST['rh'],$_REQUEST['reservaId'],
		$_REQUEST['bolsaNum'],$_REQUEST['sello'],$_REQUEST['fechaVence'],
		$_REQUEST['grupoBolsa'],$_REQUEST['rhBolsa'],$_REQUEST['nomTercero'],$_REQUEST['fechaExtraccion']);
		return true;
	}

	function FiltrarBusquedaReservas(){
	  unset($_SESSION['RESERVASANGRE']);
		if($_REQUEST['menu']){
      $this->MenuConsultas();
			return true;
		}
		unset($_SESSION['RESERVASANGRE']);
		if($_REQUEST['TipoDocumento']!=-1){$_SESSION['RESERVASANGRE']['TIPDOCUMENTO']=$_REQUEST['TipoDocumento'];}
		if($_REQUEST['Documento']){$_SESSION['RESERVASANGRE']['DOCUMENTO']=$_REQUEST['Documento'];}
		if($_REQUEST['grupoSanguineo']!=-1){$_SESSION['RESERVASANGRE']['GRUPO']=$_REQUEST['grupoSanguineo'];}
		if($_REQUEST['fechaReserva']){$_SESSION['RESERVASANGRE']['FECHRESERVA']=$_REQUEST['fechaReserva'];}
    $this->CompatibilidadSangre($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['grupoSanguineo'],$_REQUEST['fechaReserva'],$_REQUEST['departamento']);
		return true;
	}

	function GuardarCruzeSangre(){

		if($_REQUEST['salir']){
		  if($_REQUEST["origen"]==1){
        $this->CompatibilidadSangre();
				return true;
			}
		  $this->ConsultaCruzesSanguineos();
			return true;
		}
	  if($_REQUEST['cancelar']){
      $this->FormaCruzarSangre($_REQUEST['tipoId'],$_REQUEST['paciente'],$_REQUEST['nombre'],$_REQUEST['fechaReserva'],$_REQUEST['responsable'],$_REQUEST['grupo'],$_REQUEST['rh'],$_REQUEST['reservaId']);
			return true;
		}
		if(!$_REQUEST['fechaPrueba'] || $_REQUEST['horaPrueba']==-1 || $_REQUEST['minutosPrueba']==-1 ||
		  $_REQUEST['bacteriologoEntrega']==-1 || $_REQUEST['grupoGel']==-1 || $_REQUEST['bacteriologoGel']==-1){
			if(!$_REQUEST['fechaPrueba']){$this->frmError["fechaPrueba"]=1;}
			if($_REQUEST['horaPrueba']==-1){$this->frmError["horaPrueba"]=1;}
			if($_REQUEST['grupoGel']==-1){$this->frmError["grupoGel"]=1;}
			if($_REQUEST['bacteriologoGel']==-1){$this->frmError["bacteriologoGel"]=1;}
			if($_REQUEST['minutosPrueba']==-1){$this->frmError["horaPrueba"]=1;}
			if($_REQUEST['bacteriologoEntrega']==-1){$this->frmError["bacteriologoEntrega"]=1;}
      $this->frmError["MensajeError"]="Faltan Datos Obligatorios";
			$this->FormaResultadosCruze($_REQUEST['bolsa'],$_REQUEST['tipoId'],$_REQUEST['paciente'],$_REQUEST['nombre'],$_REQUEST['fechaReserva'],$_REQUEST['responsable'],$_REQUEST['grupo'],$_REQUEST['rh'],$_REQUEST['reservaId'],
		  $_REQUEST['bolsaNum'],$_REQUEST['sello'],$_REQUEST['fechaVence'],$_REQUEST['grupoBolsa'],$_REQUEST['rhBolsa'],$_REQUEST['nomTercero'],$_REQUEST['fechaExtraccion']);
			return true;
		}
		(list($gr,$rh)=explode('/',$_REQUEST['grupoRegister']));
		if(($gr)&&($rh) && (($_REQUEST['grupoRegister']!=$_REQUEST['grupoManual']) || ($_REQUEST['grupoRegister']!=$_REQUEST['grupoGel']))){
			$this->FormaConfirmarGrupo($_REQUEST['bolsa'],$_REQUEST['tipoId'],$_REQUEST['paciente'],$_REQUEST['nombre'],$_REQUEST['reservaId'],
			$_REQUEST['hemoclasifyManualA'],
			$_REQUEST['hemoclasifyManualB'],$_REQUEST['hemoclasifyManualAB'],$_REQUEST['hemoclasifyManualD'],
			$_REQUEST['grupoManual'],$_REQUEST['bacteriologoManual'],
			$_REQUEST['hemoclasifyGelA'],$_REQUEST['hemoclasifyGelB'],$_REQUEST['hemoclasifyGelAB'],$_REQUEST['hemoclasifyGelD'],
			$_REQUEST['grupoGel'],$_REQUEST['bacteriologoGel'],$_REQUEST['formaResultadoCruze'],
			$_REQUEST['CelI'],$_REQUEST['CelII'],$_REQUEST['Auto'],$_REQUEST['OtrosRai'],
			$_REQUEST['lectina'],$_REQUEST['cde'],$_REQUEST['celulasA'],$_REQUEST['celulasB'],$_REQUEST['celulas0'],
			$_REQUEST['fechaPrueba'],$_REQUEST['horaPrueba'],$_REQUEST['minutosPrueba'],$_REQUEST['observaciones'],$_REQUEST['enz'],
			$_REQUEST['cDirecto'],$_REQUEST['compatibilidad'],
			$_REQUEST['bacteriologoEntrega'],$_REQUEST['quienRecibe'],
			$_REQUEST['fechaRecibe'],$_REQUEST['horaRecibe'],$_REQUEST['minutosRecibe'],$_REQUEST['grupoRegister'],'','','','',
			$_REQUEST['fechaReserva'],$_REQUEST['responsable'],$_REQUEST['grupo'],$_REQUEST['rh'],$_REQUEST['reservaId'],
		  $_REQUEST['bolsaNum'],$_REQUEST['sello'],$_REQUEST['fechaVence'],$_REQUEST['grupoBolsa'],$_REQUEST['rhBolsa'],
			$_REQUEST['nomTercero'],$_REQUEST['fechaExtraccion']);
			return true;
		}
    if($_REQUEST['grupoManual']!=-1){
			(list($grupoManual,$rhManual)=explode('/',$_REQUEST['grupoManual']));
			$grupoManual="'$grupoManual'";
			$rhManual="'$rhManual'";
		}else{
			$grupoManual='NULL';
			$rhManual='NULL';
		}
		if($_REQUEST['bacteriologoManual']!=-1){
		  (list($profesionalManual,$tipoProfesionalManual)=explode('/',$_REQUEST['bacteriologoManual']));
			$profesionalManual="'$profesionalManual'";
			$tipoProfesionalManual="'$tipoProfesionalManual'";
		}else{
      $profesionalManual='NULL';
			$tipoProfesionalManual='NULL';
		}
    if($_REQUEST['grupoGel']!=-1){
      (list($grupoGel,$rhGel)=explode('/',$_REQUEST['grupoGel']));
      $grupoGel="'$grupoGel'";
			$rhGel="'$rhGel'";
		}else{
      $grupoGel='NULL';
			$rhGel='NULL';
		}
		if($_REQUEST['bacteriologoGel']!=-1){
		  (list($profesionalGel,$tipoProfesionalGel)=explode('/',$_REQUEST['bacteriologoGel']));
			$profesionalGel="'$profesionalGel'";
			$tipoProfesionalGel="'$tipoProfesionalGel'";
		}else{
      $profesionalGel='NULL';
			$tipoProfesionalGel='NULL';
		}
		$fechaPrueba=ereg_replace("-","/",$_REQUEST['fechaPrueba']);
    (list($dia,$mes,$ano)=explode('/',$fechaPrueba));
    $fechaPrueba=$ano.'-'.$mes.'-'.$dia.' '.$_REQUEST['horaPrueba'].':'.$_REQUEST['minutosPrueba'].':'.'00';
    (list($profesionalEntrega,$tipoProfesionalEntrega)=explode('/',$_REQUEST['bacteriologoEntrega']));
		if(!$_REQUEST['formaResultadoCruze']){$_REQUEST['formaResultadoCruze']=0;}
    if(!$_REQUEST['cDirecto']){$_REQUEST['cDirecto']=0;}
		if(!$_REQUEST['enz']){$_REQUEST['enz']=0;}
		if(!$_REQUEST['compatibilidad']){$_REQUEST['compatibilidad']=1;}
		if(!$_REQUEST['hemoclasifyManualA']){$_REQUEST['hemoclasifyManualA']=0;}
		if(!$_REQUEST['hemoclasifyManualB']){$_REQUEST['hemoclasifyManualB']=0;}
		if(!$_REQUEST['hemoclasifyManualAB']){$_REQUEST['hemoclasifyManualAB']=0;}
		if(!$_REQUEST['hemoclasifyManualD']){$_REQUEST['hemoclasifyManualD']=0;}
		if(!$_REQUEST['hemoclasifyGelA']){$_REQUEST['hemoclasifyGelA']=0;}
		if(!$_REQUEST['hemoclasifyGelB']){$_REQUEST['hemoclasifyGelB']=0;}
		if(!$_REQUEST['hemoclasifyGelAB']){$_REQUEST['hemoclasifyGelAB']=0;}
		if(!$_REQUEST['hemoclasifyGelD']){$_REQUEST['hemoclasifyGelD']=0;}
		if(!$_REQUEST['celulasA']){$_REQUEST['celulasA']=0;}
		if(!$_REQUEST['celulasB']){$_REQUEST['celulasB']=0;}
		if(!$_REQUEST['celulas0']){$_REQUEST['celulas0']=0;}
		if(!$_REQUEST['CelI']){$_REQUEST['CelI']=0;}
    if(!$_REQUEST['CelII']){$_REQUEST['CelII']=0;}
		if(!$_REQUEST['Auto']){$_REQUEST['Auto']=0;}
		if(!$_REQUEST['OtrosRai']){$_REQUEST['OtrosRai']=0;}
		if(!$_REQUEST['lectina']){$_REQUEST['lectina']=0;}
		if(!$_REQUEST['cde']){$_REQUEST['cde']=0;}

		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query ="INSERT INTO banco_sangre_cruzes_sanguineos(ingreso_bolsa_id,solicitud_reserva_sangre_id,
		hemoclasificacion_manual_anti_a,hemoclasificacion_manual_anti_b,hemoclasificacion_manual_anti_ab,
		hemoclasificacion_manual_anti_d,interpretacion_grupo_manual,interpretacion_rh_manual,
		tipo_id_profesional_manual,profesional_manual_id,hemoclasificacion_gel_anti_a,
    hemoclasificacion_gel_anti_b,hemoclasificacion_gel_anti_ab,hemoclasificacion_gel_anti_d,
    celulas_a,celulas_b,celulas_0,interpretacion_grupo_gel,interpretacion_rh_gel,tipo_id_profesional_gel,
		profesional_gel_id,reaccion_cruzada_visual,fase_coobms,enzimas,compatibilidad,
		rai_cel1,rai_cel2,rai_auto,rai_otros,lectina,cde,fecha_prueba,observaciones,
    tipo_id_profesional_responsable,profesional_responsable_id,
		usuario_id,fecha_registro,estado)
		VALUES('".$_REQUEST['bolsa']."','".$_REQUEST['reservaId']."',
		'".$_REQUEST['hemoclasifyManualA']."','".$_REQUEST['hemoclasifyManualB']."','".$_REQUEST['hemoclasifyManualAB']."','".$_REQUEST['hemoclasifyManualD']."',
		$grupoManual,$rhManual,$tipoProfesionalManual,$profesionalManual,
		'".$_REQUEST['hemoclasifyGelA']."','".$_REQUEST['hemoclasifyGelB']."','".$_REQUEST['hemoclasifyGelAB']."','".$_REQUEST['hemoclasifyGelD']."',
    '".$_REQUEST['celulasA']."','".$_REQUEST['celulasB']."','".$_REQUEST['celulas0']."',
		$grupoGel,$rhGel,$tipoProfesionalGel,$profesionalGel,'".$_REQUEST['formaResultadoCruze']."','".$_REQUEST['cDirecto']."',
    '".$_REQUEST['enz']."','".$_REQUEST['compatibilidad']."',
		'".$_REQUEST['CelI']."','".$_REQUEST['CelII']."','".$_REQUEST['Auto']."','".$_REQUEST['OtrosRai']."',
		'".$_REQUEST['lectina']."','".$_REQUEST['cde']."','$fechaPrueba','".$_REQUEST['observaciones']."',
		'$tipoProfesionalEntrega','$profesionalEntrega',
		'".UserGetUID()."','".date('Y-m-d H:i:s')."','1')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
		  $query="UPDATE banco_sangre_bolsas SET cruzada='1' WHERE ingreso_bolsa_id='".$_REQUEST['bolsa']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
        $query="SELECT * FROM pacientes_grupo_sanguineo WHERE tipo_id_paciente='".$_REQUEST['tipoId']."' AND paciente_id='".$_REQUEST['paciente']."'";
        $result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
          $datos=$result->RecordCount();
					if(!$datos){
            $query="INSERT INTO pacientes_grupo_sanguineo(tipo_id_paciente,paciente_id,grupo_sanguineo,rh,laboratorio,observaciones,fecha_examen,
						tipo_id_bacteriologo,bacteriologo_id,usuario_id,fecha_registro,estado)VALUES('".$_REQUEST['tipoId']."','".$_REQUEST['paciente']."',
						$grupoGel,$rhGel,'','','$fechaPrueba',$tipoProfesionalGel,$profesionalGel,'".UserGetUID()."','".date("Y-m-d H:i:s")."',1)";
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}
				}
				$dbconn->CommitTrans();
				$mensaje='Cruce Guardado Satisfactoriamente';
				$titulo='COMPATIBILIDAD DE SANGRE';
				$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','MenuConsultas');
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
		}
	}

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

	function TotalAuxiliares(){

		list($dbconn) = GetDBconn();
		$query="SELECT tipo_id_tercero,tercero_id,nombre FROM profesionales WHERE (tipo_profesional='3' OR tipo_profesional='4') AND estado=1 ORDER BY nombre";
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

	function LlamaConsultaCruzesSanguineos(){
	  if($_REQUEST['menu']){
      $this->MenuConsultas();
			return true;
		}
    $this->ConsultaCruzesSanguineos($_REQUEST['bolsaBusqueda'],$_REQUEST['numReserva'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['fechaPrueba'],$_REQUEST['correccion']);
		return true;
	}


  function ConsultaCrucesSanguineos($bolsaBusqueda,$numReserva,$TipoDocumento,$Documento,$fechaPrueba,$correccion){
    list($dbconn) = GetDBconn();
		if($bolsaBusqueda){
      $concat.=" AND c.bolsa_id='$bolsaBusqueda'";
		}
		if($numReserva){
      $concat.=" AND d.solicitud_reserva_sangre_id='$numReserva'";
		}
		if($TipoDocumento && $Documento){
      $concat.=" AND d.tipo_id_paciente='$TipoDocumento' AND d.paciente_id='$Documento'";
		}
		if($fechaPrueba){
      $fechaPrueba=ereg_replace("-","/",$fechaPrueba);
      (list($dia,$mes,$ano)=explode('/',$fechaPrueba));
      $concat.=" AND date(a.fecha_prueba)='".$ano.'-'.$mes.'-'.$dia."'";
		}
		if($correccion){
      $concat.=" AND a.estado='0'";
		}else{
      $concat.=" AND a.estado='1'";
		}
		$query1="SELECT count(*)
		FROM  banco_sangre_cruzes_sanguineos a,profesionales b,banco_sangre_bolsas c,banco_sangre_reserva d
		WHERE a.tipo_id_profesional_responsable=b.tipo_id_tercero AND a.profesional_responsable_id=b.tercero_id AND
		a.ingreso_bolsa_id=c.ingreso_bolsa_id AND a.solicitud_reserva_sangre_id=d.solicitud_reserva_sangre_id $concat";

		$query="SELECT a.estado as correccion,a.cruze_sanguineo_id,a.fecha_prueba,a.tipo_id_profesional_responsable,a.profesional_responsable_id,b.nombre as profesional,
    a.compatibilidad,c.bolsa_id,c.grupo_sanguineo,c.rh,d.tipo_id_paciente,d.paciente_id,
		(SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido
		FROM pacientes
		WHERE d.tipo_id_paciente=tipo_id_paciente AND d.paciente_id=paciente_id) as nombre
		FROM  banco_sangre_cruzes_sanguineos a,profesionales b,banco_sangre_bolsas c,banco_sangre_reserva d
		WHERE a.tipo_id_profesional_responsable=b.tipo_id_tercero AND a.profesional_responsable_id=b.tercero_id AND
		a.ingreso_bolsa_id=c.ingreso_bolsa_id AND a.solicitud_reserva_sangre_id=d.solicitud_reserva_sangre_id $concat";
    if(empty($_REQUEST['conteo'])){
		  $result = $dbconn->Execute($query1);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$result->fetchRow();
    }else{
        $this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of']){
        $Of='0';
		}else{
       $Of=$_REQUEST['Of'];
		}
		$query.=" LIMIT " . $this->limit . " OFFSET $Of";
		//echo $query;
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}
		return $vars;
	}

	function ConsultaCruzeSangre(){
    list($dbconn) = GetDBconn();
		$query="SELECT a.ingreso_bolsa_id,b.tipo_id_paciente,b.paciente_id,
		(SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido FROM pacientes WHERE b.tipo_id_paciente=tipo_id_paciente AND b.paciente_id=paciente_id) as nombre,
    b.fecha_hora_reserva,b.responsable_solicitud,b.grupo_sanguineo,b.rh,a.solicitud_reserva_sangre_id,
		c.bolsa_id,c.sello_calidad,c.fecha_vencimiento,c.grupo_sanguineo as grupo_sanguineo_bolsa,c.rh as rh_bolsa,e.nombre_tercero,c.fecha_extraccion,
		a.hemoclasificacion_manual_anti_a,m.descripcion as hemoclasificacion_manual_anti_a_des,
		a.hemoclasificacion_manual_anti_b,n.descripcion as hemoclasificacion_manual_anti_b_des,
		a.hemoclasificacion_manual_anti_ab,o.descripcion as hemoclasificacion_manual_anti_ab_des,
		a.hemoclasificacion_manual_anti_d,p.descripcion as hemoclasificacion_manual_anti_d_des,
		a.interpretacion_grupo_manual,a.interpretacion_rh_manual,a.tipo_id_profesional_manual,a.profesional_manual_id,
		a.hemoclasificacion_gel_anti_a,q.descripcion as hemoclasificacion_gel_anti_a_des,
		a.hemoclasificacion_gel_anti_b,r.descripcion as hemoclasificacion_gel_anti_b_des,
		a.hemoclasificacion_gel_anti_ab,s.descripcion as hemoclasificacion_gel_anti_ab_des,
		a.hemoclasificacion_gel_anti_d,t.descripcion as hemoclasificacion_gel_anti_d_des,
		a.interpretacion_grupo_gel,a.interpretacion_rh_gel,a.tipo_id_profesional_gel,a.profesional_gel_id,
		a.reaccion_cruzada_visual,
		a.celulas_a,rr.descripcion as celulas_a_des,
		a.celulas_b,ss.descripcion as celulas_b_des,
		a.celulas_0,tt.descripcion as celulas_0_des,
    a.fecha_prueba,a.observaciones,
		a.enzimas,aa.descripcion as enz_des,
		a.fase_coobms,bb.descripcion as coobms_d_des,
		a.compatibilidad,a.tipo_id_profesional_responsable,a.profesional_responsable_id,
		a.cde,pp.descripcion as cde_des,
		a.lectina,qq.descripcion as lectina_des,
		a.rai_cel2,cc.descripcion as rai_cel2_des,
		a.rai_cel1,dd.descripcion as rai_cel1_des,
		a.rai_auto,ee.descripcion as rai_auto_des,
		a.rai_otros,ff.descripcion as rai_otros_des,
		v.nombre as profesionalmanual,w.nombre as profesionalgel,y.nombre as profesionalresponsable,
    yy1.tipo_id_profesional_entrega,yy1.profesional_entrega_id,yy2.nombre as profesionalentrega,yy1.tipo_id_profesional_recibe,yy1.profesional_recibe_id,yy3.nombre as profesionalrecibe,yy1.fecha_recibe,
		neww.grupo_sanguineo as grupo_paciente,neww.rh as rh_paciente
		FROM banco_sangre_cruzes_sanguineos a
		LEFT JOIN banco_sangre_cantidad_cruzes m ON(a.hemoclasificacion_manual_anti_a=m.codigo_cantidad_cruces)
    LEFT JOIN banco_sangre_cantidad_cruzes n ON(a.hemoclasificacion_manual_anti_b=n.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes o ON(a.hemoclasificacion_manual_anti_ab=o.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes p ON(a.hemoclasificacion_manual_anti_d=p.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes q ON(a.hemoclasificacion_gel_anti_a=q.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes r ON(a.hemoclasificacion_gel_anti_b=r.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes s ON(a.hemoclasificacion_gel_anti_ab=s.codigo_cantidad_cruces)
    LEFT JOIN banco_sangre_cantidad_cruzes t ON(a.hemoclasificacion_gel_anti_d=t.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes pp ON(a.cde=pp.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes qq ON(a.lectina=qq.codigo_cantidad_cruces)
    LEFT JOIN banco_sangre_cantidad_cruzes rr ON(a.celulas_a=rr.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes ss ON(a.celulas_b=ss.codigo_cantidad_cruces)
    LEFT JOIN banco_sangre_cantidad_cruzes tt ON(a.celulas_0=tt.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes aa ON(a.enzimas=aa.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes bb ON(a.fase_coobms=bb.codigo_cantidad_cruces)
    LEFT JOIN banco_sangre_cantidad_cruzes cc ON(a.rai_cel2=cc.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes dd ON(a.rai_cel1=dd.codigo_cantidad_cruces)
    LEFT JOIN banco_sangre_cantidad_cruzes ee ON(a.rai_auto=ee.codigo_cantidad_cruces)
    LEFT JOIN banco_sangre_cantidad_cruzes ff ON(a.rai_otros=ff.codigo_cantidad_cruces)
		LEFT JOIN profesionales v ON(a.tipo_id_profesional_manual=v.tipo_id_tercero AND a.profesional_manual_id=v.tercero_id)
		LEFT JOIN profesionales w ON(a.tipo_id_profesional_gel=w.tipo_id_tercero AND a.profesional_gel_id=w.tercero_id)
		LEFT JOIN profesionales y ON(a.tipo_id_profesional_responsable=y.tipo_id_tercero AND a.profesional_responsable_id=y.tercero_id)
		LEFT JOIN banco_sangre_cruzes_sanguineos_entregados yy1 ON(a.cruze_sanguineo_id=yy1.cruze_sanguineo_id)
		LEFT JOIN profesionales yy2 ON(yy1.tipo_id_profesional_entrega=yy2.tipo_id_tercero AND yy1.profesional_entrega_id=yy2.tercero_id)
		LEFT JOIN profesionales yy3 ON(yy1.tipo_id_profesional_recibe=yy3.tipo_id_tercero AND yy1.profesional_recibe_id=yy3.tercero_id),
		banco_sangre_reserva b
    LEFT JOIN pacientes_grupo_sanguineo neww ON(b.tipo_id_paciente=neww.tipo_id_paciente AND b.paciente_id=neww.paciente_id AND neww.estado='1'),
		banco_sangre_bolsas c,
		banco_sangre_albaranes nuev
		LEFT JOIN terceros_sgsss d ON(nuev.entidad_origen=d.codigo_sgsss)
    LEFT JOIN terceros e ON(d.tipo_id_tercero=e.tipo_id_tercero AND d.tercero_id=e.tercero_id)
		WHERE a.cruze_sanguineo_id='".$_REQUEST['cruzeid']."' AND a.solicitud_reserva_sangre_id=b.solicitud_reserva_sangre_id AND
		a.ingreso_bolsa_id=c.ingreso_bolsa_id AND c.registro_albaran_id=nuev.registro_albaran_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $vars=$result->GetRowAssoc($toUpper=false);
      $_REQUEST['hemoclasifyManualA']=$vars['hemoclasificacion_manual_anti_a'];
			$_REQUEST['hemoclasifyManualA_des']=$vars['hemoclasificacion_manual_anti_a_des'];
			$_REQUEST['hemoclasifyManualB']=$vars['hemoclasificacion_manual_anti_b'];
			$_REQUEST['hemoclasifyManualB_des']=$vars['hemoclasificacion_manual_anti_b_des'];
			$_REQUEST['hemoclasifyManualAB']=$vars['hemoclasificacion_manual_anti_ab'];
			$_REQUEST['hemoclasifyManualAB_des']=$vars['hemoclasificacion_manual_anti_ab_des'];
			$_REQUEST['hemoclasifyManualD']=$vars['hemoclasificacion_manual_anti_d'];
			$_REQUEST['hemoclasifyManualD_des']=$vars['hemoclasificacion_manual_anti_d_des'];
			$_REQUEST['grupoManual']=$vars['interpretacion_grupo_manual'].'/'.$vars['interpretacion_rh_manual'];
			$_REQUEST['bacteriologoManual']=$vars['profesional_manual_id'].'/'.$vars['tipo_id_profesional_manual'];
			$_REQUEST['hemoclasifyGelA']=$vars['hemoclasificacion_gel_anti_a'];
			$_REQUEST['hemoclasifyGelA_des']=$vars['hemoclasificacion_gel_anti_a_des'];
			$_REQUEST['hemoclasifyGelB']=$vars['hemoclasificacion_gel_anti_b'];
			$_REQUEST['hemoclasifyGelB_des']=$vars['hemoclasificacion_gel_anti_b_des'];
			$_REQUEST['hemoclasifyGelAB']=$vars['hemoclasificacion_gel_anti_ab'];
			$_REQUEST['hemoclasifyGelAB_des']=$vars['hemoclasificacion_gel_anti_ab_des'];
			$_REQUEST['hemoclasifyGelD']=$vars['hemoclasificacion_gel_anti_d'];
			$_REQUEST['hemoclasifyGelD_des']=$vars['hemoclasificacion_gel_anti_d_des'];
			$_REQUEST['grupoGel']=$vars['interpretacion_grupo_gel'].'/'.$vars['interpretacion_rh_gel'];
			$_REQUEST['bacteriologoGel']=$vars['profesional_gel_id'].'/'.$vars['tipo_id_profesional_gel'];
			$_REQUEST['formaResultadoCruze']=$vars['reaccion_cruzada_visual'];
			$_REQUEST['lectina']=$vars['lectina'];
			$_REQUEST['lectina_des']=$vars['lectina_des'];
			$_REQUEST['cde']=$vars['cde'];
			$_REQUEST['cde_des']=$vars['cde_des'];
			$_REQUEST['celulasA']=$vars['celulas_a'];
			$_REQUEST['celulasA_des']=$vars['celulas_a_des'];
			$_REQUEST['celulasB']=$vars['celulas_b'];
			$_REQUEST['celulasB_des']=$vars['celulas_b_des'];
			$_REQUEST['celulas0']=$vars['celulas_0'];
			$_REQUEST['celulas0_des']=$vars['celulas_0_des'];
      $_REQUEST['CelI']=$vars['rai_cel1'];
			$_REQUEST['CelI_des']=$vars['rai_cel1_des'];
			$_REQUEST['CelII']=$vars['rai_cel2'];
			$_REQUEST['CelII_des']=$vars['rai_cel2_des'];
			$_REQUEST['Auto']=$vars['rai_auto'];
			$_REQUEST['Auto_des']=$vars['rai_auto_des'];
			$_REQUEST['OtrosRai']=$vars['rai_otros'];
			$_REQUEST['OtrosRai_des']=$vars['rai_otros_des'];
      (list($fecha,$time)=explode(' ',$vars['fecha_prueba']));
			(list($ano,$mes,$dia)=explode('-',$fecha));
      (list($hora,$minutos)=explode(':',$time));
			$_REQUEST['fechaPrueba']=$dia.'/'.$mes.'/'.$ano;
			$_REQUEST['horaPrueba']=$hora;
			$_REQUEST['minutosPrueba']=$minutos;
      $_REQUEST['observaciones']=$vars['observaciones'];
			$_REQUEST['enz']=$vars['enzimas'];
			$_REQUEST['enz_des']=$vars['enz_des'];
			$_REQUEST['cDirecto']=$vars['fase_coobms'];
			$_REQUEST['cDirecto_des']=$vars['coobms_d_des'];
		  $_REQUEST['compatibilidad']=$vars['compatibilidad'];
			$_REQUEST['bacteriologoEntrega']=$vars['profesional_responsable_id'].'/'.$vars['tipo_id_profesional_responsable'];
			$_REQUEST['profesionalResponsable']=$vars['profesionalresponsable'];
			$_REQUEST['profesionalentrega']=$vars['profesionalentrega'];
			$_REQUEST['profesionalrecibe']=$vars['profesionalrecibe'];
			(list($fecha,$time)=explode(' ',$vars['fecha_prueba']));
			(list($anoR,$mesR,$diaR)=explode('-',$fecha));
      (list($horaR,$minutosR)=explode(':',$time));
			$_REQUEST['fechaRecibe']=$diaR.'/'.$mesR.'/'.$anoR;
			$_REQUEST['horaRecibe']=$horaR;
			$_REQUEST['minutosRecibe']=$minutosR;
			if($_REQUEST['destino']){
			  if($_REQUEST['bandera']==1){
          $_SESSION['SolicitudReserva']['Bandera']=1;
				}
			  $this->FormaResultadosCruze($vars['ingreso_bolsa_id'],$vars['tipo_id_paciente'],$vars['paciente_id'],$vars['nombre'],$vars['fecha_hora_reserva'],$vars['responsable_solicitud'],$vars['grupo_sanguineo'],$vars['rh'],$vars['solicitud_reserva_sangre_id'],
		    $vars['bolsa_id'],$vars['sello_calidad'],$vars['fecha_vencimiento'],$vars['grupo_sanguineo_bolsa'],$vars['rh_bolsa'],$vars['nombre_tercero'],$vars['fecha_extraccion'],'',1);
				return true;
			}
			$this->FormaResultadosCruzeResumen($vars['ingreso_bolsa_id'],$vars['tipo_id_paciente'],$vars['paciente_id'],$vars['nombre'],$vars['fecha_hora_reserva'],$vars['responsable_solicitud'],
			$vars['grupo_paciente'],$vars['rh_paciente'],$vars['solicitud_reserva_sangre_id'],$vars['bolsa_id'],$vars['sello_calidad'],$vars['fecha_vencimiento'],$vars['grupo_sanguineo_bolsa'],
			$vars['rh_bolsa'],$vars['nombre_tercero'],$vars['fecha_extraccion'],1);
			return true;
		}
	}

	function LlamaDetalleReservaSangrePac(){
    $this->DetalleReservaSangrePac($_REQUEST['reservaId'],$_REQUEST['tipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombrePac'],$_REQUEST['fechaReserva'],$_REQUEST['departamento'],$_REQUEST['Ubicacion'],
		$_REQUEST['grupo'],$_REQUEST['rh'],$_REQUEST['origen'],$_REQUEST['destino']);
		return true;
	}

	function ReservasSangreDiariasDetalle($reservaId){
		list($dbconn) = GetDBconn();
		$query="SELECT a.tipo_componente_id,b.componente,a.cantidad_componente,a.sw_estado FROM banco_sangre_reserva_detalle a,hc_tipos_componentes b WHERE a.solicitud_reserva_sangre_id='$reservaId' AND a.tipo_componente_id=b.hc_tipo_componente AND (a.sw_estado='1' OR a.sw_estado='2')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
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
		$result->Close();
 		return $vars;
	}

	function CancelarReservaSangre(){
    list($dbconn) = GetDBconn();
		$query="UPDATE banco_sangre_reserva_detalle SET sw_estado='0' WHERE solicitud_reserva_sangre_id='".$_REQUEST['reservaId']."' AND tipo_componente_id='".$_REQUEST['tipoComponente']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->DetalleReservaSangrePac($_REQUEST['reservaId'],$_REQUEST['tipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombrePac'],$_REQUEST['fechaReserva'],$_REQUEST['departamento'],$_REQUEST['Ubicacion'],$_REQUEST['grupo'],$_REQUEST['rh']);
		return true;
	}

	function CancelarReservaSangreTotal(){
    list($dbconn) = GetDBconn();
		$query="UPDATE banco_sangre_reserva SET sw_estado='3' WHERE solicitud_reserva_sangre_id='".$_REQUEST['reservaId']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->ConsultaReservasSangreDiarias($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['grupoSanguineo'],$_REQUEST['Fecha']);
		return true;
	}

/**
* Funcion que confirma la eliminacion de un registro de la base de datos
* @return boolean
*/
	function LlamaConfirmarAccion($arreglo,$c,$m,$me,$me2,$mensaje,$Titulo,$boton1,$boton2){
		if(empty($Titulo)){
			$arreglo=$_REQUEST['arreglo'];
			$c=$_REQUEST['c'];
			$m=$_REQUEST['m'];
			$me=$_REQUEST['me'];
			$me2=$_REQUEST['me2'];
			$mensaje=$_REQUEST['mensaje'];
			$Titulo=$_REQUEST['titulo'];
			$boton1=$_REQUEST['boton1'];
			$boton2=$_REQUEST['boton2'];
		}
		$this->salida=ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,array($c,$m,'user',$me,$arreglo),array($c,$m,'user',$me2,$arreglo));
		return true;
	}

	function CambioFechaReserva(){
    $this->FormaCambioFechaReserva();
		return true;
	}

	function ActualizarFechaReserva(){
	  if($_REQUEST['Guardar']){
			list($dbconn) = GetDBconn();
			if(!$_REQUEST['nuevaFecha'] || $_REQUEST['hora']==-1 || $_REQUEST['minutos']==-1){
				$this->frmError["MensajeError"]="Complete los Datos.";
				$this->FormaCambioFechaReserva();
				return true;
			}
			(list($dia,$mes,$ano)=explode('-',$_REQUEST['nuevaFecha']));
			$fecha=$ano.'-'.$mes.'-'.$dia.' '.$_REQUEST['hora'].':'.$_REQUEST['minutos'].':'.'00';
			$query="UPDATE banco_sangre_reserva SET fecha_hora_reserva='".$fecha."' WHERE solicitud_reserva_sangre_id='".$_REQUEST['reservaId']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
        $query="UPDATE banco_sangre_reserva SET sw_estado='1' WHERE solicitud_reserva_sangre_id='".$_REQUEST['reservaId']."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
		}
		$this->ConsultaReservasSangreDiarias($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['grupoSanguineo'],$_REQUEST['Fecha'],$_REQUEST['estado']);
		return true;
	}

	function ObtenerCantidadCruces(){
    list($dbconn) = GetDBconn();
		$query="SELECT codigo_cantidad_cruces,descripcion FROM banco_sangre_cantidad_cruzes ORDER BY codigo_cantidad_cruces";
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
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

	function GuardarCorrecionCruzeSangre(){

	  if($_REQUEST['cancelar']){
		  if($_SESSION['SolicitudReserva']['Bandera']==1){
        $this->CompatibilidadSangre();
				return true;
			}
      $this->ConsultaCruzesSanguineos($_REQUEST['bolsaBusqueda'],$_REQUEST['numReserva'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['fechaPrueba']);
			return true;
		}
		if(!$_REQUEST['fechaPrueba'] || $_REQUEST['horaPrueba']==-1 || $_REQUEST['minutosPrueba']==-1 ||
		  $_REQUEST['bacteriologoEntrega']==-1 || $_REQUEST['grupoGel']==-1 || $_REQUEST['bacteriologoGel']==-1){
			if(!$_REQUEST['fechaPrueba']){$this->frmError["fechaPrueba"]=1;}
			if($_REQUEST['horaPrueba']==-1){$this->frmError["horaPrueba"]=1;}
			if($_REQUEST['minutosPrueba']==-1){$this->frmError["horaPrueba"]=1;}
			if($_REQUEST['bacteriologoEntrega']==-1){$this->frmError["bacteriologoEntrega"]=1;}
			if($_REQUEST['quienRecibe']==-1){$this->frmError["quienRecibe"]=1;}
			if(!$_REQUEST['fechaRecibe']){$this->frmError["fechaRecibe"]=1;}
			if($_REQUEST['horaRecibe']==-1){$this->frmError["horaRecibe"]=1;}
			if($_REQUEST['minutosRecibe']==-1){$this->frmError["horaRecibe"]=1;}
			if($_REQUEST['grupoGel']==-1){$this->frmError["grupoGel"]=1;}
			if($_REQUEST['bacteriologoGel']==-1){$this->frmError["bacteriologoGel"]=1;}
      $this->frmError["MensajeError"]="Faltan Datos Obligatorios";
			$this->FormaResultadosCruze($_REQUEST['bolsa'],$_REQUEST['tipoId'],$_REQUEST['paciente'],$_REQUEST['nombre'],$_REQUEST['fechaReserva'],$_REQUEST['responsable'],$_REQUEST['grupo'],$_REQUEST['rh'],$_REQUEST['reservaId'],
		  $_REQUEST['bolsaNum'],$_REQUEST['sello'],$_REQUEST['fechaVence'],$_REQUEST['grupoBolsa'],$_REQUEST['rhBolsa'],$_REQUEST['nomTercero'],$_REQUEST['fechaExtraccion'],'',1);
			return true;
		}
		if(($_REQUEST['grupoRegister']!=$_REQUEST['grupoManual']) || ($_REQUEST['grupoRegister']!=$_REQUEST['grupoGel'])){
		  $this->FormaConfirmarGrupo($_REQUEST['bolsa'],$_REQUEST['tipoId'],$_REQUEST['paciente'],$_REQUEST['nombre'],$_REQUEST['reservaId'],$_REQUEST['hemoclasifyManualA'],
			$_REQUEST['hemoclasifyManualB'],$_REQUEST['hemoclasifyManualAB'],$_REQUEST['hemoclasifyManualD'],$_REQUEST['grupoManual'],$_REQUEST['bacteriologoManual'],
			$_REQUEST['hemoclasifyGelA'],$_REQUEST['hemoclasifyGelB'],$_REQUEST['hemoclasifyGelAB'],$_REQUEST['hemoclasifyGelD'],$_REQUEST['grupoGel'],$_REQUEST['bacteriologoGel'],
			$_REQUEST['formaResultadoCruze'],$_REQUEST['CelI'],$_REQUEST['CelII'],$_REQUEST['Auto'],$_REQUEST['OtrosRai'],$_REQUEST['lectina'],$_REQUEST['cde'],$_REQUEST['celulasA'],$_REQUEST['celulasB'],$_REQUEST['celulas0'],
			$_REQUEST['fechaPrueba'],$_REQUEST['horaPrueba'],$_REQUEST['minutosPrueba'],$_REQUEST['observaciones'],$_REQUEST['enz'],
			$_REQUEST['cDirecto'],$_REQUEST['compatibilidad'],$_REQUEST['bacteriologoEntrega'],$_REQUEST['quienRecibe'],$_REQUEST['fechaRecibe'],$_REQUEST['horaRecibe'],
			$_REQUEST['minutosRecibe'],$_REQUEST['grupoRegister'],$_REQUEST['cruzeid'],$_REQUEST['bolsaBusqueda'],$_REQUEST['numReserva'],1);
			return true;
		}
		$vector=array("grupoManual"=>$_REQUEST['grupoManual'],"bacteriologoManual"=>$_REQUEST['bacteriologoManual'],"grupoGel"=>$_REQUEST['grupoGel'],"bacteriologoGel"=>$_REQUEST['bacteriologoGel'],"grupoCruze"=>$_REQUEST['grupoCruze'],
		"bacteriologoCruze"=>$_REQUEST['bacteriologoCruze'],"fechaPrueba"=>$_REQUEST['fechaPrueba'],"horaPrueba"=>$_REQUEST['horaPrueba'],"minutosPrueba"=>$_REQUEST['minutosPrueba'],"bacteriologoEntrega"=>$_REQUEST['bacteriologoEntrega'],
		"quienRecibe"=>$_REQUEST['quienRecibe'],"fechaRecibe"=>$_REQUEST['fechaRecibe'],"horaRecibe"=>$_REQUEST['horaRecibe'],"minutosRecibe"=>$_REQUEST['minutosRecibe'],"enz"=>$_REQUEST['enz'],"cDirecto"=>$_REQUEST['cDirecto'],
		"compatibilidad"=>$_REQUEST['compatibilidad'],"bolsa"=>$_REQUEST['bolsa'],"reservaId"=>$_REQUEST['reservaId'],"hemoclasifyManualA"=>$_REQUEST['hemoclasifyManualA'],"hemoclasifyManualB"=>$_REQUEST['hemoclasifyManualB'],
		"hemoclasifyManualAB"=>$_REQUEST['hemoclasifyManualAB'],"hemoclasifyManualD"=>$_REQUEST['hemoclasifyManualD'],"hemoclasifyGelA"=>$_REQUEST['hemoclasifyGelA'],"hemoclasifyGelB"=>$_REQUEST['hemoclasifyGelB'],
		"hemoclasifyGelAB"=>$_REQUEST['hemoclasifyGelAB'],"hemoclasifyGelD"=>$_REQUEST['hemoclasifyGelD'],"formaResultadoCruze"=>$_REQUEST['formaResultadoCruze'],"CelI"=>$_REQUEST['CelI'],"CelII"=>$_REQUEST['CelII'],"Auto"=>$_REQUEST['Auto'],
		"OtrosRai"=>$_REQUEST['OtrosRai'],"lectina"=>$_REQUEST['lectina'],"cde"=>$_REQUEST['cde'],"celulasA"=>$_REQUEST['celulasA'],"celulasB"=>$_REQUEST['celulasB'],"celulas0"=>$_REQUEST['celulas0'],
		"observaciones"=>$_REQUEST['observaciones'],"cruzeid"=>$_REQUEST['cruzeid']);
		$vector1=array("bolsaBusqueda"=>$_REQUEST['bolsaBusqueda'],"numReserva"=>$_REQUEST['numReserva'],"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"fechaPrueba"=>$_REQUEST['fechaPrueba']);
		$mensaje='Si Esta Seguro de Guardar la Correccion del Cruce Haga click en Aceptar, de lo Contrario Haga click en Cancelar';
		$titulo='CONFIRMACION CORRECCION DEL CRUCE';
		if($_SESSION['SolicitudReserva']['Bandera']==1){
			$accionCancelar=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','CompatibilidadSangre');
		}else{
			$accionCancelar=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaConsultaCruzesSanguineos',$vector1);
		}
		$accionAceptar=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','GuardarCorreccionCruce',$vector);
		$this->FormaConfirmacion($mensaje,$titulo,$accion,$accionAceptar,$accionCancelar);
		return true;
	}

	function GuardarCorreccionCruce(){
	  if($_REQUEST['cambio']){
      if($_REQUEST['Cancelar']){
			  if($_SESSION['SolicitudReserva']['Bandera']==1){
          $this->CompatibilidadSangre();
					return true;
				}
        $this->LlamaConsultaCruzesSanguineos();
				return true;
			}
		}
    if($_REQUEST['cambio']){
      if($_REQUEST['grupo_sanguineoNuevo']==-1 || $_REQUEST['rhNuevo']==-1 || $_REQUEST['bacteriologoCambio']==-1){
				if($_REQUEST['grupo_sanguineoNuevo']==-1){$this->frmError["grupo_sanguineoNuevo"]=1;}
				if($_REQUEST['rhNuevo']==-1){$this->frmError["rhNuevo"]=1;}
				if($_REQUEST['bacteriologoCambio']==-1){$this->frmError["bacteriologoCambio"]=1;}
				$this->frmError["MensajeError"]="Complete los Datos Obligatorios.";
				$this->FormaConfirmarGrupo($_REQUEST['bolsa'],$_REQUEST['tipoId'],$_REQUEST['paciente'],$_REQUEST['nombre'],$_REQUEST['reservaId'],
				$_REQUEST['hemoclasifyManualA'],
				$_REQUEST['hemoclasifyManualB'],$_REQUEST['hemoclasifyManualAB'],$_REQUEST['hemoclasifyManualD'],
				$_REQUEST['grupoManual'],$_REQUEST['bacteriologoManual'],
				$_REQUEST['hemoclasifyGelA'],$_REQUEST['hemoclasifyGelB'],$_REQUEST['hemoclasifyGelAB'],$_REQUEST['hemoclasifyGelD'],
				$_REQUEST['grupoGel'],$_REQUEST['bacteriologoGel'],$_REQUEST['formaResultadoCruze'],
				$_REQUEST['CelI'],$_REQUEST['CelII'],$_REQUEST['Auto'],$_REQUEST['OtrosRai'],
				$_REQUEST['lectina'],$_REQUEST['cde'],$_REQUEST['celulasA'],$_REQUEST['celulasB'],$_REQUEST['celulas0'],
				$_REQUEST['fechaPrueba'],$_REQUEST['horaPrueba'],$_REQUEST['minutosPrueba'],$_REQUEST['observaciones'],$_REQUEST['enz'],
				$_REQUEST['cDirecto'],$_REQUEST['compatibilidad'],
				$_REQUEST['bacteriologoEntrega'],$_REQUEST['quienRecibe'],
				$_REQUEST['fechaRecibe'],$_REQUEST['horaRecibe'],$_REQUEST['minutosRecibe'],$_REQUEST['grupoRegister'],
				$_REQUEST['cruzeid'],$_REQUEST['bolsaBusqueda'],$_REQUEST['numReserva'],1);
				return true;
			}
		}
    if($_REQUEST['grupoManual']!=-1){
			(list($grupoManual,$rhManual)=explode('/',$_REQUEST['grupoManual']));
			$grupoManual="'$grupoManual'";
			$rhManual="'$rhManual'";
		}else{
			$grupoManual='NULL';
			$rhManual='NULL';
		}
		if($_REQUEST['bacteriologoManual']!=-1){
		  (list($profesionalManual,$tipoProfesionalManual)=explode('/',$_REQUEST['bacteriologoManual']));
			$profesionalManual="'$profesionalManual'";
			$tipoProfesionalManual="'$tipoProfesionalManual'";
		}else{
      $profesionalManual='NULL';
			$tipoProfesionalManual='NULL';
		}
    if($_REQUEST['grupoGel']!=-1){
      (list($grupoGel,$rhGel)=explode('/',$_REQUEST['grupoGel']));
      $grupoGel="'$grupoGel'";
			$rhGel="'$rhGel'";
		}else{
      $grupoGel='NULL';
			$rhGel='NULL';
		}
		if($_REQUEST['bacteriologoGel']!=-1){
		  (list($profesionalGel,$tipoProfesionalGel)=explode('/',$_REQUEST['bacteriologoGel']));
			$profesionalGel="'$profesionalGel'";
			$tipoProfesionalGel="'$tipoProfesionalGel'";
		}else{
      $profesionalGel='NULL';
			$tipoProfesionalGel='NULL';
		}
		$fechaPrueba=ereg_replace("-","/",$_REQUEST['fechaPrueba']);
    (list($dia,$mes,$ano)=explode('/',$fechaPrueba));
    $fechaPrueba=$ano.'-'.$mes.'-'.$dia.' '.$_REQUEST['horaPrueba'].':'.$_REQUEST['minutosPrueba'].':'.'00';
    (list($profesionalEntrega,$tipoProfesionalEntrega)=explode('/',$_REQUEST['bacteriologoEntrega']));
    (list($profesionalRecibe,$tipoProfesionalRecibe)=explode('/',$_REQUEST['quienRecibe']));
		$fechaRecibe=ereg_replace("-","/",$_REQUEST['fechaRecibe']);
    (list($dia,$mes,$ano)=explode('/',$fechaRecibe));
    $fechaRecibe=$ano.'-'.$mes.'-'.$dia.' '.$_REQUEST['horaRecibe'].':'.$_REQUEST['minutosRecibe'].':'.'00';

		if(!$_REQUEST['formaResultadoCruze']){$_REQUEST['formaResultadoCruze']=0;}
    if(!$_REQUEST['cDirecto']){$_REQUEST['cDirecto']=0;}
		if(!$_REQUEST['enz']){$_REQUEST['enz']=0;}
		if(!$_REQUEST['compatibilidad']){$_REQUEST['compatibilidad']=1;}

		if(!$_REQUEST['hemoclasifyManualA']){$_REQUEST['hemoclasifyManualA']=0;}
		if(!$_REQUEST['hemoclasifyManualB']){$_REQUEST['hemoclasifyManualB']=0;}
		if(!$_REQUEST['hemoclasifyManualAB']){$_REQUEST['hemoclasifyManualAB']=0;}
		if(!$_REQUEST['hemoclasifyManualD']){$_REQUEST['hemoclasifyManualD']=0;}
		if(!$_REQUEST['hemoclasifyGelA']){$_REQUEST['hemoclasifyGelA']=0;}
		if(!$_REQUEST['hemoclasifyGelB']){$_REQUEST['hemoclasifyGelB']=0;}
		if(!$_REQUEST['hemoclasifyGelAB']){$_REQUEST['hemoclasifyGelAB']=0;}
		if(!$_REQUEST['hemoclasifyGelD']){$_REQUEST['hemoclasifyGelD']=0;}
		if(!$_REQUEST['celulasA']){$_REQUEST['celulasA']=0;}
		if(!$_REQUEST['celulasB']){$_REQUEST['celulasB']=0;}
		if(!$_REQUEST['celulas0']){$_REQUEST['celulas0']=0;}
		if(!$_REQUEST['CelI']){$_REQUEST['CelI']=0;}
    if(!$_REQUEST['CelII']){$_REQUEST['CelII']=0;}
		if(!$_REQUEST['Auto']){$_REQUEST['Auto']=0;}
		if(!$_REQUEST['OtrosRai']){$_REQUEST['OtrosRai']=0;}
		if(!$_REQUEST['lectina']){$_REQUEST['lectina']=0;}
		if(!$_REQUEST['cde']){$_REQUEST['cde']=0;}
    list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
    $query="SELECT nextval('banco_sangre_cruzes_sanguineos_cruze_sanguineo_id_seq')";
    $result = $dbconn->Execute($query);
		$NumCruce=$result->fields[0];
		$query ="INSERT INTO banco_sangre_cruzes_sanguineos(cruze_sanguineo_id,ingreso_bolsa_id,solicitud_reserva_sangre_id,
		hemoclasificacion_manual_anti_a,hemoclasificacion_manual_anti_b,hemoclasificacion_manual_anti_ab,
		hemoclasificacion_manual_anti_d,interpretacion_grupo_manual,interpretacion_rh_manual,
		tipo_id_profesional_manual,profesional_manual_id,hemoclasificacion_gel_anti_a,
    hemoclasificacion_gel_anti_b,hemoclasificacion_gel_anti_ab,hemoclasificacion_gel_anti_d,
    celulas_a,celulas_b,celulas_0,interpretacion_grupo_gel,interpretacion_rh_gel,tipo_id_profesional_gel,
		profesional_gel_id,reaccion_cruzada_visual,fase_coobms,enzimas,compatibilidad,
		rai_cel1,rai_cel2,rai_auto,rai_otros,lectina,cde,fecha_prueba,observaciones,
    tipo_id_profesional_responsable,profesional_responsable_id,
		usuario_id,fecha_registro,estado)
		VALUES('$NumCruce','".$_REQUEST['bolsa']."','".$_REQUEST['reservaId']."',
		'".$_REQUEST['hemoclasifyManualA']."','".$_REQUEST['hemoclasifyManualB']."','".$_REQUEST['hemoclasifyManualAB']."','".$_REQUEST['hemoclasifyManualD']."',
		$grupoManual,$rhManual,$tipoProfesionalManual,$profesionalManual,
		'".$_REQUEST['hemoclasifyGelA']."','".$_REQUEST['hemoclasifyGelB']."','".$_REQUEST['hemoclasifyGelAB']."','".$_REQUEST['hemoclasifyGelD']."',
    '".$_REQUEST['celulasA']."','".$_REQUEST['celulasB']."','".$_REQUEST['celulas0']."',
		$grupoGel,$rhGel,$tipoProfesionalGel,$profesionalGel,'".$_REQUEST['formaResultadoCruze']."','".$_REQUEST['cDirecto']."',
    '".$_REQUEST['enz']."','".$_REQUEST['compatibilidad']."',
		'".$_REQUEST['CelI']."','".$_REQUEST['CelII']."','".$_REQUEST['Auto']."','".$_REQUEST['OtrosRai']."',
		'".$_REQUEST['lectina']."','".$_REQUEST['cde']."','$fechaPrueba','".$_REQUEST['observaciones']."',
		'$tipoProfesionalEntrega','$profesionalEntrega','".UserGetUID()."','".date('Y-m-d H:i:s')."','1')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
		  $query="UPDATE banco_sangre_cruzes_sanguineos SET estado='0' WHERE cruze_sanguineo_id='".$_REQUEST['cruzeid']."'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
        $query="INSERT INTO banco_sangre_cruzes_correcciones(cruze_sanguineo_id,cruze_corrige)VALUES('".$_REQUEST['cruzeid']."','$NumCruce')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
          if($_REQUEST['cambio']){
            $query="UPDATE pacientes_grupo_sanguineo SET estado='0' WHERE tipo_id_paciente='".$_REQUEST['tipoId']."' AND paciente_id='".$_REQUEST['paciente']."'";
				    $result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}else{
							(list($profesionalCambio,$tipoProfesionalCambio)=explode('/',$_REQUEST['bacteriologoCambio']));
							$query="INSERT INTO pacientes_grupo_sanguineo(tipo_id_paciente,paciente_id,grupo_sanguineo,rh,
							laboratorio,observaciones,fecha_examen,tipo_id_bacteriologo,bacteriologo_id,usuario_id,fecha_registro,estado)
							VALUES('".$_REQUEST['tipoId']."','".$_REQUEST['paciente']."','".$_REQUEST['grupo_sanguineoNuevo']."',
							'".$_REQUEST['rhNuevo']."','','".$_REQUEST['observacionesII']."','$fechaPrueba','$tipoProfesionalCambio',
							'$profesionalCambio','".UserGetUID()."','".date("Y-m-d H:i:s")."','1')";
							$result = $dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0){
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
							}
						}
					}
				}
			}
			$dbconn->CommitTrans();
			$mensaje='Cruce Guardado Satisfactoriamente';
			$titulo='COMPATIBILIDAD DE SANGRE';
			if($_SESSION['SolicitudReserva']['Bandera']==1){
			  $accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','CompatibilidadSangre');
			}else{
			$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaConsultaCruzesSanguineos');
			}
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		}
		return true;
	}

	function ConfirmacionMedico(){
   list($dbconn) = GetDBconn();
		$query="SELECT * FROM profesionales_usuarios WHERE usuario_id='".UserGetUID()."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
     $datos=$result->RecordCount();
			if($datos){
			  return 1;
			}
		}
		return 0;
	}

	function ConfirmacionMedicoDatos(){
    list($dbconn) = GetDBconn();
		$query="SELECT a.tipo_tercero_id,a.tercero_id FROM profesionales_usuarios a WHERE a.usuario_id='".UserGetUID()."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
		  if($datos){
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		return $vars;
	}

	function ConfirmarComponentesSangre(){
    list($dbconn) = GetDBconn();
		$query="UPDATE banco_sangre_reserva_detalle SET sw_estado='2' WHERE solicitud_reserva_sangre_id='".$_REQUEST['reservaId']."' AND tipo_componente_id='".$_REQUEST['tipoComponente']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->DetalleReservaSangrePac($_REQUEST['reservaId'],$_REQUEST['tipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombrePac'],$_REQUEST['fechaReserva'],$_REQUEST['departamento'],$_REQUEST['Ubicacion'],$_REQUEST['grupo'],$_REQUEST['rh'],1,$_REQUEST['destino']);
		return true;
	}

	function FactorPaciente($TipoId,$PacienteId){
    list($dbconn) = GetDBconn();
		$query="SELECT grupo_sanguineo,rh
		FROM pacientes_grupo_sanguineo
		WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId' AND estado='1'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		return $vars;
	}

	function GuardarRegistroFactor(){
    if($_REQUEST['Cancelar']){
      $this->LlamaReserva_Sangre_qx($_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],$_REQUEST['responsableSolicitud']);
			return true;
		}
		if($_REQUEST['bacteriologo']==-1 || $_REQUEST['grupo_sanguineo']==-1 || $_REQUEST['rh']==-1 || !$_REQUEST['fecha_examen']){
		  if($_REQUEST['bacteriologo']==-1){$this->frmError["bacteriologo"]=1;}
      if($_REQUEST['grupo_sanguineo']==-1){$this->frmError["grupo_sanguineo"]=1;}
			if($_REQUEST['rh']==-1){$this->frmError["rh"]=1;}
			if(!$_REQUEST['fecha_examen']){$this->frmError["fecha_examen"]=1;}
			$this->frmError["MensajeError"]="Complete los Datos Obligatorios.";
      $accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','GuardarRegistroFactor',array("sw_urgencia"=>$_REQUEST['sw_urgencia'],
			"fecha_reserva"=>$_REQUEST['fecha_reserva'],"hora"=>$_REQUEST['hora'],"minutos"=>$_REQUEST['minutos'],"motivo_reserva"=>$_REQUEST['motivo_reserva'],
			"confirmarR"=>$_REQUEST['confirmarR'],"embarazos_previos"=>$_REQUEST['embarazos_previos'],"fecha_ultimo_embarazo"=>$_REQUEST['fecha_ultimo_embarazo'],
			"estado_gestacion"=>$_REQUEST['estado_gestacion']));
			$this->RegistroFactorSanguineoPaciente($_REQUEST['TipoId'],$_REQUEST['PacienteId'],$accion);
			return true;
		}
		list($dbconn) = GetDBconn();
		if($_REQUEST['cambio']){
      $query="UPDATE pacientes_grupo_sanguineo SET estado='0' WHERE tipo_id_paciente='".$_REQUEST['TipoId']."' AND paciente_id='".$_REQUEST['PacienteId']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		$fechaExamen=ereg_replace("-","/",$_REQUEST['fecha_examen']);
		(list($diaExa,$mesExa,$anoExa)=explode('/',$fechaExamen));
		(list($bacteriologo,$Tipobacteriologo)=explode('/',$_REQUEST['bacteriologo']));
		$query="INSERT INTO pacientes_grupo_sanguineo(tipo_id_paciente,paciente_id,grupo_sanguineo,rh,laboratorio,observaciones,fecha_examen,
		tipo_id_bacteriologo,bacteriologo_id,usuario_id,fecha_registro,estado)VALUES('".$_REQUEST['TipoId']."','".$_REQUEST['PacienteId']."',
		'".$_REQUEST['grupo_sanguineo']."','".$_REQUEST['rh']."','".$_REQUEST['laboratorio']."','".$_REQUEST['observaciones']."','$anoExa-$mesExa-$diaExa',
		'$Tipobacteriologo','$bacteriologo','".UserGetUID()."','".date('Y-m-d H:i:s')."','1')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->LlamaReserva_Sangre_qx($_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],$_REQUEST['responsableSolicitud']);
		return true;
	}

	function GuardarConfirmacionGrupo(){
    if($_REQUEST['Cancelar']){
      $this->FormaResultadosCruze($_REQUEST['bolsa'],$_REQUEST['tipoId'],$_REQUEST['paciente'],$_REQUEST['nombre'],$_REQUEST['fechaReserva'],$_REQUEST['responsable'],$_REQUEST['grupo'],
			$_REQUEST['rh'],$_REQUEST['reservaId'],$_REQUEST['bolsaNum'],$_REQUEST['sello'],$_REQUEST['fechaVence'],$_REQUEST['grupoBolsa'],$_REQUEST['rhBolsa'],$_REQUEST['nomTercero'],
			$_REQUEST['fechaExtraccion'],$_REQUEST['consulta'],$_REQUEST['destino']);
			return true;
		}
		if($_REQUEST['grupo_sanguineoNuevo']==-1 || $_REQUEST['rhNuevo']==-1 || $_REQUEST['bacteriologoCambio']==-1){
      if($_REQUEST['grupo_sanguineoNuevo']==-1){$this->frmError["grupo_sanguineoNuevo"]=1;}
      if($_REQUEST['rhNuevo']==-1){$this->frmError["rhNuevo"]=1;}
			if($_REQUEST['bacteriologoCambio']==-1){$this->frmError["bacteriologoCambio"]=1;}
			$this->frmError["MensajeError"]="Complete los Datos Obligatorios.";
			$this->FormaConfirmarGrupo($_REQUEST['bolsa'],$_REQUEST['tipoId'],$_REQUEST['paciente'],$_REQUEST['nombre'],$_REQUEST['reservaId'],
			$_REQUEST['hemoclasifyManualA'],
			$_REQUEST['hemoclasifyManualB'],$_REQUEST['hemoclasifyManualAB'],$_REQUEST['hemoclasifyManualD'],
			$_REQUEST['grupoManual'],$_REQUEST['bacteriologoManual'],
			$_REQUEST['hemoclasifyGelA'],$_REQUEST['hemoclasifyGelB'],$_REQUEST['hemoclasifyGelAB'],$_REQUEST['hemoclasifyGelD'],
			$_REQUEST['grupoGel'],$_REQUEST['bacteriologoGel'],$_REQUEST['formaResultadoCruze'],
			$_REQUEST['CelI'],$_REQUEST['CelII'],$_REQUEST['Auto'],$_REQUEST['OtrosRai'],
			$_REQUEST['lectina'],$_REQUEST['cde'],$_REQUEST['celulasA'],$_REQUEST['celulasB'],$_REQUEST['celulas0'],
			$_REQUEST['fechaPrueba'],$_REQUEST['horaPrueba'],$_REQUEST['minutosPrueba'],$_REQUEST['observaciones'],$_REQUEST['enz'],
			$_REQUEST['cDirecto'],$_REQUEST['compatibilidad'],
			$_REQUEST['bacteriologoEntrega'],$_REQUEST['quienRecibe'],
			$_REQUEST['fechaRecibe'],$_REQUEST['horaRecibe'],$_REQUEST['minutosRecibe'],$_REQUEST['grupoRegister']);
			return true;
		}
		if($_REQUEST['grupoManual']!=-1){
			(list($grupoManual,$rhManual)=explode('/',$_REQUEST['grupoManual']));
			$grupoManual="'$grupoManual'";
			$rhManual="'$rhManual'";
		}else{
			$grupoManual='NULL';
			$rhManual='NULL';
		}
		if($_REQUEST['bacteriologoManual']!=-1){
		  (list($profesionalManual,$tipoProfesionalManual)=explode('/',$_REQUEST['bacteriologoManual']));
			$profesionalManual="'$profesionalManual'";
			$tipoProfesionalManual="'$tipoProfesionalManual'";
		}else{
      $profesionalManual='NULL';
			$tipoProfesionalManual='NULL';
		}
    if($_REQUEST['grupoGel']!=-1){
      (list($grupoGel,$rhGel)=explode('/',$_REQUEST['grupoGel']));
      $grupoGel="'$grupoGel'";
			$rhGel="'$rhGel'";
		}else{
      $grupoGel='NULL';
			$rhGel='NULL';
		}
		if($_REQUEST['bacteriologoGel']!=-1){
		  (list($profesionalGel,$tipoProfesionalGel)=explode('/',$_REQUEST['bacteriologoGel']));
			$profesionalGel="'$profesionalGel'";
			$tipoProfesionalGel="'$tipoProfesionalGel'";
		}else{
      $profesionalGel='NULL';
			$tipoProfesionalGel='NULL';
		}
		$fechaPrueba=ereg_replace("-","/",$_REQUEST['fechaPrueba']);
    (list($dia,$mes,$ano)=explode('/',$fechaPrueba));
    $fechaPrueba=$ano.'-'.$mes.'-'.$dia.' '.$_REQUEST['horaPrueba'].':'.$_REQUEST['minutosPrueba'].':'.'00';
    (list($profesionalEntrega,$tipoProfesionalEntrega)=explode('/',$_REQUEST['bacteriologoEntrega']));
    (list($profesionalRecibe,$tipoProfesionalRecibe)=explode('/',$_REQUEST['quienRecibe']));
		$fechaRecibe=ereg_replace("-","/",$_REQUEST['fechaRecibe']);
    (list($dia,$mes,$ano)=explode('/',$fechaRecibe));
    $fechaRecibe=$ano.'-'.$mes.'-'.$dia.' '.$_REQUEST['horaRecibe'].':'.$_REQUEST['minutosRecibe'].':'.'00';
    if(!$_REQUEST['formaResultadoCruze']){$_REQUEST['formaResultadoCruze']=0;}
    if(!$_REQUEST['cDirecto']){$_REQUEST['cDirecto']=0;}
		if(!$_REQUEST['enz']){$_REQUEST['enz']=0;}
		if(!$_REQUEST['compatibilidad']){$_REQUEST['compatibilidad']=1;}
		if(!$_REQUEST['hemoclasifyManualA']){$_REQUEST['hemoclasifyManualA']=0;}
		if(!$_REQUEST['hemoclasifyManualB']){$_REQUEST['hemoclasifyManualB']=0;}
		if(!$_REQUEST['hemoclasifyManualAB']){$_REQUEST['hemoclasifyManualAB']=0;}
		if(!$_REQUEST['hemoclasifyManualD']){$_REQUEST['hemoclasifyManualD']=0;}
		if(!$_REQUEST['hemoclasifyGelA']){$_REQUEST['hemoclasifyGelA']=0;}
		if(!$_REQUEST['hemoclasifyGelB']){$_REQUEST['hemoclasifyGelB']=0;}
		if(!$_REQUEST['hemoclasifyGelAB']){$_REQUEST['hemoclasifyGelAB']=0;}
		if(!$_REQUEST['hemoclasifyGelD']){$_REQUEST['hemoclasifyGelD']=0;}
		if(!$_REQUEST['celulasA']){$_REQUEST['celulasA']=0;}
		if(!$_REQUEST['celulasB']){$_REQUEST['celulasB']=0;}
		if(!$_REQUEST['celulas0']){$_REQUEST['celulas0']=0;}
		if(!$_REQUEST['CelI']){$_REQUEST['CelI']=0;}
    if(!$_REQUEST['CelII']){$_REQUEST['CelII']=0;}
		if(!$_REQUEST['Auto']){$_REQUEST['Auto']=0;}
		if(!$_REQUEST['OtrosRai']){$_REQUEST['OtrosRai']=0;}
		if(!$_REQUEST['lectina']){$_REQUEST['lectina']=0;}
		if(!$_REQUEST['cde']){$_REQUEST['cde']=0;}
    list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query ="INSERT INTO banco_sangre_cruzes_sanguineos(ingreso_bolsa_id,solicitud_reserva_sangre_id,
		hemoclasificacion_manual_anti_a,hemoclasificacion_manual_anti_b,hemoclasificacion_manual_anti_ab,
		hemoclasificacion_manual_anti_d,interpretacion_grupo_manual,interpretacion_rh_manual,
		tipo_id_profesional_manual,profesional_manual_id,hemoclasificacion_gel_anti_a,
    hemoclasificacion_gel_anti_b,hemoclasificacion_gel_anti_ab,hemoclasificacion_gel_anti_d,
    celulas_a,celulas_b,celulas_0,interpretacion_grupo_gel,interpretacion_rh_gel,tipo_id_profesional_gel,
		profesional_gel_id,reaccion_cruzada_visual,fase_coobms,enzimas,compatibilidad,
		rai_cel1,rai_cel2,rai_auto,rai_otros,lectina,cde,fecha_prueba,observaciones,
    tipo_id_profesional_responsable,profesional_responsable_id,
		usuario_id,fecha_registro,estado)
		VALUES('".$_REQUEST['bolsa']."','".$_REQUEST['reservaId']."',
		'".$_REQUEST['hemoclasifyManualA']."','".$_REQUEST['hemoclasifyManualB']."','".$_REQUEST['hemoclasifyManualAB']."','".$_REQUEST['hemoclasifyManualD']."',
		$grupoManual,$rhManual,$tipoProfesionalManual,$profesionalManual,
		'".$_REQUEST['hemoclasifyGelA']."','".$_REQUEST['hemoclasifyGelB']."','".$_REQUEST['hemoclasifyGelAB']."','".$_REQUEST['hemoclasifyGelD']."',
    '".$_REQUEST['celulasA']."','".$_REQUEST['celulasB']."','".$_REQUEST['celulas0']."',
		$grupoGel,$rhGel,$tipoProfesionalGel,$profesionalGel,'".$_REQUEST['formaResultadoCruze']."','".$_REQUEST['cDirecto']."',
    '".$_REQUEST['enz']."','".$_REQUEST['compatibilidad']."',
		'".$_REQUEST['CelI']."','".$_REQUEST['CelII']."','".$_REQUEST['Auto']."','".$_REQUEST['OtrosRai']."',
		'".$_REQUEST['lectina']."','".$_REQUEST['cde']."','$fechaPrueba','".$_REQUEST['observaciones']."',
		'$tipoProfesionalEntrega','$profesionalEntrega','".UserGetUID()."','".date('Y-m-d H:i:s')."','1')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
		  $query="UPDATE banco_sangre_bolsas SET cruzada='1' WHERE ingreso_bolsa_id='".$_REQUEST['bolsa']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
        $query="UPDATE pacientes_grupo_sanguineo SET estado='0' WHERE tipo_id_paciente='".$_REQUEST['tipoId']."' AND paciente_id='".$_REQUEST['paciente']."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
					(list($profesionalCambio,$tipoProfesionalCambio)=explode('/',$_REQUEST['bacteriologoCambio']));
					$query="INSERT INTO pacientes_grupo_sanguineo(tipo_id_paciente,paciente_id,grupo_sanguineo,rh,
					laboratorio,observaciones,fecha_examen,tipo_id_bacteriologo,bacteriologo_id,usuario_id,fecha_registro,estado)
					VALUES('".$_REQUEST['tipoId']."','".$_REQUEST['paciente']."','".$_REQUEST['grupo_sanguineoNuevo']."',
					'".$_REQUEST['rhNuevo']."','','".$_REQUEST['observacionesII']."','$fechaPrueba','$tipoProfesionalCambio',
					'$profesionalCambio','".UserGetUID()."','".date("Y-m-d H:i:s")."','1')";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}else{
						$dbconn->CommitTrans();
						$mensaje='Cruce Guardado Satisfactoriamente';
						$titulo='COMPATIBILIDAD DE SANGRE';
						$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','MenuConsultas');
						$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
						return true;
					}
				}
			}
		}
	}

	function HallarRhRegitradoPaciente($tipoId,$paciente){
    list($dbconn) = GetDBconn();
		$query="SELECT grupo_sanguineo,rh FROM pacientes_grupo_sanguineo WHERE estado='1' AND tipo_id_paciente='$tipoId' AND paciente_id='$paciente'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		return $vars;
	}

	function LlamaEntregaExamen(){
    $this->RegistroEntregaExamen($_REQUEST['TipoDocumentoBus'],$_REQUEST['DocumentoBus'],$_REQUEST['grupoSanguineoBus'],$_REQUEST['fechaCruceBus']);
		return true;
	}

	function ListadoCrucesSinEntregar($TipoDocumento,$Documento,$grupoSanguineo,$fechaCruce){
    list($dbconn) = GetDBconn();
		$query="SELECT a.cruze_sanguineo_id,c.ingreso_bolsa_id,d.solicitud_reserva_sangre_id,c.bolsa_id,c.sello_calidad,c.tipo_componente,z.componente,c.grupo_sanguineo as grupo_sanguineo_bolsa,c.rh as rh_bolsa,d.paciente_id,d.tipo_id_paciente,
		(SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido FROM pacientes WHERE d.paciente_id=paciente_id AND d.tipo_id_paciente=tipo_id_paciente) as nombrepac,
		e.grupo_sanguineo,e.rh,date(b.fecha_prueba) as fecha_prueba
		FROM
		(SELECT cruze_sanguineo_id FROM banco_sangre_cruzes_sanguineos WHERE estado='1'
		EXCEPT
		SELECT cruze_sanguineo_id FROM banco_sangre_cruzes_sanguineos_entregados) a,
		banco_sangre_cruzes_sanguineos b,banco_sangre_bolsas c
    LEFT JOIN hc_tipos_componentes z ON(c.tipo_componente=z.hc_tipo_componente),
		banco_sangre_reserva d
		LEFT JOIN pacientes_grupo_sanguineo e ON(e.paciente_id=d.paciente_id AND e.tipo_id_paciente=d.tipo_id_paciente AND e.estado='1')
		WHERE a.cruze_sanguineo_id=b.cruze_sanguineo_id AND b.ingreso_bolsa_id=c.ingreso_bolsa_id AND
		b.solicitud_reserva_sangre_id=d.solicitud_reserva_sangre_id AND d.sw_estado='1'";
    if($grupoSanguineo && $grupoSanguineo!=-1){
		  (list($grupo,$rh)=explode('/',$grupoSanguineo));
		  $query.=" AND c.grupo_sanguineo='$grupo' AND c.rh='$rh'";
		}
		if($TipoDocumento && $Documento){
      $query.=" AND d.tipo_id_paciente='$TipoDocumento' AND d.paciente_id='$Documento'";
		}
		if($fechaCruce){
		  $fechaCruce=ereg_replace("/","-",$fechaCruce);
			(list($dia,$mes,$ano)=explode('-',$fechaCruce));
      $query.=" AND date(b.fecha_prueba)='".$ano.'-'.$mes.'-'.$dia."'";
		}
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
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

	function EntregaExamenCruce(){
    $this->FormaEntregaExamen($_REQUEST['cruce'],$_REQUEST['bolsa'],$_REQUEST['reserva'],$_REQUEST['NumBolsa'],$_REQUEST['sello'],$_REQUEST['componente'],$_REQUEST['grupoBolsa'],$_REQUEST['rhbolsa'],
		$_REQUEST['tipoId'],$_REQUEST['paciente_id'],$_REQUEST['nombrePac'],$_REQUEST['rh'],$_REQUEST['grupo'],$_REQUEST['indica'],
		$_REQUEST['TipoDocumentoBus'],$_REQUEST['DocumentoBus'],$_REQUEST['grupoSanguineoBus'],$_REQUEST['fechaCruceBus']);
		return true;
	}

	function InsertarDatosEntrega(){
	  if($_REQUEST['Salir']){
		  if($_REQUEST['origen']==1){
			  $this->CompatibilidadSangre();
				return true;
			}
      $this->RegistroEntregaExamen($_REQUEST['TipoDocumentoBus'],$_REQUEST['DocumentoBus'],$_REQUEST['grupoSanguineoBus'],$_REQUEST['fechaCruceBus']);
			return true;
		}
    if($_REQUEST['bacteriologoEntrega']==-1 || $_REQUEST['quienRecibe']==-1 || !$_REQUEST['fechaRecibe'] || $_REQUEST['horaRecibe']==-1 || $_REQUEST['minutosRecibe']==-1){
      if($_REQUEST['bacteriologoEntrega']==-1){$this->frmError["bacteriologoEntrega"]=1;}
			if($_REQUEST['quienRecibe']==-1){$this->frmError["quienRecibe"]=1;}
			if(!$_REQUEST['fechaRecibe']){$this->frmError["fechaRecibe"]=1;}
			if($_REQUEST['horaRecibe']==-1){$this->frmError["horaRecibe"]=1;}
		  if($_REQUEST['minutosRecibe']==-1){$this->frmError["minutosRecibe"]=1;}
			$this->frmError["MensajeError"]="Complete los Datos Obligatorios.";
			$this->FormaEntregaExamen($_REQUEST['cruce'],$_REQUEST['bolsa'],$_REQUEST['reserva'],$_REQUEST['NumBolsa'],$_REQUEST['sello'],$_REQUEST['componente'],$_REQUEST['grupoBolsa'],$_REQUEST['rhbolsa'],
		  $_REQUEST['tipoId'],$_REQUEST['paciente_id'],$_REQUEST['nombrePac'],$_REQUEST['rh'],$_REQUEST['grupo'],$_REQUEST['origen'],
			$_REQUEST['TipoDocumentoBus'],$_REQUEST['DocumentoBus'],$_REQUEST['grupoSanguineoBus'],$_REQUEST['fechaCruceBus']);
			return true;
		}
    $fechaRecibe=ereg_replace("-","/",$_REQUEST['fechaRecibe']);
    (list($diaR,$mesR,$anoR)=explode('/',$fechaRecibe));
    $fechaRecibe=$anoR.'-'.$mesR.'-'.$diaR.' '.$_REQUEST['horaRecibe'].':'.$_REQUEST['minutosRecibe'].':'.'00';
		(list($profesionalEntrega,$tipoProfesionalEntrega)=explode('/',$_REQUEST['bacteriologoEntrega']));
		(list($profesionalRecibe,$tipoProfesionalRecibe)=explode('/',$_REQUEST['quienRecibe']));
		list($dbconn) = GetDBconn();
		$query="INSERT INTO banco_sangre_cruzes_sanguineos_entregados(cruze_sanguineo_id,tipo_id_profesional_entrega,profesional_entrega_id,tipo_id_profesional_recibe,profesional_recibe_id,fecha_recibe,usuario_id,fecha_registro)
		VALUES('".$_REQUEST['cruce']."','$tipoProfesionalEntrega','$profesionalEntrega','$tipoProfesionalRecibe','$profesionalRecibe','$fechaRecibe','".UserGetUID()."','".date("Y-m-d H:i:s")."')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$mensaje='Registro de Entrega del resultado del Cruce Guardado Satisfactoriamente';
		$titulo='ENTREGA RESULTADO CRUCE';
    if($_REQUEST['origen']==1){
    $accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','CompatibilidadSangre');
		}else{
		$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','LlamaEntregaExamen',array("TipoDocumentoBus"=>$_REQUEST['TipoDocumentoBus'],"DocumentoBus"=>$_REQUEST['DocumentoBus'],"grupoSanguineoBus"=>$_REQUEST['grupoSanguineoBus'],"fechaCruceBus"=>$_REQUEST['fechaCruceBus']));
		}
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		return true;
	}

	function LlamaRegistroEntregaExamen(){
    if($_REQUEST['Salir']){
      $this->MenuConsultas();
			return true;
		}
		$this->RegistroEntregaExamen($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['grupoSanguineo'],$_REQUEST['fechaCruce']);
		return true;
	}

	function OtrosServiciosSolicitud(){
    list($dbconn) = GetDBconn();
		$query="SELECT c.descripcion,c.cargo
		FROM banco_sangre_departamento a,departamentos_cargos b,cups c
		WHERE a.departamento=b.departamento AND b.cargo=c.cargo";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
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

	function OtrosServiciosReservaInsertados($reservaId){
    list($dbconn) = GetDBconn();
		$query="SELECT b.descripcion
		FROM banco_sangre_reserva_otros_servicios a,cups b
		WHERE a.solicitud_reserva_sangre_id='$reservaId' AND a.cargo=b.cargo";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
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

	function AlicuotasBolsa($ingresoBolsaId){
    list($dbconn) = GetDBconn();
		$query="SELECT numero_alicuota,cantidad FROM banco_sangre_bolsas_alicuotas WHERE ingreso_bolsa_id='$ingresoBolsaId' ORDER BY numero_alicuota";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
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


}//fin clase user


/*select distinct quirofano_id
from qx_equipos_especiales
where departamento='0501'
and tipo_equipo_id IN ('01','03')*/


// borre la tabla e inserte estos insert solo con los registros no repetidos
//   select distinct 'INSERT INTO derechos_porcentaje VALUES("' || forma_calculo ||
//   '","' || tipo_derecho || '","' || via_acceso  || '","' || secuencia || '","' ||
//   porcentaje || '","' || valor || '")' FROM derechos_porcentaje ;
?>

