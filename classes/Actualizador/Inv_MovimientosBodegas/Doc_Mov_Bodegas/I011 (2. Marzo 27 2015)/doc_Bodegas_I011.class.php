<?php
  /******************************************************************************
  * $Id: doc_Bodegas_I011.class.php,v 1.1 2009/07/17 19:08:17 johanna Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.1 $ 
  * 
  * @autor Mauricio Medina
  ********************************************************************************/

if (!IncludeClass('BodegasDocumentos'))
{
    die(MsgOut("Error al incluir archivo","BodegasDocumentos"));
}

class doc_bodegas_I011
{

  function DatosParaEditar($tmp_doc_id,$usuario_id)
  {
    $ClassDOC= new BodegasDocumentos();
    $datos=$ClassDOC->GetInfoBodegaDocumentoTMP($tmp_doc_id,$usuario_id);
    return $datos;
  }
  
  function EliminarItem($tr,$item)
  {
    //print_r($item);
    list($bodegas_doc_id,$i) = explode("@",$tr);
    $ClassDOC= new BodegasDocumentos();
    $OBJETO=$ClassDOC->GetOBJ($bodegas_doc_id);
    $resultado=$OBJETO->DelItemDocTemporal($item);
    return $resultado;
  }


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
    function CrearDoc($bodegas_doc_id, $observacion, $codigo_proveedor_id, $factura)
    {
       $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
       $OBJETO=$ClassDOC->GetOBJ();//($observacion,$tipo_prestamo_id, $tipo_id_tercero, $tercero_id, $usuario_id , $documento_compra, $fecha_doc_compra
       $RETORNO=$OBJETO->NewDocTemporal($observacion,$codigo_proveedor_id, $factura,UserGetUID());
      //    $RETORNO=$OBJETO->GetInfoBodegaDocumento($bodegas_doc_id);
        echo $OBJETO->Err().$OBJETO->ErrMsg();
       //var_dump($RETORNO);
        if(!is_object($ClassDOC))
        {
            die(MsgOut("Error al crear la clase","BodegasDocumentos"));
        }
     return $RETORNO;
    }


