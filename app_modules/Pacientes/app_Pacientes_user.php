<?php

/**
 * $Id: app_Pacientes_user.php,v 1.35 2007/03/12 21:04:32 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de triage de los pacientes.
 */

/**
* Clase app_Triage_user
*
* Contiene los metodos para realizar el triage y admision de los pacientes
*/

class app_Pacientes_user extends classModulo
{

        var $classVista;
        var $frmError=array();
        var $TipoRetorno;


    /**
    * Es el contructor de la clase
    * @return boolean
    */
    function app_Pacientes_user()
    {
            $this->TipoRetorno=false;
            $this->frmError=array();
            return true;
    }


    /**
    * La funcion main es la principal y donde se llama ListarPacientes
    * que muestra el listado de los pacientes que estan en triage
    * @access public
    * @return boolean
    */
    function main()
    {
            return true;
    }

        /**
        * Llama la forma FormaDatosPacienteCreado
        * @access public
        * @return boolean
        * @param string tipo de documento
        * @param int numero de documento
        * @param int plan_id
        */
        function LlamarFormaDatosPacienteCreado($TipoId,$PacienteId,$PlanId)
        {
                $this->FormaDatosPacienteCreado($this->BuscarPaciente($TipoId,$PacienteId),$PlanId);
                return true;
        }

        /**
        * Llama la forma FormaPedirDatos
        * @access public
        * @return boolean
        */
        function LlamarFormaPedirDatos()
        {
							$TipoId=$_REQUEST['TipoId'];
							$PacienteId=$_REQUEST['PacienteId'];
							$Responsable=$_REQUEST['Responsable'];
							//$Nivel=$_REQUEST['Nivel'];
							$_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$PacienteId;
							$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$TipoId;
							$_SESSION['PACIENTES']['PACIENTE']['plan_id']=$Responsable;
							//$_SESSION['PACIENTES']['PACIENTE']['nivel']=$Nivel;
							if(!empty($_REQUEST['ModificarDatos']))
							{
									$this->FormaModificarDatos($TipoId,$PacienteId,$Responsable);
									return true;
							}
							else
							{
									$this->FormaPedirDatos($TipoId,$PacienteId,$Responsable);
									return true;
							}
        }

        /**
        * Llama el metodo de retorno cuando le dan candelas en algun proceso
        * @access public
        * @return boolean
        */
        function Cancelar()
        {
                $contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
                $modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
                $tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
                $metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
                $argumentos=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
                $_SESSION['PACIENTES']['RETORNO']['PASO']=false;

                $this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
                return true;
        }


    /**
    * Busca el estado del ingreso del paciente
    * @access public
    * @return boolean
    * @param string tipo de documento
    * @param int numero de documento
    * @param string id de la empresa
    * @param int plan_id
    * @param array accion de la forma
    */
    function BuscarIngresoActivoPaciente($TipoDocumento,$Documento,$EmpresaId,$Plan,$accion=array())
    {
					unset($_SESSION['PACIENTES']['REQUEST']);
					list($dbconn) = GetDBconn();
					$query = "SELECT paciente_fallecido,primer_nombre,segundo_nombre,
											primer_apellido,segundo_apellido,tipo_id_paciente, paciente_id
											FROM pacientes
											WHERE paciente_id='$Documento' AND tipo_id_paciente ='$TipoDocumento'";
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					$vars=$resulta->GetRowAssoc($ToUpper = false);

					if($vars[paciente_fallecido])
					{
									$this->FormaMensajePacienteFallecido($vars,$accion);
									return true;
					}

					IncludeLib("funciones_admision");
					$query = " SELECT a.ingreso, c.numerodecuenta
											FROM ingresos as a, cuentas as c
											WHERE a.estado=1 and a.paciente_id='$Documento' AND a.tipo_id_paciente ='$TipoDocumento'
											AND a.ingreso=c.ingreso AND c.empresa_id='$EmpresaId' AND c.estado=1";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					if(!$result->EOF)
					{
									$cuenta=$result->GetRowAssoc($ToUpper = false);

									//buscar paciente que esta en pacientes_urgencias pte que medico lo atienda
									$var=BuscarPacientePteAtencion($EmpresaId,$TipoDocumento,$Documento,'','','');
									if(!empty($var))
									{
											$mensaje='PACIENTE PENDIENTE DE SER ATENDIDO POR EL MEDICO ESTACION '.$var[0][descripcion];
											$this->FormaExisteIngreso($vars,$cuenta,$accion,$mensaje);
											return true;
									}

									//buscar paciente que esta en una estacion
									$var=BuscarPacienteEstacion($EmpresaId,$TipoDocumento,$Documento,'','','');
									if(!empty($var))
									{
											$mensaje='EL PACIENTE ESTA EN LA ESTACION '.$var[0][descripcion];
											$this->FormaExisteIngreso($vars,$cuenta,$accion,$mensaje);
											return true;
									}

									//buscar paciente que esta pte de ser ingressado a la estacion
									$var=BuscarPacientePteIngresar($EmpresaId,$TipoDocumento,$Documento,'','','');
									if(!empty($var))
									{
											$mensaje='EL PACIENTE ESTA PENDIENTE POR INGRESAR EN LA ESTACION '.$var[0][descripcion];
											$this->FormaExisteIngreso($vars,$cuenta,$accion,$mensaje);
											return true;
									}

									//buscar paciente que ha fue dadao de alta en urgencias pero con procesos en al estacion
									$var=PacienteSalidaUrgenciasProcesoEst($EmpresaId,$TipoDocumento,$Documento,'','','');
									if(!empty($var))
									{
											$mensaje='EL PACIENTE ESTA PENDIENTE DE SALIDA EN URGENCIAS, PERO TIENE PROCESOS PENDIENTES EN LA ESTACION';
											$this->FormaExisteIngreso($vars,$cuenta,$accion,$mensaje);
											return true;
									}

									//buscar paciente que ha fue dadao de alta en urgencias
									$var=PacienteSalidaUrgencias($EmpresaId,$TipoDocumento,$Documento,'','','');
									if(!empty($var))
									{
											$mensaje='EL PACIENTE ESTA PENDIENTE DE SALIDA EN URGENCIAS';
											$this->FormaExisteIngreso($vars,$cuenta,$accion,$mensaje);
											return true;
									}
					}

					//------------para buscar si el paciente esta en algun lado--------------


					$accionPac=ModuloGetURL($accion['contenedor'],$accion['modulo'],$accion['tipo'],$accion['metodo']);
					$var='';
					//buscar si esta pendiente de ser clasificaco en un punto
					$var=BuscarPacienteTriage($EmpresaId,$TipoDocumento,$Documento,'','','');
					if(!empty($var))
					{
							$mensaje='EL PACIENTE ESTA PENDIENTE DE SER CLASIFICADO EN EL PUNTO '.$var[0][descripcion];
							if(!$this->FormaMensaje($mensaje,'ADMISIONES - VALIDAR ESTADO DEL PACIENTE',$accionPac,$boton)){
										return false;
							}
							return true;
					}

					//buscar paciente pendiente admitir
					$var=BuscarPacientePteAdmision($EmpresaId,$TipoDocumento,$Documento,'','','');
					if(!empty($var))
					{
							$mensaje='EL PACIENTE ESTA PENDIENTE DE SER ADMITIDO EN EL PUNTO '.$var[0][descripcion];
							if(!$this->FormaMensaje($mensaje,'ADMISIONES - VALIDAR ESTADO DEL PACIENTE',$accionPac,$boton)){
										return false;
							}
							return true;
					}

					//buscar paciente que esta en pacientes_urgencias pte que medico lo atienda
					/*$var=BuscarPacientePteAtencion($EmpresaId,$TipoDocumento,$Documento,'','','');
					if(!empty($var))
					{
							$mensaje='PACIENTE PENDIENTE DE SER ATENDIDO POR EL MEDICO ESTACION '.$var[0][descripcion];
							if(!$this->FormaMensaje($mensaje,'ADMISIONES - VALIDAR ESTADO DEL PACIENTE',$accionPac,$boton)){
										return false;
							}
							return true;
					}*/

					//buscar paciente que esta pte de ser ingressado a la estacion
					/*$var=BuscarPacientePteIngresar($EmpresaId,$TipoDocumento,$Documento,'','','');
					if(!empty($var))
					{
							$mensaje='EL PACIENTE ESTA PENDIENTE POR INGRESAR EN LA ESTACION '.$var[0][descripcion];
							if(!$this->FormaMensaje($mensaje,'ADMISIONES - VALIDAR ESTADO DEL PACIENTE',$accionPac,$boton)){
										return false;
							}
							return true;
					}*/

					//buscar paciente que esta en una estacion
					$var=BuscarPteClasificacionMedica($EmpresaId,$TipoDocumento,$Documento,'','','');
					if(!empty($var))
					{
							$mensaje='PACIENTE PENDIENTE DE SER CLASIFICADO POR EL MEDICO EN LA ESTACION '.$var[0][estacion];
							if(!$this->FormaMensaje($mensaje,'ADMISIONES - VALIDAR ESTADO DEL PACIENTE',$accionPac,$boton)){
										return false;
							}
							return true;
					}

					//buscar paciente que esta en una estacion
					/*$var=BuscarPacienteEstacion($EmpresaId,$TipoDocumento,$Documento,'','','');
					if(!empty($var))
					{
							$mensaje='EL PACIENTE ESTA EN LA ESTACION '.$var[0][descripcion];
							if(!$this->FormaMensaje($mensaje,'ADMISIONES - VALIDAR ESTADO DEL PACIENTE',$accionPac,$boton)){
										return false;
							}
							return true;
					}*/

					//buscar paciente que el asistencial pidio remision
					$var=BuscarPteRemisionMedica($EmpresaId,$TipoDocumento,$Documento,'','','');
					if(!empty($var))
					{
							$mensaje='PACIENTE PENDIENTE DE SER ATENDIDO POR EL MEDICO (SOLICITUD REMISION)';
							if(!$this->FormaMensaje($mensaje,'ADMISIONES - VALIDAR ESTADO DEL PACIENTE',$accionPac,$boton)){
										return false;
							}
							return true;
					}

					//-----------------------------------------------------------------------

					$HomonimosDocumentos=$this->verificarDocumentosHomonimos($TipoDocumento,$Documento);
					if($HomonimosDocumentos===false)
					{
									return false;
					}
					elseif(is_array($HomonimosDocumentos))
					{
									$this->FormaHomonimosDocumento($HomonimosDocumentos,$accion,$Plan,$TipoDocumento,$Documento);
									return true;
					}

					$this->TipoRetorno=true;
					return true;
    }

    /**
    * Va a llamas las forma para pedir los datos del paciente
    * @access public
    * @return boolean
    */
    function PedirDatos()
    {
               if(($_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']!='MS') AND ( $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']!='AS'))
							 {
										if(empty($_SESSION['PACIENTES']['PACIENTE']['paciente_id']) || empty($_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']))
										{
														if($_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']!='MS' || $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']!='AS')
														{
																		$this->error = "PACIENTES ";
																		$this->mensajeDeError = "Los datos de pacientes estan incompletos.";
																		return false;
														}
										}
								}
                if($_SESSION['PACIENTES']['PACIENTE']['plan_id']==-1)
                {
                        $this->error = "PACIENTES";
                        $this->mensajeDeError = "Los datos de pacientes estan incompletos.";
                        return false;
                }

                $PacienteId=$_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
                $TipoId=$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];
                $PlanId=$_SESSION['PACIENTES']['PACIENTE']['plan_id'];

								if(empty($PacienteId))
								{
										$PacienteId=$this->IdentifiacionNN();
										$_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$PacienteId;
								}

