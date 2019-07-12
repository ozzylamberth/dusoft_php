<?php

/**
 * $Id: users.inc.php,v 1.3 2009/07/30 12:52:01 johanna Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejador de Usuarios del Sistema
 */

// users.inc.php  05/08/2002

function UserEncriptarPasswd($passwd)
{
  return md5($passwd);
}

function UserCambiarPasswd($usuario_id, $nuevo_passwd,$caducidad='')
{
    list($dbconn) = GetDBconn();

    //verifica si tiene una variable en system_usuarios_vars, si esta la borra.
    //UserDelVar($usuario_id,'FechaResetPWD');
 if(empty($caducidad))
 {
        $query = "UPDATE system_usuarios
                            SET passwd = '" . UserEncriptarPasswd($nuevo_passwd) . "',
                            fecha_caducidad_contrasena=NULL
                            WHERE usuario_id = $usuario_id";

 }else{
        $query = "UPDATE system_usuarios
                            SET passwd = '" . UserEncriptarPasswd($nuevo_passwd) . "',
                            fecha_caducidad_contrasena='". date("Y-m-d",strtotime("+".$caducidad." days",strtotime(date("Y-m-d"))))."'
                            WHERE usuario_id = $usuario_id";

 }

    $dbconn->Execute($query);

    if($dbconn->Affected_Rows() == 0){
        return false;
    }

    return true;
}



function UserResetPasswd($usuario_id)
{
  $nuevo_passwd=ModuloGetVar('','','PasswordDefault');
    if(empty($nuevo_passwd)){
      $nuevo_passwd='siis';
    }

    list($dbconn) = GetDBconn();

    $query = "UPDATE system_usuarios
                  SET passwd = '" . UserEncriptarPasswd($nuevo_passwd) . "',
                        fecha_caducidad_contrasena='".date("Y-m-d")."'
                        WHERE usuario_id = $usuario_id";

    $dbconn->Execute($query);

  //ESTA LINEA SE CAMBIO X JAIME Y ALEX..HAY QUE ANALIZAR SI ESTO FUE EFECTIVO EN EL FUTURO.
    //UserSetVar($usuario_id,'FechaResetPWD',date("Y-m-d",strtotime("+".GetVarConfigAplication('CaducidadReset')."days",strtotime(date("Y-m-d")))));
    if($dbconn->Affected_Rows() == 0){
        return false;
    }

    return true;

}

function UserValidarPasswd($usuario,$passwd_digitado)
{
  list($dbconn) = GetDBconn();

  $query = "SELECT passwd,usuario_id
	          FROM system_usuarios
            WHERE  usuario= '$usuario'";
//exit;
  $result = $dbconn->Execute($query);

  if($dbconn->ErrorNo() != 0) {
      return false;
  }

	if ($result->EOF) {
			return false;
	}

	list($passwd_sistema,$usuario_id)=$result->FetchRow();
  $passwd_encriptado = UserEncriptarPasswd($passwd_digitado);

  if (strcmp($passwd_encriptado, $passwd_sistema) == 0){
        return $usuario_id;
    }else{
        return false;
    }
  return false;
}

function UserCompararPasswd($passwd_digitado, $passwd_real)
{
  $passwd_encriptado = UserEncriptarPasswd($passwd_digitado);
  if (strcmp($passwd_encriptado, $passwd_real) == 0){
        return true;
    }else{
        return false;
    }
}

function UserLoggedIn()
{
    if($_SESSION['SYSTEM_USUARIO_ID']) {
        return true;
    } else {
        return false;
    }
}

function UserGetUID()
{
    if($_SESSION['SYSTEM_USUARIO_ID']) {
        return $_SESSION['SYSTEM_USUARIO_ID'];
    } else {
        return 0;
    }
}


function UserLogIn($usuario_id)
{

  if (UserLoggedIn()) {
    if(!UserLogOut()){
      return false;
    }
  }

  if(empty($usuario_id)){
    return false;
  }

  list($dbconn) = GetDBconn();

  // Actualizar el UID en la tabla System_SessionInfo
  $query = "UPDATE system_session
            SET usuario_id = $usuario_id
            WHERE session_id = '" . session_id() . "'";
//exit;
  $dbconn->Execute($query);

  if($dbconn->Affected_Rows() == 0){
    return false;
  }

  // Crear las variables de Sesion
  SessionSetVar('SYSTEM_USUARIO_ID',$usuario_id);

//   if(!empty($empresa)){
//     SessionSetVar('SYSTEM_USUARIO_EMPRESA',$empresa);
//   }
// 
//   if(!empty($departamento)){
//     SessionSetVar('SYSTEM_USUARIO_DEPARTAMENTO',$departamento);
//   }

  //Crear el log
  InsertLogUser($usuario_id,2,'');
  return true;
}



