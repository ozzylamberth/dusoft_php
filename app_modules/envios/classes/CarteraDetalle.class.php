<?php
  /******************************************************************************
  * $Id: CarteraDetalle.class.php,v 1.3 2007/07/03 21:03:39 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.3 $ 
	* 
	* @autor Hugo F  Manrique 
  ********************************************************************************/
	class CarteraDetalle
	{
		var $dn_periodos = array();
		function CarteraDetalle()
		{
			$this->dn_periodos['A7'] = "MAS DE 180 DÍAS";
			$this->dn_periodos['A6'] = "A 180 DÍAS";
			$this->dn_periodos['A5'] = "A 150 DÍAS";
			$this->dn_periodos['A4'] = "A 120 DÍAS";
			$this->dn_periodos['A3'] = "A 90 DÍAS";
			$this->dn_periodos['A2'] = "A 60 DÍAS";
			$this->dn_periodos['A1'] = "A 30 DÍAS";
			$this->dn_periodos['B7'] = "MAS DE 180 DÍAS";
			$this->dn_periodos['B6'] = "A 180 DÍAS";
			$this->dn_periodos['B5'] = "A 150 DÍAS";
			$this->dn_periodos['B4'] = "A 120 DÍAS";
			$this->dn_periodos['B3'] = "A 90 DÍAS";
			$this->dn_periodos['B2'] = "A 60 DÍAS";
			$this->dn_periodos['B1'] = "A 30 DÍAS";	
			$this->dn_periodos['B0'] = "ESTE MES";	
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerEnviosCliente($datos,$empresa)
		{
			$sql  = "SELECT	EN.envio_id,";
			$sql .= "				'ENVIOS DEL SISTEMA' AS titulo, ";
			$sql .= "				'SIIS' AS sistema, ";
			$sql .= "				TO_CHAR(EN.fecha_radicacion,'DD/MM/YYYY') AS fecha_radicacion, ";
			$sql .= "				TO_CHAR(EN.fecha_registro,'DD/MM/YYYY') AS registro, ";
			$sql .= "				SUM(FF.saldo) AS valor_envio, ";
			$sql .= "				COUNT(*) AS cantidad_facturas ";
			$sql .= "FROM		fac_facturas FF,";
			$sql .= "				envios_detalle ED,";
			$sql .= "				envios EN ";
			$sql .= "WHERE	FF.empresa_id = '".$empresa."' "; 
			$sql .= "AND		FF.tipo_id_tercero = '".$datos['tipo_id_tercero']."' "; 
			$sql .= "AND   	FF.tercero_id = '".$datos['tercero_id']."' "; 
			$sql .= "AND		FF.empresa_id = ED.empresa_id "; 
			$sql .= "AND		FF.factura_fiscal = ED.factura_fiscal "; 
			$sql .= "AND		FF.prefijo = ED.prefijo "; 
			$sql .= "AND		FF.sw_clase_factura = '1'::bpchar "; 
			$sql .= "AND		FF.estado = '0'::bpchar "; 
			$sql .= "AND		ED.envio_id = EN.envio_id "; 
			$sql .= "AND		EN.sw_estado = '1'::bpchar "; 
			$sql .= "AND 		EN.fecha_radicacion IS NOT NULL "; 
			$sql .= "AND		FF.fecha_vencimiento_factura IS NOT NULL  "; 
			$sql .= "GROUP BY EN.envio_id,titulo,sistema,EN.fecha_radicacion,registro  "; 
			$sql .= "UNION ALL "; 
			$sql .= "SELECT numero_envio AS envio_id, "; 
			$sql .= "				'ENVIOS EXTERNOS' AS titulo,  ";
			$sql .= "				'EXT' AS sistema, ";			
			$sql .= "				TO_CHAR(fecha_registro,'DD/MM/YYYY') AS fecha_radicacion,  "; 
			$sql .= "				TO_CHAR(fecha_registro,'DD/MM/YYYY') AS registro,  "; 
			$sql .= "				SUM(saldo) AS valor_envio, ";
			$sql .= "				COUNT(*) AS cantidad_facturas ";
			$sql .= "FROM		facturas_externas "; 
			$sql .= "WHERE	empresa_id = '".$empresa."' "; 
			$sql .= "AND		tipo_id_tercero = '".$datos['tipo_id_tercero']."' "; 
			$sql .= "AND   	tercero_id = '".$datos['tercero_id']."' ";
			$sql .= "AND   	numero_envio IS NOT NULL ";
			$sql .= "GROUP BY envio_id,titulo,sistema,fecha_radicacion,registro  ";
			$sql .= "ORDER BY 1";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[1]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
		}
		/********************************************************************************
		* Funcion donde se consulta la informacion de la cartera de un cliente 
		* determinado, se evalua la diferencia entre la fecha de vencimiento y la fecha 
		* actual para determinar las facturas a que rango pertenencen, se cuenta la 
		* cantidad de registros encontrdaos para determinar si se muesran las facturas en
		* un listado o se presentan agrupadas por el rango 
		*
		* @return array facturas del cliente
		*********************************************************************************/
		function ObtenerFactiurasEnvio($datos)
		{	
			$left_join  = "				LEFT JOIN ";
			$left_join .= "				(	SELECT 	SUM(valor_pendiente) AS valor_pendiente, ";
			$left_join .= "									SUM(valor_glosa) AS valor_glosa, ";
			$left_join .= "									SUM(valor_aceptado) AS valor_aceptado, ";
			$left_join .= "									SUM(valor_no_aceptado) AS valor_no_aceptado, ";
			$left_join .= "									prefijo, ";
			$left_join .= "									factura_fiscal ";
			$left_join .= "					FROM 		glosas ";
			$left_join .= "					WHERE 	sw_estado <> '0'";
			$left_join .= "					AND 		empresa_id = '".$datos['empresa_id']."' ";
			$left_join .= "					GROUP BY 5,6 ";
			$left_join .= "				) AS GL ";
			$left_join .= "				ON(	GL.prefijo = FF.prefijo AND ";
			$left_join .= "						GL.factura_fiscal = FF.factura_fiscal ";
			$left_join .= "					) ";
			$left_join .= "				LEFT JOIN ";
			$left_join .= "				( SELECT 	SUM(valor_abonado) AS valor_abonado_rc, ";
			$left_join .= "									prefijo_factura, ";
			$left_join .= "									factura_fiscal ";
			$left_join .= "					FROM 		rc_detalle_tesoreria_facturas ";
			$left_join .= "					WHERE 	empresa_id = '".$datos['empresa_id']."' ";
			$left_join .= "					GROUP BY 2,3 ) AS RC ";
			$left_join .= "				ON( RC.prefijo_factura = FF.prefijo AND ";
			$left_join .= "						RC.factura_fiscal = FF.factura_fiscal ) ";
			$left_join .= "				LEFT JOIN ";
			$left_join .= "				( SELECT 	SUM(valor_abonado) AS valor_abonado_na, ";
			$left_join .= "									prefijo_factura, ";
			$left_join .= "									factura_fiscal ";
			$left_join .= "					FROM 		notas_credito_ajuste_detalle_facturas ";
			$left_join .= "					WHERE 	empresa_id = '".$datos['empresa_id']."' ";
			$left_join .= "					GROUP BY 2,3 ) AS NA ";
			$left_join .= "				ON( NA.prefijo_factura = FF.prefijo AND ";
			$left_join .= "						NA.factura_fiscal = FF.factura_fiscal ) ";
			
			$left_join .= "				LEFT JOIN ";
			$left_join .= "				( SELECT 	SUM(valor_nota) AS valor_nota_credito, ";
			$left_join .= "									prefijo_factura, ";
			$left_join .= "									factura_fiscal, ";
			$left_join .= "									empresa_id ";
			$left_join .= "					FROM 		notas_credito ";
			$left_join .= "					WHERE		empresa_id = '".$datos['empresa_id']."' "; 
			$left_join .= "					AND			tipo_id_tercero = '".$datos['tipo_id_tercero']."' "; 
			$left_join .= "					AND   	tercero_id = '".$datos['tercero_id']."' ";
			$left_join .= "					GROUP BY 2,3,4 ) AS NC ";
			$left_join .= "				ON( NC.prefijo_factura = FF.prefijo AND ";
			$left_join .= "						NC.factura_fiscal = FF.factura_fiscal AND ";
			$left_join .= "						NC.empresa_id = FF.empresa_id ) ";

			$left_join .= "				LEFT JOIN ";
			$left_join .= "				( SELECT 	SUM(valor_nota) AS valor_nota_debito, ";
			$left_join .= "									prefijo_factura, ";
			$left_join .= "									factura_fiscal, ";
			$left_join .= "									empresa_id ";
			$left_join .= "					FROM 		notas_debito ";
			$left_join .= "					WHERE		empresa_id = '".$datos['empresa_id']."' "; 
			$left_join .= "					AND			tipo_id_tercero = '".$datos['tipo_id_tercero']."' "; 
			$left_join .= "					AND   	tercero_id = '".$datos['tercero_id']."' ";
			$left_join .= "					GROUP BY 2,3,4 ) AS ND ";
			$left_join .= "				ON( ND.prefijo_factura = FF.prefijo AND ";
			$left_join .= "						ND.factura_fiscal = FF.factura_fiscal AND ";
			$left_join .= "						ND.empresa_id = FF.empresa_id ) ";
				
			$sql = "";
			if($datos['sistema'] == 'SIIS')
			 $sql  = "SELECT (FF.fecha_vencimiento_factura::date - now()::date) / 30 AS intervalo, "; 
			else
				$sql  = "SELECT (FF.fecha_vencimiento::date - now()::date) / 30 AS intervalo, "; 

			$sql .= "				FF.prefijo,";
			$sql .= "				FF.factura_fiscal, ";
			$sql .= "				FF.saldo, ";
			$sql .= "				'".$datos['sistema']."' AS sistema, ";
			$sql .= "				COALESCE(GL.valor_glosa,0) AS valor_glosa, ";
			$sql .= "				COALESCE(GL.valor_aceptado,0) AS valor_aceptado, ";
			$sql .= "				COALESCE(GL.valor_no_aceptado,0) AS valor_no_aceptado, ";
			$sql .= "				COALESCE(GL.valor_pendiente,0) AS valor_pendiente, ";
			$sql .= "				COALESCE(RC.valor_abonado_rc,0) AS valor_abonado_rc, "; 
			$sql .= "				COALESCE(NA.valor_abonado_na,0) AS valor_abonado_na, ";
			$sql .= "				COALESCE(NC.valor_nota_credito,0) AS valor_nota_credito, ";
			$sql .= "				COALESCE(ND.valor_nota_debito,0) AS valor_nota_debito, ";
			$sql .= "				FF.total_factura AS total ";
				
			if($datos['sistema'] == 'SIIS')
			{
				$sql .= "FROM		fac_facturas FF ".$left_join.",";
				$sql .= "				envios_detalle ED ";
				$sql .= "WHERE	FF.empresa_id = '".$datos['empresa_id']."' "; 
				$sql .= "AND		FF.tipo_id_tercero = '".$datos['tipo_id_tercero']."' "; 
				$sql .= "AND   	FF.tercero_id = '".$datos['tercero_id']."' "; 
				$sql .= "AND		FF.empresa_id = ED.empresa_id "; 
				$sql .= "AND		FF.factura_fiscal = ED.factura_fiscal "; 
				$sql .= "AND		FF.prefijo = ED.prefijo "; 
				$sql .= "AND		FF.sw_clase_factura = '1'::bpchar "; 
				$sql .= "AND		FF.estado = '0'::bpchar "; 
				$sql .= "AND		ED.envio_id = ".$datos['envio_id']." "; 
				$sql .= "AND		FF.fecha_vencimiento_factura IS NOT NULL  "; 
			}
			else
			{
				$sql .= "FROM		facturas_externas FF ".$left_join." "; 
				$sql .= "WHERE	FF.empresa_id = '".$datos['empresa_id']."' "; 
				$sql .= "AND		FF.tipo_id_tercero = '".$datos['tipo_id_tercero']."' "; 
				$sql .= "AND   	FF.tercero_id = '".$datos['tercero_id']."' ";
				$sql .= "AND		FF.numero_envio = ".$datos['envio_id']." "; 
			}
			
			$sql .= "ORDER BY intervalo,FF.prefijo,FF.factura_fiscal ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$periodo = 0;
			$factura = array();
			while(!$rst->EOF)
			{
				if($rst->fields[0] < 0)
				{
					$periodo = $rst->fields[0]*(-1);
					if($periodo > 7) $periodo = 7;
					
					$periodo = "A".$periodo;
				}
				else
				{
					if($rst->fields[0] > 7) $periodo = 7;
					$periodo = "B".$periodo;
				}
				
				$facturas[$this->dn_periodos[$periodo]][]  = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $facturas;
		}
		/********************************************************************************
		* Funcion donde se calcula el numero de cuentas que posee un factura 
		* Si la cantidad de cuentas es uno, adicional se trae el numero de la cuenta 
		* correspondiente 
		* 
		* @ params string numero de la factura concatenado con el prefijo
		* @return string 
		*********************************************************************************/
		function ObtenerCuentasFactura($prefijo,$factura_fiscal,$empresa)
		{			
			$sql .= "SELECT	CU.numerodecuenta,";
			$sql .= "				CU.total_cuenta,";
			$sql .= "				PL.plan_descripcion,";
			$sql .= "				TO_CHAR(CU.fecha_registro, 'DD/MM/YYYY') AS fecha  ";
			$sql .= "FROM 	fac_facturas_cuentas FF,";
			$sql .= "				cuentas CU, ";
			$sql .= "				planes PL ";
			$sql .= "WHERE	CU.estado NOT IN('5') ";
			$sql .= "AND 		CU.plan_id = PL.plan_id ";
			$sql .= "AND 		FF.numerodecuenta = CU.numerodecuenta ";
			$sql .= "AND 		FF.prefijo = '".$prefijo."' ";
			$sql .= "AND 		FF.factura_fiscal = ".$factura_fiscal." ";
			$sql .= "AND 		FF.empresa_id = '".$empresa."' ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			return $datos;
		}
		/********************************************************************************
		* Funcion donde se obtiene la informacion del detalle de una cuenta segun sea 
		* la factura
		*
		* @return boolean 
		*********************************************************************************/
		function ObtenerInformacionDetalleCuentas($empresa,$numerodecuenta)
		{			
			$sql  = "SELECT PL.plan_descripcion,";
			$sql .= "		IG.ingreso,";
			$sql .= "		PA.tipo_id_paciente,";
			$sql .= "		PA.paciente_id,";
			$sql .= "		PA.primer_nombre||' '||PA.segundo_nombre||' '||PA.primer_apellido||' '||PA.segundo_apellido AS paciente,";
			$sql .= "		CU.valor_cuota_paciente,";
			$sql .= "		CU.valor_cuota_moderadora,";
			$sql .= "		CU.valor_total_empresa, ";
			$sql .= "		FC.prefijo, ";
			$sql .= "		FC.factura_fiscal ";
			$sql .= "FROM	planes PL,";
			$sql .= "		cuentas CU,";
			$sql .= "		ingresos IG, ";
			$sql .= "		pacientes PA, ";
			$sql .= "		fac_facturas_cuentas FC ";
			$sql .= "WHERE	FC.empresa_id = '".$empresa."' ";
			$sql .= "AND	CU.numerodecuenta = ".$numerodecuenta."";
			$sql .= "AND 	CU.numerodecuenta = FC.numerodecuenta ";
			$sql .= "AND 	CU.ingreso = IG.ingreso ";
			$sql .= "AND 	CU.plan_id = PL.plan_id ";
			$sql .= "AND 	IG.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "AND 	IG.paciente_id = PA.paciente_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
				
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************
		* Funcion donde se obtienen los cargos que pettenece a una cuenta 
		* 
		* @params int numero de cuenta
		* @return array datos de los cargos de la cuenta
		*********************************************************************************/
		function ObtenerCargosCuentas($numerodecuenta,$empresa)
		{			
			$sql  = "SELECT	TO_CHAR(CU.fecha_registro,'DD/MM/YYYY') AS fecha, ";
			$sql .= "				CU.transaccion, ";
			$sql .= "				CU.cargo_cups, ";
			$sql .= "				TD.tarifario_id, ";
			$sql .= "				TD.descripcion, "; 
			$sql .= "				CU.valor_cubierto,";
			$sql .= "				CU.valor_cargo, ";
			$sql .= "				CU.codigo_agrupamiento_id AS agrupado ";
			$sql .= "FROM		cuentas_detalle CU, ";
			$sql .= "				tarifarios_detalle TD ";
			$sql .= "WHERE 	CU.numerodecuenta = ".$numerodecuenta." ";
			$sql .= "AND 		CU.facturado = '1' ";
			$sql .= "AND 		CU.empresa_id = '".$empresa."' ";
			$sql .= "AND 		CU.valor_cargo >= 0 ";
			$sql .= "AND 		CU.cargo = TD.cargo ";
			$sql .= "AND 		CU.tarifario_id = TD.tarifario_id ";
			$sql .= "AND		TD.tarifario_id <> 'SYS' ";
			$sql .= "ORDER BY 1,2 ";			
					
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
		/********************************************************************************
		* Funcion donde se muestran los insumos pertenecientes a una cuenta 
		* 
		* @params int numero de cuenta
		* @return array datos de los insumos de la cuenta
		*********************************************************************************/
		function ObtenerInsumosCuentas($numerodeuenta,$empresa)
		{
			$sql  = "SELECT	IM.codigo_producto, "; 
			$sql .= "				SUM(CU.cantidad) AS cantidad,";
			$sql .= "				IM.descripcion, ";
      $sql .= "				SUM(CU.valor_cubierto) AS valor_cubierto,";
      $sql .= "				SUM(CU.valor_cargo) AS valor_cargo ";
			$sql .= "FROM		cuentas_detalle CU, ";
			$sql .= "				bodegas_documentos_d BD, ";
			$sql .= "				inventarios_productos IM ";
			$sql .= "WHERE	CU.numerodecuenta = ".$numerodeuenta." ";
			$sql .= "AND		CU.facturado = '1' ";
			$sql .= "AND		CU.empresa_id = '".$empresa."' ";
			$sql .= "AND		CU.valor_cargo >= 0 ";
			$sql .= "AND		CU.consecutivo = BD.consecutivo ";
			$sql .= "AND		BD.codigo_producto = IM.codigo_producto ";
			$sql .= "GROUP BY 1,3 ";
			$sql .= "ORDER BY 1 ";
			
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
		/********************************************************************************
		* Funcion donde se obtiene la informacion de las glosas de una factura 
		* determinada
		* @return array datos de las glosas de la factura
		*********************************************************************************/
		function ObtenerGlosasFactura($prefijo,$factura_fiscal,$empresa)
		{			
			$sql .= "SELECT GL.glosa_id, ";
			$sql .= "				TO_CHAR(GL.fecha_glosa,'DD/MM/YYYY') AS fecha, ";
			$sql .= "				GL.valor_glosa, ";
			$sql .= "				GL.valor_aceptado, ";
			$sql .= "				GL.valor_no_aceptado, ";
			$sql .= "				CASE WHEN sw_estado = '1' THEN 'ACTIVA' ";
			$sql .= "			 		WHEN sw_estado = '2' THEN 'POR CONTABILIZAR' ";
			$sql .= "			 		WHEN sw_estado = '3' THEN 'CERRADA' END AS estado ";
			$sql .= "FROM 	glosas GL ";
			$sql .= "WHERE	GL.factura_fiscal = ".$factura_fiscal." ";
			$sql .= "AND		GL.prefijo = '".$prefijo."' ";
			$sql .= "AND		GL.empresa_id = '".$empresa."' ";
			$sql .= "AND		GL.sw_estado <> '0' ";
			$sql .= "ORDER BY GL.glosa_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
				/********************************************************************************
		* Funcion donde se obtiene la informacion de una glosa determinada para una 
		* factura 
		* 
		* @return boolean 
		*********************************************************************************/
		function ObtenerInformacionGlosa($empresa,$glosaid,$sistema)
		{
			$sql  = "SELECT FF.total_factura, ";
			$sql .= "				FF.prefijo, ";
			$sql .= "				FF.factura_fiscal, ";
			$sql .= "				GM.motivo_glosa_descripcion,";
			$sql .= "				GL.observacion,";
			$sql .= "				TC.descripcion AS clasificacion,";
			$sql .= "				GL.documento_interno_cliente_id,";
			$sql .= "				GL.valor_glosa,";
			$sql .= "				GL.valor_aceptado,";
			$sql .= "				GL.valor_no_aceptado,";
			$sql .= "				GL.valor_pendiente, ";
			$sql .= "				SU.nombre AS auditor ,";
			$sql .= "				CASE 	WHEN GL.sw_estado = '1' THEN 'ACTIVA'";
			$sql .= "			 				WHEN GL.sw_estado = '2' THEN 'POR CONTABILIZAR'";
			$sql .= "			 				WHEN GL.sw_estado = '3' THEN 'CERRADA' END AS estado_glosa,";
			$sql .= "				GL.sw_glosa_total_factura, ";
			$sql .= "				US.nombre, ";			
			$sql .= "				TO_CHAR(GL.fecha_glosa,'DD/MM/YYYY') AS fecha_glosa,";
			$sql .= "				TO_CHAR(GL.fecha_cierre,'DD/MM/YYYY') AS fecha_cierre,";
			$sql .= "				TO_CHAR(GL.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS fecha_factura ";
			
			if($sistema == "SIIS")
			{
				$sql .= "			 ,PL.num_contrato,"; 
				$sql .= "				PL.plan_descripcion ";
			}
			
			$sql .= "FROM	glosas GL ";
			$sql .= "			LEFT JOIN system_usuarios SU ";
			$sql .= "			ON (SU.usuario_id = GL.auditor_id) ";
			$sql .= "			LEFT JOIN glosas_motivos GM";
			$sql .= "			ON(GL.motivo_glosa_id = GM.motivo_glosa_id) LEFT JOIN ";
			$sql .= "			glosas_tipos_clasificacion TC ";
			$sql .= "			ON(GL.glosa_tipo_clasificacion_id = TC.glosa_tipo_clasificacion_id), ";
			$sql .= "			system_usuarios US, ";
			
			if($sistema == "SIIS")
			{
				$sql .= "		planes PL ,";
				$sql .= "		fac_facturas FF ";
			}
			else
				$sql .= "			facturas_externas FF ";
			
			$sql .= "WHERE 	GL.glosa_id = ".$glosaid." ";
			$sql .= "AND 		GL.empresa_id = '".$empresa."' ";
			$sql .= "AND 		GL.prefijo = FF.prefijo ";
			$sql .= "AND 		GL.empresa_id = FF.empresa_id ";
			$sql .= "AND 		GL.factura_fiscal = FF.factura_fiscal ";
			$sql .= "AND 		GL.sw_estado <> '0' ";
			$sql .= "AND 		GL.usuario_id = US.usuario_id ";
			
			if($sistema == "SIIS")
			{
				$sql .= "AND 		PL.plan_id = FF.plan_id ";
				$sql .= "AND 		PL.empresa_id = FF.empresa_id ";
			}
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************
		* Funcion mediante la cual se buscan los cargos glosados de las cuentas 
		* pertenecientes a una factura 
		* 
		* @param string identificador de la glosa 
		* @return array datos de los cargos glosados  
		*********************************************************************************/
		function ObtenerInformacionGlosaCargos($glosaid)
		{	
			$sql  = "SELECT	C.numerodecuenta, ";
			$sql .= "				CASE WHEN GC.sw_glosa_total_cuenta = '0' THEN 'DA' ";
			$sql .= "		    		 WHEN GC.sw_glosa_total_cuenta = '1' THEN 'DT' END AS tipo, ";
			$sql .= "				GM.motivo_glosa_descripcion,";
			$sql .= "				CASE 	WHEN GC.sw_glosa_total_cuenta = '0' ";
			$sql .= " 				 		THEN GC.valor_glosa_copago + GC.valor_glosa_cuota_moderadora ";
			$sql .= "		     			WHEN GC.sw_glosa_total_cuenta = '1' THEN C.total_cuenta END AS valor_glosa ,";
			$sql .= "				'--' AS cargo,";
			$sql .= "				SUM(GC.valor_aceptado) AS valor_aceptado,";
			$sql .= "				SUM(GC.valor_no_aceptado) AS valor_no_aceptado ";
			$sql .= "FROM		cuentas C,";
			$sql .= "				glosas_detalle_cuentas GC LEFT JOIN glosas_motivos GM ";
			$sql .= "				ON(GM.motivo_glosa_id = GC.motivo_glosa_id) ";
			$sql .= "WHERE	GC.glosa_id = ".$glosaid." ";
			$sql .= "AND 		C.numerodecuenta = GC.numerodecuenta ";
			$sql .= "AND		GC.sw_estado <> '0' ";
			$sql .= "GROUP BY 1,2,3,4,5 ";
			$sql .= "UNION  ";
			$sql .= "SELECT	CD.numerodecuenta, ";
			$sql .= "				'DC' AS tipo, ";
			$sql .= "				GM.motivo_glosa_descripcion, ";
			$sql .= "				GC.valor_glosa, ";
			$sql .= "				CD.cargo,  ";
			$sql .= "				SUM(GC.valor_aceptado) AS valor_aceptado,";
			$sql .= "				SUM(GC.valor_no_aceptado) AS valor_no_aceptado ";
			$sql .= "FROM 	glosas_detalle_cargos GC, ";
			$sql .= "				cuentas_detalle CD, ";
			$sql .= "				glosas_motivos GM,";
			$sql .= "				glosas_detalle_cuentas GD ";
			$sql .= "WHERE 	GC.motivo_glosa_id = GM.motivo_glosa_id ";
			$sql .= "AND 		GC.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND 		GD.numerodecuenta = CD.numerodecuenta ";
			$sql .= "AND 		GC.transaccion = CD.transaccion ";
			$sql .= "AND 		GC.sw_estado <> '0' ";
			$sql .= "AND 		GC.glosa_id = ".$glosaid." ";
			$sql .= "GROUP BY 1,2,3,4,5 ";
			$sql .= "UNION ";
			$sql .= "SELECT CD.numerodecuenta, ";
			$sql .= "				'DI' AS tipo, ";
			$sql .= "				GM.motivo_glosa_descripcion, ";
			$sql .= "				GI.valor_glosa, ";
			$sql .= "				GI.codigo_producto AS cargo, ";
			$sql .= "				SUM(GI.valor_aceptado) AS valor_aceptado,";
			$sql .= "				SUM(GI.valor_no_aceptado) AS valor_no_aceptado ";
			$sql .= "FROM 	glosas_detalle_inventarios GI, ";
			$sql .= "				cuentas CD, ";
			$sql .= "				glosas_motivos GM, ";
			$sql .= "				glosas_detalle_cuentas GD ";
			$sql .= "WHERE	GI.motivo_glosa_id = GM.motivo_glosa_id ";
			$sql .= "AND 		GI.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND 		GD.numerodecuenta = CD.numerodecuenta ";
			$sql .= "AND 		GI.sw_estado <> '0' ";
			$sql .= "AND 		GI.glosa_id = ".$glosaid." ";
			$sql .= "AND 		GD.glosa_id = GI.glosa_id ";
			$sql .= "GROUP BY 1,2,3,4,5 ";
			$sql .= "ORDER BY 1,2 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerInformacionFacturaExterna($prefijo,$factura_fiscal,$empresa)
		{			
			$sql .= "SELECT prefijo,";
			$sql .= "				factura_fiscal,";
			$sql .= "				TO_CHAR(fecha_registro,'DD/MM/YYYY') AS registro,";
			$sql .= "				TO_CHAR(fecha_vencimiento,'DD/MM/YYYY') AS vencimiento,";
			$sql .= "				total_factura,";
			$sql .= "				saldo,";
			$sql .= "				concepto,";
			$sql .= "				numero_envio, ";
			$sql .= "				tercero_id, ";
			$sql .= "				tipo_id_tercero ";
			$sql .= "FROM 	facturas_externas F ";
			$sql .= "WHERE	prefijo = '".$prefijo."' ";
			$sql .= "AND		factura_fiscal = ".$factura_fiscal." ";
			$sql .= "AND		empresa_id = '".$empresa."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerPrefijos($empresa)
		{
			$sql  = "SELECT DISTINCT prefijo ";
			$sql .= "FROM   fac_facturas ";
			$sql .= "WHERE  empresa_id = '".$empresa."' ";
			$sql .= "AND    estado = '0'::bpchar ";
			$sql .= "AND    saldo > 0 ";
			$sql .= "AND    sw_clase_factura = '1'::bpchar ";
			$sql .= "AND    fecha_vencimiento_factura IS NOT NULL ";
			$sql .= "UNION ALL ";
			$sql .= "SELECT DISTINCT prefijo ";
			$sql .= "FROM   facturas_externas ";
			$sql .= "WHERE  empresa_id = '".$empresa."' ";
			$sql .= "AND    estado = '0'::bpchar ";
			$sql .= "AND    saldo > 0 ";
			$sql .= "AND    fecha_vencimiento IS NOT NULL ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************** 
		* Funcion domde se seleccionan los tipos de id de los terceros 
		* 
		* @return array datos de tipo_id_terceros 
		***********************************************************************************/
		function ObtenerTipoIdTerceros()
		{
			$sql  = "SELECT tipo_id_tercero,descripcion FROM tipo_id_terceros ORDER BY 2 ";
	
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
		/********************************************************************************
		*
		* @return rst 
		*********************************************************************************/
		function ObtenerNotas($prefijo,$factura_fiscal,$empresa)
		{			
			$sql .= "SELECT	A.*,NC.descripcion ";
			$sql .= "FROM		(";
			$sql .= "	SELECT ND.prefijo_factura,";
			$sql .= "					ND.factura_fiscal,";
			$sql .= "					ND.empresa_id,";
			$sql .= "					ND.nota_debito_id AS numero,";
			$sql .= "					ND.prefijo,";
			$sql .= "					NC.concepto_id,";
			$sql .= "					'DEBITO' AS tipo, ";
			$sql .= "					TO_CHAR(ND.fecha_registro ,'DD/MM/YYYY') AS fecha, ";
			$sql .= "					COALESCE(ND.valor_nota,0) AS valor ";
			$sql .= "	FROM		notas_debito ND, ";
			$sql .= "					notas_debito_detalle_conceptos NC ";
			$sql .= "	WHERE	ND.prefijo_factura = '".$prefijo."' ";
			$sql .= "	AND		ND.factura_fiscal = ".$factura_fiscal." ";
			$sql .= "	AND		ND.empresa_id = '".$empresa."' ";
			$sql .= "	AND		ND.empresa_id = NC.empresa_id ";
			$sql .= "	AND		ND.prefijo = NC.prefijo ";
			$sql .= "	AND		ND.nota_debito_id = NC.nota_debito_id ";
			$sql .= "	UNION ";
			$sql .= "	SELECT NO.prefijo_factura,";
			$sql .= "					NO.factura_fiscal,";
			$sql .= "					NO.empresa_id,";
			$sql .= "					NO.nota_credito_id AS numero,";
			$sql .= "					NO.prefijo,";
			$sql .= "					NC.concepto_id,";
			$sql .= "					'CREDITO' AS tipo, ";			
			$sql .= "					TO_CHAR(NO.fecha_registro ,'DD/MM/YYYY') AS fecha, ";
			$sql .= "					COALESCE(NO.valor_nota,0) AS valor ";
			$sql .= "	FROM		notas_credito NO, ";
			$sql .= "					notas_credito_detalle_conceptos NC ";
			$sql .= "	WHERE	NO.prefijo_factura = '".$prefijo."' ";
			$sql .= "	AND		NO.factura_fiscal = ".$factura_fiscal." ";
			$sql .= "	AND		NO.empresa_id = '".$empresa."' ";
			$sql .= "	AND		NO.empresa_id = NC.empresa_id ";
			$sql .= "	AND		NO.prefijo = NC.prefijo ";
			$sql .= "	AND		NO.nota_credito_id = NC.nota_credito_id ";
			$sql .= "	UNION ";
			$sql .= "	SELECT NF.prefijo_factura,";
			$sql .= "					NF.factura_fiscal,";
			$sql .= "					NF.empresa_id,";
			$sql .= "					NF.nota_credito_ajuste AS numero,";
			$sql .= "					NF.prefijo,";	
			$sql .= "					NC.concepto_id,";
			$sql .= "					'AJUSTE' AS tipo, ";
			$sql .= "					TO_CHAR(NA.fecha_registro ,'DD/MM/YYYY') AS fecha, ";
			$sql .= "					COALESCE(SUM(NF.valor_abonado),0) AS valor ";
			$sql .= "	FROM		notas_credito_ajuste_detalle_facturas NF, ";
			$sql .= "					notas_credito_ajuste_detalle_conceptos NC, ";
			$sql .= "					notas_credito_ajuste NA ";
			$sql .= "	WHERE	NF.prefijo_factura = '".$prefijo."' ";
			$sql .= "	AND		NF.factura_fiscal = ".$factura_fiscal." ";
			$sql .= "	AND		NF.empresa_id = '".$empresa."' ";
			$sql .= "	AND		NF.empresa_id = NA.empresa_id ";
			$sql .= "	AND		NF.prefijo = NA.prefijo ";
			$sql .= "	AND		NF.nota_credito_ajuste = NA.nota_credito_ajuste ";
			$sql .= "	AND		NC.empresa_id = NA.empresa_id ";
			$sql .= "	AND		NC.prefijo = NA.prefijo ";
			$sql .= "	AND		NC.nota_credito_ajuste = NA.nota_credito_ajuste ";
			$sql .= "	GROUP BY 1,2,3,4,5,6,7,8) AS A, ";
			$sql .= "	notas_credito_ajuste_conceptos NC ";
			$sql .= "WHERE NC.concepto_id = A.concepto_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$recibos = array();
			while (!$rst->EOF)
			{
				$pago = "";
				$recibos[$rst->fields[0]][]  = $rst->GetRowAssoc($ToUpper = false);
				
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $recibos;
		}
		/********************************************************************************
		*
		* @return rst 
		*********************************************************************************/
		function ObtenerRecibos($prefijo,$factura_fiscal,$empresa)
		{			
			$sql .= "SELECT RC.prefijo,";
			$sql .= "				RC.recibo_caja,";
			$sql .= "				RC.total_abono,";
			$sql .= " 			RC.total_efectivo,";
			$sql .= " 			RC.total_cheques,";
			$sql .= " 			RC.total_tarjetas,";
			$sql .= "				RC.total_consignacion,";
			$sql .= "				TO_CHAR(RC.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
			$sql .= "				COALESCE(SUM(RT.valor),0) AS valor, ";
			$sql .= "				RF.valor_abonado AS abono, ";
			$sql .= "				RC.otros ";
			$sql .= "FROM		recibos_caja RC ";
			$sql .= "				LEFT JOIN rc_detalle_tesoreria_conceptos RT ";
			$sql .= "				ON(	RT.recibo_caja = RC.recibo_caja AND ";
			$sql .= "						RT.prefijo = RC.prefijo AND ";
			$sql .= "						RT.naturaleza = 'D'), ";	
			$sql .= "				rc_detalle_tesoreria_facturas RF ";
			$sql .= "WHERE	RC.empresa_id = '".$empresa."' ";
			$sql .= "AND		RC.recibo_caja = RF.recibo_caja ";
			$sql .= "AND		RC.prefijo = RF.prefijo ";
			$sql .= "AND		RF.prefijo_factura = '".$prefijo."' ";
			$sql .= "AND		RF.factura_fiscal = ".$factura_fiscal." ";
			$sql .= "AND		RC.estado = '2' ";
			$sql .= "GROUP BY 1,2,3,4,5,6,7,8,RC.otros,abono ";
			$sql .= "ORDER BY 2 DESC ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i=0;
			$recibos = array();
			while (!$rst->EOF)
			{
				$pago = "";
				$recibos[$i]  = $rst->GetRowAssoc($ToUpper = false);

				if($recibos[$i]['otros'] > 0)	$pago = "OTRO CONCEPTO ";
				if($recibos[$i]['total_cheques'] > 0)	$pago = "CHEQUE ";
				if($recibos[$i]['total_efectivo'] > 0) $pago = "EFECTIVO ";
				if($recibos[$i]['total_tarjetas'] > 0) $pago = "TARJETA ";
				if($recibos[$i]['total_consignacion'] > 0) $pago = "CONSIGNACIÓN ";
				
				$recibos[$i]['forma_pago'] = $pago;
				
				$rst->MoveNext();
				$i++;
		  }
			$rst->Close();
			
			return $recibos;
		}
		/********************************************************************************
		*
		* @return rst 
		*********************************************************************************/
		function ObtenerFacturas($empresa,$fechai,$fechaf,$opcion,$cnt,$offset,$datos)
		{
			$sql  = "SELECT FF.plan_id, "; 
			$sql .= "				FF.prefijo, ";
			$sql .= "				FF.factura_fiscal, ";
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
			$sql .= "				FF.empresa_id, ";
			$sql .= "				FF.saldo, ";
			$sql .= "				FF.total_factura, ";
			$sql .= "				FF.retencion_fuente, ";
			$sql .= "				PL.plan_descripcion, ";
			$sql .= "				PA.tipo_id_paciente||' '||PA.paciente_id AS identificacion, ";
			$sql .= "				PA.primer_nombre ||' '|| PA.segundo_nombre AS nombre, ";
			$sql .= "				PA.primer_apellido ||' '|| PA.segundo_apellido AS apellido, ";
			$sql .= "				COALESCE(MAX(ED.envio_id),0) AS envio ";
			$sql .= "FROM 	fac_facturas FF LEFT JOIN ";
			$sql .= "				envios_detalle ED ";
			$sql .= "				ON (ED.prefijo = FF.prefijo AND ";
			$sql .= "						ED.factura_fiscal = FF.factura_fiscal AND ";
			$sql .= "						ED.empresa_id = FF.empresa_id ), ";
			$sql .= "				fac_facturas_cuentas FC, ";
			$sql .= "				cuentas CU, ";
			$sql .= "				ingresos IG, ";
			$sql .= "				pacientes PA, ";
			$sql .= "				planes PL ";
			$sql .= "WHERE 	FF.prefijo = FC.prefijo ";
			$sql .= "AND		FF.factura_fiscal = FC.factura_fiscal ";
			$sql .= "AND		FF.empresa_id = FC.empresa_id ";
			$sql .= "AND		FC.numerodecuenta = CU.numerodecuenta ";
			$sql .= "AND		CU.ingreso = IG.ingreso ";
			$sql .= "AND		IG.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "AND		IG.paciente_id = PA.paciente_id ";
			$sql .= "AND		FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "AND		FF.estado = '0'::bpchar ";
			$sql .= "AND		PL.plan_id = FF.plan_id ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			
			if($fechai)
			{
				$sql .= "AND		FF.fecha_registro::date >= '".$fechai."' ";
				$sql .= "AND		FF.fecha_registro::date <= '".$fechaf."' ";
			}
			
			if($datos['factura_f'])
			{
				$sql .= "AND		FF.prefijo = '".$datos['prefijo']."' ";
				$sql .= "AND		FF.factura_fiscal = ".$datos['factura_f']." ";
			}
			if($datos['envio'])
			{
				$datos['orden'] = '1';
				$sql .= "AND		ED.envio_id = ".$datos['envio']." ";
			}
			
			if($opcion == "1")
				$sql .= "AND		FF.fecha_vencimiento_factura IS NOT NULL ";
			else if($opcion == "2")
				$sql .= "AND		FF.fecha_vencimiento_factura IS NULL ";
				
			$sql .= "GROUP BY FF.plan_id,FF.prefijo, FF.factura_fiscal,FF.fecha_registro, FF.empresa_id, FF.saldo, FF.total_factura, FF.retencion_fuente, PL.plan_descripcion,identificacion,nombre, apellido ";
			
			if(!$cnt)
			{
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				$cnt = 0;
				if(!$rst->EOF) $cnt = $rst->RecordCount();
			}
			
			$this->ProcesarSqlConteo("",$cnt,$offset);
			switch($datos['orden'])
			{
				case '2':
					$sql .= "ORDER BY envio "; 
				break;
				default:
					$sql .= "ORDER BY FF.prefijo, FF.factura_fiscal "; 
				break;
			}
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			return $datos;
		}
		/********************************************************************************
		*
		* @return rst 
		*********************************************************************************/
		function ObtenerFacturasExternas($empresa,$fechai,$fechaf,$opcion,$cnt,$offset,$datos)
		{
			$sql  = "SELECT FF.plan_id,  ";
			$sql .= "				FF.prefijo, ";
			$sql .= "				FF.factura_fiscal, ";
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
			$sql .= "				FF.empresa_id, ";
			$sql .= "				FF.saldo, ";
			$sql .= "				FF.total_factura, ";
			$sql .= "				0 AS retencion_fuente, ";
			$sql .= "				FF.numero_envio AS envio_id, ";
			$sql .= "				TO_CHAR(FF.fecha_radicacion,'DD/MM/YYYY') AS fecha_radicacion, ";
			$sql .= "				TO_CHAR(FF.fecha_envio,'DD/MM/YYYY') AS fecha_envio, ";
			$sql .= "				PL.plan_descripcion, ";
			$sql .= "				'' AS nombre, ";
			$sql .= "				'' AS apellido ";
			$sql .= "FROM 	facturas_externas FF, ";
			$sql .= "				planes PL ";
			$sql .= "WHERE 	FF.estado = '0'::bpchar ";
			$sql .= "AND		FF.plan_id IS NOT NULL ";
			$sql .= "AND		FF.plan_id = PL.plan_id ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			if($fechai)
			{
				$sql .= "AND		FF.fecha_registro::date >= '".$fechai."' ";
				$sql .= "AND		FF.fecha_registro::date <= '".$fechaf."' ";
			}
			
			if($datos['factura_f'])
			{
				$sql .= "AND		FF.prefijo = '".$datos['prefijo']."' ";
				$sql .= "AND		FF.factura_fiscal = ".$datos['factura_f']." ";
			}
			if($datos['envio'])
			{
				$datos['orden'] = '1';
				$sql .= "AND		FF.numero_envio = ".$datos['envio']." ";
			}
			
			if(!$cnt)
			{
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				$cnt = 0;
				if(!$rst->EOF) $cnt = $rst->RecordCount();
			}
			$this->ProcesarSqlConteo("",$cnt,$offset);
			
			switch($datos['orden'])
			{
				case '2':
					$sql .= "ORDER BY FF.numero_envio "; 
				break;
				default:
					$sql .= "ORDER BY FF.prefijo, FF.factura_fiscal "; 
				break;
			}
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			return $datos;
		}
		/********************************************************************************
		*
		* @return rst 
		*********************************************************************************/
		function ObtenerFacturasEnvios($empresa,$fechai,$fechaf,$datos)
		{
			$sql  = "SELECT	ED.prefijo,";
			$sql .= "				ED.factura_fiscal,";
			$sql .= "				ED.envio_id,";
			$sql .= "				TO_CHAR(EN.fecha_radicacion,'DD/MM/YYYY') AS fecha_radicacion,";
			$sql .= "				TO_CHAR(EN.fecha_registro,'DD/MM/YYYY') AS fecha_envio ";
			$sql .= "FROM		envios_detalle ED,";
			$sql .= "				envios EN,";
			$sql .= "				fac_facturas FF ";
			$sql .= "WHERE	ED.envio_id = EN.envio_id ";
			$sql .= "AND		EN.sw_estado != '2' ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		FF.prefijo = ED.prefijo ";
			$sql .= "AND		FF.factura_fiscal = ED.factura_fiscal ";
			$sql .= "AND		FF.empresa_id = ED.empresa_id ";
			$sql .= "AND		FF.estado = '0'::bpchar ";
			if($fechai)
			{
				$sql .= "AND		FF.fecha_registro::date >= '".$fechai."' ";
				$sql .= "AND		FF.fecha_registro::date <= '".$fechaf."' ";
			}
			if($datos['facturacion'] == '2')
				$sql .= "AND		EN.fecha_radicacion IS NULL ";
			else if($datos['facturacion'] == '1')
				$sql .= "AND		EN.fecha_radicacion IS NOT NULL ";
			
			if($datos['factura_f'])
			{
				$sql .= "AND		FF.prefijo = '".$datos['prefijo']."' ";
				$sql .= "AND		FF.factura_fiscal = ".$datos['factura_f']." ";
			}
			if($datos['envio'])
			{
				$sql .= "AND		EN.envio_id = ".$datos['envio']." ";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			return $datos;
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
		function ProcesarSqlConteo($consulta,$num_reg = null,$offset=null,$limite=null)
		{
			$this->offset = 0;
			$this->paginaActual = 1;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
				if(!$this->limit) $this->limit = 20;
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
			
			if($num_reg === 0)
			{
				$this->conteo = $num_reg;
				return true;
			}
			
			if(!$num_reg)
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
				$this->conteo = $num_reg;
			}
			return true;
		}
		/********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*********************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				//echo $sql;
				echo $this->frmError['MensajeError'] = "ERROR DB : $sql " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
?>
