<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: Compras_Orden_ComprasSQL.class.php,v 1.2 2010/01/14 22:49:02 sandra Exp $Revision: 1.2 $
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja Torres 
 */
class Compras_Orden_ComprasSQL extends ConexionBD {
    /*
     * Constructor de la clase
     */

    function Compras_Orden_ComprasSQL() {
        
    }

    /**
     * Funcion donde se Consultan los Tipos de identificacion 
     * @return array $datos vector que contiene la informacion de la consulta de los Tipos 
     * de Identificacion
     */
    function ObtenerPermisos() {

        $sql = "SELECT   	a.empresa_id, ";
        $sql .= "           b.razon_social AS descripcion1, ";
        $sql .= "           a.centro_utilidad, ";
        $sql .= "           c.descripcion AS descripcion2, ";
        $sql .= "           a.usuario_id, ";
        $sql .= "           a.sw_modifica ";
        $sql .= "FROM 	    userpermisos_compras AS a, ";
        $sql .= "           empresas AS b, ";
        $sql .= "           centros_utilidad AS c ";
        $sql .= "WHERE      a.usuario_id= " . UserGetUID() . "  ";
        $sql .= "           AND 	a.empresa_id=b.empresa_id ";
        $sql .= "           AND 	a.centro_utilidad=c.centro_utilidad ";
        $sql .= "           AND 	a.empresa_id=c.empresa_id ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /**
     * Funcion donde se lista los centros de utilidad de la empresa
     * @return array $datos vector que contiene la informacion de la consulta de los Tipos 
     * de Identificacion
     */
    function ListarCentrodeUtilidad($empresa) {
        //$this->debug=true;
        $sql = "SELECT   	empresa_id, centro_utilidad,descripcion,Ubicacion";
        $sql .= "           From centros_utilidad  ";
        $sql .= "WHERE      empresa_id='" . $empresa . "'  ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se obtienen las bodegas asociadas a la farmacia..
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ObtenerBodegaFarmacia($empresa) {

        $sql = "SELECT   	";
        $sql .= "           e.bodega,  ";
        $sql .= "           e.descripcion AS descripcion3   ";
        $sql .= "FROM 	    empresas AS b, ";
        $sql .= "           centros_utilidad AS c, ";
        $sql .= "           bodegas AS e ";
        $sql .= "WHERE     	e.empresa_id=c.empresa_id  ";
        $sql .= "           AND 	e.centro_utilidad=c.centro_utilidad ";
        $sql .= "           AND 	c.empresa_id=b.empresa_id ";
        $sql .= "           AND 	b.empresa_id='" . $empresa . "' AND  b.sw_tipo_empresa='0' ";
        $sql .= "           ORDER BY e.descripcion; ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se consulta la informacion delas pre ordenes generadas
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function consultarInformacionPreOrden_($filtros, $offset, $orden_pedido_id) {

        $sql = "SELECT 	p.preorden_id,
	                    p.farmacia_id,
	                    p.observacion,
	                    p.sw_preorden,
	                    To_char(p.fecha_registro,'dd-mm-yyyy') as fecha_registro,
                        p.usuario_id,
	                    s.nombre,
	                    e.razon_social
	            FROM    informacion_preorden  p,
	                    system_usuarios  s,
	                    empresas e
	           WHERE    p.farmacia_id=e.empresa_id
	                    and     p.usuario_id=s.usuario_id
	                    and     p.sw_preorden=1 ";
        if ($filtros['orden'] != "")
            $sql.=" and p.preorden_id= " . $filtros['orden'] . " ";

        if ($filtros['farmacia'] != "")
            $sql .= " AND     e.razon_social  ILIKE '%" . $filtros['farmacia'] . "%' ";

        if ($orden_pedido_id != "") {
            $sql.=" and p.preorden_id= " . $orden_pedido_id . " ";
        }

        $cont = "select COUNT(*) from (" . $sql . ") AS A";

        $this->ProcesarSqlConteo($cont, $offset);

        $sql .= "ORDER BY  p.fecha_registro DESC ";
        $sql .="  ";
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
     * Funcion donde se consulta la informacion de los proveedores
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    Function ListarProveedoresGeneradosPO($preorden_id) {

        $sql = "  SELECT  DISTINCT  t.codigo_proveedor_id,
                                  t.sw_unificada,
                                  ter.nombre_tercero
  				FROM       			        informacion_preorden_detalle t,
                									terceros ter,
                									terceros_proveedores p
                WHERE     			t.codigo_proveedor_id=p.codigo_proveedor_id
                AND        			p.tipo_id_tercero=ter.tipo_id_tercero
                AND			        p.tercero_id=ter.tercero_id  
                AND        			t.preorden_id=" . $preorden_id . " 
                AND  				    t.sw_unificada=0
                AND  				    t.codigo_proveedor_id in ( select  p.codigo_proveedor_id
                FROM       			terceros_proveedores p
                GROUP BY   			p.codigo_proveedor_id ,ter.nombre_tercero) ";

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
     * Funcion donde se consulta el detalle de las preordenes de comrpra
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarDetallePreOrden($preorden_id, $filtros, $offset) {


        $sql = " SELECT 	p.farmacia_id,
                            p.observacion,
                            p.sw_preorden,
                            t.preorden_detalle_id,
                            t.preorden_id,
                            t.codigo_proveedor_id,
                            t.codigo_producto,
                            t.cantidad,
							t.valor_total_pactado,
                            t.fecha_registro,
                            t.sw_unificada,
                            ter.nombre_tercero,
                            i.descripcion,
                            i.cantidad as cant,
							i.contenido_unidad_venta,
                            u.abreviatura,
                            m.molecula_id,
                            m.descripcion as molecula,
                            l.laboratorio_id,
                            l.descripcion as laboratorio,
                            ter.tipo_id_tercero,
                            ter.tercero_id
      		FROM              informacion_preorden_detalle t,
                    				informacion_preorden  p,
                    				inventarios_productos i,
                    				terceros_proveedores pro,
                    				terceros ter,
                    				inv_subclases_inventarios s,
                    				inv_moleculas m,
                    				inv_clases_inventarios c,
                    				inv_laboratorios l,
                    				unidades u
      		WHERE           p.preorden_id=t.preorden_id
                  and     t.codigo_producto=i.codigo_producto
                  and     t.codigo_proveedor_id=pro.codigo_proveedor_id
                  and     pro.tipo_id_tercero=ter.tipo_id_tercero
                  and     pro.tercero_id=ter.tercero_id
                  and     i.grupo_id=s.grupo_id
                  and 	  i.clase_id=s.clase_id
                  and     i.subclase_id=s.subclase_id
                  and     s.molecula_id=m.molecula_id
                  and     s.grupo_id=c.grupo_id
                  and     s.clase_id=c.clase_id
                  and     c.laboratorio_id=l.laboratorio_id
                  and     t.sw_unificada=0
                  and     p.sw_preorden=1
                  and     i.unidad_id=u.unidad_id
                  and     t.preorden_id='" . $preorden_id . "'
                  and     t.codigo_proveedor_id=" . $filtros['proveedor_id'] . "		";

        $cont = "select COUNT(*) from (" . $sql . ") AS A";
        $this->ProcesarSqlConteo($cont, $offset);
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
     * Funcion donde se consulta la informacion  Detalle de la preorden de compras
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function SeleccionarInformacionDetalle($preorden_id, $proveedor_id) {

        $sql = " SELECT 	p.farmacia_id,
                        p.observacion,
                        p.sw_preorden,
                        t.preorden_detalle_id,
                        t.preorden_id,
                        t.codigo_proveedor_id,
                        t.codigo_producto,
                        t.cantidad,
                        t.valor_total_pactado,
                        t.fecha_registro,
                        t.sw_unificada,
                        ter.nombre_tercero,
                        i.descripcion,
                        m.molecula_id,
                        m.descripcion as molecula,
                        l.laboratorio_id,
                        l.descripcion as laboratorio,
                        ter.tipo_id_tercero,
                        ter.tercero_id,
                        t.valor_unitario
		            FROM    informacion_preorden_detalle t,
                				informacion_preorden  p,
                				inventarios_productos i,
                				terceros_proveedores pro,
                				terceros ter,
                				inv_subclases_inventarios s,
                				inv_moleculas m,
                				inv_clases_inventarios c,
                				inv_laboratorios l
            		WHERE   p.preorden_id=t.preorden_id
            		and     t.codigo_producto=i.codigo_producto
            		and     t.codigo_proveedor_id=pro.codigo_proveedor_id
            		and     pro.tipo_id_tercero=ter.tipo_id_tercero
            		and     pro.tercero_id=ter.tercero_id
            		and     i.grupo_id=s.grupo_id
            		and 	  i.clase_id=s.clase_id
            		and     i.subclase_id=s.subclase_id
            		and     s.molecula_id=m.molecula_id
            		and     s.grupo_id=c.grupo_id
            		and     s.clase_id=c.clase_id
            		and     c.laboratorio_id=l.laboratorio_id
            		and     t.sw_unificada=0
            		and     p.sw_preorden=1
            		and     t.preorden_id='" . $preorden_id . "'
                and    t.codigo_proveedor_id=" . $proveedor_id . "	;	";

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
     * Funcion donde se inserta  las ordenes de pedido  que han sido unificadas  por preorden 
     * @return boolean de acuerdo a la ejecucion del sql.
     */

    function insertarOrden_Pedido($pedido_id, $codigo_proveedor_id, $empresa) {

        $indice = array();
        $pedidoid = $pedido_id + 1;
        $this->ConexionTransaccion();

        $sql = "INSERT INTO compras_ordenes_pedidos( 
                                                    orden_pedido_id,		
                                                    codigo_proveedor_id,		
                                                    empresa_id,		
                                                    fecha_orden,		
                                                    usuario_id,		
                                                    estado,		
                                                    fecha_envio,
                                                    fecha_recibido,
                                                    sw_unificada,
                                                    empresa_id_pedido
                                                  )
                                          VALUES( 
                                                 " . $pedidoid . ",
                                                  " . $codigo_proveedor_id . ",
                                                  '" . $empresa . "',
                                                  NOW(),
                                                  " . UserGetUID() . ",
                                                  1,
                                                  NULL,
                                                  NULL,
                                                  0,
                                                  '" . $empresa . "'									
                                                ); ";

        if (!$rst = $this->ConexionTransaccion($sql)) {
            return false;
        }

        $this->Commit();
        return true;
    }

    /*
     * Funcion donde se consulta el ultimo registro se la  orden de compras 
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function SeleccionarMaxcompras_ordenes_pedidos($codigo_proveedor_id, $empresa_id) {

        /* if($codigo_proveedor_id=="-1")
          { */
        $sql = "  SELECT   MAX(orden_pedido_id) AS numero FROM compras_ordenes_pedidos; ";
        /* }
          else
          {
          $sql = "  SELECT   MAX(orden_pedido_id) AS numero FROM compras_ordenes_pedidos
          where    codigo_proveedor_id='".$codigo_proveedor_id."'
          and      empresa_id_pedido ='".$empresa_id."'; ";
          } */

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
     * Funcion donde se inserta  el detalle de las ordenes de compra
     * @return boolean de acuerdo a la ejecucion del sql.
     */

    function Ingresarcompras_ordenes_pedidos_detalle($datos, $orden_pedido_id) {
        foreach ($datos as $item => $fila) {
            $this->ConexionTransaccion();

            $sql .= "INSERT INTO   compras_ordenes_pedidos_detalle
                (
                              orden_pedido_id,
                              codigo_producto,
                              numero_unidades,
                              valor,
                              porc_iva,
                              estado,
                              acta_autorizacion,
                              numero_unidades_recibidas,
                           
                              valor_unitario
					      )
                VALUES
                (
                " . $orden_pedido_id . ",
                '" . $fila['codigo_producto'] . "',
                " . $fila['cantidad'] . ",
                " . $fila['valor_total_pactado'] . ",
                0,
                1,
                null,
                null,
             
                " . $fila['valor_unitario'] . "
                );
                ";
        }
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    /*
     * Funcion donde  se actualiza el estado de unificadas de la pre orden
     * @return boolean de acuerdo a la ejecucion del sql.
     */

    function ActuEstado($preorden_id, $codigo_proveedor_id) {

        $sql = "	 UPDATE informacion_preorden_detalle
                  SET    sw_unificada=1              
                  WHERE  preorden_id =" . $preorden_id . "
                  AND    codigo_proveedor_id =" . $codigo_proveedor_id . " ;
        ";

        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false;
        }
        return true;
    }

    /*
     * Funcion donde  se  consulta las preordenes que no han estan unificadas.
     * @return boolean de acuerdo a la ejecucion del sql.
     */

    function consultarSw_Unificados($preorden_id) {

        $sql = "  SELECT 	preorden_detalle_id,
                        preorden_id,
                        sw_unificada
               FROM    informacion_preorden_detalle
               WHERE   sw_unificada='0'
               and     preorden_id=" . $preorden_id . " ";
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
     * Funcion donde  se insertan las condiciones  para las ordenes de compras
     * @return boolean de acuerdo a la ejecucion del sql.
     */

    function insertarCondicionesOrden_Pedido($empresa_id, $orden_pedido_id, $condicion) {

        $this->ConexionTransaccion();

        $sql = "
      UPDATE compras_ordenes_pedidos
      SET observacion = '" . $condicion . "'
      where
      orden_pedido_id = " . $orden_pedido_id . "; ";

        if (!$rst = $this->ConexionTransaccion($sql)) {
            return false;
        }

        $this->Commit();
        return true;
    }

    /*
      function  insertarCondicionesOrden_Pedido($empresa_id,$orden_pedido_id,$condicion)
      {

      $indice = array();
      $sql = "SELECT NEXTVAL('condiciones_orden_compra_condicionoc_id_seq') AS sq ";

      if(!$rst = $this->ConexionBaseDatos($sql))
      return false;
      if(!$rst->EOF)
      {
      $indice = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
      }
      $rst->Close();

      $sqlerror = "SELECT setval('condiciones_orden_compra_condicionoc_id_seq', ".($indice['sq'])."); ";

      $this->ConexionTransaccion();
      $sql  = "INSERT INTO	 	condiciones_orden_compra(
      condicionoc_id,
      empresa_id,
      orden_pedido_id,
      condicion,
      usuario_id,
      fecha_registro )
      VALUES(
      ".$indice['sq'].",
      '".$empresa_id."',
      ".$orden_pedido_id.",
      '".$condicion."',
      ".UserGetUID().",
      NOW()
      ); ";
      if(!$rst = $this->ConexionTransaccion($sql))
      {
      return false;
      }

      $this->Commit();
      return true;
      }


     */

    /*
     * Funcion donde se Consultan  las ordenes de compras generadas pero que no esten unificadas
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarOrdenComprasGeneradas($filtros, $offset) {

        $sql = "  SELECT o.orden_pedido_id,
					o.codigo_proveedor_id,
					o.empresa_id,
					o.usuario_id,
					To_char(o.fecha_orden,'dd-mm-yyyy') as fecha_registro,
					s.nombre,
					e.razon_social,
					e.tipo_id_tercero as tipo_id_empresa,
					e.id as id_empresa,
					ter.nombre_tercero,
					ter.tipo_id_tercero,
					ter.tercero_id,
					ter.direccion,
					ter.telefono,
					o.estado,
					o.observacion,
					u.codigo_unidad_negocio,
					u.imagen,
					u.descripcion
					FROM        compras_ordenes_pedidos  o
					LEFT JOIN unidades_negocio as u ON (o.codigo_unidad_negocio = u.codigo_unidad_negocio),
					system_usuarios  s,
					empresas e,
					terceros_proveedores p,
					terceros ter
					WHERE       o.empresa_id=e.empresa_id
					and     o.usuario_id=s.usuario_id
					and     o.codigo_proveedor_id=p.codigo_proveedor_id
					and     p.tipo_id_tercero=ter.tipo_id_tercero
					and     p.tercero_id=ter.tercero_id 
					AND     o.sw_unificada='0' ";

        if ($filtros['tipo_id_tercero'] != "-1")
            $sql.=" and ter.tipo_id_tercero= '" . $filtros['tipo_id_tercero'] . "' ";
        if ($filtros['tercero_id']) {
            $sql.=" and ter.tercero_id= '" . $filtros['tercero_id'] . "' ";
        }
        if ($filtros['nombre_tercero'] != "")
            $sql .= "AND     ter.nombre_tercero ILIKE '%" . $filtros['nombre_tercero'] . "%' ";

        $fdatos = explode("-", $filtros['fecha_inicio']);
        $fedatos = $fdatos[2] . "-" . $fdatos[1] . "-" . $fdatos[0];

        if ($filtros['fecha_inicio'] != "")
            $sql.=" and o.fecha_orden = '" . $fedatos . "' ";

        if ($filtros['orden'] != "")
            $sql.=" and o.orden_pedido_id= " . $filtros['orden'] . " ";

        if ($filtros['farmacia'] != "")
            $sql .= " AND     e.razon_social  ILIKE '%" . $filtros['farmacia'] . "%' ";

        $cont = "select COUNT(*) from (" . $sql . ") AS A";
        $this->ProcesarSqlConteo($cont, $offset);
        $sql .= "ORDER BY o.orden_pedido_id  DESC ";
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
     * Funcion donde se Consultan las novedades de los detalles de las ordenes de compras
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarNovedadesDetallesOrdenesCompras($empresa_id, $filtros, $offset) {
        $sql = "  SELECT o.orden_pedido_id,
                        
                        copd.item_id AS compra_orden_detalle_id,
                        fc_descripcion_producto(copd.codigo_producto) AS nombre,
                        CAST (copd.numero_unidades AS INTEGER) AS cantidad_solicitada,
                        CAST (copd.numero_unidades_recibidas AS INTEGER) AS cantidad_recibida,
                        CAST ((copd.numero_unidades - copd.numero_unidades_recibidas) AS INTEGER) AS cantidad_pendiente,
                        
                        (SELECT noc.novedad_orden_compra_id FROM novedades_ordenes_compras noc WHERE copd.item_id = noc.item_id) AS novedad_orden_compra_id,
                        (SELECT noc.item_id FROM novedades_ordenes_compras noc WHERE copd.item_id = noc.item_id) AS item_id,
                        /*(SELECT noc.ruta_archivo_adjunto FROM novedades_ordenes_compras noc WHERE copd.item_id = noc.item_id) AS ruta_archivo_adjunto,*/
                        (SELECT noc.descripcion FROM novedades_ordenes_compras noc WHERE copd.item_id = noc.item_id) AS descripcion,
                        (SELECT noc.fecha_posible_envio FROM novedades_ordenes_compras noc WHERE copd.item_id = noc.item_id) AS fecha_posible_envio,
                        
                        (SELECT ooc.observacion_orden_compra_id 
                        FROM novedades_ordenes_compras noc, observaciones_ordenes_compras ooc 
                        WHERE copd.item_id = noc.item_id AND noc.observacion_orden_compra_id = ooc.observacion_orden_compra_id) AS observacion_orden_compra_id,
                        
                        (SELECT ooc.codigo 
                        FROM novedades_ordenes_compras noc, observaciones_ordenes_compras ooc 
                        WHERE copd.item_id = noc.item_id AND noc.observacion_orden_compra_id = ooc.observacion_orden_compra_id) AS codigo,
                        
                        (SELECT ooc.descripcion 
                        FROM novedades_ordenes_compras noc, observaciones_ordenes_compras ooc 
                        WHERE copd.item_id = noc.item_id AND noc.observacion_orden_compra_id = ooc.observacion_orden_compra_id) AS observacion
                        
                    FROM compras_ordenes_pedidos  o,
                        system_usuarios  s,
                        empresas e,
                        terceros_proveedores p,
                        terceros ter,
                        compras_ordenes_pedidos_detalle copd
                    WHERE o.empresa_id=e.empresa_id
                        AND o.usuario_id=s.usuario_id
                        AND o.codigo_proveedor_id=p.codigo_proveedor_id
                        AND p.tipo_id_tercero=ter.tipo_id_tercero
                        AND p.tercero_id=ter.tercero_id 
                        AND o.sw_unificada='0'
                        AND o.orden_pedido_id = copd.orden_pedido_id
                        AND o.empresa_id = '" . $empresa_id . "' ";

        $fdatos = explode("-", $filtros['fecha_inicio']);
        $fedatos = $fdatos[2] . "-" . $fdatos[1] . "-" . $fdatos[0];

        if ($filtros['fecha_inicio'] != "")
            $sql.=" and o.fecha_orden = '" . $fedatos . "' ";

        if ($filtros['orden'] != "")
            $sql.=" and o.orden_pedido_id= " . $filtros['orden'] . " ";

        if ($filtros['farmacia'] != "")
            $sql .= " AND     e.razon_social  ILIKE '%" . $filtros['farmacia'] . "%' ";

        //$sql .= " ORDER BY copd.item_id";
        //echo $sql."<br><br><br>";

        $cont = "select COUNT(*) from (" . $sql . ") AS A";
        $this->ProcesarSqlConteo($cont, $offset);
        $sql .= "ORDER BY o.orden_pedido_id  DESC ";
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
     * Funcion donde se Consultan los archivos de las novedades de los detalles de las ordenes de compras
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarArchivosNovedadDetalleOrdenCompra($novedad_orden_compra_id) {
        $sql = "SELECT archivo_novedad_orden_compra_id, archivo, nombre_original_archivo
                    FROM archivos_novedades_ordenes_compras
                    WHERE novedad_orden_compra_id = " . $novedad_orden_compra_id . " ";

        //echo $sql;

        /* $cont = "select COUNT(*) from (" . $sql . ") AS A";
          $this->ProcesarSqlConteo($cont, $offset);
          $sql .= "ORDER BY o.orden_pedido_id  DESC ";
          $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset; */
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
     * Funcion donde se Consultan las auditorias de los detalles (items) de las ordenes de compras
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarAuditoriasDetallesOrdenesCompras($filtros/* , $offset */) {
        $sql = "  SELECT 
                        o.orden_pedido_id,
                        To_char(o.fecha_orden,'dd-mm-yyyy') as fecha_registro,
                        s.nombre,
                        ter.nombre_tercero,
                        ter.tipo_id_tercero,
                        ter.tercero_id,
                        copde.codigo_producto,
                        fc_descripcion_producto(copde.codigo_producto) AS descripcion,
                        copde.numero_unidades,
                        copde.valor,
                        copde.porc_iva,
                        copde.accion,
                        copde.fecha_registro AS fecha_registro_auditoria,
                        (SELECT nombre FROM system_usuarios WHERE usuario_id = copde.usuario_id) AS usuario_auditoria,
                        (
                            CASE WHEN copde.version IS NULL THEN '--' 
                            ELSE copde.version END
                        ) AS version
                    FROM 
                        compras_ordenes_pedidos  o,
                        system_usuarios  s,
                        empresas e,
                        terceros_proveedores p,
                        terceros ter,
                        compras_ordenes_pedidos_detalle_auditoria copde
                    WHERE 
                        o.empresa_id=e.empresa_id
                        AND o.usuario_id=s.usuario_id
                        AND o.codigo_proveedor_id=p.codigo_proveedor_id
                        AND p.tipo_id_tercero=ter.tipo_id_tercero
                        AND p.tercero_id=ter.tercero_id 
                        /*AND o.sw_unificada='0'*/
                        AND o.orden_pedido_id = copde.orden_pedido_id";

        if ($filtros['empresa_id'] != "") {
            $sql.=" AND o.empresa_id='" . $filtros['empresa_id'] . "' ";
        }

        /* if ($filtros['tipo_id_tercero'] != "-1")
          $sql.=" and ter.tipo_id_tercero= '" . $filtros['tipo_id_tercero'] . "' "; */
        /* if ($filtros['tercero_id'])
          {
          $sql.=" and ter.tercero_id= '" . $filtros['tercero_id'] . "' ";
          } */
        /* if ($filtros['nombre_tercero'] != "")
          $sql .= "AND     ter.nombre_tercero ILIKE '%" . $filtros['nombre_tercero'] . "%' "; */

        /* $fdatos = explode("-", $filtros['fecha_inicio']);
          $fedatos = $fdatos[2] . "-" . $fdatos[1] . "-" . $fdatos[0];

          if ($filtros['fecha_inicio'] != "")
          $sql.=" and o.fecha_orden = '" . $fedatos . "' "; */

        if ($filtros['orden'] != "")
            $sql.=" and o.orden_pedido_id= " . $filtros['orden'] . " ";

        if ($filtros['farmacia'] != "")
            $sql .= " AND     e.razon_social  ILIKE '%" . $filtros['farmacia'] . "%' ";

        //echo $sql;

        /* $cont = "select COUNT(*) from (" . $sql . ") AS A";
          $this->ProcesarSqlConteo($cont, $offset);
          $sql .= "ORDER BY o.orden_pedido_id  DESC ";
          $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset; */
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
     * Funcion donde se consultan la auditoria de los detalles (items) del documento temporal de la orden de compra
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarAuditoriasDetallesOrdenesComprasDocumentoTemporal($filtros/* , $offset */) {
        $sql = "SELECT DISTINCT ON (copd.item_id)
                            copd.codigo_producto,
                            fc_descripcion_producto(copd.codigo_producto) AS descripcion,
                            (
                                    CASE WHEN CAST(copd.numero_unidades AS INT) = CAST(ibmtd.cantidad AS INT) THEN 'No hubo cambios' 
                                    ELSE CAST(CAST(copd.numero_unidades AS INT) AS CHAR(10)) END
                            ) AS cantidad_original,
                            (
                                    CASE WHEN CAST(copd.numero_unidades AS INT) = CAST(ibmtd.cantidad AS INT) THEN 'No hubo cambios' 
                                    ELSE CAST(CAST(ibmtd.cantidad AS INT) AS CHAR(10)) END
                            ) AS cantidad_modificada,
                            (
                                    CASE WHEN (
                                            SELECT valor_unitario_factura 
                                            FROM compras_ordenes_pedidos_productosfoc copp 
                                            WHERE copp.orden_pedido_id = copd.orden_pedido_id 
                                                    AND copp.codigo_producto = copd.codigo_producto
                                    ) IS NULL THEN 'No hubo cambios' 
                                    ELSE CAST('$' || copd.valor AS CHAR(12)) END
                            ) AS valor_original,
                            (
                                    CASE WHEN (
                                            SELECT valor_unitario_factura 
                                            FROM compras_ordenes_pedidos_productosfoc copp 
                                            WHERE copp.orden_pedido_id = copd.orden_pedido_id 
                                                    AND copp.codigo_producto = copd.codigo_producto
                                    ) IS NULL THEN 'No hubo cambios' 
                                    ELSE CAST((
                                            SELECT ('$' || valor_unitario_factura) 
                                            FROM compras_ordenes_pedidos_productosfoc copp 
                                            WHERE copp.orden_pedido_id = copd.orden_pedido_id 
                                                    AND copp.codigo_producto = copd.codigo_producto
                                    ) AS CHAR(10)) END
                            ) AS valor_modificado,
                            su.nombre,	
                            ibmt.fecha_registro
                    FROM 
                            compras_ordenes_pedidos cop
                            INNER JOIN compras_ordenes_pedidos_detalle copd ON (cop.orden_pedido_id = copd.orden_pedido_id)
                            INNER JOIN inv_bodegas_movimiento_tmp_ordenes_compra ibmtoc ON (cop.orden_pedido_id = ibmtoc.orden_pedido_id)
                            INNER JOIN inv_bodegas_movimiento_tmp ibmt ON (ibmtoc.doc_tmp_id = ibmt.doc_tmp_id)
                            INNER JOIN inv_bodegas_movimiento_tmp_d ibmtd ON (ibmt.doc_tmp_id = ibmtd.doc_tmp_id AND ibmt.usuario_id = ibmtd.usuario_id)
                            INNER JOIN system_usuarios su ON (ibmt.usuario_id = su.usuario_id)
                    WHERE
                            ibmtoc.orden_pedido_id = " . $filtros['orden'] . "
                            AND copd.item_id = ibmtd.item_id_compras
                            AND (
                                    copd.numero_unidades <> ibmtd.cantidad 
                                    OR (
                                            (CAST((copd.numero_unidades * copd.valor) AS INT) <> ibmtd.total_costo) 
                                            AND (CAST(copd.numero_unidades AS INT) = CAST(ibmtd.cantidad AS INT))
                                    )
                            )";

        /* if($filtros['empresa_id'] != "") {
          $sql.=" AND o.empresa_id='".$filtros['empresa_id']."' ";
          }

          if ($filtros['tipo_id_tercero'] != "-1")
          $sql.=" and ter.tipo_id_tercero= '" . $filtros['tipo_id_tercero'] . "' ";
          if ($filtros['tercero_id'])
          {
          $sql.=" and ter.tercero_id= '" . $filtros['tercero_id'] . "' ";
          }
          if ($filtros['nombre_tercero'] != "")
          $sql .= "AND     ter.nombre_tercero ILIKE '%" . $filtros['nombre_tercero'] . "%' ";

          $fdatos = explode("-", $filtros['fecha_inicio']);
          $fedatos = $fdatos[2] . "-" . $fdatos[1] . "-" . $fdatos[0];

          if ($filtros['fecha_inicio'] != "")
          $sql.=" and o.fecha_orden = '" . $fedatos . "' ";

          if ($filtros['orden'] != "")
          $sql.=" and o.orden_pedido_id= " . $filtros['orden'] . " ";

          if ($filtros['farmacia'] != "")
          $sql .= " AND     e.razon_social  ILIKE '%" . $filtros['farmacia'] . "%' "; */

        //echo $sql;

        /* $cont = "select COUNT(*) from (" . $sql . ") AS A";
          $this->ProcesarSqlConteo($cont, $offset);
          $sql .= "ORDER BY o.orden_pedido_id  DESC ";
          $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset; */
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
     * Funcion donde se consultan la auditoria de los detalles (items) del documento de la orden de compra
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarAuditoriasDetallesOrdenesComprasDocumento($filtros/* , $offset */) {
        $sql = "SELECT DISTINCT ON (copd.item_id)
                        copd.codigo_producto,
                        fc_descripcion_producto(copd.codigo_producto) AS descripcion,
                        (
                                CASE WHEN CAST(copd.numero_unidades AS INT) = CAST(ibmd.cantidad AS INT) THEN 'No hubo cambios' 
                                ELSE CAST(CAST(copd.numero_unidades AS INT) AS CHAR(10)) END
                        ) AS cantidad_original,
                        (
                                CASE WHEN CAST(copd.numero_unidades AS INT) = CAST(ibmd.cantidad AS INT) THEN 'No hubo cambios' 
                                ELSE CAST(CAST(ibmd.cantidad AS INT) AS CHAR(10)) END
                        ) AS cantidad_modificada,
                        (
                                CASE WHEN (
                                        copd.numero_unidades <> ibmd.cantidad 
                                        AND (
                                                (
                                                        copd.valor = (((ibmd.total_costo / ibmd.cantidad) / (ibmd.porcentaje_gravamen + 100)) * 100)
                                                )
                                        )
                                ) THEN 'No hubo cambios' 
                                ELSE CAST('$' || copd.valor AS CHAR(12)) END
                        ) AS valor_original,
                        (
                                CASE WHEN (
                                        copd.numero_unidades <> ibmd.cantidad 
                                        AND (
                                                (
                                                        copd.valor = (((ibmd.total_costo / ibmd.cantidad) / (ibmd.porcentaje_gravamen + 100)) * 100)
                                                )
                                        )
                                ) THEN 'No hubo cambios' 
                                ELSE CAST('$' || (((ibmd.total_costo / ibmd.cantidad) / (ibmd.porcentaje_gravamen + 100)) * 100) AS CHAR(12)) END
                        ) AS valor_modificado,	
                        su.nombre,
                        ibm.fecha_registro
                    FROM 
                        compras_ordenes_pedidos cop
                        INNER JOIN compras_ordenes_pedidos_detalle copd ON (cop.orden_pedido_id = copd.orden_pedido_id)
                        INNER JOIN inv_bodegas_movimiento_ordenes_compra ibmoc ON (cop.orden_pedido_id = ibmoc.orden_pedido_id)
                        INNER JOIN inv_bodegas_movimiento ibm ON (ibmoc.empresa_id = ibm.empresa_id AND ibmoc.prefijo = ibm.prefijo AND ibmoc.numero = ibm.numero)
                        INNER JOIN inv_bodegas_movimiento_d ibmd ON (ibm.empresa_id = ibmd.empresa_id AND ibm.prefijo = ibmd.prefijo AND ibm.numero = ibmd.numero)
                        INNER JOIN system_usuarios su ON (ibm.usuario_id = su.usuario_id)
                    WHERE
                        ibmoc.orden_pedido_id = " . $filtros['orden'] . "
                        AND copd.codigo_producto = ibmd.codigo_producto
                        AND (
                                copd.numero_unidades <> ibmd.cantidad 
                                OR (
                                        (copd.numero_unidades * copd.valor) <> ibmd.total_costo AND copd.numero_unidades = ibmd.cantidad
                                )
                        )";

        /* if($filtros['empresa_id'] != "") {
          $sql.=" AND o.empresa_id='".$filtros['empresa_id']."' ";
          }

          if ($filtros['tipo_id_tercero'] != "-1")
          $sql.=" and ter.tipo_id_tercero= '" . $filtros['tipo_id_tercero'] . "' ";
          if ($filtros['tercero_id'])
          {
          $sql.=" and ter.tercero_id= '" . $filtros['tercero_id'] . "' ";
          }
          if ($filtros['nombre_tercero'] != "")
          $sql .= "AND     ter.nombre_tercero ILIKE '%" . $filtros['nombre_tercero'] . "%' ";

          $fdatos = explode("-", $filtros['fecha_inicio']);
          $fedatos = $fdatos[2] . "-" . $fdatos[1] . "-" . $fdatos[0];

          if ($filtros['fecha_inicio'] != "")
          $sql.=" and o.fecha_orden = '" . $fedatos . "' ";

          if ($filtros['orden'] != "")
          $sql.=" and o.orden_pedido_id= " . $filtros['orden'] . " ";

          if ($filtros['farmacia'] != "")
          $sql .= " AND     e.razon_social  ILIKE '%" . $filtros['farmacia'] . "%' "; */

        //echo $sql;

        /* $cont = "select COUNT(*) from (" . $sql . ") AS A";
          $this->ProcesarSqlConteo($cont, $offset);
          $sql .= "ORDER BY o.orden_pedido_id  DESC ";
          $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset; */
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

    function GuardarNovedadOrdenCompra($compra_orden_detalle_id, $observacion_id, $descripcion, $fecha_posible_envio) {
        $sql = "INSERT INTO novedades_ordenes_compras(
                        item_id, 
                        observacion_orden_compra_id,
                        descripcion,
                        fecha_posible_envio
                    )
                    VALUES (
                        " . $compra_orden_detalle_id . ", 
                        " . $observacion_id . ",
                        '" . $descripcion . "',
                    ";

        if (!isset($fecha_posible_envio)) {
            $sql .= "NULL";
        } else {
            $sql .= " '" . $fecha_posible_envio . "' ";
        }

        $sql .= ")";
        ///echo $sql."<br><br><br>";exit();
        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }

    function ObtenerUltimoIdNovedadOrdenCompra() {
        $sql = "SELECT MAX(novedad_orden_compra_id) AS novedad_orden_compra_id 
                    FROM novedades_ordenes_compras";

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

    function GuardarArchivoNovedadOrdenCompra($novedad_orden_compra_id, $archivo, $nombre_original_archivo) {
        $sql = "INSERT INTO archivos_novedades_ordenes_compras(
                        novedad_orden_compra_id, 
                        archivo,
                        nombre_original_archivo
                    )
                    VALUES (
                        " . $novedad_orden_compra_id . ", 
                        '" . $archivo . "',
                        '" . $nombre_original_archivo . "'
                    )";
        //echo $sql."<br><br><br>";
        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }

    function ActualizarNovedadOrdenCompra($novedad_orden_compra_id, $observacion_id, $descripcion, $fecha_posible_envio) {
        $sql = "UPDATE novedades_ordenes_compras
                    SET
                        observacion_orden_compra_id = " . $observacion_id . ",
                        descripcion = '" . $descripcion . "', ";

        if (!isset($fecha_posible_envio)) {
            $sql .= " fecha_posible_envio = NULL ";
        } else {
            $sql .= " fecha_posible_envio = '" . $fecha_posible_envio . "' ";
        }

        $sql .= " WHERE
                        novedad_orden_compra_id = " . $novedad_orden_compra_id . "";

        //echo $sql."<br><br><br>";exit();

        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }

    function BorrarNovedadOrdenCompra($novedad_orden_compra_id) {
        $sql = "DELETE FROM novedades_ordenes_compras 
                    WHERE
                        novedad_orden_compra_id = " . $novedad_orden_compra_id . "";
        //echo $sql."<br><br><br>";exit();
        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }

    function BorrarArchivoNovedadOrdenCompra($novedad_orden_compra_id) {
        $sql = "DELETE FROM archivos_novedades_ordenes_compras 
                    WHERE
                        novedad_orden_compra_id = " . $novedad_orden_compra_id . "";
        //echo $sql."<br><br><br>";exit();
        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }

    function BorrarUnicoArchivoNovedadOrdenCompra($archivo_novedad_orden_compra_id) {
        $sql = "DELETE FROM archivos_novedades_ordenes_compras 
                    WHERE
                        archivo_novedad_orden_compra_id = " . $archivo_novedad_orden_compra_id . "";
        //echo $sql."<br><br><br>";exit();
        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false;
        }
        $resultado->Close();
        return true;
    }

    /*
     * Funcion donde se Consultan las observaciones de las ordenes de compra
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarObservacionesOrdenesCompra() {
        $sql = "SELECT  *
                    FROM observaciones_ordenes_compras";

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
     * Funcion donde se Consulta el detalle de la orden de compra
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarDetalleCompra($orden_pedido_id) {

        $sql = " select  d.orden_pedido_id,
                        d.codigo_producto,
                        d.numero_unidades,
                        d.valor,
                        fc_descripcion_producto(d.codigo_producto) as producto
                        
              from      compras_ordenes_pedidos_detalle d
                        
            WHERE       
                      d.orden_pedido_id='" . $orden_pedido_id . "'; ";

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
     * Funcion donde se Consulta  los datos de la empresa que realiza el pedido
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function EmpresasOrden_Pedido() {

        $sql = " SELECT     DISTINCT   	c.empresa_id,
                                        c.sw_unificada,
                                        e.razon_social
				        FROM  				         	compras_ordenes_pedidos c,
                                        empresas e
				       WHERE         			      c.empresa_id=e.empresa_id 
				       AND  	    			        c.sw_unificada=0 ";


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
     * Funcion donde se Consulta  las ordenes de pedido
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarProveedoresOrden_Pedido($empresa, $offset) {

        $sql = "  SELECT  DISTINCT  t.codigo_proveedor_id,
                                    t.sw_unificada,
                                    ter.nombre_tercero,
                                    ter.tipo_id_tercero,
                                    ter.tercero_id,
                                    t.empresa_id
                    FROM       			compras_ordenes_pedidos t,
                                    terceros ter,
                                    terceros_proveedores p
					WHERE     				t.codigo_proveedor_id=p.codigo_proveedor_id
          AND        				  p.tipo_id_tercero=ter.tipo_id_tercero
          AND			      	  	p.tercero_id=ter.tercero_id  
          AND  					      t.sw_unificada=0
          AND                 t.empresa_id='" . $empresa . "'
          AND                 t.estado='1'
          AND                   t.orden_pedido_id in (select orden_pedido_id from compras_ordenes_pedidos_detalle )
          AND  				        t.codigo_proveedor_id in ( select  p.codigo_proveedor_id
                                              FROM       			    terceros_proveedores p
                                              GROUP BY   		    	p.codigo_proveedor_id ,ter.nombre_tercero)
      
         ";
        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;


        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

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
     * Funcion donde consulta el detalle de las ordenes de pedido por proveedor
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ListarDetalleOrdenPedidoXProveedor($empresa, $proveedor) {

        $sql = "  select           o.orden_pedido_id,
                                    o.codigo_proveedor_id,
                                    o.empresa_id,
                                    o.fecha_orden,
                                    o.estado, 
                                    d.codigo_producto,
                                    d.numero_unidades,
                                    d.valor,
                                    i.descripcion as producto,
                                    m.molecula_id,
                                    m.descripcion as molecula,
                                    l.laboratorio_id,
                                    l.descripcion as laboratorio
					from                      compras_ordenes_pedidos o,
                                    compras_ordenes_pedidos_detalle d,
                                    inventarios_productos i,
                                    inv_subclases_inventarios s,
                                    inv_moleculas m,
                                    inv_clases_inventarios c,
                                    inv_laboratorios l
                WHERE               o.orden_pedido_id=d.orden_pedido_id
                and                 d.codigo_producto=i.codigo_producto
                and                 i.grupo_id=s.grupo_id
                and 	              i.clase_id=s.clase_id
                and                 i.subclase_id=s.subclase_id
                and                 s.molecula_id=m.molecula_id
                and                 s.grupo_id=c.grupo_id
                and                 s.clase_id=c.clase_id
                and                 c.laboratorio_id=l.laboratorio_id
                and                 o.estado = '1' 
                and                 o.sw_unificada='0'
                and                 d.numero_unidades > numero_unidades_recibidas
                and                 o.empresa_id='" . $empresa . "'
                and                  o.codigo_proveedor_id='" . $proveedor . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[$rst->fields[5]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function ListarDetalleOrdenPedidoXProveedorDos($empresa, $proveedor) {

        $sql = "  select           o.orden_pedido_id,
                                    o.codigo_proveedor_id,
                                    o.empresa_id,
                                    o.fecha_orden,
                                    o.estado, 
                                    d.codigo_producto,
                                    d.numero_unidades,
                                    d.valor,
                                    i.descripcion as producto,
                                    m.molecula_id,
                                    m.descripcion as molecula,
                                    l.laboratorio_id,
                                    l.descripcion as laboratorio
					from                      compras_ordenes_pedidos o,
                                    compras_ordenes_pedidos_detalle d,
                                    inventarios_productos i,
                                    inv_subclases_inventarios s,
                                    inv_moleculas m,
                                    inv_clases_inventarios c,
                                    inv_laboratorios l
                WHERE               o.orden_pedido_id=d.orden_pedido_id
                and                 d.codigo_producto=i.codigo_producto
                and                 i.grupo_id=s.grupo_id
                and 	              i.clase_id=s.clase_id
                and                 i.subclase_id=s.subclase_id
                and                 s.molecula_id=m.molecula_id
                and                 s.grupo_id=c.grupo_id
                and                 s.clase_id=c.clase_id
                and                 c.laboratorio_id=l.laboratorio_id
                and                 o.estado = '1' 
                and                 o.sw_unificada='0'
                and                 d.numero_unidades_recibidas IS NULL
                and                 o.empresa_id='" . $empresa . "'
                and                  o.codigo_proveedor_id='" . $proveedor . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[$rst->fields[5]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se consulta las ordenes de compras que van hacer unidicadas
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function UnificarDatos2($codigo_producto, $proveedor) {

        $sql = " select DISTINCT  c.codigo_proveedor_id,
										   c.empresa_id,
										   c.sw_unificada ,
										   d.codigo_producto,
										  sum(d.numero_unidades)as numero,
										  sum( d.valor) as valor,
										  sum( d.porc_iva) as porc 	
									from  compras_ordenes_pedidos c,
										   compras_ordenes_pedidos_detalle d
									WHERE  c.orden_pedido_id=d.orden_pedido_id
									and   d.codigo_producto='" . $codigo_producto . "'
									and   c.codigo_proveedor_id='" . $proveedor . "'
									and   c.sw_unificada=0 group by 1,2,3,4  ";

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
     * Funcion donde  se insertan el documento de pedido
     * @return boolean de acuerdo a la ejecucion del sql.
     */

    function ingresarDocumentoDePedido($empresa_id, $codigo_proveedor_id, $observacion) {

        $indice = array();

        $sql = "SELECT NEXTVAL('productos_pendientes_ordenpedido_prod_pend_op_id_seq') AS sq ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        if (!$rst->EOF) {
            $indice = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        $sqlerror = "SELECT setval('productos_pendientes_ordenpedido_prod_pend_op_id_seq', " . ($indice['sq']) . "); ";

        $this->ConexionTransaccion();

        $sql = "INSERT INTO	 	Productos_pendientes_OrdenPedido( 
                              Prod_Pend_OP_id,		
                              empresa_id,		
                              codigo_proveedor_id,
                              observacion,										
                              usuario_id,		
                              fecha_registro,
                              sw_asignado
										)
						VALUES( 
                              " . $indice['sq'] . ",
                              '" . $empresa_id . "',
                              " . $codigo_proveedor_id . ",
                              '" . $observacion . "',
                              " . UserGetUID() . ",
                              NOW(),
                              0
                              ); ";


        if (!$rst = $this->ConexionTransaccion($sql)) {
            return false;
        }

        $this->Commit();
        return true;
    }

    /*
     * Funcion donde se selecciona el ultimo registro del documento de pedido generado.
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function SeleccionarDocumentoDePedido($empresa, $proveedor) {

        $sql = " SELECT COALESCE(MAX(prod_pend_op_id),0) as id FROM productos_pendientes_ordenpedido ";


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
     * Funcion donde se selecciona el ultimo registro del documento de pedido generado.
     * @return boolean de acuerdo a la ejecucion del sql.
     */

    function InsertarDatosPendientes($datos, $id) {

        foreach ($datos as $item => $fila) {

            $this->ConexionTransaccion();
            $sql .= "INSERT INTO	 	Productos_pendientes_OrdenPedido_d( 
                                Prod_Pend_OP_id_d,		
                                Prod_Pend_OP_id,
                                empresa_id,		
                                codigo_producto,
                                numero_unidades,										
                                valor,		
                                porc_iva,
                                fecha_registro
										)
						VALUES( 
			                          NEXTVAL('productos_pendientes_ordenpedido_d_prod_pend_op_id_d_seq'),
                                " . $id . ",
                                '" . $fila['empresa_id'] . "',
                                '" . $fila['codigo_producto'] . "',
                                '" . $fila['numero'] . "',
                                '" . $fila['valor'] . "',
                                '" . $fila['porc'] . "',
                                NOW()
                            ); ";
        }

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    /*
     * Funcion donde se consulta el documento de pedido
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarDocumentoPedidoOP($prod_pend_op_id) {

        $sql = "SELECT    d.prod_pend_op_id_d,
                      d.prod_pend_op_id,
                      d.empresa_id,
                      d.codigo_producto,
                      d.numero_unidades,
                      d.valor,
                      d.porc_iva,
                      d.fecha_registro
					FROM 	      productos_pendientes_ordenpedido_d d
					WHERE     	d.prod_pend_op_id=" . $prod_pend_op_id . "
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
     * Funcion donde se selecciona actualizan las ordenes de pedido que han sido unificadas y las preordenes que ya han sido asignadas a una orden de compra
     * @return boolean de acuerdo a la ejecucion del sql.
     */

    function ActualizarSw_unificadaOp($empresa_id, $codigo_proveedor_id, $orden_pedido_id, $prod_pend_op_id) {

        $sql = "	 UPDATE  compras_ordenes_pedidos
                  SET    sw_unificada=1              
                  WHERE  empresa_id ='" . $empresa_id . "'
                  AND    codigo_proveedor_id =" . $codigo_proveedor_id . "
                  AND   	orden_pedido_id!=" . $orden_pedido_id . ";
		";

        $sql .= "	   UPDATE  productos_pendientes_ordenpedido
                    SET    sw_asignado=1              
                    WHERE  empresa_id ='" . $empresa_id . "'
                    AND    codigo_proveedor_id =" . $codigo_proveedor_id . "
                    AND    	prod_pend_op_id=" . $prod_pend_op_id . ";
		";


        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false;
        }
        return true;
    }

    /*
     * Funcion donde se Inserta el detalle de las ordenes de pedido
     * @return boolean de acuerdo a la ejecucion del sql.
     */

    function Ingresarcompras_ordenes_pedidos_detalle_d($datos, $orden_pedido_id) {

        foreach ($datos as $item => $fila) {
            $this->ConexionTransaccion();

            $sql .= "INSERT INTO   compras_ordenes_pedidos_detalle
                        (
                                orden_pedido_id,
                                codigo_producto,
                                numero_unidades,
                                valor,
                                porc_iva,
                                estado,
                                acta_autorizacion,
                                numero_unidades_recibidas
                                
                        )
                VALUES
                (
                  " . $orden_pedido_id . ",
                  '" . $fila['codigo_producto'] . "',
                  " . $fila['numero_unidades'] . ",
                  " . $fila['valor'] . ",
                  " . $fila['porc_iva'] . ",
                  1,
                  null,
                  null
                  
                  );
                  ";
        }
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    /*
     * Funcion donde se  consulta la informacion del usuario actual
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function consultarInformacionUsuarioActual() {

        $sql = "  SELECT  usuario_id,
        							 usuario,
        							 nombre,
        							 descripcion
					FROM     system_usuarios 
					WHERE    usuario_id =" . UserGetUID() . " ";

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
     * Funcion donde se  consulta las  condiciones activas   ya establecidas para las ordenes de compras
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarCondicionesEstablecidas() {

        $sql = " SELECT condicion_compra_id,
                      descripcion,
                      estado 
					FROM 	inv_condiciones_compra
					WHERE 	estado = '1' ";

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

    function Observaciones($orden_pedido_id) {

        $sql = " SELECT observacion
					FROM 	compras_ordenes_pedidos
					WHERE 	orden_pedido_id = " . $orden_pedido_id . " ";

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

    /*
     * Funcion donde se  listan los proveedores de las ordenes de compras
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ListarProveedoresOrdenCompra() {

        $sql = " SELECT  DISTINCT  	t.codigo_proveedor_id,
                                  ter.nombre_tercero
              FROM    			      compras_ordenes_pedidos t,
                                  terceros ter,
                                  terceros_proveedores p
              WHERE  				      t.codigo_proveedor_id=p.codigo_proveedor_id
              AND    				      p.tipo_id_tercero=ter.tipo_id_tercero
              AND					        p.tercero_id=ter.tercero_id 
              and                 t.estado='1'					
              AND    			      	t.codigo_proveedor_id in ( select  p.codigo_proveedor_id
              FROM       			    terceros_proveedores p
              GROUP BY   		      p.codigo_proveedor_id ,ter.nombre_tercero) ";

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
     * Funcion donde se  los proveedores de ordenes de compras que existen 
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    Function ConsultarProveedoresOrdenCompra($codprove) {

        $sql = "SELECT  DISTINCT  t.codigo_proveedor_id,
                                t.fecha_orden,
                                ter.nombre_tercero,
                                ter.tipo_id_tercero,
                                ter.tercero_id,
                                u.nombre,
                                t.orden_pedido_id
					FROM   	               compras_ordenes_pedidos t,
                  							terceros ter,
                  							terceros_proveedores p,
                  							system_usuarios u
					WHERE      t.codigo_proveedor_id=p.codigo_proveedor_id
					AND        p.tipo_id_tercero=ter.tipo_id_tercero
					AND		     p.tercero_id=ter.tercero_id  
					AND       t.sw_unificada=0
					AND        t.estado='1'
					AND        t.usuario_id=u.usuario_id
					AND        t.codigo_proveedor_id='" . $codprove . "'	";
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
     * Funcion donde se  consulta el detalle de las ordenes  de compras
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarOrdenCompraDetalle($orden) {

        $sql = " 		select      d.codigo_producto,
                                d.numero_unidades,
                                d.valor,
                                d.numero_unidades_recibidas,
                                i.descripcion as producto,
                                m.molecula_id,
                                m.descripcion as molecula,
                                l.laboratorio_id,
                                l.descripcion as laboratorio,
                                i.cantidad as cant,
                                u.unidad_id as abreviatura,
                                i.contenido_unidad_venta
						from			          compras_ordenes_pedidos_detalle d,
                                inventarios_productos i,
                                inv_subclases_inventarios s,
                                inv_moleculas m,
                                inv_clases_inventarios c,
                                inv_laboratorios l,
                                unidades u
						WHERE			   	d.codigo_producto=i.codigo_producto
						and     			i.grupo_id=s.grupo_id
						and 				  i.clase_id=s.clase_id
						and     			i.subclase_id=s.subclase_id
						and     			s.molecula_id=m.molecula_id
						and     			s.grupo_id=c.grupo_id
						and     			s.clase_id=c.clase_id
						and     			c.laboratorio_id=l.laboratorio_id
						and     			i.unidad_id=u.unidad_id
						and     			d.orden_pedido_id='" . $orden . "'
						and     			d.numero_unidades_recibidas IS NULL
						and     			d.estado='1'
						UNION
											select 		d.codigo_producto,
    														d.numero_unidades,
    														d.valor,
    														d.numero_unidades_recibidas,
    														i.descripcion as producto,
    														m.molecula_id,
    														m.descripcion as molecula,
    														l.laboratorio_id,
    														l.descripcion as laboratorio,
    														i.cantidad as cant,
    														u.unidad_id as abreviatura,
                                i.contenido_unidad_venta                                
											from    	compras_ordenes_pedidos_detalle d,
                                inventarios_productos i,
                                inv_subclases_inventarios s,
                                inv_moleculas m,
                                inv_clases_inventarios c,
                                inv_laboratorios l,
                                unidades u
											WHERE   	d.codigo_producto=i.codigo_producto
											and    		i.grupo_id=s.grupo_id
											and 		i.clase_id=s.clase_id
											and     	i.subclase_id=s.subclase_id
											and     	s.molecula_id=m.molecula_id
											and     	s.grupo_id=c.grupo_id
											and     	s.clase_id=c.clase_id
											and     	c.laboratorio_id=l.laboratorio_id
											and     	i.unidad_id=u.unidad_id
									        and     	d.orden_pedido_id='" . $orden . "'
											and   		d.numero_unidades_recibidas < d.numero_unidades 
											and    		d.estado='1' ";

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
     * Funcion donde se  consulta el detalle de las ordenes  de compras
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarOrdenCompraDetalle_($orden) {

        $sql = " 	SELECT 	orden_pedido_id,
            							codigo_producto,
            							numero_unidades,
            							valor,
            							porc_iva,
            							numero_unidades_recibidas,
            							preorden_detalle_id,
            							valor_unitario
					FROM 	          compras_ordenes_pedidos_detalle
					WHERE 	        orden_pedido_id='" . $orden . "'
					and    	        numero_unidades_recibidas IS NULL
					and              estado='1'
    					UNION
                SELECT 	orden_pedido_id,
                        codigo_producto,
                        numero_unidades,
                        valor,
                        porc_iva,
                        numero_unidades_recibidas,
                        preorden_detalle_id,
                        valor_unitario
                FROM 		compras_ordenes_pedidos_detalle
                WHERE   orden_pedido_id='" . $orden . "'
                and   	numero_unidades_recibidas < numero_unidades 
                and     estado='1' ";

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
     * Funcion donde se Inserta a la tabla temporal las ordenes de pedidos que van hacer unificadas
     * @return boolean de acuerdo a la ejecucion del sql.
     */

    function Ingresar_tmpcompras_ordenes_pedidos($datos, $proveedor) {

        foreach ($datos as $item => $fila) {
            $this->ConexionTransaccion();
            if (empty($fila['valor_unitario'])) {
                $fila['valor_unitario'] = 0;
            }
            if (empty($fila['preorden_detalle_id'])) {
                $fila['preorden_detalle_id'] = 0;
            }


            if (!empty($fila['numero_unidades_recibidas'])) {
                $sql .= "INSERT INTO   tmp_Unificadas_Orden_Pedidos
	                        (
                                  item_id,
                                  orden_pedido_id,
                                  codigo_proveedor_id,
                                  codigo_producto,
                                  numero_unidades,
                                  valor,
                                  porc_iva,
                                  numero_unidades_recibidas,
                               
                                  valor_unitario,
                                  usuario_id,
                                  fecha_registro
						    )
	                VALUES
	                (
                                nextval('tmp_unificadas_orden_pedidos_item_id_seq'),
                                " . $fila['orden_pedido_id'] . ",
                                " . $proveedor . ",
                                '" . $fila['codigo_producto'] . "',
                                " . $fila['numero_unidades'] . ",
                                " . $fila['valor'] . ",
                                " . $fila['porc_iva'] . ",
                                " . $fila['numero_unidades_recibidas'] . ",
                              
                                " . $fila['valor_unitario'] . ",
                                " . UserGetUID() . ",
                                NOW()
                                );
                                ";
            } else {
                $sql .= "INSERT INTO   tmp_Unificadas_Orden_Pedidos
	                        (
                                  item_id,
                                  orden_pedido_id,
                                  codigo_proveedor_id,
                                  codigo_producto,
                                  numero_unidades,
                                  valor,
                                  porc_iva,
                                  numero_unidades_recibidas,
                                
                                  valor_unitario,
                                  usuario_id,
                                  fecha_registro
						    )
	                VALUES
	                (
                                  nextval('tmp_unificadas_orden_pedidos_item_id_seq'),
                                  " . $fila['orden_pedido_id'] . ",
                                  " . $proveedor . ",
                                  '" . $fila['codigo_producto'] . "',
                                  " . $fila['numero_unidades'] . ",
                                  " . $fila['valor'] . ",
                                  " . $fila['porc_iva'] . ",
                                  0,
                                 
                                  " . $fila['valor_unitario'] . ",
                                  " . UserGetUID() . ",
                                  NOW()
                                  );
                                  ";
            }
        }
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    /*
     * Funcion donde se  consulta la informacion temporal de la orden de compras
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Consultar_tmp_OrdenPedidoDetalle($proveedor) {

        $sql = " 	SELECT 	DISTINCT d.orden_pedido_id,
            								To_char(d.fecha_registro,'yyyy-mm-dd') as fecha_registro, 
            								u.nombre
        						FROM  	tmp_unificadas_orden_pedidos d,
                            system_usuarios u
        						WHERE 	d.codigo_proveedor_id=" . $proveedor . " and d.usuario_id=u.usuario_id ";

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
     * Funcion donde se  consulta la informacion de la tabla temporal 
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Consultar_tmp_OrdenPedido($proveedor, $orden) {

        $sql = " 	SELECT 	DISTINCT d.orden_pedido_id,
                    To_char(d.fecha_registro,'yyyy-mm-dd') as fecha_registro, 
                    u.nombre
                    FROM 	tmp_unificadas_orden_pedidos d,
                    system_usuarios u
						WHERE 	d.codigo_proveedor_id=" . $proveedor . " 
						and     d.orden_pedido_id=" . $orden . "
						and     d.usuario_id=u.usuario_id ";
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
     * Funcion donde se  elimina la informacion de la tabla temporal
     * @return boolean de acuerdo a la ejecucion del sql.
     */

    function Eliminar_tmp_OrdenPedido($proveedor) {

        $this->ConexionTransaccion();
        $sql = "DELETE FROM tmp_unificadas_orden_pedidos ";
        $sql .= "WHERE codigo_proveedor_id = " . $proveedor . " ;";

        if (!$rst = $this->ConexionTransaccion($sql)) {
            return false;
        }

        $this->Commit();

        return true;
    }

    /*
     * Funcion donde consulta la informacion  detallada del proveedor
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function InformacionDetalleProveedor($proveedor) {

        $sql = "	SELECT 	t.tipo_id_tercero,
        								t.tercero_id,
        								t.nombre_tercero,
        								t.dv,
        								p.codigo_proveedor_id
						FROM    terceros t,
						        terceros_proveedores p
						WHERE   t.tipo_id_tercero=p.tipo_id_tercero
						and     t.tercero_id=p.tercero_id
						and     t.codigo_proveedor_id=" . $proveedor . "; ";

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
     * Funcion donde consulta la informacion  temporal de la unificacion de la orden de compra, se realiza por proveedor
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function SeleccionarInformacionTmp_OrdenPedido($proveedor) {

        $sql = " SELECT 	orden_pedido_id,
                        codigo_proveedor_id,
                        codigo_producto,
                        numero_unidades,
                        valor,
                        porc_iva,
                        numero_unidades_recibidas,
                        preorden_detalle_id,
                        valor_unitario
					FROM 		      tmp_unificadas_orden_pedidos
					WHERE 		    codigo_proveedor_id = '" . $proveedor . "' ";

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
     * Funcion donde se actualiza los estados de las ordenes de compras pasan a unificadas cuando se esta unificando por proveedor
     * @return boolean de acuerdo a la ejecucion del sql.
     */

    function Ingresarcompras_ordenes_pedidos_d($datos, $orden_pedido_id) {
        $this->ConexionTransaccion();
        foreach ($datos as $item => $fila) {

            $sql .= "INSERT INTO   compras_ordenes_pedidos_detalle
                        (
                                orden_pedido_id,
                                codigo_producto,
                                numero_unidades,
                                valor,
                                porc_iva,
                                estado,
                                numero_unidades_recibidas,
                                valor_unitario
					              )
                          VALUES
                          (
                              " . $orden_pedido_id . ",
                              '" . $fila['codigo_producto'] . "',
                              " . $fila['numero_unidades'] . ",
                              " . $fila['valor'] . ",
                              " . $fila['porc_iva'] . ",
                              1,
                              " . $fila['numero_unidades_recibidas'] . ",
                              " . $fila['valor_unitario'] . "
                              );
                              ";
        }
        /* print_r($sql); */
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    /*
     * Funcion donde se actualiza los estados de las ordenes de compras pasan a unificadas cuando se esta unificando por proveedor
     * @return boolean de acuerdo a la ejecucion del sql.
     */

    function ActuEstadosOrdenesPedidoUnificadas($datos, $proveedor) {

        foreach ($datos as $item => $fila) {
            $sql .= "	UPDATE  compras_ordenes_pedidos
                  SET    sw_unificada='1',
                  estado='3'				  
				  
                  WHERE  codigo_proveedor_id = " . $proveedor . "
                  AND   orden_pedido_id =" . $fila['orden_pedido_id'] . "; ";
        }
        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false;
        }
        return true;
    }

    /*
     * Funcion donde se selecciona Informacion adicional de la orden de compras.
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    Function ConsultarInformacionOrdenCompra($orden) {

        $sql = " SELECT     orden_pedido_id,
                          To_char(fecha_orden,'yyyy-mm-dd') as fecha_orden,
                          observacion
                FROM 	    compras_ordenes_pedidos
                WHERE 	  orden_pedido_id = '" . $orden . "' ";

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
     * Funcion donde se Consultan el tipo id .
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarTipoId() {

        $sql = "SELECT    tipo_id_tercero, descripcion ";
        $sql .= "FROM      tipo_id_terceros ";
        $sql .= "ORDER BY  tipo_id_tercero, descripcion ";
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

    function ListarTercerosProveedores($EmpresaId, $TipoId, $Nombre) {

        $sql = "SELECT  
		
				ter.*,
				p.*
		
  				FROM       	
				
				terceros ter,
                terceros_proveedores p
				
                WHERE     			
									ter.nombre_tercero ILIKE '%" . $Nombre . "%'
									AND
									ter.tipo_id_tercero ILIKE '%" . $TipoId . "%'
									AND
									ter.tipo_id_tercero=p.tipo_id_tercero
									AND			        
									ter.tercero_id=p.tercero_id  
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

    function InformacionTercerosProveedores($CodigoProveedorId) {
        $sql = "SELECT  
		
				ter.*,
				p.*
		
  				FROM       	
				
				terceros ter,
                terceros_proveedores p
				
                WHERE     			
									p.codigo_proveedor_id = " . $CodigoProveedorId . "
									AND
									p.tipo_id_tercero = ter.tipo_id_tercero
									AND			        
									p.tercero_id = ter.tercero_id
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

    function IngresarOrdenCompra($orden_pedido_id, $codigoproveedorid, $empresa_id) {

        $this->ConexionTransaccion();
        $sql .= "INSERT INTO   compras_ordenes_pedidos
                        (
                                	orden_pedido_id,
									codigo_proveedor_id,
									empresa_id,
									fecha_orden,
									usuario_id,
									estado
						)
                          VALUES
                          (
                              " . $orden_pedido_id . ",
                              '" . $codigoproveedorid . "',
                              '" . $empresa_id . "',
                              NOW(),
                              " . UserGetUID() . ",
                              1
                          )
                              ";

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    function ListaLaboratorios() {
        // $codigo_barras=eregi_replace("'","-",$CodigoBarras);

        $sql = "
            Select 
                    laboratorio_id,
                    descripcion
                    from
                          inv_laboratorios
                    where
                          estado = '1'
                          
                          ORDER BY descripcion ASC
                          ";


        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function ListaMoleculas() {
        // $codigo_barras=eregi_replace("'","-",$CodigoBarras);

        $sql = "
            Select 
                    molecula_id,
                    descripcion
                    from
                          inv_moleculas
                    where
                          estado = '1'
                          
                          ORDER BY descripcion ASC
                          ";


        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function ListaProductosInventario($CodigoProducto, $Descripcion, $Concentracion, $Empresa_Id, $ClaseId, $SubClaseId, $offset, $codigo_proveedor_id) {

        $sql = "    Select grp.descripcion as Grupo,
                    sub.descripcion as Subclase,
                    prod.codigo_producto,
                    fc_descripcion_producto(prod.codigo_producto) as descripcion,
                    prod.porc_iva as iva,
                    grp.sw_medicamento,
                    case when COALESCE (aa.valor_pactado,0)=0 then round((inv.costo_ultima_compra)/((COALESCE(prod.porc_iva,0)/100)+1),2) else aa.valor_pactado end as costo_ultima_compra,
                    case when COALESCE (aa.valor_pactado,0)=0 then 0 else 1 end as tiene_valor_pactado,
                    prod.cantidad,
                    com.descripcion as presentacion
                    from  inventarios_productos as prod
                    JOIN inventarios as inv ON (prod.codigo_producto = inv.codigo_producto)
                    JOIN inv_subclases_inventarios as sub ON (prod.subclase_id = sub.subclase_id) and   (prod.clase_id = sub.clase_id) and   (prod.grupo_id = sub.grupo_id)
                    JOIN inv_clases_inventarios as cla ON (sub.clase_id = cla.clase_id) and   (sub.grupo_id = cla.grupo_id)
                    JOIN inv_grupos_inventarios as grp ON (cla.grupo_id = grp.grupo_id)
                    LEFT JOIN inv_presentacioncomercial as com ON (prod.presentacioncomercial_id = com.presentacioncomercial_id )
                    left join (
                        select b.codigo_producto, b.valor_pactado 
                        from contratacion_produc_proveedor a 
                        inner join contratacion_produc_prov_detalle b on a.contratacion_prod_id = b.contratacion_prod_id
                        where a.empresa_id='{$Empresa_Id}' and a.codigo_proveedor_id = {$codigo_proveedor_id}
                    ) as aa on prod.codigo_producto = aa.codigo_producto
                    WHERE TRUE
                    and inv.empresa_id = '{$Empresa_Id}'
                    and	  prod.estado = '1' ";

        if ($Descripcion != "")
            $sql .= "AND    prod.descripcion ILike '" . $Descripcion . "%' ";

        if ($CodigoProducto != "")
            $sql .= "AND	  prod.codigo_producto ILike '" . $CodigoProducto . "' ";
        if ($Concentracion != "")
            $sql .= "AND	  prod.contenido_unidad_venta ILike '%" . $Concentracion . "%' ";

        if ($ClaseId != "")
            $sql .= "AND   prod.clase_id = '" . $ClaseId . "' ";

        if ($SubClaseId != "")
            $sql .= "AND   prod.subclase_id = '" . $SubClaseId . "' ";

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;

        $sql .= "ORDER BY prod.descripcion ASC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function listar_productos($empresa_id, $codigo_proveedor_id, $numero_orden, $termino_busqueda) {

        $sql_aux = " ";

        if ($numero_orden > 0)
            $sql_aux .= " AND a.codigo_producto not in ( select a.codigo_producto from compras_ordenes_pedidos_detalle a where a.orden_pedido_id = {$numero_orden} ) ";


        $sql = " SELECT 
                e.descripcion as descripcion_grupo,
                d.descripcion as descripcion_clase,
                c.descripcion as descripcion_subclase,
                a.codigo_producto,
                fc_descripcion_producto(a.codigo_producto) as descripcion_producto,
                a.porc_iva as iva,
                e.sw_medicamento,
                CASE WHEN COALESCE (aa.valor_pactado,0)=0 then round((b.costo_ultima_compra)/((COALESCE(a.porc_iva,0)/100)+1),2) else aa.valor_pactado end as costo_ultima_compra,
                CASE WHEN COALESCE (aa.valor_pactado,0)=0 then 0 else 1 end as tiene_valor_pactado,
                a.cantidad,
                f.descripcion as presentacion,
                a.sw_regulado
                FROM  inventarios_productos a
                INNER JOIN inventarios b ON a.codigo_producto = b.codigo_producto
                INNER JOIN inv_subclases_inventarios c ON a.subclase_id = c.subclase_id AND a.clase_id = c.clase_id AND a.grupo_id = c.grupo_id
                INNER JOIN inv_clases_inventarios d ON c.clase_id = d.clase_id AND c.grupo_id = d.grupo_id
                INNER JOIN inv_grupos_inventarios e ON d.grupo_id = e.grupo_id
                LEFT JOIN inv_presentacioncomercial f ON a.presentacioncomercial_id = f.presentacioncomercial_id
                LEFT JOIN (
                    SELECT b.codigo_producto, b.valor_pactado 
                    FROM contratacion_produc_proveedor a 
                    INNER JOIN contratacion_produc_prov_detalle b on a.contratacion_prod_id = b.contratacion_prod_id
                    WHERE a.empresa_id= '{$empresa_id}' AND a.codigo_proveedor_id = {$codigo_proveedor_id} 
                ) as aa on a.codigo_producto = aa.codigo_producto
                WHERE b.empresa_id = '{$empresa_id}' AND a.estado = '1' {$sql_aux} AND 
                (
                    a.descripcion ILIKE '%{$termino_busqueda}%' or
                    a.codigo_producto ILIKE '%{$termino_busqueda}%' or
                    a.contenido_unidad_venta ILIKE '%{$termino_busqueda}%' or
                    c.descripcion ILIKE '%{$termino_busqueda}%'
                )";


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
        ;
    }

    function AgregarItemOC($OrdenPedidoId, $Empresa_Id, $CodigoProducto, $NumeroUnidades, $Valor, $PorcIva) {

        $this->ConexionTransaccion();
        $sql = "INSERT INTO   compras_ordenes_pedidos_detalle
                (
                              orden_pedido_id,
                              codigo_producto,
                              numero_unidades,
                              valor,
                              porc_iva,
                              estado
                 )
                VALUES
                (
                " . $OrdenPedidoId . ",
                '" . $CodigoProducto . "',
                " . $NumeroUnidades . ",
                " . $Valor . ",
                " . $PorcIva . ",
                1
                );
                ";

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    function ConsultarObtenerUltimoItemOC() {
        $sql = "SELECT MAX(item_id) AS item_id 
                    FROM compras_ordenes_pedidos_detalle";

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

    function GuardarAuditoriaItemOriginalActualizadoOC($OrdenPedidoId, $item_id, $UsuarioId) {
        $this->ConexionTransaccion();
        $sql = "INSERT INTO compras_ordenes_pedidos_detalle_auditoria (SELECT nextval('compras_ordenes_pedidos_detalle_auditoria_auditoria_item_id_seq'::regclass) AS auditoria_item_id, '" . $OrdenPedidoId . "' AS orden_pedido_id, '" . $item_id . "' AS item_id, codigo_producto, numero_unidades, valor, porc_iva, estado, NULL AS acta_autorizacion, NULL AS numero_unidades_recibidas, NULL AS preorden_detalle_id, NULL AS valor_unitario, NULL AS fecha_vencimiento_temp, NULL AS lote_temp, NULL AS sw_ingresonc, 'Editar' AS accion, '" . $UsuarioId . "' AS usuario_id, now() AS fecha_registro,'Original' AS version FROM compras_ordenes_pedidos_detalle WHERE item_id = " . $item_id . ");";

        //echo $sql."<br><br><br><br>";

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    function GuardarAuditoriaItemModificadoActualizadoOC($OrdenPedidoId, $item_id, $cantidad, $valor, $UsuarioId) {
        $this->ConexionTransaccion();
        $sql = "INSERT INTO compras_ordenes_pedidos_detalle_auditoria (SELECT nextval('compras_ordenes_pedidos_detalle_auditoria_auditoria_item_id_seq'::regclass) AS auditoria_item_id, '" . $OrdenPedidoId . "' AS orden_pedido_id, '" . $item_id . "' AS item_id, codigo_producto, '" . $cantidad . "' AS numero_unidades, '" . $valor . "' AS valor, porc_iva, estado, NULL AS acta_autorizacion, NULL AS numero_unidades_recibidas, NULL AS preorden_detalle_id, NULL AS valor_unitario, NULL AS fecha_vencimiento_temp, NULL AS lote_temp, NULL AS sw_ingresonc, 'Editar' AS accion, '" . $UsuarioId . "' AS usuario_id, now() AS fecha_registro,'Modificado' AS version FROM compras_ordenes_pedidos_detalle WHERE item_id = " . $item_id . ");";

        //echo $sql."<br><br><br><br>";

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    function GuardarAuditoriaItemOC($OrdenPedidoId, $item_id, $CodigoProducto, $NumeroUnidades, $Valor, $PorcIva, $Estado, $Accion, $UsuarioId) {
        $this->ConexionTransaccion();
        $sql = "INSERT INTO compras_ordenes_pedidos_detalle_auditoria (orden_pedido_id, item_id, codigo_producto, numero_unidades, valor, porc_iva, estado, accion, usuario_id)
                    VALUES ({$OrdenPedidoId}, {$item_id}, '{$CodigoProducto}', {$NumeroUnidades}, {$Valor}, {$PorcIva}, {$Estado}, '{$Accion}', {$UsuarioId});";

        //echo $sql;

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    function ObtenerItemOC($item_id) {
        $sql = "SELECT orden_pedido_id, codigo_producto, numero_unidades, valor, porc_iva, estado 
                    FROM compras_ordenes_pedidos_detalle 
                    WHERE item_id = '{$item_id}'";

        //echo $sql;

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

    function ConsultarOC($orden_pedido_id) {

        $sql = " select
                        d.item_id,
                        d.orden_pedido_id,
                        d.codigo_producto,
                        d.porc_iva as iva,
                        d.numero_unidades,
                        d.valor,
                        fc_descripcion_producto(i.codigo_producto) as descripcion,
                        u.unidad_id as abreviatura 
                        from      compras_ordenes_pedidos_detalle d,
                        inventarios_productos i,
                        inv_subclases_inventarios s,
                        inv_clases_inventarios c,
                        unidades u
            WHERE       d.codigo_producto=i.codigo_producto
            and         i.grupo_id=s.grupo_id
            and       	i.clase_id=s.clase_id
            and         i.subclase_id=s.subclase_id
            and         s.grupo_id=c.grupo_id
            and         s.clase_id=c.clase_id
            and         i.unidad_id=u.unidad_id
		        and         d.orden_pedido_id='" . $orden_pedido_id . "'; ";

        $sql = " 
                select 
                d.item_id,
                d.orden_pedido_id,
                d.codigo_producto,
                d.porc_iva as iva,
                d.numero_unidades,
                d.valor,
                fc_descripcion_producto(d.codigo_producto) as descripcion,
                '' as abreviatura
                from compras_ordenes_pedidos_detalle d
                where d.orden_pedido_id='{$orden_pedido_id}'; ";

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

    function VerificarExistenciaDocumentoTemporal($orden_pedido_id, $empresa_id) {
        $sql = "SELECT
                        ibmtoc.orden_pedido_id
                    FROM 
                        inv_bodegas_movimiento_tmp_ordenes_compra ibmtoc
                        INNER JOIN compras_ordenes_pedidos cop ON (ibmtoc.orden_pedido_id = cop.orden_pedido_id)
                    WHERE
                        ibmtoc.orden_pedido_id = " . $orden_pedido_id . " 
                        AND cop.empresa_id = '" . $empresa_id . "' ";

        //echo $sql."<br><br><br>";

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

    function VerificarExistenciaDocumento($orden_pedido_id, $empresa_id) {
        $sql = "SELECT
                        ibmoc.orden_pedido_id
                    FROM 
                            inv_bodegas_movimiento_ordenes_compra ibmoc
                            INNER JOIN compras_ordenes_pedidos cop ON (ibmoc.orden_pedido_id = cop.orden_pedido_id)
                    WHERE
                        ibmoc.orden_pedido_id = " . $orden_pedido_id . " 
                        AND cop.empresa_id = '" . $empresa_id . "' ";

        //echo $sql."<br><br><br>";

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

    function VerificarEmpresa($orden_pedido_id, $empresa_id) {
        $sql = "SELECT
                        orden_pedido_id,
                        codigo_proveedor_id
                    FROM 
                        compras_ordenes_pedidos
                    WHERE
                        orden_pedido_id = " . $orden_pedido_id . "
                        AND empresa_id = '" . $empresa_id . "' ";

        //echo $sql."<br><br><br>";

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

    function Eliminar_ComprasOrdenPedido_Detalle($OrdenPedidoId, $Empresa_Id) {
        $this->ConexionTransaccion();
        $sql = "DELETE FROM compras_ordenes_pedidos_detalle ";
        $sql .= "WHERE orden_pedido_id = " . $OrdenPedidoId . " ;";

        if (!$rst = $this->ConexionTransaccion($sql)) {
            return false;
        }

        $this->Commit();

        return true;
    }

    function Eliminar_ComprasOrdenPedido($OrdenPedidoId, $Empresa_Id) {
        $this->ConexionTransaccion();
        $sql = "DELETE FROM compras_ordenes_pedidos ";
        $sql .= "WHERE orden_pedido_id = " . $OrdenPedidoId . " ;";

        if (!$rst = $this->ConexionTransaccion($sql)) {
            return false;
        }

        $this->Commit();

        return true;
    }

    function SeleccionarInformacionEmpresa($empresaid) {

        $sql = "SELECT 	empresa_id,
							tipo_id_tercero,
							id,
							razon_social,
							representante_legal,
							codigo_sgsss,
							direccion,
							telefonos,
							fax,
							codigo_postal,
							website,
							email
							
					FROM    empresas
					WHERE  empresa_id = '" . $empresaid . "' ";

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

    function ConsultarInformacionProveedor($codigo_proveedor_id) {


        $sql = " SELECT  p.codigo_proveedor_id,
							 p.tipo_id_tercero,
							 p.tercero_id,
							 p.estado,
							 t.direccion,
							 t.telefono,
							 t.fax,
							 t.email,
							 t.celular,
							 t.nombre_tercero,
							 t.dv,
							 p.porcentaje_rtf,
							 p.porcentaje_ica	
					FROM     terceros t, 
						     terceros_proveedores p
					where    p.tipo_id_tercero=t.tipo_id_tercero
					and      p.tercero_id=t.tercero_id
					and      p.codigo_proveedor_id=" . $codigo_proveedor_id . " ";

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

    function ConsultarCondicionesCompra($orden_id) {

        $sql = "  SELECT  condicionoc_id,
						empresa_id,
						orden_pedido_id,
						condicion
				FROM    condiciones_orden_compra
				WHERE   orden_pedido_id=" . $orden_id . " ";

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

    function ConsultarDetalleDeOrdenCompra($orden_pedido_id) {
        //$this->debug=true;
        $sql = "  
            select
            a.orden_pedido_id,
            a.codigo_producto,
            fc_descripcion_producto(a.codigo_producto) as nombre,
            a.numero_unidades,
            a.valor,
            a.porc_iva,
            a.estado,
            a.item_id,
            a.valor_unitario
            from
            compras_ordenes_pedidos_detalle as a
            where
            a.orden_pedido_id=" . $orden_pedido_id . "
            and a.estado = '1';";

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

    /* function ConsultarDetalleDeOrdenCompra($orden_pedido_id)
      {
      $sql = "  SELECT 	d.item_id,
      d.orden_pedido_id,
      d.codigo_producto,
      fc_descripcion_producto(d.codigo_producto) as nombre,
      d.numero_unidades,
      d.valor,
      i.porc_iva,
      d.estado,
      i.descripcion as producto,
      i.cantidad,
      d.valor_unitario,
      u.abreviatura

      FROM    compras_ordenes_pedidos_detalle d,
      inventarios_productos i,
      unidades u
      where   d.codigo_producto=i.codigo_producto
      and    d.orden_pedido_id=".$orden_pedido_id."
      and   d.estado=1
      and    i.unidad_id=u.unidad_id ";
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

    function SeleccionarMaxiPedido() {
        //$this->debug=true;
        $sql = " SELECT   MAX(orden_pedido_id) AS numero FROM compras_ordenes_pedidos; ";

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

    function SeleccionarMaxiPedido2($proveedor) {
        //$this->debug=true;
        $sql = " SELECT   MAX(orden_pedido_id) AS numero FROM compras_ordenes_pedidos; ";

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

    function AnularOC($orden_pedido_id) {
        $sql = "	 UPDATE  compras_ordenes_pedidos
                  SET    estado= '2'              
                  WHERE  orden_pedido_id = " . $orden_pedido_id . " ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        return true;
    }

    /*
     * Funcion donde se Verifica que el Documento de Compra No se Encuentre Abierto en Bodega..
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function DocumentoCompraEnBodega($empresa_id, $orden_pedido_id) {
        $sql = "SELECT   	";
        $sql .= "          *   ";
        $sql .= "FROM 	    inv_bodegas_movimiento_tmp_ordenes_compra ";
        $sql .= "WHERE     	orden_pedido_id = " . $orden_pedido_id . "  ";
        $sql .= "           ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se  consulta el detalle de las ordenes  de compras
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function DetalleOrdenCompra($CodigoProducto, $Descripcion, $Concentracion, $Empresa_Id, $ClaseId, $SubclaseId, $orden_pedido_id, $offset) {
        //$this->debug=true;
        if (!empty($ClaseId))
            $filtro = "  and             inv.clase_id = '" . $ClaseId . "'  ";

        if (!empty($SubclaseId))
            $filtro .= "  and             inv.subclase_id = '" . $SubclaseId . "'  ";

        $sql = " 	SELECT fc_descripcion_producto(cod.codigo_producto) as nombre,	
                        cod.*
              		FROM 	          compras_ordenes_pedidos_detalle cod,
                                  inventarios_productos inv
        					WHERE 	        cod.orden_pedido_id= " . $orden_pedido_id . "
        					and    	        COALESCE(cod.numero_unidades_recibidas,0) = 0
        					and             cod.estado='1'
                  and             cod.codigo_producto = inv.codigo_producto
                  and             inv.codigo_producto ILIKE '%" . $CodigoProducto . "%'
                  and             inv.descripcion ILIKE '%" . $Descripcion . "%'
                  and             inv.contenido_unidad_venta ILIKE '%" . $Concentracion . "%'
                  
                  " . $filtro . "
             ";

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;

        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

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
     * Funcion donde  se actualiza la orden de Compra
     * @return boolean de acuerdo a la ejecucion del sql.
     */

    function EjecutarConsultas($sql) {
        /* $this->debug=true; */
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
     * Funcion donde  se actualiza la orden de Compra
     * @return boolean de acuerdo a la ejecucion del sql.
     */

    function ModificarOrdenCompra($orden_pedido_id, $empresa_id, $item_id, $cantidad, $valor) {
        // $this->debug=true;
        $sql = "	UPDATE 
                         compras_ordenes_pedidos_detalle
                  SET    valor=" . $valor . ",
                         numero_unidades=" . $cantidad . "
                  WHERE  
                         item_id = " . $item_id . "
                  AND    orden_pedido_id =" . $orden_pedido_id . " ;
        ";

        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false;
        }
        return true;
    }

    /**/

    function Solicitudes_Generadas_x_Rotacion($datos, $offset) {
        /* $this->debug=true; */

        $sql = " SELECT SUM(A.cantidad) as cantidad,
				A.subclase_id,
				A.contenido_unidad_venta,
				B.descripcion||'-'||A.contenido_unidad_venta||'-'||C.descripcion as molecula,
				A.unidad_id
				FROM  (
				SELECT 
				SUM(a.cantidad) as cantidad,
				b.subclase_id,
				upper(replace(b.contenido_unidad_venta, ' ', ''))as contenido_unidad_venta,
				d.unidad_id
				FROM
				solicitud_gerencia as a
				JOIN inventarios_productos as b ON(a.codigo_producto = b.codigo_producto)
				JOIN inv_subclases_inventarios as c ON(b.grupo_id = c.grupo_id)
				AND (b.clase_id = c.clase_id)
				AND (b.subclase_id = c.subclase_id)
				JOIN unidades as d ON (b.unidad_id = d.unidad_id)
				group by b.subclase_id,
				upper(replace(b.contenido_unidad_venta, ' ', '')),
				d.unidad_id
				UNION ALL
				SELECT
				SUM(a.cantidad) as cantidad,
				b.cod_principio_activo as subclase_id,
				upper(replace(a.concentracion, ' ', ''))as contenido_unidad_venta,
				a.unidad_id
				FROM
				solicitud_gerencia as a
				JOIN inv_med_cod_principios_activos as b ON(a.cod_principio_activo = b.cod_principio_activo)
				JOIN unidades as c ON (a.unidad_id = c.unidad_id)
				WHERE
				a.cod_principio_activo IS NOT NULL
				group by b.cod_principio_activo,
				upper(replace(a.concentracion, ' ', '')),
				a.unidad_id
				) A
				JOIN inv_med_cod_principios_activos as B ON (A.subclase_id = B.cod_principio_activo)
				JOIN unidades as C ON(A.unidad_id = C.unidad_id)
				WHERE TRUE
				AND A.contenido_unidad_venta ILIKE '%" . $datos['concentracion'] . "%'
				AND B.descripcion ILIKE '%" . $datos['molecula'] . "%'
				AND C.descripcion ILIKE '%" . $datos['unidad'] . "%'
				GROUP BY A.subclase_id,
				A.contenido_unidad_venta,
				B.descripcion,
				C.descripcion,
				A.unidad_id ";

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;
        $sql .= " order by B.descripcion ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

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

    function Productos_EquivalenciasRotacion($empresa_id, $preorden_id) {
        /* $this->debug=true; */
        $sql = " 
				SELECT
				Y.codigo_producto,
				fc_descripcion_producto(Y.codigo_producto) as producto,
				round((X.costo_ultima_compra)/((COALESCE(Y.porc_iva,0)/100)+1),2) as costo_ultima_compra,
				upper(replace(Y.contenido_unidad_venta, ' ', ''))as contenido_unidad_venta,
				XX.descripcion||'-'||upper(replace(Y.contenido_unidad_venta, ' ', ''))||'-'||XY.descripcion as molecula,
				Y.unidad_id,
				Y.subclase_id,
				XX.descripcion,
				C.descripcion as laboratorio,
				X.existencia,
				Y.porc_iva,
				COALESCE(ZZ.total,0) as total,
				case  when ZZ.codigo_producto IS NULL
				THEN ''
				ELSE 'disabled checked' END AS seleccionado
				FROM
				inventarios as X
				JOIN inventarios_productos as Y ON (X.codigo_producto = Y.codigo_producto)
				AND (X.empresa_id = '" . trim($empresa_id) . "')
				JOIN inv_subclases_inventarios as S ON (Y.grupo_id = S.grupo_id)
				AND (Y.clase_id = S.clase_id)
				AND (Y.subclase_id = S.subclase_id)
				JOIN inv_clases_inventarios as C ON (S.grupo_id = C.grupo_id)
				AND (S.clase_id = C.clase_id)
				JOIN inv_med_cod_principios_activos as XX ON (Y.subclase_id = XX.cod_principio_activo)
				JOIN unidades as XY ON (Y.unidad_id = XY.unidad_id)
				JOIN
				(
						SELECT 
						A.subclase_id,
						B.descripcion as molecula,
						A.contenido_unidad_venta,
						A.unidad_id,
						C.descripcion
						FROM  (
						SELECT 
						b.subclase_id,
						upper(replace(b.contenido_unidad_venta, ' ', ''))as contenido_unidad_venta,
						d.unidad_id
						FROM
						solicitud_gerencia as a
						JOIN inventarios_productos as b ON(a.codigo_producto = b.codigo_producto)
						JOIN inv_subclases_inventarios as c ON(b.grupo_id = c.grupo_id)
						AND (b.clase_id = c.clase_id)
						AND (b.subclase_id = c.subclase_id)
						JOIN unidades as d ON (b.unidad_id = d.unidad_id)
						group by b.subclase_id,
						upper(replace(b.contenido_unidad_venta, ' ', '')),
						d.unidad_id
						UNION ALL
						SELECT
						b.cod_principio_activo as subclase_id,
						upper(replace(a.concentracion, ' ', ''))as contenido_unidad_venta,
						a.unidad_id
						FROM
						solicitud_gerencia as a
						JOIN inv_med_cod_principios_activos as b ON(a.cod_principio_activo = b.cod_principio_activo)
						JOIN unidades as c ON (a.unidad_id = c.unidad_id)
						WHERE
						a.cod_principio_activo IS NOT NULL
						group by b.cod_principio_activo,
						upper(replace(a.concentracion, ' ', '')),
						a.unidad_id
						) A
						JOIN inv_med_cod_principios_activos as B ON (A.subclase_id = B.cod_principio_activo)
						JOIN unidades as C ON(A.unidad_id = C.unidad_id)
						GROUP BY A.subclase_id,
						B.descripcion,
						A.contenido_unidad_venta,
						A.unidad_id,
						C.descripcion
				) AS Z ON (Y.subclase_id = Z.subclase_id)
				AND (upper(replace(Y.contenido_unidad_venta, ' ', ''))=Z.contenido_unidad_venta )
				AND (Y.unidad_id=Z.unidad_id )
				LEFT JOIN (
								SELECT
								codigo_producto,
								SUM(COALESCE(cantidad,0)) as total
								FROM
								informacion_preorden_detalle
								WHERE TRUE
								AND preorden_id = '" . trim($preorden_id) . "'
								group by codigo_producto
								) as ZZ ON (Y.codigo_producto = ZZ.codigo_producto)
				WHERE TRUE;";

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

    /**/

    function BuscarCabecera_Preorden($empresa, $opc) {
        if ($opc == '1') {
            $sql = " 
		SELECT
		preorden_id,
		farmacia_id,
		usuario_id,
		sw_preorden
		FROM
		informacion_preorden AS a
		WHERE TRUE
		AND farmacia_id = '" . trim($empresa) . "'
		AND sw_preorden = '1'; ";
        } else {
            $sql = "INSERT INTO informacion_preorden( ";
            $sql .= "preorden_id,";
            $sql .= "farmacia_id,";
            $sql .= "usuario_id";
            $sql .= ")VALUES( ";
            $sql .= "	DEFAULT,	";
            $sql .= "	'" . trim($empresa) . "',	";
            $sql .= "	'" . UserGetUID() . "'	";
            $sql .= "       )RETURNING(preorden_id); ";
        }
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

    function Detalle_Preorden($sql) {
        $this->ConexionTransaccion();
        if (!$rst = $this->ConexionTransaccion($sql))
            return false;
        $this->Commit();
        return true;
    }

    function Preorden_Compra($empresa_id, $preorden_id) {
        /* $this->debug=true; */
        $sql = " 
				SELECT
				b.codigo_proveedor_id,
				d.nombre_tercero,
				b.codigo_producto,
				fc_descripcion_producto(b.codigo_producto) as producto,
				b.cantidad,
				b.valor_unitario,
				b.porc_iva,
				b.usuario_id
				FROM
				informacion_preorden AS a
				JOIN informacion_preorden_detalle as b ON (a.preorden_id = b.preorden_id)
				JOIN terceros_proveedores as c ON (b.codigo_proveedor_id = c.codigo_proveedor_id)
				JOIN terceros as d ON (c.tipo_id_tercero = d.tipo_id_tercero)
				AND (c.tercero_id = d.tercero_id)
				WHERE TRUE
				AND a.farmacia_id = '03'
				AND b.sw_unificada = '0'
				AND a.sw_preorden = '1'
				order by d.nombre_tercero,producto;";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[$rst->fields[1]] [$rst->fields[0]] [] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function ConsultarProveedoresValorPactado($empresa, $medicamento) {

        $sql = " SELECT 	distinct d.contratacion_prod_id,
										c.tipo_id_tercero,
										c.tercero_id,
										d.valor_total_pactado,
										t.nombre_tercero,
										c.codigo_proveedor_id
									 
								FROM   terceros t left join  contratacion_produc_proveedor c ON ( c.tipo_id_tercero=t.tipo_id_tercero
						and      c.tercero_id=t.tercero_id ) LEFT JOIN  contratacion_produc_prov_detalle d ON(d.contratacion_prod_id=c.contratacion_prod_id)
						WHERE    
						           c.estado='1'
						and      c.sw_cliente = '0'
						and      d.empresa_id='" . $empresa . "'	
		                and      d.codigo_producto='" . $medicamento . "'			
						order by    t.nombre_tercero; ";

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

    /**/

    function ProveedoresProducto($laboratorio) {
        /* $this->debug=true; */
        $sql = " SELECT 
						b.codigo_proveedor_id,
						a.nombre_tercero||' :: '||a.tipo_id_tercero||'-'|| a.tercero_id as tercero
						FROM
						terceros as a
						JOIN terceros_proveedores as b ON (a.tipo_id_tercero = b.tipo_id_tercero)
						AND (a.tercero_id = b.tercero_id)
						WHERE TRUE
						AND a.nombre_tercero ILIKE '%" . $laboratorio . "%'
						AND a.tipo_bloqueo_id ='1'
						AND b.estado ='1'
						order by tercero;";

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

    Function Consultar_Proveedores_Sin_contrato($empresa, $producto) {
        $sql = " SELECT  PRO.codigo_proveedor_id,
								PRO.tipo_id_tercero,
								PRO.tercero_id,
								TER.nombre_tercero
						FROM    terceros_proveedores PRO,
								terceros TER
						WHERE   PRO.empresa_id = '" . $empresa . "'
								AND     PRO.estado = '1'
								AND     PRO.tipo_id_tercero=TER.tipo_id_tercero
								AND     PRO.tercero_id=TER.tercero_id 
                              AND   	PRO.codigo_proveedor_id not in (	 SELECT 	c.codigo_proveedor_id
																			FROM    contratacion_produc_prov_detalle d,
																		            contratacion_produc_proveedor   c
																	      WHERE    d.contratacion_prod_id=c.contratacion_prod_id
																			and      c.estado='1'
																			and      c.sw_cliente = '0'
																			and      d.empresa_id='" . $empresa . "'	
															                and      d.codigo_producto='" . $producto . "'	)					
					    ORDER  BY TER.nombre_tercero ";

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

    /*  INGRESO DE LA CABECERA DE LA PREORDEN */

    function IngresarPre_orden($empresa) {

        $this->ConexionTransaccion();
        $sql .= "INSERT INTO  informacion_preorden
                        (
                                	preorden_id,
									farmacia_id,
									usuario_id,
									fecha_registro,
									sw_preorden
						)
                          VALUES
                          (
									default,
									'" . $empresa . "',
									" . UserGetUID() . ",
									NOW(),
									1
                          )returning (preorden_id);
                              ";

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        $datos = array();
        while (!$rst1->EOF) {
            $datos[] = $rst1->GetRowAssoc($ToUpper);
            $rst1->MoveNext();
        }
        $rst1->Close();
        return $datos;
    }

    /* INGRESAR PREORDEN DETALLE */

    function EliminarItem_Preorden($datos, $preorden_id) {

        $this->ConexionTransaccion();
        $sql = " DELETE FROM informacion_preorden_detalle 
						WHERE
						codigo_producto= '" . trim($datos['codigo_producto']) . "'
						AND codigo_proveedor_id = '" . trim($datos['codigo_proveedor_id']) . "'
						AND preorden_id = '" . trim($preorden_id) . "';";

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    /* ELIMINAR PREDIDOS DE GERENCIA */

    function Eliminar_Pedidos_gerencia($empresa, $codigo_producto) {

        $this->ConexionTransaccion();
        $sql = "delete from solicitud_gerencia
				where  empresa_id='" . $empresa . "'
				and    codigo_producto='" . $codigo_producto . "' ";

        if (!$rst = $this->ConexionTransaccion($sql)) {
            return false;
        }

        $this->Commit();

        return true;
    }

    /**
     * Funcion donde se obtiene el nombre de un usuario
     *
     * @param int $usuario Identificacion del usuario
     *
     * @return mixed
     */
    function ObtenerInformacionUsuario($usuario) {
        $sql .= "SELECT	nombre ";
        $sql .= "FROM		system_usuarios ";
        $sql .= "WHERE	usuario_id = " . $usuario . " ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        if (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function UnidadesNegocio($codigo_unidad_negocio) {
        if ($codigo_unidad_negocio != "")
            $filtro = "	AND codigo_unidad_negocio = '" . $codigo_unidad_negocio . "'	";
        $sql = " 	SELECT
					codigo_unidad_negocio,
					descripcion,
					imagen
					FROM
					unidades_negocio
					where
					estado = '1'
					" . $filtro . ";";

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

    function ConsultarOrdenComprasGeneradas_reportePDF($orden_id) {

        $sql = "  SELECT o.orden_pedido_id,
					            o.codigo_proveedor_id,
											o.empresa_id,
											o.usuario_id,
											To_char(o.fecha_orden,'dd-mm-yyyy') as fecha_registro,
								      s.nombre,
											e.razon_social,
                      e.tipo_id_tercero as tipo_id_empresa,
                      e.id as id_empresa,
											ter.nombre_tercero,
											ter.tipo_id_tercero,
											ter.tercero_id,
                      ter.direccion,
                      ter.telefono,
											o.estado,
                      o.observacion,
					  o.codigo_unidad_negocio
					FROM        compras_ordenes_pedidos  o,
									    system_usuarios  s,
											empresas e,
											terceros_proveedores p,
											terceros ter
				  WHERE       o.empresa_id=e.empresa_id
              and     o.usuario_id=s.usuario_id
              and     o.codigo_proveedor_id=p.codigo_proveedor_id
              and     p.tipo_id_tercero=ter.tipo_id_tercero
              and     p.tercero_id=ter.tercero_id 
              AND     o.sw_unificada='0' ";


        $sql.=" and o.orden_pedido_id = '" . $orden_id . "' ";


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

    function Compras_Validador($orden_pedido_id, $codigo_proveedor_id) {
        /* $this->debug=true; */
        $sql = " select
								a.orden_pedido_id,
								a.codigo_proveedor_id,
								a.fecha_registro,
								b.codigo_unidad_negocio,
								b.descripcion,
								b.imagen
								FROM
								compras_ordenes_pedidos AS a
								LEFT JOIN unidades_negocio as b ON (a.codigo_unidad_negocio = b.codigo_unidad_negocio)
								WHERE       TRUE
								and         a.orden_pedido_id='" . $orden_pedido_id . "'
								and         a.codigo_proveedor_id='" . $codigo_proveedor_id . "' ";


        /* print_r($sql); */
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

    function ModificarUnidadNegocio($orden_pedido_id, $codigo_proveedor_id, $codigo_unidad_negocio) {
        if ($codigo_unidad_negocio == "")
            $unidad_negocio = " NULL ";
        else
            $unidad_negocio = "'" . $codigo_unidad_negocio . "'";
        /* $this->debug=true; */
        $sql = "	 UPDATE compras_ordenes_pedidos
                  SET    codigo_unidad_negocio= " . $unidad_negocio . "              
                  WHERE  orden_pedido_id = " . $orden_pedido_id . "
                  AND    codigo_proveedor_id =" . $codigo_proveedor_id . " ;
        ";

        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false;
        }
        return true;
    }

    function validar_producto($codigo_producto) {

        $sql = " select * from inventarios_productos a where a.codigo_producto = '{$codigo_producto}' ";

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

    function consultar_contrato_proveedor($codigo_proveedor) {

        $sql = " select * from contratacion_produc_proveedor a where a.	codigo_proveedor_id = {$codigo_proveedor}";

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
    
    function consultar_politicas_productos_contrato($empresa_id, $codigo_proveedor_id, $codigo_producto) {
        
        $sql = "select *  
                from contratacion_produc_proveedor a 
                inner join contratacion_produc_prov_detalle b on a.contratacion_prod_id = b.contratacion_prod_id
                left join contratacion_produc_proveedor_politicas c on b.contrato_produc_prov_det_id = c.contrato_produc_prov_det_id
                where a.empresa_id='{$empresa_id}' and a.codigo_proveedor_id = {$codigo_proveedor_id} and b.codigo_producto = '{$codigo_producto}' ";

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

}

?>