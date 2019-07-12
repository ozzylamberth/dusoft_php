 <?php
// rips.class.php  10/02/2004
// -------------------------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 IPSOFT SA.
// Email: mail@ipsoft-sa.com
// -------------------------------------------------------------------------------------
// Autor:  Tizziano Perea - Darling Liliana Dorado
// Proposito del Archivo: Clase para creacion de archivos rips.
// -------------------------------------------------------------------------------------


class rips
{
    var $error;
    var $mensajeDeError;
		var $archivo;
		var $ruta;

    function rips()
    {
        return true;
    }


    function GetError()
    {
        return $this->error;
    }


    function MensajeDeError()
    {
        return $this->mensajeDeError;
    }



     /*
    Metodo  para abrir el archivo de rips dependiendo su tipo
    */
    function AbrirArchivo($name,$modo,$envio)
    {
				$ruta=GetVarConfigAplication('DirGeneracionRips');
				if(!file_exists($ruta))
				{
						return false;
				}
				if(!file_exists($ruta.'/ENVIO'.$envio))
				{
							mkdir($ruta.'/ENVIO'.$envio,0777);
				}
				$ruta=$ruta.'/ENVIO'.$envio;
				$file=$ruta.'/'.$name;
			/*
// 				if(!file_exists($file))
// 				{
// 						$this->error = "Error Rips";
// 						$this->mensajeDeError = 'No se pudo crear:'.$file;
// 						return false;
// 				}
*/
				$this->archivo = fopen($file,$modo);
				if(!$this->archivo)
				{
						$this->error = "Error Rips";
						$this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO.';
						return false;
				}

				if(feof($this->archivo))
				{
						$this->error = "Error Rips";
						$this->mensajeDeError = 'Fin del Archivo...';
						return false;
				}
    		return true;

				if(feof($this->archivo))
				{
						$this->error = "Error Rips";
						$this->mensajeDeError = 'Fin del Archivo...';
						return false;
				}
    		return true;
    }



    /*
    Metodo que escribe en el archivo
    */
    function EscribirArchivo($texto)
    {
				fwrite($this->archivo,$texto);
				return true;
    }


    function CerrarArchivo()
    {
      if(!fclose($this->archivo))
      {
						$this->error = "Error Rips";
						$this->mensajeDeError = 'No pude cerrar El archivo...';
						return false;
      }
    	return true;
    }


//---------------------ENVIOS-----------------------------------------------------

