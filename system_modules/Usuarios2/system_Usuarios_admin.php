<?php

/**
*MODULO Administrativo para el Manejo de Usuarios del Sistema
*
* @author Lorena Aragon - Jairo Duvan Diaz Martinez
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

class system_Usuarios_admin extends classModulo
{
		var $limit;
		var $conteo;

	function system_Usuarios_admin()
	{
		$this->limit=GetLimitBrowser();
  	return true;
	}



/**
* Funcion donde se llama la funcion Menu
* @return boolean
*/

	function main(){
    unset($_SESSION['USER']['FECH']);
		unset($_SESSION['USER']['DIAS']);
    if(!$this->Menu()){
        return false;
   }

//$tabla='userpermisos_tipos_facturas'; //estos datos son de prueba
//$tabla='cajas_usuarios';
//$tabla='usuarios_maestro_inventarios';
//$tabla='userpermisos_mantenimiento_profesionales';
//$tabla='userpermisos_contratacion';
//$tabla='cuentas_filtros_usuarios';
//$this->InterfazAdmin($tabla);//esto es de prueba........
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

		$query = "SELECT count(*) from system_session where usuario_id=$uid;";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar en system_session";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
        $existencia=$res->fields[0];
		    return $existencia;
		}
	}


	/**
* Funcion donde sacamos el numero de dias de caducidad de contraseña de un usuario.
* @return boolean
*/

  function TraerUserDias($uid)
	{
			list($dbconn) = GetDBconn();

		$query = "SELECT 	fecha_caducidad_cuenta,caducidad_contrasena from system_usuarios where usuario_id=$uid;";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar en system_usuarios";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
        $existencia[0]=$res->fields[0];
				$existencia[1]=$res->fields[1];
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



function ComboEmpresa()
{
  	list($dbconn) = GetDBconn();
	 	$query="select a.empresa_id,a.razon_social from empresas a ,
						system_usuarios_administradores e
						where e.usuario_id='".UserGetUID()."'
						and e.empresa_id=a.empresa_id  order by a.razon_social asc";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al listar las empresas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;

		while (!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
	return $var;
}



/*funcion en la cual creamos un nuevo perfil*/
function InsertarPerfil()
{
 if(empty($_REQUEST['descrip']) || $_REQUEST['empresa']==-1)
 {
							if($_REQUEST['descrip']==''){ $this->frmError["des"]=1; }
							if($_REQUEST['empresa']==-1){ $this->frmError["emp"]=1; }
							$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
							$this->FormaInsertNewPerfil($_REQUEST['empresa'],$_REQUEST['descrip']);
							return true;
 }

 				list($dbconn) = GetDBconn();
				$query="SELECT COUNT(*) FROM system_perfiles
								WHERE		descripcion='".strtoupper($_REQUEST['descrip'])."'
								AND empresa_id='".$_REQUEST['empresa']."'";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al buscar en la tabla en system_perfiles";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
					}
			if($resulta->fields[0]>=1)
			{
							$this->frmError["MensajeError"]="EL NOMBRE DEL PERFIL YA EXISTE CAMBIELO POR FAVOR.";
							$this->FormaInsertNewPerfil($_REQUEST['empresa'],$_REQUEST['descrip']);
							return true;
			}
			else
			{
					$query="INSERT INTO system_perfiles
															( descripcion,empresa_id)
															VALUES
															('".strtoupper($_REQUEST['descrip'])."','".$_REQUEST['empresa']."')";
											$resulta=$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0){
												$this->error = "Error al insertar en system_perfiles";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												return false;
												}
					$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
					$this->FormaInsertNewPerfil(-1,'');
					return true;
			}
}


	/*esta funcion fue creada para eliminar los perfiles*/
	function BorrarPerfil()
	{
			list($dbconn) = GetDBconn();
			$query="DELETE FROM system_perfiles
							WHERE		descripcion='".strtoupper($_REQUEST['desc'])."'
							AND empresa_id='".$_REQUEST['id']."'";
				$resulta=$dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0){
					$this->frmError["MensajeError"]="NO SE PUEDE BORRAR YA QUE TIENE REGISTROS CARGADOS.";
					$this->ListadoPerfiles();
					return false;
					}

			$this->ListadoPerfiles();
			return true;
	 }




