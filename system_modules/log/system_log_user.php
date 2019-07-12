<?php

class system_log_user extends classModulo
{

	function system_log_user()
	{
		return true;
	}

	function main()
	{
    if(!UserLoggedIn()){
      $this->frmLogin();
    }else{
      $this->frmConfirmarLogout();
    }
	  return true;
	}



  function validarLogin($usuario,$paswd)
	{

		if(empty($_REQUEST['usuario'])){
      $this->frmLogin('','Usuario no Valido');
      return true;
    }

    $usuario_id = UserValidarUsuario($_REQUEST['usuario'],$_REQUEST['passwd']);

    if(!$usuario_id){

      if(!SessionIsSetVar('MaximosIntentosDeLoguin')){
        SessionSetVar('MaximosIntentosDeLoguin', 1);
      }

      $MaximosIntentosDeLoguin = SessionGetVar('MaximosIntentosDeLoguin');

      if($MaximosIntentosDeLoguin >= GetVarConfigAplication('MaximosIntentosDeLoguin')){
          list($dbconn) = GetDBconn();
          $iphost=GetIPAddress();
          $query = "UPDATE system_host
                     SET sw_bloqueo = '1'
                     WHERE ip = '$iphost'";

          $result = $dbconn->Execute($query);
          SessionDelVar('MaximosIntentosDeLoguin');
          InsertLogHost($iphost,4,"$MaximosIntentosDeLoguin Intentos de Login Fallidos");
          $this->frmHostLock();
      }else{
          $MaximosIntentosDeLoguin++;
          SessionSetVar('MaximosIntentosDeLoguin', $MaximosIntentosDeLoguin);
          $this->frmLogin($_REQUEST['usuario'],'Falló en la autentificación, verifique su contraseña o el estado de su usuario con el administrador del sistema');
      }

    }else{
        SessionDelVar('MaximosIntentosDeLoguin');
        if(UserGetVar($usuario_id,'sw_admin')){
          UserLogIn($usuario_id);
          $this->frmRefrescar('Iniciando Sesión como Administrador del Sistema....');
        }else{


						// verificamos si el usuario tiene contraseña reseteada.
						//REVISAR DESPUES CAMBIO POR ALEX.
              //$FechaInicio=UserGetVar($usuario_id,'FechaResetPWD');

               $Fecha_Caducacion_Cuenta=UserGetVars($usuario_id);


               //Revisamos por medio de esta funcion si la cuenta no esta o esta vencida.
							 $resultado=$this->RevisarCaducidaCuenta($usuario_id,$Fecha_Caducacion_Cuenta[fecha_caducidad_cuenta]);


               //si se ha retornado un 1 quiere decir q la cuenta ya caduco.
    					 if($resultado==1){
							  $this->frmLogin('','Su cuenta esta inactiva usuario '.$Fecha_Caducacion_Cuenta[usuario].'&nbsp; por favor consultar al administrador del sistema');
								return true;
							}


							//si es nula implica que el usuario tiene una contraseña y esta puede vencer o no.
							//if(is_null($FechaInicio))
							//{
                  $res_bool=$this->RevisarCaducidaContraseña($usuario_id,$Fecha_Caducacion_Cuenta[fecha_caducidad_contrasena]); //Revisamos primero caducidad cuenta.

									if($res_bool==1)  //si se ha retornado un 1 implica que la contraseña caduco.
									{
									   $this->frmCambiarPWD($usuario_id,'',' Debe cambiar el password del usuario '.$Fecha_Caducacion_Cuenta[usuario].'&nbsp; para continuar');
										 return true;
									}

						//	}
						//	else
						//	{
								//	$resulta_bool=$this->RevisarCaducidaPWDReset($usuario_id,$FechaInicio); //revisar pawd reseteado

								//		if($resulta_bool==1)  //si se ha retornado un 1 implica que la contraseña caduco.
								//	{
								//		 $this->frmCambiarPWD($usuario_id,'',' Debe cambiar el password del usuario '.$Fecha_Caducacion_Cuenta[usuario].'&nbsp; para continuar');
								//		 return true;
							//		}

							//}

									/****fin prueba*****/
											$_REQUEST['usuario']=$usuario_id;
											$this->loguin_full();
											
		}
				  
		$this->guardarRegistroConexion($usuario_id);
    }
	  return true;
	}



//   /*
// 	*  Revisa si el password usado es un 'reset', verifica la fecha del usuario.
// 	*  si la fecha lleva mas de un dia(prueba) se bloqueara el equipo.
// 	*/
// 	function RevisarCaducidaPWDReset($usuario_id,$FechaInicio)
// 	{
// 			if(!is_null($FechaInicio))
// 			{
// 					//$fech_cad_contraseña='2004/04/18';
// 					if(strtotime($FechaInicio) < strtotime(date("y-m-d")))
// 					{
// 								return 1;
// 					}
// 
// 			}
// 			return 0;
// 		}


