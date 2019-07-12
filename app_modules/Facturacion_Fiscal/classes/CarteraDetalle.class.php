<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: CarteraDetalle.class.php,v 1.1 2011/02/23 21:54:04 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */
  /**
  * Clase: CarteraDetalle
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */
	class CarteraDetalle extends ConexionBD
	{
    /**
    * Constructor de la clase
    */
		function CarteraDetalle(){}
    /**
		* Funcion donde se obtiene la informacion de las glosas de una factura 
		* determinada
		*
   	* @param string $prefijo Identificador del prefijo de la factura
    * @param integer $factura_fiscal Numero de la factura
    * @param string $empresa Identificador de la empresa
    *
    * @return array datos de las glosas de la factura
		**/
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
		/**
		* Funcion donde se obtiene la informacion de una glosa determinada para una 
		* factura 
		* 
    * @param string $empresa Identificador de la empresa
    * @param integer $glosaid Identificador de la glosa
    *
		* @return boolean 
		*/
		function ObtenerInformacionGlosa($empresa,$glosaid)
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
			$sql .= "			 ,PL.num_contrato,"; 
			$sql .= "				PL.plan_descripcion ";
			$sql .= "FROM	glosas GL ";
			$sql .= "			LEFT JOIN system_usuarios SU ";
			$sql .= "			ON (SU.usuario_id = GL.auditor_id) ";
			$sql .= "			LEFT JOIN glosas_motivos GM";
			$sql .= "			ON(GL.motivo_glosa_id = GM.motivo_glosa_id) LEFT JOIN ";
			$sql .= "			glosas_tipos_clasificacion TC ";
			$sql .= "			ON(GL.glosa_tipo_clasificacion_id = TC.glosa_tipo_clasificacion_id), ";
			$sql .= "			system_usuarios US, ";
      $sql .= "		planes PL ,";
      $sql .= "		fac_facturas FF ";
			$sql .= "WHERE 	GL.glosa_id = ".$glosaid." ";
			$sql .= "AND 		GL.empresa_id = '".$empresa."' ";
			$sql .= "AND 		GL.prefijo = FF.prefijo ";
			$sql .= "AND 		GL.empresa_id = FF.empresa_id ";
			$sql .= "AND 		GL.factura_fiscal = FF.factura_fiscal ";
			$sql .= "AND 		GL.sw_estado <> '0' ";
			$sql .= "AND 		GL.usuario_id = US.usuario_id ";
      $sql .= "AND 		PL.plan_id = FF.plan_id ";
      $sql .= "AND 		PL.empresa_id = FF.empresa_id ";
				
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
		/**
		* Metdodo donde se buscan los cargos glosados de las cuentas 
		* pertenecientes a una factura 
		* 
    * @param integer $glosaid Identificador de la glosa
    *
		* @return mixed
		*/
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
		/**
    * Metodo donde se buscan las notas credito y debito que ha tenido la factura
    *
   	* @param string $prefijo Identificador del prefijo de la factura
    * @param integer $factura_fiscal Numero de la factura
    * @param string $empresa Identificador de la empresa
    *
		* @return mixed 
		**/
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
		/**
    * Metodo donde se buscan los recibos de caja de tesoreria que ha tenido una factura
		*
   	* @param string $prefijo Identificador del prefijo de la factura
    * @param integer $factura_fiscal Numero de la factura
    * @param string $empresa Identificador de la empresa
    *
		* @return rst 
		*/
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
    /**
    * Metodo donde se buscan las notas credito y debito de contado 
    * que ha tenido la factura
    *
   	* @param string $prefijo Identificador del prefijo de la factura
    * @param integer $factura_fiscal Numero de la factura
    * @param string $empresa Identificador de la empresa
    *
		* @return mixed 
		**/
		function ObtenerNotasContado($prefijo,$factura_fiscal,$empresa)
		{			
			$sql .= "SELECT	A.*,NC.descripcion ";
			$sql .= "FROM		(";
			$sql .= "	        SELECT  ND.prefijo_factura,";
			$sql .= "					        ND.factura_fiscal,";
			$sql .= "					        ND.empresa_id,";
			$sql .= "					        ND.numero,";
			$sql .= "					        ND.prefijo,";
			$sql .= "					        NC.nota_contado_concepto_id,";
			$sql .= "					        'CREDITO' AS tipo, ";
			$sql .= "					        TO_CHAR(ND.fecha_registro ,'DD/MM/YYYY') AS fecha, ";
			$sql .= "					        COALESCE(ND.valor_nota,0) AS valor ";
			$sql .= "	        FROM		notas_contado_credito ND, ";
			$sql .= "					        notas_contado_credito_d NC ";
			$sql .= "	        WHERE	  ND.prefijo_factura = '".$prefijo."' ";
			$sql .= "	        AND		  ND.factura_fiscal = ".$factura_fiscal." ";
			$sql .= "	        AND		  ND.empresa_id = '".$empresa."' ";
			$sql .= "	        AND		  ND.empresa_id = NC.empresa_id ";
			$sql .= "	        AND		  ND.prefijo = NC.prefijo ";
			$sql .= "	        AND		  ND.numero = NC.numero ";
			$sql .= "	        UNION ALL ";
			$sql .= "	        SELECT  ND.prefijo_factura,";
			$sql .= "					        ND.factura_fiscal,";
			$sql .= "					        ND.empresa_id,";
			$sql .= "					        ND.numero,";
			$sql .= "					        ND.prefijo,";
			$sql .= "					        NC.nota_contado_concepto_id,";
			$sql .= "					        'DEBITO' AS tipo, ";
			$sql .= "					        TO_CHAR(ND.fecha_registro ,'DD/MM/YYYY') AS fecha, ";
			$sql .= "					        COALESCE(ND.valor_nota,0) AS valor ";
			$sql .= "	        FROM		notas_contado_debito ND, ";
			$sql .= "					        notas_contado_debito_d NC ";
			$sql .= "	        WHERE	  ND.prefijo_factura = '".$prefijo."' ";
			$sql .= "	        AND		  ND.factura_fiscal = ".$factura_fiscal." ";
			$sql .= "	        AND		  ND.empresa_id = '".$empresa."' ";
			$sql .= "	        AND		  ND.empresa_id = NC.empresa_id ";
			$sql .= "	        AND		  ND.prefijo = NC.prefijo ";
			$sql .= "	        AND		  ND.numero = NC.numero ";
			$sql .= "	      ) AS A, ";
			$sql .= "	      notas_contado_conceptos NC ";
			$sql .= "WHERE  NC.nota_contado_concepto_id = A.nota_contado_concepto_id ";
			
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
  }
?>