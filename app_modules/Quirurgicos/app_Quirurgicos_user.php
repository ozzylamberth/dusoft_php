<?php


/**
 * $Id: app_Quirurgicos_user.php,v 1.3 2011/03/29 19:52:47 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO para el Manejo de Programacion e cirugias del Sistema
 */

/**
*Contiene los metodos para realizar la programacion de cirugias del paciente
*/

class app_Quirurgicos_user extends classModulo
{
	var $limit;
	var $conteo;
	function app_Quirurgicos_user()
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
		if(!$this->FrmLogueoCirugias()){
			return false;
		}
		return true;
	}
/**
* Funcion que consulta en la base de datos los permisos del usuario para trabajar en los departamentos del sistema
* @return array
*/
	function LogueoCirugias()
	{
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
		$query = "SELECT x.empresa_id,y.razon_social as descripcion1,x.centro_utilidad,z.descripcion as descripcion2,x.departamento,l.descripcion as descripcion3  FROM userpermisos_cirugia x,empresas as y,centros_utilidad as z,departamentos as l WHERE x.usuario_id = ".UserGetUID()." AND x.empresa_id=y.empresa_id AND x.empresa_id=z.empresa_id AND x.centro_utilidad=z.centro_utilidad AND x.empresa_id=l.empresa_id AND x.centro_utilidad=l.centro_utilidad AND l.departamento=x.departamento";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		if($result->EOF){
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}else{
			while ($data = $result->FetchRow()){
				$datos[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
			}
			$mtz[0]="EMPRESA";
			$mtz[1]="CENTRO UTILIDAD";
			$mtz[4]="DEPARTAMENTO";

			$vars[0]=$mtz;
			$vars[1]=$datos;
			return $vars;
		}
	}
/**
* Funcion que coloca en la session la ubicacion donde el usuario esta logueado y llama a la forma del menu que muestra todas la opciones
* @return boolean
*/
	function menu1A(){
          $_SESSION['LocalCirugias']['empresa']=$_REQUEST['datos_query']['empresa_id'];
          $_SESSION['LocalCirugias']['NombreEmp']=$_REQUEST['datos_query']['descripcion1'];
          $_SESSION['LocalCirugias']['CentroUtili']=$_REQUEST['datos_query']['centro_utilidad'];
          $_SESSION['LocalCirugias']['NombreCU']=$_REQUEST['datos_query']['descripcion2'];
          $_SESSION['LocalCirugias']['departamento']=$_REQUEST['datos_query']['departamento'];
          $_SESSION['LocalCirugias']['NombreDpto']=$_REQUEST['datos_query']['descripcion3'];
     	if(!$this->MenuQuirurjicos()){
			return false;
     	}
		return true;
	}
/**
* Funcion que llama un metodo de HTML que muestra la forma de los datos requeridos para una programacion
* @return boolean
*/
	function LlamaProgramacionQxs(){
		$this->FormaProgramacionesQuirurgicas('',1);
		return true;
	}
/**
* Funcion que llama un metodo de HTML que muestra la forma de los datos requeridos para una programacion
* @return boolean
*/
	function LlamaEleccionFechaReservaQx(){
		$this->EleccionFechaReservaQx($_REQUEST['SalasCirugia'],$_REQUEST['EquiposMoviles'],$_REQUEST['tipoHorario']);
		return true;
	}

	/**
* Funcion que llama un metodo de HTML que muestra la forma de los datos requeridos para una programacion
* @return boolean
*/
	function LlamaProgramacionQxsMeyorFecha(){
		$this->FormaProgramacionesQuirurgicas('',$_REQUEST['mayorFecha']);
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
		$query = "SELECT primer_nombre,segundo_nombre FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
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
		$Nombres=$result->fields[0]." ".$result->fields[1];
		$result->Close();
		return $Nombres;
	}
/**
* Funcion que busca en la base de datos los apellidos de un paciente a partir de su identificacion
* @return string
* @param string tipo del documento del paciente
* @param string numero del documento del paciente
*/
	function BuscarApellidosPaciente($tipo,$documento){
		list($dbconn) = GetDBconn();
		$query = "SELECT primer_apellido,segundo_apellido FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
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
		$Apellidos=$result->fields[0]." ".$result->fields[1];
		return $Apellidos;
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
/**
* Funcion que busca en la base de datos el nombre del tercero o la ips que tiene ese numero de plan
* @return string
* @param integer numero del plan del convenio de la clinica con la ips
*/
	function Responsable($Responsable){
		list($dbconn) = GetDBconn();
		$query = "SELECT tercero_id,tipo_tercero_id FROM planes WHERE plan_id='$Responsable'";
		$result = $dbconn->Execute($query);
		$TerceroId=$result->fields[0];
		$TipoTercero=$result->fields[1];
		$query = "SELECT nombre_tercero FROM terceros WHERE tercero_id='$TerceroId' AND tipo_id_tercero='$TipoTercero'";
		$result = $dbconn->Execute($query);
		$NomTercero=$result->fields[0];
		return $NomTercero;
	}
/**
* Funcion que busca en la base de datos el nombre del plan o convenio con la ips
* @return string
* @param integer numero del plan del convenio de la clinica con la ips
*/
	function PlanNombre($Responsable){
		list($dbconn) = GetDBconn();
		$query = "SELECT plan_descripcion FROM planes WHERE plan_id='$Responsable'";
		$result = $dbconn->Execute($query);
		$NomPlan=$result->fields[0];
		return $NomPlan;
	}
/**
* Funcion que valida la eleccion del usuario en la forma y asi mismo llama el metodo correspondiente a la seleccion
* @return boolean
*/
	function SeleccionProgramacionQX(){
		
		if($_REQUEST['seleccionPac']){
          	$this->DatosPaciente();
			return true;
		}elseif($_REQUEST['Selprofesionales']){
     		$this->SeleccionProfesionalesPx();
			return true;
		}elseif($_REQUEST['procedimientosSelec']){
     		$this->ProcedimientosQuirurgicos();
			return true;
		}elseif($_REQUEST['paquetes_insumos']){
			$this->LlamaReserva_Paquetes_Insumos_qx();
			return true;
		}elseif($_REQUEST['insumos']){
			$this->LlamaReserva_Insumos_qx();
			return true;
		}elseif($_REQUEST['sangre']){
			$this->LlamaReserva_Sangre_qx($_REQUEST['tipoIdPac'],$_REQUEST['PacienteId']);
			return true;
		}elseif($_REQUEST['consentimiento']){
			$this->Consentimiento_qx();
			return true;
		}elseif($_REQUEST['reservaCama']){
			$this->ResercaCamaQX($_REQUEST['FechaProgramFin']);
			return true;
		}elseif($_REQUEST['Salir']){
               if($_SESSION['ESTACION_ENFERMERIA_QX']['ACCION']==1){
                    $this->ReturnMetodoExterno('app','EstacionEnfermeria_QX','user','Menu');          
                    return true;
	     	}
		  	if($_SESSION['QUIRURGICOS']['query']){
                    $this->FormaReportesProgramaciones();
                    unset($_SESSION['CIRUGIAS']['PROGRAMACION']);
				return true;
			}elseif($_SESSION['QUIRURGICOS']['DiaEspe']){
               	if($_SESSION['QUIRURGICOS']['tipoTiempo']){
                         $this->ConsultaAgendaQuirofano($_SESSION['QUIRURGICOS']['tipoTiempo']);
                         unset($_SESSION['CIRUGIAS']['PROGRAMACION']);
                         return true;
				}else{
                         $this->FormaReportesProgramaciones('','1',$_SESSION['QUIRURGICOS']['cancelada'],$_SESSION['QUIRURGICOS']['ejecutada'],$_SESSION['QUIRURGICOS']['activa']);
                         unset($_SESSION['CIRUGIAS']['PROGRAMACION']);
					return true;
				}
			}elseif($_SESSION['QUIRURGICOS']['ReservaQuroPaciente']){
				$this->FormaRealizaReservasQuirofano($_SESSION['QUIRURGICOS']['DiaEspecial']);
				unset($_SESSION['QUIRURGICOS']['ReservaQuroPaciente']);
				unset($_SESSION['QUIRURGICOS']['DiaEspecial']);
				unset($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']);
				return true;
			}
               unset($_SESSION['CIRUGIAS']['PROGRAMACION']);
               unset($_SESSION['QUIRURGICOS']);
		     $this->MenuQuirurjicos();
			return true;
		}elseif($_REQUEST['reservar']){
			$this->ReserveEquiposQuirofanos();
			return true;
		}elseif($_REQUEST['Imprimir']){
			$this->LlamaImprimirProgramacionQX();
			return true;
		}
	}

	function LlamaReserva_Paquetes_Insumos_qx(){
          
          if(sizeof($vector=$this->paquetesInsertadosRequeridos())>0){
               for($i=0;$i<sizeof($vector);$i++){
               	$_SESSION['CIRUGIAS']['PROGRAMACION']['PAQUETES'][$vector[$i]['paquete_insumos_id']]=$vector[$i]['cantidad'];
               }
          }else{
               $vector=$this->BuscarPaqueteProcedimiento();
               for($i=0;$i<sizeof($vector);$i++){
               	$_SESSION['CIRUGIAS']['PROGRAMACION']['PAQUETES'][$vector[$i]['paquete_insumos_id']]=1;
               }
          }
          $this->Reserva_Paquetes_Insumos_qx($_REQUEST['codigoPaquete'],$_REQUEST['Descripcion']);
          return true;
     }

	function LlamaReserva_Insumos_qx(){
          $paso=$_REQUEST['paso'];
          $Of=$_REQUEST['Of'];
		$this->Reserva_Insumos_qx($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['codigoPro'],$_REQUEST['descripcionPro']);
		return true;
	}

	function Consentimiento_qx(){
		$this->LlamaTipoConsentimiento_qx();
		return true;
	}

/**
* Funcion que llama la forma que pide los datos principales para la programacion de una cirugia
* @return boolean
*/
	function DatosPaciente(){
		$action=ModuloGetURL('app','Quirurgicos','user','PedirDatosPaciente');
		if(!$this->FormaBuscarPacientePresupuesto('','',$action,'','1')){
     		return false;
		}
		return true;
	}
/**
* Valida los datos principales para la programacion de cirugias y llama la forma de pedir datos del modulo pacientes
* @return boolean
*/
	function PedirDatosPaciente(){

		if($_REQUEST['buscar']){
      $this->FormaBuscadorDiagnostico($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cirujano'],$_REQUEST['codigo'],$_REQUEST['cargo']);
			return true;
		}
		if($_REQUEST['cancelar']){
      $this->FormaProgramacionesQuirurgicas('',1);
			return true;
		}
		$TipoId=$_REQUEST['TipoDocumento'];
		$PacienteId=$_REQUEST['Documento'];
		$PlanId=$_REQUEST['Responsable'];
		$cirujano=$_REQUEST['cirujano'];
		$cargo=$_REQUEST['cargo'];
		$codigo=$_REQUEST['codigo'];

		if($_REQUEST['AdicionProfe']){
      $this->IdentificacionNuevoProfesional($TipoId,$PacienteId,$PlanId,$cirujano,$cargo,$codigo);
			return true;
		}
		if(empty($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO'])){
		$confimar=$this->ConfirmacionProgramacionesActivas($TipoId,$PacienteId);
		if($confimar==1){
      $this->frmError["MensajeError"]="Existe una programacion Activa para este Paciente";
		  $accion=ModuloGetURL('app','Quirurgicos','user','PedirDatosPaciente');
		  if($this->FormaBuscarPacientePresupuesto($TipoId,$PacienteId,$accion,$PlanId,1,$cirujano)){
			  return true;
			}
		}
		}
		if(!$PacienteId || $PlanId==-1){
      if(!$PacienteId){$this->frmError["Documento"]=1;}
			if($PlanId==-1){$this->frmError["Responsable"]=1;}
			$this->frmError["MensajeError"]="Complete los Datos.";
		  $accion=ModuloGetURL('app','Quirurgicos','user','PedirDatosPaciente');
			$bandera=1;
		  if($this->FormaBuscarPacientePresupuesto($TipoId,$PacienteId,$accion,$PlanId,$bandera,$cirujano)){
			  return true;
			}
		}
		unset($_SESSION['PACIENTES']);
		$_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$PacienteId;
		$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$TipoId;
		$_SESSION['PACIENTES']['PACIENTE']['plan_id']=$PlanId;
		$_SESSION['PACIENTES']['PACIENTE']['cirujano']=$cirujano;
		$_SESSION['PACIENTES']['PACIENTE']['cargo']=$cargo;
		$_SESSION['PACIENTES']['PACIENTE']['codigo']=$codigo;
		$_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
		$_SESSION['PACIENTES']['RETORNO']['modulo']='Quirurgicos';
		$_SESSION['PACIENTES']['RETORNO']['tipo']='user';
		$_SESSION['PACIENTES']['RETORNO']['metodo']='InsertarProgramacionQx';
		$_SESSION['PACIENTES']['RETORNO']['argumentos']=array("TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"PlanId"=>$PlanId,"cirujano"=>$cirujano,"cargo"=>$cargo,"codigo"=>$codigo);
		$this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
		return true;
	}
/**
* Inserta los datos o los Actualiza si ya existe una programacion en la base de datos
* @return boolean
*/
	function ConfirmacionProgramacionesActivas($TipoId,$PacienteId){
	  list($dbconn) = GetDBconn();
    $query="(SELECT programacion_id FROM qx_programaciones WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId' AND estado='1')
		EXCEPT
		(SELECT programacion_id FROM qx_programaciones WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId' AND estado='1' AND programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
        return 1;
			}else{
        return 0;
			}
		}
	}
/**
* Inserta los datos o los Actualiza si ya existe una programacion en la base de datos
* @return boolean
*/
	function InsertarProgramacionQx(){
	  if(!$_SESSION['PACIENTES']['RETORNO']['PASO']){
			unset($_SESSION['PACIENTES']);
			$action=ModuloGetURL('app','Quirurgicos','user','PedirDatosPaciente');
			$this->FormaBuscarPacientePresupuesto('','',$action,'','1');
			return true;
		}
	  list($dbconn) = GetDBconn();
    $departamento=$_SESSION['LocalCirugias']['departamento'];
		if($_REQUEST['cirujano']==-1){
      $cirujano='NULL';
      $tipoIdC='NULL';
		}else{
      $cadena=explode('/',$_REQUEST['cirujano']);
			$cirujano=$cadena[0];
			$tipoIdC=$cadena[1];
      $cirujano="'$cirujano'";
      $tipoIdC="'$tipoIdC'";
		}
		$PlanId=$_REQUEST['PlanId'];
    if($PlanId==-1){
      $PlanIdr='NULL';
		}else{
      $PlanIdr="'$PlanId'";
		}
		if($_REQUEST['codigo']){$diagnostico="'".$_REQUEST['codigo']."'";}else{$diagnostico='NULL';}
    if($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']){
      $query="UPDATE qx_programaciones SET departamento='$departamento',tipo_id_cirujano=$tipoIdC,
              cirujano_id=$cirujano,tipo_id_paciente='".$_REQUEST['TipoId']."',
							paciente_id='".$_REQUEST['PacienteId']."',plan_id=$PlanIdr,usuario_id='".UserGetUID()."',
							fecha_registro='".date("Y-m-d H:i:s")."',diagnostico_id=$diagnostico WHERE
							programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."'";
		}else{
			$query="SELECT nextval('qx_programaciones_programacion_id_seq')";
			$result=$dbconn->Execute($query);
			$ProgramacionId=$result->fields[0];
			$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']=$ProgramacionId;
			$query="INSERT INTO qx_programaciones (programacion_id,departamento,tipo_id_cirujano,
		                                      cirujano_id,tipo_id_paciente,paciente_id,plan_id,
																					estado,usuario_id,fecha_registro,diagnostico_id)VALUES(
																					'$ProgramacionId','$departamento',$tipoIdC,$cirujano,
																					'".$_REQUEST['TipoId']."','".$_REQUEST['PacienteId']."',
																					$PlanIdr,'1','".UserGetUID()."','".date("Y-m-d H:i:s")."',
																					$diagnostico)";
		}
		$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Guardar en la Base de Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $this->FormaProgramacionesQuirurgicas('',1);
		  return true;
		}
	}
/**
* Function que consulta los datos principales de una programacion
* @return array
*/
	function SacaDatosPacienteProgramQX($ProgramacionId){
	  list($dbconn) = GetDBconn();
    $query="SELECT x.tipo_id_cirujano,x.cirujano_id,x.tipo_id_paciente,x.paciente_id,x.plan_id,y.diagnostico_nombre,x.diagnostico_id FROM qx_programaciones x LEFT JOIN diagnosticos y on (y.diagnostico_id=x.diagnostico_id) WHERE x.programacion_id='$ProgramacionId'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		$result->Close();
 		return $vars;
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
* Funcion que busca en los profesionales especialistas existentes en la base de datos
* @return array
*/
	function profesionalesEspecialista($tipo_tercero=null,$tercero=null,$nombre=null){
    $departamento=$_SESSION['LocalCirugias']['departamento'];
		list($dbconn) = GetDBconn();

		$datos="";
		if(!empty($tipo_tercero) and !empty($tercero))
			$datos="AND x.tipo_id_tercero='$tipo_tercero'
							AND x.tercero_id='$tercero'";
		
		if(!empty($nombre))
			$datos.="AND z.nombre_tercero ILIKE '%$nombre%'";
		
		$query = "SELECT  x.tercero_id,z.nombre_tercero as nombre,x.tipo_id_tercero
		FROM profesionales x,profesionales_departamentos y,terceros z,
		profesionales_especialidades a,especialidades b
		WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND
		x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id
		AND y.departamento='".$_SESSION['LocalCirugias']['departamento']."'
		AND x.tercero_id=z.tercero_id AND x.tipo_id_tercero=z.tipo_id_tercero
		AND profesional_activo(z.tipo_id_tercero,z.tercero_id,'".$_SESSION['LocalCirugias']['departamento']."')='1'
		AND x.tercero_id=a.tercero_id AND x.tipo_id_tercero=a.tipo_id_tercero AND
		a.especialidad=b.especialidad AND b.sw_cirujano=1
		$datos
		ORDER BY z.nombre_tercero";
		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}
/**
* Funcion que busca en los profesionales especialistas anestesiologos existentes en la base de datos
* @return array
*/
	function profesionalesEspecialistaAnestecistas($tipo_tercero,$tercero,$nombre){
    $departamento=$_SESSION['LocalCirugias']['departamento'];
		list($dbconn) = GetDBconn();
		
		$datos="";
		if(!empty($tipo_tercero) and !empty($tercero))
		{
			$datos.="AND x.tipo_id_tercero='$tipo_tercero'
							AND x.tercero_id='$tercero'";
		}
		if(!empty($nombre))
		{
			$datos.="AND c.nombre_tercero ILIKE '%$nombre%'";
		}
		$query = "SELECT  x.tercero_id,c.nombre_tercero as nombre,x.tipo_id_tercero
    FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c
    WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND x.tipo_id_tercero=y.tipo_id_tercero AND
    x.tercero_id=y.tercero_id AND y.departamento='$departamento' AND z.especialidad=l.especialidad AND
    z.sw_anestesiologo='1' AND x.tercero_id=l.tercero_id AND x.tipo_id_tercero=l.tipo_id_tercero  AND
    x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero AND
    profesional_activo(c.tipo_id_tercero,c.tercero_id,'$departamento')='1'
    $datos
		ORDER BY c.nombre_tercero";
		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}
  
   
    
  function BuscarProcedimientosInsertados($programacion_qx,$cargo){          
      
      list($dbconn) = GetDBconn();
      $query = "SELECT b.procedimiento_opcion,b.descripcion
                FROM qx_cups_opc_procedimientos_programacion a,qx_cups_opciones_procedimientos b 
                WHERE a.programacion_id='".$programacion_qx."' 
                AND a.procedimiento_qx='".$cargo."' 
                AND a.procedimiento_qx=b.cargo AND a.procedimiento_opcion=b.procedimiento_opcion";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }else{
        while (!$result->EOF) {
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }                 
      return $vars;
      
  }

/**
* Funcion que busca en los profesionales especialistas anestesiologos existentes en la base de datos
* @return array
*/
	function profesionalesEspecialistaCiculantes(){
    $departamento=$_SESSION['LocalCirugias']['departamento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT x.tercero_id,c.nombre_tercero as nombre,x.tipo_id_tercero
    FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c
    WHERE x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id AND y.departamento='$departamento' AND
    z.especialidad=l.especialidad AND z.sw_circulante='1' AND x.tercero_id=l.tercero_id AND
    x.tipo_id_tercero=l.tipo_id_tercero  AND x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero AND
    profesional_activo(c.tipo_id_tercero,c.tercero_id,'$departamento')='1'
    ORDER BY c.nombre_tercero";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

/**
* Funcion que busca en los profesionales especialistas anestesiologos existentes en la base de datos
* @return array
*/
	function profesionalesEspecialistaInstrumentistas(){
    $departamento=$_SESSION['LocalCirugias']['departamento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT  x.tercero_id,c.nombre_tercero as nombre,x.tipo_id_tercero
    FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c
    WHERE x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id AND y.departamento='$departamento' AND
    z.especialidad=l.especialidad AND z.sw_instrumentista='1' AND x.tercero_id=l.tercero_id AND
    x.tipo_id_tercero=l.tipo_id_tercero  AND x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero AND
    profesional_activo(c.tipo_id_tercero,c.tercero_id,'$departamento')='1' ORDER BY c.nombre_tercero";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

/**
* Funcion que busca los profesionales Ayudantes existentes en la base de datos
* @return array
*/
	function profesionalesAyudantes(){
    $departamento=$_SESSION['LocalCirugias']['departamento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT x.tercero_id,z.nombre_tercero as nombre,x.tipo_id_tercero
    FROM profesionales x,profesionales_departamentos y,terceros z
    WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND x.tipo_id_tercero=y.tipo_id_tercero AND
    x.tercero_id=y.tercero_id AND y.departamento='$departamento' AND x.tercero_id=z.tercero_id AND
    x.tipo_id_tercero=z.tipo_id_tercero AND profesional_activo(z.tipo_id_tercero,z.tercero_id,'$departamento')='1'
    ORDER BY z.nombre_tercero";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}
/**
* Funcion que retorna el nombre del profesional a partir de su codigo de profesional
* @return array
* @param string tipo de identificacion del profesional
* @param integer numero que identifica al profesional
*/
	function NombreProfesional($Profesional,$tipoProfesional){

		list($dbconn) = GetDBconn();
		$query = "SELECT nombre FROM profesionales WHERE tercero_id='$Profesional' AND tipo_id_tercero='$tipoProfesional'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
      $i=0;
			while(!$result->EOF){
			 $var=$result->GetRowAssoc($toUpper=false);
			 $result->MoveNext();
       $i++;
			}
		}
		$result->Close();
 		return $var;
	}
/**
* Funcion que retorna un arreglo de las vias de acceso de la base de datos para realizar una cirugia
* @return array
*/
	function ViaAccesosCirugia(){

		list($dbconn) = GetDBconn();
		$query = "SELECT via_acceso,descripcion FROM qx_vias_acceso";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'qx_vias_acceso' esta vacia ";
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
* Funcion que llama la forma donde se hace la seleccion de la reserva de equipos y quirofanos
* @return boolean
*/
	function ValidacionSeleccionEquipos(){
	  if($_REQUEST['Cancelar']){
      $this->FormaProgramacionesQuirurgicas('',1);
			return true;
		}
    $this->EleccionFechaReservaQx($_REQUEST['salasCirugia'],$_REQUEST['equiposMobiles'],$_REQUEST['tipoHorario']);
		return true;
	}
/**
* Funcion trae de la base de datos los deferentes quirofanos que pertenecen al departamento en donde el usuario se encuentra logueado
* @return array
*/
	function SeleccionQuirofanosDpto(){
    $departamento=$_SESSION['LocalCirugias']['departamento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT quirofano,descripcion,abreviatura 
		FROM qx_quirofanos 
		WHERE departamento='$departamento' AND estado='1' AND sw_programacion='1'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
        while (!$result->EOF) {
				  $vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
			  }
			}
		}
		$result->Close();
 		return $vars;
	}
/**
* Funcion que inserta en la B:D. los datos del profesional que intervienen en la cirugia o las actualiza si la programcion ya existe
* @return boolean
*/
	function ValidacionProfesionalesQx(){
	  $Cancelar=$_REQUEST['Cancelar'];
		if($Cancelar){
		  $this->FormaProgramacionesQuirurgicas('',1);
			return true;
		}
		$anestesista=$_REQUEST['anestesista'];
		if($anestesista==-1){
      $tipoIdTerecero1='NULL';
		  $TereceroId1='NULL';
		}else{
			$cadena=explode('/',$anestesista);
			$tipoIdTerecero=$cadena[1];
			$TereceroId=$cadena[0];
      $tipoIdTerecero1="'$tipoIdTerecero'";
		  $TereceroId1="'$TereceroId'";
		}
		if($_REQUEST['instrumentista']==-1){
      $tipoIdInstrumentista1='NULL';
		  $TereceroIdInstrumentista1='NULL';
		}else{
			$cadena=explode('/',$_REQUEST['instrumentista']);
			$tipoIdInstrumentista=$cadena[1];
			$TereceroIdInstrumentista=$cadena[0];
      $tipoIdInstrumentista1="'$tipoIdInstrumentista'";
		  $TereceroIdInstrumentista1="'$TereceroIdInstrumentista'";
		}
		if($_REQUEST['circulante']==-1){
      $tipoIdCirculante1='NULL';
		  $TereceroIdCirculante1='NULL';
		}else{
			$cadena=explode('/',$_REQUEST['circulante']);
			$tipoIdCirculante=$cadena[1];
			$TereceroIdCirculante=$cadena[0];
      $tipoIdCirculante1="'$tipoIdCirculante'";
		  $TereceroIdCirculante1="'$TereceroIdCirculante'";
		}

    if($_REQUEST['ayudante']==-1){
      $tipoIdAyudante1='NULL';
		  $TereceroIdAyudante1='NULL';
		}else{
			$cadena=explode('/',$_REQUEST['ayudante']);
			$tipoIdAyudante=$cadena[1];
			$TereceroIdAyudante=$cadena[0];
      $tipoIdAyudante1="'$tipoIdAyudante'";
		  $TereceroIdAyudante1="'$TereceroIdAyudante'";
		}

		list($dbconn) = GetDBconn();
		if($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']){
		  $query="SELECT * FROM qx_anestesiologo_programacion WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
				return false;
			}else{
        $datos=$result->RecordCount();
				if($datos){
          $query="UPDATE qx_anestesiologo_programacion
					SET tipo_id_tercero=$tipoIdTerecero1,tercero_id=$TereceroId1,
					tipo_id_instrumentista=$tipoIdInstrumentista1,instrumentista_id=$TereceroIdInstrumentista1,tipo_id_circulante=$tipoIdCirculante1,circulante_id=$TereceroIdCirculante1,
          tipo_id_ayudante=$tipoIdAyudante1,ayudante_id=$TereceroIdAyudante1
					WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."'";
				}else{
          $query="INSERT INTO qx_anestesiologo_programacion(tipo_id_tercero,tercero_id,programacion_id,
					tipo_id_instrumentista,instrumentista_id,tipo_id_circulante,circulante_id,tipo_id_ayudante,ayudante_id)
			     VALUES($tipoIdTerecero1,$TereceroId1,'".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."',
					 $tipoIdInstrumentista1,$TereceroIdInstrumentista1,$tipoIdCirculante1,$TereceroIdCirculante1,
           $tipoIdAyudante1,$TereceroIdAyudante1)";
				}
        $result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
					return false;
				}
			}
		}
		$this->FormaProgramacionesQuirurgicas('',1);
	  return true;
	}
/**
* Funcion que valida las reservas de equipos y los quirofanos de una programacion y los inserta en la B:D.
* @return boolean
*/
	function ValidacionReservasQXyEquipo()
     {
     	list($dbconn) = GetDBconn();
          if($_REQUEST['Cancelar']){
               $this->FormaProgramacionesQuirurgicas('',1);
               return true;
          }
          if($_REQUEST['seleccionReserv']){
			foreach($_REQUEST['seleccionReserv'] as $x=>$valor){
               	$vectorTemp[]=$valor;
               }
               }else{
				$vectorTemp=$_REQUEST['vectorTemp'];
               }
               if($vectorTemp<1)
               {
				$mensaje='No Selecciono ningun Rango de la Reserva De Cick para Continuar';
                    $titulo='RESERVA QUIROFANO';
                    $accion=ModuloGetURL('app','Quirurgicos','user','LlamaEleccionFechaReservaQx',array("EquiposMoviles"=>$_REQUEST['EquiposMoviles'],"equipos"=>$_REQUEST['equipos'],"rango"=>$_REQUEST['rango'],"SalasCirugia"=>$_REQUEST['SalasCirugia'],"tipoHorario"=>$_REQUEST['tipoHorario']));
                    $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
                    return true;
               }
               //verificacion  de la programacion de los profesionales
               for($i=0;$i<sizeof($vectorTemp);$i++)
               {
                    $rango1=explode('/',$vectorTemp[$i]);      
                    $rango2=explode('/',$vectorTemp[$i+1]);      
                    
                    if(empty($rango2[0])&&empty($rango2[1])){
                         (list($fech,$hor)=explode(' ',$rango1[1]));        
                         (list($ano,$mes,$dia)=explode('-',$fech));
                         (list($hh,$mm)=explode(':',$hor));
                         $rango2[0]=$rango1[0];
                         $rango2[1]=date("Y-m-d H:i:s",mktime($hh,($mm+59),0,$mes,$dia,$ano));       
                    }
                    $query="SELECT * FROM qx_procedimientos_programacion a,qx_quirofanos_programacion b  WHERE a.programacion_id=b.programacion_id AND b.qx_tipo_reserva_quirofano_id='3' AND '".$rango1[1]."' >= b.hora_inicio AND '".$rango2[1]."' <= b.hora_fin AND a.tipo_id_cirujano||' '||a.cirujano_id IN (SELECT tipo_id_cirujano||' '||cirujano_id FROM qx_procedimientos_programacion WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."')";                  
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
                    return false;
                    }else{
                         $datos=$result->RecordCount();
                         if($datos>0)
                         {
                              $vars=$result->GetRowAssoc($ToUpper = false);
                              if($vars['programacion_id'] <> $_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']){
                                   $nom = $this->NombreTercero($vars['tipo_id_cirujano'],$vars['cirujano_id']);
                                   $mensaje='El profesional '.$nom['nombre_tercero'].' ya esta programado en otra cirugia';
                                   $titulo='RESERVA QUIROFANO';
                                   $accion=ModuloGetURL('app','Quirurgicos','user','LlamaEleccionFechaReservaQx',array("EquiposMoviles"=>$_REQUEST['EquiposMoviles'],"equipos"=>$_REQUEST['equipos'],"rango"=>$_REQUEST['rango'],"SalasCirugia"=>$_REQUEST['SalasCirugia'],"tipoHorario"=>$_REQUEST['tipoHorario']));
                                   $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
                                   return true;
                              }
                         }
                    }      
               }   
               //verificacion  de la programacion de los equipos
               if($_REQUEST['equipos']){
                    $equipos=$_REQUEST['equipos'];
                    for($i=0;$i<(sizeof($vectorTemp)-1);$i++){
                         $rango1=explode('/',$vectorTemp[$i]);
                         $rango2=explode('/',$vectorTemp[$i+1]);
                         for($j=0;$j<sizeof($equipos);$j++){
                              $cadena=explode('/',$equipos[$j]);
                              
                              $query="SELECT b.programacion_id 
                              	   FROM qx_equipos_programacion a,qx_quirofanos_programacion b 
                                      WHERE a.equipo_id='".$cadena[1]."' 
                                      AND a.qx_quirofano_programacion_id = b.qx_quirofano_programacion_id 
                                      AND b.qx_tipo_reserva_quirofano_id! = '0' 
                                      AND '".$rango1[1]."' >= b.hora_inicio 
                                      AND '".$rango2[1]."' < b.hora_fin";
                              
                              $result = $dbconn->Execute($query);
                              if($dbconn->ErrorNo() != 0){
                                   $this->error = "Error al Cargar el Modulo";
                                   $this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
                                   return false;
                              }else{
                                   $datos=$result->RecordCount();
                                   if($datos){
                                        
                                        list($vectorEQ) = $result->FetchRow();
                                        
                                        if(empty($vectorEQ))
                                        { $vectorEQ = $_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']; }
                                        
                                        if($vectorEQ != $_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO'])
                                        {
                                             $mensaje='Ocurrio un error al crear la reserva, el Equipo '.$cadena[1].' que ud. eligiò no se encuentra disponible para el Horario Seleccionado';
                                             $titulo='RESERVA QUIROFANO';
                                             $accion=ModuloGetURL('app','Quirurgicos','user','LlamaEleccionFechaReservaQx',array("EquiposMoviles"=>$_REQUEST['EquiposMoviles'],"equipos"=>$_REQUEST['equipos'],"rango"=>$_REQUEST['rango'],"SalasCirugia"=>$_REQUEST['SalasCirugia'],"tipoHorario"=>$_REQUEST['tipoHorario']));
                                             $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
                                             return true;
                                        }
                                   }
                              }
                         }
                    }
               }
               $datos=$this->obtenerDatosProgramacionQX($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']);
               if($datos){
                    if($_REQUEST['ordenBorrar']!=1){
                         $arreglo=array("ordenBorrar"=>1,"vectorTemp"=>$vectorTemp,"EquiposMoviles"=>$_REQUEST['EquiposMoviles'],"equipos"=>$_REQUEST['equipos'],"rango"=>$_REQUEST['rango'],"SalasCirugia"=>$_REQUEST['SalasCirugia'],"tipoHorario"=>$_REQUEST['tipoHorario']);
                         $this->LlamaConfirmarAccion($arreglo,'','app','Quirurgicos','ValidacionReservasQXyEquipo','LlamaProgramacionQxs','Existe una reserva, Esta seguro de querer Modificarla','RESERVA DE QUIROFANOS','ACEPTAR','CANCELAR');
                         return true;
                    }else{
                         $query="UPDATE qx_quirofanos_programacion SET qx_tipo_reserva_quirofano_id='0' WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."'";
                         $result = $dbconn->Execute($query);
                         if($dbconn->ErrorNo() != 0) {
                              $this->error = "Error al Cargar el Modulo";
                              $this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
                              return false;
                         }
                    }
               }
               $valorTmp=$vectorTemp[0];
               $cadena=explode('/',$valorTmp);
               $Quirofano=$cadena[0];
               $FechaIni=$cadena[1];
               $Fecha=$this->FechaStamp($FechaIni);
               $infoCadena = explode ('/', $Fecha);
               $dia=$infoCadena[0];
               $mes=$infoCadena[1];
               $ano=$infoCadena[2];
               $rango=$_REQUEST['rango'];
               if(sizeof($vectorTemp)==1){
                    $HoraDef=$this->HoraStamp($FechaIni);
                    $infoCadena = explode (':',$HoraDef);
                    $Hora=$infoCadena[0];
                    $Minutos=$infoCadena[1];
                    $FechaFin=date('Y-m-d H:i:s',mktime($Hora,($Minutos+($rango-1)),0,$mes,$dia,$ano));
               }else{
                    $cont=sizeof($vectorTemp)-1;
                    $valorTmp=$vectorTemp[$cont];
                    $cadena=explode('/',$valorTmp);
                    $FechaFin=$cadena[1];
                    $HoraDef=$this->HoraStamp($FechaFin);
                    $infoCadena = explode (':',$HoraDef);
                    $Hora=$infoCadena[0];
                    $Minutos=$infoCadena[1];
                    $FechaFin=date('Y-m-d H:i:s',mktime($Hora,$Minutos+($rango-1),0,$mes,$dia,$ano));
               }
               $query="SELECT nextval('qx_quirofanos_programacion_qx_quirofano_programacion_id_seq')";
               $result=$dbconn->Execute($query);
               $QuirofanoProgramacion=$result->fields[0];
               $query="INSERT INTO qx_quirofanos_programacion(qx_quirofano_programacion_id,quirofano_id,hora_inicio,hora_fin,programacion_id,qx_tipo_reserva_quirofano_id,departamento,usuario_id,fecha_registro)
                                   VALUES('$QuirofanoProgramacion','$Quirofano','$FechaIni','$FechaFin','".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."','3','".$_SESSION['LocalCirugias']['departamento']."','".UserGetUID()."','".date("Y-m-d H:i:s")."')";
               $result = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
                    return false;
               }else
               {
               	if($_REQUEST['equipos']){
                         for($i=0;$i<sizeof($equipos);$i++){
                              $cadena=explode('/',$equipos[$i]);
                              $query="INSERT INTO qx_equipos_programacion(equipo_id,qx_quirofano_programacion_id)
                              VALUES('".$cadena[1]."','$QuirofanoProgramacion')";
                              $result = $dbconn->Execute($query);
                              if($dbconn->ErrorNo() != 0) {
                                   $this->error = "Error al Cargar el Modulo";
                                   $this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
                                   return false;
                              }
                         }
                    }
               }
          $mensaje='Su Reserva ha sido Creada Exitosamente';
          $titulo='RESERVA QUIROFANO';
          $accion=ModuloGetURL('app','Quirurgicos','user','LlamaProgramacionQxs');
          $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
          return true;
     }
/**
* Funcion que consulta los equipos que tiene un quirofano, es decir son propios o fijos dentro del quirofano
* @return array
* @param integer numero que identifica al quirofano en el departamento
*/
	function EquiposFijosQuirofanos($quirofano)
     {
          $departamento=$_SESSION['LocalCirugias']['departamento'];
	     list($dbconn) = GetDBconn();
          $query = "SELECT x.equipo_id,x.descripcion 
          FROM qx_equipos_quirofanos x,qx_quirofanos y 
          WHERE y.departamento='$departamento' AND y.estado='1' AND y.quirofano='$quirofano' AND 
          y.quirofano=x.quirofano_id AND x.estado='1' AND y.sw_programacion='1'";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{
               $datos=$result->RecordCount();
               if($datos){
				while (!$result->EOF) {
                    	$vars[]=$result->GetRowAssoc($toUpper=false);
                         $result->MoveNext();
                    }
               }
          }
          $result->Close();
          return $vars;
	}
/**
* Funcion que consulta los equipos moviles que se encuentran en el departamento en el que el usuario esta loqueado
* @return array
*/
	function EquiposMovilesDpto(){

	  $departamento=$_SESSION['LocalCirugias']['departamento'];
    list($dbconn) = GetDBconn();
		$query = "SELECT distinct b.descripcion,b.tipo_equipo_id,c.descripcion as departamento  FROM qx_equipos_moviles a,qx_tipo_equipo_movil b,departamentos c
		WHERE a.tipo_equipo_id=b.tipo_equipo_id AND a.departamento=c.departamento";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
        while (!$result->EOF) {
				  $vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
			  }
			}
		}
		$result->Close();
 		return $vars;
	}
/**
* Funcion que comprueba si esta fecha que llega por paramentro se encuentra reservada por otra programacion
* @return array
* @param integer numero que identifica al quirofano
* @param date fecha que se va a evasluar en la reserva
* @param time rango de tiempo minimo para realizar una reserva
*/
	function ComprobarExisReserva($Quiro,$SumaHora,$rango,$plan,$empresa,$IdTercero,$TerceroId){
    list($dbconn) = GetDBconn();
		$time=date("H:i:s",mktime(0,(0+$rango),0,date('m'),date('d'),date('Y')));
		$query ="SELECT revisar_rango_reserva_quirofano('$Quiro','$SumaHora','$time','$plan','$empresa', '$IdTercero','$TerceroId')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $respuesta=$result->fields[0];
      if($respuesta=='t'){
        $vars=1;
			}else{
        $vars=0;
			}
		}
		$result->Close();
 		return $vars;
	}
	/**
* Funcion que comprueba si esta fecha que llega por paramentro se encuentra reservada por otra programacion
* @return array
* @param integer numero que identifica al quirofano
* @param date fecha que se va a evasluar en la reserva
* @param time rango de tiempo minimo para realizar una reserva
*/
	function ComprobarExisReservaEquipo($Equipo,$SumaHora,$rango)
     {
		list($dbconn) = GetDBconn();
		$time=date("H:i:s",mktime(0,(0+$rango),0,date('m'),date('d'),date('Y')));
		$query ="SELECT revisar_reserva_equipo('$Equipo','$SumaHora','$time','".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
          	$respuesta=$result->fields[0];
               if($respuesta=='t'){
               	$vars=1;
               }else{
               	$vars=0;
               }
          }
		$result->Close();
 		return $vars;
	}
/**
* Funcion que comprueba si esta fecha que llega por paramentro se encuentra reservada por otra programacion
* @return array
* @param integer numero que identifica al quirofano
* @param date fecha que se va a evasluar en la reserva
* @param time rango de tiempo minimo para realizar una reserva
*/
	function ComprobarExisReservaProgram($Quiro,$SumaHora,$rango,$programacion)
     {
		list($dbconn) = GetDBconn();
		$time=date("H:i:s",mktime(0,(0+$rango),0,date('m'),date('d'),date('Y')));
		$query = "SELECT revisar_rango_programacion('$Quiro','$SumaHora','$time','$programacion')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
               $respuesta=$result->fields[0];
               	if($respuesta=='t')
                    {
        				$vars=1;
				}else{
        				$vars=0;
				}
          }
		$result->Close();
 		return $vars;
	}
/**
* Funcion que cosulta en la base de datos los datos de la reserva de equipos y quirofanos de una programacion
* @return array
* @param integer numero que identifica la programacion
*/
	function obtenerDatosProgramacionQX($ProgramacionId){
    list($dbconn) = GetDBconn();
		$query="SELECT quirofano_id,hora_inicio,hora_fin,qx_quirofano_programacion_id FROM qx_quirofanos_programacion WHERE programacion_id='$ProgramacionId' AND qx_tipo_reserva_quirofano_id != '0'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
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
/**
* Funcion que consulta en la B.D. la descripcion y la abreviarura de un quirofano a partir de su codigo de identificacion
* @return array
* @param integer numero que identifica al quirofano
*/
	function DescripcionQuirofano($quirofano){
    list($dbconn) = GetDBconn();
		$query="SELECT descripcion,abreviatura 
		FROM qx_quirofanos 
		WHERE quirofano='$quirofano' AND sw_programacion='1'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
        $vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		$result->Close();
 		return $vars;
	}
/**
* Funcion que consulta en la B.D. los equipos que intervienen en una programacion
* @return array
* @param integer numero que identifica la programacion
*/
	function SeleccionEquiposProgramacion($ProgramacionIdQuiro){
    $departamento=$_SESSION['LocalCirugias']['departamento'];
		list($dbconn) = GetDBconn();
		$query="SELECT y.descripcion FROM qx_equipos_programacion x,qx_equipos_moviles y WHERE x.qx_quirofano_programacion_id='$ProgramacionIdQuiro' AND x.equipo_id=y.equipo_id AND y.estado='1'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
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
/**
* Funcion que consulta en la B.D. los datos del anestesiologo de una programacion si ya existe
* @return array
* @param integer numero que identifica la programacion
*/
	function obtenerDatosAnestesiologoQX($ProgramacionId){

		list($dbconn) = GetDBconn();
		$query="SELECT a.tipo_id_tercero,a.tercero_id,a.tipo_id_instrumentista,a.instrumentista_id,a.tipo_id_circulante,a.circulante_id,a.tipo_id_ayudante,a.ayudante_id		
		FROM qx_anestesiologo_programacion a,qx_programaciones b
		WHERE a.programacion_id='$ProgramacionId' AND a.programacion_id=b.programacion_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
		    $vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		$result->Close();
 		return $vars;
	}
	
/**
* Funcion que busca si el paciente tuene asignada una cita pre-anestesica
* @return array
*/
function obtenerDatosCitaAnestesiologia($ProgramacionId){

		list($dbconn) = GetDBconn();
		$query="SELECT e.hora,f.fecha_turno,f.profesional_id,f.tipo_id_profesional,f.consultorio_id
		FROM qx_programaciones b,agenda_citas_asignadas c,tipos_cita d,agenda_citas e,agenda_turnos f		
		WHERE b.programacion_id='$ProgramacionId' AND 
		b.tipo_id_paciente=c.tipo_id_paciente AND b.paciente_id=c.paciente_id AND  
		c.tipo_cita=d.tipo_cita AND d.sw_anestesiologia='1' AND 
		c.agenda_cita_id=e.agenda_cita_id AND 
		e.agenda_turno_id=f.agenda_turno_id ORDER BY f.fecha_registro DESC,e.hora DESC";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
		    $vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		$result->Close();
 		return $vars;
	}
	
/**
* Funcion que inserta en la base de datos un procedimiento de una programacion  si este no existe o lo actualiza de lo contrario
* @return boolean
*/
	function InsertarProcedimientosQururgicos(){

    if($_REQUEST['BuscarProcedimiento']){
      $this->BuscadorProcedimientos();
			return true;
		}
    list($dbconn) = GetDBconn();
		if($_REQUEST['Salir']){
      $this->FormaProgramacionesQuirurgicas('',1);
			return true;
		}
		if($_REQUEST['Cancelar']){
      $this->ProcedimientosQuirurgicos();
			return true;
		}
		if(!$_REQUEST['codigos'] || !$_REQUEST['procedimiento'] || $_REQUEST['Responsable']==-1){
			if(!$_REQUEST['codigos'] || !$_REQUEST['procedimiento']){$this->frmError["procedimiento"]=1;}
			if($_REQUEST['Responsable']==-1){$this->frmError["Responsable"]=1;}
			$this->frmError["MensajeError"]="Complete los Datos del Procedimiento.";
		  $this->ProcedimientosQuirurgicos($_REQUEST['codigos'],$_REQUEST['procedimiento'],$_REQUEST['cirujano'],$_REQUEST['ayudante'],'','','','',$_REQUEST['Responsable']);
			return true;
		}
		/*if($_REQUEST['pediatrico']){
      if($_REQUEST['pediatra']==-1){
			  $this->frmError["pediatra"]=1;
				$this->frmError["MensajeError"]="Este procedimiento esta calificado como Pediatrico, Seleccione un Profesional en Pediatria";
				$this->ProcedimientosQuirurgicos($_REQUEST['codigos'],$_REQUEST['procedimiento'],$_REQUEST['cirujano'],$_REQUEST['ayudante'],'','','','',$_REQUEST['Responsable'],'',1);
				return true;
			}else{
			  $cadena=explode('/',$_REQUEST['pediatra']);
				$pediatra=$cadena[0];
				$tipoidpediatra=$cadena[1];
			}
		}else{
      $pediatrico=$this->HallarProcedPediatrico($_REQUEST['codigos']);
			if($pediatrico){
				$this->frmError["MensajeError"]="Este procedimiento esta calificado como Pediatrico, Seleccione un Profesional en Pediatria";
				$this->ProcedimientosQuirurgicos($_REQUEST['codigos'],$_REQUEST['procedimiento'],$_REQUEST['cirujano'],$_REQUEST['ayudante'],'','','','',$_REQUEST['Responsable'],'',1);
				return true;
			}
		}*/
		/*if($_REQUEST['bilateral']){
      if($_REQUEST['bilateral']==-1){
			  $this->frmError["bilateral"]=1;
				$this->frmError["MensajeError"]="El procedimiento esta identificado como Posible Bilateral, identifique la via";
				$this->ProcedimientosQuirurgicos($_REQUEST['codigos'],$_REQUEST['procedimiento'],$_REQUEST['cirujano'],$_REQUEST['ayudante'],'','','','',$_REQUEST['Responsable'],'','','',1);
				return true;
			}else{
			  $cadena=explode('/',$_REQUEST['pediatra']);
				$pediatra=$cadena[0];
				$tipoidpediatra=$cadena[1];
			}
		}else{
      $bilateral=$this->HallarProcedimientoBilateral($_REQUEST['codigos']);
			if($bilateral){
				$this->frmError["MensajeError"]="El procedimiento esta identificado como Posible Bilateral, identifique la via";
		    $this->ProcedimientosQuirurgicos($_REQUEST['codigos'],$_REQUEST['procedimiento'],$_REQUEST['cirujano'],$_REQUEST['ayudante'],'','','','',$_REQUEST['Responsable'],'','','',1);
				return true;
			}
		}*/
		if($_REQUEST['cirujano']!=-1){
		  $cadena=explode('/',$_REQUEST['cirujano']);
      $cirujano=$cadena[0];$cirujano1="'$cirujano'";
			$tipoidcirujano=$cadena[1];$tipoidcirujano1="'$tipoidcirujano'";
		}else{
      $cirujano1='NULL';
			$tipoidcirujano1='NULL';
		}
		if($_REQUEST['ayudante']!=-1){
		  $cadena=explode('/',$_REQUEST['ayudante']);
      $ayudante=$cadena[0];$ayudante1="'$ayudante'";
			$tipoidayudante=$cadena[1];$tipoidayudante1="'$tipoidayudante'";
		}else{
      $ayudante1='NULL';
			$tipoidayudante1='NULL';
		}
		if($_REQUEST['viabilateral']!=-1 && !empty($_REQUEST['viabilateral'])){
		  $viabilateral=$_REQUEST['viabilateral'];
      $viabilateral1="'$viabilateral'";
		}else{
      $viabilateral1='NULL';
		}
		if($_REQUEST['Modificar']){
      
      $query="UPDATE qx_procedimientos_programacion SET procedimiento_qx='".$_REQUEST['codigos']."',
			tipo_id_cirujano=$tipoidcirujano1,cirujano_id=$cirujano1,plan_id='".$_REQUEST['Responsable']."',
			via_procedimiento_bilateral=$viabilateral1,observaciones='".$_REQUEST['observacion']."' WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."'
			AND procedimiento_qx='".$_REQUEST['codigos1']."'";
			$result=$dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
				return false;
			}else{
			  /*if($_REQUEST['pediatrico']){
					$query="UPDATE qx_procedimientos_programacion_pediatricos SET procedimiento_qx='".$_REQUEST['codigos']."',pediatra_id='$pediatra',tipo_id_pediatra='$tipoidpediatra'
					WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND procedimiento_qx='".$_REQUEST['codigos1']."'";
					$result=$dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
						return false;
					}
				}*/
			}
      unset($_REQUEST['observacion']);
			$this->ProcedimientosQuirurgicos();
			return true;
		}
		$comprobarProc=$this->ComprobacionProcedimientos($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO'],$_REQUEST['codigos']);
		if($comprobarProc!=1){
		  $this->frmError["MensajeError"]="Este Procedimiento ya ha sido Insertado en esta Programacion.";
		  $this->ProcedimientosQuirurgicos($_REQUEST['codigos'],$_REQUEST['procedimiento'],$_REQUEST['cirujano'],$_REQUEST['ayudante'],'','','','',$_REQUEST['Responsable']);
			return true;
		}
		if($_REQUEST['NumerOrden']){
			$query="SELECT * FROM qx_programaciones_ordenes WHERE  numero_orden_id='".$_REQUEST['NumerOrden']."' AND procedimiento_qx='".$_REQUEST['codigos']."'";
			$result=$dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
				return false;
			}else{
        $datos=$result->RecordCount();
				if(!$datos){
          $query="INSERT INTO qx_programaciones_ordenes(numero_orden_id,programacion_id,procedimiento_qx)
					VALUES ('".$_REQUEST['NumerOrden']."','".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."','".$_REQUEST['codigos']."')";
					$result=$dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
						return false;
					}
				}
			}
		}
		$query="INSERT INTO qx_procedimientos_programacion(procedimiento_qx,tipo_id_cirujano,cirujano_id,programacion_id,plan_id,via_procedimiento_bilateral,observaciones)VALUES
		('".$_REQUEST['codigos']."',$tipoidcirujano1,$cirujano1,'".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."','".$_REQUEST['Responsable']."',$viabilateral1,'".$_REQUEST['observacion']."')";
		$result=$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
			return false;
		}else{
      /*if($_REQUEST['pediatrico']){
				$query="INSERT INTO qx_procedimientos_programacion_pediatricos(procedimiento_qx,programacion_id,tipo_id_pediatra,pediatra_id)
				VALUES('".$_REQUEST['codigos']."','".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."','$tipoidpediatra','$pediatra')";
				$result=$dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
					return false;
				}
			}*/
		}
		$this->ProcedimientosQuirurgicos();
		return true;
	}

	function LlamaBuscadorProcedimientos(){
    $this->BuscadorProcedimientos($_REQUEST['tipoProcedimiento'],$_REQUEST['codigos'],$_REQUEST['procedimiento']);
		return true;
	}

	function BusquedaProcedimientosQX($tipoProcedimiento,$codigos,$procedimiento){

		list($dbconn) = GetDBconn();
		$query="SELECT d.grupo_tipo_cargo,d.cargo,d.descripcion
		FROM qx_grupos_tipo_cargo a,tipos_cargos c,cups d
		WHERE a.grupo_tipo_cargo=c.grupo_tipo_cargo AND
		c.grupo_tipo_cargo=d.grupo_tipo_cargo AND c.tipo_cargo=d.tipo_cargo";
    if(!empty($tipoProcedimiento) && $tipoProcedimiento!=-1){
		  (list($val,$descrip)=explode('/',$tipoProcedimiento));
		  $query.=" AND c.tipo_cargo='".$val."'";
		}
    if($codigos){
      $query.=" AND d.cargo='".$codigos."'";
		}
		if($procedimiento){
      $query.=" AND d.descripcion LIKE '%".strtoupper($procedimiento)."%'";
    }
		$query.=" ORDER BY d.descripcion";
		if(empty($_REQUEST['conteo'])){
		  $result = $dbconn->Execute($query);
			if($result->EOF){
				$this->error = "Error al ejecutar la consulta.<br>";
				$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
				return false;
			}
			$this->conteo=$result->RecordCount();
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
		if($result->EOF){
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}else{
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

	function SeleccionProcedimiento(){
    $this->ProcedimientosQuirurgicos($_REQUEST['cargo'],$_REQUEST['descripcion']);
		return true;
	}

	/*function HallarProcedPediatrico($codigos){
		list($dbconn) = GetDBconn();
		$query="SELECT procedimiento FROM qx_procedimientos_pediatricos WHERE procedimiento='$codigos'";
		$result=$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
        return 1;
			}
		}
 		return 0;
	}*/

  function HallarProcedimientoBilateral($codigos){
		list($dbconn) = GetDBconn();
		$query="SELECT sw_bilateral FROM cups WHERE cargo='$codigos'";
		$result=$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
        if($result->fields[0]==1){
          return 1;
				}else{
          return 0;
				}
			}
		}
	}
/**
* Funcion que consulta de la base de tado los tipos de cargos agrupados
* @return array
*/
	function tiposdeProcedimientos(){
    list($dbconn) = GetDBconn();
		$query="SELECT a.tipo_cargo,a.grupo_tipo_cargo,a.descripcion FROM tipos_cargos a,qx_grupos_tipo_cargo b WHERE a.grupo_tipo_cargo=b.grupo_tipo_cargo";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
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
/**
* Funcion consulta de la base de datos los tipos de cirugia existentes
* @return array
*/
	function TiposdeCirugia(){
    list($dbconn) = GetDBconn();
		$query = "SELECT tipo_cirugia_id,descripcion FROM qx_tipos_cirugia";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'qx_tipos_cirugia' esta vacia ";
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
* Funcion que consulta de la base de datos los procedimientos de una programacion
* @return array
* @param integer numero unico que identifica la programacion
*/
	function BusquedaProcedimientosProgram($ProgramacionId){

		list($dbconn) = GetDBconn();
		$query = "SELECT x.procedimiento_qx,x.tipo_id_cirujano,x.cirujano_id,x.plan_id,y.numero_orden_id,
		z.tipo_id_pediatra,z.pediatra_id,a.descripcion as nomvia,x.via_procedimiento_bilateral,x.observaciones		
		FROM qx_procedimientos_programacion x
		LEFT JOIN qx_programaciones_ordenes y on (x.programacion_id=y.programacion_id AND x.procedimiento_qx=y.procedimiento_qx)
    LEFT JOIN qx_procedimientos_programacion_pediatricos z on (x.programacion_id=z.programacion_id AND x.procedimiento_qx=z.procedimiento_qx)
		LEFT JOIN qx_vias_acceso a on (x.via_procedimiento_bilateral=a.via_acceso)		
		WHERE x.programacion_id='$ProgramacionId' ORDER BY x.procedimiento_qx DESC,x.tipo_id_cirujano,x.cirujano_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
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
/**
* Funcion que consulta de la base de datos la descripcion de un procedimiento
* @return array
* @param integer numero unico que identifica el procedimiento
* @param integer tarifario que tiene el procedimiento
*/
	function DescripcionProcedimiento($procedimiento){

    list($dbconn) = GetDBconn();
		$query = "SELECT descripcion FROM cups WHERE cargo='$procedimiento'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		$result->Close();
 		return $vars;
	}
/**
* Funcion que consulta de la base de datos los tipos de ambito que puede tener una cirugia
* @return array
*/
	function TiposdeAmbitosdeCirugia(){
    list($dbconn) = GetDBconn();
		$query = "SELECT ambito_cirugia_id,descripcion FROM qx_ambitos_cirugias";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'qx_ambitos_cirugias' esta vacia ";
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
* Funcion que consulta de la base de datos los tipos de ambito que puede tener una cirugia
* @return array
*/
	function finalidadeCirugia(){
    list($dbconn) = GetDBconn();
		$query = "SELECT finalidad_procedimiento_id,descripcion FROM qx_finalidades_procedimientos";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'qx_ambitos_cirugias' esta vacia ";
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
* Funcion que trae de la base de datos los datos principales de la programacion
* @return array
* @param integer numero unico que identifica la programacion
*/
	/*function DatosProgramacionQX($ProgramacionId){

		list($dbconn) = GetDBconn();
		$query="SELECT b.descripcion as viacceso,d.descripcion as tipocirugia,f.descripcion as ambito,a.via_acceso,a.tipo_cirugia,a.ambito_cirugia,a.finalidad_procedimiento_id,g.descripcion as finalidad FROM
		qx_datos_procedimientos_cirugias a
    LEFT JOIN qx_vias_acceso b ON (a.via_acceso=b.via_acceso)
    LEFT JOIN qx_tipos_cirugia d ON (a.tipo_cirugia=d.tipo_cirugia_id)
    LEFT JOIN qx_ambitos_cirugias f ON (a.ambito_cirugia=f.ambito_cirugia_id)
    LEFT JOIN qx_finalidades_procedimientos g ON (a.finalidad_procedimiento_id=g.finalidad_procedimiento_id)
    WHERE
		a.programacion_id='$ProgramacionId'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
		    $vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		$result->Close();
 		return $vars;
	}*/
/**
* Funcion que realiza la comprobacion de procedimientos existentes en la base de datos para una programacion de cirugia
* @return boolean
* @param integer numero unico que identifica la programacion
* @param integer codigo que identifica el procedimiento
* @param string codigo del tarifario del procedimiento
*/
	function ComprobacionProcedimientos($Programacion,$Procedimiento){

    list($dbconn) = GetDBconn();
		$query = "SELECT * FROM qx_procedimientos_programacion WHERE programacion_id='$Programacion' AND procedimiento_qx='$Procedimiento'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
        return 0;
			}
		}
		return 1;
	}
/**
* Funcion que confirma la eliminacion de un registro de la base de datos
* @return boolean
*/
	function LlamaConfirmarAccion($arreglo,$Cuenta,$c,$m,$me,$me2,$mensaje,$Titulo,$boton1,$boton2){
		if(empty($Titulo)){
			$arreglo=$_REQUEST['arreglo'];
			$Cuenta=$_REQUEST['Cuenta'];
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
/**
* Funcion que elimina de la base de datos el procedimiento indicado
* @return boolean
*/
	function EliminarProcedimientoQX(){

		list($dbconn) = GetDBconn();
		$query = "DELETE FROM qx_procedimientos_programacion WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND procedimiento_qx='".$_REQUEST['Procedimiento']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $query = "DELETE FROM qx_cups_opc_procedimientos_programacion
                  WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' 
                  AND procedimiento_qx='".$_REQUEST['Procedimiento']."'";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      } 
    }
		$this->ProcedimientosQuirurgicos();
		return true;
	}
/**
* Funcion que modifica en la bese de datos un procedimiento indicado por el usuario
* @return boolean
*/
	function ModificarProcedimientoQX(){
    list($dbconn) = GetDBconn();
		$query = "SELECT a.tipo_id_cirujano,a.cirujano_id,a.plan_id,b.tipo_id_pediatra,b.pediatra_id,a.observaciones
		FROM qx_procedimientos_programacion a
		LEFT JOIN qx_procedimientos_programacion_pediatricos b ON (a.procedimiento_qx=b.procedimiento_qx AND a.programacion_id=b.programacion_id)
		WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND a.procedimiento_qx='".$_REQUEST['Procedimiento']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
		    $vars=$result->GetRowAssoc($toUpper=false);
			}
      $cirujano=$vars['cirujano_id'].'/'.$vars['tipo_id_cirujano'];
			$ayudante=$vars['ayudante_id'].'/'.$vars['tipo_id_ayudante'];
      $_REQUEST['observacion']=$vars['observaciones'];
			/*if($vars['pediatra_id'] && $vars['tipo_id_pediatra']){
				$dat=1;
				$pediatra=$vars['pediatra_id'].'/'.$vars['tipo_id_pediatra'];
			}else{
				$dat=0;
			}*/
			$procedimiento=$this->DescripcionProcedimiento($_REQUEST['Procedimiento']);
			$this->ProcedimientosQuirurgicos($_REQUEST['Procedimiento'],$procedimiento['descripcion'],$cirujano,$ayudante,1,$viaAcceso,$tipoCirugia,$ambitoCirugia,$vars['plan_id'],'',$dat,$pediatra);
			return true;
		}
	}
/**
* Funcion que modifica en la base de datos los datos principales de una cirugia
* @return boolean
*/
	/*function InsercionDatosProgramCirugias(){

    list($dbconn) = GetDBconn();
		if($_REQUEST['viaAcceso']==-1 || $_REQUEST['tipoCirugia']==-1 || $_REQUEST['ambitoCirugia']==-1 || $_REQUEST['finalidadCirugia']==-1){
      if($_REQUEST['viaAcceso']==-1){$this->frmError["viaAcceso"]=1;}
			if($_REQUEST['tipoCirugia']==-1){$this->frmError["tipoCirugia"]=1;}
			if($_REQUEST['ambitoCirugia']==-1){$this->frmError["ambitoCirugia"]=1;}
      if($_REQUEST['finalidadCirugia']==-1){$this->frmError["finalidadCirugia"]=1;}
			$this->frmError["MensajeError"]="Faltan Datos Obligatorios sobre la Cirugia";
			$this->ProcedimientosQuirurgicos();
			return true;
		}
		if($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']){
		  $query="SELECT * FROM qx_datos_procedimientos_cirugias WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
				return false;
			}else{
        $datos=$result->RecordCount();
				if($datos){
          $query="UPDATE qx_datos_procedimientos_cirugias SET via_acceso='".$_REQUEST['viaAcceso']."',tipo_cirugia='".$_REQUEST['tipoCirugia']."',ambito_cirugia='".$_REQUEST['ambitoCirugia']."',finalidad_procedimiento_id='".$_REQUEST['finalidadCirugia']."' WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."'";
				}else{
			    $query="INSERT INTO qx_datos_procedimientos_cirugias(programacion_id,via_acceso,tipo_cirugia,ambito_cirugia,finalidad_procedimiento_id)VALUES('".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."','".$_REQUEST['viaAcceso']."','".$_REQUEST['tipoCirugia']."','".$_REQUEST['ambitoCirugia']."','".$_REQUEST['finalidadCirugia']."')";
				}
			  $result=$dbconn->Execute($query);
			  if($dbconn->ErrorNo() != 0){
				  $this->error = "Error al Cargar el Modulo";
				  $this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
				  return false;
			  }
		  }
		}
		$this->ProcedimientosQuirurgicos($_REQUEST['codigos'],$_REQUEST['procedimiento'],$_REQUEST['cirujano'],$_REQUEST['ayudante']);
		return true;
	}*/
/**
* Funcion que consulta el quirofano de una programacion
* @return array
* @param integer codigo unico que identifica la programacion
*/
	function consultaReservaQX($ProgramacionId){
    list($dbconn) = GetDBconn();
	 $query = "SELECT quirofano_id,qx_quirofano_programacion_id FROM qx_quirofanos_programacion WHERE programacion_id='$ProgramacionId' AND qx_tipo_reserva_quirofano_id != '0'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
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
/**
* Funcion que consulta el tipo de equipo que fue reservado en la programacion
* @return array
* @param integer codigo unico que identifica la programacion
*/
	function consultaReservaQXEqipos($ProgramacionIdQuiro){
    list($dbconn) = GetDBconn();
		$query = "SELECT DISTINCT y.tipo_equipo_id FROM qx_equipos_programacion x,
		qx_tipo_equipo_movil y,qx_equipos_moviles c WHERE x.qx_quirofano_programacion_id='$ProgramacionIdQuiro'
		AND x.equipo_id=c.equipo_id AND c.tipo_equipo_id=y.tipo_equipo_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
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
		return $vars;
	}
 
/**
* Funcion que llama la forma que muestra las opciones de los parametros de busqueda
* @return boolean
*/
	function ConsultadeProgramaciones(){
	  unset($_SESSION['QUIRURGICOS']['query']);
		unset($_SESSION['QUIRURGICOS']['DiaEspe']);
		unset($_SESSION['QUIRURGICOS']['cancelada']);
		unset($_SESSION['QUIRURGICOS']['ejecutada']);
		unset($_SESSION['QUIRURGICOS']['activa']);
		if($_REQUEST ['Menu']){
		  unset($_SESSION['QUIRURGICOS']['consulta']);
      $this->MenuQuirurjicos();
			return true;
		}
		if($_REQUEST['consulta']){
      $_SESSION['QUIRURGICOS']['consulta']=$_REQUEST['consulta'];
		}
    $this->FormaBuscadorProgramCirugias();
		return true;
	}
/**
* Funcion que llama la forma que pide los parametros de busqueda para la consulta de una programacion
* @return boolean
*/
	function seleccionCriteriosConsultaProgram(){
		if($_REQUEST['salir']){
               unset($_SESSION['QUIRURGICOS']['consulta']);
               $this->MenuQuirurjicos();
               return true;
		}
          
          if(SessionIsSetVar("TipoBusqueda")) $_REQUEST['tipoBusqueda'] = SessionGetVar("TipoBusqueda");
          
		if(!$_REQUEST['tipoBusqueda']){
               $this->frmError["MensajeError"]="Seleccione Algun Tipo de Busqueda.";
               $this->FormaBuscadorProgramCirugias();
               return true;
		}
          
          SessionSetVar("TipoBusqueda",$_REQUEST['tipoBusqueda']);
		if($_REQUEST['tipoBusqueda']==1){
               if(!$_REQUEST['cirujanoQX'] && !$_REQUEST['pacienteid'] && !$_REQUEST['ayudanteQX'] && !$_REQUEST['nompacientes'] &&
                    !$_REQUEST['quirofanoQX'] && !$_REQUEST['fecha'] && !$_REQUEST['procedimientoQX'] && !$_REQUEST['anestesiologoQX'])
               {
                    $this->frmError["MensajeError"]="Seleccione Alguno de los Parametros o Elija otro Tipo de Busqueda.";
                    $this->FormaBuscadorProgramCirugias();
                    return true;
               }
               $this->FormaDatosConsultaPrograma($_REQUEST['cirujanoQX'],$_REQUEST['pacienteid'],$_REQUEST['ayudanteQX'],$_REQUEST['nompacientes'],
               $_REQUEST['quirofanoQX'],$_REQUEST['fecha'],$_REQUEST['procedimientoQX'],$_REQUEST['cancelada'],$_REQUEST['ejecutada'],$_REQUEST['activa'],$_REQUEST['tipoBusqueda'],
               '','','','','','','',$_REQUEST['anestesiologoQX']);
               return true;
          }elseif($_REQUEST['tipoBusqueda']==2){
          	$this->FormaReportesProgramaciones('',1,$_REQUEST['cancelada'],$_REQUEST['ejecutada'],$_REQUEST['activa']);
               return true;
          }elseif($_REQUEST['tipoBusqueda']==3){
          	$this->DatosCriteriosConsultaProgram();
               return true;
          }elseif($_REQUEST['tipoBusqueda']==4){
          	$this->ConsultaAgendaQuirofano($_REQUEST['tipoTiempo']);
               return true;
          }
     }
     
/**
* Funcion que retorna un arreglo de los quirofanos con los que cuenta la ips en el departamento en el que esta logueado el usuario
* @return array
*/
	function TotalQuirofanos(){
	  $departamento=$_SESSION['LocalCirugias']['departamento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT quirofano,descripcion 
		FROM qx_quirofanos 
		WHERE departamento='$departamento' AND sw_programacion='1'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
			  while (!$result->EOF) {
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
	}
/**
* Funcion que arma la consulta de acuerdo a los parametros de busqueda elegidos por el usuario para la consulta de programaciones QX
* @return boolean
*/
	function DatosCriteriosConsultaProgram(){
    if($_REQUEST['regresar']){
      $this->FormaBuscadorProgramCirugias();
			return true;
		}
		$conectarSelect=0;
		$campos="a.programacion_id,a.tipo_id_cirujano,a.cirujano_id,a.tipo_id_paciente,a.paciente_id,b.quirofano_id";
		$tablas="qx_programaciones a LEFT JOIN qx_quirofanos_programacion b ON(a.programacion_id=b.programacion_id AND b.qx_tipo_reserva_quirofano_id!='0')";
    if($_REQUEST['cirujanoQX']){
		  if($_REQUEST['cirujano']==-1){
        $this->frmError["cirujano"]=1;
				$this->frmError["MensajeError"]="Seleccione el Cirujano";
				$this->FormaDatosConsultaPrograma($_REQUEST['cirujanoQX'],$_REQUEST['pacienteid'],$_REQUEST['ayudanteQX'],
				$_REQUEST['nompacientes'],$_REQUEST['quirofanoQX'],$_REQUEST['fecha'],$_REQUEST['procedimientoQX'],
				$_REQUEST['cancelada'],$_REQUEST['ejecutada'],$_REQUEST['activa'],$_REQUEST['tipoBusqueda'],$_REQUEST['cirujano'],$_REQUEST['TipoDocumento'],
				$_REQUEST['Documento'],$_REQUEST['quirofano'],$_REQUEST['FechaInicial'],$_REQUEST['FechaFinal'],$_REQUEST['codigos'],$_REQUEST['anestesiologoQX'],$_REQUEST['anestesiologo']);
				return true;
			}
		  $cadena=explode('/',$_REQUEST['cirujano']);
      $DocCirujano=$cadena[0];
      $TipoIdCirujano=$cadena[1];
      $tablas.=",qx_procedimientos_programacion c";
			if($conectarSelect==0){
        $where.=" a.programacion_id=c.programacion_id AND c.tipo_id_cirujano='$TipoIdCirujano' AND c.cirujano_id='$DocCirujano'";
				$conectarSelect=1;
			}else{
        $where.=" AND a.programacion_id=c.programacion_id AND c.tipo_id_cirujano='$TipoIdCirujano' AND c.cirujano_id='$DocCirujano'";
			}
		}
    if($_REQUEST['anestesiologoQX']){
      if($_REQUEST['anestesiologo']==-1){
        $this->frmError["anestesiologo"]=1;
        $this->frmError["MensajeError"]="Seleccione el Anestesiologo";
        $this->FormaDatosConsultaPrograma($_REQUEST['cirujanoQX'],$_REQUEST['pacienteid'],$_REQUEST['ayudanteQX'],
        $_REQUEST['nompacientes'],$_REQUEST['quirofanoQX'],$_REQUEST['fecha'],$_REQUEST['procedimientoQX'],
        $_REQUEST['cancelada'],$_REQUEST['ejecutada'],$_REQUEST['activa'],$_REQUEST['tipoBusqueda'],$_REQUEST['cirujano'],$_REQUEST['TipoDocumento'],
        $_REQUEST['Documento'],$_REQUEST['quirofano'],$_REQUEST['FechaInicial'],$_REQUEST['FechaFinal'],$_REQUEST['codigos'],$_REQUEST['anestesiologoQX'],$_REQUEST['anestesiologo']);
        return true;
      }
      $cadena=explode('/',$_REQUEST['anestesiologo']);
      $DocAnestesiologo=$cadena[0];
      $TipoIdAnestesiologo=$cadena[1];
      $tablas.=",qx_anestesiologo_programacion xx";
      if($conectarSelect==0){
        $where.=" a.programacion_id=xx.programacion_id AND xx.tipo_id_tercero='$TipoIdAnestesiologo' AND xx.tercero_id='$DocAnestesiologo'";
        $conectarSelect=1;
      }else{
        $where.=" AND a.programacion_id=xx.programacion_id AND xx.tipo_id_tercero='$TipoIdAnestesiologo' AND xx.tercero_id='$DocAnestesiologo'";
      }
    }
    
		if($_REQUEST['ayudanteQX']){
      if($_REQUEST['ayudante']==-1){
        $this->frmError["ayudante"]=1;
				$this->frmError["MensajeError"]="Seleccione el Ayudante";
				$this->FormaDatosConsultaPrograma($_REQUEST['cirujanoQX'],$_REQUEST['pacienteid'],$_REQUEST['ayudanteQX'],
				$_REQUEST['nompacientes'],$_REQUEST['quirofanoQX'],$_REQUEST['fecha'],$_REQUEST['procedimientoQX'],
				$_REQUEST['cancelada'],$_REQUEST['ejecutada'],$_REQUEST['activa'],$_REQUEST['tipoBusqueda'],$_REQUEST['cirujano'],$_REQUEST['TipoDocumento'],
				$_REQUEST['Documento'],$_REQUEST['quirofano'],$_REQUEST['FechaInicial'],$_REQUEST['FechaFinal'],$_REQUEST['codigos'],$_REQUEST['anestesiologoQX'],$_REQUEST['anestesiologo']);
				return true;
			}
		  $cadena=explode('/',$_REQUEST['ayudante']);
      $DocAyudante=$cadena[0];
      $TipoIdAyudante=$cadena[1];
      if(empty($_REQUEST['anestesiologoQX'])){
        $tablas.=",qx_anestesiologo_programacion xx";
      }
			if($conectarSelect==0){
			  $where.=" a.programacion_id=xx.programacion_id AND xx.tipo_id_ayudante='$TipoIdAyudante' AND xx.ayudante_id='$DocAyudante'";
				$conectarSelect=1;
			}else{
        $where.=" AND a.programacion_id=xx.programacion_id AND xx.tipo_id_ayudante='$TipoIdAyudante' AND xx.ayudante_id='$DocAyudante'";
			}
		}
		if($_REQUEST['pacienteid']){
		  if($_REQUEST['TipoDocumento']==-1 || !$_REQUEST['Documento']){
			  if($_REQUEST['TipoDocumento']==-1){$this->frmError["TipoDocumento"]=1;}
        if(!$_REQUEST['Documento']){$this->frmError["Documento"]=1;}
				$this->frmError["MensajeError"]="Seleccione la Identificacion del Paciente";
				$this->FormaDatosConsultaPrograma($_REQUEST['cirujanoQX'],$_REQUEST['pacienteid'],$_REQUEST['ayudanteQX'],
				$_REQUEST['nompacientes'],$_REQUEST['quirofanoQX'],$_REQUEST['fecha'],$_REQUEST['procedimientoQX'],
				$_REQUEST['cancelada'],$_REQUEST['ejecutada'],$_REQUEST['activa'],$_REQUEST['tipoBusqueda'],$_REQUEST['cirujano'],$_REQUEST['TipoDocumento'],
				$_REQUEST['Documento'],$_REQUEST['quirofano'],$_REQUEST['FechaInicial'],$_REQUEST['FechaFinal'],$_REQUEST['codigos'],$_REQUEST['anestesiologoQX'],$_REQUEST['anestesiologo']);
				return true;
			}
			if($conectarSelect==0){
        $where.=" a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."' AND a.paciente_id='".$_REQUEST['Documento']."'";
				$conectarSelect=1;
			}else{
        $where.=" AND a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."' AND a.paciente_id='".$_REQUEST['Documento']."'";
			}
		}
		if($_REQUEST['quirofanoQX']){
      if($_REQUEST['quirofano']==-1){
        $this->frmError["quirofano"]=1;
				$this->frmError["MensajeError"]="Seleccione el Quirofano";
				$this->FormaDatosConsultaPrograma($_REQUEST['cirujanoQX'],$_REQUEST['pacienteid'],$_REQUEST['ayudanteQX'],
				$_REQUEST['nompacientes'],$_REQUEST['quirofanoQX'],$_REQUEST['fecha'],$_REQUEST['procedimientoQX'],
				$_REQUEST['cancelada'],$_REQUEST['ejecutada'],$_REQUEST['activa'],$_REQUEST['tipoBusqueda'],$_REQUEST['cirujano'],$_REQUEST['TipoDocumento'],
				$_REQUEST['Documento'],$_REQUEST['quirofano'],$_REQUEST['FechaInicial'],$_REQUEST['FechaFinal'],$_REQUEST['codigos'],$_REQUEST['anestesiologoQX'],$_REQUEST['anestesiologo']);
				return true;
			}
			if($conectarSelect==0){
		    $where.=" b.quirofano_id='".$_REQUEST['quirofano']."'";
				$conectarSelect=1;
			}else{
        $where.=" AND b.quirofano_id='".$_REQUEST['quirofano']."'";
			}
		}
		if($_REQUEST['nompacientes']){
		  $apellidos=strtoupper($_REQUEST['apellidos']);
      $nombres=strtoupper($_REQUEST['nombres']);
      if(!empty($apellidos) && !empty($nombres)){
		    $tablas.=",(SELECT primer_apellido||' '||segundo_apellido as x, primer_nombre||' '||segundo_nombre as y,paciente_id,tipo_id_paciente FROM pacientes) as hola";
				if($conectarSelect==0){
				  $where.=" (hola.x LIKE '$apellidos%' OR hola.y LIKE '$nombres%') AND hola.paciente_id=a.paciente_id AND hola.tipo_id_paciente=a.tipo_id_paciente";
					$conectarSelect=1;
				}else{
          $where.=" AND (hola.x LIKE '$apellidos%' OR hola.y LIKE '$nombres%') AND hola.paciente_id=a.paciente_id AND hola.tipo_id_paciente=a.tipo_id_paciente";
				}
			}elseif(!empty($apellidos)){
        $tablas.=",(SELECT primer_apellido||' '||segundo_apellido as x,paciente_id,tipo_id_paciente FROM pacientes) as hola";
        if($conectarSelect==0){
				  $where.=" (hola.x LIKE '$apellidos%') AND hola.paciente_id=a.paciente_id AND hola.tipo_id_paciente=a.tipo_id_paciente";
					$conectarSelect=1;
				}else{
				  $where.=" AND (hola.x LIKE '$apellidos%') AND hola.paciente_id=a.paciente_id AND hola.tipo_id_paciente=a.tipo_id_paciente";
				}
			}elseif(!empty($nombres)){
        $tablas.=",(SELECT primer_nombre||' '||segundo_nombre as y,paciente_id,tipo_id_paciente FROM pacientes) as hola";
				if($conectarSelect==0){
				  $where.=" (hola.y LIKE '$nombres%') AND hola.paciente_id=a.paciente_id AND hola.tipo_id_paciente=a.tipo_id_paciente";
					$conectarSelect=1;
				}else{
          $where.=" AND (hola.y LIKE '$nombres%') AND hola.paciente_id=a.paciente_id AND hola.tipo_id_paciente=a.tipo_id_paciente";
				}
			}else{
        $this->frmError["MensajeError"]="Digite los nombre o Apellidos del Paciente";
				$this->FormaDatosConsultaPrograma($_REQUEST['cirujanoQX'],$_REQUEST['pacienteid'],$_REQUEST['ayudanteQX'],
				$_REQUEST['nompacientes'],$_REQUEST['quirofanoQX'],$_REQUEST['fecha'],$_REQUEST['procedimientoQX'],
				$_REQUEST['cancelada'],$_REQUEST['ejecutada'],$_REQUEST['activa'],$_REQUEST['tipoBusqueda'],$_REQUEST['cirujano'],$_REQUEST['TipoDocumento'],
				$_REQUEST['Documento'],$_REQUEST['quirofano'],$_REQUEST['FechaInicial'],$_REQUEST['FechaFinal'],$_REQUEST['codigos'],$_REQUEST['anestesiologoQX'],$_REQUEST['anestesiologo']);
				return true;
			}
		}
    if($_REQUEST['fecha']){
		  if(!$_REQUEST['FechaInicial'] || !$_REQUEST['FechaFinal']){
        if(!$_REQUEST['FechaInicial']){$this->frmError["FechaInicial"]=1;}
				if(!$_REQUEST['FechaFinal']){$this->frmError["FechaFinal"]=1;}
				$this->frmError["MensajeError"]="Seleccione los Rangos de las Fecha";
				$this->FormaDatosConsultaPrograma($_REQUEST['cirujanoQX'],$_REQUEST['pacienteid'],$_REQUEST['ayudanteQX'],
				$_REQUEST['nompacientes'],$_REQUEST['quirofanoQX'],$_REQUEST['fecha'],$_REQUEST['procedimientoQX'],
				$_REQUEST['cancelada'],$_REQUEST['ejecutada'],$_REQUEST['activa'],$_REQUEST['tipoBusqueda'],$_REQUEST['cirujano'],$_REQUEST['TipoDocumento'],
				$_REQUEST['Documento'],$_REQUEST['quirofano'],$_REQUEST['FechaInicial'],$_REQUEST['FechaFinal'],$_REQUEST['codigos'],$_REQUEST['anestesiologoQX'],$_REQUEST['anestesiologo']);
			}
      $cadena=explode('/',$_REQUEST['FechaInicial']);
      $diaIni=$cadena[0];
			$mesIni=$cadena[1];
			$anoIni=$cadena[2];
			$FechaInicial=$anoIni.'-'.$mesIni.'-'.$diaIni;
			$cadena=explode('/',$_REQUEST['FechaFinal']);
      $diaFin=$cadena[0];
			$mesFin=$cadena[1];
			$anoFin=$cadena[2];
			$FechaFinal=$anoFin.'-'.$mesFin.'-'.$diaFin;
			if($conectarSelect==0){
        $where.=" date(b.hora_inicio)>=date('$FechaInicial') AND date(b.hora_inicio)<=date('$FechaFinal')";
				$conectarSelect=1;
			}else{
        $where.=" AND date(b.hora_inicio)>=date('$FechaInicial') AND date(b.hora_inicio)<=date('$FechaFinal')";
			}
		}
		if($_REQUEST['procedimientoQX']){
      if(!$_REQUEST['codigos']==-1){
        $this->frmError["procedimiento"]=1;
				$this->frmError["MensajeError"]="Seleccione el Procedimiento";
				$this->FormaDatosConsultaPrograma($_REQUEST['cirujanoQX'],$_REQUEST['pacienteid'],$_REQUEST['ayudanteQX'],
				$_REQUEST['nompacientes'],$_REQUEST['quirofanoQX'],$_REQUEST['fecha'],$_REQUEST['procedimientoQX'],
				$_REQUEST['cancelada'],$_REQUEST['ejecutada'],$_REQUEST['activa'],$_REQUEST['tipoBusqueda'],$_REQUEST['cirujano'],$_REQUEST['TipoDocumento'],
				$_REQUEST['Documento'],$_REQUEST['quirofano'],$_REQUEST['FechaInicial'],$_REQUEST['FechaFinal'],$_REQUEST['codigos'],$_REQUEST['anestesiologoQX'],$_REQUEST['anestesiologo']);
				return true;
			}
			if(!$_REQUEST['cirujanoQX']){
        $tablas.=",qx_procedimientos_programacion c";
			}
			if($conectarSelect==0){
        $where.=" a.programacion_id=c.programacion_id AND c.procedimiento_qx='".$_REQUEST['codigos']."'";
				$conectarSelect=1;
			}else{
        $where.=" AND a.programacion_id=c.programacion_id AND c.procedimiento_qx='".$_REQUEST['codigos']."'";
			}
		}
		if($_SESSION['QUIRURGICOS']['consulta']==1){
			if($_REQUEST['activa']){
				if($conectarSelect==0){
					$where.=" a.estado='1'";
					$conectarSelect=1;
				}else{
					$where.=" AND a.estado='1'";
				}
			}
			if($_REQUEST['cancelada']){
				if($conectarSelect==0){
					$where.=" a.estado='0'";
					$conectarSelect=1;
				}else{
					$where.=" AND a.estado='0'";
				}
			}
			if($_REQUEST['ejecutada']){
				if($conectarSelect==0){
					$where.=" a.estado='2'";
					$conectarSelect=1;
				}else{
					$where.=" AND a.estado='2'";
				}
			}
		}else{
			if($conectarSelect==0){
				$where.=" a.estado='1'";
				$conectarSelect=1;
			}else{
				$where.=" AND a.estado='1'";
			}
		}
		if($conectarSelect==0){
		  $where.=" a.departamento='".$_SESSION['LocalCirugias']['departamento']."'";
			$conectarSelect=1;
		}else{
		  $where.=" AND a.departamento='".$_SESSION['LocalCirugias']['departamento']."'";
		}
		$query="SELECT DISTINCT
		$campos,
		(select min(r.hora_inicio) from qx_quirofanos_programacion r where r.programacion_id=a.programacion_id and r.qx_tipo_reserva_quirofano_id!='0') as hora_inicio,
		(select max(r.hora_fin) from qx_quirofanos_programacion r where r.programacion_id=a.programacion_id and r.qx_tipo_reserva_quirofano_id!='0') as hora_fin
		FROM $tablas WHERE $where ORDER BY hora_inicio";
    
		$this->FormaReportesProgramaciones($query);
		return true;
	}
/**
* Funcion que ejecuta la consulta que llega por parametro y aplica un limit-ofset para la barra de desplazamiento
* @return array
* @param string query que se va a ejecutar
*/
	function barraEstadoParaProgramaciones($query){

	  list($dbconn) = GetDBconn();
    if(empty($_REQUEST['conteo'])){
		  $result = $dbconn->Execute($query);
			$dat = $result->RecordCount();
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		  $this->conteo=$dat;
    }else{
      $this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of']){
      $Of='0';
		}else{
      $Of=$_REQUEST['Of'];
		}
    $query1=$query." LIMIT " . $this->limit . " OFFSET $Of";
		$result = $dbconn->Execute($query1);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  while(!$result->EOF){
			  $vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
			$result->Close();
		}
		return $vars;
	}
/**
* Funcion que regresa a la forma de la programacion de una cirugia
* @return boolean
*/
	function consultarDetallePrograma(){
	  $_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']=$_REQUEST['ProgramacionId'];
		$this->FormaProgramacionesQuirurgicas($_SESSION['QUIRURGICOS']['consulta'],$_REQUEST['mayorfecha']);
		return true;
	}
/**
* Funcion que retorna los tipo de documentos de la base de datos que puede tener un profesional
* @return array
*/
	function tipo_id_terceros(){
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_id_tercero,descripcion FROM tipo_id_terceros WHERE sw_personas_naturales='1' ORDER BY indice_de_orden";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla maestra 'tipo_id_terceros' esta vacia ";
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
* Funcion que llama al modulo externo de la adicion de profesionales
* @return boolean
*/
	function LlamaAdicionProfesional(){
    $_SESSION['PROVEEDORES']['DATOS']['DesEmpresa']=$_SESSION['LocalCirugias']['NombreEmp'];
    $_SESSION['PROVEEDORES']['DATOS']['empresa']=$_SESSION['LocalCirugias']['empresa'];
		$_SESSION['PROVEEDORES']['DATOS']['departamento']=$_SESSION['LocalCirugias']['departamento'];
		$_SESSION['PROVEEDORES']['DATOS']['descdepartamento']=$_SESSION['LocalCirugias']['NombreDpto'];
		$_SESSION['PROVEEDORES']['DATOS']['TipoDocumento']=$_REQUEST['TipoDocumento'];
		$_SESSION['PROVEEDORES']['DATOS']['Documento']=$_REQUEST['Documento'];
		$_SESSION['PROVEEDORES']['RETORNO']['contenedor']='app';
		$_SESSION['PROVEEDORES']['RETORNO']['modulo']='Quirurgicos';
		$_SESSION['PROVEEDORES']['RETORNO']['tipo']='user';
		$_SESSION['PROVEEDORES']['RETORNO']['metodo']='DatosNuevoProfesional';
    $this->ReturnMetodoExterno('app','Profesionales','user','LlamadaOtrosModulos');
		return true;
	}
/**
* Funcion donde retorna el modulo de los profesionales
* @return boolean
*/
	function DatosNuevoProfesional(){
    $action=ModuloGetURL('app','Quirurgicos','user','PedirDatosPaciente');
	  $this->FormaBuscarPacientePresupuesto($TipoId,$Documento,$action,$Responsable,1,$cirujano);
		unset($_SESSION['PROVEEDORES']);
	  return true;
	}
/**
* Funcion que consulta en la base de datos las programaciones quirurgicas de un dia
* @return array
* @param dia seleccionado del que se van a realizar las consultas de las programaciones
* @param variable que indica si las programaciones a consultar deben tener un estado cancelado
* @param variable que indica si las programaciones a consultar deben tener un estado ejecutado
* @param variable que indica si las programaciones a consultar deben tener un estado activo
*/
	function ConsultaProgramacionesDiarias($DiaEspe,$cancelada,$ejecutada,$activa){
    if(empty($DiaEspe)){
      $DiaEspe=date("Y-m-d");
		}
		$query="SELECT DISTINCT a.programacion_id,a.tipo_id_cirujano,a.cirujano_id,a.tipo_id_paciente,a.paciente_id,b.quirofano_id, (select min(r.hora_inicio) from qx_quirofanos_programacion r where r.programacion_id=a.programacion_id AND r.qx_tipo_reserva_quirofano_id != '0') as hora_inicio, (select max(r.hora_fin) from qx_quirofanos_programacion r where r.programacion_id=a.programacion_id AND r.qx_tipo_reserva_quirofano_id != '0') as hora_fin FROM
		qx_quirofanos_programacion b,qx_programaciones a WHERE
		a.programacion_id=b.programacion_id AND date(b.hora_inicio)=date('$DiaEspe') AND b.qx_tipo_reserva_quirofano_id != '0'";
		if($_SESSION['QUIRURGICOS']['consulta']==1){
		if($cancelada){
      $query.=" AND a.estado='0'";
		}
		if($ejecutada){
      $query.=" AND a.estado='2'";
		}
		if($activa){
      $query.=" AND a.estado='1'";
		}
		}else{
      $query.=" AND a.estado='1'";
		}
    $query.=" ORDER BY hora_inicio";
    $vars=$this->barraEstadoParaProgramaciones($query);
		return $vars;
	}
/**
* Funcion quelista la ordenes de servicio
* @return boolean
*/

	function LlamaCapturaOrdenesServicio(){
		$this->FormaMetodoBuscar();
		return true;
	}
/**
* Realiza la busqueda general de los pacientes que tienen ordenes de servicios pendientes
* @access private
* @return array
*/
	function BusquedaCompleta()
	{

				$NUM=$_REQUEST['Of'];
				if(!$NUM)
				{   $NUM='0';   }
				$limit=$this->limit;
				list($dbconn) = GetDBconn();
				if(!empty($NUM))
				{   $x=" LIMIT ".$this->limit." OFFSET $NUM";   }
				else
				{   $x='';   }


						$query="SELECT DISTINCT
										btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
										b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
										b.tipo_id_paciente,b.paciente_id,a.plan_id,a.orden_servicio_id


										FROM pacientes as b,os_ordenes_servicios a,
										os_maestro c,os_internas d

										WHERE
										c.numero_orden_id=d.numero_orden_id
										AND a.orden_servicio_id=c.orden_servicio_id
										AND d.departamento='".$_SESSION['LocalCirugias']['departamento']."'
										AND (c.sw_estado=1 OR c.sw_estado=2 OR c.sw_estado=3)
										AND DATE(c.fecha_activacion) <= NOW()
										AND a.tipo_id_paciente=b.tipo_id_paciente
										AND a.paciente_id=b.paciente_id $x";



					 /* $query="SELECT DISTINCT
						btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
						b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
						b.tipo_id_paciente,b.paciente_id
						FROM pacientes as b,os_ordenes_servicios a
						WHERE
						cantidad_cargos_os(a.orden_servicio_id,'".$_SESSION['LocalCirugias']['departamento']."') > 0
						AND a.tipo_id_paciente=b.tipo_id_paciente
						AND a.paciente_id=b.paciente_id $x";*/

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				if(!empty($_SESSION['SPY']))
				{
						while(!$result->EOF)
						{
								$vars[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
						}
				}
				else
				{
						$vars=$result->RecordCount();
						$_SESSION['SPY']=$vars;
				}
				$result->Close();

		return $vars;
	}
	/**
	* Realiza la busqueda según el plan,documento .. de los pacientes que
	* tienen ordenes de servicios pendientes
	* @access private
	* @return boolean
	*/
  function BuscarOrden(){

		$Buscar1=$_REQUEST['Busc'];
		$Buscar=$_REQUEST['Buscar'];
		$Busqueda=$_REQUEST['TipoBusqueda'];
		$TipoBuscar=$_REQUEST['TipoBuscar'];
		$arreglo=$_REQUEST['arreglo'];
		$TipoCuenta=$_REQUEST['TipoCuenta'];
		$NUM=$_REQUEST['Of'];
		if($Buscar){
		  unset($_SESSION['SPY']);
		}
		if(!$Busqueda){
		  $new=$TipoBuscar;
		}
		if(!$NUM){
		  $NUM='0';
		}
		foreach($_REQUEST as $v=>$v1){
		  if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID'){
			  $vec[$v]=$v1;
			}
		}
		$_REQUEST['Of']=$NUM;
		if($Buscar1){
				$this->FormaMetodoBuscar($Busqueda,$arr,$f);
				return true;
		}
		list($dbconn) = GetDBconn();
		if($TipoBuscar){
			if($TipoBuscar==1){
				$TipoId=$_REQUEST['TipoDocumento'];
				$PacienteId=$_REQUEST['Documento'];
				if(!$PacienteId){
				  $this->frmError["MensajeError"]='La busqueda no arrojo resultados.';
					$this->FormaMetodoBuscar($Busqueda='',$arr,$f=true);
					return true;
				}
				/*	if(empty($_SESSION['SPY'])){
				$conteo=$this->Buscar1($TipoId,$PacienteId,$NUM);
				$_SESSION['SPY']=$conteo;
				}*/
				$Cuentas=$this->Buscar1($TipoId,$PacienteId,$NUM);
				if($Cuentas){
					$this->FormaMetodoBuscar($Busqueda='',$Cuentas,$f=true);
					return true;
				}else{
					$this->frmError["MensajeError"]='La busqueda no arrojo resultados.';
					$this->FormaMetodoBuscar($Busqueda='',$Cuentas,$f=true);
					return true;
				}
			}//tipobuscar=1
			if($TipoBuscar==3){
				$cuenta=$_REQUEST['Responsable'];
				if($cuenta==-1){
					if($cuenta==-1){
					  $this->frmError["Responsable"]=1;
					}
					$this->frmError["MensajeError"]="Debe Elegir el plan.";
					if(!$this->FormaMetodoBuscar($TipoBuscar,$arr,$f=false)){
						return false;
					}
					return true;
				}
				/*if(empty($_SESSION['SPY'])){
				$conteo=$this->RecordSearch1($Departamento,$TipoId,$PacienteId,$caso=1,$Caja,$NUM);
				$_SESSION['SPY']=$conteo;
				}*/
				$Cuentas=$this->Buscar3($cuenta);
				if($Cuentas){
					$this->FormaMetodoBuscar($Busqueda='',$Cuentas,$f=true);
					return true;
				}else{
					$this->frmError["MensajeError"]='La busqueda no arrojo resultados.';
					$this->FormaMetodoBuscar($Busqueda='',$Cuentas,$f=true);
					return true;
				}
			}//tipobuscar=1
			if($TipoBuscar==4){
				$IngresoId=$_REQUEST['NumIngreso'];
				if(!$IngresoId){
					if(!$IngresoId){
						$this->frmError["IngresoId"]=1;
					}
					$this->frmError["MensajeError"]="Debe digitar el Número de Ingreso.";
					if(!$this->FormaMetodoBuscar($TipoBuscar,$arr,$f=false)){
						return false;
					}
					return true;
				}
				/*if(empty($_SESSION['SPY'])){
				$conteo=$this->RecordSearch1($Departamento,$TipoId,$PacienteId,$caso=1,$Caja,$NUM);
				$_SESSION['SPY']=$conteo;
				}*/
				$Cuentas=$this->Buscar4($IngresoId);
				if($Cuentas){
					$this->FormaMetodoBuscar($Busqueda='',$Cuentas,$f=true);
					return true;
				}else{
					$this->frmError["MensajeError"]='La busqueda no arrojo resultados.';
					$this->FormaMetodoBuscar($Busqueda='',$Cuentas,$f=true);
					return true;
				}
			}//tipobuscar=1
		}//tipobuscar
	}
// $spia es una variable q si esta activa  va a realizar un record count del query
//si no va vacia y se realiza el query comun y corriente.
  function TraerOrdenesServicio($TipoId,$PacienteId,$spia=''){
	  list($dbconn) = GetDBconn();
    //--,z.descripcion as tipocirugia,
    //--l.descripcion as ambitocirugia,m.descripcion as finalidadprocedimiento    
    //--LEFT JOIN qx_tipos_cirugia z on(y.tipo_cirugia_id=z.tipo_cirugia_id) 
    //--LEFT JOIN qx_ambitos_cirugias l on (y.ambito_cirugia_id=l.ambito_cirugia_id) 
    //--LEFT JOIN qx_finalidades_procedimientos m on(y.finalidad_procedimiento_id=m.finalidad_procedimiento_id)
    
    $query="SELECT 
        c.plan_id,
        c.plan_descripcion,
       (SELECT h.servicio FROM servicios h WHERE a.servicio=h.servicio) AS servicio,
       (SELECT h.descripcion FROM servicios h WHERE a.servicio=h.servicio) AS serv_des,
       (SELECT h.sw_cargo_multidpto FROM servicios h WHERE a.servicio=h.servicio) AS switche,
        CASE c.sw_tipo_plan WHEN '0' THEN d.nombre_tercero WHEN '1' THEN 'SOAT' WHEN '2'
      THEN 'PARTICULAR' WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero ELSE
       (SELECT e.descripcion FROM tipos_planes e WHERE e.sw_tipo_plan=c.sw_tipo_plan) END,
       a.tipo_afiliado_id,
       a.rango,
       a.orden_servicio_id,
       f.numero_orden_id,
       a.fecha_registro,
       i.fecha_activacion,
       i.fecha_vencimiento, 
       f.cargo as cargoi,
       (SELECT g.descripcion FROM cups g WHERE g.cargo=f.cargo) AS des1,
       i.cantidad, 
       a.autorizacion_int,
       a.autorizacion_ext,
       a.observacion,
       i.cargo_cups as cargo, 
       (SELECT k.tipo_afiliado_nombre FROM tipos_afiliado k WHERE k.tipo_afiliado_id=a.tipo_afiliado_id) as tipo_afiliado_nombre,
       i.cargo_cups, 
       (SELECT y.observacion FROM hc_os_solicitudes_procedimientos y 
       INNER JOIN hc_os_solicitudes x 
       ON y.hc_os_solicitud_id=x.hc_os_solicitud_id
       WHERE i.hc_os_solicitud_id=x.hc_os_solicitud_id AND i.cargo_cups=x.cargo) as observacion
     FROM 
       os_maestro i
       INNER JOIN os_ordenes_servicios as a 
       ON a.orden_servicio_id=i.orden_servicio_id
       INNER JOIN os_internas as f
       ON i.numero_orden_id=f.numero_orden_id 
       INNER JOIN hc_os_solicitudes_acto_qx m
       ON m.hc_os_solicitud_id=i.hc_os_solicitud_id
       INNER JOIN hc_os_solicitudes_datos_acto_qx l 
       ON l.acto_qx_id=m.acto_qx_id 
       -- pacientes as b
       -- ON a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id 
       INNER JOIN planes c
       ON c.plan_id=a.plan_id 
       INNER JOIN terceros d
       ON c.tercero_id=d.tercero_id AND c.tipo_tercero_id=d.tipo_id_tercero
       LEFT JOIN qx_programaciones_ordenes xx ON(xx.numero_orden_id=i.numero_orden_id)
      WHERE 
      a.tipo_id_paciente='$TipoId' AND 
      a.paciente_id='$PacienteId' AND 
      f.departamento='".$_SESSION['LocalCirugias']['departamento']."' AND 
      i.sw_estado=1 AND
      DATE(i.fecha_activacion) <= NOW() AND 
      xx.programacion_id IS NULL 
      ORDER BY c.plan_id,i.fecha_vencimiento,a.orden_servicio_id"; 
	
	/**
	"SELECT c.plan_id,c.plan_descripcion,h.servicio,h.descripcion as serv_des, sw_cargo_multidpto as switche,
		CASE c.sw_tipo_plan WHEN '0' THEN d.nombre_tercero WHEN '1' THEN 'SOAT' WHEN '2'
		THEN 'PARTICULAR' WHEN '3' THEN 'CAPITACION - '||d.nombre_tercero ELSE
		e.descripcion END, a.tipo_afiliado_id,a.rango,a.orden_servicio_id,f.numero_orden_id,a.fecha_registro,i.fecha_activacion,
		i.fecha_vencimiento, f.cargo as cargoi,g.descripcion as des1,i.cantidad, a.autorizacion_int,a.autorizacion_ext,a.observacion,
		i.cargo_cups as cargo, k.tipo_afiliado_nombre,i.cargo_cups, y.observacion   
    
    FROM hc_os_solicitudes_datos_acto_qx l, hc_os_solicitudes_acto_qx m,
    os_ordenes_servicios as a, pacientes as b, planes c, terceros d,
		tipos_planes as e, os_internas as f, cups g, servicios h,os_maestro i
		LEFT JOIN hc_os_solicitudes x on(i.hc_os_solicitud_id=x.hc_os_solicitud_id AND i.cargo_cups=x.cargo)     
    LEFT JOIN hc_os_solicitudes_procedimientos y on (y.hc_os_solicitud_id=x.hc_os_solicitud_id)
    LEFT JOIN qx_programaciones_ordenes xx ON(xx.numero_orden_id=i.numero_orden_id),
    tipos_afiliado k
    
    WHERE l.acto_qx_id=m.acto_qx_id AND m.hc_os_solicitud_id=i.hc_os_solicitud_id 
    AND a.orden_servicio_id=i.orden_servicio_id AND i.numero_orden_id=f.numero_orden_id AND
		a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND a.tipo_id_paciente='$TipoId'
		AND a.paciente_id='$PacienteId' AND a.servicio=h.servicio AND g.cargo=f.cargo AND c.plan_id=a.plan_id
		AND e.sw_tipo_plan=c.sw_tipo_plan AND c.tercero_id=d.tercero_id AND c.tipo_tercero_id=d.tipo_id_tercero
		AND f.departamento='".$_SESSION['LocalCirugias']['departamento']."' AND i.sw_estado=1 AND
		a.tipo_afiliado_id=k.tipo_afiliado_id AND DATE(i.fecha_activacion) <= NOW() 
    AND xx.programacion_id IS NULL 
    ORDER BY c.plan_id,i.fecha_vencimiento,a.orden_servicio_id";
	*/
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al traer las 0rdenes de servicios";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    if($spia==true)	{
		  return $result->RecordCount();
		}
		while (!$result->EOF) {
			$var[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
		}
		$result->Close();
		return $var;
  }
/**
* funcion buscar1 es la que se filtra por el tipo del paciente y la identificacion del
* paciente.
* @access private
* @return array
*/
	function Buscar1($TipoId,$PacienteId){
	  list($dbconn) = GetDBconn();
		$NUM=$_REQUEST['Of'];
		if(!$NUM)
		{   $NUM='0';   }
		$limit=$this->limit;
		list($dbconn) = GetDBconn();
		if(!empty($NUM))
		{   $x=" LIMIT ".$this->limit." OFFSET $NUM";   }
		else
		{   $x='';   }
	/*	$query="SELECT DISTINCT
							btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
							b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
							b.tipo_id_paciente,b.paciente_id

							FROM pacientes as b,os_ordenes_servicios a

							WHERE
							cantidad_cargos_os(a.orden_servicio_id,'".$_SESSION['LocalCirugias']['departamento']."') > 0
							AND	b.tipo_id_paciente='$TipoId'
							AND	b.paciente_id='$PacienteId'
							AND a.tipo_id_paciente=b.tipo_id_paciente
							AND a.paciente_id=b.paciente_id $x";*/

			$query="SELECT DISTINCT
				btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
				b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
				b.tipo_id_paciente,b.paciente_id
				FROM pacientes as b,os_ordenes_servicios a,
				os_maestro c
        LEFT JOIN qx_programaciones_ordenes x ON(x.numero_orden_id=c.numero_orden_id),
        os_internas d
				WHERE
				c.numero_orden_id=d.numero_orden_id
				AND a.orden_servicio_id=c.orden_servicio_id
				AND d.departamento='".$_SESSION['LocalCirugias']['departamento']."'
				AND	b.tipo_id_paciente='$TipoId'
				AND	b.paciente_id='$PacienteId'
				AND c.sw_estado=1
				AND DATE(c.fecha_activacion) <= NOW()
				AND a.tipo_id_paciente=b.tipo_id_paciente
				AND a.paciente_id=b.paciente_id 
        AND x.programacion_id IS NULL 
        $x";
     
   	$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Consultar por el tipo y por la identificacion del paciente";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$result->EOF){
			$vars[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
    }
		$result->Close();
		return $vars;
	}
/**
* buscar 3 esta funcion filtra por EL PLAN.
* @access private
* @return array
*/
	function Buscar3($plan){
		list($dbconn) = GetDBconn();
		$NUM=$_REQUEST['Of'];
		if(!$NUM)
		{   $NUM='0';   }
		$limit=$this->limit;
		list($dbconn) = GetDBconn();
		if(!empty($NUM))
		{   $x=" LIMIT ".$this->limit." OFFSET $NUM";   }
		else
		{   $x='';   }
		/*$query="SELECT DISTINCT
						btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
						b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
						b.tipo_id_paciente,b.paciente_id
						FROM pacientes as b,os_ordenes_servicios a,planes c
						WHERE
						cantidad_cargos_os(a.orden_servicio_id,'".$_SESSION['LocalCirugias']['departamento']."') > 0
						AND c.plan_id='$plan'
						AND c.plan_id=a.plan_id
						AND a.tipo_id_paciente=b.tipo_id_paciente
						AND a.paciente_id=b.paciente_id $x";*/

						$query="SELECT DISTINCT
				btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
				b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
				b.tipo_id_paciente,b.paciente_id

				FROM pacientes as b,os_ordenes_servicios a,
				os_maestro c
        LEFT JOIN qx_programaciones_ordenes x ON(x.numero_orden_id=c.numero_orden_id),
        os_internas d,	planes e

				WHERE
				c.numero_orden_id=d.numero_orden_id
				AND a.orden_servicio_id=c.orden_servicio_id
				AND d.departamento='".$_SESSION['LocalCirugias']['departamento']."'
				AND e.plan_id='$plan'
				AND e.plan_id=a.plan_id
				AND c.sw_estado=1
				AND DATE(c.fecha_activacion) <= NOW()
				AND a.tipo_id_paciente=b.tipo_id_paciente
				AND a.paciente_id=b.paciente_id
        AND x.programacion_id IS NULL
         $x";
    
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al consultar por la cuenta";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$result->EOF){
			$vars[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
		}
		$result->Close();
		return $vars;
	}
/**
* buscar 4 es una funcion que busca o filtra por el numero de orden de la persona.
*/
	function Buscar4($nOrden){
		list($dbconn) = GetDBconn();
		$NUM=$_REQUEST['Of'];
		if(!$NUM)
		{   $NUM='0';   }
		$limit=$this->limit;
		list($dbconn) = GetDBconn();
		if(!empty($NUM))
		{   $x=" LIMIT ".$this->limit." OFFSET $NUM";   }
		else
		{   $x='';   }
		/*$query="SELECT DISTINCT
						btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
						b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
						b.tipo_id_paciente,b.paciente_id

						FROM pacientes as b,os_ordenes_servicios a

						WHERE
						cantidad_cargos_os(a.orden_servicio_id,'".$_SESSION['LocalCirugias']['departamento']."') > 0
						AND a.tipo_id_paciente=b.tipo_id_paciente
						AND a.paciente_id=b.paciente_id
						AND a.orden_servicio_id='$nOrden' $x";*/


						$query="SELECT DISTINCT
				btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
				b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
				b.tipo_id_paciente,b.paciente_id

				FROM pacientes as b,os_ordenes_servicios a,
				os_maestro c
        LEFT JOIN qx_programaciones_ordenes x ON(x.numero_orden_id=c.numero_orden_id),
        os_internas d,	planes e

				WHERE
				c.numero_orden_id=d.numero_orden_id
				AND a.orden_servicio_id=c.orden_servicio_id
				AND d.departamento='".$_SESSION['LocalCirugias']['departamento']."'
				AND e.plan_id=a.plan_id
				AND c.sw_estado=1
				AND DATE(c.fecha_activacion) <= NOW()
				AND a.tipo_id_paciente=b.tipo_id_paciente
				AND a.paciente_id=b.paciente_id
				AND a.orden_servicio_id='$nOrden' 
        AND x.programacion_id IS NULL 
        $x";
    
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Consultar por el numero de ingreso";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$result->EOF){
			$vars[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
		}
		$result->Close();
		return $vars;
	}
/**
* Busca los diferentes tipos de responsable (planes)
* @access public
* @return array
*/
	function responsables(){
	
		list($dbconn) = GetDBconn();
		
		$query="SELECT plan_id,plan_descripcion,tercero_id,tipo_tercero_id FROM planes
						WHERE fecha_final >= now() and estado=1 and fecha_inicio <= now() 
						and empresa_id = '".$_SESSION['LocalCirugias']['empresa']."'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while (!$result->EOF) {
			$var[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
		}
		$result->Close();
		return $var;
  }
/**
* Funcion que realiza una programacion de cirugia para una una orden de servicio
* @return boolean
*/
	function RealizarProgramaciondeOrden(){
    if(empty($_REQUEST['op'])){
      $this->frmError["MensajeError"]="Realice la seleccion de las ordenes de Servicio.";
      $this->FrmOrdenar($_REQUEST['nom'],$_REQUEST['id_tipo'],$_REQUEST['id']);
      return true;
    }
    list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query="SELECT nextval('qx_programaciones_programacion_id_seq')";
		$result=$dbconn->Execute($query);
		$programacionid=$result->fields[0];
		$query="INSERT INTO qx_programaciones(programacion_id,departamento,tipo_id_cirujano,cirujano_id,
		tipo_id_paciente,paciente_id,plan_id,estado,usuario_id,fecha_registro,
		diagnostico_id)VALUES('$programacionid','".$_SESSION['LocalCirugias']['departamento']."',NULL,NULL,
		'".$_REQUEST['id_tipo']."','".$_REQUEST['id']."',NULL,'1',
		'".UserGetUID()."','".date("Y-m-d H:i:s")."',NULL)";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		foreach($_REQUEST['op'] as $i=>$vector){
		  $cadena=explode(',',$vector);
			$numeroOrden=$cadena[0];
			$cargo=$cadena[1];
			$tarifario=$cadena[2];
			$ordenServicioId=$cadena[9];
			$plan=$cadena[10];
			$query="INSERT INTO qx_procedimientos_programacion(procedimiento_qx,tipo_id_cirujano,
			cirujano_id,programacion_id,plan_id,observaciones)VALUES('$cargo',NULL,NULL,'$programacionid','$plan','".$_REQUEST['observacion']."')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				$query="INSERT INTO qx_programaciones_ordenes(numero_orden_id,programacion_id,procedimiento_qx)VALUES('$numeroOrden','$programacionid','$cargo')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
		}
		$dbconn->CommitTrans();
    $_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']=$programacionid;
		$this->FormaProgramacionesQuirurgicas('',1);
		return true;
	}
/**
* Funcion que realiza una programacion de cirugia para una una orden de servicio
* @return array
*/
	function OrdenesPendientesPaciente($ProgramacionId){
	  list($dbconn) = GetDBconn();
    $query="SELECT cargo.cargo_cups,z.numero_orden_id,o.tipo_tercero_id,o.tercero_id
		FROM (SELECT * FROM
		(SELECT c.cargo_cups FROM qx_programaciones a,os_ordenes_servicios b,os_maestro c,os_internas d WHERE a.programacion_id='$ProgramacionId' AND  a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND b.orden_servicio_id=c.orden_servicio_id AND c.sw_estado='1' AND date(c.fecha_vencimiento)>='".date("Y-m-d H:i:s")."' AND c.numero_orden_id=d.numero_orden_id AND c.cargo_cups=d.cargo AND d.departamento='".$_SESSION['LocalCirugias']['departamento']."') as hola
    EXCEPT
    (SELECT f.procedimiento_qx as cargo_cups FROM qx_programaciones e,qx_procedimientos_programacion f WHERE e.programacion_id='$ProgramacionId' AND e.programacion_id=f.programacion_id)) as cargo
    LEFT JOIN qx_programaciones_ordenes xx ON (xx.programacion_id='$ProgramacionId' AND xx.procedimiento_qx=cargo.cargo_cups),
		qx_programaciones x,os_ordenes_servicios y,os_maestro z
    LEFT JOIN hc_os_solicitudes m ON (z.hc_os_solicitud_id=m.hc_os_solicitud_id)
    LEFT JOIN hc_evoluciones n ON (m.evolucion_id=n.evolucion_id) 
    LEFT JOIN profesionales_usuarios o ON (n.usuario_id=o.usuario_id),
    os_internas l
		WHERE x.programacion_id='$ProgramacionId' AND  x.tipo_id_paciente=y.tipo_id_paciente AND x.paciente_id=y.paciente_id AND y.orden_servicio_id=z.orden_servicio_id AND z.sw_estado='1' AND
		date(z.fecha_vencimiento)>='".date("Y-m-d H:i:s")."' AND z.numero_orden_id=l.numero_orden_id AND z.cargo_cups=l.cargo AND l.departamento='".$_SESSION['LocalCirugias']['departamento']."' AND z.cargo_cups=cargo.cargo_cups
    AND xx.procedimiento_qx IS NULL";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF) {
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
			return $var;
		}
	}
/**
* Funcion que consulta los procedimientos de una programacion
* @return boolean
*/
	function ProcedimientoAProgramacion(){
    list($dbconn) = GetDBconn();
    $query="SELECT a.numero_orden_id,a.cargo_cups,b.plan_id FROM os_maestro a,os_ordenes_servicios b WHERE a.numero_orden_id='".$_REQUEST['ordenId']."' AND a.cargo_cups='".$_REQUEST['cargo']."' AND a.orden_servicio_id=b.orden_servicio_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				$var=$result->GetRowAssoc($ToUpper = false);
			}
			$result->Close();
		}
		$procedimientoDes=$this->DescripcionProcedimiento($var['cargo_cups']);
		$this->ProcedimientosQuirurgicos($var['cargo_cups'],$procedimientoDes['descripcion'],$_REQUEST['cirujano'],'','',$_REQUEST['viaAcceso'],$_REQUEST['tipoCirugia'],$_REQUEST['ambitoCirugia'],$var['plan_id'],$var['numero_orden_id']);
		return true;
	}
/**
* Funcion que consulta los datos de una orden de cirugia
* @return array
* @param numero de orden de servicio
*/
	function DatosOrdenesCirugia($numeroordenid){
    list($dbconn) = GetDBconn();
    $query="SELECT c.tipo_cirugia_id,d.descripcion as tipocirugia,c.ambito_cirugia_id,e.descripcion as ambitocirugia,c.finalidad_procedimiento_id,f.descripcion as finalidadpro FROM os_maestro a,hc_os_solicitudes b,hc_os_solicitudes_procedimientos c,qx_tipos_cirugia d,qx_ambitos_cirugias e,qx_finalidades_procedimientos  f WHERE a.numero_orden_id='$numeroordenid' AND a.hc_os_solicitud_id=b.hc_os_solicitud_id AND a.cargo_cups=b.cargo AND c.hc_os_solicitud_id=b.hc_os_solicitud_id AND c.tipo_cirugia_id=d.tipo_cirugia_id AND c.ambito_cirugia_id=e.ambito_cirugia_id AND c.finalidad_procedimiento_id=f.finalidad_procedimiento_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				$var=$result->GetRowAssoc($ToUpper = false);
			}
			$result->Close();
		}
		return $var;
	}
