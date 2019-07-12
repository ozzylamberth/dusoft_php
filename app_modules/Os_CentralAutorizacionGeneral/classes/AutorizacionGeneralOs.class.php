<?php
	/**************************************************************************************
	* $Id: AutorizacionGeneralOs.class.php,v 1.1 2007/04/16 20:46:40 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F  Manrique
	***************************************************************************************/
	class AutorizacionGeneralOs
	{
		function AutorizacionGeneralOs(){}
		/**************************************************************************************
		*
		***************************************************************************************/
		function ObtenerDatosPaciente($criterio,$offset)
		{			
			$sql  = "SELECT	PA.paciente_id,";
			$sql .= "				PA.tipo_id_paciente, ";
			$sql .= "				PA.primer_nombre||' '||PA.segundo_nombre AS nombre, ";
			$sql .= "				PA.primer_apellido||' '||PA.segundo_apellido AS apellido ";
			$sql .= "FROM		pacientes PA ";
			
			if($criterio['ingreso']) $sql .= "				,ingresos IG ";
			
			if($criterio['ingreso'])
			{
				$sql .= "WHERE	PA.tipo_id_paciente = IG.tipo_id_paciente ";
				$sql .= "AND		PA.paciente_id = IG.paciente_id ";
				$sql .= "AND		IG.ingreso = ".$criterio['ingreso']." ";
				$sql .= "ORDER BY nombre,apellido ";
			}
			else if($criterio['documento'])
				{
					$sql .= "WHERE	PA.tipo_id_paciente = '".$criterio['tipodocumento']."' ";
					$sql .= "AND		PA.paciente_id = '".$criterio['documento']."' ";
					$sql .= "ORDER BY nombre,apellido ";
				}
				else if($criterio['nombres'] || $criterio['apellidos'])
					{
						IncludeClass('ClaseUtil');
						$clt = new ClaseUtil();
						$sql .= "WHERE ".$clt->FiltrarNombres($criterio['nombres'],$criterio['apellidos'],"PA");
						
						$this->registros = $datos['registros'];
						if(!$this->registros)
						{
							if(!$rst = $this->ConexionBaseDatos($sql)) return false;
							if(!$rst->EOF) $this->registros = $rst->RecordCount();
						}
						
						if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM ($sql) AS MD ",$offset))
						return false;
						
						$sql .= "ORDER BY nombre,apellido ";
						$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
					}
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerCargosSolicitudes($tid,$pid, $plan = null,$auto = null, $estado = "1")
		{
			$sql .= "SELECT PT.plan_id,";
			$sql .= "				CU.cargo, ";
			$sql .= "				TD.descripcion AS tarifario, ";
			$sql .= "				TD.tarifario_id, ";
			$sql .= "				TD.cargo, ";
			$sql .= "				TA.descripcion AS desc_tarifario, ";
			$sql .= "				CU.sw_cantidad ";
			$sql .= "FROM		hc_os_solicitudes HS, ";
			$sql .= "				departamentos_cargos DC, ";
			$sql .= "				cups CU, ";
			$sql .= "				tarifarios_equivalencias TE, ";
			$sql .= "				tarifarios_detalle TD, ";
			$sql .= "				tarifarios TA, ";
			$sql .= "				plan_tarifario PT ";
			$sql .= "WHERE 	HS.paciente_id = '".$pid."' ";
			$sql .= "AND 		HS.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND 		HS.sw_estado = '".$estado."' ";
			$sql .= "AND 		HS.plan_id = PT.plan_id ";
			$sql .= "AND 		DC.cargo = HS.cargo  ";
			$sql .= "AND 		DC.cargo = CU.cargo  ";
			$sql .= "AND 		CU.cargo = TE.cargo_base ";
			$sql .= "AND		TE.tarifario_id = TD.tarifario_id ";
			$sql .= "AND		TE.cargo = TD.cargo ";
			$sql .= "AND		TA.tarifario_id = TD.tarifario_id ";
      $sql .= "AND 		TD.grupo_tarifario_id = PT.grupo_tarifario_id ";
      $sql .= "AND		TD.subgrupo_tarifario_id = PT.subgrupo_tarifario_id ";
      $sql .= "AND		TD.tarifario_id = PT.tarifario_id ";
      $sql .= "AND 		excepciones(PT.plan_id,PT.tarifario_id, TD.cargo) = 0 ";
			
			if($plan)
				$sql .= "AND 		HS.plan_id = ".$plan." ";
				
			if($auto)
				$sql .= "AND 		HS.autorizacion = ".$auto." ";
			
			$sql .= "UNION ";
			$sql .= "SELECT EX.plan_id,";
			$sql .= "				CU.cargo, ";
			$sql .= "				TD.descripcion AS tarifario, ";
			$sql .= "				TD.tarifario_id, ";
			$sql .= "				TD.cargo, ";
			$sql .= "				TA.descripcion AS desc_tarifario, ";
			$sql .= "				CU.sw_cantidad ";
			$sql .= "FROM		hc_os_solicitudes HS, ";
			$sql .= "				departamentos_cargos DC, ";
			$sql .= "				cups CU, ";
			$sql .= "				tarifarios_equivalencias TE, ";
			$sql .= "				tarifarios_detalle TD, ";
			$sql .= "				tarifarios TA, ";
			$sql .= "				excepciones EX ";
			$sql .= "WHERE 	HS.paciente_id = '".$pid."' ";
			$sql .= "AND 		HS.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND 		HS.sw_estado = '".$estado."' ";
			$sql .= "AND 		HS.plan_id = EX.plan_id ";
			$sql .= "AND 		DC.cargo = HS.cargo  ";
			$sql .= "AND 		DC.cargo = CU.cargo  ";
			$sql .= "AND 		CU.cargo = TE.cargo_base ";
			$sql .= "AND		TE.tarifario_id = TD.tarifario_id ";
			$sql .= "AND		TE.cargo = TD.cargo ";
			$sql .= "AND		TA.tarifario_id = TD.tarifario_id ";
      $sql .= "AND 		EX.tarifario_id = TE.tarifario_id ";

			if($plan)
				$sql .= "AND 		HS.plan_id = ".$plan." ";

			if($auto)
				$sql .= "AND 		HS.autorizacion = ".$auto." ";
	
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[0]][$rst->fields[1]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerSolicitudes($tid,$pid,$plan = null, $auto = null, $estado = '1')
		{
			$sql .= "SELECT DISTINCT ";
			$sql .= "				PL.plan_descripcion,";
			$sql .= "				SE.descripcion AS des_servicio,";
			$sql .= "				HS.plan_id,";
			$sql .= "				HS.hc_os_solicitud_id, ";
			$sql .= "				HS.os_tipo_solicitud_id, ";
			$sql .= "				CASE WHEN HS.evolucion_id IS NULL THEN 'M'";
			$sql .= "					ELSE 'H' END AS tipo, ";
			$sql .= "				CU.cargo, ";
			$sql .= "				CU.descripcion AS cups, ";
			$sql .= "				HS.cantidad, ";
			$sql .= "				TO_CHAR(HS.fecha_solicitud, 'DD/MM/YYYY HH:MI AM') AS fecha, ";
			$sql .= "				SE.servicio ";
			$sql .= "FROM		hc_os_solicitudes HS, ";
			$sql .= "				cups CU, ";
			$sql .= "				planes PL, ";
			$sql .= "				servicios SE ";
			$sql .= "WHERE 	HS.paciente_id = '".$pid."' ";
			$sql .= "AND 		HS.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND 		HS.sw_estado = '".$estado."' ";
			$sql .= "AND 		CU.cargo = HS.cargo  ";
			$sql .= "AND 		PL.plan_id = HS.plan_id  ";
			$sql .= "AND 		HS.servicio = SE.servicio ";
			
			if($plan)
				$sql .= "AND 		HS.plan_id = ".$plan."  ";
			
			if($auto)
				$sql .= "AND 		HS.autorizacion = ".$auto."  ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[0]][$rst->fields[1]][$rst->fields[5]][$rst->fields[3]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerSolicitudesAutorizadas($tid,$pid,$plan = null, $auto = null, $estado = '1')
		{
			$sql .= "SELECT DISTINCT ";
			$sql .= "				CASE WHEN CD.total = 1 THEN DC.departamento ";
			$sql .= "				ELSE '0' END AS departamento, ";
			$sql .= "				CASE WHEN SE.servicio = '3' THEN 'AMBULATORIA' ";
			$sql .= "				ELSE 'PACIENTE INTERNO' END AS tipo_orden, ";
			$sql .= "				PL.plan_descripcion,";
			$sql .= "				SE.descripcion AS des_servicio,";
			$sql .= "				HS.plan_id,";
			$sql .= "				HS.hc_os_solicitud_id, ";
			$sql .= "				HS.os_tipo_solicitud_id, ";
			$sql .= "				CU.cargo, ";
			$sql .= "				CU.descripcion AS cups, ";
			$sql .= "				HS.cantidad, ";
			$sql .= "				TO_CHAR(HS.fecha_solicitud, 'DD/MM/YYYY HH:MI AM') AS fecha, ";
			$sql .= "				SE.servicio ";
			$sql .= "FROM		hc_os_solicitudes HS, ";
			$sql .= "				cups CU, ";
			$sql .= "				departamentos_cargos DC, ";
			$sql .= "				(	SELECT 	COUNT(CU.cargo) AS total, ";
			$sql .= "									CU.cargo ";
			$sql .= "					FROM		cups CU, ";
			$sql .= "									departamentos_cargos DC ";
			$sql .= "					WHERE		CU.cargo = DC.cargo ";
			$sql .= "					GROUP BY 2 ";
			$sql .= "				) AS CD, ";
			$sql .= "				planes PL, ";
			$sql .= "				servicios SE ";
			$sql .= "WHERE 	HS.paciente_id = '".$pid."' ";
			$sql .= "AND 		HS.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND 		HS.sw_estado = '".$estado."' ";
			$sql .= "AND 		CU.cargo = HS.cargo  ";
			$sql .= "AND 		CU.cargo = DC.cargo  ";
			$sql .= "AND		CD.cargo = CU.cargo  ";
			$sql .= "AND 		PL.plan_id = HS.plan_id  ";
			$sql .= "AND 		HS.servicio = SE.servicio ";
			
			if($plan)
				$sql .= "AND 		HS.plan_id = ".$plan."  ";
			
			if($auto)
				$sql .= "AND 		HS.autorizacion = ".$auto."  ";
			
			$sql .= "ORDER BY departamento ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				//$datos['010501'][$rst->fields[4]][$rst->fields[6]] = $rst->GetRowAssoc($ToUpper = false);
				$datos[$rst->fields[0]][$rst->fields[1]][$rst->fields[5]][$rst->fields[7]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function AnularSolicitud($datos)
		{
			$sql  = "UPDATE	hc_os_solicitudes ";
			$sql .= "SET 		sw_estado = '2' ";
			$sql .= "WHERE 	hc_os_solicitud_id = ".$datos['hc_os_solicitud_id']."; ";

			$sql .= "INSERT INTO auditoria_anular_solicitudes ";
			$sql .= "			(	hc_os_solicitud_id,";
			$sql .= " 			usuario_id,";
			$sql .= " 			fecha_registro,";
			$sql .= " 			observacion ) ";
			$sql .= "VALUES(";
			$sql .= "				".$datos['hc_os_solicitud_id'].", ";
			$sql .= "				".UserGetUID().", ";
			$sql .= "				'NOW()', ";
			$sql .= "				'".$datos['observacion']."' ";
			$sql .= "			); ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerDepartamentoPrestadorServicio($cargo = null, $deptno = null)
		{
			$sql  = "SELECT	DC.departamento, ";
			$sql .= "				DE.descripcion ";
			$sql .= "FROM 	departamentos_cargos DC, ";
			$sql .= "				departamentos DE ";
			$sql .= "WHERE 	DE.departamento = DC.departamento ";
			
			if($cargo)
				$sql .= "AND 	DC.cargo = '".$cargo."' ";
			
			if($deptno)
				$sql .= "AND 	DE.departamento = '".$deptno."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/************************************************************************************ 
		*
		*************************************************************************************/
		function CrearOrdenServicio($ordenes,$datos,$ingreso)
		{
			$cargos = "";
			$evento_soat = "";
			$num_ordenes = "";
			$fechabase = date("Y-m-d");
			$dias_c = array();
			$afiliacion = array();
			
			if(!$evento_soat) $evento_soat = "NULL";
			if(!$ingreso) $ingreso = "NULL";
			
			$sql  = "SELECT tipo_afiliado_id,";
			$sql .= "				semanas_cotizadas,";
			$sql .= "				rango ";
			$sql .= "FROM		autorizaciones ";
			$sql .= "WHERE	autorizacion = ".$datos['numero_autorizacion']." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			if(!$rst->EOF)
			{
				$afiliacion = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			foreach($ordenes as $key0 => $departamentos)
			{
				if($key0 != '0')
				{
					foreach($departamentos as $keyE => $evento)
					{	
						foreach($evento as $keyI => $servicio)
						{
							foreach($servicio as $keyH => $solicitudes)
							{
								foreach($solicitudes['cargo_cup'] as $keyS => $cargo)
								{
									($cargos == "")? $cargos = "'".$keyS."'" :	$cargos .= ",'".$keyS."'";
								}
							}
						}
					}
				}
			}

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$eventos = array();
			while(!$rst->EOF)
			{
				$eventos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			list($anyo,$mes,$dia) = split("-", $fechabase);
						
			$sql  = "SELECT MAX(dias_vigencia) AS dias_vigencia,";
			$sql .= "				MAX(dias_refrendar) AS dias_refrendar,";
			$sql .= "				MAX(dias_tramite_os) AS dias_tramite_os ";
			$sql .= "FROM 	os_tipos_periodos_planes ";
			$sql .= "WHERE	plan_id = ".$datos['plan_id']." ";
			$sql .= "AND 		cargo IN (".$cargos.") ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
						
			if(!$rst->EOF)
			{
				$dias_c = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
						
			if(!$dias_c['dias_tramite_os'])
							$dias_c['dias_tramite_os'] = ModuloGetVar('app','CentroAutorizacion','dias_tramite_os');
					
			if(!$dias_c['dias_refrendar'])
				$dias_c['dias_refrendar'] = ModuloGetVar('app','CentroAutorizacion','dias_refrendar');
			
			if(!$dias_c['dias_vigencia'])
				$dias_c['dias_vigencia'] = ModuloGetVar('app','CentroAutorizacion','dias_vigencia');
		
			$fecha = mktime(0,0,0, $mes,$dia,$anyo) + $dias_c['dias_tramite_os'] * 24 * 60 * 60;
			$fecha_tramite = date("Y-m-d",$fecha);
			
			$fecha = mktime(0,0,0, $mes,$dia,$anyo) + $dias_c['dias_refrendar'] * 24 * 60 * 60;
			$fecha_refrendar = date("Y-m-d",$fecha);
			
			$fecha = mktime(0,0,0, $mes,$dia,$anyo) + $dias_c['dias_vigencia'] * 24 * 60 * 60;
			$fecha_vigencia = date("Y-m-d",$fecha);
			
			foreach($ordenes as $key0 => $departamentos)
			{
				if($key0 != '0')
				{
					foreach($departamentos as $keyE => $evento)
					{
						foreach($evento as $keyI => $servicio)
						{	
							($keyE == '0' || !$keyE)? $evento_soat = "NULL": $evento_soat = $keyE;
							
							$orden = "";
							$sql = "SELECT NEXTVAL('os_ordenes_servicios_orden_servicio_id_seq');";
						
							$this->ConexionTransaccion();
							if(!$rst = $this->ConexionTransaccion($sql,'1')) return false;
							if(!$rst->EOF) $orden = $rst->fields[0];
							
							$atencion = '0';
							if($keyI != '3') $atencion = '1';
							
							$sql  = "INSERT INTO os_ordenes_servicios( ";
							$sql .= "			orden_servicio_id, ";
					 		$sql .= "			autorizacion_int,";
					 		$sql .= "			plan_id, ";
					 		$sql .= "			tipo_afiliado_id,";
							$sql .= "			rango,";
					 		$sql .= "			semanas_cotizadas,";
					 		$sql .= "			servicio,";
					 		$sql .= "			tipo_id_paciente,";
					 		$sql .= "			paciente_id,";
					 		$sql .= "			usuario_id,";
					 		$sql .= "			fecha_registro,";
					 		$sql .= "			observacion,";
					 		$sql .= "			evento_soat,";
					 		$sql .= "			departamento,";
					 		$sql .= "			sw_atencion_interna,";
					 		$sql .= "			ingreso,";
					 		$sql .= "			sw_estado,";
							$sql .= "			fecha_vencimiento,";
					 		$sql .= "			fecha_activacion,";
					 		$sql .= "			fecha_refrendar";
							$sql .= " )";
							$sql .= "VALUES ( ";
							$sql .= "			 ".$orden.", ";
							$sql .= "			 ".$datos['numero_autorizacion'].", ";
							$sql .= "			 ".$datos['plan_id'].", ";
							$sql .= "			'".$afiliacion['tipo_afiliado_id']."', ";
							$sql .= "			'".$afiliacion['rango']."', ";
							$sql .= "			 ".$afiliacion['semanas_cotizadas'].", ";
							$sql .= "			'".$keyI."',";
							$sql .= "			'".$datos['tipo_id_paciente']."',";
							$sql .= "			'".$datos['paciente_id']."',";
							$sql .= "			 ".UserGetUID().", ";
							$sql .= "			NOW(),";
							$sql .= "			'".$datos['observacion']."', ";
							$sql .= "			 ".$evento_soat.", ";
							$sql .= "			'".$key0."', ";
							$sql .= "			'".$atencion."',";
							$sql .= "			 ".$ingreso.",";
							$sql .= "			'1',";
							$sql .= "			'".$fecha_vigencia."',";
							$sql .= "			'".$fecha_tramite."',";
							$sql .= "			'".$fecha_refrendar."' ";
							$sql .= ")";
							
							if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
							foreach($servicio as $keyH => $solicitudes)
							{
								foreach($solicitudes['cargo_cup'] AS $keyC => $cargo)
								{
									$sql  = "SELECT NEXTVAL('os_maestro_numero_orden_id_seq');";
									if(!$rst = $this->ConexionTransaccion($sql,'3 Os_Maestro')) return false;
									if(!$rst->EOF) $osmaestro = $rst->fields[0];
								
									$sql  = "INSERT INTO os_maestro (	";
									$sql .= "				numero_orden_id,	";
									$sql .= "				orden_servicio_id,	";
									$sql .= "				cargo_cups,	";
									$sql .= "				cantidad,	";
									$sql .= "				hc_os_solicitud_id,	";
									$sql .= "				fecha_vencimiento,	";
									$sql .= "				fecha_activacion,	";
									$sql .= "				fecha_refrendar ";
									$sql .= "				) ";
									$sql .= "VALUES (";
									$sql .= "			".$osmaestro.",";
									$sql .= " 		".$orden.",";
									$sql .= " 		'".$keyC."',";
									$sql .= " 		".$cargo['cantidad'].",";
									$sql .= " 		".$keyH.",";
									$sql .= "			'".$fecha_vigencia."',";
									$sql .= "			'".$fecha_tramite."',";
									$sql .= "			'".$fecha_refrendar."' ";
									$sql .= "		) ";
								
									if(!$rst = $this->ConexionTransaccion($sql,'4 Os_Maestro')) return false;
									$sql  = "";
									foreach($cargo['cargo'] as $keyX => $cargoe)
									{
										$sql .= "INSERT INTO os_maestro_cargos( ";
										$sql .= "			numero_orden_id,";
										$sql .= "			tarifario_id,";
										$sql .= "			cargo,";
										$sql .= "			cantidad ";
										$sql .= "			) ";
										$sql .= "VALUES ( ";
										$sql .= "			".$osmaestro.",";
										$sql .= "			'".$cargoe['tarifario_id']."',";
										$sql .= "			'".$cargoe['cargo']."',";
										$sql .= "			".$cargoe['cantidad']." ";
										$sql .= "		);	";
									}
								
									$sql .= "UPDATE hc_os_solicitudes ";
									$sql .= "SET  	sw_estado = '0' ";
									$sql .= "WHERE 	hc_os_solicitud_id = ".$keyH."; ";
								
									if(!$rst = $this->ConexionTransaccion($sql,'5 Os_Maestro')) return false;
								}
							}
							$this->dbconn->CommitTrans();
							($num_ordenes == "")? $num_ordenes .= $orden : $num_ordenes .= ",".$orden;
						}
					}
				}
			}
			return $num_ordenes;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerEvento($solicitud)
		{
			$sql  = "SELECT IT.evento ";
			$sql .= "FROM  	hc_os_solicitudes HS, ";
			$sql .= "      	hc_evoluciones HE, ";
			$sql .= "      	ingresos_soat IT ";
			$sql .= "WHERE	HE.evolucion_id = HS.evolucion_id ";
			$sql .= "AND		IT.ingreso = HE.ingreso ";
			$sql .= "AND		HS.hc_os_solicitud_id = ".$solicitud." ";
			$sql .= "UNION ";
			$sql .= "SELECT HM.evento_soat AS evento ";
			$sql .= "FROM  	hc_os_solicitudes_manuales HM ";
			$sql .= "WHERE 	HM.hc_os_solicitud_id = ".$solicitud." ";
			
			if(!$result = $this->ConexionBaseDatos($sql))
				return false;
			
			$evento = "0";
			
			if(!$result->EOF)
			{
				$evento = $result->fields[0];
				$result->MoveNext();
			}
			$result->Close();
			
			return $evento;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ActualizarFechaRefrendar($fecha1,$os_orden)
		{
			list($anyo,$mes,$dia) = split("-", $fecha1);
			
			$fecha = mktime(0,0,0, $mes,$dia,$anyo) + 432000;
			$fecha_refrendar = date("Y-m-d",$fecha);

			$sql  = "UPDATE os_ordenes_servicios ";
			$sql .= "SET		fecha_refrendar = '".$fecha_refrendar."', ";
			$sql .= "				fecha_vencimiento = '".$fecha1."' ";
			$sql .= "WHERE	orden_servicio_id = ".$os_orden."; ";
			$sql .= "UPDATE os_maestro ";
			$sql .= "SET		fecha_refrendar = '".$fecha_refrendar."', ";
			$sql .= "				fecha_vencimiento = '".$fecha1."' ";
			$sql .= "WHERE	orden_servicio_id = ".$os_orden."; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			return true;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function AnularOrdenesServicio($datos)
		{
			$sql  = "SELECT numero_orden_id, ";
			$sql .= "				hc_os_solicitud_id ";
			$sql .= "FROM		os_maestro ";
			$sql .= "WHERE	orden_servicio_id = ".$datos['orden_servicio_id']." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i = 0;
			$sol = array();
			while(!$rst->EOF)
			{
				$sol[$i++] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			$this->ConexionTransaccion();

			$sql	= "UPDATE	os_ordenes_servicios ";
			$sql .= "SET		sw_estado = '9' ";
			$sql .= "WHERE	orden_servicio_id = ".$datos['orden_servicio_id']." ";
			
			if(!$rst = $this->ConexionTransaccion($sql,1)) return false;
			
			foreach($sol as $key => $solicitud)
			{
				$sql	= "UPDATE	os_maestro ";
				$sql .= "SET		sw_estado = '9' ";
				$sql .= "WHERE	orden_servicio_id = ".$datos['orden_servicio_id']." ";
				$sql .= "AND		numero_orden_id = ".$solicitud['numero_orden_id']."; ";
				
				$sql .= "INSERT INTO os_anuladas ";
				$sql .= "	(	orden_servicio_id,";
				$sql .= " 	numero_orden_id,";
				$sql .= " 	observacion,";
				$sql .= " 	usuario_id,";
				$sql .= " 	fecha_registro,";
				$sql .= " 	os_anulada_justificicacion_id) ";
				$sql .= "VALUES( ";
				$sql .= "		 ".$datos['orden_servicio_id'].", ";
				$sql .= "		 ".$solicitud['numero_orden_id'].", ";
				$sql .= "		'".$datos['observacion']."', ";
				$sql .= "		 ".UserGetUID().",";
				$sql .= "		 NOW(), ";
				$sql .= "		 ".$datos['concepto']." ";
				$sql .= "		); ";

				$sql .= "UPDATE	hc_os_solicitudes ";
				$sql .= "SET 		sw_estado = '".$datos['anular_liberar']."' ";
				$sql .= "WHERE	hc_os_solicitud_id = ".$solicitud['hc_os_solicitud_id']."; ";

				if(!$rst = $this->ConexionTransaccion($sql,$solicitud['hc_os_solicitud_id'])) return false;

			}
			$this->dbconn->CommitTrans();
			
			return true;
		}
		/********************************************************************************
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*********************************************************************************/
		function ProcesarSqlConteo($consulta,$offset=null,$limite=null)
		{
			$this->offset = 0;
			$this->paginaActual = 1;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
			}
			else
			{
				$this->limit = $limite;
			}
			
			if($offset)
			{
				$this->paginaActual = intval($offset);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}		

			if(!$this->registros)
			{
				if(!$result = $this->ConexionBaseDatos($consulta))
					return false;
	
				if(!$result->EOF)
				{
					$this->conteo = $result->fields[0];
					$result->MoveNext();
				}
				$result->Close();
			}
			else
			{
				$this->conteo = $this->registros;
			}
			return true;
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
					//echo "<b class=\"label\">Trasaccion: $num - ".$this->frmError['MensajeError']."</b>";
					$this->dbconn->RollbackTrans();
					return false;
				}
				return $rst;
			}
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
			//$dbconn->debug = true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				//echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}
	}
?>