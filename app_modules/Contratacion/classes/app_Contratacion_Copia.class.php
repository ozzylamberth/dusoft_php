<?php

/**
 * $Id: app_Contratacion_Copia.class.php,v 1.1.1.1 2009/09/11 20:36:30 hugo Exp $
 * @copyright (C) 2006 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

    class app_Contratacion_Copia
    {
    
            function app_Contratacion_Copia(&$dbconn,$vector)
            {
               if($this->InsertarDatosContrato(&$dbconn,$vector))
                 return true;
               else
                 return false;
            }
    
            function InsertarDatosContrato(&$dbconn,$vector)
            {
							//tablas iniciales
							$query ="INSERT INTO planes
											(
													plan_id,
													empresa_id,
													tipo_tercero_id,
													tercero_id,
													plan_descripcion,
													tipo_cliente,
													sw_tipo_plan,
													num_contrato,
													fecha_inicio,
													fecha_final,
													monto_contrato,
													monto_contrato_mensual,
													saldo_contrato,
													tope_maximo_factura,
													dias_credito_cartera,
													sw_contrata_hospitalizacion,
													fecha_registro,
													usuario_id,
													estado,
													servicios_contratados,
													protocolos,
													contacto,
													lineas_atencion,
													sw_base_liquidacion_imd,
													sw_exceder_monto_mensual,
													tipo_liquidacion_id,
													actividad_incumplimientos,
													sw_paragrafados_cd,
													sw_paragrafados_imd,
													sw_autoriza_sin_bd,
													sw_afiliacion,
													sw_facturacion_agrupada,
													meses_consulta_base_datos,
													horas_cancelacion,
													tipo_liquidacion_cargo,
													tipo_para_imd,
													lista_precios,
													porcentaje_utilidad,
													protocolo_internacion,
													sw_rips_con_cargo_cups,
													marca_prioridad_atencion
											)
											SELECT
												".$vector[plan_id].",
												'".$vector[empresa]."',
												'".$vector[tipoTerceroId]."',
												'".$vector[codigo]."',
												'".$vector['descr2ctra']."',
												tipo_cliente,
												sw_tipo_plan,
												'".$vector['numeroctra']."',
												'".$vector[fecdes]."',
												'".$vector[fechas]."',
												".$vector[valorcontr].",
												".$vector[valmecontr].",
												".$vector[valorcontr].",
												".$vector[factucontr].",
												".$vector['diasCredito'].",
												sw_contrata_hospitalizacion,
												'".date("Y-m-d H:i:s")."',
												".$vector[usuario].",
												'0',
												servicios_contratados,
												protocolos,
												'".$vector['contactoctra']."',
												lineas_atencion,
												sw_base_liquidacion_imd,
												'".$vector['excmonctra']."',
												tipo_liquidacion_id,
												actividad_incumplimientos,
												sw_paragrafados_cd,
												sw_paragrafados_imd,
												sw_autoriza_sin_bd,
												sw_afiliacion,
												sw_facturacion_agrupada,
												meses_consulta_base_datos,
												horas_cancelacion,
												tipo_liquidacion_cargo,
												tipo_para_imd,
												lista_precios,
												porcentaje_utilidad,
												protocolo_internacion,
												sw_rips_con_cargo_cups,
												marca_prioridad_atencion
											FROM planes
											WHERE plan_id=".$vector['tarifario2'].";";
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$dbconn->RollBackTrans();
									//$_POST['numeroctra']='';
									$this->frmError["MensajeError"]="DATOS DUPLICADOS - VERIFICAR N�ERO DEL CONTRATO".$dbconn->ErrorMsg();
									$this->uno=1;
									return false;
							}
							$query ="INSERT INTO planes_encargados
											(
													plan_id,
													usuario_id
											)
											SELECT
											".$vector[plan_id].",
											usuario_id
											FROM planes_encargados
											WHERE plan_id=".$vector['tarifario2'].";";
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollBackTrans();
									return false;
							}
							$query ="INSERT INTO planes_rangos
											(
													plan_id,
													tipo_afiliado_id,
													rango,
													cuota_moderadora,
													copago,
													copago_maximo,
													copago_minimo,
													copago_maximo_ano
											)
											SELECT
											".$vector[plan_id].",
											tipo_afiliado_id,
											rango,
											cuota_moderadora,
											copago,
											copago_maximo,
											copago_minimo,
											copago_maximo_ano
											FROM planes_rangos
											WHERE plan_id=".$vector['tarifario2'].";";
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "ERROR AL COPIAR LOS DATOS planes_rangos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollBackTrans();
									return false;
							}
							//FIN tablas iniciales
							$query ="INSERT INTO planes_uvrs
											(
												plan_id,
												dc_valor,
												usuario_id,
												tarifario_id,
												da_valor,
												dg_valor,
												dy_valor
											)
											SELECT
											".$vector[plan_id].",
												dc_valor,
												usuario_id,
												tarifario_id,
												da_valor,
												dg_valor,
												dy_valor
											FROM planes_uvrs
											WHERE plan_id=".$vector['tarifario2'].";";
							$resulta = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "ERROR AL COPIAR LOS DATOS planes_uvrs";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$dbconn->RollBackTrans();
									return false;
							}
//tarifarios_uvrs_paquetes_excepciones
							$query ="INSERT INTO tarifarios_uvrs_paquetes_excepciones
												(plan_id,
												tarifario_id,
												uvr_valor)
												SELECT
												".$vector[plan_id].",
												tarifario_id,
												uvr_valor
											FROM tarifarios_uvrs_paquetes_excepciones
											WHERE plan_id=".$vector['tarifario2'].";";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$dbconn->RollBackTrans();
									$this->uno=1;
									$this->frmError["MensajeError"]="Error al insertar en la tabla tarifarios_uvrs_paquetes_excepciones: ".$query.'--'.$dbconn->ErrorMsg();
									$this->FrmIngresarRangosUVR();
									return true;
							}
//
							if($vector['tarifario2']<>NULL AND $vector['tarifario2']<>$vector[plan_id])
							{
									$this->frmError["MensajeError"]='';
									if($_POST['copiartari']==1)
									{
											$query ="INSERT INTO plan_tarifario
															(
																	plan_id,
																	grupo_tarifario_id,
																	subgrupo_tarifario_id,
																	tarifario_id,
																	porcentaje,
																	por_cobertura,
																	sw_descuento
															)
															SELECT
															".$vector[plan_id].",
															grupo_tarifario_id,
															subgrupo_tarifario_id,
															tarifario_id,
															porcentaje,
															por_cobertura,
															sw_descuento
															FROM plan_tarifario
															WHERE plan_id=".$vector['tarifario2']."
															--AND tarifario_id<>'SYS'
															--AND grupo_tarifario_id<>'00'
															;";
											$resulta = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
													$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(plan_tarifario) ".$dbconn->ErrorMsg();
													$dbconn->RollBackTrans();
													return false;
											}
											else
											{
													$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
											}
									}
									if($_POST['copiarsema']==1 AND $_POST['copiartari']==1)
									{
											$query ="INSERT INTO planes_semanas_cotizadas
															(
																	plan_id,
																	grupo_tarifario_id,
																	subgrupo_tarifario_id,
																	semanas_cotizadas
															)
															SELECT
															".$vector[plan_id].",
															grupo_tarifario_id,
															subgrupo_tarifario_id,
															semanas_cotizadas
															FROM planes_semanas_cotizadas
															WHERE plan_id=".$vector['tarifario2'].";";
											$resulta = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
													$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS ".$dbconn->ErrorMsg();
													$dbconn->RollBackTrans();
													return false;
											}
											else
											{
													$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
											}
									}
									if($_POST['copiartariex']==1 AND $_POST['copiartari']==1)
									{
											$query ="INSERT INTO excepciones
															(
																	plan_id,
																	tarifario_id,
																	cargo,
																	porcentaje,
																	por_cobertura,
																	sw_descuento,
																	sw_no_contratado
															)
															SELECT
															".$vector[plan_id].",
															tarifario_id,
															cargo,
															porcentaje,
															por_cobertura,
															sw_descuento,
															sw_no_contratado
															FROM excepciones
															WHERE plan_id=".$vector['tarifario2'].";";
											$resulta = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
													$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(excepciones) ".$dbconn->ErrorMsg();
													$dbconn->RollBackTrans();
													return false;
											}
									}
									if($_POST['copiarsemaex']==1 AND $_POST['copiarsema']==1 AND $_POST['copiartari']==1)
									{
											$query ="INSERT INTO excepciones_semanas_cotizadas
															(
																	plan_id,
																	tarifario_id,
																	cargo,
																	semanas_cotizadas
															)
															SELECT
															".$vector[plan_id].",
															tarifario_id,
															cargo,
															semanas_cotizadas
															FROM excepciones_semanas_cotizadas
															WHERE plan_id=".$vector['tarifario2'].";";
											$resulta = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
													$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(excepciones_semanas_cotizadas) ".$dbconn->ErrorMsg();
													$dbconn->RollBackTrans();
													return false;
											}
									}
									if(($_POST['copiartari']==1 AND $_POST['copiarcopa']==1)
									OR $_POST['copiarauin']==1 OR $_POST['copiarauex']==1
									OR $_POST['copiarpaim']==1 OR $_POST['copiarpacd']==1
									OR $_POST['copiarinme']==1 OR $_POST['copiarinme2']==1)
									{
											$query ="INSERT INTO planes_servicios
															(
																	plan_id,
																	servicio
															)
															SELECT
															".$vector[plan_id].",
															servicio
															FROM planes_servicios
															WHERE plan_id=".$vector['tarifario2'].";";
											$resulta = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
													$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(planes_servicios) ".$dbconn->ErrorMsg();
													$dbconn->RollBackTrans();
													return false;
											}
											if($_POST['copiarcopa']==1 AND $_POST['copiartari']==1)
											{
													$query ="INSERT INTO planes_copagos
																	(
																			plan_id,
																			grupo_tarifario_id,
																			subgrupo_tarifario_id,
																			servicio,
																			sw_copago,
																			sw_cuota_moderadora
																	)
																	SELECT
																	".$vector[plan_id].",
																	grupo_tarifario_id,
																	subgrupo_tarifario_id,
																	servicio,
																	sw_copago,
																	sw_cuota_moderadora
																	FROM planes_copagos
																	WHERE plan_id=".$vector['tarifario2'].";"; 
													$resulta = $dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(planes_copagos) ".$dbconn->ErrorMsg();
															$dbconn->RollBackTrans();
															return false;
													}
													else
													{
															$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
													}
											}
											if($_POST['copiarcopaex']==1 AND $_POST['copiarcopa']==1 AND $_POST['copiartari']==1)
											{
													$query ="INSERT INTO excepciones_copagos
																	(
																			plan_id,
																			tarifario_id,
																			cargo,
																			servicio,
																			sw_copago,
																			sw_cuota_moderadora
																	)
																	SELECT
																	".$vector[plan_id].",
																	tarifario_id,
																	cargo,
																	servicio,
																	sw_copago,
																	sw_cuota_moderadora
																	FROM excepciones_copagos
																	WHERE plan_id=".$vector['tarifario2'].";";
													$resulta = $dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(excepciones_copagos) ".$dbconn->ErrorMsg();
															$dbconn->RollBackTrans();
															return false;
													}
											}
											if($_POST['copiarauin']==1)
											{

													$query ="INSERT INTO planes_autorizaciones_int
																	(
																			plan_id,
																			grupo_tipo_cargo,
																			tipo_cargo,
																			servicio,
																			nivel
																	)
																	SELECT
																	".$vector[plan_id].",
																	grupo_tipo_cargo,
																	tipo_cargo,
																	servicio,
																	nivel
																	FROM planes_autorizaciones_int
																	WHERE plan_id=".$vector['tarifario2'].";";
													$resulta = $dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(planes_autorizaciones_int) ".$dbconn->ErrorMsg();
															$dbconn->RollBackTrans();
															return false;
													}
													else
													{
															$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
													}
											}
											if($_POST['copiarauin']==1 AND $_POST['copiarauinex']==1)
											{
													$query ="INSERT INTO excepciones_aut_int
																	(
																			plan_id,
																			
																			cargo,
																			servicio,
																			sw_autorizado,
																			cantidad,
																			valor_maximo,
																			periocidad_dias
																	)
																	SELECT
																	".$vector[plan_id].",
																	
																	cargo,
																	servicio,
																	sw_autorizado,
																	cantidad,
																	valor_maximo,
																	periocidad_dias
																	FROM excepciones_aut_int
																	WHERE plan_id=".$vector['tarifario2'].";";
													$resulta = $dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(excepciones_aut_int) ".$dbconn->ErrorMsg();
															$dbconn->RollBackTrans();
															return false;
													}
											}
											if($_POST['copiarauex']==1)
											{
													$query ="INSERT INTO planes_autorizaciones_ext
																	(
																			plan_id,
																			grupo_tipo_cargo,
																			tipo_cargo,
																			servicio,
																			nivel
																	)
																	SELECT
																	".$vector[plan_id].",
																	grupo_tipo_cargo,
																	tipo_cargo,
																	servicio,
																	nivel
																	FROM planes_autorizaciones_ext
																	WHERE plan_id=".$vector['tarifario2'].";";
													$resulta = $dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(planes_autorizaciones_ext) ".$dbconn->ErrorMsg();
															$dbconn->RollBackTrans();
															return false;
													}
													else
													{
															$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
													}
											}
											if($_POST['copiarauex']==1 AND $_POST['copiarauexex']==1)
											{
													$query ="INSERT INTO excepciones_aut_ext
																	(
																			plan_id,
																			
																			cargo,
																			servicio,
																			sw_autorizado,
																			cantidad,
																			valor_maximo,
																			periocidad_dias
																	)
																	SELECT
																	".$vector[plan_id].",
																	
																	cargo,
																	servicio,
																	sw_autorizado,
																	cantidad,
																	valor_maximo,
																	periocidad_dias
																	FROM excepciones_aut_ext
																	WHERE plan_id=".$vector['tarifario2'].";";
													$resulta = $dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(excepciones_aut_ext) ".$dbconn->ErrorMsg();
															$dbconn->RollBackTrans();
															return false;
													}
											}
											if($_POST['copiarpaim']==1)
											{
													$query ="INSERT INTO planes_paragrafados_medicamentos
																	(
																			plan_id,
																			servicio,
																			departamento,
																			codigo_producto
																	)
																	SELECT
																	".$vector[plan_id].",
																	servicio,
																	departamento,
																	codigo_producto
																	FROM planes_paragrafados_medicamentos
																	WHERE plan_id=".$vector['tarifario2'].";";
													$dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(planes_paragrafados_medicamentos) ".$dbconn->ErrorMsg();
															$dbconn->RollBackTrans();
															return false;
													}
													else
													{
															$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
													}
											}
											if($_POST['copiarpacd']==1)
											{
												$query ="INSERT INTO planes_paragrafados_cargos
																	(
																			plan_id,
																			servicio,
																			tarifario_id,
																			cargo
																	)
																	SELECT
																	".$vector[plan_id].",
																	servicio,
																	tarifario_id,
																	cargo
																	FROM planes_paragrafados_cargos
																	WHERE plan_id=".$vector['tarifario2'].";";
													$dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(planes_paragrafados_cargos) ".$dbconn->ErrorMsg();
															$dbconn->RollBackTrans();
															return false;
													}
													else
													{
															$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
													}
											}
											if($_POST['copiarinm2']==1)
											{
													$query ="INSERT INTO plan_tarifario_inv_autorizaciones
																	(
																			plan_id,
																			empresa_id,
																			grupo_contratacion_id,
																			servicio,
																			cantidad_max,
																			valor_max_unidad,
																			valor_max_cuenta,
																			requiere_autorizacion_int,
																			requiere_autorizacion_ext,
																			semanas_cotizadas
																	)
																	SELECT
																	".$vector[plan_id].",
																	'".$vector[empresa]."',
																	grupo_contratacion_id,
																	servicio,
																	cantidad_max,
																	valor_max_unidad,
																	valor_max_cuenta,
																	requiere_autorizacion_int,
																	requiere_autorizacion_ext,
																	semanas_cotizadas
																	FROM plan_tarifario_inv_autorizaciones
																	WHERE plan_id=".$vector['tarifario2'].";";
													$resulta = $dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(plan_tarifario_inv_autorizaciones) ".$dbconn->ErrorMsg();
															$dbconn->RollBackTrans();
															return false;
													}
													else
													{
															$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
													}
											}
											if($_POST['copiarinm2']==1 AND $_POST['copiarinmee2']==1)
											{
													$query ="INSERT INTO excepciones_inv_autorizaciones
																	(
																			plan_id,
																			empresa_id,
																			codigo_producto,
																			servicio,
																			cantidad_max,
																			valor_max_unidad,
																			valor_max_cuenta,
																			requiere_autorizacion_int,
																			requiere_autorizacion_ext,
																			semanas_cotizadas
																	)
																	SELECT
																	".$vector[plan_id].",
																	'".$vector[empresa]."',
																	codigo_producto,
																	servicio,
																	cantidad_max,
																	valor_max_unidad,
																	valor_max_cuenta,
																	requiere_autorizacion_int,
																	requiere_autorizacion_ext,
																	semanas_cotizadas
																	FROM excepciones_inv_autorizaciones
																	WHERE plan_id=".$vector['tarifario2'].";";
													$resulta = $dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(planes_paragrafados_cargos) ".$dbconn->ErrorMsg();
															$dbconn->RollBackTrans();
															return false;
													}
											}
											if($_POST['copiarinme']==1)
											{
													$query ="INSERT INTO plan_tarifario_inv_copagos
																	(
																			plan_id,
																			empresa_id,
																			grupo_contratacion_id,
																			servicio,
																			porcentaje,
																			por_cobertura,
																			sw_descuento,
																			sw_copago,
																			sw_cuota_moderadora
																	)
																	SELECT
																	".$vector[plan_id].",
																	'".$vector[empresa]."',
																	grupo_contratacion_id,
																	servicio,
																	porcentaje,
																	por_cobertura,
																	sw_descuento,
																	sw_copago,
																	sw_cuota_moderadora
																	FROM plan_tarifario_inv_copagos
																	WHERE plan_id=".$vector['tarifario2'].";";
													$resulta = $dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(planes_paragrafados_cargos) ".$dbconn->ErrorMsg();
															$dbconn->RollBackTrans();
															return false;
													}
													else
													{
															$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
													}
											}
											if($_POST['copiarinme']==1 AND $_POST['copiarinmeex']==1)
											{
													$query ="INSERT INTO excepciones_inv_copagos
																	(
																			plan_id,
																			empresa_id,
																			codigo_producto,
																			servicio,
																			porcentaje,
																			por_cobertura,
																			sw_descuento,
																			sw_copago,
																			sw_cuota_moderadora
																	)
																	SELECT
																	".$vector[plan_id].",
																	'".$vector[empresa]."',
																	codigo_producto,
																	servicio,
																	porcentaje,
																	por_cobertura,
																	sw_descuento,
																	sw_copago,
																	sw_cuota_moderadora
																	FROM excepciones_inv_copagos
																	WHERE plan_id=".$vector['tarifario2'].";";
													$resulta = $dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(excepciones_inv_copagos) ".$dbconn->ErrorMsg();
															$dbconn->RollBackTrans();
															return false;
													}
											}
									}
									if($_POST['copiarincu']==1)
									{
											$query ="INSERT INTO planes_incumplimientos_citas
															(
																	plan_id,
																	cargo_cita,
																	valor
															)
															SELECT
															".$vector[plan_id].",
															cargo_cita,
															valor
															FROM planes_incumplimientos_citas
															WHERE plan_id=".$vector['tarifario2'].";";
											$resulta = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
													$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(planes_incumplimientos_citas) ".$dbconn->ErrorMsg();
													$dbconn->RollBackTrans();
													return false;
											}
											else
											{
													$this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
											}
									}
									if($_POST['copiarplanescamas']==1)
									{
										$query ="INSERT INTO planes_tipos_camas
														(
															tipo_cama_id,
															empresa_id, 
															plan_id, 
															cargo_cups,
															tarifario_id,
															cargo,
															porcentaje,
															valor_lista,
															valor_excedente, 
															tarifario_excedente,
															cargo_excedente,
															porcentaje_excedente
														)
														SELECT  tipo_cama_id,
															'".$vector[empresa]."',
															".$vector[plan_id].",
															cargo_cups,
															tarifario_id,
															cargo,
															porcentaje,
															valor_lista,
															valor_excedente, 
															tarifario_excedente,
															cargo_excedente,
															porcentaje_excedente
														FROM planes_tipos_camas
														WHERE plan_id = ".$vector['tarifario2'].";";
										$resulta = $dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0)
										{
												$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(planes_tipos_camas) ".$dbconn->ErrorMsg();
												$dbconn->RollBackTrans();
												return false;
										}
										$query ="INSERT INTO planes_tipos_liq_habitacion
														(
															tipo_liq_habitacion,
															tipo_clase_cama_id,
															plan_id
														)
														SELECT tipo_liq_habitacion,
															tipo_clase_cama_id,
															".$vector[plan_id]."
														FROM planes_tipos_liq_habitacion
														WHERE plan_id = ".$vector['tarifario2'].";";
										$resulta = $dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0)
										{
												$this->frmError["MensajeError"]="OCURRI�UN ERROR AL COPIAR LOS DATOS(planes_tipos_liq_habitacion) ".$dbconn->ErrorMsg();
												$dbconn->RollBackTrans();
												return false;
										}
									}
							}
							$_POST['copiartari']='';
							$_POST['copiarcopa']='';
							$_POST['copiarsema']='';
							$_POST['copiartariex']='';
							$_POST['copiarcopaex']='';
							$_POST['copiarsemaex']='';
							$_POST['copiarauin']='';
							$_POST['copiarauinex']='';
							$_POST['copiarauex']='';
							$_POST['copiarauexex']='';
							$_POST['copiarinme']='';
							$_POST['copiarinmeex']='';
							$_POST['copiarinm2']='';
							$_POST['copiarinmee2']='';
							$_POST['copiarpaim']='';
							$_POST['copiarpacd']='';
							$_POST['copiarincu']='';
							$_POST['copiarplanescamas']='';
							if($this->frmError["MensajeError"]==NULL)
							{
									$this->frmError["MensajeError"]="LAS OPCIONES PARA COPIAR NO SON CORRECTAS
									<BR>EL SISTEMA NO EFECTU�NING� CAMBIO, POR FAVOR VERIFIQUE LOS DATOS";
									return false;
							}
							$this->uno=1;
							return true;
          }
    }//end of class

?>
