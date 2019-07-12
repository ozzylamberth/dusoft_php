<?php

/**
 * $Id: tipo_doc_bodega_I012.class.php,v 1.1 2009/07/27 20:47:55 johanna Exp $
 */

/**
 * Clase que implenta metodos de BodegasDocumentosComun
 *
 * Implementa metodos para documentos de bodega del tipo I012(EGRESO POR PRESTAMOS)
 *
 * @author Mauricio Medina -- mmedina@ipsoft-sa.com
 * @version $Revision: 1.1 $
 * @package SIIS
 */
class tipo_doc_bodega_I012 extends BodegasDocumentosComun {

    /**
     * Constructor
     *
     * @param integer $bodegas_doc_id
     * @return boolean True si se ejecuto correctamnte.
     * @access public
     */
    function tipo_doc_bodega_I012($bodegas_doc_id) {
        $this->BodegasDocumentosComun($bodegas_doc_id);
        return true;
    }

    /**
     * Metodo para crear un documento temporal.
     * @param string  $observacion observacion del documento a crear
     * @param string  $tipo_id_tercero Identificador del proveedor
     * @param string  $tercero_id Identificador del proveedor
     * @param integer $usuario_id integer (opcional)
     * @return integer (doc_tmp_id del documento creado)
     * @access public
     */
    function NewDocTemporal($observacion = '', $tipo_id_tercero, $tercero_id, $numero_factura, $empresa_id, $usuario_id = null) {
        if (empty($this->bodegas_doc_id)) {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CONSTRUCCION [bodegas_doc_id] ES REQUERIDO.";
            return false;
        }

        if (empty($tipo_id_tercero) || empty($tercero_id) || empty($numero_factura)) {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO NECERARIOS [tipo_id_tercero,tercero_id, numero_factura] SON REQUERIDOS.";
            return false;
        }

        list($dbconn) = GetDBconn();

        if (empty($usuario_id)) {
            $usuario_id = UserGetUID();
        }

        $sql = "SELECT (COALESCE(MAX(doc_tmp_id),0) + 1) FROM inv_bodegas_movimiento_tmp WHERE usuario_id = $usuario_id;";

        $result = $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }


        list($doc_tmp_id) = $result->FetchRow();
        $result->Close();

        $dbconn->BeginTrans();

        list($prefijo, $numero) = explode("@", $numero_factura);

        $sql = "INSERT INTO inv_bodegas_movimiento_tmp
                    (
                        usuario_id,
                        doc_tmp_id,
                        bodegas_doc_id,
                        observacion,
                        fecha_registro
                    )
                VALUES
                    (
                        $usuario_id,
                        $doc_tmp_id,
                        " . $this->bodegas_doc_id . ",
                        '" . substr(trim($observacion), 0, 255) . "',
                        NOW()
                    );";


        $sql .= " INSERT INTO inv_bodegas_movimiento_tmp_devolucion_cliente
                    (
                        usuario_id,
                        doc_tmp_id,
                        tipo_id_tercero,
                        tercero_id,
                        prefijo,
                        numero,
                        empresa_id
                    )
                VALUES
                    (
                        $usuario_id,
                        $doc_tmp_id,
                        '$tipo_id_tercero',
                        '$tercero_id',
                        '$prefijo',
                         $numero,
                         '$empresa_id'
                    );
        ";

        /* echo "<pre>";
          print_r($sql);
          echo "</pre>";
          exit(); */

