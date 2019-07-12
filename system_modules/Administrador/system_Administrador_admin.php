<?php

/**
*MODULO Administrativo para el Manejo de Usuarios del Sistema
*
* @Jairo Duvan Diaz Martinez
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

class system_Administrador_admin extends classModulo
{
		var $limit;
		var $conteo;

	function system_Administrador_admin()
	{
		$this->limit=GetLimitBrowser();
  	return true;
	}




/**
* Funcion donde se llama la funcion MenudeEmpresas
* @return boolean
*/

	function main()
	{

// 			$Fecha='2004-06-03';
// 			$infoCadena = explode ('-', $Fecha);
// 			$diaIni=$infoCadena[0];
// 			$mesIni=$infoCadena[1];
// 			$anoIni=$infoCadena[2];
// 			$HoraDef='08:00:00:00';
// 			$infoCadena = explode (':',$HoraDef);
// 			$HoraIni=$infoCadena[0];
// 			$MinutosIni=$infoCadena[1];
// 			$Fecha='2004-06-03';
// 			$infoCadena = explode ('-', $Fecha);
// 			$diaFin=$infoCadena[0];
// 			$mesFin=$infoCadena[1];
// 			$anoFin=$infoCadena[2];
// 			$HoraDef='11:00:00:00';
// 			$infoCadena = explode (':',$HoraDef);
// 			$HoraFin=$infoCadena[0];
// 			$MinutosFin=$infoCadena[1];
//  $d=date("h:i",date(strtotime(mktime($HoraFin,$MinutosFin,0,$mesFin,$diaFin,$anoFin))-strtotime(mktime($HoraIni,$MinutosIni,0,$mesIni,$diaIni,$anoIni))));
// //$d=date("H:i",strtotime($d));
// 
// echo $d;
// exit;
	    //borrado de var empresa_id
			unset($_SESSION['ADMIN']['EMPRESAID']);
			//borrado de var de seccion multiempresa o no multiempresa
			unset($_SESSION['ADMIN']['SWM']);
			if(UserGetVar(UserGetUID(),'sw_admin'))
			{
				//este metodo es para la administracion del  menu general........
				list($dbconn) = GetDBconn();
				$query = "SELECT empresa_id,razon_social,website,
									sw_activa,sw_usuarios_multiempresa
									from empresas order by razon_social";
				$result = $dbconn->Execute($query);
				$i=0;
				while(!$result->EOF)
							{
									$var[$i]=$result->GetRowAssoc($ToUpper = false);
									$i++;
									$result->MoveNext();
							}
				$this->MenuEmpresas($var);
				return true;
			}
			else
			{
// 	list($dbconn) = GetDBconn();
// 	$query = "SELECT a.empresa_id,b.razon_social,b.website
// 					from system_usuarios_administradores a,empresas b
//  					where a.empresa_id=b.empresa_id  and a.usuario_id=".UserGetUID()."";
// 	$result = $dbconn->Execute($query);
// 	$i=0;
// 	while(!$result->EOF)
// 				{
// 						$var[$i]=$result->GetRowAssoc($ToUpper = false);
// 						$i++;
// 						$result->MoveNext();
// 				}
// if($result->RecordCount()==1)
				$this->error = "ACCESO DENEGADO";
				$this->mensajeDeError = "Usuario sin permisos";
				return False;
			}


if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error en la consulta de la tabla system_usuarios_administradores  ";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
		}
		if(!$result->EOF)
		{
		   //este es el metodo de administracion de empresas...
       $this->MenuE();
			 return true;
		}
    else
		{
			$this->error = "PERMISO DENEGADO";
			$this->mensajeDeError = "El usuario no tiene permisos de administrador ";
			return false;
		}
//}


		return true;
  }



/*funcion para sacar el nombre de pais,dpto mpio
 *segun el filtro mandado
 */
