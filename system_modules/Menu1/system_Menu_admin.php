<?php

/**
*MODULO Administrativo para el Manejo de Usuarios del Sistema
*
* @author Lorena Aragon & Jairo Duvan Diaz Martinez
* ultima actualizacion: Jairo Duvan Diaz Martinez -->lunes 1 de marzo 2004
*/

// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Email: intersof@telesat.com.co
// ----------------------------------------------------------------------

/**
*Contiene los metodos para realizar la administracion de usuarios
*/

class system_Menu_admin extends classModulo
{
		var $limit;
		var $conteo;

	function system_Menu_admin()
	{
		$this->limit=GetLimitBrowser();
  	return true;
	}



/**
* Funcion donde se llama la funcion Menu
* @return boolean
*/

	function main(){

    if(!$this->Menu()){
        return false;
    }
		return true;
  }


/**
* Funcion que lista los directorios dentro de la carpeta de THEMAS
* @return array
*/

	function listarDirectorios(){
    global $VISTA;

		$themes=opendir("themes/$VISTA");
		$i=0;
    while ($file = readdir($themes)) {
      if ($file != "." && $file != "..") {
        $archivos[$i]=$file;
				$i++;
	    }
	 }
	 closedir($themes);
	 return $archivos;
	}


/**
* Funcion donde se llama la funcion FormaInsertarUsuarioSistema
* @return boolean
*/
function Usuario(){

    $action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
		if(!$this->FormaInsertarUsuarioSistema('','','','','',$action,'','')){
        return false;
    }
		return true;
  }



/**
* Funcion donde se Modifica en la base de datos el estado(1=activo,0=inactivo) del usuarios en el sistema
* @return boolean
*/

	function ModificarEstadoUsuarioIp(){

		$ip=$_REQUEST['ip'];
    list($dbconn) = GetDBconn();
	  $query = "SELECT sw_bloqueo FROM system_host WHERE ip='$ip'";
	  $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
		}else{

      if($result->fields[0]=='1'){
	      $query = "UPDATE system_host SET sw_bloqueo='0' WHERE ip='$ip'";
	      $result = $dbconn->Execute($query);
		  }else{
	      $query = "UPDATE system_host SET sw_bloqueo='1' WHERE ip='$ip'";
	      $result = $dbconn->Execute($query);
		  }
		}
      if($_REQUEST['marca']==true)
			{
				$this->ListadoAccesos($_REQUEST['dats'],$ip,$_REQUEST['host']);
				return true;
			}
			else
			{
				$this->ListadoGeneralSistema();
				return true;
			}
  }


/**
* Funcion donde si tiene conexion 
* @return boolean
*/

  function BuscarConexion($uid)
	{
			list($dbconn) = GetDBconn();

		$query = "select count(*) from system_session where usuario_id=$uid;";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Guardar en la Base de Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
        $existencia=$res->fields[0];
		    return $existencia;
		}
	}


  function ListadoIps()
	{
					list($dbconn) = GetDBconn();
					$query="
					select B.*, C.inicio_session
					from
					(
					SELECT MAX(A.ultimo_acceso_session), A.usuario_id,
					A.ip, A.hostname, A.usuario, A.nombre , A.sw_bloqueo
					FROM
					(
						select  a.ip,a.hostname,a.sw_bloqueo,
									e.inicio_session,e.ultimo_acceso_session,
									e.usuario_id,c.usuario,c.nombre
									from system_host a
									left join system_session as e on(a.ip=e.ip_address)
									left join system_usuarios as c on( c.usuario_id=e.usuario_id)
									order by a.ip ,e.usuario_id,e.ultimo_acceso_session  desc
					) AS A
					group by A.usuario_id, A.ip, A.hostname,  A.usuario, A.nombre, A.sw_bloqueo
					order by A.ip
					) AS B,
					(
					select  a.ip,a.hostname,a.sw_bloqueo,
									e.inicio_session,e.ultimo_acceso_session,
									e.usuario_id,c.usuario,c.nombre
									from system_host a
									left join system_session as e on(a.ip=e.ip_address)
									left join system_usuarios as c on( c.usuario_id=e.usuario_id)
									order by a.ip ,e.usuario_id,e.ultimo_acceso_session  desc
					) as C
					where (B.max = C.ultimo_acceso_session And
					B.ip=C.ip) or (B.max is null And B.ip=C.ip)";
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0){
						$this->error = "Error al listar las direcciones ip's";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					$i=0;

					while (!$resulta->EOF)
					{
						$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
						//$datos[$var['ip']][$var['usuario']][sizeof($datos[$var['ip']][$var['usuario']])]=$var;
						$resulta->MoveNext();
						$i++;
					}

					return $var;
	}