/*Esta funcion lo que hace es insertar ó adicionar menus a perfiles,por ejemplo
 * el perfil Economico contiene los menus Caja,Facturacion,CxC
*/
function InsertarAPerfiMenu()
{


	list($dbconn) = GetDBconn();
	if(empty($_REQUEST['op']))
	{
		$this->frmError["MensajeError"]="Debe escoger Alguna opcion.";
		$this->ListadoMenu($_REQUEST['per'],$_REQUEST['razon'],$_REQUEST['desc']);
	}
	$query="delete  from system_perfiles_menus
				where perfil_id=".$_REQUEST['per']."";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al borrar en system_perfiles_menus";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
		foreach($_REQUEST['op'] as $index=>$codigo)
		{
						$query="INSERT INTO system_perfiles_menus
										( perfil_id,menu_id)
										VALUES
										(".$_REQUEST['per'].",'".$codigo."')";
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en system_perfiles_menus";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
							}

		}
		$this->ListadoMenu($_REQUEST['per'],$_REQUEST['razon'],$_REQUEST['nom']);
return true;


}


function InsertarAPerfilUsuario()
{

	list($dbconn) = GetDBconn();
	if(empty($_REQUEST['op']))
	{
		$this->frmError["MensajeError"]="Debe escoger Alguna opcion.";
		$this->ListadoPerfilUsuario($_REQUEST['uid'],$_REQUEST['user'],$_REQUEST['nom'],$_REQUEST['empresa'],$_REQUEST['NoEmp']);
	}
	$query="delete  from system_usuarios_perfiles
				where empresa_id=".$_REQUEST['empresa']."
				AND usuario_id=".$_REQUEST['uid']."";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al borrar en system_perfiles_usuarios";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
		foreach($_REQUEST['op'] as $index=>$codigo)
		{
						$query="INSERT INTO system_usuarios_perfiles
										( usuario_id,empresa_id,perfil_id)
										VALUES
										(".$_REQUEST['uid'].",'".$_REQUEST['empresa']."',".$codigo.")";
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en system_perfiles_menus";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
							}

		}
		$this->ListadoPerfilUsuario($_REQUEST['uid'],$_REQUEST['user'],$_REQUEST['nom'],$_REQUEST['empresa'],$_REQUEST['NoEmp']);
return true;


}


function InsertarPermisosU()
{
		list($dbconn) = GetDBconn();
  	$query="select count(*) from system_usuarios_empresas
					where usuario_id=".$_REQUEST['uid']."";
    $resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al buscar en system_usuarios_empresas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
			if($resulta->fields[0]<1)
			{
			 $query="INSERT INTO system_usuarios_empresas
								( usuario_id,empresa_id)
								VALUES
								(".$_REQUEST['uid'].",'".$_REQUEST['emp']."')";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al buscar en system_usuarios_empresas";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
 				$query="delete  from system_usuarios_departamentos
				where usuario_id=".$_REQUEST['uid']."";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al borrar en system_usuarios_departamentos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
		foreach($_REQUEST['op'] as $index=>$codigo)
		{
						$query="INSERT INTO system_usuarios_departamentos
										( usuario_id,departamento)
										VALUES
										(".$_REQUEST['uid'].",'".$codigo."')";
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en system_usuarios_departamentos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
							}

		}
		$this->AsignarPermisosUserModulo($_REQUEST['uid'],urldecode($_REQUEST['NombreUsuario']),$_REQUEST['usuario'],'1',$_REQUEST['empID']);
return true;
}



function InsertarPermisosModulo()
{
				list($dbconn) = GetDBconn();

 				$query="delete  from system_user_admin_modulos
				where usuario_id=".$_REQUEST['uid']."";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al borrar en system_user_admin_modulos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
		foreach($_REQUEST['op'] as $index=>$codigo)
		{
						$query="INSERT INTO system_user_admin_modulos
										(  usuario_id,modulo,modulo_tipo)
										VALUES
										(".$_REQUEST['uid'].",'".$codigo."','app')";
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en system_user_admin_modulos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
							}

		}
		$this->AsignarPermisosUserModulo($_REQUEST['uid'],urldecode($_REQUEST['NombreUsuario']),$_REQUEST['usuario'],'2',$_REQUEST['empID']);
return true;
}









