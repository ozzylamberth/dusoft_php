<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: RotacionFarmaciaSQL.class.php,v 1.2 2010/01/14 22:49:02 sandra Exp $Revision: 1.2 $
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja Torres 
 */
class RotacionFarmaciaSQL extends ConexionBD {
    /*
     * Constructor de la clase
     */

    function RotacionFarmaciaSQL() {
        
    }

    /*
     * Funcion donde se Consultan el tipo identificacion
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarTipoId() {

        $sql = "SELECT    tipo_id_tercero, descripcion ";
        $sql .= "FROM      tipo_id_terceros ";
        $sql .= "ORDER BY  descripcion ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se Consultan la farmacia
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function FarmaciasRotacion($filtros, $offset) {

        $sql = " SELECT    empresa_id,tipo_id_tercero,id,razon_social,representante_legal,codigo_sgsss,direccion,telefonos,fax,sw_tipo_empresa,nivel_atencion ";
        $sql .= " FROM  empresas WHERE sw_tipo_empresa='1'  ";

        if ($filtros['tipo_id_tercero'] != "-1") {
            $sql.="  and tipo_id_tercero= '" . $filtros['tipo_id_tercero'] . "'  ";
        }
        if ($filtros['codigo'])
            $sql.=" and empresa_id= '" . $filtros['codigo'] . "' ";


        if ($filtros['id'])
            $sql.=" and id= '" . $filtros['id'] . "' ";
        if ($filtros['razon_social'] != "")
            $sql .= " AND     razon_social ILIKE '%" . $filtros['razon_social'] . "%' ";

        $cont = "select COUNT(*) from (" . $sql . ") AS A";
        $this->ProcesarSqlConteo($cont, $offset);
        $sql .= "ORDER BY razon_social ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion  Consultar los productos  para generar la rotacion por medicamentos
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function RotacionFinal($fechaI, $fechaF, $farmacia) {


        $sql = " SELECT TO_CHAR(RE.fecha,'YYYY-MM') AS fecha_registro, ";
        $sql .= " p.subclase_id,";
        $sql .= " s.descripcion as molecula, ";
        $sql .= " RE.empresa_id, ";
        $sql .= " RE.codigo_producto, ";
        $sql .= " p.descripcion AS producto, ";
        $sql .= " p.sw_generico, ";
        $sql .= " p.cantidad, ";
        $sql .= " e.existencia, ";
        $sql .=" c.clase_id,";
        $sql .= " c.descripcion as laboratorio, ";
        $sql .= " u.unidad_id, ";
        $sql .= " u.abreviatura AS unidad, ";
        $sql .= " RE.cantidad_inicial, ";
        $sql .= " SUM(RE.cantidad_ingreso) AS ingreso, ";
        $sql .= " SUM(RE.cantidad_egreso) AS egreso ";
        $sql .= "FROM rotacion_producto_x_empresa RE, ";
        $sql .= " existencias_bodegas AS e, ";
        $sql .= " inventarios i, ";
        $sql .= " inventarios_productos p, ";
        $sql .= " inv_subclases_inventarios s, ";
        $sql .= " inv_clases_inventarios c, ";
        $sql .= " unidades u ";
        $sql .= "WHERE e.empresa_id = RE.empresa_id ";
        $sql .= "AND e.codigo_producto = RE.codigo_producto ";
        $sql .= "AND e.centro_utilidad = RE.centro_utilidad ";
        $sql .= "AND e.codigo_producto = RE.codigo_producto ";
        $sql .= "AND e.bodega = RE.bodega ";
        $sql .= "AND RE.empresa_id = '" . $farmacia . "' ";
        $sql .= "AND RE.fecha BETWEEN '" . $fechaI . "'::date AND '" . $fechaF . "'::date ";
        $sql .= "AND e.codigo_producto = i.codigo_producto ";
        $sql .= "AND i.codigo_producto = p.codigo_producto ";
        $sql .= "AND p.grupo_id=s.grupo_id ";
        $sql .= "AND p.clase_id=s.clase_id ";
        $sql .= "AND p.subclase_id=s.subclase_id ";
        $sql .= "AND s.grupo_id=c.grupo_id ";
        $sql .= "AND s.clase_id=c.clase_id ";
        $sql .= "AND p.unidad_id=u.unidad_id ";
        $sql .= "GROUP BY 1, RE.empresa_id, RE.codigo_producto, p.descripcion , p.subclase_id,re.cantidad_inicial, ";
        $sql .= " p.sw_generico, p.cantidad, e.existencia, s.descripcion , c.descripcion ,c.clase_id, ";
        $sql .= " u.unidad_id, u.abreviatura HAVING SUM(RE.cantidad_ingreso)> 0  AND SUM(RE.cantidad_egreso)>0 ";
        $sql .= "ORDER BY fecha_registro, RE.codigo_producto, RE.empresa_id, p.descripcion ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {

            $medicamentos[$rst->fields[4]] [$rst->fields[5]] [$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $medicamentos;
    }

    /*
     * Funcion  Consultar las existencias por productos
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Existencia_x_Producto($farmacia, $codigo_producto, $bodega) {

        $sql = " SELECT  SUM(EX.existencia) AS existencia,
		                P.tipo_producto_id
                FROM    existencias_bodegas EX 
				        JOIN inventarios I ON (I.codigo_producto=EX.codigo_producto AND  I.empresa_id=Ex.empresa_id)
						JOIN inventarios_productos P ON (I.codigo_producto=P.codigo_producto)
                WHERE   EX.empresa_id = '" . $farmacia . "'
                AND     EX.bodega='" . $bodega . "'				
                AND     EX.codigo_producto = '" . $codigo_producto . "'
			    GROUP BY P.tipo_producto_id				";


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);

            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se Consultan las Bodegas de la Farmacia
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarBodegasFarmacia($farmacia) {
        $sql = " 
                SELECT  b.centro_utilidad, b.bodega, b.descripcion, c.descripcion as centro
                FROM bodegas  b
                INNER JOIN centros_utilidad c ON b.centro_utilidad=c.centro_utilidad AND b.empresa_id=c.empresa_id
                WHERE b.empresa_id = '{$farmacia}' ORDER BY 2
               ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);

            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se Consultan todas las empresas
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarEmpresas() {
        $sql = " SELECT  empresa_id, razon_social FROM 	empresas WHERE 	sw_tipo_empresa = '0' order by razon_social ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);

            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     *  Funcion donde se inserta la primera parte del documento de pedido  
     *  @return  boolean de acuerdo a la ejecucion del sql.
     */

