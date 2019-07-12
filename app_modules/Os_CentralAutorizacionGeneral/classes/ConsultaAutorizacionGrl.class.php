<?php
	/**************************************************************************************
	* $Id: ConsultaAutorizacionGrl.class.php,v 1.1 2007/04/16 20:46:41 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F  Manrique
	***************************************************************************************/
	class ConsultaAutorizacionGrl
	{
		function ConsultaAutorizacionGrl() {}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerListadoOrdenes($tid,$id)
		{
			$sql .= "SELECT	OS.sw_atencion_interna, ";
			$sql .= "				OS.orden_servicio_id, ";
			$sql .= "				OS.evento_soat, ";
			$sql .= "				OS.plan_id, ";
			$sql .= "				OS.observacion, ";
			$sql .= "				OS.ingreso, ";
			$sql .= "				OS.servicio AS servicio_id, ";
			$sql .= "				OS.departamento, ";
			$sql .= "				TO_CHAR(OS.fecha_vencimiento,'DD/MM/YYYY') AS vencimiento, ";
			$sql .= "				TO_CHAR(OS.fecha_activacion,'DD/MM/YYYY') AS activacion, ";
			$sql .= "				TO_CHAR(OS.fecha_refrendar,'DD/MM/YYYY') AS refrendar, ";
			$sql .= "				SE.descripcion AS servicio, ";
			$sql .= "				PL.plan_descripcion ";
			$sql .= "FROM		os_ordenes_servicios OS, ";
			$sql .= "				servicios SE, ";
			$sql .= "				planes PL ";
			$sql .= "WHERE	OS.servicio = SE.servicio ";
			$sql .= "AND		OS.sw_estado IN('1','2') ";
			$sql .= "AND		OS.fecha_vencimiento >= '".date('Y-m-d')."' ";
			$sql .= "AND		OS.fecha_activacion <= '".date('Y-m-d')."' ";
			$sql .= "AND		OS.paciente_id = '".$id."' ";
			$sql .= "AND		OS.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND		OS.plan_id = PL.plan_id ";
			$sql .= "ORDER BY SE.sw_prioridad,OS.fecha_vencimiento DESC, OS.fecha_registro ";
						
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[12]][$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerListadoCargosOrdenes($tid,$id)
		{
			$sql .= "SELECT	OS.orden_servicio_id, ";
			$sql .= "				OS.plan_id, ";
			$sql .= "				OM.cargo_cups, ";
			$sql .= "				OM.cantidad, ";
			$sql .= "				CS.descripcion AS descripcion_cups, ";
			$sql .= "				OC.tarifario_id, ";
			$sql .= "				OC.cargo, ";
			$sql .= "				OC.transaccion, ";
			$sql .= "				OC.os_maestro_cargos_id, ";
			$sql .= "				TD.descripcion AS descripcion_cargo, ";
			$sql .= "				TD.precio, ";
			$sql .= "				TA.descripcion AS tarifario_descripcion, ";
			$sql .= "				OM.hc_os_solicitud_id ";
			$sql .= "FROM		os_ordenes_servicios OS, ";
			$sql .= "				os_maestro OM, ";
			$sql .= "				os_maestro_cargos OC, ";
			$sql .= "				cups CS, ";
			$sql .= "				tarifarios_detalle TD, ";
			$sql .= "				tarifarios TA ";
			$sql .= "WHERE	OS.sw_estado IN('1','2') ";
			$sql .= "AND		OS.fecha_activacion <= '".date("Y-m-d")."' ";
			$sql .= "AND		OS.fecha_vencimiento >= '".date("Y-m-d")."' ";
			$sql .= "AND		OS.paciente_id = '".$id."' ";
			$sql .= "AND		OS.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND		OM.orden_servicio_id = OS.orden_servicio_id ";
			$sql .= "AND		CS.cargo = OM.cargo_cups ";
			$sql .= "AND		OC.numero_orden_id = OM.numero_orden_id ";
			$sql .= "AND		OC.tarifario_id = TD.tarifario_id ";
			$sql .= "AND		OC.cargo = TD.cargo ";
			$sql .= "AND		TA.tarifario_id = TD.tarifario_id ";
				
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerConceptosAnulacionOS()
		{
			$sql  = "SELECT os_anulada_justificicacion_id AS oaj_id, ";
			$sql .= "	 			descripcion ";
			$sql .= "FROM		os_anuladas_justificacion ";
			
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
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerListadoOrdenesXRefrendar($tid,$id)
		{
			$sql .= "SELECT	OS.sw_atencion_interna, ";
			$sql .= "				OS.orden_servicio_id, ";
			$sql .= "				OS.evento_soat, ";
			$sql .= "				OS.plan_id, ";
			$sql .= "				OS.observacion, ";
			$sql .= "				OS.ingreso, ";
			$sql .= "				OS.servicio AS servicio_id, ";
			$sql .= "				OS.departamento, ";
			$sql .= "				TO_CHAR(OS.fecha_vencimiento,'DD/MM/YYYY') AS vencimiento, ";
			$sql .= "				TO_CHAR(OS.fecha_activacion,'DD/MM/YYYY') AS activacion, ";
			$sql .= "				TO_CHAR(OS.fecha_refrendar,'DD/MM/YYYY') AS refrendar, ";
			$sql .= "				SE.descripcion AS servicio, ";
			$sql .= "				PL.plan_descripcion ";
			$sql .= "FROM		os_ordenes_servicios OS, ";
			$sql .= "				servicios SE, ";
			$sql .= "				planes PL ";
			$sql .= "WHERE	OS.servicio = SE.servicio ";
			$sql .= "AND		OS.sw_estado IN('1','2') ";
			$sql .= "AND		OS.fecha_refrendar >= '".date('Y-m-d')."' ";
			$sql .= "AND		OS.fecha_vencimiento < '".date('Y-m-d')."' ";
			$sql .= "AND		OS.paciente_id = '".$id."' ";
			$sql .= "AND		OS.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND		OS.plan_id = PL.plan_id ";
			$sql .= "ORDER BY SE.sw_prioridad,OS.fecha_vencimiento DESC, OS.fecha_registro ";
						
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[12]][$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerListadoCargosOrdenesXRefrendar($tid,$id)
		{
			$sql .= "SELECT	OS.orden_servicio_id, ";
			$sql .= "				OS.plan_id, ";
			$sql .= "				OM.cargo_cups, ";
			$sql .= "				OM.cantidad, ";
			$sql .= "				CS.descripcion AS descripcion_cups, ";
			$sql .= "				OC.tarifario_id, ";
			$sql .= "				OC.cargo, ";
			$sql .= "				OC.transaccion, ";
			$sql .= "				OC.os_maestro_cargos_id, ";
			$sql .= "				TD.descripcion AS descripcion_cargo, ";
			$sql .= "				TD.precio, ";
			$sql .= "				TA.descripcion AS tarifario_descripcion, ";
			$sql .= "				OM.hc_os_solicitud_id ";
			$sql .= "FROM		os_ordenes_servicios OS, ";
			$sql .= "				os_maestro OM, ";
			$sql .= "				os_maestro_cargos OC, ";
			$sql .= "				cups CS, ";
			$sql .= "				tarifarios_detalle TD, ";
			$sql .= "				tarifarios TA ";
			$sql .= "WHERE	OS.sw_estado IN('1','2') ";
			$sql .= "AND		OS.fecha_vencimiento < '".date("Y-m-d")."' ";
			$sql .= "AND		OS.fecha_refrendar >= '".date("Y-m-d")."' ";
			$sql .= "AND		OS.paciente_id = '".$id."' ";
			$sql .= "AND		OS.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND		OM.orden_servicio_id = OS.orden_servicio_id ";
			$sql .= "AND		CS.cargo = OM.cargo_cups ";
			$sql .= "AND		OC.numero_orden_id = OM.numero_orden_id ";
			$sql .= "AND		OC.tarifario_id = TD.tarifario_id ";
			$sql .= "AND		OC.cargo = TD.cargo ";
			$sql .= "AND		TA.tarifario_id = TD.tarifario_id ";
				
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerListadoOrdenesVencidas($tid,$id,$dat)
		{
			if(!$dat)
			{
				$sql .= "SELECT	OS.sw_atencion_interna, ";
				$sql .= "				OS.orden_servicio_id, ";
				$sql .= "				OS.evento_soat, ";
				$sql .= "				OS.plan_id, ";
				$sql .= "				OS.observacion, ";
				$sql .= "				OS.ingreso, ";
				$sql .= "				OS.servicio AS servicio_id, ";
				$sql .= "				OS.departamento, ";
				$sql .= "				TO_CHAR(OS.fecha_vencimiento,'DD/MM/YYYY') AS vencimiento, ";
				$sql .= "				TO_CHAR(OS.fecha_activacion,'DD/MM/YYYY') AS activacion, ";
				$sql .= "				TO_CHAR(OS.fecha_refrendar,'DD/MM/YYYY') AS refrendar, ";
				$sql .= "				SE.descripcion AS servicio, ";
				$sql .= "				PL.plan_descripcion ";
			}
			else
			{
				$sql .= "SELECT	COUNT(*) ";
			}
			$sql .= "FROM		os_ordenes_servicios OS, ";
			$sql .= "				servicios SE, ";
			$sql .= "				planes PL ";
			$sql .= "WHERE	OS.servicio = SE.servicio ";
			$sql .= "AND		OS.sw_estado IN('1','2') ";
			$sql .= "AND		OS.fecha_refrendar < '".date('Y-m-d')."' ";
			$sql .= "AND		OS.fecha_vencimiento < '".date('Y-m-d')."' ";
			$sql .= "AND		OS.paciente_id = '".$id."' ";
			$sql .= "AND		OS.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND		OS.plan_id = PL.plan_id ";
			
			if(!$dat)	$sql .= "ORDER BY OS.orden_servicio_id DESC";
						
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				if(!$dat)
					$datos[$rst->fields[12]][$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				else
					$datos[] = $rst->fields[0];
				$rst->MoveNext();
			}
			krsort($datos);
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerListadoCargosOrdenesVencidas($tid,$id)
		{
			$sql .= "SELECT	OS.orden_servicio_id, ";
			$sql .= "				OS.plan_id, ";
			$sql .= "				OM.cargo_cups, ";
			$sql .= "				OM.cantidad, ";
			$sql .= "				CS.descripcion AS descripcion_cups, ";
			$sql .= "				OC.tarifario_id, ";
			$sql .= "				OC.cargo, ";
			$sql .= "				OC.transaccion, ";
			$sql .= "				OC.os_maestro_cargos_id, ";
			$sql .= "				TD.descripcion AS descripcion_cargo, ";
			$sql .= "				TD.precio, ";
			$sql .= "				TA.descripcion AS tarifario_descripcion, ";
			$sql .= "				OM.hc_os_solicitud_id ";
			$sql .= "FROM		os_ordenes_servicios OS, ";
			$sql .= "				os_maestro OM, ";
			$sql .= "				os_maestro_cargos OC, ";
			$sql .= "				cups CS, ";
			$sql .= "				tarifarios_detalle TD, ";
			$sql .= "				tarifarios TA ";
			$sql .= "WHERE	OS.sw_estado IN('1','2') ";
			$sql .= "AND		OS.fecha_vencimiento < '".date("Y-m-d")."' ";
			$sql .= "AND		OS.fecha_refrendar < '".date("Y-m-d")."' ";
			$sql .= "AND		OS.paciente_id = '".$id."' ";
			$sql .= "AND		OS.tipo_id_paciente = '".$tid."' ";
			$sql .= "AND		OM.orden_servicio_id = OS.orden_servicio_id ";
			$sql .= "AND		CS.cargo = OM.cargo_cups ";
			$sql .= "AND		OC.numero_orden_id = OM.numero_orden_id ";
			$sql .= "AND		OC.tarifario_id = TD.tarifario_id ";
			$sql .= "AND		OC.cargo = TD.cargo ";
			$sql .= "AND		TA.tarifario_id = TD.tarifario_id ";
				
			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/***********************************************************************************
	  * Funcion donde se buscan los eventos soat del paciente
	  * @params tipo del documento
	  *					documento del paciente
	  *
	  * @return array
	  ************************************************************************************/
	  function ObtenerEventoSoatPaciente($tipo_documento_id,$documento_id, $plan_id)
		{
			$sql  = "SELECT	DISTINCT A.evento, ";
			$sql .= "				A.poliza, ";
			$sql .= "				A.condicion_accidentado, ";
			$sql .= "				A.saldo, ";
			$sql .= "				A.codigo_eps, ";
			$sql .= "				A.accidente_id, ";
			$sql .= "				A.asegurado, ";
			$sql .= "				A.empresa_id, ";
			$sql .= "				C.nombre_tercero, ";
			$sql .= "				E.razon_social, "; 
			$sql .= "				TO_CHAR(D.fecha_accidente,'DD/MM/YYYY') AS fecha_accidente, ";
			$sql .= "				TO_CHAR(D.fecha_accidente,'HH:MI AM') AS hora_accidente ";
			$sql .= "FROM 	soat_eventos AS A ";
			$sql .= "				LEFT JOIN soat_accidente AS D ";
			$sql .= "				ON (A.accidente_id=D.accidente_id), ";
			$sql .= "				soat_polizas AS B, ";
			$sql .= "				terceros AS C, ";
			$sql .= "				empresas AS E ";
			$sql .= "WHERE	A.tipo_id_paciente = '".$tipo_documento_id."' ";
			$sql .= "AND		A.paciente_id = '".$documento_id."' ";
			$sql .= "AND		A.poliza = B.poliza ";
			$sql .= "AND		B.tipo_id_tercero = C.tipo_id_tercero ";
			$sql .= "AND		B.tercero_id = C.tercero_id ";
			$sql .= "AND		A.empresa_id = E.empresa_id ";
			$sql .= "ORDER BY poliza;";

			if(!$rst = $this->ConexionBaseDatos($sql)) return true;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			return $datos;
		}
		/**********************************************************************************
		* 
		* @return rst 
		************************************************************************************/
		function ObtenerPermisos($usuario)
		{
			$sql  = "SELECT	EM.razon_social,";
			$sql .= "				EM.empresa_id, ";
			$sql .= "				UA.usuario_id, ";
			$sql .= "				UA.sw_todos_planes ";
			$sql .= "FROM		empresas EM,";
			$sql .= "				userpermisos_centro_autorizacion UA ";
			$sql .= "WHERE	UA.usuario_id = ".$usuario." ";
			$sql .= "AND		UA.empresa_id = EM.empresa_id";
		
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
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
	}
?>