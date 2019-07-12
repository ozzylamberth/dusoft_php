<?php
	/*********************************************************************************************
	* $Id: Cumplimiento.class.php,v 1.2 2010/01/20 21:01:34 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.2 $
	*
	* @autor Hugo F. Manrique
	***********************************************************************************************/
	class Cumplimiento
	{
		var $ASIGNO;
		var $CORTO;
		var $cnt_citas_asignar;	
		var $turno_cita_asignada;
		
		function Cumplimiento(){}
		/**********************************************************************************
		*
		* @return boolean
		***********************************************************************************/
		function ActualizarTomaOS($cumplimiento,$departamento,$n_cumplimiento)
		{				

		}
		/**********************************************************************************
		*
		* @return boolean
		***********************************************************************************/
		function ObtenerDatosCumplimiento($datos,$departamento)
		{
			$sql  = "SELECT OC.os_maestro_cargos_id,";
			$sql .= "				DC.sw_liquidar_honario,";
			$sql .= "				DC.sw_cumplido_automatico,";
			$sql .= "				DC.sw_tomado_automatico, ";
			$sql .= "				DC.sw_cumplimiento_parcial, ";
			$sql .= "				OM.numero_orden_id, ";
			$sql .= "				OM.orden_servicio_id, ";
			$sql .= "				OC.cantidad_pendiente, ";
			$sql .= "				OC.cantidad ";
			if(ModuloGetVar('app','AgendaMedica','MenuAsignacionCitas')=='1'){
				$sql .= ", OC.cnt_citas_auto  ";
			}
			$sql .= "FROM 	os_ordenes_servicios OS,";
			//$sql .= "				,";
			$sql .= "				os_maestro_cargos OC,";
			$sql .= "				departamentos_cargos DC LEFT JOIN os_maestro OM ON (DC.cargo = OM.cargo_cups)";
      //$sql .= "AND 		 ";
			$sql .= "WHERE 	OS.paciente_id = '".$datos['paciente_id']."' ";
			$sql .= "AND 		OS.tipo_id_paciente = '".$datos['tipo_id']."' ";
			$sql .= "AND		OS.orden_servicio_id = ".$datos['orden_id']." ";
			$sql .= "AND 		OM.orden_servicio_id = OS.orden_servicio_id ";
			$sql .= "AND 		OC.numero_orden_id = OM.numero_orden_id ";
			$sql .= "AND		DC.departamento = '".$departamento."' ";
			$sql .= "AND		OS.sw_estado IN ('1','2') ";
			$sql .= "AND		OC.tarifario_id <> 'SYS' ";
			$sql .= "AND		OC.cargo <> 'IMD' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
		/**********************************************************************************
		*
		* @return boolean
		***********************************************************************************/
		function ObtenerCantidadCumplimientos($datos,$departamento)
		{
			$sql  = "SELECT COALESCE(numero_cumplimiento,0) AS numero_cumplimiento ";
			$sql .= "FROM		os_cumplimientos ";
			$sql .= "WHERE	fecha_cumplimiento  = NOW()::date ";
			$sql .= "AND		departamento = '".$departamento."' ";
			$sql .= "AND		tipo_id_paciente = '".$datos['tipo_id']."' ";
			$sql .= "AND		paciente_id = '".$datos['paciente_id']."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos['numero_cumplimiento'];
		} 
				
		/**********************************************************************************
		*
		* @return boolean
		***********************************************************************************/
		function AsignarCitas($ordenes,$datos)
		{
			//ASIGNAR CITAS AUTOMATICAS PARA LAS DEMAS SESSIONES
			$sql = "SELECT a.*,b.*, c.* "; 
			$sql .= "FROM agenda_citas_asignadas a, ";
			$sql .= " agenda_citas b, agenda_turnos c ";
			$sql .= "WHERE 	tipo_id_paciente = '".$datos['tipo_id']."' ";
			$sql .= "AND paciente_id = '".$datos['paciente_id']."' ";
			$sql .= "AND a.agenda_cita_id = b.agenda_cita_id ";
			$sql .= "AND b.agenda_turno_id = c.agenda_turno_id ";
			$sql .= "AND c.fecha_turno = '".date('Y-m-d')."'; ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
	
			if(!$rst->EOF)
			{
				$datosCitaAsignada = $rst->GetRowAssoc($ToUpper = false);
				//$rst->MoveNext();
			}

			$sql = "SELECT b.*, c.* "; 
			$sql .= "FROM agenda_citas b, agenda_turnos c ";
			$sql .= "WHERE b.agenda_turno_id = c.agenda_turno_id ";
			//$sql .= "AND c.fecha_turno = '".date('Y-m-d')."'; ";
			$sql .= "AND c.tipo_id_profesional = '".$datosCitaAsignada[tipo_id_profesional]."' ";
			$sql .= "AND c.profesional_id = '".$datosCitaAsignada[profesional_id]."' ";
			$sql .= "AND c.fecha_turno > '".date('Y-m-d')."' ";
			$sql .= "AND b.sw_estado IN ('0','3')";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			while(!$rst->EOF)
			{
				$agenda[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
				
			foreach($ordenes AS $i => $v)
			{
				if($v[cantidad] > 0)
				{
					$fechacita = date('Y-m-d');
 
					$j = 1;
						for($k = 0; $k<sizeof($agenda); $k++)
						{
							if($fechacita <> $agenda[$k][fecha_turno])
							{
								$fechacita = $agenda[$k][fecha_turno];
							// ASIGNACION CITAS DESPUES DEL PRIMER CUMPLIMIENTO
								$sql="SELECT NEXTVAL('agenda_citas_asignadas_agenda_cita_asignada_id_seq');";
								$result = $this->ConexionBaseDatos($sql);
								if (!$result){
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB i: " . $dbconn->ErrorMsg();
									return false;
								}
								if(!$result->EOF){
									$agenda_cita_asignada_id=$result->fields[0];
								}
								
								//$agendaCitaPadre = $v2[agenda_cita_id];
								$observacion = "ASIGNACION DE CITA AUTOMATICA";
								
								$sql="INSERT INTO agenda_citas_asignadas
									(
										agenda_cita_asignada_id,
										agenda_cita_id,
										paciente_id,
										tipo_id_paciente,
										tipo_cita,
										plan_id,
										cargo_cita,
										observacion,
										usuario_id,
										agenda_cita_id_padre,
										fecha_registro,
										sw_prioritaria
									)
									values
									(
										".$agenda_cita_asignada_id.",
										".$agenda[$k]['agenda_cita_id'].",
										'".$datos['paciente_id']."',
										'".$datos['tipo_id']."',
										'".$datosCitaAsignada['tipo_cita']."',
										'".$datosCitaAsignada['plan_id']."',
										'".$datosCitaAsignada['cargo_cita']."',
										'".$observacion."',
										".UserGetUID().",
										".$datosCitaAsignada['agenda_cita_id_padre'].",
										'".date("Y-m-d H:i:s")."',
										'0'
									);";

								$rst = $this->ConexionBaseDatos($sql);
								if(!$rst){
									$this->error = "Error al insertar informaciï¿½ en la tabla agenda_citas_asignadas.";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
									$dbconn->RollbackTrans();
									return false;
								}

								$sql="SELECT NEXTVAL('hc_os_solicitudes_hc_os_solicitud_id_seq');";
								$result1 = $this->ConexionBaseDatos($sql);
								if (!$result1)
								{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
									unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
									//$dbconn->RollbackTrans();
									$sql="ROLLBACK";
									$this->ConexionBaseDatos($sql);
									return false;
								}
		
								$sql="INSERT INTO hc_os_solicitudes 
											(
												hc_os_solicitud_id, 
												cargo,
												plan_id, 
												os_tipo_solicitud_id, 
												sw_estado, 
												paciente_id, 
												tipo_id_paciente
											) 
											VALUES
											(
												
										
												".$result1->fields[0].",
												'".$datosCitaAsignada['cargo_cita']."',
												'".$datosCitaAsignada['plan_id']."',
												'CIT',
												'0',
												'".$datos['paciente_id']."',
												'".$datos['tipo_id']."'
											);";
								$r = $this->ConexionBaseDatos($sql);
								/////////////A
								if (!$r)
								{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
									unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
									//$dbconn->RollbackTrans();
									$sql="ROLLBACK";
									$this->ConexionBaseDatos($sql);
									return false;
								}
								
								//SELECCIONAR DATOS DE LA PRIMERA SOLICITUD DE LA CITA
/*								$sql= "	SELECT hc_os_solicitud_id 
												FROM hc_os_solicitudes
												WHERE tipo_consulta_id = ".$v2['tipo_consulta_id']."; ";
								$result = $this->ConexionBaseDatos($sql);
								if(!$result->EOF){
									$hc_os_solicitud_id=$result->fields[0];
								}
								$sql= "	SELECT * 
												FROM hc_os_autorizaciones
												WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."; ";
								$result = $this->ConexionBaseDatos($sql);
								if(!$result->EOF){
									$autorizacion_int=$result->fields[1];
									$autorizacion_ext=$result->fields[2];
								}*/
								 $sql= "SELECT * 
									FROM os_ordenes_servicios
									WHERE orden_servicio_id = (
													SELECT MAX(orden_servicio_id)
													FROM os_ordenes_servicios
													WHERE paciente_id = '".$datos['paciente_id']."'
													AND tipo_id_paciente = '".$datos['tipo_id']."'
													); ";
								$result = $this->ConexionBaseDatos($sql);
								if(!$result->EOF){
									$os_ordenes_servicios=$result->GetRowAssoc($ToUpper = false);;
								}
						
								//FIN SELECCIONAR DATOS DE LA PRIMERA SOLICITUS DE LA CITA
								
								$sql="INSERT INTO hc_os_solicitudes_citas 
											(
												hc_os_solicitud_id,
												tipo_consulta_id
											)
											VALUES
											(
												".$result1->fields[0].",
												".$agenda[$k]['tipo_consulta_id']."
											);";
								$r = $this->ConexionBaseDatos($sql);
								if (!$r)
								{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
									unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
									//$dbconn->RollbackTrans();
									$sql="ROLLBACK";
									$this->ConexionBaseDatos($sql);
									return false;
								}
		
								$sql="INSERT INTO hc_os_autorizaciones
											(
												hc_os_solicitud_id,
												autorizacion_int,
												autorizacion_ext
											) values 
											(
												".$result1->fields[0].",
												".$os_ordenes_servicios[autorizacion_int].",
												".$os_ordenes_servicios[autorizacion_ext]."
											);";
								$r = $this->ConexionBaseDatos($sql);
								if (!$r)
								{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
									unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
									//$dbconn->RollbackTrans();
									$sql="ROLLBACK";
									$this->ConexionBaseDatos($sql);
									return false;
								}
								$sql="SELECT NEXTVAL('os_ordenes_servicios_orden_servicio_id_seq');";
								$result2 = $this->ConexionBaseDatos($sql);
								if (!$result2)
								{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
									unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
									//$dbconn->RollbackTrans();
									$sql="ROLLBACK";
									$this->ConexionBaseDatos($sql);
									return false;
								}
								
								//EXTRAER DATOS DE LA ULTIMA ORDEN DE SERVICIO DEL PACIENTE
								$sql= "	SELECT * 
												FROM  os_ordenes_servicios
												WHERE orden_servicio_id = 
															(SELECT MAX(orden_servicio_id) 
															FROM  os_ordenes_servicios
															WHERE tipo_id_paciente = '".$datos['tipo_id']."'
															AND paciente_id = '".$datos['paciente_id']."'); ";
								$result = $this->ConexionBaseDatos($sql);
								if(!$result->EOF){
									$dat=$result->GetRowAssoc($ToUpper = false);;
								}
								//FIN EXTRAER DATOS DE LA ULTIMA ORDEN DE SERVICIO DEL PACIENTE
								
									$evento = "NULL";
		
								$sql = "INSERT INTO os_ordenes_servicios (
																orden_servicio_id, 
																autorizacion_int, 
																autorizacion_ext, 
																plan_id, 
																tipo_afiliado_id, 
																rango, 
																semanas_cotizadas, 
																servicio, 
																tipo_id_paciente, 
																paciente_id, 
																usuario_id, 
																fecha_registro,
																evento_soat) 
												VALUES (".$result2->fields[0].", 
																".$os_ordenes_servicios[autorizacion_int].", 
																".$os_ordenes_servicios[autorizacion_ext].", 
																'".$datosCitaAsignada['plan_id']."',

																'".$dat['tipo_afiliado_id']."', 
																'".$dat['rango']."', 
																'".$dat['semanas_cotizadas']."', 
																'".$dat['servicio']."', 
																
																'".$datos['tipo_id']."',
																'".$datos['paciente_id']."',
																".UserGetUID().", 
																'".date("Y-m-d H:i:s")."',
																".$evento.");";
								
								$r = $this->ConexionBaseDatos($sql);
								if (!$r)
								{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
									unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
									//$dbconn->RollbackTrans();
									$sql="ROLLBACK";
									$this->ConexionBaseDatos($sql);
									return false;
								}
									
								$sql="SELECT NEXTVAL('os_maestro_numero_orden_id_seq');";
								$result4 = $this->ConexionBaseDatos($sql);
								if (!$result4)
								{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
									unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
									$sql="ROLLBACK";
									$this->ConexionBaseDatos($sql);
									return false;
								}
								if($v2[fecha_turno] < date("Y-m-d H:m"))
								{
									$v2[fecha_turno] = date("Y-m-d H:m",mktime(24,24,24,date("m"),date("d"),date("Y")));
								}
								$_SESSION['AsignacionCitas']['numero_orden_id']=$result4->fields[0];
								//*****************
								//EXTAER CARGO CUPS
								$sql= "	SELECT * 
												FROM os_maestro
												WHERE orden_servicio_id = ".$dat[orden_servicio_id]."; ";
								$result = $this->ConexionBaseDatos($sql);
								if(!$result->EOF){
									$dat2=$result->GetRowAssoc($ToUpper = false);;
								}
								//FIN EXTRAER CARGO CUPS
								
								if(!$dat2[cargo_cups])
								{
									$sql= "	SELECT * 
													FROM  agenda_citas_asignadas
													WHERE agenda_cita_asignada_id = 
															(SELECT MAX(agenda_cita_asignada_id) 
															FROM  agenda_citas_asignadas
															WHERE tipo_id_paciente = '".$datos['tipo_id']."'
															AND paciente_id = '".$datos['paciente_id']."'); ";
									$result = $this->ConexionBaseDatos($sql);
									if(!$result->EOF){
										$d=$result->GetRowAssoc($ToUpper = false);
										$dat2[cargo_cups] = $d[cargo_cita];
									}
								}
								//*****************
								if(empty($_SESSION['CumplirCita']['cita']))
								{
		
								$sql="INSERT INTO os_maestro
												(
													numero_orden_id, 
													orden_servicio_id, 
													fecha_vencimiento, 
													hc_os_solicitud_id, 
													cargo_cups
												)
												VALUES
												(
													".$result4->fields[0].",
													".$result2->fields[0].",
													'".$agenda[$k][fecha_turno]."',
													".$result1->fields[0].",
													'".$dat2[cargo_cups]."'
												);";
								}
								else
								{
		
									$sql="INSERT INTO os_maestro
												(
													numero_orden_id,
													orden_servicio_id,
													fecha_vencimiento,
													hc_os_solicitud_id,
													cargo_cups,
													sw_estado
												)
												VALUES
												(
													".$result4->fields[0].",
													".$result2->fields[0].",
													'".$agenda[$k][fecha_turno]."',
													".$result1->fields[0].",
													'".$dat2[cargo_cups]."',
													'5');";
								}
								$r = $this->ConexionBaseDatos($sql);
								if (!$r)
								{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB k: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
									unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
									$sql="ROLLBACK";
									$this->ConexionBaseDatos($sql);
									//$dbconn->RollbackTrans();
									return false;
								}
		
								//EXTRAER DEPARTAMENTO DEL CARGO
								if($dat2[numero_orden_id])
								{
									$sql= "	SELECT * 
													FROM os_internas
													WHERE numero_orden_id = ".$dat2[numero_orden_id]."; ";
									$result = $this->ConexionBaseDatos($sql);
									if(!$result->EOF){
										$dat3=$result->GetRowAssoc($ToUpper = false);;
									}
									if(!$dat3[departamento])
									{
										$dat3[departamento] = $_SESSION[CentralAtecion][departamento];
									}
								}
								else
								{
									$dat3[departamento] = $_SESSION[CentralAtecion][departamento];
								}
								//FIN EXTRAER DEPARTAMENTO DEL CARGO
								
								$sql="INSERT INTO os_internas
											(
												numero_orden_id,
												cargo,
												departamento
											)
											VALUES
											(
												".$result4->fields[0].",
												'".$dat2[cargo_cups]."',
												'".$dat3[departamento]."'
											);";
								$r = $this->ConexionBaseDatos($sql);
								///////C
								
								if (!$r)
								{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
									unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
									$sql="ROLLBACK";
									$this->ConexionBaseDatos($sql);
									//$dbconn->RollbackTrans();
									return false;
								}
		
								$sql="INSERT INTO os_cruce_citas
											(
												numero_orden_id,
												agenda_cita_asignada_id
											)
											VALUES
											(
												".$result4->fields[0].",
												$agenda_cita_asignada_id
											);";
								$r = $this->ConexionBaseDatos($sql);
								if (!$r)
								{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
									unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
									$sql="ROLLBACK";
									$this->ConexionBaseDatos($sql);
									//$dbconn->RollbackTrans();
									return false;
								}
								$sql="SELECT NEXTVAL('os_maestro_cargos_os_maestro_cargos_id_seq');";
								$result5 = $this->ConexionBaseDatos($sql);
								if (!$result5)
								{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
									unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
									$sql="ROLLBACK";
									$this->ConexionBaseDatos($sql);
									return false;
								}
								$_SESSION['AsignacionCitas']['os_maestro_cargos_id']=$result5->fields[0];
		
								//EXTRAER TARIFARIO Y CARGO
								$sql= "SELECT * 
												FROM os_maestro_cargos
												WHERE numero_orden_id = 
															(SELECT MAX(os_maestro_cargos_id)
															FROM os_maestro_cargos
															WHERE numero_orden_id = ".$dat2[numero_orden_id].")"; 
											
								$result = $this->ConexionBaseDatos($sql);
								if($result->EOF){
										$sql= "SELECT a.tarifario_id, a.cargo
																FROM tarifarios_equivalencias a, plan_tarifario b
																WHERE a.tarifario_id = b.tarifario_id
																AND b.plan_id = ".$datosCitaAsignada['plan_id']."
																AND a.cargo_base= '".$dat2[cargo_cups]."'
																LIMIT 1;"; 
													
										$result = $this->ConexionBaseDatos($sql);
										if(!$result->EOF){
											$dat4=$result->GetRowAssoc($ToUpper = false);;
										}
								}
								else
								{
									$dat4=$result->GetRowAssoc($ToUpper = false);;
								}
								//FIN EXTRAER TARIFARIO CARGO
								
								$sql="INSERT into os_maestro_cargos 
											(
												os_maestro_cargos_id,
												numero_orden_id,
												tarifario_id,
												cargo
											)
											VALUES
											(
												".$result5->fields[0].",
												".$result4->fields[0].",
												'".$dat4[tarifario_id]."',
												'".$dat4[cargo]."'
											);";
								$r = $this->ConexionBaseDatos($sql);
								if (!$r)
								{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
									unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
									$sql="ROLLBACK";
									$this->ConexionBaseDatos($sql);
									//$dbconn->RollbackTrans();
									return false;
								}
								
								//ACTUALIZAR AGENDA CITAS ESTADO 1= ASIGNADA
								$sql="UPDATE agenda_citas
											SET sw_estado = '1'
											WHERE agenda_cita_id = ".$agenda[$k][agenda_cita_id]."
											AND sw_estado = '0';";
								$r = $this->ConexionBaseDatos($sql);
								if (!$r)
								{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
									unset($_SESSION['AsignacionCitas']['idcitas'][$b]);
									$sql="ROLLBACK";
									$this->ConexionBaseDatos($sql);
									//$dbconn->RollbackTrans();
									return false;
								}
							//FIN ASIGNACION CITAS DESPUES DEL PRIMER CUMPLIMIENTO
								if($j == $v[cantidad]-1)
								{
									$k = sizeof($agenda);
								}
								$j++;
							}//FIN IF
							
						}//FIN FOR
					//}
				}
			}
			
			//FIN ASIGNAR CITAS AUTOMATICAS PARA LAS DEMAS SESSIONES
			return true;
		}
		
		/**********************************************************************************
		*
		* @return boolean
		***********************************************************************************/
		function ActualizarCumplimientoOrden($datos,$departamento,$cumplimiento,$n_cumplimiento,$usuario,$cant,$cargo)
		{
			
      $sql = "";
     
			if($n_cumplimiento == 0)
			{
       print_r($n_cumplimiento);
				$sql = "SELECT	secuencia_os_cumplimiento_restringido('".$departamento."') ";
			
				if(!$rst = $this->ConexionBaseDatos($sql)) 
					return false;
				
				$n_cumplimiento = $rst->fields[0];
	
				$sql  = "INSERT INTO os_cumplimientos ";
				$sql .= "			(	numero_cumplimiento,";
				$sql .= "				fecha_cumplimiento,";
				$sql .= "				departamento,";
				$sql .= "				tipo_id_paciente,";
				$sql .= "				paciente_id) ";
				$sql .= "VALUES( ";
				$sql .= "			".$n_cumplimiento.", ";
				$sql .= "			NOW(), ";
				$sql .= "			'".$departamento."', ";
				$sql .= "			'".$datos['tipo_id']."', ";
				$sql .= "			'".$datos['paciente_id']."' ";
				$sql .= "		); ";
        
        
        if(ModuloGetVar('app','AgendaMedica','MenuAsignacionCitas')=='1'){
          $cant=0;
          $sql1="SET		cantidad_pendiente = ".$cant." ";
        }
        else{
          $sql1= "SET		cantidad_pendiente = cantidad_pendiente - ".$cant." ";	
        }
        
        $estado = "'0'";
        if($cumplimiento['sw_tomado_automatico'] == '1')
          $estado = "'1'";
			
        if(!$usuario) $usuario = "NULL";
        
        $sql .= "INSERT INTO os_cumplimientos_detalle";
        $sql .= "		(	numero_orden_id,";
        $sql .= "			numero_cumplimiento,";
        $sql .= "			fecha_cumplimiento,";
        $sql .= "			departamento,";
        $sql .= "			sw_estado, ";
        $sql .= "			usuario_id, ";
        $sql .= "			cantidad_cumplimiento) ";
        $sql .= "VALUES(";
        $sql .= "			".$cumplimiento['numero_orden_id'].",";
        $sql .= "			".$n_cumplimiento.",";
        $sql .= "			NOW(),";
        $sql .= "			'".$departamento."', ";
        $sql .= "			".$estado.", ";
        $sql .= "			".$usuario.", ";
        $sql .= "			".$cant."); ";
        
        if(!$rst = $this->ConexionBaseDatos($sql)) 
          return false;
			}
			//ACTUALIZAR NUMERO DE CUENTA EN os_maestro, cuando se crea la cuenta ambulatoria
			//y el paciente se pueda atender por HC
			$set = " ";
			if(SessionIsSetVar('CuentaAmbulatoria'))
			{
				$set = ", numerodecuenta = '".SessionGetVar('CuentaAmbulatoria')."' ";
        
			}
			else
			{
        $sql2 = " SELECT	MAX(numero_cumplimiento) 
                  FROM    os_cumplimientos 
                  WHERE   departamento = '".$departamento."' 
					        AND     tipo_id_paciente = '".$datos['tipo_id']."' 
                  AND     paciente_id = '".$datos['paciente_id']."' 
                  AND     fecha_cumplimiento = NOW()::date";
          
				if(!$rst = $this->ConexionBaseDatos($sql2)) 
					return false;
				
				$n_cumplimiento_primer_cita = $rst->fields[0];
				
				
				$sql2 = " SELECT	fecha_cumplimiento 
                  FROM    os_cumplimientos 
                  WHERE   departamento = '".$departamento."' 
					        AND     tipo_id_paciente = '".$datos['tipo_id']."' 
                  AND     paciente_id = '".$datos['paciente_id']."' 
					        AND numero_cumplimiento = ".$n_cumplimiento_primer_cita." ";
       
				if(!$rst = $this->ConexionBaseDatos($sql2)) 
					return false;
				$fecha_cumplimiento = $rst->fields[0];
				
				
				$sql2 = " SELECT  d.numerodecuenta 
                  FROM    os_maestro as a, 
                          os_cumplimientos_detalle as b, 
                          os_maestro_cargos c, 
                          cuentas_detalle d 
                  WHERE   b.numero_cumplimiento = ".$n_cumplimiento_primer_cita." 
					        AND     b.departamento = '".$departamento."'
					        AND     b.fecha_cumplimiento = '".$fecha_cumplimiento."'
					        AND     b.fecha_cumplimiento = a.fecha_vencimiento 
					        AND     b.numero_orden_id = a.numero_orden_id
                  AND     a.numero_orden_id = c.numero_orden_id
                  AND     c.transaccion= d.transaccion
					";
         
				if(!$rst = $this->ConexionBaseDatos($sql2)) 
					return false;
				
				$numerodecuenta = $rst->fields[0];
				$set = ", numerodecuenta = '".$numerodecuenta."' ";
				
			}
			//FIN
			$sql3 .= "UPDATE	os_maestro ";
			$sql3 .= "SET		sw_estado = '3' $set";
			$sql3 .= "WHERE	numero_orden_id = ".$cumplimiento['numero_orden_id']." ";
			$sql3 .= "AND		orden_servicio_id = ".$cumplimiento['orden_servicio_id']." ";
			$sql3 .= "; ";
			
			/*if(ModuloGetVar('app','AgendaMedica','MenuAsignacionCitas')=='1'){
				$cant=0;
				$sql1="SET		cantidad_pendiente = ".$cant." ";
			}
			else{
				$sql1= "SET		cantidad_pendiente = cantidad_pendiente - ".$cant." ";	
			}*/
			$sql3 .= "UPDATE	os_maestro_cargos ";
			$sql3 .=$sql1;
			$sql3 .= "WHERE	numero_orden_id = ".$cumplimiento['numero_orden_id']." ";
			$sql3 .= "AND		os_maestro_cargos_id = ".$cargo." ";
			$sql3 .= "; ";
			
			/*$estado = "'0'";
			if($cumplimiento['sw_tomado_automatico'] == '1')
				$estado = "'1'";
			
			if(!$usuario) $usuario = "NULL";
			
			$sql .= "INSERT INTO os_cumplimientos_detalle";
			$sql .= "		(	numero_orden_id,";
			$sql .= "			numero_cumplimiento,";
			$sql .= "			fecha_cumplimiento,";
			$sql .= "			departamento,";
			$sql .= "			sw_estado, ";
			$sql .= "			usuario_id, ";
			$sql .= "			cantidad_cumplimiento) ";
			$sql .= "VALUES(";
			$sql .= "			".$cumplimiento['numero_orden_id'].",";
			$sql .= "			".$n_cumplimiento.",";
			$sql .= "			NOW(),";
			$sql .= "			'".$departamento."', ";
			$sql .= "			".$estado.", ";
			$sql .= "			".$usuario.", ";
			$sql .= "			".$cant."); ";*/

			if(!$rst = $this->ConexionBaseDatos($sql3)) 
				return false;
			
			return $n_cumplimiento;
    }
    
   
		
		function GetTipoPlan($responsable)
		{
			list($dbconn) = GetDBconn();
			$SQL="SELECT a.* FROM 	tipos_planes as a, planes as b WHERE
				b.plan_id = ".$responsable." AND b.sw_tipo_plan = a.sw_tipo_plan;";
			$rst = $dbconn->Execute($SQL);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al traer informacion de la tabla tipos_planes metodo GetTipoPlan.";
					$this->mensajeDeError = "Error DB: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
					return false;
				}
				else{
					$datos=$rst->RecordCount();
      					if($datos){
        					$var=$rst->fields[0];
						$rst->Close();
						return $var;			
      					}
				
				}
			return true;
		}
		/////////////////juanp
		
		function BusquedaServicio($depto)
		{
			list($dbconn) = GetDBconn();
			$sql="select servicio from departamentos where departamento='".$depto."';";
			$result = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB  : " . $dbconn->ErrorMsg();
				return false;
			}
			return $result->fields[0];
		}
		
		function PacienteFechaTurno($TipoId,$PacienteId,$fechacita,$tipo_consulta)
		{
			
			list($dbconn) = GetDBconn();
			$SQL="select count(a.*) from agenda_citas_asignadas as a, agenda_citas as b, agenda_turnos as c 
			where a.paciente_id='".$PacienteId."' and a.tipo_id_paciente='".$TipoId."' and a.agenda_cita_id=b.agenda_cita_id AND c.tipo_consulta_id = ".$tipo_consulta."
			and b.agenda_turno_id=c.agenda_turno_id and c.fecha_turno::date = '".$fechacita."'::date ;";
			
			
			$rst = $dbconn->Execute($SQL);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al traer informacion de la tabla agenda_citas_asiganadas metodo CantidadCitasAsignadasPlan.";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
					return false;
				}
				else{
					
					$var=$rst->fields[0];
					$rst->Close();
					return $var;			
					
					
				}
			return true;
		
		}
		
		function Restar_Fechas($fecha1,$fecha2)
		{
			//defino fecha 1
			$fechaA = explode("-",$fecha1);
			
			$ano1 = $fechaA[0];
			$mes1 = $fechaA[1];
			$dia1 = $fechaA[2];
			
			//defino fecha 2
			$fechaB = explode("-",$fecha2);
			$ano2 = $fechaB[0];
			$mes2 = $fechaB[1];
			$dia2 = $fechaB[2];
			
			//calculo timestam de las dos fechas
			$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
			$timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2);
			
			//resto a una fecha la otra
			$segundos_diferencia = $timestamp1 - $timestamp2;
			
			//convierto segundos en dï¿½s
			$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
			
			//obtengo el valor absoulto de los dï¿½s (quito el posible signo negativo)
			$dias_diferencia = abs($dias_diferencia);
			
			//quito los decimales a los dï¿½s de diferencia
			$dias_diferencia = floor($dias_diferencia);
			
			return $dias_diferencia;
			
		}
		
		
		
		function AsignarCitasAutomaticas($ordenes,$datos)
		{
			
			
			$tipo_consulta_id = $this->GetTipoConsulta($datos['orden_id']);
			
			list($dbconn) = GetDBconn();
			////////////SELECCION DE TODOS LOS DATOS DE LA PRIMERA CITA ASIGNADA
			
			$sql = "SELECT agenda_cita_asignada_id FROM os_maestro as a,os_cruce_citas as b WHERE a.orden_servicio_id = ".$datos['orden_id']."
			AND a.numero_orden_id = b.numero_orden_id";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			if(!$rst->EOF)
			{
				$cita_asig = $rst->fields[0];
				
			}
			
						
			$sql = "SELECT a.*,b.*, c.* "; 
			$sql .= "FROM agenda_citas_asignadas a, ";
			$sql .= " agenda_citas b, agenda_turnos c ";
			$sql .= "WHERE 	a.tipo_id_paciente = '".$datos['tipo_id']."' ";
			$sql .= "AND a.paciente_id = '".$datos['paciente_id']."' ";
			$sql .= "AND a.agenda_cita_asignada_id = ".$cita_asig." ";
			$sql .= "AND a.agenda_cita_id = b.agenda_cita_id ";
			$sql .= "AND b.agenda_turno_id = c.agenda_turno_id ";
			//$sql .= "AND c.tipo_consulta_id = ".$tipo_consulta_id." ";
			$sql .= "AND c.fecha_turno = '".date('Y-m-d')."'; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			if(!$rst->EOF)
			{
				$datosCitaAsignada = $rst->GetRowAssoc($ToUpper = false);
				
			}
      //print_r($datosCitaAsignada);
			//////////////////////////
			$sql=" SELECT c.transaccion 
             FROM   agenda_citas_asignadas as a, 
                    os_cruce_citas as b, 
                    os_maestro_cargos as c
				     WHERE  a.agenda_cita_asignada_id = ".$datosCitaAsignada['agenda_cita_asignada_id']."
				     AND    a.agenda_cita_asignada_id=b.agenda_cita_asignada_id 
             AND    b.numero_orden_id=c.numero_orden_id;";
			$result22 = $dbconn->Execute($sql);
			if($dbconn->ErrorNo() != 0)
      {
				echo 'ERROR: J->'.$sql;
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB E1: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
				return false;
			}
			$transaccion = $result22->fields[0];
			
			if(trim($datosCitaAsignada[observacion])=='ASIGNACION DE CITA AUTOMATICA'){

				
				$fechacita=date('Y-m-d');
							
				$TipoPlanPaciente = $this->GetTipoPlan($datos['plan_id']);
				foreach($ordenes AS $i => $v)
				{
					if($v[cnt_citas_auto] > 0)
					{
						$this->cnt_citas_asignar=$v[cnt_citas_auto];
					}
					$os_maestro_cargos_id = $v[os_maestro_cargos_id];
				}
				
					
					
					///////////////SELECCION DE TODAS LOS TURNOS DEL PROFESIONAL DESDE LA PRIMERA ATENCION
					$sql = "SELECT a.*, b.fecha_turno, b.tipo_id_profesional, b.profesional_id, b.tipo_consulta_id, b.consultorio_id, b.duracion "; 
					$sql .= "FROM agenda_citas a, agenda_turnos b ";
					$sql .= "WHERE a.agenda_turno_id = b.agenda_turno_id ";
					$sql .= "AND b.tipo_id_profesional = '".$datosCitaAsignada[tipo_id_profesional]."' ";
					$sql .= "AND b.profesional_id = '".$datosCitaAsignada[profesional_id]."' ";
					$sql .= "AND b.fecha_turno::date > '".$fechacita."'::date ";
					$sql .= "AND b.tipo_consulta_id = ".$tipo_consulta_id." ";
					$sql .= "AND b.sw_estado_cancelacion <> '1' ";
					$sql .= "AND a.hora = '".$datosCitaAsignada[hora]."' ";
					$sql .= "AND a.sw_estado IN ('0','3') ORDER BY b.fecha_turno, a.hora ASC";
					
				
					$rst = $dbconn->Execute($sql);
				
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al traer informacion de las tablas agenda_turnos,agenda_citas.";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
						return false;
					}
						
					
					$i=0;
					while(!$rst->EOF)
					{
						$profesionales[$i][0] = $rst->fields[6];//TIPO_ID_PROFESIONAL
						$profesionales[$i][1] = $rst->fields[7];//PROFESIONAL_ID
						$profesionales[$i][2] = $rst->fields[5];//FECHA_TURNO
						$profesionales[$i][3] = $rst->fields[1];//HORA
						$profesionales[$i][4] = $rst->fields[0];//AGENDA_CITA_ID
						$profesionales[$i][5] = $rst->fields[8];//TIPO_CONSULTA _ID
						$profesionales[$i][6] = $rst->fields[9];//consultorio
						$profesionales[$i][7] = $rst->fields[10];//duracion
						$rst->MoveNext();
						$i++;
					}
								
					$cnt=$i;
					$rst->Close();
					
					$fin=false;
					$i=0;
					$c=$this->cnt_citas_asignar-1;
					$k=0;
					$fecha_cita_asig=$datosCitaAsignada['fecha_turno'];
					//echo '<pre>'.print_r($profesionales).'<br>';
					//echo "<br>".$c." ---   ".$cnt."<br>";
					$this->ASIGNO=0;
					$PASO=0;
					$asig = '2';
					
					// jab -- Bloqueo de tabla agenda_citas_asignadas, mientras se realiza la transaccion de citas automaticas
					$dbconn->BeginTrans();
					$sql="LOCK TABLE agenda_citas_asignadas IN ROW EXCLUSIVE MODE";
                        		$result = $dbconn->Execute($sql);
                        		if ($dbconn->ErrorNo() != 0) 
					{
                            			die(MsgOut("Error al iniciar la transaccion","Error DB : " . $dbconn->ErrorMsg()));
			                            $dbconn->RollbackTrans();
                        		    return false;
                        		}
					
					for($k=0;$k<$c;$k++)
					{	
						$asignada=false;	
						//$this->ASIGNO++;
						while(!$asignada && $i<$cnt){
							////
							$PASO++;
							$departamento = $this->GetDepartamento($profesionales[$i][5]);
							if($TipoPlanPaciente == '3' || $departamento=='020204' || $departamento=='010604'){
							      
							      //echo 'FECHA: ----'.$profesionales[$i][2].'-'.$fecha_cita_asig.' = '.$this->Restar_Fechas($profesionales[$i][2],$fecha_cita_asig);
							       
							       if($TipoPlanPaciente == '3'){
									if($departamento!='020204' || $departamento!='010604')
									{	
										if($asig=='1')
										{
												$continua=true;
										}else	
										{	
											if($this->Restar_Fechas($profesionales[$i][2],$fecha_cita_asig)>=2){
												$continua=true;
												$asig='2';
												
											}
											else{
												$continua=false;
											}
										}
									}
								}
								if($departamento=='020204' || $departamento=='010604'){
									if($asig=='1')
									{
											$continua=true;
									}
									else	
									{	
										if($this->Restar_Fechas($profesionales[$i][2],$fecha_cita_asig)>=3){
											$continua=true;
											$asig='2';
											
										}
										else{
											$continua=false;
										}
									}
								}		
							}
							else{
							 	$continua=true;
							}
							if($continua){
								if($this->PacienteFechaTurno($datos['tipo_id'],$datos['paciente_id'],$profesionales[$i][2],$datosCitaAsignada['tipo_consulta_id'])==0)
								{
										
									//AQUI ES DONDE SE HACE LA VALIDACION DE LAS CANTIDADES DE PACIENTES POR TIPO PLAN
									//CON AYUDA DE LA FUNCION GetCitasTiposPlanes_ProfesionalDpto($TipoDocumento,$DocumentoId,$Departamento)
				// 					
									//SELECCION DE CANTIDADES DE PACIENTES POR TIPO PLAN
									
									$consulta=$this->GetCitasTiposPlanes_ProfesionalDpto($profesionales[$i][0],$profesionales[$i][1],$departamento);
									$TipoPlanPaciente = $this->GetTipoPlan($datos['plan_id']);
									//CANTIDAD MAXIMA DE PACIENTES A ATENDER POR EL PLAN DE LA NUEVA CITA
									$TotalPacientesPlan=$consulta[$TipoPlanPaciente];
									if($this->CantidadCitasAsignadasPlan($profesionales[$i][4],$TipoPlanPaciente)<$TotalPacientesPlan)
									{
										//echo '<pre>'.print_r($profesionales[$i]).'<br>';
										
										$sql="SELECT NEXTVAL('agenda_citas_asignadas_agenda_cita_asignada_id_seq');";
										$result8 = $dbconn->Execute($sql);
										if ($dbconn->ErrorNo() != 0){
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB i: " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans(); //jab
											return false;
										}
										if(!$result8->EOF){
											$agenda_cita_asignada_id=$result8->fields[0];
											$result8->Close();
										}
										///////////////////////////////////////////////////
										$observacion = "ASIGNACION DE CITA AUTOMATICA";
										
										$sql="INSERT INTO agenda_citas_asignadas
											(
												agenda_cita_asignada_id,
												agenda_cita_id,
												paciente_id,
												tipo_id_paciente,
												tipo_cita,
												plan_id,
												cargo_cita,
												observacion,
												usuario_id,
												agenda_cita_id_padre,
												fecha_registro,
												sw_prioritaria
											)
											values
											(
												".$agenda_cita_asignada_id.",
												".$profesionales[$i][4].",
												'".$datos['paciente_id']."',
												'".$datos['tipo_id']."',
												'02',
												'".$datosCitaAsignada['plan_id']."',
												'".$datosCitaAsignada['cargo_cita']."',
												'".$observacion."',
												".UserGetUID().",
												".$profesionales[$i][4].",
												'".date("Y-m-d H:i:s")."',
												'0'
											);";
				
										$rst = $dbconn->Execute($sql);
										if($dbconn->ErrorNo() != 0){
											echo 'ERROR: 0->'.$sql;
											$this->error = "Error al insertar informaciï¿½ en la tabla agenda_citas_asignadas.";
											$this->mensajeDeError = "Error DB E2: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();	
											return false;
										}
										///////////////////////////////////////////////////////////////////////
										
										$sql="SELECT NEXTVAL('hc_os_solicitudes_hc_os_solicitud_id_seq');";
										$result1 = $dbconn->Execute($sql);
										if ($dbconn->ErrorNo() != 0)
										{
											echo 'ERROR: A->'.$sql;
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB E3: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();
											return false;
										}
				
										$sql="INSERT INTO hc_os_solicitudes 
													(
														hc_os_solicitud_id, 
														cargo, 
														plan_id, 
														os_tipo_solicitud_id, 
														sw_estado, 
														paciente_id, 
														tipo_id_paciente
													) 
													VALUES
													(
														".$result1->fields[0].",
														'".$datosCitaAsignada['cargo_cita']."',
														'".$datosCitaAsignada['plan_id']."',
														'CIT',
														'0',
														'".$datos['paciente_id']."',
														'".$datos['tipo_id']."'
													);";
										$r = $dbconn->Execute($sql);
										if ($dbconn->ErrorNo() != 0)
										{
											echo 'ERROR: 1->'.$sql;
											
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB E4: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();
											return false;
										}
										
										//BIEN
										$sql= "SELECT * 
										FROM os_ordenes_servicios
										WHERE orden_servicio_id = (SELECT MAX(orden_servicio_id)
														FROM os_ordenes_servicios
														WHERE paciente_id = '".$datos['paciente_id']."'
														AND tipo_id_paciente = '".$datos['tipo_id']."'
														); ";
										$result9 = $this->ConexionBaseDatos($sql);
										if(!$result9->EOF){
											$os_ordenes_servicios=$result9->GetRowAssoc($ToUpper = false);
										}
										//else
											//echo 'ERROR: B->'.$sql;
										
										//BIEN							
										$sql="INSERT INTO hc_os_solicitudes_citas 
													(
														hc_os_solicitud_id,
														tipo_consulta_id
													)
													VALUES
													(
														".$result1->fields[0].",
														".$profesionales[$i][5]."
													);";
										$r = $dbconn->Execute($sql);
										if ($dbconn->ErrorNo() != 0)
										{
											echo 'ERROR: 2->'.$sql;
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB E5: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();
											return false;
										}
				
										$sql="INSERT INTO hc_os_autorizaciones
													(
														hc_os_solicitud_id,
														autorizacion_int,
														autorizacion_ext
													) values 
													(
														".$result1->fields[0].",
														".$os_ordenes_servicios['autorizacion_int'].",
														".$os_ordenes_servicios['autorizacion_ext']."
													);";
										$r = $dbconn->Execute($sql);
										if ($dbconn->ErrorNo() != 0)
										{
											echo 'ERROR: 3->'.$sql;
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB E6: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();
											return false;
										}
										
										$sql="SELECT NEXTVAL('os_ordenes_servicios_orden_servicio_id_seq');";
										$result2 = $dbconn->Execute($sql);
										if ($dbconn->ErrorNo() != 0)
										{
											
											echo 'ERROR: C->'.$sql;
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB E6: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();
											return false;
										}
										$evento = "NULL";
										
										$servicio = $this->BusquedaServicio($departamento);	
									//EXTRAER DATOS DE LA ULTIMA ORDEN DE SERVICIO DEL PACIENTE
									//BIEN
									$sql= "SELECT * 
										FROM  os_ordenes_servicios
										WHERE orden_servicio_id = 
										(SELECT MAX(orden_servicio_id) 
										FROM  os_ordenes_servicios
										WHERE tipo_id_paciente = '".$datos['tipo_id']."'
											AND paciente_id = '".$datos['paciente_id']."'); ";
									$result = $this->ConexionBaseDatos($sql);
									if(!$result->EOF){
										$dat=$result->GetRowAssoc($ToUpper = false);;
									}
									//else
											//echo 'ERROR: D->'.$sql;
									//FIN EXTRAER DATOS DE LA ULTIMA ORDEN DE SERVICIO DEL PACIENTE
									
									$evento = "NULL";
									//bien
									$sql = "INSERT INTO os_ordenes_servicios (
											orden_servicio_id, 
											autorizacion_int, 
											autorizacion_ext, 
											plan_id, 
											tipo_afiliado_id, 
											rango, 
											semanas_cotizadas, 
											servicio, 
											tipo_id_paciente, 
											paciente_id, 
											usuario_id,
											fecha_vencimiento, --JPS
											fecha_registro,
											evento_soat,
											departamento) 
											VALUES (".$result2->fields[0].", 
											".$dat['autorizacion_int'].", 
											".$dat['autorizacion_ext'].", 
											".$dat['plan_id'].",
											'".$dat['tipo_afiliado_id']."', 
											'".$dat['rango']."', 
											'".$dat['semanas_cotizadas']."', 
											'".$dat['servicio']."', 
											'".$datos['tipo_id']."',
											'".$datos['paciente_id']."',
											".UserGetUID().",
											'".$profesionales[$i][2]."', --JPS
											'".date("Y-m-d H:i:s")."',
											".$evento.",
											'".$dat['departamento']."');";
									$r = $this->ConexionBaseDatos($sql);
									if (!$r)
									{
										echo 'ERROR: 4->'.$sql;
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB e: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
										$dbconn->RollbackTrans();
										
										return false;
									}
									////////////////B
										
										$sql="SELECT NEXTVAL('os_maestro_numero_orden_id_seq');";
										$result4 = $dbconn->Execute($sql);
										if ($dbconn->ErrorNo() != 0)
										{
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB E9 : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();
											return false;
										}
										//else
											//echo 'ERROR: E->'.$sql;
										
										$FechaTurno = $profesionales[$i][2];
										$HoraTurno = $profesionales[$i][3];
										$_SESSION['AsignacionCitas']['numero_orden_id']=$result4->fields[0];
										
										//*****************
										//EXTAER CARGO CUPS
										$sql= "	SELECT * 
											FROM os_maestro
											WHERE orden_servicio_id = ".$dat[orden_servicio_id]."; ";
										$result = $this->ConexionBaseDatos($sql);
										if(!$result->EOF){
											$dat2=$result->GetRowAssoc($ToUpper = false);;
										}
										//else
											//echo 'ERROR: F->'.$sql;
										//FIN EXTRAER CARGO CUPS
									
									
			
										if(!$dat2[cargo_cups])
										{
											$sql= "	SELECT * 
												FROM  agenda_citas_asignadas
												WHERE agenda_cita_asignada_id = 
												(SELECT MAX(agenda_cita_asignada_id) 
												FROM  agenda_citas_asignadas
												WHERE tipo_id_paciente = '".$datos['tipo_id']."'
												AND paciente_id = '".$datos['paciente_id']."'); ";
											$result = $this->ConexionBaseDatos($sql);
											if(!$result->EOF){
												$d=$result->GetRowAssoc($ToUpper = false);
												$dat2[cargo_cups] = $d[cargo_cita];
											}
											//else
												//echo 'ERROR: G->'.$sql;
										}
									//*****************
										if(empty($_SESSION['CumplirCita']['cita']))
										{
											$sql="INSERT INTO os_maestro
												(
													numero_orden_id, 
													orden_servicio_id, 
													fecha_vencimiento, 
													hc_os_solicitud_id, 
													cargo_cups
													
												)
												VALUES
												(
													".$result4->fields[0].",
													".$result2->fields[0].",
													'".$FechaTurno."',
													".$result1->fields[0].",
													'".$datosCitaAsignada['cargo_cita']."'
												);";
										}
										else
										{
											$sql="INSERT INTO os_maestro
												(
													numero_orden_id,
													orden_servicio_id,
													fecha_vencimiento,
													hc_os_solicitud_id,
													cargo_cups,
													sw_estado
												)
												VALUES
												(
													".$result4->fields[0].",
													".$result2->fields[0].",
													'".$FechaTurno."',
													".$result1->fields[0].",
													'".$datosCitaAsignada['cargo_cita']."',
													'5'
												);";
										}
										$r = $dbconn->Execute($sql);
										if ($dbconn->ErrorNo() != 0)
										{
											echo 'ERROR: 5->'.$sql;
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB E10: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();
											return false;
										}
										///////C
																		
										$sql="INSERT INTO os_internas
												(
													numero_orden_id,
													cargo,
													departamento
												)
												VALUES
												(
													".$result4->fields[0].",
													'".$datosCitaAsignada['cargo_cita']."',
													'".$departamento."'
												);";
										$r = $dbconn->Execute($sql);
										if ($dbconn->ErrorNo() != 0)
										{
											echo 'ERROR: 6->'.$sql;
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB E11: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();
											return false;
										}
										$sql="INSERT INTO os_cruce_citas
													(
														numero_orden_id,
														agenda_cita_asignada_id
													)
													VALUES
													(
														".$result4->fields[0].",
														$agenda_cita_asignada_id
													);";
										$r = $dbconn->Execute($sql);
										if ($dbconn->ErrorNo() != 0)
										{
											echo 'ERROR: 7->'.$sql;
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB E12: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();
											return false;
										}
										$sql="SELECT NEXTVAL('os_maestro_cargos_os_maestro_cargos_id_seq');";
										$result5 = $dbconn->Execute($sql);
										if ($dbconn->ErrorNo() != 0)
										{
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB E13: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();
											return false;
										}
										//else
											//echo 'ERROR: H->'.$sql;
										
										$_SESSION['AsignacionCitas']['os_maestro_cargos_id']=$result5->fields[0];
										
										///////////////////////////TARIFARIOS
										$sql="SELECT a.tarifario_id, a.cargo,b.descripcion 
												FROM tarifarios_equivalencias as a, tarifarios_detalle as b, plan_tarifario as c 
												WHERE cargo_base='".$datosCitaAsignada['cargo_cita']."' 
												AND a.tarifario_id=b.tarifario_id 
												AND a.cargo=b.cargo 
												AND b.grupo_tarifario_id=c.grupo_tarifario_id 
												AND b.subgrupo_tarifario_id=c.subgrupo_tarifario_id 
												AND c.plan_id=".$dat['plan_id']." 
												AND b.tarifario_id=c.tarifario_id;";
										$result = $dbconn->Execute($sql);
										if($dbconn->ErrorNo() != 0){
											
											echo 'ERROR: I->'.$sql;
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB E1: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();
											return false;
										}
										$tarifario = $result->fields[0];
										$cargo     = $result->fields[1];
										
										//TRAER LA TRANSACCION DE LA PRIMERA CUENTA
										
																			
										$sql="INSERT into os_maestro_cargos 
												(
													os_maestro_cargos_id,
													numero_orden_id,
													tarifario_id,
													cargo
													
												)
												VALUES
												(
													".$result5->fields[0].",
													".$result4->fields[0].",
													'".$tarifario."',
													'".$cargo."'
													
													
													
												);";
										
										$r = $dbconn->Execute($sql);
										if ($dbconn->ErrorNo() != 0)
										{
											echo 'ERROR: 8->'.$sql;
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB E14: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();
											return false;
										}
										$asignada=true;
										$sql="select nombre from profesionales where tipo_id_tercero = '".$profesionales[$i][0]."' and tercero_id = '".$profesionales[$i][1]."'";
										$r = $dbconn->Execute($sql);
										if ($dbconn->ErrorNo() != 0)
										{
											echo 'ERROR: profesional->'.$sql;
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB E14: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
											$dbconn->RollbackTrans();
											return false;
										}
										/////JP
										$this->turno_cita_asignada[$k][0]= $profesionales[$i][4];
										$this->turno_cita_asignada[$k][1]= $profesionales[$i][2].":".$profesionales[$i][3];
										$this->turno_cita_asignada[$k][2]= $profesionales[$i][7];
										$this->turno_cita_asignada[$k][3]= $r->fields[0];
										$this->turno_cita_asignada[$k][4]= $profesionales[$i][6];
										//$i++;
										//jab -- se movio de la parte de arriba del for porq estaba retornando una cita asignada de mas
										$this->ASIGNO++;
										
										$asig='2';
										
										$fecha_cita_asig=$profesionales[$i][2];
										
									}
									else{
										$asig='1';
										$errorcnt="LA CITA NO SE ASIGNO POR LA CANTIDAD DE PACIENTES POR TIPO PLAN. VERIFIQUE LA PARAMETRIZACION DE CITAS";
										$i++;
									}
								
								}
								else{
									$asig='1';
									$i++;
								}
							}
							else{ $i++;}
							
						}
						//////////////////controlan el bclude la cantidad de citas a asignar y la cntidad de cupos
						
						if($i==$cnt){break;$this->CORTO=1;}
					}							
					$dbconn->CommitTrans(); //jab
				
				
				
				
				if(($this->cnt_citas_asignar-$this->ASIGNO)>1){
					
					
					
					$sql="UPDATE os_maestro_cargos SET cnt_citas_auto = 0 WHERE os_maestro_cargos_id = ".$os_maestro_cargos_id." 
					 ;";	
					$r = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0)
					{
						echo 'ERROR: UPDATE os_maestro_cargos->'.$sql;
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB E14: " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
						return false;
					}
				}
				
			}
			return true;
		}
		
	function CantidadCitasAsignadasPlan($AgendaCitaId,$TipoPlanId)
	{
		list($dbconn) = GetDBconn();
		$SQL="SELECT COUNT(a.agenda_cita_id)  FROM agenda_citas_asignadas as a, tipos_planes as b, planes as c
		WHERE a.agenda_cita_id=".$AgendaCitaId." AND a.plan_id=c.plan_id 
		AND c.sw_tipo_plan=b.sw_tipo_plan
		AND b.sw_tipo_plan='".$TipoPlanId."'
		AND a.agenda_cita_asignada_id NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion)
		;";
		
		
		$rst = $dbconn->Execute($SQL);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer informacion de la tabla agenda_citas_asiganadas metodo CantidadCitasAsignadasPlan.";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
				return false;
			}
			else{
				
        			$var=$rst->fields[0];
				$rst->Close();
				return $var;			
      				
				
			}
		return true;
	}
	function GetCitasTiposPlanes_ProfesionalDpto($TipoDocumento,$DocumentoId,$Departamento)
	{
        	list($dbconn) = GetDBconn();
        	
		$query="SELECT * FROM citas_tipo_plan WHERE tipo_id_tercero='".$TipoDocumento."' AND tercero_id='".$DocumentoId."' AND departamento_id='".$Departamento."';";
        	$result = $dbconn->Execute($query);
        	if ($dbconn->ErrorNo() != 0) {
           		$this->error = "Error al Cargar el Modulo[citas_tipo_plan]";
            		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';;
            		return false;
        	}else{
            		$datos=$result->RecordCount();
			$i=0;
			if($datos){
				while (!$result->EOF)
				{
					$vars[$result->fields[1]]=$result->fields[2];
					$i++;
					$result->MoveNext();
				}
			}	
			else{
				return false;	
            		}
        	}
		$result->Close();
        	return $vars;
        	
        	
	}
	function GetTipoConsulta($orden)
	{
		list($dbconn) = GetDBconn();
		$sql1 = "SELECT departamento 
             FROM   os_ordenes_servicios 
             WHERE  orden_servicio_id = ".$orden." ";
		$rst = $dbconn->Execute($sql1);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al traer informaci0n de la tabla tipos_consulta.";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
			return false;
		}else{
            		$datos=$rst->RecordCount();
            		if($datos){
        			$departamento= $rst->fields[0];
				$rst->Close();
				
			}
        	}
		
		
		
		$SQL="SELECT tipo_consulta_id FROM tipos_consulta WHERE departamento = '".$departamento."'";
		$rst = $dbconn->Execute($SQL);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al traer informaci0n de la tabla tipos_consulta.";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
			return false;
		}else{
            		$datos=$rst->RecordCount();
            		if($datos){
        			$var= $rst->fields[0];
				$rst->Close();
				return $var;
			}
        	}
	
	
	}
	
	function GetDepartamento($tipo_consulta_id)
	{
		list($dbconn) = GetDBconn();
		$SQL="SELECT * FROM tipos_consulta WHERE tipo_consulta_id = '".$tipo_consulta_id."'";
		$rst = $dbconn->Execute($SQL);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al traer informaci0n de la tabla tipos_consulta.";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
			return false;
		}else{
            		$datos=$rst->RecordCount();
            		if($datos){
        			$var= $rst->fields[1];
				$rst->Close();
				return $var;
			}
        	}
        	
	
	
	}	
		
		
		
		
		
		
		
		
		
		/**********************************************************************************
		* Funcion donde se obtienen los profesionales que prestan servicios a un 
		* departamento determinado
		*
		* @param 	string  $departamento	Identificador del departamento
		* @return rst
		************************************************************************************/
		function ComboProfesionales($departamento)
		{
			$sql  = "SELECT DISTINCT	PU.usuario_id,";
			$sql .= "				PR.nombre,";
			$sql .= "				PR.tipo_id_tercero,";
			$sql .= "				PR.tercero_id ";
			$sql .= "FROM		profesionales_departamentos PD,";
			$sql .= "				profesionales PR, ";
			$sql .= "				profesionales_usuarios PU ";
			$sql .= "WHERE	PD.departamento = '".$departamento."' ";
			$sql .= "AND		PD.tipo_id_tercero = PR.tipo_id_tercero ";
			$sql .= "AND		PD.tercero_id = PR.tercero_id ";
			$sql .= "AND		PU.tercero_id = PR.tercero_id ";
			$sql .= "AND		PU.tipo_tercero_id = PR.tipo_id_tercero ";
			$sql .= "ORDER BY PR.nombre ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
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
				$this->frmError['MensajeError'] = "ERROR DB  15: " . $dbconn->ErrorMsg();
				echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}
		/**********************************************************************************
		* Funcion que permite crear una transaccion 
		* @param string $sql Sql a ejecutar- para dar inicio a la transaccion se pasa vacio
		* @param char $num Numero correspondiente a la sentecia sql - por defect es 1
		*
		* @return object Objeto de la transaccion - Al momento de iniciar la transaccion no 
		*								 se devuelve nada
		***********************************************************************************/
		function ConexionTransaccion($sql,$num = '1')
		{
			if(!$sql)
			{
				list($this->dbconn) = GetDBconn();
				//$this->dbconn->debug=true;
				$this->dbconn->BeginTrans();
			}
			else
			{
				$rst = $this->dbconn->Execute($sql);
				if ($this->dbconn->ErrorNo() != 0)
				{
					$this->frmError['MensajeError'] = "ERROR DB : " . $this->dbconn->ErrorMsg();
					echo "<b class=\"label\">Trasaccion: $num - ".$this->frmError['MensajeError']."</b>";
					$this->dbconn->RollbackTrans();
					return false;
				}
				return $rst;
			}
		}
	}
?>