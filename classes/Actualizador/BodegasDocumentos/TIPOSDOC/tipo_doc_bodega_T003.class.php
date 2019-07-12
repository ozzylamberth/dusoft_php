<?php
/**
* $Id: tipo_doc_bodega_T003.class.php,v 1.1.1.1 2010/08/25 22:28:45 hugo Exp $
*/

/**
* Clase que implenta metodos de BodegasDocumentosComun
*
* Implementa metodos para documentos de bodega del tipo T003(TRASLADO DE BODEGA)
*
* @author Mauricio Medina -- mmedina@ipsoft-sa.com
* @version $Revision: 1.1.1.1 $
* @package SIIS
*/
class tipo_doc_bodega_T003 extends BodegasDocumentosComun
{
    /**
    * Constructor
    *
    * @param integer $bodegas_doc_id
    * @return boolean True si se ejecuto correctamnte.
    * @access public
    */
    function tipo_doc_bodega_T003($bodegas_doc_id)
    {
        $this->BodegasDocumentosComun($bodegas_doc_id);
        return true;
    }


    /**
    * Metodo para crear un documento temporal.
    * @param string  $observacion observacion del documento a crear
    * @param integer  $orden_pedido_id Identificador de la orden de pedido
    * @param integer $usuario_id integer (opcional)
    * @return integer (doc_tmp_id del documento creado)
    * @access public
    */
    function NewDocTemporal($observacion='', $centro_utilidad_destino, $bodega_destino, $usuario_id=null)
    {
    
        if(empty($this->bodegas_doc_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CONSTRUCCION [bodegas_doc_id] ES REQUERIDO.";
            return false;
        }

        if(empty($centro_utilidad_destino) || empty($bodega_destino))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETROS NECERARIOS [centro_utilidad_destino, bodega_destino] SON REQUERIDOS.";
            return false;
        }

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT * FROM inv_bodegas_documentos WHERE bodegas_doc_id = " . $this->bodegas_doc_id . ";";

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
            $this->mensajeDeError = "EL [bodegas_doc_id=".$this->bodegas_doc_id."] NO EXISTE EN LA TABLA [inv_bodegas_documentos].";
            return false;
        }

        $DATOS = $result->FetchRow();
        $result->Close();


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
					
                INSERT INTO inv_bodegas_movimiento_traslados_esm_devoluciones_tmp
                    (
                        usuario_id,
                        doc_tmp_id,
                        empresa_id,
                        centro_utilidad_origen,
                        bodega_origen,
                        centro_utilidad_destino,
                        bodega_destino
                    )
                VALUES
                    (
                        $usuario_id,
                        $doc_tmp_id,
                        '".$DATOS['empresa_id']."',
                        '".$DATOS['centro_utilidad']."',
                        '".$DATOS['bodega']."',
                        '$centro_utilidad_destino',
                        '$bodega_destino'
                    );
					
					 INSERT INTO inv_bodegas_movimiento_tmp_traslados
                    (
                        usuario_id,
                        doc_tmp_id,
                        empresa_id,
                        centro_utilidad_origen,
                        bodega_origen,
                        centro_utilidad_destino,
                        bodega_destino
                    )
                VALUES
                    (
                        $usuario_id,
                        $doc_tmp_id,
                        '".$DATOS['empresa_id']."',
                        '".$DATOS['centro_utilidad']."',
                        '".$DATOS['bodega']."',
                        '$centro_utilidad_destino',
                        '$bodega_destino'
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
        //$dbconn->debug=true;
        $sql="  SELECT  a.*,
                        b.centro_utilidad_destino,
                        b.bodega_destino,
                        c.documento_id,
                        c.empresa_id,
                        c.centro_utilidad,
                        c.bodega,
                        f.descripcion as bodega_satelite
                FROM
                    inv_bodegas_movimiento_tmp as a
                    LEFT JOIN inv_bodegas_movimiento_traslados_esm_devoluciones_tmp as b
                    ON (b.usuario_id = a.usuario_id AND b.doc_tmp_id = a.doc_tmp_id),
                    inv_bodegas_documentos as c,
                    bodegas as f
                WHERE
                    a.usuario_id = $usuario_id
                    AND a.doc_tmp_id = $doc_tmp_id
                    AND c.bodegas_doc_id = a.bodegas_doc_id
                    AND b.empresa_id = f.empresa_id
                    AND b.centro_utilidad_destino = f.centro_utilidad
                    AND b.bodega_destino = f.bodega
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
		//print_r($DATOS);
        if(empty($DATOS['centro_utilidad_destino']) || empty($DATOS['bodega_destino']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "DATOS ADICIONALES DEL DOCUMENTO NO ESTAN LLENOS..";
            return false;
        }
		$sql = "  INSERT INTO inv_bodegas_movimiento_traslados_esm_devoluciones
                (
                    empresa_id,
                    prefijo,
                    numero,
                    centro_utilidad_destino,
                    bodega_destino,
					usuario_id
                )
                VALUES
                (
                    '%empresa_id%',
                    '%prefijo%',
                    %numero%,
                    '".$DATOS['centro_utilidad_destino']."',
                    '".$DATOS['bodega_destino']."',
					".UserGetUID()."
					
                );";
		
		$sql .= "INSERT INTO inv_bodegas_movimiento_traslados
                (
                    empresa_id,
                    prefijo,
                    numero,
                    centro_utilidad_destino,
                    bodega_destino
                )
                VALUES
                (
                    '%empresa_id%',
                    '%prefijo%',
                    %numero%,
                    '".$DATOS['centro_utilidad_destino']."',
                    '".$DATOS['bodega_destino']."'
                );  ";
		
       
//print_r($sql);
        return $this->Exec_CrearDocumento($doc_tmp_id, $sql, $usuario_id);
    }
}

?>