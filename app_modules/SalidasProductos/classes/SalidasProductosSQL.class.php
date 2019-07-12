<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: SalidasProductosSQL.class.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  /**
  * Clase : SalidasProductosSQL
  * 
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
IncludeClass('BodegasDocumentos');
class SalidasProductosSQL extends ConexionBD
{
  /**
       * Constructor de la clase
       */
  function SalidasProductosSQL(){}
    
  /**
       * Funcion donde se verifica el permiso del usuario para el ingreso al modulo
       *
       * @return array $datos vector que contiene la informacion de la consulta del codigo de
       * la empresa y la razon social
       */ 
  function ObtenerPermisos()
  {
    //$this->debug = true;
    $sql  = "SELECT   EM.empresa_id AS empresa, ";
    $sql .= "         EM.razon_social AS razon_social ";
    $sql .= "FROM     userpermisos_parametrizadocumentosbode CP, empresas EM ";
    $sql .= "WHERE    CP.usuario_id = ".UserGetUID()." ";
    $sql .= "         AND CP.empresa_id = EM.empresa_id ";
      
    if(!$rst = $this->ConexionBaseDatos($sql))
      return false;
    
    $datos = array();
    while(!$rst->EOF)
    {
      $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    } 
    $rst->Close();
    return $datos;
  }
  
  /**
       * Funcion donde busca los documentos en salidas_productos_tmp
       *
       * @return booleano
       */
  function BuscarDoc()
  {
    //$this->debug = true;
    $sql  = "SELECT   a.* ";
    $sql .= "FROM     salidas_productos_tmp a, inv_bodegas_movimiento_tmp b ";
    $sql .= "WHERE    a.doc_tmp_id = b.doc_tmp_id ";
     
    if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  }
  
  /**
      * Funcion donde se consulta todos las productos en existencias bodegas
      *
      * @param  var $empresa contiene la empresa
      * @return booleano
      */
  function ListarProductos($empresa,$offset)
  {
    //$this->debug=true;
    $sql  = "SELECT	DISTINCT a.codigo_producto,a.*,b.descripcion as descripcion_prod,c.costo ";
    $sql .= "FROM		existencias_bodegas_lote_fv a, ";
    $sql .= "  		      inventarios_productos as b,inventarios as c ";
    $sql .= "WHERE  a.empresa_id='".$empresa."' ";
    $sql .= "AND    a.codigo_producto=b.codigo_producto ";
    $sql .= "AND    b.codigo_producto=c.codigo_producto ";
    $sql .= "AND    a.empresa_id = c.empresa_id ";
    $sql .= "AND    a.existencia_actual > 0  ";
    
    
    //$cont="select COUNT(*) from (".$sql.") AS A";
    
    if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "ORDER BY descripcion_prod ";
    
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
    
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
  }
  
  function ProductosTMP($doc_tmp_id)
  {
    //$this->debug = true;
    $sql .= " SELECT a.*,b.descripcion ";
    $sql .= " FROM   inv_bodegas_movimiento_tmp_d  as a, ";
    $sql .= "        inventarios_productos  as b ";
    $sql .= " WHERE  a.doc_tmp_id = ".$doc_tmp_id." ";           
    $sql .= " AND    a.codigo_producto =b.codigo_producto ";   
    if(!$rst = $this->ConexionBaseDatos($sql))
     return false;
      
    $datos = array();
    while(!$rst->EOF)
    {
      $datos[] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    } 
    $rst->Close();
    return $datos;
  }
  /**
      * Funcion donde se consulta todos documentos dependiendo del prefijo
      *
      * @param  var $empresa contiene la empresa
      * @param  var $prefijo contiene el prefijo del documento
      * @return booleano
      */
  function SacarDocumento($empresa_id,$prefijo,$numero)
  {
    //$this->debug = true;
    $sql .= " SELECT m.*, ";
    $sql .= "        c.inv_tipo_movimiento as tipo_movimiento, ";
    $sql .= "        b.tipo_doc_general_id as tipo_doc_bodega_id, "; 
    $sql .= "        c.descripcion as tipo_clase_documento, ";
    $sql .= "        b.descripcion ";
    $sql .= " FROM   inv_bodegas_movimiento as m, ";
    $sql .= "        inv_bodegas_documentos as a, ";
    $sql .= "        documentos as b, ";
    $sql .= "        tipos_doc_generales as c ";
    $sql .= " WHERE  m.empresa_id = '".$empresa_id."' ";           
    $sql .= " AND    m.prefijo = '".$prefijo."' "; 
    $sql .= " AND    m.numero = ".$numero." ";    
    $sql .= " AND    a.documento_id = m.documento_id ";
    $sql .= " AND    a.empresa_id = m.empresa_id ";
    $sql .= " AND    a.centro_utilidad = m.centro_utilidad ";
    $sql .= " AND    a.bodega = m.bodega ";
    $sql .= " AND    b.documento_id = a.documento_id ";
    $sql .= " AND    b.empresa_id = a.empresa_id ";
    $sql .= " AND    c.tipo_doc_general_id = b.tipo_doc_general_id; ";
    if(!$rst = $this->ConexionBaseDatos($sql))
     return false;
      
    $datos = array();
    while(!$rst->EOF)
    {
      $datos[] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    } 
    $rst->Close();
    return $datos;
  }
  
  /**
      * Funcion donde se consulta todos documentos dependiendo del prefijo
      *
      * @param  var $empresa contiene la empresa
      * @param  var $prefijo contiene el prefijo del documento
       * @param  var $numero contiene el numero del documento
      * @return booleano
      */
  function SacarDocumento1($empresa_id,$prefijo,$numero)
  {
    //$this->debug = true;
    $sql .= " SELECT  m.*, ";                
    $sql .= "         c.inv_tipo_movimiento as tipo_movimiento, ";
    $sql .= "         b.tipo_doc_general_id as tipo_doc_bodega_id, ";
    $sql .= "         c.descripcion as tipo_clase_documento, ";
    $sql .= "         b.descripcion ";
    $sql .= " FROM    inv_bodegas_movimiento as m, ";
    $sql .= "         inv_bodegas_documentos as a, ";
    $sql .= "         documentos as b, ";
    $sql .= "         tipos_doc_generales as c ";
    $sql .= " WHERE   m.empresa_id = '".$empresa_id."' ";
    $sql .= " AND     m.prefijo = '".$prefijo."' ";
    $sql .= " AND     m.numero = ".$numero." ";
    $sql .= " AND     a.documento_id = m.documento_id ";
    $sql .= " AND     a.empresa_id = m.empresa_id ";
    $sql .= " AND     a.centro_utilidad = m.centro_utilidad ";
    $sql .= " AND     a.bodega = m.bodega ";
    $sql .= " AND     b.documento_id = a.documento_id ";
    $sql .= " AND     b.empresa_id = a.empresa_id ";
    $sql .= " AND     c.tipo_doc_general_id = b.tipo_doc_general_id; ";
    
    if(!$rst = $this->ConexionBaseDatos($sql))
      return false;
      
    $datos = array();
    while(!$rst->EOF)
    {
      $datos[] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    } 
    $rst->Close();
    return $datos;
  }
  /**
      * Funcion donde se consulta todos los detalles dependiendo del prefijo
      *
      * @param  var $empresa contiene la empresa
      * @param  var $prefijo contiene el prefijo del documento
       * @param  var $numero contiene el numero del documento
      * @return booleano
      */
  function SacarDocDetalle($empresa_id,$prefijo,$numero)
  {
    //$this->debug = true;
    $sql .= " SELECT a.*, ";
    $sql .= "        b.descripcion, ";
    $sql .= "        b.unidad_id, ";
    $sql .= "        c.descripcion as descripcion_unidad ";
    $sql .= " FROM   inv_bodegas_movimiento_d as a, ";
    $sql .= "        inventarios_productos as b, ";
    $sql .= "        unidades as c ";
    $sql .= " WHERE  a.empresa_id = '".$empresa_id."' ";
    $sql .= " AND    a.prefijo = '".$prefijo."' ";
    $sql .= " AND    a.numero = ".$numero." ";
    $sql .= " AND    b.codigo_producto = a.codigo_producto ";
    $sql .= " AND c.unidad_id = b.unidad_id; ";
                
    if(!$rst = $this->ConexionBaseDatos($sql))
      return false;
     
    $datos = array();
    while(!$rst->EOF)
    {
      $datos[] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    } 
    $rst->Close();
    return $datos;
  }
  
  /**
       * Funcion donde se guarda el archivo de imagenes
       *
       * @return booleano
       */
  function Insertar($nombre, $size, $type, $buffer,$contratoca,$tipood,$Noid,$empresa_id,$numero)
  {
    //$this->debug = true;
    $indice = array();

    $sql = "SELECT NEXTVAL('archivo_docue_codigo_archivo_seq') AS sq ";

    if(!$rst = $this->ConexionBaseDatos($sql)) 
    return false;
    if(!$rst->EOF)
    {
      $indice = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();     
    }
    $rst->Close(); 

    $sqlerror = "SELECT setval('archivo_docue_codigo_archivo_seq', ".($indice['sq']-1).") ";    
    $this->ConexionTransaccion();

    $sql  = "INSERT INTO archivo_docuE(   ";
    $sql .= "       codigo_archivo, ";
    $sql .= "       empresa_id, ";
    $sql .= "       tipo_doc_bodega_id, ";
    $sql .= "       documento_id, ";
    $sql .= "       numero, ";
    $sql .= "       prefijo, ";
    $sql .= "       archivo_nombre, ";
    $sql .= "       archivo_peso, ";
    $sql .= "       archivo_tipo, ";
    $sql .= "       archivo_bytea, ";
    $sql .= "       usuario_registro,   ";
    $sql .= "       fecha_registro ";
    $sql .= ")VALUES( ";
    $sql .= "       ".$indice['sq'].", ";
    $sql .= "       '".$empresa_id."', ";
    $sql .= "       '".$tipood."', ";
    $sql .= "       '".$Noid."', ";
    $sql .= "       ".$numero.", ";
    $sql .= "       '".$contratoca."', ";
    $sql .= "       '".$nombre."', ";
    $sql .= "       ".$size.", ";
    $sql .= "       '".$type."', ";
    $sql .= "       '".$buffer. "' , ";
    $sql .= "       ".UserGetUID().", ";
    $sql .= "       NOW() ); ";
    if(!$rst = $this->ConexionTransaccion($sql))
    {
      if(!$rst = $this->ConexionTransaccion($sqlerror)) 
      return false;      
    }    
    else
    {
      $this->Commit();
      return true;
    }
  }
  
  /**
       * Funcion donde busca los documentos en salidas_productos_tmp
       *
       * @return booleano
       */
  function doc_tmp_id()
  {
    //$this->debug=true;
    
    $sql  = "SELECT (COALESCE(MAX(b.doc_tmp_id),0) + 1) 
             FROM    inv_bodegas_movimiento_tmp a,salidas_productos_tmp b 
             WHERE   a.usuario_id = ".UserGetUID()."
             AND     a.usuario_id = b.usuario_registro ;";
    if(!$rst = $this->ConexionBaseDatos($sql))
     return false;

    $datos = array();
    if(!$rst->EOF)
    {
     $datos = $rst->GetRowAssoc($ToUpper);
      $rst->MoveNext();
    }
    return $datos;
  }
  
    /**
       * Funcion donde busca los documentos en salidas_productos_tmp
       *
       * @return booleano
       */
  function ProductosTemporal($doc_tmp_id,$empresa_id,$codigo_producto)
  {
    //$this->debug=true;
    
    $sql  = " SELECT  * ";
    $sql .= " FROM    inv_bodegas_movimiento_tmp_d "; 
    $sql .= " WHERE  doc_tmp_id = ".$doc_tmp_id." ";
    $sql .= " AND      empresa_id = '".$empresa_id."' ";
    $sql .= " AND      codigo_producto = '".$codigo_producto."' ;";
    if(!$rst = $this->ConexionBaseDatos($sql))
     return false;

    $datos = array();
    if(!$rst->EOF)
    {
     $datos = $rst->GetRowAssoc($ToUpper);
      $rst->MoveNext();
    }
    return $datos;
  }
  
  /**
       * Funcion donde busca los documentos en salidas_productos_tmp
       *
       * @return booleano
       */
  function Doc_tmp($doc_tmp_id)
  {
    //$this->debug=true;
    $sql  = "SELECT * FROM inv_bodegas_movimiento_tmp_d WHERE doc_tmp_id 	 = ".$doc_tmp_id." ;";
    if(!$rst = $this->ConexionBaseDatos($sql))
     return false;

    $datos = array();
    if(!$rst->EOF)
    {
     $datos = $rst->GetRowAssoc($ToUpper);
      $rst->MoveNext();
    }
    return $datos;
  }
  /**
       * Funcion donde busca las validaciones de jefe bodega y jefe de control interno
       *
       * @return booleano
       */
  function validar_jefes($doc_tmp_id)
  {
    //$this->debug=true;
    $sql  = "SELECT * FROM vald_jefedoctmp WHERE doc_tmp_id = ".$doc_tmp_id." ;";
    if(!$rst = $this->ConexionBaseDatos($sql))
     return false;

    $datos = array();
    if(!$rst->EOF)
    {
     $datos = $rst->GetRowAssoc($ToUpper);
     $rst->MoveNext();
    }
    return $datos;
  }
 
  /**
       * Funcion donde se crea un documento temporal
       *
       * @return booleano
       */
  function CrearDoc($doc_tmp_id,$empresa_id)
  {
    $bodegas_doc_id= ModuloGetVar('app','SalidasProductos','documento_salida_'.$empresa_id);
    $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
    $OBJETO=$ClassDOC->GetOBJ();
    //print_r($OBJETO);
    $RETORNO=$OBJETO->NewDocTemporal();
    //print_r($RETORNO);
    $this->ConexionTransaccion();
    //$this->debug=true;
    $sql .= " INSERT INTO salidas_productos_tmp ( ";
    $sql .= "             doc_tmp_id,empresa_id,usuario_registro, ";
    $sql .= "             fecha_registro) ";
    $sql .= " VALUES (                    "; 
    $sql .= "             ".$RETORNO['doc_tmp_id'].", ";
    $sql .= "             '".$RETORNO['empresa_id']."', ";
    $sql .= "             ".$RETORNO['usuario_id'].", ";
    $sql .= "             NOW() ); ";
    
    $sql .= " INSERT INTO vald_jefedoctmp ( ";
    $sql .= "             id_vald_jefedoctmp , ";
    $sql .= "             doc_tmp_id, ";
    $sql .= "             sw_jefebodega, ";
    $sql .= "             sw_jefecontroli, ";
    $sql .= "             empresa_id, ";
    $sql .= "             usuario_registro, ";
    $sql .= "             fecha_registro) ";
    $sql .= " VALUES (                    ";    
    $sql .= "             default, ";
    $sql .= "             ".$RETORNO['doc_tmp_id'].", ";
    $sql .= "             0, ";
    $sql .= "             0, ";
    $sql .= "             '".$RETORNO['empresa_id']."', ";
    $sql .= "             ".$RETORNO['usuario_id'].", ";
    $sql .= "             NOW() ) ";
     //print_r($sql);  
    if(!$rst = $this->ConexionTransaccion($sql))
    {
      echo $this->mensajeDeError;
      return false;
    }
    $this->Commit();
    return true;
  }
  
  /**
       * Funcion donde se elimina el documento temporal.
       *
       * @return booleano
       */
  function Borrarpara_docs($doc_tmp_id)
  {
    //$this->debug=true;
    
    $sql  = "DELETE FROM vald_jefedoctmp ";
    $sql .= "WHERE       doc_tmp_id ='".$doc_tmp_id."'; ";
    
    $sql .= "DELETE FROM salidas_productos_tmp ";
    $sql .= "WHERE       doc_tmp_id =".$doc_tmp_id."; ";
       
    //print_r($sql);
    if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
    $documentos=Array();
    if(!$resultado->EOF)
    {
      $documentos = $resultado->GetRowAssoc($ToUpper = false);
      $resultado->MoveNext();
    }  
    $resultado->Close();
    // return $sql;
    return $documentos;
  }
  
  /**
       * Funcion donde se crea un documento detalle temporal
       *
       * @return booleano
       */
  function CrearDocTmp($doc_tmp_id,$codigo_producto,$cantidad,$porcentaje_gravamen,$total_costo,$fecha_venc,$lotec,$empresa_id)
  {
    $bodegas_doc_id= ModuloGetVar('app','SalidasProductos','documento_salida_'.$empresa_id);
    //102;
    $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
    $fecha = explode("-",$fecha_venc);
    $fecha_vencimiento=$fecha[2]."-".$fecha[1]."-".$fecha[0];
    $OBJETO=$ClassDOC->GetOBJ();
   // ($doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id=null,$fecha_venc,$lotec,$localizacion,$total_costo_ped,$valor_unitario)
    $RETORNO=$OBJETO->AddItemDocTemporal($doc_tmp_id,$codigo_producto,$cantidad,0,$total_costo,$usuario_id=null,$fecha_vencimiento,$lotec,null,0,0);
    $this->mensajeDeError = $OBJETO->mensajeDeError;
    return $RETORNO;
  }
  
   /**
       * Funcion donde se crea un documento real 
       *
       * @return booleano
       */
  function CrearDocReal($doc_tmp_id,$empresa_id)
  {
    $bodegas_doc_id= ModuloGetVar('app','SalidasProductos','documento_salida_'.$empresa_id);
    $ClassDOC= new BodegasDocumentos();
    $OBJETO=$ClassDOC->GetOBJ($bodegas_doc_id);
    
    $resultado=$OBJETO->CrearDocumento($doc_tmp_id,UserGetUID());
    
    return $resultado;
    //return true;
  }
}
?>