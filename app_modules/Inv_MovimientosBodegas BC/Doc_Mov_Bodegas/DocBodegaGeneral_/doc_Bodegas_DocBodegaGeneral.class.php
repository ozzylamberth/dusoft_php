<?php
  /******************************************************************************
  * $Id: doc_Bodegas_DocBodegaGeneral.class.php,v 1.1 2009/07/17 19:08:17 johanna Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.1 $ 
  * 
  * @autor Jaime Gomez
  ********************************************************************************/

if (!IncludeClass('BodegasDocumentos'))
{
    die(MsgOut("Error al incluir archivo","BodegasDocumentos"));
}

class DocBodegaGeneral
{

  function DatosParaEditar($tmp_doc_id,$usuario_id)
  {
    $ClassDOC= new BodegasDocumentos();
    $datos=$ClassDOC->GetInfoBodegaDocumentoTMP($tmp_doc_id,$usuario_id);
    return $datos;
  }
  
  function EliminarItem($tr,$item)
  {
    list($bodegas_doc_id,$i) = explode("@",$tr);
    $ClassDOC= new BodegasDocumentos();
    $OBJETO=$ClassDOC->GetOBJ($bodegas_doc_id);
    $resultado=$OBJETO->DelItemDocTemporal($item);
    return $resultado;
  }
  
//   function SacarProductosTMP($doc_tmp_id,$usuario_id)
//    {   
//        $ClassDOC= new BodegasDocumentos();
//        $datos=$ClassDOC->GetInfoBodegaDocumentoTMP($doc_tmp_id,$usuario_id);
//        $OBJETO=$ClassDOC->GetOBJ($datos['bodegas_doc_id']);
//        $tabla_de_productos=$OBJETO->GetItemsDocTemporal($doc_tmp_id,$usuario_id);
//        return $tabla_de_productos;
//   }

  function SacarProductosTMP($doc_tmp_id,$usuario_id)//
   {
       $ClassDOC= new BodegasDocumentos();
       $datos=$ClassDOC->GetInfoBodegaDocumentoTMP($doc_tmp_id,$usuario_id);
       IF(!EMPTY($datos))
       {
          $OBJETO=$ClassDOC->GetOBJ($datos['bodegas_doc_id']);
          $tabla_de_productos=$OBJETO->GetItemsDocTemporal($doc_tmp_id,$usuario_id);
          return $tabla_de_productos;
       }
       else
       {
         var_dump($ClassDOC->ErrMsg());

       }
  }
    
    function CrearDoc($bodegas_doc_id, $observacion)
    {
       $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
       $OBJETO=$ClassDOC->GetOBJ();
       $RETORNO=$OBJETO->NewDocTemporal($observacion,UserGetUID());
      //    $RETORNO=$OBJETO->GetInfoBodegaDocumento($bodegas_doc_id);
       //var_dump($RETORNO);
        if(!is_object($ClassDOC))
        {
            die(MsgOut("Error al crear la clase","BodegasDocumentos"));
        }
     return $RETORNO;
    }


