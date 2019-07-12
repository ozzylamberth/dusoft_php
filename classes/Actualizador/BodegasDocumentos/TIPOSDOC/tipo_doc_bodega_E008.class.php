<?php
/**
* $Id: tipo_doc_bodega_E008.class.php,v 1.1.1.1 2010/08/25 22:28:45 hugo Exp $
*/

/**
* Clase que implenta metodos de BodegasDocumentosComun
*
* Implementa metodos para documentos de bodega del tipo E008(EGRESO POR CONCEPTOS VARIOS)
*
* @author Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @version $Revision: 1.1.1.1 $
* @package SIIS
*/
class tipo_doc_bodega_E008 extends BodegasDocumentosComun
{
    /**
    * Constructor
    *
    * @param integer $bodegas_doc_id
    * @return boolean True si se ejecuto correctamnte.
    * @access public
    */
    function tipo_doc_bodega_E008($bodegas_doc_id)
    {
        $this->BodegasDocumentosComun($bodegas_doc_id);
        return true;
    }
    /**
    * Metodo para crear un documento temporal.
    * @param string  $observacion observacion del documento a crear
    * @param string  $tipo_id_farmaClie Indica si es una farmacia o un cliente
    * @param string  $pedido_farmacia Numero del pedido tanto del cliente como de la farmacia
    * @param string  $tipo_id_tercero
    * @param string  $tercero_id
    * @param string  $farmacia_id Identificador de la farmacia
    * @param integer $usuario_id integer (opcional)
    *
    * @return integer (doc_tmp_id del documento creado)
    * @access public
    */
    function NewDocTemporal($observacion='', $tipo_id_farmaClie, $pedido_farmacia,$tipo_id_tercero=null, $tercero_id=null,$farmacia_id=null,$usuario_id=null)
    {
      if(empty($this->bodegas_doc_id))
      {
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = "PARAMETRO DE CONSTRUCCION [bodegas_doc_id] ES REQUERIDO.";
        return false;
      }
      
      if($tipo_id_farmaClie=='1')
      {
        if(empty($pedido_farmacia))
        {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = "PARAMETRO [pedido_farmacia] ES REQUERIDO.";
          return false;
        }        
        else if(empty($farmacia_id))
        {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = "PARAMETRO [farmacia_id] ES REQUERIDO.";
          return false;
        }
      }
      
      if($tipo_id_farmaClie=='2')
      {
        if(empty($tipo_id_tercero)and empty($tercero_id))
        {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = "PARAMETRO [tercero] ES REQUERIDO.";
          return false;
        }
        else if(empty($pedido_farmacia))
        {
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = "PARAMETRO [pedido_farmacia] ES REQUERIDO.";
          return false;
        }
      }

      if(empty($usuario_id))
        $usuario_id = UserGetUID();

      GLOBAL $ADODB_FETCH_MODE;
      list($dbconn) = GetDBconn();

      $sql  = "SELECT (COALESCE(MAX(doc_tmp_id),0) + 1) ";
      $sql .= "FROM   inv_bodegas_movimiento_tmp ";
      $sql .= "WHERE  usuario_id = $usuario_id; ";
       
      $result = $dbconn->Execute($sql);

      if($dbconn->ErrorNo() != 0)
      {
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = $dbconn->ErrorMsg();
        return false;
      }

      $tipo_id_tercero_sql = ($tipo_id_tercero)? "'".$tipo_id_tercero."'":"NULL";
      $tercero_id_sql = ($tercero_id)? "'".$tercero_id."'":"NULL";

      list($doc_tmp_id) = $result->FetchRow();
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
                  ".$usuario_id.",
                  ".$doc_tmp_id.",
                  ".$this->bodegas_doc_id.",
                  '".substr(trim($observacion), 0, 255)."',
                  NOW()
                );";
      $dbconn->Execute($sql);
      $sql = "";
      if($tipo_id_farmaClie=='1')
      {
	  $info=explode("@",$farmacia_id);
	  $farmacia_id=$info[0];
	  $centro_utilidad=$info[1];
	  $bodega=$info[2];
        $sql = "INSERT INTO inv_bodegas_movimiento_tmp_despachos_farmacias
                (
                  usuario_id,
                  doc_tmp_id,
                  farmacia_id,
                  solicitud_prod_a_bod_ppal_id
                )
                VALUES
                (
                   ".$usuario_id.",
                   ".$doc_tmp_id.",
                  '".$farmacia_id."',
                   ".$pedido_farmacia."
                )";
      }
      else if($tipo_id_farmaClie=='2')
      {
        $sql = "INSERT INTO inv_bodegas_movimiento_tmp_despachos_clientes
                (
                  usuario_id,
                  doc_tmp_id,
                  tipo_id_tercero,
                  tercero_id,
                  pedido_cliente_id
                )
                VALUES
                (
                  ".$usuario_id.",
                  ".$doc_tmp_id.",
                  ".$tipo_id_tercero_sql.",
                  ".$tercero_id_sql.",
                  ".$pedido_farmacia."
                );";
      }
      if($sql != "")
      {
        $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0)
        {
          $dbconn->RollbackTrans();
          $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
          $this->mensajeDeError = $dbconn->ErrorMsg() . $sql ;
          return false;
        }
      }

      $dbconn->CommitTrans();
      
      $adicionales['numero_pedido'] = $pedido_farmacia;
      $adicionales['tipo_id_farmaClie'] = $tipo_id_farmaClie;
      
      return $this->GetDocTemporal($doc_tmp_id,$usuario_id,$adicionales);
    }
    /**
    * Metodo para obtener un documento temporal.
    *
    * @param $doc_tmp_id identificador del documento temporal
    * @param string $tipo_id_farmaClie Indica si es una farmacia o un cliente
    * @param integer $numero_p Numero del pedido
    * @param $usuario_id (opcional) identificador del creador del documento temporal
    *
    * @return array
    */
    function GetDocTemporal($doc_tmp_id,$usuario_id=null,$adicionales)
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

      if($adicionales['tipo_id_farmaClie'] =='1')
      {
        $sql="  SELECT  a.*,
                        b.farmacia_id,
                        b.solicitud_prod_a_bod_ppal_id,
                        c.documento_id,
                        c.empresa_id,
                        c.centro_utilidad,
                        c.bodega, b.rutaviaje_destinoempresa_id
                FROM    inv_bodegas_movimiento_tmp as a
                        LEFT JOIN inv_bodegas_movimiento_tmp_despachos_farmacias as b
                        ON (
                              b.usuario_id = a.usuario_id AND 
                              b.doc_tmp_id = a.doc_tmp_id AND 
                              b.solicitud_prod_a_bod_ppal_id = ".$adicionales['numero_pedido']."
                            ),
                        inv_bodegas_documentos as c
                WHERE   a.usuario_id = ".$usuario_id."
                AND     a.doc_tmp_id = ".$doc_tmp_id."
                AND     c.bodegas_doc_id = a.bodegas_doc_id ";
      }
      else if($adicionales['tipo_id_farmaClie'] == '2' )
      {
        $sql="  SELECT  a.*,
                        b.tipo_id_tercero,
                        b.tercero_id,
                        c.documento_id,
                        c.empresa_id,
                        c.centro_utilidad,
                        b.pedido_cliente_id,
                        c.bodega
                FROM    inv_bodegas_movimiento_tmp as a
                        LEFT JOIN inv_bodegas_movimiento_tmp_despachos_clientes as b
                        ON (
                              b.usuario_id = a.usuario_id AND 
                              b.doc_tmp_id = a.doc_tmp_id AND
                              b.pedido_cliente_id = ".$adicionales['numero_pedido']."
                            ),
                        inv_bodegas_documentos as c
                WHERE   a.usuario_id = ".$usuario_id."
                AND     a.doc_tmp_id = ".$doc_tmp_id."
                AND     c.bodegas_doc_id = a.bodegas_doc_id ";
      }
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
    * @param string $cliente Identificador del tipo de cliente (farmacia o cliente)
    * @param array $pedido Arreglo de datos con la informacion del pedido
    * @param integer $usuario_id (opcional) identificador del creador del documento temporal
    *
    * @return array
    */
    function CrearDocumento($doc_tmp_id,$cliente,$pedido,$usuario_id=null)
    {
      if(empty($doc_tmp_id))
      {
        $this->error = "-[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = "-PARAMETRO [doc_tmp_id] ES REQUERIDO.";
        return false;
      }

      if(empty($usuario_id))
        $usuario_id = UserGetUID();
      
      $numero_p = ($cliente == 1)? $pedido['solicitud_prod_a_bod_ppal_id'] : $pedido['pedido_cliente_id'];
      
      $adicionales['numero_pedido'] = $numero_p;
      $adicionales['tipo_id_farmaClie'] = $cliente;

      $DATOS = $this->GetDocTemporal($doc_tmp_id,$usuario_id,$adicionales);
        
      if($DATOS == false) return false;

      $DATOS['departamento'] = (empty($DATOS['departamento']))? "NULL": "'".$DATOS['departamento']."'";
      $DATOS['tipo_id_tercero'] = (empty($DATOS['tipo_id_tercero']))? "NULL":"'".$DATOS['tipo_id_tercero']."'";
      $DATOS['tercero_id'] = (empty($DATOS['tercero_id']))? "NULL":"'".$DATOS['tercero_id']."'";

      if($cliente == 1)
      {
        $sql = "INSERT INTO inv_bodegas_movimiento_despachos_farmacias
                (
                  empresa_id,
                  prefijo,
                  numero,
                  farmacia_id,
                  solicitud_prod_a_bod_ppal_id,
                  usuario_id,
                  fecha_registro,
                  sw_revisado,
                  rutaviaje_destinoempresa_id
                )
                VALUES
                (
                  '%empresa_id%',
                  '%prefijo%',
                  %numero%,
                  '".$DATOS['farmacia_id']."',
                  '".$DATOS['solicitud_prod_a_bod_ppal_id']."',
                  ".$DATOS['usuario_id'].",
                  '".$DATOS['fecha_registro']."',
                  '0',
                   '".$DATOS['rutaviaje_destinoempresa_id']."'
                );";
      }
      else
      {
				
		$sql .= "INSERT INTO inv_bodegas_movimiento_despachos_clientes
                (
                  empresa_id,
                  prefijo,
                  numero,
                  tipo_id_tercero,
                  tercero_id,
                  usuario_id,
                  fecha_registro,
                  pedido_cliente_id,
                  rutaviaje_destinoempresa_id
                )
                VALUES
                (
                    '%empresa_id%',
                    '%prefijo%',
                    %numero%,
                    ".$DATOS['tipo_id_tercero'].",
                    ".$DATOS['tercero_id'].",
                    ".$DATOS['usuario_id'].",
                    '".$DATOS['fecha_registro']."',
                    ".$DATOS['pedido_cliente_id'].",
                    '".$DATOS['rutaviaje_destinoempresa_id']."'
                );";
      }
	  
	  /*
		* Ingresa las Justificaciones de Productos pendientes por Despachar
		*/
		$sql .= " INSERT INTO inv_bodegas_movimiento_justificaciones_pendientes";
		$sql .= " (	";
		$sql .= " empresa_id,	";
		$sql .= " prefijo,	";
		$sql .= " numero,	";
		$sql .= " codigo_producto,	";
		$sql .= " cantidad_pendiente,	";
		$sql .= " observacion,	";
		$sql .= " existencia	";
		$sql .= " )	";
		$sql .= "SELECT	";
		$sql .= "	 '%empresa_id%' AS empresa_id,";
		$sql .= "	  '%prefijo%' AS prefijo,";
		$sql .= "	   %numero% AS numero,";
		$sql .= "	   codigo_producto,";
		$sql .= "	   cantidad_pendiente,";
		$sql .= "	   observacion,";
		$sql .= "	   existencia";
		$sql .= "	FROM";
		$sql .= "		inv_bodegas_movimiento_tmp_justificaciones_pendientes ";
		$sql .= "	WHERE ";
		$sql .= "	doc_tmp_id = '".$doc_tmp_id."' ";
		$sql .= "	AND usuario_id = '".UserGetUID()."'; ";
		
		/*
		* Ingresa las Autorizaciones de despachos de productos
		*/
		$sql .= " INSERT INTO inv_bodegas_movimiento_autorizaciones_despachos";
		$sql .= " (	";
		$sql .= " 	empresa_id,
						prefijo,
						numero,
						centro_utilidad,
						bodega,
						codigo_producto,
						lote,
						fecha_vencimiento,
						cantidad,
						porcentaje_gravamen,
						total_costo,
						fecha_registro,
						usuario_id_autorizador,
						observacion,
						fecha_autorizacion	";
		$sql .= " )	";
		$sql .= "SELECT	";
		$sql .= "	 	'%empresa_id%' AS empresa_id,";
		$sql .= "	  	'%prefijo%' AS prefijo,";
		$sql .= "	   	%numero% AS numero,";
		$sql .= "	   	centro_utilidad,
							bodega,
							codigo_producto,";
		$sql .= "	   	lote,
							fecha_vencimiento,
							cantidad,
							porcentaje_gravamen,
							total_costo,
							fecha_registro,
							usuario_id_autorizador,
							observacion,
							fecha_autorizacion ";
		$sql .= "	FROM";
		$sql .= "		inv_bodegas_movimiento_tmp_autorizaciones_despachos ";
		$sql .= "	WHERE TRUE";
		$sql .= "	AND sw_autorizado = '1' ";
		$sql .= "	AND doc_tmp_id = '".trim($doc_tmp_id)."' ";
		$sql .= "	AND usuario_id = '".UserGetUID()."'; ";
      return $this->Exec_CrearDocumento($doc_tmp_id, $sql, $usuario_id,$adicionales);
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
        //$dbconn->debug = true;
        
        $sql = "SELECT
                    a.*,
                    fc_descripcion_producto(b.codigo_producto) as descripcion,
                    b.unidad_id,
                    c.descripcion as descripcion_unidad,
                    a.lote,
                    a.fecha_vencimiento  
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

        $retorno = array();

        while($fila = $result->FetchRow())
        {
            $retorno[$fila['item_id']]=$fila;
        }
        $result->Close();

        return  $retorno;
    }
}
?>