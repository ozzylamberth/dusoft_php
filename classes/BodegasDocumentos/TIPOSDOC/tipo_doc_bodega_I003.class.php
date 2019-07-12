<?php
/**
* $Id: tipo_doc_bodega_I003.class.php,v 1.1 2011/05/30 22:22:34 mauricio Exp $
*/

/**
* Clase que implenta metodos de BodegasDocumentosComun
*
* Implementa metodos para documentos de bodega del tipo I003(EGRESO POR PRESTAMOS)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.1 $
* @package SIIS
*/
class tipo_doc_bodega_I003 extends BodegasDocumentosComun
{
    /**
    * Constructor
    *
    * @param integer $bodegas_doc_id
    * @return boolean True si se ejecuto correctamnte.
    * @access public
    */
    function tipo_doc_bodega_I003($bodegas_doc_id)
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
    function NewDocTemporal($observacion='', $coordinador_auxiliar=NULL,$control_interno=NULL,$fecha_selectivo=NULL, $usuario_id=null,$toma_fisica_id)
    {
        if(empty($this->bodegas_doc_id))
        {
            echo " NewDocTemporal 1";
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO DE CONSTRUCCION [bodegas_doc_id] ES REQUERIDO.";
            return false;
        }

        /*if(empty($tipo_id_tercero) || empty($tercero_id) || empty($tipo_prestamo_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO NECERARIOS [tipo_prestamo_id, tipo_id_tercero, tercero_id] SON REQUERIDOS.";
            return false;
        }*/

        list($dbconn) = GetDBconn();

        if(empty($usuario_id))
        {
            //echo " NewDocTemporal 2";
            $usuario_id = UserGetUID();
        }

        $sql = "SELECT (COALESCE(MAX(doc_tmp_id),0) + 1) FROM inv_bodegas_movimiento_tmp WHERE usuario_id = $usuario_id;";

        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
            echo " NewDocTemporal 3";
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }


        list($doc_tmp_id)=$result->FetchRow();
        $result->Close();

        $dbconn->BeginTrans();
        //echo " NewDocTemporal 4";
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

                INSERT INTO inv_bodegas_movimiento_tmp_ajustes
                    (
                        usuario_id,
                        doc_tmp_id,
                        coordinador_auxiliar,
                        control_interno,
                        fecha_selectivo,
						toma_fisica_id
                    )
                VALUES
                    (
                        $usuario_id,
                        $doc_tmp_id,
                        '$coordinador_auxiliar',
                        '$control_interno',
                        '$fecha_selectivo',
						".(($toma_fisica_id=="")? "NULL":$toma_fisica_id)."
                    );
        ";
     //   print_r($sql);
        $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {echo " NewDocTemporal 5";
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
						b.coordinador_auxiliar,
						b.control_interno,
						b.fecha_selectivo,
                        c.documento_id,
                        c.empresa_id,
                        c.centro_utilidad,
                        c.bodega,
						e.descripcion||'-'||d.descripcion as establecimiento,
						b.usuario_control_interno,
						b.usuario_jefe_bodega,
						CASE 
						WHEN usuario_control_interno IS NOT NULL
						AND usuario_jefe_bodega IS NOT NULL
						THEN '1'
						ELSE '0'
						END as autorizado,
						b.toma_fisica_id
                FROM
                    inv_bodegas_movimiento_tmp as a
                    LEFT JOIN inv_bodegas_movimiento_tmp_ajustes as b
                    ON (b.usuario_id = a.usuario_id AND b.doc_tmp_id = a.doc_tmp_id)
                    JOIN inv_bodegas_documentos as c ON (c.bodegas_doc_id = a.bodegas_doc_id)
					JOIN bodegas as d ON (c.empresa_id = d.empresa_id)
					AND (c.centro_utilidad = d.centro_utilidad)
					AND (c.bodega = d.bodega)
					JOIN centros_utilidad as e ON (d.empresa_id = e.empresa_id)
					AND (d.centro_utilidad = e.centro_utilidad)
                WHERE
                    a.usuario_id = $usuario_id
                    AND a.doc_tmp_id = $doc_tmp_id
                    
        ";
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
        //echo "I003 CrearDocumento ".$doc_tmp_id;
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

        /*print_r($DATOS);
		if(empty($DATOS['tipo_id_tercero']) || empty($DATOS['tercero_id']) || empty($DATOS['tipo_prestamo_id']))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "DATOS ADICIONALES DEL DOCUMENTO NO ESTAN LLENOS..";
            return false;
        }*/
		
		$sql = "INSERT INTO inv_bodegas_movimiento_ajustes
                (
                    empresa_id,
                    prefijo,
                    numero,
                    coordinador_auxiliar,
                    control_interno,
                    fecha_selectivo,
					tipo_ajuste,
					usuario_control_interno,
					usuario_jefe_bodega,
					toma_fisica_id
                )
                VALUES
                (
                    '%empresa_id%',
                    '%prefijo%',
                    %numero%,
					'".$DATOS['coordinador_auxiliar']."',
                    '".$DATOS['control_interno']."',
                    '".$DATOS['fecha_selectivo']."',
                    'I',
					'".(($DATOS['usuario_control_interno']=="")? '0':$DATOS['usuario_control_interno'])."',
					'".(($DATOS['usuario_jefe_bodega']=="")? '0':$DATOS['usuario_jefe_bodega'])."',
					".(($DATOS['toma_fisica_id']=="")? "NULL":$DATOS['toma_fisica_id'])."
                );";
                
              //  echo "sql ".$sql;
        //return false;
		return $this->Exec_CrearDocumento($doc_tmp_id, $sql, $usuario_id);
    }

}

?>