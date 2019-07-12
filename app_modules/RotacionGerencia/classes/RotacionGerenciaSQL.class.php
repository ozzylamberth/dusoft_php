<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: RotacionGerenciaSQL.class.php,v 1.2 2010/01/14 22:49:02 sandra Exp $Revision: 1.2 $
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/

	class RotacionGerenciaSQL extends ConexionBD
	{
	/*
	* Constructor de la clase
	*/
	function RotacionGerenciaSQL(){}

	/**
	* Funcion donde se Consulta el permiso pàra los usuarios
	* @return array $datos vector que contiene la informacion de la consulta de los Tipos 
	* de Identificacion
	*/

		function ObtenerPermisos()
		{
		
			$sql  = "SELECT a.empresa_id, ";
			$sql .= "       b.razon_social AS descripcion1, ";
			$sql .= "       b.sw_activa, ";
			$sql .= "       a.centro_utilidad, ";
			$sql .= "       c.descripcion AS descripcion2, ";
			$sql .= "       a.usuario_id, ";
			$sql .= "       DE.departamento, ";
			$sql .= "       DE.descripcion AS descripcion3, ";
			$sql .= "       e.bodega,  ";
			$sql .= "       e.descripcion AS descripcion4,   ";
			$sql .= "       b.sw_tipo_empresa   ";
			$sql .= "FROM 	userpermisos_RotacionGerencia a, ";
			$sql .= "       empresas b, ";
			$sql .= "       centros_utilidad c, ";
			$sql .= "       bodegas e, ";
			$sql .= "       departamentos DE ";
			$sql .= "WHERE  a.usuario_id = ".UserGetUID()."  ";
			$sql .= "AND 	  a.empresa_id = b.empresa_id ";
			$sql .= "AND 	  a.empresa_id = c.empresa_id ";
			$sql .= "AND 	  a.centro_utilidad = c.centro_utilidad ";
			$sql .= "AND 	  c.empresa_id = e.empresa_id ";
			$sql .= "AND 	  c.centro_utilidad = e.centro_utilidad  ";
			$sql .= "AND 	  a.empresa_id = c.empresa_id ";
			$sql .= " AND    e.departamento = DE.departamento ";
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[4]][$rst->fields[7]][$rst->fields[9]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}

			$rst->Close();
			return $datos;
		}
	 
     /*
		* Funcion  para consultar los productos  que han tenido movimiento , rotacion por laboratorio
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function  RotacionFinal($fechaI,$fechaF,$empresa,$clase,$bodega,$centro_utilidad)
		{
		/*$this->debug=true;*/
		
      if(!empty($clase))
      {
        $filtro = " AND (h.clase_id='".trim($clase)."') "; 
        $filtro1 = " AND (f.clase_id='".trim($clase)."') "; 
        $filtro2 = " AND (T.clase_id='".trim($clase)."') "; 
      }
      
      $sql = "SELECT  A.empresa_id,
                      A.periodo,
                      A.codigo_producto,
                      A.ingreso,
                      A.egreso,
                      C.clase_id,
                      C.descripcion,
                      fc_descripcion_producto(A.codigo_producto) as producto,
                      EXI.existencia
              FROM  (
                      SELECT  EMP.empresa_id,
                              CASE  WHEN E.periodo IS NULL THEN I.periodo
                                    ELSE E.periodo END AS periodo,
                              CASE  WHEN E.codigo_producto IS NULL THEN I.codigo_producto
                                    ELSE E.codigo_producto END AS codigo_producto,
                              COALESCE(SUM(I.total_ingreso),0)AS ingreso,
                              COALESCE(SUM(E.total_egreso),0)AS egreso
                      FROM    (
                                SELECT  e.codigo_producto,
                                        b.empresa_id,
                                        TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo,
                                        SUM(e.cantidad) as total_egreso
                                FROM    inv_bodegas_movimiento as a
                                        JOIN inv_bodegas_documentos as b ON(a.documento_id = b.documento_id)
                                        AND (a.empresa_id = b.empresa_id)
                                        AND (a.centro_utilidad = b.centro_utilidad)
                                        AND (a.bodega = b.bodega)
                                        JOIN documentos as c ON (b.documento_id = c.documento_id)
                                        AND (b.empresa_id = c.empresa_id) 
                                        JOIN tipos_doc_generales AS d ON (c.tipo_doc_general_id = d.tipo_doc_general_id)
                                        AND (d.inv_tipo_movimiento IN('E'))
                                        JOIN inv_bodegas_movimiento_d as e ON (a.empresa_id = e.empresa_id)
                                        AND (a.prefijo = e.prefijo)
                                        AND (a.numero = e.numero)
                                        JOIN inventarios_productos as f ON (e.codigo_producto = f.codigo_producto)
                                        JOIN inv_subclases_inventarios as g ON (f.grupo_id = g.grupo_id)
                                        AND (f.clase_id = g.clase_id)
                                        AND (f.subclase_id = g.subclase_id)
                                        JOIN inv_clases_inventarios as h ON (g.grupo_id = h.grupo_id)
                                        AND (g.clase_id = h.clase_id)
                                        JOIN inv_grupos_inventarios as i ON(h.grupo_id = i.grupo_id)
                                WHERE TRUE
																AND (c.tipo_doc_general_id not in (SELECT tipo_doc_general_id FROM Inv_Documentos_Rotacion WHERE empresa_id='".$empresa."' AND tipo_doc_general_id = c.tipo_doc_general_id)) 
																AND a.fecha_registro::date >= '".$this->DividirFecha(trim($fechaI),'-')."' ::date
																AND a.fecha_registro::date <= '".$this->DividirFecha(trim($fechaF),'-')."' ::date
                                AND a.empresa_id = '".trim($empresa)."' 
                                AND a.bodega='".trim($bodega)."' 
                                AND a.centro_utilidad='".trim($centro_utilidad)."'
																".$filtro."
																AND i.sw_medicamento = '1'
                                GROUP BY  e.codigo_producto,
                                          TO_CHAR(a.fecha_registro,'YYYY-MM'),
                                          b.empresa_id
                                UNION
                                SELECT
                                      c.codigo_producto,
                                      b.empresa_id,
                                      TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo,
                                      SUM(c.cantidad) as total_egreso
                                FROM
                                      bodegas_documentos as a
                                      JOIN bodegas_doc_numeraciones as b ON (a.bodegas_doc_id = b.bodegas_doc_id)
                                      JOIN bodegas_documentos_d as c ON (a.bodegas_doc_id = c.bodegas_doc_id)
                                      AND (a.numeracion = c.numeracion)
																		  AND (b.tipo_movimiento IN('E'))
                                      JOIN inventarios_productos as d ON (c.codigo_producto = d.codigo_producto)
                                      JOIN inv_subclases_inventarios as e ON (d.grupo_id = e.grupo_id)
                                      AND (d.clase_id = e.clase_id)
                                      AND (d.subclase_id = e.subclase_id)
                                      JOIN inv_clases_inventarios as f ON (e.grupo_id = f.grupo_id)
                                      AND (e.clase_id = f.clase_id)
                                      JOIN inv_grupos_inventarios as g ON(f.grupo_id = g.grupo_id)
                                WHERE TRUE
															  AND a.fecha_registro::date >= '".$this->DividirFecha(trim($fechaI),'-')."' ::date
															  AND a.fecha_registro::date <= '".$this->DividirFecha(trim($fechaF),'-')."' ::date
                                AND b.empresa_id = '".trim($empresa)."' 
                                AND b.bodega='".trim($bodega)."' 
                                AND b.centro_utilidad='".trim($centro_utilidad)."'
																		  ".$filtro1."
                                AND g.sw_medicamento = '1'
                                GROUP BY  c.codigo_producto,
                                TO_CHAR(a.fecha_registro,'YYYY-MM'),
                                b.empresa_id
                              ) AS E
                              FULL OUTER JOIN
                              (
                                SELECT
                                            e.codigo_producto,
                                            b.empresa_id,
                                            TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo,
                                            SUM(e.cantidad) as total_ingreso
                                FROM
                                            inv_bodegas_movimiento as a
                                            JOIN inv_bodegas_documentos as b ON(a.documento_id = b.documento_id)
                                            AND (a.empresa_id = b.empresa_id)
                                            AND (a.centro_utilidad = b.centro_utilidad)
                                            AND (a.bodega = b.bodega)
                                            JOIN documentos as c ON (b.documento_id = c.documento_id)
                                            AND (b.empresa_id = c.empresa_id)
                                            JOIN tipos_doc_generales AS d ON (c.tipo_doc_general_id = d.tipo_doc_general_id)
                                            AND (d.inv_tipo_movimiento IN('I'))
                                            JOIN inv_bodegas_movimiento_d as e ON (a.empresa_id = e.empresa_id)
                                            AND (a.prefijo = e.prefijo)
                                            AND (a.numero = e.numero)
                                            JOIN inventarios_productos as f ON (e.codigo_producto = f.codigo_producto)
                                            JOIN inv_subclases_inventarios as g ON (f.grupo_id = g.grupo_id)
                                            AND (f.clase_id = g.clase_id)
                                            AND (f.subclase_id = g.subclase_id)
                                            JOIN inv_clases_inventarios as h ON (g.grupo_id = h.grupo_id)
                                            AND (g.clase_id = h.clase_id)
                                            JOIN inv_grupos_inventarios as i ON(h.grupo_id = i.grupo_id)
                                WHERE TRUE
                                AND (c.tipo_doc_general_id not in (SELECT tipo_doc_general_id FROM Inv_Documentos_Rotacion WHERE empresa_id='".trim($empresa)."' AND tipo_doc_general_id = c.tipo_doc_general_id)) 
                                AND a.fecha_registro::date >= '".$this->DividirFecha(trim($fechaI),'-')."' ::date
                                AND a.fecha_registro::date <= '".$this->DividirFecha(trim($fechaF),'-')."' ::date
                                AND a.empresa_id = '".trim($empresa)."' 
                                AND a.bodega='".trim($bodega)."' 
                                AND a.centro_utilidad='".trim($centro_utilidad)."'
                                ".$filtro."
                                AND i.sw_medicamento = '1'
                                                          GROUP BY  e.codigo_producto,
                                                                          TO_CHAR(a.fecha_registro,'YYYY-MM'),
                                                                          b.empresa_id
                              )  
                              AS I ON (E.codigo_producto = I.codigo_producto)
                                                  AND (E.periodo = I.periodo)
                                                  AND (E.empresa_id = I.empresa_id)
                                                  LEFT JOIN empresas AS EMP ON (E.empresa_id = EMP.empresa_id)
                                                  OR (I.empresa_id = EMP.empresa_id)
                      WHERE TRUE
                      GROUP BY  E.periodo,
                                I.periodo,
                                I.codigo_producto,
                                E.codigo_producto,
                                EMP.empresa_id
                      ORDER BY   
                            EMP.empresa_id,
                            E.periodo,
                            I.periodo,
                            I.codigo_producto
                    )AS A
                        JOIN inventarios_productos P ON (A.codigo_producto=P.codigo_producto)
                        JOIN inv_subclases_inventarios S ON (P.subclase_id=S.subclase_id)AND(P.clase_id=S.clase_id)AND(P.grupo_id=S.grupo_id)
                        JOIN inv_clases_inventarios C ON (S.clase_id=C.clase_id) AND (S.grupo_id=C.grupo_id) ";
                    if(!empty($clase))
                    {
                      $sql .= " AND (C.clase_id='".trim($clase)."') "; 
                    }
                    $sql .= "  JOIN  existencias_bodegas EXI ON(A.codigo_producto=EXI.codigo_producto) 
					AND (EXI.empresa_id='".trim($empresa)."' )
					AND (EXI.centro_utilidad='".trim($centro_utilidad)."')
					AND (EXI.bodega='".trim($bodega)."') ";

            $sql .= " 
                      GROUP BY     A.empresa_id,
                                         A.periodo,
                                         A.codigo_producto,
                                         A.ingreso,
                                         A.egreso,
                                         C.clase_id,
                                         C.descripcion,
                                         EXI.existencia
					
					 UNION
