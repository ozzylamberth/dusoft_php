<?php
  /******************************************************************************
  * $Id: doc_Bodegas_E007.class.php,v 1.1 2009/07/17 19:08:17 johanna Exp $
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

class doc_bodegas_E007
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
    //CrearDoc($bodegas_doc_id, $observacion, $tipo_id_tercero, $tercero_id, $prestamo);
    function CrearDoc($bodegas_doc_id, $observacion, $tipo_id_tercero, $tercero_id, $egreso_id, $costo, $departamento)
    {
       $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
       $OBJETO=$ClassDOC->GetOBJ();//($observacion,$tipo_prestamo_id, $tipo_id_tercero, $tercero_id, $usuario_id , $documento_compra, $fecha_doc_compra

//        ECHO $bodegas_doc_id;
//        ECHO $observacion;
//        ECHO $tipo_id_tercero;
//        ECHO $tercero_id; 
//        ECHO $egreso_id; 
//        ECHO $costo; 
//        ECHO $departamento;


        if($costo=='')
        {
           $costo=null;
        }
        if($departamento=='')
        {
            $departamento=null;
        }
        if($tipo_id_tercero=='')
        {
            
            $tipo_id_tercero=null;
            $tercero_id=null;
        }

       //ECHO "AQUI VAMOS".$tipo_id_tercero.$tercero_id."AAAAAAAAAA";
       $RETORNO=$OBJETO->NewDocTemporal($observacion, $egreso_id, $costo, $departamento, $tipo_id_tercero, $tercero_id, UserGetUID());
      //    $RETORNO=$OBJETO->GetInfoBodegaDocumento($bodegas_doc_id);
        echo $OBJETO->Err().$OBJETO->ErrMsg();
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
         $sql="SELECT 
                  b.codigo_producto,
                  fc_descripcion_producto(b.codigo_producto) as descripcion,
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
				  JOIN inventarios as d ON (e.empresa_id = d.empresa_id)
				  AND (e.codigo_producto = d.codigo_producto)
                  JOIN inventarios_productos as b ON (d.codigo_producto = b.codigo_producto)
                  JOIN unidades as c ON (b.unidad_id = c.unidad_id)
                  JOIN inv_subclases_inventarios as z ON (b.grupo_id = z.grupo_id)
				  AND (b.clase_id = z.clase_id)
				  AND (b.subclase_id = z.subclase_id)
                  JOIN inv_clases_inventarios as g ON (z.grupo_id = g.grupo_id)
				  AND (z.clase_id = g.clase_id)
              WHERE
              e.empresa_id = '".trim($empresa_id)."'
              AND e.centro_utilidad = '".trim($centro_utilidad)."'
              AND e.bodega = '".trim($bodega)."'
              ".$aumento."
              ".$aumento2."              
              AND   e.existencia_actual>0
              AND   e.estado = '1'";
              /*print_r($sql);*/
              $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",10,$offset); 
              
              $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset."";
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


    function ContarProStip($empresa_id,$centro_utilidad,$bodega,$aumento,$aumento2)
    {
            $sql="SELECT
                        count(*)
                  FROM
                      existencias_bodegas as a,
                      inventarios_productos as b,
                      unidades as c,
                      existencias_bodegas_lote_fv e,
                      inv_subclases_inventarios as z,
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
                  AND   b.grupo_id = z.grupo_id
                  AND   b.subclase_id=z.subclase_id
                  AND   b.clase_id=z.clase_id
                  AND   z.molecula_id=f.molecula_id
                  AND   b.grupo_id = g.grupo_id
                  AND   b.clase_id=g.clase_id
                  AND   g.laboratorio_id=h.laboratorio_id";


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