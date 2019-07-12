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
class app_QXEjecucion_user extends classModulo
{
	function app_QXEjecucion_user()
	{
	  $this->limit=GetLimitBrowser();
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
* Funcion que consulta en la base de datos los permisos del usuario para trabajar en los departamentos
* @return array
*/
	function LogueoCirugias(){
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
			while ($data = $result->FetchRow()) {
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
* Funcion que trae los datos del lugar donde esta logueado el usuario
* @return boolean
*/
	function consultaLogueo(){
	  $_SESSION['LocalCirugias']['Empresa']=$_REQUEST['datos_query']['empresa_id'];
		$_SESSION['LocalCirugias']['NombreEmp']=$_REQUEST['datos_query']['descripcion1'];
		$_SESSION['LocalCirugias']['CentroUtili']=$_REQUEST['datos_query']['centro_utilidad'];
		$_SESSION['LocalCirugias']['NombreCU']=$_REQUEST['datos_query']['descripcion2'];
		$_SESSION['LocalCirugias']['Departamento']=$_REQUEST['datos_query']['departamento'];
		$_SESSION['LocalCirugias']['NombreDpto']=$_REQUEST['datos_query']['descripcion3'];
		if(!$this->MenuQXEjecucion()){
      return false;
    }
		return true;
	}

	function LlamaEjecucionQX(){
    $this->BusquedaPacienteCumplimiento();
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
		if($dbconn->ErrorNo() != 0) {
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
* Funcion que busca en la base de datos el nombre del tercero o la ips que tiene ese numero de plan
* @return string
* @param integer numero del plan del convenio de la clinica con la ips
*/
	function Responsable($Responsable)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT (SELECT b.nombre_tercero FROM terceros b WHERE b.tercero_id=a.tercero_id AND b.tipo_id_tercero=a.tipo_tercero_id) as nombre_tercero
		FROM planes a
		WHERE a.plan_id='$Responsable'";
		$result = $dbconn->Execute($query);
		$NomTercero=$result->fields[0];
		return $NomTercero;
	}
/**
* Funcion que busca en la base de datos el nombre del plan o convenio con la ips
* @return string
* @param integer numero del plan del convenio de la clinica con la ips
*/
	function PlanNombre($Responsable)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT plan_descripcion FROM planes WHERE plan_id='$Responsable'";
		$result = $dbconn->Execute($query);
		$NomPlan=$result->fields[0];
		return $NomPlan;
	}

	function tiposAfiliadoRango($Responsable){
    list($dbconn) = GetDBconn();
		$query = "SELECT DISTINCT b.tipo_afiliado_nombre,a.tipo_afiliado_id FROM planes_rangos a,tipos_afiliado b WHERE a.plan_id='$Responsable' AND a.tipo_afiliado_id=b.tipo_afiliado_id ORDER BY a.tipo_afiliado_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF){
					$var[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $var;
	}

	function tiposRangosAfil($Responsable){
		list($dbconn) = GetDBconn();
		$query = "SELECT DISTINCT a.rango FROM planes_rangos a WHERE a.plan_id='$Responsable' ORDER BY a.rango";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
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

	function GuardaSeleccionTipoAfil(){
    if($_REQUEST['Regresar']){
      $this->MenuQXEjecucion();
			return true;
		}
    if($_REQUEST['Responsable']==-1){
		  $this->frmError["MensajeError"]="Debe seleccionar un Plan";
      $this->BusquedaPacienteCumplimiento();
			return true;
		}
		if($_REQUEST['TipoDocumento']!=-1){$_REQUEST['TipoDocumento']=$_REQUEST['TipoDocumento'];}else{$_REQUEST['TipoDocumento']='';}
    if($_REQUEST['tipoAfil']!=-1){$_REQUEST['tipoAfil']=$_REQUEST['tipoAfil'];}else{$_REQUEST['tipoAfil']='';}
		if($_REQUEST['rango']!=-1){$_REQUEST['rango']=$_REQUEST['rango'];}else{$_REQUEST['rango']='';}
		$_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['SW']=1;
		$_SESSION['PRESUPUESTO_CIRUGIA']['AYUDANTE']=1;
		$_SESSION['PRESUPUESTO_CIRUGIA']['ANESTESIOLOGO']=1;
		$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas']);
		return true;
	}

/**
* Funcion que busca en los profesionales especialistas existentes en la base de datos
* @return array
*/
	function profesionalesEspecialista(){

    $departamento=$_SESSION['LocalCirugias']['Departamento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT  x.tercero_id,z.nombre_tercero as nombre,x.tipo_id_tercero
		FROM profesionales x,profesionales_departamentos y,terceros z,
		profesionales_especialidades a,especialidades b
		WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND
		x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id
		AND y.departamento='".$_SESSION['LocalCirugias']['Departamento']."'
		AND x.tercero_id=z.tercero_id AND x.tipo_id_tercero=z.tipo_id_tercero
		AND profesional_activo(z.tipo_id_tercero,z.tercero_id,'".$_SESSION['LocalCirugias']['Departamento']."')='1'
		AND x.tercero_id=a.tercero_id AND x.tipo_id_tercero=a.tipo_id_tercero AND
		a.especialidad=b.especialidad AND b.sw_cirujano=1";
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

	function DatosPrincipalesPresupuesto(){
		if($_REQUEST['perfusionista']){
			$perfusionista=1;
		}else{
			$perfusionista=0;
		}
		$cadenaTipoA=explode('/',$_REQUEST['TipoAnestesia']);
		if($_REQUEST['ambitoCirugia']==-1 || (!$_REQUEST['Horaduracionquiro'] && !$_REQUEST['Minduracionquiro'])){
			if($_REQUEST['ambitoCirugia']==-1){$this->frmError["ambitoCirugia"]=1;}
			if(!$_REQUEST['Horaduracionquiro'] && !$_REQUEST['Minduracionquiro']){$this->frmError["duracionquiro"]=1;}
			$this->frmError["MensajeError"]="Faltan Datos Obligatorios";
			$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],
			$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],
			$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista);
			return true;
		}
		if($cadenaTipoA[1]==1){
			if($_REQUEST['gasAnestesico']==-1 || $_REQUEST['gasAnestesicoMe']==-1 || !$_REQUEST['DuracionGas']){
				if($_REQUEST['gasAnestesico']==-1){$this->frmError["gasAnestesico"]=1;}
				if($_REQUEST['gasAnestesicoMe']==-1){$this->frmError["gasAnestesicoMe"]=1;}
				if(!$_REQUEST['DuracionGas']){$this->frmError["DuracionGas"]=1;}
				$this->frmError["MensajeError"]="Faltan Datos Obligatorios";
				$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],
				$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],
				$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$erfusionista);
				return true;
			}
		}
		if(empty($_REQUEST['Documento'])){$Documento='';}else{$Documento=$_REQUEST['Documento'];}
		if($_REQUEST['TipoDocumento']==-1){$TipoDocumento1='NULL';}else{$TipoDocumento=$_REQUEST['TipoDocumento'];$TipoDocumento1="'$TipoDocumento'";}
		if(empty($_REQUEST['nombrePac'])){$nombrePac='';}else{$nombrePac=$_REQUEST['nombrePac'];}
		if($_REQUEST['tipoAfil']==-1){$tipoAfil1='NULL';}else{$tipoAfil=$_REQUEST['tipoAfil'];$tipoAfil1="'$tipoAfil'";}
		if($_REQUEST['rango']==-1){$rango1='NULL';}else{$rango=$_REQUEST['rango'];$rango1="'$rango'";}
		if($_REQUEST['ambitoCirugia']==-1){$ambitoCirugia1='NULL';}else{$ambitoCirugia=$_REQUEST['ambitoCirugia'];$ambitoCirugia1="'$ambitoCirugia'";}
		if($cadenaTipoA[0]==-1){$TipoAnestesia1='NULL';}else{$TipoAnestesia=$cadenaTipoA[0];$TipoAnestesia1="'$TipoAnestesia'";}
		if($_REQUEST['gasAnestesico']==-1 || !$_REQUEST['gasAnestesico']){$gasAnestesico1='NULL';}else{$gasAnestesico=$_REQUEST['gasAnestesico'];$gasAnestesico1="'$gasAnestesico'";}
		if($_REQUEST['gasAnestesicoMe']==-1 || !$_REQUEST['gasAnestesicoMe']){$gasAnestesicoMe1='NULL';}else{$gasAnestesicoMe=$_REQUEST['gasAnestesicoMe'];$gasAnestesicoMe1="'$gasAnestesicoMe'";}
		if(!$_REQUEST['Horaduracionquiro']){
			$_REQUEST['Horaduracionquiro']='00';
		}
		if(!$_REQUEST['Minduracionquiro']){
			$_REQUEST['Minduracionquiro']='00';
		}
		list($dbconn) = GetDBconn();
		$duracionQuirofano=$_REQUEST['Horaduracionquiro'].':'.$_REQUEST['Minduracionquiro'];
		if(!$_SESSION['Acto']['Presupuesto']){
		  $query="SELECT nextval('qx_acto_qx_acto_id_seq')";
			$result=$dbconn->Execute($query);
			$acto=$result->fields[0];
			$query="SELECT nextval('qx_presupuesto_acto_qx_presupuesto_acto_id_seq')";
			$result=$dbconn->Execute($query);
			$presupuestoId=$result->fields[0];
			$_SESSION['Acto']['Presupuesto']=$presupuestoId;
			$query = "INSERT INTO qx_presupuesto_acto (qx_presupuesto_acto_id,paciente_id,tipo_id_paciente,
																					nombre_paciente,plan_id,tipo_afiliado_id,rango,
																					ambito_cirugia_id,qx_tipo_anestesia_id,gas_anestesico,
																					gas_medicinal,tiempo_suministro_gas,tiempo_quirofano,semanas_cotizadas,
																					sw_perfusionista,fecha_registro,usuario_id)
																					VALUES('".$_SESSION['Acto']['Presupuesto']."',
																					'$Documento',$TipoDocumento1,
																					'$nombrePac','".$_REQUEST['Responsable']."',
																					$tipoAfil1,$rango1,
																					$ambitoCirugia1,$TipoAnestesia1,$gasAnestesico1,$gasAnestesicoMe1,
																					'".$_REQUEST['DuracionGas']."','$duracionQuirofano','".$_REQUEST['semanas']."',
																					'$perfusionista','".date("Y-m-d H:i:s")."',
																					'".UserGetUID()."')";
		}else{
			$query = "UPDATE qx_presupuesto_acto SET ambito_cirugia_id=$ambitoCirugia1,
																					qx_tipo_anestesia_id=$TipoAnestesia1,
																					gas_anestesico=$gasAnestesico1,
																					gas_medicinal=$gasAnestesicoMe1,
																					tiempo_suministro_gas='".$_REQUEST['DuracionGas']."',
																					tiempo_quirofano='$duracionQuirofano',
																					semanas_cotizadas='".$_REQUEST['semanas']."',
																					sw_perfusionista='$perfusionista' WHERE qx_presupuesto_acto_id='".$_SESSION['Acto']['Presupuesto']."'";
		}
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],
		$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],
		$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista);
		return true;
	}

	function LlamaAdicionCirujanoActo(){
		$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas']);
		return true;
	}

	function CirujanosPresupuesto(){
		if($_REQUEST['perfusionista']){
			$perfusionista=1;
		}else{
			$perfusionista=0;
		}
		if(!$_SESSION['Acto']['Presupuesto']){
			$this->frmError["MensajeError"]="Inserte Primero los Datos Principales de Acto";
			$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],
			$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],
			$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista,1);
			return true;
		}
		if($_REQUEST['adicionarCir']){
			if($_REQUEST['cirujanoPro']==-1){
				$this->frmError["cirujanos"]=1;
				$this->frmError["MensajeError"]="Faltan Datos Obligatorios";
				$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],
				$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],
				$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista,1);
				return true;
			}
			$comprueba=$this->CompruebaExisteCirujano($_REQUEST['cirujanoPro']);
			if($comprueba){
				$this->frmError["MensajeError"]="Este Cirujano ya fue Insertado";
				$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],
				$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],
				$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista,1);
				return true;
			}
			list($dbconn) = GetDBconn();
			$cadena=explode('/',$_REQUEST['cirujanoPro']);
			$cirujano=$cadena[0];
			$tipocirujano=$cadena[1];
			$query = "INSERT INTO qx_presupuesto_acto_cirujanos (qx_presupuesto_acto_id,tipo_id_cirujano,cirujano_id)
			VALUES('".$_SESSION['Acto']['Presupuesto']."','$tipocirujano','$cirujano')";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],
			$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],
			$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista);
			return true;
		}
		$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],
		$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],
		$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista);
		return true;
	}

	function CompruebaExisteCirujano($cirujanoPro){

		list($dbconn) = GetDBconn();
		$cadena=explode('/',$cirujanoPro);
		$cirujano=$cadena[0];
		$tipocirujano=$cadena[1];
		$query="SELECT *
		FROM qx_presupuesto_acto_cirujanos
		WHERE  qx_presupuesto_acto_id='".$_SESSION['Acto']['Presupuesto']."' AND tipo_id_cirujano='$tipocirujano' AND cirujano_id='$cirujano'";
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

	function consultaCirujanosPresupuesto(){
		list($dbconn) = GetDBconn();
		$query="SELECT b.nombre,a.tipo_id_cirujano,a.cirujano_id
		FROM qx_presupuesto_acto_cirujanos a,profesionales b
		WHERE a.qx_presupuesto_acto_id='".$_SESSION['Acto']['Presupuesto']."' AND
		a.tipo_id_cirujano=b.tipo_id_tercero AND a.cirujano_id=b.tercero_id";
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

	function LlamaAdicionProcedimientoActo(){
	if($_REQUEST['perfusionista']){
			$perfusionista=1;
		}else{
			$perfusionista=0;
		}
		$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],
		$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista,
		'',1,$_REQUEST['tipoIdCirPro'],$_REQUEST['cirujanoPro']);
		return true;
	}

	function InserProcedimientosCirujanosPresupuesto(){
		if($_REQUEST['perfusionista']){
			$perfusionista=1;
		}else{
			$perfusionista=0;
		}
		list($dbconn) = GetDBconn();
		if($_REQUEST['insertarprocedimiento']){
			if(!$_REQUEST['codigos'] || !$_REQUEST['procedimiento']){
				$this->frmError["procedimiento"]=1;
				$this->frmError["MensajeError"]="Seleccione el Procedimiento";
				$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],
				$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],
				$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista,'',1,$_REQUEST['tipoIdCirPro'],
				$_REQUEST['cirujanoPro']);
				return true;
			}
			$comprueba=$this->RepeticionProcedimientoActo($_REQUEST['codigos']);
			if($comprueba){
				$this->frmError["MensajeError"]="Este Procedimiento ya ha sido Insertado";
				$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],
				$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],
				$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista,'',1,$_REQUEST['tipoIdCirPro'],
				$_REQUEST['cirujanoPro']);
				return true;
			}
			if($_REQUEST['IndicaBilateral']){
				if($_REQUEST['viabilateral']==-1){
				$this->frmError["viabilateral"]=1;
					$this->frmError["MensajeError"]="Seleccione la via del Procedimiento";
					$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],
					$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],
					$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista,'',1,$_REQUEST['tipoIdCirPro'],
					$_REQUEST['cirujanoPro'],1,$_REQUEST['codigos'],$_REQUEST['procedimiento']);
					return true;
				}
			}else{
				$bilateral=$this->HallarProcedimientoBilateral($_REQUEST['codigos']);
				if($bilateral){
					$this->frmError["MensajeError"]="El procedimiento esta identificado como Posible Bilateral, identifique la via";
					$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],
					$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista,$_REQUEST['adicionCir'],1,
					$_REQUEST['tipoIdCirPro'],$_REQUEST['cirujanoPro'],1,$_REQUEST['codigos'],$_REQUEST['procedimiento']);
					return true;
				}
			}
			if($_REQUEST['viabilateral']==-1 || !$_REQUEST['viabilateral']){
				$viabilateral1='NULL';
			}else{
				$viabilateral=$_REQUEST['viabilateral'];
				$viabilateral1="'$viabilateral'";
			}
			$query="INSERT INTO qx_presupuesto_acto_procedimientos (qx_presupuesto_acto_id,
																															tipo_id_cirujano,
																															cirujano_id,
																															procedimiento_qx,
																															procedimiento_via)
																															VALUES('".$_SESSION['Acto']['Presupuesto']."',
																															'".$_REQUEST['tipoIdCirPro']."',
																															'".$_REQUEST['cirujanoPro']."',
																															'".$_REQUEST['codigos']."',
																															$viabilateral1)";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$result->Close();
			$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],
			$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista,$_REQUEST['adicionCir'],$_REQUEST['adicionPro'],
			$_REQUEST['tipoIdCirPro'],$_REQUEST['cirujanoPro']);
			return true;
		}
		$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],
		$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista);
		return true;
	}

	function RepeticionProcedimientoActo($procedimiento){
		list($dbconn) = GetDBconn();
		$query="SELECT *
		FROM qx_presupuesto_acto_procedimientos
		WHERE qx_presupuesto_acto_id='".$_SESSION['Acto']['Presupuesto']."' AND
		procedimiento_qx='$procedimiento'";
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
		return 0;
	}

	function ProcedimientosCirujanoPresupuesto($TipoIdCirujano,$Cirujano){
		list($dbconn) = GetDBconn();
		$query="SELECT a.procedimiento_qx,b.descripcion as nom_procedimiento,a.procedimiento_via,
		c.descripcion as viaacceso
		FROM qx_presupuesto_acto_procedimientos a
		LEFT JOIN qx_vias_acceso c ON(c.via_acceso=a.procedimiento_via),
		cups b
		WHERE a.tipo_id_cirujano='$TipoIdCirujano' AND a.cirujano_id='$Cirujano' AND
		a.qx_presupuesto_acto_id='".$_SESSION['Acto']['Presupuesto']."' AND
		b.cargo=a.procedimiento_qx";
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
        $bilateral=$result->fields[0];
				if($bilateral==1){
					return 1;
				}else{
					return 0;
				}
			}
		}
		return 0;
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

	function EliminarProcPresupuesto(){
		if($_REQUEST['perfusionista']){
			$perfusionista=1;
		}else{
			$perfusionista=0;
		}
		list($dbconn) = GetDBconn();
		$query="DELETE FROM qx_presupuesto_acto_procedimientos
		WHERE qx_presupuesto_acto_id='".$_SESSION['Acto']['Presupuesto']."' AND tipo_id_cirujano='".$_REQUEST['tipoIdCirPro']."' AND
		cirujano_id='".$_REQUEST['cirujanoPro']."' AND procedimiento_qx='".$_REQUEST['procedimiento']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],
		$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista);
		return true;
	}

	function EliminarCirPresupuesto(){
		if($_REQUEST['perfusionista']){
			$perfusionista=1;
		}else{
			$perfusionista=0;
		}
		list($dbconn) = GetDBconn();
		$query="DELETE FROM qx_presupuesto_acto_cirujanos
		WHERE qx_presupuesto_acto_id='".$_SESSION['Acto']['Presupuesto']."' AND tipo_id_cirujano='".$_REQUEST['tipoIdCirPro']."' AND
		cirujano_id='".$_REQUEST['cirujanoPro']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['nombretipoafil'],$_REQUEST['semanas'],
		$_REQUEST['ambitoCirugia'],$_REQUEST['TipoAnestesia'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],$_REQUEST['DuracionGas'],$_REQUEST['Horaduracionquiro'],$_REQUEST['Minduracionquiro'],$perfusionista);
		return true;
	}
	
	function LiquidacionPresupuesto(){
		if($_REQUEST['Salir']){
		  unset($_SESSION['Acto']['Presupuesto']);
			$this->LlamaEjecucionQX();
			return true;
		}
		list($dbconn) = GetDBconn();
		$query="SELECT plan_id,tipo_afiliado_id,rango,semanas_cotizadas,ambito_cirugia_id,qx_tipo_anestesia_id,
		gas_anestesico,gas_medicinal,tiempo_suministro_gas,tiempo_quirofano,sw_perfusionista
		FROM qx_presupuesto_acto
		WHERE qx_presupuesto_acto_id='".$_SESSION['Acto']['Presupuesto']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){				
				$varsPrin=$result->GetRowAssoc($toUpper=false);			
			}
		}		
		$query="SELECT a.tipo_id_cirujano,a.cirujano_id,a.procedimiento_qx,a.procedimiento_via,d.descripcion as via,b.plan_id,c.descripcion  
		FROM qx_presupuesto_acto_procedimientos a 
		LEFT JOIN qx_vias_acceso d ON(a.procedimiento_via=d.via_acceso),
		qx_presupuesto_acto b,cups c
		WHERE b.qx_presupuesto_acto_id='".$_SESSION['Acto']['Presupuesto']."' AND a.qx_presupuesto_acto_id=b.qx_presupuesto_acto_id AND 
		a.procedimiento_qx=c.cargo";
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
		$this->SeleccionCargosEquivalentes($vars,$varsPrin);
		return true;		
	}
	
	function HallarNombreTarifario($tarifario){
    list($dbconn) = GetDBconn();
		$query="SELECT descripcion FROM tarifarios WHERE tarifario_id='$tarifario'";
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
		$result->Close();
		return $vars;
	}
	
	function LiquidarProcPresupuesto(){
		if($_REQUEST['Cancelar']){
			unset($_SESSION['Acto']['Presupuesto']);
			$this->LlamaEjecucionQX();
			return true;
		}
		$arregloLiqui=$_REQUEST['arregloLiqui'];
		$z=0;
		for($i=0;$i<sizeof($arregloLiqui);$i++){
			$valores=explode('|/',$arregloLiqui[$i]);
			$cargos[$z]['tipo_id_cirujano']=$valores[0];
			$cargos[$z]['cirujano_id']=$valores[1];
			$cargos[$z]['cargo']=$valores[2];
			$cargos[$z]['tarifario']=$valores[3];
			$cargos[$z]['tipo_liquidacion_qx']=$valores[4];		
			$z++;
		}			
		$SeleccionTarif=$_REQUEST['SeleccionTarif'];
		for($i=0;$i<sizeof($SeleccionTarif);$i++){
			$valores=explode('|/',$SeleccionTarif[$i]);
			$cargos[$z]['tipo_id_cirujano']=$valores[0];
			$cargos[$z]['cirujano_id']=$valores[1];
			$cargos[$z]['cargo']=$valores[2];
			$cargos[$z]['tarifario']=$valores[3];
			$cargos[$z]['tipo_liquidacion_qx']=$valores[4];		
			$z++;
		}
		unset($_SESSION['Acto']['Presupuesto']);
		$this->LlamaEjecucionQX();
		return true;
		print_R($cargos);
		echo '<BR>';
		print_R($_REQUEST['datosPresupuesto']);
		return true;		
	}

	function BuscarPacienteCumplimiento(){
    list($dbconn) = GetDBconn();
    $query="SELECT a.programacion_id,a.tipo_id_paciente,a.paciente_id,
		e.primer_nombre||' '||e.segundo_nombre||' '||e.primer_apellido||' '||e.segundo_apellido as nombrepac,
		a.tipo_id_cirujano,a.cirujano_id,c.nombre,b.quirofano_id,d.descripcion as quirofano
		FROM qx_programaciones a
		LEFT JOIN pacientes e ON(a.tipo_id_paciente=e.tipo_id_paciente AND a.paciente_id=e.paciente_id)
		LEFT JOIN profesionales c ON(a.tipo_id_cirujano=c.tipo_id_tercero AND a.cirujano_id=c.tercero_id),
		qx_quirofanos_programacion b
		LEFT JOIN qx_quirofanos d ON(b.quirofano_id=d.quirofano)
		WHERE a.programacion_id=b.programacion_id AND '".date("Y-m-d")."'=date(b.hora_inicio) AND
		a.estado='1' AND b.qx_tipo_reserva_quirofano_id='3'";
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
		return $this->ProgramacionesDelDiaQX($vars);
	}

	function AdmitirProgramacionQX(){
    if($_REQUEST['programacion']){
      $_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']=$_REQUEST['programacion'];
		}
    $mensaje='Paciente Admitido Para la Cirugia de click en Aceptar';
		$titulo='ADMISIONES CIRUGIAS';
		$accion=ModuloGetURL('app','QXEjecucion','user','LlamaCumplimientoCirugia',array("tipoIdPaciente"=>$_REQUEST['tipoIdPaciente'],"PacienteId"=>$_REQUEST['PacienteId']));
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		return true;
	}

	function LlamaCumplimientoCirugia(){
		$this->CumplimientoCirugia($_REQUEST['tipoIdPaciente'],$_REQUEST['PacienteId'],$Responsable='56',$cuenta='3795');
		return true;
	}

