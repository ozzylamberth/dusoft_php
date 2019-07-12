<?php
	/**************************************************************************************  
	* $Id: AuditoriaCuentas.class.php,v 1.4 2009/03/19 20:32:41 cahenao Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.4 $ 
	* 
	* @autor Hugo F  Manrique 
	***************************************************************************************/
	class AuditoriaGlosas
	{
		function AuditoriaGlosas(){}
		/************************************************************************************ 
		* 
		* @access private 
		*************************************************************************************/
		/************************************************************************************
		* Funcion que permite aceptar los valores de aceptados y no aceptados de la factura
		* 
		* @return boolean
		*************************************************************************************/
		function AceptarGlosaFactura($empresa, $datos,$sistema)
		{
			if($sistema == "SIIS")
			{
				$sql .= "UPDATE	glosas_detalle_cuentas ";
				$sql .= "SET	sw_estado = '2' ";
				$sql .= "WHERE	glosa_id = ".$datos['glosa_id']." ";
				$sql .= "AND	sw_estado = '1'; ";
				
				$sql .= "UPDATE	glosas_detalle_cargos ";
				$sql .= "SET	sw_estado = '2' ";
				$sql .= "WHERE	glosa_id = ".$datos['glosa_id']." ";
				$sql .= "AND	sw_estado = '1'; ";
				
				$sql .= "UPDATE	glosas_detalle_inventarios ";
				$sql .= "SET	sw_estado = '2' ";
				$sql .= "WHERE	glosa_id = ".$datos['glosa_id']." ";
				$sql .= "AND	sw_estado  = '1'; ";
			}
			$sql .= "UPDATE	glosas ";
			$sql .= "SET		sw_estado = '2', ";
			$sql .= "				sw_glosa_parcial = '1', ";
			$sql .= "				valor_aceptado = ".$datos['valor_aceptado'].",";
			$sql .= "				valor_no_aceptado = ".$datos['valor_noaceptado']." ";
			$sql .= "WHERE	glosa_id = ".$datos['glosa_id']."; ";
					
			if(!$rst->ConexionBaseDatos($sql)) return false;
			
			IncludeClass('NotasCreditoGlosas','','app','FacturacionNotaCD');
			$nc = new NotasCreditoGlosas();
			
			$datos['sw_glosa_parcial'] = '1';
			$rst = $nc->GenerarNotaCreditoDebito($empresa, $datos, $sistema);
			
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
				$this->frmError['MensajeError'] = "<b class=\"label_error\">ERROR DB : " . $dbconn->ErrorMsg()."</b>";
				echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}
	}
?>