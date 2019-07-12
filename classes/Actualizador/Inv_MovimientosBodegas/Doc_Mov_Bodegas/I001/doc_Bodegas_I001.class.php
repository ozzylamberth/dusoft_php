<?php
  /******************************************************************************
  * $Id: doc_Bodegas_I001.class.php,v 1.1 2009/07/17 19:08:17 johanna Exp $
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

class doc_bodegas_I001
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
         var_dump($ClassDOC->ErrMsg().$ClassDOC->Err());

       }
  }
    
    function CrearDoc($bodegas_doc_id, $observacion, $tipo_id_tercero, $tercero_id, $documento_compra, $fecha_doc_compra)
    {
        $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
        if(!is_object($ClassDOC))
        {
            echo(MsgOut("Error al crear la clase","BodegasDocumentos"));
        }
       
       $OBJETO=$ClassDOC->GetOBJ();
        if(!is_object($OBJETO))
        {
            echo(MsgOut("Error al crear el objeto","BodegasDocumentos"));
        }       
       
       //explode($fecha_doc_compra);
       $partes=explode("-", $fecha_doc_compra);
       $fecha_doc_compra=$partes[2]."-".$partes[1]."-".$partes[0]; 
       $RETORNO=$OBJETO->NewDocTemporal($observacion, $tipo_id_tercero, $tercero_id, $documento_compra, $fecha_doc_compra);
        if($RETORNO===false)
        {
            //(MsgOut("Error al crear el documento temporal","BodegasDocumentos"));
           echo "NO SE CREO EL TEMPORAL".$OBJETO->Err() . "<br>" . $OBJETO->ErrMsg();
        } 
        // $RETORNO=$OBJETO->GetInfoBodegaDocumento($bodegas_doc_id);
       //  var_dump($RETORNO);

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
        $sql1="SELECT
                    count(*)
              FROM
                  existencias_bodegas as a
                  JOIN inventarios as d ON (a.empresa_id = d.empresa_id)
                  AND (a.codigo_producto = d.codigo_producto) 
                  JOIN inventarios_productos as b ON (d.codigo_producto = b.codigo_producto)
                  JOIN unidades as c ON (b.unidad_id = c.unidad_id)
                  JOIN inv_subclases_inventarios as e ON (b.grupo_id = e.grupo_id)
                  AND (b.clase_id = e.clase_id)
                  AND (b.subclase_id =e.subclase_id)
                  JOIN inv_clases_inventarios as g ON (e.grupo_id = g.grupo_id)
                  AND (e.clase_id = g.clase_id)
              WHERE
              a.empresa_id = '".trim($empresa_id)."'
              AND a.centro_utilidad = '".trim($centro_utilidad)."'
              AND a.bodega = '".trim($bodega)."'
              ".$aumento."
              ".$aumento2."               
              AND b.estado = '1'
              AND a.estado = '1' 
			  AND substring(b.codigo_producto from 1 for 2) <>'FO'; "; // Se adiciona para q no muestre codigos del grupo FO
              $this->ProcesarSqlConteo($sql1,10,$offset);      

             $sql="SELECT 
                  b.codigo_producto,
                  b.unidad_id,
                  fc_descripcion_producto(b.codigo_producto) as descripcion,
                  c.descripcion as descripcion_unidad,
                  a.existencia,
                  d.costo,
                  b.contenido_unidad_venta,
                  g.descripcion as laboratorio
              FROM
                  existencias_bodegas as a
                  JOIN inventarios as d ON (a.empresa_id = d.empresa_id)
                  AND (a.codigo_producto = d.codigo_producto) 
                  JOIN inventarios_productos as b ON (d.codigo_producto = b.codigo_producto)
                  JOIN unidades as c ON (b.unidad_id = c.unidad_id)
                  JOIN inv_subclases_inventarios as e ON (b.grupo_id = e.grupo_id)
                  AND (b.clase_id = e.clase_id)
                  AND (b.subclase_id =e.subclase_id)
                  JOIN inv_clases_inventarios as g ON (e.grupo_id = g.grupo_id)
                  AND (e.clase_id = g.clase_id)
              WHERE
              a.empresa_id = '".trim($empresa_id)."'
              AND a.centro_utilidad = '".trim($centro_utilidad)."'
              AND a.bodega = '".trim($bodega)."'
              ".$aumento."
              ".$aumento2."               
              AND b.estado = '1'
              AND a.estado = '1'
			  AND substring(b.codigo_producto from 1 for 2) <>'FO'
              order by b.descripcion ASC
              limit ".$this->limit." OFFSET ".$this->offset.""; // Se adiciona <>'FO' para q no muestre codigos del grupo FO
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
                  existencias_bodegas as a
                  JOIN inventarios as d ON (a.empresa_id = d.empresa_id)
                  AND (a.codigo_producto = d.codigo_producto) 
                  JOIN inventarios_productos as b ON (d.codigo_producto = b.codigo_producto)
                  JOIN unidades as c ON (b.unidad_id = c.unidad_id)
                  JOIN inv_subclases_inventarios as e ON (b.grupo_id = e.grupo_id)
                  AND (b.clase_id = e.clase_id)
                  AND (b.subclase_id =e.subclase_id)
                  JOIN inv_clases_inventarios as g ON (e.grupo_id = g.grupo_id)
                  AND (e.clase_id = g.clase_id)
              WHERE
              a.empresa_id = '".trim($empresa_id)."'
              AND a.centro_utilidad = '".trim($centro_utilidad)."'
              AND a.bodega = '".trim($bodega)."'
              ".$aumento."
              ".$aumento2."               
              AND b.estado = '1'
              AND a.estado = '1'; ";


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
	
	function FacturaProveedor($tipo_id_tercero, $tercero_id, $documento_compra)
    {
            $sql="
				SELECT
				b.codigo_proveedor_id,
				b.numero_factura
				FROM
				terceros_proveedores as a 
				JOIN inv_facturas_proveedores as b ON (a.codigo_proveedor_id = b.codigo_proveedor_id)
				AND (a.tipo_id_tercero = '".trim($tipo_id_tercero)."')
				AND (a.tercero_id = '".trim($tercero_id)."')
				AND (b.numero_factura = '".trim($documento_compra)."')
				UNION
				SELECT
				x.codigo_proveedor_id,
				y.documento_compra
				FROM
				inv_bodegas_movimiento_compras_directas AS y
				LEFT JOIN terceros_proveedores as x ON(y.tipo_id_tercero = x.tipo_id_tercero)
				AND (y.tercero_id = x.tercero_id)
				WHERE TRUE
				AND (y.tipo_id_tercero = '".trim($tipo_id_tercero)."')
				AND (y.tercero_id = '".trim($tercero_id)."')
				AND (y.documento_compra = '".trim($documento_compra)."');";
				
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
     // $dbconn->debug=true;
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
