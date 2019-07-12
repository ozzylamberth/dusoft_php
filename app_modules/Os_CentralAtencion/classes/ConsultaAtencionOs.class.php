<?php
	/**************************************************************************************
	* $Id: ConsultaAtencionOs.class.php,v 1.1 2010/01/20 20:58:30 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F. Manrique
	***************************************************************************************/
	class ConsultaAtencionOs
	{
		function ConsultaAtencionOs(){}
		/**********************************************************************************
		* @return rst 
		************************************************************************************/
	  function ObtenerDatosCuentas($datos)
    	  {
			$sql  = "SELECT	CU.estado,";
			$sql .= "				COALESCE(CU.departamento,'0') AS dpto, "; 
			$sql .= "				CU.ingreso, "; 
			$sql .= "				CU.numerodecuenta "; 
			$sql .= "FROM		cuentas CU, ";
			$sql .= "				ingresos IG ";
			$sql .= "WHERE	CU.plan_id = ".$datos['plan_id']." ";
			$sql .= "AND		IG.tipo_id_paciente = '".$datos['tipo_id']."' ";
			$sql .= "AND		IG.paciente_id = '".$datos['paciente_id']."' ";
			$sql .= "AND		CU.estado IN ('1','2') ";
			$sql .= "AND		CU.ingreso = IG.ingreso   ";			
			
			if($datos['ingreso'])
				$sql .= "AND		IG.ingreso = ".$datos['ingreso']." ";	
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]][$rst->fields[1]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
    	 }
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerDatosOs($orden)
		{
			$sql  = "SELECT tipo_afiliado_id, 	";
			$sql .= "				semanas_cotizadas, ";
			$sql .= "				servicio, ";
			$sql .= "				tipo_id_paciente, ";
			$sql .= "				paciente_id, ";
			$sql .= "				rango ";
			$sql .= "FROM		os_ordenes_servicios ";
			$sql .= "WHERE	orden_servicio_id = ".$orden." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
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
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				//echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}
	}
?>