function ComboDpto($empresa_id,$uid)
{
  	list($dbconn) = GetDBconn();
		$query="select a.departamento,a.descripcion,
						e.usuario_id from departamentos a
						left join system_usuarios_departamentos
						as e on(e.departamento=a.departamento and usuario_id='$uid')
						where empresa_id='".$empresa_id."' order by  e.usuario_id asc ;";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al listar las empresas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;

		while (!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
	return $var;
}




/*Esta funcion saca los modulos del sistema que sean solo 'app'(aplicación)
 * y paque tengan switche administrativo ='1'
 */
function TraerModulo($uid)
{
  	list($dbconn) = GetDBconn();
		$query="select a.modulo,a.modulo_tipo,a.descripcion,e.usuario_id from system_modulos a
						left join system_user_admin_modulos as e on(e.modulo=a.modulo and
						e.modulo_tipo=e.modulo_tipo and usuario_id='".$uid."')
						where
						a.sw_admin='1' and a.modulo_tipo = 'app'
						and a.modulo <> '' order by a.modulo";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al listar modulos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;

		while (!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
	return $var;
}



/*funcion que saca los menus para adherir al perfil */
function TraerMenus($perfil)
{
	list($dbconn) = GetDBconn();
	$query="select b.perfil_id,a.menu_id,
							a.menu_nombre,a.descripcion from system_menus	a
							left join system_perfiles_menus b
							on (a.menu_id=b.menu_id and b.perfil_id=$perfil)
							where a.menu_id <> 20
							order by a.menu_nombre";
					$resulta=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0){
								$this->error = "Error al listar los Menus";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
							$i=0;

							while (!$resulta->EOF)
							{
								$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
								$i++;
							}
		return $var;
}



/*funcion que saca perfiles */
function TraerPerfilesUser($uid,$emp)
{
	list($dbconn) = GetDBconn();
	$query="
					select a.perfil_id,b.usuario_id,a.descripcion  from system_perfiles a
					left join system_usuarios_perfiles as b
					on(a.empresa_id=b.empresa_id and b.usuario_id=".$uid.")
					WHERE a.empresa_id='".$emp."'";
					$resulta=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0){
								$this->error = "Error al listar en la tabla system_perfiles";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
							$i=0;

							while (!$resulta->EOF)
							{
								$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
								$i++;
							}
		return $var;
}





/*funcion trae los perfiles según la empresa */
function TraerPerfil($perfil)
{
	list($dbconn) = GetDBconn();
	$query="select b.perfil_id,a.menu_id,
							a.menu_nombre,a.descripcion from system_menus	a
							left join system_perfiles_menus b
							on (a.menu_id=b.menu_id and b.perfil_id=$perfil)
							where a.menu_id <> 20
							order by a.menu_nombre";
					$resulta=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0){
								$this->error = "Error al listar los Menus";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
							$i=0;

							while (!$resulta->EOF)
							{
								$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
								$i++;
							}
		return $var;
}


/*funcion trae los perfiles según la empresa */
function GetCaducidadContrasena()
{
	list($dbconn) = GetDBconn();
	$query="SELECT caducidad_id,descripcion
					FROM system_caducidad_passwd ORDER BY indice_orden";
					$resulta=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0){
								$this->error = "Error al listar los formatos de caducidad";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
							$i=0;

							while (!$resulta->EOF)
							{
								$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
								$i++;
							}
		return $var;
}



 function ListarMenus($var,$uid,$nombre,$usuario,$descripcion)
	{
         if(!empty($_REQUEST['uid']))
				  {
							$uid=$_REQUEST['uid'];
							$nombre=$_REQUEST['nombre'];
							$usuario=$_REQUEST['usuario'];
							$descripcion=$_REQUEST['descripcion'];
							list($dbconn) = GetDBconn();
							$query="
							select a.menu_id, a.menu_nombre,a.descripcion,
							b.usuario_id from system_menus a left join system_usuarios_menus b
							on (a.menu_id=b.menu_id AND b.usuario_id=".$uid.")
							WHERE sw_system=0
							order by a.menu_nombre";
							$resulta=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0){
								$this->error = "Error al listar los Menus";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
							$i=0;

							while (!$resulta->EOF)
							{
								$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
								$i++;
							}
							$this->PermisosMenuUsuario($var,$uid,$nombre,$usuario,$descripcion);
					}
					else
					{
							$this->PermisosMenuUsuario($var,$uid,$nombre,$usuario,$descripcion);
					}
					return true;
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



	function InsertarPermisoMenu()
	{
			$uid=$_REQUEST['uid'];
			$menu=$_REQUEST['menu'];
			list($dbconn) = GetDBconn();
			$query = "SELECT count(*) FROM system_usuarios_menus WHERE usuario_id='$uid'
			and menu_id='$menu'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
					if($result->fields[0]>0){
					$query = "DELETE  from  system_usuarios_menus
										WHERE usuario_id='$uid' and menu_id='$menu'";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Borrar en system_usuarios_menus";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

				}else{
					$query = "INSERT INTO system_usuarios_menus
										(usuario_id,menu_id)
										VALUES
										($uid,$menu)";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Insertar en system_usuarios_menus";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
				}
			}
				$this->ListarMenus($var,$uid,$nombre,$usuario,$descripcion);
				return true;
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




/**
* Funcion que busca los datos principales de los usuarios del sistema
* @return array
*/

	function BuscarUsuariosSistema($filtro){

		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo'])){
		$query = "select a.usuario_id,d.usuario,d.nombre,d.descripcion,
								 d.passwd,d.activo,d.sw_admin,a.empresa_id,c.razon_social
								 from system_usuarios_empresas a, empresas as c
								,system_usuarios_administradores b,system_usuarios as d
								where a.empresa_id=b.empresa_id
								and b.usuario_id='".UserGetUID()."' and a.empresa_id=c.empresa_id
      					 and a.usuario_id=d.usuario_id
								 --and a.usuario_id <> '".UserGetUID()."'
								  $filtro order by empresa_id";

		$result = $dbconn->Execute($query);
		list($this->conteo)=$result->RecordCount();
		if ($dbconn->ErrorNo() != 0) {
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
    /*$query = "SELECT usuario_id,
		                 usuario,
										 nombre,
										 descripcion,
										 passwd,
										 activo,
										 sw_admin
			          FROM system_usuarios WHERE usuario_id > 0 ORDER BY usuario LIMIT " . $this->limit . " OFFSET $Of";*/
				if(!empty($_SESSION['USUARIOS']['ORDENAMIENTO']))
				{$ordenamiento=$_SESSION['USUARIOS']['ORDENAMIENTO'];}else{$ordenamiento='order by empresa_id,usuario';}
			  $query = "select a.usuario_id,d.usuario,d.nombre,d.descripcion,
								 d.passwd,d.activo,d.sw_admin,a.empresa_id,c.razon_social
								 from system_usuarios_empresas a, empresas as c
								,system_usuarios_administradores b,system_usuarios as d
								where a.empresa_id=b.empresa_id
								and b.usuario_id='".UserGetUID()."' and a.empresa_id=c.empresa_id
								and a.usuario_id=d.usuario_id
								--and a.usuario_id <> '".UserGetUID()."'
								$filtro $ordenamiento
								LIMIT " . $this->limit . " OFFSET $Of";

		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    $i=0;
		while(!$result->EOF){
		$datos[$i]=$result->fields[0].'/'.$result->fields[1].'/'.$result->fields[2].'/'.$result->fields[3].'/'.$result->fields[4].'/'.$result->fields[5].'/'.
		$result->fields[6].'/'.$result->fields[7].'/'.$result->fields[8];
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
* Funcion que busca los perfiles
* @return array
*/

	function BuscarPerfil(){
    list($dbconn) = GetDBconn();
	  $query = "select  a.perfil_id,a.descripcion,a.empresa_id,b.razon_social
							from system_perfiles a,empresas b,system_usuarios_empresas c
							where  a.empresa_id=b.empresa_id
							and a.empresa_id=c.empresa_id and c.usuario_id='".UserGetUID()."'";
	  $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al buscar los perfiles";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
		}else{

							$i=0;
							while(!$result->EOF)
									{
											$var[$i]=$result->GetRowAssoc($ToUpper = false);
											$result->MoveNext();
											$i++;
									}
					}
					return $var;
	}



/**
* Funcion que busca un usuario en particular
* @return array
*/

	function TraerUsuario(){
    list($dbconn) = GetDBconn();
	  $query = "SELECT  usuario_id,usuario,nombre from system_usuarios WHERE
							usuario_id='".UserGetUID()."'";
	  $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al buscar 	el usuario";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
		}else{

							$i=0;
							while(!$result->EOF)
									{
											$var[$i]=$result->GetRowAssoc($ToUpper = false);
											$result->MoveNext();
											$i++;
									}
					}
					return $var;
	}



function BuscaEstadoUserEmpresa($uid,$empresa){
    list($dbconn) = GetDBconn();
	  $query = "SELECT sw_activo FROM system_usuarios_empresas WHERE usuario_id='$uid'
		and empresa_id='".$empresa."'";
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
		$NombreUsuario=urldecode($_REQUEST['nombre']);
    $Usuario=$_REQUEST['usuario'];
    $empresa=$_REQUEST['empID'];
    $this->FormaAsignarPermisosUsuarios($uid,$NombreUsuario,$Usuario,$empresa);
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
		$empresa=$_REQUEST['empID'];
    $consulta='1';
		$action=ModuloGetURL('system','Usuarios','admin','ModificarUsuariosSistema',array("uid"=>$uid,"emp"=>$_REQUEST['empID']));
		if(!$this->FormaInsertarUsuarioSistema($nombre,$usuario,'','',$tema,$action,$consulta,$descripcion,true,$uid,$empresa)){
        return false;
    }
		return true;
  }


	/**
* Funcion donde se Modifican en la base de datos los datos principales de un usuario que ya exite en el sistema
* @return boolean
*/

	function ModificarUsuariosSistema(){

    $fechacaduca=$_REQUEST['caducidad'];
		$_SESSION['USER']['FECH']=$fechacaduca;
		$_SESSION['USER']['DIAS']=$_REQUEST['dias'];
		$uid=$_REQUEST['uid'];
		$nombreUsuario=urldecode($_REQUEST['nombreUsuario']);
    $tema=$_REQUEST['tema'];
		$descripcion=$_REQUEST['descripcion'];
		$activo=$_REQUEST['activo'];
		$sw_empresa=$_REQUEST['administrador']; //esta variable es el switche de empresa.
    $loginUsuario=$_REQUEST['loginUsuario'];
		$empresa=$_REQUEST['empresa'];

		if($nombreUsuario=='' || $loginUsuario=='' || $empresa==-1){
			if($nombreUsuario==''){ $this->frmError["nombreUsuario"]=1; }
			if($loginUsuario==''){ $this->frmError["loginUsuario"]=1; }
			$this->frmError["MensajeError"]="Faltan datos obligatorios.";
			$consulta='1';
      $action=ModuloGetURL('system','Usuarios','admin','ModificarUsuariosSistema',array("uid"=>$uid));
			if(!$this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,'','',$tema,$action,$consulta,$descripcion,$empresa)){
				return false;
			}
			return true;
		}

		if($activo){$activo='1';
		}else{$activo='0';}
    if($sw_empresa){$sw_empresa='1';
		}else{$sw_empresa='0';}
		if($tema==-1){$tema='';}


    if(empty($fechacaduca))
		{
				$fech=",caducidad_contrasena=".$_SESSION['USER']['DIAS'].",fecha_caducidad_cuenta=NULL";

				if($_SESSION['USER']['DIAS']!=0)
				{
					$fech.=",fecha_caducidad_contrasena='". date("Y-m-d",strtotime("+".$_SESSION['USER']['DIAS']." days",strtotime(date("Y-m-d"))))."'";
				}
				else
				{
					$fech.=",fecha_caducidad_contrasena=NULL";
				}
		}
		else
		{
											// 		  if(!checkdate(date($fechacaduca)))
								// 			{
								// 				$this->frmError["MensajeError"]="Escoga una fecha en formato d-m-a";
								// 				$action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
								// 				$this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion,$fechacaduca);
								// 				return true;
								// 			}
					if(strtotime($fechacaduca) >= strtotime(date("d-m-Y")))
					{

							$fech=",fecha_caducidad_cuenta='$fechacaduca',
											fecha_caducidad_contrasena='". date("Y-m-d",strtotime("+".$_SESSION['USER']['DIAS']." days",strtotime(date("Y-m-d"))))."'
											,caducidad_contrasena=".$_SESSION['USER']['DIAS']."";
					}
					else
					{
						$this->frmError["MensajeError"]="La fecha debe ser de hoy o posterior.";
						$consulta='1';
						$action=ModuloGetURL('system','Usuarios','admin','ModificarUsuariosSistema',array("uid"=>$uid));
					  $this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,'','',$tema,$action,$consulta,$descripcion,$empresa);
			 			return true;
					}
		}




		$login=$this->verificaExisteLoginInsertado($loginUsuario,$uid);
		if($login){
				$this->frmError["MensajeError"]="Este login ya existe Debe Cambiarlo";
        $consulta='1';
				$action=ModuloGetURL('system','Usuarios','admin','ModificarUsuariosSistema',array("uid"=>$uid));
			  $this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,'','',$tema,$action,$consulta,$descripcion,$empresa);
			  return true;
		}

		$nombreUsuario=strtoupper($nombreUsuario);
		//$loginUsuario=strtoupper($loginUsuario);
		list($dbconn) = GetDBconn();
		$dbconn->StartTrans();  //comienza transacion
		$query = "UPDATE system_usuarios SET usuario='$loginUsuario',
		                                      nombre='$nombreUsuario',
																					descripcion='$descripcion',
																					activo='$activo',
                     											sw_admin='0'
																					$fech
																					WHERE usuario_id='$uid'";
		$dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al actualizar en system_usuarios";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}


		$query="SELECT count(*) FROM system_usuarios_empresas WHERE
						 usuario_id='$uid' AND empresa_id='$empresa'";

		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
		  $this->error = "Error al consultar en system_usuarios_empresas";
		  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		  return false;
			}

		 //para que al actualizar no nos saque un error por eso lo comparamos
    if($res->fields[0]<1)
		{
				 $query = "UPDATE  system_usuarios_empresas SET
																		empresa_id='$empresa',
																		sw_activo='$sw_empresa'
																		WHERE usuario_id='$uid' AND empresa_id='".$_REQUEST['emp']."'";
				$dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al actualizar en system_usuarios_empresas";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
					}
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
		$dbconn->CompleteTrans();   //termina la transaccion
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
						$this->frmError["MensajeError"]="EL USUARIO NO SE BORRO YA QUE TIENE REGISTROS CARGADOS.";
            if(!$this->ListadoUsuariosSistema()){
						return false;
						}
						return true;
					}

				UserDelVar($uid,'Tema');
				$this->ListadoUsuariosSistema();
				return true;
}



