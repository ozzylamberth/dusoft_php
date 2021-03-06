<?php

/**
 * $Id: app_Autorizacion_user.php,v 1.23 2007/01/29 19:30:15 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */

class app_Autorizacion_user extends classModulo
{

  var $DatosRetono=array();

    function app_Autorizacion_user()
    {
      return true;
    }

    /**
    *La funcion main es la principal y donde se llama FormaBuscar de la clase
    *app_Triage_user_HTML que muestra la forma para buscar al paciente
    */
    function main()
    {
        $this->FormaMenuAuto();
         return true;
    }

		/**
		*
		*/
		function AutorizacionCaja()
		{
						unset($_SESSION['SOLICITUDAUTORIZACION']['VECTOR']);
						unset($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
						unset($_SESSION['AUTORIZACION']);
						unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGOS']['VECT']);
						$_SESSION['AUTORIZACIONES']['CAJA']['AUTO']=1;

						if(empty($_SESSION['AUTORIZACIONES']))
						{
									$this->error = "AUTORIZACION NULA";
									$this->mensajeDeError = "DATOS DE LA AUTORIZACIÓN VACIOS.";
									return false;
						}

						if(empty($_SESSION['AUTORIZACIONES']['RETORNO']))
						{
									$this->error = "AUTORIZACION ";
									$this->mensajeDeError = "EL RETORNO DE LA AUTORIZACIÓN ESTA VACIO.";
									return false;
						}

						$PacienteId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
						$TipoId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
						$PlanId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];

						if(empty($PacienteId) || empty($TipoId) || empty($PlanId))
						{
										$this->error = "AUTORIZACION ";
										$this->mensajeDeError = "DATOS DE LA AUTORIZACIÓN INCOMPLETOS.";
										return false;
						}

            list($dbconn) = GetDBconn();
            $query = "select protocolos from planes
                                where plan_id='$PlanId'";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "ERROR AL GUARDAR EN LA BASE DE DATOS";
                    $this->mensajeDeError = "ERROR DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['protocolo']=$result->fields[0];
            $result->Close();


             $query = " SELECT a.plan_id
                        FROM planes_auditores_int as a
                        WHERE a.plan_id='$PlanId'
                        and a.usuario_id=".UserGetUID()."";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "ERROR AL GUARDAR EN LA BASE DE DATOS";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            if(!$result->EOF)
            {
                    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUDITOR']=$result->fields[0];
            }

						if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
						{
								$this->error = "Error";
								$this->mensajeDeError = "NO SE PUDO INCLUIR : classes/notas_enfermeria/revision_sistemas.class.php";
								return false;
						}
						if(!class_exists('BDAfiliados'))
						{
								$this->error="Error";
								$this->mensajeDeError="NO EXISTE BD AFILIADOS";
								return false;
						}

						$class= New BDAfiliados($TipoId,$PacienteId,$PlanId);
						$class->GetDatosAfiliado();
						 if($class->GetDatosAfiliado()==false)
						{
												$this->frmError["MensajeError"]=$class->mensajeDeError;
						}

						if(!empty($class->salida))
						{
									unset($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
									$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']=$class->salida;
									//en 1 esta activo
									if($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_activo']==1)
									{
												if(!empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_afiliado_id'])
													AND !empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['rango']))
												{
														if($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado']!=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_afiliado_id']
														OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel']!=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['rango']
														OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas']!=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['semanas_cotizadas'])
														{
															$_SESSION['AUTORIZACIONES']['CAJA']['CAMBIO']=1;
														}
												}

												$this->FormaAfiliado();
												//$this->RetornarAutorizacion(true,'','',0);
												return true;
									}//no esta activo
									else
									{
												$_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']='EL PACIENTE SE ENCUENTRA INACTIVO EN LA BASE DE DATOS.';
												$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TODO']=1;
												$c2=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
												$mo2=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
												$me2=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
												$c='app';
												$mo='Autorizacion';
												$me='FormaAutorizacion';
												$msg='El paciente esta INACTIVO, esta en estado '.$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd'].' necesita una Autorización.';
												$this->ConfirmarAccion('AUTORIZAR PACIENTE',$msg,'AUTORIZAR','CANCELAR',$c,$mo,$me,$c2,$mo2,$me2);
												return true;
									}
						}
						else
						{
									$_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']='EL PACIENTE NO SE ENCUENTRA EN LA BASE DE DATOS.';
									$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TODO']=1;
									$c2=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
									$mo2=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
									$me2=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
									$c='app';
									$mo='Autorizacion';
									$me='FormaAutorizacion';
									$msg='El paciente no esta en la base de datos necesita una Autorización.';
									$this->ConfirmarAccion('AUTORIZAR PACIENTE',$msg,'AUTORIZAR','CANCELAR',$c,$mo,$me,$c2,$mo2,$me2);
									return true;
						}
		}


		/***
		*
		*/
		function SolicitudAutorizacionAmbulatoria()
		{
						unset($_SESSION['SOLICITUDAUTORIZACION']['VECTOR']);
						unset($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
						unset($_SESSION['AUTORIZACION']);
						unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGOS']['VECT']);

						if(empty($_SESSION['AUTORIZACIONES']))
						{
									$this->error = "AUTORIZACION NULA";
									$this->mensajeDeError = "DATOS DE LA AUTORIZACIÓN VACIOS.";
									return false;
						}

						if(empty($_SESSION['AUTORIZACIONES']['RETORNO']))
						{
									$this->error = "AUTORIZACION ";
									$this->mensajeDeError = "EL RETORNO DE LA AUTORIZACIÓN ESTA VACIO.";
									return false;
						}

						$PacienteId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
						$TipoId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
						$PlanId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];

						if(empty($PacienteId) || empty($TipoId) || empty($PlanId))
						{
										$this->error = "AUTORIZACION ";
										$this->mensajeDeError = "DATOS DE LA AUTORIZACIÓN INCOMPLETOS.";
										return false;
						}

            list($dbconn) = GetDBconn();

            $query = "SELECT sw_tipo_plan, sw_afiliacion, protocolos, sw_autoriza_sin_bd
											FROM planes
											WHERE estado='1' and plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
											and fecha_final >= now() and fecha_inicio <= now()";
            $results = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if ($dbconn->EOF)
	    {
                    $this->RetornarAutorizacion(false,'','EL PLAN NO EXISTE, NO TIENE VIGENCIA, O NO ESTA ACTIVO',0);
                    return true;
            }

            list($TipoPlan,$swAfiliados,$Protocolos,$swAutoSinBD)=$results->FetchRow();
            $_SESSION['AUTORIZACIONES']['RETORNO']['Protocolos']=$Protocolos;
            $_SESSION['AUTORIZACIONES']['RETORNO']['sw_autorizacion_sin_bd']=$swAutoSinBD;

		//el plan soat no debe llegar aqui
	    if($TipoPlan==1)
	    {
		$this->RetornarAutorizacion(false,'','EL PLAN SOAT NO ES ADMITIDO AQUI, REMITA AL CENTRO DE AUTORIZACIÓN',0);
		return true;
	    }
	
	if(($TipoPlan==0 AND $swAfiliados==1) OR ($swAfiliados==1) OR $TipoPlan == 3 )
	{
    //funcion que busca en las bases de afiliados (en caso de plan cliente o capitado)
		if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
		{
			$this->error = "Error";
 			$this->mensajeDeError = "NO SE PUDO INCLUIR : classes/notas_enfermeria/revision_sistemas.class.php";
			return false;
		}
		if(!class_exists('BDAfiliados'))
		{
			$this->error="Error";
			$this->mensajeDeError="NO EXISTE BD AFILIADOS";
			return false;
		}

		$class= New BDAfiliados($TipoId,$PacienteId,$PlanId);
		$class->GetDatosAfiliado();
		if($class->GetDatosAfiliado()==false)
		{
			$this->frmError["MensajeError"]=$class->mensajeDeError;
		}

		if(!empty($class->salida))
		{
			unset($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
			$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']=$class->salida;
			//en 1 esta activo
			if($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_activo']==1)
			{
				if(!empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_afiliado_id'])
					AND !empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['rango']))
				{
					if($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado']!=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_afiliado_id']
																OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel']!=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['rango']
																OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas']!=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['semanas_cotizadas'])
					{
						$_SESSION['AUTORIZACIONES']['CAJA']['CAMBIO']=1;
					}
				}
				$this->FormaAfiliado();
				return true;
			}//no esta activo
			else
			{
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']='EL PACIENTE SE ENCUENTRA INACTIVO EN LA BASE DE DATOS.';
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TODO']=1;
				$c2=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
				$mo2=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
				$me2=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
				$c='app';
				$mo='Autorizacion';
				$me='FormaAutorizacion';
				$msg='El paciente esta INACTIVO, esta en estado '.$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd'].' necesita una Autorización.';
				$this->ConfirmarAccion('AUTORIZAR PACIENTE',$msg,'AUTORIZAR','CANCELAR',$c,$mo,$me,$c2,$mo2,$me2);
				return true;
			}
		}
		else
		{
			if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['sw_autorizacion_sin_bd']))
			{
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']='EL PACIENTE NO ENCUENTRA EN LA BASE DE DATOS DE LA ENTIDAD, NECESITA CERTIFICADO DE CARTERA.';
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TODO']=1;
				$c2=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
				$mo2=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
				$me2=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
				$c='app';
				$mo='Autorizacion';
				$me='FormaAutorizacion';
				$msg='El paciente no se encuentra registrado en la base de datos,  necesita una Autorización.';
				$this->ConfirmarAccion('AUTORIZAR PACIENTE',$msg,'AUTORIZAR','CANCELAR',$c,$mo,$me,$c2,$mo2,$me2);
//echo '==>'; print_r($_SESSION['AUTORIZACIONES']['AUTORIZAR']);exit;
				return true;
			}
			else
			{
				$c=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
				$mo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
				$me=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
				$tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
				$accion=ModuloGetURL($c,$mo,$tipo,$me);
				$mensaje='El Paciente no se Encuentra Registrado en la Base de Datos de la Entidad.  No Puede ser Autorizado.';
				$this->FormaMensaje($mensaje,'AUTORIZACION',$accion);
				return true;
			}
		}
	}
	elseif($TipoPlan==2 OR ($TipoPlan==0 AND $swAfiliados==0))
	{//es particular
		$query="SELECT tipo_afiliado_id,rango FROM planes_rangos
			WHERE plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'";
		$result=$dbconn->Execute($query);
		if(!$result->EOF){
			$dat=$result->GetRowAssoc($ToUpper = false);
		}
		else
		{
			$c=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
			$mo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
			$me=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
			$tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
			$accion=ModuloGetURL($c,$mo,$tipo,$me);
			$mensaje='ERROR: EL RANGO Y TIPO DE AFILIADO NO ESTAN PRESENTES EN LA TABLA PLANES_RANGOS. COMUNIQUESE CON SISTEMAS.';
			$this->FormaMensaje($mensaje,'AUTORIZACION',$accion);
			return true;
		}
		$result->CLose();
		$_SESSION['AUTORIZACIONES']['AFILIADO']=$dat[tipo_afiliado_id];
		$_SESSION['AUTORIZACIONES']['RANGO']=$dat[rango];
		$_SESSION['AUTORIZACIONES']['SEMANAS']=0;
		$this->RetornarAutorizacion(true,'PTC','Plan Particular',1);
		return true;
	}
    }

    /**
    *
    */
    function SolicitudAutorizacion()
    {
            unset($_SESSION['SOLICITUDAUTORIZACION']['VECTOR']);
            unset($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
            unset($_SESSION['AUTORIZACION']);
            unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGOS']['VECT']);
						unset($_SESSION['AUTORIZACIONES']['CAJA']['CAMBIO']);

						if(empty($_SESSION['AUTORIZACIONES']))
						{
										$this->error = "AUTORIZACION NULA";
										$this->mensajeDeError = "DATOS DE LA AUTORIZACIÓN VACIOS.";
										return false;
						}

						if(empty($_SESSION['AUTORIZACIONES']['RETORNO']))
						{
										$this->error = "AUTORIZACION ";
										$this->mensajeDeError = "EL RETORNO DE LA AUTORIZACIÓN ESTA VACIO.";
										return false;
						}

						if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']))
						{
										$this->error = "AUTORIZACION ";
										$this->mensajeDeError = "LA SOLICITUD DE AUTORIZACIÓN ESTA VACIA.";
										return false;
						}

						if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['CUPS'])
								AND $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION']!='Admon')
						{
										$this->error = "AUTORIZACION ";
										$this->mensajeDeError = "DEBE ENVIAR EL CARGO CUPS.";
										return false;
						}

            $PacienteId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
            $TipoId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
            $PlanId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];
            $TipoServicio=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'];
            $TipoAutorizacion=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION'];

            if(empty($PacienteId) || empty($TipoId) || empty($PlanId) || empty($TipoServicio)|| empty($TipoAutorizacion))
            {
                    $this->error = "AUTORIZACION ";
                    $this->mensajeDeError = "DATOS DE LA AUTORIZACIÓN INCOMPLETOS.";
                    return false;
            }

            list($dbconn) = GetDBconn();
            $query = "select protocolos from planes where plan_id='$PlanId'";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['protocolo']=$result->fields[0];
            $result->Close();


             $query = " SELECT a.plan_id FROM planes_auditores_int as a
                        WHERE a.plan_id='$PlanId'  and a.usuario_id=".UserGetUID()."";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            if(!$result->EOF)
            {
                    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUDITOR']=$result->fields[0];
            }

            $query = "SELECT nivel_autorizador_id FROM userpermisos_centro_autorizacion
                      WHERE usuario_id=".UserGetUID()."";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error DELETE FROM hc_os_autorizaciones_proceso";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            if(!$result->EOF)
            {    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['NIVEL']=$result->fields[0];   }

            //unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']);