function EstadoIps($ip)
	{
					list($dbconn) = GetDBconn();
  				$query="SELECT sw_bloqueo from system_host WHERE ip='$ip'";
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0){
						$this->error = "Error al listar las direcciones ip's";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
          $ips=	$resulta->fields[0];
					return $ips;
	}





	function VerListadoAcceso()
	{
			$ip=$_REQUEST['ip'];
			$host=$_REQUEST['host'];
			list($dbconn) = GetDBconn();
			$query="select  b.descripcion,b.tipo_alerta_id,
			a.log,a.tipo_log,a.fecha,a.detalle
			from system_host_log a,system_tipos_log b
 			where a.tipo_log=b.tipo_log_id and a.host='$ip' order by a.fecha desc LIMIT 10 OFFSET 0;";
			$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			else
				{
				    	$i=0;
							while(!$resulta->EOF)
									{
											$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
											$resulta->MoveNext();
											$i++;
									}
					}
					$this->ListadoAccesos($var,$ip,$host);
					return true;
 }




/**
* Funcion que busca los datos principales de los usuarios del sistema
* @return array
*/

	function BuscarUsuariosSistema(){

		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo'])){
	  $query = "SELECT count(*) FROM system_usuarios WHERE usuario_id > 0";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
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
    $query = "SELECT usuario_id,
		                 usuario,
										 nombre,
										 descripcion,
										 passwd,
										 activo,
										 sw_admin
			          FROM system_usuarios WHERE usuario_id > 0 ORDER BY usuario LIMIT " . $this->limit . " OFFSET $Of";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    $i=0;
		while(!$result->EOF){
			$datos[$i]=$result->fields[0].'/'.$result->fields[1].'/'.$result->fields[2].'/'.$result->fields[3].'/'.$result->fields[4].'/'.$result->fields[5].'/'.$result->fields[6];
      $result->MoveNext();
			$i++;
		}
    $result->Close();
    return $datos;
	}

//OJO CON ESTA FUNCION QUE ES DE REVISAR EL LOGUEO DE LA PERSONA.............1588

	function BuscarLog($uid,$señal='')
	{
			list($dbconn) = GetDBconn();
			if($señal==true)
			{
				$LIMITE='LIMIT 5 OFFSET 0';
			}
		//	if($señal==)
			$query="select a.fecha,b.descripcion,b.tipo_alerta_id from system_usuarios_log a,
			system_tipos_log b where usuario_id=$uid and
			a.tipo_log=b.tipo_log_id  order by fecha desc $LIMITE";
			$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			else
				{
          if($señal==false)
					{
						$var=$resulta->RecordCount();
					}
					else
					{
							$i=0;
							while(!$resulta->EOF)
									{
											$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
											$resulta->MoveNext();
											$i++;
									}
					}
					return $var;
				}
 }


/**
* Funcion que busca el estado(1=activo,0=inactivo) actual en la base de datos del usuario en el sistema
* @return boolean
*/

	function BuscaEstadoAfiliado($uid){
    list($dbconn) = GetDBconn();
	  $query = "SELECT activo FROM system_usuarios WHERE usuario_id='$uid'";
	  $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
		}else{
      $dato=$result->fields[0];
		}
		$result->Close();
    return $dato;
	}

/**
* Funcion que busca el estado(1=activo,0=inactivo) actual en la base de datos del usuario en el sistema
* @return boolean
*/

	function RevisarTema($uid){
    list($dbconn) = GetDBconn();
	  $query = "SELECT valor from system_usuarios_vars WHERE variable='Tema' and usuario_id='$uid'";
	  $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
		}else{
      $dato=$result->fields[0];
		}
		return $dato;
	}


