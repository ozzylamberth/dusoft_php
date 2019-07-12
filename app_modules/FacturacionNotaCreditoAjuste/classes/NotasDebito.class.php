<?php
  /**
  * $Id: NotasDebito.class.php,v 1.2 2010/03/12 18:41:36 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.2 $ 
	* 
	* @autor Hugo F  Manrique 
  */
	class NotasDebito
	{
		function NotasDebito(){}
		/**********************************************************************************
		* Funcion donde se buscan las Notas De Ajuste temporales 
		* 
		* @return array 
		***********************************************************************************/
		function ObtenerNotasPorAnticipos($usuario,$datos,$empresa)
		{			
			$sql .= "SELECT TO_CHAR(NA.fecha_registro,'DD /MM /YYYY') AS fecha,";
			$sql .= "				NA.tmp_nota_debito_id,";
			$sql .= "				NA.prefijo_factura,";
			$sql .= "				NA.factura_fiscal,";
			$sql .= "				COALESCE(NC.valor,0) AS conceptos ";
			$sql .= "FROM		tmp_notas_debito NA ";
			$sql .= "				LEFT JOIN ( SELECT	SUM(valor) AS valor,";
			$sql .= "														tmp_nota_debito_id ";
			$sql .= "										FROM		tmp_notas_debito_detalle_conceptos ";
			$sql .= "										GROUP BY 2 ";
			$sql .= "									) AS NC ";
			$sql .= "				ON(	NC.tmp_nota_debito_id = NA.tmp_nota_debito_id ) ";
			$sql .= "WHERE	NA.empresa_id ='".$empresa."' ";
			$sql .= "AND		NA.usuario_id = ".$usuario." ";
			$sql .= "AND		NA.tercero_id = '".$datos['tercero_id']."' ";
			$sql .= "AND		NA.tipo_id_tercero = '".$datos['tercero_tipo']."' ";
			$sql .= "ORDER BY 2 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
      while(!$rst->EOF)
      {
      	$notas[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
      	$rst->MoveNext();
      }
            
      $rst->Close();			

			return $notas;
		}
		/**********************************************************************************
		* Funcion donde se buscan las Notas Credito Temporales
		* 
		* @return array 
		***********************************************************************************/
		function ObtenerNotasDebitoCerrada($empresa,$off,$datos)
		{			
			$sql .= "SELECT TO_CHAR(NA.fecha_registro,'DD /MM /YYYY') AS fecha,";
			$sql .= "				NA.nota_debito_id,";
			$sql .= "				NA.prefijo,";
			$sql .= "				NA.prefijo_factura,";
			$sql .= "				NA.factura_fiscal,";
			$sql .= "				TE.nombre_tercero,";
			$sql .= "				COALESCE(NC.valor,0) AS conceptos ";
			
			$where .= "FROM		notas_debito NA, ";
			$where .= "				( SELECT	SUM(valor) AS valor,";
			$where .= "									nota_debito_id, ";
			$where .= "									prefijo ";
			$where .= "					FROM		notas_debito_detalle_conceptos ";
			$where .= "					GROUP BY 2,3 ";
			$where .= "				) AS NC, ";
			$where .= "				terceros TE ";
			$where .= "WHERE	NA.empresa_id ='".$empresa."' ";
			$where .= "AND		NA.tercero_id = TE.tercero_id ";
			$where .= "AND		NA.tipo_id_tercero = TE.tipo_id_tercero ";
			$where .= "AND		NA.empresa_id = '".$empresa."' ";
			$where .= "AND		NC.nota_debito_id = NA.nota_debito_id ";
			$where .= "AND		NC.prefijo = NA.prefijo ";
			$where .= "AND		NA.estado = '1' ";
			
			if($datos['Nota'])
				$where .= "AND		NA.nota_debito_id = ".$datos['Nota']." ";
			
			if($datos['Numero'])
			{
				$where .= "AND		NA.factura_fiscal = ".$datos['Numero']." ";
				$where .= "AND		NA.prefijo_factura = '".$datos['Prefijo']."' ";
			}
			
			$this->requestoff = $off;
			if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $where"))
				return false;
				
			$sql .= $where;
			$sql .= "ORDER BY 3,2 DESC ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
      while(!$rst->EOF)
      {
      	$notas[$rst->fields[5]][] = $rst->GetRowAssoc($ToUpper = false);
      	$rst->MoveNext();
      }
            
      $rst->Close();			

			return $notas;
		}
		/**********************************************************************************
		* Funcion donde se buscan las Notas De Ajuste temporales 
		* 
		* @return array 
		***********************************************************************************/
		function ObtenerNotasCreditos($usuario,$datos,$empresa)
		{			
			$sql .= "SELECT TO_CHAR(NA.fecha_registro,'DD /MM /YYYY') AS fecha,";
			$sql .= "				NA.tmp_nota_credito_id,";
			$sql .= "				NA.prefijo_factura,";
			$sql .= "				NA.factura_fiscal,";
			$sql .= "				COALESCE(NC.valor,0) AS conceptos ";
			$sql .= "FROM		tmp_notas_credito NA ";
			$sql .= "				LEFT JOIN ( SELECT	SUM(valor) AS valor,";
			$sql .= "														tmp_nota_credito_id ";
			$sql .= "										FROM		tmp_notas_credito_detalle_conceptos ";
			$sql .= "										GROUP BY 2 ";
			$sql .= "									) AS NC ";
			$sql .= "				ON(	NC.tmp_nota_credito_id = NA.tmp_nota_credito_id ) ";
			$sql .= "WHERE	NA.empresa_id ='".$empresa."' ";
			$sql .= "AND		NA.usuario_id = ".$usuario." ";
			$sql .= "AND		NA.tercero_id = '".$datos['tercero_id']."' ";
			$sql .= "AND		NA.tipo_id_tercero = '".$datos['tercero_tipo']."' ";
			$sql .= "ORDER BY 2 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
      while(!$rst->EOF)
      {
      	$notas[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
      	$rst->MoveNext();
      }
            
      $rst->Close();			

			return $notas;
		}
		/**********************************************************************************
		* Funcion donde se ingresa una nota a la base de datos
		***********************************************************************************/
		function CrearNotaDebito($empresa,$usuario,$observacion,$prefijo,$factura,$datos,$auditor)
		{
			$sql .= "INSERT INTO tmp_notas_debito(";
			$sql .= "		tmp_nota_debito_id,";
			$sql .= "		empresa_id,";
			$sql .= "		prefijo_factura,";
			$sql .= "		factura_fiscal, ";
			$sql .= "		usuario_id, ";
			$sql .= "		fecha_registro, ";
			$sql .= "		tipo_id_tercero, ";
			$sql .= "		tercero_id, ";
			$sql .= "		observacion, ";
			$sql .= "		auditor_id ";
			$sql .= "		) ";
			$sql .= "VALUES(";
			$sql .= "		(SELECT COALESCE(MAX(tmp_nota_debito_id),0)+1 FROM tmp_notas_debito ),";
			$sql .= "		'".$empresa."',";
			$sql .= "		'".$prefijo."',";
			$sql .= "		 ".$factura.",";
			$sql .= "		 ".$usuario.", ";
			$sql .= "		 NOW(), ";
			$sql .= "		'".$datos['tercero_tipo']."', ";
			$sql .= "		'".$datos['tercero_id']."', ";
			$sql .= "		'".$observacion."', ";
			$sql .= "		 ".$auditor." ";
			$sql .= ") ";
			
			if(!$this->ConexionBaseDatos($sql)) return false;
			return true;
		}
		/************************************************************************************
		* Funcion donde se elimina de las tablas temporales los registro de una nota dada 
		*************************************************************************************/
		function EliminarNotaDC($id,$op)
		{
			$sql .= "DELETE FROM tmp_notas_debito_detalle_conceptos ";
			$sql .= "WHERE	tmp_nota_debito_id  = ".$id."; ";
			$sql .= "DELETE FROM tmp_notas_debito ";
			$sql .= "WHERE	tmp_nota_debito_id = ".$id."; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			if(!$op) $this->frmError['MensajeError'] = "<b class=\"normal_10AN\">LA NOTA DE AJUSTE SE HA ELIMINADO</b>";
			
			return true;
		}
		/************************************************************************************
		* Funcion donde se elimina de las tablas temporales los registro de una nota dada 
		*************************************************************************************/
		function CerrarNotaDebito($id,$empresa,$doc)
		{
			$suma = 0;
			$nota = array();
			$conceptos = array();
			
			$sql .= "SELECT * ";
			$sql .= "FROM 	tmp_notas_debito_detalle_conceptos ";
			$sql .= "WHERE	tmp_nota_debito_id  = ".$id." ";
			$sql .= "AND		empresa_id = '".$empresa."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			$i=0;
			while(!$rst->EOF)
      {
      	$conceptos[$i] = $rst->GetRowAssoc($ToUpper = false);
				$suma += $conceptos[$i]['valor']; 
      	$rst->MoveNext();
				$i++;
      }
      $rst->Close();
			
			$sql  = "SELECT * ";
			$sql .= "FROM 	tmp_notas_debito ";
			$sql .= "WHERE	tmp_nota_debito_id = ".$id." ";
			$sql .= "AND		empresa_id = '".$empresa."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			while(!$rst->EOF)
      {
      	$nota = $rst->GetRowAssoc($ToUpper = false);				
      	$rst->MoveNext();
      }
      $rst->Close();
			
			if(!$this->CrearNotaDebitoReal($nota,$conceptos,$suma,$doc,$empresa)) return false;
			
			if(!$this->EliminarNotaDC($id,1)) return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se crea en las tablas reales donde se guardan las notas debito
		***********************************************************************************/
		function CrearNotaDebitoReal($nota,$conceptos,$valornota,$documento,$empresa)
		{
			$this->ConexionTransaccion();
			
			$sql = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE ";//Bloqueo de tabla 
			if(!$rst = $this->ConexionTransaccion($sql,'1')) return false;

			$sql  = "SELECT prefijo,numeracion FROM documentos ";
			$sql .= "WHERE documento_id = ".$documento." AND empresa_id = '".$empresa."' ";
					
			if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
			
			$numer = array();
			while(!$rst->EOF)
      {
      	$numer = $rst->GetRowAssoc($ToUpper = false);				
      	$rst->MoveNext();
      }

			$auditor = "NULL";
			if($nota['auditor_id'])  $auditor = $nota['auditor_id'];

			$sql  = "INSERT INTO  notas_debito( ";
			$sql .= "				empresa_id,";
			$sql .= "				prefijo,";
			$sql .= "				nota_debito_id,";
			$sql .= "				prefijo_factura,";
			$sql .= "				factura_fiscal,";
			$sql .= "				valor_nota,";
			$sql .= "				fecha_registro, ";
			$sql .= "				usuario_id, ";
			$sql .= "				observacion, ";
			$sql .= "				tipo_id_tercero, ";
			$sql .= "				tercero_id, ";
			$sql .= "				documento_id, ";
			$sql .= "				auditor_id) ";
			$sql .= "VALUES (";
			$sql .= "		'".$nota['empresa_id']."', ";
			$sql .= "		'".$numer['prefijo']."', ";
			$sql .= "		 ".$numer['numeracion'].", ";
			$sql .= "		'".$nota['prefijo_factura']."', ";
			$sql .= "		 ".$nota['factura_fiscal'].", ";
			$sql .= "		 ".$valornota.", ";
			$sql .= "		'".$nota['fecha_registro']."', ";
			$sql .= "		 ".$nota['usuario_id'].", ";
			$sql .= "		'".$nota['observacion']."', ";
			$sql .= "		'".$nota['tipo_id_tercero']."', ";
			$sql .= "		'".$nota['tercero_id']."', ";
			$sql .= "		 ".$documento.", ";
			$sql .= "		 ".$auditor." ";
			$sql .= "		);";
			
			if(!$rst = $this->ConexionTransaccion($sql,'3')) return false;
			
			$sql = "";
			foreach($conceptos as $key => $Concept)
			{
				(!$Concept['departamento'])? $dep = "NULL": $dep = "'".$Concept['departamento']."'";
				(!$Concept['tercero_id'])? $trid = "NULL": $trid = "'".$Concept['tercero_id']."'";
				(!$Concept['tipo_id_tercero'])? $trdc = "NULL": $trdc = "'".$Concept['tipo_id_tercero']."'";
				
				$sql .= "INSERT INTO notas_debito_detalle_conceptos( ";
				$sql .= "		empresa_id,";
				$sql .= "		nota_debito_id, ";
				$sql .= "		prefijo,";
				$sql .= "		concepto_id, ";
				$sql .= "		valor,";
				$sql .= "		tercero_id, ";
				$sql .= "		tipo_id_tercero, ";
				$sql .= "		departamento, ";
				$sql .= "		naturaleza ";
				$sql .= "		) ";
				$sql .= "VALUES (";
				$sql .= "		'".$Concept['empresa_id']."', ";
				$sql .= "		 ".$numer['numeracion'].", ";
				$sql .= "		'".$numer['prefijo']."', ";
				$sql .= "		 ".$Concept['concepto_id'].", ";
				$sql .= "		 ".$Concept['valor'].", ";
				$sql .= "		 ".$trid.", ";
				$sql .= "		 ".$trdc.", ";
				$sql .= "		 ".$dep.", ";
				$sql .= "		'".$Concept['naturaleza']."' ";
				$sql .= "		);";				
			}
			
			if(!$rst = $this->ConexionTransaccion($sql,'4')) return false;
			
			$sql  = "UPDATE documentos ";
			$sql .= "SET 	numeracion = numeracion + 1 ";
			$sql .= "WHERE 	documento_id = ".$documento." AND empresa_id = '".$empresa."'; ";
			
			if(!$rst = $this->ConexionTransaccion($sql,'5')) return false;
			
			$this->dbconn->CommitTrans();
			$this->frmError['MensajeError'] = "<b class=\"normal_10AN\">LA NOTA DE AJUSTE SE HA CERRADO CORRECTAMENTE</b>~".$numer['prefijo']."~".$numer['numeracion'];
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se obtiene el valor abonado por las facturas para una nota de ajuste
		* 
		* @param int $codigo Codigo de la nota de ajuste temporal
		* @return array datos de las facturas con los respectivos valores
		***********************************************************************************/
		function ObtenerValorfacturas($codigo,$empresa)
		{
			$sql .= "SELECT	valor_abonado AS abono,";
			$sql .= " 			prefijo_factura,";
			$sql .= " 			factura_fiscal, ";
			$sql .= " 			tmp_nota_id ";
			$sql .= "FROM		tmp_notas_credito_ajuste_detalle_facturas ";
			$sql .= "WHERE	tmp_nota_ajuste_id = ".$codigo." ";
			$sql .= "AND		empresa_id = '".$empresa."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      while(!$rst->EOF)
      {
      	$valor[] = $rst->GetRowAssoc($ToUpper = false);				
      	$rst->MoveNext();
      }
      $rst->Close();
      
      return $valor;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerValorNotaDebito($prefijo,$factura,$empresa)
		{
			$sql .= "	SELECT 	SUM(valor_nota) AS abono
								FROM		notas_debito
								WHERE		prefijo_factura = '".$prefijo."'
								AND			factura_fiscal = ".$factura."
								AND			empresa_id = '".$empresa."' 
								AND			estado = '1' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $datos;
		}
		/**********************************************************************************
		* Funcion donde se obtienen las facturas que no se han pagado
		***********************************************************************************/
		function ObtenerFacturas($prefijo,$factura,$empresa,$datos,$offset)
		{						
			$sql  = "SELECT FF.prefijo,";
			$sql .= "				FF.factura_fiscal,";
			$sql .= "				FF.total_factura, ";
			$sql .= "				FF.saldo, ";
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS fecha ";
			$where .= "FROM fac_facturas FF ";
			$where .= "WHERE 	FF.empresa_id = '".$empresa."' ";
			$where .= "AND	 	FF.estado = '0' ";	
			$where .= "AND	 	FF.sw_calse_factura = '1' ";	
			$where .= "AND	 	FF.saldo > 0 ";
			$where .= "AND		FF.tercero_id = '".$datos['tercero_id']."' ";
			$where .= "AND		FF.tipo_id_tercero = '".$datos['tercero_tipo']."' ";
			
			if($prefijo)
				$where .= "AND	FF.prefijo = '".$prefijo."' ";
			
			if($factura)
				$where .= "AND	FF.factura_fiscal = ".$factura." ";
			
			$this->requestoff = $offset;
			
			if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $where",10))
				return false;
				
			$sql .= $where;
			$sql .= "ORDER BY 1,2 ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
				
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				
			while(!$rst->EOF)
			{
				$facturas[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $facturas;
		}
		/***
		* Funcion donde se obtienen los conceptos que pueden ser adicionados a un recibo de caja 
		* 
		* @return array datos de los conceptos de tesoreria 
		**/
		function ObtenerConceptos($tipo,$empresa)
		{
			$sql  = "SELECT concepto_id,";
			$sql .= "				sw_naturaleza, ";
			$sql .= "				descripcion, ";
			$sql .= "				sw_centro_costo, ";
			$sql .= "				sw_tercero ";
			$sql .= "FROM		notas_credito_ajuste_conceptos ";
			$sql .= "WHERE 	empresa_id ='".$empresa."' ";
			$sql .= "AND		sw_activo = '1' ";
			$sql .= "AND		sw_naturaleza = '".$tipo."' ";
			$sql .= "ORDER BY descripcion ";
		
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$conceptos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $conceptos;
		}
		/************************************************************************************
		* Funcion donde se obtienen los departamentos, de la base de dartos
		* 
		* @return array
		*************************************************************************************/
		function ObtenerDepartamentos($empresa)
		{
			$sql .= "SELECT	departamento,";
			$sql .= "				descripcion ";
			$sql .= "FROM		departamentos ";
			$sql .= "WHERE	empresa_id = '".$empresa."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      while(!$rst->EOF)
      {
      	$departamentos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }

      $rst->Close();	
			
			return $departamentos;			
		}
		/**********************************************************************************
		* Funcion en la que se adicionan los conceptos a la base de datos
		* 
		* @return boolean 
		***********************************************************************************/
		function AdicionarConceptosDebito($concepto,$valor,$natural,$departamento,$trid,$trdc,$empresa,$id)
		{						
			$sql .= "INSERT INTO tmp_notas_debito_detalle_conceptos(";
			$sql .= "		empresa_id,";
			$sql .= "		tmp_nota_debito_id, ";
			$sql .= "		concepto_id, ";
			$sql .= "		valor, ";
			$sql .= "		tercero_id,  ";
			$sql .= "		tipo_id_tercero, "; 
			$sql .= "		departamento, "; 
			$sql .= "		naturaleza) ";
			$sql .= "VALUES (";
			$sql .= "		'".$empresa."',";
			$sql .= "		'".$id."',";
			$sql .= "		'".$concepto."',";
			$sql .= "		 ".$valor.",";
			$sql .= "		 ".$trid.",";
			$sql .= "		 ".$trdc.", ";
			$sql .= "		 ".$departamento.", ";
			$sql .= "		'".$natural."' ";
			$sql .= "		)";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			return true;
		}
		/**********************************************************************************
		* Funcion que permite eliminar conceptos debito
		* 
		* @return boolean 
		***********************************************************************************/
		function EliminarConceptosDebito($tmpid,$conceptoid,$id)
		{						
			$sql .= "DELETE FROM tmp_notas_debito_detalle_conceptos ";
			$sql .= "WHERE	tmp_nd_concepto_id = ".$tmpid." ";
			$sql .= "AND		tmp_nota_debito_id = ".$id." ";
			$sql .= "AND		concepto_id = ".$conceptoid."; ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			return true;
		}
		/***
		* Funcion donde se obtiene el valor de los conceptos que pertenecen a un recibo de caja 
		* 
		* @return array datos de los conceptos 
		**/
		function ObtenerConceptosAdicionados($id,$empresa)
		{
			$sql .= "SELECT	TC.tmp_nd_concepto_id,";
			$sql .= " 			AC.descripcion, ";
			$sql .= " 			AC.concepto_id, ";
			$sql .= "				TC.valor, ";
			$sql .= "				COALESCE(DE.descripcion,'NO APLICA')||'/'||COALESCE(TC.tipo_id_tercero||' '||TC.tercero_id,'NINGUNO') AS departamento ";
			$sql .= "FROM 	notas_credito_ajuste_conceptos AC, ";
			$sql .= "				tmp_notas_debito_detalle_conceptos TC";
			$sql .= "				LEFT JOIN departamentos DE ";
			$sql .= "				ON(TC.departamento = DE.departamento) ";
			$sql .= "WHERE	TC.empresa_id = '".$empresa."' ";
			$sql .= "AND		TC.concepto_id = AC.concepto_id ";
			$sql .= "AND		TC.tmp_nota_debito_id = ".$id." ";
			$sql .= "ORDER BY 1";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$conceptos = array();
			while(!$rst->EOF)
			{
				$conceptos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			$this->frmError['MensajeError'] = "<b class=\"normal_10AN\">EL CONCEPTO SE HA ADICIONADO CORRECTAMENTE</b>";
			
			return $conceptos;
		}
		/***
		* Funcion donde se obtiene la informacion de la nota debito
		**/
		function ObtenerInformacionNotaDebito($nota,$empresa)
		{
			$sql .= "SELECT	observacion ";
			$sql .= "FROM		tmp_notas_debito "; 
			$sql .= "WHERE	tmp_nota_debito_id = ".$nota." ";
			$sql .= "AND		empresa_id = '".$empresa."' ";		
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$nota =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $nota;
		}
		/***
		* Funcion donde se actualiza la informacion de la nota debito
		**/
		function ActualizarInformacion($nota,$empresa,$observacion)
		{
			$sql .= "UPDATE	tmp_notas_debito "; 
			$sql .= "SET		observacion = '".$observacion."' ";
			$sql .= "WHERE	tmp_nota_debito_id = ".$nota." ";
			$sql .= "AND		empresa_id = '".$empresa."' ";		
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$nota =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $nota;
		}
		/**********************************************************************************
		* Funcion donde se obtiene la informacion de una nota debito cerrada 
		* 
		* @return array 
		***********************************************************************************/
		function ObtenerInformacionNotaDebitoCerrada($prefijo,$nota,$empresa)
		{			
			$sql .= "SELECT ND.prefijo_factura,";
			$sql .= "				ND.factura_fiscal,";
			$sql .= "				ND.valor_nota,";
			$sql .= "				ND.observacion,";
			$sql .= "				ND.tipo_id_tercero, ";
			$sql .= "				ND.tercero_id, ";
			$sql .= "				SU.nombre,";
			$sql .= "				SA.nombre AS auditor,";
			$sql .= "				TE.nombre_tercero,";
			$sql .= "				TO_CHAR(ND.fecha_registro,'DD /MM /YYYY') AS fecha, ";
			$sql .= "				FF.total_factura,";
			$sql .= "				FF.saldo,";
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD /MM /YYYY') AS fecha_factura ";
			$sql .= "FROM		notas_debito ND LEFT JOIN ";
			$sql .= "				system_usuarios SA ";
			$sql .= "				ON(	ND.auditor_id = SA.usuario_id), ";
			$sql .= "				system_usuarios SU, ";
			$sql .= "				terceros TE, ";
			$sql .= "				(	SELECT prefijo,factura_fiscal,empresa_id, total_factura,saldo,fecha_registro";
			$sql .= "					FROM fac_facturas  ";
			$sql .= "					WHERE empresa_id = '".$empresa."' ";
			$sql .= "					UNION ";
			$sql .= "					SELECT prefijo,factura_fiscal,empresa_id,  total_factura,saldo,fecha_registro";
			$sql .= "					FROM facturas_externas  ";
			$sql .= "					WHERE empresa_id = '".$empresa."' ";
			$sql .= "				) AS FF ";
			$sql .= "WHERE	ND.empresa_id ='".$empresa."' ";
			$sql .= "AND		ND.prefijo = '".$prefijo."' ";
			$sql .= "AND		ND.nota_debito_id = ".$nota." ";
			$sql .= "AND		ND.usuario_id = SU.usuario_id ";
			$sql .= "AND		ND.tipo_id_tercero = TE.tipo_id_tercero ";
			$sql .= "AND		ND.tercero_id =  TE.tercero_id ";
			$sql .= "AND		ND.prefijo_factura = FF.prefijo ";
			$sql .= "AND		ND.factura_fiscal = FF.factura_fiscal ";
			$sql .= "AND		ND.empresa_id = FF.empresa_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
      while(!$rst->EOF)
      {
      	$notas = $rst->GetRowAssoc($ToUpper = false);
      	$rst->MoveNext();
      }
            
      $rst->Close();			

			return $notas;
		}
		/***
		* Funcion donde se obtiene el valor de los conceptos que pertenecen a un recibo de caja 
		* 
		* @return array datos de los conceptos 
		**/
		function ObtenerConceptosNotaDebito($prefijo,$nota,$empresa)
		{
			$sql .= "SELECT	DISTINCT AC.descripcion, ";
			$sql .= "				TC.valor, ";
			$sql .= "				COALESCE(DE.descripcion,'NO APLICA')||'/'||COALESCE(TE.nombre_tercero,'NINGUNO') AS departamento ";
			$sql .= "FROM 	notas_credito_ajuste_conceptos AC, ";
			$sql .= "				notas_debito_detalle_conceptos TC ";
			$sql .= "				LEFT JOIN departamentos DE ";
			$sql .= "				ON(TC.departamento = DE.departamento) ";
			$sql .= "				LEFT JOIN terceros TE ";
			$sql .= "				ON(	TC.tercero_id = TE.tercero_id AND ";
			$sql .= "						TC.tipo_id_tercero = TE.tipo_id_tercero) ";
			$sql .= "WHERE	TC.empresa_id = '".$empresa."' ";
			$sql .= "AND		TC.concepto_id = AC.concepto_id ";
			$sql .= "AND		TC.prefijo = '".$prefijo."' ";
			$sql .= "AND		TC.nota_debito_id = ".$nota." ";
			$sql .= "ORDER BY 1";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$conceptos = array();
			while(!$rst->EOF)
			{
				$conceptos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $conceptos;
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
		/************************************************************************************
		* Funcion donde se obtienen los los prefijos de las facturas para agregarlos al 
		* buscador
		*
		* @return array datos de las facturas 
		*************************************************************************************/
		function ObtenerPrefijosDebito($empresa,$tipo,$tid)
		{		
			$sql  = "SELECT DISTINCT FF.prefijo ";
			$sql .= "FROM		view_fac_facturas FF ";
			$sql .= "WHERE 	FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		FF.estado = '0' ";	
			$sql .= "AND		FF.saldo > 0 ";		
			$sql .= "AND		FF.tipo_id_tercero = '".$tipo."' ";		
			$sql .= "AND		FF.tercero_id = '".$tid."' ";		

			
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
		/***
		* Funcion que permite obtener la poliza soat de un ingreso soat
		* @params int $ingreso Numero de ingreso
		* @params int $ingreso Numero de ingreso
		* @params int $ingreso Numero de ingreso
		*
		* @return array datos de la poliza
		**/
		function ObtenerInformacionSoat($prefijo,$factura,$empresa)
		{
			$sql .= "SELECT	SP.poliza ";
			$sql .= "FROM		ingresos_soat IF, "; 
			$sql .= "				soat_eventos SE, "; 
			$sql .= "				soat_polizas SP, "; 
			$sql .= "				fac_facturas_cuentas FC, "; 
			$sql .= "				cuentas CU "; 
			//$sql .= "				ingresos IG "; 
			$sql .= "WHERE	FC.prefijo = '".$prefijo."' ";				
			$sql .= "AND		FC.factura_fiscal = ".$factura." ";				
			$sql .= "AND		FC.empresa_id = '".$empresa."' ";				
			$sql .= "AND		FC.numerodecuenta = CU.numerodecuenta ";				
			$sql .= "AND		CU.ingreso = IF.ingreso ";				
			
			$sql .= "AND		SE.evento = IF.evento ";	
			$sql .= "AND		SE.poliza = SP.poliza ";	
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			if(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
		/********************************************************************************** 
		* Funcion donde se toman de la base de datos los auditores internos registrados
		* 
		* @return array datos de las clasificaciones de las glosas 
		***********************************************************************************/
		function ObtenerAuditoresInternos()
		{
			$sql  = "SELECT	U.usuario_id,";
			$sql .= "				U.nombre ";
			$sql .= "FROM		system_usuarios U,";
			$sql .= "				auditores_internos A ";
			$sql .= "WHERE	U.usuario_id = A.usuario_id ";
			$sql .= "AND		A.estado = '1' ";
						
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerPrefijos($emp)
		{
			$sql .= "SELECT DISTINCT prefijo_factura ";
			$sql .= "FROM		notas_debito ";
			$sql .= "WHERE 	empresa_id = '".$emp."' ";
			$sql .= "AND 		estado = '1' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerMotivosAnulacion()
		{
			$sql .= "SELECT motivo_id, ";
			$sql .= "				motivo_descripcion ";
			$sql .= "FROM		motivos_anulacion_notas_debito ";
			$sql .= "WHERE 	sw_activo = '1' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $datos;
		}		
		/**********************************************************************************
		*
		***********************************************************************************/
		function AnularNotaDebito($prefijo,$nota,$emp,$motivo,$observacion,$uid)
		{
			$sql .= "INSERT INTO notas_debito_auditoria_anulaciones ";
			$sql .= "		(	empresa_id,";
			$sql .= "			prefijo,";
			$sql .= "			nota_debito_id,";
			$sql .= "			observacion,";
			$sql .= "			motivo_anulacion_id,";
			$sql .= "			usuario_id,";
			$sql .= "			fecha_registro ";
			$sql .= "		) ";
			$sql .= "VALUES	( ";
			$sql .= "		'".$emp."', ";
			$sql .= "		'".$prefijo."', ";
			$sql .= "		 ".$nota.", ";
			$sql .= "		'".$observacion."', ";
			$sql .= "		 ".$motivo.", ";
			$sql .= "		 ".$uid.", ";
			$sql .= "			NOW() ";
			$sql .= "		); ";
			$sql .= "UPDATE	notas_debito ";
			$sql .= "SET		estado = '0' ";
			$sql .= "WHERE 	prefijo  = '".$prefijo."' ";
			$sql .= "AND  	nota_debito_id = '".$nota."' ";
			$sql .= "AND  	empresa_id = '".$emp."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
 		
	 		return true;
		}
		/***
		* Funcion que permite obtener el numero de cuenta y el paciente asociado a la cuenta
		* de una factura
		* @params char $prefijo Prefijo de la factura
		* @params int $factura Numero de la factura
		* @params char $empresa Id de la empresa
		*
		* @returns array informacion del paciente y la cuenta, agrupados por numero de cuenta
		**/
		function ObtenerInformacionCuentas($prefijo,$factura,$empresa)
		{
			$sql .= "SELECT	CU.numerodecuenta, ";
			$sql .= "				CU.ingreso, ";
			$sql .= "				PA.tipo_id_paciente, ";
			$sql .= "				PA.paciente_id, ";
			$sql .= "				PA.primer_nombre||' '||PA.segundo_nombre AS nombre, ";
			$sql .= "				PA.primer_apellido||' '||PA.segundo_apellido AS apellido ";
			$sql .= "FROM		cuentas CU, "; 
			$sql .= "				fac_facturas_cuentas FC, "; 
			$sql .= "				ingresos IG, "; 
			$sql .= "				pacientes PA "; 
			$sql .= "WHERE	FC.factura_fiscal = ".$factura." ";		
			$sql .= "AND		FC.prefijo = '".$prefijo."' ";		
			$sql .= "AND		FC.empresa_id = '".$empresa."' ";		
			$sql .= "AND		FC.numerodecuenta = CU.numerodecuenta ";		
			$sql .= "AND		CU.ingreso = IG.ingreso ";		
			$sql .= "AND		IG.paciente_id = PA.paciente_id ";		
			$sql .= "AND		IG.tipo_id_paciente = PA.tipo_id_paciente ";		
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[0]] =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
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
		/************************************************************************************ 
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*************************************************************************************/
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
			
			if($this->requestoff)
			{
				$this->paginaActual = intval($this->requestoff);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}		
			
			if(!$_REQUEST['registros'])
			{
				if(!$rst = $this->ConexionBaseDatos($consulta))
					return false;
	
				if(!$rst->EOF)
				{
					$this->conteo = $rst->fields[0];
					$rst->MoveNext();
				}
				$rst->Close();
			}
			else
			{
				$this->conteo = $_REQUEST['registros'];
			}
			return true;
		}
	}
?>