            switch($TipoAutorizacion)
            {
                        case 'Admon':
                                    switch($TipoServicio)
                                    {
                                            case 'AMBULATORIA':
                                            $this->AutorizarAdmisionAmbulatoria();
                                            break;
                                            case 'URGENCIAS':
                                            $this->AutorizarAdmisionUrgencias();
                                            break;
                                            case 'HOSPITALIZACION':
                                            $this->AutorizarAdmisionHospitalizacion();
                                            break;
                                            default:
                                                $this->error = "AUTORIZACION ";
                                                $this->mensajeDeError = "TIPO DE SERVICIO INCORRECTO";
                                                return false;
                                    }
                        break;

                        case 'Cargo':

                                    if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO']) || empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TARIFARIO']))
                                    {
                                            $this->error = "AUTORIZACION ";
                                            $this->mensajeDeError = "EL CARGO PARA AUTORIZAR ESTA VACIO.";
                                            return false;
                                    }
                                    switch($TipoServicio)
                                    {
                                            case 'CONSULTAEXTERNA':
                                            $this->AutorizarCargoConsultaExterna();
                                            break;
                                            default:
                                                $this->error = "AUTORIZACION ";
                                                $this->mensajeDeError = "TIPO DE SERVICIO INCORRECTO";
                                                return false;
                                    }
                        break;

                        default:
                                $this->error = "AUTORIZACION ";
                                $this->mensajeDeError = "TIPO DE AUTORIZACIÓN INCORRECTA";
                                return false;
             }
             return true;
    }

    /**
    *
    */
    function AccionCancelar()
    {
            $this->RetornarAutorizacion(false,'ADMITIR','',0);
            return true;
    }


    /**
    *
    */
    function RetornarAutorizacion($Autorizacion=false,$Codigo='',$Mensaje='',$NumAutorizacion=0)
    {
				//hay q hacer lo del empleador
				if(!empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['EMPLEADOR']))
				{
						//tiene varios empleadores
						/*if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_empleador1']))
						{

						}
						else*/
						if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_empleador']))
						{
								$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_empleador'];
								$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_id_empleador'];
								$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_empleador'];
								$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['telefono_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_telefono_empresa'];
								$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['direccion_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_direccion_empresa'];

								list($dbconn) = GetDBconn();
								$query = "SELECT * FROM empleadores
													WHERE tipo_id_empleador='".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador']."'
													AND empleador_id='".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador']."'";
								$result = $dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
												$this->error = "SELECT * FROM empleadores";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												return false;
								}
								//no existe el empleador en la tabla
								if($result->EOF)
								{
										$query = "INSERT INTO empleadores(
																				empleador_id,
																				tipo_id_empleador,
																				nombre,
																				direccion,
																				telefono,
																				usuario_id)
															VALUES('".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador']."','".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador']."','".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['empleador']."','".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['direccion_empleador']."','".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['telefono_empleador']."',".UserGetUID().")";
										$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error al Cargar el Modulo";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												return false;
										}
								}
								$result->Close();
						}
				}

				if(empty($Autorizacion) AND $Autorizacion!=1 AND !empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']) AND $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']!=1)
				{
						list($dbconn) = GetDBconn();
						$query = "delete from autorizaciones
																where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "delete autorizaciones_solicitudes_cargos";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}

						$query = "select * from auditoria_cambio_datos_bdafiliados
											where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
						$result=$dbconn->Execute($query);
						if(!$result->EOF)
						{
								$query = "delete from auditoria_cambio_datos_bdafiliados
																	where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
												$this->error = "delete autorizaciones_solicitudes_cargos";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												return false;
								}
						}
						$result->Close();
				}

				$_SESSION['AUTORIZACIONES']['RETORNO']['ext']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['EXT'];
				$_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']=$Autorizacion;
				$_SESSION['AUTORIZACIONES']['RETORNO']['Codigo']=$Codigo;
				$_SESSION['AUTORIZACIONES']['RETORNO']['Mensaje']=$Mensaje;
				$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion']=$NumAutorizacion;
				$_SESSION['AUTORIZACIONES']['RETORNO']['paciente_id']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
				$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_id_paciente']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
				$_SESSION['AUTORIZACIONES']['RETORNO']['plan_id']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];
				$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['AFILIADO'];
				$_SESSION['AUTORIZACIONES']['RETORNO']['rango']=$_SESSION['AUTORIZACIONES']['RANGO'];
				$_SESSION['AUTORIZACIONES']['RETORNO']['semanas']=$_SESSION['AUTORIZACIONES']['SEMANAS'];
					if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
				{  $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO'];								  }
					if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['semanas']))
					{  $_SESSION['AUTORIZACIONES']['RETORNO']['semanas']=0;  }
					$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['CARGOS']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO']['CARGOS'];

				$_SESSION['AUTORIZACIONES']['RETORNO']['observacion_ingreso']=$_SESSION['AUTORIZACIONES']['observacion_ingreso'];
				$_SESSION['AUTORIZACIONES']['RETORNO']['TIPO_SERVICIO']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'];
				$_SESSION['AUTORIZACIONES']['RETORNO']['TIPO_AUTORIZACION']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION'];
				$Contenedor=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
				$Modulo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
				$Tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
				$Metodo=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
				$argu=$_SESSION['AUTORIZACIONES']['RETORNO']['argumentos'];