	//con envios el vector es asi=> [envio] [empresa]
	function Envios($tipo,$id,$arregloenvio)
	{
			$datos=$this->DatosPrestadorServicio($arregloenvio[0][empresa]);
			$ap=$ac=$ad=$us=$af=0;
			$hospitalizacion=false;
			$dat=$this->DatosRipsEnvios($arregloenvio[0][envio]);
			$plan=$this->DatosPlan($dat[0][plan_id]);
			//con este se pude empezar el CT
			$ter=$this->NombreTercero($tipo,$id);
			for($i=0; $i<sizeof($dat); $i++)
			{		//archivo AF
					//$dat=$this->DatosRipsEnvios($arregloenvio[$i][envio]);
					//$arregloAF[]=$datos[codigo_sgsss].",".$datos[razon_social].",".$datos[tipo_id_tercero].",".$datos[id].",".$dat[$i][prefijo]."".$dat[$i][factura_fiscal].",".$this->FechaStamp($dat[$i][fecha_registro]).",".$this->FechaStamp($dat[$i][fecha_inicial]).",".$this->FechaStamp($dat[$i][fecha_final]).",".$dat[$i][codigo_sgsss].",".$nom.",".$dat[$i][num_contrato].",".$dat[$i][plan_descripcion].",".$dat[$i][poliza].",".$dat[$i][valor_cuota_paciente].",".'0.00'.",".$dat[$i][descuento].",".$dat[$i][total_factura];
					$ing='';
					if(empty($arregloAFGrupo[$dat[$i][prefijo]][$dat[$i][factura_fiscal]]))
					{
						$arregloAF.=$datos[codigo_sgsss].",".$datos[razon_social].",".$datos[tipo_id_tercero].",".$datos[id].",".$dat[$i][prefijo]."".$dat[$i][factura_fiscal].",".$this->FechaStamp($dat[$i][fecha_registro]).",".$this->FechaStamp($dat[$i][fecha_inicial]).",".$this->FechaStamp($dat[$i][fecha_final]).",".$dat[$i][codigo_sgsss].",".$ter[nombre_tercero].",".$dat[$i][num_contrato].",".$dat[$i][plan_descripcion].",".$dat[$i][poliza].",".($dat[$i][valor_cuota_paciente]+$dat[$i][valor_cuota_moderadora]).",".'0.00'.",".$dat[$i][descuento].",".$dat[$i][total_factura]."\x0a";
						$arregloAFGrupo[$dat[$i][prefijo]][$dat[$i][factura_fiscal]]=true;
						//archivo US
						$ing=$this->BuscarIngresos($dat[$i][factura_fiscal],$dat[$i][prefijo]);
					}
					$af++;

					//archivo US
					//$ing=$this->BuscarIngresos($arregloenvio[0][envio],$dat[$i][factura_fiscal],$dat[$i][prefijo]);

					for($k=0; $k<sizeof($ing); $k++)
					{
								$usu=$this->DatosUsuario($ing[$k][tipo_id_paciente],$ing[$k][paciente_id]);
								$Edad=CalcularEdad($usu[fecha_nacimiento],date('Y-m-d'));
								//$arregloUS[]=$usu[tipo_id_paciente].",".$usu[paciente_id].",".$dat[$i][codigo_sgsss].",".$ing[$k][tipo_afiliado_id].",".$usu[primer_apellido].",".$usu[segundo_apellido].",".$usu[primer_nombre].",".$usu[segundo_nombre].",".$Edad[edad_rips].",".$Edad[unidad_rips].",".$usu[sexo_id].",".$usu[tipo_dpto_id].",".$usu[tipo_mpio_id].",".$usu[zona_residencia];
								$arregloUS.=$usu[tipo_id_paciente].",".$usu[paciente_id].",".$ter[codigo_sgsss].",".$plan[regimen_id].",".$usu[primer_apellido].",".$usu[segundo_apellido].",".$usu[primer_nombre].",".$usu[segundo_nombre].",".$Edad[edad_rips].",".$Edad[unidad_rips].",".$usu[sexo_id].",".$usu[tipo_dpto_id].",".$usu[tipo_mpio_id].",".$usu[zona_residencia]."\x0a";
								$us++;

								//archivo AD
								//trae lo de cuentas detalle
								$con=$this->DatosConsulta($ing[$k][numerodecuenta]);
								//aqui va a hacer por cada item de la cuenta
								for($j=0; $j<sizeof($con); $j++)
								{

										//esto trae la autorizacion y numer_orden_id de uns os de la cuenta
										//$pro='';
										//$pro=$this->BuscarProcedimientos($con[$j][transaccion]);
										$auto='';
										//si hay autorizacion externa
										//if(!empty($pro[autorizacion_ext]) AND $pro[autorizacion_ext]>1)
										if(!empty($con[$j][autorizacion_ext]) AND $con[$j][autorizacion_ext]>1)
										{echo "<br>entro";
												//$aut=$this->AutorizacionExterna($pro[autorizacion_ext]);
												$aut=$this->AutorizacionExterna($con[$j][autorizacion_ext]);

												if(!empty($aut[0][codelect]))
												{  $auto=$aut[0][codelect];  }
												elseif(!empty($aut[0][codelectsos]))
												{  $auto=$aut[0][codelectsos];  }
												elseif(!empty($aut[0][codelescr]))
												{  $auto=$aut[0][codelescr];  }
												elseif(!empty($aut[0][codcert]))
												{  $auto=$aut[0][codcert];  }
												elseif(!empty($aut[0][codtel]))
												{  $auto=$aut[0][codtel];  }
												elseif(!empty($aut[0][codsos]))
												{  $auto=$aut[0][codsos];  }
										}

										//arciho AC
										//mira si es un cargo cita
										//$con[$j][cargo_cups]=890201;
										$cita=$this->BuscaCita($con[$j][cargo_cups]);
										
										$tipoRips='';
										if(empty($cita) AND empty($con[$j][consecutivo]))
										{										
												$tipoRips=$this->BuscarTipoRips($con[$j][grupo_tarifario_id],$con[$j][subgrupo_tarifario_id],$con[$j][tarifario_id],$con[$j][cargo],$con[$j][empresa_id]);
										}

										if(!empty($cita))
										{
													$vectorAD['VECTOR'][$con[$j][concepto_rips]][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['cantidad']+=$con[$j][cantidad];
													$vectorAD['VECTOR'][$con[$j][concepto_rips]][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['codigo_sgsss']=$datos[codigo_sgsss];
													$vectorAD['VECTOR'][$con[$j][concepto_rips]][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['precio']+=$con[$j][precio];
													$vectorAD['VECTOR'][$con[$j][concepto_rips]][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['valor_cargo']+=$con[$j][valor_cargo];
													$vectorAD['VECTOR'][$con[$j][concepto_rips]][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['factura_fiscal']=$dat[$i][factura_fiscal];
													$vectorAD['VECTOR'][$con[$j][concepto_rips]][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['prefijo']=$dat[$i][prefijo];
													$vectorAD['VECTOR'][$con[$j][concepto_rips]][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['concepto_rips']=$con[$j][concepto_rips];

													$cit=$this->DatosPrincipalesCita($ing[$k][ingreso]);
													$diagp=$diag='';
													for($m=0; $m<sizeof($cit); $m++)
													{
																if(!empty($cit[$m][sw_principal]))
																{
																		$diagp=$cit[$m][tipo_diagnostico_id];
																		$tipo=$cit[$m][tipo_diagnostico];
																}
																else
																{  $diag[]=$cit[$m][tipo_diagnostico_id];  }
													}
													//$arregloAC[]=$dat[$i][prefijo]."".$dat[$i][factura_fiscal].",".$dat[$i][codigo_sgsss].",".$usu[tipo_id_paciente].",".$usu[paciente_id].",".$con[$j][cargo_cups].",".$cit[0][tipo_finalidad_id].",".$cit[0][tipo_atencion_id].",".$diagp.",".$diag[0][tipo_diagnostico_id].",".$diag[1][tipo_diagnostico_id].",".$diag[2][tipo_diagnostico_id];
													$FC=$os='';
													$os=$this->BuscarNumeroOrden($con[$j][transaccion]);
													$FC=$this->FechaCita($os);
													if(empty($FC))
													{  $FC=$con[$j][fecha_cargo];  }

													$arregloAC.=$dat[$i][prefijo]."".$dat[$i][factura_fiscal].",".$datos[codigo_sgsss].",".$usu[tipo_id_paciente].",".$usu[paciente_id].",".$this->FechaStamp($FC).",".$auto.",".$con[$j][cargo_cups].",".$cit[0][tipo_finalidad_id].",".$cit[0][tipo_atencion_id].",".$diagp.",".$diag[0].",".$diag[1].",".$diag[2].",".$tipo.",".$con[$j][valor_cargo].",".$con[$j][valor_cuota_moderadora].",".$con[$j][valor_total_empresa]."\x0a";
													$ac++;
       							}

										//elseif(($con[$j][grupo_tipo_cargo]=='INR' OR $con[$j][grupo_tipo_cargo]=='IRX' OR $con[$j][grupo_tipo_cargo]=='QX' OR $con[$j][grupo_tipo_cargo]=='PNQ' OR $con[$j][grupo_tipo_cargo]=='LB')
										//		   AND empty($con[$j][consecutivo]))
										elseif(!empty($tipoRips) AND ($tipoRips=='AP' OR  $tipoRips=='OP') AND empty($con[$j][consecutivo]))
										{//es una os procedimiento y no es cita
												$vectorAD['VECTOR'][$con[$j][concepto_rips]][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['cantidad']+=$con[$j][cantidad];
												$vectorAD['VECTOR'][$con[$j][concepto_rips]][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['codigo_sgsss']=$datos[codigo_sgsss];
												$vectorAD['VECTOR'][$con[$j][concepto_rips]][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['precio']+=$con[$j][precio];
												$vectorAD['VECTOR'][$con[$j][concepto_rips]][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['valor_cargo']+=$con[$j][valor_cargo];
												$vectorAD['VECTOR'][$con[$j][concepto_rips]][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['factura_fiscal']=$dat[$i][factura_fiscal];
												$vectorAD['VECTOR'][$con[$j][concepto_rips]][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['prefijo']=$dat[$i][prefijo];
												$vectorAD['VECTOR'][$con[$j][concepto_rips]][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['concepto_rips']=$con[$j][concepto_rips];
												
												$ambito='';
												$ambito=$this->BuscarAmbito($con[$j][servicio_cargo]);
												//arciho AP
												//----es normal
												if($tipoRips=='AP')
												{  $arregloAP.=$dat[$i][prefijo]."".$dat[$i][factura_fiscal].",".$datos[codigo_sgsss].",".$usu[tipo_id_paciente].",".$usu[paciente_id].",".$this->FechaStamp($con[$j][fecha_cargo]).",".$auto.",".$con[$j][cargo_cups].",$ambito,1,,,,,,".$con[$j][valor_cargo]."\x0a";  }
												elseif($tipoRips=='OP')
												{		//---------es de odontologia
														$os=$this->BuscarNumeroOrden($con[$j][transaccion]);
														$diaOP= $this->BuscarDiagnosticosOdontologiaAP($os);
														$arregloAP.=$dat[$i][prefijo]."".$dat[$i][factura_fiscal].",".$datos[codigo_sgsss].",".$usu[tipo_id_paciente].",".$usu[paciente_id].",".$this->FechaStamp($con[$j][fecha_cargo]).",".$auto.",".$con[$j][cargo_cups].",$ambito,1,5,$diaOP,,,".$con[$j][valor_cargo]."\x0a";
												}
												$ap++;
										}
										elseif(!empty($con[$j][consecutivo]))
										{		//medicamentos
												//si hay autorizacion externa
												if(!empty($con[$j][autorizacion_ext]))
												{
														$aut='';
														$aut=$this->AutorizacionExterna($con[$j][autorizacion_ext]);

														if(!empty($aut[codelect]))
														{  $auto=$aut[codelect];  }
														elseif(!empty($aut[codelectsos]))
														{  $auto=$aut[codelectsos];  }
														elseif(!empty($aut[codelescr]))
														{  $auto=$aut[codelescr];  }
														elseif(!empty($aut[codcert]))
														{  $auto=$aut[codcert];  }
														elseif(!empty($aut[codtel]))
														{  $auto=$aut[codtel];  }
												}

												$med='';
												$med=$this->DatosMedicamentos($con[$j][consecutivo]);
												//medicamentos
												if(!empty($med))
												{
														$concepto_rips='';
														if($med[sw_pos]==1)//pos
														{  $concepto_rips=12;  }
														elseif($med[sw_pos]==2)//no pos
														{  $concepto_rips=13;  }

														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['cantidad']+=$con[$j][cantidad];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['codigo_sgsss']=$datos[codigo_sgsss];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['precio']+=$con[$j][precio];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['valor_cargo']+=$con[$j][valor_cargo];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['factura_fiscal']=$dat[$i][factura_fiscal];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['prefijo']=$dat[$i][prefijo];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['concepto_rips']=$concepto_rips;

														//archivo AM
														$arregloAM.=$dat[$i][prefijo]."".$dat[$i][factura_fiscal].",".$datos[codigo_sgsss].",".$usu[tipo_id_paciente].",".$usu[paciente_id].",".$auto.",".$med[codigo_producto].",".$med[sw_pos].",".$med[descripcion_abreviada].",".substr($med[farmaco],0,20).",".substr($med[concentracion_forma_farmacologica],0,20).",".substr($med[unidad],0,20).",".$con[$j][cantidad].",".$con[$j][precio].",".$con[$j][valor_cargo]."\x0a";
														$am++;
												}
												else
												{
														$concepto_rips='09';
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['cantidad']+=$con[$j][cantidad];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['codigo_sgsss']=$datos[codigo_sgsss];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['precio']+=$con[$j][precio];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['valor_cargo']+=$con[$j][valor_cargo];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['factura_fiscal']=$dat[$i][factura_fiscal];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['prefijo']=$dat[$i][prefijo];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['concepto_rips']=$concepto_rips;

														//es insumos
														//archivo AT
														$ins='';
														$ins=$this->DatosInsumos($con[$j][consecutivo]);
														$arregloAT.=$dat[$i][prefijo]."".$dat[$i][factura_fiscal].",".$datos[codigo_sgsss].",".$usu[tipo_id_paciente].",".$usu[paciente_id].",".$auto.",1,,".$ins[descripcion_abreviada].",".$con[$j][cantidad].",".$con[$j][precio].",".$con[$j][valor_cargo]."\x0a";
														$at++;
												}
										}
										else
										{
												$hono='';
												$hono=$this->DatosHonorarios($con[$j][transaccion]);
												if(!empty($hono))
												{
														$concepto_rips='07';
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['cantidad']+=$con[$j][cantidad];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['codigo_sgsss']=$datos[codigo_sgsss];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['precio']+=$hono[valor];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['valor_cargo']+=$hono[valor];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['factura_fiscal']=$dat[$i][factura_fiscal];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['prefijo']=$dat[$i][prefijo];
														$vectorAD['VECTOR'][$concepto_rips][$dat[$i][prefijo]][$dat[$i][factura_fiscal]]['concepto_rips']=$concepto_rips;

														$arregloAT.=$dat[$i][prefijo]."".$dat[$i][factura_fiscal].",".$datos[codigo_sgsss].",".$usu[tipo_id_paciente].",".$usu[paciente_id].",".$auto.",4,,honorarios,".$con[$j][cantidad].",".$hono[valor].",".$hono[valor]."\x0a";
														$at++;
												}
												else
												{
														//camas
														$cama=$this->BuscaCama($con[$j][transaccion]);
														if(!empty($cama))
														{
																$arregloAT.=$dat[$i][prefijo]."".$dat[$i][factura_fiscal].",".$datos[codigo_sgsss].",".$usu[tipo_id_paciente].",".$usu[paciente_id].",".$auto.",3,".$con[$j][cargo].",".substr($con[$j][descripcion],0,60).",".$con[$j][cantidad].",".$hono[valor].",".$hono[valor]."\x0a";
																$at++;

																if(!empty($causaExt) OR empty($diagEgre) OR empty($estado))
																{
																		$causaExt=$this->CausaExterna($ing[$k][ingreso]);
																		$diagEgre=$this->DatosEgresoPaciente($ing[$k][ingreso]);
																		$diagEp=$diagE='';
																		for($m=0; $m<sizeof($diagEgre); $m++)
																		{
																					if(!empty($diagEgre[$m][sw_principal]))
																					{
																							$diagEp=$diagEgre[$m][tipo_diagnostico_id];
																					}
																					else
																					{  $diagE[]=$diagEgre[$m][tipo_diagnostico_id];  }
																		}

																		$def=$this->DatosDefuncion($ing[$k][ingreso]);
																		if(!empty($def))
																		{  $estado=2;   }//muerto
																		else
																		{  $estado=1;   }//vivo
																}

																//es observacion
																if($cama[tipo_clase_cama_id]==3)
																{
																		$destino='';
																		$destino=$this->DestinoPaciente($ing[$k][ingreso],$ing[$k][numerodecuenta]);

																		$arregloAU.=$dat[$i][prefijo]."".$dat[$i][factura_fiscal].",".$datos[codigo_sgsss].",".$usu[tipo_id_paciente].",".$usu[paciente_id].",".$this->FechaStamp($cama[fecha_ingreso]).",".$this->HoraStamp($cama[fecha_ingreso]).",".$auto.",".$causaExt.",".$diagEp.",".$diagE[0].",".$diagE[1].",".$diagE[2].",".$destino.",".$estado.",".$def.",".$this->FechaStamp($cama[fecha_egreso]).",".$this->HoraStamp($cama[fecha_egreso])."\x0a";
																		$au++;
																}
																else
																{  $hospitalizacion=true;  }
														}
														else
														{		//OTROS SERVICIOS
																$arregloAT.=$dat[$i][prefijo]."".$dat[$i][factura_fiscal].",".$datos[codigo_sgsss].",".$usu[tipo_id_paciente].",".$usu[paciente_id].",".$auto.",,".$con[$j][cargo].",,".$con[$j][cantidad].",".$con[$j][precio].",".$con[$j][valor_cargo]."\x0a";
																$at++;
														}
												}
										}
								}//fin for archivo AD
					}//fin for archivo US termina el ingreso
					//verificacion si hay hospitalizacion
					if(!empty($hospitalizacion))
					{		//hay hospitalizacion
							if(!empty($ing[0][autorizacion_ext]) AND $ing[0][autorizacion_ext]>1)
							{
									$autoH='';
									$autH=$this->AutorizacionExterna($con[$j][autorizacion_ext]);

									if(!empty($autH[0][codelect]))
									{  $autoH=$autH[0][codelect];  }
									elseif(!empty($autH[0][codelectsos]))
									{  $autoH=$autH[0][codelectsos];  }
									elseif(!empty($autH[0][codelescr]))
									{  $autoH=$autH[0][codelescr];  }
									elseif(!empty($autH[0][codcert]))
									{  $autoH=$autH[0][codcert];  }
									elseif(!empty($autH[0][codtel]))
									{  $autoH=$autH[0][codtel];  }
									elseif(!empty($autH[0][codsos]))
									{  $autoH=$autH[0][codsos];  }
							}
							$fechas='';
							$fechas=$this->FechasHospitalizacion($ing[0][numerodecuenta]);

							$diagIngreso=$this->DiagnosticoIngreso($ing[0][ingreso]);
							$id=sizeof($fechas)-1;
							$arregloAH=$dat[$i][prefijo]."".$dat[$i][factura_fiscal].",".$datos[codigo_sgsss].",".$usu[tipo_id_paciente].",".$usu[paciente_id].",".$ing[0][via_ingreso_id].",".$this->FechaStamp($fechas[0][fecha_ingreso]).",".$this->HoraStamp($fechas[0][fecha_ingreso]).",".$autoH.",".$causaExt.",".$diagIngreso.",".$diagEp.",".$diagE[0].",".$diagE[1].",".$diagE[2].",,".$estado.",".$def.",".$this->FechaStamp($fechas[$id][fecha_ingreso]).",".$this->HoraStamp($fechas[$id][fecha_ingreso])."\x0a";
							$ah++;
					}
					//fin hospitalizacion
			}

			foreach($vectorAD as $k => $v)
			{
				foreach($v as $k1 => $v1)
				{
					foreach($v1 as $k2 => $v2)
					{
							foreach($v2 as $k3 => $v3)
							{
									//$v3[precio] = $v3[valor_cargo]/$v3[cantidad];
									//".$v3[precio]." esto va en blanco
									$arregloAD.=$v3[prefijo]."".$v3[factura_fiscal].",".$v3[codigo_sgsss].",".$v3[concepto_rips].",".$v3[cantidad].",,".$v3[valor_cargo]."\x0a";
									$ad++;
							}
					}
				}
			}
			echo "<br>AF=>";print_r($arregloAF);
			//echo "<br><br>US=>";print_r($arregloUS);
			echo "<br><br>AD=>";print_r($arregloAD);
			echo "<br><br>AC=>";print_r($arregloAC);
			echo "<br><br>AH=>";print_r($arregloAH);
			echo "<br><br>AP=>";print_r($arregloAP);
			echo "<br><br>AM=>";print_r($arregloAM);
			echo "<br><br>AT=>";print_r($arregloAT);
			echo "<br><br>AU=>";print_r($arregloAU);

			$arregloenvio[0][envio]=str_pad($arregloenvio[0][envio], 6, "0", STR_PAD_LEFT);

			if(!$this->AbrirArchivo('AF'.$arregloenvio[0][envio].'.txt','w+',$arregloenvio[0][envio]))
			{
					return false;
			}
			$this->EscribirArchivo($arregloAF);
			$this->CerrarArchivo();
			$arregloCT .= $datos[codigo_sgsss].",".date("d/m/Y").",AF".$arregloenvio[0][envio].",".$af."\x0a";

			if(!$this->AbrirArchivo('US'.$arregloenvio[0][envio].'.txt','w+',$arregloenvio[0][envio]))
			{
					return false;
			}
			$this->EscribirArchivo($arregloUS);
			$this->CerrarArchivo();
			$arregloCT .= $datos[codigo_sgsss].",".date("d/m/Y").",US".$arregloenvio[0][envio].",".$us."\x0a";

			if(!empty($arregloAD))
			{
					if(!$this->AbrirArchivo('AD'.$arregloenvio[0][envio].'.txt','w+',$arregloenvio[0][envio]))
					{
							return false;
					}
					$this->EscribirArchivo($arregloAD);
					$this->CerrarArchivo();
					$arregloCT .= $datos[codigo_sgsss].",".date("d/m/Y").",AD".$arregloenvio[0][envio].",".$ad."\x0a";
			}
			if(!empty($arregloAC))
			{
					if(!$this->AbrirArchivo('AC'.$arregloenvio[0][envio].'.txt','w+',$arregloenvio[0][envio]))
					{
							return false;
					}
					$this->EscribirArchivo($arregloAC);
					$this->CerrarArchivo();
					$arregloCT .= $datos[codigo_sgsss].",".date("d/m/Y").",AC".$arregloenvio[0][envio].",".$ac."\x0a";
			}
			if(!empty($arregloAP))
			{
					if(!$this->AbrirArchivo('AP'.$arregloenvio[0][envio].'.txt','w+',$arregloenvio[0][envio]))
					{
							return false;
					}

					$this->EscribirArchivo($arregloAP);
					$this->CerrarArchivo();
					$arregloCT .= $datos[codigo_sgsss].",".date("d/m/Y").",AP".$arregloenvio[0][envio].",".$ap."\x0a";
			}
			if(!empty($arregloAM))
			{
					if(!$this->AbrirArchivo('AM'.$arregloenvio[0][envio].'.txt','w+',$arregloenvio[0][envio]))
					{
							return false;
					}
					$this->EscribirArchivo($arregloAM);
					$this->CerrarArchivo();
					$arregloCT .= $datos[codigo_sgsss].",".date("d/m/Y").",AM".$arregloenvio[0][envio].",".$am."\x0a";
			}
			if(!empty($arregloAT))
			{
					if(!$this->AbrirArchivo('AT'.$arregloenvio[0][envio].'.txt','w+',$arregloenvio[0][envio]))
					{
							return false;
					}
					$this->EscribirArchivo($arregloAT);
					$this->CerrarArchivo();
					$arregloCT .= $datos[codigo_sgsss].",".date("d/m/Y").",AT".$arregloenvio[0][envio].",".$at."\x0a";
			}
			if(!empty($arregloAU))
			{
					if(!$this->AbrirArchivo('AU'.$arregloenvio[0][envio].'.txt','w+',$arregloenvio[0][envio]))
					{
							return false;
					}
					$this->EscribirArchivo($arregloAU);
					$this->CerrarArchivo();
					$arregloCT .= $datos[codigo_sgsss].",".date("d/m/Y").",AU".$arregloenvio[0][envio].",".$au."\x0a";
			}
			if(!empty($arregloAH))
			{
					if(!$this->AbrirArchivo('AH'.$arregloenvio[0][envio].'.txt','w+',$arregloenvio[0][envio]))
					{
							return false;
					}
					$this->EscribirArchivo($arregloAH);
					$this->CerrarArchivo();
					$arregloCT .= $datos[codigo_sgsss].",".date("d/m/Y").",AH".$arregloenvio[0][envio].",".$ah."\x0a";
			}
			if(!$this->AbrirArchivo('CT'.$arregloenvio[0][envio].'.txt','w+',$arregloenvio[0][envio]))
			{
					return false;
			}
			$this->EscribirArchivo($arregloCT);
			$this->CerrarArchivo();

			return true;
	}

//------------------------------------------------------------------------------------------
	function BuscarDiagnosticosOdontologiaAP($numeroOs)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT hc_os_solicitud_id FROM os_maestro
								WHERE numero_orden_id=$numeroOs;";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}	
			$solicitud=$result->fields[0];
			$result->Close();	
			
			$query = "SELECT b.diagnostico_id, b.sw_principal
								FROM hc_odontogramas_primera_vez_detalle  as a, hc_odontogramas_tratamientos_evolucion_primera_vez as b
								WHERE a.hc_os_solicitud_id=$solicitud and a.hc_odontograma_primera_vez_detalle_id=b.hc_odontograma_primera_vez_detalle_id
								order by b.sw_principal desc;";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}				
			//encontro los diagnosticos
			if(!$result->EOF)	
			{		$diag='';
					$i=0;
					while(!$result->EOF AND $i<=1)
					{
							if($i==0)
							{  $diag=$result->fields[0];  }
							elseif($i==1)
							{  $diag.=','.$result->fields[0];  }
							$i++;
							$result->MoveNext();
					}
					if($i==1)
					{ $diag.=',';}
					return 	$diag;
			}
			else
			{
					$query = "SELECT b.diagnostico_id, b.sw_principal
										FROM hc_odontogramas_tratamientos_detalle  as a, hc_odontogramas_tratamientos_evolucion_tratamiento as b
										WHERE a.hc_os_solicitud_id=$solicitud and a.hc_odontograma_tratamiento_detalle_id=b.hc_odontograma_tratamiento_detalle_id
										order by b.sw_principal desc;";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}		
					//encontro los diagnosticos
					if(!$result->EOF)	
					{		$diag='';
							$i=0;
							while(!$result->EOF AND $i<=1)
							{
									if($i==0)
									{  $diag=$result->fields[0];  }
									elseif($i==1)
									{  $diag.=','.$result->fields[0];  }
									$i++;
									$result->MoveNext();
							}
							if($i==1)
							{ $diag.=',';}
							return 	$diag;					
					}			
					else
					{
						echo	$query = "SELECT diagnostico_id, sw_principal
												FROM hc_odontogramas_tratamientos_evolucion_presupuesto
												WHERE hc_os_solicitud_id=$solicitud order by sw_principal desc;";
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}	
							//encontro los diagnosticos
							if(!$result->EOF)	
							{		$diag='';
									$i=0;
									while(!$result->EOF AND $i<=1)
									{
											if($i==0)
											{  $diag=$result->fields[0];  }
											elseif($i==1)
											{  $diag.=','.$result->fields[0];  }
											$i++;
											$result->MoveNext();
									}
									if($i==1)
									{ $diag.=',';}
									return 	$diag;							
							}	
							else
							{
								echo	$query = "SELECT diagnostico_id, sw_principal
														FROM hc_odontogramas_tratamientos_evolucion_apoyod
														WHERE hc_os_solicitud_id=$solicitud order by sw_principal desc;";
									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
									//encontro los diagnosticos
									if(!$result->EOF)	
									{		$diag='';
											$i=0;
											while(!$result->EOF AND $i<=1)
											{
													if($i==0)
													{  $diag=$result->fields[0];  }
													elseif($i==1)
													{  $diag.=','.$result->fields[0];  }
													$i++;
													$result->MoveNext();
											}
											if($i==1)
											{ $diag.=',';}
											return 	$diag;							
									}
									else
									{ return "error no se guardaron solicitud=>$solicitud"; }																		
							}											
					}		
			}
	}

	function BuscarTipoRips($grupo,$subgrupo,$tarifario,$cargo,$empresa)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT rips_tipo_id
								FROM rips_parametros_tipos_excepciones 
								WHERE cargo='$cargo' and tarifario_id='$tarifario' and empresa_id='$empresa'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if(!$result->EOF)
			{		//tiene excepcion
					return $result->fields[0];
			}
			else
			{
					$query = "SELECT rips_tipo_id
										FROM rips_parametros_tipos
										WHERE empresa_id='$empresa' and grupo_tarifario_id='$grupo' 
										and subgrupo_tarifario_id='$subgrupo'";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}	
					
					if(!$result->EOF)
					{		return $result->fields[0];  	}	
					else					
					{ 	//no estan llenas las tablas
							return 0;	  
					}
			}
	}
	
	function BuscarAmbito($servicio)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT ambito_rips_id FROM servicios WHERE servicio=$servicio";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if(!$result->EOF)
			{
					$var=$result->fields[0];
			}

			$result->Close();
			return $var;
	}

	function FechasHospitalizacion($cuenta)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT fecha_ingreso, fecha_egreso
								FROM movimientos_habitacion WHERE numerodecuenta=$cuenta";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
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

	function DiagnosticoIngreso($ingreso)
	{
			list($dbconn) = GetDBconn();
			$query = "  SELECT c.tipo_diagnostico_id
									FROM	hc_diagnosticos_ingreso as c, hc_evoluciones as d
									WHERE d.ingreso=$ingreso and c.evolucion_id=d.evolucion_id
									and c.sw_principal='1'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if(!$result->EOF)
			{
					$var=$result->fields[0];
			}

			$result->Close();
			return $var;
	}


	/**
	*
	*/
	function BuscarNumeroOrden($transaccion)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.numero_orden_id
								FROM os_maestro_cargos as a	WHERE a.transaccion=$transaccion";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if(!$result->EOF)
			{  $var=$result->fields[0];  }
			$result->Close();
			return $var;
	}

	function DatosHonorarios($transaccion)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT b.valor
								FROM cuentas_detalle_honorarios as b
								WHERE b.transaccion=$transaccion";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if(!$result->EOF)
			{
					$var=$result->GetRowAssoc($ToUpper = false);
			}

			$result->Close();
			return $var;
	}

	function DestinoPaciente($ingreso,$cuenta)
	{
			list($dbconn) = GetDBconn();
			$var='';
			//alta de urgencias
			$query = "  SELECT a.sw_estado FROM pacientes_urgencias as a
									WHERE a.ingreso=$ingreso AND a.sw_estado IN('4','2')";
			/*$query = "  SELECT case when a.sw_estado=2 then 3 when a.sw_estado=4 then 1 end as estado
									FROM pacientes_urgencias as a WHERE a.ingreso=$ingreso";*/
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			if(!$result->EOF)
			{
					if($result->fields[0]==4)
					{   $var=1;  }
					else
					{		//hospitalizado
							$query = "SELECT b.tipo_clase_cama_id
												FROM movimientos_habitacion as a, tipos_camas as b
												WHERE a.numerodecuenta=$cuenta
												and a.tipo_cama_id=b.tipo_cama_id and b.tipo_clase_cama_id<>'3'";
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
							if(!$result->EOF)
							{
									$var=3;
							}
							else
							{		//remirido
									$query = "  SELECT ingreso FROM hc_conducta_remision WHERE ingreso=$ingreso";
									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
									if(!$result->EOF)
									{
											$var=2;
									}
									else
									{  $var=1;  }
							}
					}
			}
			else
			{		//remirido
					$query = "  SELECT ingreso FROM hc_conducta_remision WHERE ingreso=$ingreso";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					if(!$result->EOF)
					{
							$var=2;
					}
					else
					{		//hospitalizado
							$query = "SELECT b.tipo_clase_cama_id
												FROM movimientos_habitacion as a, tipos_camas as b
												WHERE a.numerodecuenta=$cuenta
												and a.tipo_cama_id=b.tipo_cama_id and b.tipo_clase_cama_id<>'3'";
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
							if(!$result->EOF)
							{
									$var=3;
							}
					}
			}

			$result->Close();
			return $var;
	}

	function DatosDefuncion($ingreso)
	{
			list($dbconn) = GetDBconn();
			$query = "  SELECT c.diagnostico_defuncion_id
									FROM hc_conducta_defuncion as a, hc_conducta_diagnosticos_defuncion as c
									WHERE a.ingreso=$ingreso and a.evolucion_id=c.evolucion_id
									and c.sw_principal='1'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if(!$result->EOF)
			{
					$var=$result->fields[0];
			}

			$result->Close();
			return $var;
	}

	function CausaExterna($ingreso)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT tipo_atencion_id FROM hc_atencion	WHERE ingreso=$ingreso";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			$result->Close();
			return $result->fields[0];
	}

	function DatosEgresoPaciente($ingreso)
	{
			list($dbconn) = GetDBconn();
			$query = "  SELECT c.tipo_diagnostico_id, c.sw_principal, c.tipo_diagnostico
									FROM hc_diagnosticos_egreso as c, hc_evoluciones as d
									WHERE d.ingreso=$ingreso
									and c.evolucion_id=d.evolucion_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
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

	function BuscaCama($transaccion)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.fecha_ingreso, a.fecha_egreso, b.tipo_clase_cama_id
								FROM movimientos_habitacion as a, tipos_camas as b
								WHERE a.transaccion=$transaccion
								and a.tipo_cama_id=b.tipo_cama_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			while(!$result->EOF)
			{
					$var=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}

			$result->Close();
			return $var;
	}


	function FechaCita($numorden)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT e.fecha_turno
								FROM os_cruce_citas as a, agenda_citas_asignadas as b,
								agenda_citas as c, agenda_turnos as e
								WHERE a.numero_orden_id=$numorden
								and a.agenda_cita_asignada_id=b.agenda_cita_asignada_id
								and b.agenda_cita_id=c.agenda_cita_id
								and c.agenda_turno_id=e.agenda_turno_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}


			$result->Close();
			return $result->fields[0];
	}

	function DatosPlan($plan)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT b.regimen_id
								FROM planes as a left join tipos_cliente as b on(a.tipo_cliente=b.tipo_cliente)
								WHERE a.plan_id=$plan  ";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}


			$var=$result->GetRowAssoc($ToUpper = false);
			$result->Close();
			return $var;
	}

	/**
	*
	*/
	function BuscarProcedimientos($transaccion)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT c.autorizacion_int, c.autorizacion_ext, b.numero_orden_id
								FROM os_maestro_cargos as a, os_maestro as b, os_ordenes_servicios as c
								WHERE a.transaccion=$transaccion
								and a.numero_orden_id=b.numero_orden_id
								and b.orden_servicio_id=c.orden_servicio_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if(!$result->EOF)
			{  $var=$result->GetRowAssoc($ToUpper = false);  }
			$result->Close();
			return $var;
	}

	/**
	*
	*/
	function AutorizacionExterna($auto)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT  c.codigo_autorizacion as codelect,
			 					d.codigo_autorizacion as codelectsos, e.codigo_autorizacion as codescr,
								f.codigo_autorizacion as codcert, g.codigo_autorizacion as codtel
								FROM hc_os_autorizaciones as b
								left join autorizaciones_electronicas as c on(b.autorizacion_ext=c.autorizacion)
								left join autorizaciones_electronicas_sos as d on(b.autorizacion_ext=d.autorizacion)
								left join autorizaciones_escritas as e on(b.autorizacion_ext=e.autorizacion)
								left join autorizaciones_certificados as f on(b.autorizacion_ext=f.autorizacion)
								left join autorizaciones_telefonicas as g on(b.autorizacion_ext=g.autorizacion)

								WHERE b.autorizacion_ext=$auto";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
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
	function DatosRipsEnvios($envio)
	{
	//, e.codigo_sgsss
	//left join terceros_sgsss as e on(c.tipo_id_tercero=e.tipo_id_tercero and c.tercero_id=e.tercero_id)
			list($dbconn) = GetDBconn();
			$query = "select a.envio_id, a.fecha_inicial, a.fecha_final, b.prefijo, b.factura_fiscal,
								c.fecha_registro, x.codigo_sgsss,
								c.total_factura, c.valor_cuota_paciente, c.valor_cuota_moderadora, c.descuento,
								case g.sw_tipo_plan when 3 then 0 else c.total_factura end as total_factura,
								case g.sw_tipo_plan when 3 then 0 else c.valor_cuota_paciente end as valor_cuota_paciente,
								case g.sw_tipo_plan when 3 then 0 else c.valor_cuota_moderadora end as valor_cuota_moderadora,
								case g.sw_tipo_plan when 3 then 0 else c.descuento end as descuento,
								c.tercero_id, g.plan_descripcion,
								case c.tipo_id_tercero when 'NIT' then 'NI' else c.tipo_id_tercero end as tipo_id_tercero,
								g.num_contrato, k.poliza, i.ingreso, i.tipo_afiliado_id, i.numerodecuenta, i.plan_id
								FROM envios as a, envios_detalle as b, fac_facturas as c,
								planes as g left join terceros_sgsss as x
								on(x.tipo_id_tercero=g.tipo_tercero_id AND x.tercero_id=g.tercero_id),
								fac_facturas_cuentas as h,
								cuentas as i left join ingresos_soat as j on (i.ingreso=j.ingreso)
								left join soat_eventos as k on(j.evento=k.evento)
								WHERE a.envio_id=$envio and a.sw_estado in(1,0) and a.envio_id=b.envio_id
								and b.prefijo=c.prefijo and b.factura_fiscal=c.factura_fiscal
								and c.plan_id=g.plan_id and h.numerodecuenta=i.numerodecuenta
								and h.prefijo=c.prefijo and h.factura_fiscal=c.factura_fiscal
								order by c.prefijo,c.factura_fiscal";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
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
	function BuscarIngresos($factura,$prefijo)
	{//envios_detalle as b,  b.envio_id=$envio and
			list($dbconn) = GetDBconn();
			$query = "select j.tipo_id_paciente, j.paciente_id, i.tipo_afiliado_id, i.numerodecuenta,
								j.ingreso, j.via_ingreso_id, j.autorizacion_ext, j.via_ingreso_id,
								j.causa_externa_id
								from fac_facturas_cuentas as h,
								cuentas as i,  ingresos as j
								where h.factura_fiscal=$factura
								and h.prefijo='$prefijo'
								and h.numerodecuenta=i.numerodecuenta
								and i.ingreso=j.ingreso";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
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


//-------------------------------FACTURAS-------------------------------------
//EL vector es asi array('prefijo'=>$a[0],'factura'=>$a[1],'plan'=>$a[2],'empresa'=>$a[3]);
//quite
	/*function Facturas($arregloenvio)
	{
			$dat=$this->DatosPrestadorServicio($arreglofactura[0][empresa]);
			for($i=0; $i<sizeof($arreglofactura); $i++)
			{
					$this->DatosRipsFacturas($arreglofactura[$i][prefijo],$arreglofactura[$i][factura]);
			}
	}

	function DatosRipsFacturas($prefijo,$factura)
	{
			list($dbconn) = GetDBconn();
			$query = "";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
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
	}*/

//-----------------------------------------------------------------------------------

	function DatosPrestadorServicio($empresa)
	{ //quite * del select
			list($dbconn) = GetDBconn();
			$query = "select c.codigo_sgsss,c.razon_social, c.tipo_id_tercero, c.id,
								case c.tipo_id_tercero when 'NIT' then 'NI' else c.tipo_id_tercero end as tipo_id_tercero
								from empresas as c where empresa_id='$empresa'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			$var=$result->GetRowAssoc($ToUpper = false);
			$result->Close();
			return $var;
	}

	function NombreTercero($tipo,$id)
	{
			list($dbconn) = GetDBconn();
			$query = "select a.nombre_tercero, b.codigo_sgsss
								from terceros as a left join terceros_sgsss as b
								on(a.tipo_id_tercero=b.tipo_id_tercero and a.tercero_id=b.tercero_id)
								where a.tipo_id_tercero='$tipo' and a.tercero_id='$id'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			$var=$result->GetRowAssoc($ToUpper = false);
			$result->Close();
			return $var;
	}

	function DatosUsuario($tipo,$id)
	{
			list($dbconn) = GetDBconn();
			$query = "select b.* from  pacientes as b
								where b.tipo_id_paciente='$tipo' and b.paciente_id='$id'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			$var=$result->GetRowAssoc($ToUpper = false);
			$result->Close();
			return $var;
	}


 /**
  * Se encarga de separar la fecha del formato timestamp
  * @access private
  * @return string
  * @param date fecha
  */
 function FechaStamp($fecha)
 {
   if($fecha){
      $fech = strtok ($fecha,"-");
      for($l=0;$l<3;$l++)
      {
        $date[$l]=$fech;
        $fech = strtok ("-");
      }

      return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
   }
 }

	function HoraStamp($hora)
	{
		$hor = strtok ($hora," ");
		for($l=0;$l<4;$l++)
		{
			$time[$l]=$hor;
			$hor = strtok (":");
		}
		$x=explode('.',$time[3]);
		return  $time[1].":".$time[2];
	}

//-------------------------------DATOS CONSULTA---------------------------------

	function DatosConsulta($cuenta)
	{
			$des=ModuloGetVar('app','Facturacion_Fiscal','CargoDescuento');
			$apr=ModuloGetVar('app','Facturacion_Fiscal','CargoAprovechamiento');
			list($dbconn) = GetDBconn();
			$query = "SELECT a.cantidad, b.concepto_rips, d.sw_tipo_plan,
								case d.sw_tipo_plan when 3 then 0 else a.valor_cargo end as valor_cargo,
								case d.sw_tipo_plan when 3 then 0 else a.precio end as precio,
								case d.sw_tipo_plan when 3 then 0 else c.valor_cuota_moderadora end as valor_cuota_moderadora,
								case d.sw_tipo_plan when 3 then 0 else c.valor_cuota_paciente end as valor_cuota_paciente,
								case d.sw_tipo_plan when 3 then 0 else c.valor_total_empresa end as valor_total_empresa,
								a.transaccion, a.cargo_cups, a.fecha_cargo, a.tarifario_id, a.cargo, c.empresa_id, b.grupo_tarifario_id,
								a.consecutivo, a.autorizacion_ext, b.grupo_tipo_cargo, e.descripcion, a.servicio_cargo, b.subgrupo_tarifario_id
								FROM cuentas_detalle as a
								left join cups as b on(a.cargo_cups=b.cargo),
								cuentas as c, planes as d, tarifarios_detalle as e
								WHERE a.numerodecuenta=$cuenta
								AND a.facturado='1'
								and a.cargo not in('$des','$apr')
								and a.numerodecuenta=c.numerodecuenta
								and c.plan_id=d.plan_id
								and a.cargo=e.cargo and a.tarifario_id=e.tarifario_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
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


	function BuscaCita($cups)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT count(cargo_cita)
								FROM cargos_citas WHERE cargo_cita='$cups'";
			/*$query = "SELECT count(cargo)
								FROM cups
								WHERE cargo='$cups' and grupo_tipo_cargo='CM'";*/
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$var=$result->fields[0];
			
			$result->Close();
			return $var;
	}

	function DatosPrincipalesCita($ingreso)
	{
			list($dbconn) = GetDBconn();
			$query = "  SELECT a.tipo_finalidad_id, b.tipo_atencion_id,
									case when a.tipo_finalidad_id='NULL' then '10' when a.tipo_finalidad_id<>'NULL' then a.tipo_finalidad_id end as tipo_finalidad_id,
									case when b.tipo_atencion_id='NULL' then '15' when b.tipo_atencion_id<>'NULL' then b.tipo_atencion_id end as tipo_atencion_id,
									c.tipo_diagnostico_id, c.sw_principal, c.tipo_diagnostico
									FROM	hc_diagnosticos_ingreso as c,
									hc_evoluciones as d left join hc_finalidad as a on(a.evolucion_id=d.evolucion_id)
									left join hc_atencion as b on(b.evolucion_id=d.evolucion_id)
									WHERE d.ingreso=$ingreso and c.evolucion_id=d.evolucion_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
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

	function DatosInsumos($consecutivo)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT e.descripcion_abreviada
								FROM bodegas_documentos_d as c, inventarios_productos as e
								WHERE c.consecutivo=$consecutivo and c.codigo_producto=e.codigo_producto
								";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			$var=$result->GetRowAssoc($ToUpper = false);

			$result->Close();
			return $var;
	}

	function DatosMedicamentos($consecutivo)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT e.descripcion_abreviada, b.descripcion as farmaco,
								case a.sw_pos when 0 then 2 else 1 end as sw_pos,
								case a.sw_pos when 0 then '' else
								a.cod_anatomofarmacologico||''||a.cod_principio_activo||''||a.cod_forma_farmacologica||''||a.cod_concentracion
								end	as codigo_producto,
								d.descripcion as unidad, a.concentracion_forma_farmacologica
								FROM bodegas_documentos_d as c, inventarios_productos as e,
								medicamentos as a, inv_med_cod_forma_farmacologica as b,
								inv_unidades_medida_medicamentos as d
								WHERE c.consecutivo=$consecutivo and c.codigo_producto=e.codigo_producto
								and a.codigo_medicamento=e.codigo_producto
								and a.cod_forma_farmacologica=b.cod_forma_farmacologica
								and a.unidad_medida_medicamento_id=d.unidad_medida_medicamento_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if(!$result->EOF)
			{		$var=$result->GetRowAssoc($ToUpper = false);  	}

			$result->Close();
			return $var;
	}



}//fin clase rips

?>