/**
* Funcion que llama la funcion FormaAsignarPermisosUsuarios
* @return boolean
*/

	function LlamaAsignarPermisosUsuarios(){

    $uid=$_REQUEST['uid'];
		$NombreUsuario=$_REQUEST['nombre'];
    $Usuario=$_REQUEST['usuario'];
    $this->FormaAsignarPermisosUsuarios($uid,$NombreUsuario,$Usuario);
		return true;
	}


/**
* Funcion donde se llama la funcion FormaInsertarUsuarioSistema
* @return boolean
*/

	function LlamaModificarUsuarioSistema(){
    $uid=$_REQUEST['uid'];
		$nombre=$_REQUEST['nombre'];
    $usuario=$_REQUEST['usuario'];
		$tema=$_REQUEST['tema'];
		$descripcion=$_REQUEST['descripcion'];
    $consulta='1';
		$action=ModuloGetURL('system','Usuarios','admin','ModificarUsuariosSistema',array("uid"=>$uid));
		if(!$this->FormaInsertarUsuarioSistema($nombre,$usuario,'','',$tema,$action,$consulta,$descripcion,true,$uid)){
        return false;
    }
		return true;
  }


	/**
* Funcion donde se Modifican en la base de datos los datos principales de un usuario que ya exite en el sistema
* @return boolean
*/

	function ModificarUsuariosSistema(){
    $uid=$_REQUEST['uid'];
		$nombreUsuario=$_REQUEST['nombreUsuario'];
    $tema=$_REQUEST['tema'];
		$descripcion=$_REQUEST['descripcion'];
		$activo=$_REQUEST['activo'];
		$administrador=$_REQUEST['administrador'];
    $loginUsuario=$_REQUEST['loginUsuario'];

		if($nombreUsuario=='' || $loginUsuario==''){
			if($nombreUsuario==''){ $this->frmError["nombreUsuario"]=1; }
			if($loginUsuario==''){ $this->frmError["loginUsuario"]=1; }
			$this->frmError["MensajeError"]="Faltan datos obligatorios.";
			$consulta='1';
      $action=ModuloGetURL('system','Usuarios','admin','ModificarUsuariosSistema',array("uid"=>$uid));
			if(!$this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,'','',$tema,$action,$consulta,$descripcion)){
				return false;
			}
			return true;
		}
		if($activo){$activo='1';
		}else{$activo='0';}
    if($administrador){$administrador='1';
		}else{$administrador='0';}
		if($tema==-1){$tema='';}

		$login=$this->verificaExisteLoginInsertado($loginUsuario,$uid);
		if($login){
				$this->frmError["MensajeError"]="Este login ya existe Debe Cambiarlo";
        $consulta='1';
				$action=ModuloGetURL('system','Usuarios','admin','ModificarUsuariosSistema',array("uid"=>$uid));
			  $this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,'','',$tema,$action,$consulta,$descripcion);
			  return true;
		}

		$nombreUsuario=strtoupper($nombreUsuario);
		$loginUsuario=strtoupper($loginUsuario);

		list($dbconn) = GetDBconn();
		$query = "UPDATE system_usuarios SET usuario='$loginUsuario',
		                                      nombre='$nombreUsuario',
																					descripcion='$descripcion',
																					activo='$activo',
																					sw_admin='$administrador'
																					WHERE usuario_id='$uid'";
		$dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al actualizar en system_usuarios";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$query = "SELECT COUNT(*) FROM  system_usuarios_vars WHERE variable='Tema' AND usuario_id='$uid'";
		$res=$dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al buscar en system_usuarios_vars";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$conteo=$res->fields[0];
		if($conteo > 0)
		{
								if(!empty($tema))
								{
                 UserSetVar($uid,'Tema',$tema);
								}
								else
								{
									UserDelVar($uid,'Tema');
								}

		}
		else
		{UserSetVar($uid,'Tema',$tema);}
		$this->ListadoUsuariosSistema();
		return true;

	}

/**
* Funcion donde se Modifica en la base de datos el estado(1=activo,0=inactivo) del usuarios en el sistema
* @return boolean
*/