	function guardarRegistroConexion($usuarioId){

		list($dbconn) = GetDBconn();
			$query = "INSERT INTO usuarios_auditoria_conexion(usuario_id, ip, app) 
								VALUES (".$usuarioId.", '".GetIPAddress()."', 'dusoft-web (v1)')";
			
			$result = $dbconn->Execute($query);
	}

		 /*
	*  Revisa si la contraseña del usuario ha caducado.
	*  si la fecha lleva ya se ha vencido con respecto a la actual se bloqueara el usuario.
	*/
	function RevisarCaducidaContraseña($usuario_id,$fech_cad_contraseña)
	{
      if(!is_null($fech_cad_contraseña))
			{
					//$fech_cad_contraseña='2004/04/18';

					if(strtotime($fech_cad_contraseña) < strtotime(date("y-m-d")))
					{
								return 1;
					}

			}
			return 0;
		}



		 /*
	*  Revisa si la cuenta del usuario ha caducado.
	*  si la fecha lleva ya se ha vencido con respecto a la actual se bloqueara el usuario.
	*/
	function RevisarCaducidaCuenta($usuario_id,$fech_cad_cuenta)
	{
	   	if(!is_null($fech_cad_cuenta))
			{
			  //$fech_cad_cuenta='2004/04/29';

				if(strtotime($fech_cad_cuenta) < strtotime(date("y-m-d")))
				{
             	list($dbconn) = GetDBconn();
						 	$query = "UPDATE system_usuarios
												SET activo = '0'
												WHERE usuario_id=$usuario_id";

							$result = $dbconn->Execute($query);
       				//MsgOut("CUENTA CADUCADA","La IP " . GetIPAddress() . " no tiene permiso de acceso.");
              return 1;
				}

			}
			return 0;
		}




function loguin_full()
  {
		//echo $_REQUEST['usuario'];
		UserLogIn($_REQUEST['usuario']);
		//UserSetVar($_REQUEST['usuario'],'ultimo_dpto_login',$_REQUEST['departamento']);
    $this->frmRefrescar('Iniciando la Sesión ....');
    return true;
  }

  function loguin()
  {
    if(!UserLoggedIn()){
        $this->frmLogin();
    }
		return true;
  }

  function logout()
  {
	   if(UserLoggedIn()){
        UserLogOut();
        $this->frmRefrescar('Cerrando la Sesión ....');
    }
    return true;
  }



  /*
	* Esta funcion valida el cambio de password de un usuario al cual ya se ha vencido
	* su cuenta ya sea por via de 'Reset' ó por via de 'caducidad' y debera cambiarla
	* si quiere que siga.
	*/
	function validarCambioPass()
	{
   			//sacamos el pwd del usuario para compararlo
				$old_pass=UserGetVars($_REQUEST['usuario_id']);

				if(empty($_REQUEST['passwd']) || empty($_REQUEST['rpasswd'])){
        	$this->frmCambiarPWD($_REQUEST['usuario_id'],'',' Debe cambiar el password del usuario '.$old_pass[usuario].'&nbsp; para continuar');
					return true;
				}


				if(strcmp($_REQUEST['passwd'],$_REQUEST['rpasswd'])==0){
									//$passwd=UserEncriptarPasswd($_REQUEST['passwd']);

								}else{
										$this->frmCambiarPWD($_REQUEST['usuario_id'],'',' Debe escribir el nuevo password del usuario '.$old_pass[usuario].'&nbsp; y repetirlo para mayor seguridad');
										return true;
										}


					$bool=UserCompararPasswd($_REQUEST['passwd'], $old_pass[passwd]);

					if($bool==1)
					{
					 	$this->frmCambiarPWD($_REQUEST['usuario_id'],'','Por favor no repita el mismo password  usuario '.$old_pass[usuario].'&nbsp; digite un nuevo password para continuar');
						return true;
					}
					else
					{
					    	   	UserCambiarPasswd($_REQUEST['usuario_id'], $_REQUEST['passwd'],$old_pass[caducidad_contrasena]);
// 										$numero_empresas = UserEmpresasActivas($_REQUEST['usuario_id']);
// 										if(empty($numero_empresas)){
// 											$this->frmNoEmpresas();
// 										}else{
// 												$this->frmSeleccionarEmpresas($_REQUEST['usuario_id']);
// 									}
											//$_REQUEST['usuario']=$usuario_id;
											$this->loguin_full();

							return true;
					}
    		return true;
		  }

}

?>
