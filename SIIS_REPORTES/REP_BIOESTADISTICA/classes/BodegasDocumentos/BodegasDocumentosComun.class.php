<?php
/**
* $Id: BodegasDocumentosComun.class.php,v 1.15 2007/06/07 16:18:12 alexgiraldo Exp $
*/

/**
* Clase para la gestion de documentos de bodega (generica)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.15 $
* @package SIIS
*/
class BodegasDocumentosComun
{

    /**
    * Codigo de error
    *
    * @var string
    * @access private
    */
    var $error;

    /**
    * Mensaje de error
    *
    * @var string
    * @access private
    */
    var $mensajeDeError;


    /**
    * Tipo de documento de bodega
    *
    * @var integer
    * @access private
    */
    var $bodegas_doc_id;

    /************************************************************************************
    * M E T O D O S
    ************************************************************************************/

    /**
    * Constructor
    *
    * @param $bodegas_doc_id (opcional)
    * @return boolean
    * @access public
    */
    function BodegasDocumentosComun($bodegas_doc_id)
    {
        $this->bodegas_doc_id = $bodegas_doc_id;
        return true;
    }

    /**
    * Metodo para recuperar el titulo del error
    *
    * @return string
    * @access public
    */
    function Err()
    {
        return $this->error;
    }

    /**
    * Metodo para recuperar el detalle del error
    *
    * @return string
    * @access public
    */
    function ErrMsg()
    {
        return $this->mensajeDeError;
    }

    /**
    * Metodo para establecer el tipo de documento de bodega.
    *
    * @param $bodegas_doc_id (opcional)
    * @return string
    * @access public
    */
    function SetTipoDoc($bodegas_doc_id)
    {
        $this->bodegas_doc_id = $bodegas_doc_id;
        return true;
    }


    /**
    * Metodo para obtener informacion de un tipo de documento especifico.
    *
    * @param integer $bodegas_doc_id (opcional)
    * @return array informacion del documento de bodega consultado.
    * @access public
    */
    function GetInfoBodegaDocumento($bodegas_doc_id=null)
    {
        if(empty($bodegas_doc_id))
        {
            if(empty($this->bodegas_doc_id))
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "PARAMETRO [bodegas_doc_id] ES REQUERIDO.";
                return false;
            }
            $bodegas_doc_id = $this->bodegas_doc_id;
        }
        else
        {
            $this->SetTipoDoc($bodegas_doc_id);
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT
                    c.inv_tipo_movimiento as tipo_movimiento,
                    b.tipo_doc_general_id as tipo_doc_bodega_id,
                    c.descripcion as tipo_clase_documento,
                    b.prefijo,
                    b.descripcion,
                    a.empresa_id,
                    a.centro_utilidad,
                    a.bodega

                FROM
                    inv_bodegas_documentos as a,
                    documentos as b,
                    tipos_doc_generales as c
                WHERE
                    a.bodegas_doc_id = $bodegas_doc_id
                    AND b.documento_id = a.documento_id
                    AND b.empresa_id = a.empresa_id
                    AND c.tipo_doc_general_id = b.tipo_doc_general_id
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
            $this->mensajeDeError = "NO EXISTE EL DOCUMENTO DE BODEGA [$bodegas_doc_id] EN [public.inv_bodegas_documentos].";
            return false;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        return  $retorno;
    }


