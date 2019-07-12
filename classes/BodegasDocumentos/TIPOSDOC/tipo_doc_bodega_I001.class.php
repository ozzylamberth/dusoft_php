<?php
/**
* $Id: tipo_doc_bodega_I001.class.php,v 1.1.1.1 2010/08/25 22:28:45 hugo Exp $
*/

/**
* Clase que implenta metodos de BodegasDocumentosComun
*
* Implementa metodos para documentos de bodega del tipo I001(Compras directas)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.1.1.1 $
* @package SIIS
*/
class tipo_doc_bodega_I001 extends BodegasDocumentosComun
{
    /**
    * Constructor
    *
    * @param integer $bodegas_doc_id
    * @return boolean True si se ejecuto correctamnte.
    * @access public
    */
    function tipo_doc_bodega_I001($bodegas_doc_id)
    {
        $this->BodegasDocumentosComun($bodegas_doc_id);
        return true;
    }


    /**
    * Metodo para crear un documento temporal.
    * @param string  $observacion observacion del documento a crear
    * @param string  $tipo_id_tercero Identificador del proveedor
    * @param string  $tercero_id Identificador del proveedor
    * @param string  $documento_compra identificador del documento de compra
    * @param date    $fecha_doc_compra  fecha del documento de compra
    * @param integer $usuario_id integer (opcional)
    * @return integer (doc_tmp_id del documento creado)
    * @access public
    */
    function NewDocTemporal($observacion='', $tipo_id_tercero, $tercero_id, $documento_compra, $fecha_doc_compra, $usuario_id=null)
    {
        if(empty($this->bodegas_doc_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CONSTRUCCION [bodegas_doc_id] ES REQUERIDO.";
            return false;
        }

        if(empty($tipo_id_tercero) || empty($tercero_id) || empty($documento_compra) || empty($fecha_doc_compra))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO NECERARIOS [tipo_id_tercero, tercero_id, documento_compra, fecha_doc_compra] PARA EL TIPO DE DOCUMENTO [bodegas_doc_id] SON REQUERIDOS.";
            return false;
        }

        list($dbconn) = GetDBconn();

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        $sql = "SELECT (COALESCE(MAX(doc_tmp_id),0) + 1) FROM inv_bodegas_movimiento_tmp WHERE usuario_id = $usuario_id;";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }


        list($doc_tmp_id)=$result->FetchRow();
        $result->Close();

        $dbconn->BeginTrans();

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
                        ".$this->bodegas_doc_id.",
                        '".substr(trim($observacion), 0, 255)."',
                        NOW()
                    );

                INSERT INTO inv_bodegas_movimiento_tmp_compras_directas
                    (
                        usuario_id,
                        doc_tmp_id,
                        tipo_id_tercero,
                        tercero_id,
                        documento_compra,
                        fecha_doc_compra
                    )
                VALUES
                    (
                        $usuario_id,
                        $doc_tmp_id,
                        '$tipo_id_tercero',
                        '$tercero_id',
                        '$documento_compra',
                        '$fecha_doc_compra'
                    );
        ";

        $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg() . $sql ;
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
    function GetDocTemporal($doc_tmp_id, $usuario_id=null)
    {
        if(empty($doc_tmp_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO [doc_tmp_id] ES REQUERIDO.";
            return false;
        }

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql="  SELECT  a.*,
                        b.tipo_id_tercero,
                        b.tercero_id,
                        b.documento_compra,
                        b.fecha_doc_compra,
                        c.documento_id,
                        c.empresa_id,
                        c.centro_utilidad,
                        c.bodega
                FROM
                    inv_bodegas_movimiento_tmp as a
                    LEFT JOIN inv_bodegas_movimiento_tmp_compras_directas as b
                    ON (b.usuario_id = a.usuario_id AND b.doc_tmp_id = a.doc_tmp_id),
                    inv_bodegas_documentos as c
                WHERE
                    a.usuario_id = $usuario_id
                    AND a.doc_tmp_id = $doc_tmp_id
                    AND c.bodegas_doc_id = a.bodegas_doc_id
        ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "EL DOCUMENTO TEMPORAL [$doc_tmp_id] DEL USUARIO [$usuario_id] NO EXISTE.";
            return false;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        return  $retorno;

    }

    /**
    * Metodo para crear un documento de bodega a partir de un documento temporal.
    *
    * @param integer $doc_tmp_id identificador del documento temporal
    * @param integer $usuario_id (opcional) identificador del creador del documento temporal
    * @return array cabecera del documento creado.
    * @access public
    */
    function CrearDocumento($doc_tmp_id, $usuario_id=null)
    {
        if(empty($doc_tmp_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO [doc_tmp_id] ES REQUERIDO.";
            return false;
        }

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        $DATOS = $this->GetDocTemporal($doc_tmp_id, $usuario_id);
        if($DATOS==false) return false;

        if(empty($DATOS['tipo_id_tercero']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "DATOS ADICIONALES DEL DOCUMENTO NO ESTAN LLENOS..";
            return false;
        }

        $sql = "INSERT INTO inv_bodegas_movimiento_compras_directas
                (
                    empresa_id,
                    prefijo,
                    numero,
                    tipo_id_tercero,
                    tercero_id,
                    documento_compra,
                    fecha_doc_compra
                )
                VALUES
                (
                    '%empresa_id%',
                    '%prefijo%',
                    %numero%,
                    '".$DATOS['tipo_id_tercero']."',
                    '".$DATOS['tercero_id']."',
                    '".$DATOS['documento_compra']."',
                    '".$DATOS['fecha_doc_compra']."'
                );";

        return $this->Exec_CrearDocumento($doc_tmp_id, $sql, $usuario_id);
    }

}

?>