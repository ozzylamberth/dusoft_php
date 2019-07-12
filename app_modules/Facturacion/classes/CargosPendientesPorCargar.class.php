<?php
  /******************************************************************************
  * $Id: CargosPendientesPorCargar.class.php,v 1.1 2007/07/30 18:41:25 carlos Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.1 $ 
  * 
  ********************************************************************************/
  IncludeClass('app_Facturacion_userclasses_HTML','','app','Facturacion');

  class CargosPendientesPorCargar
  {
    function CargosPendientesPorCargar(){}
			/**
			* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
			* @return array
			*/
			function TiposDeSalas()
			{
					$query = "SELECT tipo_sala_id,descripcion
										FROM qx_tipos_salas";
					if(!$rst = $this->ConexionBaseDatos($query))
						return false;
					if($rst->RecordCount())
					{
						while (!$rst->EOF)
						{
										$vars[]=$rst->GetRowAssoc($toUpper=false);
										$rst->MoveNext();
						}
					}
					$rst->Close();
					return $vars;
			}

			function InsertarPendientesCargar($obj)
			{
					$FormaCuenta = new app_Facturacion_userclasses_HTML();
						//IncludeLib('funciones_facturacion');
						$f=0;
						$arreglo='';
						foreach($_REQUEST as $k => $v){
								if(substr_count($k,'cargo')){
										//0tarifario 1 cargo 2cups 3int 4ext 5tipotercero 6tercero
										$var=explode('||',$v);

										$arreglo[]=array('cargo'=>$var[1],'tarifario'=>$var[0],'servicio'=>$_REQUEST['servicio'],'aut_int'=>$var[3],'aut_ext'=>$var[4],'tipo_tercero'=>$var[5],'tercero'=>$var[6],'cups'=>$var[2],'cantidad'=>1,'departamento'=>$_REQUEST['departamento'],'sw_cargue'=>3);
										$f++;
								}
						}
						if($f==0){
								$mensaje="ERROR DATOS VACIOS: DEBE ELEGIR ALGUN CARGO EQUIVALENTE.";
								$FormaCuenta->FormaCuenta($_REQUEST[Cuenta],$_REQUEST[TipoId],$_REQUEST[PacienteId],$_REQUEST[Nivel],$_REQUEST[PlanId],$_REQUEST[Cama],$_REQUEST[Fecha],$_REQUEST[Ingreso],'',$_REQUEST[Transaccion],$mensaje,'');
								return $FormaCuenta->salida;
						}
						$arregloUnico=$arreglo[0];
						list($dbconn) = GetDBconn();
						$query="SELECT sw_tipo_cargo
						FROM hc_sub_procedimientos_realizados_cups_dpto
						WHERE cargo_cups='".$arreglo[0]['cups']."'";
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
								$this->error = "Error al Cargar el Modulo";
							echo	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}else{

								if($result->fields[0]=='QX'){
										if($_REQUEST['TipoSala']==-1){
												$mensaje="ERROR DATOS VACIOS: SELECCIONE EL TIPO DE SALA.";
												$FormaCuenta->FormaCuenta($_REQUEST[Cuenta],$_REQUEST[TipoId],$_REQUEST[PacienteId],$_REQUEST[Nivel],$_REQUEST[PlanId],$_REQUEST[Cama],$_REQUEST[Fecha],$_REQUEST[Ingreso],'',$_REQUEST[Transaccion],$mensaje,'');
												return $FormaCuenta->salida;
										}
										$arregloUnico=$arreglo[0];
										//Modificacion de Lorena para la liquidacion de procedimientos QX
										$dbconn->BeginTrans();
										$arr=$_SESSION['DATOS_ARREGLO']['CARGOS_PENDIENTES_CARGAR_CUENTA'][0];

										(list($fech,$hour)=explode(' ',$arr['fecha']));
										(list($ano,$mes,$dia)=explode('-',$fech));
										(list($hh,$mm)=explode(':',$hour));
										$_SESSION['Liquidacion_QX']['FECHA_CIRUGIA']=$dia.'/'.$mes.'/'.$ano;
										$_SESSION['Liquidacion_QX']['HORA_INICIO']=$hh;
										$_SESSION['Liquidacion_QX']['MIN_INICIO']=$mm;
										$_SESSION['Liquidacion_QX']['HORA_DURACION']=0;
										$_SESSION['Liquidacion_QX']['MIN_DURACION']=0;
										if(!$arr['tipo_sala_id']){$arr['tipo_sala_id']=$_REQUEST['TipoSala'];}
										$_SESSION['Liquidacion_QX']['TIPO_SALA']=$_REQUEST['TipoSala'];
										$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$arr['tipo_id_tercero'].'||//'.$arr['tercero_id'].'||//'.$arr['nombre_tercero']][0]=$arr['cargo_cups'].'||//'.$arr['descups'].'||//'.'0';
										$_SESSION['LIQUIDACION_QX']['Departamento']=$arr['departamento'];
										$_SESSION['LIQUIDACION_QX']['Empresa']=$_SESSION['CUENTAS']['EMPRESA'];
										$_SESSION['Liquidacion_QX']['FINALIDAD_CIRUGIA']='2';
										$_SESSION['Liquidacion_QX']['AMBITO_CIRUGIA']='01';
										$_SESSION['Liquidacion_QX']['VIA_ACCESO']='1';
										$_SESSION['Liquidacion_QX']['TIPO_CIRUGIA']='01';
										unset($_SESSION['Liquidacion_QX']['LIQUIDACION_ID']);
										if($this->CallMetodoExterno('app','DatosLiquidacionQX','user','LlamaGuardarDatosCuentaLiquidacion')===true){

												$query="UPDATE cuentas_liquidaciones_qx SET sw_derechos_cirujano='1',sw_derechos_anestesiologo='1',
												sw_derechos_ayudante='1',sw_derechos_sala='1',
												sw_derechos_materiales='1',sw_equipos_medicos='1',sw_medicamentos_consumo='1'
												WHERE cuenta_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."'";
												$result = $dbconn->Execute($query);
												if($dbconn->ErrorNo() != 0){
														$this->error = "Error al Cargar el Modulo";
													echo	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														$dbconn->RollbackTrans();
														return false;
												}else{
														$query="SELECT consecutivo_procedimiento FROM   cuentas_liquidaciones_qx_procedimientos
														WHERE cuenta_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."' AND cargo_cups='".$arr['cargo_cups']."'";
														$result = $dbconn->Execute($query);
														if($dbconn->ErrorNo() != 0){
																$this->error = "Error al Cargar el Modulo";
														echo	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																$dbconn->RollbackTrans();
																return false;
														}
														$consecutivo=$result->fields[0];
														$query="INSERT INTO cuentas_liquidaciones_qx_procedimientos_cargos(
														consecutivo_procedimiento,tarifario_id,cargo,sw_bilateral)VALUES
														('".$consecutivo."','".$arregloUnico['tarifario']."','".$arregloUnico['cargo']."','0')";
														$result = $dbconn->Execute($query);
														if($dbconn->ErrorNo() != 0){
																$this->error = "Error al Cargar el Modulo";
														echo	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																$dbconn->RollbackTrans();
																return false;
														}else{
																if (!IncludeClass("LiquidacionQX")){
																		//$this->frmError["MensajeError"]=$a->ErrMsg();
																		$mensaje = $a->ErrMsg();
																		$FormaCuenta->FormaCuenta($_REQUEST[Cuenta],$_REQUEST[TipoId],$_REQUEST[PacienteId],$_REQUEST[Nivel],$_REQUEST[PlanId],$_REQUEST[Cama],$_REQUEST[Fecha],$_REQUEST[Ingreso],'',$_REQUEST[Transaccion],$mensaje,'');
																		return $FormaCuenta->salida;
																}else{
																		$a= new LiquidacionQX;
																		if($a->SetDatosLiquidacion($_SESSION['Liquidacion_QX']['LIQUIDACION_ID'])===false){
																				//$this->frmError["MensajeError"]=$a->ErrMsg();
																				$mensaje = $a->ErrMsg();
																				$FormaCuenta->FormaCuenta($_REQUEST[Cuenta],$_REQUEST[TipoId],$_REQUEST[PacienteId],$_REQUEST[Nivel],$_REQUEST[PlanId],$_REQUEST[Cama],$_REQUEST[Fecha],$_REQUEST[Ingreso],'',$_REQUEST[Transaccion],$mensaje,'');
																				return $FormaCuenta->salida;
																		}else{
																				if(($retorno = $a->GetLiquidacion())===false){
																						//$this->frmError["MensajeError"]=$a->ErrMsg();
																						$mensaje = $a->ErrMsg();
																						$FormaCuenta->FormaCuenta($_REQUEST[Cuenta],$_REQUEST[TipoId],$_REQUEST[PacienteId],$_REQUEST[Nivel],$_REQUEST[PlanId],$_REQUEST[Cama],$_REQUEST[Fecha],$_REQUEST[Ingreso],'',$_REQUEST[Transaccion],$mensaje,'');
																						return $FormaCuenta->salida;
																				}else{
																						if(is_array($retorno)){
																								$_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS']=$retorno;
																								$query='';
																								if($obj->CallMetodoExterno('app','DatosLiquidacionQX','user','LlamaGuardarCuentaDetalle')===true){
																										$FormaCuenta->FormaCuenta($_REQUEST[Cuenta],$_REQUEST[TipoId],$_REQUEST[PacienteId],$_REQUEST[Nivel],$_REQUEST[PlanId],$_REQUEST[Cama],$_REQUEST[Fecha],$_REQUEST[Ingreso],'',$_REQUEST[Transaccion],$mensaje,'');
																										return true;
																								}else{
																										//$this->frmError["MensajeError"]="Imposible Liquidar Este Cargo.";
																										$mensaje = "Imposible Liquidar Este Cargo.";
																										$FormaCuenta->FormaCuenta($_REQUEST[Cuenta],$_REQUEST[TipoId],$_REQUEST[PacienteId],$_REQUEST[Nivel],$_REQUEST[PlanId],$_REQUEST[Cama],$_REQUEST[Fecha],$_REQUEST[Ingreso],'',$_REQUEST[Transaccion],$mensaje,'');
																										return $FormaCuenta->salida;
																								}
																						}else{
																								//$this->frmError["MensajeError"]="No se liquido ningun Procedimiento.";
																								$mensaje = "No se liquido ningun Procedimiento.";
																								$FormaCuenta->FormaCuenta($_REQUEST[Cuenta],$_REQUEST[TipoId],$_REQUEST[PacienteId],$_REQUEST[Nivel],$_REQUEST[PlanId],$_REQUEST[Cama],$_REQUEST[Fecha],$_REQUEST[Ingreso],'',$_REQUEST[Transaccion],$mensaje,'');
																								return $FormaCuenta->salida;
																						}
																				}
																		}
																}
														}
												}
										}
										//fin modificacion
								}else{
										$insertar = InsertarCuentasDetalle($_REQUEST['empresa'],$_REQUEST['cu'],$_REQUEST['Cuenta'],$_REQUEST['PlanId'],$arreglo,'');

										if(!empty($insertar)){
												$mensaje="EL CARGO FUE AGREGADO A LA CUENTA.";

												list($dbconn) = GetDBconn();
												$query = "DELETE FROM procedimientos_pendientes_cargar_det
																	WHERE procedimiento_pendiente_cargar_id=".$_REQUEST['ID']."";
												$result = $dbconn->Execute($query);
												if ($dbconn->ErrorNo() != 0) {
																				$this->error = "Error al Cargar el Modulo";
																			echo	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																				return false;
												}

												$query = "DELETE FROM procedimientos_pendientes_cargar
																																WHERE procedimiento_pendiente_cargar_id=".$_REQUEST['ID']."";
												$result = $dbconn->Execute($query);
												if ($dbconn->ErrorNo() != 0) {
																				$this->error = "Error al Cargar el Modulo";
																		echo	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																				return false;
												}
										}else{
												$mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR.";
										}
								}
						}
				$FormaCuenta->FormaCuenta($_REQUEST[Cuenta],$_REQUEST[TipoId],$_REQUEST[PacienteId],$_REQUEST[Nivel],$_REQUEST[PlanId],$_REQUEST[Cama],$_REQUEST[Fecha],$_REQUEST[Ingreso],'',$_REQUEST[Transaccion],$mensaje,'');
				return $FormaCuenta->salida;
			}

			function LlamaFormaCuantaPendientesCargar(&$obj)
			{
					$FormaCuenta = new app_Facturacion_userclasses_HTML();

					list($dbconn) = GetDBconn();
					$query="DELETE FROM     cuentas_liquidacion_cargos
					WHERE cuentas_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."'";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
							$this->error = "Error al Cargar el Modulo";
						echo	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}else{
						$query="DELETE FROM cuentas_liquidaciones_qx_procedimientos_cargos
						WHERE consecutivo_procedimiento
						IN (SELECT consecutivo_procedimiento FROM cuentas_liquidaciones_qx_procedimientos WHERE cuenta_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."')";
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
								$this->error = "Error al Cargar el Modulo";
							echo	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}else{
									$query="DELETE FROM cuentas_liquidaciones_qx_procedimientos
									WHERE cuenta_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."'";
									$result = $dbconn->Execute($query);
									if($dbconn->ErrorNo() != 0){
											$this->error = "Error al Cargar el Modulo";
										echo	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
									}else{
											$query="DELETE FROM cuentas_liquidaciones_qx
											WHERE cuenta_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."'";
											$result = $dbconn->Execute($query);
											if($dbconn->ErrorNo() != 0){
													$this->error = "Error al Cargar el Modulo";
												echo	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													return false;
											}
									}
							}
					}
					unset($_SESSION['Liquidacion_QX']);
					unset($_SESSION['LIQUIDACION_QX']);
					unset($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS']);

					$FormaCuenta->FormaCuenta($_REQUEST[Cuenta],$_REQUEST[TipoId],$_REQUEST[PacienteId],$_REQUEST[Nivel],$_REQUEST[PlanId],$_REQUEST[Cama],$_REQUEST[Fecha],$_REQUEST[Ingreso],'',$_REQUEST[Transaccion],$mensaje,'');
					//$FormaCuenta->FormaMostrarCuenta(&$obj,$_REQUEST['Cuenta'],$mensaje);
					return $FormaCuenta->salida;
			}

			function CargarALaCuentaPaciente($obj)
			{
					if($obj->CallMetodoExterno('app','DatosLiquidacionQX','user','CargarALaCuentaPaciente')===true){
							$FormaCuenta = new app_Facturacion_userclasses_HTML();

							$mensaje="EL CARGO FUE AGREGADO A LA CUENTA.";
							list($dbconn) = GetDBconn();
							$query = "DELETE FROM procedimientos_pendientes_cargar_det
																											WHERE procedimiento_pendiente_cargar_id=".$_REQUEST['id']."";
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
															$this->error = "Error al Cargar el Modulo";
														echo	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															return false;
							}
							$query = "DELETE FROM procedimientos_pendientes_cargar
																											WHERE procedimiento_pendiente_cargar_id=".$_REQUEST['id']."";
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
															$this->error = "Error al Cargar el Modulo";
														echo	$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															return false;
							}

						$FormaCuenta->FormaCuenta($_REQUEST[Cuenta],$_REQUEST[TipoId],$_REQUEST[PacienteId],$_REQUEST[Nivel],$_REQUEST[PlanId],$_REQUEST[Cama],$_REQUEST[Fecha],$_REQUEST[Ingreso],'',$_REQUEST[Transaccion],$mensaje,'');
						//$FormaCuenta->FormaMostrarCuenta(&$obj,$_REQUEST['Cuenta'],$mensaje);
						return $FormaCuenta->salida;
					}
			}

			function EliminarCargoPendiente($obj)
			{
echo '<pre>-->';
print_r($_REQUEST);
				list($dbconn)=GetDBConn();
				$FormaCuenta = new app_Facturacion_userclasses_HTML();
				$mensaje="EL CARGO PENDIENTE POR GARGAR FUE ELIMINADO (".$_REQUEST[cargo_cups].").";

				$query = "DELETE FROM procedimientos_pendientes_cargar_det
									WHERE procedimiento_pendiente_cargar_id=".$_REQUEST['procedimiento_pendiente_cargar_id']."";
				if(!$rst = $this->ConexionBaseDatosTrans($dbconn,$query))
					return false;
// 				if(!$rst = $this->ConexionBaseDatos($query))
// 					return false;

				$query = "DELETE FROM procedimientos_pendientes_cargar
									WHERE procedimiento_pendiente_cargar_id=".$_REQUEST['procedimiento_pendiente_cargar_id']."";
				if(!$rst = $this->ConexionBaseDatosTrans($dbconn,$query))
					return false;
// 				if(!$rst = $this->ConexionBaseDatos($query))
// 					return false;

				$query = "INSERT INTO auditoria_cargos_pendientes_x_cargar
									(
										empresa_id,
										centro_utilidad,
										numerodecuenta,
										usuario_id,
										fecha_registro,
										cargo_cups,
										justificacion
									)
									VALUES
									(
										'".$_REQUEST[EmpresaId]."',
										'".$_REQUEST[CentroUtilidad]."',
										".$_REQUEST[Cuenta].",
										".UserGetUID().",
										now(),
										'".$_REQUEST[cargo_cups]."',
										'".$_REQUEST[observacion]."'
									);";
				if(!$rst = $this->ConexionBaseDatosTrans($dbconn,$query,$final_Transaccion=1))
					return false;
/*				if(!$rst = $this->ConexionBaseDatos($query))
					return false;*/
				$FormaCuenta->FormaCuenta($_REQUEST[Cuenta],$_REQUEST[TipoId],$_REQUEST[PacienteId],$_REQUEST[Nivel],$_REQUEST[PlanId],$_REQUEST[Cama],$_REQUEST[Fecha],$_REQUEST[Ingreso],'',$_REQUEST[Transaccion],$mensaje,'');
				//$FormaCuenta->LlamaForma(&$obj,$_REQUEST['Cuenta'],$mensaje);
				return $FormaCuenta->salida;
			}

      /**
      * La funcion BuscarNombresPaciente se encarga de buscar en la base de datos los nombres de los pacientes.
      * @access public
      * @return array
      * @param string tipo de documento
      * @param int numero de documento
      */
     function BuscarNombresPaciente($tipo,$documento)
     {
				$query = "SELECT primer_nombre,segundo_nombre FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
				if(!$rst = $this->ConexionBaseDatos($query))
					return false;
						if($rst->EOF){
							$this->error = "Error al Cargar el Modulo";
							echo $this->mensajeDeError = "La tabla 'pacientes' esta vacia ";
							return false;
						}
				$Nombres=$rst->fields[0]." ".$rst->fields[1];
				$rst->Close();
				return $Nombres;
		}

		/**
		* Se encarga de buscar en la base de datos los apellidos de los pacientes.
		* @access public
		* @return array
		* @param string tipo de documento
		* @param int numero de documento
		*/
		function BuscarApellidosPaciente($tipo,$documento)
		{
				$query = "SELECT primer_apellido,segundo_apellido FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
				if(!$rst = $this->ConexionBaseDatos($query))
					return false;

					if($rst->EOF){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "La tabla 'paciente' esta vacia ";
						return false;
					}
				$rst->Close();
				$Apellidos=$rst->fields[0]." ".$rst->fields[1];
				return $Apellidos;
		}

      function NombreTercero($tipo_id_tercero,$tercero_id)
      {
        $query="SELECT nombre_tercero
        FROM terceros
        WHERE tipo_id_tercero='".$tipo_id_tercero."' AND tercero_id='".$tercero_id."'";
				if(!$rst = $this->ConexionBaseDatos($query))
					return false;
          if($rst->RecordCount()>0){
            $vars=$rst->GetRowAssoc($toUpper=false);
          }
        $rst->Close();
        return $vars;
      }

			function DescripcionCargosCups($cargo_cups)
			{
				$query="SELECT descripcion
				FROM cups
				WHERE cargo='".$cargo_cups."'";
				if(!$rst = $this->ConexionBaseDatos($query))
					return false;
					if($rst->RecordCount()>0){
						$vars=$rst->GetRowAssoc($toUpper=false);
					}
				$rst->Close();
				return $vars;
			}

			function DescripcionCargosTarifario($tarifario_id)
			{
				$query="SELECT a.descripcion as tarifario
				FROM tarifarios a
				WHERE a.tarifario_id='".$tarifario_id."'";
				if(!$rst = $this->ConexionBaseDatos($query))
					return false;
					if($rst->RecordCount()>0){
						$vars=$rst->GetRowAssoc($toUpper=false);
					}
				$rst->Close();
				return $vars;
			}

		/**********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param 	string  $sql	sentencia sql a ejecutar 
		* @return rst 
		************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}

    /**********************************************************************************
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la 
    * consulta sql 
    *
    * @access public  
    * @param  string  $sql  sentencia sql a ejecutar 
    * @return rst 
    ************************************************************************************/
    function ConexionBaseDatosTrans($dbconn,$sql,$final_Transaccion){  
        
      $rst = $dbconn->Execute($sql);        
      if ($dbconn->ErrorNo() != 0){
        $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
        echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>"; 
        $dbconn->RollbackTrans();       
        return false;
      }
      if($final_Transaccion==1){
        $dbconn->CommitTrans();
      }
      return $rst;
    }     

  }
?>