function UserLogOut()
{
  list($dbconn) = GetDBconn();

  if (UserLoggedIn()) {
      $usuario_id=UserGetUID();

      $query = "UPDATE system_session
                 SET usuario_id = 0
                 WHERE session_id = '" . session_id() . "'";

      $dbconn->Execute($query);

      if ($result->EOF) {
          return false;
      }

      $estilo=SessionGetVar('StyleFrames');
      $_SESSION=array();
      SessionSetVar('StyleFrames',$estilo);

      if($dbconn->Affected_Rows() > 0){
        InsertLogUser($usuario_id,3,'');
      }
  }

  return true;
}


function UserGetVarsAdicionales($usuario_id)
{
  static $user_vars = array();

  if (isset($user_vars[$usuario_id])) {
      return $user_vars[$usuario_id];
  }

    list($dbconn) = GetDBconn();

     $query = "SELECT variable, valor
                  FROM system_usuarios_vars
                  WHERE usuario_id = $usuario_id";

    $result = $dbconn->Execute($query);

  if($dbconn->ErrorNo() != 0) {
      return false;
  }

    if (!$result->EOF) {
    while ($row = $result->FetchRow()) {
      $vars[$row[0]]=$row[1];
    }
    $result->Close();
    $user_vars[$usuario_id]=$vars;
         return $vars;
    }else{
    $result->Close();
    return false;
  }
}

function UserGetVars($usuario_id)
{
  static $user_vars = array();

  if (isset($user_vars[$usuario_id])) {
      return $user_vars[$usuario_id];
  }

    list($dbconn) = GetDBconn();

    $query = "SELECT *
                  FROM system_usuarios
                  WHERE usuario_id = $usuario_id";

    $result = $dbconn->Execute($query);

  if($dbconn->ErrorNo() != 0) {
      return false;
  }

    if ($result->EOF) {
        return false;
    }

    $vars = $result->GetRowAssoc(false);
    $result->Close();

    // Aunque esta encriptado protejo el password
  //ESTA PARTE HABRA QUE ANALIZARLA PARA DESPUES REALIZAR UNA MASCARA QUE FUNCIONE.
    //CUANDIO SE NECESITE COMPARAR LAS CONTRASE�AS.
    //$vars['passwd']='****************';

  //variables de usuario
  $definidasVars = UserGetVarsAdicionales($usuario_id);
  if(is_array($definidasVars))
  {
    foreach($definidasVars as $k=>$v){
      if(!isset($vars[$k])){
         $vars[$k]=$v;
      }
    }
    }
  $user_vars[$usuario_id]=$vars;
    return($vars);
}

function UserGetVar($usuario_id,$var)
{
  if(empty($usuario_id) || empty($var)){
        return;
  }

  $vars = UserGetVars($usuario_id);

  if(!$vars){
    return;
  }

  if(!isset($vars[$var])){
    return;
  }

  return $vars[$var];

}

function UserSetVar($usuario_id,$var,$valor='')
{
  if(empty($usuario_id) || empty($var)){
    return false;
  }

  $definidasVars = UserGetVarsAdicionales($usuario_id);
  if(is_array($definidasVars))
  {
    if(isset($definidasVars[$var])){
      $query = "UPDATE system_usuarios_vars
                 SET valor = '$valor'
                 WHERE usuario_id = $usuario_id AND variable = '$var'";
    }else{
      $query = "INSERT INTO system_usuarios_vars
                (usuario_id,variable,valor)
                VALUES($usuario_id,'$var','$valor')";
    }

  }else{
    $query = "INSERT INTO system_usuarios_vars
               (usuario_id,variable,valor)
               VALUES($usuario_id,'$var','$valor')";
  }
  list($dbconn) = GetDBconn();
  $dbconn->Execute($query);

  if($dbconn->ErrorNo() != 0) {
      return false;
  }

  return true;
}

function UserDelVar($usuario_id,$var)
{
  if(empty($usuario_id) || empty($var)){
    return false;
  }

  $query = "DELETE FROM system_usuarios_vars
            WHERE usuario_id = $usuario_id AND variable = '$var'";

  list($dbconn) = GetDBconn();
  $dbconn->Execute($query);

  if($dbconn->ErrorNo() != 0) {
      return false;
  }

  return true;
}


function UserGetEmpresas($usuario_id)
{
  static $user_empresas = array();

  if (isset($user_empresas[$usuario_id])) {
      return $user_empresas[$usuario_id];
  }

    list($dbconn) = GetDBconn();


  $query = "SELECT  DISTINCT a.empresa_id, d.razon_social
              FROM system_usuarios_empresas as a, system_usuarios_departamentos as b,
              departamentos as c, empresas as d
              WHERE c.empresa_id =  a.empresa_id AND
                    d.empresa_id =  a.empresa_id AND
                    c.departamento = b.departamento AND
                    a.usuario_id = b.usuario_id AND
                    a.usuario_id = $usuario_id";

  $result = $dbconn->Execute($query);

  if($dbconn->ErrorNo() != 0) {
      return false;
  }

  if (!$result->EOF) {
      while ($row = $result->FetchRow()) {
          $vars[$row[0]]=$row[1];
      }
      $user_empresas[$usuario_id]=$vars;
  }

  $result->Close();

    return($vars);
}


