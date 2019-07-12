<?php
  /******************************************************************************
  * $Id: doc_Bodegas_T003.class.php,v 1.3 2010/07/07 15:51:31 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.3 $ 
  * 
  * @autor Mauricio Medina
  ********************************************************************************/

if (!IncludeClass('BodegasDocumentos'))
{
    die(MsgOut("Error al incluir archivo","BodegasDocumentos"));
}

class doc_Bodegas_T003
{

 var $mensajeDeError;
   
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
    //CrearDoc($bodegas_doc_id, $observacion,$centro,$bodega);
    function CrearDoc($bodegas_doc_id, $observacion,$centro,$bodega)
    {
       $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
       $OBJETO=$ClassDOC->GetOBJ();
       $RETORNO=$OBJETO->NewDocTemporal($observacion,$centro,$bodega,UserGetUID());
      //    $RETORNO=$OBJETO->GetInfoBodegaDocumento($bodegas_doc_id);
       //var_dump($RETORNO);
       $this->mensajeDeError=$OBJETO->mensajeDeError;
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
		
		function BuscarProducto($empresa_id,$centro_utilidad,$bodega,$aumento,$offset,$doc_tmp)
    { //print_r($doc_tmp);       
      $sql = "SELECT  *
              FROM    (
                        SELECT  b.codigo_producto,
                                b.descripcion,
                                b.unidad_id,
                                c.descripcion as descripcion_unidad,
                                CASE WHEN LF.fecha_vencimiento IS NULL THEN a.existencia
                                     ELSE LF.existencia_actual END AS existencia,
                                d.costo,
                                CONCE.concentracion,
                                CONCE.forma_farmacologica,
                                LF.lote,
                                TO_CHAR(LF.fecha_vencimiento,'DD-MM-YYYY') AS fecha_vencimiento
                        FROM    existencias_bodegas a LEFT JOIN
                                existencias_bodegas_lote_fv LF
                                ON( 
                                    a.empresa_id = LF.empresa_id AND
                                    a.centro_utilidad = LF.centro_utilidad AND	
                                    a.codigo_producto = LF.codigo_producto AND	
                                    a.bodega = LF.bodega
                                  )
                                LEFT JOIN existencias_bodegas EB
                                ON( a.empresa_id = EB.empresa_id AND
                                    a.centro_utilidad = EB.centro_utilidad AND 	
                                    a.codigo_producto = EB.codigo_producto AND	
                                    EB.bodega = '".$doc_tmp['bodega_destino']."'),
                                inventarios_productos b LEFT JOIN 
                                (
                                  SELECT  M.codigo_medicamento,
                                          M.concentracion_forma_farmacologica as concentracion,
                                          F.descripcion as forma_farmacologica
                                  FROM    medicamentos M,
                                          inv_med_cod_forma_farmacologica F
                                  WHERE   M.cod_forma_farmacologica = F.cod_forma_farmacologica
                                ) CONCE
                                ON (CONCE.codigo_medicamento = b.codigo_producto),
                                unidades c,
                                inventarios d
                        WHERE   a.empresa_id = '$empresa_id'
                        AND     a.centro_utilidad = '$centro_utilidad'
                        AND     a.bodega = '$bodega'
                        ".$aumento." 
                        AND     b.codigo_producto = a.codigo_producto
                        AND     c.unidad_id = b.unidad_id
                        AND     d.empresa_id = a.empresa_id
                        AND     d.codigo_producto = a.codigo_producto
                        AND     EB.codigo_producto IS NOT NULL 
                      ) A 
              WHERE   A.existencia > 0 ";
      
      $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A ",10,$offset);   
      
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
              
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return $this->frmError['MensajeError'];
                    
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
                  
      $resultado->Close();
       
//print_r($sql);
      return $cuentas;   
    }


    function ContarProStip($empresa_id,$centro_utilidad,$bodega,$aumento)
    {
            $sql="SELECT
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
                  AND b.codigo_producto = a.codigo_producto
                  AND c.unidad_id = b.unidad_id";


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
       }
      
       return $RETORNO;
     }
     function llamarhijo()
    {
    
      echo "llegamos";
      
    }
    /**
    *
    */
    function ObtenerInformacionTraslado($tmp_doc_id)
    {
      $sql  = "SELECT usuario_id,";
      $sql .= " 	    doc_tmp_id 	,";
      $sql .= " 	    empresa_id 	,";
      $sql .= " 	    centro_utilidad_origen 	,";
      $sql .= " 	    bodega_origen 	,";
      $sql .= " 	    centro_utilidad_destino 	,";
      $sql .= " 	    bodega_destino ";
      $sql .= "FROM   inv_bodegas_movimiento_tmp_traslados ";
      $sql .= "WHERE  doc_tmp_id = ".$tmp_doc_id." ";
      $sql .= "AND       usuario_id = ".UserGetUID()." ";
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return $this->frmError['MensajeError'];

      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }

      $rst->Close();

      return $datos;  
    }

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
  }
?>