if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'])
	AND empty($_SESSION['AUTORIZACIONES']['RETORNO']['modulo'])
	AND empty($_SESSION['AUTORIZACIONES']['RETORNO']['metodo'])
	AND empty($_SESSION['AUTORIZACIONES']['RETORNO']['tipo']))
	{
		$Contenedor=$_SESSION['AUTORIZACIONES1']['RETORNO']['contenedor'];
		$Modulo=$_SESSION['AUTORIZACIONES1']['RETORNO']['modulo'];
		$Tipo=$_SESSION['AUTORIZACIONES1']['RETORNO']['tipo'];
		$Metodo=$_SESSION['AUTORIZACIONES1']['RETORNO']['metodo'];
	}
				if(empty($Contenedor) || empty($Modulo) || empty($Tipo) || empty($Metodo))
				{
								$this->error = "AUTORIZACION ";
								$this->mensajeDeError = "LOS DATOS DE RETORNO DE LA AUTORIZACIÓN NO SON CORRECTOS.";
								return false;
				}
				unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']);

				if($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion'] && $_SESSION['AUTORIZACIONES']['RETORNO']['Codigo']!='SOAT')
				{
							/* $accion=ModuloGetURL($Contenedor,$Modulo,$Tipo,$Metodo,$argu);
								$mensaje='El Número de Autorizacion es '.$NumAutorizacion;
								$this->FormaMensaje($mensaje,'AUTORIZACION',$accion);
								return true;*/
								$this->ReturnMetodoExterno($Contenedor,$Modulo,$Tipo,$Metodo,$argu);
								return true;
				}
				else
				{
								$this->ReturnMetodoExterno($Contenedor,$Modulo,$Tipo,$Metodo,$argu);
								return true;
				}
    }

    /**
    *
    */
    function AutorizarAdmisionAmbulatoria()
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT sw_tipo_plan, sw_afiliacion, protocolos, sw_autoriza_sin_bd
											FROM planes
											WHERE estado='1' and plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
											and fecha_final >= now() and fecha_inicio <= now()";
            $results = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if ($dbconn->EOF) {
                    $this->RetornarAutorizacion(false,'','EL PLAN NO EXISTE, NO TIENE VIGENCIA, O NO ESTA ACTIVO',0);
                    return true;
            }

            list($TipoPlan,$swAfiliados,$Protocolos,$swAutoSinBD)=$results->FetchRow();
            $_SESSION['AUTORIZACIONES']['RETORNO']['Protocolos']=$Protocolos;
            $_SESSION['AUTORIZACIONES']['RETORNO']['sw_autorizacion_sin_bd']=$swAutoSinBD;


            switch($TipoPlan)
            {
                    //cliente
                    case 0:
                    return true;

                    break;

                    //soat
                    case 1:
                    break;

                    //particular
                    case 2:
                    break;

                    //capitado
                    case 3:
                    break;

                    default:
                            $this->error = "AUTORIZACION";
                            $this->mensajeDeError = "TIPO PLAN INCORRECTO, REVISAR LA INTEGRIDAD DE LA BASE DE DATOS";
                            return false;
            }
        return true;
    }


    /**
    *
    */
    function AutorizarAdmisionUrgencias()
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT sw_tipo_plan, sw_afiliacion, protocolos, sw_autoriza_sin_bd
                                FROM planes
                                WHERE estado='1' and plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
                                and fecha_final >= now() and fecha_inicio <= now()";
            $results = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if ($dbconn->EOF) {
                    $this->RetornarAutorizacion(false,'','EL PLAN NO EXISTE, NO TIENE VIGENCIA, O NO ESTA ACTIVO',0);
                    return true;
            }

            list($TipoPlan,$swAfiliados,$Protocolos,$swAutoSinBD)=$results->FetchRow();
            $_SESSION['AUTORIZACIONES']['RETORNO']['Protocolos']=$Protocolos;
            $_SESSION['AUTORIZACIONES']['RETORNO']['sw_autorizacion_sin_bd']=$swAutoSinBD;
            switch($TipoPlan)
            {
                    //cliente
                    case 0:
                    $this->AplicarAutorizacionesUrgencias($TipoPlan,$swAfiliados);
                    break;

                    //soat, hace de una la autorizacion
                    case 1:
                            $this->AplicarAutorizacionesSoat();
                    break;

                    //particular
                    case 2:
                    $this->AplicarAutorizacionesUrgencias($TipoPlan,$swAfiliados);
                    break;

                    //capitado
                    case 3:
                    $this->AplicarAutorizacionesUrgencias($TipoPlan,$swAfiliados);
                    break;

                    default:
                            $this->error = "AUTORIZACION";
                            $this->mensajeDeError = "TIPO PLAN INCORRECTO, REVISAR LA INTEGRIDAD DE LA BASE DE DATOS";
                            return false;
            }
        return true;
    }

    /**
    *
    */
    function AutorizarAdmisionHospitalizacion()
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT sw_tipo_plan, sw_afiliacion, protocolos, sw_autoriza_sin_bd
                                FROM planes
                                WHERE estado='1' and plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
                                and fecha_final >= now() and fecha_inicio <= now()";
            $results = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if ($dbconn->EOF) {
                    $this->RetornarAutorizacion(false,'','EL PLAN NO EXISTE, NO TIENE VIGENCIA, O NO ESTA ACTIVO',0);
                    return true;
            }

            list($TipoPlan,$swAfiliados,$Protocolos,$swAutoSinBD)=$results->FetchRow();
            $_SESSION['AUTORIZACIONES']['RETORNO']['Protocolos']=$Protocolos;
            $_SESSION['AUTORIZACIONES']['RETORNO']['sw_autorizacion_sin_bd']=$swAutoSinBD;

            switch($TipoPlan)
            {
                    //cliente
                    case 0:
                    $this->AplicarAutorizaciones($TipoPlan,$swAfiliados);
                    break;

                    //soat, retorna codigo=SOAT (no hace ningun tipo de autorizacion)
                    case 1:
														$query="SELECT tipo_afiliado_id,rango
																						FROM planes_rangos
																						WHERE plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'";
														$result=$dbconn->Execute($query);
														if(!$result->EOF){
																$dat=$result->GetRowAssoc($ToUpper = false);
														}
														else
														{
																$c=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
																$mo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
																$me=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
																$tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
																$accion=ModuloGetURL($c,$mo,$tipo,$me);
																$mensaje='ERROR: EL RANGO Y TIPO DE AFILIADO NO ESTAN PRESENTES EN LA TABLA PLANES_RANGOS. COMUNIQUESE CON SISTEMAS.';
																$this->FormaMensaje($mensaje,'AUTORIZACION',$accion);
                                return true;
														}
														$result->CLose();

														$_SESSION['AUTORIZACIONES']['AFILIADO']=$dat[tipo_afiliado_id];
														$_SESSION['AUTORIZACIONES']['RANGO']=$dat[rango];
														$_SESSION['AUTORIZACIONES']['SEMANAS']=0;

                            $this->RetornarAutorizacion(true,'SOAT','Plan SOAT',1);
                            //$this->AplicarAutorizaciones($TipoPlan,$swAfiliados);
                    break;

                    //particular
                    case 2:
                    $this->AplicarAutorizaciones($TipoPlan,$swAfiliados);
                    break;

                    //capitado
                    case 3:
                    $this->AplicarAutorizaciones($TipoPlan,$swAfiliados);
                    break;

                    default:
                            $this->error = "AUTORIZACION";
                            $this->mensajeDeError = "TIPO PLAN INCORRECTO, REVISAR LA INTEGRIDAD DE LA BASE DE DATOS.";
                            return false;
            }
            return true;
    }

    /**
    *
    */
    function AplicarAutorizaciones($TipoPlan,$swAfiliados)
    {
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_plan']=$TipoPlan;

				if(($TipoPlan==0 AND $swAfiliados==1) OR ($swAfiliados==1))
				{        //funcion que busca en las bases de afiliados (en caso de plan cliente o capitado)
						$PacienteId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
						$TipoId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
						$Plan=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];
						if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
						{
								$this->error = "Error";
								$this->mensajeDeError = "NO SE PUDO INCLUIR : classes/notas_enfermeria/revision_sistemas.class.php";
								return false;
						}
						if(!class_exists('BDAfiliados'))
						{
								$this->error="Error";
								$this->mensajeDeError="NO EXISTE BD AFILIADOS";
								return false;
						}

						$class= New BDAfiliados($TipoId,$PacienteId,$Plan);
						$class->GetDatosAfiliado();
					 if($class->GetDatosAfiliado()==false)
						{
												$this->frmError["MensajeError"]=$class->mensajeDeError;
						}

						if(!empty($class->salida))
						{
								$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']=$class->salida;
								if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_urgencias']))
								{
										$_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']='EL PACIENTE SE ENCUENTRA EN LA BASE DE DATOS DE LA ENTIDAD Y ESTA EN MES DE URGENCIAS.';
								}
								else
								{
										$_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']='EL PACIENTE SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS DE LA ENTIDAD.';
								}
								$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TODO']=0;
								/*
								$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado'];
								$_SESSION['AUTORIZACIONES']['RETORNO']['rango']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel'];
								$_SESSION['AUTORIZACIONES']['RETORNO']['semanas']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas'];
								*/
								$_SESSION['AUTORIZACIONES']['AFILIADO']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado'];;
								$_SESSION['AUTORIZACIONES']['RANGO']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel'];;
								$_SESSION['AUTORIZACIONES']['SEMANAS']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas'];;

								switch($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION'])
								{
										case 'Admon':
																switch($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'])
																{
																				case 'AMBULATORIA':
																				$this->AutorizarAdmisionAmbulatoria();
																				break;
																				case 'URGENCIAS':
																				$this->ValidarDerechosUrgencias();
																				break;
																				case 'HOSPITALIZACION':
																				$this->ValidarDerechosHospitalizacion();
																				break;
																				default:
																						$this->error = "AUTORIZACION ";
																						$this->mensajeDeError = "TIPO DE SERVICIO INCORRECTO";
																						return false;
																}
										break;

										case 'Cargo':
																if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO']))
																{
																				$this->error = "AUTORIZACION ";
																				$this->mensajeDeError = "EL CARGO PARA AUTORIZAR ESTA VACIO.";
																				return false;
																}
																switch($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'])
																{
																				case 'CONSULTAEXTERNA':
																				$this->ValidarDerechosConsultaExterna();
																				break;
																				default:
																						$this->error = "AUTORIZACION ";
																						$this->mensajeDeError = "TIPO DE SERVICIO INCORRECTO";
																						return false;
																}
										break;

										default:
														$this->error = "AUTORIZACION ";
														$this->mensajeDeError = "TIPO DE AUTORIZACIÓN INCORRECTA.";
														return false;
								}
								return true;
						}
						else
						{
									if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['sw_autorizacion_sin_bd']))
									{
															$_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']='EL PACIENTE NO ENCUENTRA EN LA BASE DE DATOS DE LA ENTIDAD, NECESITA CERTIFICADO DE CARTERA.';
															$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TODO']=1;
															$c2=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
															$mo2=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
															$me2=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
															$c='app';
															$mo='Autorizacion';
															$me='FormaAutorizacion';
															$msg='EL PACIENTE NO SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS, NECESITA UNA AUTORIZACIÓN.';
															$this->ConfirmarAccion('AUTORIZAR PACIENTE',$msg,'AUTORIZAR','CANCELAR',$c,$mo,$me,$c2,$mo2,$me2);
															return true;
									}
									else
									{
													$c=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
													$mo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
													$me=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
													$tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
													$accion=ModuloGetURL($c,$mo,$tipo,$me);
													$mensaje='EL PACIENTE NO SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS DE LA ENTIDAD. NO PUEDE SER AUTORIZADO.';
													$this->FormaMensaje($mensaje,'AUTORIZACION',$accion);
													return true;
									}
						}
				}
				else
				{
							$_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']='EL PACIENTE NECESITA AUTORIZACION.';
							$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TODO']=1;
							$c2=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
							$mo2=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
							$me2=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
							$_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']='NO';
							$c='app';
							$mo='Autorizacion';
							$me='FormaAutorizacion';
							$msg='EL PACIENTE NECESITA UNA AUTORIZACIÓN.';
							$this->ConfirmarAccion('AUTORIZAR PACIENTE',$msg,'AUTORIZAR','CANCELAR',$c,$mo,$me,$c2,$mo2,$me2);
							return true;
				}
    }


		/**
		*
		*/
    function AplicarAutorizacionesUrgencias($TipoPlan,$swAfiliados)
    {
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_plan']=$TipoPlan;
				//if(($TipoPlan==0 AND $swAfiliados==1) OR ($TipoPlan==3))
				if(($TipoPlan==0 AND $swAfiliados==1) OR ($swAfiliados==1))
				{        //funcion que busca en las bases de afiliados (en caso de plan cliente o capitado)
						$PacienteId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
						$TipoId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
						$Plan=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];
						if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
						{
								$this->error = "Error";
								$this->mensajeDeError = "NO SE PUEDE INCLUIR: classes/notas_enfermeria/revision_sistemas.class.php";
								return false;
						}
						if(!class_exists('BDAfiliados'))
						{
								$this->error="Error";
								$this->mensajeDeError="NO EXISTE BD AFILIADOS";
								return false;
						}

						$class= New BDAfiliados($TipoId,$PacienteId,$Plan);
						$class->GetDatosAfiliado();
					 	if($class->GetDatosAfiliado()==false)
						{
												$this->frmError["MensajeError"]=$class->mensajeDeError;
						}

						if(!empty($class->salida))
						{
								$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']=$class->salida;
								if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_urgencias']))
								{
										$_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']='EL PACIENTE SE ENCUENTRA EN LA BASE DE DATOS DE LA ENTIDAD Y ESTA EN MES DE URGENCIAS.';
								}
								else
								{
										$_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']='EL PACIENTE SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS DE LA ENTIDAD.';
								}
								$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TODO']=0;
								/*
								$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado'];
								$_SESSION['AUTORIZACIONES']['RETORNO']['rango']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel'];
								$_SESSION['AUTORIZACIONES']['RETORNO']['semanas']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas'];
								*/
								$_SESSION['AUTORIZACIONES']['AFILIADO']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado'];;
								$_SESSION['AUTORIZACIONES']['RANGO']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel'];;
								$_SESSION['AUTORIZACIONES']['SEMANAS']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas'];;
								switch($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION'])
								{
										case 'Admon':
																switch($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'])
																{
																				case 'AMBULATORIA':
																				$this->AutorizarAdmisionAmbulatoria();
																				break;
																				case 'URGENCIAS':
																				$this->ValidarDerechosUrgencias();
																				break;
																				case 'HOSPITALIZACION':
																				$this->ValidarDerechosHospitalizacion();
																				break;
																				default:
																						$this->error = "AUTORIZACION ";
																						$this->mensajeDeError = "TIPO DE SERVICIO INCORRECTO.";
																						return false;
																}
										break;

										case 'Cargo':
																if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO']))
																{
																				$this->error = "AUTORIZACION ";
																				$this->mensajeDeError = "EL CARGO PARA AUTORIZAR ESTA VACIO.";
																				return false;
																}
																switch($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'])
																{
																				case 'CONSULTAEXTERNA':
																				$this->ValidarDerechosConsultaExterna();
																				break;
																				default:
																						$this->error = "AUTORIZACION ";
																						$this->mensajeDeError = "TIPO DE SERVICIO INCORRECTO.";
																						return false;
																}
										break;

										default:
														$this->error = "AUTORIZACION ";
														$this->mensajeDeError = "TIPO DE AUTORIZACIÓN INCORRECTA.";
														return false;
								}
								return true;
						}
						else
						{
									if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['sw_autorizacion_sin_bd']))
									{
															$_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']='EL PACIENTE NO ENCUENTRA EN LA BASE DE DATOS DE LA ENTIDAD, NECESITA CERTIFICADO DE CARTERA.';
															$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TODO']=1;
															$c2=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
															$mo2=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
															$me2=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
															$c='app';
															$mo='Autorizacion';
															$me='FormaAutorizacion';
															$msg='EL PACIENTE NO SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS, NECESITA AUTORIZACIÓN.';
															$this->ConfirmarAccion('AUTORIZAR PACIENTE',$msg,'AUTORIZAR','CANCELAR',$c,$mo,$me,$c2,$mo2,$me2);
															return true;
									}
									else
									{
													$c=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
													$mo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
													$me=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
													$tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
													$accion=ModuloGetURL($c,$mo,$tipo,$me);
													$mensaje='EL PACIENTE NO SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS DE LA ENTIDAD. NO PUEDE SER AUTORIZADO.';
													$this->FormaMensaje($mensaje,'AUTORIZACION',$accion);
													return true;
									}
						}
				}
				else
				{
						$this->RetornarAutorizacion(true,'ADMITIR','',1);
						return true;
				}
    }


    /*
    *
    */
    function ValidarDerechosUrgencias()
    {
            if($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_activo']==1)
            {        //si esta activo no es necesario entrar a la forma de autorizacion, se muestra
                    //una ventana de confirmacion para ver si quiere hacer una autorizacion o continuar
                    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']='EL PACIENTE NO NECESITA AUTORIZACION SE ENCUENTA EN LA BASE DE DATOS DE LA ENTIDAD.';
                    $c='app';
                    $mo='Autorizacion';
                    $me='FormaAutorizacion';
                    $c2='app';
                    $mo2='Autorizacion';
                    $me2='ContinuarAutorizacion';
                    $msg='EL PACIENTE NO NECESITA AUTORIZACIÓN PARA CONTINUAR, SI DESEA REALIZAR ALGÚN TIPO DE AUTORIZACIÓN PRESIONE EN AUTORIZAR.';
                    $this->ConfirmarAccion('AUTORIZAR PACIENTE',$msg,'AUTORIZAR','CONTINUAR',$c,$mo,$me,$c2,$mo2,$me2);
                    return true;
            }
            else
            {
                    $this->frmError["MensajeError"]="EL PACIENTE NO ESTA ACTIVO EN LA BASE DE DATOS.";
                    $this->FormaAutorizacion();
                    return true;
            }
    }


    /*
    *
    */
    function ValidarDerechosHospitalizacion()
    {
            $this->frmError["MensajeError"]="EL PACIENTE NECESITA AUTORIZACIÓN PARA LA HOSPITALIZACIÓN.";
            $this->FormaAutorizacion();
            return true;
    }

    /*
    *
    */
    function ValidarDerechosConsultaExterna()
    {
				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();
				//--------------------valida si no necesita autorizacion-------------------------
				$query = "select autorizacion_cargo_cups_int(".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'].",'".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CUPS']."','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']."')";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				if($result->fields[0]!='NoRequiere')
				{
						$msg .='<BR>EL CARGO NECESITA AUTORIZACION INTERNA';
						$autoInt=1;
				}
				$queyr = "select autorizacion_cargo_cups_ext(".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'].",'".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CUPS']."','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']."')";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				if($result->fields[0]!='NoRequiere')
				{
						//$msg .='<BR>EL CARGO NECESITA AUTORIZACION EXTERNA';
						$autoExt=1;
				}
				//------------------fin validacion de autorizacion--------------------



				$activo=1;
				if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']) AND $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_activo']!=1)
				{  $activo=0;   }


				//necesita
				if(($autoExt==1 OR $autoInt==1) OR $activo==0)
				{
						$Autorizacion=$this->NextValAutorizacion();
						$query = "INSERT INTO autorizaciones(
																autorizacion,
																fecha_autorizacion,
																observaciones,
																usuario_id,
																fecha_registro,
																sw_estado,
																ingreso)
																VALUES ($Autorizacion,'now()','',".UserGetUID().",'now()',0,NULL)";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error INSERT INTO autorizaciones";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
						}

						$k=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TARIFARIO'];
						$cod=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO'];
						$query = "INSERT INTO  autorizaciones_ingreso_cargos
																					(autorizacion,
																					tarifario_id,
																					cargo,
																					servicio,
																					cantidad)
						VALUES($Autorizacion,'$k','$cod','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']."',1)";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en la Base de Datos";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
										return false;
						}
				 }
				else
				{		//no necesita entonces es la del sistema 1
						$Autorizacion=1;
				}

				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']=$Autorizacion;

				//necesita autorizacion
				if(($autoExt==1 OR $autoInt==1) OR $activo==0)
				{
						$dbconn->CommitTrans();
						$this->frmError["MensajeError"]="EL PACIENTE NECESITA AUTORIZACIÓN PARA LA CONSULTA EXTERNA.".$msg;
						$this->FormaAutorizacion();
						return true;
				}
				else
				{		//no necesita autorizacion
						if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
						{
								if($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_activo']==0)
								{
												$this->frmError["MensajeError"]="EL PACIENTE NECESITA AUTORIZACIÓN PARA LA CONSULTA EXTERNA. ESTA INACTIVO EN LA BASE DE DATOS";
												$this->FormaAutorizacion();
												return true;
								}
								else
								{
										$this->FormaAfiliado();
										return true;
								}
						}
						$this->FormaAfiliado();
						return true;
				}
    }

		function ContinuarConsultaExt()
		{
				unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['orden_servicio_id']);

				$this->FormaAutorizacion();
				return true;
		}


    /**
    *
    */
    function AutorizarCargoConsultaExterna()
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT sw_tipo_plan, sw_afiliacion, protocolos, sw_autoriza_sin_bd
											FROM planes
											WHERE estado='1' and plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
											and fecha_final >= now() and fecha_inicio <= now()";
            $results = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if ($dbconn->EOF) {
                    $this->RetornarAutorizacion(false,'','EL PLAN NO EXISTE, NO TIENE VIGENCIA, O NO ESTA ACTIVO',0);
                    return true;
            }
										$_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']=false;

            list($TipoPlan,$swAfiliados,$Protocolos,$swAutoSinBD)=$results->FetchRow();
            $_SESSION['AUTORIZACIONES']['RETORNO']['Protocolos']=$Protocolos;
            $_SESSION['AUTORIZACIONES']['RETORNO']['sw_autorizacion_sin_bd']=$swAutoSinBD;

						//cumplimiento de cita quiere decir q es el cumplimiento de una cita
						if(!empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['orden_servicio_id']))
						{
								$query="SELECT b.autorizacion_ext, b.autorizacion_int,
												b.tipo_afiliado_id, b.rango, b.semanas_cotizadas
												FROM os_ordenes_servicios as b
												WHERE b.orden_servicio_id=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['orden_servicio_id']."";

								$results=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;
									return false;
								}

								if($results->fields[0]>100 OR $results->fields[1]>100)
								{		//quiere decir q hay una autorizacion
										$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id']=$results->fields[2];
										$_SESSION['AUTORIZACIONES']['RETORNO']['rango']=$results->fields[3];
										$_SESSION['AUTORIZACIONES']['RETORNO']['semanas']=$results->fields[4];
										$_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']=true;
										$_SESSION['AUTORIZACIONES']['RETORNO']['plan_id']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];

										$query = "select  b.*, g.nombre
															from autorizaciones as b, system_usuarios as g
															where b.autorizacion=".$results->fields[1]." and b.usuario_id=g.usuario_id";
										$result=$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$this->fileError = __FILE__;
											$this->lineError = __LINE__;
											return false;
										}

										while(!$result->EOF)
										{
												$var[]=$result->GetRowAssoc($ToUpper = false);
												$result->MoveNext();
										}
										$result->Close();
										$this->FormaAutorizacionExistentes($var);
										return true;
								}
								$results->Close();
						}
						//fin cumplimiento de cita



            switch($TipoPlan)
            {
                    //cliente
                    case 0:
                    $this->AplicarAutorizaciones($TipoPlan,$swAfiliados);
                    break;

                    //soat, retorna codigo=SOAT (no hace ningun tipo de autorizacion)
                    case 1:
														$query="SELECT tipo_afiliado_id,rango
																						FROM planes_rangos
																						WHERE plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'";
														$result=$dbconn->Execute($query);
														if(!$result->EOF){
																$dat=$result->GetRowAssoc($ToUpper = false);
														}
														else
														{
																$c=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
																$mo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
																$me=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
																$tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
																$accion=ModuloGetURL($c,$mo,$tipo,$me);
																$mensaje='ERROR: EL RANGO Y EL TIPO DE AFILIADO NO ESTAN PRESENTES EN LA PLANES_RANGOS. COMUNIQUESE CON SISTEMAS.';
																$this->FormaMensaje($mensaje,'AUTORIZACION',$accion);
                                return true;
														}
														$result->CLose();

														$_SESSION['AUTORIZACIONES']['AFILIADO']=$dat[tipo_afiliado_id];
														$_SESSION['AUTORIZACIONES']['RANGO']=$dat[rango];
														$_SESSION['AUTORIZACIONES']['SEMANAS']=0;
                            $this->RetornarAutorizacion(true,'SOAT','Plan SOAT',1);
                    break;

                    //particular
                    case 2:
                    $this->AplicarAutorizaciones($TipoPlan,$swAfiliados);
                    break;

                    //capitado
                    case 3:
                    $this->AplicarAutorizaciones($TipoPlan,$swAfiliados);
                    break;

                    default:
                            $this->error = "AUTORIZACION";
                            $this->mensajeDeError = "TIPO PLAN INCORRECTO, REVISAR LA INTEGRIDAD DE LOS DATOS.";
                            return false;
            }
        return true;
    }


    /**
    *
    */
    function AplicarAutorizacionesSoat()
 		{
 						$Autorizacion=$this->NextValAutorizacion();
            list($dbconn) = GetDBconn();
            /*$query="SELECT nextval('autorizaciones_autorizacion_seq')";
            $result=$dbconn->Execute($query);
            $Autorizacion=$result->fields[0];*/
            $query = "INSERT INTO autorizaciones(
																		autorizacion,
																		fecha_autorizacion,
																		observaciones,
																		usuario_id,
																		fecha_registro,
																		sw_estado,
																		ingreso)
											VALUES ($Autorizacion,now(),'',".UserGetUID().",now(),0,NULL)";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                        $this->RetornarAutorizacion(false,'ADMITIR','ERROR',$Autorizacion);
                        return true;
            }
            else
            {
                        $this->RetornarAutorizacion(true,'ADMITIR','',$Autorizacion);
                        return true;
            }
    }


    /**
    *
    */
    function ContinuarAutorizacion()
    {
          $_SESSION['AUTORIZACIONES']['AFILIADO']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado'];
          $_SESSION['AUTORIZACIONES']['RANGO']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel'];
          $_SESSION['AUTORIZACIONES']['SEMANAS']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas'];

          $auto=$this->AutorizacionBD();
          if($auto)
          {
										$this->RetornarAutorizacion(true,'ADMITIR','',$auto);
										return true;
          }
          else
          {
										$this->RetornarAutorizacion(false,'ADMITIR','ERROR',$auto);
										return true;
          }
    }


   /**
    *
    */
   /* function AutorizarCargos()
    {
            if(empty($_SESSION['AUTORIZACIONES']))
             {
                    $this->error = "AUTORIZACION NULA";
                    $this->mensajeDeError = "DATOS DE LA AUTORIZACIÓN VACIOS.";
                    return false;
             }

            if(empty($_SESSION['AUTORIZACIONES']['RETORNO']))
             {
                    $this->error = "AUTORIZACION ";
                    $this->mensajeDeError = "EL RETORNO DE LA AUTORIZACIÓN ESTA VACIO.";
                    return false;
             }

            if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO']['CARGOS']))
             {
                    $this->error = "AUTORIZACION ";
                    $this->mensajeDeError = "LA SOLICITUD DE AUTORIZACIÓN ESTA VACIA.";
                    return false;
             }

						$Contenedor=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
						$Modulo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
						$Tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
						$Metodo=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];

						if(empty($Contenedor) || empty($Modulo) || empty($Tipo) || empty($Metodo))
						{
										$this->error = "AUTORIZACION ";
										$this->mensajeDeError = "LOS DATOS DE RETORNO DE LA AUTORIZACIÓN NO SON CORRECTOS.";
										return false;
						}

            $PlanId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];
            $TipoId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
            $PacienteId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];

            if(empty($PlanId) || empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['cantidad']))
            {
                    $this->error = "AUTORIZACION ";
                    $this->mensajeDeError = "DATOS DE LA AUTORIZACIÓN INCOMPLETOS.";
                    return false;
            }

            list($dbconn) = GetDBconn();
            $query = "select protocolos from planes
                                where plan_id='$PlanId'";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error select protocolos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['protocolo']=$result->fields[0];
            $result->Close();
							//para traer datos de la bd
							$query = "SELECT sw_tipo_plan, sw_afiliacion, protocolos, sw_autoriza_sin_bd
																	FROM planes
																	WHERE estado='1' and plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
																	and fecha_final >= now() and fecha_inicio <= now()";
							$results = $dbconn->Execute($query);

							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
							if ($dbconn->EOF) {
											$this->RetornarAutorizacion(false,'','EL PLAN NO EXISTE, NO TIENE VIGENCIA, O NO ESTA ACTIVO',0);
											return true;
							}

							list($TipoPlan,$swAfiliados,$Protocolos,$swAutoSinBD)=$results->FetchRow();

							$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_plan']=$TipoPlan;
							//if(($TipoPlan==0 AND $swAfiliados==1) OR ($TipoPlan==3))
							if(($TipoPlan==0 AND $swAfiliados==1) OR ($swAfiliados==1))
							{
									if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
									{
											$this->error = "Error";
											$this->mensajeDeError = "NO SE PUDO INCLUIR : classes/notas_enfermeria/revision_sistemas.class.php";
											return false;
									}
									if(!class_exists('BDAfiliados'))
									{
											$this->error="Error";
											$this->mensajeDeError="NO EXISTE BD AFILIADOS";
											return false;
									}

									$class= New BDAfiliados($TipoId,$PacienteId,$PlanId);
									$class->GetDatosAfiliado();
									 if($class->GetDatosAfiliado()==false)
									{
												$this->frmError["MensajeError"]=$class->mensajeDeError;
									}

									if(!empty($class->salida))
									{
												unset($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
												$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']=$class->salida;
									}
							}

								$FechaRegistro=date("Y-m-d H:i:s");
								$SystemId=UserGetUID();
								$Autorizacion=$this->NextValAutorizacion();
								list($dbconn) = GetDBconn();
								$query = "INSERT INTO autorizaciones(
																										autorizacion,
																										fecha_autorizacion,
																										observaciones,
																										usuario_id,
																										fecha_registro,
																										sw_estado,
																										ingreso)
																		VALUES ($Autorizacion,'$FechaRegistro','',$SystemId,'$FechaRegistro',0,NULL)";
								$dbconn->BeginTrans();
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error INSERT INTO autorizaciones";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$dbconn->RollbackTrans();
												return false;
								}
								else
								{
										//para sacar el mensaje de cada cargo
										for($i=0; $i<sizeof($_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO']['CARGOS']); $i++)
										{
													$query = "select autorizacion_cobertura('".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO']['CARGOS'][$i]['tarifario']."','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO']['CARGOS'][$i]['cargo']."','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']."')";
																					$result = $dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0) {
															$this->error = "Error select autorizacion_cobertura";
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															return false;
													}

													if($result->fields[0]!='NoRequiere')
													{
																	$_SESSION['AUTORIZACIONES']['RETORNO']['ACCIONAUTO']='NOCUBRE';
																	$msg.="El Plan No Cubre el Cargo ".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO'][$i]['descripcion'].'<br>';
													}

													$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']=$Autorizacion;
													$k=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO']['CARGOS'][$i]['tarifario'];
													$cod=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO']['CARGOS'][$i]['cargo'];
													$query = "INSERT INTO  autorizaciones_ingreso_cargos
																																			(autorizacion,
																																			tarifario_id,
																																			cargo,
																																			servicio,
																																			cantidad)
													VALUES(".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'].",'$k','$cod','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']."',1)";
													$dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0) {
																	$this->error = "Error INSERT INTO  autorizaciones_ingreso_cargos";
																	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																	$dbconn->RollbackTrans();
																	return false;
													}
										}//fin for
								}

							$dbconn->CommitTrans();
							unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']);
							$_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']=$msg;
							$_SESSION['AUTORIZACIONES']['RETORNO']['ACCIONAUTO']='REQUIERE';
							$this->frmError["MensajeError"]="SE NECESITA AUTORIZACIÓN PARA LOS CARGOS.";
							$this->FormaAutorizacion();
							return true;
		}*/


    /**
    *
    */
    /*function AutorizarCargo()
    {
            if(empty($_SESSION['AUTORIZACIONES']))
             {
                    $this->error = "AUTORIZACION NULA";
                    $this->mensajeDeError = "DATOS DE LA AUTORIZACIÓN VACIOS.";
                    return false;
             }

            if(empty($_SESSION['AUTORIZACIONES']['RETORNO']))
             {
                    $this->error = "AUTORIZACION ";
                    $this->mensajeDeError = "EL RETORNO DE LA AUTORIZACIÓN ESTA VACIO.";
                    return false;
             }

            if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']))
             {
                    $this->error = "AUTORIZACION ";
                    $this->mensajeDeError = "LA SOLICITUD DE LA AUTORIZACIÓN ESTA VACIA.";
                    return false;
             }

							$Contenedor=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
							$Modulo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
							$Tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
							$Metodo=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];

							if(empty($Contenedor) || empty($Modulo) || empty($Tipo) || empty($Metodo))
							{
											$this->error = "AUTORIZACION ";
											$this->mensajeDeError = "LOS DATOS DE RETORNO DE LA AUTORIZACIÓN NO SON CORRECTOS.";
											return false;
							}

            $PlanId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];

            if(empty($PlanId) || empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['cantidad']))
            {
                    $this->error = "AUTORIZACION ";
                    $this->mensajeDeError = "DATOS DE LA AUTORIZACIÓN IMCOMPLETOS.";
                    return false;
            }

            if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO']) || empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TARIFARIO']))
            {
                    $this->error = "AUTORIZACION ";
                    $this->mensajeDeError = "EL CARGO PARA AUTORIZAR ESTA VACIO.";
                    return false;
            }

            list($dbconn) = GetDBconn();
            $query = "select protocolos from planes
                                where plan_id='$PlanId'";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error select protocolos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['protocolo']=$result->fields[0];
            $result->Close();
						
							//para traer datos de la bd
							$query = "SELECT sw_tipo_plan, sw_afiliacion, protocolos, sw_autoriza_sin_bd
																	FROM planes
																	WHERE estado='1' and plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
																	and fecha_final >= now() and fecha_inicio <= now()";
							$results = $dbconn->Execute($query);

							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
							if ($dbconn->EOF) {
											$this->RetornarAutorizacion(false,'','EL PLAN NO EXISTE, NO TIENE VIGENCIA, O NO ESTA ACTIVO',0);
											return true;
							}

							list($TipoPlan,$swAfiliados,$Protocolos,$swAutoSinBD)=$results->FetchRow();

							$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_plan']=$TipoPlan;
							//if(($TipoPlan==0 AND $swAfiliados==1) OR ($TipoPlan==3))
							if(($TipoPlan==0 AND $swAfiliados==1) OR ($swAfiliados==1))
							{
									if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
									{
											$this->error = "Error";
											$this->mensajeDeError = "NO SE PUDO INCLUIR : classes/notas_enfermeria/revision_sistemas.class.php";
											return false;
									}
									if(!class_exists('BDAfiliados'))
									{
											$this->error="Error";
											$this->mensajeDeError="NO EXISTE BD AFILIADOS";
											return false;
									}

									$class= New BDAfiliados($TipoId,$PacienteId,$PlanId);
									$class->GetDatosAfiliado();
									 if($class->GetDatosAfiliado()==false)
									{
												$this->frmError["MensajeError"]=$class->mensajeDeError;
									}

									if(!empty($class->salida))
									{
												unset($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
												$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']=$class->salida;
									}
							}
						

            $query = "select autorizacion_cobertura('".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TARIFARIO']."','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO']."','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']."')";
                            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error select autorizacion_cobertura";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            else
            {
                    if($result->fields[0]=='NoRequiere')
                    {
                            if($result->fields[0]=='NoRequiere')
                            {
                                        $_SESSION['AUTORIZACIONES']['RETORNO']['ACCIONAUTO']='NOREQUIERE';
                                        $msg="El Cargo ".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO'].' no requiere Autorización.';
                                        $this->RetornarAutorizacion(false,'ADMITIR',$msg,0);
                                        return true;
                            }
                    }
                    else
                    {//si requiere autorizacion el cargo

                                if($result->fields[0]=='NoCobertura' || $result->fields[0]=='NULL')
                                {
                                        $_SESSION['AUTORIZACIONES']['RETORNO']['ACCIONAUTO']='NOCUBRE';
                                        $msg="El Plan No Cubre el Cargo ".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO'];
                                }

                                $FechaRegistro=date("Y-m-d H:i:s");
                                $SystemId=UserGetUID();
																$Autorizacion=$this->NextValAutorizacion();
                                list($dbconn) = GetDBconn();
                                $query = "INSERT INTO autorizaciones(
																									autorizacion,
																									fecha_autorizacion,
																									observaciones,
																									usuario_id,
																									fecha_registro,
																									sw_estado,
																									ingreso)
                                                    VALUES ($Autorizacion,'$FechaRegistro','',$SystemId,'$FechaRegistro',0,NULL)";
                                $dbconn->BeginTrans();
                                $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error INSERT INTO autorizaciones";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                }
                                else
                                {
                                            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']=$Autorizacion;
                                            $k=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TARIFARIO'];
                                            $cod=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO'];
                                            $query = "INSERT INTO  autorizaciones_ingreso_cargos
                                                                                                                                        (autorizacion,
                                                                                                                                        tarifario_id,
                                                                                                                                        cargo,
                                                                                                                                        servicio,
                                                                                                                                        cantidad)
                                            VALUES(".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'].",'$k','$cod','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']."',1)";
                                            $dbconn->Execute($query);
                                            if ($dbconn->ErrorNo() != 0) {
                                                    $this->error = "Error INSERT INTO  autorizaciones_ingreso_cargos";
                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                    $dbconn->RollbackTrans();
                                                    return false;
                                            }
                                            else
                                            {
                                                        $dbconn->CommitTrans();
                                                        $_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']=$msg;
                                                        $_SESSION['AUTORIZACIONES']['RETORNO']['ACCIONAUTO']='REQUIERE';
                                                        $this->frmError["MensajeError"]="SE NECESITA AUTORIZACIÓN PARA EL CARGO.";
                                                        $this->FormaAutorizacion();
                                                        return true;
                                            }
                                }
                    }
            }//fin de que salio bien el query de la cobertura del cargo
    }*/

    /**
    *
    */
    function TiposAuto()
    {
                list($dbconn) = GetDBconn();
                $query = " SELECT tipo_autorizacion,descripcion FROM tipos_autorizacion
                                     WHERE tipo_autorizacion not in(3)";
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }

                while(!$resulta->EOF)
                {
                        $vars[]=$resulta->GetRowAssoc($ToUpper = false);
                        $resulta->MoveNext();
                }
                $resulta->Close();
                return $vars;
    }

  /**
     * Llama la forma ConfirmarAccion (forma de mensaje de dos botones).
   * @ access public
     * @ return boolean
     */
    function ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$c,$mo,$me,$c2,$mo2,$me2)
    {
                $arreglo=array('Plan'=>$Plan);
                $this->salida=ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,array($c,$mo,'user',$me,$arreglo),array($c2,$mo2,'user',$me2,$arreglo));
              return true;
    }