function TraerNombreUbicacion($pais,$dpto,$mpio)
{
 list($dbconn) = GetDBconn();
 $query="	select a.pais,b.departamento,municipio
					from tipo_pais a,tipo_dptos b,tipo_mpios c
					where a.tipo_pais_id='".$pais."' and b.tipo_dpto_id='".$dpto."'
					and a.tipo_pais_id=c.tipo_pais_id and b.tipo_dpto_id=c.tipo_dpto_id
					and c.tipo_mpio_id='".$mpio."'";
					$resulta=$dbconn->execute($query);

				if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el tipo de tercero 114";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
				$i=0;
				while(!$resulta->EOF)
						{
								$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
								$i++;
						}
				return $var;

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



function InsertarUsuarioSistema()
{
		if(empty($_REQUEST['op']))
		{
			$this->ListadoUsuarioSistema();
			return true;
		}

		list($dbconn) = GetDBconn();
 		foreach($_REQUEST['op'] as $index=>$codigo)
		{
						$query="INSERT INTO system_usuarios_administradores
										( usuario_id,empresa_id)
										VALUES
										('".$codigo."','".$_SESSION['ADMIN']['EMPRESAID']."')";
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en system_usuarios_administradores";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
							}

						//insertamos el link de administracion de usuario
						//15 es ADMINISTRACION DEL USUARIO DE  menu_id.
						$query = "INSERT INTO system_usuarios_menus(
																			usuario_id,
																			menu_id
																			)
																			VALUES($codigo,15)";
												$dbconn->Execute($query);


		}
		$this->ListadoUsuarioEmpresa();
    return true;
}


/**
* Funcion donde se borra la empresa segun empresa_id y despues q no tenga relaciones
* sera borrada.
* @return boolean
*/

function BorrarEmp()
{
						list($dbconn) = GetDBconn();
						$empid=$_REQUEST['empid'];
						$query = "DELETE FROM empresas WHERE empresa_id='".$empid."'";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
						$this->frmError["MensajeError"]="LA EMPRESA NO SE BORRO YA QUE TIENE REGISTROS CARGADOS.";
            if(!$this->main()){
						return false;
						}
						return true;
					}
				$this->main();
				return true;
}


/*funcion que trae el tipo de tercero
* los parametros $decision tiene por defecto espacio,en el caso de insercion,
* pero si el caso es modificación viene con un valor booleano(true) y con el tipoid
* para filtrar por el tipo_id_tercero
*/
function TraerBusqTercero($decision='',$tipoid)
{
				list($dbconn) = GetDBconn();

				if($decision==true)
				{
					$sql="select tipo_id_tercero,descripcion
							from tipo_id_terceros where tipo_id_tercero='".$tipoid."'";
	  		}
				else
				{
			 		$sql="select tipo_id_tercero,descripcion
							from tipo_id_terceros";
				}
				$resulta=$dbconn->execute($sql);

				if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el tipo de tercero 114";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
				$i=0;
				while(!$resulta->EOF)
						{
								$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
								$i++;
						}
				return $var;
}


/*	funcion que trae los datos de la empresa
 *  datos como razon social,identificación,representante legal etc..
 *  retorna arreglo.
*/
function TraerDatosEmpresa($id)
{
  	list($dbconn) = GetDBconn();
	 	$query="select empresa_id,razon_social,tipo_id_tercero,id,codigo_sgsss,
						tipo_pais_id,tipo_dpto_id,tipo_mpio_id,direccion,telefonos,codigo_postal,
						sw_activa,sw_usuarios_multiempresa,id,representante_legal,
						website,email,fax from empresas	where empresa_id='".$id."';";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al listar la tabla de empresa";
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


/*funcion que modifica el estado de la empresa si esta activa o inactiva
 * retorna booleano
*/
function ModificarEstadoEmpresa(){

		$empid=$_REQUEST['empid'];
    list($dbconn) = GetDBconn();
	  $query = "SELECT sw_activa FROM empresas WHERE empresa_id='$empid'";
	  $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al bsucar switche activo en la tabla empresa";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
		}else{
      $result->fields[0];
      if($result->fields[0]=='1'){
	      $query = "UPDATE empresas SET sw_activa='0' WHERE empresa_id='$empid'";
	      $result = $dbconn->Execute($query);
		  }else{
	      $query = "UPDATE empresas SET sw_activa='1' WHERE empresa_id='$empid'";
	      $result = $dbconn->Execute($query);
		  }
		}
		  $this->main();
			return true;
  }




