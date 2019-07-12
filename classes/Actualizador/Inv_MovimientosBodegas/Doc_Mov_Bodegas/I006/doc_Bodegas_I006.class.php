<?php
  /******************************************************************************
  * $Id: doc_Bodegas_I006.class.php,v 1.1 2009/07/17 19:08:20 johanna Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.1 $ 
  * 
  * @autor Jaime Gomez
  ********************************************************************************/


	if(!IncludeClass('BodegasDocumentos'))
	{
		die(MsgOut("Error al incluir archivo","BodegasDocumentos"));
	}
	
	if(!IncludeClass('BodegasDocumentosComun','BodegasDocumentos'))
	{
		die(MsgOut("Error al incluir archivo","BodegasDocumentosComun"));
	}
	

class doc_bodegas_I006
{

 	/*
      * Consultar Items Existencias
      */
      
      function ConsultarItemsExistencias($Codigo_Producto,$Empresa_Id,$Centro_Utilidad,$Bodega)
		{
			
      $query = 	"
									SELECT 	
                       codigo_producto,
                       existencia
									FROM	
                      existencias_bodegas
									WHERE 
                      empresa_id = '".$Empresa_Id."'
                      and
                      centro_utilidad = '".$Centro_Utilidad."'
                      and
                      bodega = '".$Bodega."'
                      and
                      codigo_producto = '".$Codigo_Producto."'
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
		
     function BuscarProductoLoteTemporal($doc_tmp_id,$usuario,$codigo_producto,$ItemIdCompras)
    {
            $sql="SELECT
                        *
                  FROM
                      inv_bodegas_movimiento_tmp_d
                  WHERE
                          codigo_producto = '".$codigo_producto."'
                  AND     usuario_id = ".$usuario."
                  AND     doc_tmp_id = ".$doc_tmp_id."
                  AND     item_id_compras = ".$ItemIdCompras.";
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
    
    function CantidadesPorItem($doc_tmp_id,$usuario,$ItemId)
    {
            $sql="SELECT
                       sum(cantidad) as total
                  FROM
                      inv_bodegas_movimiento_tmp_d
                  WHERE
                          
                          usuario_id = ".$usuario."
                  AND     doc_tmp_id = ".$doc_tmp_id."
                  AND     item_id_compras = ".$ItemId.";
                  ";
         //print_r($sql);

            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }

                $resultado->Close();

                return $cuentas;   
    }
    
    
      function ModificarCantidadesDocumento($movimiento_id,$cantidad)
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
  
  function SacarProductosTMP($doc_tmp_id,$usuario_id)
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

  function CrearDoc($bodegas_doc_id, $observacion, $documento_devolucion, $fecha_doc_devolucion, $tipo_id_tercero, $tercero_id)
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
   $RETORNO=$OBJETO->NewDocTemporal($observacion, $documento_devolucion, $fecha_doc_devolucion, null,$tipo_id_tercero, $tercero_id);
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
  
/*  function GetInfoDocTemporal($bodegas_doc_id,$doc_tmp_id)
  {
   $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
   $OBJETO=$ClassDOC->GetOBJ();
   $RETORNO=$OBJETO->GetDocTemporal($doc_tmp_id,UserGetUID());
   //var_dump($RETORNO);
   if(!is_object($ClassDOC))
    {
        die(MsgOut("Error al crear la clase","BodegasDocumentos"));
    }
    return $RETORNO;
  }*/
  
  	function GetInfoDocTemporal($bodegas_doc_id,$doc_tmp_id)
		{
			$ClassDOC= new BodegasDocumentos($bodegas_doc_id);
			$objeto=$ClassDOC->GetOBJ();
			return $objeto->GetDocTemporal($doc_tmp_id,UserGetUID());
		}
  
  /*function BuscarProducto($empresa_id,$centro_utilidad,$bodega,$aumento,$aumento2,$offset)
  {
    $sql1=" SELECT  count(*)
            FROM    existencias_bodegas as a,
                    inventarios_productos as b,
                    unidades as c
            WHERE   a.empresa_id = '$empresa_id'
            AND     a.centro_utilidad = '$centro_utilidad'
            AND     a.bodega = '$bodega'
            ".$aumento."
            AND     b.codigo_producto = a.codigo_producto
            AND     b.estado = '1'
            AND     c.unidad_id = b.unidad_id
            AND     a.estado = '1'";
          $this->ProcesarSqlConteo($sql1,7,$offset);      

     $sql=" SELECT  b.codigo_producto,
                    --b.descripcion,
                    fc_descripcion_producto(b.codigo_producto) as descripcion,
                    b.unidad_id,
                    c.descripcion as descripcion_unidad,
                    a.existencia,
                    d.costo,
                    b.contenido_unidad_venta,
                    h.descripcion as laboratorio
            FROM    existencias_bodegas as a,
                    inventarios_productos as b,
                    unidades as c,
                    inventarios as d,
                    inv_subclases_inventarios as e,
                    inv_moleculas as f,
                    inv_clases_inventarios as g,
                    inv_laboratorios as h
            WHERE   a.empresa_id = '$empresa_id'
            AND     a.centro_utilidad = '$centro_utilidad'
            AND     a.bodega = '$bodega'
            ".$aumento."
            ".$aumento2."             
            AND     b.codigo_producto = a.codigo_producto
            AND     b.estado = '1'
            AND     c.unidad_id = b.unidad_id
            AND     d.empresa_id = a.empresa_id
            AND     d.codigo_producto = a.codigo_producto
            AND     b.codigo_producto = a.codigo_producto
            AND     c.unidad_id = b.unidad_id
            AND     d.empresa_id = a.empresa_id
            AND     d.codigo_producto = a.codigo_producto
            AND     b.grupo_id = e.grupo_id
            AND     b.subclase_id=e.subclase_id
            AND     b.clase_id=e.clase_id
            AND     e.molecula_id=f.molecula_id
            AND     b.grupo_id = g.grupo_id
            AND     b.clase_id=g.clase_id
            AND     g.laboratorio_id=h.laboratorio_id
            AND     a.estado = '1'
            
            order By b.descripcion 
            
            limit ".$this->limit." OFFSET ".$this->offset."";
            //RETURN $sql;
           // print_r($sql);
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
  */
  
   
  function ProductosDocumento($empresa_id,$prefijo,$numero,$codigo_barras,$descripcion,$bodegas_doc_id,$doc_tmp_id,$offset,$codigo_producto)
  {
     if(!empty($codigo_barras))
          $filtro =" AND   b.codigo_barras = '".$codigo_barras."'  ";
       if(!empty($codigo_producto))
          $filtro .=" AND   b.codigo_producto = '".$codigo_producto."'  ";
      
	  $sql=" SELECT     
          a.codigo_producto,
           AVG(a.porcentaje_gravamen)as porc_iva,
           AVG(d.costo) as costo,
          ((SUM(a.cantidad)-(AVG(COALESCE(a.cantidad_recibida,0))))-AVG(COALESCE(c.cantidad,0))) as cantidad,
           AVG(COALESCE(c.cantidad,0)) as cantidad_temporal,
           fc_descripcion_producto(a.codigo_producto) as descripcion
           FROM     
           inv_bodegas_movimiento_d as a
           JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
           AND (a.cantidad <> COALESCE(a.cantidad_recibida,0))
           LEFT JOIN (
                SELECT
                x.codigo_producto,
                SUM(x.cantidad)as cantidad
                FROM
                inv_bodegas_movimiento_tmp_d as x
                WHERE
                doc_tmp_id = ".$doc_tmp_id."
                AND usuario_id = ".UserGetUID()."
                GROUP BY x.codigo_producto
                ) as c ON (a.codigo_producto = c.codigo_producto)
			JOIN inventarios as d ON (a.codigo_producto = d.codigo_producto)
           AND (a.empresa_id = d.empresa_id)
           WHERE   
                    a.empresa_id = '".$empresa_id."'
            AND     a.prefijo = '".$prefijo."'
            AND     a.numero = ".$numero."
            AND     b.descripcion ILIKE '%%'    			
            ".$filtro."
            Group by a.codigo_producto";
            //limit ".$this->limit." OFFSET ".$this->offset."";
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
    $sql = " SELECT  count(*)
             FROM    existencias_bodegas as a,
                     inventarios_productos as b,
                     unidades as c,
                     inv_subclases_inventarios as e,
                     inv_moleculas as f,
                     inv_clases_inventarios as g,
                     inv_laboratorios as h
             WHERE   a.empresa_id = '$empresa_id'
             AND     a.centro_utilidad = '$centro_utilidad'
             AND     a.bodega = '$bodega'
             ".$aumento."
             ".$aumento2." 
             AND     b.codigo_producto = a.codigo_producto
             AND     c.unidad_id = b.unidad_id
             AND     b.grupo_id = e.grupo_id
             AND     b.subclase_id=e.subclase_id
             AND     b.clase_id=e.clase_id
             AND     e.molecula_id=f.molecula_id
             AND     b.grupo_id = g.grupo_id
             AND     b.clase_id=g.clase_id
             AND     g.laboratorio_id=h.laboratorio_id
             ";
             
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
     return $RETORNO;
  }
  
  function AgregarItem($doc_tmp_id,$codigo_producto,$cantidad,$total_costo,$iva,$bodegas_doc_id,$lote,$fecha_vencimiento,$localizacion)
		{
			$ClassDOC= new BodegasDocumentosComun($bodegas_doc_id);
			return $ClassDOC->AddItemDocTemporal($doc_tmp_id,$codigo_producto,$cantidad,$iva,$total_costo,UserGETUID(),$fecha_vencimiento,$lote,$localizacion);
		}
  
  
   function ItemEnMovimiento($doc_tmp_id,$item_id,$item_id_compras)
    {
       
	$sql  = "UPDATE inv_bodegas_movimiento_tmp_d ";
  $sql .= "SET ";
  $sql .= "item_id_compras = ".$item_id_compras."";
  $sql .= " Where ";
  $sql .= " item_id = ".$item_id." ";
	$sql .= " and doc_tmp_id = ".$doc_tmp_id." ";
	$sql .= " and usuario_id = ".UserGetUID()."; ";
	
	//print_r($sql);
        
      
			if(!$result = $this->ConexionBaseDatos($sql))
				return false;
	    else
         return true;
			
			$result->Close();
       
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