/**
* Funcion que busca el diagnostico de una solicitud
* @return array
* @param numero de la programacion
*/
	function BusquedaDiagnosticosSolicitud($ProgramacionId){
    list($dbconn) = GetDBconn();
    $query="SELECT f.diagnostico_id,f.diagnostico_nombre FROM qx_programaciones_ordenes a,os_maestro b,hc_os_solicitudes c,hc_os_solicitudes_procedimientos d,hc_os_solicitudes_diagnosticos e,diagnosticos f WHERE a.programacion_id='$ProgramacionId' AND a.numero_orden_id=b.numero_orden_id AND b.hc_os_solicitud_id=c.hc_os_solicitud_id AND b.cargo_cups=c.cargo AND c.hc_os_solicitud_id=d.hc_os_solicitud_id AND d.hc_os_solicitud_id=e.hc_os_solicitud_id AND e.diagnostico_id=f.diagnostico_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
			  while(!$result->EOF) {
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
		}
		return $var;
	}

	function LlamaProgramacionQX(){
	  $_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']=$_REQUEST['programacion'];
	  $this->FormaProgramacionesQuirurgicas(1,'');
		return true;
	}

 /**
* Funcion que comprueba si esta fecha que llega por paramentro se encuentra reservada por otra programacion
* @return array
* @param integer numero que identifica al quirofano
* @param date fecha que se va a evasluar en la reserva
* @param time rango de tiempo minimo para realizar una reserva
*/
	function consultaProgramacion($Quiro,$SumaHora,$rango){
    list($dbconn) = GetDBconn();
		$time=date("H:i:s",mktime(0,(0+$rango),0,date('m'),date('d'),date('Y')));
		$query = "SELECT consulta_programacion('$Quiro','$SumaHora','$time')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $respuesta=$result->fields[0];
      if($respuesta=='0'){
        $vars=0;
			}else{
        $vars=$respuesta;
			}
		}
		$result->Close();
 		return $vars;
	}

	function LlamaFormaBuscadorProgramCirugias(){
    unset($_SESSION['QUIRURGICOS']['DiaEspe']);
    unset($_SESSION['QUIRURGICOS']['tipoTiempo']);
		if($_REQUEST['Menu']){
		  unset($_SESSION['QUIRURGICOS']['consulta']);
      $this->MenuQuirurjicos();
			return true;
		}
    $this->FormaBuscadorProgramCirugias();
		return true;
	}

	function CancelarReservaQuirofano(){
    $this->FormaLlamaMotivosCancelacionReserva($_REQUEST['reservaQuirofano']);
		return true;
	}

	/**
* Funcion que retorna un arreglo de los quirofanos con los que cuenta la ips en el departamento en el que esta logueado el usuario
* @return array
*/
	function MotivosCancelacionReserva(){
		list($dbconn) = GetDBconn();
		$query = "SELECT qx_motivo_cancelacion_quirofano_id,descripcion FROM qx_motivos_cancelacion_quirofanos";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
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
		$result->Close();
 		return $vars;
	}

	function InsertarCancelarReservaProgramacion(){
	  if($_REQUEST['regresar']){
      $this->ReserveEquiposQuirofanos();
			return true;
		}
		if($_REQUEST['motivoCancel']==-1){
      $this->frmError["MensajeError"]="Seleccione Algun Motivo de Cancelacion.";
			$this->FormaLlamaMotivosCancelacionReserva($_REQUEST['reservaQuirofano'],$_REQUEST['motivoCancel'],$_REQUEST['observacion']);
			return true;
		}
    list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query = "INSERT INTO qx_reservas_quirofanos_canceladas(qx_motivo_cancelacion_quirofano_id,
		observacion_id,usuario_id,fecha_registro,qx_quirofano_programacion_id)VALUES('".$_REQUEST['motivoCancel']."',
		'".$_REQUEST['observacion']."','".UserGetUID()."','".date("Y-m-d H:i:s")."','".$_REQUEST['reservaQuirofano']."')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
      $query ="UPDATE qx_quirofanos_programacion SET qx_tipo_reserva_quirofano_id='0' WHERE qx_quirofano_programacion_id='".$_REQUEST['reservaQuirofano']."'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		$dbconn->CommitTrans();
		$this->FormaProgramacionesQuirurgicas('',1);
		return true;
	}

	function RealizarReservaQuirofano(){
		$this->FormaRealizaReservasQuirofano();
		return true;
	}

	function LlamaTipoReservaEspecial(){
	  if($_REQUEST['Cancelar']){
      $this->MenuQuirurjicos();
			return true;
		}
		if(sizeof($_REQUEST['seleccionReserv'])<1){
		  $this->frmError["MensajeError"]="No ha Seleccionado ninguna Reserva";
      $this->FormaRealizaReservasQuirofano();
			return true;
		}
	  $this->FormaTiposdeReservaQuirofano($_REQUEST['DiaEspe'],$_REQUEST['seleccionReserv'],$_REQUEST['rango']);
		return true;
	}
