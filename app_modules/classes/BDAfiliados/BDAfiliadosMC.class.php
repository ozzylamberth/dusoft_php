<?php

	class BDAfiliadosMC
	{

		var $tipoidpaciente;
		var $paciente;
		var $campo_nombre_tabla='';

		//Datos de la base de datos
		var $campo_Primer_nombre='';
		var $campo_Segundo_nombre='';
		var $campo_Primer_apellido='';
		var $campo_Segundo_apellido='';
		var $campo_nombre_completo='';
		var $campo_tipo_afiliado='';
		var $campo_activo='';
		var $campo_urgencias='';
		var $campo_fecha_urgencias='';
		var $campo_tipodocumento='';
		var $campo_documento='';
		var $campo_empleador='';
		var $campo_edad='';
		var $campo_fecha_nacimiento='';
		var $campo_sexo='';
		var $campo_nivel='';
		var $campo_semanas_cotizadas='';
		var $fecha_radicacion='';
		var $fecha_vencimiento='';
		var $campo_estado_bd='';


		var $datosCompletos=array();
		var $dbtype;
		var $dbhost;
		var $dbuser;
		var $dbpass;
		var $dbname;
		var $conexion;
		var $DatosAfiliado=array();


		function ConexionBD()
		{
			$dbconn = ADONewConnection($this->dbtype);
			if (!($dbconn->Connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname)))
			{
				die(MsgOut("Error en la Conexin a la Base de Datos",$dbconn->ErrorMsg()));
			}
			$this->conexion=&$dbconn;
			return true;
		}

		function BuscarDatos()
		{
			$dbconn = ADONewConnection($this->dbtype);
			if (!($dbconn->Connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname)))
			{
				$this->error = "Error en la conexion de la base de datos".$dbconn->ErrorMsg();
				$this->mensajeDeError = "El tipo=$this->dbtype o host=$this->dbhost o user=$this->dbuser o passwd=$this->dbpass o base de datos=$this->dbname son incorrectos";
				return false;
			}
			$datos=$dbconn->MetaTables();
			$clave='';
			$clave=array_search($this->campo_nombre_tabla,$datos);
			if($clave===false)
			{
				$this->error = "Error la tabla no existe en esta base de datos: ".$this->campo_nombre_tabla;
				$this->mensajeDeError = "NO EXISTE";
				return false;
			}
			global $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$sql="select * from \"".$this->campo_nombre_tabla."\" where ".$this->campo_tipodocumento."='".$this->tipoidpaciente."' and ".$this->campo_documento."='".$this->paciente."';";
			$result=$dbconn->Execute($sql);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al consultar en la base de datos de afiliados".$this->campo_nombre_tabla;
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			if($result->EOF)
			{
				$this->mensajeDeError = "NO SE OBTUVO NINGUN RESULTADO";
				return false;
			}
			else
			{
				$this->datosCompletos=$result->FetchRow();
			}
			return true;
		}
		
		function BuscarDatosMSSQL()
		{
			$dbconn = ADONewConnection($this->dbtype);
			if (!($dbconn->Connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname)))
			{
				$this->error = "Error en la conexion de la base de datos".$dbconn->ErrorMsg();
				$this->mensajeDeError = "El tipo=$this->dbtype o host=$this->dbhost o user=$this->dbuser o passwd=$this->dbpass o base de datos=$this->dbname son incorrectos";
				return false;
			}
			list($conexionlocal)=GetDBconn();
			$sql="select codigo_alterno from tipos_id_pacientes where tipo_id_paciente='".$this->tipoidpaciente."';";
			$result1=$conexionlocal->Execute($sql);
			if ($conexionlocal->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $conexionlocal->ErrorMsg();
				return false;
			}
			$tipoidpaciente=$result1->fields[0];
			$result1->close();
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
			global $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$sql="exec \"".$this->campo_nombre_tabla."\"
$tipoidpaciente,'".$this->paciente."',$plan,'".date("Y/m/d")."','','','';";
			$result=$dbconn->Execute($sql);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al consultar en la base de datos de afiliados
".$this->campo_nombre_tabla;
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			if($result->EOF==1)
			{
				return true;
			}
			$sql1="exec SpPmConsultaDetEmpleadoresAfiliado
