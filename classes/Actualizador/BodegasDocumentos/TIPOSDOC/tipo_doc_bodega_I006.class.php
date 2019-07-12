<?php
/**
* $Id: tipo_doc_bodega_I006.class.php,v 1.1.1.1 2010/08/25 22:28:45 hugo Exp $
*/

/**
* Clase que implenta metodos de BodegasDocumentosComun
*
* Implementa metodos para documentos de bodega del tipo I006(Compras directas)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.1.1.1 $
* @package SIIS
*/
class tipo_doc_bodega_I006 extends BodegasDocumentosComun
{
    /**
    * Constructor
    *
    * @param integer $bodegas_doc_id
    * @return boolean True si se ejecuto correctamnte.
    * @access public
    */
    function tipo_doc_bodega_I006($bodegas_doc_id)
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

    function NewDocTemporal($observacion='', $documento_devolucion, $fecha_doc_devolucion, $usuario_id=null,$tipo_id_tercero,$tercero_id)
    {
      if(empty($this->bodegas_doc_id))
      {
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = "PARAMETRO DE CONSTRUCCION [bodegas_doc_id] ES REQUERIDO.";
        return false;
      }

      if(empty($documento_devolucion) || empty($fecha_doc_devolucion))
      {
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = "PARAMETRO NECERARIOS [documento_devolucion, fecha_doc_devolucion] PARA EL TIPO DE DOCUMENTO [bodegas_doc_id] SON REQUERIDOS.";
        return false;
      }
      
      $fecha_dev=explode("-",$fecha_doc_devolucion);
      $fecha_devo=$fecha_dev[2]."-".$fecha_dev[1]."-".$fecha_dev[0];
      $todo_doc=explode("-",$documento_devolucion);
      $empresa_id=$todo_doc[0];
      $prefijo_d=$todo_doc[1];
      $numero_d=$todo_doc[2];
      //$datos['fecha_inicio'] = $request['fecha_inicio'];
       //$fdatos=explode("-", $datos['fecha_inicio']);
       //$fedatos= $fdatos[2]."/".$fdatos[1]."/".$fdatos[0];
      //print_r($fecha_devo."dshdhakjdhkjsahdyu");
      if(empty($tipo_id_tercero) || empty($tercero_id) )
      {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETRO NECERARIOS [ tipo_id_tercero, tercero_id] SON REQUERIDOS.";
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

              INSERT INTO inv_bodegas_movimiento_tmp_devoluciones
                  (
                    usuario_id,
                    doc_tmp_id,
                    documento_devolucion_01,
                    fecha_doc_devolucion,
                    tipo_id_tercero,
                    tercero_id,
                    empresa_id,
                    numero,
                    prefijo
                  )
              VALUES
                  (
                    $usuario_id,
                    $doc_tmp_id,
                    default,
                    '$fecha_devo',
                    '$tipo_id_tercero',
                    '$tercero_id',
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
                     b.documento_devolucion_01,
                     b.fecha_doc_devolucion,
                     b.tipo_id_tercero,
                     b.tercero_id,
					 d.nombre_tercero,
                     b.prefijo,
                     b.numero,
                     c.documento_id,
                     c.empresa_id,
                     c.centro_utilidad,
                     c.bodega
              FROM   inv_bodegas_movimiento_tmp as a
              LEFT JOIN inv_bodegas_movimiento_tmp_devoluciones as b
                    ON (b.usuario_id = a.usuario_id AND b.doc_tmp_id = a.doc_tmp_id)
              JOIN 	inv_bodegas_documentos as c ON (c.bodegas_doc_id = a.bodegas_doc_id)
			  JOIN 	terceros as d ON (b.tipo_id_tercero = d.tipo_id_tercero)
                     AND (b.tercero_id = d.tercero_id)
              WHERE  a.usuario_id = $usuario_id
              AND    a.doc_tmp_id = $doc_tmp_id
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
      $mod .= " 		codigo_producto = '".$valor['codigo_producto']."' "; 
      $mod .= " AND empresa_id = '".$DATOS['empresa_id']."' "; 
      $mod .= " AND prefijo = '".$DATOS['prefijo']."' "; 
      $mod .= " AND numero = ".$DATOS['numero'].";"; 
      }
      if($DATOS==false) return false;
      
      $sql = "INSERT INTO inv_bodegas_movimiento_ing_dev_prestamos
              (
                  empresa_id,
                  prefijo,
                  numero,
                  prefijo_prestamo,
                  numero_prestamo,
                  tipo_id_tercero,
                  tercero_id
              )
              VALUES
              (
                  '%empresa_id%',
                  '%prefijo%',
                  %numero%,
                  '".$DATOS['prefijo']."',
                  '".$DATOS['numero']."',
                  '".$DATOS['tipo_id_tercero']."',
                   '".$DATOS['tercero_id']."'
              );";
     
       $sql .= $mod;
	   
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