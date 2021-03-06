<?php
/**
* $Id: tipo_doc_bodega_I002.class.php,v 1.1.1.1 2010/08/25 22:28:45 hugo Exp $
*/

/**
* Clase que implenta metodos de BodegasDocumentosComun
*
* Implementa metodos para documentos de bodega del tipo I002(Recepcion de Ordenes de Compra)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.1.1.1 $
* @package SIIS
*/
class tipo_doc_bodega_I002 extends BodegasDocumentosComun
{
    /**
    * Constructor
    *
    * @param integer $bodegas_doc_id
    * @return boolean True si se ejecuto correctamnte.
    * @access public
    */
    function tipo_doc_bodega_I002($bodegas_doc_id)
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
    function NewDocTemporal($observacion='', $orden_pedido_id, $usuario_id=null)
    {
        if(empty($this->bodegas_doc_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CONSTRUCCION [bodegas_doc_id] ES REQUERIDO.";
            return false;
        }

        if(empty($orden_pedido_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO NECERARIO [orden_pedido_id] PARA EL TIPO DE DOCUMENTO [bodegas_doc_id] ES REQUERIDO.";
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

                INSERT INTO inv_bodegas_movimiento_tmp_ordenes_compra
                    (
                        usuario_id,
                        doc_tmp_id,
                        orden_pedido_id

                    )
                VALUES
                    (
                        $usuario_id,
                        $doc_tmp_id,
                        $orden_pedido_id
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

		$sql="  SELECT 
		a.usuario_id,
		a.doc_tmp_id,
		a.bodegas_doc_id,
		a.observacion,
		a.fecha_registro,
		a.abreviatura,
		a.empresa_destino,
		b.orden_pedido_id, 
		f.documento_id, 
		f.empresa_id, 
		f.centro_utilidad, 
		f.bodega,
		e.tipo_id_tercero,
		e.tercero_id,
		e.nombre_tercero,
		CASE 
		WHEN d.sw_rtf = '1'
		THEN d.porcentaje_rtf
		ELSE 0
		END as porcentaje_rtf,
		CASE 
		WHEN d.sw_ica = '1'
		THEN d.porcentaje_ica
		ELSE 0
		END as porcentaje_ica,
		CASE 
		WHEN d.sw_reteiva = '1'
		THEN d.porcentaje_reteiva
		ELSE 0
		END as porcentaje_reteiva,
                CASE 
		WHEN d.sw_cree = '1'
		THEN d.porcentaje_cree
		ELSE 0
		END as porcentaje_cree
		FROM 
		inv_bodegas_movimiento_tmp as a 
		JOIN inv_bodegas_movimiento_tmp_ordenes_compra as b ON (b.usuario_id = a.usuario_id)
		AND (b.doc_tmp_id = a.doc_tmp_id)
		JOIN compras_ordenes_pedidos as c ON (b.orden_pedido_id=c.orden_pedido_id)
		JOIN terceros_proveedores as d ON (c.codigo_proveedor_id = d.codigo_proveedor_id)
		JOIN terceros as e ON (d.tipo_id_tercero = e.tipo_id_tercero)
		AND (d.tercero_id = e.tercero_id) 
		JOIN inv_bodegas_documentos as f ON (f.bodegas_doc_id = a.bodegas_doc_id) 
		WHERE
		a.usuario_id = $usuario_id
		AND a.doc_tmp_id = $doc_tmp_id";
		/*print_r($sql);*/
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

        
        $DETALLE = $this->GetItemsDocTemporal($doc_tmp_id, $usuario_id);
        //var_dump($DETALLE);

        $DATOS = $this->GetDocTemporal($doc_tmp_id, $usuario_id);
        
        
        if($DATOS==false) return false;

        if(empty($DATOS['orden_pedido_id']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "DATOS ADICIONALES DEL DOCUMENTO NO ESTAN LLENOS..";
            return false;
        }
		
		
		/*Para los Impuestos*/
		/*$consulta = AutoCarga::factory("FacturasDespachoSQL", "classes", "app", "FacturasDespacho");
		$Parametros_Retencion=$consulta->Parametros_Retencion($DATOS['empresa_id']);*/
		/*
		if($Parametros_Retencion['sw_rtf']=='2' || $Parametros_Retencion['sw_rtf']=='3')
					if($subtotal >= $Parametros_Retencion['base_rtf'])
					$retencion_fuente = $subtotal*($DATOS['porcentaje_rtf']/100);
					
		if($Parametros_Retencion['sw_ica']=='2' || $Parametros_Retencion['sw_ica']=='3')
					if($subtotal >= $Parametros_Retencion['base_ica'])
					$retencion_ica = $subtotal*($DATOS['porcentaje_ica']/1000);
					
		if($Parametros_Retencion['sw_reteiva']=='2' ||$Parametros_Retencion['sw_reteiva']=='3')
					if($subtotal >= $Parametros_Retencion['base_reteiva'])
					$retencion_iva = $gravamen*($DATOS['porcentaje_reteiva']/100);
		
		print_r($DATOS);*/
		/*Fin Impuestos*/
		
        $sql = "INSERT INTO inv_bodegas_movimiento_ordenes_compra
                (
                    empresa_id,
                    prefijo,
                    numero,
                    orden_pedido_id
                )
                VALUES
                (
                    '%empresa_id%',
                    '%prefijo%',
                    %numero%,
                    '".$DATOS['orden_pedido_id']."'
                ); ";
				
        $sql .= "UPDATE inv_bodegas_movimiento
				SET
				    porcentaje_rtf = " . $DATOS['porcentaje_rtf'] . ",
				    porcentaje_ica = " . $DATOS['porcentaje_ica'] . ",
				    porcentaje_cree = " . $DATOS['porcentaje_cree'] . ",
				    porcentaje_reteiva = " . $DATOS['porcentaje_reteiva'] . "
                WHERE
					empresa_id = '%empresa_id%'
                    AND prefijo = '%prefijo%'
                    AND numero = %numero% ; ";
       
	   $sql .= " Delete from compras_ordenes_pedidos_productosfoc
                WHERE
                    orden_pedido_id ='".$DATOS['orden_pedido_id']."'
					and
					sw_autorizado = 0; ";
      /*print_r($sql);  */
		/*$dbconn->debug=true;      */
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
  //$q=$sql;
  $QUERY="";
         foreach($DETALLE AS $item => $valor)
         {
            $QUERY .=" UPDATE compras_ordenes_pedidos_detalle
                    SET 
                    numero_unidades_recibidas= COALESCE(numero_unidades_recibidas,0)+".$valor['cantidad'].",
                    sw_ingresonc = '".$valor['sw_ingresonc']."'
                    WHERE   	orden_pedido_id=".$DATOS['orden_pedido_id']."
                    AND     	codigo_producto='".$valor['codigo_producto']."'
                    AND         item_id='".$valor['item_id_compras']."'; ";
    
            /*$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($QUERY);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			$q.=$QUERY;
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }*/
         }       
		$sql .= $QUERY;
        
		return $this->Exec_CrearDocumento($doc_tmp_id, $sql, $usuario_id);
    }
}
?>