    function TraerDatos($bodegas_doc_id)
    {
       $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
       $OBJETO=$ClassDOC->GetOBJ();
       $RETORNO=$OBJETO->GetInfoBodegaDocumento($bodegas_doc_id);
       //var_dump($RETORNO);
       if(!is_object($ClassDOC))
        {
            die(MsgOut("Error al crear la clase","BodegasDocumentos"));
        }
     return $RETORNO;

    }
    function BuscarProducto($empresa_id,$centro_utilidad,$bodega,$aumento,$aumento2,$offset)
    {
         $sql=" SELECT 

                  b.codigo_producto,
                  b.descripcion,
                  b.unidad_id,
                  c.descripcion as descripcion_unidad,
                  e.existencia_actual AS existencia,
                  d.costo,
                  e.fecha_vencimiento,
                  e.lote,
                  b.contenido_unidad_venta,
                  g.descripcion as laboratorio
              FROM
                  existencias_bodegas_lote_fv as e
                  JOIN existencias_bodegas as a 
                       ON(e.empresa_id = a.empresa_id)
                       and (e.centro_utilidad = a.centro_utilidad)
                       and (e.bodega = a.bodega)
                       and (e.codigo_producto = a.codigo_producto)
                  JOIN inventarios as d
                       ON (e.empresa_id = d.empresa_id)
                       and (e.codigo_producto = d.codigo_producto) 
                  JOIN inventarios_productos as b 
                       ON(d.codigo_producto = b.codigo_producto)
                  JOIN unidades as c
                       ON (b.unidad_id = c.unidad_id)
                  JOIN inv_subclases_inventarios as z
                       ON (b.grupo_id = z.grupo_id)
                       and (b.clase_id = z.clase_id)
                       and (b.subclase_id = z.subclase_id)
                  JOIN inv_clases_inventarios as g
                       ON (z.grupo_id = g.grupo_id)
                       and (z.clase_id = g.clase_id) 
                  WHERE
                      e.empresa_id = '$empresa_id'
                  AND e.centro_utilidad = '$centro_utilidad'
                  AND e.bodega = '$bodega'
                  AND e.existencia_actual >0
                  AND e.estado = '1'
                  AND b.estado = '1' 
                  $aumento
                  $aumento2
                  AND e.existencia_actual >0
                  AND e.estado = '1'
                  AND b.estado = '1' 
                  $aumento
                  $aumento2";
              //print_r($sql);
              
              $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",10,$offset); 
              
              $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset."";
              //print_r($sql);
               //RETURN $sql;
              if(!$resultado = $this->ConexionBaseDatos($sql))
                  return $this->frmError['MensajeError'];
                    
                  $cuentas=Array();
                  while(!$resultado->EOF)
                  {
                    $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                  
                  $resultado->Close();
                 
                  return $cuentas;  
    }

    
    function BuscarProducto2($empresa_id,$centro_utilidad,$bodega,$aumento,$aumento2,$offset)
    {
       $sql1="SELECT
                    count(*)
              FROM
                  existencias_bodegas as a,
                  inventarios_productos as b,
                  unidades as c
              WHERE
              a.empresa_id = '$empresa_id'
              AND a.centro_utilidad = '$centro_utilidad'
              AND a.bodega = '$bodega'
              ".$aumento."
              ".$aumento2."
              AND b.codigo_producto = a.codigo_producto
              AND b.estado = '1'
              AND c.unidad_id = b.unidad_id
	      AND a.estado = '1'";
              $this->ProcesarSqlConteo($sql1,7,$offset);      

             $sql="SELECT 
                  b.codigo_producto,
                  --b.descripcion,
                  b.unidad_id,
                  fc_descripcion_producto(b.codigo_producto) as descripcion,
                  c.descripcion as descripcion_unidad,
                  a.existencia,
                  d.costo,
                  b.contenido_unidad_venta,
                  h.descripcion as laboratorio
              FROM
                  existencias_bodegas as a,
                  inventarios_productos as b,
                  unidades as c,
                  inventarios as d,
                  inv_subclases_inventarios as e,
                  inv_moleculas as f,
                  inv_clases_inventarios as g,
                  inv_laboratorios as h
              WHERE
              a.empresa_id = '$empresa_id'
              AND a.centro_utilidad = '$centro_utilidad'
              AND a.bodega = '$bodega'
              ".$aumento."
              ".$aumento2."               
              AND b.codigo_producto = a.codigo_producto
              AND b.estado = '1'
              AND c.unidad_id = b.unidad_id
              AND d.empresa_id = a.empresa_id
              AND d.codigo_producto = a.codigo_producto
              AND b.grupo_id = e.grupo_id
              AND b.subclase_id=e.subclase_id
              AND b.clase_id=e.clase_id
              AND e.molecula_id=f.molecula_id
              AND b.grupo_id = g.grupo_id
              AND b.clase_id=g.clase_id
              AND g.laboratorio_id=h.laboratorio_id
	            AND a.estado = '1'
              order by b.descripcion ASC
              limit ".$this->limit." OFFSET ".$this->offset."";
               //print_r ($sql);
              if(!$resultado = $this->ConexionBaseDatos($sql))
                  return $this->frmError['MensajeError'];
                    
                  $cuentas=Array();
                  while(!$resultado->EOF)
                  {
                    $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                  
                  $resultado->Close();
                  
                  return $cuentas;   
    }

    function ContarProStip($empresa_id,$centro_utilidad,$bodega,$aumento,$aumento2)
    {
            $sql="SELECT
                        count(*)
                  FROM
                      existencias_bodegas as a,
                      inventarios_productos as b,
                      unidades as c,
                      inv_subclases_inventarios as e,
                      inv_moleculas as f,
                      inv_clases_inventarios as g,
                      inv_laboratorios as h
                  WHERE
                  a.empresa_id = '$empresa_id'
                  AND a.centro_utilidad = '$centro_utilidad'
                  AND a.bodega = '$bodega'
                  ".$aumento."
                  ".$aumento2." 
                  AND b.codigo_producto = a.codigo_producto
                  AND c.unidad_id = b.unidad_id
                  AND b.grupo_id = e.grupo_id
                  AND b.subclase_id=e.subclase_id
                  AND b.clase_id=e.clase_id
                  AND e.molecula_id=f.molecula_id
                  AND b.grupo_id = g.grupo_id
                  AND b.clase_id=g.clase_id
                  AND g.laboratorio_id=h.laboratorio_id";


            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }

                $resultado->Close();

                return $cuentas;   
    }

     function ContarProStip1($empresa_id,$centro_utilidad,$bodega,$aumento,$aumento2)
    {
            $sql="SELECT
                        count(*)
                  FROM
                      (
                      SELECT 
                  b.codigo_producto,
                  b.descripcion,
                  b.unidad_id,
                  c.descripcion as descripcion_unidad,
                  e.existencia_actual AS existencia,
                  d.costo,
                  e.fecha_vencimiento,
                  e.lote,
                  b.contenido_unidad_venta,
                  g.descripcion as laboratorio
              FROM
                  existencias_bodegas_lote_fv as e
                  JOIN existencias_bodegas as a 
                       ON(e.empresa_id = a.empresa_id)
                       and (e.centro_utilidad = a.centro_utilidad)
                       and (e.bodega = a.bodega)
                       and (e.codigo_producto = a.codigo_producto)
                  JOIN inventarios as d
                       ON (e.empresa_id = d.empresa_id)
                       and (e.codigo_producto = d.codigo_producto) 
                  JOIN inventarios_productos as b 
                       ON(d.codigo_producto = b.codigo_producto)
                  JOIN unidades as c
                       ON (b.unidad_id = c.unidad_id)
                  JOIN inv_subclases_inventarios as z
                       ON (b.grupo_id = z.grupo_id)
                       and (b.clase_id = z.clase_id)
                       and (b.subclase_id = z.subclase_id)
                  JOIN inv_clases_inventarios as g
                       ON (z.grupo_id = g.grupo_id)
                       and (z.clase_id = g.clase_id) 
                  WHERE
                      e.empresa_id = '$empresa_id'
                  AND e.centro_utilidad = '$centro_utilidad'
                  AND e.bodega = '$bodega'
                  AND e.existencia_actual >0
                  AND e.estado = '1'
                  AND b.estado = '1' 
                  $aumento
                  $aumento2
                  ) AS A";


            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }

                $resultado->Close();

                return $cuentas;   
    }

