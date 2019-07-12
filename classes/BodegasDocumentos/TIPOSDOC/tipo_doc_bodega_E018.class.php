<?php
/**
* $Id: tipo_doc_bodega_E018.class.php,v 1.1 2009/07/27 20:47:55 johanna Exp $
*/

/**
* Clase que implenta metodos de BodegasDocumentosComun
*
* Implementa metodos para documentos de bodega del tipo E018
*
* @author Mauricio Medina -- mmedina@ipsoft-sa.com
* @version $Revision: 1.1 $
* @package SIIS
*/
class tipo_doc_bodega_E018 extends BodegasDocumentosComun
{
    /**
    * Constructor
    *
    * @param integer $bodegas_doc_id
    * @return boolean True si se ejecuto correctamnte.
    * @access public
    */
    function tipo_doc_bodega_E018($bodegas_doc_id)
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
    function NewDocTemporal($observacion='',$plan_id,$tipo_formula_id,$requisicion, $usuario_id=null)
    {
        if(empty($this->bodegas_doc_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CONSTRUCCION [bodegas_doc_id] ES REQUERIDO.";
            return false;
        }

        if(empty($plan_id) || empty($tipo_formula_id) || empty($requisicion))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO NECERARIOS [plan_id] [tipo_formula_id] [requisicion] SON REQUERIDOS.";
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
        
        list($prefijo,$numero) = explode("@",$documento_farmacia);
		
		$codigo_farmacia=explode("@",$farmacia_id);
		$farmacia_id=$codigo_farmacia[0];
		$centro_utilidad=$codigo_farmacia[1];
		$bodega=$codigo_farmacia[2];
		
		
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

                INSERT INTO inv_bodegas_movimiento_tmp_distribucion
                    (
                        usuario_id,
                        doc_tmp_id,
                        plan_id,
                        tipo_formula_id,
                        requisicion
                       
                    )
                VALUES
                    (
                        $usuario_id,
                        $doc_tmp_id,
                        '".trim($plan_id)."',
                        '".trim($tipo_formula_id)."',
                        '".trim($requisicion)."'
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
                        b.plan_id,
						h.plan_descripcion,
						b.tipo_formula_id,
						b.requisicion,
                        c.documento_id,
                        c.empresa_id,
                        c.centro_utilidad,
                        c.bodega,
						g.descripcion_tipo_formula,
						CASE
						WHEN e.tipo_formula_id IS NOT NULL AND d.tipo_formula_id IS NULL AND ('1' = f.sw_topes)
						THEN e.tope_mensual
						WHEN e.tipo_formula_id IS NOT NULL AND d.tipo_formula_id IS NOT NULL AND ('1' = f.sw_topes)
						THEN d.saldo
						END as tope
                FROM
                    inv_bodegas_movimiento_tmp as a
                    JOIN inv_bodegas_movimiento_tmp_distribucion as b ON (b.usuario_id = a.usuario_id)
					AND (b.doc_tmp_id = a.doc_tmp_id)
					JOIN inv_bodegas_documentos as c ON (a.bodegas_doc_id = c.bodegas_doc_id)
					LEFT JOIN esm_topes_dispensacion_mensual AS d ON (b.tipo_formula_id = d.tipo_formula_id)
				   AND (c.empresa_id = d.empresa_id)
				   AND (c.centro_utilidad = d.centro_utilidad)
				   AND (d.lapso = '".(date('Ym'))."')
				   LEFT JOIN esm_topes_dispensacion as e ON (b.tipo_formula_id = e.tipo_formula_id)
				   AND (c.empresa_id = e.empresa_id)
				   AND (c.centro_utilidad = e.centro_utilidad)
				   JOIN centros_utilidad as f ON(c.empresa_id = f.empresa_id)
				   AND (c.centro_utilidad = f.centro_utilidad)
				   JOIN esm_tipos_formulas as g ON (b.tipo_formula_id = g.tipo_formula_id)
				   JOIN planes as h ON (b.plan_id = h.plan_id)
                WHERE
                    a.usuario_id = $usuario_id
                    AND a.doc_tmp_id = $doc_tmp_id ";
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
		if($DATOS==false) return false;
	
		
        $sql = "INSERT INTO inv_bodegas_movimiento_distribucion
                (
                    empresa_id,
                    prefijo,
                    numero,
                    plan_id,
                    tipo_formula_id,
                    requisicion
                )
                VALUES
                (
                    '%empresa_id%',
                    '%prefijo%',
                    %numero%,
					'".trim($DATOS['plan_id'])."',
					'".($DATOS['tipo_formula_id'])."',
					'".($DATOS['requisicion'])."'
                );";
          
		//return false;
		return $this->Exec_CrearDocumento($doc_tmp_id, $sql, $usuario_id);
    }

}

?>