/**
* Funcion que retorna un arreglo de los quirofanos con los que cuenta la ips en el departamento en el que esta logueado el usuario
* @return array
*/
	function MotivosReservaQuirofano(){
		list($dbconn) = GetDBconn();
		$query = "SELECT qx_tipo_reserva_quirofano_id,descripcion FROM qx_tipo_reservas_quirofanos WHERE qx_tipo_reserva_quirofano_id != '0' AND qx_tipo_reserva_quirofano_id != '3'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
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
		$result->Close();
 		return $vars;
	}
/**
* Funcion que inserta la reserva de un quirofano o llama a las formas que requieren mas datos para esta reserva
* @return boolean
*/
	function InsertarReservaquirofano(){

    if($_REQUEST['regresar']){
      $this->FormaRealizaReservasQuirofano();
			return true;
		}
		if($_REQUEST['motivoReserva']==-1){
      $this->frmError["MensajeError"]="Seleccione Algun Motivo de Reserva.";
			$this->FormaTiposdeReservaQuirofano($_REQUEST['DiaEspe'],$_REQUEST['seleccionReserv'],$_REQUEST['rango']);
			return true;
		}
		if($_REQUEST['motivoReserva']=='1'){
      $this->ReservaQuirofanoCliente($_REQUEST['DiaEspe'],$_REQUEST['seleccionReserv'],$_REQUEST['rango']);
			return true;
		}elseif($_REQUEST['motivoReserva']=='2'){
      $this->ReservaQuirofanoPlan($_REQUEST['DiaEspe'],$_REQUEST['seleccionReserv'],$_REQUEST['rango']);
			return true;
		}else{
		  if($_REQUEST['seleccionReserv']){
				foreach($_REQUEST['seleccionReserv'] as $x=>$valor){
					$vectorTemp[]=$valor;
				}
			}
			$valorTmp=$vectorTemp[0];
			$cadena=explode('/',$valorTmp);
			$Quirofano=$cadena[0];
			$FechaIni=$cadena[1];
			$Fecha=$this->FechaStamp($FechaIni);
			$infoCadena = explode ('/', $Fecha);
			$dia=$infoCadena[0];
			$mes=$infoCadena[1];
			$ano=$infoCadena[2];
			$rango=$_REQUEST['rango'];
			if(sizeof($vectorTemp)==1){
				$HoraDef=$this->HoraStamp($FechaIni);
				$infoCadena = explode (':',$HoraDef);
				$Hora=$infoCadena[0];
				$Minutos=$infoCadena[1];
				$FechaFin=date('Y-m-d H:i:s',mktime($Hora,($Minutos+($rango-1)),0,$mes,$dia,$ano));
			}else{
				$cont=sizeof($vectorTemp)-1;
				$valorTmp=$vectorTemp[$cont];
				$cadena=explode('/',$valorTmp);
				$FechaFin=$cadena[1];
				$HoraDef=$this->HoraStamp($FechaFin);
				$infoCadena = explode (':',$HoraDef);
				$Hora=$infoCadena[0];
				$Minutos=$infoCadena[1];
        $FechaFin=date('Y-m-d H:i:s',mktime($Hora,$Minutos+($rango-1),0,$mes,$dia,$ano));
			}
			list($dbconn) = GetDBconn();
			$query = "INSERT INTO qx_quirofanos_programacion(quirofano_id,hora_inicio,hora_fin,programacion_id,qx_tipo_reserva_quirofano_id,departamento,usuario_id,fecha_registro)
			VALUES('$Quirofano','$FechaIni','$FechaFin',NULL,'".$_REQUEST['motivoReserva']."','".$_SESSION['LocalCirugias']['departamento']."','".UserGetUID()."',
			'".date("Y-m-d H:i:s")."')";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
        $dbconn->CommitTrans();
				$mensaje='Su Reserva ha sido Creada Exitosamente';
				$titulo='RESERVA QUIROFANO';
				$accion=ModuloGetURL('app','Quirurgicos','user','MenuQuirurjicos');
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
		}
	}
