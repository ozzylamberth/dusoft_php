<?php
  /******************************************************************************
  * $Id: CarteraC.class.php,v 1.4 2007/08/09 19:44:11 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.4 $ 
	* 
	* @autor Hugo F  Manrique 
  ********************************************************************************/
	class CarteraC
	{
		var $Arreglo = array();
		
		function CarteraC(){}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/		
		function ObtenerReporte($datos)
		{
			$f = explode("/",$datos['fecha']);
			$fecha = $f[2]."-".$f[1]."-".$f[0];
			$facturacion = array();
			$facturacion = $this->ObtenerFacturas($fecha,$datos['empresa_id']);
			$facturacion = $this->ObtenerFacturasAnuladas($fecha,$datos['empresa_id'],$facturacion);
			$facturacion = $this->ObtenerNotasAjuste($fecha,$datos['empresa_id'],$facturacion);
			$facturacion = $this->ObtenerNotasCredito($fecha,$datos['empresa_id'],$facturacion);
			$facturacion = $this->ObtenerNotasDebito($fecha,$datos['empresa_id'],$facturacion);
			$facturacion = $this->ObtenerNotasGlosas($fecha,$datos['empresa_id'],$facturacion);
			$facturacion = $this->ObtenerRecibos($fecha,$datos['empresa_id'],$facturacion);
			
			$anticipos = $this->ObtenerAnticipos($fecha,$datos['empresa_id']);
			
			$terceros = $this->ObtenerNombresTerceros();

			$datosc = array();
			$intervalos = array();
			foreach($facturacion as $key => $cartera)
			{
				foreach($cartera as $keyI => $detalle)
				{
					$periodos = array();
					foreach($detalle as $keyA => $dtl)
					{
							if(!$dtl['debito']) $dtl['debito'] = 0;
							if(!$dtl['credito']) $dtl['credito'] = 0;
							if(!$dtl['recibo']) $dtl['recibo'] = 0;
							if(!$dtl['ajuste']) $dtl['ajuste'] = 0;
							if(!$dtl['glosa']) $dtl['glosa'] = 0;
							
							$dbt = $dtl['total_factura']+$dtl['debito'];
							$cdt = $dtl['ajuste']+$dtl['glosa']+$dtl['credito']+$dtl['anulacion']+$dtl['recibo'];
							/*if(($dbt-$cdt) > 0)
							{*/
								$periodos[$keyA] = $dtl;
								$intervalos[$keyA] = $keyA;
							//}
					}
					if(!empty($periodos))
						$datosc[$terceros[$key][$keyI]."<br>".$key." ".$keyI]['periodos'] = $periodos;
						
					$datosc[$terceros[$key][$keyI]."<br>".$key." ".$keyI]['anticipos'] = $anticipos[$key][$keyI]['saldo'];
				}		
			}
			
			return array("cartera"=>$datosc,"intervalos"=>$intervalos);
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerFacturasAnuladas($fechai,$empresa,$datos)
		{
			$sql  = "SELECT SUM(COALESCE(FF.total_factura,0)) AS valor_anulado, ";
			$sql .= "				FF.tipo_id_tercero, ";
			$sql .= "				FF.tercero_id, ";
			$sql .= "				CASE 	WHEN (FF.fecha_registro::date - '".$fechai."')/30 <= -7 THEN -7 ";
			$sql .= "							WHEN (FF.fecha_registro::date - '".$fechai."')/30 >= 0 THEN 0 ";
			$sql .= "							ELSE (FF.fecha_registro::date - '".$fechai."')/30 END AS intervalo ";
			$sql .= "FROM		fac_facturas FF, ";
			$sql .= "				auditoria_anulacion_fac_facturas AF, ";
			$sql .= "				envios_detalle ED, ";
			$sql .= "				envios EN ";
			$sql .= "WHERE	FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		FF.empresa_id = AF.empresa_id ";
			$sql .= "AND		FF.prefijo = AF.prefijo ";
			$sql .= "AND		FF.factura_fiscal = AF.factura_fiscal ";
			$sql .= "AND		AF.fecha_registro::date <= '".$fechai."' ";
			$sql .= "AND		FF.empresa_id = ED.empresa_id ";
			$sql .= "AND		FF.prefijo = ED.prefijo ";
			$sql .= "AND		FF.factura_fiscal = ED.factura_fiscal ";
			$sql .= "AND		ED.envio_id = EN.envio_id ";
			$sql .= "AND		EN.sw_estado = '1'::bpchar ";
			$sql .= "AND		EN.fecha_radicacion <= '".$fechai."' ";
			//$sql .= "AND		FF.fecha_vencimiento_factura IS NOT NULL ";
			$sql .= "GROUP BY FF.tipo_id_tercero,FF.tercero_id,intervalo ";
			$sql .= "ORDER BY FF.tipo_id_tercero,FF.tercero_id,intervalo ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[2]][$rst->fields[3]]['anulacion'] = $rst->fields[0];
						
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerUsuarioNombre($id)
		{
			$sql  = "SELECT nombre FROM system_usuarios WHERE usuario_id = ".$id;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			if (!$rst->EOF)
			{
				$UsuarioNombre = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $UsuarioNombre;
	 	}
		/************************************************************************************ 
		* Funcion donde se seleccionan el nombre de los terceros que son clientes y 
		* tienen envios radicados para que se pueda filtrar por ellos en una busqueda  
		* 
		* @return array datos de tipo_id_terceros 
		*************************************************************************************/
		function ObtenerNombresTerceros()
		{
			$sql	= "SELECT nombre_tercero, ";
			$sql .= "				tipo_id_tercero, ";
			$sql .= "				tercero_id ";
			$sql .= "FROM		terceros TE ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$nombre = array();
			while(!$rst->EOF)
			{
				$nombre[$rst->fields[1]][$rst->fields[2]] = $rst->fields[0];
				$rst->MoveNext();
		  }
			$rst->Close();
						
			return $nombre;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerRecibos($fechai,$empresa,$datos)
		{
			$sql  = "SELECT COALESCE(SUM(AF.valor_abonado),0) AS valor_abonado, ";
			$sql .= "				FF.tipo_id_tercero,";
			$sql .= "				FF.tercero_id, ";
			$sql .= "				FF.intervalo ";
			$sql .= "FROM	( ";
			$sql .= "				SELECT 	FF.tipo_id_tercero, ";
			$sql .= "								FF.tercero_id, ";
			$sql .= "								FF.empresa_id, ";
			$sql .= "								FF.prefijo, ";
			$sql .= "								FF.factura_fiscal, ";
			$sql .= "								CASE 	WHEN (FF.fecha_registro::date - '".$fechai."')/30 <= -7 THEN -7 ";
			$sql .= "											WHEN (FF.fecha_registro::date - '".$fechai."')/30 >= 0 THEN 0 ";
			$sql .= "											ELSE (FF.fecha_registro::date - '".$fechai."')/30 END AS intervalo ";
			$sql .= "				FROM		fac_facturas FF, ";
			$sql .= "								envios_detalle ED, ";
			$sql .= "								envios EN ";
			$sql .= "				WHERE		FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "				AND			FF.empresa_id = '".$empresa."' ";
			$sql .= "				AND			FF.empresa_id = ED.empresa_id ";
			$sql .= "				AND			FF.prefijo = ED.prefijo ";
			$sql .= "				AND			FF.factura_fiscal = ED.factura_fiscal ";
			$sql .= "				AND			ED.envio_id = EN.envio_id ";
			$sql .= "				AND			EN.sw_estado = '1'::bpchar ";
			$sql .= "				AND			EN.fecha_radicacion <= '".$fechai."' ";
			//$sql .= "				AND			FF.fecha_vencimiento_factura IS NOT NULL ";
			$sql .= "				UNION ALL  ";
			$sql .= "				SELECT 	FF.tipo_id_tercero, ";
			$sql .= "								FF.tercero_id, ";
			$sql .= "								FF.empresa_id, ";
			$sql .= "								FF.prefijo, ";
			$sql .= "								FF.factura_fiscal, ";			
			$sql .= "								CASE 	WHEN (FF.fecha_registro::date - '".$fechai."')/30 <= -7 THEN -7 ";
			$sql .= "											WHEN (FF.fecha_registro::date - '".$fechai."')/30 >= 0 THEN 0 ";
			$sql .= "											ELSE (FF.fecha_registro::date - '".$fechai."')/30 END AS intervalo ";
			$sql .= "				FROM		facturas_externas FF ";
			$sql .= "				WHERE		FF.empresa_id = '".$empresa."' ";
			$sql .= "				AND			FF.total_factura > 0 ";
			$sql .= "				AND			FF.estado IN ('0'::bpchar,'1'::bpchar) ";
			$sql .= "			) AS FF, ";
			$sql .= "			rc_detalle_tesoreria_facturas AF,  ";
			$sql .= "			recibos_caja RC  ";
			$sql .= "WHERE	FF.empresa_id = AF.empresa_id  ";
			$sql .= "AND		FF.prefijo = AF.prefijo_factura  ";
			$sql .= "AND		FF.factura_fiscal = AF.factura_fiscal  ";
			$sql .= "AND		AF.prefijo = RC.prefijo  ";
			$sql .= "AND		AF.centro_utilidad = RC.centro_utilidad  ";
			$sql .= "AND		AF.recibo_caja = RC.recibo_caja  ";
			$sql .= "AND		AF.sw_estado = '0'::bpchar ";
			$sql .= "AND		RC.fecha_ingcaja::date <= '".$fechai."' ";
			$sql .= "GROUP BY FF.tipo_id_tercero,FF.tercero_id,intervalo ";
			$sql .= "ORDER BY FF.tipo_id_tercero,FF.tercero_id,intervalo ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[2]][$rst->fields[3]]['recibo'] = $rst->fields[0];
				
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerNotasCredito($fechai,$empresa,$datos)
		{
			$sql  = "SELECT	COALESCE(SUM(AF.valor_nota),0) AS valor_abonado, ";
			$sql .= "				FF.tipo_id_tercero, ";
			$sql .= "				FF.tercero_id, ";
			$sql .= "				CASE 	WHEN (FF.fecha_registro::date - '".$fechai."')/30 <= -7 THEN -7 ";
			$sql .= "							WHEN (FF.fecha_registro::date - '".$fechai."')/30 >= 0 THEN 0 ";
			$sql .= "							ELSE (FF.fecha_registro::date - '".$fechai."')/30 END AS intervalo ";
			$sql .= "FROM		fac_facturas FF, ";
			$sql .= "				notas_credito AF LEFT JOIN ";
			$sql .= "				notas_credito_auditoria_anulaciones NA ";
			$sql .= "					ON(		NA.empresa_id = Af.empresa_id AND ";
			$sql .= "								NA.prefijo = AF.prefijo AND	";
			$sql .= "								NA.nota_credito_id = AF.nota_credito_id AND ";
			$sql .= "								NA.fecha_registro::date <= '".$fechai."'), ";
			$sql .= "				envios_detalle ED, ";
			$sql .= "				envios EN ";
			$sql .= "WHERE	FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		FF.empresa_id = AF.empresa_id ";
			$sql .= "AND		FF.prefijo = AF.prefijo_factura ";
			$sql .= "AND		FF.factura_fiscal = AF.factura_fiscal ";
			$sql .= "AND		AF.estado = '1'::bpchar ";
			$sql .= "AND		AF.fecha_registro::date <= '".$fechai."' ";
			$sql .= "AND		FF.empresa_id = ED.empresa_id ";
			$sql .= "AND		FF.prefijo = ED.prefijo ";
			$sql .= "AND		FF.factura_fiscal = ED.factura_fiscal ";
			$sql .= "AND		ED.envio_id = EN.envio_id ";
			$sql .= "AND		EN.sw_estado = '1'::bpchar ";
			$sql .= "AND		EN.fecha_radicacion <= '".$fechai."' ";
			//$sql .= "AND		FF.fecha_vencimiento_factura IS NOT NULL ";
			$sql .= "AND		NA.nota_credito_id IS NULL ";
			$sql .= "GROUP BY FF.tipo_id_tercero,FF.tercero_id,intervalo ";
			$sql .= "ORDER BY FF.tipo_id_tercero,FF.tercero_id,intervalo ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[2]][$rst->fields[3]]['credito'] = $rst->fields[0];
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerNotasDebito($fechai,$empresa,$datos)
		{
			$sql  = "SELECT	SUM(COALESCE(AF.valor_nota,0)) AS valor_abonado, ";
			$sql .= "				FF.tipo_id_tercero, ";
			$sql .= "				FF.tercero_id, ";
			$sql .= "				CASE 	WHEN (FF.fecha_registro::date - '".$fechai."')/30 <= -7 THEN -7 ";
			$sql .= "							WHEN (FF.fecha_registro::date - '".$fechai."')/30 >= 0 THEN 0 ";
			$sql .= "							ELSE (FF.fecha_registro::date - '".$fechai."')/30 END AS intervalo ";
			$sql .= "FROM		fac_facturas FF, ";
			$sql .= "				notas_debito AF LEFT JOIN ";
			$sql .= "				notas_debito_auditoria_anulaciones NA ";
			$sql .= "					ON(		NA.empresa_id = Af.empresa_id AND ";
			$sql .= "								NA.prefijo = AF.prefijo AND	";
			$sql .= "								NA.nota_debito_id = AF.nota_debito_id AND ";
			$sql .= "								NA.fecha_registro::date <= '".$fechai."'), ";
			$sql .= "				envios_detalle ED, ";
			$sql .= "				envios EN ";
			$sql .= "WHERE	FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		FF.empresa_id = AF.empresa_id ";
			$sql .= "AND		FF.prefijo = AF.prefijo_factura ";
			$sql .= "AND		FF.factura_fiscal = AF.factura_fiscal ";
			$sql .= "AND		AF.estado = '1'::bpchar ";
			$sql .= "AND		AF.fecha_registro::date <= '".$fechai."' ";
			$sql .= "AND		FF.empresa_id = ED.empresa_id ";
			$sql .= "AND		FF.prefijo = ED.prefijo ";
			$sql .= "AND		FF.factura_fiscal = ED.factura_fiscal ";
			$sql .= "AND		ED.envio_id = EN.envio_id ";
			$sql .= "AND		EN.sw_estado = '1'::bpchar ";
			$sql .= "AND		EN.fecha_radicacion <= '".$fechai."' ";
			//$sql .= "AND		FF.fecha_vencimiento_factura IS NOT NULL ";
			$sql .= "AND		NA.nota_debito_id IS NULL ";
			$sql .= "GROUP BY FF.tipo_id_tercero,FF.tercero_id,intervalo ";
			$sql .= "ORDER BY FF.tipo_id_tercero,FF.tercero_id,intervalo ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[2]][$rst->fields[3]]['debito'] = $rst->fields[0];

				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerNotasGlosas($fechai,$empresa,$datos)
		{
			$sql  = "SELECT SUM(COALESCE(A.valor_glosa,0)) AS valor_glosa, ";
			$sql .= "				A.tipo_id_tercero, ";
			$sql .= "				A.tercero_id, ";
			$sql .= "				A.intervalo ";
			$sql .= "FROM	( ";
			$sql .= "				SELECT 	COALESCE(AF.valor_aceptado,0) AS valor_glosa, ";
			$sql .= "								FF.tipo_id_tercero, ";
			$sql .= "								FF.tercero_id, ";
			$sql .= "								CASE 	WHEN (FF.fecha_registro::date - '".$fechai."')/30 <= -7 THEN -7 ";
			$sql .= "											WHEN (FF.fecha_registro::date - '".$fechai."')/30 >= 0 THEN 0 ";
			$sql .= "											ELSE (FF.fecha_registro::date - '".$fechai."')/30 END AS intervalo ";
			$sql .= "				FROM		fac_facturas FF, ";
			$sql .= "								notas_credito_glosas AF, ";
			$sql .= "								glosas GL, ";
			$sql .= "								envios_detalle ED, ";
			$sql .= "								envios EN ";
			$sql .= "				WHERE		FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "				AND			FF.empresa_id = '".$empresa."' ";
			$sql .= "				AND			FF.empresa_id = GL.empresa_id ";
			$sql .= "				AND			FF.prefijo = GL.prefijo ";
			$sql .= "				AND			FF.factura_fiscal = GL.factura_fiscal ";
			$sql .= "				AND			GL.glosa_id = AF.glosa_id ";
			$sql .= "				AND			GL.sw_estado = '3'::bpchar ";
			$sql .= "				AND			GL.valor_aceptado > 0 ";
			$sql .= "				AND			AF.fecha_registro::date <= '".$fechai."' ";
			$sql .= "				AND			FF.empresa_id = ED.empresa_id ";
			$sql .= "				AND			FF.prefijo = ED.prefijo ";
			$sql .= "				AND			FF.factura_fiscal = ED.factura_fiscal ";
			$sql .= "				AND			ED.envio_id = EN.envio_id ";
			$sql .= "				AND			EN.sw_estado = '1'::bpchar ";
			$sql .= "				AND			EN.fecha_radicacion <= '".$fechai."' ";
			//$sql .= "				AND			FF.fecha_vencimiento_factura IS NOT NULL ";
			$sql .= "				UNION ALL ";
			$sql .= "				SELECT 	COALESCE(AF.valor_aceptado,0) AS valor_glosa, ";
			$sql .= "								FF.tipo_id_tercero, ";
			$sql .= "								FF.tercero_id, ";
			$sql .= "								CASE 	WHEN (FF.fecha_registro::date - '".$fechai."')/30 <= -7 THEN -7 ";
			$sql .= "											WHEN (FF.fecha_registro::date - '".$fechai."')/30 >= 0 THEN 0 ";
			$sql .= "											ELSE (FF.fecha_registro::date - '".$fechai."')/30 END AS intervalo ";
			$sql .= "				FROM		facturas_externas FF, ";
			$sql .= "								notas_credito_glosas AF, ";
			$sql .= "								glosas GL ";
			$sql .= "				WHERE		FF.empresa_id = '".$empresa."' ";
			$sql .= "				AND			GL.glosa_id = AF.glosa_id ";
			$sql .= "				AND			FF.empresa_id = GL.empresa_id ";
			$sql .= "				AND			FF.prefijo = GL.prefijo ";
			$sql .= "				AND			FF.factura_fiscal = GL.factura_fiscal ";
			$sql .= "				AND			GL.sw_estado = '3'::bpchar ";
			$sql .= "				AND			GL.valor_aceptado > 0 ";
			$sql .= "				AND			AF.fecha_registro::date <= '".$fechai."' ";
			$sql .= "				AND			FF.total_factura > 0 ";
			$sql .= "				AND			FF.estado IN ('0'::bpchar,'1'::bpchar) ";
			$sql .= "				) AS A ";
			$sql .= "GROUP BY A.tipo_id_tercero,A.tercero_id,A.intervalo ";
			$sql .= "ORDER BY A.tipo_id_tercero,A.tercero_id,A.intervalo ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[2]][$rst->fields[3]]['glosa'] = $rst->fields[0];

				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerNotasGlosasInsumos($fechai,$empresa,$datos)
		{
			$sql  = "SELECT SUM(AF.valor_aceptado) AS valor_glosa, ";
			$sql .= "				FF.tipo_id_tercero, ";
			$sql .= "				FF.tercero_id, ";
			$sql .= "				CASE 	WHEN (FF.fecha_registro::date - '".$fechai."')/30 <= -7 THEN -7 ";
			$sql .= "							WHEN (FF.fecha_registro::date - '".$fechai."')/30 >= 0 THEN 0 ";
			$sql .= "							ELSE (FF.fecha_registro::date - '".$fechai."')/30 END AS intervalo ";
			$sql .= "FROM		fac_facturas FF, ";
			$sql .= "				notas_credito_glosas_detalle_inventarios AF, ";
			$sql .= "				glosas GL ";
			$sql .= "WHERE	FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		FF.empresa_id = GL.empresa_id ";
			$sql .= "AND		FF.prefijo = GL.prefijo ";
			$sql .= "AND		FF.factura_fiscal = GL.factura_fiscal ";
			$sql .= "AND		GL.glosa_id = AF.glosa_id ";
			$sql .= "AND		AF.valor_aceptado > 0 ";
			$sql .= "AND		AF.fecha_registro::date <= '".$fechai."' ";
			//$sql .= "AND		FF.fecha_vencimiento_factura IS NOT NULL ";
			$sql .= "GROUP BY FF.tipo_id_tercero,FF.tercero_id,intervalo ";
			$sql .= "ORDER BY FF.tipo_id_tercero,FF.tercero_id,intervalo ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			$glosa = array(); 
			while(!$rst->EOF)
			{
				$glosa = $rst->GetRowAssoc($ToUpper = false);
				
				if(!$glosa['valor_glosa']) $glosa['valor_glosa'] = 0;
				
				if(!$datos[$glosa['tipo_id_tercero']][$glosa['tercero_id']][$glosa['intervalo']]['glosa'])
					$datos[$glosa['tipo_id_tercero']][$glosa['tercero_id']][$glosa['intervalo']]['glosa'] = 0;
					
				$datos[$glosa['tipo_id_tercero']][$glosa['tercero_id']][$glosa['intervalo']]['glosa'] += $glosa['valor_glosa'];
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerNotasGlosasCargos($fechai,$empresa,$datos)
		{
			$sql  = "SELECT SUM(AF.valor_aceptado) AS valor_glosa, ";
			$sql .= "				FF.tipo_id_tercero, ";
			$sql .= "				FF.tercero_id, ";
			$sql .= "				CASE 	WHEN (FF.fecha_registro::date - '".$fechai."')/30 <= -7 THEN -7 ";
			$sql .= "							WHEN (FF.fecha_registro::date - '".$fechai."')/30 >= 0 THEN 0 ";
			$sql .= "							ELSE (FF.fecha_registro::date - '".$fechai."')/30 END AS intervalo ";
			$sql .= "FROM		fac_facturas FF, ";
			$sql .= "				notas_credito_glosas_detalle_cargos AF, ";
			$sql .= "				glosas GL ";
			$sql .= "WHERE	FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		FF.empresa_id = GL.empresa_id ";
			$sql .= "AND		FF.prefijo = GL.prefijo ";
			$sql .= "AND		FF.factura_fiscal = GL.factura_fiscal ";
			$sql .= "AND		GL.glosa_id = AF.glosa_id ";
			$sql .= "AND		AF.valor_aceptado > 0 ";
			$sql .= "AND		AF.fecha_registro::date <= '".$fechai."' ";
			//$sql .= "AND		FF.fecha_vencimiento_factura IS NOT NULL ";
			$sql .= "GROUP BY FF.tipo_id_tercero,FF.tercero_id,intervalo ";
			$sql .= "ORDER BY FF.tipo_id_tercero,FF.tercero_id,intervalo ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			$glosa = array(); 
			while(!$rst->EOF)
			{
				$glosa = $rst->GetRowAssoc($ToUpper = false);
				if(!$glosa['valor_glosa']) $glosa['valor_glosa'] = 0;
				
				if(!$datos[$glosa['tipo_id_tercero']][$glosa['tercero_id']][$glosa['intervalo']]['glosa'])
					$datos[$glosa['tipo_id_tercero']][$glosa['tercero_id']][$glosa['intervalo']]['glosa'] = 0;

				$datos[$glosa['tipo_id_tercero']][$glosa['tercero_id']][$glosa['intervalo']]['glosa'] += $glosa['valor_glosa'];
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerNotasAjuste($fechai,$empresa,$datos)
		{
			$sql  = "SELECT	SUM(COALESCE(A.valor_abonado,0)) AS valor_abonado, ";
			$sql .= "				A.tipo_id_tercero,";
			$sql .= "				A.tercero_id, ";
			$sql .= "				A.intervalo ";
			$sql .= "FROM	(	";
			$sql .= "							SELECT	COALESCE(AF.valor_abonado,0) AS valor_abonado, ";
			$sql .= "										 	FF.tipo_id_tercero, ";
			$sql .= "											FF.tercero_id, ";
			$sql .= "											CASE 	WHEN (FF.fecha_registro::date - '".$fechai."')/30 <= -7 THEN -7 ";
			$sql .= "														WHEN (FF.fecha_registro::date - '".$fechai."')/30 >= 0 THEN 0 ";
			$sql .= "														ELSE (FF.fecha_registro::date - '".$fechai."')/30 END AS intervalo ";
			$sql .= "							FROM		fac_facturas FF, ";
			$sql .= "											notas_credito_ajuste_detalle_facturas AF, ";
			$sql .= "											notas_credito_ajuste RC LEFT JOIN ";
			$sql .= "											notas_credito_ajuste_detalle_conceptos NC ";
			$sql .= "											ON(	NC.empresa_id = RC.empresa_id AND ";
			$sql .= "									 				NC.prefijo = RC.prefijo AND ";
			$sql .= "										 			NC.nota_credito_ajuste = RC.nota_credito_ajuste AND ";
			$sql .= "									 				NC.concepto_id = 245 ), ";
			$sql .= "											envios_detalle ED, ";
			$sql .= "											envios EN ";
			$sql .= "							WHERE		FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "							AND			FF.empresa_id = '".$empresa."' ";
			$sql .= "							AND			FF.empresa_id = AF.empresa_id ";
			$sql .= "							AND			FF.prefijo = AF.prefijo_factura ";
			$sql .= "							AND			FF.factura_fiscal = AF.factura_fiscal ";
			$sql .= "							AND			AF.empresa_id = RC.empresa_id ";
			$sql .= "							AND			AF.prefijo = RC.prefijo ";
			$sql .= "							AND			NC.concepto_id IS NULL ";
			$sql .= "							AND 		AF.nota_credito_ajuste = RC.nota_credito_ajuste ";
			//$sql .= "							AND			FF.fecha_vencimiento_factura IS NOT NULL ";
			$sql .= "							AND			RC.estado != '0'::bpchar ";
			$sql .= "							AND			RC.fecha_registro::date <= '".$fechai."' ";
			$sql .= "							AND			FF.empresa_id = ED.empresa_id ";
			$sql .= "							AND			FF.prefijo = ED.prefijo ";
			$sql .= "							AND			FF.factura_fiscal = ED.factura_fiscal ";
			$sql .= "							AND			ED.envio_id = EN.envio_id ";
			$sql .= "							AND			EN.sw_estado = '1'::bpchar ";
			$sql .= "							AND			EN.fecha_radicacion <= '".$fechai."' ";
			$sql .= "							UNION ALL ";
			$sql .= "							SELECT	COALESCE(AF.valor_abonado,0) AS valor_abonado, ";
			$sql .= "										 	FF.tipo_id_tercero, ";
			$sql .= "											FF.tercero_id, ";
			$sql .= "											CASE 	WHEN (FF.fecha_registro::date - '".$fechai."')/30 <= -7 THEN -7 ";
			$sql .= "														WHEN (FF.fecha_registro::date - '".$fechai."')/30 >= 0 THEN 0 ";
			$sql .= "														ELSE (FF.fecha_registro::date - '".$fechai."')/30 END AS intervalo ";
			$sql .= "							FROM		facturas_externas FF, ";
			$sql .= "											notas_credito_ajuste_detalle_facturas AF,";
			$sql .= "											notas_credito_ajuste RC";
			$sql .= "							WHERE		FF.empresa_id = '".$empresa."'";
			$sql .= "							AND			FF.empresa_id = AF.empresa_id";
			$sql .= "							AND			FF.prefijo = AF.prefijo_factura";
			$sql .= "							AND			FF.factura_fiscal = AF.factura_fiscal";
			$sql .= "							AND			AF.empresa_id = RC.empresa_id";
			$sql .= "							AND			AF.prefijo = RC.prefijo";
			$sql .= "							AND 		AF.nota_credito_ajuste = RC.nota_credito_ajuste";
			$sql .= "							AND			RC.estado != '0'::bpchar ";
			$sql .= "							AND			RC.fecha_registro::date <= '".$fechai."' ";
			$sql .= "							AND			FF.total_factura > 0 ";
			$sql .= "							AND			FF.estado IN ('0'::bpchar,'1'::bpchar) ";
			$sql .= "						) AS A ";
			$sql .= "GROUP BY A.tipo_id_tercero,A.tercero_id,A.intervalo ";
			$sql .= "ORDER BY A.tipo_id_tercero,A.tercero_id,A.intervalo ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[2]][$rst->fields[3]]['ajuste'] = $rst->fields[0];
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerFacturas($fechai,$empresa)
		{
			$sql  = "SELECT SUM(COALESCE(A.total_factura,0)) AS total_factura, ";
			$sql .= "				A.tipo_id_tercero, ";
			$sql .= "				A.tercero_id, ";
			$sql .= "				A.intervalo ";
			$sql .= "FROM		( ";
			$sql .= "					SELECT 	FF.total_factura-COALESCE(FF.retencion_fuente,0) AS total_factura, ";
			$sql .= "									FF.tipo_id_tercero, ";
			$sql .= "									FF.tercero_id, ";
			$sql .= "									CASE 	WHEN (FF.fecha_registro::date - '".$fechai."')/30 <= -7 THEN -7 ";
			$sql .= "												WHEN (FF.fecha_registro::date - '".$fechai."')/30 >= 0 THEN 0 ";
			$sql .= "												ELSE (FF.fecha_registro::date - '".$fechai."')/30 END AS intervalo ";
			$sql .= "					FROM		fac_facturas FF, ";
			$sql .= "									envios_detalle ED, ";
			$sql .= "									envios EN ";
			$sql .= "					WHERE		FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "					AND			FF.fecha_registro::date <= '".$fechai."' ";
			$sql .= "					AND			FF.empresa_id = '".$empresa."' ";
			$sql .= "					AND			FF.empresa_id = ED.empresa_id ";
			$sql .= "					AND			FF.prefijo = ED.prefijo ";
			$sql .= "					AND			FF.factura_fiscal = ED.factura_fiscal ";
			$sql .= "					AND			ED.envio_id = EN.envio_id ";
			$sql .= "					AND			EN.sw_estado = '1'::bpchar ";
			$sql .= "					AND			EN.fecha_radicacion <= '".$fechai."' ";
			//$sql .= "					AND			FF.fecha_vencimiento_factura IS NOT NULL ";
			$sql .= "					UNION ALL ";
			$sql .= "					SELECT 	FF.total_factura AS total_factura, ";
			$sql .= "									FF.tipo_id_tercero, ";
			$sql .= "									FF.tercero_id, ";
			$sql .= "									CASE 	WHEN (FF.fecha_registro::date - '".$fechai."')/30 <= -7 THEN -7 ";
			$sql .= "												WHEN (FF.fecha_registro::date - '".$fechai."')/30 >= 0 THEN 0 ";
			$sql .= "												ELSE (FF.fecha_registro::date - '".$fechai."')/30 END AS intervalo ";
			$sql .= "					FROM		facturas_externas FF ";
			$sql .= "					WHERE		FF.fecha_registro::date <= '".$fechai."' ";
			$sql .= "					AND			FF.empresa_id = '".$empresa."' ";
			$sql .= "					AND			FF.total_factura > 0 ";
			$sql .= "					AND			FF.estado IN ('0'::bpchar,'1'::bpchar) ";
			$sql .= "				) AS A ";
			$sql .= "GROUP BY A.tipo_id_tercero,A.tercero_id,A.intervalo ";
			$sql .= "ORDER BY A.tipo_id_tercero,A.tercero_id,A.intervalo ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[2]][$rst->fields[3]] =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerAnticipos($fechai,$empresa)
		{
			$sql  = "SELECT CA.saldo, ";
			$sql .= "				CA.tipo_id_tercero, ";
			$sql .= "				CA.tercero_id ";
			$sql .= "FROM		rc_control_anticipos CA ";
			$sql .= "WHERE	CA.empresa_id = '".$empresa."' ";
			$sql .= "AND		CA.saldo > 0 ";
			
			$datos = array();
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[2]] =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;			
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
				echo $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
?>