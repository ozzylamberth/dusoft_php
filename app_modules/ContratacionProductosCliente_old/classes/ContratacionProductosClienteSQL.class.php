<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: ContratacionProductosClienteSQL.class.php,
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */

/**
 * Clase : ContratacionProductosSQL
 *
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 1.12 $
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */
class ContratacionProductosClienteSQL extends ConexionBD {
    /*
     * Constructor de la clase
     */

    function ContratacionProductosClienteSQL() {

    }

    /**
     * Funcion donde se obtiene el listado de productos sin movimiento
     *
     * @param string $empresa Identificador de la empresa
     * @param array $filtros Arreglo con los filtros para la busqueda de la nota
     *
     * @return mixed
     */
    function Listado_Contratos($empresa, $filtros, $offset) {
        /* $this->debug=true; */
        //print_r($filtros);

        $sql = "
				SELECT
				a.contrato_cliente_id,
				a.empresa_id,
				a.descripcion as descripcion_contrato,
				TO_CHAR(a.fecha_inicio,'DD-MM-YYYY') as fecha_inicio,
				TO_CHAR(a.fecha_final,'DD-MM-YYYY') as fecha_final,
				a.tipo_id_tercero,
				a.tercero_id,
				c.nombre_tercero,
				a.codigo_unidad_negocio,
				d.descripcion,
				a.contrato_generico,
				a.condiciones_cliente,
				a.observaciones,
				a.tipo_id_vendedor,
				a.vendedor_id,
				e.nombre,
				a.valor_contrato,
				a.saldo,
				a.estado,
				CASE 
				WHEN a.contrato_generico='1'
				THEN 'CONTRATO GENERICO'
				WHEN a.tipo_id_tercero IS NOT NULL
				THEN a.tipo_id_tercero||'-'||a.tercero_id||' ' ||c.nombre_tercero
				WHEN a.codigo_unidad_negocio IS NOT NULL
				THEN d.codigo_unidad_negocio||' - '||d.descripcion
				END as contrato,
				CASE 
				WHEN a.contrato_generico='1'
				THEN '3'
				WHEN a.tipo_id_tercero IS NOT NULL
				THEN '1'
				WHEN a.codigo_unidad_negocio IS NOT NULL
				THEN '2'
				END as tipo_contrato
				from
				vnts_contratos_clientes as a
				LEFT JOIN terceros_clientes as b ON(a.tipo_id_tercero = b.tipo_id_tercero)
				AND(a.tercero_id = b.tercero_id)
				AND(a.empresa_id = b.empresa_id)
				LEFT JOIN terceros as c ON (b.tipo_id_tercero = c.tipo_id_tercero)
				AND (b.tercero_id = c.tercero_id) AND (b.empresa_id = c.empresa_id) 
				LEFT JOIN unidades_negocio as d ON (a.codigo_unidad_negocio = d.codigo_unidad_negocio)
				JOIN vnts_vendedores as e ON (a.tipo_id_vendedor = e.tipo_id_vendedor)
				AND (a.vendedor_id = e.vendedor_id)";
        $sql .= "WHERE TRUE	";
        if (!empty($filtros['nombre_tercero']))
            $sql .= "AND c.nombre_tercero ILIKE '%" . $filtros['nombre_tercero'] . "%'	";
        if (!empty($filtros['descripcion']))
            $sql .= "AND d.descripcion ILIKE '%" . $filtros['descripcion'] . "%'	";
        if (!empty($filtros['contrato_cliente_id']))
            $sql .= "AND a.contrato_cliente_id = '" . $filtros['contrato_cliente_id'] . "'	";
        if (!empty($filtros['contrato_generico']))
            $sql .= "AND a.contrato_generico = '" . $filtros['contrato_generico'] . "'	";

        $cont = "SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, $offset);
        $sql .= "ORDER BY a.contrato_cliente_id  ASC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;

        // print_r($filtros);
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

    /*     * **********************************************************************************
     *
     * Funcion que consulta paises.
     *
     * *********************************************************************************** */

    function UnidadesNegocio($datos) {
        /* $this->debug=true; */
        if ($datos['codigo_unidad_negocio'] != "") {
            $filtro .= "  AND	a.codigo_unidad_negocio = '" . $datos['codigo_unidad_negocio'] . "' ";
        }

        $sql = "
			SELECT
			a.codigo_unidad_negocio,
			a.descripcion,
			a.empresa_id
			from
			unidades_negocio AS a
			        where
			a.estado = '1'	
			";
        $sql .= $filtro;

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {

            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $documentos;
    }

    /*     * **********************************************************************************
     *
     * Funcion que consulta paises.
     *
     * *********************************************************************************** */

    function Vendedores() {
        $sql = "
			Select
			a.tipo_id_vendedor,
			a.vendedor_id,
			a.nombre,
			a.telefono
			from vnts_vendedores as a
			WHERE
			a.estado = '1'
			";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {

            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $documentos;
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
        /* $this->debug=true; */
        if ($Formulario['tipo_id_tercero'] != "")
            $filtro .= " AND a.tipo_id_tercero = '" . $Formulario['tipo_id_tercero'] . "' ";

        $sql = "
		SELECT
		a.tipo_id_tercero,
		a.tercero_id,
		a.direccion,
		a.telefono,
		a.email,
		a.nombre_tercero,
		a.tipo_bloqueo_id,
		c.descripcion as bloqueo,
		COALESCE(d.tipo_id_tercero,'0') as contrato_cliente_id,
		g.pais,
		f.departamento,
		municipio
		FROM
		terceros as a
		JOIN terceros_clientes as b ON (a.tipo_id_tercero = b.tipo_id_tercero)
		AND (a.tercero_id = b.tercero_id)
		AND (b.empresa_id = '" . $Formulario['empresa_id'] . "')
		LEFT JOIN inv_tipos_bloqueos as c ON (a.tipo_bloqueo_id = c.tipo_bloqueo_id)
		LEFT JOIN vnts_contratos_clientes as d ON (a.tipo_id_tercero = d.tipo_id_tercero)
		AND (a.tercero_id = d.tercero_id)
		AND (d.empresa_id = '" . $Formulario['empresa_id'] . "')
		AND (d.estado = '1')
		LEFT JOIN tipo_mpios as e ON (a.tipo_pais_id = e.tipo_pais_id)
		AND (a.tipo_dpto_id = e.tipo_dpto_id)
		AND (a.tipo_mpio_id = e.tipo_mpio_id)
		LEFT JOIN tipo_dptos as f ON (e.tipo_pais_id = f.tipo_pais_id)
		AND (e.tipo_dpto_id = f.tipo_dpto_id)
		LEFT JOIN tipo_pais as g ON (f.tipo_pais_id = g.tipo_pais_id)
		WHERE
		a.nombre_tercero ILIKE '%" . $Formulario['nombre_tercero'] . "%'
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
		d.tipo_id_tercero,
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

    /*
     * Funcion donde se consulta  informacion completa del  Proveedor.
     * @param string $noId cadena con el valor del numero de identificacion
     * @param string $tipoId cadena con el valor del tipo de identificacion
     * @return array $datos vector con la informacion de los Proveedor
     */

    function ConsultarTercero($TerceroId, $TipoIdTercero) {
        // $this->debug=true;
        $sql = "SELECT   t.* ";
        $sql .= "FROM     terceros t ";
        $sql .= " WHERE   t.tercero_id = '" . $TerceroId . "' ";
        $sql .= "        and t.tipo_id_tercero = '" . $TipoIdTercero . "' ";

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
     * Funcion donde se consulta  informacion completa del  Proveedor.
     * @param string $noId cadena con el valor del numero de identificacion
     * @param string $tipoId cadena con el valor del tipo de identificacion
     * @return array $datos vector con la informacion de los Proveedor
     */

    function ConsultarTercero_Contrato($empresa_id, $tercero_id, $tipo_id_tercero) {
        /* $this->debug=true; */
        $sql = "SELECT
		a.tipo_id_tercero,
		a.tercero_id,
		e.nombre_tercero,
		e.direccion,
		e.telefono,
		a.observacion,
		a.tipo_cliente,
                a.cuenta_contable,
		CASE 
		WHEN a.sw_rtf = '1'
		THEN a.porcentaje_rtf
		ELSE 0
		END as porcentaje_rtf,
		CASE 
		WHEN a.sw_ica = '1'
		THEN a.porcentaje_ica
		ELSE 0
		END as porcentaje_ica,
		CASE 
		WHEN a.sw_reteiva = '1'
		THEN a.porcentaje_reteiva
		ELSE 0
		END as porcentaje_reteiva,
                CASE WHEN a.sw_cree = '1' THEN a.porcentaje_cree ELSE 0 END as porcentaje_cree,
		CASE 
		WHEN c.condiciones_cliente IS NOT NULL
		THEN c.condiciones_cliente
		WHEN d.condiciones_cliente IS NOT NULL
		THEN d.condiciones_cliente
		ELSE (SELECT
				condiciones_cliente
				FROM
				vnts_contratos_clientes
				WHERE
				estado = '1'
				and contrato_generico = '1'
		)
		END as condiciones_cliente,
		CASE 
		WHEN c.contrato_cliente_id IS NOT NULL
		THEN c.contrato_cliente_id
		WHEN d.contrato_cliente_id IS NOT NULL
		THEN d.contrato_cliente_id
		ELSE (SELECT
				contrato_cliente_id
				FROM
				vnts_contratos_clientes
				WHERE
				estado = '1'
				and contrato_generico = '1'
		)
		END as contrato,
		CASE 
		WHEN c.contrato_cliente_id IS NOT NULL
		THEN 'Contrato: <b>TERCERO - CLIENTE</b>'
		WHEN d.contrato_cliente_id IS NOT NULL
		THEN 'Contrato: UNIDAD DE NEGOCIO: <b>'||b.descripcion||'</b>'
		ELSE 'Contrato: <b>GENERICO</b>'
		END as tipo_contrato,
		CASE 
		WHEN c.vendedor_id IS NOT NULL
		THEN c.tipo_id_vendedor||'@'||c.vendedor_id
		WHEN d.vendedor_id IS NOT NULL
		THEN d.tipo_id_vendedor||'@'||d.vendedor_id
		END as vendedor_id,
                c.facturar_iva
		FROM
		terceros_clientes AS a
		LEFT JOIN unidades_negocio as b ON (a.codigo_unidad_negocio = b.codigo_unidad_negocio)
		LEFT JOIN vnts_contratos_clientes as c ON (a.tipo_id_tercero = c.tipo_id_tercero)
		AND (a.tercero_id = c.tercero_id)
		AND (a.empresa_id = '" . $empresa_id . "')
		AND (c.estado = '1')
		LEFT JOIN vnts_contratos_clientes as d ON (a.codigo_unidad_negocio = d.codigo_unidad_negocio)
		AND (d.empresa_id = '" . $empresa_id . "')
		AND (d.estado = '1')
		JOIN terceros as e ON (a.tipo_id_tercero = e.tipo_id_tercero)
		AND (a.empresa_id = '" . $empresa_id . "')
		AND (a.tercero_id = e.tercero_id)                
		WHERE
		a.tipo_id_tercero = '" . $tipo_id_tercero . "'
		AND a.tercero_id = '" . $tercero_id . "'
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

    /*
     * Funcion donde se consulta  consulta un contato vigente con un tercero
     * @return array $datos vector con la informacion del contrato
     */

    function ConsultarContratoVigente($datos, $empresa_id) {
        /* $this->debug=true; */
        if ($datos['contrato_cliente_id'] != "") {
            $filtro = " AND a.contrato_cliente_id = '" . $datos['contrato_cliente_id'] . "' ";
        } else
        if ($datos['tipo_id_tercero'] != "") {
            $filtro = " AND a.tipo_id_tercero = '" . $datos['tipo_id_tercero'] . "' ";
            $filtro .= " AND a.tercero_id = '" . $datos['tercero_id'] . "' ";
        } else
        if ($datos['codigo_unidad_negocio'] != "") {
            $filtro = " AND a.codigo_unidad_negocio = '" . $datos['codigo_unidad_negocio'] . "' ";
        } else {
            $filtro = " AND a.contrato_generico = '1' ";
        }



        $sql = "SELECT
				a.contrato_cliente_id,
				a.empresa_id,
				a.descripcion as descripcion_contrato,
				TO_CHAR(a.fecha_inicio,'DD-MM-YYYY') as fecha_inicio,
				TO_CHAR(a.fecha_final,'DD-MM-YYYY') as fecha_final,
				a.tipo_id_tercero,
				a.tercero_id,
				c.nombre_tercero,
				c.direccion,
				c.telefono,
				c.email,
				a.codigo_unidad_negocio,
				d.descripcion,
				a.contrato_generico,
				a.condiciones_cliente,
				a.observaciones,
				a.tipo_id_vendedor,
				a.vendedor_id,
				e.nombre,
				a.valor_contrato,
				a.saldo,
				a.estado,
				a.porcentaje_genericos,
				a.porcentaje_marcas,
				a.porcentajes_insumos,
				CASE 
				WHEN a.contrato_generico='1'
				THEN 'CONTRATO GENERICO'
				WHEN a.tipo_id_tercero IS NOT NULL
				THEN 'CLIENTE: '||a.tipo_id_tercero||'-'||a.tercero_id||' ' ||c.nombre_tercero
				WHEN a.codigo_unidad_negocio IS NOT NULL
				THEN 'UNIDAD DE NEGOCIO: '||d.codigo_unidad_negocio||' - '||d.descripcion
				END as contrato,
				CASE 
				WHEN a.contrato_generico='1'
				THEN '3'
				WHEN a.tipo_id_tercero IS NOT NULL
				THEN '1'
				WHEN a.codigo_unidad_negocio IS NOT NULL
				THEN '2'
				END as tipo_contrato,
				a.facturar_iva,
				a.sw_autorizacion,
				a.sw_facturacion_agrupada
				from
				vnts_contratos_clientes as a
				LEFT JOIN terceros_clientes as b ON(a.tipo_id_tercero = b.tipo_id_tercero)
				AND(a.tercero_id = b.tercero_id)
				AND(a.empresa_id = b.empresa_id)
				LEFT JOIN terceros as c ON (b.tipo_id_tercero = c.tipo_id_tercero)
				AND (b.tercero_id = c.tercero_id)
				LEFT JOIN unidades_negocio as d ON (a.codigo_unidad_negocio = d.codigo_unidad_negocio)
				JOIN vnts_vendedores as e ON (a.tipo_id_vendedor = e.tipo_id_vendedor)
				AND (a.vendedor_id = e.vendedor_id)";
        $sql .= " WHERE ";
        $sql .= " a.estado = '1' ";
        $sql .= " AND a.empresa_id = '" . $empresa_id . "' ";
        $sql .= $filtro;

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

    function IngresarDatosContrato($Formulario) {

        $indice = array();
        $datos = array();

        /* $this->debug=true; */

        $datos['fecha_inicio'] = $Formulario['fecha_inicio'];
        $fdatos = explode("-", $datos['fecha_inicio']);
        $fedatos = $fdatos[2] . "/" . $fdatos[1] . "/" . $fdatos[0];

        $dtos = array();
        $dtos['fecha_final'] = $Formulario['fecha_final'];
        $fdtos = explode("-", $dtos['fecha_final']);
        $fecdtos = $fdtos[2] . "/" . $fdtos[1] . "/" . $fdtos[0];

        /*
         * Para Sacar los datos del Vendedor
         */
        $vendedor = explode("@", $Formulario['vendedor_id']);

        if ($Formulario['tipo_contrato'] == '1') {
            $campos .= " tipo_id_tercero, ";
            $campos .= " tercero_id ";
            $valores.= " '" . $Formulario['tipo_id_tercero'] . "', ";
            $valores.= " '" . $Formulario['tercero_id'] . "' ";
            $where .= "	AND tipo_id_tercero = '" . $Formulario['tipo_id_tercero'] . "' ";
            $where .= "	AND tercero_id = '" . $Formulario['tercero_id'] . "' ";
        }

        if ($Formulario['tipo_contrato'] == '2') {
            $campos .= " codigo_unidad_negocio ";
            $valores.= " '" . $Formulario['codigo_unidad_negocio'] . "' ";
            $where .= "	AND codigo_unidad_negocio = '" . $Formulario['codigo_unidad_negocio'] . "' ";
        }

        if ($Formulario['tipo_contrato'] == '3') {
            $campos .= " contrato_generico ";
            $valores.= " '1' ";
            $where .= "	AND contrato_generico = '1' ";
        }

        if($Formulario['sincronizar']==""){
           $Formulario['sincronizar']='0';
        }

		//if(!isset($Formulario["sw_autorizacion"]) || $Formulario["sw_autorizacion"] == ""){
		//	$Formulario["sw_autorizacion"] = '0';
		//} else {
		//	$Formulario["sw_autorizacion"] = '1';
		//}
        $Formulario["sw_autorizacion"] = '1';

		if(!isset($Formulario["sw_facturacion_agrupada"]) || $Formulario["sw_facturacion_agrupada"] == ""){
			$Formulario["sw_facturacion_agrupada"] = '0';
		} else {
			$Formulario["sw_facturacion_agrupada"] = '1';
		}


        $sql .= "UPDATE vnts_contratos_clientes ";
        $sql .= "	set estado = '0' ";
        $sql .= "	WHERE ";
        $sql .= "	estado = '1' ";
        $sql .= " " . $where. " AND empresa_id='".$Formulario['empresa_id']."';";


        $sql .= "INSERT INTO vnts_contratos_clientes( ";
        $sql .= "       contrato_cliente_id, ";
        $sql .= "       empresa_id, ";
        $sql .= "       descripcion, ";
        $sql .= "       fecha_inicio, ";
        $sql .= "       fecha_final, ";
        $sql .= "       condiciones_cliente, ";
        $sql .= "       observaciones, ";
        $sql .= "       porcentaje_genericos, ";
        $sql .= "       porcentaje_marcas, ";
        $sql .= "       porcentajes_insumos, ";
        $sql .= "       valor_contrato, ";
        $sql .= "       saldo,";
        $sql .= "       usuario_id,";
        $sql .= "       tipo_id_vendedor,";
        $sql .= "       vendedor_id,";
        $sql .= "       facturar_iva,";
        $sql .= "       sw_sincroniza,";
		$sql .= "       sw_autorizacion,";
		$sql .= "       sw_facturacion_agrupada,";
        $sql .= "		" . $campos;
        $sql .= ")VALUES( ";
        $sql .= "       DEFAULT, ";
        $sql .= "		'" . $Formulario['empresa_id'] . "',";
        $sql .= "		'" . $Formulario['descripcion'] . "',";
        $sql .= "		'" . $fedatos . "',";
        $sql .= "		'" . $fecdtos . "',";
        $sql .= "		'" . $Formulario['condiciones_cliente'] . "',";
        $sql .= "		'" . $Formulario['observaciones'] . "',";
        $sql .= "		'" . $Formulario['porcentaje_genericos'] . "',";
        $sql .= "		'" . $Formulario['porcentaje_marcas'] . "',";
        $sql .= "		'" . $Formulario['porcentajes_insumos'] . "',";
        $sql .= "		'" . $Formulario['valor_contrato'] . "',";
        $sql .= "		'" . $Formulario['valor_contrato'] . "',";
        $sql .= "		" . UserGetUID() . ",";
        $sql .= "		'" . $vendedor[0] . "',";
        $sql .= "		'" . $vendedor[1] . "',";
        $sql .= "		'" . $Formulario['facturar_iva'] . "',";
        $sql .= "		'" . $Formulario['sincronizar'] . "',";
		$sql .= "		'" . $Formulario['sw_autorizacion'] . "',";
		$sql .= "		'" . $Formulario['sw_facturacion_agrupada'] . "',";
        $sql .= "       " . $valores;
        $sql .= "       );";



        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $rst->Close();
        return true;
    }

    function ModificarContratoVigente($Formulario) {

        $indice = array();
        $datos = array();

        //$this->debug=true;

        $datos['fecha_inicio'] = $Formulario['fecha_inicio'];
        $fdatos = explode("-", $datos['fecha_inicio']);
        $fi = $fdatos[2] . "/" . $fdatos[1] . "/" . $fdatos[0];

        $dtos = array();
        $dtos['fecha_final'] = $Formulario['fecha_final'];
        $fdtos = explode("-", $dtos['fecha_final']);
        $ff = $fdtos[2] . "/" . $fdtos[1] . "/" . $fdtos[0];

        /*
         * Para Sacar los datos del Vendedor
         */
        $vendedor = explode("@", $Formulario['vendedor_id']);

        if ($Formulario['tipo_contrato'] == '1') {
            $campos .= " tipo_id_tercero = '" . $Formulario['tipo_id_tercero'] . "', ";
            $campos .= " tercero_id = '" . $Formulario['tercero_id'] . "', ";
            $campos .= " codigo_unidad_negocio = NULL, ";
            $campos .= " contrato_generico = '0' ";
        }

        if ($Formulario['tipo_contrato'] == '2') {
            $campos .= " tipo_id_tercero = NULL, ";
            $campos .= " tercero_id = NULL, ";
            $campos .= " codigo_unidad_negocio = '" . $Formulario['codigo_unidad_negocio'] . "', ";
            $campos .= " contrato_generico = '0' ";
        }


        if ($Formulario['tipo_contrato'] == '3') {
            $campos .= " tipo_id_tercero = NULL, ";
            $campos .= " tercero_id = NULL, ";
            $campos .= " codigo_unidad_negocio = NULL, ";
            $campos .= " contrato_generico = '1' ";
        }

        if($Formulario['sincronizar']==""){
           $Formulario['sincronizar']='0';
        }

		if(!isset($Formulario["sw_autorizacion"]) || $Formulario["sw_autorizacion"] == ""){
			$Formulario["sw_autorizacion"] = '0';
		} else {
			$Formulario["sw_autorizacion"] = '1';
		}

		if(!isset($Formulario["sw_facturacion_agrupada"]) || $Formulario["sw_facturacion_agrupada"] == ""){
			$Formulario["sw_facturacion_agrupada"] = '0';
		} else {
			$Formulario["sw_facturacion_agrupada"] = '1';
		}

        /*
         * ESPACIO PARA CONSTRUIR LOS WHERE
         */
        if ($Formulario['tipo_id_tercero_old'] != "") {
            $where .= "	AND tipo_id_tercero = '" . $Formulario['tipo_id_tercero_old'] . "' ";
            $where .= "	AND tercero_id = '" . $Formulario['tercero_id_old'] . "'; ";
        } else
        if ($Formulario['codigo_unidad_negocio_old'] != "")
            $where .= "	AND codigo_unidad_negocio = '" . $Formulario['codigo_unidad_negocio_old'] . "'; ";
        else
            $where .= "	AND contrato_generico = '1'; ";

        $sql .= "UPDATE vnts_contratos_clientes ";
        $sql .= " SET ";
        $sql .= "       descripcion = '" . $Formulario['descripcion'] . "', ";
        $sql .= "       fecha_inicio = '" . $fi . "', ";
        $sql .= "       fecha_final = '" . $ff . "', ";
        $sql .= "       condiciones_cliente = '" . $Formulario['condiciones_cliente'] . "', ";
        $sql .= "       observaciones = '" . $Formulario['observaciones'] . "', ";
        $sql .= "       porcentaje_genericos = '" . $Formulario['porcentaje_genericos'] . "', ";
        $sql .= "       porcentaje_marcas = '" . $Formulario['porcentaje_marcas'] . "', ";
        $sql .= "       porcentajes_insumos = '" . $Formulario['porcentajes_insumos'] . "', ";
        $sql .= "       valor_contrato = '" . $Formulario['valor_contrato'] . "', ";
        $sql .= "       saldo = '" . $Formulario['valor_contrato'] . "',";
        $sql .= "       usuario_id = " . UserGetUID() . ",";
        $sql .= "       tipo_id_vendedor = '" . $vendedor[0] . "',";
        $sql .= "       vendedor_id = '" . $vendedor[1] . "',";
        $sql .= "       sw_sincroniza = '" . $Formulario['sincronizar'] . "',";
        $sql .= "       facturar_iva = '" . $Formulario['facturar_iva'] . "',";
		$sql .= "       sw_autorizacion = '" . $Formulario['sw_autorizacion'] . "',";
		$sql .= "       sw_facturacion_agrupada = '" . $Formulario['sw_facturacion_agrupada'] . "',";
        $sql .= "		" . $campos;
        $sql .= "WHERE ";
        $sql .= "		empresa_id = '" . $Formulario['empresa_id'] . "' ";
        $sql .= "		and estado = '1' ";
        $sql .= "		" . $where;



        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $rst->Close();
        return true;
    }

    function Inactivar_Contrato($contrato_cliente_id) {
        $this->ConexionTransaccion();

        $sql = " UPDATE vnts_contratos_clientes ";
        $sql .= " SET ";
        $sql .= " estado = '0' ";
        $sql .= " WHERE ";
        $sql .= " contrato_cliente_id = " . $contrato_cliente_id . "; ";
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    function ListaProductosInventario($filtros, $empresa_id, $contrato_cliente_id, $offset) {
        // $codigo_barras=eregi_replace("'","-",$CodigoBarras);

		//var_dump($empresa_id);
		//die();
        $sql = "
		SELECT
		a.codigo_producto,
		fc_descripcion_producto(b.codigo_producto) as descripcion,
		b.sw_requiereautorizacion_despachospedidos,
		(a.costo_ultima_compra)/((COALESCE(b.porc_iva,0)/100)+1) as costo_ultima_compra,
		a.costo
		FROM
		inventarios as a
		JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
		and (a.empresa_id = '" . $empresa_id . "')
		JOIN inv_subclases_inventarios as c ON (b.grupo_id = c.grupo_id)
		and(b.clase_id = c.clase_id)
		and(b.subclase_id = c.subclase_id)
		JOIN inv_clases_inventarios as d ON (c.grupo_id = d.grupo_id)
		and (c.clase_id = d.clase_id)
		WHERE
		--b.estado = '1'
		--and 
		b.codigo_producto ILIKE '%" . $filtros['codigo_producto'] . "%'
		and b.descripcion ILIKE '%" . $filtros['descripcion'] . "%'
		and c.descripcion ILIKE '%" . $filtros['laboratorio'] . "%'
		and d.descripcion ILIKE '%" . $filtros['principio_activo'] . "%'
		and b.codigo_producto NOT IN (Select
									codigo_producto
									from
									vnts_contratos_clientes_productos
									where
									contrato_cliente_id = " . $contrato_cliente_id . "
									)";

        $cont = "SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, $offset);
        $sql .= "ORDER BY b.descripcion  ASC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;


        //$this->debug=true;
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

    function ListaProductosContrato($contrato_cliente_id, $filtros, $offset) {

        /* $this->debug=true; */
        $sql = "SELECT
	a.codigo_producto,
	fc_descripcion_producto(b.codigo_producto) as descripcion,
	b.sw_requiereautorizacion_despachospedidos,
	a.precio_pactado
	FROM
	vnts_contratos_clientes_productos as a
	JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
	and (a.contrato_cliente_id = " . $contrato_cliente_id . ")
	JOIN inv_subclases_inventarios as c ON (b.grupo_id = c.grupo_id)
	and(b.clase_id = c.clase_id)
	and(b.subclase_id = c.subclase_id)
	JOIN inv_clases_inventarios as d ON (c.grupo_id = d.grupo_id)
	and (c.clase_id = d.clase_id)
	WHERE
	--b.estado = '1'
	--and 
	b.descripcion ILIKE '%" . $filtros['descripcion'] . "%'
	and c.descripcion ILIKE '%" . $filtros['laboratorio'] . "%'
	and d.descripcion ILIKE '%" . $filtros['principio_activo'] . "%'
	";

        $cont = "SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, $offset);
        $sql .= "ORDER BY b.descripcion  ASC ";
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

    function Lista_Productos($filtros, $empresa_id, $offset) {


        if ($filtros['codigo_producto'] != "")
            $filtro .= " AND b.codigo_producto =  '" . $filtros['codigo_producto'] . "'";

        $sql = "
		SELECT
		a.codigo_producto,
		fc_descripcion_producto(b.codigo_producto) as descripcion,
		b.sw_requiereautorizacion_despachospedidos,
		(a.costo_ultima_compra)/((COALESCE(b.porc_iva,0)/100)+1) as costo_ultima_compra,
		a.costo
		FROM
		inventarios as a
		JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
		and (a.empresa_id = '" . $empresa_id . "')
		JOIN inv_subclases_inventarios as c ON (b.grupo_id = c.grupo_id)
		and(b.clase_id = c.clase_id)
		and(b.subclase_id = c.subclase_id)
		JOIN inv_clases_inventarios as d ON (c.grupo_id = d.grupo_id)
		and (c.clase_id = d.clase_id)
		WHERE
		b.estado = '1'
		and b.descripcion ILIKE '%" . $filtros['descripcion'] . "%'
		and c.descripcion ILIKE '%" . $filtros['laboratorio'] . "%'
		and d.descripcion ILIKE '%" . $filtros['principio_activo'] . "%'";
        $sql .= $filtro;

        $cont = "SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, $offset);
        $sql .= "ORDER BY b.descripcion  ASC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;


        //$this->debug=true;
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

    function InsertarProducto_Contrato($contrato_cliente_id, $codigo_producto, $precio_pactado) {
        $this->ConexionTransaccion();

        /* $this->debug=true; */
        $sql = "INSERT INTO vnts_contratos_clientes_productos( ";
        $sql .= "       contrato_cliente_id , ";
        $sql .= "       codigo_producto, ";
        $sql .= "       precio_pactado, ";
        $sql .= "       usuario_id ";
        $sql .= ")VALUES( ";
        $sql .= "       " . $contrato_cliente_id . ", ";
        $sql .= "       '" . $codigo_producto . "', ";
        $sql .= "       '" . $precio_pactado . "', ";
        $sql .= "          " . UserGetUID() . " ";
        $sql .= "       ) ";

        if (!$rst = $this->ConexionTransaccion($sql)) {
            if (!$rst = $this->ConexionTransaccion($sqlerror))
                return false;
        }
        else {
            $this->Commit();
            return true;
        }
    }

    function IngresarPedidoTemporal($Datos, $empresa_id) {
        $this->ConexionTransaccion();
        $vendedor = explode("@", $Datos['vendedor_id']);
        //$this->debug=true;
        $usuario_id = UserGetUID();
        $sql = "INSERT INTO ventas_ordenes_pedidos_tmp(
                    pedido_cliente_id_tmp ,
                    empresa_id,
                    tercero_id,
                    tipo_id_tercero,
                    tipo_id_vendedor,
                    vendedor_id,
                    usuario_id, 
                    observaciones,
					centro_destino,
					bodega_destino)
                VALUES(
                    DEFAULT, 
                    '{$empresa_id}', 
                    '{$Datos['tercero_id']}', 
                    '{$Datos['tipo_id_tercero']}', 
                    '{$vendedor[0]}', 
                    '{$vendedor[1]}', 
                    {$usuario_id}, 
                    '{$Datos['observacion']}',
					'1',
					'03'
                 )RETURNING(pedido_cliente_id_tmp); ";

        /* echo "<pre>";
          var_dump($sql);
          echo "</pre>";
          exit(); */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $this->Commit();
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function actualizar_observaciones_cotizacion($cotizacion_id, $observaciones) {

        $this->ConexionTransaccion();

        $sql = "UPDATE ventas_ordenes_pedidos_tmp SET observaciones='{$observaciones}' WHERE pedido_cliente_id_tmp={$cotizacion_id};";

        /* echo "<pre>";
          print_r($sql);
          echo "</pre>";
          exit(); */
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    function Sql_IngresarProductoPedido($pedido_cliente_id, $codigo_producto, $porc_iva, $cantidad, $valor_unitario) {

        //$this->debug=true;
        $sql = "INSERT INTO ventas_ordenes_pedidos_d( ";
        $sql .= "       pedido_cliente_id , ";
        $sql .= "       codigo_producto, ";
        $sql .= "       usuario_id, ";
        $sql .= "       porc_iva, ";
        $sql .= "       numero_unidades, ";
        $sql .= "       valor_unitario ";
        $sql .= "        ";

        $sql .= ")VALUES( ";
        $sql .= "       '" . $pedido_cliente_id . "', ";
        $sql .= "       '" . $codigo_producto . "', ";
        $sql .= "          " . UserGetUID() . ", ";
        $sql .= "       '" . $porc_iva . "', ";
        $sql .= "       '" . $cantidad . "', ";
        $sql .= "       '" . $valor_unitario . "' ";
        $sql .= "       ); ";

        return $sql;
    }

    function IngresarPedidoDetalleTemporal($sql) {
        $this->ConexionTransaccion();
        //$this->debug=true;
        //print_r($sql);
        if (!$rst = $this->ConexionTransaccion($sql))
            return false;
        $this->Commit();
        return true;
    }

    function Consulta_PedidoTemporal($pedido_cliente_id_tmp) {
        $sql = "SELECT
	a.pedido_cliente_id_tmp,
	e.tipo_id_tercero as tipo_id_empresa,
	e.id,
	e.digito_verificacion,
	a.empresa_id,
	e.razon_social,
	a.tipo_id_tercero,
	a.tercero_id,
	b.nombre_tercero,
	b.direccion,
	b.telefono,
	a.fecha_registro,
	a.usuario_id,
	a.tipo_id_vendedor,
	a.vendedor_id,
	c.nombre,
	a.estado,
	a.fecha_envio,
	h.pais ||'-'||g.departamento||'-' || f.municipio as ubicacion,
	a.tipo_id_vendedor||'@'||a.vendedor_id as vendedor,
        a.observaciones
	FROM
	ventas_ordenes_pedidos_tmp as a
	JOIN terceros as b ON (a.tipo_id_tercero = b.tipo_id_tercero)
	AND (a.tercero_id = b.tercero_id)
	AND (a.estado = '1')
	JOIN vnts_vendedores as c ON (a.tipo_id_vendedor = c.tipo_id_vendedor)
	AND (a.vendedor_id = c.vendedor_id)
	JOIN system_usuarios as d ON (a.usuario_id = d.usuario_id)
	JOIN empresas as e ON (a.empresa_id = e.empresa_id)
	JOIN tipo_mpios as f ON (b.tipo_pais_id = f.tipo_pais_id)
	AND (b.tipo_dpto_id = f.tipo_dpto_id)
	AND (b.tipo_mpio_id = f.tipo_mpio_id)
	JOIN tipo_dptos as g ON (f.tipo_pais_id = g.tipo_pais_id)
	AND (f.tipo_dpto_id = g.tipo_dpto_id)
	JOIN tipo_pais as h ON (g.tipo_pais_id = h.tipo_pais_id)
	WHERE
	a.pedido_cliente_id_tmp = " . $pedido_cliente_id_tmp . ";";
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

    function Consulta_PedidoTemporal_d($pedido_cliente_id_tmp) {

        //$this->debug = true;
        $sql = "SELECT
		a.pedido_cliente_id_tmp,
		a.codigo_producto,
		fc_descripcion_producto(a.codigo_producto) as descripcion,
		a.porc_iva,
		a.numero_unidades,
		a.valor_unitario,	
		(a.valor_unitario+(a.valor_unitario*(a.porc_iva/100)))as valor_unitario_iva,
		(a.numero_unidades*(a.valor_unitario*(a.porc_iva/100))) as iva
		FROM
		ventas_ordenes_pedidos_d_tmp AS a
		WHERE
		pedido_cliente_id_tmp = " . $pedido_cliente_id_tmp . ";";

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

    function Listado_Cotizaciones($empresa_id, $filtros, $offset) {

        if ($filtros['pedido_cliente_id_tmp'] != "")
            $where .= " AND pedido_cliente_id_tmp = " . $filtros['pedido_cliente_id_tmp'] . " ";
        if ($filtros['nombre_tercero'] != "")
            $where .= " AND b.nombre_tercero ILIKE '%" . $filtros['nombre_tercero'] . "%' ";
        $sql = "SELECT
	a.pedido_cliente_id_tmp,
	e.tipo_id_tercero as tipo_id_empresa,
	e.id,
	e.digito_verificacion,
	a.empresa_id,
	e.razon_social,
	e.direccion as direccion_empresa,
	e.telefonos,
	a.tipo_id_tercero,
	a.tercero_id,
	b.nombre_tercero,
	b.direccion,
	b.telefono,
	a.fecha_registro,
	a.usuario_id,
	a.tipo_id_vendedor,
	a.vendedor_id,
	c.nombre,
	a.estado,
	a.fecha_envio,
	h.pais ||'-'||g.departamento||'-' || f.municipio as ubicacion,
	a.tipo_id_vendedor||'@'||a.vendedor_id as vendedor,
	k.pais ||'-'||j.departamento||'-' || i.municipio as ubicacion_empresa
	FROM
	ventas_ordenes_pedidos_tmp as a
	JOIN terceros as b ON (a.tipo_id_tercero = b.tipo_id_tercero)
	AND (a.tercero_id = b.tercero_id)
	AND (a.estado IN ('1','0'))
	JOIN vnts_vendedores as c ON (a.tipo_id_vendedor = c.tipo_id_vendedor)
	AND (a.vendedor_id = c.vendedor_id)
	JOIN system_usuarios as d ON (a.usuario_id = d.usuario_id)
	JOIN empresas as e ON (a.empresa_id = e.empresa_id)
	JOIN tipo_mpios as f ON (b.tipo_pais_id = f.tipo_pais_id)
	AND (b.tipo_dpto_id = f.tipo_dpto_id)
	AND (b.tipo_mpio_id = f.tipo_mpio_id)
	JOIN tipo_dptos as g ON (f.tipo_pais_id = g.tipo_pais_id)
	AND (f.tipo_dpto_id = g.tipo_dpto_id)
	JOIN tipo_pais as h ON (g.tipo_pais_id = h.tipo_pais_id)
	
	JOIN tipo_mpios as i ON (e.tipo_pais_id = i.tipo_pais_id)
	AND (e.tipo_dpto_id = i.tipo_dpto_id)
	AND (e.tipo_mpio_id = i.tipo_mpio_id)
	JOIN tipo_dptos as j ON (i.tipo_pais_id = j.tipo_pais_id)
	AND (i.tipo_dpto_id = j.tipo_dpto_id)
	JOIN tipo_pais as k ON (j.tipo_pais_id = k.tipo_pais_id)
	WHERE
	a.empresa_id = '" . $empresa_id . "'
	" . $where . "
	";
        /* $this->debug=true; */
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

    function Insertar_Producto_Contratos($codigo_producto, $precio_pactado) {

        $this->ConexionTransaccion();


        $sql = "UPDATE vnts_contratos_clientes_productos 
				SET precio_pactado= '" . $precio_pactado . "' 
				WHERE codigo_producto = '" . $codigo_producto . "';
			";
        $sql .= "  ";
        $sql .= "INSERT INTO vnts_contratos_clientes_productos ( ";
        $sql .= "       contrato_cliente_id , ";
        $sql .= "       codigo_producto, ";
        $sql .= "       precio_pactado, ";
        $sql .= "       usuario_id ";
        $sql .= ")(SELECT ";
        $sql .= "v.contrato_cliente_id, ";
        $sql .= "'" . $codigo_producto . "' as codigo_producto, ";
        $sql .= "'" . $precio_pactado . "' as precio_pactado, ";
        $sql .= " " . UserGetUID() . " as usuario_id ";
        $sql .= "FROM vnts_contratos_clientes v ";
        $sql .= "WHERE v.estado = '1' ";
        $sql .= "AND v.contrato_cliente_id NOT IN (";
        $sql .= "SELECT contrato_cliente_id ";
        $sql .= "FROM vnts_contratos_clientes_productos ";
        $sql .= "WHERE codigo_producto = '" . $codigo_producto . "')";
        $sql .= "); ";


        if (!$rst = $this->ConexionTransaccion($sql)) {
            if (!$rst = $this->ConexionTransaccion($sqlerror))
                return false;
        }
        else {
            $this->Commit();
            return true;
        }
    }

    function Eliminar_ItemContrato($contrato_cliente_id, $codigo_producto) {
        // $this->debug = true;

        $this->ConexionTransaccion();

        $sql = " DELETE     from vnts_contratos_clientes_productos ";
        $sql .= " Where ";
        $sql .= " contrato_cliente_id = " . $contrato_cliente_id . "   and codigo_producto='" . $codigo_producto . "'; ";
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    function BuscarProductos_Inventarios($Formulario, $offset) {
        $sql = "SELECT
	a.pedido_cliente_id_tmp,
	e.tipo_id_tercero as tipo_id_empresa,
	e.id,
	e.digito_verificacion,
	a.empresa_id,
	e.razon_social,
	a.tipo_id_tercero,
	a.tercero_id,
	b.nombre_tercero,
	b.direccion,
	b.telefono,
	a.fecha_registro,
	a.usuario_id,
	a.tipo_id_vendedor,
	a.vendedor_id,
	c.nombre,
	a.estado,
	a.fecha_envio,
	h.pais ||'-'||g.departamento||'-' || f.municipio as ubicacion,
	a.tipo_id_vendedor||'@'||a.vendedor_id as vendedor
	FROM
	ventas_ordenes_pedidos_tmp as a
	JOIN terceros as b ON (a.tipo_id_tercero = b.tipo_id_tercero)
	AND (a.tercero_id = b.tercero_id)
	AND (a.estado = '1')
	JOIN vnts_vendedores as c ON (a.tipo_id_vendedor = c.tipo_id_vendedor)
	AND (a.vendedor_id = c.vendedor_id)
	JOIN system_usuarios as d ON (a.usuario_id = d.usuario_id)
	JOIN empresas as e ON (a.empresa_id = e.empresa_id)
	JOIN tipo_mpios as f ON (b.tipo_pais_id = f.tipo_pais_id)
	AND (b.tipo_dpto_id = f.tipo_dpto_id)
	AND (b.tipo_mpio_id = f.tipo_mpio_id)
	JOIN tipo_dptos as g ON (f.tipo_pais_id = g.tipo_pais_id)
	AND (f.tipo_dpto_id = g.tipo_dpto_id)
	JOIN tipo_pais as h ON (g.tipo_pais_id = h.tipo_pais_id)
	WHERE
	a.pedido_cliente_id_tmp = " . $pedido_cliente_id_tmp . ";";
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

    /* Para Las Cotizaciones */

   function BuscarProductos_InventariosExistencias($Formulario, $offset) {
        /* $this->debug=true; */
       //echo print_r($Formulario);
        if ($Formulario['codigo_producto'] != "")
            $filtro = " AND b.codigo_producto = '" . $Formulario['codigo_producto'] . "' ";
       /* $sql = "
			SELECT
			a.codigo_producto,
                                                      a.precio_regulado,
			fc_descripcion_producto(a.codigo_producto) as descripcion,
			fc_precio_producto_contrato_cliente('" . $Formulario['contrato_cliente_id'] . "',a.codigo_producto,'" . $Formulario['empresa_id'] . "') as precio,
			(a.costo_ultima_compra)/((COALESCE(b.porc_iva,0)/100)+1) as costo_ultima_compra,
			SUM(a.existencia) as existencia,
			b.porc_iva,
			b.sw_requiereautorizacion_despachospedidos,
			SUM(p.cantidad_pendiente) as cantidad_pendiente_f,
			((SUM(a.existencia)-SUM(COALESCE(p.cantidad_pendiente,0)))-SUM(COALESCE(q.total_cantidad,0))) as total_disponible,
			CASE
				WHEN ((SUM(a.existencia)-SUM(COALESCE(p.cantidad_pendiente,0)))-SUM(COALESCE(q.total_cantidad,0))) > 0
				THEN ((SUM(a.existencia)-SUM(COALESCE(p.cantidad_pendiente,0)))-SUM(COALESCE(q.total_cantidad,0)))
				ELSE '0'
				END as disponible

			FROM
			inventarios as a
			JOIN inventarios_productos as b ON(a.empresa_id = '" . $Formulario['empresa_id'] . "')
			AND (a.codigo_producto = b.codigo_producto)
			LEFT JOIN
				(
				select
				a.codigo_producto,
				a.cantidad_pendiente
				from
					(
					SELECT
					sd.codigo_producto,
					sd.cantidad_solic as cantidad_pendiente
					from
					solicitud_productos_a_bodega_principal_detalle sd,
					solicitud_productos_a_bodega_principal s
					where
					sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id
					and   s.empresa_destino = '" . $Formulario['empresa_id'] . "'
					and   s.sw_despacho = '0'
					UNION
					SELECT
					sd.codigo_producto,
					ips.cantidad_pendiente
					from
					solicitud_productos_a_bodega_principal_detalle sd,
					solicitud_productos_a_bodega_principal s,
					inv_mov_pendientes_solicitudes_frm ips
					where
					sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id
					and   s.empresa_destino = '" . $Formulario['empresa_id'] . "'
					and   s.sw_despacho = '1'
					and   sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id
					and   sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id
					and   sd.solicitud_prod_a_bod_ppal_det_id = ips.solicitud_prod_a_bod_ppal_det_id
					)as a
				)as p ON (a.codigo_producto = p.codigo_producto)
			LEFT JOIN
				(
				SELECT
				b.codigo_producto,
				SUM((b.numero_unidades - b.cantidad_despachada)) as total_cantidad
				FROM
				ventas_ordenes_pedidos AS a
				JOIN ventas_ordenes_pedidos_d AS b ON (a.pedido_cliente_id = b.pedido_cliente_id)
				AND (a.estado = '1')
				AND (a.empresa_id = '" . $Formulario['empresa_id'] . "')
				AND (b.numero_unidades <> b.cantidad_despachada)
				GROUP BY b.codigo_producto
				)as q ON (a.codigo_producto = q.codigo_producto)
			JOIN inv_subclases_inventarios as d ON (b.subclase_id = d. subclase_id)
			AND(b.grupo_id = d.grupo_id)
			AND(b.clase_id = d.clase_id)
			JOIN inv_clases_inventarios as e ON(b.grupo_id = e.grupo_id)
			AND(b.clase_id = e.clase_id)
			WHERE
			b.estado = '1'
			AND b.descripcion ILIKE '%" . $Formulario['descripcion'] . "%'
			" . $filtro . "
			AND b.contenido_unidad_venta ILIKE '%" . $Formulario['concentracion'] . "%'
			AND d.descripcion ILIKE '%" . $Formulario['molecula'] . "%'
			AND e.descripcion ILIKE '%" . $Formulario['laboratorio'] . "%'
			AND b.codigo_producto NOT IN (
											select
											codigo_producto
											from
											ventas_ordenes_pedidos_d_tmp
											where
											pedido_cliente_id_tmp = '" . $Formulario['pedido_cliente_id_tmp'] . "'
										 )
			group by a.codigo_producto, a.precio_regulado,a.costo_ultima_compra,b.porc_iva,b.sw_requiereautorizacion_despachospedidos,b.descripcion
			ORDER BY b.descripcion ASC ";*/


        $sql = "
			SELECT
			a.codigo_producto,
                                                      a.precio_regulado,
			fc_descripcion_producto(a.codigo_producto) as descripcion,
			fc_precio_producto_contrato_cliente('" . $Formulario['contrato_cliente_id'] . "',a.codigo_producto,'" . $Formulario['empresa_id'] . "') as precio,
			(a.costo_ultima_compra)/((COALESCE(b.porc_iva,0)/100)+1) as costo_ultima_compra,
			SUM(a.existencia) as existencia,
			b.porc_iva,
			b.sw_requiereautorizacion_despachospedidos,
			SUM(p.cantidad_pendiente) as cantidad_pendiente_f,
			((SUM(a.existencia)-SUM(COALESCE(p.cantidad_pendiente,0)))-SUM(COALESCE(q.total_cantidad,0))) as total_disponible,
			CASE 
				WHEN ((SUM(a.existencia)-SUM(COALESCE(p.cantidad_pendiente,0)))-SUM(COALESCE(q.total_cantidad,0))) > 0
				THEN ((SUM(a.existencia)-SUM(COALESCE(p.cantidad_pendiente,0)))-SUM(COALESCE(q.total_cantidad,0)))
				ELSE '0'
				END as disponible,b.estado
			
			FROM
			inventarios as a
			JOIN inventarios_productos as b ON(a.empresa_id = '" . $Formulario['empresa_id'] . "')
			AND (a.codigo_producto = b.codigo_producto)
			LEFT JOIN 
				(
				select       
				a.codigo_producto,
				a.cantidad_pendiente
				from
					(
					SELECT   
					sd.codigo_producto,
					sum(sd.cantidad_pendiente) as cantidad_pendiente
					from
					
					solicitud_productos_a_bodega_principal s
					inner join solicitud_productos_a_bodega_principal_detalle sd on sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id 
					
					and   s.empresa_destino = '{$Formulario['empresa_id'] }'
					and   s.sw_despacho = '0'
                    
                                                                                         group by 1
					)as a
				)as p ON (a.codigo_producto = p.codigo_producto)
			LEFT JOIN 
				(
				SELECT
				b.codigo_producto,
				SUM((b.numero_unidades - b.cantidad_despachada)) as total_cantidad
				FROM
				ventas_ordenes_pedidos AS a
				JOIN ventas_ordenes_pedidos_d AS b ON (a.pedido_cliente_id = b.pedido_cliente_id)
				AND (a.estado = '1')
				AND (a.empresa_id = '" . $Formulario['empresa_id'] . "')
				AND (b.numero_unidades <> b.cantidad_despachada)
				GROUP BY b.codigo_producto
				)as q ON (a.codigo_producto = q.codigo_producto)
			JOIN inv_subclases_inventarios as d ON (b.subclase_id = d. subclase_id)
			AND(b.grupo_id = d.grupo_id)
			AND(b.clase_id = d.clase_id)
			JOIN inv_clases_inventarios as e ON(b.grupo_id = e.grupo_id)
			AND(b.clase_id = e.clase_id)
			WHERE
			--b.estado = '1' AND 
                        b.descripcion ILIKE '%" . $Formulario['descripcion'] . "%'
			" . $filtro . "
			AND b.contenido_unidad_venta ILIKE '%" . $Formulario['concentracion'] . "%'                          
			AND d.descripcion ILIKE '%" . $Formulario['molecula'] . "%'                          
			AND e.descripcion ILIKE '%" . $Formulario['laboratorio'] . "%'
			AND b.codigo_producto NOT IN (
											select
											codigo_producto
											from
											ventas_ordenes_pedidos_d_tmp
											where
											pedido_cliente_id_tmp = '" . $Formulario['pedido_cliente_id_tmp'] . "'
										 )
			group by a.codigo_producto, a.precio_regulado,a.costo_ultima_compra,b.porc_iva,b.sw_requiereautorizacion_despachospedidos,b.descripcion,b.estado
			ORDER BY b.descripcion ASC ";

        /*echo "<pre>";
        print_r($sql);
        echo "</pre>";
        exit();*/

        $cont = "SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, $offset);


        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

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

   function BuscarProducto_InventariosExistenciasPorCodigo($Formulario) {



        $sql = "
			SELECT
			a.codigo_producto,
                                                      a.precio_regulado,
			fc_descripcion_producto(a.codigo_producto) as descripcion,
			fc_precio_producto_contrato_cliente('" . $Formulario['contrato_cliente_id'] . "',a.codigo_producto,'" . $Formulario['empresa_id'] . "') as precio,
			(a.costo_ultima_compra)/((COALESCE(b.porc_iva,0)/100)+1) as costo_ultima_compra,
			SUM(a.existencia) as existencia,
			b.porc_iva,
			b.sw_requiereautorizacion_despachospedidos,
			SUM(p.cantidad_pendiente) as cantidad_pendiente_f,
			((SUM(a.existencia)-SUM(COALESCE(p.cantidad_pendiente,0)))-SUM(COALESCE(q.total_cantidad,0))) as total_disponible,
			CASE 
				WHEN ((SUM(a.existencia)-SUM(COALESCE(p.cantidad_pendiente,0)))-SUM(COALESCE(q.total_cantidad,0))) > 0
				THEN ((SUM(a.existencia)-SUM(COALESCE(p.cantidad_pendiente,0)))-SUM(COALESCE(q.total_cantidad,0)))
				ELSE '0'
				END as disponible,b.estado,
                        
                        b.sw_regulado, 
                        b.tipo_producto_id 
			
			FROM
			inventarios as a
			JOIN inventarios_productos as b ON(a.empresa_id = '" . $Formulario['empresa_id'] . "')
			AND (a.codigo_producto = b.codigo_producto)
			LEFT JOIN 
				(
				select       
				a.codigo_producto,
				a.cantidad_pendiente
				from
					(
					SELECT   
					sd.codigo_producto,
					sum(sd.cantidad_pendiente) as cantidad_pendiente
					from
					
					solicitud_productos_a_bodega_principal s
					inner join solicitud_productos_a_bodega_principal_detalle sd on sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id 
					
					and   s.empresa_destino = '{$Formulario['empresa_id'] }'
					and   s.sw_despacho = '0'
                    
                                                                                         group by 1
					)as a
				)as p ON (a.codigo_producto = p.codigo_producto)
			LEFT JOIN 
				(
				SELECT
				b.codigo_producto,
				SUM((b.numero_unidades - b.cantidad_despachada)) as total_cantidad
				FROM
				ventas_ordenes_pedidos AS a
				JOIN ventas_ordenes_pedidos_d AS b ON (a.pedido_cliente_id = b.pedido_cliente_id)
				AND (a.estado = '1')
				AND (a.empresa_id = '" . $Formulario['empresa_id'] . "')
				AND (b.numero_unidades <> b.cantidad_despachada)
				GROUP BY b.codigo_producto
				)as q ON (a.codigo_producto = q.codigo_producto)
			JOIN inv_subclases_inventarios as d ON (b.subclase_id = d. subclase_id)
			AND(b.grupo_id = d.grupo_id)
			AND(b.clase_id = d.clase_id)
			JOIN inv_clases_inventarios as e ON(b.grupo_id = e.grupo_id)
			AND(b.clase_id = e.clase_id)
			WHERE
			--b.estado = '1' AND 
		        b.codigo_producto = '" . $Formulario['codigo_producto'] . "' 
			AND b.codigo_producto NOT IN (
											select
											codigo_producto
											from
											ventas_ordenes_pedidos_d_tmp
											where
											pedido_cliente_id_tmp = '" . $Formulario['pedido_cliente_id_tmp'] . "'
										 )
			group by a.codigo_producto, a.precio_regulado,a.costo_ultima_compra,b.porc_iva,b.sw_requiereautorizacion_despachospedidos,b.descripcion,b.estado,
                        b.sw_regulado, b.tipo_producto_id
			ORDER BY b.descripcion ASC ";

        /*echo "<pre>";
        print_r($sql);
        echo "</pre>";
        exit();*/


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
    /* Para Los Pedidos */

    function BuscarProductos_InventariosExistencias_P($Formulario, $offset) {
        /* $this->debug=true; */
        if ($Formulario['codigo_producto'] != "")
            $filtro = " AND b.codigo_producto = '" . $Formulario['codigo_producto'] . "' ";
        $sql = "
			SELECT
			a.codigo_producto,
			fc_descripcion_producto(a.codigo_producto) as descripcion,
			fc_precio_producto_contrato_cliente('" . $Formulario['contrato_cliente_id'] . "',a.codigo_producto,'" . $Formulario['empresa_id'] . "') as precio,
			(a.costo_ultima_compra)/((COALESCE(b.porc_iva,0)/100)+1) as costo_ultima_compra,
			SUM(a.existencia) as existencia,
			b.porc_iva,
			b.sw_requiereautorizacion_despachospedidos,
			SUM(p.cantidad_pendiente) as cantidad_pendiente_f,
			((SUM(a.existencia)-SUM(COALESCE(p.cantidad_pendiente,0)))-SUM(COALESCE(q.total_cantidad,0))) as total_disponible,
			CASE 
				WHEN ((SUM(a.existencia)-SUM(COALESCE(p.cantidad_pendiente,0)))-SUM(COALESCE(q.total_cantidad,0))) > 0
				THEN ((SUM(a.existencia)-SUM(COALESCE(p.cantidad_pendiente,0)))-SUM(COALESCE(q.total_cantidad,0)))
				ELSE '0'
				END as disponible
			FROM
			inventarios as a
			JOIN inventarios_productos as b ON(a.empresa_id = '" . $Formulario['empresa_id'] . "')
			AND (a.codigo_producto = b.codigo_producto)
			LEFT JOIN 
				(
				select       
				a.codigo_producto,
				a.cantidad_pendiente
				from
					(
					SELECT   
					sd.codigo_producto,
					sd.cantidad_solic as cantidad_pendiente
					from
					solicitud_productos_a_bodega_principal_detalle sd,
					solicitud_productos_a_bodega_principal s
					where
					sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id
					and   s.empresa_destino = '" . $Formulario['empresa_id'] . "'
					and   s.sw_despacho = '0'
					UNION     
					SELECT 
					sd.codigo_producto,
					ips.cantidad_pendiente
					from
					solicitud_productos_a_bodega_principal_detalle sd,
					solicitud_productos_a_bodega_principal s,
					inv_mov_pendientes_solicitudes_frm ips
					where
					sd.solicitud_prod_a_bod_ppal_id = s.solicitud_prod_a_bod_ppal_id
					and   s.empresa_destino = '" . $Formulario['empresa_id'] . "'
					and   s.sw_despacho = '1'
					and   sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id
					and   sd.solicitud_prod_a_bod_ppal_id = ips.solicitud_prod_a_bod_ppal_id
					and   sd.solicitud_prod_a_bod_ppal_det_id = ips.solicitud_prod_a_bod_ppal_det_id
					)as a
				)as p ON (a.codigo_producto = p.codigo_producto)
			LEFT JOIN 
				(
				SELECT
				b.codigo_producto,
				SUM((b.numero_unidades - b.cantidad_despachada)) as total_cantidad
				FROM
				ventas_ordenes_pedidos AS a
				JOIN ventas_ordenes_pedidos_d AS b ON (a.pedido_cliente_id = b.pedido_cliente_id)
				AND (a.estado = '1')
				AND (a.empresa_id = '" . $Formulario['empresa_id'] . "')
				AND (b.numero_unidades <> b.cantidad_despachada)
				GROUP BY b.codigo_producto
				)as q ON (a.codigo_producto = q.codigo_producto)
			JOIN inv_subclases_inventarios as d ON (b.subclase_id = d. subclase_id)
			AND(b.grupo_id = d.grupo_id)
			AND(b.clase_id = d.clase_id)
			JOIN inv_clases_inventarios as e ON(b.grupo_id = e.grupo_id)
			AND(b.clase_id = e.clase_id)
			WHERE
			b.estado = '1'
			AND b.descripcion ILIKE '%" . $Formulario['descripcion'] . "%'
			" . $filtro . "
			AND b.contenido_unidad_venta ILIKE '%" . $Formulario['concentracion'] . "%'                          
			AND d.descripcion ILIKE '%" . $Formulario['molecula'] . "%'                          
			AND e.descripcion ILIKE '%" . $Formulario['laboratorio'] . "%'
			AND b.codigo_producto NOT IN (
											select
											codigo_producto
											from
											ventas_ordenes_pedidos_d
											where
											pedido_cliente_id = '" . $Formulario['pedido_cliente_id'] . "'
										 )
			group by a.codigo_producto,a.costo_ultima_compra,b.porc_iva,b.sw_requiereautorizacion_despachospedidos,b.descripcion
			ORDER BY b.descripcion ASC 
						  ";

        $cont = "SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, $offset);


        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

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

    function BorrarItem_Cotizacion($pedido_cliente_id_tmp, $codigo_producto) {
        // $this->debug = true;

        $this->ConexionTransaccion();

        $sql = " DELETE     from ventas_ordenes_pedidos_d_tmp ";
        $sql .= " Where ";
        $sql .= " pedido_cliente_id_tmp = " . $pedido_cliente_id_tmp . "   and codigo_producto='" . $codigo_producto . "'; ";
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    function Inactivar_Cotizacion($pedido_cliente_id_tmp) {
        $this->ConexionTransaccion();

        $sql = " UPDATE ventas_ordenes_pedidos_tmp ";
        $sql .= " SET ";
        $sql .= " estado = '0' ";
        $sql .= " WHERE ";
        $sql .= " pedido_cliente_id_tmp = " . $pedido_cliente_id_tmp . "; ";
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    function Sql_IngresarPedido($Datos) {
        //$this->debug=true;
        $this->ConexionTransaccion();

        $usuario_id = UserGetUID();

        $sql = "INSERT INTO ventas_ordenes_pedidos(
                    pedido_cliente_id , 
                    empresa_id, 
                    tercero_id, 
                    tipo_id_tercero, 
                    tipo_id_vendedor, 
                    vendedor_id, 
                    usuario_id,
                    observacion,
					centro_destino,
					bodega_destino)
                VALUES( 
                    DEFAULT, 
                    '{$Datos['empresa_id']}', 
                    '{$Datos['tercero_id']}', 
                    '{$Datos['tipo_id_tercero']}', 
                    '{$Datos['tipo_id_vendedor']}', 
                    '{$Datos['vendedor_id']}', 
                    {$usuario_id},
                    '{$Datos['observaciones']}',
					'1',
					'03'
              )RETURNING(pedido_cliente_id); ";

        /*echo "<pre>";
        print_r($sql);
        echo "</pre>";
        exit();*/

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $this->Commit();
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function Sql_IngresarPedido_d($pedido_cliente_id, $Datos) {

        $this->ConexionTransaccion();

        foreach ($Datos as $key => $valor) {
            $sql .= "INSERT INTO ventas_ordenes_pedidos_d( ";
            $sql .= "       item_id , ";
            $sql .= "       pedido_cliente_id , ";
            $sql .= "       codigo_producto, ";
            $sql .= "       porc_iva, ";
            $sql .= "       numero_unidades, ";
            $sql .= "       valor_unitario, ";
            $sql .= "       usuario_id ";
            $sql .= "        ";

            $sql .= ")VALUES( ";
            $sql .= "       DEFAULT, ";
            $sql .= "       '" . $pedido_cliente_id . "', ";
            $sql .= "       '" . $valor['codigo_producto'] . "', ";
            $sql .= "       '" . $valor['porc_iva'] . "', ";
            $sql .= "       '" . $valor['numero_unidades'] . "', ";
            $sql .= "       '" . $valor['valor_unitario'] . "', ";
            $sql .= "       " . UserGetUID() . " ";
            $sql .= "       ); ";
        }
        if (!$rst1 = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        return true;
    }

    function Listado_Pedidos($empresa_id, $filtros, $offset) {
        /* $this->debug=true; */
        if ($filtros['pedido_cliente_id'] != "")
            $where .= " AND a.pedido_cliente_id = " . $filtros['pedido_cliente_id'] . " ";
        if ($filtros['nombre_tercero'] != "")
            $where .= " AND b.nombre_tercero ILIKE '%" . $filtros['nombre_tercero'] . "%' ";

        $sql = "SELECT
	a.pedido_cliente_id,
	e.tipo_id_tercero as tipo_id_empresa,
	e.id,
	e.digito_verificacion,
	a.empresa_id,
	e.razon_social,
	a.tipo_id_tercero,
	a.tercero_id,
	b.nombre_tercero,
	b.direccion,
	b.telefono,
	a.fecha_registro,
	a.usuario_id,
	a.tipo_id_vendedor,
	a.vendedor_id,
	c.nombre,
	a.estado,
	a.fecha_envio,
	h.pais ||'-'||g.departamento||'-' || f.municipio as ubicacion,
	ll.pais ||'-'||l.departamento||'-' || k.municipio as ubicacion_empresa,
	e.direccion as direccion_empresa,
	e.telefonos,
	a.tipo_id_vendedor||'@'||a.vendedor_id as vendedor,
	i.accion,
	j.pedido_cliente_id as temporal,
	a.observacion
	FROM
	ventas_ordenes_pedidos as a
	JOIN terceros as b ON (a.tipo_id_tercero = b.tipo_id_tercero)
	AND (a.tercero_id = b.tercero_id)
	AND (a.estado IN ('1','0','2','3'))
	JOIN vnts_vendedores as c ON (a.tipo_id_vendedor = c.tipo_id_vendedor)
	AND (a.vendedor_id = c.vendedor_id)
	JOIN system_usuarios as d ON (a.usuario_id = d.usuario_id)
	JOIN empresas as e ON (a.empresa_id = e.empresa_id)
	JOIN tipo_mpios as f ON (b.tipo_pais_id = f.tipo_pais_id)
	AND (b.tipo_dpto_id = f.tipo_dpto_id)
	AND (b.tipo_mpio_id = f.tipo_mpio_id)
	JOIN tipo_dptos as g ON (f.tipo_pais_id = g.tipo_pais_id)
	AND (f.tipo_dpto_id = g.tipo_dpto_id)
	JOIN tipo_pais as h ON (g.tipo_pais_id = h.tipo_pais_id)
	LEFT JOIN (
				select
				pedido_cliente_id,
				SUM(cantidad_despachada) as cantidad_despachada,
				CASE 
				WHEN SUM(cantidad_despachada)=0
				THEN 'ANULAR'
				ELSE 'INACTIVAR'
				END as accion
				FROM
				ventas_ordenes_pedidos_d
				GROUP BY pedido_cliente_id
			   ) as i ON (a.pedido_cliente_id = i.pedido_cliente_id)
    LEFT JOIN (
				SELECT
				a.pedido_cliente_id
				FROM
				inv_bodegas_movimiento_tmp_despachos_clientes AS a
			   ) as j ON (a.pedido_cliente_id = j.pedido_cliente_id)
	JOIN tipo_mpios as k ON (e.tipo_pais_id = k.tipo_pais_id)
	AND (e.tipo_dpto_id = k.tipo_dpto_id)
	AND (e.tipo_mpio_id = k.tipo_mpio_id)
	JOIN tipo_dptos as l ON (k.tipo_pais_id = l.tipo_pais_id)
	AND (k.tipo_dpto_id = l.tipo_dpto_id)
	JOIN tipo_pais as ll ON (l.tipo_pais_id = ll.tipo_pais_id)
	WHERE
	a.empresa_id = '" . $empresa_id . "'
	" . $where . "
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

    /* Consulta pedidos clientes para bodega */

    function Listado_Pedidos2($empresa_id, $filtros, $offset) {
        /* $this->debug=true; */
        if ($filtros['pedido_cliente_id'] != "")
            $where .= " AND a.pedido_cliente_id >= " . $filtros['pedido_cliente_id'] . " ";
        if ($filtros['nombre_tercero'] != "")
            $where .= " AND b.nombre_tercero ILIKE '%" . $filtros['nombre_tercero'] . "%' ";

        $sql = "SELECT
	a.pedido_cliente_id,
	e.tipo_id_tercero as tipo_id_empresa,
	e.id,
	e.digito_verificacion,
	a.empresa_id,
	e.razon_social,
	a.tipo_id_tercero,
	a.tercero_id,
	b.nombre_tercero,
	b.direccion,
	b.telefono,
	a.fecha_registro,
	a.usuario_id,
	a.tipo_id_vendedor,
	a.vendedor_id,
	c.nombre,
	a.estado,
	a.fecha_envio,
	h.pais ||'-'||g.departamento||'-' || f.municipio as ubicacion,
	ll.pais ||'-'||l.departamento||'-' || k.municipio as ubicacion_empresa,
	e.direccion as direccion_empresa,
	e.telefonos,
	a.tipo_id_vendedor||'@'||a.vendedor_id as vendedor,
	i.accion,
	j.pedido_cliente_id as temporal,
	a.observacion
	FROM
	ventas_ordenes_pedidos as a
	JOIN terceros as b ON (a.tipo_id_tercero = b.tipo_id_tercero)
	AND (a.tercero_id = b.tercero_id)
	AND (a.estado IN ('1','0','2','3'))
	JOIN vnts_vendedores as c ON (a.tipo_id_vendedor = c.tipo_id_vendedor)
	AND (a.vendedor_id = c.vendedor_id)
	JOIN system_usuarios as d ON (a.usuario_id = d.usuario_id)
	JOIN empresas as e ON (a.empresa_id = e.empresa_id)
	JOIN tipo_mpios as f ON (b.tipo_pais_id = f.tipo_pais_id)
	AND (b.tipo_dpto_id = f.tipo_dpto_id)
	AND (b.tipo_mpio_id = f.tipo_mpio_id)
	JOIN tipo_dptos as g ON (f.tipo_pais_id = g.tipo_pais_id)
	AND (f.tipo_dpto_id = g.tipo_dpto_id)
	JOIN tipo_pais as h ON (g.tipo_pais_id = h.tipo_pais_id)
	LEFT JOIN (
				select
				pedido_cliente_id,
				SUM(cantidad_despachada) as cantidad_despachada,
				CASE 
				WHEN SUM(cantidad_despachada)=0
				THEN 'ANULAR'
				ELSE 'INACTIVAR'
				END as accion
				FROM
				ventas_ordenes_pedidos_d
				GROUP BY pedido_cliente_id
			   ) as i ON (a.pedido_cliente_id = i.pedido_cliente_id)
    LEFT JOIN (
				SELECT
				a.pedido_cliente_id
				FROM
				inv_bodegas_movimiento_tmp_despachos_clientes AS a
			   ) as j ON (a.pedido_cliente_id = j.pedido_cliente_id)
	JOIN tipo_mpios as k ON (e.tipo_pais_id = k.tipo_pais_id)
	AND (e.tipo_dpto_id = k.tipo_dpto_id)
	AND (e.tipo_mpio_id = k.tipo_mpio_id)
	JOIN tipo_dptos as l ON (k.tipo_pais_id = l.tipo_pais_id)
	AND (k.tipo_dpto_id = l.tipo_dpto_id)
	JOIN tipo_pais as ll ON (l.tipo_pais_id = ll.tipo_pais_id)
	WHERE
	a.empresa_id = '" . $empresa_id . "'
	" . $where . "
	";

        $cont = "SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, $offset);
        $sql .= "ORDER BY a.pedido_cliente_id, a.fecha_registro ASC ";
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

    /* Consulta simple pedidos clientes para vista de impresion */

    function Lista_pedidos_clientes($pedidoid) {

        $sql = "SELECT
		a.pedido_cliente_id,
		e.tipo_id_tercero as tipo_id_empresa,
		e.id,
		e.digito_verificacion,
		a.empresa_id,
		e.razon_social,
		a.tipo_id_tercero,
		a.tercero_id,
		b.nombre_tercero,
		b.direccion,
		b.telefono,
		a.fecha_registro,
		a.usuario_id,
		a.tipo_id_vendedor,
		a.vendedor_id,
		c.nombre,
		a.estado,
		a.fecha_envio,
		h.pais ||'-'||g.departamento||'-' || f.municipio as ubicacion,
		ll.pais ||'-'||l.departamento||'-' || k.municipio as ubicacion_empresa,
		e.direccion as direccion_empresa,
		e.telefonos,
		a.tipo_id_vendedor||'@'||a.vendedor_id as vendedor,
		i.accion,
		j.pedido_cliente_id as temporal,
		a.observacion
		FROM
		ventas_ordenes_pedidos as a
		JOIN terceros as b ON (a.tipo_id_tercero = b.tipo_id_tercero)
		AND (a.tercero_id = b.tercero_id)
		AND (a.estado IN ('1','0','2','3'))
		JOIN vnts_vendedores as c ON (a.tipo_id_vendedor = c.tipo_id_vendedor)
		AND (a.vendedor_id = c.vendedor_id)
		JOIN system_usuarios as d ON (a.usuario_id = d.usuario_id)
		JOIN empresas as e ON (a.empresa_id = e.empresa_id)
		JOIN tipo_mpios as f ON (b.tipo_pais_id = f.tipo_pais_id)
		AND (b.tipo_dpto_id = f.tipo_dpto_id)
		AND (b.tipo_mpio_id = f.tipo_mpio_id)
		JOIN tipo_dptos as g ON (f.tipo_pais_id = g.tipo_pais_id)
		AND (f.tipo_dpto_id = g.tipo_dpto_id)
		JOIN tipo_pais as h ON (g.tipo_pais_id = h.tipo_pais_id)
		LEFT JOIN (
					select
					pedido_cliente_id,
					SUM(cantidad_despachada) as cantidad_despachada,
					CASE 
					WHEN SUM(cantidad_despachada)=0
					THEN 'ANULAR'
					ELSE 'INACTIVAR'
					END as accion
					FROM
					ventas_ordenes_pedidos_d
					GROUP BY pedido_cliente_id
				   ) as i ON (a.pedido_cliente_id = i.pedido_cliente_id)
	    LEFT JOIN (
					SELECT
					a.pedido_cliente_id
					FROM
					inv_bodegas_movimiento_tmp_despachos_clientes AS a
				   ) as j ON (a.pedido_cliente_id = j.pedido_cliente_id)
		JOIN tipo_mpios as k ON (e.tipo_pais_id = k.tipo_pais_id)
		AND (e.tipo_dpto_id = k.tipo_dpto_id)
		AND (e.tipo_mpio_id = k.tipo_mpio_id)
		JOIN tipo_dptos as l ON (k.tipo_pais_id = l.tipo_pais_id)
		AND (k.tipo_dpto_id = l.tipo_dpto_id)
		JOIN tipo_pais as ll ON (l.tipo_pais_id = ll.tipo_pais_id)
		WHERE
	            a.pedido_cliente_id >= " . $pedidoid . " ORDER BY a.pedido_cliente_id, a.fecha_registro ASC ";

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

    function Consulta_Pedido_d($pedido_cliente_id) {

        //$this->debug = true;
        $sql = "SELECT
		a.pedido_cliente_id,
		a.codigo_producto,
		fc_descripcion_producto(a.codigo_producto) as descripcion,
		a.porc_iva,
		(a.numero_unidades-cantidad_despachada) as total,
		a.numero_unidades,
		a.cantidad_despachada,
		a.valor_unitario,	
		(a.valor_unitario+(a.valor_unitario*(a.porc_iva/100)))as valor_unitario_iva,
		(a.numero_unidades*(a.valor_unitario*(a.porc_iva/100))) as iva,
		b.sw_requiereautorizacion_despachospedidos
		FROM
		ventas_ordenes_pedidos_d AS a
		JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
		JOIN inv_fabricantes as c ON (b.fabricante_id = c.fabricante_id)
		WHERE
		a.pedido_cliente_id = " . $pedido_cliente_id . "
		ORDER BY b.sw_requiereautorizacion_despachospedidos ASC,
		c.descripcion ASC,
		b.descripcion ASC;";

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

    function CambiarEstado_Pedido($pedido_cliente_id, $observacion_anulacion, $estado) {
        $this->ConexionTransaccion();

        $sql = " UPDATE ventas_ordenes_pedidos ";
        $sql .= " SET ";
        $sql .= " estado = '" . $estado . "', ";
        $sql .= " usuario_anulador = " . UserGetUID() . ", ";
        $sql .= " fecha_registro_anulacion = NOW(), ";
        $sql .= " observacion_anulacion = '" . $observacion_anulacion . "' ";
        $sql .= " WHERE ";
        $sql .= " pedido_cliente_id = " . $pedido_cliente_id . "; ";
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    function ModificarPedido($pedido_cliente_id, $codigo_producto, $numero_unidades) {
        $this->ConexionTransaccion();

        $sql = " UPDATE ventas_ordenes_pedidos_d ";
        $sql .= " SET ";
        $sql .= " numero_unidades = '" . $numero_unidades . "', ";
        $sql .= " usuario_id = " . UserGetUID() . ", ";
        $sql .= " fecha_registro = NOW() ";
        $sql .= " WHERE ";
        $sql .= " pedido_cliente_id = " . $pedido_cliente_id . " ";
        $sql .= " AND codigo_producto = '" . $codigo_producto . "'; ";
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    function AsignarObservacion($pedido_cliente_id, $observacion) {
        $this->ConexionTransaccion();

        $sql = " UPDATE ventas_ordenes_pedidos ";
        $sql .= " SET ";
        $sql .= " observacion = '" . $observacion . "' ";
        $sql .= " WHERE ";
        $sql .= " pedido_cliente_id = " . $pedido_cliente_id . "; ";

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    function Sql_IngresarProductoCotizacion($pedido_cliente_id_tmp, $codigo_producto, $porc_iva, $cantidad, $valor_unitario) {

        //$this->debug=true;
        $sql = "INSERT INTO ventas_ordenes_pedidos_d_tmp( ";
        $sql .= "       item_id , ";
        $sql .= "       pedido_cliente_id_tmp , ";
        $sql .= "       codigo_producto, ";
        $sql .= "       usuario_id, ";
        $sql .= "       porc_iva, ";
        $sql .= "       numero_unidades, ";
        $sql .= "       valor_unitario ";
        $sql .= "        ";

        $sql .= ")VALUES( ";
        $sql .= "       DEFAULT, ";
        $sql .= "       '" . $pedido_cliente_id_tmp . "', ";
        $sql .= "       '" . $codigo_producto . "', ";
        $sql .= "          " . UserGetUID() . ", ";
        $sql .= "       '" . $porc_iva . "', ";
        $sql .= "       '" . $cantidad . "', ";
        $sql .= "       '" . $valor_unitario . "' ";
        $sql .= "       ); ";


        return $sql;
    }

    function BorrarItem_Pedido($pedido_cliente_id, $codigo_producto) {
        // $this->debug = true;

        $this->ConexionTransaccion();

        $sql = " DELETE     from ventas_ordenes_pedidos_d ";
        $sql .= " Where ";
        $sql .= " pedido_cliente_id = " . $pedido_cliente_id . "   and codigo_producto='" . $codigo_producto . "'; ";
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

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

    function Listado_Usuarios() {
        $sql .= "SELECT
			a.usuario_id,
			a.nombre,
			a.usuario,
			a.activo
			FROM
			system_usuarios as a
			WHERE
			a.activo = '1'
			AND a.sw_admin = '0'
			AND a.usuario_id <> 0
			ORDER BY a.nombre
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
     * Funcion donde se obtiene el listado de productos sin movimiento
     *
     * @param string $empresa Identificador de la empresa
     * @param array $filtros Arreglo con los filtros para la busqueda de la nota
     *
     * @return mixed
     */
    function Listado_Vendedores($empresa, $filtros, $offset) {

        //print_r($filtros);
        if ($filtros['vendedor_id'] != "")
            $filtro .= " AND a.vendedor_id = '" . $filtros['vendedor_id'] . "' ";
        if ($filtros['tipo_id_vendedor'] != "")
            $filtro .= " AND a.tipo_id_vendedor = '" . $filtros['tipo_id_vendedor'] . "' ";
        $sql = "
		SELECT
		a.tipo_id_vendedor,
		a.vendedor_id,
		a.nombre,
		a.telefono,
		a.usuario_sistema,
		a.estado,
		b.usuario,
		b.nombre as nombre_usuario
		FROM
		vnts_vendedores as a
		LEFT JOIN system_usuarios as b ON(a.usuario_sistema = b.usuario_id)
		WHERE
		a.estado IN ('1','0')
		AND a.nombre ILIKE '%" . $filtros['nombre'] . "%' ";
        $sql .= $filtro;
        $cont = "SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, $offset);
        $sql .= "ORDER BY a.nombre  ASC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;

        // print_r($filtros);
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

    function Ingresar_Vendedor($Datos) {
        /* $this->debug=true; */
        if ($Datos['usuario_sistema'] == "")
            $usuario_sistema = "NULL";
        else
            $usuario_sistema = $Datos['usuario_sistema'];
        $this->ConexionTransaccion();
        $sql = "INSERT INTO vnts_vendedores( ";
        $sql .= "       tipo_id_vendedor , ";
        $sql .= "       vendedor_id, ";
        $sql .= "       nombre, ";
        $sql .= "       telefono, ";
        $sql .= "       usuario_sistema, ";
        $sql .= "       usuario_id ";

        $sql .= ")VALUES( ";
        $sql .= "       '" . $Datos['tipo_id_vendedor'] . "', ";
        $sql .= "       '" . $Datos['vendedor_id'] . "', ";
        $sql .= "       '" . $Datos['nombre'] . "', ";
        $sql .= "       '" . $Datos['telefono'] . "', ";
        $sql .= "       " . $usuario_sistema . ", ";
        $sql .= "       " . UserGetUID() . " ";
        $sql .= "       ); ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $this->Commit();
        $rst->Close();
        return true;
    }

    function Modificar_Vendedor($Datos) {
        /* $this->debug=true; */
        if ($Datos['usuario_sistema'] == "")
            $usuario_sistema = "NULL";
        else
            $usuario_sistema = $Datos['usuario_sistema'];
        $this->ConexionTransaccion();
        $sql = "UPDATE vnts_vendedores ";
        $sql .= "	SET";
        $sql .= "       tipo_id_vendedor =  '" . $Datos['tipo_id_vendedor'] . "', ";
        $sql .= "       vendedor_id =  '" . $Datos['vendedor_id'] . "', ";
        $sql .= "       nombre = '" . $Datos['nombre'] . "', ";
        $sql .= "       telefono = '" . $Datos['telefono'] . "', ";
        $sql .= "       usuario_sistema = " . $usuario_sistema . ", ";
        $sql .= "       usuario_id = " . UserGetUID() . ", ";
        $sql .= "       fecha_registro = NOW() ";
        $sql .= "WHERE  ";
        $sql .= "       tipo_id_vendedor = '" . $Datos['tipo_id_vendedor_old'] . "' ";
        $sql .= "AND    vendedor_id =  '" . $Datos['vendedor_id_old'] . "'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $this->Commit();
        $rst->Close();
        return true;
    }

    function Inactivar_Vendedor($datos) {
        $this->ConexionTransaccion();

        $sql = " UPDATE vnts_vendedores ";
        $sql .= " SET ";
        $sql .= " estado = '" . $datos['cambiar_estado'] . "' ";
        $sql .= " WHERE ";
        $sql .= " tipo_id_vendedor = '" . $datos['tipo_id_vendedor'] . "' ";
        $sql .= " AND vendedor_id = '" . $datos['vendedor_id'] . "'; ";
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    function Vigencia_Cotizacion($empresa_id) {
        $sql .= "SELECT
			*
			FROM
			vnts_vigencia_cotizacion as a
			WHERE
			a.empresa_id = '" . $empresa_id . "';";

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

    function Ingresar_VigenciaCotizacion($empresa_id, $Datos) {
        /* $this->debug=true; */
        $this->ConexionTransaccion();
        $sql = "INSERT INTO vnts_vigencia_cotizacion( ";
        $sql .= "       dias , ";
        $sql .= "       empresa_id ";
        $sql .= ")VALUES( ";
        $sql .= "       '" . $Datos['dias'] . "', ";
        $sql .= "       '" . $empresa_id . "' ";
        $sql .= "       ); ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $this->Commit();
        $rst->Close();
        return true;
    }

    function Modificar_VigenciaCotizacion($empresa_id, $datos) {
        $this->ConexionTransaccion();

        $sql = " UPDATE vnts_vigencia_cotizacion ";
        $sql .= " SET ";
        $sql .= " dias = " . $datos['dias'] . " ";
        $sql .= " WHERE ";
        $sql .= " empresa_id = '" . $empresa_id . "'; ";

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    function InactivarCotizacion_Vigencia($empresa_id, $dias) {
        $this->ConexionTransaccion();

        $sql = " UPDATE ventas_ordenes_pedidos_tmp
			SET estado= '0'
			WHERE
			NOW() > (fecha_registro + '" . $dias . "d'::interval)
			AND estado = '1'
		    AND empresa_id = '" . $empresa_id . "'; ";

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    /*
     * @param string $empresa Identificador de la empresa
     * @param array $filtros Arreglo con los filtros para la busqueda de la nota
     *
     * @return mixed
     */

    function Listado_ProductosPendientes($empresa) {

        //print_r($filtros);
        $sql = "
		SELECT
		a.pedido_cliente_id,
		b.codigo_producto,
		fc_descripcion_producto(b.codigo_producto) as descripcion,
		b.numero_unidades,
		b.cantidad_despachada,
		(b.numero_unidades - b.cantidad_despachada) as cantidad_pendiente
		FROM
		ventas_ordenes_pedidos as a
		JOIN ventas_ordenes_pedidos_d as b ON (a.pedido_cliente_id = b.pedido_cliente_id)
		AND (a.estado = '1')
		AND (a.empresa_id ='" . $empresa . "')
		AND (b.numero_unidades <> b.cantidad_despachada)
		ORDER BY a.pedido_cliente_id ";

        // print_r($filtros);
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

    /*     * **********************************************************************************
     * 24102012
     * Funcion que permite obtener el valor de iva, valores invima e identificacion
     * de regulacion de medic.
     * @param: $codigo: codigo de producto
     * @return: $datos: arreglo con datos del producto
     * *********************************************************************************** */

    function Get_dataProd($codigo) {
        $sql = "SELECT porc_iva, sw_regulado, codigo_invima, vencimiento_codigo_invima, codigo_cum, tipo_producto_id ";
        $sql .= "   FROM  inventarios_productos ";
        $sql .= " WHERE codigo_producto = '" . $codigo . "' ";

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

    function Get_PrecioRegulado($codigo) {
        $sql = "SELECT inv.precio_regulado as precio FROM inventarios inv, inventarios_productos ip ";
        $sql .= " WHERE inv.codigo_producto = ip.codigo_producto AND inv.empresa_id = '03' ";
        $sql .= "      AND ip.codigo_producto = '" . $codigo . "' ";
        $sql .= "      AND ip.sw_regulado = '1'  ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $precio = array();
        while (!$rst->EOF) {
            $precio = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $precio;
    }

}

?>