function BorrarUsuarios()
{
						list($dbconn) = GetDBconn();
						$uid=$_REQUEST['uid'];
						$query = "DELETE FROM system_usuarios WHERE usuario_id=$uid";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
						$this->frmError["MensajeError"]="EL USUARIO NO SE BORRO YA QUE TIENE RELACIONES EN OTRAS TABLAS.";
            if(!$this->ListadoUsuariosSistema()){
						return false;
						}
						return true;
					}

				UserDelVar($uid,'Tema');			
				$this->ListadoUsuariosSistema();
				return true;
}

function ModificarEstadoUsuario(){

		$uid=$_REQUEST['uid'];
    $TipoForma=$_REQUEST['TipoForma'];
    $NombreUsuario=$_REQUEST['NombreUsuario'];
    $Usuario=$_REQUEST['Usuario'];

		list($dbconn) = GetDBconn();
	  $query = "SELECT activo FROM system_usuarios WHERE usuario_id='$uid'";
	  $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
		}else{
      $result->fields[0];
      if($result->fields[0]=='1'){
	      $query = "UPDATE system_usuarios SET activo='0' WHERE usuario_id='$uid'";
	      $result = $dbconn->Execute($query);
		  }else{
	      $query = "UPDATE system_usuarios SET activo='1' WHERE usuario_id='$uid'";
	      $result = $dbconn->Execute($query);
		  }
		}
    if(!$TipoForma){
		  $this->ListadoUsuariosSistema();
		  return true;
		}elseif($TipoForma==1){
      $this->FormaAsignarPermisosUsuarios($uid,$NombreUsuario,$Usuario);
			return true;
		}else{
      $this->AsignarDepartamentosUsuario($uid,$NombreUsuario,$Usuario);
			return true;
		}
  }

/**
* Funcion que llama a la funcion FormaModificarPasswd
* @return boolean
*/

function LlamaFormaModificarPasswd(){
    $uid=$_REQUEST['uid'];
		$nombre=$_REQUEST['nombre'];
		$usuario=$_REQUEST['usuario'];
		$action=ModuloGetURL('system','Usuarios','admin','ModificarPasswd',array("uid"=>$uid));
		if(!$this->FormaModificarPasswd($action,'','',$nombre,$usuario)){
        return false;
    }
		return true;
	}

/**
* Funcion que modifica en la base de datos el password actual que tiene el usuario en el sistema
* @return boolean
*/

  function ModificarPasswd(){

		$uid=$_REQUEST['uid'];
		$password=$_REQUEST['password'];
    $passwordReal=$_REQUEST['passwordReal'];
		$nombre=$_REQUEST['nombre'];
		$usuario=$_REQUEST['usuario'];

		//$_REQUEST['resetear'];

		if($_REQUEST['aceptar'])
		{
						if($password=='' || $passwordReal==''){
							if($password==''){ $this->frmError["password"]=1; }
							if($passwordReal==''){ $this->frmError["passwordReal"]=1; }
							$this->frmError["MensajeError"]="Faltan datos obligatorios.";
							$action=ModuloGetURL('system','Usuarios','admin','ModificarPasswd',array("uid"=>$uid));
							$this->FormaModificarPasswd($action,$password,$passwordReal,$nombre,$usuario);
							return true;
						}

						if(strcmp($password,$passwordReal)==0){
							$passwd=UserEncriptarPasswd($password);
						}else{
							$this->frmError["MensajeError"]="La Contraseña esta Errada.";
							$action=ModuloGetURL('system','Usuarios','admin','ModificarPasswd',array("uid"=>$uid));
							$this->FormaModificarPasswd($action,'','',$nombre,$usuario);
							return true;
						}

						list($dbconn) = GetDBconn();
						$query = "UPDATE system_usuarios SET passwd='$passwd' WHERE usuario_id='$uid'";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al actualizar en la tabla system_usuarios";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						  }else{
							$this->ListadoUsuariosSistema();
							return true;
						 }

		}
		elseif($_REQUEST['resetear'])
		{
			UserResetPasswd($uid);
			$this->ListadoUsuariosSistema();
			return true;
		}


 }

 /**
* Funcion donde se llama la funcion listadoUsuariosSistema
* @return boolean
*/

  function listadoUsuarios(){
    if(!$this->ListadoUsuariosSistema()){
      return false;
    }
	  return true;
  }

