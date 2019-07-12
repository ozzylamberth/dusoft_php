<?php

/* * ****************************************************************************
 * $Id: CrearSQL.class.php,v 1.10 2010/02/08 13:34:07 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * $Revision: 1.10 $ 
 * 
 * @autor Jaime Gomez
 * ****************************************************************************** */

class CrearSQL extends ConexionBD {

    /**
     * Funcion donde se obtiene el listado de productos sin movimiento
     *
     * @param string $empresa Identificador de la empresa
     * @param array $filtros Arreglo con los filtros para la busqueda de la nota
     *
     * @return mixed
     */
    function Terceros($filtros, $empresa_id, $offset) {
        if ($filtros['tipo_id_tercero'] != "")
            $filtro .= " and  a.tipo_id_tercero = '" . $filtros['tipo_id_tercero'] . "' ";
        if ($filtros['tercero_id'] != "")
            $filtro .= " and  a.tercero_id = " . $filtros['tercero_id'] . "";
        if ($filtros['nombre_tercero'] != "")
            $filtro .= " and  a.nombre_tercero ILIKE '%" . $filtros['nombre_tercero'] . "%' ";
        //$this->debug=true;
        $sql = "
			select
			a.tipo_id_tercero,
			a.tercero_id,
			a.direccion,
			a.telefono,
			a.fax,
			a.email,
			a.celular,
			a.sw_persona_juridica,
			a.nombre_tercero,
			a.dv,
			a.tipo_pais_id,
			d.pais,
			a.tipo_dpto_id,
			c.departamento,
			a.tipo_mpio_id,
			b.municipio,
			a.tipo_bloqueo_id,
			CASE WHEN e.tercero_id IS NOT NULL 
			THEN 'Cliente' ELSE '' END as cliente,
			CASE WHEN f.tercero_id IS NOT NULL 
			THEN '1' ELSE '0' END as tercero_proveedor,
			CASE WHEN f.tercero_id IS NOT NULL 
			THEN 'Proveedor' ELSE '' END as proveedor,
			CASE WHEN a.tipo_bloqueo_id = '1'
			THEN g.descripcion||'@checksi.png'
			WHEN a.tipo_bloqueo_id = '0'
			THEN g.descripcion||'@checkno.png'
			ELSE g.descripcion||'@bloqueo.png' END as bloqueo
			from
			terceros as a JOIN
			tipo_mpios as b ON(a.tipo_pais_id = b.tipo_pais_id)
			and (a.tipo_dpto_id = b.tipo_dpto_id)
			and (a.tipo_mpio_id = b.tipo_mpio_id)
			JOIN tipo_dptos as c ON (b.tipo_pais_id = c.tipo_pais_id)
			and (b.tipo_dpto_id = c.tipo_dpto_id)
			JOIN tipo_pais as d ON(c.tipo_pais_id = d.tipo_pais_id)
			LEFT JOIN terceros_clientes as e ON (a.tipo_id_tercero = e.tipo_id_tercero)
			and (a.tercero_id = e.tercero_id)
			and (e.empresa_id = '" . trim($empresa_id) . "')
			LEFT JOIN terceros_proveedores as f ON (a.tipo_id_tercero = f.tipo_id_tercero)
			and (a.tercero_id = f.tercero_id)
			LEFT JOIN inv_tipos_bloqueos as g ON (a.tipo_bloqueo_id = g.tipo_bloqueo_id)
			WHERE
			a.tipo_id_tercero IS NOT NULL
			";
        $sql .= $filtro;

        $cont = "SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, null, $offset);
        $sql .= "ORDER BY a.nombre_tercero  ASC ";
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

    /**
     * Funcion donde se obtiene el listado de productos sin movimiento
     *
     * @param string $empresa Identificador de la empresa
     * @param array $filtros Arreglo con los filtros para la busqueda de la nota
     *
     * @return mixed
     */
    function Tercero($Datos, $empresa_id) {

        //$this->debug=true;
        $sql = "
			select
			a.tipo_id_tercero,
			a.tercero_id,
			a.direccion,
			a.telefono,
			a.fax,
			a.email,
			a.celular,
			a.sw_persona_juridica,
			a.nombre_tercero,
			a.dv,
			a.tipo_pais_id,
			d.pais,
			a.tipo_dpto_id,
			c.departamento,
			a.tipo_mpio_id,
			b.municipio,
			a.tipo_bloqueo_id,
			COALESCE(e.sw_gran_contribuyente,'0')as sw_gran_contribuyente,
			COALESCE(e.sw_regimen_comun,'0')as sw_regimen_comun,
			COALESCE(e.sw_rtf,'0') as sw_rtf,
			COALESCE(e.porcentaje_rtf,0) as porcentaje_rtf,
			COALESCE(e.sw_reteiva,'0')as sw_reteiva,
			COALESCE(e.porcentaje_reteiva,0) as porcentaje_reteiva,
			COALESCE(e.sw_ica,'0') as sw_ica,
			COALESCE(e.porcentaje_ica,0) as porcentaje_ica,
			COALESCE(e.sw_cree,'0') as sw_cree,
			COALESCE(e.porcentaje_cree,0) as porcentaje_cree,
			e.tipo_cliente,
			e.observacion,
			e.codigo_unidad_negocio,
			CASE WHEN e.tercero_id IS NOT NULL 
			THEN '1' ELSE '0' END as tercero_cliente,
			CASE WHEN e.tercero_id IS NOT NULL 
			THEN 'Es Cliente' ELSE 'NO Es Cliente' END as cliente,
			CASE WHEN f.tercero_id IS NOT NULL 
			THEN '1' ELSE '0' END as tercero_proveedor,
			CASE WHEN f.tercero_id IS NOT NULL 
			THEN 'ES Proveedor' ELSE 'NO Es Proveedor' END as proveedor,
			COALESCE(f.sw_gran_contribuyente,'0')as prov_sw_gran_contribuyente,
			COALESCE(f.sw_regimen_comun,'0')as prov_sw_regimen_comun,
			COALESCE(f.sw_rtf,'0') as prov_sw_rtf,
			COALESCE(f.porcentaje_rtf,0) as prov_porcentaje_rtf,
			COALESCE(f.sw_reteiva,'0')as prov_sw_reteiva,
			COALESCE(f.porcentaje_reteiva,0) as prov_porcentaje_reteiva,
			COALESCE(f.sw_ica,'0') as prov_sw_ica,
			COALESCE(f.porcentaje_ica,0) as prov_porcentaje_ica,
			COALESCE(f.sw_cree,'0') as prov_sw_cree,
			COALESCE(f.porcentaje_cree,0) as prov_porcentaje_cree,
			f.actividad_id,
			COALESCE(f.dias_gracia,0) as dias_gracia,
			COALESCE(f.dias_credito,0) as dias_credito,
			COALESCE(f.tiempo_entrega,0) as tiempo_entrega,
			COALESCE(f.descuento_por_contado,0) as descuento_por_contado,
			f.representante_ventas,
			f.telefono_representante_ventas,
			f.nombre_gerente,
			f.telefono_gerente,
			g.grupo_id,
			f.sw_pago_abono_cta,
			f.sw_pago_cheque,
			f.sw_pago_efectivo,
                        e.cuenta_contable,
                        f.cxp_proveedor
			from
			terceros as a JOIN
			tipo_mpios as b ON(a.tipo_pais_id = b.tipo_pais_id)
			and (a.tipo_dpto_id = b.tipo_dpto_id)
			and (a.tipo_mpio_id = b.tipo_mpio_id)
			JOIN tipo_dptos as c ON (b.tipo_pais_id = c.tipo_pais_id)
			and (b.tipo_dpto_id = c.tipo_dpto_id)
			JOIN tipo_pais as d ON(c.tipo_pais_id = d.tipo_pais_id)
			LEFT JOIN terceros_clientes as e ON (a.tipo_id_tercero = e.tipo_id_tercero)
			and (a.tercero_id = e.tercero_id)
			and (e.empresa_id = '" . trim($empresa_id) . "')
			LEFT JOIN terceros_proveedores as f ON (a.tipo_id_tercero = f.tipo_id_tercero)
			and (a.tercero_id = f.tercero_id)
			LEFT JOIN actividades_industriales as g ON (f.actividad_id = g.actividad_id)
			WHERE
			a.tipo_id_tercero = '" . $_REQUEST['tipo_id_tercero'] . "'
			and		a.tercero_id = '" . $_REQUEST['tercero_id'] . "'
			
			";

        // print_r($filtros);
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

    /*     * **********************************************************************************
     *
     * Funcion que consulta paises.
     *
     * *********************************************************************************** */

    function Buscar_Departamentos($tipo_pais_id) {
        $sql = "select * 
       from tipo_dptos
       where tipo_pais_id='" . $tipo_pais_id . "'    
       order by departamento";

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

    function Buscar_Municipio($tipo_pais_id, $tipo_dpto_id) {
        $sql = "select * 
       from tipo_mpios
       where tipo_pais_id='" . $tipo_pais_id . "'    
       and tipo_dpto_id='" . $tipo_dpto_id . "'
       order by municipio";

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

    function ListaGruposActividades() {
        $sql = "select * from actividades_industriales_grupos";

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

    function ListaActividades($id_grupo) {
        $sql = "select * from actividades_industriales
     where grupo_id='$id_grupo'";

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

    function Lista_TiposClientes() {
        $sql = "
	select
	tipo_cliente,
	descripcion
	from tipos_clientes
	where estado='1';";

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

    /**
     * Metodo para guardar un tecero
     *
     * @param string $tipo_identificacion 
     * @param string $id_tercero 
     * @param string $nombre 
     * @param string $pais 
     * @param string $departamento 
     * @param string $municipio 
     * @param string $direccion 
     * @param string $telefono 
     * @param string $faz 
     * @param string $email 
     * @param string $celular 
     * @param string $perjur
     * @param string $dv
     * @return mensaje de conmfirmacion
     * @access public
     */
    function InsertarTercero($datos) {
        //$this->debug=true;
        //var_dump($datos);

        $sql = "INSERT INTO  terceros
			(
			tipo_id_tercero,
			tercero_id,
			tipo_pais_id,
			tipo_dpto_id,
			tipo_mpio_id,
			direccion,
			telefono,
			fax,
			email,
			celular,
			sw_persona_juridica,
			usuario_id,
			nombre_tercero,
			dv,
			empresa_id
			)
            VALUES
              (
                '" . $datos['tipo_id_tercero'] . "',
                '" . $datos['tercero_id'] . "',
                '" . $datos['tipo_pais_id'] . "',
                '" . $datos['tipo_dpto_id'] . "',
                '" . $datos['tipo_mpio_id'] . "',
                '" . $datos['direccion'] . "',
                '" . $datos['telefono'] . "',
                '" . $datos['fax'] . "',
                '" . $datos['email'] . "',
                '" . $datos['celular'] . "',
                '" . $datos['sw_persona_juridica'] . "',
                 " . UserGetUID() . ",
                '" . $datos['nombre_tercero'] . "',
                '" . $datos['dv'] . "',
                '" . $datos['empresa_id'] . "'
              );";

        if ($datos['tercero_cliente'] == '1')
            $sql .= $this->Operaciones_Cliente($datos['es_cliente'], $datos);

        if ($datos['tercero_proveedor'] == '1')
            $sql .= $this->Operaciones_Proveedor($datos['es_proveedor'], $datos);

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            //$this->MensajeError = "ERROR DB : " . $ConexionBaseDatos->ErrorMsg();
            return false;
        }
        $rst->Close();
        return true;
    }

    function ModificarTercero($datos) {

        /*echo "<pre>";
        print_r($datos);
        echo "</pre>";
        exit();*/

        //$this->debug=true;
        if ($datos['tercero_cliente'] == '1')
            $sql = $this->Operaciones_Cliente($datos['es_cliente'], $datos);

        if ($datos['tercero_proveedor'] == '1')
            $sql .= $this->Operaciones_Proveedor($datos['es_proveedor'], $datos);

        $sql .="UPDATE terceros
			SET
			tipo_id_tercero = '" . $datos['tipo_id_tercero'] . "',
			tercero_id = '" . $datos['tercero_id'] . "',
			tipo_pais_id = '" . $datos['tipo_pais_id'] . "',
			tipo_dpto_id =  '" . $datos['tipo_dpto_id'] . "',
			tipo_mpio_id = '" . $datos['tipo_mpio_id'] . "',
			direccion = '" . $datos['direccion'] . "',
			telefono = '" . $datos['telefono'] . "',
			fax = '" . $datos['fax'] . "',
			email = '" . $datos['email'] . "',
			celular = '" . $datos['celular'] . "',
			sw_persona_juridica = '" . $datos['sw_persona_juridica'] . "',
			usuario_id = " . UserGetUID() . ",
			nombre_tercero = '" . $datos['nombre_tercero'] . "',
			dv = '" . $datos['dv'] . "',
			empresa_id =  '" . $datos['empresa_id'] . "'
			";
        $sql .= "	WHERE ";
        $sql .= "	tipo_id_tercero = '" . $datos['tipo_id_tercero_old'] . "' ";
        $sql .= "	and tercero_id = '" . $datos['tercero_id_old'] . "'; ";


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $rst->Close();
        return true;
    }

    /*
     * Operaciones Cliente
     * Insertar o Modificar
     */

    function Operaciones_Cliente($EsCliente, $datos) {
        if ($datos['codigo_unidad_negocio'] == "")
            $codigo_unidad_negocio = "NULL";
        else
            $codigo_unidad_negocio = "'" . $datos['codigo_unidad_negocio'] . "'";

        if ($datos['tipo_cliente'] == "")
            $tipo_cliente = "NULL";
        else
            $tipo_cliente = "'" . $datos['tipo_cliente'] . "'";

        if ($EsCliente == '1') {


            $sql .= "UPDATE terceros_clientes ";
            $sql .= " SET ";
            $sql .= "   empresa_id = '" . $datos['empresa_id'] . "', ";
            /* $sql .= "   tipo_id_tercero = '".$datos['tipo_id_tercero']."',";
              $sql .= "   tercero_id = '".$datos['tercero_id']."', "; */
            $sql .= "   sw_gran_contribuyente = '" . $datos['sw_gran_contribuyente'] . "', ";
            $sql .= "   sw_regimen_comun = '" . $datos['sw_regimen_comun'] . "', ";
            $sql .= "   sw_reteiva = '" . $datos['sw_reteiva'] . "', ";
            $sql .= "   porcentaje_reteiva = " . $datos['porcentaje_reteiva'] . ", ";
            $sql .= "   sw_ica = '" . $datos['sw_ica'] . "', ";
            $sql .= "   porcentaje_ica = " . $datos['porcentaje_ica'] . ", ";

            $sql .= "   sw_cree = '" . $datos['sw_cree'] . "', ";
            $sql .= "   porcentaje_cree = " . $datos['porcentaje_cree'] . ", ";

            $sql .= "   sw_rtf = '" . $datos['sw_rtf'] . "', ";
            $sql .= "   porcentaje_rtf = " . $datos['porcentaje_rtf'] . ", ";
            $sql .= "   observacion = '" . $datos['observacion'] . "', ";
            $sql .= "   codigo_unidad_negocio = " . $codigo_unidad_negocio . ", ";
            $sql .= "   tipo_cliente = " . $tipo_cliente . " , ";
            $sql .= "   cuenta_contable = " . $datos['cuenta_contable_cliente'] . " ";
            $sql .= "	WHERE ";
            $sql .= "	tipo_id_tercero = '" . $datos['tipo_id_tercero_old'] . "' ";
            $sql .= "	and tercero_id = '" . $datos['tercero_id_old'] . "'; ";
        } else {
            
            
            $cta_contable = $datos['cuenta_contable_cliente'];            
            if(empty($datos['cuenta_contable_cliente']))
                $cta_contable = 'NULL';
            
            $sql .= "INSERT INTO terceros_clientes ";
            $sql .= " (";
            $sql .= "   empresa_id,";
            $sql .= "   tipo_id_tercero,";
            $sql .= "   tercero_id, ";
            $sql .= "   sw_gran_contribuyente, ";
            $sql .= "   sw_regimen_comun, ";
            $sql .= "   sw_reteiva, ";
            $sql .= "   porcentaje_reteiva, ";
            $sql .= "   sw_ica, ";
            $sql .= "   porcentaje_ica, ";
            $sql .= "   sw_cree, ";
            $sql .= "   porcentaje_cree, ";
            $sql .= "   sw_rtf, ";
            $sql .= "   porcentaje_rtf, ";
            $sql .= "   observacion, ";
            $sql .= "   codigo_unidad_negocio, ";
            $sql .= "   tipo_cliente, ";
            $sql .= "   cuenta_contable ";
            $sql .= " )";
            $sql .= "VALUES";
            $sql .= " (";
            $sql .= "   '" . $datos['empresa_id'] . "',";
            $sql .= "   '" . $datos['tipo_id_tercero'] . "',";
            $sql .= "   '" . $datos['tercero_id'] . "', ";
            $sql .= "   '" . $datos['sw_gran_contribuyente'] . "', ";
            $sql .= "   '" . $datos['sw_regimen_comun'] . "', ";
            $sql .= "   '" . $datos['sw_reteiva'] . "', ";
            $sql .= "   '" . $datos['porcentaje_reteiva'] . "', ";
            $sql .= "   '" . $datos['sw_ica'] . "', ";
            $sql .= "   '" . $datos['porcentaje_ica'] . "', ";
            $sql .= "   '" . $datos['sw_cree'] . "', ";
            $sql .= "   '" . $datos['porcentaje_cree'] . "', ";
            $sql .= "   '" . $datos['sw_rtf'] . "', ";
            $sql .= "   '" . $datos['porcentaje_rtf'] . "', ";
            $sql .= "   '" . $datos['observacion'] . "', ";
            $sql .= "   " . $codigo_unidad_negocio . ", ";
            $sql .= "   " . $tipo_cliente . ", ";
            $sql .= "   " . $cta_contable . " ";
            $sql .= " );";
        }
        return $sql;
    }

    /*
     * Operaciones Proveedor
     * Insertar/Modificar
     */

    function Operaciones_Proveedor($EsProveedor, $datos) {
        $sw_pago_abono_cta = '0';
        $sw_pago_cheque = '0';
        $sw_pago_efectivo = '0';

        if ($datos['sw_pago_abono_cta'] == '1')
            $sw_pago_abono_cta = '1';

        if ($datos['sw_pago_cheque'] == '1')
            $sw_pago_cheque = '1';

        if ($datos['sw_pago_efectivo'] == '1')
            $sw_pago_efectivo = '1';
        if ($EsProveedor == '1') {


            $sql .= "UPDATE terceros_proveedores ";
            $sql .= " SET ";
            $sql .= "   empresa_id = '" . $datos['empresa_id'] . "', ";
            /* $sql .= "   tipo_id_tercero = '".$datos['tipo_id_tercero']."',";
              $sql .= "   tercero_id = '".$datos['tercero_id']."', "; */
            $sql .= "   representante_ventas = '" . $datos['representante_ventas'] . "', ";
            $sql .= "   telefono_representante_ventas = '" . $datos['telefono_representante_ventas'] . "', ";
            $sql .= "   nombre_gerente = '" . $datos['nombre_gerente'] . "', ";
            $sql .= "   telefono_gerente = '" . $datos['telefono_gerente'] . "', ";
            $sql .= "   dias_gracia = '" . $datos['dias_gracia'] . "', ";
            $sql .= "   dias_credito = '" . $datos['dias_credito'] . "', ";
            $sql .= "   tiempo_entrega = '" . $datos['tiempo_entrega'] . "', ";
            $sql .= "   descuento_por_contado = '" . $datos['descuento_por_contado'] . "', ";
            $sql .= "   actividad_id = '" . $datos['actividad_id'] . "', ";
            $sql .= "   sw_gran_contribuyente = '" . $datos['prov_sw_gran_contribuyente'] . "', ";
            $sql .= "   sw_regimen_comun = '" . $datos['prov_sw_regimen_comun'] . "', ";
            $sql .= "   sw_reteiva = '" . $datos['prov_sw_reteiva'] . "', ";
            $sql .= "   porcentaje_reteiva = " . $datos['prov_porcentaje_reteiva'] . ", ";
            $sql .= "   sw_ica = '" . $datos['prov_sw_ica'] . "', ";
            $sql .= "   porcentaje_ica = " . $datos['prov_porcentaje_ica'] . ", ";
            $sql .= "   sw_rtf = '" . $datos['prov_sw_rtf'] . "', ";
            $sql .= "   porcentaje_rtf = " . $datos['prov_porcentaje_rtf'] . ", ";
            $sql .= "   sw_pago_abono_cta = '" . $sw_pago_abono_cta . "', ";
            $sql .= "   sw_pago_cheque = '" . $sw_pago_cheque . "', ";
            $sql .= "   sw_pago_efectivo = '" . $sw_pago_efectivo . "', ";

            $sql .= "   sw_cree = '" . $datos['prov_sw_cree'] . "', ";
            $sql .= "   porcentaje_cree = '" . $datos['prov_porcentaje_cree'] . "' ";
            //$sql .= "   observacion = '".$datos['observacion']."', ";
            //$sql .= "   codigo_unidad_negocio = ".$codigo_unidad_negocio." ";
            $sql .= "	WHERE ";
            $sql .= "	tipo_id_tercero = '" . $datos['tipo_id_tercero_old'] . "' ";
            $sql .= "	and tercero_id = '" . $datos['tercero_id_old'] . "'; ";
        } else {

            $sql .= "INSERT INTO terceros_proveedores ";
            $sql .= " (";
            $sql .= "   empresa_id,";
            $sql .= "   tipo_id_tercero,";
            $sql .= "   tercero_id, ";
            $sql .= "   sw_gran_contribuyente, ";
            $sql .= "   sw_regimen_comun, ";
            $sql .= "   sw_reteiva, ";
            $sql .= "   porcentaje_reteiva, ";
            $sql .= "   sw_ica, ";
            $sql .= "   porcentaje_ica, ";
            $sql .= "   sw_rtf, ";
            $sql .= "   porcentaje_rtf, ";
            $sql .= "   representante_ventas, ";
            $sql .= "   telefono_representante_ventas, ";
            $sql .= "   nombre_gerente, ";
            $sql .= "   telefono_gerente, ";
            $sql .= "   dias_gracia, ";
            $sql .= "   dias_credito, ";
            $sql .= "   tiempo_entrega, ";
            $sql .= "   descuento_por_contado, ";
            $sql .= "   actividad_id, ";
            $sql .= "   sw_pago_abono_cta, ";
            $sql .= "   sw_pago_cheque, ";
            $sql .= "   sw_pago_efectivo,";
            $sql .= "   sw_cree, ";
            $sql .= "   porcentaje_cree ";
            $sql .= " )";
            $sql .= "VALUES";
            $sql .= " (";
            $sql .= "   '" . $datos['empresa_id'] . "',";
            $sql .= "   '" . $datos['tipo_id_tercero'] . "',";
            $sql .= "   '" . $datos['tercero_id'] . "', ";
            $sql .= "   '" . $datos['prov_sw_gran_contribuyente'] . "', ";
            $sql .= "   '" . $datos['prov_sw_regimen_comun'] . "', ";
            $sql .= "   '" . $datos['prov_sw_reteiva'] . "', ";
            $sql .= "   '" . $datos['prov_porcentaje_reteiva'] . "', ";
            $sql .= "   '" . $datos['prov_sw_ica'] . "', ";
            $sql .= "   '" . $datos['prov_porcentaje_ica'] . "', ";
            $sql .= "   '" . $datos['prov_sw_rtf'] . "', ";
            $sql .= "   '" . $datos['prov_porcentaje_rtf'] . "', ";
            $sql .= "   '" . $datos['representante_ventas'] . "', ";
            $sql .= "   '" . $datos['telefono_representante_ventas'] . "', ";
            $sql .= "   '" . $datos['nombre_gerente'] . "', ";
            $sql .= "   '" . $datos['telefono_gerente'] . "', ";
            $sql .= "   '" . $datos['dias_gracia'] . "', ";
            $sql .= "   '" . $datos['dias_credito'] . "', ";
            $sql .= "   '" . $datos['tiempo_entrega'] . "', ";
            $sql .= "   '" . $datos['descuento_por_contado'] . "', ";
            $sql .= "   '" . $datos['actividad_id'] . "', ";
            $sql .= "   '" . $sw_pago_abono_cta . "', ";
            $sql .= "   '" . $sw_pago_cheque . "', ";
            $sql .= "   '" . $sw_pago_efectivo . "', ";
            $sql .= "   '" . $datos['prov_sw_cree'] . "', ";
            $sql .= "   '" . $datos['prov_porcentaje_cree'] . "'";
            $sql .= " );";
        }
        return $sql;
    }

    /*     * **********************************************************************************
     *
     * Funcion que consulta paises.
     *
     * *********************************************************************************** */

    function UnidadesNegocio() {
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

    function ListarBancos($tipo_id_tercero, $tercero_id) {
        $sql = "
	select 
	b.banco,b.descripcion
	from 
	bancos b
	where
	b.banco Not In 
               (select 
                      banco 
                      from 
                      terceros_bancos
                      where
                      tipo_id_tercero = '" . $tipo_id_tercero . "' 
                      AND tercero_id = '" . $tercero_id . "')
	order by descripcion;
	";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array(); //Definiendo que va a ser un arreglo.

        while (!$rst->EOF) { //Recorriendo el Vector;
            $datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function ListarTiposCuentas() {
        $sql = "
	select 
	tipo_de_cuenta_id,
	descripcion
	from
	terceros_tipos_cuentas
	order by descripcion;";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array(); //Definiendo que va a ser un arreglo.

        while (!$rst->EOF) { //Recorriendo el Vector;
            $datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function ListarBancosTercero($tipo_id_tercero, $tercero_id) {
        /* $this->debug=true; */
        $sql = "
	select 
	a.banco,
	a.descripcion,
	b.tipo_id_tercero,
	b.tercero_id
	from 
	bancos a
	JOIN terceros_bancos as b ON (a.banco = b.banco)
	where
	b.tipo_id_tercero = '" . $tipo_id_tercero . "' 
    AND b.tercero_id = '" . $tercero_id . "'
	order by descripcion;
	";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array(); //Definiendo que va a ser un arreglo.

        while (!$rst->EOF) { //Recorriendo el Vector;
            $datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function ListarBancosCuentas($tipo_id_tercero, $tercero_id) {
        /* $this->debug=true; */
        $sql = "
	select 
	a.banco,
	a.tipo_id_tercero,
	a.tercero_id,
	a.numero_cuenta,
	a.tipo_de_cuenta_id,
	b.descripcion
	from 
	terceros_bancos_cuentas as a
	JOIN terceros_tipos_cuentas as b ON (a.tipo_de_cuenta_id = b.tipo_de_cuenta_id)
	where
	a.tipo_id_tercero = '" . $tipo_id_tercero . "' 
    AND a.tercero_id = '" . $tercero_id . "'
	order by a.banco;";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array(); //Definiendo que va a ser un arreglo.

        while (!$rst->EOF) { //Recorriendo el Vector;
            $datos[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function InsertarTerceroBanco($datos) {
        $sql = "INSERT INTO terceros_bancos (";
        $sql .= "       tipo_id_tercero     , ";
        $sql .= "       tercero_id     , ";
        $sql .= "       banco,     ";
        $sql .= "       usuario_id     ";
        $sql .= " ) ";
        $sql .= "VALUES (";
        $sql .= "        '" . $datos['tipo_id_tercero'] . "',";
        $sql .= "        '" . $datos['tercero_id'] . "',";
        $sql .= "        '" . $datos['banco'] . "',";
        $sql .= "        " . UserGetUID() . "";
        $sql .= "       ); ";

        /* print_r($sql); */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;


        $rst->Close();
        //return $sql;
    }

    function InsertarTerceroBancoCuenta($tercero, $numero_cuenta, $banco, $tipo_de_cuenta_id) {
        $sql = "INSERT INTO terceros_bancos_cuentas (";
        $sql .= "       tipo_id_tercero     , ";
        $sql .= "       tercero_id     , ";
        $sql .= "       banco,     ";
        $sql .= "       numero_cuenta,     ";
        $sql .= "       tipo_de_cuenta_id,     ";
        $sql .= "       usuario_id     ";
        $sql .= " ) ";
        $sql .= "VALUES (";
        $sql .= "        '" . trim($tercero['tipo_id_tercero']) . "',";
        $sql .= "        '" . trim($tercero['tercero_id']) . "',";
        $sql .= "        '" . trim($banco) . "',";
        $sql .= "        '" . trim($numero_cuenta) . "',";
        $sql .= "        '" . trim($tipo_de_cuenta_id) . "',";
        $sql .= "        " . UserGetUID() . "";
        $sql .= "       ); ";

        /* print_r($sql); */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;


        $rst->Close();
        //return $sql;
    }

    function Borrar_TerceroBanco($datos) {
        $sql = "
	DELETE FROM terceros_bancos
	WHERE
			tipo_id_tercero = '" . $datos['tipo_id_tercero'] . "'
			AND tercero_id = '" . $datos['tercero_id'] . "'
			AND banco = '" . $datos['banco'] . "';";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        return true;
    }

    function Borrar_TerceroBancoCuenta($datos) {
        $sql = "
	DELETE FROM terceros_bancos_cuentas
	WHERE
			tipo_id_tercero = '" . $datos['tipo_id_tercero'] . "'
			AND tercero_id = '" . $datos['tercero_id'] . "'
			AND banco = '" . $datos['banco'] . "'
			AND numero_cuenta = '" . $datos['numero_cuenta'] . "';";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        return true;
    }

    /*
     * Listar Bancos por Proveedor.
     *
     */

    function Lista_TiposBloqueos() {
        $sql = "select 
		tipo_bloqueo_id,
		descripcion
		from 
		inv_tipos_bloqueos
		WHERE
		estado = '1';";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array(); //Definiendo que va a ser un arreglo.
        while (!$rst->EOF) { //Recorriendo el Vector;
            $datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Listar Bancos por Proveedor.
     *
     */

    function NuevoEstadoTercero($tipo_id_tercero, $tercero_id, $nuevo_tipo_bloqueo_id) {
        $sql = " UPDATE terceros ";
        $sql.="	SET ";
        $sql.="	 tipo_bloqueo_id = '" . $nuevo_tipo_bloqueo_id . "' ";
        $sql.="	 WHERE ";
        $sql.="	 tipo_id_tercero = '" . $tipo_id_tercero . "' ";
        $sql.="	 AND tercero_id = '" . $tercero_id . "'; ";
        $sql.=" UPDATE terceros_proveedores ";
        $sql.="	SET ";
        $sql.="	 estado = '" . $nuevo_tipo_bloqueo_id . "' ";
        $sql.="	 WHERE ";
        $sql.="	 tipo_id_tercero = '" . $tipo_id_tercero . "' ";
        $sql.="	 AND tercero_id = '" . $tercero_id . "'; ";


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $rst->Close();
        return true;
    }

    function InsertarBancoProveedor_SQL($formulario) {

        $ProveedorBancoId = $formulario['codigo_proveedor_id'] . "" . $formulario['banco']; //Concateno para crear el Codigo de proveedor banco


        $sql1 = "insert into terceros_proveedores_bancos
               values(" . $formulario['codigo_proveedor_id'] . ",'" . $formulario['banco'] . "','" . $ProveedorBancoId . "');";

        $sql2 = "insert into terceros_proveedores_bancos_cuentas (banco,proveedor_banco_id,numero_cuenta,estado,tipo_de_cuenta_id,codigo_proveedor_id)																	
               values('" . $formulario['banco'] . "','" . $ProveedorBancoId . "','" . $formulario['numero_cuenta'] . "','1','" . $formulario['tipo_de_cuenta'] . "'," . $formulario['codigo_proveedor_id'] . ");";

        $rst1 = $this->ConexionBaseDatos($sql1);
        $rst2 = $this->ConexionBaseDatos($sql2);

        //print_r($sql2);
    }

    /*
     * Listar Bancos por Proveedor.
     *
     */

    function ListarBancosProveedorCuentas($ProveedorBanco) {

        $sql = "
	select 
	cp.estado,
	cp.numero_cuenta,
    tc.descripcion
	from 
	terceros_proveedores_bancos_cuentas cp,
	terceros_proveedores_bancos_tipos_cuentas tc
	where
	cp.proveedor_banco_id ='" . $ProveedorBanco . "'
	AND
	cp.tipo_de_cuenta_id = tc.tipo_de_cuenta_id;
	";


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array(); //Definiendo que va a ser un arreglo.

        while (!$rst->EOF) { //Recorriendo el Vector;
            $datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
            $rst->MoveNext();
        }
        $rst->Close();



        return $datos;
    }

    function ListarBancosProveedor($CodigoProveedor, $offset) {

        $sql = "
	select 
	b.banco,b.descripcion,bp.proveedor_banco_id
	from 
	bancos b, terceros_proveedores_bancos bp
	where
	bp.codigo_proveedor_id =" . $CodigoProveedor . "
	AND
	bp.banco = b.banco
	 
	";

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A ", null, $offset))
            return false;


        $sql .= " order by (b.banco) ";
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

    function ListarBancos_P($CodigoProveedor) {
        $sql = "
	select 
	b.banco,b.descripcion
	from 
	bancos b,terceros_proveedores_bancos pb
	where
        pb.codigo_proveedor_id=" . $CodigoProveedor . "
        And
	b.banco Not In 
               (select 
                      banco 
                      from 
                      terceros_proveedores_bancos
                      where
                      codigo_proveedor_id = " . $CodigoProveedor . ");
	";


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array(); //Definiendo que va a ser un arreglo.

        while (!$rst->EOF) { //Recorriendo el Vector;
            $datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
            $rst->MoveNext();
        }
        $rst->Close();



        return $datos;
    }

    function sw_proveedor($estado, $primary) {

        $sql = "Update terceros_proveedores 
        SET estado='" . $estado . "'
        WHERE codigo_proveedor_id='" . $primary . "'";

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            $cad = "0";
            //return $sql;
            return $cad;
        } else {
            $cad = "1";
            $rst->Close();
            //return $sql;
            return $cad;
        }
    }

    function SacarGrupo($actividad) {
        $sql = "select 
          grupo_id 
         from 
          actividades_industriales
         where 
          actividad_id='$actividad'";

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

    function Nombres($tipo_id, $tercero_id) {
        $sql = " select *
        from terceros
        where tercero_id='" . trim($tercero_id) . "'
        and tipo_id_tercero='" . $tipo_id . "'";

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

    /*     * **********************************************************************************
     *
     * Funcion que consulta paises.
     *
     * *********************************************************************************** */

    function Paises() {
        $sql = " select * from tipo_pais order by pais";

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

    /*     * ********************************************************************************
     * Funcion que inserta  departamentos, 
     * 
     * @return vector
     * ********************************************************************************* */

    function GXD($id_pais, $departamentox) {

        $sql = "select max(tipo_dpto_id) from tipo_dptos
      where tipo_pais_id='" . $id_pais . "'";
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;
        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        if (!empty($documentos)) {
            $codigo_dep = $documentos[0]['max'] + 1;
        } else {
            $codigo_dep = 1;
        }
        $sql = "insert into tipo_dptos values('" . $codigo_dep . "','" . $id_pais . "','" . strtoupper($departamentox) . "');";
        if (!$rst = $this->ConexionBaseDatos($sql)) {
            $cad = "no se hizo la inserci�";
            //return $cad;
            return $sql;
        } else {
            $cad = $codigo_dep;
            $rst->Close();
            return $cad;
        }
    }

    /*     * ********************************************************************************
     * Funcion que inserta  municipios, 
     * 
     * @return vector
     * ********************************************************************************* */

    function GXM($id_pais, $id_dept, $Municipio) {

        $sql = "select max(tipo_mpio_id) from tipo_mpios
      where tipo_pais_id='" . $id_pais . "' and  tipo_dpto_id='" . $id_dept . "'";
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;
        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        if (!empty($documentos)) {
            $codigo_mun = $documentos[0]['max'] + 1;
        } else {
            $codigo_mun = 1;
        }
        $sql = "insert into tipo_mpios values('" . $id_pais . "','" . $id_dept . "','" . $codigo_mun . "','" . strtoupper($Municipio) . "');";
        if (!$rst = $this->ConexionBaseDatos($sql)) {
            $cad = "no se hizo la inserci�";
            //return $cad;
            return $sql;
        } else {
            $cad = $codigo_mun;
            $rst->Close();
            return $cad;
        }
    }

    function Consultadpto($departamentox) {
        $sql1 = "select tipo_dpto_id from tipo_dptos
   where departamento='" . strtoupper($departamentox) . "'";
        if (!$resultado = $this->ConexionBaseDatos($sql1)) {
            return false;
        } else {
            $deptos = array();
            while (!$resultado->EOF) {
                $deptos[] = $resultado->GetRowAssoc($ToUpper = false);
                $resultado->MoveNext();
            }

            $resultado->Close();
            //return $sql1;
            return $deptos;
        }
    }

    function Consultampio($pais, $depto, $Municipio) {
        $sql1 = "select tipo_mpio_id from tipo_mpios
           where 
            tipo_pais_id='" . $pais . "' AND
            tipo_dpto_id='" . $depto . "' AND
            municipio='" . strtoupper($Municipio) . "'";
        if (!$resultado = $this->ConexionBaseDatos($sql1)) {
            //return $sql1;
            return false;
        } else {
            $munis = array();
            while (!$resultado->EOF) {
                $munis[] = $resultado->GetRowAssoc($ToUpper = false);
                $resultado->MoveNext();
            }

            $resultado->Close();
            //return $sql1;
            return $munis;
        }
    }

    function Perdidas() {


        $sql = "  select
          tipo_perdida_id,
          descripcion
          from
          inv_bodegas_tipos_perdidas
          where
          sw_estado='1'";

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
     * Metodo para guardar un tecero
     *
     * @param string $tipo_identificacion 
     * @param string $id_tercero 
     * @param string $nombre 
     * @param string $pais 
     * @param string $departamento 
     * @param string $municipio 
     * @param string $direccion 
     * @param string $telefono 
     * @param string $faz 
     * @param string $email 
     * @param string $celular 
     * @param string $perjur
     * @param string $dv
     * @return mensaje de conmfirmacion
     * @access public
     */
    function GuardarPersonas($tipo_identificacion, $id_tercero, $nombre, $pais, $departamento, $municipio, $direccion, $telefono, $faz, $email, $celular, $perjur, $dv, $tercero_cliente) {
        //var_dump($direccion);
        if ($direccion == "") {
            $direccion = "NULL";
        } else {
            $direccion = "'" . $direccion . "'";
        }
        if ($telefono == 0) {
            $telefono = "NULL";
        } else {
            $telefono = "'" . $telefono . "'";
        }
        if ($faz == NULL) {
            $faz = "NULL";
        } else {
            $faz = "'" . $faz . "'";
        }
        if ($email == NULL) {
            $email = "NULL";
        } else {
            $email = "'" . $email . "'";
        }
        if ($celular == NULL) {
            $celular = "NULL";
        } else {
            $celular = "'" . $celular . "'";
        }

        if ($dv == "") {
            $dv = "NULL";
        } else {
            $dv = "'" . $dv . "'";
        }

        $datos = SessionGetVar("EMPRESAS");
        $sql = "insert into  terceros
            values
              (
                '" . $tipo_identificacion . "',
                '" . $id_tercero . "',
                '" . $pais . "',
                '" . $departamento . "',
                '" . $municipio . "',
                 " . $direccion . ",
                 " . $telefono . ",
                 " . $faz . ",
                 " . $email . ",
                 " . $celular . ",
                '" . $perjur . "',
                '0',
                 " . UserGetUID() . ",
                 now(),
                 NULL,
                '" . $nombre . "',
                " . $dv . ",
                '" . $datos['empresa_id'] . "',
                NULL
              );";
        if ($tercero_cliente) {
            $sql .= "INSERT INTO terceros_clientes ";
            $sql .= " (";
            $sql .= "   empresa_id,";
            $sql .= "   tipo_id_tercero,";
            $sql .= "   tercero_id ";
            $sql .= " )";
            $sql .= "VALUES";
            $sql .= " (";
            $sql .= "   '" . $datos['empresa_id'] . "',";
            $sql .= "   '" . $tipo_identificacion . "',";
            $sql .= "   '" . $id_tercero . "'";
            $sql .= " );";
        }

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            $cad = $this->frmError['MensajeError'];
            $cad.=$sql . "HAY PROBLEMAS CON LA INSERCION ES POSIBLE QUE ESTE TERCERO YA EXISTE";
            return $cad;
        } else {
            //$cad=$sql;
            $cad = "EXITO";
            $rst->Close();
            return $cad;
        }
    }

    /**
     * Metodo constructor
     * @access public
     */
    function CrearSQL() {
        
    }

    /**
     * Metodo para obtener el nombre de un departamento del pais
     *
     * @param string $pais_id 
     * @param string $dpto_id 
     * @return array $depar con el nombre del departamento 
     * @access public
     */
    function sacar_depar($pais_id, $dpto_id) {
        $sql = "SELECT * 
             FROM 
             tipo_dptos 
             WHERE 
             tipo_pais_id='" . $pais_id . "'
             AND tipo_dpto_id='" . $dpto_id . "'";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $pais = Array();
        while (!$resultado->EOF) {
            $depar = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $depar;
    }

    /**
     * Metodo para obtener el nombre de un municipio del pais
     *
     * @param string $pais_id 
     * @param string $dpto_id 
     * @param string $mpio_id 
     * @return array $mpio con el nombre del municipio 
     * @access public
     */
    function sacar_mpio($pais_id, $dpto_id, $mpio_id) {
        $sql = "SELECT * 
             FROM 
             tipo_mpios
             WHERE 
             tipo_pais_id='" . $pais_id . "'
             AND tipo_dpto_id='" . $dpto_id . "'
             AND tipo_mpio_id='" . $mpio_id . "'";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $mpio = Array();
        while (!$resultado->EOF) {
            $mpio = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $mpio;
    }

    /**
     * Metodo para obtener el numero de registro para elabora el paginador de terceros
     *
     * @param string $tipo_de_busqueda (1:identificacion 2:nombre 0:ninguno)
     * @param string $tipo_de_busqueda_aux (si $tipo_de_busqueda==1 entonces esta variable trae el tipo de documento )
     * @param string $valor_de_busqueda (trae el valor a buscar)
     * @return array $contador numero de registros que contiene la conuslta
     * @access public
     */
    function ContarRegistros($tipo_de_busqueda, $tipo_de_busqueda_aux, $valor_de_busqueda) {

        if ($tipo_de_busqueda == 1 && $valor_de_busqueda != '') {
            $filtro = "WHERE tipo_id_tercero='" . $tipo_de_busqueda_aux . "' AND tercero_id ILIKE '" . $valor_de_busqueda . "%'";
        } elseif ($tipo_de_busqueda == 1 && $valor_de_busqueda == '') {
            $filtro = "WHERE tipo_id_tercero='" . $tipo_de_busqueda_aux . "'";
        } elseif ($tipo_de_busqueda == 2) {
            $filtro = "WHERE nombre_tercero ILIKE '%" . strtoupper($valor_de_busqueda) . "%'";
        } else {
            $filtro = "";
        }
        if ($tipo_de_busqueda == "0") {
            $sql1 = "SELECT count(*)
                FROM
                terceros";
        } elseif ($tipo_de_busqueda == '1') {
            $sql1 = "SELECT count(*)
                FROM
                terceros $filtro";
        } elseif ($tipo_de_busqueda == '2' && $valor_de_busqueda != '') {
            $sql1 = "SELECT count(*)
                FROM
                terceros $filtro";
        }
        // var_dump($sql1);
        if (!$resultado = $this->ConexionBaseDatos($sql1)) {
            return false;
        }

        $contador = Array();
        while (!$resultado->EOF) {
            $contador = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $contador;
    }

    /**
     * Metodo para obtener el numero de registro para elabora el paginador de proveedores
     *
     * @param string $tipo_de_busqueda (1:identificacion 2:nombre 0:ninguno)
     * @param string $tipo_de_busqueda_aux (si $tipo_de_busqueda==1 entonces esta variable trae el tipo de documento )
     * @param string $valor_de_busqueda (trae el valor a buscar)
     * @return array $contador numero de registros que contiene la conuslta
     * @access public
     */
    function ContarRegistrosProv($tipo_de_busqueda, $tipo_de_busqueda_aux, $valor_de_busqueda) {

        if ($tipo_de_busqueda == 1 && $valor_de_busqueda != '') {
            $filtro = "WHERE a.tipo_id_tercero='" . $tipo_de_busqueda_aux . "' AND a.tercero_id ILIKE '" . $valor_de_busqueda . "%'";
        } elseif ($tipo_de_busqueda == 1 && $valor_de_busqueda == '') {
            $filtro = "WHERE a.tipo_id_tercero='" . $tipo_de_busqueda_aux . "'";
        } elseif ($tipo_de_busqueda == 2 && $valor_de_busqueda != '') {
            $filtro = "WHERE a.nombre_tercero ILIKE '%" . strtoupper($valor_de_busqueda) . "%'";
        } elseif ($tipo_de_busqueda == 2 && $valor_de_busqueda == '') {
            $filtro = "";
        } elseif ($tipo_de_busqueda == 3 && $valor_de_busqueda != '') {
            $filtro = "WHERE a.nombre_tercero ILIKE '%" . strtoupper($valor_de_busqueda) . "%'";
        } elseif ($tipo_de_busqueda == 3 && $valor_de_busqueda == '') {
            $filtro = "";
        } else {
            $filtro = "";
        }
        if ($tipo_de_busqueda == "0") {
            $sql1 = "SELECT
                count(*)
                FROM
                terceros AS a
                RIGHT JOIN terceros_proveedores AS b
                ON (a.tipo_id_tercero=b.tipo_id_tercero
                AND a.tercero_id= b.tercero_id)
                $filtro";
        } elseif ($tipo_de_busqueda == '1') {
            $sql1 = "SELECT
                count(*)
                FROM
                terceros AS a
                RIGHT JOIN terceros_proveedores AS b
                ON (a.tipo_id_tercero=b.tipo_id_tercero
                AND a.tercero_id= b.tercero_id)
                 $filtro";
        } elseif ($tipo_de_busqueda == '2' && $valor_de_busqueda != '') {
            $sql1 = "SELECT
                count(*)
                FROM
                terceros AS a
                RIGHT JOIN terceros_proveedores AS b
                ON (a.tipo_id_tercero=b.tipo_id_tercero
                AND a.tercero_id= b.tercero_id)
                $filtro";
        } elseif ($tipo_de_busqueda == '2' && $valor_de_busqueda == '') {
            $sql1 = "SELECT
                count(*)
                FROM
                terceros AS a
                RIGHT JOIN terceros_proveedores AS b
                ON (a.tipo_id_tercero=b.tipo_id_tercero
                AND a.tercero_id= b.tercero_id)
                $filtro";
        } elseif ($tipo_de_busqueda == '3' && $valor_de_busqueda != '') {
            $sql1 = "SELECT
                count(*)
                FROM
                terceros AS a
                RIGHT JOIN terceros_proveedores AS b
                ON (a.tipo_id_tercero=b.tipo_id_tercero
                AND a.tercero_id= b.tercero_id)
                $filtro";
        } elseif ($tipo_de_busqueda == '3' && $valor_de_busqueda == '') {
            $sql1 = "SELECT
                count(*)
                FROM
                terceros AS a
                RIGHT JOIN terceros_proveedores AS b
                ON (a.tipo_id_tercero=b.tipo_id_tercero
                AND a.tercero_id= b.tercero_id)
                $filtro";
        }
        if (!$resultado = $this->ConexionBaseDatos($sql1)) {
            return false;
        }

        $contador = Array();
        while (!$resultado->EOF) {
            $contador = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $contador;
    }

    /*     * *****************************************************************************
     * LISTAR LOS TIPOS DE IDENTIFICACION
     * ******************************************************************************* */

    function Terceros_id() {
        $sql = " select * from tipo_id_terceros order by indice_de_orden";

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
     * Funcion que lista los tipos documentos segun el tipo de empresa.
     *
     * *********************************************************************************** */

    function ListarEmpresas() {

        $sql = " select 
	empresa_id,
	razon_social
	from empresas
	where sw_activa = '1'
	order by empresa_id ";


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

    /*     * *****************************************************************************
     * up estado centros de costo
     * ****************************************************************************** */

    function SwCent($empresa, $cent_id, $estado) {
        $sql = "Update cg_conf.centros_de_costo 
          SET sw_estado='" . $estado . "'
          where centro_de_costo_id='" . $cent_id . "' 
          and empresa_id='" . $empresa . "'";

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            $cad = "OPERACION INVALIDA";
            return $sql;
            return $cad;
        } else {
            $cad = "DOCUMENTO ACTUALIZADO SATISFACTORIAMENTE";
            $rst->Close();
            //return $sql;
            return $cad;
        }
    }

    /*     * **********************************************************************************
     *
     * Funcion que consulta lapsos contables.
     *
     * *********************************************************************************** */

    function ColocarEmpresa($empresa) {
        $sql = " select razon_social 
       from empresas
       where empresa_id = '" . strtoupper($empresa) . "'";


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

    /*     * ******************************************************************************
     * Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
     * importantes a la hora de referenciar al paginador
     * 
     * @param String Cadena que contiene la consulta sql del conteo 
     * @param int numero que define el limite de datos,cuando no se desa el del 
     * 			 usuario,si no se pasa se tomara por defecto el del usuario 
     * @return boolean 
     * ******************************************************************************* */

    function ProcesarSqlConteo($consulta, $limite = null, $offset = null) {
        $this->offset = 0;
        $this->paginaActual = 1;
        if ($limite == null) {
            $this->limit = GetLimitBrowser();
        } else {
            $this->limit = $limite;
        }

        if ($offset) {
            $this->paginaActual = intval($offset);
            if ($this->paginaActual > 1) {
                $this->offset = ($this->paginaActual - 1) * ($this->limit);
            }
        }

        if (!$result = $this->ConexionBaseDatos($consulta))
            return false;

        if (!$result->EOF) {
            $this->conteo = $result->fields[0];
            $result->MoveNext();
        }
        $result->Close();
        $this->pagina = $this->paginaActual;

        return true;
    }

    /*     * ********************************************************************************
     * Funcion que permite realizar la conexion a la base de datos y ejecutar la 
     * consulta sql 
     * 
     * @param 	string  $sql	sentencia sql a ejecutar $empresaid,$cuenta,$nivel,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_est,$sw_cc,$sw_dc
     * @return rst 
     * ********************************************************************************** */
    /* function ConexionBaseDatos($sql)
      {
      list($dbconn)=GetDBConn();
      //$dbconn->debug=true;
      $rst = $dbconn->Execute($sql);

      if ($dbconn->ErrorNo() != 0)
      {
      $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
      "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
      return false;
      }

      return $rst;
      } */
    /*     * ********************************************************************************
     * Funcion que permite crear una transaccion 
     * @param string $sql Sql a ejecutar- para dar inicio a la transaccion se pasa vacio
     * @param char $num Numero correspondiente a la sentecia sql - por defect es 1
     *
     * @return object Objeto de la transaccion - Al momento de iniciar la transaccion no 
     * 								 se devuelve nada
     * ********************************************************************************* */
}

?>