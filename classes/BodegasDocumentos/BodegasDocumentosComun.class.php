<?php
/**
* $Id: BodegasDocumentosComun.class.php,v 1.3 2011/06/14 20:07:27 mauricio Exp $
*/

/**
* Clase para la gestion de documentos de bodega (generica)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.3 $
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

        $sql = "SELECT (COALESCE(MAX(doc_tmp_id),0) + 1) FROM inv_bodegas_movimiento_tmp";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }


        list($doc_tmp_id)=$result->FetchRow();
        $result->Close();

       //$dbconn->debug=true;
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

      $sql .= " DELETE FROM inv_bodegas_movimiento_tmp_despachos_farmacias ";
      $sql .= "WHERE  doc_tmp_id 	= ".$doc_tmp_id." ";
      $sql .= "AND      usuario_id 	= ".$usuario_id." ; ";

      $sql .= " DELETE FROM inv_bodegas_movimiento_tmp_despachos_clientes ";
      $sql .= "WHERE  doc_tmp_id 	= ".$doc_tmp_id." ";
      $sql .= "AND      usuario_id 	= ".$usuario_id."; ";

      $sql .= " DELETE FROM inv_bodegas_movimiento_tmp_ordenes_compra ";
      $sql .= "WHERE  doc_tmp_id 	= ".$doc_tmp_id." ";
      $sql .= "AND      usuario_id 	= ".$usuario_id."; ";

      $sql .= " DELETE FROM inv_bodegas_movimiento_tmp_prestamo ";
      $sql .= "WHERE  doc_tmp_id 	= ".$doc_tmp_id." ";
      $sql .= "AND      usuario_id 	= ".$usuario_id."; ";

     $sql .= " DELETE FROM inv_bodegas_movimiento_tmp_compras_directas ";
     $sql .= "WHERE  doc_tmp_id 	= ".$doc_tmp_id." ";
     $sql .= "AND      usuario_id 	= ".$usuario_id."; ";


        $sql .= " DELETE FROM inv_bodegas_movimiento_tmp
                WHERE usuario_id = $usuario_id AND doc_tmp_id = ".$doc_tmp_id."; ";


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
       
	   $sql .= "
				SELECT
				a.item_id,
				a.usuario_id,
				a.doc_tmp_id,
				a.empresa_id,
				a.centro_utilidad,
				a.bodega,
				a.codigo_producto,
				a.cantidad,
				a.porcentaje_gravamen,
				a.total_costo,
				a.fecha_vencimiento,
				a.lote,
				a.local_prod,
				a.valor_unitario,
				a.total_costo_pedido,
				a.sw_ingresonc,
				a.item_id_compras,
				a.lote_devuelto,
				a.prefijo_temp,
				a.observacion_cambio,
				COALESCE(a.cantidad_sistema,0)as cantidad_sistema,
				fc_descripcion_producto(b.codigo_producto) as descripcion,
				b.contenido_unidad_venta,
				b.unidad_id,
				c.descripcion as descripcion_unidad,
				(((a.total_costo)/((a.porcentaje_gravamen/100)+1))/a.cantidad) as valor_unit,
				((a.total_costo/a.cantidad)-(((a.total_costo)/((a.porcentaje_gravamen/100)+1))/a.cantidad)) as iva,
				((((a.total_costo)/((a.porcentaje_gravamen/100)+1))/a.cantidad)*a.cantidad) as valor_total,
				(((a.total_costo/a.cantidad)-(((a.total_costo)/((a.porcentaje_gravamen/100)+1))/a.cantidad))*a.cantidad) as iva_total,
				a.numero_caja
				FROM
				inv_bodegas_movimiento_tmp_d as a
				JOIN inventarios_productos as b ON (b.codigo_producto = a.codigo_producto)
				JOIN unidades as c ON (c.unidad_id = b.unidad_id)
				WHERE
				a.usuario_id = $usuario_id
				AND a.doc_tmp_id = $doc_tmp_id
				ORDER BY a.item_id";
//    echo "<pre>";print_r($sql);
		/* $sql = "SELECT
		a.*,
		fc_descripcion_producto(b.codigo_producto) as descripcion,
		( 
		b.contenido_unidad_venta,
		b.unidad_id,
		c.descripcion as descripcion_unidad,
		a.lote,
		a.fecha_vencimiento,
		a.sw_ingresonc
		FROM
		inv_bodegas_movimiento_tmp_d as a,
		inventarios_productos as b,
		unidades as c
		WHERE
		a.usuario_id = $usuario_id
		AND a.doc_tmp_id = $doc_tmp_id
		AND b.codigo_producto = a.codigo_producto
		AND c.unidad_id = b.unidad_id
		ORDER BY a.item_id	
                ";*/
				
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
    * Metodo para obtener todos los registros de un documento temporal.
    *
    * @param integer $doc_tmp_id identificador del documento temporal
    * @param integer $usuario_id (opcional) identificador del creador del documento temporal
    * @return array items del documento temporal consultado
    * @access public
    */
    function GetItemsDocTemporalAgrupado($doc_tmp_id, $usuario_id=null)
    {
      if(empty($doc_tmp_id))
      {
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = "PARAMETRO [doc_tmp_id] ES REQUERIDO.";
        return false;
      }

      if(empty($usuario_id))
        $usuario_id = UserGetUID();

      GLOBAL $ADODB_FETCH_MODE;
      list($dbconn) = GetDBconn();

      $sql = "SELECT  a.*,
                      b.descripcion,
                      b.unidad_id,
                      c.descripcion as descripcion_unidad,
                      a.lote,
                      a.fecha_vencimiento,
                      a.sw_ingresonc
              FROM    inv_bodegas_movimiento_tmp_d as a,
                      inventarios_productos as b,
                      unidades as c
              WHERE   a.usuario_id = $usuario_id
              AND     a.doc_tmp_id = $doc_tmp_id
              AND     b.codigo_producto = a.codigo_producto
              AND     c.unidad_id = b.unidad_id
              ORDER BY a.item_id ";

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
        $retorno[$fila['codigo_producto']][$fila['lote']][$fila['fecha_vencimiento']] = $fila;
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
        //print_r($sql);
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
    function AddItemDocTemporal($doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id=null,$fecha_venc,$lotec,$localizacion,$total_costo_ped,$valor_unitario,$caja=null)
    {
        
        if(empty($doc_tmp_id) || empty($codigo_producto) || !is_numeric($cantidad) || !is_numeric($porcentaje_gravamen) || !is_numeric($total_costo))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETROS NECESARIOS [doc_tmp_id,codigo_producto,cantidad,total_costo,total_costo_ped] PARA CREACION DE ITEM.";
			return false;
        }

        if($total_costo_ped=="")
            $total_costo_ped="0";
        
        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }
        
         //print_r($doc_tmp_id."-".$codigo_producto."-".$cantidad."-".$total_costo."-".$total_costo_ped);

        GLOBAL $ADODB_FETCH_MODE;
        
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
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
//     print_r($sql);
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
        //Bodega Destino
        $sql1= "SELECT    bodega_destino
                FROM     inv_bodegas_movimiento_tmp_traslados
                WHERE    usuario_id = $usuario_id
                AND      doc_tmp_id = $doc_tmp_id;"; 
        //print_r($fecha_venc);
        $fecha_vencim=explode("-",$fecha_venc);
        $fechavencimiento=$fecha_vencim[2]."-".$fecha_vencim[1]."-".$fecha_vencim[0];
        //print_r($fecha_vencim);
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql1);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $datos2 = $result->FetchRow();
        $result->Close();
        
       // print_r($datos2['bodega_destino']."aquibodega");
        
        if(!empty($datos2['bodega_destino']))
        {
          $sql3= "SELECT   a.descripcion as dBodega,
                           b.descripcion as dProducto
                  FROM     bodegas as a,
                           inventarios_productos b 
                  WHERE    a.bodega = '".$datos2['bodega_destino']."'
                  AND      b.codigo_producto = '$codigo_producto' ;"; 
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql3);
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

          $datos4 = $result->FetchRow();
          $result->Close();
          
          //print_r($datos2);

          $sql2 = "SELECT  codigo_producto
                   FROM    existencias_bodegas
                   WHERE   codigo_producto = '$codigo_producto'
                   AND     bodega = '".$datos2['bodega_destino']."'; ";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql2);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
           if($dbconn->ErrorNo() != 0)
          { 
              $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
              $this->mensajeDeError = $dbconn->ErrorMsg();
              return false;
          }

          if($dbconn->ErrorNo() != 0)
          {
              $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
              $this->mensajeDeError = $dbconn->ErrorMsg();
              return false;
          }

          $datos3 = $result->FetchRow();
          $result->Close();
          //print_r("hhshshhs".$datos3);
         
         //print_r($datos4);

         
         if(empty($datos3['codigo_producto']))
         {
          $this->mensajeDeError = "EL PRODUCTO:$codigo_producto -".$datos4['dproducto']." NO EXISTE EN BODEGA:".$datos4['dbodega']." ";
          return false;
         }
       }
        
        
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
                    total_costo,
                    fecha_vencimiento,
                    lote,
                    local_prod,
                    total_costo_pedido,
                    valor_unitario,
		    numero_caja
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
                    $total_costo,
                    '".trim($fechavencimiento)."',
                    '".trim($lotec)."',
                    '$localizacion',
                     $total_costo_ped,
                     ".(($valor_unitario)? $valor_unitario:"NULL" ).",
		     ".(($caja)? $caja:"NULL" )." 

                ) ";
		/*print_r($sql);*/
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
        //return false;
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
    function Exec_CrearDocumento($doc_tmp_id, $sql_doc_datos_adicionales=null, $usuario_id=null,$adicionales)
    {
       // print_r($joder);
        if(empty($doc_tmp_id))
        {
            $this->error = "--[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "--PARAMETRO [doc_tmp_id] ES REQUERIDO.";
            return false;
        }

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        $DATOS = $this->GetDocTemporal($doc_tmp_id, $usuario_id,$adicionales);
        if($DATOS===false) return false;
        //print_r($DATOS);
        $DETALLE = $this->GetItemsDocTemporal($doc_tmp_id, $usuario_id);
        if($DETALLE===false) return false;

        if(empty($DETALLE))
        {   
            echo "1) ";print_r($DETALLE);
            $this->error = "---[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "---NO HAY REGISTROS EN EL DOCUMENTO TEMPORAL[$doc_tmp_id] DEL USUARIO[$usuario_id] PARA CREAR UN DOCUMENTO.";
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
        {//echo "!!!!!!!!!!!!2";
            echo "2) "."[" . get_class($this) . "][" . __LINE__ . "] <br>".$sql ;
            print_r($dbconn->ErrorMsg());
            $dbconn->RollbackTrans();
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            echo "3) "."[" . get_class($this) . "][" . __LINE__ . "]" ;
            echo "3a) ----No se pudo obtener la numercion del nuevo documento a crear." ;
            $dbconn->RollbackTrans();
            $this->error = "----[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "----No se pudo obtener la numercion del nuevo documento a crear.";
            return false;
        }

        list($prefijo_doc,$numero_doc) = $result->FetchRow();
        //print_r($result);
        $result->Close();

        $sql  = "UPDATE documentos ";
        $sql .= "SET numeracion = numeracion + 1 ";
        $sql .= "WHERE documento_id = ".$DATOS['documento_id']." AND empresa_id = '".$DATOS['empresa_id']."';";

        $result = $dbconn->Execute($sql);
        //print_r($sql);
        if($dbconn->ErrorNo() != 0)
        {//echo "!!!!!!!!!!!!4";
            echo "4) "."[" . get_class($this) . "][" . __LINE__ . "] <br>".$sql ;
            print_r($dbconn->ErrorMsg());
            $dbconn->RollbackTrans();
            $this->error = "-----[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

      //$dbconn->debug=true;
     // echo "jdoeeeeer";
        if($DATOS['abreviatura']=="")
          {
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
                    fecha_registro,
                    abreviatura
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
                    NOW(),
                    NULL
                    
                );";
           }
                             else
                             {
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
                                      fecha_registro,
                                      abreviatura
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
                                      NOW(),
                                      '".$DATOS['abreviatura']."'
                                  );";
                                 }
        
        $result = $dbconn->Execute($sql);
		/*print_r($sql);*/
		/*$dbconn->debug=true;*/
        if($dbconn->ErrorNo() != 0)
        {//echo "!!!!!!!!!!!!5";
            echo "5) "."[" . get_class($this) . "][" . __LINE__ . "] <br>".$sql." ".$prefijo_doc." ".$numero_doc ;
            print_r($dbconn->ErrorMsg());
            $dbconn->RollbackTrans();
            $this->error = "**[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg().$prefijo_doc.$numero_doc;
            return false;
        }

        /*foreach($DETALLE as $item=>$fila)
        {
          $fechaven=explode("-",$fila['fecha_vencimiento']);
          $fechavencimiento=$fechaven[0]."-".$fechaven[1]."-".$fechaven[2];
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
                        total_costo,
                        fecha_vencimiento,
                        lote,
                        observacion_cambio,
                        total_costo_pedido,
                        valor_unitario
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
                        ".$fila['total_costo'].",
                        '".$fechavencimiento."',
                        '".$fila['lote']."',
                        '".$fila['observacion_cambio']."',
                        '".$fila['total_costo_pedido']."',
                         ".(($fila['valor_unitario'])? $fila['valor_unitario']:"NULL")."
                    );
            ";
            
        }*/
        //Para Se movi??? antes para poder hacer un traslado
         if(!empty($sql_doc_datos_adicionales))
        {
          $sql_doc_datos_adicionales = str_replace("%empresa_id%", $DATOS['empresa_id'], $sql_doc_datos_adicionales);
          $sql_doc_datos_adicionales = str_replace("%prefijo%", $prefijo_doc, $sql_doc_datos_adicionales);
          $sql = str_replace("%numero%", $numero_doc, $sql_doc_datos_adicionales);
          /*print_r($sql);*/
         /* $dbconn->debug=true;*/
          $result = $dbconn->Execute($sql);

          if($dbconn->ErrorNo() != 0)
          {//echo "!!!!!!!!!!!!6";
            echo "6) "."[" . get_class($this) . "][" . __LINE__ . "] <br>".$sql;
            print_r($dbconn->ErrorMsg());
            $dbconn->RollbackTrans();
            $this->error = "****[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
          }
        }
        
        $sql = "INSERT INTO inv_bodegas_movimiento_d
                    (
                        empresa_id,
                        prefijo,
                        numero,
                        centro_utilidad,
                        bodega,
                        codigo_producto,
                        cantidad,
                        porcentaje_gravamen,
                        total_costo,
                        fecha_vencimiento,
                        lote,
                        observacion_cambio,
                        total_costo_pedido,
                        valor_unitario,
			cantidad_sistema,
			numero_caja
                    )
        SELECT  '".$DATOS['empresa_id']."' AS empresa_id,
                '".$prefijo_doc."' AS prefijo,
                 ".$numero_doc." AS numeracion,
                 a.centro_utilidad,
                 a.bodega,
                 a.codigo_producto,
                 a.cantidad,
                 a.porcentaje_gravamen,
                 a.total_costo,
                 a.fecha_vencimiento,
                 a.lote,
                 a.observacion_cambio,
                 a.total_costo_pedido,
                 (a.total_costo/a.cantidad),
                 COALESCE(a.cantidad_sistema,0) AS cantidad_sistema,
		 numero_caja
        FROM    inv_bodegas_movimiento_tmp_d as a,
                inventarios_productos as b,
                unidades as c
        WHERE   a.usuario_id = ".$usuario_id."
        AND a.doc_tmp_id = ".$doc_tmp_id."
        AND b.codigo_producto = a.codigo_producto
        AND c.unidad_id = b.unidad_id ";
        /*((a.porcentaje_gravamen/100)*(a.total_costo/a.cantidad))+(a.total_costo/a.cantidad)*/
        /*print_r($sql);*/
        
        //$dbconn->debug=true;
        
        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {//echo "!!!!!!!!!!!!7".$sql;
            echo "7) "."[" . get_class($this) . "][" . __LINE__ . "] <br>".$sql;
            print_r($dbconn->ErrorMsg());
            $dbconn->RollbackTrans();
            $this->error = "**--[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

       
        
        $sql = "DELETE FROM inv_bodegas_movimiento_tmp WHERE usuario_id = $usuario_id AND doc_tmp_id = $doc_tmp_id;";
        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {//echo "!!!!!!!!!!!!8";
            echo "8) "."[" . get_class($this) . "][" . __LINE__ . "] <br>".$sql;
            print_r($dbconn->ErrorMsg());
            $dbconn->RollbackTrans();
            $this->error = "--**-[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $dbconn->CommitTrans();

        return $this->GetInfoBodegaMovimiento($DATOS['empresa_id'],$prefijo_doc,$numero_doc);
    }
}
?>