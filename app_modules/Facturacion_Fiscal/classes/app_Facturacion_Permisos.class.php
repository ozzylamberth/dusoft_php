<?php
  /******************************************************************************
  * $Id: app_Facturacion_Permisos.class.php,v 1.8 2011/02/23 21:54:04 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.8 $ 
	* 
	* @autor Hugo F  Manrique 
  * Proposito del Archivo:	Manejo logico de la logica del modulo de 
	*													anulacion de facturas
  ********************************************************************************/
	class app_Facturacion_Permisos
	{
		function app_Facturacion_Permisos(){}
		/**********************************************************************************
		* Funcion donde se evalua si el usuario que esta accediendo al modulo tiene o no
		* permisos
		* 
		* @return boolean Indica si tiene permisos true, o no false
		***********************************************************************************/
		function ObtenerPermisosFacturacion()
		{
			$sql .= "SELECT DISTINCT CU.centro_utilidad, ";
			$sql .= "				CU.descripcion as centro, ";
			$sql .= "				EM.empresa_id, ";
			$sql .= "				EM.razon_social ";
			$sql .= "FROM 	userpermisos_facturacion UF, ";
			$sql .= "				empresas EM, ";
			$sql .= "				centros_utilidad CU ";
			$sql .= "WHERE 	UF.usuario_id = ".UserGetUID()." ";
			$sql .= "AND		UF.empresa_id = EM.empresa_id ";
			$sql .= "AND		UF.empresa_id = CU.empresa_id ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$empresas = array();
			
			//while (!$rst->EOF)
			
			while($data = $rst->FetchRow())
			{
				//$empresas[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$empresas[$data[3]][$data[1]]=$data;
				//$rst->MoveNext();
		  }
			$rst->Close();
			
			return $empresas;
		}
		/**********************************************************************************
		* Funcion donde se seleccionan los tipos de documentos de la base de datos, 
		* su descripcion el documento asignado y el prefijo asociado
		*
		* @params	char $empresa Empresa relacionada a los documentos
		* @params char $tipodc	Tipo de documento que servira como filtro
		* @return array datos de los documentos
		***********************************************************************************/
		function ObtenerTiposDocumentos($empresa,$tipodc)
		{
			$doc = "";
			$datos = array();
			
			$sql .= "SELECT DC.documento_id, ";
			$sql .= "				DC.descripcion ";
			$sql .= "FROM 	userpermisos_facturacion UF, ";
			$sql .= "		documentos DC ";
			$sql .= "WHERE 	UF.usuario_id = ".UserGetUID()." ";
			$sql .= "AND		UF.empresa_id = '".$empresa."' ";
			$sql .= "AND		DC.empresa_id = UF.empresa_id ";
			$sql .= "AND		DC.documento_id = UF.documento_id ";
			$sql .= "AND		DC.sw_estado IN ('0','1') ";
			$sql .= "ORDER BY 2 ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			$i=0;
			$todos = "";
			while (!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				
				if($doc != $datos[$rst->fields[1]]['descripcion'] )
				{
					if($i > 0)
					{
						$cadena = trim($cadena);
						$cadena = str_replace(" ",",",$cadena);
						$datos[$doc]['documento_id'] = $cadena;
						$todos .= $cadena." ";
						$cadena = "";
					}
					$doc = $rst->fields[1];
				}
				$cadena .= $rst->fields[0]." ";					
				$rst->MoveNext();
				$i++;
		  }
			
			$cadena = trim($cadena);
			$cadena = str_replace(" ",",",$cadena);
			$datos[$doc]['documento_id'] = $cadena;
			
			$todos .= $cadena;
			$todos = trim($todos);
			$todos = str_replace(" ",",",$todos);
			//$datos["TODAS LAS FACTURAS CON PERMISOS"]['documento_id'] = $todos;
			//$datos["TODAS LAS FACTURAS CON PERMISOS"]['descripcion'] = "TODAS LAS FACTURAS CON PERMISOS";
			
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function DatosFactura($emp,$tipos,$punto)
		{	
			if(!$punto)
			{
				$sql .= "SELECT PF.prefijo_fac_contado,  ";
				$sql .= "				PF.prefijo_fac_credito,  ";
				$sql .= "				PF.punto_facturacion_id ";
				$sql .= "FROM	 	puntos_facturacion PF ";
				$sql .= "WHERE	PF.empresa_id = '".$emp."' ";
				if($tipos)
					$sql .= "AND		PF.prefijo_fac_contado IN ('".$tipos."') ";
      
				if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
				$facturacion = array();
				while (!$rst->EOF)
				{
					if($tipos)
						$facturacion = $rst->GetRowAssoc($ToUpper = false);
					else
						$facturacion[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}			
				$rst->Close();
			
				if(sizeof($facturacion) == 0)
				{
					$sql  = "SELECT PF.prefijo_fac_credito, ";
					$sql .= "				PF.prefijo_fac_contado, ";
					$sql .= "				PF.punto_facturacion_id ";
					$sql .= "FROM	 	puntos_facturacion PF ";
					$sql .= "WHERE	PF.empresa_id = '".$emp."' ";
					if($tipos)
						$sql .= "AND		PF.prefijo_fac_credito IN ('".$tipos."') ";
		
					if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
				
					while (!$rst->EOF)
					{
						if($tipos)
							$facturacion = $rst->GetRowAssoc($ToUpper = false);
						else
							$facturacion[] = $rst->GetRowAssoc($ToUpper = false);
							$rst->MoveNext();
					}
				
					$rst->Close();
				}
			}
			elseif($punto)
			{
				$sql  = "SELECT PF.prefijo_fac_credito, ";
				$sql .= "				PF.prefijo_fac_contado ";
				$sql .= "FROM	 	puntos_facturacion PF ";
				$sql .= "WHERE	PF.punto_facturacion_id = ".$punto." ";

				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
				
				if (!$rst->EOF)
				{
					$facturacion = $rst->GetRowAssoc($ToUpper = false);
				}
				
				$rst->Close();
			}
			return $facturacion;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerPrefijosFacturas($tipos,$emp)
		{
			$cadena = "";
			$documentos = $this->DatosFactura($emp,$tipos);
			
			foreach($documentos as $key => $pref)
				$cadena .= "'".$pref['prefijo_fac_contado']."','".$pref['prefijo_fac_credito']."',";
			
			$cadena = substr($cadena,0,-1);
			
			$sql .= "SELECT prefijo ";
			$sql .= "FROM 	documentos ";
      $sql .= "WHERE	documento_id IN (".$cadena.") ";
			$sql .= "ORDER BY 1 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$prefijo = array();
			while (!$rst->EOF)
			{
				$prefijo[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			return $prefijo;
		}
		/**
		*
		*/
		function ObtenerFacturasXPrefijo($datos,$emp)
		{
			if($datos['prnAnuladas'])
			{$query ="D.estado IN ('2','3')";}
			else
			//{$query ="D.estado = '0'";}
			{$query ="D.estado IN ('0','1','2','3')";}//0 => FACTURADA, 1 => PAGADA
																						//2 => ANULADA, 3 => ANULADA CON NOTA
			$sql  = "SELECT DISTINCT D.factura_fiscal,";
			$sql .= "				D.prefijo,";
			$sql .= "				F.nombre,";
			$sql .= "				D.plan_id,";
			$sql .= "				D.fecha_registro,";
			$sql .= "				D.empresa_id,";
			$sql .= "				C.primer_nombre||' '||C.segundo_nombre||' '||C.primer_apellido||' '||C.segundo_apellido AS nombre_paciente,";
			$sql .= "				D.tipo_factura,";
			$sql .= "				B.ingreso,";
			$sql .= "				G.sw_tipo_plan,";
			$sql .= "				A.numerodecuenta, ";
			$sql .= "				CASE WHEN D.estado = '0' THEN 'FACTURADA' ";
			$sql .= "							WHEN D.estado = '1' THEN 'PAGADA' ";
			$sql .= "							WHEN D.estado = '2' OR D.estado = '3' THEN 'ANULADA' ";
			$sql .= "				END AS estado, ";
			$sql .= "				D.empresa_id, ";
			$sql .= "				D.sw_clase_factura, ";
			$sql .= "				FC.valor_debito, ";
			$sql .= "				FC.valor_credito, ";
			$sql .= "				FC.valor_glosa, ";
			$sql .= "				FC.valor_recibo ";
			$sql .= "FROM 	cuentas A, ";
			$sql .= "				ingresos B, ";
			$sql .= "				pacientes C,";
			$sql .= "				fac_facturas D, ";
			$sql .= "				fac_facturas_cuentas E,  ";
			$sql .= "				system_usuarios F,";
			$sql .= "				planes G, ";
      $sql .= "       (";
      $sql .= "         SELECT  FF.empresa_id,";
      $sql .= "                 FF.prefijo,";
      $sql .= "                 FF.factura_fiscal,";
      $sql .= "                 SUM(COALESCE(NC.valor_nota,0)) AS valor_credito,";
      $sql .= "                 SUM(COALESCE(ND.valor_nota,0)) AS valor_debito,";
      $sql .= "                 0 AS valor_glosa, ";
      $sql .= "                 0 AS valor_recibo ";
      $sql .= "         FROM    fac_facturas FF LEFT JOIN";
      $sql .= "                 notas_contado_credito NC ";
      $sql .= "                 ON (FF.empresa_id = NC.empresa_id AND";
      $sql .= "                     FF.prefijo = NC.prefijo_factura AND";
      $sql .= "                     FF.factura_fiscal = NC.factura_fiscal)";
      $sql .= "                 LEFT JOIN notas_contado_debito ND ";
      $sql .= "                 ON (FF.empresa_id = ND.empresa_id AND";
      $sql .= "                     FF.prefijo = ND.prefijo_factura AND";
      $sql .= "                     FF.factura_fiscal = ND.factura_fiscal)";
			$sql .= "         WHERE		FF.prefijo = '".$datos['PrefijoFac']."' ";
			$sql .= "         AND		  FF.factura_fiscal = ".$datos['Factura']." ";
			$sql .= "         AND		  FF.sw_clase_factura = '0' ";
      $sql .= "         GROUP BY 1,2,3 ";
      $sql .= "         UNION ALL ";	 	 	 	 	
      $sql .= "         SELECT  FF.empresa_id,";
      $sql .= "                 FF.prefijo,";
      $sql .= "                 FF.factura_fiscal,";
      $sql .= "                 SUM(COALESCE(FR.total_nota_ajuste+FR.total_nota_credito,0)) AS valor_credito,";
      $sql .= "                 SUM(COALESCE(FR.total_nota_debito,0)) AS valor_debito ,";
      $sql .= "                 SUM(COALESCE(FR.total_nota_glosa,0)) AS valor_glosa,";
      $sql .= "                 SUM(COALESCE(FR.total_recibo,0)) AS valor_recibo";
      $sql .= "         FROM    fac_facturas FF,";
      $sql .= "                 cartera.facturas_resumen FR ";
			$sql .= "         WHERE		FF.prefijo = '".$datos['PrefijoFac']."' ";
			$sql .= "         AND		  FF.factura_fiscal = ".$datos['Factura']." ";
      $sql .= "         AND		  FF.sw_clase_factura = '1' ";
      $sql .= "         AND     FF.empresa_id = FR.empresa_id ";
      $sql .= "         AND     FF.prefijo = FR.prefijo ";
      $sql .= "         AND     FF.factura_fiscal = FR.factura_fiscal";
      $sql .= "         GROUP BY 1,2,3 ";
      $sql .= "       ) FC ";

			$sql .= "WHERE	$query "; 
			//$sql .= "AND 		A.empresa_id = '".$emp."' ";
			$sql .= "AND 		E.numerodecuenta = A.numerodecuenta ";
			$sql .= "AND 		D.prefijo = E.prefijo ";
			$sql .= "AND 		D.factura_fiscal = E.factura_fiscal ";
			$sql .= "AND		D.usuario_id = F.usuario_id ";
			$sql .= "AND 		D.prefijo = FC.prefijo ";
			$sql .= "AND 		D.factura_fiscal = FC.factura_fiscal ";
			$sql .= "AND		D.empresa_id = FC.empresa_id ";
			$sql .= "AND 		D.plan_id = G.plan_id ";
			$sql .= "AND		B.ingreso = A.ingreso ";
			$sql .= "AND		C.tipo_id_paciente = B.tipo_id_paciente ";
			$sql .= "AND		C.paciente_id = B.paciente_id ";
			$sql .= "AND		D.prefijo = '".$datos['PrefijoFac']."' ";
			$sql .= "AND		D.factura_fiscal = ".$datos['Factura']." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerFacturasXPaciente($datos,$emp,$tipos,$offset,$cant)
		{
			$pref = $this->ObtenerPrefijosFacturas(null,$emp);
			foreach($pref as $key => $pre)
				$cadena .= "'".$pre['prefijo']."' ";
			
			$cadena = trim($cadena);
			$cadena = str_replace(" ",",",$cadena);

			if($datos['prnAnuladas'])
			{$query ="D.estado IN ('2','3')";}
			else
			//{$query ="D.estado = '0'";}
			{$query ="D.estado IN ('0','1','2','3')";}//0 => FACTURADA, 1 => PAGADA
																						//2 => ANULADA, 3 => ANULADA CON NOTA
			$sql  = "SELECT DISTINCT D.factura_fiscal,";
			$sql .= "				D.prefijo,";
			$sql .= "				F.nombre,";
			$sql .= "				D.plan_id,";
			$sql .= "				D.fecha_registro,";
			$sql .= "				D.empresa_id,";
			$sql .= "				C.primer_nombre||' '||C.segundo_nombre||' '||C.primer_apellido||' '||C.segundo_apellido AS nombre_paciente,";
			$sql .= "				D.tipo_factura,";
			$sql .= "				B.ingreso,";
			$sql .= "				G.sw_tipo_plan,";
			$sql .= "				A.numerodecuenta, ";
			$sql .= "				CASE WHEN D.estado = '0' THEN 'FACTURADA' ";
			$sql .= "							WHEN D.estado = '1' THEN 'PAGADA' ";
			$sql .= "							WHEN D.estado = '2' OR D.estado = '3' THEN 'ANULADA' ";
			$sql .= "				END AS estado, ";
      $sql .= "				D.empresa_id, ";
      $sql .= "				D.sw_clase_factura, ";
      $sql .= "				FC.valor_debito, ";
      $sql .= "				FC.valor_credito, ";
      $sql .= "				FC.valor_glosa, ";
      $sql .= "				FC.valor_recibo ";
      $sql .= "FROM 	cuentas A, ";
			$sql .= "				ingresos B, ";
			$sql .= "				pacientes C,";
			$sql .= "				fac_facturas_cuentas E,  ";
			$sql .= "				fac_facturas D, ";
			$sql .= "				system_usuarios F,";
			$sql .= "				planes G, ";
      $sql .= "       (";
      $sql .= "         SELECT  FF.empresa_id,";
      $sql .= "                 FF.prefijo,";
      $sql .= "                 FF.factura_fiscal,";
      $sql .= "                 SUM(COALESCE(NC.valor_nota,0)) AS valor_credito,";
      $sql .= "                 SUM(COALESCE(ND.valor_nota,0)) AS valor_debito,";
      $sql .= "                 0 AS valor_glosa, ";
      $sql .= "                 0 AS valor_recibo ";
      $sql .= "         FROM    fac_facturas FF LEFT JOIN";
      $sql .= "                 notas_contado_credito NC ";
      $sql .= "                 ON (FF.empresa_id = NC.empresa_id AND";
      $sql .= "                     FF.prefijo = NC.prefijo_factura AND";
      $sql .= "                     FF.factura_fiscal = NC.factura_fiscal )";
      $sql .= "                 LEFT JOIN notas_contado_debito ND ";
      $sql .= "                 ON (FF.empresa_id = ND.empresa_id AND";
      $sql .= "                     FF.prefijo = ND.prefijo_factura AND";
      $sql .= "                     FF.factura_fiscal = ND.factura_fiscal )";
      $sql .= "         WHERE		FF.prefijo IN (".$cadena.") ";
      $sql .= "         AND		  FF.sw_clase_factura = '0' ";
      $sql .= "         GROUP BY 1,2,3 ";
      $sql .= "         UNION ALL ";	 	 	 	 	
      $sql .= "         SELECT  FF.empresa_id,";
      $sql .= "                 FF.prefijo,";
      $sql .= "                 FF.factura_fiscal,";
      $sql .= "                 SUM(COALESCE(FR.total_nota_ajuste+FR.total_nota_credito,0)) AS valor_credito ,";
      $sql .= "                 SUM(COALESCE(FR.total_nota_debito,0)) AS valor_debito,";
      $sql .= "                 SUM(COALESCE(FR.total_nota_glosa,0)) AS valor_glosa,";
      $sql .= "                 SUM(COALESCE(FR.total_recibo,0)) AS valor_recibo";
      $sql .= "         FROM    fac_facturas FF,";
      $sql .= "                 cartera.facturas_resumen FR ";
      $sql .= "         WHERE		FF.prefijo IN (".$cadena.") ";
      $sql .= "         AND		  FF.sw_clase_factura = '1' ";
      $sql .= "         AND     FF.empresa_id = FR.empresa_id ";
      $sql .= "         AND     FF.prefijo = FR.prefijo ";
      $sql .= "         AND     FF.factura_fiscal = FR.factura_fiscal";
      $sql .= "         GROUP BY 1,2,3 ";
      $sql .= "       ) FC ";
			$sql .= "WHERE	$query "; 
			$sql .= "AND 		E.numerodecuenta = A.numerodecuenta ";
			$sql .= "AND 		D.prefijo = E.prefijo ";
			$sql .= "AND 		D.factura_fiscal = E.factura_fiscal ";
			$sql .= "AND 		D.empresa_id = E.empresa_id ";
			$sql .= "AND		D.usuario_id = F.usuario_id ";
			$sql .= "AND 		D.plan_id = G.plan_id ";
      $sql .= "AND 		D.prefijo = FC.prefijo ";
      $sql .= "AND 		D.factura_fiscal = FC.factura_fiscal ";
      $sql .= "AND		D.empresa_id = FC.empresa_id ";
			$sql .= "AND		B.ingreso = A.ingreso ";
			$sql .= "AND		C.tipo_id_paciente = B.tipo_id_paciente ";
			$sql .= "AND		C.paciente_id = B.paciente_id ";
			$sql .= "AND		E.prefijo IN (".$cadena.") ";
			//$sql .= "AND 		A.empresa_id = '".$emp."' ";
			if($datos['Documento'])
			{
				if($datos['TipoDocumento'])
				{
					$sql .= "AND		C.tipo_id_paciente = '".$datos['TipoDocumento']."' ";
					$sql .= "AND		B.tipo_id_paciente = '".$datos['TipoDocumento']."' ";
				}
				$sql .= "AND		C.paciente_id LIKE '".$datos['Documento']."%' ";
				$sql .= "AND		B.paciente_id LIKE '".$datos['Documento']."%' ";
			}

			
			if($cant <= 0)
			{
				$cant = 0;
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				if(!$rst->EOF) $cant = $rst->RecordCount();
			}
			
			$this->ProcesarSqlConteo("SELECT COUNT(*) FROM ($sql) AS A",$cant);
			
			if(!$offset) $offset = 0;
			
			$sql .= "LIMIT ".$this->limit." OFFSET ".$offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerFacturasXCuenta($datos,$emp,$tipos,$offset,$cant)
		{
			if($datos['prnAnuladas'])
			{$query ="C.estado IN ('2','3')";}
			else
			//{$query ="C.estado = '0'";}
			{$query ="C.estado IN ('0','1','2','3')";}//0 => FACTURADA, 1 => PAGADA
																						//2 => ANULADA, 3 => ANULADA CON NOTA
			$pref = $this->ObtenerPrefijosFacturas(null,$emp);
			foreach($pref as $key => $pre)
				$cadena .= "'".$pre['prefijo']."' ";
			
			$cadena = trim($cadena);
			$cadena = str_replace(" ",",",$cadena);
			
			$sql  = "SELECT DISTINCT C.factura_fiscal,";
			$sql .= "				C.prefijo,";
			$sql .= "				U.nombre,";
			$sql .= "				C.plan_id,";
			$sql .= "				C.fecha_registro,";
			$sql .= "				C.empresa_id,";
			$sql .= "				PA.primer_nombre||' '||PA.segundo_nombre||' '||PA.primer_apellido||' '||PA.segundo_apellido AS nombre_paciente,";
			$sql .= "				C.tipo_factura,";
			$sql .= "				A.ingreso,";
			$sql .= "				P.sw_tipo_plan,";
			$sql .= "				A.numerodecuenta, ";
			$sql .= "				CASE WHEN C.estado = '0' THEN 'FACTURADA' ";
			$sql .= "							WHEN C.estado = '1' THEN 'PAGADA' ";
			$sql .= "							WHEN C.estado = '2' OR C.estado = '3' THEN 'ANULADA' ";
			$sql .= "				END AS estado, ";
      $sql .= "				C.empresa_id, ";
      $sql .= "				C.sw_clase_factura, ";
      $sql .= "				FC.valor_debito, ";
      $sql .= "				FC.valor_credito, ";
      $sql .= "				FC.valor_glosa, ";
      $sql .= "				FC.valor_recibo ";
			$sql .= "FROM		( ";
			$sql .= "					SELECT  IG.ingreso, ";
			$sql .= "         			  IG.tipo_id_paciente, ";
			$sql .= "            			IG.paciente_id, ";
			$sql .= "            			CU.numerodecuenta, ";
			$sql .= "            			FC.prefijo,";
			$sql .= "            			FC.factura_fiscal, ";
			$sql .= "            			FC.empresa_id ";
			$sql .= "    			FROM    ingresos IG, ";
			$sql .= "            			cuentas CU, ";
			$sql .= "									fac_facturas_cuentas FC ";
			$sql .= "    			WHERE		CU.ingreso = IG.ingreso ";
			$sql .= "					AND			FC.numerodecuenta = CU.numerodecuenta ";
			//$sql .= "					AND			FC.prefijo IN (".$cadena.") ";
			//$sql .= "					AND			CU.empresa_id = '".$emp."' ";
			
			if($datos['Ingreso']) 
				$sql .= "    			AND		IG.ingreso = ".$datos['Ingreso']." ";
			
			if($datos['Cuenta']) 
				$sql .= "    			AND		CU.numerodecuenta = ".$datos['Cuenta']." ";
			
			$sql .= "				) AS A, ";
			$sql .= "				fac_facturas AS C, ";
			$sql .= "				planes AS P, ";
			$sql .= "				system_usuarios AS U, ";
			$sql .= "				pacientes PA, ";
      $sql .= "       (";
      $sql .= "         SELECT  FF.empresa_id,";
      $sql .= "                 FF.prefijo,";
      $sql .= "                 FF.factura_fiscal,";
      $sql .= "                 SUM(COALESCE(NC.valor_nota,0)) AS valor_credito,";
      $sql .= "                 SUM(COALESCE(ND.valor_nota,0)) AS valor_debito,";
      $sql .= "                 0 AS valor_glosa, ";
      $sql .= "                 0 AS valor_recibo ";
      $sql .= "         FROM    fac_facturas FF LEFT JOIN";
      $sql .= "                 notas_contado_credito NC ";
      $sql .= "                 ON (FF.empresa_id = NC.empresa_id AND";
      $sql .= "                     FF.prefijo = NC.prefijo_factura AND";
      $sql .= "                     FF.factura_fiscal = NC.factura_fiscal)";
      $sql .= "                 LEFT JOIN notas_contado_debito ND ";
      $sql .= "                 ON (FF.empresa_id = ND.empresa_id AND";
      $sql .= "                     FF.prefijo = ND.prefijo_factura AND";
      $sql .= "                     FF.factura_fiscal = ND.factura_fiscal)";
      $sql .= "         WHERE		FF.prefijo IN (".$cadena.") ";
      $sql .= "         AND		  FF.sw_clase_factura = '0' ";
      $sql .= "         GROUP BY 1,2,3 ";
      $sql .= "         UNION ALL ";	 	 	 	 	
      $sql .= "         SELECT  FF.empresa_id,";
      $sql .= "                 FF.prefijo,";
      $sql .= "                 FF.factura_fiscal,";
      $sql .= "                 SUM(COALESCE(FR.total_nota_ajuste+FR.total_nota_credito,0)) AS valor_debito,";
      $sql .= "                 SUM(COALESCE(FR.total_nota_debito,0)) AS valor_debito,";
      $sql .= "                 SUM(COALESCE(FR.total_nota_glosa,0)) AS valor_glosa,";
      $sql .= "                 SUM(COALESCE(FR.total_recibo,0)) AS valor_recibo";
      $sql .= "         FROM    fac_facturas FF,";
      $sql .= "                 cartera.facturas_resumen FR ";
      $sql .= "         WHERE		FF.prefijo IN (".$cadena.") ";
      $sql .= "         AND		  FF.sw_clase_factura = '1' ";
      $sql .= "         AND     FF.empresa_id = FR.empresa_id ";
      $sql .= "         AND     FF.prefijo = FR.prefijo ";
      $sql .= "         AND     FF.factura_fiscal = FR.factura_fiscal";
      $sql .= "         GROUP BY 1,2,3 ";
      $sql .= "       ) FC ";
			$sql .= "WHERE	C.prefijo IN (".$cadena.") ";
			$sql .= "AND 		C.empresa_id =  A.empresa_id ";
			$sql .= "AND 		C.prefijo = A.prefijo ";
			$sql .= "AND 		C.factura_fiscal = A.factura_fiscal ";
      $sql .= "AND 		C.prefijo = FC.prefijo ";
      $sql .= "AND 		C.factura_fiscal = FC.factura_fiscal ";
      $sql .= "AND		C.empresa_id = FC.empresa_id ";
			$sql .= "AND 		$query ";
			$sql .= "AND 		P.plan_id = C.plan_id ";
			$sql .= "AND 		U.usuario_id = C.usuario_id ";
			$sql .= "AND 		PA.tipo_id_paciente = A.tipo_id_paciente ";
			$sql .= "AND 		PA.paciente_id = A.paciente_id ";
			
			
			if($cant <= 0)
			{
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				$cant = 0;
				if(!$rst->EOF) $cant = $rst->RecordCount();
			}
			
			$this->ProcesarSqlConteo("SELECT COUNT(*) FROM ($sql) AS A",$cant);
			
			if(!$offset) $offset = 0;
			
			$sql .= "LIMIT ".$this->limit." OFFSET ".$offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
						
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $retorno;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerFacturasXNombrePaciente($datos,$emp,$tipos,$offset,$cant)
		{
			$nombres = strtoupper($datos['Nombres']);
			$apellidos = strtoupper($datos['Apellidos']);
			
			if ($nombres != '')
			{
				$a = explode(' ',preg_replace("/\s{2,}/"," ",$nombres));//QUITA DOBLE ESPACIOS INTERNOS

				switch(count($a))
				{
					case 1:
						$filtroNombres .= " (primer_nombre  SIMILAR TO '(".current($a)."|".current($a)."[[:space:]]%|%[[:space:]]".current($a)."|%[[:space:]]".current($a)."[[:space:]]%)' OR segundo_nombre SIMILAR TO '(".current($a)."|".current($a)."[[:space:]]%|%[[:space:]]".current($a)."|%[[:space:]]".current($a)."[[:space:]]%)')";
					break;
					case 2:
						$filtroNombres  = " primer_nombre SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
						next($a);
						$filtroNombres .= " AND ((primer_nombre SIMILAR TO '%[[:space:]]".current($a)."') OR (segundo_nombre ILIKE '".current($a)."'))";
					break;
					default:
						$filtroNombres = " primer_nombre SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
						for($i=2;$i<count($a);$i++)
						{
							next($a);
							$filtroNombres .= " AND ((primer_nombre SIMILAR TO '%[[:space:]](".current($a)."|".current($a)."[[:space:]]%)')
																	OR (segundo_nombre SIMILAR TO '(".current($a)."[[:space:]]%|%[[:space:]]".current($a)."[[:space:]]%)'))";
						}
						next($a);
						$filtroNombres .= " AND ((primer_nombre SIMILAR TO '%[[:space:]]".current($a)."')  OR  (segundo_nombre SIMILAR TO '(".current($a)."|%[[:space:]]".current($a).")') )";
					break;
				}
			}

			if ($apellidos != '')
			{
				$a = explode(' ',preg_replace("/\s{2,}/"," ",$apellidos));

				switch(count($a))
				{
					case 1:
							$filtroApellidos  = " primer_apellido ILIKE '".current($a)."'";
					break;

					case 2:
							$filtroApellidos  = " primer_apellido SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
							next($a);
							$filtroApellidos .= " AND ((primer_apellido SIMILAR TO '%[[:space:]]".current($a)."') OR (segundo_apellido ILIKE '".current($a)."'))";
					break;

					default:
							$filtroApellidos  = " primer_apellido SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
							for($i=2;$i<count($a);$i++)
							{
								next($a);
								$filtroApellidos .= " AND ((primer_apellido SIMILAR TO '%[[:space:]](".current($a)."|".current($a)."[[:space:]]%)')
																						OR (segundo_apellido SIMILAR TO '(".current($a)."[[:space:]]%|%[[:space:]]".current($a)."[[:space:]]%)'))";
							}
							next($a);
							$filtroApellidos .= " AND ((primer_apellido SIMILAR TO '%[[:space:]]".current($a)."')  OR  (segundo_apellido SIMILAR TO '(".current($a)."|%[[:space:]]".current($a).")') )";
					break;
				}
			}

			if(!empty($filtroNombres))
			{
				if(!empty($filtroApellidos))
				{
					$filtroPrincipalTipo2= $filtroNombres ." AND ".$filtroApellidos;
				}
				else
				{
					$filtroPrincipalTipo2 = $filtroNombres;
				}
			}
			else
			{
				if(!empty($filtroApellidos))
				{
					$filtroPrincipalTipo2 = $filtroApellidos;
				}
			}
			
			$sql .= "SELECT C.numerodecuenta ";
      $sql .= "FROM		pacientes P, ";
      $sql .= "				ingresos I,  ";
      $sql .= "				cuentas C  ";
      $sql .= "WHERE	$filtroPrincipalTipo2 ";
      $sql .= "AND		P.tipo_id_paciente = I.tipo_id_paciente ";
			$sql .= "AND		P.paciente_id = I.paciente_id ";
		//	$sql .= "AND 		C.empresa_id = '".$emp."' ";
			$sql .= "AND		C.ingreso = I.ingreso ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			$cuentas = "";
			while(!$rst->EOF)
			{
				$cuentas .= $rst->fields[0]." ";
				$rst->MoveNext();
			}
			$rst->Close();
			
			$cuentas = trim($cuentas);
			$cuentas = str_replace(" ",",",$cuentas);
			
			$retorno = array();
			if($cuentas != "")
			{
				if($datos['prnAnuladas'])
				{$query ="C.estado IN ('2','3')";}
				else
				//{$query ="C.estado = '0'";}
				{$query ="C.estado IN ('0','1','2','3')";}//0 => FACTURADA, 1 => PAGADA
																						//2 => ANULADA, 3 => ANULADA CON NOTA
				$pref = $this->ObtenerPrefijosFacturas(null,$emp);
				foreach($pref as $key => $pre)
					$cadena .= "'".$pre['prefijo']."' ";
				
				$cadena = trim($cadena);
				$cadena = str_replace(" ",",",$cadena);
				
				$sql  = "SELECT DISTINCT C.factura_fiscal,";
				$sql .= "				C.prefijo,";
				$sql .= "				U.nombre,";
				$sql .= "				C.plan_id,";
				$sql .= "				C.fecha_registro,";
				$sql .= "				C.empresa_id,";
				$sql .= "				PA.primer_nombre||' '||PA.segundo_nombre||' '||PA.primer_apellido||' '||PA.segundo_apellido AS nombre_paciente,";
				$sql .= "				C.tipo_factura,";
				$sql .= "				A.ingreso,";
				$sql .= "				P.sw_tipo_plan,";
				$sql .= "				A.numerodecuenta, ";
				$sql .= "				CASE WHEN C.estado = '0' THEN 'FACTURADA' ";
				$sql .= "							WHEN C.estado = '1' THEN 'PAGADA' ";
				$sql .= "							WHEN C.estado = '2' OR C.estado = '3' THEN 'ANULADA' ";
				$sql .= "				END AS estado, ";
        $sql .= "				C.empresa_id, ";
  			$sql .= "				C.sw_clase_factura, ";
  			$sql .= "				FC.valor_debito, ";
  			$sql .= "				FC.valor_credito, ";
  			$sql .= "				FC.valor_glosa, ";
  			$sql .= "				FC.valor_recibo ";
				$sql .= "FROM		( ";
				$sql .= "					SELECT  IG.ingreso, ";
				$sql .= "         			  IG.tipo_id_paciente, ";
				$sql .= "            			IG.paciente_id, ";
				$sql .= "            			CU.numerodecuenta ";
				$sql .= "    			FROM    ingresos IG, ";
				$sql .= "            			cuentas CU ";
				$sql .= "    			WHERE		CU.ingreso = IG.ingreso ";
				//$sql .= "					AND			CU.empresa_id = '".$emp."' ";
				$sql .= "    			AND			CU.numerodecuenta IN (".$cuentas.") ";
				$sql .= "				) AS A, ";
				$sql .= "				fac_facturas_cuentas AS B, ";
				$sql .= "				fac_facturas AS C, ";
				$sql .= "				planes AS P, ";
				$sql .= "				system_usuarios AS U, ";
				$sql .= "				pacientes PA, ";
        $sql .= "       (";
        $sql .= "         SELECT  FF.empresa_id,";
        $sql .= "                 FF.prefijo,";
        $sql .= "                 FF.factura_fiscal,";
        $sql .= "                 SUM(COALESCE(NC.valor_nota,0)) AS valor_credito,";
        $sql .= "                 SUM(COALESCE(ND.valor_nota,0)) AS valor_debito,";
        $sql .= "                 0 AS valor_glosa, ";
        $sql .= "                 0 AS valor_recibo ";
        $sql .= "         FROM    fac_facturas FF LEFT JOIN";
        $sql .= "                 notas_contado_credito NC ";
        $sql .= "                 ON (FF.empresa_id = NC.empresa_id AND";
        $sql .= "                     FF.prefijo = NC.prefijo_factura AND";
        $sql .= "                     FF.factura_fiscal = NC.factura_fiscal)";
        $sql .= "                 LEFT JOIN notas_contado_debito ND ";
        $sql .= "                 ON (FF.empresa_id = ND.empresa_id AND";
        $sql .= "                     FF.prefijo = ND.prefijo_factura AND";
        $sql .= "                     FF.factura_fiscal = ND.factura_fiscal)";
  			$sql .= "         WHERE		FF.prefijo IN (".$cadena.") ";
  			$sql .= "         AND		  FF.sw_clase_factura = '0' ";
        $sql .= "         GROUP BY 1,2,3 ";
        $sql .= "         UNION ALL ";	 	 	 	 	
        $sql .= "         SELECT  FF.empresa_id,";
        $sql .= "                 FF.prefijo,";
        $sql .= "                 FF.factura_fiscal,";
        $sql .= "                 SUM(COALESCE(FR.total_nota_ajuste+FR.total_nota_credito,0)) AS valor_credito,";
        $sql .= "                 SUM(COALESCE(FR.total_nota_debito,0)) AS valor_debito,";
        $sql .= "                 SUM(COALESCE(FR.total_nota_glosa,0)) AS valor_glosa,";
        $sql .= "                 SUM(COALESCE(FR.total_recibo,0)) AS valor_recibo";
        $sql .= "         FROM    fac_facturas FF, ";
        $sql .= "                 cartera.facturas_resumen FR ";
  			$sql .= "         WHERE		FF.prefijo IN (".$cadena.") ";
        $sql .= "         AND		  FF.sw_clase_factura = '1' ";
        $sql .= "         AND     FF.empresa_id = FR.empresa_id ";
        $sql .= "         AND     FF.prefijo = FR.prefijo ";
        $sql .= "         AND     FF.factura_fiscal = FR.factura_fiscal";
        $sql .= "         GROUP BY 1,2,3 ";
        $sql .= "       ) FC ";
				$sql .= "WHERE	B.numerodecuenta = A.numerodecuenta ";
				$sql .= "AND		B.prefijo IN (".$cadena.") ";
				$sql .= "AND 		C.empresa_id =  B.empresa_id ";
				$sql .= "AND 		C.prefijo = B.prefijo ";
				$sql .= "AND 		C.factura_fiscal = B.factura_fiscal ";
        $sql .= "AND 		C.prefijo = FC.prefijo ";
        $sql .= "AND 		C.factura_fiscal = FC.factura_fiscal ";
        $sql .= "AND		C.empresa_id = FC.empresa_id ";
				$sql .= "AND 		$query ";
				$sql .= "AND 		P.plan_id = C.plan_id ";
				$sql .= "AND 		U.usuario_id = C.usuario_id ";
				$sql .= "AND 		PA.tipo_id_paciente = A.tipo_id_paciente ";
				$sql .= "AND 		PA.paciente_id = A.paciente_id ";
				
				
				if($cant <= 0)
				{
					$cant = 0;
					if(!$rst = $this->ConexionBaseDatos($sql)) return false;
					if(!$rst->EOF) $cant = $rst->RecordCount();
				}				
				
				$this->ProcesarSqlConteo("SELECT COUNT(*) FROM ($sql) AS A",$cant);
				
				if(!$offset) $offset = 0;
				
				$sql .= "LIMIT ".$this->limit." OFFSET ".$offset;
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				
				while(!$rst->EOF)
				{
					$retorno[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
				$rst->Close();
			}
			return $retorno;			
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerFacturasXTerceroId($datos,$emp,$tipos,$offset,$cant)
		{
			$pref = $this->ObtenerPrefijosFacturas(null,$emp);
			foreach($pref as $key => $pre)
				$cadena .= "'".$pre['prefijo']."' ";
			
			$cadena = trim($cadena);
			$cadena = str_replace(" ",",",$cadena);
			
			if($datos['prnAnuladas'])
			{$query ="FF.estado IN ('2','3')";}
			else
				{$query ="FF.estado IN ('0','1','2','3')";}//0 => FACTURADA, 1 => PAGADA
																						//2 => ANULADA, 3 => ANULADA CON NOTA
			$sql .= "SELECT DISTINCT D.*, ";
			$sql .= "				F.nombre, ";
			$sql .= "				C.primer_nombre||' '||C.segundo_nombre||' '||C.primer_apellido||' '||C.segundo_apellido AS nombre_paciente, ";
			$sql .= "				B.ingreso, ";
			$sql .= "				A.numerodecuenta ";
			$sql .= "FROM 	cuentas A, ";
			$sql .= "				ingresos B, ";
			$sql .= "				pacientes C, ";
			$sql .= "				system_usuarios F,";
			$sql .= "				( ";
			$sql .= "					SELECT 	PL.plan_id,";
			$sql .= "									PL.sw_tipo_plan,";
			$sql .= "									FF.tipo_factura,";
			$sql .= "									FF.empresa_id,";
			$sql .= "									FF.fecha_registro,";
			$sql .= "									FF.prefijo,";
			$sql .= "									FF.factura_fiscal,";
			$sql .= "									FF.usuario_id,";
			$sql .= "									FC.numerodecuenta, ";
			$sql .= "				          CASE WHEN FF.estado = '0' THEN 'FACTURADA' ";
			$sql .= "							         WHEN FF.estado = '1' THEN 'PAGADA' ";
			$sql .= "							         WHEN FF.estado = '2' OR FF.estado = '3' THEN 'ANULADA' ";
      $sql .= "				          END AS estado, ";
      $sql .= "				          FF.empresa_id, ";
			$sql .= "				          FF.sw_clase_factura, ";
			$sql .= "				          FN.valor_debito, ";
			$sql .= "				          FN.valor_credito, ";
			$sql .= "				          FN.valor_glosa, ";
			$sql .= "				          FN.valor_recibo ";
			$sql .= "					FROM		fac_facturas FF,";
			$sql .= "									fac_facturas_cuentas FC,";
			$sql .= "									planes PL, ";
      $sql .= "                 (";
      $sql .= "                   SELECT  FX.empresa_id,";
      $sql .= "                           FX.prefijo,";
      $sql .= "                           FX.factura_fiscal,";
      $sql .= "                           SUM(COALESCE(NC.valor_nota,0)) AS valor_credito,";
      $sql .= "                           SUM(COALESCE(ND.valor_nota,0)) AS valor_debito,";
      $sql .= "                           0 AS valor_glosa, ";
      $sql .= "                           0 AS valor_recibo ";
      $sql .= "                   FROM    fac_facturas FX LEFT JOIN";
      $sql .= "                           notas_contado_credito NC ";
      $sql .= "                           ON (FX.empresa_id = NC.empresa_id AND";
      $sql .= "                               FX.prefijo = NC.prefijo_factura AND";
      $sql .= "                               FX.factura_fiscal = NC.factura_fiscal)";
      $sql .= "                           LEFT JOIN notas_contado_debito ND ";
      $sql .= "                           ON (FX.empresa_id = ND.empresa_id AND";
      $sql .= "                               FX.prefijo = ND.prefijo_factura AND";
      $sql .= "                               FX.factura_fiscal = ND.factura_fiscal)";
      $sql .= "                   WHERE		FX.prefijo IN (".$cadena.") ";
 			$sql .= "					          AND			FX.tipo_id_tercero = '".$datos['TipoDocumentoTercero']."' "; 
			$sql .= "					          AND			FX.tercero_id = '".$datos['DocumentoTercero']."' ";
      $sql .= "                   AND		  FX.sw_clase_factura = '0' ";
      $sql .= "                   GROUP BY 1,2,3 ";
      $sql .= "                   UNION ALL ";	 	 	 	 	
      $sql .= "                   SELECT  FX.empresa_id,";
      $sql .= "                           FX.prefijo,";
      $sql .= "                           FX.factura_fiscal,";
      $sql .= "                           SUM(COALESCE(FR.total_nota_ajuste+FR.total_nota_credito,0)) AS valor_credito,";
      $sql .= "                           SUM(COALESCE(FR.total_nota_debito,0)) AS valor_debito ,";
      $sql .= "                           SUM(COALESCE(FR.total_nota_glosa,0)) AS valor_glosa,";
      $sql .= "                           SUM(COALESCE(FR.total_recibo,0)) AS valor_recibo";
      $sql .= "                   FROM    fac_facturas FX,";
      $sql .= "                           cartera.facturas_resumen FR ";
      $sql .= "                   WHERE		FX.prefijo IN (".$cadena.") ";
 			$sql .= "					          AND			FX.tipo_id_tercero = '".$datos['TipoDocumentoTercero']."' "; 
			$sql .= "					          AND			FX.tercero_id = '".$datos['DocumentoTercero']."' ";
      $sql .= "                   AND		  FX.sw_clase_factura = '1' ";
      $sql .= "                   AND     FX.empresa_id = FR.empresa_id ";
      $sql .= "                   AND     FX.prefijo = FR.prefijo ";
      $sql .= "                   AND     FX.factura_fiscal = FR.factura_fiscal";
      $sql .= "                   GROUP BY 1,2,3 ";
      $sql .= "                 ) FN ";

			$sql .= "					WHERE		FF.plan_id = PL.plan_id ";
			$sql .= "					AND			FF.tipo_id_tercero = '".$datos['TipoDocumentoTercero']."' "; 
			$sql .= "					AND			FF.tercero_id = '".$datos['DocumentoTercero']."' ";
			$sql .= "					AND 		FC.prefijo IN (".$cadena.") ";
			$sql .= "					AND			$query ";
			//$sql .= "					AND			FF.empresa_id = '".$emp."' ";
			$sql .= "					AND 		FC.prefijo = FF.prefijo ";
			$sql .= "					AND 		FC.factura_fiscal = FF.factura_fiscal ";
			$sql .= "					AND 		FC.empresa_id = FF.empresa_id ";
      $sql .= "         AND 		FF.prefijo = FN.prefijo ";
			$sql .= "         AND 		FF.factura_fiscal = FN.factura_fiscal ";
			$sql .= "         AND		  FF.empresa_id = FN.empresa_id ";
			$sql .= "				) AS D ";
			$sql .= "WHERE 	A.empresa_id = '01' ";
			$sql .= "AND 		D.numerodecuenta = A.numerodecuenta ";
			$sql .= "AND 		D.usuario_id = F.usuario_id ";
			$sql .= "AND 		B.ingreso = A.ingreso ";
			$sql .= "AND 		C.tipo_id_paciente = B.tipo_id_paciente ";
			$sql .= "AND 		C.paciente_id = B.paciente_id ";
							
			if($cant <= 0)
			{			
				$cant = 0;
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				if(!$rst->EOF) $cant = $rst->RecordCount();
			}
			
			$this->ProcesarSqlConteo("SELECT COUNT(*) FROM ($sql) AS A",$cant);
			
			if(!$offset) $offset = 0;
			
			$sql .= "LIMIT ".$this->limit." OFFSET ".$offset;
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerFacturasTerceros($datos,$emp,$tipos,$offset)
		{
			$pref = $this->ObtenerPrefijosFacturas(null,$emp);
			foreach($pref as $key => $pre)
				$cadena .= "'".$pre['prefijo']."' ";
			
			$cadena = trim($cadena);
			$cadena = str_replace(" ",",",$cadena);
						
			$sql .= "SELECT DISTINCT TE.nombre_tercero, ";
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD/MM/YYYY HH:MI AM') AS fecha,";
			$sql .= "				FF.prefijo,";
			$sql .= "				FF.factura_fiscal ";
			$sql .= "FROM		terceros TE, ";
			$sql .= "				fac_facturas FF ";
			$sql .= "WHERE	FF.tipo_id_tercero = TE.tipo_id_tercero ";
			$sql .= "AND 		FF.tercero_id = TE.tercero_id ";
			$sql .= "AND 		FF.prefijo IN (".$cadena.") ";
			
			if($datos['nombre_tercero'])
				$sql .= "AND 		TE.nombre_tercero ILIKE '%".$datos['nombre_tercero']."%' ";
			
			if($datos['tipo_id_tercero'] != '0' && $datos['tercero_id'])
			{
				$sql .= "AND		FF.tipo_id_tercero = '".$datos['tipo_id_tercero']."' ";
				$sql .= "AND 		FF.tercero_id = '".$datos['tercero_id']."' ";
			}
						
			$this->ProcesarSqlConteo("SELECT COUNT(*) FROM ($sql) AS A",$cant,$offset);
			
			$sql .= "ORDER BY TE.nombre_tercero ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObternerTiposIdTerceros()
		{
			$sql = "SELECT * FROM tipo_id_terceros ORDER BY indice_de_orden";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$vars = array();
			while (!$rst->EOF) 
			{
				$vars[$rst->fields[0]] = $rst->fields[1];
				$rst->MoveNext();
			}
			$rst->Close();
			return $vars;
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
    /**
		* Funcion donde se obtiene la información de los rangos
    *
    * @param string $tipoId identificador del tipo de tercero
    * @param string $terceroId Identificacion del tercero
    *
    * @return mixed
		*/
		function ObtenerRangosNiveles($tipoId,$terceroId, $plan_id)
		{
			$sql  = "SELECT DISTINCT PR.plan_id, ";
      $sql .= "       PR.rango ";
			$sql .= "FROM		planes_rangos PR, ";
      $sql .= "       planes PL ";
			$sql .= "WHERE 	PL.plan_id = PR.plan_id ";
      if(!$plan_id)
      {
        $sql .= "AND    PL.tercero_id = '".$terceroId."' ";
        $sql .= "AND    PL.tipo_tercero_id = '".$tipoId."' ";
			}
      else
      {
        $sql .= "AND 	PL.plan_id = ".$plan_id." ";
      }
      
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$rangos = array();
			while (!$rst->EOF)
			{
				$rangos[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $rangos;
		}
    /**
    * Metodo donde se obtienen los planes agrupados de los terceros seleccionados
    *
    * @param string $empresa Identificador de la empresa
    *
    * @return mixed
    */
    function ObtenerTercerosPlanesAgrupados($empresa,$tipo_id_tercero,$tercero_id)
    {
      $sql  = "SELECT plan_id, ";
      $sql .= "       plan_descripcion ";
      $sql .= "FROM   planes ";
      $sql .= "WHERE  tipo_tercero_id= '".$tipo_id_tercero."' ";
      $sql .= "AND    tercero_id = '".$tercero_id."' ";
      $sql .= "AND    estado='1' ";
      $sql .= "AND    sw_facturacion_agrupada='1' ";
      $sql .= "AND    fecha_final >= now() ";
      $sql .= "AND    fecha_inicio <= now() ";
      $sql .= "AND    empresa_id = '".$empresa."' ";
      $sql .= "ORDER BY plan_descripcion ";
      
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
		* Funcion donde se evalua si el usuario que esta accediendo al modulo tiene o no
		* permisos para las diferentes opciones del menu de facutaracion
		* 
		* @return boolean Indica si tiene permisos true, o no false
		***********************************************************************************/
		function permisos_opcionesFacturacion($empresa_id)
		{
      $sql = "  SELECT  sw_cuentas,
                               sw_fact_agrupada,
                               sw_factura,
                               sw_envios,
                               sw_manejo_envios,
                               sw_reportes,
                               sw_rips,
                               sw_admin_sin_hc,
                               sw_fact_anuladas
                  FROM   userpermisos_menu_facturacion
                  WHERE empresa_id = '".$empresa_id."'
                  AND      usuario_id = ".UserGetUID()." ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			
			$datos = array();
			
			//while (!$rst->EOF)
			
			while($data = $rst->FetchRow())
			{
        $datos= $rst->GetRowAssoc($ToUpper = false);
        //	$datos=$data;
        $rst->MoveNext();

        $rst->Close();

        return $datos;
      }
    }
     	/**********************************************************************************
		* Funcion donde se evalua si el usuario que esta accediendo al modulo tiene o no
		* permisos para las diferentes opciones del menu de facutaracion
		* 
		* @return boolean Indica si tiene permisos true, o no false
		*************************************************************************************/
    function permisos_opcionesManejoEnvios($empresa_id)
		{
           
      $sql = "  SELECT  sw_op_despacho,
                               sw_radicacion,
                               sw_anulacion_envios,
                               sw_anulacion_radicacion
                  FROM   userpermisos_manejo_envios
                  WHERE empresa_id = '".$empresa_id."'
                  AND      usuario_id = ".UserGetUID()." ";

			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			
			$datos = array();
			
			//while (!$rst->EOF)
			
			while($data = $rst->FetchRow())
			{
        $datos= $rst->GetRowAssoc($ToUpper = false);
        //	$datos=$data;
        $rst->MoveNext();

        $rst->Close();

        return $datos;
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