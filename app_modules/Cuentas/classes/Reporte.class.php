<?							
	/*********************************************************************************************
	* $Id: Reporte.class.php,v 1.7 2010/11/29 14:05:53 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.7 $
	*
	* @autor Hugo F. Manrique
	***********************************************************************************************/
	class Reporte
	{
		function Reporte(){}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerEncabezadoFactura($cuenta)
		{
			$sql  = "SELECT	(A.abono_efectivo + A.abono_cheque + A.abono_tarjetas + A.abono_chequespf + A.abono_bonos) AS abonos,";
			$sql .= "				A.numerodecuenta,";
			$sql .= "				A.ingreso,";
			$sql .= "				A.plan_id,";
			$sql .= "				A.empresa_id,";
			$sql .= "				B.plan_descripcion,";
			$sql .= "				C.nombre_tercero,";
			$sql .= "				C.tipo_id_tercero,";
			$sql .= "				C.tercero_id,";
			$sql .= "				D.tipo_id_paciente,";
			$sql .= "				D.paciente_id,";
			$sql .= "				E.primer_nombre||' '||E.segundo_nombre||' '||E.primer_apellido||' '||E.segundo_apellido AS nombre,";
			$sql .= "				E.residencia_telefono,";
			$sql .= "				E.residencia_direccion,";
			$sql .= "				D.departamento_actual AS dpto,";
			$sql .= "				H.descripcion,";
			$sql .= "				I.razon_social,";
			$sql .= "				I.direccion,";
			$sql .= "				I.telefonos,";
			$sql .= "				I.tipo_id_tercero AS tipoid,";
			$sql .= "				I.id,";
			$sql .= "				J.departamento, ";
			$sql .= "				K.municipio, ";
			$sql .= "				D.fecha_registro, ";
			$sql .= "				A.rango, ";
			$sql .= "				Z.tipo_afiliado_nombre,";
			$sql .= "				B.nombre_cuota_moderadora, ";
			$sql .= "				B.nombre_copago, ";
			$sql .= "				X.nombre AS usuario,";
			$sql .= "				X.usuario_id,";
			$sql .= "				A.valor_cuota_moderadora,";
			$sql .= "				A.valor_cuota_paciente,";
			$sql .= "				A.valor_nocubierto,";
			$sql .= "				A.valor_total_paciente,";
			$sql .= "				A.valor_total_empresa,";
			$sql .= "				A.valor_descuento_paciente,";
			$sql .= "				A.valor_descuento_empresa,";
			$sql .= "				A.valor_cubierto ";
			$sql .= "FROM		cuentas A,";
			$sql .= "				planes B, ";
			$sql .= "				terceros C, ";
			$sql .= "				pacientes E,";
			$sql .= "				departamentos H,";
			$sql .= "				empresas I,";
			$sql .= "				tipo_dptos J,";
			$sql .= "				tipo_mpios K,";
			$sql .= "				ingresos D,";
			$sql .= "				system_usuarios X,";
			$sql .= "				tipos_afiliado Z ";
			$sql .= "WHERE	A.numerodecuenta = ".$cuenta." ";
			$sql .= "AND		A.plan_id = A.plan_id ";
			$sql .= "AND 		B.tercero_id=c.tercero_id ";
			$sql .= "AND 		B.tipo_tercero_id = C.tipo_id_tercero ";
			$sql .= "AND 		X.usuario_id = ".UserGetUID()." ";
			$sql .= "AND 		A.tipo_afiliado_id = Z.tipo_afiliado_id ";
			$sql .= "AND 		D.ingreso=a.ingreso ";
			$sql .= "AND 		D.tipo_id_paciente = E.tipo_id_paciente ";
			$sql .= "AND 		D.paciente_id = E.paciente_id ";
			$sql .= "AND 		A.empresa_id = I.empresa_id ";
			$sql .= "AND 		I.tipo_pais_id = J.tipo_pais_id ";
			$sql .= "AND 		I.tipo_dpto_id = J.tipo_dpto_id ";
			$sql .= "AND 		I.tipo_pais_id = K.tipo_pais_id ";
			$sql .= "AND 		I.tipo_dpto_id = K.tipo_dpto_id ";
			$sql .= "AND 		I.tipo_mpio_id = K.tipo_mpio_id ";
			$sql .= "AND 		D.departamento_actual = H.departamento";
			
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
		*
		***********************************************************************************/
		function ObtenerFactura($cuenta)
		{
			$sql  = "SELECT C.prefijo, ";
			$sql .= "				C.factura_fiscal, ";
			$sql .= "				A.valor_nocubierto,";
			$sql .= "				A.precio,";
			$sql .= "				A.cargo, ";
			$sql .= "				A.tarifario_id, ";
			$sql .= "				A.cantidad, ";
			$sql .= "				A.fecha_cargo, ";
			$sql .= "				A.transaccion,";
			$sql .= "				B.descripcion AS desccargo, ";
			$sql .= "				A.departamento, ";
			$sql .= "				B.grupo_tipo_cargo, ";
			$sql .= "				C.sw_tipo,";
			$sql .= "				E.texto1, ";
			$sql .= "				E.texto2, ";
			$sql .= "				E.mensaje, ";
			$SQL .= "				F.* ";
			$sql .= "FROM		cuentas_detalle A,";
			$sql .= "				tarifarios_detalle B, ";
			$sql .= "				fac_facturas_cuentas C,";
			$sql .= "				documentos E, ";
			$sql .= "				fac_facturas F ";
			$sql .= "WHERE	A.numerodecuenta = ".$cuenta." ";
			$sql .= "AND 		A.cargo = B.cargo ";
			$sql .= "AND 		A.tarifario_id = B.tarifario_id ";
			$sql .= "AND 		A.cargo != 'DESCUENTO' ";
			$sql .= "AND 		C.numerodecuenta = A.numerodecuenta ";
			$sql .= "AND 		C.sw_tipo IN ('0','2') ";
			$sql .= "AND 		A.empresa_id = E.empresa_id ";
			$sql .= "AND 		C.prefijo = E.prefijo ";
			$sql .= "AND 		C.prefijo = F.prefijo ";
			$sql .= "AND 		C.factura_fiscal = F.factura_fiscal ";
			$sql .= "ORDER BY B.grupo_tipo_cargo DESC ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i = 1;
			$datos = array();
			
			while(!$rst->EOF)
			{
				$datos[$i++] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
				
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerFacturasEmpresa($cuenta)
		{
			$sql  = "SELECT	C.prefijo, ";
			$sql .= "				C.factura_fiscal,";
			$sql .= "				A.valor_nocubierto,";
			$sql .= "				A.precio,";
			$sql .= "				A.cargo, ";
			$sql .= "				A.tarifario_id, ";
			$sql .= "				A.cantidad, ";
			$sql .= "				A.fecha_cargo, ";
			$sql .= "				A.transaccion, ";
			$sql .= "				B.descripcion AS desccargo, ";
			$sql .= "				A.departamento, ";
			$sql .= "				B.grupo_tipo_cargo,";
			$sql .= "				C.sw_tipo,";
			$sql .= "				E.texto1,"; 
			$sql .= "				E.texto2,";
			$sql .= "				E.mensaje,";
			$sql .= "				F.* ";
			$sql .= "FROM 	cuentas_detalle A,";
			$sql .= "				tarifarios_detalle B,";
			$sql .= "				fac_facturas_cuentas C,";
			$sql .= "				documentos E,";
			$sql .= "				fac_facturas F ";
			$sql .= "WHERE	A.numerodecuenta = ".$cuenta." ";
			$sql .= "AND		A.cargo = B.cargo ";
			$sql .= "AND 		A.tarifario_id = B.tarifario_id ";
			$sql .= "AND 		A.cargo != 'DESCUENTO' ";
			$sql .= "AND 		C.numerodecuenta = A.numerodecuenta ";
			$sql .= "AND 		C.sw_tipo = '1' ";
			$sql .= "AND 		A.empresa_id = E.empresa_id ";
			$sql .= "AND 		C.prefijo = E.prefijo ";
			$sql .= "AND		C.prefijo = F.prefijo ";
			$sql .= "AND 		C.factura_fiscal = F.factura_fiscal ";
			$sql .= "ORDER BY B.grupo_tipo_cargo DESC ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i = 1;
			$datos = array();
			
			while(!$rst->EOF)
			{
				$datos[$i++] = $rst->GetRowAssoc($ToUpper = false);
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