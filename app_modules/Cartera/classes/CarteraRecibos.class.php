<?php
  /**
  * $Id: CarteraRecibos.class.php,v 1.1 2007/12/11 15:08:06 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.1 $ 
	* 
	* @autor Hugo F  Manrique 
  */
	class CarteraRecibos
	{
		function CarteraRecibos(){}
		/***
		*
		***/
		function ObtenerTercerosRecibos($empresa)
		{	
			$sql .= "SELECT DISTINCT TE.nombre_tercero,";
			$sql .= "				TE.tipo_id_tercero,";
			$sql .= "				TE.tercero_id ";
			$sql .= "FROM		terceros TE,";
			$sql .= "				recibos_caja RC ";
      $sql .= "WHERE	TE.tipo_id_tercero = RC.tipo_id_tercero ";
			$sql .= "AND		TE.tercero_id = RC.tercero_id ";
			$sql .= "AND		RC.empresa_id = '".$empresa."' ";
			$sql .= "AND		RC.sw_recibo_tesoreria = '1' ";
			$sql .= "ORDER BY 1 ";
					
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[]  = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/***
		*
		***/
		function ObtenerPrefijosRecibos($empresa)
		{	
			$sql .= "SELECT DISTINCT prefijo ";
			$sql .= "FROM		recibos_caja RC ";
      $sql .= "WHERE	RC.empresa_id = '".$empresa."' ";
			$sql .= "AND		RC.sw_recibo_tesoreria = '1' ";
					
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[]  = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/***
		*
		***/
		function ObtenerRecibosCaja($empresa,$datos,$contar="1")
		{			
			$f = explode("/",$datos['fecha_inicio']);
			$fechai = $f[2]."-".$f[1]."-".$f[0];

			$f = explode("/",$datos['fecha_fin']);
			$fechaf = $f[2]."-".$f[1]."-".$f[0];

			$sql .= "SELECT DISTINCT RC.prefijo,";
			$sql .= "				RC.recibo_caja,";
			$sql .= "				RC.total_abono,";
			$sql .= "				RC.total_abono AS valor_recibo,";
			$sql .= "				RC.total_abono + COALESCE(RT.valor,0) AS valor_final,";
			$sql .= "				COALESCE(RD.valor,0) + COALESCE(RF.valor_facturas,0) AS valor_credito,";
			$sql .= " 			RC.total_efectivo,";
			$sql .= " 			RC.total_cheques,";
			$sql .= " 			RC.total_tarjetas,";
			$sql .= "				RC.total_consignacion,";
			$sql .= "				RC.otros,";
			$sql .= "				COALESCE(RF.valor_facturas,0) AS valor_facturas,";
			$sql .= "				RC.empresa_id,";
			$sql .= "				TO_CHAR(RC.fecha_ingcaja,'DD/MM/YYYY') AS fecha_ingcaja, ";
			$sql .= "				SU.nombre, ";
			$sql .= "				TE.nombre_tercero, ";
			$sql .= "				TE.tercero_id, ";
			$sql .= "				TE.tipo_id_tercero ";
			$where .= "FROM		recibos_caja RC ";
			$where .= "				LEFT JOIN ( ";
			$where .= "					SELECT	SUM(valor) AS valor,";
			$where .= "									prefijo, ";
			$where .= "									recibo_caja, ";
			$where .= "									empresa_id ";
			$where .= "					FROM		rc_detalle_tesoreria_conceptos RT ";
			$where .= "					WHERE		empresa_id = '".$empresa."' ";
			$where .= "					AND			naturaleza = 'D'::bpchar ";
			$where .= "					GROUP BY prefijo,recibo_caja,empresa_id) AS RT ";
			$where .= "				ON(	RT.recibo_caja = RC.recibo_caja AND ";
			$where .= "						RT.prefijo = RC.prefijo AND ";
			$where .= "						RT.empresa_id = RC.empresa_id ) ";
			$where .= "				LEFT JOIN ( ";
			$where .= "					SELECT	SUM(valor) AS valor,";
			$where .= "									prefijo, ";
			$where .= "									recibo_caja, ";
			$where .= "									empresa_id ";
			$where .= "					FROM		rc_detalle_tesoreria_conceptos RT ";
			$where .= "					WHERE		empresa_id = '".$empresa."' ";
			$where .= "					AND			naturaleza = 'C'::bpchar ";
			$where .= "					GROUP BY prefijo,recibo_caja,empresa_id) AS RD ";
			$where .= "				ON(	RD.recibo_caja = RC.recibo_caja AND ";
			$where .= "						RD.prefijo = RC.prefijo AND ";
			$where .= "						RD.empresa_id = RC.empresa_id ) ";			
			$where .= "				 LEFT JOIN ( SELECT	SUM(valor_abonado) AS valor_facturas,";
			$where .= "														recibo_caja, ";
			$where .= "														prefijo, ";
			$where .= "														empresa_id, ";
			$where .= "														centro_utilidad ";
			$where .= "										FROM		rc_detalle_tesoreria_facturas ";
			$where .= "										GROUP BY 2,3,4,5) AS RF ";
			$where .= "				ON(	RF.recibo_caja = RC.recibo_caja AND ";
			$where .= "						RF.prefijo = RC.prefijo AND ";
			$where .= "						RF.centro_utilidad = RC.centro_utilidad AND ";
			$where .= "						RF.empresa_id = RC.empresa_id ), ";
			$where .= "				system_usuarios SU, ";
			$where .= "				terceros TE ";
			$where .= "WHERE	RC.empresa_id = '".$empresa."' ";
			$where .= "AND		SU.usuario_id = RC.usuario_id ";
			$where .= "AND		RC.sw_recibo_tesoreria = '1' ";
			$where .= "AND		RC.estado = '2' ";
			$where .= "AND		TE.tipo_id_tercero = RC.tipo_id_tercero ";
			$where .= "AND		TE.tercero_id = RC.tercero_id ";

			if($datos['tercero'])
			{
				$where .= "AND		RC.tipo_id_tercero = '".$datos['tipo_id_tercero']."' ";
				$where .= "AND		RC.tercero_id = '".$datos['tercero_id']."' ";
			}
			
			if($datos['numero'])
				$where .= "AND		RC.recibo_caja = ".$datos['numero']." ";
			
			if($datos['prefijo'])
				$where .= "AND		RC.prefijo = '".$datos['prefijo']."' ";
			
			if($datos['fecha_inicio'])
				$where .= "AND		RC.fecha_ingcaja::date >= '".$fechai."' ";
			
			if($datos['fecha_fin'])
				$where .= "AND		RC.fecha_ingcaja::date <= '".$fechaf."' ";
			
			if($contar == "1")
			{
				$sqlC = "SELECT COUNT(*) $where";
				$this->ProcesarSqlConteo($sqlC);
			}
			
			$sql .= "$where ";
			$sql .= "ORDER BY RC.prefijo,RC.recibo_caja DESC ";
			
			if($contar == "1")
				$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i=0;
			$recibos = array();
			while (!$rst->EOF)
			{
				$pago = "";
				$recibos[$i]  = $rst->GetRowAssoc($ToUpper = false);
				
				if($recibos[$i]['total_cheques'] > 0)	
					$pago .= "<li>CHEQUE ";
				
				if($recibos[$i]['total_efectivo'] > 0) 
					$pago .= "<li>EFECTIVO ";
									
				if($recibos[$i]['total_tarjetas'] > 0) 
					$pago .= "<li>TARJETA ";
					
				if($recibos[$i]['total_consignacion'] > 0) 
					$pago .= "<li>CONSIGNACIÓN ";

				if($recibos[$i]['otros'] > 0) 
					$pago .= "<li>OTROS ";
	
				$recibos[$i]['forma_pago'] = $pago;
				
				$rst->MoveNext();
				$i++;
		  }
			$rst->Close();
			
			return $recibos;
		}
		/**
		*
		**/
		function ObtenerUsuarioNombre($id)
		{
			$sql  = "SELECT U.nombre, A.usuario_id ";
			$sql .= "FROM 	system_usuarios U LEFT JOIN auditores_internos A ";
			$sql .= "				ON(U.usuario_id = A.usuario_id) ";
			$sql .= "WHERE 	U.usuario_id = ".$id;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			$usuario = array();
			if (!$rst->EOF)
			{
				$usuario = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $usuario;
		}
		/*********************************************************************************
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*********************************************************************************/
		function ProcesarSqlConteo($consulta,$limite=null)
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
			
			if($_REQUEST['offset'])
			{
				$this->paginaActual = intval($_REQUEST['offset']);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}		

			if(!$result = $this->ConexionBaseDatos($consulta))
				return false;

			if(!$result->EOF)
			{
				$this->conteo = $result->fields[0];
				$result->MoveNext();
			}
			$result->Close();
			return true;
		}
		/***********************************************************************************
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