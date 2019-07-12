<?php
  /**
  * $Id: NotasAjuste.class.php,v 1.1.1.1 2009/09/11 20:36:46 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.1.1.1 $ 
	* 
	* @autor Hugo F  Manrique 
  */
	class NotasAjuste
	{
		function NotasAjuste(){}
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
			$sql .= "FROM		notas_credito_ajuste_detalle_facturas ";
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
		function ObtenerValoresFactura($prefijo,$factura,$empresa,$nota)
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
								AND			nota_credito_ajuste != ".$nota."
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
		* Funcion donde se obtiene la informacion de una nota credito cerrada 
		* 
		* @return array 
		***********************************************************************************/
		function ObtenerInformacionNotaAjusteCerrada($prefijo,$nota,$empresa)
		{			
			$sql .= "SELECT ND.prefijo_factura,";
			$sql .= "				ND.factura_fiscal,";
			$sql .= "				NC.valor AS valor_nota,";
			$sql .= "				NA.tipo_id_tercero, ";
			$sql .= "				NA.tercero_id, ";
			$sql .= "				SU.nombre,";
			$sql .= "				TE.nombre_tercero,";
			$sql .= "				TO_CHAR(NA.fecha_registro,'DD /MM /YYYY') AS fecha, ";
			$sql .= "				FF.total_factura,";
			$sql .= "				FF.saldo,";
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD /MM /YYYY') AS fecha_factura ";
			$sql .= "FROM		notas_credito_ajuste_detalle_facturas ND,";
			$sql .= "				notas_credito_ajuste_detalle_conceptos NC,";
			$sql .= "				notas_credito_ajuste NA LEFT JOIN ";
			$sql .= "				terceros TE ";
			$sql .= "				ON(	NA.tipo_id_tercero = TE.tipo_id_tercero ";
			$sql .= "						AND		NA.tercero_id =  TE.tercero_id), ";
			$sql .= "				system_usuarios SU, ";
			$sql .= "				(	SELECT 	saldo,";
			$sql .= "								 	total_factura, ";
			$sql .= "								 	factura_fiscal, ";
			$sql .= "									prefijo, ";
			$sql .= "									fecha_registro, ";
			$sql .= "								 	empresa_id ";
			$sql .= " 				FROM		fac_facturas ";
			$sql .= " 				WHERE		empresa_id = '".$empresa."' ";
			$sql .= "					UNION ";
			$sql .= "					SELECT 	saldo,";
			$sql .= "								 	total_factura, ";
			$sql .= "								 	factura_fiscal, ";
			$sql .= "									prefijo, ";
			$sql .= "									fecha_registro, ";			
			$sql .= "								 	empresa_id ";
			$sql .= " 				FROM		facturas_externas";
			$sql .= " 				WHERE		empresa_id = '".$empresa."' ";
			$sql .= "				) AS FF ";
			$sql .= "WHERE	NA.prefijo = '".$prefijo."' ";
			$sql .= "AND		NA.nota_credito_ajuste = ".$nota." ";
			$sql .= "AND		ND.prefijo_factura = FF.prefijo ";
			$sql .= "AND		ND.factura_fiscal = FF.factura_fiscal ";
			$sql .= "AND		ND.empresa_id = FF.empresa_id ";
			$sql .= "AND		NA.usuario_id = SU.usuario_id ";

			$sql .= "AND		ND.nota_credito_ajuste = NA.nota_credito_ajuste ";
			$sql .= "AND		ND.prefijo = NA.prefijo ";
			$sql .= "AND		NC.nota_credito_ajuste = NA.nota_credito_ajuste ";
			$sql .= "AND		NC.prefijo = NA.prefijo ";
			
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
		function ObtenerConceptosNotaAjuste($prefijo,$nota,$empresa)
		{
			$sql .= "SELECT	DISTINCT AC.descripcion, ";
			$sql .= "				TC.valor, ";
			$sql .= "				COALESCE(DE.descripcion,'NO APLICA')||'/'||COALESCE(TE.nombre_tercero,'NINGUNO') AS departamento ";
			$sql .= "FROM 	notas_credito_ajuste_conceptos AC, ";
			$sql .= "				notas_credito_ajuste_detalle_conceptos TC";
			$sql .= "				LEFT JOIN departamentos DE ";
			$sql .= "				ON(TC.departamento = DE.departamento) ";
			$sql .= "				LEFT JOIN terceros TE ";
			$sql .= "				ON(	TC.tercero_id = TE.tercero_id AND ";
			$sql .= "						TC.tipo_id_tercero = TE.tipo_id_tercero) ";
			$sql .= "WHERE	TC.empresa_id = '".$empresa."' ";
			$sql .= "AND		TC.concepto_id = AC.concepto_id ";
			$sql .= "AND		TC.prefijo = '".$prefijo."' ";
			$sql .= "AND		TC.nota_credito_ajuste = ".$nota." ";
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
		/***
		*
		**/
		function ObtenerNotasDeAjuste($empresa,$offset,$datos)
		{			
			$sql .= "SELECT NA.prefijo,";
			$sql .= "				NA.nota_credito_ajuste,";
			$sql .= "				NA.valor AS total_nota_ajuste,";
			$sql .= "				TO_CHAR(NA.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
			$sql .= "				NA.factura_fiscal, ";
			$sql .= "				NA.prefijo_factura, ";
			$sql .= "				SU.nombre ";
			$where .= "FROM		( ";
			$where .= "					SELECT	NA.prefijo, ";
			$where .= "									NA.nota_credito_ajuste, ";
			$where .= "									NC.valor, ";
			$where .= "									NA.usuario_id,"; 
			$where .= "									NA.fecha_registro, ";
			$where .= "									NF.factura_fiscal, ";
			$where .= "									NF.prefijo_factura ";
			$where .= "					FROM 		notas_credito_ajuste NA,"; 
			$where .= "									notas_credito_ajuste_detalle_facturas NF, "; 
			$where .= "									notas_credito_ajuste_detalle_conceptos NC "; 
			$where .= "					WHERE		NA.empresa_id = '".$empresa."' ";
			$where .= "					AND			NF.nota_credito_ajuste = NA.nota_credito_ajuste ";
			$where .= "					AND			NF.prefijo = NA.prefijo ";
			$where .= "					AND			NC.nota_credito_ajuste = NA.nota_credito_ajuste ";
			$where .= "					AND			NC.prefijo = NA.prefijo ";
			$where .= "				) AS NA, ";
			$where .= "				system_usuarios SU ";
			$where .= "WHERE 	SU.usuario_id = NA.usuario_id ";

			if($datos['Nota'])
				$where .= "AND		NA.nota_credito_ajuste = ".$datos['Nota']." ";
			
			if($datos['Numero'])
			{
				$where .= "AND		NA.factura_fiscal = ".$datos['Numero']." ";
				$where .= "AND		NA.prefijo_factura = '".$datos['Prefijo']."' ";
			}
		
			$this->requestoff = $offset;
			if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $where"))
				return false;			
			
			$sql .= $where;
			$sql .= "ORDER BY 2 DESC ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i=0;
			while (!$rst->EOF)
			{
				$recibos[$i]  = $rst->GetRowAssoc($ToUpper = false);			
				$rst->MoveNext();
				$i++;
		  }
			$rst->Close();
			
			return $recibos;
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