    /**
    * Metodo para obtener un documento temporal.
    *
    * @param integer $doc_tmp_id identificador del documento temporal
    * @param integer $usuario_id (opcional) identificador del creador del documento temporal
    * @return array datos del documento temporal consultado.
    * @access public
    */
    function GetInfoBodegaMovimiento($empresa_id,$prefijo,$numero)
    {
        if(empty($empresa_id) || empty($prefijo) || empty($numero))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO [empresa_id,prefijo,numero] SON REQUERIDOS.";
            return false;
        }

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql="SELECT * FROM inv_bodegas_movimiento
              WHERE empresa_id = '$empresa_id' AND prefijo = '$prefijo' AND numero = $numero;
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
    * Metodo para crear un documento temporal.
    *
    * @param string $observacion observacion del documento a crear
    * @param integer $usuario_id (opcional)
    * @return array (informacion del documento creado)
    * @access public
    */
    function NewDocTemporal($observacion='', $usuario_id=null)
    {
        if(empty($this->bodegas_doc_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CONSTRUCCION [bodegas_doc_id] ES REQUERIDO.";
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
        ";

        $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg() . $sql ;
            return false;
        }

        $dbconn->Affected_Rows();

        return $this->GetDocTemporal($doc_tmp_id, $usuario_id);
    }



    /**
    * Metodo para borrar un documento temporal.
    *
    * @param integer $doc_tmp_id identificador del documento temporal
    * @param integer $usuario_id (opcional) identificador del creador del documento temporal
    * @return integer numero de registros borrados.
    * @access public
    */
    function DelDocTemporal($doc_tmp_id, $usuario_id=null)
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

        $sql = "DELETE FROM inv_bodegas_movimiento_tmp
                WHERE usuario_id = $usuario_id AND doc_tmp_id = $doc_tmp_id ";

        $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        return $dbconn->Affected_Rows();
    }



    /**
    * Metodo para obtener un documento temporal.
    *
    * @param integer $doc_tmp_id identificador del documento temporal
    * @param integer $usuario_id (opcional) identificador del creador del documento temporal
    * @return array datos del documento temporal consultado.
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

        $sql="SELECT
                t.*,
                c.inv_tipo_movimiento as tipo_movimiento,
                b.tipo_doc_general_id as tipo_doc_bodega_id,
                c.descripcion as tipo_clase_documento,
                b.prefijo,
                b.descripcion,
                a.documento_id,
                a.empresa_id,
                a.centro_utilidad,
                a.bodega

            FROM
                inv_bodegas_movimiento_tmp as t,
                inv_bodegas_documentos as a,
                documentos as b,
                tipos_doc_generales as c
            WHERE
                doc_tmp_id = $doc_tmp_id
                AND usuario_id = $usuario_id
                AND a.bodegas_doc_id = t.bodegas_doc_id
                AND b.documento_id = a.documento_id
                AND b.empresa_id = a.empresa_id
                AND c.tipo_doc_general_id = b.tipo_doc_general_id;
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
    * Metodo para obtener todos los registros de un documento temporal.
    *
    * @param integer $doc_tmp_id identificador del documento temporal
    * @param integer $usuario_id (opcional) identificador del creador del documento temporal
    * @return array items del documento temporal consultado
    * @access public
    */
    function GetItemsDocTemporal($doc_tmp_id, $usuario_id=null)
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

        $sql = "SELECT
                    a.*,
                    b.descripcion,
                    b.unidad_id,
                    c.descripcion as descripcion_unidad
                FROM
                    inv_bodegas_movimiento_tmp_d as a,
                    inventarios_productos as b,
                    unidades as c
                WHERE
                    a.usuario_id = $usuario_id
                    AND a.doc_tmp_id = $doc_tmp_id
                    AND b.codigo_producto = a.codigo_producto
                    AND c.unidad_id = b.unidad_id
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

        $retorno = array();

        while($fila = $result->FetchRow())
        {
            $retorno[$fila['item_id']]=$fila;
        }
        $result->Close();

