<?php

	class plan_sos_prueba extends BDAfiliadosMC
	{
		var $error="";
		var $mensajeDeError="";
		var $plan;
		var $salida=array();

		function plan_sos_prueba($tipoidpaciente,$paciente,$dbtype,$dbhost,$dbuser,$dbpass,$dbname,$dbtabla,$fecha_radicacion,$fecha_vencimiento,$datos,$plan)
		{
			$this->tipoidpaciente=$this->CovertirTipoDocumento($tipoidpaciente);
			//$this->tipoidpaciente=$tipoidpaciente;
			$this->paciente=$paciente;
			$this->dbtype=$dbtype;
			$this->dbhost=$dbhost;
			$this->dbuser=$dbuser;
			$this->dbpass=$dbpass;
			$this->dbname=$dbname;
			$this->campo_nombre_tabla=$dbtabla;
			$this->fecha_radicacion=$fecha_radicacion;
			$this->fecha_vencimiento=$fecha_vencimiento;
			$this->plan=$plan;
			$this->configurarBD(&$datos);
			return true;
		}

	function ConfigurarBD(&$datos)
	{
		$this->campo_Primer_nombre=$datos['campo_Primer_nombre'];
		$this->campo_Segundo_nombre=$datos['campo_Segundo_nombre'];
		$this->campo_Primer_apellido=$datos['campo_Primer_apellido'];
		$this->campo_Segundo_apellido=$datos['campo_Segundo_apellido'];
		$this->campo_tipo_afiliado=$datos['campo_tipo_afiliado'];
		$this->campo_activo=$datos['campo_activo'];
		$this->campo_urgencias=$datos['campo_urgencias'];
		$this->campo_fecha_urgencias=$datos['campo_fecha_urgencias'];
		$this->campo_tipodocumento=$datos['campo_tipodocumento'];
		$this->campo_documento=$datos['campo_documento'];
		$this->campo_empleador=$datos['campo_empleador'];
		$this->campo_edad=$datos['campo_edad'];
		$this->campo_sexo=$datos['campo_sexo'];
		$this->campo_nivel=$datos['campo_nivel'];
		$this->campo_nombre_completo=$datos['campo_nombre_completo'];
		$this->campo_fecha_nacimiento=$datos['campo_fecha_nacimiento'];
		$this->campo_semanas_cotizadas=$datos['campo_semanas_cotizadas'];
		$this->campo_estado_bd=$datos['campo_estado_bd'];
		//cambio dar
		$this->campo_tipo_empleador=$datos['campo_tipo_empleador'];
		$this->campo_id_empleador=$datos['campo_id_empleador'];
		//fin cambio
		//nuevos
		$this->campo_vigencia_inicial=$datos['campo_vigencia_inicial'];
		$this->campo_fecha_retiro=$datos['campo_fecha_retiro'];
		$this->campo_proteccion_laboral=$datos['campo_proteccion_laboral'];
		$this->campo_direccion_afiliado=$datos['campo_direccion_afiliado'];
		$this->campo_telefono_afiliado=$datos['campo_telefono_afiliado'];
		$this->campo_ciudad_afiliado=$datos['campo_ciudad_afiliado'];
		$this->campo_identificacion_cotizante=$datos['campo_identificacion_cotizante'];
		$this->campo_tipo_contrato=$datos['campo_tipo_contrato'];
		$this->campo_direccion_empresa=$datos['campo_direccion_empresa'];
		$this->campo_telefono_empresa=$datos['campo_telefono_empresa'];
		$this->campo_codigo_presta_medico=$datos['campo_codigo_presta_medico'];
		$this->campo_tipo_afiliacion=$datos['campo_tipo_afiliacion'];
		$this->campo_ips_afiliado=$datos['campo_ips_afiliado'];
		$this->campo_empleador_multiple=$datos['campo_empleador_multiple'];
		$this->campo_descripcion_plan=$datos['campo_descripcion_plan'];
		$this->formato_fechas='DD/MM/AAAA';//Ejemplo MM/DD/AAAA or DD/MM/AA
		return true;
	}



		function RetornarDatosCompletos()
		{
			if($this->BuscarDatosMSSQL()==false)
			{
				return false;
			}
			if(empty($this->datosCompletos))
			{
				$this->error="NO EXISTE EL USUARIO";
				$this->mensajeDeError = "EL USUARIO TIPO IDENTIFICACION=$this->tipoidpaciente Y IDENTIFICACION=$this->paciente NO EXISTE";
				return false;
			}
			$this->RetornarDatos();
			return $this->salida;
		}


		function ConvertirResult($result)
		{
			while(!$result->EOF)
			{
				$x=$result->GetRowAssoc(false);
				$prueba[]=array('nombre'=>$x['nmbre_afldo'],'tipodocumento'=>$x['cdgo_tpo_idntfccn'],'documento'=>$x['nmro_idntfccn'],'estado'=>$x['estdo'],'plan'=>$x['dscrpcn_pln']);
				$result->MoveNext();
			}
			$result->close();
			return $prueba;
		}

				//CAMBIO DAR
				//convierte el tipo q viene del combo nuestro al de la bd
				function CovertirTipoDocumento($tipoidpaciente)
				{
						switch($tipoidpaciente)
						{
									case 'CC':
														{
																$tipoidpaciente='CC';
																break;
														}
									case 'CE':
														{
																$tipoidpaciente='CE';
																break;
														}
									case 'TI':
														{
																$tipoidpaciente='TI';
																break;
														}
									case 'RC':
														{
																$tipoidpaciente='RC';
																break;
														}
									case 'PA':
														{
																$tipoidpaciente='PA';
																break;
														}
									case 'MS':
														{
																$tipoidpaciente='MS';
																break;
														}
									case 'NU':
														{
																$tipoidpaciente='NU';
																break;
														}
									case 'AS':
														{
																$tipoidpaciente='AS';
																break;
														}
									default:
														{
																$tipoidpaciente='CC';
																break;
														}
						}
						return $tipoidpaciente;
				}

				function TipoDocumento($tipoidpaciente)
				{
						switch($tipoidpaciente)
						{
									case 'CC':
														{
																$tipoidpaciente='CC';
																break;
														}
									case 'CE':
														{
																$tipoidpaciente='CE';
																break;
														}
									case 'TI':
														{
																$tipoidpaciente='TI';
																break;
														}
									case 'RC':
														{
																$tipoidpaciente='RC';
																break;
														}
									case 'PA':
														{
																$tipoidpaciente='PA';
																break;
														}
									case 'MS':
														{
																$tipoidpaciente='MS';
																break;
														}
									case 'NU':
														{
																$tipoidpaciente='NU';
																break;
														}
									case 'AS':
														{
																$tipoidpaciente='AS';
																break;
														}
									default:
														{
																$tipoidpaciente='CC';
																break;
														}
						}
						return $tipoidpaciente;
				}
				//FIN CAMBIO DAR

		function ProgramaActividad($act)
		{
				switch($act)
				{
                    case '1':
					{
						$actividad=1;
						break;
					}
                    case '2':
					{
						$actividad=1;
						break;
					}
                    case '3':
					{
						$actividad=1;
						break;
					}
                    case '4':
					{
						$actividad=1;
						break;
					}
                    case '5':
					{
						$actividad=1;
						break;
					}
                    case '10':
					{
						$actividad=1;
						break;
					}
                    case '11':
					{
						$actividad=1;
						break;
					}
                    case '13':
					{
						$actividad=1;
						break;
					}
					default:
					{
						$actividad=0;
						break;
					}
				}
				return $actividad;
		}

		function ProgramaUrgencias($urg)
		{
			switch($urg)
			{
				case 'ACTIVO':
				{
						$urgencias=1;
						break;
				}
				default:
				{
						$urgencias=0;
						break;
				}
			}
			return $urgencias;
		}

		function ProgramaFechaUrgencias($fecha)
		{
			if(empty($this->formato_fechas))
			{
					if($fecha<=4)
					{
							$urgencias=1;
					}
					else
					{
							$urgencias=0;
					}
			}
			else
			{
				if(empty($fecha))
				{
					return 0;
				}
				$formato=explode('/',$this->formato_fechas);
				$datos=explode('/',$fecha);
				switch($formato[0])
				{
					case 'AA':
										$ano=$datos[0];
										break;
					case 'DD':
										$dia=$datos[0];
										break;
					case 'AAAA':
										$ano=$datos[0];
										break;
					case 'MM':
										$mes=$datos[0];
										break;
				}
					switch($formato[1])
					{
						case 'AA':
											$ano=$datos[1];
											break;
						case 'DD':
											$dia=$datos[1];
											break;
						case 'AAAA':
											$ano=$datos[1];
											break;
						case 'MM':
											$mes=$datos[1];
											break;
					}
					switch($formato[2])
					{
						case 'AA':
											$ano=$datos[2];
											break;
						case 'DD':
											$dia=$datos[2];
											break;
						case 'AAAA':
											$ano=$datos[2];
											break;
						case 'MM':
											$mes=$datos[2];
											break;
					}
					$a=explode('-',$this->fecha_radicacion);
					if(date("Y-m-d",mktime(1,1,1,$mes,$dia,$ano))>=date("Y-m-d",mktime(1,1,1,$a[1],$a[2],$a[0])))
					{
							$urgencias=1;
					}
					else
					{
							$urgencias=0;
					}
			}
			return $urgencias;
		}

		function ProgramaNivel($niv)
		{
			if($niv=='VACIO')
			{
				return 0;
			}
			return $niv;
		}

		function ProgramaTipoAfiliado($tipo)
		{
			switch($tipo)
			{
				case '1';
									$tipoafiliado=2;
									break;
				case '2':
									$tipoafiliado=1;
									break;
				case '3':
									$tipoafiliado=1;
									break;
				case '4':
									$tipoafiliado=1;
									break;
				case '5':
									$tipoafiliado=1;
									break;
				case '6':
									$tipoafiliado=1;
									break;
				case '7':
									$tipoafiliado=1;
									break;
				case '8':
									$tipoafiliado=1;
									break;
				case '9':
									$tipoafiliado=1;
									break;
			}
			return $tipo;
		}


		//funciones auxiliares para control de la compensacion;

		function SqlGetPacientesUrgencias($tabla,$fecha)
		{
			if(empty($fecha))
			{
				$fecha=$this->fecha_radicacion;
			}
			if(empty($tabla))
			{
				$tabla=$this->campo_nombre_tabla;
			}
				$sql="select * from \"$tabla\" where to_date(urg_hasta,'DD-MM-YYYY')>date('".$fecha."') and urg_hasta!='';";
			return $sql;
		}



		function SqlGetPacientesConNombres($nombres,$apellidos)
		{
			list($conexionlocal)=GetDBconn();
			$a=explode(" ",$nombres);
			$b=explode(" ",$apellidos);
			$sql="select num_contrato from planes where plan_id=".$this->plan.";";
			$result1=$conexionlocal->Execute($sql);
			if ($conexionlocal->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $conexionlocal->ErrorMsg();
				return false;
			}
			$plan=$result1->fields[0];
			$result1->close();
			$sql="exec spPmBuscaPersonasxNombre '".$a[0]."', '".$a[1]."', '".$b[0]."', '".$b[1]."', '$plan';";
			return $sql;
		}



		function SqlGetGrupoFamiliar($tipoidpacientes, $pacienteid)
		{
			list($conexionlocal)=GetDBconn();
			$sql="select codigo_alterno from tipos_id_pacientes where tipo_id_paciente='".$tipoidpacientes."';";
			$result1=$conexionlocal->Execute($sql);
			if ($conexionlocal->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $conexionlocal->ErrorMsg();
				return false;
			}
			$tipoidpaciente=$result1->fields[0];
			$sql="exec spPmTraerGrupoFamiliar '".$tipoidpaciente."', '".$pacienteid."'";
			return $sql;
		}




		function SqlGetNumeroAutorizacion($noAutorizacion,$Prestador)
		{
			 $sql="exec bdSiSalud.dbo.spPmBuscaValidacionCajaPrueba '".$Prestador."', '".$noAutorizacion."'";
			return $sql;
		}





		function PedirAutorizacion($observacion, $fecha, $departamento)
		{
			if($this->BuscarDatosMSSQL()==true)
			{
				list($conexionlocal)=GetDBconn();
				$sql="select codigo_alterno from departamentos where departamento='".$departamento."';";
				$result=$conexionlocal->Execute($sql);
				if ($conexionlocal->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $conexionlocal->ErrorMsg();
					return false;
				}
				$sql="select codigo_alterno from system_usuarios where usuario_id=".UserGetUID().";";
				$result1=$conexionlocal->Execute($sql);
				if ($conexionlocal->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $conexionlocal->ErrorMsg();
					return false;
				}
				if(!empty($result1->fields[0]) and !empty($result->fields[0]))
				{
					$sql="Declare @auto numeric(20,0);
							Declare @auto1 varchar(100);
							exec spPminsertalogValidador '".$result->fields[0]."', @auto, @auto1, '".$result1->fields[0]."', '".$this->datosCompletos['cnsctvo_cdgo_pln']."', '".$this->datosCompletos['cdgo_ips']."', '".$this->datosCompletos['cnsctvo_cdgo_tpo_idntfccn']."', '".$this->datosCompletos['nmro_idntfccn']."', '".$this->datosCompletos['nmro_unco_idntfccn_afldo']."', '".$this->datosCompletos['prmr_aplldo']."', '".$this->datosCompletos['sgndo_aplldo']."', '".$this->datosCompletos['prmr_nmbre']."', '".$this->datosCompletos['sgndo_nmbre']."',
							'".$this->datosCompletos['fcha_ncmnto']."', '".$this->datosCompletos['edd']."', '".$this->datosCompletos['edd_mss']."', '".$this->datosCompletos['edd_ds']."', '".$this->datosCompletos['cnsctvo_cdgo_tpo_undd']."', '".$this->datosCompletos['cnsctvo_cdgo_sxo']."', '".$this->datosCompletos['cnsctvo_cdgo_prntscs']."', '".$this->datosCompletos['inco_vgnca_bnfcro']."', '".$this->datosCompletos['fn_vgnca_bnfcro']."', '".$this->datosCompletos['cnsctvo_cdgo_rngo_slrl']."', '".$this->datosCompletos['cnsctvo_cdgo_tpo_idntfccn_aprtnte']."', '".$this->datosCompletos['nmro_idntfccn_aprtnte']."', '".$this->datosCompletos['nmro_unco_idntfccn_aprtnte']."', '".$this->datosCompletos['rzn_scl']."', '".$this->datosCompletos['cnsctvo_cdgo_pln_pac']."', '".$this->datosCompletos['cptdo']."', '".$this->datosCompletos['cdgo_ips_cptcn']."', '".$this->datosCompletos['cnsctvo_cdgo_estdo_afldo']."', '".$this->datosCompletos['smns_aflcn_ss_ps']."', '".$this->datosCompletos['smns_aflcn_eps_ps']."', '".$this->datosCompletos['smns_aflcn_ss_pc']."', '".$this->datosCompletos['smns_aflcn_eps_pc']."', '".$this->datosCompletos['smns_ctzds_eps']."', '".$this->datosCompletos['txto_cta']."', '".$this->datosCompletos['txto_cpgo']."', '".$this->datosCompletos['drcho']."', '".$this->datosCompletos['cnsctvo_cdgo_tpo_afldo']."', '".$this->datosCompletos['mdlo']."', '".$this->datosCompletos['cnsctvo_cdgo_tpo_cntrto']."', '".$this->datosCompletos['nmro_cntrto']."', '".$this->datosCompletos['cnsctvo_bnfcro']."', '".$this->datosCompletos['obsrvcns']."', '".$this->datosCompletos['fcha_trnsmsn']."', 'S', '".$this->datosCompletos['cnsctvo_cdgo_sde']."', '".$this->datosCompletos['nmro_atrzcn_espcl']."', '".$this->datosCompletos['cnsctvo_cdgo_csa_drcho']."', '".$this->datosCompletos['cnsctvo_prdcto_scrsl']."', '".$this->datosCompletos['cdgo_ofcna_lg']."','0';";
					/*$sql="Declare @cosa numeric(20,0);
							Declare @cosa1 varchar(100);
							exec spPminsertalogValidadorPrueba '".$result->fields[0]."', @cosa, @cosa1, '".$result1->fields[0]."', '".$this->datosCompletos['cnsctvo_cdgo_pln']."', '".$this->datosCompletos['cdgo_ips_prmra']."', '".$this->datosCompletos['cnsctvo_cdgo_tpo_idntfccn']."', '".$this->datosCompletos['nmro_idntfccn']."', '".$this->datosCompletos['nmro_unco_idntfccn_afldo']."', '".$this->datosCompletos['prmr_aplldo']."', '".$this->datosCompletos['sgndo_aplldo']."', '".$this->datosCompletos['prmr_nmbre']."', '".$this->datosCompletos['sgndo_nmbre']."', '".$this->datosCompletos['fcha_ncmnto']."', '".$this->datosCompletos['edd']."', '".$this->datosCompletos['edd_mss']."', '".$this->datosCompletos['edd_ds']."', '".$this->datosCompletos['cnsctvo_cdgo_tpo_undd']."', '".$this->datosCompletos['cnsctvo_cdgo_sxo']."', '".$this->datosCompletos['cnsctvo_cdgo_prntsco']."', '".$this->datosCompletos['inco_vgnca_bnfcro']."', '".$this->datosCompletos['fn_vgnca_bnfcro']."', '".$this->datosCompletos['cnsctvo_cdgo_rngo_slrl']."', '".$this->datosCompletos['empcnsctvo_cdgo_tpo_idntfccn']."', '".$this->datosCompletos['empnmro_idntfccn']."', '".$this->datosCompletos['empnmro_unco_idntfccn_aprtnte']."', '".$this->datosCompletos['emprzn_scl']."', '".$this->datosCompletos['cnsctvo_cdgo_pln_pc']."', '".$this->datosCompletos['concapitado']."', '".$this->datosCompletos['concdgo_ips']."', '".$this->datosCompletos['cnsctvo_cdgo_estdo_drcho']."', '".$this->datosCompletos['smns_ctzds_ss_ps']."', '".$this->datosCompletos['smns_ctzds_ss_pc']."', '', '', '".$this->datosCompletos['dscrpcn_drcho']."', '".$this->datosCompletos['concnsctvo_cdgo_tpo_cntrto']."', '3100', '', '".$this->datosCompletos['connmro_cntrto']."', '".$this->datosCompletos['cnsctvo_bnfcro']."', '$observacion', '$fecha', '".$this->datosCompletos['orgn']."', '".$this->datosCompletos['cnsctvo_cdgo_sde_ips']."', '0', '1', '1', '';";
							*/				}
				else
				{
					$this->error="No existe usuario alterno";
					$this->mensajeDeError="El usuario no tiene relacion con un codigo alterno";
					return false;
				}
				if($this->ConexionBD()==false)
				{
					return false;
				}
				$result=$this->ExecuteSql($sql);
				if($result==false)
				{
					return false;
				}
				$dato=$result->GetRowAssoc(false);
				return $dato;
			}
			else
			{
				return false;
			}
			return true;
		}




		function SqlGetTraerSedes($departamento)
		{
			list($conexionlocal)=GetDBconn();
			$sql="select codigo_alterno from departamentos where departamento='".$departamento."';";
			$result1=$conexionlocal->Execute($sql);
			if ($conexionlocal->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $conexionlocal->ErrorMsg();
				return false;
			}
			$sql="exec spCmTraerOficinas null, '".date("Y/m/d")."', '+', 'N';";
			if($this->ConexionBD()==false)
			{echo "error";
				return false;
			}
			$result=$this->ExecuteSql($sql);
			if($result==false)
			{
				return false;
			}
			while(!$result->EOF)
			{
				$a=$result->GetRowAssoc(false);
				if($result1->fields[0]==trim($a['cnsctvo_cdgo_ofcna']))
				{
					$datos[trim($a['cnsctvo_cdgo_ofcna'])]=array('descripcion'=>$a['dscrpcn_ofcna'],'activo'=>1);
				}
				else
				{
					$datos[$a['cnsctvo_cdgo_ofcna']]=array('descripcion'=>$a['dscrpcn_ofcna'],'activo'=>0);
				}
				$result->MoveNext();
			}
			$result1->close();
			$result->close();
			return $datos;
		}


	}//fin clase

?>