     function GuardarTemporal($bodegas_doc_id,$doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id=null,$fecha_venc,$lotec)
     {
       $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
       $OBJETO=$ClassDOC->GetOBJ();
       $RETORNO=$OBJETO->AddItemDocTemporal($doc_tmp_id,$codigo_producto,$cantidad,$porcentaje_gravamen,$total_costo,$usuario_id=null,$fecha_venc,$lotec);
       
       if($RETORNO === false)
       {
        $this->mensajeDeError=$OBJETO->mensajeDeError;
        return false;
       }
       //var_dump($RETORNO);
       return $RETORNO;
     }
     function llamarhijo()
    {
    
      echo "llegamos";
      
    }         

/******************************************************************************
*funcion constructora 
*******************************************************************************/  
    
 
           

   /********************************************************************************
    * Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
    * importantes a la hora de referenciar al paginador
    * 
    * @param String Cadena que contiene la consulta sql del conteo 
    * @param int numero que define el limite de datos,cuando no se desa el del 
    *        usuario,si no se pasa se tomara por defecto el del usuario 
    * @return boolean 
    *********************************************************************************/
    function ProcesarSqlConteo($consulta,$limite=null,$offset=null)
    { 
      $this->offset = 0;
      $this->paginaActual = 1;
      if($limite == null)
      {
        $this->limit = GetLimitBrowser();
      }
      else
      {
        $this->limit = $limite;
      }
      
      if($offset)
      {
        $this->paginaActual = intval($offset);
        if($this->paginaActual > 1)
        {
          $this->offset = ($this->paginaActual - 1) * ($this->limit);
        }
      }   

      if(!$result = $this->ConexionBaseDatos($consulta))
        return false;

      if(!$result->EOF)
      {
        $this->conteo = $result->fields[0];
        $result->MoveNext();
      }
      $result->Close();
      
      
      return true;
    }

 
    /**********************************************************************************
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la 
    * consulta sql 
    * 
    * @param  string  $sql  sentencia sql a ejecutar $empresaid,$cuenta,$nivel,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_est,$sw_cc,$sw_dc
    * @return rst 
    ************************************************************************************/
    function ConexionBaseDatos($sql)
    {
      list($dbconn)=GetDBConn();
      //$dbconn->debug=true;
      $rst = $dbconn->Execute($sql);
        
      if ($dbconn->ErrorNo() != 0)
      {
        $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
         "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
        return false;
      }
      return $rst;
    }
    /**********************************************************************************
    * Funcion que permite crear una transaccion 
    * @param string $sql Sql a ejecutar- para dar inicio a la transaccion se pasa vacio
    * @param char $num Numero correspondiente a la sentecia sql - por defect es 1
    *
    * @return object Objeto de la transaccion - Al momento de iniciar la transaccion no 
    *                se devuelve nada
    ***********************************************************************************/

  }
?>