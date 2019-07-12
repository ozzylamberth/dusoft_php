<?php
	/*********************************************************************************************
	* $Id: Caja.class.php,v 1.10 2011/02/18 15:36:20 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.10 $
	*
	* @autor Hugo F. Manrique
	***********************************************************************************************/
	class Caja
	{
		function Caja(){}
		/**********************************************************************************
		*
		* @return 
		************************************************************************************/
		function ValidarRecibosSinCuadrar($caja,$empresa,$usuario)
		{
			$sql  = "SELECT	CR.descripcion AS caja ";
			$sql .= "FROM		fac_facturas FF,";
			$sql .= "				fac_facturas_contado FC,";
			$sql .= "				cajas_rapidas CR ";
			$sql .= "WHERE	CR.caja_id = ".$caja." ";
			$sql .= "AND 		FC.cierre_caja_id ISNULL  ";
			$sql .= "AND 		FC.caja_id = CR.caja_id  ";
			$sql .= "AND 		FC.empresa_id = '".$empresa."' ";
			$sql .= "AND 		FC.usuario_id NOT IN( ".$usuario." ) ";
			$sql .= "AND 		FF.empresa_id = FC.empresa_id  ";
			$sql .= "AND 		FF.prefijo = FC.prefijo  ";
			$sql .= "AND 		FF.factura_fiscal = FC.factura_fiscal ";
			$sql .= "AND 		FF.tipo_factura IN('0','2') ";
			$sql .= "AND 		FF.estado = '0' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
			if(!$rst->EOF)
			{
				$retorno[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $retorno;
		}
		/**********************************************************************************
		*
		* @return 
		************************************************************************************/
		function ObtenerPermisosCaja($usuario,$departemento,$empresa)
		{
			$sql  = "SELECT CU.descripcion,";
			$sql .= "				CR.servicio, ";
			$sql .= "				CR.via_ingreso, ";
			$sql .= "				UC.caja_id, ";
			$sql .= "				CR.departamento, ";
			$sql .= "				CR.descripcion AS caja,";
			$sql .= "				CR.prefijo_fac_contado,";
			$sql .= "				CR.prefijo_fac_credito,";
			$sql .= "				CR.documento_recibo_caja,";
			$sql .= "				DP.descripcion AS departamento_descripcion, ";
			$sql .= "				DP.centro_utilidad, ";
			$sql .= "				DP.empresa_id, ";
			$sql .= "				EM.razon_social,";
			$sql .= "				CR.cuenta_tipo_id ";
			$sql .= "FROM		userpermisos_cajas_rapidas UC, ";
			$sql .= "				empresas EM,";
			$sql .= "				departamentos DP,";
			$sql .= "				cajas_rapidas CR,";
			$sql .= "				centros_utilidad CU ";
			$sql .= "WHERE	UC.usuario_id = ".$usuario." ";
			$sql .= "AND 		CR.departamento = '".$departemento."' ";
			$sql .= "AND 		EM.empresa_id = '".$empresa."' ";			
			$sql .= "AND 		CR.departamento = DP.departamento ";
			$sql .= "AND 		CR.cuenta_tipo_id IN ('04','05') ";
			$sql .= "AND 		DP.empresa_id = EM.empresa_id ";
			$sql .= "AND 		UC.caja_id = CR.caja_id ";
			$sql .= "AND 		CU.centro_utilidad = DP.centro_utilidad ";
			$sql .= "AND 		CU.empresa_id = DP.empresa_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[11]][$rst->fields[8]][$rst->fields[5]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
		/**********************************************************************************
		*
		* @return 
		************************************************************************************/
		function ObtenerEntidadesConfirma()
		{
			$sql  = "SELECT	entidad_confirma,";
			$sql .= "				descripcion ";
			$sql .= "FROM		confirmacion_entidades ";
			$sql .= "ORDER BY entidad_confirma ";
			
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
		* @return 
		************************************************************************************/
		function ObtenerBancos()
		{
			$sql  = "SELECT	banco,";
			$sql .= "				descripcion ";
			$sql .= "FROM		bancos ";
			
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
		* @return 
		************************************************************************************/
		function IngresarTemporalCheques($datos,$empresa,$c_util) 
		{
			$sql = "SELECT NEXTVAL('public.cheques_mov_cheque_mov_id_seq') ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$cheque_id = $rst->fields[0];
			
			$sql  = "SELECT	NEXTVAL('public.confirmacion_che_consecutivo_seq') ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$consecutivo = $rst->fields[0];
			
			$fc = explode("/",$datos['fecha_cheque']);
			$ft = explode("/",$datos['fecha_transaccion']);
			$fd = explode("/",$datos['fecha_confirma']);
			
			$sql  = "INSERT INTO tmp_confirmacion_che(";
			$sql .= "		cheque_mov_id,";
			$sql .= "		entidad_confirma,";
			$sql .= "		funcionario_confirma,";
			$sql .= "		numero_confirmacion, ";
			$sql .= "		fecha,";
			$sql .= "		usuario_id,";
			$sql .= "		consecutivo,";
			$sql .= "		numerodecuenta";
			$sql .= "		)";
			$sql .= "VALUES( ";
			$sql .= "		".$cheque_id.",";
			$sql .= "		'".$datos['entidad']."',";
			$sql .= "		'".$datos['funcionario']."',";
			$sql .= "		'".$datos['numero']."',";
			$sql .= "		'".$fd[2]."-".$fd[1]."-".$fd[0]."',";
			$sql .= "		".UserGetUID().",";
			$sql .= "		".$consecutivo.",";
			$sql .= "		".$datos['numerodecuenta']."); ";
			
			$sql .= "INSERT INTO tmp_cheques_mov( ";
			$sql .= "				cheque_mov_id, ";
			$sql .= "				empresa_id, ";
			$sql .= "				centro_utilidad, ";
			$sql .= "				numerodecuenta, ";
			$sql .= "				banco, ";
			$sql .= "				cta_cte, ";
			$sql .= "				cheque, ";
			$sql .= "				girador, ";
			$sql .= "				fecha_cheque, ";
			$sql .= "				total, ";
			$sql .= "				fecha, ";
			$sql .= "				usuario_id, ";
			$sql .= "				fecha_registro, ";
			$sql .= "				consecutivo";
			$sql .= "			) ";
			$sql .= "VALUES(";
			$sql .= "		".$cheque_id.",";
			$sql .= "		'".$empresa."',";
			$sql .= "		'".$c_util."',";
			$sql .= "		".$datos['numerodecuenta'].",";
			$sql .= "		'".$datos['banco']."',";
			$sql .= "		'".$datos['numero_cuenta']."',";
			$sql .= "		'".$datos['numero_cheque']."',";
			$sql .= "		'".$datos['girador']."',";
			$sql .= "		'".$fc[2]."-".$fc[1]."-".$fc[0]."',";
			$sql .= "		".$datos['valor'].",";
			$sql .= "		'".$ft[2]."-".$ft[1]."-".$ft[0]."',";
			$sql .= "		".UserGetUID().",";
			$sql .= "		NOW(),";
			$sql .= "		".$consecutivo." ); ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		*
		* @return 
		************************************************************************************/
		function ObtenerTarjetas()
		{
			$sql  = "SELECT	tarjeta,";
			$sql .= "				descripcion,";
			$sql .= "				comision,";
			$sql .= "				cuotas_maxima,";
			$sql .= "				sw_tipo ";
			$sql .= "FROM 	tarjetas ";
			$sql .= "WHERE 	sw_estado = '1' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[4]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
		/**********************************************************************************
		*
		* @return 
		************************************************************************************/
		function IngresarTemporalTrajetaCredito($datos,$empresa,$c_util)
		{
			$sql = "SELECT NEXTVAL('public.tarjetas_mov_credito_tarjeta_mov_id_seq');";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$tarjeta_mov = $rst->fields[0];

			$sql = "SELECT NEXTVAL('public.confirmacion_tar_consecutivo_seq');";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$consecutivo = $rst->fields[0];
			
			$fe = explode("/",$datos['fecha_expiracion']);
			$ft = explode("/",$datos['fecha_transaccion']);
			$fd = explode("/",$datos['fecha_confirma']);
			
			$sql  = "INSERT INTO tmp_confirmacion_tar";
			$sql .= "		(	tarjeta_mov_id,";
			$sql .= "			entidad_confirma,";
			$sql .= "			funcionario_confirma,";
			$sql .= "			numero_confirmacion, ";
			$sql .= "			fecha,";
			$sql .= "			usuario_id,";
			$sql .= "			consecutivo,";
			$sql .= "			numerodecuenta ";
			$sql .= "		)";
			$sql .= "VALUES(";
			$sql .= "			".$tarjeta_mov.",";
			$sql .= "			'".$datos['entidad']."',";
			$sql .= "			'".$datos['funcionario']."',";
			$sql .= "			'".$datos['numero']."',";
			$sql .= "			'".$fd[2]."-".$fd[1]."-".$fd[0]."',";
			$sql .= "			".UserGetUID().",";
			$sql .= "			".$consecutivo.",";
			$sql .= "			".$datos['numerodecuenta']."); ";
			
			$sql .= "INSERT into tmp_tarjetas_mov_credito(";
			$sql .= "			tarjeta_mov_id,";
			$sql .= "			tarjeta,";
			$sql .= "			tarjeta_numero,";
			$sql .= "			empresa_id,";
			$sql .= "			centro_utilidad,";
			$sql .= "			fecha,";
			$sql .= "			autorizacion,";
			$sql .= "			socio,";
			$sql .= "			fecha_expira,";
			$sql .= "			autorizado_por,";
			$sql .= "			total,";
			$sql .= "			usuario_id,";
			$sql .= "			fecha_registro,";
			$sql .= "			numerodecuenta,";
			$sql .= "			consecutivo)";
			$sql .= "VALUES(";
			$sql .= "			".$tarjeta_mov.",";
			$sql .= "			'".$datos['tarjeta']."',";
			$sql .= "			'".$datos['num_tarjeta']."',";
			$sql .= "			'".$empresa."',";
			$sql .= "			'".$c_util."',";
			$sql .= "			'".$ft[2]."-".$ft[1]."-".$ft[0]."',";
			$sql .= "			'".$datos['numero']."',";
			$sql .= "			'".$datos['socio']."',";
			$sql .= "			'".$fd[2]."-".$fd[1]."-".$fd[0]."',";
			$sql .= "			'".$datos['funcionario']."',";
			$sql .= "			".$datos['valor'].",";
			$sql .= "			".UserGetUID().",";
			$sql .= "			NOW(),";
			$sql .= "			".$datos['numerodecuenta'].", ";
			$sql .= "			".$consecutivo."); ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		*
		* @return 
		************************************************************************************/
		function IngresarTemporalTrajetaDebito($datos,$empresa,$c_util)
		{
			$sql  = "INSERT into tmp_tarjetas_mov_debito(";
			$sql .= "			empresa_id,";
			$sql .= "			centro_utilidad,";
			$sql .= "			autorizacion,";
			$sql .= "			total,";
			$sql .= "			numerodecuenta,";
			$sql .= "			tarjeta,";
			$sql .= "			tarjeta_numero";
			$sql .= "			)";
			$sql .= "VALUES(";
			$sql .= "			'".$empresa."',";
			$sql .= "			'".$c_util."',";
			$sql .= "			'".$datos['num_autorizacion']."',";
			$sql .= "			".$datos['valor'].",";
			$sql .= "			".$datos['numerodecuenta'].",";
			$sql .= "			'".$datos['tarjeta']."',";
			$sql .= "			'".$datos['num_tarjeta']."'); ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		*	Funcion donde se procede a eliminar los temporales de los pagos realizados
		* @params int $numerodecuenta Numero de la cuenta a la cual se le eliminaran 
		* los temporales
		*
		* @return boolean true o false si se realizo o no la operacion
		************************************************************************************/
		function EliminarTemporales($numerodecuenta)
		{
			$sql  = "DELETE FROM	tmp_confirmacion_tar	WHERE numerodecuenta = ".$numerodecuenta.";";
			$sql .= "DELETE FROM	tmp_confirmacion_che	WHERE numerodecuenta = ".$numerodecuenta.";";
			$sql .= "DELETE FROM  tmp_tarjetas_mov_debito	WHERE numerodecuenta = ".$numerodecuenta.";";
			$sql .= "DELETE FROM	tmp_tarjetas_mov_credito	WHERE numerodecuenta = ".$numerodecuenta.";";
			$sql .= "DELETE FROM	tmp_cheques_mov	WHERE numerodecuenta = ".$numerodecuenta.";";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		
		/**********************************************************************************
		*Funcion de pagos modificada para ingresar pagos de una cuenta sin facturar la cuenta 
		*Abonos a una cuenta ambulatoria
		***********************************************************************************/
		function IngresoPagosCuenta($datos,$rqst,$caja)
		{
			$this->ConexionTransaccion();
			$tercero = array();
			$numeracion = array();
			$retorno = array();
						
			if($datos['valor_total_paciente'] == 0 && $datos['valor_total_empresa'] == 0)
			{
				$estado = '3';
        if(!$this->ActualizarEstadoCuenta($datos['numerodecuenta'],$estado,"transaccion"))
					return false;
			}
			else
			{
				if($datos['valor_total_paciente'] > 0 || $rqst['h_total'])
				{
					$estado = '0';
          IncludeLib('funciones_facturacion');
					if(!$tercero = ResponsableFacturaPaciente($datos['tipo_id_paciente'],$datos['paciente_id'],$caja['empresa_id'],&$this->dbconn))
						return false;
					
					if($datos['valor_cuota_moderadora']>0 OR $datos['valor_cuota_paciente']>0)
					{
						$sw_cuota_moderadora = 1;
						$numeracion = $this->ObtenerNumeracion($caja['documento_recibo_caja'],$caja['empresa_id']);
						if(empty($numeracion))
							return false;
						
						$tipo_factura = 0;
						if($datos['sw_tipo_plan'] == "2") $tipo_factura = 2;

						if(!$this->IngresarPagos($datos,$rqst,$numeracion,$tercero,$caja['empresa_id'],$caja['centro_utilidad'],$caja['caja_id'],$sw_cuota_moderadora))
						return false;
						
						if(!$this->CrearFacturas($datos,$numeracion,$tercero,"0",$caja['prefijo_fac_contado'],$tipo_factura,$caja['empresa_id']))
							return false;
						/*if(!$this->CrearRecibos($datos,$numeracion,$tercero,"0",$caja['prefijo_fac_contado'],$tipo_factura,$caja['empresa_id'],$caja['centro_utilidad'],$rqst,$caja['caja_id'],$caja['cuenta_tipo_id']))
							return false;*/
						
					}
					if($datos['valor_nocubierto']>0)
					{
						$sw_cuota_moderadora = 2;
						$numeracion = $this->ObtenerNumeracion($caja['prefijo_fac_contado'],$caja['empresa_id']);
						if(empty($numeracion))
							return false;
						
						$tipo_factura = 0;
						if($datos['sw_tipo_plan'] == "2") $tipo_factura = 2;

						if(!$this->IngresarPagos($datos,$rqst,$numeracion,$tercero,$caja['empresa_id'],$caja['centro_utilidad'],$caja['caja_id'],$sw_cuota_moderadora))
						return false;
						
						if(!$this->CrearFacturas($datos,$numeracion,$tercero,"0",$caja['prefijo_fac_contado'],$tipo_factura,$caja['empresa_id']))
							return false;
						/*if(!$this->CrearRecibos($datos,$numeracion,$tercero,"0",$caja['prefijo_fac_contado'],$tipo_factura,$caja['empresa_id'],$caja['centro_utilidad'],$rqst,$caja['caja_id'],$caja['cuenta_tipo_id']))
							return false;*/						
					}
					
					/*$numeracion = $this->ObtenerNumeracion($caja['prefijo_fac_contado'],$caja['empresa_id']);
					if(empty($numeracion))
						return false;
					
					$tipo_factura = 0;
					if($datos['sw_tipo_plan'] == "2") $tipo_factura = 2;

					if(!$this->CrearFacturas($datos,$numeracion,$tercero,"0",$caja['prefijo_fac_contado'],$tipo_factura,$caja['empresa_id']))
						return false;
					if(!$this->CrearRecibos($datos,$numeracion,$tercero,"0",$caja['prefijo_fac_contado'],$tipo_factura,$caja['empresa_id'],$caja['centro_utilidad'],$rqst,$caja['caja_id'],$caja['cuenta_tipo_id']))
						return false;
					
					if(!$this->IngresarPagos($datos,$rqst,$numeracion,$tercero,$caja['empresa_id'],$caja['centro_utilidad'],$caja['caja_id']))
						return false;*/
          
          if(!$this->ActualizarEstadoCuenta($datos['numerodecuenta'],$estado,"transaccion"))
					return false;
						
					$retorno['contado'] = $numeracion;
				}
				
				if($datos['valor_total_empresa'] > 0)
				{
					if($datos['sw_facturacion_agrupada'] == 0 && ($datos['sw_tipo_plan'] == "0" || $datos['sw_tipo_plan'] == "1"))
					{
						$estado = '0';
            $tercero = $this->ObtenerTerceroPlan($datos['plan_id']);

						if(!$numeracion = $this->ObtenerNumeracion($caja['prefijo_fac_credito'],$caja['empresa_id']))
							return false;
							
						if(!$this->CrearFacturas($datos,$numeracion,$tercero,"1",$caja['prefijo_fac_credito'],"1",$caja['empresa_id']))
							return false;
              
            if(!$this->ActualizarEstadoCuenta($datos['numerodecuenta'],$estado,"transaccion"))
            return false;  
						
						$retorno['credito'] = $numeracion;
					}
					else if($datos['sw_facturacion_agrupada'] == 1 && $datos['sw_tipo_plan'] == "3")
					{
						$estado = '0';
            if(!$this->ActualizarEstadoCuenta($datos['numerodecuenta'],$estado,"transaccion"))
            return false;
					}
				}
				
				/*if(!$this->ActualizarEstadoCuenta($datos['numerodecuenta'],$estado,"transaccion"))
					return false;*/
			}
			
			if(!$this->ActualizarOrdenes($datos)) return false;
			
			if(!$this->EliminarTemporales($datos['numerodecuenta'])) return false;
			
			if(!is_null($caja['departamento']))
			{
				if(!$this->ActualizarCumpliminetos($datos,$caja['departamento']))
					return false;
			}
			
			$this->dbconn->CommitTrans();
			
			
			return $retorno;
		}
		
		/**********************************************************************************
		*FUNCION ORIGINAL DE PAGOS DE CUENTAS DE CONSULTA EXTERNA
		***********************************************************************************/
/*		function IngresoPagosCuenta($datos,$rqst,$caja)
		{
			$this->ConexionTransaccion();
			$tercero = array();
			$numeracion = array();
			$retorno = array();
						
			if($datos['valor_total_paciente'] == 0 && $datos['valor_total_empresa'] == 0)
			{
				if(!$this->ActualizarEstadoCuenta($datos['numerodecuenta'],"0","transaccion"))
					return false;
			}
			else
			{
				if($datos['valor_total_paciente'] > 0 || $rqst['h_total'])
				{
					IncludeLib('funciones_facturacion');
					if(!$tercero = ResponsableFacturaPaciente($datos['tipo_id_paciente'],$datos['paciente_id'],$caja['empresa_id'],&$this->dbconn))
						return false;
					
					$numeracion = $this->ObtenerNumeracion($caja['prefijo_fac_contado'],$caja['empresa_id']);
					if(empty($numeracion))
						return false;
					
					$tipo_factura = 0;
					if($datos['sw_tipo_plan'] == "2") $tipo_factura = 2;

					if(!$this->CrearFacturas($datos,$numeracion,$tercero,"0",$caja['prefijo_fac_contado'],$tipo_factura,$caja['empresa_id']))
						return false;
					
					if(!$this->IngresarPagos($datos,$rqst,$numeracion,$tercero,$caja['empresa_id'],$caja['centro_utilidad'],$caja['caja_id']))
						return false;
					
					$retorno['contado'] = $numeracion;
				}
				
				if($datos['valor_total_empresa'] > 0 && $datos['sw_facturacion_agrupada'] == 0 && ($datos['sw_tipo_plan'] == "0" || $datos['sw_tipo_plan'] == "1"))
				{
					$tercero = $this->ObtenerTerceroPlan($datos['plan_id']);

					if(!$numeracion = $this->ObtenerNumeracion($caja['prefijo_fac_credito'],$caja['empresa_id']))
						return false;
						
					if(!$this->CrearFacturas($datos,$numeracion,$tercero,"1",$caja['prefijo_fac_credito'],"1",$caja['empresa_id']))
						return false;
					
					$retorno['credito'] = $numeracion;
				}
				
				if(!$this->ActualizarEstadoCuenta($datos['numerodecuenta'],"0","transaccion"))
					return false;
			}
			
			if(!$this->ActualizarOrdenes($datos)) return false;
			
			if(!$this->EliminarTemporales($datos['numerodecuenta'])) return false;
			
			if(!is_null($caja['departamento']))
			{
				if(!$this->ActualizarCumpliminetos($datos,$caja['departamento']))
					return false;
			}
			
			$this->dbconn->CommitTrans();
			
			
			return $retorno;
		}*/
		/**********************************************************************************
		*
		***********************************************************************************/
		function CrearFacturas($datos,$numeracion,$tercero,$clase_factura,$documento,$tipo_factura,$empresa)
		{
			$sql  = "INSERT INTO fac_facturas( ";
			$sql .= "			empresa_id, ";
			$sql .= "			prefijo, ";
			$sql .= "			factura_fiscal, ";
			$sql .= "			estado, ";
			$sql .= "			usuario_id, ";
			$sql .= "			fecha_registro, ";
			$sql .= "			plan_id, ";
			$sql .= "			tipo_id_tercero, ";
			$sql .= "			tercero_id, ";
			$sql .= "			sw_clase_factura, ";
			$sql .= "			documento_id, ";
			$sql .= "			tipo_factura ) ";
			$sql .= "VALUES(";
			$sql .= "			'".$empresa."',";
			$sql .= "			'".$numeracion['prefijo']."',";
			$sql .= "			'".$numeracion['numeracion']."',";
			$sql .= "			'0',";
			$sql .= "			".UserGetUID().",";
			$sql .= "			NOW(),";
			$sql .= "			'".$datos['plan_id']."',";
			$sql .= "			'".$tercero['tipo_id_tercero']."',";
			$sql .= "			'".$tercero['tercero_id']."',";
			$sql .= "			'".$clase_factura."',";
			$sql .= "			".$documento.",";
			$sql .= "			'".$tipo_factura."'); ";
			
			if(!$rst = $this->ConexionTransaccion($sql,"Insert Fac_Facturas"))
				return false;	
			
			$sql  = "INSERT	INTO fac_facturas_cuentas(";
			$sql .= "		empresa_id, ";
			$sql .= "		prefijo, ";
			$sql .= "		factura_fiscal, ";
			$sql .= "		numerodecuenta, ";
			$sql .= "		sw_tipo )";
			$sql .= "VALUES(";
			$sql .= "			'".$empresa."',";
			$sql .= "			'".$numeracion['prefijo']."',";
			$sql .= "			'".$numeracion['numeracion']."',";
			$sql .= "			".$datos['numerodecuenta'].",";
			$sql .= "			'".$tipo_factura."'); ";

			if(!$rst = $this->ConexionTransaccion($sql,"Insert Fac_Facturas_Cuentas"))
				return false;
			
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function CrearRecibos($datos,$numeracion,$tercero,$clase_factura,$documento,$tipo_factura,$empresa,$c_util,$rqst,$caja_id,$cuenta_tipo_id)
		{
			//CONSULTAR PAGOS
			$cheque = 0;
			$sql  = "SELECT COALESCE(SUM(total),0) "; 
			$sql .= "FROM		tmp_cheques_mov ";
			$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']." ";

			if(!$rst = $this->ConexionTransaccion($sql,"Select Cheque")) 
				return false;
				
			if(!$rst->EOF)
			{
				$cheque = $rst->fields[0];
				$rst->MoveNext();
			}
			
			$tarjeta_credito = 0;
			$sql  = "SELECT COALESCE(SUM(total),0) ";
			$sql .= "FROM		tmp_tarjetas_mov_credito ";
			$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']." ";
			
			if(!$rst = $this->ConexionTransaccion($sql,"Select Tarjeta Credito")) 
				return false;
				
			if(!$rst->EOF)
			{
				$tarjeta_credito = $rst->fields[0];
				$rst->MoveNext();
			}
			
			$tarjeta_debito = 0;
			$sql  = "SELECT COALESCE(SUM(total),0) ";
			$sql .= "FROM		tmp_tarjetas_mov_debito ";
			$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']." ";
			
			if(!$rst = $this->ConexionTransaccion($sql,"Select Tarjeta Debito")) 
				return false;
				
			if(!$rst->EOF)
			{
				$tarjeta_debito = $rst->fields[0];
				$rst->MoveNext();
			}
			//FIN CONSULTAR PAGOS

			$sql="INSERT INTO recibos_caja(
												empresa_id,
												recibo_caja,
												centro_utilidad,
												prefijo,
												fecha_ingcaja,
												total_abono,
												total_efectivo,
												total_cheques,
												total_tarjetas,
												tipo_id_tercero,
												tercero_id,
												estado,
												fecha_registro,
												usuario_id,
												caja_id,
												total_bonos,
												documento_id,
												cuenta_tipo_id
												)VALUES(
												'".$empresa."',
												'".$numeracion['numeracion']."',
												'".$c_util."',
												'".$numeracion['prefijo']."',
												'now()',
												".$rqst['h_total'].",
												".$rqst['h_efectivo'].",
												".$cheque.",
												".($tarjeta_credito+$tarjeta_debito).",
												'".$tercero['tipo_id_tercero']."',
												'".$tercero['tercero_id']."',
												'0',
												'now()',
												".UserGetUID().",
												'$caja_id',
												".$rqst['h_bono'].",
												".$documento.",
												'".$cuenta_tipo_id."'
												);";
			if(!$rst = $this->ConexionTransaccion($sql,"Insert recibos_caja"))
				return false;

					$sql="INSERT INTO rc_detalle_hosp
															(
															empresa_id,
															centro_utilidad,
															recibo_caja,
															prefijo,
															numerodecuenta
															)VALUES(
															'".$empresa."',
															'".$c_util."',
															'".$numeracion['numeracion']."',
															'".$numeracion['prefijo']."',
															".$datos['numerodecuenta'].");";
					if(!$rst = $this->ConexionTransaccion($sql,"Insert rc_detalle_hosp"))
						return false;
			//*********************************************************
			if($cheque > 0)
			{
				$sql  = "INSERT INTO chequesf_mov( ";
				$sql .= "		cheque_mov_id,";
				$sql .= "		empresa_id,";
				$sql .= "		centro_utilidad,";
				$sql .= "		factura_fiscal,";
				$sql .= "		prefijo,";
				$sql .= "		banco,";
				$sql .= "		cta_cte,";
				$sql .= "		cheque,";
				$sql .= "		girador,";
				$sql .= "		fecha_cheque,";
				$sql .= "		total,";
				$sql .= "		fecha,";
				$sql .= "		estado,";
				$sql .= "		usuario_id,";
				$sql .= "		fecha_registro,";
				$sql .= "		sw_postfechado ) ";
				$sql .= "SELECT	cheque_mov_id, ";
				$sql .= "				empresa_id, ";
				$sql .= "				centro_utilidad, ";
				$sql .= "				".$numeracion['numeracion']." AS recibo_caja, ";
				$sql .= "				'".$numeracion['prefijo']."' AS prefijo,";
				$sql .= "				banco,";
				$sql .= "				cta_cte,";
				$sql .= "				cheque,";
				$sql .= "				girador,";
				$sql .= "				fecha_cheque,";
				$sql .= "				total,";
				$sql .= "				fecha,";
				$sql .= "				'0' AS estado,";
				$sql .= "				usuario_id,";
				$sql .= "				fecha_registro,";
				$sql .= "				CASE WHEN fecha_cheque > NOW()::date THEN '1' ELSE '0' END AS sw_postfechado "; 
				$sql .= "FROM		tmp_cheques_mov ";
				$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']."; ";
				
				if(!$rst = $this->ConexionTransaccion($sql,"Insert chequesf_mov")) 
					return false;
				
				$sql  = "INSERT INTO  confirmacion_chef( ";
				$sql .= "				consecutivo,";
				$sql .= "				cheque_mov_id,";
				$sql .= "				entidad_confirma,";
				$sql .= "				funcionario_confirma,";
				$sql .= "				numero_confirmacion,";
				$sql .= "				fecha,";
				$sql .= "				usuario_id ";
				$sql .= "		) ";
				$sql .= "SELECT	consecutivo,";
				$sql .= "				cheque_mov_id,";
				$sql .= "				entidad_confirma,";
				$sql .= "				funcionario_confirma,";
				$sql .= "				numero_confirmacion,";
				$sql .= "				fecha,";
				$sql .= "				usuario_id ";
				$sql .= "FROM 	tmp_confirmacion_che ";
				$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']."; ";
				
				if(!$rst = $this->ConexionTransaccion($sql,"Insert confirmacion_chef")) 
					return false;
			}

			if($tarjeta_credito > 0)
			{
				$sql  = "INSERT INTO tarjetasf_mov_credito( ";
				$sql .= "				tarjeta_mov_id,";
				$sql .= "				tarjeta,";
				$sql .= "				empresa_id,";
				$sql .= "				centro_utilidad,";
				$sql .= "				factura_fiscal,";
				$sql .= "				prefijo,";
				$sql .= "				fecha,";
				$sql .= "				autorizacion,";
				$sql .= "				socio,";
				$sql .= "				fecha_expira,";
				$sql .= "				autorizado_por,";
				$sql .= "				total,";
				$sql .= "				usuario_id,";
				$sql .= "				fecha_registro,";
				$sql .= "				tarjeta_numero ) ";
				$sql .= "SELECT	tarjeta_mov_id,";
				$sql .= "				tarjeta,";
				$sql .= "				empresa_id,";
				$sql .= "				centro_utilidad,";
				$sql .= "				".$numeracion['numeracion']." AS recibo_caja, ";
				$sql .= "				'".$numeracion['prefijo']."' AS prefijo,";
				$sql .= "				fecha,";
				$sql .= "				autorizacion,";
				$sql .= "				socio,";
				$sql .= "				fecha_expira,";
				$sql .= "				autorizado_por,";
				$sql .= "				total,";
				$sql .= "				usuario_id,";
				$sql .= "				fecha_registro,";
				$sql .= "				tarjeta_numero ";
				$sql .= "FROM		tmp_tarjetas_mov_credito ";
				$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']."; ";
				
				if(!$rst = $this->ConexionTransaccion($sql,"Insert tarjetasf_mov_credito")) 
					return false;
				
				$sql  = "INSERT INTO confirmacion_tarf(";
				$sql .= "				consecutivo,";
				$sql .= "				tarjeta_mov_id,";
				$sql .= "				entidad_confirma,";
				$sql .= "				funcionario_confirma,";
				$sql .= "				numero_confirmacion,";
				$sql .= "				fecha,";
				$sql .= "				usuario_id";
				$sql .= "		) ";
				$sql .= "SELECT consecutivo, ";
				$sql .= "				tarjeta_mov_id, ";
				$sql .= "				entidad_confirma, ";
				$sql .= "				funcionario_confirma, ";
				$sql .= "				numero_confirmacion, ";
				$sql .= "				fecha, ";
				$sql .= "				usuario_id ";
				$sql .= "FROM		tmp_confirmacion_tar ";
				$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']."; ";
				
				if(!$rst = $this->ConexionTransaccion($sql,"Insert confirmacion_tarf")) 
					return false;
			}

			if($tarjeta_debito > 0)
			{
				$sql  = "INSERT INTO tarjetasf_mov_debito( ";
				$sql .= "			empresa_id, ";
				$sql .= "			centro_utilidad, ";
				$sql .= "			factura_fiscal, ";
				$sql .= "			prefijo, ";
				$sql .= "			autorizacion, ";
				$sql .= "			tarjeta, ";
				$sql .= "			total, ";
				$sql .= "			tarjeta_numero ";
				$sql .= "			) ";
				$sql .= "SELECT	empresa_id, ";
				$sql .= "				centro_utilidad, ";
				$sql .= "				".$numeracion['numeracion']." AS recibo_caja, ";
				$sql .= "				'".$numeracion['prefijo']."' AS prefijo,";
				$sql .= "				autorizacion, ";
				$sql .= "				tarjeta, ";
				$sql .= "				total, ";
				$sql .= "				tarjeta_numero ";
				$sql .= "FROM 	tmp_tarjetas_mov_debito ";
				$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']."; ";

				if(!$rst = $this->ConexionTransaccion($sql,"Insert confirmacion_tarf")) 
					return false;
			}
			//*********************************************************
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerNumeracion($documento,$empresa)
		{
			$sql = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE ";
			
			if(!$rst = $this->ConexionTransaccion($sql,"Bloqueo"))
				return false;			
			
			$sql  = "SELECT	COALESCE(numeracion,0)+1 AS numeracion,";
			$sql .= "				prefijo ";
			$sql .= "FROM		documentos ";
			$sql .= "WHERE 	documento_id = ".$documento."  ";
			$sql .= "AND		empresa_id = '".$empresa."' ";
			
			if(!$rst = $this->ConexionTransaccion($sql,"Bloqueo"))
				return false;	
			
			$nmr = array();	
			if(!$rst->EOF)
			{
				$nmr = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
		
			$sql  = "UPDATE	documentos ";
			$sql .= "SET		numeracion = numeracion + 1 ";
			$sql .= "WHERE 	documento_id = ".$documento."  ";
			$sql .= "AND		empresa_id = '".$empresa."' ";
			
			if(!$rst = $this->ConexionTransaccion($sql,"Bloqueo"))
				return false;	
			
			return $nmr;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ActualizarOrdenes($datos)
		{
			$sql  = "SELECT OM.numero_orden_id ,";
			$sql .= "				OS.evento_soat,";
			$sql .= " 			OS.sw_estado ";
			$sql .= "FROM 	os_ordenes_servicios OS,";
			$sql .= " 			os_maestro OM,";
			$sql .= " 			os_maestro_cargos OC,";
			$sql .= " 			cuentas_detalle CD ";
			$sql .= "WHERE 	OS.paciente_id = '".$datos['paciente_id']."' ";
			$sql .= "AND 		OS.tipo_id_paciente = '".$datos['tipo_id_paciente']."' ";
			$sql .= "AND 		OM.orden_servicio_id = OS.orden_servicio_id ";
			$sql .= "AND		OM.numerodecuenta IS NULL ";
			$sql .= "AND 		OC.numero_orden_id = OM.numero_orden_id ";
			$sql .= "AND 		CD.transaccion = OC.transaccion ";
			$sql .= "AND 		CD.numerodecuenta = ".$datos['numerodecuenta']." ";
			$sql .= "AND 		OS.sw_estado IN ('0','1','5') ";
			$sql .= "AND		OC.tarifario_id <> 'SYS' ";
			$sql .= "AND		OC.cargo <> 'IMD' ";

			if(!$rst = $this->ConexionTransaccion($sql,"Select Os Maestro")) 
				return false;
				
			$os = array();
			$sql = "";
			$evento = null;
			
			while(!$rst->EOF)
			{
				$os = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				
				$estado = "2";
				if($os['sw_estado'] == "0") $estado = "6";
				if($os['evento_soat']) $evento = $os['evento_soat'];
				
				$sql .= "UPDATE	os_maestro ";
				$sql .= "SET		sw_estado = '".$estado."',";
				$sql .= "				numerodecuenta = ".$datos['numerodecuenta']." ";
				$sql .= "WHERE	numero_orden_id = ".$os['numero_orden_id']."; ";
			}
			if($sql != "")
			{
				if(!$rst = $this->ConexionTransaccion($sql,"Actualizar Os Maestro")) 
					return false;
			}
			if(!is_null($evento))
			{
				$sql  = "INSERT INTO ingresos_soat ";
				$sql .= "	(	ingreso,";
				$sql .= "		evento) ";
				$sql .= "VALUES (";
				$sql .= "		".$datos['ingreso'].",";
				$sql .= "		".$evento." )";

				if(!$rst = $this->ConexionTransaccion($sql,"Ingreso Soat"))
					return false;
			}
			return true;
		}
		/**********************************************************************************
		*
		* @return boolean
		*************                                                   ***********************************************************************/
		function IngresarPagos($datos,$rqst,$numeracion,$tercero,$empresa,$cutil,$caja_id)
		{
			$cheque = 0;
			$sql  = "SELECT COALESCE(SUM(total),0) "; 
			$sql .= "FROM		tmp_cheques_mov ";
			$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']." ";

			if(!$rst = $this->ConexionTransaccion($sql,"Select Cheque")) 
				return false;
				
			if(!$rst->EOF)
			{
				$cheque = $rst->fields[0];
				$rst->MoveNext();
			}
			
			$tarjeta_credito = 0;
			$sql  = "SELECT COALESCE(SUM(total),0) ";
			$sql .= "FROM		tmp_tarjetas_mov_credito ";
			$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']." ";
			
			if(!$rst = $this->ConexionTransaccion($sql,"Select Tarjeta Credito")) 
				return false;
				
			if(!$rst->EOF)
			{
				$tarjeta_credito = $rst->fields[0];
				$rst->MoveNext();
			}
			
			$tarjeta_debito = 0;
			$sql  = "SELECT COALESCE(SUM(total),0) ";
			$sql .= "FROM		tmp_tarjetas_mov_debito ";
			$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']." ";
			
			if(!$rst = $this->ConexionTransaccion($sql,"Select Tarjeta Debito")) 
				return false;
				
			if(!$rst->EOF)
			{
				$tarjeta_debito = $rst->fields[0];
				$rst->MoveNext();
			}
			
			$sql  = "INSERT into fac_facturas_contado( ";
			$sql .= "		empresa_id, ";
			$sql .= "		centro_utilidad, ";
			$sql .= "		factura_fiscal, ";
			$sql .= "		prefijo, ";
			$sql .= "		total_abono, ";
			$sql .= "		total_efectivo, ";
			$sql .= "		total_cheques, ";
			$sql .= "		total_tarjetas, ";
			$sql .= "		tipo_id_tercero, ";
			$sql .= "		tercero_id,  ";
			$sql .= "		estado, ";
			$sql .= "		fecha_registro, ";
			$sql .= "		usuario_id, ";
			$sql .= "		caja_id,  ";
			$sql .= "		total_bonos  ";
			$sql .= "		) ";
			$sql .= "VALUES(  ";
			$sql .= "		'".$empresa."',";
			$sql .= "		'".$cutil."', ";
			$sql .= "		".$numeracion['numeracion'].",";
			$sql .= "		'".$numeracion['prefijo']."',";
			$sql .= "		".$rqst['h_total'].", ";
			$sql .= "		".$rqst['h_efectivo'].",";
			$sql .= "		".$cheque.",";
			$sql .= "		".($tarjeta_credito+$tarjeta_debito).", ";
			$sql .= "		'".$tercero['tipo_id_tercero']."',";
			$sql .= "		'".$tercero['tercero_id']."',";
			$sql .= "		'0',";
			$sql .= "		NOW(),";
			$sql .= "		".UserGetUID().",";
			$sql .= "		'".$caja_id."',";
			$sql .= "		".$rqst['h_bono']."); ";
			
			if(!$rst = $this->ConexionTransaccion($sql,"Insert Fac_Facturas_Contado")) 
				return false;

			if($cheque > 0)
			{
				$sql  = "INSERT INTO chequesf_mov( ";
				$sql .= "		cheque_mov_id,";
				$sql .= "		empresa_id,";
				$sql .= "		centro_utilidad,";
				$sql .= "		factura_fiscal,";
				$sql .= "		prefijo,";
				$sql .= "		banco,";
				$sql .= "		cta_cte,";
				$sql .= "		cheque,";
				$sql .= "		girador,";
				$sql .= "		fecha_cheque,";
				$sql .= "		total,";
				$sql .= "		fecha,";
				$sql .= "		estado,";
				$sql .= "		usuario_id,";
				$sql .= "		fecha_registro,";
				$sql .= "		sw_postfechado ) ";
				$sql .= "SELECT	cheque_mov_id, ";
				$sql .= "				empresa_id, ";
				$sql .= "				centro_utilidad, ";
				$sql .= "				".$numeracion['numeracion']." AS recibo_caja, ";
				$sql .= "				'".$numeracion['prefijo']."' AS prefijo,";
				$sql .= "				banco,";
				$sql .= "				cta_cte,";
				$sql .= "				cheque,";
				$sql .= "				girador,";
				$sql .= "				fecha_cheque,";
				$sql .= "				total,";
				$sql .= "				fecha,";
				$sql .= "				'0' AS estado,";
				$sql .= "				usuario_id,";
				$sql .= "				fecha_registro,";
				$sql .= "				CASE WHEN fecha_cheque > NOW()::date THEN '1' ELSE '0' END AS sw_postfechado "; 
				$sql .= "FROM		tmp_cheques_mov ";
				$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']."; ";
				
				if(!$rst = $this->ConexionTransaccion($sql,"Insert chequesf_mov")) 
					return false;
				
				$sql  = "INSERT INTO  confirmacion_chef( ";
				$sql .= "				consecutivo,";
				$sql .= "				cheque_mov_id,";
				$sql .= "				entidad_confirma,";
				$sql .= "				funcionario_confirma,";
				$sql .= "				numero_confirmacion,";
				$sql .= "				fecha,";
				$sql .= "				usuario_id ";
				$sql .= "		) ";
				$sql .= "SELECT	consecutivo,";
				$sql .= "				cheque_mov_id,";
				$sql .= "				entidad_confirma,";
				$sql .= "				funcionario_confirma,";
				$sql .= "				numero_confirmacion,";
				$sql .= "				fecha,";
				$sql .= "				usuario_id ";
				$sql .= "FROM 	tmp_confirmacion_che ";
				$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']."; ";
				
				if(!$rst = $this->ConexionTransaccion($sql,"Insert confirmacion_chef")) 
					return false;
			}

			if($tarjeta_credito > 0)
			{
				$sql  = "INSERT INTO tarjetasf_mov_credito( ";
				$sql .= "				tarjeta_mov_id,";
				$sql .= "				tarjeta,";
				$sql .= "				empresa_id,";
				$sql .= "				centro_utilidad,";
				$sql .= "				factura_fiscal,";
				$sql .= "				prefijo,";
				$sql .= "				fecha,";
				$sql .= "				autorizacion,";
				$sql .= "				socio,";
				$sql .= "				fecha_expira,";
				$sql .= "				autorizado_por,";
				$sql .= "				total,";
				$sql .= "				usuario_id,";
				$sql .= "				fecha_registro,";
				$sql .= "				tarjeta_numero ) ";
				$sql .= "SELECT	tarjeta_mov_id,";
				$sql .= "				tarjeta,";
				$sql .= "				empresa_id,";
				$sql .= "				centro_utilidad,";
				$sql .= "				".$numeracion['numeracion']." AS recibo_caja, ";
				$sql .= "				'".$numeracion['prefijo']."' AS prefijo,";
				$sql .= "				fecha,";
				$sql .= "				autorizacion,";
				$sql .= "				socio,";
				$sql .= "				fecha_expira,";
				$sql .= "				autorizado_por,";
				$sql .= "				total,";
				$sql .= "				usuario_id,";
				$sql .= "				fecha_registro,";
				$sql .= "				tarjeta_numero ";
				$sql .= "FROM		tmp_tarjetas_mov_credito ";
				$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']."; ";
				
				if(!$rst = $this->ConexionTransaccion($sql,"Insert tarjetasf_mov_credito")) 
					return false;
				
				$sql  = "INSERT INTO confirmacion_tarf(";
				$sql .= "				consecutivo,";
				$sql .= "				tarjeta_mov_id,";
				$sql .= "				entidad_confirma,";
				$sql .= "				funcionario_confirma,";
				$sql .= "				numero_confirmacion,";
				$sql .= "				fecha,";
				$sql .= "				usuario_id";
				$sql .= "		) ";
				$sql .= "SELECT consecutivo, ";
				$sql .= "				tarjeta_mov_id, ";
				$sql .= "				entidad_confirma, ";
				$sql .= "				funcionario_confirma, ";
				$sql .= "				numero_confirmacion, ";
				$sql .= "				fecha, ";
				$sql .= "				usuario_id ";
				$sql .= "FROM		tmp_confirmacion_tar ";
				$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']."; ";
				
				if(!$rst = $this->ConexionTransaccion($sql,"Insert confirmacion_tarf")) 
					return false;
			}

			if($tarjeta_debito > 0)
			{
				$sql  = "INSERT INTO tarjetasf_mov_debito( ";
				$sql .= "			empresa_id, ";
				$sql .= "			centro_utilidad, ";
				$sql .= "			factura_fiscal, ";
				$sql .= "			prefijo, ";
				$sql .= "			autorizacion, ";
				$sql .= "			tarjeta, ";
				$sql .= "			total, ";
				$sql .= "			tarjeta_numero ";
				$sql .= "			) ";
				$sql .= "SELECT	empresa_id, ";
				$sql .= "				centro_utilidad, ";
				$sql .= "				".$numeracion['numeracion']." AS recibo_caja, ";
				$sql .= "				'".$numeracion['prefijo']."' AS prefijo,";
				$sql .= "				autorizacion, ";
				$sql .= "				tarjeta, ";
				$sql .= "				total, ";
				$sql .= "				tarjeta_numero ";
				$sql .= "FROM 	tmp_tarjetas_mov_debito ";
				$sql .= "WHERE	numerodecuenta = ".$datos['numerodecuenta']."; ";

				if(!$rst = $this->ConexionTransaccion($sql,"Insert confirmacion_tarf")) 
					return false;
			}
			
			return true;
		}	
		/**********************************************************************************
		*
		* @return boolean
		************************************************************************************/
		function ObtenerTerceroPlan($plan_id)
		{
			$sql  = "SELECT	PL.tipo_tercero_id AS tipo_id_tercero,";
			$sql .= "				PL.tercero_id ";
			$sql .= "FROM		planes PL,";
			$sql .= "				terceros TE ";
			$sql .= "WHERE	PL.plan_id = ".$plan_id." ";
			$sql .= "AND		PL.tipo_tercero_id = TE.tipo_id_tercero ";
			$sql .= "AND		PL.tercero_id = TE.tercero_id ";
		
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
		/**********************************************************************************
		*
		* @return boolean
		***********************************************************************************/
		function ActualizarCumpliminetos($datos,$departamento)
		{
			$sql  = "SELECT DC.sw_liquidar_honario,";
			$sql .= "				DC.sw_cumplido_automatico,";
			$sql .= "				DC.sw_tomado_automatico, ";
			$sql .= "				OM.numero_orden_id, ";
			$sql .= "				OM.orden_servicio_id, ";
			$sql .= "				OM.cantidad ";
			$sql .= "FROM 	os_ordenes_servicios OS,";
			$sql .= "				os_maestro OM,";
			$sql .= "				os_maestro_cargos OC,";
			$sql .= "				departamentos_cargos DC ";
			$sql .= "WHERE 	OS.paciente_id = '".$datos['paciente_id']."' ";
			$sql .= "AND 		OS.tipo_id_paciente = '".$datos['tipo_id_paciente']."' ";
			$sql .= "AND		OM.numerodecuenta = ".$datos['numerodecuenta']." ";
			$sql .= "AND 		OM.orden_servicio_id = OS.orden_servicio_id ";
			$sql .= "AND 		OC.numero_orden_id = OM.numero_orden_id ";
			$sql .= "AND 		DC.cargo = OM.cargo_cups ";
			$sql .= "AND		DC.departamento = '".$departamento."' ";
			$sql .= "AND		OC.tarifario_id <> 'SYS' ";
			$sql .= "AND		OC.cargo <> 'IMD' ";
			
			//if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			if(!$rst = $this->ConexionTransaccion($sql,"Actualizar Cumplimientos")) 
				return false;
			
			$cmpl = array();
			while(!$rst->EOF)
			{
				$cmpl[$rst->fields[0]][$rst->fields[1]][] = $rst->GetRowAssoc($ToUpper = false);				
				$rst->MoveNext();
			}
			$rst->Close();
			
			if(!empty($cmpl['0']['1']))
			{
				$sql = "SELECT	secuencia_os_cumplimiento_restringido('".$departamento."') ";
				
				//if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				if(!$rst = $this->ConexionTransaccion($sql,"Actualiza Estado Cuenta3")) 
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
				$sql .= "			'".$datos['tipo_id_paciente']."', ";
				$sql .= "			'".$datos['paciente_id']."' ";
				$sql .= "		); ";
	
				foreach($cmpl['0']['1'] as $key => $cumplimiento)
				{
					/*$sql .= "UPDATE	os_maestro ";
					$sql .= "SET		sw_estado = '3', ";
					$sql .= "				cantidad_pendiente = 0 ";
					$sql .= "WHERE	numero_orden_id = ".$cumplimiento['numero_orden_id']." ";
					$sql .= "AND		orden_servicio_id = ".$cumplimiento['orden_servicio_id']." ";
					$sql .= "AND 		cantidad_pendiente > 0 ";
					$sql .= "AND 		sw_estado = '2'; ";*/
					
					$sql .= "UPDATE	os_maestro ";
					$sql .= "SET		sw_estado = '3'";
					$sql .= "WHERE	numero_orden_id = ".$cumplimiento['numero_orden_id']." ";
					$sql .= "AND		orden_servicio_id = ".$cumplimiento['orden_servicio_id']." ";
					$sql .= "AND 		sw_estado = '2'; ";
					
					$sql .= "UPDATE	os_maestro_cargos ";
					$sql .= "SET		cantidad_pendiente = 0 ";
					$sql .= "WHERE	numero_orden_id = ".$cumplimiento['numero_orden_id']." ";
					$sql .= "AND 		cantidad_pendiente > 0; ";
					
					$estado = "'0'";
					if($cumplimiento['sw_tomado_automatico'] == '1')
						$estado = "'1'";
					
					$sql .= "INSERT INTO os_cumplimientos_detalle";
					$sql .= "		(	numero_orden_id,";
					$sql .= "			numero_cumplimiento,";
					$sql .= "			fecha_cumplimiento,";
					$sql .= "			departamento,";
					$sql .= "			sw_estado, ";
					$sql .= "			cantidad_cumplimiento) ";
					$sql .= "VALUES(";
					$sql .= "			".$cumplimiento['numero_orden_id'].",";
					$sql .= "			".$n_cumplimiento.",";
					$sql .= "			NOW(),";
					$sql .= "			'".$departamento."', ";
					$sql .= "			".$estado.", ";
					$sql .= "			".$cumplimiento['cantidad']." ); ";
				}
				
				//if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				if(!$rst = $this->ConexionTransaccion($sql,"Actualiza Estado Cuenta2")) 
					return false;
			}
			return true;
		}
		/**********************************************************************************
		*
		* @return boolean
		***********************************************************************************/
		function ObtenerDatosFacturaCliente($numerodecuenta,$empresa,$prefijo,$factura)
		{
			$sql  = "SELECT	A.abonos,";
			$sql .= "				A.numerodecuenta, ";
			$sql .= "				A.ingreso,";
			$sql .= "				A.plan_id,";
			$sql .= "				A.empresa_id,";
			$sql .= "				B.plan_descripcion,";
			$sql .= "				C.nombre_tercero,";
			$sql .= "				C.tipo_id_tercero,";
			$sql .= "				C.tercero_id,";
			$sql .= "				D.tipo_id_paciente,";
			$sql .= "				D.paciente_id,";
			$sql .= "				D.fecha_cierre,";
			$sql .= "				E.primer_apellido||' '||E.segundo_apellido||' '||E.primer_nombre||' '||E.segundo_nombre as nombre,";
			$sql .= "				E.residencia_telefono,";
			$sql .= "				E.residencia_direccion,";
			$sql .= "				A.prefijo,";
			$sql .= "				A.factura_fiscal,";
			$sql .= "				D.departamento_actual AS dpto,";
			$sql .= "				H.descripcion,";
			$sql .= "				I.razon_social,";
			$sql .= "				I.direccion,";
			$sql .= "				I.telefonos,";
			$sql .= "				I.tipo_id_tercero AS tipoid,";
			$sql .= "				I.id,";
			$sql .= "				J.departamento,";
			$sql .= "				K.municipio,";
			$sql .= "				D.fecha_registro,";
			$sql .= "				A.sw_tipo,";
			$sql .= "				A.valor_cuota_paciente,";
			$sql .= "				A.valor_nocubierto,";
			$sql .= "				A.valor_cubierto,";
			$sql .= "				A.valor_descuento_empresa,";
			$sql .= "				A.valor_descuento_paciente,";
			$sql .= "				A.total_cuenta,";
			$sql .= "				A.abono_efectivo,";
			$sql .= "				A.abono_cheque,";
			$sql .= "				A.abono_tarjetas,";
			$sql .= "				A.abono_chequespf,";
			$sql .= "				A.abono_letras,";
			$sql .= "				A.valor_total_paciente,";
			$sql .= "				A.valor_total_empresa,";
			$sql .= "				X.texto1,";
			$sql .= "				X.texto2,";
			$sql .= "				X.mensaje,";
			$sql .= "				A.fechafac,";
			$sql .= "				C.direccion AS direccion_tercero,";
			$sql .= "				C.telefono AS telefono_tercero ";
			$sql .= "FROM		(	SELECT	A.empresa_id,";
			$sql .= "									A.prefijo,";
			$sql .= "									A.factura_fiscal,";
			$sql .= "									A.fecha_registro AS fechafac,";
			$sql .= "									A.documento_id,";
			$sql .= "									B.sw_tipo,";
			$sql .= "									(C.abono_efectivo + C.abono_cheque + C.abono_tarjetas + C.abono_chequespf + C.abono_bonos) AS abonos,";
			$sql .= "									C.numerodecuenta,";
			$sql .= "									C.ingreso,";
			$sql .= "									C.plan_id,";
			$sql .= "									C.valor_cuota_paciente,";
			$sql .= "									C.valor_nocubierto,";
			$sql .= "									C.valor_cubierto,";
			$sql .= "									C.valor_descuento_empresa,";
			$sql .= "									C.valor_descuento_paciente,";
			$sql .= "									C.total_cuenta,";
			$sql .= "									C.abono_efectivo,";
			$sql .= "									C.abono_cheque,";
			$sql .= "									C.abono_tarjetas,";
			$sql .= "									C.abono_chequespf,";
			$sql .= "									C.abono_letras,";
			$sql .= "									C.valor_total_paciente,";
			$sql .= "									C.valor_total_empresa ";
			$sql .= "					FROM		fac_facturas A, ";
			$sql .= "									fac_facturas_cuentas B,";
			$sql .= "									cuentas C ";
			$sql .= "					WHERE		A.empresa_id = '".$empresa."'  ";
			$sql .= "					AND			A.prefijo = '".$prefijo."' ";
			$sql .= "					AND			A.factura_fiscal = ".$factura." ";
			$sql .= "					AND			B.empresa_id = '".$empresa."' ";
			$sql .= "					AND			B.prefijo = '".$prefijo."' ";
			$sql .= "					AND			B.factura_fiscal = ".$factura." ";
			$sql .= "					AND			B.numerodecuenta = ".$numerodecuenta." ";
			$sql .= "					AND			C.numerodecuenta = ".$numerodecuenta." ";
			$sql .= "				) AS A,  ";
			$sql .= "				planes B,  ";
			$sql .= "				terceros C,  ";
			$sql .= "				ingresos D,  ";
			$sql .= "				pacientes E,  ";
			$sql .= "				departamentos H, ";
			$sql .= "				empresas I, ";
			$sql .= "				tipo_dptos J, ";
			$sql .= "				tipo_mpios K, ";
			$sql .= "				documentos X ";
			$sql .= "WHERE	B.plan_id = A.plan_id ";
			$sql .= "AND		C.tipo_id_tercero = B.tipo_tercero_id ";
			$sql .= "AND		C.tercero_id = B.tercero_id ";
			$sql .= "AND		D.ingreso = A.ingreso ";
			$sql .= "AND		E.paciente_id = D.paciente_id ";
			$sql .= "AND		E.tipo_id_paciente = D.tipo_id_paciente ";
			$sql .= "AND 		H.departamento = D.departamento_actual ";
			$sql .= "AND 		I.empresa_id = A.empresa_id ";
			$sql .= "AND 		J.tipo_pais_id = I.tipo_pais_id ";
			$sql .= "AND 		J.tipo_dpto_id = I.tipo_dpto_id ";
			$sql .= "AND 		K.tipo_pais_id = I.tipo_pais_id ";
			$sql .= "AND 		K.tipo_dpto_id = I.tipo_dpto_id ";
			$sql .= "AND 		K.tipo_mpio_id = I.tipo_mpio_id ";
			$sql .= "AND 		X.documento_id = A.documento_id ";
			$sql .= "AND 		X.empresa_id = A.empresa_id ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			
			while(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
				
			return $datos;
		}
		
		/**********************************************************************************
		* Funcion que permite cuadrar la cuenta cuando es un plan agrupado
		************************************************************************************/
		function ActualizarEstadoCuenta($cuenta, $estado, $transaccion)
		{
			$sql  =	"UPDATE cuentas SET estado = '".$estado."', ";
			$sql .= "usuario_cierre = ".UserGetUID()." ";
			$sql .=	" WHERE numerodecuenta = ".$cuenta.";";
			
			
			if(!$rst = $this->ConexionTransaccion($sql,"Actualiza Cuenta"))
				return false;
			
			
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