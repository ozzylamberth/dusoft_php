<?php
  /******************************************************************************
  * $Id: CarteraResumen.class.php,v 1.3 2007/08/09 19:44:11 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.3 $ 
	* 
	* @autor Hugo F  Manrique 
  ********************************************************************************/
	class CarteraResumen
	{
		var $Arreglo = array();
		
		function CarteraResumen(){}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/		
		function ObtenerReporte($datos)
		{
			$fechaf = date("Y-m-d", mktime(0, 0, 0,(intval($datos['mes'])+1), 0,date("Y")));
			$fechai = date("Y-m-d", mktime(0, 0, 0,(intval($datos['mes'])), 0,date("Y")));
			
			$carterai = $carteraf = array();
			
			$carterai['factura'] = $this->ObtenerFacturas($fechai,null,$datos['empresa_id']);
			$carterai['anulacion'] = $this->ObtenerFacturasAnuladas($fechai,$datos['empresa_id']);
			$carterai['ajuste']  = $this->ObtenerNotasAjuste($fechai,null,$datos['empresa_id']);
			$carterai['credito'] = $this->ObtenerNotasCredito($fechai,null,$datos['empresa_id']);
			$carterai['debito']  = $this->ObtenerNotasDebito($fechai,null,$datos['empresa_id']);
			$carterai['glosas']  = $this->ObtenerNotasGlosas($fechai,null,$datos['empresa_id']);
			//$carterai['glosas'] += $this->ObtenerNotasGlosasCargos($fechai,null,$datos['empresa_id']);
			//$carterai['glosas'] += $this->ObtenerNotasGlosasInsumos($fechai,null,$datos['empresa_id']);
			
			$carterai['recibo']  = $this->ObtenerRecibos($fechai,null,$datos['empresa_id']);
			
			$fechai = date("Y")."-".$datos['mes']."-01";
			$carteraf['factura'] = $this->ObtenerFacturas($fechai,$fechaf,$datos['empresa_id']);
			$carteraf['anulacion'] = $this->ObtenerFacturasAnuladas($fechai,$datos['empresa_id'],$fechaf);
			$carteraf['ajuste']  = $this->ObtenerNotasAjuste($fechai,$fechaf,$datos['empresa_id']);
			$carteraf['credito'] = $this->ObtenerNotasCredito($fechai,$fechaf,$datos['empresa_id']);
			$carteraf['debito']  = $this->ObtenerNotasDebito($fechai,$fechaf,$datos['empresa_id']);
			$carteraf['glosas']  = $this->ObtenerNotasGlosas($fechai,$fechaf,$datos['empresa_id']);
			//$carteraf['glosas'] += $this->ObtenerNotasGlosasCargos($fechai,$fechaf,$datos['empresa_id']);
			//$carteraf['glosas'] += $this->ObtenerNotasGlosasInsumos($fechai,$fechaf,$datos['empresa_id']);
			
			$carteraf['recibo']  = $this->ObtenerRecibos($fechai,$fechaf,$datos['empresa_id']);
			$carteraf['anticipo']  = $this->ObtenerAnticipos($fechai,$fechaf,$datos['empresa_id']);
			
			return array("inicial"=>$carterai,"final"=>$carteraf);		
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerFacturasAnuladas($fechai,$empresa,$fechaf= null)
		{
			$sql  = "SELECT SUM(COALESCE(FF.total_factura,0)) AS valor_anulado ";
			$sql .= "FROM		fac_facturas FF, ";
			$sql .= "				auditoria_anulacion_fac_facturas AF ";
			$sql .= "WHERE	FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		FF.empresa_id = AF.empresa_id ";
			$sql .= "AND		FF.prefijo = AF.prefijo ";
			$sql .= "AND		FF.factura_fiscal = AF.factura_fiscal ";
			if(!$fechaf)
				$sql .= "AND		AF.fecha_registro::date <= '".$fechai."' ";
			else
			{
				$sql .= "AND		AF.fecha_registro::date >= '".$fechai."' ";
				$sql .= "AND		AF.fecha_registro::date <= '".$fechaf."' ";
			}	
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos['valor_anulado'];
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
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerRecibos($fechai,$fechaf= null,$empresa)
		{
			$sql  = "SELECT SUM(COALESCE(AF.valor_abonado,0)) AS valor_abonado ";
			$sql .= "FROM	( ";
			$sql .= "				SELECT	FF.prefijo,FF.factura_fiscal,FF.empresa_id ";
			$sql .= "				FROM		fac_facturas FF ";
			$sql .= "				WHERE		FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "				AND			FF.empresa_id = '".$empresa."' ";
			$sql .= "				UNION ALL  ";
			$sql .= "				SELECT	FF.prefijo,FF.factura_fiscal,FF.empresa_id ";
			$sql .= "				FROM		facturas_externas FF ";
			$sql .= "				WHERE		FF.empresa_id = '".$empresa."' ";
			//$sql .= "				AND			FF.total_factura > 0 ";
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
			$sql .= "AND		RC.estado = '2'::bpchar ";
			$sql .= "AND		RC.sw_recibo_tesoreria = '1' ";
			if(!$fechaf)
				$sql .= "AND		RC.fecha_ingcaja::date <= '".$fechai."' ";
			else
			{
				$sql .= "AND		RC.fecha_ingcaja::date >= '".$fechai."' ";
				$sql .= "AND		RC.fecha_ingcaja::date <= '".$fechaf."' ";
			}	
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos['valor_abonado'];
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerNotasCredito($fechai,$fechaf= null,$empresa)
		{
			$sql  = "SELECT	SUM(COALESCE(AF.valor_nota,0)) AS valor_abonado ";
			$sql .= "FROM		fac_facturas FF, ";
			$sql .= "				notas_credito AF LEFT JOIN ";
			$sql .= "				notas_credito_auditoria_anulaciones NA ";
			$sql .= "					ON(		NA.empresa_id = Af.empresa_id AND ";
			$sql .= "								NA.prefijo = AF.prefijo AND	";
			$sql .= "								NA.nota_credito_id = AF.nota_credito_id ";
			if(!$fechaf)
				$sql .= "								AND		NA.fecha_registro::date <= '".$fechai."') ";
			else
			{
				$sql .= "								AND		NA.fecha_registro::date >= '".$fechai."' ";
				$sql .= "								AND		NA.fecha_registro::date <= '".$fechaf."') ";
			}	
			$sql .= "WHERE	FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		FF.empresa_id = AF.empresa_id ";
			$sql .= "AND		FF.prefijo = AF.prefijo_factura ";
			$sql .= "AND		FF.factura_fiscal = AF.factura_fiscal ";
			$sql .= "AND		AF.estado = '1'::bpchar ";
			$sql .= "AND		NA.nota_credito_id IS NULL ";
			if(!$fechaf)
				$sql .= "AND		AF.fecha_registro::date <= '".$fechai."' ";
			else
			{
				$sql .= "AND		AF.fecha_registro::date >= '".$fechai."' ";
				$sql .= "AND		AF.fecha_registro::date <= '".$fechaf."' ";
			}	
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos['valor_abonado'];
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerNotasDebito($fechai,$fechaf= null,$empresa)
		{
			$sql  = "SELECT	SUM(COALESCE(AF.valor_nota,0)) AS valor_abonado ";
			$sql .= "FROM		fac_facturas FF, ";
			$sql .= "				notas_debito AF LEFT JOIN ";
			$sql .= "				notas_debito_auditoria_anulaciones NA ";
			$sql .= "					ON(		NA.empresa_id = Af.empresa_id AND ";
			$sql .= "								NA.prefijo = AF.prefijo AND	";
			$sql .= "								NA.nota_debito_id = AF.nota_debito_id ";
			if(!$fechaf)
				$sql .= "								AND		NA.fecha_registro::date <= '".$fechai."') ";
			else
			{
				$sql .= "								AND		NA.fecha_registro::date >= '".$fechai."' ";
				$sql .= "								AND		NA.fecha_registro::date <= '".$fechaf."') ";
			}	
			$sql .= "WHERE	FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		FF.empresa_id = AF.empresa_id ";
			$sql .= "AND		FF.prefijo = AF.prefijo_factura ";
			$sql .= "AND		FF.factura_fiscal = AF.factura_fiscal ";
			$sql .= "AND		AF.estado = '1'::bpchar ";
			$sql .= "AND		NA.nota_debito_id IS NULL ";
			if(!$fechaf)
				$sql .= "AND		AF.fecha_registro::date <= '".$fechai."' ";
			else
			{
				$sql .= "AND		AF.fecha_registro::date >= '".$fechai."' ";
				$sql .= "AND		AF.fecha_registro::date <= '".$fechaf."' ";
			}	
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos['valor_abonado'];
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerNotasGlosas($fechai,$fechaf= null,$empresa)
		{
			$sql  = "SELECT SUM(COALESCE(A.valor_glosa,0)) AS valor_glosa ";
			$sql .= "FROM	( ";
			$sql .= "				SELECT 	COALESCE(AF.valor_aceptado,0) AS valor_glosa ";
			$sql .= "				FROM		fac_facturas FF, ";
			$sql .= "								notas_credito_glosas AF, ";
			$sql .= "								glosas GL ";
			$sql .= "				WHERE		FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "				AND			FF.empresa_id = '".$empresa."' ";
			$sql .= "				AND			FF.empresa_id = GL.empresa_id ";
			$sql .= "				AND			FF.prefijo = GL.prefijo ";
			$sql .= "				AND			FF.factura_fiscal = GL.factura_fiscal ";
			$sql .= "				AND			GL.glosa_id = AF.glosa_id ";
			$sql .= "				AND			GL.sw_estado = '3'::bpchar ";
			if(!$fechaf)
				$sql .= "				AND			AF.fecha_registro::date <= '".$fechai."' ";
			else
			{
				$sql .= "				AND			AF.fecha_registro::date >= '".$fechai."' ";
				$sql .= "				AND			AF.fecha_registro::date <= '".$fechaf."' ";
			}
			$sql .= "				UNION ALL ";
			$sql .= "				SELECT 	COALESCE(AF.valor_aceptado,0) AS valor_glosa ";
			$sql .= "				FROM		facturas_externas FF, ";
			$sql .= "								notas_credito_glosas AF, ";
			$sql .= "								glosas GL ";
			$sql .= "				WHERE		FF.empresa_id = '".$empresa."' ";
			$sql .= "				AND			GL.glosa_id = AF.glosa_id ";
			$sql .= "				AND			FF.empresa_id = GL.empresa_id ";
			$sql .= "				AND			FF.prefijo = GL.prefijo ";
			$sql .= "				AND			FF.factura_fiscal = GL.factura_fiscal ";
			$sql .= "				AND			GL.sw_estado = '3'::bpchar ";
			$sql .= "				AND			FF.total_factura > 0 ";

			if(!$fechaf)
				$sql .= "				AND			AF.fecha_registro::date <= '".$fechai."' ";
			else
			{
				$sql .= "				AND			AF.fecha_registro::date >= '".$fechai."' ";
				$sql .= "				AND			AF.fecha_registro::date <= '".$fechaf."' ";
			}
			$sql .= "					AND			FF.estado IN ('0'::bpchar,'1'::bpchar) ";
			$sql .= "				) AS A ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos['valor_glosa'];
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerNotasAjuste($fechai,$fechaf= null,$empresa)
		{
			$sql  = "SELECT	SUM(COALESCE(A.valor_abonado,0)) AS valor_abonado ";
			$sql .= "				FROM	(	";
			$sql .= "							SELECT	COALESCE(AF.valor_abonado,0) AS valor_abonado ";
			$sql .= "							FROM		fac_facturas FF, ";
			$sql .= "											notas_credito_ajuste_detalle_facturas AF, ";
			$sql .= "											notas_credito_ajuste RC LEFT JOIN ";
			$sql .= "											notas_credito_ajuste_detalle_conceptos NC ";
			$sql .= "											ON(	NC.empresa_id = RC.empresa_id AND ";
			$sql .= "									 				NC.prefijo = RC.prefijo AND ";
			$sql .= "										 			NC.nota_credito_ajuste = RC.nota_credito_ajuste AND ";
			$sql .= "									 				NC.concepto_id = 245 ) ";
			$sql .= "							WHERE		FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "							AND			FF.empresa_id = '".$empresa."' ";
			$sql .= "							AND			FF.empresa_id = AF.empresa_id ";
			$sql .= "							AND			FF.prefijo = AF.prefijo_factura ";
			$sql .= "							AND			FF.factura_fiscal = AF.factura_fiscal ";
			$sql .= "							AND			AF.empresa_id = RC.empresa_id ";
			$sql .= "							AND			AF.prefijo = RC.prefijo ";
			$sql .= "							AND			NC.concepto_id IS NULL ";
			$sql .= "							AND 		AF.nota_credito_ajuste = RC.nota_credito_ajuste ";
			$sql .= "							AND			RC.estado != '0'::bpchar ";
			if(!$fechaf)
				$sql .= "					AND			RC.fecha_registro::date <= '".$fechai."' ";
			else
			{
				$sql .= "					AND			RC.fecha_registro::date >= '".$fechai."' ";
				$sql .= "					AND			RC.fecha_registro::date <= '".$fechaf."' ";
			}
			$sql .= "							UNION ALL ";
			$sql .= "							SELECT	COALESCE(AF.valor_abonado,0) AS valor_abonado ";
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
			$sql .= "							AND			FF.total_factura > 0 ";
			$sql .= "							AND			FF.estado IN ('0'::bpchar,'1'::bpchar) ";
			if(!$fechaf)
				$sql .= "					AND			RC.fecha_registro::date <= '".$fechai."' ";
			else
			{
				$sql .= "					AND			RC.fecha_registro::date >= '".$fechai."' ";
				$sql .= "					AND			RC.fecha_registro::date <= '".$fechaf."' ";
			}
			$sql .= "						) AS A";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos['valor_abonado'];
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerFacturas($fechai,$fechaf= null,$empresa)
		{
			$sql  = "SELECT SUM(COALESCE(A.total_factura,0)) AS total_factura ";
			$sql .= "FROM		( ";
			$sql .= "					SELECT 	FF.total_factura-COALESCE(FF.retencion_fuente,0) AS total_factura ";
			$sql .= "					FROM		fac_facturas FF ";
			$sql .= "					WHERE		FF.sw_clase_factura = '1'::bpchar ";
			if(!$fechaf)
				$sql .= "					AND			FF.fecha_registro::date <= '".$fechai."' ";
			else
			{
				$sql .= "					AND			FF.fecha_registro::date >= '".$fechai."' ";
				$sql .= "					AND			FF.fecha_registro::date <= '".$fechaf."' ";
			}
			$sql .= "					AND			FF.empresa_id = '".$empresa."' ";
			$sql .= "					UNION ALL ";
			$sql .= "					SELECT 	FF.total_factura AS total_factura ";
			$sql .= "					FROM		facturas_externas FF ";
			$sql .= "					WHERE		FF.empresa_id = '".$empresa."' ";
			$sql .= "					AND			FF.total_factura > 0 ";
			if(!$fechaf)
				$sql .= "					AND			FF.fecha_registro::date <= '".$fechai."' ";
			else
			{
				$sql .= "					AND			FF.fecha_registro::date >= '".$fechai."' ";
				$sql .= "					AND			FF.fecha_registro::date <= '".$fechaf."' ";
			}
			$sql .= "					AND			FF.estado IN ('0'::bpchar,'1'::bpchar) ";
			$sql .= "				) AS A ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos['total_factura'];
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerNotasGlosasInsumos($fechai,$fechaf= null,$empresa)
		{
			$sql  = "SELECT COALESCE(SUM(AF.valor_aceptado),0) AS valor_glosa ";
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
			if(!$fechaf)
				$sql .= "AND		AF.fecha_registro::date <= '".$fechai."' ";
			else
			{
				$sql .= "AND		AF.fecha_registro::date >= '".$fechai."' ";
				$sql .= "AND		AF.fecha_registro::date <= '".$fechaf."' ";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos['valor_glosa'];
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerNotasGlosasCargos($fechai,$fechaf= null,$empresa)
		{
			$sql  = "SELECT COALESCE(SUM(AF.valor_aceptado),0) AS valor_glosa ";
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
			if(!$fechaf)
				$sql .= "AND		AF.fecha_registro::date <= '".$fechai."' ";
			else
			{
				$sql .= "AND		AF.fecha_registro::date >= '".$fechai."' ";
				$sql .= "AND		AF.fecha_registro::date <= '".$fechaf."' ";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos['valor_glosa'];			
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function ObtenerAnticipos($fechai,$fechaf= null,$empresa)
		{
			$sql  = "SELECT COALESCE(SUM(CA.saldo),0) AS valor_anticipo ";
			$sql .= "FROM		rc_control_anticipos CA ";
			$sql .= "WHERE	CA.empresa_id = '".$empresa."' ";
			$sql .= "AND		CA.saldo > 0 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos['valor_anticipo'];			
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