/**
* Funcion que verifica si en la base de datos existe el login que tiene el usuario del sistema
* @return string
* @param string login del usuario
*/

  function verificaExisteLogin($login){

		$login=strtoupper($login);
		list($dbconn) = GetDBconn();
	  $query = "SELECT * FROM system_usuarios WHERE usuario='$login'";
	  $result = $dbconn->Execute($query);
		$datos=$result->RecordCount();

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($datos){
         return 1;
			}
		}
  }

/**
* Funcion que verifica si el usuario del sistema al cambiar el login, este ya existe en la base de datos para otro usuario
* @return boolean
* @param string login del usuario
* @param integer identificacion unica del usuario
*/

	function verificaExisteLoginInsertado($login,$uid){

		$login=strtoupper($login);
		list($dbconn) = GetDBconn();
	  $query = "SELECT * FROM system_usuarios WHERE usuario='$login' AND usuario_id!='$uid'";
	  $result = $dbconn->Execute($query);
		$datos=$result->RecordCount();

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($datos){
         return 1;
			}
		}
  }


	/**
* Funcion donde se Insertan datos de un usuario del sistema que fue creado en la forma
* @return boolean
*/

	function InsertarUsuariosSistema(){
	  $nombreUsuario=$_REQUEST['nombreUsuario'];
    $tema=$_REQUEST['tema'];
	 	$activo=$_REQUEST['activo'];
		$administrador=$_REQUEST['administrador'];
		$descripcion=$_REQUEST['descripcion'];
    $loginUsuario=$_REQUEST['loginUsuario'];
    $password=$_REQUEST['password'];
    $passwordReal=$_REQUEST['passwordReal'];
		$action=$_REQUEST['action'];

		if($nombreUsuario=='' || $loginUsuario==''){
			if($nombreUsuario==''){ $this->frmError["nombreUsuario"]=1; }
			if($loginUsuario==''){ $this->frmError["loginUsuario"]=1; }
			//if($password==''){ $this->frmError["password"]=1; }
      //if($passwordReal==''){ $this->frmError["passwordReal"]=1; }
			$this->frmError["MensajeError"]="Faltan datos obligatorios.";
      $action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
			$this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion);
			return true;
		}
    if($activo){$activo='1';
		}else{$activo='0';}
    if($administrador){$administrador='1';
		}else{$administrador='0';}
    if($tema==-1){$tema='';}


// 		if(strcmp($password,$passwordReal)==0){
// 			$passwd=UserEncriptarPasswd($password);
// 		}else{
//       $this->frmError["MensajeError"]="Escriba de Nuevo las contraseñas, Estas no coinciden";
//       $action=ModuloGetURL('system','Usuarios','user','InsertarUsuariosSistema');
// 			$this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,'','',$tema,$action,'',$descripcion);
// 			return true;
// 		}
//
// 		$login=$this->verificaExisteLogin($loginUsuario);
// 		if($login){
//         $this->frmError["MensajeError"]="Este login ya existe Debe Cambiarlo";
//         $action=ModuloGetURL('system','Usuarios','user','InsertarUsuariosSistema');
// 			  $this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion);
// 			  return true;
// 		}
		list($dbconn) = GetDBconn();
		$query = "select nextval('system_usuarios_usuario_id_seq');";
		$res=$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
		  $this->error = "Error al seleccionar el serial de system_usuarios";
		  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		  return false;
			}
    $serial=$res->fields[0];
		$dbconn->StartTrans();  //comienza transacion
		$nombreUsuario=strtoupper($nombreUsuario);
    $loginUsuario=strtoupper($loginUsuario);
		$query = "INSERT INTO system_usuarios(
                       					usuario_id,
																usuario,
																nombre,
																descripcion,
																activo,
																sw_admin)
																VALUES($serial,'$loginUsuario','$nombreUsuario','$descripcion','$activo','$administrador')";
		$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
		  $this->error = "Error al Guardar en system_usuarios";
		  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		  return false;
			}

     if(!empty($tema))
     	{
				UserSetVar($serial,'Tema',$tema);
			}

		UserResetPasswd($serial);
		$dbconn->CompleteTrans();   //termina la transaccion
		$this->ListadoUsuariosSistema();
	  return true;

  } //final funcion....


}//fin clase user

?>