/*funcion en la cual se crea una nueva empresa
 * retorna booleano
 */
 function InsertarEmp()
 {

		if($_REQUEST['ide']=='' ||
		$_REQUEST['id']=='' || $_REQUEST['nombreemp']=='' || $_REQUEST['reprelegal']=='' ||
		$_REQUEST['codigossg']=='' || $_REQUEST['pais']=='' || $_REQUEST['dpto']=='' ||
		$_REQUEST['mpio']=='')
		{
			if($_REQUEST['ide']==''){ $this->frmError["ide"]=1; }
			if($_REQUEST['id']==''){ $this->frmError["id"]=1; }
			if($_REQUEST['codigossg']==''){ $this->frmError["codigossg"]=1; }
			if($_REQUEST['pais']==''){ $this->frmError["pais"]=1; }
			if($_REQUEST['dpto']==''){ $this->frmError["dpto"]=1; }
			if($_REQUEST['mpio']==''){ $this->frmError["mpio"]=1; }
			$this->frmError["MensajeError"]="Faltan datos obligatorios.";
      $this->FormaIngresarEmpresa();
			return true;
		}
		else
		{
        if($_REQUEST['tipodoc']==-1)
				{
					$this->frmError['tipodoc']=1;
					$this->frmError["MensajeError"]="Debe seleccionar un tipo de documento.";
      		$this->FormaIngresarEmpresa();
					return true;
				}


		    if(empty($_REQUEST['activo']))
				{
					$swactivo='0';
				}
				else
				{
					$swactivo='1';
				}
				 if(empty($_REQUEST['mempresa']))
				{
					$swempresa='0';
				}
				else
				{
					$swempresa='1';
				}
        list($dbconn) = GetDBconn();
        $query="select count(*) from empresas where empresa_id='".$_REQUEST['ide']."'";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al insertar en la tabla  empresa";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}

        if($resulta->fields[0]>= 1)
				{
						$this->frmError["MensajeError"]="	ya existe un empresa con esa identificación.";
						$this->frmError["ide"]=1;
						$this->FormaIngresarEmpresa();
						return true;
        }

				$query="INSERT INTO empresas(
						    empresa_id,
								tipo_id_tercero,
								id,
								razon_social,
								representante_legal,
								codigo_sgsss,
								tipo_pais_id,
								tipo_dpto_id,
								tipo_mpio_id,
								direccion,
								telefonos,
								fax,
								codigo_postal,
								website,
								email,
								sw_activa,
								sw_usuarios_multiempresa)
								VALUES(
                '".$_REQUEST['ide']."',
								'".$_REQUEST['tipodoc']."',
								'".$_REQUEST['id']."',
								'".strtoupper($_REQUEST['nombreemp'])."',
								'".$_REQUEST['reprelegal']."',
								'".$_REQUEST['codigossg']."',
								'".$_REQUEST['pais']."',
								'".$_REQUEST['dpto']."',
        				'".$_REQUEST['mpio']."',
								'".$_REQUEST['dir']."',
								'".$_REQUEST['tel']."',
								'".$_REQUEST['fax']."',
								'".$_REQUEST['cpostal']."',
								'".$_REQUEST['web']."',
								'".$_REQUEST['email']."',
								'".$swactivo."',
								'".$swempresa."'
								)";

				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al insertar en la tabla  empresa";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
		}
		$this->main();
		return true;


}