'".$result->fields['cnsctvo_cdgo_tpo_cntrto']."',
'".$result->fields['nmro_cntrto']."', '".date("Y/m/d")."',
'".$result->fields['cnsctvo_cdgo_pln']."',
'".$result->fields['cnsctvo_cdgo_tpo_idntfccn']."',
'".$result->fields['nmro_idntfccn']."', '".$result->fields['cdgo_eapb']."',
'".$result->fields['cnsctvo_cdgo_tpo_frmlro']."',
'".$result->fields['nmro_frmlro']."', '';";
			$result1=$dbconn->Execute($sql1);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al consultar en la base de datos de afiliados ".$this->campo_nombre_tabla;
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			if($result->EOF==1)
			{
				return true;
			}
			$sql1="exec SpPmConsultaDetConveniosCapitacionAfiliado
'".$result->fields['cnsctvo_cdgo_tpo_cntrto']."',
'".$result->fields['nmro_cntrto']."', '".$result->fields['cnsctvo_bnfcro']."',
'".$result->fields['nmro_unco_idntfccn_afldo']."', '".date("Y/m/d")."';";
			$result2=$dbconn->Execute($sql1);
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al consultar en la base de datos de afiliados ".$this->campo_nombre_tabla;
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				if(is_object($result)==false or is_object($result1)==false or is_object($result2)==false)
				{
					return true;
				}
				else
				{
					$dat=$result->FetchRow();
					foreach($dat as $k=>$v)
					{
						if('smns_ctzds_ss_ps'==$k or 'smns_ctzds_eps_ps'==$k)
						{
							$sema=$sema+$v;
						}
						if('smns_ctzds_ss_pc'==$k or 'smns_ctzds_eps_pc'==$k)
						{
							$sema1=$sema1+$v;
						}
						$this->datosCompletos[$k]=$v;
					}
					$this->datosCompletos['smns_ctzds_ss_ps']=$sema;
					$this->datosCompletos['smns_ctzds_ss_pc']=$sema1;
					$i=0;
					while(!$result1->EOF)
					{
						$dat=$result1->FetchRow();
						$sql="spPmInformacionConvenioCaja '".$this->datosCompletos['cnsctvo_cdgo_pln']."', '".$this->datosCompletos['cnsctvo_cdgo_rngo_slrl']."', '".$this->datosCompletos['cnsctvo_cdgo_tpo_afldo']."', '".$dat['cnsctvo_prdcto_scrsl']."', '".date("Y/m/d")."'";
						$result3=$dbconn->Execute($sql);
						if($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al consultar en la base de datos de afiliados ".$this->campo_nombre_tabla;
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						foreach($dat as $k=>$v)
						{
							$this->datosCompletos['emp'.$k.$i]=$v;
						}
						$datcom=$result3->FetchRow();
						$_SESSION['DATOSAFILIADOEMPLEADOR'][$i]=$dat;
						foreach($datcom as $k=>$v)
						{
							$this->datosCompletos['emp'.$k.$i]=$v;
							$_SESSION['DATOSAFILIADOEMPLEADOR'][$i][$k]=$v;
						}
						$i=$i+1;
					}
					$dat=$result2->FetchRow();
					foreach($dat as $k=>$v)
					{
						$this->datosCompletos['con'.$k]=$v;
					}
					//print_r($this->datosCompletos);
					$result->close();
					$result1->close();
					$result2->close();
				}
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return true;
		}

		
		
		function ExecuteSql($sql)
		{
			$dbconn=&$this->conexion;
			$result=$dbconn->Execute($sql);
			if ($this->conexion->ErrorNo() != 0)
			{
				echo $this->error="EN LA CONSULTA OCURRIO UN ERROR";
				echo $this->mensajeDeError="Error DB: ".$this->conexion->ErrorMsg();
				return false;
			}
			return $result;
		}


		function RetornarDatos()
		{
			if(!empty($this->datosCompletos))
			{
				if(!empty($this->campo_tipodocumento))
				{
					$datos[]=array('campo_tipodocumento'=>$this->TipoDocumento($this->datosCompletos[$this->campo_tipodocumento]));
					//$datos[]=array('campo_tipodocumento'=>$this->datosCompletos[$this->campo_tipodocumento]);
				}
				if(!empty($this->campo_documento))
				{
					$datos[]=array('campo_documento'=>$this->datosCompletos[$this->campo_documento]);
				}
				if(!empty($this->campo_Primer_nombre))
				{
					if(!empty($this->datosCompletos[$this->campo_Primer_nombre]))
					{
						$datos[]=array('campo_Primer_nombre'=>$this->datosCompletos[$this->campo_Primer_nombre]);
					}
				}
				if(!empty($this->campo_Segundo_nombre))
				{
					if(!empty($this->datosCompletos[$this->campo_Segundo_nombre]))
					{
						$datos[]=array('campo_Segundo_nombre'=>$this->datosCompletos[$this->campo_Segundo_nombre]);
					}
				}
				if(!empty($this->campo_Primer_apellido))
				{
					if(!empty($this->datosCompletos[$this->campo_Primer_apellido]))
					{
						$datos[]=array('campo_Primer_apellido'=>$this->datosCompletos[$this->campo_Primer_apellido]);
					}
				}
				if(!empty($this->campo_Segundo_apellido))
				{
					if(!empty($this->datosCompletos[$this->campo_Segundo_apellido]))
					{
						$datos[]=array('campo_Segundo_apellido'=>$this->datosCompletos[$this->campo_Segundo_apellido]);
					}
				}
				if(!empty($this->campo_nombre_completo))
				{
					$datos[]=array('campo_nombre_completo'=>$this->datosCompletos[$this->campo_nombre_completo]);
				}
				if(!empty($this->campo_sexo))
				{
					$datos[]=array('campo_sexo'=>$this->datosCompletos[$this->campo_sexo]);
				}
				if(!empty($this->campo_edad))
				{
					$datos[]=array('campo_edad'=>$this->datosCompletos[$this->campo_edad]);
				}
				if(!empty($this->campo_tipo_afiliacion))
				{
					$datos[]=array('campo_tipo_afiliacion'=>$this->datosCompletos[$this->campo_tipo_afiliacion]);
				}
				if(!empty($this->campo_estado_bd))
				{
					$datos[]=array('campo_estado_bd'=>$this->datosCompletos[$this->campo_estado_bd]);
				}
				else
				{
					$datos[]=array('campo_estado_bd'=>'Activo');
				}
				if(!empty($this->campo_activo))
				{
					$datos[]=array('campo_activo'=>$this->ProgramaActividad($this->datosCompletos[$this->campo_activo]));
				}
				else
				{
					$datos[]=array('campo_activo'=>1);
				}
				if(!empty($this->campo_vigencia_inicial))
				{
					$datos[]=array('campo_vigencia_inicial'=>$this->datosCompletos[$this->campo_vigencia_inicial]);
				}
				if(!empty($this->campo_fecha_retiro) and !empty($this->datosCompletos[$this->campo_fecha_retiro]))
				{
					$datos[]=array('campo_fecha_retiro'=>$this->datosCompletos[$this->campo_fecha_retiro]);
				}
				if(!empty($this->campo_urgencias))
				{
					$datos[]=array('campo_urgencias'=>$this->ProgramaUrgencias($this->datosCompletos[$this->campo_urgencias]));
				}
				else
				{
					if(!empty($this->campo_fecha_urgencias))
					{
						$datos[]=array('campo_urgencias'=>$this->ProgramaFechaUrgencias($this->datosCompletos[$this->campo_fecha_urgencias]));
					}
				}
				if(!empty($this->campo_proteccion_laboral) and !empty($this->datosCompletos[$this->campo_proteccion_laboral]))
				{
					$datos[]=array('campo_proteccion_laboral'=>$this->datosCompletos[$this->campo_proteccion_laboral]);
				}
				if(!empty($this->campo_semanas_cotizadas))
				{
					$datos[]=array('campo_semanas_cotizadas'=>$this->datosCompletos[$this->campo_semanas_cotizadas]);
				}
				if(!empty($this->campo_nivel))
				{
					$datos[]=array('campo_nivel'=>$this->ProgramaNivel($this->datosCompletos[$this->campo_nivel]));//falta
				}
				if(!empty($this->campo_tipo_afiliado))
				{
					$datos[]=array('campo_tipo_afiliado'=>$this->ProgramaTipoAfiliado($this->datosCompletos[$this->campo_tipo_afiliado]));
					$datos[]=array('campo_nombre_tipo_afiliado'=>$this->datosCompletos[$this->campo_tipo_afiliado]);
				}
				if(!empty($this->campo_direccion_afiliado))
				{
					$datos[]=array('campo_direccion_afiliado'=>$this->datosCompletos[$this->campo_direccion_afiliado]);
				}
				if(!empty($this->campo_telefono_afiliado))
				{
					$datos[]=array('campo_telefono_afiliado'=>$this->datosCompletos[$this->campo_telefono_afiliado]);
				}
				if(!empty($this->campo_ciudad_afiliado))
				{
					$datos[]=array('campo_ciudad_afiliado'=>$this->datosCompletos[$this->campo_ciudad_afiliado]);
				}
				if(!empty($this->campo_identificacion_cotizante))
				{
					$datos[]=array('campo_identificacion_cotizante'=>$this->datosCompletos[$this->campo_identificacion_cotizante]);
				}
				if(!empty($this->campo_tipo_contrato))
				{
					$datos[]=array('campo_tipo_contrato'=>$this->datosCompletos[$this->campo_tipo_contrato]);
				}
				//cambio dar
				if(!empty($this->campo_tipo_empleador) AND !empty($this->campo_id_empleador))
				{
					$datos[]=array('campo_tipo_empleador'=>$this->datosCompletos[$this->campo_tipo_empleador]);
					$datos[]=array('campo_id_empleador'=>$this->datosCompletos[$this->campo_id_empleador]);
				}
				//fin cambio
				if(!empty($this->campo_empleador))
				{
					$datos[]=array('campo_empleador'=>$this->datosCompletos[$this->campo_empleador]);
					if($this->campo_empleador_multiple==1)
					{
						$empleador=substr($this->campo_empleador,0,strlen($this->campo_empleador)-1);
						$i=1;
						while(!empty($this->datosCompletos[$empleador.$i]))
						{
							$datos[]=array('campo_empleador'.$i=>$this->datosCompletos[$empleador.$i]);
							$i++;
						}
					}
				}
				if(!empty($this->campo_direccion_empresa))
				{
					$datos[]=array('campo_direccion_empresa'=>$this->datosCompletos[$this->campo_direccion_empresa]);
				}
				if(!empty($this->campo_telefono_empresa))
				{
					$datos[]=array('campo_telefono_empresa'=>$this->datosCompletos[$this->campo_telefono_empresa]);
				}
				if(!empty($this->campo_codigo_presta_medico))
				{
					$datos[]=array('campo_codigo_presta_medico'=>$this->datosCompletos[$this->campo_codigo_presta_medico]);
				}

				if(!empty($this->campo_fecha_nacimiento))
				{
					$datos[]=array('campo_fecha_nacimiento'=>$this->datosCompletos[$this->campo_fecha_nacimiento]);
				}
				if(!empty($this->fecha_radicacion))
				{
					if(!empty($this->fecha_radicacion))
					{
						$datos[]=array('fecha_radicacion'=>$this->fecha_radicacion);
					}
				}
				if(!empty($this->fecha_vencimiento))
				{
					if(!empty($this->fecha_vencimiento))
					{
						$datos[]=array('fecha_vencimiento'=>$this->fecha_vencimiento);
					}
				}
				if(!empty($this->campo_ips_afiliado))
				{
					$datos[]=array('campo_ips_afiliado'=>$this->datosCompletos[$this->campo_ips_afiliado]);
				}
				if(!empty($this->campo_descripcion_plan))
				{
					$datos[]=array('campo_descripcion_plan'=>$this->datosCompletos[$this->campo_descripcion_plan]);
				}
				foreach($datos as $k=>$v)
				{
					foreach($v as $t=>$r)
					{
						$this->salida[$t]=$r;
					}
				}
			}
			return true;
		}

	}//fin clases

?>
