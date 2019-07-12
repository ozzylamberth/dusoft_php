<?php
  /******************************************************************************
  * $Id: app_Cartera_Notas.class.php,v 1.4 2007/08/09 19:44:11 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.4 $ 
	* 
	* @autor Hugo F  Manrique 
  ********************************************************************************/
	class app_Cartera_Notas
	{
		function app_Cartera_Notas(){}
		/***********************************************************************************
		* funcion donde se obtienen los datos de las notas de ajuste tanto temporales como 
		* las cerradas
		*
		* @params array $datos Filtros de la consulta
		* @params char 	$empresa Identificacion de la empresa
		* @return array datos de las notas
		************************************************************************************/
		function ObtenerNotasDeAjuste($datos,$empresa,$contar="1")
		{
			$f = explode("/",$datos['fecha_inicio']);
			$fechai = $f[2]."-".$f[1]."-".$f[0];

			$f = explode("/",$datos['fecha_fin']);
			$fechaf = $f[2]."-".$f[1]."-".$f[0];
			
			$sql  = "SELECT	NT.prefijo_nota,";
			$sql .= "				NT.nota_credito_ajuste,";
			$sql .= "				TO_CHAR(NT.fecha_registro,'DD/MM/YYYY') AS fecha_registro,";
			$sql .= "				NT.tipo,";
			$sql .= "				NT.abrv,";
			$sql .= "				NT.abono,";
			$sql .= "				NT.prefijo,";
			$sql .= "				NT.factura_fiscal,";
			$sql .= "				SU.nombre ";
			$where .= "FROM (	";
			$where .= "				SELECT 	AF.valor_nota AS abono, ";
			$where .= "								AF.prefijo AS prefijo_nota,";
			$where .= "								AF.nota_credito_id AS nota_credito_ajuste, ";
			$where .= "								AF.fecha_registro::date,";
			$where .= "								AF.usuario_id, ";
			$where .= "								'CREDITO' AS tipo, ";
			$where .= "								'NC' AS abrv, ";
			$where .= "								AF.prefijo_factura AS prefijo, ";
			$where .= "								AF.factura_fiscal ";
			$where .= "				FROM		notas_credito AF LEFT JOIN ";
			$where .= "								notas_credito_auditoria_anulaciones NA ";
			$where .= "								ON(		NA.empresa_id = Af.empresa_id AND ";
			$where .= "											NA.prefijo = AF.prefijo AND	";
			$where .= "											NA.nota_credito_id = AF.nota_credito_id ";
			if($datos['fecha_fin'])
				$where .= "											AND		NA.fecha_registro::date >= '".$fechai."' ";
			if($datos['fecha_fin'])
				$where .= "											AND		NA.fecha_registro::date <= '".$fechaf."' ";
			$where .= "									) ";

			$where .= "				WHERE		AF.empresa_id = '".$empresa."' ";
			$where .= "				AND			NA.prefijo IS NULL ";
			if($datos['prefijo'])
				$where .= "				AND		AF.prefijo = '".$datos['prefijo']."' ";
			if($datos['numero'])
				$where .= "				AND		AF.nota_credito_id = '".$datos['numero']."' ";
			
			if($datos['fecha_inicio'])
				$where .= "				AND		AF.fecha_registro::date >= '".$fechai."' ";
			if($datos['fecha_fin'])
				$where .= "				AND		AF.fecha_registro::date <= '".$fechaf."' ";
				
			$where .= "				UNION ALL ";
			$where .= "				SELECT 	AF.valor_nota AS abono, ";
			$where .= "								AF.prefijo AS prefijo_nota,";
			$where .= "								AF.nota_debito_id AS nota_credito_ajuste, ";
			$where .= "								AF.fecha_registro::date,";
			$where .= "								AF.usuario_id, ";
			$where .= "								'DEBITO' AS tipo, ";
			$where .= "								'ND' AS abrv, ";
			$where .= "								AF.prefijo_factura AS prefijo, ";
			$where .= "								AF.factura_fiscal ";
			$where .= "				FROM		notas_debito AF LEFT JOIN ";
			$where .= "								notas_debito_auditoria_anulaciones NA ";
			$where .= "								ON(		NA.empresa_id = Af.empresa_id AND ";
			$where .= "											NA.prefijo = AF.prefijo AND	";
			$where .= "											NA.nota_debito_id = AF.nota_debito_id ";
			
			if($datos['fecha_inicio'])
				$where .= "										AND		NA.fecha_registro::date >= '".$fechai."' ";
			if($datos['fecha_fin'])
				$where .= "										AND		NA.fecha_registro::date <= '".$fechaf."' ";
			
			$where .= "							) ";
			$where .= "				WHERE		AF.empresa_id = '".$empresa."' ";
			$where .= "				AND			NA.prefijo IS NULL ";
			if($datos['prefijo'])
				$where .= "				AND		AF.prefijo = '".$datos['prefijo']."' ";
			if($datos['numero'])
				$where .= "				AND		AF.nota_debito_id = '".$datos['numero']."' ";
			if($datos['fecha_inicio'])
				$where .= "				AND		AF.fecha_registro::date >= '".$fechai."' ";
			if($datos['fecha_fin'])
				$where .= "				AND		AF.fecha_registro::date <= '".$fechaf."' ";
			
			$where .= "				UNION ALL ";
			$where .= "				SELECT 	SUM(NF.valor_abonado) AS abono,";
			$where .= "								NF.prefijo AS prefijo_nota, ";
			$where .= "								NF.nota_credito_ajuste, ";
			$where .= "								NA.fecha_registro::date,";
			$where .= "								NA.usuario_id, ";
			$where .= "								'AJUSTE' AS tipo, ";
			$where .= "								'NA' AS abrv, ";
			$where .= "								'' AS prefijo, ";
			$where .= "								0 AS factura_fiscal ";
			$where .= "				FROM		notas_credito_ajuste_detalle_facturas AS NF, ";
			$where .= "								notas_credito_ajuste_detalle_conceptos AS NC, ";
			$where .= "								notas_credito_ajuste NA ";
			$where .= "				WHERE		NF.empresa_id = '".$empresa."' ";
			$where .= "				AND			NA.empresa_id = NC.empresa_id ";
			$where .= "				AND 		NA.nota_credito_ajuste = NC.nota_credito_ajuste ";
			$where .= "				AND			NA.prefijo = NC.prefijo ";
			$where .= "				AND			NA.empresa_id = NF.empresa_id ";
			$where .= "				AND 		NA.nota_credito_ajuste = NF.nota_credito_ajuste ";
			$where .= "				AND			NA.prefijo = NF.prefijo ";
			$where .= "				AND			NC.concepto_id != 246 ";
			if($datos['prefijo'])
				$where .= "				AND		NF.prefijo = '".$datos['prefijo']."' ";
			if($datos['numero'])
				$where .= "				AND		NF.nota_credito_ajuste = '".$datos['numero']."' ";
			if($datos['fecha_inicio'])
				$where .= "				AND		NA.fecha_registro::date >= '".$fechai."' ";
			if($datos['fecha_fin'])
				$where .= "				AND		NA.fecha_registro::date <= '".$fechaf."' ";	
				
			$where .= "				GROUP BY 2,3,4,5,6,7,8,9 ";
			$where .= "				UNION ALL ";
			$where .= "				SELECT 	NG.valor_aceptado AS abono, ";
			$where .= "								NG.prefijo AS prefijo_nota, ";
			$where .= "								NG.numero AS nota_credito_ajuste, ";
			$where .= "								NG.fecha_registro::date,";
			$where .= "								NG.usuario_id, ";
			$where .= "								'CREDITO GLOSAS' AS tipo, ";
			$where .= "								'NG' AS abrv, ";
			$where .= "								G.prefijo, ";
			$where .= "								G.factura_fiscal ";
			$where .= "				FROM		glosas G,";
			$where .= "								notas_credito_glosas NG ";
			$where .= "				WHERE		G.empresa_id = '".$empresa."' ";
			$where .= "				AND			NG.valor_aceptado > 0 ";
			$where .= "				AND			NG.glosa_id = G.glosa_id ";
			if($datos['prefijo'])
				$where .= "				AND		NG.prefijo = '".$datos['prefijo']."' ";
			if($datos['numero'])
				$where .= "				AND		NG.numero = '".$datos['numero']."' ";
			if($datos['fecha_inicio'])
				$where .= "				AND		NG.fecha_registro::date >= '".$fechai."' ";
			if($datos['fecha_fin'])
				$where .= "				AND		NG.fecha_registro::date <= '".$fechaf."' ";
				
			$where .= "			) AS NT, ";
			$where .= "			system_usuarios SU ";
			$where .= "WHERE 	NT.usuario_id = SU.usuario_id ";
			if($datos['tipo_nota'])
				$where .= "AND		NT.abrv = '".$datos['tipo_nota']."' ";
			
			if($contar == "1")
				$this->ProcesarSqlConteo("SELECT COUNT(*) $where");
			
			$sql .= "$where ";
			$sql .= "ORDER BY  tipo,prefijo_nota,nota_credito_ajuste ";
			
			if($contar == "1")
				$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			while (!$rst->EOF)
			{
				$notas[$rst->fields[3]][]  = $rst->GetRowAssoc($ToUpper = false);		
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $notas;
		}
		/********************************************************************************** 
		* Funcion domde se obtiene la informacion de la nota de ajuste individaual
		* 
		* @params array $datos Filtros de la consulta
		* @params char 	$empresa Identificacion de la empresa
		* @return array datos de tipo_id_terceros 
		***********************************************************************************/
		function ObtenerInformacionNota($datos,$empresa)
		{
			$sql .= "SELECT	NA.total_nota_ajuste, ";
			$sql .= "				TO_CHAR(NA.fecha_registro,'DD/MM/YYYY') AS registro, ";			
			$sql .= "				TE.nombre_tercero, ";
			$sql .= "				TE.tipo_id_tercero, ";
			$sql .= "				TE.tercero_id, ";
			$sql .= "				NA.observacion ";
			$sql .= "FROM		notas_credito_ajuste NA LEFT JOIN ";
			$sql .= "				terceros TE  ";
			$sql .= "				ON(	NA.tercero_id = TE.tercero_id AND ";
			$sql .= "						NA.tipo_id_tercero = TE.tipo_id_tercero) ";			
			$sql .= "WHERE	NA.nota_credito_ajuste = ".$datos['nota_credito_ajuste']." ";		
			$sql .= "AND		NA.prefijo = '".$datos['prefijo_nota']."' ";
			$sql .= "AND		NA.empresa_id = '".$empresa."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$notas = array();
			if(!$rst->EOF)
			{
				$notas =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $notas;
		}
		/***************************************************************************************
		* Funcion donde se obtiene el valor de los conceptos que pertenecen a una nota de ajuste 
		* 
		* @params array $datos Filtros de la consulta
		* @params char 	$empresa Identificacion de la empresa
		* @return array datos de los conceptos 
		****************************************************************************************/
		function ObtenerValorConceptosNA($datos,$empresa)
		{
			$sql .= "SELECT	COALESCE(NA.valor,0) AS valor,";
			$sql .= " 			NA.naturaleza, ";
			$sql .= " 			NAC.descripcion,  ";
			$sql .= " 			COALESCE(DE.descripcion, 'NO APLICA')||'/'||COALESCE(NA.tipo_id_tercero||' '||NA.tercero_id, 'NINGUNO') AS  departamento ";
			$sql .= "FROM 	notas_credito_ajuste_conceptos NAC, ";
			$sql .= "				notas_credito_ajuste_detalle_conceptos NA ";
			$sql .= "				LEFT JOIN departamentos DE ";
			$sql .= "				ON(NA.departamento = DE.departamento) ";
			$sql .= "WHERE	NA.nota_credito_ajuste = ".$datos['nota_credito_ajuste']." ";
			$sql .= "AND		NA.prefijo = '".$datos['prefijo_nota']."' ";
			$sql .= "AND		NA.empresa_id = '".$empresa."' ";
			$sql .= "AND		NA.concepto_id = NAC.concepto_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/************************************************************************************
		* Funcion donde se obtiene la informacion de las facturas que se han cruzado con una 
		* nota
		*
		* @params array $datos Filtros de la consulta
		* @params char 	$empresa Identificacion de la empresa
		* @return array datos de las facturas
		*************************************************************************************/
		function ObtenerFacturasCruzadasNA($datos,$empresa)
		{
			$sql  = "SELECT	FF.prefijo, ";
			$sql .= "				FF.factura_fiscal, ";
			$sql .= "				FF.total_factura, ";
			$sql .= "				FF.saldo, ";
			$sql .= "				FF.estado, ";
			$sql .= "				FF.registro, ";
			$sql .= "				FF.abono ";
			$sql .= "FROM ( ";
			$sql .= "				SELECT	FF.prefijo, ";
			$sql .= "								FF.factura_fiscal, ";
			$sql .= "								FF.total_factura, ";
			$sql .= "								FF.saldo, ";
			$sql .= "								FF.estado, ";
			$sql .= "								TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS registro, ";
			$sql .= "								SUM(NA.valor_abonado) AS abono ";
			$sql .= "				FROM		fac_facturas FF, ";
			$sql .= "								notas_credito_ajuste_detalle_facturas NA ";
			$sql .= "				WHERE	NA.nota_credito_ajuste = ".$datos['nota_credito_ajuste']." ";		
			$sql .= "				AND		NA.prefijo = '".$datos['prefijo_nota']."' ";
			$sql .= "				AND		NA.empresa_id = '".$empresa."' ";
			$sql .= "				AND		NA.prefijo_factura = FF.prefijo ";
			$sql .= "				AND		NA.factura_fiscal = FF.factura_fiscal ";
			$sql .= "				GROUP BY FF.prefijo,FF.factura_fiscal,FF.total_factura,FF.saldo,FF.estado,registro ";
			$sql .= "				UNION ALL ";
			$sql .= "				SELECT	FF.prefijo, ";
			$sql .= "								FF.factura_fiscal, ";
			$sql .= "								FF.total_factura, ";
			$sql .= "								FF.saldo, ";
			$sql .= "								FF.estado, ";
			$sql .= "								TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS registro, ";
			$sql .= "								SUM(NA.valor_abonado) AS abono ";
			$sql .= "				FROM		facturas_externas FF, ";
			$sql .= "								notas_credito_ajuste_detalle_facturas NA ";
			$sql .= "				WHERE	NA.nota_credito_ajuste = ".$datos['nota_credito_ajuste']." ";		
			$sql .= "				AND		NA.prefijo = '".$datos['prefijo_nota']."' ";
			$sql .= "				AND		NA.empresa_id = '".$empresa."' ";
			$sql .= "				AND		NA.prefijo_factura = FF.prefijo ";
			$sql .= "				AND		NA.factura_fiscal = FF.factura_fiscal ";
			$sql .= "				AND		FF.estado IN ('0'::bpchar,'1'::bpchar) ";
			$sql .= "				GROUP BY FF.prefijo,FF.factura_fiscal,FF.total_factura,FF.saldo,FF.estado,registro ";
			$sql .= "			) AS FF ";
			$sql .= "ORDER BY 6 DESC ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$total = 0;
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$total += $rst->fields[6];
				$rst->MoveNext();
		  }
			$rst->Close();
			if(sizeof($datos)>0) $datos[0]['total'] = $total;
			return $datos;
		}
		/********************************************************************************
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
		/********************************************************************************
		* Funcion donde se obtienen los departamentos en los cuales se puede hacer una 
		* internacion
		* 
		* @params char $empresa identificador de la empresa dueña de la cartera
		* @return array datos de los departamentos - departamento,descripcion
		*********************************************************************************/
		function ObtenerDepartamentos($empresa)
		{
			$sql .= "SELECT departamento,";
			$sql .= "				descripcion ";
			$sql .= "FROM 	departamentos ";
			$sql .= "WHERE	sw_internacion = '1' ";
			$sql .= "AND		empresa_id = '".$empresa."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$departamentos = array();
			while(!$rst->EOF)
			{
				$departamentos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $departamentos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerReporteVencidos($datos,$empresa)
		{
			$sql .= "SELECT	prefijo,";
			$sql .= "				factura_fiscal,";
			$sql .= "				saldo,";
			$sql .= "				envio_id,";
			$sql .= "				fecha_radicaion,";
			$sql .= "				fecha_registro ";
			$sql .= "FROM (";
			$sql .= "				SELECT	FF.prefijo,";
			$sql .= "								FF.factura_fiscal,";
			$sql .= "								FF.saldo,";
			$sql .= "								EN.envio_id,";
			$sql .= "								TO_CHAR(EN.fecha_radicacion,'DD/MM/YYYY') AS fecha_radicaion,";
			$sql .= "								TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
			$sql .= "					FROM	fac_facturas FF,";
			$sql .= "								envios_detalle ED,";
			$sql .= "								envios EN ";
			$sql .= "					WHERE	FF.empresa_id = '".$empresa."'  ";
			$sql .= "					AND		FF.tipo_id_tercero = '".$datos['tipo_id']."'  ";
			$sql .= "					AND   FF.tercero_id = '".$datos['tercero_id']."'  ";
			$sql .= "					AND		FF.empresa_id = ED.empresa_id  ";
			$sql .= "					AND		FF.factura_fiscal = ED.factura_fiscal  ";
			$sql .= "					AND		FF.prefijo = ED.prefijo  ";
			$sql .= "					AND   FF.saldo > 0";
			$sql .= "					AND		FF.sw_clase_factura = '1'::bpchar  ";
			$sql .= "					AND		FF.estado = '0'::bpchar  ";
			$sql .= "					AND		ED.envio_id = EN.envio_id  ";
			$sql .= "					AND		EN.sw_estado = '1'::bpchar  ";
			$sql .= "					AND 	EN.fecha_radicacion IS NOT NULL  ";
			$sql .= "					UNION ALL  ";
			$sql .= "					SELECT 	prefijo,";
			$sql .= "									factura_fiscal,";
			$sql .= "									saldo,";
			$sql .= "									numero_envio AS envio_id,";
			$sql .= "									'' AS fecha_radicacion,";
			$sql .= "									TO_CHAR(fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
			$sql .= "					FROM		facturas_externas  ";
			$sql .= "					WHERE		empresa_id = '".$empresa."'  ";
			$sql .= "					AND   	estado = '0'";
			$sql .= "					AND			tipo_id_tercero = '".$datos['tipo_id']."'  ";
			$sql .= "					AND   	tercero_id = '".$datos['tercero_id']."'";
			$sql .= "					AND   	saldo > 0";
			$sql .= "					AND			fecha_vencimiento IS NOT NULL";
			$sql .= "				) AS A ";
			$sql .= "ORDER BY prefijo,factura_fiscal ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/***********************************************************************************
		* funcion donde se obtienen los datos de las notas de ajuste tanto temporales como 
		* las cerradas
		*
		* @params array $datos Filtros de la consulta
		* @params char 	$empresa Identificacion de la empresa
		* @return array datos de las notas
		************************************************************************************/
		function ObtenerPrefijos($empresa)
		{
			$sql  = "SELECT	NT.prefijo ";
			$sql .= "FROM (	";
			$sql .= "				SELECT 	DISTINCT prefijo ";
			$sql .= "				FROM		notas_credito ";
			$sql .= "				WHERE		empresa_id = '".$empresa."' ";
			$sql .= "				UNION ALL ";
			$sql .= "				SELECT 	prefijo ";
			$sql .= "				FROM		notas_debito ";
			$sql .= "				WHERE		empresa_id = '".$empresa."' ";
			$sql .= "				UNION ALL ";
			$sql .= "				SELECT 	DISTINCT NF.prefijo ";
			$sql .= "				FROM		notas_credito_ajuste_detalle_facturas AS NF, ";
			$sql .= "								notas_credito_ajuste_detalle_conceptos AS NC, ";
			$sql .= "								notas_credito_ajuste NA ";
			$sql .= "				WHERE		NF.empresa_id = '".$empresa."' ";
			$sql .= "				AND			NA.empresa_id = NC.empresa_id ";
			$sql .= "				AND 		NA.nota_credito_ajuste = NC.nota_credito_ajuste ";
			$sql .= "				AND			NA.prefijo = NC.prefijo ";
			$sql .= "				AND			NA.empresa_id = NF.empresa_id ";
			$sql .= "				AND 		NA.nota_credito_ajuste = NF.nota_credito_ajuste ";
			$sql .= "				AND			NA.prefijo = NF.prefijo ";
			$sql .= "				AND			NC.concepto_id != 246 ";
			$sql .= "				UNION ALL ";
			$sql .= "				SELECT 	DISTINCT NG.prefijo  ";
			$sql .= "				FROM		glosas G,";
			$sql .= "								notas_credito_glosas NG ";
			$sql .= "				WHERE		G.empresa_id = '".$empresa."' ";
			$sql .= "				AND			G.sw_estado != '0' ";
			$sql .= "				AND			NG.valor_aceptado > 0 ";
			$sql .= "				AND			NG.glosa_id = G.glosa_id ) AS NT ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$notas = array();
			while (!$rst->EOF)
			{
				$notas[$rst->fields[0]]  = $rst->GetRowAssoc($ToUpper = false);		
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $notas;
		}
		/**************************************************************************************
		*
		**************************************************************************************/
		function ObtenerFacturas($datos,$empresa)
		{
			$fechaf = date("Y-m-d", mktime(0, 0, 0,(intval($datos['mes'])+1), 0,date("Y")));
			$fechai = date("Y-m-d", mktime(0, 0, 0,(intval($datos['mes'])), 1,date("Y")));
			
			$sql	= "SELECT FF.prefijo,";
			$sql .= "				FF.factura_fiscal,";
			$sql .= "				FF.total_factura,";
			$sql .= "				FF.retencion_fuente,";
			$sql .= "				TO_CHAR(FF.fecha_registro::date,'DD/MM/YYYY') AS fecha_registro		";
			$sql .= "FROM		( ";
			$sql .= "					SELECT 	FF.total_factura,";
			$sql .= "									FF.retencion_fuente, ";
			$sql .= "									FF.prefijo,";
			$sql .= "									FF.factura_fiscal,";
			$sql .= "									FF.fecha_registro";
			$sql .= "					FROM		fac_facturas FF ";
			$sql .= "					WHERE		FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "					AND			FF.empresa_id = '".$empresa."' ";
			$sql .= "					AND			FF.fecha_registro::date >= '".$fechai."' ";
			$sql .= "					AND			FF.fecha_registro::date <= '".$fechaf."' ";
			$sql .= "					UNION ALL ";
			$sql .= "					SELECT 	FF.total_factura,";
			$sql .= "									0 AS retencion_fuente, ";
			$sql .= "									FF.prefijo,";
			$sql .= "									FF.factura_fiscal,";
			$sql .= "									FF.fecha_registro	";			
			$sql .= "					FROM		facturas_externas FF ";
			$sql .= "					WHERE		FF.empresa_id = '".$empresa."' ";
			$sql .= "					AND			FF.fecha_registro::date >= '".$fechai."' ";
			$sql .= "					AND			FF.fecha_registro::date <= '".$fechaf."' ";
			$sql .= "				) AS FF ";
			$sql .= "ORDER BY prefijo,factura_fiscal";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[]  = $rst->GetRowAssoc($ToUpper = false);		
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/**************************************************************************************
		*
		**************************************************************************************/
		function ObtenerFacturasAnuladas($datos,$empresa)
		{
			$fechaf = date("Y-m-d", mktime(0, 0, 0,(intval($datos['mes'])+1), 0,date("Y")));
			$fechai = date("Y-m-d", mktime(0, 0, 0,(intval($datos['mes'])), 1,date("Y")));
			
			$sql	= "SELECT FF.prefijo,";
			$sql .= "				FF.factura_fiscal,";
			$sql .= "				FF.total_factura AS valor_anulado,";
			$sql .=	"				SU.nombre,";
			$sql .= "				TO_CHAR(AF.fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
			$sql .= "FROM 	fac_facturas FF, ";
			$sql .= "				auditoria_anulacion_fac_facturas AF,";
			$sql .= "				system_usuarios SU  ";
			$sql .= "WHERE 	FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "AND 		FF.empresa_id = '".$empresa."' ";
			$sql .= "AND 		FF.empresa_id = AF.empresa_id ";
			$sql .= "AND 		FF.prefijo = AF.prefijo ";
			$sql .= "AND 		AF.usuario_id = SU.usuario_id ";
			$sql .= "AND 		FF.factura_fiscal = AF.factura_fiscal ";
			$sql .= "AND 		AF.fecha_registro::date >= '".$fechai."' ";
			$sql .= "AND 		AF.fecha_registro::date <= '".$fechaf."'  ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[]  = $rst->GetRowAssoc($ToUpper = false);		
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/**************************************************************************************
		*
		***************************************************************************************/
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