<?php
	/**************************************************************************************  
	* $Id: NotasCreditoGlosas.class.php,v 1.2 2010/03/16 13:00:58 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.2 $ 
	* 
	* @autor Hugo F  Manrique 
	***************************************************************************************/
	class NotasCreditoGlosas
	{
		function NotasCreditoGlosas(){}
		/**********************************************************************************
		* Funcion que permite generar la nota credito y debito de la glosa 
		* 
		* @return boolean 
		***********************************************************************************/
		function GenerarNotaCreditoDebito($empresa, $datos, $sistema)
		{			
			$this->ConexionTransaccion();
			
			$documento = ModuloGetVar('app','FacturacionNotaCD','documento_'.$empresa);
			$sql = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE ";//Bloqueo de tabla 
			
			if(!$rst = $this->ConexionTransaccion($sql,'1')) return false;
			
			$sql  = "SELECT prefijo,numeracion FROM documentos ";
			$sql .= "WHERE documento_id = ".$documento." AND empresa_id = '".$empresa."' ";
			
			if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
			
			$numeracion = array();
			if(!$rst->EOF)
      {
      	$numeracion = $rst->GetRowAssoc($ToUpper = false);				
      	$rst->MoveNext();
      }
			
			if(empty($numeracion))
			{
				$this->frmError['MensajeError'] = "NO SE HAN PARAMETRIZADO LOS VALORES DEL DOCUMENTO";
				return false;
			}
			
			if($datos['sw_glosa_parcial'] == '1')
			{
				$sql  = "INSERT INTO notas_credito_glosas ";
				$sql .= "		(documento_id,";
				$sql .= "		 empresa_id, ";
				$sql .= "		 prefijo, ";
				$sql .= "		 numero, ";
				$sql .= "		 glosa_id, ";
				$sql .= "		 usuario_id, ";
				$sql .= "		 fecha_registro,";
				$sql .= "		 valor_glosa,";
				$sql .= "		 valor_aceptado,";
				$sql .= "		 valor_no_aceptado ) ";
				$sql .= "VALUES ( ".$documento.", ";
				$sql .= "		 '".$empresa."', ";
				$sql .= "		 '".$numeracion['prefijo']."',";
				$sql .= "		  ".$numeracion['numeracion'].",";
				$sql .= "		  ".$datos['glosa_id'].", ";
				$sql .= "		  ".UserGetUID().", ";
				$sql .= "		    NOW(),";
				$sql .= "		  ".$datos['valor_glosa'].", ";
				$sql .= "		  ".$datos['valor_aceptado'].", ";
				$sql .= "		  ".$datos['valor_noaceptado']."); ";
				$sql .= "UPDATE glosas ";
				$sql .= "SET  	sw_estado = '3', ";
				$sql .= "	  		fecha_cierre = NOW() ";
				$sql .= "WHERE glosa_id = ".$datos['glosa_id']."; ";
				$sql .= "UPDATE glosas_detalle_cuentas ";
				$sql .= "SET sw_estado = '3' ";
				$sql .= "WHERE glosa_id = ".$datos['glosa_id']." ";
				$sql .= "AND sw_estado = '2'; ";
				if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
			}
			else
			{
				$sql  = "SELECT	glosa_detalle_cargo_id, ";
				$sql .= "				valor_glosa, ";
				$sql .= "				valor_aceptado,";
				$sql .= "				valor_no_aceptado ";
				$sql .= "FROM		glosas_detalle_cargos ";
				$sql .= "WHERE	glosa_id = ".$datos['glosa_id']." ";
				$sql .= "AND		sw_estado = '2' ";

				if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
			
				$retorno = array();
				while(!$rst->EOF)
				{
					$retorno[] = $rst->GetRowAssoc($ToUpper = false);				
					$rst->MoveNext();
				}
				$sql = "";
				foreach($retorno as $key => $cargos)
				{
					$sql .= "INSERT INTO notas_credito_glosas_detalle_cargos ";
					$sql .= "			(	documento_id,";
					$sql .= "				empresa_id, ";
					$sql .= "				prefijo, ";
					$sql .= "				numero, ";
					$sql .= "				glosa_id, ";
					$sql .= "		 		glosa_detalle_cargo_id, ";
					$sql .= "				usuario_id, ";
					$sql .= "				fecha_registro, ";
					$sql .= "				valor_glosa,";
					$sql .= "				valor_aceptado,";
					$sql .= "				valor_no_aceptado ) ";
					$sql .= "VALUES ( ".$documento.", ";
					$sql .= "		 '".$empresa."', ";
					$sql .= "		 '".$numeracion['prefijo']."',";
					$sql .= "		  ".$numeracion['numeracion'].",";
					$sql .= "		  ".$datos['glosa_id'].", ";
					$sql .= "		  ".$cargos['glosa_detalle_cargo_id'].", ";
					$sql .= "		  ".UserGetUID().", ";
					$sql .= "		    NOW(), ";
					$sql .= "		  ".$cargos['valor_glosa'].", ";
					$sql .= "		  ".$cargos['valor_aceptado'].", ";
					$sql .= "		  ".$cargos['valor_no_aceptado']."); ";
				}
				if($sql != "")
					if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
					
				$sql  = "SELECT glosa_detalle_inventario_id AS gdi, ";
				$sql .= "				valor_glosa, ";
				$sql .= "				valor_aceptado,";
				$sql .= "				valor_no_aceptado ";
				$sql .= "FROM		glosas_detalle_inventarios ";
				$sql .= "WHERE	glosa_id = ".$datos['glosa_id']." ";
				$sql .= "AND		sw_estado = '2' ";
				
				if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
				$retorno = array();
				while(!$rst->EOF)
				{
					$retorno[] = $rst->GetRowAssoc($ToUpper = false);				
					$rst->MoveNext();
				}
				$sql = "";
				
				foreach($retorno as $key => $insumos)
				{					
					$sql .= "INSERT INTO notas_credito_glosas_detalle_inventarios ";
					$sql .= "		(documento_id,";
					$sql .= "		 empresa_id, ";
					$sql .= "		 prefijo, ";
					$sql .= "		 numero, ";
					$sql .= "		 glosa_id, ";
					$sql .= "		 glosa_detalle_inventario_id, ";
					$sql .= "		 usuario_id, ";
					$sql .= "		 fecha_registro, ";
					$sql .= "		 valor_glosa,";
					$sql .= "		 valor_aceptado,";
					$sql .= "		 valor_no_aceptado ) ";
					$sql .= "VALUES ( ".$documento.",";
					$sql .= "		 '".$empresa."', ";
					$sql .= "		 '".$numeracion['prefijo']."',";
					$sql .= "		  ".$numeracion['numeracion'].",";
					$sql .= "		  ".$datos['glosa_id'].", ";
					$sql .= "		  ".$insumos['gdi'].", ";
					$sql .= "		  ".UserGetUID().", ";
					$sql .= "		    NOW(), ";
					$sql .= "		  ".$insumos['valor_glosa'].", ";
					$sql .= "		  ".$insumos['valor_aceptado'].", ";
					$sql .= "		  ".$insumos['valor_no_aceptado']."); ";
				}
				
				if($sql != "")
					if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
				
				$sql  = "UPDATE glosas ";
				$sql .= "SET  	sw_estado = '1', ";
				$sql .= "	  		fecha_cierre = NOW() ";
				$sql .= "WHERE glosa_id = ".$datos['glosa_id']."; ";
				$sql .= "UPDATE glosas_detalle_cuentas ";
				$sql .= "SET sw_estado = '1' ";
				$sql .= "WHERE glosa_id = ".$datos['glosa_id']." ";
				$sql .= "AND sw_estado = '2'; ";
				
				if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
			}
			
			$sql  = "UPDATE documentos ";
			$sql .= "SET numeracion = numeracion + 1 ";
			$sql .= "WHERE documento_id = ".$documento." AND empresa_id = '".$empresa."'; ";
			if($sistema == "SIIS")
			{
				$sql .= "UPDATE glosas_detalle_cargos ";
				$sql .= "SET sw_estado = '3' ";
				$sql .= "WHERE glosa_id= ".$datos['glosa_id']." ";
				$sql .= "AND sw_estado = '2'; ";
				$sql .= "UPDATE glosas_detalle_inventarios ";
				$sql .= "SET sw_estado = '3' ";
				$sql .= "WHERE glosa_id = ".$datos['glosa_id']." ";
				$sql .= "AND sw_estado = '2'; ";
			}
				
			if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
			
			$this->dbconn->CommitTrans();
			
			return $numeracion;
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