								if(!empty($_SESSION['PACIENTES']['PACIENTE']['plan_id']) AND $_SESSION['PACIENTES']['PACIENTE']['plan_id']!=-1) 
								{
										list($dbconn) = GetDBconn();
										$query = "SELECT sw_tipo_plan, sw_afiliacion, protocolos, sw_autoriza_sin_bd
																				FROM planes
																				WHERE estado='1' and plan_id=$PlanId
																				and fecha_final >= now() and fecha_inicio <= now()";
										$results = $dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error al Cargar el Modulo1";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												return false;
										}
										list($TipoPlan,$swAfiliados,$Protocolos,$swAutoSinBD)=$results->FetchRow();
										
										$_SESSION['PACIENTES']['protocolo']=$Protocolos;
										$results->Close();
																						
										if(($TipoPlan==0 AND $swAfiliados==1) OR ($swAfiliados==1))
										{
													if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
													{
															$this->error = "Error";
															$this->mensajeDeError = "No se pudo incluir : classes/notas_enfermeria/revision_sistemas.class.php";
															return false;
													}
													if(!class_exists('BDAfiliados'))
													{
															$this->error="Error";
															$this->mensajeDeError="no existe BDAfiliados";
															return false;
													}
		
													$class= New BDAfiliados($TipoId,$PacienteId,$PlanId);
													if($class->GetDatosAfiliado()==false)
													{
															$this->error=$class->error;
															$this->mensajeDeError=$class->mensajeDeError;
															$this->frmError["MensajeError"]=$class->mensajeDeError;
													}
		
													if(!empty($class->salida))
													{  $_SESSION['PACIENTES']['PACIENTE']['ARREGLO']=$class->salida;  }
										}								
								}//fin condicio si hay plan

                if($TipoId=='AS' || $TipoId=='MS')
                {
                        $this->FormaNN($TipoId,$PacienteId,$PlanId);
                        return true;
                }
                else
                {
                        $this->FormaPedirDatos($TipoId,$PacienteId,$PlanId);
                        return true;
                }
    }

