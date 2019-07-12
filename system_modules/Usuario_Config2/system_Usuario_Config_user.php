<?php

/**
*MODULO para el Manejo de Usuarios del Sistema
*
* @author Jairo Duvan Diaz Martinez
* ultima actualizacion: Jairo Duvan Diaz Martinez -->lunes 1 de marzo 2004
*/

// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Emai: intersof@telesat.com.co
// ----------------------------------------------------------------------

/**
*Contiene los metodos para realizar la administracion de usuarios
*/

class system_Usuario_Config_user extends classModulo
{
		var $limit;
		var $conteo;

	function system_Usuario_Config_user()
	{
		//$this->limit=GetLimitBrowser();
	//	$this->limit=5;
		return true;
	}

/**
* Funcion donde se llama la funcion FormaInsertarUsuarioSistema
* @return boolean
*/

	function main(){

  	if(!$this->Menu()){
        return false;
    }
		return true;
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
* Funcion que busca los datos principales de los usuarios del sistema
* @return array
*/

	function BuscarUsuariosSistema()
	{

				list($dbconn) = GetDBconn();
				if(empty($_REQUEST['conteo']))
				{

						$query = "SELECT count(*) FROM system_usuarios";
						$result = $dbconn->Execute($query);

						if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
				$query = "SELECT usuario_id,
												usuario,
												nombre,
												descripcion,
												passwd,
												activo,
												sw_admin
										FROM system_usuarios ORDER BY usuario LIMIT " . $this->limit . " OFFSET $Of";
				$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$i=0;
				while(!$result->EOF)
				{
					$datos[$i]=$result->fields[0].'/'.$result->fields[1].'/'.$result->fields[2].'/'.$result->fields[3].'/'.$result->fields[4].'/'.$result->fields[5].'/'.$result->fields[6];
					$result->MoveNext();
					$i++;
				}
				$result->Close();
				return $datos;
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
		$action=ModuloGetURL('system','Usuario_Config','user','ModificarUsuariosSistema',array("uid"=>$uid));
		if(!$this->FormaInsertarUsuarioSistema($nombre,$usuario,'','',$tema,$action,$consulta,$descripcion)){
        return false;
    }
		return true;
  }

/**
* Funcion donde se llama la funcion FormaInsertarUsuarioSistema
* @return boolean
*/

	function LlamaConfigUsuarioSistema(){
		$action=ModuloGetURL('system','Usuario_Config','user','InsertarConfig',array("uid"=>$uid));
		if(!$this->FormaConfigUsuarioSistema($action)){
        return false;
    }
		return true;
  }



/*funcion que refresca el tema nuevo esta funcion es experimental....*/
function RefrescarTema($tema)
  {
     $this->RefrescarPantalla('Guardando Nueva Configuración '.$tema.' ....');
     return true;
  }
/**********************************************************************/




/**
* Funcion donde se inserta la configuración del usuario
* @return boolean
*/
	function InsertarConfig()
	{
	    $barra=$_REQUEST['barra'];
			$uid=UserGetUID();
		 	$tema=$_REQUEST['tema'];
			if($tema==-1)
			{
				$tema='';
			}

			if(!empty($barra))
			{
			 				UserSetVar($uid,'LimitRowsBrowser',$barra);
			}
			else
			{
							UserDelVar($uid,'LimitRowsBrowser');
			}
			if(!empty($tema))
			{
					if($tema != UserGetVar(UserGetUID(),'Tema'))
					{
							UserSetVar($uid,'Tema',$tema);
							$this->RefrescarTema($tema);
							return true;
					}

			}
			else
			{
				UserDelVar($uid,'Tema');

			}
		$this->main();	
		return true;
	}



 /**
* Funcion que trae el valor de la variable 'LimitRowsBrowser' segun el usuario_id
* @return integer
*/
		function TraerBarra()
		{
		   $uid=UserGetUID();
       $var=UserGetVar($uid,'LimitRowsBrowser');
			 return $var;
		}


 /**
* Funcion que trae el usuario,nombre,password de la tabla system_usuarios
* @return array
*/
function TraerUsuario()
{
	list($dbconn) = GetDBconn();
	$query="select usuario,nombre,passwd from system_usuarios where usuario_id=".UserGetUID()."";
	$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar el usuario Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
	else
		{
		$i=0;
				while(!$resulta->EOF)
							{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$i++;
									$resulta->MoveNext();
							}
		}
			return $var;
}



function LlamaFormaModificarPasswd(){
    $action=ModuloGetURL('system','Usuario_Config','user','ModificarPasswd',array("uid"=>$uid));
		if(!$this->FormaModificarPasswd($action)){
        return false;
    }
		return true;
	}



/**
* Funcion que modifica en la base de datos el password actual que tiene el usuario en el sistema
* @return boolean
*/

  function ModificarPasswd(){
		$password=$_REQUEST['password'];
  	$passwordReal=$_REQUEST['passwordReal'];
		$nombre=$_REQUEST['nombre'];
		$usuario=$_REQUEST['usuario'];
 		$uid=UserGetUID();

    //sacamos la caducacion de la contraseña del usuario.
				$contraseña=UserGetVars($uid);
		if($_REQUEST['aceptar'])
		{
						if($password=='' || $passwordReal==''){
							if($password==''){ $this->frmError["password"]=1; }
							if($passwordReal==''){ $this->frmError["passwordReal"]=1; }
							$this->frmError["MensajeError"]="Faltan datos obligatorios.";
							$action=ModuloGetURL('system','Usuario_Config','user','ModificarPasswd',array("uid"=>$uid));
							$this->FormaModificarPasswd($action,$password,$passwordReal,$nombre,$usuario);
							return true;
						}

						if(strcmp($password,$passwordReal)==0){
							UserCambiarPasswd($uid,$password,$contraseña[caducidad_contraseña]);
							$this->frmError["MensajeError"]="Password guardado exitosamente";
              unset($_SESSION['PWD']);
							$this->FormaModificarPasswd();
							return true;

						}else{
							$this->frmError["MensajeError"]="La Contraseña no coinciden";
							$action=ModuloGetURL('system','Usuario_Config','user','ModificarPasswd',array("uid"=>$uid));
							$this->FormaModificarPasswd($action);
							return true;
						}


		}
		elseif($_REQUEST['verificar'])
		{

					$re=UserCompararPasswd($_REQUEST['viejopass'],$_REQUEST['viejo']);
					if($re==true)
				{
					$_SESSION['PWD']=true;
					$this->LlamaFormaModificarPasswd();
					return true;
				}
				else
				{
					$this->frmError["MensajeError"]="La Contraseña esta errada.";
					$this->LlamaFormaModificarPasswd();
					return true;
				}

		}


 }


 function LlamaImpresora()
 {
		$this->FormaImpresorasPredeterminadas();
		return true;
 }

 function TraerImpresorasPrdeterminadas()
 {
	list($dbconn) = GetDBconn();
  $query="SELECT a.impresora,b.sw_predeterminada FROM system_printers as a left join
	system_printers_host b on(a.impresora=b.impresora) WHERE b.ip='".GetIPAddress()."'";
	$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar el usuario Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
		$i=0;
				while(!$resulta->EOF)
							{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$i++;
									$resulta->MoveNext();
							}
		}
			return $var;
 }




}//fin clase user

?>