function ModificarEstadoEmpresa()
{
		list($dbconn) = GetDBconn();
		$uid=$_REQUEST['uid'];
    $TipoForma=$_REQUEST['TipoForma'];
    $NombreUsuario=urldecode($_REQUEST['NombreUsuario']);
    $Usuario=$_REQUEST['usuario'];

		$query = "SELECT activo FROM system_usuarios WHERE usuario_id='$uid'";
	  $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
		}

     $result->fields[0];
     if($result->fields[0]=='1')
		 {
					$query = "SELECT sw_activo FROM system_usuarios_empresas WHERE usuario_id='$uid'
					and empresa_id='".$_REQUEST['empresa']."';";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al buscar en system_usuarios_empresas";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}else{

						$result->fields[0];
						if($result->fields[0]=='1'){
							$query = "UPDATE system_usuarios_empresas SET sw_activo='0' WHERE usuario_id='$uid'
							and empresa_id='".$_REQUEST['empresa']."';";
							$result = $dbconn->Execute($query);
						}else{
							$query = "UPDATE system_usuarios_empresas SET sw_activo='1' WHERE usuario_id='$uid'
							and empresa_id='".$_REQUEST['empresa']."';";
							$result = $dbconn->Execute($query);
						}
					}
					if(!$TipoForma){
						$this->ListadoUsuariosSistema();
						return true;
					}elseif($TipoForma==1){
						$this->FormaAsignarPermisosUsuarios($uid,$NombreUsuario,$Usuario,$_REQUEST['empresa']);
						return true;
					}
		}
		else
		{
			if(!$TipoForma){
						$this->ListadoUsuariosSistema();
						return true;
					}elseif($TipoForma==1){
						$this->FormaAsignarPermisosUsuarios($uid,$NombreUsuario,$Usuario,$_REQUEST['empresa']);
						return true;
					}
		}
}