//----------------------------------FIN FORMA PRINCIPAL--------------------------------------------

    /**
    * Llama la forma FormaCambioIdentificacion
    * @access public
    * @return boolean
    */
     function CambiarIdentificacionPaciente()
     {
            $PacienteId=$_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
            $TipoId=$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];

            if(!$this->FormaCambioIdentificacion($TipoId,$PacienteId)){
                    return false;
            }
            return true;
     }

    /**
    * Llama la forma para unificar hisotrias
    * @access public
    * @return boolean
    */
     function UnificarHistorias()
     {
            $PacienteId=$_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
            $TipoId=$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];

            if(!$this->FormaUnificacion($TipoId,$PacienteId)){
                    return false;
            }
            return true;
     }


    /**
    * Llama la forma FormaDatosAcudiente
    * @access public
    * @return boolean
    * @param int ingreso
    */
    function LlamarFormaDatosAcudiente($IngresoId)
    {
							if(empty($IngresoId) AND empty($_SESSION['PACIENTES']['ACUDIENTES']['TMP']))
							{
										$_SESSION['PACIENTES']['RETORNO']['MENSAJE']="EL INGRESO ES OBLIGATORIO PATA LOS DATOS DEL ACUDIENTE.";
										$contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
										$modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
										$tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
										$metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
										$argumentos=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
										$this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
										return true;
							}

            if(!$this->FormaDatosAcudiente($IngresoId)){
                return false;
            }
            return true;
    }

    /**
    * Llama las funciones para validar e inserta los datos del acudiente de un paciente
    * @access public
    * @return boolean
    */
    function CapturaDatosAcudiente()
    {
            $Nombre=$_REQUEST['Nombre'];
            $Parentesco=$_REQUEST['Parentesco'];
            $Observaciones=$_REQUEST['Observaciones'];
            $Direccion=$_REQUEST['Direccion'];
            $Telefono=$_REQUEST['Telefono'];
            $Ingreso=$_REQUEST['Ingreso'];

            $validar=$this->ValidarDatosAcudiente($Nombre,$Parentesco);
            if($validar)
            {
									if($Nombre && $Parentesco!=-1)
									{
													$insertar=$this->InsertarDatosAcudiente($Ingreso,$Nombre,$Parentesco,$Direccion,$Telefono,$Observaciones);
													if($insertar)
													{
																	$contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
																	$modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
																	$tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
																	$metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
																	$argumentos=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
																	$_SESSION['PACIENTES']['RETORNO']['ingreso']=$Ingreso;

																	$this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
																	return true;
													}
													else
													{   return false;  }
									}
									else
									{
											$contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
											$modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
											$tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
											$metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
											$argumentos=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
											$_SESSION['PACIENTES']['RETORNO']['ingreso']=$Ingreso;

											$this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
											return true;
									}
							}
							else
							{
         				 $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
										$this->FormaDatosAcudiente($_REQUEST['Ingreso']);
										return true;
							}
                   /* if($Nombre && $Parentesco!=-1)
                    {
                            $insertar=$this->InsertarDatosAcudiente($Ingreso,$Nombre,$Parentesco,$Direccion,$Telefono,$Observaciones);
                            if($insertar)
                            {
                                    $contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
                                    $modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
                                    $tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
                                    $metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
                                    $argumentos=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
                                    $_SESSION['PACIENTES']['RETORNO']['ingreso']=$Ingreso;

                                    $this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
                                    return true;
                            }
                            else
                            {   return false;  }
                    }
                    else
                    {
                                    $contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
                                    $modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
                                    $tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
                                    $argumentos=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
                                    $metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
                                    $_SESSION['PACIENTES']['RETORNO']['ingreso']=$Ingreso;

                                    $this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
                                    return true;
                    }
            }*/
    }


    /**
    * Valida que los datos del acudiente esten completos
    * @access public
    * @return boolean
    * @param string nombre
    * @param int parentesco
    */
    function ValidarDatosAcudiente($Nombre,$Parentesco)
    {
					$valin = "^([a-zA-Z?-?])* +([a-zA-Z?-?])*$";
					$vali = "^([a-zA-Z?-?])*$";
					$valida = "^([a-zA-Z?-?])* +([a-zA-Z?-?])* +([a-zA-Z?-?])*$";
					$validar = "^([a-zA-Z?-?])* +([a-zA-Z?-?])* +([a-zA-Z?-?])* +([a-zA-Z?-?])*$";
					if(!$Nombre || $Parentesco==-1)
					{
							if($Parentesco==-1){ $this->frmError["Parentesco"]=1; }
							if(!$Nombre){ $this->frmError["Nombre"]=1; }
							return false;
					}
					elseif(eregi($valin,$Nombre) || eregi($vali,$Nombre) || eregi($valida,$Nombre) || eregi($validar,$Nombre))
					{
							return true;
					}
					else
					{
							$this->frmError["Nombre"]=1;
							return false;
					}
    }

    /**
    * Inserta los datos del acudiente de un paciente
    * @access public
    * @return boolean
    * @param int ingreso
    * @param string nombre
    * @param int parentesco
    * @param string direccion
    * @param int telefono
    * @param string observacion
    */
    function InsertarDatosAcudiente($Ingreso,$Nombre,$Parentesco,$Direccion,$Telefono,$Observaciones)
    {
				$Nombre=strtoupper($Nombre);
				list($dbconn) = GetDBconn();
				if(empty($_SESSION['PACIENTES']['ACUDIENTES']['TMP']))
				{
					  $query = "INSERT INTO hc_contactos_paciente(
																ingreso,
																nombre_completo,
																direccion,
																telefono,
																tipo_parentesco_id,
																observaciones)
																VALUES (".$_SESSION['PACIENTES']['ACUDIENTES']['INGRESO'].",'$Nombre','$Direccion','$Telefono','$Parentesco','$Observaciones')";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en la Base de Datos";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}
						return true;
				}
				else
				{
					  $query = "INSERT INTO tmp_hc_contactos_paciente(
																triage_pendiente_admitir_id,
																nombre_completo,
																direccion,
																telefono,
																tipo_parentesco_id,
																observaciones)
																VALUES (".$_SESSION['PACIENTES']['ACUDIENTES']['TMP'].",'$Nombre','$Direccion','$Telefono','$Parentesco','$Observaciones')";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar en la Base de Datos";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}
						return true;
				}
    }

	function BuscarAcudientes()
	{
			list($dbconn) = GetDBconn();
			if(!empty($_SESSION['PACIENTES']['ACUDIENTES']['INGRESO']))
			{
					$query = "SELECT a.nombre_completo,a.telefono,a.direccion,
										b.descripcion, a.contacto_id as contacto
										FROM hc_contactos_paciente as a, tipos_parentescos as b
										WHERE ingreso=".$_SESSION['PACIENTES']['ACUDIENTES']['INGRESO']."
										AND a.tipo_parentesco_id=b.tipo_parentesco_id";
			}
			elseif(!empty($_SESSION['PACIENTES']['ACUDIENTES']['TMP']))
			{
					$query = "SELECT a.nombre_completo,a.telefono,a.direccion,
										b.descripcion, a.tmp_contacto_id as contacto
										FROM tmp_hc_contactos_paciente as a, tipos_parentescos as b
										WHERE triage_pendiente_admitir_id=".$_SESSION['PACIENTES']['ACUDIENTES']['TMP']."
										AND a.tipo_parentesco_id=b.tipo_parentesco_id";
			}
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			while(!$result->EOF)
			{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			$result->Close();
			return $vars;
	}


	function EliminarAcudiente()
	{
			list($dbconn) = GetDBconn();
			if(!empty($_SESSION['ADMISIONES']['INGRESO']['ingreso']))
			{
					$query = "DELETE FROM  hc_contactos_paciente
										WHERE contacto_id=".$_REQUEST['idAcudiente']."";
			}
			elseif(!empty($_SESSION['ADMISIONES']['INGRESO']['ingresotmp']))
			{
					$query = "DELETE FROM tmp_hc_contactos_paciente
										WHERE tmp_contacto_id=".$_REQUEST['idAcudiente']."";
			}
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			$this->frmError["MensajeError"]="EL ACUDIENTE FUE ELIMINADO.";
			$this->FormaDatosAcudiente();
			return true;
	}
    /**
  * Valida los datos de un paciente cuando es creado por primera vez
    * @access public
    * @return boolean
    */
    function ValidarDatosPacienteNew()
    {
						$Forma=$_REQUEST['Forma'];
						$Responsable=$_REQUEST['Responsable'];
						$PacienteId=$_REQUEST['PacienteId'];
						$PrimerApellido=strtoupper($_REQUEST['PrimerApellido']);
						$SegundoApellido=strtoupper($_REQUEST['SegundoApellido']);
						$PrimerNombre=strtoupper($_REQUEST['PrimerNombre']);
						$SegundoNombre=strtoupper($_REQUEST['SegundoNombre']);
						$FechaNacimiento=$_REQUEST['FechaNacimiento'];
            $Direccion=$_REQUEST['Direccion'];
            $Telefono=$_REQUEST['Telefono'];
            $Ocupacion=$_REQUEST['ocupacion_id'];
            $TipoId=$_REQUEST['TipoId'];
            $Sexo=$_REQUEST['Sexo'];
            $EstadoCivil=$_REQUEST['EstadoCivil'];
            $Pais=$_REQUEST['pais'];
            $Dpto=$_REQUEST['dpto'];
            $Mpio=$_REQUEST['mpio'];
            $comuna=$_REQUEST['comuna'];
            $barrio=$_REQUEST['barrio'];
            $estrato=trim($_REQUEST['estrato']);
            $Mama=strtoupper($_REQUEST['Mama']);
            $accion=$_REQUEST['accion'];
						$hc=$_REQUEST['historia'];
						$prefijo=strtoupper($_REQUEST['prefijo']);
            $ZonaResidencia=$_REQUEST['Zona'];
            $FechaNacimientoCalculada=$_REQUEST['FechaNacimientoCalculada'];
            $Edad=$_REQUEST['Edad'];
            $Observaciones=$_REQUEST['Observaciones'];
						$peso=$_REQUEST['Peso'];
						$talla=$_REQUEST['Talla'];
						$LugarExpedicion=$_REQUEST['LugarExpedicion'];
						
            //$Nivel=$_REQUEST['Nivel'];
            $_SESSION['PACIENTES']['DAT']=$_REQUEST;
            //-------------para validar los datos-----------
            $validar=$this->ValidarPaciente($Forma,$Responsable,$TipoId,$PacienteId,$PrimerApellido,$PrimerNombre,$FechaNacimiento,$FechaNacimientoCalculada,$Sexo,$Pais,$Dpto,$Mpio,$estrato,$_REQUEST['barrio'],$_REQUEST['Direccion'],$_REQUEST['Telefono'],$_REQUEST['comuna'],$_REQUEST['prefijo'],$_REQUEST['historia'],$_REQUEST['Observaciones'],$Mama,$_REQUEST['ocupacion_id'],$_REQUEST['EstadoCivil'],$SegundoNombre,$SegundoApellido,$peso,$talla,$LugarExpedicion);
            if($Forma!='FormaNN' && !$validar)
            {
                    $this->frmError["MensajeError"]="Faltan datos obligatorios o Existen Formatos De Fecha Incorrectos1.";
                    if(!$this->FormaPedirDatos($TipoId,$PacienteId,$Responsable)){
                            return false;
                    }
                    return true;
            }
            if($Forma=='FormaNN' && !$validar)
            {
                        $this->frmError["MensajeError"]="Faltan datos obligatorios o Existen Formatos De Fecha Incorrectos2..";
                        if(!$this->FormaNN($TipoId,$PacienteId,$Responsable)){
                                return false;
                        }
                        return true;
            }
            //------------------------------------------------
            $homonimos=array();
            if($TipoId!='AS' && $TipoId!='MS')
            {  $homonimos=$this->verificarNombresHomonimos($TipoId,$PacienteId,$PrimerNombre,$SegundoNombre,$PrimerApellido,$SegundoApellido); }
            if($homonimos){
										$nom=$PrimerNombre." ".$SegundoNombre." ".$PrimerApellido." ".$SegundoApellido;
                    unset($_SESSION['PACIENTES']['REQUEST']);
                    $_SESSION['PACIENTES']['REQUEST']=$_REQUEST;
                    $accion=ModuloGetURL('app','Pacientes','user','TerminarValidarNew');
                    $this->FormaHomonimosNombres($homonimos,$accion,$Plan,$TipoId,$PacienteId,$nom);
                    return true;
            }
            else{
                            $Insertar=$this->InsertarDatosPaciente($PacienteId,$TipoId,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$FechaNacimiento,$FechaNacimientoCalculada,$Direccion,$Telefono,$ZonaResidencia,$Ocupacion,$Sexo,$EstadoCivil,$foto,$Pais,$Dpto,$Mpio,$Mama,$Edad,$Responsable,$Observaciones,$comuna,$barrio,$estrato,$prefijo,$hc,$peso,$talla,$LugarExpedicion);
                            if($Insertar)
                            {
                                    $contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
                                    $modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
                                    $tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
                                    $metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
                                    $argumentos=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
                                    $_SESSION['PACIENTES']['RETORNO']['PASO']=$Insertar;

                                    $this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
                                    return true;
                            }
														else
														{
																	if($Forma!='FormaNN')
																	{
																					$this->frmError["MensajeError"]="ERROR: Formato de fecha incorrecto o la historia ya existe.";
																					if(!$this->FormaPedirDatos($TipoId,$PacienteId,$Responsable)){
																									return false;
																					}
																					return true;
																	}
																	if($Forma=='FormaNN')
																	{
																							$this->frmError["MensajeError"]="ERROR: Formato de fecha incorrecto o la historia ya existe.";
																							if(!$this->FormaNN($TipoId,$PacienteId,$Responsable)){
																											return false;
																							}
																							return true;
																	}
														}
            }
    }

    /**
    * Llama la funcion para inserta  los datod del pacientes
    * @access public
    * @return boolean
    */
    function TerminarValidarNew()
    {
            $_REQUEST=$_SESSION['PACIENTES']['DAT'];
            $Forma=$_REQUEST['Forma'];
            $Responsable=$_REQUEST['Responsable'];
            $PacienteId=$_REQUEST['PacienteId'];
            $PrimerApellido=strtoupper($_REQUEST['PrimerApellido']);
            $SegundoApellido=strtoupper($_REQUEST['SegundoApellido']);
            $PrimerNombre=strtoupper($_REQUEST['PrimerNombre']);
            $SegundoNombre=strtoupper($_REQUEST['SegundoNombre']);
            //$FechaNacimiento=$_REQUEST['FechaNacimiento'];
            //$Direccion=$_REQUEST['Direccion'];
            //$Telefono=$_REQUEST['Telefono'];
            //$Ocupacion=$_REQUEST['ocupacion_id'];
            $TipoId=$_REQUEST['TipoId'];
            //$Sexo=$_REQUEST['Sexo'];
            //$EstadoCivil=$_REQUEST['EstadoCivil'];
            //$Pais=$_REQUEST['pais'];
            //$Dpto=$_REQUEST['dpto'];
            //$Mpio=$_REQUEST['mpio'];
            $Mama=strtoupper($_REQUEST['Mama']);
            $accion=$_REQUEST['accion'];
            //$ZonaResidencia=$_REQUEST['Zona'];
            //$FechaNacimientoCalculada=$_REQUEST['FechaNacimientoCalculada'];
            //$Edad=$_REQUEST['Edad'];
            //$Observaciones=$_REQUEST['Observaciones'];
            //$Nivel=$_REQUEST['Nivel'];
            unset($_SESSION['PACIENTES']['DAT']);
            $Insertar=$this->InsertarDatosPaciente($PacienteId,$TipoId,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$_REQUEST['FechaNacimiento'],$_REQUEST['FechaNacimientoCalculada'],$_REQUEST['Direccion'],$_REQUEST['Telefono'],$_REQUEST['Zona'],$_REQUEST['ocupacion_id'],$_REQUEST['Sexo'],$_REQUEST['EstadoCivil'],$foto,$_REQUEST['pais'],$_REQUEST['dpto'],$_REQUEST['mpio'],$Mama,$_REQUEST['Edad'],$Responsable,$_REQUEST['Observaciones'],$_REQUEST['comuna'],$_REQUEST['barrio'],$_REQUEST['estrato'],$_REQUEST['prefijo'],$_REQUEST['historia'],$peso,$talla);
            if($Insertar)
            {
                    $contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
                    $modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
                    $tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
                    $metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
                    $argumentos=$_SESSION['PACIENTES']['RETORNO']['argumentos'];

                    $this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
                    return true;
            }
              else
              {
                    if($Forma!='FormaNN')
                    {
													$this->frmError["MensajeError"]="ERROR: al guardar el paciente, Revise el formato de la fecha.";
													if(!$this->FormaPedirDatos($TipoId,$PacienteId,$Responsable)){
																	return false;
													}
													return true;
                    }
                    if($Forma=='FormaNN')
                    {
													$this->frmError["MensajeError"]="ERROR: al guardar el paciente, Revise el formato de la fecha.";
													if(!$this->FormaNN($TipoId,$PacienteId,$Responsable)){
																	return false;
													}
													return true;
                    }
              }
    }


    /**
    * Valida que los datos del paciente esten correctos
    * @access public
    * @return boolean
    * @param string nombre de la forma (NN)
    * @param int plan_id
    * @param string tipo de documento
    * @param int numero de documento
    * @param string primer nombre
    * @param string segundo nombre
    * @param string primer apellido
    * @param string segundo apellido
    * @param date fecha nacimiento
    * @param int fecha nacimientocalculada
    * @param string sexo
    * @param string pais
    * @param string dpto
    * @param string ciudad
    */
		function ValidarPaciente($Forma,$Responsable,$TipoId,$PacienteId,$PrimerApellido,$PrimerNombre,$FechaNacimiento,$FechaNacimientoCalculada,$Sexo,$Pais,$Dpto,$Mpio,$estrato,$barrio,$direccion,$tele,$comuna,$prefijo,$hc,$observacion,$mama,$ocupacion,$estado,$segnom,$segape,$peso,$talla,$LugarExpedicion)
		{		
			/*	if(empty($peso)){
					$this->frmError["Peso"]=1;
					return false;
				}
				if(empty($talla)){
					$this->frmError["Talla"]=1;
					return false;
				}*/
				if(!empty($estrato))
				{
						if(is_numeric($estrato)==0)
						{  	return false;    	}

						list($dbconn) = GetDBconn();
						$query = " 	SELECT * FROM tipos_estratos where tipo_estrato_id=$estrato";
						$result=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al eliminar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
						if($result->EOF)
						{  	return false;    	}
				}

				$campo=$this->BuscarCamposObligatorios();
				if($campo[historia_numero][sw_obligatorio]==1 AND $campo[historia_numero][sw_mostrar]==1)
				{
						if(!$hc)
						{
								$this->frmError["historia"]=1;
								return false;
						}
				}
				if($campo[historia_prefijo][sw_obligatorio]==1 AND $campo[historia_prefijo][sw_mostrar]==1)
				{
						$prefijo=strtoupper($prefijo);
						if(!$prefijo)
						{
								$this->frmError["prefijo"]=1;
								return false;
						}
				}

				if($Forma=='FormaNN')
				{
						if($Responsable==-1 || !$FechaNacimientoCalculada || $Sexo==-1 || !$Pais || !$Dpto || !$Mpio)
						{
									if($Responsable==-1){ $this->frmError["Responsable"]=1; }
									if(!$FechaNacimientoCalculada ){ $this->frmError["FechaNacimiento"]=1; }
									if(!$Pais){ $this->frmError["pais"]=1; }
									if(!$Dpto){ $this->frmError["dpto"]=1; }
									if(!$Mpio){ $this->frmError["mpio"]=1; }
									if($Sexo==-1){ $this->frmError["Sexo"]=1; }
									return false;
						}
						else{ return true;}
				}
				else
				{
							if(!empty($FechaNacimiento))
							{
									$val=$this->ValidarFecha($FechaNacimiento);
									if(empty($val))
									{  	return false;    	}
							}

							if($FechaNacimientoCalculada && !$FechaNacimiento)
							{  $FechaNacimiento=$FechaNacimientoCalculada;  }

							if(!$PacienteId || !$TipoId || !$FechaNacimiento || $Sexo==-1 || !$PrimerNombre || !$PrimerApellido)
							{
									if(!$PacienteId){ $this->frmError["PacienteId"]=1; }
									if(!$TipoId){ $this->frmError["TipoId"]=1; }
									if(!$PrimerNombre){ $this->frmError["PrimerNombre"]=1; }
									if(!$PrimerApellido){ $this->frmError["PrimerApellido"]=1; }
									if(!$FechaNacimiento){ $this->frmError["FechaNacimiento"]=1; }
									if($Sexo==-1){ $this->frmError["Sexo"]=1; }

									if($campo[lugar_residencia][sw_obligatorio]==1 AND $campo[lugar_residencia][sw_mostrar]==1)
									{
											if(!$Pais){$this->frmError["pais"]=1;}
											if(!$Dpto){$this->frmError["dpto"]=1;}
											if(!$Mpio){$this->frmError["mpio"]=1;}
									}

									if($campo[residencia_direccion][sw_obligatorio]==1 AND $campo[residencia_direccion][sw_mostrar]==1)
									{  if(!$direccion){$this->frmError["Direccion"]=1;} }
									if($campo[residencia_telefono][sw_obligatorio]==1 AND $campo[residencia_telefono][sw_mostrar]==1)
									{  if(!$tele){$this->frmError["Telefono"]=1;} }
									if($campo[tipo_estrato_id][sw_obligatorio]==1 AND $campo[tipo_estrato_id][sw_mostrar]==1)
									{  if(!$estrato){$this->frmError["estrato"]=1;}  }
									if($campo[tipo_barrio_id][sw_obligatorio]==1 AND $campo[tipo_barrio_id][sw_mostrar]==1)
									{  if(!$barrio){$this->frmError["barrio"]=1;}  }
									if($campo[tipo_comuna_id][sw_obligatorio]==1 AND $campo[tipo_comuna_id][sw_mostrar]==1)
									{  if(!$comuna){$this->frmError["comuna"]=1;}  }
									if($campo[observaciones][sw_obligatorio]==1 AND $campo[observaciones][sw_mostrar]==1)
									{  if(!$mama){$this->frmError["Observaciones"]=1;}  }
									if($campo[nombre_madre][sw_obligatorio]==1 AND $campo[nombre_madre][sw_mostrar]==1)
									{  if(!$mama){$this->frmError["Mama"]=1;}  }
									if($campo[ocupacion_id][sw_obligatorio]==1 AND $campo[ocupacion_id][sw_mostrar]==1)
									{  if(!$ocupacion){$this->frmError["Ocupacion"]=1;}  }
									if($campo[tipo_estado_civil_id][sw_obligatorio]==1 AND $campo[tipo_estado_civil_id][sw_mostrar]==1)
									{  if($estado==-1){$this->frmError["EstadoCivil"]=1;}  }
									if($campo[segundo_nombre][sw_obligatorio]==1 AND $campo[segundo_nombre][sw_mostrar]==1)
									{  if(!$segnom){$this->frmError["SegundoNombre"]=1;}  }
									if($campo[segundo_apellido][sw_obligatorio]==1 AND $campo[segundo_apellido][sw_mostrar]==1)
									{  if(!$segape){$this->frmError["SegundoApellido"]=1;}  }
									//campos peso y talla
									if($campo[peso][sw_obligatorio]==1 AND $campo[peso][sw_mostrar]==1)
									{  if(!$peso){$this->frmError["Peso"]=1;}  }											
									if($campo[talla][sw_obligatorio]==1 AND $campo[talla][sw_mostrar]==1)
									{  if(!$talla){$this->frmError["Talla"]=1;}  }									
									return false;									
							}
							//campos de la tabla
							if($campo[segundo_nombre][sw_obligatorio]==1 AND $campo[segundo_nombre][sw_mostrar]==1)
							{
									if(!$segnom)
									{
											$this->frmError["SegundoNombre"]=1;
											return false;
									}
							}
							if($campo[segundo_apellido][sw_obligatorio]==1 AND $campo[segundo_apellido][sw_mostrar]==1)
							{
									if(!$segape)
									{
											$this->frmError["SegundoApellido"]=1;
											return false;
									}
							}
							if($campo[lugar_residencia][sw_obligatorio]==1 AND $campo[lugar_residencia][sw_mostrar]==1)
							{
									if(!$Pais)
									{
											$this->frmError["pais"]=1;
											return false;
									}
									if(!$Dpto)
									{
											$this->frmError["dpto"]=1;
											return false;
									}
									if(!$Mpio)
									{
											$this->frmError["mpio"]=1;
											return false;
									}

									if($campo[tipo_comuna_id][sw_obligatorio]==1 AND $campo[tipo_comuna_id][sw_mostrar]==1)
									{
											if(!$comuna)
											{
													$this->frmError["comuna"]=1;
													return false;
											}
											if($campo[tipo_barrio_id][sw_obligatorio]==1 AND $campo[tipo_barrio_id][sw_mostrar]==1)
											{
													if(!$barrio)
													{
															$this->frmError["barrio"]=1;
															return false;
													}
											}
									}
							}
							if($campo[residencia_direccion][sw_obligatorio]==1 AND $campo[residencia_direccion][sw_mostrar]==1)
							{
									if(!$direccion)
									{
											$this->frmError["Direccion"]=1;
											return false;
									}
							}
											if($campo[residencia_telefono][sw_obligatorio]==1 AND $campo[residencia_telefono][sw_mostrar]==1)
							{
									if(!$tele)
									{
											$this->frmError["Telefono"]=1;
											return false;
									}
							}
							if($campo[tipo_estrato_id][sw_obligatorio]==1 AND $campo[tipo_estrato_id][sw_mostrar]==1)
							{
									if(!$estrato)
									{
											$this->frmError["estrato"]=1;
											return false;
									}
							}
							if($campo[ocupacion_id][sw_obligatorio]==1 AND $campo[ocupacion_id][sw_mostrar]==1)
							{
									if(!$ocupacion)
									{
											$this->frmError["Ocupacion"]=1;
											return false;
									}
							}
							if($campo[nombre_madre][sw_obligatorio]==1 AND $campo[nombre_madre][sw_mostrar]==1)
							{
									if(!$mama)
									{
											$this->frmError["Mama"]=1;
											return false;
									}
							}
							if($campo[tipo_estado_civil_id][sw_obligatorio]==1 AND $campo[tipo_estado_civil_id][sw_mostrar]==1)
							{
									if($estado==-1)
									{
											$this->frmError["EstadoCivil"]=1;
											return false;
									}
							}
							if($campo[observaciones][sw_obligatorio]==1 AND $campo[observaciones][sw_mostrar]==1)
							{
									if(!$observacion)
									{
											$this->frmError["Observaciones"]=1;
											return false;
									}
							}
							//campos peso y talla
							if($campo[peso][sw_obligatorio]==1 AND $campo[peso][sw_mostrar]==1)
							{  
									if(!$peso)
									{
											$this->frmError["Peso"]=1;
											return false;
									}  
							}											
							if($campo[talla][sw_obligatorio]==1 AND $campo[talla][sw_mostrar]==1)
							{  
									if(!$talla)
									{
											$this->frmError["Talla"]=1;
											return true;
									}  
							}
//LUGAR EXPEDICION
//$_SESSION[PACIENTES][RETORNO][modulo]; //Soat
							if(!empty($Responsable))
							{
								$TipoPlan=$this->NombrePlan($Responsable);
								if($TipoPlan[sw_tipo_plan]=='1')
								{ 
										if(!$LugarExpedicion)
										{
											$this->frmError["LugarExpedicion"]=1;
											return false;
										}
								}
								else
								{
									if($campo[lugar_expedicion_documento][sw_mostrar]==1)
									{
										if($campo[lugar_expedicion_documento][sw_obligatorio]==1 AND !$LugarExpedicion)
										{
											$this->frmError["LugarExpedicion"]=1;
											return false;
										}
									}
								}
							}
							else
							{
								if($campo[lugar_expedicion_documento][sw_mostrar]==1)
								{
									if($campo[lugar_expedicion_documento][sw_obligatorio]==1 AND !$LugarExpedicion)
									{
										$this->frmError["LugarExpedicion"]=1;
										return false;
									}
								}
							}
//FIN LUGAR EXPEDICION
							
							return true;
				}
		}

		/**
		*
		*/
		function BuscarCamposObligatorios()
		{
					list($dbconn) = GetDBconn();
					$query="SELECT campo,sw_mostrar,sw_obligatorio FROM pacientes_campos_obligatorios";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}

					while(!$result->EOF){
							$var[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}

					$result->Close();
					return $var;
		}


	/**
	* Inserta los datos de un paciente cuando es creado por primera vez
	* @access public
	* @return boolean
	*/
	function InsertarDatosPaciente($PacienteId,$TipoId,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$FechaNacimiento,$FechaNacimientoCalculada,$Direccion,$Telefono,$Zona,$Ocupacion,$Sexo,$EstadoCivil,$foto,$Pais,$Dpto,$Mpio,$Mama,$Edad,$Responsable,$Observaciones,$comuna,$barrio,$estrato,$prefijo,$hc,$peso,$talla,$LugarExpedicion)
	{
				list($dbconn) = GetDBconn();
				$prefijo=strtoupper($prefijo);
				if(!empty($prefijo) OR !empty($hc))
				{
						$query = "SELECT * FROM historias_clinicas
						WHERE historia_prefijo='$prefijo' AND historia_numero='$hc'
						AND tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'";
						$result=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar SELECT en historias_clinicas";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}
						if(!$result->EOF)
						{
								$this->frmError["historia"]=1;
								return false;
						}
				}

				$prefijo=strtoupper($prefijo);
				unset($_SESSION['SELECTO']);
				if(!$EstadoCivil || $EstadoCivil==-1)
				{  $EstadoCivil='NULL';  }
				else
				{  $EstadoCivil="'$EstadoCivil'";  }
				if(!$Ocupacion || $Ocupacion==-1)
				{  $Ocupacion='NULL';  }
				else
				{  $Ocupacion="'$Ocupacion'";  }

				if(!$comuna || $comuna==0)
				{  $comuna='NULL';  }
				else
				{  $comuna="'$comuna'";  }
				if(!$barrio || $barrio==0)
				{  $barrio='NULL';  }
				else
				{  $barrio="'$barrio'";  }

				if(!$Pais)
				{  $Pais='NULL';  }
				else
				{  $Pais="'$Pais'";  }
				if(!$Dpto)
				{  $Dpto='NULL';  }
				else
				{  $Dpto="'$Dpto'";  }
				if(!$Mpio)
				{  $Mpio='NULL';  }
				else
				{  $Mpio="'$Mpio'";  }

				if(!$estrato)
				{  $estrato='NULL';  }

				if(!$LugarExpedicion)
				{  $LugarExpedicion='NULL';  }
				else
				{  $LugarExpedicion="'$LugarExpedicion'";  }

				$EdadCalculada=$FechaNacimientoCalculada;
				if($FechaNacimientoCalculada && !$FechaNacimiento)
				{
								if($Edad==1) {  $tipo='dias';   }
								if($Edad==2) {  $tipo='meses';    }
								if($Edad==3) {  $tipo='anos';       }
								$FechaNacimiento=CalcularFechaNacimiento($FechaNacimientoCalculada,$tipo);
								/*if($Edad==1) {  $edad=$FechaNacimientoCalculada/365;   }
								if($Edad==2) {  $edad=$FechaNacimientoCalculada/12;    }
								if($Edad==3) {  $edad=$FechaNacimientoCalculada;       }
								$FechaNacimiento=CalcularFecha($edad);
								$FechaNacimiento=$FechaNacimiento[dias]."/".$FechaNacimiento[meses]."/".$FechaNacimiento[a?os];
								*/
								$FechaNacimientoCalculada=1;
				}
				elseif(!$FechaNacimientoCalculada && $FechaNacimiento)
				{  $FechaNacimientoCalculada=0; }
				elseif($FechaNacimientoCalculada && $FechaNacimiento)
				{  $FechaNacimientoCalculada=0; }

				if($Edad==1)
				{
						$FechaNacimiento=date("d/m/Y",strtotime("-".$EdadCalculada." days",strtotime(date("Y-m-d"))));
				}

				$f=explode('/',$FechaNacimiento);
				$FechaNacimiento=$f[2].'-'.$f[1].'-'.$f[0];
				if(empty($Zona))
				{ $Zona='U'; }

				$SystemId=UserGetUID();
				$FechaRegistro=date("Y-m-d H:i:s");
				$dbconn->BeginTrans();
				
				if(!empty($talla))
				{  $this->VerificaMetricaPaciente($TipoId,$PacienteId,'talla',$talla,&$dbconn);  }
				if(!empty($peso))
				{  $this->VerificaMetricaPaciente($TipoId,$PacienteId,'peso',$peso,&$dbconn);  }
				$query = "INSERT INTO pacientes (
																			paciente_id,
																			tipo_id_paciente,
																			primer_apellido,
																			segundo_apellido,
																			primer_nombre,
																			segundo_nombre,
																			fecha_nacimiento,
																			fecha_nacimiento_es_calculada,
																			residencia_direccion,
																			residencia_telefono,
																			zona_residencia,
																			ocupacion_id,
																			fecha_registro,
																			sexo_id,
																			tipo_estado_civil_id,
																			foto,
																			tipo_pais_id,
																			tipo_dpto_id,
																			tipo_mpio_id,
																			nombre_madre,
																			usuario_id,
																			observaciones,
																			tipo_comuna_id,
																			tipo_barrio_id,
																			tipo_estrato_id,
																			lugar_expedicion_documento)
									VALUES ('$PacienteId','$TipoId','$PrimerApellido','$SegundoApellido','$PrimerNombre','$SegundoNombre','$FechaNacimiento','$FechaNacimientoCalculada','$Direccion','$Telefono','$Zona',$Ocupacion,'$FechaRegistro','$Sexo',$EstadoCivil,'$foto',$Pais,$Dpto,$Mpio,'$Mama',$SystemId,'$Observaciones',$comuna,$barrio,$estrato,$LugarExpedicion)";
	
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
							// $this->error = "Error INSERT INTO pacientes";
							// $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
				}
				else
				{
							$query = "SELECT * FROM historias_clinicas WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
							$result=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
											//$this->error = "Error al Guardar SELECT en historias_clinicas";
											//$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
							}
							else
							{
									if($result->EOF)
									{
														$query = "INSERT INTO historias_clinicas( tipo_id_paciente,
																																			paciente_id,
																																			historia_numero,
																																			historia_prefijo,
																																			fecha_creacion)
																		VALUES ('$TipoId','$PacienteId','$hc','$prefijo','$FechaRegistro')";
													$dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0) {
																	//$this->error = "Error INSERT INTO historias_clinicas";
																	//$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																	$dbconn->RollbackTrans();
																	return false;
													}
													else
													{
																	$_SESSION['PACIENTES']['RETORNO']['PASO']=TRUE;
																	$dbconn->CommitTrans();
																	return true;
													}
									}
									else
									{
													$_SESSION['PACIENTES']['RETORNO']['PASO']=TRUE;
													$dbconn->CommitTrans();
													return true;
									}
							}
				}
		}


    /**
  * Actualiza los datos del paciente cuando ya esta en el registro
    * @access public
    * @return boolean
    */
    function ActualizarDatosPaciente()
    {
            $Responsable=$_REQUEST['Responsable'];
            $PacienteId=$_REQUEST['PacienteId'];
            $PrimerApellido=strtoupper($_REQUEST['PrimerApellido']);
            $SegundoApellido=strtoupper($_REQUEST['SegundoApellido']);
            $PrimerNombre=strtoupper($_REQUEST['PrimerNombre']);
            $SegundoNombre=strtoupper($_REQUEST['SegundoNombre']);
            $FechaNacimiento1=$_REQUEST['FechaNacimiento1'];
            $FechaNacimiento=$_REQUEST['FechaNacimiento'];
            $FechaNacimientoCalculada=$_REQUEST['FechaNacimientoCalculada'];
            $Direccion=$_REQUEST['Direccion'];
            $Telefono=$_REQUEST['Telefono'];
            $Ocupacion=$_REQUEST['ocupacion_id'];
            $FechaRegistro=$_REQUEST['FechaRegistro'];
            $TipoId=$_REQUEST['TipoId'];
            $Sexo=$_REQUEST['Sexo'];
            $EstadoCivil=$_REQUEST['EstadoCivil'];
            $Pais=$_REQUEST['pais'];
            $Dpto=$_REQUEST['dpto'];
            $Mpio=$_REQUEST['mpio'];
            $Mama=strtoupper($_REQUEST['Mama']);
            $Zona=$_REQUEST['Zona'];
            $Edad=$_REQUEST['Edad'];
						$Forma=$_REQUEST['Forma'];
						$peso=$_REQUEST['Peso'];
						$talla=$_REQUEST['Talla'];
						$LugarExpedicion=$_REQUEST['LugarExpedicion'];

            $validar=$this->ValidarPaciente($Forma,$Responsable,$TipoId,$PacienteId,$PrimerApellido,$PrimerNombre,$FechaNacimiento,$FechaNacimientoCalculada,$Sexo,$Pais,$Dpto,$Mpio,trim($_REQUEST['estrato']),$_REQUEST['barrio'],$_REQUEST['Direccion'],$_REQUEST['Telefono'],$_REQUEST['comuna'],$_REQUEST['prefijo'],$_REQUEST['historia'],$_REQUEST['Observaciones'],$Mama,$_REQUEST['ocupacion_id'],$_REQUEST['EstadoCivil'],$SegundoNombre,$SegundoApellido,$peso,$talla,$LugarExpedicion);

            if($Forma!='FormaNN' && !$validar)
            {
                    $this->frmError["MensajeError"]="Faltan datos obligatorios o Existen Formatos De Fecha Incorrectos3.";
                    if(!$this->FormaPedirDatos($TipoId,$PacienteId,$Responsable)){
                            return false;
                    }
                    return true;
            }
            if($Forma=='FormaNN' && !$validar)
            {
                        $this->frmError["MensajeError"]="Faltan datos obligatorios o Existen Formatos De Fecha Incorrectos4..";
                        if(!$this->FormaNN($TipoId,$PacienteId,$Responsable)){
                                return false;
                        }
                        return true;
            }

            /*if(!$validar)
            {
                    $this->frmError["MensajeError"]="Faltan datos obligatorios o Existen Formatos Incorrectos..";
                    if(!$this->FormaPedirDatos($TipoId,$PacienteId,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$FechaNacimiento,$FechaNacimientoCalculada,$Direccion,$Telefono,$ZonaResidencia,$Ocupacion,$FechaRegistro,$Sexo,$EstadoCivil,$Foto,$Pais,$Dpto,$Mpio,$mensaje,$accion,$Existe,$Responsable,$Afiliado,$Mama)){
                            return false;
                    }
                    return true;
            }*/
            $Update=$this->ActualizarPaciente($TipoId,$PacienteId,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$FechaNacimiento,$FechaNacimientoCalculada,$Direccion,$Telefono,$Zona,$Ocupacion,$Sexo,$EstadoCivil,$foto,$Pais,$Dpto,$Mpio,$Mama,$Edad,$_REQUEST['comuna'],$_REQUEST['barrio'],trim($_REQUEST['estrato']),$_REQUEST['Observaciones'],$_REQUEST['prefijo'],$_REQUEST['historia'],$peso,$talla,$LugarExpedicion);
            if($Update)
            {
                    $contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
                    $modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
                    $tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
                    $metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
                    $argumentos=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
                    $_SESSION['PACIENTES']['RETORNO']['PASO']=$Update;
					$this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
                    return true;
            }
            else
            {
										if($Forma!='FormaNN' && !$Update)
										{
																$this->frmError["MensajeError"]="ERROR: al actualizar el paciente revise el formato de la fecha o la Historia ya Existe.";
																if(!$this->FormaPedirDatos($TipoId,$PacienteId,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$FechaNacimiento,$FechaNacimientoCalculada,$Direccion,$Telefono,$ZonaResidencia,$Ocupacion,$FechaRegistro,$Sexo,$EstadoCivil,$Foto,$Pais,$Dpto,$Mpio,$mensaje,$accion,$Existe,$Responsable,$Afiliado,$Mama)){
																				return false;
																}
																return true;
										}
										if($Forma=='FormaNN' && !$Update)
										{
																$this->frmError["MensajeError"]="ERROR: al actualizar el paciente revise el formato de la fecha o la Historia ya Existe.";
																if(!$this->FormaNN($TipoId,$PacienteId,$Responsable)){
																				return false;
																}
																return true;
										}
            }
    }

    function ModificarDatosPaciente()
    {
            $Responsable=$_REQUEST['Responsable'];
            $PacienteId=$_REQUEST['PacienteId'];
            $PrimerApellido=strtoupper($_REQUEST['PrimerApellido']);
            $SegundoApellido=strtoupper($_REQUEST['SegundoApellido']);
            $PrimerNombre=strtoupper($_REQUEST['PrimerNombre']);
            $SegundoNombre=strtoupper($_REQUEST['SegundoNombre']);


            $FechaNacimiento1=$_REQUEST['FechaNacimiento1'];
            //$FechaNacimiento=$_REQUEST['FechaNacimiento'];
            //$FechaNacimientoCalculada=$_REQUEST['FechaNacimientoCalculada'];
            $Direccion=$_REQUEST['Direccion'];
            $Telefono=$_REQUEST['Telefono'];
            $Ocupacion=$_REQUEST['ocupacion_id'];
            $FechaRegistro=$_REQUEST['FechaRegistro'];
            $TipoId=$_REQUEST['TipoId'];
            //$Sexo=$_REQUEST['Sexo'];
            $EstadoCivil=$_REQUEST['EstadoCivil'];
            //$Pais=$_REQUEST['pais'];
            //$Dpto=$_REQUEST['dpto'];
            //$Mpio=$_REQUEST['mpio'];
            $Mama=strtoupper($_REQUEST['Mama']);
            $Zona=$_REQUEST['Zona'];
            $Edad=$_REQUEST['Edad'];
						$peso=$_REQUEST['Peso'];
						$talla=$_REQUEST['Talla'];
						$LugarExpedicion=$_REQUEST['LugarExpedicion'];

            $validar=$this->ValidarModificarPaciente($PrimerApellido,$PrimerNombre,$_REQUEST['FechaNacimiento'],$_REQUEST['FechaNacimientoCalculada'],$_REQUEST['Sexo'],$_REQUEST['dpto'],$_REQUEST['mpio'],trim($_REQUEST['estrato']),$_REQUEST['prefijo'],$_REQUEST['historia'],$peso,$talla,$LugarExpedicion);
            if(!$validar)
            {
										$this->frmError["MensajeError"]="Faltan datos obligatorios o Existen Formatos De Fecha Incorrectos5.";
										if(!$this->FormaModificarDatos($TipoId,$PacienteId)){
														return false;
										}
										return true;
            }

            $Update=$this->ActualizarPaciente($TipoId,$PacienteId,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$_REQUEST['FechaNacimiento'],$_REQUEST['FechaNacimientoCalculada'],$Direccion,$Telefono,$Zona,$Ocupacion,$_REQUEST['Sexo'],$EstadoCivil,$foto,$_REQUEST['pais'],$_REQUEST['dpto'],$_REQUEST['mpio'],$Mama,$Edad,$_REQUEST['comuna'],$_REQUEST['barrio'],trim($_REQUEST['estrato']),$_REQUEST['Observaciones'],$_REQUEST['prefijo'],$_REQUEST['historia'],$peso,$talla,$LugarExpedicion);
            if($Update)
            {
										$contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
										$modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
										$tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
										$metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
										$argumentos=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
										$_SESSION['PACIENTES']['RETORNO']['PASO']=$Update;

										$this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
										return true;
            }
            else
            {
										$this->frmError["MensajeError"]="ERROR: al actualizar el paciente revise el formato de la fecha o la Historia ya Existe.";
										if(!$this->FormaModificarDatos($TipoId,$PacienteId,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$FechaNacimiento,$FechaNacimientoCalculada,$Direccion,$Telefono,$ZonaResidencia,$Ocupacion,$FechaRegistro,$Sexo,$EstadoCivil,$Foto,$Pais,$Dpto,$Mpio,$mensaje,$accion,$Existe,$Responsable,$Afiliado,$Mama)){
														return false;
										}
										return true;
            }
    }

		function ValidarModificarPaciente($PrimerApellido,$PrimerNombre,$FechaNacimiento,$FechaNacimientoCalculada,$Sexo,$Dpto,$Mpio,$estrato,$prefijo,$hc,$peso,$talla,$LugarExpedicion)
		{
				if(!empty($estrato))
				{
						if(is_numeric($estrato)==0)
						{  	return false;    	}

						list($dbconn) = GetDBconn();
						$query = " 	SELECT * FROM tipos_estratos where tipo_estrato_id=$estrato";
						$result=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {							
								$this->error = "Error al eliminar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
						if($result->EOF)
						{  	return false;    	}
				}

				$campo=$this->BuscarCamposObligatorios();
				if($campo[historia_numero][sw_obligatorio]==1 AND $campo[historia_numero][sw_mostrar]==1)
				{
						if(!$hc)
						{
								$this->frmError["historia"]=1;
								return false;
						}
				}
				if($campo[historia_prefijo][sw_obligatorio]==1 AND $campo[historia_prefijo][sw_mostrar]==1)
				{
						$prefijo=strtoupper($prefijo);
						if(!$prefijo)
						{
								$this->frmError["prefijo"]=1;
								return false;
						}
				}

				if($campo[lugar_expedicion_documento][sw_obligatorio]==1 AND $campo[lugar_expedicion_documento][sw_mostrar]==1)
				{
						if(!$LugarExpedicion)
						{
								$this->frmError["LugarExpedicion"]=1;
								return false;
						}
				}

				if(!empty($FechaNacimiento))
				{
						$val=$this->ValidarFecha($FechaNacimiento);
						if(empty($val))
						{  return false;    	}
				}

				if($FechaNacimientoCalculada && !$FechaNacimiento)
				{  $FechaNacimiento=$FechaNacimientoCalculada;  }
				//comentado por saca error y estos datos no son obligatorios
				//empty($peso) ||empty($talla)
				if(!$FechaNacimiento || $Sexo==-1 || !$PrimerNombre || !$PrimerApellido || empty($Mpio) || empty($Dpto))
				{
							if(!$PrimerNombre){ echo '1';$this->frmError["PrimerNombre"]=1; }
							if(!$PrimerApellido){ echo '2';$this->frmError["PrimerApellido"]=1; }
							if(!$FechaNacimiento){ echo '3';$this->frmError["FechaNacimiento"]=1; }
							if($Sexo==-1){ echo '4';$this->frmError["Sexo"]=1; }
							if(!$Dpto){echo '5';$this->frmError["dpto"]=1;}
							if(!$Mpio){echo '6';$this->frmError["mpio"]=1;}
							//if(!$peso){echo '7';$this->frmError["Peso"]=1;}
							//if(!$talla){echo '8';$this->frmError["Talla"]=1;}							
							return false;
				}
				return true;
		}


    /**
    * Actualiza los datos del paciente
    * @access public
    * @return boolean
    * @param string tipo de documento
    * @param int numero de documento
    * @param string primer nombre
    * @param string segundo nombre
    * @param string primer apellido
    * @param string segundo apellido
    * @param date fecha nacimiento
    * @param int fecha nacimientocalculada
    * @param string direccion
    * @param int telefono
    * @param string zona de residencia
    * @param int ocupacion
    * @param string sexo
    * @param string estado civil
    * @param string pais
    * @param string dpto
    * @param string municipio
    * @param string nombre de la madre
    * @param int edad
    */
    function ActualizarPaciente($TipoId,$PacienteId,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$FechaNacimiento,$FechaNacimientoCalculada,$Direccion,$Telefono,$Zona,$Ocupacion,$Sexo,$EstadoCivil,$foto,$Pais,$Dpto,$Mpio,$Mama,$Edad,$comuna,$barrio,$estrato,$obs,$prefijo,$hc,$peso,$talla,$LugarExpedicion)
    {
             unset($_SESSION['SELECTO']);    
            if(!$EstadoCivil || $EstadoCivil==-1)
            {  $EstadoCivil='NULL';  }
            else
            {  $EstadoCivil="'$EstadoCivil'";  }
            if(!$Ocupacion || $Ocupacion==-1)
            {  $Ocupacion='NULL';  }
            else
            {  $Ocupacion="'$Ocupacion'";  }

						if(!$comuna || $comuna==0)
						{  $comuna='NULL';  }
						else
						{  $comuna="'$comuna'";  }
						if(!$barrio || $barrio==0)
						{  $barrio='NULL';  }
						else
						{  $barrio="'$barrio'";  }

						if(empty($Pais))
						{  $Pais='NULL';  }
						else
						{  $Pais="'$Pais'";  }
						if(!$Dpto)
						{  $Dpto='NULL';  }
						else
						{  $Dpto="'$Dpto'";  }
						if(!$Mpio)
						{  $Mpio='NULL';  }
						else
						{  $Mpio="'$Mpio'";  }

						if(!$estrato)
						{  $estrato='NULL';  }

						if(!$LugarExpedicion)
						{  $LugarExpedicion='NULL';  }
						else
						{  $LugarExpedicion="'$LugarExpedicion'";  }

            if($FechaNacimientoCalculada)
            {
                    if($Edad==1) {  $tipo='dias';   }
                    if($Edad==2) {  $tipo='meses';    }
                    if($Edad==3) {  $tipo='anos';       }							
										$FechaNacimiento=CalcularFechaNacimiento($FechaNacimientoCalculada,$tipo);
                   /* if($Edad==1) {  $edad=$FechaNacimientoCalculada/365;   }
                    if($Edad==2) {  $edad=$FechaNacimientoCalculada/12;    }
                    if($Edad==3) {  $edad=$FechaNacimientoCalculada;       }										
                    $FechaNacimiento=CalcularFecha($edad);
                    $FechaNacimiento=$FechaNacimiento[dias]."/".$FechaNacimiento[meses]."/".$FechaNacimiento[anos];
										*/
                    $FechaNacimientoCalculada=1;
            }
            elseif(!$FechaNacimientoCalculada && $FechaNacimiento)
            {  $FechaNacimientoCalculada=0; }
            elseif($FechaNacimientoCalculada && $FechaNacimiento)
             {  $FechaNacimientoCalculada=0; }

            $f=explode('/',$FechaNacimiento);
            $FechaNacimiento=$f[2].'-'.$f[1].'-'.$f[0];
						$prefijo=strtoupper($prefijo);

            list($dbconn) = GetDBconn();
						$dbconn->BeginTrans();
						if(!empty($talla))
						{  $this->VerificaMetricaPaciente($TipoId,$PacienteId,'talla',$talla,&$dbconn);  }
						if(!empty($peso))
						{  $this->VerificaMetricaPaciente($TipoId,$PacienteId,'peso',$peso,&$dbconn);  }
								
            $query = "UPDATE pacientes SET
                                    primer_apellido='$PrimerApellido',
                                    segundo_apellido='$SegundoApellido',
                                    primer_nombre='$PrimerNombre',
                                    segundo_nombre='$SegundoNombre',
                                    fecha_nacimiento='$FechaNacimiento',
                                    fecha_nacimiento_es_calculada='$FechaNacimientoCalculada',
                                    residencia_direccion='$Direccion',
                                    residencia_telefono='$Telefono',
                                    zona_residencia='$Zona',
                                    ocupacion_id=$Ocupacion,
                                    sexo_id='$Sexo',
                                    tipo_estado_civil_id=$EstadoCivil,
                                    foto='$foto',
                                    tipo_pais_id=$Pais,
                                    tipo_dpto_id=$Dpto,
                                    tipo_mpio_id=$Mpio,
                                    tipo_comuna_id=$comuna,
                                    tipo_barrio_id=$barrio,
																		tipo_estrato_id=$estrato,
																		observaciones='$obs',
                                    nombre_madre='$Mama',
                                    fecha_registro='now()',
                                    usuario_id=".UserGetUID().",
                                    lugar_expedicion_documento=$LugarExpedicion
                              WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de DatosUPDATE pacientes";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$dbconn->RollbackTrans();
                    return false;
            }

						$query = "SELECT historia_numero,historia_prefijo FROM historias_clinicas
										  WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
						$result=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Guardar SELECT en historias_clinicas";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
						}
						//no hay hc
						if($result->EOF)
						{
										$query = "INSERT INTO historias_clinicas( tipo_id_paciente,
																																paciente_id,
																																historia_numero,
																																historia_prefijo,
																																fecha_creacion)
																				VALUES ('$TipoId','$PacienteId','$hc','$prefijo','now()')";
										$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
														$this->error = "Error INSERT INTO historias_clinicas";
														$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														$dbconn->RollbackTrans();
														return false;
										}
						}
						else
						{  //ya existe numero de historia del paciente
								if((empty($result->fields[0]) AND empty($result->fields[1])))
								{
											$query = "UPDATE historias_clinicas SET historia_prefijo='$prefijo',
																historia_numero='$hc'
																WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
											$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0) {
															$this->error = "Error INSERT INTO historias_clinicas";
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															$dbconn->RollbackTrans();
															return false;
											}
								}

								//0 hc 1 prefijo
								if(!empty($result->fields[0]) AND empty($result->fields[1]))
								{
													$query = "UPDATE historias_clinicas SET historia_prefijo='".strtoupper($prefijo)."'
																		WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
													$dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0) {
																	$this->error = "Error INSERT INTO historias_clinicas";
																	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																	$dbconn->RollbackTrans();
																	return false;
													}
								}
								elseif(empty($result->fields[0]) AND !empty($result->fields[1]))
								{
											$query = "UPDATE historias_clinicas SET historia_numero='$hc'
																WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
											$dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0) {
															$this->error = "Error INSERT INTO historias_clinicas";
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															$dbconn->RollbackTrans();
															return false;
											}
								}
						}

						$dbconn->CommitTrans();
						return true;
    }



    /**
    * Busca la identificacion del nn
    * @access public
    * @return int
    */
  	function  IdentifiacionNN()
    {
					list($dbconn) = GetDBconn();
						//$query="SELECT nextval('disparadornn')";
						$query="SELECT max(paciente_id) FROM pacientes
						WHERE tipo_id_paciente='MS' OR tipo_id_paciente='AS'";
					$result=$dbconn->Execute($query);

					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					return $result->fields[0]+1;
    }

    /**
    * Valida por nombre si el paciente tiene homonimos
    * @access public
    * @return array
    * @param string tipo de documento
    * @param int numero de documento
    * @param string primer nombre
    * @param string segundo nombre
    * @param string primer apellido
    * @param string segundo apellido
    */
    function verificarNombresHomonimos($tipoDocumento,$numeroDocumento,$primerNombre,$segundoNombre,$primerApellido,$segundoApellido)
    {
        $primerApellido=strtoupper($primerApellido);
        $segundoApellido=strtoupper($segundoApellido);
        $primerNombre=strtoupper($primerNombre);
        $segundoNombre=strtoupper($segundoNombre);

        list($dbconn) = GetDBconn();
        $query  = "SELECT * FROM pacientes
        WHERE (primer_apellido LIKE '%$primerApellido%') AND (primer_nombre LIKE '%$primerNombre%')
        AND paciente_id!='$numeroDocumento'";
        $result= $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        if(!$result->EOF)
        {
              while(!$result->EOF)
              {
                      $var[]=$result->GetRowAssoc(false);
                      $result->MoveNext();
              }
        }

/*
        if($segundoApellido!="" OR $segundoNombre!="" OR $primerApellido!="" OR $primerNombre!="")
        {
            if($segundoApellido=="" AND $segundoNombre==""){
                $query  = "SELECT * FROM pacientes WHERE (primer_apellido LIKE '$primerApellido' OR segundo_apellido LIKE '$primerApellido') AND (primer_nombre LIKE '$primerNombre' OR segundo_nombre LIKE '$primerNombre') AND paciente_id!='$numeroDocumento'";
                $result= $dbconn->Execute($query);
                $datos=$result->RecordCount();
                    //Pregunta si el parametro de segundo apellido es nulo para realizar el select con los datos existentes
                }elseif($segundoApellido==""){
                    $query  = "SELECT * FROM pacientes WHERE (primer_apellido LIKE '$primerApellido' OR segundo_apellido LIKE '$primerApellido') AND (primer_nombre LIKE '$primerNombre' OR segundo_nombre LIKE '$primerNombre') AND(primer_nombre LIKE '$segundoNombre' OR segundo_nombre LIKE '$segundoNombre' OR segundo_nombre LIKE '') AND paciente_id!='$numeroDocumento'";
                    $result = $dbconn->Execute($query);
                    $datos=$result->RecordCount();
                    //Pregunta si el parametro de segundo nombre es nulo para realizar el select con los datos existentes
                }elseif($segundoNombre==""){
                    $query  = "SELECT * FROM pacientes WHERE (primer_apellido LIKE '$primerApellido' OR segundo_apellido LIKE '$primerApellido') AND (primer_apellido LIKE '$segundoApellido' OR segundo_apellido LIKE '$segundoApellido' OR segundo_apellido LIKE '') AND (primer_nombre LIKE '$primerNombre' OR segundo_nombre LIKE '$primerNombre') AND paciente_id!='$numeroDocumento'";
                    $result = $dbconn->Execute($query);
                    $datos=$result->RecordCount();
                    //si todos los datos estos completo realiza el select con todos los parametros
                }else{
                    $query  = "SELECT * FROM pacientes WHERE (primer_apellido LIKE '$primerApellido' OR segundo_apellido LIKE '$primerApellido') AND (primer_apellido LIKE '$segundoApellido' OR segundo_apellido LIKE '$segundoApellido' OR segundo_apellido LIKE '') AND ((primer_nombre LIKE '$primerNombre' OR segundo_nombre LIKE '$primerNombre') OR (primer_nombre LIKE '$segundoNombre' OR segundo_nombre LIKE '$segundoNombre' OR segundo_nombre LIKE '')) AND paciente_id!='$numeroDocumento'";
                    $result = $dbconn->Execute($query);
                    $datos=$result->RecordCount();
                }
         }
*/
        return $var;
 }

    /**
    * Valida por documento si el paciente tiene homonimos
    * @access public
    * @return array
    * @param string tipo de documento
    * @param int numero de documento
    */
    function verificarDocumentosHomonimos($tipoDocumento,$numeroDocumento)
    {
            list($dbconn) = GetDBconn();
            $query  = "SELECT paciente_id,tipo_id_paciente,primer_nombre,segundo_nombre,primer_apellido,segundo_apellido
                                    FROM pacientes
                                    WHERE paciente_id='$numeroDocumento' AND tipo_id_paciente!='$tipoDocumento'";
            $result = $dbconn->Execute($query);

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

                while(!$result->EOF)
                {
                        $var[]=$result->GetRowAssoc(false);
                        $result->MoveNext();
            }
                return $var;
    }

    /**
    * Busca si el paciente tiene cuentas por cobrar
    * @access public
    * @return array
    * @param string tipo tercero
    * @param int id tercero
    */
    function CuentasxCobrar($Tipo,$Tercero)
    {
        list($dbconn) = GetDBconn();
        $query = "    select a.valor, a.saldo, a.fecha_vence, b.razon_social, c.descripcion
                      from cuentasxcobrar as a, empresas as b, centros_utilidad as c
                      where a.tipo_id_tercero='$Tipo' and a.tercero_id='$Tercero' and a.empresa_id='01'
                      and a.empresa_id=b.empresa_id and b.empresa_id=c.empresa_id and
                      a.centro_utilidad=c.centro_utilidad and a.saldo!=0";
        $result=$dbconn->Execute($query);
        $i=0;
        while(!$result->EOF)
        {
                $var[$i]=$result->GetRowAssoc($ToUpper = false);
                $i++;
                $result->MoveNext();
        }
        return $var;
    }


    /**
    * Busca si el paciente tiene cuentas inactivas
    * @access public
    * @return boolean
    * @param string tipo de documento
    * @param int numero de documento
    */
    function BuscarCuentasInactivas($TipoId,$PacienteId)
    {
                list($dbconn) = GetDBconn();
                $query = "select b.numerodecuenta
                                    from ingresos as a, cuentas as b
                                    where a.paciente_id='$PacienteId' and a.tipo_id_paciente='$TipoId'
                                    and a.ingreso=b.ingreso and b.estado=2";
                $result=$dbconn->Execute($query);
                $i=0;
                while(!$result->EOF)
                {
                        $var[$i]=$result->GetRowAssoc($ToUpper = false);
                        $i++;
                        $result->MoveNext();
            }
      return $var;
    }


    /**
    * Busca el nombre del pais
    * @access public
    * @return array
    * @param int codigo del pais
    */
    function nombre_pais($Pais)
  {
            list($dbconn) = GetDBconn();
            $query = "SELECT pais FROM tipo_pais WHERE tipo_pais_id='$Pais'";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{

                    if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
                        return false;
                    }
                }
                $result->Close();
        return $result->fields[0];
    }

    /**
    * Busca el nombre del departamento
    * @access public
    * @return array
    * @param int codigo del pais
  * @param int codigo del departamento
    */
    function nombre_dpto($Pais,$Dpto)
  {
            list($dbconn) = GetDBconn();
            $query = "SELECT * FROM tipo_dptos WHERE tipo_pais_id='$Pais' AND tipo_dpto_id='$Dpto'";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{

                    if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
                        return false;
                    }
                }
                $result->Close();
        return $result->fields[2];
    }

    /**
    * Busca el nombre de la ciudad o municipio
    * @access public
    * @return array
    * @param int codigo del pais
  * @param int codigo del departamento
    * @param int codigo del municipio
    */
    function nombre_ciudad($Pais,$Dpto,$Mpio)
  {
            list($dbconn) = GetDBconn();
            $query = "SELECT * FROM tipo_mpios WHERE tipo_pais_id='$Pais' AND tipo_dpto_id='$Dpto' AND tipo_mpio_id='$Mpio'";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{

                    if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
                        return false;
                    }
                }
                $result->Close();
        return $result->fields[3];
    }

		function nombre_comuna($Pais,$Dpto,$Mpio,$comuna)
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT comuna FROM tipo_comunas
				WHERE tipo_pais_id='$Pais' AND tipo_dpto_id='$Dpto' AND tipo_mpio_id='$Mpio' AND tipo_comuna_id='$comuna'";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				else{
						if($result->EOF){
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
								return false;
						}
				}
				$result->Close();
        return $result->fields[0];
    }

		function nombre_barrio($Pais,$Dpto,$Mpio,$comuna,$barrio)
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT barrio FROM tipo_barrios
				WHERE tipo_pais_id='$Pais' AND tipo_dpto_id='$Dpto' AND tipo_mpio_id='$Mpio' AND tipo_comuna_id='$comuna' AND tipo_barrio_id='$barrio'";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				else{
						if($result->EOF){
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
								return false;
						}
				}
