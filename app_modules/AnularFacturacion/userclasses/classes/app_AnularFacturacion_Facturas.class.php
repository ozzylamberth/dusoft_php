<?php
  /******************************************************************************
  * $Id: app_AnularFacturacion_Facturas.class.php,v 1.5 2010/03/16 13:00:35 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.5 $ 
	* 
	* @autor Hugo F  Manrique 
  * Proposito del Archivo:	Manejo logico de la logica del modulo de 
	*													anulacion de facturas
  ********************************************************************************/
	class app_AnularFacturacion_Facturas
	{
		function app_AnularFacturacion_Facturas(){}
		/**********************************************************************************
		* Funcion que trae los motivos de anulacion creados para la anulacion de facturas
		* 
		* @return array informacion de los motivo de anulacion de las facturas
		***********************************************************************************/
		function ObtenerMotivosAnulacionFacturacion()
		{
			$datos = array();
			
			$sql .= "SELECT	motivo_id AS motivo_anulacion_id,";
			$sql .= "				motivo_descripcion ";
			$sql .= "FROM		motivos_anulacion_facturas ";
			$sql .= "WHERE	sw_activo = '1' ";
			$sql .= "ORDER BY 2 ";
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
		
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
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
			$sql .= "FROM 	userpermisos_anulacion_facturas UF, ";
			$sql .= "				documentos DC ";
			$sql .= "WHERE 	UF.usuario_id = ".UserGetUID()." ";
			$sql .= "AND		UF.empresa_id = '".$empresa."' ";
			$sql .= "AND		DC.empresa_id = UF.empresa_id ";
			$sql .= "AND		DC.documento_id = UF.documento_id ";
			$sql .= "AND		DC.sw_estado IN ('0','1') ";
			$sql .= "AND		DC.tipo_doc_general_id = '".$tipodc."' ";
			$sql .= "ORDER BY 2 ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			$i=0;
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
			
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		* Funcion donde se obtiene informacion de la factura credito y el tercero
		* 
		* @params int $numerodecuenta Numero de cuenta
		* @return array datos de la factura
		***********************************************************************************/
		function ObtenerInformacionFacturaCredito($numerodecuenta,$estado = '1')
		{
			$datos = array();
			
			$sql .= "SELECT	FF.prefijo,";
			$sql .= "				FF.factura_fiscal,";
			$sql .= "				FF.total_factura, ";
			$sql .= "				FF.tipo_id_tercero, ";
			$sql .= "				FF.tercero_id, ";
			$sql .= "				TO_CHAR(FF.fecha_registro,'YYYYMM') AS periodo, ";
			$sql .= "				TE.nombre_tercero ";
			$sql .= "FROM		fac_facturas FF, ";
			$sql .= "				fac_facturas_cuentas FC, ";
			$sql .= "				terceros TE ";
			$sql .= "WHERE	FC.numerodecuenta = ".$numerodecuenta." ";
			$sql .= "AND		FC.prefijo = FF.prefijo ";
			$sql .= "AND		FC.empresa_id = FF.empresa_id ";
			$sql .= "AND		FC.factura_fiscal = FF.factura_fiscal ";
			$sql .= "AND		FF.tercero_id = TE.tercero_id ";
			$sql .= "AND		FF.tipo_id_tercero = TE.tipo_id_tercero ";
			$sql .= "AND		FF.sw_clase_factura = '".$estado."' ";
			$sql .= "AND		FF.estado NOT IN ('2','3') ";
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
		
      $datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      if($datos['periodo'] != date("Ym"))
      {
        $this->Nota = "LA FACTURA NO PUEDE SER ANULADA SU FECHA DE REGISTRO ES DE UM MES ANTERIOR AL ACTUAl";
      }
      
			return $datos;
		}
		/**********************************************************************************
		* Funcion donde se obtienen los motivos de anulacion para las facturas
		* hospitalaria
		* 
		* @return array informacion de los motivo de anulacion de las facturas
		***********************************************************************************/
		function ObtenerMotivosAnulacion()
		{
			$datos = array();
			
			$sql .= "SELECT	motivo_anulacion_id,";
			$sql .= "				motivo_descripcion ";
			$sql .= "FROM		cajas_rapidas_motivos_anulaciones ";
			$sql .= "WHERE	sw_activo = '1' ";
			$sql .= "ORDER BY 2 ";
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
		
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/**********************************************************************************
		* Funcion donde se obtiene la informacion de una factura contado y la cuenta 
		* asociada a ella
		*
		* @params char 	$prefijo Prefijo de la factura contado
		* @params int		$numerofactura Numero de la factura
		* @return array Datos de la factura
		***********************************************************************************/
		function ObtenerInformacionFacturaContado($prefijo,$numerofactura)
		{
			$datos = array();
			
			$sql .= "SELECT	FC.prefijo,";
			$sql .= "				FC.factura_fiscal,";
			$sql .= "				FC.caja_id,";
			$sql .= "				FC.cierre_caja_id, ";
			$sql .= "				FC.empresa_id, ";
			$sql .= "				FC.tercero_id, ";
			$sql .= "				FC.tipo_id_tercero, ";
			$sql .= "				FS.total_factura,";
			$sql .= "				TO_CHAR(FS.fecha_registro,'YYYYMM') AS periodo,";
			$sql .= "				CR.descripcion, ";
			$sql .= "				CU.numerodecuenta, ";
			$sql .= "				CU.total_cuenta, ";
			$sql .= "				CU.valor_nocubierto,";
			$sql .= "				CU.valor_cubierto, ";
			$sql .= "				CU.ingreso, ";
			$sql .= "				PL.plan_descripcion, ";
			$sql .= "				PA.tipo_id_paciente ||' '||PA.paciente_id AS identificacion, ";
			$sql .= "				PA.primer_nombre ||' '||PA.segundo_nombre AS nombres, ";
			$sql .= "				PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos ";
			$sql .= "FROM		fac_facturas_contado FC, ";
			$sql .= "				cajas_rapidas CR, ";
			$sql .= "				fac_facturas_cuentas FF, ";
			$sql .= "				fac_facturas FS, ";
			$sql .= "				cuentas CU, ";
			$sql .= "				ingresos IG, ";
			$sql .= "				pacientes PA, ";
			$sql .= "				planes PL ";
			$sql .= "WHERE	FC.prefijo = '".$prefijo."' ";
			$sql .= "AND		FC.factura_fiscal = ".$numerofactura." ";
			$sql .= "AND		FC.caja_id = CR.caja_id ";
			$sql .= "AND		FC.prefijo = FF.prefijo ";
			$sql .= "AND		FC.factura_fiscal = FF.factura_fiscal ";
			$sql .= "AND		FC.empresa_id = FF.empresa_id ";
			$sql .= "AND		FC.prefijo = FS.prefijo ";
			$sql .= "AND		FC.factura_fiscal = FS.factura_fiscal ";
			$sql .= "AND		FC.empresa_id = FS.empresa_id ";
			$sql .= "AND		FS.estado = '0' ";
			$sql .= "AND		FF.numerodecuenta = CU.numerodecuenta ";
			$sql .= "AND		CU.ingreso = IG.ingreso ";
			$sql .= "AND		IG.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "AND		IG.paciente_id = PA.paciente_id ";
			$sql .= "AND		CU.plan_id = PL.plan_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
		
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			$datos_factura = array();
			if(sizeof($datos) == 1) 
      {
        $datos_factura = $datos[0];
        if($datos_factura['periodo'] != date("Ym"))
        {
          $this->Nota = "LA FACTURA NO PUEDE SER ANULADA SU FECHA DE REGISTRO ES DE UM MES ANTERIOR AL ACTUAl";
        }
			}
			return $datos_factura;
		}
		/**********************************************************************************
		* Funcion donde se obtiene la informacion de una factura contado y la cuenta 
		* asociada a ella que no esta en la tabla fac_facturas_contado
		*
		* @params char 	$prefijo Prefijo de la factura contado
		* @params int		$numerofactura Numero de la factura
		* @return array Datos de la factura
		***********************************************************************************/
		function ObtenerInformacionFacturaContadoF($prefijo,$numerofactura)
		{
			$datos = array();
			//$facturas = $this->ObtenerInfoFacturaContado($prefijo,$numerofactura,$clase = "1");
			
			$sql .= "SELECT	FC.prefijo,";
			$sql .= "				FC.factura_fiscal,";
			$sql .= "				FC.empresa_id, ";
			$sql .= "				FC.tercero_id, ";
			$sql .= "				FC.tipo_id_tercero, ";
			$sql .= "				FS.total_factura,";
      $sql .= "				TO_CHAR(FS.fecha_registro,'YYYYMM') AS periodo,";
			$sql .= "				CU.numerodecuenta, ";
			$sql .= "				CU.total_cuenta, ";
			$sql .= "				CU.valor_nocubierto,";
			$sql .= "				CU.valor_cubierto, ";
			$sql .= "				CU.ingreso, ";
			$sql .= "				PL.plan_descripcion, ";
			$sql .= "				PA.tipo_id_paciente ||' '||PA.paciente_id AS identificacion, ";
			$sql .= "				PA.primer_nombre ||' '||PA.segundo_nombre AS nombres, ";
			$sql .= "				PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos ";
			$sql .= "FROM		fac_facturas FC, ";
			$sql .= "				fac_facturas_cuentas FF, ";
			$sql .= "				fac_facturas FS, ";
			$sql .= "				cuentas CU, ";
			$sql .= "				ingresos IG, ";
			$sql .= "				pacientes PA, ";
			$sql .= "				planes PL ";
			$sql .= "WHERE	FC.prefijo = '".$prefijo."' ";
			$sql .= "AND		FC.factura_fiscal = ".$numerofactura." ";
			$sql .= "AND		FC.prefijo = FF.prefijo ";
			$sql .= "AND		FC.factura_fiscal = FF.factura_fiscal ";
			$sql .= "AND		FC.empresa_id = FF.empresa_id ";
			$sql .= "AND		FC.prefijo = FS.prefijo ";
			$sql .= "AND		FC.factura_fiscal = FS.factura_fiscal ";
			$sql .= "AND		FC.empresa_id = FS.empresa_id ";
			$sql .= "AND		FS.estado = '0' ";
			$sql .= "AND		FF.numerodecuenta = CU.numerodecuenta ";
			$sql .= "AND		CU.ingreso = IG.ingreso ";
			$sql .= "AND		IG.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "AND		IG.paciente_id = PA.paciente_id ";
			$sql .= "AND		CU.plan_id = PL.plan_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
		
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			$datos_factura = array();
			if(sizeof($datos) == 1) 
      {
        $datos_factura = $datos[0];
        if($datos_factura['periodo'] != date("Ym"))
        {
          $this->Nota = "LA FACTURA NO PUEDE SER ANULADA SU FECHA DE REGISTRO ES DE UM MES ANTERIOR AL ACTUAl";
        }
			}
			return $datos_factura;
		}
		/********************************************************************************** 
		* Funcion en donde se obtienen los prefijos que maneja la empresa 
		* 
		* @params string $documentos Rango de documentos para traer los prefijos, separados 
		*														 por coma
		* @return array datos de la tabla documentos
		***********************************************************************************/
		function ObtenerPrefijosAnulacion($documentos)
		{	
			$datos = array();
			$sql  = "SELECT prefijo ";
			$sql .= "FROM 	documentos ";
			$sql .= "WHERE	sw_estado IN ('0','1') ";
			$sql .= "AND		documento_id IN (".$documentos.")";
			$sql .= "ORDER BY 1 ";
			
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
		* Funcion donde se obtiene el tipo de factura, el estado en el que se encuentra y 
		* la cantidad de cuentas asociadas a la factura
		*
		* @params char	$prefijo Prefijo de la factura
		* @params int		$facturafiscal Numero de la factura
		* @params char	$empresa Identificador de la empresa
		* @return array Datos de la facfura
		***********************************************************************************/
		function ObtenerTipoFactura($prefijo,$facturafiscal,$empresa)
		{
			$datos = array(); 
			$sql .= "SELECT	FF.sw_clase_factura, ";
			$sql .= "				FF.estado, ";
			$sql .= "				COUNT(FC.*) AS cuentas ";
			$sql .= "FROM		fac_facturas FF LEFT JOIN ";
			$sql .= "				fac_facturas_cuentas FC ";
			$sql .= "				ON(	FF.factura_fiscal = FC.factura_fiscal AND ";
			$sql .= "						FF.prefijo = FC.prefijo AND ";
			$sql .= "						FF.empresa_id = FC.empresa_id ) ";
			$sql .= "WHERE	FF.factura_fiscal = ".$facturafiscal." ";
			$sql .= "AND		FF.prefijo = '".$prefijo."' ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			$sql .= "GROUP BY 1,2 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
	
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		* Funcion donde se evalua si una factura se puede anular o no
		*
		* @params char	$prefijo Prefijo de la factura
		* @params int		$facturafiscal Numero de la factura
		* @params char	$empresa Identificador de la empresa		
		* @return boolean True se puede anular, False, no se puede anular
		***********************************************************************************/
		function IsAnulacion($prefijo,$facturafiscal,$empresa)
		{
			$datos = array(); 
			$sql .= "SELECT	EN.sw_estado, ";
			$sql .= "				COALESCE(CM.tipo_bloqueo_id,'00') AS tipo_bloqueo_id ";
			$sql .= "FROM		fac_facturas FF LEFT JOIN ";
			$sql .= "				(	SELECT	EN.sw_estado, ";
			$sql .= "									ED.factura_fiscal,";
			$sql .= "									ED.prefijo ";
			$sql .= "					FROM		envios_detalle ED, ";
			$sql .= "									envios EN ";
			$sql .= "					WHERE		ED.envio_id = EN.envio_id ";
			$sql .= "					AND			ED.empresa_id = '".$empresa."' ";
			$sql .= "					AND			EN.sw_estado != '2' ";
			$sql .= "				) AS EN ";
			$sql .= "				ON( EN.prefijo = FF.prefijo AND ";
			$sql .= "						EN.factura_fiscal = FF.factura_fiscal  ";
			$sql .= "					) ";
			$sql .= "				LEFT JOIN cg_movimientos_contables CM ";
			$sql .= "				ON(CM.documento_contable_id = FF.documento_contable_id )";
			$sql .= "WHERE	FF.factura_fiscal = ".$facturafiscal." ";
			$sql .= "AND		FF.prefijo = '".$prefijo."' ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
	
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();

			if($datos['sw_estado'] || $datos['tipo_bloqueo_id'] !== '00') return false;
			
			return true;
		}
		/**
		* Funcion que permite anular la factura
		*
		* @params array	$cuenta Datos de la cuenta
		* @params array $factura Datos de la factura
		* @params array $datos Datos del la anulacion, motivo descripcion
		* @params char	$empresa identificador de la empresa
		* @return boolean
		*/		
		function AnularFactura($cuenta,$factura,$datos,$empresa)
		{
      list($dbconn) = GetDBconn();
			//$dbconn->debug = true;
      $dbconn->BeginTrans();

      //para ver si ya tiene una cuenta activa
      $sql  = "SELECT IG.ingreso, ";
			$sql .= "				CU.numerodecuenta ";
			$sql .= "FROM		ingresos IG,";
			$sql .= "				cuentas CU ";
			$sql .= "WHERE 	IG.estado = '1' ";
			$sql .= "AND 		IG.paciente_id = '".$cuenta['tipoId']."' ";
			$sql .= "AND 		IG.tipo_id_paciente = '".$cuenta['identificacion']."' ";
      $sql .= "AND		IG.ingreso = CU.ingreso ";
			$sql .= "AND 		CU.empresa_id = '".$empresa."'";
			$sql .= "AND 		CU.estado = '1' ";
      
			$rst = $dbconn->Execute($sql);
      if ($dbconn->ErrorNo() != 0) 
			{
        $this->frmError['MensajeError'] = "Query 1->AnularFactura <br> Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
			if($datos['opcion'] != '1')
			{
				$result = $this->IngresarAuditoria($factura,$empresa,$datos);
			
				if (!$result)
				{
					$dbconn->RollbackTrans();
					return false;
				}
			}
      $estado = "1";
			if(!$rst->EOF)	$estado = "2";

			$result = $this->LiberarCuenta($cuenta['numerodecuenta'],$cuenta['ingreso'],$estado);
			if (!$result)
			{
        $dbconn->RollbackTrans();
        return false;
      }
      
			$sql = "";
 			$apro = ModuloGetVar('app','Facturacion_Fiscal','CargoAprovechamiento_'.$empresa);
			$cargo = ModuloGetVar('app','Facturacion_Fiscal','CargoDescuento_'.$empresa);
	    
			$descuento = $this->BuscarCargoAjusteDes($cuenta['numerodecuenta'],$cargo);
			if(sizeof($descuento) > 0)
			{
				$sql .= "DELETE FROM cuentas_detalle ";
				$sql .= "WHERE	transaccion =".$descuento['transaccion']." ";
			}
      $aprovecho = $this->BuscarCargoAjusteApro($cuenta['numerodecuenta'],$apro);

      if(sizeof($aprovecho) > 0)
      {
        $sql .= "DELETE FROM cuentas_detalle ";
				$sql .= "WHERE	transaccion = ".$aprovecho['transaccion']." ";
      }
			
			if($sql != "")
			{
				$rst = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->frmError['MensajeError'] = "Query 5->AnularFactura <br> Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}

			$dbconn->CommitTrans();
			return true;
		}
		/**********************************************************************************
		* Funcion que permite anular una factura agrupada
		*
		* @params array $factura Datos de la factura
		* @params array $datos Datos del la anulacion, motivo descripcion
		* @params char	$empresa identificador de la empresa
		* @return boolean		
		***********************************************************************************/		
		function AnularFacturaAgrupada($factura,$datos,$empresa)
		{
      list($dbconn) = GetDBconn();
      $dbconn->BeginTrans();
			
			$result = $this->IngresarAuditoria($factura,$empresa,$datos);

      if (!$result)
      {
        $dbconn->RollbackTrans();
        return false;
      }
	
			$sql  = "UPDATE cuentas ";
			$sql .= "SET		estado = '3' ";
			$sql .= "WHERE	numerodecuenta IN ( ";
			$sql .= "					SELECT	numerodecuenta ";
			$sql .= "					FROM		fac_facturas_cuentas ";
			$sql .= "					WHERE 	prefijo = '".$factura['prefijo']."' ";
			$sql .= "					AND			factura_fiscal = '".$factura['factura_fiscal']."' ";
			$sql .= "				); ";
			
			$sql .= "UPDATE ingresos ";
			$sql .= "SET		estado = '0',";
			$sql .= "				fecha_cierre = NOW() ";
			$sql .= ",sw_apertura_admision = '1' ";
			$sql .= "WHERE	ingreso IN ( ";
			$sql .= "					SELECT	CU.ingreso ";
			$sql .= "					FROM		fac_facturas_cuentas  FF, ";
			$sql .= "									cuentas CU ";
			$sql .= "					WHERE 	FF.prefijo = '".$factura['prefijo']."' ";
			$sql .= "					AND			FF.factura_fiscal = '".$factura['factura_fiscal']."' ";
			$sql .= "					AND			FF.empresa_id = '".$empresa."' ";
			$sql .= "					AND			FF.numerodecuenta = CU.numerodecuenta ";
			$sql .= "				);";

			$rst = $dbconn->Execute($sql);
      if ($dbconn->ErrorNo() != 0)
      {
				$this->frmError['MensajeError'] = "Query 1->AnularFacturaAgrupada <br> Error DB : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
        return false;
      }

      $dbconn->CommitTrans();
      return true;
		}
		/**********************************************************************************
		* Funcion donde se ingresa la informacion sobre la anulacion de la factura
		*
		* @params array $factura Datos de la factura
		* @params char	$empresa identificador de la empresa
		* @params array $datos Datos del la anulacion, motivo descripcion
		* @params char	$estado (default '2') Estado que se desea colocar a la factura 
		* @return boolean
		***********************************************************************************/
		function IngresarAuditoria($factura,$empresa,$datos,$estado = '2')
		{
			$sql  = "UPDATE	fac_facturas ";
			$sql .= "SET 		estado = '".$estado."' ";
			$sql .= "WHERE 	prefijo = '".$factura['prefijo']."' ";
			$sql .= "AND		factura_fiscal = ".$factura['factura_fiscal']." ";
			$sql .= "AND		empresa_id = '".$empresa."'; ";
			
      $sql .= "INSERT INTO auditoria_anulacion_fac_facturas";
			$sql .= "				(	empresa_id, ";
			$sql .= "					prefijo, ";
			$sql .= "					factura_fiscal, ";
			$sql .= "					observacion, ";
			$sql .= "					fecha_registro, ";
			$sql .= "					usuario_id, ";
			$sql .= "					motivo_id ) ";
			$sql .= "VALUES	(	'".$empresa."', ";
			$sql .= "        	'".$factura['prefijo']."',";
			$sql .= "					 ".$factura['factura_fiscal'].", ";
			$sql .= "					'".$datos['observacion']."',";
			$sql .= "					 NOW(),";
			$sql .= "					 ".UserGetUID().", ";
			$sql .= "					 ".$datos['motivo_id'].")";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$sql  = "UPDATE cg_movimientos_contables ";
			$sql .= "SET		sw_estado = '0' ";
			$sql .= "WHERE	documento_contable_id  = (";
			$sql .= "				SELECT	documento_contable_id ";
			$sql .= "				FROM		fac_facturas ";
			$sql .= "				WHERE 	prefijo = '".$factura['prefijo']."' ";
			$sql .= "				AND			factura_fiscal = '".$factura['factura_fiscal']."' ";
			$sql .= "				AND			empresa_id = '".$empresa."') ";
			$sql .= "AND		tipo_bloqueo_id = '00'; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se obtiene informacion de la factura credito y el tercero
		* 
		* @params int $numerodecuenta Numero de cuenta
		* @return array datos de la factura
		***********************************************************************************/
		function ObtenerInformacionFactura($prefijo,$factura,$empresa,$estado = '1')
		{
			$datos = array();
			
			$sql .= "SELECT	FF.total_factura, ";
			$sql .= "				FF.prefijo, ";
			$sql .= "				FF.factura_fiscal, ";
      $sql .= "				TO_CHAR(FF.fecha_registro,'YYYYMM') AS periodo,";
			$sql .= "				TE.nombre_tercero, ";
			$sql .= "				PL.plan_descripcion, ";
			$sql .= "				PL.plan_id, ";
			$sql .= "				FC.numerodecuenta, ";
			$sql .= "				TE.tercero_id, ";
			$sql .= "				TE.tipo_id_tercero ";
			$sql .= "FROM		fac_facturas FF, ";
			$sql .= "				fac_facturas_cuentas FC, ";
			$sql .= "				terceros TE, ";
			$sql .= "				planes PL ";
			$sql .= "WHERE	FF.prefijo = '".$prefijo."' ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		FF.factura_fiscal = ".$factura." ";
			$sql .= "AND		FC.prefijo = FF.prefijo ";
			$sql .= "AND		FC.empresa_id = FF.empresa_id ";
			$sql .= "AND		FC.factura_fiscal = FF.factura_fiscal ";
			$sql .= "AND		FF.tercero_id = TE.tercero_id ";
			$sql .= "AND		FF.tipo_id_tercero = TE.tipo_id_tercero ";
			$sql .= "AND		FF.sw_clase_factura = '".$estado."' ";
			$sql .= "AND		FF.estado NOT IN ('2','3') ";
			$sql .= "AND		FF.plan_id = PL.plan_id ";
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
		
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
      if($datos['periodo'] != date("Ym"))
      {
        $this->Nota = "LA FACTURA NO PUEDE SER ANULADA SU FECHA DE REGISTRO ES DE UM MES ANTERIOR AL ACTUAl";
      }
      		
			return $datos;
		}
		/**********************************************************************************
		* Funcion donde se obtiene la informacion de una cuenta
		*
		* @params int		$cuenta Numero de cuenta
		* @return array Datos de la cuenta
		***********************************************************************************/
		function ObtenerInformacionCuenta($cuenta)
		{
			$datos = array();
			
			$sql .= "SELECT	IG.ingreso,";
			$sql .= "				PA.tipo_id_paciente, ";
			$sql .= "				PA.paciente_id, ";
			$sql .= "				PA.primer_nombre ||' '||PA.segundo_nombre AS nombres, ";
			$sql .= "				PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos ";
			$sql .= "FROM		cuentas CU, ";
			$sql .= "				ingresos IG, ";
			$sql .= "				pacientes PA ";
			$sql .= "WHERE	CU.ingreso = IG.ingreso ";
			$sql .= "AND		IG.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "AND		IG.paciente_id = PA.paciente_id ";
			$sql .= "AND		CU.numerodecuenta = ".$cuenta." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
		
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos[0];
		}
		/**********************************************************************************
		* funcion donde se anula una factura sea contado o credito y se llama a las funciones 
		* que anulan o liberan una cuenta segun lo se indique
		*
		* @params char 	$prefijo Prefijo de la factura que se va a anular
		* @params int		$factura Numero de la factura que se va a anular
		* @params char	$opcion	 Indica si la la cuenta se va a anular o a liberar
		* @params int		$ingreso Numero del ingreso asociado a la factura que se anulara
		* @parmas int 	$cuenta	 Numero de cuenta asociado a la factura que se anulara
		* @return boolean Indica si la factura se anulo o no
		***********************************************************************************/
		function AnularFacturaContadoCredito($prefijo,$factura,$opcion,$ingreso,$cuenta)
		{
			$result = true;
			if($opcion == '0')
				$result = $this->AnularCuenta($ingreso,$cuenta);
			else if($opcion == '1')
				$result = $this->LiberarCuenta($cuenta,$ingreso);
			
			if($result)
			{
				$sql  = "UPDATE fac_facturas ";
				$sql .= "SET 		estado = '2' ";
				$sql .= "WHERE 	prefijo = '".$prefijo."' ";
				$sql .= "AND		factura_fiscal = ".$factura."; ";
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				
				$sql  = "UPDATE fac_facturas_contado ";
				$sql .= "SET 		estado = '2' ";
				$sql .= "WHERE 	prefijo = '".$prefijo."' ";
				$sql .= "AND		factura_fiscal = ".$factura." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			}
			else
			{
				$this->frmError['Mensaje'] .= "LA CUENTA ASOCIADA A LA FACTURA CONTADO ".$prefijo." ".$factura.", NO SE PUEDE ANULAR";
			}
			return $result;
		}
		/**********************************************************************************
		* Funcion donde se hace el proceso de liberar una cuenta 
		* 
		* @params int $cuenta Numero de cuenta
		* @params int $ingreso Numero de ingreso asociado a la cuenta
		* @return boolean Indica si la cuenta se libero o no 
		***********************************************************************************/
		function LiberarCuenta($cuenta,$ingreso,$estado = "1")
		{
			$sql  = "UPDATE cuentas ";
			$sql .= "SET 		estado = '".$estado."' ";
			$sql .= "WHERE 	numerodecuenta = ".$cuenta."; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$sql  = "UPDATE ingresos ";
			$sql .= "SET 	estado='2' ";
			//$sql .= "	fecha_cierre='now()' ";
			$sql .= ",sw_apertura_admision = '1' ";
			$sql .= "WHERE 	ingreso = ".$ingreso."; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se hace el proceso de anular una cuenta 
		* 
		* @params int $cuenta Numero de cuenta
		* @params int $ingreso Numero de ingreso asociado a la cuenta
		* @return boolean Indica si la cuenta se anulo o no 
		***********************************************************************************/
		function AnularCuenta($ingreso,$cuenta)
		{
			$result = true;
			$sql = "SELECT COUNT(*) FROM cuentas_detalle WHERE numerodecuenta = ".$cuenta." ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      if($rst->fields[0] > 0 && !$rst->EOF)
      	$result = $this->AnularOrdenServicio($cuenta);
						
			if($result)
			{				
				if(!$rst = $this->ConexionBaseDatos($sql))	return false;
				
				$sql  = "UPDATE ingresos ";
				$sql .= "SET 		estado='0',";
				$sql .= "				fecha_cierre='NOW()' ";
				$sql .= "WHERE 	ingreso = ".$ingreso."; ";
					
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				
				$sql  = "UPDATE cuentas ";
				$sql .= "SET 		estado='5' ";
				$sql .= "WHERE 	numerodecuenta = ".$cuenta."; ";
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;     
			}
			return $result;
		}
		/**********************************************************************************
		* Funcion donde se anulan las ordenes de servicio segun sean manuales o del sistema
		* 
		* @params	int	$cuenta	Numero de cuenta asociado a la orden de servicio
		* @return boolean Indioca si se anulo o no la orden de servicio
		***********************************************************************************/
		function AnularOrdenServicio($cuenta)
		{
			$cadena = "";
			$datos = array();
			
			$sql .= "SELECT	HS.evolucion_id,";
			$sql .= "				OM.hc_os_solicitud_id ";
			$sql .= "FROM		hc_os_solicitudes HS, ";
			$sql .= "				os_maestro OM ";
			$sql .= "WHERE	OM.hc_os_solicitud_id = HS.hc_os_solicitud_id ";
			$sql .= "AND		OM.numerodecuenta = ".$cuenta." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$cadena .= $rst->fields[1]." ";
				$rst->MoveNext();
		  }
			$rst->Close();
			
			if($cadena != "")
			{
				$cadena = trim($cadena);
				$cadena = str_replace(" ",",",$cadena);
				
				$estado = "1";
				if($datos[0]['evolucion_id']) $estado = "8";
				
				$sql  = "UPDATE	hc_os_solicitudes ";
				$sql .= "SET		sw_estado = '2' ";
				$sql .= "WHERE	hc_os_solicitud_id IN (".$cadena."); ";
				
				$sql .= "UPDATE	os_maestro ";
				$sql .= "SET		sw_estado = '".$estado."' ";
				$sql .= "WHERE	numerodecuenta = ".$cuenta."; ";
				
				$sql .= "UPDATE os_maestro_cargos ";
				$sql .= "SET		transaccion = NULL ";
				$sql .= "WHERE	transaccion IN (";
				$sql .= "				SELECT	transaccion ";
				$sql .= "				FROM		cuentas_detalle ";
				$sql .= "				WHERE		numerodecuenta = ".$cuenta." );";
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			}
			return true;
		}
		/**********************************************************************************
		* @return 
		************************************************************************************/
		function BuscarCargoAjusteDes($cuenta,$cargo)
		{
			$datos = array();

			$sql  = "SELECT transaccion ";
			$sql .= "FROM 	cuentas_detalle ";
			$sql .= "WHERE 	numerodecuenta = ".$cuenta." ";
			$sql .= "AND 		cargo = '".$cargo."'";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			if(!$rst->EOF)				
			{   
				$datos = $rst->GetRowAssoc($ToUpper = false);   
			}
			$rst->Close();
			
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function BuscarCargoAjusteApro($cuenta,$apro)
	  {
			$datos = array();
			$sql  = "SELECT transaccion ";
			$sql .= "FROM 	cuentas_detalle ";
			$sql .= "WHERE 	numerodecuenta = ".$cuenta." ";
			$sql .= "AND 		cargo = '".$apro."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			if(!$rst->EOF)				
			{   
				$datos = $rst->GetRowAssoc($ToUpper = false);   
			}
			$rst->Close();
			
			return $datos;
	  }
		/**********************************************************************************
		*
		***********************************************************************************/
		function AnularFacturaCajaRapida($datos,$empresa)
		{
			$sql  = "INSERT INTO cajas_rapidas_auditoria_anulaciones ";
			$sql .= "				(empresa_id ,";
			$sql .= "				prefijo ,";
			$sql .= "				factura_fiscal ,";
			$sql .= "				prefijo_referencia ,";
			$sql .= "				factura_fiscal_referencia,";
			$sql .= "				numerodecuenta ,";
			$sql .= "				observacion ,";
			$sql .= "				motivo_anulacion_id,";
			$sql .= "				sw_cuenta	,";
			$sql .= "				usuario_id,";
			$sql .= " 			fecha_registro ) ";
			$sql .= "VALUES (";
			$sql .= "				'".$empresa."' ,";
			$sql .= "				'".$datos['prefijo']."' ,";
			$sql .= "				 ".$datos['factura'].",";
			$sql .= "				'".$datos['prefijoC']."' ,";
			$sql .= "				 ".$datos['facturaC'].",";
			$sql .= "				 ".$datos['cuenta']." ,";
			$sql .= "				'".$datos['observacion']."' ,";
			$sql .= "				 ".$datos['motivo_id'].",";
			$sql .= "				'".$datos['sw_cuenta']."',";
			$sql .= "				 ".$datos['usuario'].", ";
			$sql .= "				 NOW() ";
			$sql .= "				);";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		
		/**********************************************************************************
		*
		***********************************************************************************/
		function AnularFacturaNota($documento,$factura,$datos,$cuenta,$empresa,$contado = array())
		{		
			if (empty($contado))
			{
				if(!$datos['estado']) $datos['estado'] = "0";
				$contado = $this->ObtenerInfoFacturaContado($factura['prefijo'],$factura['factura_fiscal'],$datos['estado']);
			}
			$sql = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE; ";//Bloqueo de tabla 
			//$sql .= "ALTER TABLE fac_facturas DISABLE TRIGGER actualizar_saldo_factura;";
		//	$sql .= "ALTER TABLE fac_facturas DISABLE TRIGGER actualizar_log_movimientos;";
	
			list($this->dbconn) = GetDBConn();
			//$this->dbconn->debug=true;
			
			$this->dbconn->BeginTrans();
			$rst = $this->dbconn->Execute($sql);
			if ($this->dbconn->ErrorNo() != 0) 
			{
				$this->frmError['MensajeError'] = "Query 1->AnularFacturaNota <br> Error DB : " . $this->dbconn->ErrorMsg();
        $this->dbconn->RollbackTrans();
        return false;
			}
			
			$sql  = "SELECT prefijo,numeracion FROM documentos ";
			$sql .= "WHERE 	documento_id = ".$documento." ";
			$sql .= "AND		empresa_id = '".$empresa."' ";
					
			$numeracion = $this->ObtenerNumeracion($sql);
			$observacion = "'Nota creada para anular una factura que ya ha sido enviada o pasada por interface contable'";
			
			if($cuenta['agrupado'] > 1)
			{
				$sql  = "UPDATE cuentas ";
				$sql .= "SET		estado = '3' ";
				$sql .= "WHERE	numerodecuenta IN ( ";
				$sql .= "					SELECT	numerodecuenta ";
				$sql .= "					FROM		fac_facturas_cuentas ";
				$sql .= "					WHERE 	prefijo = '".$factura['prefijo']."' ";
				$sql .= "					AND			factura_fiscal = '".$factura['factura_fiscal']."' ";
				$sql .= "				); ";
				
				$rst = $this->dbconn->Execute($sql);
					
				if ($this->dbconn->ErrorNo() != 0) 
				{
					$this->frmError['MensajeError'] = "Query -1->AnularFacturaNota <br> Error DB : " . $this->dbconn->ErrorMsg();
					$this->dbconn->RollbackTrans();
					return false;
				}		
			}
			else
			{
				if($datos['opcion'] == '0')
					$result = $this->AnularCuenta($cuenta['numerodecuenta'],$cuenta['ingreso']);
				else if($datos['opcion'] == '1')
					$result = $this->LiberarCuenta($cuenta['numerodecuenta'],$cuenta['ingreso'],"2");
				
				if (!$result) 
				{
					$this->frmError['MensajeError'] = "Query -2->AnularFacturaNota <br> Error DB : " . $this->dbconn->ErrorMsg();
					$this->dbconn->RollbackTrans();
					return false;
				}
			}
			
			$sql  = "INSERT INTO  notas_credito_anulacion_facturas( ";
			$sql .= "				empresa_id,";
			$sql .= "				prefijo,";
			$sql .= "				nota_credito_id,";
			$sql .= "				prefijo_factura,";
			$sql .= "				factura_fiscal,";
			$sql .= "				valor_nota,";
			$sql .= "				fecha_registro,";
			$sql .= "				usuario_id,";
			$sql .= "				tipo_id_tercero, ";
			$sql .= "				tercero_id, ";
			$sql .= "				documento_id) ";
			$sql .= "VALUES (";
			$sql .= "			'".$empresa."', ";
			$sql .= "			'".$numeracion['prefijo']."', ";
			$sql .= "		 	 ".$numeracion['numeracion'].", ";
			$sql .= "			'".$factura['prefijo']."', ";
			$sql .= "		 	 ".$factura['factura_fiscal'].", ";
			$sql .= "		 	 ".$factura['valor_factura'].", ";
			$sql .= "		 		NOW(), ";
			$sql .= "		 	 ".UserGetUID().", ";
			$sql .= "			'".$factura['tipo_id']."', ";
			$sql .= "			'".$factura['tercero_id']."', ";
			$sql .= "		 	 ".$documento." ";
			$sql .= "		);";
						
			$rst = $this->dbconn->Execute($sql);
			if ($this->dbconn->ErrorNo() != 0) 
			{
				$this->frmError['MensajeError'] = "Query 4->AnularFacturaNota <br> Error DB : " . $this->dbconn->ErrorMsg();
				$this->dbconn->RollbackTrans();
				return false;
			}		
			
			$i=1;
			if(!empty($contado) && !($cuenta['agrupado'] >1))
			{
				$i=2;
				$sql  = "INSERT INTO  notas_credito_anulacion_facturas( ";
				$sql .= "				empresa_id,";
				$sql .= "				prefijo,";
				$sql .= "				nota_credito_id,";
				$sql .= "				prefijo_factura,";
				$sql .= "				factura_fiscal,";
				$sql .= "				valor_nota,";
				$sql .= "				fecha_registro,";
				$sql .= "				usuario_id,";
				$sql .= "				tipo_id_tercero, ";
				$sql .= "				tercero_id, ";
				$sql .= "				documento_id) ";
				$sql .= "VALUES (";
				$sql .= "			'".$empresa."', ";
				$sql .= "			'".$numeracion['prefijo']."', ";
				$sql .= "		 	 ".($numeracion['numeracion']+1).", ";
				$sql .= "			'".$contado['prefijo']."', ";
				$sql .= "		 	 ".$contado['factura_fiscal'].", ";
				$sql .= "		 	 ".$contado['total_factura'].", ";
				$sql .= "		 		NOW(), ";
				$sql .= "		 	 ".UserGetUID().", ";
				$sql .= "			'".$contado['tipo_id_tercero']."', ";
				$sql .= "			'".$contado['tercero_id']."', ";
				$sql .= "		 	 ".$documento." ";
				$sql .= "		);";

				$rst = $this->dbconn->Execute($sql);
				if ($this->dbconn->ErrorNo() != 0) 
				{
					$this->frmError['MensajeError'] = "Query 4.1->AnularFacturaNota <br> Error DB : " . $this->dbconn->ErrorMsg();
					$this->dbconn->RollbackTrans();
					return false;
				}
				
				$sql  = "UPDATE fac_facturas ";
				$sql .= "SET 		estado = '3' ";
				$sql .= "WHERE 	prefijo = '".$contado['prefijo']."' ";
				$sql .= "AND		factura_fiscal = ".$contado['factura_fiscal']."; ";
				$sql .= "UPDATE fac_facturas_contado ";
				$sql .= "SET 		estado = '3' ";
				$sql .= "WHERE 	prefijo = '".$contado['prefijo']."' ";
				$sql .= "AND		factura_fiscal = ".$contado['factura_fiscal']."; ";
				
				$rst = $this->dbconn->Execute($sql);
				if ($this->dbconn->ErrorNo() != 0) 
				{
					$this->frmError['MensajeError'] = "Query 4.2->AnularFacturaNota <br> Error DB : " . $this->dbconn->ErrorMsg();
					$this->dbconn->RollbackTrans();
					return false;
				}
			}
			
			$sql  = "UPDATE documentos ";
			$sql .= "SET 		numeracion = numeracion + $i ";
			$sql .= "WHERE 	documento_id = ".$documento." ";
			$sql .= "AND 		empresa_id = '".$empresa."'; "; 
			
			$rst = $this->dbconn->Execute($sql);
			if ($this->dbconn->ErrorNo() != 0) 
			{
				$this->frmError['MensajeError'] = "Query 4->AnularFacturaNota <br> Error DB : " . $this->dbconn->ErrorMsg();
				$this->dbconn->RollbackTrans();
				return false;
			}		
			
			$result = $this->IngresarAuditoria($factura,$empresa,$datos,"3");
			if(!$result)
			{
				$this->frmError['MensajeError'] = "Query 2->AnularFacturaNota <br> Error DB : " . $this->dbconn->ErrorMsg();
        $this->dbconn->RollbackTrans();
        return false;
			}
			
			//$sql .= "ALTER TABLE fac_facturas ENABLE TRIGGER actualizar_saldo_factura;";
		//	$sql .= "ALTER TABLE fac_facturas ENABLE TRIGGER actualizar_log_movimientos;";
			
			//$rst = $this->dbconn->Execute($sql);
			if ($this->dbconn->ErrorNo() != 0) 
			{
				$this->frmError['MensajeError'] = "Query 4->AnularFacturaNota <br> Error DB : " . $this->dbconn->ErrorMsg();
				$this->dbconn->RollbackTrans();
				return false;
			}
			
			$this->dbconn->CommitTrans();
			$this->frmError['notas'] = $numeracion;
			return true;
		}
		/**********************************************************************************
		* Funcion donde se obtiene la informacion de una cuenta
		*
		* @params int		$cuenta Numero de cuenta
		* @return array Datos de la cuenta
		***********************************************************************************/
		function ObtenerInfoFacturaContado($prefijo,$factura, $clase = "0")
		{
			$datos = array();
			$sql  = "SELECT	numerodecuenta ";
			$sql .= "FROM		fac_facturas_cuentas ";
			$sql .= "WHERE 	prefijo = '".$prefijo."' ";
			$sql .= "AND		factura_fiscal = '".$factura."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
		
			if(!$rst->EOF)
			{
				$cuenta = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			$sql  = "SELECT	FF.prefijo,FF.factura_fiscal,FF.total_factura,FF.tipo_id_tercero,FF.tercero_id ";
			$sql .= "FROM		fac_facturas FF,fac_facturas_cuentas FC ";
			$sql .= "WHERE	FC.numerodecuenta = ".$cuenta['numerodecuenta']." ";
			$sql .= "AND		FF.prefijo = FC.prefijo ";
			$sql .= "AND		FF.factura_fiscal = FC.factura_fiscal ";
			$sql .= "AND		FF.empresa_id = FC.empresa_id ";
			$sql .= "AND		FF.sw_clase_factura = '".$clase."'::bpchar ";
			$sql .= "AND		FF.estado IN ('0'::bpchar,'1'::bpchar) ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
		
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
		/****************************************************************************************
		*
		* @return array 
		*****************************************************************************************/
		function ObtenerNumeracion($sql)
		{
			$datos = array();
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
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
			if(is_object($this->dbconn))
			{
				$rst = $this->dbconn->Execute($sql);
				if ($this->dbconn->ErrorNo() != 0)
				{
					$this->frmError['MensajeError'] = "ERROR DB : " . $this->dbconn->ErrorMsg();
					return false;
				}
				return $rst;
			}
			
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