        return  $retorno;
    }


    /**
    * Metodo para obtener un registro temporal.
    *
    * @param integer $item_id identificador del item del documento temporal a consultar
    * @return array informacion del item consultado
    * @access public
    */
    function GetItemDocTemporal($item_id)
    {
        if(empty($item_id))
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

        $sql = "SELECT
                    a.*,
                    b.descripcion,
                    b.unidad_id,
                    c.descripcion as descripcion_unidad
                FROM
                    inv_bodegas_movimiento_tmp_d as a,
                    inventarios_productos as b,
                    unidades as c
                WHERE
                    a.item_id = $item_id
                    AND b.codigo_producto = a.codigo_producto
                    AND c.unidad_id = b.unidad_id
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
            $this->mensajeDeError = "EL ITEM [$item_id] NO EXISTE.";
            return false;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        return  $retorno;
    }


    /**
    * Metodo para borrar un registro temporal.
    *
    * @param integer $item_id identificador del item del documento temporal a consultar
    * @return integer numero de registros eliminados
    * @access public
    */
    function DelItemDocTemporal($item_id)
    {
        if(empty($item_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO [doc_tmp_id] ES REQUERIDO.";
            return false;
        }

        list($dbconn) = GetDBconn();

        $sql = "DELETE FROM inv_bodegas_movimiento_tmp_d
                WHERE item_id = $item_id ";

        $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        return $dbconn->Affected_Rows();
    }

    /**
    * Metodo para adicionar un registro temporal.
    *
    * @param integer $doc_tmp_id identificador del documento temporal
    * @param string $codigo_producto identificador del producto
    * @param numeric $cantidad cantidad
    * @param numeric $porcentaje_gravamen porcentaje de gravamen
    * @param numeric $total_costo total costo (cantidad * precio unitario gravado)
    * @param integer $usuario_id (opcional) identificador del documento temporal
    * @return integer numero de item_id del documento creado.
    * @access public
    */
    function AddItemDocTemporal($doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id=null)
    {
        if(empty($doc_tmp_id) || empty($codigo_producto) || !is_numeric($cantidad) || !is_numeric($porcentaje_gravamen) || !is_numeric($total_costo))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETROS NECERARIOS [doc_tmp_id,codigo_producto,cantidad,total_costo] PARA CREACION DE ITEM.";
            return false;
        }

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT
                    c.empresa_id,
                    c.centro_utilidad,
                    c.bodega,
                    c.codigo_producto

                FROM
                    inv_bodegas_movimiento_tmp as a,
                    inv_bodegas_documentos as b,
                    existencias_bodegas as c
                WHERE
                    a.usuario_id = $usuario_id
                    AND a.doc_tmp_id = $doc_tmp_id

                    AND b.bodegas_doc_id = a.bodegas_doc_id

                    AND c.empresa_id = b.empresa_id
                    AND c.centro_utilidad = b.centro_utilidad
                    AND c.bodega = b.bodega
                    AND c.codigo_producto ='$codigo_producto';
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
            $this->mensajeDeError = "EL producto [$codigo_producto] no esta relacionado en existencias_bodega.";
            return false;
        }

        $datos = $result->FetchRow();
        $result->Close();

        $sql = "SELECT nextval('inv_bodegas_movimiento_tmp_d_item_id_seq'::regclass);";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        list($item_id) = $result->FetchRow();
        $result->Close();


         $sql = "INSERT INTO inv_bodegas_movimiento_tmp_d
                (
                    item_id,
                    usuario_id,
                    doc_tmp_id,
                    empresa_id,
                    centro_utilidad,
                    bodega,
                    codigo_producto,
                    cantidad,
                    porcentaje_gravamen,
                    total_costo
                )
                VALUES
                (
                    $item_id,
                    $usuario_id,
                    $doc_tmp_id,
                    '".$datos['empresa_id']."',
                    '".$datos['centro_utilidad']."',
                    '".$datos['bodega']."',
                    '".$datos['codigo_producto']."',
                    $cantidad,
                    $porcentaje_gravamen,
                    $total_costo
                )
        ";

        $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        return $item_id;
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
        return $this->Exec_CrearDocumento($doc_tmp_id, $sql_doc_datos_adicionales=null, $usuario_id);
    }


    /**
    * Metodo PRIVADO para crear un documento de bodega a partir de un documento temporal.
    *
    * @param integer $doc_tmp_id identificador del documento temporal
    * @param string $sql_datos_adicionales
    * @param integer $usuario_id (opcional) identificador del creador del documento temporal
    * @return array cabecera del documento creado.
    * @access private
    */
    function Exec_CrearDocumento($doc_tmp_id, $sql_doc_datos_adicionales=null, $usuario_id=null)
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
        if($DATOS===false) return false;

        $DETALLE = $this->GetItemsDocTemporal($doc_tmp_id, $usuario_id);
        if($DETALLE===false) return false;

        if(empty($DETALLE))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "NO HAY REGISTROS EN EL DOCUMENTO TEMPORAL[$doc_tmp_id] DEL USUARIO[$usuario_id] PARA CREAR UN DOCUMENTO.";
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();

        $sql  = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE; ";
        $sql .= "SELECT prefijo,numeracion FROM documentos ";
        $sql .= "WHERE documento_id = ".$DATOS['documento_id']." AND empresa_id = '".$DATOS['empresa_id']."'; ";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $dbconn->RollbackTrans();
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "No se pudo obtener la numercion del nuevo documento a crear.";
            return false;
        }

        list($prefijo_doc,$numero_doc) = $result->FetchRow();
        $result->Close();

        $sql  = "UPDATE documentos ";
        $sql .= "SET numeracion = numeracion + 1 ";
        $sql .= "WHERE documento_id = ".$DATOS['documento_id']." AND empresa_id = '".$DATOS['empresa_id']."';";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }


        $sql = "INSERT INTO inv_bodegas_movimiento
                (
                    documento_id,
                    empresa_id,
                    centro_utilidad,
                    bodega,
                    prefijo,
                    numero,
                    observacion,
                    sw_estado,
                    usuario_id,
                    fecha_registro
                )
                VALUES
                (
                    ".$DATOS['documento_id'].",
                    '".$DATOS['empresa_id']."',
                    '".$DATOS['centro_utilidad']."',
                    '".$DATOS['bodega']."',
                    '$prefijo_doc',
                    $numero_doc,
                    '".$DATOS['observacion']."',
                    '1',
                    $usuario_id,
                    NOW()
                );";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if(!empty($sql_doc_datos_adicionales))
        {
            $sql_doc_datos_adicionales = str_replace("%empresa_id%", $DATOS['empresa_id'], $sql_doc_datos_adicionales);
            $sql_doc_datos_adicionales = str_replace("%prefijo%", $prefijo_doc, $sql_doc_datos_adicionales);
            $sql = str_replace("%numero%", $numero_doc, $sql_doc_datos_adicionales);

            $result = $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $dbconn->RollbackTrans();
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
        }

        $sql = '';

        foreach($DETALLE as $item=>$fila)
        {
            $sql.= "INSERT INTO inv_bodegas_movimiento_d
                    (
                        empresa_id,
                        prefijo,
                        numero,
                        centro_utilidad,
                        bodega,
                        codigo_producto,
                        cantidad,
                        porcentaje_gravamen,
                        total_costo
                    )
                    VALUES
                    (
                        '".$DATOS['empresa_id']."',
                        '$prefijo_doc',
                        $numero_doc,
                        '".$fila['centro_utilidad']."',
                        '".$fila['bodega']."',
                        '".$fila['codigo_producto']."',
                        ".$fila['cantidad'].",
                        ".$fila['porcentaje_gravamen'].",
                        ".$fila['total_costo']."
                    );
            ";

        }

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $sql = "DELETE FROM inv_bodegas_movimiento_tmp WHERE usuario_id = $usuario_id AND doc_tmp_id = $doc_tmp_id;";
        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $dbconn->CommitTrans();

        return $this->GetInfoBodegaMovimiento($DATOS['empresa_id'],$prefijo_doc,$numero_doc);
    }


}

?>