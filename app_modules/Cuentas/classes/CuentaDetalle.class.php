<?php
	/*********************************************************************************************
	* $Id: CuentaDetalle.class.php,v 1.7 2010/11/29 14:05:53 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.7 $
	*
	* @autor Hugo F. Manrique
	***********************************************************************************************/
	class CuentaDetalle	
	{
		function CuentaDetalle(){}
		/**********************************************************************************
		*
		* @return rst
		************************************************************************************/
		function IngresarCuotaCopago($datos,$nombre)
		{
			$sql  = "INSERT INTO cuentas_modificacion_".$nombre." ";
			$sql .= "		(";
			$sql .= "			numerodecuenta,";
			$sql .= "			valor, ";
			$sql .= "			motivo_cambio_".$nombre."_id,";
			$sql .= "			observacion,";
			$sql .= "			fecha_registro,";
			$sql .= "			usuario_id ";
			$sql .= "		) ";
			$sql .= "VALUES (";
			$sql .= "		 ".$datos['numerodecuenta'].", ";
			$sql .= "		 ".$datos['valor'].", ";
			$sql .= "		'".$datos['motivo_id']."',";
			$sql .= "		'".$datos['observacion']."',";
			$sql .= "		 NOW(),";
			$sql .= "		 ".UserGetUID()." ";
			$sql .= "		) ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		*
		* @return rst
		************************************************************************************/
		function ActualizarCuotaCopago($datos,$nombre)
		{
			$sql  = "UPDATE cuentas_modificacion_".$nombre." ";
			$sql .= "SET		valor = ".$datos['valor'].", ";
			$sql .= "				motivo_cambio_".$nombre."_id = '".$datos['motivo_id']."',";
			$sql .= "				observacion = '".$datos['observacion']."',";
			$sql .= "				fecha_registro = NOW(),";
			$sql .= "				usuario_id = ".UserGetUID()." ";
			$sql .= "WHERE 	numerodecuenta = ".$datos['numerodecuenta']." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		*
		* @return rst
		************************************************************************************/
		function ObtenerDatosCuotaCopago($datos,$nombre)
		{
			$sql .= "SELECT COUNT(*) AS contador ";
			$sql .= "FROM		cuentas_modificacion_".$nombre." ";
			$sql .= "WHERE 	numerodecuenta = ".$datos['numerodecuenta']." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos['contador'] =  0;
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
				
			$rst->Close();
			
			return $datos['contador'];
		}
		
		function ObtenerEstadoCuenta($Cuenta)
		{
			$sql = "SELECT estado ";
			$sql .= "FROM		cuentas ";
			$sql .= "WHERE 	numerodecuenta = ".$Cuenta." ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos['estado'] =  0;
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
				
			$rst->Close();
			
			return $datos['estado'];
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