function ModificarEstadoUsuario(){

		$uid=$_REQUEST['uid'];
    $TipoForma=$_REQUEST['TipoForma'];
    $NombreUsuario=urldecode($_REQUEST['NombreUsuario']);
    $Usuario=$_REQUEST['usuario'];

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
	 		  $query = "UPDATE system_usuarios SET activo='0' WHERE usuario_id='$uid';";
	      $result = $dbconn->Execute($query);
				$query = "UPDATE system_usuarios_empresas SET sw_activo='0' WHERE usuario_id='$uid'
				and empresa_id='".$_REQUEST['empresa']."';";
	      $result = $dbconn->Execute($query);
		  }else{
	  		$query = "UPDATE system_usuarios SET activo='1' WHERE usuario_id='$uid';";
	      $result = $dbconn->Execute($query);
				$query = "UPDATE system_usuarios_empresas SET sw_activo='1' WHERE usuario_id='$uid'
				and empresa_id='".$_REQUEST['empresa']."';";
				$result = $dbconn->Execute($query);
		  }
		}
    if(!$TipoForma){
		  $this->ListadoUsuariosSistema();
		  return true;
		}elseif($TipoForma==1){
      $this->FormaAsignarPermisosUsuarios($uid,$NombreUsuario,$Usuario,$_REQUEST['empresa']);
			return true;
		}

