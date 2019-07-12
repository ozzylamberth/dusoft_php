<?php
  /******************************************************************************
  * $Id: InformacionCuentas.class.php,v 1.7 2010/11/29 14:05:53 hugo Exp $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.7 $ 
	* 
	* @autor
  ********************************************************************************/
	IncludeClass('InformacionCuentas','','app','Cuentas');
	class InformacionCuentas
	{
		function InformacionCuentas(){}

		/**
		*DatosFactura
		*/
		function DatosFactura($cuenta)
		{//f.tipo_factura=g.tipo_factura and lo que se corto del query
					list($dbconn) = GetDBconn();
					$query = "select (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos) as abonos,
										a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
										c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,
										e.primer_apellido||' '||e.segundo_apellido||' '||e.primer_nombre||' '||e.segundo_nombre as nombre,
										e.residencia_telefono, e.residencia_direccion, f.prefijo, f.factura_fiscal,  d.departamento_actual as dpto, h.descripcion,
										i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid, i.id, j.departamento, k.municipio, d.fecha_registro
										from cuentas as a, planes as b, terceros as c, pacientes as e, fac_facturas_cuentas as f,  departamentos as  h,
										empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d
										where a.numerodecuenta=$cuenta and a.plan_id=b.plan_id and b.tercero_id=c.tercero_id
										and b.tipo_tercero_id=c.tipo_id_tercero
										and d.ingreso=a.ingreso and d.tipo_id_paciente=e.tipo_id_paciente
										and d.paciente_id=e.paciente_id and a.numerodecuenta=f.numerodecuenta
										and a.empresa_id=i.empresa_id and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
										and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id
										and d.departamento_actual=h.departamento";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
	
					$vars=$result->GetRowAssoc($ToUpper = false);
	
					$result->Close();
					return $vars;
		}

		/**
		* Busca el detalle de una cuenta en la tabla cuentas_detalle.
		* @access public
		* @return array
		* @param int numero de Cuenta
		*/
		function BuscarDetalleCuenta($Cuenta)
		{
			list($dbconn) = GetDBconn();
			$query = "SELECT  *
								FROM cuentas_detalle as a 
								WHERE a.numerodecuenta='$Cuenta'
								AND a.facturado=1";

			$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}

			while(!$result->EOF)
			{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}
			$result->Close();
			return $vars;
		}

		/**
		*
		*/
		function TraerReportesHojaCargos($EmpresaId)
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT ruta_reporte, titulo
														FROM    reportes_facturas_clientes_planes
														WHERE empresa_id='".$EmpresaId."'
														AND sw_hoja_cargos = '1';";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
								$this->error = "Error al Seleccionar fechas envios";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}
				while(!$result->EOF)
				{
						$vars[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
				$result->Close();
				return $vars;
		}

		/**
		*
		*/
		function BuscarFacturas($EmpresaId,$Cuenta,$estado)
		{
				list($dbconn) = GetDBconn();
				if($estado == 'Estado')
				{
					$query = "SELECT CASE WHEN C.estado = '1' THEN 'ACTIVA'
																	WHEN C.estado = '2' THEN 'INACTIVA'
																	WHEN C.estado = '3' THEN 'CUADRADA'
																	WHEN C.estado = '0' THEN 'FACTURADA'
															END AS estado
									FROM 
												cuentas C
									WHERE C.numerodecuenta = $Cuenta
									AND C.empresa_id='".$EmpresaId."';";
				}
				else
				{
					$query = "SELECT FF.prefijo, 
												FF.factura_fiscal,
												FF.sw_clase_factura,
												FF.tipo_factura
									FROM fac_facturas FF,
												fac_facturas_cuentas FFC,
												cuentas C
									WHERE FFC.empresa_id='".$EmpresaId."'
									AND FFC.numerodecuenta = $Cuenta
									AND FF.empresa_id = FFC.empresa_id
									AND FF.prefijo = FFC.prefijo
									AND FF.factura_fiscal = FFC.factura_fiscal
									AND FF.estado IN('0','1')
									AND FFC.numerodecuenta = C.numerodecuenta
									AND C.estado IN('0');";
					}
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
								$this->error = "Error al Seleccionar fechas envios";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}
				while(!$result->EOF)
				{
						$vars[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
				$result->Close();
				return $vars;
		}
	}
?>