SELECT
			EXI.empresa_id,
			'' as periodo,
			EXI.codigo_producto,
			0 as ingreso,
			0 as egreso,
			T.clase_id,
            T.descripcion as descripcion,
			fc_descripcion_producto(EXI.codigo_producto) as producto,
			EXI.existencia
			FROM
			existencias_bodegas as EXI
			JOIN inventarios_productos as P ON (EXI.codigo_producto = P.codigo_producto)
			AND (EXI.empresa_id='".trim($empresa)."' )
			AND (EXI.centro_utilidad='".trim($centro_utilidad)."')
			AND (EXI.bodega='".trim($bodega)."')
    		JOIN inv_subclases_inventarios S ON (P.subclase_id=S.subclase_id)
			AND(P.clase_id=S.clase_id)
			AND(P.grupo_id=S.grupo_id)
			JOIN inv_clases_inventarios as T ON (S.clase_id = T.clase_id)
			AND (S.grupo_id = T.grupo_id)
			JOIN inv_grupos_inventarios as G ON (T.grupo_id = G.grupo_id)
			WHERE TRUE
			".$filtro2."
			AND (G.sw_medicamento='1')
			AND (EXI.existencia >0)
			GROUP BY     
			EXI.empresa_id,
			periodo,
			EXI.codigo_producto,
			ingreso,
			egreso,
			T.clase_id,
            T.descripcion,
			EXI.existencia
			ORDER BY        descripcion ";
		
		

        if(!$rst = $this->ConexionBaseDatos($sql))	return false;
        $datos = array();
        while (!$rst->EOF)
        {
         
          $medicamentos[$rst->fields[2]] [$rst->fields[6]]  [$rst->fields[7]] [$rst->fields[1]]  = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        return $medicamentos;
     }

    /* Function que permite obtener los laboratorios para los medicamentos
     * @return array $datos vector que contiene la informacion de la consulta.
     */
        function Consultar_Laboratorios($tipo_empresa)
        {
	     
                $sql = " SELECT clase_id,
                                descripcion
                     FROM   inv_clases_inventarios
                     group by clase_id,descripcion
                     order by descripcion 
                     ";
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
    /* Function que permite obtener Las Moleculas del sistemas
     * @return array $datos vector que contiene la informacion de la consulta.
     */
        function Consultar_Moleculas()
        {
	     
					$sql = "SELECT
					a.cod_principio_activo,
					a.descripcion||'-'||a.cod_principio_activo as descripcion
					FROM
					inv_med_cod_principios_activos as a
					ORDER BY descripcion;";
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
	    //* Function que permite obtener lo solicitado por generencia
       /*  * @return array $datos vector que contiene la informacion de la consulta./*/
	   function solcitud_Gerencia_($datos_empresa,$medicamento,$cantidad)
		{
		 
            $this->ConexionTransaccion();
			
			$sql  = "INSERT INTO solicitud_gerencia( 
									solictud_gerencia_id,
									empresa_id,
									centro_utilidad,
									bodega,
									codigo_producto,
									cantidad,
									usuario_id
									
						)VALUES( 
						         nextval('solicitud_gerencia_solictud_gerencia_id_seq'),
								'".$datos_empresa['empresa_id']."', 
								'".$datos_empresa['centro_utilidad']."', 
								'".$datos_empresa['bodega']."', 
								'".$medicamento."', 
								".$cantidad.", 
								".UserGetUID()."
							  ) ";
             
					if(!$rst = $this->ConexionTransaccion($sql))
					{
					return false;
					}
					$this->Commit();
					return true;
		}
	 
	      /*
		* Funcion  Consultar los productos  para generar la rotacion por medicamentos
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
   	function  RotacionFinalProducto($fechaI,$fechaF,$empresa,$bodega,$centro_utilidad)
     {
 
    
        $sql = "   SELECT 
                                    A.empresa_id,
                                    A.periodo,
                                    A.codigo_producto,
                                    A.ingreso,
                                    A.egreso,
                                    S.subclase_id,
                                    S.descripcion as descripcion,
                                    fc_descripcion_producto(A.codigo_producto) as producto,
                                    EXI.existencia
                    FROM (
                                  SELECT
                                                EMP.empresa_id,
                                                CASE
                                                WHEN E.periodo IS NULL
                                                THEN I.periodo
                                                ELSE E.periodo
                                                END AS periodo,
                                                CASE
                                                WHEN E.codigo_producto IS NULL
                                                THEN I.codigo_producto
                                                ELSE E.codigo_producto
                                                END AS codigo_producto,
                                                COALESCE(SUM(I.total_ingreso),0)AS ingreso,
                                                COALESCE(SUM(E.total_egreso),0)AS egreso
                                    FROM
                                              (
                                                      SELECT
                                                                e.codigo_producto,
                                                                b.empresa_id,
                                                                TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo,
                                                                SUM(e.cantidad) as total_egreso
                                                      FROM
                                                                    inv_bodegas_movimiento as a
                                                                    JOIN inv_bodegas_documentos as b ON(a.documento_id = b.documento_id)
                                                                    AND (a.empresa_id = b.empresa_id)
                                                                    AND (a.centro_utilidad = b.centro_utilidad)
                                                                    AND (a.bodega = b.bodega)
                                                                    JOIN documentos as c ON (b.documento_id = c.documento_id)
                                                                    AND (b.empresa_id = c.empresa_id) 
                                                                    JOIN tipos_doc_generales AS d ON (c.tipo_doc_general_id = d.tipo_doc_general_id)
                                                                    AND (d.inv_tipo_movimiento IN('E'))
                                                                    JOIN inv_bodegas_movimiento_d as e ON (a.empresa_id = e.empresa_id)
                                                                    AND (a.prefijo = e.prefijo)
                                                                    AND (a.numero = e.numero)
																	
																	JOIN inventarios_productos as f ON (e.codigo_producto = f.codigo_producto)
																	JOIN inv_subclases_inventarios as g ON (f.grupo_id = g.grupo_id)
																	AND (f.clase_id = g.clase_id)
																	AND (f.subclase_id = g.subclase_id)
																	JOIN inv_clases_inventarios as h ON (g.grupo_id = h.grupo_id)
																	AND (g.clase_id = h.clase_id)
																	JOIN inv_grupos_inventarios as i ON(h.grupo_id = i.grupo_id)
                                                WHERE TRUE
                                                AND (c.tipo_doc_general_id not in (SELECT tipo_doc_general_id FROM Inv_Documentos_Rotacion WHERE empresa_id='".trim($empresa)."' AND tipo_doc_general_id = c.tipo_doc_general_id)) 
												AND a.fecha_registro::date >= '".$this->DividirFecha(trim($fechaI),'-')."' ::date
												AND a.fecha_registro::date <= '".$this->DividirFecha(trim($fechaF),'-')."' ::date
                                                AND a.empresa_id = '".trim($empresa)."' 
                                                AND a.bodega='".trim($bodega)."' 
                                                AND a.centro_utilidad='".trim($centro_utilidad)."'
												AND i.sw_medicamento = '1'
                                                GROUP BY  e.codigo_producto,
                                                              TO_CHAR(a.fecha_registro,'YYYY-MM'),
                                                              b.empresa_id
                                                UNION
                                                SELECT
                                                    c.codigo_producto,
                                                    b.empresa_id,
                                                    TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo,
                                                    SUM(c.cantidad) as total_egreso
                                                FROM
                                                    bodegas_documentos as a
                                                    JOIN bodegas_doc_numeraciones as b ON (a.bodegas_doc_id = b.bodegas_doc_id)
                                                    AND (b.tipo_movimiento IN('E'))
                                                    JOIN bodegas_documentos_d as c ON (a.bodegas_doc_id = c.bodegas_doc_id)
                                                    AND (a.numeracion = c.numeracion)
													
													JOIN inventarios_productos as d ON (c.codigo_producto = d.codigo_producto)
													JOIN inv_subclases_inventarios as e ON (d.grupo_id = e.grupo_id)
													AND (d.clase_id = e.clase_id)
													AND (d.subclase_id = e.subclase_id)
													JOIN inv_clases_inventarios as f ON (e.grupo_id = f.grupo_id)
													AND (e.clase_id = f.clase_id)
													JOIN inv_grupos_inventarios as g ON(f.grupo_id = g.grupo_id)
                                                WHERE TRUE
                                                    AND a.fecha_registro::date >= '".$this->DividirFecha(trim($fechaI),'-')."' ::date
													AND a.fecha_registro::date <= '".$this->DividirFecha(trim($fechaF),'-')."' ::date
                                                    AND b.empresa_id = '".trim($empresa)."' 
                                                      AND b.bodega='".trim($bodega)."' 
                                                    AND b.centro_utilidad='".trim($centro_utilidad)."'
													AND g.sw_medicamento = '1'
                                               GROUP BY  c.codigo_producto,
                                                              TO_CHAR(a.fecha_registro,'YYYY-MM'),
                                                              b.empresa_id
                                  ) AS E
                                FULL OUTER JOIN
                                (
                                SELECT
                                          e.codigo_producto,
                                          b.empresa_id,
                                          TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo,
                                          SUM(e.cantidad) as total_ingreso
                                FROM
                                                inv_bodegas_movimiento as a
                                                JOIN inv_bodegas_documentos as b ON(a.documento_id = b.documento_id)
                                                AND (a.empresa_id = b.empresa_id)
                                                AND (a.centro_utilidad = b.centro_utilidad)
                                                AND (a.bodega = b.bodega)
                                                JOIN documentos as c ON (b.documento_id = c.documento_id)
                                                AND (b.empresa_id = c.empresa_id) 
                                                JOIN tipos_doc_generales AS d ON (c.tipo_doc_general_id = d.tipo_doc_general_id)
                                                AND (d.inv_tipo_movimiento IN('I'))
                                                JOIN inv_bodegas_movimiento_d as e ON (a.empresa_id = e.empresa_id)
                                                AND (a.prefijo = e.prefijo)
                                                AND (a.numero = e.numero)
												
												JOIN inventarios_productos as f ON (e.codigo_producto = f.codigo_producto)
												JOIN inv_subclases_inventarios as g ON (f.grupo_id = g.grupo_id)
												AND (f.clase_id = g.clase_id)
												AND (f.subclase_id = g.subclase_id)
												JOIN inv_clases_inventarios as h ON (g.grupo_id = h.grupo_id)
												AND (g.clase_id = h.clase_id)
												JOIN inv_grupos_inventarios as i ON(h.grupo_id = i.grupo_id)
                                WHERE TRUE
                                            AND (c.tipo_doc_general_id not in (SELECT tipo_doc_general_id FROM Inv_Documentos_Rotacion WHERE empresa_id='".$empresa."' AND tipo_doc_general_id = c.tipo_doc_general_id)) 
											AND a.fecha_registro::date >= '".$this->DividirFecha(trim($fechaI),'-')."' ::date
											AND a.fecha_registro::date <= '".$this->DividirFecha(trim($fechaF),'-')."' ::date
                                            AND a.empresa_id = '".trim($empresa)."' 
                                            AND a.bodega='".trim($bodega)."' 
                                            AND a.centro_utilidad='".trim($centro_utilidad)."'
											AND i.sw_medicamento = '1'
                                  GROUP BY  e.codigo_producto,
                                            TO_CHAR(a.fecha_registro,'YYYY-MM'),
                                            b.empresa_id
					) AS I ON (E.codigo_producto = I.codigo_producto)
					AND (E.periodo = I.periodo)
					AND (E.empresa_id = I.empresa_id)
					LEFT JOIN empresas AS EMP ON (E.empresa_id = EMP.empresa_id)
					OR (I.empresa_id = EMP.empresa_id)
          WHERE TRUE
                  GROUP BY E.periodo,I.periodo,I.codigo_producto, E.codigo_producto,EMP.empresa_id
                  ORDER BY EMP.empresa_id,E.periodo,I.periodo,I.codigo_producto
        )AS A
          JOIN inventarios_productos P ON (A.codigo_producto=P.codigo_producto)
          JOIN inv_subclases_inventarios S ON (P.subclase_id=S.subclase_id)AND(P.clase_id=S.clase_id)AND(P.grupo_id=S.grupo_id)
          JOIN  existencias_bodegas EXI ON(A.codigo_producto=EXI.codigo_producto) 
		  AND (EXI.empresa_id='".trim($empresa)."' )
		  AND (EXI.centro_utilidad='".trim($centro_utilidad)."')
		  AND (EXI.bodega='".trim($bodega)."')
          GROUP BY     A.empresa_id,
                          A.periodo,
                         A.codigo_producto,
                         A.ingreso,
                         A.egreso,
                         S.subclase_id,
                         S.descripcion,
                         EXI.existencia
           UNION
SELECT
			EXI.empresa_id,
			'' as periodo,
			EXI.codigo_producto,
			0 as ingreso,
			0 as egreso,
			S.subclase_id,
			S.descripcion as descripcion,
			fc_descripcion_producto(EXI.codigo_producto) as producto,
			EXI.existencia
			FROM
			existencias_bodegas as EXI
			JOIN inventarios_productos as P ON (EXI.codigo_producto = P.codigo_producto)
			AND (EXI.empresa_id='".trim($empresa)."' )
			AND (EXI.centro_utilidad='".trim($centro_utilidad)."')
			AND (EXI.bodega='".trim($bodega)."')
    		JOIN inv_subclases_inventarios S ON (P.subclase_id=S.subclase_id)
			AND(P.clase_id=S.clase_id)
			AND(P.grupo_id=S.grupo_id)
			JOIN inv_clases_inventarios as T ON (S.clase_id = T.clase_id)
			AND (S.grupo_id = T.grupo_id)
			JOIN inv_grupos_inventarios as G ON (T.grupo_id = G.grupo_id)
			WHERE TRUE
			AND (G.sw_medicamento='1')
			AND (EXI.existencia >0)
			GROUP BY     
			EXI.empresa_id,
			periodo,
			EXI.codigo_producto,
			ingreso,
			egreso,
			S.subclase_id,
			S.descripcion,
			EXI.existencia
			ORDER BY   descripcion,producto,existencia ";
			
			/*print_r($sql);*/
        if(!$rst = $this->ConexionBaseDatos($sql))	return false;
        $datos = array();
        while (!$rst->EOF)
        {
         
          $medicamentos[$rst->fields[2]] [$rst->fields[6]]  [$rst->fields[7]] [$rst->fields[1]]  = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        return $medicamentos;
        
	}
	/**/
	
	function Solicitudes_Generadas_x_Rotacion($datos_empresa)
	{
	   
		$sql = " SELECT 	g.codigo_producto,sum(g.cantidad),
							fc_descripcion_producto(g.codigo_producto) as producto
				FROM    	solicitud_gerencia g
				WHERE  		g.empresa_id = '".$datos_empresa['empresa_id']."'
				AND   		g.centro_utilidad ='".$datos_empresa['centro_utilidad']."'
				AND  		g.bodega ='".$datos_empresa['bodega']."'
				AND  		g.estado = '0'
				GROUP BY g.codigo_producto ";
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
  
	   /*/*/
	    function  Eliminar_Cantidad_Solicitudes($datos_empresa,$producto)
		{
  		
  			$this->ConexionTransaccion();
  			$sql  = " 	DELETE  FROM  solicitud_gerencia
						WHERE  		  empresa_id='".$datos_empresa['empresa_id']."'
						AND    		  centro_utilidad='".$datos_empresa['centro_utilidad']."'
						AND	   		  bodega ='".$datos_empresa['bodega']."'		
						AND           codigo_producto='".$producto."' 
						AND           usuario_id=".UserGetUID()."
                        AND           estado = '0'				";
						

		  			if(!$rst1 = $this->ConexionTransaccion($sql))
		  			{
		  			return false;
		  			}

		  			$this->Commit();
		  			return $datos;
		}
     /*
		* Funcion  Consultar los insumos   para generar la rotacion por insumos
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
     function  RotacionInsumos($fechaI,$fechaF,$empresa,$bodega,$centro_utilidad,$grupo_id)
    {

        $sql = " SELECT A.empresa_id,
                                A.periodo,
                                A.codigo_producto,
                                A.ingreso,
                                A.egreso,
                                G.grupo_id,
                                G.descripcion,
                                fc_descripcion_producto(A.codigo_producto) as producto,
                                EXI.existencia
                    FROM ( 
                              SELECT 	EMP.empresa_id,
                                            CASE WHEN E.periodo IS NULL
                                            THEN I.periodo 
                                            ELSE E.periodo 
                                            END AS periodo,
                                            CASE WHEN E.codigo_producto IS NULL 
                                            THEN I.codigo_producto 
                                            ELSE E.codigo_producto 
                                            END AS codigo_producto,
                                            COALESCE(SUM(I.total_ingreso),0)AS ingreso, 
                                            COALESCE(SUM(E.total_egreso),0)AS egreso 
                              FROM (
                                              SELECT 	e.codigo_producto, 
                                                            b.empresa_id, 
                                                            TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo, 
                                                            SUM(e.cantidad) as total_egreso 
                                              FROM      inv_bodegas_movimiento as a
                                                            JOIN inv_bodegas_documentos as b ON(a.documento_id = b.documento_id) 
                                                            AND (a.empresa_id = b.empresa_id) 
                                                            AND (a.centro_utilidad = b.centro_utilidad) 
                                                            AND (a.bodega = b.bodega)
                                                            JOIN documentos as c ON (b.documento_id = c.documento_id)
                                                            AND (b.empresa_id = c.empresa_id)   
                                                            JOIN tipos_doc_generales AS d ON (c.tipo_doc_general_id = d.tipo_doc_general_id)
                                                            AND (d.inv_tipo_movimiento IN('E'))
                                                            JOIN inv_bodegas_movimiento_d as e ON (a.empresa_id = e.empresa_id)
                                                            AND (a.prefijo = e.prefijo) 
                                                            AND (a.numero = e.numero) 
															
															JOIN inventarios_productos as f ON (e.codigo_producto = f.codigo_producto)
															JOIN inv_subclases_inventarios as g ON (f.grupo_id = g.grupo_id)
															AND (f.clase_id = g.clase_id)
															AND (f.subclase_id = g.subclase_id)
															JOIN inv_clases_inventarios as h ON (g.grupo_id = h.grupo_id)
															AND (g.clase_id = h.clase_id)
															JOIN inv_grupos_inventarios as i ON(h.grupo_id = i.grupo_id)
                                              WHERE TRUE 
                                                          AND (c.tipo_doc_general_id not in (SELECT tipo_doc_general_id FROM Inv_Documentos_Rotacion WHERE empresa_id='".trim($empresa)."' AND tipo_doc_general_id = c.tipo_doc_general_id)) 
														  AND a.fecha_registro::date >= '".$this->DividirFecha(trim($fechaI),'-')."' ::date
														  AND a.fecha_registro::date <= '".$this->DividirFecha(trim($fechaF),'-')."' ::date
                                                          AND a.empresa_id = '".trim($empresa)."' 
                                                          AND a.bodega='".trim($bodega)."' 
                                                          AND a.centro_utilidad='".trim($centro_utilidad)."'
														  AND i.sw_insumos = '1'
								                          GROUP BY    e.codigo_producto, 
                                                          TO_CHAR(a.fecha_registro,'YYYY-MM'), 
                                                          b.empresa_id
                                          UNION 
																	SELECT    c.codigo_producto, 
																	b.empresa_id, 
																	TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo,
																	SUM(c.cantidad) as total_egreso 
																	FROM         bodegas_documentos as a 
																	JOIN bodegas_doc_numeraciones as b 
																	ON (a.bodegas_doc_id = b.bodegas_doc_id) 
																	AND (b.tipo_movimiento IN('E')) 
																	JOIN bodegas_documentos_d as c ON (a.bodegas_doc_id = c.bodegas_doc_id) 
																	AND (a.numeracion = c.numeracion) 
																	  
																	JOIN inventarios_productos as d ON (c.codigo_producto = d.codigo_producto)
																	JOIN inv_subclases_inventarios as e ON (d.grupo_id = e.grupo_id)
																	AND (d.clase_id = e.clase_id)
																	AND (d.subclase_id = e.subclase_id)
																	JOIN inv_clases_inventarios as f ON (e.grupo_id = f.grupo_id)
																	AND (e.clase_id = f.clase_id)
																	JOIN inv_grupos_inventarios as g ON(f.grupo_id = g.grupo_id)
                                                      WHERE TRUE 
                                                                      AND a.fecha_registro::date >= '".$this->DividirFecha(trim($fechaI),'-')."' ::date
																	  AND a.fecha_registro::date <= '".$this->DividirFecha(trim($fechaF),'-')."' ::date
                                                                      AND b.empresa_id ='".trim($empresa)."'
                                                                      AND b.bodega='".trim($bodega)."' 
                                                                      AND b.centro_utilidad='".trim($centro_utilidad)."' 
																	  AND g.sw_insumos = '1'
																		GROUP  BY  c.codigo_producto, 
                                                                      TO_CHAR(a.fecha_registro,'YYYY-MM'), 
                                                                        b.empresa_id
                                      ) AS E 
                                  FULL OUTER 
                                  JOIN (
                                                SELECT 	  e.codigo_producto,
                                                                b.empresa_id,
                                                                TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo,
                                                                SUM(e.cantidad) as total_ingreso
                                                  FROM      inv_bodegas_movimiento as a 
                                                                JOIN inv_bodegas_documentos as b ON(a.documento_id = b.documento_id)
                                                                AND (a.empresa_id = b.empresa_id)
                                                                AND (a.centro_utilidad = b.centro_utilidad) 
                                                                AND (a.bodega = b.bodega)
                                                                JOIN documentos as c ON (b.documento_id = c.documento_id) 
                                                                AND (b.empresa_id = c.empresa_id)   
                                                                JOIN tipos_doc_generales AS d ON (c.tipo_doc_general_id = d.tipo_doc_general_id)
                                                                AND (d.inv_tipo_movimiento IN('I')) 
                                                                JOIN inv_bodegas_movimiento_d as e ON (a.empresa_id = e.empresa_id)
                                                                AND (a.prefijo = e.prefijo) 
                                                                AND (a.numero = e.numero) 
																
																JOIN inventarios_productos as f ON (e.codigo_producto = f.codigo_producto)
																JOIN inv_subclases_inventarios as g ON (f.grupo_id = g.grupo_id)
																AND (f.clase_id = g.clase_id)
																AND (f.subclase_id = g.subclase_id)
																JOIN inv_clases_inventarios as h ON (g.grupo_id = h.grupo_id)
																AND (g.clase_id = h.clase_id)
																JOIN inv_grupos_inventarios as i ON(h.grupo_id = i.grupo_id)
                                                  WHERE TRUE 
                                                              AND (c.tipo_doc_general_id not in (SELECT tipo_doc_general_id FROM Inv_Documentos_Rotacion WHERE empresa_id='".trim($empresa)."' AND tipo_doc_general_id = c.tipo_doc_general_id)) 
															  AND a.fecha_registro::date >= '".$this->DividirFecha(trim($fechaI),'-')."' ::date
															  AND a.fecha_registro::date <= '".$this->DividirFecha(trim($fechaF),'-')."' ::date
                                                              AND a.empresa_id = '".trim($empresa)."'
                                                              AND a.bodega='".trim($bodega)."' 
                                                              AND a.centro_utilidad='".trim($centro_utilidad)."' 
															  AND i.sw_insumos = '1'
                                              GROUP BY e.codigo_producto, 
                                                              TO_CHAR(a.fecha_registro,'YYYY-MM'),
                                                              b.empresa_id 
                                                
                                      )   AS I 
                                                            ON (E.codigo_producto = I.codigo_producto)
                                                            AND (E.periodo = I.periodo) 
                                                            AND (E.empresa_id = I.empresa_id) 
                                                            LEFT JOIN empresas AS EMP ON (E.empresa_id = EMP.empresa_id)
                                                            OR (I.empresa_id = EMP.empresa_id) 
                                        WHERE TRUE 
                                        GROUP BY E.periodo,
                                                        I.periodo,
                                                        I.codigo_producto, 
                                                        E.codigo_producto,
                                                        EMP.empresa_id
                                        ORDER BY  EMP.empresa_id,
                                                          E.periodo,
                                                          I.periodo,
                                                          I.codigo_producto
                      )AS A 
                   JOIN inventarios_productos P ON (A.codigo_producto=P.codigo_producto) 
                   JOIN inv_subclases_inventarios S ON (P.subclase_id=S.subclase_id)
                   AND(P.clase_id=S.clase_id)
                   AND(P.grupo_id=S.grupo_id)
                   JOIN inv_clases_inventarios C ON (S.clase_id=C.clase_id) AND (S.grupo_id=C.grupo_id)
                   JOIN inv_grupos_inventarios G  ON (C.grupo_id=G.grupo_id) AND (G.sw_insumos='1') ";
                   
                if(!empty($grupo_id))
              {
                $sql .= " AND (G.grupo_id='".trim($grupo_id)."') "; 
              }
              $sql .= "  JOIN existencias_bodegas EXI ON(A.codigo_producto=EXI.codigo_producto)
               AND (EXI.empresa_id='".trim($empresa)."' )
               AND (EXI.centro_utilidad='".trim($centro_utilidad)."')
               AND (EXI.bodega='".trim($bodega)."') 
			 
             GROUP BY 	A.empresa_id, 
                  A.periodo,
                  A.codigo_producto, 
                  A.ingreso,
                  A.egreso,
                  G.grupo_id, 
                  G.descripcion, 
                  EXI.existencia 
				  
			UNION
			SELECT
			EXI.empresa_id,
			'' as periodo,
			EXI.codigo_producto,
			0 as ingreso,
			0 as egreso,
			G.grupo_id, 
            G.descripcion as descripcion,
			fc_descripcion_producto(EXI.codigo_producto) as producto,
			EXI.existencia
			FROM
			existencias_bodegas as EXI
			JOIN inventarios_productos as P ON (EXI.codigo_producto = P.codigo_producto)
			AND (EXI.empresa_id='".trim($empresa)."' )
			AND (EXI.centro_utilidad='".trim($centro_utilidad)."')
			AND (EXI.bodega='".trim($bodega)."')
    		JOIN inv_subclases_inventarios S ON (P.subclase_id=S.subclase_id)
			AND(P.clase_id=S.clase_id)
			AND(P.grupo_id=S.grupo_id)
			JOIN inv_clases_inventarios as T ON (S.clase_id = T.clase_id)
			AND (S.grupo_id = T.grupo_id)
			JOIN inv_grupos_inventarios as G ON (T.grupo_id = G.grupo_id)
			WHERE TRUE
			AND (G.sw_insumos='1')
			AND (EXI.existencia >0)
			GROUP BY     
			EXI.empresa_id,
			periodo,
			EXI.codigo_producto,
			ingreso,
			egreso,
			G.grupo_id, 
            G.descripcion, 
			EXI.existencia
				  
            ORDER BY producto   ";

	
        if(!$rst = $this->ConexionBaseDatos($sql))	return false;
        $datos = array();
        while (!$rst->EOF)
        {
         
            $medicamentos[$rst->fields[2]]  [$rst->fields[7]] [$rst->fields[1]]  = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        return $medicamentos;
        
	}
		
     
   
     /*
		* Funcion  Consultar todas las farmacias 
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		
		function  Consulta_Farmacias()
		{
			
				$sql = " SELECT  	empresa_id,
									razon_social
						FROM 		empresas
						WHERE 		sw_tipo_empresa = '1'
                        ORDER BY    razon_social ";					
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
	     /*
		* Funcion  Consultar el total de las existencias por farmacia y medicamento 
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function Consulta_Total_Existencias($farmacia,$codigo_producto,$bodega_id)
		{
			
			
		
			$sql = " SELECT  SUM(existencia) AS Existencias
					FROM 	existencias_bodegas
					WHERE 	empresa_id = '".$farmacia."' 
					AND     codigo_producto='".$codigo_producto."' ";
					
					
					if(!empty($bodega_id))
					 {
								 
						$sql .= " AND  bodega='".$bodega_id."' ";
									
					  }
						
					
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
		
	     /*
		* Funcion  Consultar todos los productos que han tenido movimiento en un periodo determinado
		* @return array $datos vector que contiene la informacion de la consulta.*/
		function Productos_Con_Mto($fechaI,$fechaF)
		{
		   
		   $sql = " SELECT 				RE.codigo_producto, 
										fc_descripcion_producto(RE.codigo_producto) as producto,
										p.sw_generico, 
										SUM(RE.cantidad_ingreso) AS ingreso,
										SUM(RE.cantidad_egreso) AS egreso 
					FROM      existencias_bodegas AS e 
					JOIN      rotacion_producto_x_empresa RE ON 
          (e.empresa_id = RE.empresa_id 
          AND e.codigo_producto = RE.codigo_producto 
          AND e.centro_utilidad = RE.centro_utilidad 
          AND e.bodega = RE.bodega)
					JOIN      inventarios i ON (RE.codigo_producto = i.codigo_producto) and (RE.empresa_id = i.empresa_id)
					JOIN      inventarios_productos p ON (i.codigo_producto = p.codigo_producto)	
					JOIN      inv_subclases_inventarios s ON (p.grupo_id=s.grupo_id AND p.clase_id=s.clase_id AND p.subclase_id=s.subclase_id ) 				
					JOIN      inv_clases_inventarios c ON (s.grupo_id=c.grupo_id AND s.clase_id=c.clase_id)				
				   JOIN       unidades u ON (p.unidad_id=u.unidad_id)			
							
										
					WHERE 			
							RE.fecha >=  '".$fechaI." 00:00:00' and  RE.fecha <= '".$fechaF." 24:00:00'
						
							GROUP BY RE.codigo_producto, 
							p.descripcion,
							p.subclase_id,
							p.sw_generico
							HAVING SUM(RE.cantidad_ingreso)>= 0 
							AND SUM(RE.cantidad_egreso)>0 
							ORDER BY 	p.descripcion   "; 
							
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
     /*
		* Funcion  Consultar  la rotacion general por farmacia
		* @return array $datos vector que contiene la informacion de la consulta.
	*/

	/*function  Rotacion_General_x_farmacia($fechaI,$fechaF,$farmacia,$medicamento)
    {
       //  $this->debug=true;
		 $sql = " 	 SELECT TO_CHAR(RE.fecha,'YYYY-MM') AS  fecha_registro,
															SUM(RE.cantidad_ingreso) AS ingreso,
															SUM(RE.cantidad_egreso) AS egreso
										FROM 				rotacion_producto_x_empresa RE
										JOIN          		inventarios INV ON (RE.empresa_id=INV.empresa_id and RE.codigo_producto=INV.codigo_producto)
										JOIN				inventarios_productos PRO ON(INV.codigo_producto=PRO.codigo_producto)
										JOIN				inv_subclases_inventarios SUB ON (PRO.grupo_id=SUB.grupo_id 
										AND                 PRO.clase_id=SUB.clase_id
										AND                 PRO.subclase_id=SUB.subclase_id)
																		
															
															
															
										WHERE 				RE.empresa_id = '".$farmacia."'
										AND RE.fecha >=  '".$fechaI." 00:00:00' and  RE.fecha <= '".$fechaF." 24:00:00'
									    AND                 RE.codigo_producto='".$medicamento."'
									
				    					GROUP BY 			1,
										                    RE.codigo_producto,
															PRO.descripcion,
															RE.cantidad_ingreso,
															RE.cantidad_egreso
										HAVING SUM(RE.cantidad_ingreso)>=0
										AND SUM(RE.cantidad_egreso)>0
										ORDER BY 	fecha_registro, 
													RE.codigo_producto,
													PRO.descripcion ";
				if(!$rst = $this->ConexionBaseDatos($sql))	return false;
		        $datos = array();
		        while (!$rst->EOF)
		        {
		         
		          $medicamentos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
		          $rst->MoveNext();
		        }
		        $rst->Close();
		        return $medicamentos;
		        
	}*/
  function  Rotacion_General_x_farmacia($fechaI,$fechaF,$farmacia,$medicamento)
    {
 
	    $sql = " SELECT A.empresa_id,
					A.periodo,
					A.codigo_producto,
					A.ingreso,
					A.egreso,
					fc_descripcion_producto(A.codigo_producto) as producto,
					EXI.existencia
FROM ( SELECT 	EMP.empresa_id,
							CASE WHEN E.periodo IS NULL
							THEN I.periodo 
							ELSE E.periodo 
							END AS periodo,
							CASE WHEN E.codigo_producto IS NULL 
							THEN I.codigo_producto 
							ELSE E.codigo_producto 
							END AS codigo_producto,
							COALESCE(SUM(I.total_ingreso),0)AS ingreso, 
							COALESCE(SUM(E.total_egreso),0)AS egreso 
							FROM (SELECT 	e.codigo_producto, 
											b.empresa_id, 
											TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo, 
											SUM(e.cantidad) as total_egreso 
								  FROM      inv_bodegas_movimiento as a
											  JOIN inv_bodegas_documentos as b ON(a.documento_id = b.documento_id) 
											  AND (a.empresa_id = b.empresa_id) 
											  AND (a.centro_utilidad = b.centro_utilidad) 
											  AND (a.bodega = b.bodega)
											  JOIN documentos as c ON (b.documento_id = c.documento_id)
											  AND (b.empresa_id = c.empresa_id)   AND (c.tipo_doc_general_id not in (SELECT tipo_doc_general_id FROM Inv_Documentos_Rotacion WHERE empresa_id='".$empresa."'))
											  JOIN tipos_doc_generales AS d ON (c.tipo_doc_general_id = d.tipo_doc_general_id)
											  AND (d.inv_tipo_movimiento IN('E'))
											  JOIN inv_bodegas_movimiento_d as e ON (a.empresa_id = e.empresa_id)
											  AND (a.prefijo = e.prefijo) 
											  AND (a.numero = e.numero) 
								  WHERE TRUE 
											AND a.fecha_registro::date >= '".$fechaI."' ::date
											AND a.fecha_registro::date <= '".$fechaF."' ::date
											AND a.empresa_id = '".$farmacia."' 
											AND e.codigo_producto='".$medicamento."'
								  group by e.codigo_producto, 
								  TO_CHAR(a.fecha_registro,'YYYY-MM'), 
								  b.empresa_id
					 UNION 
					 SELECT c.codigo_producto, 
							b.empresa_id, 
							TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo,
							SUM(c.cantidad) as total_egreso 
					FROM bodegas_documentos as a 
					JOIN bodegas_doc_numeraciones as b 
					ON (a.bodegas_doc_id = b.bodegas_doc_id) 
					AND (b.tipo_movimiento IN('E')) 
					JOIN bodegas_documentos_d as c ON (a.bodegas_doc_id = c.bodegas_doc_id) 
					AND (a.numeracion = c.numeracion) 
					WHERE TRUE 
					AND a.fecha_registro::date >= '".$fechaI."' ::date
					AND a.fecha_registro::date <= '".$fechaF."' ::date
					AND b.empresa_id ='".$farmacia."'
					AND c.codigo_producto='".$medicamento."'
					group by c.codigo_producto, 
							TO_CHAR(a.fecha_registro,'YYYY-MM'), 
							b.empresa_id ) AS E 
				FULL OUTER 
				JOIN (
						SELECT 	e.codigo_producto,
								b.empresa_id,
								TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo,
								SUM(e.cantidad) as total_ingreso
								FROM inv_bodegas_movimiento as a 
								JOIN inv_bodegas_documentos as b ON(a.documento_id = b.documento_id)
								AND (a.empresa_id = b.empresa_id)
								AND (a.centro_utilidad = b.centro_utilidad) 
								AND (a.bodega = b.bodega)
								JOIN documentos as c ON (b.documento_id = c.documento_id) 
								AND (b.empresa_id = c.empresa_id)   AND (c.tipo_doc_general_id not in (SELECT tipo_doc_general_id FROM Inv_Documentos_Rotacion WHERE empresa_id='".$empresa."'))
								JOIN tipos_doc_generales AS d ON (c.tipo_doc_general_id = d.tipo_doc_general_id)
								AND (d.inv_tipo_movimiento IN('I')) 
								JOIN inv_bodegas_movimiento_d as e ON (a.empresa_id = e.empresa_id)
								AND (a.prefijo = e.prefijo) 
								AND (a.numero = e.numero) 
						WHERE TRUE 
						AND a.fecha_registro::date >= '".$fechaI."' ::date
						AND a.fecha_registro::date <= '".$fechaF."' ::date 
						AND a.empresa_id = '".$farmacia."'
						AND e.codigo_producto='".$medicamento."'
						
						group by e.codigo_producto, 
						TO_CHAR(a.fecha_registro,'YYYY-MM'),
						b.empresa_id 
					UNION 
					SELECT 	c.codigo_producto,
							b.empresa_id, 
							TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo, 
							SUM(c.cantidad) as total_ingreso 
					FROM    bodegas_documentos as a
					JOIN    bodegas_doc_numeraciones as b ON (a.bodegas_doc_id = b.bodegas_doc_id) 
					AND (b.tipo_movimiento IN('I')) 
					JOIN bodegas_documentos_d as c ON (a.bodegas_doc_id = c.bodegas_doc_id) 
					AND (a.numeracion = c.numeracion)
					WHERE TRUE 
					AND a.fecha_registro::date >='".$fechaI."' ::date
					AND a.fecha_registro::date <= '".$fechaF."' ::date 
					AND b.empresa_id ='".$farmacia."'
					AND c.codigo_producto='".$medicamento."'
					group by c.codigo_producto, 
							TO_CHAR(a.fecha_registro,'YYYY-MM'), b.empresa_id ) AS I 
							ON (E.codigo_producto = I.codigo_producto)
							AND (E.periodo = I.periodo) 
							AND (E.empresa_id = I.empresa_id) 
							LEFT JOIN empresas AS EMP ON (E.empresa_id = EMP.empresa_id)
							OR (I.empresa_id = EMP.empresa_id) 
							WHERE TRUE 
							GROUP BY E.periodo,
									I.periodo,
									I.codigo_producto, 
									E.codigo_producto,
									EMP.empresa_id
							ORDER BY  EMP.empresa_id,
									  E.periodo,
									  I.periodo,
									  I.codigo_producto
)AS A 
			 JOIN inventarios_productos P ON (A.codigo_producto=P.codigo_producto) 
		    JOIN existencias_bodegas EXI ON(A.codigo_producto=EXI.codigo_producto)
			 AND (EXI.empresa_id='".$farmacia."' )
			 
			 GROUP BY 	A.empresa_id, 
						A.periodo,
						A.codigo_producto, 
						A.ingreso,
						A.egreso,
						EXI.existencia 
						ORDER BY producto   ";

		if(!$rst = $this->ConexionBaseDatos($sql))	return false;
        $datos = array();
        while (!$rst->EOF)
        {
         
            $medicamentos[$rst->fields[1]]  = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        return $medicamentos;
        
		        
	}
     /*
		* Funcion  Consultar la rotacion por tipo de insumo
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	function  RotacionInsumos_x_Tipo_()
	{
          $sql .= " SELECT  grupo_id,descripcion
                        FROM    inv_grupos_inventarios
                        WHERE    sw_insumos = '1'  ";
	
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
	/*  Funcion que me trae los productos que tengan o no existencias en la empresa */
	function  Productos_por_empresa_conMTO($empresa)
	{
	  
	   
	    $sql .= " SELECT  codigo_producto,
		                  fc_descripcion_producto(codigo_producto) as producto,
		                  existencia
		          FROM    inventarios
				  WHERE    empresa_id = '".$empresa."' order by existencia desc,producto  ";
	
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
	   /*
		* Funcion  Consultar la rotacion por molecula
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	 function  RotacionMoleculas($fechaI,$fechaF,$empresa,$bodega,$centro_utilidad,$subclase_id=null)
    {
 
        if($subclase_id!="")
		{
		
		if($subclase_id!="-1")
		{
		$filtro_1="	AND  (g.subclase_id = '".$subclase_id."')	";
		$filtro_2="	AND  (e.subclase_id = '".$subclase_id."')	";
		$filtro_3="	AND  (P.subclase_id = '".$subclase_id."')	";
		}
		
		$sql = "   SELECT A.empresa_id,
                                  A.periodo,
                                  SUM(A.ingreso)AS ingreso,
                                  SUM(A.egreso) AS egreso,
                                  S.subclase_id,
                                  S.descripcion||'|'|| upper(replace( P.contenido_unidad_venta, ' ', ''))||'|'||upper(U.descripcion) AS molecula,
                                  P.sw_generico,
                                  U.unidad_id,
                                  SUM(EXI.existencia) as existencia
                       FROM 
                      (
                            SELECT 	EMP.empresa_id,
                                          CASE WHEN E.periodo IS NULL
                                          THEN I.periodo 
                                          ELSE E.periodo 
                                          END AS periodo,
                                          CASE WHEN E.codigo_producto IS NULL 
                                          THEN I.codigo_producto 
                                          ELSE E.codigo_producto 
                                          END AS codigo_producto,
                                          COALESCE(SUM(I.total_ingreso),0)AS ingreso, 
                                          COALESCE(SUM(E.total_egreso),0)AS egreso 
                          FROM 
                          (
                                SELECT 	e.codigo_producto, 
                                              b.empresa_id, 
                                              TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo, 
                                              SUM(e.cantidad) as total_egreso 
                                FROM 
                                            inv_bodegas_movimiento as a
                                            JOIN inv_bodegas_documentos as b ON(a.documento_id = b.documento_id) 
                                            AND (a.empresa_id = b.empresa_id) 
                                            AND (a.centro_utilidad = b.centro_utilidad) 
                                            AND (a.bodega = b.bodega)
                                            JOIN documentos as c ON (b.documento_id = c.documento_id)
                                            AND (b.empresa_id = c.empresa_id)  
                                            JOIN tipos_doc_generales AS d ON (c.tipo_doc_general_id = d.tipo_doc_general_id)
                                            AND (d.inv_tipo_movimiento IN('E'))
                                            JOIN inv_bodegas_movimiento_d as e ON (a.empresa_id = e.empresa_id)
                                            AND (a.prefijo = e.prefijo) 
                                            AND (a.numero = e.numero) 
											
											JOIN inventarios_productos as f ON (e.codigo_producto = f.codigo_producto)
											JOIN inv_subclases_inventarios as g ON (f.grupo_id = g.grupo_id)
											AND (f.clase_id = g.clase_id)
											AND (f.subclase_id = g.subclase_id)
											".$filtro_1."
											JOIN inv_clases_inventarios as h ON (g.grupo_id = h.grupo_id)
											AND (g.clase_id = h.clase_id)
											JOIN inv_grupos_inventarios as i ON(h.grupo_id = i.grupo_id)
                            WHERE TRUE 
											AND (c.tipo_doc_general_id not in (SELECT tipo_doc_general_id FROM Inv_Documentos_Rotacion WHERE empresa_id='".trim($empresa)."' AND tipo_doc_general_id = c.tipo_doc_general_id)) 
                                            AND a.fecha_registro::date >= '".$this->DividirFecha(trim($fechaI),'-')."' ::date
											AND a.fecha_registro::date <= '".$this->DividirFecha(trim($fechaF),'-')."' ::date
                                            AND a.empresa_id = '".trim($empresa)."' 
                                            AND a.bodega='".trim($bodega)."' 
                                            AND a.centro_utilidad='".trim($centro_utilidad)."'
											AND i.sw_medicamento = '1'
                            GROUP BY  e.codigo_producto, 
                                            TO_CHAR(a.fecha_registro,'YYYY-MM'), 
                                            b.empresa_id
                            UNION 
                            SELECT c.codigo_producto, 
                                        b.empresa_id, 
                                        TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo,
                                        SUM(c.cantidad) as total_egreso 
                              FROM bodegas_documentos as a 
                                        JOIN bodegas_doc_numeraciones as b 
                                      ON (a.bodegas_doc_id = b.bodegas_doc_id) 
                                      AND (b.tipo_movimiento IN('E')) 
                                      JOIN bodegas_documentos_d as c ON (a.bodegas_doc_id = c.bodegas_doc_id) 
                                      AND (a.numeracion = c.numeracion) 
									  
									JOIN inventarios_productos as d ON (c.codigo_producto = d.codigo_producto)
									JOIN inv_subclases_inventarios as e ON (d.grupo_id = e.grupo_id)
									AND (d.clase_id = e.clase_id)
									AND (d.subclase_id = e.subclase_id)
									".$filtro_2."
									JOIN inv_clases_inventarios as f ON (e.grupo_id = f.grupo_id)
									AND (e.clase_id = f.clase_id)
									JOIN inv_grupos_inventarios as g ON(f.grupo_id = g.grupo_id)
                             WHERE TRUE 
									
                                    AND a.fecha_registro::date >= '".$this->DividirFecha(trim($fechaI),'-')."' ::date
									AND a.fecha_registro::date <= '".$this->DividirFecha(trim($fechaF),'-')."' ::date
                                    AND b.empresa_id ='".trim($empresa)."'
                                    AND b.bodega='".trim($bodega)."' 
                                    AND b.centro_utilidad='".trim($centro_utilidad)."' 
									AND g.sw_medicamento = '1'
                              GROUP BY c.codigo_producto, 
                                            TO_CHAR(a.fecha_registro,'YYYY-MM'), 
                                            b.empresa_id
                        ) AS E 
                      FULL OUTER 
                      JOIN (
                              SELECT 	e.codigo_producto,
                                            b.empresa_id,
                                            TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo,
                                            SUM(e.cantidad) as total_ingreso
                                FROM inv_bodegas_movimiento as a 
                                          JOIN inv_bodegas_documentos as b ON(a.documento_id = b.documento_id)
                                          AND (a.empresa_id = b.empresa_id)
                                          AND (a.centro_utilidad = b.centro_utilidad) 
                                          AND (a.bodega = b.bodega)
                                          JOIN documentos as c ON (b.documento_id = c.documento_id) 
                                          AND (b.empresa_id = c.empresa_id)   
                                          JOIN tipos_doc_generales AS d ON (c.tipo_doc_general_id = d.tipo_doc_general_id)
                                          AND (d.inv_tipo_movimiento IN('I')) 
                                          JOIN inv_bodegas_movimiento_d as e ON (a.empresa_id = e.empresa_id)
                                          AND (a.prefijo = e.prefijo) 
                                          AND (a.numero = e.numero) 
										  
											JOIN inventarios_productos as f ON (e.codigo_producto = f.codigo_producto)
											JOIN inv_subclases_inventarios as g ON (f.grupo_id = g.grupo_id)
											AND (f.clase_id = g.clase_id)
											AND (f.subclase_id = g.subclase_id)
											".$filtro_1."
											JOIN inv_clases_inventarios as h ON (g.grupo_id = h.grupo_id)
											AND (g.clase_id = h.clase_id)
											JOIN inv_grupos_inventarios as i ON(h.grupo_id = i.grupo_id)
                              WHERE TRUE 
							  AND (c.tipo_doc_general_id not in (SELECT tipo_doc_general_id FROM Inv_Documentos_Rotacion WHERE empresa_id='".trim($empresa)."' AND tipo_doc_general_id = c.tipo_doc_general_id)) 
                              AND a.fecha_registro::date >= '".$this->DividirFecha(trim($fechaI),'-')."' ::date
							  AND a.fecha_registro::date <= '".$this->DividirFecha(trim($fechaF),'-')."' ::date
                              AND a.empresa_id = '".trim($empresa)."'
                              AND a.bodega='".trim($bodega)."' 
                              AND a.centro_utilidad='".trim($centro_utilidad)."' 
							  AND i.sw_medicamento = '1'
                              GROUP  BY e.codigo_producto, 
                                          TO_CHAR(a.fecha_registro,'YYYY-MM'),
                                          b.empresa_id 
                    
                            ) AS I 
                            ON (E.codigo_producto = I.codigo_producto)
                            AND (E.periodo = I.periodo) 
                            AND (E.empresa_id = I.empresa_id) 
                            LEFT JOIN empresas AS EMP ON (E.empresa_id = EMP.empresa_id)
                            OR (I.empresa_id = EMP.empresa_id) 
							WHERE TRUE 
							GROUP BY E.periodo,
									I.periodo,
									I.codigo_producto, 
									E.codigo_producto,
									EMP.empresa_id
							ORDER BY  EMP.empresa_id,
									  E.periodo,
									  I.periodo,
									  I.codigo_producto
)AS A 
			 JOIN inventarios_productos P ON (A.codigo_producto=P.codigo_producto) 
			 
			 JOIN inv_subclases_inventarios S ON (P.subclase_id=S.subclase_id)
			 AND(P.clase_id=S.clase_id)
			 AND(P.grupo_id=S.grupo_id)
			JOIN unidades U ON(P.unidad_id=U.unidad_id)
			JOIN existencias_bodegas EXI ON(A.codigo_producto=EXI.codigo_producto)
			 AND (EXI.empresa_id='".trim($empresa)."' )
			 AND (EXI.centro_utilidad='".trim($centro_utilidad)."')
			 AND (EXI.bodega='".trim($bodega)."') 
        GROUP BY 	A.empresa_id, 
        A.periodo,
        S.subclase_id,
        P.sw_generico,
        S.descripcion,
        upper(replace(P.contenido_unidad_venta, ' ', '')),
        upper(U.descripcion),
		U.unidad_id
		
		UNION
		SELECT
					EXI.empresa_id,
					'' AS periodo,
					0 AS ingreso,
					0 AS egreso,
					S.subclase_id,
					S.descripcion||'|'|| upper(replace( P.contenido_unidad_venta, ' ', ''))||'|'||U.descripcion AS molecula,
					P.sw_generico,
					U.unidad_id,
					SUM(COALESCE(EXI.existencia,0)) as existencia
					FROM
					existencias_bodegas as EXI
					JOIN inventarios_productos as P ON (EXI.codigo_producto = P.codigo_producto)
					AND (EXI.empresa_id='".trim($empresa)."' )
					AND (EXI.centro_utilidad='".trim($centro_utilidad)."')
					AND (EXI.bodega='".trim($bodega)."')
					
					 JOIN inv_subclases_inventarios S ON (P.subclase_id=S.subclase_id)
					AND(P.clase_id=S.clase_id)
					AND(P.grupo_id=S.grupo_id)
					JOIN inv_clases_inventarios as C ON (S.clase_id = C.clase_id)
					AND (S.grupo_id = C.grupo_id)
					JOIN inv_grupos_inventarios as G ON (C.grupo_id = G.grupo_id)
					JOIN unidades as U ON (P.unidad_id = U.unidad_id)
					WHERE TRUE
					".$filtro_3."
					AND G.sw_medicamento = '1'
					AND (EXI.existencia >0)
		GROUP BY  EXI.empresa_id, 
						periodo,
						S.subclase_id,
				    	P.sw_generico,
						S.descripcion,
						 upper(replace(P.contenido_unidad_venta, ' ', '')),
						 U.descripcion,
						 U.unidad_id
        ORDER BY molecula,existencia   ";
	}
/*print_r($sql);*/
        if(!$rst = $this->ConexionBaseDatos($sql))	return false;
        $datos = array();
        while (!$rst->EOF)
        {
         
            $medicamentos[$rst->fields[4]]  [$rst->fields[5]] [$rst->fields[1]]  = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        return $medicamentos;
        
	}
	/*
		* Funcion  Consultar la rotacion del producto que tiene asociada una molecula escogida en la rotacion por molecula 
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	function  RotacionProductos_Moleculas($fechaI,$fechaF,$empresa,$bodega,$centro_utilidad,$subclase,$descripcion)
    {

        /*$sql = " SELECT 	A.codigo_producto,
                                  SUM(A.ingreso) AS ingreso,
                                  SUM(A.egreso) AS egreso,
                                  EXI.existencia,
                                  fc_descripcion_producto(A.codigo_producto) as producto
                        FROM ( SELECT 	EMP.empresa_id,
                                                  CASE WHEN E.periodo IS NULL
                                                  THEN I.periodo 
                                                  ELSE E.periodo 
                                                  END AS periodo,
                                                  CASE WHEN E.codigo_producto IS NULL 
                                                  THEN I.codigo_producto 
                                                  ELSE E.codigo_producto 
                                                  END AS codigo_producto,
                                                  COALESCE(SUM(I.total_ingreso),0)AS ingreso, 
                                                  COALESCE(SUM(E.total_egreso),0)AS egreso 
                                  FROM (SELECT 	e.codigo_producto, 
                                                          b.empresa_id, 
                                                          TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo, 
                                                          SUM(e.cantidad) as total_egreso 
                                            FROM      inv_bodegas_movimiento as a
                                                          JOIN inv_bodegas_documentos as b ON(a.documento_id = b.documento_id) 
                                                          AND (a.empresa_id = b.empresa_id) 
                                                          AND (a.centro_utilidad = b.centro_utilidad) 
                                                          AND (a.bodega = b.bodega)
                                                          JOIN documentos as c ON (b.documento_id = c.documento_id)
                                                          AND (b.empresa_id = c.empresa_id)   AND (c.tipo_doc_general_id not in (SELECT tipo_doc_general_id FROM Inv_Documentos_Rotacion WHERE empresa_id='".$empresa."'))
                                                          JOIN tipos_doc_generales AS d ON (c.tipo_doc_general_id = d.tipo_doc_general_id)
                                                          AND (d.inv_tipo_movimiento IN('E'))
                                                          JOIN inv_bodegas_movimiento_d as e ON (a.empresa_id = e.empresa_id)
                                                          AND (a.prefijo = e.prefijo) 
                                                          AND (a.numero = e.numero) 
                                        WHERE TRUE 
                                                        AND a.fecha_registro::date >= '".$fechaI."' ::date
                                                        AND a.fecha_registro::date <= '".$fechaF."' ::date
                                                        AND a.empresa_id = '".trim($empresa)."' 
                                                        AND a.bodega='".trim($bodega)."' 
                                                        AND a.centro_utilidad='".trim($centro_utilidad)."'
                                      GROUP BY  e.codigo_producto, 
                                                      TO_CHAR(a.fecha_registro,'YYYY-MM'), 
                                                      b.empresa_id
                                    UNION 
                                    SELECT c.codigo_producto, 
                                                b.empresa_id, 
                                                TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo,
                                                SUM(c.cantidad) as total_egreso 
                                     FROM bodegas_documentos as a 
                                              JOIN bodegas_doc_numeraciones as b 
                                              ON (a.bodegas_doc_id = b.bodegas_doc_id) 
                                              AND (b.tipo_movimiento IN('E')) 
                                              JOIN bodegas_documentos_d as c ON (a.bodegas_doc_id = c.bodegas_doc_id) 
                                              AND (a.numeracion = c.numeracion) 
                                    WHERE TRUE 
                                          AND a.fecha_registro::date >= '".$fechaI."' ::date
                                          AND a.fecha_registro::date <= '".$fechaF."' ::date
                                          AND b.empresa_id ='".trim($empresa)."'
                                          AND b.bodega='".trim($bodega)."' 
                                          AND b.centro_utilidad='".trim($centro_utilidad)."' 
                                       GROUP BY  c.codigo_producto, 
                                                        TO_CHAR(a.fecha_registro,'YYYY-MM'), 
                                                        b.empresa_id 
                                                        ) AS E 
                                    FULL OUTER 
                                    JOIN (
                                              SELECT 	e.codigo_producto,
                                                            b.empresa_id,
                                                            TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo,
                                                            SUM(e.cantidad) as total_ingreso
                                            FROM inv_bodegas_movimiento as a 
                                                      JOIN inv_bodegas_documentos as b ON(a.documento_id = b.documento_id)
                                                      AND (a.empresa_id = b.empresa_id)
                                                      AND (a.centro_utilidad = b.centro_utilidad) 
                                                      AND (a.bodega = b.bodega)
                                                      JOIN documentos as c ON (b.documento_id = c.documento_id) 
                                                      AND (b.empresa_id = c.empresa_id)   AND (c.tipo_doc_general_id not in (SELECT tipo_doc_general_id FROM Inv_Documentos_Rotacion WHERE empresa_id='".$empresa."'))
                                                      JOIN tipos_doc_generales AS d ON (c.tipo_doc_general_id = d.tipo_doc_general_id)
                                                      AND (d.inv_tipo_movimiento IN('I')) 
                                                      JOIN inv_bodegas_movimiento_d as e ON (a.empresa_id = e.empresa_id)
                                                      AND (a.prefijo = e.prefijo) 
                                                      AND (a.numero = e.numero) 
                                        WHERE TRUE 
                                                    AND a.fecha_registro::date >= '".$fechaI."' ::date
                                                    AND a.fecha_registro::date <= '".$fechaF."' ::date 
                                                    AND a.empresa_id = '".trim($empresa)."'
                                                    AND a.bodega='".trim($bodega)."' 
                                                    AND a.centro_utilidad='".trim($centro_utilidad)."' 
                                              GROUP BY e.codigo_producto, 
                                                            TO_CHAR(a.fecha_registro,'YYYY-MM'),
                                                            b.empresa_id 
                                UNION 
                                        SELECT 	c.codigo_producto,
                                                      b.empresa_id, 
                                                      TO_CHAR(a.fecha_registro,'YYYY-MM') as periodo, 
                                                      SUM(c.cantidad) as total_ingreso 
                                        FROM    bodegas_documentos as a
                                                    JOIN    bodegas_doc_numeraciones as b ON (a.bodegas_doc_id = b.bodegas_doc_id) 
                                                    AND (b.tipo_movimiento IN('I')) 
                                                    JOIN bodegas_documentos_d as c ON (a.bodegas_doc_id = c.bodegas_doc_id) 
                                                    AND (a.numeracion = c.numeracion)
                                        WHERE TRUE 
                                                  AND a.fecha_registro::date >='".$fechaI."' ::date
                                                  AND a.fecha_registro::date <= '".$fechaF."' ::date 
                                                  AND b.empresa_id ='".trim($empresa)."'
                                                  AND b.bodega='".trim($bodega)."' 
                                                  AND b.centro_utilidad='".trim($centro_utilidad)."' 
				                         GROUP BY c.codigo_producto, 
                                                TO_CHAR(a.fecha_registro,'YYYY-MM'), b.empresa_id ) AS I 
                                                ON (E.codigo_producto = I.codigo_producto)
                                                AND (E.periodo = I.periodo) 
                                                AND (E.empresa_id = I.empresa_id) 
                                                LEFT JOIN empresas AS EMP ON (E.empresa_id = EMP.empresa_id)
                                                OR (I.empresa_id = EMP.empresa_id) 
                                WHERE TRUE 
                                GROUP BY E.periodo,
                                I.periodo,
                                I.codigo_producto, 
                                E.codigo_producto,
                                EMP.empresa_id
                                ORDER BY  EMP.empresa_id,
                                E.periodo,
                                I.periodo,
                                I.codigo_producto)AS A 
                                JOIN inventarios_productos P ON (A.codigo_producto=P.codigo_producto) 
                                JOIN  inv_subclases_inventarios S ON(P.grupo_id=S.grupo_id)
								AND(P.clase_id=S.clase_id)
								AND(P.subclase_id=S.subclase_id) ";
			 
			$sql .= "  JOIN existencias_bodegas EXI ON(A.codigo_producto=EXI.codigo_producto)
			 AND (EXI.empresa_id='".trim($empresa)."' )
			 AND (EXI.centro_utilidad='".trim($centro_utilidad)."')
			 AND (EXI.bodega='".trim($bodega)."')
      WHERE   P.subclase_id = '".$subclase."'
			 AND     S.descripcion||'  |  '|| upper(replace(P.contenido_unidad_venta, ' ', ''))='".$descripcion."'
			 GROUP BY 	
					A.codigo_producto,
          EXI.existencia
				 ";*/
        if(!$rst = $this->ConexionBaseDatos($sql))	return false;
        $datos = array();
        while (!$rst->EOF)
        {
         
          $medicamentos[]  = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();
        return $medicamentos;
        
	}
	
	function Insertar_SolicitudGerencia($datos_empresa,$molecula_id,$descripcion_completa,$unidad_id,$sw_generico,$cantidad)
	{
	 $this->ConexionTransaccion(); 
	$producto=explode("|",$descripcion_completa);
	$descripcion=$producto[0];
	$concentracion=$producto[1];
	$unidad_descripcion=$producto[2];
	/*$this->debug=true;*/
	$sql = " 	INSERT INTO solicitud_gerencia
					(
					solictud_gerencia_id,
					empresa_id,
					centro_utilidad,
					bodega,
					cantidad,
					usuario_id,
					cod_principio_activo,
					unidad_id,
					concentracion,
					sw_generico
					)
					VALUES
					(
					DEFAULT,
					'".trim($datos_empresa['empresa_id'])."',
					'".trim($datos_empresa['centro_utilidad'])."',
					'".trim($datos_empresa['bodega'])."',
					'".trim($cantidad)."',
					'".UserGetUID()."',
					'".trim($molecula_id)."',
					'".trim($unidad_id)."',
					'".trim($concentracion)."',
					'".trim($sw_generico)."'
					);";
	
		if(!$rst = $this->ConexionTransaccion($sql))
		{
		return false;
		}
		$this->Commit();
		return true;
	}
	
	
	function Modificar_SolicitudGerencia($datos_empresa,$molecula_id,$descripcion_completa,$unidad_id,$sw_generico,$cantidad)
	{
	$producto=explode("|",$descripcion_completa);
	$descripcion=$producto[0];
	$concentracion=$producto[1];
	$unidad_descripcion=$producto[2];
	
	
	$sql .= "	UPDATE solicitud_gerencia
					SET
					cantidad = '".trim($cantidad)."'
					WHERE TRUE
					AND cantidad <> '".trim($cantidad)."'
					AND empresa_id ='".trim($datos_empresa['empresa_id'])."'
					AND centro_utilidad = '".trim($datos_empresa['centro_utilidad'])."'
					AND bodega = '".trim($datos_empresa['bodega'])."' 
					AND cod_principio_activo = '".trim($molecula_id)."' 
					AND unidad_id = '".trim($unidad_id)."' 
					AND concentracion = '".trim($concentracion)."' ;";
					
	$sql .= "	DELETE FROM solicitud_gerencia
					WHERE TRUE
					AND cantidad <=0
					AND empresa_id ='".trim($datos_empresa['empresa_id'])."'
					AND centro_utilidad = '".trim($datos_empresa['centro_utilidad'])."'
					AND bodega = '".trim($datos_empresa['bodega'])."' 
					AND cod_principio_activo = '".trim($molecula_id)."' 
					AND unidad_id = '".trim($unidad_id)."' 
					AND concentracion = '".trim($concentracion)."' ;";
	return $sql;
	}
	
	function Solicitud_GerenciaMoleculas($datos_empresa)
	{
	
	$sql .= "	SELECT
	b.descripcion||'|'|| upper(replace(a.concentracion, ' ', ''))||'|'||upper(c.descripcion) AS molecula,
	a.cod_principio_activo,
	a.cantidad,
	a.sw_generico,
	a.unidad_id,
	a.empresa_id,
	a.centro_utilidad,
	a.bodega
	FROM
	solicitud_gerencia AS a
	JOIN inv_med_cod_principios_activos as b ON (a.cod_principio_activo = b.cod_principio_activo)
	JOIN unidades as c ON (a.unidad_id = c.unidad_id) 
	WHERE TRUE
	AND empresa_id ='".trim($datos_empresa['empresa_id'])."'
	AND centro_utilidad = '".trim($datos_empresa['centro_utilidad'])."'
	AND bodega = '".trim($datos_empresa['bodega'])."' 
	order by molecula,a.sw_generico;";
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
	
	
	/* Function Ejecutar_ConsultasMoleculas 
     * @return array $datos 
     */
        function Ejecutar_Consultas($sql)
        {
		
	    $this->ConexionTransaccion(); 
		if(!$rst = $this->ConexionTransaccion($sql))
		{
		return false;
		}
		$this->Commit();
		return true;
	   }
	
	    /**
    *
    */
    function  ObtenerCentrosUtilidad($empresa_id)
		{
			$sql  = "SELECT empresa_id,";
      $sql .= "				centro_utilidad,";
      $sql .= " 	    descripcion ";
      $sql .= "FROM		centros_utilidad ";
      $sql .= "WHERE	empresa_id = '".$empresa_id."' ";
      $sql .= "ORDER BY	descripcion ";
      
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
    /**
    *
    */
    function ObtenerProductosExistencias($bodega,$fechai,$fechaf,$empresa_id,$centros)
    {
      $centros_utilidad = "";
      foreach($centros as $key => $dtl)
        $centros_utilidad .= (($centros_utilidad == "")? "":",")."'".$key."'";
      
      $sql  = "SELECT A.codigo_producto, ";
      $sql .= "       fc_descripcion_producto(A.codigo_producto) AS descripcion_producto, ";
      $sql .= "       SUM(egr) AS egr, ";
      $sql .= "       SUM(igr) AS igr, ";
      $sql .= "       SUM(existencia) AS existencia, ";
      $sql .= "       IC.descripcion ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT IR.codigo_producto, ";
      $sql .= "                SUM(IR.cantidad_egreso) AS egr, ";
      $sql .= "                SUM(IR.cantidad_ingreso) AS igr, ";
      $sql .= "                0 AS existencia ";
      $sql .= "         FROM   inv_rotaciones_detalle IR ";
      $sql .= "         WHERE  IR.fecha >= '".$this->DividirFecha($fechai,"-")."'::date ";
      $sql .= "         AND    IR.fecha <= '".$this->DividirFecha($fechaf,'-')."'::date ";
      $sql .= "         AND    IR.empresa_id = '".$empresa_id."' ";
      $sql .= "         AND    IR.centro_utilidad IN (".$centros_utilidad.") ";
      $sql .= "         GROUP BY IR.codigo_producto ";
      $sql .= "         UNION ALL ";
      $sql .= "         SELECT DISTINCT EB.codigo_producto,  ";
      $sql .= "                0 AS egr, ";
      $sql .= "                0 AS igr, ";
      $sql .= "                EB.existencia ";
      $sql .= "         FROM   existencias_bodegas EB , ";
      $sql .= "                inventarios_productos IV, ";
      $sql .= "                inv_grupos_inventarios GI ";
      $sql .= "         WHERE  EB.empresa_id = '".$bodega['empresa_id']."' ";
      $sql .= "         AND    EB.centro_utilidad = '".$bodega['centro_utilidad']."' ";
      $sql .= "         AND    EB.bodega = '".$bodega['bodega']."' ";
      $sql .= "         AND    EB.codigo_producto = IV.codigo_producto ";
      $sql .= "         AND    IV.grupo_id = GI.grupo_id ";
      $sql .= "         AND    GI.sw_medicamento = '1' ";
      $sql .= "       ) A, ";
      $sql .= "       inventarios_productos IV, ";
      $sql .= "       inv_clases_inventarios IC  ";
      $sql .= "WHERE  A.codigo_producto = IV.codigo_producto ";
      $sql .= "AND    IV.clase_id = IC.clase_id ";
      $sql .= "AND    IV.grupo_id = IC.grupo_id ";
      $sql .= "GROUP BY 1,2,6 ";
      $sql .= "ORDER BY 6 "; 
      	  
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
    /**
    *
    */
    function ObtenerRotacionXBodega($centro,$empresa_id,$fechai,$fechaf)
    {
      $sql  = "SELECT  A.centro_utilidad, ";
      $sql .= "        A.codigo_producto, ";
      $sql .= "        TO_CHAR(a.fecha, 'YYYY-MM') AS periodo, ";
      $sql .= "        SUM(A.cantidad_egreso) AS cnt_egreso, ";
      $sql .= "        SUM(A.cantidad_ingreso) AS cnt_ingreso, ";
      $sql .= "        EB.existencia ";
      $sql .= "FROM    inv_rotaciones_detalle A  ";
      $sql .= "        LEFT JOIN inv_documentos_rotacion dr ";
      $sql .= "        ON( A.tipo_doc_general_id = dr.tipo_doc_general_id AND ";
      $sql .= "            dr.empresa_id = '".$empresa_id."'), ";
      $sql .= "        existencias_bodegas EB ";
      $sql .= "WHERE   dr.tipo_doc_general_id IS NULL ";
      $sql .= "AND     a.fecha >= '".$this->DividirFecha($fechai,'-')."'::date ";
      $sql .= "AND     a.fecha <= '".$this->DividirFecha($fechaf,'-')."'::date ";
      $sql .= "AND     a.empresa_id = '".$empresa_id."' ";
      $sql .= "AND     a.centro_utilidad='".$centro."' ";
      $sql .= "AND     a.empresa_id = EB.empresa_id ";
      $sql .= "AND     a.centro_utilidad = EB.centro_utilidad ";
      $sql .= "AND     a.bodega = EB.bodega ";
      $sql .= "AND     a.codigo_producto = EB.codigo_producto ";
      $sql .= "GROUP BY 1,2,3,6 ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[$rst->fields[2]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;	
    }    
    /**
    *
    */
    /*function ObtenerRotacionXLaboratorio($clase_id,$centro_utilidad,$bodega,$empresa_id,$fechai,$fechaf)
    {     
      $sql  = "SELECT EB.empresa_id,";
      $sql .= "       TO_CHAR(a.fecha, 'YYYY-MM') AS periodo,";
      $sql .= "       EB.codigo_producto,";
      $sql .= "       SUM(COALESCE(A.cantidad_ingreso,0)) AS ingreso,";
      $sql .= "       SUM(COALESCE(A.cantidad_egreso,0)) AS egreso,";
      $sql .= "       IC.clase_id,";
      $sql .= "       IC.descripcion, ";
      $sql .= "       fc_descripcion_producto(EB.codigo_producto) AS producto, ";
      $sql .= "       EB.existencia  ";
      $sql .= "FROM   existencias_bodegas EB";
      $sql .= "       LEFT JOIN inv_rotaciones_detalle A  ";
      $sql .= "       ON( EB.empresa_id = A.empresa_id AND";
      $sql .= "           EB.centro_utilidad = A.centro_utilidad AND";
      $sql .= "           EB.bodega = A.bodega AND";
      $sql .= "           EB.codigo_producto = A.codigo_producto AND";
      $sql .= "           a.fecha >= '".$this->DividirFecha($fechai,'-')."'::date AND  ";
      $sql .= "           a.fecha <= '".$this->DividirFecha($fechaf,'-')."'::date AND";
      $sql .= "           a.empresa_id = '".$empresa_id."'   AND";
      $sql .= "           a.centro_utilidad='".$centro_utilidad."' AND  ";
      $sql .= "           a.bodega='".$bodega."'  ";
      $sql .= "         ),";
      $sql .= "       inventarios_productos IV,  ";
      $sql .= "       inv_clases_inventarios IC,  ";
      $sql .= "       inv_grupos_inventarios IG  ";
      $sql .= "WHERE   EB.empresa_id = '".$empresa_id."'";
      $sql .= "AND     EB.centro_utilidad='".$centro_utilidad."'  ";
      $sql .= "AND     EB.bodega='".$bodega."'  ";
      $sql .= "AND     EB.codigo_producto = IV.codigo_producto  ";
	 // $sql .= "AND (c.tipo_doc_general_id not in (SELECT tipo_doc_general_id FROM Inv_Documentos_Rotacion WHERE empresa_id='".$empresa."' AND tipo_doc_general_id = c.tipo_doc_general_id))";
      if($clase_id != "-1" && $clase_id)
        $sql .= "AND     IV.clase_id = ".$clase_id."  ";
      $sql .= "AND     IV.clase_id = IC.clase_id  ";
      $sql .= "AND     IV.grupo_id = IC.grupo_id  ";
      $sql .= "AND     IC.grupo_id = IG.grupo_id ";
      $sql .= "AND     IG.sw_medicamento = '1'  ";
      $sql .= "GROUP BY 1,2,3,6,7,8,9  ";
      $sql .= "ORDER BY 7,8 ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[$rst->fields[2]] [$rst->fields[6]]  [$rst->fields[7]] [$rst->fields[1]]  = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;	
    }*/
	function ObtenerRotacionXLaboratorio($clase_id,$centro_utilidad,$bodega,$empresa_id,$fechai,$fechaf)
    {   
	$sql  = "SELECT EB.empresa_id, 
              TO_CHAR(a.fecha, 'YYYY-MM') AS periodo, 
              EB.codigo_producto, 
              SUM(COALESCE(A.cantidad_ingreso,0)) AS ingreso, 
              SUM(COALESCE(A.cantidad_egreso,0)) AS egreso, 
              IC.clase_id, 
              IC.descripcion,  
              fc_descripcion_producto(EB.codigo_producto) AS producto,  
              EB.existencia   
              FROM   existencias_bodegas EB 
              leFT JOIN inv_rotaciones_detalle A   
              ON( EB.empresa_id = A.empresa_id AND 
                  EB.centro_utilidad = A.centro_utilidad AND 
                  EB.bodega = A.bodega AND 
                  EB.codigo_producto = A.codigo_producto AND 
				  a.fecha >= '".$this->DividirFecha($fechai,'-')."'::date AND  
                  a.fecha <= '".$this->DividirFecha($fechaf,'-')."'::date AND
                  a.empresa_id = '".$empresa_id."'  
				 AND  a.centro_utilidad='".$centro_utilidad."' AND   
                  a.bodega='".$bodega."'    
               AND (A.tipo_doc_general_id not in (SELECT tipo_doc_general_id FROM Inv_Documentos_Rotacion WHERE empresa_id='".$empresa_id."'  AND tipo_doc_general_id = A.tipo_doc_general_id)) 
                ), 
              inventarios_productos F, 					
		 
              inv_clases_inventarios IC,   
              inv_grupos_inventarios IG   
       WHERE   EB.empresa_id = '".$empresa_id."'  
       AND     EB.centro_utilidad='".$centro_utilidad."'  
       AND     EB.bodega= '".$empresa_id."'  
       AND     EB.codigo_producto = F.codigo_producto ";  
        if($clase_id != "-1" && $clase_id)
            $sql .= "AND     F.clase_id = ".$clase_id."  ";
 	   $sql.="AND     F.clase_id = IC.clase_id   
   
       AND     F.grupo_id = IC.grupo_id   
       AND     IC.grupo_id = IG.grupo_id  
       AND     IG.sw_medicamento = '1'   
       GROUP BY 1,2,3,6,7,8,9   
       ORDER BY 7,8 ";
	   
	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[$rst->fields[2]] [$rst->fields[6]]  [$rst->fields[7]] [$rst->fields[1]]  = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;	
	
	}
    
    function InsertarSolicitud($datos_empresa,$codigo_producto,$valor)
		{
		
		$sql = "INSERT INTO solicitud_gerencia
				(
					empresa_id, 	
					centro_utilidad, 	
					bodega, 	
					codigo_producto, 	
					cantidad, 	
					usuario_id 	
				)
				VALUES
				(
					'".$datos_empresa['empresa_id']."',
					'".$datos_empresa['centro_utilidad']."',
					'".$datos_empresa['bodega']."',
					'".$codigo_producto."',
					".$valor.",
					".UserGetUID()."
				);";
		if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
		return true;
		}
	
    function ModificarSolicitud($datos_empresa,$codigo_producto,$valor)
		{
      $sql = "UPDATE solicitud_gerencia
              SET cantidad = ".$valor."
              WHERE
              empresa_id = '".$datos_empresa['empresa_id']."'
              AND centro_utilidad = '".$datos_empresa['centro_utilidad']."'
              AND bodega = '".$datos_empresa['bodega']."'
              AND codigo_producto = '".$codigo_producto."'
              AND usuario_id 	= ".UserGetUID().";";
      if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
		return true;
		}
    /**
    *
    */
    function IngresardatosRotacion($fechai,$fechaf,$actual)
    {
      $sql  = "DELETE FROM inv_rotaciones_detalle ";
      $sql .= "WHERE  fecha = '".$this->DividirFecha($fechai,'-')."'::date ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $sql  = "INSERT INTO inv_rotaciones_detalle ";
      $sql .= " ( ";
      $sql .= "   SELECT  a.empresa_id,";
      $sql .= "           a.centro_utilidad,";
      $sql .= "           a.bodega,";
      $sql .= "           e.codigo_producto,";
      $sql .= "           a.fecha_registro::date,";
      $sql .= "           d.tipo_doc_general_id,";
      $sql .= "           SUM(CASE WHEN d.inv_tipo_movimiento = 'E' THEN e.cantidad ELSE 0 END) AS cantidad_egreso,";
      $sql .= "           SUM(CASE WHEN d.inv_tipo_movimiento = 'I' THEN e.cantidad ELSE 0 END) AS cantidad_ingreso";
      $sql .= "   FROM    inv_bodegas_movimiento a,";
      $sql .= "           inv_bodegas_movimiento_d e,";
      $sql .= "           documentos c,";
      $sql .= "           inventarios_productos f,";
      $sql .= "           inv_grupos_inventarios i , ";
      $sql .= "           tipos_doc_generales d ";
      $sql .= "   WHERE   a.empresa_id = e.empresa_id ";
      $sql .= "   AND     a.prefijo = e.prefijo ";
      $sql .= "   AND     a.numero = e.numero ";
      $sql .= "   AND     a.documento_id = c.documento_id ";
      $sql .= "   AND     a.empresa_id = c.empresa_id ";
      $sql .= "   AND     c.tipo_doc_general_id = d.tipo_doc_general_id ";
      $sql .= "   AND     e.codigo_producto = f.codigo_producto ";
      $sql .= "   AND     f.grupo_id = I.grupo_id ";
      $sql .= "   AND     i.sw_medicamento = '1' ";
      if($actual)
        $sql .= "   AND     a.fecha_registro::date = '".$this->DividirFecha($fechai,'-')."'::date ";
      else
      {
        $sql .= "   AND     a.fecha_registro::date >= '".$this->DividirFecha($fechai,'-')."'::date ";
        $sql .= "   AND     a.fecha_registro::date <= '".$this->DividirFecha($fechaf,'-')."'::date ";
      }
      $sql .= "   GROUP BY 1,2,3,4,5,6 ";
      $sql .= " ) ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $sql  = "INSERT INTO inv_rotaciones_detalle ";
      $sql .= " ( ";
      $sql .= "   SELECT  b.empresa_id,";
      $sql .= "           b.centro_utilidad,";
      $sql .= "           b.bodega,";
      $sql .= "           c.codigo_producto,";
      $sql .= "           a.fecha_registro::date,";
      $sql .= "           '----' AS tipo_doc_general_id,";
      $sql .= "           SUM(c.cantidad) AS cantiad_egreso,";
      $sql .= "           0 AS cantidad_ingreso ";
      $sql .= "   FROM    bodegas_documentos a,";
      $sql .= "           bodegas_doc_numeraciones b,";
      $sql .= "           bodegas_documentos_d c,";
      $sql .= "           inventarios_productos d,";
      $sql .= "           inv_grupos_inventarios g ";
      $sql .= "   WHERE   a.bodegas_doc_id = b.bodegas_doc_id ";
      $sql .= "   AND     b.tipo_movimiento IN('E') ";
      $sql .= "   AND     a.bodegas_doc_id = c.bodegas_doc_id ";
      $sql .= "   AND     a.numeracion = c.numeracion ";
      $sql .= "   AND     c.codigo_producto = d.codigo_producto ";
      $sql .= "   AND     d.grupo_id = g.grupo_id ";
      $sql .= "   AND     g.sw_medicamento = '1' ";
      
      if($actual)
        $sql .= "   AND     a.fecha_registro::date = '".$this->DividirFecha($fechai,'-')."'::date ";
      else
      {
        $sql .= "   AND     a.fecha_registro::date >= '".$this->DividirFecha($fechai,'-')."'::date ";
        $sql .= "   AND     a.fecha_registro::date <= '".$this->DividirFecha($fechaf,'-')."'::date ";
      }
      $sql .= "   GROUP BY 1,2,3,4,5,6,8 ";
      $sql .= "   )";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      return true;
    }
	}
?>