/**
* Funcion que busca en los profesionales especialistas existentes en la base de datos
* @return array
*/
	function SeleccionTercerosReserva(){

		list($dbconn) = GetDBconn();
		$query = "SELECT  x.tercero_id,y.nombre_tercero,x.tipo_id_tercero
    FROM terceros_clientes x,terceros y
    WHERE x.empresa_id='".$_SESSION['LocalCirugias']['empresa']."' AND
    x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id
    ORDER BY y.nombre_tercero";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			$i=0;
			while (!$result->EOF) {
				$vars[$i]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			  $i++;
			}
		}
		$result->Close();
 		return $vars;
	}
/**
* Funcion que inserta la reserva de un quirofano o y pone como responsable al cliente
* @return boolean
*/
	function InsertarReservaCliente(){
	  if($_REQUEST['regresar']){
      $this->FormaTiposdeReservaQuirofano($_REQUEST['DiaEspe'],$_REQUEST['seleccionReserv']);
			return true;
		}
    if(!$_REQUEST['codigo'] || !$_REQUEST['tipoTerceroId']){
      $this->frmError["MensajeError"]="Seleccione El cliente que Realiza la Reserva.";
			$this->ReservaQuirofanoCliente($_REQUEST['DiaEspe'],$_REQUEST['seleccionReserv']);
			return true;
		}
		if($_REQUEST['seleccionReserv']){
      foreach($_REQUEST['seleccionReserv'] as $x=>$valor){
				$vectorTemp[]=$valor;
			}
		}
		$valorTmp=$vectorTemp[0];
		$cadena=explode('/',$valorTmp);
		$Quirofano=$cadena[0];
		$FechaIni=$cadena[1];
		$Fecha=$this->FechaStamp($FechaIni);
		$infoCadena = explode ('/', $Fecha);
		$dia=$infoCadena[0];
		$mes=$infoCadena[1];
		$ano=$infoCadena[2];
		$rango=$_REQUEST['rango'];
		if(sizeof($vectorTemp)==1){
			$HoraDef=$this->HoraStamp($FechaIni);
			$infoCadena = explode (':',$HoraDef);
			$Hora=$infoCadena[0];
			$Minutos=$infoCadena[1];
			$FechaFin=date('Y-m-d H:i:s',mktime($Hora,($Minutos+($rango-1)),0,$mes,$dia,$ano));
		}else{
			$cont=sizeof($vectorTemp)-1;
			$valorTmp=$vectorTemp[$cont];
			$cadena=explode('/',$valorTmp);
			$FechaFin=$cadena[1];
			$HoraDef=$this->HoraStamp($FechaFin);
			$infoCadena = explode (':',$HoraDef);
			$Hora=$infoCadena[0];
			$Minutos=$infoCadena[1];
			$FechaFin=date('Y-m-d H:i:s',mktime($Hora,$Minutos+($rango-1),0,$mes,$dia,$ano));
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
    $query="SELECT nextval('qx_quirofanos_programacion_qx_quirofano_programacion_id_seq')";
		$result=$dbconn->Execute($query);
		$ProgramacionIdQuiro=$result->fields[0];
		$query = "INSERT INTO qx_quirofanos_programacion(qx_quirofano_programacion_id,quirofano_id,hora_inicio,hora_fin,programacion_id,qx_tipo_reserva_quirofano_id,departamento,usuario_id,fecha_registro)
		VALUES('$ProgramacionIdQuiro','$Quirofano','$FechaIni','$FechaFin',NULL,'1','".$_SESSION['LocalCirugias']['departamento']."','".UserGetUID()."',
		'".date("Y-m-d H:i:s")."')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
			$NumTercero=$_REQUEST['codigo'];
      $IdTercero=$_REQUEST['tipoTerceroId'];
      $query = "INSERT INTO qx_reservas_quirofanos_clientes(qx_quirofano_programacion_id,empresa_id,
			tipo_id_tercero,tercero_id,observacion,usuario_id,fecha_registro)
			VALUES('$ProgramacionIdQuiro','".$_SESSION['LocalCirugias']['empresa']."','$IdTercero','$NumTercero','".$_REQUEST['observacion']."','".UserGetUID()."','".date("Y-m-d H:i:s")."')";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		$dbconn->CommitTrans();
		$mensaje='Su Reserva ha sido Creada Exitosamente';
		$titulo='RESERVA QUIROFANO';
		$accion=ModuloGetURL('app','Quirurgicos','user','MenuQuirurjicos');
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		return true;
	}