        $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            $dbconn->RollbackTrans();
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg() . $sql;
            return false;
        }

        $dbconn->CommitTrans();

        return $this->GetDocTemporal($doc_tmp_id, $usuario_id);
    }

    /**
     * Metodo para obtener un documento temporal.
     *
     * @param $doc_tmp_id identificador del documento temporal
     * @param $usuario_id (opcional) identificador del creador del documento temporal
     * @return string
     * @access public
     */
    function GetDocTemporal($doc_tmp_id, $usuario_id = null) {
        if (empty($doc_tmp_id)) {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO [doc_tmp_id] ES REQUERIDO.";
            return false;
        }

        if (empty($usuario_id)) {
            $usuario_id = UserGetUID();
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "  SELECT  a.*,
                        b.tipo_id_tercero,
                        b.tercero_id,
                        b.prefijo,
                        b.numero,
                        c.documento_id,
                        c.empresa_id,
                        c.centro_utilidad,
                        c.bodega,
                        d.subtotal,
                        d.iva_total,
                        e.porcentaje_rtf,
                        e.porcentaje_ica,
                        e.porcentaje_reteiva
                FROM
                    inv_bodegas_movimiento_tmp as a
                    LEFT JOIN inv_bodegas_movimiento_tmp_devolucion_cliente as b
                    ON (b.usuario_id = a.usuario_id AND b.doc_tmp_id = a.doc_tmp_id)
                    JOIN inv_bodegas_documentos as c ON (c.bodegas_doc_id = a.bodegas_doc_id)
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
                    )as d ON (d.empresa_id= b.empresa_id) AND (d.prefijo = b.prefijo) AND (d.factura_fiscal = b.numero)
                    JOIN inv_facturas_despacho as e ON (e.empresa_id= b.empresa_id) AND (e.prefijo = b.prefijo) AND (e.factura_fiscal = b.numero)
                WHERE a.usuario_id = $usuario_id AND a.doc_tmp_id = $doc_tmp_id
        ";
        
        $sql = "
                SELECT  
                a.*,
                b.tipo_id_tercero,
                b.tercero_id,
                b.prefijo,
                b.numero,
                c.documento_id,
                c.empresa_id,
                c.centro_utilidad,
                c.bodega,
                d.subtotal,
                d.iva_total,
                e.porcentaje_rtf,
                e.porcentaje_ica,
                e.porcentaje_reteiva
                FROM
                inv_bodegas_movimiento_tmp as a
                LEFT JOIN inv_bodegas_movimiento_tmp_devolucion_cliente as b ON (b.usuario_id = a.usuario_id AND b.doc_tmp_id = a.doc_tmp_id)
                JOIN inv_bodegas_documentos as c ON (c.bodegas_doc_id = a.bodegas_doc_id)
                JOIN (
                  SELECT
                  a.empresa_id,
                  a.prefijo,
                  a.factura_fiscal,
                  SUM((a.valor_unitario*a.cantidad)) as subtotal,
                  SUM(((a.valor_unitario*a.cantidad)*(a.porc_iva/100))) as iva_total
                  FROM (
                      SELECT empresa_id, prefijo, factura_fiscal, valor_unitario, cantidad, porc_iva,0 FROM inv_facturas_despacho_d 
                      UNION
                      SELECT empresa_id, prefijo, factura_fiscal, valor_unitario, cantidad, porc_iva,1 FROM inv_facturas_agrupadas_despacho_d
                  ) AS a
                  group by a.empresa_id,a.prefijo,a.factura_fiscal
                )as d ON (d.empresa_id= b.empresa_id) AND (d.prefijo = b.prefijo) AND (d.factura_fiscal = b.numero)
                JOIN (
                  select empresa_id, prefijo, factura_fiscal, porcentaje_rtf, porcentaje_ica, porcentaje_reteiva, 0 from inv_facturas_despacho
                  union 
                  select empresa_id, prefijo, factura_fiscal, porcentaje_rtf, porcentaje_ica, porcentaje_reteiva, 1 from inv_facturas_agrupadas_despacho
                ) AS e ON (e.empresa_id= b.empresa_id) AND (e.prefijo = b.prefijo) AND (e.factura_fiscal = b.numero)
                WHERE a.usuario_id = {$usuario_id} AND a.doc_tmp_id = {$doc_tmp_id}     
        ";

        /*echo "<pre>";
        print_r($sql);
        echo "</pre>";
        exit();*/

        //$dbconn->debug=true;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if ($result->EOF) {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "EL DOCUMENTO TEMPORAL [$doc_tmp_id] DEL USUARIO [$usuario_id] NO EXISTE.";
            return false;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        return $retorno;
    }

    /**
     * Metodo para crear un documento de bodega a partir de un documento temporal.
     *
     * @param integer $doc_tmp_id identificador del documento temporal
     * @param integer $usuario_id (opcional) identificador del creador del documento temporal
     * @return array cabecera del documento creado.
     * @access public
     */
    function CrearDocumento($doc_tmp_id, $usuario_id = null) {
        if (empty($doc_tmp_id)) {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO [doc_tmp_id] ES REQUERIDO.";
            return false;
        }

        if (empty($usuario_id)) {
            $usuario_id = UserGetUID();
        }

        $DATOS = $this->GetDocTemporal($doc_tmp_id, $usuario_id);

        if ($DATOS == false)
            return false;

        if (empty($DATOS['tipo_id_tercero']) || empty($DATOS['tercero_id']) || empty($DATOS['prefijo']) || empty($DATOS['numero'])) {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "DATOS ADICIONALES DEL DOCUMENTO NO ESTAN LLENOS..";
            return false;
        }


        $sql = "INSERT INTO inv_bodegas_movimiento_devolucion_cliente
                (
                    empresa_id,
                    prefijo,
                    numero,
                    tipo_id_tercero,
                    tercero_id,
                    prefijo_doc_cliente,
                    numero_doc_cliente
                )
                VALUES
                (
				'%empresa_id%',
				'%prefijo%',
				%numero%,
				'" . $DATOS['tipo_id_tercero'] . "',
				'" . $DATOS['tercero_id'] . "',
				'" . $DATOS['prefijo'] . "',
				'" . $DATOS['numero'] . "'
                );";

        //print_r($sql);
        //return false;
        return $this->Exec_CrearDocumento($doc_tmp_id, $sql, $usuario_id);
    }

}

?>