<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: FacturasDespachoSQL.class.php
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */
/**
 * Clase : SalidasProductosSQL
 * 
 *  
 * @package IPSOFT-SIIS
 * @version $Revision: 
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */
IncludeClass('BodegasDocumentos');

class FacturasDespachoSQL extends ConexionBD {

    /**
     * Constructor de la clase
     */
    function FacturasDespachoSQL() {
        
    }

    /**
     * Funcion donde se verifica el permiso del usuario para el ingreso al modulo
     *
     * @return array $datos vector que contiene la informacion de la consulta del codigo de
     * la empresa y la razon social
     */
    function ObtenerPermisos() {
        //$this->debug = true;
        $sql = "SELECT   EM.empresa_id, ";
        $sql .= "         EM.razon_social AS razon_social ";
        $sql .= "FROM     userpermisos_facturasdespachos CP, empresas EM ";
        $sql .= "WHERE    CP.usuario_id = " . UserGetUID() . " ";
        $sql .= "         AND CP.empresa_id = EM.empresa_id ";

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
     * Funcion donde busca los documentos en de despacho del cliente
     *
     * @return booleano
     */
    function BuscarDespachoClientes() {
        //$this->debug = true;
        $sql = "SELECT   a.*,b.nombre_tercero ";
        $sql .= "FROM     inv_bodegas_movimiento_despachos_clientes a, terceros b ";
        $sql .= "WHERE   a.tipo_id_tercero=b.tipo_id_tercero ";
        $sql .= "AND        a.tercero_id 	=b.tercero_id 	 ";
        $sql .= "AND        a.factura_gener 	=0 	 ";

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

    /**
     * Funcion donde busca los documentos en de despacho del cliente
     *
     * @return booleano
     */
    function BuscarDespachoFacturasGeneradas() {
        //$this->debug = true;
        $sql = "SELECT   a.*,b.nombre_tercero ";
        $sql .= "FROM     inv_facturas_despacho a , terceros b ";
        $sql .= "WHERE   a.tipo_id_tercero=b.tipo_id_tercero ";
        $sql .= "AND        a.tercero_id 	=b.tercero_id 	 ";

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

    /**
     * Funcion donde busca los documentos en de despacho del cliente
     *
     * @return booleano
     */
    function BuscarTipoIdTercero() {
        //$this->debug = true;
        $sql = "SELECT   * ";
        $sql .= "FROM     tipo_id_terceros ";

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

    /**
     * Funcion donde se verifica el permiso del usuario para el ingreso al modulo
     * @return array $datos vector que contiene la informacion de la consulta del codigo de
     * la empresa y la razon social
     */
    function Tipos_Ids_Terceros() {
        //   $this->debug=true;
        $sql = "SELECT    tipo_id_tercero, descripcion ";
        $sql .= "FROM      tipo_id_terceros ";
        $sql .= "ORDER BY  tipo_id_tercero, descripcion ";

        $sql = "
            SELECT tipo_id_tercero, descripcion FROM tipo_id_terceros ORDER BY  tipo_id_tercero, descripcion
        ";


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

    /**
     * Funcion donde se Consulta la Informacion del Proveedor y se realizan los 
     * filtros de busqueda teniendo en cuenta diferentes  parametros de busqueda
     * @param array $filtro vector con los datos del request donde se encuentra el 
     * parametos de busqueda
     * @param array $offset vector con los datos del request donde se encuentra el
     * parametos de busqueda
     * @return array $datos vector que contiene la informacion consultada del paciente
     */
    function Terceros_Clientes($Formulario, $offset) {

        if ($Formulario['tipo_id_tercero'] != "")
            $filtro .= " AND a.tipo_id_tercero = '" . $Formulario['tipo_id_tercero'] . "' ";

        $sql = "
		SELECT
		DISTINCT a.tipo_id_tercero,
		a.tercero_id,
		a.direccion,
		a.telefono,
		a.email,
		a.nombre_tercero,
		a.tipo_bloqueo_id,
		c.descripcion as bloqueo,
		g.pais,
		f.departamento,
		municipio
		FROM
		terceros as a
		JOIN terceros_clientes as b ON (a.tipo_id_tercero = b.tipo_id_tercero) AND (a.tercero_id = b.tercero_id) AND (b.empresa_id = '" . $Formulario['empresa_id'] . "')
		LEFT JOIN inv_tipos_bloqueos as c ON (a.tipo_bloqueo_id = c.tipo_bloqueo_id)
		JOIN inv_bodegas_movimiento_despachos_clientes as d ON (a.tipo_id_tercero = b.tipo_id_tercero) AND (a.tercero_id = d.tercero_id)
		LEFT JOIN tipo_mpios as e ON (a.tipo_pais_id = e.tipo_pais_id) AND (a.tipo_dpto_id = e.tipo_dpto_id) AND (a.tipo_mpio_id = e.tipo_mpio_id)
		LEFT JOIN tipo_dptos as f ON (e.tipo_pais_id = f.tipo_pais_id) AND (e.tipo_dpto_id = f.tipo_dpto_id)
		LEFT JOIN tipo_pais as g ON (f.tipo_pais_id = g.tipo_pais_id) 
		WHERE a.nombre_tercero ILIKE '%" . $Formulario['nombre_tercero'] . "%'
		AND a.tercero_id ILIKE '%" . $Formulario['tercero_id'] . "%'
		";
        $sql .= $filtro;

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;
        $sql .= "GROUP BY a.tipo_id_tercero,
		a.tercero_id,
		a.direccion,
		a.telefono,
		a.email,
		a.nombre_tercero,
		a.tipo_bloqueo_id,
		c.descripcion,
		g.pais,
		f.departamento,
		municipio		";
        $sql .= " ORDER BY a.nombre_tercero ";
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

    function Listado_Pedidos($empresa_id, $filtros, $offset) {

        if ($filtros['pedido_cliente_id'] != "")
            $where .= " AND x.pedido_cliente_id = " . $filtros['pedido_cliente_id'] . " ";

        $sql = "SELECT
	a.tipo_id_tercero,
	a.tercero_id,
	c.nombre_tercero,
	c.direccion,
	a.pedido_cliente_id,
	a.fecha_registro,
	a.tipo_id_vendedor,
	a.vendedor_id,
	a.empresa_id,
	d.nombre,
	a.observacion
	FROM
	ventas_ordenes_pedidos as a 
	JOIN (
	SELECT DISTINCT
	x.pedido_cliente_id
	FROM
	inv_bodegas_movimiento_despachos_clientes AS x
	WHERE
	x.factura_gener = '0'
	AND x.empresa_id = '" . $empresa_id . "'
	" . $where . "
	) as b ON (a.pedido_cliente_id = b.pedido_cliente_id)
	JOIN terceros as c ON (a.tipo_id_tercero = c.tipo_id_tercero)
	AND (a.tercero_id = c.tercero_id)
	JOIN vnts_vendedores as d ON (a.tipo_id_vendedor = d.tipo_id_vendedor)
	AND (a.vendedor_id = d.vendedor_id)
	WHERE
	a.tipo_id_tercero = '" . $filtros['tipo_id_tercero'] . "'
	AND a.tercero_id = '" . $filtros['tercero_id'] . "'
	";


        $cont = "SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, $offset);
        $sql .= "ORDER BY a.fecha_registro DESC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;
	
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

    /**
     * Funcion donde busca los productos de despacho del documento
     *
     * @return booleano
     */
    function DocumentosDespacho($empresa_id, $pedido_cliente_id) {
        //$this->debug = true;
        $sql = "SELECT DISTINCT
	x.empresa_id,
	x.prefijo,
	x.numero,
	x.pedido_cliente_id
	FROM
	inv_bodegas_movimiento_despachos_clientes AS x
	WHERE
	x.factura_gener = '0' 
	AND x.pedido_cliente_id = '" . $pedido_cliente_id . "' 
	AND x.empresa_id = '" . $empresa_id . "';";
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

    /**
     * Funcion donde se consultan los permisos de un usuario
     *
     * @return array $datos vector que contiene la informacion de la consulta de los permisos de usuario
     * @param array $filtros vector con los datos del request donde se encuentran los
     *  parametos de busqueda
     *  @param string $pg_siguiente
     *  @param var $empresa donde se encuentra la empersa id
     * @return array $datos vector que contiene la informacion de los usuarios
     */
    function ConsultarClientesDespachos($filtros, $pg_siguiente, $empresa) {
        //$this->debug = true;
        $sql = "SELECT  * ";
        $sql .= "FROM    inv_bodegas_movimiento_despachos_clientes as a ";
        $sql .= "WHERE  a.empresa_id='" . $empresa . "' ";
        $sql .= "AND       a.tipo_id_tercero ='" . $filtros['id'] . "' ";
        $sql .= "AND       a.tercero_id ='" . $filtros['documento'] . "' ";
        $sql .= "AND       a.factura_gener=0 ";
        /* if($filtros['id'])
          {
          $whr.=" and a.usuario_id= ".$filtros['id']." ";
          }
          if($filtros['usuario'] != "")
          $whr .= "AND     b.usuario ILIKE '%".$filtros['usuario']."%' ";
         */
        // $l = " DISTINCT b.usuario";
        //if(!$this->ProcesarSqlConteo("SELECT COUNT($l) $whr",$pg_siguiente,null,50))
        //return false;
        //if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        //return false;
        //$sql  .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";

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

    /**
     * Funcion donde busca los documentos en de despacho del cliente
     *
     * @return booleano
     */
    function BuscarFacturasDespacho($numero, $tipo_id_tercero, $tercero_id) {
        //$this->debug = true;
        $sql = "SELECT   a.*,b.*,c.descripcion,d.descripcion as descripcion_unidad ";
        $sql .= "FROM     inv_facturas_despacho as a, inv_facturas_despacho_d as b, inventarios_productos as c, unidades as d ";
        $sql .= "WHERE   a.numero=" . $numero . " ";
        $sql .= "AND       a.tipo_id_tercero 	='" . $tipo_id_tercero . "' 	 ";
        $sql .= "AND       a.tercero_id 	='" . $tercero_id . "' 	 ";
        $sql .= "AND       a.numero 	=b.inv_facturas_despacho 	 ";
        $sql .= "AND       b.codigo_producto = c.codigo_producto 	 ";
        $sql .= "AND       c.unidad_id = d.unidad_id 	 ";

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

    /**
     * Funcion donde busca los productos bloqueados por lote
     *
     * @param var  $codigo_producto la informacion del codigo de producto
     * @return booleano
     */
    function BuscarDescripProducto($codigo_producto) {
        //$this->debug=true;
        $sql = "SELECT	* ";
        $sql .= "FROM		inventarios_productos ";
        $sql .= "WHERE	codigo_producto='" . $codigo_producto . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        if (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    /**
     * Funcion donde busca los documentos de la factura
     *
     * @return booleano
     */
    function Documento_Factura($documento_id) {
        //$this->debug=true;
        $sql = "SELECT	* ";
        $sql .= "FROM		documentos ";
        $sql .= "WHERE	tipo_doc_general_id='FV01' ";
        $sql .= "AND	  documento_id=" . $documento_id . " ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        if (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }
    
    /**
     * Funcion donde busca los documentos de la factura
     *
     * @return booleano
     */
    function tipo_pago() {
        //$this->debug=true;
        $sql = "SELECT	tipo_pago_id,descripcion ";
        $sql .= "FROM tipo_pago where tipo_pago_id !=0 ;";

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

    /**
     * Funcion donde se guarda la factura de despacho
     *
     * @param var  $empresa_id la informacion de la empresa
     * @param var  $numero la informacion del numeracion
     * @param var  $prefijo la informacion del prefijo
     * @param var  $documento_id la informacion del documento
     * @param var  $factura la informacion la factura
     * @param var  $tipo_id_tercero la informacion del tipo del tercero
     * @param var  $tercero_id la informacion del tercero id
     * @param var  $valor_total la informacion del valor total
     * @return booleano
     */
    function InsertarFactura($Documento, $Tercero, $Formulario, $Parametros_Retencion,$forma_pago) {

        $porcentaje_rtf = '0';
        $porcentaje_ica = '0';
        $porcentaje_reteiva = '0';
        $porcentaje_cree = $Tercero['porcentaje_cree'];

        if ($Parametros_Retencion['sw_rtf'] == '1' || $Parametros_Retencion['sw_rtf'] == '3')
            $porcentaje_rtf = $Tercero['porcentaje_rtf'];
        if ($Parametros_Retencion['sw_ica'] == '1' || $Parametros_Retencion['sw_ica'] == '3')
            $porcentaje_ica = $Tercero['porcentaje_ica'];
        if ($Parametros_Retencion['sw_reteiva'] == '1' || $Parametros_Retencion['sw_reteiva'] == '3')
            $porcentaje_reteiva = $Tercero['porcentaje_reteiva'];

        $usuario_id = UserGetUID();
        $sql = "INSERT INTO inv_facturas_despacho (  
                empresa_id, 
                factura_fiscal, 
                prefijo, 
                documento_id, 
                tipo_id_tercero, 
                tercero_id, 
                usuario_id, 
                tipo_id_vendedor, 
                vendedor_id, 
                pedido_cliente_id, 
                observaciones, 
                porcentaje_rtf, 
                porcentaje_ica, 
                porcentaje_reteiva,
                porcentaje_cree,
                tipo_pago_id)
                VALUES( 
                '{$Documento['empresa_id']}',
                '{$Documento['numeracion']}', 
                '{$Documento['prefijo']}', 
                '{$Documento['documento_id']}', 
                '{$Formulario['tipo_id_tercero']}', 
                '{$Formulario['tercero_id']}', 
                '{$usuario_id}', 
                '{$Formulario['tipo_id_vendedor']}', 
                '{$Formulario['vendedor_id']}', 
                '{$Formulario['pedido_cliente_id']}', 
                '{$Tercero['condiciones_cliente']}', 
                {$porcentaje_rtf}, 
                {$porcentaje_ica}, 
                {$porcentaje_reteiva},
                {$porcentaje_cree},
                {$forma_pago}    
                );";

        return $sql;
    }

    /**
     * Funcion donde se guarda la factura de despacho
     *
     * @param var  $empresa_id la informacion de la empresa
     * @param var  $numero la informacion del numeracion
     * @param var  $prefijo la informacion del prefijo
     * @param var  $documento_id la informacion del documento
     * @param var  $factura la informacion la factura
     * @param var  $tipo_id_tercero la informacion del tipo del tercero
     * @param var  $tercero_id la informacion del tercero id
     * @param var  $valor_total la informacion del valor total
     * @return booleano
     */
    function InsertarFactura_d($Documento, $valor) {

        $sql = "INSERT INTO inv_facturas_despacho_d(
                item_id, 
                empresa_id, 
                factura_fiscal, 
                prefijo, 
                codigo_producto, 
                cantidad, 
                valor_unitario, 
                lote, 
                fecha_vencimiento, 
                porc_iva ) 
                VALUES( 
                DEFAULT, 
                '{$Documento['empresa_id']}', 
                '{$Documento['numeracion']}', 
                '{$Documento['prefijo']}', 
                '{$valor['codigo_producto']}', 
                {$valor['cantidad']}, 
                {$valor['valor_unitario']}, 
                '{$valor['lote']}', 
                '{$valor['fecha_vencimiento']}', 
                {$valor['porcentaje_gravamen']} ); ";
        return $sql;
    }
    
    function getRealIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            $ip= $_SERVER['HTTP_CLIENT_IP'];
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip= $_SERVER['HTTP_X_FORWARDED_FOR'];
        if (!empty($_SERVER['REMOTE_ADDR']))
            $ip= $_SERVER['REMOTE_ADDR'];
      
       $sql = " SELECT ip ";
       $sql .= " FROM pc_crea_facturacion ";
       $sql .= " where ip='$ip' ;";
echo "<pre>";print_r($sql);
       if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        if (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        $ips="";
        if(!empty($datos['ip']))
            $ips=$datos['ip'];   
 
        return $ips;
    }

     /**
     * Funcion donde se guarda la relacion con el pc donde se factura el despacho
     *
     * @param var  $empresa_id la informacion de la empresa
     */
    function InsertarPcFactura($Documento,$sw_tipo_factura) {
        $ip = $this->getRealIP();

        $sql = "INSERT INTO pc_factura_clientes(
                ip, 
                prefijo, 
                factura_fiscal, 
                sw_tipo_factura, 
                fecha_registro ,
                empresa_id
                )VALUES( 
                '{$ip}', 
                '{$Documento['prefijo']}',     
                '{$Documento['numeracion']}',
                '$sw_tipo_factura',  
                 now(),
                '{$Documento['empresa_id']}'); ";
        return $sql;
    }
	
	
	/**
     * +Descripcion: Funcion encargada de actualizar en la tabla donde se almacenan
	 *	      		 los pedidos el campo estado_factura_fiscal en 1 para identificar
	 *		  		 que el pedido ya ha sido facturado
	 * @fecha 19/01/2017 
	 * @author Cristian Manuel Ardila Troches
     */
    function actualizarEstadoFacturaPedido($parametros) {
		
        $sql = "UPDATE ventas_ordenes_pedidos SET estado_factura_fiscal = '1'
				WHERE pedido_cliente_id = '{$parametros['pedido_cliente_id'] }'
					AND tipo_id_tercero = '{$parametros['tipo_id_tercero']}' 
					AND tercero_id = '{$parametros['tercero_id']}'
					AND tipo_id_vendedor = '{$parametros['tipo_id_vendedor'] }'
					AND vendedor_id = '{$parametros['vendedor_id']}' ; ";

        return $sql;     
    }
	

    /**
     * Funcion donde actualiza el estado de la activacion
     *
     * @param  var $documento_id contiene el documento id
     * @param  var $prefijo contiene el prefijo
     * @return booleano
     */
    function ActualizarNumeracion($Documento_Factura) {
        $sql = "UPDATE documentos ";
        $sql .= "SET    numeracion = (numeracion+1)  ";
        $sql .= "WHERE  empresa_id= '" . $Documento_Factura['empresa_id'] . "' ";
        $sql .= "AND documento_id= '" . $Documento_Factura['documento_id'] . "'  ";
        $sql .= "AND tipo_doc_general_id= 'FV01';  ";

        return $sql;
    }

    /**
     * Funcion donde actualiza el estado de la activacion
     *
     * @param  var $documento_id contiene el documento id
     * @param  var $prefijo contiene el prefijo
     * @return booleano
     */
    function Actualizar_Despachos($empresa_id, $prefijo, $numero) {
        $sql = "UPDATE inv_bodegas_movimiento_despachos_clientes ";
        $sql .= "SET    factura_gener = '1'  ";
        $sql .= "WHERE  empresa_id= '" . $empresa_id . "' ";
        $sql .= "AND prefijo= '" . $prefijo . "'  ";
        $sql .= "AND numero= '" . $numero . "';  ";

        return $sql;
    }

    /**
     * Funcion donde busca los productos bloqueados por lote
     *
     * @param var  $codigo_producto la informacion del codigo de producto
     * @return booleano
     */
    function Parametros_Retencion($empresa_id, $anio_retencion) {

        if ($anio_retencion != "") {
            $wh .= "AND	anio = '" . $anio_retencion . "' ";
        } else {
            $wh .= "AND	anio = TO_CHAR(NOW(),'YYYY') ";
        }
        $sql = "SELECT	* ";
        $sql .= "FROM	vnts_bases_retenciones ";
        $sql .= "WHERE	estado='1' ";
        $sql .= $wh;
        $sql .= "AND	empresa_id = '" . $empresa_id . "'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        if (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    /**
     * Funcion donde actualiza el estado de la factura despacho de clientes
     *
     * @param  var $numero contiene el numero
     * @return booleano
     */
    function Actualizarinv_facturas_despacho($numero, $valor_total) {
        //$this->debug=true;
        $this->ConexionTransaccion();

        if (!$rst = $this->ConexionTransaccion($sql)) {
            echo $this->mensajeDeError;
            return false;
        }
        $this->Commit();
    }

    function EjecutarSql($sql) {

        //$this->debug=true;

        $this->ConexionTransaccion();

        if (!$rst = $this->ConexionTransaccion($sql)) {
            //echo $this->mensajeDeError;
            return false;
        }
        $this->Commit();
        return true;
    }

    /* 		
      function Listado_FacturasCliente($empresa_id,$filtros,$offset)
      {

      if($filtros['prefijo']!="")
      $where .= " AND a.prefijo = '".$filtros['prefijo']."' ";
      if($filtros['numero']!="")
      $where .= " AND a.factura_fiscal = '".$filtros['numero']."' ";
      if($filtros['tipo_id_tercero']!="")
      $where .= " AND a.tipo_id_tercero = '".$filtros['tipo_id_tercero']."' ";
      if($filtros['pedido_cliente_id']!="")
      $where .= " AND a.pedido_cliente_id = '".$filtros['pedido_cliente_id']."' ";

      $sql = "SELECT
      a.empresa_id,
      a.factura_fiscal,
      a.prefijo,
      a.documento_id,
      i.descripcion,
      i.texto1,
      i.texto2,
      i.texto3,
      i.mensaje,
      a.tipo_id_tercero,
      a.tercero_id,
      c.nombre_tercero,
      c.direccion,
      c.telefono,
      h.pais||'-'||g.departamento ||'-'||f.municipio as ubicacion,
      a.fecha_registro,
      a.usuario_id,
      a.tipo_id_vendedor,
      a.vendedor_id,
      b.nombre,
      a.valor_total,
      a.saldo,
      a.pedido_cliente_id,
      a.observaciones,
      a.fecha_vencimiento_factura,
      a.porcentaje_rtf,
      a.porcentaje_ica,
      a.porcentaje_reteiva,
      e.razon_social,
      e.tipo_id_tercero as tipo_id_empresa,
      e.id,
      e.direccion as direccion_empresa,
      e.telefonos as telefono_empresa,
      e.digito_verificacion,
      l.pais as pais_empresa,
      k.departamento as departamento_empresa,
      j.municipio as municipio_empresa,
      TO_CHAR(a.fecha_registro,'YYYY') as anio_factura,
      m.subtotal,
      m.iva_total
      FROM
      inv_facturas_despacho as a
      JOIN vnts_vendedores as b ON (a.tipo_id_vendedor = b.tipo_id_vendedor)
      AND (a.vendedor_id = b.vendedor_id)
      JOIN terceros as c ON (a.tipo_id_tercero = c.tipo_id_tercero)
      AND (a.tercero_id = c.tercero_id)
      JOIN system_usuarios as d ON (a.usuario_id = d.usuario_id)
      JOIN empresas as e ON (a.empresa_id = e.empresa_id)
      JOIN tipo_mpios as f ON (c.tipo_pais_id = f.tipo_pais_id)
      AND (c.tipo_dpto_id = f.tipo_dpto_id)
      AND (c.tipo_mpio_id = f.tipo_mpio_id)
      JOIN tipo_dptos as g ON (f.tipo_pais_id = g.tipo_pais_id)
      AND (f.tipo_dpto_id = g.tipo_dpto_id)
      JOIN tipo_pais as h ON (g.tipo_pais_id = h.tipo_pais_id)

      JOIN tipo_mpios as j ON (e.tipo_pais_id = j.tipo_pais_id)
      AND (e.tipo_dpto_id = j.tipo_dpto_id)
      AND (e.tipo_mpio_id = j.tipo_mpio_id)
      JOIN tipo_dptos as k ON (j.tipo_pais_id = k.tipo_pais_id)
      AND (j.tipo_dpto_id = k.tipo_dpto_id)
      JOIN tipo_pais as l ON (k.tipo_pais_id = l.tipo_pais_id)

      JOIN documentos as i ON (a.empresa_id = i.empresa_id)
      AND (a.documento_id = i.documento_id)

      JOIN (
      SELECT
      a.empresa_id,
      a.prefijo,
      a.factura_fiscal,
      SUM((a.valor_unitario*a.cantidad)) as subtotal,
      SUM(((a.valor_unitario*a.cantidad)*(a.porc_iva/100))) as iva_total
      FROM
      inv_facturas_despacho_d as a
      group by a.empresa_id,a.prefijo,a.factura_fiscal
      )as m ON (m.empresa_id= a.empresa_id)
      AND (m.prefijo = a.prefijo)
      AND (m.factura_fiscal = a.factura_fiscal)

      WHERE
      a.empresa_id = '".$empresa_id."'
      AND a.tercero_id ILIKE '%".$filtros['tercero_id']."%'
      AND c.nombre_tercero ILIKE '%".$filtros['nombre_tercero']."%' ";
      $sql .= $where;

      $cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
      $this->ProcesarSqlConteo($cont,$offset);
      $sql .= "ORDER BY a.fecha_registro DESC ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;

      if(!$rst = $this->ConexionBaseDatos($sql))
      return false;
      $datos = array();
      while(!$rst->EOF)
      {
      $datos[] = $rst->GetRowAssoc($ToUpper);
      $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
      } */

    function Listado_FacturasCliente($empresa_id, $filtros, $offset) {

        $where = " ";
        $where_aux = " ";

        if ($filtros['prefijo'] != "") {
            $where .= "AND a.prefijo = '{$filtros['prefijo']}' ";
            $where_aux .= "AND a.prefijo = '{$filtros['prefijo']}' ";
        }
        if ($filtros['numero'] != "") {
            $where .= "AND a.factura_fiscal = '{$filtros['numero']}' ";
            $where_aux .= "AND a.factura_fiscal = '{$filtros['numero']}' ";
        }
        if ($filtros['tipo_id_tercero'] != "") {
            $where .= "AND a.tipo_id_tercero = '{$filtros['tipo_id_tercero']}' ";
            $where_aux .= "AND a.tipo_id_tercero = '{$filtros['tipo_id_tercero']}' ";
        }
        if ($filtros['pedido_cliente_id'] != "")
            $where .= "AND a.pedido_cliente_id = '{$filtros['pedido_cliente_id']}' ";

        //$this->debug=true;        

        $sql = "
            SELECT 
            a.*, 
            b.estado,
            case when b.estado=0 then 'Sincronizado' else 'NO sincronizado' end as descripcion_estado,
            b.mensaje 
            FROM (
                SELECT
                '0' as factura_agrupada,
                a.empresa_id,
                a.factura_fiscal,
                a.prefijo,
                a.documento_id,
                i.descripcion,
                i.texto1,
                i.texto2,
                i.texto3,
                i.mensaje,
                a.tipo_id_tercero,
                a.tercero_id,
                c.nombre_tercero,
                c.direccion,
                c.telefono,
                h.pais||'-'||g.departamento ||'-'||f.municipio as ubicacion,
                a.fecha_registro,
                a.usuario_id,
                a.tipo_id_vendedor,
                a.vendedor_id,
                b.nombre,
                a.valor_total,
                a.saldo,
                a.pedido_cliente_id,
                a.observaciones,
                a.fecha_vencimiento_factura,
                a.porcentaje_rtf,
                a.porcentaje_ica,
                a.porcentaje_reteiva,
                e.razon_social,
                e.tipo_id_tercero as tipo_id_empresa,
                e.id,
                e.direccion as direccion_empresa,
                e.telefonos as telefono_empresa,
                e.digito_verificacion,
                l.pais as pais_empresa,
                k.departamento as departamento_empresa,
                j.municipio as municipio_empresa,
                TO_CHAR(a.fecha_registro,'YYYY') as anio_factura,
                m.subtotal,
                m.iva_total,
                pedi.observacion	
                FROM inv_facturas_despacho as a
                JOIN vnts_vendedores as b ON (a.tipo_id_vendedor = b.tipo_id_vendedor) 	AND (a.vendedor_id = b.vendedor_id)
                JOIN terceros as c ON (a.tipo_id_tercero = c.tipo_id_tercero) AND (a.tercero_id = c.tercero_id)
                JOIN system_usuarios as d ON (a.usuario_id = d.usuario_id) 
                JOIN empresas as e ON (a.empresa_id = e.empresa_id)
                JOIN tipo_mpios as f ON (c.tipo_pais_id = f.tipo_pais_id) AND (c.tipo_dpto_id = f.tipo_dpto_id) AND (c.tipo_mpio_id = f.tipo_mpio_id) 
                JOIN tipo_dptos as g ON (f.tipo_pais_id = g.tipo_pais_id) AND (f.tipo_dpto_id = g.tipo_dpto_id)
                JOIN tipo_pais as h ON (g.tipo_pais_id = h.tipo_pais_id)	
                JOIN tipo_mpios as j ON (e.tipo_pais_id = j.tipo_pais_id) AND (e.tipo_dpto_id = j.tipo_dpto_id) AND (e.tipo_mpio_id = j.tipo_mpio_id)
                JOIN tipo_dptos as k ON (j.tipo_pais_id = k.tipo_pais_id) AND (j.tipo_dpto_id = k.tipo_dpto_id)
                JOIN tipo_pais as l ON (k.tipo_pais_id = l.tipo_pais_id)	
                JOIN ventas_ordenes_pedidos pedi ON (pedi.empresa_id = a.empresa_id) and (pedi.pedido_cliente_id=a.pedido_cliente_id) and (pedi.tercero_id = a.tercero_id) and (pedi.tipo_id_tercero=a.tipo_id_tercero)	
                JOIN documentos as i ON (a.empresa_id = i.empresa_id) AND (a.documento_id = i.documento_id)
                JOIN (
                        SELECT
                        a.empresa_id,
                        a.prefijo,
                        a.factura_fiscal,
                        SUM((a.valor_unitario*a.cantidad)) as subtotal,
                        SUM(((a.valor_unitario*a.cantidad)*(a.porc_iva/100))) as iva_total
                        FROM
                        inv_facturas_despacho_d as a
                        group by a.empresa_id,a.prefijo,a.factura_fiscal
                )as m ON (m.empresa_id= a.empresa_id) AND (m.prefijo = a.prefijo) AND (m.factura_fiscal = a.factura_fiscal)
                WHERE a.empresa_id = '{$empresa_id}' AND a.tercero_id ILIKE '%{$filtros['tercero_id']}%' AND c.nombre_tercero ILIKE '%{$filtros['nombre_tercero']}%' 
                {$where}

                UNION 

                select 
                '1' as factura_agrupada,
                a.empresa_id,
                a.factura_fiscal,
                a.prefijo,
                a.documento_id,
                i.descripcion,
                i.texto1,
                i.texto2,
                i.texto3,
                i.mensaje,
                a.tipo_id_tercero,
                a.tercero_id,
                c.nombre_tercero,
                c.direccion,
                c.telefono,
                h.pais||'-'||g.departamento ||'-'||f.municipio as ubicacion,
                a.fecha_registro,
                a.usuario_id,
                (
                	select bb.tipo_id_vendedor from inv_facturas_agrupadas_despacho_d bb 
                    where  bb.empresa_id = a.empresa_id and bb.prefijo = a.prefijo and bb.factura_fiscal = a.factura_fiscal
                	limit 1
                ) as tipo_id_vendedor,
                (
                	select cc.vendedor_id from inv_facturas_agrupadas_despacho_d cc 
                    where  cc.empresa_id = a.empresa_id and cc.prefijo = a.prefijo and cc.factura_fiscal = a.factura_fiscal
                	limit 1
                ) as vendedor_id,
                (
                	select ee.nombre from inv_facturas_agrupadas_despacho_d dd
                    inner join vnts_vendedores ee on dd.tipo_id_vendedor = ee.tipo_id_vendedor and dd.vendedor_id = ee.vendedor_id
                    where dd.empresa_id = a.empresa_id and dd.prefijo = a.prefijo and dd.factura_fiscal = a.factura_fiscal
                    limit 1 
                ) as nombre,
                a.valor_total,
                a.saldo,
                0 as pedido_cliente_id,
                a.observaciones,
                a.fecha_vencimiento_factura,
                a.porcentaje_rtf,
                a.porcentaje_ica,
                a.porcentaje_reteiva,
                e.razon_social,
                e.tipo_id_tercero as tipo_id_empresa,
                e.id,
                e.direccion as direccion_empresa,
                e.telefonos as telefono_empresa,
                e.digito_verificacion,
                l.pais as pais_empresa,
                k.departamento as departamento_empresa,
                j.municipio as municipio_empresa,
                TO_CHAR(a.fecha_registro,'YYYY') as anio_factura,
                m.subtotal,
                m.iva_total,
                '' as observacion
                from  inv_facturas_agrupadas_despacho a     
                inner join terceros as c ON a.tipo_id_tercero = c.tipo_id_tercero AND a.tercero_id = c.tercero_id
                inner join empresas as e ON a.empresa_id = e.empresa_id
                inner join tipo_mpios as f ON c.tipo_pais_id = f.tipo_pais_id AND c.tipo_dpto_id = f.tipo_dpto_id AND c.tipo_mpio_id = f.tipo_mpio_id 
                inner join tipo_dptos as g ON f.tipo_pais_id = g.tipo_pais_id AND f.tipo_dpto_id = g.tipo_dpto_id
                inner join tipo_pais as h ON g.tipo_pais_id = h.tipo_pais_id	
                inner join documentos as i ON a.empresa_id = i.empresa_id AND a.documento_id = i.documento_id
                inner join tipo_mpios as j ON e.tipo_pais_id = j.tipo_pais_id AND e.tipo_dpto_id = j.tipo_dpto_id AND e.tipo_mpio_id = j.tipo_mpio_id
                inner join tipo_dptos as k ON j.tipo_pais_id = k.tipo_pais_id AND j.tipo_dpto_id = k.tipo_dpto_id
                inner join tipo_pais as l ON k.tipo_pais_id = l.tipo_pais_id
                inner join (
                    SELECT
                    a.empresa_id,
                    a.prefijo,
                    a.factura_fiscal,
                    SUM((a.valor_unitario*a.cantidad)) as subtotal,
                    SUM(((a.valor_unitario*a.cantidad)*(a.porc_iva/100))) as iva_total
                    FROM
                    inv_facturas_agrupadas_despacho_d as a
                    group by a.empresa_id,a.prefijo,a.factura_fiscal
                )as m ON m.empresa_id= a.empresa_id AND m.prefijo = a.prefijo AND m.factura_fiscal = a.factura_fiscal                
                WHERE a.empresa_id = '{$empresa_id}' AND a.tercero_id ILIKE '%{$filtros['tercero_id']}%' AND c.nombre_tercero ILIKE '%{$filtros['nombre_tercero']}%' 
                {$where_aux}
            ) AS a --facturas_cliente    
            LEFT JOIN logs_facturacion_clientes_ws_fi b on a.prefijo = b.prefijo and  a.factura_fiscal = b.factura_fiscal and b.prefijo_nota IS NULL 
            ";

        $cont = "SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, $offset);
        $sql .= "ORDER BY fecha_registro DESC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;

        /* echo "<pre>";
          var_dump($sql);
          echo "</pre>";
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

    /**
     * Funcion donde se verifica el permiso del usuario para el ingreso al modulo
     * @return array $datos vector que contiene la informacion de la consulta del codigo de
     * la empresa y la razon social
     */
    function Prefijos_Facturas($empresa_id) {
        //   $this->debug=true;
        $sql = "SELECT    * ";
        $sql .= "FROM      documentos ";
        $sql .= "WHERE 
				 tipo_doc_general_id ='FV01'
				 AND empresa_id= '" . $empresa_id . "'; ";

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

    /**
     * Funcion donde se verifica el permiso del usuario para el ingreso al modulo
     * @return array $datos vector que contiene la informacion de la consulta del codigo de
     * la empresa y la razon social
     */
    function Detalle_Factura($empresa_id, $prefijo, $factura_fiscal) {
        $sql = " 
            SELECT
            a.empresa_id,
            a.prefijo,
            a.factura_fiscal,
            a.codigo_producto,
            (SELECT codigo_cum FROM inventarios_productos WHERE codigo_producto = a.codigo_producto) AS codigo_cum,
            (SELECT codigo_invima FROM inventarios_productos WHERE codigo_producto = a.codigo_producto) AS codigo_invima,
            fc_descripcion_producto(a.codigo_producto) as descripcion,
            (a.cantidad) as cantidad,
            a.fecha_vencimiento,
            a.lote,
            (f.costo * a.cantidad ) as costo,
            a.valor_unitario,
            a.porc_iva,
            (a.valor_unitario * a.cantidad) as subtotal,
            (a.valor_unitario*(a.porc_iva/100)) as iva,
            ((a.valor_unitario * (a.porc_iva/100))* a.cantidad) as iva_total,
            --((a.valor_unitario+(a.valor_unitario*(a.porc_iva/100))) - a.valor_unitario) * a.cantidad as iva_total,
            (a.valor_unitario+(a.valor_unitario*(a.porc_iva/100))) as valor_unitario_iva,
            (((a.cantidad))*(a.valor_unitario+(a.valor_unitario*(a.porc_iva/100)))) as total,
            c.observacion,
            e.sw_medicamento,
            e.sw_insumos,
            1
            FROM inv_facturas_despacho as b
            JOIN inv_facturas_despacho_d as a ON b.factura_fiscal = a.factura_fiscal AND b.prefijo = a.prefijo AND b.empresa_id = a.empresa_id
            JOIN ventas_ordenes_pedidos c ON (c.pedido_cliente_id = b.pedido_cliente_id) AND (c.empresa_id = b.empresa_id)				
            inner join inventarios_productos d on a.codigo_producto = d.codigo_producto 
            inner join inv_grupos_inventarios e on d.grupo_id = e.grupo_id
            inner join inventarios f on d.codigo_producto = f.codigo_producto and a.empresa_id = f.empresa_id
            WHERE a.empresa_id ='{$empresa_id}' AND a.prefijo ='{$prefijo}' AND a.factura_fiscal ='{$factura_fiscal}'

            union all

            SELECT
            a.empresa_id,
            a.prefijo,
            a.factura_fiscal,
            a.codigo_producto,
            (SELECT codigo_cum FROM inventarios_productos WHERE codigo_producto = a.codigo_producto) AS codigo_cum,
            (SELECT codigo_invima FROM inventarios_productos WHERE codigo_producto = a.codigo_producto) AS codigo_invima,
            fc_descripcion_producto(a.codigo_producto) as descripcion,
            (a.cantidad) as cantidad,
            a.fecha_vencimiento,
            a.lote,
            (f.costo * a.cantidad ) as costo,
            a.valor_unitario,
            a.porc_iva,            
            (a.valor_unitario * a.cantidad) as subtotal,
            (a.valor_unitario*(a.porc_iva/100)) as iva,
            ((a.valor_unitario * (a.porc_iva/100))* a.cantidad) as iva_total,
            --((a.valor_unitario+(a.valor_unitario*(a.porc_iva/100))) - a.valor_unitario) * a.cantidad as iva_total,
            (a.valor_unitario+(a.valor_unitario*(a.porc_iva/100))) as valor_unitario_iva,
            (((a.cantidad))*(a.valor_unitario+(a.valor_unitario*(a.porc_iva/100)))) as total,
            c.observacion,
            e.sw_medicamento,
            e.sw_insumos,
            2
            FROM inv_facturas_agrupadas_despacho as b
            JOIN inv_facturas_agrupadas_despacho_d as a ON b.factura_fiscal = a.factura_fiscal AND b.prefijo = a.prefijo AND b.empresa_id = a.empresa_id
            JOIN ventas_ordenes_pedidos c ON (a.pedido_cliente_id = c.pedido_cliente_id) AND (a.empresa_id = c.empresa_id)	
            inner join inventarios_productos d on a.codigo_producto = d.codigo_producto 
            inner join inv_grupos_inventarios e on d.grupo_id = e.grupo_id
            inner join inventarios f on d.codigo_producto = f.codigo_producto and a.empresa_id = f.empresa_id
            WHERE a.empresa_id ='{$empresa_id}' AND a.prefijo ='{$prefijo}' AND a.factura_fiscal ='{$factura_fiscal}'
           ";

         /*echo "<pre>";   
          print_r($sql);
          echo "</pre>";
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

    function validar_factura_agrupada($empresa_id, $prefijo, $factura_fiscal) {

        $sql = "SELECT * FROM inv_facturas_agrupadas_despacho WHERE empresa_id='{$empresa_id}' AND prefijo='{$prefijo}' AND factura_fiscal={$factura_fiscal}";

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

    function insertar_factura_agrupada($documento_facturacion, $datos_cliente, $parametros_retencion,$forma_pago) {
        $porcentaje_rtf = '0';
        $porcentaje_ica = '0';
        $porcentaje_reteiva = '0';
        $porcentaje_cree = $datos_cliente['porcentaje_cree'];

        if ($parametros_retencion['sw_rtf'] == '1' || $parametros_retencion['sw_rtf'] == '3')
            $porcentaje_rtf = $datos_cliente['porcentaje_rtf'];
        if ($parametros_retencion['sw_ica'] == '1' || $parametros_retencion['sw_ica'] == '3')
            $porcentaje_ica = $datos_cliente['porcentaje_ica'];
        if ($parametros_retencion['sw_reteiva'] == '1' || $parametros_retencion['sw_reteiva'] == '3')
            $porcentaje_reteiva = $datos_cliente['porcentaje_reteiva'];

        $usuario_id = UserGetUID();
        $sql = "INSERT INTO inv_facturas_agrupadas_despacho (  
                empresa_id, 
                tipo_id_tercero,
                tercero_id,
                factura_fiscal, 
                prefijo, 
                documento_id,                  
                usuario_id,                 
                observaciones, 
                porcentaje_rtf, 
                porcentaje_ica, 
                porcentaje_reteiva,
                porcentaje_cree,
                tipo_pago_id)
                VALUES( 
                '{$documento_facturacion['empresa_id']}',
                '{$datos_cliente['tipo_id_tercero']}',    
                '{$datos_cliente['tercero_id']}',    
                '{$documento_facturacion['numeracion']}', 
                '{$documento_facturacion['prefijo']}',    
                '{$documento_facturacion['documento_id']}',                 
                '{$usuario_id}',                 
                '{$datos_cliente['condiciones_cliente']}', 
                {$porcentaje_rtf}, 
                {$porcentaje_ica}, 
                {$porcentaje_reteiva},
                {$porcentaje_cree},
                {$forma_pago}
                ); ";

        return $sql;
    }

    function insertar_detalle_factura_agrupada($documento_facturacion, $datos_documento_despacho, $detalle_documento_despacho) {


        $sql = "INSERT INTO inv_facturas_agrupadas_despacho_d(
                item_id,                
                tipo_id_vendedor,  
                vendedor_id,
                pedido_cliente_id,
                empresa_id, 
                factura_fiscal, 
                prefijo, 
                codigo_producto, 
                cantidad, 
                valor_unitario, 
                lote, 
                fecha_vencimiento, 
                porc_iva ) 
                VALUES( 
                DEFAULT,                
                '{$datos_documento_despacho['tipo_id_vendedor']}',
                '{$datos_documento_despacho['vendedor_id']}',
                {$datos_documento_despacho['pedido_cliente_id']},
                '{$documento_facturacion['empresa_id']}', 
                '{$documento_facturacion['numeracion']}', 
                '{$documento_facturacion['prefijo']}', 
                '{$detalle_documento_despacho['codigo_producto']}', 
                {$detalle_documento_despacho['cantidad']}, 
                {$detalle_documento_despacho['valor_unitario']}, 
                '{$detalle_documento_despacho['lote']}', 
                '{$detalle_documento_despacho['fecha_vencimiento']}', 
                {$detalle_documento_despacho['porcentaje_gravamen']} ); ";

        return $sql;
    }

    function obtener_pedidos_agrupados($empresa_id, $prefijo, $numero) {

        $sql = "
            select a.pedido_cliente_id 
            from inv_facturas_agrupadas_despacho_d a 
            where a.empresa_id='{$empresa_id}' and a.prefijo='{$prefijo}' and a.factura_fiscal={$numero};";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos [] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function obtener_factura_despacho($empresa_id, $prefijo, $numero) {

        $sql = "
                select to_char(a.fecha_registro,'YYYY') as anio_factura, a.empresa_id, a.prefijo, a.factura_fiscal, a.documento_id, 
                a.tipo_id_tercero, a.tercero_id, a.observaciones, a.porcentaje_rtf, 
                a.porcentaje_ica, a.porcentaje_reteiva, cast(a.valor_total as double precision ) as valor_total, a.fecha_registro, to_char(a.fecha_registro, 'dd/mm/yyyy') as fecha_factura,
                a.porcentaje_cree
                from inv_facturas_despacho a 
                where a.empresa_id='{$empresa_id}' and a.prefijo='{$prefijo}' and a.factura_fiscal='{$numero}'
                union all
                select to_char(a.fecha_registro,'YYYY') as anio_factura, a.empresa_id, a.prefijo, a.factura_fiscal, a.documento_id,
                a.tipo_id_tercero, a.tercero_id, a.observaciones, a.porcentaje_rtf,
                a.porcentaje_ica, a.porcentaje_reteiva, cast(a.valor_total as double precision ) as valor_total, a.fecha_registro, to_char(a.fecha_registro, 'dd/mm/yyyy') as fecha_factura,
                a.porcentaje_cree
                from inv_facturas_agrupadas_despacho a 
                where a.empresa_id='{$empresa_id}' and a.prefijo='{$prefijo}' and a.factura_fiscal='{$numero}' ";

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

    function obtener_prefijo_fi($empresa_id, $tipo_documento) {

        $sql = "
                select COALESCE(b.prefijo ,'') as prefijo_fi
                from documentos a 
                inner join prefijos_financiero b on a.prefijos_financiero_id = b.id
                where a.prefijo='{$tipo_documento}' and a.empresa_id='{$empresa_id}' ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        while (!$resultado->EOF) {
            $datos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $datos;
    }

    function registrar_resultado_sincronizacion($prefijo, $numero_factura, $mensaje, $estado) {


        $sql = " select * from logs_facturacion_clientes_ws_fi where prefijo = '{$prefijo}' and  factura_fiscal = {$numero_factura} AND prefijo_nota IS NULL ;";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $datos = Array();
        while (!$resultado->EOF) {
            $datos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();


        $sql = " update logs_facturacion_clientes_ws_fi set mensaje='{$mensaje}', estado='{$estado}' where prefijo = '{$prefijo}' and  factura_fiscal = {$numero_factura}  AND prefijo_nota IS NULL ;";

        if (count($datos) == 0) {
            $sql = " INSERT INTO logs_facturacion_clientes_ws_fi (prefijo, factura_fiscal, mensaje, estado ) VALUES ('{$prefijo}', {$numero_factura}, '{$mensaje}', '{$estado}'); ";
        }


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    function registrar_resultado_sincronizacion_notas($prefijo, $numero_factura, $prefijo_nota, $nota_id, $mensaje, $estado) {


        $sql = " select * from logs_facturacion_clientes_ws_fi where prefijo = '{$prefijo}' and  factura_fiscal = {$numero_factura} AND prefijo_nota = '{$prefijo_nota}' AND numero_nota = '{$nota_id}' ;";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $datos = Array();
        while (!$resultado->EOF) {
            $datos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();


        $sql = " update logs_facturacion_clientes_ws_fi set mensaje='{$mensaje}', estado='{$estado}' where prefijo = '{$prefijo}' and  factura_fiscal = {$numero_factura} AND prefijo_nota = '{$prefijo_nota}' AND numero_nota = '{$nota_id}' ;";

        if (count($datos) == 0) {
            $sql = " INSERT INTO logs_facturacion_clientes_ws_fi (prefijo, factura_fiscal, mensaje, estado, prefijo_nota, numero_nota ) VALUES ('{$prefijo}', {$numero_factura}, '{$mensaje}', '{$estado}', '{$prefijo_nota}', '{$nota_id}'); ";
        }

        //echo $sql;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

}

?>