/**
* Funcion que inserta la muestra los planes activos de la empresa
* @return array
*/
	function SeleccionPlanesReserva(){
    list($dbconn) = GetDBconn();
		$query = "SELECT plan_id,plan_descripcion FROM planes WHERE empresa_id='".$_SESSION['LocalCirugias']['empresa']."' AND estado='1'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
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
		$result->Close();
 		return $vars;
	}
/**
* Funcion que inserta la reserva de un quirofano o y pone como responsable al plan
* @return boolean
*/
	function InsertarReservaPlan(){

    if($_REQUEST['regresar']){
      $this->FormaTiposdeReservaQuirofano($_REQUEST['DiaEspe'],$_REQUEST['seleccionReserv']);
			return true;
		}
    if($_REQUEST['plan']==-1){
      $this->frmError["MensajeError"]="Seleccione El plan que Realiza la Reserva.";
			$this->ReservaQuirofanoPlan($_REQUEST['DiaEspe'],$_REQUEST['seleccionReserv']);
			return true;
		}
		if($_REQUEST['seleccionReserv']){
      foreach($_REQUEST['seleccionReserv'] as $x=>$valor){
				$vectorTemp[]=$valor;
			}
		}
		$valorTmp=$vectorTemp[0];
		$cadena=explode('/',$valorTmp);
		$Quirofano=$cadena[0];
		$FechaIni=$cadena[1];
		$rango=$_REQUEST['rango'];
		$Fecha=$this->FechaStamp($FechaIni);
		$infoCadena = explode ('/', $Fecha);
		$dia=$infoCadena[0];
		$mes=$infoCadena[1];
		$ano=$infoCadena[2];
		if(sizeof($vectorTemp)==1){
			$HoraDef=$this->HoraStamp($FechaIni);
			$infoCadena = explode (':',$HoraDef);
			$Hora=$infoCadena[0];
			$Minutos=$infoCadena[1];
			$FechaFin=date('Y-m-d H:i:s',mktime($Hora,($Minutos+($rango-1)),0,$mes,$dia,$ano));
		}else{
			$cont=sizeof($vectorTemp)-1;
			$valorTmp=$vectorTemp[$cont];
			$cadena=explode('/',$valorTmp);
			$FechaFin=$cadena[1];
			$HoraDef=$this->HoraStamp($FechaFin);
			$infoCadena = explode (':',$HoraDef);
			$Hora=$infoCadena[0];
			$Minutos=$infoCadena[1];
			$FechaFin=date('Y-m-d H:i:s',mktime($Hora,$Minutos+($rango-1),0,$mes,$dia,$ano));
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
    $query="SELECT nextval('qx_quirofanos_programacion_qx_quirofano_programacion_id_seq')";
		$result=$dbconn->Execute($query);
		$ProgramacionIdQuiro=$result->fields[0];
		$query = "INSERT INTO qx_quirofanos_programacion(qx_quirofano_programacion_id,quirofano_id,hora_inicio,hora_fin,programacion_id,qx_tipo_reserva_quirofano_id,departamento,usuario_id,fecha_registro)
		VALUES('$ProgramacionIdQuiro','$Quirofano','$FechaIni','$FechaFin',NULL,'2','".$_SESSION['LocalCirugias']['departamento']."','".UserGetUID()."',
		'".date("Y-m-d H:i:s")."')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
		  $cadena=explode('/',$_REQUEST['tercero']);
			$NumTercero=$cadena[0];
      $IdTercero=$cadena[1];
      $query = "INSERT INTO qx_reservas_quirofanos_planes(qx_quirofano_programacion_id,plan_id,
			observacion,usuario_id,fecha_registro)
			VALUES('$ProgramacionIdQuiro','".$_REQUEST['plan']."','".$_REQUEST['observacion']."','".UserGetUID()."','".date("Y-m-d H:i:s")."')";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		$dbconn->CommitTrans();
		$mensaje='Su Reserva ha sido Creada Exitosamente';
		$titulo='RESERVA QUIROFANO';
		$accion=ModuloGetURL('app','Quirurgicos','user','MenuQuirurjicos');
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		return true;
	}
