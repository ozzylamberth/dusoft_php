<?php
  /******************************************************************************
  * $Id: GlosaDetalle.class.php,v 1.1 2009/09/02 13:02:28 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.1 $ 
	* 
	* @autor Hugo F  Manrique 
  ********************************************************************************/
	class GlosaDetalle
	{
		function GlosaDetalle(){}
		/***************************************************************************************************
		* Funcion donde se toma de la base de datos las cuentas con la descripcion de las mismas 
		* 
		* @return array datos de las cuentas
		****************************************************************************************************/
		function ObtenerInformacionDetalleCuentas($datos,$limite)
		{	
			$sql  = "SELECT C.numerodecuenta,";
			$sql .= "				P.plan_descripcion,";
			$sql .= "				I.ingreso,";
			$sql .= "				PA.tipo_id_paciente||' '||PA.paciente_id AS identificacion,";
			$sql .= "				PA.primer_nombre||' '||PA.segundo_nombre||' '||PA.primer_apellido||' '||PA.segundo_apellido AS paciente,";
			$sql .= "				C.valor_cuota_paciente,";
			$sql .= "				C.valor_cuota_moderadora,";
			$sql .= "				C.valor_total_empresa, ";
			$sql .= "				C.plan_id, ";
			$sql .= "				G.sw_glosa_total_cuenta, ";
			$sql .= "				COALESCE(G.sw_estado,'0') AS estado, ";
			$sql .= "	  		G.motivo_glosa_id, ";
			$sql .= "				G.observacion, ";
			$sql .= "				G.valor_glosa_copago, ";
			$sql .= "				G.valor_glosa_cuota_moderadora, ";
			$sql .= "				G.glosa_detalle_cuenta_id, ";
			$sql .= "				G.motivo_glosa_id, ";
			$sql .= "				G.mayor_valor, ";
			$sql .= "				G.menor_valor, ";
			$sql .= "				G.codigo_concepto_general, ";
			$sql .= "				G.codigo_concepto_especifico, ";
			$sql .= "				G.auditor_id ";
			$sql .= "FROM		ingresos I,";
			$sql .= "				fac_facturas_cuentas FF,";
			$sql .= "				planes P,pacientes PA, ";
			$sql .= "				fac_facturas F,";
			$sql .= "				cuentas C ";
			$sql .= "				LEFT JOIN glosas_detalle_cuentas G ";
			$sql .= "				ON(	C.numerodecuenta = G.numerodecuenta AND ";
			$sql .= "						G.sw_estado <> '0'::bpchar AND ";
			$sql .= "		   			G.glosa_id = ".$datos['glosa_id']." ) ";
			$sql .= "WHERE 	F.empresa_id = '".$datos['empresa_id']."' ";
			$sql .= "AND 		F.prefijo = '".$datos['prefijo']."' ";
			$sql .= "AND		F.factura_fiscal = ".$datos['factura_fiscal']." ";
			$sql .= "AND 		FF.prefijo = F.prefijo ";
			$sql .= "AND 		FF.factura_fiscal = F.factura_fiscal ";
			$sql .= "AND 		FF.empresa_id = F.empresa_id ";
			$sql .= "AND 		C.numerodecuenta = FF.numerodecuenta ";
			$sql .= "AND 		C.ingreso = I.ingreso ";
			$sql .= "AND 		I.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "AND 		I.paciente_id = PA.paciente_id ";
			$sql .= "AND 		C.plan_id = P.plan_id ";
			$sql .= "AND 		C.estado != '5'::bpchar ";
			
			if($datos['numerodecuenta'])
				$sql .= "AND C.numerodecuenta = ".$datos['numerodecuenta'];
			
			if(!$datos['numerodecuenta'])
			{
				$this->ProcesarSqlConteo('',$datos['offset'],$datos['cantidad'],$limite);
				$sql .= "ORDER BY 1 ";
				$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			}
			
			if(!$rst= $this->ConexionBaseDatos($sql))	return false;
				
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
		
			$rst->Close();
			return $datos;
		}
		/****************************************************************************************
		* Funcion donde se obtienen los cargos que estan glosados y los que no pettenecientes a 
		* una cuenta 
		* 
		* @return array  
		*****************************************************************************************/
		function ObtenerCargosCuentas($datos,$detalle_id = null)
		{
      //$this->debug=true;
			$sql  = "SELECT	C.transaccion, ";
			$sql .= "				C.cargo_cups, ";
			$sql .= "				C.valor_cubierto,  ";
			$sql .= "				C.valor_cargo, ";
			$sql .= "				C.codigo_agrupamiento_id AS agrupado, ";
			$sql .= "				TO_CHAR(C.fecha_registro,'DD/MM/YYYY') AS registro,  ";
			$sql .= "				T.tarifario_id,  ";
			$sql .= "				T.descripcion,"; 
			$sql .= "				GC.valor_glosa, ";			
			$sql .= "				GC.sw_estado, ";			
			$sql .= "				GC.glosa_detalle_cargo_id, ";
			$sql .= "				GC.auditor_id, ";	
			$sql .= "				GC.motivo_glosa_id, ";				
			$sql .= "				GC.glosa_detalle_cuenta_id, ";				
			$sql .= "				GC.observacion, ";				
			$sql .= "				GC.codigo_concepto_general, ";
			$sql .= "				GC.codigo_concepto_especifico, ";
			$sql .= "				GG.descripcion_concepto_general, ";
			$sql .= "				CE.descripcion_concepto_especifico, ";
			$sql .= "				GM.motivo_glosa_descripcion ";
			$sql .= "FROM		tarifarios_detalle T, ";
			$sql .= "				cuentas_detalle C ";
			$sql .= "				LEFT JOIN glosas_detalle_cargos GC ";
			$sql .= "				ON(	C.transaccion = GC.transaccion AND ";
			$sql .= "		   			GC.sw_estado NOT IN ('0','3') AND ";
			$sql .= "  	   			GC.glosa_id = ".$datos['glosa_id'].") ";
			$sql .= "				LEFT JOIN glosas_motivos GM ";
			$sql .= "				ON (GM.motivo_glosa_id = GC.motivo_glosa_id ) ";
			$sql .= "				LEFT JOIN glosas_concepto_general GG ";
			$sql .= "			ON(GG.codigo_concepto_general = GC.codigo_concepto_general) ";
			$sql .= "				LEFT JOIN glosas_concepto_especifico CE ";
			$sql .= "			ON(CE.codigo_concepto_especifico = GC.codigo_concepto_especifico) ";
			$sql .= "WHERE 	C.numerodecuenta = ".$datos['numerodecuenta']." ";
			$sql .= "AND 		C.facturado = '1'::bpchar ";
			$sql .= "AND 		C.empresa_id = '".$datos['empresa_id']."' ";
			$sql .= "AND 		T.cargo = C.cargo ";
			$sql .= "AND 		T.tarifario_id = C.tarifario_id ";
			$sql .= "AND 		C.valor_cargo >= 0 ";
			$sql .= "AND		C.tarifario_id <> 'SYS' ";
			if($detalle_id)
				$sql .= "AND	GC.glosa_detalle_cargo_id = ".$detalle_id." ";
				
			$sql .= "ORDER BY agrupado,C.transaccion ";
	
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]]  = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/****************************************************************************************
		* Funcion donde se muestran los insumos que han sidoglosados y los que no 
		* 
		* @return array 
		****************************************************************************************/
		function ObtenerInsumosCuenta($datos,$detalle_id = null)
		{
			$sql  = "SELECT	ID.codigo_producto, "; 
			$sql .= "				SUM(CASE WHEN C.cargo ='DIMD' THEN C.cantidad*-1 ";
      $sql .= "                ELSE C.cantidad END ) AS cantidad, ";
      $sql .= "				SUM(C.valor_cubierto) AS valor_cubierto,";
      $sql .= "				SUM(C.valor_cargo) AS valor_cargo,";
			$sql .= "				ID.descripcion, ";
			$sql .= "				GI.valor_glosa, ";
			$sql .= "				GI.sw_estado, ";			
			$sql .= "				GI.glosa_detalle_inventario_id, ";	
			$sql .= "				GI.auditor_id, ";	
			$sql .= "				GI.motivo_glosa_id, ";	
			$sql .= "				GI.glosa_detalle_cuenta_id, ";	
			$sql .= "				GI.observacion, ";	
			$sql .= "				COUNT(*) AS transaccion, ";
			$sql .= "				GI.codigo_concepto_general, ";
			$sql .= "				GI.codigo_concepto_especifico, ";
			$sql .= "				GG.descripcion_concepto_general, ";
			$sql .= "				CE.descripcion_concepto_especifico, ";
			$sql .= "				GM.motivo_glosa_descripcion ";
			$sql .= "FROM		bodegas_documentos_d BD, ";
			$sql .= "				cuentas_detalle C , ";
			$sql .= "				inventarios_productos ID ";
			$sql .= "				LEFT JOIN glosas_detalle_inventarios GI ";
			$sql .= "			ON(ID.codigo_producto = GI.codigo_producto ";
			$sql .= "		   		AND GI.sw_estado NOT IN ('0','3') ";
			$sql .= "		   		AND GI.glosa_id = ".$datos['glosa_id'].") ";
			$sql .= "				LEFT JOIN glosas_motivos GM ";
			$sql .= "				ON (GM.motivo_glosa_id = GI.motivo_glosa_id ) ";
			$sql .= "				LEFT JOIN glosas_concepto_general GG ";
			$sql .= "			ON(GG.codigo_concepto_general = GI.codigo_concepto_general) ";
			$sql .= "				LEFT JOIN glosas_concepto_especifico CE ";
			$sql .= "			ON(CE.codigo_concepto_especifico = GI.codigo_concepto_especifico) ";
			$sql .= "WHERE	C.numerodecuenta = ".$datos['numerodecuenta']." ";
			$sql .= "AND		C.facturado = '1'::bpchar ";
			$sql .= "AND		C.empresa_id = '".$datos['empresa_id']."' ";
			$sql .= "AND		C.consecutivo = BD.consecutivo ";
			$sql .= "AND		BD.codigo_producto = ID.codigo_producto ";
			if($detalle_id)
				$sql .= "AND	GI.glosa_detalle_inventario_id = ".$detalle_id." ";
			$sql .= "GROUP BY 1,5,6,7,8,9,10,11,12,14,15,16,17,18 ";
			$sql .= "ORDER BY ID.codigo_producto ";
					
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
      $i = 1;
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]]  = $rst->GetRowAssoc($ToUpper = false);
				$datos[$rst->fields[0]]['transaccion'] = $i++;
        $rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/****************************************************************************************
		* Funcion donde se consulta la descripcion de los actos quirurgicos cargados a la cuenta
		* 
		* @params	string	$transaccion	Transaccion de la cuenta que tiene el acto quirurgico
		* @params	String	$agrupado			Indica el codigo de agrupacion del acto quirurgico
		* @return	string	Descripcion del acto quirurgico
		*****************************************************************************************/
		function ObtenerActoQuirurgico($transaccion,$agrupado)
		{
			if($agrupado)
			{
				$sql .= "SELECT CP.descripcion ";
				$sql .= "FROM		cups CP, ";
				$sql .= "				cuentas_cargos_qx_procedimientos CQ, ";
				$sql .= "				cuentas_liquidaciones_qx CL, ";
				$sql .= "				cuentas_codigos_agrupamiento CA ";
				$sql .= "WHERE	CA.codigo_agrupamiento_id = ".$agrupado." ";
				$sql .= "AND		CA.cuenta_liquidacion_qx_id = CL.cuenta_liquidacion_qx_id ";
				$sql .= "AND		CL.cargo_principal = CP.cargo ";
				$sql .= "AND		CL.cuenta_liquidacion_qx_id = CQ.cuenta_liquidacion_qx_id ";
				$sql .= "AND		CQ.transaccion = ".$transaccion." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
				
				if (!$rst->EOF)
				{
					$descripcion = $rst->fields[0];
			  }
				$rst->Close();
			}
			return $descripcion;
		}
		/********************************************************************************** 
		* Funcion donde se toma de la base de datos la informacion de los motivos
		* 
		* @return array datos de los motivos de cancelacion de la glosa 
		************************************************************************************/
		function ObtenerMotivosGlosas()
		{
			$sql  = "SELECT motivo_glosa_id, ";
			$sql .= "				motivo_glosa_descripcion, ";
			$sql .= "				glosa_tipo_clasificacion_id ";
			$sql .= "FROM 	glosas_motivos ";
			$sql .= "WHERE 	sw_motivo_factura = '1' ";
			$sql .= "ORDER BY 2";
					
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		return $datos;
		}
		/****************************************************************************************
		* Funcion donde se obtienen los valores de los cargos o insumos que poseen una o varias 
		* notas credito 
		* 
		* @return int valor de la aceptado 
		*****************************************************************************************/
		function ObtenerCargoConNC($glosaid,$codigo,$numero,$opc)
		{
			switch($opc)
			{
				case '0':
					$sql  = "SELECT SUM(COALESCE(GC.valor_aceptado,0)) ";
					$sql .= "FROM		glosas_detalle_cargos GC, ";
					$sql .= " 			notas_credito_glosas_detalle_cargos NC ";
					$sql .= "WHERE	GC.glosa_id = ".$glosaid." ";
					$sql .= "AND		GC.transaccion = ".$codigo." ";
					$sql .= "AND		GC.glosa_id = NC.glosa_id ";
					$sql .= "AND		GC.glosa_detalle_cargo_id = NC.glosa_detalle_cargo_id ";
					$sql .= "AND		GC.sw_estado = '3' ";
				break;
				case '1':
					$sql  = "SELECT SUM(COALESCE(GI.valor_aceptado,0)) ";
					$sql .= "FROM		glosas_detalle_inventarios GI, ";
					$sql .= " 			notas_credito_glosas_detalle_inventarios NI ";
					$sql .= "WHERE	GI.glosa_id = ".$glosaid." ";
					$sql .= "AND		GI.codigo_producto = '".$codigo."' ";
					$sql .= "AND		GI.glosa_id = NI.glosa_id ";
					$sql .= "AND		GI.glosa_detalle_inventario_id = NI.glosa_detalle_inventario_id ";
					$sql .= "AND		GI.sw_estado = '3' ";
				break;
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			$i = 0;
			if (!$rst->EOF)
			{
				$valor = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();
			return $valor;
		}
		/********************************************************************************** 
		* Funcion donde se averiguan los auditores internos asociados al plan de la factura 
		* 
		* @return array datos de las clasificaciones de las glosas 
		***********************************************************************************/
		function ObtenerAuditoresInternos($auditor = null)
		{
			$sql  = "SELECT U.usuario_id,U.nombre ";
			$sql .= "FROM 	system_usuarios U, auditores_internos A ";
			$sql .= "WHERE 	U.usuario_id = A.usuario_id ";
			$sql .= "AND 		A.estado = '1' ";
			
			if($auditor != null)	$sql .= "AND  	A.usuario_id <> ".$auditor." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $datos;
		}
		/********************************************************************************** 
		* Funcion donde se averiguan los auditores internos asociados al plan de la factura 
		* 
		* @return array datos de las clasificaciones de las glosas 
		***********************************************************************************/
		function ActualizarDetalle($datos)
		{
			$sql = "";
      if(!$datos['auditor_id']) $datos['auditor_id'] = "NULL";
      if(!$datos['motivo_id']) $datos['motivo_id'] = '-1';
			switch($datos['tipo'])
			{
				case 'C':
					$sql  = "UPDATE glosas_detalle_cargos ";
					$sql .= "SET		motivo_glosa_id = '".$datos['motivo_id']."',";
					$sql .= "	 			observacion = '".$datos['observacion']."',";
					$sql .= "	 			valor_glosa = ".$datos['valor_glosa'].",";
					$sql .= "	 			auditor_id = ".$datos['auditor_id'].", ";
					$sql .= "	 			usuario_id = ".UserGetUID().",";
					$sql .= "	 			codigo_concepto_general = '".$datos['concepto_general']."', ";
					$sql .= "	 			codigo_concepto_especifico = '".$datos['concepto_especifico']."',";
					$sql .= "	 			fecha_registro = NOW() ";
					$sql .= "WHERE	glosa_id = ".$datos['glosa_id']." ";
					$sql .= "AND		glosa_detalle_cargo_id  = ".$datos['detalle_id']."; ";
					
					$sql .= "INSERT INTO glosas_auditoria_modificaciones_cargos ";
					$sql .= "			(	observacion,";
					$sql .= "		 		usuario_id,";
					$sql .= "		 		fecha_registro,";
					$sql .= "		 		glosa_detalle_cargo_id) ";
					$sql .= "VALUES('Se realizó una modificación sobre el cargo perteneciente a la cuenta Nº ".$_REQUEST['numero_cuenta']."',";
					$sql .= "				".UserGetUID().",";
					$sql .= "		 		NOW(),";
					$sql .= "				".$datos['detalle_id'].");";
				break;
				case 'I':
					$sql  = "UPDATE glosas_detalle_inventarios ";
					$sql .= "SET		motivo_glosa_id = '".$datos['motivo_id']."',";
					$sql .= "	 			observacion = '".$datos['observacion']."',";
					$sql .= "	 			valor_glosa = ".$datos['valor_glosa'].",";
					$sql .= "	 			auditor_id = ".$datos['auditor_id'].", ";
					$sql .= "	 			usuario_id = ".UserGetUID().",";
					$sql .= "	 			codigo_concepto_general = '".$datos['concepto_general']."', ";
					$sql .= "	 			codigo_concepto_especifico = '".$datos['concepto_especifico']."',";
					$sql .= "	 			fecha_registro = NOW() ";
					$sql .= "WHERE	glosa_id = ".$datos['glosa_id']." ";
					$sql .= "AND		glosa_detalle_inventario_id  = ".$datos['detalle_id']."; ";
					$sql .= "INSERT INTO glosas_auditoria_modificaciones_inventarios ";
					$sql .= "				(	observacion,";
					$sql .= "		 			usuario_id,";
					$sql .= "		 			fecha_registro, ";
					$sql .= "		 			glosa_detalle_inventario_id) ";
					$sql .= "VALUES('Se realizó una modificación sobre el medicamento perteneciente a la cuenta Nº ".$_REQUEST['numero_cuenta']."',";
					$sql .= "				".UserGetUID().",";
					$sql .= "		  	NOW(),";
					$sql .= "				".$datos['detalle_id']."); ";
				break;
			}
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
			return true;
		}
		/*****************************************************************************************
		* Funcion donde se cambia el  estado de una glosa a anulada y se inserta en la tabla de 
		* glosas_auditoris_anulaciones_cargos o glosas_auditoris_anulaciones_inventarios,
		* segun sea el caso y el motivo de la anulacion de la glosa 
		* 
		* @return boolean 
		******************************************************************************************/
		function AnularGlosaCargoInsumo($datos)
		{
			$sql = "";
			switch($datos['tipo'])
			{
				case 'C':
					$sql .= "UPDATE	glosas_detalle_cargos ";
					$sql .= "SET		sw_estado = '0' ";
					$sql .= "WHERE	glosa_id = ".$datos['glosa_id']." ";
					$sql .= "AND		glosa_detalle_cargo_id = ".$datos['detalle_id']."; ";
					$sql .= "INSERT INTO glosas_auditoria_modificaciones_cargos (";
					$sql .= "				glosa_detalle_cargo_id,";
					$sql .= "				observacion,";	
					$sql .= "				usuario_id,";
					$sql .= "				fecha_registro) ";
					$sql .= "VALUES (".$datos['detalle_id'].",";
					$sql .= "				'".$datos['observacion']."',";
					$sql .= "		 		 ".UserGetUID().",";
					$sql .= "		   	 NOW()) ;";
				break;
				case 'I':
					$sql .= "UPDATE	glosas_detalle_inventarios ";
					$sql .= "SET		sw_estado = '0' ";
					$sql .= "WHERE	glosa_id = ".$datos['glosa_id']." ";
					$sql .= "AND		glosa_detalle_inventario_id = ".$datos['detalle_id']."; ";
					$sql .= "INSERT INTO glosas_auditoria_modificaciones_inventarios (";
					$sql .= "				glosa_detalle_inventario_id,";
					$sql .= "				observacion,";	
					$sql .= "				usuario_id,";
					$sql .= "				fecha_registro) ";
					$sql .= "VALUES (".$datos['detalle_id'].",";
					$sql .= "				'".$datos['observacion']."',";
					$sql .= "		 		 ".UserGetUID().",";
					$sql .= "		   	 NOW()) ;";
				break;
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;

			$sql  = "UPDATE	glosas_detalle_cuentas  ";
			$sql .= "SET		sw_estado = '0'  ";
			$sql .= "WHERE	motivo_glosa_id IS NULL  ";
			$sql .= "AND		glosa_id = ".$datos['glosa_id']."  ";
			$sql .= "AND		glosa_detalle_cuenta_id = ".$datos['detalle_cuenta']."  ";
			$sql .= "AND		glosa_detalle_cuenta_id NOT IN ( ";
			$sql .= "		SELECT 	glosa_detalle_cuenta_id ";
			$sql .= "		FROM  	glosas_detalle_inventarios ";
			$sql .= "		WHERE 	glosa_detalle_cuenta_id = ".$datos['detalle_cuenta']." ";
			$sql .= "		AND			sw_estado <> '0'::bpchar ";
			$sql .= "		UNION DISTINCT ";
			$sql .= "		SELECT 	glosa_detalle_cuenta_id ";
			$sql .= "		FROM  	glosas_detalle_cargos ";
			$sql .= "		WHERE 	glosa_detalle_cuenta_id = ".$datos['detalle_cuenta']." ";
			$sql .= "		AND			sw_estado <> '0'::bpchar ";
			$sql .= "	);";
			
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
			return true;
		}
		/*****************************************************************************************
		* Funcion donde se cambia el  estado de una glosa a anulada y se inserta en la tabla de 
		* glosas_auditoris_anulaciones el motivo de la anulacion de la glosa 
		* 
		* @return boolean 
		******************************************************************************************/
		function AnularGlosaCuenta($datos)
		{
			$sql  = "INSERT INTO glosas_auditoria_anulaciones(";
			$sql .= "			glosa_id,";
			$sql .= "			observacion,";
			$sql .= "			fecha_registro ,";
			$sql .= "			glosa_detalle_cuenta_id ,";
			$sql .= "			usuario_id) ";
			$sql .= "VALUES (".$datos['glosa_id'].", ";
			$sql .= "			'".$datos['observacion']."',";
			$sql .= "		   NOW(),";
			$sql .= "		   ".$datos['detalle_cuenta'].",";
			$sql .= "		 	 ".UserGetUID().");";
			
			$sql .= "UPDATE glosas_detalle_cuentas ";
			$sql .= "SET 		sw_estado = '0' ";
			$sql .= "WHERE 	glosa_id = ".$datos['glosa_id']." ";
			$sql .= "AND 		glosa_detalle_cuenta_id = ".$datos['detalle_cuenta']."; ";
			
			$sql .= "UPDATE glosas_detalle_cargos ";
			$sql .= "SET 		sw_estado = '0' ";
			$sql .= "WHERE  glosa_id = ".$datos['glosa_id']." ";
			$sql .= "AND 		glosa_detalle_cuenta_id = ".$datos['detalle_cuenta']."; ";
			
			$sql .= "UPDATE glosas_detalle_inventarios ";
			$sql .= "SET 		sw_estado = '0' ";
			$sql .= "WHERE  glosa_id = ".$datos['glosa_id']." ";
			$sql .= "AND 		glosa_detalle_cuenta_id = ".$datos['detalle_cuenta']."; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
			return true;
		}
		/********************************************************************************** 
		* Funcion donde se cambia el  estado de una glosa a anulada y se inserta en la 
		* tabla de glosas_auditoris_anulaciones el motivo de la anulacion de la glosa 
		* 
		* @return boolean 
		************************************************************************************/
		function AnularGlosa($datos)
		{
			$sql  = "INSERT INTO glosas_auditoria_anulaciones( ";
			$sql .= "			glosa_id,";
			$sql .= "			observacion,";
			$sql .= "			fecha_registro ,";
			$sql .= "			usuario_id) ";
			$sql .= "VALUES (".$datos['glosa_id'].", ";
			$sql .= "				'".$datos['observacion']."',";
			$sql .= "		   	NOW(),";
			$sql .= "		 		".UserGetUID().");";
			$sql .= "UPDATE glosas ";
			$sql .= "SET 		sw_estado = '0' ";
			$sql .= "WHERE 	glosa_id = ".$datos['glosa_id']."; ";
			
			$sql .= "UPDATE glosas_detalle_cuentas ";
			$sql .= "SET 		sw_estado = '0' ";
			$sql .= "WHERE 	glosa_id = ".$datos['glosa_id']." ; ";
			
			$sql .= "UPDATE glosas_detalle_cargos ";
			$sql .= "SET 		sw_estado = '0' ";
			$sql .= "WHERE  glosa_id = ".$datos['glosa_id']." ; ";
			
			$sql .= "UPDATE glosas_detalle_inventarios ";
			$sql .= "SET 		sw_estado = '0' ";
			$sql .= "WHERE  glosa_id = ".$datos['glosa_id']." ; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
			return true;
		}
		/******************************************************************************* 
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		********************************************************************************/
		function ProcesarSqlConteo($sqlCont,$offset=null,$cant=null,$limite=null)
		{
			$this->paginaActual = 1;
			$this->offset = 0;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
				if(!$this->limit)	$this->limit = 20;
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
			if(!$cant)
			{
				if(!$result = $this->ConexionBaseDatos($sqlCont))
					return false;
	
				if(!$result->EOF)
				{
					$this->conteo = $result->fields[0];
					$result->MoveNext();
					$result->Close();
				}
			}
			else
			{
				$this->conteo = $cant;
			}
			return true;
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
				echo "<b class=\"label\">".$this->frmError['MensajeError']."</b><br>".$sql;
				return false;
			}
			return $rst;
		}
	}
?>