    function GetDocTemporal($bodegas_doc_id,$doc_tmp_id,$usuario_id)
    {
       $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
       $OBJETO=$ClassDOC->GetOBJ();
       $RETORNO=$OBJETO->GetDocTemporal($doc_tmp_id,$usuario_id);
       //print_r($RETORNO);
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
    
    
    function ProductosFactura($codigo_proveedor_id,$numero_factura)
    {
       $sql="SELECT 
                  fpd.*,
                  fc_descripcion_producto(fpd.codigo_producto) as descripcion_producto
              FROM
                  inv_facturas_proveedores fp,
                  inv_facturas_proveedores_d fpd
              WHERE
                fp.numero_factura  = '".$numero_factura."'
                and fp.codigo_proveedor_id = ".$codigo_proveedor_id."
                and fp.numero_factura = fpd.numero_factura;
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
	
  function ProductosExistencias($EmpresaId,$CentroUtilidad,$Bodega,$CodigoProducto,$Lote,$FechaVencimiento)
    {
       $sql="SELECT 
                  *
              FROM
                  existencias_bodegas_lote_fv
              WHERE
                   empresa_id  = '".$EmpresaId."'
               AND centro_utilidad = '".$CentroUtilidad."'
               AND bodega = '".$Bodega."'
               AND codigo_producto = '".$CodigoProducto."'
               AND lote = '".$Lote."'
               AND fecha_vencimiento = '".$FechaVencimiento."';
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
	
  
    
    
     function ModificarCantidadesDocumentoFarmacia($movimiento_id,$cantidad)
	{
	$sql  = " UPDATE inv_bodegas_movimiento_d ";
  $sql .= " SET 
	          cantidad_recibida = cantidad_recibida + ".$cantidad." ";
  $sql .= " Where ";
  $sql .= " movimiento_id = ".$movimiento_id."";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    
    function InsertarCabeceraDocumentoVerificacionTmp($DocTemporal_Auxiliar)
	{
	  //$this->debug=true;
 $sql="INSERT INTO inv_documento_verificacion_tmp (
                            farmacia_id,
                            prefijo,
                            numero,
                            empresa_id,
                            centro_utilidad,
                            bodega,
                            doc_tmp_id,
                            usuario_id
                     )
                  VALUES ('".$DocTemporal_Auxiliar['farmacia_id']."',
                          '".$DocTemporal_Auxiliar['prefijo']."',
                           ".$DocTemporal_Auxiliar['numero'].",
                          '".$DocTemporal_Auxiliar['empresa_id']."',
                          '".$DocTemporal_Auxiliar['centro_utilidad']."',
                          '".$DocTemporal_Auxiliar['bodega']."',
                           ".$DocTemporal_Auxiliar['doc_tmp_id'].",
                           ".UserGetUID()."
                          );";
	
	  //print_r($sql);
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    
       function InsertarDetalleDocumentoVerificacionTmp(
       $farmacia_id,$prefijo,$numero,$doc_tmp_id,$item_id,
       $codigo_producto,$cantidad,$lote,$fecha_vencimiento,
       $Novedad,$mensaje)
	{
	  //$this->debug=true;
 $sql="INSERT INTO inv_documento_verificacion_tmp_d (
                            farmacia_id,
                            prefijo,
                            numero,
                            doc_tmp_id,
                            item_id,
                            codigo_producto,
                            cantidad,
                            lote,
                            fecha_vencimiento,
                            novedad_devolucion_id,
                            novedad_anexa,
                            usuario_id
                     )
                  VALUES ('".$farmacia_id."',
                          '".$prefijo."',
                           ".$numero.",
                          ".$doc_tmp_id.",
                          ".$item_id.",
                          '".$codigo_producto."',
                           ".$cantidad.",
                           '".$lote."',
                           '".$fecha_vencimiento."',
                           '".$Novedad."',
                           '".$mensaje."',
                           ".UserGetUID()."
                          );";
	
	  //print_r($sql);
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    
  function InsertarCabeceraDocumentoVerificacion($DocTemporal_Auxiliar,$prefijo,$numero)
	{
	 // $this->debug=true;
 $sql="INSERT INTO inv_documento_verificacion (
                            farmacia_id,
                            prefijo_doc_farmacia,
                            numero_doc_farmacia,
                            empresa_id,
                            prefijo,
                            numero,
                            usuario_id
                     )
                  VALUES ('".trim($DocTemporal_Auxiliar['farmacia_id'])."',
                          '".$DocTemporal_Auxiliar['prefijo']."',
                           ".$DocTemporal_Auxiliar['numero'].",
                          '".$DocTemporal_Auxiliar['empresa_id']."',
                          '".$prefijo."',
                          ".$numero.",
                           ".UserGetUID()."
                          );";
	
	  //print_r($sql);
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    
     function InsertarDetalleDocumentoVerificacion($DocTemporal_Auxiliar,$prefijo,$numero,$CodigoProducto,$Cantidad,$Lote,$FechaVencimiento,$Novedad,$NovedadAnexa)
	{
	 // $this->debug=true;
 $sql="INSERT INTO inv_documento_verificacion_d (
                            farmacia_id,
                            prefijo_doc_farmacia,
                            numero_doc_farmacia,
                            empresa_id,
                            prefijo,
                            numero,
                            codigo_producto,
                            cantidad,
                            lote,
                            fecha_vencimiento,
                            novedad_devolucion_id,
                            novedad_anexa
                     )
                  VALUES ('".trim($DocTemporal_Auxiliar['farmacia_id'])."',
                          '".$DocTemporal_Auxiliar['prefijo']."',
                           ".$DocTemporal_Auxiliar['numero'].",
                          '".$DocTemporal_Auxiliar['empresa_id']."',
                          '".$prefijo."',
                          ".$numero.",
                          '".$CodigoProducto."',
                          ".$Cantidad.",
                          '".$Lote."',
                          '".$FechaVencimiento."',
                          '".$Novedad."',
                           '".$NovedadAnexa."'
                          );";
	
	  //print_r($sql);
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    
	 function CodigoProveedor($CodigoProveedorId)
    {
       $sql="SELECT 
                t.*
              FROM
                  terceros_proveedores tp,
                  terceros t
              WHERE
                tp.codigo_proveedor_id=".$CodigoProveedorId."
                and tp.tipo_id_tercero = t.tipo_id_tercero
                and tp.tercero_id = t.tercero_id;
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
    
    
    function ProductosDocumento($farmacia_id,$prefijo,$numero,$codigo_barras,$descripcion)
    {
       if(!empty($codigo_barras))
          $filtro = " invp.codigo_barras = '".$codigo_barras."'  ";
       
       $sql="SELECT 
                  md.*,
                  fc_descripcion_producto(md.codigo_producto) as descripcion
                  FROM
                  inv_bodegas_movimiento_d md,
                  inventarios_productos invp
              WHERE
                    md.empresa_id='".trim($farmacia_id)."'
              AND   md.prefijo = '".$prefijo."'
              AND   md.numero = '".$numero."' 
              AND   md.cantidad <> md.cantidad_recibida
              AND   md.codigo_producto = invp.codigo_producto
              AND   invp.descripcion ILIKE '%".$descripcion."%'  
                    ".$filtro."
              ";
              //print_r($sql);
              $sql .= " Order By invp.descripcion ASC, md.fecha_vencimiento ASC ";
                            
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

    	function ListadoNovedadesDevolucion()
{

          $sql="
            select
            novedad_devolucion_id as codigo,
            descripcion,
            estado
            From
            inv_novedades_devoluciones
            where
            estado='1'
            
            order by codigo ASC
            ";
 
 
 //$this->debug=true;
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


function ItemEnMovimiento($doc_tmp_id,$item_id,$item_id_compras)
    {
       
	$sql  = "UPDATE inv_bodegas_movimiento_tmp_d ";
  $sql .= "SET ";
  $sql .= "item_id_compras = ".$item_id_compras."";
  $sql .= " Where ";
  $sql .= " item_id = ".$item_id." ";
	$sql .= " and doc_tmp_id = ".$doc_tmp_id." ";
  $sql .= " and usuario_id = ".UserGetUID().";";
	
	//print_r($sql);
        
      
			if(!$result = $this->ConexionBaseDatos($sql))
				return false;
	    else
         return true;
			
			$result->Close();
       
    }    

    function ConsultaItemTemporal($item_id)
		{
			$query = 	"
									SELECT 	*
									FROM	inv_bodegas_movimiento_tmp_d
									WHERE
									item_id= ".$item_id."
									";
			
			//print_r($query);
      if(!$result = $this->ConexionBaseDatos($query))
				return false;
	
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
			
			return $vars;
		}
    
        function ConsultaDetalleVerificacion($doc_tmp_id,$item_id)
		{
			$query = 	"
									SELECT 	
                          idv.*,
                          ind.descripcion
									FROM	
                  inv_documento_verificacion_tmp_d idv,
                  inv_novedades_devoluciones ind
                  WHERE
                        idv.item_id= ".$item_id."
                  and   idv.doc_tmp_id = ".$doc_tmp_id."
                  and   idv.usuario_id = ".UserGetUID()."
                  and   idv.novedad_devolucion_id = ind.novedad_devolucion_id
									";
			
			//print_r($query);
      if(!$result = $this->ConexionBaseDatos($query))
				return false;
	
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
			
			return $vars;
		}
    
    
    function ConsultaDocumentoVerificacionTmp($farmacia_id,$prefijo,$numero)
		{
			$query = 	"
									SELECT 	
                        *
									FROM	
                  inv_documento_verificacion_tmp
                  WHERE
                        farmacia_id= '".$farmacia_id."'
                  and   prefijo = '".$prefijo."'
                  and   numero = ".$numero." ";
			
			//print_r($query);
      if(!$result = $this->ConexionBaseDatos($query))
				return false;
	
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
			
			return $vars;
		}
    
    
    
    function ConsultaDocumentoVerificacionTmp_d($farmacia_id,$prefijo,$numero)
		{
			$query = 	"
									SELECT 	
                      *
                  FROM	
                  inv_documento_verificacion_tmp_d
                  WHERE
                        farmacia_id = '".$farmacia_id."'
                  and   prefijo = '".$prefijo."'
                  and   numero = ".$numero."
									";
			
			//print_r($query);
      if(!$result = $this->ConexionBaseDatos($query))
				return false;
	
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
			
			return $vars;
		}
    
    
      function ConsultaItemTemporal_($doc_tmp_id,$item_id)
		{
			$query = 	"
									SELECT 	*
									FROM	inv_bodegas_movimiento_tmp_d
									WHERE
									 doc_tmp_id= ".$doc_tmp_id."
									and item_id_compras= ".$item_id."
                  and usuario_id =".UserGetUID().";
									";
			
			//print_r($query);
      if(!$result = $this->ConexionBaseDatos($query))
				return false;
	
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
			
			return $vars;
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
//     $dbconn->debug=true;
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