/**
* Funcion que trae los datos de un plan y su responsable
* @return array
*/
	function traeDatosLiberarReserva($Programacion){
    list($dbconn) = GetDBconn();
		$query = "SELECT x.plan_id,y.tipo_tercero_id,y.tercero_id FROM qx_programaciones x,planes y WHERE x.programacion_id='$Programacion' AND x.plan_id=y.plan_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
			  $vars=$result->GetRowAssoc($ToUpper = false);
			}
		}
		$result->Close();
 		return $vars;
	}
/**
* Funcion que muestra las reservas que existen sobre un rango de tiempo
* @return boolean
*/
	function VerDetalleSobreReserva($horario,$quirofano,$DiaEspe){
    list($dbconn) = GetDBconn();
		if(!$horario){
		  $horario=$_REQUEST['SumaHora'];
		}
		if(!$quirofano){
		  $quirofano=$_REQUEST['Quiro'];
		}
		if(!$_REQUEST['DiaEspe']){
      $_REQUEST['DiaEspe']=$DiaEspe;
		}
		$query ="SELECT qx_quirofano_programacion_id,programacion_id FROM qx_quirofanos_programacion WHERE (hora_inicio <= timestamp '$horario' and timestamp '$horario' <= hora_fin)
    AND quirofano_id='$quirofano' AND qx_tipo_reserva_quirofano_id != '0'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
		$this->FormaConsultaRangoReserva($vars,$horario,$quirofano,$_REQUEST['DiaEspe']);
		return true;
	}
/**
* Funcion que muestra los datos de una reserva del quirofano
* @return array
*/
	function DatosReservaGeneral($quirofanoprogramacion){
	  list($dbconn) = GetDBconn();
    $query ="SELECT a.quirofano_id,a.hora_inicio,a.hora_fin,a.qx_tipo_reserva_quirofano_id,d.descripcion,
		b.empresa_id,b.tipo_id_tercero,b.tercero_id,b.observacion,
		c.plan_id,c.observacion as observacionplan
		FROM qx_quirofanos_programacion a LEFT JOIN qx_reservas_quirofanos_clientes b ON (a.qx_quirofano_programacion_id=b.qx_quirofano_programacion_id)
    LEFT JOIN qx_reservas_quirofanos_planes c ON (a.qx_quirofano_programacion_id=c.qx_quirofano_programacion_id),qx_tipo_reservas_quirofanos d
		WHERE a.qx_quirofano_programacion_id='$quirofanoprogramacion' AND a.qx_tipo_reserva_quirofano_id=d.qx_tipo_reserva_quirofano_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
			  while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			return $vars;
		}
	}
/**
* Funcion que muestra si el rango de tiempo esta bajo algun responsable
* @return array
*/
	function DatosReservaGeneralPaciente($quirofanoprogramacion){
    list($dbconn) = GetDBconn();
    $query ="SELECT a.quirofano_id,a.hora_inicio,a.hora_fin,
		b.tipo_id_paciente,b.paciente_id,b.programacion_id
		FROM qx_quirofanos_programacion a,qx_programaciones b
		WHERE a.qx_quirofano_programacion_id='$quirofanoprogramacion' AND a.programacion_id=b.programacion_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($ToUpper = false);
			}
		}
		return $vars;
	}
/**
* Funcion que muestra el nombre del responsable
* @return array
*/
	function NombreTercero($TipoTercero,$TerceroId){
    list($dbconn) = GetDBconn();
    $query = "SELECT nombre_tercero
    FROM terceros
    WHERE tercero_id='$TerceroId' AND tipo_id_tercero='$TipoTercero'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($ToUpper = false);
			}
		}
		return $vars;
	}
/**
* Funcion que llama la forma que visualiza las reservas del quirofano
* @return boolean
*/
	function accionConsultaReservas(){
    if($_REQUEST['regresar']){
      $this->FormaRealizaReservasQuirofano();
			return true;
		}
	}
/**
* Funcion que elimina una reserva de un responsable de la reserva
* @return boolean
*/
	function CancelarReservaGeneral(){
    list($dbconn) = GetDBconn();
    $query="DELETE FROM qx_quirofanos_programacion WHERE qx_quirofano_programacion_id='".$_REQUEST['programacionQuiro']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->VerDetalleSobreReserva($_REQUEST['horario'],$_REQUEST['quirofano'],$_REQUEST['DiaEspe']);
		return true;
	}
/**
* Funcion que llama la forma que visuliza una consulta las reservas del quirofano
* @return boolean
*/
	function ConsultaProgamacionPacQX(){
	  $_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']=$_REQUEST['ProgramacionId'];
    $_SESSION['QUIRURGICOS']['ReservaQuroPaciente']=1;
		$_SESSION['QUIRURGICOS']['DiaEspecial']=$_REQUEST['DiaEspe'];
    $this->FormaProgramacionesQuirurgicas(1,'');
		return true;
	}

	function ProcesoCancelarLaProgramacion(){
          $this->DatosCancelacionProgramacion($_REQUEST['mayorFecha']);
          return true;
	}