// 		else{
//       $this->AsignarDepartamentosUsuario($uid,$NombreUsuario,$Usuario);
// 			return true;
// 		}
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
		$nombreUsuario=urldecode($_REQUEST['nombreUsuario']);
    $fechacaduca=$_REQUEST['caducidad'];
//echo "-->".$fechacaduca;
//exit;
		$_SESSION['USER']['FECH']=$fechacaduca;
    $_SESSION['USER']['DIAS']=$_REQUEST['dias'];
		$tema=$_REQUEST['tema'];
	 	$activo=$_REQUEST['activo'];
		$descripcion=$_REQUEST['descripcion'];
    $loginUsuario=$_REQUEST['loginUsuario'];
    $password=$_REQUEST['password'];
    $passwordReal=$_REQUEST['passwordReal'];
		$empresa=$_REQUEST['empresa'];
		$sw_empresa=$_REQUEST['administrador']; //ya no es administrador sino que este dato va
		//para el switche de system_usuarios_empresas.
		$action=$_REQUEST['action'];//este si es el switche de system_usuarios

		if($nombreUsuario=='' || $loginUsuario==''){
			if($nombreUsuario==''){ $this->frmError["nombreUsuario"]=1; }
			if($loginUsuario==''){ $this->frmError["loginUsuario"]=1; }
			if($empresa==-1){ $this->frmError["emp"]=1; }
			//if($password==''){ $this->frmError["password"]=1; }
      //if($passwordReal==''){ $this->frmError["passwordReal"]=1; }
			$this->frmError["MensajeError"]="Faltan datos obligatorios.";
      $action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
			$this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion);
			return true;
		}

