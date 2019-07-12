<?php
  /**
  * $Id: app_Cartera_Notas.class.php,v 1.11 2009/06/26 13:53:16 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.11 $ 
	* 
	* @autor Hugo F  Manrique 
  */
	class app_Cartera_Notas extends ConexionBD
	{
		function app_Cartera_Notas(){}
		/**
		* Funcion donde se obtienen los datos de las notas de ajuste tanto temporales como 
		* las cerradas
		*
		* @param array $datos Filtros de la consulta
		* @param char 	$empresa Identificacion de la empresa
    *
		* @return array datos de las notas
		*/
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
      $NC .= "				SELECT 	AF.valor_nota AS abono, ";
      $NC .= "								AF.prefijo AS prefijo_nota,";
      $NC .= "								AF.nota_credito_id AS nota_credito_ajuste, ";
      $NC .= "								AF.fecha_registro::date,";
      $NC .= "								AF.usuario_id, ";
      $NC .= "								'CREDITO' AS tipo, ";
      $NC .= "								'NC' AS abrv, ";
      $NC .= "								AF.prefijo_factura AS prefijo, ";
      $NC .= "								AF.factura_fiscal ";
      $NC .= "				FROM		notas_credito AF LEFT JOIN ";
      $NC .= "								notas_credito_auditoria_anulaciones NA ";
      $NC .= "								ON(		NA.empresa_id = Af.empresa_id AND ";
      $NC .= "											NA.prefijo = AF.prefijo AND	";
      $NC .= "											NA.nota_credito_id = AF.nota_credito_id ";
      if($datos['fecha_fin'])
        $NC .= "											AND		NA.fecha_registro::date >= '".$fechai."' ";
      if($datos['fecha_fin'])
        $NC .= "											AND		NA.fecha_registro::date <= '".$fechaf."' ";
      $NC .= "									) ";

      $NC .= "				WHERE		AF.empresa_id = '".$empresa."' ";
      $NC .= "				AND			NA.nota_credito_id IS NULL ";
      $NC .= "        AND		  AF.estado = '1'::bpchar ";
      if($datos['prefijo'])
        $NC .= "				AND		AF.prefijo = '".$datos['prefijo']."' ";
      if($datos['numero'])
        $NC .= "				AND		AF.nota_credito_id = '".$datos['numero']."' ";
      
      if($datos['fecha_inicio'])
        $NC .= "				AND		AF.fecha_registro::date >= '".$fechai."' ";
      if($datos['fecha_fin'])
        $NC .= "				AND		AF.fecha_registro::date <= '".$fechaf."' ";

			$ND .= "				SELECT 	AF.valor_nota AS abono, ";
			$ND .= "								AF.prefijo AS prefijo_nota,";
			$ND .= "								AF.nota_debito_id AS nota_credito_ajuste, ";
			$ND .= "								AF.fecha_registro::date,";
			$ND .= "								AF.usuario_id, ";
			$ND .= "								'DEBITO' AS tipo, ";
			$ND .= "								'ND' AS abrv, ";
			$ND .= "								AF.prefijo_factura AS prefijo, ";
			$ND .= "								AF.factura_fiscal ";
			$ND .= "				FROM		notas_debito AF LEFT JOIN ";
			$ND .= "								notas_debito_auditoria_anulaciones NA ";
			$ND .= "								ON(		NA.empresa_id = Af.empresa_id AND ";
			$ND .= "											NA.prefijo = AF.prefijo AND	";
			$ND .= "											NA.nota_debito_id = AF.nota_debito_id ";
			
			if($datos['fecha_inicio'])
				$ND .= "										AND		NA.fecha_registro::date >= '".$fechai."' ";
			if($datos['fecha_fin'])
				$ND .= "										AND		NA.fecha_registro::date <= '".$fechaf."' ";
			
			$ND .= "							) ";
			$ND .= "				WHERE		AF.empresa_id = '".$empresa."' ";
			$ND .= "				AND			NA.prefijo IS NULL ";
      $ND .= "        AND		  AF.estado = '1'::bpchar ";
			if($datos['prefijo'])
				$ND .= "				AND		AF.prefijo = '".$datos['prefijo']."' ";
			if($datos['numero'])
				$ND .= "				AND		AF.nota_debito_id = '".$datos['numero']."' ";
			if($datos['fecha_inicio'])
				$ND .= "				AND		AF.fecha_registro::date >= '".$fechai."' ";
			if($datos['fecha_fin'])
				$ND .= "				AND		AF.fecha_registro::date <= '".$fechaf."' ";
			
			$NA .= "				SELECT 	SUM(NF.valor_abonado) AS abono,";
			$NA .= "								NF.prefijo AS prefijo_nota, ";
			$NA .= "								NF.nota_credito_ajuste, ";
			$NA .= "								NA.fecha_registro::date,";
			$NA .= "								NA.usuario_id, ";
			$NA .= "								'AJUSTE' AS tipo, ";
			$NA .= "								'NA' AS abrv, ";
			$NA .= "								NF.prefijo_factura AS prefijo, ";
			$NA .= "								NF.factura_fiscal ";
			$NA .= "				FROM		notas_credito_ajuste_detalle_facturas AS NF, ";
			$NA .= "								notas_credito_ajuste_detalle_conceptos AS NC, ";
			$NA .= "								notas_credito_ajuste NA ";
      $NA .= "								LEFT JOIN	notas_credito_ajuste_detalle_conceptos NX ";
			$NA .= "								ON(	NX.empresa_id = NA.empresa_id AND ";
			$NA .= "										NX.prefijo = NA.prefijo AND ";
			$NA .= "										NX.nota_credito_ajuste = NA.nota_credito_ajuste AND ";
			$NA .= "										NX.concepto_id = 245 ) ";
			$NA .= "				WHERE		NF.empresa_id = '".$empresa."' ";
			$NA .= "				AND			NA.empresa_id = NC.empresa_id ";
      $NA .= "				AND			NX.concepto_id IS NULL ";
			$NA .= "				AND 		NA.nota_credito_ajuste = NC.nota_credito_ajuste ";
			$NA .= "				AND			NA.prefijo = NC.prefijo ";
			$NA .= "				AND			NA.empresa_id = NF.empresa_id ";
			$NA .= "				AND 		NA.nota_credito_ajuste = NF.nota_credito_ajuste ";
			$NA .= "				AND			NA.prefijo = NF.prefijo ";
			$NA .= "				AND			NC.concepto_id != 246 ";
 			$NA .= "				AND			NA.estado != '0'::bpchar ";

			if($datos['prefijo'])
				$NA .= "				AND		NF.prefijo = '".$datos['prefijo']."' ";
			if($datos['numero'])
				$NA .= "				AND		NF.nota_credito_ajuste = '".$datos['numero']."' ";
			if($datos['fecha_inicio'])
				$NA .= "				AND		NA.fecha_registro::date >= '".$fechai."' ";
			if($datos['fecha_fin'])
				$NA .= "				AND		NA.fecha_registro::date <= '".$fechaf."' ";	
				
			$NA .= "				GROUP BY 2,3,4,5,6,7,8,9 ";
			
			$NG .= "				SELECT 	NG.valor_aceptado AS abono, ";
			$NG .= "								NG.prefijo AS prefijo_nota, ";
			$NG .= "								NG.numero AS nota_credito_ajuste, ";
			$NG .= "								NG.fecha_registro::date,";
			$NG .= "								NG.usuario_id, ";
			$NG .= "								'CREDITO GLOSAS' AS tipo, ";
			$NG .= "								'NG' AS abrv, ";
			$NG .= "								G.prefijo, ";
			$NG .= "								G.factura_fiscal ";
			$NG .= "				FROM		glosas G,";
			$NG .= "								notas_credito_glosas NG ";
			$NG .= "				WHERE		G.empresa_id = '".$empresa."' ";
			//$NG .= "				AND			NG.valor_aceptado > 0 ";
 			$NG .= "				AND			G.sw_estado = '3'::bpchar ";
			$NG .= "				AND			NG.glosa_id = G.glosa_id ";
			if($datos['prefijo'])
				$NG .= "				AND		NG.prefijo = '".$datos['prefijo']."' ";
			if($datos['numero'])
				$NG .= "				AND		NG.numero = '".$datos['numero']."' ";
			if($datos['fecha_inicio'])
				$NG .= "				AND		NG.fecha_registro::date >= '".$fechai."' ";
			if($datos['fecha_fin'])
				$NG .= "				AND		NG.fecha_registro::date <= '".$fechaf."' ";
			
      if($datos['tipo_nota'])
      {
        switch($datos['tipo_nota'])
        {
          case 'NA': $where .= $NA; break;
          case 'NC': $where .= $NC; break;
          case 'ND': $where .= $ND; break;
          case 'NG': $where .= $NG; break;
        }
      }
      else
      {
        $where .= $NA." UNION ALL ".$NC." UNION ALL ".$NG." UNION ALL ".$ND;
      }
      
			$where .= "			) AS NT ";
			$where .= "			LEFT JOIN auditoria_anulacion_fac_facturas AN ";
			$where .= "			ON( AN.prefijo = NT.prefijo AND ";
			$where .= "			    AN.factura_fiscal = NT.factura_fiscal), ";
			$where .= "			system_usuarios SU ";
			$where .= "WHERE 	NT.usuario_id = SU.usuario_id ";
			$where .= "AND    AN.prefijo IS NULL ";
			$where .= "AND    AN.factura_fiscal IS NULL ";
			//if($datos['tipo_nota'])
			//	$where .= "AND		NT.abrv = '".$datos['tipo_nota']."' ";
			
			if($contar == "1")
				$this->ProcesarSqlConteo("SELECT COUNT(*) $where");
			
			$sql .= "$where ";
			$sql .= "ORDER BY  prefijo_nota,nota_credito_ajuste ";
			
			if($contar == "1")
				$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			while (!$rst->EOF)
			{
				$notas[$rst->fields[3]][]  = $rst->GetRowAssoc($ToUpper = false);		
				$rst->MoveNext();
		  }
			$rst->Close();
			$this->paginaActual = $this->pagina;
      
			return $notas;
		}
		/*********************************************************************************** 
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
      $sql .= "AND    NA.estado != '0'::bpchar ";
			
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
		/***
		* Funcion donde se obtiene el valor de los conceptos que pertenecen a una nota de ajuste 
		* 
		* @params array $datos Filtros de la consulta
		* @params char 	$empresa Identificacion de la empresa
		* @return array datos de los conceptos 
		***/
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
		/**
		* Funcion donde se obtiene la informacion de las facturas que se han cruzado con una 
		* nota
		*
		* @param array $datos Filtros de la consulta
		* @param string	$empresa Identificacion de la empresa
		* @return array datos de las facturas
		*/
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
		/*********************************************************************************
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
		/***********************************************************************************
		*
		***********************************************************************************/
		function ObtenerReporteVencidos($datos,$empresa)
		{
			$sql .= "SELECT	prefijo,";
			$sql .= "				factura_fiscal,";
			$sql .= "				saldo,";
			$sql .= "				envio_id,";
			$sql .= "				fecha_radicacion,";
			$sql .= "				fecha_registro ";
			$sql .= "FROM (";
			$sql .= "				SELECT	FF.prefijo,";
			$sql .= "								FF.factura_fiscal,";
			$sql .= "								FF.saldo,";
			$sql .= "								EN.envio_id,";
			$sql .= "								TO_CHAR(EN.fecha_radicacion,'DD/MM/YYYY') AS fecha_radicacion,";
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
		/************************************************************************************
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
		/**
		*
		*/
		function ObtenerFacturas($datos,$empresa)
		{
			$fechaf = date("Y-m-d", mktime(0, 0, 0,(intval($datos['mes'])+1), 0,$datos['anyo']));
			$fechai = date("Y-m-d", mktime(0, 0, 0,(intval($datos['mes'])), 1,$datos['anyo']));
			
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
		/**
		*
		*/
		function ObtenerFacturasAnuladas($datos,$empresa)
		{
			$fechaf = date("Y-m-d", mktime(0, 0, 0,(intval($datos['mes'])+1), 0,$datos['anyo']));
			$fechai = date("Y-m-d", mktime(0, 0, 0,(intval($datos['mes'])), 1,$datos['anyo']));
			
			$sql	= "SELECT FF.prefijo,";
			$sql .= "				FF.factura_fiscal,";
			$sql .= "				FF.total_factura - COALESCE(FF.retencion_fuente,0) AS valor_anulado,";
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

	}
?>