    function IngresoSolicitud($farmacia, $centro, $bodega, $observacion, $destino) {

        $this->ConexionTransaccion();

        $sql = "INSERT INTO Solicitud_Productos_A_Bodega_principal
						(
							Solicitud_Prod_A_Bod_ppal_id,	
							farmacia_id,
							centro_utilidad,
							bodega,
							observacion,
						    usuario_id, 
							fecha_registro,
							empresa_destino,
							sw_despacho
						)
						VALUES
						(
							NEXTVAL('solicitud_productos_a_bodega_p_solicitud_prod_a_bod_ppal_id_seq'),
							 '" . $farmacia . "',
							'" . $centro . "',
							  '" . $bodega . "' ,
							'" . $observacion . "',
							" . UserGetUID() . ",
							NOW(),
							'" . $destino . "',
							0
											);
							";

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    /*     * ***************************************************************************************************************************************** */
    /*
     * Funcion  Consultar la rotacion por tipo de insumo
     * @return array $datos vector que contiene la informacion de la consulta.
     */
    /* function  RotacionInsumos_x_Tipo_($fechaI,$fechaF,$empresa,$bodega_id)
      {

      $sql .= " SELECT Distinct s.subclase_id,";
      $sql .= " s.descripcion as molecula, ";
      $sql .= " SUM(RE.cantidad_ingreso) AS ingreso, ";
      $sql .= " SUM(RE.cantidad_egreso) AS egreso ";
      $sql .= "FROM   existencias_bodegas AS e ";
      $sql .= "      JOIN rotacion_producto_x_empresa RE ON
      (e.empresa_id = RE.empresa_id
      AND e.codigo_producto = RE.codigo_producto
      AND e.centro_utilidad = RE.centro_utilidad
      AND e.bodega = RE.bodega)";
      $sql .= "      JOIN inventarios i ON (e.codigo_producto = i.codigo_producto ) and (e.empresa_id = i.empresa_id) ";
      $sql .= "      JOIN inventarios_productos p ON (i.codigo_producto = p.codigo_producto) ";
      $sql .= "     JOIN inv_subclases_inventarios s ON (p.grupo_id=s.grupo_id AND p.clase_id=s.clase_id AND p.subclase_id=s.subclase_id  ) ";
      $sql .= "     JOIN inv_clases_inventarios c ON (s.grupo_id=c.grupo_id AND  s.clase_id=c.clase_id   ) ";
      $sql .= "     JOIN inv_grupos_inventarios g ON (c.grupo_id=g.grupo_id) ";
      $sql .= "     JOIN  unidades u ON (p.unidad_id=u.unidad_id) ";
      $sql .= "WHERE  ";
      $sql .= "     RE.empresa_id = '".$empresa."' ";
      $sql .= "	AND RE.fecha >=  '".$fechaI." 00:00:00' and  RE.fecha <= '".$fechaF." 24:00:00' ";
      $sql .= "AND g.sw_insumos='1' ";
      $sql .= "AND  RE.bodega='".$bodega_id."' ";
      $sql .= "GROUP BY  s.subclase_id, ";
      $sql .= " s.descripcion ";
      $sql .= " HAVING SUM(RE.cantidad_ingreso)>= 0  AND SUM(RE.cantidad_egreso)>0  ";
      $sql .= "ORDER BY s.descripcion ";

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

      } */


    /* CONSULTA DE INSUMOS */
    /**/
    /*
     * Funcion  Consultar los productos  para generar la rotacion por medicamentos
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function RotacionInsumos($fechaI, $fechaF, $empresa, $bodega, $subclase_id) {

        $sql = " SELECT 	TO_CHAR(RE.fecha,'YYYY-MM') AS fecha_registro, 
        RE.codigo_producto, 
        fc_descripcion_producto(RE.codigo_producto) as producto,
        SUM(RE.cantidad_ingreso) AS ingreso,
        SUM(RE.cantidad_egreso) AS egreso,
        EX.existencia AS existencia,
        P.tipo_producto_id
        FROM   	
        
        existencias_bodegas  EX
        JOIN rotacion_producto_x_empresa RE ON (EX.empresa_id = RE.empresa_id 
        AND EX.codigo_producto = RE.codigo_producto 
        AND EX.centro_utilidad = RE.centro_utilidad 
        AND EX.bodega = RE.bodega)
        JOIN inventarios I ON (RE.empresa_id=I.empresa_id AND RE.codigo_producto=I.codigo_producto)
        JOIN inventarios_productos P ON (I.codigo_producto=P.codigo_producto)
        JOIN inv_subclases_inventarios S ON (P.grupo_id=S.grupo_id AND P.clase_id=S.clase_id AND P.subclase_id=S.subclase_id  )
        JOIN inv_clases_inventarios c ON (S.grupo_id=C.grupo_id AND S.clase_id=C.clase_id )
        JOIN inv_grupos_inventarios G ON (C.grupo_id=G.grupo_id )
        WHERE  	RE.empresa_id = '" . $empresa . "' 
        AND RE.bodega ='" . $bodega . "'
        AND RE.fecha >=  '" . $fechaI . " 00:00:00' and  RE.fecha <= '" . $fechaF . " 24:00:00'
        AND G.sw_insumos='1'
        ";

        if (!empty($subclase_id) && $subclase_id != -1) {

            $sql .= " AND  S.subclase_id='" . $subclase_id . "' ";
        }
        if ($clase_id == -1) {
            $sql .= "   ";
        }

        $sql .= " GROUP BY   1, RE.codigo_producto,P.descripcion,P.tipo_producto_id,
									EX.existencia
							    HAVING SUM(RE.cantidad_ingreso)> 0
							    AND SUM(RE.cantidad_egreso)>0
			 	ORDER BY 	   fecha_registro,
							   RE.codigo_producto,
							   P.descripcion ";


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {

            $medicamentos[$rst->fields[1]] [$rst->fields[2]] [$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $medicamentos;
    }

    //* Function que permite obtener lo solicitado por generencia
    /*     * @return array $datos vector que contiene la informacion de la consulta./ */
    function solcitud_Gerencia_($datos_empresa, $medicamento, $cantidad) {

        $this->ConexionTransaccion();

        $sql = "INSERT INTO solicitud_gerencia( 
									solictud_gerencia_id,
									empresa_id,
									centro_utilidad,
									bodega,
									codigo_producto,
									cantidad,
									usuario_id
									
						)VALUES( 
						         nextval('solicitud_gerencia_solictud_gerencia_id_seq'),
								'" . $datos_empresa['Farmacia_id'] . "', 
								'" . $datos_empresa['centro'] . "', 
								'" . $datos_empresa['bodega'] . "', 
								'" . $medicamento . "', 
								" . $cantidad . ", 
								" . UserGetUID() . "
							  ) ";

        if (!$rst = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    /* / */

    function Eliminar_Cantidad_Solicitudes($datos_empresa, $producto) {

        $this->ConexionTransaccion();
        $sql = " 	DELETE  FROM  solicitud_gerencia
						WHERE  		  empresa_id='" . $datos_empresa['Farmacia_id'] . "'
						AND    		  centro_utilidad='" . $datos_empresa['centro'] . "'
						AND	   		  bodega ='" . $datos_empresa['bodega'] . "'		
						AND           codigo_producto='" . $producto . "' 
						AND           usuario_id=" . UserGetUID() . "
                        AND           estado = '0'				";


        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }

        $this->Commit();
        return $datos;
    }

    /**/

    function Solicitudes_Generadas_x_Rotacion($datos_empresa) {

        $sql = " SELECT 	g.codigo_producto,sum(g.cantidad),
							fc_descripcion_producto(g.codigo_producto) as producto
				FROM    	solicitud_gerencia g
				WHERE  		g.empresa_id = '" . $datos_empresa['Farmacia_id'] . "'
				AND   		g.centro_utilidad ='" . $datos_empresa['centro'] . "'
				AND  		g.bodega ='" . $datos_empresa['bodega'] . "'
				AND  		g.estado = '0'
				GROUP BY g.codigo_producto ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);

            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     *  Funcion donde se insertan los datos a la tabla temporal solicitud_pro_a_bod_prpal_tmp
     *  @return  boolean de acuerdo a la ejecucion del sql.
     */

    function Ingresar_DatosSolicitudTMP($Datos_empresa, $producto, $cantidad, $tipo_prod, $observacion) {



        $this->ConexionTransaccion();

        $sql = "INSERT INTO solicitud_pro_a_bod_prpal_tmp
						(
							soli_a_bod_prpal_tmp_id,	
							farmacia_id,
							centro_utilidad,
							bodega,
							codigo_producto,
							cantidad_solic,
							usuario_id,
							tipo_producto,
							observacion
						)
						VALUES
						(
							
							'" . $producto . "" . $Datos_empresa['Farmacia_id'] . "" . $Datos_empresa['centro'] . "',
							'" . $Datos_empresa['Farmacia_id'] . "' , 
							'" . $Datos_empresa['centro'] . "' , 
							'" . $Datos_empresa['bodega'] . "' , 
							'" . $producto . "' , 
							" . $cantidad . " , 
							 " . UserGetUID() . ",
							" . $tipo_prod . ",
							'" . $observacion . "'							
						);
				";

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    /*
     * Funcion  Consultar los datos de la informacion temporal
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Solicitud_Temporal_Pedidos($datos_empresa) {

        $sql = " SELECT 	codigo_producto,
								fc_descripcion_producto(codigo_producto)as producto,
								observacion,
								tipo_producto,
								SUM(cantidad_solic) AS cantidad
					FROM 	solicitud_pro_a_bod_prpal_tmp
					WHERE 	farmacia_id = '" . $datos_empresa['Farmacia_id'] . "'
					AND 	centro_utilidad = '" . $datos_empresa['centro'] . "'
					AND 	bodega = '" . $datos_empresa['bodega'] . "' 
					AND 	usuario_id = " . UserGetUID() . "
                    GROUP BY codigo_producto,observacion,tipo_producto
					ORDER BY producto

					";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);

            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* buscar las cantidades disponibles * */

    function BuscarPedidosFarmacia($EmpresaId, $CodigoProducto) {


        $sql = "
		      select SUM(total)
		            from
		              (
		            SELECT 
		                      SUM(sd.cantidad_solic) as total
		                      from
		                      solicitud_productos_a_bodega_principal_detalle sd,
		                      solicitud_productos_a_bodega_principal s
		                      where
		                            sd.codigo_producto = '" . $CodigoProducto . "'
		                      and   sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id
		                      and   s.empresa_destino = '" . $EmpresaId . "'
		                      and   s.sw_despacho = '0'
		                      UNION     
		                      SELECT  SUM(ips.cantidad_pendiente) as total
		                      from 	  solicitud_productos_a_bodega_principal_detalle sd,
				                      solicitud_productos_a_bodega_principal s,
				                      inv_mov_pendientes_solicitudes_frm ips
		                      where sd.codigo_producto = '" . $CodigoProducto . "'
		                      and   sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id
		                      and   s.empresa_destino = '" . $EmpresaId . "'
		                      and   s.sw_despacho = '1'
		                      and   sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id
		                      and   sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id
		                      and   sd.solicitud_prod_a_bod_ppal_det_id = ips.solicitud_prod_a_bod_ppal_det_id
							  UNION     
								SELECT SUM(vopd.numero_unidades) as total
								from
								ventas_ordenes_pedidos vop,
								ventas_ordenes_pedidos_d vopd
								where
								vop.empresa_id= '" . $EmpresaId . "'
								and
								vop.fecha_envio IS NULL
								and
								vop.pedido_cliente_id = vopd.pedido_cliente_id
								and
								vopd.codigo_producto='" . $CodigoProducto . "'
										  
		                 )as total;
		              ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function Existencia_x_Producto_Bodega($farmacia, $codigo_producto, $bodega) {

        $sql = " SELECT  SUM(EX.existencia) AS existencia
		        FROM    existencias_bodegas EX
		        WHERE   EX.empresa_id = '" . $farmacia . "'
                AND     EX.codigo_producto = '" . $codigo_producto . "'
				AND    EX.bodega='" . $bodega . "'
		           ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /**/
    /*
     *  Funcion donde se inserta la primera parte del documento de pedido  
     *  @return  boolean de acuerdo a la ejecucion del sql.
     */

    function IngresoSolicitudBP($DatosEmpresa, $observacion, $destino) {

        $this->ConexionTransaccion();

        $sql = "INSERT INTO Solicitud_Productos_A_Bodega_principal
						(
							Solicitud_Prod_A_Bod_ppal_id,	
							farmacia_id,
							centro_utilidad,
							bodega,
							observacion,
						    usuario_id, 
							fecha_registro,
							empresa_destino,
							sw_despacho
						)
						VALUES
						(
							NEXTVAL('solicitud_productos_a_bodega_p_solicitud_prod_a_bod_ppal_id_seq'),
							 '" . $DatosEmpresa['Farmacia_id'] . "',
							'" . $DatosEmpresa['centro'] . "',
							  '" . $DatosEmpresa['bodega'] . "' ,
							'" . $observacion . "',
							" . UserGetUID() . ",
							NOW(),
							'" . $destino . "',
							0
					    );
							";
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();


        $sql2 = " SELECT MAX(solicitud_prod_a_bod_ppal_id) as solictud_id FROM  solicitud_productos_a_bodega_principal
				         WHERE   farmacia_id ='" . $DatosEmpresa['Farmacia_id'] . "' 
						 AND     centro_utilidad = '" . $DatosEmpresa['centro'] . "'
						 AND     bodega =  '" . $DatosEmpresa['bodega'] . "' 
						 AND     usuario_id =" . UserGetUID() . "
						 AND     empresa_destino ='" . $destino . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql2))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;




        return true;
    }

    /*
     *  Funcion donde se inserta la parte final del documento de pedido  
     *  @return  boolean de acuerdo a la ejecucion del sql.
     */

    function IngresoDetallSolicitud($id, $datos_empresa, $producto, $cantidad, $observacion, $tipo_producto) {

        $this->ConexionTransaccion();

        $sql .= "INSERT INTO Solicitud_Productos_A_Bodega_principal_detalle
							(
								Solicitud_Prod_A_Bod_ppal_det_id,	
								Solicitud_Prod_A_Bod_ppal_id,
								farmacia_id,
								centro_utilidad,
								bodega,
								codigo_producto,
								cantidad_Solic,
								usuario_id,
								fecha_registro,
								observacion,
								tipo_producto,
                                                                                                                                                cantidad_pendiente
							
							)
							VALUES
							(
								NEXTVAL('solicitud_productos_a_bodega__solicitud_prod_a_bod_ppal_det_seq'),
								" . $id . ",
								'" . $datos_empresa['Farmacia_id'] . "',
								'" . $datos_empresa['centro'] . "',
								 '" . $datos_empresa['bodega'] . "',
								'" . $producto . "',
								" . $cantidad . ",
								" . UserGetUID() . ",
								 now(),
								 '" . $observacion . "',
  								 '" . $tipo_producto . "',
								" . $cantidad . "
								 
								 );
						";
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();

        $sql2 = " DELETE    FROM solicitud_pro_a_bod_prpal_tmp
							WHERE 	farmacia_id = '" . $datos_empresa['Farmacia_id'] . "'
							AND 	centro_utilidad = '" . $datos_empresa['centro'] . "'
							AND 	bodega = '" . $datos_empresa['bodega'] . "' 
							AND     codigo_producto='" . $producto . "'
							AND 	usuario_id = " . UserGetUID() . " ";


        if (!$rst1 = $this->ConexionTransaccion($sql2)) {
            return false;
        }
        $this->Commit();

        return true;
    }

    /*
     * Funcion  Consultar los datos de la informacion temporal
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Solicitud_Temporal_Pedidos_x_prod($datos_empresa, $codigo_producto) {

        $sql = " SELECT 	codigo_producto,
								fc_descripcion_producto(codigo_producto)as producto,
								observacion,
								tipo_producto,
								SUM(cantidad_solic) AS cantidad
					FROM 	solicitud_pro_a_bod_prpal_tmp
					WHERE 	farmacia_id = '" . $datos_empresa['Farmacia_id'] . "'
					AND 	centro_utilidad = '" . $datos_empresa['centro'] . "'
					AND 	bodega = '" . $datos_empresa['bodega'] . "' 
					AND 	usuario_id = " . UserGetUID() . "
					AND     codigo_producto='" . $codigo_producto . "'
                    GROUP BY codigo_producto,observacion,tipo_producto
					ORDER BY producto

					";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);

            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* actualizar CANTIDADDES DELOS PEDIDOS / */

    function ActualizarCantidadMTP($datos_empresa, $producto, $canti, $observacion) {

        $sql = "  update  solicitud_pro_a_bod_prpal_tmp 
		            set     cantidad_solic=" . $canti . ",observacion='" . $observacion . "'
				    WHERE 	farmacia_id = '" . $datos_empresa['Farmacia_id'] . "'
					AND 	centro_utilidad = '" . $datos_empresa['centro'] . "'
					AND 	bodega = '" . $datos_empresa['bodega'] . "' 
					AND 	usuario_id = " . UserGetUID() . "
					AND     codigo_producto='" . $producto . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     *  Funcion donde se insertan los datos solicitados a devolver
     *  @return  boolean de acuerdo a la ejecucion del sql.
     */

    function IngresarDatosDevolucion($Datos_empresa, $producto, $cantidad) {


        $this->ConexionTransaccion();

        $sql = "INSERT INTO Devolucion_Rotacion_Farmacia
						(
								devolucionrf_id,
								empresa_id,		
								centro_utilidad,		
								bodega,		
								codigo_producto,		
								cantidad,		
								sw_devuelto,		
								usuario_id,		
								fecha_registro
						)
						VALUES
						(
							nextval('devolucion_rotacion_farmacia_devolucionrf_id_seq'),
							'" . $Datos_empresa['Farmacia_id'] . "' , 
							'" . $Datos_empresa['centro'] . "' , 
							'" . $Datos_empresa['bodega'] . "' , 
							'" . $producto . "' , 
							" . $cantidad . " , 
							'0',
							 " . UserGetUID() . ",
							now()  
					    );
						
				";

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    /*
     * Funcion donde se Consulta la informacion de lo solicitado para que sea devuelto
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarInformacion_Devolucion($Datos_empresa) {
        $sql = " 	SELECT   DE.codigo_producto,
                                 SUM(DE.cantidad) AS cantidad,
                                 fc_descripcion_producto(DE.codigo_producto) as producto
 						FROM 	devolucion_rotacion_farmacia DE
						WHERE 	DE.empresa_id ='" . $Datos_empresa['Farmacia_id'] . "'  
						AND 	DE.centro_utilidad = '" . $Datos_empresa['centro'] . "'  
						AND 	DE.bodega = '" . $Datos_empresa['bodega'] . "' AND  DE.sw_devuelto = '0' 
                        GROUP BY DE.codigo_producto						";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);

            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* / */

    function Eliminar_Cantidad_Solicitudes_D($datos_empresa, $producto) {

        $this->ConexionTransaccion();
        $sql = " 	DELETE  FROM  devolucion_rotacion_farmacia
						WHERE  		  empresa_id='" . $datos_empresa['Farmacia_id'] . "'
						AND    		  centro_utilidad='" . $datos_empresa['centro'] . "'
						AND	   		  bodega ='" . $datos_empresa['bodega'] . "'		
						AND           codigo_producto='" . $producto . "' 
						AND           usuario_id=" . UserGetUID() . "
                        AND           sw_devuelto = '0'				";


        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }

        $this->Commit();
        return $datos;
    }

    /**/
    /*
     * Funcion  Consultar los productos  para generar la rotacion por medicamentos
     * @return array $datos vector que contiene la informacion de la consulta.
     */
    /* function  RotacionFinalProducto($fechaI,$fechaF,$empresa)
      {

      //$this->debug=true;
      $sql  = " SELECT TO_CHAR(RE.fecha,'YYYY-MM') AS fecha_registro, ";
      $sql .= " s.descripcion as molecula, ";
      $sql .= " RE.empresa_id, ";
      $sql .= " RE.codigo_producto, ";
      $sql .= " fc_descripcion_producto(RE.codigo_producto) as producto, ";
      $sql .= " e.existencia, ";
      $sql .= " SUM(RE.cantidad_ingreso) AS ingreso, ";
      $sql .= " SUM(RE.cantidad_egreso) AS egreso, ";
      $sql .= "FROM existencias_bodegas AS e ";
      $sql .= " JOIN rotacion_producto_x_empresa RE ON
      (e.empresa_id = RE.empresa_id
      AND e.codigo_producto = RE.codigo_producto
      AND e.centro_utilidad = RE.centro_utilidad
      AND   e.bodega = RE.bodega ) ";
      $sql .= " JOIN  inventarios i  ON (e.codigo_producto = i.codigo_producto) and (e.empresa_id = i.empresa_id) ";
      $sql .= " JOIN inventarios_productos p ON (i.codigo_producto = p.codigo_producto) ";
      $sql .= " JOIN inv_subclases_inventarios s ON (p.grupo_id=s.grupo_id AND p.clase_id=s.clase_id AND p.subclase_id=s.subclase_id ) ";

      $sql .= "WHERE  ";
      $sql .= " RE.empresa_id = '".$empresa."' ";
      $sql .= "AND RE.fecha >=  '".$fechaI." 00:00:00' and  RE.fecha <= '".$fechaF." 24:00:00'";

      $sql .= "GROUP BY 1, RE.empresa_id, RE.codigo_producto,  ";
      //$sql .= "e.existencia, s.descripcion ,RE.cantidad_ingreso, RE.cantidad_egreso ";
      $sql .= "e.existencia, s.descripcion ";
      $sql .= " HAVING SUM(RE.cantidad_ingreso)>=0  AND SUM(RE.cantidad_egreso)>0 ";
      //$sql .= " HAVING SUM(RE.cantidad_ingreso)>=0  AND SUM(RE.cantidad_egreso)>=0 ";
      $sql .= " ORDER BY fecha_registro, s.descripcion,RE.codigo_producto, RE.empresa_id";

      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      $datos = array();
      while (!$rst->EOF)
      {

      $medicamentos[$rst->fields[3]] [$rst->fields[4]] [$rst->fields[1]]  [$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
      }
      $rst->Close();
      return $medicamentos;

      } */
    /**/

    Function Consultar_Bodegas_Destino($empresa) {

        $sql = " SELECT  bodega,
							 descripcion
					FROM     bodegas 
					WHERE    empresa_id = '" . $empresa . "'";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);

            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion  Consultar los productos  con mayor egreso en determinada fecha
     * @return array $datos vector que contiene la informacion de la consulta.
     */
    /* function Consultar_Medicamentos_Generico_MaxEgreso($fechaI,$fechaF,$empresa)
      {
      $sql = " SELECT RE.codigo_producto,
      p.descripcion as producto,
      p.contenido_unidad_venta,
      p.presentacioncomercial_id,
      p.cantidad,
      u.unidad_id,
      p.subclase_id,
      MAX(RE.cantidad_egreso) as egreso
      FROM 	existencias_bodegas AS e
      JOIN rotacion_producto_x_empresa RE ON
      (e.empresa_id = RE.empresa_id AND
      e.codigo_producto = RE.codigo_producto
      AND  e.centro_utilidad = RE.centro_utilidad
      AND  e.bodega = RE.bodega)
      JOIN inventarios i ON (e.codigo_producto = i.codigo_producto) and (e.empresa_id = i.empresa_id)
      JOIN inventarios_productos p ON (i.codigo_producto = p.codigo_producto)
      JOIN unidades u ON (p.unidad_id=u.unidad_id)
      WHERE
      RE.empresa_id = '".$empresa."'
      AND RE.fecha >=  '".$fechaI." 00:00:00' and  RE.fecha <= '".$fechaF." 24:00:00'
      AND     p.sw_generico='1'
      GROUP BY RE.codigo_producto,
      p.descripcion,
      p.contenido_unidad_venta,
      p.presentacioncomercial_id,
      p.cantidad,
      u.unidad_id,
      p.subclase_id
      HAVING SUM(RE.cantidad_egreso)> 0 ";

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
      } */
    /*
     * Funcion  Consultar los productos con la misma presentacion y molecula
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Medicamentos_Genericos_Unificados($fechaI, $fechaF, $empresa, $subclase_id, $contenido_unidad_venta, $presentacioncomercial_id, $unidad_id, $cantidad, $bodega) {


        $sql = " SELECT TO_CHAR(RE.fecha,'YYYY-MM') AS     fecha_registro,
											SUB.subclase_id,
											PRO.descripcion as molecula, 
										    fc_descripcion_producto(PRO.codigo_producto) as producto,
											PRO.codigo_producto,
											SUM(RE.cantidad_ingreso) AS ingreso,
											SUM(RE.cantidad_egreso) AS egreso
						FROM 				rotacion_producto_x_empresa RE
											JOIN inventarios INV ON (RE.empresa_id=INV.empresa_id AND RE.codigo_producto=INV.codigo_producto)
											JOIN inventarios_productos PRO ON (INV.codigo_producto=PRO.codigo_producto)
											JOIN inv_subclases_inventarios SUB ON (PRO.grupo_id=SUB.grupo_id AND    PRO.clase_id=SUB.clase_id AND  PRO.subclase_id=SUB.subclase_id)
						WHERE 				RE.empresa_id ='" . $empresa . "' 
						AND                 RE.bodega='" . $bodega . "' 
						AND RE.fecha >=  '" . $fechaI . " 00:00:00' and  RE.fecha <= '" . $fechaF . " 24:00:00'
						AND 				PRO.sw_generico='1'
						AND 				PRO.subclase_id='" . $subclase_id . "'
						AND					PRO.contenido_unidad_venta='" . $contenido_unidad_venta . "'
						AND 				PRO.presentacioncomercial_id='" . $presentacioncomercial_id . "'
						AND 				PRO.unidad_id='" . $unidad_id . "'
						AND 				PRO.cantidad='" . trim($cantidad) . "'
					
    					GROUP BY 			1, SUB.subclase_id, 
											PRO.descripcion, 
											SUB.descripcion,
											PRO.contenido_unidad_venta,
											PRO.presentacioncomercial_id,
											PRO.cantidad,
											PRO.codigo_producto,											
											PRO.unidad_id
						HAVING SUM(RE.cantidad_ingreso)>= 0
						AND SUM(RE.cantidad_egreso)>0
						ORDER BY 	fecha_registro, 
									SUB.descripcion ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {

            $medicamentos[$rst->fields[1]] [$rst->fields[2]] [$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $medicamentos;
    }

    /*
     * Funcion  Consultar los medicamentos comerciales
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Medicamentos_Comerciales($fechaI, $fechaF, $empresa, $bodega) {



        $sql = " SELECT TO_CHAR(RE.fecha,'YYYY-MM') AS     fecha_registro,
											SUB.subclase_id,
											PRO.descripcion as molecula, 
											PRO.contenido_unidad_venta,
											PRO.presentacioncomercial_id,
											PRO.cantidad, 
											PRO.unidad_id,
											PRO.codigo_producto,
											SUM(RE.cantidad_ingreso) AS ingreso,
											SUM(RE.cantidad_egreso) AS egreso
							FROM 			rotacion_producto_x_empresa RE
											JOIN inventarios INV ON (RE.empresa_id=INV.empresa_id AND RE.codigo_producto=INV.codigo_producto)
											JOIN inventarios_productos PRO ON (INV.codigo_producto=PRO.codigo_producto)
											JOIN inv_subclases_inventarios SUB ON (PRO.grupo_id=SUB.grupo_id AND    PRO.clase_id=SUB.clase_id AND  PRO.subclase_id=SUB.subclase_id)
				
						WHERE 				RE.empresa_id ='" . $empresa . "' 
						AND                 RE.bodega='" . $bodega . "' 
							AND RE.fecha >=  '" . $fechaI . " 00:00:00' and  RE.fecha <= '" . $fechaF . " 24:00:00'
						AND 				PRO.sw_generico='0'
						
    					GROUP BY 			1, SUB.subclase_id, 
											PRO.descripcion, 
											SUB.descripcion,
											PRO.contenido_unidad_venta,
											PRO.presentacioncomercial_id,
											PRO.cantidad,
											PRO.codigo_producto,											
											PRO.unidad_id
						HAVING SUM(RE.cantidad_ingreso)>= 0
						AND SUM(RE.cantidad_egreso)>0
						ORDER BY 	fecha_registro, 
									SUB.descripcion ";


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {

            $medicamentos[$rst->fields[1]] [$rst->fields[2]] [$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $medicamentos;
    }

    /**/

    function Consultar_productos_a_Devolver($Datos_empresa, $codigo_producto) {

        $sql = " 	SELECT   devolucionrf_id,
                                 codigo_producto
                                
 						FROM 	devolucion_rotacion_farmacia 
						WHERE 	empresa_id ='" . $Datos_empresa['Farmacia_id'] . "'  
						AND 	centro_utilidad = '" . $Datos_empresa['centro'] . "'  
						AND 	bodega = '" . $Datos_empresa['bodega'] . "' AND  sw_devuelto = '0' 
						AND     codigo_producto = '" . $codigo_producto . "'
                       					";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);

            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* actualizar devoluciones */

    function ActualizarCantidad_Devoluciones($datos_empresa, $producto, $canti) {

        $sql = "  update  devolucion_rotacion_farmacia 
		            set     cantidad=cantidad +" . $canti . "
				    WHERE 	empresa_id = '" . $datos_empresa['Farmacia_id'] . "'
					AND 	centro_utilidad = '" . $datos_empresa['centro'] . "'
					AND 	bodega = '" . $datos_empresa['bodega'] . "' 
					AND     codigo_producto='" . $producto . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion  Consultar los productos  para generar la rotacion por medicamentos
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function RotacionFinalProductos($fechaI, $fechaF, $farmacia, $periodo, $farmacia_id, $empresa_id, $bodega_id) {

        $sql = "            
            select 
            '{$periodo}' as periodo,
            cc.descripcion as farmacia,
            aa.codigo_producto,
            fc_descripcion_producto(aa.codigo_producto) as descripcion_producto,
            ee.descripcion as molecula,
            ff.descripcion as laboratorio,
            gg.descripcion as tipo_producto,
            aa.existencia::integer as stock_farmacia,
            (
              select sum(aaa.existencia)::integer from existencias_bodegas aaa 
              where aaa.empresa_id = '{$empresa_id}' and aaa.bodega = '{$bodega_id}' and aaa.codigo_producto= aa.codigo_producto
            ) as stock_bodega,
            COALESCE(bb.cantidad_total_despachada, 0) as cantidad_total_despachada  
            from existencias_bodegas aa
            inner join bodegas cc on aa.empresa_id = cc.empresa_id and aa.bodega = cc.bodega and aa.centro_utilidad = cc.centro_utilidad 
            inner join inventarios_productos dd on aa.codigo_producto = dd.codigo_producto
            inner join inv_subclases_inventarios ee on dd.grupo_id = ee.grupo_id and dd.clase_id=ee.clase_id and dd.subclase_id= ee.subclase_id
            inner join inv_clases_inventarios ff on ee.clase_id = ff.clase_id and ee.grupo_id = ff.grupo_id
            inner join inv_tipo_producto gg on dd.tipo_producto_id = gg.tipo_producto_id
            left join (
                select 
                aa.codigo_producto,
                (sum(aa.cantidad_total_despachada))::integer as cantidad_total_despachada
                from (
                  select
                  d.codigo_producto,
                  (sum(d.cantidad))::integer as cantidad_total_despachada,1
                  from esm_formula_externa a 
                  inner join (  
                    SELECT formula_id,bodegas_doc_id,numeracion,1
                    FROM esm_formulacion_despachos_medicamentos  
                    GROUP BY formula_id,bodegas_doc_id,numeracion  
                    UNION  
                    SELECT formula_id,bodegas_doc_id,numeracion,2  
                    FROM esm_formulacion_despachos_medicamentos_pendientes  
                    GROUP BY formula_id,bodegas_doc_id,numeracion 
                  ) as c on a.formula_id = c.formula_id 
                  inner join bodegas_documentos_d d ON c.bodegas_doc_id = d.bodegas_doc_id and c.numeracion = d.numeracion 
                  inner join bodegas_documentos j on d.bodegas_doc_id = j.bodegas_doc_id and d.numeracion = j.numeracion
                  inner JOIN bodegas_doc_numeraciones e ON d.bodegas_doc_id= e.bodegas_doc_id 
                  inner join bodegas f ON e.empresa_id= f.empresa_id AND e.centro_utilidad= f.centro_utilidad AND e.bodega= f.bodega 
                  where cast (j.fecha_registro as date) between '{$fechaI}' AND '{$fechaF}'
                  and f.bodega='{$farmacia}' and a.sw_estado <>'2' 
                  group by 1 
                  UNION
                  select 
                  d.codigo_producto,
                  (sum(d.cantidad))::integer as cantidad_total_despachada,2
                  from hc_evoluciones a
                  inner join (
                      select evolucion_id, bodegas_doc_id, numeracion,1
                      from hc_formulacion_despachos_medicamentos
                      group by evolucion_id, bodegas_doc_id, numeracion
                      UNION
                      select evolucion_id, bodegas_doc_id, numeracion,1
                      from hc_formulacion_despachos_medicamentos_pendientes
                      group by evolucion_id, bodegas_doc_id, numeracion,2
                  ) as c on a.evolucion_id = c.evolucion_id
                  inner join bodegas_documentos_d d ON c.bodegas_doc_id = d.bodegas_doc_id and c.numeracion = d.numeracion 
                  inner join bodegas_documentos j on d.bodegas_doc_id = j.bodegas_doc_id and d.numeracion = j.numeracion
                  inner JOIN bodegas_doc_numeraciones e ON d.bodegas_doc_id= e.bodegas_doc_id 
                  inner join bodegas f ON e.empresa_id= f.empresa_id AND e.centro_utilidad= f.centro_utilidad AND e.bodega= f.bodega 
                  where cast (j.fecha_registro as date) between '{$fechaI}' AND '{$fechaF}'
                  and f.bodega='{$farmacia}'
                  group by 1
                ) AS aa group by 1                           
            ) as bb on aa.codigo_producto = bb.codigo_producto
            where aa.empresa_id = '{$farmacia_id}' and aa.centro_utilidad= '{$farmacia}' and aa.bodega = '{$farmacia}'
            and (aa.existencia>0 or COALESCE(bb.cantidad_total_despachada, 0)>0) --and aa.codigo_producto='0798I0217006'
            order by 4,7  ";

        /* echo "<pre>*";
          var_dump($sql);
          echo "**</pre>";
          exit(); */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function rotacion_general_farmacias($farmacia_id, $fechaI, $fechaF) {
        
        $sql = "select 
                aa.codigo_producto,
                fc_descripcion_producto(aa.codigo_producto) as descripcion_producto,
                ee.descripcion as molecula,
                ff.descripcion as laboratorio,
                gg.descripcion as tipo_producto,
                SUM(COALESCE(bb.cantidad_total_despachada, 0)) as cantidad_total_despachada  
                from existencias_bodegas aa
                inner join bodegas cc on aa.empresa_id = cc.empresa_id and aa.bodega = cc.bodega and aa.centro_utilidad = cc.centro_utilidad 
                inner join inventarios_productos dd on aa.codigo_producto = dd.codigo_producto
                inner join inv_subclases_inventarios ee on dd.grupo_id = ee.grupo_id and dd.clase_id=ee.clase_id and dd.subclase_id= ee.subclase_id
                inner join inv_clases_inventarios ff on ee.clase_id = ff.clase_id and ee.grupo_id = ff.grupo_id
                inner join inv_tipo_producto gg on dd.tipo_producto_id = gg.tipo_producto_id
                left join (
                    select 
                    aa.empresa_id,
                    aa.codigo_producto,
                    (sum(aa.cantidad_total_despachada))::integer as cantidad_total_despachada
                    from (
                      select
                      f.empresa_id,
                      d.codigo_producto,
                      (sum(d.cantidad))::integer as cantidad_total_despachada,1
                      from esm_formula_externa a 
                      inner join (  
                        SELECT formula_id,bodegas_doc_id,numeracion,1
                        FROM esm_formulacion_despachos_medicamentos  
                        GROUP BY formula_id,bodegas_doc_id,numeracion  
                        UNION  
                        SELECT formula_id,bodegas_doc_id,numeracion,2  
                        FROM esm_formulacion_despachos_medicamentos_pendientes  
                        GROUP BY formula_id,bodegas_doc_id,numeracion 
                      ) as c on a.formula_id = c.formula_id 
                      inner join bodegas_documentos_d d ON c.bodegas_doc_id = d.bodegas_doc_id and c.numeracion = d.numeracion 
                      inner join bodegas_documentos j on d.bodegas_doc_id = j.bodegas_doc_id and d.numeracion = j.numeracion
                      inner JOIN bodegas_doc_numeraciones e ON d.bodegas_doc_id= e.bodegas_doc_id 
                      inner join bodegas f ON e.empresa_id= f.empresa_id AND e.centro_utilidad= f.centro_utilidad AND e.bodega= f.bodega 
                      where cast (j.fecha_registro as date) between '{$fechaI}' AND '{$fechaF}' and a.sw_estado <>'2' 
                      group by 1,2 
                      UNION
                      select 
                      f.empresa_id,
                      d.codigo_producto,
                      (sum(d.cantidad))::integer as cantidad_total_despachada,2
                      from hc_evoluciones a
                      inner join (
                          select evolucion_id, bodegas_doc_id, numeracion,1
                          from hc_formulacion_despachos_medicamentos
                          group by evolucion_id, bodegas_doc_id, numeracion
                          UNION
                          select evolucion_id, bodegas_doc_id, numeracion,1
                          from hc_formulacion_despachos_medicamentos_pendientes
                          group by evolucion_id, bodegas_doc_id, numeracion,2
                      ) as c on a.evolucion_id = c.evolucion_id
                      inner join bodegas_documentos_d d ON c.bodegas_doc_id = d.bodegas_doc_id and c.numeracion = d.numeracion 
                      inner join bodegas_documentos j on d.bodegas_doc_id = j.bodegas_doc_id and d.numeracion = j.numeracion
                      inner JOIN bodegas_doc_numeraciones e ON d.bodegas_doc_id= e.bodegas_doc_id 
                      inner join bodegas f ON e.empresa_id= f.empresa_id AND e.centro_utilidad= f.centro_utilidad AND e.bodega= f.bodega 
                      where cast (j.fecha_registro as date) between '{$fechaI}' AND '{$fechaF}'      
                      group by 1,2
                    ) AS aa group by 1,2                          
                ) as bb on aa.empresa_id = bb.empresa_id and aa.codigo_producto = bb.codigo_producto
                where aa.empresa_id = '{$farmacia_id}'
                and (aa.existencia>0 or COALESCE(bb.cantidad_total_despachada, 0)>0) 
                group by 1,2,3,4,5
                order by 6 DESC";

        /* echo "<pre>*";
          var_dump($sql);
          echo "**</pre>";
          exit(); */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function buscar_farmacia($empresa_id, $farmacia_id) {
        $sql = " 
                SELECT  b.centro_utilidad, b.bodega, b.descripcion, c.descripcion as centro
                FROM bodegas  b
                INNER JOIN centros_utilidad c ON b.centro_utilidad=c.centro_utilidad AND b.empresa_id=c.empresa_id
                WHERE b.empresa_id = '{$empresa_id}' and b.bodega='{$farmacia_id}' ORDER BY 2
               ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);

            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function obtener_listas_empresas() {


        $sql = "SELECT  empresa_id, razon_social FROM empresas WHERE sw_tipo_empresa='1'";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);

            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function obtener_zonas() {

        $sql = "SELECT  a.zona_bodega_id, a.empresa_id, a.zona_bodega FROM zonas_bodegas a";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);

            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function rotacion_producto($fecha_inicial, $fecha_final, $zona_id, $periodo, $codigo_producto) {

        $sql = "
            select
            '{$periodo}' as periodo_tiempo,
            f.bodega,
            f.descripcion as farmacia,
            d.codigo_producto,
            fc_descripcion_producto(d.codigo_producto) as descripcion_producto,
            (
                select sum(aa.existencia)::integer 
                from existencias_bodegas aa   
                inner join centros_utilidad bb on aa.empresa_id = bb.empresa_id and aa.centro_utilidad = bb.centro_utilidad  
                inner join zonas_bodegas cc on bb.empresa_id = cc.empresa_id            
                where cc.zona_bodega_id='{$zona_id}' and aa.codigo_producto= d.codigo_producto
             ) as stock_bodega,
            (
              select sum(aa.existencia)::integer from existencias_bodegas aa 
              where aa.empresa_id = f.empresa_id and aa.centro_utilidad= f.centro_utilidad and aa.bodega = f.bodega and aa.codigo_producto= d.codigo_producto
            ) as stock_farmacia,
            (sum(d.cantidad) + COALESCE(g.cantidad_despachada, 0))::integer as cantidad_total_despachada
            from esm_formula_externa a 
            inner join (  
              SELECT formula_id,bodegas_doc_id,numeracion,1
              FROM esm_formulacion_despachos_medicamentos  
              GROUP BY formula_id,bodegas_doc_id,numeracion  
              UNION  
              SELECT formula_id,bodegas_doc_id,numeracion,2  
              FROM esm_formulacion_despachos_medicamentos_pendientes  
              GROUP BY formula_id,bodegas_doc_id,numeracion 
            ) as c on a.formula_id = c.formula_id 
            inner join bodegas_documentos_d d ON c.bodegas_doc_id = d.bodegas_doc_id and c.numeracion = d.numeracion
            inner join bodegas_documentos j on d.bodegas_doc_id = j.bodegas_doc_id and d.numeracion = j.numeracion
            inner JOIN bodegas_doc_numeraciones e ON d.bodegas_doc_id= e.bodegas_doc_id 
            inner join bodegas f ON e.empresa_id= f.empresa_id AND e.centro_utilidad= f.centro_utilidad AND e.bodega= f.bodega 
            left join (
              select 
              e.bodega, c.codigo_producto, fc_descripcion_producto(c.codigo_producto) as descripcion_producto, sum(d.cantidad_solicitada) as cantidad_solicitada, sum(c.cantidad) as cantidad_despachada
              from inv_bodegas_movimiento_despacho_campania a 
              inner join inv_bodegas_movimiento b on a.empresa_id = b.empresa_id and a.prefijo = b.prefijo and a.numero = b.numero
              inner join inv_bodegas_movimiento_d c on b.empresa_id = c.empresa_id and b.prefijo = c.prefijo and b.numero = c.numero              
              inner join bodegas e on c.centro_utilidad = e.centro_utilidad and c.bodega = e.bodega
              left join esm_orden_requisicion_d d on a.orden_requisicion_id = d.orden_requisicion_id and c.codigo_producto = d.codigo_producto
              where b.fecha_registro between '{$fecha_inicial}' AND '{$fecha_final}'
              and c.prefijo='ERE' and e.zona_id='{$zona_id}'
              group by 1,2,3
            ) as g on d.codigo_producto = g.codigo_producto and f.bodega = g.bodega
            where cast (j.fecha_registro as date) between '{$fecha_inicial}' AND '{$fecha_final}'  
            and f.zona_id='{$zona_id}' and d.codigo_producto='{$codigo_producto}'
            and a.sw_estado <>'2' 
            group by 1, 2, 3, 4, 5, 6, 7, g.cantidad_despachada";

        /* echo "<pre>================================================================================================";
          var_dump($sql);
          echo "</pre>"; */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);

            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

}

?>