//     if(empty($_REQUEST['nocad']) and empty($fechacaduca))
// 		{
// 			$this->frmError["MensajeError"]="Debe escoger una fecha de caducidad o escoger la opcion de no caducidad.";
//       $action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
// 			$this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion,$fechacaduca);
// 			return true;
// 		}

    if(empty($fechacaduca))
		{
		  	$comparador_de_insercion=1; //esta variable cambia el query a que inserte NULL
		}
		else
		{
					$feca=explode("-",$fechacaduca);
					$fechacaduca=$feca[2]."-".$feca[1]."-".$feca[0];

// 		  if(!checkdate(date($fechacaduca)))
// 			{
// 				$this->frmError["MensajeError"]="Escoga una fecha en formato d-m-a";
// 				$action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
// 				$this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion,$fechacaduca);
// 				return true;
// 			}

			if(strtotime(date($fechacaduca)) >= strtotime(date("Y-m-d")))
			{
        $comparador_de_insercion=0; //esta variable cambia el query a q inserte fecha.
			}
			else
			{
				$this->frmError["MensajeError"]="La fecha debe ser de hoy o posterior.";
				$action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
				$this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion);
				return true;
			}

		}



    if($activo){$activo='1';
		}else{$activo='0';}
    if($sw_empresa){$sw_empresa='1';
		}else{$sw_empresa='0';}
    if($tema==-1){$tema='';}

		list($dbconn) = GetDBconn();
		//revisamos q no exista un login igual ó parecido en la base de datos..
		$query = "SELECT COUNT(*) FROM system_usuarios WHERE UPPER(usuario)='".rtrim(ltrim(strtoupper($loginUsuario)))."'";
		$res=$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
		  $this->error = "Error al buscar login en system_usuarios";
		  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		  return false;
			}

			if($res->fields[0]>0)
			{
				$this->frmError["MensajeError"]="EXISTE UN LOGIN IGUAL EN LA BASE DE DATOS,POR FAVOR CAMBIE SU LOGIN!";
				$action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
				$this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion);
				return true;
			}



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

    	if($empresa==-1){
			$this->frmError["emp"]=1;
			$this->frmError["MensajeError"]="Faltan datos obligatorios.";
      $action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
			$this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion);
			return true;
		}


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
    //$loginUsuario=strtoupper($loginUsuario);
     $fecha_contraseña=date('Y-m-d',strtotime('+'.$_SESSION['USER']['DIAS'].'days',strtotime(date('Y-m-d'))));