function UserGetDepartamentos($usuario_id, $empresa_id)
{
/*  static $user_departamentos = array();

  if (isset($user_departamentos[$usuario_id][$empresa_id])) {
      return $user_departamentos[$usuario_id][$empresa_id];
  }*/

    list($dbconn) = GetDBconn();

  $query = "SELECT a.departamento, b.descripcion
              FROM system_usuarios_departamentos as a, departamentos as b
              WHERE a.departamento = b.departamento AND
              a.usuario_id = $usuario_id
              AND b.empresa_id = '$empresa_id'
              ORDER BY departamento";

  $result = $dbconn->Execute($query);

  if($dbconn->ErrorNo() != 0) {
      return false;
  }

  if (!$result->EOF) {
      while ($row = $result->FetchRow()) {
          $vars[$row[0]]=$row[1];
      }
      $user_departamentos[$usuario_id]=$vars;
  }

  $result->Close();

    return($vars);
}


function UserEmpresasActivas($usuario_id)
{
    list($dbconn) = GetDBconn();
    $query = "SELECT count(*)
              FROM (SELECT DISTINCT a.empresa_id
                    FROM system_usuarios_empresas as a, system_usuarios_departamentos as b, departamentos as c
                    WHERE c.empresa_id =  a.empresa_id AND
                          c.departamento = b.departamento AND
                          a.usuario_id = b.usuario_id AND
                          a.usuario_id = $usuario_id
                  ) as EMPRESAS";
    $result = $dbconn->Execute($query);

    if($dbconn->ErrorNo() != 0) {
        return false;
    }

    list($numero_empresas) = $result->FetchRow();
    $result->Close();

    return $numero_empresas;
}

function UserDptosActivas($usuario_id)
{
    list($dbconn) = GetDBconn();
    $query = "SELECT count(*)
              FROM (SELECT DISTINCT b.departamento
                    FROM system_usuarios_empresas as a, system_usuarios_departamentos as b, departamentos as c
                    WHERE c.empresa_id =  a.empresa_id AND
                          c.departamento = b.departamento AND
                          a.usuario_id = b.usuario_id AND
                          a.usuario_id = $usuario_id
                  ) as EMPRESAS";
    $result = $dbconn->Execute($query);

    if($dbconn->ErrorNo() != 0) {
        return false;
    }

    list($numero_dptos) = $result->FetchRow();
    $result->Close();

    return $numero_dptos;
}

function UserValidarUsuario($usuario, $passwd)
{

  if(empty($usuario)){
    return false;
  }

  list($dbconn) = GetDBconn();

  $query = "SELECT usuario_id, passwd
            FROM system_usuarios
            WHERE usuario = '$usuario' AND activo='1'";

  $result = $dbconn->Execute($query);

  if ($result->EOF) {
    return false;
  }

  list($usuario_id, $passwd_real) = $result->FetchRow();
  $result->Close();

  if(empty($usuario_id)){
    return false;
  }
  if (UserCompararPasswd($passwd, $passwd_real)) {
    return $usuario_id;
  }

  return false;
}

function mensajes(){
    $mensaje = ConsultarMensajes();
    $numMensaje=$mensaje[0][todas]-$mensaje[0][leidas];
    if($numMensaje>1){
       $plusin="S"; 
    }else{
       $plusin=""; 
    }
    if($numMensaje==0){
      $mensajeAsunto='';   
    }else{
      $mensajeAsunto= $numMensaje.'  MENSAJE'.$plusin.' NUEVO'.$plusin;
    }
    $html.="<b>".$mensajeAsunto."</b>";
    return $html; 
}

function ConsultarMensajes(){
    list($dbconn) = GetDBconn();
        $usuario_id=UserGetUID();
        $sql ="select count(fecha_fin) as todas,count (cl.sw) as leidas
                from system_usuarios_perfiles as s
                inner join controlar_x_perfil as c on (c.perfil_id = s.perfil_id  or c.perfil_id=-1)
                inner join actualizaciones as a on a.actualizacion_id = c.actualizacion_id
                inner join system_usuarios as su on (s.usuario_id=su.usuario_id)
                left join controlar_lectura as cl on cl.actualizacion_id = a.actualizacion_id and cl.usuario_id='$usuario_id'
                where a.fecha_fin >=now() and s.usuario_id='$usuario_id'";
        
        if (!$result = $dbconn->Execute($sql))
            return false;
        while (!$result->EOF) {
            $vars[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $vars;
    }

?>