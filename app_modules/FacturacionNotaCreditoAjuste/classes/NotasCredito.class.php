<?php
  /**
  * $Id: NotasCredito.class.php,v 1.2 2010/03/12 18:41:36 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.2 $ 
	* 
	* @autor Hugo F  Manrique 
  */
	class NotasCredito
	{
		function NotasCredito(){}
		/**********************************************************************************
		* Funcion donde se buscan las Notas Credito Temporales
		* 
		* @return array 
		***********************************************************************************/
		function ObtenerNotasCreditos($usuario,$datos,$empresa)
		{			
			$sql .= "SELECT TO_CHAR(NA.fecha_registro,'DD /MM /YYYY') AS fecha,";
			$sql .= "				NA.tmp_nota_credito_id,";
			$sql .= "				NA.prefijo_factura,";
			$sql .= "				NA.factura_fiscal,";
			$sql .= "				FF.saldo,";
			$sql .= "				COALESCE(NC.valor,0) AS conceptos ";
			$sql .= "FROM		tmp_notas_credito NA ";
			$sql .= "				LEFT JOIN ( SELECT	SUM(valor) AS valor,";
			$sql .= "														tmp_nota_credito_id ";
			$sql .= "										FROM		tmp_notas_credito_detalle_conceptos ";
			$sql .= "										GROUP BY 2 ";
			$sql .= "									) AS NC ";
			$sql .= "				ON(	NC.tmp_nota_credito_id = NA.tmp_nota_credito_id ), ";
			$sql .= "				view_fac_facturas FF ";
			$sql .= "WHERE	NA.empresa_id ='".$empresa."' ";
			$sql .= "AND		NA.usuario_id = ".$usuario." ";
			$sql .= "AND		NA.tercero_id = '".$datos['tercero_id']."' ";
			$sql .= "AND		NA.tipo_id_tercero = '".$datos['tercero_tipo']."' ";
			$sql .= "AND		NA.prefijo_factura = FF.prefijo ";
			$sql .= "AND		NA.factura_fiscal = FF.factura_fiscal ";
			$sql .= "AND		NA.empresa_id = FF.empresa_id ";
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
		function ObtenerNotasCreditoCerrada($empresa,$off,$datos)
		{			
			$sql .= "SELECT TO_CHAR(NA.fecha_registro,'DD /MM /YYYY') AS fecha,";
			$sql .= "				NA.nota_credito_id,";
			$sql .= "				NA.prefijo,";
			$sql .= "				NA.prefijo_factura,";
			$sql .= "				NA.factura_fiscal,";
			$sql .= "				TE.nombre_tercero,";
			$sql .= "				COALESCE(NC.valor,0) AS conceptos ";
			
			$where .= "FROM		notas_credito NA ";
			$where .= "				LEFT JOIN ( SELECT	SUM(valor) AS valor,";
			$where .= "														nota_credito_id, ";
			$where .= "														prefijo ";
			$where .= "										FROM		notas_credito_detalle_conceptos ";
			$where .= "										GROUP BY 2,3 ";
			$where .= "									) AS NC ";
			$where .= "				ON(	NC.nota_credito_id = NA.nota_credito_id AND ";
			$where .= "						NC.prefijo = NA.prefijo ), ";
			$where .= "				terceros TE ";
			$where .= "WHERE	NA.empresa_id ='".$empresa."' ";
			$where .= "AND		NA.tercero_id = TE.tercero_id ";
			$where .= "AND		NA.tipo_id_tercero = TE.tipo_id_tercero ";
			$where .= "AND		NA.estado = '1' ";
			
			if($datos['Nota'])
				$where .= "AND		NA.nota_credito_id = ".$datos['Nota']." ";
			
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
		* Funcion donde se ingresa una nota a la base de datos
		***********************************************************************************/
		function CrearNotaCredito($empresa,$usuario,$observacion,$prefijo,$factura,$datos,$auditor)
		{
			$sql .= "INSERT INTO tmp_notas_credito(";
			$sql .= "		tmp_nota_credito_id,";
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
			$sql .= "		(SELECT COALESCE(MAX(tmp_nota_credito_id),0)+1 FROM tmp_notas_credito ),";
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
		function EliminarNotaDC($id,$op = null)
		{
			$sql .= "DELETE FROM tmp_notas_credito_detalle_conceptos ";
			$sql .= "WHERE	tmp_nota_credito_id  = ".$id."; ";
			$sql .= "DELETE FROM tmp_notas_credito ";
			$sql .= "WHERE	tmp_nota_credito_id = ".$id."; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			if($op == null) $this->frmError['MensajeError'] = "<b class=\"normal_10AN\">LA NOTA CREDITO SE HA ELIMINADO</b>";
			
			return true;
		}
		/************************************************************************************
		* Funcion donde se elimina de las tablas temporales los registro de una nota dada 
		*************************************************************************************/
		function CerrarNotaCredito($id,$empresa,$doc,$accion)
		{
			$suma = 0;
			$nota = array();
			$conceptos = array();
			
			$sql .= "SELECT * ";
			$sql .= "FROM 	tmp_notas_credito_detalle_conceptos ";
			$sql .= "WHERE	tmp_nota_credito_id  = ".$id." ";
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
			$sql .= "FROM 	tmp_notas_credito ";
			$sql .= "WHERE	tmp_nota_credito_id = ".$id." ";
			$sql .= "AND		empresa_id = '".$empresa."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			while(!$rst->EOF)
      {
      	$nota = $rst->GetRowAssoc($ToUpper = false);				
      	$rst->MoveNext();
      }
      $rst->Close();
			
			if(!$this->CrearNotaCreditoReal($nota,$conceptos,$suma,$doc,$empresa,$accion)) return false;
			
			if(!$this->EliminarNotaDC($id,1)) return false;
			
			return true;
		}
		/**
		* Funcion donde se realiza el proceso de cierre de una nota credito para
		* una factura realizada por el sistema
		*
		* @param array $nota Datos de la nota a cerrar
		* @param array $conceptos Datos de los conceptos pertenecientes a la nota credito
		* @param float $valornota Valor de la nota
		* @param int $documento Documento id del prefijo aignado a las notas credito
		* @param string $empresa Identificador de la empresa
		*
		* @return boolean Verdadero si todo concluyo de manera correcta o falso en otro caso
		*/
		function CrearNotaCreditoReal($nota,$conceptos,$valornota,$documento,$empresa,$accion)
		{
			$this->ConexionTransaccion();
			
			$sql = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE ";//Bloqueo de tabla 
			if(!$rst = $this->ConexionTransaccion($sql,'1')) return false;

			$sql  = "SELECT prefijo,numeracion FROM documentos ";
			$sql .= "WHERE documento_id = ".$documento." AND empresa_id = '".$empresa."' ";
					
			if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
			
			$numer = array();
			if(!$rst->EOF)
      {
      	$numer = $rst->GetRowAssoc($ToUpper = false);				
      	$rst->MoveNext();
      }
			
			if(empty($numer))
			{
				$this->frmError['MensajeError'] = "NO SE HAN PARAMETRIZADO LOS VALORES DEL DOCUMENTO";
				return false;
			}
			
			$auditor = "NULL";
			if($nota['auditor_id'])  $auditor = $nota['auditor_id'];

			$sql  = "INSERT INTO  notas_credito( ";
			$sql .= "				empresa_id,";
			$sql .= "				prefijo,";
			$sql .= "				nota_credito_id,";
			$sql .= "				prefijo_factura,";
			$sql .= "				factura_fiscal,";
			$sql .= "				valor_nota,";
			$sql .= "				fecha_registro, ";
			$sql .= "				usuario_id, ";
			$sql .= "				observacion, ";
			$sql .= "				tipo_id_tercero, ";
			$sql .= "				tercero_id, ";
			$sql .= "				documento_id, ";
			$sql .= "				auditor_id,";
			$sql .= "				sw_anular_factura ";
      $sql .= "     ) ";
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
			$sql .= "		 ".$auditor.", ";
			$sql .= "		 '".(($accion)? "1":"0")."' ";
			$sql .= "		);";
			
			if(!$rst = $this->ConexionTransaccion($sql,'3')) return false;
			
			$sql = "";
			foreach($conceptos as $key => $Concept)
			{
				(!$Concept['departamento'])? $dep = "NULL": $dep = "'".$Concept['departamento']."'";
				(!$Concept['tercero_id'])? $trid = "NULL": $trid = "'".$Concept['tercero_id']."'";
				(!$Concept['tipo_id_tercero'])? $trdc = "NULL": $trdc = "'".$Concept['tipo_id_tercero']."'";
				
				$sql .= "INSERT INTO notas_credito_detalle_conceptos( ";
				$sql .= "		empresa_id,";
				$sql .= "		nota_credito_id, ";
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
      
      if($accion)
      {
        $sql  = "UPDATE cuentas ";
  			$sql .= "SET 	  estado = '".$accion."' ";
  			$sql .= "WHERE 	numerodecuenta IN ";
        $sql .= "       (";
        $sql .= "         SELECT numerodecuenta ";
        $sql .= "         FROM   fac_facturas_cuentas ";
        $sql .= "         WHERE  prefijo = '".$nota['prefijo_factura']."' ";
        $sql .= "		      AND    factura_fiscal = ".$nota['factura_fiscal']." ";
        $sql .= "		      AND    empresa_id = '".$empresa."' ";
        $sql .= "       ); ";
  			
  			if(!$rst = $this->ConexionTransaccion($sql,'6')) return false;        
			}
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
		function AdicionarConceptosCredito($concepto,$valor,$natural,$departamento,$trid,$trdc,$empresa,$id)
		{						
			$sql .= "INSERT INTO tmp_notas_credito_detalle_conceptos(";
			$sql .= "		empresa_id,";
			$sql .= "		tmp_nota_credito_id, ";
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
		* Funcion que permite eliminar conceptos credito
		* 
		* @return boolean 
		***********************************************************************************/
		function EliminarConceptosCredito($tmpid,$conceptoid,$id)
		{						
			$sql .= "DELETE FROM tmp_notas_credito_detalle_conceptos ";
			$sql .= "WHERE	tmp_nc_concepto_id = ".$tmpid." ";
			$sql .= "AND		tmp_nota_credito_id = ".$id." ";
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
			$sql .= "SELECT	TC.tmp_nc_concepto_id,";
			$sql .= " 			AC.descripcion, ";
			$sql .= " 			AC.concepto_id, ";
			$sql .= "				TC.valor, ";
			$sql .= "				COALESCE(DE.descripcion,'NO APLICA')||'/'||COALESCE(TC.tipo_id_tercero||' '||TC.tercero_id,'NINGUNO') AS departamento ";
			$sql .= "FROM 	notas_credito_ajuste_conceptos AC, ";
			$sql .= "				tmp_notas_credito_detalle_conceptos TC";
			$sql .= "				LEFT JOIN departamentos DE ";
			$sql .= "				ON(TC.departamento = DE.departamento) ";
			$sql .= "WHERE	TC.empresa_id = '".$empresa."' ";
			$sql .= "AND		TC.concepto_id = AC.concepto_id ";
			$sql .= "AND		TC.tmp_nota_credito_id = ".$id." ";
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
		* Funcion donde se obtiene la informacion de la nota credito
		**/
		function ObtenerInformacionNotaCredito($nota,$empresa)
		{
			$sql .= "SELECT	observacion ";
			$sql .= "FROM		tmp_notas_credito "; 
			$sql .= "WHERE	tmp_nota_credito_id = ".$nota." ";
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
		* Funcion donde se actualiza la informacion de la nota credito
		**/
		function ActualizarInformacion($nota,$empresa,$observacion)
		{
			$sql .= "UPDATE	tmp_notas_credito "; 
			$sql .= "SET		observacion = '".$observacion."' ";
			$sql .= "WHERE	tmp_nota_credito_id = ".$nota." ";
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
		* Funcion donde se obtiene la informacion de una nota credito cerrada 
		* 
		* @return array 
		***********************************************************************************/
		function ObtenerInformacionNotaCreditoCerrada($prefijo,$nota,$empresa)
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
			$sql .= "FROM		notas_credito ND LEFT JOIN ";
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
			$sql .= "AND		ND.nota_credito_id = ".$nota." ";
			$sql .= "AND		ND.prefijo_factura = FF.prefijo ";
			$sql .= "AND		ND.factura_fiscal = FF.factura_fiscal ";
			$sql .= "AND		ND.empresa_id = FF.empresa_id ";
			$sql .= "AND		ND.usuario_id = SU.usuario_id ";
			$sql .= "AND		ND.tipo_id_tercero = TE.tipo_id_tercero ";
			$sql .= "AND		ND.tercero_id =  TE.tercero_id ";
			
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
		function ObtenerConceptosNotaCredito($prefijo,$nota,$empresa)
		{
			$sql .= "SELECT	DISTINCT AC.descripcion, ";
			$sql .= "				TC.valor, ";
			$sql .= "				COALESCE(DE.descripcion,'NO APLICA')||'/'||COALESCE(TE.nombre_tercero,'NINGUNO') AS departamento ";
			$sql .= "FROM 	notas_credito_ajuste_conceptos AC, ";
			$sql .= "				notas_credito_detalle_conceptos TC";
			$sql .= "				LEFT JOIN departamentos DE ";
			$sql .= "				ON(TC.departamento = DE.departamento) ";
			$sql .= "				LEFT JOIN terceros TE ";
			$sql .= "				ON(	TC.tercero_id = TE.tercero_id AND ";
			$sql .= "						TC.tipo_id_tercero = TE.tipo_id_tercero) ";
			$sql .= "WHERE	TC.empresa_id = '".$empresa."' ";
			$sql .= "AND		TC.concepto_id = AC.concepto_id ";
			$sql .= "AND		TC.prefijo = '".$prefijo."' ";
			$sql .= "AND		TC.nota_credito_id = ".$nota." ";
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
		/************************************************************************************
		* Funcion donde se obtienen los los prefijos de las facturas para agregarlos al 
		* buscador
		*
		* @return array datos de las facturas 
		*************************************************************************************/
		function ObtenerPrefijosCredito($empresa,$tipo,$tid)
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
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;	
		}
				/************************************************************************************
		* Funcion donde se obtienen los los prefijos de las facturas para agregarlos al 
		* buscador
		*
		* @return array datos de las facturas 
		*************************************************************************************/
		function ObtenerPrefijosCreditoExternos($empresa)
		{		
			$sql  = "SELECT DISTINCT FF.prefijo ";
			$sql .= "FROM		facturas_externas FF ";
			$sql .= "WHERE 	FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		FF.estado = '0' ";	
			$sql .= "AND		FF.saldo > 0 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;	
		}
		/***
		* Funcion donde se obtiene el nombre de un usuario
		* @param int $usuario Identificacion del usuario
		**/
		function ObtenerInformacionUsuario($usuario)
		{
			$sql .= "SELECT	nombre ";
			$sql .= "FROM		system_usuarios "; 
			$sql .= "WHERE	usuario_id = ".$usuario." ";		
			
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
		/***
		* Funcion que permite obtener la poliza soat de un ingreso soat
		* @params int $ingreso Numero de ingreso
		*
		* @return array datos de la poliza
		**/
		function ObtenerInformacionSoat($ingreso)
		{
			$sql .= "SELECT	SP.poliza ";
			$sql .= "FROM		ingresos_soat IF, "; 
			$sql .= "				soat_eventos SE, "; 
			$sql .= "				soat_polizas SP "; 
			$sql .= "WHERE	IF.ingreso = ".$ingreso." ";				
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
					$this->frmError['MensajeError'] = "ERROR DB : " . $this->dbconn->ErrorMsg()." ".$sql;
					//echo "<b class=\"label\">Trasaccion: $num - ".$this->frmError['MensajeError']."</b>";
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
		*
		***********************************************************************************/
		function ObtenerPrefijos($emp)
		{
			$sql .= "SELECT DISTINCT prefijo_factura ";
			$sql .= "FROM		notas_credito ";
			$sql .= "WHERE 	empresa_id = '".$emp."' ";
			
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
			$sql .= "FROM		motivos_anulacion_notas_credito ";
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
		function AnularNotaCredito($prefijo,$nota,$emp,$motivo,$observacion,$uid)
		{
			$sql .= "INSERT INTO notas_credito_auditoria_anulaciones ";
			$sql .= "		(	empresa_id,";
			$sql .= "			prefijo,";
			$sql .= "			nota_credito_id,";
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
			$sql .= "UPDATE	notas_credito ";
			$sql .= "SET		estado = '0' ";
			$sql .= "WHERE 	prefijo  = '".$prefijo."' ";
			$sql .= "AND  	nota_credito_id = '".$nota."' ";
			$sql .= "AND  	empresa_id = '".$emp."'; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
 		
	 		return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/		
		function ObtenerValoresFactura($prefijo,$factura,$empresa)
		{
			$sql .= "	SELECT 	COALESCE(SUM(valor_nota),0) AS abono
								FROM		notas_credito
								WHERE		prefijo_factura = '".$prefijo."'
								AND			factura_fiscal = ".$factura."
								AND			empresa_id = '".$empresa."'
								AND			estado = '1'
								UNION
								SELECT 	COALESCE(SUM(valor_abonado),0) AS abono
								FROM		notas_credito_ajuste_detalle_facturas
								WHERE		prefijo_factura = '".$prefijo."'
								AND			factura_fiscal = ".$factura."
								AND			empresa_id = '".$empresa."'
								UNION
								SELECT 	COALESCE(SUM(NG.abono),0) AS abono
								FROM		glosas G,
												(	SELECT 	glosa_id,
																	prefijo,
																	numero,
																	SUM(valor_aceptado) AS abono
													FROM 		notas_credito_glosas
													GROUP BY 1,2,3
													UNION
													SELECT 	glosa_id,
																	prefijo,
																	numero,
																	SUM(valor_aceptado) AS abono
													FROM 		notas_credito_glosas_detalle_cargos
													GROUP BY 1,2,3
													UNION
													SELECT 	glosa_id,
																	prefijo,
																	numero,
																	SUM(valor_aceptado) AS abono
													FROM		notas_credito_glosas_detalle_inventarios
													GROUP BY 1,2,3
												)	AS NG
								WHERE		G.prefijo = '".$prefijo."'
								AND			G.factura_fiscal = ".$factura."
								AND			G.empresa_id = '".$empresa."'
								AND			G.sw_estado != '0'
								AND			NG.abono > 0
								AND			NG.glosa_id = G.glosa_id ";
					
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
		function ObtenerFacturasExternas($prefijo,$factura,$empresa,$offset)
		{	
			$sql  = "SELECT FF.prefijo,";
			$sql .= "				FF.factura_fiscal,";
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS fecha, ";
			$sql .= "				FF.tipo_id_tercero,";
			$sql .= "				FF.tercero_id,";
			$sql .= "				FF.total_factura, ";
			$sql .= "				FF.saldo, ";
			$sql .= "				TE.nombre_tercero ";
			$whr .= "FROM 	facturas_externas FF ";
			$whr .= "		 		LEFT JOIN ";
			$whr .= "				(	SELECT	prefijo_factura, ";
			$whr .= "									factura_fiscal ";
			$whr .= "			 		FROM		tmp_notas_credito_ajuste_detalle_facturas ";
			$whr .= "				) AS TM ";
			$whr .= "		 		ON(	TM.prefijo_factura = FF.prefijo AND  ";
			$whr .= "						TM.factura_fiscal = FF.factura_fiscal ), ";
			$whr .= "				terceros TE ";
			$whr .= "WHERE 	FF.empresa_id = '".$empresa."' ";
			$whr .= "AND	 	FF.estado = '0' ";	
			$whr .= "AND	 	FF.saldo > 0 ";
			$whr .= "AND		TM.prefijo_factura IS NULL ";
			$whr .= "AND		TE.tercero_id = FF.tercero_id ";
			$whr .= "AND		TE.tipo_id_tercero = FF.tipo_id_tercero ";
			$whr .= "AND		FF.prefijo = '".$prefijo ."' ";
			if($factura)
				$whr .= "AND		FF.factura_fiscal = ".$factura." ";
						
			$sqlCont  = "SELECT COUNT(*) ".$whr;
			
			if($offset)	$this->requestoff = $offset;
			
			if(!$this->ProcesarSqlConteo($sqlCont))
				return false;
				
			$sql .= $whr;
			$sql .= "ORDER BY 1,2 ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			while(!$rst->EOF)
			{
				$facturas[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $facturas;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function CrearNotaAjusteBD($datos,$empresa,$observacion,$auditor)
		{
			$sql  = "		(SELECT COALESCE(MAX(tmp_nota_id),0)+1 FROM tmp_notas_credito_ajuste );";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$consecutivo = 1;
			if(!$rst->EOF)
			{
				$consecutivo = $rst->fields[0];
				$rst->MoveNext();
			}
			
			$sql  = "INSERT INTO tmp_notas_credito_ajuste(";
			$sql .= "		tmp_nota_id,";
			$sql .= "		empresa_id,";
			$sql .= "		total_nota_ajuste,";
			$sql .= "		fecha_registro,";
			$sql .= "		usuario_id, ";
			$sql .= "		tercero_id, ";
			$sql .= "		tipo_id_tercero, ";
			$sql .= "		auditor_id,	";
			$sql .= "		observacion	";
			$sql .= "		) ";
			$sql .= "VALUES(";
			$sql .= "		 ".$consecutivo." ,";
			$sql .= "		'".$empresa."',";
			$sql .= "		 0,";
			$sql .= "		 NOW(),";
			$sql .= "		".UserGetUID().", ";
			$sql .= "		'".$datos['tercero_id']."', ";
			$sql .= "		'".$datos['tipo_id_tercero']."', ";
			$sql .= "		".$auditor.", ";
			$sql .= "		'".$observacion."' ";
			$sql .= "); ";
			
			$sql .= "INSERT INTO tmp_notas_credito_ajuste_detalle_facturas( ";
			$sql .= "		empresa_id,";
			$sql .= "		prefijo_factura,";
			$sql .= "		factura_fiscal,";
			$sql .= "		valor_abonado,";
			$sql .= "		tmp_nota_ajuste_id)";
			$sql .= "VALUES (";	
			$sql .= "		'".$empresa."',";
			$sql .= "		'".$datos['prefijo']."',";
			$sql .= "		 ".$datos['factura_fiscal'].",";
			$sql .= "		 0,";
			$sql .= "		 ".$consecutivo." ";					
			$sql .= "		);";
			
			if(!$this->ConexionBaseDatos($sql))	return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion dondes se obtienen las notas de ajuste creadas
		* 
		* @return array Notas de ajuste creadas
		***********************************************************************************/
		function ObtenerNotasDeAjuste($empresa,$usuario,$tmp_id)
		{			
			$sql .= "SELECT TO_CHAR(NA.fecha_registro,'DD /MM /YYYY') AS fecha,";
			$sql .= "				NA.tmp_nota_id,";			
			$sql .= "				NA.observacion,";			
			$sql .= "				NF.prefijo_factura,";
			$sql .= "				NF.factura_fiscal,";
			$sql .= "				FF.saldo,";
			$sql .= "				FF.total_factura,";
			$sql .= "				TE.nombre_tercero,";
			$sql .= "				COALESCE(NC.valor,0) AS creditos ";
			$sql .= "FROM		tmp_notas_credito_ajuste NA ";
			$sql .= "				LEFT JOIN ";
			$sql .= "				( SELECT	SUM(valor) AS valor,";
			$sql .= "									tmp_nota_ajuste_id ";
			$sql .= "					FROM		tmp_notas_credito_ajuste_detalle_conceptos ";
			$sql .= "					WHERE 	naturaleza = 'D'";
			$sql .= "					GROUP BY tmp_nota_ajuste_id	";
			$sql .= "				) AS NC ";
			$sql .= "				ON(	NC.tmp_nota_ajuste_id = NA.tmp_nota_id ), ";
			$sql .= "				tmp_notas_credito_ajuste_detalle_facturas NF, ";
			$sql .= "				facturas_externas FF, ";
			$sql .= "				terceros TE ";
			$sql .= "WHERE	NA.empresa_id ='".$empresa."' ";
			$sql .= "AND		NA.tercero_id = TE.tercero_id ";
			$sql .= "AND		NA.tipo_id_tercero = TE.tipo_id_tercero ";
			$sql .= "AND		NA.usuario_id = ".UserGetUID()." ";
			$sql .= "AND		NF.tmp_nota_ajuste_id = NA.tmp_nota_id ";
			$sql .= "AND		FF.prefijo = NF.prefijo_factura ";
			$sql .= "AND		FF.factura_fiscal = NF.factura_fiscal  ";
			if($tmp_id)
				$sql .= "AND		NA.tmp_nota_id = ".$tmp_id." ";	
			
			$sql .= "ORDER BY NA.tmp_nota_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
      while(!$rst->EOF)
      {
      	$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
      }
            
      $rst->Close();			

			return $datos;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ModificarObservacion($tmp_id,$observacion)
		{
			$sql .= "UPDATE tmp_notas_credito_ajuste ";
			$sql .= "SET		observacion	= '".$observacion."' ";
			$sql .= "WHERE	tmp_nota_id = ".$tmp_id." ";
				
			if(!$this->ConexionBaseDatos($sql)) return false;
		
			return true;
		}
		/**********************************************************************************
		* 
		* @param 	integer
		* @return rst 
		************************************************************************************/
		function AddConceptosExternos($tmp_id,$concepto,$deptno,$tercero,$empresa,$valor)
		{
			$sql .= "INSERT INTO tmp_notas_credito_ajuste_detalle_conceptos(";
			$sql .= "		empresa_id,";
			$sql .= "		valor,";
			$sql .= "		naturaleza,";
			$sql .= "		tmp_nota_ajuste_id,";
			$sql .= "		concepto_id,";
			$sql .= "		departamento,";
			$sql .= "		tipo_id_tercero,";
			$sql .= "		tercero_id) ";
			$sql .= "VALUES (";
			$sql .= "		 '".$empresa."',";
			$sql .= "		 ".$valor.",";
			$sql .= "		 'D',";
			$sql .= "		 ".$tmp_id.",";
			$sql .= "		 ".$concepto.",";
			$sql .= "		 ".$deptno.", ";
			$sql .= "		 ".$tercero['tipo_id_tercero'].", ";
			$sql .= "		 ".$tercero['tercero_id']." ";
			$sql .= "		)";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			return true;
		}
		/***
		* Funcion donde se obtiene el valor de los conceptos que pertenecen a un recibo de caja 
		* 
		* @return array datos de los conceptos 
		**/
		function ObtenerConceptosExternosAdicionados($id,$empresa)
		{
			$sql .= "SELECT	TC.tmp_concepto_id,";
			$sql .= " 			AC.descripcion, ";
			$sql .= " 			AC.concepto_id, ";
			$sql .= "				TC.valor, ";
			$sql .= "				TC.tmp_nota_ajuste_id, ";
			$sql .= "				COALESCE(DE.descripcion,'NO APLICA')||'/'||COALESCE(TC.tipo_id_tercero||' '||TC.tercero_id,'NINGUNO') AS departamento ";
			$sql .= "FROM 	notas_credito_ajuste_conceptos AC, ";
			$sql .= "				tmp_notas_credito_ajuste_detalle_conceptos TC";
			$sql .= "				LEFT JOIN departamentos DE ";
			$sql .= "				ON(TC.departamento = DE.departamento) ";
			$sql .= "WHERE	TC.empresa_id = '".$empresa."' ";
			$sql .= "AND		TC.concepto_id = AC.concepto_id ";
			$sql .= "AND		TC.tmp_nota_ajuste_id = ".$id." ";
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
		/**********************************************************************************
		* Funcion en la que se eliminan los conceptos de la base datos, segun hayan sido 
		* seleccionados
		* 
		* @return boolean 
		***********************************************************************************/
		function EliminarConceptosExternos($tmp_id,$concepto)
		{
			$sql .= "DELETE FROM tmp_notas_credito_ajuste_detalle_conceptos ";
			$sql .= "WHERE	tmp_concepto_id = ".$tmp_id." ";
			$sql .= "AND		concepto_id = ".$concepto." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function EliminarNotaAjusteExterna($tmp_id)
		{
			$sql .= "DELETE FROM tmp_notas_credito_ajuste_detalle_conceptos ";
			$sql .= "WHERE	tmp_nota_ajuste_id  = ".$tmp_id."; ";
			$sql .= "DELETE FROM tmp_notas_credito_ajuste_detalle_facturas ";
			$sql .= "WHERE	tmp_nota_ajuste_id = ".$tmp_id."; ";
			$sql .= "DELETE FROM tmp_notas_credito_ajuste ";
			$sql .= "WHERE	tmp_nota_id = ".$tmp_id."; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function CerrarNotaAjusteExterna($tmp_id,$empresa)
		{
			$sql  = "SELECT SUM(valor) ";
			$sql .= "FROM 	tmp_notas_credito_ajuste_detalle_conceptos ";
			$sql .= "WHERE	tmp_nota_ajuste_id = ".$tmp_id."; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$total_nota = 0;
			while(!$rst->EOF)
			{
				$total_nota = $rst->fields[0];
				$rst->MoveNext();
		  }
			$rst->Close();
				
			$documento = ModuloGetVar('app','FacturacionNotaCreditoAjuste','documento_'.$empresa);
			$sql = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE ";//Bloqueo de tabla 
			
			$this->ConexionTransaccion();
			if(!$rst = $this->ConexionTransaccion($sql,'1')) return false;

			$sql  = "SELECT prefijo,numeracion FROM documentos ";
			$sql .= "WHERE documento_id = ".$documento." AND empresa_id = '".$empresa."' ";
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			while(!$rst->EOF)
			{
				$numeracion = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();	
			
			$sql  = "INSERT INTO  notas_credito_ajuste( ";
			$sql .= "				empresa_id,";
			$sql .= "				total_nota_ajuste,";
			$sql .= "				fecha_registro,";
			$sql .= "				usuario_id,";
			$sql .= "				tipo_id_tercero, ";
			$sql .= "				tercero_id, ";
			$sql .= "				prefijo,";
			$sql .= "				nota_credito_ajuste,";
			$sql .= "				documento_id, ";
			$sql .= "				estado, ";
			$sql .= "				observacion) ";
			$sql .= "SELECT empresa_id,";	
			$sql .= "				".$total_nota.", ";	
			$sql .= "				fecha_registro,	";
			$sql .= "				usuario_id, ";
			$sql .= "				tipo_id_tercero, ";
			$sql .= "				tercero_id, ";
			$sql .= "				'".$numeracion['prefijo']."', ";
			$sql .= "		 		".$numeracion['numeracion'].", ";			
			$sql .= "		 		".$documento.", ";			
			$sql .= "		 		'1', ";			
			$sql .= "				observacion ";
			$sql .= "FROM		tmp_notas_credito_ajuste ";
			$sql .= "WHERE	tmp_nota_id = ".$tmp_id."; ";
			
			if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
					
			$sql  = "INSERT INTO notas_credito_ajuste_detalle_conceptos( ";
			$sql .= "		empresa_id,";
			$sql .= "		valor,";
			$sql .= "		naturaleza,";
			$sql .= "		concepto_id, ";
			$sql .= "		prefijo,";
			$sql .= "		nota_credito_ajuste, ";
			$sql .= "		departamento, ";
			$sql .= "		tercero_id, ";
			$sql .= "		tipo_id_tercero ";
			$sql .= "		) ";
			$sql .= "SELECT empresa_id,";	
			$sql .= "				valor,";
			$sql .= "				naturaleza,";
			$sql .= "				concepto_id, ";
			$sql .= "				'".$numeracion['prefijo']."', ";
			$sql .= "		 		".$numeracion['numeracion'].", ";		
			$sql .= "				departamento, ";
			$sql .= "				tercero_id, ";
			$sql .= "				tipo_id_tercero ";
			$sql .= "FROM		tmp_notas_credito_ajuste_detalle_conceptos ";
			$sql .= "WHERE	tmp_nota_ajuste_id = ".$tmp_id." ";

			if(!$rst = $this->ConexionTransaccion($sql,'2')) return false;
			
			$sql  = "INSERT INTO notas_credito_ajuste_detalle_facturas( ";
			$sql .= "		empresa_id,";
			$sql .= "		prefijo_factura,";
			$sql .= "		factura_fiscal,";
			$sql .= "		valor_abonado, ";
			$sql .= "		prefijo,";
			$sql .= "		nota_credito_ajuste ";
			$sql .= "		) ";
			$sql .= "SELECT empresa_id,";	
			$sql .= "				prefijo_factura,";
			$sql .= "				factura_fiscal,";
			$sql .= "				".$total_nota.", ";	
			$sql .= "				'".$numeracion['prefijo']."', ";
			$sql .= "		 		".$numeracion['numeracion']." ";
			$sql .= "FROM		tmp_notas_credito_ajuste_detalle_facturas ";
			$sql .= "WHERE	tmp_nota_ajuste_id = ".$tmp_id." ";
			
			if(!$rst = $this->ConexionTransaccion($sql,'3')) return false;
			
			$sql  = "UPDATE documentos ";
			$sql .= "SET 	numeracion = numeracion + 1 ";
			$sql .= "WHERE 	documento_id = ".$documento." AND empresa_id = '".$empresa."'; ";
				
			$sql .= "DELETE FROM tmp_notas_credito_ajuste ";
			$sql .= "WHERE	tmp_nota_id = ".$tmp_id."; ";
			
			if(!$rst = $this->ConexionTransaccion($sql,'4')) return false;
			
			$this->dbconn->CommitTrans();
			
			return $numeracion;
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
				$this->frmError['MensajeError'] = "<b class=\"label_error\">ERROR DB : " . $dbconn->ErrorMsg()." $sql</b>";
				echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}
	}
?>