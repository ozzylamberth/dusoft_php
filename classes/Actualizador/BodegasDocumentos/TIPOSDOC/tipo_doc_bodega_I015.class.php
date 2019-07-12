<?php
/**
* $Id: tipo_doc_bodega_I015.class.php,v 1.1 2009/07/27 20:47:55 johanna Exp $
*/

/**
* Clase que implenta metodos de BodegasDocumentosComun
*
* Implementa metodos para documentos de bodega del tipo I015(EGRESO POR PRESTAMOS)
*
* @author Mauricio Medina -- mmedina@ipsoft-sa.com
* @version $Revision: 1.1 $
* @package SIIS
*/
class tipo_doc_bodega_I015 extends BodegasDocumentosComun
{
    /**
    * Constructor
    *
    * @param integer $bodegas_doc_id
    * @return boolean True si se ejecuto correctamnte.
    * @access public
    */
    function tipo_doc_bodega_I015($bodegas_doc_id)
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
    function NewDocTemporal($observacion='', $farmacia_id, $documento_farmacia, $usuario_id=null)
    {
        if(empty($this->bodegas_doc_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CONSTRUCCION [bodegas_doc_id] ES REQUERIDO.";
            return false;
        }

        if(empty($farmacia_id) || empty($documento_farmacia) )
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO NECERARIOS [farmacia_id, documento_farmacia] SON REQUERIDOS.";
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
        $codigo_farmacia=explode("@",$farmacia_id);
		$farmacia_id=$codigo_farmacia[0];
		$centro_utilidad=$codigo_farmacia[1];
		$bodega=$codigo_farmacia[2];
		
		
        list($prefijo,$numero) = explode("@",$documento_farmacia);
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

                INSERT INTO inv_bodegas_movimiento_tmp_ingresos_traslados_farmacia
                    (
                        usuario_id,
                        doc_tmp_id,
                        farmacia_id,
                        prefijo,
                        numero
                    )
                VALUES
                    (
                        $usuario_id,
                        $doc_tmp_id,
                        '$farmacia_id',
                        '$prefijo',
                        '$numero'
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

        $sql="  SELECT  a.*,
                        b.farmacia_id,
                        b.prefijo,
                        b.numero,
                        c.documento_id,
                        c.empresa_id,
                        c.centro_utilidad,
                        c.bodega,
                        g.razon_social||'-'||f.descripcion||'-'||e.descripcion as razon_social
                FROM
                    inv_bodegas_movimiento_tmp as a
                    LEFT JOIN inv_bodegas_movimiento_tmp_ingresos_traslados_farmacia as b
                    ON (b.usuario_id = a.usuario_id)
					AND (b.doc_tmp_id = a.doc_tmp_id)
                    LEFT JOIN inv_bodegas_movimiento as d ON (b.farmacia_id = d.empresa_id)
					AND (b.prefijo = d.prefijo)
					AND (b.numero = d.numero)
					JOIN bodegas as e ON (d.empresa_id = e.empresa_id)
					AND (d.centro_utilidad = e.centro_utilidad)
					AND (d.bodega = e.bodega)
					JOIN centros_utilidad as f ON (e.empresa_id = f.empresa_id)
					AND (e.centro_utilidad = f.centro_utilidad)
					JOIN empresas as g ON (f.empresa_id = g.empresa_id),
                    inv_bodegas_documentos as c
                WHERE
                    a.usuario_id = $usuario_id
                    AND a.doc_tmp_id = $doc_tmp_id
                    AND c.bodegas_doc_id = a.bodegas_doc_id
        ";
        /*$dbconn->debug=true;*/
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
        $PRODUCTOS = $this->GetItemsDocTemporal($doc_tmp_id, $usuario_id);
      
      foreach($PRODUCTOS as $key => $valor)
      {
      $mod .= " UPDATE inv_bodegas_movimiento_d ";
      $mod .= " SET 
	              cantidad_recibida = cantidad_recibida + ".$valor['cantidad']." ";
      $mod .= " Where ";
      $mod .= " movimiento_id = ".$valor['item_id_compras'].";"; 
      }


    
		if($DATOS==false) return false;

        if(empty($DATOS['farmacia_id']) || empty($DATOS['prefijo']) || empty($DATOS['numero']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "DATOS ADICIONALES DEL DOCUMENTO NO ESTAN LLENOS..";
            return false;
        }
        
		
        $sql = "INSERT INTO inv_bodegas_movimiento_ingresos_traslados_farmacia
                (
                    empresa_id,
                    prefijo,
                    numero,
                    farmacia_id,
                    prefijo_doc_farmacia,
                    numero_doc_farmacia
                )
                VALUES
                (
                    '%empresa_id%',
                    '%prefijo%',
                    %numero%,
					          '".$DATOS['farmacia_id']."',
                    '".$DATOS['prefijo']."',
                    ".$DATOS['numero']."
                    
                );";
                
          $sql .= $mod;
                
         
          
		//print_r($sql);
    //return false;
		return $this->Exec_CrearDocumento($doc_tmp_id, $sql, $usuario_id);
    }

}

?>