// $fecha_contraseña;
//exit;
		if($comparador_de_insercion==1)
		{

				if($_SESSION['USER']['DIAS']==0)
				{
					//quiere decir que no caduca la contrasena
					//generamos el passwd

					 $query = "INSERT INTO system_usuarios(
																		usuario_id,
																		usuario,
																		nombre,
																		descripcion,
																		activo,
																		sw_admin,
																		caducidad_contrasena,
																		passwd
																		)
																		VALUES($serial,'$loginUsuario','$nombreUsuario','$descripcion','$activo','0',".$_SESSION['USER']['DIAS'].",
																		'".UserEncriptarPasswd('siis')."')";
					//esta variable no me manda a resetear el passwd
					//siempre y cuando  inserte el usuario con la opcion que no caduque su contrasena
					$SW_NO_CADUCA=1;

				}else{

									$query = "INSERT INTO system_usuarios(
																		usuario_id,
																		usuario,
																		nombre,
																		descripcion,
																		activo,
																		sw_admin,
																		caducidad_contrasena,
																		fecha_caducidad_contrasena
																		)
																		VALUES($serial,'$loginUsuario','$nombreUsuario','$descripcion','$activo','0',".$_SESSION['USER']['DIAS'].",
																		'$fecha_contraseña')";
						}
		}
		else
		{

				if($_SESSION['USER']['DIAS']==0)
				{
					 $query = "INSERT INTO system_usuarios(
                       					usuario_id,
																usuario,
																nombre,
																descripcion,
																activo,
																sw_admin,
																fecha_caducidad_cuenta,
																caducidad_contrasena
																)
																VALUES($serial,'$loginUsuario','$nombreUsuario','$descripcion','$activo','0','$fechacaduca', ".$_SESSION['USER']['DIAS'].")";
				}else{

									$query = "INSERT INTO system_usuarios(
																					usuario_id,
																					usuario,
																					nombre,
																					descripcion,
																					activo,
																					sw_admin,
																					fecha_caducidad_cuenta,
																					caducidad_contrasena,
																					fecha_caducidad_contrasena
																					)
																					VALUES($serial,'$loginUsuario','$nombreUsuario','$descripcion','$activo','0','$fechacaduca', ".$_SESSION['USER']['DIAS'].",
																					'$fecha_contraseña')";
							}
		}
		//echo $query;exit;
		$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
		  $this->error = "Error al Guardar en system_usuarios";
		  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		  return false;
			}
			 $query = "INSERT INTO system_usuarios_empresas(
                       					usuario_id,
																empresa_id,
																sw_activo
																)
																VALUES($serial,'$empresa','$sw_empresa')";
		$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
		  $this->error = "Error al Guardar en system_usuarios_empresas";
		  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		  return false;
			}

			//insertamos el link de configuracion del usuario cada vez que se guarde
			//por primera vez..
			//45 es CONFIGURACION DEL USUARIO.
			$query = "INSERT INTO system_usuarios_menus(
                       					usuario_id,
																menu_id
																)
																VALUES($serial,45)";
		$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
		  $this->error = "Error al Guardar en system_usuarios_menus";
		  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		  return false;
			}

     if(!empty($tema))
     	{
				UserSetVar($serial,'Tema',$tema);
			}

		if($SW_NO_CADUCA!=1)
		{
			UserResetPasswd($serial);
		}
		$dbconn->CompleteTrans();   //termina la transaccion
		$this->ListadoUsuariosSistema();
	  return true;

  } //final funcion....


}//fin clase user

?>