/**
* Funcion que retorna un arreglo de los quirofanos con los que cuenta la ips en el departamento en el que esta logueado el usuario
* @return array
*/
	function MotivosCancelacionProgramacion(){
		list($dbconn) = GetDBconn();
		$query = "SELECT qx_motivo_cancelacion_programacion_id,descripcion FROM qx_motivos_cancelacion_programaciones";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
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
		$result->Close();
 		return $vars;
	}

     /**
     * Funcion que cancela una programacion quirurgica
     * @return boolean
     */
	function CancelacionProgramacionQX()
     {
		if($_REQUEST['regresar']){
               $this->FormaProgramacionesQuirurgicas('',$_REQUEST['mayorFecha']);
               return true;
		}
     	list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query = "INSERT INTO qx_programaciones_canceladas(programacion_id,observacion,usuario_id,fecha_registro,qx_motivo_cancelacion_programacion_id)
				VALUES('".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."','".$_REQUEST['observacion']."','".UserGetUID()."','".date("Y-m-d H:i:s")."','".$_REQUEST['motivoCancel']."')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
			$query = "UPDATE qx_programaciones SET estado='0' WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
                    $query = "SELECT qx_quirofano_programacion_id 
                    		FROM qx_quirofanos_programacion 
                              WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."'
                              AND qx_tipo_reserva_quirofano_id = '3';";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }else{
                         if($result->RecordCount()>0)
                         {
                              $reservaQuirofano=$result->fields[0];            
                              $query = "INSERT INTO qx_reservas_quirofanos_canceladas(qx_motivo_cancelacion_quirofano_id,
                                        observacion_id,usuario_id,fecha_registro,qx_quirofano_programacion_id)VALUES('3',
                                        'CANCELADA LA PROGRAMACION DE CIRUGIA','".UserGetUID()."','".date("Y-m-d H:i:s")."','".$reservaQuirofano."')";
                              $result = $dbconn->Execute($query);
                              if ($dbconn->ErrorNo() != 0)
                              {
                                   $this->error = "Error al Cargar el Modulo";
                                   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                   $dbconn->RollbackTrans();
                              	return false;
                              }else
                              {
                                   $query ="UPDATE qx_quirofanos_programacion SET qx_tipo_reserva_quirofano_id='0' WHERE qx_quirofano_programacion_id='".$reservaQuirofano."'";
                                   $result = $dbconn->Execute($query);
                                   if ($dbconn->ErrorNo() != 0) 
                                   {
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                   }
						}
          			}    
        			}      
      		}
		}    
		$dbconn->CommitTrans();
		unset($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']);
	  	$mensaje='La Programacion ha sido Cancelada, de click en Aceptar para Regresar al Menu';
		$titulo='RESERVA QUIROFANO';
		$accion=ModuloGetURL('app','Quirurgicos','user','MenuQuirurjicos');
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		return true;
	}

	function TurnosInstrumentadores(){
      $this->FommaReservaTurnosInstrumentadores();
			return true;
	}

	function HallartotalEquiposMoviles($tipoEquipo){
    list($dbconn) = GetDBconn();
		$query = "SELECT a.equipo_id,a.descripcion,b.descripcion as departamento FROM qx_equipos_moviles a,departamentos b WHERE a.tipo_equipo_id='$tipoEquipo' AND a.departamento=b.departamento";
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
			  while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			return $vars;
		}
		$result->Close();
 		return $vars;
	}

	function HallarNombreTipoEquipo($tipoEquipo){
    list($dbconn) = GetDBconn();
		$query = "SELECT descripcion FROM qx_tipo_equipo_movil  WHERE tipo_equipo_id='$tipoEquipo'";
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($ToUpper = false);
			}
		}
		$result->Close();
 		return $vars;
	}

	function LlamaImprimirProgramacionQX(){
		list($dbconn) = GetDBconn();
    $queryUn="SELECT a.programacion_id,a.tipo_id_paciente,a.paciente_id,b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombrepac,
		b.fecha_nacimiento,a.tipo_id_cirujano,a.cirujano_id,x.nombre as cirujano,a.diagnostico_id,z.diagnostico_nombre,
		c.quirofano_id,y.descripcion as nomquirofano,c.hora_inicio,c.hora_fin,c.qx_tipo_reserva_quirofano_id,v.descripcion as nomreserva,
		d.tipo_id_tercero,d.tercero_id,u.nombre as anestesiologo,d.tipo_id_instrumentista,d.instrumentista_id,t.nombre as instrumentista,
		d.tipo_id_circulante,d.circulante_id,s.nombre as circulante,		
		a.usuario_id,a.fecha_registro,p.nombre as nomusuario
		FROM pacientes b,qx_programaciones a
		LEFT JOIN profesionales x ON (a.tipo_id_cirujano=x.tipo_id_tercero AND a.cirujano_id=x.tercero_id)
		LEFT JOIN diagnosticos z ON (a.diagnostico_id=z.diagnostico_id)
		LEFT JOIN qx_quirofanos_programacion c ON (a.programacion_id=c.programacion_id AND c.qx_tipo_reserva_quirofano_id!=0)
		LEFT JOIN qx_quirofanos y ON (y.quirofano=c.quirofano_id AND y.sw_programacion='1')
		LEFT JOIN qx_tipo_reservas_quirofanos v ON (c.qx_tipo_reserva_quirofano_id=v.qx_tipo_reserva_quirofano_id)
		LEFT JOIN qx_anestesiologo_programacion d ON (a.programacion_id=d.programacion_id)
		LEFT JOIN profesionales u ON (d.tipo_id_tercero=u.tipo_id_tercero AND d.tercero_id=u.tercero_id)
		LEFT JOIN profesionales t ON (d.tipo_id_instrumentista=t.tipo_id_tercero AND d.instrumentista_id=t.tercero_id)
		LEFT JOIN profesionales s ON (d.tipo_id_circulante=s.tipo_id_tercero AND d.circulante_id=s.tercero_id)				
		LEFT JOIN system_usuarios p ON (p.usuario_id=a.usuario_id)
		WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND
		a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND a.estado=1";
		//LEFT JOIN qx_datos_procedimientos_cirugias e ON (a.programacion_id=e.programacion_id)
		//LEFT JOIN qx_vias_acceso m ON (m.via_acceso=e.via_acceso)
		//LEFT JOIN qx_tipos_cirugia n ON (n.tipo_cirugia_id=e.tipo_cirugia)
		//LEFT JOIN qx_ambitos_cirugias o ON (o.ambito_cirugia_id=e.ambito_cirugia)
		//e.via_acceso,m.descripcion as nomvia,e.tipo_cirugia,n.descripcion as nomtipcirugia,e.ambito_cirugia,o.descripcion as nomambito,
		$result = $dbconn->Execute($queryUn);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($ToUpper = false);
			}		
		}
		$queryDo="SELECT a.procedimiento_qx,c.descripcion as nomcups,a.tipo_id_cirujano,a.cirujano_id,d.nombre as cirujano,
		b.tipo_id_pediatra,b.pediatra_id,f.nombre as pediatra,a.observaciones
		FROM qx_procedimientos_programacion a
		LEFT JOIN cups c ON (a.procedimiento_qx=c.cargo)
		LEFT JOIN profesionales d ON (a.tipo_id_cirujano=d.tipo_id_tercero AND a.cirujano_id=d.tercero_id)
		LEFT JOIN qx_procedimientos_programacion_pediatricos b ON (a.programacion_id=b.programacion_id AND a.procedimiento_qx=b.procedimiento_qx)
		LEFT JOIN profesionales f ON (b.tipo_id_pediatra=f.tipo_id_tercero AND b.pediatra_id=f.tercero_id)
		WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."'";
		$result = $dbconn->Execute($queryDo);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
			  while(!$result->EOF){
					$varsUno[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		$queryTr="SELECT a.paquete_insumos_id,b.descripcion as paquete,a.cantidad
		FROM qx_programacion_paquetes a
		LEFT JOIN qx_paquetes_insumos b ON (b.paquete_insumos_id=a.paquete_insumos_id)
		WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."'";
		$result = $dbconn->Execute($queryTr);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
			  while(!$result->EOF){
					$varsDos[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		$arr[0]=$vars;
		$arr[1]=$varsUno;
		$arr[2]=$varsDos;
		return $arr;
		//Esta es la funcion de Imprimir pero en la impresora pos
	  /*list($dbconn) = GetDBconn();
    $query="SELECT b.tipo_id_tercero as tipo_id_cirujano,b.tercero_id as cirujano_id,b.nombre,a.tipo_id_paciente,a.paciente_id,
    c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombrepac,
		a.usuario_id,e.nombre as nombreusu,f.diagnostico_nombre,
    h.tipo_id_tercero as tipo_id_anest,h.tercero_id as anest_id,h.nombre as nombreanest,
		j.descripcion as viaacceso,k.descripcion as tipoidcirugia,m.descripcion as ambito
		FROM qx_programaciones a
		LEFT JOIN profesionales b ON (a.tipo_id_cirujano=b.tipo_id_tercero AND a.cirujano_id=b.tercero_id)
		LEFT JOIN diagnosticos f ON (a.diagnostico_id=f.diagnostico_id)
		LEFT JOIN qx_anestesiologo_programacion g ON(a.programacion_id=g.programacion_id)
    LEFT JOIN profesionales h ON (g.tipo_id_tercero=h.tipo_id_tercero AND g.tercero_id=h.tercero_id)
		LEFT JOIN qx_datos_procedimientos_cirugias i ON (a.programacion_id=i.programacion_id)
		LEFT JOIN qx_vias_acceso j ON(i.via_acceso=j.via_acceso)
		LEFT JOIN qx_tipos_cirugia k ON(i.tipo_cirugia=k.tipo_cirugia_id)
		LEFT JOIN qx_ambitos_cirugias m ON(i.ambito_cirugia=m.ambito_cirugia_id),
		pacientes c,system_usuarios e
		WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND a.tipo_id_paciente=c.tipo_id_paciente
		AND a.paciente_id=c.paciente_id AND	a.usuario_id=e.usuario_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				$datosPrincipal=$result->GetRowAssoc($ToUpper = false);
			}
		}
    $query="SELECT b.abreviatura,b.descripcion as quiro,a.hora_inicio,a.hora_fin,a.usuario_id,c.nombre
		FROM qx_quirofanos_programacion a,qx_quirofanos b,system_usuarios c
		WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND
		a.quirofano_id=b.quirofano AND a.usuario_id=c.usuario_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				$datosQuiro=$result->GetRowAssoc($ToUpper = false);
			}
		}

		$query="SELECT a.procedimiento_qx,e.descripcion as procedimiento,
		b.tipo_id_tercero as tipo_id_cirujano,b.tercero_id as cirujano_id,b.nombre as nomcir,
		c.tipo_id_tercero as tipo_id_ayudante,c.tercero_id as ayudante_id,b.nombre as nomay,
		d.plan_descripcion as plan
		FROM qx_procedimientos_programacion a
		LEFT JOIN profesionales b ON (a.tipo_id_cirujano=b.tipo_id_tercero AND a.cirujano_id=b.tercero_id)
		LEFT JOIN profesionales c ON (a.tipo_id_ayudante=c.tipo_id_tercero AND a.ayudante_id=c.tercero_id)
		LEFT JOIN planes d ON (a.plan_id=d.plan_id),cups e
		WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND
		a.procedimiento_qx=e.cargo";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF){
					$Procedimientos[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		$this->ImprimirProgramacionQX($datosPrincipal,$datosQuiro,$Procedimientos);
		return true;*/
	}

	function ImprimirProgramacionQX($datosPrincipal,$datosQuiro,$Procedimientos){


// 	Esta es la funcion de imprimir pero en la impresora pos
		/*if(!IncludeFile("classes/reports/reports.class.php")){
				$this->error = "No se pudo inicializar la Clase de Reportes";
				$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
				return false;
		}
		$classReport = new reports;
		$reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='Quirurgicos',$reporte_name='reportProgramacion',
		$datos=array("empresa"=>$_SESSION['LocalCirugias']['NombreEmp'],"dpto"=>$_SESSION['LocalCirugias']['NombreDpto'],"datosPrincipal"=>$datosPrincipal,
		"datosQuiro"=>$datosQuiro,"Procedimientos"=>$Procedimientos),$impresora='starPC16');
		if(!$reporte){
				$this->error = $classReport->GetError();
				$this->mensajeDeError = $classReport->MensajeDeError();
				unset($classReport);
				return false;
		}
		$resultado=$classReport->GetExecResultado();
		unset($classReport);
		if(!empty($resultado[codigo])){
				echo "El PrintReport retorno : " . $resultado[codigo] . "<br>";
		}else{
				echo "PrintReport : OK";
		}
		$this->FormaProgramacionesQuirurgicas();
		return true;*/
	}

	/**
* Funcion que busca en los profesionales especialistas existentes en la base de datos
* @return array
*/
	/*function profesionalesEspecialistaPediatria(){
    $departamento=$_SESSION['LocalCirugias']['departamento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT  x.tercero_id,c.nombre_tercero as nombre,x.tipo_id_tercero
    FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c
    WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND x.tipo_id_tercero=y.tipo_id_tercero AND
    x.tercero_id=y.tercero_id AND y.departamento='$departamento' AND z.especialidad=l.especialidad AND
    z.sw_pediatra='1' AND x.tercero_id=l.tercero_id AND x.tipo_id_tercero=l.tipo_id_tercero  AND
    x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero AND
    profesional_activo(c.tipo_id_tercero,c.tercero_id,'$departamento')='1'
    ORDER BY c.nombre_tercero";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			$i=0;
			while (!$result->EOF) {
				$vars[$i]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			  $i++;
			}
		}
		$result->Close();
 		return $vars;
	}*/

	function BuscarPaqueteProcedimiento(){
		list($dbconn) = GetDBconn();
		$query = "SELECT DISTINCT c.paquete_insumos_id,c.descripcion
    FROM qx_procedimientos_programacion a,qx_cups_paquetes_insumos b,qx_paquetes_insumos c
    WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND
    a.procedimiento_qx=b.cargo AND b.paquete_insumos_id=c.paquete_insumos_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		$result->Close();
 		return $vars;
	}

	function paquetesInsertadosRequeridos(){
		list($dbconn) = GetDBconn();
		$query = "SELECT a.paquete_insumos_id,a.cantidad,b.descripcion
		FROM qx_programacion_paquetes a,qx_paquetes_insumos b
		WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND
		a.paquete_insumos_id=b.paquete_insumos_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				while (!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
	}

	function InsumosInsertadosRequeridos(){
		list($dbconn) = GetDBconn();
		$query = "SELECT a.codigo_producto,a.cantidad,b.descripcion
		FROM qx_programacion_insumos a,inventarios_productos b
		WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND
		a.codigo_producto=b.codigo_producto";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				while (!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
	}

	function InsertarReqPaqutes(){

		list($dbconn) = GetDBconn();
		if($_REQUEST['buscar']){
			$this->Reserva_Paquetes_Insumos_qx($_REQUEST['codigoPaquete'],$_REQUEST['Descripcion']);
			return true;
		}
    if($_REQUEST['Seleccionar']){
      $query="DELETE FROM qx_programacion_paquetes WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."';";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      $Cantidades=$_REQUEST['Cantidad'];
      foreach($_REQUEST['SeleccionActual'] as $codPaqueteActual=>$val){
        if(!in_array($codPaqueteActual,$_REQUEST['Seleccion'])){
          unset($_SESSION['CIRUGIAS']['PROGRAMACION']['PAQUETES'][$codPaqueteActual]);
        }
      }
      foreach($_REQUEST['Seleccion'] as $codPaquete=>$val){
        $_SESSION['CIRUGIAS']['PROGRAMACION']['PAQUETES'][$codPaquete]=$Cantidades[$codPaquete];
      }
      $this->Reserva_Paquetes_Insumos_qx($_REQUEST['codigoPaquete'],$_REQUEST['Descripcion']);
      return true;
    }
    list($dbconn) = GetDBconn();
    if(sizeof($_SESSION['CIRUGIAS']['PROGRAMACION']['PAQUETES'])>0){
      $query.="DELETE FROM qx_programacion_paquetes WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."';";
      foreach($_SESSION['CIRUGIAS']['PROGRAMACION']['PAQUETES'] as $codPaquete=>$cantidad){
        if($codPaquete){
          $query.="INSERT INTO qx_programacion_paquetes(programacion_id,paquete_insumos_id,cantidad)
          VALUES('".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."','$codPaquete','$cantidad');";
        }  
      }
    }
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    unset($_SESSION['CIRUGIAS']['PROGRAMACION']['PAQUETES']);
    $this->FormaProgramacionesQuirurgicas('',1);
		return true;
	}

	function buscarPaquetes($codigoPaquete,$Descripcion){
		list($dbconn) = GetDBconn();
		$query="SELECT paquete_insumos_id,descripcion
    FROM qx_paquetes_insumos";
    if($codigoPaquete || $Descripcion){
      $query.=" WHERE ";
      if($codigoPaquete){
        $query.=" paquete_insumos_id='$codigoPaquete'";
        $ya=1;
      }
      if($Descripcion){
        $query.=" descripcion LIKE '%".strtoupper($codigoPaquete)."%'";
      }
    }
    $query.=" ORDER BY descripcion";
		if(empty($_REQUEST['conteo'])){
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$this->conteo=$result->RecordCount();
		}else{
			$this->conteo=$_REQUEST['conteo'];
		}
		$query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";
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

	function EliminarPauqeteInsertado(){
		list($dbconn) = GetDBconn();
		$query="DELETE FROM qx_programacion_paquetes
		WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND paquete_insumos_id='".$_REQUEST['paquete']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->Reserva_Paquetes_Insumos_qx();
		return true;
	}

	function EliminarInsumoQXInsertado(){
		list($dbconn) = GetDBconn();
		$query="DELETE FROM qx_programacion_insumos
		WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND codigo_producto='".$_REQUEST['insumo']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->Reserva_Insumos_qx($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['codigoPro'],$_REQUEST['descripcionPro']);
		return true;
	}

	function BusquedaNuevoPaqueteInsumos(){
		$this->Reserva_Paquetes_Insumos_qx($_REQUEST['codigoPaquete'],$_REQUEST['Descripcion']);
		return true;
	}

	function ProcedimientosProgramPaquetes($ProgramacionId){
		list($dbconn) = GetDBconn();
		$query="SELECT procedimiento_qx FROM qx_procedimientos_programacion WHERE programacion_id='$ProgramacionId'
		EXCEPT
		SELECT a.procedimiento_qx
		FROM qx_procedimientos_programacion a,qx_cups_paquetes_insumos b,qx_programacion_paquetes c
		WHERE a.programacion_id='$ProgramacionId' AND a.procedimiento_qx=b.cargo AND b.paquete_insumos_id=c.paquete_insumos_id
		AND c.programacion_id=a.programacion_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				while (!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}

	function BuscarInsumosPaquete($paquetesId){
		list($dbconn) = GetDBconn();
		$query="SELECT  a.codigo_producto,a.cantidad,c.descripcion as insumo
		FROM qx_paquetes_contiene_insumos a,inventarios b,inventarios_productos c
		WHERE a.paquete_insumos_id='$paquetesId' AND a.empresa_id='".$_SESSION['LocalCirugias']['empresa']."' AND
		a.empresa_id=b.empresa_id AND a.codigo_producto=b.codigo_producto AND b.codigo_producto=c.codigo_producto";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				while (!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}

	function tiposViaBilaterales(){
	  list($dbconn) = GetDBconn();
		$query="SELECT via_acceso,descripcion FROM qx_vias_acceso WHERE sw_bilateral=1";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				while (!$result->EOF){
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}

	function InsertarInsumosQX(){
    if($_REQUEST['Salir']){
		  unset($_SESSION['ARREGLO']['INSUMOS']);
			unset($_SESSION['ARREGLO']['INSUMOSUNO']);
			$this->FormaProgramacionesQuirurgicas('',1);
			return true;
		}
		if($_REQUEST['GuardarCant']){
		  if(empty($_REQUEST['paso'])){
				$_REQUEST['paso']=1;
			}
		  unset($_SESSION['ARREGLO']['INSUMOS'][$_REQUEST['paso']]);
      unset($_SESSION['ARREGLO']['INSUMOSUNO']['CATIDAD'][$_REQUEST['paso']]);
      $productos=$_REQUEST['seleccion'];
			$cantidades=$_REQUEST['cantidadInsumos'];
			for($i=0;$i<sizeof($productos);$i++){
			  $codigo=$productos[$i];
				$cantidades[$codigo];
        $_SESSION['ARREGLO']['INSUMOS'][$_REQUEST['paso']][$productos[$i]]=1;
        $_SESSION['ARREGLO']['INSUMOSUNO']['CATIDAD'][$_REQUEST['paso']][$productos[$i]]=$cantidades[$codigo];
			}
		  $productos=$_SESSION['ARREGLO']['INSUMOS'];
			$cantidades=$_SESSION['ARREGLO']['INSUMOSUNO']['CATIDAD'];
		  foreach($productos as $x=>$vector){
        foreach($vector as $pdto=>$z){
				  foreach($cantidades as $a=>$vectorun){
					  foreach($vectorun as $pdtoun=>$cant){
              if($pdtoun==$pdto){
                list($dbconn) = GetDBconn();
								$nuevoValor=$this->CompruebaExisteCodigoInsumo($pdtoun,$cant);
								if($nuevoValor!=-1){
                  $query="UPDATE qx_programacion_insumos SET cantidad='$nuevoValor' WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND codigo_producto='$pdtoun'";
								}else{
									$query="INSERT INTO qx_programacion_insumos(programacion_id,codigo_producto,cantidad)
									VALUES('".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."','".$pdto."','".$cant."')";
								}
								$result = $dbconn->Execute($query);
								if($dbconn->ErrorNo() != 0){
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
								unset($_SESSION['ARREGLO']['INSUMOS']);
			          unset($_SESSION['ARREGLO']['INSUMOSUNO']);
							}
						}
					}
				}
			}
		}
		$this->Reserva_Insumos_qx($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],
		$_REQUEST['codigoPro'],$_REQUEST['descripcionPro']);
		return true;
	}

	function CompruebaExisteCodigoInsumo($pdtoun,$cant){
	  list($dbconn) = GetDBconn();
    $query = "SELECT cantidad FROM qx_programacion_insumos WHERE codigo_producto='$pdtoun' AND programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($toUpper=false);
				return $vars['cantidad']+$cant;
			}else{
        return -1;
			}
		}
	}

/**
* Funcion que retorna un arreglo de los quirofanos con los que cuenta la ips en el departamento en el que esta logueado el usuario
* @return array
*/
	function GruposProductos(){
		list($dbconn) = GetDBconn();
		$query = "SELECT grupo_id,descripcion FROM inv_grupos_inventarios ORDER BY descripcion";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
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
		$result->Close();
 		return $vars;
	}

/**
* Funcion que retorna un arreglo de los quirofanos con los que cuenta la ips en el departamento en el que esta logueado el usuario
* @return array
*/
	function ClaseProductos($grupo){
		list($dbconn) = GetDBconn();
		$query = "SELECT clase_id,descripcion FROM inv_clases_inventarios WHERE grupo_id='$grupo'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
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
		$result->Close();
 		return $vars;
	}

	/**
* Funcion que retorna un arreglo de los quirofanos con los que cuenta la ips en el departamento en el que esta logueado el usuario
* @return array
*/
	function SubClaseProductos($grupo,$clase){
		list($dbconn) = GetDBconn();
		$query = "SELECT subclase_id,descripcion FROM inv_subclases_inventarios WHERE grupo_id='$grupo' AND clase_id='$clase'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
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
		$result->Close();
 		return $vars;
	}

/**
* Funcion que consulta los productos y los atributos de los productos existentes en el inventario
* @return array;
* @param string empresa en la que el usuario esta trabajando;
*/
	function TotalInventarioProductosInv($grupo,$clasePr,$subclase,$codigoPro,$descripcionPro){

		list($dbconn) = GetDBconn();
		$queryBuqueda=$this->HallarQueryBusqueda($grupo,$clasePr,$subclase,$codigoPro,$descripcionPro);
    if(empty($_REQUEST['conteo']))
		{
				$query = "SELECT count(*) FROM
				inventarios_productos z
				$queryBuqueda";
				$result = $dbconn->Execute($query);
				if($result->EOF){
					$this->error = "Error al ejecutar la consulta.<br>";
					$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
					return false;
				}
				list($this->conteo)=$result->fetchRow();
     }
		 else
		 {
        $this->conteo=$_REQUEST['conteo'];

		 }

		 if(!$_REQUEST['Of'])
		 {
        $Of='0';
		 }
		 else
		 {
       $Of=$_REQUEST['Of'];
		 }
	  $query = "SELECT z.codigo_producto,z.descripcion FROM
		inventarios_productos z
		$queryBuqueda  ORDER BY z.descripcion LIMIT " . $this->limit . " OFFSET $Of" ;
		$result = $dbconn->Execute($query);
		if($result->EOF){
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}else{
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
			return $vars;
		}
	}

	function HallarQueryBusqueda($grupo,$clasePr,$subclase,$codigoPro,$descripcionPro){
    if($grupo || $clasePr || $subclase || $codigoPro || $descripcionPro){
      $query.=" WHERE";
		}
		if($grupo && $grupo!=-1){
      $query.=" z.grupo_id='$grupo'";
		}
		if($clasePr && $clasePr!=-1){
      $query.=" AND z.clase_id='$clasePr'";
		}
		if($subclase && $subclase!=-1){
      $query.=" AND z.subclase_id='$subclase'";
		}
		if($codigoPro){
      $query.=" AND z.codigo_producto LIKE '$codigoPro%'";
		}
		$descripcionPro=strtoupper($descripcionPro);
		if($descripcionPro){
      $query.=" AND z.descripcion LIKE '%$descripcionPro%'";
		}
		return $query;
	}

	function ConsultaFactor(){
		$pfj=$this->frmPrefijo;
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

	function ConsultaComponente($hcReservaSangreId){
		list($dbconn) = GetDBconn();
		$query = "SELECT b.hc_tipo_componente,b.componente,a.cantidad_componente
		FROM hc_solicitud_reserva_sangre_detalle a,hc_tipos_componentes b
		WHERE a.hc_reserva_sangre_id='$hcReservaSangreId' AND
		a.hc_tipo_componente_id=b.hc_tipo_componente";
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

	function ConsultaTransfuciones(){
		list($dbconn) = GetDBconn();
	  $query = "SELECT ingreso, reaccion_adversa FROM hc_control_transfusiones WHERE ingreso = ".$this->ingreso."";
	  $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$transf_ant[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
	  return $transf_ant;
  }

	function SacarDatosReservaSangre($tipoIdPac,$PacienteId){

		list($dbconn) = GetDBconn();
    $query="SELECT a.hc_reserva_sangre_id,a.sw_urgencia,a.preparacion,a.fecha_hora_reserva,
		a.cruzar,a.transfuciones_ant,a.reacciones_adv,a.descripcion_reac,a.embarazos_previos,
		a.fecha_ultimo_embarazo,a.motivo_reserva,a.grupo_sanguineo,a.rh,a.estado_gestacion,d.sexo_id
		FROM hc_solicitud_reserva_sangre a,hc_evoluciones b,ingresos c,pacientes d
		WHERE a.evolucion_id=b.evolucion_id AND b.ingreso=c.ingreso AND
		c.paciente_id='$PacienteId' AND c.tipo_id_paciente='$tipoIdPac' AND
		a.sw_estado='1' AND c.paciente_id=d.paciente_id AND c.tipo_id_paciente=d.tipo_id_paciente";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($ToUpper = false);
			}
		}
	  return $vars;
	}

	function RegresaReservaSangre(){
	  $this->FormaProgramacionesQuirurgicas('',1);
		return true;
	}

	function TiposConsentimientosQX(){
    list($dbconn) = GetDBconn();
    $query="SELECT qx_consentimiento_id,descripcion FROM qx_consentimientos_tipos";
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

	function GuardarTipoConsentimientoQX(){
    list($dbconn) = GetDBconn();
		if($_REQUEST['Cancelar']){
      $this->FormaProgramacionesQuirurgicas('',1);
			return true;
		}
		if($_REQUEST['TipoConsentimiento']==-1){
      $this->frmError["MensajeError"]="Seleccione el tipo de Consentimiento";
      $this->LlamaTipoConsentimiento_qx();
			return true;
		}
		list($dbconn) = GetDBconn();
	  if($_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']){
      $query="SELECT a.tipo_id_otroresponsable,a.otroresponsable_id,a.tipo_id_testigo1,
			a.testigo1_id,a.tipo_id_testigo2,a.testigo2_id,a.numero_radicacion,a.observaciones,
			a.qx_consentimiento_id,a.sw_consentimiento_recibido,b.nombre as nombreresponsable,
			b.tipo_parentesco_id as parentescoresponsable,c.nombre as nombretestigouno,
			c.tipo_parentesco_id as parentescotestigouno,d.nombre as nombretestigodos,
			d.tipo_parentesco_id as parentescotestigodos
			FROM qx_consentimientos_confirmaciones a
			LEFT JOIN qx_consentimientos_testigos b ON (a.programacion_id=b.programacion_id AND
			a.qx_consentimiento_id=b.qx_consentimiento_id AND
			b.tipo_id_testigo=a.tipo_id_otroresponsable AND b.testigo_id=a.otroresponsable_id)
			LEFT JOIN qx_consentimientos_testigos c ON (a.programacion_id=c.programacion_id AND
			a.qx_consentimiento_id=c.qx_consentimiento_id AND
			c.tipo_id_testigo=a.tipo_id_testigo1 AND c.testigo_id=a.testigo1_id)
			LEFT JOIN qx_consentimientos_testigos d ON (a.programacion_id=d.programacion_id AND
			a.qx_consentimiento_id=d.qx_consentimiento_id AND
			d.tipo_id_testigo=a.tipo_id_testigo2 AND d.testigo_id=a.testigo2_id)
			WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND
			a.qx_consentimiento_id='".$_REQUEST['TipoConsentimiento']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				$datos=$result->RecordCount();
				if($datos){
					$vars=$result->GetRowAssoc($toUpper=false);
				}
			}
			if($vars['tipo_id_otroresponsable'] && $vars['otroresponsable_id']){
        $responsable=2;
			}else{
        $responsable=1;
			}
			$result->Close();
		}
    $this->LlamaConsentimiento_qx($_REQUEST['TipoConsentimiento'],$vars['numero_radicacion'],$responsable,$vars['tipo_id_otroresponsable'],$vars['otroresponsable_id'],
		$vars['nombreresponsable'],$vars['parentescoresponsable'],$vars['tipo_id_testigo1'],$vars['tipo_id_testigo2'],$vars['testigo1_id'],
		$vars['testigo2_id'],$vars['nombretestigouno'],$vars['nombretestigodos'],$vars['parentescotestigouno'],$vars['parentescotestigodos'],$vars['observaciones'],
		$vars['sw_consentimiento_recibido']);
		return true;

	}

	function GuardarConfiramacionQX(){
    list($dbconn) = GetDBconn();
		if($_REQUEST['Salir']){
      $this->FormaProgramacionesQuirurgicas('',1);
			return true;
		}
		$_REQUEST['nombreResponsable']=strtoupper($_REQUEST['nombreResponsable']);
		$_REQUEST['nombreTestigoUno']=strtoupper($_REQUEST['nombreTestigoUno']);
		$_REQUEST['nombreTestigoDos']=strtoupper($_REQUEST['nombreTestigoDos']);
		$dbconn->BeginTrans();
		if($_REQUEST['recibeConsentimiento']){$recibeConsentimiento=1;}
		if($_REQUEST['responsable']==2){
		  if(!$_REQUEST['DocumentoResponsable'] || !$_REQUEST['nombreResponsable'] || $_REQUEST['parentescoResponsable']==-1){
        if(!$_REQUEST['DocumentoResponsable']){$this->frmError["DocumentoResponsable"]=1;}
				if(!$_REQUEST['nombreResponsable']){$this->frmError["nombreResponsable"]=1;}
				if($_REQUEST['parentescoResponsable']==-1){$this->frmError["parentescoResponsable"]=1;}
				$this->frmError["MensajeError"]="Todos los Datos del Responsable de la cirugia son Obligatorios";
				$this->LlamaConsentimiento_qx($_REQUEST['TipoConsentimiento'],$_REQUEST['numRadicacion'],$_REQUEST['responsable'],$_REQUEST['TipoDocumentoResponsable'],$_REQUEST['DocumentoResponsable'],
				$_REQUEST['nombreResponsable'],$_REQUEST['parentescoResponsable'],$_REQUEST['TipoDocumentoTestigoUno'],$_REQUEST['TipoDocumentoTestigoDos'],$_REQUEST['DocumentoTestigoUno'],
				$_REQUEST['DocumentoTestigoDos'],$_REQUEST['nombreTestigoUno'],$_REQUEST['nombreTestigoDos'],$_REQUEST['parentescoTestigoUno'],$_REQUEST['parentescoTestigoDos'],$_REQUEST['observaciones'],
				$recibeConsentimiento);
				return true;
			}
		}
		if($_REQUEST['DocumentoTestigoUno'] || $_REQUEST['nombreTestigoUno'] || ($_REQUEST['parentescoTestigoUno']!=-1)){
			if(!$_REQUEST['DocumentoTestigoUno'] || !$_REQUEST['nombreTestigoUno'] || $_REQUEST['parentescoTestigoUno']==-1){
			if(!$_REQUEST['DocumentoTestigoUno']){$this->frmError["DocumentoTestigoUno"]=1;}
			if(!$_REQUEST['nombreTestigoUno']){$this->frmError["nombreTestigoUno"]=1;}
			if($_REQUEST['parentescoTestigoUno']==-1){$this->frmError["parentescoTestigoUno"]=1;}
			$this->frmError["MensajeError"]="Para especificar el primer testigo debe Diligenciar todos sus datos";
			$this->LlamaConsentimiento_qx($_REQUEST['TipoConsentimiento'],$_REQUEST['numRadicacion'],$_REQUEST['responsable'],$_REQUEST['TipoDocumentoResponsable'],$_REQUEST['DocumentoResponsable'],
			$_REQUEST['nombreResponsable'],$_REQUEST['parentescoResponsable'],$_REQUEST['TipoDocumentoTestigoUno'],$_REQUEST['TipoDocumentoTestigoDos'],$_REQUEST['DocumentoTestigoUno'],
			$_REQUEST['DocumentoTestigoDos'],$_REQUEST['nombreTestigoUno'],$_REQUEST['nombreTestigoDos'],$_REQUEST['parentescoTestigoUno'],$_REQUEST['parentescoTestigoDos'],$_REQUEST['observaciones'],
			$recibeConsentimiento);
			return true;
		  }
		}
		if($_REQUEST['DocumentoTestigoDos'] || $_REQUEST['nombreTestigoDos'] || ($_REQUEST['parentescoTestigoDos']!=-1)){
			if(!$_REQUEST['DocumentoTestigoDos'] || !$_REQUEST['nombreTestigoDos'] || $_REQUEST['parentescoTestigoDos']==-1){
			if(!$_REQUEST['DocumentoTestigoDos']){$this->frmError["DocumentoTestigoDos"]=1;}
			if(!$_REQUEST['nombreTestigoDos']){$this->frmError["nombreTestigoDos"]=1;}
			if($_REQUEST['parentescoTestigoDos']==-1){$this->frmError["parentescoTestigoDos"]=1;}
			$this->frmError["MensajeError"]="Si tiene Segundo Testigo diligencie todos los datos";
			$this->LlamaConsentimiento_qx($_REQUEST['TipoConsentimiento'],$_REQUEST['numRadicacion'],$_REQUEST['responsable'],$_REQUEST['TipoDocumentoResponsable'],$_REQUEST['DocumentoResponsable'],
			$_REQUEST['nombreResponsable'],$_REQUEST['parentescoResponsable'],$_REQUEST['TipoDocumentoTestigoUno'],$_REQUEST['TipoDocumentoTestigoDos'],$_REQUEST['DocumentoTestigoUno'],
			$_REQUEST['DocumentoTestigoDos'],$_REQUEST['nombreTestigoUno'],$_REQUEST['nombreTestigoDos'],$_REQUEST['parentescoTestigoUno'],$_REQUEST['parentescoTestigoDos'],$_REQUEST['observaciones'],
			$recibeConsentimiento);
			return true;
			}
		}
		if($_REQUEST['TipoDocumentoResponsable']==-1){
      $TipoDocumentoResponsable1='NULL';
		}else{
      $TipoDocumentoResponsable=$_REQUEST['TipoDocumentoResponsable'];
      $TipoDocumentoResponsable1="'$TipoDocumentoResponsable'";
		}
		if($_REQUEST['TipoDocumentoTestigoUno']==-1){
      $TipoDocumentoTestigoUno1='NULL';
		}else{
      $TipoDocumentoTestigoUno=$_REQUEST['TipoDocumentoTestigoUno'];
      $TipoDocumentoTestigoUno1="'$TipoDocumentoTestigoUno'";
		}
		if($_REQUEST['TipoDocumentoTestigoDos']==-1){
      $TipoDocumentoTestigoDos1='NULL';
		}else{
      $TipoDocumentoTestigoDos=$_REQUEST['TipoDocumentoTestigoDos'];
      $TipoDocumentoTestigoDos1="'$TipoDocumentoTestigoDos'";
		}
		if(!$_REQUEST['numRadicacion']){
      $_REQUEST['numRadicacion']=0;
		}
		$confirma=$this->ConfirmaExisteTipoConsentimiento($_REQUEST['TipoConsentimiento']);
		if($confirma){
		 $query="UPDATE qx_consentimientos_confirmaciones
			                SET 	tipo_id_otroresponsable=$TipoDocumentoResponsable1,otroresponsable_id='".$_REQUEST['DocumentoResponsable']."',
														tipo_id_testigo1=$TipoDocumentoTestigoUno1,testigo1_id='".$_REQUEST['DocumentoTestigoUno']."',
														tipo_id_testigo2=$TipoDocumentoTestigoDos1,testigo2_id='".$_REQUEST['DocumentoTestigoDos']."',
														numero_radicacion='".$_REQUEST['numRadicacion']."',observaciones='".$_REQUEST['observaciones']."',
														sw_consentimiento_recibido='$recibeConsentimiento'
											WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND
											qx_consentimiento_id='".$_REQUEST['TipoConsentimiento']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar hc_tipos_sanguineos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			$query="DELETE FROM qx_consentimientos_testigos WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND
			qx_consentimiento_id='".$_REQUEST['TipoConsentimiento']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar qx_consentimientos_testigos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}else{
			$query="INSERT INTO qx_consentimientos_confirmaciones(programacion_id,
																													tipo_id_otroresponsable,otroresponsable_id,
																													tipo_id_testigo1,testigo1_id,
																													tipo_id_testigo2,testigo2_id,
																													numero_radicacion,observaciones,
																													qx_consentimiento_id,sw_consentimiento_recibido,
																													usuario_id,fecha_registro)VALUES('".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."',
																													$TipoDocumentoResponsable1,'".$_REQUEST['DocumentoResponsable']."',
																													$TipoDocumentoTestigoUno1,'".$_REQUEST['DocumentoTestigoUno']."',
																													$TipoDocumentoTestigoDos1,'".$_REQUEST['DocumentoTestigoDos']."',
																													'".$_REQUEST['numRadicacion']."','".$_REQUEST['observaciones']."',
																													'".$_REQUEST['TipoConsentimiento']."','$recibeConsentimiento',
																													'".UserGetUID()."','".date("Y-m-d H:i:s")."')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar qx_consentimientos_confirmaciones";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		if($_REQUEST['responsable']==2){
			$query="INSERT INTO qx_consentimientos_testigos(programacion_id,tipo_id_testigo,
																										testigo_id,nombre,
																										tipo_parentesco_id,qx_consentimiento_id)
																										VALUES('".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."',$TipoDocumentoResponsable1,
																										'".$_REQUEST['DocumentoResponsable']."','".$_REQUEST['nombreResponsable']."',
																										'".$_REQUEST['parentescoResponsable']."','".$_REQUEST['TipoConsentimiento']."')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar qx_consentimientos_testigos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		if($_REQUEST['DocumentoTestigoUno'] || $_REQUEST['nombreTestigoUno'] || ($_REQUEST['parentescoTestigoUno']!=-1)){
			if(!$_REQUEST['DocumentoTestigoUno'] || !$_REQUEST['nombreTestigoUno'] || $_REQUEST['parentescoTestigoUno']==-1){
			if(!$_REQUEST['DocumentoTestigoUno']){$this->frmError["DocumentoTestigoUno"]=1;}
			if(!$_REQUEST['nombreTestigoUno']){$this->frmError["nombreTestigoUno"]=1;}
			if($_REQUEST['parentescoTestigoUno']==-1){$this->frmError["parentescoTestigoUno"]=1;}
			$this->frmError["MensajeError"]="Si tiene un primer Testigo diligencie todos los datos";
			$this->LlamaConsentimiento_qx($_REQUEST['TipoConsentimiento'],$_REQUEST['numRadicacion'],$_REQUEST['responsable'],$_REQUEST['TipoDocumentoResponsable'],$_REQUEST['DocumentoResponsable'],
			$_REQUEST['nombreResponsable'],$_REQUEST['parentescoResponsable'],$_REQUEST['TipoDocumentoTestigoUno'],$_REQUEST['TipoDocumentoTestigoDos'],$_REQUEST['DocumentoTestigoUno'],
			$_REQUEST['DocumentoTestigoDos'],$_REQUEST['nombreTestigoUno'],$_REQUEST['nombreTestigoDos'],$_REQUEST['parentescoTestigoUno'],$_REQUEST['parentescoTestigoDos'],$_REQUEST['observaciones'],
			$recibeConsentimiento);
			return true;
		  }
			$query="INSERT INTO qx_consentimientos_testigos(programacion_id,tipo_id_testigo,
																										testigo_id,nombre,
																										tipo_parentesco_id,qx_consentimiento_id)
																										VALUES('".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."',$TipoDocumentoTestigoUno1,
																										'".$_REQUEST['DocumentoTestigoUno']."','".$_REQUEST['nombreTestigoUno']."',
																										'".$_REQUEST['parentescoTestigoUno']."','".$_REQUEST['TipoConsentimiento']."')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar qx_consentimientos_testigos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		if($_REQUEST['DocumentoTestigoDos'] || $_REQUEST['nombreTestigoDos'] || ($_REQUEST['parentescoTestigoDos']!=-1)){
			if(!$_REQUEST['DocumentoTestigoDos'] || !$_REQUEST['nombreTestigoDos'] || $_REQUEST['parentescoTestigoDos']==-1){
			if(!$_REQUEST['DocumentoTestigoDos']){$this->frmError["DocumentoTestigoDos"]=1;}
			if(!$_REQUEST['nombreTestigoDos']){$this->frmError["nombreTestigoDos"]=1;}
			if($_REQUEST['parentescoTestigoDos']==-1){$this->frmError["parentescoTestigoDos"]=1;}
			$this->frmError["MensajeError"]="Si tiene Segundo Testigo diligencie todos los datos";
			$this->LlamaConsentimiento_qx($_REQUEST['TipoConsentimiento'],$_REQUEST['numRadicacion'],$_REQUEST['responsable'],$_REQUEST['TipoDocumentoResponsable'],$_REQUEST['DocumentoResponsable'],
			$_REQUEST['nombreResponsable'],$_REQUEST['parentescoResponsable'],$_REQUEST['TipoDocumentoTestigoUno'],$_REQUEST['TipoDocumentoTestigoDos'],$_REQUEST['DocumentoTestigoUno'],
			$_REQUEST['DocumentoTestigoDos'],$_REQUEST['nombreTestigoUno'],$_REQUEST['nombreTestigoDos'],$_REQUEST['parentescoTestigoUno'],$_REQUEST['parentescoTestigoDos'],$_REQUEST['observaciones'],
			$recibeConsentimiento);
			return true;
			}
			$query="INSERT INTO qx_consentimientos_testigos(programacion_id,tipo_id_testigo,
																										testigo_id,nombre,
																										tipo_parentesco_id,qx_consentimiento_id)
																										VALUES('".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."',$TipoDocumentoTestigoDos1,
																										'".$_REQUEST['DocumentoTestigoDos']."','".$_REQUEST['nombreTestigoDos']."',
																										'".$_REQUEST['parentescoTestigoDos']."','".$_REQUEST['TipoConsentimiento']."')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar qx_consentimientos_testigos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		$dbconn->CommitTrans();
		$this->LlamaConsentimiento_qx($_REQUEST['TipoConsentimiento'],$_REQUEST['numRadicacion'],$_REQUEST['responsable'],$_REQUEST['TipoDocumentoResponsable'],$_REQUEST['DocumentoResponsable'],
		$_REQUEST['nombreResponsable'],$_REQUEST['parentescoResponsable'],$_REQUEST['TipoDocumentoTestigoUno'],$_REQUEST['TipoDocumentoTestigoDos'],$_REQUEST['DocumentoTestigoUno'],
		$_REQUEST['DocumentoTestigoDos'],$_REQUEST['nombreTestigoUno'],$_REQUEST['nombreTestigoDos'],$_REQUEST['parentescoTestigoUno'],$_REQUEST['parentescoTestigoDos'],$_REQUEST['observaciones'],
		$recibeConsentimiento);
		return true;
	}

	function ConfirmaExisteTipoConsentimiento($TipoConsentimiento){
    list($dbconn) = GetDBconn();
		$query="SELECT * FROM qx_consentimientos_confirmaciones WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND qx_consentimiento_id='".$TipoConsentimiento."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar qx_consentimientos_confirmaciones";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
        return 1;
			}else{
        return 0;
			}
		}
	}

	function ConsentimientosdelPacienteQX(){
    list($dbconn) = GetDBconn();
		$query="SELECT b.descripcion as consentimiento,a.tipo_id_otroresponsable,a.otroresponsable_id,
		c.nombre,d.descripcion as parentesco
		FROM qx_consentimientos_confirmaciones a
		LEFT JOIN qx_consentimientos_testigos c ON(a.programacion_id=c.programacion_id AND a.tipo_id_otroresponsable=c.tipo_id_testigo AND a.otroresponsable_id=c.testigo_id)
    LEFT JOIN tipos_parentescos d ON(c.tipo_parentesco_id=d.tipo_parentesco_id),
		qx_consentimientos_tipos b
		WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND
		a.qx_consentimiento_id=b.qx_consentimiento_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar qx_consentimientos_confirmaciones";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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

	function EstacionesEnferemeria(){
    list($dbconn) = GetDBconn();
		$query="SELECT estacion_id,descripcion FROM estaciones_enfermeria";
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

	function GuardarSeleccionEstacionE(){
	  if($_REQUEST['Cancelar']){
      $this->FormaProgramacionesQuirurgicas('',1);
			return true;
		}
	  if($_REQUEST['estacionEnfermeria']==-1){
      $this->frmError["MensajeError"]="Seleccione la Estacion de Enfermeria";
      $this->SeleccionCamaEstacion();
			return true;
		}
    $this->SeleccionCamaEstacion($_REQUEST['estacionEnfermeria'],$_REQUEST['FechaProgramFin']);
		return true;
	}

	function SeleccionPiezasEstacion($estacionEnfermeria){
    list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT b.pieza,b.descripcion as nombrepieza,b.ubicacion as ubicacionpieza
		FROM piezas b,camas c
		WHERE b.estacion_id='$estacionEnfermeria' AND b.pieza=c.pieza";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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

	function SeleccionCamasEstacion($pieza){
    list($dbconn) = GetDBconn();
		$query="SELECT a.cama,a.descripcion as nombrecama,a.ubicacion as ubicacioncama,b.qx_reserva_id as indicador
		FROM camas a
		LEFT JOIN qx_reserva_cama b ON(b.programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND
		a.cama=b.cama)
		WHERE a.pieza='$pieza'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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

	function GuardarSeleccionCamaQX(){
    list($dbconn) = GetDBconn();
		if($_REQUEST['Cancelar']){
      $this->FormaProgramacionesQuirurgicas('',1);
			return true;
		}
		$infoCadena = explode ('/', $_REQUEST['fechaReserv']);
		$dia=$infoCadena[0];
		$mes=$infoCadena[1];
		$ano=$infoCadena[2];
		$Fecha=$ano.'-'.$mes.'-'.$dia;
		if(!$_REQUEST['fechaReserv']){
		  $this->frmError["MensajeError"]="Introduzca la fecha de la reserva";
      $this->SeleccionCamaEstacion($_REQUEST['estacionEnfermeria'],$Fecha);
		  return true;
		}
		$arreglo=$_REQUEST['seleccion'];
		if($arreglo){
		  $query="DELETE FROM qx_reserva_cama WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar hc_tipos_sanguineos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			for($i=0;$i<sizeof($arreglo);$i++){
				$query="INSERT INTO qx_reserva_cama(fecha_reserva,cama,usuario_id,
				fecha_registro,programacion_id)VALUES
				('".$Fecha."','".$arreglo[$i]."','".UserGetUID()."','".date("Y-m-d H:i:s")."','".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al consultar hc_tipos_sanguineos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
		}
		$this->SeleccionCamaEstacion($_REQUEST['estacionEnfermeria'],$Fecha);
		return true;
	}

	function nombreEstacionEnf($estacionEnfermeria){
    list($dbconn) = GetDBconn();
		$query="SELECT descripcion FROM estaciones_enfermeria WHERE estacion_id='$estacionEnfermeria'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($ToUpper = false);
			}
	    return $vars;
		}
	}

	function ReservasCamasQX(){
    list($dbconn) = GetDBconn();
		$query="SELECT a.fecha_reserva,a.cama,b.descripcion as nombrecama,b.ubicacion as ubicacioncama,
		c.descripcion as nombrepieza,d.descripcion as estacion
		FROM qx_reserva_cama a,camas b,piezas c,estaciones_enfermeria d
		WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."' AND
		a.cama=b.cama AND b.pieza=c.pieza AND c.estacion_id=d.estacion_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
	    return $vars;
		}
	}

	/**
* Funcion que consulta el cirujano principal de una programacion
* @return array
* @param integer codigo unico que identifica la programacion
*/
	function BuscarCirujanoPrincipalQX(){
    list($dbconn) = GetDBconn();

		$query = "SELECT tipo_id_cirujano,cirujano_id,plan_id
		FROM qx_programaciones
		WHERE programacion_id='".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
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
    list($dbconn) = GetDBconn();
    if($_REQUEST['Salir']){
		  $action=ModuloGetURL('app','Quirurgicos','user','PedirDatosPaciente');
      $this->FormaBuscarPacientePresupuesto($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$action,$_REQUEST['Responsable'],1,$_REQUEST['cirujano']);
			return true;
		}
		$this->FormaBuscadorDiagnostico($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cirujano'],$_REQUEST['codigo'],$_REQUEST['cargo']);
		return true;
	}

	function SeleccionarDiagnosticoPQX(){
    $action=ModuloGetURL('app','Quirurgicos','user','PedirDatosPaciente');
		$this->FormaBuscarPacientePresupuesto($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$action,$_REQUEST['Responsable'],1,$_REQUEST['cirujano'],$_REQUEST['diagnostico'],$_REQUEST['nombrediagnostico']);
		return true;
	}

	function DatosdelaReservaSangre($tipoIdPac,$PacienteId){
    list($dbconn) = GetDBconn();
    $query="SELECT a.solicitud_reserva_sangre_id,a.transfuciones_ant,
		b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
		b.fecha_nacimiento,d.tipo_componente_id,d.cantidad_componente,e.componente,c.grupo_sanguineo,c.rh,date(a.fecha_hora_reserva) as fecha,d.sw_estado,e.sw_cruze,
		ll.bolsa_id,l.cruze_sanguineo_id,l.ingreso_bolsa_id
		FROM banco_sangre_reserva a
		LEFT JOIN banco_sangre_cruzes_sanguineos l ON(l.solicitud_reserva_sangre_id=a.solicitud_reserva_sangre_id AND l.estado='1')
		LEFT JOIN banco_sangre_bolsas ll ON(l.ingreso_bolsa_id=ll.ingreso_bolsa_id)
		,pacientes b
		LEFT JOIN pacientes_grupo_sanguineo c ON(b.tipo_id_paciente=c.tipo_id_paciente AND b.paciente_id=c.paciente_id AND c.estado='1'),
		banco_sangre_reserva_detalle d,hc_tipos_componentes e
		WHERE a.paciente_id='$PacienteId' AND a.tipo_id_paciente='$tipoIdPac'
		AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND
		a.solicitud_reserva_sangre_id=d.solicitud_reserva_sangre_id AND
		d.tipo_componente_id=e.hc_tipo_componente AND a.sw_estado='1' ORDER BY a.solicitud_reserva_sangre_id";
  $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[$result->fields[0]][$result->fields[4]][$result->fields[14]]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}
		return $vars;
	}

	function LlamaReserva_Sangre_qxRegreso(){
	  $this->FormaProgramacionesQuirurgicas('',1);
		return true;
	}

  function ConsultaInsumosPaquetes(){
    $this->FormaConsultaInsumosPaquetes($_REQUEST['paqueteId'],$_REQUEST['NomPaquete'],$_REQUEST['codigoPaquete'],$_REQUEST['Descripcion']);
    return true;
  }
  
  
  function ComprobarOpcionesProcedimientosCups(){
    list($dbconn) = GetDBconn();
    $query="SELECT a.valor
    FROM system_modulos_variables a
    WHERE a.modulo='Quirurgicos' AND 
    a.modulo_tipo='app' AND 
    a.variable='cups_opciones_procedimientos'";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->fields[0]==1){
        return 1;
      }else{
        return 0;
      }
    }
  }
  
  function BuscarOpcionesProcedimientos($cups){ 
      list($dbconn) = GetDBconn();         
      $query = "SELECT a.procedimiento_opcion,a.descripcion
      FROM qx_cups_opciones_procedimientos a
      WHERE a.cargo='$cups' ORDER BY a.descripcion";
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
	
	function GetReporteCirugia($fecha_ini,$fecha_fin,$sala,$cirujano,$anestesiologo)
	{
		if(!empty($fecha_ini) AND !empty($fecha_fin))
		{
			/*if($fecha_fin > date("Y-m-d"))
			{
				$this->frmError["MensajeError"]="LA FECHA FINAL NO PUEDE SER MAYOR A LA ACTUAL";
				$this->FiltroCirugiaReporte();
				return false;
			}*/
			
			/*if($fecha_ini > date("Y-m-d"))
			{
				$this->frmError["MensajeError"]="LA FECHA INICIAL NO PUEDE SER MAYOR A LA ACTUAL";
     			$this->FiltroCirugiaReporte();
				return false;
			}*/
			
			if($fecha_ini > $fecha_fin)
			{
				$this->frmError["MensajeError"]="LA FECHA INICIA NO PUEDE SER MAYOR A LA FECHA FINAL";
				$this->FiltroCirugiaReporte();
				return false;
			}
			
			$datos.="AND date(a.hora_inicio)>='$fecha_ini'
				    AND date(a.hora_fin)<='$fecha_fin'";
		}
		
		if(!empty($sala))
		{
			$datos.="AND d.quirofano='$sala'";
		}
		
		$cir=explode("__",$cirujano);
		if(!empty($cir[0]))
		{
			$datos.="AND h.tipo_id_tercero='".$cir[0]."'
				    AND h.tercero_id='".$cir[1]."'";
		}
		$anes=explode("__",$anestesiologo);
		if(!empty($anes[0]))
		{
			$datos.="AND m.tipo_id_tercero='".$anes[0]."'
				    AND m.tercero_id='".$anes[1]."'";
		}
		
		list($dbconn) = GetDBconn();
	
		/*$query="SELECT 
                    a.hora_inicio,
                    a.hora_fin,
                    TO_CHAR(a.hora_inicio,'HH:MI') as hora_i,
                    TO_CHAR(a.hora_fin,'HH:MI') as hora_f,
                    d.descripcion as sala,
                    b.programacion_id,
                    c.primer_nombre ||' '|| c.segundo_nombre ||' '|| c.primer_apellido ||' '|| c.segundo_apellido as nombre_completo,
                    e.observaciones,
                    f.plan_descripcion,
                    c.fecha_nacimiento,
                    k.descripcion as desc_ambito_cirugia,
                    g.descripcion as descripcion_cups,
                    c.residencia_telefono,
                    b.estado,
                    h.nombre,
                    h1.nombre as nombre_anes,
                    h2.nombre as nombre_ayud
     			FROM 	qx_quirofanos_programacion a,
                    qx_programaciones b
                    LEFT JOIN qx_anestesiologo_programacion m
                    ON
                    (
                         b.programacion_id=m.programacion_id	
                    )
                    LEFT JOIN profesionales as h ON 
                    (
                         b.tipo_id_cirujano=h.tipo_id_tercero 
                         AND b.cirujano_id=h.tercero_id
                    )
                    LEFT JOIN profesionales as h1 ON 
                    (
                         m.tipo_id_tercero=h1.tipo_id_tercero 
                         AND m.tercero_id=h1.tercero_id
                    )
                    LEFT JOIN profesionales as h2 ON 
                    (
                         m.tipo_id_ayudante=h2.tipo_id_tercero 
                         AND m.ayudante_id=h2.tercero_id
                    )
                    LEFT JOIN qx_datos_procedimientos_cirugias as j
                    ON
                    ( 
                         b.programacion_id=j.programacion_id 
                    )
                    LEFT JOIN qx_ambitos_cirugias as k
                    ON
                    ( 
                         j.ambito_cirugia=k.ambito_cirugia_id
                    )
                    ,
                    pacientes c,
                    qx_quirofanos d,
                    qx_procedimientos_programacion e,
                    planes f,
                    cups g,
                    departamentos i
                    WHERE a.programacion_id=b.programacion_id
                    AND b.tipo_id_paciente=c.tipo_id_paciente
                    AND b.paciente_id=c.paciente_id
                    AND a.quirofano_id=d.quirofano
                    AND a.programacion_id=e.programacion_id
                    AND b.plan_id=f.plan_id
                    AND e.procedimiento_qx=g.cargo
                    AND b.estado !='0'
                    AND i.departamento=b.departamento
                    AND b.departamento='".$_SESSION['LocalCirugias']['departamento']."'
                    $datos
                    ORDER BY d.descripcion";
*/
		
			$query="SELECT 
                              a.hora_inicio,
                              a.hora_fin,
                              TO_CHAR(a.hora_inicio,'HH:MI') as hora_i,
                              TO_CHAR(a.hora_fin,'HH:MI') as hora_f,
                              d.descripcion as sala,
                              b.programacion_id,
                              c.tipo_id_paciente ||' '|| c.paciente_id as identificacion_paciente, 
                              c.primer_nombre ||' '|| c.segundo_nombre ||' '|| c.primer_apellido ||' '|| c.segundo_apellido as nombre_completo,
                              e.observaciones,
                              f.plan_descripcion,
                              c.fecha_nacimiento,
                              ' ' as desc_ambito_cirugia,
                              g.descripcion as descripcion_cups,
                              c.residencia_telefono,
                              b.estado,
                              h.nombre,
                              h1.nombre as nombre_anes,
                              h2.nombre as nombre_ayud
                       FROM 	qx_quirofanos_programacion a
                              JOIN qx_programaciones b
                              ON
                              (
                                   a.programacion_id=b.programacion_id
                              )
                              LEFT JOIN qx_anestesiologo_programacion m
                              ON
                              (
                                   b.programacion_id=m.programacion_id	
                              )
                              LEFT JOIN profesionales as h ON 
                              (
                                   b.tipo_id_cirujano=h.tipo_id_tercero 
                                   AND b.cirujano_id=h.tercero_id
                              )
                              LEFT JOIN profesionales as h1 ON 
                              (
                                   m.tipo_id_tercero=h1.tipo_id_tercero 
                                   AND m.tercero_id=h1.tercero_id
                              )
                              LEFT JOIN profesionales as h2 ON 
                              (
                                   m.tipo_id_ayudante=h2.tipo_id_tercero 
                                   AND m.ayudante_id=h2.tercero_id
                              )
                              JOIN pacientes c
                              ON
                              (
                                   b.tipo_id_paciente=c.tipo_id_paciente
                                   AND b.paciente_id=c.paciente_id
                              )
                              JOIN qx_quirofanos d
                              ON
                              (
                                   a.quirofano_id=d.quirofano
                              )
                              JOIN qx_procedimientos_programacion e
                              ON
                              (
                                   a.programacion_id=e.programacion_id
                              )
                              JOIN planes f
                              ON
                              (
                                   b.plan_id=f.plan_id
                              )
                              JOIN cups g
                              ON
                              (
                                   e.procedimiento_qx=g.cargo
                              )
                              JOIN departamentos i
                              ON
                              (
                                   i.departamento=b.departamento
                              )
                         WHERE b.estado !='0'
                         AND a.qx_tipo_reserva_quirofano_id != '0'
                         AND b.departamento='".$_SESSION['LocalCirugias']['departamento']."'
                         $datos
                         ORDER BY d.descripcion, a.hora_inicio ASC, hora_i ASC";	
					
					
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Quirurgicos - GetReporteCirugia";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
    }
		else
		{
			if($result->RecordCount()>0)
			{
				while(!$result->EOF)
				{
					$vars[$result->fields[4]][$result->fields[5]][]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}    	
		return $vars;
	}
	
	
	function GetSalas()
	{
		list($dbconn) = GetDBconn();
	
		$query="SELECT quirofano,descripcion
						FROM qx_quirofanos
						WHERE estado='1';";
		
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Quirurgicos - GetSalas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
    }
		else
		{
			if($result->RecordCount()>0)
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
	
	function TiposIdTerceros()
	{
		list($dbconn) = GetDBconn();
		
		$query = "SELECT tipo_id_tercero,descripcion,indice_de_orden 
							FROM tipo_id_terceros 
							ORDER BY indice_de_orden";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Quirurgicos - TiposIdTerceros";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
    }
		else
		{
			if($result->RecordCount()>0)
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
/**
   * Busca si el numero de identificacion del paciente es  numerico  o alfa numerico 
    * @access public
    * @return array
    */
        function Consulta_tipo_dato($empresa_id,$centro_utilidad)
        {
                  $sql="SELECT sw_alfanumerico  FROM pacientes_alfanumerico
                                    WHERE empresa_id='".$empresa_id."' and centro_utilidad='".$centro_utilidad."'  ";
                  $cxn = new ConexionBD();
    
                    $datos = array();
                    if(!$rst = $cxn->ConexionBaseDatos($sql))
                    return false;

                    while(!$rst->EOF)
                    {
                    $datos= $rst->GetRowAssoc($ToUpper = false);
                    $rst->MoveNext();
                    }
                    $rst->Close();
                    return $datos ;
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