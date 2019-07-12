<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Listados.class.php,v 1.5 2009/02/16 21:15:09 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  */
  /**
  * Consultas SQL para la generacion de los RIPS de EPS
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.5 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Giraldo
  */
  class Listados extends ConexionBD
  {
  	/**
    * Constructor
    */
    function Listados(){}
		/**
    * Retorna un arraglo con todas las facturas que se van 
    * a incluir en un paquete de RIPS
    */
    function ObtenerListadoRadicacion($empresa,$filtro,$op=1)
    {
			$sql  = "SELECT TE.tipo_id_tercero, 
              				TE.tercero_id, 
              				TE.nombre_tercero,	
              				FT.prefijo,
              				FT.numero,
              				FT.cxp_estado,
              				CR.cxp_radicacion_id, 
              				RI.rips_control_id,
              				TO_CHAR(CR.fecha_radicacion,'DD/MM/YYYY') AS fecha_radicacion
              FROM		cxp_facturas as FT,
                      cxp_radicacion CR 
                      LEFT JOIN 
                      ( 
                        SELECT  TP.codigo_proveedor_id, 
                                TR.tipo_id_tercero, 
                                TR.tercero_id, 
                                TR.nombre_tercero 
                        FROM		terceros_proveedores TP , 
                                terceros TR 
                        WHERE		TR.tercero_id = TP.tercero_id 
                        AND			TR.tipo_id_tercero = TP.tipo_id_tercero 
						
                      ) AS TE 
                      ON (TE.codigo_proveedor_id = CR.proveedor_id)
                      LEFT JOIN rips_arch_control AS RI 
                      ON (RI.rips_control_id = CR.rips_control_id)
              WHERE   CR.cxp_radicacion_id = FT.cxp_radicacion_id	";		
	
		if($filtro['fecha_inicial'])
			$sql .= "AND    CR.fecha_radicacion >= '".$this->DividirFecha($filtro['fecha_inicial'])."'::date ";

		if($filtro['fecha_final']) 
			$sql .= "AND    CR.fecha_radicacion::date <= '".$this->DividirFecha($filtro['fecha_final'])."'::date ";
			
		if($filtro['fac_sin_rips']) 
			$sql .= "AND    RI.rips_control_id IS NULL ";			

		if($filtro['cxp_estado']) 
		{
			$v = "";
			foreach($filtro['cxp_estado'] as $k=>$v)
				$cad .= "'$v',";
			
			$sql .= "AND    FT.cxp_estado IN (".substr($cad, 0, -1).") ";
		}
		if($op == 1)
		{
			$cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
			$this->ProcesarSqlConteo($cont,$filtro['offset']);

			$sql .= "ORDER BY CR.cxp_radicacion_id ASC ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
		}

		if(!$rst = $this->ConexionBaseDatos($sql))	return false;

		$datos = array();
		
		while (!$rst->EOF)
		{
			$datos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
		}
		$rst->Close();

		return $datos;	

    }
  	/**
  	* Retorna un arraglo con  ... de RIPS
    *
    * @return mixed
  	*/
    function ObtenerAC($filtro)
    {
			$sql  = "SELECT DISTINCT CR.cxp_radicacion_id, ";
      $sql .= "       AC.rips_arch_consulta_id, ";
      $sql .= "       AC.codigo_sgss,";
			$sql .= "		    AC.numero_factura,";	
    	$sql .= "				AC.usuario_tipo_identificacion,";
    	$sql .= "				AC.usuario_identificacion,";
    	$sql .= "				TO_CHAR(AC.fecha_consulta,'DD/MM/YYYY') AS fecha_consulta,";
    	$sql .= "				AC.numero_autorizacion,";
    	$sql .= "				AC.codigo_consulta,";
    	$sql .= "				AC.finalidad_consulta,";
    	$sql .= "				AC.causa_externa,";
    	$sql .= "				AC.codigo_diagnostico_principal,";
    	$sql .= "				AC.codigo_diagnostico_relacionado_1,";
    	$sql .= "				AC.codigo_diagnostico_relacionado_2,";
    	$sql .= "				AC.codigo_diagnostico_relacionado_3,";
    	$sql .= "				AC.tipo_disgnostico_principal,";
    	$sql .= "				AC.valor_consulta,";
    	$sql .= "				AC.valor_cuota_moderadora,";
    	$sql .= "				AC.valor_neto ";
			$sql .= "FROM   cxp_facturas as FT,";
			$sql .= "		    cxp_radicacion as CR ,";
			$sql .= "		    rips_arch_consultas as AC, ";
			$sql .= "		    interfaz_uv.afiliados_uv AF ";
      $sql .= "WHERE	CR.cxp_radicacion_id = FT.cxp_radicacion_id	";
      $sql .= "AND    AC.rips_control_id = CR.rips_control_id	";
      $sql .= "AND    AC.usuario_tipo_identificacion = AF.afiliado_tipo_id ";
      $sql .= "AND    AC.usuario_identificacion = AF.afiliado_id ";
				
  		if($filtro['fecha_inicial'])
  			$sql .= "AND    CR.fecha_radicacion >= '".$this->DividirFecha($filtro['fecha_inicial'])."'::date ";

  		if($filtro['fecha_final']) 
  			$sql .= "AND    CR.fecha_radicacion::date <= '".$this->DividirFecha($filtro['fecha_final'])."'::date ";
  			
  		if($filtro['fac_sin_rips']) 
  			$sql .= "AND    CR.rips_control_id IS NULL ";			

  		if($filtro['cxp_estado']) 
  		{
  			$v = "";
  			foreach($filtro['cxp_estado'] as $k=>$v)
  				$cad .= "'$v',";
  			
  			$sql .= "AND    FT.cxp_estado IN (".substr($cad, 0, -1).") ";
  		}
  				
  		$sql .= " ORDER BY CR.cxp_radicacion_id, AC.rips_arch_consulta_id ";
  		
  		if(!$rst = $this->ConexionBaseDatos($sql))	return false;

  		$datos = array();
  		
  		while (!$rst->EOF)
  		{
  			$datos[] = $rst->GetRowAssoc($ToUpper = false);
  			$rst->MoveNext();
  		}
  		$rst->Close();

  		return $datos;		
  	}
    /**
  	* Retorna un arraglo con  ... de RIPS
    *
    * @return mixed
  	*/
    function ObtenerAD($filtro)
    {
			$sql  = "SELECT codigo_concepto, ";
      $sql .= "       SUM(valor_total_concepto) AS valor ";
      $sql .= "FROM   (";
      $sql .= "         SELECT DISTINCT AC.rips_arch_descripcion_agrupada_id,";
      $sql .= "                 AC.codigo_concepto,";
    	$sql .= "				          AC.valor_total_concepto ";
			$sql .= "         FROM    cxp_facturas as FT,";
			$sql .= "		              cxp_radicacion as CR ,";
      $sql .= "				          rips_arch_usuarios_ss AU, ";
			$sql .= "		              rips_arch_descripciones_agrupadas as AC, ";
			$sql .= "		              interfaz_uv.afiliados_uv AF ";
      $sql .= "         WHERE   CR.cxp_radicacion_id = FT.cxp_radicacion_id	";
      $sql .= "         AND     AC.rips_control_id = CR.rips_control_id	";
      $sql .= "         AND     AU.rips_control_id = CR.rips_control_id	";
      $sql .= "         AND     AU.usuario_tipo_identificacion = AF.afiliado_tipo_id ";
      $sql .= "         AND     AU.usuario_identificacion = AF.afiliado_id ";
				
  		if($filtro['fecha_inicial'])
  			$sql .= "         AND    CR.fecha_radicacion >= '".$this->DividirFecha($filtro['fecha_inicial'])."'::date ";

  		if($filtro['fecha_final']) 
  			$sql .= "         AND    CR.fecha_radicacion::date <= '".$this->DividirFecha($filtro['fecha_final'])."'::date ";
  			
  		if($filtro['fac_sin_rips']) 
  			$sql .= "         AND    CR.rips_control_id IS NULL ";			

  		if($filtro['cxp_estado']) 
  		{
  			$v = "";
  			foreach($filtro['cxp_estado'] as $k=>$v)
  				$cad .= "'$v',";
  			
  			$sql .= "         AND    FT.cxp_estado IN (".substr($cad, 0, -1).") ";
  		}
  				
  		$sql .= "       ) AS A ";
  		$sql .= "GROUP BY codigo_concepto ";
  		
  		if(!$rst = $this->ConexionBaseDatos($sql))	return false;

  		$datos = array();
  		
  		while (!$rst->EOF)
  		{
  			$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
  			$rst->MoveNext();
  		}
  		$rst->Close();

  		return $datos;		
  	}
		/**
    * Retorna un arraglo con  ... de RIPS
    *
    * @return mixed
    */
    function ObtenerAP($filtro)
    {
      $sql  = "SELECT DISTINCT CR.cxp_radicacion_id, ";
      $sql .= "       AP.rips_arch_procedimiento_id,";
      $sql .= "       AP.numero_factura, ";
			$sql .= "		    AP.codigo_sgss, ";
			$sql .= "		    AP.usuario_tipo_identificacion, ";
			$sql .= "		    AP.usuario_identificacion, ";
			$sql .= "		    TO_CHAR(AP.fecha_procedimiento,'DD/MM/YYYY') AS fecha_procedimiento, ";
			$sql .= "		    AP.codigo_procedimiento, ";
			$sql .= "		    AP.ambito_procedimiento, ";
			$sql .= "		    AP.finalidad_procedimiento, ";
			$sql .= "		    AP.profesional_atiende, ";
			$sql .= "		    AP.codigo_diagnostico_principal, ";
			$sql .= "		    AP.codigo_diagnostico_relacionado, ";
			$sql .= "		    AP.codigo_complicacion, ";
			$sql .= "		    AP.valor_procedimiento  ";
			$sql .= "FROM   cxp_facturas as FT, ";
			$sql .= "		    cxp_radicacion as CR, ";
			$sql .= "		    rips_arch_procedimientos AP, ";
      $sql .= "		    interfaz_uv.afiliados_uv AF ";
			$sql .= "WHERE	CR.cxp_radicacion_id = FT.cxp_radicacion_id ";	
			$sql .= "AND    AP.rips_control_id = CR.rips_control_id ";
			$sql .= "AND    AP.usuario_tipo_identificacion = AF.afiliado_tipo_id ";
			$sql .= "AND    AP.usuario_identificacion = AF.afiliado_id ";
      
  		if($filtro['fecha_inicial'])
  			$sql .= "AND    CR.fecha_radicacion >= '".$this->DividirFecha($filtro['fecha_inicial'])."'::date ";

  		if($filtro['fecha_final']) 
  			$sql .= "AND    CR.fecha_radicacion::date <= '".$this->DividirFecha($filtro['fecha_final'])."'::date ";
  			
  		if($filtro['fac_sin_rips']) 
  			$sql .= "AND    RI.rips_control_id IS NULL ";			

  		if($filtro['cxp_estado']) 
  		{
  			$v = "";
  			foreach($filtro['cxp_estado'] as $k=>$v)
  				$cad .= "'$v',";
  			
  			$sql .= "AND    FT.cxp_estado IN (".substr($cad, 0, -1).") ";
  		}
  				
  		$sql .= " ORDER BY CR.cxp_radicacion_id, AP.rips_arch_procedimiento_id ";
  		
  		if(!$rst = $this->ConexionBaseDatos($sql))	return false;

  		$datos = array();
  		
  		while (!$rst->EOF)
  		{
  			$datos[] = $rst->GetRowAssoc($ToUpper = false);
  			$rst->MoveNext();
  		}
  		$rst->Close();

  		return $datos;		
  	}	
   	/**
  	* Retorna un arraglo con  ... de RIPS
    *
    * @return mixed
  	*/
    function ObtenerAU($filtro)
    {
			$sql  = "SELECT DISTINCT CR.cxp_radicacion_id, ";
      $sql .= "       AU.rips_arch_urgencia_id,";
      $sql .= "       AU.numero_factura, ";
      $sql .= "       AU.codigo_sgss, ";
      $sql .= "				AU.usuario_tipo_identificacion, ";
      $sql .= "				AU.usuario_identificacion, ";
      $sql .= "				TO_CHAR(AU.fecha_ingreso,'DD/MM/YYYY') AS fecha_ingreso, ";
      $sql .= "				AU.hora_ingreso, ";
      $sql .= "				AU.causa_externa, ";
      $sql .= "				AU.codigo_diagnostico_salida, ";
      $sql .= "				AU.codigo_diagnostico_salida_relacionado_1, ";
      $sql .= "				AU.codigo_diagnostico_salida_relacionado_2, ";
      $sql .= "				AU.codigo_diagnostico_salida_relacionado_3, ";
      $sql .= "				AU.codigo_destino_salida, ";
      $sql .= "				AU.estado_salida, ";
      $sql .= "				AU.codigo_causa_muerte, ";
      $sql .= "				TO_CHAR(AU.fecha_salida_observacion,'DD/MM/YYYY') AS fecha_salida_observacion ";
      $sql .= "FROM 	cxp_facturas as FT, ";
      $sql .= "				cxp_radicacion as CR, ";
      $sql .= "				rips_arch_urgencias AU, ";
      $sql .= "		    interfaz_uv.afiliados_uv AF ";
      $sql .= "WHERE	CR.cxp_radicacion_id = FT.cxp_radicacion_id ";	
      $sql .= "AND    AU.rips_control_id = CR.rips_control_id	";
      $sql .= "AND		AU.usuario_tipo_identificacion = AF.afiliado_tipo_id ";
      $sql .= "AND		AU.usuario_identificacion = AF.afiliado_id ";
  				
  		if($filtro['fecha_inicial'])
  			$sql .= "AND    CR.fecha_radicacion >= '".$this->DividirFecha($filtro['fecha_inicial'])."'::date ";

  		if($filtro['fecha_final']) 
  			$sql .= "AND    CR.fecha_radicacion::date <= '".$this->DividirFecha($filtro['fecha_final'])."'::date ";
  			
  		if($filtro['fac_sin_rips']) 
  			$sql .= "AND    CR.rips_control_id IS NULL ";			

  		if($filtro['cxp_estado']) 
  		{
  			$v = "";
  			foreach($filtro['cxp_estado'] as $k=>$v)
  				$cad .= "'$v',";
  			
  			$sql .= "AND    FT.cxp_estado IN (".substr($cad, 0, -1).") ";
  		}
  				
  		$sql .= " ORDER BY CR.cxp_radicacion_id, AU.rips_arch_urgencia_id ";
  		
  		if(!$rst = $this->ConexionBaseDatos($sql))	return false;

  		$datos = array();
  		
  		while (!$rst->EOF)
  		{
  			$datos[] = $rst->GetRowAssoc($ToUpper = false);
  			$rst->MoveNext();
  		}
  		$rst->Close();

  		return $datos;		
  	}	
  	/**
  	* Retorna un arraglo con  ... de RIPS
    *
    * @return mixed
  	*/
    function ObtenerAH($filtro)
    {
      $sql  = "SELECT DISTINCT CR.cxp_radicacion_id, ";
      $sql .= "       AH.rips_arch_hospitalizacion_id,";
      $sql .= "       AH.numero_factura,";
      $sql .= "		    AH.codigo_sgss,";
      $sql .= "		    AH.usuario_tipo_identificacion,";
      $sql .= "		    AH.usuario_identificacion,";
      $sql .= "		    AH.via_ingreso,";
      $sql .= "		    TO_CHAR(AH.fecha_ingreso,'DD/MM/YYYY') AS fecha_ingreso,";
      $sql .= "		    AH.hora_ingreso,";
      $sql .= "		    AH.causa_externa,";
      $sql .= "		    AH.codigo_diagnostico_ingreso,";
      $sql .= "		    AH.codigo_diagnostico_egreso,";
      $sql .= "		    AH.codigo_diagnostico_egreso_relacionado_1,";
      $sql .= "		    AH.codigo_diagnostico_egreso_relacionado_2,";
      $sql .= "		    AH.codigo_diagnostico_egreso_relacionado_3,";
      $sql .= "		    AH.codigo_diagnostico_complicacion,";
      $sql .= "		    AH.estado_salida,";
      $sql .= "		    AH.codigo_causa_muerte,";
      $sql .= "		    TO_CHAR(AH.fecha_egreso,'DD/MM/YYYY') AS fecha_egreso,";
      $sql .= "		    AH.hora_egreso ";
			$sql .= "FROM   cxp_facturas as FT, ";
			$sql .= "		    cxp_radicacion as CR, ";
			$sql .= "		    rips_arch_hospitalizaciones as AH, ";
			$sql .= "		    interfaz_uv.afiliados_uv AF ";
			$sql .= "WHERE	CR.cxp_radicacion_id = FT.cxp_radicacion_id ";
      $sql .= "AND    AH.rips_control_id = CR.rips_control_id	";
   		$sql .= "AND    AH.usuario_tipo_identificacion = AF.afiliado_tipo_id ";
      $sql .= "AND    AH.usuario_identificacion = AF.afiliado_id ";
				
  		if($filtro['fecha_inicial'])
  			$sql .= "AND    CR.fecha_radicacion >= '".$this->DividirFecha($filtro['fecha_inicial'])."'::date ";

  		if($filtro['fecha_final']) 
  			$sql .= "AND    CR.fecha_radicacion::date <= '".$this->DividirFecha($filtro['fecha_final'])."'::date ";
  			
  		if($filtro['fac_sin_rips']) 
  			$sql .= "AND    RI.rips_control_id IS NULL ";			

  		if($filtro['cxp_estado']) 
  		{
  			$v = "";
  			foreach($filtro['cxp_estado'] as $k=>$v)
  				$cad .= "'$v',";
  			
  			$sql .= "AND    FT.cxp_estado IN (".substr($cad, 0, -1).") ";
  		}
  				
  		$sql .= " ORDER BY CR.cxp_radicacion_id, AH.rips_arch_hospitalizacion_id ";
  		
  		if(!$rst = $this->ConexionBaseDatos($sql))	return false;

  		$datos = array();
  		
  		while (!$rst->EOF)
  		{
  			$datos[] = $rst->GetRowAssoc($ToUpper = false);
  			$rst->MoveNext();
  		}
  		$rst->Close();

  		return $datos;		
  	}		
    /**
    * Retorna un arraglo con  ... de RIPS
    *
    * @return mixed
    */
    function ObtenerAN($filtro)
    {
			$sql  = "SELECT DISTINCT CR.cxp_radicacion_id, ";
      $sql .= "       AN.rips_arch_recien_nacidos_id, ";
      $sql .= "       AN.numero_factura, ";
      $sql .= "		    AN.codigo_sgss, ";
      $sql .= "		    AN.usuario_tipo_identificacion, ";
      $sql .= "		    AN.usuario_identificacion, ";
      $sql .= "		    TO_CHAR(AN.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
      $sql .= "		    AN.hora_nacimiento, ";
      $sql .= "		    AN.edad_gestacional, ";
      $sql .= "		    AN.control_prenatal, ";
      $sql .= "		    AN.tipo_sexo, ";
      $sql .= "		    AN.peso, ";
      $sql .= "		    AN.codigo_diagnostico, ";
      $sql .= "		    AN.codigo_causa_muerte, ";
      $sql .= "		    TO_CHAR(AN.fecha_muerte,'DD/MM/YYYY') AS fecha_muerte, ";
      $sql .= "		    AN.hora_muerte  ";
			$sql .= "FROM   cxp_facturas as FT, ";
      $sql .= "		    cxp_radicacion as CR, ";
      $sql .= "		    rips_arch_recien_nacidos aS AN, ";
      $sql .= "		    interfaz_uv.afiliados_uv AF ";
			$sql .= "WHERE  CR.cxp_radicacion_id = FT.cxp_radicacion_id	";
			$sql .= "AND    AN.rips_control_id = CR.rips_control_id ";
      $sql .= "AND    AN.usuario_tipo_identificacion = AF.afiliado_tipo_id ";
      $sql .= "AND    AN.usuario_identificacion = AF.afiliado_id ";
				
  		if($filtro['fecha_inicial'])
  			$sql .= "AND    CR.fecha_radicacion >= '".$this->DividirFecha($filtro['fecha_inicial'])."'::date ";

  		if($filtro['fecha_final']) 
  			$sql .= "AND    CR.fecha_radicacion::date <= '".$this->DividirFecha($filtro['fecha_final'])."'::date ";
  			
  		if($filtro['fac_sin_rips']) 
  			$sql .= "AND    RI.rips_control_id IS NULL ";			

  		if($filtro['cxp_estado']) 
  		{
  			$v = "";
  			foreach($filtro['cxp_estado'] as $k=>$v)
  				$cad .= "'$v',";
  			
  			$sql .= "AND    FT.cxp_estado IN (".substr($cad, 0, -1).") ";
  		}
  				
  		$sql .= " ORDER BY CR.cxp_radicacion_id, AN.rips_arch_recien_nacidos_id ";
  		
  		if(!$rst = $this->ConexionBaseDatos($sql))	return false;

  		$datos = array();
  		
  		while (!$rst->EOF)
  		{
  			$datos[] = $rst->GetRowAssoc($ToUpper = false);
  			$rst->MoveNext();
  		}
  		$rst->Close();

  		return $datos;		
  	}	
  	/**
  	* Retorna un arraglo con  ... de RIPS
    *
    * @return mixed
  	*/
    function ObtenerAM($filtro)
    {
			$sql  = "SELECT DISTINCT CR.cxp_radicacion_id, ";
      $sql .= "       AM.rips_arch_medicamento_id,";
      $sql .= "       AM.numero_factura, ";
      $sql .= "				AM.codigo_sgss, ";
      $sql .= "				AM.usuario_tipo_identificacion, ";
      $sql .= "				AM.usuario_identificacion, ";
      $sql .= "				US.edad, ";
      $sql .= "				US.unidad_medida_edad, ";
      $sql .= "				AM.nombre_generico_medicamento, ";
      $sql .= "				AM.tipo_medicamento, ";
      $sql .= "				AM.forma_farmaceutica, ";
      $sql .= "				AM.concentracion_medicamento, ";
      $sql .= "				AM.unidad_medida, ";
      $sql .= "				AM.numero_unidades, ";
      $sql .= "				AM.valor_unitario, ";
      $sql .= "				AM.valor_total  ";
      $sql .= "FROM		cxp_facturas FT, ";
      $sql .= "       cxp_radicacion CR , ";
      $sql .= "       rips_arch_usuarios_ss US, ";
      $sql .= "       rips_arch_medicamentos AM, ";
      $sql .= "		    interfaz_uv.afiliados_uv AF ";
      $sql .= "WHERE  CR.cxp_radicacion_id = FT.cxp_radicacion_id	 ";
      $sql .= "AND    US.rips_control_id = CR.rips_control_id ";
      $sql .= "AND    AM.rips_control_id = CR.rips_control_id ";
      $sql .= "AND    US.usuario_tipo_identificacion = AM.usuario_tipo_identificacion ";
      $sql .= "AND    US.usuario_identificacion = AM.usuario_identificacion ";
      $sql .= "AND    US.usuario_tipo_identificacion = AF.afiliado_tipo_id ";
      $sql .= "AND    US.usuario_identificacion = AF.afiliado_id ";
  				
  		if($filtro['fecha_inicial'])
  			$sql .= "AND    CR.fecha_radicacion >= '".$this->DividirFecha($filtro['fecha_inicial'])."'::date ";

  		if($filtro['fecha_final']) 
  			$sql .= "AND    CR.fecha_radicacion::date <= '".$this->DividirFecha($filtro['fecha_final'])."'::date ";
  			
  		if($filtro['fac_sin_rips']) 
  			$sql .= "AND    RI.rips_control_id IS NULL ";			

  		if($filtro['cxp_estado']) 
  		{
  			$v = "";
  			foreach($filtro['cxp_estado'] as $k=>$v)
  				$cad .= "'$v',";
  			
  			$sql .= "AND    FT.cxp_estado IN (".substr($cad, 0, -1).") ";
  		}
  				
  		$sql .= " ORDER BY CR.cxp_radicacion_id, AM.rips_arch_medicamento_id ";
  		
  		if(!$rst = $this->ConexionBaseDatos($sql))	return false;

  		$datos = array();
  		
  		while (!$rst->EOF)
  		{
  			$datos[] = $rst->GetRowAssoc($ToUpper = false);
  			$rst->MoveNext();
  		}
  		$rst->Close();

  		return $datos;		
  	}		
    /**
  	* 
    * @return mixed
  	*/
    function ObtenerUS($filtro)
    {
			$sql  = "SELECT DISTINCT AU.usuario_tipo_identificacion,";
      $sql .= " 	    AU.usuario_identificacion,";
      $sql .= "     	AU.tipo_usuario,";
      $sql .= " 	    AU.edad,";
      $sql .= " 	    AU.unidad_medida_edad,";
      $sql .= " 	    AU.tipo_sexo,";
      $sql .= " 	    AU.tipo_dpto_id,";
      $sql .= " 	    AU.tipo_mpio_id,";
      $sql .= " 	    AU.zona_residencia ";
      $sql .= "FROM 	cxp_facturas as FT,";
      $sql .= "				cxp_radicacion as CR,";
      $sql .= "				rips_arch_usuarios_ss AU, ";
      $sql .= "		    interfaz_uv.afiliados_uv AF ";
      $sql .= "WHERE	CR.cxp_radicacion_id = FT.cxp_radicacion_id ";	
      $sql .= "AND    AU.rips_control_id = CR.rips_control_id	";
  		$sql .= "AND    AU.usuario_tipo_identificacion = AF.afiliado_tipo_id ";
      $sql .= "AND    AU.usuario_identificacion = AF.afiliado_id ";
      
  		if($filtro['fecha_inicial'])
  			$sql .= "AND    CR.fecha_radicacion >= '".$this->DividirFecha($filtro['fecha_inicial'])."'::date ";

  		if($filtro['fecha_final']) 
  			$sql .= "AND    CR.fecha_radicacion::date <= '".$this->DividirFecha($filtro['fecha_final'])."'::date ";
  			
  		if($filtro['fac_sin_rips']) 
  			$sql .= "AND    CR.rips_control_id IS NULL ";			

  		if($filtro['cxp_estado']) 
  		{
  			$v = "";
  			foreach($filtro['cxp_estado'] as $k=>$v)
  				$cad .= "'$v',";
  			
  			$sql .= "AND    FT.cxp_estado IN (".substr($cad, 0, -1).") ";
  		}
  		//$sql .= " ORDER BY CR.cxp_radicacion_id, AU.rips_arch_usuario_ss_id ";
  		
  		if(!$rst = $this->ConexionBaseDatos($sql))	return false;

  		$datos = array();
  		
  		while (!$rst->EOF)
  		{
  			$datos[] = $rst->GetRowAssoc($ToUpper = false);
  			$rst->MoveNext();
  		}
  		$rst->Close();

  		return $datos;		
  	}
  }
?>