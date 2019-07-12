<?php
/**
* $Id: tipo_doc_bodega_E012.class.php,v 1.1 2009/07/27 20:47:55 johanna Exp $
*/

/**
* Clase que implenta metodos de BodegasDocumentosComun
*
* Implementa metodos para documentos de bodega del tipo E012(EGRESO POR PRESTAMOS)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.1 $
* @package SIIS
*/
class tipo_doc_bodega_E012 extends BodegasDocumentosComun
{
    /**
    * Constructor
    *
    * @param integer $bodegas_doc_id
    * @return boolean True si se ejecuto correctamnte.
    * @access public
    */
    function tipo_doc_bodega_E012($bodegas_doc_id)
    {
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
    function NewDocTemporal($observacion='', $codigo_proveedor_id, $numero_factura, $usuario_id=null)
    {
        if(empty($this->bodegas_doc_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CONSTRUCCION [bodegas_doc_id] ES REQUERIDO.";
            return false;
        }

        if(empty($codigo_proveedor_id) || empty($numero_factura))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO NECERARIOS [codigo_proveedor_id, numero_factura] SON REQUERIDOS.";
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

                INSERT INTO inv_bodegas_movimiento_tmp_devolucion_proveedor
                    (
                        usuario_id,
                        doc_tmp_id,
                        codigo_proveedor_id,
                        numero_factura
                    )
                VALUES
                    (
                        $usuario_id,
                        $doc_tmp_id,
                        '$codigo_proveedor_id',
                        '$numero_factura'
                    );
        ";
      // print_r($sql);
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
		a.*, 
		b.codigo_proveedor_id, 
		b.numero_factura, 
		c.documento_id, 
		c.empresa_id, 
		c.centro_utilidad, 
		c.bodega,
		d.porc_rtf,
		d.porc_ica,
		d.porc_rtiva,
		f.subtotal,
		f.iva_total,
		f.total,
		TO_CHAR(d.fecha_registro,'YYYY') as anio_factura
		FROM 
		inv_bodegas_movimiento_tmp as a 
		JOIN inv_bodegas_movimiento_tmp_devolucion_proveedor as b ON (b.usuario_id = a.usuario_id)
		AND (b.doc_tmp_id = a.doc_tmp_id)
		JOIN inv_facturas_proveedores as d ON (b.numero_factura = d.numero_factura)
		AND (b.codigo_proveedor_id = d.codigo_proveedor_id)
		JOIN inv_bodegas_documentos as c ON (a.bodegas_doc_id = c.bodegas_doc_id)
		JOIN (
		SELECT
		x.codigo_proveedor_id,
		x.numero_factura,
		SUM(((x.valor/((x.porc_iva/100)+1))*x.cantidad)) as subtotal,
		SUM(((x.valor-(x.valor/((x.porc_iva/100)+1)))*x.cantidad)) as iva_total,
		SUM((x.valor * x.cantidad)) as total
		FROM
		inv_facturas_proveedores_d as x
		group by
		x.codigo_proveedor_id,
		x.numero_factura
		) as f ON (b.numero_factura = f.numero_factura)
		AND (b.codigo_proveedor_id = f.codigo_proveedor_id) 
		WHERE 
		a.usuario_id = $usuario_id
		AND a.doc_tmp_id = $doc_tmp_id
		AND c.bodegas_doc_id = a.bodegas_doc_id;";
        /*print_r($sql);*/
		//$dbconn->debug=true;
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

        if(empty($DATOS['codigo_proveedor_id']) || empty($DATOS['numero_factura']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "DATOS ADICIONALES DEL DOCUMENTO NO ESTAN LLENOS..";
            return false;
        }
		
		
        $sql = "INSERT INTO inv_bodegas_movimiento_devolucion_proveedor
                (
                    empresa_id,
                    prefijo,
                    numero,
                    codigo_proveedor_id,
                    numero_factura
                )
                VALUES
                (
                    '%empresa_id%',
                    '%prefijo%',
                    %numero%,
					          '".$DATOS['codigo_proveedor_id']."',
                    '".$DATOS['numero_factura']."'
                    
                );";
          
		//print_r($sql);
        //return false;
		return $this->Exec_CrearDocumento($doc_tmp_id, $sql, $usuario_id);
    }

}

?>