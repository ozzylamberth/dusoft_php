<?php
	/**************************************************************************************  
	* $Id: AuditoriaGlosas.class.php,v 1.4 2009/03/19 20:32:41 cahenao Exp $ 
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
		function AceptarGlosaFactura($empresa, $datos, $sistema)
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
					
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			IncludeClass('NotasCreditoGlosas','','app','FacturacionNotaCD');
			$nc = new NotasCreditoGlosas();
			$datos['sw_glosa_parcial'] = '1';
			$rst = $nc->GenerarNotaCreditoDebito($empresa, $datos, $sistema);
			$this->frmError['MensajeError'] = $nc->frmError['MensajeError'];
			return $rst;
		}
		/************************************************************************************
		* Funcion que permite ingresar los valores aceptados y no acepatdos de las cuentas
		* 
		* @return boolean
		*************************************************************************************/
		function AceptarGlosaCuenta($empresa, $datos, $sistema)
		{
			$sql .= "UPDATE	glosas_detalle_cuentas ";
			$sql .= "SET	valor_aceptado = ".$datos['valor_aceptado'] .", ";
			$sql .= "			valor_no_aceptado = ".$datos['valor_noaceptado'] .", ";
			$sql .= "			sw_estado = '2' ";
			$sql .= "WHERE	glosa_detalle_cuenta_id = ".$datos['glosa_id_cuenta']." ";
			$sql .= "AND		glosa_id = ".$datos['glosa_id']." ";
			$sql .= "AND		numerodecuenta = ".$datos['numero_cuenta']."; ";
			
			$sql .= "UPDATE	glosas_detalle_cargos ";
			$sql .= "SET		sw_estado = '2', ";
			$sql .= "				valor_aceptado = 0, ";
			$sql .= "				valor_no_aceptado = 0 ";
			
			$sql .= "WHERE	glosa_detalle_cuenta_id = ".$datos['glosa_id_cuenta']." ";
			$sql .= "AND		glosa_id = ".$datos['glosa_id']." ";
			$sql .= "AND		sw_estado NOT IN ('3','0'); ";
			
			$sql .= "UPDATE	glosas_detalle_inventarios ";
			$sql .= "SET		sw_estado = '2', ";
			$sql .= "				valor_aceptado = 0, ";
			$sql .= "				valor_no_aceptado = 0 ";

			$sql .= "WHERE	glosa_detalle_cuenta_id = ".$datos['glosa_id_cuenta']." ";
			$sql .= "AND		glosa_id = ".$datos['glosa_id']." ";
			$sql .= "AND		sw_estado NOT IN ('3','0'); ";

			if($datos['cantidad'] == '1')
			{
				$sql .= "UPDATE	glosas ";
				$sql .= "SET	sw_estado = '2', ";
				$sql .= "		sw_glosa_parcial = '1' ";
				$sql .= "WHERE	glosa_id = ".$datos['glosa_id']."; ";
				$datos['sw_glosa_parcial'] = '1';
			}
			else
			{
				$sql .= "UPDATE	glosas ";
				$sql .= "SET	sw_estado = '2', ";
				$sql .= "		sw_glosa_parcial = '0' ";
				$sql .= "WHERE	glosa_id = ".$datos['glosa_id']."; ";
				$datos['sw_glosa_parcial'] = '0';
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			IncludeClass('NotasCreditoGlosas','','app','FacturacionNotaCD');
			$nc = new NotasCreditoGlosas();
			
			$rst = $nc->GenerarNotaCreditoDebito($empresa, $datos, $sistema);
			$this->frmError['MensajeError'] = $nc->frmError['MensajeError'];
			return $rst;
		}
		/************************************************************************************
		* Funcion que permite ingresar los valores aceptados y no aceptados de los cargos e 
		* insumos
		*
		* @return boolean
		*************************************************************************************/
		function AceptarGlosaCargos($empresa, $datos, $sistema)
		{
			$i = 0;
			$sql = "";
			foreach($datos['transaccion'] as $key => $cargos)
			{
				if($datos['valor_aceptado'][$i] > 0 || $datos['valor_noaceptado'][$i] > 0)
				{
					$datos['valoraceptadocuenta'] += $datos['valor_aceptado'][$i];
					$datos['valornoaceptadocuenta'] += $datos['valor_noaceptado'][$i];
				
					$sql .= "UPDATE	glosas_detalle_cargos ";
					$sql .= "SET		sw_estado = '2', ";
					$sql .= "				valor_aceptado = ".$datos['valor_aceptado'][$i].",";
					$sql .= "				valor_no_aceptado = ".$datos['valor_noaceptado'][$i]." ";
					$sql .= "WHERE	glosa_detalle_cuenta_id = ".$datos['glosa_id_cuenta']." ";
					$sql .= "AND		glosa_detalle_cargo_id = ".$datos['glosa_detalle_id'][$i]." ";
					$sql .= "AND		glosa_id = ".$datos['glosa_id']." ";
					$sql .= "AND		sw_estado <> '0'; ";
				}
				$i++;
			}
			
			foreach($datos['insumo'] as $key => $insummo)
			{
				if($datos['valor_aceptado'][$i] > 0 || $datos['valor_noaceptado'][$i] > 0)
				{
					$datos['valoraceptadocuenta'] += $datos['valor_aceptado'][$i];
					$datos['valornoaceptadocuenta'] += $datos['valor_noaceptado'][$i];

					$sql .= "UPDATE	glosas_detalle_inventarios ";
					$sql .= "SET		sw_estado = '2', ";
					$sql .= "				valor_aceptado = ".$datos['valor_aceptado'][$i].",";
					$sql .= "				valor_no_aceptado = ".$datos['valor_noaceptado'][$i]." ";
					$sql .= "WHERE	glosa_detalle_cuenta_id = ".$datos['glosa_id_cuenta']." ";
					$sql .= "AND		glosa_detalle_inventario_id = ".$datos['glosa_detalle_id'][$i]." ";
					$sql .= "AND		glosa_id = ".$datos['glosa_id']." ";
					$sql .= "AND		sw_estado <> '0'; ";
				}
				$i++;
			}
			
			if($sql == "")
			{
				$this->frmError['MensajeError'] = "NO SE ESPECIFICARON CARGOS Y/O INSUMOS PARA LA CRACION DE LA NOTA";
				return false;
			}
			
			$this->ConexionTransaccion();
			if(!$rst = $this->ConexionTransaccion($sql,'1')) return false;
			
			$sql .= "UPDATE	glosas_detalle_cuentas ";
			$sql .= "SET		sw_estado = '2', ";
			$sql .= "				valor_aceptado = 0,";
			$sql .= "				valor_no_aceptado = 0 ";
			$sql .= "WHERE	glosa_detalle_cuenta_id = ".$datos['glosa_id_cuenta']." ";
			$sql .= "AND		glosa_id = ".$datos['glosa_id']." ";
			$sql .= "AND		numerodecuenta = ".$datos['numero_cuenta']."; ";
			
			$sql .= "UPDATE	glosas ";
			$sql .= "SET		sw_estado = '2', ";
			$sql .= "				sw_glosa_parcial = '0' ";
			$sql .= "WHERE	glosa_id = ".$datos['glosa_id']."; ";
			
			if(!$rst = $this->ConexionTransaccion($sql,'1')) return false;
			$this->dbconn->CommitTrans();
			
			IncludeClass('NotasCreditoGlosas','','app','FacturacionNotaCD');
			$nc = new NotasCreditoGlosas();
			
			$datos['sw_glosa_parcial'] = '0';
			$rst = $nc->GenerarNotaCreditoDebito($empresa, $datos, $sistema);
			$this->frmError['MensajeError'] = $nc->frmError['MensajeError'];
			return $rst;
		}
		/************************************************************************************
		* Funcion que permite ingresar los valores aceptados y no aceptados de los cargos e 
		* insumos
		*
		* @return boolean
		*************************************************************************************/
		function ActualizarGlosa($empresa,$glosa)
		{
			$sql  = "UPDATE	glosas ";
			$sql .= "SET		sw_estado = '3' ";
			$sql .= "WHERE	glosa_id = ".$glosa." ";
			$sql .= "AND		empresa_id = '".$empresa."'; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
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
			}
				return $rst;
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