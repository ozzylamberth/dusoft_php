<?php
/**
* $Id: tipo_doc_bodega_I008.class.php,v 1.1.1.1 2010/08/25 22:28:45 hugo Exp $
*/

/**
* Clase que implenta metodos de BodegasDocumentosComun
*
* Implementa metodos para documentos de bodega del tipo I008(Compras directas)
*
* @author Mauricio Medina -- mmedina@ipsoft-sa.com
* @version $Revision: 1.1.1.1 $
* @package SIIS
*/
class tipo_doc_bodega_I008 extends BodegasDocumentosComun
{
    /**
    * Constructor
    *
    * @param integer $bodegas_doc_id
    * @return boolean True si se ejecuto correctamnte.
    * @access public
    */
    function tipo_doc_bodega_I008($bodegas_doc_id)
    {
        $this->BodegasDocumentosComun($bodegas_doc_id);
        return true;
    }


    /**
    * Metodo para crear un documento temporal.
    * @param string  $observacion observacion del documento a crear
    * @param string  $documento_compra identificador del documento de compra
    * @param date    $fecha_doc_compra  fecha del documento de compra
    * @param integer $usuario_id integer (opcional)
    * @return integer (doc_tmp_id del documento creado)
    * @access public
    */

    function NewDocTemporal($observacion='',$usuario_id=null,$doc_despacho)
    {
      if(empty($this->bodegas_doc_id))
      {
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = "PARAMETRO DE CONSTRUCCION [bodegas_doc_id] ES REQUERIDO.";
        return false;
      }

      
      $fecha_dev=explode("-",$fecha_doc_despacho);
      $fecha_devo=$fecha_dev[2]."-".$fecha_dev[1]."-".$fecha_dev[0];
      $todo_doc=explode("@",$doc_despacho);
      $empresa_id=$todo_doc[0];
      $prefijo_d=$todo_doc[1];
      $numero_d=$todo_doc[2];
     
      if(empty($doc_despacho) || $doc_despacho==0 )
      {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO NECERARIOS [ doc_despacho ] SON REQUERIDOS.";
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
                    fecha_registro,
                    abreviatura
                                     
                  )
              VALUES
                  (
                    $usuario_id,
                    $doc_tmp_id,
                    ".$this->bodegas_doc_id.",
                    '".substr(trim($observacion), 0, 255)."',
                    NOW(),
                    NULL
                  );

              INSERT INTO inv_bodegas_movimiento_tmp_ingresosdespachos_farmacia
                  (
                    usuario_id,
                    doc_tmp_id,
                    empresa_id,
                    numero,
                    prefijo
                  )
              VALUES
                  (
                    $usuario_id,
                    $doc_tmp_id,
                    '$empresa_id',
                    $numero_d,
                    '$prefijo_d'
                  );
      ";
//print_r($sql);
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

      $sql=" SELECT  a.*,
                     d.razon_social,
					 e.solicitud_prod_a_bod_ppal_id,
					 b.empresa_id as empresa_documento,
                     b.prefijo,
                     b.numero,
                     c.documento_id,
                     c.empresa_id,
                     c.centro_utilidad,
                     c.bodega,
					 f.observacion as observacion_despacho
              FROM   inv_bodegas_movimiento_tmp as a
              LEFT JOIN inv_bodegas_movimiento_tmp_ingresosdespachos_farmacia as b
                    ON (b.usuario_id = a.usuario_id AND b.doc_tmp_id = a.doc_tmp_id)
			  JOIN inv_bodegas_movimiento_despachos_farmacias as e ON (b.empresa_id = e.empresa_id)
			  AND (b.prefijo = e.prefijo)
			  AND (b.numero = e.numero)
			  JOIN inv_bodegas_movimiento as f ON (b.empresa_id = f.empresa_id)
			  AND (b.prefijo = f.prefijo)
			  AND (b.numero = f.numero)
              JOIN 	inv_bodegas_documentos as c ON (c.bodegas_doc_id = a.bodegas_doc_id)
			  JOIN 	empresas as d ON (b.empresa_id = d.empresa_id)
              WHERE  a.usuario_id = $usuario_id
              AND    a.doc_tmp_id = $doc_tmp_id
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
      $mod .= " 	codigo_producto = '".trim($valor['codigo_producto'])."' "; 
      $mod .= " AND	lote = '".trim($valor['lote'])."' "; 
      $mod .= " AND fecha_vencimiento = '".trim($valor['fecha_vencimiento'])."' "; 
      $mod .= " AND empresa_id = '".trim($DATOS['empresa_documento'])."' "; 
      $mod .= " AND prefijo = '".trim($DATOS['prefijo'])."' "; 
      $mod .= " AND numero = ".trim($DATOS['numero']).";"; 
      }
	  
	  if($DATOS==false) return false;
      
      $sql = "INSERT INTO inv_bodegas_movimiento_ingresosdespachos_farmacias
              (
                  empresa_id,
                  prefijo,
                  numero,
                  empresa_despacho,
                  prefijo_despacho,
                  numero_despacho
              )
              VALUES
              (
                  '%empresa_id%',
                  '%prefijo%',
                  %numero%,
                  '".$DATOS['empresa_documento']."',
                  '".$DATOS['prefijo']."',
                  '".$DATOS['numero']."'
              );";
     
       $sql .= $mod;
	   /*print_r($sql);*/
       return $this->Exec_CrearDocumento($doc_tmp_id, $sql, $usuario_id);
    }
	
	function GetNombreProyecto($empresa_id,$prefijo,$numero)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
		//$dbconn->debug=true;
        $sql="  SELECT  b.codigo_proyecto_cg,
						b.descripcion
                FROM
                    inv_bodegas_movimiento_consumo as a,
					inv_bodegas_movimiento_proyectos as b
                WHERE
                    a.empresa_id = '".$empresa_id."'
                    AND a.prefijo= '".$prefijo."'
                    AND a.numero = ".$numero."
					AND a.proyecto_id = b.proyecto_id
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
            $this->mensajeDeError = "EL DOCUMENTO NO TIENE ASOCIADO UN PROYECTO.";
            return false;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        return  $retorno;

    }
}
?>