//----------------------------------------------------------------------------------

    /**
    *
    */
    function ValidarAutorizacion($Tipo,$CodAuto,$Responsable,$Validez)
    {
                if($Tipo=='1')
                {
                            if(!$Responsable){
                                        if(!$Responsable){ $this->frmError["Responsable"]=1; }
                                        $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
                                        return false;
                            }
                            return true;
                }
                if($Tipo=='2')
                {
                            if(!$Validez){
                                        if(!$Validez){ $this->frmError["Validez"]=1; }
                                        $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
                                        return false;
                            }
                            else
                            {
                                $paso=$this->ValidarFecha($Validez);
                                if(empty($paso))
                                {
                                    $this->frmError["Validez"]=1;
                                    $this->frmError["MensajeError"]="FORMATO DE FECHA INCORRECTO.";
                                    return false;
                                }
                            }
                            return true;
                }
                if($Tipo=='4')
                {
                            if(!$Responsable){
                                        if(!$Responsable){ $this->frmError["Responsable"]=1; }
                                        $this->frmError["MensajeError"]="DEBE ELEGIR USUARIO.";
                                        return false;
                            }
                            return true;
                }

                if($Tipo=='5')
                {
                            if(!$Validez){
                                        if(!$Validez){ $this->frmError["Validez"]=1; }
                                        $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
                                        return false;
                            }
                            return true;
                }
                if($Tipo=='6')
                {
                            if(!$Validez){
                                        if(!$Validez){ $this->frmError["Validez"]=1; }
                                        $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
                                        return false;
                            }
                            else
                            {
                                $paso=$this->ValidarFecha($Validez);
                                if(empty($paso))
                                {
                                    $this->frmError["Validez"]=1;
                                    $this->frmError["MensajeError"]="FORMATO DE FECHA INCORRECTO.";
                                    return false;
                                }
                            }
                            if(!$Responsable){
                                        if(!$Responsable){ $this->frmError["Responsable"]=1; }
                                        $this->frmError["MensajeError"]="DEBE ESCRIBIR EL NOMBRE DEL USUARIO.";
                                        return false;
                            }						
                            if(!$CodAuto){
                                        if(!$CodAuto){ $this->frmError["CodAuto"]=1; }
                                        $this->frmError["MensajeError"]="DEBE DIGITAR EL NÚMERO DE AUTORIZACIÓN DEL CERTIFICADO.";
                                        return false;
                            }																						
                            return true;
                }
								//cambio para sos
								if($Tipo=='7')
                {
                            if(!$Validez){
                                        if(!$Validez){ $this->frmError["Validez"]=1; }
                                        $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
                                        return false;
                            }
														if($CodAuto=='' or $CodAuto==='0'){
                                        $this->frmError["CodAuto"]=1;
                                        $this->frmError["MensajeError"]="DEBE SOLICITAR EL NÚMERO DE AUTORIZACIÓN DEL CERTIFICADO.";
                                        return false;
                            }
                            return true;
                }
								//fin cambio sos
    }

  /**
  *
  */
  function ValidarFecha($fecha)
  {
      $x=explode("-",$fecha);
      if(strlen ($x[0])!=4 OR is_numeric($x[0])==0)
      {
          $this->frmError["MensajeError"]="FORMATO DE FECHA INCORRECTO ";
          return false;
      }
      if(strlen ($x[1])>2 OR is_numeric($x[1])==0 OR $x[1]==0)
      {
          $this->frmError["MensajeError"]="FORMATO DE FECHA INCORRECTO ";
          return false;
      }
      if(strlen ($x[2])>2 OR is_numeric($x[2])==0 OR $x[1]==0)
      {
          $this->frmError["MensajeError"]="FORMATO DE FECHA INCORRECTO ";
          return false;
      }
      return true;
  }

    /**
    *
    */
    function BuscarServicios()
    {
            list($dbconn) = GetDBconn();
            $query = " SELECT servicio, descripcion FROM servicios WHERE sw_asistencial=1";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            while(!$result->EOF)
            {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }
            $result->Close();
            return $var;
    }

    /**
    *
    */
    function BuscarNivelesAtencion()
    {
            list($dbconn) = GetDBconn();
            $query = " SELECT nivel,descripcion,descripcion_corta FROM niveles_atencion";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            while(!$result->EOF)
            {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }
            $result->Close();
            return $var;
    }


    /**
    *
    */
    function AutorizacionBD()
    {
								$Fecha=date("Y-m-d H:i:s");
                $SystemId=UserGetUID();
								$Autorizacion=$this->NextValAutorizacion();
                list($dbconn) = GetDBconn();
                /*$query="SELECT nextval('autorizaciones_autorizacion_seq')";
                $result=$dbconn->Execute($query);
                $Autorizacion=$result->fields[0];*/
                $query = "INSERT INTO autorizaciones(
																									autorizacion,
																									fecha_autorizacion,
																									observaciones,
																									usuario_id,
																									fecha_registro,
																									sw_estado)
                                    VALUES ($Autorizacion,'$Fecha','$Observaciones',$SystemId,'$Fecha',1)";
                $dbconn->BeginTrans();
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Tabal autorizaiones";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
                else
                {
                            $Registro=ImplodeArrayAssoc($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
                            $query = "INSERT INTO autorizaciones_bd(
                                                                                    autorizacion,
                                                                                    registro)
                                                VALUES ($Autorizacion,'$Registro')";
                            $result=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al guardar en autorizaciones";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                            }
                            if($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']=='CONSULTAEXTERNA')
                            {
                                                $query = "INSERT INTO  autorizaciones_ingreso_cargos
																																											(autorizacion,
																																											tarifario_id,
																																											cargo,
																																											servicio,
																																											cantidad)
                                                VALUES($Autorizacion,'".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TARIFARIO']."','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO']."','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']."',1)";
                                        $dbconn->Execute($query);
                                        if ($dbconn->ErrorNo() != 0) {
                                                $this->error = "Error al Guardar en la Base de Datos";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                $dbconn->RollbackTrans();
                                                return false;
                                        }
                                        else
                                        {
                                                    $dbconn->CommitTrans();
                                                    return $Autorizacion;
                                        }
                            }
                            else
                            {
                                        $dbconn->CommitTrans();
                                        return $Autorizacion;
                            }
                }
    }

