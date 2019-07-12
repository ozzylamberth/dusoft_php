<?php
/**
* $Id: tipo_doc_bodega_E016.class.php,v 1.1.1.1 2010/08/25 22:28:45 hugo Exp $
*/

/**
* Clase que implenta metodos de BodegasDocumentosComun
*
* Implementa metodos para documentos de bodega del tipo E016(TRASLADO DE BODEGA)
*
* @author Mauricio Medina -- mmedina@ipsoft-sa.com
* @version $Revision: 1.1.1.1 $
* @package SIIS
*/
class tipo_doc_bodega_E016 extends BodegasDocumentosComun
{
    /**
    * Constructor
    *
    * @param integer $bodegas_doc_id
    * @return boolean True si se ejecuto correctamnte.
    * @access public
    */
    function tipo_doc_bodega_E016($bodegas_doc_id)
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
    function NewDocTemporal($observacion='', $usuario_id=null,$OrdenRequisicion)
    {
    
        if(empty($this->bodegas_doc_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CONSTRUCCION [bodegas_doc_id] ES REQUERIDO.";
            return false;
        }

        if(empty($OrdenRequisicion))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETROS NECERARIOS [Orden Requisicion] SON REQUERIDOS.";
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
					
                INSERT INTO inv_bodegas_movimiento_tmp_despacho_campania
                    (
                        usuario_id,
                        doc_tmp_id,
                        orden_requisicion_id,
                        tipo_id_tercero,
                        tercero_id,
                        tipo_fuerza_id
                    )
                VALUES
                    (
                        $usuario_id,
                        $doc_tmp_id,
                        ".$OrdenRequisicion['orden_requisicion_id'].",
                        '".$OrdenRequisicion['tipo_id_tercero']."',
                        '".$OrdenRequisicion['tercero_id']."',
                        ".$OrdenRequisicion['tipo_fuerza_id']."
                    );	
        ";
        //print_r($OrdenRequisicion);
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
        
        $horas = ModuloGetVar("","","ESM_TiempoMaxEntregaPendientes");
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql="  SELECT  a.*,
                        b.*,
                        c.documento_id,
                        c.empresa_id,
                        c.centro_utilidad,
                        c.bodega,
                        d.nombre_tercero,
                        d.direccion as direccion_esm,
                        e.descripcion as nombre_bodega,
                        e.sw_bodegamindefensa,
                        f.fecha_registro + '".$horas."hr'::interval AS fecha_confro,
                        NOW() AS actual,
                        CASE WHEN (f.fecha_registro + '".$horas."hr'::interval) >= (now())
                        THEN '0' ELSE '1' END as f_rango

                FROM
                    inv_bodegas_movimiento_tmp as a
                    LEFT JOIN inv_bodegas_movimiento_tmp_despacho_campania as b
                    ON (b.usuario_id = a.usuario_id AND b.doc_tmp_id = a.doc_tmp_id)
                    JOIN esm_orden_requisicion as f 
                    ON (b.orden_requisicion_id = f.orden_requisicion_id),
                    inv_bodegas_documentos as c
                    JOIN bodegas as e ON (c.empresa_id = e.empresa_id)
                    and (c.centro_utilidad = e.centro_utilidad)
                    and (c.bodega = e.bodega),
                    terceros d
                WHERE
                    a.usuario_id = $usuario_id
                    AND a.doc_tmp_id = $doc_tmp_id
                    AND c.bodegas_doc_id = a.bodegas_doc_id
                    AND b.tipo_id_tercero = d.tipo_id_tercero
                    AND b.tercero_id = d.tercero_id
        ";
        //print_r($sql);
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
        //print_r($retorno);
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
       
		$sql = "  INSERT INTO inv_bodegas_movimiento_despacho_campania
                (
                    empresa_id,
                    prefijo,
                    numero,
                    orden_requisicion_id,
                    tipo_id_tercero,
                    tercero_id,
                    tipo_fuerza_id,
                    direccion,
                    empresa_transportadora,
                    numero_guia,
                    sw_bodegamindefensa,
                    sw_entregado_off
                )
                VALUES
                (
                    '%empresa_id%',
                    '%prefijo%',
                    %numero%,
                    ".$DATOS['orden_requisicion_id'].",
                    '".$DATOS['tipo_id_tercero']."',
                    '".$DATOS['tercero_id']."',
                    ".$DATOS['tipo_fuerza_id'].",
                    '".$DATOS['direccion']."',
                    '".$DATOS['empresa_transportadora']."',
                    '".$DATOS['numero_guia']."',
                    '".$DATOS['sw_bodegamindefensa']."',
                    '".$DATOS['f_rango']."'
                );";
		
	//print_r($DATOS);
        return $this->Exec_CrearDocumento($doc_tmp_id, $sql, $usuario_id);
    }
}

?>