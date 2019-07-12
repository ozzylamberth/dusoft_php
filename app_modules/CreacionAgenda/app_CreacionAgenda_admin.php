<?php

/**
 * $Id: app_CreacionAgenda_admin.php,v 1.10 2006/05/19 17:38:28 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_CreacionAgenda_admin extends classModulo
{

	function app_CreacionAgenda_admin()
	{
		$this->limit=GetLimitBrowser();
   	//$this->limit=2;
   	return true;
		
	}

	function main()
	{
		if(!$this->FrmLogueoEmpresa()){
      return false;
    }
		return true;
	}
	
	/**
* Funcion que consulta en la base de datos los permisos del usuario para trabajar en los departamentos del sistema
* @return array
*/
	function LogueoCirugias(){
	
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;
		$query = "SELECT x.empresa_id,x.razon_social as descripcion1   
		FROM empresas as x";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		if($result->EOF){
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}else{
			while ($data = $result->FetchRow()){
				$datos[$data['descripcion1']]=$data;
			}
			$mtz[0]="EMPRESA";
			$vars[0]=$mtz;
			$vars[1]=$datos;
			return $vars;
		}
	}
	
/**
* Funcion que coloca en la session la ubicacion donde el usuario esta logueado y llama a la forma del menu que muestra todas la opciones
* @return boolean
*/
	function LlamaListadoUsuarios(){
		unset($_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO']);
		unset($_SESSION['CREACION_AGENDA_PERMISOS']['FILTRO']);
	  $_SESSION['CREACION_AGENDA_PERMISOS']['empresa']=$_REQUEST['datos_query']['empresa_id'];
		$_SESSION['CREACION_AGENDA_PERMISOS']['NombreEmp']=$_REQUEST['datos_query']['descripcion1'];		
		if(!$this->ListadoUsuarios()){
      return false;
    }
		return true;
	}
	
	/**
* Funcion que busca los datos principales de los usuarios del sistema
* @return array
*/

	function BuscarUsuariosSistema($filtro){
		
		list($dbconn) = GetDBconn();		
		if(!empty($_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO'])){
			$ordenamiento=$_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO'];
		}else{
			$ordenamiento='order by empresa_id,usuario';
		}
		$query = "select a.usuario_id,d.usuario,d.nombre,d.descripcion,
		d.passwd,d.activo,d.sw_admin,a.empresa_id,c.razon_social
		from system_usuarios_empresas a, empresas as c
		,system_usuarios_administradores b,system_usuarios as d
		where a.empresa_id=b.empresa_id
		and b.usuario_id='".UserGetUID()."' and a.empresa_id=c.empresa_id
		and a.usuario_id=d.usuario_id AND a.empresa_id='".$_SESSION['CREACION_AGENDA_PERMISOS']['empresa']."'
		--and a.usuario_id <> '".UserGetUID()."'			
		$filtro $ordenamiento";	
		
		$result = $dbconn->Execute($query);		
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
		//$query.=" LIMIT 10 OFFSET 10";
		$query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";	
		
		$result = $dbconn->Execute($query);		
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$i=0;
			while(!$result->EOF){
			$datos[$i]=$result->fields[0].'/'.$result->fields[1].'/'.$result->fields[2].'/'.$result->fields[3].'/'.$result->fields[4].'/'.$result->fields[5].'/'.
			$result->fields[6].'/'.$result->fields[7].'/'.$result->fields[8];
				$result->MoveNext();
				$i++;
			}
		}		
    $result->Close();
    return $datos;
	}
	
	function LlamaAsignarPermisosUsuarios(){    
		$this->AsignarPermisosUsuarios($_REQUEST['uid'],$_REQUEST['nombre'],$_REQUEST['empresa'],$_REQUEST['usuario'],$_REQUEST['nombreE']);
		return true;
	}
	
	function LlamaPermisoPagoCaja(){
		list($dbconn) = GetDBconn();	
		$query="SELECT caja_id
		FROM userpermisos_cajas_rapidas
		WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){				
				while (!$result->EOF) {
					$vars[]=$result->fields[0];
					$result->MoveNext();
				}
			}	
		}
		$_REQUEST['Seleccion']=$vars;		
		$this->PermisosPagoCaja($_REQUEST['uid'],$_REQUEST['nombre'],$_REQUEST['empresa'],$_REQUEST['usuario'],$_REQUEST['nombreE']);
		return true;
	}
	
	function LlamaPermisoCreacionAgenda(){
		list($dbconn) = GetDBconn();	
		$query="SELECT tipo_consulta_id
		FROM userpermisos_creacion_agenda
		WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){				
				while (!$result->EOF) {
					$vars[]=$result->fields[0];
					$result->MoveNext();
				}
			}	
		}
		$_REQUEST['Seleccion']=$vars;		
		$query="SELECT tipo_consulta_id
		FROM 	userpermisos_tipos_consulta
		WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){				
				while (!$result->EOF) {
					$vars1[]=$result->fields[0];
					$result->MoveNext();
				}
			}	
		}
		$_REQUEST['Seleccion1']=$vars1;	
		$query="SELECT tipo_consulta_id
		FROM userpermisos_consultas_cumplimientos
		WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){				
				while (!$result->EOF) {
					$vars2[]=$result->fields[0];
					$result->MoveNext();
				}
			}	
		}
		$_REQUEST['Seleccion2']=$vars2;	
		$this->PermisosCreacionAgenda($_REQUEST['uid'],$_REQUEST['nombre'],$_REQUEST['empresa'],$_REQUEST['usuario'],$_REQUEST['nombreE']);
		return true;
	}
	
	function LlamaPermisoCierreCaja(){
		list($dbconn) = GetDBconn();	
		$query="SELECT caja_id
		FROM cajas_usuarios
		WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){				
				while (!$result->EOF) {
					$vars[]=$result->fields[0];
					$result->MoveNext();
				}
			}	
		}
		$query="SELECT departamento
		FROM system_usuarios_departamentos
		WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){				
				while (!$result->EOF) {
					$varsDptos[]=$result->fields[0];
					$result->MoveNext();
				}
			}	
		}
		$_REQUEST['Seleccion']=$vars;		
		$_REQUEST['SeleccionDptos']=$varsDptos;		
		$this->PermisosCierreCaja($_REQUEST['uid'],$_REQUEST['nombre'],$_REQUEST['empresa'],$_REQUEST['usuario'],$_REQUEST['nombreE']);
		return true;
	}
	
	function LlamaPermisoPuntosAdmision(){
		list($dbconn) = GetDBconn();	
		$query="SELECT punto_admision_id
		FROM puntos_admisiones_usuarios
		WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){				
				while (!$result->EOF) {
					$vars[]=$result->fields[0];
					$result->MoveNext();
				}
			}	
		}		
		$_REQUEST['Seleccion']=$vars;			
		$this->PermisoPuntosAdmision($_REQUEST['uid'],$_REQUEST['nombre'],$_REQUEST['empresa'],$_REQUEST['usuario'],$_REQUEST['nombreE']);
		return true;
	}
	
	function LlamaPermisoEstacionEnfermeria(){
		list($dbconn) = GetDBconn();	
		$query="SELECT estacion_id
		FROM estaciones_enfermeria_usuarios
		WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){				
				while (!$result->EOF) {
					$vars[]=$result->fields[0];
					$result->MoveNext();
				}
			}	
		}		
		$_REQUEST['Seleccion']=$vars;			
		$this->PermisoEstacionEnfermeria($_REQUEST['uid'],$_REQUEST['nombre'],$_REQUEST['empresa'],$_REQUEST['usuario'],$_REQUEST['nombreE']);
		return true;
	}
	
	function CajasRapidasdelSistema(){
		list($dbconn) = GetDBconn();	
		$query="SELECT caja_id,descripcion
		FROM cajas_rapidas
		WHERE empresa_id='".$_SESSION['CREACION_AGENDA_PERMISOS']['empresa']."'
		ORDER BY descripcion";
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
	
	function TiposConsultasdelSistema(){
		list($dbconn) = GetDBconn();	
		$query="SELECT tipo_consulta_id,descripcion
		FROM tipos_consulta ORDER BY descripcion";
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
	
	function CajasdelSistema(){
		list($dbconn) = GetDBconn();	
		$query="SELECT caja_id,descripcion
		FROM cajas
		WHERE empresa_id='".$_SESSION['CREACION_AGENDA_PERMISOS']['empresa']."'
		ORDER BY descripcion";
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
	
	function PuntosAdmisionSistema(){
		list($dbconn) = GetDBconn();	
		$query="SELECT a.punto_admision_id,a.descripcion
		FROM puntos_admisiones a,departamentos dpto
		WHERE dpto.empresa_id='".$_SESSION['CREACION_AGENDA_PERMISOS']['empresa']."' AND
		a.departamento=dpto.departamento
		ORDER BY descripcion";
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
	
	function EstacionesEnfermeriaSistema(){
		list($dbconn) = GetDBconn();	
		$query="SELECT a.estacion_id,a.descripcion
		FROM estaciones_enfermeria a,departamentos dpto
		WHERE dpto.empresa_id='".$_SESSION['CREACION_AGENDA_PERMISOS']['empresa']."' AND
		a.departamento=dpto.departamento
		ORDER BY descripcion";
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
	
	function DepartamentosdelSistema(){
		list($dbconn) = GetDBconn();	
		$query="SELECT departamento,descripcion
		FROM departamentos
		WHERE empresa_id='".$_SESSION['CREACION_AGENDA_PERMISOS']['empresa']."'
		ORDER BY descripcion";
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
	
	function GuardarPermisosPagoCaja(){
		list($dbconn) = GetDBconn();	
		$dbconn->BeginTrans();
		$vectorCajas=$_REQUEST['Seleccion'];
		/*if(empty($vectorCajas)){
			$this->frmError["MensajeError"]="Seleccione la cajas que se van a asignar al usuario.";		  
		  if($this->PermisosPagoCaja($_REQUEST['uid'],$_REQUEST['nombre'],$_REQUEST['empresa'],$_REQUEST['usuario'],$_REQUEST['nombreE'])){
			  return true;
			}
		}*/
		$query="DELETE FROM userpermisos_cajas_rapidas WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$query='';		
		for($i=0;$i<sizeof($vectorCajas);$i++){
			$query.="INSERT INTO userpermisos_cajas_rapidas(usuario_id,caja_id)
			VALUES('".$_REQUEST['uid']."','".$vectorCajas[$i]."');";
		}			
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$dbconn->CommitTrans();
		$mensaje='Cajas Asignadas a los Usuarios';
		$titulo='ASIGNACIÓN DE CAJAS RAPIDAS';
		$accion=ModuloGetURL('app','CreacionAgenda','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$_REQUEST['uid'],"nombre"=>$_REQUEST['nombre'],"empresa"=>$_REQUEST['empresa'],"usuario"=>$_REQUEST['usuario'],"nombreE"=>$_REQUEST['nombreE']));
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		return true;			
	}
	
	function GuardarPermisosCierreCaja(){
		list($dbconn) = GetDBconn();	
		$dbconn->BeginTrans();
		$vectorCajas=$_REQUEST['Seleccion'];
		$vectorDptos=$_REQUEST['SeleccionDptos'];		
		/*if(empty($vectorCajas)){
			$this->frmError["MensajeError"]="Seleccione la cajas que se van a asignar al usuario.";		  
		  if($this->PermisosCierreCaja($_REQUEST['uid'],$_REQUEST['nombre'],$_REQUEST['empresa'],$_REQUEST['usuario'],$_REQUEST['nombreE'])){
			  return true;
			}
		}*/
		$query="DELETE FROM cajas_usuarios WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$query='';
		for($i=0;$i<sizeof($vectorCajas);$i++){
			$query.="INSERT INTO cajas_usuarios(usuario_id,caja_id)
			VALUES('".$_REQUEST['uid']."','".$vectorCajas[$i]."');";
		}					
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$query='';
		$query="DELETE FROM system_usuarios_departamentos WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$query='';
		for($i=0;$i<sizeof($vectorDptos);$i++){
			$query.="INSERT INTO system_usuarios_departamentos(usuario_id,departamento)
			VALUES('".$_REQUEST['uid']."','".$vectorDptos[$i]."');";
		}					
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$dbconn->CommitTrans();
		$mensaje='Cajas Asignadas a los Usuarios';
		$titulo='ASIGNACIÓN DE CAJAS';
		$accion=ModuloGetURL('app','CreacionAgenda','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$_REQUEST['uid'],"nombre"=>$_REQUEST['nombre'],"empresa"=>$_REQUEST['empresa'],"usuario"=>$_REQUEST['usuario'],"nombreE"=>$_REQUEST['nombreE']));
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		return true;		
	}	
	
	function GuardarPermisosCreaciónAgenda(){
		list($dbconn) = GetDBconn();	
		$dbconn->BeginTrans();
		$vectorTiposCon=$_REQUEST['Seleccion'];
		$vectorTiposCon1=$_REQUEST['Seleccion1'];
		$vectorTiposCon2=$_REQUEST['Seleccion2'];		
		$query="DELETE FROM userpermisos_creacion_agenda WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$query='';
		for($i=0;$i<sizeof($vectorTiposCon);$i++){
			$query.="INSERT INTO userpermisos_creacion_agenda(usuario_id,tipo_consulta_id)
			VALUES('".$_REQUEST['uid']."','".$vectorTiposCon[$i]."');";
		}					
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$query="DELETE FROM userpermisos_tipos_consulta WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$query='';
		for($i=0;$i<sizeof($vectorTiposCon1);$i++){
			$query.="INSERT INTO userpermisos_tipos_consulta(usuario_id,tipo_consulta_id)
			VALUES('".$_REQUEST['uid']."','".$vectorTiposCon1[$i]."');";
		}					
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$query="DELETE FROM userpermisos_consultas_cumplimientos WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$query='';
		for($i=0;$i<sizeof($vectorTiposCon2);$i++){
			$query.="INSERT INTO userpermisos_consultas_cumplimientos(usuario_id,tipo_consulta_id)
			VALUES('".$_REQUEST['uid']."','".$vectorTiposCon2[$i]."');";
		}					
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$dbconn->CommitTrans();
		$mensaje='Permisos Asignados al Usuario';
		$titulo='ASIGNACIÓN DE PERMISOS DE CREACIÓN Y CITAS MÉDICAS';
		$accion=ModuloGetURL('app','CreacionAgenda','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$_REQUEST['uid'],"nombre"=>$_REQUEST['nombre'],"empresa"=>$_REQUEST['empresa'],"usuario"=>$_REQUEST['usuario'],"nombreE"=>$_REQUEST['nombreE']));
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		return true;		
	}	
	
	
	/*$num es el numero de opcion que escogio en el combo */
	/*$busca es la busqueda*/
	function GetFiltroUsuarios($num,$busca)
	{

			switch($num)
			{
					case "1":
					{
						if(is_numeric($busca))
						{
									$filtro="AND d.usuario_id=".trim($busca)."";

						}
						else
						{
									$filtro="";
						}
						$_SESSION['CENTRAL']['negrilla']=1;
						break;
					}
					case "2":
					{
									$filtro="AND lower(d.usuario) like '%".strtolower(trim($busca))."%'";
										 //or lower(d.usuario) like '%".strtolower(trim($busca))."'
										// or lower(d.usuario) like '".strtolower(trim($busca))."%'
										$_SESSION['CENTRAL']['negrilla']=2;
						break;
					}
					case "3":
					{
									$filtro="AND lower(d.nombre) like '%".strtolower(trim($busca))."%'";
										 //or lower(d.nombre) like '%".strtolower(trim($busca))."'
										 //or lower(d.nombre) like '".strtolower(trim($busca))."%'
										 $_SESSION['CENTRAL']['negrilla']=3;
						break;
					}
			}
			return $filtro;
	}
	
	function GuardarPermisosPuntosAdmision(){
		list($dbconn) = GetDBconn();	
		$dbconn->BeginTrans();
		$vectorPuntos=$_REQUEST['Seleccion'];	
		
		$query="DELETE FROM puntos_admisiones_usuarios WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$query='';
		for($i=0;$i<sizeof($vectorPuntos);$i++){
			$query.="INSERT INTO puntos_admisiones_usuarios(usuario_id,punto_admision_id)
			VALUES('".$_REQUEST['uid']."','".$vectorPuntos[$i]."');";
		}					
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}		
		$dbconn->CommitTrans();
		$mensaje='Puntos de Admision Asignados al Usuario';
		$titulo='ASIGNACIÓN DE PUNTOS DE ADMISION';
		$accion=ModuloGetURL('app','CreacionAgenda','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$_REQUEST['uid'],"nombre"=>$_REQUEST['nombre'],"empresa"=>$_REQUEST['empresa'],"usuario"=>$_REQUEST['usuario'],"nombreE"=>$_REQUEST['nombreE']));
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		return true;		
	}
	
	function GuardarPermisosEstacionesEnfermeria(){
		list($dbconn) = GetDBconn();	
		$dbconn->BeginTrans();
		$vectorEstaciones=$_REQUEST['Seleccion'];	
		
		$query="DELETE FROM estaciones_enfermeria_usuarios WHERE usuario_id='".$_REQUEST['uid']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$query='';
		for($i=0;$i<sizeof($vectorEstaciones);$i++){
			$query.="INSERT INTO estaciones_enfermeria_usuarios(usuario_id,estacion_id)
			VALUES('".$_REQUEST['uid']."','".$vectorEstaciones[$i]."');";
		}					
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}		
		$dbconn->CommitTrans();
		$mensaje='Estaciones de Enfemeria Asignadas al Usuario';
		$titulo='ASIGNACIÓN DE ESTACIONES DE ENFERMERIA';
		$accion=ModuloGetURL('app','CreacionAgenda','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$_REQUEST['uid'],"nombre"=>$_REQUEST['nombre'],"empresa"=>$_REQUEST['empresa'],"usuario"=>$_REQUEST['usuario'],"nombreE"=>$_REQUEST['nombreE']));
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		return true;		
	}
	
	function LlamaPermisosEnDepartamento(){
		list($dbconn) = GetDBconn();
		$query = "SELECT a.centro_utilidad,a.unidad_funcional,a.departamento,a.auditor
		FROM userpermisos_repconsultaexterna a
		WHERE a.usuario_id='".$_REQUEST['uid']."'";		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				while(!$result->EOF){
					$vector[]=$result->fields[0].','.$result->fields[1].','.$result->fields[2];
					$auditor=$result->fields[3];
					$result->MoveNext();
				}
			}     
		}	
		if($auditor){
			$_REQUEST['todosProfesionalesRepCE']='1';
		}		
		$query = "SELECT b.centro_utilidad,b.unidad_funcional,b.departamento
		FROM userpermisos_os_atencion a,departamentos b
		WHERE a.usuario_id='".$_REQUEST['uid']."' AND a.departamento=b.departamento";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				while(!$result->EOF){
					$vectorUno[]=$result->fields[0].','.$result->fields[1].','.$result->fields[2];
					$result->MoveNext();
				}
			}     
		}		
		
		$query = "SELECT b.centro_utilidad,b.unidad_funcional,b.departamento
		FROM 	userpermisos_central a,departamentos b
		WHERE a.usuario_id='".$_REQUEST['uid']."' AND a.departamento=b.departamento";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				while(!$result->EOF){
					$vectorDos[]=$result->fields[0].','.$result->fields[1].','.$result->fields[2];
					$result->MoveNext();
				}
			}     
		}			
		$_REQUEST['Seleccion']=$vector;		
		$_REQUEST['SeleccionUno']=$vectorUno;		
		$_REQUEST['SeleccionDos']=$vectorDos;		
		$this->PermisosEnDepartamento($_REQUEST['uid'],$_REQUEST['nombre'],$_REQUEST['empresa'],$_REQUEST['usuario'],$_REQUEST['nombreE']);
		return true;
	}	
	
	function CentrosUtilidad($empresa){
	
		list($dbconn) = GetDBconn();
		$query = "SELECT a.centro_utilidad,
							a.descripcion AS centro
				FROM centros_utilidad a
				WHERE a.empresa_id='".$empresa."' 								
				ORDER BY a.descripcion;";				
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
      return $vars;
		}
	}
	
	function Unidades_Funcionales($empresa,$centro_utilidad){
	
		list($dbconn) = GetDBconn();
		$query = "SELECT a.unidad_funcional,
               			  a.descripcion AS unidad
					FROM   unidades_funcionales a
					WHERE a.empresa_id='".$empresa."'
					AND a.centro_utilidad='".$centro_utilidad."'				
					ORDER BY a.descripcion;";
				
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
      return $vars;
		}
	}	
	
	function Departamentos($empresa,$centro_utilidad,$unidad_funcional){
	
		list($dbconn) = GetDBconn();
		$query = "SELECT a.departamento,
               			  a.descripcion AS nombre_dpto
					FROM   departamentos a,servicios b
					WHERE a.empresa_id='".$empresa."'
					AND a.centro_utilidad='".$centro_utilidad."'
          AND a.unidad_funcional='".$unidad_funcional."'
					AND a.servicio=b.servicio 
					AND b.sw_asistencial='1'										 
					ORDER BY a.descripcion;";
				
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount()>0){
				while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
      return $vars;
		}
	}	
	
	function GuardarPermisosEnDepartamento(){
		
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$Seleccion=$_REQUEST['Seleccion'];		
		$SeleccionUno=$_REQUEST['SeleccionUno'];		
		$SeleccionDos=$_REQUEST['SeleccionDos'];		
		$query='';
		$query.="DELETE FROM userpermisos_repconsultaexterna WHERE usuario_id='".$_REQUEST['uid']."';";
		$query.="DELETE FROM userpermisos_busqueda_agenda WHERE usuario_id='".$_REQUEST['uid']."';";
		for($i=0;$i<sizeof($Seleccion);$i++){
			(list($centro,$Unidad,$depto)=explode(',',$Seleccion[$i]));
			$query.="INSERT INTO userpermisos_repconsultaexterna(empresa_id,usuario_id,centro_utilidad,unidad_funcional,departamento)
			VALUES('".$_SESSION['CREACION_AGENDA_PERMISOS']['empresa']."','".$_REQUEST['uid']."','$centro','$Unidad','$depto');";
			$query.="INSERT INTO userpermisos_busqueda_agenda(usuario_id,departamento)
			VALUES('".$_REQUEST['uid']."','$depto');";
		}
		if($_REQUEST['todosProfesionalesRepCE']){
			$query.="UPDATE userpermisos_repconsultaexterna SET auditor='1' WHERE usuario_id='".$_REQUEST['uid']."';";
		}else{
			$query.="UPDATE userpermisos_repconsultaexterna SET auditor='0' WHERE usuario_id='".$_REQUEST['uid']."';";
		}					
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$query='';
		$query.="DELETE FROM userpermisos_central WHERE usuario_id='".$_REQUEST['uid']."';";
		for($i=0;$i<sizeof($SeleccionUno);$i++){
			(list($centro,$Unidad,$depto)=explode(',',$SeleccionUno[$i]));
			$query.="INSERT INTO userpermisos_central(usuario_id,departamento)
			VALUES('".$_REQUEST['uid']."','$depto');";
		}					
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		$query='';
		$query.="DELETE FROM userpermisos_os_atencion WHERE usuario_id='".$_REQUEST['uid']."';";
		for($i=0;$i<sizeof($SeleccionDos);$i++){
			(list($centro,$Unidad,$depto)=explode(',',$SeleccionDos[$i]));
			$query.="INSERT INTO userpermisos_os_atencion(usuario_id,departamento)
			VALUES('".$_REQUEST['uid']."','$depto');";
		}					
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		
		$dbconn->CommitTrans();					
		$mensaje='Permisos Asignados a los Usuarios';
		$titulo='PERMISOS EN LOS DEPARTAMENTOS';
		$accion=ModuloGetURL('app','CreacionAgenda','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$_REQUEST['uid'],"nombre"=>$_REQUEST['nombre'],"empresa"=>$_REQUEST['empresa'],"usuario"=>$_REQUEST['usuario'],"nombreE"=>$_REQUEST['nombreE']));
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		return true;			
	}
  
  function ConsultaEstadoRegFallasSistema($uid){    
    list($dbconn) = GetDBconn();
    $query = "SELECT sw_estado
          FROM userpermisos_fallas_sistema
          WHERE usuario_id='".$uid."';";        
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      return $result->fields[0]; 
    }
  }
  
  function PermisosUsuariosRegFallas(){
    if($_REQUEST['estado']=='1'){
      $cambioEstado='0';
    }else{
      $cambioEstado='1';
    }
    list($dbconn) = GetDBconn();
    $query = "SELECT *
          FROM userpermisos_fallas_sistema
          WHERE usuario_id='".$_REQUEST['uid']."';";        
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        $query = "UPDATE userpermisos_fallas_sistema
                  SET sw_estado='".$cambioEstado."'
                  WHERE usuario_id='".$_REQUEST['uid']."';";        
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
      }else{
        $query = "INSERT INTO userpermisos_fallas_sistema(usuario_id,sw_estado)
                  VALUES('".$_REQUEST['uid']."','0');";        
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }        
      } 
    }   
    $this->AsignarPermisosUsuarios($_REQUEST['uid'],$_REQUEST['nombre'],$_REQUEST['empresa'],$_REQUEST['usuario'],$_REQUEST['nombreE']);
    return true;
  }
  
  function LlamaPermisoPuntosFacturacionRips(){
    list($dbconn) = GetDBconn();  
    $query="SELECT punto_facturacion_id
    FROM  userpermisos_tipos_facturas
    WHERE usuario_id='".$_REQUEST['uid']."'";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){       
        while (!$result->EOF) {
          $vars[]=$result->fields[0];
          $result->MoveNext();
        }
      } 
    }   
    $_REQUEST['Seleccion']=$vars;     
    $this->PermisoPuntosFacturacionRips($_REQUEST['uid'],$_REQUEST['nombre'],$_REQUEST['empresa'],$_REQUEST['usuario'],$_REQUEST['nombreE']);
    return true;
  }
  
  function PuntosFacturacionRips($Empresa){
    list($dbconn) = GetDBconn();  
    $query="SELECT a.punto_facturacion_id,a.descripcion
    FROM puntos_facturacion a   
    WHERE empresa_id='".$Empresa."'    
    ORDER BY a.descripcion";
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
  
  function GuardarPermisosPuntosFacturacionRips(){
    list($dbconn) = GetDBconn();  
    $dbconn->BeginTrans();
    $vectorPuntos=$_REQUEST['Seleccion']; 
    
    $query="DELETE FROM userpermisos_tipos_facturas WHERE usuario_id='".$_REQUEST['uid']."'";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $dbconn->RollbackTrans();
      return false;
    }
    $query='';
    for($i=0;$i<sizeof($vectorPuntos);$i++){
      $query.="INSERT INTO userpermisos_tipos_facturas(usuario_id, punto_facturacion_id)
      VALUES('".$_REQUEST['uid']."','".$vectorPuntos[$i]."');";
    }         
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $dbconn->RollbackTrans();
      return false;
    }   
    $dbconn->CommitTrans();
    $mensaje='Puntos de Facturacion Asignados al Usuario';
    $titulo='ASIGNACIÓN DE PUNTOS DE FACTURACION';
    $accion=ModuloGetURL('app','CreacionAgenda','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$_REQUEST['uid'],"nombre"=>$_REQUEST['nombre'],"empresa"=>$_REQUEST['empresa'],"usuario"=>$_REQUEST['usuario'],"nombreE"=>$_REQUEST['nombreE']));
    $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
    return true;    
  }
	
	
	
}
?>
