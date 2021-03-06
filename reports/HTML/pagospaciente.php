<?php
/*
$Id: pagospaciente.php,v 1.8 2007/03/16 18:48:47 carlos Exp $
*/
		$_ROOT='../../';
		$VISTA='HTML';
		include $_ROOT.'includes/enviroment.inc.php';
		$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	
		IncludeFile($fileName);
	
		print(ReturnHeader('DETALLE PAGOS PACIENTE'));
		print(ReturnBody());

		$Cuenta=$_REQUEST['cuenta'];

		list($dbconn) = GetDBconn();
		$query = "SELECT e.empresa_id,e.recibo_caja,e.centro_utilidad,
							e.prefijo,e.fecha_ingcaja,e.total_abono,e.total_bonos,
							e.total_efectivo,e.total_cheques, e.total_tarjetas,
							e.tipo_id_tercero,e.tercero_id, h.prefijo,
							e.estado,e.fecha_registro,e.usuario_id
							FROM recibos_caja e, rc_detalle_hosp h
							WHERE h.numerodecuenta=$Cuenta AND
							e.recibo_caja=h.recibo_caja AND e.prefijo=h.prefijo and e.estado='0'" ;
		$resul = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

		if($resul->RecordCount()===0)
		{
				$query = "SELECT e.empresa_id,e.factura_fiscal as recibo_caja,
								e.centro_utilidad,e.prefijo,e.fecha_registro,e.total_abono,
								e.total_bonos, e.total_efectivo,e.total_cheques, 
								e.total_tarjetas, e.tipo_id_tercero,e.tercero_id, 
								h.prefijo, e.estado,e.fecha_registro,e.usuario_id 
						FROM fac_facturas_contado e, 
								fac_facturas_cuentas h 
						WHERE h.numerodecuenta=$Cuenta 
						AND e.factura_fiscal=h.factura_fiscal 
						AND e.prefijo=h.prefijo;" ;
				$resul = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
		}

	while (!$resul->EOF) {
				$datos[]= $resul->GetRowAssoc($ToUpper = false);
				$resul->MoveNext();
	}
	$resul->Close();


	echo ThemeAbrirTabla('DETALLE PAGOS PACIENTE');
	for($j=0;$j<sizeof($datos);$j++)
	{
					echo "<br><table  align=\"center\" border=\"0\" width=\"95%\" class=\"modulo_table_list\">";
					echo "<tr class=\"modulo_table_list_title \"><td colspan=\"7\">RECIBO DE CAJA No. ".$datos[$j][recibo_caja]." </td></tr>";
					echo "<tr class=\"modulo_table_list_title \">";
					echo "  <td>Recibo de Caja</td>";
					echo "  <td>Fecha de Registro</td>";
					echo "  <td>Total Efectivos</td>";
					echo "  <td>Total Cheques</td>";
					echo "  <td>Total Tarjetas</td>";
					echo "  <td>Total Bonos</td>";
					echo "  <td>Total</td>";
					echo "</tr>";
					$rcaja=$datos[$j][recibo_caja];
					$fech=$datos[$j][fecha_registro];
					$Te=FormatoValor($datos[$j][total_efectivo]);
					$Tc=FormatoValor($datos[$j][total_cheques]);
					$Tt=FormatoValor($datos[$j][total_tarjetas]);
					$Tb=FormatoValor($datos[$j][total_bonos]);
					$TOTAL=FormatoValor($datos[$j][total_abono]);
					if( $j % 2){ $estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					echo "<tr class=\"$estilo\" align=\"center\">";
					echo "  <td>$rcaja</td>";
					echo "  <td>$fech</td>";
					echo "  <td>$Te</td>";
					echo "  <td>$Tc</td>";
					echo "  <td>$Tt</td>";
					echo "  <td>$Tb</td>";
					echo "  <td class=\"label_error\">$TOTAL</td>";
					echo "</tr>";
					if(!empty($datos[$j][total_cheques]) OR !empty($datos[$j][total_tarjetas]) OR !empty($datos[$j][total_bonos]))
					{
							echo "<tr class=\"modulo_list_claro\" align=\"center\">";
							echo "  <td colspan=\"7\">";
							if(!empty($datos[$j][total_tarjetas]))
							{
									$var='';
									$query = "SELECT e.tarjeta_mov_db_id,
                          e.tarjeta, f.descripcion, e.empresa_id,
                          e.centro_utilidad, e.recibo_caja, e.prefijo,
                          e.autorizacion, e.total, e.tarjeta_numero
													FROM tarjetas_mov_debito e,tarjetas f
													WHERE prefijo= '".$datos[$j][prefijo]."' AND recibo_caja='".$datos[$j][recibo_caja]."'
													AND e.tarjeta=f.tarjeta";
									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
									while(!$result->EOF)
									{
										$var[]= $result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
									}
									if($var)
									{
													echo "<table align=\"center\" border=\"0\"  width=\"100%\">";
													echo "<tr class=\"modulo_table_title\"><td  colspan=\"4\">PAGOS TARJETA DEBITO</td></tr>";
													echo "<tr class=\"modulo_table_list_title\">";
													echo "  <td>No. Tarjeta</td>";
													echo "  <td>Tarjeta</td>";
													echo "  <td>No. Autorizacion</td>";
													echo "  <td>Valor</td>";
													echo "</tr>";
													for($i=0;$i<sizeof($var);$i++)
													{
																	$Tarjeta=$var[$i][descripcion];
																	$Auto=$var[$i][autorizacion];
																	$Valor=FormatoValor($var[$i][total]);
																	$Recibo=$var[$i][recibo_caja];
																	$NTarjeta=$var[$i][tarjeta_numero];
																	if( $i % 2){ $estilo='modulo_list_claro';}
																	else {$estilo='modulo_list_oscuro';}
																	echo "<tr class=\"$estilo\"align=\"center\">";
																	echo "  <td>$NTarjeta</td>";
																	echo "  <td>$Tarjeta</td>";
																	echo "  <td>$Auto</td>";
																	echo "  <td>$Valor</td>";
																	echo "</tr>";
													}
													echo "</table>";
									}
									$dat='';
									$query = "SELECT e.tarjeta_mov_id, e.tarjeta,
                          f.descripcion, e.empresa_id, e.centro_utilidad,
                          e.recibo_caja, e.prefijo, e.fecha,
                          e.autorizacion, e.socio, e.fecha_expira,
                          e.autorizado_por, e.total, e.usuario_id, e.fecha_registro,
                          e.tarjeta_numero
													FROM tarjetas_mov_credito e,tarjetas f
													WHERE prefijo= '".$datos[$j][prefijo]."' AND recibo_caja='".$datos[$j][recibo_caja]."'
													AND e.tarjeta=f.tarjeta";
									$results = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
									while(!$results->EOF)
									{
										$dat[]= $results->GetRowAssoc($ToUpper = false);
										$results->MoveNext();
									}

									if($dat)
									{
											echo "<table align=\"center\" border=\"0\" width=\"100%\">";
											echo "<tr class=\"modulo_table_title\"><td  colspan=\"8\">PAGOS TARJETA CREDITO</td></tr>";
											echo "<tr class=\"modulo_table_list_title\">";
											echo "  <td>No. Tarjeta</td>";
											echo "  <td>Tarjeta</td>";
											echo "  <td>No. Autorizacion</td>";
											echo "  <td>Fecha</td>";
											echo "  <td>Socio</td>";
											echo "  <td>Autorizado por</td>";
											echo "  <td>Fecha de Expiracion</td>";
											echo "  <td>Valor</td>";
											echo "</tr>";
											for($i=0;$i<sizeof($dat);$i++)
											{
														$noTarjeta=$dat[$i][tarjeta_numero];
														$Tarjeta=$dat[$i][descripcion];
														$NTarjeta=$dat[$i][tarjeta_numero];
														$FechaExp=$dat[$i][fecha_expira];
														$Fecha=$dat[$i][fecha];
														$Socio=$dat[$i][socio];
														$AutoPor=$dat[$i][autorizado_por];
														$Valor=FormatoValor($dat[$i][total]);
														$Auto=$dat[$i][autorizacion];
														if( $i % 2){ $estilo='modulo_list_claro';}
														else {$estilo='modulo_list_oscuro';}
														echo "<tr class=\"$estilo\" align=\"center\">";
														echo "  <td>$noTarjeta</td>";
														echo "  <td>$Tarjeta</td>";
														echo "  <td>$Auto</td>";
														echo "  <td>$Fecha</td>";
														echo "  <td>$Socio</td>";
														echo "  <td>$AutoPor</td>";
														echo "  <td>$FechaExp</td>";
														echo "  <td>$Valor</td>";
														echo "</tr>";
											}
											echo "</table>";
									}
									$dat ='';
									$query = "SELECT e.cheque_mov_id,e.banco,
														e.recibo_caja,f.descripcion,e.cta_cte,
														e.cheque,e.girador,e.fecha_cheque,e.total,e.fecha
														FROM cheques_mov e,  bancos f
														WHERE e.prefijo= '".$datos[$j][prefijo]."' AND e.recibo_caja='".$datos[$j][recibo_caja]."'
														AND e.banco=f.banco";
													$results = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
									while(!$results->EOF)
									{
										$dat[]= $results->GetRowAssoc($ToUpper = false);
										$results->MoveNext();
									}
									if($dat)
									{
													echo "<table  align=\"center\" border=\"0\"  width=\"100%\">";
													echo "<tr class=\"modulo_table_title\"><td colspan=\"7\">PAGOS CON CHEQUE</td></tr>";
													echo "<tr class=\"modulo_table_list_title\">";
													echo "  <td>Numero de Cheque</td>";
													echo "  <td>Banco</td>";
													echo "  <td>Girador</td>";
													echo "  <td>Fecha</td>";
													echo "  <td>Cta Cte</td>";
													echo "  <td>Fecha de Cheque</td>";
													echo "  <td>Valor</td>";
													echo "</tr>";
													for($i=0;$i<sizeof($dat);$i++)
													{
																	$NoCheque=$dat[$i][cheque];
																	$Banco=$dat[$i][descripcion];
																	$Girador=$dat[$i][girador];
																	$Fecha=$dat[$i][fecha];
																	$CtaCte=$dat[$i][cta_cte];
																	$FechaCheque=$dat[$i][fecha_cheque];
																	$vl=FormatoValor($dat[$i][total]);
																	if( $i % 2){ $estilo='modulo_list_claro';}
																	else {$estilo='modulo_list_oscuro';}
																	echo "<tr class=\"$estilo\" align=\"center\">";
																	echo "  <td>$NoCheque</td>";
																	echo "  <td>$Banco</td>";
																	echo "  <td>$Girador</td>";
																	echo "  <td>$Fecha</td>";
																	echo "  <td>$CtaCte</td>";
																	echo "  <td>$FechaCheque</td>";
																	echo "  <td>$vl</td>";
																	echo "</tr>";
													}
													echo "</table>";
									}
									$dat='';
									$query = "SELECT e.valor_bono,f.descripcion,
														e.empresa_id,e.centro_utilidad,e.recibo_caja,e.prefijo
														FROM caja_bonos e,tipos_bonos f
														WHERE e.prefijo= '".$datos[$j][prefijo]."' AND e.recibo_caja='".$datos[$j][recibo_caja]."'
														AND e.tipo_bono=f.tipo_bono";
									if($dat)
									{
													echo "<table align=\"center\" border=\"0\"  width=\"85%\">";
													echo "<tr  align=\"center\" class=\"modulo_table_title\"><td  colspan=\"3\">PAGOS BONOS</td></tr>";
													echo "<tr class=\"modulo_table_list_title\">";
													echo "  <td>Recibo Caja</td>";
													echo "  <td>Descripcion</td>";
													echo "  <td>Valor</td>";
													echo "</tr>";
													for($i=0;$i<sizeof($dat);$i++)
													{
																	$desc=$dat[$i][descripcion];
																	$Valor=FormatoValor($dat[$i][valor_bono]);
																	$Recibo=$dat[$i][recibo_caja];
																	if( $i % 2){ $estilo='modulo_list_claro';}
																	else {$estilo='modulo_list_oscuro';}
																	echo "<tr class=\"$estilo\"align=\"center\">";
																	echo "  <td>$Recibo</td>";
																	echo "  <td>$desc</td>";
																	echo "  <td>$Valor</td>";
																	echo "</tr>";
													}
													echo "</table><br>";
									}
							}
							echo "</td>";
							echo "</tr>";
					}
			echo "</table>";
	}
		//MOSTRAR LAS DEVOLUCIONES
		$dat='';
		$query = "SELECT total_devolucion,fecha_registro,empresa_id,
								centro_utilidad,recibo_caja,prefijo
							FROM rc_devoluciones
							WHERE numerodecuenta=$Cuenta";
						$results = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						while(!$results->EOF)
						{
							$dat[]= $results->GetRowAssoc($ToUpper = false);
							$results->MoveNext();
						}
		if($dat)
		{
						echo "<br><br><table align=\"center\" border=\"0\"  width=\"85%\"  class=\"modulo_table_list\">";
						echo "<tr align=\"center\" class=\"modulo_table_title\"><td  colspan=\"3\">DEVOLUCIONES</td></tr>";
						echo "<tr class=\"modulo_table_list_title\">";
						echo "  <td width=\"15%\">Recibo Dev</td>";
						echo "  <td width=\"45%\">Valor</td>";
						echo "  <td width=\"40%\">fecha</td>";
						echo "</tr>";
						for($i=0;$i<sizeof($dat);$i++)
						{
										$fecha=$dat[$i][fecha_registro];
										$Valor=FormatoValor($dat[$i][total_devolucion]);
										$Recibo=$dat[$i][prefijo].$dat[$i][recibo_caja];
										if( $i % 2){ $estilo='modulo_list_claro';}
										else {$estilo='modulo_list_oscuro';}
										echo "<tr class=\"$estilo\"align=\"center\">";
										echo "  <td>$Recibo</td>";
										echo "  <td class=\"label_error\">$Valor</td>";
										echo "  <td>$fecha</td>";
										echo "</tr>";
						}
						echo "</table><br>";
		}
		//
		//MOSTRAR PAGOS CON PAGARES
		$dat='';
		$query = "SELECT A.abono_letras,B.fecha_registro,B.empresa_id,
										B.numero,B.prefijo, D.nombre_tercero, 
										C.tipo_id_tercero, C.tercero_id
							FROM cuentas A, pagares B, 
									pagares_responsables C, terceros D
							WHERE A.numerodecuenta=$Cuenta
							AND A.numerodecuenta=B.numerodecuenta
							AND B.empresa_id=C.empresa_id
							AND B.prefijo=C.prefijo
							AND B.numero=C.numero
							AND C.tipo_id_tercero=D.tipo_id_tercero
							AND C.tercero_id=D.tercero_id;";
						$results = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						while(!$results->EOF)
						{
							$dat[]= $results->GetRowAssoc($ToUpper = false);
							$results->MoveNext();
						}
		if($dat)
		{
						echo "<br><br><table align=\"center\" border=\"0\"  width=\"90%\"  class=\"modulo_table_list\">";
						echo "<tr align=\"center\" class=\"modulo_table_title\"><td  colspan=\"3\">ABONOS LETRAS - PAGARES</td></tr>";
						echo "<tr class=\"modulo_table_list_title\">";
						echo "  <td width=\"50%\">Responsable</td>";
						echo "  <td width=\"15%\">Recibo</td>";
						echo "  <td width=\"20%\">Valor</td>";
						echo "  <td width=\"15%\">fecha</td>";
						echo "</tr>";
						for($i=0;$i<sizeof($dat);$i++)
						{
										$Responsable=$dat[$i][tipo_id_tercero].' '.$dat[$i][tercero_id].' '.$dat[$i][nombre_tercero];
										$fecha=$dat[$i][fecha_registro];
										$Valor=FormatoValor($dat[$i][abono_letras]);
										$Recibo=$dat[$i][prefijo].$dat[$i][numero];
										if( $i % 2){ $estilo='modulo_list_claro';}
										else {$estilo='modulo_list_oscuro';}
										echo "<tr class=\"$estilo\"align=\"center\">";
										echo "  <td>$Responsable</td>";
										echo "  <td>$Recibo</td>";
										echo "  <td class=\"label_error\">$Valor</td>";
										echo "  <td>$fecha</td>";
										echo "</tr>";
						}
						echo "</table><br>";
		}

	echo ThemeCerrarTabla();
	print(ReturnFooter());
?>

