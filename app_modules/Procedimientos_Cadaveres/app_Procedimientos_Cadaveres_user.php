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

class app_Procedimientos_Cadaveres_user extends classModulo
{

	function app_Procedimientos_Cadaveres_user()
	{
	  $this->limit=GetLimitBrowser();
		//$this->limit=1;
    return true;
	}

	/**
* Funcion que llama la forma donde se muestran los departamentos del sistema a los que el usuario puede accesar
* @return array
*/
	function main(){
	  $validarUsuario=$this->compruebaTipoUsuario();
		if($validarUsuario==1){
			if(!$this->MenuConsultas()){
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
		$query = "SELECT * FROM userpermisos_auxiliares_patologias_cadaveres WHERE usuario_id='".UserGetUID()."'";
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

	function LlamaRecibirCadaver(){
    $this->PedirRegistroPaciente();
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
			$this->frmError["MensajeError"]="El numero de Documento del Paciente es Obligatorio";
			$this->PedirRegistroPaciente();
			return true;
		}
		unset($_SESSION['PACIENTES']);
		$_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$_REQUEST['Documento'];
		$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['TipoDocumento'];
		$_SESSION['PACIENTES']['PACIENTE']['plan_id']=56;
		$_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
		$_SESSION['PACIENTES']['RETORNO']['modulo']='Procedimientos_Cadaveres';
		$_SESSION['PACIENTES']['RETORNO']['tipo']='user';
		$_SESSION['PACIENTES']['RETORNO']['metodo']='LlamaFormaDatosCadaver';
		$_SESSION['PACIENTES']['RETORNO']['argumentos']=array("Documento"=>$_REQUEST['Documento'],"TipoDocumento"=>$_REQUEST['TipoDocumento']);
		$this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
		return true;
	}

	function LlamaFormaDatosCadaver(){
    if(empty($_SESSION['PACIENTES']['RETORNO']['PASO'])){
      $this->PedirRegistroPaciente();
			return true;
		}
		$this->FormaDatosCadaver($_REQUEST['Documento'],$_REQUEST['TipoDocumento']);
		return true;
	}

	function nombrePaciente($TipoId,$PacienteId){
		list($dbconn) = GetDBconn();
		$query = "SELECT fecha_nacimiento,primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido as nombre FROM pacientes WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'";
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

	function GuardarRecepcionCadaver(){

		if($_REQUEST['Regresar']){
		  unset($_SESSION['CADAVERES']['PROCEDIMIENTOSINICIAL']);
      $this->PedirRegistroPaciente();
			return true;
		}
		if($_REQUEST['BuscarPro']){
			foreach($_REQUEST as $v=>$datos){
			  if($v!='SIIS_SID' AND $v!='modulo' AND $v!='metodo' AND $v!='BuscarPro'){
          $vec[$v]=$datos;
				}
			}
      $this->FormaBusquedaProcedimientosInicial($_REQUEST['Documento'],$_REQUEST['TipoDocumento'],$vec);
			return true;
		}
    if(!$_REQUEST['fecha'] || $_REQUEST['hora']==-1 || $_REQUEST['minutos']==-1 || ($_REQUEST['responsableCadaver']==-1 && empty($_REQUEST['otroresponsableCadaver']))){
		  if(!$_REQUEST['fecha']){$this->frmError["fecha"]=1;}
			if($_REQUEST['hora']==-1){$this->frmError["fecha"]=1;}
			if($_REQUEST['minutos']==-1){$this->frmError["fecha"]=1;}
			if($_REQUEST['responsableCadaver']==-1 && empty($_REQUEST['otroresponsableCadaver'])){$this->frmError["responsableCadaver"]=1;$this->frmError["otroresponsableCadaver"]=1;}
      $this->frmError["MensajeError"]="Faltan Datos Obligatorios";
      $this->FormaDatosCadaver($_REQUEST['Documento'],$_REQUEST['TipoDocumento']);
			return true;
		}
		if($_REQUEST['departamento']==-1){$departamento='NULL';}else{$departamento="'".$_REQUEST['departamento']."'";}
		$fecha=ereg_replace("-","/",$_REQUEST['fecha']);
		(list($dia,$mes,$ano)=explode('/',$fecha));
		if($_REQUEST['responsableCadaver']==-1){
      $responsable='NULL';
			$tipoResonsable='NULL';
		}else{
      (list($responsable,$tipoResonsable)=explode('/',$_REQUEST['responsableCadaver']));
      $responsable="'$responsable'";
			$tipoResonsable="'$tipoResonsable'";
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
    $query="SELECT nextval('cadaveres_recepcion_cadaver_id_seq')";
		$result = $dbconn->Execute($query);
		$CadaverId=$result->fields[0];
		$query="INSERT INTO cadaveres_recepcion(cadaver_id,fecha_recepcion,tipo_id_responsable_cadaver,responsable_cadaver,departamento,
		descripcion_solicitud,observaciones,tipo_id_paciente,paciente_id,certificado_defuncion,
		causa_muerte,usuario_id,fecha_registro,estado,responsable_solicitud,origen_solicitud,otro_responsable_cadaver)VALUES('$CadaverId','".$ano.'-'.$mes.'-'.$dia." ".$_REQUEST['hora'].":".$_REQUEST['minutos'].":00',$tipoResonsable,$responsable,
		$departamento,'".$_REQUEST['Solicitud']."','".$_REQUEST['observaciones']."','".$_REQUEST['TipoDocumento']."',
		'".$_REQUEST['Documento']."','".$_REQUEST['certificadoDefuncion']."','".$_REQUEST['causaMuerte']."','".UserGetUID()."',
		'".date("Y-m-d H:i:s")."','1','".$_REQUEST['responsableSolicitud']."','".$_REQUEST['origenSolicitud']."','".$_REQUEST['otroresponsableCadaver']."')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
      unset($_SESSION['CADAVERES']['PROCEDIMIENTOSINICIAL']);
			$dbconn->CommitTrans();
			$mensaje='Datos del Cadaver No.: '.$CadaverId.' Guardados Correctamente';
			$titulo='RECEPCION CADAVER';
			$accion=ModuloGetURL('app','Procedimientos_Cadaveres','user','MenuConsultas');
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		/*else{
		  $query="SELECT tipo_cargo,grupo_tipo_cargo,prefijo,inicio_consecutivo,sw_prefijo
			FROM cadaveres_tipo_cargo";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
			  $vars=$result->GetRowAssoc($toUpper=false);
			}
			if($vars['sw_prefijo']==1){
        $query="SELECT *
				FROM cadaveres_informes
				WHERE tipo_cargo='".$vars['tipo_cargo']."' AND grupo_tipo_cargo='".$vars['grupo_tipo_cargo']."' AND prefijo='".$vars['prefijo']."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
          $datos=$result->RecordCount();
					if($datos){
            $query="SELECT max(resultado_informe_id)
						FROM cadaveres_informes
						WHERE tipo_cargo='".$vars['tipo_cargo']."' AND grupo_tipo_cargo='".$vars['grupo_tipo_cargo']."' AND prefijo='".$vars['prefijo']."'";
						$result = $dbconn->Execute($query);
						$NoInforme=$result->fields[0]+1;
						$prefijo=$vars['prefijo'];
					}else{
						if($vars['inicio_consecutivo']){
              $NoInforme=$vars['inicio_consecutivo'];
							$prefijo=$vars['prefijo'];
						}else{
              $NoInforme=1;
							$prefijo=$vars['prefijo'];
						}
					}
				}
			}else{
			  $prefijo='NULL';
        $query="SELECT max(resultado_informe_id)
				FROM cadaveres_informes";
				$result = $dbconn->Execute($query);
				$NoInforme=$result->fields[0]+1;
			}
			$query="INSERT INTO cadaveres_informes(resultado_informe_id,cadaver_id,prefijo,tipo_cargo,grupo_tipo_cargo,entregado,usuario_id,fecha_registro,examen_firmado)
			VALUES('$NoInforme','$CadaverId','$prefijo','".$vars['tipo_cargo']."','".$vars['grupo_tipo_cargo']."','0','".UserGetUID()."','".date("Y-m-d H:i:s")."','0')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
			  foreach($_SESSION['CADAVERES']['PROCEDIMIENTOSINICIAL'] as $codigo=>$vector){
					foreach($vector as $indice=>$nombrepro){
						$query="INSERT INTO cadaveres_informes_procedimientos(resultado_informe_id,prefijo,procedimiento)
						VALUES('$NoInforme','$prefijo','$codigo')";
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
			unset($_SESSION['CADAVERES']['PROCEDIMIENTOSINICIAL']);
			$dbconn->CommitTrans();
		  $mensaje='Datos del Cadaver Guardados Correctamente, fue Creado el Informe No. '.$prefijo.' '.$NoInforme;
			$titulo='RECEPCION CADAVER';
			$accion=ModuloGetURL('app','Procedimientos_Cadaveres','user','MenuConsultas');
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}*/
	}

	function LlamaConsultaCadaver(){
	  if($_REQUEST['Menu']){
      $this->MenuConsultas();
			return true;
		}
    $this->ConsultaCadaver($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['departamento'],
		$_REQUEST['fecha'],$_REQUEST['certificado'],$_REQUEST['entregado'],$_REQUEST['noinforme'],$_REQUEST['todasFechas']);
		return true;
	}

	function ConsultaCadaveres($TipoDocumento,$Documento,$departamento,$fecha,$certificado,$entregado,$noinforme,$todasFechas){

    list($dbconn) = GetDBconn();
		if($noinforme){
		$query1="SELECT count(*)
		FROM cadaveres_recepcion a
		LEFT JOIN profesionales e ON (a.tipo_id_responsable_cadaver=e.tipo_id_tercero AND a.responsable_cadaver=e.tercero_id)
		LEFT JOIN departamentos c ON (c.departamento=a.departamento),
		cadaveres_informes d
		LEFT JOIN profesionales_usuarios f ON (f.usuario_id=d.usuario_id_firma)
    LEFT JOIN profesionales g ON (d.tipo_id_tercero=g.tipo_id_tercero AND d.tercero_id=g.tercero_id),
		pacientes b
		WHERE a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND
		a.cadaver_id=d.cadaver_id AND d.resultado_informe_id='$noinforme'";
		}else{
    $query1="SELECT count(*)
		FROM cadaveres_recepcion a
		LEFT JOIN departamentos c ON (c.departamento=a.departamento)
		LEFT JOIN cadaveres_informes d ON (a.cadaver_id=d.cadaver_id)
		LEFT JOIN profesionales_usuarios f ON (f.usuario_id=d.usuario_id_firma)
    LEFT JOIN profesionales g ON (d.tipo_id_tercero=g.tipo_id_tercero AND d.tercero_id=g.tercero_id)
		LEFT JOIN profesionales e ON (a.tipo_id_responsable_cadaver=e.tipo_id_tercero AND a.responsable_cadaver=e.tercero_id),
		pacientes b
		WHERE a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id";
		}
    if($noinforme){
		$query="SELECT a.estado as estadocadaver,a.cadaver_id,a.tipo_id_paciente,a.paciente_id,b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,b.fecha_nacimiento,
		a.fecha_recepcion as fecha ,a.departamento,c.descripcion as nom_departamento,a.certificado_defuncion,d.resultado_informe_id,d.prefijo,d.examen_firmado,a.tipo_id_responsable_cadaver,
		a.responsable_cadaver,a.certificado_defuncion,e.nombre as nomprofesional,g.nombre as nomprofesionalinfor,a.otro_responsable_cadaver
		FROM cadaveres_recepcion a
		LEFT JOIN departamentos c ON (c.departamento=a.departamento)
		LEFT JOIN profesionales e ON (a.tipo_id_responsable_cadaver=e.tipo_id_tercero AND a.responsable_cadaver=e.tercero_id),
		cadaveres_informes d
		LEFT JOIN profesionales_usuarios f ON (f.usuario_id=d.usuario_id_firma)
    LEFT JOIN profesionales g ON (d.tipo_id_tercero=g.tipo_id_tercero AND d.tercero_id=g.tercero_id),
		pacientes b
		WHERE a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND
		a.cadaver_id=d.cadaver_id AND d.resultado_informe_id='$noinforme'";
    }else{
    $query="SELECT a.estado as estadocadaver,a.cadaver_id,a.tipo_id_paciente,a.paciente_id,b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,b.fecha_nacimiento,
		a.fecha_recepcion as fecha ,a.departamento,c.descripcion as nom_departamento,a.certificado_defuncion,d.resultado_informe_id,d.prefijo,d.examen_firmado,a.tipo_id_responsable_cadaver,
		a.responsable_cadaver,a.certificado_defuncion,e.nombre as nomprofesional,g.nombre as nomprofesionalinfor,a.otro_responsable_cadaver
		FROM cadaveres_recepcion a
		LEFT JOIN departamentos c ON (c.departamento=a.departamento)
		LEFT JOIN cadaveres_informes d ON (a.cadaver_id=d.cadaver_id)
		LEFT JOIN profesionales_usuarios f ON (f.usuario_id=d.usuario_id_firma)
    LEFT JOIN profesionales g ON (d.tipo_id_tercero=g.tipo_id_tercero AND d.tercero_id=g.tercero_id)
		LEFT JOIN profesionales e ON (a.tipo_id_responsable_cadaver=e.tipo_id_tercero AND a.responsable_cadaver=e.tercero_id),
		pacientes b
		WHERE a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id";
		}
    if($TipoDocumento && $Documento){
		  $query.=" AND a.tipo_id_paciente='$TipoDocumento' AND a.paciente_id='$Documento'";
			$query1.=" AND a.tipo_id_paciente='$TipoDocumento' AND a.paciente_id='$Documento'";
		}
		if($departamento!=-1 && !empty($departamento)){
      $query.=" AND a.departamento='$departamento'";
			$query1.=" AND a.departamento='$departamento'";
		}
		if($todasFechas!=1){
    if($fecha){
		  $fecha=ereg_replace("-","/",$fecha);
      (list($dia,$mes,$ano)=explode('/',$fecha));
      $query.=" AND date(a.fecha_recepcion)='".$ano.'-'.$mes.'-'.$dia."'";
			$query1.=" AND date(a.fecha_recepcion)='".$ano.'-'.$mes.'-'.$dia."'";
		}
		}
		if($certificado){
      $query.=" AND a.certificado_defuncion LIKE '%$certificado%'";
			$query1.=" AND a.certificado_defuncion LIKE '%$certificado%'";
		}
		if($entregado==1){
      $query.=" AND a.estado='0'";
			$query1.=" AND a.estado='0'";
		}else{
      $query.=" AND a.estado='1'";
			$query1.=" AND a.estado='1'";
		}
		$query.=" ORDER BY d.examen_firmado ASC,d.resultado_informe_id DESC,d.prefijo";
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

	function LlamaInformeCadaver(){

	  if($_REQUEST['Destino']==2 || $_REQUEST['Destino']==1){
      list($dbconn) = GetDBconn();
		  $query="SELECT a.tipo_id_tercero,a.tercero_id,a.observaciones,c.nombre,a.usuario_id_firma
			FROM cadaveres_informes a
      LEFT JOIN profesionales_usuarios b ON(a.usuario_id_firma=b.usuario_id)
			LEFT JOIN profesionales c ON (b.tipo_tercero_id=c.tipo_id_tercero AND b.tercero_id=c.tercero_id)
			WHERE a.resultado_informe_id='".$_REQUEST['informe']."' AND a.prefijo='".$_REQUEST['prefijo']."' AND
			a.cadaver_id='".$_REQUEST['cadaverId']."'";
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
			$observacionesInforme=$vars['observaciones'];
			$profesional=$vars['nombre'];
			$usuariofirma=$vars['usuario_id_firma'];
			if($vars['tercero_id'] && $vars['tipo_id_tercero']){
			$patologoProfe=$vars['tercero_id'].'/'.$vars['tipo_id_tercero'];
			}

			$query="SELECT b.diagnostico_id,c.diagnostico_nombre
			FROM cadaveres_informes a,cadaveres_informes_diagnosticos b,diagnosticos c
			WHERE a.resultado_informe_id='".$_REQUEST['informe']."' AND a.prefijo='".$_REQUEST['prefijo']."' AND
			a.resultado_informe_id=b.resultado_informe_id AND a.prefijo=b.prefijo AND
			b.diagnostico_id=c.diagnostico_id";
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
				}
				for($i=0;$i<sizeof($varsDiag);$i++){
          $_SESSION['CADAVERES']['DIAGNOSTICOS'][$varsDiag[$i]['diagnostico_id']]=$varsDiag[$i]['diagnostico_nombre'];
				}
			}
      $query="SELECT b.procedimiento,c.descripcion
			FROM cadaveres_informes a,cadaveres_informes_procedimientos b,cups c
			WHERE a.resultado_informe_id='".$_REQUEST['informe']."' AND a.prefijo='".$_REQUEST['prefijo']."' AND
			a.resultado_informe_id=b.resultado_informe_id AND a.prefijo=b.prefijo AND
			b.procedimiento=c.cargo";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
			  $datos=$result->RecordCount();
				if($datos){
					while(!$result->EOF){
						$varsProced[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
				for($i=0;$i<sizeof($varsProced);$i++){
				  $size=sizeof($_SESSION['CADAVERES']['PROCEDIMIENTOS'][$varsProced[$i]['procedimiento']]);
          $_SESSION['CADAVERES']['PROCEDIMIENTOS'][$varsProced[$i]['procedimiento']][$size]=$varsProced[$i]['descripcion'];
				}
			}
			$query="SELECT f.observaciones_adicionales,f.fecha_registro,h.nombre
			FROM cadaveres_informes_observaciones_adicionales f,profesionales_usuarios g,profesionales h
			WHERE f.resultado_informe_id='".$_REQUEST['informe']."' AND f.prefijo='".$_REQUEST['prefijo']."' AND
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
						$varsObserv[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			$_SESSION['CADAVERES']['INFORME']=$_REQUEST['informe'];
		  $_SESSION['CADAVERES']['PREFIJO']=$_REQUEST['prefijo'];
			$this->InformeCadaver($_REQUEST['cadaverId'],$_REQUEST['informe'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],$_REQUEST['fechaNac'],$_REQUEST['Destino'],$observacionesInforme,'',$profesional,$usuariofirma,$observacionesAdicionales,$patologoProfe,$varsObserv);
      return true;
		}elseif($_REQUEST['Destino']==3){
      list($dbconn) = GetDBconn();
      $query="SELECT tipo_cargo,grupo_tipo_cargo,prefijo,inicio_consecutivo,sw_prefijo
			FROM cadaveres_tipo_cargo";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
			  $vars=$result->GetRowAssoc($toUpper=false);
			}
			if($vars['sw_prefijo']==1){
        $query="SELECT *
				FROM cadaveres_informes
				WHERE tipo_cargo='".$vars['tipo_cargo']."' AND grupo_tipo_cargo='".$vars['grupo_tipo_cargo']."' AND prefijo='".$vars['prefijo']."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
          $datos=$result->RecordCount();
					if($datos){
            $query="SELECT max(resultado_informe_id)
						FROM cadaveres_informes
						WHERE tipo_cargo='".$vars['tipo_cargo']."' AND grupo_tipo_cargo='".$vars['grupo_tipo_cargo']."' AND prefijo='".$vars['prefijo']."'";
						$result = $dbconn->Execute($query);
						$NoInforme=$result->fields[0]+1;
						$prefijo=$vars['prefijo'];
					}else{
						if($vars['inicio_consecutivo']){
              $NoInforme=$vars['inicio_consecutivo'];
							$prefijo=$vars['prefijo'];
						}else{
              $NoInforme=1;
							$prefijo=$vars['prefijo'];
						}
					}
				}
			}else{
			  $prefijo='NULL';
        $query="SELECT max(resultado_informe_id)
				FROM cadaveres_informes";
				$result = $dbconn->Execute($query);
				$NoInforme=$result->fields[0]+1;
			}
			$query="INSERT INTO cadaveres_informes(resultado_informe_id,cadaver_id,prefijo,tipo_cargo,grupo_tipo_cargo,entregado,usuario_id,fecha_registro,examen_firmado)
			VALUES('$NoInforme','".$_REQUEST['cadaverId']."','$prefijo','".$vars['tipo_cargo']."','".$vars['grupo_tipo_cargo']."','0','".UserGetUID()."','".date("Y-m-d H:i:s")."','0')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
			  foreach($_SESSION['CADAVERES']['PROCEDIMIENTOSINICIAL'] as $codigo=>$vector){
					foreach($vector as $indice=>$nombrepro){
						$query="INSERT INTO cadaveres_informes_procedimientos(resultado_informe_id,prefijo,procedimiento)
						VALUES('$NoInforme','$prefijo','$codigo')";
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
			unset($_SESSION['CADAVERES']['PROCEDIMIENTOSINICIAL']);
			$dbconn->CommitTrans();
		  $mensaje='Fue Creado el Informe No. '.$prefijo.' '.$NoInforme.' Para el cadaver No.:'.$_REQUEST['cadaverId'];
			$titulo='RECEPCION CADAVER';
			$accion=ModuloGetURL('app','Procedimientos_Cadaveres','user','ConsultaCadaver');
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
	}

	function SeleccionDiagnostico(){
    if($_REQUEST['Salir']){
      $this->InformeCadaver($_REQUEST['cadaverId'],$_REQUEST['informe'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],$_REQUEST['fechaNac'],
			$_REQUEST['Destino'],$_REQUEST['observacionesInforme'],$_REQUEST['firma'],'','','',$_REQUEST['patologoProfe']);
			return true;
		}
		$this->FormaBuscadorDiagnosticoResultado($_REQUEST['cadaverId'],$_REQUEST['informe'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],
		$_REQUEST['fechaNac'],$_REQUEST['Destino'],$_REQUEST['observacionesInforme'],$_REQUEST['firma'],$_REQUEST['patologoProfe']);
		return true;
	}

	function SeleccionarDiagnosticoPatologia(){
	  $_SESSION['CADAVERES']['DIAGNOSTICOS'][$_REQUEST['codigoDiagnostico']]=$_REQUEST['nombreDiagnostico'];
    $this->InformeCadaver($_REQUEST['cadaverId'],$_REQUEST['informe'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],$_REQUEST['fechaNac'],
		$_REQUEST['Destino'],$_REQUEST['observacionesInforme'],$_REQUEST['firma'],'','','',$_REQUEST['patologoProfe']);
		return true;
	}

	function EliminaDiagResultadoCadaveres(){
	  unset($_SESSION['CADAVERES']['DIAGNOSTICOS'][$_REQUEST['codigo']]);
    $this->InformeCadaver($_REQUEST['cadaverId'],$_REQUEST['informe'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],$_REQUEST['fechaNac'],
		$_REQUEST['Destino'],$_REQUEST['observacionesInforme'],$_REQUEST['firma'],'','','',$_REQUEST['patologoProfe']);
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

	function GuardarResultadoCadaver(){

    if($_REQUEST['Regresar']){
			unset($_SESSION['CADAVERES']['DIAGNOSTICOS']);
			unset($_SESSION['CADAVERES']['PROCEDIMIENTOS']);
			unset($_SESSION['CADAVERES']['INFORME']);
			unset($_SESSION['CADAVERES']['PREFIJO']);
      $this->ConsultaCadaver();
			return true;
		}
		if($_REQUEST['BuscarDiag']){
      $this->FormaBuscadorDiagnosticoResultado($_REQUEST['cadaverId'],$_REQUEST['informe'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],
			$_REQUEST['fechaNac'],$_REQUEST['Destino'],$_REQUEST['observacionesInforme'],$_REQUEST['firma'],$_REQUEST['patologoProfe']);
			return true;
		}
		if($_REQUEST['buscarPro']){
      $this->FormaBusquedaProcedimientos($_REQUEST['cadaverId'],$_REQUEST['informe'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],
			$_REQUEST['fechaNac'],$_REQUEST['Destino'],$_REQUEST['observacionesInforme'],$_REQUEST['firma'],$_REQUEST['patologoProfe']);
			return true;
		}
		if($_REQUEST['AdicionarPlan']){
      list($dbconn) = GetDBconn();
			if($_REQUEST['Plantilla']!=-1){
        $query = "SELECT contenido_plantilla FROM cadaveres_tipos_plantillas WHERE plantilla_id='".$_REQUEST['Plantilla']."'";
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
			$this->InformeCadaver($_REQUEST['cadaverId'],$_REQUEST['informe'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],$_REQUEST['fechaNac'],$_REQUEST['Destino'],$_REQUEST['observacionesInforme'],$_REQUEST['firma'],$_REQUEST['nombreProfesional'],$_REQUEST['usuariofirma'],$_REQUEST['observacionesAdicionales'],$_REQUEST['patologoProfe']);
			return true;
		}


		if($_REQUEST['AdicionarPlanAdicional']){
      list($dbconn) = GetDBconn();
			if($_REQUEST['PlantillaAdicional']!=-1){
        $query = "SELECT contenido_plantilla FROM cadaveres_tipos_plantillas WHERE plantilla_id='".$_REQUEST['PlantillaAdicional']."'";
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
			$this->InformeCadaver($_REQUEST['cadaverId'],$_REQUEST['informe'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],$_REQUEST['fechaNac'],$_REQUEST['Destino'],$_REQUEST['observacionesInforme'],$_REQUEST['firma'],$_REQUEST['nombreProfesional'],$_REQUEST['usuariofirma'],$_REQUEST['observacionesAdicionales'],$_REQUEST['patologoProfe']);
			return true;
		}

		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		if($_REQUEST['firma']==1){
      $firma=1;
			$usuariofirma="'".UserGetUID()."'";
		}else{
      $firma=0;
			$usuariofirma='NULL';
		}
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
      (list($IdProf,$tipoIdPro)=explode('/',$_REQUEST['patologoProfe']));
			$tipoIdPro="'".$tipoIdPro."'";
			$IdProf="'".$IdProf."'";
		}else{
      $tipoIdPro='NULL';
			$IdProf='NULL';
		}

		if($_REQUEST['Destino']==3){
		  if(empty($_SESSION['CADAVERES']['PREFIJO'])){
        $_SESSION['CADAVERES']['PREFIJO']='NULL';
			}
		  $query="SELECT tipo_cargo,grupo_tipo_cargo,prefijo,inicio_consecutivo,sw_prefijo
			FROM cadaveres_tipo_cargo";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
			  $vars=$result->GetRowAssoc($toUpper=false);
			}
			$sw_prefijo=$vars['sw_prefijo'];
			$TipoCargo=$vars['tipo_cargo'];
			$grupoTipoCargo=$vars['grupo_tipo_cargo'];
			$prefijo=$vars['prefijo'];
      $iniConsec=$vars['inicio_consecutivo'];
			if($sw_prefijo==1){
        $query="SELECT *
				FROM cadaveres_informes
				WHERE tipo_cargo='$TipoCargo' AND grupo_tipo_cargo='$grupoTipoCargo' AND prefijo='$prefijo'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
          $datos=$result->RecordCount();
					if($datos){
            $query="SELECT max(resultado_informe_id)
						FROM cadaveres_informes
						WHERE tipo_cargo='$TipoCargo' AND grupo_tipo_cargo='$grupoTipoCargo' AND prefijo='$prefijo'";
						$result = $dbconn->Execute($query);
						$NoInforme=$result->fields[0]+1;
					}else{
						if($iniConsec){
              $NoInforme=$iniConsec;
						}else{
              $NoInforme=1;
						}
					}
				}
			}else{
			  $prefijo='NULL';
        $query="SELECT max(resultado_informe_id)
				FROM cadaveres_informes";
				$result = $dbconn->Execute($query);
				$NoInforme=$result->fields[0]+1;
			}
			$query="INSERT INTO cadaveres_informes(resultado_informe_id,cadaver_id,observaciones,
			fecha_registro,usuario_id,examen_firmado,tipo_cargo,grupo_tipo_cargo,prefijo,entregado,usuario_id_firma,tipo_id_tercero,tercero_id)VALUES('$NoInforme',
			'".$_REQUEST['cadaverId']."','".$_REQUEST['observacionesInforme']."',
			'".date("Y-m-d H:i:s")."','".UserGetUID()."','$firma','$TipoCargo','$grupoTipoCargo','$prefijo','0',$usuariofirma,$tipoIdPro,$IdProf)";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
			  foreach($_SESSION['CADAVERES']['PROCEDIMIENTOS'] as $codigo=>$vector){
				  foreach($vector as $indice=>$nombreProc){
					$query="INSERT INTO cadaveres_informes_procedimientos(resultado_informe_id,procedimiento,prefijo)
					VALUES('$NoInforme','$codigo','$prefijo')";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
				}
        foreach($_SESSION['CADAVERES']['DIAGNOSTICOS'] as $codigo=>$nombreDiag){
          $query="INSERT INTO cadaveres_informes_diagnosticos(resultado_informe_id,diagnostico_id,prefijo)
					VALUES('$NoInforme','$codigo','$prefijo')";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
				$dbconn->CommitTrans();
				$mensaje='Datos del Resultado del Informe '.$prefijo.' '.$NoInforme.' del Cadaver Guardados Correctamente';
			}
		}elseif($_REQUEST['Destino']==1){
		  if($_REQUEST['observacionesAdicionales']){
			  $query="INSERT INTO cadaveres_informes_observaciones_adicionales(resultado_informe_id,prefijo,observaciones_adicionales,usuario_id,fecha_registro)
				VALUES('".$_SESSION['CADAVERES']['INFORME']."','".$_SESSION['CADAVERES']['PREFIJO']."','".$_REQUEST['observacionesAdicionales']."','".UserGetUID()."','".date("Y-m-d H:i:s")."')";
				//$query="UPDATE cadaveres_informes SET observaciones_adicionales='".$_REQUEST['observacionesAdicionales']."'
				//WHERE resultado_informe_id='".$_SESSION['CADAVERES']['INFORME']."' AND prefijo='".$_SESSION['CADAVERES']['PREFIJO']."' AND cadaver_id='".$_REQUEST['cadaverId']."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				$dbconn->CommitTrans();
				$mensaje='Datos del Resultado del Informe '.$_SESSION['CADAVERES']['PREFIJO'].' '.$_SESSION['CADAVERES']['INFORME'].' del Cadaver Modificados Correctamente';
			}
		}else{
			$query="UPDATE cadaveres_informes SET observaciones='".$_REQUEST['observacionesInforme']."',
			examen_firmado='$firma',usuario_id_firma=$usuariofirma,tipo_id_tercero=$tipoIdPro,tercero_id=$IdProf
			WHERE resultado_informe_id='".$_SESSION['CADAVERES']['INFORME']."' AND prefijo='".$_SESSION['CADAVERES']['PREFIJO']."' AND cadaver_id='".$_REQUEST['cadaverId']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
			  $query="DELETE FROM cadaveres_informes_diagnosticos WHERE resultado_informe_id='".$_SESSION['CADAVERES']['INFORME']."' AND prefijo='".$_SESSION['CADAVERES']['PREFIJO']."'";
        $result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
          foreach($_SESSION['CADAVERES']['DIAGNOSTICOS'] as $codigo=>$nombreDiag){
						$query="INSERT INTO cadaveres_informes_diagnosticos(resultado_informe_id,diagnostico_id,prefijo)
						VALUES('".$_SESSION['CADAVERES']['INFORME']."','$codigo','".$_SESSION['CADAVERES']['PREFIJO']."')";
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}
				}
				$query="DELETE FROM cadaveres_informes_procedimientos WHERE resultado_informe_id='".$_SESSION['CADAVERES']['INFORME']."' AND prefijo='".$_SESSION['CADAVERES']['PREFIJO']."'";
        $result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
          foreach($_SESSION['CADAVERES']['PROCEDIMIENTOS'] as $codigo=>$vector){
					  foreach($vector as $indice=>$nombreProc){
						$query="INSERT INTO cadaveres_informes_procedimientos(resultado_informe_id,procedimiento,prefijo)
						VALUES('".$_SESSION['CADAVERES']['INFORME']."','$codigo','".$_SESSION['CADAVERES']['PREFIJO']."')";
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
				$mensaje='Datos del Resultado del Informe '.$_SESSION['CADAVERES']['PREFIJO'].' '.$_SESSION['CADAVERES']['INFORME'].' del Cadaver Modificados Correctamente';
			}
		}
		unset($_SESSION['CADAVERES']['DIAGNOSTICOS']);
		unset($_SESSION['CADAVERES']['PROCEDIMIENTOS']);
		unset($_SESSION['CADAVERES']['INFORME']);
		unset($_SESSION['CADAVERES']['PREFIJO']);
		$titulo='RECEPCION CADAVER';
		$accion=ModuloGetURL('app','Procedimientos_Cadaveres','user','ConsultaCadaver');
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		return true;
	}

	function LlamaEntregaCadaver(){
    $this->EntregaCadaver($_REQUEST['cadaverId'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],$_REQUEST['fechaNac'],$_REQUEST['nomprofesional'],$_REQUEST['certificado_defuncion'],$_REQUEST['otroResponsable']);
		return true;
	}

	function TotalProfesionales(){

		list($dbconn) = GetDBconn();
		$query="SELECT tipo_id_tercero,tercero_id,nombre FROM profesionales WHERE estado='1' ORDER BY nombre";
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

	function TotalProfesionalesPermitidos(){

		list($dbconn) = GetDBconn();
		$query="SELECT b.tipo_id_tercero,b.tercero_id,b.nombre_tercero as nombre
		FROM banco_sangre_autorizados_entregas_componentes a,terceros b
		WHERE b.tipo_id_tercero=a.tipo_id_identificacion AND b.tercero_id=a.identificacion
		ORDER BY b.nombre_tercero";
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

	function TotalProfesionalesFuncionarios(){

		list($dbconn) = GetDBconn();
		$query="SELECT b.tipo_id_tercero,b.tercero_id,b.nombre_tercero as nombre
		FROM patologias_funcionarios a,terceros b
		WHERE b.tipo_id_tercero=a.tipo_id_identificacion AND b.tercero_id=a.identificacion
		ORDER BY b.nombre_tercero";
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

	function GuardarEntregaCadaver(){
    if($_REQUEST['Regresar']){
      $this->ConsultaCadaver();
			return true;
		}
		if(!$_REQUEST['fecha'] || $_REQUEST['hora']==-1 || $_REQUEST['minutos']==-1 || ($_REQUEST['responsableEntregaCad']==-1 && empty($_REQUEST['otroFuncionario']))){
      if(!$_REQUEST['fecha']){$this->frmError["fecha"]=1;}
			if($_REQUEST['hora']==-1){$this->frmError["fecha"]=1;}
			if($_REQUEST['minutos']==-1){$this->frmError["fecha"]=1;}
			if(($_REQUEST['responsableEntregaCad']==-1 && empty($_REQUEST['otroFuncionario']))){$this->frmError["responsableEntregaCad"]=1;$this->frmError["otroFuncionario"]=1;}
      $this->frmError["MensajeError"]="Faltan Datos Obligatorios";
			$this->EntregaCadaver($_REQUEST['cadaverId'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],$_REQUEST['fechaNac'],$_REQUEST['nomprofesional'],$_REQUEST['certificado_defuncion'],$_REQUEST['otroResponsable'],
			$_REQUEST['responsable'],$_REQUEST['nombre_recibe'],$_REQUEST['telefono'],$_REQUEST['parentesco'],
			$_REQUEST['observacion'],$_REQUEST['tipo_id_funcionario'],$_REQUEST['funcionario_id']);
			return true;
		}
		if ($_REQUEST['responsable']=='1'){
		  $parentesco1='NULL';
		}elseif($_REQUEST['responsable']=='2'){
			if ($_REQUEST['parentesco']=='-1'   OR $_REQUEST['nombre_recibe']=='' OR $_REQUEST['telefono']=='' ){
				if ($_REQUEST['parentesco']=='-1'){
					$this->frmError["parentesco"]=1;
				}
				if ($_REQUEST['nombre_recibe']==''){
					$this->frmError["nombre_recibe"]=1;
				}
				if ($_REQUEST['telefono']==''){
					$this->frmError["telefono"]=1;
				}
				$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS PARA OTRO RESPONSABLE.";
				$this->EntregaCadaver($_REQUEST['cadaverId'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],$_REQUEST['fechaNac'],$_REQUEST['nomprofesional'],$_REQUEST['certificado_defuncion'],$_REQUEST['otroResponsable'],
				$_REQUEST['responsable'],$_REQUEST['nombre_recibe'],$_REQUEST['telefono'],$_REQUEST['parentesco'],
			  $_REQUEST['observacion'],$_REQUEST['tipo_id_funcionario'],$_REQUEST['funcionario_id']);
			  return true;
			}else{
				$parentesco=$_REQUEST['parentesco'];
				$parentesco1="'$parentesco'";
			}
		}elseif($_REQUEST['responsable']=='3'){
			if ($_REQUEST['tipo_id_funcionario']=='-1' OR $_REQUEST['funcionario_id']==''  OR $_REQUEST['nombre_recibe']==''){
				if ($_REQUEST['tipo_id_funcionario']=='-1' OR $_REQUEST['funcionario_id']==''){
					$this->frmError["identificacion"]=1;
				}
				if ($_REQUEST['nombre_recibe']==''){
					$this->frmError["nombre_recibe"]=1;
				}
				$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS PARA ENTREGAR A FUNCIONARIO.";
				$this->EntregaCadaver($_REQUEST['cadaverId'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],$_REQUEST['fechaNac'],$_REQUEST['nomprofesional'],$_REQUEST['certificado_defuncion'],$_REQUEST['otroResponsable'],
				$_REQUEST['responsable'],$_REQUEST['nombre_recibe'],$_REQUEST['telefono'],$_REQUEST['parentesco'],
			  $_REQUEST['observacion'],$_REQUEST['tipo_id_funcionario'],$_REQUEST['funcionario_id']);
			  return true;
			}else{
				$parentesco1='NULL';
			}
		}
		if($_REQUEST['responsableEntregaCad']==-1){
		  $responsable='NULL';
			$tipoResonsable='NULL';
		}else{
		  (list($responsable,$tipoResonsable)=explode('/',$_REQUEST['responsableEntregaCad']));
      $responsable="'$responsable'";
			$tipoResonsable="'$tipoResonsable'";
		}
		$fecha=ereg_replace("-","/",$_REQUEST['fecha']);
		(list($dia,$mes,$ano)=explode('/',$fecha));
    list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query="INSERT INTO cadaveres_entregados(cadaver_id,fecha_entrega,tipo_id_entrega_cadaver,entrega_cadaver,
		reclama_cadaver,observaciones,usuario_id,fecha_registro,otro_funcionario)VALUES('".$_REQUEST['cadaverId']."',
		'".$ano.'-'.$mes.'-'.$dia." ".$_REQUEST['hora'].":".$_REQUEST['minutos'].":00',$tipoResonsable,$responsable,
		'','".$_REQUEST['observaciones']."','".UserGetUID()."','".date("Y-m-d H:i:s")."','".$_REQUEST['otroFuncionario']."')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
      $query="UPDATE cadaveres_recepcion SET estado='0' WHERE cadaver_id='".$_REQUEST['cadaverId']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
        if($_REQUEST['responsable']=='2' || $_REQUEST['responsable']=='3'){
          $query="INSERT INTO cadaveres_entregados_responsable
					(cadaver_id, tipo_parentesco_id, nombre, telefono, observacion,
					sw_tipo_persona, tipo_id_funcionario,
					funcionario_id)
					VALUES ('".$_REQUEST['cadaverId']."', $parentesco1,
					'".$_REQUEST['nombre_recibe']."', '".$_REQUEST['telefono']."',
					'".$_REQUEST['observacion']."','".$_REQUEST['responsable']."',
					'".$_REQUEST['tipo_id_funcionario']."','".$_REQUEST['funcionario_id']."')";
					$resulta=$dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
			}
			$dbconn->CommitTrans();
			$mensaje='Datos de la Entrega del Cadaver Guardados Correctamente';
			$titulo='ENTREGA CADAVER';
			$accion=ModuloGetURL('app','Procedimientos_Cadaveres','user','MenuConsultas');
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
	}

	function LlamaConsultaEntregaCadaver(){

		list($dbconn) = GetDBconn();
		$query="SELECT a.fecha_entrega,a.tipo_id_entrega_cadaver,a.entrega_cadaver,b.nombre,a.reclama_cadaver,a.observaciones,a.otro_funcionario,
		c.tipo_parentesco_id,d.descripcion as parentesco,c.nombre as nomresponsable,c.telefono,c.observacion,c.sw_tipo_persona,c.tipo_id_funcionario,c.funcionario_id
		FROM cadaveres_entregados a
		LEFT JOIN profesionales b ON(a.tipo_id_entrega_cadaver=b.tipo_id_tercero AND a.entrega_cadaver=b.tercero_id)
    LEFT JOIN cadaveres_entregados_responsable c ON (a.cadaver_id=c.cadaver_id)
		LEFT JOIN tipos_parentescos d ON (c.tipo_parentesco_id=d.tipo_parentesco_id)
		WHERE a.cadaver_id='".$_REQUEST['cadaverId']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($toUpper=false);
				(list($fecha,$hora)=explode(' ',$vars['fecha_entrega']));
				(list($ano,$mes,$dia)=explode('-',$fecha));
        $fecha=$dia.'/'.$mes.'/'.$ano;
        (list($Hora,$Minutos)=explode(':',$hora));
			}
		}
    $this->ConsultaEntregaCadaver($_REQUEST['cadaverId'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],$_REQUEST['fechaNac'],
		$_REQUEST['nomprofesional'],$_REQUEST['certificado_defuncion'],$fecha,$Hora,$Minutos,$vars['tipo_id_entrega_cadaver'],$vars['entrega_cadaver'],
		$vars['nombre'],$vars['reclama_cadaver'],$vars['observaciones'],$vars['otro_funcionario'],$_REQUEST['otroResponsable'],
		$vars['parentesco'],$vars['nomresponsable'],$vars['telefono'],$vars['observacion'],$vars['sw_tipo_persona'],$vars['tipo_id_funcionario'],$vars['funcionario_id']);
		return true;
	}

	function RegresaConsultaEntregaCadaver(){
    $this->ConsultaCadaver();
		return true;
	}

	function HallarCupsPatologia($codigoBus,$procedimientoBus){

		list($dbconn) = GetDBconn();

		$query = "SELECT a.cargo,a.descripcion
		FROM cups a,cadaveres_tipo_cargo b
		WHERE a.grupo_tipo_cargo=b.grupo_tipo_cargo AND a.tipo_cargo=b.tipo_cargo";

		$query1 = "SELECT count(*)
		FROM cups a,cadaveres_tipo_cargo b
		WHERE a.grupo_tipo_cargo=b.grupo_tipo_cargo AND a.tipo_cargo=b.tipo_cargo";

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

	function SeleccionProcedimiento(){

    if($_REQUEST['Salir']){
      $this->InformeCadaver($_REQUEST['cadaverId'],$_REQUEST['informe'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],$_REQUEST['fechaNac'],
			$_REQUEST['Destino'],$_REQUEST['observacionesInforme'],$_REQUEST['firma'],'','','',$_REQUEST['patologoProfe']);
			return true;
		}
		$this->FormaBusquedaProcedimientos($_REQUEST['cadaverId'],$_REQUEST['informe'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],
		$_REQUEST['fechaNac'],$_REQUEST['Destino'],$_REQUEST['observacionesInforme'],$_REQUEST['firma'],$_REQUEST['patologoProfe']);
		return true;
	}

	function SeleccionarProcedimientoPatologia(){
    $size=sizeof($_SESSION['CADAVERES']['PROCEDIMIENTOS'][$_REQUEST['codigoProcedimiento']]);
	  $_SESSION['CADAVERES']['PROCEDIMIENTOS'][$_REQUEST['codigoProcedimiento']][$size]=$_REQUEST['nombreProcedimiento'];
    $this->InformeCadaver($_REQUEST['cadaverId'],$_REQUEST['informe'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],$_REQUEST['fechaNac'],
		$_REQUEST['Destino'],$_REQUEST['observacionesInforme'],$_REQUEST['firma'],'','','',$_REQUEST['patologoProfe']);
		return true;
	}

	function EliminaProcPatologiaResultado(){
	  $size=sizeof($_SESSION['CADAVERES']['PROCEDIMIENTOS'][$_REQUEST['codigo']]);
	  unset($_SESSION['CADAVERES']['PROCEDIMIENTOS'][$_REQUEST['codigo']][$size-1]);
    $this->InformeCadaver($_REQUEST['cadaverId'],$_REQUEST['informe'],$_REQUEST['TipoId'],$_REQUEST['pacienteId'],$_REQUEST['nombre'],$_REQUEST['fechaNac'],
		$_REQUEST['Destino'],$_REQUEST['observacionesInforme'],$_REQUEST['firma'],'','','',$_REQUEST['patologoProfe']);
		return true;
	}

		function Tipo_Usuario_Log(){
    list($dbconn) = GetDBconn();
		$query = "SELECT * FROM userpermisos_profesionales_patologias_cadaveres WHERE usuario_id='".UserGetUID()."'";
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

	function TotalPlantillasPatologia(){

		list($dbconn) = GetDBconn();
		$query="SELECT plantilla_id,nombre_plantilla
		FROM cadaveres_tipos_plantillas";
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

	function SeleccionProcedimientoInicial(){
    if($_REQUEST['Salir']){
      $this->FormaDatosCadaver($_REQUEST['Documento'],$_REQUEST['TipoDocumento']);
			return true;
		}
		$this->FormaBusquedaProcedimientosInicial($_REQUEST['Documento'],$_REQUEST['TipoDocumento']);
		return true;
	}

	function SeleccionarProcedimientoPatologiaInicial(){
    $size=sizeof($_SESSION['CADAVERES']['PROCEDIMIENTOSINICIAL'][$_REQUEST['codigoProcedimiento']]);
	  $_SESSION['CADAVERES']['PROCEDIMIENTOSINICIAL'][$_REQUEST['codigoProcedimiento']][$size]=$_REQUEST['nombreProcedimiento'];
		$this->FormaDatosCadaver($_REQUEST['Documento'],$_REQUEST['TipoDocumento']);
		return true;
	}

	function EliminaProcedimientoInicial(){
    $size=sizeof($_SESSION['CADAVERES']['PROCEDIMIENTOSINICIAL'][$_REQUEST['codigoProcedimiento']]);
		unset($_SESSION['CADAVERES']['PROCEDIMIENTOSINICIAL'][$_REQUEST['codigoProcedimiento']][$size-1]);
    $this->FormaDatosCadaver($_REQUEST['Documento'],$_REQUEST['TipoDocumento']);
		return true;
	}

	function tiposParentescosPaciente(){
		list($dbconn) = GetDBconn();
		$query="SELECT tipo_parentesco_id,descripcion FROM tipos_parentescos";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
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