//				$result->Close();
        return $result->fields[0];
    }

    /**
    * Busca la descripcion del plan
    * @access public
    * @return string
    * @param string plan_id
    */
  function NombrePlan($Responsable)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT plan_descripcion, sw_tipo_plan FROM planes WHERE plan_id='$Responsable'";
            $result = $dbconn->Execute($query);
            //$NomTercero=$result->fields[0];
            $NomTercero=$result->GetRowAssoc($ToUpper = false);
            return $NomTercero;
    }


    /**
    * Busca el nombre del tipo de identificacion de un paciente
    * @access public
    * @return string
    * @param string tipo de documento
    */
    function mostrar_id_paciente($TipoId)
  {
            list($dbconn) = GetDBconn();
            $query = "SELECT descripcion FROM tipos_id_pacientes WHERE tipo_id_paciente='$TipoId'";
            $result = $dbconn->Execute($query);
            $datos=$result->RecordCount();

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{
                    if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
                        return false;
                    }
                }
                if($datos){
                $Tipo=$result->fields[0];
                $result->Close();
                }
        return $Tipo;
    }

    /**
    * Busca los diferentes tipos de sexo utilizados en la aplicacion
    * @access public
    * @return array
    */
  function sexo()
  {
            list($dbconn) = GetDBconn();
            $result="";
            $query = "SELECT sexo_id,descripcion
                      FROM tipo_sexo WHERE sexo_id<>0
                      ORDER BY indice_de_orden";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{
                    if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
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
    * Busca los diferentes tipos de estado civil utilizados en la aplicacion
    * @access public
    * @return array
    */
    function estadocivil()
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT * FROM tipo_estado_civil WHERE tipo_estado_civil_id!=0 ORDER BY indice_de_orden";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{

                    if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
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
    * Busca los diferentes tipos ocupaciones de los pacientes
    * @access public
    * @return array
    */
  function ocupacion()
  {
            list($dbconn) = GetDBconn();
            $query = "SELECT ocupacion_id,ocupacion_descripcion FROM ocupaciones ORDER BY ocupacion_descripcion asc";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{

                    if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'tipo_sexo' esta vacia ";
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

  function NombreOcupacion($Ocupacion)
  {
            list($dbconn) = GetDBconn();
            $query = "SELECT ocupacion_descripcion FROM ocupaciones
                                WHERE ocupacion_id='$Ocupacion'";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                return $result->fields[0];
    }



    /**
    * Busca si el paciente que se va ha ingresar ya existe en el registro,
    * si esta en la lista de afiliados entregada por su EPS o si la
    * EPS no envia base de datos de sus afiliados y si es particular
    * @access public
    * @return boolean
    */
    function BuscarPaciente($TipoDocumento,$Documento)
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT a.*, b.historia_numero, b.historia_prefijo
									FROM pacientes as a LEFT JOIN historias_clinicas as b ON
											(a.tipo_id_paciente=b.tipo_id_paciente
											AND a.paciente_id=b.paciente_id)
                 WHERE a.tipo_id_paciente='$TipoDocumento' AND  a.paciente_id='$Documento'
								 --AND b.tipo_id_paciente='$TipoDocumento' AND  b.paciente_id='$Documento'
								 
								 ";
            $result = $dbconn->Execute($query);
       if ($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
				if($result->EOF)
				{
					return false;
				}
				else
				{
					$var=$result->GetRowAssoc($ToUpper = false);
					
					$query="SELECT	 tipo_metrica_id, valor_metrica
									FROM		 pacientes_metricas
									WHERE		 tipo_id_paciente='$TipoDocumento' AND  
													 paciente_id='$Documento'";
					$result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						else{
							while(!$result->EOF)
							{
								$var[$result->fields[0]].=$result->fields[1];
								$result->MoveNext();
							}
						}
					//print_r($var);
					return $var;
				}
    }


    /**
    * Busca los tipos de zonas de residencia
    * @access public
    * @return array
    */
     function ZonasResidencia()
     {
                list($dbconn) = GetDBconn();
                $query="SELECT zona_residencia,descripcion FROM zonas_residencia";
                $result=$dbconn->Execute($query);
                $i=0;
                while(!$result->EOF)
                {
                        $zonas[$i]=$result->GetRowAssoc($ToUpper = false);
                        $i++;
                        $result->MoveNext();
            }
      return $zonas;
     }


    /**
    * Busca el nombre de la zona de residencia
    * @access public
    * @return boolean
    * @param string zona residencia
    */
     function NombreZona($ZonaResidencia)
     {
                list($dbconn) = GetDBconn();
                $query="SELECT zona_residencia,descripcion FROM zonas_residencia
                                        WHERE zona_residencia='$ZonaResidencia'";
                $result=$dbconn->Execute($query);
          return $result->fields[1];
     }

    /**
    * Busca el nombre del tipo de sexo
    * @access public
    * @return boolean
    * @param string sexo
    */
  function NombreSexoPac($Sexo)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT descripcion FROM tipo_sexo WHERE sexo_id='$Sexo'";
            $result = $dbconn->Execute($query);
    return $result->fields[0];
    }

    /**
    * Busca la descripcion de tipo de sexo especifico
    * @access public
    * @return string
    * @param string id del estado del civil
    */
  function NombreEstadoCivil($Estado)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT descripcion FROM tipo_estado_civil WHERE tipo_estado_civil_id='$Estado'";
            $result = $dbconn->Execute($query);
    return $result->fields[0];
    }



    /**
    * Busca los tipos de parentescos
    * @access public
    * @return array
    */
    function TiposParentescos()
    {
                list($dbconn) = GetDBconn();
                $query = "select tipo_parentesco_id,descripcion
                                    from tipos_parentescos";
                $result=$dbconn->Execute($query);
                while(!$result->EOF)
                {
                        $var[]=$result->GetRowAssoc($ToUpper = false);
                        $result->MoveNext();
            }
      return $var;
    }


    /**
    * Unifica la historia clinica (Actualiza)
    * @access public
    * @return boolean
    */
    function UnificarHistoriasClinicas()
    {
          $PacienteIdN=$_REQUEST['PacienteId'];
          $TipoIdN=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId1'];
          $TipoId=$_REQUEST['TipoId1'];

          if(!$PacienteIdN || !$TipoIdN){
	          if(!$PacienteIdN){ $this->frmError["PacienteId"]=1; }
               if(!$TipoIdN){ $this->frmError["TipoId"]=1; }
               $this->frmError["MensajeError"]="Digite el n?mero del documento.";
               $this->FormaUnificacion($TipoId,$PacienteId);
               return true;
          }

          if($PacienteIdN==$PacienteId AND $TipoIdN==$TipoId){
               if(!$PacienteIdN){ $this->frmError["PacienteId"]=1; }
               if(!$TipoIdN){ $this->frmError["TipoId"]=1; }
               $this->frmError["MensajeError"]="Debe ser otra identificaci?n diferente a la actual.";
               $this->FormaUnificacion($TipoId,$PacienteId);
               return true;
          }

          list($dbconn) = GetDBconn();
          $query = "select * from pacientes where paciente_id='$PacienteIdN' AND tipo_id_paciente='$TipoIdN'";
          $result = $dbconn->Execute($query);
          if($result->EOF)
          {
               $this->frmError["MensajeError"]="Debe Digitar un N?mero de Documento Existente.";
               $this->FormaUnificacion($TipoId,$PacienteId);
               return true;
          }

          $dbconn->BeginTrans();
          $query = "select tipo_id_paciente from hc_recomendaciones where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE hc_recomendaciones SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error hc_recomendaciones";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from gestacion where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE gestacion SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error gestacion";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from cronicos where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE cronicos SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error cronicos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from hc_control_protocolos where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE hc_control_protocolos SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error hc_control_protocolos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from hc_vacunas_cumplidas where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE hc_vacunas_cumplidas SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error hc_vacunas_cumplidas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from ingresos where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE ingresos SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error ingresos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from programacion_cx where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE programacion_cx SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error programacion_cx";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from agenda_citas_asignadas where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE agenda_citas_asignadas SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                    	WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error agenda_citas_asignadas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from soat_eventos where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE soat_eventos SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error soat_eventos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from caja_ordenes_pago where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE caja_ordenes_pago SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error caja_ordenes_pago";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from hc_pacientes_hemoclasificacion where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE hc_pacientes_hemoclasificacion SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error hc_pacientes_hemoclasificacion";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from os_ordenes_servicios where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE os_ordenes_servicios SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error os_ordenes_servicios";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from os_cumplimientos where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE os_cumplimientos SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error os_cumplimientos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from hc_os_solicitudes_manuales where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE hc_os_solicitudes_manuales SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                                   $this->error = "Error hc_os_solicitudes_manuales";
                                   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                   $dbconn->RollbackTrans();
                                   return false;
               }
          }

          $query = "select tipo_id_paciente from hc_resultados where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE hc_resultados SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error caja_ordenes_pago";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from banco_sangre_reserva where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE banco_sangre_reserva SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error caja_ordenes_pago";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from tmp_solicitud_manual where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE tmp_solicitud_manual SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error caja_ordenes_pago";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from pagare_cuenta where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE pagare_cuenta SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error caja_ordenes_pago";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          $query = "select tipo_id_paciente from pacientes_grupo_sanguineo where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE pacientes_grupo_sanguineo SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error caja_ordenes_pago";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
				
          $query = "select tipo_id_paciente from remisiones_pacientes where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE remisiones_pacientes SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error caja_ordenes_pago";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
				
          $query = "select tipo_id_paciente from egresos_no_atencion where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE egresos_no_atencion SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error caja_ordenes_pago";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }				
          
          $query = "select tipo_id_paciente from hc_os_solicitudes where paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $result = $dbconn->Execute($query);
          if(!$result->EOF)
          {
               $query = "UPDATE hc_os_solicitudes SET
                                tipo_id_paciente='$TipoIdN',
                                paciente_id='$PacienteIdN'
                         WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error hc_os_solicitudes";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
          
          $query = "DELETE FROM  historias_clinicas
                           WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error historias_clinicas";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }

          $query = "DELETE FROM pacientes
                           WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error pacientes";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }

          $dbconn->CommitTrans();
          $mensaje='La Unificaci?n de Historia se realiz? correctamente.';
          $contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
          $modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
          $tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
          $metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
          $accion=ModuloGetURL($contenedor,$modulo,$tipo,$metodo);
          if(!$this->FormaMensaje($mensaje,'UNIFICACION HISTORIAS',$accion,$boton)){
                              return false;
          }
          return true;
    }


    /**
  * Modifica la identificacion de un paciente
    * @access public
    * @return boolean
    */
    function ModificarIdentificacionPaciente()
    {
					$PacienteIdN=$_REQUEST['PacienteId'];
					$TipoIdN=$_REQUEST['TipoId'];
					$PacienteId=$_REQUEST['PacienteId1'];
					$TipoId=$_REQUEST['TipoId1'];

					if(!$PacienteIdN || !$TipoIdN){
											if(!$PacienteIdN){ $this->frmError["PacienteId"]=1; }
											if(!$TipoIdN){ $this->frmError["TipoId"]=1; }
													$this->frmError["MensajeError"]="Digite el n?mero del documento.";
													$this->FormaCambioIdentificacion($TipoId,$PacienteId);
													return true;
					}

					list($dbconn) = GetDBconn();
					$query = "select * from pacientes WHERE paciente_id='$PacienteIdN' AND tipo_id_paciente='$TipoIdN'";
					$result=$dbconn->Execute($query);

					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
					}
					if(!$result->EOF){
													$this->frmError["MensajeError"]="EL paciente ya esta registrado.";
													$this->FormaCambioIdentificacion($TipoId,$PacienteId);
													return true;
					}

					$query = "UPDATE pacientes SET
													tipo_id_paciente='$TipoIdN',
													paciente_id='$PacienteIdN'
														WHERE paciente_id='$PacienteId' AND tipo_id_paciente='$TipoId'";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Guardar en la Base de Datos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
					}

					$mensaje='La actualizaci?n se realiz? correctamente.';
					$contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
					$modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
					$tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
					$metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
					$accion=ModuloGetURL($contenedor,$modulo,$tipo,$metodo);
					if(!$this->FormaMensaje($mensaje,'CAMBIO DE IDENTIFICACION',$accion,$boton)){
									return false;
					}
					return true;
    }

    /**
    * Busca los diferentes tipos de identificacion de los paciente
    * @access public
    * @return array
    */
    function tipo_id_paciente()
  {
            list($dbconn) = GetDBconn();
            $query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
            $result = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{
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
		*
		*/
		function Estratos()
		{
			list($dbconn) = GetDBconn();
			$query = " 	SELECT * FROM tipos_estratos";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al eliminar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while(!$result->EOF)
			{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			return $var;
		}


  /**
  *
  */
  function ValidarFecha($fecha)
  {
      $x=explode("/",$fecha);
			//0 dias 1 mes 2 a?o
      if(strlen ($x[2])!=4 OR is_numeric($x[2])==0)
      {
          $this->frmError["MensajeError"]="Formato de Fecha Incorrecto ";
					$this->frmError["FechaNacimiento"]=1;
          return false;
      }
      if(strlen ($x[1])>2 OR is_numeric($x[1])==0 OR $x[1]==0 OR $x[1]>12)
      {
          $this->frmError["MensajeError"]="Formato de Fecha Incorrecto ";
					$this->frmError["FechaNacimiento"]=1;
          return false;
      }
      if(strlen ($x[0])>2 OR is_numeric($x[0])==0 OR $x[0]==0)
      {
          $this->frmError["MensajeError"]="Formato de Fecha Incorrecto ";
					$this->frmError["FechaNacimiento"]=1;
          return false;
      }
      return true;
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

		//MauroB
		function ConsultaUnidad($id){
			$query="SELECT unidad FROM tipos_metricas WHERE tipo_metrica_id = '$id'";
			list($dbconn) = GetDBconn();
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}
				
				$var=$result->GetRowAssoc($ToUpper = false);
				$result->Close();
				return $var;
		}
		/**
		* Verifica si las metrica del paciente ya fue ingresada
		*/
		function VerificaMetricaPaciente($tipo_id_paciente,$paciente_id,$tipo_metrica,$valor,&$dbconn){
			$query="SELECT valor_metrica
							FROM  	pacientes_metricas
							WHERE tipo_metrica_id = '$tipo_metrica' AND
										paciente_id =	'$paciente_id' AND
										tipo_id_paciente= '$tipo_id_paciente'";
			//list($dbconn) = GetDBconn();
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				return false;
			}else{
				if($result->EOF){//ingresa nuevo valor
					$this->IngresaMetrica($tipo_id_paciente,$paciente_id,$tipo_metrica,$valor,&$dbconn);
				}else{//modifica valor antiguo
					$this->ActualizaMetrica($tipo_id_paciente,$paciente_id,$tipo_metrica,$valor,&$dbconn);
				}
			}
			$result->Close();
		}
		/**
		* Inserta nueva metrica
		*/
		function IngresaMetrica($tipo_id_paciente,$paciente_id,$tipo_metrica,$valor,&$dbconn){
			$query="INSERT INTO pacientes_metricas (paciente_id,tipo_id_paciente,tipo_metrica_id,valor_metrica,fecha_registro,sw_calculada,usuario_id )
							VALUES ('$paciente_id','$tipo_id_paciente','$tipo_metrica',$valor,'now()','0',".UserGetUID().")";
			//list($dbconn) = GetDBconn();
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				return false;
			}
		}
		/**
		* Actualiza la metrica anterior
		*/
		function ActualizaMetrica($tipo_id_paciente,$paciente_id,$tipo_metrica,$valor,&$dbconn){
			 $query ="	UPDATE pacientes_metricas  
											SET valor_metrica=$valor,
													fecha_registro='now()',
													sw_calculada ='0',
													usuario_id =".UserGetUID()."
											WHERE paciente_id='$paciente_id' AND 
														tipo_id_paciente='$tipo_id_paciente' AND
														tipo_metrica_id='$tipo_metrica'
						";
			//list($dbconn) = GetDBconn();
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				return false;
			}
			return true;
		}
		//fin MAuroB

//----------------------------------------------------------------------------------------

}//fin clase user

?>