/*funcion en la cual se modifica una empresa
 * retorna booleano
 */
 function ModificarEmp()
 {

		if($_REQUEST['id']=='' || $_REQUEST['nombreemp']=='' || $_REQUEST['reprelegal']=='' ||
		$_REQUEST['codigossg']=='' || $_REQUEST['pais']=='' || $_REQUEST['dpto']=='' ||
		$_REQUEST['mpio']=='')
		{
			if($_REQUEST['id']==''){ $this->frmError["id"]=1; }
			if($_REQUEST['codigossg']==''){ $this->frmError["codigossg"]=1; }
			if($_REQUEST['pais']==''){ $this->frmError["pais"]=1; }
			if($_REQUEST['dpto']==''){ $this->frmError["dpto"]=1; }
			if($_REQUEST['mpio']==''){ $this->frmError["mpio"]=1; }
			$this->frmError["MensajeError"]="Faltan datos obligatorios.";
      $this->FormaIngresarEmpresa(true,$_REQUEST['empid']);
			return true;
		}
		else
		{
        if(empty($_REQUEST['activo']))
				{
					$swactivo='0';
				}
				else
				{
					$swactivo='1';
				}
				 if(empty($_REQUEST['mempresa']))
				{
					$swempresa='0';
				}
				else
				{
					$swempresa='1';
				}
        list($dbconn) = GetDBconn();
       	$query="UPDATE empresas SET

								tipo_id_tercero='".$_REQUEST['tipodoc']."',
								id='".$_REQUEST['id']."',
								razon_social='".strtoupper($_REQUEST['nombreemp'])."',
								representante_legal='".$_REQUEST['reprelegal']."',
								codigo_sgsss='".$_REQUEST['codigossg']."',
								tipo_pais_id='".$_REQUEST['pais']."',
								tipo_dpto_id='".$_REQUEST['dpto']."',
								tipo_mpio_id='".$_REQUEST['mpio']."',
								direccion='".$_REQUEST['dir']."',
								telefonos='".$_REQUEST['tel']."',
								fax='".$_REQUEST['fax']."',
								codigo_postal='".$_REQUEST['cpostal']."',
								website='".$_REQUEST['web']."',
								email='".$_REQUEST['email']."',
								sw_activa='".$swactivo."',
								sw_usuarios_multiempresa='".$swempresa."' where empresa_id='".$_REQUEST['empid']."'";

				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al modificar en la tabla  empresa";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
		}
		$this->main();
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
* Funcion que busca los datos principales de los usuarios segun la empresa
* @return array
*/

	function TraerListadoUsuarios(){

		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo'])){
	  $query = "SELECT count(*) FROM system_usuarios WHERE usuario_id > 0 and sw_admin=0";
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
   $query = "SELECT b.usuario_id,a.usuario,a.nombre,a.descripcion,
							activo,sw_admin FROM system_usuarios a,system_usuarios_administradores b
							WHERE a.usuario_id > 0 AND a.sw_admin=0
							and a.usuario_id=b.usuario_id and b.empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
 							ORDER BY usuario";
							//LIMIT .$this->limit . " OFFSET $Of";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    $i=0;

		while (!$result->EOF)
		{
			$var[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
		}
    $result->Close();
    return $var;
	}


/**
* Funcion que busca los datos principales de los usuarios del sistema que no esten
* en la tabla system_usuarios-administradores
* @return array
*/

	function TraerListadoUsuariosSistema(){

		list($dbconn) = GetDBconn();
// 		if(empty($_REQUEST['conteo'])){
// 	  $query = "SELECT count(*) FROM system_usuarios WHERE usuario_id > 0";
// 		$result = $dbconn->Execute($query);
//
// 		if ($dbconn->ErrorNo() != 0) {
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 			return false;
// 		}
// 			list($this->conteo)=$result->fetchRow();
//     }else{
//       $this->conteo=$_REQUEST['conteo'];            //OJO DESCOMENTAR SI SE COLOCA LA BARRA
// 		}
// 		if(!$_REQUEST['Of']){
//       $Of='0';
// 		}else{
//       $Of=$_REQUEST['Of'];
// 		}
  		 $query = "	select usuario_id,usuario,nombre,descripcion from system_usuarios
									where usuario_id>0 and sw_admin=0
									except
									(
									select a.usuario_id,a.usuario,a.nombre,a.descripcion
									from system_usuarios_administradores b,system_usuarios a
									where b.usuario_id=a.usuario_id and empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
									) ORDER BY usuario";
			$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    $i=0;

		while (!$result->EOF)
		{
			$var[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
		}
    $result->Close();
    return $var;
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
* Funcion donde se Modifica en la base de datos el estado(1=activo,0=inactivo) del usuarios en el sistema
* @return boolean
*/

function BorrarUsuarios()
{
				list($dbconn) = GetDBconn();
				$uid=$_REQUEST['uid'];
				$query = "DELETE
									FROM  system_usuarios_administradores
									WHERE usuario_id=$uid
									AND empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."';

									DELETE
									FROM  system_usuarios_empresas
									WHERE usuario_id=$uid
									AND empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."';

                  DELETE
									FROM system_usuarios_menus WHERE usuario_id=$uid
									AND menu_id=15;";
				$dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
				$this->frmError["MensajeError"]="EL USUARIO NO SE BORRO YA QUE TIENE REGISTROS CARGADOS.";
				if(!$this->ListadoUsuarioEmpresa()){
				return false;
				}
				return true;
			}

		UserDelVar($uid,'Tema');
				$this->ListadoUsuarioEmpresa();
				return true;
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


function Usuario(){

    $action=ModuloGetURL('system','Administrador','admin','InsertarUsuariosSistema');
 		if(!$this->FormaInsertarUsuarioSistema('','','','','',$action,'','')){
         return false;
     }

		return true;
  }



/*funcion en la cual se crea un nuevo usuario administrador de empresas
 * mediante el usuario administrador primario
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
    //$loginUsuario=strtoupper($loginUsuario);
    $query = "INSERT INTO system_usuarios(
                       					usuario_id,
																usuario,
																nombre,
																descripcion,
																activo,
																sw_admin,
																passwd)
																VALUES($serial,'$loginUsuario','$nombreUsuario','$descripcion','$activo','0'
																,'".UserEncriptarPasswd('siis')."')";
		$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
		  $this->error = "Error al Guardar en system_usuarios";
		  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		  return false;
			}
			$query = "INSERT INTO system_usuarios_administradores(
                       					usuario_id,
																empresa_id)
																VALUES($serial,'".$_SESSION['ADMIN']['EMPRESAID']."')";
		$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
		  $this->error = "Error al Guardar en system_usuarios_administradores";
		  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		  return false;
			}
			//Es necesario adjudicarlo a la empresa por q si no el software no lo reconoce cuando se loguee
			//revisar esto OJO.
			$query = "INSERT INTO system_usuarios_empresas(
                       					usuario_id,
																empresa_id,
																sw_activo)
																VALUES($serial,'".$_SESSION['ADMIN']['EMPRESAID']."','1')";
		$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
		  $this->error = "Error al Guardar en system_usuarios_administradores";
		  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		  return false;
			}
	/*en estas lineas se le inserta al usuario admin el menu de usuarios  para administracion*/
			$query = "INSERT INTO system_usuarios_menus
										(usuario_id,menu_id)
										VALUES
										($serial,15)"; //el menu_id=15 el el modulo Usuarios para administrar los
										//usuarios de su empresa, TENER PRESENTE ESTA CUESTION...
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Insertar en system_usuarios_menus";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$query = "INSERT INTO system_usuarios_menus
										(usuario_id,menu_id)
										VALUES
										($serial,45)"; //el menu_id=15 el el modulo Usuarios para administrar los
										//usuarios de su empresa, TENER PRESENTE ESTA CUESTION...
					$result = $dbconn->Execute($query);
/*en estas lineas se le inserta al usuario admin el menu de usuarios  para administracion*/

     if(!empty($tema))
     	{
				UserSetVar($serial,'Tema',$tema);
			}

		//UserResetPasswd($serial); //el usuario administrador de usuarios operativos
		//no tendra caducidad de passwd y el reseteo va insertado de una en system_usuarios.
		$dbconn->CompleteTrans();   //termina la transaccion
		$this->ListadoUsuarioEmpresa();
	  return true;

  } //final funcion....


	function ModificarEstadoActivo()
{
 	list($dbconn) = GetDBconn();
	$sw=$_REQUEST['decision']; //variable que trae el campo a revisar activo ó sw_user ó sw_admin

	$query=		"SELECT $sw FROM system_modulos where
						modulo='".$_REQUEST['mod']."' AND modulo_tipo='".$_REQUEST['tipos1']."'";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al buscar el campo $sw en la tabla system_modulos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if($resulta->fields[0]==0)
		{
				$query="UPDATE system_modulos set $sw='1' where
								modulo='".$_REQUEST['mod']."' AND modulo_tipo='".$_REQUEST['tipos1']."'";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al actualizar el campo $sw en la tabla system_modulos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
		}
		else
		{
				$query="UPDATE system_modulos set $sw='0' where
								modulo='".$_REQUEST['mod']."' AND modulo_tipo='".$_REQUEST['tipos1']."'";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al actualizar el campo $sw en la tabla system_modulos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
		}
		$this->ListadoModulos();
		return true;
}


	function TraerModulo()
{
  	list($dbconn) = GetDBconn();
		$query="select modulo,modulo_tipo,descripcion,activo,sw_user,sw_admin
						from system_modulos
						where
					  modulo <> '' order by modulo";
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


}//fin clase user

?>