//-----------------------------------------------------------------
    /**
    * Busca los niveles del plan del responsable del paciente
    * @access public
    * @return array
    * @param string plan_id
    */
     function Niveles()
     {
					list($dbconn) = GetDBconn();
					$query="SELECT DISTINCT rango
													FROM planes_rangos
													WHERE plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'";
					$resulta=$dbconn->Execute($query);
					while(!$resulta->EOF){
							$niveles[]=$resulta->GetRowAssoc($ToUpper = false);
							$resulta->MoveNext();
					}
					$resulta->Close();
					return $niveles;
     }


    /**
  * Busca los diferentes tipos de afiliados
    * @access public
    * @return array
    */
        function Tipo_Afiliado()
        {
                list($dbconn) = GetDBconn();
                $query = "SELECT DISTINCT a.tipo_afiliado_nombre, a.tipo_afiliado_id
                                    FROM tipos_afiliado as a, planes_rangos as b
                                    WHERE b.plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
                                    and b.tipo_afiliado_id=a.tipo_afiliado_id";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                while(!$resulta->EOF)
                {
                        $vars[]=$resulta->GetRowAssoc($ToUpper = false);
                        $resulta->MoveNext();
                }
                $resulta->Close();
                return $vars;
        }

     /**
     *
     */
     function AdicionarServicio()
     {
            list($dbconn) = GetDBconn();
            GLOBAL $ADODB_FETCH_MODE;
            if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TODO']))
            {
                        $query = "select a.nivel, a.grupo_tipo_cargo, a.tipo_cargo, b.descripcion,
                                            c.descripcion as destipo, d.descripcion_corta
                                            from planes_autorizaciones_int as a, grupos_tipos_cargo as b, tipos_cargos as c,
                                            niveles_atencion as d
                                            where a.plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
                                            and a.servicio='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']."'
                                            and a.grupo_tipo_cargo=b.grupo_tipo_cargo and a.grupo_tipo_cargo=c.grupo_tipo_cargo and
                                            a.tipo_cargo=c.tipo_cargo and a.nivel=d.nivel
                                            order by a.grupo_tipo_cargo, c.descripcion, a.tipo_cargo";
            }
            else
            {
                        $query = "select d.nivel, b.grupo_tipo_cargo, c.tipo_cargo, b.descripcion,
                            c.descripcion as destipo, d.descripcion_corta from grupos_tipos_cargo as b,
                            tipos_cargos as c, niveles_atencion as d
                            where b.grupo_tipo_cargo=c.grupo_tipo_cargo and b.grupo_tipo_cargo <> 'SYS'
                            order by b.grupo_tipo_cargo, c.descripcion, c.tipo_cargo";
            }
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result=$dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while ($data = $result->FetchRow()) {
                $grupo[$data['descripcion']]+= 1;
                $tipo[$data['descripcion']][$data['destipo']] += 1;
                $nivel[$data['descripcion']][$data['destipo']][$data['descripcion_corta']] = 1;
            }

            $result=$dbconn->Execute($query);
            while(!$result->EOF)
            {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }
            $result->Close();

            $this->SolicitudServicios($grupo,$tipo,$var,$nivel);
            return true;
     }


    /**
    *
    */
    function AdicionarCargo()
    {
             if(!$this->FormaCargos()){
                    return false;
            }
          return true;
    }


    /**
    *
    */
    function LlamarFormaAutorizacion()
    {
            unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['VECTOR']);
            if(!$this->FormaAutorizacion()){
                    return false;
            }
          return true;
    }

    /**
    *
    */
    function InsertarAutorizacionInicial()
    {
            if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']))
            {
                        if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']))
                        {  $Ingreso='NULL';  }
                        else
                        {  $Ingreso=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso'];  }
                        $FechaRegistro=date("Y-m-d H:i:s");
                        $SystemId=UserGetUID();
												$Autorizacion=$this->NextValAutorizacion();
                        list($dbconn) = GetDBconn();
                        /*$query="SELECT nextval('autorizaciones_autorizacion_seq')";
                        $result=$dbconn->Execute($query);
                        $Autorizacion=$result->fields[0];*/
                        $query = "INSERT INTO autorizaciones(
																															autorizacion,
																															fecha_autorizacion,
																															observaciones,
																															usuario_id,
																															fecha_registro,
																															sw_estado,
																															ingreso)
																VALUES ($Autorizacion,'$FechaRegistro','',$SystemId,'$FechaRegistro',0,$Ingreso)";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error INSERT INTO autorizaciones";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                        }
                        $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']=$Autorizacion;

                        if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
                        {
                                $Registro=ImplodeArrayAssoc($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
                                $query = "INSERT INTO autorizaciones_bd(
                                                                                        autorizacion,
                                                                                        registro)
                                                    VALUES ($Autorizacion,'$Registro')";
                                $result=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al guardar en autorizaciones";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                }
                        }
            }
    }


    /**
    *
    */
    function CargosSolicitadosAutorizacion()
    {
            list($dbconn) = GetDBconn();
           $query = "select a.*, b.descripcion
                      from autorizaciones_ingreso_cargos as a, tarifarios_detalle as b
                      where a.autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
                      and a.tarifario_id=b.tarifario_id and a.cargo=b.cargo";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            if(!$result->EOF)
            {
                    while(!$result->EOF)
                    {
                            $cargos[]=$result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                    }
            }

            //servicios
            GLOBAL $ADODB_FETCH_MODE;
            $query = "select a.*, b.descripcion, c.descripcion as destipo, d.descripcion_corta
                      from autorizaciones_ingreso_grupo_cargos as a, grupos_tipos_cargo as b,tipos_cargos as c, niveles_atencion as d
                      where a.autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
                      and a.grupo_tipo_cargo=b.grupo_tipo_cargo and a.grupo_tipo_cargo=c.grupo_tipo_cargo and
                      a.tipo_cargo=c.tipo_cargo and a.nivel=d.nivel order by a.grupo_tipo_cargo,
                      c.descripcion, a.tipo_cargo";
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result=$dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if(!$result->EOF)
            {
                    while ($data = $result->FetchRow()) {
                        $grupo[$data['descripcion']]+= 1;
                        $tipo[$data['descripcion']][$data['destipo']] += 1;
                        $nivel[$data['descripcion']][$data['destipo']][$data['descripcion_corta']] = 1;
                    }

                    $result=$dbconn->Execute($query);
                    while(!$result->EOF)
                    {
                            $servicio[]=$result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                    }
            }

            $this->FormaSolicitud($cargos,$servicio,$grupo,$tipo,$nivel);
            return true;
    }


    /**
    *
    */
    function InsertarCargo()
    {
                 IncludeLib("tarifario");
                if(empty($_REQUEST['Guardar']))
                {
                    if($_REQUEST['Cargo'] && $_REQUEST['Codigo'])
                    {
                            list($dbconn) = GetDBconn();
                            $query = "select autorizacion_cobertura('".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."','".$_REQUEST['TarifarioId']."','".$_REQUEST['Codigo']."','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']."')";
                            $result = $dbconn->Execute($query);

                            if($result->fields[0]=='NULL' || $result->fields[0]=='NoRequiere')
                            {
                                    if($result->fields[0]=='NULL')
                                    {
                                                $this->frmError["MensajeError"]="ERROR AUTORIZACION: El Plan No Cubre el Cargo ".$_REQUEST['Cargo'];
                                                //$this->AutorizacionServicio();
                                                $this->FormaCargos();
                                                return true;
                                    }
                                    if($result->fields[0]=='NoRequiere')
                                    {
                                                $this->frmError["MensajeError"]="ERROR AUTORIZACION: El Cargo ".$_REQUEST['Cargo']." No Requiere Autorización.";
                                                $this->FormaCargos();
                                                return true;
                                    }
                            }
                            //forma el arreglo
                            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['VECTOR'][$_REQUEST['TarifarioId']][$_REQUEST['Codigo']][$_REQUEST['Cantidad']]=$_REQUEST['Cargo'];
                    }
                    elseif(empty($_REQUEST['Cargo']) && !empty($_REQUEST['Codigo']))
                    {
                          $key1="cargo";
                          $filtro = "( lower ($key1)='".$_REQUEST['Codigo']."')";
                          $campos_select = " tarifario_id, grupo_tarifario_id, subgrupo_tarifario_id, sw_cantidad , descripcion ";
                          $resulta = PlanTarifario($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'], '', '', '', '', '', '', $filtro, $campos_select, $fetch_mode_assoc=false,'','');
                          $arreglo=$resulta->GetRowAssoc($ToUpper = false);
                          $TarifarioId=$arreglo[tarifario_id];
                          $desc=$arreglo[descripcion];
                          $_SESSION['AUTORIZACIONES']['AUTORIZAR']['VECTOR'][$TarifarioId][$_REQUEST['Codigo']][$_REQUEST['Cantidad']]=$desc;

                    }
                    $this->FormaCargos();
                    return true;
                }

                list($dbconn) = GetDBconn();
                foreach($_SESSION['AUTORIZACIONES']['AUTORIZAR']['VECTOR'] as $k => $v)
                {
                      foreach($v as $cod => $cant)
                      {
                              foreach($cant as $cantidad => $cargo)
                              {
                                          $query = "INSERT INTO  autorizaciones_ingreso_cargos
                                                                      (autorizacion,
                                                                      tarifario_id,
                                                                      cargo,
                                                                      servicio,
                                                                      cantidad)
                                          VALUES(".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'].",'$k','$cod','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']."',$cantidad)";
                                          $dbconn->Execute($query);
                                          if ($dbconn->ErrorNo() != 0) {
                                                  $this->error = "Error al Guardar en la Base de Datos";
                                                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                  return false;
                                          }

                              }
                      }
                }
            unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['VECTOR']);
            $this->FormaAutorizacion();
            return true;
    }

     /**
     *
     */
    function InsertarServicio()
    {
            $f=0;
            foreach($_REQUEST as $k => $v)
            {
                if(substr_count($k,'Nivel'))
                {
                        $f=1;
                }
            }

            if($f==0)
            {
                    $this->frmError["MensajeError"]="ERRO DATOS VACIOS: DEBE ELEGIR EL SERVICIO A SOLICITAR.";
                    $this->AdicionarServicio();
                    return true;
            }

            unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGOS']['VECT']);
            foreach($_REQUEST as $k => $v)
            {
                    if(substr_count($k,'Nivel'))
                    {
                            $servicio=explode(',',$v);
                            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGOS']['VECT'][$servicio[0]][$servicio[1]][$servicio[2]]=$servicio[0];
                    }
            }

            list($dbconn) = GetDBconn();
            $query = "delete from autorizaciones_ingreso_grupo_cargos
                                where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
            $dbconn->Execute($query);

            foreach($_REQUEST as $k => $v)
            {
                if(substr_count($k,'Nivel'))
                {
                            $servicio=explode(',',$v);
                            $query = "INSERT INTO  autorizaciones_ingreso_grupo_cargos
                                                                                                                                        (autorizacion,
                                                                                                                                        grupo_tipo_cargo,
                                                                                                                                        tipo_cargo,
                                                                                                                                        servicio,
                                                                                                                                        nivel)
                            VALUES(".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'].",'$servicio[0]','$servicio[1]','".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['SERVICIO']."',$servicio[2])";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al Guardar en la Base de Datos";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                            }
                }
            }

            $this->FormaAutorizacion();
            return true;
     }


    /**
    *
    */
    function EliminarCargo()
    {
            unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['VECTOR'][$_REQUEST['TarifarioId']][$_REQUEST['Codigo']]);
            if(sizeof($_SESSION['AUTORIZACIONES']['AUTORIZAR']['VECTOR'][$_REQUEST['TarifarioId']][$_REQUEST['Codigo']])==0)
            {
                    unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['VECTOR'][$_REQUEST['TarifarioId']][$_REQUEST['Codigo']]);
            }

            $this->FormaCargos();
            return true;
    }


    /**
    *
    */
    function InsertarAutorizacion()
    {
                $Tipo=$_REQUEST['Tipo'];
                $FechaAuto=$_REQUEST['FechaAuto'];
                $HoraAuto=$_REQUEST['HoraAuto'];
                $MinAuto=$_REQUEST['MinAuto'];
                $Observaciones=$_REQUEST['ObservacionesA'];
                $ObservacionesT=$_REQUEST['ObservacionesT'];
                $ObservacionesI=$_REQUEST['ObservacionesI'];
                $f=explode('/',$FechaAuto);
                $FechaAuto=$f[2].'-'.$f[1].'-'.$f[0];                       
                $Fecha=$FechaAuto." ".$HoraAuto.":".$MinAuto;
                $Ingreso='NULL';
                $_SESSION['AUTORIZACIONES']['ObservacionesI']=$_REQUEST['ObservacionesI'];
                $_SESSION['AUTORIZACIONES']['ObservacionesA']=$_REQUEST['ObservacionesA'];
                if($_REQUEST['Si']==1)
                {
                        $_SESSION['AUTORIZACIONES']['AFILIADO']=$_REQUEST['TipoAfiliado'];
                        $_SESSION['AUTORIZACIONES']['RANGO']=$_REQUEST['Nivel'];
                        $_SESSION['AUTORIZACIONES']['SEMANAS']=$_REQUEST['Semanas'];
                }
                if(!empty($_REQUEST['Cargos']))
                {
                    $this->FormaCargos();
                    return true;
                }
                if(!empty($_REQUEST['Grupos']))
                {
                    $this->AdicionarServicio();
                    return true;
                }
                //en caso que los datos de afiliado existan
                if(!empty($_REQUEST['Cambiar']))
                {
                        $this->LlamarFormaCambiar($_REQUEST['TipoAfiliado'],$_REQUEST['Nivel'],$_REQUEST['Semanas']);
                        return true;
                }
                //valida si elegio el tipo de autorizacion
                if(!empty($_REQUEST['Aceptar']) && $_REQUEST['TipoAutorizacion']==-1)
                {
                        $this->frmError["MensajeError"]="DEBE ELEGIR EL TIPO DE AUTORIZACIÓN.";
                        $this->FormaAutorizacion();
                        return true;
                }
                elseif(!empty($_REQUEST['Aceptar']) && $_REQUEST['TipoAutorizacion']!=-1)
                {
                        $this->FormaAutorizacionTipo($_REQUEST['TipoAutorizacion']);
                        return true;
                }
                //en caso que los datos de afiliado no existan y los pide
                if($_REQUEST['Si']==1)
                {
                        $_SESSION['AUTORIZACIONES']['AFILIADO']=$_REQUEST['TipoAfiliado'];
                        $_SESSION['AUTORIZACIONES']['RANGO']=$_REQUEST['Nivel'];
                        $_SESSION['AUTORIZACIONES']['SEMANAS']=$_REQUEST['Semanas'];
                        //if($_REQUEST['Semanas'] OR $_REQUEST['Semanas']===0)
                        if($_REQUEST['Semanas']=='')
                        {
                              if(is_numeric($_REQUEST['Semanas'])==0)
                              {
                                  $this->frmError["Semanas"]=1;
                                  $this->frmError["MensajeError"]="LAS SEMANAS DEBEN SER ENTERAS.";
                                  $this->FormaAutorizacion();
                                  return true;
                              }
                        }
                        if(($_REQUEST['Semanas']=='') || $_REQUEST['TipoAfiliado']==-1 || $_REQUEST['Nivel']==-1){
                                if($_REQUEST['Semanas']==''){ $this->frmError["Semanas"]=1; }
                                if($_REQUEST['TipoAfiliado']==-1){ $this->frmError["TipoAfiliado"]=1; }
                                if($_REQUEST['Nivel']==-1){ $this->frmError["Nivel"]=1; }
                                $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
                                $this->FormaAutorizacion();
                                return true;
                        }
                }
                if(!$FechaAuto || !$HoraAuto || !$MinAuto)
                {
                            if(!$FechaAuto){ $this->frmError["FechaAuto"]=1; }
                            if(!$HoraAuto){ $this->frmError["HoraAuto"]=1; }
                            if(!$MinAuto){ $this->frmError["HoraAuto"]=1; }
                            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
                            $this->FormaAutorizacion();
                            return true;
                }

                if(!empty($_REQUEST['NoAutorizar'])) {  $sw=1;  }
                else {  $sw=0; }

								if(!empty($_REQUEST['CodAuto']))
								{
									list($dbconn) = GetDBconn();
									$CodAuto=$_REQUEST['CodAuto'];
									$Autorizacion=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'];
									$Observaciones=$_REQUEST['Observaciones'];
									$query = "INSERT INTO autorizaciones_electronicas_sos(
																							autorizacion,
																							validez,
																							codigo_autorizacion,
																							observaciones)
                                                        VALUES ($Autorizacion,'".date("Y-m-d")."','$CodAuto','$Observaciones')";
									$results = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error select count(*)";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$this->lineError = __LINE__;
													return false;
									}
								}
																
                /*if(empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_activo'])
                  AND empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUDITOR']))
                {*/
                if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUDITOR']))
                {
                        list($dbconn) = GetDBconn();
                        $query = "select count(*)
                                            from autorizaciones_escritas as a
																						full join autorizaciones_telefonicas as b on (a.autorizacion=b.autorizacion)
																						full join autorizaciones_por_sistema as c on (b.autorizacion=c.autorizacion)
																						full join autorizaciones_electronicas as d on (c.autorizacion=d.autorizacion)
																						full join autorizaciones_certificados as e on (c.autorizacion=e.autorizacion)
																						full join autorizaciones_electronicas_sos as f on (c.autorizacion=f.autorizacion)
                                            where a.autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
                                            or b.autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
                                            or c.autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
                                            or d.autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
																						or e.autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
																						or f.autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
                        $results = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error select count(*)";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																$this->lineError = __LINE__;
                                return false;
                        }
												$results->Close();
                        if($results->fields[0]==0)
                        {
                                    $this->frmError["MensajeError"]="DEBE REALIZAR ALGÚN TIPO DE AUTORIZACIÓN.";
                                    $this->FormaAutorizacion();
                                    return true;
                        }
                }
                if(!empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUDITOR']))
                {
                        list($dbconn) = GetDBconn();
                        $query = "select count(*)
                                            from autorizaciones_escritas as a
																						full join autorizaciones_telefonicas as b on (a.autorizacion=b.autorizacion)
																						full join autorizaciones_por_sistema as c on (b.autorizacion=c.autorizacion)
																						full join autorizaciones_electronicas as d on (c.autorizacion=d.autorizacion)
																						full join autorizaciones_electronicas_sos as f on (c.autorizacion=f.autorizacion)
                                            where a.autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
                                            or b.autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
                                            or c.autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
                                            or d.autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
																						or f.autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
					              $results = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error select count(*)";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																$this->lineError = __LINE__;
                                return false;
                        }
												$results->Close();
                        if($results->fields[0]==0)
                        {
                                  $query = "select count(*) from autorizaciones_por_sistema
                                            where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
                                  $results = $dbconn->Execute($query);
                                  if ($dbconn->ErrorNo() != 0) {
                                          $this->error = "Error select count(*)";
																					$this->lineError = __LINE__;
                                          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                          return false;
                                  }
                                  if($results->fields[0]==0)
                                  {
                                      $query = "INSERT INTO autorizaciones_por_sistema(
                                                                  autorizacion,
                                                                  usuario_id,
                                                                  solicitud,
                                                                  fecha_confirmacion,
                                                                  observaciones,
                                                                  sw_confirmacion)
                                                VALUES (".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'].",".UserGetUID().",'','now()','',0)";
                                      $results = $dbconn->Execute($query);
                                      if ($dbconn->ErrorNo() != 0) {
                                              $this->error = "Error select count(*)";
                                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																							$this->lineError = __LINE__;																									
                                              return false;
                                      }
                                  }
                        }
                }

                //actualiza la autorizacion inicial
                $t=$o='';
                list($dbconn) = GetDBconn();
                if(!empty($ObservacionesT) AND $ObservacionesT!=' ')
                {  $t="OBSERVACIONES DE LAS AUTORIZACIONES: ".$ObservacionesT;  }
                if(!empty($Observaciones))
                {  $o=" OBSERVACIONES DE LA AUTORIZACION: ".$Observaciones;  }
                $obs=$t.$o;
                $query = "UPDATE autorizaciones SET
                                      fecha_autorizacion='$Fecha',
                                      observaciones='$obs',
                                      observacion_ingreso='$ObservacionesI',
                                      sw_estado=$sw
                                    WHERE autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
                $dbconn->BeginTrans();
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Tabal autorizaiones";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
                else
                {
                        $dbconn->CommitTrans();
                        $_SESSION['AUTORIZACIONES']['observacion_ingreso']=$ObservacionesI;
                        $auto=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'];
                        //unset($_SESSION['SOLICITUDAUTORIZACION']['VECTOR']);
                        //unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']);

                        if($auto)
                        {
                                    if(!empty($_REQUEST['NoAutorizar']))
                                    {
                                                $this->FormaJustificar($auto);
                                                return true;
                                    }
                                    else
                                    {
                                                $this->RetornarAutorizacion(true,'ADMITIR','',$auto);
                                                return true;
                                    }
                        }
                        else
                        {
                                    $this->RetornarAutorizacion(false,'ADMITIR','Plan',$auto);
                                    return true;
                        }
                }
    }


    /**
    *
    */
    function JustificarNoAutorizacion()
    {
                if(empty($_REQUEST['Observaciones']))
                {
                        $this->frmError["MensajeError"]="DEBE ELEGIR O DIGITAR LA JUSTIFICACIÓN DE LA NO AUTORIZACIÓN.";
                        $this->FormaJustificar($_REQUEST['auto']);
                        return true;
                }

                list($dbconn) = GetDBconn();
                $query = "select observaciones from autorizaciones where autorizacion=".$_REQUEST['auto']."";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Tabla autorizaiones";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $obs = $result->fields[0];
                $obs .=$_REQUEST['Observaciones'];

                $query = "UPDATE autorizaciones SET
                                      observaciones='$obs',
																			sw_estado=1
                                    WHERE autorizacion=".$_REQUEST['auto']."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Tabla autorizaiones";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $_SESSION['AUTORIZACIONES']['observacion_ingreso']=$obs;
								$_SESSION['AUTORIZACIONES']['NOAUTO']=TRUE;
                $this->RetornarAutorizacion(false,'ADMITIR','',$_REQUEST['auto']);
                return true;
    }



    /**
    *
    */
    function LlamarFormaAutorizacionTipo()
    {
                $this->FormaAutorizacionTipo($_REQUEST['TipoAutorizacion']);
                return true;
    }


    /**
    *
    */
    function InsertarTipoAutorizacion()
    {
                $Tipo=$_REQUEST['Tipo'];
                $CodAuto=$_REQUEST['CodAuto'];
                $Responsable=$_REQUEST['Responsable'];
                $Validez=$_REQUEST['Validez'];
                $Registro=$_REQUEST['Registro'];
                $Observaciones=$_REQUEST['Observaciones'];
                IF(!empty($Registro))
                {
                    $f=explode('/',$Registro);
                    $Registro=$f[2].'-'.$f[1].'-'.$f[0];
                }
                if(!empty($Validez))
                {
                    $f=explode('/',$Validez);
                    $Validez=$f[2].'-'.$f[1].'-'.$f[0];
                }

                /*if($Tipo!='4')
                {*/
                $Validar=$this->ValidarAutorizacion($Tipo,$CodAuto,$Responsable,$Validez);
                /*}
                else
                {  $Validar=true;  }*/
                if($Validar)
                {
                            $Autorizacion=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'];
                            $SystemId=UserGetUID();
                            list($dbconn) = GetDBconn();
                            if($Tipo=='1')
                            {
																		$_SESSION['AUTORIZACIONES']['AUTORIZAR']['EXT']=TRUE;
                                $query = "INSERT INTO autorizaciones_telefonicas(
                                                                autorizacion,
                                                                responsable,
                                                                codigo_autorizacion,
                                                                observaciones)
                                                        VALUES ($Autorizacion,'$Responsable','$CodAuto','$Observaciones')";
                            }
                            if($Tipo=='2')
                            {
                                    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['EXT']=TRUE;
                                    $query = "INSERT INTO autorizaciones_escritas(
                                                          autorizacion,
                                                          validez,
                                                          codigo_autorizacion,
                                                          observaciones)
                                                        VALUES ($Autorizacion,'$Validez','$CodAuto','$Observaciones')";
                            }
                            if($Tipo=='3')
                            {
																				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['EXT']=TRUE;
                                    $query = "INSERT INTO autorizaciones_bd(
                                                          autorizacion,
                                                          registro)
                                                        VALUES ($Autorizacion,'$Registro')";
                            }
                            if($Tipo=='4')
                            {
                                    if($Responsable!=UserGetUID())
                                    {  $sw=1;  }
                                    else
                                    {  $sw=0;  }
                                    $query = "INSERT INTO autorizaciones_por_sistema(
                                                          autorizacion,
                                                          usuario_id,
                                                          solicitud,
                                                          fecha_confirmacion,
                                                          observaciones,
                                                          sw_confirmacion)
                                                        VALUES ($Autorizacion,'$Responsable','$Solicitud',NULL,'$Observaciones',$sw)";
                            }
                            if($Tipo=='5')
                            {
																				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['EXT']=TRUE;
                                    $query = "INSERT INTO autorizaciones_electronicas(
																																								autorizacion,
																																								validez,
																																								codigo_autorizacion,
																																								observaciones)
																							VALUES ($Autorizacion,'$Validez','$CodAuto','$Observaciones')";
                            }
                            if($Tipo=='6')
                            {
																				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['EXT']=TRUE;
                                    $query = "INSERT INTO autorizaciones_certificados(
																																										autorizacion,
																																										responsable,
																																										codigo_autorizacion,
																																										observaciones,
																																										fecha_terminacion)
                                                        VALUES ($Autorizacion,'$Responsable','$CodAuto','$Observaciones','$Validez')";
                            }
														//cambio para sos
														if($Tipo=='7')
                            {
																				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['EXT']=TRUE;
																				unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['error']);
																				unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['mensajeDeError']);
                                    $query = "INSERT INTO autorizaciones_electronicas_sos(
																							autorizacion,
																							validez,
																							codigo_autorizacion,
																							observaciones)
                                                        VALUES ($Autorizacion,'$Validez','$CodAuto','$Observaciones')";
                            }
														//fin cambio sos
                            $result = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al guardar en autorizaciones";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                            }
                            else
                            {
                                        $this->frmError["MensajeError"]="LA AUTORIZACIÓN SE GUARDO CORRECTAMENTE.";
                                        $this->FormaAutorizacion();
                                        return true;
                            }
                }
                else
                {
                        $this->frmError["MensajeError"]="ERROR DATOS VACIOS: Faltan datos obligatorios.";
                        $this->FormaAutorizacionTipo($Tipo);
                        return true;
                }
    }


    /**
    *
    */
    function DetalleAutorizacion()
    {
                unset($_SESSION['TRIAGE']['VECT']);
                if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['cuenta']))
                {
                        $this->error = "AUTORIZACION ";
                        $this->mensajeDeError = "DATOS DE LA AUTORIZACIÓn INCOMPLETOS.";
                        return false;
                }

                $Contenedor=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
                $Modulo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
                $Tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
                $Metodo=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];

                if(empty($Contenedor) || empty($Modulo) || empty($Tipo) || empty($Metodo))
                {
                        $this->error = "AUTORIZACION ";
                        $this->mensajeDeError = "LOS DATOS DE RETORNO DE LA AUTORIZACIÓN NO SON CORRECTOS.";
                        return false;
                }

                list($dbconn) = GetDBconn();
                $query = "select d.tipo_afiliado_nombre, a.rango, b.ingreso, c.tipo_id_paciente, c.paciente_id,
                            c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre, b.comentario, a.plan_id, e.plan_descripcion
                            from cuentas as a, ingresos as b, pacientes as c, tipos_afiliado as d, planes as e
                            where a.numerodecuenta=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['cuenta']."
                            and a.tipo_afiliado_id=d.tipo_afiliado_id and a.ingreso=b.ingreso and b.tipo_id_paciente=c.tipo_id_paciente
                            and b.paciente_id=c.paciente_id and a.plan_id=e.plan_id";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $vars=$resulta->GetRowAssoc($ToUpper = false);

                $query = "SELECT A.servicio, B.descripcion
                        FROM planes_servicios AS A,
                        servicios AS B
                        WHERE A.plan_id=".$vars[plan_id]."
                        AND A.servicio=B.servicio
                        ORDER BY A.servicio;";
                $results = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                while(!$results->EOF)
                {
                    $var[]=$results->GetRowAssoc($ToUpper = false);
                    $results->MoveNext();
                }
                $_SESSION['TRIAGE']['VECT']['SERVICIOS']=$var;
                $_SESSION['TRIAGE']['VECT']['DATOS']=$vars;
