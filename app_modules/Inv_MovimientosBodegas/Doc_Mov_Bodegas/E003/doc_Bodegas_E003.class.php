<?php
  /******************************************************************************
  * $Id: doc_Bodegas_E003.class.php,v 1.2 2011/06/14 19:58:06 mauricio Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.2 $ 
  * 
  * @autor Jaime Gomez
  ********************************************************************************/

if (!IncludeClass('BodegasDocumentos'))
{
    die(MsgOut("Error al incluir archivo","BodegasDocumentos"));
}

if(!IncludeClass('BodegasDocumentosComun','BodegasDocumentos'))
	{
		die(MsgOut("Error al incluir archivo","BodegasDocumentosComun"));
	}

class doc_bodegas_E003
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
    //CrearDoc($bodegas_doc_id, $observacion, $tipo_id_tercero, $tercero_id, $prestamo);
    function CrearDoc($bodegas_doc_id, $observacion, $coordinador_auxiliar,$control_interno,$fecha_selectivo)
    {
       $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
       $OBJETO=$ClassDOC->GetOBJ();//($observacion,$tipo_prestamo_id, $tipo_id_tercero, $tercero_id, $usuario_id , $documento_compra, $fecha_doc_compra
       $RETORNO=$OBJETO->NewDocTemporal($observacion, $coordinador_auxiliar,$control_interno,$fecha_selectivo,UserGetUID());
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
    
	function TraerGetDocTemporal($bodegas_doc_id,$doc_tmp_id)
		{
			$ClassDOC= new BodegasDocumentos($bodegas_doc_id);
			$objeto=$ClassDOC->GetOBJ();
			return $objeto->GetDocTemporal($doc_tmp_id);
		}
    
    function GuardarJustificacion($item_id,$justificacion)
    {
       $sql="	UPDATE inv_bodegas_movimiento_tmp_d
					SET observacion_cambio = '".$justificacion."'
					WHERE
					item_id = ".trim($item_id).";";
					
	$sql.="	UPDATE inv_bodegas_movimiento_tmp_d
					SET cantidad_sistema = a.existencia_actual
					FROM
						(
						SELECT
						e.existencia_actual
						FROM
						inv_bodegas_movimiento_tmp_d as t
						JOIN existencias_bodegas_lote_fv as e ON (t.codigo_producto = e.codigo_producto)
						AND (t.empresa_id = e.empresa_id)
						AND (t.centro_utilidad = e.centro_utilidad)
						AND (t.bodega = e.bodega)
						AND (t.lote = e.lote)
						AND (t.fecha_vencimiento = e.fecha_vencimiento)
						WHERE
						t.item_id = ".trim($item_id)."
						) as a
			WHERE
				item_id = ".trim($item_id).";";
              
              /*print_r($sql);*/
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
	
    function TraerTipoPrestamo($doc_tmp_id,$usuario)
    {
       $sql="SELECT 
                a.tipo_prestamo_id,
				a.tipo_id_tercero,
				a.tercero_id
              FROM
                  inv_bodegas_movimiento_tmp_prestamo as a
              WHERE
                a.doc_tmp_id  = ".$doc_tmp_id."
                and
                a.usuario_id = ".$usuario.";
              ";
              
              //print_r($sql);
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
	
	
	 function CodigoProveedor($tercero_id,$tipo_id_tercero)
    {
       $sql="SELECT 
                codigo_proveedor_id
              FROM
                  terceros_proveedores
              WHERE
                tipo_id_tercero = '".$tipo_id_tercero."' 
        				and
        				tercero_id = '".$tercero_id."';
              ";
              
              //print_r($sql);
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
    
    
    function BuscarProducto($empresa_id,$centro_utilidad,$bodega,$aumento,$bodegas_doc_id,$doc_tmp_id,$offset)
    {
       $sql="
			SELECT 
			b.codigo_producto,
			fc_descripcion_producto(b.codigo_producto) as descripcion,
			b.unidad_id as descripcion_unidad,
			e.existencia_actual as existencia,
			e.fecha_vencimiento,
			e.lote,
			g.costo
			FROM
			existencias_bodegas_lote_fv e
			JOIN inventarios as g ON (e.empresa_id = g.empresa_id)
			AND (e.codigo_producto = g.codigo_producto)
			JOIN inventarios_productos as b ON (g.codigo_producto = b.codigo_producto)
			WHERE
			b.estado = '1'
			".$aumento."
			AND e.empresa_id = '".trim($empresa_id)."'          
			AND e.centro_utilidad = '".trim($centro_utilidad)."'
			AND e.bodega = '".trim($bodega)."'
			AND e.existencia_actual > 0
			AND e.estado= '1'
			AND b.codigo_producto||''||e.lote||''||e.fecha_vencimiento NOT IN ( SELECT
																				codigo_producto||''||lote||''||fecha_vencimiento as producto
																				FROM
																				inv_bodegas_movimiento_tmp_d
																				WHERE
																				doc_tmp_id = '".$doc_tmp_id."'
																				AND usuario_id = '".UserGetUID()."'
																				AND empresa_id = '".trim($empresa_id)."'
																				AND centro_utilidad = '".trim($centro_utilidad)."'
																				AND bodega = '".trim($bodega)."'
																			   )";
              $sql .= " Order By e.fecha_vencimiento ASC,b.descripcion ASC ";
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
       //var_dump($RETORNO);
       return $RETORNO;
     }
     
     
      function DescripcionProducto($CodigoProducto)
    {
       $sql=" select fc_descripcion_producto('".$CodigoProducto."') as descripcion_producto;  ";
              
             
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

 
 
 function PoliticasVencimiento($tercero_id)
    {
       $sql="SELECT 
                *
              FROM
                  inv_terceros_proveedores_politicasdevolucion
              WHERE
                tercero_id = '".$tercero_id."'
              ";
              
              //print_r($sql);
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