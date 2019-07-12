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

class app_Patologia_user extends classModulo
{

	function app_Patologia_user()
	{
	  $this->limit=GetLimitBrowser();
		//$this->limit=3;
    return true;
	}
/**
* Funcion que llama la forma donde se muestran los departamentos del sistema a los que el usuario puede accesar
* @return array
*/
	function main(){
	  $validarUsuario=$this->compruebaTipoUsuario();
		if($validarUsuario==1){
			if(!$this->MenuPrincipal()){
				return false;
			}
			return true;
		}else{
      $this->SinPermisosUsuarios();
			return true;
		}
	}

	function compruebaTipoUsuario(){
    list($dbconn) = GetDBconn();
		$query = "SELECT * FROM userpermisos_auxiliares_patologias WHERE usuario_id='".UserGetUID()."'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
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

	function PedirIdentificacionPaciente(){
	  $this->IdentificacionPaciente();
		return true;
	}

/**
* Funcion que retorna los tipo de documentos de la base de datos que puede tener el paciente
* @return array
*/
	function tipo_id_paciente(){
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_id_paciente,descripcion
		FROM tipos_id_pacientes ORDER BY indice_de_orden";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
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

	function PedirDatosPaciente(){

		if(!$_REQUEST['Documento']){
			$this->frmError["MensajeError"]="El tipo de Documento del Paciente es Obligatorio";
			$this->PedirIdentificacionPaciente();
			return true;
		}

		$TipoId=$_REQUEST['TipoDocumento'];
		$PacienteId=$_REQUEST['Documento'];
		unset($_SESSION['PACIENTES']);
		$_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$PacienteId;
		$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$TipoId;
		$_SESSION['PACIENTES']['PACIENTE']['plan_id']=56;
		$_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
		$_SESSION['PACIENTES']['RETORNO']['modulo']='Patologia';
		$_SESSION['PACIENTES']['RETORNO']['tipo']='user';
		$_SESSION['PACIENTES']['RETORNO']['metodo']='LlamaSolicitudPatologia';
		$_SESSION['PACIENTES']['RETORNO']['argumentos']=array("TipoDocumento"=>$TipoId,"Documento"=>$PacienteId);
		$this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
		return true;
	}

	function LlamaSolicitudPatologia(){
	  if(empty($_SESSION['PACIENTES']['RETORNO']['PASO'])){
      $this->MenuPrincipal();
			return true;
		}
    $this->SolicitudPatologia($_REQUEST['TipoDocumento'],$_REQUEST['Documento']);
		return true;
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

	 function nombrePaciente($TipoId,$PacienteId){
		list($dbconn) = GetDBconn();
		$query = "SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido as nombre FROM pacientes WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo en la tabla pacientes";
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

  function LlamaFormaBusquedaProcedimientos(){
	  $this->FormaBusquedaProcedimientos($_REQUEST['observacionesInforme'],$_REQUEST['firma'],'','',$_REQUEST['patologoProfe']);
		return true;
	}

	function GuardarSolicitudPatologia(){

    if($_REQUEST['buscarPro']){
      $this->FormaBusquedaProcedimientos($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],
			$_REQUEST['responsableSolicitud'],$_REQUEST['procedimiento'],$_REQUEST['codigoPro'],$_REQUEST['tratamientos'],$_REQUEST['Solicitud'],$_REQUEST['observaciones']);
			return true;
		}
		if($_REQUEST['buscarDiagn']){
      $this->FormaBuscadorDiagnostico($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],
			$_REQUEST['responsableSolicitud'],$_REQUEST['procedimiento'],$_REQUEST['codigoPro'],$_REQUEST['tratamientos'],$_REQUEST['Solicitud'],$_REQUEST['observaciones'],$_REQUEST['origenSolicitud']);
			return true;
		}
		if($_REQUEST['buscarTejido']){
      $this->BucadorTejidosPatologicos($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],
			$_REQUEST['responsableSolicitud'],$_REQUEST['tratamientos'],$_REQUEST['Solicitud'],$_REQUEST['procedimiento'],$_REQUEST['codigoPro'],$_REQUEST['observaciones'],$_REQUEST['origenSolicitud']);
			return true;
		}

		if($_REQUEST['Guardar']){
		  if(!$_REQUEST['Solicitud']){
			  $this->frmError["MensajeError"]="Debe Especificar la Solicitud patologica a Realizar";
        $this->SolicitudPatologia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],
				$_REQUEST['responsableSolicitud'],$_REQUEST['tratamientos'],$_REQUEST['Solicitud'],$_REQUEST['procemiento'],$_REQUEST['codigoPro'],$_REQUEST['observaciones'],'','','','',$_REQUEST['origenSolicitud']);
				return true;
			}
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			if($_REQUEST['departamento']==-1){
        $departamento='NULL';
			}else{
        $departamento="'".$_REQUEST['departamento']."'";
			}
			$query="SELECT nextval('patologias_solicitudes_patologia_solicitud_id_seq')";
			$result = $dbconn->Execute($query);
			$codigoPatologico=$result->fields[0];
			$query="INSERT INTO patologias_solicitudes(patologia_solicitud_id,evolucion_id,tipo_id_paciente,paciente_id,quirofano,ubicacion_paciente,departamento,responsable_solicitud,
			tratamientos_efectuados,hallazgos,solicitud,observaciones,usuario_id,fecha_registro,origen_solicitud)VALUES('$codigoPatologico',NULL,'".$_REQUEST['TipoDocumento']."',
			'".$_REQUEST['Documento']."',NULL,'".$_REQUEST['ubicacionPaciente']."',$departamento,'".$_REQUEST['responsableSolicitud']."',
			'".$_REQUEST['tratamientos']."','','".$_REQUEST['Solicitud']."','".$_REQUEST['observaciones']."','".UserGetUID()."','".date("Y-m-d H:i:s")."','".$_REQUEST['origenSolicitud']."')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo en la tabla pacientes";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				foreach($_SESSION['PATOLOGIA']['DIAGNOSTICOS'] as $codigo=>$nombre){
          $query="INSERT INTO patologias_solicitudes_diagnosticos(patologia_solicitud_id,diagnostico_id)
			    VALUES('$codigoPatologico','$codigo')";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo en la tabla pacientes";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
				foreach($_SESSION['PATOLOGIA']['TEJIDOS'] as $codigo=>$nombre){
          $query="INSERT INTO patologias_solicitudes_detalle(patologia_solicitud_id,tejido_id,estado)
			    VALUES('$codigoPatologico','$codigo','1')";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo en la tabla pacientes";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}else{
            $dbconn->CommitTrans();
					}
				}
			}
			unset($_SESSION['PATOLOGIA']['TEJIDOS']);
			unset($_SESSION['PATOLOGIA']['DIAGNOSTICOS']);
			$mensaje='Solicitud Guardada Correctamente';
			$titulo='SOLICITUD PATOLOGIA';
			$accion=ModuloGetURL('app','Patologia','user','MenuPrincipal');
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		unset($_SESSION['PATOLOGIA']['TEJIDOS']);
		unset($_SESSION['PATOLOGIA']['DIAGNOSTICOS']);
		$this->MenuPrincipal();
		return true;
	}

	function HallarCupsPatologia($codigoBus,$procedimientoBus,$filtrogrupoTipoCargo,$filtroTipoCargo){
    list($dbconn) = GetDBconn();
		$query = "SELECT cargo,descripcion FROM cups WHERE grupo_tipo_cargo='".$_SESSION['DATOS_PATOLOGIA']['GRUPO']."' AND tipo_cargo='".$_SESSION['DATOS_PATOLOGIA']['TIPO']."'";
		$query1 = "SELECT count(*) FROM cups WHERE grupo_tipo_cargo='".$_SESSION['DATOS_PATOLOGIA']['GRUPO']."' AND tipo_cargo='".$_SESSION['DATOS_PATOLOGIA']['TIPO']."'";
		if($codigoBus){
      $query.=" AND cargo LIKE '%$codigoBus%'";
			$query1.=" AND cargo LIKE '%$codigoBus%'";
		}
		if($procedimientoBus){
      $query.=" AND descripcion LIKE '%".strtoupper($procedimientoBus)."%'";
			$query1.=" AND descripcion LIKE '%".strtoupper($procedimientoBus)."%'";
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

	function SeleccionProcedimientoBusqueda(){
    if($_REQUEST['Salir']){
      $this->InformeResultadoPatologico($_REQUEST['observacionesInforme'],$_REQUEST['firma'],'','','','',$_REQUEST['patologoProfe']);
			return true;
		}
		if($_REQUEST['buscar']){
      $this->FormaBusquedaProcedimientos($_REQUEST['observacionesInforme'],$_REQUEST['firma'],$_REQUEST['filtrogrupoTipoCargo'],$_REQUEST['filtroTipoCargo'],$_REQUEST['patologoProfe']);
			return true;
		}
	}

	function SeleccionarProcedimiento(){
	  if($_REQUEST['bandera']==1){
    $this->FormaRecepcionTejidoPatologico($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['fecha'],
		$_REQUEST['descripcionSelect'],$_REQUEST['cargoSelect'],$_REQUEST['tejido'],$_REQUEST['codigoTejido']);
		}else{
		$size=sizeof($_SESSION['PATOLOGIA']['PROCEDIMIENTOS'][$_REQUEST['cargoSelect']]);
		$_SESSION['PATOLOGIA']['PROCEDIMIENTOS'][$_REQUEST['cargoSelect']][$size]=$_REQUEST['descripcionSelect'];
    $this->InformeResultadoPatologico($_REQUEST['observacionesInforme'],$_REQUEST['firma'],'','','','',$_REQUEST['patologoProfe']);
		}
		return true;
	}

	function LlamaFormaBuscadorDiagnostico(){
    $this->FormaBuscadorDiagnostico($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],
		$_REQUEST['responsableSolicitud'],$_REQUEST['procedimiento'],$_REQUEST['codigoPro'],$_REQUEST['tratamientos'],$_REQUEST['Solicitud'],$_REQUEST['observaciones'],$_REQUEST['origenSolicitud']);
		return true;
	}

	function HallarDiagnosticosPatologia($codigoBus,$procedimientoBus){

		list($dbconn) = GetDBconn();
    $query="SELECT diagnostico_id,diagnostico_nombre FROM diagnosticos";
    $query1="SELECT count(*) FROM diagnosticos";
		if($codigoBus){
      $query.=" WHERE diagnostico_id LIKE '%$codigoBus%'";
			$query1.=" WHERE diagnostico_id LIKE '%$codigoBus%'";
			$ya=1;
		}
		if($procedimientoBus){
      if($ya==1){
        $query.=" AND diagnostico_nombre LIKE '%".strtoupper($procedimientoBus)."%'";
			  $query1.=" AND diagnostico_nombre LIKE '%".strtoupper($procedimientoBus)."%'";
			}else{
        $query.=" WHERE diagnostico_nombre LIKE '%".strtoupper($procedimientoBus)."%'";
			  $query1.=" WHERE diagnostico_nombre LIKE '%".strtoupper($procedimientoBus)."%'";
			}
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

	function SeleccionDiagnostico(){
	  if($_REQUEST['buscar']){
		  if($_REQUEST['origen']){
        $this->FormaBuscadorDiagnosticoResultado($_REQUEST['observacionesInforme'],$_REQUEST['firma'],$_REQUEST['patologoProfe']);
				return true;
			}else{
				$this->FormaBuscadorDiagnostico($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],
				$_REQUEST['ubicacionPaciente'],$_REQUEST['responsableSolicitud'],$_REQUEST['procedimiento'],$_REQUEST['codigoPro'],
				$_REQUEST['tratamientos'],$_REQUEST['Solicitud'],$_REQUEST['observaciones'],$_REQUEST['origenSolicitud']);
				return true;
			}
		}
		if($_REQUEST['origen']){
      $this->InformeResultadoPatologico($_REQUEST['observacionesInforme'],$_REQUEST['firma'],'','','','',$_REQUEST['patologoProfe']);
			return true;
		}else{
			$this->SolicitudPatologia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],
			$_REQUEST['responsableSolicitud'],$_REQUEST['tratamientos'],$_REQUEST['Solicitud'],$_REQUEST['procemiento'],$_REQUEST['codigoPro'],$_REQUEST['observaciones'],
			$_REQUEST['nombreDiagnostico'],$_REQUEST['codigoDiag'],$_REQUEST['nombreTejido'],$_REQUEST['codigoTejido'],$_REQUEST['origenSolicitud']);
			return true;
		}
	}

	function  SeleccionarDiagnosticoPatologia(){
	  if($_REQUEST['origen']!=1){
			$_SESSION['PATOLOGIA']['DIAGNOSTICOS'][$_REQUEST['codigoDiagnostico']]=$_REQUEST['nombreDiagnostico'];
			$this->SolicitudPatologia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],$_REQUEST['responsableSolicitud'],$_REQUEST['tratamientos'],$_REQUEST['Solicitud'],$_REQUEST['procemiento'],$_REQUEST['codigoPro'],$_REQUEST['observaciones'],'','','','',$_REQUEST['origenSolicitud']);
		}else{
		  $_SESSION['PATOLOGIA']['RESULTADO'][$_REQUEST['codigoDiagnostico']]=$_REQUEST['nombreDiagnostico'];
			$this->InformeResultadoPatologico($_REQUEST['observacionesInforme'],$_REQUEST['firma'],'','','','',$_REQUEST['patologoProfe']);
			return true;
		}
		return true;
	}

	function EliminaDiagnosticoPatologia(){
    list($dbconn) = GetDBconn();
		unset($_SESSION['PATOLOGIA']['DIAGNOSTICOS'][$_REQUEST['codigoDiagnostico']]);
		$this->SolicitudPatologia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],$_REQUEST['responsableSolicitud'],$_REQUEST['tratamientos'],$_REQUEST['Solicitud'],$_REQUEST['procemiento'],$_REQUEST['codigoPro'],$_REQUEST['observaciones'],'','','','',$_REQUEST['origenSolicitud']);
		return true;
	}

	function EliminaTejidoPatologia(){
    list($dbconn) = GetDBconn();
		list($dbconn) = GetDBconn();
		unset($_SESSION['PATOLOGIA']['TEJIDOS'][$_REQUEST['codigoTejido']]);
		$this->SolicitudPatologia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],$_REQUEST['responsableSolicitud'],$_REQUEST['tratamientos'],$_REQUEST['Solicitud'],$_REQUEST['procemiento'],$_REQUEST['codigoPro'],$_REQUEST['observaciones'],'','','','',$_REQUEST['origenSolicitud']);
		return true;
	}

	function HallarTejidosPatologia($codigoBus,$procedimientoBus){
    list($dbconn) = GetDBconn();
    $query="SELECT tejido_id,descripcion FROM tipos_tejidos";
    $query1="SELECT count(*) FROM tipos_tejidos";
		if($codigoBus){
      $query.=" WHERE tejido_id LIKE '%$codigoBus%'";
			$query1.=" WHERE tejido_id LIKE '%$codigoBus%'";
			$ya=1;
		}
		if($procedimientoBus){
      if($ya==1){
        $query.=" AND descripcion LIKE '%".strtoupper($procedimientoBus)."%'";
			  $query1.=" AND descripcion LIKE '%".strtoupper($procedimientoBus)."%'";
			}else{
        $query.=" WHERE descripcion LIKE '%".strtoupper($procedimientoBus)."%'";
			  $query1.=" WHERE descripcion LIKE '%".strtoupper($procedimientoBus)."%'";
			}
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

	function SeleccionarTejidoPatologia(){
	  if(!$_REQUEST['destino']){
      $_SESSION['PATOLOGIA']['TEJIDOS'][$_REQUEST['codigoTejido']]=$_REQUEST['nombreTejido'];
      $this->SolicitudPatologia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],
		  $_REQUEST['responsableSolicitud'],$_REQUEST['tratamientos'],$_REQUEST['Solicitud'],$_REQUEST['procedimiento'],$_REQUEST['codigoPro'],$_REQUEST['observaciones'],'','','','',$_REQUEST['origenSolicitud']);
		}else{
      $this->FormaRecepcionTejidoPatologico($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['fecha'],
		  $_REQUEST['procedimiento'],$_REQUEST['codigoPro'],$_REQUEST['nombreTejido'],$_REQUEST['codigoTejido']);
		}
		return true;
	}

	function SeleccionTejidos(){
    if($_REQUEST['buscar']){
		  if(!$_REQUEST['destino']){
				$this->BucadorTejidosPatologicos($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],
				$_REQUEST['responsableSolicitud'],$_REQUEST['tratamientos'],$_REQUEST['Solicitud'],$_REQUEST['procedimiento'],$_REQUEST['codigoPro'],$_REQUEST['observaciones'],$_REQUEST['origenSolicitud']);
			}else{
        $this->BucadorTejidosPatologicosBuscador($_REQUEST['destino'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],
				$_REQUEST['fecha'],$_REQUEST['procedimiento'],$_REQUEST['codigoPro'],$_REQUEST['tejido'],$_REQUEST['codigoTejido']);
			}
			return true;
		}
		if(!$_REQUEST['destino']){
      $this->SolicitudPatologia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],
		  $_REQUEST['responsableSolicitud'],$_REQUEST['tratamientos'],$_REQUEST['Solicitud'],$_REQUEST['procedimiento'],$_REQUEST['codigoPro'],$_REQUEST['observaciones'],'','','','',$_REQUEST['origenSolicitud']);
		}else{
      $this->FormaRecepcionTejidoPatologico($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['fecha'],
		  $_REQUEST['procedimiento'],$_REQUEST['codigoPro']);
		}
		return true;
	}

	function LlamaBucadorTejidosPatologicos(){
	  $this->BucadorTejidosPatologicos($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['ubicacionPaciente'],
    $_REQUEST['responsableSolicitud'],$_REQUEST['tratamientos'],$_REQUEST['Solicitud'],$_REQUEST['procedimiento'],$_REQUEST['codigoPro'],$_REQUEST['observaciones']);
		return true;
	}

	function LlamaBucadorTejidosPatologicosBuscador(){
	  $this->BucadorTejidosPatologicosBuscador($_REQUEST['destino'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['fecha'],$_REQUEST['procedimiento'],$_REQUEST['codigoPro'],$_REQUEST['tejido'],$_REQUEST['codigoTejido']);
		return true;
	}


	function RecepcionTejidoPatologico(){
    $this->FormaRecepcionTejidoPatologico();
		return true;
	}

	function FiltroBusquedaSolicitudes(){
    if($_REQUEST['Menu']){
      $this->MenuPrincipal();
			return true;
		}
		if($_REQUEST['buscarPro']){
      $this->FormaBusquedaProcedimientosSolicitud($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['fecha'],$_REQUEST['procedimiento'],$_REQUEST['codigoPro'],$_REQUEST['tejido'],$_REQUEST['codigoTejido']);
			return true;
		}
		if($_REQUEST['buscarTejido']){
      $this->BucadorTejidosPatologicosBuscador(1,$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['fecha'],$_REQUEST['procedimiento'],$_REQUEST['codigoPro'],$_REQUEST['tejido'],$_REQUEST['codigoTejido']);
			return true;
		}
		$this->FormaRecepcionTejidoPatologico($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['fecha'],
		$_REQUEST['procedimiento'],$_REQUEST['codigoPro'],$_REQUEST['tejido'],$_REQUEST['codigoTejido'],$_REQUEST['estadosolicitudes']);
		return true;
	}

	function SeleccionProcedimientoBuscador(){
	  if($_REQUEST['Salir']){
      $this->FormaRecepcionTejidoPatologico($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['fecha'],
		  '','',$_REQUEST['tejido'],$_REQUEST['codigoTejido']);
		  return true;
		}
    $this->FormaBusquedaProcedimientosSolicitud($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],$_REQUEST['fecha'],$_REQUEST['procedimiento'],$_REQUEST['codigoPro'],$_REQUEST['tejido'],$_REQUEST['codigoTejido']);
		return true;
	}

	function SolicitudesPatologicas($TipoDocumento,$Documento,$departamento,$fecha,$codigoPro,$codigoTejido,$estadosolicitudes){

    list($dbconn) = GetDBconn();
    $query="SELECT a.patologia_solicitud_id,c.tejido_id,c.estado,a.tipo_id_paciente,a.paciente_id,b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
    date(a.fecha_registro) as fecha,d.descripcion as tejido,dpto.descripcion as departamento,a.origen_solicitud
    FROM patologias_solicitudes a
		LEFT JOIN departamentos dpto ON(dpto.departamento=a.departamento),
		pacientes b,patologias_solicitudes_detalle c
		LEFT JOIN patologias_solicitudes_detalle_informes l ON (l.patologia_solicitud_id=c.patologia_solicitud_id AND l.tejido_id=c.tejido_id)
		,tipos_tejidos d
    WHERE a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND
    a.patologia_solicitud_id=c.patologia_solicitud_id AND c.tejido_id=d.tejido_id";
		$query1="SELECT count(*)
    FROM patologias_solicitudes a,
		pacientes b,patologias_solicitudes_detalle c
		LEFT JOIN patologias_solicitudes_detalle_informes l ON (l.patologia_solicitud_id=c.patologia_solicitud_id AND l.tejido_id=c.tejido_id)
		,tipos_tejidos d
    WHERE a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND
    a.patologia_solicitud_id=c.patologia_solicitud_id AND c.tejido_id=d.tejido_id";

		if($TipoDocumento && $Documento){
      $query.=" AND a.tipo_id_paciente='$TipoDocumento' AND a.paciente_id='$Documento'";
			$query1.=" AND a.tipo_id_paciente='$TipoDocumento' AND a.paciente_id='$Documento'";
		}
		if($departamento!=-1 && $departamento){
      $query.=" AND a.departamento='$departamento'";
			$query1.=" AND a.departamento='$departamento'";
		}
		if($fecha){
		  $fecha=ereg_replace("-","/",$fecha);
      (list($dia,$mes,$ano)=explode('/',$fecha));
      $query.=" AND date(a.fecha_registro)='".$ano.'-'.$mes.'-'.$dia."'";
			$query1.=" AND date(a.fecha_registro)='".$ano.'-'.$mes.'-'.$dia."'";
		}
		if($codigoPro){
      $query.=" AND a.procedimiento='$codigoPro'";
			$query1.=" AND a.procedimiento='$codigoPro'";
		}
		if($codigoTejido){
      $query.=" AND c.tejido_id='$codigoTejido'";
			$query1.=" AND c.tejido_id='$codigoTejido'";
		}
		if($estadosolicitudes==1){
      $query.=" AND c.estado='1'";
			$query1.=" AND c.estado='1'";
		}elseif($estadosolicitudes==2){
      $query.=" AND c.estado='2' AND l.resultado_informe_id is null";
			$query1.=" AND c.estado='2' AND l.resultado_informe_id is null";
		}elseif($estadosolicitudes==3){
      $query.=" AND c.estado='2' AND l.resultado_informe_id is not null";
			$query1.=" AND c.estado='2' AND l.resultado_informe_id is not null";
		}else{
      $query.=" ORDER BY c.estado,l.resultado_informe_id DESC";
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
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}
		return $vars;
	}

	function ConfirmacionLlegadaTejido(){
	  if($_REQUEST['modificacion']==1){
      list($dbconn) = GetDBconn();
			$query="SELECT observaciones,inadecuada
			FROM patologias_solicitudes_confirmadas
			WHERE patologia_solicitud_id='".$_REQUEST['solicitud']."' AND tejido_id='".$_REQUEST['tejidoId']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				$vars=$result->GetRowAssoc($toUpper=false);
			}
			$_REQUEST['inadecuada']=$vars['inadecuada'];
			$_REQUEST['grupoPatologico']=$vars['patologia_grupo_id'];
			$_REQUEST['observaciones']=$vars['observaciones'];

			$query="SELECT a.procedimiento,b.descripcion
			FROM patologias_tejidos_confirmados_procedimientos a,cups b
			WHERE a.patologia_solicitud_id='".$_REQUEST['solicitud']."' AND a.tejido_id='".$_REQUEST['tejidoId']."' AND
			a.procedimiento=b.cargo";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				while(!$result->EOF){
					$_SESSION['PATOLOGIA_CLASIFICACION']['PROCEDIMIENTOS'][$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
				}
			}
		}
    $this->FormaConfirmacionLlegadaTejido($_REQUEST['solicitud'],$_REQUEST['tejidoId'],$_REQUEST['nomTejido'],$_REQUEST['tipoId'],
		$_REQUEST['PacienteId'],$_REQUEST['nombre'],$_REQUEST['fecha'],$_REQUEST['modificacion']);
		return true;
	}

	function GuardaConfirmacionTejido(){
    if($_REQUEST['cancelar']){
		  unset($_SESSION['PATOLOGIA_CLASIFICACION']['PROCEDIMIENTOS']);
      $this->FormaRecepcionTejidoPatologico();
			return true;
		}

		if($_REQUEST['buscarProc']){
      $this->FormaBusquedaProcedimientosMuestras($_REQUEST['solicitud'],$_REQUEST['tejidoId'],$_REQUEST['nomTejido'],$_REQUEST['tipoId'],
		  $_REQUEST['PacienteId'],$_REQUEST['nombre'],$_REQUEST['fecha'],$_REQUEST['modificacion'],$_REQUEST['inadecuada'],$_REQUEST['observaciones']);
			return true;
		}

		/*if($_REQUEST['grupoPatologico']==-1){
		  $this->frmError["MensajeError"]="Es Obligatorio Clasificar la Muestra en un Grupo Patologico";
      $this->FormaConfirmacionLlegadaTejido($_REQUEST['solicitud'],$_REQUEST['tejidoId'],$_REQUEST['nomTejido'],$_REQUEST['tipoId'],
			$_REQUEST['PacienteId'],$_REQUEST['nombre'],$_REQUEST['fecha'],$_REQUEST['modificacion']);
			return true;
		}*/
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		if(sizeof($_SESSION['PATOLOGIA_CLASIFICACION']['PROCEDIMIENTOS'])<1){
		  $this->frmError["MensajeError"]="Especifique procedimiento(s) para la muestra";
      $this->FormaConfirmacionLlegadaTejido($_REQUEST['solicitud'],$_REQUEST['tejidoId'],$_REQUEST['nomTejido'],$_REQUEST['tipoId'],
		  $_REQUEST['PacienteId'],$_REQUEST['nombre'],$_REQUEST['fecha'],$_REQUEST['modificacion']);
			return true;
		}
		if($_REQUEST['modificacion']!=1){
			$query="UPDATE patologias_solicitudes_detalle
			SET estado='2'
			WHERE patologia_solicitud_id='".$_REQUEST['solicitud']."' AND tejido_id='".$_REQUEST['tejidoId']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				$query="INSERT INTO patologias_solicitudes_confirmadas(patologia_solicitud_id,tejido_id,inadecuada,observaciones,fecha_registro)
				VALUES('".$_REQUEST['solicitud']."','".$_REQUEST['tejidoId']."','".$_REQUEST['inadecuada']."','".$_REQUEST['observaciones']."','".date("Y-m-d H:i:s")."')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
				  $procedimientos=$_SESSION['PATOLOGIA_CLASIFICACION']['PROCEDIMIENTOS'];
					foreach($procedimientos as $codigo=>$vector){
					  foreach($vector as $indice=>$nombrePro){
							$query="INSERT INTO patologias_tejidos_confirmados_procedimientos(patologia_solicitud_id,tejido_id,procedimiento)
							VALUES('".$_REQUEST['solicitud']."','".$_REQUEST['tejidoId']."','$codigo')";
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
				$dbconn->CommitTrans();
			}
		}else{
      $query="UPDATE patologias_solicitudes_confirmadas
			SET tejido_id='".$_REQUEST['tejidoId']."',inadecuada='".$_REQUEST['inadecuada']."',observaciones='".$_REQUEST['observaciones']."'
			WHERE patologia_solicitud_id='".$_REQUEST['solicitud']."' AND tejido_id='".$_REQUEST['tejidoId']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
			  $query="DELETE FROM patologias_tejidos_confirmados_procedimientos WHERE patologia_solicitud_id='".$_REQUEST['solicitud']."' AND tejido_id='".$_REQUEST['tejidoId']."'";
        $result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
					$procedimientos=$_SESSION['PATOLOGIA_CLASIFICACION']['PROCEDIMIENTOS'];
					foreach($procedimientos as $codigo=>$vector){
					  foreach($vector as $indice=>$nombrePro){
							$query="INSERT INTO patologias_tejidos_confirmados_procedimientos(patologia_solicitud_id,tejido_id,procedimiento)
							VALUES('".$_REQUEST['solicitud']."','".$_REQUEST['tejidoId']."','$codigo')";
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
		}
		unset($_SESSION['PATOLOGIA_CLASIFICACION']['PROCEDIMIENTOS']);
		$this->FormaRecepcionTejidoPatologico();
		return true;
	}

	function gruposPatologicos(){

		list($dbconn) = GetDBconn();
		$query = "SELECT patologia_grupo_id,descripcion
		FROM patologias_grupos";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
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

	function Tipo_Usuario_Log(){
    list($dbconn) = GetDBconn();
		$query = "SELECT * FROM userpermisos_profesionales_patologias WHERE usuario_id='".UserGetUID()."'";
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
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

	function LlamaInsercionResultadoPat(){
	  if($_REQUEST['informe']){
		  list($dbconn) = GetDBconn();
		  $query = "SELECT a.descripcion_macroscopica,a.descripcion_microscopica,a.examen_firmado,
			b.diagnostico,c.diagnostico_nombre,e.nombre,d.usuario_id_firma
			FROM patologias_resultados_solicitudes a
			LEFT JOIN patologias_resultados_solicitudes_diagnosticos b ON(a.resultado_informe_id=b.resultado_informe_id)
			LEFT JOIN diagnosticos c ON(b.diagnostico=c.diagnostico_id)
      LEFT JOIN profesionales_usuarios d ON(a.usuario_id=d.usuario_id_firma)
			LEFT JOIN profesionales e ON(d.tipo_tercero_id=e.tipo_id_tercero AND d.tercero_id=e.tercero_id)
			WHERE a.resultado_informe_id='".$_REQUEST['informe']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
        while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$descripcion_macro=$vars[0]['descripcion_macroscopica'];
		$descripcion_micro=$vars[0]['descripcion_microscopica'];
		$firma=$vars[0]['examen_firmado'];
		$nombreProfesional=$vars[0]['nombre'];
		$usuarioFirma=$vars[0]['usuario_id_firma'];
		for($i=0;$i<sizeof($vars);$i++){
      $_SESSION['PATOLOGIA']['RESULTADO'][$vars[$i]['diagnostico']]=$vars[$i]['diagnostico_nombre'];
		}
    $this->InsercionResultadoPat($_REQUEST['solicitud'],$_REQUEST['nomgrupo'],$_REQUEST['grupo'],$_REQUEST['tipoId'],$_REQUEST['PacienteId'],$_REQUEST['nombre'],$_REQUEST['fecha'],$descripcion_macro,$descripcion_micro,$firma,$_REQUEST['informe'],$_REQUEST['consulta'],$nombreProfesional);
		return true;
	}

	function GruposSolicitud($solicitud){
    list($dbconn) = GetDBconn();
		$query = "SELECT DISTINCT c.patologia_grupo_id,c.descripcion,b.resultado_informe_id,x.examen_firmado
		FROM patologias_solicitudes_confirmadas a,patologias_solicitudes_detalle b
		LEFT JOIN patologias_resultados_solicitudes x ON(x.resultado_informe_id=b.resultado_informe_id),
		patologias_grupos c
		WHERE a.patologia_solicitud_id='$solicitud' AND a.patologia_solicitud_id=b.patologia_solicitud_id AND
		a.tejido_id=b.tejido_id AND (b.estado='2' OR b.estado='3') AND a.patologia_grupo_id=c.patologia_grupo_id";
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

	function TejidosSolicitud($solicitud,$grupo){
    list($dbconn) = GetDBconn();
		$query = "SELECT c.tejido_id,c.descripcion as nomtejido
		FROM patologias_solicitudes_detalle a,patologias_solicitudes_confirmadas b,tipos_tejidos c
		WHERE a.patologia_solicitud_id='$solicitud' AND a.estado='2' AND a.patologia_solicitud_id=b.patologia_solicitud_id AND
		a.tejido_id=b.tejido_id AND b.patologia_grupo_id='$grupo' AND b.tejido_id=c.tejido_id";
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

	function GuardarResultadoPatologia(){
    /*if($_REQUEST['Regresar']){
		  unset($_SESSION['PATOLOGIA']['RESULTADO']);
      $this->FormaRecepcionTejidoPatologico();
			return true;
		}
		if($_REQUEST['BuscarDiag']){
      $this->FormaBuscadorDiagnosticoResultado($_REQUEST['solicitud'],$_REQUEST['nomgrupo'],$_REQUEST['grupo'],$_REQUEST['tipoId'],$_REQUEST['PacienteId'],$_REQUEST['nombre'],$_REQUEST['fecha'],$_REQUEST['descripcion_macro'],$_REQUEST['descripcion_micro'],$_REQUEST['firma'],$_REQUEST['NoInforme']);
			return true;
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		if($_REQUEST['firma']==1){
      $firma=1;
		}else{
      $firma=0;
		}
		if(!$_REQUEST['NoInforme']){
		$query="SELECT nextval('patologias_resultados_solicitudes_resultado_informe_id_seq')";
    $result = $dbconn->Execute($query);
		$NoInforme=$result->fields[0];
		$query = "INSERT INTO patologias_resultados_solicitudes(resultado_informe_id,patologia_solicitud_id,patologia_grupo_id,
		descripcion_macroscopica,descripcion_microscopica,fecha_registro,usuario_id,examen_firmado)
		VALUES('$NoInforme','".$_REQUEST['solicitud']."','".$_REQUEST['grupo']."','".$_REQUEST['descripcion_macro']."',
		'".$_REQUEST['descripcion_micro']."','".date("Y-m-d H:i:s")."','".UserGetUID()."','$firma')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
		  $query="UPDATE patologias_solicitudes_detalle SET resultado_informe_id='$NoInforme' WHERE patologia_solicitud_id='".$_REQUEST['solicitud']."' AND estado='2' AND resultado_informe_id is null";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				foreach($_SESSION['PATOLOGIA']['RESULTADO'] as $codigo=>$nombre){
					$query="INSERT INTO patologias_resultados_solicitudes_diagnosticos(resultado_informe_id,diagnostico)VALUES('$NoInforme','$codigo')";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
				$dbconn->CommitTrans();
				$mensaje='Resultado Guardado Correctamente con el numero de Informe'.'  '.$NoInforme;
			}
		}
		}else{
      $query = "UPDATE patologias_resultados_solicitudes SET
			descripcion_macroscopica='".$_REQUEST['descripcion_macro']."',descripcion_microscopica='".$_REQUEST['descripcion_micro']."',examen_firmado='$firma'
			WHERE resultado_informe_id='".$_REQUEST['NoInforme']."' AND patologia_solicitud_id='".$_REQUEST['solicitud']."' AND
			patologia_grupo_id='".$_REQUEST['grupo']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
        $query = "DELETE FROM patologias_resultados_solicitudes_diagnosticos WHERE resultado_informe_id='".$_REQUEST['NoInforme']."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
          foreach($_SESSION['PATOLOGIA']['RESULTADO'] as $codigo=>$nombre){
						$query="INSERT INTO patologias_resultados_solicitudes_diagnosticos(resultado_informe_id,diagnostico)VALUES('".$_REQUEST['NoInforme']."','$codigo')";
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}
					$dbconn->CommitTrans();
					$mensaje='Resultado No.'.' '.$_REQUEST['NoInforme'].' '.'Modificado Correctamente';
				}
			}
		}
		unset($_SESSION['PATOLOGIA']['RESULTADO']);
		$titulo='RESULTADOS PATOLOGIA';
		$accion=ModuloGetURL('app','Patologia','user','MenuPrincipal');
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		*/
		return true;
	}

	function LlamaFormaBuscadorDiagnosticoResultado(){
    $this->FormaBuscadorDiagnosticoResultado($_REQUEST['observacionesInforme'],$_REQUEST['firma'],$_REQUEST['patologoProfe']);
		return true;
	}

	function EliminaDiagPatologiaResultado(){
    unset($_SESSION['PATOLOGIA']['RESULTADO'][$_REQUEST['codigo']]);
		$this->InformeResultadoPatologico($_REQUEST['observacionesInforme'],$_REQUEST['firma'],'','','','',$_REQUEST['patologoProfe']);
		return true;
	}

	function IncinerarTejidoMuestra(){
    $this->FormaIncinerarTejidoMuestra($_REQUEST['solicitud'],$_REQUEST['tejidoId'],$_REQUEST['tipoId'],$_REQUEST['PacienteId'],$_REQUEST['nombre'],$_REQUEST['fecha'],$_REQUEST['nomTejido']);
		return true;
	}

	function GuardarIncineracionTejido(){
	  if($_REQUEST['Regresar']){
      $this->FormaRecepcionTejidoPatologico();
			return true;
		}
    list($dbconn) = GetDBconn();
		$query="INSERT INTO patologias_tejidos_incinerados(patologia_solicitud_id,tejido_id,observaciones,usuario_id,fecha_registro)
		VALUES('".$_REQUEST['solicitud']."','".$_REQUEST['tejidoId']."','".$_REQUEST['observaciones']."','".UserGetUID()."','".date("Y-m-d H:i:s")."')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $query="UPDATE patologias_solicitudes_detalle SET estado='3' WHERE patologia_solicitud_id='".$_REQUEST['solicitud']."' AND tejido_id='".$_REQUEST['tejidoId']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		$this->FormaRecepcionTejidoPatologico();
		return true;
	}

	function LlamaEntregaCadaver(){
    $this->FormaEntregaCadaver($_REQUEST['solicitud'],$_REQUEST['tejidoId'],$_REQUEST['tipoId'],$_REQUEST['PacienteId'],$_REQUEST['nombre'],$_REQUEST['fecha'],$_REQUEST['nomTejido']);
		return true;
	}

	function LlamaCreacionInformesPatologia(){
    $this->CreacionInformesPatologia();
		return true;
	}

	function FiltroSolicitudesConfirmadas(){
    if($_REQUEST['Menu']){
      $this->MenuPrincipal();
			return true;
		}
		if($_REQUEST['crearInforme']){
		  foreach($_REQUEST['TipoInforme'] as $solicitud=>$valor){
        if($valor!=-1){
          $solicitudInforme=$solicitud;
          $ValorInforme=$valor;
				}
			}
      if(empty($solicitudInforme) && empty($ValorInforme)){
        $this->frmError["MensajeError"]="Elija algun tipo de Informe";
        $this->CreacionInformesPatologia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['noSolicitud'],$_REQUEST['fecha'],$_REQUEST['noinforme'],$_REQUEST['prefijo'],$_REQUEST['todasFechas']);
		    return true;
			}
			(list($grupoTipoCargo,$TipoCargo,$prefijo,$infoId,$nombreInfo)=explode('||//',$ValorInforme));
			$_SESSION['DATOS_PATOLOGIA']['PREFIJO']=$prefijo;
			$_SESSION['DATOS_PATOLOGIA']['INFORME']=$infoId;
			$_SESSION['DATOS_PATOLOGIA']['GRUPO']=$grupoTipoCargo;
			$_SESSION['DATOS_PATOLOGIA']['TIPO']=$TipoCargo;
			$_SESSION['DATOS_PATOLOGIA']['NOMBREINFO']=$nombreInfo;
			$_SESSION['DATOS_PATOLOGIA']['SOLICITUD']=$solicitudInforme;
      list($dbconn) = GetDBconn();
		  $query="SELECT a.procedimiento,b.descripcion
			FROM patologias_tejidos_confirmados_procedimientos a,cups b
			WHERE a.patologia_solicitud_id='".$_SESSION['DATOS_PATOLOGIA']['SOLICITUD']."' AND a.procedimiento=b.cargo AND
			b.grupo_tipo_cargo='$grupoTipoCargo' AND b.tipo_cargo='$TipoCargo'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				while(!$result->EOF){
				  $size=sizeof($_SESSION['PATOLOGIA']['PROCEDIMIENTOS'][$result->fields[0]]);
				  $_SESSION['PATOLOGIA']['PROCEDIMIENTOS'][$result->fields[0]][$size]=$result->fields[1];
					$result->MoveNext();
				}
			}
      $this->InformeResultadoPatologico();
			return true;
		}
		$this->CreacionInformesPatologia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['noSolicitud'],$_REQUEST['fecha'],$_REQUEST['noinforme'],$_REQUEST['prefijo'],$_REQUEST['todasFechas']);
		return true;
	}

	function SolicitudesConfirmadas($TipoDocumento,$Documento,$noSolicitud,$fecha,$noinforme,$prefijo,$todasFechas){

		list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT a.patologia_solicitud_id,b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nompaciente,
		date(b.fecha_registro),a.tipo_id_paciente,a.paciente_id
		FROM patologias_solicitudes a,pacientes b,patologias_solicitudes_detalle_informes c
		WHERE a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND a.patologia_solicitud_id=c.patologia_solicitud_id";

		$query1="SELECT DISTINCT count(*)
		FROM patologias_solicitudes a,pacientes b,patologias_solicitudes_detalle_informes c
		WHERE a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND a.patologia_solicitud_id=c.patologia_solicitud_id";

    if($TipoDocumento && $Documento){
      $query.=" AND a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."' AND  a.paciente_id='".$_REQUEST['Documento']."'";
			$query1.=" AND a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."' AND  a.paciente_id='".$_REQUEST['Documento']."'";
		}
		if($noSolicitud){
      $query.=" AND a.patologia_solicitud_id='".$_REQUEST['noSolicitud']."'";
			$query1.=" AND a.patologia_solicitud_id='".$_REQUEST['noSolicitud']."'";
		}
		if($todasFechas!=1){
			if($fecha){
				$fecha=ereg_replace("-","/",$fecha);
				(list($dia,$mes,$ano)=explode('/',$fecha));
				$query.=" AND date(a.fecha_registro)='".$ano.'-'.$mes.'-'.$dia."'";
				$query1.=" AND date(a.fecha_registro)='".$ano.'-'.$mes.'-'.$dia."'";
			}
		}
		if($noinforme){
		  $query.=" AND c.resultado_informe_id='$noinforme'";
			$query1.=" AND c.resultado_informe_id='$noinforme'";
		}
		if($prefijo!=-1 && !empty($prefijo)){
		  $query.=" AND c.prefijo='$prefijo'";
			$query1.=" AND c.prefijo='$prefijo'";
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
		$query.=" ORDER BY a.patologia_solicitud_id";
		$query.=" LIMIT " . $this->limit . " OFFSET $Of";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[$result->fields[0]][$result->fields[3]]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}
		return $vars;
	}

	function tiposGruposPatologicos($solicitud){
		list($dbconn) = GetDBconn();
    $query="SELECT DISTINCT a.grupo_tipo_cargo,a.tipo_cargo,b.descripcion,c.sw_prefijo,a.prefijo,a.resultado_informe_id
		FROM
		(SELECT a.patologia_solicitud_id,a.resultado_informe_id,a.prefijo
		FROM patologias_solicitudes_detalle_informes a
		WHERE a.patologia_solicitud_id='$solicitud'
		EXCEPT
		SELECT b.patologia_solicitud_id,b.resultado_informe_id,b.prefijo
		FROM patologias_resultados_solicitudes b
		WHERE b.patologia_solicitud_id='$solicitud') as aa, patologias_solicitudes_detalle_informes a,tipos_cargos b,patologias_tipos_cargos c
		WHERE aa.patologia_solicitud_id=a.patologia_solicitud_id AND aa.resultado_informe_id=a.resultado_informe_id AND aa.prefijo=a.prefijo AND a.tipo_cargo=b.tipo_cargo AND
		a.grupo_tipo_cargo=b.grupo_tipo_cargo AND a.tipo_cargo=c.tipo_cargo AND a.grupo_tipo_cargo=c.grupo_tipo_cargo
		ORDER BY b.descripcion";
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

	function DatosSolicitud(){
    list($dbconn) = GetDBconn();
		$query = "SELECT a.tipo_id_paciente,a.paciente_id,date(a.fecha_registro) as fecha,
		b.fecha_nacimiento,b.primer_nombre||' '||b.segundo_nombre||' '||primer_apellido||' '||segundo_apellido as nombrepac,d.descripcion as nomtejido
		FROM patologias_solicitudes a,pacientes b,patologias_solicitudes_detalle_informes c,tipos_tejidos d
		WHERE a.patologia_solicitud_id='".$_SESSION['DATOS_PATOLOGIA']['SOLICITUD']."' AND
		a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND a.patologia_solicitud_id=c.patologia_solicitud_id AND
		c.resultado_informe_id='".$_SESSION['DATOS_PATOLOGIA']['INFORME']."' AND c.prefijo='".$_SESSION['DATOS_PATOLOGIA']['PREFIJO']."' AND c.tipo_cargo='".$_SESSION['DATOS_PATOLOGIA']['TIPO']."' AND c.grupo_tipo_cargo='".$_SESSION['DATOS_PATOLOGIA']['GRUPO']."' AND
		c.tejido_id=d.tejido_id ";
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

	function GuardarInformePatologico(){

    if($_REQUEST['Regresar']){
      unset($_SESSION['DATOS_PATOLOGIA']['PREFIJO']);
			unset($_SESSION['DATOS_PATOLOGIA']['INFORME']);
			unset($_SESSION['DATOS_PATOLOGIA']['GRUPO']);
			unset($_SESSION['DATOS_PATOLOGIA']['TIPO']);
			unset($_SESSION['DATOS_PATOLOGIA']['NOMBREINFO']);
			unset($_SESSION['DATOS_PATOLOGIA']['SOLICITUD']);
			unset($_SESSION['PATOLOGIA']['RESULTADO']);
			unset($_SESSION['PATOLOGIA']['PROCEDIMIENTOS']);
			unset($_SESSION['PATOLOGO']['MODIFICACION']);
      $this->CreacionInformesPatologia();
			return true;
		}
		if($_REQUEST['AdicionarPlan']){
      list($dbconn) = GetDBconn();
			if($_REQUEST['Plantilla']!=-1){
        $query = "SELECT contenido_plantilla FROM patologias_tipos_plantillas WHERE plantilla_id='".$_REQUEST['Plantilla']."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}else{
          $datos=$result->RecordCount();
					if($datos){
            $_REQUEST['observacionesInforme'].="\x0a".$result->fields[0];
					}
				}
			}
			$this->InformeResultadoPatologico($_REQUEST['observacionesInforme'],$_REQUEST['firma'],$_REQUEST['consulta'],$_REQUEST['profesional'],$_REQUEST['usuariofirma'],$_REQUEST['observacionesAdicionales'],$_REQUEST['patologoProfe']);
			return true;
		}

		if($_REQUEST['AdicionarPlanAdicional']){
      list($dbconn) = GetDBconn();
			if($_REQUEST['PlantillaAdicional']!=-1){
        $query = "SELECT contenido_plantilla FROM patologias_tipos_plantillas WHERE plantilla_id='".$_REQUEST['PlantillaAdicional']."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}else{
          $datos=$result->RecordCount();
					if($datos){
            $_REQUEST['observacionesAdicionales'].="\x0a".$result->fields[0];
					}
				}
			}
			$this->InformeResultadoPatologico($_REQUEST['observacionesInforme'],$_REQUEST['firma'],$_REQUEST['consulta'],$_REQUEST['profesional'],$_REQUEST['usuariofirma'],$_REQUEST['observacionesAdicionales'],$_REQUEST['patologoProfe']);
			return true;
		}

		if($_REQUEST['BuscarDiag']){
      $this->FormaBuscadorDiagnosticoResultado($_REQUEST['observacionesInforme'],$_REQUEST['firma'],$_REQUEST['patologoProfe']);
			return true;
		}
		if($_REQUEST['buscarPro']){
      $this->FormaBusquedaProcedimientos($_REQUEST['observacionesInforme'],$_REQUEST['firma'],$_REQUEST['filtrogrupoTipoCargo'],$_REQUEST['filtroTipoCargo'],$_REQUEST['patologoProfe']);
			return true;
		}
    foreach($_REQUEST as $nombre=>$valor){
		  if($nombre!='modulo' && $nombre!='metodo' && $nombre!='SIIS_SID'){
        $vector[$nombre]=$valor;
			}
		}
		$titulo='CONFIRMACION DEL ALMACENAMIENTO DE LOS DATOS';
		$mensaje='Desea Confirmar el Almacenamiento de los Datos del Informe '.$_SESSION['DATOS_PATOLOGIA']['PREFIJO'].' '.$_SESSION['DATOS_PATOLOGIA']['INFORME'];
		$accionAceptar=ModuloGetURL('app','Patologia','user','GuardarRedultadoPatologico',$vector);
		$accionCancelar=ModuloGetURL('app','Patologia','user','VolverPantallaResuladoPatologia',$vector);
		$this->FormaConfirmacionDatos($mensaje,$titulo,$accionAceptar,$accionCancelar);
		return true;
	}

	function VolverPantallaResuladoPatologia(){
    $this->InformeResultadoPatologico($_REQUEST['observacionesInforme'],$_REQUEST['firma'],$_REQUEST['consulta'],$_REQUEST['profesional'],$_REQUEST['usuariofirma'],$_REQUEST['observacionesAdicionales'],$_REQUEST['patologoProfe'],$_REQUEST['Observas']);
		return true;
	}

	function GuardarRedultadoPatologico(){

    if($_REQUEST['firma']){
      $firma=1;
			$usuariofirma="'".UserGetUID()."'";
		}else{
      $firma=0;
			$usuariofirma='NULL';
		}
    list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		if(empty($_REQUEST['patologoProfe'])){
		  $tipoUser=$this->Tipo_Usuario_Log();
		  if($tipoUser==1){
				$query="SELECT tipo_tercero_id,tercero_id FROM profesionales_usuarios WHERE usuario_id='".UserGetUID()."'";
				$result = $dbconn->Execute($query);
				$tipoIdPro="'".$result->fields[0]."'";
				$IdProf="'".$result->fields[1]."'";
			}else{
        $tipoIdPro='NULL';
			  $IdProf='NULL';
			}
		}elseif($_REQUEST['patologoProfe']!=-1){
      (list($tipoIdPro,$IdProf)=explode('||//',$_REQUEST['patologoProfe']));
			$tipoIdPro="'".$tipoIdPro."'";
			$IdProf="'".$IdProf."'";
		}else{
      $tipoIdPro='NULL';
			$IdProf='NULL';
		}
		if(!$_SESSION['PATOLOGO']['MODIFICACION']){
			$query = "INSERT INTO patologias_resultados_solicitudes(resultado_informe_id,patologia_solicitud_id,observaciones,
			fecha_registro,usuario_id,examen_firmado,tipo_cargo,grupo_tipo_cargo,prefijo,entregado,usuario_id_firma,
			tipo_id_tercero,tercero_id)
			VALUES('".$_SESSION['DATOS_PATOLOGIA']['INFORME']."','".$_SESSION['DATOS_PATOLOGIA']['SOLICITUD']."','".$_REQUEST['observacionesInforme']."',
			'".date("Y-m-d H:i:s")."','".UserGetUID()."','$firma','".$_SESSION['DATOS_PATOLOGIA']['TIPO']."','".$_SESSION['DATOS_PATOLOGIA']['GRUPO']."','".$_SESSION['DATOS_PATOLOGIA']['PREFIJO']."',
			'0',$usuariofirma,$tipoIdPro,$IdProf)";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				foreach($_SESSION['PATOLOGIA']['PROCEDIMIENTOS'] as $codigo=>$vector){
          foreach($vector as $indice=>$nombre){
						$query="INSERT INTO patologias_resultados_solicitudes_procedimientos(resultado_informe_id,prefijo,procedimiento)VALUES('".$_SESSION['DATOS_PATOLOGIA']['INFORME']."','".$_SESSION['DATOS_PATOLOGIA']['PREFIJO']."','$codigo')";
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}
				}
				foreach($_SESSION['PATOLOGIA']['RESULTADO'] as $codigo=>$nombre){
					$query="INSERT INTO patologias_resultados_solicitudes_diagnosticos(resultado_informe_id,prefijo,diagnostico)VALUES('".$_SESSION['DATOS_PATOLOGIA']['INFORME']."','".$_SESSION['DATOS_PATOLOGIA']['PREFIJO']."','$codigo')";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
				$dbconn->CommitTrans();
				if(!empty($_SESSION['DATOS_PATOLOGIA']['PREFIJO'])){
				$mensaje="Informe No. ".$_SESSION['DATOS_PATOLOGIA']['PREFIJO'].' '.$_SESSION['DATOS_PATOLOGIA']['INFORME']." fue Guardado Satisfactoriamente";
				}else{
        $mensaje="Informe No. ".$_SESSION['DATOS_PATOLOGIA']['INFORME']." fue Guardado Satisfactoriamente";
				}
				$titulo='RESULTADOS PATOLOGIA';
				$accion=ModuloGetURL('app','Patologia','user','MenuPrincipal');
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton,1,$_SESSION['DATOS_PATOLOGIA']['PREFIJO'],$_SESSION['DATOS_PATOLOGIA']['INFORME'],$firma);
				unset($_SESSION['DATOS_PATOLOGIA']['PREFIJO']);
				unset($_SESSION['DATOS_PATOLOGIA']['INFORME']);
				unset($_SESSION['DATOS_PATOLOGIA']['GRUPO']);
				unset($_SESSION['DATOS_PATOLOGIA']['TIPO']);
				unset($_SESSION['DATOS_PATOLOGIA']['NOMBREINFO']);
				unset($_SESSION['DATOS_PATOLOGIA']['SOLICITUD']);
				unset($_SESSION['PATOLOGIA']['RESULTADO']);
				unset($_SESSION['PATOLOGIA']['PROCEDIMIENTOS']);
				unset($_SESSION['PATOLOGO']['MODIFICACION']);
				return true;
			}
		}elseif($_REQUEST['consulta']==1){
      if($_REQUEST['observacionesAdicionales']){
        $query="INSERT INTO patologias_resultados_observaciones_adicionales(resultado_informe_id,prefijo,observaciones_adicionales,usuario_id,fecha_registro)
				VALUES('".$_SESSION['DATOS_PATOLOGIA']['INFORME']."','".$_SESSION['DATOS_PATOLOGIA']['PREFIJO']."','".$_REQUEST['observacionesAdicionales']."','".UserGetUID()."','".date("Y-m-d H:i:s")."')";
				/*$query = "UPDATE patologias_resultados_solicitudes SET observaciones_adicionales='".$_REQUEST['observacionesAdicionales']."'
				WHERE resultado_informe_id='".$_SESSION['DATOS_PATOLOGIA']['INFORME']."' AND
				prefijo='".$_SESSION['DATOS_PATOLOGIA']['PREFIJO']."'";
				*/
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				$dbconn->CommitTrans();
				if(!empty($_SESSION['DATOS_PATOLOGIA']['PREFIJO'])){
				$mensaje="Informe No. ".$_SESSION['DATOS_PATOLOGIA']['PREFIJO'].' '.$_SESSION['DATOS_PATOLOGIA']['INFORME']." fue Modificado Satisfactoriamente";
				}else{
				$mensaje="Informe No. ".$_SESSION['DATOS_PATOLOGIA']['INFORME']." fue Modificado Satisfactoriamente";
				}
			}
			$titulo='RESULTADOS PATOLOGIA';
			$accion=ModuloGetURL('app','Patologia','user','MenuPrincipal');
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton,1,$_SESSION['DATOS_PATOLOGIA']['PREFIJO'],$_SESSION['DATOS_PATOLOGIA']['INFORME'],$firma);
			unset($_SESSION['DATOS_PATOLOGIA']['PREFIJO']);
			unset($_SESSION['DATOS_PATOLOGIA']['INFORME']);
			unset($_SESSION['DATOS_PATOLOGIA']['GRUPO']);
			unset($_SESSION['DATOS_PATOLOGIA']['TIPO']);
			unset($_SESSION['DATOS_PATOLOGIA']['NOMBREINFO']);
			unset($_SESSION['DATOS_PATOLOGIA']['SOLICITUD']);
			unset($_SESSION['PATOLOGIA']['RESULTADO']);
			unset($_SESSION['PATOLOGIA']['PROCEDIMIENTOS']);
			unset($_SESSION['PATOLOGO']['MODIFICACION']);
			return true;
		}else{
			$query = "UPDATE patologias_resultados_solicitudes SET observaciones='".$_REQUEST['observacionesInforme']."',
			examen_firmado='$firma',usuario_id_firma=$usuariofirma,
			tipo_id_tercero=$tipoIdPro,tercero_id=$IdProf
			WHERE resultado_informe_id='".$_SESSION['DATOS_PATOLOGIA']['INFORME']."' AND patologia_solicitud_id='".$_SESSION['DATOS_PATOLOGIA']['SOLICITUD']."' AND
			prefijo='".$_SESSION['DATOS_PATOLOGIA']['PREFIJO']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				$query="DELETE FROM  patologias_resultados_solicitudes_procedimientos WHERE resultado_informe_id='".$_SESSION['DATOS_PATOLOGIA']['INFORME']."' AND prefijo='".$_SESSION['DATOS_PATOLOGIA']['PREFIJO']."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				foreach($_SESSION['PATOLOGIA']['PROCEDIMIENTOS'] as $codigo=>$vector){
				  foreach($vector as $indice=>$nombre){
						$query="INSERT INTO patologias_resultados_solicitudes_procedimientos(resultado_informe_id,prefijo,procedimiento)VALUES('".$_SESSION['DATOS_PATOLOGIA']['INFORME']."','".$_SESSION['DATOS_PATOLOGIA']['PREFIJO']."','$codigo')";
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}
				}
				$query="DELETE FROM  patologias_resultados_solicitudes_diagnosticos WHERE resultado_informe_id='".$_SESSION['DATOS_PATOLOGIA']['INFORME']."' AND prefijo='".$_SESSION['DATOS_PATOLOGIA']['PREFIJO']."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				foreach($_SESSION['PATOLOGIA']['RESULTADO'] as $codigo=>$nombre){
					$query="INSERT INTO patologias_resultados_solicitudes_diagnosticos(resultado_informe_id,prefijo,diagnostico)VALUES('".$_SESSION['DATOS_PATOLOGIA']['INFORME']."','".$_SESSION['DATOS_PATOLOGIA']['PREFIJO']."','$codigo')";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
				$dbconn->CommitTrans();
				if(!empty($_SESSION['DATOS_PATOLOGIA']['PREFIJO'])){
				$mensaje="Informe No. ".$_SESSION['DATOS_PATOLOGIA']['PREFIJO'].' '.$_SESSION['DATOS_PATOLOGIA']['INFORME']." fue Modificado Satisfactoriamente";
				}else{
				$mensaje="Informe No. ".$_SESSION['DATOS_PATOLOGIA']['INFORME']." fue Modificado Satisfactoriamente";
				}
				$titulo='RESULTADOS PATOLOGIA';
				$accion=ModuloGetURL('app','Patologia','user','MenuPrincipal');
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton,1,$_SESSION['DATOS_PATOLOGIA']['PREFIJO'],$_SESSION['DATOS_PATOLOGIA']['INFORME'],$firma);
				unset($_SESSION['DATOS_PATOLOGIA']['PREFIJO']);
				unset($_SESSION['DATOS_PATOLOGIA']['INFORME']);
				unset($_SESSION['DATOS_PATOLOGIA']['GRUPO']);
				unset($_SESSION['DATOS_PATOLOGIA']['TIPO']);
				unset($_SESSION['DATOS_PATOLOGIA']['NOMBREINFO']);
				unset($_SESSION['DATOS_PATOLOGIA']['SOLICITUD']);
				unset($_SESSION['PATOLOGIA']['RESULTADO']);
				unset($_SESSION['PATOLOGIA']['PROCEDIMIENTOS']);
				unset($_SESSION['PATOLOGO']['MODIFICACION']);
				return true;
			}
		}
	}

	function EliminaProcPatologiaResultado(){
	  $size=sizeof($_SESSION['PATOLOGIA']['PROCEDIMIENTOS'][$_REQUEST['codigo']]);
    unset($_SESSION['PATOLOGIA']['PROCEDIMIENTOS'][$_REQUEST['codigo']][$size-1]);
		$this->InformeResultadoPatologico($_REQUEST['observacionesInforme'],$_REQUEST['firma'],'','','','',$_REQUEST['patologoProfe']);
		return true;
	}

	function ListaDeInformesRelizados($solicitud){

    list($dbconn) = GetDBconn();
		$query="SELECT a.examen_firmado,a.prefijo,a.resultado_informe_id,c.descripcion as cargo,e.nombre as nomprofesional,a.usuario_id_firma
		FROM patologias_resultados_solicitudes a
    LEFT JOIN profesionales e ON(a.tipo_id_tercero=e.tipo_id_tercero AND a.tercero_id=e.tercero_id)
		,patologias_tipos_cargos b,tipos_cargos c
		WHERE a.patologia_solicitud_id='$solicitud' AND a.grupo_tipo_cargo=b.grupo_tipo_cargo AND a.tipo_cargo=b.tipo_cargo AND a.prefijo=b.prefijo AND
    b.tipo_cargo=c.tipo_cargo AND b.grupo_tipo_cargo=c.grupo_tipo_cargo";
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

	function LlamaConsultaInforme(){

		list($dbconn) = GetDBconn();
    $query="SELECT a.patologia_solicitud_id,c.tipo_cargo,c.grupo_tipo_cargo,c.prefijo,c.sw_prefijo,d.descripcion,b.tejido_id,
		a.observaciones,a.examen_firmado,e.descripcion as nomtejido,a.tipo_id_tercero,a.tercero_id
		FROM patologias_resultados_solicitudes a,patologias_solicitudes_detalle_informes b,patologias_tipos_cargos c,
		tipos_cargos d,tipos_tejidos e
		WHERE a.resultado_informe_id='".$_REQUEST['informeId']."' AND a.prefijo='".$_REQUEST['prefijo']."' AND a.patologia_solicitud_id='".$_REQUEST['solicitud']."' AND
		b.patologia_solicitud_id=b.patologia_solicitud_id AND b.resultado_informe_id=a.resultado_informe_id AND a.prefijo=b.prefijo AND
		a.tipo_cargo=c.tipo_cargo AND a.grupo_tipo_cargo=c.grupo_tipo_cargo AND a.prefijo=c.prefijo AND
		c.tipo_cargo=d.tipo_cargo AND c.grupo_tipo_cargo=d.grupo_tipo_cargo AND
		b.tejido_id=e.tejido_id";
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
		$query="SELECT b.cargo,b.descripcion
		FROM patologias_resultados_solicitudes_procedimientos a,cups b
		WHERE a.resultado_informe_id='".$_REQUEST['informeId']."' AND
		a.prefijo='".$_REQUEST['prefijo']."' AND a.procedimiento=b.cargo";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
		  if($datos){
				while(!$result->EOF){
					$varsProc[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
				for($i=0;$i<sizeof($varsProc);$i++){
				  $size=sizeof($_SESSION['PATOLOGIA']['PROCEDIMIENTOS'][$varsProc[$i]['cargo']]);
          $_SESSION['PATOLOGIA']['PROCEDIMIENTOS'][$varsProc[$i]['cargo']][$size]=$varsProc[$i]['descripcion'];
				}
			}
		}
		$query="SELECT b.diagnostico_id,b.diagnostico_nombre
		FROM patologias_resultados_solicitudes_diagnosticos a,diagnosticos b
		WHERE a.resultado_informe_id='".$_REQUEST['informeId']."' AND
		a.prefijo='".$_REQUEST['prefijo']."' AND a.diagnostico=b.diagnostico_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
		  if($datos){
				while(!$result->EOF){
					$varsDiag[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
				for($i=0;$i<sizeof($varsDiag);$i++){
          $_SESSION['PATOLOGIA']['RESULTADO'][$varsDiag[$i]['diagnostico_id']]=$varsDiag[$i]['diagnostico_nombre'];
				}
			}
		}
    if($_REQUEST['consul']==1){
			$query="SELECT f.observaciones_adicionales,f.fecha_registro,h.nombre
			FROM patologias_resultados_observaciones_adicionales f,profesionales_usuarios g,profesionales h
			WHERE f.resultado_informe_id='".$_REQUEST['informeId']."' AND f.prefijo='".$_REQUEST['prefijo']."' AND
			f.usuario_id=g.usuario_id AND g.tipo_tercero_id=h.tipo_id_tercero AND g.tercero_id=h.tercero_id
			ORDER BY fecha_registro DESC";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				$datos=$result->RecordCount();
				if($datos){
					while(!$result->EOF){
						$varsObser[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
		}
		$observacionesInforme=$vars[0]['observaciones'];
		$firma=$vars[0]['examen_firmado'];
		if($vars[0]['tipo_id_tercero'] && $vars[0]['tercero_id']){
		$patologoProfe=$vars[0]['tipo_id_tercero'].'||//'.$vars[0]['tercero_id'];
		}
		if($_REQUEST['Modify']==1 || $_REQUEST['consul']==1){
      $_SESSION['PATOLOGO']['MODIFICACION']=1;
			$_SESSION['DATOS_PATOLOGIA']['PREFIJO']=$_REQUEST['prefijo'];
			$_SESSION['DATOS_PATOLOGIA']['INFORME']=$_REQUEST['informeId'];
			$_SESSION['DATOS_PATOLOGIA']['GRUPO']=$vars[0]['grupo_tipo_cargo'];
			$_SESSION['DATOS_PATOLOGIA']['TIPO']=$vars[0]['tipo_cargo'];
			$_SESSION['DATOS_PATOLOGIA']['NOMBREINFO']=$vars[0]['descripcion'];
			$_SESSION['DATOS_PATOLOGIA']['SOLICITUD']=$vars[0]['patologia_solicitud_id'];
		}
    $this->InformeResultadoPatologico($observacionesInforme,$firma,$_REQUEST['consul'],$_REQUEST['profesional'],$_REQUEST['usuariofirma'],$observacionesAdicionales,$patologoProfe,$varsObser);
		return true;
	}

	function TotalPatologos(){

		list($dbconn) = GetDBconn();
		$query="SELECT a.nombre,a.tipo_id_tercero,a.tercero_id
		FROM profesionales a
		WHERE a.tipo_profesional='9' AND a.estado=1
		ORDER BY a.nombre";
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

	function HallarTodosCupsPatologia($codigoBus,$procedimientoBus){
    list($dbconn) = GetDBconn();
		$query = "SELECT a.cargo,a.descripcion FROM cups a,patologias_tipos_cargos b WHERE a.grupo_tipo_cargo=b.grupo_tipo_cargo AND a.tipo_cargo=b.tipo_cargo";
		$query1 = "SELECT count(*) FROM cups a,patologias_tipos_cargos b WHERE a.grupo_tipo_cargo=b.grupo_tipo_cargo AND a.tipo_cargo=b.tipo_cargo";
		if($codigoBus){
      $query.=" AND cargo LIKE '%$codigoBus%'";
			$query1.=" AND cargo LIKE '%$codigoBus%'";
		}
		if($procedimientoBus){
      $query.=" AND descripcion LIKE '%".strtoupper($procedimientoBus)."%'";
			$query1.=" AND descripcion LIKE '%".strtoupper($procedimientoBus)."%'";
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

	function SeleccionProcedimientosBusqueda(){
    if($_REQUEST['buscar']){
      $this->FormaBusquedaProcedimientosMuestras($_REQUEST['solicitud'],$_REQUEST['tejidoId'],$_REQUEST['nomTejido'],$_REQUEST['tipoId'],
		  $_REQUEST['PacienteId'],$_REQUEST['nombre'],$_REQUEST['fecha'],$_REQUEST['modificacion'],$_REQUEST['inadecuada'],$_REQUEST['observaciones']);
			return true;
		}
		$this->FormaConfirmacionLlegadaTejido($_REQUEST['solicitud'],$_REQUEST['tejidoId'],$_REQUEST['nomTejido'],$_REQUEST['tipoId'],
		$_REQUEST['PacienteId'],$_REQUEST['nombre'],$_REQUEST['fecha'],$_REQUEST['modificacion'],$_REQUEST['inadecuada'],$_REQUEST['observaciones']);
		return true;
	}

	function SeleccionarProcedimientodeMuestra(){
	 // unset($_SESSION['PATOLOGIA_CLASIFICACION']['PROCEDIMIENTOS']);
	  $count=sizeof($_SESSION['PATOLOGIA_CLASIFICACION']['PROCEDIMIENTOS'][$_REQUEST['cargoSelect']]);
	  $_SESSION['PATOLOGIA_CLASIFICACION']['PROCEDIMIENTOS'][$_REQUEST['cargoSelect']][$count]=$_REQUEST['descripcionSelect'];
    $this->FormaConfirmacionLlegadaTejido($_REQUEST['solicitud'],$_REQUEST['tejidoId'],$_REQUEST['nomTejido'],$_REQUEST['tipoId'],
		$_REQUEST['PacienteId'],$_REQUEST['nombre'],$_REQUEST['fecha'],$_REQUEST['modificacion'],$_REQUEST['inadecuada'],$_REQUEST['observaciones']);
		return true;
	}

	function EliminaProcedimientoMuestra(){
	  $count=sizeof($_SESSION['PATOLOGIA_CLASIFICACION']['PROCEDIMIENTOS'][$_REQUEST['codigoTejido']]);
    unset($_SESSION['PATOLOGIA_CLASIFICACION']['PROCEDIMIENTOS'][$_REQUEST['codigoTejido']][$count-1]);
		$this->FormaConfirmacionLlegadaTejido($_REQUEST['solicitud'],$_REQUEST['tejidoId'],$_REQUEST['nomTejido'],$_REQUEST['tipoId'],
		$_REQUEST['PacienteId'],$_REQUEST['nombre'],$_REQUEST['fecha'],$_REQUEST['modificacion'],$_REQUEST['inadecuada'],$_REQUEST['observaciones']);
		return true;
	}

	function TiposProcedimientosTejidos($solicitud,$tejido){
    list($dbconn) = GetDBconn();
		$query = "SELECT DISTINCT c.grupo_tipo_cargo,c.tipo_cargo,c.descripcion,z.resultado_informe_id,z.prefijo,z1.examen_firmado,z2.patologia_solicitud_id as existe
		FROM patologias_tejidos_confirmados_procedimientos a,cups b,tipos_cargos c
		LEFT JOIN patologias_solicitudes_detalle_informes z ON(z.patologia_solicitud_id='$solicitud' AND z.tejido_id='$tejido' AND c.tipo_cargo=z.tipo_cargo AND c.grupo_tipo_cargo=z.grupo_tipo_cargo)
		LEFT JOIN patologias_resultados_solicitudes z1 ON(z.patologia_solicitud_id=z1.patologia_solicitud_id AND z.grupo_tipo_cargo=z1.grupo_tipo_cargo AND z.tipo_cargo=z1.tipo_cargo AND z.prefijo=z1.prefijo)
		LEFT JOIN patologias_tejidos_incinerados z2 ON(z2.patologia_solicitud_id='$solicitud' AND z2.tejido_id='$tejido')
		WHERE a.patologia_solicitud_id='$solicitud' AND a.tejido_id='$tejido' AND
		a.procedimiento=b.cargo AND b.tipo_cargo=c.tipo_cargo AND b.grupo_tipo_cargo=c.grupo_tipo_cargo ORDER BY z.resultado_informe_id DESC";
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

	function AsignarNumeracionInforme(){
    $this->FormaAsignarNumeracionInforme($_REQUEST['solicitud'],$_REQUEST['tipoId'],$_REQUEST['PacienteId'],
		$_REQUEST['nombre'],$_REQUEST['fecha'],$_REQUEST['tipoCargo'],$_REQUEST['tipoInformeNombre'],$_REQUEST['grupoTipoCargo']);
		return true;
	}

	function TejidosProcedimientosxGrupo($solicitud,$tipoCargo,$grupoTipoCargo){
    list($dbconn) = GetDBconn();
		$query = "(SELECT a.tejido_id,d.descripcion
		FROM patologias_tejidos_confirmados_procedimientos a,cups b,patologias_tipos_cargos c,tipos_tejidos d
		WHERE a.patologia_solicitud_id='$solicitud' AND a.procedimiento=b.cargo AND b.tipo_cargo=c.tipo_cargo AND
		b.grupo_tipo_cargo=c.grupo_tipo_cargo AND c.tipo_cargo='$tipoCargo' AND c.grupo_tipo_cargo='$grupoTipoCargo' AND
		d.tejido_id=a.tejido_id)
		EXCEPT
    (SELECT a.tejido_id,b.descripcion
		FROM patologias_solicitudes_detalle_informes a,tipos_tejidos b
		WHERE a.patologia_solicitud_id='$solicitud' AND a.tipo_cargo='$tipoCargo' AND a.grupo_tipo_cargo='$grupoTipoCargo' AND a.tejido_id=b.tejido_id)";
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

	 function AsignacionConsecutivoTejidos(){

      if($_REQUEST['cancelar']){
        $this->FormaRecepcionTejidoPatologico();
				return true;
			}
			if(sizeof($_REQUEST['tejidosLista'])<1){
        $this->FormaAsignarNumeracionInforme($_REQUEST['solicitud'],$_REQUEST['tipoId'],$_REQUEST['PacienteId'],
		    $_REQUEST['nombre'],$_REQUEST['fecha'],$_REQUEST['tipoCargo'],$_REQUEST['tipoInformeNombre'],$_REQUEST['grupoTipoCargo']);
		    return true;
			}
			list($dbconn) = GetDBconn();
			$query = "SELECT a.sw_prefijo,a.prefijo,a.inicio_consecutivo
			FROM patologias_tipos_cargos a
			WHERE a.grupo_tipo_cargo='".$_REQUEST['grupoTipoCargo']."' AND a.tipo_cargo='".$_REQUEST['tipoCargo']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				$varsTmp=$result->GetRowAssoc($toUpper=false);
			}
			if($varsTmp['sw_prefijo']==1){
        $query = "SELECT max(b.resultado_informe_id) as numero
				FROM patologias_tipos_cargos a,patologias_solicitudes_detalle_informes b
				WHERE a.grupo_tipo_cargo='".$_REQUEST['grupoTipoCargo']."' AND a.tipo_cargo='".$_REQUEST['tipoCargo']."' AND a.prefijo='".$varsTmp['prefijo']."' AND
				a.grupo_tipo_cargo=b.grupo_tipo_cargo AND a.tipo_cargo=b.tipo_cargo AND a.prefijo=b.prefijo";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}else{
          $vars=$result->GetRowAssoc($toUpper=false);
					if(empty($vars['numero'])){
            $NoInforme=$varsTmp['inicio_consecutivo'];
						$prefijo=$varsTmp['prefijo'];
					}else{
            $NoInforme=$vars['numero']+1;
						$prefijo=$varsTmp['prefijo'];
					}
				}
			}else{
        $query = "SELECT max(resultado_informe_id) as numero
				FROM patologias_resultados_solicitudes";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}else{
          $vars=$result->GetRowAssoc($toUpper=false);
				}
				$NoInforme=$vars['numero']+1;
				$prefijo='NULL';
			}
      $tejidosLista=$_REQUEST['tejidosLista'];
			for($i=0;$i<sizeof($tejidosLista);$i++){
        $query="INSERT INTO patologias_solicitudes_detalle_informes(patologia_solicitud_id,tejido_id,resultado_informe_id,prefijo,
				tipo_cargo,grupo_tipo_cargo)
				VALUES('".$_REQUEST['solicitud']."','".$tejidosLista[$i]."','$NoInforme','$prefijo',
				'".$_REQUEST['tipoCargo']."','".$_REQUEST['grupoTipoCargo']."')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
			$this->FormaRecepcionTejidoPatologico();
			return true;
	 }

	function TotalPlantillasPatologia(){

		list($dbconn) = GetDBconn();
		$query="SELECT plantilla_id,nombre_plantilla
		FROM patologias_tipos_plantillas";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF){
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
				}
			}
		}
		return $vars;
  }

  function TotalPrefijos(){
    list($dbconn) = GetDBconn();
		$query = "SELECT DISTINCT a.prefijo
		FROM patologias_tipos_cargos a";
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
				$vars[$result->fields[0]]=$result->fields[0];
				$result->MoveNext();
			}
		}
		$result->Close();
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