//los cargo que autoriza
//select a.transaccion, b.*,c.*
//from cuentas_detalle as a left join ayudas_diagnosticas as c on (a.transaccion=c.transaccion), autorizaciones_cruce_cargos as b
//where a.numerodecuenta=258 and a.transaccion=b.transaccion

//select a.transaccion, b.*,c.*, f.descripcion as desc, e.descripcion as desa
//from cuentas_detalle as a left join tarifarios_detalle as f on (a.cargo=f.cargo and a.tarifario_id=f.tarifario_id) left join ayudas_diagnosticas as c on (a.transaccion=c.transaccion) left join tarifarios_detalle as e on (c.cargo=e.cargo and c.tarifario_id=e.tarifario_id), autorizaciones_cruce_cargos as b
//where a.numerodecuenta=258 and a.transaccion=b.transaccion
            $this->FormaDetalleAutorizacion();
            return true;
    }

    /**
    *
    */
    function VerServicio()
    {
            if(empty($_SESSION['TRIAGE']['VECT']['SERVICIO']))
            {
                    $_SESSION['TRIAGE']['VECT']['DESSERVICIO']=$_REQUEST['Descripcion'];
                    $_SESSION['TRIAGE']['VECT']['SERVICIO']=$_REQUEST['Servicio'];
            }

            list($dbconn) = GetDBconn();
             $query = "
                    (SELECT A.descripcion AS des1, B.descripcion AS des2,
                    C.servicio, 0 as interno, C.nivel, A.grupo_tipo_cargo, B.tipo_cargo
                    FROM grupos_tipos_cargo AS A,
                    tipos_cargos AS B,
                    planes_autorizaciones_int AS C
                    WHERE A.grupo_tipo_cargo=B.grupo_tipo_cargo
                    AND A.grupo_tipo_cargo<>'SYS' AND
                    C.plan_id=".$_SESSION['TRIAGE']['VECT']['DATOS']['plan_id']."
                    AND B.grupo_tipo_cargo=C.grupo_tipo_cargo
                    AND B.tipo_cargo=C.tipo_cargo
                    AND C.servicio='".$_SESSION['TRIAGE']['VECT']['SERVICIO']."'
                    ORDER BY des1, des2, C.servicio, interno, C.nivel)
                    UNION
                    (SELECT A.descripcion AS des1, B.descripcion AS des2,
                    C.servicio, 1 as interno, C.nivel, A.grupo_tipo_cargo, B.tipo_cargo
                    FROM grupos_tipos_cargo AS A,
                    tipos_cargos AS B,
                    planes_autorizaciones_ext AS C
                    WHERE A.grupo_tipo_cargo=B.grupo_tipo_cargo
                    AND A.grupo_tipo_cargo<>'SYS' AND
                    C.plan_id=".$_SESSION['TRIAGE']['VECT']['DATOS']['plan_id']."
                    AND B.grupo_tipo_cargo=C.grupo_tipo_cargo
                    AND B.tipo_cargo=C.tipo_cargo
                    AND C.servicio='".$_SESSION['TRIAGE']['VECT']['SERVICIO']."'
                    ORDER BY des1, des2, C.servicio, interno, C.nivel);";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$resulta->EOF)
            {
                $var[]=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
            }

            $this->FormaDetalleAutorizacionServicio($var);
            return true;
    }

    /**
    *
    */
    function VerExcepciones()
    {
            list($dbconn) = GetDBconn();
            $query = "(
                                    SELECT c.descripcion as des1, d.descripcion as des2, b.descripcion, 0 as interno,
                                    b.grupo_tipo_cargo, b.tipo_cargo, a.tarifario_id, a.cargo, a.cantidad, a.valor_maximo,
                                    a.sw_autorizado, a.periocidad_dias
                                    FROM excepciones_aut_int as a, tarifarios_detalle as b, grupos_tipos_cargo as c, tipos_cargos as d
                                    WHERE a.plan_id=".$_SESSION['TRIAGE']['VECT']['DATOS']['plan_id']."
                                    and a.servicio='".$_SESSION['TRIAGE']['VECT']['SERVICIO']."'
                                    and a.tarifario_id=b.tarifario_id
                                    and a.cargo=b.cargo
                                    and b.grupo_tipo_cargo='".$_REQUEST['Grupo']."'
                                    and b.grupo_tipo_cargo=c.grupo_tipo_cargo
                                    and c.grupo_tipo_cargo=d.grupo_tipo_cargo
                                    and c.grupo_tipo_cargo<>'SYS'
                                    and b.tipo_cargo='".$_REQUEST['Tipo']."'
                                    and b.tipo_cargo=d.tipo_cargo
                                    ORDER BY des1, des2, b.descripcion, interno
                                    )
                                UNION
                                (
                                SELECT c.descripcion as des1, d.descripcion as des2, b.descripcion, 1 as interno,
                                b.grupo_tipo_cargo, b.tipo_cargo, a.tarifario_id, a.cargo, a.cantidad, a.valor_maximo,
                                a.sw_autorizado, a.periocidad_dias
                                FROM excepciones_aut_ext as a, tarifarios_detalle as b, grupos_tipos_cargo as c, tipos_cargos as d
                                WHERE a.plan_id=".$_SESSION['TRIAGE']['VECT']['DATOS']['plan_id']."
                                and a.servicio='".$_SESSION['TRIAGE']['VECT']['SERVICIO']."'
                                and a.tarifario_id=b.tarifario_id
                                and a.cargo=b.cargo
                                and b.grupo_tipo_cargo='".$_REQUEST['Grupo']."'
                                and b.grupo_tipo_cargo=c.grupo_tipo_cargo
                                and c.grupo_tipo_cargo=d.grupo_tipo_cargo
                                and c.grupo_tipo_cargo<>'SYS'
                                and b.tipo_cargo='".$_REQUEST['Tipo']."'
                                and b.tipo_cargo=d.tipo_cargo
                                ORDER BY des1, des2, b.descripcion, interno
                                )";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$resulta->EOF)
            {
                $var[]=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
            }

            $this->FormaDetalleAutorizacioExcepciones($var);
            return true;
    }

    /**
    *
    */
    function DetalleAutorizacionesRealizadas()
    {
                if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['cuenta']))
                {
                        $this->error = "AUTORIZACION ";
                        $this->mensajeDeError = "DATOS DE LA AUTORIZACIÓN INCOMPLETOS.";
                        return false;
                }

                $Contenedor=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
                $Modulo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
                $Tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
                $Metodo=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];

                if(empty($Contenedor) || empty($Modulo) || empty($Tipo) || empty($Metodo))
                {
                        $this->error = "AUTORIZACION ";
                        $this->mensajeDeError = "LOS DATOS DE LA AUTORIZACIÓN NO SON CORRECTOS.";
                        return false;
                }

                list($dbconn) = GetDBconn();
                $query = "select a.transaccion, a.cantidad as cantc, a.cargo as cargoc, b.*, f.descripcion as desc,
														g.nombre, case when b.sw_tipo_autorizacion=1 then 'EXTERNA' when b.sw_tipo_autorizacion=0 then 'INTERNA' else 'INTERNA-EXTERNA' end as tipo
														from cuentas_detalle as a left join tarifarios_detalle as f on (a.cargo=f.cargo and a.tarifario_id=f.tarifario_id),
														autorizaciones_ingreso_cargos as b, system_usuarios as g
														where a.numerodecuenta=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['cuenta']."
														and a.transaccion=b.transaccion and b.usuario_id=g.usuario_id";
									/*
                $query = "select a.transaccion, a.cantidad as cantc, c.cantidad as canta, a.cargo as cargoc, c.cargo as cargoa,b.*, f.descripcion as desc,
                                    e.descripcion as desa, g.nombre, case when b.sw_tipo_autorizacion=1 then 'EXTERNA' when b.sw_tipo_autorizacion=0 then 'INTERNA' else 'INTERNA-EXTERNA' end as tipo
                                    from cuentas_detalle as a left join tarifarios_detalle as f on (a.cargo=f.cargo and a.tarifario_id=f.tarifario_id)
                                    left join ayudas_diagnosticas as c on (a.transaccion=c.transaccion)
                                    left join tarifarios_detalle as e on (c.cargo=e.cargo and c.tarifario_id=e.tarifario_id),
                                    autorizaciones_ingreso_cargos as b, system_usuarios as g
                                    where a.numerodecuenta=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['cuenta']."
                                    and a.transaccion=b.transaccion and b.usuario_id=g.usuario_id";

									*/

                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                while(!$resulta->EOF)
                {
                    $var[]=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                }

                $query = "select d.tipo_afiliado_nombre, a.rango, b.ingreso, c.tipo_id_paciente, c.paciente_id,
                            c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre, b.comentario, a.plan_id, e.plan_descripcion
                            from cuentas as a, ingresos as b, pacientes as c, tipos_afiliado as d, planes as e
                            where a.numerodecuenta=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['cuenta']."
                            and a.tipo_afiliado_id=d.tipo_afiliado_id and a.ingreso=b.ingreso and b.tipo_id_paciente=c.tipo_id_paciente
                            and b.paciente_id=c.paciente_id and a.plan_id=e.plan_id";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                $vars=$result->GetRowAssoc($ToUpper = false);
                $_SESSION['TRIAGE']['VECT']['DATOS']=$vars;

                $resulta->Close();
                $result->Close();

                $this->FormaCargosAutorizados($var);
                return true;
    }

    /**
    *
    */
    function EliminarCargoAutorizado()
    {
                list($dbconn) = GetDBconn();
                $query = "DELETE FROM autorizaciones_ingreso_cargos
                                    WHERE autorizacion=".$_REQUEST['autorizacion']." and tarifario_id='".$_REQUEST['tarifario']."'
                                    and cargo='".$_REQUEST['cargo']."' ";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }

                $this->frmError["MensajeError"]="El Cargo se Elimino.";
                $this->LlamarFormaAutorizacion();
                return true;
    }

  /**
     * Llama la forma ConfirmarAccion (forma de mensaje de dos botones).
   * @ access public
     * @ return boolean
     */
    function LlamaConfirmarAccion()
    {
                if(empty($_REQUEST['arreglo']))
                { $arreglo=array();  }
                else
                { $arreglo=$_REQUEST['arreglo']; }

                $c=$_REQUEST['c'];
                $m=$_REQUEST['m'];
                $me=$_REQUEST['me'];
                $me2=$_REQUEST['me2'];
                $mensaje=$_REQUEST['mensaje'];
                $Titulo=$_REQUEST['titulo'];
                $boton1=$_REQUEST['boton1'];
                $boton2=$_REQUEST['boton2'];

                $this->salida=ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,array($c,$m,'user',$me,$arreglo),array($c,$m,'user',$me2,$arreglo));
                return true;
    }

    /**
    *
    */
    function BuscarAutorizaciones($tabla)
    {
                list($dbconn) = GetDBconn();
                if($tabla=='autorizaciones_por_sistema')
                {
                        $query = "select  b.nombre, a.* from $tabla as a, system_usuarios as b
                                    where a.autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
                                    and a.usuario_id=b.usuario_id";
                }
                else
                {
                       $query = "select * from $tabla
                                    where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
                }
                $result=$dbconn->Execute($query);

								if(!$result->EOF)
								{
										while(!$result->EOF)
										{
														$var[]=$result->GetRowAssoc($ToUpper = false);
														$result->MoveNext();
										}
								}
                return $var;
    }


    /**
    *
    */
    function EliminarAutorizaciones()
    {
                list($dbconn) = GetDBconn();
                $query = "DELETE FROM ".$_REQUEST['tabla']."
                                    where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
                                    and ".$_REQUEST['campo']."=".$_REQUEST['id']."";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }

                $this->frmError["MensajeError"]="La Autorizacion se Elimino.";
                $this->FormaAutorizacionTipo($_REQUEST['TipoAutorizacion']);
                return true;
    }

    /**
    *
    */
    function Observaciones()
    {
                list($dbconn) = GetDBconn();
                $query = "select observaciones from autorizaciones_telefonicas
                                    where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                while(!$result->EOF)
                {
                        $obs.=$result->fields[0]." ";
                        $result->MoveNext();
                }
                $query = "select observaciones from autorizaciones_escritas
                                    where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                while(!$result->EOF)
                {
                        $obs.=$result->fields[0]." ";
                        $result->MoveNext();
                }
                $query = "select observaciones from autorizaciones_electronicas
                                    where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                while(!$result->EOF)
                {
                        $obs.=$result->fields[0]." ";
                        $result->MoveNext();
                }
                $query = "select observaciones from autorizaciones_por_sistema
                                    where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                while(!$result->EOF)
                {
                        $obs.=$result->fields[0]." ";
                        $result->MoveNext();
                }
                $query = "select observaciones from autorizaciones_certificados
                                    where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                while(!$result->EOF)
                {
                        $obs.=$result->fields[0]." ";
                        $result->MoveNext();
                }
                return $obs;
    }


    /**
  * Busca los diferentes tipos de afiliados
    * @access public
    * @return array
    */
    function NombreAfiliado($Tipo)
    {
                list($dbconn) = GetDBconn();
                $query = "SELECT DISTINCT a.tipo_afiliado_nombre, a.tipo_afiliado_id
                                    FROM tipos_afiliado as a, planes_rangos as b
                                    WHERE b.plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
                                    and a.tipo_afiliado_id='$Tipo'";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $vars=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->Close();
                return $vars;
    }

    /**
    *
    */
    function LlamarFormaCambiar($tipo,$rango,$semanas)
    {
            $this->FormaCambiar($tipo,$rango,$semanas);
            return true;
    }

    /**
    *
    */
    function GuardarCambiosAfiliado()
    {
					if($_REQUEST['Semanas']=='' || $_REQUEST['TipoAfiliado']==-1 || $_REQUEST['Nivel']==-1){
									if($_REQUEST['Semanas']==''){ $this->frmError["Semanas"]=1; }
									if($_REQUEST['TipoAfiliado']==-1){ $this->frmError["TipoAfiliado"]=1; }
									if($_REQUEST['Nivel']==-1){ $this->frmError["Nivel"]=1; }
									$this->frmError["MensajeError"]="Faltan datos obligatorios.";
									$this->FormaCambiar($_REQUEST['TipoAfiliado'],$_REQUEST['Nivel'],$_REQUEST['Semanas']);
									return true;
					}
					if(empty($_REQUEST['Semanas']))
					{  $_REQUEST['Semanas']=0; }
					if(empty($_SESSION['AUTORIZACIONES']['SEMANAS']))
					{  $_SESSION['AUTORIZACIONES']['SEMANAS']=0; }
					list($dbconn) = GetDBconn();

					$query = "SELECT autorizacion FROM auditoria_cambio_datos_bdafiliados
										WHERE autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
					$results=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error NSERT INTO auditoria_cambio_datos_bdafiliados";
							$this->lineError = __LINE__;
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					if(!$results->EOF)
					{
								$query = "DELETE FROM auditoria_cambio_datos_bdafiliados
													WHERE autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
								$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error NSERT INTO auditoria_cambio_datos_bdafiliados";
										$this->lineError = __LINE__;
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
								}
					}

					$query = "INSERT INTO auditoria_cambio_datos_bdafiliados(
														tipo_id_afiliado_ant,
														rango_ant,
														semanas_cotizadas_ant,
														tipo_id_afiliado_act,
														rango_act,
														semanas_cotizadas_act,
														observacion,
														usuario_id,
														fecha_registro,
														autorizacion,
														plan_id)
										VALUES('".$_SESSION['AUTORIZACIONES']['AFILIADO']."','".$_SESSION['AUTORIZACIONES']['RANGO']."',".$_SESSION['AUTORIZACIONES']['SEMANAS'].",
										'".$_REQUEST['TipoAfiliado']."','".$_REQUEST['Nivel']."',".$_REQUEST['Semanas'].",'".$_REQUEST['Observacion']."',".UserGetUID().",'now()',
										".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'].",".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'].")";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error NSERT INTO auditoria_cambio_datos_bdafiliados";
							$this->lineError = __LINE__;
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}

					$_SESSION['AUTORIZACIONES']['AFILIADO']=$_REQUEST['TipoAfiliado'];
					$_SESSION['AUTORIZACIONES']['RANGO']=$_REQUEST['Nivel'];
					$_SESSION['AUTORIZACIONES']['SEMANAS']=$_REQUEST['Semanas'];

					$this->LlamarFormaAutorizacion();
					return true;
    }

    /**
    *
    */
    function BuscarUsuarios($PlanId)
    {
            list($dbconn) = GetDBconn();
             $query = " SELECT b.nombre, b.usuario_id
                                    FROM planes_auditores_int as a, system_usuarios as b
                                    WHERE a.plan_id='$PlanId' and a.usuario_id=b.usuario_id";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            while(!$result->EOF)
            {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }
            $result->Close();
            return $var;
    }

    /**
    * Busca el nombre del paciente
    * @access public
    * @return array
    * @param string tipo de documento
    * @param int numero de documento
    */
    function NombrePaciente($TipoDocumento,$Documento)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido as nombre,
                      tipo_id_paciente, paciente_id
                      FROM pacientes
                      WHERE paciente_id='$Documento' AND tipo_id_paciente ='$TipoDocumento'";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            if(!$resulta->EOF)
            {  $vars=$resulta->GetRowAssoc($ToUpper = false);  }
            return $vars;
    }

    /**
    *
    */
    function DatosPlan()
    {
            list($dbconn) = GetDBconn();
             $query = "SELECT a.plan_descripcion, b.nombre_tercero
                      FROM planes as a, terceros as b
                      WHERE a.plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
                      and a.tipo_tercero_id=b.tipo_id_tercero
                      and a.tercero_id=b.tercero_id";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $vars=$resulta->GetRowAssoc($ToUpper = false);
            return $vars;
    }


    /**
    *
    */
    function MultiplesBD()
    {
            list($dbconn) = GetDBconn();
            $query = "select meses_consulta_base_datos from planes
                        where plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }

            return $result->fields[0];
    }

    /**
    *
    */
    function BuscarEvolucion()
    {    $var='';
        list($dbconn) = GetDBconn();
        $query = "select b.evolucion_id from hc_evoluciones as b
                  where b.ingreso='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']."'
                  and b.fecha_cierre=(select max(fecha_cierre) from hc_evoluciones
									where ingreso='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']."')";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        if(!$result->EOF)
        {  $var=$result->fields[0];  }

        return $var;
    }


    /**
    *
    */
    function CantidadMeses($plan)
    {
        list($dbconn) = GetDBconn();
        $sql="select meses_consulta_base_datos from planes where plan_id=$plan;";
        $result=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }
        $result->Close();
        return $result->fields[0];
    }


    /**
    *
    */
    function BuscarSwHc()
    {
        list($dbconn) = GetDBconn();
        $query = "select sw_hc from autorizaciones_niveles_autorizador
                  where nivel_autorizador_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['NIVEL']."'";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        if(!$result->EOF)
        {  $var=$result->fields[0];  }

        return $var;
    }


    /**
    *
    */
    function GuardarAfiliado()
    {
            if($_REQUEST['Semanas']=='' || $_REQUEST['TipoAfiliado']==-1 || $_REQUEST['Nivel']==-1){
                    if($_REQUEST['Semanas']==''){ $this->frmError["Semanas"]=1; }
                    if($_REQUEST['TipoAfiliado']==-1){ $this->frmError["TipoAfiliado"]=1; }
                    if($_REQUEST['Nivel']==-1){ $this->frmError["Nivel"]=1; }
                    $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                    $this->FormaAfiliado($_REQUEST['TipoAfiliado'],$_REQUEST['Nivel'],$_REQUEST['Semanas']);
                    return true;
            }

            $_SESSION['AUTORIZACIONES']['AFILIADO']=$_REQUEST['TipoAfiliado'];
            $_SESSION['AUTORIZACIONES']['RANGO']=$_REQUEST['Nivel'];
            $_SESSION['AUTORIZACIONES']['SEMANAS']=$_REQUEST['Semanas'];
						list($dbconn) = GetDBconn();
						$dbconn->BeginTrans();
            if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
            {
                if($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado']!=$_REQUEST['TipoAfiliado']
                  AND $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel']!=$_REQUEST['Nivel']
                  AND $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas']!=$_REQUEST['Semanas'])
                {
                    if(empty($_REQUEST['Observacion']))
                    {
                        $this->frmError["Observacion"]=1;
                        $this->frmError["MensajeError"]="DEBE DIGITAR LA JUSTIFICACIÓN DEL CAMBIO.";
                        $this->FormaDatosAfiliado($_REQUEST['TipoAfiliado'],$_REQUEST['Nivel'],$_REQUEST['Semanas']);
                        return true;
                    }

                    $query = "INSERT INTO auditoria_cambio_datos_bdafiliados
                              VALUES('".$_SESSION['AUTORIZACIONES']['AFILIADO']."','".$_SESSION['AUTORIZACIONES']['RANGO']."',
                              ".$_SESSION['AUTORIZACIONES']['SEMANAS'].",
                              '".$_REQUEST['TipoAfiliado']."','".$_REQUEST['Nivel']."',
                              ".$_REQUEST['Semanas'].",'".$_REQUEST['Observacion']."',".UserGetUID().",'now()',
                              ".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'].",".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'].")";
                    $result=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error INSERT INTO auditoria_cambio_datos_bdafiliados";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$dbconn->RollbackTrans();
                        return false;
                    }								
                }
            }

						if(!empty($_SESSION['AUTORIZACIONES']['CAJA']['AUTO']) AND !empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['orden_servicio_id']))
						{
								if($_REQUEST['TipoAfiliado']!=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_afiliado_id']
								OR $_REQUEST['Nivel']!=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['rango']
								OR ($_REQUEST['Semanas']!=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['semanas_cotizadas']))
								{
											$query = "UPDATE os_ordenes_servicios set
																tipo_afiliado_id='".$_REQUEST['TipoAfiliado']."',
																rango='".$_REQUEST['Nivel']."',
																semanas_cotizadas=".$_REQUEST['Semanas']."
																WHERE orden_servicio_id=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['orden_servicio_id']."";
											$result=$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error UPDATE os_ordenes_servicios set";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$dbconn->RollbackTrans();
													return false;
											}		
								}                
						}
						$this->frmError["MensajeError"]="LOS CAMBIOS FUERON SATISFACTORIOS.";						
						$dbconn->CommitTrans();	

            $this->RetornarAutorizacion(true,'ADMITIR','',1);
            return true;
    }


		//Autorizacion Externa que se realiza en SOS

		function LlamarAutorizacionExterna()
		{
				unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['error']);
				unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['mensajeDeError']);
				if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
				{
						echo "Error";
						echo "NO SE PUDO INCLUIR : classes/BDAfiliados/BDAfiliados.class.php";
				}
				if(!class_exists('BDAfiliados'))
				{
						echo "Error";
						echo "NO EXISTE BD AFILIADOS";
				}
				$class= New BDAfiliados($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'],$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'],$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']);
				$Validez=$_REQUEST['Validez'];
				if(!empty($Validez))
				{
						$f=explode('/',$Validez);
						$Validez=$f[2].'-'.$f[1].'-'.$f[0];
				}
				$a=$class->PeticionAutorizacion($_REQUEST['Observaciones'],$Validez,$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']['departamento']);
				if($a==false)
				{
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['error']=$class->error;
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['mensajeDeError']=$class->mensajeDeError;
				}
				if(!empty($a[nmro_vrfcn]) and !($a[nmro_vrfcn]==='0'))
				{
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']['NoAutorizacion']=$a[nmro_vrfcn];
				}
				if($a[nmro_vrfcn]==='0')
				{
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['error']="NO SE HA INICIADO LA SECUENCIA PARA ESTA OFICINA";
					$this->FormaAutorizacionTipo($_REQUEST['TipoAutorizacion']);
					return true;
				}
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['error']=$a[ofcna_usro].' - '.$a[cdgo_ofcna_lg];
				$this->FormaAutorizacionTipo($_REQUEST['TipoAutorizacion']);
        return true;
		}

		/**
		*
		*/
		//cambio sos
		function BuscarPlanOcultar()
		{
			list($dbconn) = GetDBconn();
			$sql="select sw_ocultar from planes_ocultar where plan_id=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'].";";
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}
			if(empty($result->fields[0]))
			{
				return 0;
			}
			return $result->fields[0];
		}

		/**
		*
		*/
		function NextValAutorizacion()
		{
				list($dbconn) = GetDBconn();
				$query="SELECT nextval('autorizaciones_autorizacion_seq')";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				if($result->fields[0] <= 100)
				{
								$msg = "ERROR DE IMPLEMENTACION: LAS AUTORIZACIONES DEBEN EMPEZAR EN 100";
								$this->RetornarAutorizacion(false,'ADMITIR',$msg,$auto);
								//return true;
								$this->mensajeDeError = "ERROR DE IMPLEMENTACION: LAS AUTORIZACIONES DEBEN EMPEZAR EN 100";
								echo $this->mensajeDeError; 
								return false;exit;
				}

				$result->Close();
				return $result->fields[0];
		}

		function NombreCups($cargo)
		{
				list($dbconn) = GetDBconn();
				$sql="select descripcion from cups where cargo='".$cargo."';";
				$result=$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				return $result->fields[0];
		}