/**
* Function que consulta los datos principales de una programacion
* @return array
*/
	function SacaDatosPacienteProgramQX($ProgramacionId,$TipoId,$Documento){
	  list($dbconn) = GetDBconn();
    $query="SELECT x.tipo_id_cirujano,x.cirujano_id,x.tipo_id_paciente,x.paciente_id,x.plan_id,y.diagnostico_nombre,x.diagnostico_id
		FROM qx_programaciones x
		LEFT JOIN diagnosticos y on (y.diagnostico_id=x.diagnostico_id)
		WHERE x.programacion_id='$ProgramacionId' AND x.tipo_id_paciente='$TipoId'
		AND x.paciente_id='$Documento' AND x.estado='1'";
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

	function SacaDatosPacienteCumplimiento(){
    list($dbconn) = GetDBconn();
    $query="SELECT x.tipo_id_cirujano,x.cirujano_id,x.tipo_id_paciente,x.paciente_id,x.plan_id,
		y.diagnostico_nombre,x.diagnostico_id
		FROM qx_cumplimientos x
		LEFT JOIN diagnosticos y on (y.diagnostico_id=x.diagnostico_id)
		WHERE x.qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."'";
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
		$result->Close();
 		return $vars;
	}

	function SeleccionCumplimiento(){
    if($_REQUEST['Salir']){
		  unset($_SESSION['CIRUGIAS']['ACTO']);
			unset($_SESSION['CIRUGIAS']['CUMPLIMIENTO']);
      $this->BuscarPacienteCumplimiento();
			return true;
		}
    if($_REQUEST['seleccionPac']){
      $this->FormaDatosPrincipales($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
			return true;
		}elseif($_REQUEST['Selprofesionales']){
      $this->SeleccionProfesionalesPx($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
			return true;
		}elseif($_REQUEST['paquetes_insumos']){
      $this->Reserva_Paquetes_Insumos_qx($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
			return true;
		}elseif($_REQUEST['insumos']){
      $this->Reserva_Insumos_qx($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
			return true;
		}elseif($_REQUEST['procedimientosSelec']){
      $this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
			return true;
		}elseif($_REQUEST['sangre']){
      $this->LlamaReserva_Sangre_qx($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
			return true;
		}elseif($_REQUEST['reservar']){
      $this->ReserveEquiposQuirofanos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
			return true;
		}
	}

	function GuardarDatosCumplimiento(){
	  if($_REQUEST['cancelar']){
      $this->CumplimientoCirugia($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
			return true;
		}
	  if($_REQUEST['cirujano']==-1){
			$tipoIdCirujano1='NULL';
			$cirujanoId1='NULL';
		}else{
			$cadena=explode('/',$_REQUEST['cirujano']);
			$tipoIdCirujano=$cadena[1];
			$cirujanoId=$cadena[0];
			$tipoIdCirujano1="'$tipoIdCirujano'";
			$cirujanoId1="'$cirujanoId'";
		}
    list($dbconn) = GetDBconn();
		if($_SESSION['CIRUGIAS']['ACTO']['CODIGO']){
			$query="UPDATE qx_cumplimientos SET tipo_id_cirujano=$tipoIdCirujano1,cirujano_id=$cirujanoId1,diagnostico_id='".$_REQUEST['codigo']."'
			WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}else{
			$query="SELECT nextval('qx_cumplimientos_qx_cumplimiento_id_seq')";
			$result=$dbconn->Execute($query);
			$NoCumplimiento=$result->fields[0];
			if($_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){
        $programacion=$_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO'];
        $programacion1="'$programacion'";
				$query="UPDATE qx_programaciones SET estado='2' WHERE programacion_id='".$_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}else{
        $programacion1='NULL';
			}
			$query="INSERT INTO qx_cumplimientos(qx_cumplimiento_id,departamento,tipo_id_cirujano,
			cirujano_id,tipo_id_paciente,paciente_id,plan_id,estado,usuario_id,fecha_registro,
			diagnostico_id,numerodecuenta,programacion_id)VALUES('$NoCumplimiento','".$_SESSION['LocalCirugias']['Departamento']."',
			$tipoIdCirujano1,$cirujanoId1,'".$_REQUEST['TipoId']."','".$_REQUEST['Documento']."',
			'".$_REQUEST['Responsable']."','1','".UserGetUID()."','".date("Y-m-d H:i:s")."','".$_REQUEST['codigo']."','".$_REQUEST['cuenta']."',
			$programacion1)";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$_SESSION['CIRUGIAS']['ACTO']['CODIGO']=$NoCumplimiento;
		}
		$this->CumplimientoCirugia($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
		return true;
	}

/**
* Funcion que consulta en la B.D. los datos del anestesiologo de una programacion si ya existe
* @return array
* @param integer numero que identifica la programacion
*/
	function obtenerDatosAnestesiologoQX($ProgramacionId){

		list($dbconn) = GetDBconn();
		$query="SELECT a.tipo_id_tercero,a.tercero_id,a.tipo_id_instrumentista,a.instrumentista_id,a.tipo_id_circulante,a.circulante_id,
		e.hora,f.fecha_turno,f.profesional_id,f.tipo_id_profesional,f.consultorio_id
		FROM qx_anestesiologo_programacion a,qx_programaciones b
		LEFT JOIN agenda_citas_asignadas c on (b.tipo_id_paciente=c.tipo_id_paciente AND b.paciente_id=c.paciente_id AND c.sw_atencion='0')
		LEFT JOIN tipos_cita d on (c.tipo_cita=d.tipo_cita AND d.sw_anestesiologia='1')
		LEFT JOIN agenda_citas e on (c.agenda_cita_id=e.agenda_cita_id)
		LEFT JOIN agenda_turnos f on (e.agenda_turno_id=f.agenda_turno_id)
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
* Funcion que consulta en la B.D. los datos del anestesiologo de una programacion si ya existe
* @return array
* @param integer numero que identifica la programacion
*/
	function DatosAnestesiologoCumplimiento(){

		list($dbconn) = GetDBconn();
		$query="SELECT a.tipo_id_anestesiologo,a.anestesiologo_id,a.tipo_id_instrumentista,a.instrumentista_id,a.tipo_id_circulante,a.circulante_id,
		e.hora,f.fecha_turno,f.profesional_id,f.tipo_id_profesional,f.consultorio_id
		FROM qx_cumplimiento_profesionales a,qx_cumplimientos b
		LEFT JOIN agenda_citas_asignadas c on (b.tipo_id_paciente=c.tipo_id_paciente AND b.paciente_id=c.paciente_id AND c.sw_atencion='0')
		LEFT JOIN tipos_cita d on (c.tipo_cita=d.tipo_cita AND d.sw_anestesiologia='1')
		LEFT JOIN agenda_citas e on (c.agenda_cita_id=e.agenda_cita_id)
		LEFT JOIN agenda_turnos f on (e.agenda_turno_id=f.agenda_turno_id)
		WHERE a.qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND a.qx_cumplimiento_id=b.qx_cumplimiento_id";
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
* Funcion que busca en los profesionales especialistas anestesiologos existentes en la base de datos
* @return array
*/
	function profesionalesEspecialistaCiculantes(){
    $departamento=$_SESSION['LocalCirugias']['Departamento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT  x.tercero_id,x.nombre,x.tipo_id_tercero
		FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c
		WHERE x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id AND y.departamento='$departamento' AND
		z.especialidad=l.especialidad AND z.sw_circulante='1' AND x.tercero_id=l.tercero_id AND
		x.tipo_id_tercero=l.tipo_id_tercero  AND x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero AND
		profesional_activo(c.tipo_id_tercero,c.tercero_id,'$departamento')='1'";
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
    $departamento=$_SESSION['LocalCirugias']['Departamento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT  x.tercero_id,x.nombre,x.tipo_id_tercero FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c WHERE x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id AND y.departamento='$departamento' AND z.especialidad=l.especialidad AND z.sw_instrumentista='1' AND x.tercero_id=l.tercero_id AND x.tipo_id_tercero=l.tipo_id_tercero  AND x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero AND profesional_activo(c.tipo_id_tercero,c.tercero_id,'$departamento')='1'";
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
	function profesionalesEspecialistaAnestecistas(){
    $departamento=$_SESSION['LocalCirugias']['Departamento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT  x.tercero_id,x.nombre,x.tipo_id_tercero
		FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c
		WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id AND y.departamento='$departamento' AND z.especialidad=l.especialidad AND z.sw_anestesiologo='1' AND x.tercero_id=l.tercero_id AND x.tipo_id_tercero=l.tipo_id_tercero  AND x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero AND profesional_activo(c.tipo_id_tercero,c.tercero_id,'$departamento')='1'";
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

	function ValidacionProfesionalesQx(){
    if($_REQUEST['Cancelar']){
		  $this->CumplimientoCirugia($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
		  return true;
		}
		if($_REQUEST['instrumentista']==-1){
      $tipoIdinstrumentista1='NULL';
		  $instrumentistaId1='NULL';
		}else{
			$cadena=explode('/',$_REQUEST['instrumentista']);
			$tipoIdinstrumentista=$cadena[1];
			$instrumentistaId=$cadena[0];
			$tipoIdinstrumentista1="'$tipoIdinstrumentista'";
			$instrumentistaId1="'$instrumentistaId'";
		}
		if($_REQUEST['anestesista']==-1){
      $tipoIdanestesista1='NULL';
			$anestesistaId1='NULL';
		}else{
			$cadena=explode('/',$_REQUEST['anestesista']);
			$tipoIdanestesista=$cadena[1];
			$anestesistaId=$cadena[0];
			$tipoIdanestesista1="'$tipoIdanestesista'";
			$anestesistaId1="'$anestesistaId'";
		}
		if($_REQUEST['circulante']==-1){
		  $tipoIdcirculante1='NULL';
			$circulanteId1='NULL';
		}else{
			$cadena=explode('/',$_REQUEST['circulante']);
			$tipoIdcirculante=$cadena[1];
			$circulanteId=$cadena[0];
			$tipoIdcirculante1="'$tipoIdcirculante'";
			$circulanteId1="'$circulanteId'";
		}
		list($dbconn) = GetDBconn();
    if($_SESSION['CIRUGIAS']['ACTO']['PROFESIONAL']){
      $query="UPDATE qx_cumplimiento_profesionales SET tipo_id_anestesiologo=$tipoIdanestesista1,anestesiologo_id=$anestesistaId1,
			tipo_id_instrumentista=$tipoIdinstrumentista1,instrumentista_id=$instrumentistaId1,tipo_id_circulante=$tipoIdcirculante1,circulante_id=$circulanteId1
			WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}else{
      $query="INSERT INTO qx_cumplimiento_profesionales(qx_cumplimiento_id,
			tipo_id_anestesiologo,anestesiologo_id,tipo_id_instrumentista,
			instrumentista_id,tipo_id_circulante,circulante_id)VALUES('".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."',
			$tipoIdanestesista1,$anestesistaId1,$tipoIdinstrumentista1,$instrumentistaId1,
			$tipoIdcirculante1,$circulanteId1)";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
      $_SESSION['CIRUGIAS']['ACTO']['PROFESIONALES']=1;
		}
		$this->CumplimientoCirugia($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
		return true;
	}

	function paquetesInsertadosRequeridos(){
		list($dbconn) = GetDBconn();
		$query = "SELECT a.paquete_insumos_id,a.cantidad,b.descripcion
		FROM qx_programacion_paquetes a,qx_paquetes_insumos b
		WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']."' AND
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

	function paquetesCumplimientoRequeridos(){

		list($dbconn) = GetDBconn();
		$query = "SELECT a.paquete_insumos_id,a.cantidad,b.descripcion
		FROM qx_cumplimiento_paquetes a,qx_paquetes_insumos b
		WHERE a.qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND
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

	function InsertarReqPaqutes(){
    if($_REQUEST['Cancelar']){
		  $this->CumplimientoCirugia($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
		  return true;
		}
		list($dbconn) = GetDBconn();
		if($_REQUEST['buscar']){
      $this->Reserva_Paquetes_Insumos_qx($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta'],1,$_REQUEST['nombuscar']);
			return true;
		}
		if($_REQUEST['GuardarCant'] || $_REQUEST['GuardarDos']){
		  $paquetes=$_REQUEST['paquetes'];
			$cantidades=$_REQUEST['cantidadpaq'];
			if($_REQUEST['GuardarDos']){
        $cantidades=$_REQUEST['cantidad_paqDos'];
				$paquetes=$_REQUEST['seleccionDos'];
			}
      for($i=0;$i<sizeof($paquetes);$i++){
        $codigo=$paquetes[$i];
				$query = "SELECT *,cantidad
				FROM qx_cumplimiento_paquetes
				WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND
				paquete_insumos_id='".$codigo."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}else{
					$datos=$result->RecordCount();
					if($datos){
						$vars=$result->GetRowAssoc($toUpper=false);
						$valorTot=$cantidades[$codigo] + $vars['cantidad'];
						$query = "UPDATE qx_cumplimiento_paquetes SET cantidad='".$valorTot."'
						WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND
						paquete_insumos_id='".$codigo."'";
					}else{
						$query="INSERT INTO qx_cumplimiento_paquetes(qx_cumplimiento_id,paquete_insumos_id,cantidad)
						VALUES('".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."','$codigo','".$cantidades[$codigo]."')";
					}
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
				}
				$_SESSION['CIRUGIAS']['ACTO']['PAQUETES']=1;
			}
			$this->Reserva_Paquetes_Insumos_qx($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
			return true;
		}
	}

	function BusquedaNuevoPaqueteInsumos(){
		$this->Reserva_Paquetes_Insumos_qx($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta'],1);
		return true;
	}

	function buscarPaquetesNuevos($cadena){

		list($dbconn) = GetDBconn();
		$cadena=strtoupper($cadena);
		$query="SELECT paquete_insumos_id,descripcion FROM qx_paquetes_insumos WHERE descripcion LIKE '%$cadena%'";
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
		$query="SELECT  a.cantidad,c.descripcion as insumo
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

	function EliminarPauqeteInsertado(){
		list($dbconn) = GetDBconn();
		$query="DELETE FROM qx_cumplimiento_paquetes
		WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND paquete_insumos_id='".$_REQUEST['paquete']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->Reserva_Paquetes_Insumos_qx($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable']);
		return true;
	}

	function InsumosInsertadosRequeridos(){
		list($dbconn) = GetDBconn();
		$query = "SELECT a.codigo_producto,a.cantidad,b.descripcion
		FROM qx_programacion_insumos a,inventarios_productos b
		WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']."' AND
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

	function InsumosInsertadosCumplimiento(){
		list($dbconn) = GetDBconn();
		$query = "SELECT a.codigo_producto,a.cantidad,b.descripcion
		FROM qx_cumplimiento_insumos a,inventarios_productos b
		WHERE a.qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND
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

	function InsertarInsumosQX(){
	  if($_REQUEST['Salir']){
		  $this->CumplimientoCirugia($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
		  return true;
		}
    list($dbconn) = GetDBconn();
		if($_REQUEST['GuardarCant'] || $_REQUEST['GuardarCantUno']){
		  $insumos=$_REQUEST['insumos'];
			$cantidades=$_REQUEST['cantidadInsumos'];
			if($_REQUEST['GuardarCantUno']){
        $insumos=$_REQUEST['seleccion'];
			  $cantidades=$_REQUEST['cantidadInsumosUno'];
			}
      for($i=0;$i<sizeof($insumos);$i++){
        $codigo=$insumos[$i];
				$query = "SELECT *,cantidad
				FROM qx_cumplimiento_insumos
				WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND
				codigo_producto='".$codigo."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}else{
					$datos=$result->RecordCount();
					if($datos){
						$vars=$result->GetRowAssoc($toUpper=false);
						$valorTot=$cantidades[$codigo] + $vars['cantidad'];
						$query = "UPDATE qx_cumplimiento_insumos SET cantidad='".$valorTot."'
						WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND
						codigo_producto='".$codigo."'";
					}else{
            $query="INSERT INTO qx_cumplimiento_insumos(qx_cumplimiento_id,codigo_producto,cantidad)
						VALUES('".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."','$codigo','".$cantidades[$codigo]."')";
					}
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
				  $_SESSION['CIRUGIAS']['ACTO']['INSUMOS']=1;
				}
			}
		}
		$this->Reserva_Insumos_qx($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta'],$_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['codigoPro'],$_REQUEST['descripcionPro'],1);
		return true;
	}

	function EliminarInsumoQXInsertado(){
		list($dbconn) = GetDBconn();
		$query="DELETE FROM qx_cumplimiento_insumos
		WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND codigo_producto='".$_REQUEST['insumo']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->Reserva_Insumos_qx($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta'],$_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['codigoPro'],$_REQUEST['descripcionPro']);
		return true;
	}

	function BusquedaNuevoInsumos(){
    $this->Reserva_Insumos_qx($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta'],$_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['codigoPro'],$_REQUEST['descripcionPro'],1);
		return true;
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
    }else{
        $this->conteo=$_REQUEST['conteo'];
		 }
		 if(!$_REQUEST['Of']){
        $Of='0';
		 }else{
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

	function LlamaReserva_Insumos_qx(){
    $this->Reserva_Insumos_qx($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta'],$_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['codigoPro'],$_REQUEST['descripcionPro'],1);
		return true;
	}

	function ReservasCamasQX(){
    list($dbconn) = GetDBconn();
		$query="SELECT a.fecha_reserva,a.cama,b.descripcion as nombrecama,b.ubicacion as ubicacioncama,
		c.descripcion as nombrepieza,d.descripcion as estacion
		FROM qx_reserva_cama a,camas b,piezas c,estaciones_enfermeria d
		WHERE a.programacion_id='".$_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']."' AND
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
* Funcion que consulta de la base de datos los procedimientos de una programacion
* @return array
* @param integer numero unico que identifica la programacion
*/
	function BusquedaProcedimientosProgram($ProgramacionId){

		list($dbconn) = GetDBconn();
		$query = "SELECT x.procedimiento_qx,x.tipo_id_cirujano,x.cirujano_id,x.tipo_id_ayudante,x.ayudante_id,x.plan_id,y.numero_orden_id,
		z.tipo_id_pediatra,z.pediatra_id,a.descripcion as nomvia,x.via_procedimiento_bilateral
		FROM qx_procedimientos_programacion x
		LEFT JOIN qx_programaciones_ordenes y on (x.programacion_id=y.programacion_id AND x.procedimiento_qx=y.procedimiento_qx)
    LEFT JOIN qx_procedimientos_programacion_pediatricos z on (x.programacion_id=z.programacion_id AND x.procedimiento_qx=z.procedimiento_qx)
		LEFT JOIN qx_vias_acceso a on (x.via_procedimiento_bilateral=a.via_acceso)
		WHERE x.programacion_id='$ProgramacionId'";
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
* Funcion que consulta de la base de datos los procedimientos de una programacion
* @return array
* @param integer numero unico que identifica la programacion
*/
	function BusquedaProcedimientosCumplimiento(){

		list($dbconn) = GetDBconn();
		$query = "SELECT x.procedimiento_qx,x.tipo_id_cirujano,x.cirujano_id,x.tipo_id_ayudante,x.ayudante_id,x.plan_id,y.numero_orden_id,
		z.tipo_id_pediatra,z.pediatra_id,a.descripcion as nomvia,x.via_procedimiento_bilateral
		FROM qx_cumplimiento_procedimientos x
		LEFT JOIN qx_cumplimiento_procedimientos_ordenes y on (x.qx_cumplimiento_id=y.qx_cumplimiento_id AND x.procedimiento_qx=y.procedimiento_qx)
    LEFT JOIN qx_cumplimiento_procedimientos_pediatricos z on (x.qx_cumplimiento_id=z.qx_cumplimiento_id AND x.procedimiento_qx=z.procedimiento_qx)
		LEFT JOIN qx_vias_acceso a on (x.via_procedimiento_bilateral=a.via_acceso)
		WHERE x.qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."'";
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
* Funcion que consulta el cirujano principal de una programacion
* @return array
* @param integer codigo unico que identifica la programacion
*/
	function BuscarCirujanoPrincipalQX(){
    list($dbconn) = GetDBconn();
		$query = "SELECT tipo_id_cirujano,cirujano_id
		FROM qx_cumplimientos
		WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."'";
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
* Funcion que realiza una programacion de cirugia para una una orden de servicio
* @return array
*/
	function OrdenesPendientesPaciente(){
	  list($dbconn) = GetDBconn();
    $query="SELECT cargo.cargo_cups,z.numero_orden_id
		FROM (SELECT * FROM
		(SELECT c.cargo_cups FROM qx_cumplimientos a,os_ordenes_servicios b,os_maestro c,os_internas d WHERE a.qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND  a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND b.orden_servicio_id=c.orden_servicio_id AND c.sw_estado='1' AND date(c.fecha_vencimiento)>='".date("Y-m-d H:i:s")."' AND c.numero_orden_id=d.numero_orden_id AND c.cargo_cups=d.cargo AND d.departamento='".$_SESSION['LocalCirugias']['Departamento']."') as hola
    EXCEPT
    (SELECT f.procedimiento_qx as cargo_cups FROM qx_cumplimientos e,qx_cumplimiento_procedimientos f WHERE e.qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND e.qx_cumplimiento_id=f.qx_cumplimiento_id)) as cargo,
		qx_cumplimientos x,os_ordenes_servicios y,os_maestro z,os_internas l
		WHERE x.qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND  x.tipo_id_paciente=y.tipo_id_paciente AND x.paciente_id=y.paciente_id AND y.orden_servicio_id=z.orden_servicio_id AND z.sw_estado='1' AND
		date(z.fecha_vencimiento)>='".date("Y-m-d H:i:s")."' AND z.numero_orden_id=l.numero_orden_id AND z.cargo_cups=l.cargo AND l.departamento='".$_SESSION['LocalCirugias']['Departamento']."' AND z.cargo_cups=cargo.cargo_cups";
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

	function InsertarProcedimientosQururgicos(){
    list($dbconn) = GetDBconn();
		if($_REQUEST['Salir']){
      $this->CumplimientoCirugia($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
		  return true;
		}

		if($_REQUEST['Cancelar']){
      $this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
			return true;
		}

    if($_REQUEST['Guardar']){
		  $dbconn->BeginTrans();
		  $procedimientos=$_REQUEST['seleccion'];
      for($i=0;$i<sizeof($procedimientos);$i++){
        $query="INSERT INTO qx_cumplimiento_procedimientos
				(qx_cumplimiento_id,
				procedimiento_qx,
				tipo_id_cirujano,
				cirujano_id,
				tipo_id_ayudante,
				ayudante_id,
				plan_id,
				via_procedimiento_bilateral)
				SELECT
				'".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."',
				procedimiento_qx,
				tipo_id_cirujano,
				cirujano_id,
				tipo_id_ayudante,
				ayudante_id,
				plan_id,
				via_procedimiento_bilateral
				FROM qx_procedimientos_programacion
				WHERE programacion_id='".$_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']."'
				AND procedimiento_qx='".$procedimientos[$i]."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
				  $query="SELECT numero_orden_id
					FROM qx_programaciones_ordenes
					WHERE programacion_id='".$_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']."' AND
					procedimiento_qx='".$procedimientos[$i]."'";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}else{
            $datos=$result->RecordCount();
						if($datos){
						  $vars=$result->GetRowAssoc($toUpper=false);
						  $query="INSERT INTO qx_cumplimiento_procedimientos_ordenes(numero_orden_id,
							                                                          qx_cumplimiento_id,
							                                                          procedimiento_qx)VALUES(
                                                                        '".$vars['numero_orden_id']."',
                                                                        '".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."',
																																				'".$procedimientos[$i]."')";
							$result = $dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0){
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
							}else{
                $query="UPDATE os_maestro SET sw_estado='3' WHERE numero_orden_id='".$vars['numero_orden_id']."'";
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
			}
			$_SESSION['CIRUGIAS']['ACTO']['PROCEDIMIENTOS']=1;
			$dbconn->CommitTrans();
			$this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
			return true;
		}

		if(!$_REQUEST['codigos'] || !$_REQUEST['procedimiento'] || $_REQUEST['Responsable']==-1){
			if(!$_REQUEST['codigos'] || !$_REQUEST['procedimiento']){$this->frmError["procedimiento"]=1;}
			if($_REQUEST['Responsable']==-1){$this->frmError["Responsable"]=1;}
			$this->frmError["MensajeError"]="Complete los Datos del Procedimiento.";
			$this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta'],$_REQUEST['codigos'],$_REQUEST['procedimiento'],$_REQUEST['cirujano'],$_REQUEST['ayudante'],$_REQUEST['ResponsablePro']);
			return true;
		}

		if($_REQUEST['pediatrico']){
			if($_REQUEST['pediatra']==-1){
				$this->frmError["pediatra"]=1;
				$this->frmError["MensajeError"]="Este procedimiento esta calificado como Pediatrico, Seleccione un Profesional en Pediatria";
				$this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta'],$_REQUEST['codigos'],$_REQUEST['procedimiento'],$_REQUEST['cirujano'],$_REQUEST['ayudante'],$_REQUEST['ResponsablePro'],'',1);
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
				$this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta'],$_REQUEST['codigos'],$_REQUEST['procedimiento'],$_REQUEST['cirujano'],$_REQUEST['ayudante'],$_REQUEST['ResponsablePro'],'',1);
				return true;
			}
		}

		if($_REQUEST['bilateral']){
			if($_REQUEST['bilateral']==-1){
				$this->frmError["bilateral"]=1;
				$this->frmError["MensajeError"]="El procedimiento esta identificado como Posible Bilateral, identifique la via";
				$this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta'],$_REQUEST['codigos'],$_REQUEST['procedimiento'],$_REQUEST['cirujano'],$_REQUEST['ayudante'],$_REQUEST['ResponsablePro'],'','','',1);
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
				$this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta'],$_REQUEST['codigos'],$_REQUEST['procedimiento'],$_REQUEST['cirujano'],$_REQUEST['ayudante'],$_REQUEST['ResponsablePro'],'','','',1);
				return true;
			}
		}

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
			$query="UPDATE qx_cumplimiento_procedimientos SET procedimiento_qx='".$_REQUEST['codigos']."',
			tipo_id_cirujano=$tipoidcirujano1,cirujano_id=$cirujano1,tipo_id_ayudante=$tipoidayudante1,
			ayudante_id=$ayudante1,plan_id='".$_REQUEST['Responsable']."',
			via_procedimiento_bilateral=$viabilateral1 WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."'
			AND procedimiento_qx='".$_REQUEST['codigos1']."'";
			$result=$dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
				return false;
			}else{
				if($_REQUEST['pediatrico']){
					$query="UPDATE qx_cumplimiento_procedimientos_pediatricos SET procedimiento_qx='".$_REQUEST['codigos']."',pediatra_id='$pediatra',tipo_id_pediatra='$tipoidpediatra'
					WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND procedimiento_qx='".$_REQUEST['codigos1']."'";
					$result=$dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
						return false;
					}
				}
			}
			$this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
		  return true;
		}
    if($_REQUEST['Aceptar']){
			$comprobarProc=$this->ComprobacionProcedimientos($_SESSION['CIRUGIAS']['ACTO']['CODIGO'],$_REQUEST['codigos']);
			if($comprobarProc!=1){
				$this->frmError["MensajeError"]="Este Procedimiento ya ha sido Insertado en esta Programacion.";
				$this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta'],$_REQUEST['codigos'],$_REQUEST['procedimiento'],$_REQUEST['cirujano'],$_REQUEST['ayudante'],$_REQUEST['ResponsablePro'],'','','',1);
				return true;
			}
			if($_REQUEST['NumerOrden']){
				$query="SELECT *
				FROM qx_cumplimiento_procedimientos_ordenes
				WHERE  numero_orden_id='".$_REQUEST['NumerOrden']."' AND
				procedimiento_qx='".$_REQUEST['codigos']."' AND
				qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']."'";
				$result=$dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
					return false;
				}else{
					$datos=$result->RecordCount();
					if(!$datos){
						$query="INSERT INTO qx_cumplimiento_procedimientos_ordenes(numero_orden_id,qx_cumplimiento_id,procedimiento_qx)
						VALUES ('".$_REQUEST['NumerOrden']."','".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."','".$_REQUEST['codigos']."')";
						$result=$dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
							return false;
						}
					}
				}
			}
			$query="INSERT INTO qx_cumplimiento_procedimientos(qx_cumplimiento_id,procedimiento_qx,
			tipo_id_cirujano,cirujano_id,tipo_id_ayudante,ayudante_id,plan_id,via_procedimiento_bilateral)VALUES
			('".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."','".$_REQUEST['codigos']."',$tipoidcirujano1,$cirujano1,
			$tipoidayudante1,$ayudante1,'".$_REQUEST['Responsable']."',$viabilateral1)";
			$result=$dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
				return false;
			}else{
				if($_REQUEST['pediatrico']){
					$query="INSERT INTO qx_cumplimiento_procedimientos_pediatricos(procedimiento_qx,qx_cumplimiento_id,tipo_id_pediatra,pediatra_id)
					VALUES('".$_REQUEST['codigos']."','".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."','$tipoidpediatra','$pediatra')";
					$result=$dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
						return false;
					}
				}
			}
			$this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
		  return true;
		}
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
* Funcion que realiza la comprobacion de procedimientos existentes en la base de datos para una programacion de cirugia
* @return boolean
* @param integer numero unico que identifica la programacion
* @param integer codigo que identifica el procedimiento
* @param string codigo del tarifario del procedimiento
*/
	function ComprobacionProcedimientos($Programacion,$Procedimiento){

    list($dbconn) = GetDBconn();
		$query = "SELECT *
		FROM qx_cumplimiento_procedimientos
		WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND procedimiento_qx='$Procedimiento'";
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
	function EliminarProcedimientoCumplimiento(){

		list($dbconn) = GetDBconn();
		$query = "DELETE FROM qx_cumplimiento_procedimientos WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND procedimiento_qx='".$_REQUEST['Procedimiento']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
		return true;
	}
/**
* Funcion que modifica en la bese de datos un procedimiento indicado por el usuario
* @return boolean
*/
	function ModificarProcedimientoQX(){
    list($dbconn) = GetDBconn();
		$query = "SELECT a.tipo_id_cirujano,a.cirujano_id,a.tipo_id_ayudante,a.ayudante_id,a.plan_id,b.tipo_id_pediatra,b.pediatra_id
		FROM qx_cumplimiento_procedimientos a
		LEFT JOIN qx_cumplimiento_procedimientos_pediatricos b ON (a.procedimiento_qx=b.procedimiento_qx AND a.qx_cumplimiento_id=b.qx_cumplimiento_id)
		WHERE a.qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."' AND a.procedimiento_qx='".$_REQUEST['Procedimiento']."'";
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
			if($vars['pediatra_id'] && $vars['tipo_id_pediatra']){
				$dat=1;
				$pediatra=$vars['pediatra_id'].'/'.$vars['tipo_id_pediatra'];
			}else{
				$dat=0;
			}
			$procedimiento=$this->DescripcionProcedimiento($_REQUEST['Procedimiento']);
			$this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta'],$_REQUEST['Procedimiento'],$procedimiento['descripcion'],$cirujano,$ayudante,$vars['plan_id'],'',$dat,$pediatra,$bilateral,1);
			return true;
		}
	}

	function HallarProcedPediatrico($codigos){
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
	}
/**
* Funcion que busca en los profesionales especialistas existentes en la base de datos
* @return array
*/
	function profesionalesEspecialistaPediatria(){
    $departamento=$_SESSION['LocalCirugias']['Departamento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT  x.tercero_id,x.nombre,x.tipo_id_tercero
		FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c
		WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND x.tipo_id_tercero=y.tipo_id_tercero
		AND x.tercero_id=y.tercero_id AND y.departamento='$departamento' AND z.especialidad=l.especialidad
		AND z.sw_pediatra='1' AND x.tercero_id=l.tercero_id AND x.tipo_id_tercero=l.tipo_id_tercero
		AND x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero
		AND profesional_activo(c.tipo_id_tercero,c.tercero_id,'$departamento')='1'";
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
* Funcion que trae de la base de datos los datos principales de la programacion
* @return array
* @param integer numero unico que identifica la programacion
*/
	function DatosProgramacionQX($ProgramacionId){

		list($dbconn) = GetDBconn();
		$query="SELECT b.descripcion as viacceso,d.descripcion as tipocirugia,f.descripcion as ambito,a.via_acceso,a.tipo_cirugia,a.ambito_cirugia
		FROM qx_datos_procedimientos_cirugias a
		LEFT JOIN qx_vias_acceso b ON (a.via_acceso=b.via_acceso)
		LEFT JOIN qx_tipos_cirugia d ON (a.tipo_cirugia=d.tipo_cirugia_id)
		LEFT JOIN qx_ambitos_cirugias f ON (a.ambito_cirugia=f.ambito_cirugia_id) WHERE
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
	}

/**
* Funcion que trae de la base de datos los datos principales de la programacion
* @return array
* @param integer numero unico que identifica la programacion
*/
	function DatosProgramacionCumplimiento(){

		list($dbconn) = GetDBconn();
		$query="SELECT b.descripcion as viacceso,d.descripcion as tipocirugia,f.descripcion as ambito,a.via_acceso,a.tipo_cirugia,a.ambito_cirugia
		FROM qx_cumplimientos_datos a
		LEFT JOIN qx_vias_acceso b ON (a.via_acceso=b.via_acceso)
		LEFT JOIN qx_tipos_cirugia d ON (a.tipo_cirugia=d.tipo_cirugia_id)
		LEFT JOIN qx_ambitos_cirugias f ON (a.ambito_cirugia=f.ambito_cirugia_id) WHERE
		a.qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."'";
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
* Funcion que modifica en la base de datos los datos principales de una cirugia
* @return boolean
*/
	function InsercionDatosProgramCirugias(){
    list($dbconn) = GetDBconn();
		if($_REQUEST['viaAcceso']==-1 || $_REQUEST['tipoCirugia']==-1 || $_REQUEST['ambitoCirugia']==-1){
      if($_REQUEST['viaAcceso']==-1){$this->frmError["viaAcceso"]=1;}
			if($_REQUEST['tipoCirugia']==-1){$this->frmError["tipoCirugia"]=1;}
			if($_REQUEST['ambitoCirugia']==-1){$this->frmError["ambitoCirugia"]=1;}
			$this->frmError["MensajeError"]="Faltan Datos Obligatorios sobre la Cirugia";
			$this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
		  return true;
		}

		$query="SELECT * FROM qx_cumplimientos_datos WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				$query="UPDATE qx_cumplimientos_datos SET via_acceso='".$_REQUEST['viaAcceso']."',tipo_cirugia='".$_REQUEST['tipoCirugia']."',ambito_cirugia='".$_REQUEST['ambitoCirugia']."' WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."'";
			}else{
				$query="INSERT INTO qx_cumplimientos_datos(qx_cumplimiento_id,via_acceso,tipo_cirugia,ambito_cirugia)VALUES('".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."','".$_REQUEST['viaAcceso']."','".$_REQUEST['tipoCirugia']."','".$_REQUEST['ambitoCirugia']."')";
			}
			$result=$dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
				return false;
			}
			$_SESSION['CIRUGIAS']['ACTO']['DATOSPROCEDIMIENTOS']=1;
		}
		$this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
		return true;
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

	function RegresaReservaSangre(){
	  $this->CumplimientoCirugia($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
		return true;
	}

/**
* Funcion que cosulta en la base de datos los datos de la reserva de equipos y quirofanos de una programacion
* @return array
* @param integer numero que identifica la programacion
*/
	function obtenerDatosProgramacionQX(){
    list($dbconn) = GetDBconn();
		$query="SELECT quirofano_id,hora_inicio,hora_fin,qx_quirofano_programacion_id
		FROM qx_quirofanos_programacion
		WHERE programacion_id='".$_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']."' AND
		qx_tipo_reserva_quirofano_id != '0'";
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
	function SeleccionEquiposProgramacion(){

		list($dbconn) = GetDBconn();
		if(!$_SESSION['CIRUGIAS']['ACTO']['CODIGO']){$acto=0;}else{$acto=$_SESSION['CIRUGIAS']['ACTO']['CODIGO'];}
		if(!$_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO']){$program=0;}else{$program=$_SESSION['CIRUGIAS']['CUMPLIMIENTO']['CODIGO'];}
		$query="SELECT a.equipo_id,a.descripcion,e.descripcion as departamento,
		(SELECT 1
		FROM qx_equipos_programacion b,qx_quirofanos_programacion c
		WHERE b.equipo_id=a.equipo_id AND b.qx_quirofano_programacion_id=c.qx_quirofano_programacion_id AND c.programacion_id='$program') as programado,
		(SELECT 1
		FROM qx_cumplimientos_equipos d
		WHERE d.equipo_id=a.equipo_id AND d.qx_cumplimiento_id='$acto') as cumplido
		FROM qx_equipos_moviles a,departamentos e	WHERE a.estado=1 AND a.departamento=e.departamento";
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

/**
* Funcion que cosulta en la base de datos los datos de la reserva de equipos y quirofanos de una programacion
* @return array
* @param integer numero que identifica la programacion
*/
	function obtenerDatosCumplimiento(){
    list($dbconn) = GetDBconn();
		$query="SELECT quirofano_id,hora_inicio,hora_fin
		FROM qx_cumplimientos_quirofano
		WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."'";
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

	function GuardarDatosQuirofanos(){
    if($_REQUEST['cancelar']){
      $this->CumplimientoCirugia($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
		  return true;
		}
		if($_REQUEST['quirofano']==-1 || !$_REQUEST['fechaCirugia'] || !$_REQUEST['horaInicio'] || !$_REQUEST['minInicio'] || !$_REQUEST['HorasDura'] || !$_REQUEST['MinutosDura']){
      if($_REQUEST['quirofano']==-1){$this->frmError["quirofano"]=1;}
			if(!$_REQUEST['fechaCirugia']){$this->frmError["fechaCirugia"]=1;}
			if(!$_REQUEST['horaInicio']){$this->frmError["horaInicio"]=1;}
			if(!$_REQUEST['minInicio']){$this->frmError["minInicio"]=1;}
			if(!$_REQUEST['HorasDura']){$this->frmError["HorasDura"]=1;}
			if(!$_REQUEST['MinutosDura']){$this->frmError["MinutosDura"]=1;}
			$this->frmError["MensajeError"]="No es Posible realizar la modificacion porque los datos son Obligatorios";
			$this->ReserveEquiposQuirofanos($_REQUEEST['TipoId'],$_REQUEEST['Documento'],$_REQUEEST['Responsable'],$_REQUEEST['cuenta']);
			return true;
		}
		list($dbconn) = GetDBconn();
		$cadena=explode('/',$_REQUEST['fechaCirugia']);
		$fechaIn=$cadena[2].'-'.$cadena[1].'-'.$cadena[0].' '.$_REQUEST['horaInicio'].':'.$_REQUEST['minInicio'].':'.'00';
		$fechaFn=date("Y-m-d H:i:s",(mktime($_REQUEST['horaInicio']+$_REQUEST['HorasDura'],$_REQUEST['minInicio']+$_REQUEST['MinutosDura'],0,$cadena[1],$cadena[0],$cadena[2])));
		$query="SELECT * FROM qx_cumplimientos_quirofano WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."'";
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
			  $query="DELETE FROM qx_cumplimientos_equipos WHERE qx_cumplimiento_id='".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
        $query="UPDATE qx_cumplimientos_quirofano
		    SET quirofano_id='".$_REQUEST['quirofano']."',hora_inicio='$fechaIn',hora_fin='$fechaFn'";
			}else{
        $query="INSERT INTO qx_cumplimientos_quirofano(qx_cumplimiento_id,quirofano_id,
		                                              hora_inicio,hora_fin,
																									departamento,usuario_id,
																									fecha_registro)VALUES('".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."','".$_REQUEST['quirofano']."',
																									'$fechaIn','$fechaFn','".$_SESSION['LocalCirugias']['Departamento']."',
																									'".UserGetUID()."','".date("Y-m-d H:i:s")."')";
			}
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		$vector=$_REQUEST['seleccion'];
		for($i=0;$i<sizeof($vector);$i++){
			$query="INSERT INTO qx_cumplimientos_equipos (equipo_id,qx_cumplimiento_id)
							VALUES('".$vector[$i]."','".$_SESSION['CIRUGIAS']['ACTO']['CODIGO']."')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		$_SESSION['CIRUGIAS']['ACTO']['QUIROFANO']=1;
		$this->CumplimientoCirugia($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta']);
		return true;
	}






















































	/*function BuscarPacienteCumplimiento(){
	  $TipoDocumento='CC';
		$Documento='16369456';
		$nombrePac='ROBERT ALBERTO OSORIO OSORIOA';
    $Responsable='73';
	  list($dbconn) = GetDBconn();
	  $query="SELECT  qx_acto_id FROM qx_acto WHERE paciente_id='$Documento' AND tipo_id_paciente='$TipoDocumento' AND sw_estado='1'";
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
        $vars=$result->GetRowAssoc($toUpper=false);
        $_SESSION['Cirugia']['Cumplimiento']['Acto']=$vars['qx_acto'];
			}
		}
    $this->CumplirLlegadaPac($TipoDocumento,$Documento,$nombrePac,$Responsable='',$tipoAfil='',$rango='',$semanas='');
		return true;
	}

	function DatosCumplimiento(){

		list($dbconn) = GetDBconn();
	  $query="SELECT  a.quirofano_id,a.fecha_hora_inicio,a.fecha_hora_final,a.tipo_id_cirujano,a.cirujano_principal
		a.tipo_id_anestesiologo,a.anestesiologo,a.tipo_id_circulante_uno,a.circulante_uno,
		a.tipo_id_instrumentista,a.instrumentista,a.gas_anestesico,a.gas_medicinal,a.via_acceso,a.tipo_cirugia_id,a.ambito_cirugia_id,
		a.diagnostico_id,b.diagnostico_nombre,a.tipo_anestesia
		FROM qx_acto a
		LEFT JOIN diagnosticos b ON(b.diagnostico_id=a.diagnostico_id)
		WHERE a.qx_acto_id='".$_SESSION['Cirugia']['Cumplimiento']['Acto']."'";
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

	function DatosProgramacion($TipoDocumento,$Documento){
    list($dbconn) = GetDBconn();
	  $query="SELECT a.tipo_id_cirujano,a.cirujano_id,
		a.diagnostico_id,b.diagnostico_nombre,c.tipo_id_tercero as tipo_id_anestesiologo,
		c.tercero_id as anestesiologo,c.tipo_id_instrumentista,c.instrumentista_id,
		c.tipo_id_circulante,c.circulante_id,d.quirofano_id,d.hora_inicio,d.hora_fin,
		e.via_acceso,e.tipo_cirugia,e.ambito_cirugia
		FROM qx_programaciones a
		LEFT JOIN diagnosticos b ON(a.diagnostico_id=b.diagnostico_id)
		LEFT JOIN qx_anestesiologo_programacion c ON (a.programacion_id=c.programacion_id)
		LEFT JOIN qx_quirofanos_programacion d ON(a.programacion_id=d.programacion_id AND d.qx_tipo_reserva_quirofano_id='3')
		LEFT JOIN qx_datos_procedimientos_cirugias e ON(a.programacion_id=e.programacion_id)
		WHERE a.tipo_id_paciente='$TipoDocumento' AND a.paciente_id='$Documento'";
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
	}*/
















	
	

	
	
	
	













/**
* Funcion que retorna un arreglo de los quirofanos con los que cuenta la ips en el departamento en el que esta logueado el usuario
* @return array
*/
	function TotalQuirofanos(){
	  $departamento=$_SESSION['LocalCirugias']['Departamento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT quirofano,descripcion FROM qx_quirofanos WHERE departamento='".$_SESSION['LocalCirugias']['Departamento']."'";
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
* Funcion que busca en la base de datos los apellidos de un paciente a partir de su identificacion
* @return string
* @param string tipo del documento del paciente
* @param string numero del documento del paciente
*/
	function BuscarApellidosPaciente($tipo,$documento)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT primer_apellido,segundo_apellido FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else{

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

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo en la tabla pacientes";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else{

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

	function BusquedaProgramacion(){

	  if($_REQUEST['Regresar']){
      $this->MenuQXEjecucion();
			return true;
		}
    if($_REQUEST['Aceptar']){
      $this->FormaBusquedaProgramacion($_REQUEST['TipoBusquedaInv']);
			return true;
		}
		$exepcion="SELECT a.programacion_id
		FROM qx_programaciones a,qx_quirofanos_programacion b
		WHERE a.programacion_id=b.programacion_id AND b.qx_tipo_reserva_quirofano_id!=0 AND a.estado=1
		EXCEPT SELECT qx_programacion_id FROM qx_acto";
		if($_REQUEST['Buscar']){
      if(empty($_REQUEST['Busqueda'])){
        if(!$_REQUEST['Documento']){
				  $this->frmError["MensajeError"]="Para Realizar la Busqueda Requiere llenar todos los datos Solicitados";
				  $this->FormaBusquedaProgramacion($_REQUEST['Busqueda']);
					return true;
				}else{
          $query="SELECT a.programacion_id,a.tipo_id_paciente,a.paciente_id,a.tipo_id_cirujano,a.cirujano_id,b.hora_inicio,b.hora_fin,b.quirofano_id
					FROM qx_programaciones a,qx_quirofanos_programacion b,($exepcion) as m
					WHERE a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."' AND a.paciente_id='".$_REQUEST['Documento']."' AND a.programacion_id=b.programacion_id
					AND a.estado=1 AND b.qx_tipo_reserva_quirofano_id!=0 AND m.programacion_id=a.programacion_id";
				}
			}elseif($_REQUEST['Busqueda']==1){
			  if($_REQUEST['cirujano']==-1){
				  $this->frmError["MensajeError"]="Para Realizar la Busqueda Requiere llenar todos los datos Solicitados";
          $this->FormaBusquedaProgramacion($_REQUEST['Busqueda']);
					return true;
				}else{
          $cadena=explode('/',$_REQUEST['cirujano']);
          $query="SELECT a.programacion_id,a.tipo_id_paciente,a.paciente_id,a.tipo_id_cirujano,a.cirujano_id,b.hora_inicio,b.hora_fin,b.quirofano_id
					FROM qx_programaciones a,qx_quirofanos_programacion b,($exepcion) as m
					WHERE a.tipo_id_cirujano='".$cadena[1]."' AND a.cirujano_id='".$cadena[0]."' AND a.programacion_id=b.programacion_id AND a.estado=1 AND b.qx_tipo_reserva_quirofano_id!=0 AND
					m.programacion_id=a.programacion_id";
				}
			}elseif($_REQUEST['Busqueda']==2){
        $FechaIni=explode('/',$_REQUEST['FechaInicial']);
				$FechaFin=explode('/',$_REQUEST['FechaFinal']);
				if(!$_REQUEST['FechaInicial'] || !$_REQUEST['FechaFinal'] || (mktime(0,0,0,$FechaFin[1],$FechaFin[0],$FechaFin[3])<mktime(0,0,0,$FechaIni[1],$FechaIni[0],$FechaIni[3]))){
				   $this->frmError["MensajeError"]="Realize de nuevo la insercion y verifiquelas fechas";
           $this->FormaBusquedaProgramacion($_REQUEST['Busqueda']);
					 return true;
				}else{
          $FechaIn=$FechaIni[2].'/'.$FechaIni[1].'/'.$FechaIni[0];
					$FechaFi=$FechaFin[2].'/'.$FechaFin[1].'/'.$FechaFin[0];
          $query="SELECT a.programacion_id,a.tipo_id_paciente,a.paciente_id,a.tipo_id_cirujano,a.cirujano_id,b.hora_inicio,b.hora_fin,b.quirofano_id
					FROM qx_programaciones a,qx_quirofanos_programacion b,($exepcion) as m
					WHERE date(b.hora_inicio)>='$FechaIn' AND date(b.hora_inicio)<='$FechaFi' AND a.programacion_id=b.programacion_id AND a.estado=1 AND b.qx_tipo_reserva_quirofano_id!=0 AND
					a.programacion_id=m.programacion_id";
				}
			}elseif($_REQUEST['Busqueda']==3){
        if(!$_REQUEST['numeroProgramacion']){
				   $this->frmError["MensajeError"]="Para Realizar la Busqueda Requiere llenar todos los datos Solicitados";
           $this->FormaBusquedaProgramacion($_REQUEST['Busqueda']);
					 return true;
				}else{
          $query="SELECT a.programacion_id,a.tipo_id_paciente,a.paciente_id,a.tipo_id_cirujano,a.cirujano_id,b.hora_inicio,b.hora_fin,b.quirofano_id
					FROM qx_programaciones a,qx_quirofanos_programacion b,($exepcion) as m
					WHERE a.programacion_id='".$_REQUEST['numeroProgramacion']."' AND a.programacion_id=b.programacion_id AND a.estado=1 AND b.qx_tipo_reserva_quirofano_id!=0 AND
					a.programacion_id=m.programacion_id";
				}
			}elseif($_REQUEST['Busqueda']==4){
			  if($_REQUEST['quirofano']==-1){
				  $this->frmError["MensajeError"]="Para Realizar la Busqueda Requiere llenar todos los datos Solicitados";
          $this->FormaBusquedaProgramacion($_REQUEST['Busqueda']);
					return true;
				}else{
          $query="SELECT a.programacion_id,a.tipo_id_paciente,a.paciente_id,a.tipo_id_cirujano,a.cirujano_id,b.hora_inicio,b.hora_fin,b.quirofano_id
					FROM qx_programaciones a,qx_quirofanos_programacion b,($exepcion) as m
					WHERE a.programacion_id=b.programacion_id AND b.quirofano_id='".$_REQUEST['quirofano']."' AND a.estado=1 AND b.qx_tipo_reserva_quirofano_id!=0 AND
					a.programacion_id=m.programacion_id";
				}
			}elseif($_REQUEST['Busqueda']==5){
        if(!$_REQUEST['nombres'] && !$_REQUEST['apellidos']){
				  $this->frmError["MensajeError"]="Para Realizar la Busqueda Requiere llenar todos los datos Solicitados";
          $this->FormaBusquedaProgramacion($_REQUEST['Busqueda']);
					return true;
				}else{
					$_REQUEST['nombres']=strtoupper($_REQUEST['nombres']);
					$_REQUEST['apellidos']=strtoupper($_REQUEST['apellidos']);
				  $query="SELECT a.programacion_id,a.tipo_id_paciente,a.paciente_id,a.tipo_id_cirujano,a.cirujano_id,b.hora_inicio,b.hora_fin,b.quirofano_id
					FROM qx_programaciones a,(SELECT primer_apellido||' '||segundo_apellido as nom,primer_nombre||' '||segundo_nombre as prenom,tipo_id_paciente,paciente_id FROM pacientes) c,qx_quirofanos_programacion b,($exepcion) as m
					WHERE (c.nom LIKE '%".$_REQUEST['apellidos']."%' AND c.prenom LIKE '%".$_REQUEST['nombres']."%') AND c.paciente_id=a.paciente_id AND c.tipo_id_paciente=a.tipo_id_paciente AND a.programacion_id=b.programacion_id AND a.estado=1 AND b.qx_tipo_reserva_quirofano_id!=0 AND
					m.programacion_id=a.programacion_id";
				}
			}
		}
    if($_REQUEST['BuscarTotal']){
       $query="SELECT a.programacion_id,a.tipo_id_paciente,a.paciente_id,a.tipo_id_cirujano,a.cirujano_id,b.hora_inicio,b.hora_fin,b.quirofano_id
				FROM qx_programaciones a,qx_quirofanos_programacion b,($exepcion) as m
				WHERE a.programacion_id=b.programacion_id AND a.estado=1 AND b.qx_tipo_reserva_quirofano_id!=0 AND m.programacion_id=a.programacion_id";
		}
		list($dbconn) = GetDBconn();
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
		$this->FormaBusquedaProgramacion('',1,$vars);
		return true;
	}


 	function LlamaFormaEjecucionCirugia($programacion){
	  if($programacion){
      $_REQUEST['programacion']=$programacion;
		}
 	  if($_REQUEST['programacion']){
		  $_SESSION['EJECUCION']['CIRUGIAS']['CODIGO']=$_REQUEST['programacion'];
      $datosPaciente=$this->SacaDatosPacienteProgramQX($_REQUEST['programacion']);
			$cirujano=$datosPaciente['cirujano_id'].'/'.$datosPaciente['tipo_id_cirujano'];
			$anestesista=$datosPaciente['terceroanestesiologo'].'/'.$datosPaciente['tipoanestesiologo'];
			$FechaInicial=$this->FechaStamp($datosPaciente['hora_inicio']);
      $Hora=$this->HoraStamp($datosPaciente['hora_inicio']);
      $cadena=explode(':',$Hora);
			$HoraIni=$cadena[0];
			$MinIni=$cadena[1];
			$FechaFinal=$this->FechaStamp($datosPaciente['hora_fin']);
      $Hora=$this->HoraStamp($datosPaciente['hora_fin']);
      $cadena=explode(':',$Hora);
			$HoraFin=$cadena[0];
			$MinFin=$cadena[1];
			$this->FormaEjecucionCirugia($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id'],$datosPaciente['plan_id'],$cirujano,
			$anestesista,$datosPaciente['quirofano_id'],$FechaInicial,$HoraIni,$MinIni,$FechaFinal,$HoraFin,$MinFin,$datosPaciente['via_acceso']);
			return true;
 		}else{
      $this->FormaEjecucionCirugia();
			return true;
		}
 	}

/**
* Funcion que busca en la base de datos la fecha de nacimiento de un paciente a partir de su identificacion
* @return string
* @param string tipo del documento del paciente
* @param string numero del documento del paciente
*/
	function Edad($TipoId,$PacienteId)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT fecha_nacimiento FROM pacientes WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else{

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

	function SalirFormaEjecucion(){
		if($_REQUEST['AdicionProfe']){
      $this->IdentificacionNuevoProfesional();
			return true;
		}
		if(!$_REQUEST['Documento']){
			$this->frmError["Documento"]=1;
			$this->FormaEjecucionCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cirujano'],$_REQUEST['anestesista'],$_REQUEST['quirofano'],
			$_REQUEST['FechaInicial'],$_REQUEST['HoraIni'],$_REQUEST['MinIni'],$_REQUEST['FechaFinal'],$_REQUEST['HoraFin'],$_REQUEST['MinFin'],$_REQUEST['viaAcceso'],$_REQUEST['tipoCirugia'],$_REQUEST['ambitoCirugia'],$_REQUEST['circulante1'],$_REQUEST['circulante2'],
			$_REQUEST['instrumentista'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],'','','','','',$_REQUEST['destino'],
			$_REQUEST['cargo'],$_REQUEST['codigo'],$_REQUEST['cargo1'],$_REQUEST['codigo1'],$_REQUEST['TipoAnestesia'],$_REQUEST['FechaIncioGas'],$_REQUEST['HoraInicioGas'],$_REQUEST['MinutosInicioGas'],$_REQUEST['FechaFinGas'],$_REQUEST['HoraFinGas'],$_REQUEST['MinutosFinGas'],
			$_REQUEST['estadoSalida'],$_REQUEST['protocolo'],$_REQUEST['FechaIngresoRecuperacion'],$_REQUEST['HoraIngresoRecuperacion'],$_REQUEST['MinutosIngresoRecuperacion'],$_REQUEST['FechaEgresoRecuperacion'],$_REQUEST['HoraEgresoRecuperacion'],
			$_REQUEST['MinutosEgresoRecuperacion']);
			return true;
		}
		if($_REQUEST['Responsable']==-1 || $_REQUEST['cirujano']==-1 ||
		  !$_REQUEST['FechaInicial'] || !$_REQUEST['HoraIni'] || !$_REQUEST['MinIni'] ||
			!$_REQUEST['FechaFinal'] || !$_REQUEST['HoraFin'] || !$_REQUEST['MinFin'] ||
			$_REQUEST['viaAcceso']==-1 || $_REQUEST['tipoCirugia']==-1 ||$_REQUEST['ambitoCirugia']==-1){
			if($_REQUEST['cirujano']==-1){$this->frmError["cirujano"]=1;}
			if($_REQUEST['Responsable']==-1){$this->frmError["Responsable"]=1;}
			if(!$_REQUEST['FechaInicial']){$this->frmError["FechaInicial"]=1;}
			if(!$_REQUEST['HoraIni']){$this->frmError["HoraIni"]=1;}
			if(!$_REQUEST['MinIni']){$this->frmError["MinIni"]=1;}
      if(!$_REQUEST['FechaFinal']){$this->frmError["FechaFinal"]=1;}
			if(!$_REQUEST['HoraFin']){$this->frmError["HoraFin"]=1;}
			if(!$_REQUEST['MinFin']){$this->frmError["MinFin"]=1;}
			if($_REQUEST['viaAcceso']==-1){$this->frmError["viaAcceso"]=1;}
			if($_REQUEST['tipoCirugia']==-1){$this->frmError["tipoCirugia"]=1;}
			if($_REQUEST['ambitoCirugia']==-1){$this->frmError["ambitoCirugia"]=1;}
			$this->frmError["MensajeError"]="Datos Incompletos";
			$this->FormaEjecucionCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cirujano'],$_REQUEST['anestesista'],$_REQUEST['quirofano'],
			$_REQUEST['FechaInicial'],$_REQUEST['HoraIni'],$_REQUEST['MinIni'],$_REQUEST['FechaFinal'],$_REQUEST['HoraFin'],$_REQUEST['MinFin'],$_REQUEST['viaAcceso'],$_REQUEST['tipoCirugia'],$_REQUEST['ambitoCirugia'],$_REQUEST['circulante1'],$_REQUEST['circulante2'],
			$_REQUEST['instrumentista'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],'','','','','',$_REQUEST['destino'],
			$_REQUEST['cargo'],$_REQUEST['codigo'],$_REQUEST['cargo1'],$_REQUEST['codigo1'],$_REQUEST['TipoAnestesia'],$_REQUEST['FechaIncioGas'],$_REQUEST['HoraInicioGas'],$_REQUEST['MinutosInicioGas'],$_REQUEST['FechaFinGas'],$_REQUEST['HoraFinGas'],$_REQUEST['MinutosFinGas'],
			$_REQUEST['estadoSalida'],$_REQUEST['protocolo'],$_REQUEST['FechaIngresoRecuperacion'],$_REQUEST['HoraIngresoRecuperacion'],$_REQUEST['MinutosIngresoRecuperacion'],$_REQUEST['FechaEgresoRecuperacion'],$_REQUEST['HoraEgresoRecuperacion'],
			$_REQUEST['MinutosEgresoRecuperacion']);
			return true;
		}
		$cadena=explode('/',$_REQUEST['FechaInicial']);
		$cadena1=explode('/',$_REQUEST['FechaFinal']);
		if(!$_REQUEST['FechaInicial']||!$_REQUEST['FechaFinal']||!$_REQUEST['HoraIni']||
		!$_REQUEST['MinIni']||!$_REQUEST['HoraFin']||!$_REQUEST['MinFin']||
		  (mktime($_REQUEST['HoraIni'],$_REQUEST['MinIni'],0,$cadena[1],$cadena[0],$cadena[2])>mktime($_REQUEST['HoraFin'],$_REQUEST['MinFin'],0,$cadena1[1],$cadena1[0],$cadena1[2]))){
      $this->frmError["MensajeError"]="Existe un Error con las fechas de la Cirugia,Verifique estas fechas";
			$this->FormaEjecucionCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cirujano'],$_REQUEST['anestesista'],$_REQUEST['quirofano'],
			$_REQUEST['FechaInicial'],$_REQUEST['HoraIni'],$_REQUEST['MinIni'],$_REQUEST['FechaFinal'],$_REQUEST['HoraFin'],$_REQUEST['MinFin'],$_REQUEST['viaAcceso'],$_REQUEST['tipoCirugia'],$_REQUEST['ambitoCirugia'],$_REQUEST['circulante1'],$_REQUEST['circulante2'],
			$_REQUEST['instrumentista'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],'','','','','',$_REQUEST['destino'],
			$_REQUEST['cargo'],$_REQUEST['codigo'],$_REQUEST['cargo1'],$_REQUEST['codigo1'],$_REQUEST['TipoAnestesia'],$_REQUEST['FechaIncioGas'],$_REQUEST['HoraInicioGas'],$_REQUEST['MinutosInicioGas'],$_REQUEST['FechaFinGas'],$_REQUEST['HoraFinGas'],$_REQUEST['MinutosFinGas'],
			$_REQUEST['estadoSalida'],$_REQUEST['protocolo'],$_REQUEST['FechaIngresoRecuperacion'],$_REQUEST['HoraIngresoRecuperacion'],$_REQUEST['MinutosIngresoRecuperacion'],$_REQUEST['FechaEgresoRecuperacion'],$_REQUEST['HoraEgresoRecuperacion'],
			$_REQUEST['MinutosEgresoRecuperacion']);
			return true;
		}
		if($_REQUEST['FechaIncioGas'] || $_REQUEST['HoraInicioGas'] || $_REQUEST['MinutosInicioGas'] || $_REQUEST['FechaFinGas'] || $_REQUEST['HoraFinGas'] || $_REQUEST['MinutosFinGas']){
		  $FechaGasIn=explode('/',$_REQUEST['FechaIncioGas']);
			$FechaGasFn=explode('/',$_REQUEST['FechaFinGas']);
      if(!$_REQUEST['FechaIncioGas'] || !$_REQUEST['HoraInicioGas'] || !$_REQUEST['MinutosInicioGas'] || !$_REQUEST['FechaFinGas'] || !$_REQUEST['HoraFinGas'] || !$_REQUEST['MinutosFinGas'] ||
			  (mktime($_REQUEST['HoraInicioGas'],$_REQUEST['MinutosInicioGas'],0,$FechaGasIn[1],$FechaGasIn[0],$FechaGasIn[2])>mktime($_REQUEST['HoraFinGas'],$_REQUEST['MinutosFinGas'],0,$FechaGasFn[1],$FechaGasFn[0],$FechaGasFn[2]))){
				$this->frmError["MensajeError"]="Existe un Error con las fechas del inicio de la Aplicacion del Gas,Verifique estas fechas";
				$this->FormaEjecucionCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cirujano'],$_REQUEST['anestesista'],$_REQUEST['quirofano'],
				$_REQUEST['FechaInicial'],$_REQUEST['HoraIni'],$_REQUEST['MinIni'],$_REQUEST['FechaFinal'],$_REQUEST['HoraFin'],$_REQUEST['MinFin'],$_REQUEST['viaAcceso'],$_REQUEST['tipoCirugia'],$_REQUEST['ambitoCirugia'],$_REQUEST['circulante1'],$_REQUEST['circulante2'],
				$_REQUEST['instrumentista'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],'','','','','',$_REQUEST['destino'],
				$_REQUEST['cargo'],$_REQUEST['codigo'],$_REQUEST['cargo1'],$_REQUEST['codigo1'],$_REQUEST['TipoAnestesia'],$_REQUEST['FechaIncioGas'],$_REQUEST['HoraInicioGas'],$_REQUEST['MinutosInicioGas'],$_REQUEST['FechaFinGas'],$_REQUEST['HoraFinGas'],$_REQUEST['MinutosFinGas'],
				$_REQUEST['estadoSalida'],$_REQUEST['protocolo'],$_REQUEST['FechaIngresoRecuperacion'],$_REQUEST['HoraIngresoRecuperacion'],$_REQUEST['MinutosIngresoRecuperacion'],$_REQUEST['FechaEgresoRecuperacion'],$_REQUEST['HoraEgresoRecuperacion'],
				$_REQUEST['MinutosEgresoRecuperacion']);
				return true;
			}
		}
		if($_REQUEST['FechaIngresoRecuperacion'] || $_REQUEST['HoraIngresoRecuperacion'] || $_REQUEST['MinutosIngresoRecuperacion'] || $_REQUEST['FechaEgresoRecuperacion'] || $_REQUEST['HoraEgresoRecuperacion'] || $_REQUEST['MinutosEgresoRecuperacion']){
		  $FechaRecupIn=explode('/',$_REQUEST['FechaIngresoRecuperacion']);
			$FechaRecupEg=explode('/',$_REQUEST['FechaEgresoRecuperacion']);
      if(!$_REQUEST['FechaIngresoRecuperacion'] || !$_REQUEST['HoraIngresoRecuperacion'] || !$_REQUEST['MinutosIngresoRecuperacion'] || !$_REQUEST['FechaEgresoRecuperacion'] || !$_REQUEST['HoraEgresoRecuperacion'] || !$_REQUEST['MinutosEgresoRecuperacion'] ||
			  (mktime($_REQUEST['HoraIngresoRecuperacion'],$_REQUEST['MinutosIngresoRecuperacion'],0,$FechaRecupIn[1],$FechaRecupIn[0],$FechaRecupIn[2])>mktime($_REQUEST['HoraEgresoRecuperacion'],$_REQUEST['MinutosEgresoRecuperacion'],0,$FechaRecupEg[1],$FechaRecupEg[0],$FechaRecupEg[2]))||
				(mktime($_REQUEST['HoraIngresoRecuperacion'],$_REQUEST['MinutosIngresoRecuperacion'],0,$FechaRecupIn[1],$FechaRecupIn[0],$FechaRecupIn[2])<mktime($_REQUEST['HoraFin'],$_REQUEST['MinFin'],0,$cadena1[1],$cadena1[0],$cadena1[2]))){
				$this->frmError["MensajeError"]="Existe un Error con las fechas de la Recuperacion de la cirugia,Verifique estas fechas";
				$this->FormaEjecucionCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cirujano'],$_REQUEST['anestesista'],$_REQUEST['quirofano'],
				$_REQUEST['FechaInicial'],$_REQUEST['HoraIni'],$_REQUEST['MinIni'],$_REQUEST['FechaFinal'],$_REQUEST['HoraFin'],$_REQUEST['MinFin'],$_REQUEST['viaAcceso'],$_REQUEST['tipoCirugia'],$_REQUEST['ambitoCirugia'],$_REQUEST['circulante1'],$_REQUEST['circulante2'],
				$_REQUEST['instrumentista'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],'','','','','',$_REQUEST['destino'],
				$_REQUEST['cargo'],$_REQUEST['codigo'],$_REQUEST['cargo1'],$_REQUEST['codigo1'],$_REQUEST['TipoAnestesia'],$_REQUEST['FechaIncioGas'],$_REQUEST['HoraInicioGas'],$_REQUEST['MinutosInicioGas'],$_REQUEST['FechaFinGas'],$_REQUEST['HoraFinGas'],$_REQUEST['MinutosFinGas'],
				$_REQUEST['estadoSalida'],$_REQUEST['protocolo'],$_REQUEST['FechaIngresoRecuperacion'],$_REQUEST['HoraIngresoRecuperacion'],$_REQUEST['MinutosIngresoRecuperacion'],$_REQUEST['FechaEgresoRecuperacion'],$_REQUEST['HoraEgresoRecuperacion'],
				$_REQUEST['MinutosEgresoRecuperacion']);
				return true;
			}
		}
		if(!$_SESSION['EJECUCION']['CIRUGIAS']['ACTO']){
			unset($_SESSION['PACIENTES']);
			$_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$_REQUEST['Documento'];
			$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['TipoDocumento'];
			$_SESSION['PACIENTES']['PACIENTE']['plan_id']=$_REQUEST['Responsable'];
			$_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
			$_SESSION['PACIENTES']['RETORNO']['modulo']='QXEjecucion';
			$_SESSION['PACIENTES']['RETORNO']['tipo']='user';
			$_SESSION['PACIENTES']['RETORNO']['metodo']='GuardarCumplimientoCirugia';
			$_SESSION['PACIENTES']['RETORNO']['argumentos']=array("TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"Responsable"=>$_REQUEST['Responsable'],"cirujano"=>$_REQUEST['cirujano'],"anestesista"=>$_REQUEST['anestesista'],"quirofano"=>$_REQUEST['quirofano'],
			"FechaInicial"=>$_REQUEST['FechaInicial'],"HoraIni"=>$_REQUEST['HoraIni'],"MinIni"=>$_REQUEST['MinIni'],"FechaFinal"=>$_REQUEST['FechaFinal'],"HoraFin"=>$_REQUEST['HoraFin'],"MinFin"=>$_REQUEST['MinFin'],"viaAcceso"=>$_REQUEST['viaAcceso'],"tipoCirugia"=>$_REQUEST['tipoCirugia'],"ambitoCirugia"=>$_REQUEST['ambitoCirugia'],"circulante1"=>$_REQUEST['circulante1'],"circulante2"=>$_REQUEST['circulante2'],
			"instrumentista"=>$_REQUEST['instrumentista'],"gasAnestesico"=>$_REQUEST['gasAnestesico'],"gasAnestesicoMe"=>$_REQUEST['gasAnestesicoMe'],
			"destino"=>$_REQUEST['destino'],"cargo"=>$_REQUEST['cargo'],"codigo"=>$_REQUEST['codigo'],"cargo1"=>$_REQUEST['cargo1'],"codigo1"=>$_REQUEST['codigo1'],"TipoAnestesia"=>$_REQUEST['TipoAnestesia'],"FechaIncioGas"=>$_REQUEST['FechaIncioGas'],"HoraInicioGas"=>$_REQUEST['HoraInicioGas'],"MinutosInicioGas"=>$_REQUEST['MinutosInicioGas'],"FechaFinGas"=>$_REQUEST['FechaFinGas'],"HoraFinGas"=>$_REQUEST['HoraFinGas'],"MinutosFinGas"=>$_REQUEST['MinutosFinGas'],
			"estadoSalida"=>$_REQUEST['estadoSalida'],"protocolo"=>$_REQUEST['protocolo'],"FechaIngresoRecuperacion"=>$_REQUEST['FechaIngresoRecuperacion'],"HoraIngresoRecuperacion"=>$_REQUEST['HoraIngresoRecuperacion'],"MinutosIngresoRecuperacion"=>$_REQUEST['MinutosIngresoRecuperacion'],"FechaEgresoRecuperacion"=>$_REQUEST['FechaEgresoRecuperacion'],"HoraEgresoRecuperacion"=>$_REQUEST['HoraEgresoRecuperacion'],
			"MinutosEgresoRecuperacion"=>$_REQUEST['MinutosEgresoRecuperacion']);
			$this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
			return true;
		}else{
      $this->GuardarCumplimientoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'],
			$_REQUEST['cirujano'],$_REQUEST['anestesista'],$_REQUEST['quirofano'],$_REQUEST['FechaInicial'],$_REQUEST['HoraIni'],$_REQUEST['MinIni'],$_REQUEST['FechaFinal'],
			$_REQUEST['HoraFin'],$_REQUEST['MinFin'],$_REQUEST['viaAcceso'],$_REQUEST['tipoCirugia'],$_REQUEST['ambitoCirugia'],$_REQUEST['circulante1'],$_REQUEST['circulante2'],$_REQUEST['instrumentista'],
			$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],$_REQUEST['destino'],$_REQUEST['cargo'],$_REQUEST['codigo'],$_REQUEST['cargo1'],$_REQUEST['codigo1'],$_REQUEST['TipoAnestesia'],
			$_REQUEST['FechaIncioGas'],$_REQUEST['HoraInicioGas'],$_REQUEST['MinutosInicioGas'],$_REQUEST['FechaFinGas'],$_REQUEST['HoraFinGas'],$_REQUEST['MinutosFinGas'],
			$_REQUEST['estadoSalida'],$_REQUEST['protocolo'],$_REQUEST['FechaIngresoRecuperacion'],$_REQUEST['HoraIngresoRecuperacion'],$_REQUEST['MinutosIngresoRecuperacion'],$_REQUEST['FechaEgresoRecuperacion'],$_REQUEST['HoraEgresoRecuperacion'],
			$_REQUEST['MinutosEgresoRecuperacion']);
			return true;
		}
	}
/**
* Funcion que llama al modulo externo de la adicion de profesionales
* @return boolean
*/
	function LlamaAdicionProfesional(){
    $_SESSION['PROVEEDORES']['DATOS']['DesEmpresa']=$_SESSION['LocalCirugias']['NombreEmp'];
    $_SESSION['PROVEEDORES']['DATOS']['empresa']=$_SESSION['LocalCirugias']['empresa'];
		$_SESSION['PROVEEDORES']['DATOS']['departamento']=$_SESSION['LocalCirugias']['Departamento'];
		$_SESSION['PROVEEDORES']['DATOS']['descdepartamento']=$_SESSION['LocalCirugias']['NombreDpto'];
		$_SESSION['PROVEEDORES']['DATOS']['TipoDocumento']=$_REQUEST['TipoDocumento'];
		$_SESSION['PROVEEDORES']['DATOS']['Documento']=$_REQUEST['Documento'];
		$_SESSION['PROVEEDORES']['RETORNO']['contenedor']='app';
		$_SESSION['PROVEEDORES']['RETORNO']['modulo']='QXEjecucion';
		$_SESSION['PROVEEDORES']['RETORNO']['tipo']='user';
		$_SESSION['PROVEEDORES']['RETORNO']['metodo']='FormaEjecucionCirugia';
    $this->ReturnMetodoExterno('app','Profesionales','user','LlamadaOtrosModulos');
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

	function GuardarCumplimientoCirugia($TipoDocumento,$Documento,$Responsable,
	$cirujano,$anestesista,$quirofano,$FechaInicial,$HoraIni,$MinIni,$FechaFinal,
	$HoraFin,$MinFin,$viaAcceso,$tipoCirugia,$ambitoCirugia,$circulante1,$circulante2,$instrumentista,
	$gasAnestesico,$gasAnestesicoMe,$destino,$cargo,$codigo,$cargo1,$codigo1,$TipoAnestesia,$FechaIncioGas,
	$HoraInicioGas,$MinutosInicioGas,$FechaFinGas,$HoraFinGas,$MinutosFinGas,$estadoSalida,$protocolo,
	$FechaIngresoRecuperacion,$HoraIngresoRecuperacion,$MinutosIngresoRecuperacion,$FechaEgresoRecuperacion,
	$HoraEgresoRecuperacion,$MinutosEgresoRecuperacion){
    if(!$Documento && !$TipoDocumento && !$Responsable){
		  $TipoDocumento=$_REQUEST['TipoDocumento'];$Documento=$_REQUEST['Documento'];$Responsable=$_REQUEST['Responsable'];
      $cirujano=$_REQUEST['cirujano'];$anestesista=$_REQUEST['anestesista'];$quirofano=$_REQUEST['quirofano'];
	    $FechaInicial=$_REQUEST['FechaInicial'];$HoraIni=$_REQUEST['HoraIni'];$MinIni=$_REQUEST['MinIni'];
			$FechaFinal=$_REQUEST['FechaFinal'];$HoraFin=$_REQUEST['HoraFin'];$MinFin=$_REQUEST['MinFin'];
			$viaAcceso=$_REQUEST['viaAcceso'];$tipoCirugia=$_REQUEST['tipoCirugia'];$ambitoCirugia=$_REQUEST['ambitoCirugia'];
			$circulante1=$_REQUEST['circulante1'];$circulante2=$_REQUEST['circulante2'];$instrumentista=$_REQUEST['instrumentista'];
			$gasAnestesico=$_REQUEST['gasAnestesico'];$gasAnestesicoMe=$_REQUEST['gasAnestesicoMe'];
			$destino=$_REQUEST['destino'];$cargo=$_REQUEST['cargo'];$codigo=$_REQUEST['codigo'];
			$cargo1=$_REQUEST['cargo1'];$codigo1=$_REQUEST['codigo1'];$TipoAnestesia=$_REQUEST['TipoAnestesia'];
			$FechaIncioGas=$_REQUEST['FechaIncioGas'];$HoraInicioGas=$_REQUEST['HoraInicioGas'];
			$MinutosInicioGas=$_REQUEST['MinutosInicioGas'];$FechaFinGas=$_REQUEST['FechaFinGas'];$HoraFinGas=$_REQUEST['HoraFinGas'];
			$MinutosFinGas=$_REQUEST['MinutosFinGas'];$estadoSalida=$_REQUEST['estadoSalida'];$protocolo=$_REQUEST['protocolo'];
			$FechaIngresoRecuperacion=$_REQUEST['FechaIngresoRecuperacion'];$HoraIngresoRecuperacion=$_REQUEST['HoraIngresoRecuperacion'];
			$MinutosIngresoRecuperacion=$_REQUEST['MinutosIngresoRecuperacion'];$FechaEgresoRecuperacion=$_REQUEST['FechaEgresoRecuperacion'];
	    $HoraEgresoRecuperacion=$_REQUEST['HoraEgresoRecuperacion'];$MinutosEgresoRecuperacion=$_REQUEST['MinutosEgresoRecuperacion'];
	  }
    $cadena=explode('/',$FechaInicial);
    $fechaIn=$cadena[2].'-'.$cadena[1].'-'.$cadena[0].' '.$HoraIni.':'.$MinIni;
    $cadena=explode('/',$FechaFinal);
		$fechaFn=$cadena[2].'-'.$cadena[1].'-'.$cadena[0].' '.$HoraFin.':'.$MinIni;
    $cadena=explode('/',$cirujano);
		$numeroCiru=$cadena[0];
    $tipoCiru=$cadena[1];
		//DATOS NO PRINCIPALES
		//QUIROFANO
		if($quirofano==-1){$quirofano1='NULL';}else{$quirofano1="'$quirofano'";}
		//PROGRAMACION
    if($_SESSION['EJECUCION']['CIRUGIAS']['CODIGO']){$program=$_SESSION['EJECUCION']['CIRUGIAS']['CODIGO'];
      $program1="'$program'";}else{$program1='NULL';}
    //ANESTESIOLOGO
		if($anestesista==-1){$numeroAnes1='NULL';$tipoAnes1='NULL';}
		else{$cadena=explode('/',$anestesista);$numeroAnes=$cadena[0];
		  $numeroAnes1="'$numeroAnes'";$tipoAnes=$cadena[1];$tipoAnes1="'$tipoAnes'";
		}
		//CIRCULANTE UNO
		if($circulante1==-1){$numeroCircul11='NULL';$tipoCircul11='NULL';}
		else{$cadena=explode('/',$circulante1);$numeroCircul1=$cadena[0];
		  $numeroCircul11="'$numeroCircul1'";$tipoCircul1=$cadena[1];$tipoCircul11="'$tipoCircul1'";
		}
		//CIRCULANTE DOS
		if($circulante2==-1){$numeroCircul21='NULL';$tipoCircul21='NULL';}
		else{$cadena=explode('/',$circulante2);$numeroCircul2=$cadena[0];
		  $numeroCircul21="'$numeroCircul2'";$tipoCircul2=$cadena[1];$tipoCircul21="'$tipoCircul2'";
		}
		//INTRUMENTISTA
		if($instrumentista==-1){$numeroInstru1='NULL';$tipoInstru1='NULL';}
		else{$cadena=explode('/',$instrumentista);$numeroInstru=$cadena[0];
		  $numeroInstru1="'$numeroInstru'";$tipoInstru=$cadena[1];$tipoInstru1="'$tipoInstru'";
		}
		//GAS ANESTESICO
		if($gasAnestesico==-1){$gasAnestesico1='NULL';}else{$gasAnestesico1="'$gasAnestesico'";}
    //GAS MEDICINAL
		if($gasAnestesicoMe==-1){$gasAnestesicoMe1='NULL';}else{$gasAnestesicoMe1="'$gasAnestesicoMe'";}
		//DIAGNOSTICO
		if(!$codigo){$diagnostico='NULL';}else{$diagnostico="'$codigo'";}
		//COMPLICACION
    if(!$codigo1){$complicacion='NULL';}else{$complicacion="'$codigo1'";}
		//TIPO ANESTESIA
    if($TipoAnestesia==-1){$tipoAnestesia='NULL';}else{$tipoAnestesia="'$TipoAnestesia'";}
    //ESTADO SALIDA
    if($estadoSalida==1){$EstadoSalida='v';}elseif($estadoSalida==2){$EstadoSalida='m';}
		//TIPO PROTOCOLO
    if($protocolo==-1){$Protocolo='NULL';}else{$Protocolo="'$protocolo'";}
    //INICIO GAS
		if(!$FechaIncioGas){
		  $FechaInGas='NULL';
		}else{
      $cadenaFecha=explode('/',$FechaIncioGas);
      $Fecha=$cadenaFecha[2].'-'.$cadenaFecha[1].'-'.$cadenaFecha[0];
			$FechaInGas=$Fecha.' '.$HoraInicioGas.':'.$MinutosInicioGas;
      $FechaInGas="'$FechaInGas'";
		}
		//FIN GAS
		if(!$FechaFinGas){
      $FechaFnGas='NULL';
		}else{
      $cadenaFecha=explode('/',$FechaFinGas);
      $Fecha=$cadenaFecha[2].'-'.$cadenaFecha[1].'-'.$cadenaFecha[0];
			$FechaFnGas=$Fecha.' '.$HoraFinGas.':'.$MinutosFinGas;
			$FechaFnGas="'$FechaFnGas'";
		}
		//INICIO RECUPERACION
		if(!$FechaIngresoRecuperacion){
      $FechaInRecuperacion='NULL';
		}else{
      $cadenaFecha=explode('/',$FechaIngresoRecuperacion);
      $Fecha=$cadenaFecha[2].'-'.$cadenaFecha[1].'-'.$cadenaFecha[0];
			$FechaInRecuperacion=$Fecha.' '.$HoraIngresoRecuperacion.':'.$MinutosIngresoRecuperacion;
      $FechaInRecuperacion="'$FechaInRecuperacion'";
		}
		//FIN RECUPERACION
		if(!$FechaEgresoRecuperacion){
      $FechaEgRecuperacion='NULL';
		}else{
      $cadenaFecha=explode('/',$FechaEgresoRecuperacion);
      $Fecha=$cadenaFecha[2].'-'.$cadenaFecha[1].'-'.$cadenaFecha[0];
			$FechaEgRecuperacion=$Fecha.' '.$HoraEgresoRecuperacion.':'.$MinutosEgresoRecuperacion;
      $FechaEgRecuperacion="'$FechaEgRecuperacion'";
		}

		list($dbconn) = GetDBconn();
		if(!$_SESSION['EJECUCION']['CIRUGIAS']['ACTO']){
			$query="SELECT nextval('qx_acto_qx_acto_id_seq')";
			$result=$dbconn->Execute($query);
			$acto=$result->fields[0];
			$query="INSERT INTO qx_acto(qx_acto_id,quirofano_id,fecha_hora_inicio,fecha_hora_final,
			tipo_id_cirujano,cirujano_principal,qx_programacion_id,tipo_id_anestesiologo,
			anestesiologo,paciente_id,tipo_id_paciente,tipo_id_circulante_uno,
			circulante_uno,tipo_id_circulante_dos,circulante_dos,tipo_id_instrumentista,
			instrumentista,gas_anestesico,gas_medicinal,via_acceso,plan_id,tipo_cirugia_id,ambito_cirugia_id,
			departamento,fecha_registro,usuario_id,diagnostico_id,complicacion_id,tipo_anestesia,fecha_inicio_anestesia,
			fecha_fin_anestesia,fecha_ingreso_recuperacion,fecha_egreso_recuperacion,sw_estado_salida,protocolo
			)VALUES(
			'$acto',$quirofano1,'$fechaIn','$fechaFn','$tipoCiru','$numeroCiru',$program1,$tipoAnes1,
			$numeroAnes1,'$Documento','$TipoDocumento',
			NULL,NULL,NULL,NULL,NULL,NULL,$gasAnestesico1,$gasAnestesicoMe1,'$viaAcceso','$Responsable',
			'$tipoCirugia','$ambitoCirugia','".$_SESSION['LocalCirugias']['Departamento']."',
			'".date("Y-m-d H:i:s")."','".UserGetUID()."',$diagnostico,$complicacion,$tipoAnestesia,
			$FechaInGas,$FechaFnGas,$FechaInRecuperacion,$FechaEgRecuperacion,'$EstadoSalida',$Protocolo)";
			//$tipoCircul11,$numeroCircul11,$tipoCircul21,$numeroCircul21,$tipoInstru1,$numeroInstru1
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$_SESSION['EJECUCION']['CIRUGIAS']['ACTO']=$acto;
		}else{
      $query="UPDATE qx_acto SET quirofano_id=$quirofano1,fecha_hora_inicio='$fechaIn',
			fecha_hora_final='$fechaFn',tipo_id_cirujano='$tipoCiru',cirujano_principal='$numeroCiru',
			qx_programacion_id=$program1,tipo_id_anestesiologo=$tipoAnes1,anestesiologo=$numeroAnes1,
			paciente_id='$Documento',tipo_id_paciente='$TipoDocumento',
			tipo_id_circulante_uno=NULL,circulante_uno=NULL,tipo_id_circulante_dos=NULL,
			circulante_dos=NULL,tipo_id_instrumentista=NULL,instrumentista=NULL,
			gas_anestesico=$gasAnestesico1,gas_medicinal=$gasAnestesico1,via_acceso='$viaAcceso',
			plan_id='$Responsable',tipo_cirugia_id='$tipoCirugia',ambito_cirugia_id='$ambitoCirugia',
			departamento='".$_SESSION['LocalCirugias']['Departamento']."',
			diagnostico_id=$diagnostico,complicacion_id=$complicacion,tipo_anestesia=$tipoAnestesia,
			fecha_inicio_anestesia=$FechaInGas,fecha_fin_anestesia=$FechaFnGas,
			fecha_ingreso_recuperacion=$FechaInRecuperacion,fecha_egreso_recuperacion=$FechaEgRecuperacion,
			sw_estado_salida='$EstadoSalida',protocolo=$Protocolo WHERE qx_acto_id='".$_SESSION['EJECUCION']['CIRUGIAS']['ACTO']."'";
			/*tipo_id_circulante_uno=$tipoCircul11,circulante_uno=$numeroCircul11,tipo_id_circulante_dos=$tipoCircul21,
			circulante_dos=$numeroCircul21,tipo_id_instrumentista=$tipoInstru1,instrumentista=$numeroInstru1,
			gas_anestesico=$gasAnestesico1,gas_medicinal=$gasAnestesico1,
      */
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		$this->FormaEjecucionCirugia($TipoDocumento,$Documento,$Responsable,$cirujano,$anestesista,$quirofano,
		$FechaInicial,$HoraIni,$MinIni,$FechaFinal,$HoraFin,$MinFin,$viaAcceso,$tipoCirugia,$ambitoCirugia,$circulante1,$circulante2,
		$instrumentista,$gasAnestesico,$gasAnestesicoMe,'','','','','',$destino,$cargo,$codigo,$cargo1,$codigo1,$TipoAnestesia,
		$FechaIncioGas,$HoraInicioGas,$MinutosInicioGas,$FechaFinGas,$HoraFinGas,$MinutosFinGas,$estadoSalida,$protocolo,
		$FechaIngresoRecuperacion,$HoraIngresoRecuperacion,$MinutosIngresoRecuperacion,$FechaEgresoRecuperacion,
		$HoraEgresoRecuperacion,$MinutosEgresoRecuperacion);
		return true;
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

	function ComprobarIgualdadPlan($acto,$ProductosEjecucion){
    list($dbconn) = GetDBconn();
		for($i=0;$i<sizeof($ProductosEjecucion);$i++){
      $query="SELECT plan_id FROM qx_acto_procedimientos_realizados WHERE qx_acto_id='$acto' AND procedimiento_qx='".$ProductosEjecucion[$i]."'";
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
			if($i==0){
			  $planEs=$vars['plan_id'];
			}else{
        if($vars['plan_id']!=$planEs){
          return 0;
				}
			}
		}
		return 1;
	}

	function SalirFormaEjecucionProcedimientos(){
    if($_REQUEST['Salir']){
		  unset($_SESSION['EJECUCION']['CIRUGIAS']['ACTO']);
			unset($_SESSION['EJECUCION']['CIRUGIAS']['CODIGO']);
			if($_REQUEST['destino']){
        $this->BusquedaConsultaCumplimiento();
			}else{
		    $this->FormaBusquedaProgramacion();
			}
			return true;
		}

		if($_REQUEST['Ejecucion']){
		  $comprobar=$this->ComprobarIgualdadPlan($_SESSION['EJECUCION']['CIRUGIAS']['ACTO'],$_REQUEST['paraEjecucion']);
			if(!$comprobar || (sizeof($_REQUEST['paraEjecucion'])<1)){
			  $this->frmError["MensajeError"]="Seleccione los Procedimientos Agrupados por el Plan";
        $this->FormaEjecucionCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cirujano'],$_REQUEST['anestesista'],$_REQUEST['quirofano'],
				$_REQUEST['FechaInicial'],$_REQUEST['HoraIni'],$_REQUEST['MinIni'],$_REQUEST['FechaFinal'],$_REQUEST['HoraFin'],$_REQUEST['MinFin'],$_REQUEST['viaAcceso'],$_REQUEST['tipoCirugia'],$_REQUEST['ambitoCirugia'],$_REQUEST['circulante1'],$_REQUEST['circulante2'],
				$_REQUEST['instrumentista'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],'','','','','',$_REQUEST['destino'],
				$_REQUEST['cargo'],$_REQUEST['codigo'],$_REQUEST['cargo1'],$_REQUEST['codigo1'],$_REQUEST['TipoAnestesia'],$_REQUEST['FechaIncioGas'],$_REQUEST['HoraInicioGas'],$_REQUEST['MinutosInicioGas'],$_REQUEST['FechaFinGas'],$_REQUEST['HoraFinGas'],$_REQUEST['MinutosFinGas'],
				$_REQUEST['estadoSalida'],$_REQUEST['protocolo'],$_REQUEST['FechaIngresoRecuperacion'],$_REQUEST['HoraIngresoRecuperacion'],$_REQUEST['MinutosIngresoRecuperacion'],$_REQUEST['FechaEgresoRecuperacion'],$_REQUEST['HoraEgresoRecuperacion'],
				$_REQUEST['MinutosEgresoRecuperacion']);
				return true;
			}
      $this->LiquidacionTarifCirugia($_SESSION['EJECUCION']['CIRUGIAS']['ACTO'],$_REQUEST['paraEjecucion']);
			return true;
		}

		list($dbconn) = GetDBconn();
		if($_REQUEST['SalirsinGuardar']){
		  if($_SESSION['EJECUCION']['CIRUGIAS']['ACTO']){
		  $query="DELETE FROM qx_acto_procedimientos_realizados WHERE qx_acto_id='".$_SESSION['EJECUCION']['CIRUGIAS']['ACTO']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
        $query="DELETE FROM qx_acto WHERE qx_acto_id='".$_SESSION['EJECUCION']['CIRUGIAS']['ACTO']."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
			}
			unset($_SESSION['EJECUCION']['CIRUGIAS']['ACTO']);
			unset($_SESSION['EJECUCION']['CIRUGIAS']['CODIGO']);
			if($_REQUEST['destino']){
        $this->BusquedaConsultaCumplimiento();
			}else{
			  $this->FormaBusquedaProgramacion();
			}
			return true;
		}
		if($_REQUEST['insertar']){
      if(!$_SESSION['EJECUCION']['CIRUGIAS']['ACTO']){
        $this->frmError["MensajeError"]="Debe Insertar Primero los Datos Principales de la Ejecucion";
        $this->FormaEjecucionCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cirujano'],$_REQUEST['anestesista'],$_REQUEST['quirofano'],
				$_REQUEST['FechaInicial'],$_REQUEST['HoraIni'],$_REQUEST['MinIni'],$_REQUEST['FechaFinal'],$_REQUEST['HoraFin'],$_REQUEST['MinFin'],$_REQUEST['viaAcceso'],$_REQUEST['tipoCirugia'],$_REQUEST['ambitoCirugia'],$_REQUEST['circulante1'],$_REQUEST['circulante2'],
				$_REQUEST['instrumentista'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],'','','','','',$_REQUEST['destino'],
				$_REQUEST['cargo'],$_REQUEST['codigo'],$_REQUEST['cargo1'],$_REQUEST['codigo1'],$_REQUEST['TipoAnestesia'],$_REQUEST['FechaIncioGas'],$_REQUEST['HoraInicioGas'],$_REQUEST['MinutosInicioGas'],$_REQUEST['FechaFinGas'],$_REQUEST['HoraFinGas'],$_REQUEST['MinutosFinGas'],
				$_REQUEST['estadoSalida'],$_REQUEST['protocolo'],$_REQUEST['FechaIngresoRecuperacion'],$_REQUEST['HoraIngresoRecuperacion'],$_REQUEST['MinutosIngresoRecuperacion'],$_REQUEST['FechaEgresoRecuperacion'],$_REQUEST['HoraEgresoRecuperacion'],
				$_REQUEST['MinutosEgresoRecuperacion']);
				return true;
			}
			if(!$_REQUEST['codigos'] || !$_REQUEST['procedimiento'] || $_REQUEST['cirujanoPro']==-1 || $_REQUEST['ResponsablePro']==-1){
				if(!$_REQUEST['codigos']){$this->frmError["procedimiento"]=1;}
				if(!$_REQUEST['procedimiento']){$this->frmError["procedimiento"]=1;}
				if($_REQUEST['cirujanoPro']==-1){$this->frmError["cirujanoPro"]=1;}
				if($_REQUEST['ResponsablePro']==-1){$this->frmError["ResponsablePro"]=1;}
				$this->frmError["MensajeError"]="Faltan Datos Obligatorios";
				$this->FormaEjecucionCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cirujano'],$_REQUEST['anestesista'],$_REQUEST['quirofano'],
				$_REQUEST['FechaInicial'],$_REQUEST['HoraIni'],$_REQUEST['MinIni'],$_REQUEST['FechaFinal'],$_REQUEST['HoraFin'],$_REQUEST['MinFin'],$_REQUEST['viaAcceso'],$_REQUEST['tipoCirugia'],$_REQUEST['ambitoCirugia'],$_REQUEST['circulante1'],$_REQUEST['circulante2'],
				$_REQUEST['instrumentista'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],
        $_REQUEST['codigos'],$_REQUEST['procedimiento'],$_REQUEST['cirujanoPro'],$_REQUEST['ayudante'],$_REQUEST['ResponsablePro'],'','','','','',$_REQUEST['destino'],
				$_REQUEST['cargo'],$_REQUEST['codigo'],$_REQUEST['cargo1'],$_REQUEST['codigo1'],$_REQUEST['TipoAnestesia'],$_REQUEST['FechaIncioGas'],$_REQUEST['HoraInicioGas'],$_REQUEST['MinutosInicioGas'],$_REQUEST['FechaFinGas'],$_REQUEST['HoraFinGas'],$_REQUEST['MinutosFinGas'],
				$_REQUEST['estadoSalida'],$_REQUEST['protocolo'],$_REQUEST['FechaIngresoRecuperacion'],$_REQUEST['HoraIngresoRecuperacion'],$_REQUEST['MinutosIngresoRecuperacion'],$_REQUEST['FechaEgresoRecuperacion'],$_REQUEST['HoraEgresoRecuperacion'],
				$_REQUEST['MinutosEgresoRecuperacion']);
				return true;
			}
			$confirmar=$this->verificarExistenciaProc($_SESSION['EJECUCION']['CIRUGIAS']['ACTO'],$_REQUEST['codigos']);
			if($confirmar==1){
        $this->frmError["MensajeError"]="Imposible Insertar este Procedimiento, Ya lo Inserto Anteriormente";
				$this->FormaEjecucionCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cirujano'],$_REQUEST['anestesista'],$_REQUEST['quirofano'],
				$_REQUEST['FechaInicial'],$_REQUEST['HoraIni'],$_REQUEST['MinIni'],$_REQUEST['FechaFinal'],$_REQUEST['HoraFin'],$_REQUEST['MinFin'],$_REQUEST['viaAcceso'],$_REQUEST['tipoCirugia'],$_REQUEST['ambitoCirugia'],$_REQUEST['circulante1'],$_REQUEST['circulante2'],
				$_REQUEST['instrumentista'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],
        $_REQUEST['codigos'],$_REQUEST['procedimiento'],$_REQUEST['cirujanoPro'],$_REQUEST['ayudante'],$_REQUEST['ResponsablePro'],$_REQUEST['destino'],
				$_REQUEST['cargo'],$_REQUEST['codigo'],$_REQUEST['cargo1'],$_REQUEST['codigo1'],$_REQUEST['TipoAnestesia'],$_REQUEST['FechaIncioGas'],$_REQUEST['HoraInicioGas'],$_REQUEST['MinutosInicioGas'],$_REQUEST['FechaFinGas'],$_REQUEST['HoraFinGas'],$_REQUEST['MinutosFinGas'],
				$_REQUEST['estadoSalida'],$_REQUEST['protocolo'],$_REQUEST['FechaIngresoRecuperacion'],$_REQUEST['HoraIngresoRecuperacion'],$_REQUEST['MinutosIngresoRecuperacion'],$_REQUEST['FechaEgresoRecuperacion'],$_REQUEST['HoraEgresoRecuperacion'],
				$_REQUEST['MinutosEgresoRecuperacion']);
				return true;
			}
			$cadenaCiru=explode('/',$_REQUEST['cirujanoPro']);

			if($_REQUEST['ordenid']){
        $ordenid=$_REQUEST['ordenid'];
				$ordenid1="'$ordenid'";
			}else{
        $ordenid1='NULL';
			}
			if($_REQUEST['ayudante']!=-1){
				$cadenaAyu=explode('/',$_REQUEST['ayudante']);
				$tipoAyu=$cadenaAyu[1];$tipoAyu1="'$tipoAyu'";
				$Ayud=$cadenaAyu[0];$Ayud1="'$Ayud'";
			}else{
        $tipoAyu1='NULL';
        $Ayud1='NULL';
			}
			$query="INSERT INTO qx_acto_procedimientos_realizados(qx_acto_id,
			                                                      procedimiento_qx,
																														tipo_id_ayudante,
																														ayudante_id,
																														tipo_id_cirujano,
																														cirujano_id,
																														orden_id,
																														departamento,
																														plan_id) VALUES(
																														'".$_SESSION['EJECUCION']['CIRUGIAS']['ACTO']."',
																														'".$_REQUEST['codigos']."',
                                                            $tipoAyu1,
                                                            $Ayud1,
																														'$cadenaCiru[1]',
																														'$cadenaCiru[0]',
																														$ordenid1,
																														'".$_SESSION['LocalCirugias']['Departamento']."',
																														'".$_REQUEST['ResponsablePro']."')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$this->FormaEjecucionCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cirujano'],$_REQUEST['anestesista'],$_REQUEST['quirofano'],
			$_REQUEST['FechaInicial'],$_REQUEST['HoraIni'],$_REQUEST['MinIni'],$_REQUEST['FechaFinal'],$_REQUEST['HoraFin'],$_REQUEST['MinFin'],$_REQUEST['viaAcceso'],$_REQUEST['tipoCirugia'],$_REQUEST['ambitoCirugia'],$_REQUEST['circulante1'],$_REQUEST['circulante2'],
			$_REQUEST['instrumentista'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],'','','','','',$_REQUEST['destino'],
			$_REQUEST['cargo'],$_REQUEST['codigo'],$_REQUEST['cargo1'],$_REQUEST['codigo1'],$_REQUEST['TipoAnestesia'],$_REQUEST['FechaIncioGas'],$_REQUEST['HoraInicioGas'],$_REQUEST['MinutosInicioGas'],$_REQUEST['FechaFinGas'],$_REQUEST['HoraFinGas'],$_REQUEST['MinutosFinGas'],
			$_REQUEST['estadoSalida'],$_REQUEST['protocolo'],$_REQUEST['FechaIngresoRecuperacion'],$_REQUEST['HoraIngresoRecuperacion'],$_REQUEST['MinutosIngresoRecuperacion'],$_REQUEST['FechaEgresoRecuperacion'],$_REQUEST['HoraEgresoRecuperacion'],
			$_REQUEST['MinutosEgresoRecuperacion']);
			return true;
		}
		if($_REQUEST['cancelar']){
      $this->FormaEjecucionCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cirujano'],$_REQUEST['anestesista'],$_REQUEST['quirofano'],
			$_REQUEST['FechaInicial'],$_REQUEST['HoraIni'],$_REQUEST['MinIni'],$_REQUEST['FechaFinal'],$_REQUEST['HoraFin'],$_REQUEST['MinFin'],$_REQUEST['viaAcceso'],$_REQUEST['tipoCirugia'],$_REQUEST['ambitoCirugia'],$_REQUEST['circulante1'],$_REQUEST['circulante2'],
			$_REQUEST['instrumentista'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],'','','','','',$_REQUEST['destino'],
			$_REQUEST['cargo'],$_REQUEST['codigo'],$_REQUEST['cargo1'],$_REQUEST['codigo1'],$_REQUEST['TipoAnestesia'],$_REQUEST['FechaIncioGas'],$_REQUEST['HoraInicioGas'],$_REQUEST['MinutosInicioGas'],$_REQUEST['FechaFinGas'],$_REQUEST['HoraFinGas'],$_REQUEST['MinutosFinGas'],
			$_REQUEST['estadoSalida'],$_REQUEST['protocolo'],$_REQUEST['FechaIngresoRecuperacion'],$_REQUEST['HoraIngresoRecuperacion'],$_REQUEST['MinutosIngresoRecuperacion'],$_REQUEST['FechaEgresoRecuperacion'],$_REQUEST['HoraEgresoRecuperacion'],
			$_REQUEST['MinutosEgresoRecuperacion']);
			return true;
		}
	}
/**
* Funcion que busca los profesionales Ayudantes existentes en la base de datos
* @return array
*/
	function profesionalesAyudantes(){
    $departamento=$_SESSION['LocalCirugias']['Departamento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT x.tercero_id,z.nombre_tercero as nombre,x.tipo_id_tercero FROM profesionales x,profesionales_departamentos y,terceros z WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id AND y.departamento='".$_SESSION['LocalCirugias']['Departamento']."' AND x.tercero_id=z.tercero_id AND x.tipo_id_tercero=z.tipo_id_tercero AND profesional_activo(z.tipo_id_tercero,z.tercero_id,'".$_SESSION['LocalCirugias']['Departamento']."')='1'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
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

	function ProcedimientosEjecucionQX($acto){
		list($dbconn) = GetDBconn();
		$query ="SELECT a.procedimiento_qx,b.descripcion,c.nombre,d.plan_descripcion FROM qx_acto_procedimientos_realizados a,cups b,profesionales c,planes d
		WHERE a.qx_acto_id='$acto' AND a.departamento='".$_SESSION['LocalCirugias']['Departamento']."' AND a.procedimiento_qx=b.cargo AND
		a.tipo_id_cirujano=c.tipo_id_tercero AND a.cirujano_id=c.tercero_id AND a.plan_id=d.plan_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
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

	function EliminaProcedimientoEj(){
    list($dbconn) = GetDBconn();
		$query ="DELETE FROM qx_acto_procedimientos_realizados WHERE qx_acto_id='".$_SESSION['EJECUCION']['CIRUGIAS']['ACTO']."' AND procedimiento_qx='".$_REQUEST['codigoProc']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->FormaEjecucionCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cirujano'],$_REQUEST['anestesista'],$_REQUEST['quirofano'],
		$_REQUEST['FechaInicial'],$_REQUEST['HoraIni'],$_REQUEST['MinIni'],$_REQUEST['FechaFinal'],$_REQUEST['HoraFin'],$_REQUEST['MinFin'],$_REQUEST['viaAcceso'],$_REQUEST['tipoCirugia'],$_REQUEST['ambitoCirugia'],$_REQUEST['circulante1'],$_REQUEST['circulante2'],
		$_REQUEST['instrumentista'],$_REQUEST['gasAnestesico'],$_REQUEST['gasAnestesicoMe'],'','','','','',$_REQUEST['destino'],
		$_REQUEST['cargo'],$_REQUEST['codigo'],$_REQUEST['cargo1'],$_REQUEST['codigo1'],$_REQUEST['TipoAnestesia'],$_REQUEST['FechaIncioGas'],$_REQUEST['HoraInicioGas'],$_REQUEST['MinutosInicioGas'],$_REQUEST['FechaFinGas'],$_REQUEST['HoraFinGas'],$_REQUEST['MinutosFinGas'],
		$_REQUEST['estadoSalida'],$_REQUEST['protocolo'],$_REQUEST['FechaIngresoRecuperacion'],$_REQUEST['HoraIngresoRecuperacion'],$_REQUEST['MinutosIngresoRecuperacion'],$_REQUEST['FechaEgresoRecuperacion'],$_REQUEST['HoraEgresoRecuperacion'],
		$_REQUEST['MinutosEgresoRecuperacion']);
		return true;
	}

/**
* Funcion que consulta en la B.D. la descripcion y la abreviarura de un quirofano a partir de su codigo de identificacion
* @return array
* @param integer numero que identifica al quirofano
*/
	function DescripcionQuirofano($quirofano){
    list($dbconn) = GetDBconn();
		$query="SELECT descripcion,abreviatura FROM qx_quirofanos WHERE quirofano='$quirofano'";
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
		$this->ProcedimientosQuirurgicos($_REQUEST['TipoId'],$_REQUEST['Documento'],$_REQUEST['Responsable'],$_REQUEST['cuenta'],$var['cargo_cups'],$procedimientoDes['descripcion'],'','',$var['plan_id'],$var['numero_orden_id']);
		return true;
	}

	function verificarExistenciaProc($acto,$procedimiento){
    list($dbconn) = GetDBconn();
		$query = "SELECT * FROM qx_acto_procedimientos_realizados WHERE qx_acto_id='$acto' AND procedimiento_qx='$procedimiento'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
			  $result->Close();
				return 1;
			}else{
			  $result->Close();
        return 0;
			}
		}
	}

/**
* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
* @return array
*/
  function TiposGasesAnestesicos($tipoGas){

		list($dbconn) = GetDBconn();
		$query = "SELECT  gas_anestesia,descripcion FROM qx_gases_anestesia WHERE tipo_gas_anestesia='$tipoGas' ";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'diagnosticos' esta vacia ";
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
* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
* @return array
*/
  function TiposDeAnestesias(){

		list($dbconn) = GetDBconn();
		$query = "SELECT qx_tipo_anestesia_id,descripcion,sw_uso_gases FROM qx_tipos_anestesia";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
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
				$this->mensajeDeError = "La tabla 'diagnosticos' esta vacia ";
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


 function HallarProcedimientosProgram(){
    list($dbconn) = GetDBconn();
    $query = "SELECT a.procedimiento_qx,a.tipo_id_cirujano,a.cirujano_id,a.tipo_id_ayudante,a.ayudante_id,b.descripcion,d.numero_orden_id,f.tipo_cirugia_id,f.ambito_cirugia_id,f.finalidad_procedimiento_id,g.plan_id,
		h.plan_descripcion,i.descripcion as tipcirugia,j.descripcion as amcirugia,k.descripcion as fincirugia
		FROM qx_procedimientos_programacion a
		LEFT JOIN qx_programaciones_ordenes  d ON (a.programacion_id=d.programacion_id AND a.procedimiento_qx=d.procedimiento_qx)
		LEFT JOIN os_maestro e ON (d.numero_orden_id=e.numero_orden_id)
		LEFT JOIN hc_os_solicitudes_procedimientos f ON (e.hc_os_solicitud_id=f.hc_os_solicitud_id)
		LEFT JOIN hc_os_solicitudes g ON (f.hc_os_solicitud_id=g.hc_os_solicitud_id)
    LEFT JOIN planes h ON(g.plan_id=h.plan_id)
    LEFT JOIN qx_tipos_cirugia i ON(f.tipo_cirugia_id=i.tipo_cirugia_id)
		LEFT JOIN qx_ambitos_cirugias j ON(f.ambito_cirugia_id=j.ambito_cirugia_id)
    LEFT JOIN qx_finalidades_procedimientos k ON(f.finalidad_procedimiento_id=k.finalidad_procedimiento_id),
		cups b,
		((SELECT procedimiento_qx FROM qx_procedimientos_programacion WHERE programacion_id='".$_SESSION['EJECUCION']['CIRUGIAS']['CODIGO']."')
		EXCEPT
		(SELECT procedimiento_qx FROM qx_acto_procedimientos_realizados WHERE
		qx_acto_id='".$_SESSION['EJECUCION']['CIRUGIAS']['ACTO']."')) c
		WHERE a.programacion_id='".$_SESSION['EJECUCION']['CIRUGIAS']['CODIGO']."' AND a.procedimiento_qx=c.procedimiento_qx AND a.procedimiento_qx=b.cargo";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
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
			return $vars;
		}
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
				$this->mensajeDeError = "La tabla 'vias_acceso_cx' esta vacia ";
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
	function TiposfinalidadesCirugia(){
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

	function EjecucionISS2oo1_2ooo($noCumplimiento){

    $query="SELECT * FROM (SELECT g.via_acceso,a.tipo_id_cirujano||' '||a.cirujano_id as cirujano,
		a.plan_id, b.cargo_base, b.tarifario_id, b.cargo, d.porcentaje AS por_plan, d.por_cobertura AS cob_plan , d.sw_descuento AS des_plan,  f.porcentaje AS por_excp, f.por_cobertura AS cob_excp, f.sw_descuento AS des_excp,z.grupo_tipo_cargo
		FROM qx_acto g,qx_acto_procedimientos_realizados a
		LEFT JOIN tarifarios_equivalencias b ON (a.procedimiento_qx=b.cargo_base)
		LEFT JOIN cups z ON (z.cargo=b.cargo_base)
		LEFT JOIN tarifarios_detalle c ON (b.tarifario_id=c.tarifario_id AND b.cargo=c.cargo)
		LEFT JOIN plan_tarifario d ON (d.plan_id=a.plan_id AND d.tarifario_id=c.tarifario_id AND d.grupo_tarifario_id=c.grupo_tarifario_id AND d.subgrupo_tarifario_id=c.subgrupo_tarifario_id)
		LEFT JOIN excepciones f ON (f.plan_id=a.plan_id AND b.tarifario_id=f.tarifario_id AND b.cargo=f.cargo AND f.sw_no_contratado='0')
		WHERE a.qx_acto_id='$noCumplimiento' AND a.qx_acto_id=g.qx_acto_id) as h";
		$queryCir=$query." LEFT JOIN qx_uvrs_grupos_cargos x ON(h.cargo = x.cargo AND x.tarifario_id=h.tarifario_id)
		LEFT JOIN qx_uvrs_valores y ON (y.tipo_cargo='DC' AND x.dc_uvrs > y.uvr_desde AND x.dc_uvrs <= y.uvr_hasta)
		WHERE h.por_plan is not null ORDER BY x.dc_uvrs DESC,h.cirujano ASC";
    $queryAne=$query." LEFT JOIN qx_uvrs_grupos_cargos x ON(h.cargo = x.cargo AND x.tarifario_id=h.tarifario_id)
		LEFT JOIN qx_uvrs_valores y ON (y.tipo_cargo='DA' AND x.da_uvrs > y.uvr_desde AND x.da_uvrs <= y.uvr_hasta)
		WHERE h.por_plan is not null ORDER BY x.da_uvrs DESC";
    $queryAyu=$query." LEFT JOIN qx_uvrs_grupos_cargos x ON(h.cargo = x.cargo AND x.tarifario_id=h.tarifario_id)
		LEFT JOIN qx_uvrs_valores y ON (y.tipo_cargo='DY' AND x.dy_uvrs > y.uvr_desde AND x.dy_uvrs <= y.uvr_hasta)
		WHERE h.por_plan is not null ORDER BY x.dy_uvrs DESC";
    $querySal=$query." LEFT JOIN qx_uvrs_grupos_cargos x ON(h.cargo = x.cargo AND x.tarifario_id=h.tarifario_id)
		LEFT JOIN qx_uvrs_valores y ON (y.tipo_cargo='DS' AND x.ds_uvrs > y.uvr_desde AND x.ds_uvrs <= y.uvr_hasta)
		WHERE h.por_plan is not null ORDER BY x.ds_uvrs DESC";
    $queryMat=$query." LEFT JOIN qx_uvrs_grupos_cargos x ON(h.cargo = x.cargo AND x.tarifario_id=h.tarifario_id)
		LEFT JOIN qx_uvrs_valores y ON (y.tipo_cargo='DM' AND x.dm_uvrs > y.uvr_desde AND x.dm_uvrs <= y.uvr_hasta)
		WHERE h.por_plan is not null ORDER BY x.dm_uvrs DESC";
    $orderCir=$this->EjecucionQueryDatos($queryCir);
    $orderAne=$this->EjecucionQueryDatos($queryAne);
		$orderAyu=$this->EjecucionQueryDatos($queryAyu);
		$orderSal=$this->EjecucionQueryDatos($querySal);
		$orderMat=$this->EjecucionQueryDatos($queryMat);
		$indicaNoEspecialistas=$this->NoEspecialistasCirugia($queryCir);
		for($i=0;$i<sizeof($orderCir);$i++){
		  if($orderCir[$i]['por_excp']){
        $porcentaje=$orderCir[$i]['por_excp'];
			}else{
        $porcentaje=$orderCir[$i]['por_plan'];
			}
			$porcentaje/=100;
      if($i==0){
				$vector[0][$i]=array("valorCir"=>(($orderCir[$i]['dc_uvrs']*$orderCir[$i]['valor'])*($porcentaje+1)),"proced"=>$orderCir[$i]['cargo_base']);
				$CirujanoPrimer=$orderCir[$i]['cirujano'];
				$primeraCir=0;
				//verificacion por si es un procedimiento de diagnostico o terapeutico
				if(($orderCir[$i]['via_acceso']=='BILA')&&($orderCir[$i]['grupo_tipo_cargo']!='QX')){
					$vector[0][$i]=array("valorCir"=>(($orderCir[$i]['valor'])*($porcentaje+1)),"proced"=>$orderCir[$i]['cargo_base']);
				}
			}else{
				if($orderCir[$i]['via_acceso']=='BILA'){
				  if($orderCir[$i]['grupo_tipo_cargo']=='QX'){
						if($i==1){
							$vector[0][$i]=array("valorCir"=>(($orderCir[$i]['dc_uvrs']*$orderCir[$i]['valor'])*($porcentaje+1)*0.75),"proced"=>$orderCir[$i]['cargo_base']);
						}
					}else{//Si es un procedimiento diagnostico o terapeutico
            if($i==1){
							$vector[0][$i]=array("valorCir"=>(($orderCir[$i]['valor'])*($porcentaje+1)),"proced"=>$orderCir[$i]['cargo_base']);
						}
					}
				}elseif($orderCir[$i]['via_acceso']=='IVIA'){
          if(sizeof($indicaNoEspecialistas)==1){
            if($i==1){
							$vector[0][$i]=array("valorCir"=>(($orderCir[$i]['dc_uvrs']*$orderCir[$i]['valor'])*($porcentaje+1)*0.60),"proced"=>$orderCir[$i]['cargo_base']);
						}
					}else{
						if(strcasecmp($CirujanoPrimer,$orderCir[$i]['cirujano'])){
							if(!in_array($orderCir[$i]['cirujano'],$vectorCirCobro1)){
								$vector[0][$i]=array("valorCir"=>(($orderCir[$i]['dc_uvrs']*$orderCir[$i]['valor'])*($porcentaje+1)),"proced"=>$orderCir[$i]['cargo_base']);
								$vectorCirCobro1[]=$orderCir[$i]['cirujano'];
							}elseif(!in_array($orderCir[$i]['cirujano'],$vectorCirCobro2)){
								$vector[0][$i]=array("valorCir"=>(($orderCir[$i]['dc_uvrs']*$orderCir[$i]['valor'])*($porcentaje+1)*0.60),"proced"=>$orderCir[$i]['cargo_base']);
								$vectorCirCobro2[]=$orderCir[$i]['cirujano'];
							}
						}else{
              if($primeraCir==0){
								$vector[0][$i]=array("valorCir"=>(($orderCir[$i]['dc_uvrs']*$orderCir[$i]['valor'])*($porcentaje+1)*0.60),"proced"=>$orderCir[$i]['cargo_base']);
								$CirujanoAnt=$orderCir[$i]['cirujano'];
								$primeraCir=1;
							}
						}
					}
				}elseif($orderCir[$i]['via_acceso']=='DVIA'){
          if(sizeof($indicaNoEspecialistas)==1){
					  $vector[0][$i]=array("valorCir"=>(($orderCir[$i]['dc_uvrs']*$orderCir[$i]['valor'])*($porcentaje+1)*0.75),"proced"=>$orderCir[$i]['cargo_base']);
					}else{
					  if(strcasecmp($CirujanoPrimer,$orderCir[$i]['cirujano'])){
						  if(!in_array($orderCir[$i]['cirujano'],$vectorCirCobro1)){
								$vector[0][$i]=array("valorCir"=>(($orderCir[$i]['dc_uvrs']*$orderCir[$i]['valor'])*($porcentaje+1)),"proced"=>$orderCir[$i]['cargo_base']);
								$vectorCirCobro1[]=$orderCir[$i]['cirujano'];
							}elseif(!in_array($orderCir[$i]['cirujano'],$vectorCirCobro2)){
								$vector[0][$i]=array("valorCir"=>(($orderCir[$i]['dc_uvrs']*$orderCir[$i]['valor'])*($porcentaje+1)*0.60),"proced"=>$orderCir[$i]['cargo_base']);
								$vectorCirCobro2[]=$orderCir[$i]['cirujano'];
							}
						}else{
							if($primeraCir==0){
								$vector[0][$i]=array("valorCir"=>(($orderCir[$i]['dc_uvrs']*$orderCir[$i]['valor'])*($porcentaje+1)*0.60),"proced"=>$orderCir[$i]['cargo_base']);
								$CirujanoAnt=$orderCir[$i]['cirujano'];
								$primeraCir=1;
							}
					  }
					}
				}
			}
		}
		for($i=0;$i<sizeof($orderAne);$i++){
			if($orderAne[$i]['por_excp']){
        $porcentaje=$orderAne[$i]['por_excp'];
			}else{
        $porcentaje=$orderAne[$i]['por_plan'];
			}
			$porcentaje/=100;

      if($i==0){
        $vector[1][$i]=array("valorAne"=>($orderAne[$i]['da_uvrs']*$orderAne[$i]['valor'])*($porcentaje+1),"proced"=>$orderAne[$i]['cargo_base']);
			}else{
        if($orderAne[$i]['via_acceso']=='BILA'){
				  if($i==1){
            $vector[1][$i]=array("valorAne"=>(($orderAne[$i]['da_uvrs']*$orderAne[$i]['valor'])*($porcentaje+1)*0.75),"proced"=>$orderAne[$i]['cargo_base']);
					}

				}elseif($orderAne[$i]['via_acceso']=='IVIA'){
					if(sizeof($indicaNoEspecialistas)==1){
						if($i==1){
							$vector[1][$i]=array("valorAne"=>(($orderAne[$i]['da_uvrs']*$orderAne[$i]['valor'])*($porcentaje+1)*0.60),"proced"=>$orderAne[$i]['cargo_base']);
						}
					}else{
            if($i==1){
              $vector[1][$i]=array("valorAne"=>(($orderAne[$i]['da_uvrs']*$orderAne[$i]['valor'])*($porcentaje+1)*0.75),"proced"=>$orderAne[$i]['cargo_base']);
						}
					}

				}elseif($orderAne[$i]['via_acceso']=='DVIA'){
          if(sizeof($indicaNoEspecialistas)==1){
					  $vector[1][$i]=array("valorAne"=>(($orderAne[$i]['da_uvrs']*$orderAne[$i]['valor'])*($porcentaje+1)*0.75),"proced"=>$orderAne[$i]['cargo_base']);
					}else{
            if($i==1){
              $vector[1][$i]=array("valorAne"=>(($orderAne[$i]['da_uvrs']*$orderAne[$i]['valor'])*($porcentaje+1)*0.75),"proced"=>$orderAne[$i]['cargo_base']);
						}
					}
				}
			}
		}
		for($i=0;$i<sizeof($orderAyu);$i++){
		  if($orderAyu[$i]['por_excp']){
        $porcentaje=$orderAyu[$i]['por_excp'];
			}else{
        $porcentaje=$orderAyu[$i]['por_plan'];
			}
			$porcentaje/=100;

      if($i==0){
        $vector[2][$i]=array("valorAyu"=>($orderAyu[$i]['dy_uvrs']*$orderAyu[$i]['valor'])*($porcentaje+1),"proced"=>$orderAyu[$i]['cargo_base']);
			}else{
			  if($orderAyu[$i]['via_acceso']=='BILA'){
				  if($i==1){
            $vector[2][$i]=array("valorAyu"=>(($orderAyu[$i]['dy_uvrs']*$orderAyu[$i]['valor'])*($porcentaje+1)*0.75),"proced"=>$orderAyu[$i]['cargo_base']);
					}

				}elseif($orderAyu[$i]['via_acceso']=='IVIA'){
					if(sizeof($indicaNoEspecialistas)==1){
						if($i==1){
							$vector[2][$i]=array("valorAyu"=>(($orderAyu[$i]['dy_uvrs']*$orderAyu[$i]['valor'])*($porcentaje+1)*0.60),"proced"=>$orderAyu[$i]['cargo_base']);
						}
					}else{
            if($i==1){
              $vector[2][$i]=array("valorAyu"=>(($orderAyu[$i]['dy_uvrs']*$orderAyu[$i]['valor'])*($porcentaje+1)*0.50),"proced"=>$orderAyu[$i]['cargo_base']);
						}
					}
				}elseif($orderAyu[$i]['via_acceso']=='DVIA'){
          if(sizeof($indicaNoEspecialistas)==1){
					  $vector[2][$i]=array("valorAyu"=>(($orderAyu[$i]['dy_uvrs']*$orderAyu[$i]['valor'])*($porcentaje+1)*0.75),"proced"=>$orderAyu[$i]['cargo_base']);
					}else{
            if($i==1){
              $vector[2][$i]=array("valorAyu"=>(($orderAyu[$i]['dy_uvrs']*$orderAyu[$i]['valor'])*($porcentaje+1)*0.50),"proced"=>$orderAyu[$i]['cargo_base']);
						}
					}
				}
			}
		}
		/***************************CAMBIA DEPENDIENDO DEL TARIFARIO ISS 2000 O ISS 2001 **************/
		for($i=0;$i<sizeof($orderSal);$i++){
		  if($orderSal[$i]['por_excp']){
        $porcentaje=$orderSal[$i]['por_excp'];
			}else{
        $porcentaje=$orderSal[$i]['por_plan'];
			}
			$porcentaje/=100;

      if($orderSal[$i]['ds_uvrs']<='450'){
				if($i==0){
					$vector[3][$i]=array("valorSal"=>(($orderSal[$i]['ds_uvrs']*$orderSal[$i]['valor'])*($porcentaje+1)),"proced"=>$orderSal[$i]['cargo_base']);
				}else{
					if($orderSal[$i]['via_acceso']=='BILA'){
						if($i==1){
							$vector[3][$i]=array("valorSal"=>(($orderSal[$i]['ds_uvrs']*$orderSal[$i]['valor'])*($porcentaje+1)*0.75),"proced"=>$orderSal[$i]['cargo_base']);
						}
					}elseif($orderSal[$i]['via_acceso']=='IVIA'){
					  if($Tarifario!=$dosmil){
							if($i==1){
								$vector[3][$i]=array("valorSal"=>(($orderSal[$i]['ds_uvrs']*$orderSal[$i]['valor'])*($porcentaje+1)*0.50),"proced"=>$orderSal[$i]['cargo_base']);
							}
						//Se utiliza el tarifario 2000
						}else{
						  if(sizeof($indicaNoEspecialistas)==1){
                if($i==1){
                  $vector[3][$i]=array("valorSal"=>(($orderSal[$i]['ds_uvrs']*$orderSal[$i]['valor'])*($porcentaje+1)*0.50),"proced"=>$orderSal[$i]['cargo_base']);
								}
							}else{
                $vector[3][$i]=array("valorSal"=>(($orderSal[$i]['ds_uvrs']*$orderSal[$i]['valor'])*($porcentaje+1)*0.50),"proced"=>$orderSal[$i]['cargo_base']);
							}
						}
					}elseif($orderSal[$i]['via_acceso']=='DVIA'){
					  if($Tarifario!=$dosmil){
							if(sizeof($indicaNoEspecialistas)==1){
								$vector[3][$i]=array("valorSal"=>(($orderSal[$i]['ds_uvrs']*$orderSal[$i]['valor'])*($porcentaje+1)*0.50),"proced"=>$orderSal[$i]['cargo_base']);
							}else{
								if($i==1){
									$vector[3][$i]=array("valorSal"=>(($orderSal[$i]['ds_uvrs']*$orderSal[$i]['valor'])*($porcentaje+1)*0.50),"proced"=>$orderSal[$i]['cargo_base']);
								}
							}
						//Se utiliza el tarifario 2000
						}else{
              $vector[3][$i]=array("valorSal"=>(($orderSal[$i]['ds_uvrs']*$orderSal[$i]['valor'])*($porcentaje+1)*0.50),"proced"=>$orderSal[$i]['cargo_base']);
						}
					}
				}
			}else{
			  $valorMult=$this->HallarValorTarifario($orderSal[$i]['tarifario_id']);
				$vector[3][$i]=array("valorSal"=>(($orderSal[$i]['ds_uvrs'] * $valorMult)*($porcentaje+1)),"proced"=>$orderSal[$i]['cargo_base']);
			}
		}

		for($i=0;$i<sizeof($orderMat);$i++){
		  if($orderMat[$i]['por_excp']){
        $porcentaje=$orderMat[$i]['por_excp'];
			}else{
        $porcentaje=$orderMat[$i]['por_plan'];
			}
			$porcentaje/=100;
      if($i==0){
			  if($orderMat[$i]['dm_uvrs']>170){
          $vector[4][$i]=array("valorMat"=>0,"proced"=>$orderMat[$i]['cargo_base']);
					$uvrsMayor=1;
				}else{
          $vector[4][$i]=array("valorMat"=>(($orderMat[$i]['dm_uvrs']*$orderMat[$i]['valor'])*($porcentaje+1)),"proced"=>$orderMat[$i]['cargo_base']);
				}
			}else{
        if(!$uvrsMayor){
					if($orderMat[$i]['via_acceso']=='BILA'){
						if($i==1){
							$vector[4][$i]=array("valorMat"=>(($orderMat[$i]['dm_uvrs']*$orderMat[$i]['valor'])*($porcentaje+1)*0.75),"proced"=>$orderMat[$i]['cargo_base']);
						}
					}elseif($orderMat[$i]['via_acceso']=='IVIA'){
					  if($Tarifario!=$dosmil){
							if($i==1){
								$vector[4][$i]=array("valorMat"=>(($orderMat[$i]['dm_uvrs']*$orderMat[$i]['valor'])*($porcentaje+1)*0.50),"proced"=>$orderMat[$i]['cargo_base']);
							}
						//Se utiliza el tarifario 2000
						}else{
              if(sizeof($indicaNoEspecialistas)==1){
							  if($i==1){
								  $vector[4][$i]=array("valorMat"=>(($orderMat[$i]['dm_uvrs']*$orderMat[$i]['valor'])*($porcentaje+1)*0.50),"proced"=>$orderMat[$i]['cargo_base']);
								}
							}else{
                $vector[4][$i]=array("valorMat"=>(($orderMat[$i]['dm_uvrs']*$orderMat[$i]['valor'])*($porcentaje+1)*0.50),"proced"=>$orderMat[$i]['cargo_base']);
							}
						}
					}elseif($orderMat[$i]['via_acceso']=='DVIA'){
					  if($Tarifario!=$dosmil){
							if(sizeof($indicaNoEspecialistas)==1){
								$vector[4][$i]=array("valorMat"=>(($orderMat[$i]['dm_uvrs']*$orderMat[$i]['valor'])*($porcentaje+1)*0.50),"proced"=>$orderMat[$i]['cargo_base']);
							}else{
								if($i==1){
									$vector[4][$i]=array("valorMat"=>(($orderMat[$i]['dm_uvrs']*$orderMat[$i]['valor'])*($porcentaje+1)*0.50),"proced"=>$orderMat[$i]['cargo_base']);
								}
							}
						//Se utiliza el tarifario 2000
						}else{
              $vector[4][$i]=array("valorMat"=>(($orderMat[$i]['dm_uvrs']*$orderMat[$i]['valor'])*($porcentaje+1)*0.50),"proced"=>$orderMat[$i]['cargo_base']);
						}
					}
				}else{
          $vector[4][$i]=array("valorMat"=>0,"proced"=>$orderMat[$i]['cargo_base']);
				}
			}
		}
	}

	function HallarValorTarifario($Tarifario){
    list($dbconn) = GetDBconn();
		$query="SELECT  valor FROM qx_uvrs_valor_sala_mas_450 WHERE tarifario_id='$Tarifario'";
		$result = $dbconn->Execute($query);
		return $result->fields[0];
	}

	function EjecucionQueryDatos($query){
	  list($dbconn) = GetDBconn();
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
        while(!$result->EOF){
					$datosCir[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $datosCir;
	}

	function NoEspecialistasCirugia($query){
    list($dbconn) = GetDBconn();
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
        while(!$result->EOF){
					$datosCir[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$cont=0;
		for($i=0;$i<sizeof($datosCir);$i++){
		  if($i==0){
				$vectorCirujano[$cont]=$datosCir[$i]['cirujano'];
        $cont++;
			}else{
			  if(!in_array($datosCir[$i]['cirujano'],$vectorCirujano)){
          $vectorCirujano[$cont]=$datosCir[$i]['cirujano'];
          $cont++;
				}
			}
		}
		return $vectorCirujano;
	}

	function EjecucionQxSOAT($noCumplimiento){

		list($dbconn) = GetDBconn();
    $query="SELECT x.via_acceso,a.tipo_id_cirujano||' '||a.cirujano_id as cirujano,a.plan_id,b.tarifario_id,
		b.cargo,b.cargo_base,c.qx_soat_grupo_id,c.dc_cargo,c.da_cargo,c.dy_cargo,c.ds_cargo,c.dm_cargo,d.precio,
		e.porcentaje AS por_plan, e.por_cobertura AS cob_plan , e.sw_descuento AS des_plan,
		f.porcentaje AS por_excp, f.por_cobertura AS cob_excp, f.sw_descuento AS des_excp
		FROM qx_acto x,qx_acto_procedimientos_realizados a
		LEFT JOIN tarifarios_equivalencias b ON(a.procedimiento_qx=b.cargo_base)
		LEFT JOIN qx_soat_grupos_cargos c ON (b.tarifario_id=c.tarifario_id AND b.cargo=c.cargo)";
    $queryCir="SELECT * FROM ($query LEFT JOIN tarifarios_detalle d ON (c.tarifario_id=d.tarifario_id AND c.dc_cargo=d.cargo)
		LEFT JOIN plan_tarifario e ON (e.plan_id=a.plan_id AND d.tarifario_id=e.tarifario_id AND d.grupo_tarifario_id=e.grupo_tarifario_id
		AND d.subgrupo_tarifario_id=e.subgrupo_tarifario_id)
		LEFT JOIN excepciones f ON (f.plan_id=a.plan_id AND d.tarifario_id=f.tarifario_id AND d.cargo=f.cargo AND f.sw_no_contratado='0')
		WHERE a.qx_acto_id='$noCumplimiento' AND a.qx_acto_id=x.qx_acto_id) as h WHERE h.por_plan is not null ORDER BY h.qx_soat_grupo_id,h.cirujano";
    $queryAne="SELECT * FROM ($query LEFT JOIN tarifarios_detalle d ON (c.tarifario_id=d.tarifario_id AND c.da_cargo=d.cargo)
		LEFT JOIN plan_tarifario e ON (e.plan_id=a.plan_id AND d.tarifario_id=e.tarifario_id AND d.grupo_tarifario_id=e.grupo_tarifario_id AND d.subgrupo_tarifario_id=e.subgrupo_tarifario_id)
		LEFT JOIN excepciones f ON (f.plan_id=a.plan_id AND d.tarifario_id=f.tarifario_id AND d.cargo=f.cargo AND f.sw_no_contratado='0')
		WHERE a.qx_acto_id='$noCumplimiento' AND a.qx_acto_id=x.qx_acto_id) as h WHERE h.por_plan is not null ORDER BY h.qx_soat_grupo_id,h.cirujano";
		$queryAyu="SELECT * FROM ($query LEFT JOIN tarifarios_detalle d ON (c.tarifario_id=d.tarifario_id AND c.dy_cargo=d.cargo)
		LEFT JOIN plan_tarifario e ON (e.plan_id=a.plan_id AND d.tarifario_id=e.tarifario_id AND d.grupo_tarifario_id=e.grupo_tarifario_id AND d.subgrupo_tarifario_id=e.subgrupo_tarifario_id)
		LEFT JOIN excepciones f ON (f.plan_id=a.plan_id AND d.tarifario_id=f.tarifario_id AND d.cargo=f.cargo AND f.sw_no_contratado='0')
		WHERE a.qx_acto_id='$noCumplimiento' AND a.qx_acto_id=x.qx_acto_id) as h WHERE h.por_plan is not null ORDER BY h.qx_soat_grupo_id,h.cirujano";
		$querySal="SELECT * FROM ($query LEFT JOIN tarifarios_detalle d ON (c.tarifario_id=d.tarifario_id AND c.ds_cargo=d.cargo)
		LEFT JOIN plan_tarifario e ON (e.plan_id=a.plan_id AND d.tarifario_id=e.tarifario_id AND d.grupo_tarifario_id=e.grupo_tarifario_id AND d.subgrupo_tarifario_id=e.subgrupo_tarifario_id)
		LEFT JOIN excepciones f ON (f.plan_id=a.plan_id AND d.tarifario_id=f.tarifario_id AND d.cargo=f.cargo AND f.sw_no_contratado='0')
		WHERE a.qx_acto_id='$noCumplimiento' AND a.qx_acto_id=x.qx_acto_id) as h WHERE h.por_plan is not null ORDER BY h.qx_soat_grupo_id,h.cirujano";
    $queryMat="SELECT * FROM ($query LEFT JOIN tarifarios_detalle d ON (c.tarifario_id=d.tarifario_id AND c.dm_cargo=d.cargo)
		LEFT JOIN plan_tarifario e ON (e.plan_id=a.plan_id AND d.tarifario_id=e.tarifario_id AND d.grupo_tarifario_id=e.grupo_tarifario_id AND d.subgrupo_tarifario_id=e.subgrupo_tarifario_id)
		LEFT JOIN excepciones f ON (f.plan_id=a.plan_id AND d.tarifario_id=f.tarifario_id AND d.cargo=f.cargo AND f.sw_no_contratado='0')
		WHERE a.qx_acto_id='$noCumplimiento' AND a.qx_acto_id=x.qx_acto_id) as h WHERE h.por_plan is not null ORDER BY h.qx_soat_grupo_id,h.cirujano";
    $OrderCir=$this->EjecucionQueryDatos($queryCir);
		$OrderAne=$this->EjecucionQueryDatos($queryAne);
		$OrderAyu=$this->EjecucionQueryDatos($queryAyu);
		$OrderSal=$this->EjecucionQueryDatos($querySal);
		$OrderMat=$this->EjecucionQueryDatos($queryMat);
		$indicaNoEspecialistas=$this->NoEspecialistasCirugia($queryCir);
		for($i=0;$i<sizeof($OrderCir);$i++){
		  if($OrderCir[$i]['por_excp']){
        $porcentaje=$OrderCir[$i]['por_excp']/100;
			}else{
        $porcentaje=$OrderCir[$i]['por_plan']/100;
			}
		  if($i==0){
        $vector[0][$i]=array("valorCir"=>($OrderCir[$i]['precio']*($porcentaje+1)),"proced"=>$OrderCir[$i]['cargo_base']);
				$CirujanoPrimer=$OrderCir[$i]['cirujano'];
			}else{
        if($OrderCir[$i]['via_acceso']=='BILA'){
				  if($i==1){
            $vector[0][$i]=array("valorCir"=>(($OrderCir[$i]['precio']*($porcentaje+1)*0.75)),"proced"=>$OrderCir[$i]['cargo_base']);
					}
				}elseif($OrderCir[$i]['via_acceso']=='IVIA'){
				  if(sizeof($indicaNoEspecialistas)==1){
            $vector[0][$i]=array("valorCir"=>(($OrderCir[$i]['precio']*($porcentaje+1))*0.50),"proced"=>$OrderCir[$i]['cargo_base']);
					}else{
					  if(strcasecmp($CirujanoPrimer,$OrderCir[$i]['cirujano'])){
						  if(!in_array($OrderCir[$i]['cirujano'],$vectorCirCobro1)){
								$vector[0][$i]=array("valorCir"=>($OrderCir[$i]['precio']*($porcentaje+1)),"proced"=>$OrderCir[$i]['cargo_base']);
								$vectorCirCobro1[]=$OrderCir[$i]['cirujano'];
							}else{
								$vector[0][$i]=array("valorCir"=>(($OrderCir[$i]['precio']*($porcentaje+1))*0.50),"proced"=>$OrderCir[$i]['cargo_base']);
							}
						}else{
							$vector[0][$i]=array("valorCir"=>(($OrderCir[$i]['precio']*($porcentaje+1))*0.50),"proced"=>$OrderCir[$i]['cargo_base']);
						}
				  }
			  }elseif($OrderCir[$i]['via_acceso']=='DVIA'){
          if(sizeof($indicaNoEspecialistas)==1){
					  $vector[0][$i]=array("valorCir"=>(($OrderCir[$i]['precio']*($porcentaje+1))*0.75),"proced"=>$OrderCir[$i]['cargo_base']);
				  }else{
				    if(strcasecmp($CirujanoPrimer,$OrderCir[$i]['cirujano'])){//DIFERENTE AL PRIMER CIRUJANO
						  if(!in_array($OrderCir[$i]['cirujano'],$vectorCirCobro1)){//nO ESTA EN EL VECTOR
								$vector[0][$i]=array("valorCir"=>$OrderCir[$i]['precio']*($porcentaje+1),"proced"=>$OrderCir[$i]['cargo_base']);
								$vectorCirCobro1[]=$OrderCir[$i]['cirujano'];
						  }else{
							  $vector[0][$i]=array("valorCir"=>(($OrderCir[$i]['precio']*($porcentaje+1))*0.50),"proced"=>$OrderCir[$i]['cargo_base']);
						  }
						}else{
							$vector[0][$i]=array("valorCir"=>(($OrderCir[$i]['precio']*($porcentaje+1))*0.50),"proced"=>$OrderCir[$i]['cargo_base']);
						}
				  }
			  }
		  }
		}
		for($i=0;$i<sizeof($OrderAne);$i++){
      if($OrderAne[$i]['por_excp']){
        $porcentaje=$OrderAne[$i]['por_excp']/100;
			}else{
        $porcentaje=$OrderAne[$i]['por_plan']/100;
			}
		  if($i==0){
        $vector[1][$i]=array("valorAne"=>($OrderAne[$i]['precio']*($porcentaje+1)),"proced"=>$OrderAne[$i]['cargo_base']);
				$CirujanoPrimer=$OrderAne[$i]['cirujano'];
			}else{
        if($OrderAne[$i]['via_acceso']=='BILA'){
				  if($i==1){
            $vector[1][$i]=array("valorAne"=>(($OrderAne[$i]['precio']*($porcentaje+1))*0.75),"proced"=>$OrderAne[$i]['cargo_base']);
					}
				}elseif($OrderAne[$i]['via_acceso']=='IVIA'){
				  if(sizeof($indicaNoEspecialistas)==1){
            $vector[1][$i]=array("valorAne"=>(($OrderAne[$i]['precio']*($porcentaje+1)*0.50)),"proced"=>$OrderAne[$i]['cargo_base']);
					}else{
					  if(strcasecmp($CirujanoPrimer,$OrderAne[$i]['cirujano'])){
						  if(!in_array($OrderAne[$i]['cirujano'],$vectorCirCobro1)){
								$vector[1][$i]=array("valorAne"=>$OrderAne[$i]['precio']*($porcentaje+1),"proced"=>$OrderAne[$i]['cargo_base']);
								$vectorCirCobro1[]=$OrderAne[$i]['cirujano'];
							}else{
								$vector[1][$i]=array("valorAne"=>(($OrderAne[$i]['precio']*($porcentaje+1))*0.75),"proced"=>$OrderAne[$i]['cargo_base']);
							}
						}else{
							$vector[1][$i]=array("valorAne"=>(($OrderAne[$i]['precio']*($porcentaje+1))*0.75),"proced"=>$OrderAne[$i]['cargo_base']);
						}
				  }
			  }elseif($OrderAne[$i]['via_acceso']=='DVIA'){
          if(sizeof($indicaNoEspecialistas)==1){
					  $vector[1][$i]=array("valorAne"=>(($OrderAne[$i]['precio']*($porcentaje+1))*0.75),"proced"=>$OrderAne[$i]['cargo_base']);
				  }else{
				    if(strcasecmp($CirujanoPrimer,$OrderAne[$i]['cirujano'])){//DIFERENTE AL PRIMER CIRUJANO
						  if(!in_array($OrderAne[$i]['cirujano'],$vectorCirCobro1)){//nO ESTA EN EL VECTOR
							  $vector[1][$i]=array("valorAne"=>$OrderAne[$i]['precio']*($porcentaje+1),"proced"=>$OrderAne[$i]['cargo_base']);
							  $vectorCirCobro1[]=$OrderAne[$i]['cirujano'];
						  }else{
							  $vector[1][$i]=array("valorAne"=>(($OrderAne[$i]['precio']*($porcentaje+1))*0.75),"proced"=>$OrderAne[$i]['cargo_base']);
						  }
					  }else{
						  $vector[1][$i]=array("valorAne"=>(($OrderAne[$i]['precio']*($porcentaje+1)*0.75)),"proced"=>$OrderAne[$i]['cargo_base']);
					  }
					}
				}
			}
		}
		for($i=0;$i<sizeof($OrderAyu);$i++){
      if($OrderAyu[$i]['por_excp']){
        $porcentaje=$OrderAyu[$i]['por_excp']/100;
			}else{
        $porcentaje=$OrderAyu[$i]['por_plan']/100;
			}
		  if($i==0){
        $vector[2][$i]=array("valorAyu"=>($OrderAyu[$i]['precio']*($porcentaje+1)),"proced"=>$OrderAyu[$i]['cargo_base']);
			}else{
        if($OrderAyu[$i]['via_acceso']=='BILA'){
				  if($i==1){
            $vector[2][$i]=array("valorAyu"=>(($OrderAyu[$i]['precio']*($porcentaje+1))*0.75),"proced"=>$OrderAyu[$i]['cargo_base']);
					}
				}elseif($OrderAyu[$i]['via_acceso']=='IVIA'){
				  if(sizeof($indicaNoEspecialistas)==1){
            $vector[2][$i]=array("valorAyu"=>(($OrderAyu[$i]['precio']*($porcentaje+1))*0.50),"proced"=>$OrderAyu[$i]['cargo_base']);
					}else{
            if($i==1){
              $vector[2][$i]=array("valorAyu"=>(($OrderAyu[$i]['precio']*($porcentaje+1))*0.50),"proced"=>$OrderAyu[$i]['cargo_base']);
						}
					}
				}elseif($OrderAyu[$i]['via_acceso']=='DVIA'){
					if(sizeof($indicaNoEspecialistas)==1){
						$vector[2][$i]=array("valorAyu"=>(($OrderAyu[$i]['precio']*($porcentaje+1))*0.75),"proced"=>$OrderAyu[$i]['cargo_base']);
					}else{
						if($i==1){
							$vector[2][$i]=array("valorAyu"=>(($OrderAyu[$i]['precio']*($porcentaje+1))*0.50),"proced"=>$OrderAyu[$i]['cargo_base']);
						}
					}
				}
			}
		}
		for($i=0;$i<sizeof($OrderSal);$i++){
      if($OrderSal[$i]['por_excp']){
        $porcentaje=$OrderSal[$i]['por_excp']/100;
			}else{
        $porcentaje=$OrderSal[$i]['por_plan']/100;
			}
		  if($i==0){
        $vector[3][$i]=array("valorSal"=>($OrderSal[$i]['precio']*($porcentaje+1)),"proced"=>$OrderSal[$i]['cargo_base']);
			}else{
        if($OrderSal[$i]['via_acceso']=='BILA'){
				  if($i==1){
            $vector[3][$i]=array("valorSal"=>(($OrderSal[$i]['precio']*($porcentaje+1))*0.50),"proced"=>$OrderSal[$i]['cargo_base']);
					}
				}elseif($OrderSal[$i]['via_acceso']=='IVIA'){
          $vector[3][$i]=array("valorSal"=>(($OrderSal[$i]['precio']*($porcentaje+1))*0.50),"proced"=>$OrderSal[$i]['cargo_base']);
				}elseif($OrderSal[$i]['via_acceso']=='DVIA'){
					$vector[3][$i]=array("valorSal"=>(($OrderSal[$i]['precio']*($porcentaje+1))*0.50),"proced"=>$OrderSal[$i]['cargo_base']);
				}
			}
		}
		for($i=0;$i<sizeof($OrderMat);$i++){
      if($OrderMat[$i]['por_excp']){
        $porcentaje=$OrderMat[$i]['por_excp']/100;
			}else{
        $porcentaje=$OrderMat[$i]['por_plan']/100;
			}
		  if($i==0){
        $vector[4][$i]=array("valorMat"=>($OrderMat[$i]['precio']*($porcentaje+1)),"proced"=>$OrderMat[$i]['cargo_base']);
			}else{
        if($OrderMat[$i]['via_acceso']=='BILA'){
				  if($i==1){
            $vector[4][$i]=array("valorMat"=>(($OrderMat[$i]['precio']*($porcentaje+1))*0.75),"proced"=>$OrderMat[$i]['cargo_base']);
					}
				}elseif($OrderMat[$i]['via_acceso']=='IVIA'){
          $vector[4][$i]=array("valorMat"=>(($OrderMat[$i]['precio']*($porcentaje+1))*0.75),"proced"=>$OrderMat[$i]['cargo_base']);
				}elseif($OrderMat[$i]['via_acceso']=='DVIA'){
          $vector[4][$i]=array("valorMat"=>(($OrderMat[$i]['precio']*($porcentaje+1))*0.75),"proced"=>$OrderMat[$i]['cargo_base']);
				}
			}
		}
		$this->FormaProdedimientosliquidados($vector,$noCumplimiento);
		return true;
	}

	function EjecucionParticular($noCumplimiento){

    list($dbconn) = GetDBconn();
	  $query="SELECT fecha_hora_inicio,fecha_hora_final FROM qx_acto WHERE qx_acto_id='$noCumplimiento'";
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
		$FechaIni=$this->FechaStamp($vars['fecha_hora_inicio']);
		$CadenaFechaIni = explode ('/', $FechaIni);
		$HoraIni=$this->HoraStamp($vars['fecha_hora_inicio']);
		$CadenaHoraIni= explode (':',$HoraIni);
		$FechaFin=$this->FechaStamp($vars['fecha_hora_final']);
		$CadenaFechaFin = explode ('/', $FechaFin);
		$HoraFin=$this->HoraStamp($vars['fecha_hora_final']);
		$CadenaHoraFin= explode (':',$HoraFin);
		$DuracionMin=(mktime($CadenaHoraFin[0],$CadenaHoraFin[1],0,$CadenaFechaFin[1],$CadenaFechaFin[2],$CadenaFechaFin[0]) -
		mktime($CadenaHoraIni[0],$CadenaHoraIni[1],0,$CadenaFechaIni[1],$CadenaFechaIni[2],$CadenaFechaIni[0]
		))/60;
	}


 function LiquidacionTarifCirugia($numacto){
    $this->EjecucionQxSOAT($numacto);
		return true;
	}

	function ProcedimientosTotalesQX($noCumplimiento){
    list($dbconn) = GetDBconn();
	  $query="SELECT a.procedimiento_qx,b.descripcion
		FROM qx_acto_procedimientos_realizados a,cups b
		WHERE a.qx_acto_id='$noCumplimiento' AND a.procedimiento_qx=b.cargo";
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
		$result->close();
		return $vars;
	}

	function LlamaConsultaCumplimiento(){
		$this->BusquedaConsultaCumplimiento();
		return true;
	}

	function BusquedaCumplimientoQX(){
		if($_REQUEST['Regresar']){
      $this->MenuQXEjecucion();
			return true;
		}
    if($_REQUEST['Aceptar']){
      $this->BusquedaConsultaCumplimiento($_REQUEST['TipoBusquedaInv']);
			return true;
		}
		if($_REQUEST['Buscar']){
      if(empty($_REQUEST['Busqueda'])){
        if(!$_REQUEST['Documento']){
				  $this->frmError["MensajeError"]="Para Realizar la Busqueda Requiere llenar todos los datos Solicitados";
				  $this->FormaBusquedaProgramacion($_REQUEST['Busqueda']);
					return true;
				}else{
          $query="SELECT a.qx_acto_id,a.paciente_id,a.tipo_id_paciente,a.quirofano_id,a.fecha_hora_inicio,a.fecha_hora_final,a.tipo_id_cirujano,
			 		a.cirujano_principal,a.via_acceso,b.primer_apellido||' '||b.segundo_apellido as nom,b.primer_nombre||' '||b.segundo_nombre as prenom
					FROM qx_acto a,pacientes b WHERE a.paciente_id='".$_REQUEST['Documento']."' AND a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."' AND
					a.paciente_id=b.paciente_id AND a.tipo_id_paciente=b.tipo_id_paciente";
				}
			}elseif($_REQUEST['Busqueda']==1){
			  if($_REQUEST['cirujano']==-1){
				  $this->frmError["MensajeError"]="Para Realizar la Busqueda Requiere llenar todos los datos Solicitados";
          $this->FormaBusquedaProgramacion($_REQUEST['Busqueda']);
					return true;
				}else{
          $cadena=explode('/',$_REQUEST['cirujano']);
          $query="SELECT a.qx_acto_id,a.paciente_id,a.tipo_id_paciente,a.quirofano_id,a.fecha_hora_inicio,a.fecha_hora_final,a.tipo_id_cirujano,
			 		a.cirujano_principal,a.via_acceso,b.primer_apellido||' '||b.segundo_apellido as nom,b.primer_nombre||' '||b.segundo_nombre as prenom
					FROM qx_acto a,pacientes b WHERE a.tipo_id_cirujano='".$cadena[1]."' AND a.cirujano_principal='".$cadena[0]."' AND
					a.paciente_id=b.paciente_id AND a.tipo_id_paciente=b.tipo_id_paciente";
				}
			}elseif($_REQUEST['Busqueda']==2){
        $FechaIni=explode('/',$_REQUEST['FechaInicial']);
				$FechaFin=explode('/',$_REQUEST['FechaFinal']);
				if(!$_REQUEST['FechaInicial'] || !$_REQUEST['FechaFinal'] || (mktime(0,0,0,$FechaFin[1],$FechaFin[0],$FechaFin[3])<mktime(0,0,0,$FechaIni[1],$FechaIni[0],$FechaIni[3]))){
				   $this->frmError["MensajeError"]="Realize de nuevo la insercion y verifiquelas fechas";
           $this->FormaBusquedaProgramacion($_REQUEST['Busqueda']);
					 return true;
				}else{
          $FechaIn=$FechaIni[2].'/'.$FechaIni[1].'/'.$FechaIni[0];
					$FechaFi=$FechaFin[2].'/'.$FechaFin[1].'/'.$FechaFin[0];
          $query="SELECT a.qx_acto_id,a.paciente_id,a.tipo_id_paciente,a.quirofano_id,a.fecha_hora_inicio,a.fecha_hora_final,a.tipo_id_cirujano,
			 		a.cirujano_principal,a.via_acceso,b.primer_apellido||' '||b.segundo_apellido as nom,b.primer_nombre||' '||b.segundo_nombre as prenom
					FROM qx_acto a,pacientes b
					WHERE date(a.fecha_hora_inicio)>='$FechaIn' AND date(a.fecha_hora_inicio)<='$FechaFi' AND
					a.paciente_id=b.paciente_id AND a.tipo_id_paciente=b.tipo_id_paciente";
				}
			}elseif($_REQUEST['Busqueda']==3){
        if(!$_REQUEST['numeroProgramacion']){
				   $this->frmError["MensajeError"]="Para Realizar la Busqueda Requiere llenar todos los datos Solicitados";
           $this->FormaBusquedaProgramacion($_REQUEST['Busqueda']);
					 return true;
				}else{
          $query="SELECT a.qx_acto_id,a.paciente_id,a.tipo_id_paciente,a.quirofano_id,a.fecha_hora_inicio,a.fecha_hora_final,a.tipo_id_cirujano,
			 		a.cirujano_principal,a.via_acceso,b.primer_apellido||' '||b.segundo_apellido as nom,b.primer_nombre||' '||b.segundo_nombre as prenom
					FROM qx_acto a,pacientes b
					WHERE a.qx_acto_id='".$_REQUEST['numeroProgramacion']."' AND
					a.paciente_id=b.paciente_id AND a.tipo_id_paciente=b.tipo_id_paciente";
				}
			}elseif($_REQUEST['Busqueda']==4){
			  if($_REQUEST['quirofano']==-1){
				  $this->frmError["MensajeError"]="Para Realizar la Busqueda Requiere llenar todos los datos Solicitados";
          $this->FormaBusquedaProgramacion($_REQUEST['Busqueda']);
					return true;
				}else{
          $query="SELECT a.qx_acto_id,a.paciente_id,a.tipo_id_paciente,a.quirofano_id,a.fecha_hora_inicio,a.fecha_hora_final,a.tipo_id_cirujano,
			 		a.cirujano_principal,a.via_acceso,b.primer_apellido||' '||b.segundo_apellido as nom,b.primer_nombre||' '||b.segundo_nombre as prenom
					FROM qx_acto a,pacientes b
					WHERE a.quirofano_id='".$_REQUEST['quirofano']."' AND
					a.paciente_id=b.paciente_id AND a.tipo_id_paciente=b.tipo_id_paciente";
				}
			}elseif($_REQUEST['Busqueda']==5){
        if(!$_REQUEST['nombres'] && !$_REQUEST['apellidos']){
				  $this->frmError["MensajeError"]="Para Realizar la Busqueda Requiere llenar todos los datos Solicitados";
          $this->FormaBusquedaProgramacion($_REQUEST['Busqueda']);
					return true;
				}else{
					$_REQUEST['nombres']=strtoupper($_REQUEST['nombres']);
					$_REQUEST['apellidos']=strtoupper($_REQUEST['apellidos']);
				  $query="SELECT a.qx_acto_id,a.paciente_id,a.tipo_id_paciente,a.quirofano_id,a.fecha_hora_inicio,a.fecha_hora_final,a.tipo_id_cirujano,
			 		a.cirujano_principal,a.via_acceso,b.primer_apellido||' '||b.segundo_apellido as nom,b.primer_nombre||' '||b.segundo_nombre as prenom
					FROM qx_acto a,pacientes b
					WHERE a.paciente_id=b.paciente_id AND a.tipo_id_paciente=b.tipo_id_paciente AND
					(b.primer_apellido||' '||b.segundo_apellido LIKE '%".$_REQUEST['apellidos']."%' AND b.primer_nombre||' '||b.segundo_nombre LIKE '%".$_REQUEST['nombres']."%')";
				}
			}
		}
    if($_REQUEST['BuscarTotal']){
       	$query="SELECT a.qx_acto_id,a.paciente_id,a.tipo_id_paciente,a.quirofano_id,a.fecha_hora_inicio,a.fecha_hora_final,a.tipo_id_cirujano,
			 	a.cirujano_principal,a.via_acceso,b.primer_apellido||' '||b.segundo_apellido as nom,b.primer_nombre||' '||b.segundo_nombre as prenom
				FROM qx_acto a,pacientes b
				WHERE a.paciente_id=b.paciente_id AND a.tipo_id_paciente=b.tipo_id_paciente";
		}
		list($dbconn) = GetDBconn();
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
		$this->BusquedaConsultaCumplimiento('',1,$vars);
		return true;
	}

	function ConsultaCumplimientoCirugia(){
		list($dbconn) = GetDBconn();
		$query="SELECT a.qx_acto_id,a.quirofano_id,a.fecha_hora_inicio,a.fecha_hora_final,a.tipo_id_cirujano,
						a.cirujano_principal,a.qx_programacion_id,a.tipo_id_anestesiologo,a.anestesiologo,a.paciente_id,
						a.tipo_id_paciente,a.tipo_id_circulante_uno,a.circulante_uno,a.tipo_id_circulante_dos,a.circulante_dos,
						a.tipo_id_instrumentista,a.instrumentista,a.gas_anestesico,a.gas_medicinal,a.via_acceso,a.plan_id,a.tipo_cirugia_id,
						a.ambito_cirugia_id,a.departamento,a.diagnostico_id,b.diagnostico_nombre as diag,a.complicacion_id,
						c.diagnostico_nombre as complic,a.tipo_anestesia,a.fecha_inicio_anestesia,
						a.fecha_fin_anestesia,a.fecha_ingreso_recuperacion,a.fecha_egreso_recuperacion,a.sw_estado_salida,a.protocolo
						FROM qx_acto a
						LEFT JOIN diagnosticos b ON(b.diagnostico_id=a.diagnostico_id)
						LEFT JOIN diagnosticos c ON(c.diagnostico_id=a.complicacion_id)
						WHERE a.qx_acto_id='".$_REQUEST['noActo']."'";
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
		$result->Close();
		$cirujano=$vars['cirujano_principal'].'/'.$vars['tipo_id_cirujano'];
		$anestesista=$vars['anestesiologo'].'/'.$vars['tipo_id_anestesiologo'];
		$FechaIn=$this->FechaStamp($vars['fecha_hora_inicio']);
		$HoraIn=$this->HoraStamp($vars['fecha_hora_inicio']);
    $cadenaHoraIn=explode(':',$HoraIn);
		$FechaFn=$this->FechaStamp($vars['fecha_hora_final']);
		$HoraFn=$this->HoraStamp($vars['fecha_hora_final']);
    $cadenaHoraFn=explode(':',$HoraFn);

		$FechaInAnes=$this->FechaStamp($vars['fecha_inicio_anestesia']);
		$HoraInAnes=$this->HoraStamp($vars['fecha_inicio_anestesia']);
    $cadenaInHoraAnes=explode(':',$HoraInAnes);
		$FechaFnAnes=$this->FechaStamp($vars['fecha_fin_anestesia']);
		$HoraFnAnes=$this->HoraStamp($vars['fecha_fin_anestesia']);
    $cadenaHoraFnAnes=explode(':',$HoraFnAnes);

		$FechaInRecup=$this->FechaStamp($vars['fecha_ingreso_recuperacion']);
		$HoraInRecup=$this->HoraStamp($vars['fecha_ingreso_recuperacion']);
    $cadenaHoraInRecup=explode(':',$HoraInRecup);
		$FechaFnRecup=$this->FechaStamp($vars['fecha_egreso_recuperacion']);
		$HoraFnRecup=$this->HoraStamp($vars['fecha_egreso_recuperacion']);
    $cadenaHoraFnRecup=explode(':',$HoraFnRecup);

		if($vars['sw_estado_salida']=='v'){$estado=1;}else{$estado=2;}
		$_SESSION['EJECUCION']['CIRUGIAS']['ACTO']=$vars['qx_acto_id'];
		$this->FormaEjecucionCirugia($vars['tipo_id_paciente'],$vars['paciente_id'],$vars['plan_id'],$cirujano,$anestesista,$vars['quirofano_id'],
		$FechaIn,$cadenaHoraIn[0],$cadenaHoraIn[1],$FechaFn,$cadenaHoraFn[0],$cadenaHoraFn[1],$vars['via_acceso'],$vars['tipo_cirugia_id'],
		$vars['ambito_cirugia_id'],$vars['circulante1'],$vars['circulante2'],$vars['instrumentista'],$vars['gas_anestesico'],$vars['gas_medicinal'],
		'','','','','',1,$vars['diag'],$vars['diagnostico_id'],$vars['complic'],$vars['complicacion_id'],$vars['tipo_anestesia'],
		$FechaInAnes,$cadenaInHoraAnes[0],$cadenaInHoraAnes[1],$FechaFnAnes,$cadenaHoraFnAnes[0],$cadenaHoraFnAnes[1],
		$estado,$vars['protocolo'],$FechaInRecup,$cadenaHoraInRecup[0],$cadenaHoraInRecup[1],$FechaFnRecup,$cadenaHoraFnRecup[0],$cadenaHoraFnRecup[1]);
		return true;
	}


	function Protocolos_quirurgicos(){

		list($dbconn) = GetDBconn();
		$query="SELECT protocolo,descripcion FROM qx_protocolos";
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
		$result->Close();
		return $vars;
	}


  function DatosRequeridosPresupuesto(){
    if($_REQUEST['Salir']){
		  unset($_SESSION['PRESUPUESTO_CIRUGIA']);
      $this->BusquedaPacienteCumplimiento();
			return true;
		}
		if($_REQUEST['cirujanoUno']){
		  $_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['SW']=1;
		}else{
      unset($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['SW']);
		}
    if($_REQUEST['cirujanoDos']){
		  $_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['SW']=1;
		}else{
      unset($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['SW']);
		}
    if($_REQUEST['Ayundante']){
		  $_SESSION['PRESUPUESTO_CIRUGIA']['AYUDANTE']=1;
		}else{
      unset($_SESSION['PRESUPUESTO_CIRUGIA']['AYUDANTE']);
		}
		if($_REQUEST['Anestesiologo']){
		  $_SESSION['PRESUPUESTO_CIRUGIA']['ANESTESIOLOGO']=1;
		}else{
      unset($_SESSION['PRESUPUESTO_CIRUGIA']['ANESTESIOLOGO']);
		}
    if(!empty($_REQUEST['TipoSala']) && $_REQUEST['TipoSala']!=-1){
		  $_SESSION['PRESUPUESTO_CIRUGIA']['TIPO_SALA']=$_REQUEST['TipoSala'];
			if($_REQUEST['noquiro']=='1'){
        $_SESSION['PRESUPUESTO_CIRUGIA']['NO_QUIRO']='1';
			}elseif($_REQUEST['noquiro']=='0'){
         $_SESSION['PRESUPUESTO_CIRUGIA']['NO_QUIRO']='0';
			}else{
			  if($_SESSION['PRESUPUESTO_CIRUGIA']['NO_QUIRO']=='1'){
          $_SESSION['PRESUPUESTO_CIRUGIA']['NO_QUIRO']='1';
				}else{
          $_SESSION['PRESUPUESTO_CIRUGIA']['NO_QUIRO']='0';
				}
			}
		}else{
      unset($_SESSION['PRESUPUESTO_CIRUGIA']['TIPO_SALA']);
			unset($_SESSION['PRESUPUESTO_CIRUGIA']['NO_QUIRO']);
		}
    if(!empty($_REQUEST['quirofano']) && $_REQUEST['quirofano']!=-1){
		  $_SESSION['PRESUPUESTO_CIRUGIA']['QUIROFANO']=$_REQUEST['quirofano'];
		}else{
      unset($_SESSION['PRESUPUESTO_CIRUGIA']['QUIROFANO']);
		}
		if(!empty($_REQUEST['hora']) && $_REQUEST['hora']!=-1){
		  $_SESSION['PRESUPUESTO_CIRUGIA']['DURACION_HORAS']=$_REQUEST['hora'];
		}else{
      unset($_SESSION['PRESUPUESTO_CIRUGIA']['DURACION_HORAS']);
		}
		if(!empty($_REQUEST['minutos']) && $_REQUEST['minutos']!=-1){
		  $_SESSION['PRESUPUESTO_CIRUGIA']['DURACION_MINUTOS']=$_REQUEST['minutos'];
		}else{
      unset($_SESSION['PRESUPUESTO_CIRUGIA']['DURACION_MINUTOS']);
		}
    if(!empty($_REQUEST['via']) && $_REQUEST['via']!=-1){
		  $_SESSION['PRESUPUESTO_CIRUGIA']['VIAS_ACCESO']=$_REQUEST['via'];
		}else{
      unset($_SESSION['PRESUPUESTO_CIRUGIA']['VIAS_ACCESO']);
		}

    if(!empty($_REQUEST['salaRecuperacion']) && $_REQUEST['salaRecuperacion']!=-1){
		  $_SESSION['PRESUPUESTO_CIRUGIA']['SALA_RECUPERACION']=$_REQUEST['salaRecuperacion'];
		}else{
      unset($_SESSION['PRESUPUESTO_CIRUGIA']['SALA_RECUPERACION']);
		}
    if(!empty($_REQUEST['internacionPreQX']) && $_REQUEST['internacionPreQX']!=-1){
		  $_SESSION['PRESUPUESTO_CIRUGIA']['SALA_RECUPERACION_PRE_QX']=$_REQUEST['internacionPreQX'];
		}else{
      unset($_SESSION['PRESUPUESTO_CIRUGIA']['SALA_RECUPERACION_PRE_QX']);
		}
		if(!empty($_REQUEST['diasInternacionPreQX']) && $_REQUEST['diasInternacionPreQX']!=-1){
		  $_SESSION['PRESUPUESTO_CIRUGIA']['DIAS_RECUPERACION_PRE_QX']=$_REQUEST['diasInternacionPreQX'];
		}else{
      unset($_SESSION['PRESUPUESTO_CIRUGIA']['DIAS_RECUPERACION_PRE_QX']);
		}

    if(!empty($_REQUEST['internacionPostQX']) && $_REQUEST['internacionPostQX']!=-1){
		  $_SESSION['PRESUPUESTO_CIRUGIA']['SALA_RECUPERACION_POST_QX']=$_REQUEST['internacionPostQX'];
		}else{
      unset($_SESSION['PRESUPUESTO_CIRUGIA']['SALA_RECUPERACION_POST_QX']);
		}
		if(!empty($_REQUEST['diasInternacionPostQX']) && $_REQUEST['diasInternacionPostQX']!=-1){
		  $_SESSION['PRESUPUESTO_CIRUGIA']['DIAS_RECUPERACION_POST_QX']=$_REQUEST['diasInternacionPostQX'];
		}else{
      unset($_SESSION['PRESUPUESTO_CIRUGIA']['DIAS_RECUPERACION_POST_QX']);
		}

    unset($_SESSION['PRESUPUESTO_CIRUGIA']['PROCEDIMIENTOS_BILATERAL']);
    $bilaterales=$_REQUEST['bilateral'];
    if($bilaterales){
      foreach($bilaterales as $cargo=>$valor){
        $_SESSION['PRESUPUESTO_CIRUGIA']['PROCEDIMIENTOS_BILATERAL'][$cargo]=1;
      }
    }

		if($_REQUEST['numeroCirujano']){
      $this->InsertarProcedReqLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas'],$_REQUEST['numeroCirujano']);
			return true;
		}
    if($_REQUEST['numeroCirujanoSelect']){
      $this->BuscadorProfesional($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas'],$_REQUEST['numeroCirujanoSelect']);
			return true;
		}
		if($_REQUEST['EliminaCirujano']){
      if($_REQUEST['EliminaCirujano']=='1'){
			  unset($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['NOMBRE']);
        $this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas']);
				return true;
			}elseif($_REQUEST['EliminaCirujano']=='2'){
			  unset($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['NOMBRE']);
        $this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas']);
				return true;
			}
		}
		if($_REQUEST['Liquidar']){
			if($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['SW']==1){
        if(sizeof($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['PROCEDIMIENTOS'])<1){
				  $this->frmError["MensajeError"]="Inserte Procedimientos para el primer Cirujano";
          $this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas']);
					return true;
				}
			}
			if($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['SW']==1){
				if(sizeof($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['PROCEDIMIENTOS'])<1){
				  $this->frmError["MensajeError"]="Inserte Procedimientos para el segundo Cirujano";
          $this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas']);
					return true;
				}
			}
			if(empty($_SESSION['PRESUPUESTO_CIRUGIA']['TIPO_SALA'])){
				$this->frmError["MensajeError"]="Elija el Tipo de Sala para la Cirugia";
				$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas']);
				return true;
			}
			if(empty($_SESSION['PRESUPUESTO_CIRUGIA']['VIAS_ACCESO'])){
				$this->frmError["MensajeError"]="Elija la via de Acceso para la Cirugia";
				$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas']);
				return true;
			}

      $_REQUEST['der_cirujano']=1;
      $_REQUEST['der_anestesiologo']=1;
      $_REQUEST['der_ayudante']=1;
      $_REQUEST['der_sala']=1;
      $_REQUEST['der_materiales']=1;
      $this->FormaEquivalentesLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas']);
			return true;

      //$this->GuardarDatosPresupuesto();
		}
	}

  /**
* Metodo para Obtener los cargos contratados para un cargo cups
*
* @return array
* @access private
*/
  function GetEquivalenciasCargosLiquidacion($cargo,$plan){
    GLOBAL $ADODB_FETCH_MODE;
  /*$query="SELECT a.tipo_id_cirujano,a.cirujano_id,
    a.cargo_cups,b.descripcion,a.sw_bilateral,c.nombre_tercero,
    (SELECT count(*) FROM cuentas_liquidaciones_qx_procedimientos WHERE cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND tipo_id_cirujano=a.tipo_id_cirujano AND cirujano_id=a.cirujano_id) as contador
    FROM cuentas_liquidaciones_qx_procedimientos a,cups b,terceros c
    WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND a.cargo_cups=b.cargo AND
    a.tipo_id_cirujano=c.tipo_id_tercero AND a.cirujano_id=c.tercero_id";
  */
    list($dbconn) = GetDBconn();
    $sql = "(SELECT b.plan_id, a.tarifario_id, a.cargo, a.descripcion, a.precio, a.gravamen, b.porcentaje, b.por_cobertura, b.sw_descuento, a.sw_cantidad,
             tarif.descripcion as nomtarifario,pl.plan_descripcion
             FROM tarifarios_detalle a, plan_tarifario b, tarifarios_equivalencias c,tarifarios tarif,planes pl


            WHERE b.plan_id = '$plan'
            AND b.plan_id=pl.plan_id
            AND a.grupo_tarifario_id = b.grupo_tarifario_id
            AND a.subgrupo_tarifario_id = b.subgrupo_tarifario_id
            AND a.tarifario_id = b.tarifario_id
            AND a.tarifario_id=tarif.tarifario_id
            AND c.cargo_base = '$cargo'
            AND c.tarifario_id = a.tarifario_id
            AND c.cargo=a.cargo
            AND excepciones(b.plan_id,b.tarifario_id, a.cargo) = 0
            )
            UNION
            (SELECT b.plan_id, a.tarifario_id, a.cargo, a.descripcion, a.precio, a.gravamen, b.porcentaje, b.por_cobertura, b.sw_descuento, a.sw_cantidad,
            tarif.descripcion as nomtarifario,pl.plan_descripcion
            FROM tarifarios_detalle a, excepciones b, tarifarios_equivalencias c,tarifarios tarif,planes pl

            WHERE c.cargo_base = '$cargo'
            AND b.plan_id = '$plan'
            AND b.plan_id=pl.plan_id
            AND b.tarifario_id = c.tarifario_id
            AND b.cargo = c.cargo
            AND a.tarifario_id = c.tarifario_id
            AND a.tarifario_id=tarif.tarifario_id
            AND a.cargo = c.cargo
            AND b.sw_no_contratado = 0
            )";

    /*SELECT b.tipo_id_paciente,b.paciente_id
    FROM cuentas a,ingresos b
    WHERE (a.estado='1' OR a.estado='2') AND a.plan_id='56' AND a.ingreso=b.ingreso AND b.estado='1'*/

    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $result = $dbconn->Execute($sql);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

    if ($dbconn->ErrorNo() != 0) {
      $this->error = "CLASS LiquidacionQX -  - ERROR 01";
      $this->mensajeDeError = $dbconn->ErrorMsg();
      return false;
    }
    $cargos_contratados_plan=$result->GetRows();
    $result->Close();
    return $cargos_contratados_plan;
  }

  function GuardarDatosPresupuesto(){
    list($dbconn) = GetDBconn();
    $dbconn->BeginTrans();
    if(empty($_REQUEST['TipoDocumento']) || $_REQUEST['TipoDocumento']==-1){
      $TipoDocumento='NULL';
    }else{
      $TipoDocumento="'".$_REQUEST['TipoDocumento']."'";
    }
    if(empty($_REQUEST['Documento'])){
      $Documento='NULL';
    }else{
      $Documento="'".$_REQUEST['Documento']."'";
    }
    if(empty($_REQUEST['tipoAfil']) || $_REQUEST['tipoAfil']==-1){
      $tipoAfil='NULL';
    }else{
      (list($tipoAfil,$descripciontipoAfil)=explode('/',$_REQUEST['tipoAfil']));
      $tipoAfil="'".$tipoAfil."'";
    }
    if(empty($_REQUEST['rango']) || $_REQUEST['rango']==-1){
      $rango='NULL';
    }else{
      $rango="'".$_REQUEST['rango']."'";
    }
    if(!empty($_SESSION['PRESUPUESTO_CIRUGIA']['DURACION_HORAS']) && !empty($_SESSION['PRESUPUESTO_CIRUGIA']['DURACION_MINUTOS'])){
      $duracion="'".$_SESSION['PRESUPUESTO_CIRUGIA']['DURACION_HORAS'].':'.$_SESSION['PRESUPUESTO_CIRUGIA']['DURACION_MINUTOS']."'";
    }else{
      $duracion='NULL';
    }
    if($_REQUEST['der_cirujano']){$der_cirujano=1;}else{$der_cirujano=0;}
    if($_REQUEST['der_anestesiologo']){$der_anestesiologo=1;}else{$der_anestesiologo=0;}
    if($_REQUEST['der_ayudante']){$der_ayudante=1;}else{$der_ayudante=0;}
    if($_REQUEST['der_sala']){$der_sala=1;}else{$der_sala=0;}
    if($_REQUEST['der_materiales']){$der_materiales=1;}else{$der_materiales=0;}

    (list($tipoSala,$sw_quiro)=explode('/',$_SESSION['PRESUPUESTO_CIRUGIA']['TIPO_SALA']));
    $query = " SELECT nextval('presupuesto_cx_presupuesto_cx_id_seq')";
    $result = $dbconn->Execute($query);
    $PresupuestoId=$result->fields[0];
		$query = " INSERT INTO presupuesto_cx(
    presupuesto_cx_id,tipo_id_paciente,paciente_id,
    nombre_paciente,plan_id,tipo_afiliado_id,rango,
    semanas_cotizadas,via_acceso,tipo_sala_id,
    sw_derechos_ayudante,sw_derechos_anestesiologo,sw_derechos_materiales,
    sw_derechos_cirujano,sw_derechos_sala,
    duracion_cirugia,sw_temporal,fecha_registro,usuario_id)VALUES
    ('".$PresupuestoId."',$TipoDocumento,$Documento,'".$_REQUEST['nombrePac']."',
    '".$_REQUEST['Responsable']."',$tipoAfil,$rango,'".$_REQUEST['semanas']."',
    '".$_SESSION['PRESUPUESTO_CIRUGIA']['VIAS_ACCESO']."','".$tipoSala."',
    '$der_ayudante','$der_anestesiologo','$der_materiales','$der_cirujano','$der_cirujano',
    $duracion,'0','".date("Y-m-d H:i:s")."','".UserGetUID()."')";
    $result = $dbconn->Execute($query);

    if($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Guardar en la Base de Datos";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $dbconn->RollbackTrans();
      return false;
    }else{
      //procedimiento Cirujano uno
      if(sizeof($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['PROCEDIMIENTOS'])>0){
        foreach($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['PROCEDIMIENTOS'] as $pos=>$vector){
          (list($codigo,$desProcedimiento,$sw_bilateral)=explode('||//',$vector));
          if($_SESSION['PRESUPUESTO_CIRUGIA']['PROCEDIMIENTOS_BILATERAL'][$codigo]==1){$sw_bilateral=1;}else{$sw_bilateral=0;}
          if(!empty($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['NOMBRE'])){
            (list($tipoIdCirujano1,$IdCirujano1,$nombreCirujano1)=explode('/',$_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['NOMBRE']));
            if($tipoIdCirujano1 && $IdCirujano1){
              $tipoIdCirujano1="'".$tipoIdCirujano1."'";
              $IdCirujano1="'".$IdCirujano1."'";
            }else{
              $tipoIdCirujano1='NULL';
              $IdCirujano1='NULL';
            }
          }else{
            $tipoIdCirujano1='NULL';
            $IdCirujano1='NULL';
          }
          if(empty($_REQUEST['Seleccion'.$codigo])){
            $this->frmError["MensajeError"]="Debe Seleccionar un Tarifario para Liquidar cada Procedimiento";
            $this->FormaEquivalentesLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas']);
            return true;
          }else{
            (list($tarifario,$cargo)=explode('||//',$_REQUEST['Seleccion'.$codigo]));
          }
          if($_REQUEST['Cantidad'.$codigo]<1){$_REQUEST['Cantidad'.$codigo]=1;}

          $query = " SELECT nextval('presupuesto_cx_procedimientos_presupuesto_cx_procedimiento__seq')";
          $result = $dbconn->Execute($query);
          $ProcedPresupuestoId=$result->fields[0];
          $query = " INSERT INTO presupuesto_cx_procedimientos(
          presupuesto_cx_procedimiento_id,presupuesto_cx_id,
          cargo_cups,sw_bilateral,tipo_id_cirujano,
          cirujano_id)VALUES('".$ProcedPresupuestoId."','".$PresupuestoId."',
          '".$codigo."','$sw_bilateral',$tipoIdCirujano1,$IdCirujano1)";

          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }else{
            $query = " INSERT INTO presupuesto_cx_procedimientos_cargos(
            presupuesto_cx_procedimiento_id,tarifario_id,cargo,cantidad)
            VALUES('".$ProcedPresupuestoId."','$tarifario','$cargo',
            '".$_REQUEST['Cantidad'.$codigo]."')";

            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollbackTrans();
              return false;
            }
          }
        }
      }
      //Fin
      //procedimiento Cirujano dos
      if(sizeof($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['PROCEDIMIENTOS'])>0){
        foreach($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['PROCEDIMIENTOS'] as $pos=>$vector){
          (list($codigo,$desProcedimiento,$sw_bilateral)=explode('||//',$vector));
          if($_SESSION['PRESUPUESTO_CIRUGIA']['PROCEDIMIENTOS_BILATERAL'][$codigo]==1){$sw_bilateral=1;}else{$sw_bilateral=0;}
          if(!empty($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['NOMBRE'])){
            (list($tipoIdCirujano2,$IdCirujano2,$nombreCirujano2)=explode('/',$_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['NOMBRE']));
            if($tipoIdCirujano2 && $IdCirujano2){
              $tipoIdCirujano2="'".$tipoIdCirujano2."'";
              $IdCirujano2="'".$IdCirujano2."'";
            }else{
              $tipoIdCirujano2='NULL';
              $IdCirujano2='NULL';
            }
          }else{
            $tipoIdCirujano2='NULL';
            $IdCirujano2='NULL';
          }
          if(empty($_REQUEST['Seleccion'.$codigo])){
            $this->frmError["MensajeError"]="Debe Seleccionar un Tarifario para Liquidar cada Procedimiento";
            $this->FormaEquivalentesLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas']);
            return true;
          }else{
            (list($tarifario,$cargo)=explode('||//',$_REQUEST['Seleccion'.$codigo]));
          }
          if($_REQUEST['Cantidad'.$codigo]<1){$_REQUEST['Cantidad'.$codigo]=1;}

          $query = " SELECT nextval('presupuesto_cx_procedimientos_presupuesto_cx_procedimiento__seq')";
          $result = $dbconn->Execute($query);
          $ProcedPresupuestoId=$result->fields[0];
          $query = " INSERT INTO presupuesto_cx_procedimientos(
          presupuesto_cx_procedimiento_id,presupuesto_cx_id,
          cargo_cups,sw_bilateral,tipo_id_cirujano,
          cirujano_id)VALUES('".$ProcedPresupuestoId."','".$PresupuestoId."',
          '".$codigo."','$sw_bilateral',$tipoIdCirujano2,$IdCirujano2)";

          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }else{
            $query = " INSERT INTO presupuesto_cx_procedimientos_cargos(
            presupuesto_cx_procedimiento_id,tarifario_id,cargo,cantidad)
            VALUES('".$ProcedPresupuestoId."','$tarifario','$cargo',
            '".$_REQUEST['Cantidad'.$codigo]."')";
            
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollbackTrans();
              return false;
            }
          }
        }
      }
      //Fin
      /*if(!IncludeClass("Liquidacion_qx")){
        $this->error = "Error";
        $this->mensajeDeError = "No se pudo incluir : classes/Liquidacion_qx/Liquidacion_qx.class.php";
        return false;
      }
      if(!class_exists('Liquidacion_qx')){
        $this->error="Error";
        $this->mensajeDeError="no existe Liquidacion_qx";
        return false;
      }
      $class= New Liquidacion_qx();
      $datosDpto=$this->ServicioCentroUtilidadDepartamento($_SESSION['LIQUIDACION_QX']['Departamento']);
      if($class->Liquidar_Cirugia($_REQUEST['NoLiquidacion'],$_SESSION['LIQUIDACION_QX']['Empresa'],$_SESSION['LIQUIDACION_QX']['Departamento'],$datosDpto['servicio'])==false){
        $this->error=$class->error;
        $this->mensajeDeError=$class->mensajeDeError;
        return false;
      }*/
      exit;
      $dbconn->CommitTrans();
      return true;
    }
  }
/**
* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
* @return array
*/
  function TiposDeSalas(){

		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_sala_id,descripcion,sw_quirofano
		FROM qx_tipos_salas";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()){
        while (!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
			  }
			}
		}
		$result->Close();
 		return $vars;
	}

	/**
* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
* @return array
*/
  function TiposQuirofanosTotal(){

		list($dbconn) = GetDBconn();
		$query = "SELECT quirofano,descripcion FROM qx_quirofanos WHERE estado='1'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($result->RecordCount()){
				while(!$result->EOF){
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
	}

	function LlamaInsertarProcedReqLiquidacion(){
    $this->InsertarProcedReqLiquidacion();
		return true;
	}

	function BusquedaProcedimientosQX($tipoProcedimiento,$codigoBus,$procedimientoBus){

		list($dbconn) = GetDBconn();
		$query="SELECT d.grupo_tipo_cargo,d.cargo,d.descripcion,d.sw_bilateral
		FROM qx_grupos_tipo_cargo a,tipos_cargos c,cups d
		WHERE a.grupo_tipo_cargo=c.grupo_tipo_cargo AND
		c.grupo_tipo_cargo=d.grupo_tipo_cargo AND c.tipo_cargo=d.tipo_cargo";
    if(!empty($tipoProcedimiento) && $tipoProcedimiento!=-1){
		  (list($val,$descrip)=explode('/',$tipoProcedimiento));
		  $query.=" AND c.tipo_cargo='".$val."'";
		}
    if($codigoBus){
      $query.=" AND d.cargo='".$codigoBus."'";
		}
		if($procedimientoBus){
      $query.=" AND d.descripcion LIKE '%".strtoupper($procedimientoBus)."%'";
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

	function SeleccionProcedimientoQX(){
	  if($_REQUEST['filtrar']){
			$this->InsertarProcedReqLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas'],$_REQUEST['numeroCirujano'],$_REQUEST['procedimientoBus'],$_REQUEST['codigoBus'],$_REQUEST['tipoProcedimiento']);
			return true;
		}
    if($_REQUEST['cargo']){
			if($_REQUEST['numeroCirujano']=='1'){
			  if(sizeof($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['PROCEDIMIENTOS'])>0){
          $conta=sizeof($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['PROCEDIMIENTOS'])+1;
				}else{
          $conta=1;
				}
				$_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['PROCEDIMIENTOS'][$conta]=$_REQUEST['cargo'];
				$_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['SW']=1;
			}
			if($_REQUEST['numeroCirujano']=='2'){
			  if(sizeof($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['PROCEDIMIENTOS'])>0){
          $conta=sizeof($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['PROCEDIMIENTOS'])+1;
				}else{
          $conta=1;
				}
				$_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['PROCEDIMIENTOS'][$conta]=$_REQUEST['cargo'];
				$_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['SW']=1;
			}
		}
		$this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas']);
		return true;
	}

	function SeleccionProfesionalBuscador(){
     if($_REQUEST['filtrar']){
			$this->BuscadorProfesional($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas'],$_REQUEST['numeroCirujanoSelect'],$_REQUEST['TipoDocumentoBus'],$_REQUEST['DocumentoBus'],$_REQUEST['NomcirujanoBus']);
			return true;
		}
		if($_REQUEST['profesional']){
			if($_REQUEST['numeroCirujanoSelect']=='1'){
				$_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['NOMBRE']=$_REQUEST['profesional'];
				$_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['SW']=1;
			}
			if($_REQUEST['numeroCirujanoSelect']=='2'){
				$_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['NOMBRE']=$_REQUEST['profesional'];
				$_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['SW']=1;
			}
		}
    $this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas']);
		return true;
	}

	function EliminaProcedimientoPresupuesto(){
    if($_REQUEST['cirujanoNum']==1){
	    unset($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO1']['PROCEDIMIENTOS'][$_REQUEST['indice']]);
		}elseif($_REQUEST['cirujanoNum']==2){
      unset($_SESSION['PRESUPUESTO_CIRUGIA']['CIRUJANO2']['PROCEDIMIENTOS'][$_REQUEST['indice']]);
		}
    $this->FormaPresupuestoCirugia($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePac'],$_REQUEST['Responsable'],$_REQUEST['tipoAfil'],$_REQUEST['rango'],$_REQUEST['semanas']);
		return true;
	}

	function TiposViasCirugia(){

    list($dbconn) = GetDBconn();
		$query = " SELECT via_acceso,descripcion
		FROM qx_vias_acceso";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
			}
		}
		return $vars;
	}

	/**
* Funcion que busca en los profesionales especialistas existentes en la base de datos
* @return array
*/
	function profesionalesEspecialistas($TipoDocumentoBus,$DocumentoBus,$NomcirujanoBus,$barra){
    $departamento=$_SESSION['LocalCirugias']['departamento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT  x.tercero_id,z.nombre_tercero as nombre,x.tipo_id_tercero
		FROM profesionales x,terceros z,
		profesionales_especialidades a,especialidades b
		WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND x.estado='1' AND
		x.tercero_id=z.tercero_id AND x.tipo_id_tercero=z.tipo_id_tercero AND
		x.tercero_id=a.tercero_id AND x.tipo_id_tercero=a.tipo_id_tercero AND
		a.especialidad=b.especialidad AND b.sw_cirujano=1";
		if($barra==1){
			if(!empty($TipoDocumentoBus) && $TipoDocumentoBus!=-1 && !empty($DocumentoBus)){
				$query.=" AND x.tercero_id='".$DocumentoBus."' AND x.tipo_id_tercero='".$TipoDocumentoBus."'";
			}
			if(!empty($NomcirujanoBus)){
				$query.=" AND z.nombre_tercero LIKE '%".strtoupper($NomcirujanoBus)."%'";
			}
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
			if(!$_REQUEST['Of']){
					$Of='0';
			}else{
				$Of=$_REQUEST['Of'];
			}
			$query.=" LIMIT " . $this->limit . " OFFSET $Of";
		}
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

	function TiposCamasQX(){
    list($dbconn) = GetDBconn();
		$query = " SELECT tipo_cama_id,descripcion,tipo_clase_cama_id
		FROM tipos_camas WHERE empresa_id='".$_SESSION['LocalCirugias']['Empresa']."'";
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





/**
* Funcion que realiza una programacion de cirugia para una una orden de servicio
* @return array
*/
	/*function OrdenesPendientesPaciente($acto){

	  list($dbconn) = GetDBconn();
		$query="SELECT cargo.cargo_cups,z.numero_orden_id
		FROM (SELECT * FROM
		(SELECT c.cargo_cups
		FROM qx_acto a,os_ordenes_servicios b,os_maestro c,os_internas d
		WHERE a.qx_acto_id='$acto' AND a.tipo_id_paciente=b.tipo_id_paciente AND
		  a.paciente_id=b.paciente_id AND b.orden_servicio_id=c.orden_servicio_id AND
		  c.sw_estado='1' AND date(c.fecha_vencimiento)>='".date("Y-m-d H:i:s")."' AND
			c.numero_orden_id=d.numero_orden_id AND c.cargo_cups=d.cargo AND d.departamento='".$_SESSION['LocalCirugias']['Departamento']."') as hola
		EXCEPT
		(SELECT f.procedimiento_qx as cargo_cups FROM qx_acto e,qx_acto_procedimientos_realizados f WHERE e.qx_acto_id='$acto' AND e.qx_acto_id=f.qx_acto_id)) as cargo,
		qx_acto x,os_ordenes_servicios y,os_maestro z,os_internas l WHERE x.qx_acto_id='$acto' AND x.tipo_id_paciente=y.tipo_id_paciente AND x.paciente_id=y.paciente_id AND
		y.orden_servicio_id=z.orden_servicio_id AND z.sw_estado='1' AND date(z.fecha_vencimiento)>='".date("Y-m-d H:i:s")."' AND z.numero_orden_id=l.numero_orden_id AND
		z.cargo_cups=l.cargo AND l.departamento='".$_SESSION['LocalCirugias']['Departamento']."' AND z.cargo_cups=cargo.cargo_cups";
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
	}*/




}//fin clase user
?>