//------------------FUNCIONES PARA LOS CAMPOS DE MOSTRAR BD--------------

		function PlantilaBD($plan)
		{
				list($dbconn) = GetDBconn();
				$sql="SELECT plantilla_bd_id FROM plantillas_planes WHERE plan_id=$plan";
				$result=$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				if(!$result->EOF)
				{  $var=$result->fields[0];  }

				$result->Close();
				return $var;
		}


		function CamposMostrarBD($campo,$plantilla)
		{
				list($dbconn) = GetDBconn();
			 	$sql="SELECT nombre_mostrar,sw_mostrar FROM plantillas_detalles
							WHERE descripcion_campo='$campo' AND plantilla_bd_id=$plantilla";
				$result=$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				$var=$result->GetRowAssoc($ToUpper = false);
				$result->Close();
				return $var;
		}

		function DatosAutorizaciones($autorizacion,$tabla)
		{
				list($dbconn) = GetDBconn();
				if($tabla=='autorizaciones_por_sistema')
				{
								$query = "select  b.nombre, a.* from $tabla as a, system_usuarios as b
													where a.autorizacion=$autorizacion and a.usuario_id=b.usuario_id";
				}
				else
				{
							$query = "select * from $tabla where autorizacion=$autorizacion";
				}
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al traer los cargos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
								return false;
				}

				if(!$result->EOF)
				{
						while(!$result->EOF)
						{
										$var[]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
						}
				}
				return $var;
		}

//------------------------------------------------------------------------------------

}//fin clase user

?>

